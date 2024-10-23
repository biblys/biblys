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