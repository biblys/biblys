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


	class Customer extends Entity
	{
        protected $prefix = 'customer';
	}
	
	class CustomerManager extends EntityManager
	{
        protected $prefix = 'customer',
				  $table = 'customers',
				  $object = 'Customer';
        
        public function create(array $defaults = array())
        {
            $defaults['site_id'] = $this->site['site_id']; 
            return parent::create($defaults);
        }
        
        public function getAll(array $where = array(), array $options = array(), $withJoins = true)
        {
            $where['site_id'] = $this->site['site_id'];
            return parent::getAll($where, $options);
        }
        
        /**
		 * Search for a client
		 */
		public function search($query)
		{
			
			$params['site_id'] = $this->site['site_id'];
			
			$query = explode(" ",$query);
			foreach ($query as $i => $keyword)
			{
				if (isset($req)) $req .= " AND "; else $req = NULL;
				$req .= "(`customer_first_name` LIKE :keyword_".$i." OR `customer_last_name` LIKE :keyword_".$i." OR `customer_email` LIKE :keyword_".$i.")";
				$params['keyword_'.$i] = '%'.$keyword.'%';
			}
			
			$q = $this->db->prepare('
				SELECT * FROM `customers`
				WHERE `customers`.`site_id` = :site_id AND '.$req);
			$q->execute($params) or error($q->errorInfo());
			
			$list = array();
			while ($d = $q->fetch(PDO::FETCH_ASSOC))
			{
				$d['customer_newsletter'] = 0;
				
				$list[] = new Customer($d);
			}
			$q->closeCursor();
			return $list;
			
		}
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	