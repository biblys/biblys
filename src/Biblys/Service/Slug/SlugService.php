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


namespace Biblys\Service\Slug;

use Biblys\Service\StringService;
use Symfony\Component\String\Slugger\AsciiSlugger;

class SlugService
{
    public function slugify(string $input): string
    {
        $replacements = [
            "&" => "et",
            "+" => "plus",
        ];
        $slugger = new AsciiSlugger("fr", ["fr" => $replacements]);

        return $slugger->slug((new StringService($input))->lowercase()->get());
    }

    public function createForBookCollection($collectionName, $publisherName): string
    {
        $collectionSlug = $this->slugify($collectionName);
        $publisherSlug = $this->slugify($publisherName);
        $collectionNameIncludesPublisher = stripos($collectionSlug, $publisherSlug) !== false;
        if ($collectionNameIncludesPublisher) {
            return $this->slugify($collectionName);
        }

        return $this->slugify("$publisherName-$collectionName");
    }

    /**
     * @throws InvalidSlugException
     */
    public
    function validateArticleSlug(string $string): void
    {
        $pattern = "/^[a-z0-9\-]+\/[a-z0-9\-_]+$/";
        if (!preg_match($pattern, $string)) {
            throw new InvalidSlugException("Invalid article slug");
        }
    }
}
