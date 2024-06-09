<?php

namespace Biblys\Service\Images;

use Biblys\Service\Config;
use Biblys\Test\ModelFactory;
use Mockery;
use Model\Article;
use Model\Image;
use Model\ImageQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . "/../../../setUp.php";

class ImagesServiceTest extends TestCase
{
    /** ImagesService->addArticleCoverImage */

    /**
     * @throws PropelException
     */
    public function testAddArticleCoverImageCreatesImage(): void
    {
        // given
        $config = new Config();
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->expects('copy');
        $service = new ImagesService($config, $filesystem);

        $article = new Article();
        $article->setId(1984);

        // when
        $service->addArticleCoverImage($article, __DIR__ . "/image.jpeg");

        // then
        $image = ImageQuery::create()->findOneByArticleId($article->getId());
        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals("cover", $image->getType());
        $this->assertEquals("/book/84/", $image->getFilepath());
        $this->assertEquals("1984.jpg", $image->getFilename());
        $this->assertEquals(1, $image->getVersion());
        $this->assertEquals("image/jpeg", $image->getMediatype());
        $this->assertEquals(14788, $image->getFilesize());
        $this->assertEquals(200, $image->getWidth());
        $this->assertEquals(300, $image->getHeight());
        $filesystem->shouldHaveReceived("copy");
    }

    /** ImagesServices->articleHasCoverImage */

    /**
     * @throws PropelException
     */
    public function testArticleHasCoverImageReturnsTrue()
    {
        // given
        $article = ModelFactory::createArticle();
        ModelFactory::createImage(article: $article);
        $config = Mockery::mock(Config::class);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $filesystem);

        // when
        $hasCover = $service->articleHasCoverImage($article);

        // then
        $this->assertTrue($hasCover);
    }

    /**
     * @throws PropelException
     */
    public function testArticleHasCoverImageReturnsFalse()
    {
        // given
        $article = ModelFactory::createArticle();
        $config = Mockery::mock(Config::class);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $filesystem);

        // when
        $hasCover = $service->articleHasCoverImage($article);

        // then
        $this->assertFalse($hasCover);
    }

    /**
     * ImagesServices->getCoverImageForArticle
     */

    public function testGetCoverImageForArticle()
    {
        // given
        $article = new Article();
        $article->setId(1984);
        $config = Mockery::mock(Config::class);
        $config->shouldReceive("get")->with("media_path")->andReturn(null);
        $config->shouldReceive("get")->with("media_url")->andReturn(null);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $filesystem);

        // when
        $cover = $service->getCoverImageForArticle($article);

        // then
        $this->assertInstanceOf(ArticleCoverImage::class, $cover);
    }
}
