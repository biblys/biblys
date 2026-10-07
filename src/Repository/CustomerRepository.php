<?php

/*
 * Copyright (C) 2026 Clément Latzarus
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

namespace Repository;

use Model\Customer;
use Model\CustomerQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;

class CustomerRepository
{
    /**
     * @return Customer[]
     * @throws PropelException
     */
    public function search(string $term, int $limit = 10): array
    {
        $keywords = preg_split('/\s+/', trim($term), flags: PREG_SPLIT_NO_EMPTY);
        if (empty($keywords)) {
            return [];
        }

        $query = CustomerQuery::create();
        foreach ($keywords as $index => $keyword) {
            $pattern = "%$keyword%";
            $query
                ->condition("first_name_$index", "Customer.FirstName LIKE ?", $pattern)
                ->condition("last_name_$index", "Customer.LastName LIKE ?", $pattern)
                ->condition("email_$index", "Customer.Email LIKE ?", $pattern)
                ->where(["first_name_$index", "last_name_$index", "email_$index"], Criteria::LOGICAL_OR);
        }

        return $query
            ->orderByLastName()
            ->orderByFirstName()
            ->limit($limit)
            ->find()
            ->getArrayCopy();
    }
}
