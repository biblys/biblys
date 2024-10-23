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


namespace Biblys\Service\MailingList;

use Biblys\Service\Config;
use Biblys\Service\MailingList\Exception\InvalidEmailAddressException;

interface MailingListInterface
{
    public function __construct(Config $config);

    public function getSource(): string;

    public function getLink(): string;

    public function getContactCount(): int;

    /**
     * @return Contact[]
     */
    public function getContacts(int $offset = 0, int $limit = 0): array;

    public function hasContact(string $emailAddress): bool;

    /**
     * @throws InvalidEmailAddressException
     */
    public function addContact(string $emailAddress, bool $force = false): void;

    public function removeContact(string $emailAddress): void;
}