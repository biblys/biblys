<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\AxysAccount as ChildAxysAccount;
use Model\AxysAccountQuery as ChildAxysAccountQuery;
use Model\Map\AxysAccountTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `axys_accounts` table.
 *
 * @method     ChildAxysAccountQuery orderById($order = Criteria::ASC) Order by the axys_account_id column
 * @method     ChildAxysAccountQuery orderByEmail($order = Criteria::ASC) Order by the axys_account_email column
 * @method     ChildAxysAccountQuery orderByPassword($order = Criteria::ASC) Order by the axys_account_password column
 * @method     ChildAxysAccountQuery orderByKey($order = Criteria::ASC) Order by the axys_account_key column
 * @method     ChildAxysAccountQuery orderByEmailKey($order = Criteria::ASC) Order by the axys_account_email_key column
 * @method     ChildAxysAccountQuery orderByUsername($order = Criteria::ASC) Order by the axys_account_screen_name column
 * @method     ChildAxysAccountQuery orderBySlug($order = Criteria::ASC) Order by the axys_account_slug column
 * @method     ChildAxysAccountQuery orderBySignupDate($order = Criteria::ASC) Order by the axys_account_signup_date column
 * @method     ChildAxysAccountQuery orderByLoginDate($order = Criteria::ASC) Order by the axys_account_login_date column
 * @method     ChildAxysAccountQuery orderByFirstName($order = Criteria::ASC) Order by the axys_account_first_name column
 * @method     ChildAxysAccountQuery orderByLastName($order = Criteria::ASC) Order by the axys_account_last_name column
 * @method     ChildAxysAccountQuery orderByUpdate($order = Criteria::ASC) Order by the axys_account_update column
 * @method     ChildAxysAccountQuery orderByEmailVerifiedAt($order = Criteria::ASC) Order by the email_verified_at column
 * @method     ChildAxysAccountQuery orderByMarkedForEmailVerificationAt($order = Criteria::ASC) Order by the marked_for_email_verification_at column
 * @method     ChildAxysAccountQuery orderByWarnedBeforeDeletionAt($order = Criteria::ASC) Order by the warned_before_deletion_at column
 * @method     ChildAxysAccountQuery orderByCreatedAt($order = Criteria::ASC) Order by the axys_account_created column
 * @method     ChildAxysAccountQuery orderByUpdatedAt($order = Criteria::ASC) Order by the axys_account_updated column
 *
 * @method     ChildAxysAccountQuery groupById() Group by the axys_account_id column
 * @method     ChildAxysAccountQuery groupByEmail() Group by the axys_account_email column
 * @method     ChildAxysAccountQuery groupByPassword() Group by the axys_account_password column
 * @method     ChildAxysAccountQuery groupByKey() Group by the axys_account_key column
 * @method     ChildAxysAccountQuery groupByEmailKey() Group by the axys_account_email_key column
 * @method     ChildAxysAccountQuery groupByUsername() Group by the axys_account_screen_name column
 * @method     ChildAxysAccountQuery groupBySlug() Group by the axys_account_slug column
 * @method     ChildAxysAccountQuery groupBySignupDate() Group by the axys_account_signup_date column
 * @method     ChildAxysAccountQuery groupByLoginDate() Group by the axys_account_login_date column
 * @method     ChildAxysAccountQuery groupByFirstName() Group by the axys_account_first_name column
 * @method     ChildAxysAccountQuery groupByLastName() Group by the axys_account_last_name column
 * @method     ChildAxysAccountQuery groupByUpdate() Group by the axys_account_update column
 * @method     ChildAxysAccountQuery groupByEmailVerifiedAt() Group by the email_verified_at column
 * @method     ChildAxysAccountQuery groupByMarkedForEmailVerificationAt() Group by the marked_for_email_verification_at column
 * @method     ChildAxysAccountQuery groupByWarnedBeforeDeletionAt() Group by the warned_before_deletion_at column
 * @method     ChildAxysAccountQuery groupByCreatedAt() Group by the axys_account_created column
 * @method     ChildAxysAccountQuery groupByUpdatedAt() Group by the axys_account_updated column
 *
 * @method     ChildAxysAccountQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildAxysAccountQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildAxysAccountQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildAxysAccountQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildAxysAccountQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildAxysAccountQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildAxysAccount|null findOne(?ConnectionInterface $con = null) Return the first ChildAxysAccount matching the query
 * @method     ChildAxysAccount findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildAxysAccount matching the query, or a new ChildAxysAccount object populated from the query conditions when no match is found
 *
 * @method     ChildAxysAccount|null findOneById(int $axys_account_id) Return the first ChildAxysAccount filtered by the axys_account_id column
 * @method     ChildAxysAccount|null findOneByEmail(string $axys_account_email) Return the first ChildAxysAccount filtered by the axys_account_email column
 * @method     ChildAxysAccount|null findOneByPassword(string $axys_account_password) Return the first ChildAxysAccount filtered by the axys_account_password column
 * @method     ChildAxysAccount|null findOneByKey(string $axys_account_key) Return the first ChildAxysAccount filtered by the axys_account_key column
 * @method     ChildAxysAccount|null findOneByEmailKey(string $axys_account_email_key) Return the first ChildAxysAccount filtered by the axys_account_email_key column
 * @method     ChildAxysAccount|null findOneByUsername(string $axys_account_screen_name) Return the first ChildAxysAccount filtered by the axys_account_screen_name column
 * @method     ChildAxysAccount|null findOneBySlug(string $axys_account_slug) Return the first ChildAxysAccount filtered by the axys_account_slug column
 * @method     ChildAxysAccount|null findOneBySignupDate(string $axys_account_signup_date) Return the first ChildAxysAccount filtered by the axys_account_signup_date column
 * @method     ChildAxysAccount|null findOneByLoginDate(string $axys_account_login_date) Return the first ChildAxysAccount filtered by the axys_account_login_date column
 * @method     ChildAxysAccount|null findOneByFirstName(string $axys_account_first_name) Return the first ChildAxysAccount filtered by the axys_account_first_name column
 * @method     ChildAxysAccount|null findOneByLastName(string $axys_account_last_name) Return the first ChildAxysAccount filtered by the axys_account_last_name column
 * @method     ChildAxysAccount|null findOneByUpdate(string $axys_account_update) Return the first ChildAxysAccount filtered by the axys_account_update column
 * @method     ChildAxysAccount|null findOneByEmailVerifiedAt(string $email_verified_at) Return the first ChildAxysAccount filtered by the email_verified_at column
 * @method     ChildAxysAccount|null findOneByMarkedForEmailVerificationAt(string $marked_for_email_verification_at) Return the first ChildAxysAccount filtered by the marked_for_email_verification_at column
 * @method     ChildAxysAccount|null findOneByWarnedBeforeDeletionAt(string $warned_before_deletion_at) Return the first ChildAxysAccount filtered by the warned_before_deletion_at column
 * @method     ChildAxysAccount|null findOneByCreatedAt(string $axys_account_created) Return the first ChildAxysAccount filtered by the axys_account_created column
 * @method     ChildAxysAccount|null findOneByUpdatedAt(string $axys_account_updated) Return the first ChildAxysAccount filtered by the axys_account_updated column
 *
 * @method     ChildAxysAccount requirePk($key, ?ConnectionInterface $con = null) Return the ChildAxysAccount by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAxysAccount requireOne(?ConnectionInterface $con = null) Return the first ChildAxysAccount matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAxysAccount requireOneById(int $axys_account_id) Return the first ChildAxysAccount filtered by the axys_account_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAxysAccount requireOneByEmail(string $axys_account_email) Return the first ChildAxysAccount filtered by the axys_account_email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAxysAccount requireOneByPassword(string $axys_account_password) Return the first ChildAxysAccount filtered by the axys_account_password column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAxysAccount requireOneByKey(string $axys_account_key) Return the first ChildAxysAccount filtered by the axys_account_key column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAxysAccount requireOneByEmailKey(string $axys_account_email_key) Return the first ChildAxysAccount filtered by the axys_account_email_key column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAxysAccount requireOneByUsername(string $axys_account_screen_name) Return the first ChildAxysAccount filtered by the axys_account_screen_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAxysAccount requireOneBySlug(string $axys_account_slug) Return the first ChildAxysAccount filtered by the axys_account_slug column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAxysAccount requireOneBySignupDate(string $axys_account_signup_date) Return the first ChildAxysAccount filtered by the axys_account_signup_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAxysAccount requireOneByLoginDate(string $axys_account_login_date) Return the first ChildAxysAccount filtered by the axys_account_login_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAxysAccount requireOneByFirstName(string $axys_account_first_name) Return the first ChildAxysAccount filtered by the axys_account_first_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAxysAccount requireOneByLastName(string $axys_account_last_name) Return the first ChildAxysAccount filtered by the axys_account_last_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAxysAccount requireOneByUpdate(string $axys_account_update) Return the first ChildAxysAccount filtered by the axys_account_update column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAxysAccount requireOneByEmailVerifiedAt(string $email_verified_at) Return the first ChildAxysAccount filtered by the email_verified_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAxysAccount requireOneByMarkedForEmailVerificationAt(string $marked_for_email_verification_at) Return the first ChildAxysAccount filtered by the marked_for_email_verification_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAxysAccount requireOneByWarnedBeforeDeletionAt(string $warned_before_deletion_at) Return the first ChildAxysAccount filtered by the warned_before_deletion_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAxysAccount requireOneByCreatedAt(string $axys_account_created) Return the first ChildAxysAccount filtered by the axys_account_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAxysAccount requireOneByUpdatedAt(string $axys_account_updated) Return the first ChildAxysAccount filtered by the axys_account_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAxysAccount[]|Collection find(?ConnectionInterface $con = null) Return ChildAxysAccount objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildAxysAccount> find(?ConnectionInterface $con = null) Return ChildAxysAccount objects based on current ModelCriteria
 *
 * @method     ChildAxysAccount[]|Collection findById(int|array<int> $axys_account_id) Return ChildAxysAccount objects filtered by the axys_account_id column
 * @psalm-method Collection&\Traversable<ChildAxysAccount> findById(int|array<int> $axys_account_id) Return ChildAxysAccount objects filtered by the axys_account_id column
 * @method     ChildAxysAccount[]|Collection findByEmail(string|array<string> $axys_account_email) Return ChildAxysAccount objects filtered by the axys_account_email column
 * @psalm-method Collection&\Traversable<ChildAxysAccount> findByEmail(string|array<string> $axys_account_email) Return ChildAxysAccount objects filtered by the axys_account_email column
 * @method     ChildAxysAccount[]|Collection findByPassword(string|array<string> $axys_account_password) Return ChildAxysAccount objects filtered by the axys_account_password column
 * @psalm-method Collection&\Traversable<ChildAxysAccount> findByPassword(string|array<string> $axys_account_password) Return ChildAxysAccount objects filtered by the axys_account_password column
 * @method     ChildAxysAccount[]|Collection findByKey(string|array<string> $axys_account_key) Return ChildAxysAccount objects filtered by the axys_account_key column
 * @psalm-method Collection&\Traversable<ChildAxysAccount> findByKey(string|array<string> $axys_account_key) Return ChildAxysAccount objects filtered by the axys_account_key column
 * @method     ChildAxysAccount[]|Collection findByEmailKey(string|array<string> $axys_account_email_key) Return ChildAxysAccount objects filtered by the axys_account_email_key column
 * @psalm-method Collection&\Traversable<ChildAxysAccount> findByEmailKey(string|array<string> $axys_account_email_key) Return ChildAxysAccount objects filtered by the axys_account_email_key column
 * @method     ChildAxysAccount[]|Collection findByUsername(string|array<string> $axys_account_screen_name) Return ChildAxysAccount objects filtered by the axys_account_screen_name column
 * @psalm-method Collection&\Traversable<ChildAxysAccount> findByUsername(string|array<string> $axys_account_screen_name) Return ChildAxysAccount objects filtered by the axys_account_screen_name column
 * @method     ChildAxysAccount[]|Collection findBySlug(string|array<string> $axys_account_slug) Return ChildAxysAccount objects filtered by the axys_account_slug column
 * @psalm-method Collection&\Traversable<ChildAxysAccount> findBySlug(string|array<string> $axys_account_slug) Return ChildAxysAccount objects filtered by the axys_account_slug column
 * @method     ChildAxysAccount[]|Collection findBySignupDate(string|array<string> $axys_account_signup_date) Return ChildAxysAccount objects filtered by the axys_account_signup_date column
 * @psalm-method Collection&\Traversable<ChildAxysAccount> findBySignupDate(string|array<string> $axys_account_signup_date) Return ChildAxysAccount objects filtered by the axys_account_signup_date column
 * @method     ChildAxysAccount[]|Collection findByLoginDate(string|array<string> $axys_account_login_date) Return ChildAxysAccount objects filtered by the axys_account_login_date column
 * @psalm-method Collection&\Traversable<ChildAxysAccount> findByLoginDate(string|array<string> $axys_account_login_date) Return ChildAxysAccount objects filtered by the axys_account_login_date column
 * @method     ChildAxysAccount[]|Collection findByFirstName(string|array<string> $axys_account_first_name) Return ChildAxysAccount objects filtered by the axys_account_first_name column
 * @psalm-method Collection&\Traversable<ChildAxysAccount> findByFirstName(string|array<string> $axys_account_first_name) Return ChildAxysAccount objects filtered by the axys_account_first_name column
 * @method     ChildAxysAccount[]|Collection findByLastName(string|array<string> $axys_account_last_name) Return ChildAxysAccount objects filtered by the axys_account_last_name column
 * @psalm-method Collection&\Traversable<ChildAxysAccount> findByLastName(string|array<string> $axys_account_last_name) Return ChildAxysAccount objects filtered by the axys_account_last_name column
 * @method     ChildAxysAccount[]|Collection findByUpdate(string|array<string> $axys_account_update) Return ChildAxysAccount objects filtered by the axys_account_update column
 * @psalm-method Collection&\Traversable<ChildAxysAccount> findByUpdate(string|array<string> $axys_account_update) Return ChildAxysAccount objects filtered by the axys_account_update column
 * @method     ChildAxysAccount[]|Collection findByEmailVerifiedAt(string|array<string> $email_verified_at) Return ChildAxysAccount objects filtered by the email_verified_at column
 * @psalm-method Collection&\Traversable<ChildAxysAccount> findByEmailVerifiedAt(string|array<string> $email_verified_at) Return ChildAxysAccount objects filtered by the email_verified_at column
 * @method     ChildAxysAccount[]|Collection findByMarkedForEmailVerificationAt(string|array<string> $marked_for_email_verification_at) Return ChildAxysAccount objects filtered by the marked_for_email_verification_at column
 * @psalm-method Collection&\Traversable<ChildAxysAccount> findByMarkedForEmailVerificationAt(string|array<string> $marked_for_email_verification_at) Return ChildAxysAccount objects filtered by the marked_for_email_verification_at column
 * @method     ChildAxysAccount[]|Collection findByWarnedBeforeDeletionAt(string|array<string> $warned_before_deletion_at) Return ChildAxysAccount objects filtered by the warned_before_deletion_at column
 * @psalm-method Collection&\Traversable<ChildAxysAccount> findByWarnedBeforeDeletionAt(string|array<string> $warned_before_deletion_at) Return ChildAxysAccount objects filtered by the warned_before_deletion_at column
 * @method     ChildAxysAccount[]|Collection findByCreatedAt(string|array<string> $axys_account_created) Return ChildAxysAccount objects filtered by the axys_account_created column
 * @psalm-method Collection&\Traversable<ChildAxysAccount> findByCreatedAt(string|array<string> $axys_account_created) Return ChildAxysAccount objects filtered by the axys_account_created column
 * @method     ChildAxysAccount[]|Collection findByUpdatedAt(string|array<string> $axys_account_updated) Return ChildAxysAccount objects filtered by the axys_account_updated column
 * @psalm-method Collection&\Traversable<ChildAxysAccount> findByUpdatedAt(string|array<string> $axys_account_updated) Return ChildAxysAccount objects filtered by the axys_account_updated column
 *
 * @method     ChildAxysAccount[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildAxysAccount> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class AxysAccountQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\AxysAccountQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\AxysAccount', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildAxysAccountQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildAxysAccountQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildAxysAccountQuery) {
            return $criteria;
        }
        $query = new ChildAxysAccountQuery();
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
     * @return ChildAxysAccount|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(AxysAccountTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = AxysAccountTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildAxysAccount A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT axys_account_id, axys_account_email, axys_account_password, axys_account_key, axys_account_email_key, axys_account_screen_name, axys_account_slug, axys_account_signup_date, axys_account_login_date, axys_account_first_name, axys_account_last_name, axys_account_update, email_verified_at, marked_for_email_verification_at, warned_before_deletion_at, axys_account_created, axys_account_updated FROM axys_accounts WHERE axys_account_id = :p0';
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
            /** @var ChildAxysAccount $obj */
            $obj = new ChildAxysAccount();
            $obj->hydrate($row);
            AxysAccountTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildAxysAccount|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the axys_account_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE axys_account_id = 1234
     * $query->filterById(array(12, 34)); // WHERE axys_account_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE axys_account_id > 12
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
                $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the axys_account_email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE axys_account_email = 'fooValue'
     * $query->filterByEmail('%fooValue%', Criteria::LIKE); // WHERE axys_account_email LIKE '%fooValue%'
     * $query->filterByEmail(['foo', 'bar']); // WHERE axys_account_email IN ('foo', 'bar')
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

        $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_EMAIL, $email, $comparison);

        return $this;
    }

    /**
     * Filter the query on the axys_account_password column
     *
     * Example usage:
     * <code>
     * $query->filterByPassword('fooValue');   // WHERE axys_account_password = 'fooValue'
     * $query->filterByPassword('%fooValue%', Criteria::LIKE); // WHERE axys_account_password LIKE '%fooValue%'
     * $query->filterByPassword(['foo', 'bar']); // WHERE axys_account_password IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $password The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPassword($password = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($password)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_PASSWORD, $password, $comparison);

        return $this;
    }

    /**
     * Filter the query on the axys_account_key column
     *
     * Example usage:
     * <code>
     * $query->filterByKey('fooValue');   // WHERE axys_account_key = 'fooValue'
     * $query->filterByKey('%fooValue%', Criteria::LIKE); // WHERE axys_account_key LIKE '%fooValue%'
     * $query->filterByKey(['foo', 'bar']); // WHERE axys_account_key IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $key The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByKey($key = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($key)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_KEY, $key, $comparison);

        return $this;
    }

    /**
     * Filter the query on the axys_account_email_key column
     *
     * Example usage:
     * <code>
     * $query->filterByEmailKey('fooValue');   // WHERE axys_account_email_key = 'fooValue'
     * $query->filterByEmailKey('%fooValue%', Criteria::LIKE); // WHERE axys_account_email_key LIKE '%fooValue%'
     * $query->filterByEmailKey(['foo', 'bar']); // WHERE axys_account_email_key IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $emailKey The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEmailKey($emailKey = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($emailKey)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_EMAIL_KEY, $emailKey, $comparison);

        return $this;
    }

    /**
     * Filter the query on the axys_account_screen_name column
     *
     * Example usage:
     * <code>
     * $query->filterByUsername('fooValue');   // WHERE axys_account_screen_name = 'fooValue'
     * $query->filterByUsername('%fooValue%', Criteria::LIKE); // WHERE axys_account_screen_name LIKE '%fooValue%'
     * $query->filterByUsername(['foo', 'bar']); // WHERE axys_account_screen_name IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $username The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUsername($username = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($username)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_SCREEN_NAME, $username, $comparison);

        return $this;
    }

    /**
     * Filter the query on the axys_account_slug column
     *
     * Example usage:
     * <code>
     * $query->filterBySlug('fooValue');   // WHERE axys_account_slug = 'fooValue'
     * $query->filterBySlug('%fooValue%', Criteria::LIKE); // WHERE axys_account_slug LIKE '%fooValue%'
     * $query->filterBySlug(['foo', 'bar']); // WHERE axys_account_slug IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $slug The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySlug($slug = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($slug)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_SLUG, $slug, $comparison);

        return $this;
    }

    /**
     * Filter the query on the axys_account_signup_date column
     *
     * Example usage:
     * <code>
     * $query->filterBySignupDate('2011-03-14'); // WHERE axys_account_signup_date = '2011-03-14'
     * $query->filterBySignupDate('now'); // WHERE axys_account_signup_date = '2011-03-14'
     * $query->filterBySignupDate(array('max' => 'yesterday')); // WHERE axys_account_signup_date > '2011-03-13'
     * </code>
     *
     * @param mixed $signupDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySignupDate($signupDate = null, ?string $comparison = null)
    {
        if (is_array($signupDate)) {
            $useMinMax = false;
            if (isset($signupDate['min'])) {
                $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_SIGNUP_DATE, $signupDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($signupDate['max'])) {
                $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_SIGNUP_DATE, $signupDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_SIGNUP_DATE, $signupDate, $comparison);

        return $this;
    }

    /**
     * Filter the query on the axys_account_login_date column
     *
     * Example usage:
     * <code>
     * $query->filterByLoginDate('2011-03-14'); // WHERE axys_account_login_date = '2011-03-14'
     * $query->filterByLoginDate('now'); // WHERE axys_account_login_date = '2011-03-14'
     * $query->filterByLoginDate(array('max' => 'yesterday')); // WHERE axys_account_login_date > '2011-03-13'
     * </code>
     *
     * @param mixed $loginDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByLoginDate($loginDate = null, ?string $comparison = null)
    {
        if (is_array($loginDate)) {
            $useMinMax = false;
            if (isset($loginDate['min'])) {
                $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_LOGIN_DATE, $loginDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($loginDate['max'])) {
                $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_LOGIN_DATE, $loginDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_LOGIN_DATE, $loginDate, $comparison);

        return $this;
    }

    /**
     * Filter the query on the axys_account_first_name column
     *
     * Example usage:
     * <code>
     * $query->filterByFirstName('fooValue');   // WHERE axys_account_first_name = 'fooValue'
     * $query->filterByFirstName('%fooValue%', Criteria::LIKE); // WHERE axys_account_first_name LIKE '%fooValue%'
     * $query->filterByFirstName(['foo', 'bar']); // WHERE axys_account_first_name IN ('foo', 'bar')
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

        $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_FIRST_NAME, $firstName, $comparison);

        return $this;
    }

    /**
     * Filter the query on the axys_account_last_name column
     *
     * Example usage:
     * <code>
     * $query->filterByLastName('fooValue');   // WHERE axys_account_last_name = 'fooValue'
     * $query->filterByLastName('%fooValue%', Criteria::LIKE); // WHERE axys_account_last_name LIKE '%fooValue%'
     * $query->filterByLastName(['foo', 'bar']); // WHERE axys_account_last_name IN ('foo', 'bar')
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

        $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_LAST_NAME, $lastName, $comparison);

        return $this;
    }

    /**
     * Filter the query on the axys_account_update column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdate('2011-03-14'); // WHERE axys_account_update = '2011-03-14'
     * $query->filterByUpdate('now'); // WHERE axys_account_update = '2011-03-14'
     * $query->filterByUpdate(array('max' => 'yesterday')); // WHERE axys_account_update > '2011-03-13'
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
                $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_UPDATE, $update['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($update['max'])) {
                $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_UPDATE, $update['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_UPDATE, $update, $comparison);

        return $this;
    }

    /**
     * Filter the query on the email_verified_at column
     *
     * Example usage:
     * <code>
     * $query->filterByEmailVerifiedAt('2011-03-14'); // WHERE email_verified_at = '2011-03-14'
     * $query->filterByEmailVerifiedAt('now'); // WHERE email_verified_at = '2011-03-14'
     * $query->filterByEmailVerifiedAt(array('max' => 'yesterday')); // WHERE email_verified_at > '2011-03-13'
     * </code>
     *
     * @param mixed $emailVerifiedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEmailVerifiedAt($emailVerifiedAt = null, ?string $comparison = null)
    {
        if (is_array($emailVerifiedAt)) {
            $useMinMax = false;
            if (isset($emailVerifiedAt['min'])) {
                $this->addUsingAlias(AxysAccountTableMap::COL_EMAIL_VERIFIED_AT, $emailVerifiedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($emailVerifiedAt['max'])) {
                $this->addUsingAlias(AxysAccountTableMap::COL_EMAIL_VERIFIED_AT, $emailVerifiedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AxysAccountTableMap::COL_EMAIL_VERIFIED_AT, $emailVerifiedAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the marked_for_email_verification_at column
     *
     * Example usage:
     * <code>
     * $query->filterByMarkedForEmailVerificationAt('2011-03-14'); // WHERE marked_for_email_verification_at = '2011-03-14'
     * $query->filterByMarkedForEmailVerificationAt('now'); // WHERE marked_for_email_verification_at = '2011-03-14'
     * $query->filterByMarkedForEmailVerificationAt(array('max' => 'yesterday')); // WHERE marked_for_email_verification_at > '2011-03-13'
     * </code>
     *
     * @param mixed $markedForEmailVerificationAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByMarkedForEmailVerificationAt($markedForEmailVerificationAt = null, ?string $comparison = null)
    {
        if (is_array($markedForEmailVerificationAt)) {
            $useMinMax = false;
            if (isset($markedForEmailVerificationAt['min'])) {
                $this->addUsingAlias(AxysAccountTableMap::COL_MARKED_FOR_EMAIL_VERIFICATION_AT, $markedForEmailVerificationAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($markedForEmailVerificationAt['max'])) {
                $this->addUsingAlias(AxysAccountTableMap::COL_MARKED_FOR_EMAIL_VERIFICATION_AT, $markedForEmailVerificationAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AxysAccountTableMap::COL_MARKED_FOR_EMAIL_VERIFICATION_AT, $markedForEmailVerificationAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the warned_before_deletion_at column
     *
     * Example usage:
     * <code>
     * $query->filterByWarnedBeforeDeletionAt('2011-03-14'); // WHERE warned_before_deletion_at = '2011-03-14'
     * $query->filterByWarnedBeforeDeletionAt('now'); // WHERE warned_before_deletion_at = '2011-03-14'
     * $query->filterByWarnedBeforeDeletionAt(array('max' => 'yesterday')); // WHERE warned_before_deletion_at > '2011-03-13'
     * </code>
     *
     * @param mixed $warnedBeforeDeletionAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByWarnedBeforeDeletionAt($warnedBeforeDeletionAt = null, ?string $comparison = null)
    {
        if (is_array($warnedBeforeDeletionAt)) {
            $useMinMax = false;
            if (isset($warnedBeforeDeletionAt['min'])) {
                $this->addUsingAlias(AxysAccountTableMap::COL_WARNED_BEFORE_DELETION_AT, $warnedBeforeDeletionAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($warnedBeforeDeletionAt['max'])) {
                $this->addUsingAlias(AxysAccountTableMap::COL_WARNED_BEFORE_DELETION_AT, $warnedBeforeDeletionAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AxysAccountTableMap::COL_WARNED_BEFORE_DELETION_AT, $warnedBeforeDeletionAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the axys_account_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE axys_account_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE axys_account_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE axys_account_created > '2011-03-13'
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
                $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_CREATED, $createdAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the axys_account_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE axys_account_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE axys_account_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE axys_account_updated > '2011-03-13'
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
                $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_UPDATED, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Exclude object from result
     *
     * @param ChildAxysAccount $axysAccount Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($axysAccount = null)
    {
        if ($axysAccount) {
            $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_ID, $axysAccount->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the axys_accounts table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AxysAccountTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            AxysAccountTableMap::clearInstancePool();
            AxysAccountTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(AxysAccountTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(AxysAccountTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            AxysAccountTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            AxysAccountTableMap::clearRelatedInstancePool();

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
        $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_CREATED);

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
        $this->addUsingAlias(AxysAccountTableMap::COL_AXYS_ACCOUNT_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(AxysAccountTableMap::COL_AXYS_ACCOUNT_CREATED);

        return $this;
    }

}
