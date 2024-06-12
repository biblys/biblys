<?php

class Option extends Entity
{
    protected $prefix = 'option';

}

class OptionManager extends EntityManager
{
    protected $prefix = 'option',
			  $table = 'options',
			  $object = 'Option';

}

