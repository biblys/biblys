<?php

/*
CREATE TABLE `cron_jobs` (
  `cron_job_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(11) DEFAULT NULL,
  `cron_job_task` varchar(128) DEFAULT NULL,
  `cron_job_result` varchar(16) DEFAULT NULL,
  `cron_job_message` varchar(256) DEFAULT NULL,
  `cron_job_created` datetime DEFAULT NULL,
  `cron_job_updated` datetime DEFAULT NULL,
  `cron_job_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`cron_job_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
*/

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
