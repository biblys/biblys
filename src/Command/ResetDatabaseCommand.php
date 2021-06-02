<?php

namespace Command;

use Biblys\Database\Database;
use Biblys\Service\Config;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ResetDatabaseCommand extends Command
{
    protected static $defaultName = 'db:reset';

    protected function configure(): void
    {
        $this->setDescription('Clears database and execute migrations');
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(["Resetting database…"]);

        $config = new Config();
        $db = new Database($config->get("db"));
        $db->reset();
        $db->migrate();

        $output->writeln(["Database resetted!"]);
        return 0;
    }
}