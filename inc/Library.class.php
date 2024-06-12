<?php

    class Library extends Entity
	{
		protected $prefix = 'library';
    }
    
    class LibraryManager extends EntityManager
	{
		protected $prefix = 'library',
				  $table = 'libraries',
				  $object = 'Library';
		
	}
