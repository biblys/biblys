<?php

namespace AppBundle\Controller;

use Framework\Controller;

class AwardsController extends Controller
{
    public function indexAction()
    {
        global $site;

        $this->setPageTitle('Récompenses littéraires');

        $awm = $this->entityManager("Award");

        $filter = $site->getOpt('publisher_filter');
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
