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


namespace Model;

use Biblys\Test\ModelFactory;
use Exception;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__ . "/../setUp.php";

class ShippingOptionQueryTest extends TestCase
{
    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetForCountryAndAmountAndWeightAndArticleCount()
    {
        // given
        $site = ModelFactory::createSite();
        $zone = ModelFactory::createShippingZone();
        $country = ModelFactory::createCountry(shippingZone: $zone);
        $fee = ModelFactory::createShippingOption(site: $site, country: $country, shippingZone: $zone);
        $orderWeight = 500;
        $orderAmount = 1500;

        // when
        $fees = ShippingOptionQuery::getForCountryAndWeightAndAmountAndArticleCount(
            $country,
            $orderWeight,
            $orderAmount,
            articleCount: 1
        );

        // then
        $feeNormal = $fees[1];
        $this->assertEquals($feeNormal, $fee);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetForWithArticleCountConstraint()
    {
        // given
        $site = ModelFactory::createSite();
        $zone = ModelFactory::createShippingZone();
        $country = ModelFactory::createCountry(shippingZone: $zone);
        ModelFactory::createShippingOption(
            site: $site,
            country: $country,
            maxArticles: 1,
            shippingZone: $zone,
        );
        $feeForTwoArticles = ModelFactory::createShippingOption(
            site: $site,
            country: $country,
            maxArticles: 2,
            shippingZone: $zone,
        );
        $orderWeight = 500;
        $orderAmount = 1500;

        // when
        list(, $returnedFee) = ShippingOptionQuery::getForCountryAndWeightAndAmountAndArticleCount(
            $country,
            $orderWeight,
            $orderAmount,
            articleCount: 2
        );

        // then
        $this->assertEquals(
            $returnedFee,
            $feeForTwoArticles
        );
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetForDoesNotReturnArchivedFees()
    {
        // given
        $site = ModelFactory::createSite();
        $zone = ModelFactory::createShippingZone();
        $country = ModelFactory::createCountry(shippingZone: $zone);
        $archivedFee = ModelFactory::createShippingOption(site: $site, country: $country, isArchived: true, shippingZone: $zone);
        $activeFee = ModelFactory::createShippingOption(site: $site, country: $country, shippingZone: $zone);
        $orderWeight = 500;
        $orderAmount = 1500;

        // when
        $returnedFees = ShippingOptionQuery::getForCountryAndWeightAndAmountAndArticleCount(
            $country,
            $orderWeight,
            $orderAmount,
            articleCount: 1
        );

        // then
        $this->assertContains($activeFee, $returnedFees);
        $this->assertNotContains($archivedFee, $returnedFees);
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetEnforcesMinWeightRules(): void
    {
        // given
        $site = ModelFactory::createSite();
        $zone = ModelFactory::createShippingZone();
        $country = ModelFactory::createCountry(shippingZone: $zone);
        $fee = ModelFactory::createShippingOption(
            site: $site,
            country: $country,
            minWeight: 1000, // 1kg
            shippingZone: $zone
        );
        $orderWeight = 500; // 0.5kg
        $orderAmount = 1500;

        // when
        $fees = ShippingOptionQuery::getForCountryAndWeightAndAmountAndArticleCount(
            $country,
            $orderWeight,
            $orderAmount,
            articleCount: 1
        );

        // then
        $this->assertNotContains($fee, $fees);
    }
}
