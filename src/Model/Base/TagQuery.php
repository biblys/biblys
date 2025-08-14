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
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `tags` table.
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
 *
 * @method     ChildTagQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildTagQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildTagQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildTagQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildTagQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildTagQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildTagQuery leftJoinLink($relationAlias = null) Adds a LEFT JOIN clause to the query using the Link relation
 * @method     ChildTagQuery rightJoinLink($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Link relation
 * @method     ChildTagQuery innerJoinLink($relationAlias = null) Adds a INNER JOIN clause to the query using the Link relation
 *
 * @method     ChildTagQuery joinWithLink($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Link relation
 *
 * @method     ChildTagQuery leftJoinWithLink() Adds a LEFT JOIN clause and with to the query using the Link relation
 * @method     ChildTagQuery rightJoinWithLink() Adds a RIGHT JOIN clause and with to the query using the Link relation
 * @method     ChildTagQuery innerJoinWithLink() Adds a INNER JOIN clause and with to the query using the Link relation
 *
 * @method     ChildTagQuery leftJoinArticleTag($relationAlias = null) Adds a LEFT JOIN clause to the query using the ArticleTag relation
 * @method     ChildTagQuery rightJoinArticleTag($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ArticleTag relation
 * @method     ChildTagQuery innerJoinArticleTag($relationAlias = null) Adds a INNER JOIN clause to the query using the ArticleTag relation
 *
 * @method     ChildTagQuery joinWithArticleTag($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ArticleTag relation
 *
 * @method     ChildTagQuery leftJoinWithArticleTag() Adds a LEFT JOIN clause and with to the query using the ArticleTag relation
 * @method     ChildTagQuery rightJoinWithArticleTag() Adds a RIGHT JOIN clause and with to the query using the ArticleTag relation
 * @method     ChildTagQuery innerJoinWithArticleTag() Adds a INNER JOIN clause and with to the query using the ArticleTag relation
 *
 * @method     \Model\LinkQuery|\Model\ArticleTagQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildTag|null findOne(?ConnectionInterface $con = null) Return the first ChildTag matching the query
 * @method     ChildTag findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildTag matching the query, or a new ChildTag object populated from the query conditions when no match is found
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
 *
 * @method     ChildTag requirePk($key, ?ConnectionInterface $con = null) Return the ChildTag by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTag requireOne(?ConnectionInterface $con = null) Return the first ChildTag matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
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
 *
 * @method     ChildTag[]|Collection find(?ConnectionInterface $con = null) Return ChildTag objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildTag> find(?ConnectionInterface $con = null) Return ChildTag objects based on current ModelCriteria
 *
 * @method     ChildTag[]|Collection findById(int|array<int> $tag_id) Return ChildTag objects filtered by the tag_id column
 * @psalm-method Collection&\Traversable<ChildTag> findById(int|array<int> $tag_id) Return ChildTag objects filtered by the tag_id column
 * @method     ChildTag[]|Collection findByName(string|array<string> $tag_name) Return ChildTag objects filtered by the tag_name column
 * @psalm-method Collection&\Traversable<ChildTag> findByName(string|array<string> $tag_name) Return ChildTag objects filtered by the tag_name column
 * @method     ChildTag[]|Collection findByUrl(string|array<string> $tag_url) Return ChildTag objects filtered by the tag_url column
 * @psalm-method Collection&\Traversable<ChildTag> findByUrl(string|array<string> $tag_url) Return ChildTag objects filtered by the tag_url column
 * @method     ChildTag[]|Collection findByDescription(string|array<string> $tag_description) Return ChildTag objects filtered by the tag_description column
 * @psalm-method Collection&\Traversable<ChildTag> findByDescription(string|array<string> $tag_description) Return ChildTag objects filtered by the tag_description column
 * @method     ChildTag[]|Collection findByDate(string|array<string> $tag_date) Return ChildTag objects filtered by the tag_date column
 * @psalm-method Collection&\Traversable<ChildTag> findByDate(string|array<string> $tag_date) Return ChildTag objects filtered by the tag_date column
 * @method     ChildTag[]|Collection findByNum(int|array<int> $tag_num) Return ChildTag objects filtered by the tag_num column
 * @psalm-method Collection&\Traversable<ChildTag> findByNum(int|array<int> $tag_num) Return ChildTag objects filtered by the tag_num column
 * @method     ChildTag[]|Collection findByInsert(string|array<string> $tag_insert) Return ChildTag objects filtered by the tag_insert column
 * @psalm-method Collection&\Traversable<ChildTag> findByInsert(string|array<string> $tag_insert) Return ChildTag objects filtered by the tag_insert column
 * @method     ChildTag[]|Collection findByUpdate(string|array<string> $tag_update) Return ChildTag objects filtered by the tag_update column
 * @psalm-method Collection&\Traversable<ChildTag> findByUpdate(string|array<string> $tag_update) Return ChildTag objects filtered by the tag_update column
 * @method     ChildTag[]|Collection findByCreatedAt(string|array<string> $tag_created) Return ChildTag objects filtered by the tag_created column
 * @psalm-method Collection&\Traversable<ChildTag> findByCreatedAt(string|array<string> $tag_created) Return ChildTag objects filtered by the tag_created column
 * @method     ChildTag[]|Collection findByUpdatedAt(string|array<string> $tag_updated) Return ChildTag objects filtered by the tag_updated column
 * @psalm-method Collection&\Traversable<ChildTag> findByUpdatedAt(string|array<string> $tag_updated) Return ChildTag objects filtered by the tag_updated column
 *
 * @method     ChildTag[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildTag> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class TagQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\TagQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Tag', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildTagQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildTagQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
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
    public function findPk($key, ?ConnectionInterface $con = null)
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
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildTag A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT tag_id, tag_name, tag_url, tag_description, tag_date, tag_num, tag_insert, tag_update, tag_created, tag_updated FROM tags WHERE tag_id = :p0';
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
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
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

        $this->addUsingAlias(TagTableMap::COL_TAG_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(TagTableMap::COL_TAG_ID, $keys, Criteria::IN);

        return $this;
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

        $this->addUsingAlias(TagTableMap::COL_TAG_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the tag_name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE tag_name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE tag_name LIKE '%fooValue%'
     * $query->filterByName(['foo', 'bar']); // WHERE tag_name IN ('foo', 'bar')
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

        $this->addUsingAlias(TagTableMap::COL_TAG_NAME, $name, $comparison);

        return $this;
    }

    /**
     * Filter the query on the tag_url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE tag_url = 'fooValue'
     * $query->filterByUrl('%fooValue%', Criteria::LIKE); // WHERE tag_url LIKE '%fooValue%'
     * $query->filterByUrl(['foo', 'bar']); // WHERE tag_url IN ('foo', 'bar')
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

        $this->addUsingAlias(TagTableMap::COL_TAG_URL, $url, $comparison);

        return $this;
    }

    /**
     * Filter the query on the tag_description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE tag_description = 'fooValue'
     * $query->filterByDescription('%fooValue%', Criteria::LIKE); // WHERE tag_description LIKE '%fooValue%'
     * $query->filterByDescription(['foo', 'bar']); // WHERE tag_description IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $description The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDescription($description = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(TagTableMap::COL_TAG_DESCRIPTION, $description, $comparison);

        return $this;
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
     * @param mixed $date The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDate($date = null, ?string $comparison = null)
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

        $this->addUsingAlias(TagTableMap::COL_TAG_DATE, $date, $comparison);

        return $this;
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
     * @param mixed $num The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByNum($num = null, ?string $comparison = null)
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

        $this->addUsingAlias(TagTableMap::COL_TAG_NUM, $num, $comparison);

        return $this;
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

        $this->addUsingAlias(TagTableMap::COL_TAG_INSERT, $insert, $comparison);

        return $this;
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

        $this->addUsingAlias(TagTableMap::COL_TAG_UPDATE, $update, $comparison);

        return $this;
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

        $this->addUsingAlias(TagTableMap::COL_TAG_CREATED, $createdAt, $comparison);

        return $this;
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

        $this->addUsingAlias(TagTableMap::COL_TAG_UPDATED, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \Model\Link object
     *
     * @param \Model\Link|ObjectCollection $link the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByLink($link, ?string $comparison = null)
    {
        if ($link instanceof \Model\Link) {
            $this
                ->addUsingAlias(TagTableMap::COL_TAG_ID, $link->getTagId(), $comparison);

            return $this;
        } elseif ($link instanceof ObjectCollection) {
            $this
                ->useLinkQuery()
                ->filterByPrimaryKeys($link->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByLink() only accepts arguments of type \Model\Link or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Link relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinLink(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Link');

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
            $this->addJoinObject($join, 'Link');
        }

        return $this;
    }

    /**
     * Use the Link relation Link object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\LinkQuery A secondary query class using the current class as primary query
     */
    public function useLinkQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinLink($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Link', '\Model\LinkQuery');
    }

    /**
     * Use the Link relation Link object
     *
     * @param callable(\Model\LinkQuery):\Model\LinkQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withLinkQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useLinkQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Link table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\LinkQuery The inner query object of the EXISTS statement
     */
    public function useLinkExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\LinkQuery */
        $q = $this->useExistsQuery('Link', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Link table for a NOT EXISTS query.
     *
     * @see useLinkExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\LinkQuery The inner query object of the NOT EXISTS statement
     */
    public function useLinkNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\LinkQuery */
        $q = $this->useExistsQuery('Link', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Link table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\LinkQuery The inner query object of the IN statement
     */
    public function useInLinkQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\LinkQuery */
        $q = $this->useInQuery('Link', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Link table for a NOT IN query.
     *
     * @see useLinkInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\LinkQuery The inner query object of the NOT IN statement
     */
    public function useNotInLinkQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\LinkQuery */
        $q = $this->useInQuery('Link', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\ArticleTag object
     *
     * @param \Model\ArticleTag|ObjectCollection $articleTag the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByArticleTag($articleTag, ?string $comparison = null)
    {
        if ($articleTag instanceof \Model\ArticleTag) {
            $this
                ->addUsingAlias(TagTableMap::COL_TAG_ID, $articleTag->getTagId(), $comparison);

            return $this;
        } elseif ($articleTag instanceof ObjectCollection) {
            $this
                ->useArticleTagQuery()
                ->filterByPrimaryKeys($articleTag->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByArticleTag() only accepts arguments of type \Model\ArticleTag or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ArticleTag relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinArticleTag(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ArticleTag');

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
            $this->addJoinObject($join, 'ArticleTag');
        }

        return $this;
    }

    /**
     * Use the ArticleTag relation ArticleTag object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\ArticleTagQuery A secondary query class using the current class as primary query
     */
    public function useArticleTagQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinArticleTag($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ArticleTag', '\Model\ArticleTagQuery');
    }

    /**
     * Use the ArticleTag relation ArticleTag object
     *
     * @param callable(\Model\ArticleTagQuery):\Model\ArticleTagQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withArticleTagQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useArticleTagQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to ArticleTag table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\ArticleTagQuery The inner query object of the EXISTS statement
     */
    public function useArticleTagExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\ArticleTagQuery */
        $q = $this->useExistsQuery('ArticleTag', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to ArticleTag table for a NOT EXISTS query.
     *
     * @see useArticleTagExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\ArticleTagQuery The inner query object of the NOT EXISTS statement
     */
    public function useArticleTagNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\ArticleTagQuery */
        $q = $this->useExistsQuery('ArticleTag', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to ArticleTag table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\ArticleTagQuery The inner query object of the IN statement
     */
    public function useInArticleTagQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\ArticleTagQuery */
        $q = $this->useInQuery('ArticleTag', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to ArticleTag table for a NOT IN query.
     *
     * @see useArticleTagInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\ArticleTagQuery The inner query object of the NOT IN statement
     */
    public function useNotInArticleTagQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\ArticleTagQuery */
        $q = $this->useInQuery('ArticleTag', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related Article object
     * using the tags_articles table as cross reference
     *
     * @param Article $article the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL and Criteria::IN for queries
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByArticle($article, string $comparison = null)
    {
        $this
            ->useArticleTagQuery()
            ->filterByArticle($article, $comparison)
            ->endUse();

        return $this;
    }

    /**
     * Exclude object from result
     *
     * @param ChildTag $tag Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
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
    public function doDeleteAll(?ConnectionInterface $con = null): int
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
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(?ConnectionInterface $con = null): int
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
        $this->addUsingAlias(TagTableMap::COL_TAG_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(TagTableMap::COL_TAG_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(TagTableMap::COL_TAG_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(TagTableMap::COL_TAG_CREATED);

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
        $this->addUsingAlias(TagTableMap::COL_TAG_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(TagTableMap::COL_TAG_CREATED);

        return $this;
    }

}
