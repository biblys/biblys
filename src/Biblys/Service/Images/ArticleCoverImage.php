<?php

namespace Biblys\Service\Images;

use InvalidArgumentException;
use Model\Image;

class ArticleCoverImage implements ImageInterface
{
    private Image $_image;
    private string $_basePath;
    private string $_baseUrl;

    public function __construct(
        Image $image,
        string $basePath,
        string $baseUrl,
    )
    {
        if ($image->getType() !== "cover") {
            throw new InvalidArgumentException('Image must be of type "cover"');
        }

        $this->_image = $image;
        $this->_basePath = $basePath;
        $this->_baseUrl = $baseUrl;
    }

    public function getFilePath(): string
    {
        return "$this->_basePath/{$this->_image->getFilepath()}/{$this->_image->getFilename()}";
    }

    public function getUrl(): string
    {
        return "$this->_baseUrl/{$this->_image->getFilepath()}/{$this->_image->getFilename()}";
    }
}