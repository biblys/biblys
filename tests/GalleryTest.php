<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

require_once('inc/functions.php');

class GalleryTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test creating a copy
     */
    public function testCreate()
    {
        $lm = new GalleryManager();

        $gallery = $lm->create();

        $this->assertInstanceOf('Gallery', $gallery);

        return $gallery;
    }

    /**
     * Test getting a copy
     * @depends testCreate
     */
    public function testGet(Gallery $gallery)
    {
        $lm = new GalleryManager();

        $gotGallery = $lm->getById($gallery->get('id'));

        $this->assertInstanceOf('Gallery', $gallery);
        $this->assertEquals($gallery->get('id'), $gotGallery->get('id'));

        return $gallery;
    }

    /**
     * Test updating a copy
     * @depends testGet
     */
    public function testUpdate(Gallery $gallery)
    {
        $lm = new GalleryManager();

        $gallery->set('gallery_title', 'Gallerytou');
        $lm->update($gallery);

        $updatedGallery = $lm->getById($gallery->get('id'));

        $this->assertTrue($updatedGallery->has('updated'));
        $this->assertEquals($updatedGallery->get('title'), 'Gallerytou');

        return $updatedGallery;
    }

    /**
     * Test deleting a copy
     * @depends testGet
     */
    public function testDelete(Gallery $gallery)
    {
        $lm = new GalleryManager();

        $lm->delete($gallery, 'Test entity');

        $galleryExists = $lm->getById($gallery->get('id'));

        $this->assertFalse($galleryExists);
    }
}
