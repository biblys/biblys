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


namespace Model;

use DateTimeInterface;
use InvalidArgumentException;

final class PaymentHash
{
    public const CURRENT_VERSION = 1;

    public static function compute(
        int $version,
        ?int $amount,
        ?DateTimeInterface $createdAt,
        ?int $orderId,
        string $previousHash
    ): string {
        return match ($version) {
            1 => self::computeV1($amount, $createdAt, $orderId, $previousHash),
            default => throw new InvalidArgumentException("Unknown hash version: $version"),
        };
    }

    // APPEND-ONLY : ne jamais modifier ni supprimer.
    private static function computeV1(
        ?int $amount,
        ?DateTimeInterface $createdAt,
        ?int $orderId,
        string $previousHash
    ): string {
        $parts = [
            $amount ?? "",
            $createdAt?->format("Y-m-d H:i:s") ?? "",
            $orderId ?? "",
            $previousHash,
        ];
        return hash("sha256", implode("|", $parts));
    }
}
