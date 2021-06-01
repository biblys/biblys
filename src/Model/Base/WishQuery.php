<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Wish as ChildWish;
use Model\WishQuery as ChildWishQuery;
use Model\Map\WishTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'wishes' table.
 *
 *
 *
 * @method     ChildWishQuery orderById($order = Criteria::ASC) Order by the wish_id column
 * @method     ChildWishQuery orderByWishlistId($order = Criteria::ASC) Order by the wishlist_id column
 * @method     ChildWishQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildWishQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildWishQuery orderByArticleId($order = Criteria::ASC) Order by the article_id column
 * @method     ChildWishQuery orderByCreatedAt($order = Criteria::ASC) Order by the wish_created column
 * @method     ChildWishQuery orderByUpdatedAt($order = Criteria::ASC) Order by the wish_updated column
 * @method     ChildWishQuery orderByBought($order = Criteria::ASC) Order by the wish_bought column
 * @method     ChildWishQuery orderByDeletedAt($order = Criteria::ASC) Order by the wish_deleted column
 *
 * @method     ChildWishQuery groupById() Group by the wish_id column
 * @method     ChildWishQuery groupByWishlistId() Group by the wishlist_id column
 * @method     ChildWishQuery groupByUserId() Group by the user_id column
 * @method     ChildWishQuery groupBySiteId() Group by the site_id column
 * @method     ChildWishQuery groupByArticleId() Group by the article_id column
 * @method     ChildWishQuery groupByCreatedAt() Group by the wish_created column
 * @method     ChildWishQuery groupByUpdatedAt() Group by the wish_updated column
 * @method     ChildWishQuery groupByBought() Group by the wish_bought column
 * @method     ChildWishQuery groupByDeletedAt() Group by the wish_deleted column
 *
 * @method     ChildWishQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildWishQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildWishQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildWishQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildWishQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildWishQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildWish|null findOne(ConnectionInterface $con = null) Return the first ChildWish matching the query
 * @method     ChildWish findOneOrCreate(ConnectionInterface $con = null) Return the first ChildWish matching the query, or a new ChildWish object populated from the query conditions when no match is found
 *
 * @method     ChildWish|null findOneById(int $wish_id) Return the first ChildWish filtered by the wish_id column
 * @method     ChildWish|null findOneByWishlistId(int $wishlist_id) Return the first ChildWish filtered by the wishlist_id column
 * @method     ChildWish|null findOneByUserId(int $user_id) Return the first ChildWish filtered by the user_id column
 * @method     ChildWish|null findOneBySiteId(int $site_id) Return the first ChildWish filtered by the site_id column
 * @method     ChildWish|null findOneByArticleId(int $article_id) Return the first ChildWish filtered by the article_id column
 * @method     ChildWish|null findOneByCreatedAt(string $wish_created) Return the first ChildWish filtered by the wish_created column
 * @method     ChildWish|null findOneByUpdatedAt(string $wish_updated) Return the first ChildWish filtered by the wish_updated column
 * @method     ChildWish|null findOneByBought(string $wish_bought) Return the first ChildWish filtered by the wish_bought column
 * @method     ChildWish|null findOneByDeletedAt(string $wish_deleted) Return the first ChildWish filtered by the wish_deleted column *

 * @method     ChildWish requirePk($key, ConnectionInterface $con = null) Return the ChildWish by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWish requireOne(ConnectionInterface $con = null) Return the first ChildWish matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildWish requireOneById(int $wish_id) Return the first ChildWish filtered by the wish_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWish requireOneByWishlistId(int $wishlist_id) Return the first ChildWish filtered by the wishlist_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWish requireOneByUserId(int $user_id) Return the first ChildWish filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWish requireOneBySiteId(int $site_id) Return the first ChildWish filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWish requireOneByArticleId(int $article_id) Return the first ChildWish filtered by the article_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWish requireOneByCreatedAt(string $wish_created) Return the first ChildWish filtered by the wish_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWish requireOneByUpdatedAt(string $wish_updated) Return the first ChildWish filtered by the wish_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWish requireOneByBought(string $wish_bought) Return the first ChildWish filtered by the wish_bought column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWish requireOneByDeletedAt(string $wish_deleted) Return the first ChildWish filtered by the wish_deleted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildWish[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildWish objects based on current ModelCriteria
 * @method     ChildWish[]|ObjectCollection findById(int $wish_id) Return ChildWish objects filtered by the wish_id column
 * @method     ChildWish[]|ObjectCollection findByWishlistId(int $wishlist_id) Return ChildWish objects filtered by the wishlist_id column
 * @method     ChildWish[]|ObjectCollection findByUserId(int $user_id) Return ChildWish objects filtered by the user_id column
 * @method     ChildWish[]|ObjectCollection findBySiteId(int $site_id) Return ChildWish objects filtered by the site_id column
 * @method     ChildWish[]|ObjectCollection findByArticleId(int $article_id) Return ChildWish objects filtered by the article_id column
 * @method     ChildWish[]|ObjectCollection findByCreatedAt(string $wish_created) Return ChildWish objects filtered by the wish_created column
 * @method     ChildWish[]|ObjectCollection findByUpdatedAt(string $wish_updated) Return ChildWish objects filtered by the wish_updated column
 * @method     ChildWish[]|ObjectCollection findByBought(string $wish_bought) Return ChildWish objects filtered by the wish_bought column
 * @method     ChildWish[]|ObjectCollection findByDeletedAt(string $wish_deleted) Return ChildWish objects filtered by the wish_deleted column
 * @method     ChildWish[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class WishQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\WishQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Wish', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildWishQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildWishQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildWishQuery) {
            return $criteria;
        }
        $query = new ChildWishQuery();
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
     * @return ChildWish|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(WishTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = WishTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildWish A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT wish_id, wishlist_id, user_id, site_id, article_id, wish_created, wish_updated, wish_bought, wish_deleted FROM wishes WHERE wish_id = :p0';
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
            /** @var ChildWish $obj */
            $obj = new ChildWish();
            $obj->hydrate($row);
            WishTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildWish|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildWishQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(WishTableMap::COL_WISH_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildWishQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(WishTableMap::COL_WISH_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the wish_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE wish_id = 1234
     * $query->filterById(array(12, 34)); // WHERE wish_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE wish_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWishQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(WishTableMap::COL_WISH_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(WishTableMap::COL_WISH_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WishTableMap::COL_WISH_ID, $id, $comparison);
    }

    /**
     * Filter the query on the wishlist_id column
     *
     * Example usage:
     * <code>
     * $query->filterByWishlistId(1234); // WHERE wishlist_id = 1234
     * $query->filterByWishlistId(array(12, 34)); // WHERE wishlist_id IN (12, 34)
     * $query->filterByWishlistId(array('min' => 12)); // WHERE wishlist_id > 12
     * </code>
     *
     * @param     mixed $wishlistId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWishQuery The current query, for fluid interface
     */
    public function filterByWishlistId($wishlistId = null, $comparison = null)
    {
        if (is_array($wishlistId)) {
            $useMinMax = false;
            if (isset($wishlistId['min'])) {
                $this->addUsingAlias(WishTableMap::COL_WISHLIST_ID, $wishlistId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($wishlistId['max'])) {
                $this->addUsingAlias(WishTableMap::COL_WISHLIST_ID, $wishlistId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WishTableMap::COL_WISHLIST_ID, $wishlistId, $comparison);
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
     * @return $this|ChildWishQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(WishTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(WishTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WishTableMap::COL_USER_ID, $userId, $comparison);
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
     * @return $this|ChildWishQuery The current query, for fluid interface
     */
    public function filterBySiteId($siteId = null, $comparison = null)
    {
        if (is_array($siteId)) {
            $useMinMax = false;
            if (isset($siteId['min'])) {
                $this->addUsingAlias(WishTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(WishTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WishTableMap::COL_SITE_ID, $siteId, $comparison);
    }

    /**
     * Filter the query on the article_id column
     *
     * Example usage:
     * <code>
     * $query->filterByArticleId(1234); // WHERE article_id = 1234
     * $query->filterByArticleId(array(12, 34)); // WHERE article_id IN (12, 34)
     * $query->filterByArticleId(array('min' => 12)); // WHERE article_id > 12
     * </code>
     *
     * @param     mixed $articleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWishQuery The current query, for fluid interface
     */
    public function filterByArticleId($articleId = null, $comparison = null)
    {
        if (is_array($articleId)) {
            $useMinMax = false;
            if (isset($articleId['min'])) {
                $this->addUsingAlias(WishTableMap::COL_ARTICLE_ID, $articleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($articleId['max'])) {
                $this->addUsingAlias(WishTableMap::COL_ARTICLE_ID, $articleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WishTableMap::COL_ARTICLE_ID, $articleId, $comparison);
    }

    /**
     * Filter the query on the wish_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE wish_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE wish_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE wish_created > '2011-03-13'
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
     * @return $this|ChildWishQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(WishTableMap::COL_WISH_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(WishTableMap::COL_WISH_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WishTableMap::COL_WISH_CREATED, $createdAt, $comparison);
    }

    /**
     * Filter the query on the wish_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE wish_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE wish_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE wish_updated > '2011-03-13'
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
     * @return $this|ChildWishQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(WishTableMap::COL_WISH_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(WishTableMap::COL_WISH_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WishTableMap::COL_WISH_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the wish_bought column
     *
     * Example usage:
     * <code>
     * $query->filterByBought('2011-03-14'); // WHERE wish_bought = '2011-03-14'
     * $query->filterByBought('now'); // WHERE wish_bought = '2011-03-14'
     * $query->filterByBought(array('max' => 'yesterday')); // WHERE wish_bought > '2011-03-13'
     * </code>
     *
     * @param     mixed $bought The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildWishQuery The current query, for fluid interface
     */
    public function filterByBought($bought = null, $comparison = null)
    {
        if (is_array($bought)) {
            $useMinMax = false;
            if (isset($bought['min'])) {
                $this->addUsingAlias(WishTableMap::COL_WISH_BOUGHT, $bought['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bought['max'])) {
                $this->addUsingAlias(WishTableMap::COL_WISH_BOUGHT, $bought['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WishTableMap::COL_WISH_BOUGHT, $bought, $comparison);
    }

    /**
     * Filter the query on the wish_deleted column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletedAt('2011-03-14'); // WHERE wish_deleted = '2011-03-14'
     * $query->filterByDeletedAt('now'); // WHERE wish_deleted = '2011-03-14'
     * $query->filterByDeletedAt(array('max' => 'yesterday')); // WHERE wish_deleted > '2011-03-13'
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
     * @return $this|ChildWishQuery The current query, for fluid interface
     */
    public function filterByDeletedAt($deletedAt = null, $comparison = null)
    {
        if (is_array($deletedAt)) {
            $useMinMax = false;
            if (isset($deletedAt['min'])) {
                $this->addUsingAlias(WishTableMap::COL_WISH_DELETED, $deletedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletedAt['max'])) {
                $this->addUsingAlias(WishTableMap::COL_WISH_DELETED, $deletedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(WishTableMap::COL_WISH_DELETED, $deletedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildWish $wish Object to remove from the list of results
     *
     * @return $this|ChildWishQuery The current query, for fluid interface
     */
    public function prune($wish = null)
    {
        if ($wish) {
            $this->addUsingAlias(WishTableMap::COL_WISH_ID, $wish->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the wishes table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(WishTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            WishTableMap::clearInstancePool();
            WishTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(WishTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(WishTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            WishTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            WishTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildWishQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(WishTableMap::COL_WISH_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildWishQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(WishTableMap::COL_WISH_UPDATED);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildWishQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(WishTableMap::COL_WISH_UPDATED);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildWishQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(WishTableMap::COL_WISH_CREATED);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildWishQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(WishTableMap::COL_WISH_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildWishQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(WishTableMap::COL_WISH_CREATED);
    }

} // WishQuery
