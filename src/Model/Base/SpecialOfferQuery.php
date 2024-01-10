<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\SpecialOffer as ChildSpecialOffer;
use Model\SpecialOfferQuery as ChildSpecialOfferQuery;
use Model\Map\SpecialOfferTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `special_offers` table.
 *
 * @method     ChildSpecialOfferQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildSpecialOfferQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildSpecialOfferQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildSpecialOfferQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method     ChildSpecialOfferQuery orderByTargetCollectionId($order = Criteria::ASC) Order by the target_collection_id column
 * @method     ChildSpecialOfferQuery orderByTargetQuantity($order = Criteria::ASC) Order by the target_quantity column
 * @method     ChildSpecialOfferQuery orderByFreeArticleId($order = Criteria::ASC) Order by the free_article_id column
 * @method     ChildSpecialOfferQuery orderByStartDate($order = Criteria::ASC) Order by the start_date column
 * @method     ChildSpecialOfferQuery orderByEndDate($order = Criteria::ASC) Order by the end_date column
 * @method     ChildSpecialOfferQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildSpecialOfferQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildSpecialOfferQuery groupById() Group by the id column
 * @method     ChildSpecialOfferQuery groupBySiteId() Group by the site_id column
 * @method     ChildSpecialOfferQuery groupByName() Group by the name column
 * @method     ChildSpecialOfferQuery groupByDescription() Group by the description column
 * @method     ChildSpecialOfferQuery groupByTargetCollectionId() Group by the target_collection_id column
 * @method     ChildSpecialOfferQuery groupByTargetQuantity() Group by the target_quantity column
 * @method     ChildSpecialOfferQuery groupByFreeArticleId() Group by the free_article_id column
 * @method     ChildSpecialOfferQuery groupByStartDate() Group by the start_date column
 * @method     ChildSpecialOfferQuery groupByEndDate() Group by the end_date column
 * @method     ChildSpecialOfferQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildSpecialOfferQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildSpecialOfferQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSpecialOfferQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSpecialOfferQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSpecialOfferQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildSpecialOfferQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildSpecialOfferQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildSpecialOfferQuery leftJoinSite($relationAlias = null) Adds a LEFT JOIN clause to the query using the Site relation
 * @method     ChildSpecialOfferQuery rightJoinSite($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Site relation
 * @method     ChildSpecialOfferQuery innerJoinSite($relationAlias = null) Adds a INNER JOIN clause to the query using the Site relation
 *
 * @method     ChildSpecialOfferQuery joinWithSite($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Site relation
 *
 * @method     ChildSpecialOfferQuery leftJoinWithSite() Adds a LEFT JOIN clause and with to the query using the Site relation
 * @method     ChildSpecialOfferQuery rightJoinWithSite() Adds a RIGHT JOIN clause and with to the query using the Site relation
 * @method     ChildSpecialOfferQuery innerJoinWithSite() Adds a INNER JOIN clause and with to the query using the Site relation
 *
 * @method     ChildSpecialOfferQuery leftJoinTargetCollection($relationAlias = null) Adds a LEFT JOIN clause to the query using the TargetCollection relation
 * @method     ChildSpecialOfferQuery rightJoinTargetCollection($relationAlias = null) Adds a RIGHT JOIN clause to the query using the TargetCollection relation
 * @method     ChildSpecialOfferQuery innerJoinTargetCollection($relationAlias = null) Adds a INNER JOIN clause to the query using the TargetCollection relation
 *
 * @method     ChildSpecialOfferQuery joinWithTargetCollection($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the TargetCollection relation
 *
 * @method     ChildSpecialOfferQuery leftJoinWithTargetCollection() Adds a LEFT JOIN clause and with to the query using the TargetCollection relation
 * @method     ChildSpecialOfferQuery rightJoinWithTargetCollection() Adds a RIGHT JOIN clause and with to the query using the TargetCollection relation
 * @method     ChildSpecialOfferQuery innerJoinWithTargetCollection() Adds a INNER JOIN clause and with to the query using the TargetCollection relation
 *
 * @method     ChildSpecialOfferQuery leftJoinFreeArticle($relationAlias = null) Adds a LEFT JOIN clause to the query using the FreeArticle relation
 * @method     ChildSpecialOfferQuery rightJoinFreeArticle($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FreeArticle relation
 * @method     ChildSpecialOfferQuery innerJoinFreeArticle($relationAlias = null) Adds a INNER JOIN clause to the query using the FreeArticle relation
 *
 * @method     ChildSpecialOfferQuery joinWithFreeArticle($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the FreeArticle relation
 *
 * @method     ChildSpecialOfferQuery leftJoinWithFreeArticle() Adds a LEFT JOIN clause and with to the query using the FreeArticle relation
 * @method     ChildSpecialOfferQuery rightJoinWithFreeArticle() Adds a RIGHT JOIN clause and with to the query using the FreeArticle relation
 * @method     ChildSpecialOfferQuery innerJoinWithFreeArticle() Adds a INNER JOIN clause and with to the query using the FreeArticle relation
 *
 * @method     \Model\SiteQuery|\Model\BookCollectionQuery|\Model\ArticleQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildSpecialOffer|null findOne(?ConnectionInterface $con = null) Return the first ChildSpecialOffer matching the query
 * @method     ChildSpecialOffer findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildSpecialOffer matching the query, or a new ChildSpecialOffer object populated from the query conditions when no match is found
 *
 * @method     ChildSpecialOffer|null findOneById(int $id) Return the first ChildSpecialOffer filtered by the id column
 * @method     ChildSpecialOffer|null findOneBySiteId(int $site_id) Return the first ChildSpecialOffer filtered by the site_id column
 * @method     ChildSpecialOffer|null findOneByName(string $name) Return the first ChildSpecialOffer filtered by the name column
 * @method     ChildSpecialOffer|null findOneByDescription(string $description) Return the first ChildSpecialOffer filtered by the description column
 * @method     ChildSpecialOffer|null findOneByTargetCollectionId(int $target_collection_id) Return the first ChildSpecialOffer filtered by the target_collection_id column
 * @method     ChildSpecialOffer|null findOneByTargetQuantity(int $target_quantity) Return the first ChildSpecialOffer filtered by the target_quantity column
 * @method     ChildSpecialOffer|null findOneByFreeArticleId(int $free_article_id) Return the first ChildSpecialOffer filtered by the free_article_id column
 * @method     ChildSpecialOffer|null findOneByStartDate(string $start_date) Return the first ChildSpecialOffer filtered by the start_date column
 * @method     ChildSpecialOffer|null findOneByEndDate(string $end_date) Return the first ChildSpecialOffer filtered by the end_date column
 * @method     ChildSpecialOffer|null findOneByCreatedAt(string $created_at) Return the first ChildSpecialOffer filtered by the created_at column
 * @method     ChildSpecialOffer|null findOneByUpdatedAt(string $updated_at) Return the first ChildSpecialOffer filtered by the updated_at column
 *
 * @method     ChildSpecialOffer requirePk($key, ?ConnectionInterface $con = null) Return the ChildSpecialOffer by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSpecialOffer requireOne(?ConnectionInterface $con = null) Return the first ChildSpecialOffer matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSpecialOffer requireOneById(int $id) Return the first ChildSpecialOffer filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSpecialOffer requireOneBySiteId(int $site_id) Return the first ChildSpecialOffer filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSpecialOffer requireOneByName(string $name) Return the first ChildSpecialOffer filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSpecialOffer requireOneByDescription(string $description) Return the first ChildSpecialOffer filtered by the description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSpecialOffer requireOneByTargetCollectionId(int $target_collection_id) Return the first ChildSpecialOffer filtered by the target_collection_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSpecialOffer requireOneByTargetQuantity(int $target_quantity) Return the first ChildSpecialOffer filtered by the target_quantity column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSpecialOffer requireOneByFreeArticleId(int $free_article_id) Return the first ChildSpecialOffer filtered by the free_article_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSpecialOffer requireOneByStartDate(string $start_date) Return the first ChildSpecialOffer filtered by the start_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSpecialOffer requireOneByEndDate(string $end_date) Return the first ChildSpecialOffer filtered by the end_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSpecialOffer requireOneByCreatedAt(string $created_at) Return the first ChildSpecialOffer filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSpecialOffer requireOneByUpdatedAt(string $updated_at) Return the first ChildSpecialOffer filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSpecialOffer[]|Collection find(?ConnectionInterface $con = null) Return ChildSpecialOffer objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildSpecialOffer> find(?ConnectionInterface $con = null) Return ChildSpecialOffer objects based on current ModelCriteria
 *
 * @method     ChildSpecialOffer[]|Collection findById(int|array<int> $id) Return ChildSpecialOffer objects filtered by the id column
 * @psalm-method Collection&\Traversable<ChildSpecialOffer> findById(int|array<int> $id) Return ChildSpecialOffer objects filtered by the id column
 * @method     ChildSpecialOffer[]|Collection findBySiteId(int|array<int> $site_id) Return ChildSpecialOffer objects filtered by the site_id column
 * @psalm-method Collection&\Traversable<ChildSpecialOffer> findBySiteId(int|array<int> $site_id) Return ChildSpecialOffer objects filtered by the site_id column
 * @method     ChildSpecialOffer[]|Collection findByName(string|array<string> $name) Return ChildSpecialOffer objects filtered by the name column
 * @psalm-method Collection&\Traversable<ChildSpecialOffer> findByName(string|array<string> $name) Return ChildSpecialOffer objects filtered by the name column
 * @method     ChildSpecialOffer[]|Collection findByDescription(string|array<string> $description) Return ChildSpecialOffer objects filtered by the description column
 * @psalm-method Collection&\Traversable<ChildSpecialOffer> findByDescription(string|array<string> $description) Return ChildSpecialOffer objects filtered by the description column
 * @method     ChildSpecialOffer[]|Collection findByTargetCollectionId(int|array<int> $target_collection_id) Return ChildSpecialOffer objects filtered by the target_collection_id column
 * @psalm-method Collection&\Traversable<ChildSpecialOffer> findByTargetCollectionId(int|array<int> $target_collection_id) Return ChildSpecialOffer objects filtered by the target_collection_id column
 * @method     ChildSpecialOffer[]|Collection findByTargetQuantity(int|array<int> $target_quantity) Return ChildSpecialOffer objects filtered by the target_quantity column
 * @psalm-method Collection&\Traversable<ChildSpecialOffer> findByTargetQuantity(int|array<int> $target_quantity) Return ChildSpecialOffer objects filtered by the target_quantity column
 * @method     ChildSpecialOffer[]|Collection findByFreeArticleId(int|array<int> $free_article_id) Return ChildSpecialOffer objects filtered by the free_article_id column
 * @psalm-method Collection&\Traversable<ChildSpecialOffer> findByFreeArticleId(int|array<int> $free_article_id) Return ChildSpecialOffer objects filtered by the free_article_id column
 * @method     ChildSpecialOffer[]|Collection findByStartDate(string|array<string> $start_date) Return ChildSpecialOffer objects filtered by the start_date column
 * @psalm-method Collection&\Traversable<ChildSpecialOffer> findByStartDate(string|array<string> $start_date) Return ChildSpecialOffer objects filtered by the start_date column
 * @method     ChildSpecialOffer[]|Collection findByEndDate(string|array<string> $end_date) Return ChildSpecialOffer objects filtered by the end_date column
 * @psalm-method Collection&\Traversable<ChildSpecialOffer> findByEndDate(string|array<string> $end_date) Return ChildSpecialOffer objects filtered by the end_date column
 * @method     ChildSpecialOffer[]|Collection findByCreatedAt(string|array<string> $created_at) Return ChildSpecialOffer objects filtered by the created_at column
 * @psalm-method Collection&\Traversable<ChildSpecialOffer> findByCreatedAt(string|array<string> $created_at) Return ChildSpecialOffer objects filtered by the created_at column
 * @method     ChildSpecialOffer[]|Collection findByUpdatedAt(string|array<string> $updated_at) Return ChildSpecialOffer objects filtered by the updated_at column
 * @psalm-method Collection&\Traversable<ChildSpecialOffer> findByUpdatedAt(string|array<string> $updated_at) Return ChildSpecialOffer objects filtered by the updated_at column
 *
 * @method     ChildSpecialOffer[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildSpecialOffer> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class SpecialOfferQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\SpecialOfferQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\SpecialOffer', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSpecialOfferQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSpecialOfferQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildSpecialOfferQuery) {
            return $criteria;
        }
        $query = new ChildSpecialOfferQuery();
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
     * @return ChildSpecialOffer|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SpecialOfferTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = SpecialOfferTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildSpecialOffer A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, site_id, name, description, target_collection_id, target_quantity, free_article_id, start_date, end_date, created_at, updated_at FROM special_offers WHERE id = :p0';
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
            /** @var ChildSpecialOffer $obj */
            $obj = new ChildSpecialOffer();
            $obj->hydrate($row);
            SpecialOfferTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildSpecialOffer|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(SpecialOfferTableMap::COL_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(SpecialOfferTableMap::COL_ID, $keys, Criteria::IN);

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
                $this->addUsingAlias(SpecialOfferTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SpecialOfferTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SpecialOfferTableMap::COL_ID, $id, $comparison);

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
     * @see       filterBySite()
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
                $this->addUsingAlias(SpecialOfferTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(SpecialOfferTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SpecialOfferTableMap::COL_SITE_ID, $siteId, $comparison);

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

        $this->addUsingAlias(SpecialOfferTableMap::COL_NAME, $name, $comparison);

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

        $this->addUsingAlias(SpecialOfferTableMap::COL_DESCRIPTION, $description, $comparison);

        return $this;
    }

    /**
     * Filter the query on the target_collection_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTargetCollectionId(1234); // WHERE target_collection_id = 1234
     * $query->filterByTargetCollectionId(array(12, 34)); // WHERE target_collection_id IN (12, 34)
     * $query->filterByTargetCollectionId(array('min' => 12)); // WHERE target_collection_id > 12
     * </code>
     *
     * @see       filterByTargetCollection()
     *
     * @param mixed $targetCollectionId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTargetCollectionId($targetCollectionId = null, ?string $comparison = null)
    {
        if (is_array($targetCollectionId)) {
            $useMinMax = false;
            if (isset($targetCollectionId['min'])) {
                $this->addUsingAlias(SpecialOfferTableMap::COL_TARGET_COLLECTION_ID, $targetCollectionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($targetCollectionId['max'])) {
                $this->addUsingAlias(SpecialOfferTableMap::COL_TARGET_COLLECTION_ID, $targetCollectionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SpecialOfferTableMap::COL_TARGET_COLLECTION_ID, $targetCollectionId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the target_quantity column
     *
     * Example usage:
     * <code>
     * $query->filterByTargetQuantity(1234); // WHERE target_quantity = 1234
     * $query->filterByTargetQuantity(array(12, 34)); // WHERE target_quantity IN (12, 34)
     * $query->filterByTargetQuantity(array('min' => 12)); // WHERE target_quantity > 12
     * </code>
     *
     * @param mixed $targetQuantity The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTargetQuantity($targetQuantity = null, ?string $comparison = null)
    {
        if (is_array($targetQuantity)) {
            $useMinMax = false;
            if (isset($targetQuantity['min'])) {
                $this->addUsingAlias(SpecialOfferTableMap::COL_TARGET_QUANTITY, $targetQuantity['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($targetQuantity['max'])) {
                $this->addUsingAlias(SpecialOfferTableMap::COL_TARGET_QUANTITY, $targetQuantity['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SpecialOfferTableMap::COL_TARGET_QUANTITY, $targetQuantity, $comparison);

        return $this;
    }

    /**
     * Filter the query on the free_article_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFreeArticleId(1234); // WHERE free_article_id = 1234
     * $query->filterByFreeArticleId(array(12, 34)); // WHERE free_article_id IN (12, 34)
     * $query->filterByFreeArticleId(array('min' => 12)); // WHERE free_article_id > 12
     * </code>
     *
     * @see       filterByFreeArticle()
     *
     * @param mixed $freeArticleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFreeArticleId($freeArticleId = null, ?string $comparison = null)
    {
        if (is_array($freeArticleId)) {
            $useMinMax = false;
            if (isset($freeArticleId['min'])) {
                $this->addUsingAlias(SpecialOfferTableMap::COL_FREE_ARTICLE_ID, $freeArticleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($freeArticleId['max'])) {
                $this->addUsingAlias(SpecialOfferTableMap::COL_FREE_ARTICLE_ID, $freeArticleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SpecialOfferTableMap::COL_FREE_ARTICLE_ID, $freeArticleId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the start_date column
     *
     * Example usage:
     * <code>
     * $query->filterByStartDate('2011-03-14'); // WHERE start_date = '2011-03-14'
     * $query->filterByStartDate('now'); // WHERE start_date = '2011-03-14'
     * $query->filterByStartDate(array('max' => 'yesterday')); // WHERE start_date > '2011-03-13'
     * </code>
     *
     * @param mixed $startDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByStartDate($startDate = null, ?string $comparison = null)
    {
        if (is_array($startDate)) {
            $useMinMax = false;
            if (isset($startDate['min'])) {
                $this->addUsingAlias(SpecialOfferTableMap::COL_START_DATE, $startDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($startDate['max'])) {
                $this->addUsingAlias(SpecialOfferTableMap::COL_START_DATE, $startDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SpecialOfferTableMap::COL_START_DATE, $startDate, $comparison);

        return $this;
    }

    /**
     * Filter the query on the end_date column
     *
     * Example usage:
     * <code>
     * $query->filterByEndDate('2011-03-14'); // WHERE end_date = '2011-03-14'
     * $query->filterByEndDate('now'); // WHERE end_date = '2011-03-14'
     * $query->filterByEndDate(array('max' => 'yesterday')); // WHERE end_date > '2011-03-13'
     * </code>
     *
     * @param mixed $endDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEndDate($endDate = null, ?string $comparison = null)
    {
        if (is_array($endDate)) {
            $useMinMax = false;
            if (isset($endDate['min'])) {
                $this->addUsingAlias(SpecialOfferTableMap::COL_END_DATE, $endDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($endDate['max'])) {
                $this->addUsingAlias(SpecialOfferTableMap::COL_END_DATE, $endDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SpecialOfferTableMap::COL_END_DATE, $endDate, $comparison);

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
                $this->addUsingAlias(SpecialOfferTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(SpecialOfferTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SpecialOfferTableMap::COL_CREATED_AT, $createdAt, $comparison);

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
                $this->addUsingAlias(SpecialOfferTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(SpecialOfferTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SpecialOfferTableMap::COL_UPDATED_AT, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \Model\Site object
     *
     * @param \Model\Site|ObjectCollection $site The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySite($site, ?string $comparison = null)
    {
        if ($site instanceof \Model\Site) {
            return $this
                ->addUsingAlias(SpecialOfferTableMap::COL_SITE_ID, $site->getId(), $comparison);
        } elseif ($site instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(SpecialOfferTableMap::COL_SITE_ID, $site->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterBySite() only accepts arguments of type \Model\Site or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Site relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinSite(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Site');

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
            $this->addJoinObject($join, 'Site');
        }

        return $this;
    }

    /**
     * Use the Site relation Site object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\SiteQuery A secondary query class using the current class as primary query
     */
    public function useSiteQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSite($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Site', '\Model\SiteQuery');
    }

    /**
     * Use the Site relation Site object
     *
     * @param callable(\Model\SiteQuery):\Model\SiteQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withSiteQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useSiteQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Site table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\SiteQuery The inner query object of the EXISTS statement
     */
    public function useSiteExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\SiteQuery */
        $q = $this->useExistsQuery('Site', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Site table for a NOT EXISTS query.
     *
     * @see useSiteExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\SiteQuery The inner query object of the NOT EXISTS statement
     */
    public function useSiteNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\SiteQuery */
        $q = $this->useExistsQuery('Site', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Site table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\SiteQuery The inner query object of the IN statement
     */
    public function useInSiteQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\SiteQuery */
        $q = $this->useInQuery('Site', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Site table for a NOT IN query.
     *
     * @see useSiteInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\SiteQuery The inner query object of the NOT IN statement
     */
    public function useNotInSiteQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\SiteQuery */
        $q = $this->useInQuery('Site', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\BookCollection object
     *
     * @param \Model\BookCollection|ObjectCollection $bookCollection The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTargetCollection($bookCollection, ?string $comparison = null)
    {
        if ($bookCollection instanceof \Model\BookCollection) {
            return $this
                ->addUsingAlias(SpecialOfferTableMap::COL_TARGET_COLLECTION_ID, $bookCollection->getId(), $comparison);
        } elseif ($bookCollection instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(SpecialOfferTableMap::COL_TARGET_COLLECTION_ID, $bookCollection->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByTargetCollection() only accepts arguments of type \Model\BookCollection or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the TargetCollection relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinTargetCollection(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('TargetCollection');

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
            $this->addJoinObject($join, 'TargetCollection');
        }

        return $this;
    }

    /**
     * Use the TargetCollection relation BookCollection object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\BookCollectionQuery A secondary query class using the current class as primary query
     */
    public function useTargetCollectionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTargetCollection($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'TargetCollection', '\Model\BookCollectionQuery');
    }

    /**
     * Use the TargetCollection relation BookCollection object
     *
     * @param callable(\Model\BookCollectionQuery):\Model\BookCollectionQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withTargetCollectionQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useTargetCollectionQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the TargetCollection relation to the BookCollection table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\BookCollectionQuery The inner query object of the EXISTS statement
     */
    public function useTargetCollectionExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\BookCollectionQuery */
        $q = $this->useExistsQuery('TargetCollection', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the TargetCollection relation to the BookCollection table for a NOT EXISTS query.
     *
     * @see useTargetCollectionExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\BookCollectionQuery The inner query object of the NOT EXISTS statement
     */
    public function useTargetCollectionNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\BookCollectionQuery */
        $q = $this->useExistsQuery('TargetCollection', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the TargetCollection relation to the BookCollection table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\BookCollectionQuery The inner query object of the IN statement
     */
    public function useInTargetCollectionQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\BookCollectionQuery */
        $q = $this->useInQuery('TargetCollection', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the TargetCollection relation to the BookCollection table for a NOT IN query.
     *
     * @see useTargetCollectionInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\BookCollectionQuery The inner query object of the NOT IN statement
     */
    public function useNotInTargetCollectionQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\BookCollectionQuery */
        $q = $this->useInQuery('TargetCollection', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Article object
     *
     * @param \Model\Article|ObjectCollection $article The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFreeArticle($article, ?string $comparison = null)
    {
        if ($article instanceof \Model\Article) {
            return $this
                ->addUsingAlias(SpecialOfferTableMap::COL_FREE_ARTICLE_ID, $article->getId(), $comparison);
        } elseif ($article instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(SpecialOfferTableMap::COL_FREE_ARTICLE_ID, $article->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByFreeArticle() only accepts arguments of type \Model\Article or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FreeArticle relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinFreeArticle(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FreeArticle');

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
            $this->addJoinObject($join, 'FreeArticle');
        }

        return $this;
    }

    /**
     * Use the FreeArticle relation Article object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\ArticleQuery A secondary query class using the current class as primary query
     */
    public function useFreeArticleQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFreeArticle($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FreeArticle', '\Model\ArticleQuery');
    }

    /**
     * Use the FreeArticle relation Article object
     *
     * @param callable(\Model\ArticleQuery):\Model\ArticleQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withFreeArticleQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useFreeArticleQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the FreeArticle relation to the Article table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\ArticleQuery The inner query object of the EXISTS statement
     */
    public function useFreeArticleExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\ArticleQuery */
        $q = $this->useExistsQuery('FreeArticle', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the FreeArticle relation to the Article table for a NOT EXISTS query.
     *
     * @see useFreeArticleExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\ArticleQuery The inner query object of the NOT EXISTS statement
     */
    public function useFreeArticleNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\ArticleQuery */
        $q = $this->useExistsQuery('FreeArticle', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the FreeArticle relation to the Article table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\ArticleQuery The inner query object of the IN statement
     */
    public function useInFreeArticleQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\ArticleQuery */
        $q = $this->useInQuery('FreeArticle', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the FreeArticle relation to the Article table for a NOT IN query.
     *
     * @see useFreeArticleInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\ArticleQuery The inner query object of the NOT IN statement
     */
    public function useNotInFreeArticleQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\ArticleQuery */
        $q = $this->useInQuery('FreeArticle', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Exclude object from result
     *
     * @param ChildSpecialOffer $specialOffer Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($specialOffer = null)
    {
        if ($specialOffer) {
            $this->addUsingAlias(SpecialOfferTableMap::COL_ID, $specialOffer->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the special_offers table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SpecialOfferTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SpecialOfferTableMap::clearInstancePool();
            SpecialOfferTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(SpecialOfferTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SpecialOfferTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SpecialOfferTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SpecialOfferTableMap::clearRelatedInstancePool();

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
        $this->addUsingAlias(SpecialOfferTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(SpecialOfferTableMap::COL_UPDATED_AT);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(SpecialOfferTableMap::COL_UPDATED_AT);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(SpecialOfferTableMap::COL_CREATED_AT);

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
        $this->addUsingAlias(SpecialOfferTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(SpecialOfferTableMap::COL_CREATED_AT);

        return $this;
    }

}
