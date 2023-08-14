<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Vote as ChildVote;
use Model\VoteQuery as ChildVoteQuery;
use Model\Map\VoteTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `votes` table.
 *
 * @method     ChildVoteQuery orderById($order = Criteria::ASC) Order by the vote_id column
 * @method     ChildVoteQuery orderByAxysAccountId($order = Criteria::ASC) Order by the axys_account_id column
 * @method     ChildVoteQuery orderByF($order = Criteria::ASC) Order by the vote_F column
 * @method     ChildVoteQuery orderByE($order = Criteria::ASC) Order by the vote_E column
 * @method     ChildVoteQuery orderByDate($order = Criteria::ASC) Order by the vote_date column
 *
 * @method     ChildVoteQuery groupById() Group by the vote_id column
 * @method     ChildVoteQuery groupByAxysAccountId() Group by the axys_account_id column
 * @method     ChildVoteQuery groupByF() Group by the vote_F column
 * @method     ChildVoteQuery groupByE() Group by the vote_E column
 * @method     ChildVoteQuery groupByDate() Group by the vote_date column
 *
 * @method     ChildVoteQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildVoteQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildVoteQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildVoteQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildVoteQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildVoteQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildVote|null findOne(?ConnectionInterface $con = null) Return the first ChildVote matching the query
 * @method     ChildVote findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildVote matching the query, or a new ChildVote object populated from the query conditions when no match is found
 *
 * @method     ChildVote|null findOneById(int $vote_id) Return the first ChildVote filtered by the vote_id column
 * @method     ChildVote|null findOneByAxysAccountId(int $axys_account_id) Return the first ChildVote filtered by the axys_account_id column
 * @method     ChildVote|null findOneByF(int $vote_F) Return the first ChildVote filtered by the vote_F column
 * @method     ChildVote|null findOneByE(int $vote_E) Return the first ChildVote filtered by the vote_E column
 * @method     ChildVote|null findOneByDate(string $vote_date) Return the first ChildVote filtered by the vote_date column
 *
 * @method     ChildVote requirePk($key, ?ConnectionInterface $con = null) Return the ChildVote by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildVote requireOne(?ConnectionInterface $con = null) Return the first ChildVote matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildVote requireOneById(int $vote_id) Return the first ChildVote filtered by the vote_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildVote requireOneByAxysAccountId(int $axys_account_id) Return the first ChildVote filtered by the axys_account_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildVote requireOneByF(int $vote_F) Return the first ChildVote filtered by the vote_F column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildVote requireOneByE(int $vote_E) Return the first ChildVote filtered by the vote_E column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildVote requireOneByDate(string $vote_date) Return the first ChildVote filtered by the vote_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildVote[]|Collection find(?ConnectionInterface $con = null) Return ChildVote objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildVote> find(?ConnectionInterface $con = null) Return ChildVote objects based on current ModelCriteria
 *
 * @method     ChildVote[]|Collection findById(int|array<int> $vote_id) Return ChildVote objects filtered by the vote_id column
 * @psalm-method Collection&\Traversable<ChildVote> findById(int|array<int> $vote_id) Return ChildVote objects filtered by the vote_id column
 * @method     ChildVote[]|Collection findByAxysAccountId(int|array<int> $axys_account_id) Return ChildVote objects filtered by the axys_account_id column
 * @psalm-method Collection&\Traversable<ChildVote> findByAxysAccountId(int|array<int> $axys_account_id) Return ChildVote objects filtered by the axys_account_id column
 * @method     ChildVote[]|Collection findByF(int|array<int> $vote_F) Return ChildVote objects filtered by the vote_F column
 * @psalm-method Collection&\Traversable<ChildVote> findByF(int|array<int> $vote_F) Return ChildVote objects filtered by the vote_F column
 * @method     ChildVote[]|Collection findByE(int|array<int> $vote_E) Return ChildVote objects filtered by the vote_E column
 * @psalm-method Collection&\Traversable<ChildVote> findByE(int|array<int> $vote_E) Return ChildVote objects filtered by the vote_E column
 * @method     ChildVote[]|Collection findByDate(string|array<string> $vote_date) Return ChildVote objects filtered by the vote_date column
 * @psalm-method Collection&\Traversable<ChildVote> findByDate(string|array<string> $vote_date) Return ChildVote objects filtered by the vote_date column
 *
 * @method     ChildVote[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildVote> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class VoteQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\VoteQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Vote', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildVoteQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildVoteQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildVoteQuery) {
            return $criteria;
        }
        $query = new ChildVoteQuery();
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
     * @return ChildVote|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(VoteTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = VoteTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildVote A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT vote_id, axys_account_id, vote_F, vote_E, vote_date FROM votes WHERE vote_id = :p0';
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
            /** @var ChildVote $obj */
            $obj = new ChildVote();
            $obj->hydrate($row);
            VoteTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildVote|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(VoteTableMap::COL_VOTE_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(VoteTableMap::COL_VOTE_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the vote_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE vote_id = 1234
     * $query->filterById(array(12, 34)); // WHERE vote_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE vote_id > 12
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
                $this->addUsingAlias(VoteTableMap::COL_VOTE_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(VoteTableMap::COL_VOTE_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(VoteTableMap::COL_VOTE_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the axys_account_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAxysAccountId(1234); // WHERE axys_account_id = 1234
     * $query->filterByAxysAccountId(array(12, 34)); // WHERE axys_account_id IN (12, 34)
     * $query->filterByAxysAccountId(array('min' => 12)); // WHERE axys_account_id > 12
     * </code>
     *
     * @param mixed $axysAccountId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAxysAccountId($axysAccountId = null, ?string $comparison = null)
    {
        if (is_array($axysAccountId)) {
            $useMinMax = false;
            if (isset($axysAccountId['min'])) {
                $this->addUsingAlias(VoteTableMap::COL_AXYS_ACCOUNT_ID, $axysAccountId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($axysAccountId['max'])) {
                $this->addUsingAlias(VoteTableMap::COL_AXYS_ACCOUNT_ID, $axysAccountId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(VoteTableMap::COL_AXYS_ACCOUNT_ID, $axysAccountId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the vote_F column
     *
     * Example usage:
     * <code>
     * $query->filterByF(1234); // WHERE vote_F = 1234
     * $query->filterByF(array(12, 34)); // WHERE vote_F IN (12, 34)
     * $query->filterByF(array('min' => 12)); // WHERE vote_F > 12
     * </code>
     *
     * @param mixed $f The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByF($f = null, ?string $comparison = null)
    {
        if (is_array($f)) {
            $useMinMax = false;
            if (isset($f['min'])) {
                $this->addUsingAlias(VoteTableMap::COL_VOTE_F, $f['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($f['max'])) {
                $this->addUsingAlias(VoteTableMap::COL_VOTE_F, $f['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(VoteTableMap::COL_VOTE_F, $f, $comparison);

        return $this;
    }

    /**
     * Filter the query on the vote_E column
     *
     * Example usage:
     * <code>
     * $query->filterByE(1234); // WHERE vote_E = 1234
     * $query->filterByE(array(12, 34)); // WHERE vote_E IN (12, 34)
     * $query->filterByE(array('min' => 12)); // WHERE vote_E > 12
     * </code>
     *
     * @param mixed $e The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByE($e = null, ?string $comparison = null)
    {
        if (is_array($e)) {
            $useMinMax = false;
            if (isset($e['min'])) {
                $this->addUsingAlias(VoteTableMap::COL_VOTE_E, $e['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($e['max'])) {
                $this->addUsingAlias(VoteTableMap::COL_VOTE_E, $e['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(VoteTableMap::COL_VOTE_E, $e, $comparison);

        return $this;
    }

    /**
     * Filter the query on the vote_date column
     *
     * Example usage:
     * <code>
     * $query->filterByDate('2011-03-14'); // WHERE vote_date = '2011-03-14'
     * $query->filterByDate('now'); // WHERE vote_date = '2011-03-14'
     * $query->filterByDate(array('max' => 'yesterday')); // WHERE vote_date > '2011-03-13'
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
                $this->addUsingAlias(VoteTableMap::COL_VOTE_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(VoteTableMap::COL_VOTE_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(VoteTableMap::COL_VOTE_DATE, $date, $comparison);

        return $this;
    }

    /**
     * Exclude object from result
     *
     * @param ChildVote $vote Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($vote = null)
    {
        if ($vote) {
            $this->addUsingAlias(VoteTableMap::COL_VOTE_ID, $vote->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the votes table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(VoteTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            VoteTableMap::clearInstancePool();
            VoteTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(VoteTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(VoteTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            VoteTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            VoteTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

}
