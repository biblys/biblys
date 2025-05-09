<?php
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


use Model\User;
use Propel\Runtime\Exception\PropelException;

class Post extends Entity
{
    protected $prefix = 'post';
    private ?Media $illustration = null;
    private ?Publisher $publisher = null;
    private bool $illustrationExists;

    /**
     * @throws PropelException
     */
    public static function buildFromModel(\Model\Post $post): Post
    {
        return new Post([
            "post_id" => $post->getId(),
            "post_title" => $post->getTitle(),
            "post_url" => $post->getUrl(),
            "post_content" => $post->getContent(),
            "post_date" => $post->getDate()->format("Y-m-d H:i:s"),
            "post_illustration_legend" => $post->getIllustrationLegend(),
            "category_id" => $post->getCategoryId(),
        ]);
    }

    public function __construct($data)
    {
        /* JOINS */

        // Category (OneToMany)
        $cm = new CategoryManager();
        if (isset($data['category_id'])) $data['category'] = $cm->get(array('category_id' => $data['category_id']));

        parent::__construct($data);
    }

    /**
     * @throws DateMalformedStringException
     */
    public function getModel(): \Model\Post
    {
        $model = new \Model\Post();
        $model->setId($this->get("id"));
        $model->setDate($this->has("date") ? new DateTime($this->get("date")) : null);

        return $model;
    }

    /**
     * @deprecated Post->getIllustration is deprecated. Use method ImagesService->getImageUrlFor instead.
     * @throws Exception
     */
    public function getIllustration(): Media
    {
        trigger_deprecation(
            "biblys/biblys",
            "2.86.0",
            "Post->getIllustration… methods are deprecated. Use method ImagesService->getImageUrlFor instead.",
        );

        if (!isset($this->illustration)) {
            $this->illustration = new Media('post', $this->get('id'));
        }

        return $this->illustration;
    }

    /**
     * @deprecated Post->hasIllustration is deprecated. Use method ImagesService->imageExistsFor instead.
     * @throws Exception
     */
    public function hasIllustration(): bool
    {
        $illustration = $this->getIllustration();
        if (!isset($this->illustrationExists)) {
            $this->illustrationExists = $illustration->exists();
        }
        return $this->illustrationExists;
    }

    /**
     * @deprecated Post->getIllustrationUrl is deprecated. Use method ImagesService->getIllustrationUrl instead.
     * @throws Exception
     */
    public function getIllustrationUrl(): string
    {
        $options = [];

        $illustrationVersion = $this->get("illustration_version");
        if ($illustrationVersion >= 1) {
            $options["version"] = $illustrationVersion;
        }

        return $this->getIllustration()->getUrl($options);
    }

    /**
     * @deprecated Post->getIllustrationTag is deprecated. Use method ImagesService->getImageUrlFor instead.
     * @throws Exception
     */
    public function getIllustrationTag(?int $height = null): string
    {
        $illustration = $this->getIllustration();

        $heightAttribute = "";
        if ($height !== null) {
            $heightAttribute = " height=$height";
        }

        return '<img src="' . $illustration->getUrl() . '" alt="' . $this->get('illustration_legend') . '"' . $heightAttribute . ' class="illustration">';
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
     * @return bool true if a user is admin or post's author
     */
    public function canBeDeletedBy(User $user): bool
    {
        if ($user->getId() === $this->get('user_id')) {
            return true;
        }

        return false;
    }

    /**
     * Returns previous post from current post's date
     * @noinspection PhpUnused
     * @throws PropelException
     */
    public function getPrevPost(): ?\Model\Post
    {
        return $this->getModel()->getPreviousPost();
    }

    /**
     * Returns next post from current post's date
     * @noinspection PhpUnused
     * @throws PropelException
     */
    public function getNextPost(): ?\Model\Post
    {
        return $this->getModel()->getNextPost();
    }
}

class PostManager extends EntityManager
{
    protected $prefix = 'post',
        $table = 'posts',
        $object = 'Post';
}
