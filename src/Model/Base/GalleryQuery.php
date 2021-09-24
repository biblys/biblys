<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Gallery as ChildGallery;
use Model\GalleryQuery as ChildGalleryQuery;
use Model\Map\GalleryTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'galleries' table.
 *
 *
 *
 * @method     ChildGalleryQuery orderById($order = Criteria::ASC) Order by the gallery_id column
 * @method     ChildGalleryQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildGalleryQuery orderByTitle($order = Criteria::ASC) Order by the gallery_title column
 * @method     ChildGalleryQuery orderByMediaDir($order = Criteria::ASC) Order by the media_dir column
 * @method     ChildGalleryQuery orderByInsert($order = Criteria::ASC) Order by the gallery_insert column
 * @method     ChildGalleryQuery orderByUpdate($order = Criteria::ASC) Order by the gallery_update column
 * @method     ChildGalleryQuery orderByCreated($order = Criteria::ASC) Order by the gallery_created column
 * @method     ChildGalleryQuery orderByUpdated($order = Criteria::ASC) Order by the gallery_updated column
 * @method     ChildGalleryQuery orderByDeleted($order = Criteria::ASC) Order by the gallery_deleted column
 *
 * @method     ChildGalleryQuery groupById() Group by the gallery_id column
 * @method     ChildGalleryQuery groupBySiteId() Group by the site_id column
 * @method     ChildGalleryQuery groupByTitle() Group by the gallery_title column
 * @method     ChildGalleryQuery groupByMediaDir() Group by the media_dir column
 * @method     ChildGalleryQuery groupByInsert() Group by the gallery_insert column
 * @method     ChildGalleryQuery groupByUpdate() Group by the gallery_update column
 * @method     ChildGalleryQuery groupByCreated() Group by the gallery_created column
 * @method     ChildGalleryQuery groupByUpdated() Group by the gallery_updated column
 * @method     ChildGalleryQuery groupByDeleted() Group by the gallery_deleted column
 *
 * @method     ChildGalleryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildGalleryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildGalleryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildGalleryQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildGalleryQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildGalleryQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildGallery|null findOne(ConnectionInterface $con = null) Return the first ChildGallery matching the query
 * @method     ChildGallery findOneOrCreate(ConnectionInterface $con = null) Return the first ChildGallery matching the query, or a new ChildGallery object populated from the query conditions when no match is found
 *
 * @method     ChildGallery|null findOneById(int $gallery_id) Return the first ChildGallery filtered by the gallery_id column
 * @method     ChildGallery|null findOneBySiteId(int $site_id) Return the first ChildGallery filtered by the site_id column
 * @method     ChildGallery|null findOneByTitle(string $gallery_title) Return the first ChildGallery filtered by the gallery_title column
 * @method     ChildGallery|null findOneByMediaDir(string $media_dir) Return the first ChildGallery filtered by the media_dir column
 * @method     ChildGallery|null findOneByInsert(string $gallery_insert) Return the first ChildGallery filtered by the gallery_insert column
 * @method     ChildGallery|null findOneByUpdate(string $gallery_update) Return the first ChildGallery filtered by the gallery_update column
 * @method     ChildGallery|null findOneByCreated(string $gallery_created) Return the first ChildGallery filtered by the gallery_created column
 * @method     ChildGallery|null findOneByUpdated(string $gallery_updated) Return the first ChildGallery filtered by the gallery_updated column
 * @method     ChildGallery|null findOneByDeleted(string $gallery_deleted) Return the first ChildGallery filtered by the gallery_deleted column *

 * @method     ChildGallery requirePk($key, ConnectionInterface $con = null) Return the ChildGallery by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGallery requireOne(ConnectionInterface $con = null) Return the first ChildGallery matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildGallery requireOneById(int $gallery_id) Return the first ChildGallery filtered by the gallery_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGallery requireOneBySiteId(int $site_id) Return the first ChildGallery filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGallery requireOneByTitle(string $gallery_title) Return the first ChildGallery filtered by the gallery_title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGallery requireOneByMediaDir(string $media_dir) Return the first ChildGallery filtered by the media_dir column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGallery requireOneByInsert(string $gallery_insert) Return the first ChildGallery filtered by the gallery_insert column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGallery requireOneByUpdate(string $gallery_update) Return the first ChildGallery filtered by the gallery_update column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGallery requireOneByCreated(string $gallery_created) Return the first ChildGallery filtered by the gallery_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGallery requireOneByUpdated(string $gallery_updated) Return the first ChildGallery filtered by the gallery_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGallery requireOneByDeleted(string $gallery_deleted) Return the first ChildGallery filtered by the gallery_deleted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildGallery[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildGallery objects based on current ModelCriteria
 * @psalm-method ObjectCollection&\Traversable<ChildGallery> find(ConnectionInterface $con = null) Return ChildGallery objects based on current ModelCriteria
 * @method     ChildGallery[]|ObjectCollection findById(int $gallery_id) Return ChildGallery objects filtered by the gallery_id column
 * @psalm-method ObjectCollection&\Traversable<ChildGallery> findById(int $gallery_id) Return ChildGallery objects filtered by the gallery_id column
 * @method     ChildGallery[]|ObjectCollection findBySiteId(int $site_id) Return ChildGallery objects filtered by the site_id column
 * @psalm-method ObjectCollection&\Traversable<ChildGallery> findBySiteId(int $site_id) Return ChildGallery objects filtered by the site_id column
 * @method     ChildGallery[]|ObjectCollection findByTitle(string $gallery_title) Return ChildGallery objects filtered by the gallery_title column
 * @psalm-method ObjectCollection&\Traversable<ChildGallery> findByTitle(string $gallery_title) Return ChildGallery objects filtered by the gallery_title column
 * @method     ChildGallery[]|ObjectCollection findByMediaDir(string $media_dir) Return ChildGallery objects filtered by the media_dir column
 * @psalm-method ObjectCollection&\Traversable<ChildGallery> findByMediaDir(string $media_dir) Return ChildGallery objects filtered by the media_dir column
 * @method     ChildGallery[]|ObjectCollection findByInsert(string $gallery_insert) Return ChildGallery objects filtered by the gallery_insert column
 * @psalm-method ObjectCollection&\Traversable<ChildGallery> findByInsert(string $gallery_insert) Return ChildGallery objects filtered by the gallery_insert column
 * @method     ChildGallery[]|ObjectCollection findByUpdate(string $gallery_update) Return ChildGallery objects filtered by the gallery_update column
 * @psalm-method ObjectCollection&\Traversable<ChildGallery> findByUpdate(string $gallery_update) Return ChildGallery objects filtered by the gallery_update column
 * @method     ChildGallery[]|ObjectCollection findByCreated(string $gallery_created) Return ChildGallery objects filtered by the gallery_created column
 * @psalm-method ObjectCollection&\Traversable<ChildGallery> findByCreated(string $gallery_created) Return ChildGallery objects filtered by the gallery_created column
 * @method     ChildGallery[]|ObjectCollection findByUpdated(string $gallery_updated) Return ChildGallery objects filtered by the gallery_updated column
 * @psalm-method ObjectCollection&\Traversable<ChildGallery> findByUpdated(string $gallery_updated) Return ChildGallery objects filtered by the gallery_updated column
 * @method     ChildGallery[]|ObjectCollection findByDeleted(string $gallery_deleted) Return ChildGallery objects filtered by the gallery_deleted column
 * @psalm-method ObjectCollection&\Traversable<ChildGallery> findByDeleted(string $gallery_deleted) Return ChildGallery objects filtered by the gallery_deleted column
 * @method     ChildGallery[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildGallery> paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class GalleryQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\GalleryQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Gallery', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildGalleryQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildGalleryQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildGalleryQuery) {
            return $criteria;
        }
        $query = new ChildGalleryQuery();
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
     * @return ChildGallery|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(GalleryTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = GalleryTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildGallery A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT gallery_id, site_id, gallery_title, media_dir, gallery_insert, gallery_update, gallery_created, gallery_updated, gallery_deleted FROM galleries WHERE gallery_id = :p0';
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
            /** @var ChildGallery $obj */
            $obj = new ChildGallery();
            $obj->hydrate($row);
            GalleryTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildGallery|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildGalleryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(GalleryTableMap::COL_GALLERY_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildGalleryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(GalleryTableMap::COL_GALLERY_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the gallery_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE gallery_id = 1234
     * $query->filterById(array(12, 34)); // WHERE gallery_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE gallery_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGalleryQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(GalleryTableMap::COL_GALLERY_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(GalleryTableMap::COL_GALLERY_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GalleryTableMap::COL_GALLERY_ID, $id, $comparison);
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
     * @return $this|ChildGalleryQuery The current query, for fluid interface
     */
    public function filterBySiteId($siteId = null, $comparison = null)
    {
        if (is_array($siteId)) {
            $useMinMax = false;
            if (isset($siteId['min'])) {
                $this->addUsingAlias(GalleryTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(GalleryTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GalleryTableMap::COL_SITE_ID, $siteId, $comparison);
    }

    /**
     * Filter the query on the gallery_title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE gallery_title = 'fooValue'
     * $query->filterByTitle('%fooValue%', Criteria::LIKE); // WHERE gallery_title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGalleryQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GalleryTableMap::COL_GALLERY_TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the media_dir column
     *
     * Example usage:
     * <code>
     * $query->filterByMediaDir('fooValue');   // WHERE media_dir = 'fooValue'
     * $query->filterByMediaDir('%fooValue%', Criteria::LIKE); // WHERE media_dir LIKE '%fooValue%'
     * </code>
     *
     * @param     string $mediaDir The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGalleryQuery The current query, for fluid interface
     */
    public function filterByMediaDir($mediaDir = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($mediaDir)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GalleryTableMap::COL_MEDIA_DIR, $mediaDir, $comparison);
    }

    /**
     * Filter the query on the gallery_insert column
     *
     * Example usage:
     * <code>
     * $query->filterByInsert('2011-03-14'); // WHERE gallery_insert = '2011-03-14'
     * $query->filterByInsert('now'); // WHERE gallery_insert = '2011-03-14'
     * $query->filterByInsert(array('max' => 'yesterday')); // WHERE gallery_insert > '2011-03-13'
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
     * @return $this|ChildGalleryQuery The current query, for fluid interface
     */
    public function filterByInsert($insert = null, $comparison = null)
    {
        if (is_array($insert)) {
            $useMinMax = false;
            if (isset($insert['min'])) {
                $this->addUsingAlias(GalleryTableMap::COL_GALLERY_INSERT, $insert['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($insert['max'])) {
                $this->addUsingAlias(GalleryTableMap::COL_GALLERY_INSERT, $insert['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GalleryTableMap::COL_GALLERY_INSERT, $insert, $comparison);
    }

    /**
     * Filter the query on the gallery_update column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdate('2011-03-14'); // WHERE gallery_update = '2011-03-14'
     * $query->filterByUpdate('now'); // WHERE gallery_update = '2011-03-14'
     * $query->filterByUpdate(array('max' => 'yesterday')); // WHERE gallery_update > '2011-03-13'
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
     * @return $this|ChildGalleryQuery The current query, for fluid interface
     */
    public function filterByUpdate($update = null, $comparison = null)
    {
        if (is_array($update)) {
            $useMinMax = false;
            if (isset($update['min'])) {
                $this->addUsingAlias(GalleryTableMap::COL_GALLERY_UPDATE, $update['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($update['max'])) {
                $this->addUsingAlias(GalleryTableMap::COL_GALLERY_UPDATE, $update['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GalleryTableMap::COL_GALLERY_UPDATE, $update, $comparison);
    }

    /**
     * Filter the query on the gallery_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreated('2011-03-14'); // WHERE gallery_created = '2011-03-14'
     * $query->filterByCreated('now'); // WHERE gallery_created = '2011-03-14'
     * $query->filterByCreated(array('max' => 'yesterday')); // WHERE gallery_created > '2011-03-13'
     * </code>
     *
     * @param     mixed $created The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGalleryQuery The current query, for fluid interface
     */
    public function filterByCreated($created = null, $comparison = null)
    {
        if (is_array($created)) {
            $useMinMax = false;
            if (isset($created['min'])) {
                $this->addUsingAlias(GalleryTableMap::COL_GALLERY_CREATED, $created['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($created['max'])) {
                $this->addUsingAlias(GalleryTableMap::COL_GALLERY_CREATED, $created['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GalleryTableMap::COL_GALLERY_CREATED, $created, $comparison);
    }

    /**
     * Filter the query on the gallery_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdated('2011-03-14'); // WHERE gallery_updated = '2011-03-14'
     * $query->filterByUpdated('now'); // WHERE gallery_updated = '2011-03-14'
     * $query->filterByUpdated(array('max' => 'yesterday')); // WHERE gallery_updated > '2011-03-13'
     * </code>
     *
     * @param     mixed $updated The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGalleryQuery The current query, for fluid interface
     */
    public function filterByUpdated($updated = null, $comparison = null)
    {
        if (is_array($updated)) {
            $useMinMax = false;
            if (isset($updated['min'])) {
                $this->addUsingAlias(GalleryTableMap::COL_GALLERY_UPDATED, $updated['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updated['max'])) {
                $this->addUsingAlias(GalleryTableMap::COL_GALLERY_UPDATED, $updated['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GalleryTableMap::COL_GALLERY_UPDATED, $updated, $comparison);
    }

    /**
     * Filter the query on the gallery_deleted column
     *
     * Example usage:
     * <code>
     * $query->filterByDeleted('2011-03-14'); // WHERE gallery_deleted = '2011-03-14'
     * $query->filterByDeleted('now'); // WHERE gallery_deleted = '2011-03-14'
     * $query->filterByDeleted(array('max' => 'yesterday')); // WHERE gallery_deleted > '2011-03-13'
     * </code>
     *
     * @param     mixed $deleted The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGalleryQuery The current query, for fluid interface
     */
    public function filterByDeleted($deleted = null, $comparison = null)
    {
        if (is_array($deleted)) {
            $useMinMax = false;
            if (isset($deleted['min'])) {
                $this->addUsingAlias(GalleryTableMap::COL_GALLERY_DELETED, $deleted['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deleted['max'])) {
                $this->addUsingAlias(GalleryTableMap::COL_GALLERY_DELETED, $deleted['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GalleryTableMap::COL_GALLERY_DELETED, $deleted, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildGallery $gallery Object to remove from the list of results
     *
     * @return $this|ChildGalleryQuery The current query, for fluid interface
     */
    public function prune($gallery = null)
    {
        if ($gallery) {
            $this->addUsingAlias(GalleryTableMap::COL_GALLERY_ID, $gallery->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the galleries table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GalleryTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            GalleryTableMap::clearInstancePool();
            GalleryTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(GalleryTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(GalleryTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            GalleryTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            GalleryTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildGalleryQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(GalleryTableMap::COL_GALLERY_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildGalleryQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(GalleryTableMap::COL_GALLERY_UPDATED);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildGalleryQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(GalleryTableMap::COL_GALLERY_UPDATED);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildGalleryQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(GalleryTableMap::COL_GALLERY_CREATED);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildGalleryQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(GalleryTableMap::COL_GALLERY_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildGalleryQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(GalleryTableMap::COL_GALLERY_CREATED);
    }

} // GalleryQuery
