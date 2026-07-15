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

use PHPUnit\Framework\TestCase;

require_once __DIR__."/../../setUp.php";

class ShippingVatAllocationServiceTest extends TestCase
{
    public function testAllocatesWholeAmountWhenSingleRate(): void
    {
        // given : un seul taux (5,5 %) sur la commande, port TTC 600 c (6 €)
        $htByRate = ["5.5" => 4000];

        // when
        $result = ShippingVatAllocationService::allocate($htByRate, 600);

        // then : tout le port est affecté à ce taux, sans calcul de prorata
        // (600 / 1,055 = 568,72... arrondi à 569)
        $this->assertSame(["5.5" => ["ht" => 569, "vat" => 31, "ttc" => 600]], $result);
    }

    public function testAllocatesProportionallyForTwoBalancedRates(): void
    {
        // given : 40 € HT à 5,5 % + 20 € HT à 2,1 %, port 6 € TTC (exemple métier de la spec)
        $htByRate = ["5.5" => 4000, "2.1" => 2000];

        // when
        $result = ShippingVatAllocationService::allocate($htByRate, 600);

        // then : 66,7 % du port au taux 5,5 %, 33,3 % au taux 2,1 %, somme exacte
        $this->assertSame(400, $result["5.5"]["ht"] + $result["5.5"]["vat"]);
        $this->assertSame(200, $result["2.1"]["ht"] + $result["2.1"]["vat"]);
        $this->assertSame(600, array_sum(array_column($result, "ttc")));
    }

    public function testHandlesTinyShippingAmountWithZeroPart(): void
    {
        // given : port à 1 centime, deux taux
        $htByRate = ["5.5" => 4000, "2.1" => 2000];

        // when
        $result = ShippingVatAllocationService::allocate($htByRate, 1);

        // then : une part peut légitimement tomber à 0, la somme reste exacte
        $this->assertSame(1, array_sum(array_column($result, "ttc")));
        $this->assertTrue($result["5.5"]["ttc"] === 0 || $result["2.1"]["ttc"] === 0);
    }

    public function testRoundingRemainderIsAbsorbedByLastRate(): void
    {
        // given : 3 taux, montants qui produisent un écart d'arrondi classique
        $htByRate = ["2.1" => 1000, "5.5" => 1000, "20" => 1000];

        // when
        $result = ShippingVatAllocationService::allocate($htByRate, 100);

        // then : la somme des parts TTC arrondies reste strictement égale au port
        $this->assertSame(100, array_sum(array_column($result, "ttc")));
    }

    public function testZeroHtGroupGetsNoDivisionByZero(): void
    {
        // given : un taux à 0 € HT (article gratuit) parmi d'autres
        $htByRate = ["5.5" => 0, "20" => 4000];

        // when
        $result = ShippingVatAllocationService::allocate($htByRate, 600);

        // then : pas d'exception, le groupe à 0 € HT ne reçoit rien via le poids
        // (il peut néanmoins recevoir le reliquat d'arrondi s'il est traité en dernier)
        $this->assertSame(600, array_sum(array_column($result, "ttc")));
    }

    public function testReturnsEmptyArrayWhenShippingIsZero(): void
    {
        // given
        $htByRate = ["5.5" => 4000, "20" => 2000];

        // when
        $result = ShippingVatAllocationService::allocate($htByRate, 0);

        // then
        $this->assertSame([], $result);
    }

    public function testUnknownRateGroupIsTreatedAsRegularGroup(): void
    {
        // given : un groupe "unknown" (taux de TVA non renseigné, cas legacy) et un taux connu
        $htByRate = ["unknown" => 2000, "20" => 2000];

        // when
        $result = ShippingVatAllocationService::allocate($htByRate, 600);

        // then : pas de crash, le groupe "unknown" reçoit sa part TTC intégralement en HT (pas de TVA)
        $this->assertSame(300, $result["unknown"]["ttc"]);
        $this->assertSame(300, $result["unknown"]["ht"]);
        $this->assertSame(0, $result["unknown"]["vat"]);
    }

    public function testMergeIntoBreakdownAddsPartsToExistingGroups(): void
    {
        // given : un récapitulatif de commande à deux taux, et des parts de port pour ces mêmes taux
        $vatBreakdown = [
            "5.5" => ["rate" => 5.5, "ht" => 1896, "vat" => 104, "ttc" => 2000],
            "20" => ["rate" => 20.0, "ht" => 833, "vat" => 167, "ttc" => 1000],
        ];
        $shippingParts = [
            "5.5" => ["ht" => 395, "vat" => 22, "ttc" => 417],
            "20" => ["ht" => 153, "vat" => 30, "ttc" => 183],
        ];

        // when
        $result = ShippingVatAllocationService::mergeIntoBreakdown($vatBreakdown, $shippingParts);

        // then : les montants du port s'ajoutent à ceux des articles, sans toucher au taux
        $this->assertSame(["rate" => 5.5, "ht" => 2291, "vat" => 126, "ttc" => 2417], $result["5.5"]);
        $this->assertSame(["rate" => 20.0, "ht" => 986, "vat" => 197, "ttc" => 1183], $result["20"]);
    }

    public function testMergeIntoBreakdownIsANoOpWhenNoShippingParts(): void
    {
        // given
        $vatBreakdown = ["5.5" => ["rate" => 5.5, "ht" => 1896, "vat" => 104, "ttc" => 2000]];

        // when
        $result = ShippingVatAllocationService::mergeIntoBreakdown($vatBreakdown, []);

        // then
        $this->assertSame($vatBreakdown, $result);
    }

    public function testSummarizeForDisplayReturnsNullWhenNoShipping(): void
    {
        // when
        $result = ShippingVatAllocationService::summarizeForDisplay([], []);

        // then
        $this->assertNull($result);
    }

    public function testSummarizeForDisplayReturnsRealRateForSingleGroup(): void
    {
        // given : un seul taux concerné par le port, aucun recalcul nécessaire
        $shippingParts = ["20" => ["ht" => 500, "vat" => 100, "ttc" => 600]];
        $vatBreakdown = ["20" => ["rate" => 20.0, "ht" => 2500, "vat" => 500, "ttc" => 3000]];

        // when
        $result = ShippingVatAllocationService::summarizeForDisplay($shippingParts, $vatBreakdown);

        // then
        $this->assertSame(["rate" => 20.0, "ht" => 500, "vat" => 100, "recalculated" => false], $result);
    }

    public function testSummarizeForDisplayDerivesRecalculatedRateForMultipleGroups(): void
    {
        // given : deux taux concernés par le port (mêmes parts que le test de fusion ci-dessus)
        $shippingParts = [
            "5.5" => ["ht" => 395, "vat" => 22, "ttc" => 417],
            "20" => ["ht" => 153, "vat" => 30, "ttc" => 183],
        ];
        $vatBreakdown = [
            "5.5" => ["rate" => 5.5, "ht" => 2291, "vat" => 126, "ttc" => 2417],
            "20" => ["rate" => 20.0, "ht" => 986, "vat" => 197, "ttc" => 1183],
        ];

        // when
        $result = ShippingVatAllocationService::summarizeForDisplay($shippingParts, $vatBreakdown);

        // then : taux recalculé = TVA totale du port / HT total du port = 52 / 548 ≈ 9,5 %
        $this->assertSame(["rate" => 9.5, "ht" => 548, "vat" => 52, "recalculated" => true], $result);
    }

    public function testSummarizeForDisplayReturnsNullRateWhenAggregatedHtIsZero(): void
    {
        // given : cas dégénéré, HT total nul (ne devrait pas arriver en pratique)
        $shippingParts = [
            "unknown" => ["ht" => 0, "vat" => 0, "ttc" => 0],
            "20" => ["ht" => 0, "vat" => 0, "ttc" => 0],
        ];
        $vatBreakdown = [
            "unknown" => ["rate" => null, "ht" => 0, "vat" => 0, "ttc" => 0],
            "20" => ["rate" => 20.0, "ht" => 0, "vat" => 0, "ttc" => 0],
        ];

        // when
        $result = ShippingVatAllocationService::summarizeForDisplay($shippingParts, $vatBreakdown);

        // then : pas de division par zéro, le taux recalculé est simplement inconnu
        $this->assertSame(["rate" => null, "ht" => 0, "vat" => 0, "recalculated" => true], $result);
    }
}
