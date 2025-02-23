<?php
/*
 * Copyright (C) 2025 Clément Latzarus
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

use Biblys\Exception\CannotDeleteUser;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Exception;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

class UserTest extends TestCase
{
    /** delete */

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testDeleteIsImpossibleIfUserHasOrders(): void
    {
        // given
        $user = ModelFactory::createUser(email: "user-with-orders@example.org");
        ModelFactory::createOrder(user: $user);

        // when
        $exception = Helpers::runAndCatchException(fn() => $user->delete());

        // then
        $this->assertInstanceOf(CannotDeleteUser::class, $exception);
        $this->assertEquals(
            "Impossible de supprimer le compte user-with-orders@example.org car il a des commandes.",
            $exception->getMessage()
        );
        $this->assertFalse($user->isDeleted());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testDeleteIsImpossibleIfUserHasItemsInLibrary(): void
    {
        // given
        $user = ModelFactory::createUser(email: "user-with-library@example.org");
        ModelFactory::createStockItem(user: $user);

        // when
        $exception = Helpers::runAndCatchException(fn() => $user->delete());

        // then
        $this->assertInstanceOf(CannotDeleteUser::class, $exception);
        $this->assertEquals(
            "Impossible de supprimer le compte user-with-library@example.org car il a des livres dans sa bibliothèque numérique.",
            $exception->getMessage()
        );
        $this->assertFalse($user->isDeleted());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testDeleteIsImpossibleIfUserHasCustomerAccount(): void
    {
        // given
        $user = ModelFactory::createUser(email: "user-with-customer-account@example.org");
        ModelFactory::createCustomer(user: $user);

        // when
        $exception = Helpers::runAndCatchException(fn() => $user->delete());

        // then
        $this->assertInstanceOf(CannotDeleteUser::class, $exception);
        $this->assertEquals(
            "Impossible de supprimer le compte user-with-customer-account@example.org car il a un compte client.",
            $exception->getMessage()
        );
        $this->assertFalse($user->isDeleted());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testDeleteIsImpossibleIfUserHasDownloads(): void
    {
        // given
        $user = ModelFactory::createUser(email: "user-with-downloads@example.org");
        ModelFactory::createDownload(user: $user);

        // when
        $exception = Helpers::runAndCatchException(fn() => $user->delete());

        // then
        $this->assertInstanceOf(CannotDeleteUser::class, $exception);
        $this->assertEquals(
            "Impossible de supprimer le compte user-with-downloads@example.org car il a des téléchargements.",
            $exception->getMessage()
        );
        $this->assertFalse($user->isDeleted());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testDeleteIsImpossibleIfUserHasBlogPosts(): void
    {
        // given
        $user = ModelFactory::createUser(email: "user-with-posts@example.org");
        ModelFactory::createPost(user: $user);

        // when
        $exception = Helpers::runAndCatchException(fn() => $user->delete());

        // then
        $this->assertInstanceOf(CannotDeleteUser::class, $exception);
        $this->assertEquals(
            "Impossible de supprimer le compte user-with-posts@example.org car il a des billets de blog.",
            $exception->getMessage()
        );
        $this->assertFalse($user->isDeleted());
    }


    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testDeleteDeletesUser(): void
    {
        // given
        $user = ModelFactory::createUser(email: "user-to-delete@example.org");

        // when
        $user->delete();

        // then
        $this->assertTrue($user->isDeleted());
    }
}
