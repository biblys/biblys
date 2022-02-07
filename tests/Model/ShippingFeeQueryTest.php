<?php

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
