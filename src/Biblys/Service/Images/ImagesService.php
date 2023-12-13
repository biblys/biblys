<?php

namespace Biblys\Service\Images;

use Biblys\Service\Config;
use Model\Article;
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