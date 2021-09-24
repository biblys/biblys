<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Lang as ChildLang;
use Model\LangQuery as ChildLangQuery;
use Model\Map\LangTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'langs' table.
 *
 *
 *
 * @method     ChildLangQuery orderById($order = Criteria::ASC) Order by the lang_id column
 * @method     ChildLangQuery orderByIso639-1($order = Criteria::ASC) Order by the lang_iso_639-1 column
 * @method     ChildLangQuery orderByIso639-2($order = Criteria::ASC) Order by the lang_iso_639-2 column
 * @method     ChildLangQuery orderByIso639-3($order = Criteria::ASC) Order by the lang_iso_639-3 column
 * @method     ChildLangQuery orderByName($order = Criteria::ASC) Order by the lang_name column
 * @method     ChildLangQuery orderByNameOriginal($order = Criteria::ASC) Order by the lang_name_original column
 * @method     ChildLangQuery orderByCreatedAt($order = Criteria::ASC) Order by the lang_created column
 * @method     ChildLangQuery orderByUpdatedAt($order = Criteria::ASC) Order by the lang_updated column
 * @method     ChildLangQuery orderByDeletedAt($order = Criteria::ASC) Order by the lang_deleted column
 *
 * @method     ChildLangQuery groupById() Group by the lang_id column
 * @method     ChildLangQuery groupByIso639-1() Group by the lang_iso_639-1 column
 * @method     ChildLangQuery groupByIso639-2() Group by the lang_iso_639-2 column
 * @method     ChildLangQuery groupByIso639-3() Group by the lang_iso_639-3 column
 * @method     ChildLangQuery groupByName() Group by the lang_name column
 * @method     ChildLangQuery groupByNameOriginal() Group by the lang_name_original column
 * @method     ChildLangQuery groupByCreatedAt() Group by the lang_created column
 * @method     ChildLangQuery groupByUpdatedAt() Group by the lang_updated column
 * @method     ChildLangQuery groupByDeletedAt() Group by the lang_deleted column
 *
 * @method     ChildLangQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLangQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLangQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLangQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildLangQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildLangQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildLang|null findOne(ConnectionInterface $con = null) Return the first ChildLang matching the query
 * @method     ChildLang findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLang matching the query, or a new ChildLang object populated from the query conditions when no match is found
 *
 * @method     ChildLang|null findOneById(int $lang_id) Return the first ChildLang filtered by the lang_id column
 * @method     ChildLang|null findOneByIso639-1(string $lang_iso_639-1) Return the first ChildLang filtered by the lang_iso_639-1 column
 * @method     ChildLang|null findOneByIso639-2(string $lang_iso_639-2) Return the first ChildLang filtered by the lang_iso_639-2 column
 * @method     ChildLang|null findOneByIso639-3(string $lang_iso_639-3) Return the first ChildLang filtered by the lang_iso_639-3 column
 * @method     ChildLang|null findOneByName(string $lang_name) Return the first ChildLang filtered by the lang_name column
 * @method     ChildLang|null findOneByNameOriginal(string $lang_name_original) Return the first ChildLang filtered by the lang_name_original column
 * @method     ChildLang|null findOneByCreatedAt(string $lang_created) Return the first ChildLang filtered by the lang_created column
 * @method     ChildLang|null findOneByUpdatedAt(string $lang_updated) Return the first ChildLang filtered by the lang_updated column
 * @method     ChildLang|null findOneByDeletedAt(string $lang_deleted) Return the first ChildLang filtered by the lang_deleted column *

 * @method     ChildLang requirePk($key, ConnectionInterface $con = null) Return the ChildLang by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLang requireOne(ConnectionInterface $con = null) Return the first ChildLang matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLang requireOneById(int $lang_id) Return the first ChildLang filtered by the lang_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLang requireOneByIso639-1(string $lang_iso_639-1) Return the first ChildLang filtered by the lang_iso_639-1 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLang requireOneByIso639-2(string $lang_iso_639-2) Return the first ChildLang filtered by the lang_iso_639-2 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLang requireOneByIso639-3(string $lang_iso_639-3) Return the first ChildLang filtered by the lang_iso_639-3 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLang requireOneByName(string $lang_name) Return the first ChildLang filtered by the lang_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLang requireOneByNameOriginal(string $lang_name_original) Return the first ChildLang filtered by the lang_name_original column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLang requireOneByCreatedAt(string $lang_created) Return the first ChildLang filtered by the lang_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLang requireOneByUpdatedAt(string $lang_updated) Return the first ChildLang filtered by the lang_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLang requireOneByDeletedAt(string $lang_deleted) Return the first ChildLang filtered by the lang_deleted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLang[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildLang objects based on current ModelCriteria
 * @psalm-method ObjectCollection&\Traversable<ChildLang> find(ConnectionInterface $con = null) Return ChildLang objects based on current ModelCriteria
 * @method     ChildLang[]|ObjectCollection findById(int $lang_id) Return ChildLang objects filtered by the lang_id column
 * @psalm-method ObjectCollection&\Traversable<ChildLang> findById(int $lang_id) Return ChildLang objects filtered by the lang_id column
 * @method     ChildLang[]|ObjectCollection findByIso639-1(string $lang_iso_639-1) Return ChildLang objects filtered by the lang_iso_639-1 column
 * @psalm-method ObjectCollection&\Traversable<ChildLang> findByIso639-1(string $lang_iso_639-1) Return ChildLang objects filtered by the lang_iso_639-1 column
 * @method     ChildLang[]|ObjectCollection findByIso639-2(string $lang_iso_639-2) Return ChildLang objects filtered by the lang_iso_639-2 column
 * @psalm-method ObjectCollection&\Traversable<ChildLang> findByIso639-2(string $lang_iso_639-2) Return ChildLang objects filtered by the lang_iso_639-2 column
 * @method     ChildLang[]|ObjectCollection findByIso639-3(string $lang_iso_639-3) Return ChildLang objects filtered by the lang_iso_639-3 column
 * @psalm-method ObjectCollection&\Traversable<ChildLang> findByIso639-3(string $lang_iso_639-3) Return ChildLang objects filtered by the lang_iso_639-3 column
 * @method     ChildLang[]|ObjectCollection findByName(string $lang_name) Return ChildLang objects filtered by the lang_name column
 * @psalm-method ObjectCollection&\Traversable<ChildLang> findByName(string $lang_name) Return ChildLang objects filtered by the lang_name column
 * @method     ChildLang[]|ObjectCollection findByNameOriginal(string $lang_name_original) Return ChildLang objects filtered by the lang_name_original column
 * @psalm-method ObjectCollection&\Traversable<ChildLang> findByNameOriginal(string $lang_name_original) Return ChildLang objects filtered by the lang_name_original column
 * @method     ChildLang[]|ObjectCollection findByCreatedAt(string $lang_created) Return ChildLang objects filtered by the lang_created column
 * @psalm-method ObjectCollection&\Traversable<ChildLang> findByCreatedAt(string $lang_created) Return ChildLang objects filtered by the lang_created column
 * @method     ChildLang[]|ObjectCollection findByUpdatedAt(string $lang_updated) Return ChildLang objects filtered by the lang_updated column
 * @psalm-method ObjectCollection&\Traversable<ChildLang> findByUpdatedAt(string $lang_updated) Return ChildLang objects filtered by the lang_updated column
 * @method     ChildLang[]|ObjectCollection findByDeletedAt(string $lang_deleted) Return ChildLang objects filtered by the lang_deleted column
 * @psalm-method ObjectCollection&\Traversable<ChildLang> findByDeletedAt(string $lang_deleted) Return ChildLang objects filtered by the lang_deleted column
 * @method     ChildLang[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildLang> paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class LangQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\LangQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Lang', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLangQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLangQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildLangQuery) {
            return $criteria;
        }
        $query = new ChildLangQuery();
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
     * @return ChildLang|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LangTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = LangTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildLang A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT lang_id, lang_iso_639-1, lang_iso_639-2, lang_iso_639-3, lang_name, lang_name_original, lang_created, lang_updated, lang_deleted FROM langs WHERE lang_id = :p0';
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
            /** @var ChildLang $obj */
            $obj = new ChildLang();
            $obj->hydrate($row);
            LangTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildLang|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildLangQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(LangTableMap::COL_LANG_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildLangQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(LangTableMap::COL_LANG_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the lang_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE lang_id = 1234
     * $query->filterById(array(12, 34)); // WHERE lang_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE lang_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLangQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(LangTableMap::COL_LANG_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(LangTableMap::COL_LANG_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LangTableMap::COL_LANG_ID, $id, $comparison);
    }

    /**
     * Filter the query on the lang_iso_639-1 column
     *
     * Example usage:
     * <code>
     * $query->filterByIso639-1('fooValue');   // WHERE lang_iso_639-1 = 'fooValue'
     * $query->filterByIso639-1('%fooValue%', Criteria::LIKE); // WHERE lang_iso_639-1 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $iso639-1 The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLangQuery The current query, for fluid interface
     */
    public function filterByIso639-1($iso639-1 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($iso639-1)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LangTableMap::COL_ISO639_1, $iso639-1, $comparison);
    }

    /**
     * Filter the query on the lang_iso_639-2 column
     *
     * Example usage:
     * <code>
     * $query->filterByIso639-2('fooValue');   // WHERE lang_iso_639-2 = 'fooValue'
     * $query->filterByIso639-2('%fooValue%', Criteria::LIKE); // WHERE lang_iso_639-2 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $iso639-2 The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLangQuery The current query, for fluid interface
     */
    public function filterByIso639-2($iso639-2 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($iso639-2)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LangTableMap::COL_ISO639_2, $iso639-2, $comparison);
    }

    /**
     * Filter the query on the lang_iso_639-3 column
     *
     * Example usage:
     * <code>
     * $query->filterByIso639-3('fooValue');   // WHERE lang_iso_639-3 = 'fooValue'
     * $query->filterByIso639-3('%fooValue%', Criteria::LIKE); // WHERE lang_iso_639-3 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $iso639-3 The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLangQuery The current query, for fluid interface
     */
    public function filterByIso639-3($iso639-3 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($iso639-3)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LangTableMap::COL_ISO639_3, $iso639-3, $comparison);
    }

    /**
     * Filter the query on the lang_name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE lang_name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE lang_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLangQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LangTableMap::COL_LANG_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the lang_name_original column
     *
     * Example usage:
     * <code>
     * $query->filterByNameOriginal('fooValue');   // WHERE lang_name_original = 'fooValue'
     * $query->filterByNameOriginal('%fooValue%', Criteria::LIKE); // WHERE lang_name_original LIKE '%fooValue%'
     * </code>
     *
     * @param     string $nameOriginal The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLangQuery The current query, for fluid interface
     */
    public function filterByNameOriginal($nameOriginal = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nameOriginal)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LangTableMap::COL_LANG_NAME_ORIGINAL, $nameOriginal, $comparison);
    }

    /**
     * Filter the query on the lang_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE lang_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE lang_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE lang_created > '2011-03-13'
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
     * @return $this|ChildLangQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(LangTableMap::COL_LANG_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(LangTableMap::COL_LANG_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LangTableMap::COL_LANG_CREATED, $createdAt, $comparison);
    }

    /**
     * Filter the query on the lang_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE lang_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE lang_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE lang_updated > '2011-03-13'
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
     * @return $this|ChildLangQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(LangTableMap::COL_LANG_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(LangTableMap::COL_LANG_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LangTableMap::COL_LANG_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the lang_deleted column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletedAt('2011-03-14'); // WHERE lang_deleted = '2011-03-14'
     * $query->filterByDeletedAt('now'); // WHERE lang_deleted = '2011-03-14'
     * $query->filterByDeletedAt(array('max' => 'yesterday')); // WHERE lang_deleted > '2011-03-13'
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
     * @return $this|ChildLangQuery The current query, for fluid interface
     */
    public function filterByDeletedAt($deletedAt = null, $comparison = null)
    {
        if (is_array($deletedAt)) {
            $useMinMax = false;
            if (isset($deletedAt['min'])) {
                $this->addUsingAlias(LangTableMap::COL_LANG_DELETED, $deletedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletedAt['max'])) {
                $this->addUsingAlias(LangTableMap::COL_LANG_DELETED, $deletedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LangTableMap::COL_LANG_DELETED, $deletedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildLang $lang Object to remove from the list of results
     *
     * @return $this|ChildLangQuery The current query, for fluid interface
     */
    public function prune($lang = null)
    {
        if ($lang) {
            $this->addUsingAlias(LangTableMap::COL_LANG_ID, $lang->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the langs table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LangTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LangTableMap::clearInstancePool();
            LangTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(LangTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(LangTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            LangTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            LangTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildLangQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(LangTableMap::COL_LANG_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildLangQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(LangTableMap::COL_LANG_UPDATED);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildLangQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(LangTableMap::COL_LANG_UPDATED);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildLangQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(LangTableMap::COL_LANG_CREATED);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildLangQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(LangTableMap::COL_LANG_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildLangQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(LangTableMap::COL_LANG_CREATED);
    }

} // LangQuery
