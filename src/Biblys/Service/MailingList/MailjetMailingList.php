<?php

namespace Biblys\Service\MailingList;

use Biblys\Service\Config;
use Mailjet\Client;
use Mailjet\Client as Mailjet;
use Mailjet\Resources;
use Mailjet\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MailjetMailingList implements MailingListInterface
{

    private Config $config;
    private Client $client;
    private string $source = "Mailjet";

    public function __construct(Config $config)
    {
        $this->config = $config;
        $mailjet = new Mailjet(
            key: $config->get("mailing.client_id"),
            secret: $config->get("mailing.client_secret"),
        );
        $mailjet->setTimeout(20000);
        $mailjet->setConnectionTimeout(20000);

        $this->client = $mailjet;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getLink(): string
    {
        return "https://app.mailjet.com/contacts";
    }

    public function getContactCount(): int
    {
        $listId = $this->config->get("mailing.list_id");
        $response = $this->_queryMailjet(
            resource: Resources::$Contact,
            filters: ["ContactsList" => $listId, "countOnly" => true],
        );

        return $response->getTotal();
    }

    public function getContacts(int $offset = 0, int $limit = 1000): array
    {
        $listId = $this->config->get("mailing.list_id");
        $response = $this->_queryMailjet(
            resource: Resources::$Contact,
            filters: [
                "Offset" => $offset,
                "Limit" => $limit,
                "ContactsList" => $listId
            ],
        );

        return array_map(function (array $dto) {
            return new Contact($dto["Email"], $dto["CreatedAt"]);
        }, $response->getData());
    }

    public function addContact(string $emailAddress, bool $force = false): void
    {
        $action = $force ? "addforce" : "addnoforce";
        $this->_queryMailjet(
            resource: Resources::$ContactslistManagecontact,
            method: "post",
            id: $this->config->get("mailing.list_id"),
            body: ["Action" => $action, "Email" => $emailAddress]
        );
    }

    public function hasContact(string $emailAddress): bool
    {
        $listRecipients = $this->_queryMailjet(
            resource: Resources::$Listrecipient,
            filters: ["ContactEmail" => $emailAddress, "ContactList" => $this->config->get("mailing.list_id")]
        );

        if ($listRecipients->getCount() > 0) {
            return true;
        }
        return false;
    }

    public function removeContact(string $emailAddress): void
    {
        $this->_queryMailjet(
            resource: Resources::$ContactslistManagecontact,
            method: "post",
            id: $this->config->get("mailing.list_id"),
            body: ["Action" => "remove", "Email" => $emailAddress]
        );
    }

    private function _queryMailjet(
        array $resource,
        string $method = "get",
        int $id = 0,
        array $filters = [],
        array $body = [],
    ):
    Response
    {
        $response = $this->client->$method($resource, [
            "id" => $id,
            "filters" => $filters,
            "body" => $body,
        ]);
        if (!$response->success()) {
            $data = $response->getData();
            $errorDetails = join ($data["ErrorInfo"]);
            $errorMessage = "Mailjet a répondu : {$data["StatusCode"]} {$data["ErrorMessage"]} : $errorDetails";
            throw new BadRequestHttpException($errorMessage);
        }
        return $response;
    }
}