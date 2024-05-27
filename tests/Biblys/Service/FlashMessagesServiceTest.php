<?php

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
