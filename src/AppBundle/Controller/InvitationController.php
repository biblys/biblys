<?php

namespace AppBundle\Controller;

use Biblys\Article\Type;
use Biblys\Exception\InvalidEmailAddressException;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\Mailer;
use Biblys\Service\Pagination;
use Biblys\Service\TemplateService;
use DateTime;
use Exception;
use Framework\Controller;
use InvalidArgumentException;
use League\Csv\CannotInsertRecord;
use League\Csv\Writer;
use Model\Article;
use Model\ArticleQuery;
use Model\Invitation;
use Model\InvitationQuery;
use Model\Map\InvitationTableMap;
use Model\Stock;
use Model\StockQuery;
use Model\User;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\Collection;
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
        Request         $request,
        CurrentSite     $currentSite,
        TemplateService $templateService,
    ): Response
    {
        self::authAdmin($request);

        $downloadableTypes = Type::getAllDownloadableTypes();
        $downloadbleTypeIds = array_map(function ($type) {
            return $type->getId();
        }, $downloadableTypes);

        $downloadableArticles = ArticleQuery::create()
            ->filterForCurrentSite($currentSite)
            ->filterByTypeId($downloadbleTypeIds)
            ->orderByTitleAlphabetic()
            ->find();
        return $templateService->renderResponse("AppBundle:Invitation:new.html.twig", [
            "downloadableArticles" => $downloadableArticles->getData(),
        ]);
    }

    /**
     * @throws CannotInsertRecord
     * @throws PropelException
     * @throws TransportExceptionInterface
     * @throws \League\Csv\Exception
     * @throws Exception
     */
    public function createAction(
        Request         $request,
        CurrentSite     $currentSite,
        Mailer          $mailer,
        TemplateService $templateService,
        Session         $session,
        UrlGenerator    $urlGenerator,
        CurrentUser $currentUser
    ): Response|RedirectResponse
    {
        $currentUser->authAdmin();

        $recipientEmailsRaw = $request->request->get("email_addresses");
        $recipientEmails = explode("\r\n", $recipientEmailsRaw);
        if (count($recipientEmails) === 0) {
            throw new BadRequestHttpException("Le champ adresse e-mail est obligatoire.");
        }

        $articleIds = $request->request->all("article_ids");
        $articles = ArticleQuery::create()->findById($articleIds);
        foreach ($articles->getData() as $article) {
            self::_validateDownloadableArticle($currentSite, $article);
        }

        $isManualMode = $request->request->getAlpha("mode") === "manual";
        $shouldSendEmail = $request->request->getAlpha("mode") === "send";
        $shouldWriteCSV = $request->request->getAlpha("mode") === "download";
        $allowsPreDownload = $request->request->getBoolean("allows_pre_download");

        if ($shouldWriteCSV) {
            $csv = Writer::createFromString();
            $csv->insertOne(["email", "code"]);
        }

        foreach ($recipientEmails as $recipientEmail) {
            if ($recipientEmail === "") {
                continue;
            }

            try {
                $invitation = InvitationController::_createAndSendInvitations(
                    currentSite: $currentSite,
                    articles: $articles,
                    recipientEmail: $recipientEmail,
                    urlGenerator: $urlGenerator,
                    templateService: $templateService,
                    mailer: $mailer,
                    session: $session,
                    shouldSendEmail: $shouldSendEmail,
                    isManualMode: $isManualMode,
                    allowsPreDownload: $allowsPreDownload,
                    request: $request,
                );

                if ($shouldWriteCSV) {
                    $csv->insertOne([$recipientEmail, $invitation->getCode()]);
                }
            } catch (Exception $exception) {
                $session->getFlashBag()->add(
                    "error",
                    "La création de l'invitation pour $recipientEmail a échoué : {$exception->getMessage()}"
                );
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
     * @throws Exception
     */
    public function listAction(
        Request     $request,
        CurrentSite $currentSite,
        CurrentUser $currentUser,
        TemplateService $templateService,
    ): Response
    {
        $currentUser->authAdmin();

        $invitationsQuery = InvitationQuery::create()
            ->filterBySite($currentSite->getSite())
            ->orderByUpdatedAt(Criteria::DESC);

        try {
            $pageNumber = (int) $request->query->get("p", 0);
            $invitationTotalCount = $invitationsQuery->count();
            $invitationsPerPage = 100;
            $pagination = new Pagination($pageNumber, $invitationTotalCount, $invitationsPerPage);
        } catch (InvalidArgumentException $exception) {
            throw new BadRequestHttpException($exception->getMessage(), $exception);
        }

        $invitations = $invitationsQuery
            ->setLimit($pagination->getLimit())
            ->setOffset($pagination->getOffset())
            ->find();

        return $templateService->renderResponse("AppBundle:Invitation:list.html.twig", [
            "invitations" => $invitations,
            "pages" => $pagination,
            "total" => $invitationTotalCount,
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     */
    public function showAction(
        CurrentSite     $currentSite,
        CurrentUser     $currentUser,
        TemplateService $templateService,
        string          $code
    ): Response
    {
        if (!$currentUser->isAuthentified()) {
            return $templateService->renderResponse(
                "AppBundle:Invitation:show-for-anonymous-user.html.twig",
            );
        }

        $invitation = self::_getInvitationFromCode($currentSite, $code);
        $error = null;
        try {
            self::_validateInvitation($invitation);
            self::_validateArticlesFromInvitation($currentSite, $invitation);
        } catch (NotFoundHttpException|BadRequestHttpException $exception) {
            $error = $exception->getMessage();
        }

        return $templateService->renderResponse("AppBundle:Invitation:show.html.twig", [
            "currentUser" => $currentUser,
            "invitation" => $invitation,
            "error" => $error,
        ]);
    }

    /**
     * @throws PropelException
     */
    public function consumeAction(
        Request     $request,
        CurrentSite $currentSite,
        CurrentUser $currentUser,
        Session     $session,
    ): RedirectResponse
    {
        self::authUser($request);

        $code = $request->request->get("code");
        $invitation = self::_getInvitationFromCode($currentSite, $code);
        self::_validateInvitation($invitation);
        self::_validateArticlesFromInvitation($currentSite, $invitation);
        $articles = self::_getArticlesNotAlreadyInLibrary(
            $currentSite, $currentUser, $invitation, $session
        );
        $invitation->setConsumedAt(new DateTime());

        $con = Propel::getWriteConnection(InvitationTableMap::DATABASE_NAME);
        $con->beginTransaction();

        try {
            foreach ($articles as $article) {
                self::_addArticleToUserLibrary(
                    article: $article,
                    user: $currentUser->getUser(),
                    allowsPreDownload: $invitation->getAllowsPreDownload(),
                    currentSite: $currentSite,
                    session: $session
                );
            }
            $invitation->save();
        } catch (Exception $exception) {
            $con->rollBack();
            throw $exception;
        }

        $con->commit();

        return new RedirectResponse("/pages/log_myebooks");
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function deleteAction(
        Session $session,
        CurrentUser $currentUser,
        int     $id,
    ): RedirectResponse
    {
        $currentUser->authAdmin();

        $invitation = InvitationQuery::create()->findPk($id);
        if ($invitation === null) {
            throw new NotFoundHttpException("Cette invitation n'existe pas.");
        }

        $invitation->delete();

        $session->getFlashBag()->add("success",
            "L'invitation pour {$invitation->getEmail()} a été supprimée."
        );

        return new RedirectResponse("/admin/invitations");
    }

    /**
     * @throws PropelException
     * @throws NotFoundHttpException
     */
    private static function _getInvitationFromCode(CurrentSite $currentSite, string $code): ?Invitation
    {
        return InvitationQuery::create()
            ->filterBySite($currentSite->getSite())
            ->findOneByCode($code);
    }

    /**
     * @throws PropelException
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    private static function _validateInvitation(?Invitation $invitation): void
    {
        if ($invitation === null) {
            throw new NotFoundHttpException("Cette invitation n'existe pas.");
        }

        if ($invitation->getExpiresAt() <= new DateTime()) {
            throw new BadRequestHttpException("Cette invitation a expiré.");
        }

        if ($invitation->getConsumedAt() !== null) {
            throw new BadRequestHttpException("Cette invitation a déjà été utilisée.");
        }
    }

    /**
     * @throws PropelException
     * @throws BadRequestHttpException
     */
    private static function _validateArticlesFromInvitation(
        CurrentSite $currentSite,
        Invitation  $invitation,
    ): void
    {
        $articles = $invitation->getArticles();
        foreach ($articles as $article) {
            self::_validateDownloadableArticle($currentSite, $article);
        }
    }

    /**
     * @throws PropelException
     */
    private static function _validateDownloadableArticle(
        CurrentSite $currentSite,
        ?Article    $article
    ): void
    {
        if ($article === null) {
            throw new BadRequestHttpException("L'article n'existe pas.");
        }

        $publisherFilterOption = $currentSite->getOption("publisher_filter") ?? "";
        $publisherFilterIds = explode(",", $publisherFilterOption);
        if (!in_array($article->getPublisherId(), $publisherFilterIds)) {
            throw new BadRequestHttpException(
                "Ce site n'est pas autorisé à distribuer les articles de {$article->getPublisher()->getName()}."
            );
        }

        $downloadablePublishersOption = $currentSite->getOption("downloadable_publishers") ?? "";
        $downloadablePublishersId = explode(",", $downloadablePublishersOption);
        if (!in_array($article->getPublisherId(), $downloadablePublishersId)) {
            throw new BadRequestHttpException(
                "Le téléchargement des articles de {$article->getPublisher()->getName()} n'est pas autorisé sur ce site."
            );
        }

        if ($article->getType()->isDownloadable() === false) {
            throw new BadRequestHttpException("L'article {$article->getTitle()} n'est pas téléchargeable.");
        }
    }

    /**
     * @throws PropelException
     * @return Article[]
     */
    private static function _getArticlesNotAlreadyInLibrary(
        CurrentSite $currentSite,
        CurrentUser $currentUser,
        Invitation  $invitation,
        Session     $session,
    ): array
    {
        return array_filter($invitation->getArticles()->getData(), function ($article) use
        ($currentSite, $currentUser, $session) {
            $stock = StockQuery::create()
                ->filterBySite($currentSite->getSite())
                ->filterByArticle($article)
                ->findOneByUserId($currentUser->getUser()->getId());

            if ($stock) {
                $session->getFlashBag()->add(
                    "warning",
                    "{$article->getTitle()} était déjà dans votre bibliothèque."
                );
            }

            return $stock === null;
        });
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
        Collection      $articles,
        string          $recipientEmail,
        UrlGenerator    $urlGenerator,
        TemplateService $templateService,
        Mailer          $mailer,
        Session         $session,
        bool            $shouldSendEmail,
        bool            $isManualMode,
        bool            $allowsPreDownload,
        Request         $request,
    ): Invitation
    {
        $invitation = new Invitation();
        $invitation->setSite($currentSite->getSite());
        $invitation->setArticles($articles);
        $invitation->setEmail($recipientEmail);
        $invitation->setCode(Invitation::generateCode());
        $invitation->setAllowsPreDownload($allowsPreDownload);
        $invitation->setExpiresAt(strtotime("+1 month"));

        $articlesTitle = "« {$articles->getFirst()->getTitle()} »";
        if ($articles->count() > 1) {
            $articlesTitle .= " et " . ($articles->count() - 1) . " autres";
        }

        $invitationRelativeUrl = $urlGenerator->generate("invitation_show", [
            "code" => $invitation->getCode()
        ]);
        $invitationUrl = $request->getSchemeAndHttpHost().$invitationRelativeUrl;
        $mailContent = $templateService->renderResponse(
            "AppBundle:Invitation:email.html.twig",
            [
                "articleTitle" => $articlesTitle,
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
                    subject: "Téléchargez $articlesTitle en numérique",
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
                "Une invitation pour $articlesTitle a été envoyée à $recipientEmail"
            );
        }

        if ($isManualMode) {
            $session->getFlashBag()->add("success",
                "Une invitation à télécharger $articlesTitle a été créée pour $recipientEmail"
            );
        }

        return $invitation;
    }

    /**
     * @throws PropelException
     */
    private function _addArticleToUserLibrary(
        mixed       $article,
        User        $user,
        bool        $allowsPreDownload,
        CurrentSite $currentSite,
        Session     $session
    ): void
    {
        $libraryItem = new Stock();
        $libraryItem->setArticle($article);
        $libraryItem->setUser($user);
        $libraryItem->setAllowPredownload($allowsPreDownload);
        $libraryItem->setSite($currentSite->getSite());
        $libraryItem->setSellingPrice(0);
        $libraryItem->setSellingDate(new DateTime());
        $libraryItem->save();

        $session->getFlashBag()->add(
            "success",
            "{$article->getTitle()} a été ajouté à votre bibliothèque."
        );
    }
}