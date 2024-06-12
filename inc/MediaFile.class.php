<?php

class MediaFile extends Entity
{
    protected $prefix = 'media';
    public $trackChange = false;

    public function getUrl()
    {
        global $site;

        return '/' . $site->get('name') . '/media/' . $this->get('dir') . '/' . $this->get('file')
            . '.' . $this->get('ext');
    }
}

class MediaFileManager extends EntityManager
{
    protected $prefix = 'media',
        $table = 'medias',
        $object = 'MediaFile';

    public function getMediaFolderPath()
    {
        $mediaFolderPath = BIBLYS_PATH . 'public/media/';
        if (!is_dir($mediaFolderPath)) {
            throw new Exception("Le dossier $mediaFolderPath n'existe pas.");
        }

        return $mediaFolderPath;
    }

    public function deleteDirectory($dir)
    {
        $dirFullPath = $this->getMediaFolderPath() . $dir;

        // Delete all directory media from database
        $medias = $this->getAll(['media_dir' => $dir]);
        foreach ($medias as $media) {
            $this->delete($media);
        }

        if (!empty(array_slice(scandir($dirFullPath), 2))) {
            throw new \Exception("Directory $dir is not empty");
        }

        // Delete folder
        $deleted = rmdir($dirFullPath);
        if (!$deleted) {
            throw new Exception("Le dossier $dir n'a pas pu être supprimé.");
        }
    }

    public function beforeDelete($media)
    {
        $mediaFolderPath = $this->getMediaFolderPath();

        // Delete file
        $filePath = $mediaFolderPath . $media->get('dir') . '/' . $media->get('file') . '.' . $media->get('ext');
        if (file_exists($filePath)) {
            $deleted = @unlink($filePath);
            if (!$deleted) {
                throw new Exception("Le fichier $filePath n'a pas pu être supprimé.");
            }
        }

        // Delete thumbs created for this file
        $thumbs = glob($mediaFolderPath . $media->get('dir') . '/' . $media->get('file') . '__*.jpg');
        foreach ($thumbs as $thumb) {
            $deleted = unlink($thumb);
            if (!$deleted) {
                throw new Exception("Le fichier $thumb n'a pas pu être supprimé.");
            }
        }
    }
}
