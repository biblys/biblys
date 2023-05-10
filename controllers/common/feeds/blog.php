<?php

use Biblys\Legacy\LegacyCodeHelper;

$_FEED["channel"]["title"] = LegacyCodeHelper::getLegacyCurrentSite()["site_title"];
$_FEED["channel"]["link"] = 'http://'. LegacyCodeHelper::getLegacyCurrentSite()["site_domain"].'/blog/';
$_FEED["channel"]["description"] = 'Les derniers billets du blog';

$posts = $_SQL->query(
    "SELECT * FROM `posts` 
	WHERE `site_id` = '". LegacyCodeHelper::getLegacyCurrentSite()["site_id"]."' AND `post_status` = 1 
		AND `post_date` <= NOW() 
	ORDER BY `post_date` DESC LIMIT 15"
);
while ($p = $posts->fetch()) {
    $p["url"] = 'http://'. LegacyCodeHelper::getLegacyCurrentSite()["site_domain"].'/blog/'.$p["post_url"];
    
    $item["title"] = $p["post_title"];
    $item["link"] = $p["url"];
    $item["guid"] = $p["url"];
    $item["pubDate"] = _date($p["post_date"], 'D, d M Y H:i:s +0100');
    $item["description"] = array('@cdata' => $p["post_content"]);
    
    $_FEED["channel"]["item"][] = $item;
}
$posts->closeCursor();
