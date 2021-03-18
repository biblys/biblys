<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

require_once "setUp.php";

class MailingTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test creating a mailing
     */
    public function testCreate()
    {
        $mm = new MailingManager();

        $mailing = $mm->create();

        $this->assertInstanceOf('Mailing', $mailing);

        return $mailing;
    }

    /**
     * Test getting a mailing
     * @depends testCreate
     */
    public function testGet(Mailing $mailing)
    {
        $mm = new MailingManager();

        $gotMailing = $mm->getById($mailing->get('id'));

        $this->assertInstanceOf('Mailing', $mailing);
        $this->assertEquals($mailing->get('id'), $gotMailing->get('id'));

        return $mailing;
    }

    /**
     * Test updating a mailing
     * @depends testGet
     */
    public function testUpdate(Mailing $mailing)
    {
        $mm = new MailingManager();

        $mailing->set('mailing_email', "user@example.com");
        $mm->update($mailing);

        $updatedMailing = $mm->getById($mailing->get('id'));

        $this->assertTrue($updatedMailing->has('updated'));
        $this->assertEquals($updatedMailing->get('mailing_email'), 'user@example.com');

        return $updatedMailing;
    }

    /**
     * Test if a mailing is subscribed
     * @depends testGet
     */
    public function testIsSubscribed(Mailing $mailing)
    {
        $this->assertFalse($mailing->isSubscribed());

        $mailing->set('mailing_checked', 1);
        $this->assertTrue($mailing->isSubscribed());

        $mailing->set('mailing_block', 1);
        $this->assertFalse($mailing->isSubscribed());
    }

    /**
     * Test if a mailing has unsubscribed
     * @depends testGet
     */
    public function testHasUnsubscribed(Mailing $mailing)
    {
        $mailing->set('mailing_block', 0);
        $this->assertFalse($mailing->hasUnsubscribed());

        $mailing->set('mailing_block', 1);
        $this->assertTrue($mailing->hasUnsubscribed());
    }

    /**
     * Test deleting a mailing
     * @depends testGet
     */
    public function testDelete(Mailing $mailing)
    {
        $mm = new MailingManager();

        $mm->delete($mailing, 'Test entity');

        $mailingExists = $mm->getById($mailing->get('id'));

        $this->assertFalse($mailingExists);
    }
}
