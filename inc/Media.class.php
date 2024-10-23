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


use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\Config;

class Media
{
    private int $_id;
    private string $_type;
    private string $_mime;
    private string $_ext;
    private string $_dir;
    private string $_domain;
    private string $_directoryPath;
    private ?string $_path = null;
    private bool $_exists = false;
    private Config $config;

    /**
     * @deprecated Using Media class is deprecated. Use ImagesService instead.
     * @throws Exception
     */
    public function __construct($type, $id)
    {
        $this->config = Config::load();

        trigger_deprecation(
            "biblys",
            "3.0.0",
            "Using Media class is deprecated. Use ImagesService instead."
        );

        $this->setDomain('media'); // domaine par défaut

        $this->setId($id);
        $this->setType($type);

        $this->update();
    }

    /**
     * Mettre à jour l'objet
     */
    public function update(): void
    {
        // Dossier
        $this->setDir(str_pad(substr($this->id(), -2, 2), 2, '0', STR_PAD_LEFT));

        // Path
        if ($this->domain() == 'media') {
            $directoryPath = __DIR__ . "/../" . $this->config->getImagesPath() . '/' . $this->type() . '/' . $this->dir() . '/';
            $this->setDirectoryPath($directoryPath);
            $this->setPath($this->directoryPath() . $this->id() . '.' . $this->ext());
        }

        // Exists
        if (realpath($this->path()) !== false) {
            $this->setExists(true);
        }
    }

    /**
     * Supprimer le fichier (et les vignettes)
     */
    public function delete(): void
    {
        if ($this->exists()) {
            foreach (glob(substr($this->path(), 0, -4) . "*") as $f) {
                unlink($f) or error("media delete error");
            }
        }
    }

    /* SETTERS */

    private function setId($id): void
    {
        $this->_id = (int)$id;
    }

    /**
     * @throws Exception
     */
    private function setType($type): void
    {

        if ($type == "article") {
            $type = "book";
        }

        // Types autorisés
        $types = array('article', 'book', 'stock', 'people', 'extrait', 'event', 'post', 'publisher', 'pdf', 'epub', 'azw');

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
            $this->_type = (string)$type;
        } else {
            throw new Exception('Type ' . $type . ' inconnu');
        }
    }

    private function setExt($ext): void
    {
        $this->_ext = (string)$ext;
    }

    private function setMime($mime): void
    {
        $this->_mime = (string)$mime;
    }

    private function setDomain($domain): void
    {
        $this->_domain = (string)$domain;
    }

    private function setDirectoryPath($directoryPath): void
    {
        $this->_directoryPath = (string)$directoryPath;
    }

    private function setPath($path): void
    {
        $this->_path = (string)$path;
    }

    private function setDir($dir): void
    {
        $this->_dir = (string)$dir;
    }

    public function setExists(bool $exists): void
    {
        $this->_exists = $exists;
    }

    /* GETTERS */

    public function id(): int
    {
        return $this->_id;
    }

    public function type(): string
    {
        return $this->_type;
    }

    public function ext(): string
    {
        return $this->_ext;
    }

    public function mime(): string
    {
        return $this->_mime;
    }

    public function domain(): string
    {
        return $this->_domain;
    }

    public function directoryPath(): string
    {
        return $this->_directoryPath;
    }

    public function path(): ?string
    {
        return $this->_path;
    }

    public function dir(): string
    {
        return $this->_dir;
    }

    /**
     * @deprecated Using Media->url is deprecated. Use Media->getUrl instead.
     */
    public function url($data = null): string
    {
        trigger_deprecation(
            package: "biblys/biblys",
            version: "2.68.0",
            message: "Using Media->url is deprecated. Use Media->getUrl instead.",
        );
        return $this->getUrl(["size" => $data]);
    }

    public function getUrl(array $options = []): string
    {
        $config = LegacyCodeHelper::getGlobalConfig();

        $version = '';
        if (isset($options['version']) && $options['version'] > 1) {
            $version = '?v=' . $options['version'];
        }

        $orientation = null;
        if (isset($options["size"])) {
            $orientation = substr($options["size"], 0, 1);
            $size = substr($options["size"], 1, 4);
            $size = ceil($size / 100) * 100;
            $options[$orientation] = $size;
            unset($options["size"]);
        }

        $baseUrl = '/' . $this->type() .
            '/' . $this->dir() .
            '/' . $this->id() .
            '.' . $this->ext() .
            $version;

        $imagesCdn = $config->get('images_cdn');
        $mediaUrl = $config->getImagesBaseUrl();

        if ($imagesCdn) {
            if ($imagesCdn['service'] === 'cloudimage') {
                $operation = 'cdn';
                $operationSize = 'n';
                if ($orientation && isset($size)) {
                    $operation = $orientation === 'w' ? 'width' : 'height';
                    $operationSize = $size;
                }

                $url = $mediaUrl . $baseUrl;
                return 'https://' . $imagesCdn['options']['token'] . '.cloudimg.io/' . $operation . '/' . $operationSize . '/faf/' . $url;
            }

            if ($imagesCdn['service'] === 'weserv') {
                $url = $mediaUrl . $baseUrl;
                $weservOptions = ["url" => $url];

                if ($orientation && isset($size)) {
                    $weservOptions[$orientation] = $size;
                }

                return "//images.weserv.nl?" . http_build_query($weservOptions);
            }
        }

        return $mediaUrl . $baseUrl;
    }

    public function exists(): bool
    {
        return $this->_exists;
    }
}
