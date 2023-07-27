<?php

namespace Biblys\Service\MailingList;

use Biblys\Service\Config;
use Biblys\Service\MailingList\Exception\InvalidConfigurationException;
use PHPUnit\Framework\TestCase;

class MailingListServiceTest extends TestCase
{
    /**
     * isConfigured
     */

    public function testIsConfiguredReturnsFalseWithoutConfiguration()
    {
        // given
        $config = $this->createMock(Config::class);
        $config->method("get")->with("mailing.service")->willReturn(null);
        $mailingListService = new MailingListService($config);

        // when
        $isConfigured = $mailingListService->isConfigured();

        // then
        $this->assertFalse($isConfigured);
    }

    public function testIsConfiguredReturnsTrueWithConfiguration()
    {
        // given
        $config = $this->createMock(Config::class);
        $config->method("get")->willReturn("mailjet");
        $mailingListService = new MailingListService($config);

        // when
        $isConfigured = $mailingListService->isConfigured();

        // then
        $this->assertTrue($isConfigured);
    }

    /**
     * getMailingList
     */

    public function testGetMailingListThrowsIfNotConfigured()
    {
        // given
        $config = $this->createMock(Config::class);
        $config->method("get")->with("mailing.service")->willReturn(null);
        $mailingListService = new MailingListService($config);

        // then
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage("Aucun service de gestion de liste de contacts n'est configuré.");

        // when
        $mailingListService->getMailingList();
    }

    public function testGetMailingListReturnsMailjetMailingListWhenConfigured()
    {
        // given
        $config = $this->createMock(Config::class);
        $config->method("get")->willReturn("mailjet");
        $mailingListService = new MailingListService($config);

        // when
        $list = $mailingListService->getMailingList();

        // then
        $this->assertInstanceOf(MailjetMailingList::class, $list);
    }
}
