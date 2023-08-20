<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Wish as ChildWish;
use Model\WishQuery as ChildWishQuery;
use Model\Map\WishTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `wishes` table.
 *
 * @method     ChildWishQuery orderById($order = Criteria::ASC) Order by the wish_id column
 * @method     ChildWishQuery orderByWishlistId($order = Criteria::ASC) Order by the wishlist_id column
 * @method     ChildWishQuery orderByAxysAccountId($order = Criteria::ASC) Order by the axys_account_id column
 * @method     ChildWishQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildWishQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildWishQuery orderByArticleId($order = Criteria::ASC) Order by the article_id column
 * @method     ChildWishQuery orderByCreatedAt($order = Criteria::ASC) Order by the wish_created column
 * @method     ChildWishQuery orderByUpdatedAt($order = Criteria::ASC) Order by the wish_updated column
 * @method     ChildWishQuery orderByBought($order = Criteria::ASC) Order by the wish_bought column
 *
 * @method     ChildWishQuery groupById() Group by the wish_id column
 * @method     ChildWishQuery groupByWishlistId() Group by the wishlist_id column
 * @method     ChildWishQuery groupByAxysAccountId() Group by the axys_account_id column
 * @method     ChildWishQuery groupByUserId() Group by the user_id column
 * @method     ChildWishQuery groupBySiteId() Group by the site_id column
 * @method     ChildWishQuery groupByArticleId() Group by the article_id column
 * @method     ChildWishQuery groupByCreatedAt() Group by the wish_created column
 * @method     ChildWishQuery groupByUpdatedAt() Group by the wish_updated column
 * @method     ChildWishQuery groupByBought() Group by the wish_bought column
 *
 * @method     ChildWishQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildWishQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildWishQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildWishQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildWishQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildWishQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildWishQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildWishQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildWishQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildWishQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildWishQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildWishQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildWishQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     ChildWishQuery leftJoinAxysAccount($relationAlias = null) Adds a LEFT JOIN clause to the query using the AxysAccount relation
 * @method     ChildWishQuery rightJoinAxysAccount($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AxysAccount relation
 * @method     ChildWishQuery innerJoinAxysAccount($relationAlias = null) Adds a INNER JOIN clause to the query using the AxysAccount relation
 *
 * @method     ChildWishQuery joinWithAxysAccount($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the AxysAccount relation
 *
 * @method     ChildWishQuery leftJoinWithAxysAccount() Adds a LEFT JOIN clause and with to the query using the AxysAccount relation
 * @method     ChildWishQuery rightJoinWithAxysAccount() Adds a RIGHT JOIN clause and with to the query using the AxysAccount relation
 * @method     ChildWishQuery innerJoinWithAxysAccount() Adds a INNER JOIN clause and with to the query using the AxysAccount relation
 *
 * @method     \Model\UserQuery|\Model\AxysAccountQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildWish|null findOne(?ConnectionInterface $con = null) Return the first ChildWish matching the query
 * @method     ChildWish findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildWish matching the query, or a new ChildWish object populated from the query conditions when no match is found
 *
 * @method     ChildWish|null findOneById(int $wish_id) Return the first ChildWish filtered by the wish_id column
 * @method     ChildWish|null findOneByWishlistId(int $wishlist_id) Return the first ChildWish filtered by the wishlist_id column
 * @method     ChildWish|null findOneByAxysAccountId(int $axys_account_id) Return the first ChildWish filtered by the axys_account_id column
 * @method     ChildWish|null findOneByUserId(int $user_id) Return the first ChildWish filtered by the user_id column
 * @method     ChildWish|null findOneBySiteId(int $site_id) Return the first ChildWish filtered by the site_id column
 * @method     ChildWish|null findOneByArticleId(int $article_id) Return the first ChildWish filtered by the article_id column
 * @method     ChildWish|null findOneByCreatedAt(string $wish_created) Return the first ChildWish filtered by the wish_created column
 * @method     ChildWish|null findOneByUpdatedAt(string $wish_updated) Return the first ChildWish filtered by the wish_updated column
 * @method     ChildWish|null findOneByBought(string $wish_bought) Return the first ChildWish filtered by the wish_bought column
 *
 * @method     ChildWish requirePk($key, ?ConnectionInterface $con = null) Return the ChildWish by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWish requireOne(?ConnectionInterface $con = null) Return the first ChildWish matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildWish requireOneById(int $wish_id) Return the first ChildWish filtered by the wish_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWish requireOneByWishlistId(int $wishlist_id) Return the first ChildWish filtered by the wishlist_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWish requireOneByAxysAccountId(int $axys_account_id) Return the first ChildWish filtered by the axys_account_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWish requireOneByUserId(int $user_id) Return the first ChildWish filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWish requireOneBySiteId(int $site_id) Return the first ChildWish filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWish requireOneByArticleId(int $article_id) Return the first ChildWish filtered by the article_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWish requireOneByCreatedAt(string $wish_created) Return the first ChildWish filtered by the wish_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWish requireOneByUpdatedAt(string $wish_updated) Return the first ChildWish filtered by the wish_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildWish requireOneByBought(string $wish_bought) Return the first ChildWish filtered by the wish_bought column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildWish[]|Collection find(?ConnectionInterface $con = null) Return ChildWish objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildWish> find(?ConnectionInterface $con = null) Return ChildWish objects based on current ModelCriteria
 *
 * @method     ChildWish[]|Collection findById(int|array<int> $wish_id) Return ChildWish objects filtered by the wish_id column
 * @psalm-method Collection&\Traversable<ChildWish> findById(int|array<int> $wish_id) Return ChildWish objects filtered by the wish_id column
 * @method     ChildWish[]|Collection findByWishlistId(int|array<int> $wishlist_id) Return ChildWish objects filtered by the wishlist_id column
 * @psalm-method Collection&\Traversable<ChildWish> findByWishlistId(int|array<int> $wishlist_id) Return ChildWish objects filtered by the wishlist_id column
 * @method     ChildWish[]|Collection findByAxysAccountId(int|array<int> $axys_account_id) Return ChildWish objects filtered by the axys_account_id column
 * @psalm-method Collection&\Traversable<ChildWish> findByAxysAccountId(int|array<int> $axys_account_id) Return ChildWish objects filtered by the axys_account_id column
 * @method     ChildWish[]|Collection findByUserId(int|array<int> $user_id) Return ChildWish objects filtered by the user_id column
 * @psalm-method Collection&\Traversable<ChildWish> findByUserId(int|array<int> $user_id) Return ChildWish objects filtered by the user_id column
 * @method     ChildWish[]|Collection findBySiteId(int|array<int> $site_id) Return ChildWish objects filtered by the site_id column
 * @psalm-method Collection&\Traversable<ChildWish> findBySiteId(int|array<int> $site_id) Return ChildWish objects filtered by the site_id column
 * @method     ChildWish[]|Collection findByArticleId(int|array<int> $article_id) Return ChildWish objects filtered by the article_id column
 * @psalm-method Collection&\Traversable<ChildWish> findByArticleId(int|array<int> $article_id) Return ChildWish objects filtered by the article_id column
 * @method     ChildWish[]|Collection findByCreatedAt(string|array<string> $wish_created) Return ChildWish objects filtered by the wish_created column
 * @psalm-method Collection&\Traversable<ChildWish> findByCreatedAt(string|array<string> $wish_created) Return ChildWish objects filtered by the wish_created column
 * @method     ChildWish[]|Collection findByUpdatedAt(string|array<string> $wish_updated) Return ChildWish objects filtered by the wish_updated column
 * @psalm-method Collection&\Traversable<ChildWish> findByUpdatedAt(string|array<string> $wish_updated) Return ChildWish objects filtered by the wish_updated column
 * @method     ChildWish[]|Collection findByBought(string|array<string> $wish_bought) Return ChildWish objects filtered by the wish_bought column
 * @psalm-method Collection&\Traversable<ChildWish> findByBought(string|array<string> $wish_bought) Return ChildWish objects filtered by the wish_bought column
 *
 * @method     ChildWish[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildWish> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class WishQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\WishQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Wish', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildWishQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildWishQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildWishQuery) {
            return $criteria;
        }
        $query = new ChildWishQuery();
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
     * @return ChildWish|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(WishTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = WishTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildWish A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT wish_id, wishlist_id, axys_account_id, user_id, site_id, article_id, wish_created, wish_updated, wish_bought FROM wishes WHERE wish_id = :p0';
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
            /** @var ChildWish $obj */
            $obj = new ChildWish();
            $obj->hydrate($row);
            WishTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildWish|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(WishTableMap::COL_WISH_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(WishTableMap::COL_WISH_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the wish_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE wish_id = 1234
     * $query->filterById(array(12, 34)); // WHERE wish_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE wish_id > 12
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
                $this->addUsingAlias(WishTableMap::COL_WISH_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(WishTableMap::COL_WISH_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(WishTableMap::COL_WISH_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the wishlist_id column
     *
     * Example usage:
     * <code>
     * $query->filterByWishlistId(1234); // WHERE wishlist_id = 1234
     * $query->filterByWishlistId(array(12, 34)); // WHERE wishlist_id IN (12, 34)
     * $query->filterByWishlistId(array('min' => 12)); // WHERE wishlist_id > 12
     * </code>
     *
     * @param mixed $wishlistId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByWishlistId($wishlistId = null, ?string $comparison = null)
    {
        if (is_array($wishlistId)) {
            $useMinMax = false;
            if (isset($wishlistId['min'])) {
                $this->addUsingAlias(WishTableMap::COL_WISHLIST_ID, $wishlistId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($wishlistId['max'])) {
                $this->addUsingAlias(WishTableMap::COL_WISHLIST_ID, $wishlistId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(WishTableMap::COL_WISHLIST_ID, $wishlistId, $comparison);

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
                $this->addUsingAlias(WishTableMap::COL_AXYS_ACCOUNT_ID, $axysAccountId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($axysAccountId['max'])) {
                $this->addUsingAlias(WishTableMap::COL_AXYS_ACCOUNT_ID, $axysAccountId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(WishTableMap::COL_AXYS_ACCOUNT_ID, $axysAccountId, $comparison);

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
                $this->addUsingAlias(WishTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(WishTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(WishTableMap::COL_USER_ID, $userId, $comparison);

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
                $this->addUsingAlias(WishTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(WishTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(WishTableMap::COL_SITE_ID, $siteId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_id column
     *
     * Example usage:
     * <code>
     * $query->filterByArticleId(1234); // WHERE article_id = 1234
     * $query->filterByArticleId(array(12, 34)); // WHERE article_id IN (12, 34)
     * $query->filterByArticleId(array('min' => 12)); // WHERE article_id > 12
     * </code>
     *
     * @param mixed $articleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByArticleId($articleId = null, ?string $comparison = null)
    {
        if (is_array($articleId)) {
            $useMinMax = false;
            if (isset($articleId['min'])) {
                $this->addUsingAlias(WishTableMap::COL_ARTICLE_ID, $articleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($articleId['max'])) {
                $this->addUsingAlias(WishTableMap::COL_ARTICLE_ID, $articleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(WishTableMap::COL_ARTICLE_ID, $articleId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the wish_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE wish_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE wish_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE wish_created > '2011-03-13'
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
                $this->addUsingAlias(WishTableMap::COL_WISH_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(WishTableMap::COL_WISH_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(WishTableMap::COL_WISH_CREATED, $createdAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the wish_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE wish_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE wish_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE wish_updated > '2011-03-13'
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
                $this->addUsingAlias(WishTableMap::COL_WISH_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(WishTableMap::COL_WISH_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(WishTableMap::COL_WISH_UPDATED, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the wish_bought column
     *
     * Example usage:
     * <code>
     * $query->filterByBought('2011-03-14'); // WHERE wish_bought = '2011-03-14'
     * $query->filterByBought('now'); // WHERE wish_bought = '2011-03-14'
     * $query->filterByBought(array('max' => 'yesterday')); // WHERE wish_bought > '2011-03-13'
     * </code>
     *
     * @param mixed $bought The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByBought($bought = null, ?string $comparison = null)
    {
        if (is_array($bought)) {
            $useMinMax = false;
            if (isset($bought['min'])) {
                $this->addUsingAlias(WishTableMap::COL_WISH_BOUGHT, $bought['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bought['max'])) {
                $this->addUsingAlias(WishTableMap::COL_WISH_BOUGHT, $bought['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(WishTableMap::COL_WISH_BOUGHT, $bought, $comparison);

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
                ->addUsingAlias(WishTableMap::COL_USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(WishTableMap::COL_USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);

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
                ->addUsingAlias(WishTableMap::COL_AXYS_ACCOUNT_ID, $axysAccount->getId(), $comparison);
        } elseif ($axysAccount instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(WishTableMap::COL_AXYS_ACCOUNT_ID, $axysAccount->toKeyValue('PrimaryKey', 'Id'), $comparison);

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
     * Exclude object from result
     *
     * @param ChildWish $wish Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($wish = null)
    {
        if ($wish) {
            $this->addUsingAlias(WishTableMap::COL_WISH_ID, $wish->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the wishes table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(WishTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            WishTableMap::clearInstancePool();
            WishTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(WishTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(WishTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            WishTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            WishTableMap::clearRelatedInstancePool();

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
        $this->addUsingAlias(WishTableMap::COL_WISH_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(WishTableMap::COL_WISH_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(WishTableMap::COL_WISH_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(WishTableMap::COL_WISH_CREATED);

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
        $this->addUsingAlias(WishTableMap::COL_WISH_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(WishTableMap::COL_WISH_CREATED);

        return $this;
    }

}
