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

use Biblys\Exception\CannotDeleteEntityWithImage;
use Model\Base\Stock as BaseStock;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

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

    /**
     * @throws PropelException
     */
    public function isLost(): bool
    {
        return $this->getLostDate() !== null;
    }

    /**
     * @throws PropelException
     * @throws CannotDeleteEntityWithImage
     */
    public function preDelete(?ConnectionInterface $con = null): bool
    {
        $imageExists = ImageQuery::create()->filterByStockItem($this)->exists();
        if ($imageExists) {
            throw new CannotDeleteEntityWithImage("Impossible de supprimer l'exemplaire car il a une photo associée.");
        }

        return true;
    }
}
