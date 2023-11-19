<?php

namespace Framework;

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\MetaTagsService;
use Biblys\Service\TemplateService;
use Exception;
use Model\Publisher;
use Opengraph\Writer;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Controller
{
    /**
     * Returns a Response with a rendered template.
     *
     * @deprecated Controller->render is deprecated. Use TemplateService->render instead.
     *
     * @param string $templatePath template file path
     * @param array $vars template variables
     *
     * @return Response a Response object containing the rendered template
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws PropelException
     * @throws Exception
     */
    public function render(string $templatePath, array $vars = []): Response
    {
        $config = Config::load();
        $request = Request::createFromGlobals();
        $currentSiteService = CurrentSite::buildFromConfig($config);
        $currentUserService = CurrentUser::buildFromRequestAndConfig($request, $config);
        $metaTagsService = new MetaTagsService(new Writer());
        $templateService = new TemplateService(
            config: $config,
            currentSiteService: $currentSiteService,
            currentUserService: $currentUserService,
            metaTagsService: $metaTagsService,
            request: $request,
        );
        return $templateService->render($templatePath, $vars);
    }

    /**
     * @param string $url
     * @param int $status
     * @return RedirectResponse
     * @deprecated Controller->redirect is deprecated. Use Symfony\Component\HttpFoundation\RedirectResponse instead.
     */
    public function redirect(string $url, int $status = 302): RedirectResponse
    {
        trigger_deprecation(
            "biblys/biblys",
            "2.59.0",
            "Controller->redirect is deprecated. Use Symfony\Component\HttpFoundation\RedirectResponse instead."
        );

        return new RedirectResponse($url, $status);
    }

    public function setOpengraphTags($tags = []): void
    {
        global $request;

        if (!isset($tags['type'])) {
            $tags['type'] = 'website';
        }

        if (!isset($tags['site_name'])) {
            global $_SITE;
            $tags['site_name'] = $_SITE->get('title');
        }

        if (!isset($tags['locale'])) {
            $tags['locale'] = 'fr_FR';
        }

        if (!isset($tags['url'])) {
            $tags['url'] = $request->getUri();
        }

        $request->attributes->set('opengraph_tags', $tags);
    }

    public function setTwitterCardsTags($tags): void
    {
        global $request;

        if (!isset($tags['card'])) {
            $tags['card'] = 'summary';
        }

        $request->attributes->set('twitter_cards_tags', $tags);
    }

    /**
     * Really generates an url from a route using the Routing component.
     *
     * @return string the generated url
     * @deprecated Calling Controller->generateUrl() is deprecated, inject UrlGenerator in the controller instead.
     */
    public function generateUrl(string $route, array $params = []): string
    {
        global $urlgenerator;

        trigger_deprecation(
            "biblys/biblys",
            "2.59.0",
            "Calling Controller->generateUrl() is deprecated, inject UrlGenerator in the controller instead."
        );

        return $urlgenerator->generate($route, $params);
    }

    public function getFormFactory(): FormFactoryInterface
    {
        return Forms::createFormFactoryBuilder()
            ->addExtension(new HttpFoundationExtension())
            ->getFormFactory();
    }

    /**
     * @param Request $request
     * @return CurrentUser
     * @throws PropelException
     */
    protected static function authUser(Request $request): CurrentUser
    {
        $currentUser = CurrentUser::buildFromRequest($request);

        if (!$currentUser->isAuthentified()) {
            throw new UnauthorizedHttpException("","Identification requise.");
        }

        return $currentUser;
    }

    /**
     * @param Request $request
     * @param string $errorMessage
     * @return CurrentUser
     * @throws PropelException
     * @throws Exception
     */
    protected static function authAdmin(
        Request $request,
        string $errorMessage = "Accès réservé aux administrateurs.",
    ): CurrentUser
    {
        $currentUser = self::authUser($request);
        $currentSite = CurrentSite::buildFromConfig(Config::load());

        if (!$currentUser->isAdminForSite($currentSite->getSite())) {
            throw new AccessDeniedHttpException($errorMessage);
        }

        return $currentUser;
    }

    /**
     * @param Request $request
     * @param Publisher|null $publisher
     * @return CurrentUser
     * @throws PropelException
     * @throws Exception
     */
    protected static function authPublisher(Request $request, ?Publisher $publisher): CurrentUser
    {
        $currentUser = self::authUser($request);

        $currentSite = CurrentSite::buildFromConfig(Config::load());
        if ($currentUser->isAdminForSite($currentSite->getSite())) {
            return $currentUser;
        }

        if ($publisher === null) {
            if ($currentUser->hasPublisherRight()) {
                return $currentUser;
            }

            throw new AccessDeniedHttpException("Vous n'avez pas l'autorisation de modifier un éditeur.");
        }

        if ($currentUser->hasRightForPublisher($publisher)) {
            return $currentUser;
        }

        // TODO: throw unauthorized exception (403)
        throw new AccessDeniedHttpException(
            sprintf("Vous n'avez pas l'autorisation de modifier l'éditeur %s", $publisher->getName())
        );
    }
}
