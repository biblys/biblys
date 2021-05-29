<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Image as ChildImage;
use Model\ImageQuery as ChildImageQuery;
use Model\Map\ImageTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'images' table.
 *
 *
 *
 * @method     ChildImageQuery orderById($order = Criteria::ASC) Order by the image_id column
 * @method     ChildImageQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildImageQuery orderByBookshopId($order = Criteria::ASC) Order by the bookshop_id column
 * @method     ChildImageQuery orderByEventId($order = Criteria::ASC) Order by the event_id column
 * @method     ChildImageQuery orderByLibraryId($order = Criteria::ASC) Order by the library_id column
 * @method     ChildImageQuery orderByNature($order = Criteria::ASC) Order by the image_nature column
 * @method     ChildImageQuery orderByLegend($order = Criteria::ASC) Order by the image_legend column
 * @method     ChildImageQuery orderByType($order = Criteria::ASC) Order by the image_type column
 * @method     ChildImageQuery orderBySize($order = Criteria::ASC) Order by the image_size column
 * @method     ChildImageQuery orderByInserted($order = Criteria::ASC) Order by the image_inserted column
 * @method     ChildImageQuery orderByUploaded($order = Criteria::ASC) Order by the image_uploaded column
 * @method     ChildImageQuery orderByUpdatedAt($order = Criteria::ASC) Order by the image_updated column
 * @method     ChildImageQuery orderByDeletedAt($order = Criteria::ASC) Order by the image_deleted column
 *
 * @method     ChildImageQuery groupById() Group by the image_id column
 * @method     ChildImageQuery groupBySiteId() Group by the site_id column
 * @method     ChildImageQuery groupByBookshopId() Group by the bookshop_id column
 * @method     ChildImageQuery groupByEventId() Group by the event_id column
 * @method     ChildImageQuery groupByLibraryId() Group by the library_id column
 * @method     ChildImageQuery groupByNature() Group by the image_nature column
 * @method     ChildImageQuery groupByLegend() Group by the image_legend column
 * @method     ChildImageQuery groupByType() Group by the image_type column
 * @method     ChildImageQuery groupBySize() Group by the image_size column
 * @method     ChildImageQuery groupByInserted() Group by the image_inserted column
 * @method     ChildImageQuery groupByUploaded() Group by the image_uploaded column
 * @method     ChildImageQuery groupByUpdatedAt() Group by the image_updated column
 * @method     ChildImageQuery groupByDeletedAt() Group by the image_deleted column
 *
 * @method     ChildImageQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildImageQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildImageQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildImageQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildImageQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildImageQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildImage|null findOne(ConnectionInterface $con = null) Return the first ChildImage matching the query
 * @method     ChildImage findOneOrCreate(ConnectionInterface $con = null) Return the first ChildImage matching the query, or a new ChildImage object populated from the query conditions when no match is found
 *
 * @method     ChildImage|null findOneById(int $image_id) Return the first ChildImage filtered by the image_id column
 * @method     ChildImage|null findOneBySiteId(int $site_id) Return the first ChildImage filtered by the site_id column
 * @method     ChildImage|null findOneByBookshopId(int $bookshop_id) Return the first ChildImage filtered by the bookshop_id column
 * @method     ChildImage|null findOneByEventId(int $event_id) Return the first ChildImage filtered by the event_id column
 * @method     ChildImage|null findOneByLibraryId(int $library_id) Return the first ChildImage filtered by the library_id column
 * @method     ChildImage|null findOneByNature(string $image_nature) Return the first ChildImage filtered by the image_nature column
 * @method     ChildImage|null findOneByLegend(string $image_legend) Return the first ChildImage filtered by the image_legend column
 * @method     ChildImage|null findOneByType(string $image_type) Return the first ChildImage filtered by the image_type column
 * @method     ChildImage|null findOneBySize(string $image_size) Return the first ChildImage filtered by the image_size column
 * @method     ChildImage|null findOneByInserted(string $image_inserted) Return the first ChildImage filtered by the image_inserted column
 * @method     ChildImage|null findOneByUploaded(string $image_uploaded) Return the first ChildImage filtered by the image_uploaded column
 * @method     ChildImage|null findOneByUpdatedAt(string $image_updated) Return the first ChildImage filtered by the image_updated column
 * @method     ChildImage|null findOneByDeletedAt(string $image_deleted) Return the first ChildImage filtered by the image_deleted column *

 * @method     ChildImage requirePk($key, ConnectionInterface $con = null) Return the ChildImage by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildImage requireOne(ConnectionInterface $con = null) Return the first ChildImage matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildImage requireOneById(int $image_id) Return the first ChildImage filtered by the image_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildImage requireOneBySiteId(int $site_id) Return the first ChildImage filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildImage requireOneByBookshopId(int $bookshop_id) Return the first ChildImage filtered by the bookshop_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildImage requireOneByEventId(int $event_id) Return the first ChildImage filtered by the event_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildImage requireOneByLibraryId(int $library_id) Return the first ChildImage filtered by the library_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildImage requireOneByNature(string $image_nature) Return the first ChildImage filtered by the image_nature column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildImage requireOneByLegend(string $image_legend) Return the first ChildImage filtered by the image_legend column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildImage requireOneByType(string $image_type) Return the first ChildImage filtered by the image_type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildImage requireOneBySize(string $image_size) Return the first ChildImage filtered by the image_size column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildImage requireOneByInserted(string $image_inserted) Return the first ChildImage filtered by the image_inserted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildImage requireOneByUploaded(string $image_uploaded) Return the first ChildImage filtered by the image_uploaded column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildImage requireOneByUpdatedAt(string $image_updated) Return the first ChildImage filtered by the image_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildImage requireOneByDeletedAt(string $image_deleted) Return the first ChildImage filtered by the image_deleted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildImage[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildImage objects based on current ModelCriteria
 * @method     ChildImage[]|ObjectCollection findById(int $image_id) Return ChildImage objects filtered by the image_id column
 * @method     ChildImage[]|ObjectCollection findBySiteId(int $site_id) Return ChildImage objects filtered by the site_id column
 * @method     ChildImage[]|ObjectCollection findByBookshopId(int $bookshop_id) Return ChildImage objects filtered by the bookshop_id column
 * @method     ChildImage[]|ObjectCollection findByEventId(int $event_id) Return ChildImage objects filtered by the event_id column
 * @method     ChildImage[]|ObjectCollection findByLibraryId(int $library_id) Return ChildImage objects filtered by the library_id column
 * @method     ChildImage[]|ObjectCollection findByNature(string $image_nature) Return ChildImage objects filtered by the image_nature column
 * @method     ChildImage[]|ObjectCollection findByLegend(string $image_legend) Return ChildImage objects filtered by the image_legend column
 * @method     ChildImage[]|ObjectCollection findByType(string $image_type) Return ChildImage objects filtered by the image_type column
 * @method     ChildImage[]|ObjectCollection findBySize(string $image_size) Return ChildImage objects filtered by the image_size column
 * @method     ChildImage[]|ObjectCollection findByInserted(string $image_inserted) Return ChildImage objects filtered by the image_inserted column
 * @method     ChildImage[]|ObjectCollection findByUploaded(string $image_uploaded) Return ChildImage objects filtered by the image_uploaded column
 * @method     ChildImage[]|ObjectCollection findByUpdatedAt(string $image_updated) Return ChildImage objects filtered by the image_updated column
 * @method     ChildImage[]|ObjectCollection findByDeletedAt(string $image_deleted) Return ChildImage objects filtered by the image_deleted column
 * @method     ChildImage[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ImageQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\ImageQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Image', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildImageQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildImageQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildImageQuery) {
            return $criteria;
        }
        $query = new ChildImageQuery();
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
     * @return ChildImage|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ImageTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ImageTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildImage A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT image_id, site_id, bookshop_id, event_id, library_id, image_nature, image_legend, image_type, image_size, image_inserted, image_uploaded, image_updated, image_deleted FROM images WHERE image_id = :p0';
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
            /** @var ChildImage $obj */
            $obj = new ChildImage();
            $obj->hydrate($row);
            ImageTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildImage|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildImageQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ImageTableMap::COL_IMAGE_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildImageQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ImageTableMap::COL_IMAGE_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the image_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE image_id = 1234
     * $query->filterById(array(12, 34)); // WHERE image_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE image_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildImageQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ImageTableMap::COL_IMAGE_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ImageTableMap::COL_IMAGE_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ImageTableMap::COL_IMAGE_ID, $id, $comparison);
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
     * @return $this|ChildImageQuery The current query, for fluid interface
     */
    public function filterBySiteId($siteId = null, $comparison = null)
    {
        if (is_array($siteId)) {
            $useMinMax = false;
            if (isset($siteId['min'])) {
                $this->addUsingAlias(ImageTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(ImageTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ImageTableMap::COL_SITE_ID, $siteId, $comparison);
    }

    /**
     * Filter the query on the bookshop_id column
     *
     * Example usage:
     * <code>
     * $query->filterByBookshopId(1234); // WHERE bookshop_id = 1234
     * $query->filterByBookshopId(array(12, 34)); // WHERE bookshop_id IN (12, 34)
     * $query->filterByBookshopId(array('min' => 12)); // WHERE bookshop_id > 12
     * </code>
     *
     * @param     mixed $bookshopId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildImageQuery The current query, for fluid interface
     */
    public function filterByBookshopId($bookshopId = null, $comparison = null)
    {
        if (is_array($bookshopId)) {
            $useMinMax = false;
            if (isset($bookshopId['min'])) {
                $this->addUsingAlias(ImageTableMap::COL_BOOKSHOP_ID, $bookshopId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bookshopId['max'])) {
                $this->addUsingAlias(ImageTableMap::COL_BOOKSHOP_ID, $bookshopId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ImageTableMap::COL_BOOKSHOP_ID, $bookshopId, $comparison);
    }

    /**
     * Filter the query on the event_id column
     *
     * Example usage:
     * <code>
     * $query->filterByEventId(1234); // WHERE event_id = 1234
     * $query->filterByEventId(array(12, 34)); // WHERE event_id IN (12, 34)
     * $query->filterByEventId(array('min' => 12)); // WHERE event_id > 12
     * </code>
     *
     * @param     mixed $eventId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildImageQuery The current query, for fluid interface
     */
    public function filterByEventId($eventId = null, $comparison = null)
    {
        if (is_array($eventId)) {
            $useMinMax = false;
            if (isset($eventId['min'])) {
                $this->addUsingAlias(ImageTableMap::COL_EVENT_ID, $eventId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventId['max'])) {
                $this->addUsingAlias(ImageTableMap::COL_EVENT_ID, $eventId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ImageTableMap::COL_EVENT_ID, $eventId, $comparison);
    }

    /**
     * Filter the query on the library_id column
     *
     * Example usage:
     * <code>
     * $query->filterByLibraryId(1234); // WHERE library_id = 1234
     * $query->filterByLibraryId(array(12, 34)); // WHERE library_id IN (12, 34)
     * $query->filterByLibraryId(array('min' => 12)); // WHERE library_id > 12
     * </code>
     *
     * @param     mixed $libraryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildImageQuery The current query, for fluid interface
     */
    public function filterByLibraryId($libraryId = null, $comparison = null)
    {
        if (is_array($libraryId)) {
            $useMinMax = false;
            if (isset($libraryId['min'])) {
                $this->addUsingAlias(ImageTableMap::COL_LIBRARY_ID, $libraryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($libraryId['max'])) {
                $this->addUsingAlias(ImageTableMap::COL_LIBRARY_ID, $libraryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ImageTableMap::COL_LIBRARY_ID, $libraryId, $comparison);
    }

    /**
     * Filter the query on the image_nature column
     *
     * Example usage:
     * <code>
     * $query->filterByNature('fooValue');   // WHERE image_nature = 'fooValue'
     * $query->filterByNature('%fooValue%', Criteria::LIKE); // WHERE image_nature LIKE '%fooValue%'
     * </code>
     *
     * @param     string $nature The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildImageQuery The current query, for fluid interface
     */
    public function filterByNature($nature = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nature)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ImageTableMap::COL_IMAGE_NATURE, $nature, $comparison);
    }

    /**
     * Filter the query on the image_legend column
     *
     * Example usage:
     * <code>
     * $query->filterByLegend('fooValue');   // WHERE image_legend = 'fooValue'
     * $query->filterByLegend('%fooValue%', Criteria::LIKE); // WHERE image_legend LIKE '%fooValue%'
     * </code>
     *
     * @param     string $legend The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildImageQuery The current query, for fluid interface
     */
    public function filterByLegend($legend = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($legend)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ImageTableMap::COL_IMAGE_LEGEND, $legend, $comparison);
    }

    /**
     * Filter the query on the image_type column
     *
     * Example usage:
     * <code>
     * $query->filterByType('fooValue');   // WHERE image_type = 'fooValue'
     * $query->filterByType('%fooValue%', Criteria::LIKE); // WHERE image_type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $type The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildImageQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($type)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ImageTableMap::COL_IMAGE_TYPE, $type, $comparison);
    }

    /**
     * Filter the query on the image_size column
     *
     * Example usage:
     * <code>
     * $query->filterBySize(1234); // WHERE image_size = 1234
     * $query->filterBySize(array(12, 34)); // WHERE image_size IN (12, 34)
     * $query->filterBySize(array('min' => 12)); // WHERE image_size > 12
     * </code>
     *
     * @param     mixed $size The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildImageQuery The current query, for fluid interface
     */
    public function filterBySize($size = null, $comparison = null)
    {
        if (is_array($size)) {
            $useMinMax = false;
            if (isset($size['min'])) {
                $this->addUsingAlias(ImageTableMap::COL_IMAGE_SIZE, $size['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($size['max'])) {
                $this->addUsingAlias(ImageTableMap::COL_IMAGE_SIZE, $size['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ImageTableMap::COL_IMAGE_SIZE, $size, $comparison);
    }

    /**
     * Filter the query on the image_inserted column
     *
     * Example usage:
     * <code>
     * $query->filterByInserted('2011-03-14'); // WHERE image_inserted = '2011-03-14'
     * $query->filterByInserted('now'); // WHERE image_inserted = '2011-03-14'
     * $query->filterByInserted(array('max' => 'yesterday')); // WHERE image_inserted > '2011-03-13'
     * </code>
     *
     * @param     mixed $inserted The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildImageQuery The current query, for fluid interface
     */
    public function filterByInserted($inserted = null, $comparison = null)
    {
        if (is_array($inserted)) {
            $useMinMax = false;
            if (isset($inserted['min'])) {
                $this->addUsingAlias(ImageTableMap::COL_IMAGE_INSERTED, $inserted['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($inserted['max'])) {
                $this->addUsingAlias(ImageTableMap::COL_IMAGE_INSERTED, $inserted['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ImageTableMap::COL_IMAGE_INSERTED, $inserted, $comparison);
    }

    /**
     * Filter the query on the image_uploaded column
     *
     * Example usage:
     * <code>
     * $query->filterByUploaded('2011-03-14'); // WHERE image_uploaded = '2011-03-14'
     * $query->filterByUploaded('now'); // WHERE image_uploaded = '2011-03-14'
     * $query->filterByUploaded(array('max' => 'yesterday')); // WHERE image_uploaded > '2011-03-13'
     * </code>
     *
     * @param     mixed $uploaded The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildImageQuery The current query, for fluid interface
     */
    public function filterByUploaded($uploaded = null, $comparison = null)
    {
        if (is_array($uploaded)) {
            $useMinMax = false;
            if (isset($uploaded['min'])) {
                $this->addUsingAlias(ImageTableMap::COL_IMAGE_UPLOADED, $uploaded['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($uploaded['max'])) {
                $this->addUsingAlias(ImageTableMap::COL_IMAGE_UPLOADED, $uploaded['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ImageTableMap::COL_IMAGE_UPLOADED, $uploaded, $comparison);
    }

    /**
     * Filter the query on the image_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE image_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE image_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE image_updated > '2011-03-13'
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
     * @return $this|ChildImageQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(ImageTableMap::COL_IMAGE_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(ImageTableMap::COL_IMAGE_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ImageTableMap::COL_IMAGE_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the image_deleted column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletedAt('2011-03-14'); // WHERE image_deleted = '2011-03-14'
     * $query->filterByDeletedAt('now'); // WHERE image_deleted = '2011-03-14'
     * $query->filterByDeletedAt(array('max' => 'yesterday')); // WHERE image_deleted > '2011-03-13'
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
     * @return $this|ChildImageQuery The current query, for fluid interface
     */
    public function filterByDeletedAt($deletedAt = null, $comparison = null)
    {
        if (is_array($deletedAt)) {
            $useMinMax = false;
            if (isset($deletedAt['min'])) {
                $this->addUsingAlias(ImageTableMap::COL_IMAGE_DELETED, $deletedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletedAt['max'])) {
                $this->addUsingAlias(ImageTableMap::COL_IMAGE_DELETED, $deletedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ImageTableMap::COL_IMAGE_DELETED, $deletedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildImage $image Object to remove from the list of results
     *
     * @return $this|ChildImageQuery The current query, for fluid interface
     */
    public function prune($image = null)
    {
        if ($image) {
            $this->addUsingAlias(ImageTableMap::COL_IMAGE_ID, $image->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the images table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ImageTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ImageTableMap::clearInstancePool();
            ImageTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ImageTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ImageTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ImageTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ImageTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ImageQuery
