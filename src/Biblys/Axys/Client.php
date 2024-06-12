<?php

namespace Biblys\Axys;

class Client
{
    private $options;
    private $base_url;

    public function __construct(array $options = [])
    {
        $this->options = $options;

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

    public function getLoginUrl()
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

    public function getSignupUrl()
    {
        return $this->base_url.'/#Inscription';
    }

    public function getWidgetUrl($user_uid = null)
    {
        $url = $this->base_url.'/widget.php';

        if ($user_uid) {
            $url .= '?UID='.$user_uid;
        }

        return $url;
    }
}
