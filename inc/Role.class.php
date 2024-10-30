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


    class Role extends Entity
    {
        protected $prefix = 'role';
    }
    
    class RoleManager extends EntityManager
    {
        protected $prefix = 'role',
                  $table = 'roles',
                  $object = 'Role';

        public function __construct()
        {
            parent::__construct();
            $this->idField = 'id';
        }
    }
