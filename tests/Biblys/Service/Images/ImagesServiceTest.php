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
use Model\Stock;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__ . "/../../../setUp.php";

class ImagesServiceTest extends TestCase
{
    /** ImagesService->addImageFor (article) */

    /**
     * @throws PropelException
     */
    public function testAddImageForCreatesImageWithArticle(): void
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
        $service->addImageFor($article, __DIR__ . "/image.jpeg");

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
    public function testAddImageForCreatesUpdatesImageWithArticle(): void
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

        $service->addImageFor($article, __DIR__ . "/image.jpeg");
        $createdImage = ImageQuery::create()->findOneByArticleId($article->getId());

        // when
        $service->addImageFor($article, __DIR__ . "/image2.jpeg");

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
    public function testAddImageForIfFileCopyFailsWithArticle(): void
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
            $service->addImageFor($article, __DIR__ . "/image.jpeg");
        });

        // then
        $this->assertInstanceOf(FileNotFoundException::class, $exception);
        $image = ImageQuery::create()->findOneByArticleId($article->getId());
        $this->assertNull($image);
    }

    /** ImagesService->addImageFor (stockItem) */

    /**
     * @throws PropelException
     */
    public function testAddImageForCreatesImageWithStockItem(): void
    {
        // given
        $stockItem = new Stock();
        $stockItem->setId(1984);
        $site = ModelFactory::createSite();

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->expects('copy');
        $service = new ImagesService($config, $currentSite, $filesystem);

        // when
        $service->addImageFor($stockItem, __DIR__ . "/image.jpeg");

        // then
        $image = ImageQuery::create()->filterByStockItem($stockItem)->findOne();
        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals($site, $image->getSite());
        $this->assertEquals("photo", $image->getType());
        $this->assertEquals("stock/84/", $image->getFilepath());
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
    public function testAddImageForCreatesUpdatesImageWithStockItem(): void
    {
        // given
        $site = ModelFactory::createSite();
        $stockItem = ModelFactory::createStockItem();
        $stockItem->setId(1985);

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->expects("copy");
        $filesystem->expects("remove");
        $service = new ImagesService($config, $currentSite, $filesystem);

        $service->addImageFor($stockItem, __DIR__ . "/image.jpeg");
        $createdImage = ImageQuery::create()->filterByStockItem($stockItem)->findOne();

        // when
        $service->addImageFor($stockItem, __DIR__ . "/image2.jpeg");

        // then
        $updatedImage = ImageQuery::create()->filterByStockItem($stockItem)->findOne();
        $this->assertInstanceOf(Image::class, $updatedImage);
        $this->assertEquals($createdImage->getId(), $updatedImage->getId());
        $this->assertEquals("stock/85/", $updatedImage->getFilepath());
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
    public function testAddImageForIfFileCopyFailsWithStockItem(): void
    {
        // given
        $site = ModelFactory::createSite();
        $stockItem = ModelFactory::createStockItem();

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->expects("copy")->andThrow(new FileNotFoundException());
        $service = new ImagesService($config, $currentSite, $filesystem);

        // when
        $exception = Helpers::runAndCatchException(function () use ($service, $stockItem) {
            $service->addImageFor($stockItem, __DIR__ . "/image.jpeg");
        });

        // then
        $this->assertInstanceOf(FileNotFoundException::class, $exception);
        $image = ImageQuery::create()->filterByStockItem($stockItem)->findOne();
        $this->assertNull($image);
    }

    /** ImagesService->imageExistsFor (article) */

    /**
     * @throws PropelException
     */
    public function testImageExistsForReturnsTrueWithArticle()
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
        $hasCover = $service->imageExistsFor($article);

        // then
        $this->assertTrue($hasCover);
    }

    /**
     * @throws PropelException
     */
    public function testImageExistsForReturnsFalseWithArticle()
    {
        // given
        $site = ModelFactory::createSite();
        $article = ModelFactory::createArticle();

        $currentSite = new CurrentSite($site);
        $config = new Config();
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite ,$filesystem);

        // when
        $hasCover = $service->imageExistsFor($article);

        // then
        $this->assertFalse($hasCover);
    }

    /** ImagesService->imageExistsFor (stockItem) */

    /**
     * @throws PropelException
     */
    public function testImageExistsForReturnsTrueWithStockItem()
    {
        // given
        $site = ModelFactory::createSite();
        $stockItem = ModelFactory::createStockItem();
        ModelFactory::createImage(stockItem: $stockItem);

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite, $filesystem);

        // when
        $hasCover = $service->imageExistsFor($stockItem);

        // then
        $this->assertTrue($hasCover);
    }

    /**
     * @throws PropelException
     */
    public function testImageExistsForReturnsFalseWithStockItem()
    {
        // given
        $site = ModelFactory::createSite();
        $stockItem = ModelFactory::createStockItem();

        $currentSite = new CurrentSite($site);
        $config = new Config();
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite ,$filesystem);

        // when
        $hasCover = $service->imageExistsFor($stockItem);

        // then
        $this->assertFalse($hasCover);
    }

    /** ImagesService->getImageUrlFor (article) */

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetImageUrlForIfItDoesNotExistWithArticle(): void
    {
        // given
        $site = ModelFactory::createSite();
        $article = ModelFactory::createArticle();

        $config = new Config(["images" => ["path" => "/images/"]]);
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite, $filesystem);


        // when
        $coverUrl = $service->getImageUrlFor($article);

        // then
        $this->assertNull($coverUrl);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetImageUrlForIfItExistsWithArticle(): void
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
        $coverUrl = $service->getImageUrlFor($article);

        // then
        $this->assertEquals("/images/book/covers/book-cover.jpeg", $coverUrl);
    }

    /** ImagesService->getImageUrlFor (stockItem) */

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetImageUrlForIfItDoesNotExistWithStockItem(): void
    {
        // given
        $site = ModelFactory::createSite();
        $stockItem = ModelFactory::createStockItem();

        $config = new Config(["images" => ["path" => "/images/"]]);
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite, $filesystem);


        // when
        $coverUrl = $service->getImageUrlFor($stockItem);

        // then
        $this->assertNull($coverUrl);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetImageUrlForIfItExistsWithStockItem(): void
    {
        // given
        $site = ModelFactory::createSite();

        $config = new Config(["images" => ["base_url" => "/images/"]]);
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite, $filesystem);

        $stockItem = ModelFactory::createStockItem();
        ModelFactory::createImage(
            stockItem: $stockItem,
            filePath: "book/covers/",
            fileName: "book-cover.jpeg",
        );

        // when
        $coverUrl = $service->getImageUrlFor($stockItem);

        // then
        $this->assertEquals("/images/book/covers/book-cover.jpeg", $coverUrl);
    }

    /** ImagesService->deleteImageFor */

    /**
     * @throws PropelException
     */
    public function testDeleteImageForDeletesImageWithArticle(): void
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
        $service->deleteImageFor($article);

        // then
        $deletedImage = ImageQuery::create()->findOneByArticleId($article->getId());
        $this->assertNull($deletedImage);
        $filesystem->shouldHaveReceived("remove");
    }

    /**
     * @throws PropelException
     */
    public function testDeleteImageForDeletesImageWithStockItem(): void
    {
        // given
        $stockItem = ModelFactory::createStockItem();
        ModelFactory::createImage(stockItem: $stockItem);
        $site = ModelFactory::createSite();

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->expects("remove");
        $service = new ImagesService($config, $currentSite, $filesystem);

        // when
        $service->deleteImageFor($stockItem);

        // then
        $deletedImage = ImageQuery::create()->filterByStockItem($stockItem)->findOne();
        $this->assertNull($deletedImage);
        $filesystem->shouldHaveReceived("remove");
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testDeleteImageForIfFileDeletionFails(): void
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
            $service->deleteImageFor($article);
        });

        // then
        $this->assertInstanceOf(FileNotFoundException::class, $exception);
        $image = ImageQuery::create()->findOneByArticleId($article->getId());
        $this->assertNotNull($image);
    }
}
