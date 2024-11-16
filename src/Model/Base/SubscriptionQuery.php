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
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `subscriptions` table.
 *
 * @method     ChildSubscriptionQuery orderById($order = Criteria::ASC) Order by the subscription_id column
 * @method     ChildSubscriptionQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildSubscriptionQuery orderByAxysAccountId($order = Criteria::ASC) Order by the axys_account_id column
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
 *
 * @method     ChildSubscriptionQuery groupById() Group by the subscription_id column
 * @method     ChildSubscriptionQuery groupBySiteId() Group by the site_id column
 * @method     ChildSubscriptionQuery groupByAxysAccountId() Group by the axys_account_id column
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
 *
 * @method     ChildSubscriptionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSubscriptionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSubscriptionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSubscriptionQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildSubscriptionQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildSubscriptionQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildSubscriptionQuery leftJoinSite($relationAlias = null) Adds a LEFT JOIN clause to the query using the Site relation
 * @method     ChildSubscriptionQuery rightJoinSite($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Site relation
 * @method     ChildSubscriptionQuery innerJoinSite($relationAlias = null) Adds a INNER JOIN clause to the query using the Site relation
 *
 * @method     ChildSubscriptionQuery joinWithSite($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Site relation
 *
 * @method     ChildSubscriptionQuery leftJoinWithSite() Adds a LEFT JOIN clause and with to the query using the Site relation
 * @method     ChildSubscriptionQuery rightJoinWithSite() Adds a RIGHT JOIN clause and with to the query using the Site relation
 * @method     ChildSubscriptionQuery innerJoinWithSite() Adds a INNER JOIN clause and with to the query using the Site relation
 *
 * @method     ChildSubscriptionQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildSubscriptionQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildSubscriptionQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildSubscriptionQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildSubscriptionQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildSubscriptionQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildSubscriptionQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     \Model\SiteQuery|\Model\UserQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildSubscription|null findOne(?ConnectionInterface $con = null) Return the first ChildSubscription matching the query
 * @method     ChildSubscription findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildSubscription matching the query, or a new ChildSubscription object populated from the query conditions when no match is found
 *
 * @method     ChildSubscription|null findOneById(int $subscription_id) Return the first ChildSubscription filtered by the subscription_id column
 * @method     ChildSubscription|null findOneBySiteId(int $site_id) Return the first ChildSubscription filtered by the site_id column
 * @method     ChildSubscription|null findOneByAxysAccountId(int $axys_account_id) Return the first ChildSubscription filtered by the axys_account_id column
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
 *
 * @method     ChildSubscription requirePk($key, ?ConnectionInterface $con = null) Return the ChildSubscription by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubscription requireOne(?ConnectionInterface $con = null) Return the first ChildSubscription matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSubscription requireOneById(int $subscription_id) Return the first ChildSubscription filtered by the subscription_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubscription requireOneBySiteId(int $site_id) Return the first ChildSubscription filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubscription requireOneByAxysAccountId(int $axys_account_id) Return the first ChildSubscription filtered by the axys_account_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
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
 *
 * @method     ChildSubscription[]|Collection find(?ConnectionInterface $con = null) Return ChildSubscription objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildSubscription> find(?ConnectionInterface $con = null) Return ChildSubscription objects based on current ModelCriteria
 *
 * @method     ChildSubscription[]|Collection findById(int|array<int> $subscription_id) Return ChildSubscription objects filtered by the subscription_id column
 * @psalm-method Collection&\Traversable<ChildSubscription> findById(int|array<int> $subscription_id) Return ChildSubscription objects filtered by the subscription_id column
 * @method     ChildSubscription[]|Collection findBySiteId(int|array<int> $site_id) Return ChildSubscription objects filtered by the site_id column
 * @psalm-method Collection&\Traversable<ChildSubscription> findBySiteId(int|array<int> $site_id) Return ChildSubscription objects filtered by the site_id column
 * @method     ChildSubscription[]|Collection findByAxysAccountId(int|array<int> $axys_account_id) Return ChildSubscription objects filtered by the axys_account_id column
 * @psalm-method Collection&\Traversable<ChildSubscription> findByAxysAccountId(int|array<int> $axys_account_id) Return ChildSubscription objects filtered by the axys_account_id column
 * @method     ChildSubscription[]|Collection findByUserId(int|array<int> $user_id) Return ChildSubscription objects filtered by the user_id column
 * @psalm-method Collection&\Traversable<ChildSubscription> findByUserId(int|array<int> $user_id) Return ChildSubscription objects filtered by the user_id column
 * @method     ChildSubscription[]|Collection findByPublisherId(int|array<int> $publisher_id) Return ChildSubscription objects filtered by the publisher_id column
 * @psalm-method Collection&\Traversable<ChildSubscription> findByPublisherId(int|array<int> $publisher_id) Return ChildSubscription objects filtered by the publisher_id column
 * @method     ChildSubscription[]|Collection findByBookshopId(int|array<int> $bookshop_id) Return ChildSubscription objects filtered by the bookshop_id column
 * @psalm-method Collection&\Traversable<ChildSubscription> findByBookshopId(int|array<int> $bookshop_id) Return ChildSubscription objects filtered by the bookshop_id column
 * @method     ChildSubscription[]|Collection findByLibraryId(int|array<int> $library_id) Return ChildSubscription objects filtered by the library_id column
 * @psalm-method Collection&\Traversable<ChildSubscription> findByLibraryId(int|array<int> $library_id) Return ChildSubscription objects filtered by the library_id column
 * @method     ChildSubscription[]|Collection findByType(string|array<string> $subscription_type) Return ChildSubscription objects filtered by the subscription_type column
 * @psalm-method Collection&\Traversable<ChildSubscription> findByType(string|array<string> $subscription_type) Return ChildSubscription objects filtered by the subscription_type column
 * @method     ChildSubscription[]|Collection findByEmail(string|array<string> $subscription_email) Return ChildSubscription objects filtered by the subscription_email column
 * @psalm-method Collection&\Traversable<ChildSubscription> findByEmail(string|array<string> $subscription_email) Return ChildSubscription objects filtered by the subscription_email column
 * @method     ChildSubscription[]|Collection findByEnds(int|array<int> $subscription_ends) Return ChildSubscription objects filtered by the subscription_ends column
 * @psalm-method Collection&\Traversable<ChildSubscription> findByEnds(int|array<int> $subscription_ends) Return ChildSubscription objects filtered by the subscription_ends column
 * @method     ChildSubscription[]|Collection findByOption(boolean|array<boolean> $subscription_option) Return ChildSubscription objects filtered by the subscription_option column
 * @psalm-method Collection&\Traversable<ChildSubscription> findByOption(boolean|array<boolean> $subscription_option) Return ChildSubscription objects filtered by the subscription_option column
 * @method     ChildSubscription[]|Collection findByInsert(string|array<string> $subscription_insert) Return ChildSubscription objects filtered by the subscription_insert column
 * @psalm-method Collection&\Traversable<ChildSubscription> findByInsert(string|array<string> $subscription_insert) Return ChildSubscription objects filtered by the subscription_insert column
 * @method     ChildSubscription[]|Collection findByUpdate(string|array<string> $subscription_update) Return ChildSubscription objects filtered by the subscription_update column
 * @psalm-method Collection&\Traversable<ChildSubscription> findByUpdate(string|array<string> $subscription_update) Return ChildSubscription objects filtered by the subscription_update column
 * @method     ChildSubscription[]|Collection findByCreatedAt(string|array<string> $subscription_created) Return ChildSubscription objects filtered by the subscription_created column
 * @psalm-method Collection&\Traversable<ChildSubscription> findByCreatedAt(string|array<string> $subscription_created) Return ChildSubscription objects filtered by the subscription_created column
 * @method     ChildSubscription[]|Collection findByUpdatedAt(string|array<string> $subscription_updated) Return ChildSubscription objects filtered by the subscription_updated column
 * @psalm-method Collection&\Traversable<ChildSubscription> findByUpdatedAt(string|array<string> $subscription_updated) Return ChildSubscription objects filtered by the subscription_updated column
 *
 * @method     ChildSubscription[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildSubscription> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class SubscriptionQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\SubscriptionQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Subscription', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSubscriptionQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSubscriptionQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
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
    public function findPk($key, ?ConnectionInterface $con = null)
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
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSubscription A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT subscription_id, site_id, axys_account_id, user_id, publisher_id, bookshop_id, library_id, subscription_type, subscription_email, subscription_ends, subscription_option, subscription_insert, subscription_update, subscription_created, subscription_updated FROM subscriptions WHERE subscription_id = :p0';
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
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
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

        $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_ID, $keys, Criteria::IN);

        return $this;
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

        $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_ID, $id, $comparison);

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
     * @see       filterBySite()
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

        $this->addUsingAlias(SubscriptionTableMap::COL_SITE_ID, $siteId, $comparison);

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
                $this->addUsingAlias(SubscriptionTableMap::COL_AXYS_ACCOUNT_ID, $axysAccountId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($axysAccountId['max'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_AXYS_ACCOUNT_ID, $axysAccountId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SubscriptionTableMap::COL_AXYS_ACCOUNT_ID, $axysAccountId, $comparison);

        return $this;
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
     * @see       filterByUser()
     *
     * @param mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUserId($userId = null, ?string $comparison = null)
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

        $this->addUsingAlias(SubscriptionTableMap::COL_USER_ID, $userId, $comparison);

        return $this;
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
     * @param mixed $publisherId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPublisherId($publisherId = null, ?string $comparison = null)
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

        $this->addUsingAlias(SubscriptionTableMap::COL_PUBLISHER_ID, $publisherId, $comparison);

        return $this;
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
     * @param mixed $bookshopId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByBookshopId($bookshopId = null, ?string $comparison = null)
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

        $this->addUsingAlias(SubscriptionTableMap::COL_BOOKSHOP_ID, $bookshopId, $comparison);

        return $this;
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
     * @param mixed $libraryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByLibraryId($libraryId = null, ?string $comparison = null)
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

        $this->addUsingAlias(SubscriptionTableMap::COL_LIBRARY_ID, $libraryId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the subscription_type column
     *
     * Example usage:
     * <code>
     * $query->filterByType('fooValue');   // WHERE subscription_type = 'fooValue'
     * $query->filterByType('%fooValue%', Criteria::LIKE); // WHERE subscription_type LIKE '%fooValue%'
     * $query->filterByType(['foo', 'bar']); // WHERE subscription_type IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $type The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByType($type = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($type)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_TYPE, $type, $comparison);

        return $this;
    }

    /**
     * Filter the query on the subscription_email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE subscription_email = 'fooValue'
     * $query->filterByEmail('%fooValue%', Criteria::LIKE); // WHERE subscription_email LIKE '%fooValue%'
     * $query->filterByEmail(['foo', 'bar']); // WHERE subscription_email IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $email The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEmail($email = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_EMAIL, $email, $comparison);

        return $this;
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
     * @param mixed $ends The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEnds($ends = null, ?string $comparison = null)
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

        $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_ENDS, $ends, $comparison);

        return $this;
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
     * @param bool|string $option The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByOption($option = null, ?string $comparison = null)
    {
        if (is_string($option)) {
            $option = in_array(strtolower($option), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_OPTION, $option, $comparison);

        return $this;
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

        $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_INSERT, $insert, $comparison);

        return $this;
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

        $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_UPDATE, $update, $comparison);

        return $this;
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

        $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_CREATED, $createdAt, $comparison);

        return $this;
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

        $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_UPDATED, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \Model\Site object
     *
     * @param \Model\Site|ObjectCollection $site The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySite($site, ?string $comparison = null)
    {
        if ($site instanceof \Model\Site) {
            return $this
                ->addUsingAlias(SubscriptionTableMap::COL_SITE_ID, $site->getId(), $comparison);
        } elseif ($site instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(SubscriptionTableMap::COL_SITE_ID, $site->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterBySite() only accepts arguments of type \Model\Site or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Site relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinSite(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Site');

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
            $this->addJoinObject($join, 'Site');
        }

        return $this;
    }

    /**
     * Use the Site relation Site object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\SiteQuery A secondary query class using the current class as primary query
     */
    public function useSiteQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSite($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Site', '\Model\SiteQuery');
    }

    /**
     * Use the Site relation Site object
     *
     * @param callable(\Model\SiteQuery):\Model\SiteQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withSiteQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useSiteQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Site table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\SiteQuery The inner query object of the EXISTS statement
     */
    public function useSiteExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\SiteQuery */
        $q = $this->useExistsQuery('Site', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Site table for a NOT EXISTS query.
     *
     * @see useSiteExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\SiteQuery The inner query object of the NOT EXISTS statement
     */
    public function useSiteNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\SiteQuery */
        $q = $this->useExistsQuery('Site', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Site table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\SiteQuery The inner query object of the IN statement
     */
    public function useInSiteQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\SiteQuery */
        $q = $this->useInQuery('Site', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Site table for a NOT IN query.
     *
     * @see useSiteInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\SiteQuery The inner query object of the NOT IN statement
     */
    public function useNotInSiteQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\SiteQuery */
        $q = $this->useInQuery('Site', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\User object
     *
     * @param \Model\User|ObjectCollection $user The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUser($user, ?string $comparison = null)
    {
        if ($user instanceof \Model\User) {
            return $this
                ->addUsingAlias(SubscriptionTableMap::COL_USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(SubscriptionTableMap::COL_USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \Model\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinUser(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

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
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\Model\UserQuery');
    }

    /**
     * Use the User relation User object
     *
     * @param callable(\Model\UserQuery):\Model\UserQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withUserQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useUserQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to User table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\UserQuery The inner query object of the EXISTS statement
     */
    public function useUserExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\UserQuery */
        $q = $this->useExistsQuery('User', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to User table for a NOT EXISTS query.
     *
     * @see useUserExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\UserQuery The inner query object of the NOT EXISTS statement
     */
    public function useUserNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\UserQuery */
        $q = $this->useExistsQuery('User', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to User table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\UserQuery The inner query object of the IN statement
     */
    public function useInUserQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\UserQuery */
        $q = $this->useInQuery('User', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to User table for a NOT IN query.
     *
     * @see useUserInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\UserQuery The inner query object of the NOT IN statement
     */
    public function useNotInUserQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\UserQuery */
        $q = $this->useInQuery('User', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Exclude object from result
     *
     * @param ChildSubscription $subscription Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
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
    public function doDeleteAll(?ConnectionInterface $con = null): int
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
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(?ConnectionInterface $con = null): int
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
        $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(SubscriptionTableMap::COL_SUBSCRIPTION_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(SubscriptionTableMap::COL_SUBSCRIPTION_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(SubscriptionTableMap::COL_SUBSCRIPTION_CREATED);

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
        $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(SubscriptionTableMap::COL_SUBSCRIPTION_CREATED);

        return $this;
    }

}
