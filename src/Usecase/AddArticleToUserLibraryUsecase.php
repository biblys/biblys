<?php
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


namespace Usecase;

use Biblys\Exception\InvalidEmailAddressException;
use Biblys\Service\CurrentSite;
use Biblys\Service\Mailer;
use DateTime;
use Model\Article;
use Model\Stock;
use Model\StockQuery;
use Model\User;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AddArticleToUserLibraryUsecase
{
    public function __construct(private readonly Mailer $mailer)
    {
    }

    /**
     * @throws BusinessRuleException
     * @throws PropelException
     * @throws InvalidEmailAddressException
     * @throws TransportExceptionInterface
     */
    public function execute(
        CurrentSite  $currentSite,
        UrlGenerator $urlGenerator,
        User         $user,
        ?Article     $article = null,
        array        $items = [],
        bool         $allowsPreDownload = false,
        bool         $sendEmail = false,
    ): void
    {
        if ($article) {
            $newItem = new Stock();
            $newItem->setSite($currentSite->getSite());
            $newItem->setArticle($article);
            $items[] = $newItem;
        }

        $addedArticleTitles = [];
        foreach ($items as $item) {
            $article = $item->getArticle();
            $publisher = $article->getPublisher();
            $allowedPublishers = $currentSite->getOption("downloadable_publishers");
            $allowedPublisherIds = explode(",", $allowedPublishers);
            if (!in_array($publisher->getId(), $allowedPublisherIds)) {
                throw new BusinessRuleException(
                    "Les articles de l'éditeur {$publisher->getName()} ne peuvent pas être téléchargés sur ce site."
                );
            }

            if (!$article->getType()->isDownloadable()) {
                throw new BusinessRuleException(
                    "L'article {$article->getTitle()} n'est pas téléchargeable."
                );
            }

            $articleInLibrary = StockQuery::create()
                ->filterBySite($currentSite->getSite())
                ->filterByArticle($article)
                ->filterByUser($user)
                ->findOne();
            if ($articleInLibrary) {
                continue;
            }

            if ($item->getUser() !== null) {
                throw new BusinessRuleException(
                    "L'exemplaire {$item->getId()} est déjà dans une bibliothèque."
                );
            }

            $this->_addItemToUserLibrary($item, $user, $allowsPreDownload);
            $addedArticleTitles[] = $article->getTitle();
        }

        if ($sendEmail) {
            $eLibraryUrl = $urlGenerator->generate("user_library", referenceType: UrlGeneratorInterface::ABSOLUTE_URL);
            $subject = 'De nouveaux livres numériques disponibles dans votre bibliothèque.';
            $message = '
                    <p>Bonjour,</p>
                    <p>Les livres numériques suivants ont été ajoutés à <a href="' . $eLibraryUrl . '">votre bibliothèque numérique</a> :</p>
                    <ul><li>' . implode('</li><li>', $addedArticleTitles) . '</li></ul>
                    <p>Vous pouvez les télécharger à volonté depuis notre site, dans tous les formats disponibles. Vous pourrez également profiter gratuitement des mises à jour de ces fichiers si de nouvelles versions sont proposées.</p>
                    <p>Vous trouverez également dans votre bibliothèque numérique de l\'aide pour télécharger et lire ces fichiers. En cas de difficulté, n\'hésitez pas à nous solliciter en répondant à ce message.</p>
                    <p><a href="' . $eLibraryUrl . '"><strong>Accéder à votre bibliothèque numérique</strong></a></p>
                    <p>NB : Ces fichiers vous sont volontairement proposés sans dispositif de gestion des droits numériques (DRM ou GDN). Nous vous invitons à les transmettre à vos proches si vous souhaitez les leur faire découvrir, comme vous le feriez avec un livre papier, mais nous vous prions de ne pas les diffuser plus largement, par respect pour l\'auteur et l\'éditeur.</p>
                ';
            $this->mailer->send($user->getEmail(), $subject, $message);
        }
    }

    /**
     * @throws PropelException
     */
    private function _addItemToUserLibrary(
        Stock $libraryItem,
        User  $user,
        bool  $allowsPreDownload,
    ): void
    {
        $libraryItem->setUser($user);
        $libraryItem->setAllowPredownload($allowsPreDownload);
        $libraryItem->setSellingDate(new DateTime());
        $libraryItem->save();
    }
}