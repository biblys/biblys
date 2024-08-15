<?php

namespace Biblys\Service;

use Opengraph\Opengraph;
use Opengraph\Writer;

class MetaTagsService
{
    private Writer $writer;

    public function __construct(Writer $writer)
    {
        $this->writer = $writer;
    }

    public function setTitle(string $title): void
    {
        $this->writer->append(Opengraph::OG_TITLE, $title);
    }

    public function setImage(string $string): void
    {
        $this->writer->append(Opengraph::OG_IMAGE, $string);
    }

    public function dump(): string
    {
        return $this->writer->render();
    }

    public function disallowSeoIndexing(): void
    {
        $this->writer->append("robots", "noindex");
    }
}