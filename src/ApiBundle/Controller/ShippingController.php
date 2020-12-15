<?php

namespace ApiBundle\Controller;

use Framework\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;

class ShippingController extends Controller
{
    /**
     * Returns shipping fees.
     *
     * GET /api/shipping
     */
    public function indexAction(Request $request)
    {
        global $site;

        $sm = $this->entityManager('Shipping');
        $fees = $sm->getAll(['site_id' => $site->get('id')]);

        $fees = array_map(
            function ($fee) {
                return $this->feeToJson($fee);
            }, $fees
        );

        return new JsonResponse($fees);
    }

    /**
     * Create a new shipping fee.
     *
     * POST /api/shipping
     */
    public function createAction(Request $request)
    {
        global $site;

        $this->auth('admin');

        $sm = $this->entityManager('Shipping');

        $fee = $sm->create(['site_id' => $site->get('id')]);

        $postData = json_decode($request->getContent());

        $maxWeight = $postData->max_weight;
        $maxWeight = $maxWeight === '' ? null : $maxWeight;

        $maxAmount = $postData->max_amount;
        $maxAmount = $maxAmount === '' ? null : $maxAmount;

        $maxArticles = $postData->max_articles;
        $maxArticles = $maxArticles === '' ? null : $maxArticles;

        $fee->set('shipping_mode', $postData->mode);
        $fee->set('shipping_type', $postData->type);
        $fee->set('shipping_zone', $postData->zone);
        $fee->set('shipping_max_weight', $maxWeight);
        $fee->set('shipping_max_amount', $maxAmount);
        $fee->set('shipping_max_articles', $maxArticles);
        $fee->set('shipping_fee', (int) $postData->fee);
        $fee->set('shipping_info', $postData->info);

        $fee = $sm->update($fee);

        return new JsonResponse($this->feeToJson($fee));
    }

    /**
     * Update a shipping range.
     *
     * @route PUT /api/shipping/{id}
     */
    public function updateAction(Request $request, int $id)
    {
        $this->auth('admin');

        $sm = $this->entityManager('Shipping');
        $fee = $sm->getById($id);

        if (!$fee) {
            return new NotFoundException();
        }

        $putData = json_decode($request->getContent());

        $maxWeight = $putData->max_weight;
        $maxWeight = $maxWeight === '' ? null : $maxWeight;

        $maxAmount = $putData->max_amount;
        $maxAmount = $maxAmount === '' ? null : $maxAmount;

        $maxArticles = $putData->max_articles;
        $maxArticles = $maxArticles === '' ? null : $maxArticles;

        $fee->set('shipping_mode', $putData->mode);
        $fee->set('shipping_type', $putData->type);
        $fee->set('shipping_zone', $putData->zone);
        $fee->set('shipping_max_weight', $maxWeight);
        $fee->set('shipping_max_amount', $maxAmount);
        $fee->set('shipping_max_articles', $maxArticles);
        $fee->set('shipping_fee', (int) $putData->fee);
        $fee->set('shipping_info', $putData->info);

        $fee = $sm->update($fee);

        return new JsonResponse($this->feeToJson($fee));
    }

    public function deleteAction($id)
    {
        $this->auth('admin');

        $sm = $this->entityManager('Shipping');
        $range = $sm->getById($id);

        if (!$range) {
            return new NotFoundException();
        }

        $sm->delete($range);

        return new JsonResponse([]);
    }

    private function feeToJson($fee)
    {
        return [
            'id' => $fee->get('id'),
            'mode' => $fee->get('mode'),
            'type' => $fee->get('type'),
            'zone' => $fee->get('zone'),
            'max_weight' => $fee->get('max_weight'),
            'max_amount' => $fee->get('max_amount'),
            'max_articles' => $fee->get('max_articles'),
            'fee' => $fee->get('fee'),
            'info' => $fee->get('info'),
        ];
    }
}
