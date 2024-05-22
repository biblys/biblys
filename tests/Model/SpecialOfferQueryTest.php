<?php

namespace Model;

use Biblys\Test\ModelFactory;
use DateTime;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__ .  "/../setUp.php";

class SpecialOfferQueryTest extends TestCase
{

    /**
     * @throws PropelException
     */
    public function testFilterByActive()
    {
        // given
        $site = ModelFactory::createSite();
        $activeOffer = ModelFactory::createSpecialOffer($site);
        $offerStartingTomorrow = ModelFactory::createSpecialOffer(
            site: $site,
            startDate: new DateTime("tomorrow"),
        );
        $offerEndedYesterday = ModelFactory::createSpecialOffer(
            site: $site,
            endDate: new DateTime("yesterday"),
        );

        // when
        $activeOffers = SpecialOfferQuery::create()
            ->filterBySite($site)
            ->filterByActive()
            ->find()
            ->getArrayCopy();

        // then
        $this->assertContains($activeOffer, $activeOffers, "returns active offers");
        $this->assertNotContains($offerStartingTomorrow, $activeOffers, "ignores future offers");
        $this->assertNotContains($offerEndedYesterday, $activeOffers, "ignores past offers");
    }
}
