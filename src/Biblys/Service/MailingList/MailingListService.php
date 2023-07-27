<?php

namespace Biblys\Service\MailingList;

use Biblys\Service\Config;
use Exception;

class MailingListService
{
    private MailingListInterface $list;

    public function __construct(Config $config)
    {
        if ($config->get("mailing.service") === "mailjet") {
            $this->list = new MailjetMailingList($config);
            return;
        }

        throw new Exception("Aucun service de gestion de liste de contacts n'est configuré.");
    }

    public function getMailingList(): MailingListInterface
    {
        return $this->list;
    }
}