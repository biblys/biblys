<?php
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


namespace Model\Base;

use \Exception;
use \PDO;
use Model\Rayon as ChildRayon;
use Model\RayonQuery as ChildRayonQuery;
use Model\Map\RayonTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'rayons' table.
 *
 *
 *
 * @method     ChildRayonQuery orderById($order = Criteria::ASC) Order by the rayon_id column
 * @method     ChildRayonQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildRayonQuery orderByName($order = Criteria::ASC) Order by the rayon_name column
 * @method     ChildRayonQuery orderByUrl($order = Criteria::ASC) Order by the rayon_url column
 * @method     ChildRayonQuery orderByDesc($order = Criteria::ASC) Order by the rayon_desc column
 * @method     ChildRayonQuery orderByOrder($order = Criteria::ASC) Order by the rayon_order column
 * @method     ChildRayonQuery orderBySortBy($order = Criteria::ASC) Order by the rayon_sort_by column
 * @method     ChildRayonQuery orderBySortOrder($order = Criteria::ASC) Order by the rayon_sort_order column
 * @method     ChildRayonQuery orderByShowUpcoming($order = Criteria::ASC) Order by the rayon_show_upcoming column
 * @method     ChildRayonQuery orderByCreatedAt($order = Criteria::ASC) Order by the rayon_created column
 * @method     ChildRayonQuery orderByUpdatedAt($order = Criteria::ASC) Order by the rayon_updated column
 *
 * @method     ChildRayonQuery groupById() Group by the rayon_id column
 * @method     ChildRayonQuery groupBySiteId() Group by the site_id column
 * @method     ChildRayonQuery groupByName() Group by the rayon_name column
 * @method     ChildRayonQuery groupByUrl() Group by the rayon_url column
 * @method     ChildRayonQuery groupByDesc() Group by the rayon_desc column
 * @method     ChildRayonQuery groupByOrder() Group by the rayon_order column
 * @method     ChildRayonQuery groupBySortBy() Group by the rayon_sort_by column
 * @method     ChildRayonQuery groupBySortOrder() Group by the rayon_sort_order column
 * @method     ChildRayonQuery groupByShowUpcoming() Group by the rayon_show_upcoming column
 * @method     ChildRayonQuery groupByCreatedAt() Group by the rayon_created column
 * @method     ChildRayonQuery groupByUpdatedAt() Group by the rayon_updated column
 *
 * @method     ChildRayonQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildRayonQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildRayonQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildRayonQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildRayonQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildRayonQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildRayon|null findOne(ConnectionInterface $con = null) Return the first ChildRayon matching the query
 * @method     ChildRayon findOneOrCreate(ConnectionInterface $con = null) Return the first ChildRayon matching the query, or a new ChildRayon object populated from the query conditions when no match is found
 *
 * @method     ChildRayon|null findOneById(string $rayon_id) Return the first ChildRayon filtered by the rayon_id column
 * @method     ChildRayon|null findOneBySiteId(int $site_id) Return the first ChildRayon filtered by the site_id column
 * @method     ChildRayon|null findOneByName(string $rayon_name) Return the first ChildRayon filtered by the rayon_name column
 * @method     ChildRayon|null findOneByUrl(string $rayon_url) Return the first ChildRayon filtered by the rayon_url column
 * @method     ChildRayon|null findOneByDesc(string $rayon_desc) Return the first ChildRayon filtered by the rayon_desc column
 * @method     ChildRayon|null findOneByOrder(int $rayon_order) Return the first ChildRayon filtered by the rayon_order column
 * @method     ChildRayon|null findOneBySortBy(string $rayon_sort_by) Return the first ChildRayon filtered by the rayon_sort_by column
 * @method     ChildRayon|null findOneBySortOrder(boolean $rayon_sort_order) Return the first ChildRayon filtered by the rayon_sort_order column
 * @method     ChildRayon|null findOneByShowUpcoming(boolean $rayon_show_upcoming) Return the first ChildRayon filtered by the rayon_show_upcoming column
 * @method     ChildRayon|null findOneByCreatedAt(string $rayon_created) Return the first ChildRayon filtered by the rayon_created column
 * @method     ChildRayon|null findOneByUpdatedAt(string $rayon_updated) Return the first ChildRayon filtered by the rayon_updated column *

 * @method     ChildRayon requirePk($key, ConnectionInterface $con = null) Return the ChildRayon by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRayon requireOne(ConnectionInterface $con = null) Return the first ChildRayon matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildRayon requireOneById(string $rayon_id) Return the first ChildRayon filtered by the rayon_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRayon requireOneBySiteId(int $site_id) Return the first ChildRayon filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRayon requireOneByName(string $rayon_name) Return the first ChildRayon filtered by the rayon_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRayon requireOneByUrl(string $rayon_url) Return the first ChildRayon filtered by the rayon_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRayon requireOneByDesc(string $rayon_desc) Return the first ChildRayon filtered by the rayon_desc column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRayon requireOneByOrder(int $rayon_order) Return the first ChildRayon filtered by the rayon_order column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRayon requireOneBySortBy(string $rayon_sort_by) Return the first ChildRayon filtered by the rayon_sort_by column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRayon requireOneBySortOrder(boolean $rayon_sort_order) Return the first ChildRayon filtered by the rayon_sort_order column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRayon requireOneByShowUpcoming(boolean $rayon_show_upcoming) Return the first ChildRayon filtered by the rayon_show_upcoming column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRayon requireOneByCreatedAt(string $rayon_created) Return the first ChildRayon filtered by the rayon_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRayon requireOneByUpdatedAt(string $rayon_updated) Return the first ChildRayon filtered by the rayon_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildRayon[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildRayon objects based on current ModelCriteria
 * @psalm-method ObjectCollection&\Traversable<ChildRayon> find(ConnectionInterface $con = null) Return ChildRayon objects based on current ModelCriteria
 * @method     ChildRayon[]|ObjectCollection findById(string $rayon_id) Return ChildRayon objects filtered by the rayon_id column
 * @psalm-method ObjectCollection&\Traversable<ChildRayon> findById(string $rayon_id) Return ChildRayon objects filtered by the rayon_id column
 * @method     ChildRayon[]|ObjectCollection findBySiteId(int $site_id) Return ChildRayon objects filtered by the site_id column
 * @psalm-method ObjectCollection&\Traversable<ChildRayon> findBySiteId(int $site_id) Return ChildRayon objects filtered by the site_id column
 * @method     ChildRayon[]|ObjectCollection findByName(string $rayon_name) Return ChildRayon objects filtered by the rayon_name column
 * @psalm-method ObjectCollection&\Traversable<ChildRayon> findByName(string $rayon_name) Return ChildRayon objects filtered by the rayon_name column
 * @method     ChildRayon[]|ObjectCollection findByUrl(string $rayon_url) Return ChildRayon objects filtered by the rayon_url column
 * @psalm-method ObjectCollection&\Traversable<ChildRayon> findByUrl(string $rayon_url) Return ChildRayon objects filtered by the rayon_url column
 * @method     ChildRayon[]|ObjectCollection findByDesc(string $rayon_desc) Return ChildRayon objects filtered by the rayon_desc column
 * @psalm-method ObjectCollection&\Traversable<ChildRayon> findByDesc(string $rayon_desc) Return ChildRayon objects filtered by the rayon_desc column
 * @method     ChildRayon[]|ObjectCollection findByOrder(int $rayon_order) Return ChildRayon objects filtered by the rayon_order column
 * @psalm-method ObjectCollection&\Traversable<ChildRayon> findByOrder(int $rayon_order) Return ChildRayon objects filtered by the rayon_order column
 * @method     ChildRayon[]|ObjectCollection findBySortBy(string $rayon_sort_by) Return ChildRayon objects filtered by the rayon_sort_by column
 * @psalm-method ObjectCollection&\Traversable<ChildRayon> findBySortBy(string $rayon_sort_by) Return ChildRayon objects filtered by the rayon_sort_by column
 * @method     ChildRayon[]|ObjectCollection findBySortOrder(boolean $rayon_sort_order) Return ChildRayon objects filtered by the rayon_sort_order column
 * @psalm-method ObjectCollection&\Traversable<ChildRayon> findBySortOrder(boolean $rayon_sort_order) Return ChildRayon objects filtered by the rayon_sort_order column
 * @method     ChildRayon[]|ObjectCollection findByShowUpcoming(boolean $rayon_show_upcoming) Return ChildRayon objects filtered by the rayon_show_upcoming column
 * @psalm-method ObjectCollection&\Traversable<ChildRayon> findByShowUpcoming(boolean $rayon_show_upcoming) Return ChildRayon objects filtered by the rayon_show_upcoming column
 * @method     ChildRayon[]|ObjectCollection findByCreatedAt(string $rayon_created) Return ChildRayon objects filtered by the rayon_created column
 * @psalm-method ObjectCollection&\Traversable<ChildRayon> findByCreatedAt(string $rayon_created) Return ChildRayon objects filtered by the rayon_created column
 * @method     ChildRayon[]|ObjectCollection findByUpdatedAt(string $rayon_updated) Return ChildRayon objects filtered by the rayon_updated column
 * @psalm-method ObjectCollection&\Traversable<ChildRayon> findByUpdatedAt(string $rayon_updated) Return ChildRayon objects filtered by the rayon_updated column
 * @method     ChildRayon[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildRayon> paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class RayonQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\RayonQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Rayon', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildRayonQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildRayonQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildRayonQuery) {
            return $criteria;
        }
        $query = new ChildRayonQuery();
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
     * @return ChildRayon|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(RayonTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = RayonTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildRayon A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT rayon_id, site_id, rayon_name, rayon_url, rayon_desc, rayon_order, rayon_sort_by, rayon_sort_order, rayon_show_upcoming, rayon_created, rayon_updated FROM rayons WHERE rayon_id = :p0';
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
            /** @var ChildRayon $obj */
            $obj = new ChildRayon();
            $obj->hydrate($row);
            RayonTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildRayon|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildRayonQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(RayonTableMap::COL_RAYON_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildRayonQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(RayonTableMap::COL_RAYON_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the rayon_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE rayon_id = 1234
     * $query->filterById(array(12, 34)); // WHERE rayon_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE rayon_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildRayonQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(RayonTableMap::COL_RAYON_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(RayonTableMap::COL_RAYON_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RayonTableMap::COL_RAYON_ID, $id, $comparison);
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
     * @return $this|ChildRayonQuery The current query, for fluid interface
     */
    public function filterBySiteId($siteId = null, $comparison = null)
    {
        if (is_array($siteId)) {
            $useMinMax = false;
            if (isset($siteId['min'])) {
                $this->addUsingAlias(RayonTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(RayonTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RayonTableMap::COL_SITE_ID, $siteId, $comparison);
    }

    /**
     * Filter the query on the rayon_name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE rayon_name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE rayon_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildRayonQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RayonTableMap::COL_RAYON_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the rayon_url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE rayon_url = 'fooValue'
     * $query->filterByUrl('%fooValue%', Criteria::LIKE); // WHERE rayon_url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $url The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildRayonQuery The current query, for fluid interface
     */
    public function filterByUrl($url = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($url)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RayonTableMap::COL_RAYON_URL, $url, $comparison);
    }

    /**
     * Filter the query on the rayon_desc column
     *
     * Example usage:
     * <code>
     * $query->filterByDesc('fooValue');   // WHERE rayon_desc = 'fooValue'
     * $query->filterByDesc('%fooValue%', Criteria::LIKE); // WHERE rayon_desc LIKE '%fooValue%'
     * </code>
     *
     * @param     string $desc The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildRayonQuery The current query, for fluid interface
     */
    public function filterByDesc($desc = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($desc)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RayonTableMap::COL_RAYON_DESC, $desc, $comparison);
    }

    /**
     * Filter the query on the rayon_order column
     *
     * Example usage:
     * <code>
     * $query->filterByOrder(1234); // WHERE rayon_order = 1234
     * $query->filterByOrder(array(12, 34)); // WHERE rayon_order IN (12, 34)
     * $query->filterByOrder(array('min' => 12)); // WHERE rayon_order > 12
     * </code>
     *
     * @param     mixed $order The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildRayonQuery The current query, for fluid interface
     */
    public function filterByOrder($order = null, $comparison = null)
    {
        if (is_array($order)) {
            $useMinMax = false;
            if (isset($order['min'])) {
                $this->addUsingAlias(RayonTableMap::COL_RAYON_ORDER, $order['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($order['max'])) {
                $this->addUsingAlias(RayonTableMap::COL_RAYON_ORDER, $order['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RayonTableMap::COL_RAYON_ORDER, $order, $comparison);
    }

    /**
     * Filter the query on the rayon_sort_by column
     *
     * Example usage:
     * <code>
     * $query->filterBySortBy('fooValue');   // WHERE rayon_sort_by = 'fooValue'
     * $query->filterBySortBy('%fooValue%', Criteria::LIKE); // WHERE rayon_sort_by LIKE '%fooValue%'
     * </code>
     *
     * @param     string $sortBy The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildRayonQuery The current query, for fluid interface
     */
    public function filterBySortBy($sortBy = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($sortBy)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RayonTableMap::COL_RAYON_SORT_BY, $sortBy, $comparison);
    }

    /**
     * Filter the query on the rayon_sort_order column
     *
     * Example usage:
     * <code>
     * $query->filterBySortOrder(true); // WHERE rayon_sort_order = true
     * $query->filterBySortOrder('yes'); // WHERE rayon_sort_order = true
     * </code>
     *
     * @param     boolean|string $sortOrder The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildRayonQuery The current query, for fluid interface
     */
    public function filterBySortOrder($sortOrder = null, $comparison = null)
    {
        if (is_string($sortOrder)) {
            $sortOrder = in_array(strtolower($sortOrder), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(RayonTableMap::COL_RAYON_SORT_ORDER, $sortOrder, $comparison);
    }

    /**
     * Filter the query on the rayon_show_upcoming column
     *
     * Example usage:
     * <code>
     * $query->filterByShowUpcoming(true); // WHERE rayon_show_upcoming = true
     * $query->filterByShowUpcoming('yes'); // WHERE rayon_show_upcoming = true
     * </code>
     *
     * @param     boolean|string $showUpcoming The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildRayonQuery The current query, for fluid interface
     */
    public function filterByShowUpcoming($showUpcoming = null, $comparison = null)
    {
        if (is_string($showUpcoming)) {
            $showUpcoming = in_array(strtolower($showUpcoming), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(RayonTableMap::COL_RAYON_SHOW_UPCOMING, $showUpcoming, $comparison);
    }

    /**
     * Filter the query on the rayon_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE rayon_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE rayon_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE rayon_created > '2011-03-13'
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
     * @return $this|ChildRayonQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(RayonTableMap::COL_RAYON_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(RayonTableMap::COL_RAYON_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RayonTableMap::COL_RAYON_CREATED, $createdAt, $comparison);
    }

    /**
     * Filter the query on the rayon_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE rayon_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE rayon_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE rayon_updated > '2011-03-13'
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
     * @return $this|ChildRayonQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(RayonTableMap::COL_RAYON_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(RayonTableMap::COL_RAYON_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RayonTableMap::COL_RAYON_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildRayon $rayon Object to remove from the list of results
     *
     * @return $this|ChildRayonQuery The current query, for fluid interface
     */
    public function prune($rayon = null)
    {
        if ($rayon) {
            $this->addUsingAlias(RayonTableMap::COL_RAYON_ID, $rayon->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the rayons table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(RayonTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            RayonTableMap::clearInstancePool();
            RayonTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(RayonTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(RayonTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            RayonTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            RayonTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildRayonQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(RayonTableMap::COL_RAYON_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildRayonQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(RayonTableMap::COL_RAYON_UPDATED);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildRayonQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(RayonTableMap::COL_RAYON_UPDATED);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildRayonQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(RayonTableMap::COL_RAYON_CREATED);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildRayonQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(RayonTableMap::COL_RAYON_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildRayonQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(RayonTableMap::COL_RAYON_CREATED);
    }

} // RayonQuery
