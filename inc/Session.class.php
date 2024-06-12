<?php

class Session extends Entity
{
    protected $prefix = 'session';

    public function isValid()
    {
        if ($this->get('expires') >= date('Y-m-d H:i:s')) {
            return true;
        }
        
        return false;
    }

}

class SessionManager extends EntityManager
{
    protected $prefix = 'session',
			  $table = 'session',
			  $object = 'Session';
              
    public function create(array $defaults = array())
    {
        if (!isset($defaults['session_token'])) {
            $defaults['session_token'] = md5(uniqid());
            $defaults['session_expires'] = date("Y-m-d H:i:s", strtotime('+ 1 hour'));
        }
        
        return parent::create($defaults);
    }

}

// CREATE TABLE `session` (
//   `session_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
//   `user_id` int(11) unsigned NOT NULL,
//   `session_token` varchar(32) DEFAULT NULL,
//   `session_created` datetime NOT NULL,
//   `session_expires` datetime DEFAULT NULL,
//   `session_updated` datetime DEFAULT NULL,
//   `session_deleted` datetime DEFAULT NULL,
//   PRIMARY KEY (`session_id`)
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
