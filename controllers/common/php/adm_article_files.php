<?php /** @noinspection PhpComposerExtensionStubsInspection */

use Biblys\Isbn\Isbn as Isbn;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Model\FileQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

$r = array();

return function (Request $request, CurrentSite $currentSite, CurrentUser $currentUser): Response
{
    if (!$request->getMethod() == "POST") {
        throw new MethodNotAllowedHttpException(["POST"]);
    }

    $action = $request->request->get("action");
    $fileId = $request->request->get("file_id");

    $fm = new FileManager();
    if ($fileId && !str_contains($fileId, 'new_')) {

        /** @var File $fileEntity */
        $fileEntity = $fm->getById($fileId);
        if (!$fileEntity) {
            throw new Exception("Le fichier n'existe pas !");
        }

    } elseif (str_contains($fileId, 'new_')) {
        $fileEntity = $fm->create();
    } else {
        throw new Exception("Le fichier n'existe pas !");
    }

    /** @var \Model\File $file */
    $file = FileQuery::create()->findPk($fileEntity->get("id"));

    if ($action === "delete") {
        try {
            $file->delete();
        } catch (Exception $e) {
            error($e);
        }

        $r['success'] = 'Le fichier &laquo;&nbsp;'.$file->getTitle().'&nbsp;&raquo; a bien été supprimé.';
        return new JsonResponse($r);
    }

    // Update file
    if ($action == 'update') {
        try {
            $ean = $request->request->get('file_ean', false);
            if ($ean) {
                $file->setEan(Isbn::convertToEan13($ean));
            }

            $file->setTitle($request->request->get("file_title"));
            $file->setAccess($request->request->get("file_access"));
            $file->setVersion($request->request->get("file_version"));
            $file->save();
        } catch (Exception $e) {
            json_error(0, $e->getMessage());
        }

        $r['success'] = 'Le fichier &laquo;&nbsp;'.$file->getTitle().'&nbsp;&raquo; a bien été mis à jour.';
        return new JsonResponse($r);
    }

    if ($action === "upload" && isset($_FILES["file"])) {

        $uploadedFile = $_FILES['file'];

        $articleId = $request->request->get("article_id");

        $name = explode('.', $uploadedFile["name"]);
        $title = $name[0];
        $ext = $name[1];
        $size = filesize($uploadedFile["tmp_name"]);

        $type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $uploadedFile["tmp_name"]);
        if ($type == 'application/octet-stream' && $ext === 'mobi') {
            $type = 'application/x-mobipocket-ebook';
        }

        if (strlen($title) > 32) {
            throw new BadRequestHttpException("Le titre du fichier ne doit pas dépasser 32 caractères.");
        }

        $file->setArticleId($articleId);
        $file->setAxysAccountId($currentUser->getUser()->getId());
        $file->setTitle($title);
        $file->setType($type);
        $file->setHash(md5_file($uploadedFile["tmp_name"]));
        $file->setSize($size);
        $file->setUploaded(date('Y-m-d H:i:s'));

        $filePath = $fileEntity->getDir().$file->getHash();
        if (copy($uploadedFile["tmp_name"], $filePath)) {
            $file->save();
        } else {
            throw new Exception('Copy error');
        }

        /** @var File $fileEntity */
        $fileEntity = $fm->getById($file->getId());
        $fileEntity->markAsUpdated();
        $r['success'] = 'Le fichier &laquo;&nbsp;'.$uploadedFile['name'].'&nbsp;&raquo; a bien été ajouté.';
        $r['new_line'] = $fileEntity->getLine();

        return new JsonResponse($r);
    }

    throw new BadRequestHttpException("Unknown action");
};
