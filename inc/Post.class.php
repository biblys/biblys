<?php

    class Post extends Entity
    {
        protected $prefix = 'post';

        public function __construct($data)
        {
            /* JOINS */

            // Author (OneToMany)
            $um = new UserManager();
            if (isset($data['user_id'])) $data['author'] = $um->get(array('user_id' => $data['user_id']));

            // Category (OneToMany)
            $cm = new CategoryManager();
            if (isset($data['category_id'])) $data['category'] = $cm->get(array('category_id' => $data['category_id']));

            // Illustration
            if (isset($data['post_id']) && media_exists("post", $data['post_id']))
            {
                $data['illustration']['url'] = media_url("post", $data["post_id"]);
                $data['illustration']['legend'] = $data["post_illustration_legend"];
            }

            parent::__construct($data);
        }

        public function getIllustration()
        {
            if (!isset($this->illustration)) {
                $this->illustration = new Media('post', $this->get('id'));
            }
            return $this->illustration;
        }

        public function hasIllustration()
        {
            $illustration = $this->getIllustration();
            if (!isset($this->illustrationExists)) {
                $this->illustrationExists = $illustration->exists();
            }
            return $this->illustrationExists;
        }

        public function getIllustrationTag()
        {
            $illustration = $this->getIllustration();
            return '<img src="'.$illustration->url().'" alt="'.$this->get('illustration_legend').'" class="illustration">';
        }

        public function getFirstImageUrl()
        {
            preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $this->get('content'), $image);
            if (!empty($image['src'])) {
                return $image["src"];
            }
            return false;
        }

        /**
         * Get related publisher
         * @return {Publisher} or false
         */
        public function getPublisher()
        {
            if (!isset($this->publisher)) {
                if (!$this->has('publisher_id')) {
                    $this->publisher = false;
                }

                $pm = new PublisherManager();
                $this->publisher = $pm->getById($this->get('publisher_id'));
            }

            return $this->publisher;
        }

        /**
         * Get related articles
         * @return {array} of {Articles}
         */
        public function getArticles()
        {
            $lm = new LinkManager();
            $am = new ArticleManager();
            $am->setIgnoreSiteFilters(true);

            $articles = [];
            $links = $lm->getAll(["post_id" => $this->get("id"), "article_id" => "NOT NULL"]);
            foreach ($links as $link) {
                $articleId = $link->get('article_id');
                $article = $am->getById($articleId);
                if ($article) {
                    $articles[] = $article;
                }
            }

            return $articles;
        }

        /**
         * Get related (linked) people
         * @return {array} of {People}
         */
        public function getRelatedPeople()
        {

            $lm = new LinkManager();
            $pm = new PeopleManager();

            $people = [];
            $links = $lm->getAll(["post_id" => $this->get("id"), "people_id" => "NOT NULL"]);
            foreach ($links as $link) {
                $people[] = $pm->getById($link->get("people_id"));
            }

            return $people;
        }

        /**
         * Test if user can delete post
         * @return {boolean} true if user is admin or post's author
         */
        public function canBeDeletedBy(User $user)
        {
            if ($user->isAdmin()) {
                return true;
            }

            if ($user->get('id') === $this->get('user_id')) {
                return true;
            }

            return false;
        }

        /**
         * Returns previous post from current post's date
         */
        public function getPrevPost()
        {
            global $_SQL;

            $pm = new PostManager();
            $prev = $pm->get(
                ['post_date' => '< '.$this->get('date')],
                ['order' => 'post_date', 'sort' => 'desc']
            );

            return $prev;
        }

        /**
         * Returns next post from current post's date
         */
        public function getNextPost()
        {
            global $_SQL;

            $pm = new PostManager();
            $next = $pm->get(['post_date' => '> '.$this->get('date')]);

            return $next;
        }
    }

    class PostManager extends EntityManager
    {
        protected $prefix = 'post',
                  $table = 'posts',
                  $object = 'Post',
                  $siteAgnostic = false;

        public function create(array $defaults = array())
        {
            if (!isset($defaults['site_id'])) $defaults['site_id'] = $this->site['site_id'];
            return parent::create($defaults);
        }

        public function getAll(array $where = array(), array $options = array(), $withJoins = true)
        {
            $where['posts`.`site_id'] = $this->site['site_id'];
            return parent::getAll($where, $options);
        }
    }
