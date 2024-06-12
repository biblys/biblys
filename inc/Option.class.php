<?php

class Option extends Entity
{
    protected $prefix = 'option';

}

class OptionManager extends EntityManager
{
    protected $prefix = 'option',
			  $table = 'option',
			  $object = 'Option';

}

// CREATE TABLE `option` (
//   `option_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
//   `site_id` int(11) unsigned DEFAULT NULL,
//   `option_key` varchar(32) DEFAULT NULL,
//   `option_value` varchar(32) DEFAULT NULL,
//   `option_created` datetime NOT NULL,
//   `option_updated` datetime DEFAULT NULL,
//   `option_deleted` datetime DEFAULT NULL,
//   PRIMARY KEY (`option_id`)
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
