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

    public function getModel(): \Model\Event
    {
        $model = new \Model\Event();
        $model->setId($this->get('id'));

        return $model;
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
