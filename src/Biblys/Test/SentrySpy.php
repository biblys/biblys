<?php
/*
 * Copyright (C) 2026 Clément Latzarus
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

namespace Biblys\Test;

use Sentry\ClientBuilder;
use Sentry\Event;
use Sentry\SentrySdk;
use Sentry\Transport\Result;
use Sentry\Transport\ResultStatus;
use Sentry\Transport\TransportInterface;

class SentrySpy implements TransportInterface
{
    /** @var Event[] */
    private array $events = [];

    public static function install(): self
    {
        $spy = new self();
        $client = ClientBuilder::create([
            "dsn" => "https://key@sentry.example.com/1",
            "default_integrations" => false,
        ])
            ->setTransport($spy)
            ->getClient();
        SentrySdk::init()->bindClient($client);

        return $spy;
    }

    /**
     * @return string[] Messages of the exceptions reported to Sentry
     */
    public function getReportedExceptionMessages(): array
    {
        SentrySdk::getCurrentHub()->getClient()?->flush();

        $messages = [];
        foreach ($this->events as $event) {
            foreach ($event->getExceptions() as $exception) {
                $messages[] = $exception->getValue();
            }
        }

        return $messages;
    }

    public function send(Event $event): Result
    {
        $this->events[] = $event;
        return new Result(ResultStatus::success(), $event);
    }

    public function close(?int $timeout = null): Result
    {
        return new Result(ResultStatus::success());
    }
}
