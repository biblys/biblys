<?php

namespace Biblys\Service\Images;

class WeservCdnService implements CdnService
{
    public function buildUrl(string $url, int $width = null, int $height = null): string
    {
        $weservOptions = ["url" => $url];

        if ($width !== null) {
            $weservOptions["w"] = $width;
        }

        if ($height !== null) {
            $weservOptions["h"] = $height;
        }

        return "//images.weserv.nl/?".http_build_query($weservOptions);
    }
}