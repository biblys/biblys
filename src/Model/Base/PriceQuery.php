<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Price as ChildPrice;
use Model\PriceQuery as ChildPriceQuery;
use Model\Map\PriceTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'prices' table.
 *
 *
 *
 * @method     ChildPriceQuery orderById($order = Criteria::ASC) Order by the price_id column
 * @method     ChildPriceQuery orderBygridId($order = Criteria::ASC) Order by the pricegrid_id column
 * @method     ChildPriceQuery orderByCat($order = Criteria::ASC) Order by the price_cat column
 * @method     ChildPriceQuery orderByAmount($order = Criteria::ASC) Order by the price_amount column
 * @method     ChildPriceQuery orderByCreatedAt($order = Criteria::ASC) Order by the price_created column
 * @method     ChildPriceQuery orderByUpdatedAt($order = Criteria::ASC) Order by the price_updated column
 * @method     ChildPriceQuery orderByDeletedAt($order = Criteria::ASC) Order by the price_deleted column
 *
 * @method     ChildPriceQuery groupById() Group by the price_id column
 * @method     ChildPriceQuery groupBygridId() Group by the pricegrid_id column
 * @method     ChildPriceQuery groupByCat() Group by the price_cat column
 * @method     ChildPriceQuery groupByAmount() Group by the price_amount column
 * @method     ChildPriceQuery groupByCreatedAt() Group by the price_created column
 * @method     ChildPriceQuery groupByUpdatedAt() Group by the price_updated column
 * @method     ChildPriceQuery groupByDeletedAt() Group by the price_deleted column
 *
 * @method     ChildPriceQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPriceQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPriceQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPriceQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPriceQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPriceQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPrice|null findOne(ConnectionInterface $con = null) Return the first ChildPrice matching the query
 * @method     ChildPrice findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPrice matching the query, or a new ChildPrice object populated from the query conditions when no match is found
 *
 * @method     ChildPrice|null findOneById(int $price_id) Return the first ChildPrice filtered by the price_id column
 * @method     ChildPrice|null findOneBygridId(int $pricegrid_id) Return the first ChildPrice filtered by the pricegrid_id column
 * @method     ChildPrice|null findOneByCat(string $price_cat) Return the first ChildPrice filtered by the price_cat column
 * @method     ChildPrice|null findOneByAmount(int $price_amount) Return the first ChildPrice filtered by the price_amount column
 * @method     ChildPrice|null findOneByCreatedAt(string $price_created) Return the first ChildPrice filtered by the price_created column
 * @method     ChildPrice|null findOneByUpdatedAt(string $price_updated) Return the first ChildPrice filtered by the price_updated column
 * @method     ChildPrice|null findOneByDeletedAt(string $price_deleted) Return the first ChildPrice filtered by the price_deleted column *

 * @method     ChildPrice requirePk($key, ConnectionInterface $con = null) Return the ChildPrice by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPrice requireOne(ConnectionInterface $con = null) Return the first ChildPrice matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPrice requireOneById(int $price_id) Return the first ChildPrice filtered by the price_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPrice requireOneBygridId(int $pricegrid_id) Return the first ChildPrice filtered by the pricegrid_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPrice requireOneByCat(string $price_cat) Return the first ChildPrice filtered by the price_cat column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPrice requireOneByAmount(int $price_amount) Return the first ChildPrice filtered by the price_amount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPrice requireOneByCreatedAt(string $price_created) Return the first ChildPrice filtered by the price_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPrice requireOneByUpdatedAt(string $price_updated) Return the first ChildPrice filtered by the price_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPrice requireOneByDeletedAt(string $price_deleted) Return the first ChildPrice filtered by the price_deleted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPrice[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPrice objects based on current ModelCriteria
 * @method     ChildPrice[]|ObjectCollection findById(int $price_id) Return ChildPrice objects filtered by the price_id column
 * @method     ChildPrice[]|ObjectCollection findBygridId(int $pricegrid_id) Return ChildPrice objects filtered by the pricegrid_id column
 * @method     ChildPrice[]|ObjectCollection findByCat(string $price_cat) Return ChildPrice objects filtered by the price_cat column
 * @method     ChildPrice[]|ObjectCollection findByAmount(int $price_amount) Return ChildPrice objects filtered by the price_amount column
 * @method     ChildPrice[]|ObjectCollection findByCreatedAt(string $price_created) Return ChildPrice objects filtered by the price_created column
 * @method     ChildPrice[]|ObjectCollection findByUpdatedAt(string $price_updated) Return ChildPrice objects filtered by the price_updated column
 * @method     ChildPrice[]|ObjectCollection findByDeletedAt(string $price_deleted) Return ChildPrice objects filtered by the price_deleted column
 * @method     ChildPrice[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PriceQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\PriceQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Price', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPriceQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPriceQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPriceQuery) {
            return $criteria;
        }
        $query = new ChildPriceQuery();
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
     * @return ChildPrice|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PriceTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PriceTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildPrice A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT price_id, pricegrid_id, price_cat, price_amount, price_created, price_updated, price_deleted FROM prices WHERE price_id = :p0';
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
            /** @var ChildPrice $obj */
            $obj = new ChildPrice();
            $obj->hydrate($row);
            PriceTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPrice|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPriceQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PriceTableMap::COL_PRICE_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPriceQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PriceTableMap::COL_PRICE_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the price_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE price_id = 1234
     * $query->filterById(array(12, 34)); // WHERE price_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE price_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPriceQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PriceTableMap::COL_PRICE_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PriceTableMap::COL_PRICE_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PriceTableMap::COL_PRICE_ID, $id, $comparison);
    }

    /**
     * Filter the query on the pricegrid_id column
     *
     * Example usage:
     * <code>
     * $query->filterBygridId(1234); // WHERE pricegrid_id = 1234
     * $query->filterBygridId(array(12, 34)); // WHERE pricegrid_id IN (12, 34)
     * $query->filterBygridId(array('min' => 12)); // WHERE pricegrid_id > 12
     * </code>
     *
     * @param     mixed $gridId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPriceQuery The current query, for fluid interface
     */
    public function filterBygridId($gridId = null, $comparison = null)
    {
        if (is_array($gridId)) {
            $useMinMax = false;
            if (isset($gridId['min'])) {
                $this->addUsingAlias(PriceTableMap::COL_PRICEGRID_ID, $gridId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($gridId['max'])) {
                $this->addUsingAlias(PriceTableMap::COL_PRICEGRID_ID, $gridId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PriceTableMap::COL_PRICEGRID_ID, $gridId, $comparison);
    }

    /**
     * Filter the query on the price_cat column
     *
     * Example usage:
     * <code>
     * $query->filterByCat('fooValue');   // WHERE price_cat = 'fooValue'
     * $query->filterByCat('%fooValue%', Criteria::LIKE); // WHERE price_cat LIKE '%fooValue%'
     * </code>
     *
     * @param     string $cat The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPriceQuery The current query, for fluid interface
     */
    public function filterByCat($cat = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($cat)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PriceTableMap::COL_PRICE_CAT, $cat, $comparison);
    }

    /**
     * Filter the query on the price_amount column
     *
     * Example usage:
     * <code>
     * $query->filterByAmount(1234); // WHERE price_amount = 1234
     * $query->filterByAmount(array(12, 34)); // WHERE price_amount IN (12, 34)
     * $query->filterByAmount(array('min' => 12)); // WHERE price_amount > 12
     * </code>
     *
     * @param     mixed $amount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPriceQuery The current query, for fluid interface
     */
    public function filterByAmount($amount = null, $comparison = null)
    {
        if (is_array($amount)) {
            $useMinMax = false;
            if (isset($amount['min'])) {
                $this->addUsingAlias(PriceTableMap::COL_PRICE_AMOUNT, $amount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($amount['max'])) {
                $this->addUsingAlias(PriceTableMap::COL_PRICE_AMOUNT, $amount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PriceTableMap::COL_PRICE_AMOUNT, $amount, $comparison);
    }

    /**
     * Filter the query on the price_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE price_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE price_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE price_created > '2011-03-13'
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
     * @return $this|ChildPriceQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PriceTableMap::COL_PRICE_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PriceTableMap::COL_PRICE_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PriceTableMap::COL_PRICE_CREATED, $createdAt, $comparison);
    }

    /**
     * Filter the query on the price_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE price_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE price_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE price_updated > '2011-03-13'
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
     * @return $this|ChildPriceQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PriceTableMap::COL_PRICE_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PriceTableMap::COL_PRICE_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PriceTableMap::COL_PRICE_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the price_deleted column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletedAt('2011-03-14'); // WHERE price_deleted = '2011-03-14'
     * $query->filterByDeletedAt('now'); // WHERE price_deleted = '2011-03-14'
     * $query->filterByDeletedAt(array('max' => 'yesterday')); // WHERE price_deleted > '2011-03-13'
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
     * @return $this|ChildPriceQuery The current query, for fluid interface
     */
    public function filterByDeletedAt($deletedAt = null, $comparison = null)
    {
        if (is_array($deletedAt)) {
            $useMinMax = false;
            if (isset($deletedAt['min'])) {
                $this->addUsingAlias(PriceTableMap::COL_PRICE_DELETED, $deletedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletedAt['max'])) {
                $this->addUsingAlias(PriceTableMap::COL_PRICE_DELETED, $deletedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PriceTableMap::COL_PRICE_DELETED, $deletedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPrice $price Object to remove from the list of results
     *
     * @return $this|ChildPriceQuery The current query, for fluid interface
     */
    public function prune($price = null)
    {
        if ($price) {
            $this->addUsingAlias(PriceTableMap::COL_PRICE_ID, $price->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the prices table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PriceTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PriceTableMap::clearInstancePool();
            PriceTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PriceTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PriceTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PriceTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PriceTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PriceQuery
