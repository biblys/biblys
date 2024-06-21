<?php

namespace Command;

use Biblys\Service\Config;
use Biblys\Service\Images\ImagesService;
use Biblys\Service\LoggerService;
use Exception;
use Model\ArticleQuery;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class ImportImagesCommand extends Command
{
    protected static $defaultName = 'images:import';

    public function __construct(
        private readonly Config $config,
        private readonly Finder $finder,
        private readonly Filesystem $filesystem,
        private readonly ImagesService $imagesService,
        string $name = null,
    )
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setDescription('Clears database and execute migrations');
    }

    /**
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $loggerService = new LoggerService();

        $coversDirectory = $this->config->getImagesPath() . "/book";
        $output->writeln(["Listing in files {$coversDirectory}…"]);
        $loggerService->log("images-import", "info", "Listing files {$coversDirectory}…");

        $coversDirectoryFullPath = __DIR__ . "/../.." . $coversDirectory;
        $coverFiles = $this->finder
            ->in($coversDirectoryFullPath)
            ->files()
            ->depth(1);

        $fileCount = $coverFiles->count();
        $output->writeln(["$fileCount files to process"]);
        $loggerService->log("images-import", "info", "$fileCount files to process");

        $progress = new ProgressBar($output, $fileCount);
        $progress->setFormat("%current%/%max% [%bar%] %percent:3s%% (%remaining:6s%) %message%");
        $progress->setMessage("");
        $progress->start();

        $deletedFilesCount = 0;
        $skippedFilesCount = 0;
        $loadedImagesCount = 0;
        foreach ($coverFiles as $coverFile) {
            $filePath = $coverFile->getRealPath();
            preg_match_all('/book\/\d{2}\/(\d+)\.jpg/m', $filePath, $matches);
            $articleId = $matches[1][0];
            $article = ArticleQuery::create()->findPk($articleId);

            if (!$article) {
                $this->filesystem->remove($filePath);
                $progress->setMessage("Deleted cover for inexistant article $articleId");
                $loggerService->log("images-import", "info", "Deleted cover for inexistant article $articleId");
                $progress->advance();
                $deletedFilesCount++;
                continue;
            }

            if ($this->imagesService->articleHasCoverImage($article)) {
                $progress->setMessage("Skipped already imported cover for article $articleId ({$article->getTitle()})");
                $loggerService->log("images-import", "info", "Ignored already imported cover for article $articleId  ({$article->getTitle()})");
                $progress->advance();
                $skippedFilesCount++;
                continue;
            }

            $this->imagesService->addArticleCoverImage($article, $filePath);

            $progress->setMessage("Imported cover for article $articleId ({$article->getTitle()})");
            $loggerService->log("images-import", "info", "Imported cover for article $articleId ({$article->getTitle()})");
            $loadedImagesCount++;
            $progress->advance();
        }

        $progress->finish();

        $output->writeln(["Loaded $loadedImagesCount images, skilled $skippedFilesCount files and deleted $deletedFilesCount files."]);
        return 0;
    }
}