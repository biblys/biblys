<?php

namespace AppBundle\Controller;

use Biblys\Article\Type;
use Biblys\Exception\InvalidEmailAddressException;
use Biblys\Service\CurrentSite;
use Biblys\Service\Mailer;
use Biblys\Service\TemplateService;
use Exception;
use Framework\Controller;
use Model\Base\ArticleQuery;
use Model\Invitation;
use Model\Map\ArticleTableMap;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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

        $con = Propel::getWriteConnection(ArticleTableMap::DATABASE_NAME);
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
}