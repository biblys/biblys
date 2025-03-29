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


namespace AppBundle\Controller;

use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\CurrentUser;
use Biblys\Service\MetaTagsService;
use CFCampaign;
use CFCampaignManager;
use CFRewardManager;
use Exception;
use Framework\Controller;
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
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function showAction(Request $request, MetaTagsService $metaTagsService, $slug): Response
    {

        $globalSite = LegacyCodeHelper::getGlobalSite();

        $cfcm = new CFCampaignManager();
        $campaign = $cfcm->get(['campaign_url' => $slug]);
        if (!$campaign) {
            throw new NotFoundException("Campaign $slug not found.");
        }

        $request->attributes->set("page_title", $campaign->get("title"));

        $metaTagsService->setTitle("Financement participatif : " . $campaign->get('title'));
        if ($campaign->has("image")) {
            $metaTagsService->setImage($campaign->get('image'));
        }

        $this->setOpengraphTags([
            'type' => 'website',
            'title' => 'Financement participatif : ' . $campaign->get('title'),
            'url' => 'https://' . $globalSite->get('domain') .
                \Biblys\Legacy\LegacyCodeHelper::getGlobalUrlGenerator()->generate('cf_campaign_show', ['slug' => $campaign->get('url')]),
            'description' => truncate(strip_tags($campaign->get('description')), '500', '...', true),
            'locale' => 'fr_FR',
            'image' => $campaign->get('image'),
            'site_name' => $globalSite->get('name'),
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
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     * @throws Exception
     */
    public function listAction(Request $request, CurrentUser $currentUser): Response
    {
        $currentUser->authAdmin();

        $cfcm = new CFCampaignManager();
        $campaigns = $cfcm->getAll();

        $request->attributes->set("page_title", "Financement participatif");

        return $this->render('AppBundle:CFCampaign:list.html.twig', [
            'campaigns' => $campaigns,
        ], isPrivate: true);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     * @throws Exception
     */
    public function newAction(Request $request, CurrentUser $currentUser): RedirectResponse|Response
    {
        $request = LegacyCodeHelper::getGlobalRequest();

        $currentUser->authAdmin();

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
        ], isPrivate: true);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     * @throws Exception
     */
    public function editAction(Request $request, CurrentUser $currentUser, $id): RedirectResponse|Response
    {
        $request = LegacyCodeHelper::getGlobalRequest();

        $currentUser->authAdmin();

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
        ], isPrivate: true);
    }

    /**
     * @throws Exception
     */
    public function refreshAction(CurrentUser $currentUser, $id): RedirectResponse
    {
        $currentUser->authAdmin();

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
