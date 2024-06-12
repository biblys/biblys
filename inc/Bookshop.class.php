<?php

    class Bookshop extends Entity
	{
		protected $prefix = 'bookshop';
    }
    
    class BookshopManager extends EntityManager
	{
		protected $prefix = 'bookshop',
				  $table = 'bookshops',
				  $object = 'Bookshop';
		
	}
