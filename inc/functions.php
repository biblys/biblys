<?php

use Biblys\Service\Config;
use Rollbar\Rollbar;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Default error level
ini_set('display_errors', 'On');
error_reporting(E_ALL);

// Constants
require_once 'constants.php';

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

/* AUTOLOAD */

// Include composer autoload
$autoloadFile = __DIR__."/../vendor/autoload.php";
if (!file_exists($autoloadFile)) {
    throw new Exception('Composer autoload file not found. Run `composer install`.');
} else {
    require_once $autoloadFile;
}

$config = Config::load();
$rollbarConfig = $config->get("rollbar");
if ($rollbarConfig) {
    Rollbar::init([
        "access_token" => $rollbarConfig["access_token"],
        "environment" => $rollbarConfig["environment"],
        "code_version" => BIBLYS_VERSION
    ]);
}

// Biblys autoload
require_once __DIR__."/autoload-entity.php";



/**
 * @deprecated biblysPath() is deprecated. Use relative path with __DIR__ instead.
 */
function biblysPath(): string
{
    trigger_deprecation(
        package: "biblys/biblys",
        version: "2.67.0",
        message: "biblysPath() is deprecated. Use relative path with __DIR__ instead.",
    );

    return __DIR__."/../";
}

// Media path
$media_path = __DIR__."/../".$config->get("media_path");
define('MEDIA_PATH', $media_path);

// Media url
$media_url = '/media';
if ($config->get('media_url')) {
    $media_url = $config->get('media_url');
}
define('MEDIA_URL', $media_url);

// Get request
$request = Request::createFromGlobals();

/* MAINTENANCE MODE */

$maintenanceMode = $config->get("maintenance");
if (is_array($maintenanceMode) && $maintenanceMode["enabled"] === true) {
    $response = new Response($maintenanceMode["message"], 503);
    $response->send();
    die();
}

/* DATABASE */

// Get MySQL Credential from config
$dbConfig = $config->get("db");
$_MYSQL = $dbConfig;

try {
    $_SQL = Biblys\Database\Connection::init($_MYSQL);
} catch (Exception $e) {
    trigger_error($e->getMessage());
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
    $sitePath = __DIR__.'/../public/'.$site->get('name');
    define('SITE_PATH', $sitePath);
}

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

/**
 * @throws Exception
 */
function _date($dateToFormat, $format = 'd-m-Y')
{
    if ('0000-00-00 00:00:00' == $dateToFormat || '0000-00-00' == $dateToFormat || empty($dateToFormat)) {
        return false;
    }

    if ($dateToFormat instanceof DateTime) {
        $dateToFormat = $dateToFormat->format("Y-m-d H:i:s");
    }

    $stringMatchesDateWithoutMonth = preg_match("/^[0-9]{4}\$/", $dateToFormat);
    if ($stringMatchesDateWithoutMonth) {
        $dateToFormat .= '-01-01 00:00:00';
    }

    $stringMatchesDateWithoutDay = preg_match("/^[0-9]{4}-[0-9]{2}\$/", $dateToFormat);
    if ($stringMatchesDateWithoutDay) {
        $dateToFormat .= '-01 00:00:00';
    }

    $stringMatchesDateWithoutTime = preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}\$/", $dateToFormat);
    if ($stringMatchesDateWithoutTime) {
        $dateToFormat .= ' 00:00:00';
    }

    $stringMatchesDateTime = preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}\$/", $dateToFormat);
    if (!$stringMatchesDateTime) {
        throw new Exception("Cannot format date in unknown format: $dateToFormat");
    }

    list($dateString, $timeString) = explode(' ', $dateToFormat);
    if (empty($timeString)) {
        $timeString = "00:00:00";
    }

    list($year, $month, $day) = explode("-", $dateString);
    list($hours, $minutes, $seconds) = explode(":", $timeString);

    $timestamp = mktime((int) $hours, (int) $minutes, (int) $seconds, (int) $month, (int) $day, (int) $year);
    $dayOfWeek = date("N", $timestamp);
    $weekNumber = date("W", $timestamp);

    // Traduction mois
    if ('01' == $month) {
        $localizedMonth = 'janvier';
    } elseif ('02' == $month) {
        $localizedMonth = 'février';
    } elseif ('03' == $month) {
        $localizedMonth = 'mars';
    } elseif ('04' == $month) {
        $localizedMonth = 'avril';
    } elseif ('05' == $month) {
        $localizedMonth = 'mai';
    } elseif ('06' == $month) {
        $localizedMonth = 'juin';
    } elseif ('07' == $month) {
        $localizedMonth = 'juillet';
    } elseif ('08' == $month) {
        $localizedMonth = 'août';
    } elseif ('09' == $month) {
        $localizedMonth = 'septembre';
    } elseif ('10' == $month) {
        $localizedMonth = 'octobre';
    } elseif ('11' == $month) {
        $localizedMonth = 'novembre';
    } elseif ('12' == $month) {
        $localizedMonth = 'décembre';
    } else {
        throw new Exception("Cannot format date with unknown month: $month");
    }

    // Traduction jour de la semaine
    if (1 == $dayOfWeek) {
        $localizedDay = 'lundi';
    } elseif (2 == $dayOfWeek) {
        $localizedDay = 'mardi';
    } elseif (3 == $dayOfWeek) {
        $localizedDay = 'mercredi';
    } elseif (4 == $dayOfWeek) {
        $localizedDay = 'jeudi';
    } elseif (5 == $dayOfWeek) {
        $localizedDay = 'vendredi';
    } elseif (6 == $dayOfWeek) {
        $localizedDay = 'samedi';
    } elseif (7 == $dayOfWeek) {
        $localizedDay = 'dimanche';
    } else {
        throw new Exception("Cannot format date with day of week: $dayOfWeek");
    }

    $trans = [ // Pour le Samedi 5 septembre 2010 à 07h34m05
        'D' => date('D', $timestamp), // Sat
        'd' => $day, // 05
        'j' => date('j', $timestamp), // 5
        'l' => $localizedDay, // samedi
        'L' => ucwords($localizedDay), // Samedi
        'M' => date('M', $timestamp), // Sep
        'm' => $month, // 09
        'f' => $localizedMonth, // septembre
        'F' => ucwords($localizedMonth), // Septembre
        'Y' => $year, // 2010
        'H' => $hours, // 07
        'G' => date('G', $timestamp), // 7
        'i' => $minutes, // 34
        's' => $seconds,  // 05
        'W' => $weekNumber, // numero de la semaine dans l'annee
    ];

    return strtr($format, $trans);
}

function e404($debug = 'e404 function called without debug info')
{
    throw new Symfony\Component\Routing\Exception\ResourceNotFoundException($debug);
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

function file_dir($x)
{
    $x = substr($x, -2, 2);
    if (1 == strlen($x)) {
        $x = '0' . $x;
    }

    return $x;
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

/**
 * @deprecated Use Media->getUrl instead
 */
function media_url($type, $id, $size = '0'): string
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

/**
 * @deprecated Use Media->delete() instead
 */
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
            $path = biblysPath() . '/dl/pdf/' . file_dir($id) . '/' . $id . '.pdf';
        }
        if ('ebook-epub' == $type) {
            $path = biblysPath() . '/dl/epub/' . file_dir($id) . '/' . $id . '.epub';
        }
        if ('ebook-azw' == $type) {
            $path = biblysPath() . '/dl/azw/' . file_dir($id) . '/' . $id . '.azw';
        }
        $files = glob($path . '*');
        foreach ($files as $filename) {
            unlink($filename) or die('media delete error');
        }
    }
}


/**
 * @deprecated Use Media->exists() instead
 */
function media_exists($type, $id): bool
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
        $path = biblysPath() . '/dl/pdf/' . file_dir($id) . '/' . $id . '.pdf';
    }
    if ('ebook-epub' == $type) {
        $path = biblysPath() . '/dl/epub/' . file_dir($id) . '/' . $id . '.epub';
    }
    if ('ebook-azw' == $type) {
        $path = biblysPath() . '/dl/azw/' . file_dir($id) . '/' . $id . '.azw';
    }
    if (file_exists($path)) {
        return true;
    } else {
        return false;
    }
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

/**
 * @deprecated Using redirect() is deprecated. Use RedirectResponse instead.
 */
function redirect($url, $params = null, $text = null, $status = 302): void
{
    global $response;

    trigger_deprecation(
        "biblys/biblys",
        "2.52.0",
        "Using redirect() is deprecated. Use RedirectResponse instead."
    );

    if (is_array($params)) {
        $url .= '?' . http_build_query($params);
    }

    $response = new RedirectResponse($url, $status);
    $response->send();
    die();
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

// Returns site controller if it exists, or default controller, or false
function get_controller_path($controller): bool|string
{
    global $site;

    $default_path = __DIR__."/../controllers/common/php/".$controller.".php";
    $app_path = __DIR__."/../app/controllers/".$controller.".php";

    if (file_exists($app_path)) {
        return $app_path;
    }

    if (file_exists($default_path)) {
        return $default_path;
    }

    return false;
}

function loadEncoreAssets(string $env, string $fileType, string $userLevel = 'app'): array
{
    if ($env === "prod" || $env === "test") {
        $entrypointsFile = __DIR__.'/../public/assets/bundle/entrypoints.json';
    } else {
        $entrypointsFile = __DIR__.'/../public/build/entrypoints.json';
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
