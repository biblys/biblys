<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Ticket as ChildTicket;
use Model\TicketQuery as ChildTicketQuery;
use Model\Map\TicketTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'ticket' table.
 *
 *
 *
 * @method     ChildTicketQuery orderById($order = Criteria::ASC) Order by the ticket_id column
 * @method     ChildTicketQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildTicketQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildTicketQuery orderByType($order = Criteria::ASC) Order by the ticket_type column
 * @method     ChildTicketQuery orderByTitle($order = Criteria::ASC) Order by the ticket_title column
 * @method     ChildTicketQuery orderByContent($order = Criteria::ASC) Order by the ticket_content column
 * @method     ChildTicketQuery orderByPriority($order = Criteria::ASC) Order by the ticket_priority column
 * @method     ChildTicketQuery orderByCreatedAt($order = Criteria::ASC) Order by the ticket_created column
 * @method     ChildTicketQuery orderByUpdatedAt($order = Criteria::ASC) Order by the ticket_updated column
 * @method     ChildTicketQuery orderByResolved($order = Criteria::ASC) Order by the ticket_resolved column
 * @method     ChildTicketQuery orderByClosed($order = Criteria::ASC) Order by the ticket_closed column
 * @method     ChildTicketQuery orderByDeletedAt($order = Criteria::ASC) Order by the ticket_deleted column
 *
 * @method     ChildTicketQuery groupById() Group by the ticket_id column
 * @method     ChildTicketQuery groupByUserId() Group by the user_id column
 * @method     ChildTicketQuery groupBySiteId() Group by the site_id column
 * @method     ChildTicketQuery groupByType() Group by the ticket_type column
 * @method     ChildTicketQuery groupByTitle() Group by the ticket_title column
 * @method     ChildTicketQuery groupByContent() Group by the ticket_content column
 * @method     ChildTicketQuery groupByPriority() Group by the ticket_priority column
 * @method     ChildTicketQuery groupByCreatedAt() Group by the ticket_created column
 * @method     ChildTicketQuery groupByUpdatedAt() Group by the ticket_updated column
 * @method     ChildTicketQuery groupByResolved() Group by the ticket_resolved column
 * @method     ChildTicketQuery groupByClosed() Group by the ticket_closed column
 * @method     ChildTicketQuery groupByDeletedAt() Group by the ticket_deleted column
 *
 * @method     ChildTicketQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildTicketQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildTicketQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildTicketQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildTicketQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildTicketQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildTicket|null findOne(ConnectionInterface $con = null) Return the first ChildTicket matching the query
 * @method     ChildTicket findOneOrCreate(ConnectionInterface $con = null) Return the first ChildTicket matching the query, or a new ChildTicket object populated from the query conditions when no match is found
 *
 * @method     ChildTicket|null findOneById(int $ticket_id) Return the first ChildTicket filtered by the ticket_id column
 * @method     ChildTicket|null findOneByUserId(int $user_id) Return the first ChildTicket filtered by the user_id column
 * @method     ChildTicket|null findOneBySiteId(int $site_id) Return the first ChildTicket filtered by the site_id column
 * @method     ChildTicket|null findOneByType(string $ticket_type) Return the first ChildTicket filtered by the ticket_type column
 * @method     ChildTicket|null findOneByTitle(string $ticket_title) Return the first ChildTicket filtered by the ticket_title column
 * @method     ChildTicket|null findOneByContent(string $ticket_content) Return the first ChildTicket filtered by the ticket_content column
 * @method     ChildTicket|null findOneByPriority(int $ticket_priority) Return the first ChildTicket filtered by the ticket_priority column
 * @method     ChildTicket|null findOneByCreatedAt(string $ticket_created) Return the first ChildTicket filtered by the ticket_created column
 * @method     ChildTicket|null findOneByUpdatedAt(string $ticket_updated) Return the first ChildTicket filtered by the ticket_updated column
 * @method     ChildTicket|null findOneByResolved(string $ticket_resolved) Return the first ChildTicket filtered by the ticket_resolved column
 * @method     ChildTicket|null findOneByClosed(string $ticket_closed) Return the first ChildTicket filtered by the ticket_closed column
 * @method     ChildTicket|null findOneByDeletedAt(string $ticket_deleted) Return the first ChildTicket filtered by the ticket_deleted column *

 * @method     ChildTicket requirePk($key, ConnectionInterface $con = null) Return the ChildTicket by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTicket requireOne(ConnectionInterface $con = null) Return the first ChildTicket matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildTicket requireOneById(int $ticket_id) Return the first ChildTicket filtered by the ticket_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTicket requireOneByUserId(int $user_id) Return the first ChildTicket filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTicket requireOneBySiteId(int $site_id) Return the first ChildTicket filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTicket requireOneByType(string $ticket_type) Return the first ChildTicket filtered by the ticket_type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTicket requireOneByTitle(string $ticket_title) Return the first ChildTicket filtered by the ticket_title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTicket requireOneByContent(string $ticket_content) Return the first ChildTicket filtered by the ticket_content column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTicket requireOneByPriority(int $ticket_priority) Return the first ChildTicket filtered by the ticket_priority column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTicket requireOneByCreatedAt(string $ticket_created) Return the first ChildTicket filtered by the ticket_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTicket requireOneByUpdatedAt(string $ticket_updated) Return the first ChildTicket filtered by the ticket_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTicket requireOneByResolved(string $ticket_resolved) Return the first ChildTicket filtered by the ticket_resolved column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTicket requireOneByClosed(string $ticket_closed) Return the first ChildTicket filtered by the ticket_closed column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTicket requireOneByDeletedAt(string $ticket_deleted) Return the first ChildTicket filtered by the ticket_deleted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildTicket[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildTicket objects based on current ModelCriteria
 * @method     ChildTicket[]|ObjectCollection findById(int $ticket_id) Return ChildTicket objects filtered by the ticket_id column
 * @method     ChildTicket[]|ObjectCollection findByUserId(int $user_id) Return ChildTicket objects filtered by the user_id column
 * @method     ChildTicket[]|ObjectCollection findBySiteId(int $site_id) Return ChildTicket objects filtered by the site_id column
 * @method     ChildTicket[]|ObjectCollection findByType(string $ticket_type) Return ChildTicket objects filtered by the ticket_type column
 * @method     ChildTicket[]|ObjectCollection findByTitle(string $ticket_title) Return ChildTicket objects filtered by the ticket_title column
 * @method     ChildTicket[]|ObjectCollection findByContent(string $ticket_content) Return ChildTicket objects filtered by the ticket_content column
 * @method     ChildTicket[]|ObjectCollection findByPriority(int $ticket_priority) Return ChildTicket objects filtered by the ticket_priority column
 * @method     ChildTicket[]|ObjectCollection findByCreatedAt(string $ticket_created) Return ChildTicket objects filtered by the ticket_created column
 * @method     ChildTicket[]|ObjectCollection findByUpdatedAt(string $ticket_updated) Return ChildTicket objects filtered by the ticket_updated column
 * @method     ChildTicket[]|ObjectCollection findByResolved(string $ticket_resolved) Return ChildTicket objects filtered by the ticket_resolved column
 * @method     ChildTicket[]|ObjectCollection findByClosed(string $ticket_closed) Return ChildTicket objects filtered by the ticket_closed column
 * @method     ChildTicket[]|ObjectCollection findByDeletedAt(string $ticket_deleted) Return ChildTicket objects filtered by the ticket_deleted column
 * @method     ChildTicket[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class TicketQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\TicketQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Ticket', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildTicketQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildTicketQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildTicketQuery) {
            return $criteria;
        }
        $query = new ChildTicketQuery();
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
     * @return ChildTicket|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(TicketTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = TicketTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildTicket A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT ticket_id, user_id, site_id, ticket_type, ticket_title, ticket_content, ticket_priority, ticket_created, ticket_updated, ticket_resolved, ticket_closed, ticket_deleted FROM ticket WHERE ticket_id = :p0';
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
            /** @var ChildTicket $obj */
            $obj = new ChildTicket();
            $obj->hydrate($row);
            TicketTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildTicket|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildTicketQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(TicketTableMap::COL_TICKET_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildTicketQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(TicketTableMap::COL_TICKET_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the ticket_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE ticket_id = 1234
     * $query->filterById(array(12, 34)); // WHERE ticket_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE ticket_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTicketQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(TicketTableMap::COL_TICKET_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(TicketTableMap::COL_TICKET_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TicketTableMap::COL_TICKET_ID, $id, $comparison);
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE user_id = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE user_id > 12
     * </code>
     *
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTicketQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(TicketTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(TicketTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TicketTableMap::COL_USER_ID, $userId, $comparison);
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
     * @return $this|ChildTicketQuery The current query, for fluid interface
     */
    public function filterBySiteId($siteId = null, $comparison = null)
    {
        if (is_array($siteId)) {
            $useMinMax = false;
            if (isset($siteId['min'])) {
                $this->addUsingAlias(TicketTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(TicketTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TicketTableMap::COL_SITE_ID, $siteId, $comparison);
    }

    /**
     * Filter the query on the ticket_type column
     *
     * Example usage:
     * <code>
     * $query->filterByType('fooValue');   // WHERE ticket_type = 'fooValue'
     * $query->filterByType('%fooValue%', Criteria::LIKE); // WHERE ticket_type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $type The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTicketQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($type)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TicketTableMap::COL_TICKET_TYPE, $type, $comparison);
    }

    /**
     * Filter the query on the ticket_title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE ticket_title = 'fooValue'
     * $query->filterByTitle('%fooValue%', Criteria::LIKE); // WHERE ticket_title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTicketQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TicketTableMap::COL_TICKET_TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the ticket_content column
     *
     * Example usage:
     * <code>
     * $query->filterByContent('fooValue');   // WHERE ticket_content = 'fooValue'
     * $query->filterByContent('%fooValue%', Criteria::LIKE); // WHERE ticket_content LIKE '%fooValue%'
     * </code>
     *
     * @param     string $content The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTicketQuery The current query, for fluid interface
     */
    public function filterByContent($content = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($content)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TicketTableMap::COL_TICKET_CONTENT, $content, $comparison);
    }

    /**
     * Filter the query on the ticket_priority column
     *
     * Example usage:
     * <code>
     * $query->filterByPriority(1234); // WHERE ticket_priority = 1234
     * $query->filterByPriority(array(12, 34)); // WHERE ticket_priority IN (12, 34)
     * $query->filterByPriority(array('min' => 12)); // WHERE ticket_priority > 12
     * </code>
     *
     * @param     mixed $priority The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTicketQuery The current query, for fluid interface
     */
    public function filterByPriority($priority = null, $comparison = null)
    {
        if (is_array($priority)) {
            $useMinMax = false;
            if (isset($priority['min'])) {
                $this->addUsingAlias(TicketTableMap::COL_TICKET_PRIORITY, $priority['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($priority['max'])) {
                $this->addUsingAlias(TicketTableMap::COL_TICKET_PRIORITY, $priority['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TicketTableMap::COL_TICKET_PRIORITY, $priority, $comparison);
    }

    /**
     * Filter the query on the ticket_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE ticket_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE ticket_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE ticket_created > '2011-03-13'
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
     * @return $this|ChildTicketQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(TicketTableMap::COL_TICKET_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(TicketTableMap::COL_TICKET_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TicketTableMap::COL_TICKET_CREATED, $createdAt, $comparison);
    }

    /**
     * Filter the query on the ticket_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE ticket_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE ticket_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE ticket_updated > '2011-03-13'
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
     * @return $this|ChildTicketQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(TicketTableMap::COL_TICKET_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(TicketTableMap::COL_TICKET_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TicketTableMap::COL_TICKET_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the ticket_resolved column
     *
     * Example usage:
     * <code>
     * $query->filterByResolved('2011-03-14'); // WHERE ticket_resolved = '2011-03-14'
     * $query->filterByResolved('now'); // WHERE ticket_resolved = '2011-03-14'
     * $query->filterByResolved(array('max' => 'yesterday')); // WHERE ticket_resolved > '2011-03-13'
     * </code>
     *
     * @param     mixed $resolved The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTicketQuery The current query, for fluid interface
     */
    public function filterByResolved($resolved = null, $comparison = null)
    {
        if (is_array($resolved)) {
            $useMinMax = false;
            if (isset($resolved['min'])) {
                $this->addUsingAlias(TicketTableMap::COL_TICKET_RESOLVED, $resolved['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($resolved['max'])) {
                $this->addUsingAlias(TicketTableMap::COL_TICKET_RESOLVED, $resolved['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TicketTableMap::COL_TICKET_RESOLVED, $resolved, $comparison);
    }

    /**
     * Filter the query on the ticket_closed column
     *
     * Example usage:
     * <code>
     * $query->filterByClosed('2011-03-14'); // WHERE ticket_closed = '2011-03-14'
     * $query->filterByClosed('now'); // WHERE ticket_closed = '2011-03-14'
     * $query->filterByClosed(array('max' => 'yesterday')); // WHERE ticket_closed > '2011-03-13'
     * </code>
     *
     * @param     mixed $closed The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTicketQuery The current query, for fluid interface
     */
    public function filterByClosed($closed = null, $comparison = null)
    {
        if (is_array($closed)) {
            $useMinMax = false;
            if (isset($closed['min'])) {
                $this->addUsingAlias(TicketTableMap::COL_TICKET_CLOSED, $closed['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($closed['max'])) {
                $this->addUsingAlias(TicketTableMap::COL_TICKET_CLOSED, $closed['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TicketTableMap::COL_TICKET_CLOSED, $closed, $comparison);
    }

    /**
     * Filter the query on the ticket_deleted column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletedAt('2011-03-14'); // WHERE ticket_deleted = '2011-03-14'
     * $query->filterByDeletedAt('now'); // WHERE ticket_deleted = '2011-03-14'
     * $query->filterByDeletedAt(array('max' => 'yesterday')); // WHERE ticket_deleted > '2011-03-13'
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
     * @return $this|ChildTicketQuery The current query, for fluid interface
     */
    public function filterByDeletedAt($deletedAt = null, $comparison = null)
    {
        if (is_array($deletedAt)) {
            $useMinMax = false;
            if (isset($deletedAt['min'])) {
                $this->addUsingAlias(TicketTableMap::COL_TICKET_DELETED, $deletedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletedAt['max'])) {
                $this->addUsingAlias(TicketTableMap::COL_TICKET_DELETED, $deletedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TicketTableMap::COL_TICKET_DELETED, $deletedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildTicket $ticket Object to remove from the list of results
     *
     * @return $this|ChildTicketQuery The current query, for fluid interface
     */
    public function prune($ticket = null)
    {
        if ($ticket) {
            $this->addUsingAlias(TicketTableMap::COL_TICKET_ID, $ticket->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the ticket table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(TicketTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            TicketTableMap::clearInstancePool();
            TicketTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(TicketTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(TicketTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            TicketTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            TicketTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // TicketQuery
