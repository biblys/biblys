<?php

    class Role extends Entity
    {
        protected $prefix = 'role';
    }
    
    class RoleManager extends EntityManager
    {
        protected $prefix = 'role',
                  $table = 'roles',
                  $object = 'Role',
                  $delete = 'hard';
                  
        public function __construct()
        {
            parent::__construct();
            $this->idField = 'id';
        }
    }
