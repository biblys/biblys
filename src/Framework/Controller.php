<?php

namespace Framework;

use Biblys\Isbn\Isbn as Isbn;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use EntityManager;
use Framework\Exception\AuthException;
use Model\Publisher;
use Propel\Runtime\Exception\PropelException;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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

class Controller
{
    /**
     * @var Visitor
     * @deprecated Controller->user is deprecated. Use CurrentUser service instead.
     */
    protected $user;

    public function __construct()
    {
        global $_V;

        $this->user = $_V;
    }

    /**
     * Check current user's rank.
     *
     * @param string $rank minimal rank required
     * @param int|null $id publisher id required
     *
     * @return bool true if user's rank match requirement
     * @throws AuthException
     * @deprecated Controller->auth is deprecated, use Controller::auth… functions instead.
     */
    public function auth(string $rank = 'user', int $id = null): bool
    {
        if ($rank == 'root' && !$this->user->isRoot()) {
            throw new AuthException('Accès réservé aux super-administrateurs.');
        }

        if ($rank == 'admin' && !$this->user->isAdmin()) {
            throw new AuthException('Accès réservé aux administrateurs.');
        }

        if ($rank == 'publisher' && !$this->user->isPublisherWithId($id) && !$this->user->isAdmin()) {
            throw new AuthException('Accès réservé aux éditeurs.');
        }

        if ($rank == 'user' && !$this->user->isLogged()) {
            throw new AuthException('Identification requise.');
        }

        return true;
    }

    /**
     * Returns an entity manager.
     *
     * @param string $entity the entity we want a manager for
     *
     * @return EntityManager child class
     * @deprecated
     */
    public function entityManager(string $entity): EntityManager
    {
        $class = $entity.'Manager';

        return new $class();
    }

    /**
     * Returns a Response with a rendered template.
     *
     * @param string $template template file path
     * @param array $vars template variables
     *
     * @return Response a Response object containing the rendered template
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(string $template, array $vars = []): Response
    {
        global $site, $request, $axys;

        //** Twig custom functions **//

        $functions = [];

        // return relative url for a route
        $functions[] = new TwigFunction('path', function ($route, $vars = []) {
            global $container;
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
        $loader = new TemplateLoader($site);

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

        // Global variables
        $app = [
            'request' => $request,
            'user' => $this->user,
            'axys' => $axys,
            'session' => $session,
            'site' => $site,
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
        $template = $twig->load($template);

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
     *             use $request->attributes->set("page_title") instead.
     */
    public function setPageTitle(string $title)
    {
        global $request;

        trigger_deprecation(
            "biblys/biblys",
            "2.53.1",
            "Calling Controller->setPageTitle is deprecated, use \$request->attributes->set(\"page_title\") instead."
        );

        $request->attributes->set('page_title', $title);
    }

    public function setOpengraphTags($tags = [])
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

    public function setTwitterCardsTags($tags)
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
     * @throws AuthException
     * @throws PropelException
     */
    protected static function authUser(Request $request): CurrentUser
    {
        $currentUser = CurrentUser::buildFromRequest($request);

        if (!$currentUser->isAuthentified()) {
            // TODO: throw unauthentified exception (401)
            throw new AuthException("Identification requise.");
        }

        return $currentUser;
    }

    /**
     * @param Request $request
     * @return CurrentUser
     * @throws AuthException
     * @throws PropelException
     */
    protected static function authAdmin(Request $request): CurrentUser
    {
        $currentUser = self::authUser($request);
        $currentSite = CurrentSite::buildFromConfig(new Config());

        if (!$currentUser->isAdminForSite($currentSite->getSite())) {
            // TODO: throw unauthorized exception (403)
            throw new AuthException("Accès réservé aux administrateurs.");
        }

        return $currentUser;
    }

    /**
     * @param Request $request
     * @param Publisher|null $publisher
     * @return CurrentUser
     * @throws AuthException
     * @throws PropelException
     */
    protected static function authPublisher(Request $request, ?Publisher $publisher): CurrentUser
    {
        $currentUser = self::authUser($request);

        $currentSite = CurrentSite::buildFromConfig(new Config());
        if ($currentUser->isAdminForSite($currentSite->getSite())) {
            return $currentUser;
        }

        if ($publisher === null) {
            if ($currentUser->hasPublisherRight()) {
                return $currentUser;
            }

            // TODO: throw unauthorized exception (403)
            throw new AuthException("Vous n'avez pas l'autorisation de modifier un éditeur.");
        }

        if ($currentUser->hasRightForPublisher($publisher)) {
            return $currentUser;
        }

        // TODO: throw unauthorized exception (403)
        throw new AuthException(
            sprintf("Vous n'avez pas l'autorisation de modifier l'éditeur %s", $publisher->getName())
        );
    }
}
