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


/**
 * 
 */
class Right extends Entity
{
    protected $prefix = 'right';
    
    public function __construct($data, $withJoins = true)
    {
        /* JOINS */

        if ($withJoins) {
            // Publisher (OneToMany)
            $pm = new PublisherManager();
            if (isset($data['publisher_id'])) $data['publisher'] = $pm->get(array('publisher_id' => $data['publisher_id']));
        }
    
        parent::__construct($data);
    }
    
}

class RightManager extends EntityManager
{
    protected $prefix = 'right',
			  $table = 'rights',
			  $object = 'Right';
    
    public function create(array $defaults = array())
    {
        $defaults['right_uid'] = md5(uniqid('', true));

        return parent::create($defaults);
    }
}