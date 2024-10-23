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


use Biblys\Exception\EntityAlreadyExistsException;
use Biblys\Exception\InvalidEntityException;
use Biblys\Legacy\LegacyCodeHelper;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Publisher extends Entity
{
    protected $prefix = 'publisher';

    public static function buildFromModel(\Model\Publisher $publisher): self
    {
        return new Publisher([
            "publisher_id" => $publisher->getId(),
            "publisher_name" => $publisher->getName(),
            "publisher_url" => $publisher->getUrl(),
        ]);
    }

    public function getModel(): \Model\Publisher
    {
        $publisher = new \Model\Publisher();
        $publisher->setId($this->get('id'));
        $publisher->setName($this->get('name'));
        $publisher->setUrl($this->get('url'));

        return $publisher;
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
        global $_SQL;

        $globalSite = LegacyCodeHelper::getGlobalSite();

        $query = $_SQL->prepare("SELECT `supplier_id`, `supplier_name` FROM `links` JOIN `suppliers` USING(`supplier_id`) WHERE `links`.`site_id` = :site AND `suppliers`.`site_id` = :site AND `publisher_id` = :publisher");
        $query->execute(['site' => $globalSite->get('id'), 'publisher' => $this->get('id')]);
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
        $globalSite = LegacyCodeHelper::getGlobalSite();

        $lm = new LinkManager();

        $link = $lm->get([
            'site_id' => $globalSite->get('id'),
            'supplier_id' => $supplier->get('id'),
            'publisher_id' => $this->get('id')
        ]);

        if (!$link) {
            $lm->create([
                'site_id' => $globalSite->get('id'),
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
        $globalSite = LegacyCodeHelper::getGlobalSite();

        $lm = new LinkManager();

        $link = $lm->get([
            'site_id' => $globalSite->get('id'),
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
    private bool $isSiteFilterEnabled = true;

    public function enableSiteFilter(): void
    {
        $this->isSiteFilterEnabled = true;
    }

    public function disableSiteFilter(): void
    {
        $this->isSiteFilterEnabled = false;
    }

    /**
     * Add site filters if any defined
     * @param array $where
     * @return array
     */
    public function addSiteFilters(array $where = []): array
    {
        if (!$this->isSiteFilterEnabled) {
            return $where;
        }

        $globalSite = LegacyCodeHelper::getGlobalSite(ignoreDeprecation: true);

        $publisherFilter = $globalSite->getOpt('publisher_filter');
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
