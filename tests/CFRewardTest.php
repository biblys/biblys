<?php

use AppBundle\Controller\CFRewardController;
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
     */
    public function testCreate()
    {
        global $site;

        $cm = new CFRewardManager();

        $reward = $cm->create();

        $this->assertInstanceOf('CFReward', $reward);
        $this->assertEquals($reward->get('site_id'), $site->get('id'));

        return $reward;
    }

    /**
     * Test getting a post
     * @depends testCreate
     */
    public function testGet(CFReward $reward)
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
     */
    public function testUpdate(CFReward $reward)
    {
        $cm = new CFRewardManager();

        $reward->set('reward_content', 'Un truc vachement intéressant');
        $cm->update($reward);

        $updatedCFReward = $cm->getById($reward->get('id'));

        $this->assertTrue($updatedCFReward->has('updated'));
        $this->assertEquals($updatedCFReward->get('content'), 'Un truc vachement intéressant');

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
        $article = EntityFactory::createArticle();
        EntityFactory::createStock(["article_id" => $article->get('id')]);
        EntityFactory::createStock(["article_id" => $article->get('id')]);
        $reward = EntityFactory::createCrowdfundingReward();
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
        $reward = EntityFactory::createCrowdfundingReward();
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
     * Test deleting a post
     * @depends testGet
     */
    public function testDelete(CFReward $reward)
    {
        $cm = new CFRewardManager();

        $cm->delete($reward, 'Test entity');

        $rewardExists = $cm->getById($reward->get('id'));

        $this->assertFalse($rewardExists);
    }
}
