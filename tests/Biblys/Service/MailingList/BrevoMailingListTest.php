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
use Brevo\Contacts\ContactsClientInterface;
use Brevo\Contacts\Types\GetContactInfoResponse;
use Brevo\Contacts\Types\GetListResponse;
use Brevo\Exceptions\BrevoApiException;
use Brevo\Types\GetContactDetails;
use Brevo\Types\GetContacts;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BrevoMailingListTest extends TestCase
{
    private Config&MockObject $config;
    private ContactsClientInterface&MockObject $client;
    private BrevoMailingList $brevoMailingList;

    protected function setUp(): void
    {
        $this->config = $this->createMock(Config::class);
        $this->config->method("get")->willReturnMap([
            ["mailing.api_key", "test-api-key"],
            ["mailing.list_id", "42"],
        ]);

        $this->client = $this->createMock(ContactsClientInterface::class);

        $this->brevoMailingList = new BrevoMailingList($this->config);
        $this->brevoMailingList->setClient($this->client);
    }

    /**
     * getSource
     */

    public function testGetSourceReturnsBrevo()
    {
        // when
        $source = $this->brevoMailingList->getSource();

        // then
        $this->assertEquals("Brevo", $source);
    }

    /**
     * getLink
     */

    public function testGetLinkReturnsBrevoListUrl()
    {
        // when
        $link = $this->brevoMailingList->getLink();

        // then
        $this->assertEquals("https://app.brevo.com/contact/list/id/42", $link);
    }

    /**
     * getContactCount
     */

    public function testGetContactCountReturnsUniqueSubscribers()
    {
        // given
        $listResponse = $this->createMock(GetListResponse::class);
        $listResponse->uniqueSubscribers = 123;
        $this->client->method("getList")->with(42)->willReturn($listResponse);

        // when
        $count = $this->brevoMailingList->getContactCount();

        // then
        $this->assertEquals(123, $count);
    }

    /**
     * getContacts
     */

    public function testGetContactsReturnsContactList()
    {
        // given
        $dto1 = $this->createMock(GetContactDetails::class);
        $dto1->email = "alice@example.com";
        $dto1->createdAt = "2024-01-01";
        $dto2 = $this->createMock(GetContactDetails::class);
        $dto2->email = "bob@example.com";
        $dto2->createdAt = "2024-02-01";

        $result = $this->createMock(GetContacts::class);
        $result->contacts = [$dto1, $dto2];
        $this->client->method("getContactsFromList")->willReturn($result);

        // when
        $contacts = $this->brevoMailingList->getContacts();

        // then
        $this->assertCount(2, $contacts);
        $this->assertInstanceOf(Contact::class, $contacts[0]);
        $this->assertEquals("alice@example.com", $contacts[0]->getEmail());
    }

    /**
     * hasContact
     */

    public function testHasContactReturnsTrueIfContactIsInList()
    {
        // given
        $contact = $this->createMock(GetContactInfoResponse::class);
        $contact->listIds = [42];
        $this->client->method("getContactInfo")->willReturn($contact);

        // when
        $result = $this->brevoMailingList->hasContact("alice@example.com");

        // then
        $this->assertTrue($result);
    }

    public function testHasContactReturnsFalseIfContactIsNotInList()
    {
        // given
        $contact = $this->createMock(GetContactInfoResponse::class);
        $contact->listIds = [99];
        $this->client->method("getContactInfo")->willReturn($contact);

        // when
        $result = $this->brevoMailingList->hasContact("alice@example.com");

        // then
        $this->assertFalse($result);
    }

    public function testHasContactReturnsFalseIfContactDoesNotExist()
    {
        // given
        $this->client->method("getContactInfo")
            ->willThrowException(new BrevoApiException("Not Found", 404, null));

        // when
        $result = $this->brevoMailingList->hasContact("unknown@example.com");

        // then
        $this->assertFalse($result);
    }

    /**
     * addContact
     */

    public function testAddContactCreatesAndAddsNewContact()
    {
        // given
        $this->client->method("getContactInfo")
            ->willThrowException(new BrevoApiException("Not Found", 404, null));
        $this->client->expects($this->once())->method("createContact");
        $this->client->expects($this->once())->method("addContactToList");

        // when
        $this->brevoMailingList->addContact("new@example.com");
    }

    public function testAddContactDoesNotAddIfAlreadyInList()
    {
        // given
        $contact = $this->createMock(GetContactInfoResponse::class);
        $contact->listIds = [42];
        $this->client->method("getContactInfo")->willReturn($contact);
        $this->client->expects($this->never())->method("addContactToList");

        // when
        $this->brevoMailingList->addContact("alice@example.com");
    }

    /**
     * removeContact
     */

    public function testRemoveContactRemovesFromList()
    {
        // given
        $contact = $this->createMock(GetContactInfoResponse::class);
        $contact->listIds = [42];
        $this->client->method("getContactInfo")->willReturn($contact);
        $this->client->expects($this->once())->method("removeContactFromList");

        // when
        $this->brevoMailingList->removeContact("alice@example.com");
    }

    public function testRemoveContactDoesNothingIfContactDoesNotExist()
    {
        // given
        $this->client->method("getContactInfo")
            ->willThrowException(new BrevoApiException("Not Found", 404, null));
        $this->client->expects($this->never())->method("removeContactFromList");

        // when
        $this->brevoMailingList->removeContact("unknown@example.com");
    }

    public function testRemoveContactDoesNothingIfNotInList()
    {
        // given
        $contact = $this->createMock(GetContactInfoResponse::class);
        $contact->listIds = [99];
        $this->client->method("getContactInfo")->willReturn($contact);
        $this->client->expects($this->never())->method("removeContactFromList");

        // when
        $this->brevoMailingList->removeContact("alice@example.com");
    }
}
