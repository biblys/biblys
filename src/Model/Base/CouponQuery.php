<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Coupon as ChildCoupon;
use Model\CouponQuery as ChildCouponQuery;
use Model\Map\CouponTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `coupons` table.
 *
 * @method     ChildCouponQuery orderById($order = Criteria::ASC) Order by the coupon_id column
 * @method     ChildCouponQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildCouponQuery orderByAxysAccountId($order = Criteria::ASC) Order by the axys_account_id column
 * @method     ChildCouponQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildCouponQuery orderByCode($order = Criteria::ASC) Order by the coupon_code column
 * @method     ChildCouponQuery orderByArticleId($order = Criteria::ASC) Order by the article_id column
 * @method     ChildCouponQuery orderByStockId($order = Criteria::ASC) Order by the stock_id column
 * @method     ChildCouponQuery orderByAmount($order = Criteria::ASC) Order by the coupon_amount column
 * @method     ChildCouponQuery orderByNote($order = Criteria::ASC) Order by the coupon_note column
 * @method     ChildCouponQuery orderByUsed($order = Criteria::ASC) Order by the coupon_used column
 * @method     ChildCouponQuery orderByCreator($order = Criteria::ASC) Order by the coupon_creator column
 * @method     ChildCouponQuery orderByCreatedAt($order = Criteria::ASC) Order by the coupon_insert column
 * @method     ChildCouponQuery orderByUpdatedAt($order = Criteria::ASC) Order by the coupon_update column
 *
 * @method     ChildCouponQuery groupById() Group by the coupon_id column
 * @method     ChildCouponQuery groupBySiteId() Group by the site_id column
 * @method     ChildCouponQuery groupByAxysAccountId() Group by the axys_account_id column
 * @method     ChildCouponQuery groupByUserId() Group by the user_id column
 * @method     ChildCouponQuery groupByCode() Group by the coupon_code column
 * @method     ChildCouponQuery groupByArticleId() Group by the article_id column
 * @method     ChildCouponQuery groupByStockId() Group by the stock_id column
 * @method     ChildCouponQuery groupByAmount() Group by the coupon_amount column
 * @method     ChildCouponQuery groupByNote() Group by the coupon_note column
 * @method     ChildCouponQuery groupByUsed() Group by the coupon_used column
 * @method     ChildCouponQuery groupByCreator() Group by the coupon_creator column
 * @method     ChildCouponQuery groupByCreatedAt() Group by the coupon_insert column
 * @method     ChildCouponQuery groupByUpdatedAt() Group by the coupon_update column
 *
 * @method     ChildCouponQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCouponQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCouponQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCouponQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildCouponQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildCouponQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildCouponQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildCouponQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildCouponQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildCouponQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildCouponQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildCouponQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildCouponQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     \Model\UserQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildCoupon|null findOne(?ConnectionInterface $con = null) Return the first ChildCoupon matching the query
 * @method     ChildCoupon findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildCoupon matching the query, or a new ChildCoupon object populated from the query conditions when no match is found
 *
 * @method     ChildCoupon|null findOneById(int $coupon_id) Return the first ChildCoupon filtered by the coupon_id column
 * @method     ChildCoupon|null findOneBySiteId(int $site_id) Return the first ChildCoupon filtered by the site_id column
 * @method     ChildCoupon|null findOneByAxysAccountId(int $axys_account_id) Return the first ChildCoupon filtered by the axys_account_id column
 * @method     ChildCoupon|null findOneByUserId(int $user_id) Return the first ChildCoupon filtered by the user_id column
 * @method     ChildCoupon|null findOneByCode(string $coupon_code) Return the first ChildCoupon filtered by the coupon_code column
 * @method     ChildCoupon|null findOneByArticleId(int $article_id) Return the first ChildCoupon filtered by the article_id column
 * @method     ChildCoupon|null findOneByStockId(int $stock_id) Return the first ChildCoupon filtered by the stock_id column
 * @method     ChildCoupon|null findOneByAmount(int $coupon_amount) Return the first ChildCoupon filtered by the coupon_amount column
 * @method     ChildCoupon|null findOneByNote(string $coupon_note) Return the first ChildCoupon filtered by the coupon_note column
 * @method     ChildCoupon|null findOneByUsed(string $coupon_used) Return the first ChildCoupon filtered by the coupon_used column
 * @method     ChildCoupon|null findOneByCreator(int $coupon_creator) Return the first ChildCoupon filtered by the coupon_creator column
 * @method     ChildCoupon|null findOneByCreatedAt(string $coupon_insert) Return the first ChildCoupon filtered by the coupon_insert column
 * @method     ChildCoupon|null findOneByUpdatedAt(string $coupon_update) Return the first ChildCoupon filtered by the coupon_update column
 *
 * @method     ChildCoupon requirePk($key, ?ConnectionInterface $con = null) Return the ChildCoupon by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCoupon requireOne(?ConnectionInterface $con = null) Return the first ChildCoupon matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCoupon requireOneById(int $coupon_id) Return the first ChildCoupon filtered by the coupon_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCoupon requireOneBySiteId(int $site_id) Return the first ChildCoupon filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCoupon requireOneByAxysAccountId(int $axys_account_id) Return the first ChildCoupon filtered by the axys_account_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCoupon requireOneByUserId(int $user_id) Return the first ChildCoupon filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCoupon requireOneByCode(string $coupon_code) Return the first ChildCoupon filtered by the coupon_code column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCoupon requireOneByArticleId(int $article_id) Return the first ChildCoupon filtered by the article_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCoupon requireOneByStockId(int $stock_id) Return the first ChildCoupon filtered by the stock_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCoupon requireOneByAmount(int $coupon_amount) Return the first ChildCoupon filtered by the coupon_amount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCoupon requireOneByNote(string $coupon_note) Return the first ChildCoupon filtered by the coupon_note column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCoupon requireOneByUsed(string $coupon_used) Return the first ChildCoupon filtered by the coupon_used column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCoupon requireOneByCreator(int $coupon_creator) Return the first ChildCoupon filtered by the coupon_creator column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCoupon requireOneByCreatedAt(string $coupon_insert) Return the first ChildCoupon filtered by the coupon_insert column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCoupon requireOneByUpdatedAt(string $coupon_update) Return the first ChildCoupon filtered by the coupon_update column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCoupon[]|Collection find(?ConnectionInterface $con = null) Return ChildCoupon objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildCoupon> find(?ConnectionInterface $con = null) Return ChildCoupon objects based on current ModelCriteria
 *
 * @method     ChildCoupon[]|Collection findById(int|array<int> $coupon_id) Return ChildCoupon objects filtered by the coupon_id column
 * @psalm-method Collection&\Traversable<ChildCoupon> findById(int|array<int> $coupon_id) Return ChildCoupon objects filtered by the coupon_id column
 * @method     ChildCoupon[]|Collection findBySiteId(int|array<int> $site_id) Return ChildCoupon objects filtered by the site_id column
 * @psalm-method Collection&\Traversable<ChildCoupon> findBySiteId(int|array<int> $site_id) Return ChildCoupon objects filtered by the site_id column
 * @method     ChildCoupon[]|Collection findByAxysAccountId(int|array<int> $axys_account_id) Return ChildCoupon objects filtered by the axys_account_id column
 * @psalm-method Collection&\Traversable<ChildCoupon> findByAxysAccountId(int|array<int> $axys_account_id) Return ChildCoupon objects filtered by the axys_account_id column
 * @method     ChildCoupon[]|Collection findByUserId(int|array<int> $user_id) Return ChildCoupon objects filtered by the user_id column
 * @psalm-method Collection&\Traversable<ChildCoupon> findByUserId(int|array<int> $user_id) Return ChildCoupon objects filtered by the user_id column
 * @method     ChildCoupon[]|Collection findByCode(string|array<string> $coupon_code) Return ChildCoupon objects filtered by the coupon_code column
 * @psalm-method Collection&\Traversable<ChildCoupon> findByCode(string|array<string> $coupon_code) Return ChildCoupon objects filtered by the coupon_code column
 * @method     ChildCoupon[]|Collection findByArticleId(int|array<int> $article_id) Return ChildCoupon objects filtered by the article_id column
 * @psalm-method Collection&\Traversable<ChildCoupon> findByArticleId(int|array<int> $article_id) Return ChildCoupon objects filtered by the article_id column
 * @method     ChildCoupon[]|Collection findByStockId(int|array<int> $stock_id) Return ChildCoupon objects filtered by the stock_id column
 * @psalm-method Collection&\Traversable<ChildCoupon> findByStockId(int|array<int> $stock_id) Return ChildCoupon objects filtered by the stock_id column
 * @method     ChildCoupon[]|Collection findByAmount(int|array<int> $coupon_amount) Return ChildCoupon objects filtered by the coupon_amount column
 * @psalm-method Collection&\Traversable<ChildCoupon> findByAmount(int|array<int> $coupon_amount) Return ChildCoupon objects filtered by the coupon_amount column
 * @method     ChildCoupon[]|Collection findByNote(string|array<string> $coupon_note) Return ChildCoupon objects filtered by the coupon_note column
 * @psalm-method Collection&\Traversable<ChildCoupon> findByNote(string|array<string> $coupon_note) Return ChildCoupon objects filtered by the coupon_note column
 * @method     ChildCoupon[]|Collection findByUsed(string|array<string> $coupon_used) Return ChildCoupon objects filtered by the coupon_used column
 * @psalm-method Collection&\Traversable<ChildCoupon> findByUsed(string|array<string> $coupon_used) Return ChildCoupon objects filtered by the coupon_used column
 * @method     ChildCoupon[]|Collection findByCreator(int|array<int> $coupon_creator) Return ChildCoupon objects filtered by the coupon_creator column
 * @psalm-method Collection&\Traversable<ChildCoupon> findByCreator(int|array<int> $coupon_creator) Return ChildCoupon objects filtered by the coupon_creator column
 * @method     ChildCoupon[]|Collection findByCreatedAt(string|array<string> $coupon_insert) Return ChildCoupon objects filtered by the coupon_insert column
 * @psalm-method Collection&\Traversable<ChildCoupon> findByCreatedAt(string|array<string> $coupon_insert) Return ChildCoupon objects filtered by the coupon_insert column
 * @method     ChildCoupon[]|Collection findByUpdatedAt(string|array<string> $coupon_update) Return ChildCoupon objects filtered by the coupon_update column
 * @psalm-method Collection&\Traversable<ChildCoupon> findByUpdatedAt(string|array<string> $coupon_update) Return ChildCoupon objects filtered by the coupon_update column
 *
 * @method     ChildCoupon[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildCoupon> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class CouponQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\CouponQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Coupon', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCouponQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCouponQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildCouponQuery) {
            return $criteria;
        }
        $query = new ChildCouponQuery();
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
     * @return ChildCoupon|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CouponTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = CouponTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildCoupon A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT coupon_id, site_id, axys_account_id, user_id, coupon_code, article_id, stock_id, coupon_amount, coupon_note, coupon_used, coupon_creator, coupon_insert, coupon_update FROM coupons WHERE coupon_id = :p0';
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
            /** @var ChildCoupon $obj */
            $obj = new ChildCoupon();
            $obj->hydrate($row);
            CouponTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildCoupon|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(CouponTableMap::COL_COUPON_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(CouponTableMap::COL_COUPON_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the coupon_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE coupon_id = 1234
     * $query->filterById(array(12, 34)); // WHERE coupon_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE coupon_id > 12
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
                $this->addUsingAlias(CouponTableMap::COL_COUPON_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CouponTableMap::COL_COUPON_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CouponTableMap::COL_COUPON_ID, $id, $comparison);

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
                $this->addUsingAlias(CouponTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(CouponTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CouponTableMap::COL_SITE_ID, $siteId, $comparison);

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
                $this->addUsingAlias(CouponTableMap::COL_AXYS_ACCOUNT_ID, $axysAccountId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($axysAccountId['max'])) {
                $this->addUsingAlias(CouponTableMap::COL_AXYS_ACCOUNT_ID, $axysAccountId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CouponTableMap::COL_AXYS_ACCOUNT_ID, $axysAccountId, $comparison);

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
                $this->addUsingAlias(CouponTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(CouponTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CouponTableMap::COL_USER_ID, $userId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the coupon_code column
     *
     * Example usage:
     * <code>
     * $query->filterByCode('fooValue');   // WHERE coupon_code = 'fooValue'
     * $query->filterByCode('%fooValue%', Criteria::LIKE); // WHERE coupon_code LIKE '%fooValue%'
     * $query->filterByCode(['foo', 'bar']); // WHERE coupon_code IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $code The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCode($code = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($code)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CouponTableMap::COL_COUPON_CODE, $code, $comparison);

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
                $this->addUsingAlias(CouponTableMap::COL_ARTICLE_ID, $articleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($articleId['max'])) {
                $this->addUsingAlias(CouponTableMap::COL_ARTICLE_ID, $articleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CouponTableMap::COL_ARTICLE_ID, $articleId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the stock_id column
     *
     * Example usage:
     * <code>
     * $query->filterByStockId(1234); // WHERE stock_id = 1234
     * $query->filterByStockId(array(12, 34)); // WHERE stock_id IN (12, 34)
     * $query->filterByStockId(array('min' => 12)); // WHERE stock_id > 12
     * </code>
     *
     * @param mixed $stockId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByStockId($stockId = null, ?string $comparison = null)
    {
        if (is_array($stockId)) {
            $useMinMax = false;
            if (isset($stockId['min'])) {
                $this->addUsingAlias(CouponTableMap::COL_STOCK_ID, $stockId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($stockId['max'])) {
                $this->addUsingAlias(CouponTableMap::COL_STOCK_ID, $stockId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CouponTableMap::COL_STOCK_ID, $stockId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the coupon_amount column
     *
     * Example usage:
     * <code>
     * $query->filterByAmount(1234); // WHERE coupon_amount = 1234
     * $query->filterByAmount(array(12, 34)); // WHERE coupon_amount IN (12, 34)
     * $query->filterByAmount(array('min' => 12)); // WHERE coupon_amount > 12
     * </code>
     *
     * @param mixed $amount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAmount($amount = null, ?string $comparison = null)
    {
        if (is_array($amount)) {
            $useMinMax = false;
            if (isset($amount['min'])) {
                $this->addUsingAlias(CouponTableMap::COL_COUPON_AMOUNT, $amount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($amount['max'])) {
                $this->addUsingAlias(CouponTableMap::COL_COUPON_AMOUNT, $amount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CouponTableMap::COL_COUPON_AMOUNT, $amount, $comparison);

        return $this;
    }

    /**
     * Filter the query on the coupon_note column
     *
     * Example usage:
     * <code>
     * $query->filterByNote('fooValue');   // WHERE coupon_note = 'fooValue'
     * $query->filterByNote('%fooValue%', Criteria::LIKE); // WHERE coupon_note LIKE '%fooValue%'
     * $query->filterByNote(['foo', 'bar']); // WHERE coupon_note IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $note The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByNote($note = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($note)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CouponTableMap::COL_COUPON_NOTE, $note, $comparison);

        return $this;
    }

    /**
     * Filter the query on the coupon_used column
     *
     * Example usage:
     * <code>
     * $query->filterByUsed('2011-03-14'); // WHERE coupon_used = '2011-03-14'
     * $query->filterByUsed('now'); // WHERE coupon_used = '2011-03-14'
     * $query->filterByUsed(array('max' => 'yesterday')); // WHERE coupon_used > '2011-03-13'
     * </code>
     *
     * @param mixed $used The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUsed($used = null, ?string $comparison = null)
    {
        if (is_array($used)) {
            $useMinMax = false;
            if (isset($used['min'])) {
                $this->addUsingAlias(CouponTableMap::COL_COUPON_USED, $used['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($used['max'])) {
                $this->addUsingAlias(CouponTableMap::COL_COUPON_USED, $used['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CouponTableMap::COL_COUPON_USED, $used, $comparison);

        return $this;
    }

    /**
     * Filter the query on the coupon_creator column
     *
     * Example usage:
     * <code>
     * $query->filterByCreator(1234); // WHERE coupon_creator = 1234
     * $query->filterByCreator(array(12, 34)); // WHERE coupon_creator IN (12, 34)
     * $query->filterByCreator(array('min' => 12)); // WHERE coupon_creator > 12
     * </code>
     *
     * @param mixed $creator The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCreator($creator = null, ?string $comparison = null)
    {
        if (is_array($creator)) {
            $useMinMax = false;
            if (isset($creator['min'])) {
                $this->addUsingAlias(CouponTableMap::COL_COUPON_CREATOR, $creator['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($creator['max'])) {
                $this->addUsingAlias(CouponTableMap::COL_COUPON_CREATOR, $creator['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CouponTableMap::COL_COUPON_CREATOR, $creator, $comparison);

        return $this;
    }

    /**
     * Filter the query on the coupon_insert column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE coupon_insert = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE coupon_insert = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE coupon_insert > '2011-03-13'
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
                $this->addUsingAlias(CouponTableMap::COL_COUPON_INSERT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(CouponTableMap::COL_COUPON_INSERT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CouponTableMap::COL_COUPON_INSERT, $createdAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the coupon_update column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE coupon_update = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE coupon_update = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE coupon_update > '2011-03-13'
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
                $this->addUsingAlias(CouponTableMap::COL_COUPON_UPDATE, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(CouponTableMap::COL_COUPON_UPDATE, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CouponTableMap::COL_COUPON_UPDATE, $updatedAt, $comparison);

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
                ->addUsingAlias(CouponTableMap::COL_USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(CouponTableMap::COL_USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);

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
     * @param ChildCoupon $coupon Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($coupon = null)
    {
        if ($coupon) {
            $this->addUsingAlias(CouponTableMap::COL_COUPON_ID, $coupon->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the coupons table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CouponTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CouponTableMap::clearInstancePool();
            CouponTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(CouponTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CouponTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            CouponTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CouponTableMap::clearRelatedInstancePool();

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
        $this->addUsingAlias(CouponTableMap::COL_COUPON_UPDATE, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(CouponTableMap::COL_COUPON_UPDATE);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(CouponTableMap::COL_COUPON_UPDATE);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(CouponTableMap::COL_COUPON_INSERT);

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
        $this->addUsingAlias(CouponTableMap::COL_COUPON_INSERT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(CouponTableMap::COL_COUPON_INSERT);

        return $this;
    }

}
