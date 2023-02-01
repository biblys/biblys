<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\CronJob as ChildCronJob;
use Model\CronJobQuery as ChildCronJobQuery;
use Model\Map\CronJobTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `cron_jobs` table.
 *
 * @method     ChildCronJobQuery orderById($order = Criteria::ASC) Order by the cron_job_id column
 * @method     ChildCronJobQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildCronJobQuery orderByTask($order = Criteria::ASC) Order by the cron_job_task column
 * @method     ChildCronJobQuery orderByResult($order = Criteria::ASC) Order by the cron_job_result column
 * @method     ChildCronJobQuery orderByMessage($order = Criteria::ASC) Order by the cron_job_message column
 * @method     ChildCronJobQuery orderByCreatedAt($order = Criteria::ASC) Order by the cron_job_created column
 * @method     ChildCronJobQuery orderByUpdatedAt($order = Criteria::ASC) Order by the cron_job_updated column
 *
 * @method     ChildCronJobQuery groupById() Group by the cron_job_id column
 * @method     ChildCronJobQuery groupBySiteId() Group by the site_id column
 * @method     ChildCronJobQuery groupByTask() Group by the cron_job_task column
 * @method     ChildCronJobQuery groupByResult() Group by the cron_job_result column
 * @method     ChildCronJobQuery groupByMessage() Group by the cron_job_message column
 * @method     ChildCronJobQuery groupByCreatedAt() Group by the cron_job_created column
 * @method     ChildCronJobQuery groupByUpdatedAt() Group by the cron_job_updated column
 *
 * @method     ChildCronJobQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCronJobQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCronJobQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCronJobQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildCronJobQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildCronJobQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildCronJob|null findOne(?ConnectionInterface $con = null) Return the first ChildCronJob matching the query
 * @method     ChildCronJob findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildCronJob matching the query, or a new ChildCronJob object populated from the query conditions when no match is found
 *
 * @method     ChildCronJob|null findOneById(int $cron_job_id) Return the first ChildCronJob filtered by the cron_job_id column
 * @method     ChildCronJob|null findOneBySiteId(int $site_id) Return the first ChildCronJob filtered by the site_id column
 * @method     ChildCronJob|null findOneByTask(string $cron_job_task) Return the first ChildCronJob filtered by the cron_job_task column
 * @method     ChildCronJob|null findOneByResult(string $cron_job_result) Return the first ChildCronJob filtered by the cron_job_result column
 * @method     ChildCronJob|null findOneByMessage(string $cron_job_message) Return the first ChildCronJob filtered by the cron_job_message column
 * @method     ChildCronJob|null findOneByCreatedAt(string $cron_job_created) Return the first ChildCronJob filtered by the cron_job_created column
 * @method     ChildCronJob|null findOneByUpdatedAt(string $cron_job_updated) Return the first ChildCronJob filtered by the cron_job_updated column
 *
 * @method     ChildCronJob requirePk($key, ?ConnectionInterface $con = null) Return the ChildCronJob by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCronJob requireOne(?ConnectionInterface $con = null) Return the first ChildCronJob matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCronJob requireOneById(int $cron_job_id) Return the first ChildCronJob filtered by the cron_job_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCronJob requireOneBySiteId(int $site_id) Return the first ChildCronJob filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCronJob requireOneByTask(string $cron_job_task) Return the first ChildCronJob filtered by the cron_job_task column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCronJob requireOneByResult(string $cron_job_result) Return the first ChildCronJob filtered by the cron_job_result column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCronJob requireOneByMessage(string $cron_job_message) Return the first ChildCronJob filtered by the cron_job_message column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCronJob requireOneByCreatedAt(string $cron_job_created) Return the first ChildCronJob filtered by the cron_job_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCronJob requireOneByUpdatedAt(string $cron_job_updated) Return the first ChildCronJob filtered by the cron_job_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCronJob[]|Collection find(?ConnectionInterface $con = null) Return ChildCronJob objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildCronJob> find(?ConnectionInterface $con = null) Return ChildCronJob objects based on current ModelCriteria
 *
 * @method     ChildCronJob[]|Collection findById(int|array<int> $cron_job_id) Return ChildCronJob objects filtered by the cron_job_id column
 * @psalm-method Collection&\Traversable<ChildCronJob> findById(int|array<int> $cron_job_id) Return ChildCronJob objects filtered by the cron_job_id column
 * @method     ChildCronJob[]|Collection findBySiteId(int|array<int> $site_id) Return ChildCronJob objects filtered by the site_id column
 * @psalm-method Collection&\Traversable<ChildCronJob> findBySiteId(int|array<int> $site_id) Return ChildCronJob objects filtered by the site_id column
 * @method     ChildCronJob[]|Collection findByTask(string|array<string> $cron_job_task) Return ChildCronJob objects filtered by the cron_job_task column
 * @psalm-method Collection&\Traversable<ChildCronJob> findByTask(string|array<string> $cron_job_task) Return ChildCronJob objects filtered by the cron_job_task column
 * @method     ChildCronJob[]|Collection findByResult(string|array<string> $cron_job_result) Return ChildCronJob objects filtered by the cron_job_result column
 * @psalm-method Collection&\Traversable<ChildCronJob> findByResult(string|array<string> $cron_job_result) Return ChildCronJob objects filtered by the cron_job_result column
 * @method     ChildCronJob[]|Collection findByMessage(string|array<string> $cron_job_message) Return ChildCronJob objects filtered by the cron_job_message column
 * @psalm-method Collection&\Traversable<ChildCronJob> findByMessage(string|array<string> $cron_job_message) Return ChildCronJob objects filtered by the cron_job_message column
 * @method     ChildCronJob[]|Collection findByCreatedAt(string|array<string> $cron_job_created) Return ChildCronJob objects filtered by the cron_job_created column
 * @psalm-method Collection&\Traversable<ChildCronJob> findByCreatedAt(string|array<string> $cron_job_created) Return ChildCronJob objects filtered by the cron_job_created column
 * @method     ChildCronJob[]|Collection findByUpdatedAt(string|array<string> $cron_job_updated) Return ChildCronJob objects filtered by the cron_job_updated column
 * @psalm-method Collection&\Traversable<ChildCronJob> findByUpdatedAt(string|array<string> $cron_job_updated) Return ChildCronJob objects filtered by the cron_job_updated column
 *
 * @method     ChildCronJob[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildCronJob> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class CronJobQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\CronJobQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\CronJob', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCronJobQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCronJobQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildCronJobQuery) {
            return $criteria;
        }
        $query = new ChildCronJobQuery();
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
     * @return ChildCronJob|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CronJobTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = CronJobTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildCronJob A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT cron_job_id, site_id, cron_job_task, cron_job_result, cron_job_message, cron_job_created, cron_job_updated FROM cron_jobs WHERE cron_job_id = :p0';
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
            /** @var ChildCronJob $obj */
            $obj = new ChildCronJob();
            $obj->hydrate($row);
            CronJobTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildCronJob|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(CronJobTableMap::COL_CRON_JOB_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(CronJobTableMap::COL_CRON_JOB_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the cron_job_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE cron_job_id = 1234
     * $query->filterById(array(12, 34)); // WHERE cron_job_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE cron_job_id > 12
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
                $this->addUsingAlias(CronJobTableMap::COL_CRON_JOB_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CronJobTableMap::COL_CRON_JOB_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CronJobTableMap::COL_CRON_JOB_ID, $id, $comparison);

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
                $this->addUsingAlias(CronJobTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(CronJobTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CronJobTableMap::COL_SITE_ID, $siteId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the cron_job_task column
     *
     * Example usage:
     * <code>
     * $query->filterByTask('fooValue');   // WHERE cron_job_task = 'fooValue'
     * $query->filterByTask('%fooValue%', Criteria::LIKE); // WHERE cron_job_task LIKE '%fooValue%'
     * $query->filterByTask(['foo', 'bar']); // WHERE cron_job_task IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $task The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTask($task = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($task)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CronJobTableMap::COL_CRON_JOB_TASK, $task, $comparison);

        return $this;
    }

    /**
     * Filter the query on the cron_job_result column
     *
     * Example usage:
     * <code>
     * $query->filterByResult('fooValue');   // WHERE cron_job_result = 'fooValue'
     * $query->filterByResult('%fooValue%', Criteria::LIKE); // WHERE cron_job_result LIKE '%fooValue%'
     * $query->filterByResult(['foo', 'bar']); // WHERE cron_job_result IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $result The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByResult($result = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($result)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CronJobTableMap::COL_CRON_JOB_RESULT, $result, $comparison);

        return $this;
    }

    /**
     * Filter the query on the cron_job_message column
     *
     * Example usage:
     * <code>
     * $query->filterByMessage('fooValue');   // WHERE cron_job_message = 'fooValue'
     * $query->filterByMessage('%fooValue%', Criteria::LIKE); // WHERE cron_job_message LIKE '%fooValue%'
     * $query->filterByMessage(['foo', 'bar']); // WHERE cron_job_message IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $message The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByMessage($message = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($message)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CronJobTableMap::COL_CRON_JOB_MESSAGE, $message, $comparison);

        return $this;
    }

    /**
     * Filter the query on the cron_job_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE cron_job_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE cron_job_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE cron_job_created > '2011-03-13'
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
                $this->addUsingAlias(CronJobTableMap::COL_CRON_JOB_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(CronJobTableMap::COL_CRON_JOB_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CronJobTableMap::COL_CRON_JOB_CREATED, $createdAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the cron_job_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE cron_job_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE cron_job_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE cron_job_updated > '2011-03-13'
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
                $this->addUsingAlias(CronJobTableMap::COL_CRON_JOB_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(CronJobTableMap::COL_CRON_JOB_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CronJobTableMap::COL_CRON_JOB_UPDATED, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Exclude object from result
     *
     * @param ChildCronJob $cronJob Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($cronJob = null)
    {
        if ($cronJob) {
            $this->addUsingAlias(CronJobTableMap::COL_CRON_JOB_ID, $cronJob->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the cron_jobs table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CronJobTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CronJobTableMap::clearInstancePool();
            CronJobTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(CronJobTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CronJobTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            CronJobTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CronJobTableMap::clearRelatedInstancePool();

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
        $this->addUsingAlias(CronJobTableMap::COL_CRON_JOB_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(CronJobTableMap::COL_CRON_JOB_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(CronJobTableMap::COL_CRON_JOB_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(CronJobTableMap::COL_CRON_JOB_CREATED);

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
        $this->addUsingAlias(CronJobTableMap::COL_CRON_JOB_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(CronJobTableMap::COL_CRON_JOB_CREATED);

        return $this;
    }

}
