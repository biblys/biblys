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

use Biblys\Service\Config;
use Biblys\Service\CurrentUser;
use Exception;
use Framework\Controller;
use Propel\Runtime\Exception\PropelException;
use StockManager;
use SupplierManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class StatsController extends Controller
{

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     * @throws Exception
     */
    public function suppliersAction(
        CurrentUser $currentUser,
        $year,
    ): Response
    {
        $currentUser->authAdmin();

        $sum = new SupplierManager();
        $suppliers = $sum->getAll([], [
            'order' => 'supplier_name'
        ]);

        return $this->render('AppBundle:Stats:suppliers.html.twig', [
            'suppliers' => $suppliers,
            'years' => array_reverse(range(2011, date('Y'))),
            'year' => $year
        ], isPrivate: true);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     * @throws Exception
     */
    public function lostAction(CurrentUser $currentUser, $year): Response
    {
        $currentUser->authAdmin();

        $year_filter = 'NOT NULL';
        if ($year != 'all') {
            $year_filter = "LIKE $year%";
        }

        $sm = new StockManager();
        $copies = $sm->getAll([
            'stock_lost_date' => $year_filter
        ],[
            'order' => 'stock_lost_date',
            'sort' => 'desc'
        ]);

        $total = [];
        $total['count'] = 0;
        $total['purchase'] = 0;
        $total['selling_ht'] = 0;
        $total['selling_ttc'] = 0;
        foreach ($copies as $copy) {
            $total['count']++;
            $total['purchase'] += $copy->get('purchase_price');
            $total['selling_ht'] += $copy->get('selling_price_ht');
            $total['selling_ttc'] += $copy->get('selling_price');
        }

        return $this->render('AppBundle:Stats:lost.html.twig', [
            'copies' => $copies,
            'years' => array_reverse(range(2011, date('Y'))),
            'year' => $year,
            'total' => $total
        ], isPrivate: true);
    }

    public function matomo(Config $config): RedirectResponse
    {
        if (!$config->has("matomo.login") || !$config->has("matomo.md5pass")) {
            throw new NotFoundHttpException("Matomo is not configured.");
        }

        $queryString = http_build_query([
            "module" => "Login",
            "action" => "logme",
            "login" => $config->get("matomo.login"),
            "password" => $config->get("matomo.md5pass"),
        ]);
        $loginUrl = "https://{$config->get("matomo.domain")}/index.php?$queryString";

        return new RedirectResponse($loginUrl);
    }

    public function umami(Config $config): RedirectResponse
    {
        if (!$config->has("umami")) {
            throw new NotFoundHttpException("Matomo is not configured.");
        }

        return new RedirectResponse($config->get("umami.share_url"));
    }
}
