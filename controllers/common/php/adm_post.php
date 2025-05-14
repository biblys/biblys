<?php
/*
 * Copyright (C) 2025 Clément Latzarus
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


use Biblys\Service\QueryParamsService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGenerator;

return function(QueryParamsService $queryParams, UrlGenerator $urlGenerator): RedirectResponse
{
    $queryParams->parse(["id" => ["type" => "numeric", "default" => null]]);
    $postId = $queryParams->getInteger("id");

    $url = $urlGenerator->generate("post_new");
    if ($postId) {
        $url = $urlGenerator->generate("post_edit", ["id" => $postId]);
    }

    return new RedirectResponse($url);
};
