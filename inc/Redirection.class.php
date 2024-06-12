<?php

    class Redirection extends Entity
	{
		protected $prefix = 'redirection';
    }
    
    class RedirectionManager extends EntityManager
	{
		protected $prefix = 'redirection',
				  $table = 'redirections',
				  $object = 'Redirection';
		
	}
