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
use Brevo\Brevo;
use Brevo\Contacts\ContactsClientInterface;
use Brevo\Contacts\Requests\AddContactToListRequest;
use Brevo\Contacts\Requests\CreateContactRequest;
use Brevo\Contacts\Requests\GetContactsFromListRequest;
use Brevo\Contacts\Requests\RemoveContactFromListRequest;
use Brevo\Contacts\Types\AddContactToListRequestBodyEmails;
use Brevo\Contacts\Types\GetContactInfoResponse;
use Brevo\Contacts\Types\RemoveContactFromListRequestBodyEmails;
use Brevo\Exceptions\BrevoApiException;

class BrevoMailingList implements MailingListInterface
{
    private Config $config;
    private ContactsClientInterface $client;
    private string $source = "Brevo";

    public function __construct(Config $config)
    {
        $this->config = $config;

        $brevo = new Brevo($config->get("mailing.api_key"));
        $this->client = $brevo->contacts;
    }

    public function setClient(ContactsClientInterface $client): void
    {
        $this->client = $client;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getLink(): string
    {
        $listId = $this->config->get("mailing.list_id");
        return "https://app.brevo.com/contact/list/id/$listId";
    }

    /**
     * @throws BrevoApiException
     */
    public function getContactCount(): int
    {
        $listId = (int) $this->config->get("mailing.list_id");
        $result = $this->client->getList($listId);

        return $result->uniqueSubscribers;
    }

    /**
     * @throws BrevoApiException
     */
    public function getContacts(int $offset = 0, int $limit = 500): array
    {
        if ($limit > 500) {
            $limit = 500;
        }

        $listId = (int) $this->config->get("mailing.list_id");
        $result = $this->client->getContactsFromList(
            $listId,
            new GetContactsFromListRequest([
                "limit" => $limit,
                "offset" => $offset,
                "sort" => "asc",
            ]),
        );

        return array_map(function ($dto) {
            return new Contact($dto->email, $dto->createdAt);
        }, $result->contacts);
    }

    /**
     * @throws BrevoApiException
     */
    public function hasContact(string $emailAddress): bool
    {
        $contact = $this->_getContactForEmail($emailAddress);
        if ($contact === null) {
            return false;
        }

        $listId = (int) $this->config->get("mailing.list_id");
        return in_array($listId, $contact->listIds);
    }

    /**
     * @throws BrevoApiException
     */
    public function addContact(string $emailAddress, bool $force = false): void
    {
        if (!$this->_getContactForEmail($emailAddress)) {
            $this->client->createContact(new CreateContactRequest(["email" => $emailAddress]));
        }

        if ($this->hasContact($emailAddress)) {
            return;
        }

        $listId = (int) $this->config->get("mailing.list_id");
        $this->client->addContactToList(
            $listId,
            new AddContactToListRequest([
                "body" => new AddContactToListRequestBodyEmails(["emails" => [$emailAddress]]),
            ]),
        );
    }

    /**
     * @throws BrevoApiException
     */
    public function removeContact(string $emailAddress): void
    {
        if (!$this->_getContactForEmail($emailAddress)) {
            return;
        }

        if (!$this->hasContact($emailAddress)) {
            return;
        }

        $listId = (int) $this->config->get("mailing.list_id");
        $this->client->removeContactFromList(
            $listId,
            new RemoveContactFromListRequest([
                "body" => new RemoveContactFromListRequestBodyEmails(["emails" => [$emailAddress]]),
            ]),
        );
    }

    /**
     * @throws BrevoApiException
     */
    private function _getContactForEmail(string $emailAddress): ?GetContactInfoResponse
    {
        try {
            return $this->client->getContactInfo($emailAddress);
        } catch (BrevoApiException $exception) {
            if ($exception->getCode() === 404) {
                return null;
            }
            throw $exception;
        }
    }
}
