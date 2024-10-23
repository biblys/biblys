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


class Inventory extends Entity
{
    protected $prefix = 'inventory';

}

class InventoryManager extends EntityManager
{
    protected $prefix = 'inventory',
			  $table = 'inventory',
			  $object = 'inventory';

    public function create(array $defaults = array())
    {
        if (!isset($defaults['site_id']))
        {
            $defaults['site_id'] = $this->site['site_id'];
        }
        
        return parent::create($defaults);
    }


    public function getAll(array $where = array(), array $options = array(), $withJoins = true)
    {
        if (!isset($where['site_id']))
        {
            $where['inventory`.`site_id'] = $this->site['site_id'];
        }
        
        return parent::getAll($where, $options, $withJoins);
    }

}
