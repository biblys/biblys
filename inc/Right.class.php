<?php

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
            
            // Bookshop (OneToMany)
            $bm = new BookshopManager();
            if (isset($data['bookshop_id'])) $data['bookshop'] = $bm->get(array('bookshop_id' => $data['bookshop_id']));
            
            // Library (OneToMany)
            $lm = new LibraryManager();
            if (isset($data['library_id'])) $data['library'] = $lm->get(array('library_id' => $data['library_id']));
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