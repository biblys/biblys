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


namespace Model\Base;

use \Exception;
use \PDO;
use Model\InventoryItem as ChildInventoryItem;
use Model\InventoryItemQuery as ChildInventoryItemQuery;
use Model\Map\InventoryItemTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `inventory_item` table.
 *
 * @method     ChildInventoryItemQuery orderById($order = Criteria::ASC) Order by the ii_id column
 * @method     ChildInventoryItemQuery orderByInventoryId($order = Criteria::ASC) Order by the inventory_id column
 * @method     ChildInventoryItemQuery orderByEan($order = Criteria::ASC) Order by the ii_ean column
 * @method     ChildInventoryItemQuery orderByQuantity($order = Criteria::ASC) Order by the ii_quantity column
 * @method     ChildInventoryItemQuery orderByStock($order = Criteria::ASC) Order by the ii_stock column
 * @method     ChildInventoryItemQuery orderByCreatedAt($order = Criteria::ASC) Order by the ii_created column
 * @method     ChildInventoryItemQuery orderByUpdatedAt($order = Criteria::ASC) Order by the ii_updated column
 *
 * @method     ChildInventoryItemQuery groupById() Group by the ii_id column
 * @method     ChildInventoryItemQuery groupByInventoryId() Group by the inventory_id column
 * @method     ChildInventoryItemQuery groupByEan() Group by the ii_ean column
 * @method     ChildInventoryItemQuery groupByQuantity() Group by the ii_quantity column
 * @method     ChildInventoryItemQuery groupByStock() Group by the ii_stock column
 * @method     ChildInventoryItemQuery groupByCreatedAt() Group by the ii_created column
 * @method     ChildInventoryItemQuery groupByUpdatedAt() Group by the ii_updated column
 *
 * @method     ChildInventoryItemQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildInventoryItemQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildInventoryItemQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildInventoryItemQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildInventoryItemQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildInventoryItemQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildInventoryItem|null findOne(?ConnectionInterface $con = null) Return the first ChildInventoryItem matching the query
 * @method     ChildInventoryItem findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildInventoryItem matching the query, or a new ChildInventoryItem object populated from the query conditions when no match is found
 *
 * @method     ChildInventoryItem|null findOneById(int $ii_id) Return the first ChildInventoryItem filtered by the ii_id column
 * @method     ChildInventoryItem|null findOneByInventoryId(int $inventory_id) Return the first ChildInventoryItem filtered by the inventory_id column
 * @method     ChildInventoryItem|null findOneByEan(string $ii_ean) Return the first ChildInventoryItem filtered by the ii_ean column
 * @method     ChildInventoryItem|null findOneByQuantity(int $ii_quantity) Return the first ChildInventoryItem filtered by the ii_quantity column
 * @method     ChildInventoryItem|null findOneByStock(int $ii_stock) Return the first ChildInventoryItem filtered by the ii_stock column
 * @method     ChildInventoryItem|null findOneByCreatedAt(string $ii_created) Return the first ChildInventoryItem filtered by the ii_created column
 * @method     ChildInventoryItem|null findOneByUpdatedAt(string $ii_updated) Return the first ChildInventoryItem filtered by the ii_updated column
 *
 * @method     ChildInventoryItem requirePk($key, ?ConnectionInterface $con = null) Return the ChildInventoryItem by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInventoryItem requireOne(?ConnectionInterface $con = null) Return the first ChildInventoryItem matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildInventoryItem requireOneById(int $ii_id) Return the first ChildInventoryItem filtered by the ii_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInventoryItem requireOneByInventoryId(int $inventory_id) Return the first ChildInventoryItem filtered by the inventory_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInventoryItem requireOneByEan(string $ii_ean) Return the first ChildInventoryItem filtered by the ii_ean column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInventoryItem requireOneByQuantity(int $ii_quantity) Return the first ChildInventoryItem filtered by the ii_quantity column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInventoryItem requireOneByStock(int $ii_stock) Return the first ChildInventoryItem filtered by the ii_stock column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInventoryItem requireOneByCreatedAt(string $ii_created) Return the first ChildInventoryItem filtered by the ii_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInventoryItem requireOneByUpdatedAt(string $ii_updated) Return the first ChildInventoryItem filtered by the ii_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildInventoryItem[]|Collection find(?ConnectionInterface $con = null) Return ChildInventoryItem objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildInventoryItem> find(?ConnectionInterface $con = null) Return ChildInventoryItem objects based on current ModelCriteria
 *
 * @method     ChildInventoryItem[]|Collection findById(int|array<int> $ii_id) Return ChildInventoryItem objects filtered by the ii_id column
 * @psalm-method Collection&\Traversable<ChildInventoryItem> findById(int|array<int> $ii_id) Return ChildInventoryItem objects filtered by the ii_id column
 * @method     ChildInventoryItem[]|Collection findByInventoryId(int|array<int> $inventory_id) Return ChildInventoryItem objects filtered by the inventory_id column
 * @psalm-method Collection&\Traversable<ChildInventoryItem> findByInventoryId(int|array<int> $inventory_id) Return ChildInventoryItem objects filtered by the inventory_id column
 * @method     ChildInventoryItem[]|Collection findByEan(string|array<string> $ii_ean) Return ChildInventoryItem objects filtered by the ii_ean column
 * @psalm-method Collection&\Traversable<ChildInventoryItem> findByEan(string|array<string> $ii_ean) Return ChildInventoryItem objects filtered by the ii_ean column
 * @method     ChildInventoryItem[]|Collection findByQuantity(int|array<int> $ii_quantity) Return ChildInventoryItem objects filtered by the ii_quantity column
 * @psalm-method Collection&\Traversable<ChildInventoryItem> findByQuantity(int|array<int> $ii_quantity) Return ChildInventoryItem objects filtered by the ii_quantity column
 * @method     ChildInventoryItem[]|Collection findByStock(int|array<int> $ii_stock) Return ChildInventoryItem objects filtered by the ii_stock column
 * @psalm-method Collection&\Traversable<ChildInventoryItem> findByStock(int|array<int> $ii_stock) Return ChildInventoryItem objects filtered by the ii_stock column
 * @method     ChildInventoryItem[]|Collection findByCreatedAt(string|array<string> $ii_created) Return ChildInventoryItem objects filtered by the ii_created column
 * @psalm-method Collection&\Traversable<ChildInventoryItem> findByCreatedAt(string|array<string> $ii_created) Return ChildInventoryItem objects filtered by the ii_created column
 * @method     ChildInventoryItem[]|Collection findByUpdatedAt(string|array<string> $ii_updated) Return ChildInventoryItem objects filtered by the ii_updated column
 * @psalm-method Collection&\Traversable<ChildInventoryItem> findByUpdatedAt(string|array<string> $ii_updated) Return ChildInventoryItem objects filtered by the ii_updated column
 *
 * @method     ChildInventoryItem[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildInventoryItem> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class InventoryItemQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\InventoryItemQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\InventoryItem', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildInventoryItemQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildInventoryItemQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildInventoryItemQuery) {
            return $criteria;
        }
        $query = new ChildInventoryItemQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildInventoryItem|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(InventoryItemTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = InventoryItemTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildInventoryItem A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT ii_id, inventory_id, ii_ean, ii_quantity, ii_stock, ii_created, ii_updated FROM inventory_item WHERE ii_id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildInventoryItem $obj */
            $obj = new ChildInventoryItem();
            $obj->hydrate($row);
            InventoryItemTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @return ChildInventoryItem|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param array $keys Primary keys to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return Collection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param mixed $key Primary key to use for the query
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        $this->addUsingAlias(InventoryItemTableMap::COL_II_ID, $key, Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param array|int $keys The list of primary key to use for the query
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        $this->addUsingAlias(InventoryItemTableMap::COL_II_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the ii_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE ii_id = 1234
     * $query->filterById(array(12, 34)); // WHERE ii_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE ii_id > 12
     * </code>
     *
     * @param mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterById($id = null, ?string $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(InventoryItemTableMap::COL_II_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(InventoryItemTableMap::COL_II_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(InventoryItemTableMap::COL_II_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the inventory_id column
     *
     * Example usage:
     * <code>
     * $query->filterByInventoryId(1234); // WHERE inventory_id = 1234
     * $query->filterByInventoryId(array(12, 34)); // WHERE inventory_id IN (12, 34)
     * $query->filterByInventoryId(array('min' => 12)); // WHERE inventory_id > 12
     * </code>
     *
     * @param mixed $inventoryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByInventoryId($inventoryId = null, ?string $comparison = null)
    {
        if (is_array($inventoryId)) {
            $useMinMax = false;
            if (isset($inventoryId['min'])) {
                $this->addUsingAlias(InventoryItemTableMap::COL_INVENTORY_ID, $inventoryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($inventoryId['max'])) {
                $this->addUsingAlias(InventoryItemTableMap::COL_INVENTORY_ID, $inventoryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(InventoryItemTableMap::COL_INVENTORY_ID, $inventoryId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the ii_ean column
     *
     * Example usage:
     * <code>
     * $query->filterByEan('fooValue');   // WHERE ii_ean = 'fooValue'
     * $query->filterByEan('%fooValue%', Criteria::LIKE); // WHERE ii_ean LIKE '%fooValue%'
     * $query->filterByEan(['foo', 'bar']); // WHERE ii_ean IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $ean The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEan($ean = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($ean)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(InventoryItemTableMap::COL_II_EAN, $ean, $comparison);

        return $this;
    }

    /**
     * Filter the query on the ii_quantity column
     *
     * Example usage:
     * <code>
     * $query->filterByQuantity(1234); // WHERE ii_quantity = 1234
     * $query->filterByQuantity(array(12, 34)); // WHERE ii_quantity IN (12, 34)
     * $query->filterByQuantity(array('min' => 12)); // WHERE ii_quantity > 12
     * </code>
     *
     * @param mixed $quantity The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByQuantity($quantity = null, ?string $comparison = null)
    {
        if (is_array($quantity)) {
            $useMinMax = false;
            if (isset($quantity['min'])) {
                $this->addUsingAlias(InventoryItemTableMap::COL_II_QUANTITY, $quantity['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($quantity['max'])) {
                $this->addUsingAlias(InventoryItemTableMap::COL_II_QUANTITY, $quantity['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(InventoryItemTableMap::COL_II_QUANTITY, $quantity, $comparison);

        return $this;
    }

    /**
     * Filter the query on the ii_stock column
     *
     * Example usage:
     * <code>
     * $query->filterByStock(1234); // WHERE ii_stock = 1234
     * $query->filterByStock(array(12, 34)); // WHERE ii_stock IN (12, 34)
     * $query->filterByStock(array('min' => 12)); // WHERE ii_stock > 12
     * </code>
     *
     * @param mixed $stock The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByStock($stock = null, ?string $comparison = null)
    {
        if (is_array($stock)) {
            $useMinMax = false;
            if (isset($stock['min'])) {
                $this->addUsingAlias(InventoryItemTableMap::COL_II_STOCK, $stock['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($stock['max'])) {
                $this->addUsingAlias(InventoryItemTableMap::COL_II_STOCK, $stock['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(InventoryItemTableMap::COL_II_STOCK, $stock, $comparison);

        return $this;
    }

    /**
     * Filter the query on the ii_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE ii_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE ii_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE ii_created > '2011-03-13'
     * </code>
     *
     * @param mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, ?string $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(InventoryItemTableMap::COL_II_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(InventoryItemTableMap::COL_II_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(InventoryItemTableMap::COL_II_CREATED, $createdAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the ii_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE ii_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE ii_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE ii_updated > '2011-03-13'
     * </code>
     *
     * @param mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, ?string $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(InventoryItemTableMap::COL_II_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(InventoryItemTableMap::COL_II_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(InventoryItemTableMap::COL_II_UPDATED, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Exclude object from result
     *
     * @param ChildInventoryItem $inventoryItem Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($inventoryItem = null)
    {
        if ($inventoryItem) {
            $this->addUsingAlias(InventoryItemTableMap::COL_II_ID, $inventoryItem->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the inventory_item table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(InventoryItemTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            InventoryItemTableMap::clearInstancePool();
            InventoryItemTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(InventoryItemTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(InventoryItemTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            InventoryItemTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            InventoryItemTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param int $nbDays Maximum age of the latest update in days
     *
     * @return $this The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        $this->addUsingAlias(InventoryItemTableMap::COL_II_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(InventoryItemTableMap::COL_II_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(InventoryItemTableMap::COL_II_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(InventoryItemTableMap::COL_II_CREATED);

        return $this;
    }

    /**
     * Filter by the latest created
     *
     * @param int $nbDays Maximum age of in days
     *
     * @return $this The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        $this->addUsingAlias(InventoryItemTableMap::COL_II_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(InventoryItemTableMap::COL_II_CREATED);

        return $this;
    }

}
