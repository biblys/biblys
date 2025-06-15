<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Country as ChildCountry;
use Model\CountryQuery as ChildCountryQuery;
use Model\Map\CountryTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `countries` table.
 *
 * @method     ChildCountryQuery orderById($order = Criteria::ASC) Order by the country_id column
 * @method     ChildCountryQuery orderByCode($order = Criteria::ASC) Order by the country_code column
 * @method     ChildCountryQuery orderByName($order = Criteria::ASC) Order by the country_name column
 * @method     ChildCountryQuery orderByNameEn($order = Criteria::ASC) Order by the country_name_en column
 * @method     ChildCountryQuery orderByCreatedAt($order = Criteria::ASC) Order by the country_created column
 * @method     ChildCountryQuery orderByUpdatedAt($order = Criteria::ASC) Order by the country_updated column
 *
 * @method     ChildCountryQuery groupById() Group by the country_id column
 * @method     ChildCountryQuery groupByCode() Group by the country_code column
 * @method     ChildCountryQuery groupByName() Group by the country_name column
 * @method     ChildCountryQuery groupByNameEn() Group by the country_name_en column
 * @method     ChildCountryQuery groupByCreatedAt() Group by the country_created column
 * @method     ChildCountryQuery groupByUpdatedAt() Group by the country_updated column
 *
 * @method     ChildCountryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCountryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCountryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCountryQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildCountryQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildCountryQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildCountryQuery leftJoinOrder($relationAlias = null) Adds a LEFT JOIN clause to the query using the Order relation
 * @method     ChildCountryQuery rightJoinOrder($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Order relation
 * @method     ChildCountryQuery innerJoinOrder($relationAlias = null) Adds a INNER JOIN clause to the query using the Order relation
 *
 * @method     ChildCountryQuery joinWithOrder($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Order relation
 *
 * @method     ChildCountryQuery leftJoinWithOrder() Adds a LEFT JOIN clause and with to the query using the Order relation
 * @method     ChildCountryQuery rightJoinWithOrder() Adds a RIGHT JOIN clause and with to the query using the Order relation
 * @method     ChildCountryQuery innerJoinWithOrder() Adds a INNER JOIN clause and with to the query using the Order relation
 *
 * @method     ChildCountryQuery leftJoinShippingZonesCountries($relationAlias = null) Adds a LEFT JOIN clause to the query using the ShippingZonesCountries relation
 * @method     ChildCountryQuery rightJoinShippingZonesCountries($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ShippingZonesCountries relation
 * @method     ChildCountryQuery innerJoinShippingZonesCountries($relationAlias = null) Adds a INNER JOIN clause to the query using the ShippingZonesCountries relation
 *
 * @method     ChildCountryQuery joinWithShippingZonesCountries($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ShippingZonesCountries relation
 *
 * @method     ChildCountryQuery leftJoinWithShippingZonesCountries() Adds a LEFT JOIN clause and with to the query using the ShippingZonesCountries relation
 * @method     ChildCountryQuery rightJoinWithShippingZonesCountries() Adds a RIGHT JOIN clause and with to the query using the ShippingZonesCountries relation
 * @method     ChildCountryQuery innerJoinWithShippingZonesCountries() Adds a INNER JOIN clause and with to the query using the ShippingZonesCountries relation
 *
 * @method     \Model\OrderQuery|\Model\ShippingZonesCountriesQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildCountry|null findOne(?ConnectionInterface $con = null) Return the first ChildCountry matching the query
 * @method     ChildCountry findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildCountry matching the query, or a new ChildCountry object populated from the query conditions when no match is found
 *
 * @method     ChildCountry|null findOneById(int $country_id) Return the first ChildCountry filtered by the country_id column
 * @method     ChildCountry|null findOneByCode(string $country_code) Return the first ChildCountry filtered by the country_code column
 * @method     ChildCountry|null findOneByName(string $country_name) Return the first ChildCountry filtered by the country_name column
 * @method     ChildCountry|null findOneByNameEn(string $country_name_en) Return the first ChildCountry filtered by the country_name_en column
 * @method     ChildCountry|null findOneByCreatedAt(string $country_created) Return the first ChildCountry filtered by the country_created column
 * @method     ChildCountry|null findOneByUpdatedAt(string $country_updated) Return the first ChildCountry filtered by the country_updated column
 *
 * @method     ChildCountry requirePk($key, ?ConnectionInterface $con = null) Return the ChildCountry by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCountry requireOne(?ConnectionInterface $con = null) Return the first ChildCountry matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCountry requireOneById(int $country_id) Return the first ChildCountry filtered by the country_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCountry requireOneByCode(string $country_code) Return the first ChildCountry filtered by the country_code column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCountry requireOneByName(string $country_name) Return the first ChildCountry filtered by the country_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCountry requireOneByNameEn(string $country_name_en) Return the first ChildCountry filtered by the country_name_en column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCountry requireOneByCreatedAt(string $country_created) Return the first ChildCountry filtered by the country_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCountry requireOneByUpdatedAt(string $country_updated) Return the first ChildCountry filtered by the country_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCountry[]|Collection find(?ConnectionInterface $con = null) Return ChildCountry objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildCountry> find(?ConnectionInterface $con = null) Return ChildCountry objects based on current ModelCriteria
 *
 * @method     ChildCountry[]|Collection findById(int|array<int> $country_id) Return ChildCountry objects filtered by the country_id column
 * @psalm-method Collection&\Traversable<ChildCountry> findById(int|array<int> $country_id) Return ChildCountry objects filtered by the country_id column
 * @method     ChildCountry[]|Collection findByCode(string|array<string> $country_code) Return ChildCountry objects filtered by the country_code column
 * @psalm-method Collection&\Traversable<ChildCountry> findByCode(string|array<string> $country_code) Return ChildCountry objects filtered by the country_code column
 * @method     ChildCountry[]|Collection findByName(string|array<string> $country_name) Return ChildCountry objects filtered by the country_name column
 * @psalm-method Collection&\Traversable<ChildCountry> findByName(string|array<string> $country_name) Return ChildCountry objects filtered by the country_name column
 * @method     ChildCountry[]|Collection findByNameEn(string|array<string> $country_name_en) Return ChildCountry objects filtered by the country_name_en column
 * @psalm-method Collection&\Traversable<ChildCountry> findByNameEn(string|array<string> $country_name_en) Return ChildCountry objects filtered by the country_name_en column
 * @method     ChildCountry[]|Collection findByCreatedAt(string|array<string> $country_created) Return ChildCountry objects filtered by the country_created column
 * @psalm-method Collection&\Traversable<ChildCountry> findByCreatedAt(string|array<string> $country_created) Return ChildCountry objects filtered by the country_created column
 * @method     ChildCountry[]|Collection findByUpdatedAt(string|array<string> $country_updated) Return ChildCountry objects filtered by the country_updated column
 * @psalm-method Collection&\Traversable<ChildCountry> findByUpdatedAt(string|array<string> $country_updated) Return ChildCountry objects filtered by the country_updated column
 *
 * @method     ChildCountry[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildCountry> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class CountryQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\CountryQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Country', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCountryQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCountryQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildCountryQuery) {
            return $criteria;
        }
        $query = new ChildCountryQuery();
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
     * @return ChildCountry|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CountryTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = CountryTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildCountry A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT country_id, country_code, country_name, country_name_en, country_created, country_updated FROM countries WHERE country_id = :p0';
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
            /** @var ChildCountry $obj */
            $obj = new ChildCountry();
            $obj->hydrate($row);
            CountryTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildCountry|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(CountryTableMap::COL_COUNTRY_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(CountryTableMap::COL_COUNTRY_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the country_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE country_id = 1234
     * $query->filterById(array(12, 34)); // WHERE country_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE country_id > 12
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
                $this->addUsingAlias(CountryTableMap::COL_COUNTRY_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CountryTableMap::COL_COUNTRY_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CountryTableMap::COL_COUNTRY_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the country_code column
     *
     * Example usage:
     * <code>
     * $query->filterByCode('fooValue');   // WHERE country_code = 'fooValue'
     * $query->filterByCode('%fooValue%', Criteria::LIKE); // WHERE country_code LIKE '%fooValue%'
     * $query->filterByCode(['foo', 'bar']); // WHERE country_code IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $code The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCode($code = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($code)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CountryTableMap::COL_COUNTRY_CODE, $code, $comparison);

        return $this;
    }

    /**
     * Filter the query on the country_name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE country_name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE country_name LIKE '%fooValue%'
     * $query->filterByName(['foo', 'bar']); // WHERE country_name IN ('foo', 'bar')
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

        $this->addUsingAlias(CountryTableMap::COL_COUNTRY_NAME, $name, $comparison);

        return $this;
    }

    /**
     * Filter the query on the country_name_en column
     *
     * Example usage:
     * <code>
     * $query->filterByNameEn('fooValue');   // WHERE country_name_en = 'fooValue'
     * $query->filterByNameEn('%fooValue%', Criteria::LIKE); // WHERE country_name_en LIKE '%fooValue%'
     * $query->filterByNameEn(['foo', 'bar']); // WHERE country_name_en IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $nameEn The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByNameEn($nameEn = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nameEn)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CountryTableMap::COL_COUNTRY_NAME_EN, $nameEn, $comparison);

        return $this;
    }

    /**
     * Filter the query on the country_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE country_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE country_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE country_created > '2011-03-13'
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
                $this->addUsingAlias(CountryTableMap::COL_COUNTRY_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(CountryTableMap::COL_COUNTRY_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CountryTableMap::COL_COUNTRY_CREATED, $createdAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the country_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE country_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE country_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE country_updated > '2011-03-13'
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
                $this->addUsingAlias(CountryTableMap::COL_COUNTRY_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(CountryTableMap::COL_COUNTRY_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CountryTableMap::COL_COUNTRY_UPDATED, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \Model\Order object
     *
     * @param \Model\Order|ObjectCollection $order the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByOrder($order, ?string $comparison = null)
    {
        if ($order instanceof \Model\Order) {
            $this
                ->addUsingAlias(CountryTableMap::COL_COUNTRY_ID, $order->getCountryId(), $comparison);

            return $this;
        } elseif ($order instanceof ObjectCollection) {
            $this
                ->useOrderQuery()
                ->filterByPrimaryKeys($order->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByOrder() only accepts arguments of type \Model\Order or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Order relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinOrder(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Order');

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
            $this->addJoinObject($join, 'Order');
        }

        return $this;
    }

    /**
     * Use the Order relation Order object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\OrderQuery A secondary query class using the current class as primary query
     */
    public function useOrderQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinOrder($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Order', '\Model\OrderQuery');
    }

    /**
     * Use the Order relation Order object
     *
     * @param callable(\Model\OrderQuery):\Model\OrderQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withOrderQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useOrderQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Order table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\OrderQuery The inner query object of the EXISTS statement
     */
    public function useOrderExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\OrderQuery */
        $q = $this->useExistsQuery('Order', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Order table for a NOT EXISTS query.
     *
     * @see useOrderExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\OrderQuery The inner query object of the NOT EXISTS statement
     */
    public function useOrderNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\OrderQuery */
        $q = $this->useExistsQuery('Order', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Order table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\OrderQuery The inner query object of the IN statement
     */
    public function useInOrderQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\OrderQuery */
        $q = $this->useInQuery('Order', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Order table for a NOT IN query.
     *
     * @see useOrderInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\OrderQuery The inner query object of the NOT IN statement
     */
    public function useNotInOrderQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\OrderQuery */
        $q = $this->useInQuery('Order', $modelAlias, $queryClass, 'NOT IN');
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
                ->addUsingAlias(CountryTableMap::COL_COUNTRY_ID, $shippingZonesCountries->getCountryId(), $comparison);

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
     * Filter the query by a related ShippingZone object
     * using the shipping_zones_countries table as cross reference
     *
     * @param ShippingZone $shippingZone the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL and Criteria::IN for queries
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByShippingZone($shippingZone, string $comparison = null)
    {
        $this
            ->useShippingZonesCountriesQuery()
            ->filterByShippingZone($shippingZone, $comparison)
            ->endUse();

        return $this;
    }

    /**
     * Exclude object from result
     *
     * @param ChildCountry $country Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($country = null)
    {
        if ($country) {
            $this->addUsingAlias(CountryTableMap::COL_COUNTRY_ID, $country->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the countries table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CountryTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CountryTableMap::clearInstancePool();
            CountryTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(CountryTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CountryTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            CountryTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CountryTableMap::clearRelatedInstancePool();

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
        $this->addUsingAlias(CountryTableMap::COL_COUNTRY_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(CountryTableMap::COL_COUNTRY_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(CountryTableMap::COL_COUNTRY_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(CountryTableMap::COL_COUNTRY_CREATED);

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
        $this->addUsingAlias(CountryTableMap::COL_COUNTRY_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(CountryTableMap::COL_COUNTRY_CREATED);

        return $this;
    }

}
