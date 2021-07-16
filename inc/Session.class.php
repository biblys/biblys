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

