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


namespace Model;

use Biblys\Exception\EntityAlreadyExistsException;
use Biblys\Exception\InvalidEntityException;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Exception;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

class PeopleTest extends TestCase
{
    /**
     * @throws PropelException
     */
    protected function setUp(): void
    {
        PeopleQuery::create()->deleteAll();
    }


    /** getFullName */

    public function testGetFullName()
    {
        // given
        $people = new People();
        $people->setFirstName("Mnémosyne");
        $people->setLastName("Pachidermata");

        // when
        $fullName = $people->getFullName();

        // then
        $this->assertEquals("Mnémosyne Pachidermata", $fullName);
    }

    public function testGetFullNameWithoutFirstName()
    {
        // given
        $people = new People();
        $people->setFirstName("");
        $people->setLastName("Y");

        // when
        $fullName = $people->getFullName();

        // then
        $this->assertEquals("Y", $fullName);
    }

    /** getAlphabeticalName */

    public function testGetAlphabeticalName()
    {
        // given
        $people = new People();
        $people->setFirstName("Mnémosyne");
        $people->setLastName("Pachidermata");

        // when
        $alphabeticalName = $people->getAlphabeticalName();

        // then
        $this->assertEquals("Pachidermata Mnémosyne", $alphabeticalName);
    }

    public function testGetAlphabeticalNameWithoutFirstName()
    {
        // given
        $people = new People();
        $people->setFirstName("");
        $people->setLastName("Y");

        // when
        $alphabeticalName = $people->getAlphabeticalName();

        // then
        $this->assertEquals("Y", $alphabeticalName);
    }

    /** save */

    /**
     * @throws PropelException
     */
    public function testSavesPreprocessesAndCreatesNewContributor()
    {
        // given
        $people = new People();
        $people->setFirstName("Mnémosyne");
        $people->setLastName("Pachidermata");

        // when
        $people->save();

        // then
        $this->assertNotNull($people->getId());
        $this->assertEquals("mnemosyne-pachidermata", $people->getUrl());
    }


    /**
     * @throws PropelException
     */
    public function testSavesPreprocessesAndUpdatesExistingContributor()
    {
        // given
        $people = ModelFactory::createContributor(firstName: "Mnémosyne", lastName: "Pachidermata");

        // when
        $people->save();

        // then
        $this->expectNotToPerformAssertions();
    }

    /**
     * @throws Exception
     */
    public function testSavesFailsIfLastNameIsMissing()
    {
        // given
        $people = new People();
        $people->setFirstName("Mnémosyne");

        // when
        $exception = Helpers::runAndCatchException(fn() => $people->save());

        // then
        $this->assertInstanceOf(InvalidEntityException::class, $exception);
        $this->assertEquals("Le contributeur doit avoir un nom.", $exception->getMessage());
    }

    /**
     * @throws Exception
     */
    public function testSavesFailsIfWebsiteUrlIsInvalid()
    {
        // given
        $people = new People();
        $people->setLastName("Pachidermata");
        $people->setSite("www.example.com");

        // when
        $exception = Helpers::runAndCatchException(fn() => $people->save());

        // then
        $this->assertInstanceOf(InvalidEntityException::class, $exception);
        $this->assertEquals("L'adresse du site web doit être une URL valide.", $exception->getMessage());
    }

    /**
     * @throws Exception
     */
    public function testSavesFailsIfContributorAlreadyExists()
    {
        // given
        ModelFactory::createContributor(firstName: "Mnémosyne", lastName: "Pachidermata");
        $people = new People();
        $people->setFirstName("Mnémosyne");
        $people->setLastName("Pachidermata");

        // when
        $exception = Helpers::runAndCatchException(fn() => $people->save());

        // then
        $this->assertInstanceOf(EntityAlreadyExistsException::class, $exception);
        $this->assertEquals(
            "Il existe déjà un contributeur avec le nom Mnémosyne Pachidermata.",
            $exception->getMessage()
        );
    }
}
