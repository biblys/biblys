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

use Biblys\Exception\InvalidEntityException;
use Model\Base\Redirection as BaseRedirection;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for representing a row from the 'redirections' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Redirection extends BaseRedirection
{

    /**
     * @throws InvalidEntityException
     */
    public function preSave(?ConnectionInterface $con = null): bool
    {
        if (!str_starts_with($this->getOldUrl(), "/")) {
            throw new InvalidEntityException("L'ancienne URL doit commencer par un slash (/).");
        }

        if (!str_starts_with($this->getNewUrl(), "/")) {
            throw new InvalidEntityException("La nouvelle URL doit commencer par un slash (/).");
        }

        if ($this->getOldUrl() === $this->getNewUrl()) {
            throw new InvalidEntityException("L'ancienne URL et la nouvelle URL doivent être différentes.");
        }

        return parent::preSave($con);
    }
}
