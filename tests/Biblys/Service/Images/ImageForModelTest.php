<?php

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
        $this->assertEquals("//images.weserv.nl/?url=https%3A%2F%2Fexample.org%2Fdirectory%2Fimage.jpeg%3Fv%3D2&w=1280&h=720", $path);
    }
}
