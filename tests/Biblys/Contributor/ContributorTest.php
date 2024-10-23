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


namespace Biblys\Contributor;

use Biblys\Test\EntityFactory;
use Biblys\Test\ModelFactory;
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
        $entityPeople = EntityFactory::createPeople(["people_gender" => "F"]);
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
        $entityPeople = EntityFactory::createPeople(["people_gender" => "F"]);
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
        $entityPeople = EntityFactory::createPeople();
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
        $entityPeople = EntityFactory::createPeople([
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
        $entityPeople = EntityFactory::createPeople([
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
        $entityPeople = EntityFactory::createPeople([
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
        $entityPeople = EntityFactory::createPeople([
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
        $people = ModelFactory::createPeople(["gender" => "F"]);
        $contributor = new Contributor(
            $people,
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
