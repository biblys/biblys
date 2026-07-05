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

use DateTime;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class PaymentHashTest extends TestCase
{
    /** Golden vector — genesis (previousHash vide). Fige la v1. */
    public function testVersion1GenesisIsFrozen(): void
    {
        $hash = PaymentHash::compute(1, 1500, new DateTime("2026-01-01 12:00:00"), 42, "");
        $this->assertSame(
            "c9cc9f071d5e4553561f1c82ba04ee6dae4e75148d5550c0d247b4d7c7e8bff3",
            $hash
        );
    }

    /** Golden vector — maillon chaîné sur le genesis. */
    public function testVersion1ChainsOnPreviousHash(): void
    {
        $previous = "c9cc9f071d5e4553561f1c82ba04ee6dae4e75148d5550c0d247b4d7c7e8bff3";
        $hash = PaymentHash::compute(1, 900, new DateTime("2026-01-02 09:30:00"), 43, $previous);
        $this->assertSame(
            "6a902e886212458b3697a76503f48c625cc4ed89380ad3613b05d93a5fa218c7",
            $hash
        );
    }

    /** amount null et orderId null sont sérialisés en chaîne vide. */
    public function testVersion1SerializesNullFieldsAsEmptyString(): void
    {
        $previous = "c9cc9f071d5e4553561f1c82ba04ee6dae4e75148d5550c0d247b4d7c7e8bff3";
        $hash = PaymentHash::compute(1, null, new DateTime("2026-01-03 08:00:00"), null, $previous);
        $this->assertSame(
            "2e94c203350e13789172fccb3fb30a2120d7d222576c7073670a575c802f81ce",
            $hash
        );
    }

    public function testUnknownVersionThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        PaymentHash::compute(99, 1500, new DateTime("2026-01-01 12:00:00"), 42, "");
    }
}
