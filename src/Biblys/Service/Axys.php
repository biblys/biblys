<?php

namespace Biblys\Service;

use Axys\AxysOpenIDConnectProvider;

class Axys
{
    /**
     * @var array
     */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config->get("axys");
    }

    public function getOpenIDConnectProvider(): AxysOpenIDConnectProvider
    {
        return new AxysOpenIDConnectProvider($this->config);
    }
}
