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
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
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
 *
 * @method     ChildBookCollectionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildBookCollectionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildBookCollectionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildBookCollectionQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildBookCollectionQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildBookCollectionQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildBookCollectionQuery leftJoinArticle($relationAlias = null) Adds a LEFT JOIN clause to the query using the Article relation
 * @method     ChildBookCollectionQuery rightJoinArticle($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Article relation
 * @method     ChildBookCollectionQuery innerJoinArticle($relationAlias = null) Adds a INNER JOIN clause to the query using the Article relation
 *
 * @method     ChildBookCollectionQuery joinWithArticle($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Article relation
 *
 * @method     ChildBookCollectionQuery leftJoinWithArticle() Adds a LEFT JOIN clause and with to the query using the Article relation
 * @method     ChildBookCollectionQuery rightJoinWithArticle() Adds a RIGHT JOIN clause and with to the query using the Article relation
 * @method     ChildBookCollectionQuery innerJoinWithArticle() Adds a INNER JOIN clause and with to the query using the Article relation
 *
 * @method     \Model\ArticleQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildBookCollection|null findOne(?ConnectionInterface $con = null) Return the first ChildBookCollection matching the query
 * @method     ChildBookCollection findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildBookCollection matching the query, or a new ChildBookCollection object populated from the query conditions when no match is found
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
 * @method     ChildBookCollection|null findOneByUpdatedAt(string $collection_updated) Return the first ChildBookCollection filtered by the collection_updated column *

 * @method     ChildBookCollection requirePk($key, ?ConnectionInterface $con = null) Return the ChildBookCollection by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookCollection requireOne(?ConnectionInterface $con = null) Return the first ChildBookCollection matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
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
 *
 * @method     ChildBookCollection[]|Collection find(?ConnectionInterface $con = null) Return ChildBookCollection objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildBookCollection> find(?ConnectionInterface $con = null) Return ChildBookCollection objects based on current ModelCriteria
 * @method     ChildBookCollection[]|Collection findById(int $collection_id) Return ChildBookCollection objects filtered by the collection_id column
 * @psalm-method Collection&\Traversable<ChildBookCollection> findById(int $collection_id) Return ChildBookCollection objects filtered by the collection_id column
 * @method     ChildBookCollection[]|Collection findBySiteId(int $site_id) Return ChildBookCollection objects filtered by the site_id column
 * @psalm-method Collection&\Traversable<ChildBookCollection> findBySiteId(int $site_id) Return ChildBookCollection objects filtered by the site_id column
 * @method     ChildBookCollection[]|Collection findByPublisherId(int $publisher_id) Return ChildBookCollection objects filtered by the publisher_id column
 * @psalm-method Collection&\Traversable<ChildBookCollection> findByPublisherId(int $publisher_id) Return ChildBookCollection objects filtered by the publisher_id column
 * @method     ChildBookCollection[]|Collection findByPricegridId(int $pricegrid_id) Return ChildBookCollection objects filtered by the pricegrid_id column
 * @psalm-method Collection&\Traversable<ChildBookCollection> findByPricegridId(int $pricegrid_id) Return ChildBookCollection objects filtered by the pricegrid_id column
 * @method     ChildBookCollection[]|Collection findByName(string $collection_name) Return ChildBookCollection objects filtered by the collection_name column
 * @psalm-method Collection&\Traversable<ChildBookCollection> findByName(string $collection_name) Return ChildBookCollection objects filtered by the collection_name column
 * @method     ChildBookCollection[]|Collection findByUrl(string $collection_url) Return ChildBookCollection objects filtered by the collection_url column
 * @psalm-method Collection&\Traversable<ChildBookCollection> findByUrl(string $collection_url) Return ChildBookCollection objects filtered by the collection_url column
 * @method     ChildBookCollection[]|Collection findByPublisher(string $collection_publisher) Return ChildBookCollection objects filtered by the collection_publisher column
 * @psalm-method Collection&\Traversable<ChildBookCollection> findByPublisher(string $collection_publisher) Return ChildBookCollection objects filtered by the collection_publisher column
 * @method     ChildBookCollection[]|Collection findByDesc(string $collection_desc) Return ChildBookCollection objects filtered by the collection_desc column
 * @psalm-method Collection&\Traversable<ChildBookCollection> findByDesc(string $collection_desc) Return ChildBookCollection objects filtered by the collection_desc column
 * @method     ChildBookCollection[]|Collection findByIgnorenum(boolean $collection_ignorenum) Return ChildBookCollection objects filtered by the collection_ignorenum column
 * @psalm-method Collection&\Traversable<ChildBookCollection> findByIgnorenum(boolean $collection_ignorenum) Return ChildBookCollection objects filtered by the collection_ignorenum column
 * @method     ChildBookCollection[]|Collection findByOrderby(string $collection_orderby) Return ChildBookCollection objects filtered by the collection_orderby column
 * @psalm-method Collection&\Traversable<ChildBookCollection> findByOrderby(string $collection_orderby) Return ChildBookCollection objects filtered by the collection_orderby column
 * @method     ChildBookCollection[]|Collection findByIncorrectWeights(boolean $collection_incorrect_weights) Return ChildBookCollection objects filtered by the collection_incorrect_weights column
 * @psalm-method Collection&\Traversable<ChildBookCollection> findByIncorrectWeights(boolean $collection_incorrect_weights) Return ChildBookCollection objects filtered by the collection_incorrect_weights column
 * @method     ChildBookCollection[]|Collection findByNoosfereId(int $collection_noosfere_id) Return ChildBookCollection objects filtered by the collection_noosfere_id column
 * @psalm-method Collection&\Traversable<ChildBookCollection> findByNoosfereId(int $collection_noosfere_id) Return ChildBookCollection objects filtered by the collection_noosfere_id column
 * @method     ChildBookCollection[]|Collection findByInsert(string $collection_insert) Return ChildBookCollection objects filtered by the collection_insert column
 * @psalm-method Collection&\Traversable<ChildBookCollection> findByInsert(string $collection_insert) Return ChildBookCollection objects filtered by the collection_insert column
 * @method     ChildBookCollection[]|Collection findByUpdate(string $collection_update) Return ChildBookCollection objects filtered by the collection_update column
 * @psalm-method Collection&\Traversable<ChildBookCollection> findByUpdate(string $collection_update) Return ChildBookCollection objects filtered by the collection_update column
 * @method     ChildBookCollection[]|Collection findByHits(int $collection_hits) Return ChildBookCollection objects filtered by the collection_hits column
 * @psalm-method Collection&\Traversable<ChildBookCollection> findByHits(int $collection_hits) Return ChildBookCollection objects filtered by the collection_hits column
 * @method     ChildBookCollection[]|Collection findByDuplicate(boolean $collection_duplicate) Return ChildBookCollection objects filtered by the collection_duplicate column
 * @psalm-method Collection&\Traversable<ChildBookCollection> findByDuplicate(boolean $collection_duplicate) Return ChildBookCollection objects filtered by the collection_duplicate column
 * @method     ChildBookCollection[]|Collection findByCreatedAt(string $collection_created) Return ChildBookCollection objects filtered by the collection_created column
 * @psalm-method Collection&\Traversable<ChildBookCollection> findByCreatedAt(string $collection_created) Return ChildBookCollection objects filtered by the collection_created column
 * @method     ChildBookCollection[]|Collection findByUpdatedAt(string $collection_updated) Return ChildBookCollection objects filtered by the collection_updated column
 * @psalm-method Collection&\Traversable<ChildBookCollection> findByUpdatedAt(string $collection_updated) Return ChildBookCollection objects filtered by the collection_updated column
 * @method     ChildBookCollection[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildBookCollection> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class BookCollectionQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\BookCollectionQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\BookCollection', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildBookCollectionQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildBookCollectionQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
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
    public function findPk($key, ?ConnectionInterface $con = null)
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
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildBookCollection A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT collection_id, site_id, publisher_id, pricegrid_id, collection_name, collection_url, collection_publisher, collection_desc, collection_ignorenum, collection_orderby, collection_incorrect_weights, collection_noosfere_id, collection_insert, collection_update, collection_hits, collection_duplicate, collection_created, collection_updated FROM collections WHERE collection_id = :p0';
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
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
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

        $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_ID, $keys, Criteria::IN);

        return $this;
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

        $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_ID, $id, $comparison);

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

        $this->addUsingAlias(BookCollectionTableMap::COL_SITE_ID, $siteId, $comparison);

        return $this;
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
     * @param mixed $publisherId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPublisherId($publisherId = null, ?string $comparison = null)
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

        $this->addUsingAlias(BookCollectionTableMap::COL_PUBLISHER_ID, $publisherId, $comparison);

        return $this;
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
     * @param mixed $pricegridId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPricegridId($pricegridId = null, ?string $comparison = null)
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

        $this->addUsingAlias(BookCollectionTableMap::COL_PRICEGRID_ID, $pricegridId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the collection_name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE collection_name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE collection_name LIKE '%fooValue%'
     * $query->filterByName(['foo', 'bar']); // WHERE collection_name IN ('foo', 'bar')
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

        $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_NAME, $name, $comparison);

        return $this;
    }

    /**
     * Filter the query on the collection_url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE collection_url = 'fooValue'
     * $query->filterByUrl('%fooValue%', Criteria::LIKE); // WHERE collection_url LIKE '%fooValue%'
     * $query->filterByUrl(['foo', 'bar']); // WHERE collection_url IN ('foo', 'bar')
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

        $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_URL, $url, $comparison);

        return $this;
    }

    /**
     * Filter the query on the collection_publisher column
     *
     * Example usage:
     * <code>
     * $query->filterByPublisher('fooValue');   // WHERE collection_publisher = 'fooValue'
     * $query->filterByPublisher('%fooValue%', Criteria::LIKE); // WHERE collection_publisher LIKE '%fooValue%'
     * $query->filterByPublisher(['foo', 'bar']); // WHERE collection_publisher IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $publisher The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPublisher($publisher = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($publisher)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_PUBLISHER, $publisher, $comparison);

        return $this;
    }

    /**
     * Filter the query on the collection_desc column
     *
     * Example usage:
     * <code>
     * $query->filterByDesc('fooValue');   // WHERE collection_desc = 'fooValue'
     * $query->filterByDesc('%fooValue%', Criteria::LIKE); // WHERE collection_desc LIKE '%fooValue%'
     * $query->filterByDesc(['foo', 'bar']); // WHERE collection_desc IN ('foo', 'bar')
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

        $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_DESC, $desc, $comparison);

        return $this;
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
     * @param bool|string $ignorenum The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByIgnorenum($ignorenum = null, ?string $comparison = null)
    {
        if (is_string($ignorenum)) {
            $ignorenum = in_array(strtolower($ignorenum), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_IGNORENUM, $ignorenum, $comparison);

        return $this;
    }

    /**
     * Filter the query on the collection_orderby column
     *
     * Example usage:
     * <code>
     * $query->filterByOrderby('fooValue');   // WHERE collection_orderby = 'fooValue'
     * $query->filterByOrderby('%fooValue%', Criteria::LIKE); // WHERE collection_orderby LIKE '%fooValue%'
     * $query->filterByOrderby(['foo', 'bar']); // WHERE collection_orderby IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $orderby The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByOrderby($orderby = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($orderby)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_ORDERBY, $orderby, $comparison);

        return $this;
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
     * @param bool|string $incorrectWeights The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByIncorrectWeights($incorrectWeights = null, ?string $comparison = null)
    {
        if (is_string($incorrectWeights)) {
            $incorrectWeights = in_array(strtolower($incorrectWeights), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_INCORRECT_WEIGHTS, $incorrectWeights, $comparison);

        return $this;
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
     * @param mixed $noosfereId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByNoosfereId($noosfereId = null, ?string $comparison = null)
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

        $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_NOOSFERE_ID, $noosfereId, $comparison);

        return $this;
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

        $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_INSERT, $insert, $comparison);

        return $this;
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

        $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_UPDATE, $update, $comparison);

        return $this;
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
     * @param mixed $hits The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByHits($hits = null, ?string $comparison = null)
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

        $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_HITS, $hits, $comparison);

        return $this;
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
     * @param bool|string $duplicate The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDuplicate($duplicate = null, ?string $comparison = null)
    {
        if (is_string($duplicate)) {
            $duplicate = in_array(strtolower($duplicate), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_DUPLICATE, $duplicate, $comparison);

        return $this;
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

        $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_CREATED, $createdAt, $comparison);

        return $this;
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

        $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_UPDATED, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \Model\Article object
     *
     * @param \Model\Article|ObjectCollection $article the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByArticle($article, ?string $comparison = null)
    {
        if ($article instanceof \Model\Article) {
            $this
                ->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_ID, $article->getCollectionId(), $comparison);

            return $this;
        } elseif ($article instanceof ObjectCollection) {
            $this
                ->useArticleQuery()
                ->filterByPrimaryKeys($article->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByArticle() only accepts arguments of type \Model\Article or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Article relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinArticle(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Article');

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
            $this->addJoinObject($join, 'Article');
        }

        return $this;
    }

    /**
     * Use the Article relation Article object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\ArticleQuery A secondary query class using the current class as primary query
     */
    public function useArticleQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinArticle($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Article', '\Model\ArticleQuery');
    }

    /**
     * Use the Article relation Article object
     *
     * @param callable(\Model\ArticleQuery):\Model\ArticleQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withArticleQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useArticleQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }
    /**
     * Use the relation to Article table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string $typeOfExists Either ExistsCriterion::TYPE_EXISTS or ExistsCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\ArticleQuery The inner query object of the EXISTS statement
     */
    public function useArticleExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        return $this->useExistsQuery('Article', $modelAlias, $queryClass, $typeOfExists);
    }

    /**
     * Use the relation to Article table for a NOT EXISTS query.
     *
     * @see useArticleExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\ArticleQuery The inner query object of the NOT EXISTS statement
     */
    public function useArticleNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        return $this->useExistsQuery('Article', $modelAlias, $queryClass, 'NOT EXISTS');
    }
    /**
     * Exclude object from result
     *
     * @param ChildBookCollection $bookCollection Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
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
    public function doDeleteAll(?ConnectionInterface $con = null): int
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
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(?ConnectionInterface $con = null): int
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
     * @param int $nbDays Maximum age of the latest update in days
     *
     * @return $this The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(BookCollectionTableMap::COL_COLLECTION_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(BookCollectionTableMap::COL_COLLECTION_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(BookCollectionTableMap::COL_COLLECTION_CREATED);

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
        $this->addUsingAlias(BookCollectionTableMap::COL_COLLECTION_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(BookCollectionTableMap::COL_COLLECTION_CREATED);

        return $this;
    }

}
