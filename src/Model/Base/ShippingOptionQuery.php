<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\ShippingOption as ChildShippingOption;
use Model\ShippingOptionQuery as ChildShippingOptionQuery;
use Model\Map\ShippingOptionTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `shipping` table.
 *
 * @method     ChildShippingOptionQuery orderById($order = Criteria::ASC) Order by the shipping_id column
 * @method     ChildShippingOptionQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildShippingOptionQuery orderByArticleId($order = Criteria::ASC) Order by the article_id column
 * @method     ChildShippingOptionQuery orderByMode($order = Criteria::ASC) Order by the shipping_mode column
 * @method     ChildShippingOptionQuery orderByType($order = Criteria::ASC) Order by the shipping_type column
 * @method     ChildShippingOptionQuery orderByShippingZoneId($order = Criteria::ASC) Order by the shipping_zone_id column
 * @method     ChildShippingOptionQuery orderByMinWeight($order = Criteria::ASC) Order by the shipping_min_weight column
 * @method     ChildShippingOptionQuery orderByMaxWeight($order = Criteria::ASC) Order by the shipping_max_weight column
 * @method     ChildShippingOptionQuery orderByMaxArticles($order = Criteria::ASC) Order by the shipping_max_articles column
 * @method     ChildShippingOptionQuery orderByMinAmount($order = Criteria::ASC) Order by the shipping_min_amount column
 * @method     ChildShippingOptionQuery orderByMaxAmount($order = Criteria::ASC) Order by the shipping_max_amount column
 * @method     ChildShippingOptionQuery orderByFee($order = Criteria::ASC) Order by the shipping_fee column
 * @method     ChildShippingOptionQuery orderByInfo($order = Criteria::ASC) Order by the shipping_info column
 * @method     ChildShippingOptionQuery orderByCreatedAt($order = Criteria::ASC) Order by the shipping_created column
 * @method     ChildShippingOptionQuery orderByUpdatedAt($order = Criteria::ASC) Order by the shipping_updated column
 * @method     ChildShippingOptionQuery orderByArchivedAt($order = Criteria::ASC) Order by the shipping_archived_at column
 *
 * @method     ChildShippingOptionQuery groupById() Group by the shipping_id column
 * @method     ChildShippingOptionQuery groupBySiteId() Group by the site_id column
 * @method     ChildShippingOptionQuery groupByArticleId() Group by the article_id column
 * @method     ChildShippingOptionQuery groupByMode() Group by the shipping_mode column
 * @method     ChildShippingOptionQuery groupByType() Group by the shipping_type column
 * @method     ChildShippingOptionQuery groupByShippingZoneId() Group by the shipping_zone_id column
 * @method     ChildShippingOptionQuery groupByMinWeight() Group by the shipping_min_weight column
 * @method     ChildShippingOptionQuery groupByMaxWeight() Group by the shipping_max_weight column
 * @method     ChildShippingOptionQuery groupByMaxArticles() Group by the shipping_max_articles column
 * @method     ChildShippingOptionQuery groupByMinAmount() Group by the shipping_min_amount column
 * @method     ChildShippingOptionQuery groupByMaxAmount() Group by the shipping_max_amount column
 * @method     ChildShippingOptionQuery groupByFee() Group by the shipping_fee column
 * @method     ChildShippingOptionQuery groupByInfo() Group by the shipping_info column
 * @method     ChildShippingOptionQuery groupByCreatedAt() Group by the shipping_created column
 * @method     ChildShippingOptionQuery groupByUpdatedAt() Group by the shipping_updated column
 * @method     ChildShippingOptionQuery groupByArchivedAt() Group by the shipping_archived_at column
 *
 * @method     ChildShippingOptionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildShippingOptionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildShippingOptionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildShippingOptionQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildShippingOptionQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildShippingOptionQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildShippingOptionQuery leftJoinShippingZone($relationAlias = null) Adds a LEFT JOIN clause to the query using the ShippingZone relation
 * @method     ChildShippingOptionQuery rightJoinShippingZone($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ShippingZone relation
 * @method     ChildShippingOptionQuery innerJoinShippingZone($relationAlias = null) Adds a INNER JOIN clause to the query using the ShippingZone relation
 *
 * @method     ChildShippingOptionQuery joinWithShippingZone($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ShippingZone relation
 *
 * @method     ChildShippingOptionQuery leftJoinWithShippingZone() Adds a LEFT JOIN clause and with to the query using the ShippingZone relation
 * @method     ChildShippingOptionQuery rightJoinWithShippingZone() Adds a RIGHT JOIN clause and with to the query using the ShippingZone relation
 * @method     ChildShippingOptionQuery innerJoinWithShippingZone() Adds a INNER JOIN clause and with to the query using the ShippingZone relation
 *
 * @method     ChildShippingOptionQuery leftJoinOrder($relationAlias = null) Adds a LEFT JOIN clause to the query using the Order relation
 * @method     ChildShippingOptionQuery rightJoinOrder($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Order relation
 * @method     ChildShippingOptionQuery innerJoinOrder($relationAlias = null) Adds a INNER JOIN clause to the query using the Order relation
 *
 * @method     ChildShippingOptionQuery joinWithOrder($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Order relation
 *
 * @method     ChildShippingOptionQuery leftJoinWithOrder() Adds a LEFT JOIN clause and with to the query using the Order relation
 * @method     ChildShippingOptionQuery rightJoinWithOrder() Adds a RIGHT JOIN clause and with to the query using the Order relation
 * @method     ChildShippingOptionQuery innerJoinWithOrder() Adds a INNER JOIN clause and with to the query using the Order relation
 *
 * @method     \Model\ShippingZoneQuery|\Model\OrderQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildShippingOption|null findOne(?ConnectionInterface $con = null) Return the first ChildShippingOption matching the query
 * @method     ChildShippingOption findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildShippingOption matching the query, or a new ChildShippingOption object populated from the query conditions when no match is found
 *
 * @method     ChildShippingOption|null findOneById(int $shipping_id) Return the first ChildShippingOption filtered by the shipping_id column
 * @method     ChildShippingOption|null findOneBySiteId(int $site_id) Return the first ChildShippingOption filtered by the site_id column
 * @method     ChildShippingOption|null findOneByArticleId(int $article_id) Return the first ChildShippingOption filtered by the article_id column
 * @method     ChildShippingOption|null findOneByMode(string $shipping_mode) Return the first ChildShippingOption filtered by the shipping_mode column
 * @method     ChildShippingOption|null findOneByType(string $shipping_type) Return the first ChildShippingOption filtered by the shipping_type column
 * @method     ChildShippingOption|null findOneByShippingZoneId(int $shipping_zone_id) Return the first ChildShippingOption filtered by the shipping_zone_id column
 * @method     ChildShippingOption|null findOneByMinWeight(int $shipping_min_weight) Return the first ChildShippingOption filtered by the shipping_min_weight column
 * @method     ChildShippingOption|null findOneByMaxWeight(int $shipping_max_weight) Return the first ChildShippingOption filtered by the shipping_max_weight column
 * @method     ChildShippingOption|null findOneByMaxArticles(int $shipping_max_articles) Return the first ChildShippingOption filtered by the shipping_max_articles column
 * @method     ChildShippingOption|null findOneByMinAmount(int $shipping_min_amount) Return the first ChildShippingOption filtered by the shipping_min_amount column
 * @method     ChildShippingOption|null findOneByMaxAmount(int $shipping_max_amount) Return the first ChildShippingOption filtered by the shipping_max_amount column
 * @method     ChildShippingOption|null findOneByFee(int $shipping_fee) Return the first ChildShippingOption filtered by the shipping_fee column
 * @method     ChildShippingOption|null findOneByInfo(string $shipping_info) Return the first ChildShippingOption filtered by the shipping_info column
 * @method     ChildShippingOption|null findOneByCreatedAt(string $shipping_created) Return the first ChildShippingOption filtered by the shipping_created column
 * @method     ChildShippingOption|null findOneByUpdatedAt(string $shipping_updated) Return the first ChildShippingOption filtered by the shipping_updated column
 * @method     ChildShippingOption|null findOneByArchivedAt(string $shipping_archived_at) Return the first ChildShippingOption filtered by the shipping_archived_at column
 *
 * @method     ChildShippingOption requirePk($key, ?ConnectionInterface $con = null) Return the ChildShippingOption by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingOption requireOne(?ConnectionInterface $con = null) Return the first ChildShippingOption matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildShippingOption requireOneById(int $shipping_id) Return the first ChildShippingOption filtered by the shipping_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingOption requireOneBySiteId(int $site_id) Return the first ChildShippingOption filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingOption requireOneByArticleId(int $article_id) Return the first ChildShippingOption filtered by the article_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingOption requireOneByMode(string $shipping_mode) Return the first ChildShippingOption filtered by the shipping_mode column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingOption requireOneByType(string $shipping_type) Return the first ChildShippingOption filtered by the shipping_type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingOption requireOneByShippingZoneId(int $shipping_zone_id) Return the first ChildShippingOption filtered by the shipping_zone_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingOption requireOneByMinWeight(int $shipping_min_weight) Return the first ChildShippingOption filtered by the shipping_min_weight column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingOption requireOneByMaxWeight(int $shipping_max_weight) Return the first ChildShippingOption filtered by the shipping_max_weight column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingOption requireOneByMaxArticles(int $shipping_max_articles) Return the first ChildShippingOption filtered by the shipping_max_articles column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingOption requireOneByMinAmount(int $shipping_min_amount) Return the first ChildShippingOption filtered by the shipping_min_amount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingOption requireOneByMaxAmount(int $shipping_max_amount) Return the first ChildShippingOption filtered by the shipping_max_amount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingOption requireOneByFee(int $shipping_fee) Return the first ChildShippingOption filtered by the shipping_fee column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingOption requireOneByInfo(string $shipping_info) Return the first ChildShippingOption filtered by the shipping_info column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingOption requireOneByCreatedAt(string $shipping_created) Return the first ChildShippingOption filtered by the shipping_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingOption requireOneByUpdatedAt(string $shipping_updated) Return the first ChildShippingOption filtered by the shipping_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingOption requireOneByArchivedAt(string $shipping_archived_at) Return the first ChildShippingOption filtered by the shipping_archived_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildShippingOption[]|Collection find(?ConnectionInterface $con = null) Return ChildShippingOption objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildShippingOption> find(?ConnectionInterface $con = null) Return ChildShippingOption objects based on current ModelCriteria
 *
 * @method     ChildShippingOption[]|Collection findById(int|array<int> $shipping_id) Return ChildShippingOption objects filtered by the shipping_id column
 * @psalm-method Collection&\Traversable<ChildShippingOption> findById(int|array<int> $shipping_id) Return ChildShippingOption objects filtered by the shipping_id column
 * @method     ChildShippingOption[]|Collection findBySiteId(int|array<int> $site_id) Return ChildShippingOption objects filtered by the site_id column
 * @psalm-method Collection&\Traversable<ChildShippingOption> findBySiteId(int|array<int> $site_id) Return ChildShippingOption objects filtered by the site_id column
 * @method     ChildShippingOption[]|Collection findByArticleId(int|array<int> $article_id) Return ChildShippingOption objects filtered by the article_id column
 * @psalm-method Collection&\Traversable<ChildShippingOption> findByArticleId(int|array<int> $article_id) Return ChildShippingOption objects filtered by the article_id column
 * @method     ChildShippingOption[]|Collection findByMode(string|array<string> $shipping_mode) Return ChildShippingOption objects filtered by the shipping_mode column
 * @psalm-method Collection&\Traversable<ChildShippingOption> findByMode(string|array<string> $shipping_mode) Return ChildShippingOption objects filtered by the shipping_mode column
 * @method     ChildShippingOption[]|Collection findByType(string|array<string> $shipping_type) Return ChildShippingOption objects filtered by the shipping_type column
 * @psalm-method Collection&\Traversable<ChildShippingOption> findByType(string|array<string> $shipping_type) Return ChildShippingOption objects filtered by the shipping_type column
 * @method     ChildShippingOption[]|Collection findByShippingZoneId(int|array<int> $shipping_zone_id) Return ChildShippingOption objects filtered by the shipping_zone_id column
 * @psalm-method Collection&\Traversable<ChildShippingOption> findByShippingZoneId(int|array<int> $shipping_zone_id) Return ChildShippingOption objects filtered by the shipping_zone_id column
 * @method     ChildShippingOption[]|Collection findByMinWeight(int|array<int> $shipping_min_weight) Return ChildShippingOption objects filtered by the shipping_min_weight column
 * @psalm-method Collection&\Traversable<ChildShippingOption> findByMinWeight(int|array<int> $shipping_min_weight) Return ChildShippingOption objects filtered by the shipping_min_weight column
 * @method     ChildShippingOption[]|Collection findByMaxWeight(int|array<int> $shipping_max_weight) Return ChildShippingOption objects filtered by the shipping_max_weight column
 * @psalm-method Collection&\Traversable<ChildShippingOption> findByMaxWeight(int|array<int> $shipping_max_weight) Return ChildShippingOption objects filtered by the shipping_max_weight column
 * @method     ChildShippingOption[]|Collection findByMaxArticles(int|array<int> $shipping_max_articles) Return ChildShippingOption objects filtered by the shipping_max_articles column
 * @psalm-method Collection&\Traversable<ChildShippingOption> findByMaxArticles(int|array<int> $shipping_max_articles) Return ChildShippingOption objects filtered by the shipping_max_articles column
 * @method     ChildShippingOption[]|Collection findByMinAmount(int|array<int> $shipping_min_amount) Return ChildShippingOption objects filtered by the shipping_min_amount column
 * @psalm-method Collection&\Traversable<ChildShippingOption> findByMinAmount(int|array<int> $shipping_min_amount) Return ChildShippingOption objects filtered by the shipping_min_amount column
 * @method     ChildShippingOption[]|Collection findByMaxAmount(int|array<int> $shipping_max_amount) Return ChildShippingOption objects filtered by the shipping_max_amount column
 * @psalm-method Collection&\Traversable<ChildShippingOption> findByMaxAmount(int|array<int> $shipping_max_amount) Return ChildShippingOption objects filtered by the shipping_max_amount column
 * @method     ChildShippingOption[]|Collection findByFee(int|array<int> $shipping_fee) Return ChildShippingOption objects filtered by the shipping_fee column
 * @psalm-method Collection&\Traversable<ChildShippingOption> findByFee(int|array<int> $shipping_fee) Return ChildShippingOption objects filtered by the shipping_fee column
 * @method     ChildShippingOption[]|Collection findByInfo(string|array<string> $shipping_info) Return ChildShippingOption objects filtered by the shipping_info column
 * @psalm-method Collection&\Traversable<ChildShippingOption> findByInfo(string|array<string> $shipping_info) Return ChildShippingOption objects filtered by the shipping_info column
 * @method     ChildShippingOption[]|Collection findByCreatedAt(string|array<string> $shipping_created) Return ChildShippingOption objects filtered by the shipping_created column
 * @psalm-method Collection&\Traversable<ChildShippingOption> findByCreatedAt(string|array<string> $shipping_created) Return ChildShippingOption objects filtered by the shipping_created column
 * @method     ChildShippingOption[]|Collection findByUpdatedAt(string|array<string> $shipping_updated) Return ChildShippingOption objects filtered by the shipping_updated column
 * @psalm-method Collection&\Traversable<ChildShippingOption> findByUpdatedAt(string|array<string> $shipping_updated) Return ChildShippingOption objects filtered by the shipping_updated column
 * @method     ChildShippingOption[]|Collection findByArchivedAt(string|array<string> $shipping_archived_at) Return ChildShippingOption objects filtered by the shipping_archived_at column
 * @psalm-method Collection&\Traversable<ChildShippingOption> findByArchivedAt(string|array<string> $shipping_archived_at) Return ChildShippingOption objects filtered by the shipping_archived_at column
 *
 * @method     ChildShippingOption[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildShippingOption> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class ShippingOptionQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\ShippingOptionQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\ShippingOption', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildShippingOptionQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildShippingOptionQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildShippingOptionQuery) {
            return $criteria;
        }
        $query = new ChildShippingOptionQuery();
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
     * @return ChildShippingOption|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ShippingOptionTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ShippingOptionTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildShippingOption A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT shipping_id, site_id, article_id, shipping_mode, shipping_type, shipping_zone_id, shipping_min_weight, shipping_max_weight, shipping_max_articles, shipping_min_amount, shipping_max_amount, shipping_fee, shipping_info, shipping_created, shipping_updated, shipping_archived_at FROM shipping WHERE shipping_id = :p0';
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
            /** @var ChildShippingOption $obj */
            $obj = new ChildShippingOption();
            $obj->hydrate($row);
            ShippingOptionTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildShippingOption|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_ID, $keys, Criteria::IN);

        return $this;
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
                $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_ID, $id, $comparison);

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
                $this->addUsingAlias(ShippingOptionTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(ShippingOptionTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ShippingOptionTableMap::COL_SITE_ID, $siteId, $comparison);

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
                $this->addUsingAlias(ShippingOptionTableMap::COL_ARTICLE_ID, $articleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($articleId['max'])) {
                $this->addUsingAlias(ShippingOptionTableMap::COL_ARTICLE_ID, $articleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ShippingOptionTableMap::COL_ARTICLE_ID, $articleId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the shipping_mode column
     *
     * Example usage:
     * <code>
     * $query->filterByMode('fooValue');   // WHERE shipping_mode = 'fooValue'
     * $query->filterByMode('%fooValue%', Criteria::LIKE); // WHERE shipping_mode LIKE '%fooValue%'
     * $query->filterByMode(['foo', 'bar']); // WHERE shipping_mode IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $mode The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByMode($mode = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($mode)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_MODE, $mode, $comparison);

        return $this;
    }

    /**
     * Filter the query on the shipping_type column
     *
     * Example usage:
     * <code>
     * $query->filterByType('fooValue');   // WHERE shipping_type = 'fooValue'
     * $query->filterByType('%fooValue%', Criteria::LIKE); // WHERE shipping_type LIKE '%fooValue%'
     * $query->filterByType(['foo', 'bar']); // WHERE shipping_type IN ('foo', 'bar')
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

        $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_TYPE, $type, $comparison);

        return $this;
    }

    /**
     * Filter the query on the shipping_zone_id column
     *
     * Example usage:
     * <code>
     * $query->filterByShippingZoneId(1234); // WHERE shipping_zone_id = 1234
     * $query->filterByShippingZoneId(array(12, 34)); // WHERE shipping_zone_id IN (12, 34)
     * $query->filterByShippingZoneId(array('min' => 12)); // WHERE shipping_zone_id > 12
     * </code>
     *
     * @see       filterByShippingZone()
     *
     * @param mixed $shippingZoneId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByShippingZoneId($shippingZoneId = null, ?string $comparison = null)
    {
        if (is_array($shippingZoneId)) {
            $useMinMax = false;
            if (isset($shippingZoneId['min'])) {
                $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_ZONE_ID, $shippingZoneId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($shippingZoneId['max'])) {
                $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_ZONE_ID, $shippingZoneId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_ZONE_ID, $shippingZoneId, $comparison);

        return $this;
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
     * @param mixed $minWeight The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByMinWeight($minWeight = null, ?string $comparison = null)
    {
        if (is_array($minWeight)) {
            $useMinMax = false;
            if (isset($minWeight['min'])) {
                $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_MIN_WEIGHT, $minWeight['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($minWeight['max'])) {
                $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_MIN_WEIGHT, $minWeight['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_MIN_WEIGHT, $minWeight, $comparison);

        return $this;
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
     * @param mixed $maxWeight The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByMaxWeight($maxWeight = null, ?string $comparison = null)
    {
        if (is_array($maxWeight)) {
            $useMinMax = false;
            if (isset($maxWeight['min'])) {
                $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_MAX_WEIGHT, $maxWeight['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($maxWeight['max'])) {
                $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_MAX_WEIGHT, $maxWeight['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_MAX_WEIGHT, $maxWeight, $comparison);

        return $this;
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
     * @param mixed $maxArticles The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByMaxArticles($maxArticles = null, ?string $comparison = null)
    {
        if (is_array($maxArticles)) {
            $useMinMax = false;
            if (isset($maxArticles['min'])) {
                $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_MAX_ARTICLES, $maxArticles['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($maxArticles['max'])) {
                $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_MAX_ARTICLES, $maxArticles['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_MAX_ARTICLES, $maxArticles, $comparison);

        return $this;
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
     * @param mixed $minAmount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByMinAmount($minAmount = null, ?string $comparison = null)
    {
        if (is_array($minAmount)) {
            $useMinMax = false;
            if (isset($minAmount['min'])) {
                $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_MIN_AMOUNT, $minAmount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($minAmount['max'])) {
                $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_MIN_AMOUNT, $minAmount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_MIN_AMOUNT, $minAmount, $comparison);

        return $this;
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
     * @param mixed $maxAmount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByMaxAmount($maxAmount = null, ?string $comparison = null)
    {
        if (is_array($maxAmount)) {
            $useMinMax = false;
            if (isset($maxAmount['min'])) {
                $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_MAX_AMOUNT, $maxAmount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($maxAmount['max'])) {
                $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_MAX_AMOUNT, $maxAmount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_MAX_AMOUNT, $maxAmount, $comparison);

        return $this;
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
     * @param mixed $fee The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFee($fee = null, ?string $comparison = null)
    {
        if (is_array($fee)) {
            $useMinMax = false;
            if (isset($fee['min'])) {
                $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_FEE, $fee['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($fee['max'])) {
                $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_FEE, $fee['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_FEE, $fee, $comparison);

        return $this;
    }

    /**
     * Filter the query on the shipping_info column
     *
     * Example usage:
     * <code>
     * $query->filterByInfo('fooValue');   // WHERE shipping_info = 'fooValue'
     * $query->filterByInfo('%fooValue%', Criteria::LIKE); // WHERE shipping_info LIKE '%fooValue%'
     * $query->filterByInfo(['foo', 'bar']); // WHERE shipping_info IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $info The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByInfo($info = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($info)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_INFO, $info, $comparison);

        return $this;
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
                $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_CREATED, $createdAt, $comparison);

        return $this;
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
                $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_UPDATED, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the shipping_archived_at column
     *
     * Example usage:
     * <code>
     * $query->filterByArchivedAt('2011-03-14'); // WHERE shipping_archived_at = '2011-03-14'
     * $query->filterByArchivedAt('now'); // WHERE shipping_archived_at = '2011-03-14'
     * $query->filterByArchivedAt(array('max' => 'yesterday')); // WHERE shipping_archived_at > '2011-03-13'
     * </code>
     *
     * @param mixed $archivedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByArchivedAt($archivedAt = null, ?string $comparison = null)
    {
        if (is_array($archivedAt)) {
            $useMinMax = false;
            if (isset($archivedAt['min'])) {
                $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_ARCHIVED_AT, $archivedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($archivedAt['max'])) {
                $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_ARCHIVED_AT, $archivedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_ARCHIVED_AT, $archivedAt, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \Model\ShippingZone object
     *
     * @param \Model\ShippingZone|ObjectCollection $shippingZone The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByShippingZone($shippingZone, ?string $comparison = null)
    {
        if ($shippingZone instanceof \Model\ShippingZone) {
            return $this
                ->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_ZONE_ID, $shippingZone->getId(), $comparison);
        } elseif ($shippingZone instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_ZONE_ID, $shippingZone->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByShippingZone() only accepts arguments of type \Model\ShippingZone or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ShippingZone relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinShippingZone(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ShippingZone');

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
            $this->addJoinObject($join, 'ShippingZone');
        }

        return $this;
    }

    /**
     * Use the ShippingZone relation ShippingZone object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\ShippingZoneQuery A secondary query class using the current class as primary query
     */
    public function useShippingZoneQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinShippingZone($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ShippingZone', '\Model\ShippingZoneQuery');
    }

    /**
     * Use the ShippingZone relation ShippingZone object
     *
     * @param callable(\Model\ShippingZoneQuery):\Model\ShippingZoneQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withShippingZoneQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useShippingZoneQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to ShippingZone table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\ShippingZoneQuery The inner query object of the EXISTS statement
     */
    public function useShippingZoneExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\ShippingZoneQuery */
        $q = $this->useExistsQuery('ShippingZone', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to ShippingZone table for a NOT EXISTS query.
     *
     * @see useShippingZoneExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\ShippingZoneQuery The inner query object of the NOT EXISTS statement
     */
    public function useShippingZoneNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\ShippingZoneQuery */
        $q = $this->useExistsQuery('ShippingZone', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to ShippingZone table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\ShippingZoneQuery The inner query object of the IN statement
     */
    public function useInShippingZoneQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\ShippingZoneQuery */
        $q = $this->useInQuery('ShippingZone', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to ShippingZone table for a NOT IN query.
     *
     * @see useShippingZoneInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\ShippingZoneQuery The inner query object of the NOT IN statement
     */
    public function useNotInShippingZoneQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\ShippingZoneQuery */
        $q = $this->useInQuery('ShippingZone', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Order object
     *
     * @param \Model\Order|ObjectCollection $order the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByOrder($order, ?string $comparison = null)
    {
        if ($order instanceof \Model\Order) {
            $this
                ->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_ID, $order->getShippingId(), $comparison);

            return $this;
        } elseif ($order instanceof ObjectCollection) {
            $this
                ->useOrderQuery()
                ->filterByPrimaryKeys($order->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByOrder() only accepts arguments of type \Model\Order or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Order relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinOrder(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Order');

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
            $this->addJoinObject($join, 'Order');
        }

        return $this;
    }

    /**
     * Use the Order relation Order object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\OrderQuery A secondary query class using the current class as primary query
     */
    public function useOrderQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinOrder($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Order', '\Model\OrderQuery');
    }

    /**
     * Use the Order relation Order object
     *
     * @param callable(\Model\OrderQuery):\Model\OrderQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withOrderQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useOrderQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Order table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\OrderQuery The inner query object of the EXISTS statement
     */
    public function useOrderExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\OrderQuery */
        $q = $this->useExistsQuery('Order', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Order table for a NOT EXISTS query.
     *
     * @see useOrderExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\OrderQuery The inner query object of the NOT EXISTS statement
     */
    public function useOrderNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\OrderQuery */
        $q = $this->useExistsQuery('Order', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Order table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\OrderQuery The inner query object of the IN statement
     */
    public function useInOrderQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\OrderQuery */
        $q = $this->useInQuery('Order', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Order table for a NOT IN query.
     *
     * @see useOrderInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\OrderQuery The inner query object of the NOT IN statement
     */
    public function useNotInOrderQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\OrderQuery */
        $q = $this->useInQuery('Order', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Exclude object from result
     *
     * @param ChildShippingOption $shippingOption Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($shippingOption = null)
    {
        if ($shippingOption) {
            $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_ID, $shippingOption->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the shipping table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ShippingOptionTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ShippingOptionTableMap::clearInstancePool();
            ShippingOptionTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ShippingOptionTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ShippingOptionTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ShippingOptionTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ShippingOptionTableMap::clearRelatedInstancePool();

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
        $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(ShippingOptionTableMap::COL_SHIPPING_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(ShippingOptionTableMap::COL_SHIPPING_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(ShippingOptionTableMap::COL_SHIPPING_CREATED);

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
        $this->addUsingAlias(ShippingOptionTableMap::COL_SHIPPING_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(ShippingOptionTableMap::COL_SHIPPING_CREATED);

        return $this;
    }

}
