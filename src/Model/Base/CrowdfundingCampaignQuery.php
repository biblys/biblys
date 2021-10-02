<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\CrowdfundingCampaign as ChildCrowdfundingCampaign;
use Model\CrowdfundingCampaignQuery as ChildCrowdfundingCampaignQuery;
use Model\Map\CrowdfundingCampaignTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'cf_campaigns' table.
 *
 *
 *
 * @method     ChildCrowdfundingCampaignQuery orderById($order = Criteria::ASC) Order by the campaign_id column
 * @method     ChildCrowdfundingCampaignQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildCrowdfundingCampaignQuery orderByTitle($order = Criteria::ASC) Order by the campaign_title column
 * @method     ChildCrowdfundingCampaignQuery orderByUrl($order = Criteria::ASC) Order by the campaign_url column
 * @method     ChildCrowdfundingCampaignQuery orderByDescription($order = Criteria::ASC) Order by the campaign_description column
 * @method     ChildCrowdfundingCampaignQuery orderByImage($order = Criteria::ASC) Order by the campaign_image column
 * @method     ChildCrowdfundingCampaignQuery orderByGoal($order = Criteria::ASC) Order by the campaign_goal column
 * @method     ChildCrowdfundingCampaignQuery orderByPledged($order = Criteria::ASC) Order by the campaign_pledged column
 * @method     ChildCrowdfundingCampaignQuery orderByBackers($order = Criteria::ASC) Order by the campaign_backers column
 * @method     ChildCrowdfundingCampaignQuery orderByStarts($order = Criteria::ASC) Order by the campaign_starts column
 * @method     ChildCrowdfundingCampaignQuery orderByEnds($order = Criteria::ASC) Order by the campaign_ends column
 * @method     ChildCrowdfundingCampaignQuery orderByCreatedAt($order = Criteria::ASC) Order by the campaign_created column
 * @method     ChildCrowdfundingCampaignQuery orderByUpdatedAt($order = Criteria::ASC) Order by the campaign_updated column
 *
 * @method     ChildCrowdfundingCampaignQuery groupById() Group by the campaign_id column
 * @method     ChildCrowdfundingCampaignQuery groupBySiteId() Group by the site_id column
 * @method     ChildCrowdfundingCampaignQuery groupByTitle() Group by the campaign_title column
 * @method     ChildCrowdfundingCampaignQuery groupByUrl() Group by the campaign_url column
 * @method     ChildCrowdfundingCampaignQuery groupByDescription() Group by the campaign_description column
 * @method     ChildCrowdfundingCampaignQuery groupByImage() Group by the campaign_image column
 * @method     ChildCrowdfundingCampaignQuery groupByGoal() Group by the campaign_goal column
 * @method     ChildCrowdfundingCampaignQuery groupByPledged() Group by the campaign_pledged column
 * @method     ChildCrowdfundingCampaignQuery groupByBackers() Group by the campaign_backers column
 * @method     ChildCrowdfundingCampaignQuery groupByStarts() Group by the campaign_starts column
 * @method     ChildCrowdfundingCampaignQuery groupByEnds() Group by the campaign_ends column
 * @method     ChildCrowdfundingCampaignQuery groupByCreatedAt() Group by the campaign_created column
 * @method     ChildCrowdfundingCampaignQuery groupByUpdatedAt() Group by the campaign_updated column
 *
 * @method     ChildCrowdfundingCampaignQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCrowdfundingCampaignQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCrowdfundingCampaignQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCrowdfundingCampaignQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildCrowdfundingCampaignQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildCrowdfundingCampaignQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildCrowdfundingCampaign|null findOne(ConnectionInterface $con = null) Return the first ChildCrowdfundingCampaign matching the query
 * @method     ChildCrowdfundingCampaign findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCrowdfundingCampaign matching the query, or a new ChildCrowdfundingCampaign object populated from the query conditions when no match is found
 *
 * @method     ChildCrowdfundingCampaign|null findOneById(int $campaign_id) Return the first ChildCrowdfundingCampaign filtered by the campaign_id column
 * @method     ChildCrowdfundingCampaign|null findOneBySiteId(int $site_id) Return the first ChildCrowdfundingCampaign filtered by the site_id column
 * @method     ChildCrowdfundingCampaign|null findOneByTitle(string $campaign_title) Return the first ChildCrowdfundingCampaign filtered by the campaign_title column
 * @method     ChildCrowdfundingCampaign|null findOneByUrl(string $campaign_url) Return the first ChildCrowdfundingCampaign filtered by the campaign_url column
 * @method     ChildCrowdfundingCampaign|null findOneByDescription(string $campaign_description) Return the first ChildCrowdfundingCampaign filtered by the campaign_description column
 * @method     ChildCrowdfundingCampaign|null findOneByImage(string $campaign_image) Return the first ChildCrowdfundingCampaign filtered by the campaign_image column
 * @method     ChildCrowdfundingCampaign|null findOneByGoal(int $campaign_goal) Return the first ChildCrowdfundingCampaign filtered by the campaign_goal column
 * @method     ChildCrowdfundingCampaign|null findOneByPledged(int $campaign_pledged) Return the first ChildCrowdfundingCampaign filtered by the campaign_pledged column
 * @method     ChildCrowdfundingCampaign|null findOneByBackers(int $campaign_backers) Return the first ChildCrowdfundingCampaign filtered by the campaign_backers column
 * @method     ChildCrowdfundingCampaign|null findOneByStarts(string $campaign_starts) Return the first ChildCrowdfundingCampaign filtered by the campaign_starts column
 * @method     ChildCrowdfundingCampaign|null findOneByEnds(string $campaign_ends) Return the first ChildCrowdfundingCampaign filtered by the campaign_ends column
 * @method     ChildCrowdfundingCampaign|null findOneByCreatedAt(string $campaign_created) Return the first ChildCrowdfundingCampaign filtered by the campaign_created column
 * @method     ChildCrowdfundingCampaign|null findOneByUpdatedAt(string $campaign_updated) Return the first ChildCrowdfundingCampaign filtered by the campaign_updated column *

 * @method     ChildCrowdfundingCampaign requirePk($key, ConnectionInterface $con = null) Return the ChildCrowdfundingCampaign by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCrowdfundingCampaign requireOne(ConnectionInterface $con = null) Return the first ChildCrowdfundingCampaign matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCrowdfundingCampaign requireOneById(int $campaign_id) Return the first ChildCrowdfundingCampaign filtered by the campaign_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCrowdfundingCampaign requireOneBySiteId(int $site_id) Return the first ChildCrowdfundingCampaign filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCrowdfundingCampaign requireOneByTitle(string $campaign_title) Return the first ChildCrowdfundingCampaign filtered by the campaign_title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCrowdfundingCampaign requireOneByUrl(string $campaign_url) Return the first ChildCrowdfundingCampaign filtered by the campaign_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCrowdfundingCampaign requireOneByDescription(string $campaign_description) Return the first ChildCrowdfundingCampaign filtered by the campaign_description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCrowdfundingCampaign requireOneByImage(string $campaign_image) Return the first ChildCrowdfundingCampaign filtered by the campaign_image column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCrowdfundingCampaign requireOneByGoal(int $campaign_goal) Return the first ChildCrowdfundingCampaign filtered by the campaign_goal column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCrowdfundingCampaign requireOneByPledged(int $campaign_pledged) Return the first ChildCrowdfundingCampaign filtered by the campaign_pledged column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCrowdfundingCampaign requireOneByBackers(int $campaign_backers) Return the first ChildCrowdfundingCampaign filtered by the campaign_backers column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCrowdfundingCampaign requireOneByStarts(string $campaign_starts) Return the first ChildCrowdfundingCampaign filtered by the campaign_starts column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCrowdfundingCampaign requireOneByEnds(string $campaign_ends) Return the first ChildCrowdfundingCampaign filtered by the campaign_ends column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCrowdfundingCampaign requireOneByCreatedAt(string $campaign_created) Return the first ChildCrowdfundingCampaign filtered by the campaign_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCrowdfundingCampaign requireOneByUpdatedAt(string $campaign_updated) Return the first ChildCrowdfundingCampaign filtered by the campaign_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCrowdfundingCampaign[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildCrowdfundingCampaign objects based on current ModelCriteria
 * @psalm-method ObjectCollection&\Traversable<ChildCrowdfundingCampaign> find(ConnectionInterface $con = null) Return ChildCrowdfundingCampaign objects based on current ModelCriteria
 * @method     ChildCrowdfundingCampaign[]|ObjectCollection findById(int $campaign_id) Return ChildCrowdfundingCampaign objects filtered by the campaign_id column
 * @psalm-method ObjectCollection&\Traversable<ChildCrowdfundingCampaign> findById(int $campaign_id) Return ChildCrowdfundingCampaign objects filtered by the campaign_id column
 * @method     ChildCrowdfundingCampaign[]|ObjectCollection findBySiteId(int $site_id) Return ChildCrowdfundingCampaign objects filtered by the site_id column
 * @psalm-method ObjectCollection&\Traversable<ChildCrowdfundingCampaign> findBySiteId(int $site_id) Return ChildCrowdfundingCampaign objects filtered by the site_id column
 * @method     ChildCrowdfundingCampaign[]|ObjectCollection findByTitle(string $campaign_title) Return ChildCrowdfundingCampaign objects filtered by the campaign_title column
 * @psalm-method ObjectCollection&\Traversable<ChildCrowdfundingCampaign> findByTitle(string $campaign_title) Return ChildCrowdfundingCampaign objects filtered by the campaign_title column
 * @method     ChildCrowdfundingCampaign[]|ObjectCollection findByUrl(string $campaign_url) Return ChildCrowdfundingCampaign objects filtered by the campaign_url column
 * @psalm-method ObjectCollection&\Traversable<ChildCrowdfundingCampaign> findByUrl(string $campaign_url) Return ChildCrowdfundingCampaign objects filtered by the campaign_url column
 * @method     ChildCrowdfundingCampaign[]|ObjectCollection findByDescription(string $campaign_description) Return ChildCrowdfundingCampaign objects filtered by the campaign_description column
 * @psalm-method ObjectCollection&\Traversable<ChildCrowdfundingCampaign> findByDescription(string $campaign_description) Return ChildCrowdfundingCampaign objects filtered by the campaign_description column
 * @method     ChildCrowdfundingCampaign[]|ObjectCollection findByImage(string $campaign_image) Return ChildCrowdfundingCampaign objects filtered by the campaign_image column
 * @psalm-method ObjectCollection&\Traversable<ChildCrowdfundingCampaign> findByImage(string $campaign_image) Return ChildCrowdfundingCampaign objects filtered by the campaign_image column
 * @method     ChildCrowdfundingCampaign[]|ObjectCollection findByGoal(int $campaign_goal) Return ChildCrowdfundingCampaign objects filtered by the campaign_goal column
 * @psalm-method ObjectCollection&\Traversable<ChildCrowdfundingCampaign> findByGoal(int $campaign_goal) Return ChildCrowdfundingCampaign objects filtered by the campaign_goal column
 * @method     ChildCrowdfundingCampaign[]|ObjectCollection findByPledged(int $campaign_pledged) Return ChildCrowdfundingCampaign objects filtered by the campaign_pledged column
 * @psalm-method ObjectCollection&\Traversable<ChildCrowdfundingCampaign> findByPledged(int $campaign_pledged) Return ChildCrowdfundingCampaign objects filtered by the campaign_pledged column
 * @method     ChildCrowdfundingCampaign[]|ObjectCollection findByBackers(int $campaign_backers) Return ChildCrowdfundingCampaign objects filtered by the campaign_backers column
 * @psalm-method ObjectCollection&\Traversable<ChildCrowdfundingCampaign> findByBackers(int $campaign_backers) Return ChildCrowdfundingCampaign objects filtered by the campaign_backers column
 * @method     ChildCrowdfundingCampaign[]|ObjectCollection findByStarts(string $campaign_starts) Return ChildCrowdfundingCampaign objects filtered by the campaign_starts column
 * @psalm-method ObjectCollection&\Traversable<ChildCrowdfundingCampaign> findByStarts(string $campaign_starts) Return ChildCrowdfundingCampaign objects filtered by the campaign_starts column
 * @method     ChildCrowdfundingCampaign[]|ObjectCollection findByEnds(string $campaign_ends) Return ChildCrowdfundingCampaign objects filtered by the campaign_ends column
 * @psalm-method ObjectCollection&\Traversable<ChildCrowdfundingCampaign> findByEnds(string $campaign_ends) Return ChildCrowdfundingCampaign objects filtered by the campaign_ends column
 * @method     ChildCrowdfundingCampaign[]|ObjectCollection findByCreatedAt(string $campaign_created) Return ChildCrowdfundingCampaign objects filtered by the campaign_created column
 * @psalm-method ObjectCollection&\Traversable<ChildCrowdfundingCampaign> findByCreatedAt(string $campaign_created) Return ChildCrowdfundingCampaign objects filtered by the campaign_created column
 * @method     ChildCrowdfundingCampaign[]|ObjectCollection findByUpdatedAt(string $campaign_updated) Return ChildCrowdfundingCampaign objects filtered by the campaign_updated column
 * @psalm-method ObjectCollection&\Traversable<ChildCrowdfundingCampaign> findByUpdatedAt(string $campaign_updated) Return ChildCrowdfundingCampaign objects filtered by the campaign_updated column
 * @method     ChildCrowdfundingCampaign[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildCrowdfundingCampaign> paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class CrowdfundingCampaignQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\CrowdfundingCampaignQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\CrowdfundingCampaign', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCrowdfundingCampaignQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCrowdfundingCampaignQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildCrowdfundingCampaignQuery) {
            return $criteria;
        }
        $query = new ChildCrowdfundingCampaignQuery();
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
     * @return ChildCrowdfundingCampaign|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CrowdfundingCampaignTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = CrowdfundingCampaignTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildCrowdfundingCampaign A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT campaign_id, site_id, campaign_title, campaign_url, campaign_description, campaign_image, campaign_goal, campaign_pledged, campaign_backers, campaign_starts, campaign_ends, campaign_created, campaign_updated FROM cf_campaigns WHERE campaign_id = :p0';
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
            /** @var ChildCrowdfundingCampaign $obj */
            $obj = new ChildCrowdfundingCampaign();
            $obj->hydrate($row);
            CrowdfundingCampaignTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildCrowdfundingCampaign|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildCrowdfundingCampaignQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildCrowdfundingCampaignQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the campaign_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE campaign_id = 1234
     * $query->filterById(array(12, 34)); // WHERE campaign_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE campaign_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCrowdfundingCampaignQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_ID, $id, $comparison);
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
     * @return $this|ChildCrowdfundingCampaignQuery The current query, for fluid interface
     */
    public function filterBySiteId($siteId = null, $comparison = null)
    {
        if (is_array($siteId)) {
            $useMinMax = false;
            if (isset($siteId['min'])) {
                $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_SITE_ID, $siteId, $comparison);
    }

    /**
     * Filter the query on the campaign_title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE campaign_title = 'fooValue'
     * $query->filterByTitle('%fooValue%', Criteria::LIKE); // WHERE campaign_title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCrowdfundingCampaignQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the campaign_url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE campaign_url = 'fooValue'
     * $query->filterByUrl('%fooValue%', Criteria::LIKE); // WHERE campaign_url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $url The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCrowdfundingCampaignQuery The current query, for fluid interface
     */
    public function filterByUrl($url = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($url)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_URL, $url, $comparison);
    }

    /**
     * Filter the query on the campaign_description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE campaign_description = 'fooValue'
     * $query->filterByDescription('%fooValue%', Criteria::LIKE); // WHERE campaign_description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCrowdfundingCampaignQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the campaign_image column
     *
     * Example usage:
     * <code>
     * $query->filterByImage('fooValue');   // WHERE campaign_image = 'fooValue'
     * $query->filterByImage('%fooValue%', Criteria::LIKE); // WHERE campaign_image LIKE '%fooValue%'
     * </code>
     *
     * @param     string $image The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCrowdfundingCampaignQuery The current query, for fluid interface
     */
    public function filterByImage($image = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($image)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_IMAGE, $image, $comparison);
    }

    /**
     * Filter the query on the campaign_goal column
     *
     * Example usage:
     * <code>
     * $query->filterByGoal(1234); // WHERE campaign_goal = 1234
     * $query->filterByGoal(array(12, 34)); // WHERE campaign_goal IN (12, 34)
     * $query->filterByGoal(array('min' => 12)); // WHERE campaign_goal > 12
     * </code>
     *
     * @param     mixed $goal The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCrowdfundingCampaignQuery The current query, for fluid interface
     */
    public function filterByGoal($goal = null, $comparison = null)
    {
        if (is_array($goal)) {
            $useMinMax = false;
            if (isset($goal['min'])) {
                $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_GOAL, $goal['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($goal['max'])) {
                $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_GOAL, $goal['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_GOAL, $goal, $comparison);
    }

    /**
     * Filter the query on the campaign_pledged column
     *
     * Example usage:
     * <code>
     * $query->filterByPledged(1234); // WHERE campaign_pledged = 1234
     * $query->filterByPledged(array(12, 34)); // WHERE campaign_pledged IN (12, 34)
     * $query->filterByPledged(array('min' => 12)); // WHERE campaign_pledged > 12
     * </code>
     *
     * @param     mixed $pledged The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCrowdfundingCampaignQuery The current query, for fluid interface
     */
    public function filterByPledged($pledged = null, $comparison = null)
    {
        if (is_array($pledged)) {
            $useMinMax = false;
            if (isset($pledged['min'])) {
                $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_PLEDGED, $pledged['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pledged['max'])) {
                $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_PLEDGED, $pledged['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_PLEDGED, $pledged, $comparison);
    }

    /**
     * Filter the query on the campaign_backers column
     *
     * Example usage:
     * <code>
     * $query->filterByBackers(1234); // WHERE campaign_backers = 1234
     * $query->filterByBackers(array(12, 34)); // WHERE campaign_backers IN (12, 34)
     * $query->filterByBackers(array('min' => 12)); // WHERE campaign_backers > 12
     * </code>
     *
     * @param     mixed $backers The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCrowdfundingCampaignQuery The current query, for fluid interface
     */
    public function filterByBackers($backers = null, $comparison = null)
    {
        if (is_array($backers)) {
            $useMinMax = false;
            if (isset($backers['min'])) {
                $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_BACKERS, $backers['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($backers['max'])) {
                $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_BACKERS, $backers['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_BACKERS, $backers, $comparison);
    }

    /**
     * Filter the query on the campaign_starts column
     *
     * Example usage:
     * <code>
     * $query->filterByStarts('2011-03-14'); // WHERE campaign_starts = '2011-03-14'
     * $query->filterByStarts('now'); // WHERE campaign_starts = '2011-03-14'
     * $query->filterByStarts(array('max' => 'yesterday')); // WHERE campaign_starts > '2011-03-13'
     * </code>
     *
     * @param     mixed $starts The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCrowdfundingCampaignQuery The current query, for fluid interface
     */
    public function filterByStarts($starts = null, $comparison = null)
    {
        if (is_array($starts)) {
            $useMinMax = false;
            if (isset($starts['min'])) {
                $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_STARTS, $starts['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($starts['max'])) {
                $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_STARTS, $starts['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_STARTS, $starts, $comparison);
    }

    /**
     * Filter the query on the campaign_ends column
     *
     * Example usage:
     * <code>
     * $query->filterByEnds('2011-03-14'); // WHERE campaign_ends = '2011-03-14'
     * $query->filterByEnds('now'); // WHERE campaign_ends = '2011-03-14'
     * $query->filterByEnds(array('max' => 'yesterday')); // WHERE campaign_ends > '2011-03-13'
     * </code>
     *
     * @param     mixed $ends The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCrowdfundingCampaignQuery The current query, for fluid interface
     */
    public function filterByEnds($ends = null, $comparison = null)
    {
        if (is_array($ends)) {
            $useMinMax = false;
            if (isset($ends['min'])) {
                $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_ENDS, $ends['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ends['max'])) {
                $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_ENDS, $ends['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_ENDS, $ends, $comparison);
    }

    /**
     * Filter the query on the campaign_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE campaign_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE campaign_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE campaign_created > '2011-03-13'
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
     * @return $this|ChildCrowdfundingCampaignQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_CREATED, $createdAt, $comparison);
    }

    /**
     * Filter the query on the campaign_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE campaign_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE campaign_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE campaign_updated > '2011-03-13'
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
     * @return $this|ChildCrowdfundingCampaignQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildCrowdfundingCampaign $crowdfundingCampaign Object to remove from the list of results
     *
     * @return $this|ChildCrowdfundingCampaignQuery The current query, for fluid interface
     */
    public function prune($crowdfundingCampaign = null)
    {
        if ($crowdfundingCampaign) {
            $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_ID, $crowdfundingCampaign->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the cf_campaigns table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CrowdfundingCampaignTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CrowdfundingCampaignTableMap::clearInstancePool();
            CrowdfundingCampaignTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(CrowdfundingCampaignTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CrowdfundingCampaignTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            CrowdfundingCampaignTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CrowdfundingCampaignTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildCrowdfundingCampaignQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildCrowdfundingCampaignQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(CrowdfundingCampaignTableMap::COL_CAMPAIGN_UPDATED);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildCrowdfundingCampaignQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(CrowdfundingCampaignTableMap::COL_CAMPAIGN_UPDATED);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildCrowdfundingCampaignQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(CrowdfundingCampaignTableMap::COL_CAMPAIGN_CREATED);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildCrowdfundingCampaignQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(CrowdfundingCampaignTableMap::COL_CAMPAIGN_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildCrowdfundingCampaignQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(CrowdfundingCampaignTableMap::COL_CAMPAIGN_CREATED);
    }

} // CrowdfundingCampaignQuery
