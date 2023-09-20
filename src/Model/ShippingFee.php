<?php

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
