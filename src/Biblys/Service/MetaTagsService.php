<?php
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


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

    public function disallowSeoIndexing(): void
    {
        $this->writer->append("robots", "noindex");
    }

    public function dump(): string
    {
        return $this->writer->render();
    }
}