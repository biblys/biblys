<?php

namespace Model;

use Model\Base\Stock as BaseStock;

/**
 * Skeleton subclass for representing a row from the 'stock' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Stock extends BaseStock
{

    public function isWatermarked(): bool
    {
        if ($this->getLemoninkTransactionId() === null) {
            return false;
        }

        if ($this->getLemoninkTransactionToken() === null) {
            return false;
        }

        return true;
    }
}
