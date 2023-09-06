<?php

namespace Biblys\Service\Watermarking;

class WatermarkedFile
{
    private string $format;
    private string $transactionToken;
    private string $transactionId;

    public function __construct($format, $transactionToken, $transactionId)
    {
        $this->format = $format;
        $this->transactionToken = $transactionToken;
        $this->transactionId = $transactionId;
    }

    /**
     * @return string
     */
    public function getFormatName(): string
    {
        return $this->format;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return "https://dl.lemonink.co/transactions/$this->transactionToken/$this->transactionId.$this->format";
    }
}