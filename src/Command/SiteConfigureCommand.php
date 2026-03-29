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
use Usecase\ConfigureSiteUsecase;

class SiteConfigureCommand extends Command
{
    protected static $defaultName = "site:configure";

    public function __construct(string $name = "site:configure")
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

        $name = $input->getArgument('name');
        $url = $input->getArgument('url');
        $email = $input->getArgument('email');

        $nameIsValid = false;
        $urlIsValid = false;
        $emailIsValid = false;

        do {

            do {
                $name = $io->ask("Nom du site (par ex. Éditions Paronymie)", $name);
                if (empty($name)) {
                    $io->error("Le nom du site ne peut pas être vide.");
                    continue;
                }
                $nameIsValid = true;
            } while (!$nameIsValid);

            do {
                $url = $io->ask("Adresse URL du site (par ex. https://paronymie.fr)", $url);
                if (!filter_var($url, FILTER_VALIDATE_URL)) {
                    $io->error("L'URL $url n'est pas valide. L'URL avoir la forme https://example.org.");
                    continue;
                }
                $urlIsValid = true;
            } while (!$urlIsValid);

            do {
                $email = $io->ask("Adresse de contact (par ex. contact@paronymie.fr)", $email);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $io->error("L'adresse e-mail $email n'est pas valide. L'adresse avoir la forme contact@example.org.");
                    continue;
                }
                $emailIsValid = true;
            } while (!$emailIsValid);

            $io->info("Le site $name va être créé avec l‘URL $url et l'email $email.");

            $confirmed = $io->confirm("Continuer ?");
            if (!$confirmed) {
                continue;
            }

            $confirmed = true;

        } while (!$confirmed);


        try {
            $usecase = new ConfigureSiteUsecase();
            $usecase->execute($name, $url, $email);

            $io->success("Le site $name a été créé avec succès.");

            $io->text("Il sera accessible à l'adresse $url.");

            return Command::SUCCESS;
        } catch (BusinessRuleException $exception) {
            $io->error($exception->getMessage());

            return Command::FAILURE;
        }
    }
}