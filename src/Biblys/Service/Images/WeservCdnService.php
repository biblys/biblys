<?php

namespace Biblys\Service\Images;

class WeservCdnService
{
    public function buildUrl(string $localUrl): string
    {
        $weservOptions = ["url" => $localUrl];
        return "//images.weserv.nl/?".http_build_query($weservOptions);
    }
}