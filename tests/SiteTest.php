<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__."/setUp.php";

class SiteTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testAllowsPublisherWithIdWithoutFilter()
    {
        // given
        $sm = new SiteManager();
        $site = $sm->create();

        // when
        $publisherIsAllowed = $site->allowsPublisherWithId(1);

        // then
        $this->assertTrue(
            $publisherIsAllowed,
            "it should be allowed when there is no publisher filter"
        );
    }

    /**
     * @throws Exception
     */
    public function testAllowsPublisherWithIdWithAllowingFilter()
    {
        // given
        $sm = new SiteManager();
                $site = $sm->create();
        $site->setOpt("publisher_filter", "3,1,2");

        // when
        $publisherIsAllowed = $site->allowsPublisherWithId(1);

        // then
        $this->assertTrue(
            $publisherIsAllowed,
            "it should be allowed when publisher is in filter"
        );
    }

    /**
     * @throws Exception
     */
    public function testAllowsPublisherWithIdWithoutAllowingFilter()
    {
        // given
        $sm = new SiteManager();
                $site = $sm->create();
        $site->setOpt("publisher_filter", "3,2");

        // when
        $publisherIsAllowed = $site->allowsPublisherWithId(1);

        // then
        $this->assertFalse(
            $publisherIsAllowed,
            "it should not be allowed when publisher is not in filter"
        );
    }
}
