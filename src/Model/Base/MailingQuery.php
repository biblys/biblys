<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Mailing as ChildMailing;
use Model\MailingQuery as ChildMailingQuery;
use Model\Map\MailingTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'mailing' table.
 *
 *
 *
 * @method     ChildMailingQuery orderById($order = Criteria::ASC) Order by the mailing_id column
 * @method     ChildMailingQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildMailingQuery orderByEmail($order = Criteria::ASC) Order by the mailing_email column
 * @method     ChildMailingQuery orderByBlock($order = Criteria::ASC) Order by the mailing_block column
 * @method     ChildMailingQuery orderByChecked($order = Criteria::ASC) Order by the mailing_checked column
 * @method     ChildMailingQuery orderByDate($order = Criteria::ASC) Order by the mailing_date column
 * @method     ChildMailingQuery orderByCreatedAt($order = Criteria::ASC) Order by the mailing_created column
 * @method     ChildMailingQuery orderByUpdatedAt($order = Criteria::ASC) Order by the mailing_updated column
 * @method     ChildMailingQuery orderByDeletedAt($order = Criteria::ASC) Order by the mailing_deleted column
 *
 * @method     ChildMailingQuery groupById() Group by the mailing_id column
 * @method     ChildMailingQuery groupBySiteId() Group by the site_id column
 * @method     ChildMailingQuery groupByEmail() Group by the mailing_email column
 * @method     ChildMailingQuery groupByBlock() Group by the mailing_block column
 * @method     ChildMailingQuery groupByChecked() Group by the mailing_checked column
 * @method     ChildMailingQuery groupByDate() Group by the mailing_date column
 * @method     ChildMailingQuery groupByCreatedAt() Group by the mailing_created column
 * @method     ChildMailingQuery groupByUpdatedAt() Group by the mailing_updated column
 * @method     ChildMailingQuery groupByDeletedAt() Group by the mailing_deleted column
 *
 * @method     ChildMailingQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMailingQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMailingQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMailingQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildMailingQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildMailingQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildMailing|null findOne(ConnectionInterface $con = null) Return the first ChildMailing matching the query
 * @method     ChildMailing findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMailing matching the query, or a new ChildMailing object populated from the query conditions when no match is found
 *
 * @method     ChildMailing|null findOneById(int $mailing_id) Return the first ChildMailing filtered by the mailing_id column
 * @method     ChildMailing|null findOneBySiteId(int $site_id) Return the first ChildMailing filtered by the site_id column
 * @method     ChildMailing|null findOneByEmail(string $mailing_email) Return the first ChildMailing filtered by the mailing_email column
 * @method     ChildMailing|null findOneByBlock(boolean $mailing_block) Return the first ChildMailing filtered by the mailing_block column
 * @method     ChildMailing|null findOneByChecked(boolean $mailing_checked) Return the first ChildMailing filtered by the mailing_checked column
 * @method     ChildMailing|null findOneByDate(string $mailing_date) Return the first ChildMailing filtered by the mailing_date column
 * @method     ChildMailing|null findOneByCreatedAt(string $mailing_created) Return the first ChildMailing filtered by the mailing_created column
 * @method     ChildMailing|null findOneByUpdatedAt(string $mailing_updated) Return the first ChildMailing filtered by the mailing_updated column
 * @method     ChildMailing|null findOneByDeletedAt(string $mailing_deleted) Return the first ChildMailing filtered by the mailing_deleted column *

 * @method     ChildMailing requirePk($key, ConnectionInterface $con = null) Return the ChildMailing by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMailing requireOne(ConnectionInterface $con = null) Return the first ChildMailing matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMailing requireOneById(int $mailing_id) Return the first ChildMailing filtered by the mailing_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMailing requireOneBySiteId(int $site_id) Return the first ChildMailing filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMailing requireOneByEmail(string $mailing_email) Return the first ChildMailing filtered by the mailing_email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMailing requireOneByBlock(boolean $mailing_block) Return the first ChildMailing filtered by the mailing_block column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMailing requireOneByChecked(boolean $mailing_checked) Return the first ChildMailing filtered by the mailing_checked column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMailing requireOneByDate(string $mailing_date) Return the first ChildMailing filtered by the mailing_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMailing requireOneByCreatedAt(string $mailing_created) Return the first ChildMailing filtered by the mailing_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMailing requireOneByUpdatedAt(string $mailing_updated) Return the first ChildMailing filtered by the mailing_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMailing requireOneByDeletedAt(string $mailing_deleted) Return the first ChildMailing filtered by the mailing_deleted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMailing[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMailing objects based on current ModelCriteria
 * @method     ChildMailing[]|ObjectCollection findById(int $mailing_id) Return ChildMailing objects filtered by the mailing_id column
 * @method     ChildMailing[]|ObjectCollection findBySiteId(int $site_id) Return ChildMailing objects filtered by the site_id column
 * @method     ChildMailing[]|ObjectCollection findByEmail(string $mailing_email) Return ChildMailing objects filtered by the mailing_email column
 * @method     ChildMailing[]|ObjectCollection findByBlock(boolean $mailing_block) Return ChildMailing objects filtered by the mailing_block column
 * @method     ChildMailing[]|ObjectCollection findByChecked(boolean $mailing_checked) Return ChildMailing objects filtered by the mailing_checked column
 * @method     ChildMailing[]|ObjectCollection findByDate(string $mailing_date) Return ChildMailing objects filtered by the mailing_date column
 * @method     ChildMailing[]|ObjectCollection findByCreatedAt(string $mailing_created) Return ChildMailing objects filtered by the mailing_created column
 * @method     ChildMailing[]|ObjectCollection findByUpdatedAt(string $mailing_updated) Return ChildMailing objects filtered by the mailing_updated column
 * @method     ChildMailing[]|ObjectCollection findByDeletedAt(string $mailing_deleted) Return ChildMailing objects filtered by the mailing_deleted column
 * @method     ChildMailing[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class MailingQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\MailingQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Mailing', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMailingQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMailingQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMailingQuery) {
            return $criteria;
        }
        $query = new ChildMailingQuery();
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
     * @return ChildMailing|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MailingTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = MailingTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildMailing A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT mailing_id, site_id, mailing_email, mailing_block, mailing_checked, mailing_date, mailing_created, mailing_updated, mailing_deleted FROM mailing WHERE mailing_id = :p0';
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
            /** @var ChildMailing $obj */
            $obj = new ChildMailing();
            $obj->hydrate($row);
            MailingTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildMailing|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildMailingQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MailingTableMap::COL_MAILING_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMailingQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MailingTableMap::COL_MAILING_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the mailing_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE mailing_id = 1234
     * $query->filterById(array(12, 34)); // WHERE mailing_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE mailing_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMailingQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(MailingTableMap::COL_MAILING_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(MailingTableMap::COL_MAILING_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MailingTableMap::COL_MAILING_ID, $id, $comparison);
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
     * @param     mixed $siteId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMailingQuery The current query, for fluid interface
     */
    public function filterBySiteId($siteId = null, $comparison = null)
    {
        if (is_array($siteId)) {
            $useMinMax = false;
            if (isset($siteId['min'])) {
                $this->addUsingAlias(MailingTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(MailingTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MailingTableMap::COL_SITE_ID, $siteId, $comparison);
    }

    /**
     * Filter the query on the mailing_email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE mailing_email = 'fooValue'
     * $query->filterByEmail('%fooValue%', Criteria::LIKE); // WHERE mailing_email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMailingQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MailingTableMap::COL_MAILING_EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the mailing_block column
     *
     * Example usage:
     * <code>
     * $query->filterByBlock(true); // WHERE mailing_block = true
     * $query->filterByBlock('yes'); // WHERE mailing_block = true
     * </code>
     *
     * @param     boolean|string $block The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMailingQuery The current query, for fluid interface
     */
    public function filterByBlock($block = null, $comparison = null)
    {
        if (is_string($block)) {
            $block = in_array(strtolower($block), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(MailingTableMap::COL_MAILING_BLOCK, $block, $comparison);
    }

    /**
     * Filter the query on the mailing_checked column
     *
     * Example usage:
     * <code>
     * $query->filterByChecked(true); // WHERE mailing_checked = true
     * $query->filterByChecked('yes'); // WHERE mailing_checked = true
     * </code>
     *
     * @param     boolean|string $checked The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMailingQuery The current query, for fluid interface
     */
    public function filterByChecked($checked = null, $comparison = null)
    {
        if (is_string($checked)) {
            $checked = in_array(strtolower($checked), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(MailingTableMap::COL_MAILING_CHECKED, $checked, $comparison);
    }

    /**
     * Filter the query on the mailing_date column
     *
     * Example usage:
     * <code>
     * $query->filterByDate('2011-03-14'); // WHERE mailing_date = '2011-03-14'
     * $query->filterByDate('now'); // WHERE mailing_date = '2011-03-14'
     * $query->filterByDate(array('max' => 'yesterday')); // WHERE mailing_date > '2011-03-13'
     * </code>
     *
     * @param     mixed $date The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMailingQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(MailingTableMap::COL_MAILING_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(MailingTableMap::COL_MAILING_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MailingTableMap::COL_MAILING_DATE, $date, $comparison);
    }

    /**
     * Filter the query on the mailing_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE mailing_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE mailing_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE mailing_created > '2011-03-13'
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
     * @return $this|ChildMailingQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(MailingTableMap::COL_MAILING_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(MailingTableMap::COL_MAILING_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MailingTableMap::COL_MAILING_CREATED, $createdAt, $comparison);
    }

    /**
     * Filter the query on the mailing_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE mailing_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE mailing_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE mailing_updated > '2011-03-13'
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
     * @return $this|ChildMailingQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(MailingTableMap::COL_MAILING_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(MailingTableMap::COL_MAILING_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MailingTableMap::COL_MAILING_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the mailing_deleted column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletedAt('2011-03-14'); // WHERE mailing_deleted = '2011-03-14'
     * $query->filterByDeletedAt('now'); // WHERE mailing_deleted = '2011-03-14'
     * $query->filterByDeletedAt(array('max' => 'yesterday')); // WHERE mailing_deleted > '2011-03-13'
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
     * @return $this|ChildMailingQuery The current query, for fluid interface
     */
    public function filterByDeletedAt($deletedAt = null, $comparison = null)
    {
        if (is_array($deletedAt)) {
            $useMinMax = false;
            if (isset($deletedAt['min'])) {
                $this->addUsingAlias(MailingTableMap::COL_MAILING_DELETED, $deletedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletedAt['max'])) {
                $this->addUsingAlias(MailingTableMap::COL_MAILING_DELETED, $deletedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MailingTableMap::COL_MAILING_DELETED, $deletedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMailing $mailing Object to remove from the list of results
     *
     * @return $this|ChildMailingQuery The current query, for fluid interface
     */
    public function prune($mailing = null)
    {
        if ($mailing) {
            $this->addUsingAlias(MailingTableMap::COL_MAILING_ID, $mailing->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the mailing table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MailingTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MailingTableMap::clearInstancePool();
            MailingTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(MailingTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MailingTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            MailingTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MailingTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // MailingQuery
