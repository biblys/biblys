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

class Log
{
    /**
     * @deprecated Legacy Log service is deprecated. Use LoggerService->log("errors", …) instead.
     */
    public static function error(string $level, string $message, array $context = []): void
    {
        $logger = new LoggerService();
        $logger->log('errors', $level, $message, $context);
    }

    /**
     * @deprecated Legacy Log service is deprecated. Use LoggerService->log("sql", …) instead.
     */
    public static function sql(string $level, string $message, array $context = []): void
    {
        $logger = new LoggerService();
        $logger->log('sql', $level, $message, $context);
    }

    /**
     * @deprecated Legacy Log service is deprecated. Use LoggerService->log("payplug", …) instead.
     */
    public static function payplug(string $level, string $message, array $context = []): void
    {
        $logger = new LoggerService();
        $logger->log('payplug', $level, $message, $context);
    }

    /**
     * @deprecated Legacy Log service is deprecated. Use LoggerService->log("stripe", …) instead.
     */
    public static function stripe(string $level, string $message, array $context = []): void
    {
        $logger = new LoggerService();
        $logger->log('stripe', $level, $message, $context);
    }
}
