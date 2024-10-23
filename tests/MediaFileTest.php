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

use Biblys\Legacy\LegacyCodeHelper;

require_once "setUp.php";

class MediaFileTest extends PHPUnit\Framework\TestCase
{
    /**
     * Test creating a copy
     */
    public function testCreate()
    {
        $lm = new MediaFileManager();

        $media = $lm->create();

        $this->assertInstanceOf('MediaFile', $media);

        return $media;
    }

    /**
     * Test getting a copy
     * @depends testCreate
     */
    public function testGet(MediaFile $media)
    {
        $lm = new MediaFileManager();

        $gotMediaFile = $lm->getById($media->get('id'));

        $this->assertInstanceOf('MediaFile', $media);
        $this->assertEquals($media->get('id'), $gotMediaFile->get('id'));

        return $media;
    }

    /**
     * Test updating a copy
     * @depends testGet
     */
    public function testUpdate(MediaFile $media)
    {
        $lm = new MediaFileManager();

        $media->set('media_title', 'MediaFiletou');
        $lm->update($media);

        $updatedMediaFile = $lm->getById($media->get('id'));

        $this->assertTrue($updatedMediaFile->has('updated'));
        $this->assertEquals($updatedMediaFile->get('title'), 'MediaFiletou');

        return $updatedMediaFile;
    }

    /**
     * Test getting media file url
     */
    public function testGetUrl()
    {
        $mfm = new MediaFileManager();
        $media = $mfm->create([
            'media_dir' => 'dir',
            'media_file' => 'file',
            'media_ext' => 'ext'
        ]);

        $this->assertEquals($media->getUrl(), '/media/dir/file.ext');
    }

    /**
     * Test deleting a copy
     */
    public function testDelete()
    {
        $mfm = new MediaFileManager();

        $media = $mfm->create([
            "media_dir" => "dir",
            "media_file" => "file",
            "media_ext" => "ext",
        ]);

        $mfm->delete($media, 'Test entity');

        $mediaExists = $mfm->getById($media->get('id'));

        $this->assertFalse($mediaExists);
    }
}
