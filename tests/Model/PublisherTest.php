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

class PublisherTest extends TestCase
{

    /**
     * @throws PropelException
     */
    protected function setUp(): void
    {
        PublisherQuery::create()->deleteAll();
    }

    /**
     * @throws PropelException
     */
    public function testSavePreprocessesAndCreatesNewPublisher()
    {
        // given
        $publisher = new Publisher();
        $publisher->setName("L'éditeur");

        // when
        $publisher->save();

        // then
        $this->assertNotNull($publisher->getId());
        $this->assertEquals("éditeur, L'", $publisher->getNameAlphabetic());
        $this->assertEquals("l-editeur", $publisher->getUrl());
    }

    /**
     * @throws PropelException
     */
    public function testSavePreprocessesAndUpdateExistingPublisher()
    {
        // given
        $publisher = ModelFactory::createPublisher(name: "L'éditeur", url: "l-editeur");
        $publisher->setName("L éditeur");

        // when
        $publisher->save();

        // then
        $this->assertEquals("L éditeur", $publisher->getName());
    }

    /**
     * @throws Exception
     */
    public function testSaveFailsIfPublisherDoesNotHaveAName(): void
    {
        // given
        $publisher = new Publisher();

        // when
        $exception = Helpers::runAndCatchException(fn() => $publisher->save());

        // then
        $this->assertInstanceOf(InvalidEntityException::class, $exception);
        $this->assertEquals("L'éditeur doit avoir un nom.", $exception->getMessage());
    }

    /**
     * @throws Exception
     */
    public function testSaveFailsIfAnotherPublisherExistsWithTheSameName(): void
    {
        // given
        ModelFactory::createPublisher(name: "LE MÊME ÉDITEUR", url: "le-meme-editeur");
        $publisher = new Publisher();
        $publisher->setName("Le même éditeur");

        // when
        $exception = Helpers::runAndCatchException(fn() => $publisher->save());

        // then
        $this->assertInstanceOf(EntityAlreadyExistsException::class, $exception);
        $this->assertEquals("Il existe déjà un éditeur avec le nom Le même éditeur.", $exception->getMessage());
    }
}
