<?php

namespace Command;

use Model\Country;
use Model\Job;
use Model\Right;
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

        $site = new Site();
        $site->setTitle("Librairie Ys");
        $site->setDomain("www.librys.fr");
        $site->setContact("contact@librys.fr");
        $output->writeln(["Inserted site: Librairie Ys"]);

        $admin = new User();
        $admin->setEmail("admin@librys.fr");
        $admin->setUsername("admin");
        $admin->setPassword("$2y$10\$uBSKxkPvkt8UQM8B98u61e.GGOEdLHzU470Nw4X17zq05i1wIYftm");
        $admin->save();
        $right = new Right();
        $right->setSite($site);
        $right->setUser($admin);
        $right->save();
        $output->writeln(["Inserted user: admin@librys.fr (password: password)"]);

        $user = new User();
        $user->setEmail("user@librys.fr");
        $user->setUsername("user");
        $user->setPassword("$2y$10\$uBSKxkPvkt8UQM8B98u61e.GGOEdLHzU470Nw4X17zq05i1wIYftm");
        $user->save();
        $output->writeln(["Inserted user: user@librys.fr (password: password)"]);

        $job1 = new Job();
        $job1->setName("Auteur·trice");
        $job1->save();
        $job2 = new Job();
        $job2->setName("Traducteur·trice");
        $job2->save();
        $job3 = new Job();
        $job3->setName("Illustrateur·trice");
        $job3->save();
        $output->writeln(["Inserted jobs: Auteur·trice, Traducteur·trice, Illustrateur·trice"]);

        $country = new Country();
        $country->setName("France");
        $country->save();
        $output->writeln(["Inserted country: France"]);

        $output->writeln(["Seeds generated!"]);
        return 0;
    }
}