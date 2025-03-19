<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\ShippingZonesCountries as ChildShippingZonesCountries;
use Model\ShippingZonesCountriesQuery as ChildShippingZonesCountriesQuery;
use Model\Map\ShippingZonesCountriesTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `shipping_zones_countries` table.
 *
 * @method     ChildShippingZonesCountriesQuery orderByShippingZoneId($order = Criteria::ASC) Order by the shipping_zone_id column
 * @method     ChildShippingZonesCountriesQuery orderByCountryId($order = Criteria::ASC) Order by the country_id column
 *
 * @method     ChildShippingZonesCountriesQuery groupByShippingZoneId() Group by the shipping_zone_id column
 * @method     ChildShippingZonesCountriesQuery groupByCountryId() Group by the country_id column
 *
 * @method     ChildShippingZonesCountriesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildShippingZonesCountriesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildShippingZonesCountriesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildShippingZonesCountriesQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildShippingZonesCountriesQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildShippingZonesCountriesQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildShippingZonesCountriesQuery leftJoinShippingZone($relationAlias = null) Adds a LEFT JOIN clause to the query using the ShippingZone relation
 * @method     ChildShippingZonesCountriesQuery rightJoinShippingZone($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ShippingZone relation
 * @method     ChildShippingZonesCountriesQuery innerJoinShippingZone($relationAlias = null) Adds a INNER JOIN clause to the query using the ShippingZone relation
 *
 * @method     ChildShippingZonesCountriesQuery joinWithShippingZone($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ShippingZone relation
 *
 * @method     ChildShippingZonesCountriesQuery leftJoinWithShippingZone() Adds a LEFT JOIN clause and with to the query using the ShippingZone relation
 * @method     ChildShippingZonesCountriesQuery rightJoinWithShippingZone() Adds a RIGHT JOIN clause and with to the query using the ShippingZone relation
 * @method     ChildShippingZonesCountriesQuery innerJoinWithShippingZone() Adds a INNER JOIN clause and with to the query using the ShippingZone relation
 *
 * @method     ChildShippingZonesCountriesQuery leftJoinCountry($relationAlias = null) Adds a LEFT JOIN clause to the query using the Country relation
 * @method     ChildShippingZonesCountriesQuery rightJoinCountry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Country relation
 * @method     ChildShippingZonesCountriesQuery innerJoinCountry($relationAlias = null) Adds a INNER JOIN clause to the query using the Country relation
 *
 * @method     ChildShippingZonesCountriesQuery joinWithCountry($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Country relation
 *
 * @method     ChildShippingZonesCountriesQuery leftJoinWithCountry() Adds a LEFT JOIN clause and with to the query using the Country relation
 * @method     ChildShippingZonesCountriesQuery rightJoinWithCountry() Adds a RIGHT JOIN clause and with to the query using the Country relation
 * @method     ChildShippingZonesCountriesQuery innerJoinWithCountry() Adds a INNER JOIN clause and with to the query using the Country relation
 *
 * @method     \Model\ShippingZoneQuery|\Model\CountryQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildShippingZonesCountries|null findOne(?ConnectionInterface $con = null) Return the first ChildShippingZonesCountries matching the query
 * @method     ChildShippingZonesCountries findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildShippingZonesCountries matching the query, or a new ChildShippingZonesCountries object populated from the query conditions when no match is found
 *
 * @method     ChildShippingZonesCountries|null findOneByShippingZoneId(int $shipping_zone_id) Return the first ChildShippingZonesCountries filtered by the shipping_zone_id column
 * @method     ChildShippingZonesCountries|null findOneByCountryId(int $country_id) Return the first ChildShippingZonesCountries filtered by the country_id column
 *
 * @method     ChildShippingZonesCountries requirePk($key, ?ConnectionInterface $con = null) Return the ChildShippingZonesCountries by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingZonesCountries requireOne(?ConnectionInterface $con = null) Return the first ChildShippingZonesCountries matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildShippingZonesCountries requireOneByShippingZoneId(int $shipping_zone_id) Return the first ChildShippingZonesCountries filtered by the shipping_zone_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildShippingZonesCountries requireOneByCountryId(int $country_id) Return the first ChildShippingZonesCountries filtered by the country_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildShippingZonesCountries[]|Collection find(?ConnectionInterface $con = null) Return ChildShippingZonesCountries objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildShippingZonesCountries> find(?ConnectionInterface $con = null) Return ChildShippingZonesCountries objects based on current ModelCriteria
 *
 * @method     ChildShippingZonesCountries[]|Collection findByShippingZoneId(int|array<int> $shipping_zone_id) Return ChildShippingZonesCountries objects filtered by the shipping_zone_id column
 * @psalm-method Collection&\Traversable<ChildShippingZonesCountries> findByShippingZoneId(int|array<int> $shipping_zone_id) Return ChildShippingZonesCountries objects filtered by the shipping_zone_id column
 * @method     ChildShippingZonesCountries[]|Collection findByCountryId(int|array<int> $country_id) Return ChildShippingZonesCountries objects filtered by the country_id column
 * @psalm-method Collection&\Traversable<ChildShippingZonesCountries> findByCountryId(int|array<int> $country_id) Return ChildShippingZonesCountries objects filtered by the country_id column
 *
 * @method     ChildShippingZonesCountries[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildShippingZonesCountries> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class ShippingZonesCountriesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\ShippingZonesCountriesQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\ShippingZonesCountries', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildShippingZonesCountriesQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildShippingZonesCountriesQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildShippingZonesCountriesQuery) {
            return $criteria;
        }
        $query = new ChildShippingZonesCountriesQuery();
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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array[$shipping_zone_id, $country_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildShippingZonesCountries|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ShippingZonesCountriesTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ShippingZonesCountriesTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildShippingZonesCountries A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT shipping_zone_id, country_id FROM shipping_zones_countries WHERE shipping_zone_id = :p0 AND country_id = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildShippingZonesCountries $obj */
            $obj = new ChildShippingZonesCountries();
            $obj->hydrate($row);
            ShippingZonesCountriesTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildShippingZonesCountries|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
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
        $this->addUsingAlias(ShippingZonesCountriesTableMap::COL_SHIPPING_ZONE_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(ShippingZonesCountriesTableMap::COL_COUNTRY_ID, $key[1], Criteria::EQUAL);

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
        if (empty($keys)) {
            $this->add(null, '1<>1', Criteria::CUSTOM);

            return $this;
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(ShippingZonesCountriesTableMap::COL_SHIPPING_ZONE_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(ShippingZonesCountriesTableMap::COL_COUNTRY_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the shipping_zone_id column
     *
     * Example usage:
     * <code>
     * $query->filterByShippingZoneId(1234); // WHERE shipping_zone_id = 1234
     * $query->filterByShippingZoneId(array(12, 34)); // WHERE shipping_zone_id IN (12, 34)
     * $query->filterByShippingZoneId(array('min' => 12)); // WHERE shipping_zone_id > 12
     * </code>
     *
     * @see       filterByShippingZone()
     *
     * @param mixed $shippingZoneId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByShippingZoneId($shippingZoneId = null, ?string $comparison = null)
    {
        if (is_array($shippingZoneId)) {
            $useMinMax = false;
            if (isset($shippingZoneId['min'])) {
                $this->addUsingAlias(ShippingZonesCountriesTableMap::COL_SHIPPING_ZONE_ID, $shippingZoneId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($shippingZoneId['max'])) {
                $this->addUsingAlias(ShippingZonesCountriesTableMap::COL_SHIPPING_ZONE_ID, $shippingZoneId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ShippingZonesCountriesTableMap::COL_SHIPPING_ZONE_ID, $shippingZoneId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the country_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCountryId(1234); // WHERE country_id = 1234
     * $query->filterByCountryId(array(12, 34)); // WHERE country_id IN (12, 34)
     * $query->filterByCountryId(array('min' => 12)); // WHERE country_id > 12
     * </code>
     *
     * @see       filterByCountry()
     *
     * @param mixed $countryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCountryId($countryId = null, ?string $comparison = null)
    {
        if (is_array($countryId)) {
            $useMinMax = false;
            if (isset($countryId['min'])) {
                $this->addUsingAlias(ShippingZonesCountriesTableMap::COL_COUNTRY_ID, $countryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($countryId['max'])) {
                $this->addUsingAlias(ShippingZonesCountriesTableMap::COL_COUNTRY_ID, $countryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ShippingZonesCountriesTableMap::COL_COUNTRY_ID, $countryId, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \Model\ShippingZone object
     *
     * @param \Model\ShippingZone|ObjectCollection $shippingZone The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByShippingZone($shippingZone, ?string $comparison = null)
    {
        if ($shippingZone instanceof \Model\ShippingZone) {
            return $this
                ->addUsingAlias(ShippingZonesCountriesTableMap::COL_SHIPPING_ZONE_ID, $shippingZone->getId(), $comparison);
        } elseif ($shippingZone instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(ShippingZonesCountriesTableMap::COL_SHIPPING_ZONE_ID, $shippingZone->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByShippingZone() only accepts arguments of type \Model\ShippingZone or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ShippingZone relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinShippingZone(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ShippingZone');

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
            $this->addJoinObject($join, 'ShippingZone');
        }

        return $this;
    }

    /**
     * Use the ShippingZone relation ShippingZone object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\ShippingZoneQuery A secondary query class using the current class as primary query
     */
    public function useShippingZoneQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinShippingZone($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ShippingZone', '\Model\ShippingZoneQuery');
    }

    /**
     * Use the ShippingZone relation ShippingZone object
     *
     * @param callable(\Model\ShippingZoneQuery):\Model\ShippingZoneQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withShippingZoneQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useShippingZoneQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to ShippingZone table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\ShippingZoneQuery The inner query object of the EXISTS statement
     */
    public function useShippingZoneExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\ShippingZoneQuery */
        $q = $this->useExistsQuery('ShippingZone', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to ShippingZone table for a NOT EXISTS query.
     *
     * @see useShippingZoneExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\ShippingZoneQuery The inner query object of the NOT EXISTS statement
     */
    public function useShippingZoneNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\ShippingZoneQuery */
        $q = $this->useExistsQuery('ShippingZone', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to ShippingZone table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\ShippingZoneQuery The inner query object of the IN statement
     */
    public function useInShippingZoneQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\ShippingZoneQuery */
        $q = $this->useInQuery('ShippingZone', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to ShippingZone table for a NOT IN query.
     *
     * @see useShippingZoneInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\ShippingZoneQuery The inner query object of the NOT IN statement
     */
    public function useNotInShippingZoneQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\ShippingZoneQuery */
        $q = $this->useInQuery('ShippingZone', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Country object
     *
     * @param \Model\Country|ObjectCollection $country The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCountry($country, ?string $comparison = null)
    {
        if ($country instanceof \Model\Country) {
            return $this
                ->addUsingAlias(ShippingZonesCountriesTableMap::COL_COUNTRY_ID, $country->getId(), $comparison);
        } elseif ($country instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(ShippingZonesCountriesTableMap::COL_COUNTRY_ID, $country->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByCountry() only accepts arguments of type \Model\Country or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Country relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinCountry(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Country');

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
            $this->addJoinObject($join, 'Country');
        }

        return $this;
    }

    /**
     * Use the Country relation Country object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\CountryQuery A secondary query class using the current class as primary query
     */
    public function useCountryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCountry($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Country', '\Model\CountryQuery');
    }

    /**
     * Use the Country relation Country object
     *
     * @param callable(\Model\CountryQuery):\Model\CountryQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withCountryQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useCountryQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Country table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\CountryQuery The inner query object of the EXISTS statement
     */
    public function useCountryExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\CountryQuery */
        $q = $this->useExistsQuery('Country', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Country table for a NOT EXISTS query.
     *
     * @see useCountryExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\CountryQuery The inner query object of the NOT EXISTS statement
     */
    public function useCountryNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\CountryQuery */
        $q = $this->useExistsQuery('Country', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Country table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\CountryQuery The inner query object of the IN statement
     */
    public function useInCountryQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\CountryQuery */
        $q = $this->useInQuery('Country', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Country table for a NOT IN query.
     *
     * @see useCountryInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\CountryQuery The inner query object of the NOT IN statement
     */
    public function useNotInCountryQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\CountryQuery */
        $q = $this->useInQuery('Country', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Exclude object from result
     *
     * @param ChildShippingZonesCountries $shippingZonesCountries Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($shippingZonesCountries = null)
    {
        if ($shippingZonesCountries) {
            $this->addCond('pruneCond0', $this->getAliasedColName(ShippingZonesCountriesTableMap::COL_SHIPPING_ZONE_ID), $shippingZonesCountries->getShippingZoneId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(ShippingZonesCountriesTableMap::COL_COUNTRY_ID), $shippingZonesCountries->getCountryId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the shipping_zones_countries table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ShippingZonesCountriesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ShippingZonesCountriesTableMap::clearInstancePool();
            ShippingZonesCountriesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ShippingZonesCountriesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ShippingZonesCountriesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ShippingZonesCountriesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ShippingZonesCountriesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

}
