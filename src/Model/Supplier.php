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

use Model\Base\Supplier as BaseSupplier;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;

/**
 * Skeleton subclass for representing a row from the 'suppliers' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Supplier extends BaseSupplier
{
    /**
     * @return Publisher[]
     * @throws PropelException
     */
    public function getPublisherIds(): array
    {
        $links = LinkQuery::create()
            ->select("publisher_id")
            ->filterByPublisherId(null, Criteria::ISNOTNULL)
            ->filterBySupplierId($this->getId())
            ->find();

        return $links->getData();
    }

    /**
     * @return Publisher[]
     * @throws PropelException
     */
    public function getPublishers(): array
    {
        $publisherIds = $this->getPublisherIds();

        return PublisherQuery::create()
            ->filterById($publisherIds)
            ->orderByName()
            ->find()
            ->getData();
    }
}
