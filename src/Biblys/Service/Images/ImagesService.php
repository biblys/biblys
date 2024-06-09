<?php

namespace Biblys\Service\Images;

use Biblys\Service\Config;
use Model\Article;
use Model\Image;
use Model\ImageQuery;
use Propel\Runtime\Exception\PropelException;

class ImagesService
{
    private Config $_config;

    public function __construct(Config $config)
    {
        $this->_config = $config;
    }

    /**
     * @throws PropelException
     */
    public function addArticleCoverImage(Article $article, string $imagePath): void
    {
        $imageDirectory = str_pad(
            string: substr(string: $article->getId(), offset: -2, length: 2),
            length: 2,
            pad_string: '0',
            pad_type: STR_PAD_LEFT
        );

        $imageDimensions = getimagesize($imagePath);
        list($width, $height) = $imageDimensions;

        $image = new Image();
        $image->setType("cover");
        $image->setArticleId($article->getId());
        $image->setFilepath("/book/$imageDirectory/");
        $image->setFilename("{$article->getId()}.jpg");
        $image->setVersion(1);
        $image->setMediatype(mime_content_type($imagePath));
        $image->setFilesize(filesize($imagePath));
        $image->setWidth($width);
        $image->setHeight($height);
        $image->save();
    }

    public function articleHasCoverImage(Article $article): bool
    {
        return ImageQuery::create()->filterByArticleId($article->getId())->exists();
    }

    public function getCoverImageForArticle(Article $article): ArticleCoverImage
    {
        $image = ImageQuery::create()->findOneByArticleId($article->getId());
        $basePathFromRoot = $this->_config->get("media_path") ?: "public/images";
        $basePath =  __DIR__."/../../../../$basePathFromRoot";
        $baseUrl = $this->_config->get("media_url") ?: "/images/";

        return new ArticleCoverImage($image, $basePath, $baseUrl);
    }
}