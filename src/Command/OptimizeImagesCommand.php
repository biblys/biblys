<?php

namespace Command;

use Biblys\Service\Config;
use Biblys\Service\Images\ImageForModel;
use Biblys\Service\Images\ImagesService;
use Biblys\Service\LoggerService;
use Biblys\Test\ModelFactory;
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
        $targetDimension = 2000;
        $images = ImageQuery::create()
            ->filterByWidth($targetDimension, Criteria::GREATER_THAN)
            ->orderByFilesize(Criteria::DESC)
            ->find();
        $progressBar = new ProgressBar($output, $images->count());
        $progressBar->setFormat("%current%/%max% [%bar%] %percent:3s%% (%remaining:6s%) %message%");
        $progressBar->start();

        $optimizedImages = 0;
        $skippedImages = 0;
        $spaceSaved = 0;

        foreach ($images as $image) {
            $imageForModel = new ImageForModel($this->config, $image);

            if (!file_exists($imageForModel->getFilePath())) {
                $progressBar->setMessage("Skipping not found {$image->getType()}: {$image->getFilename()}");
                $progressBar->advance();
                $skippedImages++;
                continue;
            }

            $oldSizeInMB = round(filesize($imageForModel->getFilePath()) / 1024 / 1024, 2);

            $optimizedImagePath = "{$imageForModel->getFilePath()}-optimized.jpg";
            Image::load($imageForModel->getFilePath())
                ->width($targetDimension)
                ->height($targetDimension)
                ->save($optimizedImagePath);
            $newSizeInMB = round(filesize($optimizedImagePath) / 1024 / 1024, 2);

            $model = $image->getArticle() ?? $image->getStockItem();
            $this->imagesService->addImageFor($model, $optimizedImagePath);
            $this->filesystem->remove($optimizedImagePath);

            $loggerService = new LoggerService();
            $successMessage = "Optimized {$image->getType()}: {$image->getFilename()} ($oldSizeInMB Mo > $newSizeInMB Mo)";
            $loggerService->log("images-optimize", "info", $successMessage);
            $progressBar->setMessage($successMessage);

            $spaceSaved += $oldSizeInMB - $newSizeInMB;

            $progressBar->advance();
            $optimizedImages++;
        }

        $progressBar->finish();
        $output->writeln("\n$optimizedImages images optimized, $skippedImages images skipped, $spaceSaved Mo saved");

        return 0;
    }
}