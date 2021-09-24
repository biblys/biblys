<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Wishlist as ChildWishlist;
use Model\WishlistQuery as ChildWishlistQuery;
use Model\Map\WishlistTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'wishlist' table.
 *
 *
 *
 * @method     ChildWishlistQuery orderById($order = Criteria::ASC) Order by the wishlist_id column
 * @method     ChildWishlistQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildWishlistQuery orderByName($order = Criteria::ASC) Order by the wishlist_name column
 * @method     ChildWishlistQuery orderByCurrent($order = Criteria::ASC) Order by the wishlist_current column
 * @method     ChildWishlistQuery orderByPublic($order = Criteria::ASC) Order by the wishlist_public column
 * @method     ChildWishlistQuery orderByCreatedAt($order = Criteria::ASC) Order by the wishlist_created column
 * @method     ChildWishlistQuery orderByUpdatedAt($order = Criteria::ASC) Order by the wishlist_updated column
 * @method     ChildWishlistQuery orderByDeletedAt($order = Criteria::ASC) Order by the wishlist_deleted column
 *
 * @method     ChildWishlistQuery groupById() Group by the wishlist_id column
 * @method     ChildWishlistQuery groupByUserId() Group by the user_id column
 * @method     ChildWishlistQuery groupByName() Group by the wishlist_name column
 * @method     ChildWishlistQuery groupByCurrent() Group by the wishlist_current column
 * @method     ChildWishlistQuery groupByPublic() Group by the wishlist_public column
 * @method     ChildWishlistQuery groupByCreatedAt() Group by the wishlist_created column
 * @method     ChildWishlistQuery groupByUpdatedAt() Group by the wishlist_updated column
 * @method     ChildWishlistQuery groupByDeletedAt() Group by the wishlist_deleted column
 *
 * @method     ChildWishlistQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildWishlistQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildWishlistQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildWishlistQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildWishlistQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildWishlistQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildWishlist|null findOne(ConnectionInterface $con = null) Return the first ChildWishlist matching the query
 * @method     ChildWishlist findOneOrCreate(ConnectionInterface $con = null) Return the first ChildWishlist matching the query, or a new ChildWishlist object populated from the query conditions when no match is found
 *
 * @method     ChildWishlist|null findOneById(int $wishlist_id) Return the first ChildWishlist filtered by the wishlist_id column
 * @method     ChildWishlist|null findOneByUserId(int $user_id) Return the first ChildWishlist filtered by the user_id column
 * @method     ChildWishlist|null findOneByName(string $wishlist_name) Return the first ChildWishlist filtered by the wishlist_name column
 * @method     ChildWishlist|null findOneByCurrent(boolean $wishlist_current) Return the first ChildWishlist filtered by the wishlist_current column
 * @method     ChildWishlist|null findOneByPublic(boolean $wishlist_public) Return the first ChildWishlist filtered by the wishlist_public column
 * @method     ChildWishlist|null findOneByCreatedAt(string $wishlist_created) Return the first ChildWishlist filtered by the wishlist_created column
 * @method     ChildWishlist|null findOneByUpdatedAt(string $wishlist_updated) Return the first ChildWishlist filtered by the wishlist_updated column
 * @method     ChildWishlist|null findOneByDeletedAt(string $wishlist_deleted) Return the first ChildWishlist filtered by the wishlist_deleted column *

 * @method     ChildWishlist requirePk($key, ConnectionInterface $con = null) Return the ChildWishlist by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWishlist requireOne(ConnectionInterface $con = null) Return the first ChildWishlist matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildWishlist requireOneById(int $wishlist_id) Return the first ChildWishlist filtered by the wishlist_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWishlist requireOneByUserId(int $user_id) Return the first ChildWishlist filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWishlist requireOneByName(string $wishlist_name) Return the first ChildWishlist filtered by the wishlist_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWishlist requireOneByCurrent(boolean $wishlist_current) Return the first ChildWishlist filtered by the wishlist_current column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWishlist requireOneByPublic(boolean $wishlist_public) Return the first ChildWishlist filtered by the wishlist_public column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWishlist requireOneByCreatedAt(string $wishlist_created) Return the first ChildWishlist filtered by the wishlist_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWishlist requireOneByUpdatedAt(string $wishlist_updated) Return the first ChildWishlist filtered by the wishlist_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWishlist requireOneByDeletedAt(string $wishlist_deleted) Return the first ChildWishlist filtered by the wishlist_deleted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildWishlist[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildWishlist objects based on current ModelCriteria
 * @psalm-method ObjectCollection&\Traversable<ChildWishlist> find(ConnectionInterface $con = null) Return ChildWishlist objects based on current ModelCriteria
 * @method     ChildWishlist[]|ObjectCollection findById(int $wishlist_id) Return ChildWishlist objects filtered by the wishlist_id column
 * @psalm-method ObjectCollection&\Traversable<ChildWishlist> findById(int $wishlist_id) Return ChildWishlist objects filtered by the wishlist_id column
 * @method     ChildWishlist[]|ObjectCollection findByUserId(int $user_id) Return ChildWishlist objects filtered by the user_id column
 * @psalm-method ObjectCollection&\Traversable<ChildWishlist> findByUserId(int $user_id) Return ChildWishlist objects filtered by the user_id column
 * @method     ChildWishlist[]|ObjectCollection findByName(string $wishlist_name) Return ChildWishlist objects filtered by the wishlist_name column
 * @psalm-method ObjectCollection&\Traversable<ChildWishlist> findByName(string $wishlist_name) Return ChildWishlist objects filtered by the wishlist_name column
 * @method     ChildWishlist[]|ObjectCollection findByCurrent(boolean $wishlist_current) Return ChildWishlist objects filtered by the wishlist_current column
 * @psalm-method ObjectCollection&\Traversable<ChildWishlist> findByCurrent(boolean $wishlist_current) Return ChildWishlist objects filtered by the wishlist_current column
 * @method     ChildWishlist[]|ObjectCollection findByPublic(boolean $wishlist_public) Return ChildWishlist objects filtered by the wishlist_public column
 * @psalm-method ObjectCollection&\Traversable<ChildWishlist> findByPublic(boolean $wishlist_public) Return ChildWishlist objects filtered by the wishlist_public column
 * @method     ChildWishlist[]|ObjectCollection findByCreatedAt(string $wishlist_created) Return ChildWishlist objects filtered by the wishlist_created column
 * @psalm-method ObjectCollection&\Traversable<ChildWishlist> findByCreatedAt(string $wishlist_created) Return ChildWishlist objects filtered by the wishlist_created column
 * @method     ChildWishlist[]|ObjectCollection findByUpdatedAt(string $wishlist_updated) Return ChildWishlist objects filtered by the wishlist_updated column
 * @psalm-method ObjectCollection&\Traversable<ChildWishlist> findByUpdatedAt(string $wishlist_updated) Return ChildWishlist objects filtered by the wishlist_updated column
 * @method     ChildWishlist[]|ObjectCollection findByDeletedAt(string $wishlist_deleted) Return ChildWishlist objects filtered by the wishlist_deleted column
 * @psalm-method ObjectCollection&\Traversable<ChildWishlist> findByDeletedAt(string $wishlist_deleted) Return ChildWishlist objects filtered by the wishlist_deleted column
 * @method     ChildWishlist[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildWishlist> paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class WishlistQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\WishlistQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Wishlist', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildWishlistQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildWishlistQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildWishlistQuery) {
            return $criteria;
        }
        $query = new ChildWishlistQuery();
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
     * @return ChildWishlist|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(WishlistTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = WishlistTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildWishlist A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT wishlist_id, user_id, wishlist_name, wishlist_current, wishlist_public, wishlist_created, wishlist_updated, wishlist_deleted FROM wishlist WHERE wishlist_id = :p0';
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
            /** @var ChildWishlist $obj */
            $obj = new ChildWishlist();
            $obj->hydrate($row);
            WishlistTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildWishlist|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildWishlistQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(WishlistTableMap::COL_WISHLIST_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildWishlistQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(WishlistTableMap::COL_WISHLIST_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the wishlist_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE wishlist_id = 1234
     * $query->filterById(array(12, 34)); // WHERE wishlist_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE wishlist_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWishlistQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(WishlistTableMap::COL_WISHLIST_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(WishlistTableMap::COL_WISHLIST_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WishlistTableMap::COL_WISHLIST_ID, $id, $comparison);
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
     * @return $this|ChildWishlistQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(WishlistTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(WishlistTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WishlistTableMap::COL_USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the wishlist_name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE wishlist_name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE wishlist_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWishlistQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WishlistTableMap::COL_WISHLIST_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the wishlist_current column
     *
     * Example usage:
     * <code>
     * $query->filterByCurrent(true); // WHERE wishlist_current = true
     * $query->filterByCurrent('yes'); // WHERE wishlist_current = true
     * </code>
     *
     * @param     boolean|string $current The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWishlistQuery The current query, for fluid interface
     */
    public function filterByCurrent($current = null, $comparison = null)
    {
        if (is_string($current)) {
            $current = in_array(strtolower($current), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(WishlistTableMap::COL_WISHLIST_CURRENT, $current, $comparison);
    }

    /**
     * Filter the query on the wishlist_public column
     *
     * Example usage:
     * <code>
     * $query->filterByPublic(true); // WHERE wishlist_public = true
     * $query->filterByPublic('yes'); // WHERE wishlist_public = true
     * </code>
     *
     * @param     boolean|string $public The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWishlistQuery The current query, for fluid interface
     */
    public function filterByPublic($public = null, $comparison = null)
    {
        if (is_string($public)) {
            $public = in_array(strtolower($public), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(WishlistTableMap::COL_WISHLIST_PUBLIC, $public, $comparison);
    }

    /**
     * Filter the query on the wishlist_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE wishlist_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE wishlist_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE wishlist_created > '2011-03-13'
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
     * @return $this|ChildWishlistQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(WishlistTableMap::COL_WISHLIST_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(WishlistTableMap::COL_WISHLIST_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WishlistTableMap::COL_WISHLIST_CREATED, $createdAt, $comparison);
    }

    /**
     * Filter the query on the wishlist_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE wishlist_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE wishlist_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE wishlist_updated > '2011-03-13'
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
     * @return $this|ChildWishlistQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(WishlistTableMap::COL_WISHLIST_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(WishlistTableMap::COL_WISHLIST_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WishlistTableMap::COL_WISHLIST_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the wishlist_deleted column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletedAt('2011-03-14'); // WHERE wishlist_deleted = '2011-03-14'
     * $query->filterByDeletedAt('now'); // WHERE wishlist_deleted = '2011-03-14'
     * $query->filterByDeletedAt(array('max' => 'yesterday')); // WHERE wishlist_deleted > '2011-03-13'
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
     * @return $this|ChildWishlistQuery The current query, for fluid interface
     */
    public function filterByDeletedAt($deletedAt = null, $comparison = null)
    {
        if (is_array($deletedAt)) {
            $useMinMax = false;
            if (isset($deletedAt['min'])) {
                $this->addUsingAlias(WishlistTableMap::COL_WISHLIST_DELETED, $deletedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletedAt['max'])) {
                $this->addUsingAlias(WishlistTableMap::COL_WISHLIST_DELETED, $deletedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WishlistTableMap::COL_WISHLIST_DELETED, $deletedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildWishlist $wishlist Object to remove from the list of results
     *
     * @return $this|ChildWishlistQuery The current query, for fluid interface
     */
    public function prune($wishlist = null)
    {
        if ($wishlist) {
            $this->addUsingAlias(WishlistTableMap::COL_WISHLIST_ID, $wishlist->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the wishlist table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(WishlistTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            WishlistTableMap::clearInstancePool();
            WishlistTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(WishlistTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(WishlistTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            WishlistTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            WishlistTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildWishlistQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(WishlistTableMap::COL_WISHLIST_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildWishlistQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(WishlistTableMap::COL_WISHLIST_UPDATED);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildWishlistQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(WishlistTableMap::COL_WISHLIST_UPDATED);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildWishlistQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(WishlistTableMap::COL_WISHLIST_CREATED);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildWishlistQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(WishlistTableMap::COL_WISHLIST_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildWishlistQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(WishlistTableMap::COL_WISHLIST_CREATED);
    }

} // WishlistQuery
