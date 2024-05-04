<?php

namespace Command;

use Biblys\Contributor\Job;
use Biblys\Test\ModelFactory;
use Model\Article;
use Model\AxysApp;
use Model\BookCollection;
use Model\Country;
use Model\People;
use Model\Publisher;
use Model\Right;
use Model\Role;
use Model\ShippingFee;
use Model\Site;
use Model\AxysAccount;
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

        $axysApp = new AxysApp();
        $axysApp->setName("Éditions Paronymie");
        $axysApp->setClientId("paronymie");
        $axysApp->setClientSecret("paronymie-secret");
        $axysApp->setRedirectUri("http://localhost:8088/oauth/callback");
        $axysApp->save();
        $output->writeln(["Inserted Axys app: paronymie"]);

        // Admin
        $admin = new AxysAccount();
        $admin->setEmail("admin@paronymie.fr");
        $admin->setUsername("admin");
        $admin->setPassword("$2y$10\$uBSKxkPvkt8UQM8B98u61e.GGOEdLHzU470Nw4X17zq05i1wIYftm");
        $admin->save();
        $right = new Right();
        $right->setSite($site);
        $right->setAxysAccount($admin);
        $right->save();
        $output->writeln(["Inserted user: admin@paronymie.fr (password: password)"]);

        // Simple user
        $user = new AxysAccount();
        $user->setEmail("user@paronymie.fr");
        $user->setUsername("user");
        $user->setPassword("$2y$10\$uBSKxkPvkt8UQM8B98u61e.GGOEdLHzU470Nw4X17zq05i1wIYftm");
        $user->save();
        $output->writeln(["Inserted user: user@paronymie.fr (password: password)"]);

        // Publisher
        $publisher = new Publisher();
        $publisher->setName("Les Éditions Paronymie");
        $publisher->setUrl("les-editions-paronymie");
        $publisher->save();

        // User with publisher right
        $publisherUser = new AxysAccount();
        $publisherUser->setEmail("publisher@paronymie.fr");
        $publisherUser->setUsername("publisher");
        $publisherUser->setPassword("$2y$10\$uBSKxkPvkt8UQM8B98u61e.GGOEdLHzU470Nw4X17zq05i1wIYftm");
        $publisherUser->save();

        $right = new Right();
        $right->setAxysAccount($publisherUser);
        $right->setPublisher($publisher);
        $right->save();
        $output->writeln(["Inserted user: publisher@paronymie.fr (password: password)"]);

        $country = new Country();
        $country->setName("France");
        $country->save();
        $output->writeln(["Inserted country: France"]);

        // Site
        $shippingFee = new ShippingFee();
        $shippingFee->setSiteId($site->getId());
        $shippingFee->setMode("Offerts");
        $shippingFee->setType("normal");
        $shippingFee->setZone("ALL");
        $shippingFee->setFee(1);
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
        $output->writeln(["Inserted book collection: Aymeric Buvard"]);

        // Article
        $article = ModelFactory::createArticle(
            title: "L'Ordure du jeu",
            authors: [$contributor],
            publisher: $publisher,
            collection: $collection,
        );
        $output->writeln(["Inserted book collection: L'Ordure du jeu"]);

        // Stock item
        ModelFactory::createStockItem(
            site: $site,
            article: $article,
            sellingPrice: 999,
        );
        $output->writeln(["Inserted stock item for L'Ordure du jeu"]);

        $output->writeln(["Seeds generated!"]);
        return 0;
    }
}