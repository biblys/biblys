<?php

namespace Biblys\Service\Updater;

use Biblys\Service\Config;
use Gitonomy\Git\Repository;
use Gitonomy\Git\Exception\ProcessException;

class Updater
{
    private $repository_path,
        $current_version,
        $repository,
        $releases,
        $latest;
    /**
     * @var Config
     */
    private $config;

    public function __construct(
        string $repository_path,
        string $current_version,
        Config $config
    )
    {
        $this->repository_path = $repository_path;
        $this->current_version = $current_version;
        $this->config = $config;
    }

    /**
     * Download available updates from repo
     * @return bool true if repository was successfuly reached
     * @throws UpdaterException
     */
    public function downloadUpdates(Config $config): bool
    {
        if ($config->get("environment") === "dev" || $config->get("environment") === "test") {
            return false;
        }

        try {
            $repository = $this->getRepository();
            $repository->run('fetch', ['origin', '--tags', '-f']);
            return true;
        } catch (ProcessException $exception) {
            throw new UpdaterException(
                "Une erreur est survenue pendant la récupération des mises à jour.",
                $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * Compare git tags to get the latest update
     * @return Release latest update
     */
    public function getLatestRelease(): ?Release
    {
        if (isset($this->latest)) {
            return $this->latest;
        }

        // Get latest version & check if up-to-date
        $releases = $this->getReleases();

        if (count($releases) === 0) {
            return null;
        }

        $this->latest = $releases[0];
        return $this->latest;
    }

    /**
     * @return Release[]
     */
    public function getReleases(): array
    {
        if (isset($this->releases)) {
            return $this->releases;
        }

        $repository = $this->getRepository();

        // Get tags that are release (starts with a number)
        $releases = [];
        $tags = $repository->getReferences()->getTags();
        foreach ($tags as $tag) {
            if (preg_match("/^\\d/", $tag->getName())) {
                $release = new Release($tag, $repository);
                $releases[] = $release;
            }
        }

        // Sort releases by version number
        usort($releases, function($a, $b) {
            return version_compare($a->version, $b->version);
        });
        $this->releases = array_reverse($releases);
        return $this->releases;
    }

    /**
     * @throws ReleaseNotFoundException
     */
    public function getRelease(string $version): Release
    {
        $releases = $this->getReleases();
        foreach($releases as $release) {
            if ($release->version === $version) {
                return $release;
            }
        }

        throw new ReleaseNotFoundException(sprintf("Cannot find release for version %s", $version));
    }

    /**
     * Filters releases to return only those newer than a version
     * @param string $version: the version to compare
     * @return Release[] the releases that are newer
     */
    public function getReleasesNewerThan(string $version): array
    {
        $releases = $this->getReleases();

        // Filter release by version number
        return array_filter($releases, function($release) use($version) {
            return version_compare($release->version, $version, ">");
        });
    }

    /**
     * Checks if latest update is higher than current version
     * @return bool
     */
    public function isUpdateAvailable(Config $config): bool
    {
        if ($config->get("environment") === "dev" || $config->get("environment") === "test") {
            return false;
        }

        $latest = $this->getLatestRelease();
        return version_compare($latest->version, $this->current_version, ">");
    }

    /**
     * Apply update with a git checkout
     * @param  Release  $release the git tag to use
     */
    public function applyRelease(Release $release): void
    {
        $repository = $this->getRepository();
        $wc = $repository->getWorkingCopy();
        $wc->checkout($release->version);
    }

    /**
     * Get the current repository with logger
     * @return object git repository
     */
    private function getRepository()
    {
        if (isset($this->repository)) {
            return $this->repository;
        }

        // Get local repo & working copy
        $repository = new Repository($this->repository_path);

        $this->repository = $repository;
        return $this->repository;
    }
}
