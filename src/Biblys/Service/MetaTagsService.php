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
    private CurrentSite $currentSite;
    private static array $tags = [];

    public function __construct(Writer $writer, CurrentSite $currentSite)
    {
        $this->writer = $writer;
        $this->currentSite = $currentSite;
    }

    public function setTitle(string $title): void
    {
        $this->writer->append(Opengraph::OG_TITLE, $title);
    }

    public function setImage(string $url): void
    {
        $url = $this->_ensureUrlIsAbsolute($url);
        $this->writer->append(Opengraph::OG_IMAGE, $url);
    }

    public function setUrl(string $url): void
    {
        $url = $this->_ensureUrlIsAbsolute($url);
        $this->writer->append(Opengraph::OG_URL, $url);
        MetaTagsService::$tags[] = "<link rel=\"canonical\" href=\"$url\" />";
    }

    public function disallowSeoIndexing(): void
    {
        $this->writer->append("robots", "noindex");
    }

    public function dump(): string
    {
        return $this->writer->render() . join("\n", MetaTagsService::$tags);
    }

    /**
     * @param string $url
     * @return string
     */
    private function _ensureUrlIsAbsolute(string $url): string
    {
        $domain = $this->currentSite->getSite()->getDomain();
        if (!str_starts_with($url, "http")) {
            $url = "https://$domain$url";
        }
        return $url;
    }
}