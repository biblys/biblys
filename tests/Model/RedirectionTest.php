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

use Biblys\Exception\InvalidEntityException;
use Biblys\Test\Helpers;
use Exception;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

class RedirectionTest extends TestCase
{
    /**
     * @throws PropelException
     */
    protected function setUp(): void
    {
        RedirectionQuery::create()->deleteAll();
    }

    public function testSavingValidRedirection()
    {
        // given
        $redirection = new Redirection();
        $redirection->setOldUrl("/old-url");
        $redirection->setNewUrl("/new-url");

        // when
        $redirection->save();

        // then
        $this->assertNotNull($redirection->getId(), "should save the redirection");
    }

    /**
     * @throws Exception
     */
    public function testSavingWhenOldUrlDoesNotStartWithASlash()
    {
        // given
        $redirection = new Redirection();
        $redirection->setOldUrl("old-url");
        $redirection->setNewUrl("/new-url");

        // when
        $exception = Helpers::runAndCatchException(fn () => $redirection->save());

        // then
        $this->assertInstanceOf(InvalidEntityException::class, $exception);
        $this->assertEquals("L'ancienne URL doit commencer par un slash (/).", $exception->getMessage());
    }

    public function testSavingWhenNewUrlDoesNotStartWithASlash()
    {
        // given
        $redirection = new Redirection();
        $redirection->setOldUrl("/old-url");
        $redirection->setNewUrl("new-url");

        // when
        $exception = Helpers::runAndCatchException(fn () => $redirection->save());

        // then
        $this->assertInstanceOf(InvalidEntityException::class, $exception);
        $this->assertEquals("La nouvelle URL doit commencer par un slash (/).", $exception->getMessage());
    }

    /**
     * @throws Exception
     */
    public function testSavingWhenOldUrlEqualsNewUrl()
    {
        // given
        $redirection = new Redirection();
        $redirection->setOldUrl("/same-url");
        $redirection->setNewUrl("/same-url");

        // when
        $exception = Helpers::runAndCatchException(fn () => $redirection->save());

        // then
        $this->assertInstanceOf(InvalidEntityException::class, $exception);
        $this->assertEquals("L'ancienne URL et la nouvelle URL doivent être différentes.", $exception->getMessage());
    }
}
