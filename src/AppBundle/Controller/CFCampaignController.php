<?php

namespace AppBundle\Controller;

use Framework\Controller;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;

class CFCampaignController extends Controller
{
    public function showAction($slug)
    {
        global $site, $urlgenerator;

        $cfcm = $this->entityManager('CFCampaign');
        $campaign = $cfcm->get(['campaign_url' => $slug]);
        if (!$campaign) {
            throw new NotFoundException("Campaign $slug not found.");
        }

        $this->setPageTitle($campaign->get('title'));
        $this->setOpengraphTags([
            'type' => 'website',
            'title' => 'Financement participatif : '.$campaign->get('title'),
            'url' => 'http://'.$site->get('domain').
                $urlgenerator->generate('cf_campaign_show', ['slug' => $campaign->get('url')]),
            'description' => truncate(strip_tags($campaign->get('description')), '500', '...', true),
            'locale' => 'fr_FR',
            'image' => $campaign->get('image'),
            'site_name' => $site->get('name'),
        ]);

        $cfrm = $this->entityManager('CFReward');
        $rewards = $cfrm->getAll(['campaign_id' => $campaign->get('id')], [
            'order' => 'reward_highlighted DESC, reward_price',
        ]);

        return $this->render('AppBundle:CFCampaign:show.html.twig', [
            'campaign' => $campaign,
            'rewards' => $rewards,
        ]);
    }

    public function listAction()
    {
        $this->auth('admin');

        $cfcm = $this->entityManager('CFCampaign');
        $campaigns = $cfcm->getAll();

        $this->setPageTitle('Financement participatif');

        return $this->render('AppBundle:CFCampaign:list.html.twig', [
            'campaigns' => $campaigns,
        ]);
    }

    public function newAction()
    {
        global $request;

        $this->auth('admin');

        $cfcm = $this->entityManager('CFCampaign');
        $campaign = new \CFCampaign([]);

        $this->setPageTitle('Créer une campagne');

        if ($request->getMethod() == 'POST') {
            $campaign = $cfcm->create();

            $goal = $request->request->get('goal', 0) * 100;

            $campaign->set('campaign_title', $request->request->get('title'))
                ->set('campaign_url', slugify($request->request->get('title')))
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

    public function editAction($id)
    {
        global $request;

        $this->auth('admin');

        $cfcm = $this->entityManager('CFCampaign');

        $campaign = $cfcm->getById($id);
        if (!$campaign) {
            throw new NotFoundException("Campaign $id not found.");
        }

        $this->setPageTitle('Modifier '.$campaign->get('title'));

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

    public function refreshAction($id)
    {
        $this->auth('admin');

        $cfcm = $this->entityManager('CFCampaign');
        $cfrm = $this->entityManager('CFReward');

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
