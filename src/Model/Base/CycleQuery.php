<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Cycle as ChildCycle;
use Model\CycleQuery as ChildCycleQuery;
use Model\Map\CycleTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'cycles' table.
 *
 *
 *
 * @method     ChildCycleQuery orderById($order = Criteria::ASC) Order by the cycle_id column
 * @method     ChildCycleQuery orderByName($order = Criteria::ASC) Order by the cycle_name column
 * @method     ChildCycleQuery orderByUrl($order = Criteria::ASC) Order by the cycle_url column
 * @method     ChildCycleQuery orderByDesc($order = Criteria::ASC) Order by the cycle_desc column
 * @method     ChildCycleQuery orderByHits($order = Criteria::ASC) Order by the cycle_hits column
 * @method     ChildCycleQuery orderByNoosfereId($order = Criteria::ASC) Order by the cycle_noosfere_id column
 * @method     ChildCycleQuery orderByInsert($order = Criteria::ASC) Order by the cycle_insert column
 * @method     ChildCycleQuery orderByUpdate($order = Criteria::ASC) Order by the cycle_update column
 * @method     ChildCycleQuery orderByCreatedAt($order = Criteria::ASC) Order by the cycle_created column
 * @method     ChildCycleQuery orderByUpdatedAt($order = Criteria::ASC) Order by the cycle_updated column
 * @method     ChildCycleQuery orderByDeletedAt($order = Criteria::ASC) Order by the cycle_deleted column
 *
 * @method     ChildCycleQuery groupById() Group by the cycle_id column
 * @method     ChildCycleQuery groupByName() Group by the cycle_name column
 * @method     ChildCycleQuery groupByUrl() Group by the cycle_url column
 * @method     ChildCycleQuery groupByDesc() Group by the cycle_desc column
 * @method     ChildCycleQuery groupByHits() Group by the cycle_hits column
 * @method     ChildCycleQuery groupByNoosfereId() Group by the cycle_noosfere_id column
 * @method     ChildCycleQuery groupByInsert() Group by the cycle_insert column
 * @method     ChildCycleQuery groupByUpdate() Group by the cycle_update column
 * @method     ChildCycleQuery groupByCreatedAt() Group by the cycle_created column
 * @method     ChildCycleQuery groupByUpdatedAt() Group by the cycle_updated column
 * @method     ChildCycleQuery groupByDeletedAt() Group by the cycle_deleted column
 *
 * @method     ChildCycleQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCycleQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCycleQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCycleQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildCycleQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildCycleQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildCycle|null findOne(ConnectionInterface $con = null) Return the first ChildCycle matching the query
 * @method     ChildCycle findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCycle matching the query, or a new ChildCycle object populated from the query conditions when no match is found
 *
 * @method     ChildCycle|null findOneById(int $cycle_id) Return the first ChildCycle filtered by the cycle_id column
 * @method     ChildCycle|null findOneByName(string $cycle_name) Return the first ChildCycle filtered by the cycle_name column
 * @method     ChildCycle|null findOneByUrl(string $cycle_url) Return the first ChildCycle filtered by the cycle_url column
 * @method     ChildCycle|null findOneByDesc(string $cycle_desc) Return the first ChildCycle filtered by the cycle_desc column
 * @method     ChildCycle|null findOneByHits(int $cycle_hits) Return the first ChildCycle filtered by the cycle_hits column
 * @method     ChildCycle|null findOneByNoosfereId(int $cycle_noosfere_id) Return the first ChildCycle filtered by the cycle_noosfere_id column
 * @method     ChildCycle|null findOneByInsert(string $cycle_insert) Return the first ChildCycle filtered by the cycle_insert column
 * @method     ChildCycle|null findOneByUpdate(string $cycle_update) Return the first ChildCycle filtered by the cycle_update column
 * @method     ChildCycle|null findOneByCreatedAt(string $cycle_created) Return the first ChildCycle filtered by the cycle_created column
 * @method     ChildCycle|null findOneByUpdatedAt(string $cycle_updated) Return the first ChildCycle filtered by the cycle_updated column
 * @method     ChildCycle|null findOneByDeletedAt(string $cycle_deleted) Return the first ChildCycle filtered by the cycle_deleted column *

 * @method     ChildCycle requirePk($key, ConnectionInterface $con = null) Return the ChildCycle by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCycle requireOne(ConnectionInterface $con = null) Return the first ChildCycle matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCycle requireOneById(int $cycle_id) Return the first ChildCycle filtered by the cycle_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCycle requireOneByName(string $cycle_name) Return the first ChildCycle filtered by the cycle_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCycle requireOneByUrl(string $cycle_url) Return the first ChildCycle filtered by the cycle_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCycle requireOneByDesc(string $cycle_desc) Return the first ChildCycle filtered by the cycle_desc column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCycle requireOneByHits(int $cycle_hits) Return the first ChildCycle filtered by the cycle_hits column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCycle requireOneByNoosfereId(int $cycle_noosfere_id) Return the first ChildCycle filtered by the cycle_noosfere_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCycle requireOneByInsert(string $cycle_insert) Return the first ChildCycle filtered by the cycle_insert column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCycle requireOneByUpdate(string $cycle_update) Return the first ChildCycle filtered by the cycle_update column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCycle requireOneByCreatedAt(string $cycle_created) Return the first ChildCycle filtered by the cycle_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCycle requireOneByUpdatedAt(string $cycle_updated) Return the first ChildCycle filtered by the cycle_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCycle requireOneByDeletedAt(string $cycle_deleted) Return the first ChildCycle filtered by the cycle_deleted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCycle[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildCycle objects based on current ModelCriteria
 * @method     ChildCycle[]|ObjectCollection findById(int $cycle_id) Return ChildCycle objects filtered by the cycle_id column
 * @method     ChildCycle[]|ObjectCollection findByName(string $cycle_name) Return ChildCycle objects filtered by the cycle_name column
 * @method     ChildCycle[]|ObjectCollection findByUrl(string $cycle_url) Return ChildCycle objects filtered by the cycle_url column
 * @method     ChildCycle[]|ObjectCollection findByDesc(string $cycle_desc) Return ChildCycle objects filtered by the cycle_desc column
 * @method     ChildCycle[]|ObjectCollection findByHits(int $cycle_hits) Return ChildCycle objects filtered by the cycle_hits column
 * @method     ChildCycle[]|ObjectCollection findByNoosfereId(int $cycle_noosfere_id) Return ChildCycle objects filtered by the cycle_noosfere_id column
 * @method     ChildCycle[]|ObjectCollection findByInsert(string $cycle_insert) Return ChildCycle objects filtered by the cycle_insert column
 * @method     ChildCycle[]|ObjectCollection findByUpdate(string $cycle_update) Return ChildCycle objects filtered by the cycle_update column
 * @method     ChildCycle[]|ObjectCollection findByCreatedAt(string $cycle_created) Return ChildCycle objects filtered by the cycle_created column
 * @method     ChildCycle[]|ObjectCollection findByUpdatedAt(string $cycle_updated) Return ChildCycle objects filtered by the cycle_updated column
 * @method     ChildCycle[]|ObjectCollection findByDeletedAt(string $cycle_deleted) Return ChildCycle objects filtered by the cycle_deleted column
 * @method     ChildCycle[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class CycleQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\CycleQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Cycle', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCycleQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCycleQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildCycleQuery) {
            return $criteria;
        }
        $query = new ChildCycleQuery();
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
     * @return ChildCycle|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CycleTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = CycleTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCycle A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT cycle_id, cycle_name, cycle_url, cycle_desc, cycle_hits, cycle_noosfere_id, cycle_insert, cycle_update, cycle_created, cycle_updated, cycle_deleted FROM cycles WHERE cycle_id = :p0';
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
            /** @var ChildCycle $obj */
            $obj = new ChildCycle();
            $obj->hydrate($row);
            CycleTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildCycle|array|mixed the result, formatted by the current formatter
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
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
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
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildCycleQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CycleTableMap::COL_CYCLE_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildCycleQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CycleTableMap::COL_CYCLE_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the cycle_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE cycle_id = 1234
     * $query->filterById(array(12, 34)); // WHERE cycle_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE cycle_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCycleQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CycleTableMap::COL_CYCLE_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CycleTableMap::COL_CYCLE_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CycleTableMap::COL_CYCLE_ID, $id, $comparison);
    }

    /**
     * Filter the query on the cycle_name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE cycle_name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE cycle_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCycleQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CycleTableMap::COL_CYCLE_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the cycle_url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE cycle_url = 'fooValue'
     * $query->filterByUrl('%fooValue%', Criteria::LIKE); // WHERE cycle_url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $url The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCycleQuery The current query, for fluid interface
     */
    public function filterByUrl($url = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($url)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CycleTableMap::COL_CYCLE_URL, $url, $comparison);
    }

    /**
     * Filter the query on the cycle_desc column
     *
     * Example usage:
     * <code>
     * $query->filterByDesc('fooValue');   // WHERE cycle_desc = 'fooValue'
     * $query->filterByDesc('%fooValue%', Criteria::LIKE); // WHERE cycle_desc LIKE '%fooValue%'
     * </code>
     *
     * @param     string $desc The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCycleQuery The current query, for fluid interface
     */
    public function filterByDesc($desc = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($desc)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CycleTableMap::COL_CYCLE_DESC, $desc, $comparison);
    }

    /**
     * Filter the query on the cycle_hits column
     *
     * Example usage:
     * <code>
     * $query->filterByHits(1234); // WHERE cycle_hits = 1234
     * $query->filterByHits(array(12, 34)); // WHERE cycle_hits IN (12, 34)
     * $query->filterByHits(array('min' => 12)); // WHERE cycle_hits > 12
     * </code>
     *
     * @param     mixed $hits The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCycleQuery The current query, for fluid interface
     */
    public function filterByHits($hits = null, $comparison = null)
    {
        if (is_array($hits)) {
            $useMinMax = false;
            if (isset($hits['min'])) {
                $this->addUsingAlias(CycleTableMap::COL_CYCLE_HITS, $hits['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($hits['max'])) {
                $this->addUsingAlias(CycleTableMap::COL_CYCLE_HITS, $hits['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CycleTableMap::COL_CYCLE_HITS, $hits, $comparison);
    }

    /**
     * Filter the query on the cycle_noosfere_id column
     *
     * Example usage:
     * <code>
     * $query->filterByNoosfereId(1234); // WHERE cycle_noosfere_id = 1234
     * $query->filterByNoosfereId(array(12, 34)); // WHERE cycle_noosfere_id IN (12, 34)
     * $query->filterByNoosfereId(array('min' => 12)); // WHERE cycle_noosfere_id > 12
     * </code>
     *
     * @param     mixed $noosfereId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCycleQuery The current query, for fluid interface
     */
    public function filterByNoosfereId($noosfereId = null, $comparison = null)
    {
        if (is_array($noosfereId)) {
            $useMinMax = false;
            if (isset($noosfereId['min'])) {
                $this->addUsingAlias(CycleTableMap::COL_CYCLE_NOOSFERE_ID, $noosfereId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($noosfereId['max'])) {
                $this->addUsingAlias(CycleTableMap::COL_CYCLE_NOOSFERE_ID, $noosfereId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CycleTableMap::COL_CYCLE_NOOSFERE_ID, $noosfereId, $comparison);
    }

    /**
     * Filter the query on the cycle_insert column
     *
     * Example usage:
     * <code>
     * $query->filterByInsert('2011-03-14'); // WHERE cycle_insert = '2011-03-14'
     * $query->filterByInsert('now'); // WHERE cycle_insert = '2011-03-14'
     * $query->filterByInsert(array('max' => 'yesterday')); // WHERE cycle_insert > '2011-03-13'
     * </code>
     *
     * @param     mixed $insert The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCycleQuery The current query, for fluid interface
     */
    public function filterByInsert($insert = null, $comparison = null)
    {
        if (is_array($insert)) {
            $useMinMax = false;
            if (isset($insert['min'])) {
                $this->addUsingAlias(CycleTableMap::COL_CYCLE_INSERT, $insert['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($insert['max'])) {
                $this->addUsingAlias(CycleTableMap::COL_CYCLE_INSERT, $insert['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CycleTableMap::COL_CYCLE_INSERT, $insert, $comparison);
    }

    /**
     * Filter the query on the cycle_update column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdate('2011-03-14'); // WHERE cycle_update = '2011-03-14'
     * $query->filterByUpdate('now'); // WHERE cycle_update = '2011-03-14'
     * $query->filterByUpdate(array('max' => 'yesterday')); // WHERE cycle_update > '2011-03-13'
     * </code>
     *
     * @param     mixed $update The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCycleQuery The current query, for fluid interface
     */
    public function filterByUpdate($update = null, $comparison = null)
    {
        if (is_array($update)) {
            $useMinMax = false;
            if (isset($update['min'])) {
                $this->addUsingAlias(CycleTableMap::COL_CYCLE_UPDATE, $update['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($update['max'])) {
                $this->addUsingAlias(CycleTableMap::COL_CYCLE_UPDATE, $update['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CycleTableMap::COL_CYCLE_UPDATE, $update, $comparison);
    }

    /**
     * Filter the query on the cycle_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE cycle_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE cycle_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE cycle_created > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCycleQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(CycleTableMap::COL_CYCLE_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(CycleTableMap::COL_CYCLE_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CycleTableMap::COL_CYCLE_CREATED, $createdAt, $comparison);
    }

    /**
     * Filter the query on the cycle_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE cycle_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE cycle_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE cycle_updated > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCycleQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(CycleTableMap::COL_CYCLE_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(CycleTableMap::COL_CYCLE_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CycleTableMap::COL_CYCLE_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the cycle_deleted column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletedAt('2011-03-14'); // WHERE cycle_deleted = '2011-03-14'
     * $query->filterByDeletedAt('now'); // WHERE cycle_deleted = '2011-03-14'
     * $query->filterByDeletedAt(array('max' => 'yesterday')); // WHERE cycle_deleted > '2011-03-13'
     * </code>
     *
     * @param     mixed $deletedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCycleQuery The current query, for fluid interface
     */
    public function filterByDeletedAt($deletedAt = null, $comparison = null)
    {
        if (is_array($deletedAt)) {
            $useMinMax = false;
            if (isset($deletedAt['min'])) {
                $this->addUsingAlias(CycleTableMap::COL_CYCLE_DELETED, $deletedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletedAt['max'])) {
                $this->addUsingAlias(CycleTableMap::COL_CYCLE_DELETED, $deletedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CycleTableMap::COL_CYCLE_DELETED, $deletedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildCycle $cycle Object to remove from the list of results
     *
     * @return $this|ChildCycleQuery The current query, for fluid interface
     */
    public function prune($cycle = null)
    {
        if ($cycle) {
            $this->addUsingAlias(CycleTableMap::COL_CYCLE_ID, $cycle->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the cycles table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CycleTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CycleTableMap::clearInstancePool();
            CycleTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CycleTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CycleTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            CycleTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CycleTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // CycleQuery
