<?php

/** @noinspection JSUnresolvedReference */

use Biblys\Service\CurrentSite;
use Biblys\Service\FlashMessagesService;
use Biblys\Service\Slug\SlugService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 */
return function (
    Request $request,
    CurrentSite $currentSite,
    FlashMessagesService $flashMessagesService,
): Response
{
    $slugService = new SlugService();

    $mm = new MediaFileManager();
    $mediaFolderPath = $mm->getMediaFolderPath();

    $request->attributes->set("page_title", "Gestion des médias");

    $content = '<h1><span class="fa fa-image"></span> Gestion des médias</h1>';

    if (!isset($_GET['dir']) || ($_GET['dir'] == '/') || (isset($_GET['path']) && strstr($_GET['path'], '..'))) {
        $_GET['dir'] = null;
    }

    // Nouveau dossier
    if (!empty($_POST['new_dir'])) {
        $newDirSlug = $slugService->slugify($_POST['new_dir']);
        mkdir($mediaFolderPath . $newDirSlug);
    }

    /** @var UploadedFile[] $uploadedFiles */
    $uploadedFiles = $request->files->get("uploads", []);
    if (count($uploadedFiles) > 0) {
        foreach ($uploadedFiles as $uploadedFile) {
            $rawFileName = explode(".", $uploadedFile->getClientOriginalName())[0];
            $fileName = $slugService->slugify($rawFileName);
            $fileExtension = $slugService->slugify($uploadedFile->getClientOriginalExtension());
            $targetDirectory = $_GET["dir"];
            $targetFilePath = "$mediaFolderPath$targetDirectory/$fileName.$fileExtension";

            copy($uploadedFile->getRealPath(), $targetFilePath);
            chmod($targetFilePath, 0604);

            $mediaFile = new \Model\MediaFile();
            $mediaFile->setSiteId($currentSite->getId());
            $mediaFile->setDir($_GET['dir']);
            $mediaFile->setFile($fileName);
            $mediaFile->setExt($fileExtension);
            $mediaFile->setFileSize($uploadedFile->getSize());
            $mediaFile->save();

            $flashMessagesService->add("success", "Le fichier « $fileName.$fileExtension » a été ajouté au dossier « $targetDirectory ».");
        }
    }

    $dir_nom = $mediaFolderPath . $_GET['dir']; // dossier liste (pour lister le répertoire courant : $dir_nom = '.'  --> ('point')
    $dir = opendir($dir_nom); // on ouvre le contenu du dossier courant
    if (!$dir) {
        throw new Exception('Erreur de listage : le répertoire ' . $dir_nom . ' n\'existe pas');
    }

    $fichier = []; // on déclare le tableau contenant le nom des fichiers
    $dossier = []; // on déclare le tableau contenant le nom des dossiers

    while ($element = readdir($dir)) {
        if ($element != '.' && $element != '..') {
            if (!is_dir($dir_nom . '/' . $element)) {
                $fichier[] = $element;
            } else {
                $dossier[] = $element;
            }
        }
    }

    closedir($dir);

    $content .= '
        <img src="/common/icons/directory_16x16.png" alt="Dossier" /> 
        <a href="/pages/adm_media?CKEditorFuncNum=' . ($_GET['CKEditorFuncNum'] ?? null) . '">media</a>
    ';

    $getFile = $request->query->get('file');
    $getDir = $request->query->get('dir');
    $getDel = $request->query->get('del');

    // Display a single file

    if ($getDir && $getFile) {

        $getFileArray = explode('.', $getFile);
        $fileName = $getFileArray[0];
        $fileExt = $getFileArray[1];

        $file = $mm->get(['media_dir' => $getDir, 'media_file' => $fileName, 'media_ext' => $fileExt]);
        if (!$file) {
            throw new Exception("File $getFile not found in directory $getDir.");
        }

        if (!empty($_POST)) {
            EntityManager::prepareAndExecute(
                'UPDATE `medias` SET `category_id` = :category_id, `media_title` = :media_title, `media_desc` = :media_desc, `media_link` = :media_link, `media_headline` = :media_headline WHERE `media_id` = :media_id LIMIT 1',
                [
                    'category_id' => $request->request->get('category_id'),
                    'media_title' => $request->request->get('media_title'),
                    'media_desc' => $request->request->get('media_desc'),
                    'media_link' => $request->request->get('media_link'),
                    'media_headline' => $request->request->get('media_headline'),
                    'media_id' => $request->request->get('media_id'),
                ]
            );
        }

        $media_dir = $getDir;
        $media_file = $fileName;
        $media_ext = $fileExt;

        $content .= '
            &raquo;
            <img src="/common/icons/directory_16x16.png" alt="" role="presentation" /> 
            <a href="/pages/adm_media?dir=' . $_GET['dir'] . '&CKEditorFuncNum=' . $_GET['CKEditorFuncNum'] . '">' . $_GET['dir'] . '</a>
        ';

        if ($getDel) {
            $mm->delete($file);
            $content .= '<br /><p>Le fichier <strong>' . $_GET['file'] . '</strong> a été supprimé.</p>';
        } else {
            $media = EntityManager::prepareAndExecute(
                'SELECT * FROM `medias` WHERE `site_id` = :site_id AND `media_dir` = :media_dir AND `media_file` = :media_file AND `media_ext` = :media_ext LIMIT 1',
                [
                    "site_id" => $GLOBALS["LEGACY_CURRENT_SITE"]->get("id"),
                    "media_dir" => $media_dir,
                    "media_file" => $media_file,
                    "media_ext" => $media_ext,
                ],
            );
            if ($m = $media->fetch(PDO::FETCH_ASSOC)) {
                $categories_options = null;
                $categories = EntityManager::prepareAndExecute(
                    'SELECT `category_id`, `category_name` FROM `categories` WHERE `site_id` = :site_id',
                    ['site_id' => $GLOBALS["LEGACY_CURRENT_SITE"]->get('id')]
                );
                while ($c = $categories->fetch(PDO::FETCH_ASSOC)) {
                    $categories_options .= '<option value="' . $c['category_id'] . '" ' . ($m['category_id'] == $c['category_id'] ? 'selected' : null) . '>' . $c['category_name'] . '</option>';
                }

                $content .= '
                    &raquo;
                    <img src="/common/icons/file_16x16.png" alt="Fichier" /> 
                    <a href="/pages/adm_media?dir=' . $_GET['dir'] . '&file=' . $_GET['file'] . '&CKEditorFuncNum=' . $_GET['CKEditorFuncNum'] . '">
                        ' . $_GET['file'] . '
                    </a> 
                    <a 
                        href="/pages/adm_media?dir=' . $_GET['dir'] . '&file=' . $_GET['file'] . '&del=1&CKEditorFuncNum=' . $_GET['CKEditorFuncNum'] . '" 
                        data-confirm="Voulez-vous vraiment supprimer le fichier ' . $m['media_file'] . '.' . $m['media_ext'] . ' ?"
                    >
                        <span class="fa fa-trash-o"></span>
                    </a>
                ';
                $content .= '<div class="center"><img src="' . $request->getScheme() . '://' . $_SERVER['HTTP_HOST'] . '/media/' . $m['media_dir'] . '/' . $m['media_file'] . '.' . $m['media_ext'] . '" style="max-width: 450px;" onClick="window.opener.CKEDITOR.tools.callFunction(\'' . $_GET['CKEditorFuncNum'] . '\',\'' . $request->getScheme() . '://' . $_SERVER['HTTP_HOST'] . '/media/' . $m['media_dir'] . '/' . $m['media_file'] . '.' . $m['media_ext'] . '\'); window.close();" title="Cliquer sur l\'image pour l\'insérer." class="pointer"  alt="Cliquer sur l\'image pour l\'insérer"/></div>';
                $content .= '<br />';
                $content .= '
                    <form method="post">
                        <fieldset>
                            <label for="media_id" class="disabled">Media n&deg;</label>
                            <input type="text" name="media_id" id="media_id" value="' . $m['media_id'] . '" class="short" readonly="readonly" /><br />
    
                            <label for="media_url">URL :</label>
                            <input type="text" name="media_url" id="media_url" class="long" value="' . $request->getScheme() . '://' . $_SERVER['HTTP_HOST'] . '/media/' . $m['media_dir'] . '/' . $m['media_file'] . '.' . $m['media_ext'] . '" /><br />
                            <br />
    
                            <label for="media_title">Titre :</label>
                            <input type="text" name="media_title" id="media_title" value="' . $m['media_title'] . '" class="long" /><br />
    
                            <label for="category_id">Catégorie :</label>
                            <select name="category_id">
                                <option value="0" />
                                ' . $categories_options . '
                            </select>
                            <br /><br />
    
    
                            <label for="media_link">Lien :</label>
                            <input type="url" placeholder="' . $request->getScheme() . '://" name="media_link" id="media_link" value="' . $m['media_link'] . '" class="long" /><br />
    
                            <label for="media_headline">À la une :</label>
                            <input type="checkbox" name="media_headline" value="1"' . ($m['media_headline'] ? ' checked' : null) . ' /><br />
                            <br />
    
                            <textarea id="media_description" name="media_desc" class="wysiwyg">' . $m['media_desc'] . '</textarea><br />
    
                            <div class="center">
                                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                            </div>
    
                        </fieldset>
                    </form>
                ';
            } else {
                throw new Exception("Pas d'entrée en base pour ce fichier ce fichier.");
            }
        }
    } elseif ($getDir) {// Dans un dossier, on liste les fichiers

        if ($getDel) {
            $mm->deleteDirectory($getDir);
            $content .= '<br /><p>Le dossier <strong>' . $getDir . '</strong> a été supprimé.</p>';
        } else {

            $content .= '
                &raquo;
                <img src="/common/icons/directory_16x16.png" alt="" role="presentation" /> 
                <a href="/pages/adm_media?dir=' . $_GET['dir'] . '&CKEditorFuncNum=' . $_GET['CKEditorFuncNum'] . '">
                    ' . $_GET['dir'] . '
                </a>
                <a 
                    href="/pages/adm_media?dir=' . $_GET['dir'] . '&del=1&CKEditorFuncNum=' . $_GET['CKEditorFuncNum'] . '" 
                    data-confirm="Voulez-vous vraiment supprimer le dossier ' . $_GET['dir'] . ' et tous les fichiers qu\'il contient ?"
                >
                    <span class="fa fa-trash-o"></span>
                </a>';

            sort($fichier);
            foreach ($fichier as $lien) {
                if (!str_contains($lien, '__')) {
                    $content .= '<li><img src="/common/icons/file_16x16.png" alt="Dossier" /> <a href="/pages/adm_media?dir=' . $_GET['dir'] . '&file=' . $lien . '&CKEditorFuncNum=' . $_GET['CKEditorFuncNum'] . '">' . $lien . '</a></li>';
                }
            }
            $content .= '<ul></li>';

            $content .= '
                            </li>
                        </ul>
                    </li>
                </ul>
            ';

            // Ajouter un fichier
            $content .= '
                <br />
                <p>
                    <form enctype="multipart/form-data" method="post">Ajouter un fichier au dossier &laquo; ' . $_GET['dir'] . ' &raquo; :<br />
                    <input type="file" name="uploads[]" class="autosubmit" multiple="multiple" /></form>
                </p>
            ';
        }
    } else {
        // À la racine, on liste les dossiers
        sort($dossier);
        foreach ($dossier as $lien) {
            $content .= '<li><img src="/common/icons/directory_16x16.png" alt="Dossier" /> <a href="/pages/adm_media?dir=' . $lien . '&CKEditorFuncNum=' . ($_GET['CKEditorFuncNum'] ?? null) . '">' . $lien . '</a></li>';
        }
        $content .= '</ul>';
        $content .= '
            <br>
            <p>Choisir un dossier ci-dessus ou créer un nouveau dossier :</p>
            <form method="post">
                <input type="text" name="new_dir" placeholder="Nouveau dossier...">
                <button type="submit" class="btn btn-primary">Créer</button>
            </form>
        </p>';
    }

    $content .= '</ul>';

    return new Response($content);
};
