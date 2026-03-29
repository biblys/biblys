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
use Model\Site;
use Model\SiteQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class SiteConfigureCommandTest extends TestCase
{
    /** @var Site[]  */
    private array $sitesCreated = [];

    public function setUp(): void
    {
        $this->sitesCreated = [];
    }

    /**
     * @throws PropelException
     */
    public function testExecuteCreatesASite(): void
    {
        // given
        $command = new SiteConfigureCommand();
        $commandTester = new CommandTester($command);
        $commandTester->setInputs(["yes"]);

        // when
        $commandTester->execute([
            "name" => "Éditions Example",
            "url" => "https://example.org",
            "email" => "contact@example.org",
        ]);

        // then
        $site = SiteQuery::create()->findOneByTitle("Éditions Example");
        $this->sitesCreated[] = $site;
        $this->assertNotNull($site);

        $this->assertEquals(Command::SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString(
            "Le site Éditions Example a été créé avec succès.",
            $commandTester->getDisplay()
        );
        $this->assertStringContainsString(
            "Il sera accessible à l'adresse https://example.org.",
            $commandTester->getDisplay()
        );
    }

    public function testExecuteFailsIfUrlIsInvalid(): void
    {
        // given
        $command = new SiteConfigureCommand();
        $commandTester = new CommandTester($command);

        // when
        $commandTester->execute([
            "name" => "Éditions Example",
            "url" => "example.org",
            "email" => "contact@example.org",
        ]);

        // then
        $this->assertEquals(Command::FAILURE, $commandTester->getStatusCode());
        $this->assertStringContainsString(
            "Erreur : example.org n'est pas une url valide. L'url avoir la forme https://example.org.",
            $commandTester->getDisplay()
        );
    }

    /**
     * @throws PropelException
     */
    public function testExecuteFailsIfSiteAlreadyExists(): void
    {
        // given
        ModelFactory::createSite(title: "Site existant");
        $command = new SiteConfigureCommand();
        $commandTester = new CommandTester($command);
        $commandTester->setInputs(["yes"]);

        // when
        $commandTester->execute([
            "name" => "Nouveau titre",
            "url" => "https://nouveau-site.org",
            "email" => "contact@nouveau-site.org",
        ]);

        // then
        $site = SiteQuery::create()->findOne();
        $this->sitesCreated[] = $site;
        $this->assertEquals(Command::SUCCESS, $commandTester->getStatusCode());
        $this->assertEquals("Nouveau titre", $site->getTitle());
        $this->assertEquals("https://nouveau-site.org", $site->getDomain());
    }
}