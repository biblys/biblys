<?php

    class Shipping extends Entity
    {
        protected $prefix = 'shipping';
    }

    class ShippingManager extends EntityManager
    {
        protected $prefix = 'shipping';
        protected $table = 'shipping';
        protected $object = 'shipping';

        public function getFees(Country $country, int $weight, int $amount)
        {
            global $site;

            $where = [];

            // Add 5 % to the weight for wrapping
            $weight *= 1.05;

            // If site don't use french grid, keep only site specific fees
            if ($site->get('shipping_fee') !== 'fr') {
                $where['site_id'] = $site->get('id');
            }

            // Get shipping zone
            $zone = $country->get('shipping_zone');
            $fees = $this->getAll($where, ['order' => 'shipping_fee']);


            // For each types in ['normal', 'suivi', 'magasin']
            $types = array_map(
                function ($type) use ($fees, $zone, $weight, $amount) {
                    foreach ($fees as $fee) {
                        // Keeps only fees for current type
                        if ($fee->get('type') !== $type) {
                            continue;
                        }

                        // Keep only shipping without article
                        if ($fee->has('article_id')) {
                            continue;
                        }

                        // Keep only fees for this zones or ALL zones
                        if ($fee->get('zone') !== $zone && $fee->get('zone') !== 'ALL') {
                            continue;
                        }

                        // Keep only site-specific or global fees
                        if ($fee->get('site_id') !== '0' && $fee->get('site_id') !== $this->site['site_id']) {
                            continue;
                        }

                        // Keep only fees for weight higher than order
                        if ($fee->get('max_weight') !== null && $fee->get('max_weight') <= $weight) {
                            continue;
                        }

                        // Keep only fees for which order's amount is higher than min amount
                        if ($fee->get('min_amount') !== null && $amount < $fee->get('min_amount')) {
                            continue;
                        }

                        // Keep only fees for which order's amount is lesser than max amount
                        if ($fee->get('max_amount') !== null && $amount > $fee->get('max_amount')) {
                            continue;
                        }

                        // Return first fee that survived until here
                        return $fee;
                    }
                },
                ['magasin', 'normal', 'suivi']
            );

            return array_filter(
                $types,
                function ($fee) { return $fee !== null; }
            );
        }
    }
