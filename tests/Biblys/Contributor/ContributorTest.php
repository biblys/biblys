<?php

namespace Biblys\Contributor;

use Biblys\Test\Factory;
use Exception;
use Model\PeopleQuery;
use People;
use PHPUnit\Framework\TestCase;

require_once __DIR__."/../../setUp.php";

class ContributorTest extends TestCase
{

    /**
     * @throws Exception
     */
    public function testConstructor()
    {
        // then
        $this->expectException("TypeError");

        // given
        $people = new People([]);

        // when
        new Contributor(
            $people,
            Job::getById(3),
            1
        );
    }

    /**
     * @throws Exception
     */
    public function testGetRole()
    {
        // given
        $entityPeople = Factory::createPeople(["people_gender" => "F"]);
        $contributor = new Contributor(
            PeopleQuery::create()->findPk($entityPeople->get("id")),
            Job::getById(3),
            1
        );

        // when
        $role = $contributor->getRole();

        // then
        $this->assertEquals(
            "Traductrice",
            $role,
            "should return the correct role"
        );
    }

    /**
     * @throws UnknownJobException
     * @throws Exception
     */
    public function testGetContributionId()
    {
        // given
        $entityPeople = Factory::createPeople(["people_gender" => "F"]);
        $contributor = new Contributor(
            PeopleQuery::create()->findPk($entityPeople->get("id")),
            Job::getById(5),
            1
        );

        // when
        $contributionId = $contributor->getContributionId();

        // then
        $this->assertEquals(
            1,
            $contributionId,
            "should return the correct contribution id"
        );
    }

    /**
     * @throws UnknownJobException
     * @throws Exception
     */
    public function testLegacyGet()
    {
        // given
        $entityPeople = Factory::createPeople([
            "people_first_name" => "Monsieur",
            "people_last_name" => "Poulpe",
        ]);
        $contributor = new Contributor(
            PeopleQuery::create()->findPk($entityPeople->get("id")),
            Job::getById(5),
            1
        );

        // when
        $url = $contributor->get("url");

        // then
        $this->assertEquals(
            "monsieur-poulpe",
            $url,
            "should return the correct property"
        );
    }
}
