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


class Session extends Entity
{
    protected $prefix = 'session';

    public function isValid()
    {
        if ($this->get('expires') >= date('Y-m-d H:i:s')) {
            return true;
        }
        
        return false;
    }

}

class SessionManager extends EntityManager
{
    protected $prefix = 'session',
			  $table = 'session',
			  $object = 'Session';
              
    public function create(array $defaults = array())
    {
        if (!isset($defaults['session_token'])) {
            $defaults['session_token'] = md5(uniqid());
            $defaults['session_expires'] = date("Y-m-d H:i:s", strtotime('+ 1 hour'));
        }
        
        return parent::create($defaults);
    }

}

