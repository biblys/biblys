<?php

namespace Biblys\Service\MailingList;

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Exception;
use MailingManager;
use Model\Base\Mailing;
use Model\MailingQuery;
use Propel\Runtime\ActiveQuery\Criteria;

class BiblysMailingList implements MailingListInterface
{
    private Config $config;
    private string $source = "Biblys";

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getLink(): string
    {
        return "/pages/adm_newsletter_users";
    }

    public function getContactCount(): int
    {
        $mailingQuery = $this->_getMailingQuery();
        return $mailingQuery->count();
    }

    public function getContacts(int $offset = 0, int $limit = 0): array
    {
        $mailingQuery = $this->_getMailingQuery();
        if ($limit !== 0) {
            $mailingQuery->offset($offset)->limit($limit);
        }

        $mailings = $mailingQuery->find()->getData();
        return array_map(function(Mailing $mailing) {
            return new Contact(
                email: $mailing->getEmail(),
                createdAt: $mailing->getCreatedAt("Y-m-d H:i:s"),
            );
        }, $mailings);
    }

    public function hasContact(string $emailAddress): bool
    {
        $mm = new MailingManager();
        $mailing = $mm->get(["mailing_email" => $emailAddress]);
        if ($mailing && $mailing->isSubscribed()) {
            return true;
        }
        return false;
    }

    /**
     * @throws Exception
     */
    public function addContact(string $emailAddress, bool $force = false): void
    {
        $mailingManager = new MailingManager();
        $mailingManager->addSubscriber($emailAddress, $force);
    }

    /**
     * @throws Exception
     */
    public function removeContact(string $emailAddress): void
    {
        $mailingManager = new MailingManager();
        $mailingManager->removeSubscriber($emailAddress);
    }

    /**
     * @return MailingQuery
     */
    public function _getMailingQuery(): MailingQuery
    {
        $currentSiteService = CurrentSite::buildFromConfig($this->config);
        return MailingQuery::create()
            ->filterBySiteId($currentSiteService->getSite()->getId())
            ->filterByChecked(1)
            ->filterByBlock(1, Criteria::NOT_EQUAL);
    }
}