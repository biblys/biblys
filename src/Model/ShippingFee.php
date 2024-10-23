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

use Model\Base\ShippingFee as BaseShippingFee;

/**
 * Skeleton subclass for representing a row from the 'shipping' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class ShippingFee extends BaseShippingFee
{

    public function isCompliantWithFrenchLaw(): bool
    {
        if ($this->getType() === 'magasin') {
            return true;
        }

        if ($this->getMinAmount() >= 3500) {
            return true;
        }

        if ($this->getFee() >= 300) {
            return true;
        }

        return false;
    }
}
