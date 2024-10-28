<?php

use Biblys\Legacy\LegacyCodeHelper;

/**
 *
 */
class Event extends Entity
{
    protected $prefix = 'event';
    
    public function __construct(array $data)
    {
        if (isset($data['event_start'])) {
            $start = explode(' ', $data['event_start']);
            $this->set('event_start_date', $start[0]);
            $this->set('event_start_time', $start[1]);
        }
        
        if (isset($data['event_end'])) {
            $end = explode(' ', $data['event_end']);
            $this->set('event_end_date', $end[0]);
            $this->set('event_end_time', $end[1]);
        }
        
        parent::__construct($data);
    }

    public function hasIllustration(): bool
    {
        return false;
    }

    public function getArticles()
    {
        $lm = new LinkManager();
        $am = new ArticleManager();
        $am->setIgnoreSiteFilters(true);

        $articles = [];
        $links = $lm->getAll([
            "event_id" => $this->get("id"), 
            "article_id" => "NOT NULL"
        ]);
        foreach ($links as $link) {
            $articleId = $link->get('article_id');
            $article = $am->getById($articleId);
            if ($article) {
                $articles[] = $article;
            }
        }

        return $articles;
    }
}

class EventManager extends EntityManager
{
    protected $prefix = 'event';
    protected $table = 'events';
    protected $object = 'Event';
    
    public function create(array $defaults = array())
    {
        if (!isset($defaults['site_id'])) {
            $defaults['site_id'] = $this->site['site_id'];
        }
        return parent::create($defaults);
    }

    /**
     * @throws Exception
     */
    public function getAll(array $where = array(), array $options = array(), $withJoins = true)
    {
        if ($this->siteAgnostic === false && !isset($where['site_id'])) {
            $globalSite = LegacyCodeHelper::getGlobalSite(ignoreDeprecation: true);
            $where['site_id'] = $globalSite->get('id');
        }

        return parent::getAll($where, $options);
    }

    /**
     * @throws Exception
     */
    public function update($entity, $reason = null)
    {
        $entity->remove("event_start_date");
        $entity->remove("event_start_time");
        $entity->remove("event_end_date");
        $entity->remove("event_end_time");

        return parent::update($entity, $reason);
    }
}
