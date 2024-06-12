<?php

class Media
{
    private $_id;
    private $_type;
    private $_mime;
    private $_ext;
    private $_dir;
    private $_domain;
    private $_directoryPath;
    private $_path;
    private $_exists = false;
    private $_dimensions;

    public function __construct($type, $id)
    {
        $this->setDomain('media'); // domaine par défaut

        $this->setId($id);
        $this->setType($type);

        $this->update();
    }

    /**
     * Mettre à jour l'objet
     */
    public function update()
    {

        // Dossier
        $this->setDir(str_pad(substr($this->id(), -2, 2), 2, '0', STR_PAD_LEFT));

        // Path
        if ($this->domain() == 'media') {
            $this->setDirectoryPath(MEDIA_PATH.'/'.$this->type().'/'.$this->dir().'/');
            $this->setPath($this->directoryPath().$this->id().'.'.$this->ext());
        } elseif ($this->domain() == 'dl') {
            $this->setDirectoryPath(DL_PATH.'/'.$this->type().'/'.$this->dir().'/');
            $this->setPath($this->directoryPath().$this->id().'.'.$this->ext());
        }

        // Exists
        if (file_exists($this->path())) {
            $this->setExists(true);
        }
        // URL
        if ($this->exists()) {
            if ($this->domain() == 'media') {
                $this->setURL(MEDIA_URL.'/'.$this->type().'/'.$this->dir().'/'.$this->id().'.'.$this->ext());
            }
            if ($this->domain() == 'dl') {
                $this->setURL(DL_URL.'/'.$this->type().'/'.$this->dir().'/'.$this->id().'.'.$this->ext());
            }
        }
    }

    /**
     * Upload a new file
     */
    public function upload($file, $mime = null)
    {
        // If file already exists, delete it
        if ($this->exists()) {
            $this->delete();
        }

        // If directory does not already exists create it
        if (!is_dir($this->directoryPath())) {
            mkdir($this->directoryPath(), 0777, true);
        }

        // Copy file from temp upload path
        if (copy($file, $this->path())) {
            $this->update();
            return (bool) true;
        }

        return (bool) false;
    }

    public function getDimensions()
    {
        if (!isset($this->_dimensions)) {
            $size = getimagesize($this->path());
            $this->_dimensions = [
                'height' => $size[1],
                'width' => $size[0],
            ];
        }

        return $this->_dimensions;
    }

    public function getOrientation()
    {
        $dimensions = $this->getDimensions();
        $height = $dimensions['height'];
        $width = $dimensions['width'];
        $ratio = $width / $height;

        if ($ratio > 1) {
            return 'landscape';
        }

        return 'portrait';
    }

    /**
     * Fix image orientation based on exif
     *
     * @param string $file file path to image to fix
     *
     * @return null
     */
    public function fixImageOrientation($file)
    {

        // Skip if image is not jpeg
        $mimeType = mime_content_type($file);
        if ($mimeType !== 'image/jpeg') {
            return;
        }

        $image = imagecreatefromjpeg($file);

        $exif = exif_read_data($file);
        if (!empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
            case 3:
                $image = imagerotate($image, 180, 0);
                break;

            case 6:
                $image = imagerotate($image, -90, 0);
                break;

            case 8:
                $image = imagerotate($image, 90, 0);
                break;
            }
        }

        if ($image) {
            imagejpeg($image, $file);
            imagedestroy($image);
        }
    }

    /**
     * Supprimer le fichier (et les vignettes)
     */
    public function delete()
    {
        if ($this->exists()) {
            foreach (glob(substr($this->path(), 0, -4)."*") as $f) {
                unlink($f) or error("media delete error");
            }
        }
    }

    /* SETTERS */

    private function setId($id)
    {
        $this->_id = (int) $id;
    }

    private function setType($type)
    {

        //if ($type == "book") $type == "article";
        if ($type == "article") {
            $type = "book";
        }

        // Types autorisés
        $types = array('article','book','stock','people','extrait','event','post','publisher','pdf','epub','azw');

        // Extension correspondante
        if ($type == "extrait" || $type == "pdf") {
            $this->setExt('pdf');
            $this->setMime('application/pdf');
            if ($type == "pdf") {
                $this->setDomain('dl');
            }
        } elseif ($type == "epub") {
            $this->setExt('epub');
            $this->setMime('application/epub+zip');
            $this->setDomain('dl');
        } elseif ($type == "azw") {
            $this->setExt('azw');
            $this->setMime('application/octet-stream');
            $this->setDomain('dl');
        } elseif ($type == "publisher") {
            $this->setExt('png');
            $this->setMime('image/png');
        } else {
            $this->setExt('jpg');
            $this->setMime('image/jpeg');
        }

        if (in_array($type, $types)) {
            $this->_type = (string) $type;
        } else {
            error('Type '.$type.' inconnu');
        }
    }

    private function setExt($ext)
    {
        $this->_ext = (string) $ext;
    }

    private function setMime($mime)
    {
        $this->_mime = (string) $mime;
    }

    private function setDomain($domain)
    {
        $this->_domain = (string) $domain;
    }

    private function setDirectoryPath($directoryPath)
    {
        $this->_directoryPath = (string) $directoryPath;
    }

    private function setPath($path)
    {
        $this->_path = (string) $path;
    }

    private function setDir($dir)
    {
        $this->_dir = (string) $dir;
    }

    private function setURL($url)
    {
        $this->_url = (string) $url;
    }

    private function setExists($exists)
    {
        $this->_exists = (bool) $exists;
    }

    /* GETTERS */

    public function id()
    {
        return $this->_id;
    }

    public function type()
    {
        return $this->_type;
    }

    public function ext()
    {
        return $this->_ext;
    }

    public function mime()
    {
        return $this->_mime;
    }

    public function domain()
    {
        return $this->_domain;
    }

    public function directoryPath()
    {
        return $this->_directoryPath;
    }

    public function path()
    {
        return $this->_path;
    }

    public function dir()
    {
        return $this->_dir;
    }

    /**
     * Kept for backward compatibility, use getUrl instead
     */
    public function url($data = null)
    {
        return $this->getUrl(["size" => $data]);
    }

    public function getUrl(array $options = [])
    {
        global $config;

        $orientation = null;
        if (isset($options["size"])) {
            $orientation = substr($options["size"], 0, 1);
            $size = substr($options["size"], 1, 4);
            $size = ceil($size / 100) * 100;
            $options[$orientation] = $size;
            unset($options["size"]);
        }

        $baseUrl = '/'.$this->type().'/'.$this->dir().'/'.$this->id().'.'.$this->ext();

        $cloud = $config->get("cloud");
        if ($cloud && $cloud["cdn"]) {
            
            $cdnOptions = null;
            if ($options) {
                $cdnOptions = "?".http_build_query($options);
            }
            
            return "https://cdn.biblys.cloud$baseUrl$cdnOptions";
        }


        $imagesCdn = $config->get('images_cdn');
        if ($imagesCdn) {
            
            if ($imagesCdn['service'] === 'cloudimage') {
                $operation = 'cdn';
                $operationSize = 'n';
                if ($orientation) {
                    $operation = $orientation === 'w' ? 'width' : 'height';
                    $operationSize = $size;
                }

                $url = MEDIA_URL.$baseUrl;
                $cloudUrl = 'https://'.$imagesCdn['options']['token'].'.cloudimg.io/'.$operation.'/'.$operationSize.'/faf/'.$url;
                return $cloudUrl;
            }
            
            if ($imagesCdn['service'] === 'weserv') {
                $url = MEDIA_URL.$baseUrl;
                $weservOptions = ["url" => $url];
                if ($orientation) {
                    $weservOptions[$orientation] = $size;
                }

                $weservUrl = "//images.weserv.nl?".http_build_query($weservOptions);
                return $weservUrl;
            }
        }

        $transformer = null;
        if ($orientation) {
            $transformer = '-'.$orientation.$size;
        }

        return MEDIA_URL.'/'.$this->type().'/'.$this->dir().'/'.$this->id().'.'.$this->ext();

    }

    public function exists()
    {
        global $config;
        if ($config->get("environment") === "test") {
            return true;
        }

        return (bool) $this->_exists;
    }
}
