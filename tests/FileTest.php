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

use Biblys\Test\EntityFactory;

require_once "setUp.php";

class FileTest extends PHPUnit\Framework\TestCase
{
    /**
     * Test creating a file
     */
    public function testCreate()
    {
        $rm = new FileManager();

        $file = $rm->create();

        $this->assertInstanceOf('File', $file);

        return $file;
    }

    /**
     * Test getting a file
     * @depends testCreate
     */
    public function testGet(File $file)
    {
        $rm = new FileManager();

        $gotFile = $rm->getById($file->get('id'));

        $this->assertInstanceOf('File', $file);
        $this->assertEquals($file->get('id'), $gotFile->get('id'));

        return $file;
    }

    /**
     * Test updating a file
     * @depends testGet
     */
    public function testUpdate(File $file)
    {
        $rm = new FileManager();

        $file->set('file_title', "The X-File");
        $rm->update($file);

        $updatedFile = $rm->getById($file->get('id'));

        $this->assertTrue($updatedFile->has('updated'));
        $this->assertEquals($updatedFile->get('file_title'), "The X-File");

        return $updatedFile;
    }

    /**
     * Test if all related bought copies are marked as updated
     */
    public function testMarkAsUpdated()
    {
        $am = new ArticleManager();
        $sm = new StockManager();
        $fm = new FileManager();

        $article = EntityFactory::createArticle();
        $soldCopy = $sm->create([
            "article_id" => $article->get("id"),
            "stock_selling_date" => date("Y-m-d H:i:s")
        ]);
        $availableCopy = $sm->create(["article_id" => $article->get("id")
        ]);
        $file = $fm->create(["article_id" => $article->get("id")]);

        $file->markAsUpdated();

        $soldCopy = $sm->getById($soldCopy->get('id'));
        $availableCopy = $sm->getById($availableCopy->get('id'));

        $this->assertEquals($soldCopy->get('file_updated'), 1, "Sold copy should be marked as updated.");
        $this->assertEquals($availableCopy->get('file_updated'), 0, "Available copy should not be marked as updated.");
    }

    /**
     * Test deleting a file
     * @depends testGet
     */
    public function testDelete(File $file)
    {
        $rm = new FileManager();

        $rm->delete($file, 'Test entity');

        $fileExists = $rm->getById($file->get('id'));

        $this->assertFalse($fileExists);
    }
}
