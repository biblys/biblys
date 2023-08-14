<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Permission as ChildPermission;
use Model\PermissionQuery as ChildPermissionQuery;
use Model\Map\PermissionTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `permissions` table.
 *
 * @method     ChildPermissionQuery orderById($order = Criteria::ASC) Order by the permission_id column
 * @method     ChildPermissionQuery orderByAxysAccountId($order = Criteria::ASC) Order by the axys_account_id column
 * @method     ChildPermissionQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildPermissionQuery orderByRank($order = Criteria::ASC) Order by the permission_rank column
 * @method     ChildPermissionQuery orderByLast($order = Criteria::ASC) Order by the permission_last column
 * @method     ChildPermissionQuery orderByDate($order = Criteria::ASC) Order by the permission_date column
 *
 * @method     ChildPermissionQuery groupById() Group by the permission_id column
 * @method     ChildPermissionQuery groupByAxysAccountId() Group by the axys_account_id column
 * @method     ChildPermissionQuery groupBySiteId() Group by the site_id column
 * @method     ChildPermissionQuery groupByRank() Group by the permission_rank column
 * @method     ChildPermissionQuery groupByLast() Group by the permission_last column
 * @method     ChildPermissionQuery groupByDate() Group by the permission_date column
 *
 * @method     ChildPermissionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPermissionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPermissionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPermissionQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPermissionQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPermissionQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPermission|null findOne(?ConnectionInterface $con = null) Return the first ChildPermission matching the query
 * @method     ChildPermission findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildPermission matching the query, or a new ChildPermission object populated from the query conditions when no match is found
 *
 * @method     ChildPermission|null findOneById(int $permission_id) Return the first ChildPermission filtered by the permission_id column
 * @method     ChildPermission|null findOneByAxysAccountId(int $axys_account_id) Return the first ChildPermission filtered by the axys_account_id column
 * @method     ChildPermission|null findOneBySiteId(int $site_id) Return the first ChildPermission filtered by the site_id column
 * @method     ChildPermission|null findOneByRank(string $permission_rank) Return the first ChildPermission filtered by the permission_rank column
 * @method     ChildPermission|null findOneByLast(string $permission_last) Return the first ChildPermission filtered by the permission_last column
 * @method     ChildPermission|null findOneByDate(string $permission_date) Return the first ChildPermission filtered by the permission_date column
 *
 * @method     ChildPermission requirePk($key, ?ConnectionInterface $con = null) Return the ChildPermission by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPermission requireOne(?ConnectionInterface $con = null) Return the first ChildPermission matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPermission requireOneById(int $permission_id) Return the first ChildPermission filtered by the permission_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPermission requireOneByAxysAccountId(int $axys_account_id) Return the first ChildPermission filtered by the axys_account_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPermission requireOneBySiteId(int $site_id) Return the first ChildPermission filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPermission requireOneByRank(string $permission_rank) Return the first ChildPermission filtered by the permission_rank column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPermission requireOneByLast(string $permission_last) Return the first ChildPermission filtered by the permission_last column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPermission requireOneByDate(string $permission_date) Return the first ChildPermission filtered by the permission_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPermission[]|Collection find(?ConnectionInterface $con = null) Return ChildPermission objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildPermission> find(?ConnectionInterface $con = null) Return ChildPermission objects based on current ModelCriteria
 *
 * @method     ChildPermission[]|Collection findById(int|array<int> $permission_id) Return ChildPermission objects filtered by the permission_id column
 * @psalm-method Collection&\Traversable<ChildPermission> findById(int|array<int> $permission_id) Return ChildPermission objects filtered by the permission_id column
 * @method     ChildPermission[]|Collection findByAxysAccountId(int|array<int> $axys_account_id) Return ChildPermission objects filtered by the axys_account_id column
 * @psalm-method Collection&\Traversable<ChildPermission> findByAxysAccountId(int|array<int> $axys_account_id) Return ChildPermission objects filtered by the axys_account_id column
 * @method     ChildPermission[]|Collection findBySiteId(int|array<int> $site_id) Return ChildPermission objects filtered by the site_id column
 * @psalm-method Collection&\Traversable<ChildPermission> findBySiteId(int|array<int> $site_id) Return ChildPermission objects filtered by the site_id column
 * @method     ChildPermission[]|Collection findByRank(string|array<string> $permission_rank) Return ChildPermission objects filtered by the permission_rank column
 * @psalm-method Collection&\Traversable<ChildPermission> findByRank(string|array<string> $permission_rank) Return ChildPermission objects filtered by the permission_rank column
 * @method     ChildPermission[]|Collection findByLast(string|array<string> $permission_last) Return ChildPermission objects filtered by the permission_last column
 * @psalm-method Collection&\Traversable<ChildPermission> findByLast(string|array<string> $permission_last) Return ChildPermission objects filtered by the permission_last column
 * @method     ChildPermission[]|Collection findByDate(string|array<string> $permission_date) Return ChildPermission objects filtered by the permission_date column
 * @psalm-method Collection&\Traversable<ChildPermission> findByDate(string|array<string> $permission_date) Return ChildPermission objects filtered by the permission_date column
 *
 * @method     ChildPermission[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildPermission> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class PermissionQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\PermissionQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Permission', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPermissionQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPermissionQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildPermissionQuery) {
            return $criteria;
        }
        $query = new ChildPermissionQuery();
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
     * @return ChildPermission|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PermissionTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PermissionTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildPermission A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT permission_id, axys_account_id, site_id, permission_rank, permission_last, permission_date FROM permissions WHERE permission_id = :p0';
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
            /** @var ChildPermission $obj */
            $obj = new ChildPermission();
            $obj->hydrate($row);
            PermissionTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPermission|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(PermissionTableMap::COL_PERMISSION_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(PermissionTableMap::COL_PERMISSION_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the permission_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE permission_id = 1234
     * $query->filterById(array(12, 34)); // WHERE permission_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE permission_id > 12
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
                $this->addUsingAlias(PermissionTableMap::COL_PERMISSION_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PermissionTableMap::COL_PERMISSION_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PermissionTableMap::COL_PERMISSION_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the axys_account_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAxysAccountId(1234); // WHERE axys_account_id = 1234
     * $query->filterByAxysAccountId(array(12, 34)); // WHERE axys_account_id IN (12, 34)
     * $query->filterByAxysAccountId(array('min' => 12)); // WHERE axys_account_id > 12
     * </code>
     *
     * @param mixed $axysAccountId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAxysAccountId($axysAccountId = null, ?string $comparison = null)
    {
        if (is_array($axysAccountId)) {
            $useMinMax = false;
            if (isset($axysAccountId['min'])) {
                $this->addUsingAlias(PermissionTableMap::COL_AXYS_ACCOUNT_ID, $axysAccountId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($axysAccountId['max'])) {
                $this->addUsingAlias(PermissionTableMap::COL_AXYS_ACCOUNT_ID, $axysAccountId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PermissionTableMap::COL_AXYS_ACCOUNT_ID, $axysAccountId, $comparison);

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
                $this->addUsingAlias(PermissionTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(PermissionTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PermissionTableMap::COL_SITE_ID, $siteId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the permission_rank column
     *
     * Example usage:
     * <code>
     * $query->filterByRank('fooValue');   // WHERE permission_rank = 'fooValue'
     * $query->filterByRank('%fooValue%', Criteria::LIKE); // WHERE permission_rank LIKE '%fooValue%'
     * $query->filterByRank(['foo', 'bar']); // WHERE permission_rank IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $rank The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByRank($rank = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($rank)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PermissionTableMap::COL_PERMISSION_RANK, $rank, $comparison);

        return $this;
    }

    /**
     * Filter the query on the permission_last column
     *
     * Example usage:
     * <code>
     * $query->filterByLast('2011-03-14'); // WHERE permission_last = '2011-03-14'
     * $query->filterByLast('now'); // WHERE permission_last = '2011-03-14'
     * $query->filterByLast(array('max' => 'yesterday')); // WHERE permission_last > '2011-03-13'
     * </code>
     *
     * @param mixed $last The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByLast($last = null, ?string $comparison = null)
    {
        if (is_array($last)) {
            $useMinMax = false;
            if (isset($last['min'])) {
                $this->addUsingAlias(PermissionTableMap::COL_PERMISSION_LAST, $last['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($last['max'])) {
                $this->addUsingAlias(PermissionTableMap::COL_PERMISSION_LAST, $last['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PermissionTableMap::COL_PERMISSION_LAST, $last, $comparison);

        return $this;
    }

    /**
     * Filter the query on the permission_date column
     *
     * Example usage:
     * <code>
     * $query->filterByDate('2011-03-14'); // WHERE permission_date = '2011-03-14'
     * $query->filterByDate('now'); // WHERE permission_date = '2011-03-14'
     * $query->filterByDate(array('max' => 'yesterday')); // WHERE permission_date > '2011-03-13'
     * </code>
     *
     * @param mixed $date The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDate($date = null, ?string $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(PermissionTableMap::COL_PERMISSION_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(PermissionTableMap::COL_PERMISSION_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PermissionTableMap::COL_PERMISSION_DATE, $date, $comparison);

        return $this;
    }

    /**
     * Exclude object from result
     *
     * @param ChildPermission $permission Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($permission = null)
    {
        if ($permission) {
            $this->addUsingAlias(PermissionTableMap::COL_PERMISSION_ID, $permission->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the permissions table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PermissionTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PermissionTableMap::clearInstancePool();
            PermissionTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PermissionTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PermissionTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PermissionTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PermissionTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

}
