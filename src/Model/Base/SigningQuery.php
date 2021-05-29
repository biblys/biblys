<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Signing as ChildSigning;
use Model\SigningQuery as ChildSigningQuery;
use Model\Map\SigningTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'signings' table.
 *
 *
 *
 * @method     ChildSigningQuery orderById($order = Criteria::ASC) Order by the signing_id column
 * @method     ChildSigningQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildSigningQuery orderByPublisherId($order = Criteria::ASC) Order by the publisher_id column
 * @method     ChildSigningQuery orderByPeopleId($order = Criteria::ASC) Order by the people_id column
 * @method     ChildSigningQuery orderByDate($order = Criteria::ASC) Order by the signing_date column
 * @method     ChildSigningQuery orderByStarts($order = Criteria::ASC) Order by the signing_starts column
 * @method     ChildSigningQuery orderByEnds($order = Criteria::ASC) Order by the signing_ends column
 * @method     ChildSigningQuery orderByLocation($order = Criteria::ASC) Order by the signing_location column
 * @method     ChildSigningQuery orderByCreatedAt($order = Criteria::ASC) Order by the signing_created column
 * @method     ChildSigningQuery orderByUpdatedAt($order = Criteria::ASC) Order by the signing_updated column
 * @method     ChildSigningQuery orderByDeletedAt($order = Criteria::ASC) Order by the signing_deleted column
 *
 * @method     ChildSigningQuery groupById() Group by the signing_id column
 * @method     ChildSigningQuery groupBySiteId() Group by the site_id column
 * @method     ChildSigningQuery groupByPublisherId() Group by the publisher_id column
 * @method     ChildSigningQuery groupByPeopleId() Group by the people_id column
 * @method     ChildSigningQuery groupByDate() Group by the signing_date column
 * @method     ChildSigningQuery groupByStarts() Group by the signing_starts column
 * @method     ChildSigningQuery groupByEnds() Group by the signing_ends column
 * @method     ChildSigningQuery groupByLocation() Group by the signing_location column
 * @method     ChildSigningQuery groupByCreatedAt() Group by the signing_created column
 * @method     ChildSigningQuery groupByUpdatedAt() Group by the signing_updated column
 * @method     ChildSigningQuery groupByDeletedAt() Group by the signing_deleted column
 *
 * @method     ChildSigningQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSigningQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSigningQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSigningQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildSigningQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildSigningQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildSigning|null findOne(ConnectionInterface $con = null) Return the first ChildSigning matching the query
 * @method     ChildSigning findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSigning matching the query, or a new ChildSigning object populated from the query conditions when no match is found
 *
 * @method     ChildSigning|null findOneById(int $signing_id) Return the first ChildSigning filtered by the signing_id column
 * @method     ChildSigning|null findOneBySiteId(int $site_id) Return the first ChildSigning filtered by the site_id column
 * @method     ChildSigning|null findOneByPublisherId(int $publisher_id) Return the first ChildSigning filtered by the publisher_id column
 * @method     ChildSigning|null findOneByPeopleId(int $people_id) Return the first ChildSigning filtered by the people_id column
 * @method     ChildSigning|null findOneByDate(string $signing_date) Return the first ChildSigning filtered by the signing_date column
 * @method     ChildSigning|null findOneByStarts(string $signing_starts) Return the first ChildSigning filtered by the signing_starts column
 * @method     ChildSigning|null findOneByEnds(string $signing_ends) Return the first ChildSigning filtered by the signing_ends column
 * @method     ChildSigning|null findOneByLocation(string $signing_location) Return the first ChildSigning filtered by the signing_location column
 * @method     ChildSigning|null findOneByCreatedAt(string $signing_created) Return the first ChildSigning filtered by the signing_created column
 * @method     ChildSigning|null findOneByUpdatedAt(string $signing_updated) Return the first ChildSigning filtered by the signing_updated column
 * @method     ChildSigning|null findOneByDeletedAt(string $signing_deleted) Return the first ChildSigning filtered by the signing_deleted column *

 * @method     ChildSigning requirePk($key, ConnectionInterface $con = null) Return the ChildSigning by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSigning requireOne(ConnectionInterface $con = null) Return the first ChildSigning matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSigning requireOneById(int $signing_id) Return the first ChildSigning filtered by the signing_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSigning requireOneBySiteId(int $site_id) Return the first ChildSigning filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSigning requireOneByPublisherId(int $publisher_id) Return the first ChildSigning filtered by the publisher_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSigning requireOneByPeopleId(int $people_id) Return the first ChildSigning filtered by the people_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSigning requireOneByDate(string $signing_date) Return the first ChildSigning filtered by the signing_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSigning requireOneByStarts(string $signing_starts) Return the first ChildSigning filtered by the signing_starts column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSigning requireOneByEnds(string $signing_ends) Return the first ChildSigning filtered by the signing_ends column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSigning requireOneByLocation(string $signing_location) Return the first ChildSigning filtered by the signing_location column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSigning requireOneByCreatedAt(string $signing_created) Return the first ChildSigning filtered by the signing_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSigning requireOneByUpdatedAt(string $signing_updated) Return the first ChildSigning filtered by the signing_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSigning requireOneByDeletedAt(string $signing_deleted) Return the first ChildSigning filtered by the signing_deleted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSigning[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSigning objects based on current ModelCriteria
 * @method     ChildSigning[]|ObjectCollection findById(int $signing_id) Return ChildSigning objects filtered by the signing_id column
 * @method     ChildSigning[]|ObjectCollection findBySiteId(int $site_id) Return ChildSigning objects filtered by the site_id column
 * @method     ChildSigning[]|ObjectCollection findByPublisherId(int $publisher_id) Return ChildSigning objects filtered by the publisher_id column
 * @method     ChildSigning[]|ObjectCollection findByPeopleId(int $people_id) Return ChildSigning objects filtered by the people_id column
 * @method     ChildSigning[]|ObjectCollection findByDate(string $signing_date) Return ChildSigning objects filtered by the signing_date column
 * @method     ChildSigning[]|ObjectCollection findByStarts(string $signing_starts) Return ChildSigning objects filtered by the signing_starts column
 * @method     ChildSigning[]|ObjectCollection findByEnds(string $signing_ends) Return ChildSigning objects filtered by the signing_ends column
 * @method     ChildSigning[]|ObjectCollection findByLocation(string $signing_location) Return ChildSigning objects filtered by the signing_location column
 * @method     ChildSigning[]|ObjectCollection findByCreatedAt(string $signing_created) Return ChildSigning objects filtered by the signing_created column
 * @method     ChildSigning[]|ObjectCollection findByUpdatedAt(string $signing_updated) Return ChildSigning objects filtered by the signing_updated column
 * @method     ChildSigning[]|ObjectCollection findByDeletedAt(string $signing_deleted) Return ChildSigning objects filtered by the signing_deleted column
 * @method     ChildSigning[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SigningQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\SigningQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Signing', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSigningQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSigningQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSigningQuery) {
            return $criteria;
        }
        $query = new ChildSigningQuery();
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
     * @return ChildSigning|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SigningTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = SigningTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildSigning A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT signing_id, site_id, publisher_id, people_id, signing_date, signing_starts, signing_ends, signing_location, signing_created, signing_updated, signing_deleted FROM signings WHERE signing_id = :p0';
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
            /** @var ChildSigning $obj */
            $obj = new ChildSigning();
            $obj->hydrate($row);
            SigningTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildSigning|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildSigningQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SigningTableMap::COL_SIGNING_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSigningQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SigningTableMap::COL_SIGNING_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the signing_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE signing_id = 1234
     * $query->filterById(array(12, 34)); // WHERE signing_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE signing_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSigningQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SigningTableMap::COL_SIGNING_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SigningTableMap::COL_SIGNING_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SigningTableMap::COL_SIGNING_ID, $id, $comparison);
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
     * @return $this|ChildSigningQuery The current query, for fluid interface
     */
    public function filterBySiteId($siteId = null, $comparison = null)
    {
        if (is_array($siteId)) {
            $useMinMax = false;
            if (isset($siteId['min'])) {
                $this->addUsingAlias(SigningTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(SigningTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SigningTableMap::COL_SITE_ID, $siteId, $comparison);
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
     * @return $this|ChildSigningQuery The current query, for fluid interface
     */
    public function filterByPublisherId($publisherId = null, $comparison = null)
    {
        if (is_array($publisherId)) {
            $useMinMax = false;
            if (isset($publisherId['min'])) {
                $this->addUsingAlias(SigningTableMap::COL_PUBLISHER_ID, $publisherId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publisherId['max'])) {
                $this->addUsingAlias(SigningTableMap::COL_PUBLISHER_ID, $publisherId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SigningTableMap::COL_PUBLISHER_ID, $publisherId, $comparison);
    }

    /**
     * Filter the query on the people_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPeopleId(1234); // WHERE people_id = 1234
     * $query->filterByPeopleId(array(12, 34)); // WHERE people_id IN (12, 34)
     * $query->filterByPeopleId(array('min' => 12)); // WHERE people_id > 12
     * </code>
     *
     * @param     mixed $peopleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSigningQuery The current query, for fluid interface
     */
    public function filterByPeopleId($peopleId = null, $comparison = null)
    {
        if (is_array($peopleId)) {
            $useMinMax = false;
            if (isset($peopleId['min'])) {
                $this->addUsingAlias(SigningTableMap::COL_PEOPLE_ID, $peopleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($peopleId['max'])) {
                $this->addUsingAlias(SigningTableMap::COL_PEOPLE_ID, $peopleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SigningTableMap::COL_PEOPLE_ID, $peopleId, $comparison);
    }

    /**
     * Filter the query on the signing_date column
     *
     * Example usage:
     * <code>
     * $query->filterByDate('2011-03-14'); // WHERE signing_date = '2011-03-14'
     * $query->filterByDate('now'); // WHERE signing_date = '2011-03-14'
     * $query->filterByDate(array('max' => 'yesterday')); // WHERE signing_date > '2011-03-13'
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
     * @return $this|ChildSigningQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(SigningTableMap::COL_SIGNING_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(SigningTableMap::COL_SIGNING_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SigningTableMap::COL_SIGNING_DATE, $date, $comparison);
    }

    /**
     * Filter the query on the signing_starts column
     *
     * Example usage:
     * <code>
     * $query->filterByStarts('2011-03-14'); // WHERE signing_starts = '2011-03-14'
     * $query->filterByStarts('now'); // WHERE signing_starts = '2011-03-14'
     * $query->filterByStarts(array('max' => 'yesterday')); // WHERE signing_starts > '2011-03-13'
     * </code>
     *
     * @param     mixed $starts The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSigningQuery The current query, for fluid interface
     */
    public function filterByStarts($starts = null, $comparison = null)
    {
        if (is_array($starts)) {
            $useMinMax = false;
            if (isset($starts['min'])) {
                $this->addUsingAlias(SigningTableMap::COL_SIGNING_STARTS, $starts['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($starts['max'])) {
                $this->addUsingAlias(SigningTableMap::COL_SIGNING_STARTS, $starts['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SigningTableMap::COL_SIGNING_STARTS, $starts, $comparison);
    }

    /**
     * Filter the query on the signing_ends column
     *
     * Example usage:
     * <code>
     * $query->filterByEnds('2011-03-14'); // WHERE signing_ends = '2011-03-14'
     * $query->filterByEnds('now'); // WHERE signing_ends = '2011-03-14'
     * $query->filterByEnds(array('max' => 'yesterday')); // WHERE signing_ends > '2011-03-13'
     * </code>
     *
     * @param     mixed $ends The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSigningQuery The current query, for fluid interface
     */
    public function filterByEnds($ends = null, $comparison = null)
    {
        if (is_array($ends)) {
            $useMinMax = false;
            if (isset($ends['min'])) {
                $this->addUsingAlias(SigningTableMap::COL_SIGNING_ENDS, $ends['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ends['max'])) {
                $this->addUsingAlias(SigningTableMap::COL_SIGNING_ENDS, $ends['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SigningTableMap::COL_SIGNING_ENDS, $ends, $comparison);
    }

    /**
     * Filter the query on the signing_location column
     *
     * Example usage:
     * <code>
     * $query->filterByLocation('fooValue');   // WHERE signing_location = 'fooValue'
     * $query->filterByLocation('%fooValue%', Criteria::LIKE); // WHERE signing_location LIKE '%fooValue%'
     * </code>
     *
     * @param     string $location The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSigningQuery The current query, for fluid interface
     */
    public function filterByLocation($location = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($location)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SigningTableMap::COL_SIGNING_LOCATION, $location, $comparison);
    }

    /**
     * Filter the query on the signing_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE signing_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE signing_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE signing_created > '2011-03-13'
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
     * @return $this|ChildSigningQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(SigningTableMap::COL_SIGNING_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(SigningTableMap::COL_SIGNING_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SigningTableMap::COL_SIGNING_CREATED, $createdAt, $comparison);
    }

    /**
     * Filter the query on the signing_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE signing_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE signing_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE signing_updated > '2011-03-13'
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
     * @return $this|ChildSigningQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(SigningTableMap::COL_SIGNING_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(SigningTableMap::COL_SIGNING_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SigningTableMap::COL_SIGNING_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the signing_deleted column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletedAt('2011-03-14'); // WHERE signing_deleted = '2011-03-14'
     * $query->filterByDeletedAt('now'); // WHERE signing_deleted = '2011-03-14'
     * $query->filterByDeletedAt(array('max' => 'yesterday')); // WHERE signing_deleted > '2011-03-13'
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
     * @return $this|ChildSigningQuery The current query, for fluid interface
     */
    public function filterByDeletedAt($deletedAt = null, $comparison = null)
    {
        if (is_array($deletedAt)) {
            $useMinMax = false;
            if (isset($deletedAt['min'])) {
                $this->addUsingAlias(SigningTableMap::COL_SIGNING_DELETED, $deletedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletedAt['max'])) {
                $this->addUsingAlias(SigningTableMap::COL_SIGNING_DELETED, $deletedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SigningTableMap::COL_SIGNING_DELETED, $deletedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSigning $signing Object to remove from the list of results
     *
     * @return $this|ChildSigningQuery The current query, for fluid interface
     */
    public function prune($signing = null)
    {
        if ($signing) {
            $this->addUsingAlias(SigningTableMap::COL_SIGNING_ID, $signing->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the signings table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SigningTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SigningTableMap::clearInstancePool();
            SigningTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(SigningTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SigningTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SigningTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SigningTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // SigningQuery
