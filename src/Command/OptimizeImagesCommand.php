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
use Biblys\Service\Images\ImageForModel;
use Biblys\Service\Images\ImagesService;
use Biblys\Service\LoggerService;
use Exception;
use Model\ImageQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spatie\Image\Image;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class OptimizeImagesCommand extends Command
{
    protected static $defaultName = "images:optimize";

    public function __construct(
        private readonly Config        $config,
        private readonly Filesystem    $filesystem,
        private readonly ImagesService $imagesService,
        string                         $name = null,
    )
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setDescription("Optimizes images found in the images directory");
    }

    /**
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $loggerService = new LoggerService();

        $targetDimension = 2000;
        $images = ImageQuery::create()
            ->filterByWidth($targetDimension, Criteria::GREATER_THAN)
            ->orderByFilesize(Criteria::DESC)
            ->find();

        $logMessage = "Found {$images->count()} images to optimize";
        $output->writeln($logMessage);
        $loggerService->log("images-optimize", "info", $logMessage);

        $progressBar = new ProgressBar($output, $images->count());
        $progressBar->setFormat("%current%/%max% [%bar%] %percent:3s%% (%remaining:6s%) %message%");
        $progressBar->start();

        $optimizedImages = 0;
        $skippedImages = 0;
        $spaceSaved = 0;

        foreach ($images as $image) {
            $imageForModel = new ImageForModel($this->config, $image);
            $target = $image->getArticle() ?? $image->getStockItem() ?? $image->getPost() ?? $image->getPublisher() ??
                $image->getContributor() ?? $image->getEvent();

            if (!$target) {
                $logMessage = "Skipping image {$image->getId()} with no nonexistent model {$image->getType()}";
                $loggerService->log("images-optimize", "info", $logMessage);
                $progressBar->setMessage($logMessage);
                $progressBar->advance();
                $skippedImages++;
                continue;
            }

            if (!file_exists($imageForModel->getFilePath())) {
                $logMessage = "Skipping not found {$image->getType()}: {$image->getFilename()}";
                $loggerService->log("images-optimize", "info", $logMessage);
                $progressBar->setMessage($logMessage);
                $progressBar->advance();
                $skippedImages++;
                continue;
            }

            $oldSizeInMB = round(filesize($imageForModel->getFilePath()) / 1024 / 1024, 2);

            $logMessage = "Optimizing image for {$image->getType()} {$target->getId()} ($oldSizeInMB MB)… ";
            $loggerService->log("images-optimize", "info", $logMessage);

            try {
                $optimizedImagePath = sys_get_temp_dir() . "/optimized-image";
                Image::load($imageForModel->getFilePath())
                    ->width($targetDimension)
                    ->height($targetDimension)
                    ->save($optimizedImagePath);
                $newSizeInMB = round(filesize($optimizedImagePath) / 1024 / 1024, 2);


                $this->imagesService->addImageFor($target, $optimizedImagePath);
            } catch (Exception $exception) {
                $errorMessage = "Failed to optimize image {$image->getId()}: {$exception->getMessage()}";
                $output->writeln(["", $errorMessage]);
                $loggerService->log("images-optimize", "error", $errorMessage);
                $skippedImages++;
                $progressBar->advance();
                continue;
            }

            $this->filesystem->remove($optimizedImagePath);

            $successMessage = "Optimized image {$image->getId()} ({$image->getType()}): $oldSizeInMB Mo > $newSizeInMB Mo";
            $loggerService->log("images-optimize", "info", $successMessage);
            $progressBar->setMessage($successMessage);

            $spaceSaved += $oldSizeInMB - $newSizeInMB;

            $progressBar->advance();
            $optimizedImages++;
        }

        $progressBar->finish();
        $logMessage = "\n$optimizedImages images optimized, $skippedImages images skipped, $spaceSaved Mo saved";
        $output->writeln($logMessage);
        $loggerService->log("images-optimize", "info", $logMessage);

        return 0;
    }
}