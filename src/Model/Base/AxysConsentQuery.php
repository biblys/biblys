<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\AxysConsent as ChildAxysConsent;
use Model\AxysConsentQuery as ChildAxysConsentQuery;
use Model\Map\AxysConsentTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `axys_consents` table.
 *
 * @method     ChildAxysConsentQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildAxysConsentQuery orderByAppId($order = Criteria::ASC) Order by the app_id column
 * @method     ChildAxysConsentQuery orderByAxysAccountId($order = Criteria::ASC) Order by the axys_account_id column
 * @method     ChildAxysConsentQuery orderByScopes($order = Criteria::ASC) Order by the scopes column
 * @method     ChildAxysConsentQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildAxysConsentQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildAxysConsentQuery groupById() Group by the id column
 * @method     ChildAxysConsentQuery groupByAppId() Group by the app_id column
 * @method     ChildAxysConsentQuery groupByAxysAccountId() Group by the axys_account_id column
 * @method     ChildAxysConsentQuery groupByScopes() Group by the scopes column
 * @method     ChildAxysConsentQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildAxysConsentQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildAxysConsentQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildAxysConsentQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildAxysConsentQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildAxysConsentQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildAxysConsentQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildAxysConsentQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildAxysConsentQuery leftJoinAxysApp($relationAlias = null) Adds a LEFT JOIN clause to the query using the AxysApp relation
 * @method     ChildAxysConsentQuery rightJoinAxysApp($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AxysApp relation
 * @method     ChildAxysConsentQuery innerJoinAxysApp($relationAlias = null) Adds a INNER JOIN clause to the query using the AxysApp relation
 *
 * @method     ChildAxysConsentQuery joinWithAxysApp($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the AxysApp relation
 *
 * @method     ChildAxysConsentQuery leftJoinWithAxysApp() Adds a LEFT JOIN clause and with to the query using the AxysApp relation
 * @method     ChildAxysConsentQuery rightJoinWithAxysApp() Adds a RIGHT JOIN clause and with to the query using the AxysApp relation
 * @method     ChildAxysConsentQuery innerJoinWithAxysApp() Adds a INNER JOIN clause and with to the query using the AxysApp relation
 *
 * @method     ChildAxysConsentQuery leftJoinAxysAccount($relationAlias = null) Adds a LEFT JOIN clause to the query using the AxysAccount relation
 * @method     ChildAxysConsentQuery rightJoinAxysAccount($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AxysAccount relation
 * @method     ChildAxysConsentQuery innerJoinAxysAccount($relationAlias = null) Adds a INNER JOIN clause to the query using the AxysAccount relation
 *
 * @method     ChildAxysConsentQuery joinWithAxysAccount($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the AxysAccount relation
 *
 * @method     ChildAxysConsentQuery leftJoinWithAxysAccount() Adds a LEFT JOIN clause and with to the query using the AxysAccount relation
 * @method     ChildAxysConsentQuery rightJoinWithAxysAccount() Adds a RIGHT JOIN clause and with to the query using the AxysAccount relation
 * @method     ChildAxysConsentQuery innerJoinWithAxysAccount() Adds a INNER JOIN clause and with to the query using the AxysAccount relation
 *
 * @method     \Model\AxysAppQuery|\Model\AxysAccountQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildAxysConsent|null findOne(?ConnectionInterface $con = null) Return the first ChildAxysConsent matching the query
 * @method     ChildAxysConsent findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildAxysConsent matching the query, or a new ChildAxysConsent object populated from the query conditions when no match is found
 *
 * @method     ChildAxysConsent|null findOneById(int $id) Return the first ChildAxysConsent filtered by the id column
 * @method     ChildAxysConsent|null findOneByAppId(int $app_id) Return the first ChildAxysConsent filtered by the app_id column
 * @method     ChildAxysConsent|null findOneByAxysAccountId(int $axys_account_id) Return the first ChildAxysConsent filtered by the axys_account_id column
 * @method     ChildAxysConsent|null findOneByScopes(string $scopes) Return the first ChildAxysConsent filtered by the scopes column
 * @method     ChildAxysConsent|null findOneByCreatedAt(string $created_at) Return the first ChildAxysConsent filtered by the created_at column
 * @method     ChildAxysConsent|null findOneByUpdatedAt(string $updated_at) Return the first ChildAxysConsent filtered by the updated_at column
 *
 * @method     ChildAxysConsent requirePk($key, ?ConnectionInterface $con = null) Return the ChildAxysConsent by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAxysConsent requireOne(?ConnectionInterface $con = null) Return the first ChildAxysConsent matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAxysConsent requireOneById(int $id) Return the first ChildAxysConsent filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAxysConsent requireOneByAppId(int $app_id) Return the first ChildAxysConsent filtered by the app_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAxysConsent requireOneByAxysAccountId(int $axys_account_id) Return the first ChildAxysConsent filtered by the axys_account_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAxysConsent requireOneByScopes(string $scopes) Return the first ChildAxysConsent filtered by the scopes column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAxysConsent requireOneByCreatedAt(string $created_at) Return the first ChildAxysConsent filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAxysConsent requireOneByUpdatedAt(string $updated_at) Return the first ChildAxysConsent filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAxysConsent[]|Collection find(?ConnectionInterface $con = null) Return ChildAxysConsent objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildAxysConsent> find(?ConnectionInterface $con = null) Return ChildAxysConsent objects based on current ModelCriteria
 *
 * @method     ChildAxysConsent[]|Collection findById(int|array<int> $id) Return ChildAxysConsent objects filtered by the id column
 * @psalm-method Collection&\Traversable<ChildAxysConsent> findById(int|array<int> $id) Return ChildAxysConsent objects filtered by the id column
 * @method     ChildAxysConsent[]|Collection findByAppId(int|array<int> $app_id) Return ChildAxysConsent objects filtered by the app_id column
 * @psalm-method Collection&\Traversable<ChildAxysConsent> findByAppId(int|array<int> $app_id) Return ChildAxysConsent objects filtered by the app_id column
 * @method     ChildAxysConsent[]|Collection findByAxysAccountId(int|array<int> $axys_account_id) Return ChildAxysConsent objects filtered by the axys_account_id column
 * @psalm-method Collection&\Traversable<ChildAxysConsent> findByAxysAccountId(int|array<int> $axys_account_id) Return ChildAxysConsent objects filtered by the axys_account_id column
 * @method     ChildAxysConsent[]|Collection findByScopes(string|array<string> $scopes) Return ChildAxysConsent objects filtered by the scopes column
 * @psalm-method Collection&\Traversable<ChildAxysConsent> findByScopes(string|array<string> $scopes) Return ChildAxysConsent objects filtered by the scopes column
 * @method     ChildAxysConsent[]|Collection findByCreatedAt(string|array<string> $created_at) Return ChildAxysConsent objects filtered by the created_at column
 * @psalm-method Collection&\Traversable<ChildAxysConsent> findByCreatedAt(string|array<string> $created_at) Return ChildAxysConsent objects filtered by the created_at column
 * @method     ChildAxysConsent[]|Collection findByUpdatedAt(string|array<string> $updated_at) Return ChildAxysConsent objects filtered by the updated_at column
 * @psalm-method Collection&\Traversable<ChildAxysConsent> findByUpdatedAt(string|array<string> $updated_at) Return ChildAxysConsent objects filtered by the updated_at column
 *
 * @method     ChildAxysConsent[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildAxysConsent> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class AxysConsentQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\AxysConsentQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\AxysConsent', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildAxysConsentQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildAxysConsentQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildAxysConsentQuery) {
            return $criteria;
        }
        $query = new ChildAxysConsentQuery();
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
     * @return ChildAxysConsent|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(AxysConsentTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = AxysConsentTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildAxysConsent A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, app_id, axys_account_id, scopes, created_at, updated_at FROM axys_consents WHERE id = :p0';
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
            /** @var ChildAxysConsent $obj */
            $obj = new ChildAxysConsent();
            $obj->hydrate($row);
            AxysConsentTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildAxysConsent|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(AxysConsentTableMap::COL_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(AxysConsentTableMap::COL_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
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
                $this->addUsingAlias(AxysConsentTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(AxysConsentTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AxysConsentTableMap::COL_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the app_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAppId(1234); // WHERE app_id = 1234
     * $query->filterByAppId(array(12, 34)); // WHERE app_id IN (12, 34)
     * $query->filterByAppId(array('min' => 12)); // WHERE app_id > 12
     * </code>
     *
     * @see       filterByAxysApp()
     *
     * @param mixed $appId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAppId($appId = null, ?string $comparison = null)
    {
        if (is_array($appId)) {
            $useMinMax = false;
            if (isset($appId['min'])) {
                $this->addUsingAlias(AxysConsentTableMap::COL_APP_ID, $appId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($appId['max'])) {
                $this->addUsingAlias(AxysConsentTableMap::COL_APP_ID, $appId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AxysConsentTableMap::COL_APP_ID, $appId, $comparison);

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
     * @see       filterByAxysAccount()
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
                $this->addUsingAlias(AxysConsentTableMap::COL_AXYS_ACCOUNT_ID, $axysAccountId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($axysAccountId['max'])) {
                $this->addUsingAlias(AxysConsentTableMap::COL_AXYS_ACCOUNT_ID, $axysAccountId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AxysConsentTableMap::COL_AXYS_ACCOUNT_ID, $axysAccountId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the scopes column
     *
     * Example usage:
     * <code>
     * $query->filterByScopes('fooValue');   // WHERE scopes = 'fooValue'
     * $query->filterByScopes('%fooValue%', Criteria::LIKE); // WHERE scopes LIKE '%fooValue%'
     * $query->filterByScopes(['foo', 'bar']); // WHERE scopes IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $scopes The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByScopes($scopes = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($scopes)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AxysConsentTableMap::COL_SCOPES, $scopes, $comparison);

        return $this;
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
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
                $this->addUsingAlias(AxysConsentTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(AxysConsentTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AxysConsentTableMap::COL_CREATED_AT, $createdAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
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
                $this->addUsingAlias(AxysConsentTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(AxysConsentTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AxysConsentTableMap::COL_UPDATED_AT, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \Model\AxysApp object
     *
     * @param \Model\AxysApp|ObjectCollection $axysApp The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAxysApp($axysApp, ?string $comparison = null)
    {
        if ($axysApp instanceof \Model\AxysApp) {
            return $this
                ->addUsingAlias(AxysConsentTableMap::COL_APP_ID, $axysApp->getId(), $comparison);
        } elseif ($axysApp instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(AxysConsentTableMap::COL_APP_ID, $axysApp->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByAxysApp() only accepts arguments of type \Model\AxysApp or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AxysApp relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinAxysApp(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AxysApp');

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
            $this->addJoinObject($join, 'AxysApp');
        }

        return $this;
    }

    /**
     * Use the AxysApp relation AxysApp object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\AxysAppQuery A secondary query class using the current class as primary query
     */
    public function useAxysAppQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAxysApp($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AxysApp', '\Model\AxysAppQuery');
    }

    /**
     * Use the AxysApp relation AxysApp object
     *
     * @param callable(\Model\AxysAppQuery):\Model\AxysAppQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withAxysAppQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useAxysAppQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to AxysApp table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\AxysAppQuery The inner query object of the EXISTS statement
     */
    public function useAxysAppExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\AxysAppQuery */
        $q = $this->useExistsQuery('AxysApp', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to AxysApp table for a NOT EXISTS query.
     *
     * @see useAxysAppExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\AxysAppQuery The inner query object of the NOT EXISTS statement
     */
    public function useAxysAppNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\AxysAppQuery */
        $q = $this->useExistsQuery('AxysApp', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to AxysApp table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\AxysAppQuery The inner query object of the IN statement
     */
    public function useInAxysAppQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\AxysAppQuery */
        $q = $this->useInQuery('AxysApp', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to AxysApp table for a NOT IN query.
     *
     * @see useAxysAppInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\AxysAppQuery The inner query object of the NOT IN statement
     */
    public function useNotInAxysAppQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\AxysAppQuery */
        $q = $this->useInQuery('AxysApp', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\AxysAccount object
     *
     * @param \Model\AxysAccount|ObjectCollection $axysAccount The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAxysAccount($axysAccount, ?string $comparison = null)
    {
        if ($axysAccount instanceof \Model\AxysAccount) {
            return $this
                ->addUsingAlias(AxysConsentTableMap::COL_AXYS_ACCOUNT_ID, $axysAccount->getId(), $comparison);
        } elseif ($axysAccount instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(AxysConsentTableMap::COL_AXYS_ACCOUNT_ID, $axysAccount->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByAxysAccount() only accepts arguments of type \Model\AxysAccount or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AxysAccount relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinAxysAccount(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AxysAccount');

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
            $this->addJoinObject($join, 'AxysAccount');
        }

        return $this;
    }

    /**
     * Use the AxysAccount relation AxysAccount object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\AxysAccountQuery A secondary query class using the current class as primary query
     */
    public function useAxysAccountQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAxysAccount($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AxysAccount', '\Model\AxysAccountQuery');
    }

    /**
     * Use the AxysAccount relation AxysAccount object
     *
     * @param callable(\Model\AxysAccountQuery):\Model\AxysAccountQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withAxysAccountQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useAxysAccountQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to AxysAccount table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\AxysAccountQuery The inner query object of the EXISTS statement
     */
    public function useAxysAccountExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\AxysAccountQuery */
        $q = $this->useExistsQuery('AxysAccount', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to AxysAccount table for a NOT EXISTS query.
     *
     * @see useAxysAccountExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\AxysAccountQuery The inner query object of the NOT EXISTS statement
     */
    public function useAxysAccountNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\AxysAccountQuery */
        $q = $this->useExistsQuery('AxysAccount', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to AxysAccount table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\AxysAccountQuery The inner query object of the IN statement
     */
    public function useInAxysAccountQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\AxysAccountQuery */
        $q = $this->useInQuery('AxysAccount', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to AxysAccount table for a NOT IN query.
     *
     * @see useAxysAccountInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\AxysAccountQuery The inner query object of the NOT IN statement
     */
    public function useNotInAxysAccountQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\AxysAccountQuery */
        $q = $this->useInQuery('AxysAccount', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Exclude object from result
     *
     * @param ChildAxysConsent $axysConsent Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($axysConsent = null)
    {
        if ($axysConsent) {
            $this->addUsingAlias(AxysConsentTableMap::COL_ID, $axysConsent->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the axys_consents table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AxysConsentTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            AxysConsentTableMap::clearInstancePool();
            AxysConsentTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(AxysConsentTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(AxysConsentTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            AxysConsentTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            AxysConsentTableMap::clearRelatedInstancePool();

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
        $this->addUsingAlias(AxysConsentTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(AxysConsentTableMap::COL_UPDATED_AT);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(AxysConsentTableMap::COL_UPDATED_AT);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(AxysConsentTableMap::COL_CREATED_AT);

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
        $this->addUsingAlias(AxysConsentTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(AxysConsentTableMap::COL_CREATED_AT);

        return $this;
    }

}
