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


namespace Usecase;

use Biblys\Test\ModelFactory;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

class DeleteUserUsecaseTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function testUsecaseDeletesUserAndRelatedData(): void
    {
        // given
        $usecase = new DeleteUserUsecase();
        $user = ModelFactory::createUser();
        $alert = ModelFactory::createAlert(user: $user);
        $authenticationMethod = ModelFactory::createAuthenticationMethod(user: $user);
        $cart = ModelFactory::createCart(user: $user);
        $option = ModelFactory::createUserOption(user: $user);
        $right = ModelFactory::createRight(user: $user);
        $session = ModelFactory::createUserSession(user: $user);
        $vote = ModelFactory::createVote(user: $user);
        $wishlist = ModelFactory::createWishlist(user: $user);
        $wish = ModelFactory::createWish(wishlist: $wishlist);

        // when
        $usecase->execute($user);

        // then
        $this->assertTrue($user->isDeleted());
        $this->assertTrue($alert->isDeleted());
        $this->assertTrue($authenticationMethod->isDeleted());
        $this->assertTrue($cart->isDeleted());
        $this->assertTrue($option->isDeleted());
        $this->assertTrue($right->isDeleted());
        $this->assertTrue($session->isDeleted());
        $this->assertTrue($vote->isDeleted());
        $this->assertTrue($wishlist->isDeleted());
        $this->assertTrue($wish->isDeleted());
    }
}
