<?php

namespace Biblys\Template;

use Framework\Framework;

class Template
{
    private $_name;
    private $_type = 'Twig';
    private $_slug;
    private $_dirPath;
    private $_filename;

    /**
     * Static: return the template list as an array of templates.
     */
    public static function getAll()
    {
        global $site;

        $templates = [];

        $css = new Template();
        $css->setName('Styles CSS');
        $css->setSlug('css');
        $css->setType('CSS');
        $css->setFileName('styles.css');
        $templates[] = $css;

        $global = new Template();
        $global->setName('Modèle principal');
        $global->setSlug('layout');
        $global->setType('HTML');
        $global->setFileName('layout.html');
        $templates[] = $global;

        $articleShow = new Template();
        $articleShow->setName('Page d\'accueil');
        $articleShow->setSlug('home');
        $articleShow->setDirPath('Main');
        $articleShow->setFileName('home.html.twig');
        $templates[] = $articleShow;

        $articleShow = new Template();
        $articleShow->setName('Page d\'accueil (derniers billets)');
        $articleShow->setSlug('home-posts');
        $articleShow->setDirPath('Main');
        $articleShow->setFileName('home-posts.html.twig');
        $templates[] = $articleShow;

        $articleShow = new Template();
        $articleShow->setName('Page d\'accueil (derniers articles parus)');
        $articleShow->setSlug('home-articles');
        $articleShow->setDirPath('Main');
        $articleShow->setFileName('home-articles.html.twig');
        $templates[] = $articleShow;

        $template = new Template();
        $template->setName('Page d\'accueil (rayon)');
        $template->setSlug('home-rayon');
        $template->setDirPath('Main');
        $template->setFileName('home-rayon.html.twig');
        $templates[] = $template;

        $articleShow = new Template();
        $articleShow->setName('Fiche article');
        $articleShow->setSlug('article-show');
        $articleShow->setDirPath('Article');
        $articleShow->setFileName('show.html.twig');
        $templates[] = $articleShow;

        $articleList = new Template();
        $articleList->setName('Liste d\'articles');
        $articleList->setSlug('article-list');
        $articleList->setDirPath('Article');
        $articleList->setFileName('_list.html.twig');
        $templates[] = $articleList;

        $articleList = new Template();
        $articleList->setName('Formulaire de contact');
        $articleList->setSlug('contact');
        $articleList->setDirPath('Main');
        $articleList->setFileName('contact.html.twig');
        $templates[] = $articleList;

        return $templates;
    }

    /**
     * Static: return the template list as an array of templates.
     */
    public static function get($slug)
    {
        $templates = self::getAll();
        foreach ($templates as $template) {
            if ($template->getSlug('slug') == $slug) {
                return $template;
            }
        }
    }

    /**
     * Returns the custom (if it exists) or default template content.
     */
    public function getContent()
    {
        if ($this->customFileExists()) {
            $path = $this->getCustomDirPath();
        } else {
            if ($this->getSlug() === 'css') {
                return '';
            }
            $path = $this->getDefaultDirPath();
        }

        return file_get_contents($path.'/'.$this->getFileName());
    }

    /**
     * Update the custom template file content.
     */
    public function updateContent($content): void
    {
        global $site;

        if (!$this->customFileExists()) {
            $this->createCustomFile();
        }
        $path = $this->getCustomDirPath().'/'.$this->getFileName();
        file_put_contents($path, $content);

        // If css was modified, refresh theme and bump assets version
        if ($this->getSlug() === 'css') {
            Framework::runComposerCommand('theme:refresh');

            $assetsVersion = (int) $site->getOpt('assets_version') ?? '0';
            $site->setOpt('assets_version', $assetsVersion + 1);
        }
    }

    /**
     * Test if a custom version of the template exists.
     *
     * @return {boolean}
     */
    public function customFileExists()
    {
        $path = $this->getCustomDirPath().'/'.$this->getFileName();
        if (file_exists($path)) {
            return true;
        }

        return false;
    }

    /**
     * Create the custom file and directories.
     *
     * @return {boolean}
     */
    public function createCustomFile()
    {
        $path = $this->getCustomDirPath();
        if (!is_dir($path)) {
            mkdir($path, 0700, true);
        }

        $file = $path.'/'.$this->getFileName();
        $content = $this->getContent();
        file_put_contents($file, $content);

        return false;
    }

    /**
     * Returns the template custom file path.
     *
     * @return {string}
     */
    public function getCustomDirPath()
    {
        if ($this->getSlug() === 'css') {
            return BIBLYS_PATH.'/app/public/theme/';
        }
        if ($this->getSlug() === 'layout') {
            return BIBLYS_PATH.'/app/Resources/views/';
        }

        return BIBLYS_PATH.'/app/Resources/AppBundle/views/'.$this->getDirPath();
    }

    /**
     * Returns the template default file path.
     *
     * @return {string}
     */
    public function getDefaultDirPath()
    {
        if ($this->getSlug() === 'global') {
            return BIBLYS_PATH.'/src/AppBundle/Resources/views/';
        }

        return BIBLYS_PATH.'src/AppBundle/Resources/views/'.$this->getDirPath();
    }

    /**
     * Delete the custom template file (by renaming it).
     *
     * @return null
     */
    public function deleteCustomFile()
    {
        if ($this->customFileExists()) {
            $file = $this->getCustomDirPath().'/'.$this->getFileName();
            rename($file, $file.'.deleted');
        }
    }

    // Properties getters and setters

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setSlug($slug)
    {
        $this->_slug = $slug;
    }

    public function getSlug()
    {
        return $this->_slug;
    }

    public function setType($type)
    {
        $this->_type = $type;
    }

    public function getType()
    {
        return $this->_type;
    }

    public function setDirPath($dirPath)
    {
        $this->_dirPath = $dirPath;
    }

    public function getDirPath()
    {
        return $this->_dirPath;
    }

    public function setFileName($fileName)
    {
        $this->_fileName = $fileName;
    }

    public function getFileName()
    {
        return $this->_fileName;
    }

    public function getAceMode()
    {
        return strtolower($this->getType());
    }
}
