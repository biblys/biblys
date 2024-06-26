<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

use Biblys\Data\ArticleType;

require_once "setUp.php";

class ArticleTypeTest extends PHPUnit\Framework\TestCase
{

    /**
     * Test creating a copy
     */
    public function testCreate()
    {
        $type = new ArticleType();

        $this->assertInstanceOf('Biblys\Data\ArticleType', $type);
    }

    public function testSetId()
    {
        $type = new ArticleType();

        $type->setId(1);
        $id = $type->getId();

        $this->assertEquals($id, 1);
    }

    public function testSetName()
    {
        $type = new ArticleType();

        $type->setName('Livre papier');
        $name = $type->getName();
        $slug = $type->getSlug();

        $this->assertEquals($name, 'Livre papier');
        $this->assertEquals($slug, 'livre-papier');
    }

    public function testSetSlug()
    {
        $type = new ArticleType();

        $type->setSlug('livre-papier');
        $slug = $type->getSlug();

        $this->assertEquals($slug, 'livre-papier');
    }

    public function testSetTax()
    {
        $type = new ArticleType();

        $type->setTax('BOOK');
        $slug = $type->getTax();

        $this->assertEquals($slug, 'BOOK');
    }

    public function testSetDownloadable()
    {
        $type = new ArticleType();

        $this->assertFalse($type->isDownloadable());

        $type->setDownloadable(true);

        $this->assertTrue($type->isDownloadable());
    }

    public function testSetPhysical()
    {
        $type = new ArticleType();

        $this->assertFalse($type->isPhysical());

        $type->setPhysical(true);

        $this->assertTrue($type->isPhysical());
    }

    public function testGetById()
    {
        $type = ArticleType::getById(1);

        $id = $type->getId();

        $this->assertEquals($id, 1);
    }

    public function testGetBySlug()
    {
        $type = ArticleType::getBySlug('livre-papier');

        $id = $type->getId();

        $this->assertEquals($id, 1);
    }

    public function testGetAllPhysicalTypes()
    {
        $types = ArticleType::getAllPhysicalTypes();

        foreach ($types as $type) {
            $this->assertTrue($type->isPhysical());
        }
    }

    public function testGetAllDownloadableTypes()
    {
        $types = ArticleType::getAllDownloadableTypes();

        foreach ($types as $type) {
            $this->assertTrue($type->isDownloadable());
        }
    }

    public function testGetTypeOptions()
    {
        $options = ArticleType::getOptions(3);

        $this->assertEquals($options[2], '<option value="3" selected>CD</option>');
    }
}
