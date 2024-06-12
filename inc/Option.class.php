<?php

use Biblys\Service\Config;

class Option extends Entity
{
    protected $prefix = 'option';

}

class OptionManager extends EntityManager
{
    protected $prefix = 'option',
			  $table = 'options',
			  $object = 'Option';

    public function __construct()
    {
        parent::__construct();

        $config = new Config();
        $this->table = $config->get("options_table_name");
    }
}

