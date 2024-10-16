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
        $fee = ModelFactory::createShippingFee(
            site: $site,
            country: $country,
        );
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
