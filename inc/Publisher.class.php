<?php

use Biblys\Exception\EntityAlreadyExistsException;
use Biblys\Exception\InvalidEntityException;

class Publisher extends Entity
{
    protected $prefix = 'publisher';

    public function hasLogo()
    {
        $media = new Media("publisher", $this->get("id"));
        if ($media->exists()) {
            return true;
        }

        return false;
    }

    public function getLogo()
    {
        if ($this->hasLogo()) {
            return new Media("publisher", $this->get("id"));
        }

        $publisherName = $this->get("name");
        throw new Error("Publisher $publisherName has no logo");
    }

    /**
     * Save uploaded file as publisher's logo
     * @param UploadedFile $file a file that was uploaded
     * @return Media             the contributor's saved Media
     */
    public function addLogo($file)
    {
        if ($file->getMimeType() !== 'image/png') {
            throw new Exception('La photo doit être au format PNG.');
        }

        $logo = new Media('publisher', $this->get('id'));
        $logo->upload($file->getRealPath());

        return $logo;
    }

    public function countArticles()
    {
        $am = new ArticleManager();
        return $am->count(['publisher_id' => $this->get('id')]);
    }

    public function getArticles()
    {
        $am = new ArticleManager();
        return $am->getAll(['publisher_id' => $this->get('id')]);
    }

    /**
     * Returns all publisher's supplers
     * @return an array of Suppliers
     */
    public function getSuppliers()
    {
        global $site, $_SQL;

        $query = $_SQL->prepare("SELECT `supplier_id`, `supplier_name` FROM `links` JOIN `suppliers` USING(`supplier_id`) WHERE `links`.`site_id` = :site AND `suppliers`.`site_id` = :site AND `publisher_id` = :publisher AND `supplier_deleted` IS NULL");
        $query->execute(['site' => $site->get('id'), 'publisher' => $this->get('id')]);
        $query = $query->fetchAll();

        $suppliers = [];
        foreach ($query as $q) {
            $suppliers[] = new Supplier($q);
        }

        return $suppliers;
    }

    /**
     * Add a publisher's supplier
     */
    public function addSupplier(Supplier $supplier)
    {
        global $site;

        $lm = new LinkManager();

        $link = $lm->get([
            'site_id' => $site->get('id'),
            'supplier_id' => $supplier->get('id'),
            'publisher_id' => $this->get('id')
        ]);

        if (!$link) {
            $lm->create([
                'site_id' => $site->get('id'),
                'supplier_id' => $supplier->get('id'),
                'publisher_id' => $this->get('id')
            ]);
        }
    }

    /**
     * Remove a publisher's supplier
     */
    public function removeSupplier(Supplier $supplier)
    {
        global $site;

        $lm = new LinkManager();

        $link = $lm->get([
            'site_id' => $site->get('id'),
            'supplier_id' => $supplier->get('id'),
            'publisher_id' => $this->get('id')
        ]);

        if ($link) {
            $lm->delete($link);
        }
    }


    /**
     * Returns revenue for all sales of this publisher
     * @param {int} $year: year filter
     * @return {int} the revenue for this publisher
     */
    public function getRevenue($year = 'all')
    {
        global $_SQL;

        if ($year == 'current') {
            $year = date('Y');
        }

        $am = new ArticleManager();
        $sm = new StockManager();

        $revenue = 0;
        $params = ['publisher_id' => $this->get('id')];

        $req_date = 'IS NOT NULL';
        if ($year != 'all') {
            $req_date = 'LIKE :year';
            $params['year'] = $year . '%';
        }

        $sql = $_SQL->prepare("SELECT `stock_selling_price_ht` FROM `stock` JOIN `articles` USING(`article_id`) WHERE `articles`.`publisher_id` = :publisher_id AND `stock_selling_date`" . $req_date);
        $sql->execute($params);
        foreach ($sql->fetchAll(PDO::FETCH_ASSOC) as $copy) {
            $revenue += $copy['stock_selling_price_ht'];
        }

        return $revenue;
    }

    public function getRights()
    {
        $rm = new RightManager();
        return $rm->getAll(["publisher_id" => $this->get('id')]);
    }
}

class PublisherManager extends EntityManager
{
    protected $prefix = 'publisher',
        $table = 'publishers',
        $object = 'Publisher',
        $ignoreSiteFilters = false;

    /**
     * Add site filters if any defined
     * @param [type] $where [description]
     */
    public function addSiteFilters(array $where = [])
    {
        if ($this->ignoreSiteFilters) {
            return $where;
        }

        global $site;

        $publisherFilter = $site->getOpt('publisher_filter');
        if ($publisherFilter && !array_key_exists('publisher_id', $where)) {
            $where['publisher_id'] = explode(',', $publisherFilter);
        }

        return $where;
    }

    /**
     * Calls Entity->getAll after adding site filter
     */
    public function getAll(array $where = array(), array $options =  array(), $withJoins = true)
    {

        $where = $this->addSiteFilters($where);
        return parent::getAll($where, $options, $withJoins);
    }

    public function preprocess($publisher)
    {
        $name = $publisher->get('name');

        // Uppercase publisher's name
        $name = mb_strtoupper($name, 'UTF-8');

        // Alphabetize publiser's name
        $alpha = alphabetize($name, 'UTF-8');

        // Make publisher's slug from name
        $slug = makeurl($name);

        $publisher->set('publisher_name', $name);
        $publisher->set('publisher_name_alphabetic', $alpha);
        $publisher->set('publisher_url', $slug);

        return $publisher;
    }

    public function validate($publisher)
    {
        if (!$publisher->has("name")) {
            throw new InvalidEntityException("L'éditeur doit avoir un nom.");
        }

        // Check that there is not another publisher with that name
        $other = $this->get(["publisher_url" => $publisher->get("url"), "publisher_id" => "!= " . $publisher->get('id')]);
        if ($other) {
            throw new EntityAlreadyExistsException(
                "Il existe déjà un éditeur avec le nom " . $publisher->get("name") . "."
            );
        }

        return true;
    }
}
