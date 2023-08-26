<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Invitation as ChildInvitation;
use Model\InvitationQuery as ChildInvitationQuery;
use Model\Map\InvitationTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `invitations` table.
 *
 * @method     ChildInvitationQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildInvitationQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildInvitationQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method     ChildInvitationQuery orderByCode($order = Criteria::ASC) Order by the code column
 * @method     ChildInvitationQuery orderByAllowsPreDownload($order = Criteria::ASC) Order by the allows_pre_download column
 * @method     ChildInvitationQuery orderByConsumedAt($order = Criteria::ASC) Order by the consumed_at column
 * @method     ChildInvitationQuery orderByExpiresAt($order = Criteria::ASC) Order by the expires_at column
 * @method     ChildInvitationQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildInvitationQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildInvitationQuery groupById() Group by the id column
 * @method     ChildInvitationQuery groupBySiteId() Group by the site_id column
 * @method     ChildInvitationQuery groupByEmail() Group by the email column
 * @method     ChildInvitationQuery groupByCode() Group by the code column
 * @method     ChildInvitationQuery groupByAllowsPreDownload() Group by the allows_pre_download column
 * @method     ChildInvitationQuery groupByConsumedAt() Group by the consumed_at column
 * @method     ChildInvitationQuery groupByExpiresAt() Group by the expires_at column
 * @method     ChildInvitationQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildInvitationQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildInvitationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildInvitationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildInvitationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildInvitationQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildInvitationQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildInvitationQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildInvitationQuery leftJoinSite($relationAlias = null) Adds a LEFT JOIN clause to the query using the Site relation
 * @method     ChildInvitationQuery rightJoinSite($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Site relation
 * @method     ChildInvitationQuery innerJoinSite($relationAlias = null) Adds a INNER JOIN clause to the query using the Site relation
 *
 * @method     ChildInvitationQuery joinWithSite($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Site relation
 *
 * @method     ChildInvitationQuery leftJoinWithSite() Adds a LEFT JOIN clause and with to the query using the Site relation
 * @method     ChildInvitationQuery rightJoinWithSite() Adds a RIGHT JOIN clause and with to the query using the Site relation
 * @method     ChildInvitationQuery innerJoinWithSite() Adds a INNER JOIN clause and with to the query using the Site relation
 *
 * @method     ChildInvitationQuery leftJoinInvitationsArticles($relationAlias = null) Adds a LEFT JOIN clause to the query using the InvitationsArticles relation
 * @method     ChildInvitationQuery rightJoinInvitationsArticles($relationAlias = null) Adds a RIGHT JOIN clause to the query using the InvitationsArticles relation
 * @method     ChildInvitationQuery innerJoinInvitationsArticles($relationAlias = null) Adds a INNER JOIN clause to the query using the InvitationsArticles relation
 *
 * @method     ChildInvitationQuery joinWithInvitationsArticles($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the InvitationsArticles relation
 *
 * @method     ChildInvitationQuery leftJoinWithInvitationsArticles() Adds a LEFT JOIN clause and with to the query using the InvitationsArticles relation
 * @method     ChildInvitationQuery rightJoinWithInvitationsArticles() Adds a RIGHT JOIN clause and with to the query using the InvitationsArticles relation
 * @method     ChildInvitationQuery innerJoinWithInvitationsArticles() Adds a INNER JOIN clause and with to the query using the InvitationsArticles relation
 *
 * @method     \Model\SiteQuery|\Model\InvitationsArticlesQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildInvitation|null findOne(?ConnectionInterface $con = null) Return the first ChildInvitation matching the query
 * @method     ChildInvitation findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildInvitation matching the query, or a new ChildInvitation object populated from the query conditions when no match is found
 *
 * @method     ChildInvitation|null findOneById(int $id) Return the first ChildInvitation filtered by the id column
 * @method     ChildInvitation|null findOneBySiteId(int $site_id) Return the first ChildInvitation filtered by the site_id column
 * @method     ChildInvitation|null findOneByEmail(string $email) Return the first ChildInvitation filtered by the email column
 * @method     ChildInvitation|null findOneByCode(string $code) Return the first ChildInvitation filtered by the code column
 * @method     ChildInvitation|null findOneByAllowsPreDownload(boolean $allows_pre_download) Return the first ChildInvitation filtered by the allows_pre_download column
 * @method     ChildInvitation|null findOneByConsumedAt(string $consumed_at) Return the first ChildInvitation filtered by the consumed_at column
 * @method     ChildInvitation|null findOneByExpiresAt(string $expires_at) Return the first ChildInvitation filtered by the expires_at column
 * @method     ChildInvitation|null findOneByCreatedAt(string $created_at) Return the first ChildInvitation filtered by the created_at column
 * @method     ChildInvitation|null findOneByUpdatedAt(string $updated_at) Return the first ChildInvitation filtered by the updated_at column
 *
 * @method     ChildInvitation requirePk($key, ?ConnectionInterface $con = null) Return the ChildInvitation by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvitation requireOne(?ConnectionInterface $con = null) Return the first ChildInvitation matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildInvitation requireOneById(int $id) Return the first ChildInvitation filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvitation requireOneBySiteId(int $site_id) Return the first ChildInvitation filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvitation requireOneByEmail(string $email) Return the first ChildInvitation filtered by the email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvitation requireOneByCode(string $code) Return the first ChildInvitation filtered by the code column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvitation requireOneByAllowsPreDownload(boolean $allows_pre_download) Return the first ChildInvitation filtered by the allows_pre_download column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvitation requireOneByConsumedAt(string $consumed_at) Return the first ChildInvitation filtered by the consumed_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvitation requireOneByExpiresAt(string $expires_at) Return the first ChildInvitation filtered by the expires_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvitation requireOneByCreatedAt(string $created_at) Return the first ChildInvitation filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvitation requireOneByUpdatedAt(string $updated_at) Return the first ChildInvitation filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildInvitation[]|Collection find(?ConnectionInterface $con = null) Return ChildInvitation objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildInvitation> find(?ConnectionInterface $con = null) Return ChildInvitation objects based on current ModelCriteria
 *
 * @method     ChildInvitation[]|Collection findById(int|array<int> $id) Return ChildInvitation objects filtered by the id column
 * @psalm-method Collection&\Traversable<ChildInvitation> findById(int|array<int> $id) Return ChildInvitation objects filtered by the id column
 * @method     ChildInvitation[]|Collection findBySiteId(int|array<int> $site_id) Return ChildInvitation objects filtered by the site_id column
 * @psalm-method Collection&\Traversable<ChildInvitation> findBySiteId(int|array<int> $site_id) Return ChildInvitation objects filtered by the site_id column
 * @method     ChildInvitation[]|Collection findByEmail(string|array<string> $email) Return ChildInvitation objects filtered by the email column
 * @psalm-method Collection&\Traversable<ChildInvitation> findByEmail(string|array<string> $email) Return ChildInvitation objects filtered by the email column
 * @method     ChildInvitation[]|Collection findByCode(string|array<string> $code) Return ChildInvitation objects filtered by the code column
 * @psalm-method Collection&\Traversable<ChildInvitation> findByCode(string|array<string> $code) Return ChildInvitation objects filtered by the code column
 * @method     ChildInvitation[]|Collection findByAllowsPreDownload(boolean|array<boolean> $allows_pre_download) Return ChildInvitation objects filtered by the allows_pre_download column
 * @psalm-method Collection&\Traversable<ChildInvitation> findByAllowsPreDownload(boolean|array<boolean> $allows_pre_download) Return ChildInvitation objects filtered by the allows_pre_download column
 * @method     ChildInvitation[]|Collection findByConsumedAt(string|array<string> $consumed_at) Return ChildInvitation objects filtered by the consumed_at column
 * @psalm-method Collection&\Traversable<ChildInvitation> findByConsumedAt(string|array<string> $consumed_at) Return ChildInvitation objects filtered by the consumed_at column
 * @method     ChildInvitation[]|Collection findByExpiresAt(string|array<string> $expires_at) Return ChildInvitation objects filtered by the expires_at column
 * @psalm-method Collection&\Traversable<ChildInvitation> findByExpiresAt(string|array<string> $expires_at) Return ChildInvitation objects filtered by the expires_at column
 * @method     ChildInvitation[]|Collection findByCreatedAt(string|array<string> $created_at) Return ChildInvitation objects filtered by the created_at column
 * @psalm-method Collection&\Traversable<ChildInvitation> findByCreatedAt(string|array<string> $created_at) Return ChildInvitation objects filtered by the created_at column
 * @method     ChildInvitation[]|Collection findByUpdatedAt(string|array<string> $updated_at) Return ChildInvitation objects filtered by the updated_at column
 * @psalm-method Collection&\Traversable<ChildInvitation> findByUpdatedAt(string|array<string> $updated_at) Return ChildInvitation objects filtered by the updated_at column
 *
 * @method     ChildInvitation[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildInvitation> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class InvitationQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\InvitationQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Invitation', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildInvitationQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildInvitationQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildInvitationQuery) {
            return $criteria;
        }
        $query = new ChildInvitationQuery();
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
     * @return ChildInvitation|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(InvitationTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = InvitationTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildInvitation A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, site_id, email, code, allows_pre_download, consumed_at, expires_at, created_at, updated_at FROM invitations WHERE id = :p0';
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
            /** @var ChildInvitation $obj */
            $obj = new ChildInvitation();
            $obj->hydrate($row);
            InvitationTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildInvitation|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(InvitationTableMap::COL_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(InvitationTableMap::COL_ID, $keys, Criteria::IN);

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
                $this->addUsingAlias(InvitationTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(InvitationTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(InvitationTableMap::COL_ID, $id, $comparison);

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
                $this->addUsingAlias(InvitationTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(InvitationTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(InvitationTableMap::COL_SITE_ID, $siteId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%', Criteria::LIKE); // WHERE email LIKE '%fooValue%'
     * $query->filterByEmail(['foo', 'bar']); // WHERE email IN ('foo', 'bar')
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

        $this->addUsingAlias(InvitationTableMap::COL_EMAIL, $email, $comparison);

        return $this;
    }

    /**
     * Filter the query on the code column
     *
     * Example usage:
     * <code>
     * $query->filterByCode('fooValue');   // WHERE code = 'fooValue'
     * $query->filterByCode('%fooValue%', Criteria::LIKE); // WHERE code LIKE '%fooValue%'
     * $query->filterByCode(['foo', 'bar']); // WHERE code IN ('foo', 'bar')
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

        $this->addUsingAlias(InvitationTableMap::COL_CODE, $code, $comparison);

        return $this;
    }

    /**
     * Filter the query on the allows_pre_download column
     *
     * Example usage:
     * <code>
     * $query->filterByAllowsPreDownload(true); // WHERE allows_pre_download = true
     * $query->filterByAllowsPreDownload('yes'); // WHERE allows_pre_download = true
     * </code>
     *
     * @param bool|string $allowsPreDownload The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAllowsPreDownload($allowsPreDownload = null, ?string $comparison = null)
    {
        if (is_string($allowsPreDownload)) {
            $allowsPreDownload = in_array(strtolower($allowsPreDownload), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(InvitationTableMap::COL_ALLOWS_PRE_DOWNLOAD, $allowsPreDownload, $comparison);

        return $this;
    }

    /**
     * Filter the query on the consumed_at column
     *
     * Example usage:
     * <code>
     * $query->filterByConsumedAt('2011-03-14'); // WHERE consumed_at = '2011-03-14'
     * $query->filterByConsumedAt('now'); // WHERE consumed_at = '2011-03-14'
     * $query->filterByConsumedAt(array('max' => 'yesterday')); // WHERE consumed_at > '2011-03-13'
     * </code>
     *
     * @param mixed $consumedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByConsumedAt($consumedAt = null, ?string $comparison = null)
    {
        if (is_array($consumedAt)) {
            $useMinMax = false;
            if (isset($consumedAt['min'])) {
                $this->addUsingAlias(InvitationTableMap::COL_CONSUMED_AT, $consumedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($consumedAt['max'])) {
                $this->addUsingAlias(InvitationTableMap::COL_CONSUMED_AT, $consumedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(InvitationTableMap::COL_CONSUMED_AT, $consumedAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the expires_at column
     *
     * Example usage:
     * <code>
     * $query->filterByExpiresAt('2011-03-14'); // WHERE expires_at = '2011-03-14'
     * $query->filterByExpiresAt('now'); // WHERE expires_at = '2011-03-14'
     * $query->filterByExpiresAt(array('max' => 'yesterday')); // WHERE expires_at > '2011-03-13'
     * </code>
     *
     * @param mixed $expiresAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByExpiresAt($expiresAt = null, ?string $comparison = null)
    {
        if (is_array($expiresAt)) {
            $useMinMax = false;
            if (isset($expiresAt['min'])) {
                $this->addUsingAlias(InvitationTableMap::COL_EXPIRES_AT, $expiresAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($expiresAt['max'])) {
                $this->addUsingAlias(InvitationTableMap::COL_EXPIRES_AT, $expiresAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(InvitationTableMap::COL_EXPIRES_AT, $expiresAt, $comparison);

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
                $this->addUsingAlias(InvitationTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(InvitationTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(InvitationTableMap::COL_CREATED_AT, $createdAt, $comparison);

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
                $this->addUsingAlias(InvitationTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(InvitationTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(InvitationTableMap::COL_UPDATED_AT, $updatedAt, $comparison);

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
                ->addUsingAlias(InvitationTableMap::COL_SITE_ID, $site->getId(), $comparison);
        } elseif ($site instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(InvitationTableMap::COL_SITE_ID, $site->toKeyValue('PrimaryKey', 'Id'), $comparison);

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
     * Filter the query by a related \Model\InvitationsArticles object
     *
     * @param \Model\InvitationsArticles|ObjectCollection $invitationsArticles the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByInvitationsArticles($invitationsArticles, ?string $comparison = null)
    {
        if ($invitationsArticles instanceof \Model\InvitationsArticles) {
            $this
                ->addUsingAlias(InvitationTableMap::COL_ID, $invitationsArticles->getInvitationId(), $comparison);

            return $this;
        } elseif ($invitationsArticles instanceof ObjectCollection) {
            $this
                ->useInvitationsArticlesQuery()
                ->filterByPrimaryKeys($invitationsArticles->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByInvitationsArticles() only accepts arguments of type \Model\InvitationsArticles or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the InvitationsArticles relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinInvitationsArticles(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('InvitationsArticles');

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
            $this->addJoinObject($join, 'InvitationsArticles');
        }

        return $this;
    }

    /**
     * Use the InvitationsArticles relation InvitationsArticles object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\InvitationsArticlesQuery A secondary query class using the current class as primary query
     */
    public function useInvitationsArticlesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInvitationsArticles($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'InvitationsArticles', '\Model\InvitationsArticlesQuery');
    }

    /**
     * Use the InvitationsArticles relation InvitationsArticles object
     *
     * @param callable(\Model\InvitationsArticlesQuery):\Model\InvitationsArticlesQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withInvitationsArticlesQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useInvitationsArticlesQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to InvitationsArticles table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\InvitationsArticlesQuery The inner query object of the EXISTS statement
     */
    public function useInvitationsArticlesExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\InvitationsArticlesQuery */
        $q = $this->useExistsQuery('InvitationsArticles', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to InvitationsArticles table for a NOT EXISTS query.
     *
     * @see useInvitationsArticlesExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\InvitationsArticlesQuery The inner query object of the NOT EXISTS statement
     */
    public function useInvitationsArticlesNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\InvitationsArticlesQuery */
        $q = $this->useExistsQuery('InvitationsArticles', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to InvitationsArticles table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\InvitationsArticlesQuery The inner query object of the IN statement
     */
    public function useInInvitationsArticlesQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\InvitationsArticlesQuery */
        $q = $this->useInQuery('InvitationsArticles', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to InvitationsArticles table for a NOT IN query.
     *
     * @see useInvitationsArticlesInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\InvitationsArticlesQuery The inner query object of the NOT IN statement
     */
    public function useNotInInvitationsArticlesQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\InvitationsArticlesQuery */
        $q = $this->useInQuery('InvitationsArticles', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related Article object
     * using the invitations_articles table as cross reference
     *
     * @param Article $article the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL and Criteria::IN for queries
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByArticle($article, string $comparison = null)
    {
        $this
            ->useInvitationsArticlesQuery()
            ->filterByArticle($article, $comparison)
            ->endUse();

        return $this;
    }

    /**
     * Exclude object from result
     *
     * @param ChildInvitation $invitation Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($invitation = null)
    {
        if ($invitation) {
            $this->addUsingAlias(InvitationTableMap::COL_ID, $invitation->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the invitations table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(InvitationTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            InvitationTableMap::clearInstancePool();
            InvitationTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(InvitationTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(InvitationTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            InvitationTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            InvitationTableMap::clearRelatedInstancePool();

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
        $this->addUsingAlias(InvitationTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(InvitationTableMap::COL_UPDATED_AT);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(InvitationTableMap::COL_UPDATED_AT);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(InvitationTableMap::COL_CREATED_AT);

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
        $this->addUsingAlias(InvitationTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(InvitationTableMap::COL_CREATED_AT);

        return $this;
    }

}
