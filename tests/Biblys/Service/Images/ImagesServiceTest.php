<?php

namespace Biblys\Service\Images;

use Biblys\Service\Config;
use Biblys\Test\Helpers;
use Biblys\Test\ModelFactory;
use Exception;
use Mockery;
use Model\Article;
use Model\Image;
use Model\ImageQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
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
        $this->assertEquals("book/84/", $image->getFilepath());
        $this->assertEquals("1984.jpg", $image->getFilename());
        $this->assertEquals(1, $image->getVersion());
        $this->assertEquals("image/jpeg", $image->getMediatype());
        $this->assertEquals(14788, $image->getFilesize());
        $this->assertEquals(200, $image->getWidth());
        $this->assertEquals(300, $image->getHeight());
        $filesystem->shouldHaveReceived("copy");
    }

    /**
     * @throws PropelException
     */
    public function testAddArticleCoverImageCreatesUpdatesImage(): void
    {
        // given
        $config = new Config();
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->expects("copy");
        $filesystem->expects("remove");
        $service = new ImagesService($config, $filesystem);

        $article = ModelFactory::createArticle();
        $article->setId(1985);
        $service->addArticleCoverImage($article, __DIR__ . "/image.jpeg");
        $createdImage = ImageQuery::create()->findOneByArticleId($article->getId());

        // when
        $service->addArticleCoverImage($article, __DIR__ . "/image2.jpeg");

        // then
        $updatedImage = ImageQuery::create()->findOneByArticleId($article->getId());
        $this->assertInstanceOf(Image::class, $updatedImage);
        $this->assertEquals($createdImage->getId(), $updatedImage->getId());
        $this->assertEquals("book/85/", $updatedImage->getFilepath());
        $this->assertEquals("1985.jpg", $updatedImage->getFilename());
        $this->assertEquals(2, $updatedImage->getVersion());
        $this->assertEquals("image/jpeg", $updatedImage->getMediatype());
        $this->assertEquals(4410, $updatedImage->getFilesize());
        $this->assertEquals(100, $updatedImage->getWidth());
        $this->assertEquals(150, $updatedImage->getHeight());
        $filesystem->shouldHaveReceived("remove");
        $filesystem->shouldHaveReceived("copy");
    }

    /**
     * @throws Exception
     */
    public function testAddArticleCoverImageIfFileCopyFails(): void
    {
        // given
        $config = new Config();
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->expects("copy")->andThrow(new FileNotFoundException());
        $service = new ImagesService($config, $filesystem);

        $article = ModelFactory::createArticle();

        // when
        $exception = Helpers::runAndCatchException(function () use ($service, $article) {
            $service->addArticleCoverImage($article, __DIR__ . "/image.jpeg");
        });

        // then
        $this->assertInstanceOf(FileNotFoundException::class, $exception);
        $image = ImageQuery::create()->findOneByArticleId($article->getId());
        $this->assertNull($image);
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
        $config = new Config();
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
        $config = new Config();
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $filesystem);

        // when
        $hasCover = $service->articleHasCoverImage($article);

        // then
        $this->assertFalse($hasCover);
    }

    /** ImagesService->getCoverUrlForArticle */

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetCoverUrlForArticleIfItDoesNotExist(): void
    {
        // given
        $config = new Config(["media_url" => "/images/"]);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $filesystem);

        $article = ModelFactory::createArticle();

        // when
        $coverUrl = $service->getCoverUrlForArticle($article);

        // then
        $this->assertNull($coverUrl);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetCoverUrlForArticleIfItExists(): void
    {
        // given
        $config = new Config(["media_url" => "/images/"]);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $filesystem);

        $article = ModelFactory::createArticle();
        ModelFactory::createImage(
            article: $article,
            filePath: "book/covers/",
            fileName: "book-cover.jpeg",
        );

        // when
        $coverUrl = $service->getCoverUrlForArticle($article);

        // then
        $this->assertEquals("/images/book/covers/book-cover.jpeg", $coverUrl);
    }


    /** ImagesService->deleteArticleCoverImage */

    /**
     * @throws PropelException
     */
    public function testDeleteArticleCoverImageDeletesImage(): void
    {
        // given
        $config = new Config();
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->expects("remove");
        $service = new ImagesService($config, $filesystem);

        $article = ModelFactory::createArticle();
        ModelFactory::createImage(article: $article);

        // when
        $service->deleteArticleCoverImage($article);

        // then
        $deletedImage = ImageQuery::create()->findOneByArticleId($article->getId());
        $this->assertNull($deletedImage);
        $filesystem->shouldHaveReceived("remove");
    }
    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetCoverUrlForArticleWithVersion(): void
    {
        // given
        $config = new Config(["media_url" => "images/"]);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $filesystem);

        $article = ModelFactory::createArticle();
        ModelFactory::createImage(
            article: $article,
            filePath: "book/covers",
            fileName: "book-cover-updated.jpeg",
            version: 2,
        );

        // when
        $coverUrl = $service->getCoverUrlForArticle($article);

        // then
        $this->assertEquals("images/book/covers/book-cover-updated.jpeg?v=2", $coverUrl);
    }
}
