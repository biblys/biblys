<?php

class CronJob extends Entity
{
    protected $prefix = 'cron_job';
}

class CronJobManager extends EntityManager
{
    protected $prefix = 'cron_job';
    protected $table = 'cron_jobs';
    protected $object = 'CronJob';
    protected $siteAgnostic = false;
}
