<?php

class Lang extends Entity
{
    protected $prefix = 'lang';
    public $trackChange = false;
}

class LangManager extends EntityManager
{
    protected $prefix = 'lang',
              $table = 'langs',
              $object = 'Lang';
}
