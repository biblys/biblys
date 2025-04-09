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


namespace Biblys\Service;

use Biblys\Isbn\Isbn;
use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Legacy\TemplateGlobal\Site;
use Biblys\Service\Images\ImagesService;
use Cart;
use Exception;
use Framework\TemplateLoader;
use Model\Article;
use Model\Event;
use Model\People;
use Model\Post;
use Model\Publisher;
use Model\Stock;
use Propel\Runtime\Exception\PropelException;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGenerator;
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
    private MetaTagsService $metaTagsService;
    private Request $request;

    public function __construct(
        Config $config,
        CurrentSite $currentSiteService,
        CurrentUser $currentUserService,
        MetaTagsService $metaTagsService,
        Request $request,
    )
    {
        $this->config = $config;
        $this->currentSiteService = $currentSiteService;
        $this->currentUserService = $currentUserService;
        $this->metaTagsService = $metaTagsService;
        $this->request = $request;
    }

    /**
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(string $templatePath, array $vars): string
    {
        $twig = $this->_getTwigEnvironment();
        $template = $twig->load($templatePath);
        return $template->render($vars);
    }


    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function renderResponse(string $templatePath, array $vars = [], bool $isPrivate = false): Response
    {
        $rendered = $this->render($templatePath, $vars);
        $response = new Response($rendered);

        if ($isPrivate) {
            $response->setCache(["no_store" => true, "private" => true]);
        }

        return $response;
    }

    /**
     * @throws LoaderError
     * @throws PropelException
     * @throws SyntaxError
     */
    public function renderFromString(string $templateString, array $vars = []): string
    {
        $twig = $this->_getTwigEnvironment();
        $template = $twig->createTemplate($templateString);
        return $template->render($vars);
    }

    /**
     * @throws LoaderError
     * @throws PropelException
     * @throws SyntaxError
     */
    public function renderResponseFromString(string $templateString, array $vars = [], bool $isPrivate = false): Response
    {
        $rendered = $this->renderFromString($templateString, $vars);
        $response = new Response($rendered);

        if ($isPrivate) {
            $response->setCache(["no_store" => true, "private" => true]);
        }

        return $response;
    }

    /**
     * @throws PropelException
     */
    private function _getTwigEnvironment(): Environment
    {
        $config = $this->config;
        $currentSite = $this->currentSiteService;
        $currentUserService = $this->currentUserService;
        $currentUserIsAdmin = $currentUserService->isAdmin();
        $request = $this->request;

        $functions = $this->_getCustomFunctions(
            $config,
            $currentUserIsAdmin,
            $currentUserService,
            $currentSite,
            $request,
        );
        $filters = $this->_getCustomFilters($config, $currentSite);

        $loader = new TemplateLoader($currentSite, new Filesystem());
        if ($config->get("environment") === "dev") {
            $twig = new Environment($loader, ['strict_variables' => true]);
        } else {
            $twig = new Environment($loader, ['strict_variables' => true, 'debug' => true]);
            $twig->addExtension(new DebugExtension());
        }

        $formEngine = new TwigRendererEngine(['AppBundle:Main:_form_bootstrap_layout.html.twig'], $twig);
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

        $twig->addGlobal('app', [
            "currentSite" => $currentSite,
            "currentUrl" => new CurrentUrlService($this->request),
            "currentUser" => $currentUserService,
            "request" => $this->request,
            "user" => new Visitor($this->request),
            "session" => new Session(),
            "site" => new Site($config),
            "trackers" => self::_getAnalyticsTrackers($config),
            "metaTags" => $this->metaTagsService->dump(),
        ]);

        foreach ($functions as $function) {
            $twig->addFunction($function);
        }

        foreach ($filters as $filter) {
            $twig->addFilter($filter);
        }
        return $twig;
    }

    private function _getCustomFunctions(
        Config      $config,
        bool        $currentUserIsAdmin,
        CurrentUser $currentUserService,
        CurrentSite $currentSite,
        Request     $request,
    ): array
    {
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
        $functions[] = new TwigFunction(
            "path",
            function ($name, $parameters = [], $absolute = false) use($request) {
                $container = require __DIR__ . "/../../container.php";
                /** @var UrlGenerator $urlGenerator */
                $urlGenerator = $container->get("url_generator");
                $relativeUrl = $urlGenerator->generate($name, $parameters);

                if ($absolute) {
                    return $request->getSchemeAndHttpHost().$relativeUrl;
                }

                return $relativeUrl;
            }
        );

        // return absolute url for a route
        $functions[] = new TwigFunction('url', function ($route, $vars = []) use($currentSite) {
            $siteDomain = $currentSite->getSite()->getDomain();
            return 'https://' . $siteDomain . LegacyCodeHelper::getGlobalUrlGenerator()->generate($route, $vars);
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
        return $functions;
    }

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

    public function _getCustomFilters(Config $config, CurrentSite $currentSite): array
    {
        $filters = [];

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

        $imagesService = new ImagesService($config, $currentSite, new Filesystem());

        $filters[] = new TwigFilter('hasImage',
            fn (Article|Stock|Post|Publisher|People|Event $model) => $imagesService->imageExistsFor($model)
        );

        $filters[] = new TwigFilter('imageUrl',
            fn (Article|Stock|Post|Publisher|People|Event $model, array $options = []) =>
                $imagesService->getImageUrlFor(
                    model: $model,
                    width: $options[0] ?? null,
                    height: $options[1] ?? null
                ),
            ['is_variadic' => true]
        );

        $filters[] = new TwigFilter('currency', function ($amount, $cents = false) {
            return currency($amount, $cents);
        });

        $filters[] = new TwigFilter('date', function ($date, $format = 'd/m/Y') {
            return _date($date, $format);
        });

        $filters[] = new TwigFilter('price', function ($price, $currency = null, $decimals = 2) {
            if ($currency == 'EUR') {
                return number_format(round($price / 100, 6), $decimals, ',', '&#8239;') . '&nbsp;&euro;';
            }

            return $price / 100;
        });

        $filters[] = new TwigFilter('pluralize', function ($text, $number) {
            if ($number == 1 || $number == 0) {
                return $text;
            }

            return $text . 's';
        });

        $filters[] = new TwigFilter('truncate', function ($text, $length, $ellipsis = '…') {
            return truncate(strip_tags($text), $length, $ellipsis);
        });

        $filters[] = new TwigFilter('isbn', function ($ean) {
            return Isbn::convertToIsbn13($ean);
        });

        /** Deprecated filters */

        $filters[] = new TwigFilter('hasCover', function (Article $article) use ($imagesService) {
            trigger_deprecation(
                "biblys/biblys",
                "2.87.0",
                "Twig filer hasCover is deprecated. Use method hasImage instead.",
            );
            return $imagesService->imageExistsFor($article);
        });

        $filters[] = new TwigFilter('coverUrl',
            function (Article $article, array $options = []) use ($imagesService) {
                trigger_deprecation(
                    "biblys/biblys",
                    "2.87.0",
                    "Twig filter coverUrl is deprecated. Use method imageUrl instead.",
                );
                $width = $options[0] ?? null;
                $height = $options[1] ?? null;
                return $imagesService->getImageUrlFor($article, width: $width, height: $height);
            },
            ['is_variadic' => true]
        );

        return $filters;
    }
}