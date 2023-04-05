<?php

namespace Biblys\Service\MailingList;

use Biblys\Service\Config;

class MailingListService
{
    private MailingListInterface $list;

    public function __construct(Config $config)
    {
        if ($config->get("mailing.service") === "mailjet") {
            $this->list = new MailjetMailingList($config);
            return;
        }

        $this->list = new BiblysMailingList($config);
    }

    public function getMailingList(): MailingListInterface
    {
        return $this->list;
    }
}