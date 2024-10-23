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


use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Test\EntityFactory;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

require_once "setUp.php";

class CFRewardTest extends PHPUnit\Framework\TestCase
{
    /**
     * Test creating a post
     * @throws Exception
     */
    public function testCreate()
    {
        $globalSite = LegacyCodeHelper::getGlobalSite();

        $cm = new CFRewardManager();

        $reward = $cm->create();

        $this->assertInstanceOf('CFReward', $reward);
        $this->assertEquals($reward->get('site_id'), $globalSite->get('id'));

        return $reward;
    }

    /**
     * Test getting a post
     * @depends testCreate
     */
    public function testGet(CFReward $reward): CFReward
    {
        $cm = new CFRewardManager();

        $gotCFReward = $cm->getById($reward->get('id'));

        $this->assertInstanceOf('CFReward', $reward);
        $this->assertEquals($reward->get('id'), $gotCFReward->get('id'));

        return $reward;
    }

    /**
     * Test updating a post
     * @depends testGet
     * @throws Exception
     */
    public function testUpdate(CFReward $reward)
    {
        $cm = new CFRewardManager();

        $reward->set('reward_content', 'Un truc vachement intéressant');
        $cm->update($reward);

        $updatedCFReward = $cm->getById($reward->get('id'));

        $this->assertTrue($updatedCFReward->has('updated'));
        $this->assertEquals('Un truc vachement intéressant', $updatedCFReward->get('content'));

        return $updatedCFReward;
    }

    /**
     * Test hasStarted method
     * @depends testGet
     */
    public function testIsLimited(CFReward $reward)
    {
        $this->assertFalse($reward->isLimited());

        $reward->set('reward_limited', 1);
        $this->assertTrue($reward->isLimited());
    }

    /**
     * Test hasStarted method
     * @depends testGet
     */
    public function testIsAvailable(CFReward $reward)
    {
        $reward->set('reward_limited', 0);
        $this->assertTrue($reward->isAvailable());

        $reward->set('reward_limited', 1);
        $this->assertFalse($reward->isAvailable());

        $reward->set('reward_quantity', 1);
        $this->assertTrue($reward->isAvailable());
    }

    /**
     * @throws Exception
     */
    public function testUpdateQuantity()
    {
        // given
        $GLOBALS["LEGACY_CURRENT_SITE"] = EntityFactory::createSite();
        $article = EntityFactory::createArticle();
        EntityFactory::createStock(["article_id" => $article->get('id')]);
        EntityFactory::createStock(["article_id" => $article->get('id')]);
        $reward = EntityFactory::createCrowdfundingReward([
            "site_id" => $GLOBALS["LEGACY_CURRENT_SITE"]->get('id')
        ]);
        $reward->set("reward_quantity", 0);
        $reward->set("reward_limited", 1);
        $reward->set("reward_articles", "[{$article->get("id")}]");
        $rm = new CFRewardManager();

        // when
        $updatedReward = $rm->updateQuantity($reward);

        // then
        $this->assertEquals(2, $updatedReward->get("reward_quantity"));
    }

    /**
     * @throws Exception
     */
    public function testUpdateQuantityWithUnexistingArticle()
    {
        // given
        $GLOBALS["LEGACY_CURRENT_SITE"] = EntityFactory::createSite();
        $reward = EntityFactory::createCrowdfundingReward([
            "site_id" => $GLOBALS["LEGACY_CURRENT_SITE"]->get('id')
        ]);
        $reward->set("reward_quantity", 0);
        $reward->set("reward_limited", 1);
        $reward->set("reward_articles", "[99999]");
        $rm = new CFRewardManager();

        // then
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("L'article n° 99999 n'existe pas.");

        // when
        $rm->updateQuantity($reward);
    }

    /**
     * @throws Exception
     */
    public function testUpdateQuantityForRewardInEndedCampaign()
    {
        // given
        $GLOBALS["LEGACY_CURRENT_SITE"] = EntityFactory::createSite();
        $campaign = EntityFactory::createCrowdfundingCampaign([
            "site_id" => $GLOBALS["LEGACY_CURRENT_SITE"]->get('id'),
            "ends" => "2019-04-28 02:42:00"
        ]);
        $reward = EntityFactory::createCrowdfundingReward([
            "site_id" => $GLOBALS["LEGACY_CURRENT_SITE"]->get('id'),
            "campaign_id" => $campaign->get("id"),
            "quantity" => 10,
        ]);
        $rm = new CFRewardManager();

        // when
        $rm->updateQuantity($reward);

        // then
        $this->assertEquals(10, $reward->get("quantity"));
    }

    /**
     * Test deleting a post
     * @depends testGet
     * @throws Exception
     */
    public function testDelete(CFReward $reward)
    {
        $cm = new CFRewardManager();

        $cm->delete($reward, 'Test entity');

        $rewardExists = $cm->getById($reward->get('id'));

        $this->assertFalse($rewardExists);
    }
}
