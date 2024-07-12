<?php

namespace Biblys\Service\Images;

use Biblys\Service\Config;
use Model\Image;
use RuntimeException;

class ImageForModel
{
    public function __construct(
        private readonly Config $config,
        private Image|null $model
    )
    {
    }

    public function setModel(Image $model): void
    {
        $this->model = $model;
    }

    public function getModel(): Image
    {
        if (!$this->exists()) {
            throw new RuntimeException("Image does not exist");
        }

        return $this->model;
    }

    public function exists(): bool
    {
        return $this->model !== null;
    }

    public function getFilePath(): string
    {
        $basePath = __DIR__ . "/../../../../".$this->config->getImagesPath();
        $path = "$basePath/{$this->getModel()->getFilepath()}/{$this->getModel()->getFilename()}";
        return $this->_removeDuplicateSlashes($path);
    }

    public function getUrl(?int $width, ?int $height): string
    {
        $imageModel = $this->getModel();

        $baseUrl = rtrim($this->config->get("images.base_url"), "/");
        $filePath = trim($imageModel->getFilepath(), "/");
        $fileName = trim($imageModel->getFilename(), "/");
        $url = "$baseUrl/$filePath/$fileName";
        $version = $imageModel->getVersion() > 1 ? "?v={$imageModel->getVersion()}" : "";
        $urlWithVersion = $url . $version;

        if ($this->config->get("images.cdn.service") === "weserv") {
            $cdnService = new WeservCdnService();
            return $cdnService->buildUrl(url: $urlWithVersion, width: $width, height: $height);
        }

        return $urlWithVersion;
    }

    private function _removeDuplicateSlashes(string $string): string|array
    {
        return str_replace("//", "/", $string);
    }
}