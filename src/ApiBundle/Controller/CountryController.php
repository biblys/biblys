<?php

namespace ApiBundle\Controller;

use Biblys\Service\CurrentUser;
use Biblys\Service\QueryParamsService;
use Framework\Controller;
use Model\Country;
use Model\CountryQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\JsonResponse;

class CountryController extends Controller
{
    /**
     * @throws PropelException
     */
    public function searchAction(CurrentUser $currentUser, QueryParamsService $queryParams): JsonResponse
    {
        $currentUser->authAdmin();

        $queryParams->parse(["term" => ["type" => "string", "mb_min_length" => 3]]);
        $term = $queryParams->get("term");

        $countries = CountryQuery::create()
            ->filterByName("%$term%", Criteria::LIKE)
            ->find();

        $results = array_map(function (Country $country) {
            return [
                "label" => "{$country->getName()} ({$country->getCode()})",
                "value" => $country->getId(),
            ];
        }, $countries->getData());

        return new JsonResponse(["results" => $results]);
    }
}