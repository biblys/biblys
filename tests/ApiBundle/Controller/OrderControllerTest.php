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

use Biblys\Data\ArticleType;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\QueryParamsService;
use Biblys\Test\ModelFactory;
use DateTime;
use League\Csv\CannotInsertRecord;
use League\Csv\Exception;
use Mockery;
use Model\OrderQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__ . "/../../setUp.php";

class OrderControllerTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function setUp(): void
    {
        OrderQuery::create()->deleteAll();
    }

    /**
     * @throws PropelException
     * @throws CannotInsertRecord
     * @throws Exception
     * @throws \Exception
     */
    public function testExportForMondialRelayAction()
    {
        // given
        $controller = new OrderController();

        $site = ModelFactory::createSite();
        $shippingFee = ModelFactory::createShippingOption(site: $site, type: "mondial-relay");
        $order = ModelFactory::createOrder(
            site: $site,
            shippingOption: $shippingFee,
            firstName: "Éléonore",
            lastName: "Champollion",
            postalCode: "02330",
            city: "Plymouth",
            phone: "+33.6-01 02/03;04",
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
            "payment" => ["type" => "string", "default" => null],
            "shipping" => ["type" => "string", "default" => "mondial-relay"],
            "query" => ["type" => "string", "default" => ""],
        ]);
        $queryParams->shouldReceive("getInteger")->with("status")->andReturn(0);
        $queryParams->shouldReceive("get")->with("shipping")->andReturn("mondial-relay");

        // when
        $response = $controller->exportForMondialRelayAction($currentUser, $currentSite, $config, $queryParams);

        // then
        // https://www.mondialrelay.fr/media/62811/import-de-fichiers-csv-v3.1.pdf
        $record = [
            "CHAMPOLLI",               # A - Numéro de client (F)
            $order->getId(),           # B - Numéro de commande (F)
            "CHAMPOLLION ELEONORE",    # C - Adresse de livraison (Nom du client final) (O)
            "",                        # D - Adresse de livraison (Complément du nom) (F)
            "1 RUE DE LA FISSURE",     # E - Adresse du destinataire (Numéro + Rue) (O)
            "APPARTEMENT 2",           # F - Adresse du destinataire (Complément d'adresse) (F)
            "PLYMOUTH",                # G - Ville du destinataire (O)
            "02330",                   # H - Code Postal du destinataire (O)
            "FR",                      # I - Pays du destinataire (O)
            "+33601020304",            # J - Téléphone fixe du destinataire (F)
            "",                        # K - Téléphone cellulaire (F)
            "SILAS.COADE@EXAMPLE.NET", # L - Adresse e-mail du destinataire (F)
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
        $this->assertEquals($expectedLine, $response->getContent());
    }

    /**
     * @throws CannotInsertRecord
     * @throws PropelException
     * @throws Exception
     */
    public function testExportForColissimoAction()
    {
        // given
        $controller = new OrderController();

        $site = ModelFactory::createSite();
        $shippingFee = ModelFactory::createShippingOption(type: "colissimo");
        $order = ModelFactory::createOrder(
            site: $site,
            shippingOption: $shippingFee,
            firstName: "Éléonore",
            lastName: "Champollion",
            postalCode: "02330",
            city: "Plymouth",
            phone: "+33.6-01 02/03;04",
            paymentDate: new DateTime(),
        );
        $article1 = ModelFactory::createArticle(title: "Article 1");
        ModelFactory::createStockItem(article: $article1, order: $order, weight: 123);
        $article2 = ModelFactory::createArticle(title: "Article 2");
        ModelFactory::createStockItem(article: $article2, order: $order, weight: 456);
        $downloadableArticle = ModelFactory::createArticle(title: "Downloadable", typeId: ArticleType::EBOOK);
        ModelFactory::createStockItem(article: $downloadableArticle, order: $order);

        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentSite->shouldReceive("getOption")->with("shipping_packaging_weight")->andReturn("421");
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("authAdmin");
        $queryParams = Mockery::mock(QueryParamsService::class);
        $queryParams->shouldReceive("parse")->with([
            "status" => ["type" => "numeric", "default" => 0],
            "payment" => ["type" => "string", "default" => null],
            "shipping" => ["type" => "string", "default" => "colissimo"],
            "query" => ["type" => "string", "default" => ""],
        ]);

        // when
        $response = $controller->exportForColissimoAction($currentUser, $currentSite, $queryParams);

        // then
        $record = [
            "Champollion",           # A - Nom du destinataire
            "Éléonore",              # B - Prénom du destinataire
            '"1 rue de la Fissure"',   # C - Adresse 1
            '"Appartement 2"',         # D - Adresse 2
            "02330" ,                # E - Code postal
            "Plymouth",              # F - Commune du destinataire
            "FR",                    # G - Code pays du destinataire
            "1000",                  # H - Poids
            $order->getEmail(),      # I - Adresse e-mail du destinataire
            "+33601020304",          # J - Téléphone du destinataire
            '"Article 1, Article 2"',  # K - Liste des articles
        ];
        $expectedLine = implode(";", $record) . "\n";
        $this->assertEquals($expectedLine, $response->getContent());
    }
}
