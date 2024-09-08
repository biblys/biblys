<?php

/** @noinspection JSUnresolvedReference */

use Biblys\Service\CurrentSite;
use Biblys\Service\FlashMessagesService;
use Biblys\Service\Slug\SlugService;
use Model\MediaFileQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return function (
    Request              $request,
    CurrentSite          $currentSite,
    FlashMessagesService $flashMessagesService,
): Response {
    $slugService = new SlugService();
    $mm = new MediaFileManager();
    $mediaFolderPath = $mm->getMediaFolderPath();

    $getDir = $request->query->getAlnum("dir");
    $getPath = $request->query->getAlnum("path");
    if (!isset($getDir) || ($getDir == '/') || (isset($getPath) && strstr($getPath, '..'))) {
        $getDir = null;
    }

    $getFile = $request->query->get("file");
    $getDel = $request->query->getBoolean("del");
    $CKEditorFuncNum = $request->query->getAlnum("CKEditorFuncNum");

    $postNewDir = $request->request->getAlnum("new_dir");
    if (!empty($postNewDir)) {
        return _createMediaDirectory($slugService, $postNewDir, $mediaFolderPath, $flashMessagesService);
    }

    /** @var UploadedFile[] $uploadedFiles */
    $uploadedFiles = $request->files->get("uploads", []);
    if (count($uploadedFiles) > 0) {
        return _uploadMediaFiles($uploadedFiles, $slugService, $getDir, $mediaFolderPath, $currentSite, $flashMessagesService);
    }

    $request->attributes->set("page_title", "Gestion des médias");
    $content = '
        <h1><span class="fa fa-image"></span> Gestion des médias</h1>
        <img src="/common/icons/directory_16x16.png" alt="Dossier" /> 
        <a href="/pages/adm_media?CKEditorFuncNum=' . ($CKEditorFuncNum ?? null) . '">media</a>
    ';

    // Single file
    if ($getDir && $getFile) {

        $getFileArray = explode('.', $getFile);
        $fileName = $getFileArray[0];
        $fileExt = $getFileArray[1];

        $file = $mm->get(['media_dir' => $getDir, 'media_file' => $fileName, 'media_ext' => $fileExt]);
        if (!$file) {
            throw new NotFoundHttpException("File $getFile not found in directory $getDir.");
        }

        if ($request->isMethod("POST")) {
            return _updateMediaFileInfo($request, $flashMessagesService, $getFile, $getDir, $CKEditorFuncNum);
        }

        if ($getDel) {
            return _deleteMediaFile($mm, $file, $flashMessagesService, $getFile, $getDir);
        }

        return _displayMediaFile($getDir, $CKEditorFuncNum, $content, $currentSite, $fileName, $fileExt, $getFile, $request);
    }

    // Single directory
    if ($getDir) {
        if ($getDel) {
            return _deleteMediaDirectory($mm, $getDir, $flashMessagesService);
        }

        return _displayMediaDirectory($currentSite, $getDir, $CKEditorFuncNum, $content);
    }

    return _displayMediaDirectories($currentSite, $CKEditorFuncNum, $content);
};

function _createMediaDirectory(SlugService $slugService, string $postNewDir, string $mediaFolderPath, FlashMessagesService $flashMessagesService): RedirectResponse
{
    $newDirSlug = $slugService->slugify($postNewDir);
    mkdir($mediaFolderPath . $newDirSlug);
    $flashMessagesService->add("success", "Le dossier « $postNewDir » a été créé.");
    return new RedirectResponse("/pages/adm_media?dir=$newDirSlug");
}

/**
 * @throws PropelException
 */
function _uploadMediaFiles(array $uploadedFiles, SlugService $slugService, float|InputBag|bool|int|string|null $getDir, string $mediaFolderPath, CurrentSite $currentSite, FlashMessagesService $flashMessagesService): RedirectResponse
{
    foreach ($uploadedFiles as $uploadedFile) {
        $rawFileName = explode(".", $uploadedFile->getClientOriginalName())[0];
        $fileName = $slugService->slugify($rawFileName);
        $fileExtension = $slugService->slugify($uploadedFile->getClientOriginalExtension());
        $targetDirectory = $getDir;
        $targetFilePath = "$mediaFolderPath$targetDirectory/$fileName.$fileExtension";

        copy($uploadedFile->getRealPath(), $targetFilePath);
        chmod($targetFilePath, 0604);

        $mediaFile = new \Model\MediaFile();
        $mediaFile->setSiteId($currentSite->getId());
        $mediaFile->setDir($getDir);
        $mediaFile->setFile($fileName);
        $mediaFile->setExt($fileExtension);
        $mediaFile->setFileSize($uploadedFile->getSize());
        $mediaFile->save();

        $flashMessagesService->add("success", "Le fichier « $fileName.$fileExtension » a été ajouté au dossier « $targetDirectory ».");
    }
    return new RedirectResponse("/pages/adm_media?dir=$getDir");
}

function _updateMediaFileInfo(Request $request, FlashMessagesService $flashMessagesService, string $getFile, string $getDir, string $CKEditorFuncNum): RedirectResponse
{
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
    $flashMessagesService->add("success", "Le fichier « $getFile » a été mis à jour.");
    return new RedirectResponse("/pages/adm_media?dir=$getDir&file=$getFile&CKEditorFuncNum=$CKEditorFuncNum");
}

/**
 * @throws Exception
 */
function _deleteMediaFile(MediaFileManager $mm, mixed $file, FlashMessagesService $flashMessagesService, string $getFile, string $getDir): RedirectResponse
{
    $mm->delete($file);
    $flashMessagesService->add("success", "Le fichier « $getFile » a été supprimé.");
    return new RedirectResponse("/pages/adm_media?dir=$getDir");
}

/**
 * @throws Exception
 */
function _displayMediaFile(string $getDir, string $CKEditorFuncNum, string $content, CurrentSite $currentSite, string $fileName, string $fileExt, string $getFile, Request $request): Response
{
    $content .= '
            &raquo;
            <img src="/common/icons/directory_16x16.png" alt="" role="presentation" /> 
            <a href="/pages/adm_media?dir=' . $getDir . '&CKEditorFuncNum=' . $CKEditorFuncNum . '">' . $getDir . '</a>
        ';

    $mediaFileQuery = EntityManager::prepareAndExecute(
        'SELECT * FROM `medias` WHERE `site_id` = :site_id AND `media_dir` = :media_dir AND `media_file` = :media_file AND `media_ext` = :media_ext LIMIT 1',
        [
            "site_id" => $currentSite->getId(),
            "media_dir" => $getDir,
            "media_file" => $fileName,
            "media_ext" => $fileExt,
        ],
    );

    $m = $mediaFileQuery->fetch(PDO::FETCH_ASSOC);
    if (!$m) {
        throw new Exception("Pas d'entrée en base pour ce fichier ce fichier.");
    }

    $categories_options = null;
    $categories = EntityManager::prepareAndExecute(
        'SELECT `category_id`, `category_name` FROM `categories` WHERE `site_id` = :site_id',
        ['site_id' => $currentSite->getId()]
    );
    while ($c = $categories->fetch(PDO::FETCH_ASSOC)) {
        $categories_options .= '<option value="' . $c['category_id'] . '" ' . ($m['category_id'] == $c['category_id'] ? 'selected' : null) . '>' . $c['category_name'] . '</option>';
    }

    $content .= '
            &raquo;
            <img src="/common/icons/file_16x16.png" alt="Fichier" /> 
            <a href="/pages/adm_media?dir=' . $getDir . '&file=' . $getFile . '&CKEditorFuncNum=' . $CKEditorFuncNum . '">
                ' . $getFile . '
            </a> 
            <a 
                href="/pages/adm_media?dir=' . $getDir . '&file=' . $getFile . '&del=1&CKEditorFuncNum=' . $CKEditorFuncNum . '" 
                data-confirm="Voulez-vous vraiment supprimer le fichier ' . $m['media_file'] . '.' . $m['media_ext'] . ' ?"
            >
                <span class="fa fa-trash-o"></span>
            </a>
        ';
    $content .= '<div class="center"><img src="' . $request->getScheme() . '://' . $request->getHttpHost() . '/media/' . $m['media_dir'] . '/' . $m['media_file'] . '.' . $m['media_ext'] . '" style="max-width: 450px;" onClick="window.opener.CKEDITOR.tools.callFunction(\'' . $CKEditorFuncNum . '\',\'' . $request->getScheme() . '://' . $request->getHttpHost() . '/media/' . $m['media_dir'] . '/' . $m['media_file'] . '.' . $m['media_ext'] . '\'); window.close();" title="Cliquer sur l\'image pour l\'insérer." class="pointer"  alt="Cliquer sur l\'image pour l\'insérer"/></div>';
    $content .= '<br />';
    $content .= '
            <form method="post">
                <fieldset>
                    <label for="media_id" class="disabled">Media n&deg;</label>
                    <input type="text" name="media_id" id="media_id" value="' . $m['media_id'] . '" class="short" readonly="readonly" /><br />

                    <label for="media_url">URL :</label>
                    <input type="text" name="media_url" id="media_url" class="long" value="' . $request->getScheme() . '://' . $request->getHttpHost() . '/media/' . $m['media_dir'] . '/' . $m['media_file'] . '.' . $m['media_ext'] . '" /><br />
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

    return new Response($content);
}

/**
 * @throws Exception
 */
function _deleteMediaDirectory(MediaFileManager $mm, string $getDir, FlashMessagesService $flashMessagesService): RedirectResponse
{
    $mm->deleteDirectory($getDir);
    $flashMessagesService->add("success", "Le dossier « $getDir » a été supprimé.");
    return new RedirectResponse("/pages/adm_media");
}

function _displayMediaDirectory(CurrentSite $currentSite, string $currentDirectory, string $CKEditorFuncNum, string $content): Response
{
    $content .= '
            &raquo;
            <img src="/common/icons/directory_16x16.png" alt="" role="presentation" /> 
            <a href="/pages/adm_media?dir=' . $currentDirectory . '&CKEditorFuncNum=' . $CKEditorFuncNum . '">
                ' . $currentDirectory . '
            </a>
            <a 
                href="/pages/adm_media?dir=' . $currentDirectory . '&del=1&CKEditorFuncNum=' . $CKEditorFuncNum . '" 
                data-confirm="Voulez-vous vraiment supprimer le dossier ' . $currentDirectory . ' et tous les fichiers qu\'il contient ?"
            >
                <span class="fa fa-trash-o"></span>
            </a>';

    /** @var \Model\MediaFile[] $mediaFiles */
    $mediaFiles = MediaFileQuery::create()
        ->filterBySiteId($currentSite->getId())
        ->filterByDir($currentDirectory)
        ->find();

    foreach ($mediaFiles as $file) {
        if (!str_contains($file, '__')) {
            $fullName = $file->getFile() . '.' . $file->getExt();
            $content .= '<li>
              <img src="/common/icons/file_16x16.png" alt="Dossier" /> 
              <a href="/pages/adm_media?dir=' . $currentDirectory . '&file=' . $fullName . '&CKEditorFuncNum=' . $CKEditorFuncNum . '">
                ' .$fullName . '
              </a>
            </li>';
        }
    }

    $content .= '<ul></li>
                        </li>
                    </ul>
                </li>
            </ul>
            <br />
            <p>
                <form enctype="multipart/form-data" method="post">Ajouter un fichier au dossier &laquo; ' . $currentDirectory . ' &raquo; :<br />
                <input type="file" name="uploads[]" class="autosubmit" multiple="multiple" /></form>
            </p>
        ';

    return new Response($content);
}

/**
 * @throws PropelException
 */
function _displayMediaDirectories(CurrentSite $currentSite, string|null $CKEditorFuncNum, string $content): Response
{
    $mediaDirectories = MediaFileQuery::create()
        ->select("Dir")
        ->filterBySiteId($currentSite->getId())
        ->groupByDir()
        ->find();

    foreach ($mediaDirectories as $directory) {
        $content .= '<li>
          <img src="/common/icons/directory_16x16.png" alt="Dossier" /> 
          <a href="/pages/adm_media?dir=' . $directory . '&CKEditorFuncNum=' . ($CKEditorFuncNum ?? null) . '">
            ' . $directory . '
          </a>
        </li>';
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

    $content .= '</ul>';

    return new Response($content);
}