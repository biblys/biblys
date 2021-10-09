<?php

use Biblys\Exception\EntityAlreadyExistsException;

class Collection extends Entity
    {
        protected $prefix = 'collection';

        private $publisher;

        public function __construct($data)
        {
            /* JOINS */

            // Publisher(OneToMany)
            $pm = new PublisherManager();
            if (isset($data['publisher_id'])) {
                $data['publisher'] = $pm->get(['publisher_id' => $data['publisher_id']]);
            }

            parent::__construct($data);
        }

        public function getPublisher()
        {
            if (!isset($this->publisher)) {
                if (!$this->has('publisher_id')) {
                    return false;
                }

                $pm = new PublisherManager();
                $this->publisher = $pm->get(['publisher_id' => $this->get('publisher_id')]);
            }

            return $this->publisher;
        }
    }

    class CollectionManager extends EntityManager
    {
        protected $prefix = 'collection';
        protected $table = 'collections';
        protected $object = 'Collection';
        protected $search_fields = ['name', 'publisher'];
        protected $ignoreSiteFilters = false;

        /**
         * Add site filters if any defined.
         *
         * @param [type] $where [description]
         */
        public function addSiteFilters($where)
        {
            if ($this->ignoreSiteFilters) {
                return $where;
            }

            global $site;

            $publisher_filter = $site->getOpt('publisher_filter');
            if ($publisher_filter && !array_key_exists('publisher_id', $where)) {
                $where['publisher_id'] = explode(',', $publisher_filter);
            }

            return $where;
        }

        /**
         * Get query.
         */
        public function getQuery($query, $params, $options = [], $withJoins = true)
        {
            $filters = $this->addSiteFilters([]);
            $filtersQuery = EntityManager::buildSqlQuery($filters, [], count($params));
            if ($filtersQuery['where']) {
                if ($query) {
                    $query .= ' AND ';
                }

                $query .= $filtersQuery['where'];
                $params = array_merge($params, $filtersQuery['params']);
            }

            return parent::getQuery($query, $params, $options, $withJoins);
        }

        /**
         * Update collection AND articles.
         *
         * @param type $x
         */
        public function update($x, $reason = null)
        {
            if ($c = parent::update($x, $reason = null)) {
                $am = new ArticleManager();
                $articles = $am->getAll(['collection_id' => $c->get('id')]);
                foreach ($articles as $a) {
                    $a->set('article_keywords_generated', null);
                    $a->set('article_collection', $c->get('name'));
                    $am->update($a);
                }
            }

            return $c;
        }

        public function preprocess($collection)
        {
            $publisher = $collection->getPublisher();
            if (!$publisher) {
                throw new Exception('La collection doit être associée à un éditeur.');
            }

            $collection->set('collection_publisher', $publisher->get('name'));

            $slug = CollectionManager::createSlug(
                $publisher->get('name'), $collection->get('name')
            );
            $collection->set('collection_url', $slug);

            return $collection;
        }

        public static function createSlug($publisherName, $collectionName)
        {
            $publisherSlug = makeurl($publisherName);
            $collectionSlug = makeurl($collectionName);
            if ($publisherSlug == $collectionSlug
                || strstr($collectionSlug, $publisherSlug)
            ) {
                $slug = $collectionName;
            } else {
                $slug = $publisherName.' '.$collectionName;
            }

            return makeurl($slug);
        }

        public function validate($collection)
        {
            if (!$collection->has('name')) {
                throw new Exception('La collection doit avoir un nom.');
            }

            $publisher = $collection->getPublisher();
            if (!$publisher) {
                throw new Exception('La collection doit être associée à un éditeur.');
            }

            // Check that there is not another publisher with that name
            $otherCollectionWithTheSameName = $this->get(
                [
                    'collection_url' => $collection->get('url'),
                    'collection_id' => '!= '.$collection->get('id'),
                ]
            );
            if ($otherCollectionWithTheSameName) {
                throw new EntityAlreadyExistsException(
                    sprintf(
                        "Il existe déjà une collection avec le nom « %s » (n° %s) chez l'éditeur %s (slug: %s).",
                        $otherCollectionWithTheSameName->get("name"),
                        $otherCollectionWithTheSameName->get("id"),
                        $publisher->get("name"),
                        $otherCollectionWithTheSameName->get("url"),
                    )
                );
            }

            // Check that there is not another publisher with that noosfere_id
            $otherNoosfereId = $this->get(
                [
                    'collection_noosfere_id' => $collection->get('noosfere_id'),
                    'collection_id' => '!= '.$collection->get('id'),
                ]
            );
            if ($otherNoosfereId) {
                throw new Exception('Il existe déjà une collection avec l\'identifiant noosfere '.$collection->get('noosfere_id').'.');
            }

            return true;
        }

        public function beforeDelete($collection)
        {
            // Check if collection has related articles
            $am = new ArticleManager();
            $article = $am->get(['collection_id' => $collection->get('id')]);
            if ($article) {
                throw new Exception('Impossible de supprimer la collection car des articles y sont associés.');
            }
        }
    }
