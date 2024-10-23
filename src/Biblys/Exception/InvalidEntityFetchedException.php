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


namespace Biblys\Exception;

use Entity;
use Exception;

class InvalidEntityFetchedException extends Exception
{
    private Entity $invalidEntity;

    public function __construct($message, string $entityType, Entity $invalidEntity)
    {
        $message = sprintf("%s %s is invalid: $message", $entityType, $invalidEntity->get("id"));
        parent::__construct($message);

        $this->invalidEntity = $invalidEntity;
    }

    public function getInvalidEntity(): Entity
    {
        return $this->invalidEntity;
    }
}