<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Tag as ChildTag;
use Model\TagQuery as ChildTagQuery;
use Model\Map\TagTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'tags' table.
 *
 *
 *
 * @method     ChildTagQuery orderById($order = Criteria::ASC) Order by the tag_id column
 * @method     ChildTagQuery orderByName($order = Criteria::ASC) Order by the tag_name column
 * @method     ChildTagQuery orderByUrl($order = Criteria::ASC) Order by the tag_url column
 * @method     ChildTagQuery orderByDescription($order = Criteria::ASC) Order by the tag_description column
 * @method     ChildTagQuery orderByDate($order = Criteria::ASC) Order by the tag_date column
 * @method     ChildTagQuery orderByNum($order = Criteria::ASC) Order by the tag_num column
 * @method     ChildTagQuery orderByInsert($order = Criteria::ASC) Order by the tag_insert column
 * @method     ChildTagQuery orderByUpdate($order = Criteria::ASC) Order by the tag_update column
 * @method     ChildTagQuery orderByCreatedAt($order = Criteria::ASC) Order by the tag_created column
 * @method     ChildTagQuery orderByUpdatedAt($order = Criteria::ASC) Order by the tag_updated column
 * @method     ChildTagQuery orderByDeletedAt($order = Criteria::ASC) Order by the tag_deleted column
 *
 * @method     ChildTagQuery groupById() Group by the tag_id column
 * @method     ChildTagQuery groupByName() Group by the tag_name column
 * @method     ChildTagQuery groupByUrl() Group by the tag_url column
 * @method     ChildTagQuery groupByDescription() Group by the tag_description column
 * @method     ChildTagQuery groupByDate() Group by the tag_date column
 * @method     ChildTagQuery groupByNum() Group by the tag_num column
 * @method     ChildTagQuery groupByInsert() Group by the tag_insert column
 * @method     ChildTagQuery groupByUpdate() Group by the tag_update column
 * @method     ChildTagQuery groupByCreatedAt() Group by the tag_created column
 * @method     ChildTagQuery groupByUpdatedAt() Group by the tag_updated column
 * @method     ChildTagQuery groupByDeletedAt() Group by the tag_deleted column
 *
 * @method     ChildTagQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildTagQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildTagQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildTagQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildTagQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildTagQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildTag|null findOne(ConnectionInterface $con = null) Return the first ChildTag matching the query
 * @method     ChildTag findOneOrCreate(ConnectionInterface $con = null) Return the first ChildTag matching the query, or a new ChildTag object populated from the query conditions when no match is found
 *
 * @method     ChildTag|null findOneById(int $tag_id) Return the first ChildTag filtered by the tag_id column
 * @method     ChildTag|null findOneByName(string $tag_name) Return the first ChildTag filtered by the tag_name column
 * @method     ChildTag|null findOneByUrl(string $tag_url) Return the first ChildTag filtered by the tag_url column
 * @method     ChildTag|null findOneByDescription(string $tag_description) Return the first ChildTag filtered by the tag_description column
 * @method     ChildTag|null findOneByDate(string $tag_date) Return the first ChildTag filtered by the tag_date column
 * @method     ChildTag|null findOneByNum(int $tag_num) Return the first ChildTag filtered by the tag_num column
 * @method     ChildTag|null findOneByInsert(string $tag_insert) Return the first ChildTag filtered by the tag_insert column
 * @method     ChildTag|null findOneByUpdate(string $tag_update) Return the first ChildTag filtered by the tag_update column
 * @method     ChildTag|null findOneByCreatedAt(string $tag_created) Return the first ChildTag filtered by the tag_created column
 * @method     ChildTag|null findOneByUpdatedAt(string $tag_updated) Return the first ChildTag filtered by the tag_updated column
 * @method     ChildTag|null findOneByDeletedAt(string $tag_deleted) Return the first ChildTag filtered by the tag_deleted column *

 * @method     ChildTag requirePk($key, ConnectionInterface $con = null) Return the ChildTag by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTag requireOne(ConnectionInterface $con = null) Return the first ChildTag matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildTag requireOneById(int $tag_id) Return the first ChildTag filtered by the tag_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTag requireOneByName(string $tag_name) Return the first ChildTag filtered by the tag_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTag requireOneByUrl(string $tag_url) Return the first ChildTag filtered by the tag_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTag requireOneByDescription(string $tag_description) Return the first ChildTag filtered by the tag_description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTag requireOneByDate(string $tag_date) Return the first ChildTag filtered by the tag_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTag requireOneByNum(int $tag_num) Return the first ChildTag filtered by the tag_num column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTag requireOneByInsert(string $tag_insert) Return the first ChildTag filtered by the tag_insert column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTag requireOneByUpdate(string $tag_update) Return the first ChildTag filtered by the tag_update column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTag requireOneByCreatedAt(string $tag_created) Return the first ChildTag filtered by the tag_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTag requireOneByUpdatedAt(string $tag_updated) Return the first ChildTag filtered by the tag_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTag requireOneByDeletedAt(string $tag_deleted) Return the first ChildTag filtered by the tag_deleted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildTag[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildTag objects based on current ModelCriteria
 * @method     ChildTag[]|ObjectCollection findById(int $tag_id) Return ChildTag objects filtered by the tag_id column
 * @method     ChildTag[]|ObjectCollection findByName(string $tag_name) Return ChildTag objects filtered by the tag_name column
 * @method     ChildTag[]|ObjectCollection findByUrl(string $tag_url) Return ChildTag objects filtered by the tag_url column
 * @method     ChildTag[]|ObjectCollection findByDescription(string $tag_description) Return ChildTag objects filtered by the tag_description column
 * @method     ChildTag[]|ObjectCollection findByDate(string $tag_date) Return ChildTag objects filtered by the tag_date column
 * @method     ChildTag[]|ObjectCollection findByNum(int $tag_num) Return ChildTag objects filtered by the tag_num column
 * @method     ChildTag[]|ObjectCollection findByInsert(string $tag_insert) Return ChildTag objects filtered by the tag_insert column
 * @method     ChildTag[]|ObjectCollection findByUpdate(string $tag_update) Return ChildTag objects filtered by the tag_update column
 * @method     ChildTag[]|ObjectCollection findByCreatedAt(string $tag_created) Return ChildTag objects filtered by the tag_created column
 * @method     ChildTag[]|ObjectCollection findByUpdatedAt(string $tag_updated) Return ChildTag objects filtered by the tag_updated column
 * @method     ChildTag[]|ObjectCollection findByDeletedAt(string $tag_deleted) Return ChildTag objects filtered by the tag_deleted column
 * @method     ChildTag[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class TagQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\TagQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Tag', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildTagQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildTagQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildTagQuery) {
            return $criteria;
        }
        $query = new ChildTagQuery();
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
     * @return ChildTag|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(TagTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = TagTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildTag A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT tag_id, tag_name, tag_url, tag_description, tag_date, tag_num, tag_insert, tag_update, tag_created, tag_updated, tag_deleted FROM tags WHERE tag_id = :p0';
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
            /** @var ChildTag $obj */
            $obj = new ChildTag();
            $obj->hydrate($row);
            TagTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildTag|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildTagQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(TagTableMap::COL_TAG_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildTagQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(TagTableMap::COL_TAG_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the tag_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE tag_id = 1234
     * $query->filterById(array(12, 34)); // WHERE tag_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE tag_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTagQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(TagTableMap::COL_TAG_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(TagTableMap::COL_TAG_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TagTableMap::COL_TAG_ID, $id, $comparison);
    }

    /**
     * Filter the query on the tag_name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE tag_name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE tag_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTagQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TagTableMap::COL_TAG_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the tag_url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE tag_url = 'fooValue'
     * $query->filterByUrl('%fooValue%', Criteria::LIKE); // WHERE tag_url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $url The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTagQuery The current query, for fluid interface
     */
    public function filterByUrl($url = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($url)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TagTableMap::COL_TAG_URL, $url, $comparison);
    }

    /**
     * Filter the query on the tag_description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE tag_description = 'fooValue'
     * $query->filterByDescription('%fooValue%', Criteria::LIKE); // WHERE tag_description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTagQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TagTableMap::COL_TAG_DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the tag_date column
     *
     * Example usage:
     * <code>
     * $query->filterByDate('2011-03-14'); // WHERE tag_date = '2011-03-14'
     * $query->filterByDate('now'); // WHERE tag_date = '2011-03-14'
     * $query->filterByDate(array('max' => 'yesterday')); // WHERE tag_date > '2011-03-13'
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
     * @return $this|ChildTagQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(TagTableMap::COL_TAG_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(TagTableMap::COL_TAG_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TagTableMap::COL_TAG_DATE, $date, $comparison);
    }

    /**
     * Filter the query on the tag_num column
     *
     * Example usage:
     * <code>
     * $query->filterByNum(1234); // WHERE tag_num = 1234
     * $query->filterByNum(array(12, 34)); // WHERE tag_num IN (12, 34)
     * $query->filterByNum(array('min' => 12)); // WHERE tag_num > 12
     * </code>
     *
     * @param     mixed $num The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTagQuery The current query, for fluid interface
     */
    public function filterByNum($num = null, $comparison = null)
    {
        if (is_array($num)) {
            $useMinMax = false;
            if (isset($num['min'])) {
                $this->addUsingAlias(TagTableMap::COL_TAG_NUM, $num['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($num['max'])) {
                $this->addUsingAlias(TagTableMap::COL_TAG_NUM, $num['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TagTableMap::COL_TAG_NUM, $num, $comparison);
    }

    /**
     * Filter the query on the tag_insert column
     *
     * Example usage:
     * <code>
     * $query->filterByInsert('2011-03-14'); // WHERE tag_insert = '2011-03-14'
     * $query->filterByInsert('now'); // WHERE tag_insert = '2011-03-14'
     * $query->filterByInsert(array('max' => 'yesterday')); // WHERE tag_insert > '2011-03-13'
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
     * @return $this|ChildTagQuery The current query, for fluid interface
     */
    public function filterByInsert($insert = null, $comparison = null)
    {
        if (is_array($insert)) {
            $useMinMax = false;
            if (isset($insert['min'])) {
                $this->addUsingAlias(TagTableMap::COL_TAG_INSERT, $insert['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($insert['max'])) {
                $this->addUsingAlias(TagTableMap::COL_TAG_INSERT, $insert['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TagTableMap::COL_TAG_INSERT, $insert, $comparison);
    }

    /**
     * Filter the query on the tag_update column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdate('2011-03-14'); // WHERE tag_update = '2011-03-14'
     * $query->filterByUpdate('now'); // WHERE tag_update = '2011-03-14'
     * $query->filterByUpdate(array('max' => 'yesterday')); // WHERE tag_update > '2011-03-13'
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
     * @return $this|ChildTagQuery The current query, for fluid interface
     */
    public function filterByUpdate($update = null, $comparison = null)
    {
        if (is_array($update)) {
            $useMinMax = false;
            if (isset($update['min'])) {
                $this->addUsingAlias(TagTableMap::COL_TAG_UPDATE, $update['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($update['max'])) {
                $this->addUsingAlias(TagTableMap::COL_TAG_UPDATE, $update['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TagTableMap::COL_TAG_UPDATE, $update, $comparison);
    }

    /**
     * Filter the query on the tag_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE tag_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE tag_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE tag_created > '2011-03-13'
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
     * @return $this|ChildTagQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(TagTableMap::COL_TAG_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(TagTableMap::COL_TAG_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TagTableMap::COL_TAG_CREATED, $createdAt, $comparison);
    }

    /**
     * Filter the query on the tag_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE tag_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE tag_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE tag_updated > '2011-03-13'
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
     * @return $this|ChildTagQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(TagTableMap::COL_TAG_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(TagTableMap::COL_TAG_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TagTableMap::COL_TAG_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the tag_deleted column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletedAt('2011-03-14'); // WHERE tag_deleted = '2011-03-14'
     * $query->filterByDeletedAt('now'); // WHERE tag_deleted = '2011-03-14'
     * $query->filterByDeletedAt(array('max' => 'yesterday')); // WHERE tag_deleted > '2011-03-13'
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
     * @return $this|ChildTagQuery The current query, for fluid interface
     */
    public function filterByDeletedAt($deletedAt = null, $comparison = null)
    {
        if (is_array($deletedAt)) {
            $useMinMax = false;
            if (isset($deletedAt['min'])) {
                $this->addUsingAlias(TagTableMap::COL_TAG_DELETED, $deletedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletedAt['max'])) {
                $this->addUsingAlias(TagTableMap::COL_TAG_DELETED, $deletedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TagTableMap::COL_TAG_DELETED, $deletedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildTag $tag Object to remove from the list of results
     *
     * @return $this|ChildTagQuery The current query, for fluid interface
     */
    public function prune($tag = null)
    {
        if ($tag) {
            $this->addUsingAlias(TagTableMap::COL_TAG_ID, $tag->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the tags table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(TagTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            TagTableMap::clearInstancePool();
            TagTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(TagTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(TagTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            TagTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            TagTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // TagQuery
