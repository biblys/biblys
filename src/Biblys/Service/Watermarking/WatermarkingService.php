<?php

namespace Biblys\Service\Watermarking;

use Biblys\Service\Config;
use LemonInk\Client;
use LemonInk\Models\Transaction;

/**
 * @property Client $client
 */
class WatermarkingService
{
    private bool $isConfigured = false;

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