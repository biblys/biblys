<?php

use Biblys\Isbn\Isbn as Isbn;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Default error level
ini_set('display_errors', 'On');
error_reporting(E_ALL);

// Biblys version
define('BIBLYS_VERSION', '2.51.3');

/**
 * Calls biblys_error with the correct arguments for an Exception
 */
function biblys_exception($exception)
{
    biblys_error(
        E_ERROR,
        $exception->getMessage(),
        $exception->getFile(),
        $exception->getLine(),
        null,
        $exception
    );
}

function biblys_error($level, $message, $file, $line, $trace, Exception $exception = null)
{
    global $request, $config, $site;

    $response = new Response();

    switch ($level) {
        case E_ERROR:
        case E_USER_ERROR:
            $level = 'ERROR';
            break;
        case E_WARNING:
        case E_USER_WARNING:
            $level = 'WARNING';
            break;
        case E_DEPRECATED:
        case E_USER_DEPRECATED:
            $level = 'DEPRECATED';
            break;
        case E_PARSE:
            $level = 'PARSE ERROR';
            break;
        case E_NOTICE:
        case E_USER_NOTICE:
            $level = 'NOTICE';
            break;
        case E_RECOVERABLE_ERROR:
            $level = 'RECOVERABLE FATAL ERROR';
            break;
        default:
            $level = 'UNKNOWN ERROR (' . $level . ')';
            break;
    }

    // Errors.log
    $log = BIBLYS_PATH . '/logs/errors.log';
    if (!file_exists($log)) {
        file_put_contents($log, '');
    }
    $current = file_get_contents($log);
    $log_line = "\nDate: " . date('Y-m-d H:i:s') . "\nError: " . $message . "\nURL: " . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . "\nFile: " . $file . "\nLine: " . $line . "\r\n";
    file_put_contents($log, $current . $log_line);
    $lines = count(explode("\r\n", $current));

    // CLI mode
    if (!isset($request) || 'cli' == php_sapi_name()) {
        throw new Exception("$level: $message\nin $file on line $line");
        // XHR mode
    } elseif ($request->isXmlHttpRequest() || 'application/json' == $request->headers->get('Accept')) {
        $response = new JsonResponse();
        $response->setStatusCode(500);
        $response->setData(['error' => $message, 'file' => $file, 'line' => $line]);
        $response->send();
        die();

        // Web mode
    } else {

        $errorId = null;
        if ($site) {
            $errorId = $site->get('name') . '-' . $lines;
        }

        $errorMessage = $level . ': ' . $message . "\n";

        $devErrorMessage = '
URL: ' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . '
in ' . $file . ':' . $line . '

';

        $stackTrace = '';
        if ($exception) {
            $stackTrace = $exception->getTraceAsString();
        } else {

            $debugBacktrace = debug_backtrace();
            $i = 0;
            foreach ($debugBacktrace as $b) {

                if (!isset($b['file']) || !isset($b['line']) || !isset($b['function'])) {
                    continue;
                }

                $stackTrace .= '#' . $i . ' ' . $b['file'] . '(' . $b['line'] . '): ' . $b['function'] . "\n";
                $i++;
            }
        }

        $mailSubject = "J'ai rencontré une erreur sur le site";
        if ($site) {
            $mailSubject .= ' ' . $site->get('title');
        }

        $mailBody = "Merci de décrire ce que vous étiez en train de faire :


-----------------------------------------
Merci de ne rien changer sous cette ligne
-----------------------------------------\n";
        $mailBody = urlencode(
            $mailBody . html_entity_decode($errorMessage) . $devErrorMessage
        );

        if ('dev' == $config->get('environment')) {
            $errorMessage .= $devErrorMessage . $stackTrace;
        }


        if ($level === 'WARNING' || $level === 'DEPRECATED') {
            // Ignore deprecated warnings if not in dev mode
            if ($level === 'DEPRECATED' && $config->get('environment') !== 'dev') {
                return;
            }

            echo '<div class="biblys-warning noprint">
                ' . $level . ': ' . $message . '
                <pre>' . $stackTrace . '</pre>
            </div>';

            return;
        }

        $error = '
            <!DOCTYPE html>
            <html>
                <head>
                    <title>Biblys - Une erreur est survenue</title>
                    <style>
                        body { margin-top: 10%; text-align: center; font-family: "Lucida Console", Verdana, Arial, sans-serif; }
                        pre { max-width: 768px; margin: auto; text-align: left; white-space: pre-wrap; }
                        .mail-link { text-align: center; }
                    </style>
                </head>
                <body>
                    <img src="/common/img/biblys.png" alt="Biblys">
                    <h1>Une erreur est survenue.</h1>

                    <p class="mail-link">
                        <a href="/contact/?subject=' . $mailSubject . '&message=' . $mailBody . '">
                            Signaler l\'erreur
                        </a>
                    </p>

                    <pre>' . $errorMessage . '</pre>
                </body>
            </html>
        ';

        $response->setContent($error);
        $response->setStatusCode(500);
        $response->send();
        die();
    }
}

// Default host if none is specified
if (!isset($_SERVER['HTTP_HOST'])) {
    $_SERVER['HTTP_HOST'] = 'www.biblys.fr';
}

// Variables
$_ENGINE = 1;
if (!isset($_CRON)) {
    $_CRON = 0;
}
$_MYSQL_DOWN = 0;
$_OPENGRAPH = null;

// BIBLYS
if (!defined('BIBLYS_PATH')) {
    define('BIBLYS_PATH', dirname(dirname(__FILE__)) . '/');
}
define('TYS_PATH', BIBLYS_PATH . '/../../tys');
define('DL_PATH', BIBLYS_PATH . '/../../dl');
define('DL_URL', 'http://dl.biblys.fr');
define('IMAGES_PATH', BIBLYS_PATH . '/../../images/files');
define('IMAGES_URL', 'http://images.biblys.fr');

/* AUTOLOAD */

// Include composer autoload
$autoload_file = BIBLYS_PATH . '/vendor/autoload.php';
if (!file_exists($autoload_file)) {
    throw new Exception('Composer autoload file not found. Run `composer install`.');
} else {
    require_once BIBLYS_PATH . '/vendor/autoload.php';
}

// Biblys autoload
function loadClass($class)
{
    $Entity = BIBLYS_PATH . '/inc/' . $class . '.class.php';
    $EntityManager = BIBLYS_PATH . '/inc/' . str_replace('Manager', '', $class) . '.class.php';

    if (is_file($Entity)) {
        require_once $Entity;
    } elseif (is_file($EntityManager)) {
        require_once $EntityManager;
    }
}
spl_autoload_register('loadClass');

// Load config
$config = new Config();

// Media path
$media_path = BIBLYS_PATH . '/public/media';
if ($config->get('media_path')) {
    $media_path = BIBLYS_PATH . $config->get('media_path');
}
define('MEDIA_PATH', $media_path);

// Media url
$media_url = '/media';
if ($config->get('media_url')) {
    $media_url = $config->get('media_url');
}
define('MEDIA_URL', $media_url);

// Get request
$request = Request::createFromGlobals();

/* ENVIRONNEMENT */

if ('dev' == $config->get('environment')) {
    define('DEV', true);
    error_reporting(E_ALL);
    set_error_handler('biblys_error', E_ALL ^ E_DEPRECATED);
} else {
    define('DEV', false);
    error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE);
    set_error_handler('biblys_error', E_ALL ^ E_DEPRECATED ^ E_NOTICE);
}

/* DATABASE */

// Get MySQL Credential from config
$_MYSQL = $config->get('db');
if (!array_key_exists('port', $_MYSQL)) {
    $_MYSQL['port'] = 3306;
}

// MySQL PDO
try {
    $_SQL = new PDO('mysql:host=' . $_MYSQL['host'] . ';port=' . $_MYSQL['port'] . ';dbname=' . $_MYSQL['base'], $_MYSQL['user'], $_MYSQL['pass']);
    $_SQL->exec('SET CHARACTER SET utf8');
    $_SQL->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $GLOBALS['_SQL'] = $_SQL;
} catch (PDOException $e) {
    trigger_error('MySQL error #' . $e->getCode() . ': ' . $e->getMessage());
}

/* CURRENT SITE DETECTION */

// One site
$site_id = $config->get('site');
$multisites = false;

// Multi-site
if (!$site_id) {
    $host = $request->getHost();

    // Check if host is an alias
    $aliases = $config->get('aliases');
    if ($aliases) {
        if (isset($aliases[$host])) {
            $url = 'http://' . $aliases[$host] . $request->getRequestUri();
            $response = new RedirectResponse($url);
            $response->send();
            die();
        }
    }

    // Get all sites from config
    $sites = $config->get('sites');
    if (!$sites) {
        throw new Exception('No site defined in config file');
    }

    // Look for a site with this host
    if (isset($sites[$host])) {
        $site_id = $sites[$host];
    } else {
        throw new Exception('No site found for ' . $host);
    }

    $multisites = true;
}

// Get site info
$sm = new SiteManager();
$site = $sm->getById($site_id);
$_SITE = $site;
if (!$site) {
    throw new Exception('No site defined with id ' . $site_id);
}

// Define site_path (should be replace with $site->get("path"))
if (!defined('SITE_PATH')) {
    $site->set('site_path', BIBLYS_PATH . '/public/' . $site->get('name'));
    define('SITE_PATH', $site->get('path'));
}

// if (!$_CRON)
// {
//     $_SITE = sites();
//     if (empty($_SITE['site_name'])
//             && !defined('CRON')
//             && $_SERVER["HTTP_HOST"] != 'dl.biblys.fr'
//             && $_SERVER["HTTP_HOST"] != 'status.biblys.fr'
//             && $_SERVER["HTTP_HOST"] != 'code.biblys.fr'
//             && $_SERVER["HTTP_HOST"] != 'biblys.me'
//         ) trigger_error('Site inconnu');
// }

/* BIBLYS.ME */

if (!$_CRON) {
    $_V = new Visitor($request);
}

// Identification
function auth($type = 'user')
{
    global $_V;

    if (isset($_V) && $_V->isLogged()) {
        if ('log' == $type) {
            return $_V;
        } elseif ('user' == $type) {
            return true;
        } elseif ('publisher' == $type && $_V->isPublisher()) {
            return true;
        } elseif ('admin' == $type && $_V->isAdmin()) {
            return true;
        } elseif ('root' == $type && $_V->isRoot()) {
            return true;
        }
    }

    return false;
}

function urlp($url, $params)
{
    if (strstr($url, '?')) {
        $signe = '&';
    } else {
        $signe = '?';
    }

    return $url . $signe . $params;
}

function authors($x, $m = null)
{
    $x = explode(',', $x);
    $c = count($x);
    if ('url' == $m) {
        foreach ($x as $k => $v) {
            $x[$k] = '<a href="/' . makeurl($v) . '/">' . $v . '</a>';
        }
    }
    if ($c > 2) {
        return 'COLLECTIF';
    } elseif (2 == $c) {
        return $x[0] . ' & ' . $x[1];
    } else {
        return $x[0];
    }
}

// Formatage du bb code
function bbcode($var)
{
    $search = [
        '/\[b\](.*?)\[\/b\]/is',
        '/\[i\](.*?)\[\/i\]/is',
        '/\[u\](.*?)\[\/u\]/is',
        '/\[img\](.*?)\[\/img\]/is',
        '/\[url\](.*?)\[\/url\]/is',
        '/\[url\=(.*?)\](.*?)\[\/url\]/is',
    ];

    $replace = [
        '<strong>$1</strong>',
        '<em>$1</em>',
        '<u>$1</u>',
        '<img src="$1" />',
        '<a href="$1">$1</a>',
        '<a href="$1">$2</a>',
    ];
    $var = preg_replace($search, $replace, $var);

    return $var;
}

function cache($etag)
{
    global $_LOG;
    global $_SITE;
    $etag = 'W/"' . md5($_SITE['site_version'] . $_LOG['user_id'] . $_SERVER['HTTP_ACCEPT_ENCODING'] . $etag) . '"';
    if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $etag) {
        header('HTTP/1.1 304 Not Modified');
        exit;
    } else {
        header('Etag: ' . $etag);
    }
}

function _date($x, $m = 'd-m-Y')
{
    if ('0000-00-00 00:00:00' == $x || '0000-00-00' == $x || empty($x)) {
        return false;
    }

    if ($x instanceof \DateTime) {
        $x = $x->format('Y-m-d H:i:s');
    }

    $x = explode(' ', $x);
    if (empty($x[1])) {
        $x[1] = '00:00:00';
    }

    $d = explode('-', $x[0]);
    $h = explode(':', $x[1]);

    if (!isset($h[2])) {
        $h[2] = 0;
    }
    if (!isset($d[2])) {
        $d[2] = 0;
    }

    $t = mktime($h[0], $h[1], $h[2], $d[1], $d[2], $d[0]);
    $N = date('N', $t);
    $W = date('W', $t);

    // Traduction mois
    if ('01' == $d[1]) {
        $mois = 'janvier';
    } elseif ('02' == $d[1]) {
        $mois = 'février';
    } elseif ('03' == $d[1]) {
        $mois = 'mars';
    } elseif ('04' == $d[1]) {
        $mois = 'avril';
    } elseif ('05' == $d[1]) {
        $mois = 'mai';
    } elseif ('06' == $d[1]) {
        $mois = 'juin';
    } elseif ('07' == $d[1]) {
        $mois = 'juillet';
    } elseif ('08' == $d[1]) {
        $mois = 'août';
    } elseif ('09' == $d[1]) {
        $mois = 'septembre';
    } elseif ('10' == $d[1]) {
        $mois = 'octobre';
    } elseif ('11' == $d[1]) {
        $mois = 'novembre';
    } elseif ('12' == $d[1]) {
        $mois = 'décembre';
    } else {
        $mois = '?';
    }

    // Traduction jour de la semaine
    if (1 == $N) {
        $jour = 'lundi';
    } elseif (2 == $N) {
        $jour = 'mardi';
    } elseif (3 == $N) {
        $jour = 'mercredi';
    } elseif (4 == $N) {
        $jour = 'jeudi';
    } elseif (5 == $N) {
        $jour = 'vendredi';
    } elseif (6 == $N) {
        $jour = 'samedi';
    } elseif (7 == $N) {
        $jour = 'dimanche';
    }

    if ('DATE_W3C' == $m) {
        //return $d[2]."-"
    } else {
        $trans = [ // Pour le Samedi 5 septembre 2010 à 07h34m05
            'D' => date('D', $t), // Sat
            'd' => $d[2], // 05
            'j' => date('j', $t), // 5
            'l' => $jour, // samedi
            'L' => ucwords($jour), // Samedi
            'M' => date('M', $t), // Sep
            'm' => $d[1], // 09
            'f' => $mois, // septembre
            'F' => ucwords($mois), // Septembre
            'Y' => $d[0], // 2010
            'H' => $h[0], // 07
            'G' => date('G', $t), // 7
            'i' => $h[1], // 34
            's' => $h[2],  // 05
            'W' => $W, // numero de la semaine dans l'annee
        ];
    }

    return strtr($m, $trans);
}

function userE()
{
    global $_LOG;
    if ('Mme' == $_LOG['user_title'] or 'Mlle' == $_LOG['user_title']) {
        return 'e';
    }
}

function e404($debug = '404 function called without debug info')
{
    global $_V, $response, $_SQL, $site;
    $controller = get_controller_path('404');
    include $controller;

    return $_ECHO;
}

function error($x, $t = 'MySQL')
{
    global $_SITE;
    global $_LOG;
    global $_POST;
    global $_MYSQL;
    if (is_array($x)) {
        $x = 'SQL Error #' . $x[1] . ' : ' . $x[2];
    }
    if ('404' != $t) {
        trigger_error($x, E_USER_ERROR);
    }
}

// Calcul de la date d'expédition
function expdate($date = 'today', $format = 'none')
{
    if ('today' == $date) {
        $date = date('Y-m-d H:m:s');
    }
    $d = 0;
    if (('Mon' == date('D', strtotime($date))) && (date('G', strtotime($date)) > '19')) {
        $d += 2;
    } // A partir de jeudi 20h, on passe au vendredi suivant
    $expDay = null;
    while ('Tue' != $expDay) {
        $expDate = date('Y-m-d', strtotime("$date + $d day"));
        $expDay = date('D', strtotime("$date + $d day"));
        ++$d;
    }
    //$expDate = "2014-01-04"; // Forcer la date d'expedition
    if ('none' == $format) {
        return $expDate;
    } elseif ('days' == $format) {
        return $d;
    } else {
        return _date($expDate, $format);
    }
}

function file_dir($x)
{
    $x = substr($x, -2, 2);
    if (1 == strlen($x)) {
        $x = '0' . $x;
    }

    return $x;
}

// ** ISBN ** //

function isbn($x, $m = 'check')
{
    $isbn = new Isbn($x);
    if ($isbn->isValid()) {
        if ('check' == $m) {
            return true;
        } else {
            return $isbn->format($m);
        }
    }

    return false;
}

function json_error($errno, $errstr, $errfile = null, $errline = null, $errcontext = null)
{
    $json = [
        'errno' => $errno,
        'error' => $errstr,
        'errfile' => $errfile,
        'errline' => $errline,
    ];
    die(json_encode($json));
}

//** MEDIAS **//

// url
function media_url($type, $id, $size = '0')
{
    $host = 'media.biblys.fr';

    if (!empty($size)) {
        $size = '-' . $size;
    } else {
        $size = null;
    }
    if ('article' == $type) {
        $type = 'book';
    }
    if ('extrait' == $type) {
        $ext = 'pdf';
    } elseif ('publisher' == $type) {
        $ext = 'png';
    } else {
        $ext = 'jpg';
    }

    return 'https://' . $host . '/' . $type . '/' . file_dir($id) . '/' . $id . $size . '.' . $ext;
}

// path
function media_path($type, $id)
{
    if (strstr($type, 'ebook-')) { // ebook
        $ext = str_replace('ebook-', '', $type);
        $path = BIBLYS_PATH . 'dl/' . $ext . '/' . file_dir($id) . '/' . $id . '.' . $ext;
    }

    return $path;
}

// supprimer
function media_delete($type, $id)
{
    if (media_exists($type, $id)) {
        if ('article' == $type) {
            $type = 'book';
        }
        if ('publisher' == $type) {
            $ext = 'png';
        } elseif ('extrait' == $type) {
            $ext = 'pdf';
        } else {
            $ext = 'jpg';
        }
        $path = MEDIA_PATH . '//' . $type . '/' . file_dir($id) . '/' . $id;
        if ('ebook-pdf' == $type) {
            $path = BIBLYS_PATH . '/dl/pdf/' . file_dir($id) . '/' . $id . '.pdf';
        }
        if ('ebook-epub' == $type) {
            $path = BIBLYS_PATH . '/dl/epub/' . file_dir($id) . '/' . $id . '.epub';
        }
        if ('ebook-azw' == $type) {
            $path = BIBLYS_PATH . '/dl/azw/' . file_dir($id) . '/' . $id . '.azw';
        }
        $files = glob($path . '*');
        foreach ($files as $filename) {
            unlink($filename) or die('media delete error');
        }
    }
}

// tester la presence
function media_exists($type, $id)
{
    if ('article' == $type) {
        $type = 'book';
    }
    if ('extrait' == $type) {
        $ext = 'pdf';
    } elseif ('publisher' == $type) {
        $ext = 'png';
    } else {
        $ext = 'jpg';
    }
    $path = MEDIA_PATH . '//' . $type . '/' . file_dir($id) . '/' . $id . '.' . $ext;
    if ('ebook-pdf' == $type) {
        $path = BIBLYS_PATH . '/dl/pdf/' . file_dir($id) . '/' . $id . '.pdf';
    }
    if ('ebook-epub' == $type) {
        $path = BIBLYS_PATH . '/dl/epub/' . file_dir($id) . '/' . $id . '.epub';
    }
    if ('ebook-azw' == $type) {
        $path = BIBLYS_PATH . '/dl/azw/' . file_dir($id) . '/' . $id . '.azw';
    }
    if (file_exists($path)) {
        return true;
    } else {
        return false;
    }
}

// uploader
function media_upload($type, $id)
{
    trigger_error("media_upload is deprecated. Use Media->upload instead.", E_USER_DEPRECATED);

    global $_FILES;
    media_delete($type, $id);
    if ('post' == $type) {
        move_uploaded_file($_FILES['post_illustration_upload']['tmp_name'], MEDIA_PATH . '/post/' . file_dir($id) . '/' . $id . '.jpg') or die('error');

        return 1;
    } elseif ('event' == $type) {
        move_uploaded_file($_FILES['event_image']['tmp_name'], MEDIA_PATH . '/event/' . file_dir($id) . '/' . $id . '.jpg') or die('media upload error');

        return 1;
    } elseif ('publisher' == $type) {
        move_uploaded_file($_FILES['publisher_logo_upload']['tmp_name'], MEDIA_PATH . '/publisher/' . file_dir($id) . '/' . $id . '.png') or die('error');

        return 1;
    } elseif ('article' == $type || 'book' == $type) {
        $path = MEDIA_PATH . '/book/' . file_dir($id) . '/' . $id . '.jpg';
        $field = 'cover';
    } elseif ('extrait' == $type) {
        $path = MEDIA_PATH . '/extrait/' . file_dir($id) . '/' . $id . '.pdf';
        $field = 'excerpt';
    } elseif ('ebook-pdf' == $type) {
        $path = BIBLYS_PATH . 'dl/pdf/' . file_dir($id) . '/' . $id . '.pdf';
        $field = 'pdf';
    } elseif ('ebook-epub' == $type) {
        $path = BIBLYS_PATH . 'dl/epub/' . file_dir($id) . '/' . $id . '.epub';
        $field = 'epub';
    } elseif ('ebook-azw' == $type) {
        $path = BIBLYS_PATH . 'dl/azw/' . file_dir($id) . '/' . $id . '.azw';
        $field = 'azw';
    }
    move_uploaded_file($_FILES['article_' . $field . '_upload']['tmp_name'], $path);
}

function numero($x, $b = ' n&deg;&nbsp;')
{
    if (!empty($x)) {
        return $b . $x;
    } else {
        return '';
    }
}

function price($x, $m = null, $decimals = 2)
{
    if ('EUR' == $m) {
        return number_format(round($x / 100, 6), $decimals, ',', '&#8239;') . '&nbsp;&euro;';
    }

    return $x / 100;
}

/**
 * Format number as a price with site currency.
 *
 * @param float $amount the amount to format
 * @param bool  $cents  if true, the amount is in cents and should be
 *                      divided by 100 before display
 */
function currency($amount, $cents = false)
{
    global $site;
    $currency = $site->getOpt('currency');

    if ($cents) {
        $amount = round($amount / 100, 6);
    }

    if ('FCFA' === $currency) {
        return number_format($amount, 2, ',', '&#8239;') . '&nbsp;F&#8239;CFA';
    }

    // Default: EUR
    return number_format($amount, 2, ',', '&#8239;') . '&nbsp;&euro;';
}

function redirect($url, $params = null, $text = null, $status = 302)
{
    global $response;

    if (is_array($params)) {
        $url .= '?' . http_build_query($params);
    }

    $response = new RedirectResponse($url, $status);
    $response->send();
}

function root($x)
{
    if (auth('root')) {
        die($x);
    }
}

function s($x, $s = null, $p = null)
{
    if (empty($p) and $x > 1) {
        return 's';
    } elseif (isset($p) and $x <= 1) {
        return $s;
    } elseif (isset($p) and $x > 1) {
        return $p;
    }
}

function test($x)
{
    if (auth('root')) {
        echo 'Test : ' . $x;
    }
}

/**
 * Truncate a string.
 *
 * @param string $string
 * @param int    $max_length
 * @param type   $replacement
 * @param bool   $trunc_at_space
 * @param bool   $with_tooltip
 *
 * @return string
 */
function truncate($string, $max_length = 30, $replacement = '', $trunc_at_space = false, $with_tooltip = false)
{
    $string = strip_tags($string);
    $max_length -= mb_strlen($replacement);
    $string_length = mb_strlen($string);
    if ($string_length <= $max_length) {
        return $string;
    }
    if ($trunc_at_space && ($space_position = strrpos($string, ' ', $max_length - $string_length))) {
        $max_length = $space_position;
    }
    if ($with_tooltip) {
        return '<span title="' . strip_tags($string) . '">' . substr_replace($string, $replacement, $max_length) . '</span>';
    } else {
        return substr_replace($string, $replacement, $max_length);
    }
}

// Retirer les caractères interdits dans une url
function makeurl($x)
{
    // Caractères à supprimer
    $delete = ['', '°', '/', '«', '»', '(', ')', '?', '!', '.', ',', ';', '"', '°', '€', '#'];
    $x = str_replace($delete, '', $x);

    // Espaces à la fin
    $x = trim($x);

    // Caractères à remplacer
    $replacements = [
        'à' => 'a', 'À' => 'A',
        'â' => 'a', 'Â' => 'A',
        'ä' => 'a', 'Ä' => 'A',
        'á' => 'a', 'Á' => 'A',
        'ã' => 'a', 'Ã' => 'A',
        'å' => 'a', 'Å' => 'A',
        'î' => 'i', 'Î' => 'I',
        'ï' => 'i', 'Ï' => 'I',
        'ì' => 'i', 'Ì' => 'I',
        'í' => 'i', 'Í' => 'I',
        'ô' => 'o', 'Ô' => 'O',
        'ö' => 'o', 'Ö' => 'O',
        'ò' => 'o', 'Ò' => 'O',
        'ó' => 'o', 'Ó' => 'O',
        'õ' => 'o', 'Õ' => 'O',
        'ø' => 'o', 'Ø' => 'O',
        'ù' => 'u', 'Ù' => 'U',
        'û' => 'u', 'Û' => 'U',
        'ü' => 'u', 'Ü' => 'U',
        'ú' => 'u', 'Ú' => 'U',
        'é' => 'e', 'É' => 'E',
        'è' => 'e', 'È' => 'E',
        'ê' => 'e', 'Ê' => 'E',
        'ë' => 'e', 'Ë' => 'E',
        'ç' => 'c', 'Ç' => 'C',
        'ÿ' => 'y', 'Ÿ' => 'Y',
        'ñ' => 'n', 'Ñ' => 'N',
        'ś' => 's', 'Ś' => 'S',
        'š' => 's', 'Š' => 'S',
        '' => 'oe', 'æ' => 'ae',
        //" n° " => "-",
        '%' => 'pourcents',
        ' - ' => '-',
        ' – ' => '-',
        ' : ' => '-',
        ' ' => '-',
        '_' => '-',
        '*' => '-',
        "'" => '-',
        '’' => '-',
        '' => '-',
        '+' => 'plus',
        '&' => 'et',
    ];
    $x = str_replace(array_keys($replacements), $replacements, $x);

    // Remplacer les doubles tirets par des simples
    while (preg_match('#-{2,}#', $x)) {
        $x = str_replace('--', '-', $x);
    }

    // Minuscules
    $x = strtolower($x);

    // Ne garder que certains caractères
    $url = null;
    foreach (str_split($x) as $char) {
        if (in_array($char, str_split('-abcdefghijklmnopqrstuvwxyz0123456789'))) {
            $url .= $char;
        }
    }
    $x = $url;

    $x = trim($x, ' -');
    $x = stripslashes($x);

    return $x;
}

// Créer l'adresse de la page de la collection
function collection_url($publisher, $collection)
{
    return CollectionManager::createSlug($publisher, $collection);
}

// Retirer l'article déterminant avec un titre
function alphabetize($text)
{
    return preg_replace("#^(L'|l'|Le |le |LE |La |la |LA |Les |les |LES )(.*)#", '$2, $1', $text);
}

// Afficher nom d'utilisateur
function user_name($x)
{
    if (!empty($x['user_screen_name'])) {
        $name = $x['user_screen_name'];
    } elseif (!empty($x['user_last_name'])) {
        $name = trim($x['user_first_name'] . ' ' . $x['user_last_name']);
    } elseif (!empty($x['order_last_name'])) {
        $name = trim($x['order_first_name'] . ' ' . $x['order_last_name']);
    } elseif (!empty($x['customer_last_name'])) {
        $name = trim($x['customer_first_name'] . ' ' . $x['customer_last_name']);
    } elseif (!empty($x['user_email'])) {
        $name = $x['user_email'];
    } elseif (!empty($x['customer_email'])) {
        $name = $x['customer_email'];
    } elseif (!empty($x['user_id'])) {
        $name = $x['user_id'];
    } elseif (!empty($x['customer_id'])) {
        $name = 'Client n° ' . $x['customer_id'];
    } else {
        $name = 'Inconnu';
    }

    return $name;
}

// Afficher page
function render_page($page_id, $mode = 'standalone')
{
    global $_SITE;
    global $_SQL;
    $pages = $_SQL->query("SELECT `page_content` FROM `pages` WHERE `page_id` = '" . $page_id . "' AND `site_id` = '" . $_SITE['site_id'] . "' AND `page_status` = 1 LIMIT 1");
    if ('include' == $mode && $p = $pages->fetch(PDO::FETCH_ASSOC)) {
        return $p['page_content'];
    }
}

// Unité de taille
function file_size($s)
{
    $i = 0;
    while ($s > 1024) {
        $s /= 1024;
        ++$i;
    }
    $p = 0;
    if (0 == $i) {
        $u = '&nbsp;octets';
    } elseif (1 == $i) {
        $u = '&nbsp;Ko';
    } elseif (2 == $i) {
        $u = '&nbsp;Mo';
        $p = 2;
    } elseif (3 == $i) {
        $u = '&nbsp;Go';
        $p = 3;
    } elseif (4 == $i) {
        $u = '&nbsp;To';
    }

    return round($s, $p) . $u;
}

// Retourne un taux de TVA
function tva_rate($tva, $date = null, $article_type = 1)
{
    global $_SITE;

    if ('be' == $_SITE['site_tva']) {
        if (1 == $tva && 1 == $article_type) { // Livre papier
            $rate = '6';
        } else {
            $rate = '21';
        }
    } elseif ('fr' == $_SITE['site_tva']) {
        if ('all' == $tva) {
            return [2.1, 5.5, 7, 19.6, 20];
        }

        if (1 == $tva) { // Taux reduit livre
            if ($date < '2013-01-01' && $date >= '2012-04-01') {
                $rate = '7';
            } else {
                $rate = '5.5';
            }
        } elseif (2 == $tva) { // Taux reduit presse
            $rate = '2.1';
        } elseif (3 == $tva) {
            if ($date < '2014-01-01') {
                $rate = '19.6';
            } else {
                $rate = '20';
            }
        }
    }

    if (isset($rate)) {
        return $rate;
    } else {
        return false;
    }
}

// Calcule un pourcentage
function percent($val1, $val2, $precision = 0)
{
    if (!$val2) {
        return false;
    }

    $division = $val1 / $val2;
    $res = $division * 100;
    $res = round($res, $precision);

    return $res . '&nbsp;%';
}

function slugify($text)
{
    if (empty($text)) {
        throw new Exception('Cannot slugify: text is empty.');
    }

    // replace non letter or digits by -
    $slug = preg_replace('~[^\\pL\d]+~u', '-', $text);

    // trim
    $slug = trim($slug, '-');

    // transliterate
    if (function_exists('iconv')) {
        $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
    }

    // lowercase
    $slug = strtolower($slug);

    // remove unwanted characters
    $slug = preg_replace('~[^-\w]+~', '', $slug);

    if (empty($slug)) {
        throw new Exception('Slug is empty for text: "' . $text . '" .');
    }

    return $slug;
}

function get_template($template, $variables = [])
{
    $controller = new Framework\Controller();

    return $controller->render($template, $variables);
}

/**
 * Generates HTML for share buttons.
 *
 * @param string $url     the url to share
 * @param string $text    the text to share
 * @param array  $options an array of options
 *
 * @return HTML The HTML code for the share buttons
 *
 * Options:
 *  - class:   adds a class to main element
 *  - buttons: for each button, boolean to set whether the button should be shown
 *  - icons:   for each button, replace the default icon with another from set
 *  - images:  for each button, replace the default icon with an image
 */
function share_buttons($url, $text = null, $options = [])
{
    // Buttons option

    $options['buttons'] = isset($options['buttons']) ? $options['buttons'] : [];
    $options['buttons'] = [
        'facebook' => isset($options['buttons']['facebook']) ? $options['buttons']['facebook'] : true,
        'twitter' => isset($options['buttons']['twitter']) ? $options['buttons']['twitter'] : true,
        'pinterest' => isset($options['buttons']['pinterest']) ? $options['buttons']['pinterest'] : true,
        'mail' => isset($options['buttons']['mail']) ? $options['buttons']['mail'] : true,
    ];

    // Icons option

    $options['icons'] = isset($options['icons']) ? $options['icons'] : [];
    $options['icons'] = [
        'facebook' => isset($options['icons']['facebook']) ? $options['icons']['facebook'] : 'fa-facebook-square',
        'twitter' => isset($options['icons']['twitter']) ? $options['icons']['twitter'] : 'fa-twitter-square',
        'pinterest' => isset($options['icons']['pinterest']) ? $options['icons']['pinterest'] : 'fa-pinterest-square',
        'mail' => isset($options['icons']['mail']) ? $options['icons']['mail'] : 'fa-envelope',
    ];

    // Images option

    $options['images'] = isset($options['images']) ? $options['images'] : [];
    $options['images'] = [
        'facebook' => isset($options['images']['facebook']) ? '<img src="' . $options['images']['facebook'] . '" alt="Facebook">' : '<i class="fa fa-' . $options['icons']['facebook'] . ' fa-2x"></i>',
        'twitter' => isset($options['images']['twitter']) ? '<img src="' . $options['images']['twitter'] . '" alt="Twitter">' : '<i class="fa fa-' . $options['icons']['twitter'] . ' fa-2x"></i>',
        'pinterest' => isset($options['images']['pinterest']) ? '<img src="' . $options['images']['pinterest'] . '" alt="Twitter">' : '<i class="fa fa-' . $options['icons']['pinterest'] . ' fa-2x"></i>',
        'mail' => isset($options['images']['mail']) ? '<img src="' . $options['images']['mail'] . '" alt="E-mail">' : '<i class="fa fa-' . $options['icons']['mail'] . ' fa-2x"></i>',
    ];

    // Build buttons

    $buttons = [];

    if (true == $options['buttons']['facebook']) {
        $buttons['facebook'] = ' <li><a class="facebook-share-button" href="https://www.facebook.com/sharer/sharer.php?u=' . urlencode($url) . '" target="_blank" title="Partager sur Facebook">' . $options['images']['facebook'] . '</a></li>';
    }

    if (true == $options['buttons']['twitter']) {
        $buttons['twitter'] = ' <li><a class="twitter-share-button" href="https://twitter.com/intent/tweet?text=' . urlencode($text . ' ' . $url) . '&related=biblys" target="_blank" title="Partager sur Twitter">' . $options['images']['twitter'] . '</a></li>';
    }

    if (true == $options['buttons']['pinterest']) {
        $buttons['pinterest'] = ' <li><a class="pinterest-share-button" href="https://pinterest.com/pin/create/link/?url=' . urlencode($url) . '" target="_blank" title="Partager sur Pinterest">' . $options['images']['pinterest'] . '</a></li>';
    }

    if (true == $options['buttons']['mail']) {
        $buttons['mail'] = ' <li><a class="mail-share-button" href="mailto:?&subject=' . urlencode($text) . '&body=' . urlencode($url) . '" title="Partager par courriel">' . $options['images']['mail'] . '</a></li>';
    }

    return '
            <ul class="share-buttons' . (isset($options['class']) ? ' ' . $options['class'] : null) . '">
                ' . join($buttons) . '
            </ul>
        ';
}

// Verif adresse mail
function check_email($email_address)
{
    if (preg_match(";^(?=.{4,256}$)((?=.{1,64}(?!.+@))((((((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\(((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?([\x21-\x27\x2A-\x5B\\x5D-\x7E]|\\\\[\x21-\x7E\x20\x09]|\(((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?([\x21-\x27\x2A-\x5B\\x5D-\x7E]|\\\\[\x21-\x7E\x20\x09]|\(((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?([\x21-\x27\x2A-\x5B\\x5D-\x7E]|\\\\[\x21-\x7E\x20\x09]|\(((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?([\x21-\x27\x2A-\x5B\\x5D-\x7E]|\\\\[\x21-\x7E\x20\x09]))*(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\)))*(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\)))*(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\)))*(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\))+(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?)|([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?[\x41-\x5A\x61-\x7A\x30-\x39\x21\x23-\x27\x2A\x2B\\x2D\x2F\x3D\x3F\x5E-\x60\x7B-\x7E]+(\.[\x41-\x5A\x61-\x7A\x30-\x39\x21\x23-\x27\x2A\x2B\\x2D\x2F\x3D\x3F\x5E-\x60\x7B-\x7E]+)*((((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\(((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?([\x21-\x27\x2A-\x5B\\x5D-\x7E]|\\\\[\x21-\x7E\x20\x09]|\(((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?([\x21-\x27\x2A-\x5B\\x5D-\x7E]|\\\\[\x21-\x7E\x20\x09]|\(((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?([\x21-\x27\x2A-\x5B\\x5D-\x7E]|\\\\[\x21-\x7E\x20\x09]|\(((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?([\x21-\x27\x2A-\x5B\\x5D-\x7E]|\\\\[\x21-\x7E\x20\x09]))*(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\)))*(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\)))*(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\)))*(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\))+(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?)|([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?)|(((((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\(((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?([\x21-\x27\x2A-\x5B\\x5D-\x7E]|\\\\[\x21-\x7E\x20\x09]|\(((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?([\x21-\x27\x2A-\x5B\\x5D-\x7E]|\\\\[\x21-\x7E\x20\x09]|\(((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?([\x21-\x27\x2A-\x5B\\x5D-\x7E]|\\\\[\x21-\x7E\x20\x09]|\(((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?([\x21-\x27\x2A-\x5B\\x5D-\x7E]|\\\\[\x21-\x7E\x20\x09]))*(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\)))*(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\)))*(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\)))*(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\))+(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?)|([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\x22((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?([\x21\x23-\x5B\\x5D-\x7E]|\\\\[\x21-\x7E\x20\x09]))*(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\x22((((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\(((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?([\x21-\x27\x2A-\x5B\\x5D-\x7E]|\\\\[\x21-\x7E\x20\x09]|\(((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?([\x21-\x27\x2A-\x5B\\x5D-\x7E]|\\\\[\x21-\x7E\x20\x09]|\(((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?([\x21-\x27\x2A-\x5B\\x5D-\x7E]|\\\\[\x21-\x7E\x20\x09]|\(((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?([\x21-\x27\x2A-\x5B\\x5D-\x7E]|\\\\[\x21-\x7E\x20\x09]))*(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\)))*(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\)))*(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\)))*(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\))+(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?)|([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?)))@((?=.{1,255}$)(((((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\(((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?([\x21-\x27\x2A-\x5B\\x5D-\x7E]|\\\\[\x21-\x7E\x20\x09]|\(((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?([\x21-\x27\x2A-\x5B\\x5D-\x7E]|\\\\[\x21-\x7E\x20\x09]|\(((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?([\x21-\x27\x2A-\x5B\\x5D-\x7E]|\\\\[\x21-\x7E\x20\x09]|\(((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?([\x21-\x27\x2A-\x5B\\x5D-\x7E]|\\\\[\x21-\x7E\x20\x09]))*(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\)))*(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\)))*(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\)))*(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\))+(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?)|([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?(?!(.*\.|)([\x30-\x39]{2,64}|.)($|\())(?=(.{1,63}(\.|$|\())+)(?!(-.*)|(.*-($|\()))(?!.*-\.)[\x41-\x5A\x61-\x7A\x30-\x39\\x2D]+(\.[\x41-\x5A\x61-\x7A\x30-\x39\\x2D]+)*((((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\(((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?([\x21-\x27\x2A-\x5B\\x5D-\x7E]|\\\\[\x21-\x7E\x20\x09]|\(((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?([\x21-\x27\x2A-\x5B\\x5D-\x7E]|\\\\[\x21-\x7E\x20\x09]|\(((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?([\x21-\x27\x2A-\x5B\\x5D-\x7E]|\\\\[\x21-\x7E\x20\x09]|\(((([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?([\x21-\x27\x2A-\x5B\\x5D-\x7E]|\\\\[\x21-\x7E\x20\x09]))*(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\)))*(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\)))*(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\)))*(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?\))+(([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?)|([\x20\x09]*\x0D\x0A)?[\x20\x09]+)?|\[((\x32\x35[\x30-\x35]|\x32[\x30-\x34][\x30-\x39]|[\x30\x31]?[\x30-\x39]{1,2})(\.(\x32\x35[\x30-\x35]|\x32[\x30-\x34][\x30-\x39]|[\x30\x31]?[\x30-\x39]{1,2})){3}|IPv6\x3A([\x30-\x39\x41-\x46\x61-\x66]{1,4}(\x3A[\x30-\x39\x41-\x46\x61-\x66]{1,4}){7}|(?=((\x3A\x3A)?(([^\x3A]*)(\x3A|\x3A\x3A|(?=\]))){0,6}\]))([\x30-\x39\x41-\x46\x61-\x66]{1,4}(\x3A[\x30-\x39\x41-\x46\x61-\x66]{1,4}){0,5})?\x3A\x3A([\x30-\x39\x41-\x46\x61-\x66]{1,4}(\x3A[\x30-\x39\x41-\x46\x61-\x66]{1,4}){0,5})?|[\x30-\x39\x41-\x46\x61-\x66]{1,4}(\x3A[\x30-\x39\x41-\x46\x61-\x66]{1,4}){5}\x3A(\x32\x35[\x30-\x35]|\x32[\x30-\x34][\x30-\x39]|[\x30\x31]?[\x30-\x39]{1,2})(\.(\x32\x35[\x30-\x35]|\x32[\x30-\x34][\x30-\x39]|[\x30\x31]?[\x30-\x39]{1,2})){3}|(?=((\x3A\x3A)?(([^\x3A]*)(\x3A|\x3A\x3A)){0,4}(\x32\x35[\x30-\x35]|\x32[\x30-\x34][\x30-\x39]|[\x30\x31]?[\x30-\x39]{1,2})(\.(\x32\x35[\x30-\x35]|\x32[\x30-\x34][\x30-\x39]|[\x30\x31]?[\x30-\x39]{1,2})){3}\]))([\x30-\x39\x41-\x46\x61-\x66]{1,4}(\x3A[\x30-\x39\x41-\x46\x61-\x66]{1,4}){0,3})?\x3A\x3A([\x30-\x39\x41-\x46\x61-\x66]{1,4}(\x3A[\x30-\x39\x41-\x46\x61-\x66]{1,4}){0,3}\x3A)?(\x32\x35[\x30-\x35]|\x32[\x30-\x34][\x30-\x39]|[\x30\x31]?[\x30-\x39]{1,2})(\.(\x32\x35[\x30-\x35]|\x32[\x30-\x34][\x30-\x39]|[\x30\x31]?[\x30-\x39]{1,2})){3}))\]))$;ius", $email_address)) {
        return true;
    }    // Return affirmative if successful match
    return false; // No successful match was made - Return Negatory
}

// Returns site controller if it exists, or default controller, or false
function get_controller_path($controller)
{
    global $site;

    $default_path = BIBLYS_PATH . '/controllers/common/php/' . $controller . '.php';
    $app_path = BIBLYS_PATH . 'app/controllers/' . $controller . '.php';

    if (file_exists($app_path)) {
        return $app_path;
    }

    if (file_exists($default_path)) {
        return $default_path;
    }

    return false;
}

function loadEncoreAssets(string $env, string $fileType, string $userLevel = 'app')
{
    if ($env === 'prod') {
        $entrypointsFile = './assets/bundle/entrypoints.json';
    } else {
        $entrypointsFile = './build/entrypoints.json';
    }

    $entrypointsJson = file_get_contents($entrypointsFile);
    $entrypointsArray = json_decode($entrypointsJson, true);

    $calls = [];

    if ($fileType === 'js') {
        foreach ($entrypointsArray['entrypoints'][$userLevel]['js'] as $asset) {
            $calls[] = $asset;
        }
    }

    if ($fileType === 'css') {
        foreach ($entrypointsArray['entrypoints'][$userLevel]['css'] as $asset) {
            $calls[] = "screen:$asset";
        }
    }

    return $calls;
}
