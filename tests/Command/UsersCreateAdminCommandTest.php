<?php
/*
 * Copyright (C) 2025 Clément Latzarus
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
use Biblys\Test\ModelFactory;
use Exception;
use Mockery;
use Model\RightQuery;
use Model\UserQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UsersCreateAdminCommandTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function setUp(): void
    {
        UserQuery::create()->deleteAll();
        RightQuery::create()->deleteAll();
    }

    /**
     * @throws Exception
     */
    public function testCommandCreatesAdminUser(): void
    {
        // given
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);
        $command = new UsersCreateAdminCommand($currentSite);
        $input = Mockery::mock(InputInterface::class);
        $input->shouldReceive("getArgument")->with("email")->andReturn("new-admin@example.org");
        $output = Mockery::mock(OutputInterface::class);
        $output->shouldReceive("writeln")->times(2);

        // when
        $result = $command->execute($input, $output);

        // then
        $this->assertEquals(Command::SUCCESS, $result);
        $user = UserQuery::create()->findOneByEmail("new-admin@example.org");
        $this->assertNotNull($user);
        $this->assertTrue(RightQuery::create()->isUserAdmin($user));
    }
}
