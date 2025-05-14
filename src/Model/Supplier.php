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
     */
    public function getPublishers(): array
    {
        $publishers = [];
        $links = LinkQuery::create()
            ->filterByPublisherId(null, Criteria::ISNOTNULL)
            ->filterBySupplierId($this->getId())
            ->find();

        /**
         * @var Link $link
         */
        foreach ($links as $link) {
            $publisher = PublisherQuery::create()->findPk($link->getPublisherId());
            if ($publisher) {
                $publishers[] = $publisher;
            }
        }

        return $publishers;
    }
}
