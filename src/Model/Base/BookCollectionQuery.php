<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\BookCollection as ChildBookCollection;
use Model\BookCollectionQuery as ChildBookCollectionQuery;
use Model\Map\BookCollectionTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'collections' table.
 *
 *
 *
 * @method     ChildBookCollectionQuery orderById($order = Criteria::ASC) Order by the collection_id column
 * @method     ChildBookCollectionQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildBookCollectionQuery orderByPublisherId($order = Criteria::ASC) Order by the publisher_id column
 * @method     ChildBookCollectionQuery orderByPricegridId($order = Criteria::ASC) Order by the pricegrid_id column
 * @method     ChildBookCollectionQuery orderByName($order = Criteria::ASC) Order by the collection_name column
 * @method     ChildBookCollectionQuery orderByUrl($order = Criteria::ASC) Order by the collection_url column
 * @method     ChildBookCollectionQuery orderByPublisher($order = Criteria::ASC) Order by the collection_publisher column
 * @method     ChildBookCollectionQuery orderByDesc($order = Criteria::ASC) Order by the collection_desc column
 * @method     ChildBookCollectionQuery orderByIgnorenum($order = Criteria::ASC) Order by the collection_ignorenum column
 * @method     ChildBookCollectionQuery orderByOrderby($order = Criteria::ASC) Order by the collection_orderby column
 * @method     ChildBookCollectionQuery orderByIncorrectWeights($order = Criteria::ASC) Order by the collection_incorrect_weights column
 * @method     ChildBookCollectionQuery orderByNoosfereId($order = Criteria::ASC) Order by the collection_noosfere_id column
 * @method     ChildBookCollectionQuery orderByInsert($order = Criteria::ASC) Order by the collection_insert column
 * @method     ChildBookCollectionQuery orderByUpdate($order = Criteria::ASC) Order by the collection_update column
 * @method     ChildBookCollectionQuery orderByHits($order = Criteria::ASC) Order by the collection_hits column
 * @method     ChildBookCollectionQuery orderByDuplicate($order = Criteria::ASC) Order by the collection_duplicate column
 * @method     ChildBookCollectionQuery orderByCreatedAt($order = Criteria::ASC) Order by the collection_created column
 * @method     ChildBookCollectionQuery orderByUpdatedAt($order = Criteria::ASC) Order by the collection_updated column
 * @method     ChildBookCollectionQuery orderByDeletedAt($order = Criteria::ASC) Order by the collection_deleted column
 *
 * @method     ChildBookCollectionQuery groupById() Group by the collection_id column
 * @method     ChildBookCollectionQuery groupBySiteId() Group by the site_id column
 * @method     ChildBookCollectionQuery groupByPublisherId() Group by the publisher_id column
 * @method     ChildBookCollectionQuery groupByPricegridId() Group by the pricegrid_id column
 * @method     ChildBookCollectionQuery groupByName() Group by the collection_name column
 * @method     ChildBookCollectionQuery groupByUrl() Group by the collection_url column
 * @method     ChildBookCollectionQuery groupByPublisher() Group by the collection_publisher column
 * @method     ChildBookCollectionQuery groupByDesc() Group by the collection_desc column
 * @method     ChildBookCollectionQuery groupByIgnorenum() Group by the collection_ignorenum column
 * @method     ChildBookCollectionQuery groupByOrderby() Group by the collection_orderby column
 * @method     ChildBookCollectionQuery groupByIncorrectWeights() Group by the collection_incorrect_weights column
 * @method     ChildBookCollectionQuery groupByNoosfereId() Group by the collection_noosfere_id column
 * @method     ChildBookCollectionQuery groupByInsert() Group by the collection_insert column
 * @method     ChildBookCollectionQuery groupByUpdate() Group by the collection_update column
 * @method     ChildBookCollectionQuery groupByHits() Group by the collection_hits column
 * @method     ChildBookCollectionQuery groupByDuplicate() Group by the collection_duplicate column
 * @method     ChildBookCollectionQuery groupByCreatedAt() Group by the collection_created column
 * @method     ChildBookCollectionQuery groupByUpdatedAt() Group by the collection_updated column
 * @method     ChildBookCollectionQuery groupByDeletedAt() Group by the collection_deleted column
 *
 * @method     ChildBookCollectionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildBookCollectionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildBookCollectionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildBookCollectionQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildBookCollectionQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildBookCollectionQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildBookCollection|null findOne(ConnectionInterface $con = null) Return the first ChildBookCollection matching the query
 * @method     ChildBookCollection findOneOrCreate(ConnectionInterface $con = null) Return the first ChildBookCollection matching the query, or a new ChildBookCollection object populated from the query conditions when no match is found
 *
 * @method     ChildBookCollection|null findOneById(int $collection_id) Return the first ChildBookCollection filtered by the collection_id column
 * @method     ChildBookCollection|null findOneBySiteId(int $site_id) Return the first ChildBookCollection filtered by the site_id column
 * @method     ChildBookCollection|null findOneByPublisherId(int $publisher_id) Return the first ChildBookCollection filtered by the publisher_id column
 * @method     ChildBookCollection|null findOneByPricegridId(int $pricegrid_id) Return the first ChildBookCollection filtered by the pricegrid_id column
 * @method     ChildBookCollection|null findOneByName(string $collection_name) Return the first ChildBookCollection filtered by the collection_name column
 * @method     ChildBookCollection|null findOneByUrl(string $collection_url) Return the first ChildBookCollection filtered by the collection_url column
 * @method     ChildBookCollection|null findOneByPublisher(string $collection_publisher) Return the first ChildBookCollection filtered by the collection_publisher column
 * @method     ChildBookCollection|null findOneByDesc(string $collection_desc) Return the first ChildBookCollection filtered by the collection_desc column
 * @method     ChildBookCollection|null findOneByIgnorenum(boolean $collection_ignorenum) Return the first ChildBookCollection filtered by the collection_ignorenum column
 * @method     ChildBookCollection|null findOneByOrderby(string $collection_orderby) Return the first ChildBookCollection filtered by the collection_orderby column
 * @method     ChildBookCollection|null findOneByIncorrectWeights(boolean $collection_incorrect_weights) Return the first ChildBookCollection filtered by the collection_incorrect_weights column
 * @method     ChildBookCollection|null findOneByNoosfereId(int $collection_noosfere_id) Return the first ChildBookCollection filtered by the collection_noosfere_id column
 * @method     ChildBookCollection|null findOneByInsert(string $collection_insert) Return the first ChildBookCollection filtered by the collection_insert column
 * @method     ChildBookCollection|null findOneByUpdate(string $collection_update) Return the first ChildBookCollection filtered by the collection_update column
 * @method     ChildBookCollection|null findOneByHits(int $collection_hits) Return the first ChildBookCollection filtered by the collection_hits column
 * @method     ChildBookCollection|null findOneByDuplicate(boolean $collection_duplicate) Return the first ChildBookCollection filtered by the collection_duplicate column
 * @method     ChildBookCollection|null findOneByCreatedAt(string $collection_created) Return the first ChildBookCollection filtered by the collection_created column
 * @method     ChildBookCollection|null findOneByUpdatedAt(string $collection_updated) Return the first ChildBookCollection filtered by the collection_updated column
 * @method     ChildBookCollection|null findOneByDeletedAt(string $collection_deleted) Return the first ChildBookCollection filtered by the collection_deleted column *

 * @method     ChildBookCollection requirePk($key, ConnectionInterface $con = null) Return the ChildBookCollection by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookCollection requireOne(ConnectionInterface $con = null) Return the first ChildBookCollection matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildBookCollection requireOneById(int $collection_id) Return the first ChildBookCollection filtered by the collection_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookCollection requireOneBySiteId(int $site_id) Return the first ChildBookCollection filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookCollection requireOneByPublisherId(int $publisher_id) Return the first ChildBookCollection filtered by the publisher_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookCollection requireOneByPricegridId(int $pricegrid_id) Return the first ChildBookCollection filtered by the pricegrid_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookCollection requireOneByName(string $collection_name) Return the first ChildBookCollection filtered by the collection_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookCollection requireOneByUrl(string $collection_url) Return the first ChildBookCollection filtered by the collection_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookCollection requireOneByPublisher(string $collection_publisher) Return the first ChildBookCollection filtered by the collection_publisher column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookCollection requireOneByDesc(string $collection_desc) Return the first ChildBookCollection filtered by the collection_desc column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookCollection requireOneByIgnorenum(boolean $collection_ignorenum) Return the first ChildBookCollection filtered by the collection_ignorenum column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookCollection requireOneByOrderby(string $collection_orderby) Return the first ChildBookCollection filtered by the collection_orderby column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookCollection requireOneByIncorrectWeights(boolean $collection_incorrect_weights) Return the first ChildBookCollection filtered by the collection_incorrect_weights column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookCollection requireOneByNoosfereId(int $collection_noosfere_id) Return the first ChildBookCollection filtered by the collection_noosfere_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookCollection requireOneByInsert(string $collection_insert) Return the first ChildBookCollection filtered by the collection_insert column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookCollection requireOneByUpdate(string $collection_update) Return the first ChildBookCollection filtered by the collection_update column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookCollection requireOneByHits(int $collection_hits) Return the first ChildBookCollection filtered by the collection_hits column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookCollection requireOneByDuplicate(boolean $collection_duplicate) Return the first ChildBookCollection filtered by the collection_duplicate column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookCollection requireOneByCreatedAt(string $collection_created) Return the first ChildBookCollection filtered by the collection_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookCollection requireOneByUpdatedAt(string $collection_updated) Return the first ChildBookCollection filtered by the collection_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookCollection requireOneByDeletedAt(string $collection_deleted) Return the first ChildBookCollection filtered by the collection_deleted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildBookCollection[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildBookCollection objects based on current ModelCriteria
 * @psalm-method ObjectCollection&\Traversable<ChildBookCollection> find(ConnectionInterface $con = null) Return ChildBookCollection objects based on current ModelCriteria
 * @method     ChildBookCollection[]|ObjectCollection findById(int $collection_id) Return ChildBookCollection objects filtered by the collection_id column
 * @psalm-method ObjectCollection&\Traversable<ChildBookCollection> findById(int $collection_id) Return ChildBookCollection objects filtered by the collection_id column
 * @method     ChildBookCollection[]|ObjectCollection findBySiteId(int $site_id) Return ChildBookCollection objects filtered by the site_id column
 * @psalm-method ObjectCollection&\Traversable<ChildBookCollection> findBySiteId(int $site_id) Return ChildBookCollection objects filtered by the site_id column
 * @method     ChildBookCollection[]|ObjectCollection findByPublisherId(int $publisher_id) Return ChildBookCollection objects filtered by the publisher_id column
 * @psalm-method ObjectCollection&\Traversable<ChildBookCollection> findByPublisherId(int $publisher_id) Return ChildBookCollection objects filtered by the publisher_id column
 * @method     ChildBookCollection[]|ObjectCollection findByPricegridId(int $pricegrid_id) Return ChildBookCollection objects filtered by the pricegrid_id column
 * @psalm-method ObjectCollection&\Traversable<ChildBookCollection> findByPricegridId(int $pricegrid_id) Return ChildBookCollection objects filtered by the pricegrid_id column
 * @method     ChildBookCollection[]|ObjectCollection findByName(string $collection_name) Return ChildBookCollection objects filtered by the collection_name column
 * @psalm-method ObjectCollection&\Traversable<ChildBookCollection> findByName(string $collection_name) Return ChildBookCollection objects filtered by the collection_name column
 * @method     ChildBookCollection[]|ObjectCollection findByUrl(string $collection_url) Return ChildBookCollection objects filtered by the collection_url column
 * @psalm-method ObjectCollection&\Traversable<ChildBookCollection> findByUrl(string $collection_url) Return ChildBookCollection objects filtered by the collection_url column
 * @method     ChildBookCollection[]|ObjectCollection findByPublisher(string $collection_publisher) Return ChildBookCollection objects filtered by the collection_publisher column
 * @psalm-method ObjectCollection&\Traversable<ChildBookCollection> findByPublisher(string $collection_publisher) Return ChildBookCollection objects filtered by the collection_publisher column
 * @method     ChildBookCollection[]|ObjectCollection findByDesc(string $collection_desc) Return ChildBookCollection objects filtered by the collection_desc column
 * @psalm-method ObjectCollection&\Traversable<ChildBookCollection> findByDesc(string $collection_desc) Return ChildBookCollection objects filtered by the collection_desc column
 * @method     ChildBookCollection[]|ObjectCollection findByIgnorenum(boolean $collection_ignorenum) Return ChildBookCollection objects filtered by the collection_ignorenum column
 * @psalm-method ObjectCollection&\Traversable<ChildBookCollection> findByIgnorenum(boolean $collection_ignorenum) Return ChildBookCollection objects filtered by the collection_ignorenum column
 * @method     ChildBookCollection[]|ObjectCollection findByOrderby(string $collection_orderby) Return ChildBookCollection objects filtered by the collection_orderby column
 * @psalm-method ObjectCollection&\Traversable<ChildBookCollection> findByOrderby(string $collection_orderby) Return ChildBookCollection objects filtered by the collection_orderby column
 * @method     ChildBookCollection[]|ObjectCollection findByIncorrectWeights(boolean $collection_incorrect_weights) Return ChildBookCollection objects filtered by the collection_incorrect_weights column
 * @psalm-method ObjectCollection&\Traversable<ChildBookCollection> findByIncorrectWeights(boolean $collection_incorrect_weights) Return ChildBookCollection objects filtered by the collection_incorrect_weights column
 * @method     ChildBookCollection[]|ObjectCollection findByNoosfereId(int $collection_noosfere_id) Return ChildBookCollection objects filtered by the collection_noosfere_id column
 * @psalm-method ObjectCollection&\Traversable<ChildBookCollection> findByNoosfereId(int $collection_noosfere_id) Return ChildBookCollection objects filtered by the collection_noosfere_id column
 * @method     ChildBookCollection[]|ObjectCollection findByInsert(string $collection_insert) Return ChildBookCollection objects filtered by the collection_insert column
 * @psalm-method ObjectCollection&\Traversable<ChildBookCollection> findByInsert(string $collection_insert) Return ChildBookCollection objects filtered by the collection_insert column
 * @method     ChildBookCollection[]|ObjectCollection findByUpdate(string $collection_update) Return ChildBookCollection objects filtered by the collection_update column
 * @psalm-method ObjectCollection&\Traversable<ChildBookCollection> findByUpdate(string $collection_update) Return ChildBookCollection objects filtered by the collection_update column
 * @method     ChildBookCollection[]|ObjectCollection findByHits(int $collection_hits) Return ChildBookCollection objects filtered by the collection_hits column
 * @psalm-method ObjectCollection&\Traversable<ChildBookCollection> findByHits(int $collection_hits) Return ChildBookCollection objects filtered by the collection_hits column
 * @method     ChildBookCollection[]|ObjectCollection findByDuplicate(boolean $collection_duplicate) Return ChildBookCollection objects filtered by the collection_duplicate column
 * @psalm-method ObjectCollection&\Traversable<ChildBookCollection> findByDuplicate(boolean $collection_duplicate) Return ChildBookCollection objects filtered by the collection_duplicate column
 * @method     ChildBookCollection[]|ObjectCollection findByCreatedAt(string $collection_created) Return ChildBookCollection objects filtered by the collection_created column
 * @psalm-method ObjectCollection&\Traversable<ChildBookCollection> findByCreatedAt(string $collection_created) Return ChildBookCollection objects filtered by the collection_created column
 * @method     ChildBookCollection[]|ObjectCollection findByUpdatedAt(string $collection_updated) Return ChildBookCollection objects filtered by the collection_updated column
 * @psalm-method ObjectCollection&\Traversable<ChildBookCollection> findByUpdatedAt(string $collection_updated) Return ChildBookCollection objects filtered by the collection_updated column
 * @method     ChildBookCollection[]|ObjectCollection findByDeletedAt(string $collection_deleted) Return ChildBookCollection objects filtered by the collection_deleted column
 * @psalm-method ObjectCollection&\Traversable<ChildBookCollection> findByDeletedAt(string $collection_deleted) Return ChildBookCollection objects filtered by the collection_deleted column
 * @method     ChildBookCollection[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildBookCollection> paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class BookCollectionQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\BookCollectionQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\BookCollection', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildBookCollectionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildBookCollectionQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildBookCollectionQuery) {
            return $criteria;
        }
        $query = new ChildBookCollectionQuery();
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
     * @return ChildBookCollection|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(BookCollectionTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = BookCollectionTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildBookCollection A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT collection_id, site_id, publisher_id, pricegrid_id, collection_name, collection_url, collection_publisher, collection_desc, collection_ignorenum, collection_orderby, collection_incorrect_weights, collection_noosfere_id, collection_insert, collection_update, collection_hits, collection_duplicate, collection_created, collection_updated, collection_deleted FROM collections WHERE collection_id = :p0';
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
            /** @var ChildBookCollection $obj */
            $obj = new ChildBookCollection();
            $obj->hydrate($row);
            BookCollectionTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildBookCollection|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildBookCollectionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildBookCollectionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the collection_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE collection_id = 1234
     * $query->filterById(array(12, 34)); // WHERE collection_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE collection_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookCollectionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_ID, $id, $comparison);
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
     * @return $this|ChildBookCollectionQuery The current query, for fluid interface
     */
    public function filterBySiteId($siteId = null, $comparison = null)
    {
        if (is_array($siteId)) {
            $useMinMax = false;
            if (isset($siteId['min'])) {
                $this->addUsingAlias(BookCollectionTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(BookCollectionTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookCollectionTableMap::COL_SITE_ID, $siteId, $comparison);
    }

    /**
     * Filter the query on the publisher_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPublisherId(1234); // WHERE publisher_id = 1234
     * $query->filterByPublisherId(array(12, 34)); // WHERE publisher_id IN (12, 34)
     * $query->filterByPublisherId(array('min' => 12)); // WHERE publisher_id > 12
     * </code>
     *
     * @param     mixed $publisherId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookCollectionQuery The current query, for fluid interface
     */
    public function filterByPublisherId($publisherId = null, $comparison = null)
    {
        if (is_array($publisherId)) {
            $useMinMax = false;
            if (isset($publisherId['min'])) {
                $this->addUsingAlias(BookCollectionTableMap::COL_PUBLISHER_ID, $publisherId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publisherId['max'])) {
                $this->addUsingAlias(BookCollectionTableMap::COL_PUBLISHER_ID, $publisherId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookCollectionTableMap::COL_PUBLISHER_ID, $publisherId, $comparison);
    }

    /**
     * Filter the query on the pricegrid_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPricegridId(1234); // WHERE pricegrid_id = 1234
     * $query->filterByPricegridId(array(12, 34)); // WHERE pricegrid_id IN (12, 34)
     * $query->filterByPricegridId(array('min' => 12)); // WHERE pricegrid_id > 12
     * </code>
     *
     * @param     mixed $pricegridId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookCollectionQuery The current query, for fluid interface
     */
    public function filterByPricegridId($pricegridId = null, $comparison = null)
    {
        if (is_array($pricegridId)) {
            $useMinMax = false;
            if (isset($pricegridId['min'])) {
                $this->addUsingAlias(BookCollectionTableMap::COL_PRICEGRID_ID, $pricegridId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pricegridId['max'])) {
                $this->addUsingAlias(BookCollectionTableMap::COL_PRICEGRID_ID, $pricegridId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookCollectionTableMap::COL_PRICEGRID_ID, $pricegridId, $comparison);
    }

    /**
     * Filter the query on the collection_name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE collection_name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE collection_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookCollectionQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the collection_url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE collection_url = 'fooValue'
     * $query->filterByUrl('%fooValue%', Criteria::LIKE); // WHERE collection_url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $url The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookCollectionQuery The current query, for fluid interface
     */
    public function filterByUrl($url = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($url)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_URL, $url, $comparison);
    }

    /**
     * Filter the query on the collection_publisher column
     *
     * Example usage:
     * <code>
     * $query->filterByPublisher('fooValue');   // WHERE collection_publisher = 'fooValue'
     * $query->filterByPublisher('%fooValue%', Criteria::LIKE); // WHERE collection_publisher LIKE '%fooValue%'
     * </code>
     *
     * @param     string $publisher The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookCollectionQuery The current query, for fluid interface
     */
    public function filterByPublisher($publisher = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($publisher)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_PUBLISHER, $publisher, $comparison);
    }

    /**
     * Filter the query on the collection_desc column
     *
     * Example usage:
     * <code>
     * $query->filterByDesc('fooValue');   // WHERE collection_desc = 'fooValue'
     * $query->filterByDesc('%fooValue%', Criteria::LIKE); // WHERE collection_desc LIKE '%fooValue%'
     * </code>
     *
     * @param     string $desc The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookCollectionQuery The current query, for fluid interface
     */
    public function filterByDesc($desc = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($desc)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_DESC, $desc, $comparison);
    }

    /**
     * Filter the query on the collection_ignorenum column
     *
     * Example usage:
     * <code>
     * $query->filterByIgnorenum(true); // WHERE collection_ignorenum = true
     * $query->filterByIgnorenum('yes'); // WHERE collection_ignorenum = true
     * </code>
     *
     * @param     boolean|string $ignorenum The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookCollectionQuery The current query, for fluid interface
     */
    public function filterByIgnorenum($ignorenum = null, $comparison = null)
    {
        if (is_string($ignorenum)) {
            $ignorenum = in_array(strtolower($ignorenum), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_IGNORENUM, $ignorenum, $comparison);
    }

    /**
     * Filter the query on the collection_orderby column
     *
     * Example usage:
     * <code>
     * $query->filterByOrderby('fooValue');   // WHERE collection_orderby = 'fooValue'
     * $query->filterByOrderby('%fooValue%', Criteria::LIKE); // WHERE collection_orderby LIKE '%fooValue%'
     * </code>
     *
     * @param     string $orderby The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookCollectionQuery The current query, for fluid interface
     */
    public function filterByOrderby($orderby = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($orderby)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_ORDERBY, $orderby, $comparison);
    }

    /**
     * Filter the query on the collection_incorrect_weights column
     *
     * Example usage:
     * <code>
     * $query->filterByIncorrectWeights(true); // WHERE collection_incorrect_weights = true
     * $query->filterByIncorrectWeights('yes'); // WHERE collection_incorrect_weights = true
     * </code>
     *
     * @param     boolean|string $incorrectWeights The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookCollectionQuery The current query, for fluid interface
     */
    public function filterByIncorrectWeights($incorrectWeights = null, $comparison = null)
    {
        if (is_string($incorrectWeights)) {
            $incorrectWeights = in_array(strtolower($incorrectWeights), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_INCORRECT_WEIGHTS, $incorrectWeights, $comparison);
    }

    /**
     * Filter the query on the collection_noosfere_id column
     *
     * Example usage:
     * <code>
     * $query->filterByNoosfereId(1234); // WHERE collection_noosfere_id = 1234
     * $query->filterByNoosfereId(array(12, 34)); // WHERE collection_noosfere_id IN (12, 34)
     * $query->filterByNoosfereId(array('min' => 12)); // WHERE collection_noosfere_id > 12
     * </code>
     *
     * @param     mixed $noosfereId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookCollectionQuery The current query, for fluid interface
     */
    public function filterByNoosfereId($noosfereId = null, $comparison = null)
    {
        if (is_array($noosfereId)) {
            $useMinMax = false;
            if (isset($noosfereId['min'])) {
                $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_NOOSFERE_ID, $noosfereId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($noosfereId['max'])) {
                $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_NOOSFERE_ID, $noosfereId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_NOOSFERE_ID, $noosfereId, $comparison);
    }

    /**
     * Filter the query on the collection_insert column
     *
     * Example usage:
     * <code>
     * $query->filterByInsert('2011-03-14'); // WHERE collection_insert = '2011-03-14'
     * $query->filterByInsert('now'); // WHERE collection_insert = '2011-03-14'
     * $query->filterByInsert(array('max' => 'yesterday')); // WHERE collection_insert > '2011-03-13'
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
     * @return $this|ChildBookCollectionQuery The current query, for fluid interface
     */
    public function filterByInsert($insert = null, $comparison = null)
    {
        if (is_array($insert)) {
            $useMinMax = false;
            if (isset($insert['min'])) {
                $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_INSERT, $insert['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($insert['max'])) {
                $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_INSERT, $insert['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_INSERT, $insert, $comparison);
    }

    /**
     * Filter the query on the collection_update column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdate('2011-03-14'); // WHERE collection_update = '2011-03-14'
     * $query->filterByUpdate('now'); // WHERE collection_update = '2011-03-14'
     * $query->filterByUpdate(array('max' => 'yesterday')); // WHERE collection_update > '2011-03-13'
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
     * @return $this|ChildBookCollectionQuery The current query, for fluid interface
     */
    public function filterByUpdate($update = null, $comparison = null)
    {
        if (is_array($update)) {
            $useMinMax = false;
            if (isset($update['min'])) {
                $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_UPDATE, $update['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($update['max'])) {
                $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_UPDATE, $update['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_UPDATE, $update, $comparison);
    }

    /**
     * Filter the query on the collection_hits column
     *
     * Example usage:
     * <code>
     * $query->filterByHits(1234); // WHERE collection_hits = 1234
     * $query->filterByHits(array(12, 34)); // WHERE collection_hits IN (12, 34)
     * $query->filterByHits(array('min' => 12)); // WHERE collection_hits > 12
     * </code>
     *
     * @param     mixed $hits The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookCollectionQuery The current query, for fluid interface
     */
    public function filterByHits($hits = null, $comparison = null)
    {
        if (is_array($hits)) {
            $useMinMax = false;
            if (isset($hits['min'])) {
                $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_HITS, $hits['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($hits['max'])) {
                $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_HITS, $hits['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_HITS, $hits, $comparison);
    }

    /**
     * Filter the query on the collection_duplicate column
     *
     * Example usage:
     * <code>
     * $query->filterByDuplicate(true); // WHERE collection_duplicate = true
     * $query->filterByDuplicate('yes'); // WHERE collection_duplicate = true
     * </code>
     *
     * @param     boolean|string $duplicate The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookCollectionQuery The current query, for fluid interface
     */
    public function filterByDuplicate($duplicate = null, $comparison = null)
    {
        if (is_string($duplicate)) {
            $duplicate = in_array(strtolower($duplicate), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_DUPLICATE, $duplicate, $comparison);
    }

    /**
     * Filter the query on the collection_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE collection_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE collection_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE collection_created > '2011-03-13'
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
     * @return $this|ChildBookCollectionQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_CREATED, $createdAt, $comparison);
    }

    /**
     * Filter the query on the collection_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE collection_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE collection_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE collection_updated > '2011-03-13'
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
     * @return $this|ChildBookCollectionQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the collection_deleted column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletedAt('2011-03-14'); // WHERE collection_deleted = '2011-03-14'
     * $query->filterByDeletedAt('now'); // WHERE collection_deleted = '2011-03-14'
     * $query->filterByDeletedAt(array('max' => 'yesterday')); // WHERE collection_deleted > '2011-03-13'
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
     * @return $this|ChildBookCollectionQuery The current query, for fluid interface
     */
    public function filterByDeletedAt($deletedAt = null, $comparison = null)
    {
        if (is_array($deletedAt)) {
            $useMinMax = false;
            if (isset($deletedAt['min'])) {
                $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_DELETED, $deletedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletedAt['max'])) {
                $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_DELETED, $deletedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_DELETED, $deletedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildBookCollection $bookCollection Object to remove from the list of results
     *
     * @return $this|ChildBookCollectionQuery The current query, for fluid interface
     */
    public function prune($bookCollection = null)
    {
        if ($bookCollection) {
            $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_ID, $bookCollection->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the collections table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(BookCollectionTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            BookCollectionTableMap::clearInstancePool();
            BookCollectionTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(BookCollectionTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(BookCollectionTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            BookCollectionTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            BookCollectionTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildBookCollectionQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildBookCollectionQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(BookCollectionTableMap::COL_COLLECTION_UPDATED);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildBookCollectionQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(BookCollectionTableMap::COL_COLLECTION_UPDATED);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildBookCollectionQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(BookCollectionTableMap::COL_COLLECTION_CREATED);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildBookCollectionQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildBookCollectionQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(BookCollectionTableMap::COL_COLLECTION_CREATED);
    }

} // BookCollectionQuery
