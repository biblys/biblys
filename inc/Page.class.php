<?php

    class Page extends Entity
    {
        protected $prefix = 'page';
        public $trackChange = false;
    }

    class PageManager extends EntityManager
    {
        protected $prefix = 'page',
                  $table = 'pages',
                  $object = 'Page';

        public function create(array $defaults = array())
        {
            if (!isset($defaults['site_id'])) {
                $defaults['site_id'] = $this->site['site_id'];
            }

            return parent::create($defaults);
        }

        public function getAll(array $where = array(), array $options = array(), $withJoins = true)
        {
            $where['pages`.`site_id'] = $this->site['site_id'];

            return parent::getAll($where, $options);
        }

        public function preprocess($page)
        {

            // If page has no url, generate one from title
            if (!$page->has("url")) {
                $pageUrl = makeurl($page->get("title"));
                $page->set("page_url", $pageUrl);
            }

            // Check that there is no other page with this url
            // And if there is, add id to page url
            if ($this->get(
                [
                    "page_id" => "!= ".$page->get("id"),
                    "page_url" => $page->get("url")
                ]
            )) {
                $pageUrl = $page->get("url")."_".$page->get("id");
                $page->set("page_url", $pageUrl);
            }

            return $page;
        }

        public function validate($page)
        {
            if (!$page->has("url")) {
                throw new Exception("La page doit avoir une adresse.");
            }

            return true;
        }

    }
