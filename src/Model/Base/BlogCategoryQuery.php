<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\BlogCategory as ChildBlogCategory;
use Model\BlogCategoryQuery as ChildBlogCategoryQuery;
use Model\Map\BlogCategoryTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `categories` table.
 *
 * @method     ChildBlogCategoryQuery orderById($order = Criteria::ASC) Order by the category_id column
 * @method     ChildBlogCategoryQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildBlogCategoryQuery orderByName($order = Criteria::ASC) Order by the category_name column
 * @method     ChildBlogCategoryQuery orderByUrl($order = Criteria::ASC) Order by the category_url column
 * @method     ChildBlogCategoryQuery orderByDesc($order = Criteria::ASC) Order by the category_desc column
 * @method     ChildBlogCategoryQuery orderByOrder($order = Criteria::ASC) Order by the category_order column
 * @method     ChildBlogCategoryQuery orderByHidden($order = Criteria::ASC) Order by the category_hidden column
 * @method     ChildBlogCategoryQuery orderByInsert($order = Criteria::ASC) Order by the category_insert column
 * @method     ChildBlogCategoryQuery orderByUpdate($order = Criteria::ASC) Order by the category_update column
 * @method     ChildBlogCategoryQuery orderByCreatedAt($order = Criteria::ASC) Order by the category_created column
 * @method     ChildBlogCategoryQuery orderByUpdatedAt($order = Criteria::ASC) Order by the category_updated column
 *
 * @method     ChildBlogCategoryQuery groupById() Group by the category_id column
 * @method     ChildBlogCategoryQuery groupBySiteId() Group by the site_id column
 * @method     ChildBlogCategoryQuery groupByName() Group by the category_name column
 * @method     ChildBlogCategoryQuery groupByUrl() Group by the category_url column
 * @method     ChildBlogCategoryQuery groupByDesc() Group by the category_desc column
 * @method     ChildBlogCategoryQuery groupByOrder() Group by the category_order column
 * @method     ChildBlogCategoryQuery groupByHidden() Group by the category_hidden column
 * @method     ChildBlogCategoryQuery groupByInsert() Group by the category_insert column
 * @method     ChildBlogCategoryQuery groupByUpdate() Group by the category_update column
 * @method     ChildBlogCategoryQuery groupByCreatedAt() Group by the category_created column
 * @method     ChildBlogCategoryQuery groupByUpdatedAt() Group by the category_updated column
 *
 * @method     ChildBlogCategoryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildBlogCategoryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildBlogCategoryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildBlogCategoryQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildBlogCategoryQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildBlogCategoryQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildBlogCategoryQuery leftJoinPost($relationAlias = null) Adds a LEFT JOIN clause to the query using the Post relation
 * @method     ChildBlogCategoryQuery rightJoinPost($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Post relation
 * @method     ChildBlogCategoryQuery innerJoinPost($relationAlias = null) Adds a INNER JOIN clause to the query using the Post relation
 *
 * @method     ChildBlogCategoryQuery joinWithPost($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Post relation
 *
 * @method     ChildBlogCategoryQuery leftJoinWithPost() Adds a LEFT JOIN clause and with to the query using the Post relation
 * @method     ChildBlogCategoryQuery rightJoinWithPost() Adds a RIGHT JOIN clause and with to the query using the Post relation
 * @method     ChildBlogCategoryQuery innerJoinWithPost() Adds a INNER JOIN clause and with to the query using the Post relation
 *
 * @method     \Model\PostQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildBlogCategory|null findOne(?ConnectionInterface $con = null) Return the first ChildBlogCategory matching the query
 * @method     ChildBlogCategory findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildBlogCategory matching the query, or a new ChildBlogCategory object populated from the query conditions when no match is found
 *
 * @method     ChildBlogCategory|null findOneById(int $category_id) Return the first ChildBlogCategory filtered by the category_id column
 * @method     ChildBlogCategory|null findOneBySiteId(int $site_id) Return the first ChildBlogCategory filtered by the site_id column
 * @method     ChildBlogCategory|null findOneByName(string $category_name) Return the first ChildBlogCategory filtered by the category_name column
 * @method     ChildBlogCategory|null findOneByUrl(string $category_url) Return the first ChildBlogCategory filtered by the category_url column
 * @method     ChildBlogCategory|null findOneByDesc(string $category_desc) Return the first ChildBlogCategory filtered by the category_desc column
 * @method     ChildBlogCategory|null findOneByOrder(int $category_order) Return the first ChildBlogCategory filtered by the category_order column
 * @method     ChildBlogCategory|null findOneByHidden(boolean $category_hidden) Return the first ChildBlogCategory filtered by the category_hidden column
 * @method     ChildBlogCategory|null findOneByInsert(string $category_insert) Return the first ChildBlogCategory filtered by the category_insert column
 * @method     ChildBlogCategory|null findOneByUpdate(string $category_update) Return the first ChildBlogCategory filtered by the category_update column
 * @method     ChildBlogCategory|null findOneByCreatedAt(string $category_created) Return the first ChildBlogCategory filtered by the category_created column
 * @method     ChildBlogCategory|null findOneByUpdatedAt(string $category_updated) Return the first ChildBlogCategory filtered by the category_updated column
 *
 * @method     ChildBlogCategory requirePk($key, ?ConnectionInterface $con = null) Return the ChildBlogCategory by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBlogCategory requireOne(?ConnectionInterface $con = null) Return the first ChildBlogCategory matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildBlogCategory requireOneById(int $category_id) Return the first ChildBlogCategory filtered by the category_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBlogCategory requireOneBySiteId(int $site_id) Return the first ChildBlogCategory filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBlogCategory requireOneByName(string $category_name) Return the first ChildBlogCategory filtered by the category_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBlogCategory requireOneByUrl(string $category_url) Return the first ChildBlogCategory filtered by the category_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBlogCategory requireOneByDesc(string $category_desc) Return the first ChildBlogCategory filtered by the category_desc column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBlogCategory requireOneByOrder(int $category_order) Return the first ChildBlogCategory filtered by the category_order column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBlogCategory requireOneByHidden(boolean $category_hidden) Return the first ChildBlogCategory filtered by the category_hidden column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBlogCategory requireOneByInsert(string $category_insert) Return the first ChildBlogCategory filtered by the category_insert column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBlogCategory requireOneByUpdate(string $category_update) Return the first ChildBlogCategory filtered by the category_update column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBlogCategory requireOneByCreatedAt(string $category_created) Return the first ChildBlogCategory filtered by the category_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBlogCategory requireOneByUpdatedAt(string $category_updated) Return the first ChildBlogCategory filtered by the category_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildBlogCategory[]|Collection find(?ConnectionInterface $con = null) Return ChildBlogCategory objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildBlogCategory> find(?ConnectionInterface $con = null) Return ChildBlogCategory objects based on current ModelCriteria
 *
 * @method     ChildBlogCategory[]|Collection findById(int|array<int> $category_id) Return ChildBlogCategory objects filtered by the category_id column
 * @psalm-method Collection&\Traversable<ChildBlogCategory> findById(int|array<int> $category_id) Return ChildBlogCategory objects filtered by the category_id column
 * @method     ChildBlogCategory[]|Collection findBySiteId(int|array<int> $site_id) Return ChildBlogCategory objects filtered by the site_id column
 * @psalm-method Collection&\Traversable<ChildBlogCategory> findBySiteId(int|array<int> $site_id) Return ChildBlogCategory objects filtered by the site_id column
 * @method     ChildBlogCategory[]|Collection findByName(string|array<string> $category_name) Return ChildBlogCategory objects filtered by the category_name column
 * @psalm-method Collection&\Traversable<ChildBlogCategory> findByName(string|array<string> $category_name) Return ChildBlogCategory objects filtered by the category_name column
 * @method     ChildBlogCategory[]|Collection findByUrl(string|array<string> $category_url) Return ChildBlogCategory objects filtered by the category_url column
 * @psalm-method Collection&\Traversable<ChildBlogCategory> findByUrl(string|array<string> $category_url) Return ChildBlogCategory objects filtered by the category_url column
 * @method     ChildBlogCategory[]|Collection findByDesc(string|array<string> $category_desc) Return ChildBlogCategory objects filtered by the category_desc column
 * @psalm-method Collection&\Traversable<ChildBlogCategory> findByDesc(string|array<string> $category_desc) Return ChildBlogCategory objects filtered by the category_desc column
 * @method     ChildBlogCategory[]|Collection findByOrder(int|array<int> $category_order) Return ChildBlogCategory objects filtered by the category_order column
 * @psalm-method Collection&\Traversable<ChildBlogCategory> findByOrder(int|array<int> $category_order) Return ChildBlogCategory objects filtered by the category_order column
 * @method     ChildBlogCategory[]|Collection findByHidden(boolean|array<boolean> $category_hidden) Return ChildBlogCategory objects filtered by the category_hidden column
 * @psalm-method Collection&\Traversable<ChildBlogCategory> findByHidden(boolean|array<boolean> $category_hidden) Return ChildBlogCategory objects filtered by the category_hidden column
 * @method     ChildBlogCategory[]|Collection findByInsert(string|array<string> $category_insert) Return ChildBlogCategory objects filtered by the category_insert column
 * @psalm-method Collection&\Traversable<ChildBlogCategory> findByInsert(string|array<string> $category_insert) Return ChildBlogCategory objects filtered by the category_insert column
 * @method     ChildBlogCategory[]|Collection findByUpdate(string|array<string> $category_update) Return ChildBlogCategory objects filtered by the category_update column
 * @psalm-method Collection&\Traversable<ChildBlogCategory> findByUpdate(string|array<string> $category_update) Return ChildBlogCategory objects filtered by the category_update column
 * @method     ChildBlogCategory[]|Collection findByCreatedAt(string|array<string> $category_created) Return ChildBlogCategory objects filtered by the category_created column
 * @psalm-method Collection&\Traversable<ChildBlogCategory> findByCreatedAt(string|array<string> $category_created) Return ChildBlogCategory objects filtered by the category_created column
 * @method     ChildBlogCategory[]|Collection findByUpdatedAt(string|array<string> $category_updated) Return ChildBlogCategory objects filtered by the category_updated column
 * @psalm-method Collection&\Traversable<ChildBlogCategory> findByUpdatedAt(string|array<string> $category_updated) Return ChildBlogCategory objects filtered by the category_updated column
 *
 * @method     ChildBlogCategory[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildBlogCategory> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class BlogCategoryQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\BlogCategoryQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\BlogCategory', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildBlogCategoryQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildBlogCategoryQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildBlogCategoryQuery) {
            return $criteria;
        }
        $query = new ChildBlogCategoryQuery();
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
     * @return ChildBlogCategory|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(BlogCategoryTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = BlogCategoryTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildBlogCategory A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT category_id, site_id, category_name, category_url, category_desc, category_order, category_hidden, category_insert, category_update, category_created, category_updated FROM categories WHERE category_id = :p0';
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
            /** @var ChildBlogCategory $obj */
            $obj = new ChildBlogCategory();
            $obj->hydrate($row);
            BlogCategoryTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildBlogCategory|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(BlogCategoryTableMap::COL_CATEGORY_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(BlogCategoryTableMap::COL_CATEGORY_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the category_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE category_id = 1234
     * $query->filterById(array(12, 34)); // WHERE category_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE category_id > 12
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
                $this->addUsingAlias(BlogCategoryTableMap::COL_CATEGORY_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(BlogCategoryTableMap::COL_CATEGORY_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(BlogCategoryTableMap::COL_CATEGORY_ID, $id, $comparison);

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
                $this->addUsingAlias(BlogCategoryTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(BlogCategoryTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(BlogCategoryTableMap::COL_SITE_ID, $siteId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the category_name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE category_name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE category_name LIKE '%fooValue%'
     * $query->filterByName(['foo', 'bar']); // WHERE category_name IN ('foo', 'bar')
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

        $this->addUsingAlias(BlogCategoryTableMap::COL_CATEGORY_NAME, $name, $comparison);

        return $this;
    }

    /**
     * Filter the query on the category_url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE category_url = 'fooValue'
     * $query->filterByUrl('%fooValue%', Criteria::LIKE); // WHERE category_url LIKE '%fooValue%'
     * $query->filterByUrl(['foo', 'bar']); // WHERE category_url IN ('foo', 'bar')
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

        $this->addUsingAlias(BlogCategoryTableMap::COL_CATEGORY_URL, $url, $comparison);

        return $this;
    }

    /**
     * Filter the query on the category_desc column
     *
     * Example usage:
     * <code>
     * $query->filterByDesc('fooValue');   // WHERE category_desc = 'fooValue'
     * $query->filterByDesc('%fooValue%', Criteria::LIKE); // WHERE category_desc LIKE '%fooValue%'
     * $query->filterByDesc(['foo', 'bar']); // WHERE category_desc IN ('foo', 'bar')
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

        $this->addUsingAlias(BlogCategoryTableMap::COL_CATEGORY_DESC, $desc, $comparison);

        return $this;
    }

    /**
     * Filter the query on the category_order column
     *
     * Example usage:
     * <code>
     * $query->filterByOrder(1234); // WHERE category_order = 1234
     * $query->filterByOrder(array(12, 34)); // WHERE category_order IN (12, 34)
     * $query->filterByOrder(array('min' => 12)); // WHERE category_order > 12
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
                $this->addUsingAlias(BlogCategoryTableMap::COL_CATEGORY_ORDER, $order['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($order['max'])) {
                $this->addUsingAlias(BlogCategoryTableMap::COL_CATEGORY_ORDER, $order['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(BlogCategoryTableMap::COL_CATEGORY_ORDER, $order, $comparison);

        return $this;
    }

    /**
     * Filter the query on the category_hidden column
     *
     * Example usage:
     * <code>
     * $query->filterByHidden(true); // WHERE category_hidden = true
     * $query->filterByHidden('yes'); // WHERE category_hidden = true
     * </code>
     *
     * @param bool|string $hidden The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByHidden($hidden = null, ?string $comparison = null)
    {
        if (is_string($hidden)) {
            $hidden = in_array(strtolower($hidden), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(BlogCategoryTableMap::COL_CATEGORY_HIDDEN, $hidden, $comparison);

        return $this;
    }

    /**
     * Filter the query on the category_insert column
     *
     * Example usage:
     * <code>
     * $query->filterByInsert('2011-03-14'); // WHERE category_insert = '2011-03-14'
     * $query->filterByInsert('now'); // WHERE category_insert = '2011-03-14'
     * $query->filterByInsert(array('max' => 'yesterday')); // WHERE category_insert > '2011-03-13'
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
                $this->addUsingAlias(BlogCategoryTableMap::COL_CATEGORY_INSERT, $insert['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($insert['max'])) {
                $this->addUsingAlias(BlogCategoryTableMap::COL_CATEGORY_INSERT, $insert['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(BlogCategoryTableMap::COL_CATEGORY_INSERT, $insert, $comparison);

        return $this;
    }

    /**
     * Filter the query on the category_update column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdate('2011-03-14'); // WHERE category_update = '2011-03-14'
     * $query->filterByUpdate('now'); // WHERE category_update = '2011-03-14'
     * $query->filterByUpdate(array('max' => 'yesterday')); // WHERE category_update > '2011-03-13'
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
                $this->addUsingAlias(BlogCategoryTableMap::COL_CATEGORY_UPDATE, $update['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($update['max'])) {
                $this->addUsingAlias(BlogCategoryTableMap::COL_CATEGORY_UPDATE, $update['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(BlogCategoryTableMap::COL_CATEGORY_UPDATE, $update, $comparison);

        return $this;
    }

    /**
     * Filter the query on the category_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE category_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE category_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE category_created > '2011-03-13'
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
                $this->addUsingAlias(BlogCategoryTableMap::COL_CATEGORY_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(BlogCategoryTableMap::COL_CATEGORY_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(BlogCategoryTableMap::COL_CATEGORY_CREATED, $createdAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the category_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE category_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE category_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE category_updated > '2011-03-13'
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
                $this->addUsingAlias(BlogCategoryTableMap::COL_CATEGORY_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(BlogCategoryTableMap::COL_CATEGORY_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(BlogCategoryTableMap::COL_CATEGORY_UPDATED, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \Model\Post object
     *
     * @param \Model\Post|ObjectCollection $post the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPost($post, ?string $comparison = null)
    {
        if ($post instanceof \Model\Post) {
            $this
                ->addUsingAlias(BlogCategoryTableMap::COL_CATEGORY_ID, $post->getCategoryId(), $comparison);

            return $this;
        } elseif ($post instanceof ObjectCollection) {
            $this
                ->usePostQuery()
                ->filterByPrimaryKeys($post->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByPost() only accepts arguments of type \Model\Post or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Post relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinPost(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Post');

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
            $this->addJoinObject($join, 'Post');
        }

        return $this;
    }

    /**
     * Use the Post relation Post object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\PostQuery A secondary query class using the current class as primary query
     */
    public function usePostQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPost($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Post', '\Model\PostQuery');
    }

    /**
     * Use the Post relation Post object
     *
     * @param callable(\Model\PostQuery):\Model\PostQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withPostQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->usePostQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Post table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\PostQuery The inner query object of the EXISTS statement
     */
    public function usePostExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\PostQuery */
        $q = $this->useExistsQuery('Post', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Post table for a NOT EXISTS query.
     *
     * @see usePostExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\PostQuery The inner query object of the NOT EXISTS statement
     */
    public function usePostNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\PostQuery */
        $q = $this->useExistsQuery('Post', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Post table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\PostQuery The inner query object of the IN statement
     */
    public function useInPostQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\PostQuery */
        $q = $this->useInQuery('Post', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Post table for a NOT IN query.
     *
     * @see usePostInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\PostQuery The inner query object of the NOT IN statement
     */
    public function useNotInPostQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\PostQuery */
        $q = $this->useInQuery('Post', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Exclude object from result
     *
     * @param ChildBlogCategory $blogCategory Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($blogCategory = null)
    {
        if ($blogCategory) {
            $this->addUsingAlias(BlogCategoryTableMap::COL_CATEGORY_ID, $blogCategory->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the categories table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(BlogCategoryTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            BlogCategoryTableMap::clearInstancePool();
            BlogCategoryTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(BlogCategoryTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(BlogCategoryTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            BlogCategoryTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            BlogCategoryTableMap::clearRelatedInstancePool();

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
        $this->addUsingAlias(BlogCategoryTableMap::COL_CATEGORY_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(BlogCategoryTableMap::COL_CATEGORY_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(BlogCategoryTableMap::COL_CATEGORY_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(BlogCategoryTableMap::COL_CATEGORY_CREATED);

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
        $this->addUsingAlias(BlogCategoryTableMap::COL_CATEGORY_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(BlogCategoryTableMap::COL_CATEGORY_CREATED);

        return $this;
    }

}
