<?php


namespace Biblys\Service\Updater;


use DateTime;
use Gitonomy\Git\Reference\Tag;
use Gitonomy\Git\Repository;

class Release
{
    private $tag;
    private $repository;
    public $version;

    public function __construct(Tag $tag, Repository $repository)
    {
        $this->tag = $tag;
        $this->repository = $repository;
        $this->version = $tag->getName();
    }

    public function getDate(): DateTime
    {
        return $this->tag->getLastModification()->getAuthorDate();
    }

    public function getNotes(): string
    {
        $notes = $this->repository->run('tag', ['-l', '-n99', $this->version]);
        return str_replace($this->version, "", $notes);
    }
}