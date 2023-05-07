<?php

class Rayon extends Entity
{
    protected $prefix = 'rayon';

    public function getArticles()
    {
        if (isset($this->articles)) {
            return $this->articles;
        }

        $am = new ArticleManager();
        return $am->getAllFromRayon($this, false);
    }

    public function countArticles(): int
    {
        if (isset($this->articles)) {
            return count($this->articles);
        }

        $am = new ArticleManager();
        return $am->countAllFromRayon($this);
    }

    public function addArticle($article)
    {
        global $_SITE;

        $lm = new LinkManager();

        // Check if article is already in rayon
        $link = $lm->get(['site_id' => $_SITE->get('id'), 'rayon_id' => $this->get('id'), 'article_id' => $article->get('id')]);
        if ($link) {
            throw new Exception("L'article « ".$article->get('title')." » est déjà dans le rayon « ".$this->get('name')." ».");
        }

        // Create link
        $link = $lm->create(['site_id' => $_SITE->get('id'), 'rayon_id' => $this->get('id'), 'article_id' => $article->get('id')]);

        // Update article metadata
        $article_links = $article->get('links')."[rayon:".$this->get('id')."]";
        $article->set('article_links', $article_links);
        $am = new ArticleManager();
        $am->update($article);

        return $link;
    }

    public function removeArticle($article)
    {
        global $_SITE;

        $lm = new LinkManager();
        $am = new ArticleManager();

        // Remove links for this article & rayon
        $links = $lm->getAll(['site_id' => $_SITE->get('id'), 'rayon_id' => $this->get('id'), 'article_id' => $article->get('id')]);
        foreach ($links as $link) {
            $lm->delete($link);
        }

        // Update article metadata
        $article = $am->refreshMetadata($article);
        $am->update($article);

        return true;
    }

}

class RayonManager extends EntityManager
{
    protected $prefix = 'rayon',
              $table = 'rayons',
              $object = 'Rayon';

    public function create(array $defaults = [])
    {
        if (!isset($defaults['site_id'])) {
            $defaults['site_id'] = $this->site['site_id'];
        }

        return parent::create($defaults);
    }

    public function getAll(array $where = array(), array $options = array(), $withJoins = true)
    {
        if (!isset($where['site_id'])) {
            $where['rayons`.`site_id'] = $this->site['site_id'];
        }

        return parent::getAll($where, $options);
    }

    public function preprocess($rayon)
    {
        // Make publisher's slug from name
        $slug = makeurl($rayon->get('name'));
        $rayon->set('rayon_url', $slug);

        return $rayon;
    }

    public function validate($rayon)
    {
        if (!$rayon->has('name')) {
            throw new Exception('Le rayon doit avoir un nom.');
        }

        // Check that there is not another publisher with that name
        $other = $this->get(['rayon_url' => $rayon->get('url'), 'rayon_id' => '!= '.$rayon->get('id')]);
        if ($other) {
            throw new Exception('Il existe déjà un rayon avec le nom '.$rayon->get('name').'.');
        }

        return true;
    }
}
