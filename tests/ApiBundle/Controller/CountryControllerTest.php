<?php

namespace ApiBundle\Controller;

use Biblys\Service\CurrentUser;
use Biblys\Service\QueryParamsService;
use Biblys\Test\ModelFactory;
use Mockery;
use Model\CountryQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

class CountryControllerTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function testSearchAction(): void
    {
        // given
        $controller = new CountryController();

        $country = ModelFactory::createCountry(name: "Scarif", code: "SC");

        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->expects("authAdmin");
        $queryParamsService = Mockery::mock(QueryParamsService::class);
        $queryParamsService->expects("parse")
            ->with(["term" => ["type" => "string", "mb_min_length" => 3]]);
        $queryParamsService->expects("get")
            ->with("term")
            ->andReturn("Scar");

        // when
        $response = $controller->searchAction($currentUser, $queryParamsService);

        // then
        $expectedResponse = [
            "label" => "Scarif (SC)",
            "value" => $country->getId(),
        ];

        $parsedResponse = json_decode($response->getContent(), true);
        $this->assertContains($expectedResponse, $parsedResponse["results"]);
    }
}
