<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\User as ChildUser;
use Model\UserQuery as ChildUserQuery;
use Model\Map\UserTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `users` table.
 *
 * @method     ChildUserQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildUserQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildUserQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method     ChildUserQuery orderByLastLoggedAt($order = Criteria::ASC) Order by the lastLoggedAt column
 * @method     ChildUserQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildUserQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildUserQuery groupById() Group by the id column
 * @method     ChildUserQuery groupBySiteId() Group by the site_id column
 * @method     ChildUserQuery groupByEmail() Group by the email column
 * @method     ChildUserQuery groupByLastLoggedAt() Group by the lastLoggedAt column
 * @method     ChildUserQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildUserQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildUserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildUserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildUserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildUserQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildUserQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildUserQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildUserQuery leftJoinSite($relationAlias = null) Adds a LEFT JOIN clause to the query using the Site relation
 * @method     ChildUserQuery rightJoinSite($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Site relation
 * @method     ChildUserQuery innerJoinSite($relationAlias = null) Adds a INNER JOIN clause to the query using the Site relation
 *
 * @method     ChildUserQuery joinWithSite($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Site relation
 *
 * @method     ChildUserQuery leftJoinWithSite() Adds a LEFT JOIN clause and with to the query using the Site relation
 * @method     ChildUserQuery rightJoinWithSite() Adds a RIGHT JOIN clause and with to the query using the Site relation
 * @method     ChildUserQuery innerJoinWithSite() Adds a INNER JOIN clause and with to the query using the Site relation
 *
 * @method     ChildUserQuery leftJoinAlert($relationAlias = null) Adds a LEFT JOIN clause to the query using the Alert relation
 * @method     ChildUserQuery rightJoinAlert($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Alert relation
 * @method     ChildUserQuery innerJoinAlert($relationAlias = null) Adds a INNER JOIN clause to the query using the Alert relation
 *
 * @method     ChildUserQuery joinWithAlert($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Alert relation
 *
 * @method     ChildUserQuery leftJoinWithAlert() Adds a LEFT JOIN clause and with to the query using the Alert relation
 * @method     ChildUserQuery rightJoinWithAlert() Adds a RIGHT JOIN clause and with to the query using the Alert relation
 * @method     ChildUserQuery innerJoinWithAlert() Adds a INNER JOIN clause and with to the query using the Alert relation
 *
 * @method     ChildUserQuery leftJoinCart($relationAlias = null) Adds a LEFT JOIN clause to the query using the Cart relation
 * @method     ChildUserQuery rightJoinCart($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Cart relation
 * @method     ChildUserQuery innerJoinCart($relationAlias = null) Adds a INNER JOIN clause to the query using the Cart relation
 *
 * @method     ChildUserQuery joinWithCart($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Cart relation
 *
 * @method     ChildUserQuery leftJoinWithCart() Adds a LEFT JOIN clause and with to the query using the Cart relation
 * @method     ChildUserQuery rightJoinWithCart() Adds a RIGHT JOIN clause and with to the query using the Cart relation
 * @method     ChildUserQuery innerJoinWithCart() Adds a INNER JOIN clause and with to the query using the Cart relation
 *
 * @method     ChildUserQuery leftJoinCoupon($relationAlias = null) Adds a LEFT JOIN clause to the query using the Coupon relation
 * @method     ChildUserQuery rightJoinCoupon($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Coupon relation
 * @method     ChildUserQuery innerJoinCoupon($relationAlias = null) Adds a INNER JOIN clause to the query using the Coupon relation
 *
 * @method     ChildUserQuery joinWithCoupon($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Coupon relation
 *
 * @method     ChildUserQuery leftJoinWithCoupon() Adds a LEFT JOIN clause and with to the query using the Coupon relation
 * @method     ChildUserQuery rightJoinWithCoupon() Adds a RIGHT JOIN clause and with to the query using the Coupon relation
 * @method     ChildUserQuery innerJoinWithCoupon() Adds a INNER JOIN clause and with to the query using the Coupon relation
 *
 * @method     ChildUserQuery leftJoinCustomer($relationAlias = null) Adds a LEFT JOIN clause to the query using the Customer relation
 * @method     ChildUserQuery rightJoinCustomer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Customer relation
 * @method     ChildUserQuery innerJoinCustomer($relationAlias = null) Adds a INNER JOIN clause to the query using the Customer relation
 *
 * @method     ChildUserQuery joinWithCustomer($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Customer relation
 *
 * @method     ChildUserQuery leftJoinWithCustomer() Adds a LEFT JOIN clause and with to the query using the Customer relation
 * @method     ChildUserQuery rightJoinWithCustomer() Adds a RIGHT JOIN clause and with to the query using the Customer relation
 * @method     ChildUserQuery innerJoinWithCustomer() Adds a INNER JOIN clause and with to the query using the Customer relation
 *
 * @method     ChildUserQuery leftJoinDownload($relationAlias = null) Adds a LEFT JOIN clause to the query using the Download relation
 * @method     ChildUserQuery rightJoinDownload($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Download relation
 * @method     ChildUserQuery innerJoinDownload($relationAlias = null) Adds a INNER JOIN clause to the query using the Download relation
 *
 * @method     ChildUserQuery joinWithDownload($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Download relation
 *
 * @method     ChildUserQuery leftJoinWithDownload() Adds a LEFT JOIN clause and with to the query using the Download relation
 * @method     ChildUserQuery rightJoinWithDownload() Adds a RIGHT JOIN clause and with to the query using the Download relation
 * @method     ChildUserQuery innerJoinWithDownload() Adds a INNER JOIN clause and with to the query using the Download relation
 *
 * @method     ChildUserQuery leftJoinFile($relationAlias = null) Adds a LEFT JOIN clause to the query using the File relation
 * @method     ChildUserQuery rightJoinFile($relationAlias = null) Adds a RIGHT JOIN clause to the query using the File relation
 * @method     ChildUserQuery innerJoinFile($relationAlias = null) Adds a INNER JOIN clause to the query using the File relation
 *
 * @method     ChildUserQuery joinWithFile($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the File relation
 *
 * @method     ChildUserQuery leftJoinWithFile() Adds a LEFT JOIN clause and with to the query using the File relation
 * @method     ChildUserQuery rightJoinWithFile() Adds a RIGHT JOIN clause and with to the query using the File relation
 * @method     ChildUserQuery innerJoinWithFile() Adds a INNER JOIN clause and with to the query using the File relation
 *
 * @method     ChildUserQuery leftJoinLink($relationAlias = null) Adds a LEFT JOIN clause to the query using the Link relation
 * @method     ChildUserQuery rightJoinLink($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Link relation
 * @method     ChildUserQuery innerJoinLink($relationAlias = null) Adds a INNER JOIN clause to the query using the Link relation
 *
 * @method     ChildUserQuery joinWithLink($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Link relation
 *
 * @method     ChildUserQuery leftJoinWithLink() Adds a LEFT JOIN clause and with to the query using the Link relation
 * @method     ChildUserQuery rightJoinWithLink() Adds a RIGHT JOIN clause and with to the query using the Link relation
 * @method     ChildUserQuery innerJoinWithLink() Adds a INNER JOIN clause and with to the query using the Link relation
 *
 * @method     ChildUserQuery leftJoinStockItemList($relationAlias = null) Adds a LEFT JOIN clause to the query using the StockItemList relation
 * @method     ChildUserQuery rightJoinStockItemList($relationAlias = null) Adds a RIGHT JOIN clause to the query using the StockItemList relation
 * @method     ChildUserQuery innerJoinStockItemList($relationAlias = null) Adds a INNER JOIN clause to the query using the StockItemList relation
 *
 * @method     ChildUserQuery joinWithStockItemList($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the StockItemList relation
 *
 * @method     ChildUserQuery leftJoinWithStockItemList() Adds a LEFT JOIN clause and with to the query using the StockItemList relation
 * @method     ChildUserQuery rightJoinWithStockItemList() Adds a RIGHT JOIN clause and with to the query using the StockItemList relation
 * @method     ChildUserQuery innerJoinWithStockItemList() Adds a INNER JOIN clause and with to the query using the StockItemList relation
 *
 * @method     ChildUserQuery leftJoinOption($relationAlias = null) Adds a LEFT JOIN clause to the query using the Option relation
 * @method     ChildUserQuery rightJoinOption($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Option relation
 * @method     ChildUserQuery innerJoinOption($relationAlias = null) Adds a INNER JOIN clause to the query using the Option relation
 *
 * @method     ChildUserQuery joinWithOption($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Option relation
 *
 * @method     ChildUserQuery leftJoinWithOption() Adds a LEFT JOIN clause and with to the query using the Option relation
 * @method     ChildUserQuery rightJoinWithOption() Adds a RIGHT JOIN clause and with to the query using the Option relation
 * @method     ChildUserQuery innerJoinWithOption() Adds a INNER JOIN clause and with to the query using the Option relation
 *
 * @method     ChildUserQuery leftJoinOrder($relationAlias = null) Adds a LEFT JOIN clause to the query using the Order relation
 * @method     ChildUserQuery rightJoinOrder($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Order relation
 * @method     ChildUserQuery innerJoinOrder($relationAlias = null) Adds a INNER JOIN clause to the query using the Order relation
 *
 * @method     ChildUserQuery joinWithOrder($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Order relation
 *
 * @method     ChildUserQuery leftJoinWithOrder() Adds a LEFT JOIN clause and with to the query using the Order relation
 * @method     ChildUserQuery rightJoinWithOrder() Adds a RIGHT JOIN clause and with to the query using the Order relation
 * @method     ChildUserQuery innerJoinWithOrder() Adds a INNER JOIN clause and with to the query using the Order relation
 *
 * @method     ChildUserQuery leftJoinPermission($relationAlias = null) Adds a LEFT JOIN clause to the query using the Permission relation
 * @method     ChildUserQuery rightJoinPermission($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Permission relation
 * @method     ChildUserQuery innerJoinPermission($relationAlias = null) Adds a INNER JOIN clause to the query using the Permission relation
 *
 * @method     ChildUserQuery joinWithPermission($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Permission relation
 *
 * @method     ChildUserQuery leftJoinWithPermission() Adds a LEFT JOIN clause and with to the query using the Permission relation
 * @method     ChildUserQuery rightJoinWithPermission() Adds a RIGHT JOIN clause and with to the query using the Permission relation
 * @method     ChildUserQuery innerJoinWithPermission() Adds a INNER JOIN clause and with to the query using the Permission relation
 *
 * @method     ChildUserQuery leftJoinPost($relationAlias = null) Adds a LEFT JOIN clause to the query using the Post relation
 * @method     ChildUserQuery rightJoinPost($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Post relation
 * @method     ChildUserQuery innerJoinPost($relationAlias = null) Adds a INNER JOIN clause to the query using the Post relation
 *
 * @method     ChildUserQuery joinWithPost($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Post relation
 *
 * @method     ChildUserQuery leftJoinWithPost() Adds a LEFT JOIN clause and with to the query using the Post relation
 * @method     ChildUserQuery rightJoinWithPost() Adds a RIGHT JOIN clause and with to the query using the Post relation
 * @method     ChildUserQuery innerJoinWithPost() Adds a INNER JOIN clause and with to the query using the Post relation
 *
 * @method     ChildUserQuery leftJoinRight($relationAlias = null) Adds a LEFT JOIN clause to the query using the Right relation
 * @method     ChildUserQuery rightJoinRight($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Right relation
 * @method     ChildUserQuery innerJoinRight($relationAlias = null) Adds a INNER JOIN clause to the query using the Right relation
 *
 * @method     ChildUserQuery joinWithRight($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Right relation
 *
 * @method     ChildUserQuery leftJoinWithRight() Adds a LEFT JOIN clause and with to the query using the Right relation
 * @method     ChildUserQuery rightJoinWithRight() Adds a RIGHT JOIN clause and with to the query using the Right relation
 * @method     ChildUserQuery innerJoinWithRight() Adds a INNER JOIN clause and with to the query using the Right relation
 *
 * @method     ChildUserQuery leftJoinRole($relationAlias = null) Adds a LEFT JOIN clause to the query using the Role relation
 * @method     ChildUserQuery rightJoinRole($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Role relation
 * @method     ChildUserQuery innerJoinRole($relationAlias = null) Adds a INNER JOIN clause to the query using the Role relation
 *
 * @method     ChildUserQuery joinWithRole($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Role relation
 *
 * @method     ChildUserQuery leftJoinWithRole() Adds a LEFT JOIN clause and with to the query using the Role relation
 * @method     ChildUserQuery rightJoinWithRole() Adds a RIGHT JOIN clause and with to the query using the Role relation
 * @method     ChildUserQuery innerJoinWithRole() Adds a INNER JOIN clause and with to the query using the Role relation
 *
 * @method     ChildUserQuery leftJoinSession($relationAlias = null) Adds a LEFT JOIN clause to the query using the Session relation
 * @method     ChildUserQuery rightJoinSession($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Session relation
 * @method     ChildUserQuery innerJoinSession($relationAlias = null) Adds a INNER JOIN clause to the query using the Session relation
 *
 * @method     ChildUserQuery joinWithSession($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Session relation
 *
 * @method     ChildUserQuery leftJoinWithSession() Adds a LEFT JOIN clause and with to the query using the Session relation
 * @method     ChildUserQuery rightJoinWithSession() Adds a RIGHT JOIN clause and with to the query using the Session relation
 * @method     ChildUserQuery innerJoinWithSession() Adds a INNER JOIN clause and with to the query using the Session relation
 *
 * @method     ChildUserQuery leftJoinStock($relationAlias = null) Adds a LEFT JOIN clause to the query using the Stock relation
 * @method     ChildUserQuery rightJoinStock($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Stock relation
 * @method     ChildUserQuery innerJoinStock($relationAlias = null) Adds a INNER JOIN clause to the query using the Stock relation
 *
 * @method     ChildUserQuery joinWithStock($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Stock relation
 *
 * @method     ChildUserQuery leftJoinWithStock() Adds a LEFT JOIN clause and with to the query using the Stock relation
 * @method     ChildUserQuery rightJoinWithStock() Adds a RIGHT JOIN clause and with to the query using the Stock relation
 * @method     ChildUserQuery innerJoinWithStock() Adds a INNER JOIN clause and with to the query using the Stock relation
 *
 * @method     ChildUserQuery leftJoinSubscription($relationAlias = null) Adds a LEFT JOIN clause to the query using the Subscription relation
 * @method     ChildUserQuery rightJoinSubscription($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Subscription relation
 * @method     ChildUserQuery innerJoinSubscription($relationAlias = null) Adds a INNER JOIN clause to the query using the Subscription relation
 *
 * @method     ChildUserQuery joinWithSubscription($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Subscription relation
 *
 * @method     ChildUserQuery leftJoinWithSubscription() Adds a LEFT JOIN clause and with to the query using the Subscription relation
 * @method     ChildUserQuery rightJoinWithSubscription() Adds a RIGHT JOIN clause and with to the query using the Subscription relation
 * @method     ChildUserQuery innerJoinWithSubscription() Adds a INNER JOIN clause and with to the query using the Subscription relation
 *
 * @method     ChildUserQuery leftJoinAuthenticationMethod($relationAlias = null) Adds a LEFT JOIN clause to the query using the AuthenticationMethod relation
 * @method     ChildUserQuery rightJoinAuthenticationMethod($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AuthenticationMethod relation
 * @method     ChildUserQuery innerJoinAuthenticationMethod($relationAlias = null) Adds a INNER JOIN clause to the query using the AuthenticationMethod relation
 *
 * @method     ChildUserQuery joinWithAuthenticationMethod($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the AuthenticationMethod relation
 *
 * @method     ChildUserQuery leftJoinWithAuthenticationMethod() Adds a LEFT JOIN clause and with to the query using the AuthenticationMethod relation
 * @method     ChildUserQuery rightJoinWithAuthenticationMethod() Adds a RIGHT JOIN clause and with to the query using the AuthenticationMethod relation
 * @method     ChildUserQuery innerJoinWithAuthenticationMethod() Adds a INNER JOIN clause and with to the query using the AuthenticationMethod relation
 *
 * @method     ChildUserQuery leftJoinVote($relationAlias = null) Adds a LEFT JOIN clause to the query using the Vote relation
 * @method     ChildUserQuery rightJoinVote($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Vote relation
 * @method     ChildUserQuery innerJoinVote($relationAlias = null) Adds a INNER JOIN clause to the query using the Vote relation
 *
 * @method     ChildUserQuery joinWithVote($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Vote relation
 *
 * @method     ChildUserQuery leftJoinWithVote() Adds a LEFT JOIN clause and with to the query using the Vote relation
 * @method     ChildUserQuery rightJoinWithVote() Adds a RIGHT JOIN clause and with to the query using the Vote relation
 * @method     ChildUserQuery innerJoinWithVote() Adds a INNER JOIN clause and with to the query using the Vote relation
 *
 * @method     ChildUserQuery leftJoinWish($relationAlias = null) Adds a LEFT JOIN clause to the query using the Wish relation
 * @method     ChildUserQuery rightJoinWish($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Wish relation
 * @method     ChildUserQuery innerJoinWish($relationAlias = null) Adds a INNER JOIN clause to the query using the Wish relation
 *
 * @method     ChildUserQuery joinWithWish($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Wish relation
 *
 * @method     ChildUserQuery leftJoinWithWish() Adds a LEFT JOIN clause and with to the query using the Wish relation
 * @method     ChildUserQuery rightJoinWithWish() Adds a RIGHT JOIN clause and with to the query using the Wish relation
 * @method     ChildUserQuery innerJoinWithWish() Adds a INNER JOIN clause and with to the query using the Wish relation
 *
 * @method     ChildUserQuery leftJoinWishlist($relationAlias = null) Adds a LEFT JOIN clause to the query using the Wishlist relation
 * @method     ChildUserQuery rightJoinWishlist($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Wishlist relation
 * @method     ChildUserQuery innerJoinWishlist($relationAlias = null) Adds a INNER JOIN clause to the query using the Wishlist relation
 *
 * @method     ChildUserQuery joinWithWishlist($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Wishlist relation
 *
 * @method     ChildUserQuery leftJoinWithWishlist() Adds a LEFT JOIN clause and with to the query using the Wishlist relation
 * @method     ChildUserQuery rightJoinWithWishlist() Adds a RIGHT JOIN clause and with to the query using the Wishlist relation
 * @method     ChildUserQuery innerJoinWithWishlist() Adds a INNER JOIN clause and with to the query using the Wishlist relation
 *
 * @method     \Model\SiteQuery|\Model\AlertQuery|\Model\CartQuery|\Model\CouponQuery|\Model\CustomerQuery|\Model\DownloadQuery|\Model\FileQuery|\Model\LinkQuery|\Model\StockItemListQuery|\Model\OptionQuery|\Model\OrderQuery|\Model\PermissionQuery|\Model\PostQuery|\Model\RightQuery|\Model\RoleQuery|\Model\SessionQuery|\Model\StockQuery|\Model\SubscriptionQuery|\Model\AuthenticationMethodQuery|\Model\VoteQuery|\Model\WishQuery|\Model\WishlistQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildUser|null findOne(?ConnectionInterface $con = null) Return the first ChildUser matching the query
 * @method     ChildUser findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildUser matching the query, or a new ChildUser object populated from the query conditions when no match is found
 *
 * @method     ChildUser|null findOneById(int $id) Return the first ChildUser filtered by the id column
 * @method     ChildUser|null findOneBySiteId(int $site_id) Return the first ChildUser filtered by the site_id column
 * @method     ChildUser|null findOneByEmail(string $email) Return the first ChildUser filtered by the email column
 * @method     ChildUser|null findOneByLastLoggedAt(string $lastLoggedAt) Return the first ChildUser filtered by the lastLoggedAt column
 * @method     ChildUser|null findOneByCreatedAt(string $created_at) Return the first ChildUser filtered by the created_at column
 * @method     ChildUser|null findOneByUpdatedAt(string $updated_at) Return the first ChildUser filtered by the updated_at column
 *
 * @method     ChildUser requirePk($key, ?ConnectionInterface $con = null) Return the ChildUser by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOne(?ConnectionInterface $con = null) Return the first ChildUser matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUser requireOneById(int $id) Return the first ChildUser filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneBySiteId(int $site_id) Return the first ChildUser filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByEmail(string $email) Return the first ChildUser filtered by the email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByLastLoggedAt(string $lastLoggedAt) Return the first ChildUser filtered by the lastLoggedAt column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByCreatedAt(string $created_at) Return the first ChildUser filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByUpdatedAt(string $updated_at) Return the first ChildUser filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUser[]|Collection find(?ConnectionInterface $con = null) Return ChildUser objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildUser> find(?ConnectionInterface $con = null) Return ChildUser objects based on current ModelCriteria
 *
 * @method     ChildUser[]|Collection findById(int|array<int> $id) Return ChildUser objects filtered by the id column
 * @psalm-method Collection&\Traversable<ChildUser> findById(int|array<int> $id) Return ChildUser objects filtered by the id column
 * @method     ChildUser[]|Collection findBySiteId(int|array<int> $site_id) Return ChildUser objects filtered by the site_id column
 * @psalm-method Collection&\Traversable<ChildUser> findBySiteId(int|array<int> $site_id) Return ChildUser objects filtered by the site_id column
 * @method     ChildUser[]|Collection findByEmail(string|array<string> $email) Return ChildUser objects filtered by the email column
 * @psalm-method Collection&\Traversable<ChildUser> findByEmail(string|array<string> $email) Return ChildUser objects filtered by the email column
 * @method     ChildUser[]|Collection findByLastLoggedAt(string|array<string> $lastLoggedAt) Return ChildUser objects filtered by the lastLoggedAt column
 * @psalm-method Collection&\Traversable<ChildUser> findByLastLoggedAt(string|array<string> $lastLoggedAt) Return ChildUser objects filtered by the lastLoggedAt column
 * @method     ChildUser[]|Collection findByCreatedAt(string|array<string> $created_at) Return ChildUser objects filtered by the created_at column
 * @psalm-method Collection&\Traversable<ChildUser> findByCreatedAt(string|array<string> $created_at) Return ChildUser objects filtered by the created_at column
 * @method     ChildUser[]|Collection findByUpdatedAt(string|array<string> $updated_at) Return ChildUser objects filtered by the updated_at column
 * @psalm-method Collection&\Traversable<ChildUser> findByUpdatedAt(string|array<string> $updated_at) Return ChildUser objects filtered by the updated_at column
 *
 * @method     ChildUser[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildUser> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class UserQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\UserQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\User', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUserQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildUserQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildUserQuery) {
            return $criteria;
        }
        $query = new ChildUserQuery();
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
     * @return ChildUser|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = UserTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildUser A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, site_id, email, lastLoggedAt, created_at, updated_at FROM users WHERE id = :p0';
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
            /** @var ChildUser $obj */
            $obj = new ChildUser();
            $obj->hydrate($row);
            UserTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildUser|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(UserTableMap::COL_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(UserTableMap::COL_ID, $keys, Criteria::IN);

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
                $this->addUsingAlias(UserTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(UserTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_ID, $id, $comparison);

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
                $this->addUsingAlias(UserTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(UserTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_SITE_ID, $siteId, $comparison);

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

        $this->addUsingAlias(UserTableMap::COL_EMAIL, $email, $comparison);

        return $this;
    }

    /**
     * Filter the query on the lastLoggedAt column
     *
     * Example usage:
     * <code>
     * $query->filterByLastLoggedAt('2011-03-14'); // WHERE lastLoggedAt = '2011-03-14'
     * $query->filterByLastLoggedAt('now'); // WHERE lastLoggedAt = '2011-03-14'
     * $query->filterByLastLoggedAt(array('max' => 'yesterday')); // WHERE lastLoggedAt > '2011-03-13'
     * </code>
     *
     * @param mixed $lastLoggedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByLastLoggedAt($lastLoggedAt = null, ?string $comparison = null)
    {
        if (is_array($lastLoggedAt)) {
            $useMinMax = false;
            if (isset($lastLoggedAt['min'])) {
                $this->addUsingAlias(UserTableMap::COL_LASTLOGGEDAT, $lastLoggedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lastLoggedAt['max'])) {
                $this->addUsingAlias(UserTableMap::COL_LASTLOGGEDAT, $lastLoggedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_LASTLOGGEDAT, $lastLoggedAt, $comparison);

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
                $this->addUsingAlias(UserTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(UserTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_CREATED_AT, $createdAt, $comparison);

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
                $this->addUsingAlias(UserTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(UserTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_UPDATED_AT, $updatedAt, $comparison);

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
                ->addUsingAlias(UserTableMap::COL_SITE_ID, $site->getId(), $comparison);
        } elseif ($site instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(UserTableMap::COL_SITE_ID, $site->toKeyValue('PrimaryKey', 'Id'), $comparison);

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
     * Filter the query by a related \Model\Alert object
     *
     * @param \Model\Alert|ObjectCollection $alert the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAlert($alert, ?string $comparison = null)
    {
        if ($alert instanceof \Model\Alert) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $alert->getUserId(), $comparison);

            return $this;
        } elseif ($alert instanceof ObjectCollection) {
            $this
                ->useAlertQuery()
                ->filterByPrimaryKeys($alert->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByAlert() only accepts arguments of type \Model\Alert or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Alert relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinAlert(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Alert');

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
            $this->addJoinObject($join, 'Alert');
        }

        return $this;
    }

    /**
     * Use the Alert relation Alert object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\AlertQuery A secondary query class using the current class as primary query
     */
    public function useAlertQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinAlert($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Alert', '\Model\AlertQuery');
    }

    /**
     * Use the Alert relation Alert object
     *
     * @param callable(\Model\AlertQuery):\Model\AlertQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withAlertQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useAlertQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Alert table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\AlertQuery The inner query object of the EXISTS statement
     */
    public function useAlertExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\AlertQuery */
        $q = $this->useExistsQuery('Alert', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Alert table for a NOT EXISTS query.
     *
     * @see useAlertExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\AlertQuery The inner query object of the NOT EXISTS statement
     */
    public function useAlertNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\AlertQuery */
        $q = $this->useExistsQuery('Alert', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Alert table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\AlertQuery The inner query object of the IN statement
     */
    public function useInAlertQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\AlertQuery */
        $q = $this->useInQuery('Alert', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Alert table for a NOT IN query.
     *
     * @see useAlertInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\AlertQuery The inner query object of the NOT IN statement
     */
    public function useNotInAlertQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\AlertQuery */
        $q = $this->useInQuery('Alert', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Cart object
     *
     * @param \Model\Cart|ObjectCollection $cart the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCart($cart, ?string $comparison = null)
    {
        if ($cart instanceof \Model\Cart) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $cart->getUserId(), $comparison);

            return $this;
        } elseif ($cart instanceof ObjectCollection) {
            $this
                ->useCartQuery()
                ->filterByPrimaryKeys($cart->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByCart() only accepts arguments of type \Model\Cart or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Cart relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinCart(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Cart');

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
            $this->addJoinObject($join, 'Cart');
        }

        return $this;
    }

    /**
     * Use the Cart relation Cart object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\CartQuery A secondary query class using the current class as primary query
     */
    public function useCartQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCart($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Cart', '\Model\CartQuery');
    }

    /**
     * Use the Cart relation Cart object
     *
     * @param callable(\Model\CartQuery):\Model\CartQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withCartQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useCartQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Cart table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\CartQuery The inner query object of the EXISTS statement
     */
    public function useCartExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\CartQuery */
        $q = $this->useExistsQuery('Cart', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Cart table for a NOT EXISTS query.
     *
     * @see useCartExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\CartQuery The inner query object of the NOT EXISTS statement
     */
    public function useCartNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\CartQuery */
        $q = $this->useExistsQuery('Cart', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Cart table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\CartQuery The inner query object of the IN statement
     */
    public function useInCartQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\CartQuery */
        $q = $this->useInQuery('Cart', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Cart table for a NOT IN query.
     *
     * @see useCartInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\CartQuery The inner query object of the NOT IN statement
     */
    public function useNotInCartQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\CartQuery */
        $q = $this->useInQuery('Cart', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Coupon object
     *
     * @param \Model\Coupon|ObjectCollection $coupon the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCoupon($coupon, ?string $comparison = null)
    {
        if ($coupon instanceof \Model\Coupon) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $coupon->getUserId(), $comparison);

            return $this;
        } elseif ($coupon instanceof ObjectCollection) {
            $this
                ->useCouponQuery()
                ->filterByPrimaryKeys($coupon->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByCoupon() only accepts arguments of type \Model\Coupon or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Coupon relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinCoupon(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Coupon');

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
            $this->addJoinObject($join, 'Coupon');
        }

        return $this;
    }

    /**
     * Use the Coupon relation Coupon object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\CouponQuery A secondary query class using the current class as primary query
     */
    public function useCouponQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCoupon($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Coupon', '\Model\CouponQuery');
    }

    /**
     * Use the Coupon relation Coupon object
     *
     * @param callable(\Model\CouponQuery):\Model\CouponQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withCouponQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useCouponQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Coupon table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\CouponQuery The inner query object of the EXISTS statement
     */
    public function useCouponExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\CouponQuery */
        $q = $this->useExistsQuery('Coupon', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Coupon table for a NOT EXISTS query.
     *
     * @see useCouponExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\CouponQuery The inner query object of the NOT EXISTS statement
     */
    public function useCouponNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\CouponQuery */
        $q = $this->useExistsQuery('Coupon', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Coupon table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\CouponQuery The inner query object of the IN statement
     */
    public function useInCouponQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\CouponQuery */
        $q = $this->useInQuery('Coupon', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Coupon table for a NOT IN query.
     *
     * @see useCouponInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\CouponQuery The inner query object of the NOT IN statement
     */
    public function useNotInCouponQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\CouponQuery */
        $q = $this->useInQuery('Coupon', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Customer object
     *
     * @param \Model\Customer|ObjectCollection $customer the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCustomer($customer, ?string $comparison = null)
    {
        if ($customer instanceof \Model\Customer) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $customer->getUserId(), $comparison);

            return $this;
        } elseif ($customer instanceof ObjectCollection) {
            $this
                ->useCustomerQuery()
                ->filterByPrimaryKeys($customer->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByCustomer() only accepts arguments of type \Model\Customer or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Customer relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinCustomer(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Customer');

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
            $this->addJoinObject($join, 'Customer');
        }

        return $this;
    }

    /**
     * Use the Customer relation Customer object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\CustomerQuery A secondary query class using the current class as primary query
     */
    public function useCustomerQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCustomer($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Customer', '\Model\CustomerQuery');
    }

    /**
     * Use the Customer relation Customer object
     *
     * @param callable(\Model\CustomerQuery):\Model\CustomerQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withCustomerQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useCustomerQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Customer table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\CustomerQuery The inner query object of the EXISTS statement
     */
    public function useCustomerExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\CustomerQuery */
        $q = $this->useExistsQuery('Customer', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Customer table for a NOT EXISTS query.
     *
     * @see useCustomerExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\CustomerQuery The inner query object of the NOT EXISTS statement
     */
    public function useCustomerNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\CustomerQuery */
        $q = $this->useExistsQuery('Customer', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Customer table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\CustomerQuery The inner query object of the IN statement
     */
    public function useInCustomerQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\CustomerQuery */
        $q = $this->useInQuery('Customer', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Customer table for a NOT IN query.
     *
     * @see useCustomerInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\CustomerQuery The inner query object of the NOT IN statement
     */
    public function useNotInCustomerQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\CustomerQuery */
        $q = $this->useInQuery('Customer', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Download object
     *
     * @param \Model\Download|ObjectCollection $download the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDownload($download, ?string $comparison = null)
    {
        if ($download instanceof \Model\Download) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $download->getUserId(), $comparison);

            return $this;
        } elseif ($download instanceof ObjectCollection) {
            $this
                ->useDownloadQuery()
                ->filterByPrimaryKeys($download->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByDownload() only accepts arguments of type \Model\Download or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Download relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinDownload(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Download');

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
            $this->addJoinObject($join, 'Download');
        }

        return $this;
    }

    /**
     * Use the Download relation Download object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\DownloadQuery A secondary query class using the current class as primary query
     */
    public function useDownloadQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDownload($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Download', '\Model\DownloadQuery');
    }

    /**
     * Use the Download relation Download object
     *
     * @param callable(\Model\DownloadQuery):\Model\DownloadQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withDownloadQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useDownloadQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Download table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\DownloadQuery The inner query object of the EXISTS statement
     */
    public function useDownloadExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\DownloadQuery */
        $q = $this->useExistsQuery('Download', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Download table for a NOT EXISTS query.
     *
     * @see useDownloadExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\DownloadQuery The inner query object of the NOT EXISTS statement
     */
    public function useDownloadNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\DownloadQuery */
        $q = $this->useExistsQuery('Download', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Download table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\DownloadQuery The inner query object of the IN statement
     */
    public function useInDownloadQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\DownloadQuery */
        $q = $this->useInQuery('Download', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Download table for a NOT IN query.
     *
     * @see useDownloadInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\DownloadQuery The inner query object of the NOT IN statement
     */
    public function useNotInDownloadQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\DownloadQuery */
        $q = $this->useInQuery('Download', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\File object
     *
     * @param \Model\File|ObjectCollection $file the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFile($file, ?string $comparison = null)
    {
        if ($file instanceof \Model\File) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $file->getUserId(), $comparison);

            return $this;
        } elseif ($file instanceof ObjectCollection) {
            $this
                ->useFileQuery()
                ->filterByPrimaryKeys($file->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByFile() only accepts arguments of type \Model\File or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the File relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinFile(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('File');

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
            $this->addJoinObject($join, 'File');
        }

        return $this;
    }

    /**
     * Use the File relation File object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\FileQuery A secondary query class using the current class as primary query
     */
    public function useFileQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinFile($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'File', '\Model\FileQuery');
    }

    /**
     * Use the File relation File object
     *
     * @param callable(\Model\FileQuery):\Model\FileQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withFileQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useFileQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to File table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\FileQuery The inner query object of the EXISTS statement
     */
    public function useFileExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\FileQuery */
        $q = $this->useExistsQuery('File', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to File table for a NOT EXISTS query.
     *
     * @see useFileExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\FileQuery The inner query object of the NOT EXISTS statement
     */
    public function useFileNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\FileQuery */
        $q = $this->useExistsQuery('File', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to File table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\FileQuery The inner query object of the IN statement
     */
    public function useInFileQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\FileQuery */
        $q = $this->useInQuery('File', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to File table for a NOT IN query.
     *
     * @see useFileInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\FileQuery The inner query object of the NOT IN statement
     */
    public function useNotInFileQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\FileQuery */
        $q = $this->useInQuery('File', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Link object
     *
     * @param \Model\Link|ObjectCollection $link the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByLink($link, ?string $comparison = null)
    {
        if ($link instanceof \Model\Link) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $link->getUserId(), $comparison);

            return $this;
        } elseif ($link instanceof ObjectCollection) {
            $this
                ->useLinkQuery()
                ->filterByPrimaryKeys($link->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByLink() only accepts arguments of type \Model\Link or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Link relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinLink(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Link');

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
            $this->addJoinObject($join, 'Link');
        }

        return $this;
    }

    /**
     * Use the Link relation Link object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\LinkQuery A secondary query class using the current class as primary query
     */
    public function useLinkQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinLink($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Link', '\Model\LinkQuery');
    }

    /**
     * Use the Link relation Link object
     *
     * @param callable(\Model\LinkQuery):\Model\LinkQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withLinkQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useLinkQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Link table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\LinkQuery The inner query object of the EXISTS statement
     */
    public function useLinkExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\LinkQuery */
        $q = $this->useExistsQuery('Link', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Link table for a NOT EXISTS query.
     *
     * @see useLinkExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\LinkQuery The inner query object of the NOT EXISTS statement
     */
    public function useLinkNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\LinkQuery */
        $q = $this->useExistsQuery('Link', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Link table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\LinkQuery The inner query object of the IN statement
     */
    public function useInLinkQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\LinkQuery */
        $q = $this->useInQuery('Link', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Link table for a NOT IN query.
     *
     * @see useLinkInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\LinkQuery The inner query object of the NOT IN statement
     */
    public function useNotInLinkQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\LinkQuery */
        $q = $this->useInQuery('Link', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\StockItemList object
     *
     * @param \Model\StockItemList|ObjectCollection $stockItemList the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByStockItemList($stockItemList, ?string $comparison = null)
    {
        if ($stockItemList instanceof \Model\StockItemList) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $stockItemList->getUserId(), $comparison);

            return $this;
        } elseif ($stockItemList instanceof ObjectCollection) {
            $this
                ->useStockItemListQuery()
                ->filterByPrimaryKeys($stockItemList->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByStockItemList() only accepts arguments of type \Model\StockItemList or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the StockItemList relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinStockItemList(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('StockItemList');

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
            $this->addJoinObject($join, 'StockItemList');
        }

        return $this;
    }

    /**
     * Use the StockItemList relation StockItemList object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\StockItemListQuery A secondary query class using the current class as primary query
     */
    public function useStockItemListQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinStockItemList($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'StockItemList', '\Model\StockItemListQuery');
    }

    /**
     * Use the StockItemList relation StockItemList object
     *
     * @param callable(\Model\StockItemListQuery):\Model\StockItemListQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withStockItemListQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useStockItemListQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to StockItemList table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\StockItemListQuery The inner query object of the EXISTS statement
     */
    public function useStockItemListExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\StockItemListQuery */
        $q = $this->useExistsQuery('StockItemList', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to StockItemList table for a NOT EXISTS query.
     *
     * @see useStockItemListExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\StockItemListQuery The inner query object of the NOT EXISTS statement
     */
    public function useStockItemListNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\StockItemListQuery */
        $q = $this->useExistsQuery('StockItemList', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to StockItemList table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\StockItemListQuery The inner query object of the IN statement
     */
    public function useInStockItemListQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\StockItemListQuery */
        $q = $this->useInQuery('StockItemList', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to StockItemList table for a NOT IN query.
     *
     * @see useStockItemListInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\StockItemListQuery The inner query object of the NOT IN statement
     */
    public function useNotInStockItemListQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\StockItemListQuery */
        $q = $this->useInQuery('StockItemList', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Option object
     *
     * @param \Model\Option|ObjectCollection $option the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByOption($option, ?string $comparison = null)
    {
        if ($option instanceof \Model\Option) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $option->getUserId(), $comparison);

            return $this;
        } elseif ($option instanceof ObjectCollection) {
            $this
                ->useOptionQuery()
                ->filterByPrimaryKeys($option->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByOption() only accepts arguments of type \Model\Option or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Option relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinOption(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Option');

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
            $this->addJoinObject($join, 'Option');
        }

        return $this;
    }

    /**
     * Use the Option relation Option object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\OptionQuery A secondary query class using the current class as primary query
     */
    public function useOptionQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinOption($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Option', '\Model\OptionQuery');
    }

    /**
     * Use the Option relation Option object
     *
     * @param callable(\Model\OptionQuery):\Model\OptionQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withOptionQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useOptionQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Option table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\OptionQuery The inner query object of the EXISTS statement
     */
    public function useOptionExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\OptionQuery */
        $q = $this->useExistsQuery('Option', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Option table for a NOT EXISTS query.
     *
     * @see useOptionExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\OptionQuery The inner query object of the NOT EXISTS statement
     */
    public function useOptionNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\OptionQuery */
        $q = $this->useExistsQuery('Option', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Option table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\OptionQuery The inner query object of the IN statement
     */
    public function useInOptionQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\OptionQuery */
        $q = $this->useInQuery('Option', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Option table for a NOT IN query.
     *
     * @see useOptionInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\OptionQuery The inner query object of the NOT IN statement
     */
    public function useNotInOptionQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\OptionQuery */
        $q = $this->useInQuery('Option', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Order object
     *
     * @param \Model\Order|ObjectCollection $order the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByOrder($order, ?string $comparison = null)
    {
        if ($order instanceof \Model\Order) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $order->getUserId(), $comparison);

            return $this;
        } elseif ($order instanceof ObjectCollection) {
            $this
                ->useOrderQuery()
                ->filterByPrimaryKeys($order->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByOrder() only accepts arguments of type \Model\Order or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Order relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinOrder(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Order');

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
            $this->addJoinObject($join, 'Order');
        }

        return $this;
    }

    /**
     * Use the Order relation Order object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\OrderQuery A secondary query class using the current class as primary query
     */
    public function useOrderQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinOrder($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Order', '\Model\OrderQuery');
    }

    /**
     * Use the Order relation Order object
     *
     * @param callable(\Model\OrderQuery):\Model\OrderQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withOrderQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useOrderQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Order table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\OrderQuery The inner query object of the EXISTS statement
     */
    public function useOrderExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\OrderQuery */
        $q = $this->useExistsQuery('Order', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Order table for a NOT EXISTS query.
     *
     * @see useOrderExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\OrderQuery The inner query object of the NOT EXISTS statement
     */
    public function useOrderNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\OrderQuery */
        $q = $this->useExistsQuery('Order', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Order table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\OrderQuery The inner query object of the IN statement
     */
    public function useInOrderQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\OrderQuery */
        $q = $this->useInQuery('Order', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Order table for a NOT IN query.
     *
     * @see useOrderInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\OrderQuery The inner query object of the NOT IN statement
     */
    public function useNotInOrderQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\OrderQuery */
        $q = $this->useInQuery('Order', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Permission object
     *
     * @param \Model\Permission|ObjectCollection $permission the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPermission($permission, ?string $comparison = null)
    {
        if ($permission instanceof \Model\Permission) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $permission->getUserId(), $comparison);

            return $this;
        } elseif ($permission instanceof ObjectCollection) {
            $this
                ->usePermissionQuery()
                ->filterByPrimaryKeys($permission->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByPermission() only accepts arguments of type \Model\Permission or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Permission relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinPermission(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Permission');

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
            $this->addJoinObject($join, 'Permission');
        }

        return $this;
    }

    /**
     * Use the Permission relation Permission object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\PermissionQuery A secondary query class using the current class as primary query
     */
    public function usePermissionQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPermission($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Permission', '\Model\PermissionQuery');
    }

    /**
     * Use the Permission relation Permission object
     *
     * @param callable(\Model\PermissionQuery):\Model\PermissionQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withPermissionQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->usePermissionQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Permission table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\PermissionQuery The inner query object of the EXISTS statement
     */
    public function usePermissionExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\PermissionQuery */
        $q = $this->useExistsQuery('Permission', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Permission table for a NOT EXISTS query.
     *
     * @see usePermissionExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\PermissionQuery The inner query object of the NOT EXISTS statement
     */
    public function usePermissionNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\PermissionQuery */
        $q = $this->useExistsQuery('Permission', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Permission table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\PermissionQuery The inner query object of the IN statement
     */
    public function useInPermissionQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\PermissionQuery */
        $q = $this->useInQuery('Permission', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Permission table for a NOT IN query.
     *
     * @see usePermissionInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\PermissionQuery The inner query object of the NOT IN statement
     */
    public function useNotInPermissionQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\PermissionQuery */
        $q = $this->useInQuery('Permission', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Post object
     *
     * @param \Model\Post|ObjectCollection $post the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPost($post, ?string $comparison = null)
    {
        if ($post instanceof \Model\Post) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $post->getUserId(), $comparison);

            return $this;
        } elseif ($post instanceof ObjectCollection) {
            $this
                ->usePostQuery()
                ->filterByPrimaryKeys($post->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByPost() only accepts arguments of type \Model\Post or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Post relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinPost(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Post');

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
            $this->addJoinObject($join, 'Post');
        }

        return $this;
    }

    /**
     * Use the Post relation Post object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\PostQuery A secondary query class using the current class as primary query
     */
    public function usePostQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPost($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Post', '\Model\PostQuery');
    }

    /**
     * Use the Post relation Post object
     *
     * @param callable(\Model\PostQuery):\Model\PostQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withPostQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->usePostQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Post table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\PostQuery The inner query object of the EXISTS statement
     */
    public function usePostExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\PostQuery */
        $q = $this->useExistsQuery('Post', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Post table for a NOT EXISTS query.
     *
     * @see usePostExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\PostQuery The inner query object of the NOT EXISTS statement
     */
    public function usePostNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\PostQuery */
        $q = $this->useExistsQuery('Post', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Post table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\PostQuery The inner query object of the IN statement
     */
    public function useInPostQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\PostQuery */
        $q = $this->useInQuery('Post', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Post table for a NOT IN query.
     *
     * @see usePostInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\PostQuery The inner query object of the NOT IN statement
     */
    public function useNotInPostQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\PostQuery */
        $q = $this->useInQuery('Post', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Right object
     *
     * @param \Model\Right|ObjectCollection $right the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByRight($right, ?string $comparison = null)
    {
        if ($right instanceof \Model\Right) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $right->getUserId(), $comparison);

            return $this;
        } elseif ($right instanceof ObjectCollection) {
            $this
                ->useRightQuery()
                ->filterByPrimaryKeys($right->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByRight() only accepts arguments of type \Model\Right or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Right relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinRight(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Right');

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
            $this->addJoinObject($join, 'Right');
        }

        return $this;
    }

    /**
     * Use the Right relation Right object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\RightQuery A secondary query class using the current class as primary query
     */
    public function useRightQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinRight($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Right', '\Model\RightQuery');
    }

    /**
     * Use the Right relation Right object
     *
     * @param callable(\Model\RightQuery):\Model\RightQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withRightQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useRightQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Right table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\RightQuery The inner query object of the EXISTS statement
     */
    public function useRightExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\RightQuery */
        $q = $this->useExistsQuery('Right', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Right table for a NOT EXISTS query.
     *
     * @see useRightExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\RightQuery The inner query object of the NOT EXISTS statement
     */
    public function useRightNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\RightQuery */
        $q = $this->useExistsQuery('Right', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Right table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\RightQuery The inner query object of the IN statement
     */
    public function useInRightQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\RightQuery */
        $q = $this->useInQuery('Right', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Right table for a NOT IN query.
     *
     * @see useRightInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\RightQuery The inner query object of the NOT IN statement
     */
    public function useNotInRightQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\RightQuery */
        $q = $this->useInQuery('Right', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Role object
     *
     * @param \Model\Role|ObjectCollection $role the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByRole($role, ?string $comparison = null)
    {
        if ($role instanceof \Model\Role) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $role->getUserId(), $comparison);

            return $this;
        } elseif ($role instanceof ObjectCollection) {
            $this
                ->useRoleQuery()
                ->filterByPrimaryKeys($role->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByRole() only accepts arguments of type \Model\Role or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Role relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinRole(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Role');

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
            $this->addJoinObject($join, 'Role');
        }

        return $this;
    }

    /**
     * Use the Role relation Role object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\RoleQuery A secondary query class using the current class as primary query
     */
    public function useRoleQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinRole($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Role', '\Model\RoleQuery');
    }

    /**
     * Use the Role relation Role object
     *
     * @param callable(\Model\RoleQuery):\Model\RoleQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withRoleQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useRoleQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Role table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\RoleQuery The inner query object of the EXISTS statement
     */
    public function useRoleExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\RoleQuery */
        $q = $this->useExistsQuery('Role', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Role table for a NOT EXISTS query.
     *
     * @see useRoleExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\RoleQuery The inner query object of the NOT EXISTS statement
     */
    public function useRoleNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\RoleQuery */
        $q = $this->useExistsQuery('Role', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Role table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\RoleQuery The inner query object of the IN statement
     */
    public function useInRoleQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\RoleQuery */
        $q = $this->useInQuery('Role', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Role table for a NOT IN query.
     *
     * @see useRoleInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\RoleQuery The inner query object of the NOT IN statement
     */
    public function useNotInRoleQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\RoleQuery */
        $q = $this->useInQuery('Role', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Session object
     *
     * @param \Model\Session|ObjectCollection $session the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySession($session, ?string $comparison = null)
    {
        if ($session instanceof \Model\Session) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $session->getUserId(), $comparison);

            return $this;
        } elseif ($session instanceof ObjectCollection) {
            $this
                ->useSessionQuery()
                ->filterByPrimaryKeys($session->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterBySession() only accepts arguments of type \Model\Session or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Session relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinSession(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Session');

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
            $this->addJoinObject($join, 'Session');
        }

        return $this;
    }

    /**
     * Use the Session relation Session object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\SessionQuery A secondary query class using the current class as primary query
     */
    public function useSessionQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSession($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Session', '\Model\SessionQuery');
    }

    /**
     * Use the Session relation Session object
     *
     * @param callable(\Model\SessionQuery):\Model\SessionQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withSessionQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useSessionQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Session table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\SessionQuery The inner query object of the EXISTS statement
     */
    public function useSessionExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\SessionQuery */
        $q = $this->useExistsQuery('Session', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Session table for a NOT EXISTS query.
     *
     * @see useSessionExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\SessionQuery The inner query object of the NOT EXISTS statement
     */
    public function useSessionNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\SessionQuery */
        $q = $this->useExistsQuery('Session', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Session table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\SessionQuery The inner query object of the IN statement
     */
    public function useInSessionQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\SessionQuery */
        $q = $this->useInQuery('Session', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Session table for a NOT IN query.
     *
     * @see useSessionInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\SessionQuery The inner query object of the NOT IN statement
     */
    public function useNotInSessionQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\SessionQuery */
        $q = $this->useInQuery('Session', $modelAlias, $queryClass, 'NOT IN');
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
                ->addUsingAlias(UserTableMap::COL_ID, $stock->getUserId(), $comparison);

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
     * Filter the query by a related \Model\Subscription object
     *
     * @param \Model\Subscription|ObjectCollection $subscription the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySubscription($subscription, ?string $comparison = null)
    {
        if ($subscription instanceof \Model\Subscription) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $subscription->getUserId(), $comparison);

            return $this;
        } elseif ($subscription instanceof ObjectCollection) {
            $this
                ->useSubscriptionQuery()
                ->filterByPrimaryKeys($subscription->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterBySubscription() only accepts arguments of type \Model\Subscription or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Subscription relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinSubscription(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Subscription');

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
            $this->addJoinObject($join, 'Subscription');
        }

        return $this;
    }

    /**
     * Use the Subscription relation Subscription object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\SubscriptionQuery A secondary query class using the current class as primary query
     */
    public function useSubscriptionQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSubscription($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Subscription', '\Model\SubscriptionQuery');
    }

    /**
     * Use the Subscription relation Subscription object
     *
     * @param callable(\Model\SubscriptionQuery):\Model\SubscriptionQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withSubscriptionQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useSubscriptionQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Subscription table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\SubscriptionQuery The inner query object of the EXISTS statement
     */
    public function useSubscriptionExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\SubscriptionQuery */
        $q = $this->useExistsQuery('Subscription', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Subscription table for a NOT EXISTS query.
     *
     * @see useSubscriptionExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\SubscriptionQuery The inner query object of the NOT EXISTS statement
     */
    public function useSubscriptionNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\SubscriptionQuery */
        $q = $this->useExistsQuery('Subscription', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Subscription table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\SubscriptionQuery The inner query object of the IN statement
     */
    public function useInSubscriptionQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\SubscriptionQuery */
        $q = $this->useInQuery('Subscription', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Subscription table for a NOT IN query.
     *
     * @see useSubscriptionInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\SubscriptionQuery The inner query object of the NOT IN statement
     */
    public function useNotInSubscriptionQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\SubscriptionQuery */
        $q = $this->useInQuery('Subscription', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\AuthenticationMethod object
     *
     * @param \Model\AuthenticationMethod|ObjectCollection $authenticationMethod the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAuthenticationMethod($authenticationMethod, ?string $comparison = null)
    {
        if ($authenticationMethod instanceof \Model\AuthenticationMethod) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $authenticationMethod->getUserId(), $comparison);

            return $this;
        } elseif ($authenticationMethod instanceof ObjectCollection) {
            $this
                ->useAuthenticationMethodQuery()
                ->filterByPrimaryKeys($authenticationMethod->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByAuthenticationMethod() only accepts arguments of type \Model\AuthenticationMethod or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AuthenticationMethod relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinAuthenticationMethod(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AuthenticationMethod');

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
            $this->addJoinObject($join, 'AuthenticationMethod');
        }

        return $this;
    }

    /**
     * Use the AuthenticationMethod relation AuthenticationMethod object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\AuthenticationMethodQuery A secondary query class using the current class as primary query
     */
    public function useAuthenticationMethodQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAuthenticationMethod($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AuthenticationMethod', '\Model\AuthenticationMethodQuery');
    }

    /**
     * Use the AuthenticationMethod relation AuthenticationMethod object
     *
     * @param callable(\Model\AuthenticationMethodQuery):\Model\AuthenticationMethodQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withAuthenticationMethodQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useAuthenticationMethodQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to AuthenticationMethod table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\AuthenticationMethodQuery The inner query object of the EXISTS statement
     */
    public function useAuthenticationMethodExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\AuthenticationMethodQuery */
        $q = $this->useExistsQuery('AuthenticationMethod', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to AuthenticationMethod table for a NOT EXISTS query.
     *
     * @see useAuthenticationMethodExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\AuthenticationMethodQuery The inner query object of the NOT EXISTS statement
     */
    public function useAuthenticationMethodNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\AuthenticationMethodQuery */
        $q = $this->useExistsQuery('AuthenticationMethod', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to AuthenticationMethod table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\AuthenticationMethodQuery The inner query object of the IN statement
     */
    public function useInAuthenticationMethodQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\AuthenticationMethodQuery */
        $q = $this->useInQuery('AuthenticationMethod', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to AuthenticationMethod table for a NOT IN query.
     *
     * @see useAuthenticationMethodInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\AuthenticationMethodQuery The inner query object of the NOT IN statement
     */
    public function useNotInAuthenticationMethodQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\AuthenticationMethodQuery */
        $q = $this->useInQuery('AuthenticationMethod', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Vote object
     *
     * @param \Model\Vote|ObjectCollection $vote the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByVote($vote, ?string $comparison = null)
    {
        if ($vote instanceof \Model\Vote) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $vote->getUserId(), $comparison);

            return $this;
        } elseif ($vote instanceof ObjectCollection) {
            $this
                ->useVoteQuery()
                ->filterByPrimaryKeys($vote->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByVote() only accepts arguments of type \Model\Vote or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Vote relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinVote(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Vote');

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
            $this->addJoinObject($join, 'Vote');
        }

        return $this;
    }

    /**
     * Use the Vote relation Vote object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\VoteQuery A secondary query class using the current class as primary query
     */
    public function useVoteQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinVote($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Vote', '\Model\VoteQuery');
    }

    /**
     * Use the Vote relation Vote object
     *
     * @param callable(\Model\VoteQuery):\Model\VoteQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withVoteQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useVoteQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Vote table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\VoteQuery The inner query object of the EXISTS statement
     */
    public function useVoteExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\VoteQuery */
        $q = $this->useExistsQuery('Vote', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Vote table for a NOT EXISTS query.
     *
     * @see useVoteExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\VoteQuery The inner query object of the NOT EXISTS statement
     */
    public function useVoteNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\VoteQuery */
        $q = $this->useExistsQuery('Vote', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Vote table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\VoteQuery The inner query object of the IN statement
     */
    public function useInVoteQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\VoteQuery */
        $q = $this->useInQuery('Vote', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Vote table for a NOT IN query.
     *
     * @see useVoteInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\VoteQuery The inner query object of the NOT IN statement
     */
    public function useNotInVoteQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\VoteQuery */
        $q = $this->useInQuery('Vote', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Wish object
     *
     * @param \Model\Wish|ObjectCollection $wish the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByWish($wish, ?string $comparison = null)
    {
        if ($wish instanceof \Model\Wish) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $wish->getUserId(), $comparison);

            return $this;
        } elseif ($wish instanceof ObjectCollection) {
            $this
                ->useWishQuery()
                ->filterByPrimaryKeys($wish->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByWish() only accepts arguments of type \Model\Wish or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Wish relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinWish(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Wish');

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
            $this->addJoinObject($join, 'Wish');
        }

        return $this;
    }

    /**
     * Use the Wish relation Wish object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\WishQuery A secondary query class using the current class as primary query
     */
    public function useWishQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinWish($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Wish', '\Model\WishQuery');
    }

    /**
     * Use the Wish relation Wish object
     *
     * @param callable(\Model\WishQuery):\Model\WishQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withWishQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useWishQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Wish table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\WishQuery The inner query object of the EXISTS statement
     */
    public function useWishExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\WishQuery */
        $q = $this->useExistsQuery('Wish', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Wish table for a NOT EXISTS query.
     *
     * @see useWishExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\WishQuery The inner query object of the NOT EXISTS statement
     */
    public function useWishNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\WishQuery */
        $q = $this->useExistsQuery('Wish', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Wish table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\WishQuery The inner query object of the IN statement
     */
    public function useInWishQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\WishQuery */
        $q = $this->useInQuery('Wish', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Wish table for a NOT IN query.
     *
     * @see useWishInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\WishQuery The inner query object of the NOT IN statement
     */
    public function useNotInWishQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\WishQuery */
        $q = $this->useInQuery('Wish', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Wishlist object
     *
     * @param \Model\Wishlist|ObjectCollection $wishlist the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByWishlist($wishlist, ?string $comparison = null)
    {
        if ($wishlist instanceof \Model\Wishlist) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $wishlist->getUserId(), $comparison);

            return $this;
        } elseif ($wishlist instanceof ObjectCollection) {
            $this
                ->useWishlistQuery()
                ->filterByPrimaryKeys($wishlist->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByWishlist() only accepts arguments of type \Model\Wishlist or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Wishlist relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinWishlist(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Wishlist');

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
            $this->addJoinObject($join, 'Wishlist');
        }

        return $this;
    }

    /**
     * Use the Wishlist relation Wishlist object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\WishlistQuery A secondary query class using the current class as primary query
     */
    public function useWishlistQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinWishlist($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Wishlist', '\Model\WishlistQuery');
    }

    /**
     * Use the Wishlist relation Wishlist object
     *
     * @param callable(\Model\WishlistQuery):\Model\WishlistQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withWishlistQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useWishlistQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Wishlist table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\WishlistQuery The inner query object of the EXISTS statement
     */
    public function useWishlistExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\WishlistQuery */
        $q = $this->useExistsQuery('Wishlist', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Wishlist table for a NOT EXISTS query.
     *
     * @see useWishlistExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\WishlistQuery The inner query object of the NOT EXISTS statement
     */
    public function useWishlistNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\WishlistQuery */
        $q = $this->useExistsQuery('Wishlist', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Wishlist table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\WishlistQuery The inner query object of the IN statement
     */
    public function useInWishlistQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\WishlistQuery */
        $q = $this->useInQuery('Wishlist', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Wishlist table for a NOT IN query.
     *
     * @see useWishlistInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\WishlistQuery The inner query object of the NOT IN statement
     */
    public function useNotInWishlistQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\WishlistQuery */
        $q = $this->useInQuery('Wishlist', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Exclude object from result
     *
     * @param ChildUser $user Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($user = null)
    {
        if ($user) {
            $this->addUsingAlias(UserTableMap::COL_ID, $user->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the users table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UserTableMap::clearInstancePool();
            UserTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UserTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            UserTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            UserTableMap::clearRelatedInstancePool();

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
        $this->addUsingAlias(UserTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(UserTableMap::COL_UPDATED_AT);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(UserTableMap::COL_UPDATED_AT);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(UserTableMap::COL_CREATED_AT);

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
        $this->addUsingAlias(UserTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(UserTableMap::COL_CREATED_AT);

        return $this;
    }

}
