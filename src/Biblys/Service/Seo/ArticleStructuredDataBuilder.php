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


namespace Biblys\Service\Seo;

use Article;
use Biblys\Data\ArticleType;
use Biblys\Service\CurrentSite;

class ArticleStructuredDataBuilder
{
    private const EXCLUDED_TYPE_IDS = [
        ArticleType::SUBSCRIPTION,
        ArticleType::SUBSCRIPTION_CPPAP,
    ];

    private const BOOK_TAXES = ["BOOK", "EBOOK", "EAUDIOBOOK"];

    public function build(Article $article, ?string $imageUrl, CurrentSite $currentSite): array
    {
        $type = $article->getType();
        if (!$type || in_array($type->getId(), self::EXCLUDED_TYPE_IDS, true)) {
            return [];
        }

        $isBook = in_array($type->getTax(), self::BOOK_TAXES, true);

        $data = [
            "@context" => "https://schema.org",
            "@type" => $isBook ? ["Product", "Book"] : "Product",
            "name" => $article->get("title"),
        ];

        if ($imageUrl) {
            $data["image"] = $this->_ensureUrlIsAbsolute($imageUrl, $currentSite);
        }

        $summary = $article->get("summary") ?: "";
        $data["description"] = truncate(strip_tags($summary), 500, "...", true);

        $publisher = $article->get("publisher");
        if ($publisher) {
            $data["brand"] = [
                "@type" => "Brand",
                "name" => $publisher->get("name"),
            ];
        }

        return $data;
    }

    private function _ensureUrlIsAbsolute(string $url, CurrentSite $currentSite): string
    {
        if (str_starts_with($url, "http")) {
            return $url;
        }

        $domain = $currentSite->getSite()->getDomain();
        return "https://$domain$url";
    }
}
