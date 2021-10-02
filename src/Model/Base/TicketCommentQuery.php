<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\TicketComment as ChildTicketComment;
use Model\TicketCommentQuery as ChildTicketCommentQuery;
use Model\Map\TicketCommentTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'ticket_comment' table.
 *
 *
 *
 * @method     ChildTicketCommentQuery orderById($order = Criteria::ASC) Order by the ticket_comment_id column
 * @method     ChildTicketCommentQuery orderByTicketId($order = Criteria::ASC) Order by the ticket_id column
 * @method     ChildTicketCommentQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildTicketCommentQuery orderByContent($order = Criteria::ASC) Order by the ticket_comment_content column
 * @method     ChildTicketCommentQuery orderByCreatedAt($order = Criteria::ASC) Order by the ticket_comment_created column
 * @method     ChildTicketCommentQuery orderByUpdate($order = Criteria::ASC) Order by the ticket_comment_update column
 *
 * @method     ChildTicketCommentQuery groupById() Group by the ticket_comment_id column
 * @method     ChildTicketCommentQuery groupByTicketId() Group by the ticket_id column
 * @method     ChildTicketCommentQuery groupByUserId() Group by the user_id column
 * @method     ChildTicketCommentQuery groupByContent() Group by the ticket_comment_content column
 * @method     ChildTicketCommentQuery groupByCreatedAt() Group by the ticket_comment_created column
 * @method     ChildTicketCommentQuery groupByUpdate() Group by the ticket_comment_update column
 *
 * @method     ChildTicketCommentQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildTicketCommentQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildTicketCommentQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildTicketCommentQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildTicketCommentQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildTicketCommentQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildTicketComment|null findOne(ConnectionInterface $con = null) Return the first ChildTicketComment matching the query
 * @method     ChildTicketComment findOneOrCreate(ConnectionInterface $con = null) Return the first ChildTicketComment matching the query, or a new ChildTicketComment object populated from the query conditions when no match is found
 *
 * @method     ChildTicketComment|null findOneById(int $ticket_comment_id) Return the first ChildTicketComment filtered by the ticket_comment_id column
 * @method     ChildTicketComment|null findOneByTicketId(int $ticket_id) Return the first ChildTicketComment filtered by the ticket_id column
 * @method     ChildTicketComment|null findOneByUserId(int $user_id) Return the first ChildTicketComment filtered by the user_id column
 * @method     ChildTicketComment|null findOneByContent(string $ticket_comment_content) Return the first ChildTicketComment filtered by the ticket_comment_content column
 * @method     ChildTicketComment|null findOneByCreatedAt(string $ticket_comment_created) Return the first ChildTicketComment filtered by the ticket_comment_created column
 * @method     ChildTicketComment|null findOneByUpdate(string $ticket_comment_update) Return the first ChildTicketComment filtered by the ticket_comment_update column *

 * @method     ChildTicketComment requirePk($key, ConnectionInterface $con = null) Return the ChildTicketComment by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTicketComment requireOne(ConnectionInterface $con = null) Return the first ChildTicketComment matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildTicketComment requireOneById(int $ticket_comment_id) Return the first ChildTicketComment filtered by the ticket_comment_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTicketComment requireOneByTicketId(int $ticket_id) Return the first ChildTicketComment filtered by the ticket_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTicketComment requireOneByUserId(int $user_id) Return the first ChildTicketComment filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTicketComment requireOneByContent(string $ticket_comment_content) Return the first ChildTicketComment filtered by the ticket_comment_content column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTicketComment requireOneByCreatedAt(string $ticket_comment_created) Return the first ChildTicketComment filtered by the ticket_comment_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTicketComment requireOneByUpdate(string $ticket_comment_update) Return the first ChildTicketComment filtered by the ticket_comment_update column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildTicketComment[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildTicketComment objects based on current ModelCriteria
 * @psalm-method ObjectCollection&\Traversable<ChildTicketComment> find(ConnectionInterface $con = null) Return ChildTicketComment objects based on current ModelCriteria
 * @method     ChildTicketComment[]|ObjectCollection findById(int $ticket_comment_id) Return ChildTicketComment objects filtered by the ticket_comment_id column
 * @psalm-method ObjectCollection&\Traversable<ChildTicketComment> findById(int $ticket_comment_id) Return ChildTicketComment objects filtered by the ticket_comment_id column
 * @method     ChildTicketComment[]|ObjectCollection findByTicketId(int $ticket_id) Return ChildTicketComment objects filtered by the ticket_id column
 * @psalm-method ObjectCollection&\Traversable<ChildTicketComment> findByTicketId(int $ticket_id) Return ChildTicketComment objects filtered by the ticket_id column
 * @method     ChildTicketComment[]|ObjectCollection findByUserId(int $user_id) Return ChildTicketComment objects filtered by the user_id column
 * @psalm-method ObjectCollection&\Traversable<ChildTicketComment> findByUserId(int $user_id) Return ChildTicketComment objects filtered by the user_id column
 * @method     ChildTicketComment[]|ObjectCollection findByContent(string $ticket_comment_content) Return ChildTicketComment objects filtered by the ticket_comment_content column
 * @psalm-method ObjectCollection&\Traversable<ChildTicketComment> findByContent(string $ticket_comment_content) Return ChildTicketComment objects filtered by the ticket_comment_content column
 * @method     ChildTicketComment[]|ObjectCollection findByCreatedAt(string $ticket_comment_created) Return ChildTicketComment objects filtered by the ticket_comment_created column
 * @psalm-method ObjectCollection&\Traversable<ChildTicketComment> findByCreatedAt(string $ticket_comment_created) Return ChildTicketComment objects filtered by the ticket_comment_created column
 * @method     ChildTicketComment[]|ObjectCollection findByUpdate(string $ticket_comment_update) Return ChildTicketComment objects filtered by the ticket_comment_update column
 * @psalm-method ObjectCollection&\Traversable<ChildTicketComment> findByUpdate(string $ticket_comment_update) Return ChildTicketComment objects filtered by the ticket_comment_update column
 * @method     ChildTicketComment[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildTicketComment> paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class TicketCommentQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\TicketCommentQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\TicketComment', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildTicketCommentQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildTicketCommentQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildTicketCommentQuery) {
            return $criteria;
        }
        $query = new ChildTicketCommentQuery();
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
     * @return ChildTicketComment|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(TicketCommentTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = TicketCommentTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildTicketComment A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT ticket_comment_id, ticket_id, user_id, ticket_comment_content, ticket_comment_created, ticket_comment_update FROM ticket_comment WHERE ticket_comment_id = :p0';
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
            /** @var ChildTicketComment $obj */
            $obj = new ChildTicketComment();
            $obj->hydrate($row);
            TicketCommentTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildTicketComment|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildTicketCommentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(TicketCommentTableMap::COL_TICKET_COMMENT_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildTicketCommentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(TicketCommentTableMap::COL_TICKET_COMMENT_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the ticket_comment_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE ticket_comment_id = 1234
     * $query->filterById(array(12, 34)); // WHERE ticket_comment_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE ticket_comment_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTicketCommentQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(TicketCommentTableMap::COL_TICKET_COMMENT_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(TicketCommentTableMap::COL_TICKET_COMMENT_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TicketCommentTableMap::COL_TICKET_COMMENT_ID, $id, $comparison);
    }

    /**
     * Filter the query on the ticket_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTicketId(1234); // WHERE ticket_id = 1234
     * $query->filterByTicketId(array(12, 34)); // WHERE ticket_id IN (12, 34)
     * $query->filterByTicketId(array('min' => 12)); // WHERE ticket_id > 12
     * </code>
     *
     * @param     mixed $ticketId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTicketCommentQuery The current query, for fluid interface
     */
    public function filterByTicketId($ticketId = null, $comparison = null)
    {
        if (is_array($ticketId)) {
            $useMinMax = false;
            if (isset($ticketId['min'])) {
                $this->addUsingAlias(TicketCommentTableMap::COL_TICKET_ID, $ticketId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ticketId['max'])) {
                $this->addUsingAlias(TicketCommentTableMap::COL_TICKET_ID, $ticketId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TicketCommentTableMap::COL_TICKET_ID, $ticketId, $comparison);
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
     * @return $this|ChildTicketCommentQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(TicketCommentTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(TicketCommentTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TicketCommentTableMap::COL_USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the ticket_comment_content column
     *
     * Example usage:
     * <code>
     * $query->filterByContent('fooValue');   // WHERE ticket_comment_content = 'fooValue'
     * $query->filterByContent('%fooValue%', Criteria::LIKE); // WHERE ticket_comment_content LIKE '%fooValue%'
     * </code>
     *
     * @param     string $content The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTicketCommentQuery The current query, for fluid interface
     */
    public function filterByContent($content = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($content)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TicketCommentTableMap::COL_TICKET_COMMENT_CONTENT, $content, $comparison);
    }

    /**
     * Filter the query on the ticket_comment_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE ticket_comment_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE ticket_comment_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE ticket_comment_created > '2011-03-13'
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
     * @return $this|ChildTicketCommentQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(TicketCommentTableMap::COL_TICKET_COMMENT_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(TicketCommentTableMap::COL_TICKET_COMMENT_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TicketCommentTableMap::COL_TICKET_COMMENT_CREATED, $createdAt, $comparison);
    }

    /**
     * Filter the query on the ticket_comment_update column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdate('2011-03-14'); // WHERE ticket_comment_update = '2011-03-14'
     * $query->filterByUpdate('now'); // WHERE ticket_comment_update = '2011-03-14'
     * $query->filterByUpdate(array('max' => 'yesterday')); // WHERE ticket_comment_update > '2011-03-13'
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
     * @return $this|ChildTicketCommentQuery The current query, for fluid interface
     */
    public function filterByUpdate($update = null, $comparison = null)
    {
        if (is_array($update)) {
            $useMinMax = false;
            if (isset($update['min'])) {
                $this->addUsingAlias(TicketCommentTableMap::COL_TICKET_COMMENT_UPDATE, $update['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($update['max'])) {
                $this->addUsingAlias(TicketCommentTableMap::COL_TICKET_COMMENT_UPDATE, $update['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TicketCommentTableMap::COL_TICKET_COMMENT_UPDATE, $update, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildTicketComment $ticketComment Object to remove from the list of results
     *
     * @return $this|ChildTicketCommentQuery The current query, for fluid interface
     */
    public function prune($ticketComment = null)
    {
        if ($ticketComment) {
            $this->addUsingAlias(TicketCommentTableMap::COL_TICKET_COMMENT_ID, $ticketComment->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the ticket_comment table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(TicketCommentTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            TicketCommentTableMap::clearInstancePool();
            TicketCommentTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(TicketCommentTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(TicketCommentTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            TicketCommentTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            TicketCommentTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildTicketCommentQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(TicketCommentTableMap::COL_TICKET_COMMENT_UPDATE, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildTicketCommentQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(TicketCommentTableMap::COL_TICKET_COMMENT_UPDATE);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildTicketCommentQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(TicketCommentTableMap::COL_TICKET_COMMENT_UPDATE);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildTicketCommentQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(TicketCommentTableMap::COL_TICKET_COMMENT_CREATED);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildTicketCommentQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(TicketCommentTableMap::COL_TICKET_COMMENT_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildTicketCommentQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(TicketCommentTableMap::COL_TICKET_COMMENT_CREATED);
    }

} // TicketCommentQuery
