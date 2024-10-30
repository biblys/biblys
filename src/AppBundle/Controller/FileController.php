<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentUser;
use Exception;
use File;
use FileManager;
use Framework\Controller;
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

        /** @var File $file */
        $file = $fm->getById($id);
        if (!$file) {
            throw new NotFoundException("File $id not found.");
        }

        // Check download right
        try {
            $file->canBeDownloadedBy($currentUser);
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
        if ($file->get('access') == 1) {
            // Find related copy
            $copy = $sm->get([
                'article_id' => $file->get('article_id'),
                'user_id' => $currentUser->getUser()->getId(),
            ]);
            if (!$copy) {
                throw new Exception('Related copy not found');
            }
        }

        // Increment download count
        $file->addDownloadBy($currentUser);

        // Remove updated marker on copy if necessary
        if ($copy && $copy->get('file_updated')) {
            $copy->set('stock_file_updated', 0);
            $sm->update($copy);
        }

        $filesystem = new FileSystem();
        if (!$filesystem->exists($file->getPath())) {
            throw new NotFoundException("No content found for file $id.");
        }

        $fileContent = file_get_contents($file->getPath());

        $response = new Response();

        // Force download only if non-public file
        if ($file->get('access') == 1) {
            $response->headers->set('Content-Disposition', 'attachment; filename='.$file->getName());
        }

        $response->headers->set('Content-Type', $file->get('type'));
        $response->headers->set('Content-Transfer-Encoding', $file->get('type'));
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Cache-Control', 'must-revalidate, post-check=0, pre-check=0, public');
        $response->headers->set('Expires', '0');

        $response->setContent($fileContent);

        $response->send();
        die();
    }
}
