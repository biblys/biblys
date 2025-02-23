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


namespace Usecase;

use Exception;
use Model\Map\UserTableMap;
use Model\User;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;

class DeleteUserUsecase
{
    /**
     * @throws PropelException
     */
    public function execute(User $user): void
    {
        $con = Propel::getWriteConnection(UserTableMap::DATABASE_NAME);
        $con->beginTransaction();

        try {
            $this->_deleteEntities($con, $user->getAlerts());
            $this->_deleteEntities($con, $user->getAuthenticationMethods());
            $this->_deleteEntities($con, $user->getCartsRelatedByUserId());
            $this->_deleteEntities($con, $user->getOptions());
            $this->_deleteEntities($con, $user->getRights());
            $this->_deleteEntities($con, $user->getSessions());
            $this->_deleteEntities($con, $user->getVotes());
            $this->_deleteEntities($con, $user->getWishlists());
            $this->_deleteEntities($con, $user->getWishes());
            $user->delete($con);
            $con->commit();
        } catch(Exception $exception) {
            $con->rollBack();
            throw $exception;
        }
    }

    private function _deleteEntities(ConnectionInterface $con, ObjectCollection $entities): void
    {
        foreach ($entities as $entity) {
            $entity->delete($con);
        }
    }
}