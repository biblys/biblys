<?php

class Liste extends Entity
{
    protected $prefix = 'list';

}

class ListeManager extends EntityManager
{
    protected $prefix = 'list',
              $table = 'lists',
              $object = 'Liste';

    /**
    * Create order
    * @param array $defaults
    * @return type
    */
    public function create(array $defaults = array())
    {
        if (!isset($defaults['site_id'])) {
            $defaults['site_id'] = $this->site['site_id'];
        }

        try {
            return parent::create($defaults);
        } catch(Exception $e) {
            trigger_error($e->getMessage());
        }
    }

    public function getAll(array $where = array(), array $options = array(), $withJoins = true)
    {
        $where['lists`.`site_id'] = $this->site['site_id'];

        return parent::getAll($where, $options);
    }

    public function hasStock($list, $stock)
    {
        $lm = new LinkManager();
        return $lm->get(array('list_id' => $list->get('id'), 'stock_id' => $stock->get('id')));
    }

    /**
     * Add stocks to a list
     * @param Liste $list   the list object
     * @param Array $stocks an array of stock object
     */
    public function addStock($list, $stocks)
    {
        $lm = new LinkManager();

        if (!is_array($stocks))
        {
            $stocks = array($stocks);
        }

        foreach ($stocks as $stock)
        {
            if (!$this->hasStock($list, $stock))
            {
                $lm->create(array('list_id' => $list->get('id'), 'stock_id' => $stock->get('id')));
            }
        }
    }
}
