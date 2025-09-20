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
use Brevo\Client\Api\ContactsApi;
use Brevo\Client\ApiException;
use Brevo\Client\Configuration;
use Brevo\Client\Model\AddContactToList;
use Brevo\Client\Model\CreateContact;
use Brevo\Client\Model\GetExtendedContactDetails;
use Brevo\Client\Model\RemoveContactFromList;
use GuzzleHttp\Client;

class BrevoMailingList implements MailingListInterface
{

    private Config $config;
    private ContactsApi $client;
    private string $source = "Brevo";

    public function __construct(Config $config)
    {
        $this->config = $config;

        $config = Configuration::getDefaultConfiguration()->setApiKey(
            'api-key',
            $config->get("mailing.api_key"),
        );

        $this->client = new ContactsApi(
            new Client(),
            $config
        );
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
     * @throws ApiException
     */
    public function getContactCount(): int
    {
        $listId = $this->config->get("mailing.list_id");
        $result = $this->client->getList($listId);

        return $result["uniqueSubscribers"];
    }

    /**
     * @throws ApiException
     */
    public function getContacts(int $offset = 0, int $limit = 500): array
    {
        if ($limit > 500) {
            $limit = 500;
        }

        $listId = $this->config->get("mailing.list_id");
        $result = $this->client->getContactsFromList(
            listId: $listId,
            limit: $limit,
            offset: $offset,
            sort: "asc",
        );

        return array_map(function ($dto) {
            return new Contact($dto["email"], $dto["createdAt"]);
        }, $result["contacts"]);
    }

    /**
     * @throws ApiException
     */
    public function hasContact(string $emailAddress): bool
    {
        $contact = $this->_getContactForEmail($emailAddress);
        if ($contact === null) {
            return false;
        }

        $listId = $this->config->get("mailing.list_id");
        $listIds = $contact["listIds"] ?? [];
        return in_array($listId, $listIds);
    }

    /**
     * @throws ApiException
     */
    public function addContact(string $emailAddress, bool $force = false): void
    {
        $contact = $this->_getContactForEmail($emailAddress);
        if (!$contact) {
            $createContact = new CreateContact(["email" => $emailAddress]);
            $this->client->createContact($createContact);
        }

        if ($this->hasContact($emailAddress)) {
            return;
        }

        $listId = (int) $this->config->get("mailing.list_id");
        $contactEmails = new AddContactToList(["emails" => [$emailAddress]]);
        $this->client->addContactToList($listId, $contactEmails);
    }

    /**
     * @throws ApiException
     */
    public function removeContact(string $emailAddress): void
    {

        $contact = $this->_getContactForEmail($emailAddress);
        if (!$contact) {
            return;
        }

        if (!$this->hasContact($emailAddress)) {
            return;
        }

        $listId = (int) $this->config->get("mailing.list_id");
        $contactEmails = new RemoveContactFromList(["emails" => [$emailAddress]]);
        $this->client->removeContactFromList($listId, $contactEmails);
    }

    /**
     * @throws ApiException
     */
    private function _getContactForEmail(string $emailAddress): ?GetExtendedContactDetails
    {
        try {
            return $this->client->getContactInfo($emailAddress);
        } catch (ApiException $exception) {
            if ($exception->getCode() === 404) {
                return null;
            } else {
                throw $exception;
            }
        }
    }
}