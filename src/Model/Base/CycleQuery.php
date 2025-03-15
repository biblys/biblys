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
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `cycles` table.
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
 *
 * @method     ChildCycleQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCycleQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCycleQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCycleQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildCycleQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildCycleQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildCycleQuery leftJoinArticle($relationAlias = null) Adds a LEFT JOIN clause to the query using the Article relation
 * @method     ChildCycleQuery rightJoinArticle($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Article relation
 * @method     ChildCycleQuery innerJoinArticle($relationAlias = null) Adds a INNER JOIN clause to the query using the Article relation
 *
 * @method     ChildCycleQuery joinWithArticle($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Article relation
 *
 * @method     ChildCycleQuery leftJoinWithArticle() Adds a LEFT JOIN clause and with to the query using the Article relation
 * @method     ChildCycleQuery rightJoinWithArticle() Adds a RIGHT JOIN clause and with to the query using the Article relation
 * @method     ChildCycleQuery innerJoinWithArticle() Adds a INNER JOIN clause and with to the query using the Article relation
 *
 * @method     \Model\ArticleQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildCycle|null findOne(?ConnectionInterface $con = null) Return the first ChildCycle matching the query
 * @method     ChildCycle findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildCycle matching the query, or a new ChildCycle object populated from the query conditions when no match is found
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
 *
 * @method     ChildCycle requirePk($key, ?ConnectionInterface $con = null) Return the ChildCycle by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCycle requireOne(?ConnectionInterface $con = null) Return the first ChildCycle matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
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
 *
 * @method     ChildCycle[]|Collection find(?ConnectionInterface $con = null) Return ChildCycle objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildCycle> find(?ConnectionInterface $con = null) Return ChildCycle objects based on current ModelCriteria
 *
 * @method     ChildCycle[]|Collection findById(int|array<int> $cycle_id) Return ChildCycle objects filtered by the cycle_id column
 * @psalm-method Collection&\Traversable<ChildCycle> findById(int|array<int> $cycle_id) Return ChildCycle objects filtered by the cycle_id column
 * @method     ChildCycle[]|Collection findByName(string|array<string> $cycle_name) Return ChildCycle objects filtered by the cycle_name column
 * @psalm-method Collection&\Traversable<ChildCycle> findByName(string|array<string> $cycle_name) Return ChildCycle objects filtered by the cycle_name column
 * @method     ChildCycle[]|Collection findByUrl(string|array<string> $cycle_url) Return ChildCycle objects filtered by the cycle_url column
 * @psalm-method Collection&\Traversable<ChildCycle> findByUrl(string|array<string> $cycle_url) Return ChildCycle objects filtered by the cycle_url column
 * @method     ChildCycle[]|Collection findByDesc(string|array<string> $cycle_desc) Return ChildCycle objects filtered by the cycle_desc column
 * @psalm-method Collection&\Traversable<ChildCycle> findByDesc(string|array<string> $cycle_desc) Return ChildCycle objects filtered by the cycle_desc column
 * @method     ChildCycle[]|Collection findByHits(int|array<int> $cycle_hits) Return ChildCycle objects filtered by the cycle_hits column
 * @psalm-method Collection&\Traversable<ChildCycle> findByHits(int|array<int> $cycle_hits) Return ChildCycle objects filtered by the cycle_hits column
 * @method     ChildCycle[]|Collection findByNoosfereId(int|array<int> $cycle_noosfere_id) Return ChildCycle objects filtered by the cycle_noosfere_id column
 * @psalm-method Collection&\Traversable<ChildCycle> findByNoosfereId(int|array<int> $cycle_noosfere_id) Return ChildCycle objects filtered by the cycle_noosfere_id column
 * @method     ChildCycle[]|Collection findByInsert(string|array<string> $cycle_insert) Return ChildCycle objects filtered by the cycle_insert column
 * @psalm-method Collection&\Traversable<ChildCycle> findByInsert(string|array<string> $cycle_insert) Return ChildCycle objects filtered by the cycle_insert column
 * @method     ChildCycle[]|Collection findByUpdate(string|array<string> $cycle_update) Return ChildCycle objects filtered by the cycle_update column
 * @psalm-method Collection&\Traversable<ChildCycle> findByUpdate(string|array<string> $cycle_update) Return ChildCycle objects filtered by the cycle_update column
 * @method     ChildCycle[]|Collection findByCreatedAt(string|array<string> $cycle_created) Return ChildCycle objects filtered by the cycle_created column
 * @psalm-method Collection&\Traversable<ChildCycle> findByCreatedAt(string|array<string> $cycle_created) Return ChildCycle objects filtered by the cycle_created column
 * @method     ChildCycle[]|Collection findByUpdatedAt(string|array<string> $cycle_updated) Return ChildCycle objects filtered by the cycle_updated column
 * @psalm-method Collection&\Traversable<ChildCycle> findByUpdatedAt(string|array<string> $cycle_updated) Return ChildCycle objects filtered by the cycle_updated column
 *
 * @method     ChildCycle[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildCycle> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class CycleQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\CycleQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Cycle', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCycleQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCycleQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
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
    public function findPk($key, ?ConnectionInterface $con = null)
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
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCycle A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT cycle_id, cycle_name, cycle_url, cycle_desc, cycle_hits, cycle_noosfere_id, cycle_insert, cycle_update, cycle_created, cycle_updated FROM cycles WHERE cycle_id = :p0';
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
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
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

        $this->addUsingAlias(CycleTableMap::COL_CYCLE_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(CycleTableMap::COL_CYCLE_ID, $keys, Criteria::IN);

        return $this;
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

        $this->addUsingAlias(CycleTableMap::COL_CYCLE_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the cycle_name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE cycle_name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE cycle_name LIKE '%fooValue%'
     * $query->filterByName(['foo', 'bar']); // WHERE cycle_name IN ('foo', 'bar')
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

        $this->addUsingAlias(CycleTableMap::COL_CYCLE_NAME, $name, $comparison);

        return $this;
    }

    /**
     * Filter the query on the cycle_url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE cycle_url = 'fooValue'
     * $query->filterByUrl('%fooValue%', Criteria::LIKE); // WHERE cycle_url LIKE '%fooValue%'
     * $query->filterByUrl(['foo', 'bar']); // WHERE cycle_url IN ('foo', 'bar')
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

        $this->addUsingAlias(CycleTableMap::COL_CYCLE_URL, $url, $comparison);

        return $this;
    }

    /**
     * Filter the query on the cycle_desc column
     *
     * Example usage:
     * <code>
     * $query->filterByDesc('fooValue');   // WHERE cycle_desc = 'fooValue'
     * $query->filterByDesc('%fooValue%', Criteria::LIKE); // WHERE cycle_desc LIKE '%fooValue%'
     * $query->filterByDesc(['foo', 'bar']); // WHERE cycle_desc IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $desc The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDesc($desc = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($desc)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CycleTableMap::COL_CYCLE_DESC, $desc, $comparison);

        return $this;
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

        $this->addUsingAlias(CycleTableMap::COL_CYCLE_HITS, $hits, $comparison);

        return $this;
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
     * @param mixed $noosfereId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByNoosfereId($noosfereId = null, ?string $comparison = null)
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

        $this->addUsingAlias(CycleTableMap::COL_CYCLE_NOOSFERE_ID, $noosfereId, $comparison);

        return $this;
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
     * @param mixed $insert The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByInsert($insert = null, ?string $comparison = null)
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

        $this->addUsingAlias(CycleTableMap::COL_CYCLE_INSERT, $insert, $comparison);

        return $this;
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
     * @param mixed $update The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUpdate($update = null, ?string $comparison = null)
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

        $this->addUsingAlias(CycleTableMap::COL_CYCLE_UPDATE, $update, $comparison);

        return $this;
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

        $this->addUsingAlias(CycleTableMap::COL_CYCLE_CREATED, $createdAt, $comparison);

        return $this;
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

        $this->addUsingAlias(CycleTableMap::COL_CYCLE_UPDATED, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \Model\Article object
     *
     * @param \Model\Article|ObjectCollection $article the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByArticle($article, ?string $comparison = null)
    {
        if ($article instanceof \Model\Article) {
            $this
                ->addUsingAlias(CycleTableMap::COL_CYCLE_ID, $article->getCycleId(), $comparison);

            return $this;
        } elseif ($article instanceof ObjectCollection) {
            $this
                ->useArticleQuery()
                ->filterByPrimaryKeys($article->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByArticle() only accepts arguments of type \Model\Article or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Article relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinArticle(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Article');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Article');
        }

        return $this;
    }

    /**
     * Use the Article relation Article object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\ArticleQuery A secondary query class using the current class as primary query
     */
    public function useArticleQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinArticle($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Article', '\Model\ArticleQuery');
    }

    /**
     * Use the Article relation Article object
     *
     * @param callable(\Model\ArticleQuery):\Model\ArticleQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withArticleQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useArticleQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Article table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\ArticleQuery The inner query object of the EXISTS statement
     */
    public function useArticleExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\ArticleQuery */
        $q = $this->useExistsQuery('Article', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Article table for a NOT EXISTS query.
     *
     * @see useArticleExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\ArticleQuery The inner query object of the NOT EXISTS statement
     */
    public function useArticleNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\ArticleQuery */
        $q = $this->useExistsQuery('Article', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Article table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\ArticleQuery The inner query object of the IN statement
     */
    public function useInArticleQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\ArticleQuery */
        $q = $this->useInQuery('Article', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Article table for a NOT IN query.
     *
     * @see useArticleInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\ArticleQuery The inner query object of the NOT IN statement
     */
    public function useNotInArticleQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\ArticleQuery */
        $q = $this->useInQuery('Article', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Exclude object from result
     *
     * @param ChildCycle $cycle Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
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
    public function doDeleteAll(?ConnectionInterface $con = null): int
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
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(?ConnectionInterface $con = null): int
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
        $this->addUsingAlias(CycleTableMap::COL_CYCLE_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(CycleTableMap::COL_CYCLE_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(CycleTableMap::COL_CYCLE_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(CycleTableMap::COL_CYCLE_CREATED);

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
        $this->addUsingAlias(CycleTableMap::COL_CYCLE_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(CycleTableMap::COL_CYCLE_CREATED);

        return $this;
    }

}
