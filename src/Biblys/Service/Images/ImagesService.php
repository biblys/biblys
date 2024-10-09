<?php

namespace Biblys\Service\Images;

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Exception;
use Model\Article;
use Model\Image;
use Model\ImageQuery;
use Model\Map\ImageTableMap;
use Model\Post;
use Model\Stock;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use Symfony\Component\Filesystem\Filesystem;

class ImagesService
{
    public function __construct(
        private readonly Config      $config,
        private readonly CurrentSite $currentSite,
        private readonly Filesystem  $filesystem,
    )
    {
    }

    /**
     * @throws PropelException
     */
    public function addImageFor(Article|Stock|Post $model, string $imagePath): void
    {
        match (get_class($model)) {
            Article::class => $this->_addImage($imagePath, type: "cover", typeDirectory: "book", article: $model),
            Stock::class => $this->_addImage($imagePath, type: "photo", typeDirectory: "stock", stockItem: $model),
            Post::class => $this->_addImage($imagePath, type: "illustration", typeDirectory: "post", post: $model),
        };
    }

    /**
     * @throws PropelException
     */
    private function _addImage(
        string  $imagePath,
        string  $type,
        string  $typeDirectory,
        Article $article = null,
        Stock   $stockItem = null,
        Post    $post = null,
    ): void
    {
        $model = $article ?? $stockItem ?? $post;

        $imageDirectory = str_pad(
            string: substr(string: $model->getId(), offset: -2, length: 2),
            length: 2,
            pad_string: '0',
            pad_type: STR_PAD_LEFT
        );

        $imageDimensions = getimagesize($imagePath);
        list($width, $height) = $imageDimensions;

        $image = $this->_getImageFor($model);
        if ($image->exists()) {
            $imageModel = $image->getModel();
            $imageModel->setVersion($imageModel->getVersion() + 1);
            $this->filesystem->remove($image->getFilePath());
        } else {
            $imageModel = new Image();
            $imageModel->setVersion(1);
            $image->setModel($imageModel);
        }

        $imageModel->setSite($this->currentSite->getSite());
        $imageModel->setType($type);
        $imageModel->setArticleId($article?->getId());
        $imageModel->setStockItemId($stockItem?->getId());
        $imageModel->setPostId($post?->getId());
        $imageModel->setFilepath("$typeDirectory/$imageDirectory/");
        $imageModel->setFilename("{$model->getId()}.jpg");
        $imageModel->setMediatype(mime_content_type($imagePath));
        $imageModel->setFilesize(filesize($imagePath));
        $imageModel->setWidth($width);
        $imageModel->setHeight($height);

        $db = Propel::getWriteConnection(ImageTableMap::DATABASE_NAME);
        $db->beginTransaction();

        try {
            $imageModel->save($db);
            $this->filesystem->copy($imagePath, $image->getFilePath());
            $db->commit();
        } catch (Exception $exception) {
            $db->rollBack();
            throw $exception;
        }
    }

    /**
     * @throws PropelException
     */
    public function deleteImageFor(Article|Stock|Post $article): void
    {
        $db = Propel::getWriteConnection(ImageTableMap::DATABASE_NAME);
        $db->beginTransaction();

        try {
            $image = $this->_getImageFor($article);
            $imageModel = $image->getModel();
            $imageModel->delete($db);
            $this->filesystem->remove($image->getFilePath());
            $db->commit();
        } catch (Exception $exception) {
            $db->rollBack();
            throw $exception;
        }
    }

    /**
     * @throws PropelException
     */
    public function imageExistsFor(Article|Stock|Post $model): bool
    {
        return match (get_class($model)) {
            Article::class => ImageQuery::create()->filterByArticle($model)->exists(),
            Stock::class => ImageQuery::create()->filterByStockItem($model)->exists(),
            Post::class => ImageQuery::create()->filterByPost($model)->exists(),
        };
    }

    /**
     * @throws PropelException
     */
    public function getImageUrlFor(
        Article|Stock|Post $model,
        int                $width = null,
        int                $height = null
    ):
    ?string
    {
        $image = $this->_getImageFor($model);
        if (!$image->exists()) {
            return null;
        }

        return $image->getUrl($width, $height);
    }

    /**
     * @throws PropelException
     */
    public function getImagePathFor(Article $article): ?string
    {
        $image = $this->_getImageFor($article);
        if (!$image->exists()) {
            return null;
        }

        return $image->getFilePath();
    }

    /**
     * @throws PropelException
     */
    private function _getImageFor(Article|Stock|Post $model): ImageForModel
    {
        if ($model instanceof Stock) {
            $image = ImageQuery::create()->filterByStockItem($model)->findOne();
            return new ImageForModel($this->config, $image);
        }

        if ($model instanceof Post) {
            $image = ImageQuery::create()->filterByPost($model)->findOne();
            return new ImageForModel($this->config, $image);
        }

        $image = ImageQuery::create()->filterByArticle($model)->findOne();
        return new ImageForModel($this->config, $image);
    }

    /** Deprecated methods */

    /**
     * @throws PropelException
     * @deprecated ImagesService->getCoverUrlForArticle is deprecated. Use method getImageUrlFor instead.
     * @noinspection PhpUnused
     */
    public function getCoverUrlForArticle(Article $article, int $width = null, int $height = null): void
    {
        trigger_deprecation(
            "biblys/biblys",
            "2.84.0",
            "ImagesService->getCoverUrlForArticle is deprecated. Use method getImageUrlFor instead.",
        );

        $this->getImageUrlFor($article, $width, $height);
    }
}