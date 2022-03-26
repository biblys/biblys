<?php

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
        ];
    }
}
