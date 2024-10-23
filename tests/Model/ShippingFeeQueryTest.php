<?php
// Copyright (C) 2024 Clément Latzarus
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU Affero General Public License as published
// by the Free Software Foundation, version 3.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Affero General Public License for more details.
//
// You should have received a copy of the GNU Affero General Public License
// along with this program.  If not, see <https://www.gnu.org/licenses/>.


namespace Model;

use Biblys\Service\CurrentSite;
use Biblys\Test\ModelFactory;
use Exception;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__."/../setUp.php";

class ShippingFeeQueryTest extends TestCase
{
    /**
     * Test getting fees
     * @throws PropelException
     * @throws Exception
     */
    public function testGetForCountryAmountAndWeight()
    {
        // given
        $site = ModelFactory::createSite();
        $country = ModelFactory::createCountry();
        $fee = ModelFactory::createShippingFee([
            "site_id" => $site->getId(),
            "type" => "normal",
            "zone" => $country->getShippingZone(),
            "max_weight" => 1000,
            "max_amount" => 2000,
        ]);
        $orderWeight = 500;
        $orderAmount = 1500;
        $currentSite = new CurrentSite($site);

        // when
        list(, $feeNormal) = ShippingFeeQuery::getForCountryWeightAndAmount(
            $currentSite,
            $country,
            $orderWeight,
            $orderAmount
        );

        // then
        $this->assertEquals(
            $feeNormal,
            $fee
        );
    }
}
