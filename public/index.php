<?php

use Biblys\Axys\Client as AxysClient;
use Framework\Exception\AuthException;
use Framework\Exception\ServiceUnavailableException;

// INCLUDES
include '../inc/functions.php';

use Framework\Framework;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

// Create request from globals
$request = Request::createFromGlobals();

// Create session
$session = new Session();
$session->start();

// Identification utilisateur
if ($_V->isLogged()) {
    $_LOG = $_V;
}

// Load Encore assets
$_JS_CALLS = loadEncoreAssets($config->get('environment'), 'js');
$_CSS_CALLS = loadEncoreAssets($config->get('environment'), 'css');

// Load Encore assets
if ($_V->isAdmin()) {
    $_JS_CALLS = array_merge($_JS_CALLS, loadEncoreAssets($config->get('environment'), 'js', 'admin'));
    $_CSS_CALLS = array_merge($_CSS_CALLS, loadEncoreAssets($config->get('environment'), 'css', 'admin'));
}

// jQuery
$_JS_CALLS[] = '/libs/jquery/dist/jquery.min.js?1.12.2';

// jQuery UI
$_JS_CALLS[] = '/libs/jquery-ui/jquery-ui.min.js?1.11.4';
$_CSS_CALLS[] = 'screen:/libs/jquery-ui/themes/base/all.css?1.11.4';

// Bootstrap
$_CSS_CALLS[] = 'screen,print:/libs/bootstrap/dist/css/bootstrap.min.css?3.3.6';
$_JS_CALLS[] = '/libs/bootstrap/dist/js/bootstrap.min.js?3.3.6';

// Promise polyfill
$_JS_CALLS[] = '/libs/promise-polyfill/dist/promise.min.js';

// Fetch polyfill
$_JS_CALLS[] = '/libs/fetch/fetch.js';

// Font Awesome
$_CSS_CALLS[] = 'screen:/libs/font-awesome/css/font-awesome.min.css?4.5.0';

// Tooltipster
$_JS_CALLS[] = '/libs/tooltipster/js/jquery.tooltipster.min.js?3.3.0';
$_CSS_CALLS[] = 'screen:/libs/tooltipster/css/tooltipster.css?3.3.0';

// CKEditor
$_JS_CALLS[] = '/libs/ckeditor/ckeditor.js?4.5.7';
$_JS_CALLS[] = '/libs/ckeditor/adapters/jquery.js?4.5.7';

// Axys
$axysConfig = $config->get('axys');
$axys = new AxysClient($axysConfig);

if (!$request->isSecure() && $config->get('https') === true) {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}

if ($site->get('axys') || $_V->isAdmin()) {
    if ($_V->isLogged()) {
        $_JS_CALLS[] = $axys->getWidgetUrl($_COOKIE['user_uid']);
    } else {
        $_JS_CALLS[] = $axys->getWidgetUrl();
    }
}

if ($_V->isAdmin() || $_V->isPublisher() || $_V->isBookshop() || $_V->isLibrary()) {
    $_JS_CALLS[] = '/common/js/jquery.hotkeys.js';
}

// Biblys CSS & JS
$_CSS_CALLS[] = 'screen:/common/css/common.css';
$_CSS_CALLS[] = 'print:/common/css/print.css';
$_JS_CALLS[] = '/common/js/common.js';

if ($_V->isAdmin() || $_V->isPublisher() || $_V->isBookshop() || $_V->isLibrary()) {
    $_JS_CALLS[] = '/common/js/biblys-admin.js';
    $_CSS_CALLS[] = 'screen:/common/css/biblys-admin.css';
}

$opengraph = [];

$_PAGE_TYPE = null;
$_PAGE = null;

try {
    // Site closed
    $closed = $site->getOpt('closed');
    if ($closed) {
        if (!$_V->isAdmin()) {
            throw new ServiceUnavailableException($closed);
        }

        trigger_error(
            "
                Biblys est en mode maintenance (raison : $closed).<br />
                Durant la période de maintenance, le site est accessible uniquement<br />
                aux administrateurs mais son utilisation est déconseillée<br />
                et peut conduire à la perte de données.
            ", 
            E_USER_WARNING
        );
    }

    $framework = new Framework($request);
    $urlgenerator = $framework->getUrlGenerator();
    $response = $framework->handle($request);

    // Set security headers
    $response->headers->set('X-XSS-Protection', '1; mode=block');
    $response->headers->set('X-Content-Type-Options', 'nosniff');
    $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
    $response->headers->set('X-Frame-Options', 'DENY');

    // Set HTTP Strict Transport Security to one month if config has option
    if ($request->isSecure() && $config->get('hsts') === true) {
        $response->headers->set('Strict-Transport-Security', 'max-age=15768000');
    }

    // If response is JSON, return immediately and die
    if ($response instanceof JsonResponse) {
        $response->send();
        die();
    }

    if (!$response instanceof Response) {
        throw new Exception('Controller should return a Response object.');
    }
} 

// Thrown when server is in maintenance mode
catch (ServiceUnavailableException $e) {

    $response = new Response();
    $response->setStatusCode(503);
    $response->headers->set('Retry-After', 3600);
    $response->setContent('
        <div class="text-center">
            <h1>Maintenance en cours</h1>
            <p>Merci de réessayer dans quelques instants…</p>
        </div>
    ');
}

// Thrown when user is accessing a unauthorized resource
catch (AuthException $e) {
    if (
        $request->isXmlHttpRequest()
        || $request->headers->get('Accept') == 'application/json'
    ) {
        $response = new JsonResponse(['error' => $e->getMessage()]);
        $response->setStatusCode(401);
        $response->send();
        die();
    }

    $response = new Response();
    $response->setStatusCode(403);
    $response->setContent('
        <h1>Erreur d\'authentification</h1>
        <p>' . $e->getMessage() . '</p>
        <p>Pour continuer, veuillez <a href="' . $axys->getLoginUrl() . '">vous identifier</a> ou <a href="' . $axys->getSignupUrl() . '">créer un compte</a>.</p>
    ');
}

// Not defined as symfony route, fallback to old router
catch (ResourceNotFoundException $e) {
    $symfonyRouter404message = $e->getMessage();

    // PAGE EN COURS
    $_PAGE = $request->get('page', 'home');

    // Verification page utilisateur et admin
    $_PAGE_TYPE = substr($_PAGE, 0, 4);
    if ($_PAGE_TYPE == 'adm_' and !$_V->isAdmin()) {
        $_PAGE = 'nologin';
    }
    if ($_PAGE_TYPE == 'log_' and !$_V->isLogged()) {
        $_PAGE = 'nologin';
    }

    // Get correct controller for called url
    $controller_path = get_controller_path($_PAGE);
    $twig_template = BIBLYS_PATH . '/public/' . $site->get('name') . '/html/' . $_PAGE . '.html.twig';
    if ($_PAGE == '404') {
        $debug = '404 page direct access';
    }

    // Twig template controller
    elseif ($site->get('html_renderer') && file_exists($twig_template)) {
        $_HTML = $twig_template;
        $_INCLUDE = get_controller_path('_twig');
    }

    // Native controller
    elseif ($controller_path) {
        $_INCLUDE = $controller_path;
    }

    // Controller for static page from DB
    else {
        $pm = new PageManager();

        $page_request = ['page_url' => $_PAGE];
        if (!$_V->isAdmin()) {
            $page_request['page_status'] = 1;
        }
        $page = $pm->get($page_request);

        if ($page) {
            $_INCLUDE = get_controller_path('_page');
        } else {
            $debug = 'Unable to find page from index.php';
            $_INCLUDE = get_controller_path('404');
        }
    }
} catch (Exception $e) {
    biblys_error(E_ERROR, $e->getMessage(), $e->getFile(), $e->getLine(), null, $e);
}

// INCLUDE PAGE EN COURS
try {
    if (isset($_INCLUDE)) {
        $_ECHO = null;
        $response = require $_INCLUDE;

        if (isset($_ECHO)) {
            trigger_error("Using \$_ECHO in $_INCLUDE. Legacy controllers should return a Response.", E_USER_DEPRECATED);
            $response = new Response($_ECHO);
            $_ECHO = null;
        };

        // Is this still used?
        if (isset($_JSON)) {
            trigger_error("Using \$_JSON in $_INCLUDE. Legacy controllers should return a Response", E_USER_DEPRECATED);
            $_JSON->send();
            die();
        }

        // If response is JSON, return immediately and die
        if ($response instanceof JsonResponse) {
            $response->send();
            die();
        }
    }
} catch (ResourceNotFoundException $e) {
    $response = new Response(e404($symfonyRouter404message ?? $e->getMessage()), 404);
} catch (AuthException $e) {
    $response = new Response('
        <h1>Erreur d\'authentification</h1>
        <p>' . $e->getMessage() . '</p>
        <p>Pour continuer, veuillez <a href="' . $axys->getLoginUrl() . '">vous identifier</a> ou <a href="' . $axys->getSignupUrl() . '">créer un compte</a>.</p>
    ', 403);
} catch (Exception $e) {
    biblys_error(E_USER_ERROR, $e->getMessage(), $e->getFile(), $e->getLine(), null, $e);
}

$assetsVersion = '';
if ($site->getOpt('assets_version')) {
    $assetsVersion = $site->getOpt('assets_version');
} elseif ($site->get('version')) {
    $assetsVersion = $site->get('version');
}

// Appels CSS
if (isset($_CSS_CALLS)) {
    $css_calls = null;
    foreach ($_CSS_CALLS as $c) {
        $cx = explode(':', $c);
        if (!strstr($cx[1], '?')) {
            $separator = '?';
        } else {
            $separator = '&';
        }
        $css_calls .= '        <link type="text/css" media="' . $cx[0] . '" rel="stylesheet" href="' . $cx[1] . $separator . BIBLYS_VERSION . '.' . $assetsVersion . '" />' . "\n";
    }
}

// Appels Javascript
if (isset($_JS_CALLS)) {
    $js_calls = null;
    foreach (array_unique($_JS_CALLS) as $j) {
        if (!strstr($j, '?')) {
            $separator = '?';
        } else {
            $separator = '&';
        }
        $js_calls .= '        <script type="text/javascript" src="' . $j . $separator . BIBLYS_VERSION . '.' . $assetsVersion . '"></script>' . "\n";
    }
}

$js_calls .= '
    <script>
        window.site = {};
        window.site.currency = \'' . $site->getOpt('currency') . '\';
    </script>
';

// Get custom or default layout template
$layout = BIBLYS_PATH . '/src/AppBundle/Resources/views/layout.html';
$custom_layout = BIBLYS_PATH . '/app/Resources/views/layout.html';
$old_wrap_layout = $site->get('path') . '/html/_wrap.html'; // Retro-compatibility

if (file_exists($custom_layout)) {
    $layout = $custom_layout;
} elseif (file_exists($old_wrap_layout)) {
    $layout = $old_wrap_layout;
}
$content = file_get_contents($layout);

$php_wrap = get_controller_path('_wrap');
if ($php_wrap) {
    include $php_wrap;
}

// Add custom entries to Axys menu
$menuAxys = null;
if ($site->get('wishlist')) {
    $menuAxys .= '<li><a href="/pages/log_mywishes" rel="nofollow">mes envies</a></li>';
}
if ($site->get('alerts')) {
    $menuAxys .= '<li><a href="/pages/log_myalerts" rel="nofollow">mes alertes</a></li>';
}
if ($site->get('vpc')) {
    $menuAxys .= '<li><a href="/pages/log_myorders" rel="nofollow">mes commandes</a></li>';
}
if ($site->get('shop')) {
    $menuAxys .= '<li><a href="/pages/log_mybooks" rel="nofollow">mes achats</a></li>';
}
if ($site->getOpt('show_elibrary')) {
    $menuAxys .= '<li><a href="/pages/log_myebooks" rel="nofollow">ma bibliothèque</a></li>';
}
if ($_V->hasRight('publisher') || $_V->hasRight('bookshop') || $_V->hasRight('library')) {
    $menuAxys .= '<li><a href="/pages/log_dashboard" rel="nofollow">tableau de bord</a></li>';
}
if ($_V->isAdmin() && isset($urlgenerator)) {
    $menuAxys .= '<li><a href="' . $urlgenerator->generate('main_admin') . '" rel="nofollow">administration</a></li>';
}
$axysMenu = '<ul id="addToAxysMenu" class="hidden">' . $menuAxys . '</ul>';

// Get current page title
if ($request->attributes->has('page_title')) {
    $_PAGE_TITLE = $request->attributes->get('page_title') . ' | ' . $site->get('title');
} elseif (isset($_PAGE_TITLE)) {
    $_PAGE_TITLE = $_PAGE_TITLE . ' | ' . $site->get('title');
} else {
    $_PAGE_TITLE = $site->get('title');
}

// GOOGLE ANALYTICS WITH BANNER
$googleAnalyticsId = $site->getOpt('google_analytics_id');
if ($googleAnalyticsId) {
    $analytics = '
        <script src="/libs/js-cookie/src/js.cookie.js"></script>
        <script src="/assets/js/analytics.js"></script>
        <script type="text/javascript">
            new Biblys.Analytics({ propertyId: "' . $googleAnalyticsId . '" });
        </script>
    ';
}

// Matomo tracker
$matomo = $config->get('matomo');
if ($matomo) {
    $matomoTracker = '
        <script type="text/javascript">
        var _paq = window._paq || [];
        _paq.push([\'trackPageView\']);
        _paq.push([\'enableLinkTracking\']);
        (function() {
            var u="//' . $matomo['domain'] . '/";
            _paq.push([\'setTrackerUrl\', u+\'matomo.php\']);
            _paq.push([\'setSiteId\', \'' . $matomo['site_id'] . '\']);
            var d=document, g=d.createElement(\'script\'), s=d.getElementsByTagName(\'script\')[0];
            g.type=\'text/javascript\'; g.async=true; g.defer=true; g.src=u+\'matomo.js\'; s.parentNode.insertBefore(g,s);
        })();
        </script>
    ';
}

if ($request->attributes->has('opengraph_tags')) {
    $tags = [];
    $opengraph = $request->attributes->get('opengraph_tags');
    foreach ($opengraph as $key => $val) {
        $tags[] = "<meta property=\"og:$key\" content=\"$val\">\n\r";
    }
    $_OPENGRAPH = join($tags);
}

$_TWITTER_CARDS = null;
if ($request->attributes->has('twitter_cards_tags')) {
    $tags = [];
    $opengraph = $request->attributes->get('twitter_cards_tags');
    foreach ($opengraph as $key => $val) {
        $tags[] = "<meta property=\"twitter:$key\" content=\"$val\">\n\r";
    }
    $_TWITTER_CARDS = join($tags);
}

// Tags Biblys
$content = str_replace('{PAGE_TITLE}', strip_tags($_PAGE_TITLE), $content);
$content = str_replace('{SITE_TITLE}', strip_tags($site->get('title')), $content);
$content = str_replace('{OPENGRAPH}', $_OPENGRAPH . $_TWITTER_CARDS, $content);
$content = str_replace('{CSS_CALLS}', $css_calls, $content);
$content = str_replace('{JS_CALLS}', $js_calls, $content);
$content = str_replace('{ASSETS_VERSION}', $site->getOpt('assets_version') ?? '', $content);
$content = str_replace('{AXYS_MENU}', $axysMenu, $content);

$cart = $_V->getCart();
if ($cart) {
    $content = str_replace('{cart_oneline}', $cart->getOneLine(), $content);
} else {
    $content = str_replace('{cart_oneline}', Cart::getOneLineEmpty(), $content);
}

// Close MySQL connection
$_SQL = null;

$search_terms = $request->get('q', '');
$content = str_replace('{SEARCH_TERMS}', htmlentities($search_terms), $content);

if (isset($_PAGE)) {
    $content = str_replace('{PAGE_NAME}', $_PAGE, $content);
}

if (isset($analytics)) {
    $content = str_replace('{ANALYTICS_TRACKER}', $analytics, $content);
} else {
    $content = str_replace('{ANALYTICS_TRACKER}', '', $content);
}

if (isset($matomoTracker)) {
    $content = str_replace('{MATOMO}', $matomoTracker, $content);
} else {
    $content = str_replace('{MATOMO}', '', $content);
}

// Temporary piwik fix
//if (isset($piwik)) $content = str_replace('{PIWIK_TRACKER}', $piwik, $content);
$content = str_replace('{PIWIK_TRACKER}', '', $content);

// Must be the last one (to avoid theme editor bug)
$content = str_replace('{PAGE_CONTENT}', $response->getContent(), $content);

// Create empty response if necessary
if (!isset($response)) {
    $response = new Response();
    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'text/html');
    $response->setCharset('UTF-8');
}

// Save UTM params in cookies

$utmCookiesExpires = time() + (3600 * 24 * 30 * 6); // 6 months
$utmCookiesPath = '/';
$utmCookiesDomain = null;
$utmCookiesSecure = $request->isSecure();
$utmCookiesHttpOnly = true;
$utmCookiesRaw = false;
$utmCookiesSameSite = 'strict';

$utmSource = $request->query->get('utm_source');
if ($utmSource) {
    $sourceCookie = Cookie::create(
        'utm_source',
        $utmSource,
        $utmCookiesExpires,
        $utmCookiesPath,
        $utmCookiesDomain,
        $utmCookiesSecure,
        $utmCookiesHttpOnly,
        $utmCookiesRaw,
        $utmCookiesSameSite
    );
    $response->headers->setCookie($sourceCookie);
}

$utmMedium = $request->query->get('utm_medium');
if ($utmMedium) {
    $mediumCookie = Cookie::create(
        'utm_medium',
        $utmMedium,
        $utmCookiesExpires,
        $utmCookiesPath,
        $utmCookiesDomain,
        $utmCookiesSecure,
        $utmCookiesHttpOnly,
        $utmCookiesRaw,
        $utmCookiesSameSite
    );
    $response->headers->setCookie($mediumCookie);
}

$utmCampaign = $request->query->get('utm_campaign');
if ($utmCampaign) {
    $campaignCookie = Cookie::create(
        'utm_campaign',
        $utmCampaign,
        $utmCookiesExpires,
        $utmCookiesPath,
        $utmCookiesDomain,
        $utmCookiesSecure,
        $utmCookiesHttpOnly,
        $utmCookiesRaw,
        $utmCookiesSameSite
    );
    $response->headers->setCookie($campaignCookie);
}

if ($config->get('environment') === 'dev') {
    $content .= '<div class="dev-mode noprint">~ dev mode ~</div>';
}

// Set response content & send
$response->setContent($content);
$response->prepare($request);

// In dev mode, add response time header
if ($config->get('environment') == 'dev') {
    $responseTime = round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 3) * 1000;
    $response->headers->set('Server-Timing', 'total;desc="Total";dur=' . $responseTime);
}

$response->send();
if (isset($framework)) {
    $framework->terminateKernel($request, $response);
}
