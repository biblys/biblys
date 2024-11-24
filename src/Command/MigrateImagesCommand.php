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

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\Images\ImagesService;
use Biblys\Service\LoggerService;
use Exception;
use Model\Image;
use Model\ImageQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class MigrateImagesCommand extends Command
{
    protected static $defaultName = "images:migrate";

    public function __construct(
        private readonly Config        $config,
        private readonly CurrentSite   $currentSite,
        private readonly ImagesService $imagesService,
        string                         $name = null,
    )
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setDescription("Migrate images found in the images directory");

        $this->addOption(
            name: "offset",
            shortcut: "o",
            mode: InputArgument::OPTIONAL,
            description: "Number of images to skip",
            default: 0
        );
    }

    /**
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $contributorPortraitsQuery = ImageQuery::create()->filterByType("portrait");
        $publisherLogosQuery = ImageQuery::create()->filterByType("logo");
        $stockItemPhotosQuery = ImageQuery::create()->filterByType("photo")->filterBySite($this->currentSite->getSite());
        $postIllustrationsQuery = ImageQuery::create()
            ->filterByType("illustration")
            ->filterByPostId(null, Criteria::ISNOTNULL)
            ->filterBySite($this->currentSite->getSite());
        $eventIllustrationsQuery = ImageQuery::create()
            ->filterByType("illustration")
            ->filterByEventId(null, Criteria::ISNOTNULL)
            ->filterBySite($this->currentSite->getSite());
        $articleCoversQuery = $this->_createArticleQuery();

        $output->writeln($contributorPortraitsQuery->count() . " contributor portraits to migrate");
        $output->writeln($publisherLogosQuery->count() . " publisher logos to migrate");
        $output->writeln($stockItemPhotosQuery->count() . " stock items photos to migrate");
        $output->writeln($postIllustrationsQuery->count() . " post illustrations to migrate");
        $output->writeln($eventIllustrationsQuery->count() . " event illustrations to migrate");
        $output->writeln($articleCoversQuery->count() . " article covers to migrate");

        /* MIGRATE IMAGES */

        $loggerService = new LoggerService();

        $contributorPortraits = $contributorPortraitsQuery->find()->getData();
        $this->_migrateImages($loggerService, $output, "contributor portraits", $contributorPortraits);

        $publisherLogos = $publisherLogosQuery->find()->getData();
        $this->_migrateImages($loggerService, $output, "publisher logos", $publisherLogos);

        $stockItemsPhotos = $stockItemPhotosQuery->find()->getData();
        $this->_migrateImages($loggerService, $output, "stock items photos", $stockItemsPhotos);

        $postIllustrations = $postIllustrationsQuery->find()->getData();
        $this->_migrateImages($loggerService, $output, "post illustrations", $postIllustrations);

        $eventIllustrations = $eventIllustrationsQuery->find()->getData();
        if (count($eventIllustrations) > 0) {
            $this->_migrateImages($loggerService, $output, "event illustrations", $eventIllustrations);
        }

        for ($offset = $input->getOption("offset"); $offset < $articleCoversQuery->count(); $offset += 10000) {
            $articleCovers = $this->_createArticleQuery()->limit(10000)->offset($offset)->find()->getData();
            $this->_migrateImages($loggerService, $output, "article covers", $articleCovers);
        }

        $logMessage = "All images were migrated.";
        $output->writeln($logMessage);
        $loggerService->log("images-migrate", "info", $logMessage);

        return 0;
    }

    /**
     * @param Image[] $imagesToMigrate
     * @throws PropelException
     * @throws Exception
     */
    private function _migrateImages(
        LoggerService   $loggerService,
        OutputInterface $output,
        string          $imageType,
        array           $imagesToMigrate
    ): void
    {
        $mediaPath = $this->config->get("media_path");
        $oldBasePath = realpath(__DIR__ . "/../../" . $mediaPath);

        $imageCount = count($imagesToMigrate);
        $output->writeln("Migrating " . $imageCount . " $imageType");

        $progressBar = new ProgressBar($output, $imageCount);
        $progressBar->setFormat("%current%/%max% [%bar%] %percent:3s%% (%remaining:6s%) %message%");
        $progressBar->start();

        $imagesMigrated = 0;
        $imageSkipped = 0;

        $fileSystem = new Filesystem();

        foreach ($imagesToMigrate as $image) {

            $filePath = $image->getFilepath() . $image->getFilename();
            $model = $image->getArticle() ?? $image->getStockItem() ?? $image->getPost() ?? $image->getPublisher()
                ?? $image->getContributor() ?? $image->getEvent();

            if (!$model) {
                $loggerService->log("images-migrate", "info", "Skipped image {$image->getId()} without model: $filePath");
                $progressBar->setMessage("Skipped image {$image->getId()} without model: $filePath");
                $progressBar->advance();
                $imageSkipped++;
                continue;
            }


            $oldFullPath = $oldBasePath . "/" . $filePath;
            $newFullPath = $this->imagesService->getImagePathFor($model);

            if (!file_exists($oldFullPath)) {
                $loggerService->log("images-migrate", "info", "Skipped file not found: $oldFullPath");
                $progressBar->setMessage("Skipped file not found: $oldFullPath");
                $progressBar->advance();
                $imageSkipped++;
                continue;
            }

            if (file_exists($newFullPath)) {
                $realNewFullPath = realpath($newFullPath);
                $loggerService->log("images-migrate", "info", "Skipped already migrated file: $realNewFullPath");
                $progressBar->setMessage("Skipped already migrated file: $realNewFullPath");
                $progressBar->advance();
                $imageSkipped++;
                continue;
            }

            try {
                $fileSystem->copy($oldFullPath, $newFullPath);
            } catch (Exception $e) {
                throw new Exception("Failed to migrate image $oldFullPath: " . $e->getMessage());
            }

            $successMessage = "Migrated image $oldFullPath to " . realpath($newFullPath);
            $loggerService->log("files-migrate", "info", $successMessage);
            $progressBar->setMessage($successMessage);
            $progressBar->advance();
            $imagesMigrated++;
        }

        $progressBar->finish();
        $logMessage = "\n$imagesMigrated $imageType migrated to new path (and $imageSkipped skipped)";
        $loggerService->log("images-migrate", "info", $logMessage);
        $output->writeln($logMessage);
    }

    /**
     * @throws PropelException
     */
    private function _createArticleQuery(): ImageQuery
    {
        $publisherFilter = $this->currentSite->getOption("publisher_filter");
        $articleCoversQuery = ImageQuery::create()->filterByType("cover");

        if ($publisherFilter) {
            $allowedPublisherIds = explode(",", $publisherFilter);
            $articleCoversQuery = $articleCoversQuery
                ->joinWithArticle()
                ->useArticleQuery()
                ->filterByPublisherId($allowedPublisherIds)
                ->endUse();
        }

        return $articleCoversQuery;
    }
}