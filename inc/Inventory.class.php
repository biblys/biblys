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
