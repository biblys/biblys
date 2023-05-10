<?php

namespace Biblys\Service;

use Biblys\Isbn\Isbn;
use Biblys\Legacy\LegacyCodeHelper;
use Cart;
use Exception;
use Framework\Controller;
use Framework\TemplateLoader;
use Media;
use Model\Article;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
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

class TemplateService
{
    private Config $config;
    private CurrentSite $currentSiteService;
    private CurrentUser $currentUserService;

    public function __construct(
        Config $config,
        CurrentSite $currentSiteService,
        CurrentUser $currentUserService,
    )
    {
        $this->config = $config;
        $this->currentSiteService = $currentSiteService;
        $this->currentUserService = $currentUserService;
    }

    /**
     * @param string $templatePath
     * @param array $vars
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function render(string $templatePath, array $vars): Response
    {
        global $request;

        $config = $this->config;
        $currentSite = $this->currentSiteService->getSite();
        $currentUserService = $this->currentUserService;
        $currentUserIsAdmin = $currentUserService->isAdminForSite($currentSite);

        //** Twig custom functions **//

        $functions = [];

        $functions[] = new TwigFunction("get_assets", function ($fileType) use ($config, $currentUserIsAdmin) {
            $assets = loadEncoreAssets($this->config->get("environment"), $fileType);

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
            $container = require __DIR__ . "/../../container.php";
            $urlGenerator = $container->get("url_generator");
            return $urlGenerator->generate($route, $vars);
        });

        // return absolute url for a route
        $functions[] = new TwigFunction('url', function ($route, $vars = []) {
            global $urlgenerator, $_SITE;

            return 'https://' . $_SITE->get('domain') . $urlgenerator->generate($route, $vars);
        });

        // returns share buttons for url
        $functions[] = new TwigFunction('share_buttons', function ($url, $message = '', $options = []) {
            return share_buttons($url, $message, $options);
        });

        $functions[] = new TwigFunction('asset', function ($url) {
            $hash = substr(md5(BIBLYS_VERSION), 0, 8);
            return $url . "?" . $hash;
        });

        $functions[] = new TwigFunction("cart_status", function () use ($currentUserService) {
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
                return $authors[0] . ' & ' . $authors[1];
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
                return number_format(round($price / 100, 6), $decimals, ',', '&#8239;') . '&nbsp;&euro;';
            }

            return $price / 100;
        });

        // pluralize
        $filters[] = new TwigFilter('pluralize', function ($text, $number) {
            if ($number == 1 || $number == 0) {
                return $text;
            }

            return $text . 's';
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
        if ($config->get("environment") === "dev") {
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
        $trackers = self::_getAnalyticsTrackers($config);

        // Global variables
        $app = [
            "currentSite" => $currentSite,
            "currentUrl" => $currentUrlService,
            "currentUser" => $currentUserService,
            "request" => $request,
            "user" => new Visitor($request),
            "session" => $session,
            "site" => LegacyCodeHelper::getLegacyCurrentSite(),
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
     * @param Config $config
     * @return array
     */
    private static function _getAnalyticsTrackers(Config $config): array
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