<?php

namespace Command;

use Biblys\Service\Config;
use Biblys\Service\Images\ImagesService;
use Biblys\Service\LoggerService;
use Exception;
use Model\Article;
use Model\ArticleQuery;
use Model\Base\People;
use Model\ImageQuery;
use Model\PeopleQuery;
use Model\Post;
use Model\PostQuery;
use Model\Publisher;
use Model\PublisherQuery;
use Model\Stock;
use Model\StockQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class ImportImagesCommand extends Command
{
    protected static $defaultName = "images:import";

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
        $this->setDescription("Creates entries in the database for images found in the images directory");
        $this->addArgument(
            "target_model",
            InputArgument::REQUIRED,
            "The model type to import images for"
        );
        $this->addOption(
            name: "start",
            shortcut: "s",
            mode: InputArgument::OPTIONAL,
            description: "Directory to start the import from (0-99)",
            default: 0,
        );
    }

    /**
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $loggerService = new LoggerService();

        $modelType = $input->getArgument("target_model");
        $startDirectory = $input->getOption("start");

        $sourceDirectory = $modelType === "article" ? "book" : $modelType;

        $imagesDirectory = $this->config->getImagesPath() . $sourceDirectory;
        $output->writeln(["Listing in files {$imagesDirectory}…"]);
        $loggerService->log("images-import", "info", "Listing files {$imagesDirectory}…");

        $imagesSubDirectories = [];
        $imagesDirectoryFullPath = __DIR__ . "/../../" . $imagesDirectory;
        $fileCount = 0;
        for ($i = $startDirectory; $i < 100; $i++) {
            $currentDirectoryName = str_pad($i, 2, "0", STR_PAD_LEFT);
            $currentDirectory = $imagesDirectoryFullPath . "/" . $currentDirectoryName;
            $currentDirectoryPath = $imagesDirectory . "/" . $currentDirectoryName;
            if (!$this->filesystem->exists($currentDirectory)) {
                $output->writeln(["- Directory $currentDirectoryPath : —"]);
                continue;
            }

            $finder = new Finder();
            $imageFilesInCurrentDirectory = $finder
                ->in($currentDirectory)
                ->files();
            $currentDirectoryFileCount = $imageFilesInCurrentDirectory->count();
            $fileCount += $currentDirectoryFileCount;
            $output->writeln(["- Directory $currentDirectoryPath : $currentDirectoryFileCount files"]);
            $imagesSubDirectories[] = $imageFilesInCurrentDirectory;
        }

        $output->writeln(["$fileCount files to process"]);
        $loggerService->log("images-import", "info", "$fileCount files to process");

        $progress = new ProgressBar($output, $fileCount);
        $progress->setFormat("%current%/%max% [%bar%] %percent:3s%% (%remaining:6s%) %message%");
        $progress->setMessage("");
        $progress->start();

        $deletedFilesCount = 0;
        $skippedFilesCount = 0;
        $loadedImagesCount = 0;
        foreach ($imagesSubDirectories as $imagesDirectoryFiles) {
            foreach ($imagesDirectoryFiles as $imageFile) {
                $filePath = $imageFile->getRealPath();
                preg_match_all("/$sourceDirectory\\/\\d{2}\\/(\\d+)\\.(jpg|png)/m", $filePath, $matches);
                $modelId = $matches[1][0];
                $model = $this->_getModelQuery($modelType)->findPk($modelId);

                if (!$model) {
                    $this->filesystem->remove($filePath);
                    $progress->setMessage("Deleted image for inexistant $modelType $modelId");
                    $loggerService->log("images-import", "info", "Deleted image for inexistant $modelType $modelId");
                    $progress->advance();
                    $deletedFilesCount++;
                    continue;
                }

                if ($modelType === "stock" && $model->getArticle() === null) {
                    $model->delete();
                    $this->filesystem->remove($filePath);
                    $progress->setMessage("Deleted image and stock for inexistant article {$model->getArticleId()}");
                    $loggerService->log(
                        "images-import",
                        "info",
                        "Deleted image and stock for inexistant article {$model->getArticleId()}"
                    );
                    $progress->advance();
                    $deletedFilesCount++;
                    continue;
                }

                $modelTitle = $this->_getModelTitle($model, $modelType);

                if ($this->imagesService->imageExistsFor($model)) {
                    $progress->setMessage("Skipped already imported image for $modelType $modelId ($modelTitle)");
                    $loggerService->log("images-import", "info", "Ignored already imported image for $modelType $modelId ($modelTitle)");
                    $progress->advance();
                    $skippedFilesCount++;
                    continue;
                }

                $this->imagesService->addImageFor($model, $filePath);

                if ($modelType === "stock") {
                    $image = ImageQuery::create()->filterByStockItem($model)->findOne();
                    $image->setSite($model->getSite());
                    $image->save();
                }

                if ($modelType === "post") {
                    $image = ImageQuery::create()->filterByPost($model)->findOne();
                    $image->setSite($model->getSite());
                    $image->save();
                }

                $progress->setMessage("Imported image for $modelType $modelId ($modelTitle)");
                $loggerService->log("images-import", "info", "Imported image for $modelType $modelId ($modelTitle)");
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
    private function _getModelQuery(string $modelType): ArticleQuery|StockQuery|PostQuery|PublisherQuery|PeopleQuery
    {
        return match ($modelType) {
            "article" => ArticleQuery::create(),
            "stock" => StockQuery::create(),
            "post" => PostQuery::create(),
            "publisher" => PublisherQuery::create(),
            "people" => PeopleQuery::create(),
            default => throw new Exception("Unsupported model type $modelType"),
        };
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    private function _getModelTitle(Article|Stock|Post|Publisher|People $model, string $modelType): string
    {
        return match ($modelType) {
            "article", "post" => $model->getTitle(),
            "stock" => $model->getArticle()->getTitle(),
            "publisher", "people" => $model->getName(),
            default => throw new Exception("Unsupported model type $modelType"),
        };
    }
}