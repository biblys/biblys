<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Redirection as ChildRedirection;
use Model\RedirectionQuery as ChildRedirectionQuery;
use Model\Map\RedirectionTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'redirections' table.
 *
 *
 *
 * @method     ChildRedirectionQuery orderById($order = Criteria::ASC) Order by the redirection_id column
 * @method     ChildRedirectionQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildRedirectionQuery orderByOld($order = Criteria::ASC) Order by the redirection_old column
 * @method     ChildRedirectionQuery orderByNew($order = Criteria::ASC) Order by the redirection_new column
 * @method     ChildRedirectionQuery orderByHits($order = Criteria::ASC) Order by the redirection_hits column
 * @method     ChildRedirectionQuery orderByDate($order = Criteria::ASC) Order by the redirection_date column
 * @method     ChildRedirectionQuery orderByCreatedAt($order = Criteria::ASC) Order by the redirection_created column
 * @method     ChildRedirectionQuery orderByUpdatedAt($order = Criteria::ASC) Order by the redirection_updated column
 *
 * @method     ChildRedirectionQuery groupById() Group by the redirection_id column
 * @method     ChildRedirectionQuery groupBySiteId() Group by the site_id column
 * @method     ChildRedirectionQuery groupByOld() Group by the redirection_old column
 * @method     ChildRedirectionQuery groupByNew() Group by the redirection_new column
 * @method     ChildRedirectionQuery groupByHits() Group by the redirection_hits column
 * @method     ChildRedirectionQuery groupByDate() Group by the redirection_date column
 * @method     ChildRedirectionQuery groupByCreatedAt() Group by the redirection_created column
 * @method     ChildRedirectionQuery groupByUpdatedAt() Group by the redirection_updated column
 *
 * @method     ChildRedirectionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildRedirectionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildRedirectionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildRedirectionQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildRedirectionQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildRedirectionQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildRedirection|null findOne(?ConnectionInterface $con = null) Return the first ChildRedirection matching the query
 * @method     ChildRedirection findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildRedirection matching the query, or a new ChildRedirection object populated from the query conditions when no match is found
 *
 * @method     ChildRedirection|null findOneById(int $redirection_id) Return the first ChildRedirection filtered by the redirection_id column
 * @method     ChildRedirection|null findOneBySiteId(int $site_id) Return the first ChildRedirection filtered by the site_id column
 * @method     ChildRedirection|null findOneByOld(string $redirection_old) Return the first ChildRedirection filtered by the redirection_old column
 * @method     ChildRedirection|null findOneByNew(string $redirection_new) Return the first ChildRedirection filtered by the redirection_new column
 * @method     ChildRedirection|null findOneByHits(int $redirection_hits) Return the first ChildRedirection filtered by the redirection_hits column
 * @method     ChildRedirection|null findOneByDate(string $redirection_date) Return the first ChildRedirection filtered by the redirection_date column
 * @method     ChildRedirection|null findOneByCreatedAt(string $redirection_created) Return the first ChildRedirection filtered by the redirection_created column
 * @method     ChildRedirection|null findOneByUpdatedAt(string $redirection_updated) Return the first ChildRedirection filtered by the redirection_updated column *

 * @method     ChildRedirection requirePk($key, ?ConnectionInterface $con = null) Return the ChildRedirection by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRedirection requireOne(?ConnectionInterface $con = null) Return the first ChildRedirection matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildRedirection requireOneById(int $redirection_id) Return the first ChildRedirection filtered by the redirection_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRedirection requireOneBySiteId(int $site_id) Return the first ChildRedirection filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRedirection requireOneByOld(string $redirection_old) Return the first ChildRedirection filtered by the redirection_old column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRedirection requireOneByNew(string $redirection_new) Return the first ChildRedirection filtered by the redirection_new column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRedirection requireOneByHits(int $redirection_hits) Return the first ChildRedirection filtered by the redirection_hits column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRedirection requireOneByDate(string $redirection_date) Return the first ChildRedirection filtered by the redirection_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRedirection requireOneByCreatedAt(string $redirection_created) Return the first ChildRedirection filtered by the redirection_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRedirection requireOneByUpdatedAt(string $redirection_updated) Return the first ChildRedirection filtered by the redirection_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildRedirection[]|Collection find(?ConnectionInterface $con = null) Return ChildRedirection objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildRedirection> find(?ConnectionInterface $con = null) Return ChildRedirection objects based on current ModelCriteria
 * @method     ChildRedirection[]|Collection findById(int $redirection_id) Return ChildRedirection objects filtered by the redirection_id column
 * @psalm-method Collection&\Traversable<ChildRedirection> findById(int $redirection_id) Return ChildRedirection objects filtered by the redirection_id column
 * @method     ChildRedirection[]|Collection findBySiteId(int $site_id) Return ChildRedirection objects filtered by the site_id column
 * @psalm-method Collection&\Traversable<ChildRedirection> findBySiteId(int $site_id) Return ChildRedirection objects filtered by the site_id column
 * @method     ChildRedirection[]|Collection findByOld(string $redirection_old) Return ChildRedirection objects filtered by the redirection_old column
 * @psalm-method Collection&\Traversable<ChildRedirection> findByOld(string $redirection_old) Return ChildRedirection objects filtered by the redirection_old column
 * @method     ChildRedirection[]|Collection findByNew(string $redirection_new) Return ChildRedirection objects filtered by the redirection_new column
 * @psalm-method Collection&\Traversable<ChildRedirection> findByNew(string $redirection_new) Return ChildRedirection objects filtered by the redirection_new column
 * @method     ChildRedirection[]|Collection findByHits(int $redirection_hits) Return ChildRedirection objects filtered by the redirection_hits column
 * @psalm-method Collection&\Traversable<ChildRedirection> findByHits(int $redirection_hits) Return ChildRedirection objects filtered by the redirection_hits column
 * @method     ChildRedirection[]|Collection findByDate(string $redirection_date) Return ChildRedirection objects filtered by the redirection_date column
 * @psalm-method Collection&\Traversable<ChildRedirection> findByDate(string $redirection_date) Return ChildRedirection objects filtered by the redirection_date column
 * @method     ChildRedirection[]|Collection findByCreatedAt(string $redirection_created) Return ChildRedirection objects filtered by the redirection_created column
 * @psalm-method Collection&\Traversable<ChildRedirection> findByCreatedAt(string $redirection_created) Return ChildRedirection objects filtered by the redirection_created column
 * @method     ChildRedirection[]|Collection findByUpdatedAt(string $redirection_updated) Return ChildRedirection objects filtered by the redirection_updated column
 * @psalm-method Collection&\Traversable<ChildRedirection> findByUpdatedAt(string $redirection_updated) Return ChildRedirection objects filtered by the redirection_updated column
 * @method     ChildRedirection[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildRedirection> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class RedirectionQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\RedirectionQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Redirection', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildRedirectionQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildRedirectionQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildRedirectionQuery) {
            return $criteria;
        }
        $query = new ChildRedirectionQuery();
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
     * @return ChildRedirection|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(RedirectionTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = RedirectionTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildRedirection A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT redirection_id, site_id, redirection_old, redirection_new, redirection_hits, redirection_date, redirection_created, redirection_updated FROM redirections WHERE redirection_id = :p0';
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
            /** @var ChildRedirection $obj */
            $obj = new ChildRedirection();
            $obj->hydrate($row);
            RedirectionTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildRedirection|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(RedirectionTableMap::COL_REDIRECTION_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(RedirectionTableMap::COL_REDIRECTION_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the redirection_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE redirection_id = 1234
     * $query->filterById(array(12, 34)); // WHERE redirection_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE redirection_id > 12
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
                $this->addUsingAlias(RedirectionTableMap::COL_REDIRECTION_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(RedirectionTableMap::COL_REDIRECTION_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(RedirectionTableMap::COL_REDIRECTION_ID, $id, $comparison);

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
                $this->addUsingAlias(RedirectionTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(RedirectionTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(RedirectionTableMap::COL_SITE_ID, $siteId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the redirection_old column
     *
     * Example usage:
     * <code>
     * $query->filterByOld('fooValue');   // WHERE redirection_old = 'fooValue'
     * $query->filterByOld('%fooValue%', Criteria::LIKE); // WHERE redirection_old LIKE '%fooValue%'
     * $query->filterByOld(['foo', 'bar']); // WHERE redirection_old IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $old The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByOld($old = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($old)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(RedirectionTableMap::COL_REDIRECTION_OLD, $old, $comparison);

        return $this;
    }

    /**
     * Filter the query on the redirection_new column
     *
     * Example usage:
     * <code>
     * $query->filterByNew('fooValue');   // WHERE redirection_new = 'fooValue'
     * $query->filterByNew('%fooValue%', Criteria::LIKE); // WHERE redirection_new LIKE '%fooValue%'
     * $query->filterByNew(['foo', 'bar']); // WHERE redirection_new IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $new The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByNew($new = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($new)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(RedirectionTableMap::COL_REDIRECTION_NEW, $new, $comparison);

        return $this;
    }

    /**
     * Filter the query on the redirection_hits column
     *
     * Example usage:
     * <code>
     * $query->filterByHits(1234); // WHERE redirection_hits = 1234
     * $query->filterByHits(array(12, 34)); // WHERE redirection_hits IN (12, 34)
     * $query->filterByHits(array('min' => 12)); // WHERE redirection_hits > 12
     * </code>
     *
     * @param mixed $hits The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByHits($hits = null, ?string $comparison = null)
    {
        if (is_array($hits)) {
            $useMinMax = false;
            if (isset($hits['min'])) {
                $this->addUsingAlias(RedirectionTableMap::COL_REDIRECTION_HITS, $hits['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($hits['max'])) {
                $this->addUsingAlias(RedirectionTableMap::COL_REDIRECTION_HITS, $hits['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(RedirectionTableMap::COL_REDIRECTION_HITS, $hits, $comparison);

        return $this;
    }

    /**
     * Filter the query on the redirection_date column
     *
     * Example usage:
     * <code>
     * $query->filterByDate('2011-03-14'); // WHERE redirection_date = '2011-03-14'
     * $query->filterByDate('now'); // WHERE redirection_date = '2011-03-14'
     * $query->filterByDate(array('max' => 'yesterday')); // WHERE redirection_date > '2011-03-13'
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
                $this->addUsingAlias(RedirectionTableMap::COL_REDIRECTION_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(RedirectionTableMap::COL_REDIRECTION_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(RedirectionTableMap::COL_REDIRECTION_DATE, $date, $comparison);

        return $this;
    }

    /**
     * Filter the query on the redirection_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE redirection_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE redirection_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE redirection_created > '2011-03-13'
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
                $this->addUsingAlias(RedirectionTableMap::COL_REDIRECTION_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(RedirectionTableMap::COL_REDIRECTION_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(RedirectionTableMap::COL_REDIRECTION_CREATED, $createdAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the redirection_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE redirection_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE redirection_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE redirection_updated > '2011-03-13'
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
                $this->addUsingAlias(RedirectionTableMap::COL_REDIRECTION_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(RedirectionTableMap::COL_REDIRECTION_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(RedirectionTableMap::COL_REDIRECTION_UPDATED, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Exclude object from result
     *
     * @param ChildRedirection $redirection Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($redirection = null)
    {
        if ($redirection) {
            $this->addUsingAlias(RedirectionTableMap::COL_REDIRECTION_ID, $redirection->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the redirections table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(RedirectionTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            RedirectionTableMap::clearInstancePool();
            RedirectionTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(RedirectionTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(RedirectionTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            RedirectionTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            RedirectionTableMap::clearRelatedInstancePool();

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
        $this->addUsingAlias(RedirectionTableMap::COL_REDIRECTION_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(RedirectionTableMap::COL_REDIRECTION_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(RedirectionTableMap::COL_REDIRECTION_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(RedirectionTableMap::COL_REDIRECTION_CREATED);

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
        $this->addUsingAlias(RedirectionTableMap::COL_REDIRECTION_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(RedirectionTableMap::COL_REDIRECTION_CREATED);

        return $this;
    }

}
