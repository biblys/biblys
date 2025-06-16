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

use Biblys\Test\ModelFactory;
use Model\Publisher;
use Model\Right;
use Model\ShippingOption;
use Model\Site;
use Model\User;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateSeedsCommand extends Command
{
    protected static $defaultName = 'db:seed';

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
        $site->save();
        $output->writeln(["Inserted site: Éditions Paronymie"]);

        // Admin
        $admin = new User();
        $admin->setEmail("admin@paronymie.fr");
        $admin->setSite($site);
        $admin->save();

        $right = new Right();
        $right->setSite($site);
        $right->setUser($admin);
        $right->setIsAdmin(true);
        $right->save();
        $output->writeln(["Inserted user: admin@paronymie.fr"]);

        // Simple user
        $user = new User();
        $user->setSite($site);
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
        $publisherUser->setSite($site);
        $publisherUser->setEmail("publisher@paronymie.fr");
        $publisherUser->save();

        $right = new Right();
        $right->setUser($publisherUser);
        $right->setPublisher($publisher);
        $right->setSite($site);
        $right->save();
        $output->writeln(["Inserted user: publisher@paronymie.fr"]);

        // Site
        $shippingFee = new ShippingOption();
        $shippingFee->setSiteId($site->getId());
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
            site: $site,
            article: $article,
            sellingPrice: 999,
        );
        $output->writeln(["Inserted stock item for L'Ordure du jeu"]);

        // Order
        $order = ModelFactory::createOrder(
            site: $site,
        );

        // Stock Item
        ModelFactory::createStockItem(
            site: $site,
            article: $article,
            order: $order,
            sellingPrice: 999,
        );

        $output->writeln(["Seeds generated!"]);
        return 0;
    }
}