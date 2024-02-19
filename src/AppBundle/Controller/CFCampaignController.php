<?php

namespace AppBundle\Controller;

use Biblys\Legacy\LegacyCodeHelper;
use CFCampaign;
use CFCampaignManager;
use CFRewardManager;
use Exception;
use Framework\Controller;
use Framework\Exception\AuthException;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CFCampaignController extends Controller
{
    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function showAction(Request $request, $slug): Response
    {
        global $urlgenerator;

        $_SITE = LegacyCodeHelper::getGlobalSite();

        $cfcm = new CFCampaignManager();
        $campaign = $cfcm->get(['campaign_url' => $slug]);
        if (!$campaign) {
            throw new NotFoundException("Campaign $slug not found.");
        }

        $request->attributes->set("page_title", $campaign->get("title"));
        $this->setOpengraphTags([
            'type' => 'website',
            'title' => 'Financement participatif : '.$campaign->get('title'),
            'url' => 'https://'.$_SITE->get('domain').
                $urlgenerator->generate('cf_campaign_show', ['slug' => $campaign->get('url')]),
            'description' => truncate(strip_tags($campaign->get('description')), '500', '...', true),
            'locale' => 'fr_FR',
            'image' => $campaign->get('image'),
            'site_name' => $_SITE->get('name'),
        ]);

        $cfrm = new CFRewardManager();
        $rewards = $cfrm->getAll(['campaign_id' => $campaign->get('id')], [
            'order' => 'reward_highlighted DESC, reward_price',
        ]);

        return $this->render('AppBundle:CFCampaign:show.html.twig', [
            'campaign' => $campaign,
            'rewards' => $rewards,
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws AuthException
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     */
    public function listAction(Request $request): Response
    {
        self::authAdmin($request);

        $cfcm = new CFCampaignManager();
        $campaigns = $cfcm->getAll();

        $request->attributes->set("page_title", "Financement participatif");

        return $this->render('AppBundle:CFCampaign:list.html.twig', [
            'campaigns' => $campaigns,
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws AuthException
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     * @throws Exception
     */
    public function newAction(Request $request)
    {
        global $request;

        self::authAdmin($request);

        $cfcm = new CFCampaignManager();
        $campaign = new CFCampaign([]);

        $request->attributes->set("page_title", "Créer une campagne");

        if ($request->getMethod() == 'POST') {
            $campaign = $cfcm->create();

            $goal = $request->request->get('goal', 0) * 100;

            $campaign->set('campaign_title', $request->request->get('title'))
                ->set('campaign_url', makeurl($request->request->get('title')))
                ->set('campaign_goal', $goal)
                ->set('campaign_starts', $request->request->get('starts'))
                ->set('campaign_ends', $request->request->get('ends'))
                ->set('campaign_image', $request->request->get('image'))
                ->set('campaign_description', $request->request->get('description'));
            $campaign = $cfcm->update($campaign);

            return $this->redirect($this->generateUrl('cf_campaign_show', ['slug' => $campaign->get('url')]));
        }

        return $this->render('AppBundle:CFCampaign:new.html.twig', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws AuthException
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     * @throws Exception
     */
    public function editAction(Request $request, $id)
    {
        global $request;

        self::authAdmin($request);

        $cfcm = new CFCampaignManager();

        $campaign = $cfcm->getById($id);
        if (!$campaign) {
            throw new NotFoundException("Campaign $id not found.");
        }

        $request->attributes->set("page_title", "Modifier {$campaign->get("title")}");

        if ($request->getMethod() == 'POST') {
            $goal = $request->request->get('goal', 0) * 100;

            $campaign->set('campaign_title', $request->request->get('title'))
                ->set('campaign_goal', $goal)
                ->set('campaign_starts', $request->request->get('starts'))
                ->set('campaign_ends', $request->request->get('ends'))
                ->set('campaign_image', $request->request->get('image'))
                ->set('campaign_description', $request->request->get('description'));
            $campaign = $cfcm->update($campaign);

            $campaign = $cfcm->updateFromSales($campaign);

            return $this->redirect($this->generateUrl('cf_campaign_show', ['slug' => $campaign->get('url')]));
        }

        return $this->render('AppBundle:CFCampaign:edit.html.twig', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * @throws AuthException
     * @throws PropelException
     * @throws Exception
     */
    public function refreshAction(Request $request, $id): RedirectResponse
    {
        self::authAdmin($request);

        $cfcm = new CFCampaignManager();
        $cfrm = new CFRewardManager();

        $campaign = $cfcm->getById($id);
        if (!$campaign) {
            throw new NotFoundException("Campaign $id not found.");
        }

        // Update campaign stats from sales
        $cfcm->updateFromSales($campaign);

        // Update rewards quantity
        $rewards = $cfrm->getAll([
            'campaign_id' => $campaign->get('id'),
            'reward_limited' => 1,
        ], ['order' => 'reward_price']);
        foreach ($rewards as $reward) {
            $cfrm->updateQuantity($reward);
        }

        return $this->redirect($this->generateUrl('cf_campaign_show', ['slug' => $campaign->get('url')]));
    }
}
