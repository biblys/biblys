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


namespace ApiBundle\Controller;

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\InvalidSiteIdException;
use Exception;
use Framework\Controller;
use Model\CountryQuery;
use Model\ShippingFee;
use Model\ShippingFeeQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ShippingController extends Controller
{

    /**
     * Returns shipping fees.
     *
     * @route GET /api/admin/shipping
     *
     * @throws Exception
     */
    public function indexAction(CurrentUser $currentUser, Config $config): JsonResponse
    {
        $currentUser->authAdmin();

        $currentSite = CurrentSite::buildFromConfig($config);
        $allFees = ShippingFeeQuery::createForSite($currentSite)
            ->orderByType()
            ->orderByZone()
            ->orderByFee()
            ->find();

        $fees = array_map(function ($fee) {
                return self::_feeToJson($fee);
            }, $allFees->getData()
        );

        return new JsonResponse($fees);
    }

    /**
     * Create a new shipping fee.
     *
     * @route POST /api/admin/shipping
     *
     * @throws PropelException
     * @throws Exception
     */
    public function createAction(Request $request, Config $config, CurrentUser $currentUser): JsonResponse
    {
        $currentUser->authAdmin();
        $currentSite = CurrentSite::buildFromConfig($config);

        $data = self::_getDataFromRequest($request);

        $fee = new ShippingFee();
        self::_hydrateFee($fee, $data);
        $fee->setSiteId($currentSite->getSite()->getId());
        $fee->save();

        return new JsonResponse($this->_feeToJson($fee), 201);
    }

    /**
     * Update a shipping range.
     *
     * @route PUT /api/admin/shipping/{id}
     *
     * @throws PropelException
     * @throws Exception
     */
    public function updateAction(
        Request $request,
        Config $config,
        CurrentUser $currentUser,
        int $id
    ): JsonResponse
    {
        $currentUser->authAdmin();

        $fee = self::_getFeeFromId($config, $id);
        $data = self::_getDataFromRequest($request);
        $fee = self::_hydrateFee($fee, $data);
        $fee->save();

        return new JsonResponse($this->_feeToJson($fee));
    }

    /**
     * @route DELETE /api/admin/shipping/{id}
     *
     * @throws Exception
     */
    public function archiveAction(
        CurrentUser $currentUser,
        int $id
    ): JsonResponse
    {
        $currentUser->authAdmin();

        $fee = ShippingFeeQuery::create()->findPk($id);
        if (!$fee) {
            throw new ResourceNotFoundException(
                sprintf("Cannot find shipping fee with id %s", $id)
            );
        }

        $fee->archive();
        $fee->save();

        return new JsonResponse(null, 204);
    }

    /**
     * @route DELETE /api/admin/shipping/{id}
     *
     * @throws PropelException
     * @throws Exception
     */
    public function deleteAction(
        Config $config,
        CurrentUser $currentUser,
        int $id
    ): JsonResponse
    {
        $currentUser->authAdmin();

        $fee = self::_getFeeFromId($config, $id);
        $fee->delete();

        return new JsonResponse(null, 204);
    }

    /**
     * @route GET /api/shipping/{id}
     * @throws InvalidSiteIdException
     */
    public function get(Config $config, int $id): JsonResponse
    {
        $fee = self::_getFeeFromId($config, $id);
        $json = self::_feeToJson($fee);
        return new JsonResponse($json, 200);
    }

    /**
     * @throws Exception
     */
    public function search(Request $request, CurrentSite $currentSite): JsonResponse
    {
        $countryId = $request->query->get('country_id');
        $country = CountryQuery::create()->findPk($countryId);
        if (!$country) {
            throw new BadRequestException("Pays inconnu");
        }

        $countriesBlockedForShipping = $currentSite->getOption("countries_blocked_for_shipping");
        if ($countriesBlockedForShipping) {
            $blockedCountriesCodes = explode(",", $countriesBlockedForShipping);
            if (in_array($country->getCode(), $blockedCountriesCodes)) {
                throw new BadRequestException("Expédition non disponible pour ce pays.");
            }
        }

        $orderWeight = $request->query->get("order_weight", 0);
        $orderAmount = $request->query->get("order_amount", 0);
        $articleCount = $request->query->get("article_count", 0);
        $fees = ShippingFeeQuery::getForCountryAndWeightAndAmountAndArticleCount(
            $currentSite,
            $country,
            $orderWeight,
            $orderAmount,
            $articleCount
        );
        $serializedFees = array_values(array_map(function ($fee) {
                return [
                    'id' => $fee->getId(),
                    'mode' => $fee->getMode(),
                    'fee' => $fee->getFee(),
                    'type' => $fee->getType(),
                    'info' => $fee->getInfo(),
                ];
            }, $fees
        ));

        return new JsonResponse($serializedFees);
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
            'is_compliant_with_french_law' => $fee->isCompliantWithFrenchLaw(),
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

    /**
     * @throws InvalidSiteIdException
     */
    private static function _getFeeFromId(Config $config, int $id): ShippingFee
    {
        $currentSite = CurrentSite::buildFromConfig($config);
        $fee = ShippingFeeQuery::createForSite($currentSite)->findPk($id);
        if (!$fee) {
            throw new ResourceNotFoundException(
                sprintf("Cannot find shipping fee with id %s", $id)
            );
        }
        return $fee;
    }
}
