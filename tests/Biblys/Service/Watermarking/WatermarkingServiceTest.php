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


namespace Biblys\Service\Watermarking;

use LemonInk\Client;
use LemonInk\Models\Master;
use LemonInk\Models\Transaction;
use Mockery;
use PHPUnit\Framework\TestCase;

class WatermarkingServiceTest extends TestCase
{
    /**
     * #isConfigured
     */

    public function testIsConfiguredIfConfigured()
    {
        // given
        $service = new WatermarkingService(new Client("api_key"), true);

        // when
        $isConfigured = $service->isConfigured();

        // then
        $this->assertTrue($isConfigured);
    }

    public function testIsConfiguredIfNotConfigured()
    {
        // given
        $service = new WatermarkingService(new Client(null), false);

        // when
        $isConfigured = $service->isConfigured();

        // then
        $this->assertFalse($isConfigured);
    }

    /**
     * #watermark
     */

    public function testWatermark()
    {
        // given
        $expectedTransaction = new Transaction();
        $expectedTransaction->setMasterId("master_id");
        $expectedTransaction->setWatermarkValue("text");
        $client = Mockery::mock(Client::class);
        $client->shouldReceive("save")->once();
        $service = new WatermarkingService($client, true);

        // when
        $returnedTransaction = $service->watermark("master_id", "text");

        // then
        $this->assertEquals($expectedTransaction, $returnedTransaction);
    }

    /**
     * #getFiles
     */

    public function testGetFiles()
    {
        // given
        $exceptedFiles = [
            new WatermarkedFile("epub", "token", "id"),
            new WatermarkedFile("pdf", "token", "id"),
        ];
        $master = Mockery::mock(Master::class);
        $master->shouldReceive("getFormats")->once()->andReturn(["epub", "pdf"]);
        $client = Mockery::mock(Client::class);
        $client->shouldReceive("find")->once()->with("master", "master_id")
            ->andReturn($master);
        $service = new WatermarkingService($client, isConfigured: true);

        // when
        $returnedFiles = $service->getFiles("master_id", "id", "token");

        // then
        $this->assertEquals($exceptedFiles, $returnedFiles);
    }
}
