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


namespace Model;

use Model\Base\Payment as BasePayment;

/**
 * Skeleton subclass for representing a row from the 'payments' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Payment extends BasePayment
{

    private const MODE_CASH = 'cash';
    private const MODE_CHECK = 'cheque';
    private const MODE_CARD = 'card';
    private const MODE_TRANSFER = 'transfer';
    private const MODE_PAYPAL = 'paypal';
    private const MODE_PAYPLUG = 'payplug';
    private const MODE_STRIPE = 'stripe';
    private const MODE_EXCHANGE = 'exchange';

    public static function getModes(): array
    {
        return [
            self::MODE_CASH,
            self::MODE_CHECK,
            self::MODE_CARD,
            self::MODE_TRANSFER,
            self::MODE_PAYPAL,
            self::MODE_PAYPLUG,
            self::MODE_STRIPE,
            self::MODE_EXCHANGE,
        ];
    }
}
