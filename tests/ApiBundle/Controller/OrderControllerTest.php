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
use Biblys\Service\QueryParamsService;
use Biblys\Test\ModelFactory;
use League\Csv\CannotInsertRecord;
use League\Csv\Exception;
use Mockery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__ . "/../../setUp.php";

class OrderControllerTest extends TestCase
{
    /**
     * @throws PropelException
     * @throws CannotInsertRecord
     * @throws Exception
     * @throws \Exception
     */
    public function testExportAction()
    {
        // given
        $controller = new OrderController();

        $site = ModelFactory::createSite();
        $shippingFee = ModelFactory::createShippingFee(site: $site, type: "mondial-relay");
        $order = ModelFactory::createOrder(
            site: $site,
            shippingFee: $shippingFee,
            postalCode: "02330",
            city: "Plymouth",
            mondialRelayPickupPointCode: "123456",
        );
        ModelFactory::createStockItem(order: $order, weight: 123);
        ModelFactory::createStockItem(order: $order, weight: 456);

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentSite->shouldReceive("getOption")->with("shipping_packaging_weight")->andReturn("421");
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin");
        $config = new Config(["mondial_relay" => ["id_relais_collecte" => "654321"]]);
        $queryParams = Mockery::mock(QueryParamsService::class);
        $queryParams->shouldReceive("parse")->with([
            "status" => ["type" => "numeric", "default" => 0],
            "payment" => ["type" => "string"],
            "shipping" => ["type" => "string"],
            "query" => ["type" => "string"],
        ]);
        $queryParams->shouldReceive("getInteger")->with("status")->andReturn(0);
        $queryParams->shouldReceive("get")->with("shipping")->andReturn("mondial-relay");

        // when
        $response = $controller->exportAction($currentUser, $currentSite, $config, $queryParams);

        // then
        // https://www.mondialrelay.fr/media/62811/import-de-fichiers-csv-v3.1.pdf
        $record = [
            $order->getCustomerId(),   # A - Numéro de client (F)
            $order->getId(),           # B - Numéro de commande (F)
            "Silas Coade",             # C - Adresse de livraison (Nom du client final) (O)
            "",                        # D - Adresse de livraison (Complément du nom) (F)
            "1 rue de la Fissure",     # E - Adresse du destinataire (Numéro + Rue) (O)
            "Appartement 2",           # F - Adresse du destinataire (Complément d'adresse) (F)
            "Plymouth",                # G - Ville du destinataire (O)
            "02330",                   # H - Code Postal du destinataire (O)
            "FR",                      # I - Pays du destinataire (O)
            "0601020304",              # J - Téléphone fixe du destinataire (F)
            "",                        # K - Téléphone cellulaire (F)
            "silas.coade@example.net", # L - Adresse e-mail du destinataire (F)
            "R",                       # M - Type Collect (R = Relais)
            "654321",                  # N - Id Relais Collecte
            "FR",                      # O - Code Pays Collecte
            "R",                       # P - Type Livraison (R = Relais)
            "123456",                  # Q - Id Relais de Livraison
            "FR",                      # R - Code Pays du Relais de Livraison (FR = France)
            "24R",                     # S - Mode de livraison (24R = Point Relais)
            "FR",                      # T - Code Langue du Destinataire
            "1",                       # U - Nombre de colis
            "1000",                     # V - Poids en grammes
        ];
        $recordWithEmptyFields = array_merge($record, array_fill(0, 22, ""));
        $expectedLine = implode(";", $recordWithEmptyFields) . "\n";
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals($expectedLine, $response->getContent());
    }
}
