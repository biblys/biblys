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


namespace Biblys\Service\Images;

use Biblys\Service\Config;
use Biblys\Test\ModelFactory;
use Exception;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__ . "/../../../setUp.php";

class ImageForModelTest extends TestCase
{
    /** ImageForModel->getFilePath */

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetFilePath()
    {
        // given
        $config = new Config(["images" => ["path" => "/path/to/base"]]);
        $model = ModelFactory::createImage(filePath: "/directory/", fileName: "image.jpeg");
        $image = new ImageForModel($config, $model);

        // when
        $path = $image->getFilePath();

        // then
        $this->assertStringEndsWith("/src/Biblys/Service/Images/../../../../path/to/base/directory/image.jpeg", $path);
    }

    /** ImageForModel->getUrl */

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetUrl()
    {
        // given
        $config = new Config(["images" => ["base_url" => "https://example.org"]]);
        $model = ModelFactory::createImage(filePath: "/directory/", fileName: "image.jpeg");
        $image = new ImageForModel($config, $model);

        // when
        $path = $image->getUrl(1280, 720);

        // then
        $this->assertEquals("https://example.org/directory/image.jpeg", $path);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetUrlWithDefaultBaseUrl()
    {
        // given
        $config = new Config();
        $model = ModelFactory::createImage(filePath: "/directory/", fileName: "image.jpeg");
        $image = new ImageForModel($config, $model);

        // when
        $path = $image->getUrl(null, null);

        // then
        $this->assertEquals("/images/directory/image.jpeg", $path);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetUrlWithVersion()
    {
        // given
        $config = new Config(["images" => ["base_url" => "https://example.org"]]);
        $model = ModelFactory::createImage(filePath: "/directory/", fileName: "image.jpeg", version: 2);
        $image = new ImageForModel($config, $model);

        // when
        $path = $image->getUrl(1280, 720);

        // then
        $this->assertEquals("https://example.org/directory/image.jpeg?v=2", $path);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetUrlWithCDN()
    {
        // given
        $config = new Config(["images" => ["base_url" => "https://example.org", "cdn" => ["service" => "weserv"]]]);
        $model = ModelFactory::createImage(filePath: "/directory/", fileName: "image.jpeg", version: 2);
        $image = new ImageForModel($config, $model);

        // when
        $path = $image->getUrl(1280, 720);

        // then
        $this->assertEquals("https://images.weserv.nl/?url=https%3A%2F%2Fexample.org%2Fdirectory%2Fimage.jpeg%3Fv%3D2&w=1280&h=720", $path);
    }
}
