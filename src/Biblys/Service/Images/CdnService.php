<?php

namespace Biblys\Service\Images;

interface CdnService
{
    public function buildUrl(string $url, int $width = null, int $height = null): string;
}