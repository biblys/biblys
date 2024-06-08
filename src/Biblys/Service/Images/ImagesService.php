<?php

namespace Biblys\Service\Images;

use Biblys\Service\Config;
use Model\Article;
use Model\Image;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Filesystem\Filesystem;

class ImagesService
{
    private Config $_config;
    private Filesystem $_filesystem;

    public function __construct(Config $config, Filesystem $filesystem)
    {
        $this->_config = $config;
        $this->_filesystem = $filesystem;
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
        $coverImage = $this->getCoverImageForArticle($article);
        $coverImageFilePath = $coverImage->getFilePath();

        return $this->_filesystem->exists($coverImageFilePath);
    }

    public function getCoverImageForArticle(Article $article): ArticleCoverImage
    {
        $basePathFromRoot = $this->_config->get("media_path") ?: "public/images";
        $basePath =  __DIR__."/../../../../$basePathFromRoot";
        $baseUrl = $this->_config->get("media_url") ?: "/images/";
        return new ArticleCoverImage($article, $basePath, $baseUrl);
    }
}