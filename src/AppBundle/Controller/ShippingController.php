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

use Biblys\Service\Config;
use Biblys\Service\CurrentUser;
use Biblys\Service\QueryParamsService;
use Biblys\Service\TemplateService;
use DansMaCulotte\MondialRelay\DeliveryChoice;
use Exception;
use Framework\Controller;
use Model\CountryQuery;
use Model\ShippingZoneQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ShippingController extends Controller
{
    /**
     * GET /admin/shipping.
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws PropelException
     * @throws Exception
     */
    public function optionsAction(
        CurrentUser $currentUser,
        TemplateService $templateService
    ): Response
    {
        $currentUser->authAdmin();

        return $templateService->renderResponse("AppBundle:Shipping:options.html.twig", isPrivate: true);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function countriesAction(
        CurrentUser $currentUser,
        TemplateService $templateService,
    ): Response
    {
        $currentUser->authAdmin();

        $countries = CountryQuery::create()->find();

        return $templateService->renderResponse(
            "AppBundle:Shipping:countries.html.twig",
            ["countries" => $countries],
            isPrivate: true,
        );
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function zonesAction(
        CurrentUser $currentUser,
        TemplateService $templateService,
    ): Response
    {
        $currentUser->authAdmin();

        $zones = ShippingZoneQuery::create()->find();

        return $templateService->renderResponse(
            "AppBundle:Shipping:zones.html.twig",
            ["zones" => $zones],
            isPrivate: true,
        );
    }

    /**
     * @route GET /admin/shipping/zones/{id}/countries
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function zoneCountriesAction(
        CurrentUser $currentUser,
        TemplateService $templateService,
        int $id,
    ): Response
    {
        $currentUser->authAdmin();

        $zone = ShippingZoneQuery::create()->findPk($id);

        return $templateService->renderResponse(
            "AppBundle:Shipping:zone_countries.html.twig",
            ["zone" => $zone],
            isPrivate: true,
        );
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    public function selectPickupPointAction(
        Config             $config,
        QueryParamsService $queryParams,
        TemplateService    $templateService
    ): Response
    {
        if (!$config->isMondialRelayEnabled()) {
            throw new Exception("Mondial Relay n'est pas configuré sur ce site.");
        }

        $queryParams->parse([
            "country_id" => ["type" => "numeric"],
            "shipping_id" => ["type" => "numeric"],
            "postal_code" => ["type" => "string", "default" => ""]
        ]);
        $postalCode = $queryParams->get("postal_code");

        $pickupPoints = [];
        if ($postalCode) {
            $delivery = new DeliveryChoice([
                "site_id" => $config->get("mondial_relay.code_enseigne"),
                "site_key" => $config->get("mondial_relay.private_key"),
            ]);
            $pickupPoints = $delivery->findPickupPoints(country: 'FR', zipCode: $postalCode, nbResults: 20);
        }

        return $templateService->renderResponse("AppBundle:Shipping:select_pickup_point.html.twig", [
            "postal_code" => $postalCode,
            "pickup_points" => $pickupPoints,
            "country_id" => $queryParams->getInteger("country_id"),
            "shipping_id" => $queryParams->getInteger("shipping_id"),
        ]);
    }
}
