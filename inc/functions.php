<?php /** @noinspection PhpUnhandledExceptionInspection */

use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\Slug\SlugService;
use JetBrains\PhpStorm\NoReturn;
use Propel\Runtime\Exception\PropelException;
use Rollbar\Rollbar;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

// Default error level
ini_set('display_errors', 'On');
error_reporting(E_ALL);

// Catch trigger_error calls and turn them into exceptions
// to ensure execution is stopped until they are all replaced
// with proper exceptions
/** @noinspection PhpUnusedParameterInspection */
function errorHandler($errno, $errstr, $errfile, $errline): void
{
    $message = "An error was thrown using trigger_error in $errfile:$errline: $errstr";
    throw new Exception($message);
}
set_error_handler("errorHandler", E_USER_ERROR);

// Constants
require_once 'constants.php';

// Default host if none is specified
if (!isset($_SERVER['HTTP_HOST'])) {
    $_SERVER['HTTP_HOST'] = 'www.biblys.fr';
}

/* AUTOLOAD */

// Composer autoload
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

// Legacy entities autoload
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

// Get request
$request = Request::createFromGlobals();

/* MAINTENANCE MODE */

$maintenanceMode = $config->get("maintenance");
if (is_array($maintenanceMode) && $maintenanceMode["enabled"] === true) {
    $response = new Response($maintenanceMode["message"], 503);
    $response->send();
    die();
}

try {
    $_SQL = Biblys\Database\Connection::init($config);
} catch (ServiceUnavailableHttpException $exception) {
    $response = new Response($exception->getMessage(), 503);
    $response->send();
    die();
}

$config = Config::load();
Biblys\Database\Connection::initPropel($config);

function authors(?string $nameString, string $mode = null): ?string
{
    global $urlgenerator;

    if ($nameString === null) {
        return null;
    }

    $names = explode(',', $nameString);
    $nameCount = count($names);

    if ($nameCount > 2) {
        return "COLLECTIF";
    }

    if ($mode === "url") {
        $names = array_map(function ($name) use ($urlgenerator) {
            $slugService = new SlugService();
            $slug = $slugService->slugify($name);

            if ($slug === "") {
                return $name;
            }

            $url = $urlgenerator->generate("people_show", ["slug" => $slug]);
            return "<a href=\"$url\">$name</a>";
        }, $names);
    }

     if ($nameCount === 2) {
        return $names[0] . ' & ' . $names[1];
    } else {
        return $names[0];
    }
}

class InvalidDateFormatException extends Exception {}

/**
 * @throws InvalidDateFormatException
 */
function _date($dateToFormat, $format = 'd-m-Y'): bool|string
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
        throw new InvalidDateFormatException("Cannot format date in unknown format: $dateToFormat");
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
        throw new InvalidDateFormatException("Cannot format date with unknown month: $month");
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
        throw new InvalidDateFormatException("Cannot format date with day of week: $dayOfWeek");
    }

    $trans = [ // Pour le dimanche 5 septembre 2010 à 07h34m05
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

function error($x, $t = 'MySQL'): void
{

    global $_POST;
    if (is_array($x)) {
        $x = 'SQL Error #' . $x[1] . ' : ' . $x[2];
    }
    if ('404' != $t) {
        trigger_error($x, E_USER_ERROR);
    }
}

function file_dir($x): string
{
    $x = substr($x, -2, 2);
    if (1 == strlen($x)) {
        $x = '0' . $x;
    }

    return $x;
}

#[NoReturn] function json_error($errno, $errstr, $errfile = null, $errline = null): void
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
        $path = __DIR__ . '/../dl/pdf/' . file_dir($id) . '/' . $id . '.pdf';
    }
    if ('ebook-epub' == $type) {
        $path = __DIR__ . '/../dl/epub/' . file_dir($id) . '/' . $id . '.epub';
    }
    if ('ebook-azw' == $type) {
        $path = __DIR__ . '/../dl/azw/' . file_dir($id) . '/' . $id . '.azw';
    }
    if (file_exists($path)) {
        return true;
    } else {
        return false;
    }
}

function numero($x, $b = ' n&deg;&nbsp;'): string
{
    if (!empty($x)) {
        return $b . $x;
    } else {
        return '';
    }
}

function price($x, $m = null, $decimals = 2): float|int|string
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
 * @param bool $cents if true, the amount is in cents and should be
 *                      divided by 100 before display
 * @return string
 * @throws PropelException
 */
function currency(float $amount, bool $cents = false): string
{
    $config = Config::load();
    $currentSiteService = CurrentSite::buildFromConfig($config);
    $currency = $currentSiteService->getOption("currency");

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
#[NoReturn] function redirect($url, $params = null, $status = 302): void
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

    return "";
}

/**
 * Truncate a string.
 */
function truncate(
    string $text,
    int    $maxLength = 30,
    string $replacement = '',
    bool   $truncateAtSpace = false,
    bool   $withTooltip = false,
    bool   $lengthInBytes = false,
): string
{
    $lengthFunction = 'mb_strlen';
    if ($lengthInBytes) {
        $lengthFunction = 'strlen';
    }

    $text = strip_tags($text);
    $maxLength -= $lengthFunction($replacement);
    $stringLength = $lengthFunction($text);
    if ($stringLength <= $maxLength) {
        return $text;
    }
    if ($truncateAtSpace && ($spacePosition = strrpos($text, ' ', $maxLength - $stringLength))) {
        $maxLength = $spacePosition;
    }
    if ($withTooltip) {
        return '<span title="' . strip_tags($text) . '">' . substr_replace($text, $replacement, $maxLength) . '</span>';
    } else {
        return substr_replace($text, $replacement, $maxLength);
    }
}

/**
 * @deprecated makeurl is deprecated. Use SlugService->slugify instead
 */
function makeurl($x): string
{
    trigger_deprecation(
        "biblys/biblys",
        "2.68.0",
        "makeurl is deprecated. Use SlugService->slugify instead",
    );

    $slugService = new SlugService();
    return $slugService->slugify($x);
}

// Créer l'adresse de la page de la collection
function collection_url($publisher, $collection): string
{
    return CollectionManager::createSlug($publisher, $collection);
}

// Retirer l'article déterminant avec un titre
function alphabetize($text): array|string|null
{
    return preg_replace("#^(L'|l'|Le |le |LE |La |la |LA |Les |les |LES )(.*)#", '$2, $1', $text);
}

// Afficher nom d'utilisateur
function user_name($x)
{
    if (!empty($x['axys_account_screen_name'])) {
        $name = $x['axys_account_screen_name'];
    } elseif (!empty($x['axys_account_last_name'])) {
        $name = trim($x['axys_account_first_name'] . ' ' . $x['axys_account_last_name']);
    } elseif (!empty($x['order_last_name'])) {
        $name = trim($x['order_first_name'] . ' ' . $x['order_last_name']);
    } elseif (!empty($x['customer_last_name'])) {
        $name = trim($x['customer_first_name'] . ' ' . $x['customer_last_name']);
    } elseif (!empty($x['axys_account_email'])) {
        $name = $x['axys_account_email'];
    } elseif (!empty($x['customer_email'])) {
        $name = $x['customer_email'];
    } elseif (!empty($x['axys_account_id'])) {
        $name = $x['axys_account_id'];
    } elseif (!empty($x['customer_id'])) {
        $name = 'Client n° ' . $x['customer_id'];
    } else {
        $name = 'Inconnu';
    }

    return $name;
}

// Unité de taille
function file_size($s): string
{
    $i = 0;
    while ($s > 1024) {
        $s /= 1024;
        ++$i;
    }
    $p = 0;
    $u = "";
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
function tva_rate($tva, $date = null, $article_type = 1): bool|array|string
{
$config = Config::load();
    $currentSiteService = CurrentSite::buildFromConfig($config);
    if ('be' == $currentSiteService->getSite()->getTva()) {
        if (1 == $tva && 1 == $article_type) { // Livre papier
            $rate = '6';
        } else {
            $rate = '21';
        }
    } elseif ('fr' == $currentSiteService->getSite()->getTva()) {
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

    return $rate ?? false;
}

// Calcule un pourcentage
function percent($val1, $val2, $precision = 0): bool|string
{
    if (!$val2) {
        return false;
    }

    $division = $val1 / $val2;
    $res = $division * 100;
    $res = round($res, $precision);

    return $res . '&nbsp;%';
}

function get_template($template, $variables = []): Response
{
    $controller = new Framework\Controller();

    return $controller->render($template, $variables);
}

/**
 * Generates HTML for share buttons.
 *
 * Options:
 *  - class:   adds a class to main element
 *  - buttons: for each button, boolean to set whether the button should be shown
 *  - icons:   for each button, replace the default icon with another from set
 *  - images:  for each button, replace the default icon with an image
 */
function share_buttons($url, $text = null, $options = []): string
{
    // Buttons option

    $options['buttons'] = $options['buttons'] ?? [];
    $options['buttons'] = [
        'facebook' => $options['buttons']['facebook'] ?? true,
        'twitter' => $options['buttons']['twitter'] ?? true,
        'pinterest' => $options['buttons']['pinterest'] ?? true,
        'mail' => $options['buttons']['mail'] ?? true,
    ];

    // Icons option

    $options['icons'] = $options['icons'] ?? [];
    $options['icons'] = [
        'facebook' => $options['icons']['facebook'] ?? 'fa-facebook-square',
        'twitter' => $options['icons']['twitter'] ?? 'fa-twitter-square',
        'pinterest' => $options['icons']['pinterest'] ?? 'fa-pinterest-square',
        'mail' => $options['icons']['mail'] ?? 'fa-envelope',
    ];

    // Images option

    $options['images'] = $options['images'] ?? [];
    $options['images'] = [
        'facebook' => isset($options['images']['facebook']) ? '<img src="' . $options['images']['facebook'] . '" alt="Facebook">' : '<i class="fa fa-' . $options['icons']['facebook'] . ' fa-2x"></i>',
        'twitter' => isset($options['images']['twitter']) ? '<img src="' . $options['images']['twitter'] . '" alt="Twitter">' : '<i class="fa fa-' . $options['icons']['twitter'] . ' fa-2x"></i>',
        'pinterest' => isset($options['images']['pinterest']) ? '<img src="' . $options['images']['pinterest'] . '" alt="Twitter">' : '<i class="fa fa-' . $options['icons']['pinterest'] . ' fa-2x"></i>',
        'mail' => isset($options['images']['mail']) ? '<img src="' . $options['images']['mail'] . '" alt="E-mail">' : '<i class="fa fa-' . $options['icons']['mail'] . ' fa-2x"></i>',
    ];

    // Build buttons

    $buttons = [];

    if ($options['buttons']['facebook']) {
        $buttons['facebook'] = ' <li><a class="facebook-share-button" href="https://www.facebook.com/sharer/sharer.php?u=' . urlencode($url) . '" target="_blank" title="Partager sur Facebook">' . $options['images']['facebook'] . '</a></li>';
    }

    if ($options['buttons']['twitter']) {
        $buttons['twitter'] = ' <li><a class="twitter-share-button" href="https://twitter.com/intent/tweet?text=' . urlencode($text . ' ' . $url) . '&related=biblys" target="_blank" title="Partager sur Twitter">' . $options['images']['twitter'] . '</a></li>';
    }

    if ($options['buttons']['pinterest']) {
        $buttons['pinterest'] = ' <li><a class="pinterest-share-button" href="https://pinterest.com/pin/create/link/?url=' . urlencode($url) . '" target="_blank" title="Partager sur Pinterest">' . $options['images']['pinterest'] . '</a></li>';
    }

    if ($options['buttons']['mail']) {
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
    /** @noinspection PhpUnusedLocalVariableInspection */
    $globalSite = LegacyCodeHelper::getGlobalSite();

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
