<?php

namespace Biblys\Service\Images;

use Biblys\Service\Config;
use Mockery;
use Model\Article;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class ImagesServiceTest extends TestCase
{
    /**
     * ImagesServices->articleHasCoverImage
     */

    public function testArticleHasCoverImageReturnsTrue()
    {
        // given
        $article = new Article();
        $article->setId(1984);
        $config = Mockery::mock(Config::class);
        $config->shouldReceive("get")->with("media_path")->andReturn(null);
        $config->shouldReceive("get")->with("media_url")->andReturn(null);
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem
            ->shouldReceive("exists")
            ->with(Mockery::pattern("/\.\.\/public\/images\/book\/84\/1984\.jpg$/"))
            ->andReturn(true);
        $service = new ImagesService($config, $filesystem);

        // when
        $hasCover = $service->articleHasCoverImage($article);

        // then
        $this->assertTrue($hasCover);
    }

    public function testArticleHasCoverImageReturnsFalse()
    {
        // given
        $article = new Article();
        $article->setId(404);
        $config = Mockery::mock(Config::class);
        $config->shouldReceive("get")->with("media_path")->andReturn(null);
        $config->shouldReceive("get")->with("media_url")->andReturn(null);
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem
            ->shouldReceive("exists")
            ->with(Mockery::pattern("/\.\.\/public\/images\/book\/04\/404\.jpg$/"))
            ->andReturn(false);
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
