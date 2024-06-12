<?php

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
            $defaults["wishlist_name"] = "Ma liste d'envies Biblys";
        }
        
        if (!isset($defaults["wishlist_current"]))
        {
            $defaults["wishlist_current"] = 1;
        }
        
        return parent::create($defaults);
        
    }
    
}
