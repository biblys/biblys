<?php


namespace Biblys\Service\Updater;


use Gitonomy\Git\Reference\Tag;
use Gitonomy\Git\Repository;

class Release
{
    public $version;
    public $date;
    public $notes;

    public function __construct($version, $date, $notes)
    {
        $this->version = $version;
        $this->date = $date;
        $this->notes = $notes;
    }

    public static function buildFromTag(Tag $tag, Repository $repository): Release
    {
        $version = $tag->getName();
        $date = $tag->getLastModification()->getAuthorDate();
        $notes = $repository->run('tag', ['-l', '-n99', $tag->getName()]);
        $notesWithoutVersion = str_replace($version, "", $notes);

        return new Release($version, $date, $notesWithoutVersion);
    }
}