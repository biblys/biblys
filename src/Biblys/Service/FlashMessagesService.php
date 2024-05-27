<?php

namespace Biblys\Service;

use Symfony\Component\HttpFoundation\Session\Session;

class FlashMessagesService
{
    public function __construct(private readonly Session $session)
    {
    }

    public function add(string $type, string $message): void
    {
        $this->session->getFlashBag()->add($type, $message);
    }
}