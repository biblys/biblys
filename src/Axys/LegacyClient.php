<?php

namespace Axys;

class LegacyClient
{
    private $options;
    private $base_url;

    /**
     * @var string
     */
    private $userToken;

    /**
     * @var int
     */
    private $version;

    public function __construct(array $options = [], string $userToken = null)
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

        $this->userToken = $userToken;
    }

    public function getLoginUrl(): string
    {
        if ($this->version === 2) {
            return "/openid/axys";
        }

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

    public function getWidgetUrl(): string
    {
        $url = $this->base_url."/widget.php?version={$this->version}";

        if ($this->userToken) {
            $url .= '&UID='.$this->userToken;
        }

        return $url;
    }

    public function getVersion(): int
    {
        return $this->version;
    }
}
