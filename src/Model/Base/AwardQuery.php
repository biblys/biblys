<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Award as ChildAward;
use Model\AwardQuery as ChildAwardQuery;
use Model\Map\AwardTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'awards' table.
 *
 *
 *
 * @method     ChildAwardQuery orderById($order = Criteria::ASC) Order by the award_id column
 * @method     ChildAwardQuery orderByArticleId($order = Criteria::ASC) Order by the article_id column
 * @method     ChildAwardQuery orderByBookId($order = Criteria::ASC) Order by the book_id column
 * @method     ChildAwardQuery orderByName($order = Criteria::ASC) Order by the award_name column
 * @method     ChildAwardQuery orderByYear($order = Criteria::ASC) Order by the award_year column
 * @method     ChildAwardQuery orderByCategory($order = Criteria::ASC) Order by the award_category column
 * @method     ChildAwardQuery orderByNote($order = Criteria::ASC) Order by the award_note column
 * @method     ChildAwardQuery orderByDate($order = Criteria::ASC) Order by the award_date column
 * @method     ChildAwardQuery orderByCreatedAt($order = Criteria::ASC) Order by the award_created column
 * @method     ChildAwardQuery orderByUpdatedAt($order = Criteria::ASC) Order by the award_updated column
 * @method     ChildAwardQuery orderByDeletedAt($order = Criteria::ASC) Order by the award_deleted column
 *
 * @method     ChildAwardQuery groupById() Group by the award_id column
 * @method     ChildAwardQuery groupByArticleId() Group by the article_id column
 * @method     ChildAwardQuery groupByBookId() Group by the book_id column
 * @method     ChildAwardQuery groupByName() Group by the award_name column
 * @method     ChildAwardQuery groupByYear() Group by the award_year column
 * @method     ChildAwardQuery groupByCategory() Group by the award_category column
 * @method     ChildAwardQuery groupByNote() Group by the award_note column
 * @method     ChildAwardQuery groupByDate() Group by the award_date column
 * @method     ChildAwardQuery groupByCreatedAt() Group by the award_created column
 * @method     ChildAwardQuery groupByUpdatedAt() Group by the award_updated column
 * @method     ChildAwardQuery groupByDeletedAt() Group by the award_deleted column
 *
 * @method     ChildAwardQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildAwardQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildAwardQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildAwardQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildAwardQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildAwardQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildAward|null findOne(ConnectionInterface $con = null) Return the first ChildAward matching the query
 * @method     ChildAward findOneOrCreate(ConnectionInterface $con = null) Return the first ChildAward matching the query, or a new ChildAward object populated from the query conditions when no match is found
 *
 * @method     ChildAward|null findOneById(int $award_id) Return the first ChildAward filtered by the award_id column
 * @method     ChildAward|null findOneByArticleId(int $article_id) Return the first ChildAward filtered by the article_id column
 * @method     ChildAward|null findOneByBookId(int $book_id) Return the first ChildAward filtered by the book_id column
 * @method     ChildAward|null findOneByName(string $award_name) Return the first ChildAward filtered by the award_name column
 * @method     ChildAward|null findOneByYear(string $award_year) Return the first ChildAward filtered by the award_year column
 * @method     ChildAward|null findOneByCategory(string $award_category) Return the first ChildAward filtered by the award_category column
 * @method     ChildAward|null findOneByNote(string $award_note) Return the first ChildAward filtered by the award_note column
 * @method     ChildAward|null findOneByDate(string $award_date) Return the first ChildAward filtered by the award_date column
 * @method     ChildAward|null findOneByCreatedAt(string $award_created) Return the first ChildAward filtered by the award_created column
 * @method     ChildAward|null findOneByUpdatedAt(string $award_updated) Return the first ChildAward filtered by the award_updated column
 * @method     ChildAward|null findOneByDeletedAt(string $award_deleted) Return the first ChildAward filtered by the award_deleted column *

 * @method     ChildAward requirePk($key, ConnectionInterface $con = null) Return the ChildAward by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAward requireOne(ConnectionInterface $con = null) Return the first ChildAward matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAward requireOneById(int $award_id) Return the first ChildAward filtered by the award_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAward requireOneByArticleId(int $article_id) Return the first ChildAward filtered by the article_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAward requireOneByBookId(int $book_id) Return the first ChildAward filtered by the book_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAward requireOneByName(string $award_name) Return the first ChildAward filtered by the award_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAward requireOneByYear(string $award_year) Return the first ChildAward filtered by the award_year column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAward requireOneByCategory(string $award_category) Return the first ChildAward filtered by the award_category column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAward requireOneByNote(string $award_note) Return the first ChildAward filtered by the award_note column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAward requireOneByDate(string $award_date) Return the first ChildAward filtered by the award_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAward requireOneByCreatedAt(string $award_created) Return the first ChildAward filtered by the award_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAward requireOneByUpdatedAt(string $award_updated) Return the first ChildAward filtered by the award_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAward requireOneByDeletedAt(string $award_deleted) Return the first ChildAward filtered by the award_deleted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAward[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildAward objects based on current ModelCriteria
 * @method     ChildAward[]|ObjectCollection findById(int $award_id) Return ChildAward objects filtered by the award_id column
 * @method     ChildAward[]|ObjectCollection findByArticleId(int $article_id) Return ChildAward objects filtered by the article_id column
 * @method     ChildAward[]|ObjectCollection findByBookId(int $book_id) Return ChildAward objects filtered by the book_id column
 * @method     ChildAward[]|ObjectCollection findByName(string $award_name) Return ChildAward objects filtered by the award_name column
 * @method     ChildAward[]|ObjectCollection findByYear(string $award_year) Return ChildAward objects filtered by the award_year column
 * @method     ChildAward[]|ObjectCollection findByCategory(string $award_category) Return ChildAward objects filtered by the award_category column
 * @method     ChildAward[]|ObjectCollection findByNote(string $award_note) Return ChildAward objects filtered by the award_note column
 * @method     ChildAward[]|ObjectCollection findByDate(string $award_date) Return ChildAward objects filtered by the award_date column
 * @method     ChildAward[]|ObjectCollection findByCreatedAt(string $award_created) Return ChildAward objects filtered by the award_created column
 * @method     ChildAward[]|ObjectCollection findByUpdatedAt(string $award_updated) Return ChildAward objects filtered by the award_updated column
 * @method     ChildAward[]|ObjectCollection findByDeletedAt(string $award_deleted) Return ChildAward objects filtered by the award_deleted column
 * @method     ChildAward[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class AwardQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\AwardQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Award', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildAwardQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildAwardQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildAwardQuery) {
            return $criteria;
        }
        $query = new ChildAwardQuery();
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
     * @return ChildAward|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(AwardTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = AwardTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildAward A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT award_id, article_id, book_id, award_name, award_year, award_category, award_note, award_date, award_created, award_updated, award_deleted FROM awards WHERE award_id = :p0';
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
            /** @var ChildAward $obj */
            $obj = new ChildAward();
            $obj->hydrate($row);
            AwardTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildAward|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildAwardQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(AwardTableMap::COL_AWARD_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildAwardQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(AwardTableMap::COL_AWARD_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the award_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE award_id = 1234
     * $query->filterById(array(12, 34)); // WHERE award_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE award_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAwardQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(AwardTableMap::COL_AWARD_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(AwardTableMap::COL_AWARD_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AwardTableMap::COL_AWARD_ID, $id, $comparison);
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
     * @return $this|ChildAwardQuery The current query, for fluid interface
     */
    public function filterByArticleId($articleId = null, $comparison = null)
    {
        if (is_array($articleId)) {
            $useMinMax = false;
            if (isset($articleId['min'])) {
                $this->addUsingAlias(AwardTableMap::COL_ARTICLE_ID, $articleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($articleId['max'])) {
                $this->addUsingAlias(AwardTableMap::COL_ARTICLE_ID, $articleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AwardTableMap::COL_ARTICLE_ID, $articleId, $comparison);
    }

    /**
     * Filter the query on the book_id column
     *
     * Example usage:
     * <code>
     * $query->filterByBookId(1234); // WHERE book_id = 1234
     * $query->filterByBookId(array(12, 34)); // WHERE book_id IN (12, 34)
     * $query->filterByBookId(array('min' => 12)); // WHERE book_id > 12
     * </code>
     *
     * @param     mixed $bookId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAwardQuery The current query, for fluid interface
     */
    public function filterByBookId($bookId = null, $comparison = null)
    {
        if (is_array($bookId)) {
            $useMinMax = false;
            if (isset($bookId['min'])) {
                $this->addUsingAlias(AwardTableMap::COL_BOOK_ID, $bookId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bookId['max'])) {
                $this->addUsingAlias(AwardTableMap::COL_BOOK_ID, $bookId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AwardTableMap::COL_BOOK_ID, $bookId, $comparison);
    }

    /**
     * Filter the query on the award_name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE award_name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE award_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAwardQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AwardTableMap::COL_AWARD_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the award_year column
     *
     * Example usage:
     * <code>
     * $query->filterByYear('fooValue');   // WHERE award_year = 'fooValue'
     * $query->filterByYear('%fooValue%', Criteria::LIKE); // WHERE award_year LIKE '%fooValue%'
     * </code>
     *
     * @param     string $year The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAwardQuery The current query, for fluid interface
     */
    public function filterByYear($year = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($year)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AwardTableMap::COL_AWARD_YEAR, $year, $comparison);
    }

    /**
     * Filter the query on the award_category column
     *
     * Example usage:
     * <code>
     * $query->filterByCategory('fooValue');   // WHERE award_category = 'fooValue'
     * $query->filterByCategory('%fooValue%', Criteria::LIKE); // WHERE award_category LIKE '%fooValue%'
     * </code>
     *
     * @param     string $category The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAwardQuery The current query, for fluid interface
     */
    public function filterByCategory($category = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($category)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AwardTableMap::COL_AWARD_CATEGORY, $category, $comparison);
    }

    /**
     * Filter the query on the award_note column
     *
     * Example usage:
     * <code>
     * $query->filterByNote('fooValue');   // WHERE award_note = 'fooValue'
     * $query->filterByNote('%fooValue%', Criteria::LIKE); // WHERE award_note LIKE '%fooValue%'
     * </code>
     *
     * @param     string $note The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAwardQuery The current query, for fluid interface
     */
    public function filterByNote($note = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($note)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AwardTableMap::COL_AWARD_NOTE, $note, $comparison);
    }

    /**
     * Filter the query on the award_date column
     *
     * Example usage:
     * <code>
     * $query->filterByDate('2011-03-14'); // WHERE award_date = '2011-03-14'
     * $query->filterByDate('now'); // WHERE award_date = '2011-03-14'
     * $query->filterByDate(array('max' => 'yesterday')); // WHERE award_date > '2011-03-13'
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
     * @return $this|ChildAwardQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(AwardTableMap::COL_AWARD_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(AwardTableMap::COL_AWARD_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AwardTableMap::COL_AWARD_DATE, $date, $comparison);
    }

    /**
     * Filter the query on the award_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE award_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE award_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE award_created > '2011-03-13'
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
     * @return $this|ChildAwardQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(AwardTableMap::COL_AWARD_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(AwardTableMap::COL_AWARD_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AwardTableMap::COL_AWARD_CREATED, $createdAt, $comparison);
    }

    /**
     * Filter the query on the award_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE award_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE award_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE award_updated > '2011-03-13'
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
     * @return $this|ChildAwardQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(AwardTableMap::COL_AWARD_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(AwardTableMap::COL_AWARD_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AwardTableMap::COL_AWARD_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the award_deleted column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletedAt('2011-03-14'); // WHERE award_deleted = '2011-03-14'
     * $query->filterByDeletedAt('now'); // WHERE award_deleted = '2011-03-14'
     * $query->filterByDeletedAt(array('max' => 'yesterday')); // WHERE award_deleted > '2011-03-13'
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
     * @return $this|ChildAwardQuery The current query, for fluid interface
     */
    public function filterByDeletedAt($deletedAt = null, $comparison = null)
    {
        if (is_array($deletedAt)) {
            $useMinMax = false;
            if (isset($deletedAt['min'])) {
                $this->addUsingAlias(AwardTableMap::COL_AWARD_DELETED, $deletedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletedAt['max'])) {
                $this->addUsingAlias(AwardTableMap::COL_AWARD_DELETED, $deletedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AwardTableMap::COL_AWARD_DELETED, $deletedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildAward $award Object to remove from the list of results
     *
     * @return $this|ChildAwardQuery The current query, for fluid interface
     */
    public function prune($award = null)
    {
        if ($award) {
            $this->addUsingAlias(AwardTableMap::COL_AWARD_ID, $award->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the awards table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AwardTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            AwardTableMap::clearInstancePool();
            AwardTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(AwardTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(AwardTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            AwardTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            AwardTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // AwardQuery
