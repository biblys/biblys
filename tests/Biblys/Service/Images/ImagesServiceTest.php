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
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

require_once __DIR__ . "/../../../setUp.php";

class ImagesServiceTest extends TestCase
{
    /**j
     * @throws PropelException
     */
    protected function setUp(): void
    {
        ImageQuery::create()->deleteAll();
    }

    /**
     * Generic
     */

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testAddImageForWithUnsupportedFormat(): void
    {
        // given
        $article = ModelFactory::createArticle();
        $site = ModelFactory::createSite();

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->expects("copy");
        $service = new ImagesService($config, $currentSite, $filesystem);

        // when
        $exception = Helpers::runAndCatchException(
            fn () => $service->addImageFor($article, __DIR__ . "/image.gif")
        );

        // then
        $this->assertInstanceOf(BadRequestHttpException::class, $exception);
        $this->assertEquals(
            "Le format image/gif n'est pas supporté. Essayez avec JPEG, PNG ou WebP.",
            $exception->getMessage()
        );
        $image = ImageQuery::create()->findOneByArticleId($article->getId());
        $this->assertNull($image);
    }

    /**
     * @throws PropelException
     */
    public function testDeleteImageByModelId(): void
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
        $service->deleteImageByModelId(Article::class, $article->getId());

        // then
        $deletedImage = ImageQuery::create()->findOneByArticleId($article->getId());
        $this->assertNull($deletedImage);
        $filesystem->shouldHaveReceived("remove");
    }

    /** Article **/

    /** ImagesService->addImageFor (article) */

    /**
     * @throws PropelException
     */
    public function testAddImageForCreatesImageWithArticle(): void
    {
        // given
        $article = ModelFactory::createArticle();
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
        $this->assertEquals("articles/{$article->getId()}/", $image->getFilepath());
        $this->assertEquals("cover.jpg", $image->getFilename());
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
        $this->assertEquals("articles/{$article->getId()}/", $updatedImage->getFilepath());
        $this->assertEquals("cover.jpg", $updatedImage->getFilename());
        $this->assertEquals(2, $updatedImage->getVersion());
        $this->assertEquals("image/jpeg", $updatedImage->getMediatype());
        $this->assertEquals(4410, $updatedImage->getFilesize());
        $this->assertEquals(100, $updatedImage->getWidth());
        $this->assertEquals(150, $updatedImage->getHeight());
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

    /** ImagesService->getImageUrlFor (article) */

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetImagePathForIfItDoesNotExistWithArticle(): void
    {
        // given
        $site = ModelFactory::createSite();
        $article = ModelFactory::createArticle();

        $config = new Config(["images" => ["path" => "/images/"]]);
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite, $filesystem);

        // when
        $imagePath = $service->getImagePathFor($article);

        // then
        $this->assertNull($imagePath);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetImagePathForIfItExistsWithArticle(): void
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
        $imagePath = $service->getImagePathFor($article);

        // then
        $this->assertStringEndsWith("/images/book/covers/book-cover.jpeg", $imagePath);
    }

    /** ImagesService->deleteImageFor (article) */

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

    /** StockItem **/

    /** ImagesService->addImageFor (stockItem) */

    /**
     * @throws PropelException
     */
    public function testAddImageForCreatesImageWithStockItem(): void
    {
        // given
        $stockItem = ModelFactory::createStockItem();
        $site = ModelFactory::createSite();

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->expects('copy');
        $service = new ImagesService($config, $currentSite, $filesystem);

        // when
        $service->addImageFor($stockItem, __DIR__ . "/image.png");

        // then
        $image = ImageQuery::create()->filterByStockItem($stockItem)->findOne();
        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals($site, $image->getSite());
        $this->assertEquals("photo", $image->getType());
        $this->assertEquals("stockitems/{$stockItem->getId()}/", $image->getFilepath());
        $this->assertEquals("photo.png", $image->getFilename());
        $this->assertEquals(1, $image->getVersion());
        $this->assertEquals("image/png", $image->getMediatype());
        $this->assertEquals(114907, $image->getFilesize());
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
        $this->assertEquals("stockitems/{$stockItem->getId()}/", $updatedImage->getFilepath());
        $this->assertEquals("photo.jpg", $updatedImage->getFilename());
        $this->assertEquals(2, $updatedImage->getVersion());
        $this->assertEquals("image/jpeg", $updatedImage->getMediatype());
        $this->assertEquals(4410, $updatedImage->getFilesize());
        $this->assertEquals(100, $updatedImage->getWidth());
        $this->assertEquals(150, $updatedImage->getHeight());
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

    /** ImagesService->deleteImageFor (stockItem) */

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

    /** Post **/

    /** ImagesService->addImageFor (post) */

    /**
     * @throws PropelException
     */
    public function testAddImageForCreatesImageWithPost(): void
    {
        // given
        $post = ModelFactory::createPost();
        $site = ModelFactory::createSite();

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->expects('copy');
        $service = new ImagesService($config, $currentSite, $filesystem);

        // when
        $service->addImageFor($post, __DIR__ . "/image.webp");

        // then
        $image = ImageQuery::create()->filterByPost($post)->findOne();
        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals($site, $image->getSite());
        $this->assertEquals("illustration", $image->getType());
        $this->assertEquals("posts/{$post->getId()}/", $image->getFilepath());
        $this->assertEquals("illustration.webp", $image->getFilename());
        $this->assertEquals(1, $image->getVersion());
        $this->assertEquals("image/webp", $image->getMediatype());
        $this->assertEquals(12556, $image->getFilesize());
        $this->assertEquals(200, $image->getWidth());
        $this->assertEquals(300, $image->getHeight());
        $filesystem->shouldHaveReceived("copy");
    }

    /**
     * @throws PropelException
     */
    public function testAddImageForCreatesUpdatesImageWithPost(): void
    {
        // given
        $site = ModelFactory::createSite();
        $post = ModelFactory::createPost();

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->expects("copy");
        $filesystem->expects("remove");
        $service = new ImagesService($config, $currentSite, $filesystem);

        $service->addImageFor($post, __DIR__ . "/image.jpeg");
        $createdImage = ImageQuery::create()->filterByPost($post)->findOne();

        // when
        $service->addImageFor($post, __DIR__ . "/image2.jpeg");

        // then
        $updatedImage = ImageQuery::create()->filterByPost($post)->findOne();
        $this->assertInstanceOf(Image::class, $updatedImage);
        $this->assertEquals($createdImage->getId(), $updatedImage->getId());
        $this->assertEquals("posts/{$post->getId()}/", $updatedImage->getFilepath());
        $this->assertEquals("illustration.jpg", $updatedImage->getFilename());
        $this->assertEquals(2, $updatedImage->getVersion());
        $this->assertEquals("image/jpeg", $updatedImage->getMediatype());
        $this->assertEquals(4410, $updatedImage->getFilesize());
        $this->assertEquals(100, $updatedImage->getWidth());
        $this->assertEquals(150, $updatedImage->getHeight());
        $filesystem->shouldHaveReceived("copy");
    }

    /**
     * @throws Exception
     */
    public function testAddImageForIfFileCopyFailsWithPost(): void
    {
        // given
        $site = ModelFactory::createSite();
        $post = ModelFactory::createPost();

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->expects("copy")->andThrow(new FileNotFoundException());
        $service = new ImagesService($config, $currentSite, $filesystem);

        // when
        $exception = Helpers::runAndCatchException(function () use ($service, $post) {
            $service->addImageFor($post, __DIR__ . "/image.jpeg");
        });

        // then
        $this->assertInstanceOf(FileNotFoundException::class, $exception);
        $image = ImageQuery::create()->filterByPost($post)->findOne();
        $this->assertNull($image);
    }

    /** ImagesService->imageExistsFor (post) */

    /**
     * @throws PropelException
     */
    public function testImageExistsForReturnsTrueWithPost()
    {
        // given
        $site = ModelFactory::createSite();
        $post = ModelFactory::createPost();
        ModelFactory::createImage(post: $post);

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite, $filesystem);

        // when
        $hasCover = $service->imageExistsFor($post);

        // then
        $this->assertTrue($hasCover);
    }

    /**
     * @throws PropelException
     */
    public function testImageExistsForReturnsFalseWithPost()
    {
        // given
        $site = ModelFactory::createSite();
        $post = ModelFactory::createPost();

        $currentSite = new CurrentSite($site);
        $config = new Config();
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite ,$filesystem);

        // when
        $hasCover = $service->imageExistsFor($post);

        // then
        $this->assertFalse($hasCover);
    }

    /** ImagesService->getImageUrlFor (post) */

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetImageUrlForIfItDoesNotExistWithPost(): void
    {
        // given
        $site = ModelFactory::createSite();
        $post = ModelFactory::createPost();

        $config = new Config(["images" => ["path" => "/images/"]]);
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite, $filesystem);


        // when
        $coverUrl = $service->getImageUrlFor($post);

        // then
        $this->assertNull($coverUrl);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetImageUrlForIfItExistsWithPost(): void
    {
        // given
        $site = ModelFactory::createSite();

        $config = new Config(["images" => ["base_url" => "/images/"]]);
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite, $filesystem);

        $post = ModelFactory::createPost();
        ModelFactory::createImage(
            post: $post,
            filePath: "book/covers/",
            fileName: "book-cover.jpeg",
        );

        // when
        $coverUrl = $service->getImageUrlFor($post);

        // then
        $this->assertEquals("/images/book/covers/book-cover.jpeg", $coverUrl);
    }

    /** ImagesService->deleteImageFor (post) */

    /**
     * @throws PropelException
     */
    public function testDeleteImageForDeletesImageWithPost(): void
    {
        // given
        $post = ModelFactory::createPost();
        ModelFactory::createImage(post: $post);
        $site = ModelFactory::createSite();

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->expects("remove");
        $service = new ImagesService($config, $currentSite, $filesystem);

        // when
        $service->deleteImageFor($post);

        // then
        $deletedImage = ImageQuery::create()->filterByPost($post)->findOne();
        $this->assertNull($deletedImage);
        $filesystem->shouldHaveReceived("remove");
    }

    /** Event **/

    /** ImagesService->addImageFor (event) */

    /**
     * @throws PropelException
     */
    public function testAddImageForCreatesImageWithEvent(): void
    {
        // given
        $site = ModelFactory::createSite();
        $event = ModelFactory::createEvent(site: $site);

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->expects('copy');
        $service = new ImagesService($config, $currentSite, $filesystem);

        // when
        $service->addImageFor($event, __DIR__ . "/image.webp");

        // then
        $image = ImageQuery::create()->filterByEvent($event)->findOne();
        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals($site, $image->getSite());
        $this->assertEquals("illustration", $image->getType());
        $this->assertEquals("events/{$event->getId()}/", $image->getFilepath());
        $this->assertEquals("illustration.webp", $image->getFilename());
        $this->assertEquals(1, $image->getVersion());
        $this->assertEquals("image/webp", $image->getMediatype());
        $this->assertEquals(12556, $image->getFilesize());
        $this->assertEquals(200, $image->getWidth());
        $this->assertEquals(300, $image->getHeight());
        $filesystem->shouldHaveReceived("copy");
    }

    /**
     * @throws PropelException
     */
    public function testAddImageForCreatesUpdatesImageWithEvent(): void
    {
        // given
        $site = ModelFactory::createSite();
        $event = ModelFactory::createEvent(site: $site);

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->expects("copy");
        $filesystem->expects("remove");
        $service = new ImagesService($config, $currentSite, $filesystem);

        $service->addImageFor($event, __DIR__ . "/image.jpeg");
        $createdImage = ImageQuery::create()->filterByEvent($event)->findOne();

        // when
        $service->addImageFor($event, __DIR__ . "/image2.jpeg");

        // then
        $updatedImage = ImageQuery::create()->filterByEvent($event)->findOne();
        $this->assertInstanceOf(Image::class, $updatedImage);
        $this->assertEquals($createdImage->getId(), $updatedImage->getId());
        $this->assertEquals("events/{$event->getId()}/", $updatedImage->getFilepath());
        $this->assertEquals("illustration.jpg", $updatedImage->getFilename());
        $this->assertEquals(2, $updatedImage->getVersion());
        $this->assertEquals("image/jpeg", $updatedImage->getMediatype());
        $this->assertEquals(4410, $updatedImage->getFilesize());
        $this->assertEquals(100, $updatedImage->getWidth());
        $this->assertEquals(150, $updatedImage->getHeight());
        $filesystem->shouldHaveReceived("copy");
    }

    /**
     * @throws Exception
     */
    public function testAddImageForIfFileCopyFailsWithEvent(): void
    {
        // given
        $site = ModelFactory::createSite();
        $event = ModelFactory::createEvent(site: $site);

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->expects("copy")->andThrow(new FileNotFoundException());
        $service = new ImagesService($config, $currentSite, $filesystem);

        // when
        $exception = Helpers::runAndCatchException(function () use ($service, $event) {
            $service->addImageFor($event, __DIR__ . "/image.jpeg");
        });

        // then
        $this->assertInstanceOf(FileNotFoundException::class, $exception);
        $image = ImageQuery::create()->filterByEvent($event)->findOne();
        $this->assertNull($image);
    }

    /** ImagesService->imageExistsFor (event) */

    /**
     * @throws PropelException
     */
    public function testImageExistsForReturnsTrueWithEvent()
    {
        // given
        $site = ModelFactory::createSite();
        $event = ModelFactory::createEvent(site: $site);
        ModelFactory::createImage(event: $event);

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite, $filesystem);

        // when
        $hasCover = $service->imageExistsFor($event);

        // then
        $this->assertTrue($hasCover);
    }

    /**
     * @throws PropelException
     */
    public function testImageExistsForReturnsFalseWithEvent()
    {
        // given
        $site = ModelFactory::createSite();
        $event = ModelFactory::createEvent(site: $site);

        $currentSite = new CurrentSite($site);
        $config = new Config();
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite ,$filesystem);

        // when
        $hasCover = $service->imageExistsFor($event);

        // then
        $this->assertFalse($hasCover);
    }

    /** ImagesService->getImageUrlFor (event) */

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetImageUrlForIfItDoesNotExistWithEvent(): void
    {
        // given
        $site = ModelFactory::createSite();
        $event = ModelFactory::createEvent(site: $site);

        $config = new Config(["images" => ["path" => "/images/"]]);
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite, $filesystem);


        // when
        $coverUrl = $service->getImageUrlFor($event);

        // then
        $this->assertNull($coverUrl);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetImageUrlForIfItExistsWithEvent(): void
    {
        // given
        $site = ModelFactory::createSite();

        $config = new Config(["images" => ["base_url" => "/images/"]]);
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite, $filesystem);

        $event = ModelFactory::createEvent(site: $site);
        ModelFactory::createImage(
            event: $event,
            filePath: "book/covers/",
            fileName: "book-cover.jpeg",
        );

        // when
        $coverUrl = $service->getImageUrlFor($event);

        // then
        $this->assertEquals("/images/book/covers/book-cover.jpeg", $coverUrl);
    }

    /** ImagesService->deleteImageFor (event) */

    /**
     * @throws PropelException
     */
    public function testDeleteImageForDeletesImageWithEvent(): void
    {
        // given
        $site = ModelFactory::createSite();
        $event = ModelFactory::createEvent(site: $site);
        ModelFactory::createImage(event: $event);
        $site = ModelFactory::createSite();

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->expects("remove");
        $service = new ImagesService($config, $currentSite, $filesystem);

        // when
        $service->deleteImageFor($event);

        // then
        $deletedImage = ImageQuery::create()->filterByEvent($event)->findOne();
        $this->assertNull($deletedImage);
        $filesystem->shouldHaveReceived("remove");
    }

    /** Publisher **/

    /** ImagesService->addImageFor (publisher) */

    /**
     * @throws PropelException
     */
    public function testAddImageForCreatesImageWithPublisher(): void
    {
        // given
        $publisher = ModelFactory::createPublisher();
        $site = ModelFactory::createSite();

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->expects('copy');
        $service = new ImagesService($config, $currentSite, $filesystem);

        // when
        $service->addImageFor($publisher, __DIR__ . "/image.jpeg");

        // then
        $image = ImageQuery::create()->findOneByPublisherId($publisher->getId());
        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals($site, $image->getSite());
        $this->assertEquals("logo", $image->getType());
        $this->assertEquals("publishers/{$publisher->getId()}/", $image->getFilepath());
        $this->assertEquals("logo.jpg", $image->getFilename());
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
    public function testAddImageForCreatesUpdatesImageWithPublisher(): void
    {
        // given
        $site = ModelFactory::createSite();
        $publisher = ModelFactory::createPublisher();

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->expects("copy");
        $filesystem->expects("remove");
        $service = new ImagesService($config, $currentSite, $filesystem);

        $service->addImageFor($publisher, __DIR__ . "/image.jpeg");
        $createdImage = ImageQuery::create()->findOneByPublisherId($publisher->getId());

        // when
        $service->addImageFor($publisher, __DIR__ . "/image2.jpeg");

        // then
        $updatedImage = ImageQuery::create()->findOneByPublisherId($publisher->getId());
        $this->assertInstanceOf(Image::class, $updatedImage);
        $this->assertEquals($createdImage->getId(), $updatedImage->getId());
        $this->assertEquals("publishers/{$publisher->getId()}/", $updatedImage->getFilepath());
        $this->assertEquals("logo.jpg", $updatedImage->getFilename());
        $this->assertEquals(2, $updatedImage->getVersion());
        $this->assertEquals("image/jpeg", $updatedImage->getMediatype());
        $this->assertEquals(4410, $updatedImage->getFilesize());
        $this->assertEquals(100, $updatedImage->getWidth());
        $this->assertEquals(150, $updatedImage->getHeight());
        $filesystem->shouldHaveReceived("copy");
    }

    /**
     * @throws Exception
     */
    public function testAddImageForIfFileCopyFailsWithPublisher(): void
    {
        // given
        $site = ModelFactory::createSite();
        $publisher = ModelFactory::createPublisher();

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->expects("copy")->andThrow(new FileNotFoundException());
        $service = new ImagesService($config, $currentSite, $filesystem);

        // when
        $exception = Helpers::runAndCatchException(function () use ($service, $publisher) {
            $service->addImageFor($publisher, __DIR__ . "/image.jpeg");
        });

        // then
        $this->assertInstanceOf(FileNotFoundException::class, $exception);
        $image = ImageQuery::create()->findOneByPublisherId($publisher->getId());
        $this->assertNull($image);
    }

    /** ImagesService->imageExistsFor (publisher) */

    /**
     * @throws PropelException
     */
    public function testImageExistsForReturnsTrueWithPublisher()
    {
        // given
        $site = ModelFactory::createSite();
        $publisher = ModelFactory::createPublisher();
        ModelFactory::createImage(publisher: $publisher);

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite, $filesystem);

        // when
        $hasImage = $service->imageExistsFor($publisher);

        // then
        $this->assertTrue($hasImage);
    }

    /**
     * @throws PropelException
     */
    public function testImageExistsForReturnsFalseWithPublisher()
    {
        // given
        $site = ModelFactory::createSite();
        $publisher = ModelFactory::createPublisher();

        $currentSite = new CurrentSite($site);
        $config = new Config();
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite ,$filesystem);

        // when
        $hasImage = $service->imageExistsFor($publisher);

        // then
        $this->assertFalse($hasImage);
    }

    /** ImagesService->getImageUrlFor (publisher) */

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetImageUrlForIfItDoesNotExistWithPublisher(): void
    {
        // given
        $site = ModelFactory::createSite();
        $publisher = ModelFactory::createPublisher();

        $config = new Config(["images" => ["path" => "/images/"]]);
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite, $filesystem);


        // when
        $imageUrl = $service->getImageUrlFor($publisher);

        // then
        $this->assertNull($imageUrl);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetImageUrlForIfItExistsWithPublisher(): void
    {
        // given
        $site = ModelFactory::createSite();

        $config = new Config(["images" => ["base_url" => "/images/"]]);
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite, $filesystem);

        $publisher = ModelFactory::createPublisher();
        ModelFactory::createImage(
            publisher: $publisher,
            filePath: "publisher/logos/",
            fileName: "publisher-logo.jpeg",
        );

        // when
        $imageUrl = $service->getImageUrlFor($publisher);

        // then
        $this->assertEquals("/images/publisher/logos/publisher-logo.jpeg", $imageUrl);
    }

    /** ImagesService->deleteImageFor (publisher) */

    /**
     * @throws PropelException
     */
    public function testDeleteImageForDeletesImageWithPublisher(): void
    {
        // given
        $publisher = ModelFactory::createPublisher();
        ModelFactory::createImage(publisher: $publisher);
        $site = ModelFactory::createSite();

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->expects("remove");
        $service = new ImagesService($config, $currentSite, $filesystem);

        // when
        $service->deleteImageFor($publisher);

        // then
        $deletedImage = ImageQuery::create()->findOneByPublisherId($publisher->getId());
        $this->assertNull($deletedImage);
        $filesystem->shouldHaveReceived("remove");
    }

    /** Contributor **/

    /** ImagesService->addImageFor (contributor) */

    /**
     * @throws PropelException
     */
    public function testAddImageForCreatesImageWithContributor(): void
    {
        // given
        $contributor = ModelFactory::createContributor();
        $site = ModelFactory::createSite();

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->expects('copy');
        $service = new ImagesService($config, $currentSite, $filesystem);

        // when
        $service->addImageFor($contributor, __DIR__ . "/image.jpeg");

        // then
        $image = ImageQuery::create()->findOneByContributorId($contributor->getId());
        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals($site, $image->getSite());
        $this->assertEquals("portrait", $image->getType());
        $this->assertEquals("contributors/{$contributor->getId()}/", $image->getFilepath());
        $this->assertEquals("portrait.jpg", $image->getFilename());
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
    public function testAddImageForCreatesUpdatesImageWithContributor(): void
    {
        // given
        $site = ModelFactory::createSite();
        $contributor = ModelFactory::createContributor();

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->expects("copy");
        $filesystem->expects("remove");
        $service = new ImagesService($config, $currentSite, $filesystem);

        $service->addImageFor($contributor, __DIR__ . "/image.jpeg");
        $createdImage = ImageQuery::create()->findOneByContributorId($contributor->getId());

        // when
        $service->addImageFor($contributor, __DIR__ . "/image2.jpeg");

        // then
        $updatedImage = ImageQuery::create()->findOneByContributorId($contributor->getId());
        $this->assertInstanceOf(Image::class, $updatedImage);
        $this->assertEquals($createdImage->getId(), $updatedImage->getId());
        $this->assertEquals("contributors/{$contributor->getId()}/", $updatedImage->getFilepath());
        $this->assertEquals("portrait.jpg", $updatedImage->getFilename());
        $this->assertEquals(2, $updatedImage->getVersion());
        $this->assertEquals("image/jpeg", $updatedImage->getMediatype());
        $this->assertEquals(4410, $updatedImage->getFilesize());
        $this->assertEquals(100, $updatedImage->getWidth());
        $this->assertEquals(150, $updatedImage->getHeight());
        $filesystem->shouldHaveReceived("copy");
    }

    /**
     * @throws Exception
     */
    public function testAddImageForIfFileCopyFailsWithContributor(): void
    {
        // given
        $site = ModelFactory::createSite();
        $contributor = ModelFactory::createContributor();

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->expects("copy")->andThrow(new FileNotFoundException());
        $service = new ImagesService($config, $currentSite, $filesystem);

        // when
        $exception = Helpers::runAndCatchException(function () use ($service, $contributor) {
            $service->addImageFor($contributor, __DIR__ . "/image.jpeg");
        });

        // then
        $this->assertInstanceOf(FileNotFoundException::class, $exception);
        $image = ImageQuery::create()->findOneByContributorId($contributor->getId());
        $this->assertNull($image);
    }

    /** ImagesService->imageExistsFor (contributor) */

    /**
     * @throws PropelException
     */
    public function testImageExistsForReturnsTrueWithContributor()
    {
        // given
        $site = ModelFactory::createSite();
        $contributor = ModelFactory::createContributor();
        ModelFactory::createImage(contributor: $contributor);

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite, $filesystem);

        // when
        $hasCover = $service->imageExistsFor($contributor);

        // then
        $this->assertTrue($hasCover);
    }

    /**
     * @throws PropelException
     */
    public function testImageExistsForReturnsFalseWithContributor()
    {
        // given
        $site = ModelFactory::createSite();
        $contributor = ModelFactory::createContributor();

        $currentSite = new CurrentSite($site);
        $config = new Config();
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite ,$filesystem);

        // when
        $hasCover = $service->imageExistsFor($contributor);

        // then
        $this->assertFalse($hasCover);
    }

    /** ImagesService->getImageUrlFor (contributor) */

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetImageUrlForIfItDoesNotExistWithContributor(): void
    {
        // given
        $site = ModelFactory::createSite();
        $contributor = ModelFactory::createContributor();

        $config = new Config(["images" => ["path" => "/images/"]]);
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite, $filesystem);


        // when
        $coverUrl = $service->getImageUrlFor($contributor);

        // then
        $this->assertNull($coverUrl);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetImageUrlForIfItExistsWithContributor(): void
    {
        // given
        $site = ModelFactory::createSite();

        $config = new Config(["images" => ["base_url" => "/images/"]]);
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $service = new ImagesService($config, $currentSite, $filesystem);

        $contributor = ModelFactory::createContributor();
        ModelFactory::createImage(
            contributor: $contributor,
            filePath: "book/covers/",
            fileName: "book-cover.jpeg",
        );

        // when
        $coverUrl = $service->getImageUrlFor($contributor);

        // then
        $this->assertEquals("/images/book/covers/book-cover.jpeg", $coverUrl);
    }

    /** ImagesService->deleteImageFor (contributor) */

    /**
     * @throws PropelException
     */
    public function testDeleteImageForDeletesImageWithContributor(): void
    {
        // given
        $contributor = ModelFactory::createContributor();
        ModelFactory::createImage(contributor: $contributor);
        $site = ModelFactory::createSite();

        $config = new Config();
        $currentSite = new CurrentSite($site);
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->expects("remove");
        $service = new ImagesService($config, $currentSite, $filesystem);

        // when
        $service->deleteImageFor($contributor);

        // then
        $deletedImage = ImageQuery::create()->findOneByContributorId($contributor->getId());
        $this->assertNull($deletedImage);
        $filesystem->shouldHaveReceived("remove");
    }
}
