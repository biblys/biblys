<?php

namespace Framework;

use Biblys\Service\CurrentSite;
use Exception;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Error\LoaderError;
use Twig\Loader\LoaderInterface;
use Twig\Source;

class TemplateLoader implements LoaderInterface
{
    /**
     * @var CurrentSite
     */
    private $currentSite;

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(CurrentSite $currentSite, Filesystem $filesystem)
    {
        $this->currentSite = $currentSite;
        $this->filesystem = $filesystem;
    }

    /**
     * Returns the source context for a given template logical name.
     *
     * @param string $name The template logical name
     *
     * @throws LoaderError When $name is not found
     * @throws Exception
     */
    public function getSourceContext(string $name): Source
    {
        $path = $this->findTemplate($name);
        $code = file_get_contents($path);

        return new Source($code, $name, $path);
    }

    /**
     * Gets the cache key to use for the cache for a given template name.
     *
     * @param string $name string The name of the template to load
     *
     * @return string The cache key
     * @throws Exception
     */
    public function getCacheKey(string $name): string
    {
        return $this->findTemplate($name);
    }

    /**
     * Returns true if the template is still fresh.
     *
     * @param string $name The template name
     * @param int $time The last modification time of the cached template
     * @return bool
     * @throws Exception
     */
    public function isFresh(string $name, int $time): bool
    {
        return filemtime($this->findTemplate($name)) <= $time;
    }

    /**
     * Check if we have the source code of a template, given its name.
     *
     * @param string $name The name of the template to check if we can load
     *
     * @return bool If the template source code is handled by this loader or not
     */
    public function exists(string $name): bool
    {
        return true;
    }

    /**
     * @throws Exception
     */
    private function findTemplate($name): string
    {
        $path = explode(':', $name);

        // Legacy deprecated templates
        if (!isset($path[1])) {
            $siteName = $this->currentSite->getSite()->getName();
            $site_file = biblysPath().'/public/'. $siteName .'/templates/'.$name.'.html.twig';
            if (file_exists($site_file)) {
                trigger_error('Using deprecated legacy template '.$site_file, E_USER_DEPRECATED);

                return $site_file;
            }

            $common_file = biblysPath().'/public/common/templates/'.$name.'.html.twig';
            if (file_exists($common_file)) {
                trigger_error('Using deprecated legacy template '.$common_file, E_USER_DEPRECATED);

                return $common_file;
            }

            throw new Exception("Cannot find a legacy template named $name.");
        }

        // Twig layout templates
        if ($path[0] === "layout") {
            if (
                $path[1] === "base.html.twig" &&
                $this->currentSite->getOption("use_legacy_layout_builder")
            ) {
                return __DIR__."/../AppBundle/Resources/layout/base_for_legacy_builder.html.twig";
            }

            return self::_getLayoutFilePath($path[1], $name);
        }

        return $this->_getViewTemplateFilePath($path, $name);
    }

    /**
     * @param $layoutFileName
     * @param $name
     * @return string
     * @throws Exception
     */
    private function _getLayoutFilePath($layoutFileName, $name): string
    {
        $customLayoutFilePath = __DIR__ . "/../../app/layout/".$layoutFileName;
        if ($this->filesystem->exists($customLayoutFilePath)) {
            return $customLayoutFilePath;
        }

        $defaultLayoutFilePath = __DIR__."/../AppBundle/Resources/layout/".$layoutFileName;
        if ($this->filesystem->exists($defaultLayoutFilePath)) {
            return $defaultLayoutFilePath;
        }

        throw new Exception("Cannot find a layout template named $name at custom path ($customLayoutFilePath) or default path ($defaultLayoutFilePath).");
    }

    /**
     * @param array $path
     * @param $name
     * @return string
     * @throws Exception
     */
    private function _getViewTemplateFilePath(array $path, $name): string
    {

        $customFile = __DIR__."/../../app/views/$path[1]/$path[2]";
        if ($this->currentSite->getOption("use_legacy_layout_builder")) {
            $customFile = __DIR__."/../../app/Resources/$path[0]/views/$path[1]/$path[2]";
        }

        if ($this->filesystem->exists($customFile)) {
            return $customFile;
        }

        $defaultFile = __DIR__."/../$path[0]/Resources/views/$path[1]/$path[2]";
        if ($this->filesystem->exists($defaultFile)) {
            return $defaultFile;
        }

        $oldCustomFile = SITE_PATH."/templates/$path[1]/$path[2]";
        if (file_exists($oldCustomFile)) {
            trigger_deprecation(
                "biblys/biblys",
                "2.57.0",
                "Using the /templates/ folder is deprecated. Please move template at $customFile"
            );
            return $oldCustomFile;
        }

        throw new Exception("Cannot find a template named $name at $customFile or $defaultFile.");
    }
}
