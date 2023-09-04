<?php

namespace Biblys\Service\MailingList;

use Biblys\Service\Config;
use Biblys\Service\MailingList\Exception\InvalidConfigurationException;

class MailingListService
{
    private ?MailingListInterface $list = null;
    private bool $isConfigured = false;

    public function __construct(Config $config)
    {
        if ($config->get("mailing.service") === "mailjet") {
            $this->list = new MailjetMailingList($config);
            $this->isConfigured = true;
        }

        if ($config->get("mailing.service") === "brevo") {
            $this->list = new BrevoMailingList($config);
            $this->isConfigured = true;
        }
    }

    public function isConfigured(): bool
    {
        return $this->isConfigured;
    }

    /**
     * @throws InvalidConfigurationException
     */
    public function getMailingList(): ?MailingListInterface
    {
        if (!$this->isConfigured()) {
            throw new InvalidConfigurationException(
                "Aucun service de gestion de liste de contacts n'est configuré."
            );
        }

        return $this->list;
    }
}