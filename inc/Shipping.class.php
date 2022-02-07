<?php

    class Shipping extends Entity
    {
        protected $prefix = 'shipping';
    }

    class ShippingManager extends EntityManager
    {
        protected $prefix = 'shipping';
        protected $table = 'shipping';
        protected $object = 'shipping';
    }
