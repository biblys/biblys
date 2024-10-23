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

use AwardManager;
use Biblys\Legacy\LegacyCodeHelper;
use Framework\Controller;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AwardsController extends Controller
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function indexAction(): Response
    {
        $globalSite = LegacyCodeHelper::getGlobalSite();

        $awm = new AwardManager();

        $where = [];
        $filter = $globalSite->getOpt('publisher_filter');
        if ($filter) {
            $where = ['publisher_id' => explode(',', $filter)];
        }

        $awards = $awm->getAll($where, [
            'order' => 'award_year',
            'sort' => 'desc',
            'left-join' => [[
                'table' => 'articles',
                'key' => 'article_id'
            ]]
        ]);

        return $this->render('AppBundle:Award:index.html.twig', [
            'awards' => $awards,
        ]);
    }
}
