<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\AxysApp as ChildAxysApp;
use Model\AxysAppQuery as ChildAxysAppQuery;
use Model\Map\AxysAppTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'axys_apps' table.
 *
 *
 *
 * @method     ChildAxysAppQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildAxysAppQuery orderByClientId($order = Criteria::ASC) Order by the client_id column
 * @method     ChildAxysAppQuery orderByClientSecret($order = Criteria::ASC) Order by the client_secret column
 * @method     ChildAxysAppQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildAxysAppQuery orderByRedirectUri($order = Criteria::ASC) Order by the redirect_uri column
 * @method     ChildAxysAppQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildAxysAppQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildAxysAppQuery groupById() Group by the id column
 * @method     ChildAxysAppQuery groupByClientId() Group by the client_id column
 * @method     ChildAxysAppQuery groupByClientSecret() Group by the client_secret column
 * @method     ChildAxysAppQuery groupByName() Group by the name column
 * @method     ChildAxysAppQuery groupByRedirectUri() Group by the redirect_uri column
 * @method     ChildAxysAppQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildAxysAppQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildAxysAppQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildAxysAppQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildAxysAppQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildAxysAppQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildAxysAppQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildAxysAppQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildAxysApp|null findOne(?ConnectionInterface $con = null) Return the first ChildAxysApp matching the query
 * @method     ChildAxysApp findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildAxysApp matching the query, or a new ChildAxysApp object populated from the query conditions when no match is found
 *
 * @method     ChildAxysApp|null findOneById(int $id) Return the first ChildAxysApp filtered by the id column
 * @method     ChildAxysApp|null findOneByClientId(string $client_id) Return the first ChildAxysApp filtered by the client_id column
 * @method     ChildAxysApp|null findOneByClientSecret(string $client_secret) Return the first ChildAxysApp filtered by the client_secret column
 * @method     ChildAxysApp|null findOneByName(string $name) Return the first ChildAxysApp filtered by the name column
 * @method     ChildAxysApp|null findOneByRedirectUri(string $redirect_uri) Return the first ChildAxysApp filtered by the redirect_uri column
 * @method     ChildAxysApp|null findOneByCreatedAt(string $created_at) Return the first ChildAxysApp filtered by the created_at column
 * @method     ChildAxysApp|null findOneByUpdatedAt(string $updated_at) Return the first ChildAxysApp filtered by the updated_at column *

 * @method     ChildAxysApp requirePk($key, ?ConnectionInterface $con = null) Return the ChildAxysApp by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAxysApp requireOne(?ConnectionInterface $con = null) Return the first ChildAxysApp matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAxysApp requireOneById(int $id) Return the first ChildAxysApp filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAxysApp requireOneByClientId(string $client_id) Return the first ChildAxysApp filtered by the client_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAxysApp requireOneByClientSecret(string $client_secret) Return the first ChildAxysApp filtered by the client_secret column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAxysApp requireOneByName(string $name) Return the first ChildAxysApp filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAxysApp requireOneByRedirectUri(string $redirect_uri) Return the first ChildAxysApp filtered by the redirect_uri column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAxysApp requireOneByCreatedAt(string $created_at) Return the first ChildAxysApp filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAxysApp requireOneByUpdatedAt(string $updated_at) Return the first ChildAxysApp filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAxysApp[]|Collection find(?ConnectionInterface $con = null) Return ChildAxysApp objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildAxysApp> find(?ConnectionInterface $con = null) Return ChildAxysApp objects based on current ModelCriteria
 * @method     ChildAxysApp[]|Collection findById(int $id) Return ChildAxysApp objects filtered by the id column
 * @psalm-method Collection&\Traversable<ChildAxysApp> findById(int $id) Return ChildAxysApp objects filtered by the id column
 * @method     ChildAxysApp[]|Collection findByClientId(string $client_id) Return ChildAxysApp objects filtered by the client_id column
 * @psalm-method Collection&\Traversable<ChildAxysApp> findByClientId(string $client_id) Return ChildAxysApp objects filtered by the client_id column
 * @method     ChildAxysApp[]|Collection findByClientSecret(string $client_secret) Return ChildAxysApp objects filtered by the client_secret column
 * @psalm-method Collection&\Traversable<ChildAxysApp> findByClientSecret(string $client_secret) Return ChildAxysApp objects filtered by the client_secret column
 * @method     ChildAxysApp[]|Collection findByName(string $name) Return ChildAxysApp objects filtered by the name column
 * @psalm-method Collection&\Traversable<ChildAxysApp> findByName(string $name) Return ChildAxysApp objects filtered by the name column
 * @method     ChildAxysApp[]|Collection findByRedirectUri(string $redirect_uri) Return ChildAxysApp objects filtered by the redirect_uri column
 * @psalm-method Collection&\Traversable<ChildAxysApp> findByRedirectUri(string $redirect_uri) Return ChildAxysApp objects filtered by the redirect_uri column
 * @method     ChildAxysApp[]|Collection findByCreatedAt(string $created_at) Return ChildAxysApp objects filtered by the created_at column
 * @psalm-method Collection&\Traversable<ChildAxysApp> findByCreatedAt(string $created_at) Return ChildAxysApp objects filtered by the created_at column
 * @method     ChildAxysApp[]|Collection findByUpdatedAt(string $updated_at) Return ChildAxysApp objects filtered by the updated_at column
 * @psalm-method Collection&\Traversable<ChildAxysApp> findByUpdatedAt(string $updated_at) Return ChildAxysApp objects filtered by the updated_at column
 * @method     ChildAxysApp[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildAxysApp> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class AxysAppQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\AxysAppQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\AxysApp', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildAxysAppQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildAxysAppQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildAxysAppQuery) {
            return $criteria;
        }
        $query = new ChildAxysAppQuery();
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
     * @return ChildAxysApp|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(AxysAppTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = AxysAppTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildAxysApp A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, client_id, client_secret, name, redirect_uri, created_at, updated_at FROM axys_apps WHERE id = :p0';
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
            /** @var ChildAxysApp $obj */
            $obj = new ChildAxysApp();
            $obj->hydrate($row);
            AxysAppTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildAxysApp|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(AxysAppTableMap::COL_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(AxysAppTableMap::COL_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
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
                $this->addUsingAlias(AxysAppTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(AxysAppTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AxysAppTableMap::COL_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the client_id column
     *
     * Example usage:
     * <code>
     * $query->filterByClientId('fooValue');   // WHERE client_id = 'fooValue'
     * $query->filterByClientId('%fooValue%', Criteria::LIKE); // WHERE client_id LIKE '%fooValue%'
     * $query->filterByClientId(['foo', 'bar']); // WHERE client_id IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $clientId The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByClientId($clientId = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($clientId)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AxysAppTableMap::COL_CLIENT_ID, $clientId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the client_secret column
     *
     * Example usage:
     * <code>
     * $query->filterByClientSecret('fooValue');   // WHERE client_secret = 'fooValue'
     * $query->filterByClientSecret('%fooValue%', Criteria::LIKE); // WHERE client_secret LIKE '%fooValue%'
     * $query->filterByClientSecret(['foo', 'bar']); // WHERE client_secret IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $clientSecret The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByClientSecret($clientSecret = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($clientSecret)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AxysAppTableMap::COL_CLIENT_SECRET, $clientSecret, $comparison);

        return $this;
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE name LIKE '%fooValue%'
     * $query->filterByName(['foo', 'bar']); // WHERE name IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $name The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByName($name = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AxysAppTableMap::COL_NAME, $name, $comparison);

        return $this;
    }

    /**
     * Filter the query on the redirect_uri column
     *
     * Example usage:
     * <code>
     * $query->filterByRedirectUri('fooValue');   // WHERE redirect_uri = 'fooValue'
     * $query->filterByRedirectUri('%fooValue%', Criteria::LIKE); // WHERE redirect_uri LIKE '%fooValue%'
     * $query->filterByRedirectUri(['foo', 'bar']); // WHERE redirect_uri IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $redirectUri The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByRedirectUri($redirectUri = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($redirectUri)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AxysAppTableMap::COL_REDIRECT_URI, $redirectUri, $comparison);

        return $this;
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
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
                $this->addUsingAlias(AxysAppTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(AxysAppTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AxysAppTableMap::COL_CREATED_AT, $createdAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
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
                $this->addUsingAlias(AxysAppTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(AxysAppTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AxysAppTableMap::COL_UPDATED_AT, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Exclude object from result
     *
     * @param ChildAxysApp $axysApp Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($axysApp = null)
    {
        if ($axysApp) {
            $this->addUsingAlias(AxysAppTableMap::COL_ID, $axysApp->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the axys_apps table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AxysAppTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            AxysAppTableMap::clearInstancePool();
            AxysAppTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(AxysAppTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(AxysAppTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            AxysAppTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            AxysAppTableMap::clearRelatedInstancePool();

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
        $this->addUsingAlias(AxysAppTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(AxysAppTableMap::COL_UPDATED_AT);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(AxysAppTableMap::COL_UPDATED_AT);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(AxysAppTableMap::COL_CREATED_AT);

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
        $this->addUsingAlias(AxysAppTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(AxysAppTableMap::COL_CREATED_AT);

        return $this;
    }

}
