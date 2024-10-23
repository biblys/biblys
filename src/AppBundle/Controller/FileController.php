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


namespace AppBundle\Controller;

use Biblys\Service\CurrentUser;
use Exception;
use File;
use FileManager;
use Framework\Controller;
use Model\FileQuery;
use StockManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;

class FileController extends Controller
{
    /**
     * @throws Exception
     */
    public function downloadAction(CurrentUser $currentUser, $id)
    {
        $fm = new FileManager();
        $sm = new StockManager();

        /** @var File $fileEntity */
        $fileEntity = $fm->getById($id);
        if (!$fileEntity) {
            throw new NotFoundException("File $id not found.");
        }

        $file = FileQuery::create()->findPk($fileEntity->get("id"));

        // Check download right
        try {
            $fileEntity->canBeDownloadedBy($currentUser);
        } catch (AccessDeniedHttpException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            return new Response(
                '
                    <h1>Téléchargement impossible</h1>
                    <p>
                        Une erreur est survenue pendant la tentative de 
                        téléchargement du fichier :<br />
                        '.$exception->getMessage().'
                    </p>
                '
            );
        }

        // If file access is restricted
        $copy = false;
        if ($fileEntity->get('access') == 1) {
            // Find related copy
            $copy = $sm->get([
                'article_id' => $fileEntity->get('article_id'),
                'user_id' => $currentUser->getUser()->getId(),
            ]);
            if (!$copy) {
                throw new Exception('Related copy not found');
            }
        }

        // Increment download count
        $fileEntity->addDownloadBy($currentUser);

        // Remove updated marker on copy if necessary
        if ($copy && $copy->get('file_updated')) {
            $copy->set('stock_file_updated', 0);
            $sm->update($copy);
        }

        $filesystem = new FileSystem();
        $filePath = $file->getFullPath();
        if (!$filesystem->exists($filePath)) {
            throw new NotFoundException("No content found for file $id.");
        }

        $fileContent = file_get_contents($filePath);

        $response = new Response();

        // Force download only if non-public file
        if ($fileEntity->get('access') == 1) {
            $response->headers->set('Content-Disposition', 'attachment; filename='.$fileEntity->getName());
        }

        $response->headers->set('Content-Type', $fileEntity->get('type'));
        $response->headers->set('Content-Transfer-Encoding', $fileEntity->get('type'));
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Cache-Control', 'must-revalidate, post-check=0, pre-check=0, public');
        $response->headers->set('Expires', '0');

        $response->setContent($fileContent);

        $response->send();
        die();
    }
}
