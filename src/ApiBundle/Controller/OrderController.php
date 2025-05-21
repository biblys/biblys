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

use Biblys\Exception\InvalidConfigurationException;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\QueryParamsService;
use Biblys\Service\StringService;
use DateTime;
use Framework\Controller;
use League\Csv\CannotInsertRecord;
use League\Csv\Exception;
use League\Csv\InvalidArgument;
use League\Csv\Writer;
use Model\OrderQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    /**
     * @throws PropelException
     * @throws CannotInsertRecord
     * @throws Exception
     * @throws InvalidConfigurationException
     */
    public function exportForMondialRelayAction(
        CurrentUser        $currentUser,
        CurrentSite        $currentSite,
        Config             $config,
        QueryParamsService $queryParams,
    ): Response
    {
        $currentUser->authAdmin();

        $queryParams->parse([
            "status" => ["type" => "numeric", "default" => 0],
            "payment" => ["type" => "string", "default" => null],
            "shipping" => ["type" => "string", "default" => "mondial-relay"],
            "query" => ["type" => "string", "default" => ""],
        ]);

        $orderQuery = OrderQuery::create()
            ->filterBySite($currentSite->getSite())
            ->filterByCancelDate(null, Criteria::ISNULL);

        if ($queryParams->getInteger("status") === 2) {
            $orderQuery
                ->filterByPaymentDate(null, Criteria::ISNOTNULL)
                ->filterByShippingDate(null, Criteria::ISNULL);
        }

        if ($queryParams->get("shipping")) {
            $orderQuery->joinWithShippingOption()
                ->useShippingOptionQuery()
                ->filterByType($queryParams->get("shipping"))
                ->endUse();
        }

        $csv = Writer::createFromString();
        $csv->setDelimiter(';');

        $collectPointId = $config->get("mondial_relay.id_relais_collecte");
        if (!$collectPointId) {
            throw new InvalidConfigurationException(
                "L'option de configuration mondial_relay.id_relais_collecte doit être renseignée."
            );
        }

        $shippingPackagingWeight = $currentSite->getOption("shipping_packaging_weight");
        $orders = $orderQuery->find();
        foreach ($orders as $order) {
            $orderWeight = $order->getTotalWeight() + $shippingPackagingWeight;

            $formattedPhone = preg_replace('/[^\d+]/', '', $order->getPhone());

            $customerRef = (new StringService($order->getLastname()))->limit(9)->get();
            $record = [
                $customerRef,                                                    # A - Numéro de client (F)
                $order->getId(),                                                 # B - Numéro de commande (F)
                trim("{$order->getLastname()} {$order->getFirstname()}"), # C - Adresse de livraison (Nom du client final) (O)
                "",                                                              # D - Adresse de livraison (Complément du nom) (F)
                $order->getAddress1(),                                           # E - Adresse du destinataire (Numéro + Rue) (O)
                $order->getAddress2(),                                           # F - Adresse du destinataire (Complément d'adresse) (F)
                $order->getCity(),                                               # G - Ville du destinataire (O)
                $order->getPostalcode(),                                         # H - Code Postal du destinataire (O)
                $order->getCountry()->getCode(),                                 # I - Pays du destinataire (O)
                $formattedPhone,                                                 # J - Téléphone fixe du destinataire (F)
                "",                                                              # K - Téléphone cellulaire (F)
                $order->getEmail(),                                              # L - Adresse e-mail du destinataire (F)
                "R",                                                             # M - Type Collect (R = Relais)
                $collectPointId,                                                 # N - Id Relais Collecte
                "FR",                                                            # O - Code Pays Collecte
                "R",                                                             # P - Type Livraison (R = Relais)
                $order->getMondialRelayPickupPointCode(),                        # Q - Id Relais de Livraison
                "FR",                                                            # R - Code Pays du Relais de Livraison (FR = France)
                "24R",                                                           # S - Mode de livraison (24R = Point Relais)
                "FR",                                                            # T - Code Langue du Destinataire
                "1",                                                             # U - Nombre de colis
                $orderWeight,                                                    # V - Poids en grammes
            ];
            $recordWithNormalizedFields = array_map("self::normalizeForExport", $record);
            $recordWithEmptyFields = array_merge($recordWithNormalizedFields, array_fill(0, 22, ""));
            $csv->insertOne($recordWithEmptyFields);
        }

        $csvAsString = $csv->toString();
        $csvWithoutQuotes = str_replace('"', '', $csvAsString);

        return new Response(
            content: $csvWithoutQuotes,
            headers: [
                "Content-Type" => "text/csv",
                "Content-Disposition" => "attachment; filename=\"commandes.csv\"",
            ]
        );
    }

    /**
     * @throws CannotInsertRecord
     * @throws Exception
     * @throws PropelException
     * @throws InvalidArgument
     */
    public function exportForColissimoAction(
        CurrentUser        $currentUser,
        CurrentSite        $currentSite,
        QueryParamsService $queryParams,
    ): Response
    {
        $currentUser->authAdmin();

        $queryParams->parse([
            "status" => ["type" => "numeric", "default" => 0],
            "payment" => ["type" => "string", "default" => null],
            "shipping" => ["type" => "string", "default" => "colissimo"],
            "query" => ["type" => "string", "default" => ""],
        ]);

        $orderQuery = OrderQuery::create()
            ->filterByCancelDate(null, Criteria::ISNULL)
            ->filterByPaymentDate(null, Criteria::ISNOTNULL)
            ->filterByShippingDate(null, Criteria::ISNULL)
            ->joinWithShippingOption()
            ->useShippingOptionQuery()
            ->filterByType("colissimo")
            ->endUse();

        $csv = Writer::createFromString();
        $csv->setDelimiter(';');

        $shippingPackagingWeight = $currentSite->getOption("shipping_packaging_weight");
        $orders = $orderQuery->find();
        foreach ($orders as $order) {
            $orderWeight = $order->getTotalWeight() + $shippingPackagingWeight;
            $formattedPhone = preg_replace('/[^\d+]/', '', $order->getPhone());

            $record = [
                $order->getLastname(),           # A - Nom du destinataire
                $order->getFirstname(),          # B - Prénom du destinataire
                $order->getAddress1(),           # C - Adresse 1
                $order->getAddress2(),           # D - Adresse 2
                $order->getPostalcode(),         # E - Code postal
                $order->getCity(),               # F - Commune du destinataire
                $order->getCountry()->getCode(), # G - Code pays du destinataire
                $orderWeight,                    # H - Poids
                $order->getEmail(),              # I - Adresse e-mail du destinataire
                $formattedPhone,                 # J - Téléphone du destinataire
            ];

            $csv->insertOne($record);
        }

        $csvAsString = $csv->toString();
        $csvWithoutQuotes = str_replace('"', '', $csvAsString);

        $today = new DateTime();
        $fileName = "commandes-colissimo-{$today->format("Y-m-d")}.csv";
        return new Response(
            content: $csvWithoutQuotes,
            headers: [
                "Content-Type" => "text/csv",
                "Content-Disposition" => "attachment; filename=\"$fileName\"",
            ]
        );
    }

    private static function normalizeForExport(?string $string): string
    {
        if ($string === null) {
            return "";
        }

        return (new StringService($string))->normalize()->uppercase()->get();
    }
}