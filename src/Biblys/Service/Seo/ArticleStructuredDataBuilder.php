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

use Biblys\Data\ArticleType;
use Biblys\Isbn\IsbnParsingException;
use Biblys\Service\CurrentSite;
use Model\Article;
use Propel\Runtime\Exception\PropelException;
use Repository\StockRepository;

class ArticleStructuredDataBuilder
{
    private const EXCLUDED_TYPE_IDS = [
        ArticleType::SUBSCRIPTION,
        ArticleType::SUBSCRIPTION_CPPAP,
    ];

    private const BOOK_TAXES = ["BOOK", "EBOOK", "EAUDIOBOOK"];

    private StockRepository $stockRepository;

    public function __construct(StockRepository $stockRepository)
    {
        $this->stockRepository = $stockRepository;
    }

    /**
     * @throws PropelException
     */
    public function build(Article $article, ?string $imageUrl, CurrentSite $currentSite): array
    {
        $type = $article->getType();
        if (in_array($type->getId(), self::EXCLUDED_TYPE_IDS, true)) {
            return [];
        }

        $isBook = in_array($type->getTax(), self::BOOK_TAXES, true);

        $data = [
            "@context" => "https://schema.org",
            "@type" => $isBook ? ["Product", "Book"] : "Product",
            "name" => $article->getTitle(),
        ];

        if ($imageUrl) {
            $data["image"] = $this->_ensureUrlIsAbsolute($imageUrl, $currentSite);
        }

        $summary = $article->getSummary() ?: "";
        $data["description"] = truncate(strip_tags($summary), 500, "...", true);

        $publisher = $article->getPublisher();
        if ($publisher) {
            $data["brand"] = [
                "@type" => "Brand",
                "name" => $publisher->getName(),
            ];
        }

        if ($isBook) {
            $authors = $article->getAuthors();
            if ($authors) {
                $data["author"] = $authors;
            }

            $ean = $article->getEan();
            if ($ean) {
                try {
                    $data["isbn"] = $article->getIsbn();
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
            $ean = $article->getEan();
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

    /**
     * @throws PropelException
     */
    private function _buildOffers(Article $article, ArticleType $type, CurrentSite $currentSite): ?array
    {
        if ($type->isDownloadable()) {
            return $this->_buildSimpleOffer(
                price: $article->getPrice() / 100,
                availability: $this->_mapAvailability($article),
                currency: $this->_getCurrency($currentSite),
            );
        }

        if ($currentSite->hasOptionEnabled("virtual_stock")) {
            return $this->_buildSimpleOffer(
                price: $article->getPrice() / 100,
                availability: $this->_mapAvailability($article),
                currency: $this->_getCurrency($currentSite),
            );
        }

        $items = $this->stockRepository->getAvailableItemsFor($article, $currentSite);
        if (count($items) === 0) {
            return null;
        }

        $distinctOffers = array_unique(array_map(
            fn($item) => $item->getSellingPrice() . "-" . $item->getCondition(),
            $items,
        ));

        if (count($items) === 1 || count($distinctOffers) === 1) {
            $item = $items[0];
            return $this->_buildSimpleOffer(
                price: $item->getSellingPrice() / 100,
                availability: "https://schema.org/InStock",
                currency: $this->_getCurrency($currentSite),
                condition: $item->getCondition() === "Neuf"
                    ? "https://schema.org/NewCondition"
                    : "https://schema.org/UsedCondition",
            );
        }

        $prices = array_map(fn($item) => $item->getSellingPrice() / 100, $items);

        return [
            "@type" => "AggregateOffer",
            "priceCurrency" => $this->_getCurrency($currentSite),
            "lowPrice" => number_format(min($prices), 2, ".", ""),
            "highPrice" => number_format(max($prices), 2, ".", ""),
            "offerCount" => count($items),
            "availability" => "https://schema.org/InStock",
        ];
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
        if ($article->isOutOfPrint()) {
            return "https://schema.org/OutOfStock";
        }

        if ($article->isSoonOutOfPrint()) {
            return "https://schema.org/LimitedAvailability";
        }

        if ($article->isToBeReprinted()) {
            return "https://schema.org/BackOrder";
        }

        if (!$article->isPublished() && $article->isPreorder()) {
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
