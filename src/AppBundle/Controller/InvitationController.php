<?php

namespace AppBundle\Controller;

use Biblys\Article\Type;
use Biblys\Exception\InvalidEmailAddressException;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\Mailer;
use Biblys\Service\TemplateService;
use DateTime;
use Exception;
use Framework\Controller;
use Model\ArticleQuery;
use Model\Invitation;
use Model\InvitationQuery;
use Model\Map\ArticleTableMap;
use Model\Map\InvitationTableMap;
use Model\Stock;
use Model\StockQuery;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class InvitationController extends Controller
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function newAction(
        Request $request,
        CurrentSite $currentSite,
        TemplateService $templateService,
    ): Response
    {
        self::authAdmin($request);

        $downloadableTypes = Type::getAllDownloadableTypes();
        $downloadbleTypeIds = array_map(function($type) {
            return $type->getId();
        }, $downloadableTypes);

        $downloadableArticles = ArticleQuery::create()
            ->filterForCurrentSite($currentSite)
            ->filterByTypeId($downloadbleTypeIds)
            ->orderByTitleAlphabetic()
            ->find();
        return $templateService->render("AppBundle:Invitation:new.html.twig", [
            "downloadableArticles" => $downloadableArticles->getData(),
        ]);
    }

    /**
     * @throws InvalidEmailAddressException
     * @throws LoaderError
     * @throws PropelException
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     */
    public function createAction(
        Request         $request,
        CurrentSite     $currentSite,
        Mailer          $mailer,
        TemplateService $templateService,
        Session         $session,
        UrlGenerator    $urlGenerator
    ): RedirectResponse
    {
        self::authAdmin($request);

        $recipientEmail = $request->request->get("email_address");
        if ($recipientEmail === null) {
            throw new BadRequestHttpException("Le champ adresse e-mail est obligatoire.");
        }

        $articleId = $request->request->get("article_id");
        $article = ArticleQuery::create()->filterForCurrentSite($currentSite)->findOneById($articleId);
        if ($article === null) {
            throw new BadRequestHttpException("L'article $articleId n'existe pas.");
        }

        if ($article->getType()->isDownloadable() === false) {
            throw new BadRequestHttpException("L'article demandé n'est pas téléchargeable.");
        }

        $invitation = new Invitation();
        $invitation->setSite($currentSite->getSite());
        $invitation->setArticle($article);
        $invitation->setEmail($recipientEmail);
        $invitation->setCode(Invitation::generateCode());
        $invitation->setExpiresAt(strtotime("+1 month"));

        $invitationUrl = $urlGenerator->generate("invitation_show", [
            "code" => $invitation->getCode()
        ], referenceType: UrlGeneratorInterface::ABSOLUTE_URL);
        $mailContent = $templateService->renderFromString("
            <p>Bonjour,</p>
            <p>
                Vous avez reçu une invitation à télécharger 
                <strong>{{ articleTitle }}</strong> 
                en numérique.<br /> 
                Suivez le lien ci-dessous pour l’ajouter à votre bibliothèque numérique 
                <strong>{{ siteTitle }}</strong>
                d’où vous pourrez le télécharger à volonté dans le format de votre choix.
            </p>
            <p>
                <a href=\"{{ invitationUrl }}\">{{ invitationUrl }}</a>
            </p>
            <p>
                Notez que ce lien n'est valable qu'une seule fois 
                et expirera le {{ expirationDate }}. 
            </p>
            <p>
                ---<br />
                {{ siteTitle }} - Propulsé par Biblys
            </p>
        ", [
            "articleTitle" => $article->getTitle(),
            "siteTitle" => $currentSite->getTitle(),
            "invitationUrl" => $invitationUrl,
            "expirationDate" => $invitation->getExpiresAt()->format("d/m/Y")
        ]);

        $con = Propel::getWriteConnection(InvitationTableMap::DATABASE_NAME);
        $con->beginTransaction();

        try {
            $invitation->save();
            $mailer->send(
                to: $recipientEmail,
                subject: "Téléchargez « {$article->getTitle()} » en numérique",
                body: $mailContent->getContent(),
            );
            $con->commit();
        } catch (Exception $exception) {
            $con->rollBack();
            throw $exception;
        }

        $session->getFlashBag()->add("success",
            "Une invitation pour {$article->getTitle()} a été envoyée à $recipientEmail"
        );

        return new RedirectResponse($urlGenerator->generate("invitation_new"));
    }

    public function listAction(UrlGenerator $urlGenerator): RedirectResponse
    {
        return new RedirectResponse($urlGenerator->generate("invitation_new"));
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     */
    public function showAction(
        CurrentSite $currentSite,
        CurrentUser $currentUser,
        TemplateService $templateService,
        string $code
    ): Response
    {
        $invitation = self::_getValidInvitationFromCode($currentSite, $code);
        self::_validateArticleFromInvitation($currentSite, $currentUser, $invitation);

        return $templateService->render("AppBundle:Invitation:show.html.twig", [
            "articleTitle" => $invitation->getArticle()->getTitle(),
            "currentUser" => $currentUser,
        ]);
    }

    /**
     * @throws PropelException
     */
    public function consumeAction(
        Request $request,
        CurrentSite $currentSite,
        CurrentUser $currentUser,
        Session $session,
    ): RedirectResponse
    {
        self::authUser($request);

        $code = $request->request->get("code");
        $invitation = self::_getValidInvitationFromCode($currentSite, $code);
        self::_validateArticleFromInvitation($currentSite, $currentUser, $invitation);

        $invitation->setConsumedAt(new DateTime());

        $libraryItem = new Stock();
        $libraryItem->setSite($currentSite->getSite());
        $libraryItem->setArticle($invitation->getArticle());
        $libraryItem->setAxysAccountId($currentUser->getAxysAccount()->getId());
        $libraryItem->setSellingPrice(0);
        $libraryItem->setSellingDate(new DateTime());

        $con = Propel::getWriteConnection(InvitationTableMap::DATABASE_NAME);
        $con->beginTransaction();

        try {
            $invitation->save();
            $libraryItem->save();
        } catch (Exception $exception) {
            $con->rollBack();
            throw $exception;
        }

        $con->commit();

        $session->getFlashBag()->add(
            "success",
            "{$invitation->getArticle()->getTitle()} a été ajouté à votre bibliothèque."
        );

        return new RedirectResponse("/pages/log_myebooks");
    }

    /**
     * @throws PropelException
     */
    private static function _getValidInvitationFromCode(CurrentSite $currentSite, string $code): Invitation
    {
        $invitation = InvitationQuery::create()
            ->filterBySite($currentSite->getSite())
            ->findOneByCode($code);

        if ($invitation === null) {
            throw new NotFoundHttpException("Cette invitation n'existe pas.");
        }

        if ($invitation->getExpiresAt() <= new DateTime()) {
            throw new BadRequestHttpException("Cette invitation a expiré.");
        }

        if ($invitation->getConsumedAt() !== null) {
            throw new BadRequestHttpException("Cette invitation a déjà été utilisée.");
        }

        return $invitation;
    }

    /**
     * @throws PropelException
     */
    private static function _validateArticleFromInvitation(
        CurrentSite $currentSite,
        CurrentUser $currentUser,
        Invitation  $invitation,
    ): void
    {
        $article = ArticleQuery::create()
            ->filterForCurrentSite($currentSite)
            ->findOneById($invitation->getArticleId());

        if ($article === null) {
            throw new BadRequestHttpException("L'article {$invitation->getArticleId()} n'existe pas.");
        }

        $downloadablePublishersOptions = $currentSite->getOption("downloadable_publishers") ?? "";
        $downloadablePublishersId = explode(",", $downloadablePublishersOptions);
        if (!in_array($article->getPublisherId(), $downloadablePublishersId)) {
            throw new BadRequestHttpException(
                "Le téléchargement des articles de {$article->getPublisher()->getName()} n'est pas autorisé sur ce site."
            );
        }

        if ($article->getType()->isDownloadable() === false) {
            throw new BadRequestHttpException("L'article {$article->getTitle()} n'est pas téléchargeable.");
        }

        $stock = StockQuery::create()
            ->filterBySite($currentSite->getSite())
            ->filterByArticle($article)
            ->findOneByAxysAccountId($currentUser->getAxysAccount()->getId());
        if ($stock) {
            throw new BadRequestHttpException("L'article {$article->getTitle()} est déjà dans votre bibliothèque.");
        }
    }
}