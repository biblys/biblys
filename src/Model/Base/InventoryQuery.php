<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Inventory as ChildInventory;
use Model\InventoryQuery as ChildInventoryQuery;
use Model\Map\InventoryTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'inventory' table.
 *
 *
 *
 * @method     ChildInventoryQuery orderById($order = Criteria::ASC) Order by the inventory_id column
 * @method     ChildInventoryQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildInventoryQuery orderByTitle($order = Criteria::ASC) Order by the inventory_title column
 * @method     ChildInventoryQuery orderByCreatedAt($order = Criteria::ASC) Order by the inventory_created column
 * @method     ChildInventoryQuery orderByUpdatedAt($order = Criteria::ASC) Order by the inventory_updated column
 *
 * @method     ChildInventoryQuery groupById() Group by the inventory_id column
 * @method     ChildInventoryQuery groupBySiteId() Group by the site_id column
 * @method     ChildInventoryQuery groupByTitle() Group by the inventory_title column
 * @method     ChildInventoryQuery groupByCreatedAt() Group by the inventory_created column
 * @method     ChildInventoryQuery groupByUpdatedAt() Group by the inventory_updated column
 *
 * @method     ChildInventoryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildInventoryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildInventoryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildInventoryQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildInventoryQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildInventoryQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildInventory|null findOne(?ConnectionInterface $con = null) Return the first ChildInventory matching the query
 * @method     ChildInventory findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildInventory matching the query, or a new ChildInventory object populated from the query conditions when no match is found
 *
 * @method     ChildInventory|null findOneById(int $inventory_id) Return the first ChildInventory filtered by the inventory_id column
 * @method     ChildInventory|null findOneBySiteId(int $site_id) Return the first ChildInventory filtered by the site_id column
 * @method     ChildInventory|null findOneByTitle(string $inventory_title) Return the first ChildInventory filtered by the inventory_title column
 * @method     ChildInventory|null findOneByCreatedAt(string $inventory_created) Return the first ChildInventory filtered by the inventory_created column
 * @method     ChildInventory|null findOneByUpdatedAt(string $inventory_updated) Return the first ChildInventory filtered by the inventory_updated column *

 * @method     ChildInventory requirePk($key, ?ConnectionInterface $con = null) Return the ChildInventory by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInventory requireOne(?ConnectionInterface $con = null) Return the first ChildInventory matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildInventory requireOneById(int $inventory_id) Return the first ChildInventory filtered by the inventory_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInventory requireOneBySiteId(int $site_id) Return the first ChildInventory filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInventory requireOneByTitle(string $inventory_title) Return the first ChildInventory filtered by the inventory_title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInventory requireOneByCreatedAt(string $inventory_created) Return the first ChildInventory filtered by the inventory_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInventory requireOneByUpdatedAt(string $inventory_updated) Return the first ChildInventory filtered by the inventory_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildInventory[]|Collection find(?ConnectionInterface $con = null) Return ChildInventory objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildInventory> find(?ConnectionInterface $con = null) Return ChildInventory objects based on current ModelCriteria
 * @method     ChildInventory[]|Collection findById(int $inventory_id) Return ChildInventory objects filtered by the inventory_id column
 * @psalm-method Collection&\Traversable<ChildInventory> findById(int $inventory_id) Return ChildInventory objects filtered by the inventory_id column
 * @method     ChildInventory[]|Collection findBySiteId(int $site_id) Return ChildInventory objects filtered by the site_id column
 * @psalm-method Collection&\Traversable<ChildInventory> findBySiteId(int $site_id) Return ChildInventory objects filtered by the site_id column
 * @method     ChildInventory[]|Collection findByTitle(string $inventory_title) Return ChildInventory objects filtered by the inventory_title column
 * @psalm-method Collection&\Traversable<ChildInventory> findByTitle(string $inventory_title) Return ChildInventory objects filtered by the inventory_title column
 * @method     ChildInventory[]|Collection findByCreatedAt(string $inventory_created) Return ChildInventory objects filtered by the inventory_created column
 * @psalm-method Collection&\Traversable<ChildInventory> findByCreatedAt(string $inventory_created) Return ChildInventory objects filtered by the inventory_created column
 * @method     ChildInventory[]|Collection findByUpdatedAt(string $inventory_updated) Return ChildInventory objects filtered by the inventory_updated column
 * @psalm-method Collection&\Traversable<ChildInventory> findByUpdatedAt(string $inventory_updated) Return ChildInventory objects filtered by the inventory_updated column
 * @method     ChildInventory[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildInventory> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class InventoryQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\InventoryQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Inventory', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildInventoryQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildInventoryQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildInventoryQuery) {
            return $criteria;
        }
        $query = new ChildInventoryQuery();
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
     * @return ChildInventory|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(InventoryTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = InventoryTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildInventory A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT inventory_id, site_id, inventory_title, inventory_created, inventory_updated FROM inventory WHERE inventory_id = :p0';
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
            /** @var ChildInventory $obj */
            $obj = new ChildInventory();
            $obj->hydrate($row);
            InventoryTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildInventory|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(InventoryTableMap::COL_INVENTORY_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(InventoryTableMap::COL_INVENTORY_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the inventory_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE inventory_id = 1234
     * $query->filterById(array(12, 34)); // WHERE inventory_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE inventory_id > 12
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
                $this->addUsingAlias(InventoryTableMap::COL_INVENTORY_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(InventoryTableMap::COL_INVENTORY_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(InventoryTableMap::COL_INVENTORY_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the site_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySiteId(1234); // WHERE site_id = 1234
     * $query->filterBySiteId(array(12, 34)); // WHERE site_id IN (12, 34)
     * $query->filterBySiteId(array('min' => 12)); // WHERE site_id > 12
     * </code>
     *
     * @param mixed $siteId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySiteId($siteId = null, ?string $comparison = null)
    {
        if (is_array($siteId)) {
            $useMinMax = false;
            if (isset($siteId['min'])) {
                $this->addUsingAlias(InventoryTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(InventoryTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(InventoryTableMap::COL_SITE_ID, $siteId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the inventory_title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE inventory_title = 'fooValue'
     * $query->filterByTitle('%fooValue%', Criteria::LIKE); // WHERE inventory_title LIKE '%fooValue%'
     * $query->filterByTitle(['foo', 'bar']); // WHERE inventory_title IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $title The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTitle($title = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(InventoryTableMap::COL_INVENTORY_TITLE, $title, $comparison);

        return $this;
    }

    /**
     * Filter the query on the inventory_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE inventory_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE inventory_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE inventory_created > '2011-03-13'
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
                $this->addUsingAlias(InventoryTableMap::COL_INVENTORY_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(InventoryTableMap::COL_INVENTORY_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(InventoryTableMap::COL_INVENTORY_CREATED, $createdAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the inventory_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE inventory_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE inventory_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE inventory_updated > '2011-03-13'
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
                $this->addUsingAlias(InventoryTableMap::COL_INVENTORY_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(InventoryTableMap::COL_INVENTORY_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(InventoryTableMap::COL_INVENTORY_UPDATED, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Exclude object from result
     *
     * @param ChildInventory $inventory Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($inventory = null)
    {
        if ($inventory) {
            $this->addUsingAlias(InventoryTableMap::COL_INVENTORY_ID, $inventory->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the inventory table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(InventoryTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            InventoryTableMap::clearInstancePool();
            InventoryTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(InventoryTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(InventoryTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            InventoryTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            InventoryTableMap::clearRelatedInstancePool();

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
        $this->addUsingAlias(InventoryTableMap::COL_INVENTORY_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(InventoryTableMap::COL_INVENTORY_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(InventoryTableMap::COL_INVENTORY_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(InventoryTableMap::COL_INVENTORY_CREATED);

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
        $this->addUsingAlias(InventoryTableMap::COL_INVENTORY_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(InventoryTableMap::COL_INVENTORY_CREATED);

        return $this;
    }

}
