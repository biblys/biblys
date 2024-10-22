<?php

namespace Command;

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\Images\ImagesService;
use Biblys\Service\LoggerService;
use Exception;
use Model\Article;
use Model\ArticleQuery;
use Model\ImageQuery;
use Model\MediaFileQuery;
use Model\Stock;
use Model\StockQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class ImportMediaCommand extends Command
{
    protected static $defaultName = "media:import";

    public function __construct(
        private readonly CurrentSite $currentSite,
        private readonly Filesystem  $filesystem,
        string                       $name = null,
    )
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setDescription("Creates entries in the database for files found in the media directory");
    }

    /**
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $loggerService = new LoggerService();

        $mediaFolderPath = realpath(__DIR__ . '/../../public/media/');

        $output->writeln(["Listing files in public/media…"]);
        $loggerService->log("media-import", "info", "Listing files public/media…");

        $finder = new Finder();
        $subDirectories = $finder
            ->in($mediaFolderPath)
            ->depth(0)
            ->directories();

        $fileCount = 0;
        foreach ($subDirectories as $subDirectory) {
            $finder = new Finder();
            $imageFilesInCurrentDirectory = $finder
                ->in($subDirectory)
                ->depth(0)
                ->files();
            $currentDirectoryFileCount = $imageFilesInCurrentDirectory->count();
            $fileCount += $currentDirectoryFileCount;
            $output->writeln(["- Directory \"{$subDirectory->getBasename()}\": $currentDirectoryFileCount files"]);
            $mediaSubDirectories[] = $imageFilesInCurrentDirectory;
        }

        $output->writeln(["$fileCount files to process"]);
        $loggerService->log("media-import", "info", "$fileCount files to process");


        $progress = new ProgressBar($output, $fileCount);
        $progress->setFormat("%current%/%max% [%bar%] %percent:3s%% (%remaining:6s%) %message%");
        $progress->setMessage("");
        $progress->start();

        $deletedFilesCount = 0;
        $skippedFilesCount = 0;
        $loadedImagesCount = 0;
        foreach ($mediaSubDirectories as $imagesDirectoryFiles) {
            foreach ($imagesDirectoryFiles as $imageFile) {
                $filePath = $imageFile->getRealPath();
                $re = '/(.*)\/(.*)\/(.*)\.(.*)$/m';

                preg_match_all($re, $filePath, $matches, PREG_SET_ORDER);

                if (!isset($matches[0])) {
                    throw new Exception("Invalid path $filePath");
                }

                [, , $directory, $fileName, $extension] = $matches[0];
                $mediaFile = MediaFileQuery::create()
                    ->filterByDir($directory)
                    ->filterByFile($fileName)
                    ->filterByExt($extension)
                    ->filterBySiteId($this->currentSite->getSite()->getId())
                    ->findOne();

                if (!$mediaFile) {
                    $this->filesystem->remove($filePath);
                    $progress->setMessage("Deleted file for inexistant db entry $directory/$fileName.$extension");
                    $loggerService->log("media-import", "info", "Deleted file for inexistant db entry $directory/$fileName.$extension");
                    $progress->advance();
                    $deletedFilesCount++;
                    continue;
                }

                if ($mediaFile->getFileSize() !== null) {
                    $progress->setMessage("Skipped already imported image for $directory/$fileName.$extension");
                    $loggerService->log("media-import", "info", "Ignored already imported image for $directory/$fileName.$extension");
                    $progress->advance();
                    $skippedFilesCount++;
                    continue;
                }

                $mediaFile->setFileSize($imageFile->getSize());
                $mediaFile->save();

                $progress->setMessage("Imported image for $directory/$fileName.$extension");
                $loggerService->log("media-import", "info", "Imported image for $directory/$fileName.$extension");
                $loadedImagesCount++;
                $progress->advance();
            }
        }

        $progress->finish();

        $output->writeln(["", "Loaded $loadedImagesCount images, skipped $skippedFilesCount files and deleted $deletedFilesCount files."]);
        return 0;
    }

    /**
     * @throws Exception
     */
    private function _getModelQuery(string $modelType): ArticleQuery|StockQuery
    {
        return match ($modelType) {
            "article" => ArticleQuery::create(),
            "stock" => StockQuery::create(),
            default => throw new Exception("Unsupported model type $modelType"),
        };
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    private function _getModelTitle(Article|Stock $model, string $modelType): string
    {
        return match ($modelType) {
            "article" => $model->getTitle(),
            "stock" => $model->getArticle()->getTitle(),
            default => throw new Exception("Unsupported model type"),
        };
    }
}