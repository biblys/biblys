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


namespace Command;

use Biblys\Database\Connection;
use Biblys\Service\Config;
use Biblys\Service\Slug\SlugService;
use Biblys\Test\ModelFactory;
use Model\Publisher;
use Model\Right;
use Model\ShippingOption;
use Model\Site;
use Model\User;
use Propel\Runtime\Exception\PropelException;
use StockManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateSeedsCommand extends Command
{
    protected static $defaultName = 'db:seed';

    public const CATALOG_ARTICLES = [
        ["title" => "Chaussons d'ours", "firstName" => "Laetitia", "lastName" => "Mani", "ean" => "9781000000016", "price" => 1200],
        ["title" => "Sous-sol", "firstName" => "Matt", "lastName" => "Yassenar", "ean" => "9781000000023", "price" => 1550],
        ["title" => "Papeete", "firstName" => "Lili", "lastName" => "Calvaire", "ean" => "9781000000030", "price" => 900],
        ["title" => "Au-revoir Mao", "firstName" => "Perle", "lastName" => "Maître", "ean" => "9781000000047", "price" => 1800],
        ["title" => "Le Serpent sur la butte aux pommes", "firstName" => "Gérard", "lastName" => "Ferrori", "ean" => "9781000000054", "price" => 2200],
        ["title" => "Le lard français pendant la guerre", "firstName" => "Alexei", "lastName" => "Gémit", "ean" => "9781000000061", "price" => 1450],
        ["title" => "La Tarte et le terroir", "firstName" => "Michelou", "lastName" => "Elbecq", "ean" => "9781000000078", "price" => 1990],
        ["title" => "Troie, dame bruissante", "firstName" => "Mariette", "lastName" => "D'Ail", "ean" => "9781000000085", "price" => 1650],
    ];

    protected function configure(): void
    {
        $this->setDescription('Generates development seeds');
    }

    /**
     * @throws PropelException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(["Generating seeds…"]);

        // Site
        $site = new Site();
        $site->setName("paronymie");
        $site->setTitle("Éditions Paronymie");
        $site->setDomain("paronymie.fr");
        $site->setContact("contact@paronymie.fr");
        $site->setTva("fr");
        $site->save();
        $output->writeln(["Inserted site: Éditions Paronymie"]);

        // Admin
        $admin = new User();
        $admin->setEmail("admin@paronymie.fr");
        $admin->save();

        $right = new Right();
        $right->setUser($admin);
        $right->setIsAdmin(true);
        $right->save();
        $output->writeln(["Inserted user: admin@paronymie.fr"]);

        // Simple user
        $user = new User();
        $user->setEmail("user@paronymie.fr");
        $user->save();
        $output->writeln(["Inserted user: user@paronymie.fr"]);

        // Publisher
        $publisher = new Publisher();
        $publisher->setName("Les Éditions Paronymie");
        $publisher->setUrl("les-editions-paronymie");
        $publisher->save();

        // User with publisher right
        $publisherUser = new User();
        $publisherUser->setEmail("publisher@paronymie.fr");
        $publisherUser->save();

        $right = new Right();
        $right->setUser($publisherUser);
        $right->setPublisher($publisher);
        $right->save();
        $output->writeln(["Inserted user: publisher@paronymie.fr"]);

        // Site
        $shippingFee = new ShippingOption();
        $shippingFee->setMode("Expédition France et Monde");
        $shippingFee->setType("normal");
        $shippingFee->setShippingZoneId(1);
        $shippingFee->setFee(300);
        $shippingFee->save();
        $output->writeln(["Inserted shipping fee: Offerts"]);

        // Collection
        $collection = ModelFactory::createCollection(
            publisher: $publisher,
            name: "Lis tes ratures",
        );
        $output->writeln(["Inserted book collection: Lis tes ratures"]);

        // Contributor
        $contributor = ModelFactory::createContributor(
            firstName: "Aymeric",
            lastName: "Buvard",
        );
        $output->writeln(["Inserted contributor: Aymeric Buvard"]);

        // Article
        $article = ModelFactory::createArticle(
            title: "L'Ordure du jeu",
            authors: [$contributor],
            publisher: $publisher,
            collection: $collection,
        );
        $output->writeln(["Inserted article: L'Ordure du jeu"]);

        // Stock item
        ModelFactory::createStockItem(
            article: $article,
            sellingPrice: 999,
        );
        $output->writeln(["Inserted stock item for L'Ordure du jeu"]);

        // Catalog articles, each with its author and one new stock item
        $slugService = new SlugService();
        foreach (self::CATALOG_ARTICLES as $catalogArticle) {
            $author = ModelFactory::createContributor(
                firstName: $catalogArticle["firstName"],
                lastName: $catalogArticle["lastName"],
            );

            $authorSlug = $slugService->slugify($author->getFullName());
            $titleSlug = $slugService->slugify($catalogArticle["title"]);

            $catalogArticleModel = ModelFactory::createArticle(
                title: $catalogArticle["title"],
                authors: [$author],
                ean: $catalogArticle["ean"],
                url: "$authorSlug/$titleSlug",
                price: $catalogArticle["price"],
                publisher: $publisher,
                collection: $collection,
            );

            ModelFactory::createStockItem(
                article: $catalogArticleModel,
                sellingPrice: $catalogArticle["price"],
            );

            $output->writeln(["Inserted article with stock item: {$catalogArticle["title"]}"]);
        }

        // Order, shipped with the seeded shipping option so the invoice shows a shipping line
        $order = ModelFactory::createOrder(
            shippingOption: $shippingFee,
            amount: 999,
            amountToBePaid: 1299,
            shippingCost: 300,
        );

        // Stock Item, weighed so the invoice shows a total weight
        $orderedStockItem = ModelFactory::createStockItem(
            article: $article,
            order: $order,
            sellingPrice: 999,
            weight: 450,
        );

        // Compute and persist VAT on the sold stock item, mirroring what happens on a real sale
        require_once __DIR__ . "/../../inc/autoload-entity.php";
        if (!isset($GLOBALS["_SQL"])) {
            Connection::init(Config::load());
        }
        $stockManager = new StockManager();
        $stock = $stockManager->calculateTax($stockManager->getById($orderedStockItem->getId()));
        $stockManager->update($stock);

        $output->writeln(["Seeds generated!"]);
        return 0;
    }
}