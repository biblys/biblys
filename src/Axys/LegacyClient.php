<?php

namespace Axys;

class LegacyClient
{
    private $options;
    private $base_url;

    /**
     * @var int
     */
    private $version;

    public function __construct(array $options = [])
    {
        $this->options = $options;

        $this->version = $options["version"] ?? 1;

        if (!isset($this->options['host'])) {
            $this->options['host'] = 'axys.me';
        }

        if (!isset($this->options['protocol'])) {
            $this->options['protocol'] = 'https';
        }

        if (!isset($this->options['port'])) {
            $this->options['port'] = 80;
        }

        $port = null;
        if ($this->options['port'] !== 80) {
            $port = ':'.$this->options['port'];
        }

        $this->base_url = $this->options['protocol'].'://'.$this->options['host'].$port;
    }

    public function getLoginUrl(): string
    {
        global $config;

        $protocol = 'http';
        $https = $config->get('https');
        if ($https) {
            $protocol = 'https';
        }

        $returnUrl = $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

        return $this->base_url.'/login/?return_url='.$returnUrl;
    }

    public function getSignupUrl(): string
    {
        return $this->base_url.'/#Inscription';
    }

    public function getWidgetUrl($user_uid = null): string
    {
        $url = $this->base_url.'/widget.php';

        if ($user_uid) {
            $url .= '?UID='.$user_uid;
        }

        return $url;
    }

    public function getVersion(): int
    {
        return $this->version;
    }
}
