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


    class Subscription extends Entity
	{
		protected $prefix = 'subscription';
        
        public function __construct($data)
        {
            global $_SQL;

            /* JOINS */
            
            // Publisher (OneToMany)
            $pm = new PublisherManager();
            if (isset($data['publisher_id'])) $data['publisher'] = $pm->get(array('publisher_id' => $data['publisher_id']));

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
            return parent::getAll($where, $options);
        }
        
        public function create(array $defaults = array())
        {
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
