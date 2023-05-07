<?php

	use Symfony\Component\HttpFoundation\JsonResponse;
    use Biblys\Isbn\Isbn as Isbn;

    $r = array();

    $controller = function() {

        global $request, $_SQL, $_SITE;

        $fm = new FileManager();
        $am = new ArticleManager();

        if ($request->getMethod() == "POST") {

            $action = $request->request->get('action');

            // Associate a file from file manager
            if ($action == 'associate') {

                $file_path = $_SITE->get('path').$_POST['file'];

                if (!file_exists($file_path)) {
                    trigger_error("Le fichier $new_file n'existe pas !");
                }

                $article_id = $request->request->get('article_id');
                $article = $am->getById($article_id);
                if (!$article) {
                    trigger_error("L'article n° $article_id n'existe pas !");
                }
                $article_title = $article->get('title');

                $file_name = $request->request->get('name');
                $file = $fm->create();
                $fm->upload($file, $file_path, $file_name, $article->get('id'), getLegacyVisitor()['user_id']);

                // Return new table line
                $r['success'] = "Le fichier &laquo;&nbsp;$file_name&nbsp;&raquo; a bien été associé à l'article &laquo;&nbsp;$article_title&nbsp;&raquo;.";
                $r['new_line'] = $file->getLine();
                return new JsonResponse($r);
            }

            $file_id = $request->request->get('file_id');

            // If file exist
            if ($file_id && !strstr($file_id, 'new_')) {

                $file = $fm->getById($file_id);
                if (!$file) {
                    throw new Exception("Le fichier n'existe pas !");
                }

            } elseif (strstr($file_id, 'new_')) {
                $file = $fm->create();
            } else {
                throw new Exception("Le fichier n'existe pas !");
            }


            // Delete file
            if ($action == 'delete')
            {
                try
                {
                    $fm->delete($file);
                }
                catch (Exception $e)
                {
                    error($e);
                }

                $r['success'] = 'Le fichier &laquo;&nbsp;'.$file->get('file_title').'&nbsp;&raquo; a bien été supprimé.';
            }

            // Update file
            elseif ($action == 'update')
            {
                try
                {
                    $ean = $request->request->get('file_ean', false);

                    // EAN check
                    if ($ean) {
                        $file->set('file_ean', Isbn::convertToEan13($ean));
                    }

                    $file->set('file_title', $_POST['file_title']);
                    $file->set('file_access', $_POST['file_access']);
                    $file->set('file_version', $_POST['file_version']);
                    $fm->update($file);
                }
                catch (Exception $e)
                {
                    json_error(0, $e->getMessage());
                }

                $r['success'] = 'Le fichier &laquo;&nbsp;'.$file->get('file_title').'&nbsp;&raquo; a bien été mis à jour.';

            }

            // Upload a file from computer
            elseif ($action == 'upload' && isset($_FILES['file']))
            {

                $f = $_FILES['file'];

                // Copy file into the files directory
                try
                {
                    $fm->upload($file, $f['tmp_name'], $f['name'], $_POST['article_id'], getLegacyVisitor()['user_id']);
                    $file->markAsUpdated();
                }
                catch (Exception $e)
                {
                    error($e);
                }

                $file->markAsUpdated();

                // Return new table line
                $r['success'] = 'Le fichier &laquo;&nbsp;'.$f['name'].'&nbsp;&raquo; a bien été ajouté.';
                $r['new_line'] = $file->getLine();
            }
        }
        return new JsonResponse($r);
    };


    $response = $controller();
    $response->send();
