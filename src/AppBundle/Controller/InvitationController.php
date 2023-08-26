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
use League\Csv\CannotInsertRecord;
use League\Csv\Writer;
use Model\Article;
use Model\ArticleQuery;
use Model\Invitation;
use Model\InvitationQuery;
use Model\Map\InvitationTableMap;
use Model\Stock;
use Model\StockQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
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
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws CannotInsertRecord
     * @throws \League\Csv\Exception
     */
    public function createAction(
        Request         $request,
        CurrentSite     $currentSite,
        Mailer          $mailer,
        TemplateService $templateService,
        Session         $session,
        UrlGenerator    $urlGenerator
    ): Response|RedirectResponse
    {
        self::authAdmin($request);

        $recipientEmailsRaw = $request->request->get("email_addresses");
        $recipientEmails = explode("\r\n", $recipientEmailsRaw);
        if (count($recipientEmails) === 0) {
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

        $isManualMode = $request->request->getAlpha("mode") === "manual";
        $shouldSendEmail = $request->request->getAlpha("mode") === "send";
        $shouldWriteCSV = $request->request->getAlpha("mode") === "download";
        $allowsPreDownload = $request->request->getBoolean("allows_pre_download") ;

        if ($shouldWriteCSV) {
            $csv = Writer::createFromString();
            $csv->insertOne(["email", "code"]);
        }

        foreach ($recipientEmails as $recipientEmail) {
            $invitation = InvitationController::_createAndSendInvitations(
                currentSite: $currentSite,
                article: $article,
                recipientEmail: $recipientEmail,
                urlGenerator: $urlGenerator,
                templateService: $templateService,
                mailer: $mailer,
                session: $session,
                shouldSendEmail: $shouldSendEmail,
                isManualMode: $isManualMode,
                allowsPreDownload: $allowsPreDownload,
            );

            if ($shouldWriteCSV) {
                $csv->insertOne([$recipientEmail, $invitation->getCode()]);
            }
        }

        if ($shouldWriteCSV) {
            $response = new Response();
            $response->headers->set("Content-Type", "text/csv; charset=utf-8");
            $response->headers->set("Content-Disposition", "attachment; filename=invitations.csv");
            $response->setContent($csv);
            return $response;
        }

        return new RedirectResponse($urlGenerator->generate("invitation_list"));
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     */
    public function listAction(Request $request): Response
    {
        self::authAdmin($request);

        $invitations = InvitationQuery::create()
            ->orderByCreatedAt(Criteria::DESC)
            ->find();

        return $this->render("AppBundle:Invitation:list.html.twig", [
            "invitations" => $invitations,
        ]);
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
        $invitation = self::_getInvitationFromCode($currentSite, $code);

        $error = null;
        try {
            self::_validateInvitation($invitation);
            self::_validateArticleFromInvitation($currentSite, $currentUser, $invitation);
        } catch(UnauthorizedHttpException) {
        } catch (NotFoundHttpException | BadRequestHttpException $exception) {
            $error = $exception->getMessage();
        }

        return $templateService->render("AppBundle:Invitation:show.html.twig", [
            "articleTitle" => $invitation->getArticles()->getFirst()->getTitle(),
            "currentUser" => $currentUser,
            "code" => $invitation->getCode(),
            "error" => $error,
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
        $invitation = self::_getInvitationFromCode($currentSite, $code);
        self::_validateInvitation($invitation);
        self::_validateArticleFromInvitation($currentSite, $currentUser, $invitation);

        $invitation->setConsumedAt(new DateTime());

        $libraryItem = new Stock();
        $libraryItem->setSite($currentSite->getSite());
        $libraryItem->setArticle($invitation->getArticles()->getFirst());
        $libraryItem->setAxysAccountId($currentUser->getAxysAccount()->getId());
        $libraryItem->setAllowPredownload($invitation->getAllowsPreDownload());
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
            "{$invitation->getArticles()->getFirst()->getTitle()} a été ajouté à votre bibliothèque."
        );

        return new RedirectResponse("/pages/log_myebooks");
    }

    /**
     * @throws PropelException
     * @throws NotFoundHttpException
     */
    private static function _getInvitationFromCode(CurrentSite $currentSite, string $code): ?Invitation
    {
        $invitation = InvitationQuery::create()
            ->filterBySite($currentSite->getSite())
            ->findOneByCode($code);

        if ($invitation === null) {
            throw new NotFoundHttpException("Cette invitation n'existe pas.");
        }

        return $invitation;
    }

    /**
     * @throws PropelException
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    private static function _validateInvitation(Invitation $invitation): void
    {
        if ($invitation->getExpiresAt() <= new DateTime()) {
            throw new BadRequestHttpException("Cette invitation a expiré.");
        }

        if ($invitation->getConsumedAt() !== null) {
            throw new BadRequestHttpException("Cette invitation a déjà été utilisée.");
        }
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
        $articleId = $invitation->getArticles()->getFirst()->getId();

        $article = self::_getValidDownloadableArticle($currentSite, $articleId);

        $stock = StockQuery::create()
            ->filterBySite($currentSite->getSite())
            ->filterByArticle($article)
            ->findOneByAxysAccountId($currentUser->getAxysAccount()->getId());
        if ($stock) {
            throw new BadRequestHttpException("L'article {$article->getTitle()} est déjà dans votre bibliothèque.");
        }
    }

    /**
     * @throws PropelException
     */
    private static function _getValidDownloadableArticle(CurrentSite $currentSite, $articleId): Article
    {
        $article = ArticleQuery::create()
            ->filterForCurrentSite($currentSite)
            ->findOneById($articleId);

        if ($article === null) {
            throw new BadRequestHttpException("L'article $articleId n'existe pas.");
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
        return $article;
    }

    /**
     * @throws InvalidEmailAddressException
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     */
    private static function _createAndSendInvitations(
        CurrentSite     $currentSite,
        Article         $article,
        string          $recipientEmail,
        UrlGenerator    $urlGenerator,
        TemplateService $templateService,
        Mailer          $mailer,
        Session         $session,
        bool            $shouldSendEmail,
        bool            $isManualMode,
        bool            $allowsPreDownload,
    ): Invitation
    {
        $invitation = new Invitation();
        $invitation->setSite($currentSite->getSite());
        $invitation->addArticle($article);
        $invitation->setEmail($recipientEmail);
        $invitation->setCode(Invitation::generateCode());
        $invitation->setAllowsPreDownload($allowsPreDownload);
        $invitation->setExpiresAt(strtotime("+1 month"));

        $invitationUrl = $urlGenerator->generate("invitation_show", [
            "code" => $invitation->getCode()
        ], referenceType: UrlGeneratorInterface::ABSOLUTE_URL);
        $mailContent = $templateService->render(
            "AppBundle:Invitation:email.html.twig",
            [
                "articleTitle" => $article->getTitle(),
                "invitationUrl" => $invitationUrl,
                "expirationDate" => $invitation->getExpiresAt()->format("d/m/Y")
            ]
        );

        $con = Propel::getWriteConnection(InvitationTableMap::DATABASE_NAME);
        $con->beginTransaction();

        try {
            $invitation->save();
            if ($shouldSendEmail) {
                $mailer->send(
                    to: $recipientEmail,
                    subject: "Téléchargez « {$article->getTitle()} » en numérique",
                    body: $mailContent->getContent(),
                );
            }
            $con->commit();
        } catch (Exception $exception) {
            $con->rollBack();
            throw $exception;
        }

        if ($shouldSendEmail) {
            $session->getFlashBag()->add("success",
                "Une invitation pour {$article->getTitle()} a été envoyée à $recipientEmail"
            );
        }

        if ($isManualMode) {
            $session->getFlashBag()->add("success",
                "Une invitation à télécharger {$article->getTitle()} a été créée pour $recipientEmail"
            );
        }

        return $invitation;
    }
}