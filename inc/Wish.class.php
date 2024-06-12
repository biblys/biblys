<?php

    class Wish extends Entity
	{
		protected $prefix = 'wish';
    }
    
    class WishManager extends EntityManager
	{
		protected $prefix = 'wish',
				  $table = 'wishes',
				  $object = 'Wish';
		
	}
