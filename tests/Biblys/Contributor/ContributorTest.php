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

    public function testCallingUnknownMethod()
    {
        // given
        $entityPeople = Factory::createPeople();
        $contributor = new Contributor(
            PeopleQuery::create()->findPk($entityPeople->get("id")),
            Job::getById(5),
            1
        );

        // then
        $this->expectException("InvalidArgumentException");
        $this->expectExceptionMessage("Cannot call unknown method unknownMethod on Contributor");

        // when
        $contributor->unknownMethod();
    }

    /**
     * @throws UnknownJobException
     * @throws Exception
     */
    public function testGetFromPeople()
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
        $firstName = $contributor->getFirstName();

        // then
        $this->assertEquals(
            "Monsieur",
            $firstName,
            "should return the correct property"
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
        $firstName = $contributor->get("first_name");

        // then
        $this->assertEquals(
            "Monsieur",
            $firstName,
            "should return the correct property"
        );
    }

    /**
     * @throws UnknownJobException
     * @throws Exception
     */
    public function testLegacyGetProperty()
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
        $firstName = $contributor->first_name();

        // then
        $this->assertEquals(
            "Monsieur",
            $firstName,
            "should return the correct property"
        );
    }

    /**
     * @throws UnknownJobException
     * @throws Exception
     */
    public function testLegacyGetJobName()
    {
        // given
        $entityPeople = Factory::createPeople([
            "people_first_name" => "Margotte",
            "people_last_name" => "Saint-James",
        ]);
        $contributor = new Contributor(
            PeopleQuery::create()->findPk($entityPeople->get("id")),
            Job::getById(1),
            1
        );

        // when
        $firstName = $contributor->job_name();

        // then
        $this->assertEquals(
            "Autrice",
            $firstName,
            "should return the correct property"
        );
    }

    /**
     * @throws UnknownJobException
     * @throws Exception
     */
    public function testLegacyGetJobNameWithArgument()
    {
        // given
        $entityPeople = Factory::createPeople([
            "people_first_name" => "Margotte",
            "people_last_name" => "Saint-James",
        ]);
        $contributor = new Contributor(
            PeopleQuery::create()->findPk($entityPeople->get("id")),
            Job::getById(1),
            1
        );

        // when
        $firstName = $contributor->get("job_name");

        // then
        $this->assertEquals(
            "Autrice",
            $firstName,
            "should return the correct property"
        );
    }
}
