<?php

class CFReward extends Entity
    {
        protected $prefix = 'reward';

        /**
         * Returns true if reward is unlimited or has quantity
         */
        public function isLimited(): bool
        {
            return ($this->get('limited') == 1);
        }

        /**
         * Returns true if reward is unlimited or has quantity
         */
        public function isAvailable(): bool
        {
            return ($this->get('quantity') > 0 || !$this->isLimited());
        }

        public function getCampaign(): CFCampaign
        {
            if (!isset($this->campaign)) {
                $cm = new CFCampaignManager();
                $this->campaign = $cm->getById($this->get('campaign_id'));
            }
            return $this->campaign;
        }

        /**
         * @return Article[]
         * @throws Exception
         */
        public function getArticles(): array
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

        /**
         * @throws Exception
         */
        public function create(array $defaults = array())
        {
          if (!isset($defaults['site_id'])) $defaults['site_id'] = $this->site['site_id'];
          return parent::create($defaults);
        }

        /**
         * @param array $where
         * @param array $options
         * @param $withJoins
         * @return Entity[]
         */
        public function getAll(array $where = array(), array $options = array(), $withJoins = true): array
        {
          $where['site_id'] = $this->site['site_id'];

          return parent::getAll($where, $options);
        }

        /**
         * Calculate price from content articles
         * @param CFReward $reward
         * @throws Exception
         */
        public function updatePrice(Entity $reward)
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
         * @param Entity $reward
         * @return Entity
         * @throws Exception
         */
        public function updateQuantity(Entity $reward): Entity
        {
            if (!$reward->isLimited()) {
                return $reward;
            }

            $campaign = $reward->getCampaign();
            if ($campaign->hasEnded()) {
                return $reward;
            }

            $am = new ArticleManager();
            $sm = new StockManager();

            $qty = 0;

            $articles = json_decode($reward->get('articles'));

            foreach($articles as $articleId) {
                $article = $am->getById($articleId);

                if (!$article) {
                    throw new Exception("L'article n° $articleId n'existe pas.");
                }

                // if article is downloadable, quantity is unlimited
                if ($article->get('type_id') == 2 || $article->get('type_id') == 10 || $article->get('type_id') == 11) {
                    continue;
                }

                // else count available stock
                else {

                    $this_qty = 0;
                    $stocks = $sm->getAll(array('article_id' => $articleId), array(), false);
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
