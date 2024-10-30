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


namespace Framework;

use Biblys\Service\Config;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequestListener
{
    public function onUnsecureRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if ($request->isSecure()) {
            return;
        }

        $config = Config::load();
        if ($config->get('https') !== true) {
            return;
        }

        $httpsUrl = "https://" . $request->getHttpHost() . $request->getRequestUri();
        $response = new RedirectResponse($httpsUrl, 302);
        $event->setResponse($response);
    }
}
