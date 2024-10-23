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


class Wishlist extends Entity
{
    protected $prefix = 'wishlist';
    
}

class WishlistManager extends EntityManager
{
    protected $prefix = 'wishlist',
			  $table = 'wishlist',
			  $object = 'Wishlist';
              
    public function create(array $defaults = array())
    {
        
        if (!isset($defaults["wishlist_name"]))
        {
            $defaults["wishlist_name"] = "Ma liste d'envies";
        }
        
        if (!isset($defaults["wishlist_current"]))
        {
            $defaults["wishlist_current"] = 1;
        }
        
        return parent::create($defaults);
        
    }
    
}
