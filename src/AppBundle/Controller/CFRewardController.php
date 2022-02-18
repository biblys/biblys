<?php

namespace AppBundle\Controller;

use ArticleManager;
use CFCampaignManager;
use CFReward;
use CFRewardManager;
use Exception;
use Framework\Controller;

use Framework\Exception\AuthException;
use Model\ArticleQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CFRewardController extends Controller
{

    /**
     * @throws SyntaxError
     * @throws AuthException
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function listAction(Request $request, $campaign_id): Response
    {
        self::authAdmin($request);

        $cfcm = new CFCampaignManager();
        $campaign = $cfcm->getById($campaign_id);
        if (!$campaign) {
            throw new NotFoundException("Campaign $campaign_id not found.");
        }

        $request->attributes->set("page_title", "Contreparties de {$campaign->get("title")}");

        $cfrm = new CFRewardManager();
        $rewards = $cfrm->getAll(["campaign_id" => $campaign->get('id')]);

        return $this->render('AppBundle:CFReward:list.html.twig', [
            'campaign' => $campaign,
            'rewards' => $rewards
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws AuthException
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     * @throws Exception
     */
    public function newAction(Request $request, UrlGenerator $urlGenerator, $campaign_id)
    {
        self::authAdmin($request);

        $cfcm = new CFCampaignManager();
        $campaign = $cfcm->getById($campaign_id);
        if (!$campaign) {
            throw new NotFoundException("Campaign $campaign_id not found.");
        }

        $reward = new CFReward(["campaign_id" => $campaign->get("id")]);

        $request->attributes->set("page_title", "Créer une contrepartie");

        if ($request->getMethod() == "POST") {

            self::_checkArticlesIdsInRequest($request);

            $cfrm = new CFRewardManager();
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

            return $this->redirect($urlGenerator->generate('cf_reward_list', [
                'campaign_id' => $reward->getCampaign()->get('id')
            ]));
        }

        return $this->render('AppBundle:CFReward:new.html.twig', [
            'reward' => $reward
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws AuthException
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     * @throws Exception
     * @throws Exception
     */
    public function editAction(Request $request, UrlGenerator $urlGenerator, $id)
    {
        self::authAdmin($request);

        $cfrm = new CFRewardManager();
        $reward = $cfrm->getById($id);
        if (!$reward) {
            throw new NotFoundException("Reward $id not found.");
        }

        $request->attributes->set("page_title", "Modifier une contrepartie");

        if ($request->getMethod() == "POST") {

            self::_checkArticlesIdsInRequest($request);

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

            return $this->redirect(
                $urlGenerator->generate(
                    "cf_reward_list",
                    ["campaign_id" => $reward->getCampaign()->get("id")]
                )
            );
        }

        return $this->render('AppBundle:CFReward:edit.html.twig', [
            'reward' => $reward
        ]);
    }

    /**
     * @throws AuthException
     * @throws PropelException
     * @throws Exception
     */
    public function deleteAction(Request $request, $id): RedirectResponse
    {
        self::authAdmin($request);

        $cfrm = new CFRewardManager();
        $reward = $cfrm->getById($id);
        if (!$reward) {
            throw new NotFoundException("Reward $id not found.");
        }

        $cfrm->delete($reward);

        return $this->redirect($this->generateUrl('cf_reward_list', ['campaign_id' => $reward->getCampaign()->get('id')]));
    }

    /**
     * @param Request $request
     * @return void
     */
    private static function _checkArticlesIdsInRequest(Request $request): void
    {
        $articlesIds = json_decode($request->request->get("articles"), true);
        $am = new ArticleManager();
        foreach ($articlesIds as $articleId) {
            if (!$am->getById($articleId)) {
                throw new BadRequestException("L'article {$articleId} n'existe pas.");
            }
        }
    }
}
