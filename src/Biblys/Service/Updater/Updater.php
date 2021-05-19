<?php

namespace Biblys\Service\Updater;

use Exception;
use Gitonomy\Git\Repository;
use Gitonomy\Git\Exception\ProcessException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Updater
{
    private $repository_path,
        $current_version,
        $repository,
        $releases,
        $latest;

    public function __construct($repository_path, $current_version)
    {
        $this->repository_path = $repository_path;
        $this->current_version = $current_version;
    }

    /**
     * Download available updates from repo
     * @return bool true if repository was successfuly reached
     * @throws UpdaterException
     */
    public function downloadUpdates(): bool
    {
        try {
            $repository = $this->getRepository();
            $repository->run('fetch', ['origin', '--tags']);
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
     * @return array latest update
     */
    public function getLatestRelease(): array
    {
        if (isset($this->latest)) {
            return $this->latest;
        }

        // Get latest version & check if up-to-date
        $releases = $this->getReleases();

        if (count($releases) === 0) {
            return [];
        }

        $this->latest = $releases[0];
        return $this->latest;
    }

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
                $release["tag"] = $tag;
                $release["version"] = $tag->getName();
                $releases[] = $release;
            }
        }

        // Sort releases by version number
        usort($releases, function($a, $b) {
            return version_compare($a["version"], $b["version"]);
        });
        $this->releases = array_reverse($releases);
        return $this->releases;
    }

    public function getRelease($version)
    {
        $releases = $this->getReleases();
        $key = array_search($version, $releases);

        if (!isset($releases[$key])) {
            return new NotFoundHttpException(sprintf("Cannot find release for version %s", $version));
        }

        return $releases[$key];
    }

    /**
     * Filters releases to return only those newer than a version
     * @param string $version: the version to compare
     * @return array the releases that are newer
     */
    public function getReleasesNewerThan(string $version): array
    {
        $releases = $this->getReleases();

        // Filter release by version number
        $releases = array_filter($releases, function($release) use($version) {
            return version_compare($release["version"], $version, ">");
        });

        // Get details for filtered releases
        return $this->getReleasesDetails($releases);
    }

    /**
     * Get details for provided releases
     * @param array $releases the releases for which we want details
     * @return array the provided releases with details
     */
    public function getReleasesDetails(array $releases): array
    {
        return array_map(function($release) {
            $release["date"] = $release["tag"]->getLastModification()->getAuthorDate();
            $release["notes"] = $this->getReleaseNotes($release["tag"]->getName());
            return $release;
        }, $releases);
    }

    /**
     * Checks if latest update is higher than current version
     * @return bool
     */
    public function isUpdateAvailable(): bool
    {
        $latest = $this->getLatestRelease();
        return version_compare($latest["version"], $this->current_version, ">");
    }

    public function getReleaseNotes($version)
    {
        $repository = $this->getRepository();
        $notes = $repository->run('tag', ['-l', '-n99', $version]);
        return str_replace($version, '', $notes);
    }

    /**
     * Apply update with a git checkout
     * @param  array  $release the git tag to use
     * @return bool true on success
     */
    public function applyRelease(array $release): bool
    {
        $repository = $this->getRepository();
        try {
            $wc = $repository->getWorkingCopy();
            $wc->checkout($release["version"]);
        } catch (Exception $e) {
            trigger_error($e->getMessage());
        }
        return true;
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
