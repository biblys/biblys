<?php

namespace AppBundle\Controller;

use Framework\Controller;
use Framework\Exception\AuthException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;

class FileController extends Controller
{
    public function downloadAction($id, $format)
    {
        $fm = new \FileManager();
        $sm = new \StockManager();

        // Check if file exists
        $file = $fm->getById($id);
        if (!$file) {
            throw new NotFoundException("File $id not found.");
        }

        // Check download right
        try {
            $file->canBeDownloadedBy(\Biblys\Legacy\LegacyCodeHelper::getGlobalVisitor());
        } catch (AuthException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
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
                'axys_account_id' => \Biblys\Legacy\LegacyCodeHelper::getGlobalVisitor()->get('id'),
            ]);
            if (!$copy) {
                throw new \Exception('Related copy not found');
            }
        }

        // Increment download count
        $file->addDownloadBy(\Biblys\Legacy\LegacyCodeHelper::getGlobalVisitor());

        // Remove updated marker on copy if necessary
        if ($copy && $copy->get('file_updated')) {
            $copy->set('stock_file_updated', 0);
            $sm->update($copy);
        }

        $response = new Response();

        // Force download only if non-public file
        if ($file->get('access') == 1) {
            $response->headers->set('Content-Disposition', 'attachment; filename='.$file->getName());
        }

        $response->headers->set('Content-Type', $file->get('type'));
        $response->headers->set('Content-Transfer-Encoding', $file->get('type'));
        // $response->headers->set("Content-Length", $file->get('size')); <= causing problem on iOS Safari
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Cache-Control', 'must-revalidate, post-check=0, pre-check=0, public');
        $response->headers->set('Expires', '0');

        $response->setContent(file_get_contents($file->getPath()));

        $response->send();
        die();
    }
}
