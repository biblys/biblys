<?php
use AppBundle\Controller\CFRewardController;

    class CFReward extends Entity
    {
        protected $prefix = 'reward';

        /**
         * Returns true if reward is unlimited or has quantity
         */
        public function isLimited()
        {
            return ($this->get('limited') == 1);
        }

        /**
         * Returns true if reward is unlimited or has quantity
         */
        public function isAvailable()
        {
            return ($this->get('quantity') > 0 || !$this->isLimited());
        }

        public function getCampaign()
        {
            if (!isset($this->campaign)) {
                $cm = new CFCampaignManager();
                $this->campaign = $cm->getById($this->get('campaign_id'));
            }
            return $this->campaign;
        }

        public function getArticles()
        {
            $am = new ArticleManager();
            $articles = json_decode($this->get('articles'));
            $result = [];
            foreach ($articles as $article_id) {
                $article = $am->getById($article_id);
                if (!$article) {
                    throw new Exception('Article '.$article_id.' inconnu.');
                }
                $result[] = $article;
            }
            return $result;
        }
    }

    class CFRewardManager extends EntityManager
    {
        protected $prefix = 'reward',
                  $table = 'cf_rewards',
                  $object = 'CFReward';

        public function create(array $defaults = array())
        {
          if (!isset($defaults['site_id'])) $defaults['site_id'] = $this->site['site_id'];
          return parent::create($defaults);
        }

        public function getAll(array $where = array(), array $options = array(), $withJoins = true)
        {
          $where['site_id'] = $this->site['site_id'];

          return parent::getAll($where, $options);
        }

        /**
         * Calculate price from content articles
         * @param CFReward $reward
         */
        public function updatePrice(CFReward $reward)
        {
            $articles = $reward->getArticles();
            $price = 0;
            foreach($articles as $article) {
                $price += $article->get('price');
            }
            $reward->set('reward_price', $price);
            $this->update($reward);
        }

        /**
         * Get available quantity for a reward
         * @param CFReward $reward
         */
        public function updateQuantity(CFReward $reward)
        {
            if (!$reward->isLimited()) {
                return;
            }

            $am = new ArticleManager();
            $sm = new StockManager();

            $qty = 0;

            $articles = json_decode($reward->get('articles'));

            foreach($articles as $article_id) {
                $article = $am->get(array('article_id' => $article_id));

                // if article is downloadable, quantity is unlimited
                if ($article->get('type_id') == 2 || $article->get('type_id') == 10 || $article->get('type_id') == 11) {
                    continue;
                }

                // else count available stock
                else {

                    $this_qty = 0;
                    $stocks = $sm->getAll(array('article_id' => $article_id), array(), false);
                    foreach ($stocks as $stock) {
                        if ($stock->isAvailable()) {
                            $this_qty++;
                        }
                    }
                    if ($qty == 0 || $this_qty < $qty) {
                        $qty = $this_qty;
                    }
                }
            }

            // If reward is unlimited, ignore quantity
            $reward->set('reward_quantity', $qty);

            $this->update($reward);

            return $reward;
        }
    }
