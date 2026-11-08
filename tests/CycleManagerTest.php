<?php
/*
 * Copyright (C) 2026 Clément Latzarus
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

use Biblys\Exception\EntityAlreadyExistsException;
use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Test\ModelFactory;
use Model\CycleQuery;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . "/setUp.php";

class CycleManagerTest extends TestCase
{
    protected function setUp(): void
    {
        LegacyCodeHelper::setGlobalSite(ModelFactory::createSite());
        CycleQuery::create()->deleteAll();
    }

    public function testCreateRefusesACycleWhoseNameGivesAnExistingUrl(): void
    {
        // given
        $cycleManager = new CycleManager();
        $cycleManager->create(["cycle_name" => "Les Livres Magiques de Xanth"]);

        // when
        $exception = null;
        try {
            $cycleManager->create(["cycle_name" => "Les Livres Magiques de  Xanth "]);
        } catch (EntityAlreadyExistsException $exception) {
        }

        // then
        $this->assertInstanceOf(EntityAlreadyExistsException::class, $exception);
        $this->assertEquals(1, CycleQuery::create()->count());
    }

    public function testUpdateRefusesAnUrlUsedByAnotherCycle(): void
    {
        // given
        $cycleManager = new CycleManager();
        $cycleManager->create(["cycle_name" => "Xanth"]);
        $other = $cycleManager->create(["cycle_name" => "Autre"]);
        $other->set("cycle_name", "Xanth ");

        // then
        $this->expectException(EntityAlreadyExistsException::class);

        // when
        $cycleManager->update($other);
    }
}
