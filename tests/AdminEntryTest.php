<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/
use Biblys\Admin\Entry;

require_once 'setUp.php';

class AdminEntryTest extends PHPUnit\Framework\TestCase
{
    /**
     * Test creating an entry.
     */
    public function testCreate()
    {
        $type = new Entry('Nouvel article');

        $this->assertInstanceOf('Biblys\Admin\Entry', $type);
    }

    public function testSetUrl()
    {
        $entry = new Entry('Nouvel article', ['url' => '/pages/articles']);

        $this->assertEquals($entry->getUrl(), '/pages/articles');
    }

    public function testSetTarget()
    {
        $entry = new Entry('Nouvel article', ['target' => '_blank']);

        $this->assertEquals($entry->getTarget(), '_blank');
    }

    public function testSetTargetDefault()
    {
        $entry = new Entry('Nouvel article', []);

        $this->assertEquals($entry->getTarget(), '_self');
    }

    public function testSetCategory()
    {
        $entry = new Entry('Nouvel article', ['category' => 'Articles']);

        $this->assertEquals($entry->getCategory(), 'Articles');
    }

    public function testSetIcon()
    {
        $entry = new Entry('Nouvel article');
        $this->assertEquals($entry->getIcon(), 'cog');

        $entry = new Entry('Nouvel article', ['icon' => 'user']);
        $this->assertEquals($entry->getIcon(), 'user');
    }

    public function testSetClass()
    {
        $entry = new Entry('Nouvel article', ['class' => 'stockQuickAdd']);

        $this->assertTrue($entry->hasClass());
        $this->assertEquals($entry->getClass(), 'stockQuickAdd');
    }

    public function testgSetTaskCount()
    {
        $entry = new Entry('Nouvel article', ['taskCount' => 5]);

        $this->assertEquals($entry->getTaskCount(), 5);
    }

    public function testSetSubscription()
    {
        $entry = new Entry('Commandes', ['subscription' => 'orders']);

        $this->assertEquals($entry->getSubscription(), 'orders');
    }
}
