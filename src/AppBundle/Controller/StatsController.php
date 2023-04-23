<?php

namespace AppBundle\Controller;

use Biblys\Admin\Entry;
use Biblys\Service\Config;
use Framework\Controller;
use Propel\Runtime\Exception\PropelException;
use StockManager;
use SupplierManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
     */
    public function suppliersAction(Request $request, $year): Response
    {
        self::authAdmin($request);

        $sum = new SupplierManager();
        $suppliers = $sum->getAll([], [
            'order' => 'supplier_name'
        ]);

        return $this->render('AppBundle:Stats:suppliers.html.twig', [
            'suppliers' => $suppliers,
            'years' => array_reverse(range(2011, date('Y'))),
            'year' => $year
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function lostAction(Request $request, $year): Response
    {
        self::authAdmin($request);

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
        ]);
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
