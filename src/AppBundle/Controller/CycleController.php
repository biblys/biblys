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


namespace AppBundle\Controller;

use ArticleManager;
use Biblys\Service\Pagination;
use CycleManager;
use Framework\Controller;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CycleController extends Controller
{
    /**
     * @route GET /cycle/{slug}.
     *
     * @throws PropelException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function showAction(Request $request, $slug): Response
    {
        $cm = new CycleManager();
        $am = new ArticleManager();

        $cycle = $cm->get(['cycle_url' => $slug]);
        if (!$cycle) {
            throw new NotFoundException("Cycle $slug not found");
        }

        $request->attributes->set("page_title", $cycle->get("name"));

        // Pagination
        $page = (int) $request->query->get("p", 0);
        $totalCount = $am->count(["cycle_id" => $cycle->get("id")]);
        $pagination = new Pagination($page, $totalCount);

        $articles = $am->getAll(["cycle_id" => $cycle->get("id")], [
            "order" => "article_tome",
            "limit" => $pagination->getLimit(),
            "offset" => $pagination->getOffset(),
        ]);

        return $this->render("AppBundle:Cycle:show.html.twig", [
            "cycle" => $cycle,
            "articles" => $articles,
            "pages" => $pagination,
        ]);
    }
}
