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
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ListmonkMailingList implements MailingListInterface
{
    private readonly Client $client;
    private string $source = "Listmonk";

    public function __construct(private readonly Config $config)
    {
        $this->client = new Client([
            'base_uri' => rtrim($config->get("mailing.base_url"), '/') . '/',
            'auth' => [
                $config->get("mailing.username"),
                $config->get("mailing.token"),
            ],
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getLink(): string
    {
        $baseUrl = rtrim($this->config->get("mailing.base_url"), '/');
        $listId = $this->config->get("mailing.list_id");
        return "$baseUrl/admin/lists/$listId";
    }

    /**
     * @throws GuzzleException
     */
    public function getContactCount(): int
    {
        $listId = $this->config->get("mailing.list_id");
        $response = $this->client->get("api/lists/$listId");
        $data = json_decode($response->getBody()->getContents(), true);

        return $data["data"]["subscriber_count"] ?? 0;
    }

    /**
     * @throws GuzzleException
     */
    public function getContacts(int $offset = 0, int $limit = 500): array
    {
        $listId = $this->config->get("mailing.list_id");
        $page = ($offset / $limit) + 1;

        $response = $this->client->get("api/subscribers", [
            'query' => [
                'list_id' => $listId,
                'page' => (int) $page,
                'per_page' => $limit,
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        $subscribers = $data["data"]["results"] ?? [];

        return array_map(function ($subscriber) {
            return new Contact($subscriber["email"], $subscriber["created_at"]);
        }, $subscribers);
    }

    /**
     * @throws GuzzleException
     */
    public function hasContact(string $emailAddress): bool
    {
        $subscriber = $this->_getSubscriberByEmail($emailAddress);
        if ($subscriber === null) {
            return false;
        }

        $listId = (int) $this->config->get("mailing.list_id");
        $lists = $subscriber["lists"] ?? [];

        foreach ($lists as $list) {
            if ($list["id"] === $listId && $list["subscription_status"] === "confirmed") {
                return true;
            }
        }

        return false;
    }

    /**
     * @throws GuzzleException
     */
    public function addContact(string $emailAddress, bool $force = false): void
    {
        $subscriber = $this->_getSubscriberByEmail($emailAddress);
        $listId = (int) $this->config->get("mailing.list_id");

        if ($subscriber === null) {
            $this->client->post("api/subscribers", [
                'json' => [
                    'email' => $emailAddress,
                    'name' => '',
                    'status' => 'enabled',
                    'lists' => [$listId],
                    'preconfirm_subscriptions' => true,
                ],
            ]);
            return;
        }

        if ($this->hasContact($emailAddress)) {
            return;
        }

        $this->client->put("api/subscribers/lists", [
            'json' => [
                'ids' => [$subscriber["id"]],
                'action' => 'add',
                'target_list_ids' => [$listId],
                'status' => 'confirmed',
            ],
        ]);
    }

    /**
     * @throws GuzzleException
     */
    public function removeContact(string $emailAddress): void
    {
        $subscriber = $this->_getSubscriberByEmail($emailAddress);
        if ($subscriber === null) {
            return;
        }

        if (!$this->hasContact($emailAddress)) {
            return;
        }

        $listId = (int) $this->config->get("mailing.list_id");
        $this->client->put("api/subscribers/lists", [
            'json' => [
                'ids' => [$subscriber["id"]],
                'action' => 'remove',
                'target_list_ids' => [$listId],
            ],
        ]);
    }

    /**
     * @throws GuzzleException
     */
    private function _getSubscriberByEmail(string $emailAddress): ?array
    {
        $response = $this->client->get("api/subscribers", [
            'query' => [
                'query' => "subscribers.email = '$emailAddress'",
                'per_page' => 1,
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        $results = $data["data"]["results"] ?? [];

        if (empty($results)) {
            return null;
        }

        return $results[0];
    }
}