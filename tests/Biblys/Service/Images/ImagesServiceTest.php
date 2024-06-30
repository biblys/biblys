<?php

namespace Biblys\Service\Images;

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
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
        $article = new Article();
        $article->setId(1984);
        $site = ModelFactory::createSite();

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->expects('copy');
        $service = new ImagesService($config, $currentSite, $filesystem);

        // when
        $service->addArticleCoverImage($article, __DIR__ . "/image.jpeg");

        // then
        $image = ImageQuery::create()->findOneByArticleId($article->getId());
        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals($site, $image->getSite());
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
        $site = ModelFactory::createSite();
        $article = ModelFactory::createArticle();
        $article->setId(1985);

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->expects("copy");
        $filesystem->expects("remove");
        $service = new ImagesService($config, $currentSite, $filesystem);

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
        $site = ModelFactory::createSite();
        $article = ModelFactory::createArticle();

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->expects("copy")->andThrow(new FileNotFoundException());
        $service = new ImagesService($config, $currentSite, $filesystem);

        // when
        $exception = Helpers::runAndCatchException(function () use ($service, $article) {
            $service->addArticleCoverImage($article, __DIR__ . "/image.jpeg");
        });

        // then
        $this->assertInstanceOf(FileNotFoundException::class, $exception);
        $image = ImageQuery::create()->findOneByArticleId($article->getId());
        $this->assertNull($image);
    }

    /** ImagesService->articleHasCoverImage */

    /**
     * @throws PropelException
     */
    public function testArticleHasCoverImageReturnsTrue()
    {
        // given
        $site = ModelFactory::createSite();
        $article = ModelFactory::createArticle();
        ModelFactory::createImage(article: $article);

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite, $filesystem);

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
        $site = ModelFactory::createSite();
        $article = ModelFactory::createArticle();

        $currentSite = new CurrentSite($site);
        $config = new Config();
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite ,$filesystem);

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
        $site = ModelFactory::createSite();
        $article = ModelFactory::createArticle();

        $config = new Config(["images" => ["path" => "/images/"]]);
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite, $filesystem);


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
        $site = ModelFactory::createSite();

        $config = new Config(["images" => ["base_url" => "/images/"]]);
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite, $filesystem);

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

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetCoverUrlForArticleWithVersion(): void
    {
        // given
        $site = ModelFactory::createSite();
        $article = ModelFactory::createArticle();
        ModelFactory::createImage(
            article: $article,
            filePath: "book/covers",
            fileName: "book-cover-updated.jpeg",
            version: 2,
        );

        $config = new Config(["images" => ["base_url" => "images/"]]);
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite, $filesystem);


        // when
        $coverUrl = $service->getCoverUrlForArticle($article);

        // then
        $this->assertEquals("images/book/covers/book-cover-updated.jpeg?v=2", $coverUrl);
    }


    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetCoverUrlForArticleWithCDN(): void
    {
        // given
        $site = ModelFactory::createSite();

        $config = new Config([
            "images" => [
                "base_url" => "https://paronymie.fr/images/",
                "cdn" => ["service" => "weserv"],
            ],
        ]);
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite, $filesystem);

        $article = ModelFactory::createArticle();
        ModelFactory::createImage(
            article: $article,
            filePath: "book/covers/",
            fileName: "book-cover.jpeg",
        );

        // when
        $coverUrl = $service->getCoverUrlForArticle($article);

        // then
        $this->assertEquals(
            "//images.weserv.nl/?url=https%3A%2F%2Fparonymie.fr%2Fimages%2Fbook%2Fcovers%2Fbook-cover.jpeg",
            $coverUrl
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetCoverUrlForArticleWithProtocolInBaseUrl(): void
    {
        // given
        $site = ModelFactory::createSite();
        $article = ModelFactory::createArticle();
        ModelFactory::createImage(
            article: $article,
            filePath: "book/covers",
            fileName: "book-cover-updated.jpeg",
            version: 2,
        );

        $config = new Config(["images" => ["base_url" => "https://paronymie.fr/images/"]]);
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite, $filesystem);

        // when
        $coverUrl = $service->getCoverUrlForArticle($article);

        // then
        $this->assertEquals("https://paronymie.fr/images/book/covers/book-cover-updated.jpeg?v=2", $coverUrl);
    }

    /** ImagesService->getCoverPathForArticle */

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetCoverPathForArticleIfItDoesNotExist(): void
    {
        // given
        $site = ModelFactory::createSite();
        $article = ModelFactory::createArticle();

        $config = new Config(["images" => ["base_url" => "/images/"]]);
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite, $filesystem);

        // when
        $coverUrl = $service->getCoverPathForArticle($article);

        // then
        $this->assertNull($coverUrl);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetCoverPathForArticleIfItExists(): void
    {
        // given
        $site = ModelFactory::createSite();

        $config = new Config(["images" => ["path" => "/images/"]]);
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite, $filesystem);

        $article = ModelFactory::createArticle();
        ModelFactory::createImage(
            article: $article,
            filePath: "book/covers/",
            fileName: "book-cover.jpeg",
        );

        // when
        $coverPath = $service->getCoverPathForArticle($article);

        // then
        $this->assertStringEndsWith("/images/book/covers/book-cover.jpeg", $coverPath);
    }

    /** ImagesService->deleteArticleCoverImage */

    /**
     * @throws PropelException
     */
    public function testDeleteArticleCoverImageDeletesImage(): void
    {
        // given
        $article = ModelFactory::createArticle();
        ModelFactory::createImage(article: $article);
        $site = ModelFactory::createSite();

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->expects("remove");
        $service = new ImagesService($config, $currentSite, $filesystem);

        // when
        $service->deleteArticleCoverImage($article);

        // then
        $deletedImage = ImageQuery::create()->findOneByArticleId($article->getId());
        $this->assertNull($deletedImage);
        $filesystem->shouldHaveReceived("remove");
    }

    /** ImagesService->deleteArticleCoverImage */

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testDeleteArticleCoverImageIfFileDeletionFails(): void
    {
        // given
        $article = ModelFactory::createArticle();
        ModelFactory::createImage(article: $article);
        $site = ModelFactory::createSite();

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->shouldReceive("remove")->andThrow(new FileNotFoundException());
        $service = new ImagesService($config, $currentSite, $filesystem);

        // when
        $exception = Helpers::runAndCatchException(function() use($service, $article) {
            $service->deleteArticleCoverImage($article);
        });

        // then
        $this->assertInstanceOf(FileNotFoundException::class, $exception);
        $image = ImageQuery::create()->findOneByArticleId($article->getId());
        $this->assertNotNull($image);
    }
}
