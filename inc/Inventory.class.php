<?php

class Inventory extends Entity
{
    protected $prefix = 'inventory';

}

class InventoryManager extends EntityManager
{
    protected $prefix = 'inventory',
			  $table = 'inventory',
			  $object = 'inventory';

    public function create(array $defaults = array())
    {
        if (!isset($defaults['site_id']))
        {
            $defaults['site_id'] = $this->site['site_id'];
        }
        
        return parent::create($defaults);
    }


    public function getAll(array $where = array(), array $options = array(), $withJoins = true)
    {
        if (!isset($where['site_id']))
        {
            $where['inventory`.`site_id'] = $this->site['site_id'];
        }
        
        return parent::getAll($where, $options, $withJoins);
    }

}

// CREATE TABLE `inventory` (
//   `inventory_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
//   `site_id` int(11) unsigned DEFAULT NULL,
//   `inventory_title` varchar(32) DEFAULT NULL,
//   `inventory_created` datetime NOT NULL,
//   `inventory_updated` datetime DEFAULT NULL,
//   `inventory_deleted` datetime DEFAULT NULL,
//   PRIMARY KEY (`inventory_id`)
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
