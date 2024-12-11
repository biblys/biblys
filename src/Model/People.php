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

use Biblys\Service\StringService;
use Model\Base\People as BasePeople;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for representing a row from the 'people' table.
 *
 * Intervenants
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class People extends BasePeople
{
    /**
     * @deprecated People->getName is deprecated, use People->getFullName instead.
     */
    public function getName(): string
    {
        trigger_deprecation(
            "biblys/biblys",
            "3.1.1",
            "People->getName is deprecated, use People->getFullName instead."
        );

        return $this->getFullName();
    }

    /**
     * @deprecated People->getAlpha is deprecated, use People->getAlphabeticalName instead.
     */
    public function getAlpha(): string
    {
        trigger_deprecation(
            "biblys/biblys",
            "3.1.1",
            "People->getAlpha is deprecated, use People->getAlphabeticalName instead."
        );

        return $this->getFullName();
    }

    public function getFullName(): string
    {
        return trim($this->getFirstName()." ".$this->getLastName());
    }

    public function getAlphabeticalName(): string
    {
        return trim($this->getLastName()." ".$this->getFirstName());
    }
}
