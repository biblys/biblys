<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Right as ChildRight;
use Model\RightQuery as ChildRightQuery;
use Model\Map\RightTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `rights` table.
 *
 * @method     ChildRightQuery orderById($order = Criteria::ASC) Order by the right_id column
 * @method     ChildRightQuery orderByUid($order = Criteria::ASC) Order by the right_uid column
 * @method     ChildRightQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildRightQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildRightQuery orderByPublisherId($order = Criteria::ASC) Order by the publisher_id column
 * @method     ChildRightQuery orderByBookshopId($order = Criteria::ASC) Order by the bookshop_id column
 * @method     ChildRightQuery orderByLibraryId($order = Criteria::ASC) Order by the library_id column
 * @method     ChildRightQuery orderByCurrent($order = Criteria::ASC) Order by the right_current column
 * @method     ChildRightQuery orderByCreatedAt($order = Criteria::ASC) Order by the right_created column
 * @method     ChildRightQuery orderByUpdatedAt($order = Criteria::ASC) Order by the right_updated column
 *
 * @method     ChildRightQuery groupById() Group by the right_id column
 * @method     ChildRightQuery groupByUid() Group by the right_uid column
 * @method     ChildRightQuery groupByUserId() Group by the user_id column
 * @method     ChildRightQuery groupBySiteId() Group by the site_id column
 * @method     ChildRightQuery groupByPublisherId() Group by the publisher_id column
 * @method     ChildRightQuery groupByBookshopId() Group by the bookshop_id column
 * @method     ChildRightQuery groupByLibraryId() Group by the library_id column
 * @method     ChildRightQuery groupByCurrent() Group by the right_current column
 * @method     ChildRightQuery groupByCreatedAt() Group by the right_created column
 * @method     ChildRightQuery groupByUpdatedAt() Group by the right_updated column
 *
 * @method     ChildRightQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildRightQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildRightQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildRightQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildRightQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildRightQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildRightQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildRightQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildRightQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildRightQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildRightQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildRightQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildRightQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     ChildRightQuery leftJoinSite($relationAlias = null) Adds a LEFT JOIN clause to the query using the Site relation
 * @method     ChildRightQuery rightJoinSite($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Site relation
 * @method     ChildRightQuery innerJoinSite($relationAlias = null) Adds a INNER JOIN clause to the query using the Site relation
 *
 * @method     ChildRightQuery joinWithSite($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Site relation
 *
 * @method     ChildRightQuery leftJoinWithSite() Adds a LEFT JOIN clause and with to the query using the Site relation
 * @method     ChildRightQuery rightJoinWithSite() Adds a RIGHT JOIN clause and with to the query using the Site relation
 * @method     ChildRightQuery innerJoinWithSite() Adds a INNER JOIN clause and with to the query using the Site relation
 *
 * @method     ChildRightQuery leftJoinPublisher($relationAlias = null) Adds a LEFT JOIN clause to the query using the Publisher relation
 * @method     ChildRightQuery rightJoinPublisher($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Publisher relation
 * @method     ChildRightQuery innerJoinPublisher($relationAlias = null) Adds a INNER JOIN clause to the query using the Publisher relation
 *
 * @method     ChildRightQuery joinWithPublisher($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Publisher relation
 *
 * @method     ChildRightQuery leftJoinWithPublisher() Adds a LEFT JOIN clause and with to the query using the Publisher relation
 * @method     ChildRightQuery rightJoinWithPublisher() Adds a RIGHT JOIN clause and with to the query using the Publisher relation
 * @method     ChildRightQuery innerJoinWithPublisher() Adds a INNER JOIN clause and with to the query using the Publisher relation
 *
 * @method     \Model\UserQuery|\Model\SiteQuery|\Model\PublisherQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildRight|null findOne(?ConnectionInterface $con = null) Return the first ChildRight matching the query
 * @method     ChildRight findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildRight matching the query, or a new ChildRight object populated from the query conditions when no match is found
 *
 * @method     ChildRight|null findOneById(int $right_id) Return the first ChildRight filtered by the right_id column
 * @method     ChildRight|null findOneByUid(string $right_uid) Return the first ChildRight filtered by the right_uid column
 * @method     ChildRight|null findOneByUserId(int $user_id) Return the first ChildRight filtered by the user_id column
 * @method     ChildRight|null findOneBySiteId(int $site_id) Return the first ChildRight filtered by the site_id column
 * @method     ChildRight|null findOneByPublisherId(int $publisher_id) Return the first ChildRight filtered by the publisher_id column
 * @method     ChildRight|null findOneByBookshopId(int $bookshop_id) Return the first ChildRight filtered by the bookshop_id column
 * @method     ChildRight|null findOneByLibraryId(int $library_id) Return the first ChildRight filtered by the library_id column
 * @method     ChildRight|null findOneByCurrent(boolean $right_current) Return the first ChildRight filtered by the right_current column
 * @method     ChildRight|null findOneByCreatedAt(string $right_created) Return the first ChildRight filtered by the right_created column
 * @method     ChildRight|null findOneByUpdatedAt(string $right_updated) Return the first ChildRight filtered by the right_updated column
 *
 * @method     ChildRight requirePk($key, ?ConnectionInterface $con = null) Return the ChildRight by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRight requireOne(?ConnectionInterface $con = null) Return the first ChildRight matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildRight requireOneById(int $right_id) Return the first ChildRight filtered by the right_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRight requireOneByUid(string $right_uid) Return the first ChildRight filtered by the right_uid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRight requireOneByUserId(int $user_id) Return the first ChildRight filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRight requireOneBySiteId(int $site_id) Return the first ChildRight filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRight requireOneByPublisherId(int $publisher_id) Return the first ChildRight filtered by the publisher_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRight requireOneByBookshopId(int $bookshop_id) Return the first ChildRight filtered by the bookshop_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRight requireOneByLibraryId(int $library_id) Return the first ChildRight filtered by the library_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRight requireOneByCurrent(boolean $right_current) Return the first ChildRight filtered by the right_current column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRight requireOneByCreatedAt(string $right_created) Return the first ChildRight filtered by the right_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRight requireOneByUpdatedAt(string $right_updated) Return the first ChildRight filtered by the right_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildRight[]|Collection find(?ConnectionInterface $con = null) Return ChildRight objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildRight> find(?ConnectionInterface $con = null) Return ChildRight objects based on current ModelCriteria
 *
 * @method     ChildRight[]|Collection findById(int|array<int> $right_id) Return ChildRight objects filtered by the right_id column
 * @psalm-method Collection&\Traversable<ChildRight> findById(int|array<int> $right_id) Return ChildRight objects filtered by the right_id column
 * @method     ChildRight[]|Collection findByUid(string|array<string> $right_uid) Return ChildRight objects filtered by the right_uid column
 * @psalm-method Collection&\Traversable<ChildRight> findByUid(string|array<string> $right_uid) Return ChildRight objects filtered by the right_uid column
 * @method     ChildRight[]|Collection findByUserId(int|array<int> $user_id) Return ChildRight objects filtered by the user_id column
 * @psalm-method Collection&\Traversable<ChildRight> findByUserId(int|array<int> $user_id) Return ChildRight objects filtered by the user_id column
 * @method     ChildRight[]|Collection findBySiteId(int|array<int> $site_id) Return ChildRight objects filtered by the site_id column
 * @psalm-method Collection&\Traversable<ChildRight> findBySiteId(int|array<int> $site_id) Return ChildRight objects filtered by the site_id column
 * @method     ChildRight[]|Collection findByPublisherId(int|array<int> $publisher_id) Return ChildRight objects filtered by the publisher_id column
 * @psalm-method Collection&\Traversable<ChildRight> findByPublisherId(int|array<int> $publisher_id) Return ChildRight objects filtered by the publisher_id column
 * @method     ChildRight[]|Collection findByBookshopId(int|array<int> $bookshop_id) Return ChildRight objects filtered by the bookshop_id column
 * @psalm-method Collection&\Traversable<ChildRight> findByBookshopId(int|array<int> $bookshop_id) Return ChildRight objects filtered by the bookshop_id column
 * @method     ChildRight[]|Collection findByLibraryId(int|array<int> $library_id) Return ChildRight objects filtered by the library_id column
 * @psalm-method Collection&\Traversable<ChildRight> findByLibraryId(int|array<int> $library_id) Return ChildRight objects filtered by the library_id column
 * @method     ChildRight[]|Collection findByCurrent(boolean|array<boolean> $right_current) Return ChildRight objects filtered by the right_current column
 * @psalm-method Collection&\Traversable<ChildRight> findByCurrent(boolean|array<boolean> $right_current) Return ChildRight objects filtered by the right_current column
 * @method     ChildRight[]|Collection findByCreatedAt(string|array<string> $right_created) Return ChildRight objects filtered by the right_created column
 * @psalm-method Collection&\Traversable<ChildRight> findByCreatedAt(string|array<string> $right_created) Return ChildRight objects filtered by the right_created column
 * @method     ChildRight[]|Collection findByUpdatedAt(string|array<string> $right_updated) Return ChildRight objects filtered by the right_updated column
 * @psalm-method Collection&\Traversable<ChildRight> findByUpdatedAt(string|array<string> $right_updated) Return ChildRight objects filtered by the right_updated column
 *
 * @method     ChildRight[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildRight> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class RightQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\RightQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Right', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildRightQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildRightQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildRightQuery) {
            return $criteria;
        }
        $query = new ChildRightQuery();
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
     * @return ChildRight|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(RightTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = RightTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildRight A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT right_id, right_uid, user_id, site_id, publisher_id, bookshop_id, library_id, right_current, right_created, right_updated FROM rights WHERE right_id = :p0';
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
            /** @var ChildRight $obj */
            $obj = new ChildRight();
            $obj->hydrate($row);
            RightTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildRight|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(RightTableMap::COL_RIGHT_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(RightTableMap::COL_RIGHT_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the right_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE right_id = 1234
     * $query->filterById(array(12, 34)); // WHERE right_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE right_id > 12
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
                $this->addUsingAlias(RightTableMap::COL_RIGHT_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(RightTableMap::COL_RIGHT_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(RightTableMap::COL_RIGHT_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the right_uid column
     *
     * Example usage:
     * <code>
     * $query->filterByUid('fooValue');   // WHERE right_uid = 'fooValue'
     * $query->filterByUid('%fooValue%', Criteria::LIKE); // WHERE right_uid LIKE '%fooValue%'
     * $query->filterByUid(['foo', 'bar']); // WHERE right_uid IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $uid The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUid($uid = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($uid)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(RightTableMap::COL_RIGHT_UID, $uid, $comparison);

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
                $this->addUsingAlias(RightTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(RightTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(RightTableMap::COL_USER_ID, $userId, $comparison);

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
                $this->addUsingAlias(RightTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(RightTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(RightTableMap::COL_SITE_ID, $siteId, $comparison);

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
     * @see       filterByPublisher()
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
                $this->addUsingAlias(RightTableMap::COL_PUBLISHER_ID, $publisherId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publisherId['max'])) {
                $this->addUsingAlias(RightTableMap::COL_PUBLISHER_ID, $publisherId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(RightTableMap::COL_PUBLISHER_ID, $publisherId, $comparison);

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
                $this->addUsingAlias(RightTableMap::COL_BOOKSHOP_ID, $bookshopId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bookshopId['max'])) {
                $this->addUsingAlias(RightTableMap::COL_BOOKSHOP_ID, $bookshopId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(RightTableMap::COL_BOOKSHOP_ID, $bookshopId, $comparison);

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
                $this->addUsingAlias(RightTableMap::COL_LIBRARY_ID, $libraryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($libraryId['max'])) {
                $this->addUsingAlias(RightTableMap::COL_LIBRARY_ID, $libraryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(RightTableMap::COL_LIBRARY_ID, $libraryId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the right_current column
     *
     * Example usage:
     * <code>
     * $query->filterByCurrent(true); // WHERE right_current = true
     * $query->filterByCurrent('yes'); // WHERE right_current = true
     * </code>
     *
     * @param bool|string $current The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCurrent($current = null, ?string $comparison = null)
    {
        if (is_string($current)) {
            $current = in_array(strtolower($current), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(RightTableMap::COL_RIGHT_CURRENT, $current, $comparison);

        return $this;
    }

    /**
     * Filter the query on the right_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE right_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE right_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE right_created > '2011-03-13'
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
                $this->addUsingAlias(RightTableMap::COL_RIGHT_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(RightTableMap::COL_RIGHT_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(RightTableMap::COL_RIGHT_CREATED, $createdAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the right_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE right_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE right_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE right_updated > '2011-03-13'
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
                $this->addUsingAlias(RightTableMap::COL_RIGHT_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(RightTableMap::COL_RIGHT_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(RightTableMap::COL_RIGHT_UPDATED, $updatedAt, $comparison);

        return $this;
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
                ->addUsingAlias(RightTableMap::COL_USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(RightTableMap::COL_USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);

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
                ->addUsingAlias(RightTableMap::COL_SITE_ID, $site->getId(), $comparison);
        } elseif ($site instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(RightTableMap::COL_SITE_ID, $site->toKeyValue('PrimaryKey', 'Id'), $comparison);

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
     * Filter the query by a related \Model\Publisher object
     *
     * @param \Model\Publisher|ObjectCollection $publisher The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPublisher($publisher, ?string $comparison = null)
    {
        if ($publisher instanceof \Model\Publisher) {
            return $this
                ->addUsingAlias(RightTableMap::COL_PUBLISHER_ID, $publisher->getId(), $comparison);
        } elseif ($publisher instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(RightTableMap::COL_PUBLISHER_ID, $publisher->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByPublisher() only accepts arguments of type \Model\Publisher or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Publisher relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinPublisher(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Publisher');

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
            $this->addJoinObject($join, 'Publisher');
        }

        return $this;
    }

    /**
     * Use the Publisher relation Publisher object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\PublisherQuery A secondary query class using the current class as primary query
     */
    public function usePublisherQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPublisher($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Publisher', '\Model\PublisherQuery');
    }

    /**
     * Use the Publisher relation Publisher object
     *
     * @param callable(\Model\PublisherQuery):\Model\PublisherQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withPublisherQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->usePublisherQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Publisher table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\PublisherQuery The inner query object of the EXISTS statement
     */
    public function usePublisherExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\PublisherQuery */
        $q = $this->useExistsQuery('Publisher', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Publisher table for a NOT EXISTS query.
     *
     * @see usePublisherExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\PublisherQuery The inner query object of the NOT EXISTS statement
     */
    public function usePublisherNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\PublisherQuery */
        $q = $this->useExistsQuery('Publisher', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Publisher table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\PublisherQuery The inner query object of the IN statement
     */
    public function useInPublisherQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\PublisherQuery */
        $q = $this->useInQuery('Publisher', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Publisher table for a NOT IN query.
     *
     * @see usePublisherInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\PublisherQuery The inner query object of the NOT IN statement
     */
    public function useNotInPublisherQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\PublisherQuery */
        $q = $this->useInQuery('Publisher', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Exclude object from result
     *
     * @param ChildRight $right Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($right = null)
    {
        if ($right) {
            $this->addUsingAlias(RightTableMap::COL_RIGHT_ID, $right->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the rights table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(RightTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            RightTableMap::clearInstancePool();
            RightTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(RightTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(RightTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            RightTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            RightTableMap::clearRelatedInstancePool();

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
        $this->addUsingAlias(RightTableMap::COL_RIGHT_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(RightTableMap::COL_RIGHT_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(RightTableMap::COL_RIGHT_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(RightTableMap::COL_RIGHT_CREATED);

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
        $this->addUsingAlias(RightTableMap::COL_RIGHT_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(RightTableMap::COL_RIGHT_CREATED);

        return $this;
    }

}
