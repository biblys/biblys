<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Job as ChildJob;
use Model\JobQuery as ChildJobQuery;
use Model\Map\JobTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `jobs` table.
 *
 * @method     ChildJobQuery orderById($order = Criteria::ASC) Order by the job_id column
 * @method     ChildJobQuery orderByName($order = Criteria::ASC) Order by the job_name column
 * @method     ChildJobQuery orderByNameF($order = Criteria::ASC) Order by the job_name_f column
 * @method     ChildJobQuery orderByOtherNames($order = Criteria::ASC) Order by the job_other_names column
 * @method     ChildJobQuery orderByEvent($order = Criteria::ASC) Order by the job_event column
 * @method     ChildJobQuery orderByOrder($order = Criteria::ASC) Order by the job_order column
 * @method     ChildJobQuery orderByOnix($order = Criteria::ASC) Order by the job_onix column
 * @method     ChildJobQuery orderByDate($order = Criteria::ASC) Order by the job_date column
 * @method     ChildJobQuery orderByCreatedAt($order = Criteria::ASC) Order by the job_created column
 * @method     ChildJobQuery orderByUpdatedAt($order = Criteria::ASC) Order by the job_updated column
 *
 * @method     ChildJobQuery groupById() Group by the job_id column
 * @method     ChildJobQuery groupByName() Group by the job_name column
 * @method     ChildJobQuery groupByNameF() Group by the job_name_f column
 * @method     ChildJobQuery groupByOtherNames() Group by the job_other_names column
 * @method     ChildJobQuery groupByEvent() Group by the job_event column
 * @method     ChildJobQuery groupByOrder() Group by the job_order column
 * @method     ChildJobQuery groupByOnix() Group by the job_onix column
 * @method     ChildJobQuery groupByDate() Group by the job_date column
 * @method     ChildJobQuery groupByCreatedAt() Group by the job_created column
 * @method     ChildJobQuery groupByUpdatedAt() Group by the job_updated column
 *
 * @method     ChildJobQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildJobQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildJobQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildJobQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildJobQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildJobQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildJob|null findOne(?ConnectionInterface $con = null) Return the first ChildJob matching the query
 * @method     ChildJob findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildJob matching the query, or a new ChildJob object populated from the query conditions when no match is found
 *
 * @method     ChildJob|null findOneById(int $job_id) Return the first ChildJob filtered by the job_id column
 * @method     ChildJob|null findOneByName(string $job_name) Return the first ChildJob filtered by the job_name column
 * @method     ChildJob|null findOneByNameF(string $job_name_f) Return the first ChildJob filtered by the job_name_f column
 * @method     ChildJob|null findOneByOtherNames(string $job_other_names) Return the first ChildJob filtered by the job_other_names column
 * @method     ChildJob|null findOneByEvent(boolean $job_event) Return the first ChildJob filtered by the job_event column
 * @method     ChildJob|null findOneByOrder(int $job_order) Return the first ChildJob filtered by the job_order column
 * @method     ChildJob|null findOneByOnix(string $job_onix) Return the first ChildJob filtered by the job_onix column
 * @method     ChildJob|null findOneByDate(string $job_date) Return the first ChildJob filtered by the job_date column
 * @method     ChildJob|null findOneByCreatedAt(string $job_created) Return the first ChildJob filtered by the job_created column
 * @method     ChildJob|null findOneByUpdatedAt(string $job_updated) Return the first ChildJob filtered by the job_updated column
 *
 * @method     ChildJob requirePk($key, ?ConnectionInterface $con = null) Return the ChildJob by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildJob requireOne(?ConnectionInterface $con = null) Return the first ChildJob matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildJob requireOneById(int $job_id) Return the first ChildJob filtered by the job_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildJob requireOneByName(string $job_name) Return the first ChildJob filtered by the job_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildJob requireOneByNameF(string $job_name_f) Return the first ChildJob filtered by the job_name_f column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildJob requireOneByOtherNames(string $job_other_names) Return the first ChildJob filtered by the job_other_names column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildJob requireOneByEvent(boolean $job_event) Return the first ChildJob filtered by the job_event column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildJob requireOneByOrder(int $job_order) Return the first ChildJob filtered by the job_order column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildJob requireOneByOnix(string $job_onix) Return the first ChildJob filtered by the job_onix column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildJob requireOneByDate(string $job_date) Return the first ChildJob filtered by the job_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildJob requireOneByCreatedAt(string $job_created) Return the first ChildJob filtered by the job_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildJob requireOneByUpdatedAt(string $job_updated) Return the first ChildJob filtered by the job_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildJob[]|Collection find(?ConnectionInterface $con = null) Return ChildJob objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildJob> find(?ConnectionInterface $con = null) Return ChildJob objects based on current ModelCriteria
 *
 * @method     ChildJob[]|Collection findById(int|array<int> $job_id) Return ChildJob objects filtered by the job_id column
 * @psalm-method Collection&\Traversable<ChildJob> findById(int|array<int> $job_id) Return ChildJob objects filtered by the job_id column
 * @method     ChildJob[]|Collection findByName(string|array<string> $job_name) Return ChildJob objects filtered by the job_name column
 * @psalm-method Collection&\Traversable<ChildJob> findByName(string|array<string> $job_name) Return ChildJob objects filtered by the job_name column
 * @method     ChildJob[]|Collection findByNameF(string|array<string> $job_name_f) Return ChildJob objects filtered by the job_name_f column
 * @psalm-method Collection&\Traversable<ChildJob> findByNameF(string|array<string> $job_name_f) Return ChildJob objects filtered by the job_name_f column
 * @method     ChildJob[]|Collection findByOtherNames(string|array<string> $job_other_names) Return ChildJob objects filtered by the job_other_names column
 * @psalm-method Collection&\Traversable<ChildJob> findByOtherNames(string|array<string> $job_other_names) Return ChildJob objects filtered by the job_other_names column
 * @method     ChildJob[]|Collection findByEvent(boolean|array<boolean> $job_event) Return ChildJob objects filtered by the job_event column
 * @psalm-method Collection&\Traversable<ChildJob> findByEvent(boolean|array<boolean> $job_event) Return ChildJob objects filtered by the job_event column
 * @method     ChildJob[]|Collection findByOrder(int|array<int> $job_order) Return ChildJob objects filtered by the job_order column
 * @psalm-method Collection&\Traversable<ChildJob> findByOrder(int|array<int> $job_order) Return ChildJob objects filtered by the job_order column
 * @method     ChildJob[]|Collection findByOnix(string|array<string> $job_onix) Return ChildJob objects filtered by the job_onix column
 * @psalm-method Collection&\Traversable<ChildJob> findByOnix(string|array<string> $job_onix) Return ChildJob objects filtered by the job_onix column
 * @method     ChildJob[]|Collection findByDate(string|array<string> $job_date) Return ChildJob objects filtered by the job_date column
 * @psalm-method Collection&\Traversable<ChildJob> findByDate(string|array<string> $job_date) Return ChildJob objects filtered by the job_date column
 * @method     ChildJob[]|Collection findByCreatedAt(string|array<string> $job_created) Return ChildJob objects filtered by the job_created column
 * @psalm-method Collection&\Traversable<ChildJob> findByCreatedAt(string|array<string> $job_created) Return ChildJob objects filtered by the job_created column
 * @method     ChildJob[]|Collection findByUpdatedAt(string|array<string> $job_updated) Return ChildJob objects filtered by the job_updated column
 * @psalm-method Collection&\Traversable<ChildJob> findByUpdatedAt(string|array<string> $job_updated) Return ChildJob objects filtered by the job_updated column
 *
 * @method     ChildJob[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildJob> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class JobQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\JobQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Job', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildJobQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildJobQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildJobQuery) {
            return $criteria;
        }
        $query = new ChildJobQuery();
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
     * @return ChildJob|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(JobTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = JobTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildJob A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT job_id, job_name, job_name_f, job_other_names, job_event, job_order, job_onix, job_date, job_created, job_updated FROM jobs WHERE job_id = :p0';
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
            /** @var ChildJob $obj */
            $obj = new ChildJob();
            $obj->hydrate($row);
            JobTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildJob|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(JobTableMap::COL_JOB_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(JobTableMap::COL_JOB_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the job_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE job_id = 1234
     * $query->filterById(array(12, 34)); // WHERE job_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE job_id > 12
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
                $this->addUsingAlias(JobTableMap::COL_JOB_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(JobTableMap::COL_JOB_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(JobTableMap::COL_JOB_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the job_name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE job_name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE job_name LIKE '%fooValue%'
     * $query->filterByName(['foo', 'bar']); // WHERE job_name IN ('foo', 'bar')
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

        $this->addUsingAlias(JobTableMap::COL_JOB_NAME, $name, $comparison);

        return $this;
    }

    /**
     * Filter the query on the job_name_f column
     *
     * Example usage:
     * <code>
     * $query->filterByNameF('fooValue');   // WHERE job_name_f = 'fooValue'
     * $query->filterByNameF('%fooValue%', Criteria::LIKE); // WHERE job_name_f LIKE '%fooValue%'
     * $query->filterByNameF(['foo', 'bar']); // WHERE job_name_f IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $nameF The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByNameF($nameF = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nameF)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(JobTableMap::COL_JOB_NAME_F, $nameF, $comparison);

        return $this;
    }

    /**
     * Filter the query on the job_other_names column
     *
     * Example usage:
     * <code>
     * $query->filterByOtherNames('fooValue');   // WHERE job_other_names = 'fooValue'
     * $query->filterByOtherNames('%fooValue%', Criteria::LIKE); // WHERE job_other_names LIKE '%fooValue%'
     * $query->filterByOtherNames(['foo', 'bar']); // WHERE job_other_names IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $otherNames The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByOtherNames($otherNames = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($otherNames)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(JobTableMap::COL_JOB_OTHER_NAMES, $otherNames, $comparison);

        return $this;
    }

    /**
     * Filter the query on the job_event column
     *
     * Example usage:
     * <code>
     * $query->filterByEvent(true); // WHERE job_event = true
     * $query->filterByEvent('yes'); // WHERE job_event = true
     * </code>
     *
     * @param bool|string $event The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEvent($event = null, ?string $comparison = null)
    {
        if (is_string($event)) {
            $event = in_array(strtolower($event), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(JobTableMap::COL_JOB_EVENT, $event, $comparison);

        return $this;
    }

    /**
     * Filter the query on the job_order column
     *
     * Example usage:
     * <code>
     * $query->filterByOrder(1234); // WHERE job_order = 1234
     * $query->filterByOrder(array(12, 34)); // WHERE job_order IN (12, 34)
     * $query->filterByOrder(array('min' => 12)); // WHERE job_order > 12
     * </code>
     *
     * @param mixed $order The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByOrder($order = null, ?string $comparison = null)
    {
        if (is_array($order)) {
            $useMinMax = false;
            if (isset($order['min'])) {
                $this->addUsingAlias(JobTableMap::COL_JOB_ORDER, $order['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($order['max'])) {
                $this->addUsingAlias(JobTableMap::COL_JOB_ORDER, $order['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(JobTableMap::COL_JOB_ORDER, $order, $comparison);

        return $this;
    }

    /**
     * Filter the query on the job_onix column
     *
     * Example usage:
     * <code>
     * $query->filterByOnix('fooValue');   // WHERE job_onix = 'fooValue'
     * $query->filterByOnix('%fooValue%', Criteria::LIKE); // WHERE job_onix LIKE '%fooValue%'
     * $query->filterByOnix(['foo', 'bar']); // WHERE job_onix IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $onix The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByOnix($onix = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($onix)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(JobTableMap::COL_JOB_ONIX, $onix, $comparison);

        return $this;
    }

    /**
     * Filter the query on the job_date column
     *
     * Example usage:
     * <code>
     * $query->filterByDate('2011-03-14'); // WHERE job_date = '2011-03-14'
     * $query->filterByDate('now'); // WHERE job_date = '2011-03-14'
     * $query->filterByDate(array('max' => 'yesterday')); // WHERE job_date > '2011-03-13'
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
                $this->addUsingAlias(JobTableMap::COL_JOB_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(JobTableMap::COL_JOB_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(JobTableMap::COL_JOB_DATE, $date, $comparison);

        return $this;
    }

    /**
     * Filter the query on the job_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE job_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE job_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE job_created > '2011-03-13'
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
                $this->addUsingAlias(JobTableMap::COL_JOB_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(JobTableMap::COL_JOB_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(JobTableMap::COL_JOB_CREATED, $createdAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the job_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE job_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE job_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE job_updated > '2011-03-13'
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
                $this->addUsingAlias(JobTableMap::COL_JOB_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(JobTableMap::COL_JOB_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(JobTableMap::COL_JOB_UPDATED, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Exclude object from result
     *
     * @param ChildJob $job Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($job = null)
    {
        if ($job) {
            $this->addUsingAlias(JobTableMap::COL_JOB_ID, $job->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the jobs table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(JobTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            JobTableMap::clearInstancePool();
            JobTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(JobTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(JobTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            JobTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            JobTableMap::clearRelatedInstancePool();

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
        $this->addUsingAlias(JobTableMap::COL_JOB_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(JobTableMap::COL_JOB_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(JobTableMap::COL_JOB_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(JobTableMap::COL_JOB_CREATED);

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
        $this->addUsingAlias(JobTableMap::COL_JOB_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(JobTableMap::COL_JOB_CREATED);

        return $this;
    }

}
