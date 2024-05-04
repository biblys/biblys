<?php

use Biblys\Exception\InvalidEntityException;
use Biblys\Service\CurrentUser;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class File extends Entity
{
    protected $prefix = 'file';

    /**
     * @throws Exception
     */
    public function canBeDownloadedBy(CurrentUser $currentUser): bool
    {
        // Public file
        if ($this->get('access') == 0) {
            return true;
        }

        // Private file

        // Visitor must be logged
        if (!$currentUser->isAuthentified()) {
            throw new AccessDeniedHttpException("Vous n'êtes pas identifié.");
        }

        // Article must be in user's library
        $sm = new StockManager();
        $right = $sm->get(['article_id' => $this->get('article_id'), 'axys_account_id' =>
            $currentUser->getAxysAccount()->getId()]);
        if (!$right) {
            throw new AccessDeniedHttpException("Le fichier est en accès restreint et l'article lié n'est pas dans votre bibliothèque.");
        }

        // Linked article must exist
        $am = new ArticleManager();
        /** @var Article $article */
        $article = $am->getById($this->get('article_id'));
        if (!$article) {
            throw new AccessDeniedHttpException("L'article lié à cet exemplaire n'existe pas.");
        }

        // Article must be published (or predownload allowed)
        if (!$article->isPublished() && !$right->get('allow_predownload')) {
            throw new Exception("L'article n'est pas encore disponible et le pré-téléchargement n'a pas été autorisé.");
        }

        // Everything looks good
        return true;
    }

    /**
     * Increment download count.
     * @throws Exception
     */
    public function addDownloadBy(CurrentUser $currentUser): void
    {
        global $request;

        $dm = new DownloadManager();

        $download = $dm->create();

        $download->set('file_id', $this->get('id'));
        $download->set('article_id', $this->get('article_id'));
        $download->set('download_filetype', $this->get('type'));
        $download->set('download_version', $this->get('version'));
        $download->set('download_ip', $request->getClientIp());

        if ($currentUser->isAuthentified()) {
            $download->set('axys_account_id', $currentUser->getAxysAccount()->getId());
        }

        $dm->update($download);
    }

    /* Get file dir and create it if needed */
    public function getDir(): string
    {
        global $config;
        $dir = $config->get('downloadable_path').str_pad(substr($this->get('file_id'), -2, 2), 2, '0', STR_PAD_LEFT).'/'.$this->get('file_id').'/';
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        return $dir;
    }

    /*
        Get human-readable name
        return String
    */
    public function getName(): string
    {
        $am = new ArticleManager();

        $url = 'file';
        $article = $am->getById($this->get('article_id'));
        if ($article) {
            $url = str_replace('/', '_', $article->get('url'));
        }

        return $url.$this->getType('ext');
    }

    /*
        Get file path
        return string
    */
    public function getPath(): string
    {
        return $this->getDir().$this->get('file_hash');
    }

    /*
        Get file type
        return String
    */
    public function getType($data): string
    {
        // Get current file mime-type
        if ($this->has('file_type')) {
            $type = $this->get('file_type');
        } else {
            $type = 'application/octet-stream';
        }

        // Alternate mime types
        switch ($type) {
            case 'application/ogg': $type = 'audio/ogg'; break;
            case 'audio/mpeg': $type = 'audio/mp3'; break;
        }

        // Get data according to type
        $types = [
            'application/octet-stream' => [
                'name' => 'Inconnu',
                'ext' => '',
                'icon' => '/common/icons/file.svg',
                'mime' => 'application/octet-stream',
            ],
            'application/pdf' => [
                'name' => 'PDF',
                'ext' => '.pdf',
                'icon' => '/common/icons/pdf_16.png',
                'mime' => 'application/pdf',
            ],
            'application/epub+zip' => [
                'name' => 'ePub',
                'ext' => '.epub',
                'icon' => '/common/icons/epub_16.png',
                'mime' => 'application/epub+zip',
            ],
            'application/x-mobipocket-ebook' => [
                'name' => 'Kindle',
                'ext' => '.mobi',
                'icon' => '/common/icons/azw_16.png',
                'mime' => 'application/x-mobipocket-ebook',
            ],
            'audio/mp3' => [
                'name' => 'MP3',
                'ext' => '.mp3',
                'icon' => '/common/icons/file_mp3.png',
                'mime' => 'audio/mp3',
            ],
            'audio/ogg' => [
                'name' => 'OGG',
                'ext' => '.ogg',
                'icon' => '/common/icons/file_ogg.png',
                'mime' => 'audio/ogg',
            ],
            'image/png' => [
                'name' => 'PNG',
                'ext' => '.png',
                'icon' => '/common/icons/file.svg',
                'mime' => 'image/png',
            ],
            'image/jpeg' => [
                'name' => 'JPG',
                'ext' => '.jpg',
                'icon' => '/common/icons/file.svg',
                'mime' => 'image/jpeg',
            ],
            'image/vnd.adobe.photoshop' => [
                'name' => 'Photoshop',
                'ext' => '.psd',
                'icon' => '/common/icons/file.svg',
                'mime' => 'image/vnd.adobe.photoshop',
            ],
            'application/zip' => [
                'name' => 'Zip',
                'ext' => '.zip',
                'icon' => '/common/icons/file.svg',
                'mime' => 'application/zip',
            ],
            'text/rtf' => [
                'name' => 'RTF',
                'ext' => '.rtf',
                'icon' => '/common/icons/file.svg',
                'mime' => 'text/rtf',
            ],
            'audio/flac' => [
                'name' => 'FLAC',
                'ext' => '.flac',
                'icon' => '/common/icons/file.svg',
                'mime' => 'audio/flac',
            ],
        ];

        if (!isset($types[$type])) {
            trigger_error('Unknown type: '.$type.' for file '.$this->get('file_id'));
        }

        return $types[$type][$data];
    }

    /**
     * @deprecated File->getUrl() is deprecated, use UrlGenerator service with "file_download" route
     * instead.
     * @throws InvalidEntityException
     */
    public function getUrl(): string
    {
        global $urlgenerator;

        trigger_deprecation(
            "biblys/biblys",
            "2.75.0",
            "File->getUrl() is deprecated, use UrlGenerator service with \"file_download\" route instead."
        );

        if ($this->getType("ext") === "") {
            throw new InvalidEntityException(
                "Cannot generate url for file {$this->get("id")} without extension."
            );
        }

        return $urlgenerator->generate('file_download', [
            'id' => $this->get('id'),
            'format' => ltrim($this->getType('ext'), '.'),
        ]);
    }

    /* Create table line */
    public function getLine(): string
    {
        $sel[0] = null;
        $sel[1] = null;
        $sel[$this->get('file_access')] = ' selected';

        return '
            <tr id="dlfile_'.$this->get('id').'" class="dlfile">
                <td class="file_title" title="'.$this->get('title').'" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" contenteditable>'.$this->get('title').'</td>
                <td class="center nopadding va-middle">
                    <select class="file_access">
                        <option'.$sel[0].' value="0">Public</option>
                        <option'.$sel[1].' value="1">Restreint</option>
                    </select>
                </td>
                <td class="file_version right" contenteditable>'.$this->get('version').'</td>
                <td class="file_ean center" contenteditable>'.($this->has('file_ean') ? $this->get('ean') : null).'</td>
                <td class="center"><img src='.$this->getType('icon').' width=16 alt="'.$this->getType('name').'" title="'.$this->getType('name').'"></td>
                <td class="right">'.file_size($this->get('file_size')).'</td>
                <td class="right">'.$this->getDownloads().'</td>
                <td class="center" style="width: 100px;">
                    <img src="/common/icons/save.svg" data-update_file='.$this->get('id').' class="pointer event" width=16 alt="Enregistrer" title="Enregistrer les modifications">
                    <input type="file" id="dlfile_upload_'.$this->get('id').'" data-file_id='.$this->get('id').' class="dlfile_upload event hidden">
                    <label for="dlfile_upload_'.$this->get('id').'" class="after">
                        <img src="/common/icons/upload.svg" class="pointer" width=16 alt="Upload" title="Mettre à jour le fichier">
                    </label>
                    <img src="/common/icons/delete.svg" data-delete_file='.$this->get('id').' class="pointer event" width=16 alt="Supprimer" title="Supprimer le fichier">
                </td>
            </tr>
        ';
    }

    /* Get download count */
    public function getDownloads(): int
    {
        global $_SQL;
        $downloads = $_SQL->query('SELECT `download_id` FROM `downloads` WHERE `file_id` = '.$this->get('file_id'));
        return count($downloads->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Set all related bought stock as updated.
     * @throws Exception
     */
    public function markAsUpdated(): void
    {
        $sm = new StockManager();

        $copies = $sm->getAll(['article_id' => $this->get('article_id')]);
        foreach ($copies as $copy) {
            if ($copy->isSold()) {
                $copy->set('stock_file_updated', 1);
                $sm->update($copy);
            }
        }
    }
}

class FileManager extends EntityManager
{
    protected $prefix = 'file';
    protected $table = 'files';
    protected $object = 'File';
}
