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

use Biblys\Service\LoggerService;
use Exception;
use Model\File;
use Model\FileQuery;
use Model\OptionQuery;
use Model\Site;
use Model\SiteQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class MigrateFilesCommand extends Command
{
    protected static $defaultName = "files:migrate";

    public function __construct(
        private readonly Filesystem    $filesystem,
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
        $filesToMigrate = FileQuery::create()
            ->filterBySiteId(null, Criteria::ISNULL)
            ->leftJoinWithArticle()
            ->limit(10)
            ->find();

        $progressBar = new ProgressBar($output, $filesToMigrate->count());
        $progressBar->setFormat("%current%/%max% [%bar%] %percent:3s%% (%remaining:6s%) %message%");
        $progressBar->start();

        $filesMigrated = 0;
        $filesSkipped = 0;

        $oldBasePath = __DIR__ . "/../../../../dl/files";

        $loggerService = new LoggerService();

        $sitesForPublisherIds = $this->_getSitesForPublishers();

        /** @var File $file */
        foreach ($filesToMigrate as $file) {

            $padDirectory = str_pad(substr($file->getId(), -2, 2), 2, "0", STR_PAD_LEFT);
            $articleDirectory = $file->getId();
            $oldFilePath = "$oldBasePath/$padDirectory/$articleDirectory/{$file->getHash()}";

            if (!$this->filesystem->exists($oldFilePath)) {
                $logMessage = "Skipped file not found on filesystem {$file->getId()}";
                $loggerService->log("files-migrate", "info", $logMessage);
                $progressBar->setMessage($logMessage);
                $progressBar->advance();
                $filesSkipped++;
                continue;
            }

            $article = $file->getArticle();
            if (!$article) {
                $logMessage = "Skipped file {$file->getId()} for non existent article {$file->getArticleId()}";
                $loggerService->log("files-migrate", "info", $logMessage);
                $progressBar->setMessage($logMessage);
                $progressBar->advance();
                $filesSkipped++;
                continue;
            }

            $publisherId = $article->getPublisherId();
            $targetSite = $this->_getSiteForPublisher($publisherId, $sitesForPublisherIds);

            if (!$targetSite) {
                $logMessage = "No target site found for file {$file->getId()} with publisher $publisherId";
                $loggerService->log("files-migrate", "info", $logMessage);
                $progressBar->setMessage($logMessage);
                $progressBar->advance();
                $filesSkipped++;
                continue;
            }

            $file->setSite($targetSite);
            $file->save();

            $targetSiteName = $targetSite->getName();

            $targetBasePath = realpath(__DIR__ . "/../../../");
            $targetFilePath = "/$targetSiteName/content/downloadable/{$article->getId()}/{$file->getHash()}";
            $targetFullPath = $targetBasePath . $targetFilePath;

            $this->filesystem->copy($oldFilePath, $targetFullPath);

            $successMessage = "Migrated file {$file->getId()} to new path $targetFullPath";
            $loggerService->log("files-migrate", "info", $successMessage);
            $progressBar->setMessage($successMessage);

            $progressBar->advance();
            $filesMigrated++;
        }

        $progressBar->finish();
        $logMessage = "\n$filesMigrated files migrated to new path and $filesSkipped files skipped";
        $loggerService->log("files-migrate", "info", $logMessage);
        $output->writeln($logMessage);

        return 0;
    }

    /**
     * @throws PropelException
     */
    private function _getSitesForPublishers(): array
    {
        $sitesForPublisherIds = [];
        $sites = SiteQuery::create()->find();

        /** @var Site $site */
        foreach ($sites as $site) {
            $sitesForPublisherIds[$site->getPublisherId()] = $site;
        }

        $publisherFilterOptions = OptionQuery::create()
            ->filterByKey("publisher_filter")
            ->find();
        foreach ($publisherFilterOptions as $publisherFilterOption) {
            $publisherIds = explode(",", $publisherFilterOption->getValue());
            foreach ($publisherIds as $publisherId) {
                $sitesForPublisherIds[$publisherId] = $publisherFilterOption->getSite();
            }
        }

        return $sitesForPublisherIds;
    }

    private function _getSiteForPublisher(int $publisherId, array $sitesForPublisherIds): ?Site
    {
        if (!isset($sitesForPublisherIds[$publisherId])) {
            return null;
        }

        return $sitesForPublisherIds[$publisherId];
    }
}