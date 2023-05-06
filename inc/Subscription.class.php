<?php

    class Subscription extends Entity
	{
		protected $prefix = 'subscription';
        
        public function __construct($data)
        {
            global $_SQL;

            /* JOINS */
            
            // User (OneToMany)
            $um = new UserManager();
            if (isset($data['user_id'])) $data['user'] = $um->get(array('user_id' => $data['user_id']));

            // Publisher (OneToMany)
            $pm = new PublisherManager();
            if (isset($data['publisher_id'])) $data['publisher'] = $pm->get(array('publisher_id' => $data['publisher_id']));

            // Bookshop (OneToMany)
            $bm = new BookshopManager();
            if (isset($data['bookshop_id'])) $data['bookshop'] = $bm->get(array('bookshop_id' => $data['bookshop_id']));

            // Library (OneToMany)
            $lm = new LibraryManager();
            if (isset($data['library_id'])) $data['library'] = $lm->get(array('library_id' => $data['library_id']));

            parent::__construct($data);
        }
    }
    
    class SubscriptionManager extends EntityManager
	{
		protected $prefix = 'subscription',
				  $table = 'subscriptions',
				  $object = 'Subscription';
		
        public function getAll(array $where = array(), array $options = array(), $withJoins = true)
        {
            $where['subscriptions`.`site_id'] = $this->site['site_id'];
            
            return parent::getAll($where, $options);
        }
        
        public function create(array $defaults = array())
        {
            if (!isset($defaults['site_id'])) $defaults['site_id'] = $this->site['site_id'];
            
            try
            {
                return parent::create($defaults);
            }
            catch(Exception $e)
            {
                trigger_error($e->getMessage());
            }
        }
        
	}
