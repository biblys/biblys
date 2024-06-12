<?php

    class Category extends Entity
    {
        protected $prefix = 'category';
    }

    class CategoryManager extends EntityManager
    {
        protected $prefix = 'category',
                  $table = 'categories',
                  $object = 'Category';


        public function create(array $defaults = array())
        {
            if (!isset($defaults['site_id'])) $defaults['site_id'] = $this->site['site_id'];
            return parent::create($defaults);
        }

        public function getAll(array $where = array(), array $options = array(), $withJoins = true)
        {
            $where['categories`.`site_id'] = $this->site['site_id'];
            return parent::getAll($where, $options);
        }
    }
