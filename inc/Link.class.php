<?php

class Link extends Entity
{
    protected $prefix = 'link';
    
}

class LinkManager extends EntityManager
{
    protected $prefix = 'link',
			  $table = 'links',
			  $object = 'Link',
              $delete = 'hard';
    
}
