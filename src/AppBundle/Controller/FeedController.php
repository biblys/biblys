<?php
/*
 * Copyright (C) 2025 Clément Latzarus
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


namespace AppBundle\Controller;

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUrlService;
use Biblys\Service\Images\ImagesService;
use Biblys\Service\TemplateService;
use DateTime;
use Framework\Controller;
use Laminas\Feed\Writer\Feed;
use Model\Article;
use Model\ArticleQuery;
use Model\Post;
use Model\PostQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class FeedController extends Controller
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function indexAction(CurrentSite $currentSite, TemplateService $templateService): Response
    {
        return $templateService->renderResponse("AppBundle:Feed:index.html.twig", [
            "domain" => $currentSite->getSite()->getDomain(),
        ]);
    }

    /**
     * @throws PropelException
     */
    public function blogAction(
        CurrentSite $currentSite,
        CurrentUrlService $currentUrl,
        UrlGenerator $urlGenerator,
        ImagesService $imagesService,
    ): Response
    {
        $posts = PostQuery::create()
            ->filterByStatus(1)
            ->filterByDate(new DateTime("now"), Criteria::LESS_EQUAL)
            ->orderByDate(Criteria::DESC)
            ->limit(15)
            ->find();

        $feed = new Feed();
        $feed->setTitle("{$currentSite->getTitle()} · Actualités");
        $feed->setGenerator("Biblys (https://biblys.org)");
        $feed->setDescription("Les derniers billets du blog");
        $feed->setLink("https://{$currentSite->getSite()->getDomain()}");
        $feed->setFeedLink($currentUrl->getAbsoluteUrl(), "rss");

        if ($posts->count() > 0) {
            $feed->setDateModified($posts->getFirst()->getDate());
        }

        /** @var Post $post */
        foreach ($posts as $post) {
            if (empty($post->getContent())) {
                continue;
            }

            $imageHtml = "";
            if ($imagesService->imageExistsFor($post)) {
                $imageHtml = <<<HTML
<img src="{$imagesService->getImageUrlFor($post)}" alt="" role="presentation" />
HTML;
            }

            $entry = $feed->createEntry();
            $entry->setTitle($post->getTitle());
            $entry->setLink($urlGenerator->generate("post_show", ["slug" => $post->getUrl()], UrlGeneratorInterface::ABSOLUTE_URL));
            $entry->setDateCreated($post->getDate());
            $entry->setContent($imageHtml.$post->getContent());
            $feed->addEntry($entry);
        }

        return new Response($feed->export("rss"), 200, ["Content-Type" => "application/rss+xml"]);
    }

    /**
     * @throws PropelException
     */
    public function catalogAction(
        CurrentSite $currentSite,
        CurrentUrlService $currentUrl,
        UrlGenerator $urlGenerator,
        ImagesService $imagesService,
    ): Response
    {
        $feed = new Feed();
        $feed->setTitle("{$currentSite->getTitle()} · Parutions");
        $feed->setGenerator("Biblys (https://biblys.org)");
        $feed->setDescription("Les derniers articles du catalogue");
        $feed->setLink("https://{$currentSite->getSite()->getDomain()}");
        $feed->setFeedLink($currentUrl->getAbsoluteUrl(), "rss");

        $articles = ArticleQuery::create()
            ->filterByPubdate(new DateTime("now"), Criteria::LESS_EQUAL)
            ->orderByPubdate(Criteria::DESC)
            ->limit(15)
            ->find();

        if ($articles->count() > 0) {
            $feed->setDateModified($articles->getFirst()->getPubdate());
        }

        /** @var Article $article */
        foreach ($articles as $article) {
            if (empty($article->getSummary())) {
                continue;
            }

            $imageHtml = "";
            if ($imagesService->imageExistsFor($article)) {
                $imageHtml = <<<HTML
<img src="{$imagesService->getImageUrlFor($article)}" alt="" role="presentation" />
HTML;
            }

            $entry = $feed->createEntry();
            $entry->setTitle($article->getTitle());
            $entry->setLink($urlGenerator->generate("article_show", ["slug" => $article->getUrl()], UrlGeneratorInterface::ABSOLUTE_URL));
            $entry->setDateCreated($article->getPubdate());
            $entry->setContent($imageHtml.$article->getSummary());
            $feed->addEntry($entry);
        }

        return new Response($feed->export("rss"), 200, ["Content-Type" => "application/rss+xml"]);
    }
}