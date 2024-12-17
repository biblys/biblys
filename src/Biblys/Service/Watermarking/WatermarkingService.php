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
use LemonInk\Models\Transaction;

/**
 * @property Client $client
 */
class WatermarkingService
{
    private bool $isConfigured = false;
    private Client $client;

    public function __construct(Client $client, bool $isConfigured)
    {
        if (!$isConfigured) {
            return;
        }

        $this->client = $client;
        $this->isConfigured = true;
    }

    public function isConfigured(): bool
    {
        return $this->isConfigured;
    }

    public function watermark(string $masterId, string $text): Transaction
    {
        $transaction = new Transaction();
        $transaction->setMasterId($masterId);
        $transaction->setWatermarkValue($text);

        $this->client->save($transaction);

        return $transaction;
    }

    /**
     * @return WatermarkedFile[]
     */
    public function getFiles(string $masterId, string $transactionId, string $transactionToken): array
    {
        $master = $this->client->find("master", $masterId);
        $formats = $master->getFormats();
        return array_map(function($format) use($transactionToken, $transactionId) {
            return new WatermarkedFile($format, $transactionToken, $transactionId);
        }, $formats);
    }
}