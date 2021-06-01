<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Payment as ChildPayment;
use Model\PaymentQuery as ChildPaymentQuery;
use Model\Map\PaymentTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'payments' table.
 *
 *
 *
 * @method     ChildPaymentQuery orderById($order = Criteria::ASC) Order by the payment_id column
 * @method     ChildPaymentQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildPaymentQuery orderByOrderId($order = Criteria::ASC) Order by the order_id column
 * @method     ChildPaymentQuery orderByAmount($order = Criteria::ASC) Order by the payment_amount column
 * @method     ChildPaymentQuery orderByMode($order = Criteria::ASC) Order by the payment_mode column
 * @method     ChildPaymentQuery orderByProviderId($order = Criteria::ASC) Order by the payment_provider_id column
 * @method     ChildPaymentQuery orderByUrl($order = Criteria::ASC) Order by the payment_url column
 * @method     ChildPaymentQuery orderByCreatedAt($order = Criteria::ASC) Order by the payment_created column
 * @method     ChildPaymentQuery orderByExecuted($order = Criteria::ASC) Order by the payment_executed column
 * @method     ChildPaymentQuery orderByUpdatedAt($order = Criteria::ASC) Order by the payment_updated column
 * @method     ChildPaymentQuery orderByDeletedAt($order = Criteria::ASC) Order by the payment_deleted column
 *
 * @method     ChildPaymentQuery groupById() Group by the payment_id column
 * @method     ChildPaymentQuery groupBySiteId() Group by the site_id column
 * @method     ChildPaymentQuery groupByOrderId() Group by the order_id column
 * @method     ChildPaymentQuery groupByAmount() Group by the payment_amount column
 * @method     ChildPaymentQuery groupByMode() Group by the payment_mode column
 * @method     ChildPaymentQuery groupByProviderId() Group by the payment_provider_id column
 * @method     ChildPaymentQuery groupByUrl() Group by the payment_url column
 * @method     ChildPaymentQuery groupByCreatedAt() Group by the payment_created column
 * @method     ChildPaymentQuery groupByExecuted() Group by the payment_executed column
 * @method     ChildPaymentQuery groupByUpdatedAt() Group by the payment_updated column
 * @method     ChildPaymentQuery groupByDeletedAt() Group by the payment_deleted column
 *
 * @method     ChildPaymentQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPaymentQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPaymentQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPaymentQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPaymentQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPaymentQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPayment|null findOne(ConnectionInterface $con = null) Return the first ChildPayment matching the query
 * @method     ChildPayment findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPayment matching the query, or a new ChildPayment object populated from the query conditions when no match is found
 *
 * @method     ChildPayment|null findOneById(int $payment_id) Return the first ChildPayment filtered by the payment_id column
 * @method     ChildPayment|null findOneBySiteId(int $site_id) Return the first ChildPayment filtered by the site_id column
 * @method     ChildPayment|null findOneByOrderId(int $order_id) Return the first ChildPayment filtered by the order_id column
 * @method     ChildPayment|null findOneByAmount(int $payment_amount) Return the first ChildPayment filtered by the payment_amount column
 * @method     ChildPayment|null findOneByMode(string $payment_mode) Return the first ChildPayment filtered by the payment_mode column
 * @method     ChildPayment|null findOneByProviderId(string $payment_provider_id) Return the first ChildPayment filtered by the payment_provider_id column
 * @method     ChildPayment|null findOneByUrl(string $payment_url) Return the first ChildPayment filtered by the payment_url column
 * @method     ChildPayment|null findOneByCreatedAt(string $payment_created) Return the first ChildPayment filtered by the payment_created column
 * @method     ChildPayment|null findOneByExecuted(string $payment_executed) Return the first ChildPayment filtered by the payment_executed column
 * @method     ChildPayment|null findOneByUpdatedAt(string $payment_updated) Return the first ChildPayment filtered by the payment_updated column
 * @method     ChildPayment|null findOneByDeletedAt(string $payment_deleted) Return the first ChildPayment filtered by the payment_deleted column *

 * @method     ChildPayment requirePk($key, ConnectionInterface $con = null) Return the ChildPayment by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPayment requireOne(ConnectionInterface $con = null) Return the first ChildPayment matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPayment requireOneById(int $payment_id) Return the first ChildPayment filtered by the payment_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPayment requireOneBySiteId(int $site_id) Return the first ChildPayment filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPayment requireOneByOrderId(int $order_id) Return the first ChildPayment filtered by the order_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPayment requireOneByAmount(int $payment_amount) Return the first ChildPayment filtered by the payment_amount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPayment requireOneByMode(string $payment_mode) Return the first ChildPayment filtered by the payment_mode column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPayment requireOneByProviderId(string $payment_provider_id) Return the first ChildPayment filtered by the payment_provider_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPayment requireOneByUrl(string $payment_url) Return the first ChildPayment filtered by the payment_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPayment requireOneByCreatedAt(string $payment_created) Return the first ChildPayment filtered by the payment_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPayment requireOneByExecuted(string $payment_executed) Return the first ChildPayment filtered by the payment_executed column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPayment requireOneByUpdatedAt(string $payment_updated) Return the first ChildPayment filtered by the payment_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPayment requireOneByDeletedAt(string $payment_deleted) Return the first ChildPayment filtered by the payment_deleted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPayment[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPayment objects based on current ModelCriteria
 * @method     ChildPayment[]|ObjectCollection findById(int $payment_id) Return ChildPayment objects filtered by the payment_id column
 * @method     ChildPayment[]|ObjectCollection findBySiteId(int $site_id) Return ChildPayment objects filtered by the site_id column
 * @method     ChildPayment[]|ObjectCollection findByOrderId(int $order_id) Return ChildPayment objects filtered by the order_id column
 * @method     ChildPayment[]|ObjectCollection findByAmount(int $payment_amount) Return ChildPayment objects filtered by the payment_amount column
 * @method     ChildPayment[]|ObjectCollection findByMode(string $payment_mode) Return ChildPayment objects filtered by the payment_mode column
 * @method     ChildPayment[]|ObjectCollection findByProviderId(string $payment_provider_id) Return ChildPayment objects filtered by the payment_provider_id column
 * @method     ChildPayment[]|ObjectCollection findByUrl(string $payment_url) Return ChildPayment objects filtered by the payment_url column
 * @method     ChildPayment[]|ObjectCollection findByCreatedAt(string $payment_created) Return ChildPayment objects filtered by the payment_created column
 * @method     ChildPayment[]|ObjectCollection findByExecuted(string $payment_executed) Return ChildPayment objects filtered by the payment_executed column
 * @method     ChildPayment[]|ObjectCollection findByUpdatedAt(string $payment_updated) Return ChildPayment objects filtered by the payment_updated column
 * @method     ChildPayment[]|ObjectCollection findByDeletedAt(string $payment_deleted) Return ChildPayment objects filtered by the payment_deleted column
 * @method     ChildPayment[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PaymentQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\PaymentQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Payment', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPaymentQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPaymentQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPaymentQuery) {
            return $criteria;
        }
        $query = new ChildPaymentQuery();
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
     * @return ChildPayment|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PaymentTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PaymentTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildPayment A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT payment_id, site_id, order_id, payment_amount, payment_mode, payment_provider_id, payment_url, payment_created, payment_executed, payment_updated, payment_deleted FROM payments WHERE payment_id = :p0';
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
            /** @var ChildPayment $obj */
            $obj = new ChildPayment();
            $obj->hydrate($row);
            PaymentTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPayment|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PaymentTableMap::COL_PAYMENT_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PaymentTableMap::COL_PAYMENT_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the payment_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE payment_id = 1234
     * $query->filterById(array(12, 34)); // WHERE payment_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE payment_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PaymentTableMap::COL_PAYMENT_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PaymentTableMap::COL_PAYMENT_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentTableMap::COL_PAYMENT_ID, $id, $comparison);
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
     * @return $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function filterBySiteId($siteId = null, $comparison = null)
    {
        if (is_array($siteId)) {
            $useMinMax = false;
            if (isset($siteId['min'])) {
                $this->addUsingAlias(PaymentTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(PaymentTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentTableMap::COL_SITE_ID, $siteId, $comparison);
    }

    /**
     * Filter the query on the order_id column
     *
     * Example usage:
     * <code>
     * $query->filterByOrderId(1234); // WHERE order_id = 1234
     * $query->filterByOrderId(array(12, 34)); // WHERE order_id IN (12, 34)
     * $query->filterByOrderId(array('min' => 12)); // WHERE order_id > 12
     * </code>
     *
     * @param     mixed $orderId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function filterByOrderId($orderId = null, $comparison = null)
    {
        if (is_array($orderId)) {
            $useMinMax = false;
            if (isset($orderId['min'])) {
                $this->addUsingAlias(PaymentTableMap::COL_ORDER_ID, $orderId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($orderId['max'])) {
                $this->addUsingAlias(PaymentTableMap::COL_ORDER_ID, $orderId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentTableMap::COL_ORDER_ID, $orderId, $comparison);
    }

    /**
     * Filter the query on the payment_amount column
     *
     * Example usage:
     * <code>
     * $query->filterByAmount(1234); // WHERE payment_amount = 1234
     * $query->filterByAmount(array(12, 34)); // WHERE payment_amount IN (12, 34)
     * $query->filterByAmount(array('min' => 12)); // WHERE payment_amount > 12
     * </code>
     *
     * @param     mixed $amount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function filterByAmount($amount = null, $comparison = null)
    {
        if (is_array($amount)) {
            $useMinMax = false;
            if (isset($amount['min'])) {
                $this->addUsingAlias(PaymentTableMap::COL_PAYMENT_AMOUNT, $amount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($amount['max'])) {
                $this->addUsingAlias(PaymentTableMap::COL_PAYMENT_AMOUNT, $amount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentTableMap::COL_PAYMENT_AMOUNT, $amount, $comparison);
    }

    /**
     * Filter the query on the payment_mode column
     *
     * Example usage:
     * <code>
     * $query->filterByMode('fooValue');   // WHERE payment_mode = 'fooValue'
     * $query->filterByMode('%fooValue%', Criteria::LIKE); // WHERE payment_mode LIKE '%fooValue%'
     * </code>
     *
     * @param     string $mode The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function filterByMode($mode = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($mode)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentTableMap::COL_PAYMENT_MODE, $mode, $comparison);
    }

    /**
     * Filter the query on the payment_provider_id column
     *
     * Example usage:
     * <code>
     * $query->filterByProviderId('fooValue');   // WHERE payment_provider_id = 'fooValue'
     * $query->filterByProviderId('%fooValue%', Criteria::LIKE); // WHERE payment_provider_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $providerId The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function filterByProviderId($providerId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($providerId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentTableMap::COL_PAYMENT_PROVIDER_ID, $providerId, $comparison);
    }

    /**
     * Filter the query on the payment_url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE payment_url = 'fooValue'
     * $query->filterByUrl('%fooValue%', Criteria::LIKE); // WHERE payment_url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $url The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function filterByUrl($url = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($url)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentTableMap::COL_PAYMENT_URL, $url, $comparison);
    }

    /**
     * Filter the query on the payment_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE payment_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE payment_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE payment_created > '2011-03-13'
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
     * @return $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PaymentTableMap::COL_PAYMENT_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PaymentTableMap::COL_PAYMENT_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentTableMap::COL_PAYMENT_CREATED, $createdAt, $comparison);
    }

    /**
     * Filter the query on the payment_executed column
     *
     * Example usage:
     * <code>
     * $query->filterByExecuted('2011-03-14'); // WHERE payment_executed = '2011-03-14'
     * $query->filterByExecuted('now'); // WHERE payment_executed = '2011-03-14'
     * $query->filterByExecuted(array('max' => 'yesterday')); // WHERE payment_executed > '2011-03-13'
     * </code>
     *
     * @param     mixed $executed The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function filterByExecuted($executed = null, $comparison = null)
    {
        if (is_array($executed)) {
            $useMinMax = false;
            if (isset($executed['min'])) {
                $this->addUsingAlias(PaymentTableMap::COL_PAYMENT_EXECUTED, $executed['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($executed['max'])) {
                $this->addUsingAlias(PaymentTableMap::COL_PAYMENT_EXECUTED, $executed['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentTableMap::COL_PAYMENT_EXECUTED, $executed, $comparison);
    }

    /**
     * Filter the query on the payment_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE payment_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE payment_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE payment_updated > '2011-03-13'
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
     * @return $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PaymentTableMap::COL_PAYMENT_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PaymentTableMap::COL_PAYMENT_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentTableMap::COL_PAYMENT_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the payment_deleted column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletedAt('2011-03-14'); // WHERE payment_deleted = '2011-03-14'
     * $query->filterByDeletedAt('now'); // WHERE payment_deleted = '2011-03-14'
     * $query->filterByDeletedAt(array('max' => 'yesterday')); // WHERE payment_deleted > '2011-03-13'
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
     * @return $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function filterByDeletedAt($deletedAt = null, $comparison = null)
    {
        if (is_array($deletedAt)) {
            $useMinMax = false;
            if (isset($deletedAt['min'])) {
                $this->addUsingAlias(PaymentTableMap::COL_PAYMENT_DELETED, $deletedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletedAt['max'])) {
                $this->addUsingAlias(PaymentTableMap::COL_PAYMENT_DELETED, $deletedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PaymentTableMap::COL_PAYMENT_DELETED, $deletedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPayment $payment Object to remove from the list of results
     *
     * @return $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function prune($payment = null)
    {
        if ($payment) {
            $this->addUsingAlias(PaymentTableMap::COL_PAYMENT_ID, $payment->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the payments table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PaymentTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PaymentTableMap::clearInstancePool();
            PaymentTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PaymentTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PaymentTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PaymentTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PaymentTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PaymentTableMap::COL_PAYMENT_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PaymentTableMap::COL_PAYMENT_UPDATED);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PaymentTableMap::COL_PAYMENT_UPDATED);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PaymentTableMap::COL_PAYMENT_CREATED);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PaymentTableMap::COL_PAYMENT_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildPaymentQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PaymentTableMap::COL_PAYMENT_CREATED);
    }

} // PaymentQuery
