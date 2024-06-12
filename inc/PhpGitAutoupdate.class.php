<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use Gitonomy\Git\Repository;
use Gitonomy\Git\Exception\ProcessException;

class PhpGitAutoupdate
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
     * @return boolean true if repository was successfuly reached
     */
    public function downloadUpdates()
    {
        $repository = $this->getRepository();

        try {
            $repository->run('fetch', ['origin', '--tags']);
        } catch(ProcessException $e) {
            return false;
        }

        return true;
    }

    /**
     * Compare git tags to get the latest update
     * @return string latest update
     */
    public function getLatestRelease()
    {
        if (isset($this->latest)) {
            return $this->latest;
        }

        // Get latest version & check if uptodate
        $releases = $this->getReleases();
        
        if (count($releases) === 0) {
            return null;
        }

        $this->latest = $releases[0];
        return $this->latest;
    }

    public function getReleases()
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

        foreach ($releases as $release) {
            if ($release["version"] == $version) {
                return $release;
            }
        }
    }

    /**
     * Filters releases to return only those newer than a version
     * @param String $version: the version to compare
     * @return Array: the releases that are newer
     */
    public function getReleasesNewerThan($version)
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
     * @param Array $releases: the releases for which we want details
     * @return Array: the provided releases with details
     */
    public function getReleasesDetails($releases)
    {
        return array_map(function($release) {
            $release["date"] = $release["tag"]->getLastModification()->getAuthorDate();
            $release["notes"] = $this->getReleaseNotes($release["tag"]->getName());
            return $release;
        }, $releases);
    }

    /**
     * Checks if latest update is higher than current version
     * @return boolean
     */
    public function updateAvailable()
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
     * @param  string  $version the git tag to use
     * @return boolean          returns true if success
     */
    public function applyRelease($release)
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
