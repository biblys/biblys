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
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
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
        Request     $request,
        CurrentUser $currentUser,
    ): Response
    {
        $currentUser->authAdmin();

        $request->attributes->set("page_title", "Expéditions");

        return $this->render("AppBundle:Shipping:options.html.twig");
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
