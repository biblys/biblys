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


use Biblys\Legacy\LegacyCodeHelper;

$_FEED["channel"]["title"] = LegacyCodeHelper::getGlobalSite()["site_title"];
$_FEED["channel"]["link"] = 'http://'. LegacyCodeHelper::getGlobalSite()["site_domain"].'/blog/';
$_FEED["channel"]["description"] = 'Les derniers billets du blog';

$posts = $_SQL->query(
    "SELECT * FROM `posts` 
	WHERE `site_id` = '". LegacyCodeHelper::getGlobalSite()["site_id"]."' AND `post_status` = 1 
		AND `post_date` <= NOW() 
	ORDER BY `post_date` DESC LIMIT 15"
);
while ($p = $posts->fetch()) {
    $p["url"] = 'http://'. LegacyCodeHelper::getGlobalSite()["site_domain"].'/blog/'.$p["post_url"];
    
    $item["title"] = $p["post_title"];
    $item["link"] = $p["url"];
    $item["guid"] = $p["url"];
    $item["pubDate"] = _date($p["post_date"], 'D, d M Y H:i:s +0100');
    $item["description"] = array('@cdata' => $p["post_content"]);
    
    $_FEED["channel"]["item"][] = $item;
}
$posts->closeCursor();
