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
use Exception;
use Model\Article;
use Model\Event;
use Model\Image;
use Model\ImageQuery;
use Model\Map\ImageTableMap;
use Model\People;
use Model\Post;
use Model\Publisher;
use Model\Stock;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
    public function addImageFor(Article|Stock|Post|Publisher|People|Event $model, string $imagePath): void
    {
        match (get_class($model)) {
            Article::class => $this->_addImage($imagePath, type: "cover", directory: "articles", article: $model),
            Stock::class => $this->_addImage($imagePath, type: "photo", directory: "stockitems", stockItem: $model),
            Post::class => $this->_addImage($imagePath, type: "illustration", directory: "posts", post: $model),
            Publisher::class => $this->_addImage($imagePath, type: "logo", directory: "publishers", publisher: $model),
            People::class => $this->_addImage($imagePath, type: "portrait", directory: "contributors", contributor: $model),
            Event::class => $this->_addImage($imagePath, type: "illustration", directory: "events", event: $model),
        };
    }

    /**
     * @throws PropelException
     */
    private function _addImage(
        string    $imagePath,
        string    $type,
        string    $directory,
        Article   $article = null,
        Stock     $stockItem = null,
        Post      $post = null,
        Publisher $publisher = null,
        People    $contributor = null,
        Event     $event = null,
    ): void
    {
        $model = $article ?? $stockItem ?? $post ?? $publisher ?? $contributor ?? $event;

        $imageDimensions = getimagesize($imagePath);
        list($width, $height) = $imageDimensions;

        $image = $this->_getImageFor($model);
        if ($image->exists()) {
            $imageModel = $image->getModel();
            $imageModel->setVersion($imageModel->getVersion() + 1);
        } else {
            $imageModel = new Image();
            $imageModel->setVersion(1);
            $image->setModel($imageModel);
        }

        $mediaType = mime_content_type($imagePath);
        $fileExtension = match ($mediaType) {
            "image/jpeg" => "jpg",
            "image/png" => "png",
            "image/webp" => "webp",
            default => throw new BadRequestHttpException("Le format $mediaType n'est pas supporté. Essayez avec JPEG, PNG ou WebP."),
        };

        $imageModel->setSite($this->currentSite->getSite());
        $imageModel->setType($type);
        $imageModel->setArticle($article);
        $imageModel->setStockItem($stockItem);
        $imageModel->setPost($post);
        $imageModel->setPublisher($publisher);
        $imageModel->setContributor($contributor);
        $imageModel->setEvent($event);
        $imageModel->setFilepath("$directory/{$model->getId()}/");
        $imageModel->setFilename("$type.$fileExtension");
        $imageModel->setMediatype($mediaType);
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
    public function deleteImageFor(Article|Stock|Post|Publisher|People|Event $model): void
    {
        $db = Propel::getWriteConnection(ImageTableMap::DATABASE_NAME);
        $db->beginTransaction();

        try {
            $image = $this->_getImageFor($model);
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
    public function imageExistsFor(Article|Stock|Post|Publisher|People|Event $model): bool
    {
        return ImageQuery::create()->filterByModel($model)->exists();
    }

    /**
     * @throws PropelException
     */
    public function getImageUrlFor(
        Article|Stock|Post|Publisher|People|Event $model,
        int                                 $width = null,
        int                                 $height = null
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
    private function _getImageFor(Article|Stock|Post|Publisher|People|Event $model): ImageForModel
    {
        $image = ImageQuery::create()->filterByModel($model)->findOne();
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