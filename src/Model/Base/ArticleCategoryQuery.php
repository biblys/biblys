<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\ArticleCategory as ChildArticleCategory;
use Model\ArticleCategoryQuery as ChildArticleCategoryQuery;
use Model\Map\ArticleCategoryTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `rayons` table.
 *
 * @method     ChildArticleCategoryQuery orderById($order = Criteria::ASC) Order by the rayon_id column
 * @method     ChildArticleCategoryQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildArticleCategoryQuery orderByName($order = Criteria::ASC) Order by the rayon_name column
 * @method     ChildArticleCategoryQuery orderByUrl($order = Criteria::ASC) Order by the rayon_url column
 * @method     ChildArticleCategoryQuery orderByDesc($order = Criteria::ASC) Order by the rayon_desc column
 * @method     ChildArticleCategoryQuery orderByOrder($order = Criteria::ASC) Order by the rayon_order column
 * @method     ChildArticleCategoryQuery orderBySortBy($order = Criteria::ASC) Order by the rayon_sort_by column
 * @method     ChildArticleCategoryQuery orderBySortOrder($order = Criteria::ASC) Order by the rayon_sort_order column
 * @method     ChildArticleCategoryQuery orderByShowUpcoming($order = Criteria::ASC) Order by the rayon_show_upcoming column
 * @method     ChildArticleCategoryQuery orderByCreatedAt($order = Criteria::ASC) Order by the rayon_created column
 * @method     ChildArticleCategoryQuery orderByUpdatedAt($order = Criteria::ASC) Order by the rayon_updated column
 *
 * @method     ChildArticleCategoryQuery groupById() Group by the rayon_id column
 * @method     ChildArticleCategoryQuery groupBySiteId() Group by the site_id column
 * @method     ChildArticleCategoryQuery groupByName() Group by the rayon_name column
 * @method     ChildArticleCategoryQuery groupByUrl() Group by the rayon_url column
 * @method     ChildArticleCategoryQuery groupByDesc() Group by the rayon_desc column
 * @method     ChildArticleCategoryQuery groupByOrder() Group by the rayon_order column
 * @method     ChildArticleCategoryQuery groupBySortBy() Group by the rayon_sort_by column
 * @method     ChildArticleCategoryQuery groupBySortOrder() Group by the rayon_sort_order column
 * @method     ChildArticleCategoryQuery groupByShowUpcoming() Group by the rayon_show_upcoming column
 * @method     ChildArticleCategoryQuery groupByCreatedAt() Group by the rayon_created column
 * @method     ChildArticleCategoryQuery groupByUpdatedAt() Group by the rayon_updated column
 *
 * @method     ChildArticleCategoryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildArticleCategoryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildArticleCategoryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildArticleCategoryQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildArticleCategoryQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildArticleCategoryQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildArticleCategory|null findOne(?ConnectionInterface $con = null) Return the first ChildArticleCategory matching the query
 * @method     ChildArticleCategory findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildArticleCategory matching the query, or a new ChildArticleCategory object populated from the query conditions when no match is found
 *
 * @method     ChildArticleCategory|null findOneById(string $rayon_id) Return the first ChildArticleCategory filtered by the rayon_id column
 * @method     ChildArticleCategory|null findOneBySiteId(int $site_id) Return the first ChildArticleCategory filtered by the site_id column
 * @method     ChildArticleCategory|null findOneByName(string $rayon_name) Return the first ChildArticleCategory filtered by the rayon_name column
 * @method     ChildArticleCategory|null findOneByUrl(string $rayon_url) Return the first ChildArticleCategory filtered by the rayon_url column
 * @method     ChildArticleCategory|null findOneByDesc(string $rayon_desc) Return the first ChildArticleCategory filtered by the rayon_desc column
 * @method     ChildArticleCategory|null findOneByOrder(int $rayon_order) Return the first ChildArticleCategory filtered by the rayon_order column
 * @method     ChildArticleCategory|null findOneBySortBy(string $rayon_sort_by) Return the first ChildArticleCategory filtered by the rayon_sort_by column
 * @method     ChildArticleCategory|null findOneBySortOrder(boolean $rayon_sort_order) Return the first ChildArticleCategory filtered by the rayon_sort_order column
 * @method     ChildArticleCategory|null findOneByShowUpcoming(boolean $rayon_show_upcoming) Return the first ChildArticleCategory filtered by the rayon_show_upcoming column
 * @method     ChildArticleCategory|null findOneByCreatedAt(string $rayon_created) Return the first ChildArticleCategory filtered by the rayon_created column
 * @method     ChildArticleCategory|null findOneByUpdatedAt(string $rayon_updated) Return the first ChildArticleCategory filtered by the rayon_updated column
 *
 * @method     ChildArticleCategory requirePk($key, ?ConnectionInterface $con = null) Return the ChildArticleCategory by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticleCategory requireOne(?ConnectionInterface $con = null) Return the first ChildArticleCategory matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildArticleCategory requireOneById(string $rayon_id) Return the first ChildArticleCategory filtered by the rayon_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticleCategory requireOneBySiteId(int $site_id) Return the first ChildArticleCategory filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticleCategory requireOneByName(string $rayon_name) Return the first ChildArticleCategory filtered by the rayon_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticleCategory requireOneByUrl(string $rayon_url) Return the first ChildArticleCategory filtered by the rayon_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticleCategory requireOneByDesc(string $rayon_desc) Return the first ChildArticleCategory filtered by the rayon_desc column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticleCategory requireOneByOrder(int $rayon_order) Return the first ChildArticleCategory filtered by the rayon_order column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticleCategory requireOneBySortBy(string $rayon_sort_by) Return the first ChildArticleCategory filtered by the rayon_sort_by column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticleCategory requireOneBySortOrder(boolean $rayon_sort_order) Return the first ChildArticleCategory filtered by the rayon_sort_order column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticleCategory requireOneByShowUpcoming(boolean $rayon_show_upcoming) Return the first ChildArticleCategory filtered by the rayon_show_upcoming column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticleCategory requireOneByCreatedAt(string $rayon_created) Return the first ChildArticleCategory filtered by the rayon_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticleCategory requireOneByUpdatedAt(string $rayon_updated) Return the first ChildArticleCategory filtered by the rayon_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildArticleCategory[]|Collection find(?ConnectionInterface $con = null) Return ChildArticleCategory objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildArticleCategory> find(?ConnectionInterface $con = null) Return ChildArticleCategory objects based on current ModelCriteria
 *
 * @method     ChildArticleCategory[]|Collection findById(string|array<string> $rayon_id) Return ChildArticleCategory objects filtered by the rayon_id column
 * @psalm-method Collection&\Traversable<ChildArticleCategory> findById(string|array<string> $rayon_id) Return ChildArticleCategory objects filtered by the rayon_id column
 * @method     ChildArticleCategory[]|Collection findBySiteId(int|array<int> $site_id) Return ChildArticleCategory objects filtered by the site_id column
 * @psalm-method Collection&\Traversable<ChildArticleCategory> findBySiteId(int|array<int> $site_id) Return ChildArticleCategory objects filtered by the site_id column
 * @method     ChildArticleCategory[]|Collection findByName(string|array<string> $rayon_name) Return ChildArticleCategory objects filtered by the rayon_name column
 * @psalm-method Collection&\Traversable<ChildArticleCategory> findByName(string|array<string> $rayon_name) Return ChildArticleCategory objects filtered by the rayon_name column
 * @method     ChildArticleCategory[]|Collection findByUrl(string|array<string> $rayon_url) Return ChildArticleCategory objects filtered by the rayon_url column
 * @psalm-method Collection&\Traversable<ChildArticleCategory> findByUrl(string|array<string> $rayon_url) Return ChildArticleCategory objects filtered by the rayon_url column
 * @method     ChildArticleCategory[]|Collection findByDesc(string|array<string> $rayon_desc) Return ChildArticleCategory objects filtered by the rayon_desc column
 * @psalm-method Collection&\Traversable<ChildArticleCategory> findByDesc(string|array<string> $rayon_desc) Return ChildArticleCategory objects filtered by the rayon_desc column
 * @method     ChildArticleCategory[]|Collection findByOrder(int|array<int> $rayon_order) Return ChildArticleCategory objects filtered by the rayon_order column
 * @psalm-method Collection&\Traversable<ChildArticleCategory> findByOrder(int|array<int> $rayon_order) Return ChildArticleCategory objects filtered by the rayon_order column
 * @method     ChildArticleCategory[]|Collection findBySortBy(string|array<string> $rayon_sort_by) Return ChildArticleCategory objects filtered by the rayon_sort_by column
 * @psalm-method Collection&\Traversable<ChildArticleCategory> findBySortBy(string|array<string> $rayon_sort_by) Return ChildArticleCategory objects filtered by the rayon_sort_by column
 * @method     ChildArticleCategory[]|Collection findBySortOrder(boolean|array<boolean> $rayon_sort_order) Return ChildArticleCategory objects filtered by the rayon_sort_order column
 * @psalm-method Collection&\Traversable<ChildArticleCategory> findBySortOrder(boolean|array<boolean> $rayon_sort_order) Return ChildArticleCategory objects filtered by the rayon_sort_order column
 * @method     ChildArticleCategory[]|Collection findByShowUpcoming(boolean|array<boolean> $rayon_show_upcoming) Return ChildArticleCategory objects filtered by the rayon_show_upcoming column
 * @psalm-method Collection&\Traversable<ChildArticleCategory> findByShowUpcoming(boolean|array<boolean> $rayon_show_upcoming) Return ChildArticleCategory objects filtered by the rayon_show_upcoming column
 * @method     ChildArticleCategory[]|Collection findByCreatedAt(string|array<string> $rayon_created) Return ChildArticleCategory objects filtered by the rayon_created column
 * @psalm-method Collection&\Traversable<ChildArticleCategory> findByCreatedAt(string|array<string> $rayon_created) Return ChildArticleCategory objects filtered by the rayon_created column
 * @method     ChildArticleCategory[]|Collection findByUpdatedAt(string|array<string> $rayon_updated) Return ChildArticleCategory objects filtered by the rayon_updated column
 * @psalm-method Collection&\Traversable<ChildArticleCategory> findByUpdatedAt(string|array<string> $rayon_updated) Return ChildArticleCategory objects filtered by the rayon_updated column
 *
 * @method     ChildArticleCategory[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildArticleCategory> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class ArticleCategoryQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\ArticleCategoryQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\ArticleCategory', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildArticleCategoryQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildArticleCategoryQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildArticleCategoryQuery) {
            return $criteria;
        }
        $query = new ChildArticleCategoryQuery();
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
     * @return ChildArticleCategory|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ArticleCategoryTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ArticleCategoryTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildArticleCategory A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT rayon_id, site_id, rayon_name, rayon_url, rayon_desc, rayon_order, rayon_sort_by, rayon_sort_order, rayon_show_upcoming, rayon_created, rayon_updated FROM rayons WHERE rayon_id = :p0';
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
            /** @var ChildArticleCategory $obj */
            $obj = new ChildArticleCategory();
            $obj->hydrate($row);
            ArticleCategoryTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildArticleCategory|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(ArticleCategoryTableMap::COL_RAYON_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(ArticleCategoryTableMap::COL_RAYON_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the rayon_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE rayon_id = 1234
     * $query->filterById(array(12, 34)); // WHERE rayon_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE rayon_id > 12
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
                $this->addUsingAlias(ArticleCategoryTableMap::COL_RAYON_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ArticleCategoryTableMap::COL_RAYON_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleCategoryTableMap::COL_RAYON_ID, $id, $comparison);

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
                $this->addUsingAlias(ArticleCategoryTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(ArticleCategoryTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleCategoryTableMap::COL_SITE_ID, $siteId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the rayon_name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE rayon_name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE rayon_name LIKE '%fooValue%'
     * $query->filterByName(['foo', 'bar']); // WHERE rayon_name IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $name The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByName($name = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleCategoryTableMap::COL_RAYON_NAME, $name, $comparison);

        return $this;
    }

    /**
     * Filter the query on the rayon_url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE rayon_url = 'fooValue'
     * $query->filterByUrl('%fooValue%', Criteria::LIKE); // WHERE rayon_url LIKE '%fooValue%'
     * $query->filterByUrl(['foo', 'bar']); // WHERE rayon_url IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $url The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUrl($url = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($url)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleCategoryTableMap::COL_RAYON_URL, $url, $comparison);

        return $this;
    }

    /**
     * Filter the query on the rayon_desc column
     *
     * Example usage:
     * <code>
     * $query->filterByDesc('fooValue');   // WHERE rayon_desc = 'fooValue'
     * $query->filterByDesc('%fooValue%', Criteria::LIKE); // WHERE rayon_desc LIKE '%fooValue%'
     * $query->filterByDesc(['foo', 'bar']); // WHERE rayon_desc IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $desc The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDesc($desc = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($desc)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleCategoryTableMap::COL_RAYON_DESC, $desc, $comparison);

        return $this;
    }

    /**
     * Filter the query on the rayon_order column
     *
     * Example usage:
     * <code>
     * $query->filterByOrder(1234); // WHERE rayon_order = 1234
     * $query->filterByOrder(array(12, 34)); // WHERE rayon_order IN (12, 34)
     * $query->filterByOrder(array('min' => 12)); // WHERE rayon_order > 12
     * </code>
     *
     * @param mixed $order The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByOrder($order = null, ?string $comparison = null)
    {
        if (is_array($order)) {
            $useMinMax = false;
            if (isset($order['min'])) {
                $this->addUsingAlias(ArticleCategoryTableMap::COL_RAYON_ORDER, $order['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($order['max'])) {
                $this->addUsingAlias(ArticleCategoryTableMap::COL_RAYON_ORDER, $order['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleCategoryTableMap::COL_RAYON_ORDER, $order, $comparison);

        return $this;
    }

    /**
     * Filter the query on the rayon_sort_by column
     *
     * Example usage:
     * <code>
     * $query->filterBySortBy('fooValue');   // WHERE rayon_sort_by = 'fooValue'
     * $query->filterBySortBy('%fooValue%', Criteria::LIKE); // WHERE rayon_sort_by LIKE '%fooValue%'
     * $query->filterBySortBy(['foo', 'bar']); // WHERE rayon_sort_by IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $sortBy The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySortBy($sortBy = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($sortBy)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleCategoryTableMap::COL_RAYON_SORT_BY, $sortBy, $comparison);

        return $this;
    }

    /**
     * Filter the query on the rayon_sort_order column
     *
     * Example usage:
     * <code>
     * $query->filterBySortOrder(true); // WHERE rayon_sort_order = true
     * $query->filterBySortOrder('yes'); // WHERE rayon_sort_order = true
     * </code>
     *
     * @param bool|string $sortOrder The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySortOrder($sortOrder = null, ?string $comparison = null)
    {
        if (is_string($sortOrder)) {
            $sortOrder = in_array(strtolower($sortOrder), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(ArticleCategoryTableMap::COL_RAYON_SORT_ORDER, $sortOrder, $comparison);

        return $this;
    }

    /**
     * Filter the query on the rayon_show_upcoming column
     *
     * Example usage:
     * <code>
     * $query->filterByShowUpcoming(true); // WHERE rayon_show_upcoming = true
     * $query->filterByShowUpcoming('yes'); // WHERE rayon_show_upcoming = true
     * </code>
     *
     * @param bool|string $showUpcoming The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByShowUpcoming($showUpcoming = null, ?string $comparison = null)
    {
        if (is_string($showUpcoming)) {
            $showUpcoming = in_array(strtolower($showUpcoming), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(ArticleCategoryTableMap::COL_RAYON_SHOW_UPCOMING, $showUpcoming, $comparison);

        return $this;
    }

    /**
     * Filter the query on the rayon_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE rayon_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE rayon_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE rayon_created > '2011-03-13'
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
                $this->addUsingAlias(ArticleCategoryTableMap::COL_RAYON_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(ArticleCategoryTableMap::COL_RAYON_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleCategoryTableMap::COL_RAYON_CREATED, $createdAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the rayon_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE rayon_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE rayon_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE rayon_updated > '2011-03-13'
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
                $this->addUsingAlias(ArticleCategoryTableMap::COL_RAYON_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(ArticleCategoryTableMap::COL_RAYON_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleCategoryTableMap::COL_RAYON_UPDATED, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Exclude object from result
     *
     * @param ChildArticleCategory $articleCategory Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($articleCategory = null)
    {
        if ($articleCategory) {
            $this->addUsingAlias(ArticleCategoryTableMap::COL_RAYON_ID, $articleCategory->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the rayons table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ArticleCategoryTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ArticleCategoryTableMap::clearInstancePool();
            ArticleCategoryTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ArticleCategoryTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ArticleCategoryTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ArticleCategoryTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ArticleCategoryTableMap::clearRelatedInstancePool();

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
        $this->addUsingAlias(ArticleCategoryTableMap::COL_RAYON_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(ArticleCategoryTableMap::COL_RAYON_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(ArticleCategoryTableMap::COL_RAYON_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(ArticleCategoryTableMap::COL_RAYON_CREATED);

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
        $this->addUsingAlias(ArticleCategoryTableMap::COL_RAYON_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(ArticleCategoryTableMap::COL_RAYON_CREATED);

        return $this;
    }

    // sluggable behavior

    /**
     * Filter the query on the slug column
     *
     * @param string $slug The value to use as filter.
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySlug(string $slug)
    {
        $this->addUsingAlias(ArticleCategoryTableMap::COL_RAYON_URL, $slug, Criteria::EQUAL);

        return $this;
    }

    /**
     * Find one object based on its slug
     *
     * @param string $slug The value to use as filter.
     * @param ConnectionInterface $con The optional connection object
     *
     * @return ChildArticleCategory the result, formatted by the current formatter
     */
    public function findOneBySlug(string $slug, ?ConnectionInterface $con = null)
    {
        return $this->filterBySlug($slug)->findOne($con);
    }

}
