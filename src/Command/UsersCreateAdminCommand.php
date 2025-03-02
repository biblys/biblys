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

use Biblys\Service\CurrentSite;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Usecase\AddAdminUsecase;
use Usecase\BusinessRuleException;

class  UsersCreateAdminCommand extends Command
{
    protected static $defaultName = "users:create-admin";

    public function __construct(
        private readonly CurrentSite $currentSite,
        string                       $name = null,
    )
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setDescription("Créer un utilisateur avec des droits d’admin à partir d’une adresse e-mail.");

        $this->addArgument('email', InputArgument::REQUIRED, 'Adresse e-mail de l’utilisateur.');
    }

    /**
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $userEmail = $input->getArgument('email');

        try {
            $usecase = new AddAdminUsecase($this->currentSite);
            list($userWasCreated, $user) = $usecase->execute($userEmail);

            if ($userWasCreated) {
                $output->writeln("Un compte utilisateur a été créé pour $userEmail.");
            }

            $output->writeln("Un accès administrateur a été ajouté pour le compte $userEmail.");

            return Command::SUCCESS;
        } catch (BusinessRuleException $exception) {
            $output->writeln($exception->getMessage());

            return Command::FAILURE;
        }
    }
}