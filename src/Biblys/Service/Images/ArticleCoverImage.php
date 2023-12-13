<?php

namespace Biblys\Service\Images;

use Model\Article;

class ArticleCoverImage implements ImageInterface
{
    private Article $_article;
    private string $_basePath;
    private string $_baseUrl;

    public function __construct(
        Article $article,
        string $basePath,
        string $baseUrl,
    )
    {
        $this->_article = $article;
        $this->_basePath = $basePath;
        $this->_baseUrl = $baseUrl;
    }

    public function getFilePath(): string
    {
        $articleId = $this->_article->getId();
        $imageDirectory = str_pad(
            string: substr(string: $articleId, offset: -2, length: 2),
            length: 2,
            pad_string: '0',
            pad_type: STR_PAD_LEFT
        );
        return "$this->_basePath/book/$imageDirectory/$articleId.jpg";
    }

    public function getUrl(): string
    {
        $articleId = $this->_article->getId();
        $imageDirectory = str_pad(
            string: substr(string: $articleId, offset: -2, length: 2),
            length: 2,
            pad_string: '0',
            pad_type: STR_PAD_LEFT
        );
        return "{$this->_baseUrl}book/$imageDirectory/$articleId.jpg";
    }
}