<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Supplier as ChildSupplier;
use Model\SupplierQuery as ChildSupplierQuery;
use Model\Map\SupplierTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'suppliers' table.
 *
 *
 *
 * @method     ChildSupplierQuery orderById($order = Criteria::ASC) Order by the supplier_id column
 * @method     ChildSupplierQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildSupplierQuery orderByName($order = Criteria::ASC) Order by the supplier_name column
 * @method     ChildSupplierQuery orderByGln($order = Criteria::ASC) Order by the supplier_gln column
 * @method     ChildSupplierQuery orderByRemise($order = Criteria::ASC) Order by the supplier_remise column
 * @method     ChildSupplierQuery orderByNotva($order = Criteria::ASC) Order by the supplier_notva column
 * @method     ChildSupplierQuery orderByOnOrder($order = Criteria::ASC) Order by the supplier_on_order column
 * @method     ChildSupplierQuery orderByInsert($order = Criteria::ASC) Order by the supplier_insert column
 * @method     ChildSupplierQuery orderByUpdate($order = Criteria::ASC) Order by the supplier_update column
 * @method     ChildSupplierQuery orderByCreatedAt($order = Criteria::ASC) Order by the supplier_created column
 * @method     ChildSupplierQuery orderByUpdatedAt($order = Criteria::ASC) Order by the supplier_updated column
 * @method     ChildSupplierQuery orderByDeletedAt($order = Criteria::ASC) Order by the supplier_deleted column
 *
 * @method     ChildSupplierQuery groupById() Group by the supplier_id column
 * @method     ChildSupplierQuery groupBySiteId() Group by the site_id column
 * @method     ChildSupplierQuery groupByName() Group by the supplier_name column
 * @method     ChildSupplierQuery groupByGln() Group by the supplier_gln column
 * @method     ChildSupplierQuery groupByRemise() Group by the supplier_remise column
 * @method     ChildSupplierQuery groupByNotva() Group by the supplier_notva column
 * @method     ChildSupplierQuery groupByOnOrder() Group by the supplier_on_order column
 * @method     ChildSupplierQuery groupByInsert() Group by the supplier_insert column
 * @method     ChildSupplierQuery groupByUpdate() Group by the supplier_update column
 * @method     ChildSupplierQuery groupByCreatedAt() Group by the supplier_created column
 * @method     ChildSupplierQuery groupByUpdatedAt() Group by the supplier_updated column
 * @method     ChildSupplierQuery groupByDeletedAt() Group by the supplier_deleted column
 *
 * @method     ChildSupplierQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSupplierQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSupplierQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSupplierQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildSupplierQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildSupplierQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildSupplier|null findOne(ConnectionInterface $con = null) Return the first ChildSupplier matching the query
 * @method     ChildSupplier findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSupplier matching the query, or a new ChildSupplier object populated from the query conditions when no match is found
 *
 * @method     ChildSupplier|null findOneById(int $supplier_id) Return the first ChildSupplier filtered by the supplier_id column
 * @method     ChildSupplier|null findOneBySiteId(int $site_id) Return the first ChildSupplier filtered by the site_id column
 * @method     ChildSupplier|null findOneByName(string $supplier_name) Return the first ChildSupplier filtered by the supplier_name column
 * @method     ChildSupplier|null findOneByGln(string $supplier_gln) Return the first ChildSupplier filtered by the supplier_gln column
 * @method     ChildSupplier|null findOneByRemise(int $supplier_remise) Return the first ChildSupplier filtered by the supplier_remise column
 * @method     ChildSupplier|null findOneByNotva(boolean $supplier_notva) Return the first ChildSupplier filtered by the supplier_notva column
 * @method     ChildSupplier|null findOneByOnOrder(boolean $supplier_on_order) Return the first ChildSupplier filtered by the supplier_on_order column
 * @method     ChildSupplier|null findOneByInsert(string $supplier_insert) Return the first ChildSupplier filtered by the supplier_insert column
 * @method     ChildSupplier|null findOneByUpdate(string $supplier_update) Return the first ChildSupplier filtered by the supplier_update column
 * @method     ChildSupplier|null findOneByCreatedAt(string $supplier_created) Return the first ChildSupplier filtered by the supplier_created column
 * @method     ChildSupplier|null findOneByUpdatedAt(string $supplier_updated) Return the first ChildSupplier filtered by the supplier_updated column
 * @method     ChildSupplier|null findOneByDeletedAt(string $supplier_deleted) Return the first ChildSupplier filtered by the supplier_deleted column *

 * @method     ChildSupplier requirePk($key, ConnectionInterface $con = null) Return the ChildSupplier by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSupplier requireOne(ConnectionInterface $con = null) Return the first ChildSupplier matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSupplier requireOneById(int $supplier_id) Return the first ChildSupplier filtered by the supplier_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSupplier requireOneBySiteId(int $site_id) Return the first ChildSupplier filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSupplier requireOneByName(string $supplier_name) Return the first ChildSupplier filtered by the supplier_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSupplier requireOneByGln(string $supplier_gln) Return the first ChildSupplier filtered by the supplier_gln column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSupplier requireOneByRemise(int $supplier_remise) Return the first ChildSupplier filtered by the supplier_remise column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSupplier requireOneByNotva(boolean $supplier_notva) Return the first ChildSupplier filtered by the supplier_notva column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSupplier requireOneByOnOrder(boolean $supplier_on_order) Return the first ChildSupplier filtered by the supplier_on_order column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSupplier requireOneByInsert(string $supplier_insert) Return the first ChildSupplier filtered by the supplier_insert column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSupplier requireOneByUpdate(string $supplier_update) Return the first ChildSupplier filtered by the supplier_update column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSupplier requireOneByCreatedAt(string $supplier_created) Return the first ChildSupplier filtered by the supplier_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSupplier requireOneByUpdatedAt(string $supplier_updated) Return the first ChildSupplier filtered by the supplier_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSupplier requireOneByDeletedAt(string $supplier_deleted) Return the first ChildSupplier filtered by the supplier_deleted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSupplier[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSupplier objects based on current ModelCriteria
 * @method     ChildSupplier[]|ObjectCollection findById(int $supplier_id) Return ChildSupplier objects filtered by the supplier_id column
 * @method     ChildSupplier[]|ObjectCollection findBySiteId(int $site_id) Return ChildSupplier objects filtered by the site_id column
 * @method     ChildSupplier[]|ObjectCollection findByName(string $supplier_name) Return ChildSupplier objects filtered by the supplier_name column
 * @method     ChildSupplier[]|ObjectCollection findByGln(string $supplier_gln) Return ChildSupplier objects filtered by the supplier_gln column
 * @method     ChildSupplier[]|ObjectCollection findByRemise(int $supplier_remise) Return ChildSupplier objects filtered by the supplier_remise column
 * @method     ChildSupplier[]|ObjectCollection findByNotva(boolean $supplier_notva) Return ChildSupplier objects filtered by the supplier_notva column
 * @method     ChildSupplier[]|ObjectCollection findByOnOrder(boolean $supplier_on_order) Return ChildSupplier objects filtered by the supplier_on_order column
 * @method     ChildSupplier[]|ObjectCollection findByInsert(string $supplier_insert) Return ChildSupplier objects filtered by the supplier_insert column
 * @method     ChildSupplier[]|ObjectCollection findByUpdate(string $supplier_update) Return ChildSupplier objects filtered by the supplier_update column
 * @method     ChildSupplier[]|ObjectCollection findByCreatedAt(string $supplier_created) Return ChildSupplier objects filtered by the supplier_created column
 * @method     ChildSupplier[]|ObjectCollection findByUpdatedAt(string $supplier_updated) Return ChildSupplier objects filtered by the supplier_updated column
 * @method     ChildSupplier[]|ObjectCollection findByDeletedAt(string $supplier_deleted) Return ChildSupplier objects filtered by the supplier_deleted column
 * @method     ChildSupplier[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SupplierQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\SupplierQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Supplier', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSupplierQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSupplierQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSupplierQuery) {
            return $criteria;
        }
        $query = new ChildSupplierQuery();
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
     * @return ChildSupplier|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SupplierTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = SupplierTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildSupplier A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT supplier_id, site_id, supplier_name, supplier_gln, supplier_remise, supplier_notva, supplier_on_order, supplier_insert, supplier_update, supplier_created, supplier_updated, supplier_deleted FROM suppliers WHERE supplier_id = :p0';
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
            /** @var ChildSupplier $obj */
            $obj = new ChildSupplier();
            $obj->hydrate($row);
            SupplierTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildSupplier|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildSupplierQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SupplierTableMap::COL_SUPPLIER_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSupplierQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SupplierTableMap::COL_SUPPLIER_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the supplier_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE supplier_id = 1234
     * $query->filterById(array(12, 34)); // WHERE supplier_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE supplier_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSupplierQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SupplierTableMap::COL_SUPPLIER_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SupplierTableMap::COL_SUPPLIER_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SupplierTableMap::COL_SUPPLIER_ID, $id, $comparison);
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
     * @return $this|ChildSupplierQuery The current query, for fluid interface
     */
    public function filterBySiteId($siteId = null, $comparison = null)
    {
        if (is_array($siteId)) {
            $useMinMax = false;
            if (isset($siteId['min'])) {
                $this->addUsingAlias(SupplierTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(SupplierTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SupplierTableMap::COL_SITE_ID, $siteId, $comparison);
    }

    /**
     * Filter the query on the supplier_name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE supplier_name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE supplier_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSupplierQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SupplierTableMap::COL_SUPPLIER_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the supplier_gln column
     *
     * Example usage:
     * <code>
     * $query->filterByGln(1234); // WHERE supplier_gln = 1234
     * $query->filterByGln(array(12, 34)); // WHERE supplier_gln IN (12, 34)
     * $query->filterByGln(array('min' => 12)); // WHERE supplier_gln > 12
     * </code>
     *
     * @param     mixed $gln The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSupplierQuery The current query, for fluid interface
     */
    public function filterByGln($gln = null, $comparison = null)
    {
        if (is_array($gln)) {
            $useMinMax = false;
            if (isset($gln['min'])) {
                $this->addUsingAlias(SupplierTableMap::COL_SUPPLIER_GLN, $gln['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($gln['max'])) {
                $this->addUsingAlias(SupplierTableMap::COL_SUPPLIER_GLN, $gln['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SupplierTableMap::COL_SUPPLIER_GLN, $gln, $comparison);
    }

    /**
     * Filter the query on the supplier_remise column
     *
     * Example usage:
     * <code>
     * $query->filterByRemise(1234); // WHERE supplier_remise = 1234
     * $query->filterByRemise(array(12, 34)); // WHERE supplier_remise IN (12, 34)
     * $query->filterByRemise(array('min' => 12)); // WHERE supplier_remise > 12
     * </code>
     *
     * @param     mixed $remise The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSupplierQuery The current query, for fluid interface
     */
    public function filterByRemise($remise = null, $comparison = null)
    {
        if (is_array($remise)) {
            $useMinMax = false;
            if (isset($remise['min'])) {
                $this->addUsingAlias(SupplierTableMap::COL_SUPPLIER_REMISE, $remise['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($remise['max'])) {
                $this->addUsingAlias(SupplierTableMap::COL_SUPPLIER_REMISE, $remise['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SupplierTableMap::COL_SUPPLIER_REMISE, $remise, $comparison);
    }

    /**
     * Filter the query on the supplier_notva column
     *
     * Example usage:
     * <code>
     * $query->filterByNotva(true); // WHERE supplier_notva = true
     * $query->filterByNotva('yes'); // WHERE supplier_notva = true
     * </code>
     *
     * @param     boolean|string $notva The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSupplierQuery The current query, for fluid interface
     */
    public function filterByNotva($notva = null, $comparison = null)
    {
        if (is_string($notva)) {
            $notva = in_array(strtolower($notva), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(SupplierTableMap::COL_SUPPLIER_NOTVA, $notva, $comparison);
    }

    /**
     * Filter the query on the supplier_on_order column
     *
     * Example usage:
     * <code>
     * $query->filterByOnOrder(true); // WHERE supplier_on_order = true
     * $query->filterByOnOrder('yes'); // WHERE supplier_on_order = true
     * </code>
     *
     * @param     boolean|string $onOrder The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSupplierQuery The current query, for fluid interface
     */
    public function filterByOnOrder($onOrder = null, $comparison = null)
    {
        if (is_string($onOrder)) {
            $onOrder = in_array(strtolower($onOrder), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(SupplierTableMap::COL_SUPPLIER_ON_ORDER, $onOrder, $comparison);
    }

    /**
     * Filter the query on the supplier_insert column
     *
     * Example usage:
     * <code>
     * $query->filterByInsert('2011-03-14'); // WHERE supplier_insert = '2011-03-14'
     * $query->filterByInsert('now'); // WHERE supplier_insert = '2011-03-14'
     * $query->filterByInsert(array('max' => 'yesterday')); // WHERE supplier_insert > '2011-03-13'
     * </code>
     *
     * @param     mixed $insert The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSupplierQuery The current query, for fluid interface
     */
    public function filterByInsert($insert = null, $comparison = null)
    {
        if (is_array($insert)) {
            $useMinMax = false;
            if (isset($insert['min'])) {
                $this->addUsingAlias(SupplierTableMap::COL_SUPPLIER_INSERT, $insert['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($insert['max'])) {
                $this->addUsingAlias(SupplierTableMap::COL_SUPPLIER_INSERT, $insert['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SupplierTableMap::COL_SUPPLIER_INSERT, $insert, $comparison);
    }

    /**
     * Filter the query on the supplier_update column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdate('2011-03-14'); // WHERE supplier_update = '2011-03-14'
     * $query->filterByUpdate('now'); // WHERE supplier_update = '2011-03-14'
     * $query->filterByUpdate(array('max' => 'yesterday')); // WHERE supplier_update > '2011-03-13'
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
     * @return $this|ChildSupplierQuery The current query, for fluid interface
     */
    public function filterByUpdate($update = null, $comparison = null)
    {
        if (is_array($update)) {
            $useMinMax = false;
            if (isset($update['min'])) {
                $this->addUsingAlias(SupplierTableMap::COL_SUPPLIER_UPDATE, $update['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($update['max'])) {
                $this->addUsingAlias(SupplierTableMap::COL_SUPPLIER_UPDATE, $update['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SupplierTableMap::COL_SUPPLIER_UPDATE, $update, $comparison);
    }

    /**
     * Filter the query on the supplier_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE supplier_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE supplier_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE supplier_created > '2011-03-13'
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
     * @return $this|ChildSupplierQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(SupplierTableMap::COL_SUPPLIER_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(SupplierTableMap::COL_SUPPLIER_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SupplierTableMap::COL_SUPPLIER_CREATED, $createdAt, $comparison);
    }

    /**
     * Filter the query on the supplier_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE supplier_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE supplier_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE supplier_updated > '2011-03-13'
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
     * @return $this|ChildSupplierQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(SupplierTableMap::COL_SUPPLIER_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(SupplierTableMap::COL_SUPPLIER_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SupplierTableMap::COL_SUPPLIER_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the supplier_deleted column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletedAt('2011-03-14'); // WHERE supplier_deleted = '2011-03-14'
     * $query->filterByDeletedAt('now'); // WHERE supplier_deleted = '2011-03-14'
     * $query->filterByDeletedAt(array('max' => 'yesterday')); // WHERE supplier_deleted > '2011-03-13'
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
     * @return $this|ChildSupplierQuery The current query, for fluid interface
     */
    public function filterByDeletedAt($deletedAt = null, $comparison = null)
    {
        if (is_array($deletedAt)) {
            $useMinMax = false;
            if (isset($deletedAt['min'])) {
                $this->addUsingAlias(SupplierTableMap::COL_SUPPLIER_DELETED, $deletedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletedAt['max'])) {
                $this->addUsingAlias(SupplierTableMap::COL_SUPPLIER_DELETED, $deletedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SupplierTableMap::COL_SUPPLIER_DELETED, $deletedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSupplier $supplier Object to remove from the list of results
     *
     * @return $this|ChildSupplierQuery The current query, for fluid interface
     */
    public function prune($supplier = null)
    {
        if ($supplier) {
            $this->addUsingAlias(SupplierTableMap::COL_SUPPLIER_ID, $supplier->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the suppliers table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SupplierTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SupplierTableMap::clearInstancePool();
            SupplierTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(SupplierTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SupplierTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SupplierTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SupplierTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // SupplierQuery
