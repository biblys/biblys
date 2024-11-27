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

        $config = Config::load();
        $db = new Database($config->get("db"));
        $db->reset();

        $output->writeln(["Database resetted!"]);
        return 0;
    }
}