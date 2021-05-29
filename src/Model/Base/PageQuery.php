<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Page as ChildPage;
use Model\PageQuery as ChildPageQuery;
use Model\Map\PageTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'pages' table.
 *
 *
 *
 * @method     ChildPageQuery orderById($order = Criteria::ASC) Order by the page_id column
 * @method     ChildPageQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildPageQuery orderByUrl($order = Criteria::ASC) Order by the page_url column
 * @method     ChildPageQuery orderByTitle($order = Criteria::ASC) Order by the page_title column
 * @method     ChildPageQuery orderByContent($order = Criteria::ASC) Order by the page_content column
 * @method     ChildPageQuery orderByStatus($order = Criteria::ASC) Order by the page_status column
 * @method     ChildPageQuery orderByInsert($order = Criteria::ASC) Order by the page_insert column
 * @method     ChildPageQuery orderByUpdate($order = Criteria::ASC) Order by the page_update column
 * @method     ChildPageQuery orderByCreatedAt($order = Criteria::ASC) Order by the page_created column
 * @method     ChildPageQuery orderByUpdatedAt($order = Criteria::ASC) Order by the page_updated column
 * @method     ChildPageQuery orderByDeletedAt($order = Criteria::ASC) Order by the page_deleted column
 *
 * @method     ChildPageQuery groupById() Group by the page_id column
 * @method     ChildPageQuery groupBySiteId() Group by the site_id column
 * @method     ChildPageQuery groupByUrl() Group by the page_url column
 * @method     ChildPageQuery groupByTitle() Group by the page_title column
 * @method     ChildPageQuery groupByContent() Group by the page_content column
 * @method     ChildPageQuery groupByStatus() Group by the page_status column
 * @method     ChildPageQuery groupByInsert() Group by the page_insert column
 * @method     ChildPageQuery groupByUpdate() Group by the page_update column
 * @method     ChildPageQuery groupByCreatedAt() Group by the page_created column
 * @method     ChildPageQuery groupByUpdatedAt() Group by the page_updated column
 * @method     ChildPageQuery groupByDeletedAt() Group by the page_deleted column
 *
 * @method     ChildPageQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPageQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPageQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPageQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPageQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPageQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPage|null findOne(ConnectionInterface $con = null) Return the first ChildPage matching the query
 * @method     ChildPage findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPage matching the query, or a new ChildPage object populated from the query conditions when no match is found
 *
 * @method     ChildPage|null findOneById(int $page_id) Return the first ChildPage filtered by the page_id column
 * @method     ChildPage|null findOneBySiteId(int $site_id) Return the first ChildPage filtered by the site_id column
 * @method     ChildPage|null findOneByUrl(string $page_url) Return the first ChildPage filtered by the page_url column
 * @method     ChildPage|null findOneByTitle(string $page_title) Return the first ChildPage filtered by the page_title column
 * @method     ChildPage|null findOneByContent(string $page_content) Return the first ChildPage filtered by the page_content column
 * @method     ChildPage|null findOneByStatus(boolean $page_status) Return the first ChildPage filtered by the page_status column
 * @method     ChildPage|null findOneByInsert(string $page_insert) Return the first ChildPage filtered by the page_insert column
 * @method     ChildPage|null findOneByUpdate(string $page_update) Return the first ChildPage filtered by the page_update column
 * @method     ChildPage|null findOneByCreatedAt(string $page_created) Return the first ChildPage filtered by the page_created column
 * @method     ChildPage|null findOneByUpdatedAt(string $page_updated) Return the first ChildPage filtered by the page_updated column
 * @method     ChildPage|null findOneByDeletedAt(string $page_deleted) Return the first ChildPage filtered by the page_deleted column *

 * @method     ChildPage requirePk($key, ConnectionInterface $con = null) Return the ChildPage by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPage requireOne(ConnectionInterface $con = null) Return the first ChildPage matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPage requireOneById(int $page_id) Return the first ChildPage filtered by the page_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPage requireOneBySiteId(int $site_id) Return the first ChildPage filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPage requireOneByUrl(string $page_url) Return the first ChildPage filtered by the page_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPage requireOneByTitle(string $page_title) Return the first ChildPage filtered by the page_title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPage requireOneByContent(string $page_content) Return the first ChildPage filtered by the page_content column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPage requireOneByStatus(boolean $page_status) Return the first ChildPage filtered by the page_status column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPage requireOneByInsert(string $page_insert) Return the first ChildPage filtered by the page_insert column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPage requireOneByUpdate(string $page_update) Return the first ChildPage filtered by the page_update column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPage requireOneByCreatedAt(string $page_created) Return the first ChildPage filtered by the page_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPage requireOneByUpdatedAt(string $page_updated) Return the first ChildPage filtered by the page_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPage requireOneByDeletedAt(string $page_deleted) Return the first ChildPage filtered by the page_deleted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPage[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPage objects based on current ModelCriteria
 * @method     ChildPage[]|ObjectCollection findById(int $page_id) Return ChildPage objects filtered by the page_id column
 * @method     ChildPage[]|ObjectCollection findBySiteId(int $site_id) Return ChildPage objects filtered by the site_id column
 * @method     ChildPage[]|ObjectCollection findByUrl(string $page_url) Return ChildPage objects filtered by the page_url column
 * @method     ChildPage[]|ObjectCollection findByTitle(string $page_title) Return ChildPage objects filtered by the page_title column
 * @method     ChildPage[]|ObjectCollection findByContent(string $page_content) Return ChildPage objects filtered by the page_content column
 * @method     ChildPage[]|ObjectCollection findByStatus(boolean $page_status) Return ChildPage objects filtered by the page_status column
 * @method     ChildPage[]|ObjectCollection findByInsert(string $page_insert) Return ChildPage objects filtered by the page_insert column
 * @method     ChildPage[]|ObjectCollection findByUpdate(string $page_update) Return ChildPage objects filtered by the page_update column
 * @method     ChildPage[]|ObjectCollection findByCreatedAt(string $page_created) Return ChildPage objects filtered by the page_created column
 * @method     ChildPage[]|ObjectCollection findByUpdatedAt(string $page_updated) Return ChildPage objects filtered by the page_updated column
 * @method     ChildPage[]|ObjectCollection findByDeletedAt(string $page_deleted) Return ChildPage objects filtered by the page_deleted column
 * @method     ChildPage[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PageQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\PageQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Page', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPageQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPageQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPageQuery) {
            return $criteria;
        }
        $query = new ChildPageQuery();
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
     * @return ChildPage|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PageTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PageTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildPage A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT page_id, site_id, page_url, page_title, page_content, page_status, page_insert, page_update, page_created, page_updated, page_deleted FROM pages WHERE page_id = :p0';
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
            /** @var ChildPage $obj */
            $obj = new ChildPage();
            $obj->hydrate($row);
            PageTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPage|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPageQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PageTableMap::COL_PAGE_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPageQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PageTableMap::COL_PAGE_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the page_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE page_id = 1234
     * $query->filterById(array(12, 34)); // WHERE page_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE page_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPageQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PageTableMap::COL_PAGE_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PageTableMap::COL_PAGE_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PageTableMap::COL_PAGE_ID, $id, $comparison);
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
     * @return $this|ChildPageQuery The current query, for fluid interface
     */
    public function filterBySiteId($siteId = null, $comparison = null)
    {
        if (is_array($siteId)) {
            $useMinMax = false;
            if (isset($siteId['min'])) {
                $this->addUsingAlias(PageTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(PageTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PageTableMap::COL_SITE_ID, $siteId, $comparison);
    }

    /**
     * Filter the query on the page_url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE page_url = 'fooValue'
     * $query->filterByUrl('%fooValue%', Criteria::LIKE); // WHERE page_url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $url The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPageQuery The current query, for fluid interface
     */
    public function filterByUrl($url = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($url)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PageTableMap::COL_PAGE_URL, $url, $comparison);
    }

    /**
     * Filter the query on the page_title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE page_title = 'fooValue'
     * $query->filterByTitle('%fooValue%', Criteria::LIKE); // WHERE page_title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPageQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PageTableMap::COL_PAGE_TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the page_content column
     *
     * Example usage:
     * <code>
     * $query->filterByContent('fooValue');   // WHERE page_content = 'fooValue'
     * $query->filterByContent('%fooValue%', Criteria::LIKE); // WHERE page_content LIKE '%fooValue%'
     * </code>
     *
     * @param     string $content The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPageQuery The current query, for fluid interface
     */
    public function filterByContent($content = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($content)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PageTableMap::COL_PAGE_CONTENT, $content, $comparison);
    }

    /**
     * Filter the query on the page_status column
     *
     * Example usage:
     * <code>
     * $query->filterByStatus(true); // WHERE page_status = true
     * $query->filterByStatus('yes'); // WHERE page_status = true
     * </code>
     *
     * @param     boolean|string $status The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPageQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_string($status)) {
            $status = in_array(strtolower($status), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PageTableMap::COL_PAGE_STATUS, $status, $comparison);
    }

    /**
     * Filter the query on the page_insert column
     *
     * Example usage:
     * <code>
     * $query->filterByInsert('2011-03-14'); // WHERE page_insert = '2011-03-14'
     * $query->filterByInsert('now'); // WHERE page_insert = '2011-03-14'
     * $query->filterByInsert(array('max' => 'yesterday')); // WHERE page_insert > '2011-03-13'
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
     * @return $this|ChildPageQuery The current query, for fluid interface
     */
    public function filterByInsert($insert = null, $comparison = null)
    {
        if (is_array($insert)) {
            $useMinMax = false;
            if (isset($insert['min'])) {
                $this->addUsingAlias(PageTableMap::COL_PAGE_INSERT, $insert['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($insert['max'])) {
                $this->addUsingAlias(PageTableMap::COL_PAGE_INSERT, $insert['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PageTableMap::COL_PAGE_INSERT, $insert, $comparison);
    }

    /**
     * Filter the query on the page_update column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdate('2011-03-14'); // WHERE page_update = '2011-03-14'
     * $query->filterByUpdate('now'); // WHERE page_update = '2011-03-14'
     * $query->filterByUpdate(array('max' => 'yesterday')); // WHERE page_update > '2011-03-13'
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
     * @return $this|ChildPageQuery The current query, for fluid interface
     */
    public function filterByUpdate($update = null, $comparison = null)
    {
        if (is_array($update)) {
            $useMinMax = false;
            if (isset($update['min'])) {
                $this->addUsingAlias(PageTableMap::COL_PAGE_UPDATE, $update['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($update['max'])) {
                $this->addUsingAlias(PageTableMap::COL_PAGE_UPDATE, $update['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PageTableMap::COL_PAGE_UPDATE, $update, $comparison);
    }

    /**
     * Filter the query on the page_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE page_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE page_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE page_created > '2011-03-13'
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
     * @return $this|ChildPageQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PageTableMap::COL_PAGE_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PageTableMap::COL_PAGE_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PageTableMap::COL_PAGE_CREATED, $createdAt, $comparison);
    }

    /**
     * Filter the query on the page_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE page_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE page_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE page_updated > '2011-03-13'
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
     * @return $this|ChildPageQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PageTableMap::COL_PAGE_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PageTableMap::COL_PAGE_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PageTableMap::COL_PAGE_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the page_deleted column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletedAt('2011-03-14'); // WHERE page_deleted = '2011-03-14'
     * $query->filterByDeletedAt('now'); // WHERE page_deleted = '2011-03-14'
     * $query->filterByDeletedAt(array('max' => 'yesterday')); // WHERE page_deleted > '2011-03-13'
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
     * @return $this|ChildPageQuery The current query, for fluid interface
     */
    public function filterByDeletedAt($deletedAt = null, $comparison = null)
    {
        if (is_array($deletedAt)) {
            $useMinMax = false;
            if (isset($deletedAt['min'])) {
                $this->addUsingAlias(PageTableMap::COL_PAGE_DELETED, $deletedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletedAt['max'])) {
                $this->addUsingAlias(PageTableMap::COL_PAGE_DELETED, $deletedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PageTableMap::COL_PAGE_DELETED, $deletedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPage $page Object to remove from the list of results
     *
     * @return $this|ChildPageQuery The current query, for fluid interface
     */
    public function prune($page = null)
    {
        if ($page) {
            $this->addUsingAlias(PageTableMap::COL_PAGE_ID, $page->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the pages table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PageTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PageTableMap::clearInstancePool();
            PageTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PageTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PageTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PageTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PageTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PageQuery
