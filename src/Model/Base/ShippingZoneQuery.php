<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\ShippingZone as ChildShippingZone;
use Model\ShippingZoneQuery as ChildShippingZoneQuery;
use Model\Map\ShippingZoneTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `shipping_zones` table.
 *
 * @method     ChildShippingZoneQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildShippingZoneQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildShippingZoneQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method     ChildShippingZoneQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildShippingZoneQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildShippingZoneQuery groupById() Group by the id column
 * @method     ChildShippingZoneQuery groupByName() Group by the name column
 * @method     ChildShippingZoneQuery groupByDescription() Group by the description column
 * @method     ChildShippingZoneQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildShippingZoneQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildShippingZoneQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildShippingZoneQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildShippingZoneQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildShippingZoneQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildShippingZoneQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildShippingZoneQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildShippingZoneQuery leftJoinShippingOption($relationAlias = null) Adds a LEFT JOIN clause to the query using the ShippingOption relation
 * @method     ChildShippingZoneQuery rightJoinShippingOption($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ShippingOption relation
 * @method     ChildShippingZoneQuery innerJoinShippingOption($relationAlias = null) Adds a INNER JOIN clause to the query using the ShippingOption relation
 *
 * @method     ChildShippingZoneQuery joinWithShippingOption($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ShippingOption relation
 *
 * @method     ChildShippingZoneQuery leftJoinWithShippingOption() Adds a LEFT JOIN clause and with to the query using the ShippingOption relation
 * @method     ChildShippingZoneQuery rightJoinWithShippingOption() Adds a RIGHT JOIN clause and with to the query using the ShippingOption relation
 * @method     ChildShippingZoneQuery innerJoinWithShippingOption() Adds a INNER JOIN clause and with to the query using the ShippingOption relation
 *
 * @method     ChildShippingZoneQuery leftJoinShippingZonesCountries($relationAlias = null) Adds a LEFT JOIN clause to the query using the ShippingZonesCountries relation
 * @method     ChildShippingZoneQuery rightJoinShippingZonesCountries($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ShippingZonesCountries relation
 * @method     ChildShippingZoneQuery innerJoinShippingZonesCountries($relationAlias = null) Adds a INNER JOIN clause to the query using the ShippingZonesCountries relation
 *
 * @method     ChildShippingZoneQuery joinWithShippingZonesCountries($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ShippingZonesCountries relation
 *
 * @method     ChildShippingZoneQuery leftJoinWithShippingZonesCountries() Adds a LEFT JOIN clause and with to the query using the ShippingZonesCountries relation
 * @method     ChildShippingZoneQuery rightJoinWithShippingZonesCountries() Adds a RIGHT JOIN clause and with to the query using the ShippingZonesCountries relation
 * @method     ChildShippingZoneQuery innerJoinWithShippingZonesCountries() Adds a INNER JOIN clause and with to the query using the ShippingZonesCountries relation
 *
 * @method     \Model\ShippingOptionQuery|\Model\ShippingZonesCountriesQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildShippingZone|null findOne(?ConnectionInterface $con = null) Return the first ChildShippingZone matching the query
 * @method     ChildShippingZone findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildShippingZone matching the query, or a new ChildShippingZone object populated from the query conditions when no match is found
 *
 * @method     ChildShippingZone|null findOneById(int $id) Return the first ChildShippingZone filtered by the id column
 * @method     ChildShippingZone|null findOneByName(string $name) Return the first ChildShippingZone filtered by the name column
 * @method     ChildShippingZone|null findOneByDescription(string $description) Return the first ChildShippingZone filtered by the description column
 * @method     ChildShippingZone|null findOneByCreatedAt(string $created_at) Return the first ChildShippingZone filtered by the created_at column
 * @method     ChildShippingZone|null findOneByUpdatedAt(string $updated_at) Return the first ChildShippingZone filtered by the updated_at column
 *
 * @method     ChildShippingZone requirePk($key, ?ConnectionInterface $con = null) Return the ChildShippingZone by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingZone requireOne(?ConnectionInterface $con = null) Return the first ChildShippingZone matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildShippingZone requireOneById(int $id) Return the first ChildShippingZone filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingZone requireOneByName(string $name) Return the first ChildShippingZone filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingZone requireOneByDescription(string $description) Return the first ChildShippingZone filtered by the description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingZone requireOneByCreatedAt(string $created_at) Return the first ChildShippingZone filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingZone requireOneByUpdatedAt(string $updated_at) Return the first ChildShippingZone filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildShippingZone[]|Collection find(?ConnectionInterface $con = null) Return ChildShippingZone objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildShippingZone> find(?ConnectionInterface $con = null) Return ChildShippingZone objects based on current ModelCriteria
 *
 * @method     ChildShippingZone[]|Collection findById(int|array<int> $id) Return ChildShippingZone objects filtered by the id column
 * @psalm-method Collection&\Traversable<ChildShippingZone> findById(int|array<int> $id) Return ChildShippingZone objects filtered by the id column
 * @method     ChildShippingZone[]|Collection findByName(string|array<string> $name) Return ChildShippingZone objects filtered by the name column
 * @psalm-method Collection&\Traversable<ChildShippingZone> findByName(string|array<string> $name) Return ChildShippingZone objects filtered by the name column
 * @method     ChildShippingZone[]|Collection findByDescription(string|array<string> $description) Return ChildShippingZone objects filtered by the description column
 * @psalm-method Collection&\Traversable<ChildShippingZone> findByDescription(string|array<string> $description) Return ChildShippingZone objects filtered by the description column
 * @method     ChildShippingZone[]|Collection findByCreatedAt(string|array<string> $created_at) Return ChildShippingZone objects filtered by the created_at column
 * @psalm-method Collection&\Traversable<ChildShippingZone> findByCreatedAt(string|array<string> $created_at) Return ChildShippingZone objects filtered by the created_at column
 * @method     ChildShippingZone[]|Collection findByUpdatedAt(string|array<string> $updated_at) Return ChildShippingZone objects filtered by the updated_at column
 * @psalm-method Collection&\Traversable<ChildShippingZone> findByUpdatedAt(string|array<string> $updated_at) Return ChildShippingZone objects filtered by the updated_at column
 *
 * @method     ChildShippingZone[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildShippingZone> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class ShippingZoneQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\ShippingZoneQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\ShippingZone', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildShippingZoneQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildShippingZoneQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildShippingZoneQuery) {
            return $criteria;
        }
        $query = new ChildShippingZoneQuery();
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
     * @return ChildShippingZone|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ShippingZoneTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ShippingZoneTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildShippingZone A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, name, description, created_at, updated_at FROM shipping_zones WHERE id = :p0';
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
            /** @var ChildShippingZone $obj */
            $obj = new ChildShippingZone();
            $obj->hydrate($row);
            ShippingZoneTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildShippingZone|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(ShippingZoneTableMap::COL_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(ShippingZoneTableMap::COL_ID, $keys, Criteria::IN);

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
                $this->addUsingAlias(ShippingZoneTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ShippingZoneTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ShippingZoneTableMap::COL_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE name LIKE '%fooValue%'
     * $query->filterByName(['foo', 'bar']); // WHERE name IN ('foo', 'bar')
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

        $this->addUsingAlias(ShippingZoneTableMap::COL_NAME, $name, $comparison);

        return $this;
    }

    /**
     * Filter the query on the description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE description = 'fooValue'
     * $query->filterByDescription('%fooValue%', Criteria::LIKE); // WHERE description LIKE '%fooValue%'
     * $query->filterByDescription(['foo', 'bar']); // WHERE description IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $description The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDescription($description = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ShippingZoneTableMap::COL_DESCRIPTION, $description, $comparison);

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
                $this->addUsingAlias(ShippingZoneTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(ShippingZoneTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ShippingZoneTableMap::COL_CREATED_AT, $createdAt, $comparison);

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
                $this->addUsingAlias(ShippingZoneTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(ShippingZoneTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ShippingZoneTableMap::COL_UPDATED_AT, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \Model\ShippingOption object
     *
     * @param \Model\ShippingOption|ObjectCollection $shippingOption the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByShippingOption($shippingOption, ?string $comparison = null)
    {
        if ($shippingOption instanceof \Model\ShippingOption) {
            $this
                ->addUsingAlias(ShippingZoneTableMap::COL_ID, $shippingOption->getShippingZoneId(), $comparison);

            return $this;
        } elseif ($shippingOption instanceof ObjectCollection) {
            $this
                ->useShippingOptionQuery()
                ->filterByPrimaryKeys($shippingOption->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByShippingOption() only accepts arguments of type \Model\ShippingOption or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ShippingOption relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinShippingOption(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ShippingOption');

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
            $this->addJoinObject($join, 'ShippingOption');
        }

        return $this;
    }

    /**
     * Use the ShippingOption relation ShippingOption object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\ShippingOptionQuery A secondary query class using the current class as primary query
     */
    public function useShippingOptionQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinShippingOption($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ShippingOption', '\Model\ShippingOptionQuery');
    }

    /**
     * Use the ShippingOption relation ShippingOption object
     *
     * @param callable(\Model\ShippingOptionQuery):\Model\ShippingOptionQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withShippingOptionQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useShippingOptionQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to ShippingOption table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\ShippingOptionQuery The inner query object of the EXISTS statement
     */
    public function useShippingOptionExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\ShippingOptionQuery */
        $q = $this->useExistsQuery('ShippingOption', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to ShippingOption table for a NOT EXISTS query.
     *
     * @see useShippingOptionExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\ShippingOptionQuery The inner query object of the NOT EXISTS statement
     */
    public function useShippingOptionNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\ShippingOptionQuery */
        $q = $this->useExistsQuery('ShippingOption', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to ShippingOption table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\ShippingOptionQuery The inner query object of the IN statement
     */
    public function useInShippingOptionQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\ShippingOptionQuery */
        $q = $this->useInQuery('ShippingOption', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to ShippingOption table for a NOT IN query.
     *
     * @see useShippingOptionInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\ShippingOptionQuery The inner query object of the NOT IN statement
     */
    public function useNotInShippingOptionQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\ShippingOptionQuery */
        $q = $this->useInQuery('ShippingOption', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\ShippingZonesCountries object
     *
     * @param \Model\ShippingZonesCountries|ObjectCollection $shippingZonesCountries the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByShippingZonesCountries($shippingZonesCountries, ?string $comparison = null)
    {
        if ($shippingZonesCountries instanceof \Model\ShippingZonesCountries) {
            $this
                ->addUsingAlias(ShippingZoneTableMap::COL_ID, $shippingZonesCountries->getShippingZoneId(), $comparison);

            return $this;
        } elseif ($shippingZonesCountries instanceof ObjectCollection) {
            $this
                ->useShippingZonesCountriesQuery()
                ->filterByPrimaryKeys($shippingZonesCountries->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByShippingZonesCountries() only accepts arguments of type \Model\ShippingZonesCountries or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ShippingZonesCountries relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinShippingZonesCountries(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ShippingZonesCountries');

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
            $this->addJoinObject($join, 'ShippingZonesCountries');
        }

        return $this;
    }

    /**
     * Use the ShippingZonesCountries relation ShippingZonesCountries object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\ShippingZonesCountriesQuery A secondary query class using the current class as primary query
     */
    public function useShippingZonesCountriesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinShippingZonesCountries($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ShippingZonesCountries', '\Model\ShippingZonesCountriesQuery');
    }

    /**
     * Use the ShippingZonesCountries relation ShippingZonesCountries object
     *
     * @param callable(\Model\ShippingZonesCountriesQuery):\Model\ShippingZonesCountriesQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withShippingZonesCountriesQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useShippingZonesCountriesQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to ShippingZonesCountries table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\ShippingZonesCountriesQuery The inner query object of the EXISTS statement
     */
    public function useShippingZonesCountriesExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\ShippingZonesCountriesQuery */
        $q = $this->useExistsQuery('ShippingZonesCountries', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to ShippingZonesCountries table for a NOT EXISTS query.
     *
     * @see useShippingZonesCountriesExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\ShippingZonesCountriesQuery The inner query object of the NOT EXISTS statement
     */
    public function useShippingZonesCountriesNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\ShippingZonesCountriesQuery */
        $q = $this->useExistsQuery('ShippingZonesCountries', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to ShippingZonesCountries table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\ShippingZonesCountriesQuery The inner query object of the IN statement
     */
    public function useInShippingZonesCountriesQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\ShippingZonesCountriesQuery */
        $q = $this->useInQuery('ShippingZonesCountries', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to ShippingZonesCountries table for a NOT IN query.
     *
     * @see useShippingZonesCountriesInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\ShippingZonesCountriesQuery The inner query object of the NOT IN statement
     */
    public function useNotInShippingZonesCountriesQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\ShippingZonesCountriesQuery */
        $q = $this->useInQuery('ShippingZonesCountries', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related Country object
     * using the shipping_zones_countries table as cross reference
     *
     * @param Country $country the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL and Criteria::IN for queries
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCountry($country, string $comparison = null)
    {
        $this
            ->useShippingZonesCountriesQuery()
            ->filterByCountry($country, $comparison)
            ->endUse();

        return $this;
    }

    /**
     * Exclude object from result
     *
     * @param ChildShippingZone $shippingZone Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($shippingZone = null)
    {
        if ($shippingZone) {
            $this->addUsingAlias(ShippingZoneTableMap::COL_ID, $shippingZone->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the shipping_zones table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ShippingZoneTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ShippingZoneTableMap::clearInstancePool();
            ShippingZoneTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ShippingZoneTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ShippingZoneTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ShippingZoneTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ShippingZoneTableMap::clearRelatedInstancePool();

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
        $this->addUsingAlias(ShippingZoneTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(ShippingZoneTableMap::COL_UPDATED_AT);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(ShippingZoneTableMap::COL_UPDATED_AT);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(ShippingZoneTableMap::COL_CREATED_AT);

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
        $this->addUsingAlias(ShippingZoneTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(ShippingZoneTableMap::COL_CREATED_AT);

        return $this;
    }

}
