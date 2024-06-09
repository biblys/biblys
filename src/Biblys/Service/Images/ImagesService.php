<?php

namespace Biblys\Service\Images;

use Biblys\Service\Config;
use Exception;
use Model\Article;
use Model\Image;
use Model\ImageQuery;
use Model\Map\ImageTableMap;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use Symfony\Component\Filesystem\Filesystem;

class ImagesService
{
    private string $basePath;
    private string|array|bool $baseUrl;

    public function __construct(
        private readonly Config $config,
        private readonly Filesystem $filesystem,
    )
    {
        $basePathFromRoot = $this->config->get("media_path") ?: "public/images";
        $this->basePath = __DIR__ . "/../../../../$basePathFromRoot";
        $this->baseUrl = $this->config->get("media_url") ?: "/images/";
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

        $image = ImageQuery::create()->findOneByArticleId($article->getId());
        if ($image) {
            $image->setVersion($image->getVersion() + 1);
            $this->filesystem->remove($this->_buildArticleCoverImagePath($image));
        } else {
            $image = new Image();
            $image->setVersion(1);
        }

        $image->setType("cover");
        $image->setArticleId($article->getId());
        $image->setFilepath("/book/$imageDirectory/");
        $image->setFilename("{$article->getId()}.jpg");
        $image->setMediatype(mime_content_type($imagePath));
        $image->setFilesize(filesize($imagePath));
        $image->setWidth($width);
        $image->setHeight($height);

        $db = Propel::getWriteConnection(ImageTableMap::DATABASE_NAME);
        $db->beginTransaction();

        try {
            $image->save($db);
            $this->filesystem->copy($imagePath, $this->_buildArticleCoverImagePath($image));
            $db->commit();
        } catch (Exception $exception) {
            $db->rollBack();
            throw $exception;
        }
    }

    /**
     * @throws PropelException
     */
    public function articleHasCoverImage(Article $article): bool
    {
        return ImageQuery::create()->filterByArticle($article)->exists();
    }

    public function getCoverUrlForArticle(Article $article): ?string
    {
        $image = ImageQuery::create()->findOneByArticleId($article->getId());
        if (!$image) {
            return null;
        }

        $version = $image->getVersion() > 1 ? "?v={$image->getVersion()}" : "";
        return "$this->baseUrl/{$image->getFilepath()}/{$image->getFilename()}$version";
    }

    private function _buildArticleCoverImagePath(Image $image): ?string
    {
        return "$this->basePath/{$image->getFilepath()}/{$image->getFilename()}";
    }
}