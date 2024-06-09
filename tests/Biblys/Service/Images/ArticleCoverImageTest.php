<?php

namespace Biblys\Service\Images;

use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Exception;
use InvalidArgumentException;
use Model\Article;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__ . "/../../../setUp.php";

class ArticleCoverImageTest extends TestCase
{
    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testConstructor()
    {
        // given
        $image = ModelFactory::createImage();

        // when
        $exception = Helpers::runAndCatchException(function() use($image) {
            return new ArticleCoverImage($image, "", "");
        });

        // then
        $this->assertInstanceOf(InvalidArgumentException::class, $exception);
        $this->assertEquals('Image must be of type "cover"', $exception->getMessage());
    }

    /**
     * ArticleCoverImage->getPath()
     * @throws PropelException
     */
    public function testGetPath()
    {
        // given
        $image = ModelFactory::createImage(
            type: "cover",
            filePath: "path/to",
            fileName: "image-file.jpeg",
        );
        $basePath = "/www/paronymie/public/media";
        $baseUrl = "https://www.biblys.fr/images/";
        $image = new ArticleCoverImage($image, $basePath, $baseUrl);

        // when
        $url = $image->getFilePath();

        // then
        $this->assertEquals(
            "/www/paronymie/public/media/path/to/image-file.jpeg",
            $url,
            "returns correct path"
        );
    }

    /**
     * ArticleCoverImage->getUrl
     * @throws PropelException
     */

    public function testGetUrl()
    {
        // given
        $image = ModelFactory::createImage(
            type: "cover",
            filePath: "path/to",
            fileName: "image-file.jpeg",
        );
        $basePath = "/users/biblys/public/media";
        $baseUrl = "https://paronymie.fr/images";
        $image = new ArticleCoverImage($image, $basePath, $baseUrl);

        // when
        $url = $image->getUrl();

        // then
        $this->assertEquals(
            "https://paronymie.fr/images/path/to/image-file.jpeg",
            $url,
            "returns correct url"
        );
    }
}
