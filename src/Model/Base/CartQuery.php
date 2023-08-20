<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Cart as ChildCart;
use Model\CartQuery as ChildCartQuery;
use Model\Map\CartTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `carts` table.
 *
 * @method     ChildCartQuery orderById($order = Criteria::ASC) Order by the cart_id column
 * @method     ChildCartQuery orderByUid($order = Criteria::ASC) Order by the cart_uid column
 * @method     ChildCartQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildCartQuery orderByAxysAccountId($order = Criteria::ASC) Order by the axys_account_id column
 * @method     ChildCartQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildCartQuery orderBySellerId($order = Criteria::ASC) Order by the cart_seller_id column
 * @method     ChildCartQuery orderByCustomerId($order = Criteria::ASC) Order by the customer_id column
 * @method     ChildCartQuery orderByTitle($order = Criteria::ASC) Order by the cart_title column
 * @method     ChildCartQuery orderByType($order = Criteria::ASC) Order by the cart_type column
 * @method     ChildCartQuery orderByIp($order = Criteria::ASC) Order by the cart_ip column
 * @method     ChildCartQuery orderByCount($order = Criteria::ASC) Order by the cart_count column
 * @method     ChildCartQuery orderByAmount($order = Criteria::ASC) Order by the cart_amount column
 * @method     ChildCartQuery orderByAsAGift($order = Criteria::ASC) Order by the cart_as_a_gift column
 * @method     ChildCartQuery orderByGiftRecipient($order = Criteria::ASC) Order by the cart_gift_recipient column
 * @method     ChildCartQuery orderByDate($order = Criteria::ASC) Order by the cart_date column
 * @method     ChildCartQuery orderByInsert($order = Criteria::ASC) Order by the cart_insert column
 * @method     ChildCartQuery orderByUpdate($order = Criteria::ASC) Order by the cart_update column
 * @method     ChildCartQuery orderByCreatedAt($order = Criteria::ASC) Order by the cart_created column
 * @method     ChildCartQuery orderByUpdatedAt($order = Criteria::ASC) Order by the cart_updated column
 *
 * @method     ChildCartQuery groupById() Group by the cart_id column
 * @method     ChildCartQuery groupByUid() Group by the cart_uid column
 * @method     ChildCartQuery groupBySiteId() Group by the site_id column
 * @method     ChildCartQuery groupByAxysAccountId() Group by the axys_account_id column
 * @method     ChildCartQuery groupByUserId() Group by the user_id column
 * @method     ChildCartQuery groupBySellerId() Group by the cart_seller_id column
 * @method     ChildCartQuery groupByCustomerId() Group by the customer_id column
 * @method     ChildCartQuery groupByTitle() Group by the cart_title column
 * @method     ChildCartQuery groupByType() Group by the cart_type column
 * @method     ChildCartQuery groupByIp() Group by the cart_ip column
 * @method     ChildCartQuery groupByCount() Group by the cart_count column
 * @method     ChildCartQuery groupByAmount() Group by the cart_amount column
 * @method     ChildCartQuery groupByAsAGift() Group by the cart_as_a_gift column
 * @method     ChildCartQuery groupByGiftRecipient() Group by the cart_gift_recipient column
 * @method     ChildCartQuery groupByDate() Group by the cart_date column
 * @method     ChildCartQuery groupByInsert() Group by the cart_insert column
 * @method     ChildCartQuery groupByUpdate() Group by the cart_update column
 * @method     ChildCartQuery groupByCreatedAt() Group by the cart_created column
 * @method     ChildCartQuery groupByUpdatedAt() Group by the cart_updated column
 *
 * @method     ChildCartQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCartQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCartQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCartQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildCartQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildCartQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildCartQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildCartQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildCartQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildCartQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildCartQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildCartQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildCartQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     ChildCartQuery leftJoinSite($relationAlias = null) Adds a LEFT JOIN clause to the query using the Site relation
 * @method     ChildCartQuery rightJoinSite($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Site relation
 * @method     ChildCartQuery innerJoinSite($relationAlias = null) Adds a INNER JOIN clause to the query using the Site relation
 *
 * @method     ChildCartQuery joinWithSite($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Site relation
 *
 * @method     ChildCartQuery leftJoinWithSite() Adds a LEFT JOIN clause and with to the query using the Site relation
 * @method     ChildCartQuery rightJoinWithSite() Adds a RIGHT JOIN clause and with to the query using the Site relation
 * @method     ChildCartQuery innerJoinWithSite() Adds a INNER JOIN clause and with to the query using the Site relation
 *
 * @method     ChildCartQuery leftJoinAxysAccount($relationAlias = null) Adds a LEFT JOIN clause to the query using the AxysAccount relation
 * @method     ChildCartQuery rightJoinAxysAccount($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AxysAccount relation
 * @method     ChildCartQuery innerJoinAxysAccount($relationAlias = null) Adds a INNER JOIN clause to the query using the AxysAccount relation
 *
 * @method     ChildCartQuery joinWithAxysAccount($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the AxysAccount relation
 *
 * @method     ChildCartQuery leftJoinWithAxysAccount() Adds a LEFT JOIN clause and with to the query using the AxysAccount relation
 * @method     ChildCartQuery rightJoinWithAxysAccount() Adds a RIGHT JOIN clause and with to the query using the AxysAccount relation
 * @method     ChildCartQuery innerJoinWithAxysAccount() Adds a INNER JOIN clause and with to the query using the AxysAccount relation
 *
 * @method     ChildCartQuery leftJoinStock($relationAlias = null) Adds a LEFT JOIN clause to the query using the Stock relation
 * @method     ChildCartQuery rightJoinStock($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Stock relation
 * @method     ChildCartQuery innerJoinStock($relationAlias = null) Adds a INNER JOIN clause to the query using the Stock relation
 *
 * @method     ChildCartQuery joinWithStock($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Stock relation
 *
 * @method     ChildCartQuery leftJoinWithStock() Adds a LEFT JOIN clause and with to the query using the Stock relation
 * @method     ChildCartQuery rightJoinWithStock() Adds a RIGHT JOIN clause and with to the query using the Stock relation
 * @method     ChildCartQuery innerJoinWithStock() Adds a INNER JOIN clause and with to the query using the Stock relation
 *
 * @method     \Model\UserQuery|\Model\SiteQuery|\Model\AxysAccountQuery|\Model\StockQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildCart|null findOne(?ConnectionInterface $con = null) Return the first ChildCart matching the query
 * @method     ChildCart findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildCart matching the query, or a new ChildCart object populated from the query conditions when no match is found
 *
 * @method     ChildCart|null findOneById(int $cart_id) Return the first ChildCart filtered by the cart_id column
 * @method     ChildCart|null findOneByUid(string $cart_uid) Return the first ChildCart filtered by the cart_uid column
 * @method     ChildCart|null findOneBySiteId(int $site_id) Return the first ChildCart filtered by the site_id column
 * @method     ChildCart|null findOneByAxysAccountId(int $axys_account_id) Return the first ChildCart filtered by the axys_account_id column
 * @method     ChildCart|null findOneByUserId(int $user_id) Return the first ChildCart filtered by the user_id column
 * @method     ChildCart|null findOneBySellerId(int $cart_seller_id) Return the first ChildCart filtered by the cart_seller_id column
 * @method     ChildCart|null findOneByCustomerId(int $customer_id) Return the first ChildCart filtered by the customer_id column
 * @method     ChildCart|null findOneByTitle(string $cart_title) Return the first ChildCart filtered by the cart_title column
 * @method     ChildCart|null findOneByType(string $cart_type) Return the first ChildCart filtered by the cart_type column
 * @method     ChildCart|null findOneByIp(string $cart_ip) Return the first ChildCart filtered by the cart_ip column
 * @method     ChildCart|null findOneByCount(int $cart_count) Return the first ChildCart filtered by the cart_count column
 * @method     ChildCart|null findOneByAmount(int $cart_amount) Return the first ChildCart filtered by the cart_amount column
 * @method     ChildCart|null findOneByAsAGift(string $cart_as_a_gift) Return the first ChildCart filtered by the cart_as_a_gift column
 * @method     ChildCart|null findOneByGiftRecipient(int $cart_gift_recipient) Return the first ChildCart filtered by the cart_gift_recipient column
 * @method     ChildCart|null findOneByDate(string $cart_date) Return the first ChildCart filtered by the cart_date column
 * @method     ChildCart|null findOneByInsert(string $cart_insert) Return the first ChildCart filtered by the cart_insert column
 * @method     ChildCart|null findOneByUpdate(string $cart_update) Return the first ChildCart filtered by the cart_update column
 * @method     ChildCart|null findOneByCreatedAt(string $cart_created) Return the first ChildCart filtered by the cart_created column
 * @method     ChildCart|null findOneByUpdatedAt(string $cart_updated) Return the first ChildCart filtered by the cart_updated column
 *
 * @method     ChildCart requirePk($key, ?ConnectionInterface $con = null) Return the ChildCart by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOne(?ConnectionInterface $con = null) Return the first ChildCart matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCart requireOneById(int $cart_id) Return the first ChildCart filtered by the cart_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByUid(string $cart_uid) Return the first ChildCart filtered by the cart_uid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneBySiteId(int $site_id) Return the first ChildCart filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByAxysAccountId(int $axys_account_id) Return the first ChildCart filtered by the axys_account_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByUserId(int $user_id) Return the first ChildCart filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneBySellerId(int $cart_seller_id) Return the first ChildCart filtered by the cart_seller_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByCustomerId(int $customer_id) Return the first ChildCart filtered by the customer_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByTitle(string $cart_title) Return the first ChildCart filtered by the cart_title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByType(string $cart_type) Return the first ChildCart filtered by the cart_type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByIp(string $cart_ip) Return the first ChildCart filtered by the cart_ip column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByCount(int $cart_count) Return the first ChildCart filtered by the cart_count column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByAmount(int $cart_amount) Return the first ChildCart filtered by the cart_amount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByAsAGift(string $cart_as_a_gift) Return the first ChildCart filtered by the cart_as_a_gift column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByGiftRecipient(int $cart_gift_recipient) Return the first ChildCart filtered by the cart_gift_recipient column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByDate(string $cart_date) Return the first ChildCart filtered by the cart_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByInsert(string $cart_insert) Return the first ChildCart filtered by the cart_insert column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByUpdate(string $cart_update) Return the first ChildCart filtered by the cart_update column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByCreatedAt(string $cart_created) Return the first ChildCart filtered by the cart_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCart requireOneByUpdatedAt(string $cart_updated) Return the first ChildCart filtered by the cart_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCart[]|Collection find(?ConnectionInterface $con = null) Return ChildCart objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildCart> find(?ConnectionInterface $con = null) Return ChildCart objects based on current ModelCriteria
 *
 * @method     ChildCart[]|Collection findById(int|array<int> $cart_id) Return ChildCart objects filtered by the cart_id column
 * @psalm-method Collection&\Traversable<ChildCart> findById(int|array<int> $cart_id) Return ChildCart objects filtered by the cart_id column
 * @method     ChildCart[]|Collection findByUid(string|array<string> $cart_uid) Return ChildCart objects filtered by the cart_uid column
 * @psalm-method Collection&\Traversable<ChildCart> findByUid(string|array<string> $cart_uid) Return ChildCart objects filtered by the cart_uid column
 * @method     ChildCart[]|Collection findBySiteId(int|array<int> $site_id) Return ChildCart objects filtered by the site_id column
 * @psalm-method Collection&\Traversable<ChildCart> findBySiteId(int|array<int> $site_id) Return ChildCart objects filtered by the site_id column
 * @method     ChildCart[]|Collection findByAxysAccountId(int|array<int> $axys_account_id) Return ChildCart objects filtered by the axys_account_id column
 * @psalm-method Collection&\Traversable<ChildCart> findByAxysAccountId(int|array<int> $axys_account_id) Return ChildCart objects filtered by the axys_account_id column
 * @method     ChildCart[]|Collection findByUserId(int|array<int> $user_id) Return ChildCart objects filtered by the user_id column
 * @psalm-method Collection&\Traversable<ChildCart> findByUserId(int|array<int> $user_id) Return ChildCart objects filtered by the user_id column
 * @method     ChildCart[]|Collection findBySellerId(int|array<int> $cart_seller_id) Return ChildCart objects filtered by the cart_seller_id column
 * @psalm-method Collection&\Traversable<ChildCart> findBySellerId(int|array<int> $cart_seller_id) Return ChildCart objects filtered by the cart_seller_id column
 * @method     ChildCart[]|Collection findByCustomerId(int|array<int> $customer_id) Return ChildCart objects filtered by the customer_id column
 * @psalm-method Collection&\Traversable<ChildCart> findByCustomerId(int|array<int> $customer_id) Return ChildCart objects filtered by the customer_id column
 * @method     ChildCart[]|Collection findByTitle(string|array<string> $cart_title) Return ChildCart objects filtered by the cart_title column
 * @psalm-method Collection&\Traversable<ChildCart> findByTitle(string|array<string> $cart_title) Return ChildCart objects filtered by the cart_title column
 * @method     ChildCart[]|Collection findByType(string|array<string> $cart_type) Return ChildCart objects filtered by the cart_type column
 * @psalm-method Collection&\Traversable<ChildCart> findByType(string|array<string> $cart_type) Return ChildCart objects filtered by the cart_type column
 * @method     ChildCart[]|Collection findByIp(string|array<string> $cart_ip) Return ChildCart objects filtered by the cart_ip column
 * @psalm-method Collection&\Traversable<ChildCart> findByIp(string|array<string> $cart_ip) Return ChildCart objects filtered by the cart_ip column
 * @method     ChildCart[]|Collection findByCount(int|array<int> $cart_count) Return ChildCart objects filtered by the cart_count column
 * @psalm-method Collection&\Traversable<ChildCart> findByCount(int|array<int> $cart_count) Return ChildCart objects filtered by the cart_count column
 * @method     ChildCart[]|Collection findByAmount(int|array<int> $cart_amount) Return ChildCart objects filtered by the cart_amount column
 * @psalm-method Collection&\Traversable<ChildCart> findByAmount(int|array<int> $cart_amount) Return ChildCart objects filtered by the cart_amount column
 * @method     ChildCart[]|Collection findByAsAGift(string|array<string> $cart_as_a_gift) Return ChildCart objects filtered by the cart_as_a_gift column
 * @psalm-method Collection&\Traversable<ChildCart> findByAsAGift(string|array<string> $cart_as_a_gift) Return ChildCart objects filtered by the cart_as_a_gift column
 * @method     ChildCart[]|Collection findByGiftRecipient(int|array<int> $cart_gift_recipient) Return ChildCart objects filtered by the cart_gift_recipient column
 * @psalm-method Collection&\Traversable<ChildCart> findByGiftRecipient(int|array<int> $cart_gift_recipient) Return ChildCart objects filtered by the cart_gift_recipient column
 * @method     ChildCart[]|Collection findByDate(string|array<string> $cart_date) Return ChildCart objects filtered by the cart_date column
 * @psalm-method Collection&\Traversable<ChildCart> findByDate(string|array<string> $cart_date) Return ChildCart objects filtered by the cart_date column
 * @method     ChildCart[]|Collection findByInsert(string|array<string> $cart_insert) Return ChildCart objects filtered by the cart_insert column
 * @psalm-method Collection&\Traversable<ChildCart> findByInsert(string|array<string> $cart_insert) Return ChildCart objects filtered by the cart_insert column
 * @method     ChildCart[]|Collection findByUpdate(string|array<string> $cart_update) Return ChildCart objects filtered by the cart_update column
 * @psalm-method Collection&\Traversable<ChildCart> findByUpdate(string|array<string> $cart_update) Return ChildCart objects filtered by the cart_update column
 * @method     ChildCart[]|Collection findByCreatedAt(string|array<string> $cart_created) Return ChildCart objects filtered by the cart_created column
 * @psalm-method Collection&\Traversable<ChildCart> findByCreatedAt(string|array<string> $cart_created) Return ChildCart objects filtered by the cart_created column
 * @method     ChildCart[]|Collection findByUpdatedAt(string|array<string> $cart_updated) Return ChildCart objects filtered by the cart_updated column
 * @psalm-method Collection&\Traversable<ChildCart> findByUpdatedAt(string|array<string> $cart_updated) Return ChildCart objects filtered by the cart_updated column
 *
 * @method     ChildCart[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildCart> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class CartQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\CartQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Cart', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCartQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCartQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildCartQuery) {
            return $criteria;
        }
        $query = new ChildCartQuery();
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
     * @return ChildCart|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CartTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = CartTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildCart A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT cart_id, cart_uid, site_id, axys_account_id, user_id, cart_seller_id, customer_id, cart_title, cart_type, cart_ip, cart_count, cart_amount, cart_as_a_gift, cart_gift_recipient, cart_date, cart_insert, cart_update, cart_created, cart_updated FROM carts WHERE cart_id = :p0';
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
            /** @var ChildCart $obj */
            $obj = new ChildCart();
            $obj->hydrate($row);
            CartTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildCart|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(CartTableMap::COL_CART_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(CartTableMap::COL_CART_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the cart_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE cart_id = 1234
     * $query->filterById(array(12, 34)); // WHERE cart_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE cart_id > 12
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
                $this->addUsingAlias(CartTableMap::COL_CART_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CartTableMap::COL_CART_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the cart_uid column
     *
     * Example usage:
     * <code>
     * $query->filterByUid('fooValue');   // WHERE cart_uid = 'fooValue'
     * $query->filterByUid('%fooValue%', Criteria::LIKE); // WHERE cart_uid LIKE '%fooValue%'
     * $query->filterByUid(['foo', 'bar']); // WHERE cart_uid IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $uid The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUid($uid = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($uid)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CartTableMap::COL_CART_UID, $uid, $comparison);

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
                $this->addUsingAlias(CartTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(CartTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CartTableMap::COL_SITE_ID, $siteId, $comparison);

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
     * @see       filterByAxysAccount()
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
                $this->addUsingAlias(CartTableMap::COL_AXYS_ACCOUNT_ID, $axysAccountId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($axysAccountId['max'])) {
                $this->addUsingAlias(CartTableMap::COL_AXYS_ACCOUNT_ID, $axysAccountId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CartTableMap::COL_AXYS_ACCOUNT_ID, $axysAccountId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE user_id = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE user_id > 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUserId($userId = null, ?string $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(CartTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(CartTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CartTableMap::COL_USER_ID, $userId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the cart_seller_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySellerId(1234); // WHERE cart_seller_id = 1234
     * $query->filterBySellerId(array(12, 34)); // WHERE cart_seller_id IN (12, 34)
     * $query->filterBySellerId(array('min' => 12)); // WHERE cart_seller_id > 12
     * </code>
     *
     * @param mixed $sellerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySellerId($sellerId = null, ?string $comparison = null)
    {
        if (is_array($sellerId)) {
            $useMinMax = false;
            if (isset($sellerId['min'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_SELLER_ID, $sellerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sellerId['max'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_SELLER_ID, $sellerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CartTableMap::COL_CART_SELLER_ID, $sellerId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the customer_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCustomerId(1234); // WHERE customer_id = 1234
     * $query->filterByCustomerId(array(12, 34)); // WHERE customer_id IN (12, 34)
     * $query->filterByCustomerId(array('min' => 12)); // WHERE customer_id > 12
     * </code>
     *
     * @param mixed $customerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCustomerId($customerId = null, ?string $comparison = null)
    {
        if (is_array($customerId)) {
            $useMinMax = false;
            if (isset($customerId['min'])) {
                $this->addUsingAlias(CartTableMap::COL_CUSTOMER_ID, $customerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($customerId['max'])) {
                $this->addUsingAlias(CartTableMap::COL_CUSTOMER_ID, $customerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CartTableMap::COL_CUSTOMER_ID, $customerId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the cart_title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE cart_title = 'fooValue'
     * $query->filterByTitle('%fooValue%', Criteria::LIKE); // WHERE cart_title LIKE '%fooValue%'
     * $query->filterByTitle(['foo', 'bar']); // WHERE cart_title IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $title The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTitle($title = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CartTableMap::COL_CART_TITLE, $title, $comparison);

        return $this;
    }

    /**
     * Filter the query on the cart_type column
     *
     * Example usage:
     * <code>
     * $query->filterByType('fooValue');   // WHERE cart_type = 'fooValue'
     * $query->filterByType('%fooValue%', Criteria::LIKE); // WHERE cart_type LIKE '%fooValue%'
     * $query->filterByType(['foo', 'bar']); // WHERE cart_type IN ('foo', 'bar')
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

        $this->addUsingAlias(CartTableMap::COL_CART_TYPE, $type, $comparison);

        return $this;
    }

    /**
     * Filter the query on the cart_ip column
     *
     * Example usage:
     * <code>
     * $query->filterByIp('fooValue');   // WHERE cart_ip = 'fooValue'
     * $query->filterByIp('%fooValue%', Criteria::LIKE); // WHERE cart_ip LIKE '%fooValue%'
     * $query->filterByIp(['foo', 'bar']); // WHERE cart_ip IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $ip The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByIp($ip = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($ip)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CartTableMap::COL_CART_IP, $ip, $comparison);

        return $this;
    }

    /**
     * Filter the query on the cart_count column
     *
     * Example usage:
     * <code>
     * $query->filterByCount(1234); // WHERE cart_count = 1234
     * $query->filterByCount(array(12, 34)); // WHERE cart_count IN (12, 34)
     * $query->filterByCount(array('min' => 12)); // WHERE cart_count > 12
     * </code>
     *
     * @param mixed $count The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCount($count = null, ?string $comparison = null)
    {
        if (is_array($count)) {
            $useMinMax = false;
            if (isset($count['min'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_COUNT, $count['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($count['max'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_COUNT, $count['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CartTableMap::COL_CART_COUNT, $count, $comparison);

        return $this;
    }

    /**
     * Filter the query on the cart_amount column
     *
     * Example usage:
     * <code>
     * $query->filterByAmount(1234); // WHERE cart_amount = 1234
     * $query->filterByAmount(array(12, 34)); // WHERE cart_amount IN (12, 34)
     * $query->filterByAmount(array('min' => 12)); // WHERE cart_amount > 12
     * </code>
     *
     * @param mixed $amount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAmount($amount = null, ?string $comparison = null)
    {
        if (is_array($amount)) {
            $useMinMax = false;
            if (isset($amount['min'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_AMOUNT, $amount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($amount['max'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_AMOUNT, $amount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CartTableMap::COL_CART_AMOUNT, $amount, $comparison);

        return $this;
    }

    /**
     * Filter the query on the cart_as_a_gift column
     *
     * Example usage:
     * <code>
     * $query->filterByAsAGift('fooValue');   // WHERE cart_as_a_gift = 'fooValue'
     * $query->filterByAsAGift('%fooValue%', Criteria::LIKE); // WHERE cart_as_a_gift LIKE '%fooValue%'
     * $query->filterByAsAGift(['foo', 'bar']); // WHERE cart_as_a_gift IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $asAGift The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAsAGift($asAGift = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($asAGift)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CartTableMap::COL_AS_A_GIFT, $asAGift, $comparison);

        return $this;
    }

    /**
     * Filter the query on the cart_gift_recipient column
     *
     * Example usage:
     * <code>
     * $query->filterByGiftRecipient(1234); // WHERE cart_gift_recipient = 1234
     * $query->filterByGiftRecipient(array(12, 34)); // WHERE cart_gift_recipient IN (12, 34)
     * $query->filterByGiftRecipient(array('min' => 12)); // WHERE cart_gift_recipient > 12
     * </code>
     *
     * @param mixed $giftRecipient The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByGiftRecipient($giftRecipient = null, ?string $comparison = null)
    {
        if (is_array($giftRecipient)) {
            $useMinMax = false;
            if (isset($giftRecipient['min'])) {
                $this->addUsingAlias(CartTableMap::COL_GIFT_RECIPIENT, $giftRecipient['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($giftRecipient['max'])) {
                $this->addUsingAlias(CartTableMap::COL_GIFT_RECIPIENT, $giftRecipient['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CartTableMap::COL_GIFT_RECIPIENT, $giftRecipient, $comparison);

        return $this;
    }

    /**
     * Filter the query on the cart_date column
     *
     * Example usage:
     * <code>
     * $query->filterByDate('2011-03-14'); // WHERE cart_date = '2011-03-14'
     * $query->filterByDate('now'); // WHERE cart_date = '2011-03-14'
     * $query->filterByDate(array('max' => 'yesterday')); // WHERE cart_date > '2011-03-13'
     * </code>
     *
     * @param mixed $date The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDate($date = null, ?string $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CartTableMap::COL_CART_DATE, $date, $comparison);

        return $this;
    }

    /**
     * Filter the query on the cart_insert column
     *
     * Example usage:
     * <code>
     * $query->filterByInsert('2011-03-14'); // WHERE cart_insert = '2011-03-14'
     * $query->filterByInsert('now'); // WHERE cart_insert = '2011-03-14'
     * $query->filterByInsert(array('max' => 'yesterday')); // WHERE cart_insert > '2011-03-13'
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
                $this->addUsingAlias(CartTableMap::COL_CART_INSERT, $insert['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($insert['max'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_INSERT, $insert['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CartTableMap::COL_CART_INSERT, $insert, $comparison);

        return $this;
    }

    /**
     * Filter the query on the cart_update column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdate('2011-03-14'); // WHERE cart_update = '2011-03-14'
     * $query->filterByUpdate('now'); // WHERE cart_update = '2011-03-14'
     * $query->filterByUpdate(array('max' => 'yesterday')); // WHERE cart_update > '2011-03-13'
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
                $this->addUsingAlias(CartTableMap::COL_CART_UPDATE, $update['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($update['max'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_UPDATE, $update['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CartTableMap::COL_CART_UPDATE, $update, $comparison);

        return $this;
    }

    /**
     * Filter the query on the cart_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE cart_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE cart_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE cart_created > '2011-03-13'
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
                $this->addUsingAlias(CartTableMap::COL_CART_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CartTableMap::COL_CART_CREATED, $createdAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the cart_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE cart_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE cart_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE cart_updated > '2011-03-13'
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
                $this->addUsingAlias(CartTableMap::COL_CART_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(CartTableMap::COL_CART_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CartTableMap::COL_CART_UPDATED, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \Model\User object
     *
     * @param \Model\User|ObjectCollection $user The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUser($user, ?string $comparison = null)
    {
        if ($user instanceof \Model\User) {
            return $this
                ->addUsingAlias(CartTableMap::COL_USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(CartTableMap::COL_USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \Model\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinUser(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

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
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\Model\UserQuery');
    }

    /**
     * Use the User relation User object
     *
     * @param callable(\Model\UserQuery):\Model\UserQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withUserQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useUserQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to User table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\UserQuery The inner query object of the EXISTS statement
     */
    public function useUserExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\UserQuery */
        $q = $this->useExistsQuery('User', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to User table for a NOT EXISTS query.
     *
     * @see useUserExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\UserQuery The inner query object of the NOT EXISTS statement
     */
    public function useUserNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\UserQuery */
        $q = $this->useExistsQuery('User', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to User table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\UserQuery The inner query object of the IN statement
     */
    public function useInUserQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\UserQuery */
        $q = $this->useInQuery('User', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to User table for a NOT IN query.
     *
     * @see useUserInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\UserQuery The inner query object of the NOT IN statement
     */
    public function useNotInUserQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\UserQuery */
        $q = $this->useInQuery('User', $modelAlias, $queryClass, 'NOT IN');
        return $q;
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
                ->addUsingAlias(CartTableMap::COL_SITE_ID, $site->getId(), $comparison);
        } elseif ($site instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(CartTableMap::COL_SITE_ID, $site->toKeyValue('PrimaryKey', 'Id'), $comparison);

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
    public function joinSite(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
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
    public function useSiteQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
        ?string $joinType = Criteria::LEFT_JOIN
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
     * Filter the query by a related \Model\AxysAccount object
     *
     * @param \Model\AxysAccount|ObjectCollection $axysAccount The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAxysAccount($axysAccount, ?string $comparison = null)
    {
        if ($axysAccount instanceof \Model\AxysAccount) {
            return $this
                ->addUsingAlias(CartTableMap::COL_AXYS_ACCOUNT_ID, $axysAccount->getId(), $comparison);
        } elseif ($axysAccount instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(CartTableMap::COL_AXYS_ACCOUNT_ID, $axysAccount->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByAxysAccount() only accepts arguments of type \Model\AxysAccount or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AxysAccount relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinAxysAccount(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AxysAccount');

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
            $this->addJoinObject($join, 'AxysAccount');
        }

        return $this;
    }

    /**
     * Use the AxysAccount relation AxysAccount object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\AxysAccountQuery A secondary query class using the current class as primary query
     */
    public function useAxysAccountQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinAxysAccount($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AxysAccount', '\Model\AxysAccountQuery');
    }

    /**
     * Use the AxysAccount relation AxysAccount object
     *
     * @param callable(\Model\AxysAccountQuery):\Model\AxysAccountQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withAxysAccountQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useAxysAccountQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to AxysAccount table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\AxysAccountQuery The inner query object of the EXISTS statement
     */
    public function useAxysAccountExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\AxysAccountQuery */
        $q = $this->useExistsQuery('AxysAccount', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to AxysAccount table for a NOT EXISTS query.
     *
     * @see useAxysAccountExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\AxysAccountQuery The inner query object of the NOT EXISTS statement
     */
    public function useAxysAccountNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\AxysAccountQuery */
        $q = $this->useExistsQuery('AxysAccount', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to AxysAccount table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\AxysAccountQuery The inner query object of the IN statement
     */
    public function useInAxysAccountQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\AxysAccountQuery */
        $q = $this->useInQuery('AxysAccount', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to AxysAccount table for a NOT IN query.
     *
     * @see useAxysAccountInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\AxysAccountQuery The inner query object of the NOT IN statement
     */
    public function useNotInAxysAccountQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\AxysAccountQuery */
        $q = $this->useInQuery('AxysAccount', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Stock object
     *
     * @param \Model\Stock|ObjectCollection $stock the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByStock($stock, ?string $comparison = null)
    {
        if ($stock instanceof \Model\Stock) {
            $this
                ->addUsingAlias(CartTableMap::COL_CART_ID, $stock->getCartId(), $comparison);

            return $this;
        } elseif ($stock instanceof ObjectCollection) {
            $this
                ->useStockQuery()
                ->filterByPrimaryKeys($stock->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByStock() only accepts arguments of type \Model\Stock or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Stock relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinStock(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Stock');

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
            $this->addJoinObject($join, 'Stock');
        }

        return $this;
    }

    /**
     * Use the Stock relation Stock object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\StockQuery A secondary query class using the current class as primary query
     */
    public function useStockQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinStock($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Stock', '\Model\StockQuery');
    }

    /**
     * Use the Stock relation Stock object
     *
     * @param callable(\Model\StockQuery):\Model\StockQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withStockQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useStockQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Stock table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\StockQuery The inner query object of the EXISTS statement
     */
    public function useStockExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\StockQuery */
        $q = $this->useExistsQuery('Stock', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Stock table for a NOT EXISTS query.
     *
     * @see useStockExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\StockQuery The inner query object of the NOT EXISTS statement
     */
    public function useStockNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\StockQuery */
        $q = $this->useExistsQuery('Stock', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Stock table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\StockQuery The inner query object of the IN statement
     */
    public function useInStockQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\StockQuery */
        $q = $this->useInQuery('Stock', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Stock table for a NOT IN query.
     *
     * @see useStockInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\StockQuery The inner query object of the NOT IN statement
     */
    public function useNotInStockQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\StockQuery */
        $q = $this->useInQuery('Stock', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Exclude object from result
     *
     * @param ChildCart $cart Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($cart = null)
    {
        if ($cart) {
            $this->addUsingAlias(CartTableMap::COL_CART_ID, $cart->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the carts table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CartTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CartTableMap::clearInstancePool();
            CartTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(CartTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CartTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            CartTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CartTableMap::clearRelatedInstancePool();

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
        $this->addUsingAlias(CartTableMap::COL_CART_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(CartTableMap::COL_CART_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(CartTableMap::COL_CART_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(CartTableMap::COL_CART_CREATED);

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
        $this->addUsingAlias(CartTableMap::COL_CART_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(CartTableMap::COL_CART_CREATED);

        return $this;
    }

}
