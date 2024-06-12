<?php

    class Country extends Entity
	{
		protected $prefix = 'country';
    }
    
    class CountryManager extends EntityManager
	{
		protected $prefix = 'country',
				  $table = 'countries',
				  $object = 'Country';
		
	}
