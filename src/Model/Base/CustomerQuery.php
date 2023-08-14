<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Customer as ChildCustomer;
use Model\CustomerQuery as ChildCustomerQuery;
use Model\Map\CustomerTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `customers` table.
 *
 * @method     ChildCustomerQuery orderById($order = Criteria::ASC) Order by the customer_id column
 * @method     ChildCustomerQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildCustomerQuery orderByAxysAccountId($order = Criteria::ASC) Order by the axys_account_id column
 * @method     ChildCustomerQuery orderByType($order = Criteria::ASC) Order by the customer_type column
 * @method     ChildCustomerQuery orderByFirstName($order = Criteria::ASC) Order by the customer_first_name column
 * @method     ChildCustomerQuery orderByLastName($order = Criteria::ASC) Order by the customer_last_name column
 * @method     ChildCustomerQuery orderByEmail($order = Criteria::ASC) Order by the customer_email column
 * @method     ChildCustomerQuery orderByPhone($order = Criteria::ASC) Order by the customer_phone column
 * @method     ChildCustomerQuery orderByCountryId($order = Criteria::ASC) Order by the country_id column
 * @method     ChildCustomerQuery orderByPrivatization($order = Criteria::ASC) Order by the customer_privatization column
 * @method     ChildCustomerQuery orderByInsert($order = Criteria::ASC) Order by the customer_insert column
 * @method     ChildCustomerQuery orderByUpdate($order = Criteria::ASC) Order by the customer_update column
 * @method     ChildCustomerQuery orderByCreatedAt($order = Criteria::ASC) Order by the customer_created column
 * @method     ChildCustomerQuery orderByUpdatedAt($order = Criteria::ASC) Order by the customer_updated column
 *
 * @method     ChildCustomerQuery groupById() Group by the customer_id column
 * @method     ChildCustomerQuery groupBySiteId() Group by the site_id column
 * @method     ChildCustomerQuery groupByAxysAccountId() Group by the axys_account_id column
 * @method     ChildCustomerQuery groupByType() Group by the customer_type column
 * @method     ChildCustomerQuery groupByFirstName() Group by the customer_first_name column
 * @method     ChildCustomerQuery groupByLastName() Group by the customer_last_name column
 * @method     ChildCustomerQuery groupByEmail() Group by the customer_email column
 * @method     ChildCustomerQuery groupByPhone() Group by the customer_phone column
 * @method     ChildCustomerQuery groupByCountryId() Group by the country_id column
 * @method     ChildCustomerQuery groupByPrivatization() Group by the customer_privatization column
 * @method     ChildCustomerQuery groupByInsert() Group by the customer_insert column
 * @method     ChildCustomerQuery groupByUpdate() Group by the customer_update column
 * @method     ChildCustomerQuery groupByCreatedAt() Group by the customer_created column
 * @method     ChildCustomerQuery groupByUpdatedAt() Group by the customer_updated column
 *
 * @method     ChildCustomerQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCustomerQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCustomerQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCustomerQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildCustomerQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildCustomerQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildCustomer|null findOne(?ConnectionInterface $con = null) Return the first ChildCustomer matching the query
 * @method     ChildCustomer findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildCustomer matching the query, or a new ChildCustomer object populated from the query conditions when no match is found
 *
 * @method     ChildCustomer|null findOneById(int $customer_id) Return the first ChildCustomer filtered by the customer_id column
 * @method     ChildCustomer|null findOneBySiteId(int $site_id) Return the first ChildCustomer filtered by the site_id column
 * @method     ChildCustomer|null findOneByAxysAccountId(int $axys_account_id) Return the first ChildCustomer filtered by the axys_account_id column
 * @method     ChildCustomer|null findOneByType(string $customer_type) Return the first ChildCustomer filtered by the customer_type column
 * @method     ChildCustomer|null findOneByFirstName(string $customer_first_name) Return the first ChildCustomer filtered by the customer_first_name column
 * @method     ChildCustomer|null findOneByLastName(string $customer_last_name) Return the first ChildCustomer filtered by the customer_last_name column
 * @method     ChildCustomer|null findOneByEmail(string $customer_email) Return the first ChildCustomer filtered by the customer_email column
 * @method     ChildCustomer|null findOneByPhone(string $customer_phone) Return the first ChildCustomer filtered by the customer_phone column
 * @method     ChildCustomer|null findOneByCountryId(int $country_id) Return the first ChildCustomer filtered by the country_id column
 * @method     ChildCustomer|null findOneByPrivatization(string $customer_privatization) Return the first ChildCustomer filtered by the customer_privatization column
 * @method     ChildCustomer|null findOneByInsert(string $customer_insert) Return the first ChildCustomer filtered by the customer_insert column
 * @method     ChildCustomer|null findOneByUpdate(string $customer_update) Return the first ChildCustomer filtered by the customer_update column
 * @method     ChildCustomer|null findOneByCreatedAt(string $customer_created) Return the first ChildCustomer filtered by the customer_created column
 * @method     ChildCustomer|null findOneByUpdatedAt(string $customer_updated) Return the first ChildCustomer filtered by the customer_updated column
 *
 * @method     ChildCustomer requirePk($key, ?ConnectionInterface $con = null) Return the ChildCustomer by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCustomer requireOne(?ConnectionInterface $con = null) Return the first ChildCustomer matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCustomer requireOneById(int $customer_id) Return the first ChildCustomer filtered by the customer_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCustomer requireOneBySiteId(int $site_id) Return the first ChildCustomer filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCustomer requireOneByAxysAccountId(int $axys_account_id) Return the first ChildCustomer filtered by the axys_account_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCustomer requireOneByType(string $customer_type) Return the first ChildCustomer filtered by the customer_type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCustomer requireOneByFirstName(string $customer_first_name) Return the first ChildCustomer filtered by the customer_first_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCustomer requireOneByLastName(string $customer_last_name) Return the first ChildCustomer filtered by the customer_last_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCustomer requireOneByEmail(string $customer_email) Return the first ChildCustomer filtered by the customer_email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCustomer requireOneByPhone(string $customer_phone) Return the first ChildCustomer filtered by the customer_phone column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCustomer requireOneByCountryId(int $country_id) Return the first ChildCustomer filtered by the country_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCustomer requireOneByPrivatization(string $customer_privatization) Return the first ChildCustomer filtered by the customer_privatization column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCustomer requireOneByInsert(string $customer_insert) Return the first ChildCustomer filtered by the customer_insert column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCustomer requireOneByUpdate(string $customer_update) Return the first ChildCustomer filtered by the customer_update column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCustomer requireOneByCreatedAt(string $customer_created) Return the first ChildCustomer filtered by the customer_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCustomer requireOneByUpdatedAt(string $customer_updated) Return the first ChildCustomer filtered by the customer_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCustomer[]|Collection find(?ConnectionInterface $con = null) Return ChildCustomer objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildCustomer> find(?ConnectionInterface $con = null) Return ChildCustomer objects based on current ModelCriteria
 *
 * @method     ChildCustomer[]|Collection findById(int|array<int> $customer_id) Return ChildCustomer objects filtered by the customer_id column
 * @psalm-method Collection&\Traversable<ChildCustomer> findById(int|array<int> $customer_id) Return ChildCustomer objects filtered by the customer_id column
 * @method     ChildCustomer[]|Collection findBySiteId(int|array<int> $site_id) Return ChildCustomer objects filtered by the site_id column
 * @psalm-method Collection&\Traversable<ChildCustomer> findBySiteId(int|array<int> $site_id) Return ChildCustomer objects filtered by the site_id column
 * @method     ChildCustomer[]|Collection findByAxysAccountId(int|array<int> $axys_account_id) Return ChildCustomer objects filtered by the axys_account_id column
 * @psalm-method Collection&\Traversable<ChildCustomer> findByAxysAccountId(int|array<int> $axys_account_id) Return ChildCustomer objects filtered by the axys_account_id column
 * @method     ChildCustomer[]|Collection findByType(string|array<string> $customer_type) Return ChildCustomer objects filtered by the customer_type column
 * @psalm-method Collection&\Traversable<ChildCustomer> findByType(string|array<string> $customer_type) Return ChildCustomer objects filtered by the customer_type column
 * @method     ChildCustomer[]|Collection findByFirstName(string|array<string> $customer_first_name) Return ChildCustomer objects filtered by the customer_first_name column
 * @psalm-method Collection&\Traversable<ChildCustomer> findByFirstName(string|array<string> $customer_first_name) Return ChildCustomer objects filtered by the customer_first_name column
 * @method     ChildCustomer[]|Collection findByLastName(string|array<string> $customer_last_name) Return ChildCustomer objects filtered by the customer_last_name column
 * @psalm-method Collection&\Traversable<ChildCustomer> findByLastName(string|array<string> $customer_last_name) Return ChildCustomer objects filtered by the customer_last_name column
 * @method     ChildCustomer[]|Collection findByEmail(string|array<string> $customer_email) Return ChildCustomer objects filtered by the customer_email column
 * @psalm-method Collection&\Traversable<ChildCustomer> findByEmail(string|array<string> $customer_email) Return ChildCustomer objects filtered by the customer_email column
 * @method     ChildCustomer[]|Collection findByPhone(string|array<string> $customer_phone) Return ChildCustomer objects filtered by the customer_phone column
 * @psalm-method Collection&\Traversable<ChildCustomer> findByPhone(string|array<string> $customer_phone) Return ChildCustomer objects filtered by the customer_phone column
 * @method     ChildCustomer[]|Collection findByCountryId(int|array<int> $country_id) Return ChildCustomer objects filtered by the country_id column
 * @psalm-method Collection&\Traversable<ChildCustomer> findByCountryId(int|array<int> $country_id) Return ChildCustomer objects filtered by the country_id column
 * @method     ChildCustomer[]|Collection findByPrivatization(string|array<string> $customer_privatization) Return ChildCustomer objects filtered by the customer_privatization column
 * @psalm-method Collection&\Traversable<ChildCustomer> findByPrivatization(string|array<string> $customer_privatization) Return ChildCustomer objects filtered by the customer_privatization column
 * @method     ChildCustomer[]|Collection findByInsert(string|array<string> $customer_insert) Return ChildCustomer objects filtered by the customer_insert column
 * @psalm-method Collection&\Traversable<ChildCustomer> findByInsert(string|array<string> $customer_insert) Return ChildCustomer objects filtered by the customer_insert column
 * @method     ChildCustomer[]|Collection findByUpdate(string|array<string> $customer_update) Return ChildCustomer objects filtered by the customer_update column
 * @psalm-method Collection&\Traversable<ChildCustomer> findByUpdate(string|array<string> $customer_update) Return ChildCustomer objects filtered by the customer_update column
 * @method     ChildCustomer[]|Collection findByCreatedAt(string|array<string> $customer_created) Return ChildCustomer objects filtered by the customer_created column
 * @psalm-method Collection&\Traversable<ChildCustomer> findByCreatedAt(string|array<string> $customer_created) Return ChildCustomer objects filtered by the customer_created column
 * @method     ChildCustomer[]|Collection findByUpdatedAt(string|array<string> $customer_updated) Return ChildCustomer objects filtered by the customer_updated column
 * @psalm-method Collection&\Traversable<ChildCustomer> findByUpdatedAt(string|array<string> $customer_updated) Return ChildCustomer objects filtered by the customer_updated column
 *
 * @method     ChildCustomer[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildCustomer> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class CustomerQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\CustomerQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Customer', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCustomerQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCustomerQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildCustomerQuery) {
            return $criteria;
        }
        $query = new ChildCustomerQuery();
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
     * @return ChildCustomer|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CustomerTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = CustomerTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildCustomer A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT customer_id, site_id, axys_account_id, customer_type, customer_first_name, customer_last_name, customer_email, customer_phone, country_id, customer_privatization, customer_insert, customer_update, customer_created, customer_updated FROM customers WHERE customer_id = :p0';
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
            /** @var ChildCustomer $obj */
            $obj = new ChildCustomer();
            $obj->hydrate($row);
            CustomerTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildCustomer|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(CustomerTableMap::COL_CUSTOMER_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(CustomerTableMap::COL_CUSTOMER_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the customer_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE customer_id = 1234
     * $query->filterById(array(12, 34)); // WHERE customer_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE customer_id > 12
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
                $this->addUsingAlias(CustomerTableMap::COL_CUSTOMER_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CustomerTableMap::COL_CUSTOMER_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CustomerTableMap::COL_CUSTOMER_ID, $id, $comparison);

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
                $this->addUsingAlias(CustomerTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(CustomerTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CustomerTableMap::COL_SITE_ID, $siteId, $comparison);

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
                $this->addUsingAlias(CustomerTableMap::COL_AXYS_ACCOUNT_ID, $axysAccountId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($axysAccountId['max'])) {
                $this->addUsingAlias(CustomerTableMap::COL_AXYS_ACCOUNT_ID, $axysAccountId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CustomerTableMap::COL_AXYS_ACCOUNT_ID, $axysAccountId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the customer_type column
     *
     * Example usage:
     * <code>
     * $query->filterByType('fooValue');   // WHERE customer_type = 'fooValue'
     * $query->filterByType('%fooValue%', Criteria::LIKE); // WHERE customer_type LIKE '%fooValue%'
     * $query->filterByType(['foo', 'bar']); // WHERE customer_type IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $type The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByType($type = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($type)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CustomerTableMap::COL_CUSTOMER_TYPE, $type, $comparison);

        return $this;
    }

    /**
     * Filter the query on the customer_first_name column
     *
     * Example usage:
     * <code>
     * $query->filterByFirstName('fooValue');   // WHERE customer_first_name = 'fooValue'
     * $query->filterByFirstName('%fooValue%', Criteria::LIKE); // WHERE customer_first_name LIKE '%fooValue%'
     * $query->filterByFirstName(['foo', 'bar']); // WHERE customer_first_name IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $firstName The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFirstName($firstName = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($firstName)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CustomerTableMap::COL_CUSTOMER_FIRST_NAME, $firstName, $comparison);

        return $this;
    }

    /**
     * Filter the query on the customer_last_name column
     *
     * Example usage:
     * <code>
     * $query->filterByLastName('fooValue');   // WHERE customer_last_name = 'fooValue'
     * $query->filterByLastName('%fooValue%', Criteria::LIKE); // WHERE customer_last_name LIKE '%fooValue%'
     * $query->filterByLastName(['foo', 'bar']); // WHERE customer_last_name IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $lastName The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByLastName($lastName = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lastName)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CustomerTableMap::COL_CUSTOMER_LAST_NAME, $lastName, $comparison);

        return $this;
    }

    /**
     * Filter the query on the customer_email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE customer_email = 'fooValue'
     * $query->filterByEmail('%fooValue%', Criteria::LIKE); // WHERE customer_email LIKE '%fooValue%'
     * $query->filterByEmail(['foo', 'bar']); // WHERE customer_email IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $email The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEmail($email = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CustomerTableMap::COL_CUSTOMER_EMAIL, $email, $comparison);

        return $this;
    }

    /**
     * Filter the query on the customer_phone column
     *
     * Example usage:
     * <code>
     * $query->filterByPhone('fooValue');   // WHERE customer_phone = 'fooValue'
     * $query->filterByPhone('%fooValue%', Criteria::LIKE); // WHERE customer_phone LIKE '%fooValue%'
     * $query->filterByPhone(['foo', 'bar']); // WHERE customer_phone IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $phone The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPhone($phone = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($phone)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CustomerTableMap::COL_CUSTOMER_PHONE, $phone, $comparison);

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
                $this->addUsingAlias(CustomerTableMap::COL_COUNTRY_ID, $countryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($countryId['max'])) {
                $this->addUsingAlias(CustomerTableMap::COL_COUNTRY_ID, $countryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CustomerTableMap::COL_COUNTRY_ID, $countryId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the customer_privatization column
     *
     * Example usage:
     * <code>
     * $query->filterByPrivatization('2011-03-14'); // WHERE customer_privatization = '2011-03-14'
     * $query->filterByPrivatization('now'); // WHERE customer_privatization = '2011-03-14'
     * $query->filterByPrivatization(array('max' => 'yesterday')); // WHERE customer_privatization > '2011-03-13'
     * </code>
     *
     * @param mixed $privatization The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPrivatization($privatization = null, ?string $comparison = null)
    {
        if (is_array($privatization)) {
            $useMinMax = false;
            if (isset($privatization['min'])) {
                $this->addUsingAlias(CustomerTableMap::COL_CUSTOMER_PRIVATIZATION, $privatization['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($privatization['max'])) {
                $this->addUsingAlias(CustomerTableMap::COL_CUSTOMER_PRIVATIZATION, $privatization['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CustomerTableMap::COL_CUSTOMER_PRIVATIZATION, $privatization, $comparison);

        return $this;
    }

    /**
     * Filter the query on the customer_insert column
     *
     * Example usage:
     * <code>
     * $query->filterByInsert('2011-03-14'); // WHERE customer_insert = '2011-03-14'
     * $query->filterByInsert('now'); // WHERE customer_insert = '2011-03-14'
     * $query->filterByInsert(array('max' => 'yesterday')); // WHERE customer_insert > '2011-03-13'
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
                $this->addUsingAlias(CustomerTableMap::COL_CUSTOMER_INSERT, $insert['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($insert['max'])) {
                $this->addUsingAlias(CustomerTableMap::COL_CUSTOMER_INSERT, $insert['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CustomerTableMap::COL_CUSTOMER_INSERT, $insert, $comparison);

        return $this;
    }

    /**
     * Filter the query on the customer_update column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdate('2011-03-14'); // WHERE customer_update = '2011-03-14'
     * $query->filterByUpdate('now'); // WHERE customer_update = '2011-03-14'
     * $query->filterByUpdate(array('max' => 'yesterday')); // WHERE customer_update > '2011-03-13'
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
                $this->addUsingAlias(CustomerTableMap::COL_CUSTOMER_UPDATE, $update['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($update['max'])) {
                $this->addUsingAlias(CustomerTableMap::COL_CUSTOMER_UPDATE, $update['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CustomerTableMap::COL_CUSTOMER_UPDATE, $update, $comparison);

        return $this;
    }

    /**
     * Filter the query on the customer_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE customer_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE customer_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE customer_created > '2011-03-13'
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
                $this->addUsingAlias(CustomerTableMap::COL_CUSTOMER_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(CustomerTableMap::COL_CUSTOMER_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CustomerTableMap::COL_CUSTOMER_CREATED, $createdAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the customer_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE customer_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE customer_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE customer_updated > '2011-03-13'
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
                $this->addUsingAlias(CustomerTableMap::COL_CUSTOMER_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(CustomerTableMap::COL_CUSTOMER_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CustomerTableMap::COL_CUSTOMER_UPDATED, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Exclude object from result
     *
     * @param ChildCustomer $customer Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($customer = null)
    {
        if ($customer) {
            $this->addUsingAlias(CustomerTableMap::COL_CUSTOMER_ID, $customer->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the customers table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CustomerTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CustomerTableMap::clearInstancePool();
            CustomerTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(CustomerTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CustomerTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            CustomerTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CustomerTableMap::clearRelatedInstancePool();

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
        $this->addUsingAlias(CustomerTableMap::COL_CUSTOMER_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(CustomerTableMap::COL_CUSTOMER_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(CustomerTableMap::COL_CUSTOMER_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(CustomerTableMap::COL_CUSTOMER_CREATED);

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
        $this->addUsingAlias(CustomerTableMap::COL_CUSTOMER_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(CustomerTableMap::COL_CUSTOMER_CREATED);

        return $this;
    }

}
