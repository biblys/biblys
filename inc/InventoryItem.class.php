<?php

class InventoryItem extends Entity
{
    protected $prefix = 'ii';

}

class InventoryItemManager extends EntityManager
{
    protected $prefix = 'ii',
			  $table = 'inventory_item',
			  $object = 'InventoryItem';
}
