<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\StockItemList as ChildStockItemList;
use Model\StockItemListQuery as ChildStockItemListQuery;
use Model\Map\StockItemListTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `lists` table.
 *
 * @method     ChildStockItemListQuery orderById($order = Criteria::ASC) Order by the list_id column
 * @method     ChildStockItemListQuery orderByAxysUserId($order = Criteria::ASC) Order by the axys_user_id column
 * @method     ChildStockItemListQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildStockItemListQuery orderByTitle($order = Criteria::ASC) Order by the list_title column
 * @method     ChildStockItemListQuery orderByUrl($order = Criteria::ASC) Order by the list_url column
 * @method     ChildStockItemListQuery orderByCreatedAt($order = Criteria::ASC) Order by the list_created column
 * @method     ChildStockItemListQuery orderByUpdatedAt($order = Criteria::ASC) Order by the list_updated column
 *
 * @method     ChildStockItemListQuery groupById() Group by the list_id column
 * @method     ChildStockItemListQuery groupByAxysUserId() Group by the axys_user_id column
 * @method     ChildStockItemListQuery groupBySiteId() Group by the site_id column
 * @method     ChildStockItemListQuery groupByTitle() Group by the list_title column
 * @method     ChildStockItemListQuery groupByUrl() Group by the list_url column
 * @method     ChildStockItemListQuery groupByCreatedAt() Group by the list_created column
 * @method     ChildStockItemListQuery groupByUpdatedAt() Group by the list_updated column
 *
 * @method     ChildStockItemListQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildStockItemListQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildStockItemListQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildStockItemListQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildStockItemListQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildStockItemListQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildStockItemList|null findOne(?ConnectionInterface $con = null) Return the first ChildStockItemList matching the query
 * @method     ChildStockItemList findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildStockItemList matching the query, or a new ChildStockItemList object populated from the query conditions when no match is found
 *
 * @method     ChildStockItemList|null findOneById(int $list_id) Return the first ChildStockItemList filtered by the list_id column
 * @method     ChildStockItemList|null findOneByAxysUserId(int $axys_user_id) Return the first ChildStockItemList filtered by the axys_user_id column
 * @method     ChildStockItemList|null findOneBySiteId(int $site_id) Return the first ChildStockItemList filtered by the site_id column
 * @method     ChildStockItemList|null findOneByTitle(string $list_title) Return the first ChildStockItemList filtered by the list_title column
 * @method     ChildStockItemList|null findOneByUrl(string $list_url) Return the first ChildStockItemList filtered by the list_url column
 * @method     ChildStockItemList|null findOneByCreatedAt(string $list_created) Return the first ChildStockItemList filtered by the list_created column
 * @method     ChildStockItemList|null findOneByUpdatedAt(string $list_updated) Return the first ChildStockItemList filtered by the list_updated column
 *
 * @method     ChildStockItemList requirePk($key, ?ConnectionInterface $con = null) Return the ChildStockItemList by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStockItemList requireOne(?ConnectionInterface $con = null) Return the first ChildStockItemList matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildStockItemList requireOneById(int $list_id) Return the first ChildStockItemList filtered by the list_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStockItemList requireOneByAxysUserId(int $axys_user_id) Return the first ChildStockItemList filtered by the axys_user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStockItemList requireOneBySiteId(int $site_id) Return the first ChildStockItemList filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStockItemList requireOneByTitle(string $list_title) Return the first ChildStockItemList filtered by the list_title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStockItemList requireOneByUrl(string $list_url) Return the first ChildStockItemList filtered by the list_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStockItemList requireOneByCreatedAt(string $list_created) Return the first ChildStockItemList filtered by the list_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStockItemList requireOneByUpdatedAt(string $list_updated) Return the first ChildStockItemList filtered by the list_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildStockItemList[]|Collection find(?ConnectionInterface $con = null) Return ChildStockItemList objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildStockItemList> find(?ConnectionInterface $con = null) Return ChildStockItemList objects based on current ModelCriteria
 *
 * @method     ChildStockItemList[]|Collection findById(int|array<int> $list_id) Return ChildStockItemList objects filtered by the list_id column
 * @psalm-method Collection&\Traversable<ChildStockItemList> findById(int|array<int> $list_id) Return ChildStockItemList objects filtered by the list_id column
 * @method     ChildStockItemList[]|Collection findByAxysUserId(int|array<int> $axys_user_id) Return ChildStockItemList objects filtered by the axys_user_id column
 * @psalm-method Collection&\Traversable<ChildStockItemList> findByAxysUserId(int|array<int> $axys_user_id) Return ChildStockItemList objects filtered by the axys_user_id column
 * @method     ChildStockItemList[]|Collection findBySiteId(int|array<int> $site_id) Return ChildStockItemList objects filtered by the site_id column
 * @psalm-method Collection&\Traversable<ChildStockItemList> findBySiteId(int|array<int> $site_id) Return ChildStockItemList objects filtered by the site_id column
 * @method     ChildStockItemList[]|Collection findByTitle(string|array<string> $list_title) Return ChildStockItemList objects filtered by the list_title column
 * @psalm-method Collection&\Traversable<ChildStockItemList> findByTitle(string|array<string> $list_title) Return ChildStockItemList objects filtered by the list_title column
 * @method     ChildStockItemList[]|Collection findByUrl(string|array<string> $list_url) Return ChildStockItemList objects filtered by the list_url column
 * @psalm-method Collection&\Traversable<ChildStockItemList> findByUrl(string|array<string> $list_url) Return ChildStockItemList objects filtered by the list_url column
 * @method     ChildStockItemList[]|Collection findByCreatedAt(string|array<string> $list_created) Return ChildStockItemList objects filtered by the list_created column
 * @psalm-method Collection&\Traversable<ChildStockItemList> findByCreatedAt(string|array<string> $list_created) Return ChildStockItemList objects filtered by the list_created column
 * @method     ChildStockItemList[]|Collection findByUpdatedAt(string|array<string> $list_updated) Return ChildStockItemList objects filtered by the list_updated column
 * @psalm-method Collection&\Traversable<ChildStockItemList> findByUpdatedAt(string|array<string> $list_updated) Return ChildStockItemList objects filtered by the list_updated column
 *
 * @method     ChildStockItemList[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildStockItemList> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class StockItemListQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\StockItemListQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\StockItemList', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildStockItemListQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildStockItemListQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildStockItemListQuery) {
            return $criteria;
        }
        $query = new ChildStockItemListQuery();
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
     * @return ChildStockItemList|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(StockItemListTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = StockItemListTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildStockItemList A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT list_id, axys_user_id, site_id, list_title, list_url, list_created, list_updated FROM lists WHERE list_id = :p0';
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
            /** @var ChildStockItemList $obj */
            $obj = new ChildStockItemList();
            $obj->hydrate($row);
            StockItemListTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildStockItemList|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(StockItemListTableMap::COL_LIST_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(StockItemListTableMap::COL_LIST_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the list_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE list_id = 1234
     * $query->filterById(array(12, 34)); // WHERE list_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE list_id > 12
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
                $this->addUsingAlias(StockItemListTableMap::COL_LIST_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(StockItemListTableMap::COL_LIST_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(StockItemListTableMap::COL_LIST_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the axys_user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAxysUserId(1234); // WHERE axys_user_id = 1234
     * $query->filterByAxysUserId(array(12, 34)); // WHERE axys_user_id IN (12, 34)
     * $query->filterByAxysUserId(array('min' => 12)); // WHERE axys_user_id > 12
     * </code>
     *
     * @param mixed $axysUserId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAxysUserId($axysUserId = null, ?string $comparison = null)
    {
        if (is_array($axysUserId)) {
            $useMinMax = false;
            if (isset($axysUserId['min'])) {
                $this->addUsingAlias(StockItemListTableMap::COL_AXYS_USER_ID, $axysUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($axysUserId['max'])) {
                $this->addUsingAlias(StockItemListTableMap::COL_AXYS_USER_ID, $axysUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(StockItemListTableMap::COL_AXYS_USER_ID, $axysUserId, $comparison);

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
                $this->addUsingAlias(StockItemListTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(StockItemListTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(StockItemListTableMap::COL_SITE_ID, $siteId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the list_title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE list_title = 'fooValue'
     * $query->filterByTitle('%fooValue%', Criteria::LIKE); // WHERE list_title LIKE '%fooValue%'
     * $query->filterByTitle(['foo', 'bar']); // WHERE list_title IN ('foo', 'bar')
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

        $this->addUsingAlias(StockItemListTableMap::COL_LIST_TITLE, $title, $comparison);

        return $this;
    }

    /**
     * Filter the query on the list_url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE list_url = 'fooValue'
     * $query->filterByUrl('%fooValue%', Criteria::LIKE); // WHERE list_url LIKE '%fooValue%'
     * $query->filterByUrl(['foo', 'bar']); // WHERE list_url IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $url The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUrl($url = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($url)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(StockItemListTableMap::COL_LIST_URL, $url, $comparison);

        return $this;
    }

    /**
     * Filter the query on the list_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE list_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE list_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE list_created > '2011-03-13'
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
                $this->addUsingAlias(StockItemListTableMap::COL_LIST_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(StockItemListTableMap::COL_LIST_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(StockItemListTableMap::COL_LIST_CREATED, $createdAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the list_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE list_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE list_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE list_updated > '2011-03-13'
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
                $this->addUsingAlias(StockItemListTableMap::COL_LIST_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(StockItemListTableMap::COL_LIST_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(StockItemListTableMap::COL_LIST_UPDATED, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Exclude object from result
     *
     * @param ChildStockItemList $stockItemList Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($stockItemList = null)
    {
        if ($stockItemList) {
            $this->addUsingAlias(StockItemListTableMap::COL_LIST_ID, $stockItemList->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the lists table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(StockItemListTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            StockItemListTableMap::clearInstancePool();
            StockItemListTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(StockItemListTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(StockItemListTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            StockItemListTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            StockItemListTableMap::clearRelatedInstancePool();

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
        $this->addUsingAlias(StockItemListTableMap::COL_LIST_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(StockItemListTableMap::COL_LIST_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(StockItemListTableMap::COL_LIST_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(StockItemListTableMap::COL_LIST_CREATED);

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
        $this->addUsingAlias(StockItemListTableMap::COL_LIST_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(StockItemListTableMap::COL_LIST_CREATED);

        return $this;
    }

}
