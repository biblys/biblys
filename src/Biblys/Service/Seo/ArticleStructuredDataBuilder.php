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
use Biblys\Isbn\Isbn;
use Biblys\Isbn\IsbnParsingException;
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

        if ($isBook) {
            $authors = $article->get("authors");
            if ($authors) {
                $data["author"] = $authors;
            }

            if ($article->has("ean")) {
                try {
                    $data["isbn"] = Isbn::convertToIsbn13($article->get("ean"));
                } catch (IsbnParsingException) {
                    // EAN is not a valid ISBN, omit the field
                }
            }

            if ($type->getId() === ArticleType::EBOOK) {
                $data["bookFormat"] = "https://schema.org/EBook";
            } elseif ($type->getId() === ArticleType::EAUDIOBOOK) {
                $data["bookFormat"] = "https://schema.org/AudiobookFormat";
            }
        } else {
            $ean = $article->get("ean");
            if ($ean && preg_match('/^\d{13}$/', $ean)) {
                $data["gtin13"] = $ean;
            }
        }

        $offers = $this->_buildOffers($article, $type, $currentSite);
        if ($offers !== null) {
            $data["offers"] = $offers;
        }

        return $data;
    }

    private function _buildOffers(Article $article, ArticleType $type, CurrentSite $currentSite): ?array
    {
        if ($type->isDownloadable()) {
            return $this->_buildSimpleOffer(
                price: $article->get("price") / 100,
                availability: $this->_mapAvailability($article),
                currency: $this->_getCurrency($currentSite),
            );
        }

        return null;
    }

    private function _buildSimpleOffer(
        float  $price,
        string $availability,
        string $currency,
        string $condition = "https://schema.org/NewCondition",
    ): array
    {
        return [
            "@type" => "Offer",
            "priceCurrency" => $currency,
            "price" => number_format($price, 2, ".", ""),
            "availability" => $availability,
            "itemCondition" => $condition,
        ];
    }

    private function _mapAvailability(Article $article): string
    {
        if ($article->isSoldOut()) {
            return "https://schema.org/OutOfStock";
        }

        if ($article->isSoonUnavailable()) {
            return "https://schema.org/LimitedAvailability";
        }

        if ($article->isToBeReprinted()) {
            return "https://schema.org/BackOrder";
        }

        if (!$article->isPublished() && $article->isPreorderable()) {
            return "https://schema.org/PreOrder";
        }

        if (!$article->isPublished()) {
            return "https://schema.org/OutOfStock";
        }

        if ($article->isAvailable()) {
            return "https://schema.org/InStock";
        }

        return "https://schema.org/OutOfStock";
    }

    private function _getCurrency(CurrentSite $currentSite): string
    {
        $currency = $currentSite->getOption("currency") ?? "EUR";
        return $currency === "FCFA" ? "XOF" : $currency;
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
