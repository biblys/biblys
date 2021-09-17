<?php

namespace ApiBundle\Controller;

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Framework\Controller;
use Framework\Exception\AuthException;
use Model\ShippingFee;
use Model\ShippingFeeQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ShippingController extends Controller
{

    /**
     * Returns shipping fees.
     *
     * GET /api/shipping
     *
     * @throws AuthException
     * @throws PropelException
     */
    public function indexAction(Request $request, Config $config): JsonResponse
    {
        self::authAdmin($request);

        $currentSite = CurrentSite::buildFromConfig($config);
        $allFees = ShippingFeeQuery::createForSite($currentSite)->find();

        $fees = array_map(function ($fee) {
                return self::_feeToJson($fee);
            }, $allFees->getData()
        );

        return new JsonResponse($fees);
    }

    /**
     * Create a new shipping fee.
     *
     * POST /api/shipping
     * @throws AuthException
     * @throws PropelException
     */
    public function createAction(Request $request): JsonResponse
    {
        self::authAdmin($request);

        $data = self::_getDataFromRequest($request);

        $fee = new ShippingFee();
        self::_hydrateFee($fee, $data);
        $fee->save();

        return new JsonResponse($this->_feeToJson($fee), 201);
    }

    /**
     * Update a shipping range.
     *
     * @route PUT /api/shipping/{id}
     * @throws AuthException
     * @throws PropelException
     */
    public function updateAction(Request $request, Config $config, int $id): JsonResponse
    {
        self::authAdmin($request);

        $currentSite = CurrentSite::buildFromConfig($config);
        $fee = ShippingFeeQuery::createForSite($currentSite)->findPk($id);
        if (!$fee) {
            throw new ResourceNotFoundException(
                sprintf("Cannot find shipping fee with id %s", $id)
            );
        }

        $data = self::_getDataFromRequest($request);
        $fee = self::_hydrateFee($fee, $data);
        $fee->save();

        return new JsonResponse($this->_feeToJson($fee));
    }

    /**
     * @throws AuthException
     * @throws PropelException
     */
    public function deleteAction(Request $request, Config $config, int $id): JsonResponse
    {
        self::authAdmin($request);

        $currentSite = CurrentSite::buildFromConfig($config);
        $fee = ShippingFeeQuery::createForSite($currentSite)->findPk($id);
        if (!$fee) {
            throw new ResourceNotFoundException(
                sprintf("Cannot find shipping fee with id %s", $id)
            );
        }

        $fee->delete();

        return new JsonResponse(null, 204);
    }

    private static function _feeToJson(ShippingFee $fee): array
    {
        return [
            'id' => $fee->getId(),
            'mode' => $fee->getMode(),
            'type' => $fee->getType(),
            'zone' => $fee->getZone(),
            'max_weight' => $fee->getMaxWeight(),
            'min_amount' => $fee->getMinAmount(),
            'max_amount' => $fee->getMaxAmount(),
            'max_articles' => $fee->getMaxArticles(),
            'fee' => $fee->getFee(),
            'info' => $fee->getInfo(),
        ];
    }


    /**
     * @param Request $request
     * @return array
     */
    private static function _getDataFromRequest(Request $request): array
    {
        $data = json_decode($request->getContent());

        $maxWeight = $data->max_weight;
        $maxWeight = $maxWeight === '' ? null : $maxWeight;

        $minAmount = $data->min_amount;
        $minAmount = $minAmount === '' ? null : $minAmount;

        $maxAmount = $data->max_amount;
        $maxAmount = $maxAmount === '' ? null : $maxAmount;

        $maxArticles = $data->max_articles;
        $maxArticles = $maxArticles === '' ? null : $maxArticles;

        return [
            "mode" => $data->mode,
            "type" => $data->type,
            "zone" => $data->zone,
            "fee" => $data->fee,
            "info" => $data->info,
            "maxWeight" => $maxWeight,
            "minAmount" => $minAmount,
            "maxAmount" => $maxAmount,
            "maxArticles" => $maxArticles
        ];
    }

    /**
     * @param ShippingFee $fee
     * @param array $data
     * @return ShippingFee
     */
    private static function _hydrateFee(ShippingFee $fee, array $data): ShippingFee
    {
        $fee->setMode($data["mode"]);
        $fee->setType($data["type"]);
        $fee->setZone($data["zone"]);
        $fee->setMaxWeight($data["maxWeight"]);
        $fee->setMinAmount($data["minAmount"]);
        $fee->setMaxAmount($data["maxAmount"]);
        $fee->setMaxArticles($data["maxArticles"]);
        $fee->setFee($data["fee"]);
        $fee->setInfo($data["info"]);

        return $fee;
    }
}
