<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Category as ChildCategory;
use Model\CategoryQuery as ChildCategoryQuery;
use Model\Map\CategoryTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'categories' table.
 *
 *
 *
 * @method     ChildCategoryQuery orderById($order = Criteria::ASC) Order by the category_id column
 * @method     ChildCategoryQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildCategoryQuery orderByName($order = Criteria::ASC) Order by the category_name column
 * @method     ChildCategoryQuery orderByUrl($order = Criteria::ASC) Order by the category_url column
 * @method     ChildCategoryQuery orderByDesc($order = Criteria::ASC) Order by the category_desc column
 * @method     ChildCategoryQuery orderByOrder($order = Criteria::ASC) Order by the category_order column
 * @method     ChildCategoryQuery orderByHidden($order = Criteria::ASC) Order by the category_hidden column
 * @method     ChildCategoryQuery orderByInsert($order = Criteria::ASC) Order by the category_insert column
 * @method     ChildCategoryQuery orderByUpdate($order = Criteria::ASC) Order by the category_update column
 * @method     ChildCategoryQuery orderByCreatedAt($order = Criteria::ASC) Order by the category_created column
 * @method     ChildCategoryQuery orderByUpdatedAt($order = Criteria::ASC) Order by the category_updated column
 * @method     ChildCategoryQuery orderByDeletedAt($order = Criteria::ASC) Order by the category_deleted column
 *
 * @method     ChildCategoryQuery groupById() Group by the category_id column
 * @method     ChildCategoryQuery groupBySiteId() Group by the site_id column
 * @method     ChildCategoryQuery groupByName() Group by the category_name column
 * @method     ChildCategoryQuery groupByUrl() Group by the category_url column
 * @method     ChildCategoryQuery groupByDesc() Group by the category_desc column
 * @method     ChildCategoryQuery groupByOrder() Group by the category_order column
 * @method     ChildCategoryQuery groupByHidden() Group by the category_hidden column
 * @method     ChildCategoryQuery groupByInsert() Group by the category_insert column
 * @method     ChildCategoryQuery groupByUpdate() Group by the category_update column
 * @method     ChildCategoryQuery groupByCreatedAt() Group by the category_created column
 * @method     ChildCategoryQuery groupByUpdatedAt() Group by the category_updated column
 * @method     ChildCategoryQuery groupByDeletedAt() Group by the category_deleted column
 *
 * @method     ChildCategoryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCategoryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCategoryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCategoryQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildCategoryQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildCategoryQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildCategory|null findOne(ConnectionInterface $con = null) Return the first ChildCategory matching the query
 * @method     ChildCategory findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCategory matching the query, or a new ChildCategory object populated from the query conditions when no match is found
 *
 * @method     ChildCategory|null findOneById(int $category_id) Return the first ChildCategory filtered by the category_id column
 * @method     ChildCategory|null findOneBySiteId(int $site_id) Return the first ChildCategory filtered by the site_id column
 * @method     ChildCategory|null findOneByName(string $category_name) Return the first ChildCategory filtered by the category_name column
 * @method     ChildCategory|null findOneByUrl(string $category_url) Return the first ChildCategory filtered by the category_url column
 * @method     ChildCategory|null findOneByDesc(string $category_desc) Return the first ChildCategory filtered by the category_desc column
 * @method     ChildCategory|null findOneByOrder(int $category_order) Return the first ChildCategory filtered by the category_order column
 * @method     ChildCategory|null findOneByHidden(boolean $category_hidden) Return the first ChildCategory filtered by the category_hidden column
 * @method     ChildCategory|null findOneByInsert(string $category_insert) Return the first ChildCategory filtered by the category_insert column
 * @method     ChildCategory|null findOneByUpdate(string $category_update) Return the first ChildCategory filtered by the category_update column
 * @method     ChildCategory|null findOneByCreatedAt(string $category_created) Return the first ChildCategory filtered by the category_created column
 * @method     ChildCategory|null findOneByUpdatedAt(string $category_updated) Return the first ChildCategory filtered by the category_updated column
 * @method     ChildCategory|null findOneByDeletedAt(string $category_deleted) Return the first ChildCategory filtered by the category_deleted column *

 * @method     ChildCategory requirePk($key, ConnectionInterface $con = null) Return the ChildCategory by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCategory requireOne(ConnectionInterface $con = null) Return the first ChildCategory matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCategory requireOneById(int $category_id) Return the first ChildCategory filtered by the category_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCategory requireOneBySiteId(int $site_id) Return the first ChildCategory filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCategory requireOneByName(string $category_name) Return the first ChildCategory filtered by the category_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCategory requireOneByUrl(string $category_url) Return the first ChildCategory filtered by the category_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCategory requireOneByDesc(string $category_desc) Return the first ChildCategory filtered by the category_desc column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCategory requireOneByOrder(int $category_order) Return the first ChildCategory filtered by the category_order column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCategory requireOneByHidden(boolean $category_hidden) Return the first ChildCategory filtered by the category_hidden column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCategory requireOneByInsert(string $category_insert) Return the first ChildCategory filtered by the category_insert column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCategory requireOneByUpdate(string $category_update) Return the first ChildCategory filtered by the category_update column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCategory requireOneByCreatedAt(string $category_created) Return the first ChildCategory filtered by the category_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCategory requireOneByUpdatedAt(string $category_updated) Return the first ChildCategory filtered by the category_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCategory requireOneByDeletedAt(string $category_deleted) Return the first ChildCategory filtered by the category_deleted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCategory[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildCategory objects based on current ModelCriteria
 * @method     ChildCategory[]|ObjectCollection findById(int $category_id) Return ChildCategory objects filtered by the category_id column
 * @method     ChildCategory[]|ObjectCollection findBySiteId(int $site_id) Return ChildCategory objects filtered by the site_id column
 * @method     ChildCategory[]|ObjectCollection findByName(string $category_name) Return ChildCategory objects filtered by the category_name column
 * @method     ChildCategory[]|ObjectCollection findByUrl(string $category_url) Return ChildCategory objects filtered by the category_url column
 * @method     ChildCategory[]|ObjectCollection findByDesc(string $category_desc) Return ChildCategory objects filtered by the category_desc column
 * @method     ChildCategory[]|ObjectCollection findByOrder(int $category_order) Return ChildCategory objects filtered by the category_order column
 * @method     ChildCategory[]|ObjectCollection findByHidden(boolean $category_hidden) Return ChildCategory objects filtered by the category_hidden column
 * @method     ChildCategory[]|ObjectCollection findByInsert(string $category_insert) Return ChildCategory objects filtered by the category_insert column
 * @method     ChildCategory[]|ObjectCollection findByUpdate(string $category_update) Return ChildCategory objects filtered by the category_update column
 * @method     ChildCategory[]|ObjectCollection findByCreatedAt(string $category_created) Return ChildCategory objects filtered by the category_created column
 * @method     ChildCategory[]|ObjectCollection findByUpdatedAt(string $category_updated) Return ChildCategory objects filtered by the category_updated column
 * @method     ChildCategory[]|ObjectCollection findByDeletedAt(string $category_deleted) Return ChildCategory objects filtered by the category_deleted column
 * @method     ChildCategory[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class CategoryQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\CategoryQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Category', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCategoryQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCategoryQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildCategoryQuery) {
            return $criteria;
        }
        $query = new ChildCategoryQuery();
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
     * @return ChildCategory|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CategoryTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = CategoryTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildCategory A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT category_id, site_id, category_name, category_url, category_desc, category_order, category_hidden, category_insert, category_update, category_created, category_updated, category_deleted FROM categories WHERE category_id = :p0';
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
            /** @var ChildCategory $obj */
            $obj = new ChildCategory();
            $obj->hydrate($row);
            CategoryTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildCategory|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildCategoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CategoryTableMap::COL_CATEGORY_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildCategoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CategoryTableMap::COL_CATEGORY_ID, $keys, Criteria::IN);
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
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCategoryQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CategoryTableMap::COL_CATEGORY_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CategoryTableMap::COL_CATEGORY_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CategoryTableMap::COL_CATEGORY_ID, $id, $comparison);
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
     * @return $this|ChildCategoryQuery The current query, for fluid interface
     */
    public function filterBySiteId($siteId = null, $comparison = null)
    {
        if (is_array($siteId)) {
            $useMinMax = false;
            if (isset($siteId['min'])) {
                $this->addUsingAlias(CategoryTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(CategoryTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CategoryTableMap::COL_SITE_ID, $siteId, $comparison);
    }

    /**
     * Filter the query on the category_name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE category_name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE category_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCategoryQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CategoryTableMap::COL_CATEGORY_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the category_url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE category_url = 'fooValue'
     * $query->filterByUrl('%fooValue%', Criteria::LIKE); // WHERE category_url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $url The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCategoryQuery The current query, for fluid interface
     */
    public function filterByUrl($url = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($url)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CategoryTableMap::COL_CATEGORY_URL, $url, $comparison);
    }

    /**
     * Filter the query on the category_desc column
     *
     * Example usage:
     * <code>
     * $query->filterByDesc('fooValue');   // WHERE category_desc = 'fooValue'
     * $query->filterByDesc('%fooValue%', Criteria::LIKE); // WHERE category_desc LIKE '%fooValue%'
     * </code>
     *
     * @param     string $desc The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCategoryQuery The current query, for fluid interface
     */
    public function filterByDesc($desc = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($desc)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CategoryTableMap::COL_CATEGORY_DESC, $desc, $comparison);
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
     * @param     mixed $order The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCategoryQuery The current query, for fluid interface
     */
    public function filterByOrder($order = null, $comparison = null)
    {
        if (is_array($order)) {
            $useMinMax = false;
            if (isset($order['min'])) {
                $this->addUsingAlias(CategoryTableMap::COL_CATEGORY_ORDER, $order['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($order['max'])) {
                $this->addUsingAlias(CategoryTableMap::COL_CATEGORY_ORDER, $order['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CategoryTableMap::COL_CATEGORY_ORDER, $order, $comparison);
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
     * @param     boolean|string $hidden The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCategoryQuery The current query, for fluid interface
     */
    public function filterByHidden($hidden = null, $comparison = null)
    {
        if (is_string($hidden)) {
            $hidden = in_array(strtolower($hidden), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(CategoryTableMap::COL_CATEGORY_HIDDEN, $hidden, $comparison);
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
     * @param     mixed $insert The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCategoryQuery The current query, for fluid interface
     */
    public function filterByInsert($insert = null, $comparison = null)
    {
        if (is_array($insert)) {
            $useMinMax = false;
            if (isset($insert['min'])) {
                $this->addUsingAlias(CategoryTableMap::COL_CATEGORY_INSERT, $insert['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($insert['max'])) {
                $this->addUsingAlias(CategoryTableMap::COL_CATEGORY_INSERT, $insert['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CategoryTableMap::COL_CATEGORY_INSERT, $insert, $comparison);
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
     * @param     mixed $update The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCategoryQuery The current query, for fluid interface
     */
    public function filterByUpdate($update = null, $comparison = null)
    {
        if (is_array($update)) {
            $useMinMax = false;
            if (isset($update['min'])) {
                $this->addUsingAlias(CategoryTableMap::COL_CATEGORY_UPDATE, $update['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($update['max'])) {
                $this->addUsingAlias(CategoryTableMap::COL_CATEGORY_UPDATE, $update['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CategoryTableMap::COL_CATEGORY_UPDATE, $update, $comparison);
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
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCategoryQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(CategoryTableMap::COL_CATEGORY_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(CategoryTableMap::COL_CATEGORY_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CategoryTableMap::COL_CATEGORY_CREATED, $createdAt, $comparison);
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
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCategoryQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(CategoryTableMap::COL_CATEGORY_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(CategoryTableMap::COL_CATEGORY_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CategoryTableMap::COL_CATEGORY_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the category_deleted column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletedAt('2011-03-14'); // WHERE category_deleted = '2011-03-14'
     * $query->filterByDeletedAt('now'); // WHERE category_deleted = '2011-03-14'
     * $query->filterByDeletedAt(array('max' => 'yesterday')); // WHERE category_deleted > '2011-03-13'
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
     * @return $this|ChildCategoryQuery The current query, for fluid interface
     */
    public function filterByDeletedAt($deletedAt = null, $comparison = null)
    {
        if (is_array($deletedAt)) {
            $useMinMax = false;
            if (isset($deletedAt['min'])) {
                $this->addUsingAlias(CategoryTableMap::COL_CATEGORY_DELETED, $deletedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletedAt['max'])) {
                $this->addUsingAlias(CategoryTableMap::COL_CATEGORY_DELETED, $deletedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CategoryTableMap::COL_CATEGORY_DELETED, $deletedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildCategory $category Object to remove from the list of results
     *
     * @return $this|ChildCategoryQuery The current query, for fluid interface
     */
    public function prune($category = null)
    {
        if ($category) {
            $this->addUsingAlias(CategoryTableMap::COL_CATEGORY_ID, $category->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the categories table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CategoryTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CategoryTableMap::clearInstancePool();
            CategoryTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(CategoryTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CategoryTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            CategoryTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CategoryTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // CategoryQuery
