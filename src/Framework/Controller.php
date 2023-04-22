<?php

namespace Framework;

use Biblys\Isbn\Isbn as Isbn;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUrlService;
use Biblys\Service\CurrentUser;
use Cart;
use Media;
use Model\Article;
use Model\Publisher;
use Propel\Runtime\Exception\PropelException;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\DebugExtension;
use Twig\RuntimeLoader\FactoryRuntimeLoader;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Visitor;

class Controller
{
    /**
     * Returns a Response with a rendered template.
     *
     * @param string $templatePath template file path
     * @param array $vars template variables
     *
     * @return Response a Response object containing the rendered template
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws PropelException
     */
    public function render(string $templatePath, array $vars = []): Response
    {
        global $site, $request;
        $config = Config::load();

        $currentUserService = CurrentUser::buildFromRequestAndConfig($request, $config);
        $currentSiteService = CurrentSite::buildFromConfig($config);
        $currentUserIsAdmin = $currentUserService->isAdminForSite($currentSiteService->getSite());

        //** Twig custom functions **//

        $functions = [];

        $functions[] = new TwigFunction("get_assets", function ($fileType) use(
            $config,
            $currentUserIsAdmin
        ) {
            $assets = loadEncoreAssets($config->get("environment"), $fileType);

            if ($currentUserIsAdmin) {
                $assets = array_merge(
                    $assets,
                    loadEncoreAssets($config->get("environment"), $fileType, "admin")
                );
            }

            if ($fileType === "css") {
                $assets = array_map(function ($asset) {
                    return explode(":", $asset)[1];
                }, $assets);
            }

            return array_unique($assets);
        });

        // return relative url for a route
        $functions[] = new TwigFunction('path', function ($route, $vars = []) {
            $container = require __DIR__."/../container.php";
            $urlGenerator = $container->get("url_generator");
            return $urlGenerator->generate($route, $vars);
        });

        // return absolute url for a route
        $functions[] = new TwigFunction('url', function ($route, $vars = []) {
            global $urlgenerator, $site;

            return 'https://' .$site->get('domain').$urlgenerator->generate($route, $vars);
        });

        // returns share buttons for url
        $functions[] = new TwigFunction('share_buttons', function ($url, $message = '', $options = []) {
            return share_buttons($url, $message, $options);
        });

        $functions[] = new TwigFunction('asset', function ($url) {
            $hash = substr(md5(BIBLYS_VERSION), 0, 8);
            return $url."?".$hash;
        });

        $functions[] = new TwigFunction("cart_status", function() use($currentUserService) {
            $cart = $currentUserService->getCart();
            if (!$cart) {
                return Cart::getOneLineEmpty();
            }

            return Cart::buildOneLine($cart->getCount(), $cart->getAmount());
        });

        //** Twig custom filters **//

        $filters = [];

        // authors
        $filters[] = new TwigFilter('authors', function ($authors) {
            $authors = explode(',', $authors);
            $count = count($authors);

            if ($count > 2) {
                return 'COLLECTIF';
            }

            if ($count == 2) {
                return $authors[0].' & '.$authors[1];
            }

            return $authors[0];
        });

        $filters[] = new TwigFilter('coverUrl', function (Article $article) {
            $media = new Media("article", $article->getId());
            return $media->getUrl();
        });

        $filters[] = new TwigFilter('currency', function ($amount, $cents = false) {
            return currency($amount, $cents);
        });

        // date
        $filters[] = new TwigFilter('date', function ($date, $format = 'd/m/Y') {
            return _date($date, $format);
        });

        // price
        $filters[] = new TwigFilter('price', function ($price, $currency = null, $decimals = 2) {
            if ($currency == 'EUR') {
                return number_format(round($price / 100, 6), $decimals, ',', '&#8239;').'&nbsp;&euro;';
            }

            return $price / 100;
        });

        // pluralize
        $filters[] = new TwigFilter('pluralize', function ($text, $number) {
            if ($number == 1 || $number == 0) {
                return $text;
            }

            return $text.'s';
        });

        // truncate
        $filters[] = new TwigFilter('truncate', function ($text, $length, $ellipsis = '…') {
            return truncate(strip_tags($text), $length, $ellipsis);
        });

        // isbn
        $filters[] = new TwigFilter('isbn', function ($ean) {
            return Isbn::convertToIsbn13($ean);
        });

        // Forms
        $defaultFormTheme = 'AppBundle:Main:_form_bootstrap_layout.html.twig';

        // Custom template loader
        $currentSite = CurrentSite::buildFromConfig($config);
        $filesystem = new Filesystem();
        $loader = new TemplateLoader($currentSite, $filesystem);

        // Load Twig
        if ($site->get('environment') == 'dev') {
            $twig = new Environment($loader, ['strict_variables' => true]);
        } else {
            $twig = new Environment($loader, ['strict_variables' => true, 'debug' => true]);
            $twig->addExtension(new DebugExtension());
        }

        // CRSF
        $session = new Session();

        // Forms
        $formEngine = new TwigRendererEngine([$defaultFormTheme], $twig);
        $formRenderer = new FormRenderer($formEngine, new CsrfTokenManager());
        $twig->addExtension(new FormExtension());
        $runtimeLoader = new FactoryRuntimeLoader(
            [
                FormRenderer::class => function () use ($formRenderer) {
                    return $formRenderer;
                },
            ]
        );
        $twig->addRuntimeLoader($runtimeLoader);

        $currentUrlService = new CurrentUrlService($request);
        $trackers = $this->_getAnalyticsTrackers($config);

        // Global variables
        $app = [
            "currentSite" => $currentSite,
            "currentUrl" => $currentUrlService,
            "currentUser" => $currentUserService,
            'request' => $request,
            'user' => new Visitor($request),
            'session' => $session,
            'site' => $site,
            "trackers" => $trackers,
        ];
        $twig->addGlobal('app', $app);

        // Import functions
        foreach ($functions as $function) {
            $twig->addFunction($function);
        }

        // Import filters
        foreach ($filters as $filter) {
            $twig->addFilter($filter);
        }

        // Load template file
        $template = $twig->load($templatePath);

        // Render template
        $rendered = $template->render($vars);

        return new Response($rendered);
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

    /**
     * Set the page title as a Request attribute.
     *
     * @param string $title page title
     * @deprecated Calling Controller->setPageTitle is deprecated,
     *             use {% block title %} in template
     *             or $request->attributes->set("page_title") instead.
     */
    public function setPageTitle(string $title): void
    {
        global $request;

        trigger_deprecation(
            "biblys/biblys",
            "2.53.1",
            "Calling Controller->setPageTitle is deprecated, use {% block title %} in template or \$request->attributes->set(\"page_title\") instead."
        );

        $request->attributes->set('page_title', $title);
    }

    public function setOpengraphTags($tags = []): void
    {
        global $request;

        if (!isset($tags['type'])) {
            $tags['type'] = 'website';
        }

        if (!isset($tags['site_name'])) {
            global $site;
            $tags['site_name'] = $site->get('title');
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

    /**
     * @param Config $config
     * @return array
     */
    public function _getAnalyticsTrackers(Config $config): array
    {
        $trackers = [];


        if ($config->get("matomo.site_id")) {
            $trackers["matomo"] = [
                "domain" => $config->get("matomo.domain"),
                "site_id" => $config->get("matomo.site_id"),
            ];

            if ($config->get("matomo.secondary_domain")) {
                $trackers["matomo"]["secondary_domain"] = $config->get("matomo.secondary_domain");
            }
        }

        if ($config->get("umami")) {
            $trackers["umami"] = [
                "website_id" =>  $config->get("umami.website_id"),
            ];
        }

        return $trackers;
    }
}
