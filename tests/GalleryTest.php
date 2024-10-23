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

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

require_once "setUp.php";

class GalleryTest extends PHPUnit\Framework\TestCase
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
