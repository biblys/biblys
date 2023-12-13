<?php

namespace Biblys\Service\Images;

use Model\Article;
use PHPUnit\Framework\TestCase;

class ArticleCoverImageTest extends TestCase
{
    /**
     * ArticleCoverImage->getPath()
     */

    public function testGetPath()
    {
        // given
        $article = new Article();
        $article->setId(81535);
        $basePath = "/users/biblys/public/media";
        $baseUrl = "https://www.biblys.fr/images/";
        $image = new ArticleCoverImage($article, $basePath, $baseUrl);

        // when
        $url = $image->getFilePath();

        // then
        $this->assertEquals(
            "/users/biblys/public/media/book/35/81535.jpg",
            $url,
            "returns correct path"
        );
    }

    /**
     * ArticleCoverImage->getUrl
     */

    public function testGetUrl()
    {
        // given
        $article = new Article();
        $article->setId(81535);
        $basePath = "/users/biblys/public/media";
        $baseUrl = "https://www.biblys.fr/images/";
        $image = new ArticleCoverImage($article, $basePath, $baseUrl);

        // when
        $url = $image->getUrl();

        // then
        $this->assertEquals(
            "https://www.biblys.fr/images/book/35/81535.jpg",
            $url,
            "returns correct url"
        );
    }
}
