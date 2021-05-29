<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Cart as ChildCart;
use Model\CartQuery as ChildCartQuery;
use Model\Map\CartTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'carts' table.
 *
 *
 *
 * @method     ChildCartQuery orderById($order = Criteria::ASC) Order by the cart_id column
 * @method     ChildCartQuery orderByUid($order = Criteria::ASC) Order by the cart_uid column
 * @method     ChildCartQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildCartQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildCartQuery orderBySellerId($order = Criteria::ASC) Order by the cart_seller_id column
 * @method     ChildCartQuery orderByCustomerId($order = Criteria::ASC) Order by the customer_id column
 * @method     ChildCartQuery orderByTitle($order = Criteria::ASC) Order by the cart_title column
 * @method     ChildCartQuery orderByType($order = Criteria::ASC) Order by the cart_type column
 * @method     ChildCartQuery orderByIp($order = Criteria::ASC) Order by the cart_ip column
 * @method     ChildCartQuery orderByCount($order = Criteria::ASC) Order by the cart_count column
 * @method     ChildCartQuery orderByAmount($order = Criteria::ASC) Order by the cart_amount column
 * @method     ChildCartQuery orderByAs-a-gift($order = Criteria::ASC) Order by the cart_as-a-gift column
 * @method     ChildCartQuery orderByGift-recipient($order = Criteria::ASC) Order by the cart_gift-recipient column
 * @method     ChildCartQuery orderByDate($order = Criteria::ASC) Order by the cart_date column
 * @method     ChildCartQuery orderByInsert($order = Criteria::ASC) Order by the cart_insert column
 * @method     ChildCartQuery orderByUpdate($order = Criteria::ASC) Order by the cart_update column
 * @method     ChildCartQuery orderByCreatedAt($order = Criteria::ASC) Order by the cart_created column
 * @method     ChildCartQuery orderByUpdatedAt($order = Criteria::ASC) Order by the cart_updated column
 * @method     ChildCartQuery orderByDeletedAt($order = Criteria::ASC) Order by the cart_deleted column
 *
 * @method     ChildCartQuery groupById() Group by the cart_id column
 * @method     ChildCartQuery groupByUid() Group by the cart_uid column
 * @method     ChildCartQuery groupBySiteId() Group by the site_id column
 * @method     ChildCartQuery groupByUserId() Group by the user_id column
 * @method     ChildCartQuery groupBySellerId() Group by the cart_seller_id column
 * @method     ChildCartQuery groupByCustomerId() Group by the customer_id column
 * @method     ChildCartQuery groupByTitle() Group by the cart_title column
 * @method     ChildCartQuery groupByType() Group by the cart_type column
 * @method     ChildCartQuery groupByIp() Group by the cart_ip column
 * @method     ChildCartQuery groupByCount() Group by the cart_count column
 * @method     ChildCartQuery groupByAmount() Group by the cart_amount column
 * @method     ChildCartQuery groupByAs-a-gift() Group by the cart_as-a-gift column
 * @method     ChildCartQuery groupByGift-recipient() Group by the cart_gift-recipient column
 * @method     ChildCartQuery groupByDate() Group by the cart_date column
 * @method     ChildCartQuery groupByInsert() Group by the cart_insert column
 * @method     ChildCartQuery groupByUpdate() Group by the cart_update column
 * @method     ChildCartQuery groupByCreatedAt() Group by the cart_created column
 * @method     ChildCartQuery groupByUpdatedAt() Group by the cart_updated column
 * @method     ChildCartQuery groupByDeletedAt() Group by the cart_deleted column
 *
 * @method     ChildCartQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCartQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCartQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCartQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildCartQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildCartQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildCart|null findOne(ConnectionInterface $con = null) Return the first ChildCart matching the query
 * @method     ChildCart findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCart matching the query, or a new ChildCart object populated from the query conditions when no match is found
 *
 * @method     ChildCart|null findOneById(int $cart_id) Return the first ChildCart filtered by the cart_id column
 * @method     ChildCart|null findOneByUid(string $cart_uid) Return the first ChildCart filtered by the cart_uid column
 * @method     ChildCart|null findOneBySiteId(int $site_id) Return the first ChildCart filtered by the site_id column
 * @method     ChildCart|null findOneByUserId(int $user_id) Return the first ChildCart filtered by the user_id column
 * @method     ChildCart|null findOneBySellerId(int $cart_seller_id) Return the first ChildCart filtered by the cart_seller_id column
 * @method     ChildCart|null findOneByCustomerId(int $customer_id) Return the first ChildCart filtered by the customer_id column
 * @method     ChildCart|null findOneByTitle(string $cart_title) Return the first ChildCart filtered by the cart_title column
 * @method     ChildCart|null findOneByType(string $cart_type) Return the first ChildCart filtered by the cart_type column
 * @method     ChildCart|null findOneByIp(string $cart_ip) Return the first ChildCart filtered by the cart_ip column
 * @method     ChildCart|null findOneByCount(int $cart_count) Return the first ChildCart filtered by the cart_count column
 * @method     ChildCart|null findOneByAmount(int $cart_amount) Return the first ChildCart filtered by the cart_amount column
 * @method     ChildCart|null findOneByAs-a-gift(string $cart_as-a-gift) Return the first ChildCart filtered by the cart_as-a-gift column
 * @method     ChildCart|null findOneByGift-recipient(int $cart_gift-recipient) Return the first ChildCart filtered by the cart_gift-recipient column
 * @method     ChildCart|null findOneByDate(string $cart_date) Return the first ChildCart filtered by the cart_date column
 * @method     ChildCart|null findOneByInsert(string $cart_insert) Return the first ChildCart filtered by the cart_insert column
 * @method     ChildCart|null findOneByUpdate(string $cart_update) Return the first ChildCart filtered by the cart_update column
 * @method     ChildCart|null findOneByCreatedAt(string $cart_created) Return the first ChildCart filtered by the cart_created column
 * @method     ChildCart|null findOneByUpdatedAt(string $cart_updated) Return the first ChildCart filtered by the cart_updated column
 * @method     ChildCart|null findOneByDeletedAt(string $cart_deleted) Return the first ChildCart filtered by the cart_deleted column *

 * @method     ChildCart requirePk($key, ConnectionInterface $con = null) Return the ChildCart by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOne(ConnectionInterface $con = null) Return the first ChildCart matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCart requireOneById(int $cart_id) Return the first ChildCart filtered by the cart_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByUid(string $cart_uid) Return the first ChildCart filtered by the cart_uid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneBySiteId(int $site_id) Return the first ChildCart filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByUserId(int $user_id) Return the first ChildCart filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneBySellerId(int $cart_seller_id) Return the first ChildCart filtered by the cart_seller_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByCustomerId(int $customer_id) Return the first ChildCart filtered by the customer_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByTitle(string $cart_title) Return the first ChildCart filtered by the cart_title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByType(string $cart_type) Return the first ChildCart filtered by the cart_type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByIp(string $cart_ip) Return the first ChildCart filtered by the cart_ip column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByCount(int $cart_count) Return the first ChildCart filtered by the cart_count column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByAmount(int $cart_amount) Return the first ChildCart filtered by the cart_amount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByAs-a-gift(string $cart_as-a-gift) Return the first ChildCart filtered by the cart_as-a-gift column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByGift-recipient(int $cart_gift-recipient) Return the first ChildCart filtered by the cart_gift-recipient column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByDate(string $cart_date) Return the first ChildCart filtered by the cart_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByInsert(string $cart_insert) Return the first ChildCart filtered by the cart_insert column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByUpdate(string $cart_update) Return the first ChildCart filtered by the cart_update column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByCreatedAt(string $cart_created) Return the first ChildCart filtered by the cart_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByUpdatedAt(string $cart_updated) Return the first ChildCart filtered by the cart_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByDeletedAt(string $cart_deleted) Return the first ChildCart filtered by the cart_deleted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCart[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildCart objects based on current ModelCriteria
 * @method     ChildCart[]|ObjectCollection findById(int $cart_id) Return ChildCart objects filtered by the cart_id column
 * @method     ChildCart[]|ObjectCollection findByUid(string $cart_uid) Return ChildCart objects filtered by the cart_uid column
 * @method     ChildCart[]|ObjectCollection findBySiteId(int $site_id) Return ChildCart objects filtered by the site_id column
 * @method     ChildCart[]|ObjectCollection findByUserId(int $user_id) Return ChildCart objects filtered by the user_id column
 * @method     ChildCart[]|ObjectCollection findBySellerId(int $cart_seller_id) Return ChildCart objects filtered by the cart_seller_id column
 * @method     ChildCart[]|ObjectCollection findByCustomerId(int $customer_id) Return ChildCart objects filtered by the customer_id column
 * @method     ChildCart[]|ObjectCollection findByTitle(string $cart_title) Return ChildCart objects filtered by the cart_title column
 * @method     ChildCart[]|ObjectCollection findByType(string $cart_type) Return ChildCart objects filtered by the cart_type column
 * @method     ChildCart[]|ObjectCollection findByIp(string $cart_ip) Return ChildCart objects filtered by the cart_ip column
 * @method     ChildCart[]|ObjectCollection findByCount(int $cart_count) Return ChildCart objects filtered by the cart_count column
 * @method     ChildCart[]|ObjectCollection findByAmount(int $cart_amount) Return ChildCart objects filtered by the cart_amount column
 * @method     ChildCart[]|ObjectCollection findByAs-a-gift(string $cart_as-a-gift) Return ChildCart objects filtered by the cart_as-a-gift column
 * @method     ChildCart[]|ObjectCollection findByGift-recipient(int $cart_gift-recipient) Return ChildCart objects filtered by the cart_gift-recipient column
 * @method     ChildCart[]|ObjectCollection findByDate(string $cart_date) Return ChildCart objects filtered by the cart_date column
 * @method     ChildCart[]|ObjectCollection findByInsert(string $cart_insert) Return ChildCart objects filtered by the cart_insert column
 * @method     ChildCart[]|ObjectCollection findByUpdate(string $cart_update) Return ChildCart objects filtered by the cart_update column
 * @method     ChildCart[]|ObjectCollection findByCreatedAt(string $cart_created) Return ChildCart objects filtered by the cart_created column
 * @method     ChildCart[]|ObjectCollection findByUpdatedAt(string $cart_updated) Return ChildCart objects filtered by the cart_updated column
 * @method     ChildCart[]|ObjectCollection findByDeletedAt(string $cart_deleted) Return ChildCart objects filtered by the cart_deleted column
 * @method     ChildCart[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class CartQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\CartQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Cart', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCartQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCartQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildCartQuery) {
            return $criteria;
        }
        $query = new ChildCartQuery();
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
     * @return ChildCart|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CartTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = CartTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildCart A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT cart_id, cart_uid, site_id, user_id, cart_seller_id, customer_id, cart_title, cart_type, cart_ip, cart_count, cart_amount, cart_as-a-gift, cart_gift-recipient, cart_date, cart_insert, cart_update, cart_created, cart_updated, cart_deleted FROM carts WHERE cart_id = :p0';
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
            /** @var ChildCart $obj */
            $obj = new ChildCart();
            $obj->hydrate($row);
            CartTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildCart|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildCartQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CartTableMap::COL_CART_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildCartQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CartTableMap::COL_CART_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the cart_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE cart_id = 1234
     * $query->filterById(array(12, 34)); // WHERE cart_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE cart_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCartQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CartTableMap::COL_CART_ID, $id, $comparison);
    }

    /**
     * Filter the query on the cart_uid column
     *
     * Example usage:
     * <code>
     * $query->filterByUid('fooValue');   // WHERE cart_uid = 'fooValue'
     * $query->filterByUid('%fooValue%', Criteria::LIKE); // WHERE cart_uid LIKE '%fooValue%'
     * </code>
     *
     * @param     string $uid The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCartQuery The current query, for fluid interface
     */
    public function filterByUid($uid = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($uid)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CartTableMap::COL_CART_UID, $uid, $comparison);
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
     * @return $this|ChildCartQuery The current query, for fluid interface
     */
    public function filterBySiteId($siteId = null, $comparison = null)
    {
        if (is_array($siteId)) {
            $useMinMax = false;
            if (isset($siteId['min'])) {
                $this->addUsingAlias(CartTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(CartTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CartTableMap::COL_SITE_ID, $siteId, $comparison);
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
     * @return $this|ChildCartQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(CartTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(CartTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CartTableMap::COL_USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the cart_seller_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySellerId(1234); // WHERE cart_seller_id = 1234
     * $query->filterBySellerId(array(12, 34)); // WHERE cart_seller_id IN (12, 34)
     * $query->filterBySellerId(array('min' => 12)); // WHERE cart_seller_id > 12
     * </code>
     *
     * @param     mixed $sellerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCartQuery The current query, for fluid interface
     */
    public function filterBySellerId($sellerId = null, $comparison = null)
    {
        if (is_array($sellerId)) {
            $useMinMax = false;
            if (isset($sellerId['min'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_SELLER_ID, $sellerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sellerId['max'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_SELLER_ID, $sellerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CartTableMap::COL_CART_SELLER_ID, $sellerId, $comparison);
    }

    /**
     * Filter the query on the customer_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCustomerId(1234); // WHERE customer_id = 1234
     * $query->filterByCustomerId(array(12, 34)); // WHERE customer_id IN (12, 34)
     * $query->filterByCustomerId(array('min' => 12)); // WHERE customer_id > 12
     * </code>
     *
     * @param     mixed $customerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCartQuery The current query, for fluid interface
     */
    public function filterByCustomerId($customerId = null, $comparison = null)
    {
        if (is_array($customerId)) {
            $useMinMax = false;
            if (isset($customerId['min'])) {
                $this->addUsingAlias(CartTableMap::COL_CUSTOMER_ID, $customerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($customerId['max'])) {
                $this->addUsingAlias(CartTableMap::COL_CUSTOMER_ID, $customerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CartTableMap::COL_CUSTOMER_ID, $customerId, $comparison);
    }

    /**
     * Filter the query on the cart_title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE cart_title = 'fooValue'
     * $query->filterByTitle('%fooValue%', Criteria::LIKE); // WHERE cart_title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCartQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CartTableMap::COL_CART_TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the cart_type column
     *
     * Example usage:
     * <code>
     * $query->filterByType('fooValue');   // WHERE cart_type = 'fooValue'
     * $query->filterByType('%fooValue%', Criteria::LIKE); // WHERE cart_type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $type The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCartQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($type)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CartTableMap::COL_CART_TYPE, $type, $comparison);
    }

    /**
     * Filter the query on the cart_ip column
     *
     * Example usage:
     * <code>
     * $query->filterByIp('fooValue');   // WHERE cart_ip = 'fooValue'
     * $query->filterByIp('%fooValue%', Criteria::LIKE); // WHERE cart_ip LIKE '%fooValue%'
     * </code>
     *
     * @param     string $ip The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCartQuery The current query, for fluid interface
     */
    public function filterByIp($ip = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($ip)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CartTableMap::COL_CART_IP, $ip, $comparison);
    }

    /**
     * Filter the query on the cart_count column
     *
     * Example usage:
     * <code>
     * $query->filterByCount(1234); // WHERE cart_count = 1234
     * $query->filterByCount(array(12, 34)); // WHERE cart_count IN (12, 34)
     * $query->filterByCount(array('min' => 12)); // WHERE cart_count > 12
     * </code>
     *
     * @param     mixed $count The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCartQuery The current query, for fluid interface
     */
    public function filterByCount($count = null, $comparison = null)
    {
        if (is_array($count)) {
            $useMinMax = false;
            if (isset($count['min'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_COUNT, $count['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($count['max'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_COUNT, $count['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CartTableMap::COL_CART_COUNT, $count, $comparison);
    }

    /**
     * Filter the query on the cart_amount column
     *
     * Example usage:
     * <code>
     * $query->filterByAmount(1234); // WHERE cart_amount = 1234
     * $query->filterByAmount(array(12, 34)); // WHERE cart_amount IN (12, 34)
     * $query->filterByAmount(array('min' => 12)); // WHERE cart_amount > 12
     * </code>
     *
     * @param     mixed $amount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCartQuery The current query, for fluid interface
     */
    public function filterByAmount($amount = null, $comparison = null)
    {
        if (is_array($amount)) {
            $useMinMax = false;
            if (isset($amount['min'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_AMOUNT, $amount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($amount['max'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_AMOUNT, $amount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CartTableMap::COL_CART_AMOUNT, $amount, $comparison);
    }

    /**
     * Filter the query on the cart_as-a-gift column
     *
     * Example usage:
     * <code>
     * $query->filterByAs-a-gift('fooValue');   // WHERE cart_as-a-gift = 'fooValue'
     * $query->filterByAs-a-gift('%fooValue%', Criteria::LIKE); // WHERE cart_as-a-gift LIKE '%fooValue%'
     * </code>
     *
     * @param     string $as-a-gift The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCartQuery The current query, for fluid interface
     */
    public function filterByAs-a-gift($as-a-gift = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($as-a-gift)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CartTableMap::COL_CART_AS-A-GIFT, $as-a-gift, $comparison);
    }

    /**
     * Filter the query on the cart_gift-recipient column
     *
     * Example usage:
     * <code>
     * $query->filterByGift-recipient(1234); // WHERE cart_gift-recipient = 1234
     * $query->filterByGift-recipient(array(12, 34)); // WHERE cart_gift-recipient IN (12, 34)
     * $query->filterByGift-recipient(array('min' => 12)); // WHERE cart_gift-recipient > 12
     * </code>
     *
     * @param     mixed $gift-recipient The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCartQuery The current query, for fluid interface
     */
    public function filterByGift-recipient($gift-recipient = null, $comparison = null)
    {
        if (is_array($gift-recipient)) {
            $useMinMax = false;
            if (isset($gift-recipient['min'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_GIFT-RECIPIENT, $gift-recipient['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($gift-recipient['max'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_GIFT-RECIPIENT, $gift-recipient['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CartTableMap::COL_CART_GIFT-RECIPIENT, $gift-recipient, $comparison);
    }

    /**
     * Filter the query on the cart_date column
     *
     * Example usage:
     * <code>
     * $query->filterByDate('2011-03-14'); // WHERE cart_date = '2011-03-14'
     * $query->filterByDate('now'); // WHERE cart_date = '2011-03-14'
     * $query->filterByDate(array('max' => 'yesterday')); // WHERE cart_date > '2011-03-13'
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
     * @return $this|ChildCartQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CartTableMap::COL_CART_DATE, $date, $comparison);
    }

    /**
     * Filter the query on the cart_insert column
     *
     * Example usage:
     * <code>
     * $query->filterByInsert('2011-03-14'); // WHERE cart_insert = '2011-03-14'
     * $query->filterByInsert('now'); // WHERE cart_insert = '2011-03-14'
     * $query->filterByInsert(array('max' => 'yesterday')); // WHERE cart_insert > '2011-03-13'
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
     * @return $this|ChildCartQuery The current query, for fluid interface
     */
    public function filterByInsert($insert = null, $comparison = null)
    {
        if (is_array($insert)) {
            $useMinMax = false;
            if (isset($insert['min'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_INSERT, $insert['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($insert['max'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_INSERT, $insert['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CartTableMap::COL_CART_INSERT, $insert, $comparison);
    }

    /**
     * Filter the query on the cart_update column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdate('2011-03-14'); // WHERE cart_update = '2011-03-14'
     * $query->filterByUpdate('now'); // WHERE cart_update = '2011-03-14'
     * $query->filterByUpdate(array('max' => 'yesterday')); // WHERE cart_update > '2011-03-13'
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
     * @return $this|ChildCartQuery The current query, for fluid interface
     */
    public function filterByUpdate($update = null, $comparison = null)
    {
        if (is_array($update)) {
            $useMinMax = false;
            if (isset($update['min'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_UPDATE, $update['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($update['max'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_UPDATE, $update['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CartTableMap::COL_CART_UPDATE, $update, $comparison);
    }

    /**
     * Filter the query on the cart_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE cart_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE cart_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE cart_created > '2011-03-13'
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
     * @return $this|ChildCartQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CartTableMap::COL_CART_CREATED, $createdAt, $comparison);
    }

    /**
     * Filter the query on the cart_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE cart_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE cart_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE cart_updated > '2011-03-13'
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
     * @return $this|ChildCartQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CartTableMap::COL_CART_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the cart_deleted column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletedAt('2011-03-14'); // WHERE cart_deleted = '2011-03-14'
     * $query->filterByDeletedAt('now'); // WHERE cart_deleted = '2011-03-14'
     * $query->filterByDeletedAt(array('max' => 'yesterday')); // WHERE cart_deleted > '2011-03-13'
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
     * @return $this|ChildCartQuery The current query, for fluid interface
     */
    public function filterByDeletedAt($deletedAt = null, $comparison = null)
    {
        if (is_array($deletedAt)) {
            $useMinMax = false;
            if (isset($deletedAt['min'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_DELETED, $deletedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletedAt['max'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_DELETED, $deletedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CartTableMap::COL_CART_DELETED, $deletedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildCart $cart Object to remove from the list of results
     *
     * @return $this|ChildCartQuery The current query, for fluid interface
     */
    public function prune($cart = null)
    {
        if ($cart) {
            $this->addUsingAlias(CartTableMap::COL_CART_ID, $cart->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the carts table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CartTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CartTableMap::clearInstancePool();
            CartTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(CartTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CartTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            CartTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CartTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // CartQuery
