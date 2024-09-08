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

    $currentDirectory = $request->query->getAlnum("dir");
    if ($currentDirectory === '/') {
        $currentDirectory = null;
    }

    $postNewDir = $request->request->getAlnum("new_dir");
    if (!empty($postNewDir)) {
        return _createMediaDirectory($postNewDir, $flashMessagesService);
    }

    /** @var UploadedFile[] $uploadedFiles */
    $uploadedFiles = $request->files->get("uploads", []);
    if (count($uploadedFiles) > 0) {
        return _uploadMediaFiles($uploadedFiles, $currentDirectory, $currentSite, $flashMessagesService);
    }

    // Single file
    $currentFile = $request->query->get("file");
    if ($currentDirectory && $currentFile) {

        $getFileArray = explode('.', $currentFile);
        $fileName = $getFileArray[0];
        $fileExt = $getFileArray[1];

        $mm = new MediaFileManager();
        $file = $mm->get(['media_dir' => $currentDirectory, 'media_file' => $fileName, 'media_ext' => $fileExt]);
        if (!$file) {
            throw new NotFoundHttpException("File $currentFile not found in directory $currentDirectory.");
        }

        if ($request->isMethod("POST")) {
            return _updateMediaFileInfo($request, $flashMessagesService, $currentFile, $currentDirectory);
        }

        $delete = $request->query->getBoolean("del");
        if ($delete) {
            return _deleteMediaFile($mm, $file, $flashMessagesService, $currentFile, $currentDirectory);
        }

        return _displayMediaFile($currentDirectory, $currentSite, $fileName, $fileExt, $currentFile, $request);
    }

    // Single directory
    if ($currentDirectory) {
        $delete = $request->query->getBoolean("del");
        if ($delete) {
            return _deleteMediaDirectory($currentDirectory, $flashMessagesService);
        }

        return _displayMediaDirectory($currentSite, $currentDirectory, $request);
    }

    return _displayMediaDirectories($currentSite, $request);
};

/**
 * @throws Exception
 */
function _createMediaDirectory(string $postNewDir, FlashMessagesService $flashMessagesService): RedirectResponse
{
    $slugService = new SlugService();
    $mm = new MediaFileManager();
    $mediaFolderPath = $mm->getMediaFolderPath();

    $newDirSlug = $slugService->slugify($postNewDir);
    mkdir($mediaFolderPath . $newDirSlug);
    $flashMessagesService->add("success", "Le dossier « $postNewDir » a été créé.");
    return new RedirectResponse("/pages/adm_media?dir=$newDirSlug");
}

/**
 * @throws PropelException
 * @throws Exception
 */
function _uploadMediaFiles(array $uploadedFiles, float|InputBag|bool|int|string|null $getDir, CurrentSite $currentSite, FlashMessagesService $flashMessagesService): RedirectResponse
{
    $mm = new MediaFileManager();
    $mediaFolderPath = $mm->getMediaFolderPath();
    $slugService = new SlugService();

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

function _updateMediaFileInfo(Request $request, FlashMessagesService $flashMessagesService, string $getFile, string $getDir): RedirectResponse
{
    $CKEditorFuncNum = $request->query->getAlnum("CKEditorFuncNum");

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
function _displayMediaFile(string $getDir, CurrentSite $currentSite, string $fileName, string $fileExt, string $getFile, Request $request): Response
{
    $CKEditorFuncNum = $request->query->getAlnum("CKEditorFuncNum");
    $request->attributes->set("page_title", "Médias");
    $content = '
       
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
        <h1><span class="fa fa-image"></span> Médias</h1> 
        
        <ol class="breadcrumb">
          <li>
            <span class="fa fa-folder-o"></span> 
            <a href="/pages/adm_media?CKEditorFuncNum=' . ($CKEditorFuncNum ?? null) . '">media</a>
          </li>
          <li>
            <span class="fa fa-folder-open-o"></span>
            <a href="/pages/adm_media?dir=' . $getDir . '&CKEditorFuncNum=' . $CKEditorFuncNum . '">' . $getDir . '</a>
          </li>
          <li class="active">
            <span class="fa fa-file-o"></span> ' . $getFile . '
          </li>
        </ol>       
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
            
            <br />
            
            <div class="text-center">
                <a 
                    class="btn btn-danger"
                    href="/pages/adm_media?dir=' . $getDir . '&file=' . $getFile . '&del=1&CKEditorFuncNum=' . $CKEditorFuncNum . '" 
                    data-confirm="Voulez-vous vraiment supprimer le fichier ' . $m['media_file'] . '.' . $m['media_ext'] . ' ?"
                >
                    Supprimer le fichier &laquo; '.$getFile.' &raquo;
                </a>
            </div>
        ';

    return new Response($content);
}

/**
 * @throws Exception
 */
function _deleteMediaDirectory(string $getDir, FlashMessagesService $flashMessagesService): RedirectResponse
{
    $mm = new MediaFileManager();
    $mm->deleteDirectory($getDir);
    $flashMessagesService->add("success", "Le dossier « $getDir » a été supprimé.");
    return new RedirectResponse("/pages/adm_media");
}

function _displayMediaDirectory(CurrentSite $currentSite, string $currentDirectory, Request $request): Response
{
    $CKEditorFuncNum = $request->query->getAlnum("CKEditorFuncNum");

    $request->attributes->set("page_title", "Médias");
    $content = '
        <h1><span class="fa fa-image"></span> Médias</h1>
       
        <ol class="breadcrumb">
          <li>
            <span class="fa fa-folder-o"></span> 
            <a href="/pages/adm_media?CKEditorFuncNum=' . ($CKEditorFuncNum ?? null) . '">media</a>
          </li>
          <li class="active">
            <span class="fa fa-folder-open-o"></span> ' . $currentDirectory . '
          </li>
        </ol>
        
        <table class="table table-striped">
        <tr>
          <th aria-hidden="true"></th>
          <th>Nom</th>
          <th>Taille</th>
        </tr>
    ';

    /** @var \Model\MediaFile[] $mediaFiles */
    $mediaFiles = MediaFileQuery::create()
        ->filterBySiteId($currentSite->getId())
        ->filterByDir($currentDirectory)
        ->find();

    foreach ($mediaFiles as $file) {
        if (!str_contains($file, '__')) {
            $fullName = $file->getFile() . '.' . $file->getExt();
            $content .= '<tr>
              <td aria-hidden="true" class="min-cell"><span class="fa fa-file"></span></td>
              <td>
                  <a href="/pages/adm_media?dir=' . $currentDirectory . '&file=' . $fullName . '&CKEditorFuncNum=' . $CKEditorFuncNum . '">
                    ' .$fullName . '
                  </a>
              </td>
              <td class="min-cell">
                  '._convertToMegabytes($file->getFileSize()).' Mo
              </td>
            </tr>';
        }
    }

    $content .= '</table>
                
            <form enctype="multipart/form-data" method="post" class="fieldset">
              <fieldset>
                  <label for="file-upload">
                    Ajouter un fichier au dossier &laquo; ' . $currentDirectory . ' &raquo; :<br />
                  </label>
                  <input id="file-upload" type="file" name="uploads[]" multiple="multiple" />
                  <button type="submit" class="btn btn-primary">Envoyer</button>
              </fieldset>
            </form>
            <br />
            
            <div class="text-center">
                <a 
                    class="btn btn-danger"
                    href="/pages/adm_media?dir=' . $currentDirectory . '&del=1&CKEditorFuncNum=' . $CKEditorFuncNum . '" 
                    data-confirm="Voulez-vous vraiment supprimer le dossier ' . $currentDirectory . ' et tous les fichiers qu\'il contient ?"
                >
                    Supprimer le dossier &laquo; '.$currentDirectory.' &raquo;
                </a>
            </div>
        
        ';

    return new Response($content);
}

/**
 * @throws PropelException
 */
function _displayMediaDirectories(CurrentSite $currentSite, Request $request): Response
{
    $CKEditorFuncNum = $request->query->getAlnum("CKEditorFuncNum");

    $mediaDirectories = MediaFileQuery::create()
        ->filterBySiteId($currentSite->getId())
        ->withColumn('media_dir', 'name')
        ->withColumn('SUM(`media_file_size`)', 'size')
        ->select(['name', 'size'])
        ->groupByDir()
        ->find();

    $request->attributes->set("page_title", "Médias");
    $content = '
        <h1><span class="fa fa-image"></span> Médias</h1>
        <ol class="breadcrumb">
          <li class="active">
            <span class="fa fa-folder-open-o"></span> media
          </li>
        </ol>

        <table class="table table-striped">
        <tr>
          <th aria-hidden="true"></th>
          <th>Dossier</th>
          <th>Taille</th>
        </tr>
    ';

    foreach ($mediaDirectories as $directory) {
        $content .= '<tr>
          <td class="min-cell"><span class="fa fa-folder"></span></td>
          <td> 
              <a href="/pages/adm_media?dir=' . $directory["name"] . '&CKEditorFuncNum=' . ($CKEditorFuncNum ?? null) . '">
                ' . $directory["name"] . '
              </a>
          </td>
          <td class="min-cell">
              '._convertToMegabytes($directory["size"]).' Mo
          </td>
        </tr>';
    }
    $content .= '
  
        </table>
        <br>
        
        <form class="form-inline" method="post">
        
          <div class="form-group">
            <label for="new-directory">Nouveau dossier :</label>
            <input id="new-directory" type="text" name="new_dir">
          </div>

          <button type="submit" class="btn btn-primary btn-sm">Créer</button>
        </form>
    </p>';

    $content .= '</ul>';

    return new Response($content);
}

function _convertToMegabytes(mixed $bytes): float
{
    return number_format($bytes / 1024 / 1024, 2);
}
