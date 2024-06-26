<?php

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