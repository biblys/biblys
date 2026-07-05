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

        // La colonne payment_created est un TIMESTAMP sans précision fractionnaire :
        // seule la seconde entière est stockée. Or l'arrondi des sous-secondes au
        // stockage dépend du moteur (MariaDB tronque, MySQL 8 arrondit à la seconde
        // supérieure dès .5). Si on hachait la date brute (avec microsecondes), le
        // hash porterait sur une valeur différente de celle réellement persistée sur
        // MySQL 8, faisant échouer toute vérification d'intégrité future. On fige
        // donc ici la date à la seconde entière, AVANT le calcul du hash, afin que la
        // valeur hachée soit strictement identique à la valeur stockée quel que soit
        // le moteur. On reconstruit la date dans le même fuseau horaire pour ne pas
        // décaler l'heure murale.
        $createdAt = $this->getCreatedAt();
        $this->setCreatedAt(DateTime::createFromFormat(
            "Y-m-d H:i:s",
            $createdAt->format("Y-m-d H:i:s"),
            $createdAt->getTimezone()
        ));

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
