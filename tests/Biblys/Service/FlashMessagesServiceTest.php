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


namespace Biblys\Service;

use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;

class FlashMessagesServiceTest extends TestCase
{
    public function testAddingFlashMessage(): void
    {
        // given
        $flashBag = Mockery::mock(FlashBag::class);
        $flashBag->expects("add");
        $session = Mockery::mock(Session::class);
        $session->expects("getFlashBag")->andReturn($flashBag);
        $service = new FlashMessagesService($session);

        // when
        $service->add("success", "The flash message was successfully added!");

        // then
        /** @noinspection PhpUndefinedMethodInspection */
        $flashBag->shouldHaveReceived("add")
            ->with("success", "The flash message was successfully added!");
        $this->expectNotToPerformAssertions();
    }
}
