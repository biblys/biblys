<?php
use AppBundle\Controller\CFCampaignController;
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

require_once "setUp.php";

class CFCampaignTest extends PHPUnit\Framework\TestCase
{
    /**
     * Test creating a post
     */
    public function testCreate()
    {
        global $site;

        $cm = new CFCampaignManager();

        $campaign = $cm->create();

        $this->assertInstanceOf('CFCampaign', $campaign);
        $this->assertEquals($campaign->get('site_id'), $site->get('id'));

        return $campaign;
    }

    /**
     * Test getting a post
     * @depends testCreate
     */
    public function testGet(CFCampaign $campaign)
    {
        $cm = new CFCampaignManager();

        $gotCFCampaign = $cm->getById($campaign->get('id'));

        $this->assertInstanceOf('CFCampaign', $campaign);
        $this->assertEquals($campaign->get('id'), $gotCFCampaign->get('id'));

        return $campaign;
    }

    /**
     * Test updating a post
     * @depends testGet
     */
    public function testUpdate(CFCampaign $campaign)
    {
        $cm = new CFCampaignManager();

        $campaign->set('campaign_title', 'Donnez-moi vos sous !');
        $cm->update($campaign);

        $updatedCFCampaign = $cm->getById($campaign->get('id'));

        $this->assertTrue($updatedCFCampaign->has('updated'));
        $this->assertEquals($updatedCFCampaign->get('title'), 'Donnez-moi vos sous !');

        return $updatedCFCampaign;
    }

    /**
     * Test hasStarted method
     * @depends testGet
     */
    public function testHasStarted(CFCampaign $campaign)
    {
        $today = (new DateTime("today"))->format("Y-m-d");
        $tomorrow = (new DateTime("tomorrow"))->format("Y-m-d");
        $yesterday = (new DateTime("yesterday"))->format("Y-m-d");

        $campaign->set('campaign_starts', $today);
        $this->assertTrue($campaign->hasStarted());

        $campaign->set('campaign_starts', $tomorrow);
        $this->assertFalse($campaign->hasStarted());

        $campaign->set('campaign_ends', $yesterday);
        $this->assertFalse($campaign->hasStarted());
    }

    /**
     * Test hasEnded method
     * @depends testGet
     */
    public function testHasEnded(CFCampaign $campaign)
    {
        $today = (new DateTime("today"))->format("Y-m-d");
        $tomorrow = (new DateTime("tomorrow"))->format("Y-m-d");
        $yesterday = (new DateTime("yesterday"))->format("Y-m-d");

        $campaign->set('campaign_ends', $today);
        $this->assertFalse($campaign->hasEnded());

        $campaign->set('campaign_ends', $tomorrow);
        $this->assertFalse($campaign->hasEnded());

        $campaign->set('campaign_ends', $yesterday);
        $this->assertTrue($campaign->hasEnded());
    }

    /**
     * Test isInProgress method
     * @depends testGet
     */
    public function testIsInProgress(CFCampaign $campaign)
    {
        $today = (new DateTime("today"))->format("Y-m-d");
        $tomorrow = (new DateTime("tomorrow"))->format("Y-m-d");
        $yesterday = (new DateTime("yesterday"))->format("Y-m-d");

        $campaign->set('campaign_starts', $yesterday);
        $campaign->set('campaign_ends', $yesterday);
        $this->assertFalse($campaign->isInProgress());

        $campaign->set('campaign_starts', $yesterday);
        $campaign->set('campaign_ends', $tomorrow);
        $this->assertTrue($campaign->isInProgress());

        $campaign->set('campaign_starts', $tomorrow);
        $campaign->set('campaign_ends', $tomorrow);
        $this->assertFalse($campaign->isInProgress());
    }

    /**
     * Test getProgress method
     * @depends testGet
     */
    public function testGetProgress(CFCampaign $campaign)
    {
        $campaign->set('campaign_goal', 1000);
        $campaign->set('campaign_pledged', 250);
        $this->assertEquals($campaign->getProgress(), 25);
    }

    /**
     * Test deleting a post
     * @depends testGet
     */
    public function testDelete(CFCampaign $campaign)
    {
        $cm = new CFCampaignManager();

        $cm->delete($campaign, 'Test entity');

        $campaignExists = $cm->getById($campaign->get('id'));

        $this->assertFalse($campaignExists);
    }
}
