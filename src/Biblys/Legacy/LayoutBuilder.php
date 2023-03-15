<?php

namespace Biblys\Legacy;

use Axys\LegacyClient as AxysClient;
use Biblys\Service\Config;
use Biblys\Service\CurrentUser;
use Cart;
use Exception;
use Propel\Runtime\Exception\PropelException;
use Site;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Visitor;

class LayoutBuilder
{

    /**
     * @param $site
     * @param array $cssCalls
     * @param array $jsCalls
     * @param Visitor $currentVisitor
     * @param UrlGenerator $urlgenerator
     * @param Request $request
     * @param Config $config
     * @param Response $response
     * @return Response
     * @throws Exception
     */
    public static function wrapResponseInThemeLayout($site, array $cssCalls, array $jsCalls, Visitor $currentVisitor, UrlGenerator $urlgenerator, Request $request, Config $config, Response $response): Response
    {
        $assetsVersion = '';
        if ($site->getOpt('assets_version')) {
            $assetsVersion = $site->getOpt('assets_version');
        } elseif ($site->get('version')) {
            $assetsVersion = $site->get('version');
        }

        $css_calls = null;
        foreach ($cssCalls as $c) {
            $cx = explode(':', $c);
            if (!strstr($cx[1], '?')) {
                $separator = '?';
            } else {
                $separator = '&';
            }
            $css_calls .= '        <link type="text/css" media="' . $cx[0] . '" rel="stylesheet" href="' . $cx[1] . $separator . BIBLYS_VERSION . '.' . $assetsVersion . '" />' . "\n";
        }

        $js_calls = null;
        foreach (array_unique($jsCalls) as $j) {
            if (!strstr($j, '?')) {
                $separator = '?';
            } else {
                $separator = '&';
            }
            $js_calls .= '        <script type="text/javascript" src="' . $j . $separator . BIBLYS_VERSION . '.' . $assetsVersion . '"></script>' . "\n";
        }

        $js_calls .= '
    <script>
        window.site = {};
        window.site.currency = \'' . $site->getOpt('currency') . '\';
    </script>
';

        // Get custom or default layout template
        $customLayout = biblysPath() . '/app/Resources/views/layout.html';
        if (!file_exists($customLayout)) {
            throw new Exception("Missing layout template: $customLayout");
        }
        $content = file_get_contents($customLayout);

        $php_wrap = get_controller_path('_wrap');
        if ($php_wrap) {
            include $php_wrap;
        }

        // Add custom entries to Axys menu
        $axysMenu = AxysClient::buildMenu($config, $urlgenerator, $request);

        $pageTitle = $site->get('title');
        if (isset($_PAGE_TITLE)) {
            trigger_deprecation(
                "biblys/biblys",
                "2.58.0",
                "The '_PAGE_TITLE' global variable is deprecated. Use Request->attributes->set(\"page_title\") instead."
            );
            $pageTitle = $_PAGE_TITLE . ' | ' . $site->get('title');
        }
        if ($request->attributes->has('page_title')) {
            $pageTitle = $request->attributes->get('page_title') . ' | ' . $site->get('title');
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

        $opengraphTags = null;
        if ($request->attributes->has('opengraph_tags')) {
            $tags = [];
            $opengraph = $request->attributes->get('opengraph_tags');
            foreach ($opengraph as $key => $val) {
                $tags[] = "<meta property=\"og:$key\" content=\"$val\">\n\r";
            }
            $opengraphTags = join($tags);
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
        $content = str_replace('{PAGE_TITLE}', strip_tags($pageTitle), $content);
        $content = str_replace('{SITE_TITLE}', strip_tags($site->get('title')), $content);
        $content = str_replace('{OPENGRAPH}', $opengraphTags . $_TWITTER_CARDS, $content);
        $content = str_replace('{CSS_CALLS}', $css_calls, $content);
        $content = str_replace('{JS_CALLS}', $js_calls, $content);
        $content = str_replace('{ASSETS_VERSION}', $site->getOpt('assets_version') ?? '', $content);
        $content = str_replace('{AXYS_MENU}', $axysMenu, $content);

        $cart = $currentVisitor->getCart();
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

        $response->setContent($content);
        $response->prepare($request);

        if ($config->get('environment') == 'dev') {
            $responseTime = round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 3) * 1000;
            $response->headers->set('Server-Timing', 'total;desc="Total";dur=' . $responseTime);
        }
        return $response;
    }

    /**
     * @param Config $config
     * @param Visitor $currentVisitor
     * @param Site $site
     * @param Request $request
     * @return array
     * @throws PropelException
     */
    public static function loadAssets(Config $config, Visitor $currentVisitor, Site $site, Request $request): array
    {
        $jsCalls = loadEncoreAssets($config->get('environment'), 'js');
        $cssCalls = loadEncoreAssets($config->get('environment'), 'css');

        if ($currentVisitor->isAdmin()) {
            $jsCalls = array_merge($jsCalls, loadEncoreAssets($config->get('environment'), 'js', 'admin'));
            $cssCalls = array_merge($cssCalls, loadEncoreAssets($config->get('environment'), 'css', 'admin'));
        }

        $jsCalls[] = '/libs/jquery/dist/jquery.min.js?1.12.2';
        $jsCalls[] = '/libs/jquery-ui/jquery-ui.min.js?1.11.4';
        $cssCalls[] = 'screen:/libs/jquery-ui/themes/base/all.css?1.11.4';
        $cssCalls[] = 'screen,print:/libs/bootstrap/dist/css/bootstrap.min.css?3.3.6';
        $jsCalls[] = '/libs/bootstrap/dist/js/bootstrap.min.js?3.3.6';
        $jsCalls[] = '/libs/promise-polyfill/dist/promise.min.js';
        $jsCalls[] = '/libs/fetch/fetch.js';
        $cssCalls[] = 'screen:/libs/font-awesome/css/font-awesome.min.css?4.5.0';
        $jsCalls[] = '/libs/tooltipster/js/jquery.tooltipster.min.js?3.3.0';
        $cssCalls[] = 'screen:/libs/tooltipster/css/tooltipster.css?3.3.0';
        $jsCalls[] = '/libs/ckeditor/ckeditor.js?4.5.7';
        $jsCalls[] = '/libs/ckeditor/adapters/jquery.js?4.5.7';

        // Axys
        $currentUser = CurrentUser::buildFromRequest($request);
        $axysConfig = $config->get('axys') ?: [];
        $axys = new AxysClient($axysConfig, $currentUser->getToken());

        /** @var Site $site */
        if ($site->get('axys') || $currentVisitor->isAdmin()) {
            if ($currentVisitor->isLogged()) {
                $jsCalls[] = $axys->getWidgetUrl($_COOKIE['user_uid']);
            } else {
                $jsCalls[] = $axys->getWidgetUrl();
            }
        }

        if ($currentVisitor->isAdmin() || $currentVisitor->isPublisher() || $currentVisitor->isBookshop() || $currentVisitor->isLibrary()) {
            $jsCalls[] = '/common/js/jquery.hotkeys.js';
        }

        // Biblys CSS & JS
        $cssCalls[] = 'screen:/common/css/common.css';
        $cssCalls[] = 'print:/common/css/print.css';
        $jsCalls[] = '/common/js/common.js';

        if ($currentVisitor->isAdmin()) {
            $jsCalls[] = '/common/js/biblys-admin.js';
            $cssCalls[] = 'screen:/common/css/biblys-admin.css';
        }
        return array($jsCalls, $cssCalls);
    }
}
