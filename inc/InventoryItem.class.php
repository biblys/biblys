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

// CREATE TABLE `inventory_item` (
//   `ii_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
//   `inventory_id` int(11) unsigned DEFAULT NULL,
//   `ii_ean` varchar(32) DEFAULT NULL,
//   `ii_quantity` int(11) unsigned DEFAULT NULL,
//   `ii_stock` int(11) unsigned DEFAULT NULL,
//   `ii_created` datetime NOT NULL,
//   `ii_updated` datetime DEFAULT NULL,
//   `ii_deleted` datetime DEFAULT NULL,
//   PRIMARY KEY (`ii_id`)
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
