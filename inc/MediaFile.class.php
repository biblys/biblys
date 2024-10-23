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


use Biblys\Legacy\LegacyCodeHelper;

class MediaFile extends Entity
{
    protected $prefix = 'media';
    public $trackChange = false;

    /**
     * @throws Exception
     */
    public function getUrl(): string
    {
        return '/media/' . $this->get('dir') . '/' . $this->get('file') . '.' . $this->get('ext');
    }
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
