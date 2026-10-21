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
use Repository\ShippingOptionRepository;
use Repository\StockRepository;

class ArticleStructuredDataBuilder
{
    private const EXCLUDED_TYPE_IDS = [
        ArticleType::SUBSCRIPTION,
        ArticleType::SUBSCRIPTION_CPPAP,
    ];

    private const BOOK_TAXES = ["BOOK", "EBOOK", "EAUDIOBOOK"];

    private const RETURN_WINDOW_DAYS = 14;

    private const EU_COUNTRY_CODES = [
        "AT", "BE", "BG", "CY", "CZ", "DE", "DK", "EE", "ES", "FI", "FR", "GR", "HR", "HU", "IE",
        "IT", "LT", "LU", "LV", "MT", "NL", "PL", "PT", "RO", "SE", "SI", "SK",
    ];

    private StockRepository $stockRepository;
    private ShippingOptionRepository $shippingOptionRepository;

    public function __construct(
        StockRepository          $stockRepository,
        ShippingOptionRepository $shippingOptionRepository,
    )
    {
        $this->stockRepository = $stockRepository;
        $this->shippingOptionRepository = $shippingOptionRepository;
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
        $summaryText = strip_tags($summary);
        if (!$this->_isBlank($summaryText)) {
            $data["description"] = trim(truncate($summaryText, 500, "...", true));
        }

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

        if ($imageUrl) {
            $offers = $this->_buildOffers($article, $type, $currentSite);
            if ($offers !== null) {
                $data["offers"] = $this->_withMerchantFields($offers, $type);
            }
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

    /**
     * @throws PropelException
     */
    private function _withMerchantFields(array $offers, ArticleType $type): array
    {
        $entryRates = $this->shippingOptionRepository->getEntryRates();
        if ($entryRates === []) {
            return $offers;
        }

        $isDownloadable = $type->isDownloadable();
        $servedCountryCodes = array_merge(...array_column($entryRates, "countryCodes"));
        sort($servedCountryCodes);

        $offers["hasMerchantReturnPolicy"] = $this->_buildReturnPolicies($servedCountryCodes, $isDownloadable);

        if (!$isDownloadable) {
            $offers["shippingDetails"] = $this->_buildShippingDetails($entryRates, $offers["priceCurrency"]);
        }

        return $offers;
    }

    /**
     * @param array<int, array{fee: int, countryCodes: string[]}> $entryRates
     */
    private function _buildShippingDetails(array $entryRates, string $currency): array
    {
        return array_map(
            fn(array $rate) => [
                "@type" => "OfferShippingDetails",
                "shippingRate" => [
                    "@type" => "MonetaryAmount",
                    "value" => number_format($rate["fee"] / 100, 2, ".", ""),
                    "currency" => $currency,
                ],
                "shippingDestination" => array_map(
                    fn(string $countryCode) => [
                        "@type" => "DefinedRegion",
                        "addressCountry" => $countryCode,
                    ],
                    $rate["countryCodes"],
                ),
            ],
            $entryRates,
        );
    }

    /**
     * @param string[] $servedCountryCodes
     */
    private function _buildReturnPolicies(array $servedCountryCodes, bool $isDownloadable): array
    {
        $euCountryCodes = $isDownloadable
            ? []
            : array_values(array_intersect($servedCountryCodes, self::EU_COUNTRY_CODES));
        $nonReturnableCountryCodes = array_values(array_diff($servedCountryCodes, $euCountryCodes));

        $policies = [];

        if ($euCountryCodes !== []) {
            $policies[] = [
                "@type" => "MerchantReturnPolicy",
                "applicableCountry" => $euCountryCodes,
                "returnPolicyCategory" => "https://schema.org/MerchantReturnFiniteReturnWindow",
                "merchantReturnDays" => self::RETURN_WINDOW_DAYS,
                "returnFees" => "https://schema.org/ReturnFeesCustomerResponsibility",
            ];
        }

        if ($nonReturnableCountryCodes !== []) {
            $policies[] = [
                "@type" => "MerchantReturnPolicy",
                "applicableCountry" => $nonReturnableCountryCodes,
                "returnPolicyCategory" => "https://schema.org/MerchantReturnNotPermitted",
            ];
        }

        return $policies;
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

    private function _isBlank(string $text): bool
    {
        return preg_match('/^[\s\p{Z}]*$/u', html_entity_decode($text)) === 1;
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
