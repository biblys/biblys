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