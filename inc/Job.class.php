<?php

class Job extends Entity
{
    protected $prefix = 'job';
    public $trackChange = false;
}

class JobManager extends EntityManager
{
    protected $prefix = 'job',
        $table = 'jobs',
        $object = 'Job';
}
