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

use Biblys\Service\Images\ImagesService;
use Biblys\Service\LoggerService;
use Exception;
use Model\ImageQuery;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class ExportImagesCommand extends Command
{
    protected static $defaultName = "images:export";

    public function __construct(
        private readonly ImagesService $imagesService,
        string                         $name = null,
    )
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setDescription("Exporter");
        $this->addOption(
            name: "collection",
            shortcut: "c",
            mode: InputArgument::OPTIONAL,
            description: "The collection of articles images should be exported",
            default: 0,
        );
    }

    /**
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $loggerService = new LoggerService();
        $filesystem = new Filesystem();

        $exportPath = __DIR__ . "/../../export";
        if (!$filesystem->exists($exportPath)) {
            $filesystem->mkdir($exportPath);
        }
        $exportPath = realpath($exportPath);

        $collectionId = $input->getOption("collection");

        $images = ImageQuery::create()
            ->joinWithArticle()
            ->useArticleQuery()
                ->filterByCollectionId($collectionId)
            ->endUse()
            ->find();

        $imagesCount = $images->count();
        $output->writeln(["$imagesCount images to process"]);
        $loggerService->log("images-import", "info", "$imagesCount images to process");

        $progress = new ProgressBar($output, $imagesCount);
        $progress->setFormat("%current%/%max% [%bar%] %percent:3s%% (%remaining:6s%) %message%");
        $progress->setMessage("");
        $progress->start();

        $exportedImagesCount = 0;
        foreach ($images as $image) {
            $article = $image->getArticle();
            $articleTitle = $article->getTitle();
            $imagePath = $this->imagesService->getImagePathFor($article);
            $imageExportPath = $exportPath."/".$image->getFilepath().$image->getFilename();
            $logMessage = "Exporting image for article $articleTitle to {$imageExportPath}…";
            $progress->setMessage($logMessage);
            $loggerService->log("images-export", "info", $logMessage);

            $filesystem->copy($imagePath, $imageExportPath);

            $exportedImagesCount++;
            $progress->advance();

        }

        $progress->finish();

        $output->writeln(["", "Exported $exportedImagesCount images"]);
        return 0;
    }
}