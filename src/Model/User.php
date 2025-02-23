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

use Biblys\Exception\CannotDeleteUser;
use Model\Base\User as BaseUser;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for representing a row from the 'users' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class User extends BaseUser
{
    /**
     * @throws CannotDeleteUser
     */
    private function checkRelatedEntities(
        OrderQuery|StockQuery|CustomerQuery|DownloadQuery|PostQuery $query,
        string                                                      $userEmail,
        string                                                      $modelName,
    ): void
    {
        $count = $query->filterByUserId($this->getId())->count();

        if ($count > 0) {
            throw new CannotDeleteUser("Impossible de supprimer le compte $userEmail car il a $modelName.");
        }
    }

    /**
     * @throws CannotDeleteUser
     */
    public function preDelete(?ConnectionInterface $con = null): bool
    {
        $this->checkRelatedEntities(OrderQuery::create(), $this->getEmail(), "des commandes");
        $this->checkRelatedEntities(StockQuery::create(), $this->getEmail(), "des livres dans sa bibliothèque numérique");
        $this->checkRelatedEntities(CustomerQuery::create(), $this->getEmail(), "un compte client");
        $this->checkRelatedEntities(DownloadQuery::create(), $this->getEmail(), "des téléchargements");
        $this->checkRelatedEntities(PostQuery::create(), $this->getEmail(), "des billets de blog");

        return parent::preDelete($con);
    }

}
