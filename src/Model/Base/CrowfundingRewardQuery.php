<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\CrowfundingReward as ChildCrowfundingReward;
use Model\CrowfundingRewardQuery as ChildCrowfundingRewardQuery;
use Model\Map\CrowfundingRewardTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'cf_rewards' table.
 *
 *
 *
 * @method     ChildCrowfundingRewardQuery orderById($order = Criteria::ASC) Order by the reward_id column
 * @method     ChildCrowfundingRewardQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildCrowfundingRewardQuery orderByCampaignId($order = Criteria::ASC) Order by the campaign_id column
 * @method     ChildCrowfundingRewardQuery orderByContent($order = Criteria::ASC) Order by the reward_content column
 * @method     ChildCrowfundingRewardQuery orderByArticles($order = Criteria::ASC) Order by the reward_articles column
 * @method     ChildCrowfundingRewardQuery orderByPrice($order = Criteria::ASC) Order by the reward_price column
 * @method     ChildCrowfundingRewardQuery orderByLimited($order = Criteria::ASC) Order by the reward_limited column
 * @method     ChildCrowfundingRewardQuery orderByHighlighted($order = Criteria::ASC) Order by the reward_highlighted column
 * @method     ChildCrowfundingRewardQuery orderByImage($order = Criteria::ASC) Order by the reward_image column
 * @method     ChildCrowfundingRewardQuery orderByQuantity($order = Criteria::ASC) Order by the reward_quantity column
 * @method     ChildCrowfundingRewardQuery orderByBackers($order = Criteria::ASC) Order by the reward_backers column
 * @method     ChildCrowfundingRewardQuery orderByCreatedAt($order = Criteria::ASC) Order by the reward_created column
 * @method     ChildCrowfundingRewardQuery orderByUpdatedAt($order = Criteria::ASC) Order by the reward_updated column
 * @method     ChildCrowfundingRewardQuery orderByDeletedAt($order = Criteria::ASC) Order by the reward_deleted column
 *
 * @method     ChildCrowfundingRewardQuery groupById() Group by the reward_id column
 * @method     ChildCrowfundingRewardQuery groupBySiteId() Group by the site_id column
 * @method     ChildCrowfundingRewardQuery groupByCampaignId() Group by the campaign_id column
 * @method     ChildCrowfundingRewardQuery groupByContent() Group by the reward_content column
 * @method     ChildCrowfundingRewardQuery groupByArticles() Group by the reward_articles column
 * @method     ChildCrowfundingRewardQuery groupByPrice() Group by the reward_price column
 * @method     ChildCrowfundingRewardQuery groupByLimited() Group by the reward_limited column
 * @method     ChildCrowfundingRewardQuery groupByHighlighted() Group by the reward_highlighted column
 * @method     ChildCrowfundingRewardQuery groupByImage() Group by the reward_image column
 * @method     ChildCrowfundingRewardQuery groupByQuantity() Group by the reward_quantity column
 * @method     ChildCrowfundingRewardQuery groupByBackers() Group by the reward_backers column
 * @method     ChildCrowfundingRewardQuery groupByCreatedAt() Group by the reward_created column
 * @method     ChildCrowfundingRewardQuery groupByUpdatedAt() Group by the reward_updated column
 * @method     ChildCrowfundingRewardQuery groupByDeletedAt() Group by the reward_deleted column
 *
 * @method     ChildCrowfundingRewardQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCrowfundingRewardQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCrowfundingRewardQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCrowfundingRewardQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildCrowfundingRewardQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildCrowfundingRewardQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildCrowfundingReward|null findOne(ConnectionInterface $con = null) Return the first ChildCrowfundingReward matching the query
 * @method     ChildCrowfundingReward findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCrowfundingReward matching the query, or a new ChildCrowfundingReward object populated from the query conditions when no match is found
 *
 * @method     ChildCrowfundingReward|null findOneById(int $reward_id) Return the first ChildCrowfundingReward filtered by the reward_id column
 * @method     ChildCrowfundingReward|null findOneBySiteId(int $site_id) Return the first ChildCrowfundingReward filtered by the site_id column
 * @method     ChildCrowfundingReward|null findOneByCampaignId(int $campaign_id) Return the first ChildCrowfundingReward filtered by the campaign_id column
 * @method     ChildCrowfundingReward|null findOneByContent(string $reward_content) Return the first ChildCrowfundingReward filtered by the reward_content column
 * @method     ChildCrowfundingReward|null findOneByArticles(string $reward_articles) Return the first ChildCrowfundingReward filtered by the reward_articles column
 * @method     ChildCrowfundingReward|null findOneByPrice(int $reward_price) Return the first ChildCrowfundingReward filtered by the reward_price column
 * @method     ChildCrowfundingReward|null findOneByLimited(boolean $reward_limited) Return the first ChildCrowfundingReward filtered by the reward_limited column
 * @method     ChildCrowfundingReward|null findOneByHighlighted(boolean $reward_highlighted) Return the first ChildCrowfundingReward filtered by the reward_highlighted column
 * @method     ChildCrowfundingReward|null findOneByImage(string $reward_image) Return the first ChildCrowfundingReward filtered by the reward_image column
 * @method     ChildCrowfundingReward|null findOneByQuantity(int $reward_quantity) Return the first ChildCrowfundingReward filtered by the reward_quantity column
 * @method     ChildCrowfundingReward|null findOneByBackers(int $reward_backers) Return the first ChildCrowfundingReward filtered by the reward_backers column
 * @method     ChildCrowfundingReward|null findOneByCreatedAt(string $reward_created) Return the first ChildCrowfundingReward filtered by the reward_created column
 * @method     ChildCrowfundingReward|null findOneByUpdatedAt(string $reward_updated) Return the first ChildCrowfundingReward filtered by the reward_updated column
 * @method     ChildCrowfundingReward|null findOneByDeletedAt(string $reward_deleted) Return the first ChildCrowfundingReward filtered by the reward_deleted column *

 * @method     ChildCrowfundingReward requirePk($key, ConnectionInterface $con = null) Return the ChildCrowfundingReward by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCrowfundingReward requireOne(ConnectionInterface $con = null) Return the first ChildCrowfundingReward matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCrowfundingReward requireOneById(int $reward_id) Return the first ChildCrowfundingReward filtered by the reward_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCrowfundingReward requireOneBySiteId(int $site_id) Return the first ChildCrowfundingReward filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCrowfundingReward requireOneByCampaignId(int $campaign_id) Return the first ChildCrowfundingReward filtered by the campaign_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCrowfundingReward requireOneByContent(string $reward_content) Return the first ChildCrowfundingReward filtered by the reward_content column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCrowfundingReward requireOneByArticles(string $reward_articles) Return the first ChildCrowfundingReward filtered by the reward_articles column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCrowfundingReward requireOneByPrice(int $reward_price) Return the first ChildCrowfundingReward filtered by the reward_price column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCrowfundingReward requireOneByLimited(boolean $reward_limited) Return the first ChildCrowfundingReward filtered by the reward_limited column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCrowfundingReward requireOneByHighlighted(boolean $reward_highlighted) Return the first ChildCrowfundingReward filtered by the reward_highlighted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCrowfundingReward requireOneByImage(string $reward_image) Return the first ChildCrowfundingReward filtered by the reward_image column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCrowfundingReward requireOneByQuantity(int $reward_quantity) Return the first ChildCrowfundingReward filtered by the reward_quantity column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCrowfundingReward requireOneByBackers(int $reward_backers) Return the first ChildCrowfundingReward filtered by the reward_backers column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCrowfundingReward requireOneByCreatedAt(string $reward_created) Return the first ChildCrowfundingReward filtered by the reward_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCrowfundingReward requireOneByUpdatedAt(string $reward_updated) Return the first ChildCrowfundingReward filtered by the reward_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCrowfundingReward requireOneByDeletedAt(string $reward_deleted) Return the first ChildCrowfundingReward filtered by the reward_deleted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCrowfundingReward[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildCrowfundingReward objects based on current ModelCriteria
 * @psalm-method ObjectCollection&\Traversable<ChildCrowfundingReward> find(ConnectionInterface $con = null) Return ChildCrowfundingReward objects based on current ModelCriteria
 * @method     ChildCrowfundingReward[]|ObjectCollection findById(int $reward_id) Return ChildCrowfundingReward objects filtered by the reward_id column
 * @psalm-method ObjectCollection&\Traversable<ChildCrowfundingReward> findById(int $reward_id) Return ChildCrowfundingReward objects filtered by the reward_id column
 * @method     ChildCrowfundingReward[]|ObjectCollection findBySiteId(int $site_id) Return ChildCrowfundingReward objects filtered by the site_id column
 * @psalm-method ObjectCollection&\Traversable<ChildCrowfundingReward> findBySiteId(int $site_id) Return ChildCrowfundingReward objects filtered by the site_id column
 * @method     ChildCrowfundingReward[]|ObjectCollection findByCampaignId(int $campaign_id) Return ChildCrowfundingReward objects filtered by the campaign_id column
 * @psalm-method ObjectCollection&\Traversable<ChildCrowfundingReward> findByCampaignId(int $campaign_id) Return ChildCrowfundingReward objects filtered by the campaign_id column
 * @method     ChildCrowfundingReward[]|ObjectCollection findByContent(string $reward_content) Return ChildCrowfundingReward objects filtered by the reward_content column
 * @psalm-method ObjectCollection&\Traversable<ChildCrowfundingReward> findByContent(string $reward_content) Return ChildCrowfundingReward objects filtered by the reward_content column
 * @method     ChildCrowfundingReward[]|ObjectCollection findByArticles(string $reward_articles) Return ChildCrowfundingReward objects filtered by the reward_articles column
 * @psalm-method ObjectCollection&\Traversable<ChildCrowfundingReward> findByArticles(string $reward_articles) Return ChildCrowfundingReward objects filtered by the reward_articles column
 * @method     ChildCrowfundingReward[]|ObjectCollection findByPrice(int $reward_price) Return ChildCrowfundingReward objects filtered by the reward_price column
 * @psalm-method ObjectCollection&\Traversable<ChildCrowfundingReward> findByPrice(int $reward_price) Return ChildCrowfundingReward objects filtered by the reward_price column
 * @method     ChildCrowfundingReward[]|ObjectCollection findByLimited(boolean $reward_limited) Return ChildCrowfundingReward objects filtered by the reward_limited column
 * @psalm-method ObjectCollection&\Traversable<ChildCrowfundingReward> findByLimited(boolean $reward_limited) Return ChildCrowfundingReward objects filtered by the reward_limited column
 * @method     ChildCrowfundingReward[]|ObjectCollection findByHighlighted(boolean $reward_highlighted) Return ChildCrowfundingReward objects filtered by the reward_highlighted column
 * @psalm-method ObjectCollection&\Traversable<ChildCrowfundingReward> findByHighlighted(boolean $reward_highlighted) Return ChildCrowfundingReward objects filtered by the reward_highlighted column
 * @method     ChildCrowfundingReward[]|ObjectCollection findByImage(string $reward_image) Return ChildCrowfundingReward objects filtered by the reward_image column
 * @psalm-method ObjectCollection&\Traversable<ChildCrowfundingReward> findByImage(string $reward_image) Return ChildCrowfundingReward objects filtered by the reward_image column
 * @method     ChildCrowfundingReward[]|ObjectCollection findByQuantity(int $reward_quantity) Return ChildCrowfundingReward objects filtered by the reward_quantity column
 * @psalm-method ObjectCollection&\Traversable<ChildCrowfundingReward> findByQuantity(int $reward_quantity) Return ChildCrowfundingReward objects filtered by the reward_quantity column
 * @method     ChildCrowfundingReward[]|ObjectCollection findByBackers(int $reward_backers) Return ChildCrowfundingReward objects filtered by the reward_backers column
 * @psalm-method ObjectCollection&\Traversable<ChildCrowfundingReward> findByBackers(int $reward_backers) Return ChildCrowfundingReward objects filtered by the reward_backers column
 * @method     ChildCrowfundingReward[]|ObjectCollection findByCreatedAt(string $reward_created) Return ChildCrowfundingReward objects filtered by the reward_created column
 * @psalm-method ObjectCollection&\Traversable<ChildCrowfundingReward> findByCreatedAt(string $reward_created) Return ChildCrowfundingReward objects filtered by the reward_created column
 * @method     ChildCrowfundingReward[]|ObjectCollection findByUpdatedAt(string $reward_updated) Return ChildCrowfundingReward objects filtered by the reward_updated column
 * @psalm-method ObjectCollection&\Traversable<ChildCrowfundingReward> findByUpdatedAt(string $reward_updated) Return ChildCrowfundingReward objects filtered by the reward_updated column
 * @method     ChildCrowfundingReward[]|ObjectCollection findByDeletedAt(string $reward_deleted) Return ChildCrowfundingReward objects filtered by the reward_deleted column
 * @psalm-method ObjectCollection&\Traversable<ChildCrowfundingReward> findByDeletedAt(string $reward_deleted) Return ChildCrowfundingReward objects filtered by the reward_deleted column
 * @method     ChildCrowfundingReward[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildCrowfundingReward> paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class CrowfundingRewardQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\CrowfundingRewardQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\CrowfundingReward', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCrowfundingRewardQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCrowfundingRewardQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildCrowfundingRewardQuery) {
            return $criteria;
        }
        $query = new ChildCrowfundingRewardQuery();
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
     * @return ChildCrowfundingReward|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CrowfundingRewardTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = CrowfundingRewardTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildCrowfundingReward A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT reward_id, site_id, campaign_id, reward_content, reward_articles, reward_price, reward_limited, reward_highlighted, reward_image, reward_quantity, reward_backers, reward_created, reward_updated, reward_deleted FROM cf_rewards WHERE reward_id = :p0';
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
            /** @var ChildCrowfundingReward $obj */
            $obj = new ChildCrowfundingReward();
            $obj->hydrate($row);
            CrowfundingRewardTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildCrowfundingReward|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildCrowfundingRewardQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildCrowfundingRewardQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the reward_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE reward_id = 1234
     * $query->filterById(array(12, 34)); // WHERE reward_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE reward_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCrowfundingRewardQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_ID, $id, $comparison);
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
     * @return $this|ChildCrowfundingRewardQuery The current query, for fluid interface
     */
    public function filterBySiteId($siteId = null, $comparison = null)
    {
        if (is_array($siteId)) {
            $useMinMax = false;
            if (isset($siteId['min'])) {
                $this->addUsingAlias(CrowfundingRewardTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(CrowfundingRewardTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CrowfundingRewardTableMap::COL_SITE_ID, $siteId, $comparison);
    }

    /**
     * Filter the query on the campaign_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCampaignId(1234); // WHERE campaign_id = 1234
     * $query->filterByCampaignId(array(12, 34)); // WHERE campaign_id IN (12, 34)
     * $query->filterByCampaignId(array('min' => 12)); // WHERE campaign_id > 12
     * </code>
     *
     * @param     mixed $campaignId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCrowfundingRewardQuery The current query, for fluid interface
     */
    public function filterByCampaignId($campaignId = null, $comparison = null)
    {
        if (is_array($campaignId)) {
            $useMinMax = false;
            if (isset($campaignId['min'])) {
                $this->addUsingAlias(CrowfundingRewardTableMap::COL_CAMPAIGN_ID, $campaignId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($campaignId['max'])) {
                $this->addUsingAlias(CrowfundingRewardTableMap::COL_CAMPAIGN_ID, $campaignId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CrowfundingRewardTableMap::COL_CAMPAIGN_ID, $campaignId, $comparison);
    }

    /**
     * Filter the query on the reward_content column
     *
     * Example usage:
     * <code>
     * $query->filterByContent('fooValue');   // WHERE reward_content = 'fooValue'
     * $query->filterByContent('%fooValue%', Criteria::LIKE); // WHERE reward_content LIKE '%fooValue%'
     * </code>
     *
     * @param     string $content The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCrowfundingRewardQuery The current query, for fluid interface
     */
    public function filterByContent($content = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($content)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_CONTENT, $content, $comparison);
    }

    /**
     * Filter the query on the reward_articles column
     *
     * Example usage:
     * <code>
     * $query->filterByArticles('fooValue');   // WHERE reward_articles = 'fooValue'
     * $query->filterByArticles('%fooValue%', Criteria::LIKE); // WHERE reward_articles LIKE '%fooValue%'
     * </code>
     *
     * @param     string $articles The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCrowfundingRewardQuery The current query, for fluid interface
     */
    public function filterByArticles($articles = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($articles)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_ARTICLES, $articles, $comparison);
    }

    /**
     * Filter the query on the reward_price column
     *
     * Example usage:
     * <code>
     * $query->filterByPrice(1234); // WHERE reward_price = 1234
     * $query->filterByPrice(array(12, 34)); // WHERE reward_price IN (12, 34)
     * $query->filterByPrice(array('min' => 12)); // WHERE reward_price > 12
     * </code>
     *
     * @param     mixed $price The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCrowfundingRewardQuery The current query, for fluid interface
     */
    public function filterByPrice($price = null, $comparison = null)
    {
        if (is_array($price)) {
            $useMinMax = false;
            if (isset($price['min'])) {
                $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_PRICE, $price['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($price['max'])) {
                $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_PRICE, $price['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_PRICE, $price, $comparison);
    }

    /**
     * Filter the query on the reward_limited column
     *
     * Example usage:
     * <code>
     * $query->filterByLimited(true); // WHERE reward_limited = true
     * $query->filterByLimited('yes'); // WHERE reward_limited = true
     * </code>
     *
     * @param     boolean|string $limited The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCrowfundingRewardQuery The current query, for fluid interface
     */
    public function filterByLimited($limited = null, $comparison = null)
    {
        if (is_string($limited)) {
            $limited = in_array(strtolower($limited), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_LIMITED, $limited, $comparison);
    }

    /**
     * Filter the query on the reward_highlighted column
     *
     * Example usage:
     * <code>
     * $query->filterByHighlighted(true); // WHERE reward_highlighted = true
     * $query->filterByHighlighted('yes'); // WHERE reward_highlighted = true
     * </code>
     *
     * @param     boolean|string $highlighted The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCrowfundingRewardQuery The current query, for fluid interface
     */
    public function filterByHighlighted($highlighted = null, $comparison = null)
    {
        if (is_string($highlighted)) {
            $highlighted = in_array(strtolower($highlighted), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_HIGHLIGHTED, $highlighted, $comparison);
    }

    /**
     * Filter the query on the reward_image column
     *
     * Example usage:
     * <code>
     * $query->filterByImage('fooValue');   // WHERE reward_image = 'fooValue'
     * $query->filterByImage('%fooValue%', Criteria::LIKE); // WHERE reward_image LIKE '%fooValue%'
     * </code>
     *
     * @param     string $image The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCrowfundingRewardQuery The current query, for fluid interface
     */
    public function filterByImage($image = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($image)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_IMAGE, $image, $comparison);
    }

    /**
     * Filter the query on the reward_quantity column
     *
     * Example usage:
     * <code>
     * $query->filterByQuantity(1234); // WHERE reward_quantity = 1234
     * $query->filterByQuantity(array(12, 34)); // WHERE reward_quantity IN (12, 34)
     * $query->filterByQuantity(array('min' => 12)); // WHERE reward_quantity > 12
     * </code>
     *
     * @param     mixed $quantity The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCrowfundingRewardQuery The current query, for fluid interface
     */
    public function filterByQuantity($quantity = null, $comparison = null)
    {
        if (is_array($quantity)) {
            $useMinMax = false;
            if (isset($quantity['min'])) {
                $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_QUANTITY, $quantity['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($quantity['max'])) {
                $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_QUANTITY, $quantity['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_QUANTITY, $quantity, $comparison);
    }

    /**
     * Filter the query on the reward_backers column
     *
     * Example usage:
     * <code>
     * $query->filterByBackers(1234); // WHERE reward_backers = 1234
     * $query->filterByBackers(array(12, 34)); // WHERE reward_backers IN (12, 34)
     * $query->filterByBackers(array('min' => 12)); // WHERE reward_backers > 12
     * </code>
     *
     * @param     mixed $backers The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCrowfundingRewardQuery The current query, for fluid interface
     */
    public function filterByBackers($backers = null, $comparison = null)
    {
        if (is_array($backers)) {
            $useMinMax = false;
            if (isset($backers['min'])) {
                $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_BACKERS, $backers['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($backers['max'])) {
                $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_BACKERS, $backers['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_BACKERS, $backers, $comparison);
    }

    /**
     * Filter the query on the reward_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE reward_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE reward_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE reward_created > '2011-03-13'
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
     * @return $this|ChildCrowfundingRewardQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_CREATED, $createdAt, $comparison);
    }

    /**
     * Filter the query on the reward_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE reward_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE reward_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE reward_updated > '2011-03-13'
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
     * @return $this|ChildCrowfundingRewardQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the reward_deleted column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletedAt('2011-03-14'); // WHERE reward_deleted = '2011-03-14'
     * $query->filterByDeletedAt('now'); // WHERE reward_deleted = '2011-03-14'
     * $query->filterByDeletedAt(array('max' => 'yesterday')); // WHERE reward_deleted > '2011-03-13'
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
     * @return $this|ChildCrowfundingRewardQuery The current query, for fluid interface
     */
    public function filterByDeletedAt($deletedAt = null, $comparison = null)
    {
        if (is_array($deletedAt)) {
            $useMinMax = false;
            if (isset($deletedAt['min'])) {
                $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_DELETED, $deletedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletedAt['max'])) {
                $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_DELETED, $deletedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_DELETED, $deletedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildCrowfundingReward $crowfundingReward Object to remove from the list of results
     *
     * @return $this|ChildCrowfundingRewardQuery The current query, for fluid interface
     */
    public function prune($crowfundingReward = null)
    {
        if ($crowfundingReward) {
            $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_ID, $crowfundingReward->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the cf_rewards table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CrowfundingRewardTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CrowfundingRewardTableMap::clearInstancePool();
            CrowfundingRewardTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(CrowfundingRewardTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CrowfundingRewardTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            CrowfundingRewardTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CrowfundingRewardTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildCrowfundingRewardQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildCrowfundingRewardQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(CrowfundingRewardTableMap::COL_REWARD_UPDATED);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildCrowfundingRewardQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(CrowfundingRewardTableMap::COL_REWARD_UPDATED);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildCrowfundingRewardQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(CrowfundingRewardTableMap::COL_REWARD_CREATED);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildCrowfundingRewardQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(CrowfundingRewardTableMap::COL_REWARD_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildCrowfundingRewardQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(CrowfundingRewardTableMap::COL_REWARD_CREATED);
    }

} // CrowfundingRewardQuery
