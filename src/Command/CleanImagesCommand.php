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


namespace Command;

use Biblys\Service\CurrentSite;
use Biblys\Service\Images\ImagesService;
use Biblys\Service\LoggerService;
use Exception;
use Model\Article;
use Model\Event;
use Model\ImageQuery;
use Model\People;
use Model\Post;
use Model\Publisher;
use Model\Stock;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class  CleanImagesCommand extends Command
{
    protected static $defaultName = "images:clean";
    private OutputInterface $output;
    private int $deletedImages;
    private int $keptImages;

    public function __construct(
        private readonly CurrentSite   $currentSite,
        private readonly ImagesService $imagesService,
        string                         $name = null,
    )
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setDescription("Delete orphan images");
    }

    /**
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        Propel::disableInstancePooling();

        $this->output = $output;
        $this->deletedImages = 0;
        $this->keptImages = 0;
        $result = Command::SUCCESS;

        try {
            $this->_processImages(modelType: "publisher", imageType: "logo", isSiteAgnostic: true);
            $this->_processImages(modelType: "contributor", imageType: "portrait", isSiteAgnostic: true);
            $this->_processImages(modelType: "article", imageType: "cover", isSiteAgnostic: true);

            $this->_processImages(modelType: "event", imageType: "illustration", isSiteAgnostic: false);
            $this->_processImages(modelType: "post", imageType: "illustration", isSiteAgnostic: false);
            $this->_processImages(modelType: "stock", imageType: "photo", isSiteAgnostic: false);
        } catch (Exception $exception) {
            $this->output->writeln("\r\nAn error occurred: " . $exception->getMessage());
            $result = Command::FAILURE;
        } finally {
            $logger = new LoggerService();
            $logMessage = "Completed ! Deleted $this->deletedImages images, kept $this->keptImages images.";
            $this->output->writeln("\r\n". $logMessage);
            $logger->log("images-clean", "INFO", $logMessage);
            return $result;
        }
    }
    /**
     * @throws PropelException
     * @throws Exception
     */
    private function _processImages(
        string $modelType,
        string $imageType,
        bool   $isSiteAgnostic,
    ): void
    {
        $imageQuery = ImageQuery::create();
        $logger = new LoggerService();

        $getterMethodName = "get" . ucfirst($modelType);
        $imagesToCheck = $imageQuery->filterByType($imageType);
        $numberOfImagesToCheck = $imagesToCheck->count();
        $this->output->writeln("\r\nFound $numberOfImagesToCheck $modelType {$imageType}s to check.");

        if ($numberOfImagesToCheck === 0) {
            return;
        }

        $progressBar = new ProgressBar($this->output, $numberOfImagesToCheck);
        $progressBar->setFormat("%current%/%max% [%bar%] %percent:3s%% (%remaining:6s%) %message%");
        $progressBar->start();

        $imagesDeleted = 0;
        $imagesKept = 0;

        foreach ($imagesToCheck->select(["id"])->find() as $imageId) {
            $image = ImageQuery::create()->findPk($imageId);

            $shouldDeleteImage = $isSiteAgnostic ?
                $image->$getterMethodName() === null :
                $image->getSiteId() != $this->currentSite->getSite()->getId();
            [$modelType, $modelId] = $this->_getModelTypeAndId($image);
            if ($shouldDeleteImage) {
                $this->imagesService->deleteImageByModelId($modelType, $modelId);
                $logMessage = "Deleted $imageType #{$image->getId()} for $modelType #$modelId";
                $imagesDeleted++;
            } else {
                $logMessage = "Kept $imageType #{$image->getId()} for $modelType #$modelId";
                $imagesKept++;
            }

            $logger->log("images-clean", "INFO", $logMessage);
            $progressBar->setMessage($logMessage);
            $progressBar->advance();
        }

        $logMessage = "\r\n$modelType : $imagesDeleted images deleted, $imagesKept images kept.";
        $logger->log("images-clean", "INFO", $logMessage);
        $this->output->writeln($logMessage);

        $this->deletedImages += $imagesDeleted;
        $this->keptImages += $imagesKept;
    }

    /**
     * @throws Exception
     */
    private function _getModelTypeAndId($image): array
    {
        return match (true) {
            $image->getArticleId() !== null => [Article::class, $image->getArticleId()],
            $image->getStockItemId() !== null => [Stock::class, $image->getStockItemId()],
            $image->getPostId() !== null => [Post::class, $image->getPostId()],
            $image->getPublisherId() !== null => [Publisher::class, $image->getPublisherId()],
            $image->getContributorId() !== null => [People::class, $image->getContributorId()],
            $image->getEventId() !== null => [Event::class, $image->getEventId()],
            default => throw new Exception("Unknown model type"),
        };
    }
}