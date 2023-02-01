<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Event as ChildEvent;
use Model\EventQuery as ChildEventQuery;
use Model\Map\EventTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `events` table.
 *
 * @method     ChildEventQuery orderById($order = Criteria::ASC) Order by the event_id column
 * @method     ChildEventQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildEventQuery orderByPublisherId($order = Criteria::ASC) Order by the publisher_id column
 * @method     ChildEventQuery orderByBookshopId($order = Criteria::ASC) Order by the bookshop_id column
 * @method     ChildEventQuery orderByLibraryId($order = Criteria::ASC) Order by the library_id column
 * @method     ChildEventQuery orderByUrl($order = Criteria::ASC) Order by the event_url column
 * @method     ChildEventQuery orderByTitle($order = Criteria::ASC) Order by the event_title column
 * @method     ChildEventQuery orderBySubtitle($order = Criteria::ASC) Order by the event_subtitle column
 * @method     ChildEventQuery orderByDesc($order = Criteria::ASC) Order by the event_desc column
 * @method     ChildEventQuery orderByLocation($order = Criteria::ASC) Order by the event_location column
 * @method     ChildEventQuery orderByIllustrationLegend($order = Criteria::ASC) Order by the event_illustration_legend column
 * @method     ChildEventQuery orderByHighlighted($order = Criteria::ASC) Order by the event_highlighted column
 * @method     ChildEventQuery orderByStart($order = Criteria::ASC) Order by the event_start column
 * @method     ChildEventQuery orderByEnd($order = Criteria::ASC) Order by the event_end column
 * @method     ChildEventQuery orderByDate($order = Criteria::ASC) Order by the event_date column
 * @method     ChildEventQuery orderByStatus($order = Criteria::ASC) Order by the event_status column
 * @method     ChildEventQuery orderByInsert($order = Criteria::ASC) Order by the event_insert_ column
 * @method     ChildEventQuery orderByUpdate($order = Criteria::ASC) Order by the event_update_ column
 * @method     ChildEventQuery orderByCreatedAt($order = Criteria::ASC) Order by the event_created column
 * @method     ChildEventQuery orderByUpdatedAt($order = Criteria::ASC) Order by the event_updated column
 *
 * @method     ChildEventQuery groupById() Group by the event_id column
 * @method     ChildEventQuery groupBySiteId() Group by the site_id column
 * @method     ChildEventQuery groupByPublisherId() Group by the publisher_id column
 * @method     ChildEventQuery groupByBookshopId() Group by the bookshop_id column
 * @method     ChildEventQuery groupByLibraryId() Group by the library_id column
 * @method     ChildEventQuery groupByUrl() Group by the event_url column
 * @method     ChildEventQuery groupByTitle() Group by the event_title column
 * @method     ChildEventQuery groupBySubtitle() Group by the event_subtitle column
 * @method     ChildEventQuery groupByDesc() Group by the event_desc column
 * @method     ChildEventQuery groupByLocation() Group by the event_location column
 * @method     ChildEventQuery groupByIllustrationLegend() Group by the event_illustration_legend column
 * @method     ChildEventQuery groupByHighlighted() Group by the event_highlighted column
 * @method     ChildEventQuery groupByStart() Group by the event_start column
 * @method     ChildEventQuery groupByEnd() Group by the event_end column
 * @method     ChildEventQuery groupByDate() Group by the event_date column
 * @method     ChildEventQuery groupByStatus() Group by the event_status column
 * @method     ChildEventQuery groupByInsert() Group by the event_insert_ column
 * @method     ChildEventQuery groupByUpdate() Group by the event_update_ column
 * @method     ChildEventQuery groupByCreatedAt() Group by the event_created column
 * @method     ChildEventQuery groupByUpdatedAt() Group by the event_updated column
 *
 * @method     ChildEventQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildEventQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildEventQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildEventQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildEventQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildEventQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildEvent|null findOne(?ConnectionInterface $con = null) Return the first ChildEvent matching the query
 * @method     ChildEvent findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildEvent matching the query, or a new ChildEvent object populated from the query conditions when no match is found
 *
 * @method     ChildEvent|null findOneById(int $event_id) Return the first ChildEvent filtered by the event_id column
 * @method     ChildEvent|null findOneBySiteId(int $site_id) Return the first ChildEvent filtered by the site_id column
 * @method     ChildEvent|null findOneByPublisherId(int $publisher_id) Return the first ChildEvent filtered by the publisher_id column
 * @method     ChildEvent|null findOneByBookshopId(int $bookshop_id) Return the first ChildEvent filtered by the bookshop_id column
 * @method     ChildEvent|null findOneByLibraryId(int $library_id) Return the first ChildEvent filtered by the library_id column
 * @method     ChildEvent|null findOneByUrl(string $event_url) Return the first ChildEvent filtered by the event_url column
 * @method     ChildEvent|null findOneByTitle(string $event_title) Return the first ChildEvent filtered by the event_title column
 * @method     ChildEvent|null findOneBySubtitle(string $event_subtitle) Return the first ChildEvent filtered by the event_subtitle column
 * @method     ChildEvent|null findOneByDesc(string $event_desc) Return the first ChildEvent filtered by the event_desc column
 * @method     ChildEvent|null findOneByLocation(string $event_location) Return the first ChildEvent filtered by the event_location column
 * @method     ChildEvent|null findOneByIllustrationLegend(string $event_illustration_legend) Return the first ChildEvent filtered by the event_illustration_legend column
 * @method     ChildEvent|null findOneByHighlighted(boolean $event_highlighted) Return the first ChildEvent filtered by the event_highlighted column
 * @method     ChildEvent|null findOneByStart(string $event_start) Return the first ChildEvent filtered by the event_start column
 * @method     ChildEvent|null findOneByEnd(string $event_end) Return the first ChildEvent filtered by the event_end column
 * @method     ChildEvent|null findOneByDate(string $event_date) Return the first ChildEvent filtered by the event_date column
 * @method     ChildEvent|null findOneByStatus(boolean $event_status) Return the first ChildEvent filtered by the event_status column
 * @method     ChildEvent|null findOneByInsert(string $event_insert_) Return the first ChildEvent filtered by the event_insert_ column
 * @method     ChildEvent|null findOneByUpdate(string $event_update_) Return the first ChildEvent filtered by the event_update_ column
 * @method     ChildEvent|null findOneByCreatedAt(string $event_created) Return the first ChildEvent filtered by the event_created column
 * @method     ChildEvent|null findOneByUpdatedAt(string $event_updated) Return the first ChildEvent filtered by the event_updated column
 *
 * @method     ChildEvent requirePk($key, ?ConnectionInterface $con = null) Return the ChildEvent by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOne(?ConnectionInterface $con = null) Return the first ChildEvent matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEvent requireOneById(int $event_id) Return the first ChildEvent filtered by the event_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneBySiteId(int $site_id) Return the first ChildEvent filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByPublisherId(int $publisher_id) Return the first ChildEvent filtered by the publisher_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByBookshopId(int $bookshop_id) Return the first ChildEvent filtered by the bookshop_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByLibraryId(int $library_id) Return the first ChildEvent filtered by the library_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByUrl(string $event_url) Return the first ChildEvent filtered by the event_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByTitle(string $event_title) Return the first ChildEvent filtered by the event_title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneBySubtitle(string $event_subtitle) Return the first ChildEvent filtered by the event_subtitle column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByDesc(string $event_desc) Return the first ChildEvent filtered by the event_desc column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByLocation(string $event_location) Return the first ChildEvent filtered by the event_location column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByIllustrationLegend(string $event_illustration_legend) Return the first ChildEvent filtered by the event_illustration_legend column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByHighlighted(boolean $event_highlighted) Return the first ChildEvent filtered by the event_highlighted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByStart(string $event_start) Return the first ChildEvent filtered by the event_start column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByEnd(string $event_end) Return the first ChildEvent filtered by the event_end column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByDate(string $event_date) Return the first ChildEvent filtered by the event_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByStatus(boolean $event_status) Return the first ChildEvent filtered by the event_status column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByInsert(string $event_insert_) Return the first ChildEvent filtered by the event_insert_ column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByUpdate(string $event_update_) Return the first ChildEvent filtered by the event_update_ column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByCreatedAt(string $event_created) Return the first ChildEvent filtered by the event_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByUpdatedAt(string $event_updated) Return the first ChildEvent filtered by the event_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEvent[]|Collection find(?ConnectionInterface $con = null) Return ChildEvent objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildEvent> find(?ConnectionInterface $con = null) Return ChildEvent objects based on current ModelCriteria
 *
 * @method     ChildEvent[]|Collection findById(int|array<int> $event_id) Return ChildEvent objects filtered by the event_id column
 * @psalm-method Collection&\Traversable<ChildEvent> findById(int|array<int> $event_id) Return ChildEvent objects filtered by the event_id column
 * @method     ChildEvent[]|Collection findBySiteId(int|array<int> $site_id) Return ChildEvent objects filtered by the site_id column
 * @psalm-method Collection&\Traversable<ChildEvent> findBySiteId(int|array<int> $site_id) Return ChildEvent objects filtered by the site_id column
 * @method     ChildEvent[]|Collection findByPublisherId(int|array<int> $publisher_id) Return ChildEvent objects filtered by the publisher_id column
 * @psalm-method Collection&\Traversable<ChildEvent> findByPublisherId(int|array<int> $publisher_id) Return ChildEvent objects filtered by the publisher_id column
 * @method     ChildEvent[]|Collection findByBookshopId(int|array<int> $bookshop_id) Return ChildEvent objects filtered by the bookshop_id column
 * @psalm-method Collection&\Traversable<ChildEvent> findByBookshopId(int|array<int> $bookshop_id) Return ChildEvent objects filtered by the bookshop_id column
 * @method     ChildEvent[]|Collection findByLibraryId(int|array<int> $library_id) Return ChildEvent objects filtered by the library_id column
 * @psalm-method Collection&\Traversable<ChildEvent> findByLibraryId(int|array<int> $library_id) Return ChildEvent objects filtered by the library_id column
 * @method     ChildEvent[]|Collection findByUrl(string|array<string> $event_url) Return ChildEvent objects filtered by the event_url column
 * @psalm-method Collection&\Traversable<ChildEvent> findByUrl(string|array<string> $event_url) Return ChildEvent objects filtered by the event_url column
 * @method     ChildEvent[]|Collection findByTitle(string|array<string> $event_title) Return ChildEvent objects filtered by the event_title column
 * @psalm-method Collection&\Traversable<ChildEvent> findByTitle(string|array<string> $event_title) Return ChildEvent objects filtered by the event_title column
 * @method     ChildEvent[]|Collection findBySubtitle(string|array<string> $event_subtitle) Return ChildEvent objects filtered by the event_subtitle column
 * @psalm-method Collection&\Traversable<ChildEvent> findBySubtitle(string|array<string> $event_subtitle) Return ChildEvent objects filtered by the event_subtitle column
 * @method     ChildEvent[]|Collection findByDesc(string|array<string> $event_desc) Return ChildEvent objects filtered by the event_desc column
 * @psalm-method Collection&\Traversable<ChildEvent> findByDesc(string|array<string> $event_desc) Return ChildEvent objects filtered by the event_desc column
 * @method     ChildEvent[]|Collection findByLocation(string|array<string> $event_location) Return ChildEvent objects filtered by the event_location column
 * @psalm-method Collection&\Traversable<ChildEvent> findByLocation(string|array<string> $event_location) Return ChildEvent objects filtered by the event_location column
 * @method     ChildEvent[]|Collection findByIllustrationLegend(string|array<string> $event_illustration_legend) Return ChildEvent objects filtered by the event_illustration_legend column
 * @psalm-method Collection&\Traversable<ChildEvent> findByIllustrationLegend(string|array<string> $event_illustration_legend) Return ChildEvent objects filtered by the event_illustration_legend column
 * @method     ChildEvent[]|Collection findByHighlighted(boolean|array<boolean> $event_highlighted) Return ChildEvent objects filtered by the event_highlighted column
 * @psalm-method Collection&\Traversable<ChildEvent> findByHighlighted(boolean|array<boolean> $event_highlighted) Return ChildEvent objects filtered by the event_highlighted column
 * @method     ChildEvent[]|Collection findByStart(string|array<string> $event_start) Return ChildEvent objects filtered by the event_start column
 * @psalm-method Collection&\Traversable<ChildEvent> findByStart(string|array<string> $event_start) Return ChildEvent objects filtered by the event_start column
 * @method     ChildEvent[]|Collection findByEnd(string|array<string> $event_end) Return ChildEvent objects filtered by the event_end column
 * @psalm-method Collection&\Traversable<ChildEvent> findByEnd(string|array<string> $event_end) Return ChildEvent objects filtered by the event_end column
 * @method     ChildEvent[]|Collection findByDate(string|array<string> $event_date) Return ChildEvent objects filtered by the event_date column
 * @psalm-method Collection&\Traversable<ChildEvent> findByDate(string|array<string> $event_date) Return ChildEvent objects filtered by the event_date column
 * @method     ChildEvent[]|Collection findByStatus(boolean|array<boolean> $event_status) Return ChildEvent objects filtered by the event_status column
 * @psalm-method Collection&\Traversable<ChildEvent> findByStatus(boolean|array<boolean> $event_status) Return ChildEvent objects filtered by the event_status column
 * @method     ChildEvent[]|Collection findByInsert(string|array<string> $event_insert_) Return ChildEvent objects filtered by the event_insert_ column
 * @psalm-method Collection&\Traversable<ChildEvent> findByInsert(string|array<string> $event_insert_) Return ChildEvent objects filtered by the event_insert_ column
 * @method     ChildEvent[]|Collection findByUpdate(string|array<string> $event_update_) Return ChildEvent objects filtered by the event_update_ column
 * @psalm-method Collection&\Traversable<ChildEvent> findByUpdate(string|array<string> $event_update_) Return ChildEvent objects filtered by the event_update_ column
 * @method     ChildEvent[]|Collection findByCreatedAt(string|array<string> $event_created) Return ChildEvent objects filtered by the event_created column
 * @psalm-method Collection&\Traversable<ChildEvent> findByCreatedAt(string|array<string> $event_created) Return ChildEvent objects filtered by the event_created column
 * @method     ChildEvent[]|Collection findByUpdatedAt(string|array<string> $event_updated) Return ChildEvent objects filtered by the event_updated column
 * @psalm-method Collection&\Traversable<ChildEvent> findByUpdatedAt(string|array<string> $event_updated) Return ChildEvent objects filtered by the event_updated column
 *
 * @method     ChildEvent[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildEvent> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class EventQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\EventQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Event', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildEventQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildEventQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildEventQuery) {
            return $criteria;
        }
        $query = new ChildEventQuery();
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
     * @return ChildEvent|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(EventTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = EventTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildEvent A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT event_id, site_id, publisher_id, bookshop_id, library_id, event_url, event_title, event_subtitle, event_desc, event_location, event_illustration_legend, event_highlighted, event_start, event_end, event_date, event_status, event_insert_, event_update_, event_created, event_updated FROM events WHERE event_id = :p0';
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
            /** @var ChildEvent $obj */
            $obj = new ChildEvent();
            $obj->hydrate($row);
            EventTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildEvent|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(EventTableMap::COL_EVENT_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(EventTableMap::COL_EVENT_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the event_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE event_id = 1234
     * $query->filterById(array(12, 34)); // WHERE event_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE event_id > 12
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
                $this->addUsingAlias(EventTableMap::COL_EVENT_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(EventTableMap::COL_EVENT_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_EVENT_ID, $id, $comparison);

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
                $this->addUsingAlias(EventTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(EventTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_SITE_ID, $siteId, $comparison);

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
                $this->addUsingAlias(EventTableMap::COL_PUBLISHER_ID, $publisherId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publisherId['max'])) {
                $this->addUsingAlias(EventTableMap::COL_PUBLISHER_ID, $publisherId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_PUBLISHER_ID, $publisherId, $comparison);

        return $this;
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
     * @param mixed $bookshopId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByBookshopId($bookshopId = null, ?string $comparison = null)
    {
        if (is_array($bookshopId)) {
            $useMinMax = false;
            if (isset($bookshopId['min'])) {
                $this->addUsingAlias(EventTableMap::COL_BOOKSHOP_ID, $bookshopId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bookshopId['max'])) {
                $this->addUsingAlias(EventTableMap::COL_BOOKSHOP_ID, $bookshopId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_BOOKSHOP_ID, $bookshopId, $comparison);

        return $this;
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
     * @param mixed $libraryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByLibraryId($libraryId = null, ?string $comparison = null)
    {
        if (is_array($libraryId)) {
            $useMinMax = false;
            if (isset($libraryId['min'])) {
                $this->addUsingAlias(EventTableMap::COL_LIBRARY_ID, $libraryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($libraryId['max'])) {
                $this->addUsingAlias(EventTableMap::COL_LIBRARY_ID, $libraryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_LIBRARY_ID, $libraryId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the event_url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE event_url = 'fooValue'
     * $query->filterByUrl('%fooValue%', Criteria::LIKE); // WHERE event_url LIKE '%fooValue%'
     * $query->filterByUrl(['foo', 'bar']); // WHERE event_url IN ('foo', 'bar')
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

        $this->addUsingAlias(EventTableMap::COL_EVENT_URL, $url, $comparison);

        return $this;
    }

    /**
     * Filter the query on the event_title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE event_title = 'fooValue'
     * $query->filterByTitle('%fooValue%', Criteria::LIKE); // WHERE event_title LIKE '%fooValue%'
     * $query->filterByTitle(['foo', 'bar']); // WHERE event_title IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $title The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTitle($title = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_EVENT_TITLE, $title, $comparison);

        return $this;
    }

    /**
     * Filter the query on the event_subtitle column
     *
     * Example usage:
     * <code>
     * $query->filterBySubtitle('fooValue');   // WHERE event_subtitle = 'fooValue'
     * $query->filterBySubtitle('%fooValue%', Criteria::LIKE); // WHERE event_subtitle LIKE '%fooValue%'
     * $query->filterBySubtitle(['foo', 'bar']); // WHERE event_subtitle IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $subtitle The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySubtitle($subtitle = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($subtitle)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_EVENT_SUBTITLE, $subtitle, $comparison);

        return $this;
    }

    /**
     * Filter the query on the event_desc column
     *
     * Example usage:
     * <code>
     * $query->filterByDesc('fooValue');   // WHERE event_desc = 'fooValue'
     * $query->filterByDesc('%fooValue%', Criteria::LIKE); // WHERE event_desc LIKE '%fooValue%'
     * $query->filterByDesc(['foo', 'bar']); // WHERE event_desc IN ('foo', 'bar')
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

        $this->addUsingAlias(EventTableMap::COL_EVENT_DESC, $desc, $comparison);

        return $this;
    }

    /**
     * Filter the query on the event_location column
     *
     * Example usage:
     * <code>
     * $query->filterByLocation('fooValue');   // WHERE event_location = 'fooValue'
     * $query->filterByLocation('%fooValue%', Criteria::LIKE); // WHERE event_location LIKE '%fooValue%'
     * $query->filterByLocation(['foo', 'bar']); // WHERE event_location IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $location The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByLocation($location = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($location)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_EVENT_LOCATION, $location, $comparison);

        return $this;
    }

    /**
     * Filter the query on the event_illustration_legend column
     *
     * Example usage:
     * <code>
     * $query->filterByIllustrationLegend('fooValue');   // WHERE event_illustration_legend = 'fooValue'
     * $query->filterByIllustrationLegend('%fooValue%', Criteria::LIKE); // WHERE event_illustration_legend LIKE '%fooValue%'
     * $query->filterByIllustrationLegend(['foo', 'bar']); // WHERE event_illustration_legend IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $illustrationLegend The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByIllustrationLegend($illustrationLegend = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($illustrationLegend)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_EVENT_ILLUSTRATION_LEGEND, $illustrationLegend, $comparison);

        return $this;
    }

    /**
     * Filter the query on the event_highlighted column
     *
     * Example usage:
     * <code>
     * $query->filterByHighlighted(true); // WHERE event_highlighted = true
     * $query->filterByHighlighted('yes'); // WHERE event_highlighted = true
     * </code>
     *
     * @param bool|string $highlighted The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByHighlighted($highlighted = null, ?string $comparison = null)
    {
        if (is_string($highlighted)) {
            $highlighted = in_array(strtolower($highlighted), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(EventTableMap::COL_EVENT_HIGHLIGHTED, $highlighted, $comparison);

        return $this;
    }

    /**
     * Filter the query on the event_start column
     *
     * Example usage:
     * <code>
     * $query->filterByStart('2011-03-14'); // WHERE event_start = '2011-03-14'
     * $query->filterByStart('now'); // WHERE event_start = '2011-03-14'
     * $query->filterByStart(array('max' => 'yesterday')); // WHERE event_start > '2011-03-13'
     * </code>
     *
     * @param mixed $start The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByStart($start = null, ?string $comparison = null)
    {
        if (is_array($start)) {
            $useMinMax = false;
            if (isset($start['min'])) {
                $this->addUsingAlias(EventTableMap::COL_EVENT_START, $start['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($start['max'])) {
                $this->addUsingAlias(EventTableMap::COL_EVENT_START, $start['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_EVENT_START, $start, $comparison);

        return $this;
    }

    /**
     * Filter the query on the event_end column
     *
     * Example usage:
     * <code>
     * $query->filterByEnd('2011-03-14'); // WHERE event_end = '2011-03-14'
     * $query->filterByEnd('now'); // WHERE event_end = '2011-03-14'
     * $query->filterByEnd(array('max' => 'yesterday')); // WHERE event_end > '2011-03-13'
     * </code>
     *
     * @param mixed $end The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEnd($end = null, ?string $comparison = null)
    {
        if (is_array($end)) {
            $useMinMax = false;
            if (isset($end['min'])) {
                $this->addUsingAlias(EventTableMap::COL_EVENT_END, $end['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($end['max'])) {
                $this->addUsingAlias(EventTableMap::COL_EVENT_END, $end['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_EVENT_END, $end, $comparison);

        return $this;
    }

    /**
     * Filter the query on the event_date column
     *
     * Example usage:
     * <code>
     * $query->filterByDate('2011-03-14'); // WHERE event_date = '2011-03-14'
     * $query->filterByDate('now'); // WHERE event_date = '2011-03-14'
     * $query->filterByDate(array('max' => 'yesterday')); // WHERE event_date > '2011-03-13'
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
                $this->addUsingAlias(EventTableMap::COL_EVENT_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(EventTableMap::COL_EVENT_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_EVENT_DATE, $date, $comparison);

        return $this;
    }

    /**
     * Filter the query on the event_status column
     *
     * Example usage:
     * <code>
     * $query->filterByStatus(true); // WHERE event_status = true
     * $query->filterByStatus('yes'); // WHERE event_status = true
     * </code>
     *
     * @param bool|string $status The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByStatus($status = null, ?string $comparison = null)
    {
        if (is_string($status)) {
            $status = in_array(strtolower($status), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(EventTableMap::COL_EVENT_STATUS, $status, $comparison);

        return $this;
    }

    /**
     * Filter the query on the event_insert_ column
     *
     * Example usage:
     * <code>
     * $query->filterByInsert('2011-03-14'); // WHERE event_insert_ = '2011-03-14'
     * $query->filterByInsert('now'); // WHERE event_insert_ = '2011-03-14'
     * $query->filterByInsert(array('max' => 'yesterday')); // WHERE event_insert_ > '2011-03-13'
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
                $this->addUsingAlias(EventTableMap::COL_EVENT_INSERT_, $insert['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($insert['max'])) {
                $this->addUsingAlias(EventTableMap::COL_EVENT_INSERT_, $insert['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_EVENT_INSERT_, $insert, $comparison);

        return $this;
    }

    /**
     * Filter the query on the event_update_ column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdate('2011-03-14'); // WHERE event_update_ = '2011-03-14'
     * $query->filterByUpdate('now'); // WHERE event_update_ = '2011-03-14'
     * $query->filterByUpdate(array('max' => 'yesterday')); // WHERE event_update_ > '2011-03-13'
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
                $this->addUsingAlias(EventTableMap::COL_EVENT_UPDATE_, $update['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($update['max'])) {
                $this->addUsingAlias(EventTableMap::COL_EVENT_UPDATE_, $update['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_EVENT_UPDATE_, $update, $comparison);

        return $this;
    }

    /**
     * Filter the query on the event_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE event_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE event_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE event_created > '2011-03-13'
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
                $this->addUsingAlias(EventTableMap::COL_EVENT_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(EventTableMap::COL_EVENT_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_EVENT_CREATED, $createdAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the event_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE event_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE event_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE event_updated > '2011-03-13'
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
                $this->addUsingAlias(EventTableMap::COL_EVENT_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(EventTableMap::COL_EVENT_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(EventTableMap::COL_EVENT_UPDATED, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Exclude object from result
     *
     * @param ChildEvent $event Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($event = null)
    {
        if ($event) {
            $this->addUsingAlias(EventTableMap::COL_EVENT_ID, $event->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the events table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            EventTableMap::clearInstancePool();
            EventTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(EventTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            EventTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            EventTableMap::clearRelatedInstancePool();

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
        $this->addUsingAlias(EventTableMap::COL_EVENT_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(EventTableMap::COL_EVENT_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(EventTableMap::COL_EVENT_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(EventTableMap::COL_EVENT_CREATED);

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
        $this->addUsingAlias(EventTableMap::COL_EVENT_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(EventTableMap::COL_EVENT_CREATED);

        return $this;
    }

}
