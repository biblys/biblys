<?php

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
        $_SITE = LegacyCodeHelper::getGlobalSite();

        $awm = new AwardManager();

        $where = [];
        $filter = $_SITE->getOpt('publisher_filter');
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
