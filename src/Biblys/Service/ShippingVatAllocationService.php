<?php
/*
 * Copyright (C) 2026 Clément Latzarus
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

class ShippingVatAllocationService
{
    /**
     * Ventile les frais de port (TTC) au prorata du total HT de chaque taux
     * de TVA présent sur la commande, puis décompose chaque part en HT/TVA
     * avec le taux de son groupe.
     *
     * @param array<string, int> $htByRate Total HT en centimes par taux ("5.5", "20", "unknown"...)
     * @param int $shippingCostTtc Frais de port en centimes, TTC
     * @return array<string, array{ht: int, vat: int, ttc: int}>
     */
    public static function allocate(array $htByRate, int $shippingCostTtc): array
    {
        if ($shippingCostTtc === 0 || empty($htByRate)) {
            return [];
        }

        if (count($htByRate) === 1) {
            $rateKey = array_key_first($htByRate);
            return [$rateKey => self::decompose($shippingCostTtc, self::rateFromKey($rateKey))];
        }

        $totalHt = array_sum($htByRate);
        $lastKey = array_key_last($htByRate);
        $allocatedTtc = 0;
        $parts = [];

        foreach ($htByRate as $rateKey => $rateHt) {
            if ($rateKey === $lastKey) {
                $ttcPart = $shippingCostTtc - $allocatedTtc;
            } else {
                $weight = $totalHt > 0 ? $rateHt / $totalHt : 0;
                $ttcPart = (int) round($shippingCostTtc * $weight);
                $allocatedTtc += $ttcPart;
            }

            $parts[$rateKey] = self::decompose($ttcPart, self::rateFromKey($rateKey));
        }

        return $parts;
    }

    /**
     * Ajoute les parts de port ventilées à un récapitulatif de TVA par taux
     * déjà constitué (ex. celui des lignes d'une commande), en cumulant
     * HT/TVA/TTC pour chaque taux concerné.
     *
     * @param array<string, array{rate: ?float, ht: int, vat: int, ttc: int}> $vatBreakdown
     * @param array<string, array{ht: int, vat: int, ttc: int}> $shippingParts
     * @return array<string, array{rate: ?float, ht: int, vat: int, ttc: int}>
     */
    public static function mergeIntoBreakdown(array $vatBreakdown, array $shippingParts): array
    {
        foreach ($shippingParts as $rateKey => $part) {
            $vatBreakdown[$rateKey]["ht"] += $part["ht"];
            $vatBreakdown[$rateKey]["vat"] += $part["vat"];
            $vatBreakdown[$rateKey]["ttc"] += $part["ttc"];
        }

        return $vatBreakdown;
    }

    /**
     * Construit le résumé à afficher sur la ligne de port d'une facture : le
     * taux réel si un seul taux s'applique, ou un taux recalculé (TVA ÷ HT)
     * agrégeant toutes les parts si plusieurs taux ont été ventilés.
     *
     * @param array<string, array{ht: int, vat: int, ttc: int}> $shippingParts
     * @param array<string, array{rate: ?float, ht: int, vat: int, ttc: int}> $vatBreakdown
     * @return array{rate: ?float, ht: int, vat: int, recalculated: bool}|null
     */
    public static function summarizeForDisplay(array $shippingParts, array $vatBreakdown): ?array
    {
        if (empty($shippingParts)) {
            return null;
        }

        $ht = array_sum(array_column($shippingParts, "ht"));
        $vat = array_sum(array_column($shippingParts, "vat"));

        if (count($shippingParts) === 1) {
            $rateKey = array_key_first($shippingParts);

            return ["rate" => $vatBreakdown[$rateKey]["rate"], "ht" => $ht, "vat" => $vat, "recalculated" => false];
        }

        return [
            "rate" => $ht > 0 ? round($vat / $ht * 100, 1) : null,
            "ht" => $ht,
            "vat" => $vat,
            "recalculated" => true,
        ];
    }

    private static function rateFromKey(string $rateKey): ?float
    {
        return $rateKey === "unknown" ? null : (float) $rateKey;
    }

    private static function decompose(int $ttc, ?float $rate): array
    {
        if ($rate === null || $rate === 0.0) {
            return ["ht" => $ttc, "vat" => 0, "ttc" => $ttc];
        }

        $ht = (int) round($ttc / (1 + $rate / 100));

        return ["ht" => $ht, "vat" => $ttc - $ht, "ttc" => $ttc];
    }
}
