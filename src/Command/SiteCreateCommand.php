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

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Usecase\BusinessRuleException;
use Usecase\CreateSiteUsecase;

class  SiteCreateCommand extends Command
{
    protected static $defaultName = "site:create";

    public function __construct(string $name = "site:create")
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setDescription("Créer un nouveau site Biblys");

        $this->addArgument('name', InputArgument::OPTIONAL, 'Nom du site (ex: Éditions Example)');
        $this->addArgument('url', InputArgument::OPTIONAL, 'URL complète du site (ex: https://example.org)');
        $this->addArgument('email', InputArgument::OPTIONAL, 'Adresse e-mail de contact (ex: contact@example.org');
    }

    /**
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $name = $input->getArgument("name");
        if (!$name) {
            $name = $io->ask("Nom du site (par ex. Éditions Paronymie)");
        }

        $url = $input->getArgument("url");
        if (!$url) {
            $url = $io->ask("Adresse URL du site (par ex. https://paronymie.fr)");
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $output->writeln("Erreur : $url n'est pas une url valide. L'url avoir la forme https://example.org.");
            return Command::FAILURE;
        }

        $email = $input->getArgument("email");
        if (!$email) {
            $email = $io->ask("Adresse de contact (par ex. contact@paronymie.fr)");
        }

        $io->writeln("Nom du site: $name");
        $io->writeln("Adresse URL du site: $url");
        $io->writeln("Email de contact : $email");

        $confirm = $io->confirm("OK ?");
        if (!$confirm) {
            return Command::FAILURE;
        }

        try {
            $usecase = new CreateSiteUsecase();
            $usecase->execute($name, $url, $email);

            $output->writeln("Le site $name a été créé avec succès. Il sera accessible à l'adresse $url.");

            return Command::SUCCESS;
        } catch (BusinessRuleException $exception) {
            $output->writeln($exception->getMessage());

            return Command::FAILURE;
        }
    }
}