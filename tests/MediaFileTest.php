<?php

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

require_once('inc/functions.php');

class MediaFileTest extends PHPUnit_Framework_TestCase
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
        global $site;

        $mfm = new MediaFileManager();
        $media = $mfm->create([
            'media_dir' => 'dir',
            'media_file' => 'file',
            'media_ext' => 'ext'
        ]);

        $site->set('site_name', 'site');

        $this->assertEquals($media->getUrl(), '/site/media/dir/file.ext');
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
