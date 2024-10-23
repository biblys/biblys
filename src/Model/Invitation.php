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
use Model\Base\Invitation as BaseInvitation;
use Propel\Runtime\Exception\PropelException;
use RandomLib\Factory;

/**
 * Skeleton subclass for representing a row from the 'invitations' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Invitation extends BaseInvitation
{

    /**
     * @throws PropelException
     */
    public function isConsumed(): bool
    {
        return $this->getConsumedAt() !== null;
    }

    /**
     * @throws PropelException
     */
    public function hasExpired(): bool
    {
        return $this->getExpiresAt() < new DateTime("now");
    }

    public static function generateCode(): string
    {
        $randomFactory = new Factory();
        $generator = $randomFactory->getMediumStrengthGenerator();
        return $generator->generateString(8, "ABCDEFGHJKLMNPQRSTUVWXYZ23456789");
    }
}
