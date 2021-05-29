<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\ShippingFee as ChildShippingFee;
use Model\ShippingFeeQuery as ChildShippingFeeQuery;
use Model\Map\ShippingFeeTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'shipping' table.
 *
 *
 *
 * @method     ChildShippingFeeQuery orderById($order = Criteria::ASC) Order by the shipping_id column
 * @method     ChildShippingFeeQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildShippingFeeQuery orderByArticleId($order = Criteria::ASC) Order by the article_id column
 * @method     ChildShippingFeeQuery orderByMode($order = Criteria::ASC) Order by the shipping_mode column
 * @method     ChildShippingFeeQuery orderByType($order = Criteria::ASC) Order by the shipping_type column
 * @method     ChildShippingFeeQuery orderByZone($order = Criteria::ASC) Order by the shipping_zone column
 * @method     ChildShippingFeeQuery orderByMinWeight($order = Criteria::ASC) Order by the shipping_min_weight column
 * @method     ChildShippingFeeQuery orderByMaxWeight($order = Criteria::ASC) Order by the shipping_max_weight column
 * @method     ChildShippingFeeQuery orderByMaxArticles($order = Criteria::ASC) Order by the shipping_max_articles column
 * @method     ChildShippingFeeQuery orderByMinAmount($order = Criteria::ASC) Order by the shipping_min_amount column
 * @method     ChildShippingFeeQuery orderByMaxAmount($order = Criteria::ASC) Order by the shipping_max_amount column
 * @method     ChildShippingFeeQuery orderByFee($order = Criteria::ASC) Order by the shipping_fee column
 * @method     ChildShippingFeeQuery orderByInfo($order = Criteria::ASC) Order by the shipping_info column
 * @method     ChildShippingFeeQuery orderByCreatedAt($order = Criteria::ASC) Order by the shipping_created column
 * @method     ChildShippingFeeQuery orderByUpdatedAt($order = Criteria::ASC) Order by the shipping_updated column
 * @method     ChildShippingFeeQuery orderByDeletedAt($order = Criteria::ASC) Order by the shipping_deleted column
 *
 * @method     ChildShippingFeeQuery groupById() Group by the shipping_id column
 * @method     ChildShippingFeeQuery groupBySiteId() Group by the site_id column
 * @method     ChildShippingFeeQuery groupByArticleId() Group by the article_id column
 * @method     ChildShippingFeeQuery groupByMode() Group by the shipping_mode column
 * @method     ChildShippingFeeQuery groupByType() Group by the shipping_type column
 * @method     ChildShippingFeeQuery groupByZone() Group by the shipping_zone column
 * @method     ChildShippingFeeQuery groupByMinWeight() Group by the shipping_min_weight column
 * @method     ChildShippingFeeQuery groupByMaxWeight() Group by the shipping_max_weight column
 * @method     ChildShippingFeeQuery groupByMaxArticles() Group by the shipping_max_articles column
 * @method     ChildShippingFeeQuery groupByMinAmount() Group by the shipping_min_amount column
 * @method     ChildShippingFeeQuery groupByMaxAmount() Group by the shipping_max_amount column
 * @method     ChildShippingFeeQuery groupByFee() Group by the shipping_fee column
 * @method     ChildShippingFeeQuery groupByInfo() Group by the shipping_info column
 * @method     ChildShippingFeeQuery groupByCreatedAt() Group by the shipping_created column
 * @method     ChildShippingFeeQuery groupByUpdatedAt() Group by the shipping_updated column
 * @method     ChildShippingFeeQuery groupByDeletedAt() Group by the shipping_deleted column
 *
 * @method     ChildShippingFeeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildShippingFeeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildShippingFeeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildShippingFeeQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildShippingFeeQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildShippingFeeQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildShippingFee|null findOne(ConnectionInterface $con = null) Return the first ChildShippingFee matching the query
 * @method     ChildShippingFee findOneOrCreate(ConnectionInterface $con = null) Return the first ChildShippingFee matching the query, or a new ChildShippingFee object populated from the query conditions when no match is found
 *
 * @method     ChildShippingFee|null findOneById(int $shipping_id) Return the first ChildShippingFee filtered by the shipping_id column
 * @method     ChildShippingFee|null findOneBySiteId(int $site_id) Return the first ChildShippingFee filtered by the site_id column
 * @method     ChildShippingFee|null findOneByArticleId(int $article_id) Return the first ChildShippingFee filtered by the article_id column
 * @method     ChildShippingFee|null findOneByMode(string $shipping_mode) Return the first ChildShippingFee filtered by the shipping_mode column
 * @method     ChildShippingFee|null findOneByType(string $shipping_type) Return the first ChildShippingFee filtered by the shipping_type column
 * @method     ChildShippingFee|null findOneByZone(string $shipping_zone) Return the first ChildShippingFee filtered by the shipping_zone column
 * @method     ChildShippingFee|null findOneByMinWeight(int $shipping_min_weight) Return the first ChildShippingFee filtered by the shipping_min_weight column
 * @method     ChildShippingFee|null findOneByMaxWeight(int $shipping_max_weight) Return the first ChildShippingFee filtered by the shipping_max_weight column
 * @method     ChildShippingFee|null findOneByMaxArticles(int $shipping_max_articles) Return the first ChildShippingFee filtered by the shipping_max_articles column
 * @method     ChildShippingFee|null findOneByMinAmount(int $shipping_min_amount) Return the first ChildShippingFee filtered by the shipping_min_amount column
 * @method     ChildShippingFee|null findOneByMaxAmount(int $shipping_max_amount) Return the first ChildShippingFee filtered by the shipping_max_amount column
 * @method     ChildShippingFee|null findOneByFee(int $shipping_fee) Return the first ChildShippingFee filtered by the shipping_fee column
 * @method     ChildShippingFee|null findOneByInfo(string $shipping_info) Return the first ChildShippingFee filtered by the shipping_info column
 * @method     ChildShippingFee|null findOneByCreatedAt(string $shipping_created) Return the first ChildShippingFee filtered by the shipping_created column
 * @method     ChildShippingFee|null findOneByUpdatedAt(string $shipping_updated) Return the first ChildShippingFee filtered by the shipping_updated column
 * @method     ChildShippingFee|null findOneByDeletedAt(string $shipping_deleted) Return the first ChildShippingFee filtered by the shipping_deleted column *

 * @method     ChildShippingFee requirePk($key, ConnectionInterface $con = null) Return the ChildShippingFee by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingFee requireOne(ConnectionInterface $con = null) Return the first ChildShippingFee matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildShippingFee requireOneById(int $shipping_id) Return the first ChildShippingFee filtered by the shipping_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingFee requireOneBySiteId(int $site_id) Return the first ChildShippingFee filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingFee requireOneByArticleId(int $article_id) Return the first ChildShippingFee filtered by the article_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingFee requireOneByMode(string $shipping_mode) Return the first ChildShippingFee filtered by the shipping_mode column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingFee requireOneByType(string $shipping_type) Return the first ChildShippingFee filtered by the shipping_type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingFee requireOneByZone(string $shipping_zone) Return the first ChildShippingFee filtered by the shipping_zone column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingFee requireOneByMinWeight(int $shipping_min_weight) Return the first ChildShippingFee filtered by the shipping_min_weight column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingFee requireOneByMaxWeight(int $shipping_max_weight) Return the first ChildShippingFee filtered by the shipping_max_weight column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingFee requireOneByMaxArticles(int $shipping_max_articles) Return the first ChildShippingFee filtered by the shipping_max_articles column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingFee requireOneByMinAmount(int $shipping_min_amount) Return the first ChildShippingFee filtered by the shipping_min_amount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingFee requireOneByMaxAmount(int $shipping_max_amount) Return the first ChildShippingFee filtered by the shipping_max_amount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingFee requireOneByFee(int $shipping_fee) Return the first ChildShippingFee filtered by the shipping_fee column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingFee requireOneByInfo(string $shipping_info) Return the first ChildShippingFee filtered by the shipping_info column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingFee requireOneByCreatedAt(string $shipping_created) Return the first ChildShippingFee filtered by the shipping_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingFee requireOneByUpdatedAt(string $shipping_updated) Return the first ChildShippingFee filtered by the shipping_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingFee requireOneByDeletedAt(string $shipping_deleted) Return the first ChildShippingFee filtered by the shipping_deleted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildShippingFee[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildShippingFee objects based on current ModelCriteria
 * @method     ChildShippingFee[]|ObjectCollection findById(int $shipping_id) Return ChildShippingFee objects filtered by the shipping_id column
 * @method     ChildShippingFee[]|ObjectCollection findBySiteId(int $site_id) Return ChildShippingFee objects filtered by the site_id column
 * @method     ChildShippingFee[]|ObjectCollection findByArticleId(int $article_id) Return ChildShippingFee objects filtered by the article_id column
 * @method     ChildShippingFee[]|ObjectCollection findByMode(string $shipping_mode) Return ChildShippingFee objects filtered by the shipping_mode column
 * @method     ChildShippingFee[]|ObjectCollection findByType(string $shipping_type) Return ChildShippingFee objects filtered by the shipping_type column
 * @method     ChildShippingFee[]|ObjectCollection findByZone(string $shipping_zone) Return ChildShippingFee objects filtered by the shipping_zone column
 * @method     ChildShippingFee[]|ObjectCollection findByMinWeight(int $shipping_min_weight) Return ChildShippingFee objects filtered by the shipping_min_weight column
 * @method     ChildShippingFee[]|ObjectCollection findByMaxWeight(int $shipping_max_weight) Return ChildShippingFee objects filtered by the shipping_max_weight column
 * @method     ChildShippingFee[]|ObjectCollection findByMaxArticles(int $shipping_max_articles) Return ChildShippingFee objects filtered by the shipping_max_articles column
 * @method     ChildShippingFee[]|ObjectCollection findByMinAmount(int $shipping_min_amount) Return ChildShippingFee objects filtered by the shipping_min_amount column
 * @method     ChildShippingFee[]|ObjectCollection findByMaxAmount(int $shipping_max_amount) Return ChildShippingFee objects filtered by the shipping_max_amount column
 * @method     ChildShippingFee[]|ObjectCollection findByFee(int $shipping_fee) Return ChildShippingFee objects filtered by the shipping_fee column
 * @method     ChildShippingFee[]|ObjectCollection findByInfo(string $shipping_info) Return ChildShippingFee objects filtered by the shipping_info column
 * @method     ChildShippingFee[]|ObjectCollection findByCreatedAt(string $shipping_created) Return ChildShippingFee objects filtered by the shipping_created column
 * @method     ChildShippingFee[]|ObjectCollection findByUpdatedAt(string $shipping_updated) Return ChildShippingFee objects filtered by the shipping_updated column
 * @method     ChildShippingFee[]|ObjectCollection findByDeletedAt(string $shipping_deleted) Return ChildShippingFee objects filtered by the shipping_deleted column
 * @method     ChildShippingFee[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ShippingFeeQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\ShippingFeeQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\ShippingFee', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildShippingFeeQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildShippingFeeQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildShippingFeeQuery) {
            return $criteria;
        }
        $query = new ChildShippingFeeQuery();
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
     * @return ChildShippingFee|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ShippingFeeTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ShippingFeeTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildShippingFee A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT shipping_id, site_id, article_id, shipping_mode, shipping_type, shipping_zone, shipping_min_weight, shipping_max_weight, shipping_max_articles, shipping_min_amount, shipping_max_amount, shipping_fee, shipping_info, shipping_created, shipping_updated, shipping_deleted FROM shipping WHERE shipping_id = :p0';
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
            /** @var ChildShippingFee $obj */
            $obj = new ChildShippingFee();
            $obj->hydrate($row);
            ShippingFeeTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildShippingFee|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildShippingFeeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildShippingFeeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the shipping_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE shipping_id = 1234
     * $query->filterById(array(12, 34)); // WHERE shipping_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE shipping_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildShippingFeeQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_ID, $id, $comparison);
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
     * @return $this|ChildShippingFeeQuery The current query, for fluid interface
     */
    public function filterBySiteId($siteId = null, $comparison = null)
    {
        if (is_array($siteId)) {
            $useMinMax = false;
            if (isset($siteId['min'])) {
                $this->addUsingAlias(ShippingFeeTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(ShippingFeeTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ShippingFeeTableMap::COL_SITE_ID, $siteId, $comparison);
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
     * @return $this|ChildShippingFeeQuery The current query, for fluid interface
     */
    public function filterByArticleId($articleId = null, $comparison = null)
    {
        if (is_array($articleId)) {
            $useMinMax = false;
            if (isset($articleId['min'])) {
                $this->addUsingAlias(ShippingFeeTableMap::COL_ARTICLE_ID, $articleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($articleId['max'])) {
                $this->addUsingAlias(ShippingFeeTableMap::COL_ARTICLE_ID, $articleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ShippingFeeTableMap::COL_ARTICLE_ID, $articleId, $comparison);
    }

    /**
     * Filter the query on the shipping_mode column
     *
     * Example usage:
     * <code>
     * $query->filterByMode('fooValue');   // WHERE shipping_mode = 'fooValue'
     * $query->filterByMode('%fooValue%', Criteria::LIKE); // WHERE shipping_mode LIKE '%fooValue%'
     * </code>
     *
     * @param     string $mode The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildShippingFeeQuery The current query, for fluid interface
     */
    public function filterByMode($mode = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($mode)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_MODE, $mode, $comparison);
    }

    /**
     * Filter the query on the shipping_type column
     *
     * Example usage:
     * <code>
     * $query->filterByType('fooValue');   // WHERE shipping_type = 'fooValue'
     * $query->filterByType('%fooValue%', Criteria::LIKE); // WHERE shipping_type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $type The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildShippingFeeQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($type)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_TYPE, $type, $comparison);
    }

    /**
     * Filter the query on the shipping_zone column
     *
     * Example usage:
     * <code>
     * $query->filterByZone('fooValue');   // WHERE shipping_zone = 'fooValue'
     * $query->filterByZone('%fooValue%', Criteria::LIKE); // WHERE shipping_zone LIKE '%fooValue%'
     * </code>
     *
     * @param     string $zone The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildShippingFeeQuery The current query, for fluid interface
     */
    public function filterByZone($zone = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($zone)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_ZONE, $zone, $comparison);
    }

    /**
     * Filter the query on the shipping_min_weight column
     *
     * Example usage:
     * <code>
     * $query->filterByMinWeight(1234); // WHERE shipping_min_weight = 1234
     * $query->filterByMinWeight(array(12, 34)); // WHERE shipping_min_weight IN (12, 34)
     * $query->filterByMinWeight(array('min' => 12)); // WHERE shipping_min_weight > 12
     * </code>
     *
     * @param     mixed $minWeight The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildShippingFeeQuery The current query, for fluid interface
     */
    public function filterByMinWeight($minWeight = null, $comparison = null)
    {
        if (is_array($minWeight)) {
            $useMinMax = false;
            if (isset($minWeight['min'])) {
                $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_MIN_WEIGHT, $minWeight['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($minWeight['max'])) {
                $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_MIN_WEIGHT, $minWeight['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_MIN_WEIGHT, $minWeight, $comparison);
    }

    /**
     * Filter the query on the shipping_max_weight column
     *
     * Example usage:
     * <code>
     * $query->filterByMaxWeight(1234); // WHERE shipping_max_weight = 1234
     * $query->filterByMaxWeight(array(12, 34)); // WHERE shipping_max_weight IN (12, 34)
     * $query->filterByMaxWeight(array('min' => 12)); // WHERE shipping_max_weight > 12
     * </code>
     *
     * @param     mixed $maxWeight The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildShippingFeeQuery The current query, for fluid interface
     */
    public function filterByMaxWeight($maxWeight = null, $comparison = null)
    {
        if (is_array($maxWeight)) {
            $useMinMax = false;
            if (isset($maxWeight['min'])) {
                $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_MAX_WEIGHT, $maxWeight['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($maxWeight['max'])) {
                $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_MAX_WEIGHT, $maxWeight['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_MAX_WEIGHT, $maxWeight, $comparison);
    }

    /**
     * Filter the query on the shipping_max_articles column
     *
     * Example usage:
     * <code>
     * $query->filterByMaxArticles(1234); // WHERE shipping_max_articles = 1234
     * $query->filterByMaxArticles(array(12, 34)); // WHERE shipping_max_articles IN (12, 34)
     * $query->filterByMaxArticles(array('min' => 12)); // WHERE shipping_max_articles > 12
     * </code>
     *
     * @param     mixed $maxArticles The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildShippingFeeQuery The current query, for fluid interface
     */
    public function filterByMaxArticles($maxArticles = null, $comparison = null)
    {
        if (is_array($maxArticles)) {
            $useMinMax = false;
            if (isset($maxArticles['min'])) {
                $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_MAX_ARTICLES, $maxArticles['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($maxArticles['max'])) {
                $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_MAX_ARTICLES, $maxArticles['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_MAX_ARTICLES, $maxArticles, $comparison);
    }

    /**
     * Filter the query on the shipping_min_amount column
     *
     * Example usage:
     * <code>
     * $query->filterByMinAmount(1234); // WHERE shipping_min_amount = 1234
     * $query->filterByMinAmount(array(12, 34)); // WHERE shipping_min_amount IN (12, 34)
     * $query->filterByMinAmount(array('min' => 12)); // WHERE shipping_min_amount > 12
     * </code>
     *
     * @param     mixed $minAmount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildShippingFeeQuery The current query, for fluid interface
     */
    public function filterByMinAmount($minAmount = null, $comparison = null)
    {
        if (is_array($minAmount)) {
            $useMinMax = false;
            if (isset($minAmount['min'])) {
                $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_MIN_AMOUNT, $minAmount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($minAmount['max'])) {
                $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_MIN_AMOUNT, $minAmount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_MIN_AMOUNT, $minAmount, $comparison);
    }

    /**
     * Filter the query on the shipping_max_amount column
     *
     * Example usage:
     * <code>
     * $query->filterByMaxAmount(1234); // WHERE shipping_max_amount = 1234
     * $query->filterByMaxAmount(array(12, 34)); // WHERE shipping_max_amount IN (12, 34)
     * $query->filterByMaxAmount(array('min' => 12)); // WHERE shipping_max_amount > 12
     * </code>
     *
     * @param     mixed $maxAmount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildShippingFeeQuery The current query, for fluid interface
     */
    public function filterByMaxAmount($maxAmount = null, $comparison = null)
    {
        if (is_array($maxAmount)) {
            $useMinMax = false;
            if (isset($maxAmount['min'])) {
                $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_MAX_AMOUNT, $maxAmount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($maxAmount['max'])) {
                $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_MAX_AMOUNT, $maxAmount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_MAX_AMOUNT, $maxAmount, $comparison);
    }

    /**
     * Filter the query on the shipping_fee column
     *
     * Example usage:
     * <code>
     * $query->filterByFee(1234); // WHERE shipping_fee = 1234
     * $query->filterByFee(array(12, 34)); // WHERE shipping_fee IN (12, 34)
     * $query->filterByFee(array('min' => 12)); // WHERE shipping_fee > 12
     * </code>
     *
     * @param     mixed $fee The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildShippingFeeQuery The current query, for fluid interface
     */
    public function filterByFee($fee = null, $comparison = null)
    {
        if (is_array($fee)) {
            $useMinMax = false;
            if (isset($fee['min'])) {
                $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_FEE, $fee['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($fee['max'])) {
                $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_FEE, $fee['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_FEE, $fee, $comparison);
    }

    /**
     * Filter the query on the shipping_info column
     *
     * Example usage:
     * <code>
     * $query->filterByInfo('fooValue');   // WHERE shipping_info = 'fooValue'
     * $query->filterByInfo('%fooValue%', Criteria::LIKE); // WHERE shipping_info LIKE '%fooValue%'
     * </code>
     *
     * @param     string $info The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildShippingFeeQuery The current query, for fluid interface
     */
    public function filterByInfo($info = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($info)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_INFO, $info, $comparison);
    }

    /**
     * Filter the query on the shipping_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE shipping_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE shipping_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE shipping_created > '2011-03-13'
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
     * @return $this|ChildShippingFeeQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_CREATED, $createdAt, $comparison);
    }

    /**
     * Filter the query on the shipping_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE shipping_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE shipping_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE shipping_updated > '2011-03-13'
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
     * @return $this|ChildShippingFeeQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the shipping_deleted column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletedAt('2011-03-14'); // WHERE shipping_deleted = '2011-03-14'
     * $query->filterByDeletedAt('now'); // WHERE shipping_deleted = '2011-03-14'
     * $query->filterByDeletedAt(array('max' => 'yesterday')); // WHERE shipping_deleted > '2011-03-13'
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
     * @return $this|ChildShippingFeeQuery The current query, for fluid interface
     */
    public function filterByDeletedAt($deletedAt = null, $comparison = null)
    {
        if (is_array($deletedAt)) {
            $useMinMax = false;
            if (isset($deletedAt['min'])) {
                $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_DELETED, $deletedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletedAt['max'])) {
                $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_DELETED, $deletedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_DELETED, $deletedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildShippingFee $shippingFee Object to remove from the list of results
     *
     * @return $this|ChildShippingFeeQuery The current query, for fluid interface
     */
    public function prune($shippingFee = null)
    {
        if ($shippingFee) {
            $this->addUsingAlias(ShippingFeeTableMap::COL_SHIPPING_ID, $shippingFee->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the shipping table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ShippingFeeTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ShippingFeeTableMap::clearInstancePool();
            ShippingFeeTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ShippingFeeTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ShippingFeeTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ShippingFeeTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ShippingFeeTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ShippingFeeQuery
