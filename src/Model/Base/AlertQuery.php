<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Alert as ChildAlert;
use Model\AlertQuery as ChildAlertQuery;
use Model\Map\AlertTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'alerts' table.
 *
 *
 *
 * @method     ChildAlertQuery orderById($order = Criteria::ASC) Order by the alert_id column
 * @method     ChildAlertQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildAlertQuery orderByArticleId($order = Criteria::ASC) Order by the article_id column
 * @method     ChildAlertQuery orderByMaxPrice($order = Criteria::ASC) Order by the alert_max_price column
 * @method     ChildAlertQuery orderByPubYear($order = Criteria::ASC) Order by the alert_pub_year column
 * @method     ChildAlertQuery orderByCondition($order = Criteria::ASC) Order by the alert_condition column
 * @method     ChildAlertQuery orderByInsert($order = Criteria::ASC) Order by the alert_insert column
 * @method     ChildAlertQuery orderByUpdate($order = Criteria::ASC) Order by the alert_update column
 * @method     ChildAlertQuery orderByCreatedAt($order = Criteria::ASC) Order by the alert_created column
 * @method     ChildAlertQuery orderByUpdatedAt($order = Criteria::ASC) Order by the alert_updated column
 *
 * @method     ChildAlertQuery groupById() Group by the alert_id column
 * @method     ChildAlertQuery groupByUserId() Group by the user_id column
 * @method     ChildAlertQuery groupByArticleId() Group by the article_id column
 * @method     ChildAlertQuery groupByMaxPrice() Group by the alert_max_price column
 * @method     ChildAlertQuery groupByPubYear() Group by the alert_pub_year column
 * @method     ChildAlertQuery groupByCondition() Group by the alert_condition column
 * @method     ChildAlertQuery groupByInsert() Group by the alert_insert column
 * @method     ChildAlertQuery groupByUpdate() Group by the alert_update column
 * @method     ChildAlertQuery groupByCreatedAt() Group by the alert_created column
 * @method     ChildAlertQuery groupByUpdatedAt() Group by the alert_updated column
 *
 * @method     ChildAlertQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildAlertQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildAlertQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildAlertQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildAlertQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildAlertQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildAlert|null findOne(?ConnectionInterface $con = null) Return the first ChildAlert matching the query
 * @method     ChildAlert findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildAlert matching the query, or a new ChildAlert object populated from the query conditions when no match is found
 *
 * @method     ChildAlert|null findOneById(int $alert_id) Return the first ChildAlert filtered by the alert_id column
 * @method     ChildAlert|null findOneByUserId(int $user_id) Return the first ChildAlert filtered by the user_id column
 * @method     ChildAlert|null findOneByArticleId(int $article_id) Return the first ChildAlert filtered by the article_id column
 * @method     ChildAlert|null findOneByMaxPrice(int $alert_max_price) Return the first ChildAlert filtered by the alert_max_price column
 * @method     ChildAlert|null findOneByPubYear(int $alert_pub_year) Return the first ChildAlert filtered by the alert_pub_year column
 * @method     ChildAlert|null findOneByCondition(string $alert_condition) Return the first ChildAlert filtered by the alert_condition column
 * @method     ChildAlert|null findOneByInsert(string $alert_insert) Return the first ChildAlert filtered by the alert_insert column
 * @method     ChildAlert|null findOneByUpdate(string $alert_update) Return the first ChildAlert filtered by the alert_update column
 * @method     ChildAlert|null findOneByCreatedAt(string $alert_created) Return the first ChildAlert filtered by the alert_created column
 * @method     ChildAlert|null findOneByUpdatedAt(string $alert_updated) Return the first ChildAlert filtered by the alert_updated column *

 * @method     ChildAlert requirePk($key, ?ConnectionInterface $con = null) Return the ChildAlert by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAlert requireOne(?ConnectionInterface $con = null) Return the first ChildAlert matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAlert requireOneById(int $alert_id) Return the first ChildAlert filtered by the alert_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAlert requireOneByUserId(int $user_id) Return the first ChildAlert filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAlert requireOneByArticleId(int $article_id) Return the first ChildAlert filtered by the article_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAlert requireOneByMaxPrice(int $alert_max_price) Return the first ChildAlert filtered by the alert_max_price column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAlert requireOneByPubYear(int $alert_pub_year) Return the first ChildAlert filtered by the alert_pub_year column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAlert requireOneByCondition(string $alert_condition) Return the first ChildAlert filtered by the alert_condition column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAlert requireOneByInsert(string $alert_insert) Return the first ChildAlert filtered by the alert_insert column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAlert requireOneByUpdate(string $alert_update) Return the first ChildAlert filtered by the alert_update column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAlert requireOneByCreatedAt(string $alert_created) Return the first ChildAlert filtered by the alert_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAlert requireOneByUpdatedAt(string $alert_updated) Return the first ChildAlert filtered by the alert_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAlert[]|Collection find(?ConnectionInterface $con = null) Return ChildAlert objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildAlert> find(?ConnectionInterface $con = null) Return ChildAlert objects based on current ModelCriteria
 * @method     ChildAlert[]|Collection findById(int $alert_id) Return ChildAlert objects filtered by the alert_id column
 * @psalm-method Collection&\Traversable<ChildAlert> findById(int $alert_id) Return ChildAlert objects filtered by the alert_id column
 * @method     ChildAlert[]|Collection findByUserId(int $user_id) Return ChildAlert objects filtered by the user_id column
 * @psalm-method Collection&\Traversable<ChildAlert> findByUserId(int $user_id) Return ChildAlert objects filtered by the user_id column
 * @method     ChildAlert[]|Collection findByArticleId(int $article_id) Return ChildAlert objects filtered by the article_id column
 * @psalm-method Collection&\Traversable<ChildAlert> findByArticleId(int $article_id) Return ChildAlert objects filtered by the article_id column
 * @method     ChildAlert[]|Collection findByMaxPrice(int $alert_max_price) Return ChildAlert objects filtered by the alert_max_price column
 * @psalm-method Collection&\Traversable<ChildAlert> findByMaxPrice(int $alert_max_price) Return ChildAlert objects filtered by the alert_max_price column
 * @method     ChildAlert[]|Collection findByPubYear(int $alert_pub_year) Return ChildAlert objects filtered by the alert_pub_year column
 * @psalm-method Collection&\Traversable<ChildAlert> findByPubYear(int $alert_pub_year) Return ChildAlert objects filtered by the alert_pub_year column
 * @method     ChildAlert[]|Collection findByCondition(string $alert_condition) Return ChildAlert objects filtered by the alert_condition column
 * @psalm-method Collection&\Traversable<ChildAlert> findByCondition(string $alert_condition) Return ChildAlert objects filtered by the alert_condition column
 * @method     ChildAlert[]|Collection findByInsert(string $alert_insert) Return ChildAlert objects filtered by the alert_insert column
 * @psalm-method Collection&\Traversable<ChildAlert> findByInsert(string $alert_insert) Return ChildAlert objects filtered by the alert_insert column
 * @method     ChildAlert[]|Collection findByUpdate(string $alert_update) Return ChildAlert objects filtered by the alert_update column
 * @psalm-method Collection&\Traversable<ChildAlert> findByUpdate(string $alert_update) Return ChildAlert objects filtered by the alert_update column
 * @method     ChildAlert[]|Collection findByCreatedAt(string $alert_created) Return ChildAlert objects filtered by the alert_created column
 * @psalm-method Collection&\Traversable<ChildAlert> findByCreatedAt(string $alert_created) Return ChildAlert objects filtered by the alert_created column
 * @method     ChildAlert[]|Collection findByUpdatedAt(string $alert_updated) Return ChildAlert objects filtered by the alert_updated column
 * @psalm-method Collection&\Traversable<ChildAlert> findByUpdatedAt(string $alert_updated) Return ChildAlert objects filtered by the alert_updated column
 * @method     ChildAlert[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildAlert> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class AlertQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\AlertQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Alert', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildAlertQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildAlertQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildAlertQuery) {
            return $criteria;
        }
        $query = new ChildAlertQuery();
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
     * @return ChildAlert|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(AlertTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = AlertTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildAlert A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT alert_id, user_id, article_id, alert_max_price, alert_pub_year, alert_condition, alert_insert, alert_update, alert_created, alert_updated FROM alerts WHERE alert_id = :p0';
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
            /** @var ChildAlert $obj */
            $obj = new ChildAlert();
            $obj->hydrate($row);
            AlertTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildAlert|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(AlertTableMap::COL_ALERT_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(AlertTableMap::COL_ALERT_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the alert_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE alert_id = 1234
     * $query->filterById(array(12, 34)); // WHERE alert_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE alert_id > 12
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
                $this->addUsingAlias(AlertTableMap::COL_ALERT_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(AlertTableMap::COL_ALERT_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AlertTableMap::COL_ALERT_ID, $id, $comparison);

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
                $this->addUsingAlias(AlertTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(AlertTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AlertTableMap::COL_USER_ID, $userId, $comparison);

        return $this;
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
     * @param mixed $articleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByArticleId($articleId = null, ?string $comparison = null)
    {
        if (is_array($articleId)) {
            $useMinMax = false;
            if (isset($articleId['min'])) {
                $this->addUsingAlias(AlertTableMap::COL_ARTICLE_ID, $articleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($articleId['max'])) {
                $this->addUsingAlias(AlertTableMap::COL_ARTICLE_ID, $articleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AlertTableMap::COL_ARTICLE_ID, $articleId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the alert_max_price column
     *
     * Example usage:
     * <code>
     * $query->filterByMaxPrice(1234); // WHERE alert_max_price = 1234
     * $query->filterByMaxPrice(array(12, 34)); // WHERE alert_max_price IN (12, 34)
     * $query->filterByMaxPrice(array('min' => 12)); // WHERE alert_max_price > 12
     * </code>
     *
     * @param mixed $maxPrice The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByMaxPrice($maxPrice = null, ?string $comparison = null)
    {
        if (is_array($maxPrice)) {
            $useMinMax = false;
            if (isset($maxPrice['min'])) {
                $this->addUsingAlias(AlertTableMap::COL_ALERT_MAX_PRICE, $maxPrice['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($maxPrice['max'])) {
                $this->addUsingAlias(AlertTableMap::COL_ALERT_MAX_PRICE, $maxPrice['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AlertTableMap::COL_ALERT_MAX_PRICE, $maxPrice, $comparison);

        return $this;
    }

    /**
     * Filter the query on the alert_pub_year column
     *
     * Example usage:
     * <code>
     * $query->filterByPubYear(1234); // WHERE alert_pub_year = 1234
     * $query->filterByPubYear(array(12, 34)); // WHERE alert_pub_year IN (12, 34)
     * $query->filterByPubYear(array('min' => 12)); // WHERE alert_pub_year > 12
     * </code>
     *
     * @param mixed $pubYear The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPubYear($pubYear = null, ?string $comparison = null)
    {
        if (is_array($pubYear)) {
            $useMinMax = false;
            if (isset($pubYear['min'])) {
                $this->addUsingAlias(AlertTableMap::COL_ALERT_PUB_YEAR, $pubYear['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pubYear['max'])) {
                $this->addUsingAlias(AlertTableMap::COL_ALERT_PUB_YEAR, $pubYear['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AlertTableMap::COL_ALERT_PUB_YEAR, $pubYear, $comparison);

        return $this;
    }

    /**
     * Filter the query on the alert_condition column
     *
     * Example usage:
     * <code>
     * $query->filterByCondition('fooValue');   // WHERE alert_condition = 'fooValue'
     * $query->filterByCondition('%fooValue%', Criteria::LIKE); // WHERE alert_condition LIKE '%fooValue%'
     * $query->filterByCondition(['foo', 'bar']); // WHERE alert_condition IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $condition The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCondition($condition = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($condition)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AlertTableMap::COL_ALERT_CONDITION, $condition, $comparison);

        return $this;
    }

    /**
     * Filter the query on the alert_insert column
     *
     * Example usage:
     * <code>
     * $query->filterByInsert('2011-03-14'); // WHERE alert_insert = '2011-03-14'
     * $query->filterByInsert('now'); // WHERE alert_insert = '2011-03-14'
     * $query->filterByInsert(array('max' => 'yesterday')); // WHERE alert_insert > '2011-03-13'
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
                $this->addUsingAlias(AlertTableMap::COL_ALERT_INSERT, $insert['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($insert['max'])) {
                $this->addUsingAlias(AlertTableMap::COL_ALERT_INSERT, $insert['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AlertTableMap::COL_ALERT_INSERT, $insert, $comparison);

        return $this;
    }

    /**
     * Filter the query on the alert_update column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdate('2011-03-14'); // WHERE alert_update = '2011-03-14'
     * $query->filterByUpdate('now'); // WHERE alert_update = '2011-03-14'
     * $query->filterByUpdate(array('max' => 'yesterday')); // WHERE alert_update > '2011-03-13'
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
                $this->addUsingAlias(AlertTableMap::COL_ALERT_UPDATE, $update['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($update['max'])) {
                $this->addUsingAlias(AlertTableMap::COL_ALERT_UPDATE, $update['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AlertTableMap::COL_ALERT_UPDATE, $update, $comparison);

        return $this;
    }

    /**
     * Filter the query on the alert_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE alert_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE alert_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE alert_created > '2011-03-13'
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
                $this->addUsingAlias(AlertTableMap::COL_ALERT_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(AlertTableMap::COL_ALERT_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AlertTableMap::COL_ALERT_CREATED, $createdAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the alert_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE alert_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE alert_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE alert_updated > '2011-03-13'
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
                $this->addUsingAlias(AlertTableMap::COL_ALERT_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(AlertTableMap::COL_ALERT_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AlertTableMap::COL_ALERT_UPDATED, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Exclude object from result
     *
     * @param ChildAlert $alert Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($alert = null)
    {
        if ($alert) {
            $this->addUsingAlias(AlertTableMap::COL_ALERT_ID, $alert->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the alerts table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AlertTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            AlertTableMap::clearInstancePool();
            AlertTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(AlertTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(AlertTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            AlertTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            AlertTableMap::clearRelatedInstancePool();

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
        $this->addUsingAlias(AlertTableMap::COL_ALERT_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(AlertTableMap::COL_ALERT_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(AlertTableMap::COL_ALERT_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(AlertTableMap::COL_ALERT_CREATED);

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
        $this->addUsingAlias(AlertTableMap::COL_ALERT_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(AlertTableMap::COL_ALERT_CREATED);

        return $this;
    }

}
