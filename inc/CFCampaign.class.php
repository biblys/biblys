<?php

    class CFCampaign extends Entity
    {
        protected $prefix = 'campaign';

        /**
         * Returns true if start date is greater or equals today
         */
        public function hasStarted()
        {
            $today = (new DateTime('today'))->format("Y-m-d");
            if ($this->get('starts') <= $today) {
                return true;
            }
            return false;
        }

        /**
         * Returns true if ends date is lower than today
         */
        public function hasEnded()
        {
            $today = (new DateTime("today"))->format("Y-m-d H:i:s");
            if ($this->get('ends')." 23:59:59" < $today) {
                return true;
            }
            return false;
        }

        /**
         * Returns if campaign has started but not ended
         */
        public function isInProgress()
        {
            return ($this->hasStarted() && !$this->hasEnded());
        }

        /**
         * Returns the number of days left until campaign ends
         */
        public function getTimeLeft()
        {
            $end = new DateTime($this->get('ends')." 23:59:59");
            $today = new DateTime();
            $interval = $today->diff($end);

            if ($interval->d > 0) {
                $days = $interval->format('%a');
                return "$days jour".s($days);
            }

            if ($interval->h > 0) {
                $hours = $interval->h;
                return "$hours heure".s($hours);
            }

            if ($interval->i > 0) {
                $minutes = $interval->i;
                return "$minutes minute".s($minutes);
            }

            if ($interval->s > 0) {
                $seconds = $interval->s;
                return "$seconds seconde".s($seconds);
            }
        }

        /**
         * Returns the percentage of pledged money against goal
         */
        public function getProgress()
        {
            $percent = ($this->get('pledged') / $this->get('goal')) * 100;
            return number_format($percent, 2);
        }
    }

    class CFCampaignManager extends EntityManager
    {
        protected $prefix = 'campaign',
                  $table = 'cf_campaigns',
                  $object = 'CFCampaign';

        public function create(array $defaults = array())
        {
          if (!isset($defaults['site_id'])) {
              $defaults['site_id'] = $this->site['site_id'];
          }
          return parent::create($defaults);
        }

        public function getAll(array $where = array(), array $options = array(), $withJoins = true)
        {
          $where['site_id'] = $this->site['site_id'];

          return parent::getAll($where, $options);
        }

        /**
         * Update campaign from linked sales
         * @param CFCampaign $campaign The campaign object to update
         * @return \CFCampaign The updated campaign object
         */
        public function updateFromSales(CFCampaign $campaign)
        {
            $sm = new StockManager();
            $rm = new CFRewardManager();
            $om = new OrderManager();

            // Get all rewards linked to this campaign
            $rewards = $rm->getAll(array('campaign_id' => $campaign->get('id')));

            $campaign_pledged = 0;
            $campaign_backers = array();
            foreach ($rewards as $reward)
            {
                // Get all copies linked to this reward
                $stocks = $sm->getAll(array('reward_id' => $reward->get('id')), array(), false);

                $reward_backers = array();
                foreach ($stocks as $stock)
                {
                    if ($stock->has('stock_selling_date'))
                    {
                        $campaign_backers[] = $stock->get('order_id');
                        $reward_backers[] = $stock->get('order_id');
                        $campaign_pledged += $stock->get('selling_price');
                    }
                }

                // Update reward backers count
                $reward_backers = array_unique($reward_backers);
                $reward->set('reward_backers', count($reward_backers));
                $rm->update($reward);
            }

            // Update campaign
            $campaign_backers = array_unique($campaign_backers);
            $campaign->set('campaign_backers', count($campaign_backers));
            $campaign->set('campaign_pledged', $campaign_pledged);
            $this->update($campaign);

            return $campaign;
        }
    }
