<?php

include("../inc/constants.php");

// INCLUDES
include(BIBLYS_PATH.'inc/functions.php');
include(BIBLYS_PATH.'inc/Array2XML.class.php');

// PAGE EN COURS
$_PAGE = str_replace("/feed/","",$_SERVER["REQUEST_URI"]);
$_PAGE = explode('?',$_PAGE);
$_PAGE = $_PAGE[0];

$_FEED = array('@attributes' => array('version' => '2.0', 'xmlns:atom' => 'http://www.w3.org/2005/Atom'));

$_FEED["channel"]["language"] = "fr";
$_FEED["channel"]["atom:link"] = array('@attributes' => array('href' => 'http://'.$_SITE["site_domain"].'/feed/'.$_PAGE, 'rel' => 'self', 'type' => 'application/rss+xml'));

// Recherche de la page site, par defaut, ou 404
$default_feed = BIBLYS_PATH.'/controllers/common/feeds/'.$_PAGE.'.php';
$app_feed = BIBLYS_PATH.'/app/controllers/'.$_PAGE.'_feed.php';

if (file_exists($default_feed)) {
    header("Content-Type: application/xml; charset=UTF-8");
    include($default_feed);
} elseif (file_exists($app_feed)) {
    header("Content-Type: application/xml; charset=UTF-8");
    include($app_feed);
} else {
    header("HTTP/1.0 404 Not Found");
    trigger_error("Unable to find $default_feed or $app_feed");
}

$xml = Array2XML::createXML('rss', $_FEED);
echo str_replace('&nbsp;',' ',$xml->saveXML());
