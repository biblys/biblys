<?php

class Alert extends Entity
{
    protected $prefix = 'alert';
}

class AlertManager extends EntityManager
{
    protected $prefix = 'alert';
    protected $table = 'alerts';
    protected $object = 'Alert';
}
