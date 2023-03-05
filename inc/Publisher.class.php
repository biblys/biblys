<?php

use Biblys\Exception\EntityAlreadyExistsException;
use Biblys\Exception\InvalidEntityException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Publisher extends Entity
{
    protected $prefix = 'publisher';

    public function hasLogo(): bool
    {
        $media = new Media("publisher", $this->get("id"));
        if ($media->exists()) {
            return true;
        }

        return false;
    }

    public function getLogo(): Media
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
     * @throws Exception
     */
    public function addLogo(UploadedFile $file): Media
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

    public function getArticles(): array
    {
        $am = new ArticleManager();
        return $am->getAll(['publisher_id' => $this->get('id')]);
    }

    /**
     * Returns all publisher's supplers
     * @return Supplier[]
     */
    public function getSuppliers(): array
    {
        global $site, $_SQL;

        $query = $_SQL->prepare("SELECT `supplier_id`, `supplier_name` FROM `links` JOIN `suppliers` USING(`supplier_id`) WHERE `links`.`site_id` = :site AND `suppliers`.`site_id` = :site AND `publisher_id` = :publisher");
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
     * @throws Exception
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
     * @throws Exception
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
     * @param string $year
     * @return int|mixed {int} the revenue for this publisher
     */
    public function getRevenue(string $year = 'all'): mixed
    {
        global $_SQL;

        if ($year == 'current') {
            $year = date('Y');
        }

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

    public function getRights(): array
    {
        $rm = new RightManager();
        return $rm->getAll(["publisher_id" => $this->get('id')]);
    }
}

class PublisherManager extends EntityManager
{
    protected $prefix = 'publisher';
    protected $table = 'publishers';
    protected $object = 'Publisher';

    /**
     * Add site filters if any defined
     * @param array $where
     * @return array
     */
    public function addSiteFilters(array $where = []): array
    {

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
    public function getAll(array $where = array(), array $options =  array(), $withJoins = true): array
    {

        $where = $this->addSiteFilters($where);
        return parent::getAll($where, $options, $withJoins);
    }

    public function preprocess($entity): Entity
    {
        $name = $entity->get('name');

        // Uppercase publisher's name
        $name = mb_strtoupper($name, 'UTF-8');

        // Alphabetize publiser's name
        $alpha = alphabetize($name);

        // Make publisher's slug from name
        $slug = makeurl($name);

        $entity->set('publisher_name', $name);
        $entity->set('publisher_name_alphabetic', $alpha);
        $entity->set('publisher_url', $slug);

        return $entity;
    }

    public function validate($entity): bool
    {
        if (!$entity->has("name")) {
            throw new InvalidEntityException("L'éditeur doit avoir un nom.");
        }

        // Check that there is not another publisher with that name
        $other = $this->get(["publisher_url" => $entity->get("url"), "publisher_id" => "!= " . $entity->get('id')]);
        if ($other) {
            throw new EntityAlreadyExistsException(
                "Il existe déjà un éditeur avec le nom " . $entity->get("name") . "."
            );
        }

        return true;
    }
}
