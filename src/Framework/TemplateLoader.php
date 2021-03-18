<?php

namespace Framework;

class TemplateLoader implements \Twig\Loader\LoaderInterface
{
    public function __construct($site)
    {
        $this->site = $site;
    }

    /**
     * Returns the source context for a given template logical name.
     *
     * @param string $name The template logical name
     *
     * @throws \Twig\Error\LoaderError When $name is not found
     */
    public function getSourceContext(string $name): \Twig\Source
    {
        $path = $this->findTemplate($name);
        $code = file_get_contents($path);

        return new \Twig\Source($code, $name, $path);
    }

    /**
     * Gets the cache key to use for the cache for a given template name.
     *
     * @param string $name string The name of the template to load
     *
     * @return string The cache key
     */
    public function getCacheKey(string $name): string
    {
        return $this->findTemplate($name);
    }

    /**
     * Returns true if the template is still fresh.
     *
     * @param string    $name The template name
     * @param timestamp $time The last modification time of the cached template
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
    public function exists($name)
    {
        return true;
    }

    private function getTemplatePath($name)
    {
    }

    private function findTemplate($name)
    {
        $path = explode(':', $name);

        // Legacy deprecated templates
        if (!isset($path[1])) {
            $site_file = BIBLYS_PATH.'/public/'.$this->site->get('name').'/templates/'.$name.'.html.twig';
            if (file_exists($site_file)) {
                trigger_error('Using deprecated legacy template '.$site_file, E_USER_DEPRECATED);

                return $site_file;
            }

            $common_file = BIBLYS_PATH.'/public/common/templates/'.$name.'.html.twig';
            if (file_exists($common_file)) {
                trigger_error('Using deprecated legacy template '.$common_file, E_USER_DEPRECATED);

                return $common_file;
            }

            throw new \Exception("Cannot find a legacy template named $name.");
        }

        // Custom file
        $custom_file = BIBLYS_PATH.'/app/Resources/'.$path[0].'/views/'.$path[1].'/'.$path[2];
        if (file_exists($custom_file)) {
            return $custom_file;
        }

        // Old custom file location
        $old_custom_file = SITE_PATH . '/templates/' . $path[1] . '/' . $path[2];
        if (file_exists($old_custom_file)) {
            return $old_custom_file;
        }

        // Default file
        $default_file = BIBLYS_PATH.'/src/'.$path[0].'/Resources/views/'.$path[1].'/'.$path[2];
        if (file_exists($default_file)) {
            return $default_file;
        }

        throw new \Exception("Cannot find a template named $name.");
    }
}
