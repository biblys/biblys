<?php

namespace Biblys\Service\Images;

interface ImageInterface
{
    public function getFilePath(): string;
    public function getUrl(): string;
}