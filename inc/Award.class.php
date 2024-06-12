<?php

class Award extends Entity
{
    protected $prefix = 'award';
    public $trackChange = false;

    public function __construct($data, $withJoins = true)
    {
        /* JOINS */

        if ($withJoins) {

            // Article (OneToMany)
            $am = new ArticleManager();
            if (isset($data['article_id'])) {
                $data['article'] = $am->getById($data['article_id']);
            }
        }

        parent::__construct($data);
    }
}

class AwardManager extends EntityManager
{
    protected $prefix = 'award',
        $table = 'awards',
        $object = 'Award';

    public function validate($award)
    {
        if (!$award->has('name')) {
            throw new Exception('Le prix littéraire doit avoir un nom.');
        }

        return true;
    }
}
