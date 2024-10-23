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


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

$am = new ArticleManager();

$articleUrl  = $request->query->get("url");
$article = $am->get(["article_url" => $articleUrl]);

if (!$article) {
    throw new ResourceNotFoundException("No article for url " . $articleUrl . ".");
} else {
    redirect(\Biblys\Legacy\LegacyCodeHelper::getGlobalUrlGenerator()->generate('article_show', ['slug' => $article->get('url')]), null, 301);
}

