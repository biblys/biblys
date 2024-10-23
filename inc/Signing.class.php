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


    class Signing extends Entity
	{
		protected $prefix = 'signing';
        
        public function __construct(array $data)
        {
             global $_SQL;
            
            /* JOINS */
            
            // Author (OneToMany)
            $pm = new PeopleManager();
            if (isset($data['people_id'])) $data['people'] = $pm->get(array('people_id' => $data['people_id']));
            
            // Author (OneToMany)
            $pum = new PublisherManager();
            if (isset($data['publisher_id'])) $data['publisher'] = $pum->get(array('publisher_id' => $data['publisher_id']));
            
            parent::__construct($data);
        }
        
    }
    
    class SigningManager extends EntityManager
	{
		protected $prefix = 'signing',
				  $table = 'signings',
				  $object = 'Signing';
    
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
        
        public function getAll(array $where = array(), array $options = array(), $withJoins = true)
        {
            $where['signings`.`site_id'] = $this->site['site_id'];

            return parent::getAll($where, $options);
        }
		
	}
