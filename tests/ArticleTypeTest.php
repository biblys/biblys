<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

use Biblys\Article\Type;

require_once "setUp.php";

class ArticleTypeTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test creating a copy
     */
    public function testCreate()
    {
        $type = new Type();

        $this->assertInstanceOf('Biblys\Article\Type', $type);
    }

    public function testSetId()
    {
        $type = new Type();

        $type->setId(1);
        $id = $type->getId();

        $this->assertEquals($id, 1);
    }

    public function testSetName()
    {
        $type = new Type();

        $type->setName('Livre papier');
        $name = $type->getName();
        $slug = $type->getSlug();

        $this->assertEquals($name, 'Livre papier');
        $this->assertEquals($slug, 'livre-papier');
    }

    public function testSetSlug()
    {
        $type = new Type();

        $type->setSlug('livre-papier');
        $slug = $type->getSlug();

        $this->assertEquals($slug, 'livre-papier');
    }

    public function testSetTax()
    {
        $type = new Type();

        $type->setTax('BOOK');
        $slug = $type->getTax();

        $this->assertEquals($slug, 'BOOK');
    }

    public function testSetDownloadable()
    {
        $type = new Type();

        $this->assertFalse($type->isDownloadable());

        $type->setDownloadable(true);

        $this->assertTrue($type->isDownloadable());
    }

    public function testSetPhysical()
    {
        $type = new Type();

        $this->assertFalse($type->isPhysical());

        $type->setPhysical(true);

        $this->assertTrue($type->isPhysical());
    }

    public function testGetById()
    {
        $type = Type::getById(1);

        $id = $type->getId();

        $this->assertEquals($id, 1);
    }

    public function testGetBySlug()
    {
        $type = Type::getBySlug('livre-papier');

        $id = $type->getId();

        $this->assertEquals($id, 1);
    }

    public function testGetAllPhysicalTypes()
    {
        $types = Type::getAllPhysicalTypes();

        foreach ($types as $type) {
            $this->assertTrue($type->isPhysical());
        }
    }

    public function testGetAllDownloadableTypes()
    {
        $types = Type::getAllDownloadableTypes();

        foreach ($types as $type) {
            $this->assertTrue($type->isDownloadable());
        }
    }

    public function testGetTypeOptions()
    {
        $options = Type::getOptions(3);

        $this->assertEquals($options[2], '<option value="3" selected>CD</option>');
    }
}
