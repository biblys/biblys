<?php

class Price extends Entity
{
    protected $prefix = 'price';
}

class PriceManager extends EntityManager
{
    protected $prefix = 'price';
    protected $table = 'prices';
    protected $object = 'Price';
}
