<?php

class MediaFile extends Entity
{
    protected $prefix = 'media';
    public $trackChange = false;
}

class MediaFileManager extends EntityManager
{
    protected $prefix = 'media',
        $table = 'medias',
        $object = 'MediaFile';

    /**
     * @throws Exception
     */
    public function getMediaFolderPath(): string
    {
        $mediaFolderPath = __DIR__ . '/../public/media/';
        if (!is_dir($mediaFolderPath)) {
            throw new Exception("Le dossier $mediaFolderPath n'existe pas.");
        }

        return $mediaFolderPath;
    }

    /**
     * @throws Exception
     */
    public function deleteDirectory($dir): void
    {
        $dirFullPath = $this->getMediaFolderPath() . $dir;

        // Delete all directory media from database
        $medias = $this->getAll(['media_dir' => $dir]);
        foreach ($medias as $media) {
            $this->delete($media);
        }

        if (!empty(array_slice(scandir($dirFullPath), 2))) {
            throw new Exception("Directory $dir is not empty");
        }

        // Delete folder
        $deleted = rmdir($dirFullPath);
        if (!$deleted) {
            throw new Exception("Le dossier $dir n'a pas pu être supprimé.");
        }
    }

    public function beforeDelete($entity): void
    {
        $mediaFolderPath = $this->getMediaFolderPath();

        // Delete file
        $filePath = $mediaFolderPath . $entity->get('dir') . '/' . $entity->get('file') . '.' . $entity->get('ext');
        if (file_exists($filePath)) {
            $deleted = @unlink($filePath);
            if (!$deleted) {
                throw new Exception("Le fichier $filePath n'a pas pu être supprimé.");
            }
        }

        // Delete thumbs created for this file
        $thumbs = glob($mediaFolderPath . $entity->get('dir') . '/' . $entity->get('file') . '__*.jpg');
        foreach ($thumbs as $thumb) {
            $deleted = unlink($thumb);
            if (!$deleted) {
                throw new Exception("Le fichier $thumb n'a pas pu être supprimé.");
            }
        }
    }
}
