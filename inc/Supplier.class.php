<?php

use Biblys\Legacy\LegacyCodeHelper;

class Supplier extends Entity
	{
		protected $prefix = 'supplier';

        /**
         * Returns total revenue for this supplier for a year
         * @param  string $year year filter
         * @return int          the revenue
         */
        public function getRevenue($year = 'all')
        {
            $revenue = 0;
            $publishers = $this->getPublishers();
            foreach ($publishers as $publisher) {
                $revenue += $publisher->getRevenue($year);
            }
            $publishers = null;

            return $revenue;
        }

        /**
         * Get all publishers for this supplier
         * @return Array of Publishers
         */
        public function getPublishers()
        {
            $_SITE = LegacyCodeHelper::getGlobalSite();

            // Get links for this suppliers
            $lm = new LinkManager();
            $links = $lm->getAll(['site_id' => $_SITE->get('id'), 'publisher_id' => 'NOT NULL', 'supplier_id' => $this->get('id')], ['withJoins' => false]);

            // Get publishers id from links
            $publisherIds = array_map(function($link) {
                return $link->get('publisher_id');
            }, $links);
            $links = null;

            if (count($publisherIds) === 0) {
                return [];
            }

            $pm = new PublisherManager();
            return $pm->getAll(['publisher_id' => $publisherIds], ['withJoins' => false]);
        }
    }

    class SupplierManager extends EntityManager
	{
		protected $prefix = 'supplier',
				  $table = 'suppliers',
				  $object = 'Supplier';

        public function create(array $defaults = array())
        {
            if (!isset($defaults['site_id']))
            {
                $defaults['site_id'] = $this->site['site_id'];
            }
            return parent::create($defaults);
        }

        public function getAll(array $where = array(), array $options = array(), $withJoins = true)
        {
            if (!isset($where['site_id']))
            {
                $where['site_id'] = $this->site['site_id'];
            }
            return parent::getAll($where, $options);
        }

	}
