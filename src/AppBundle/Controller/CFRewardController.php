<?php

namespace AppBundle\Controller;

use Framework\Controller;

use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;

class CFRewardController extends Controller
{

    public function listAction($campaign_id)
    {
        $this->auth("admin");

        $cfcm = $this->entityManager("CFCampaign");
        $campaign = $cfcm->getById($campaign_id);
        if (!$campaign) {
            throw new NotFoundException("Campaign $id not found.");
        }

        $this->setPageTitle("Contreparties de ".$campaign->get('title'));

        $cfrm = $this->entityManager("CFReward");
        $rewards = $cfrm->getAll(["campaign_id" => $campaign->get('id')]);

        return $this->render('AppBundle:CFReward:list.html.twig', [
            'campaign' => $campaign,
            'rewards' => $rewards
        ]);
    }

    public function newAction($campaign_id)
    {
        global $request;

        $this->auth("admin");

        $cfcm = $this->entityManager("CFCampaign");
        $campaign = $cfcm->getById($campaign_id);
        if (!$campaign) {
            throw new NotFoundException("Campaign $id not found.");
        }

        $reward = new \CFReward(["campaign_id" => $campaign->get("id")]);

        $this->setPageTitle('Créer une contrepartie');

        if ($request->getMethod() == "POST") {

            $cfrm = $this->entityManager("CFReward");
            $reward = $cfrm->create(["campaign_id" => $campaign->get("id")]);

            $reward->set("reward_content", $request->request->get("content"))
                ->set("reward_articles", $request->request->get("articles"))
                ->set("reward_limited", $request->request->get("limited"))
                ->set("reward_image", $request->request->get("image"))
                ->set("reward_highlighted", $request->request->get("highlighted"));
            $cfrm->update($reward);

            // Update quantity from stock
            if ($reward->get("limited")) {
                $cfrm->updateQuantity($reward);
            }

            // Update price from content
            $cfrm->updatePrice($reward);

            return $this->redirect($this->generateUrl('cf_reward_list', ['campaign_id' => $reward->getCampaign()->get('id')]));
        }

        return $this->render('AppBundle:CFReward:new.html.twig', [
            'reward' => $reward
        ]);
    }

    public function editAction($id)
    {
        global $request;

        $this->auth("admin");

        $cfrm = $this->entityManager("CFReward");

        $reward = $cfrm->getById($id);
        if (!$reward) {
            throw new NotFoundException("Reward $id not found.");
        }

        $this->setPageTitle('Modifier une contrepartie');

        if ($request->getMethod() == "POST") {

            $reward->set("reward_content", $request->request->get("content"))
                ->set("reward_articles", $request->request->get("articles"))
                ->set("reward_limited", $request->request->get("limited"))
                ->set("reward_image", $request->request->get("image"))
                ->set("reward_highlighted", $request->request->get("highlighted"));
            $reward = $cfrm->update($reward);

            // Update quantity from stock
            if ($reward->get("limited")) {
                $cfrm->updateQuantity($reward);
            }

            // Update price from content
            $cfrm->updatePrice($reward);

            return $this->redirect($this->generateUrl('cf_reward_list', ['campaign_id' => $reward->getCampaign()->get('id')]));
        }

        return $this->render('AppBundle:CFReward:edit.html.twig', [
            'reward' => $reward
        ]);
    }

    public function deleteAction($id)
    {
        $this->auth("admin");

        $cfrm = $this->entityManager("CFReward");
        $reward = $cfrm->getById($id);
        if (!$reward) {
            throw new NotFoundException("Reward $id not found.");
        }

        $cfrm->delete($reward);

        return $this->redirect($this->generateUrl('cf_reward_list', ['campaign_id' => $reward->getCampaign()->get('id')]));
    }
}
