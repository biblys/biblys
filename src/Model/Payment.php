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

use DateTime;
use Model\Base\Payment as BasePayment;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

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

    public const MODE_CASH = 'cash';
    public const MODE_CHECK = 'cheque';
    public const MODE_CARD = 'card';
    public const MODE_TRANSFER = 'transfer';
    public const MODE_PAYPAL = 'paypal';
    public const MODE_PAYPLUG = 'payplug';
    public const MODE_STRIPE = 'stripe';
    public const MODE_EXCHANGE = 'exchange';

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

    /**
     * @throws PropelException
     */
    public function isExecuted(): bool
    {
        return $this->getExecuted() !== null;
    }

    /**
     * @throws PropelException
     */
    public function preInsert(?ConnectionInterface $con = null): bool
    {
        if (!parent::preInsert($con)) {
            return false;
        }

        // Le comportement timestampable renseigne payment_created APRÈS preInsert.
        // On fige donc la date ici si elle n'est pas déjà fixée, pour que le hash
        // porte sur la valeur réellement stockée (timestampable respectera ensuite
        // cette valeur via son test !isColumnModified).
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new DateTime());
        }

        $previous = PaymentQuery::create()
            ->orderById(Criteria::DESC)
            ->findOne($con);
        $previousHash = $previous?->getHash() ?? "";

        $this->setHashVersion(PaymentHash::CURRENT_VERSION);
        $this->setHash(PaymentHash::compute(
            PaymentHash::CURRENT_VERSION,
            $this->getAmount(),
            $this->getCreatedAt(),
            $this->getOrderId(),
            $previousHash
        ));

        return true;
    }
}
