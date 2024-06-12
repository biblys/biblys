<?php

namespace AppBundle\Controller;

use Framework\Controller;

use Symfony\Component\HttpFoundation\Request;

class StatsController extends Controller
{

    public function suppliersAction($year)
    {
        $this->auth('admin');
        $this->setPageTitle('Chiffre d\'affaires par fournisseur');

        $sum = $this->entityManager("Supplier");
        $suppliers = $sum->getAll([], [
            'order' => 'supplier_name'
        ]);

        return $this->render('AppBundle:Stats:suppliers.html.twig', [
            'suppliers' => $suppliers,
            'years' => array_reverse(range(2011, date('Y'))),
            'year' => $year
        ]);
    }

    public function lostAction($year)
    {
        $this->auth('admin');
        $this->setPageTitle('Exemplaire perdus');

        $year_filter = 'NOT NULL';
        if ($year != 'all') {
            $year_filter = "LIKE $year%";
        }

        $sm = $this->entityManager('Stock');
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
}
