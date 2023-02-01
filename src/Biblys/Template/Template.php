<?php

namespace Biblys\Template;

use Exception;
use Site;
use Symfony\Component\Filesystem\Filesystem;

class Template
{
    private string $_name;
    private string $_type = 'Twig';
    private string $_slug;
    private string $_dirPath;
    private string $_fileName;

    /**
     * Static: return the template list as an array of templates.
     * @return Template[]
     */
    public static function getAll(): array
    {
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
        $global->setFileName('base.html.twig');
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
     * @throws Exception
     */
    public static function get($slug): Template
    {
        $templates = self::getAll();
        foreach ($templates as $template) {
            if ($template->getSlug() === $slug) {
                return $template;
            }
        }

        throw new Exception(sprintf("No template found for slug %s", $slug));
    }

    /**
     * Returns the custom (if it exists) or default template content.
     */
    public function getContent(): bool|string
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
     * @throws Exception
     */
    public function updateContent(Site $site, string $content, Filesystem $filesystem): void
    {
        if (!$this->customFileExists()) {
            $this->createCustomFile();
        }
        $path = $this->getCustomDirPath()."/".$this->getFileName();
        $filesystem->dumpFile($path, $content);

        // If css was modified, refresh theme and bump assets version
        if ($this->getSlug() === 'css') {
            $this->_refreshCSSPublicFile($path, $site, $filesystem);
        }
    }

    /**
     * Test if a custom version of the template exists.
     *
     * @return bool
     */
    public function customFileExists(): bool
    {
        $path = $this->getCustomDirPath().'/'.$this->getFileName();
        if (file_exists($path)) {
            return true;
        }

        return false;
    }

    /**
     * Create the custom file and directories.
     */
    public function createCustomFile(): void
    {
        $path = $this->getCustomDirPath();
        if (!is_dir($path)) {
            mkdir($path, 0700, true);
        }

        $file = $path.'/'.$this->getFileName();
        $content = $this->getContent();
        file_put_contents($file, $content);
    }

    /**
     * Returns the template custom file path.
     *
     * @return string
     */
    public function getCustomDirPath(): string
    {
        $themeBasePath = __DIR__."/../../../app";

        if ($this->getSlug() === 'css') {
            return "$themeBasePath/public/theme";
        }

        if ($this->getSlug() === 'layout') {
            return "$themeBasePath/layout";
        }

        return "$themeBasePath/views/{$this->getDirPath()}";
    }

    /**
     * Returns the template default file path.
     */
    public function getDefaultDirPath(): string
    {
        $frameworkResourcesPath = __DIR__."/../../AppBundle/Resources";

        if ($this->getSlug() === "global") {
            return "$frameworkResourcesPath/views";
        }

        if ($this->getSlug() === "layout") {
            return "$frameworkResourcesPath/layout";
        }

        return "$frameworkResourcesPath/views/".$this->getDirPath();
    }

    /**
     * Delete the custom template file (by renaming it).
     */
    public function deleteCustomFile(): void
    {
        if ($this->customFileExists()) {
            $file = $this->getCustomDirPath().'/'.$this->getFileName();
            rename($file, $file.'.deleted');
        }
    }

    // Properties getters and setters

    public function setName($name): void
    {
        $this->_name = $name;
    }

    public function getName(): string
    {
        return $this->_name;
    }

    public function setSlug($slug): void
    {
        $this->_slug = $slug;
    }

    public function getSlug(): string
    {
        return $this->_slug;
    }

    public function setType(string $type): void
    {
        $this->_type = $type;
    }

    public function getType(): string
    {
        return $this->_type;
    }

    public function setDirPath($dirPath): void
    {
        $this->_dirPath = $dirPath;
    }

    public function getDirPath(): string
    {
        return $this->_dirPath;
    }

    public function setFileName($fileName): void
    {
        $this->_fileName = $fileName;
    }

    public function getFileName(): string
    {
        return $this->_fileName;
    }

    public function getAceMode(): string
    {
        return strtolower($this->getType());
    }

    /**
     * @param string $path
     * @param Site $site
     * @param Filesystem $filesystem
     * @return void
     */
    private function _refreshCSSPublicFile(string $path, Site $site, Filesystem $filesystem): void
    {
        $biblysPublicPath = __DIR__ . "/../../../public/theme/styles.css";
        $filesystem->copy($path, $biblysPublicPath);

        $assetsVersion = (int) $site->getOpt('assets_version') ?? '0';
        $site->setOpt('assets_version', $assetsVersion + 1);
    }
}
