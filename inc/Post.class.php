<?php

    class Post extends Entity
    {
        protected $prefix = 'post';

        /**
         * @var Media
         */
        private $illustration = null;

        /**
         * @var Publisher
         */
        private $publisher = null;

        public function __construct($data)
        {
            /* JOINS */

            // Author (OneToMany)
            $um = new UserManager();
            if (isset($data['user_id'])) $data['author'] = $um->get(array('user_id' => $data['user_id']));

            // Category (OneToMany)
            $cm = new CategoryManager();
            if (isset($data['category_id'])) $data['category'] = $cm->get(array('category_id' => $data['category_id']));

            parent::__construct($data);
        }

        public function getIllustration(): Media
        {
            if (!isset($this->illustration)) {
                $this->illustration = new Media('post', $this->get('id'));
            }

            return $this->illustration;
        }

        public function hasIllustration(): bool
        {
            $illustration = $this->getIllustration();
            if (!isset($this->illustrationExists)) {
                $this->illustrationExists = $illustration->exists();
            }
            return $this->illustrationExists;
        }

        public function getIllustrationTag(?int $height = null): string
        {
            $illustration = $this->getIllustration();

            $heightAttribute = "";
            if ($height !== null) {
                $heightAttribute = " height=$height";
            }

            return '<img src="'.$illustration->url().'" alt="'.$this->get('illustration_legend').'"'.$heightAttribute.' class="illustration">';
        }

        public function getFirstImageUrl(): ?string
        {
            if (!$this->has("content")) {
                return null;
            }

            preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $this->get('content'), $image);
            if (empty($image['src'])) {
                return null;
            }

            return $image["src"];
        }

        /**
         * Get related publisher
         */
        public function getPublisher(): ?Publisher
        {
            if (isset($this->publisher)) {
                return $this->publisher;
            }

            $publisher = null;
            if ($this->has('publisher_id')) {
                $pm = new PublisherManager();
                $publisher = $pm->getById($this->get('publisher_id'));
                if ($publisher === false) {
                    $publisher = null;
                }
            }

            $this->publisher = $publisher;
            return $this->publisher;
        }

        /**
         * Get related articles
         * @return Article[]
         */
        public function getArticles(): array
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
         * @return People[]
         */
        public function getRelatedPeople(): array
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
         * @param User $user
         * @return bool true if user is admin or post's author
         */
        public function canBeDeletedBy(User $user): bool
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
            $pm = new PostManager();
            return $pm->get(
                ['post_date' => '< '.$this->get('date')],
                ['order' => 'post_date', 'sort' => 'desc']
            );
        }

        /**
         * Returns next post from current post's date
         */
        public function getNextPost()
        {
            $pm = new PostManager();
            return $pm->get(['post_date' => '> '.$this->get('date')]);
        }
    }

    class PostManager extends EntityManager
    {
        protected $prefix = 'post',
                  $table = 'posts',
                  $object = 'Post',
                  $siteAgnostic = false;

        /**
         * @throws Exception
         */
        public function create(array $defaults = array())
        {
            if (!isset($defaults['site_id'])) $defaults['site_id'] = $this->site['site_id'];
            return parent::create($defaults);
        }

        public function getAll(array $where = array(), array $options = array(), $withJoins = true): array
        {
            $where['posts`.`site_id'] = $this->site['site_id'];
            return parent::getAll($where, $options);
        }
    }
