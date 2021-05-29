<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Subscription as ChildSubscription;
use Model\SubscriptionQuery as ChildSubscriptionQuery;
use Model\Map\SubscriptionTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'subscriptions' table.
 *
 *
 *
 * @method     ChildSubscriptionQuery orderById($order = Criteria::ASC) Order by the subscription_id column
 * @method     ChildSubscriptionQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildSubscriptionQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildSubscriptionQuery orderByPublisherId($order = Criteria::ASC) Order by the publisher_id column
 * @method     ChildSubscriptionQuery orderByBookshopId($order = Criteria::ASC) Order by the bookshop_id column
 * @method     ChildSubscriptionQuery orderByLibraryId($order = Criteria::ASC) Order by the library_id column
 * @method     ChildSubscriptionQuery orderByType($order = Criteria::ASC) Order by the subscription_type column
 * @method     ChildSubscriptionQuery orderByEmail($order = Criteria::ASC) Order by the subscription_email column
 * @method     ChildSubscriptionQuery orderByEnds($order = Criteria::ASC) Order by the subscription_ends column
 * @method     ChildSubscriptionQuery orderByOption($order = Criteria::ASC) Order by the subscription_option column
 * @method     ChildSubscriptionQuery orderByInsert($order = Criteria::ASC) Order by the subscription_insert column
 * @method     ChildSubscriptionQuery orderByUpdate($order = Criteria::ASC) Order by the subscription_update column
 * @method     ChildSubscriptionQuery orderByCreatedAt($order = Criteria::ASC) Order by the subscription_created column
 * @method     ChildSubscriptionQuery orderByUpdatedAt($order = Criteria::ASC) Order by the subscription_updated column
 * @method     ChildSubscriptionQuery orderByDeletedAt($order = Criteria::ASC) Order by the subscription_deleted column
 *
 * @method     ChildSubscriptionQuery groupById() Group by the subscription_id column
 * @method     ChildSubscriptionQuery groupBySiteId() Group by the site_id column
 * @method     ChildSubscriptionQuery groupByUserId() Group by the user_id column
 * @method     ChildSubscriptionQuery groupByPublisherId() Group by the publisher_id column
 * @method     ChildSubscriptionQuery groupByBookshopId() Group by the bookshop_id column
 * @method     ChildSubscriptionQuery groupByLibraryId() Group by the library_id column
 * @method     ChildSubscriptionQuery groupByType() Group by the subscription_type column
 * @method     ChildSubscriptionQuery groupByEmail() Group by the subscription_email column
 * @method     ChildSubscriptionQuery groupByEnds() Group by the subscription_ends column
 * @method     ChildSubscriptionQuery groupByOption() Group by the subscription_option column
 * @method     ChildSubscriptionQuery groupByInsert() Group by the subscription_insert column
 * @method     ChildSubscriptionQuery groupByUpdate() Group by the subscription_update column
 * @method     ChildSubscriptionQuery groupByCreatedAt() Group by the subscription_created column
 * @method     ChildSubscriptionQuery groupByUpdatedAt() Group by the subscription_updated column
 * @method     ChildSubscriptionQuery groupByDeletedAt() Group by the subscription_deleted column
 *
 * @method     ChildSubscriptionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSubscriptionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSubscriptionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSubscriptionQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildSubscriptionQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildSubscriptionQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildSubscription|null findOne(ConnectionInterface $con = null) Return the first ChildSubscription matching the query
 * @method     ChildSubscription findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSubscription matching the query, or a new ChildSubscription object populated from the query conditions when no match is found
 *
 * @method     ChildSubscription|null findOneById(int $subscription_id) Return the first ChildSubscription filtered by the subscription_id column
 * @method     ChildSubscription|null findOneBySiteId(int $site_id) Return the first ChildSubscription filtered by the site_id column
 * @method     ChildSubscription|null findOneByUserId(int $user_id) Return the first ChildSubscription filtered by the user_id column
 * @method     ChildSubscription|null findOneByPublisherId(int $publisher_id) Return the first ChildSubscription filtered by the publisher_id column
 * @method     ChildSubscription|null findOneByBookshopId(int $bookshop_id) Return the first ChildSubscription filtered by the bookshop_id column
 * @method     ChildSubscription|null findOneByLibraryId(int $library_id) Return the first ChildSubscription filtered by the library_id column
 * @method     ChildSubscription|null findOneByType(string $subscription_type) Return the first ChildSubscription filtered by the subscription_type column
 * @method     ChildSubscription|null findOneByEmail(string $subscription_email) Return the first ChildSubscription filtered by the subscription_email column
 * @method     ChildSubscription|null findOneByEnds(int $subscription_ends) Return the first ChildSubscription filtered by the subscription_ends column
 * @method     ChildSubscription|null findOneByOption(boolean $subscription_option) Return the first ChildSubscription filtered by the subscription_option column
 * @method     ChildSubscription|null findOneByInsert(string $subscription_insert) Return the first ChildSubscription filtered by the subscription_insert column
 * @method     ChildSubscription|null findOneByUpdate(string $subscription_update) Return the first ChildSubscription filtered by the subscription_update column
 * @method     ChildSubscription|null findOneByCreatedAt(string $subscription_created) Return the first ChildSubscription filtered by the subscription_created column
 * @method     ChildSubscription|null findOneByUpdatedAt(string $subscription_updated) Return the first ChildSubscription filtered by the subscription_updated column
 * @method     ChildSubscription|null findOneByDeletedAt(string $subscription_deleted) Return the first ChildSubscription filtered by the subscription_deleted column *

 * @method     ChildSubscription requirePk($key, ConnectionInterface $con = null) Return the ChildSubscription by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubscription requireOne(ConnectionInterface $con = null) Return the first ChildSubscription matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSubscription requireOneById(int $subscription_id) Return the first ChildSubscription filtered by the subscription_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubscription requireOneBySiteId(int $site_id) Return the first ChildSubscription filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubscription requireOneByUserId(int $user_id) Return the first ChildSubscription filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubscription requireOneByPublisherId(int $publisher_id) Return the first ChildSubscription filtered by the publisher_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubscription requireOneByBookshopId(int $bookshop_id) Return the first ChildSubscription filtered by the bookshop_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubscription requireOneByLibraryId(int $library_id) Return the first ChildSubscription filtered by the library_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubscription requireOneByType(string $subscription_type) Return the first ChildSubscription filtered by the subscription_type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubscription requireOneByEmail(string $subscription_email) Return the first ChildSubscription filtered by the subscription_email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubscription requireOneByEnds(int $subscription_ends) Return the first ChildSubscription filtered by the subscription_ends column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubscription requireOneByOption(boolean $subscription_option) Return the first ChildSubscription filtered by the subscription_option column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubscription requireOneByInsert(string $subscription_insert) Return the first ChildSubscription filtered by the subscription_insert column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubscription requireOneByUpdate(string $subscription_update) Return the first ChildSubscription filtered by the subscription_update column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubscription requireOneByCreatedAt(string $subscription_created) Return the first ChildSubscription filtered by the subscription_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubscription requireOneByUpdatedAt(string $subscription_updated) Return the first ChildSubscription filtered by the subscription_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubscription requireOneByDeletedAt(string $subscription_deleted) Return the first ChildSubscription filtered by the subscription_deleted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSubscription[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSubscription objects based on current ModelCriteria
 * @method     ChildSubscription[]|ObjectCollection findById(int $subscription_id) Return ChildSubscription objects filtered by the subscription_id column
 * @method     ChildSubscription[]|ObjectCollection findBySiteId(int $site_id) Return ChildSubscription objects filtered by the site_id column
 * @method     ChildSubscription[]|ObjectCollection findByUserId(int $user_id) Return ChildSubscription objects filtered by the user_id column
 * @method     ChildSubscription[]|ObjectCollection findByPublisherId(int $publisher_id) Return ChildSubscription objects filtered by the publisher_id column
 * @method     ChildSubscription[]|ObjectCollection findByBookshopId(int $bookshop_id) Return ChildSubscription objects filtered by the bookshop_id column
 * @method     ChildSubscription[]|ObjectCollection findByLibraryId(int $library_id) Return ChildSubscription objects filtered by the library_id column
 * @method     ChildSubscription[]|ObjectCollection findByType(string $subscription_type) Return ChildSubscription objects filtered by the subscription_type column
 * @method     ChildSubscription[]|ObjectCollection findByEmail(string $subscription_email) Return ChildSubscription objects filtered by the subscription_email column
 * @method     ChildSubscription[]|ObjectCollection findByEnds(int $subscription_ends) Return ChildSubscription objects filtered by the subscription_ends column
 * @method     ChildSubscription[]|ObjectCollection findByOption(boolean $subscription_option) Return ChildSubscription objects filtered by the subscription_option column
 * @method     ChildSubscription[]|ObjectCollection findByInsert(string $subscription_insert) Return ChildSubscription objects filtered by the subscription_insert column
 * @method     ChildSubscription[]|ObjectCollection findByUpdate(string $subscription_update) Return ChildSubscription objects filtered by the subscription_update column
 * @method     ChildSubscription[]|ObjectCollection findByCreatedAt(string $subscription_created) Return ChildSubscription objects filtered by the subscription_created column
 * @method     ChildSubscription[]|ObjectCollection findByUpdatedAt(string $subscription_updated) Return ChildSubscription objects filtered by the subscription_updated column
 * @method     ChildSubscription[]|ObjectCollection findByDeletedAt(string $subscription_deleted) Return ChildSubscription objects filtered by the subscription_deleted column
 * @method     ChildSubscription[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SubscriptionQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\SubscriptionQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Subscription', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSubscriptionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSubscriptionQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSubscriptionQuery) {
            return $criteria;
        }
        $query = new ChildSubscriptionQuery();
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
     * @return ChildSubscription|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SubscriptionTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = SubscriptionTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildSubscription A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT subscription_id, site_id, user_id, publisher_id, bookshop_id, library_id, subscription_type, subscription_email, subscription_ends, subscription_option, subscription_insert, subscription_update, subscription_created, subscription_updated, subscription_deleted FROM subscriptions WHERE subscription_id = :p0';
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
            /** @var ChildSubscription $obj */
            $obj = new ChildSubscription();
            $obj->hydrate($row);
            SubscriptionTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildSubscription|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the subscription_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE subscription_id = 1234
     * $query->filterById(array(12, 34)); // WHERE subscription_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE subscription_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_ID, $id, $comparison);
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
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterBySiteId($siteId = null, $comparison = null)
    {
        if (is_array($siteId)) {
            $useMinMax = false;
            if (isset($siteId['min'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubscriptionTableMap::COL_SITE_ID, $siteId, $comparison);
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE user_id = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE user_id > 12
     * </code>
     *
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubscriptionTableMap::COL_USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the publisher_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPublisherId(1234); // WHERE publisher_id = 1234
     * $query->filterByPublisherId(array(12, 34)); // WHERE publisher_id IN (12, 34)
     * $query->filterByPublisherId(array('min' => 12)); // WHERE publisher_id > 12
     * </code>
     *
     * @param     mixed $publisherId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterByPublisherId($publisherId = null, $comparison = null)
    {
        if (is_array($publisherId)) {
            $useMinMax = false;
            if (isset($publisherId['min'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_PUBLISHER_ID, $publisherId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publisherId['max'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_PUBLISHER_ID, $publisherId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubscriptionTableMap::COL_PUBLISHER_ID, $publisherId, $comparison);
    }

    /**
     * Filter the query on the bookshop_id column
     *
     * Example usage:
     * <code>
     * $query->filterByBookshopId(1234); // WHERE bookshop_id = 1234
     * $query->filterByBookshopId(array(12, 34)); // WHERE bookshop_id IN (12, 34)
     * $query->filterByBookshopId(array('min' => 12)); // WHERE bookshop_id > 12
     * </code>
     *
     * @param     mixed $bookshopId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterByBookshopId($bookshopId = null, $comparison = null)
    {
        if (is_array($bookshopId)) {
            $useMinMax = false;
            if (isset($bookshopId['min'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_BOOKSHOP_ID, $bookshopId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bookshopId['max'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_BOOKSHOP_ID, $bookshopId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubscriptionTableMap::COL_BOOKSHOP_ID, $bookshopId, $comparison);
    }

    /**
     * Filter the query on the library_id column
     *
     * Example usage:
     * <code>
     * $query->filterByLibraryId(1234); // WHERE library_id = 1234
     * $query->filterByLibraryId(array(12, 34)); // WHERE library_id IN (12, 34)
     * $query->filterByLibraryId(array('min' => 12)); // WHERE library_id > 12
     * </code>
     *
     * @param     mixed $libraryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterByLibraryId($libraryId = null, $comparison = null)
    {
        if (is_array($libraryId)) {
            $useMinMax = false;
            if (isset($libraryId['min'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_LIBRARY_ID, $libraryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($libraryId['max'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_LIBRARY_ID, $libraryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubscriptionTableMap::COL_LIBRARY_ID, $libraryId, $comparison);
    }

    /**
     * Filter the query on the subscription_type column
     *
     * Example usage:
     * <code>
     * $query->filterByType('fooValue');   // WHERE subscription_type = 'fooValue'
     * $query->filterByType('%fooValue%', Criteria::LIKE); // WHERE subscription_type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $type The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($type)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_TYPE, $type, $comparison);
    }

    /**
     * Filter the query on the subscription_email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE subscription_email = 'fooValue'
     * $query->filterByEmail('%fooValue%', Criteria::LIKE); // WHERE subscription_email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the subscription_ends column
     *
     * Example usage:
     * <code>
     * $query->filterByEnds(1234); // WHERE subscription_ends = 1234
     * $query->filterByEnds(array(12, 34)); // WHERE subscription_ends IN (12, 34)
     * $query->filterByEnds(array('min' => 12)); // WHERE subscription_ends > 12
     * </code>
     *
     * @param     mixed $ends The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterByEnds($ends = null, $comparison = null)
    {
        if (is_array($ends)) {
            $useMinMax = false;
            if (isset($ends['min'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_ENDS, $ends['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ends['max'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_ENDS, $ends['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_ENDS, $ends, $comparison);
    }

    /**
     * Filter the query on the subscription_option column
     *
     * Example usage:
     * <code>
     * $query->filterByOption(true); // WHERE subscription_option = true
     * $query->filterByOption('yes'); // WHERE subscription_option = true
     * </code>
     *
     * @param     boolean|string $option The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterByOption($option = null, $comparison = null)
    {
        if (is_string($option)) {
            $option = in_array(strtolower($option), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_OPTION, $option, $comparison);
    }

    /**
     * Filter the query on the subscription_insert column
     *
     * Example usage:
     * <code>
     * $query->filterByInsert('2011-03-14'); // WHERE subscription_insert = '2011-03-14'
     * $query->filterByInsert('now'); // WHERE subscription_insert = '2011-03-14'
     * $query->filterByInsert(array('max' => 'yesterday')); // WHERE subscription_insert > '2011-03-13'
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
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterByInsert($insert = null, $comparison = null)
    {
        if (is_array($insert)) {
            $useMinMax = false;
            if (isset($insert['min'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_INSERT, $insert['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($insert['max'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_INSERT, $insert['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_INSERT, $insert, $comparison);
    }

    /**
     * Filter the query on the subscription_update column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdate('2011-03-14'); // WHERE subscription_update = '2011-03-14'
     * $query->filterByUpdate('now'); // WHERE subscription_update = '2011-03-14'
     * $query->filterByUpdate(array('max' => 'yesterday')); // WHERE subscription_update > '2011-03-13'
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
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterByUpdate($update = null, $comparison = null)
    {
        if (is_array($update)) {
            $useMinMax = false;
            if (isset($update['min'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_UPDATE, $update['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($update['max'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_UPDATE, $update['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_UPDATE, $update, $comparison);
    }

    /**
     * Filter the query on the subscription_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE subscription_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE subscription_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE subscription_created > '2011-03-13'
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
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_CREATED, $createdAt, $comparison);
    }

    /**
     * Filter the query on the subscription_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE subscription_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE subscription_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE subscription_updated > '2011-03-13'
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
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the subscription_deleted column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletedAt('2011-03-14'); // WHERE subscription_deleted = '2011-03-14'
     * $query->filterByDeletedAt('now'); // WHERE subscription_deleted = '2011-03-14'
     * $query->filterByDeletedAt(array('max' => 'yesterday')); // WHERE subscription_deleted > '2011-03-13'
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
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterByDeletedAt($deletedAt = null, $comparison = null)
    {
        if (is_array($deletedAt)) {
            $useMinMax = false;
            if (isset($deletedAt['min'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_DELETED, $deletedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletedAt['max'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_DELETED, $deletedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_DELETED, $deletedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSubscription $subscription Object to remove from the list of results
     *
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function prune($subscription = null)
    {
        if ($subscription) {
            $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_ID, $subscription->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the subscriptions table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SubscriptionTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SubscriptionTableMap::clearInstancePool();
            SubscriptionTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(SubscriptionTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SubscriptionTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SubscriptionTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SubscriptionTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // SubscriptionQuery
