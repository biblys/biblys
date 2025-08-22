<?php

namespace Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Model\Alert as ChildAlert;
use Model\AlertQuery as ChildAlertQuery;
use Model\AuthenticationMethod as ChildAuthenticationMethod;
use Model\AuthenticationMethodQuery as ChildAuthenticationMethodQuery;
use Model\Cart as ChildCart;
use Model\CartQuery as ChildCartQuery;
use Model\Coupon as ChildCoupon;
use Model\CouponQuery as ChildCouponQuery;
use Model\Customer as ChildCustomer;
use Model\CustomerQuery as ChildCustomerQuery;
use Model\Download as ChildDownload;
use Model\DownloadQuery as ChildDownloadQuery;
use Model\File as ChildFile;
use Model\FileQuery as ChildFileQuery;
use Model\Link as ChildLink;
use Model\LinkQuery as ChildLinkQuery;
use Model\Option as ChildOption;
use Model\OptionQuery as ChildOptionQuery;
use Model\Order as ChildOrder;
use Model\OrderQuery as ChildOrderQuery;
use Model\Permission as ChildPermission;
use Model\PermissionQuery as ChildPermissionQuery;
use Model\Post as ChildPost;
use Model\PostQuery as ChildPostQuery;
use Model\Right as ChildRight;
use Model\RightQuery as ChildRightQuery;
use Model\Role as ChildRole;
use Model\RoleQuery as ChildRoleQuery;
use Model\Session as ChildSession;
use Model\SessionQuery as ChildSessionQuery;
use Model\Site as ChildSite;
use Model\SiteQuery as ChildSiteQuery;
use Model\Stock as ChildStock;
use Model\StockItemList as ChildStockItemList;
use Model\StockItemListQuery as ChildStockItemListQuery;
use Model\StockQuery as ChildStockQuery;
use Model\Subscription as ChildSubscription;
use Model\SubscriptionQuery as ChildSubscriptionQuery;
use Model\User as ChildUser;
use Model\UserQuery as ChildUserQuery;
use Model\Vote as ChildVote;
use Model\VoteQuery as ChildVoteQuery;
use Model\Wish as ChildWish;
use Model\WishQuery as ChildWishQuery;
use Model\Wishlist as ChildWishlist;
use Model\WishlistQuery as ChildWishlistQuery;
use Model\Map\AlertTableMap;
use Model\Map\AuthenticationMethodTableMap;
use Model\Map\CartTableMap;
use Model\Map\CouponTableMap;
use Model\Map\CustomerTableMap;
use Model\Map\DownloadTableMap;
use Model\Map\FileTableMap;
use Model\Map\LinkTableMap;
use Model\Map\OptionTableMap;
use Model\Map\OrderTableMap;
use Model\Map\PermissionTableMap;
use Model\Map\PostTableMap;
use Model\Map\RightTableMap;
use Model\Map\RoleTableMap;
use Model\Map\SessionTableMap;
use Model\Map\StockItemListTableMap;
use Model\Map\StockTableMap;
use Model\Map\SubscriptionTableMap;
use Model\Map\UserTableMap;
use Model\Map\VoteTableMap;
use Model\Map\WishTableMap;
use Model\Map\WishlistTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;

/**
 * Base class that represents a row from the 'users' table.
 *
 *
 *
 * @package    propel.generator.Model.Base
 */
abstract class User implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\Model\\Map\\UserTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var bool
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var bool
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = [];

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = [];

    /**
     * The value for the id field.
     *
     * @var        int
     */
    protected $id;

    /**
     * The value for the site_id field.
     *
     * @var        int
     */
    protected $site_id;

    /**
     * The value for the email field.
     *
     * @var        string|null
     */
    protected $email;

    /**
     * The value for the emailvalidatedat field.
     *
     * @var        DateTime|null
     */
    protected $emailvalidatedat;

    /**
     * The value for the lastloggedat field.
     *
     * @var        DateTime|null
     */
    protected $lastloggedat;

    /**
     * The value for the created_at field.
     *
     * @var        DateTime|null
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     *
     * @var        DateTime|null
     */
    protected $updated_at;

    /**
     * @var        ChildSite
     */
    protected $aSite;

    /**
     * @var        ObjectCollection|ChildAlert[] Collection to store aggregation of ChildAlert objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildAlert> Collection to store aggregation of ChildAlert objects.
     */
    protected $collAlerts;
    protected $collAlertsPartial;

    /**
     * @var        ObjectCollection|ChildCart[] Collection to store aggregation of ChildCart objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildCart> Collection to store aggregation of ChildCart objects.
     */
    protected $collCartsRelatedByUserId;
    protected $collCartsRelatedByUserIdPartial;

    /**
     * @var        ObjectCollection|ChildCart[] Collection to store aggregation of ChildCart objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildCart> Collection to store aggregation of ChildCart objects.
     */
    protected $collCartsRelatedBySellerUserId;
    protected $collCartsRelatedBySellerUserIdPartial;

    /**
     * @var        ObjectCollection|ChildCoupon[] Collection to store aggregation of ChildCoupon objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildCoupon> Collection to store aggregation of ChildCoupon objects.
     */
    protected $collCoupons;
    protected $collCouponsPartial;

    /**
     * @var        ObjectCollection|ChildCustomer[] Collection to store aggregation of ChildCustomer objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildCustomer> Collection to store aggregation of ChildCustomer objects.
     */
    protected $collCustomers;
    protected $collCustomersPartial;

    /**
     * @var        ObjectCollection|ChildDownload[] Collection to store aggregation of ChildDownload objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildDownload> Collection to store aggregation of ChildDownload objects.
     */
    protected $collDownloads;
    protected $collDownloadsPartial;

    /**
     * @var        ObjectCollection|ChildFile[] Collection to store aggregation of ChildFile objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildFile> Collection to store aggregation of ChildFile objects.
     */
    protected $collFiles;
    protected $collFilesPartial;

    /**
     * @var        ObjectCollection|ChildLink[] Collection to store aggregation of ChildLink objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildLink> Collection to store aggregation of ChildLink objects.
     */
    protected $collLinks;
    protected $collLinksPartial;

    /**
     * @var        ObjectCollection|ChildStockItemList[] Collection to store aggregation of ChildStockItemList objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildStockItemList> Collection to store aggregation of ChildStockItemList objects.
     */
    protected $collStockItemLists;
    protected $collStockItemListsPartial;

    /**
     * @var        ObjectCollection|ChildOption[] Collection to store aggregation of ChildOption objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildOption> Collection to store aggregation of ChildOption objects.
     */
    protected $collOptions;
    protected $collOptionsPartial;

    /**
     * @var        ObjectCollection|ChildOrder[] Collection to store aggregation of ChildOrder objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildOrder> Collection to store aggregation of ChildOrder objects.
     */
    protected $collOrders;
    protected $collOrdersPartial;

    /**
     * @var        ObjectCollection|ChildPermission[] Collection to store aggregation of ChildPermission objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildPermission> Collection to store aggregation of ChildPermission objects.
     */
    protected $collPermissions;
    protected $collPermissionsPartial;

    /**
     * @var        ObjectCollection|ChildPost[] Collection to store aggregation of ChildPost objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildPost> Collection to store aggregation of ChildPost objects.
     */
    protected $collPosts;
    protected $collPostsPartial;

    /**
     * @var        ObjectCollection|ChildRight[] Collection to store aggregation of ChildRight objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildRight> Collection to store aggregation of ChildRight objects.
     */
    protected $collRights;
    protected $collRightsPartial;

    /**
     * @var        ObjectCollection|ChildRole[] Collection to store aggregation of ChildRole objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildRole> Collection to store aggregation of ChildRole objects.
     */
    protected $collRoles;
    protected $collRolesPartial;

    /**
     * @var        ObjectCollection|ChildSession[] Collection to store aggregation of ChildSession objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildSession> Collection to store aggregation of ChildSession objects.
     */
    protected $collSessions;
    protected $collSessionsPartial;

    /**
     * @var        ObjectCollection|ChildStock[] Collection to store aggregation of ChildStock objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildStock> Collection to store aggregation of ChildStock objects.
     */
    protected $collStocks;
    protected $collStocksPartial;

    /**
     * @var        ObjectCollection|ChildSubscription[] Collection to store aggregation of ChildSubscription objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildSubscription> Collection to store aggregation of ChildSubscription objects.
     */
    protected $collSubscriptions;
    protected $collSubscriptionsPartial;

    /**
     * @var        ObjectCollection|ChildAuthenticationMethod[] Collection to store aggregation of ChildAuthenticationMethod objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildAuthenticationMethod> Collection to store aggregation of ChildAuthenticationMethod objects.
     */
    protected $collAuthenticationMethods;
    protected $collAuthenticationMethodsPartial;

    /**
     * @var        ObjectCollection|ChildVote[] Collection to store aggregation of ChildVote objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildVote> Collection to store aggregation of ChildVote objects.
     */
    protected $collVotes;
    protected $collVotesPartial;

    /**
     * @var        ObjectCollection|ChildWish[] Collection to store aggregation of ChildWish objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildWish> Collection to store aggregation of ChildWish objects.
     */
    protected $collWishes;
    protected $collWishesPartial;

    /**
     * @var        ObjectCollection|ChildWishlist[] Collection to store aggregation of ChildWishlist objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildWishlist> Collection to store aggregation of ChildWishlist objects.
     */
    protected $collWishlists;
    protected $collWishlistsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var bool
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildAlert[]
     * @phpstan-var ObjectCollection&\Traversable<ChildAlert>
     */
    protected $alertsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCart[]
     * @phpstan-var ObjectCollection&\Traversable<ChildCart>
     */
    protected $cartsRelatedByUserIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCart[]
     * @phpstan-var ObjectCollection&\Traversable<ChildCart>
     */
    protected $cartsRelatedBySellerUserIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCoupon[]
     * @phpstan-var ObjectCollection&\Traversable<ChildCoupon>
     */
    protected $couponsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCustomer[]
     * @phpstan-var ObjectCollection&\Traversable<ChildCustomer>
     */
    protected $customersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildDownload[]
     * @phpstan-var ObjectCollection&\Traversable<ChildDownload>
     */
    protected $downloadsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildFile[]
     * @phpstan-var ObjectCollection&\Traversable<ChildFile>
     */
    protected $filesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildLink[]
     * @phpstan-var ObjectCollection&\Traversable<ChildLink>
     */
    protected $linksScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildStockItemList[]
     * @phpstan-var ObjectCollection&\Traversable<ChildStockItemList>
     */
    protected $stockItemListsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildOption[]
     * @phpstan-var ObjectCollection&\Traversable<ChildOption>
     */
    protected $optionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildOrder[]
     * @phpstan-var ObjectCollection&\Traversable<ChildOrder>
     */
    protected $ordersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPermission[]
     * @phpstan-var ObjectCollection&\Traversable<ChildPermission>
     */
    protected $permissionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPost[]
     * @phpstan-var ObjectCollection&\Traversable<ChildPost>
     */
    protected $postsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildRight[]
     * @phpstan-var ObjectCollection&\Traversable<ChildRight>
     */
    protected $rightsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildRole[]
     * @phpstan-var ObjectCollection&\Traversable<ChildRole>
     */
    protected $rolesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildSession[]
     * @phpstan-var ObjectCollection&\Traversable<ChildSession>
     */
    protected $sessionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildStock[]
     * @phpstan-var ObjectCollection&\Traversable<ChildStock>
     */
    protected $stocksScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildSubscription[]
     * @phpstan-var ObjectCollection&\Traversable<ChildSubscription>
     */
    protected $subscriptionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildAuthenticationMethod[]
     * @phpstan-var ObjectCollection&\Traversable<ChildAuthenticationMethod>
     */
    protected $authenticationMethodsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildVote[]
     * @phpstan-var ObjectCollection&\Traversable<ChildVote>
     */
    protected $votesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildWish[]
     * @phpstan-var ObjectCollection&\Traversable<ChildWish>
     */
    protected $wishesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildWishlist[]
     * @phpstan-var ObjectCollection&\Traversable<ChildWishlist>
     */
    protected $wishlistsScheduledForDeletion = null;

    /**
     * Initializes internal state of Model\Base\User object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return bool True if the object has been modified.
     */
    public function isModified(): bool
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param string $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return bool True if $col has been modified.
     */
    public function isColumnModified(string $col): bool
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns(): array
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return bool True, if the object has never been persisted.
     */
    public function isNew(): bool
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param bool $b the state of the object.
     */
    public function setNew(bool $b): void
    {
        $this->new = $b;
    }

    /**
     * Whether this object has been deleted.
     * @return bool The deleted state of this object.
     */
    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param bool $b The deleted state of this object.
     * @return void
     */
    public function setDeleted(bool $b): void
    {
        $this->deleted = $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified(?string $col = null): void
    {
        if (null !== $col) {
            unset($this->modifiedColumns[$col]);
        } else {
            $this->modifiedColumns = [];
        }
    }

    /**
     * Compares this with another <code>User</code> instance.  If
     * <code>obj</code> is an instance of <code>User</code>, delegates to
     * <code>equals(User)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param mixed $obj The object to compare to.
     * @return bool Whether equal to the object specified.
     */
    public function equals($obj): bool
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns(): array
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param string $name The virtual column name
     * @return bool
     */
    public function hasVirtualColumn(string $name): bool
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param string $name The virtual column name
     * @return mixed
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getVirtualColumn(string $name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of nonexistent virtual column `%s`.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name The virtual column name
     * @param mixed $value The value to give to the virtual column
     *
     * @return $this The current object, for fluid interface
     */
    public function setVirtualColumn(string $name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param string $msg
     * @param int $priority One of the Propel::LOG_* logging levels
     * @return void
     */
    protected function log(string $msg, int $priority = Propel::LOG_INFO): void
    {
        Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param \Propel\Runtime\Parser\AbstractParser|string $parser An AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param bool $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @param string $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME, TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM. Defaults to TableMap::TYPE_PHPNAME.
     * @return string The exported data
     */
    public function exportTo($parser, bool $includeLazyLoadColumns = true, string $keyType = TableMap::TYPE_PHPNAME): string
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray($keyType, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     *
     * @return array<string>
     */
    public function __sleep(): array
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [site_id] column value.
     *
     * @return int
     */
    public function getSiteId()
    {
        return $this->site_id;
    }

    /**
     * Get the [email] column value.
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the [optionally formatted] temporal [emailvalidatedat] column value.
     *
     *
     * @param string|null $format The date/time format string (either date()-style or strftime()-style).
     *   If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00.
     *
     * @throws \Propel\Runtime\Exception\PropelException - if unable to parse/validate the date/time value.
     *
     * @psalm-return ($format is null ? DateTime|null : string|null)
     */
    public function getEmailValidatedAt($format = null)
    {
        if ($format === null) {
            return $this->emailvalidatedat;
        } else {
            return $this->emailvalidatedat instanceof \DateTimeInterface ? $this->emailvalidatedat->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [lastloggedat] column value.
     *
     *
     * @param string|null $format The date/time format string (either date()-style or strftime()-style).
     *   If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00.
     *
     * @throws \Propel\Runtime\Exception\PropelException - if unable to parse/validate the date/time value.
     *
     * @psalm-return ($format is null ? DateTime|null : string|null)
     */
    public function getLastLoggedAt($format = null)
    {
        if ($format === null) {
            return $this->lastloggedat;
        } else {
            return $this->lastloggedat instanceof \DateTimeInterface ? $this->lastloggedat->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param string|null $format The date/time format string (either date()-style or strftime()-style).
     *   If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00.
     *
     * @throws \Propel\Runtime\Exception\PropelException - if unable to parse/validate the date/time value.
     *
     * @psalm-return ($format is null ? DateTime|null : string|null)
     */
    public function getCreatedAt($format = null)
    {
        if ($format === null) {
            return $this->created_at;
        } else {
            return $this->created_at instanceof \DateTimeInterface ? $this->created_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param string|null $format The date/time format string (either date()-style or strftime()-style).
     *   If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00.
     *
     * @throws \Propel\Runtime\Exception\PropelException - if unable to parse/validate the date/time value.
     *
     * @psalm-return ($format is null ? DateTime|null : string|null)
     */
    public function getUpdatedAt($format = null)
    {
        if ($format === null) {
            return $this->updated_at;
        } else {
            return $this->updated_at instanceof \DateTimeInterface ? $this->updated_at->format($format) : null;
        }
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[UserTableMap::COL_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [site_id] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setSiteId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->site_id !== $v) {
            $this->site_id = $v;
            $this->modifiedColumns[UserTableMap::COL_SITE_ID] = true;
        }

        if ($this->aSite !== null && $this->aSite->getId() !== $v) {
            $this->aSite = null;
        }

        return $this;
    }

    /**
     * Set the value of [email] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[UserTableMap::COL_EMAIL] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [emailvalidatedat] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setEmailValidatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->emailvalidatedat !== null || $dt !== null) {
            if ($this->emailvalidatedat === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->emailvalidatedat->format("Y-m-d H:i:s.u")) {
                $this->emailvalidatedat = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_EMAILVALIDATEDAT] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [lastloggedat] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setLastLoggedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->lastloggedat !== null || $dt !== null) {
            if ($this->lastloggedat === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->lastloggedat->format("Y-m-d H:i:s.u")) {
                $this->lastloggedat = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_LASTLOGGEDAT] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->created_at->format("Y-m-d H:i:s.u")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->updated_at->format("Y-m-d H:i:s.u")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_UPDATED_AT] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return bool Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues(): bool
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    }

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by DataFetcher->fetch().
     * @param int $startcol 0-based offset column which indicates which resultset column to start with.
     * @param bool $rehydrate Whether this object is being re-hydrated from the database.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int next starting column
     * @throws \Propel\Runtime\Exception\PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate(array $row, int $startcol = 0, bool $rehydrate = false, string $indexType = TableMap::TYPE_NUM): int
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : UserTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : UserTableMap::translateFieldName('SiteId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : UserTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : UserTableMap::translateFieldName('EmailValidatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->emailvalidatedat = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : UserTableMap::translateFieldName('LastLoggedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->lastloggedat = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : UserTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : UserTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 7; // 7 = UserTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\User'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function ensureConsistency(): void
    {
        if ($this->aSite !== null && $this->site_id !== $this->aSite->getId()) {
            $this->aSite = null;
        }
    }

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param bool $deep (optional) Whether to also de-associated any related objects.
     * @param ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload(bool $deep = false, ?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildUserQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aSite = null;
            $this->collAlerts = null;

            $this->collCartsRelatedByUserId = null;

            $this->collCartsRelatedBySellerUserId = null;

            $this->collCoupons = null;

            $this->collCustomers = null;

            $this->collDownloads = null;

            $this->collFiles = null;

            $this->collLinks = null;

            $this->collStockItemLists = null;

            $this->collOptions = null;

            $this->collOrders = null;

            $this->collPermissions = null;

            $this->collPosts = null;

            $this->collRights = null;

            $this->collRoles = null;

            $this->collSessions = null;

            $this->collStocks = null;

            $this->collSubscriptions = null;

            $this->collAuthenticationMethods = null;

            $this->collVotes = null;

            $this->collWishes = null;

            $this->collWishlists = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param ConnectionInterface $con
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     * @see User::setDeleted()
     * @see User::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildUserQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param ConnectionInterface $con
     * @return int The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws \Propel\Runtime\Exception\PropelException
     * @see doSave()
     */
    public function save(?ConnectionInterface $con = null): int
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($this->alreadyInSave) {
            return 0;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(UserTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(UserTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(UserTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                UserTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param ConnectionInterface $con
     * @return int The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws \Propel\Runtime\Exception\PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con): int
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aSite !== null) {
                if ($this->aSite->isModified() || $this->aSite->isNew()) {
                    $affectedRows += $this->aSite->save($con);
                }
                $this->setSite($this->aSite);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->alertsScheduledForDeletion !== null) {
                if (!$this->alertsScheduledForDeletion->isEmpty()) {
                    foreach ($this->alertsScheduledForDeletion as $alert) {
                        // need to save related object because we set the relation to null
                        $alert->save($con);
                    }
                    $this->alertsScheduledForDeletion = null;
                }
            }

            if ($this->collAlerts !== null) {
                foreach ($this->collAlerts as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->cartsRelatedByUserIdScheduledForDeletion !== null) {
                if (!$this->cartsRelatedByUserIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->cartsRelatedByUserIdScheduledForDeletion as $cartRelatedByUserId) {
                        // need to save related object because we set the relation to null
                        $cartRelatedByUserId->save($con);
                    }
                    $this->cartsRelatedByUserIdScheduledForDeletion = null;
                }
            }

            if ($this->collCartsRelatedByUserId !== null) {
                foreach ($this->collCartsRelatedByUserId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->cartsRelatedBySellerUserIdScheduledForDeletion !== null) {
                if (!$this->cartsRelatedBySellerUserIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->cartsRelatedBySellerUserIdScheduledForDeletion as $cartRelatedBySellerUserId) {
                        // need to save related object because we set the relation to null
                        $cartRelatedBySellerUserId->save($con);
                    }
                    $this->cartsRelatedBySellerUserIdScheduledForDeletion = null;
                }
            }

            if ($this->collCartsRelatedBySellerUserId !== null) {
                foreach ($this->collCartsRelatedBySellerUserId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->couponsScheduledForDeletion !== null) {
                if (!$this->couponsScheduledForDeletion->isEmpty()) {
                    foreach ($this->couponsScheduledForDeletion as $coupon) {
                        // need to save related object because we set the relation to null
                        $coupon->save($con);
                    }
                    $this->couponsScheduledForDeletion = null;
                }
            }

            if ($this->collCoupons !== null) {
                foreach ($this->collCoupons as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->customersScheduledForDeletion !== null) {
                if (!$this->customersScheduledForDeletion->isEmpty()) {
                    foreach ($this->customersScheduledForDeletion as $customer) {
                        // need to save related object because we set the relation to null
                        $customer->save($con);
                    }
                    $this->customersScheduledForDeletion = null;
                }
            }

            if ($this->collCustomers !== null) {
                foreach ($this->collCustomers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->downloadsScheduledForDeletion !== null) {
                if (!$this->downloadsScheduledForDeletion->isEmpty()) {
                    foreach ($this->downloadsScheduledForDeletion as $download) {
                        // need to save related object because we set the relation to null
                        $download->save($con);
                    }
                    $this->downloadsScheduledForDeletion = null;
                }
            }

            if ($this->collDownloads !== null) {
                foreach ($this->collDownloads as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->filesScheduledForDeletion !== null) {
                if (!$this->filesScheduledForDeletion->isEmpty()) {
                    foreach ($this->filesScheduledForDeletion as $file) {
                        // need to save related object because we set the relation to null
                        $file->save($con);
                    }
                    $this->filesScheduledForDeletion = null;
                }
            }

            if ($this->collFiles !== null) {
                foreach ($this->collFiles as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->linksScheduledForDeletion !== null) {
                if (!$this->linksScheduledForDeletion->isEmpty()) {
                    foreach ($this->linksScheduledForDeletion as $link) {
                        // need to save related object because we set the relation to null
                        $link->save($con);
                    }
                    $this->linksScheduledForDeletion = null;
                }
            }

            if ($this->collLinks !== null) {
                foreach ($this->collLinks as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->stockItemListsScheduledForDeletion !== null) {
                if (!$this->stockItemListsScheduledForDeletion->isEmpty()) {
                    foreach ($this->stockItemListsScheduledForDeletion as $stockItemList) {
                        // need to save related object because we set the relation to null
                        $stockItemList->save($con);
                    }
                    $this->stockItemListsScheduledForDeletion = null;
                }
            }

            if ($this->collStockItemLists !== null) {
                foreach ($this->collStockItemLists as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->optionsScheduledForDeletion !== null) {
                if (!$this->optionsScheduledForDeletion->isEmpty()) {
                    foreach ($this->optionsScheduledForDeletion as $option) {
                        // need to save related object because we set the relation to null
                        $option->save($con);
                    }
                    $this->optionsScheduledForDeletion = null;
                }
            }

            if ($this->collOptions !== null) {
                foreach ($this->collOptions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->ordersScheduledForDeletion !== null) {
                if (!$this->ordersScheduledForDeletion->isEmpty()) {
                    foreach ($this->ordersScheduledForDeletion as $order) {
                        // need to save related object because we set the relation to null
                        $order->save($con);
                    }
                    $this->ordersScheduledForDeletion = null;
                }
            }

            if ($this->collOrders !== null) {
                foreach ($this->collOrders as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->permissionsScheduledForDeletion !== null) {
                if (!$this->permissionsScheduledForDeletion->isEmpty()) {
                    foreach ($this->permissionsScheduledForDeletion as $permission) {
                        // need to save related object because we set the relation to null
                        $permission->save($con);
                    }
                    $this->permissionsScheduledForDeletion = null;
                }
            }

            if ($this->collPermissions !== null) {
                foreach ($this->collPermissions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->postsScheduledForDeletion !== null) {
                if (!$this->postsScheduledForDeletion->isEmpty()) {
                    foreach ($this->postsScheduledForDeletion as $post) {
                        // need to save related object because we set the relation to null
                        $post->save($con);
                    }
                    $this->postsScheduledForDeletion = null;
                }
            }

            if ($this->collPosts !== null) {
                foreach ($this->collPosts as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->rightsScheduledForDeletion !== null) {
                if (!$this->rightsScheduledForDeletion->isEmpty()) {
                    foreach ($this->rightsScheduledForDeletion as $right) {
                        // need to save related object because we set the relation to null
                        $right->save($con);
                    }
                    $this->rightsScheduledForDeletion = null;
                }
            }

            if ($this->collRights !== null) {
                foreach ($this->collRights as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->rolesScheduledForDeletion !== null) {
                if (!$this->rolesScheduledForDeletion->isEmpty()) {
                    foreach ($this->rolesScheduledForDeletion as $role) {
                        // need to save related object because we set the relation to null
                        $role->save($con);
                    }
                    $this->rolesScheduledForDeletion = null;
                }
            }

            if ($this->collRoles !== null) {
                foreach ($this->collRoles as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->sessionsScheduledForDeletion !== null) {
                if (!$this->sessionsScheduledForDeletion->isEmpty()) {
                    foreach ($this->sessionsScheduledForDeletion as $session) {
                        // need to save related object because we set the relation to null
                        $session->save($con);
                    }
                    $this->sessionsScheduledForDeletion = null;
                }
            }

            if ($this->collSessions !== null) {
                foreach ($this->collSessions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->stocksScheduledForDeletion !== null) {
                if (!$this->stocksScheduledForDeletion->isEmpty()) {
                    foreach ($this->stocksScheduledForDeletion as $stock) {
                        // need to save related object because we set the relation to null
                        $stock->save($con);
                    }
                    $this->stocksScheduledForDeletion = null;
                }
            }

            if ($this->collStocks !== null) {
                foreach ($this->collStocks as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->subscriptionsScheduledForDeletion !== null) {
                if (!$this->subscriptionsScheduledForDeletion->isEmpty()) {
                    foreach ($this->subscriptionsScheduledForDeletion as $subscription) {
                        // need to save related object because we set the relation to null
                        $subscription->save($con);
                    }
                    $this->subscriptionsScheduledForDeletion = null;
                }
            }

            if ($this->collSubscriptions !== null) {
                foreach ($this->collSubscriptions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->authenticationMethodsScheduledForDeletion !== null) {
                if (!$this->authenticationMethodsScheduledForDeletion->isEmpty()) {
                    \Model\AuthenticationMethodQuery::create()
                        ->filterByPrimaryKeys($this->authenticationMethodsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->authenticationMethodsScheduledForDeletion = null;
                }
            }

            if ($this->collAuthenticationMethods !== null) {
                foreach ($this->collAuthenticationMethods as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->votesScheduledForDeletion !== null) {
                if (!$this->votesScheduledForDeletion->isEmpty()) {
                    foreach ($this->votesScheduledForDeletion as $vote) {
                        // need to save related object because we set the relation to null
                        $vote->save($con);
                    }
                    $this->votesScheduledForDeletion = null;
                }
            }

            if ($this->collVotes !== null) {
                foreach ($this->collVotes as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->wishesScheduledForDeletion !== null) {
                if (!$this->wishesScheduledForDeletion->isEmpty()) {
                    foreach ($this->wishesScheduledForDeletion as $wish) {
                        // need to save related object because we set the relation to null
                        $wish->save($con);
                    }
                    $this->wishesScheduledForDeletion = null;
                }
            }

            if ($this->collWishes !== null) {
                foreach ($this->collWishes as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->wishlistsScheduledForDeletion !== null) {
                if (!$this->wishlistsScheduledForDeletion->isEmpty()) {
                    foreach ($this->wishlistsScheduledForDeletion as $wishlist) {
                        // need to save related object because we set the relation to null
                        $wishlist->save($con);
                    }
                    $this->wishlistsScheduledForDeletion = null;
                }
            }

            if ($this->collWishlists !== null) {
                foreach ($this->collWishlists as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    }

    /**
     * Insert the row in the database.
     *
     * @param ConnectionInterface $con
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con): void
    {
        $modifiedColumns = [];
        $index = 0;

        $this->modifiedColumns[UserTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UserTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(UserTableMap::COL_SITE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'site_id';
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'email';
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAILVALIDATEDAT)) {
            $modifiedColumns[':p' . $index++]  = 'emailValidatedAt';
        }
        if ($this->isColumnModified(UserTableMap::COL_LASTLOGGEDAT)) {
            $modifiedColumns[':p' . $index++]  = 'lastLoggedAt';
        }
        if ($this->isColumnModified(UserTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'created_at';
        }
        if ($this->isColumnModified(UserTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'updated_at';
        }

        $sql = sprintf(
            'INSERT INTO users (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);

                        break;
                    case 'site_id':
                        $stmt->bindValue($identifier, $this->site_id, PDO::PARAM_INT);

                        break;
                    case 'email':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);

                        break;
                    case 'emailValidatedAt':
                        $stmt->bindValue($identifier, $this->emailvalidatedat ? $this->emailvalidatedat->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'lastLoggedAt':
                        $stmt->bindValue($identifier, $this->lastloggedat ? $this->lastloggedat->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'created_at':
                        $stmt->bindValue($identifier, $this->created_at ? $this->created_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'updated_at':
                        $stmt->bindValue($identifier, $this->updated_at ? $this->updated_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param ConnectionInterface $con
     *
     * @return int Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con): int
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName(string $name, string $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos Position in XML schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition(int $pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();

            case 1:
                return $this->getSiteId();

            case 2:
                return $this->getEmail();

            case 3:
                return $this->getEmailValidatedAt();

            case 4:
                return $this->getLastLoggedAt();

            case 5:
                return $this->getCreatedAt();

            case 6:
                return $this->getUpdatedAt();

            default:
                return null;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param string $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param bool $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param bool $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array An associative array containing the field names (as keys) and field values
     */
    public function toArray(string $keyType = TableMap::TYPE_PHPNAME, bool $includeLazyLoadColumns = true, array $alreadyDumpedObjects = [], bool $includeForeignObjects = false): array
    {
        if (isset($alreadyDumpedObjects['User'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['User'][$this->hashCode()] = true;
        $keys = UserTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getSiteId(),
            $keys[2] => $this->getEmail(),
            $keys[3] => $this->getEmailValidatedAt(),
            $keys[4] => $this->getLastLoggedAt(),
            $keys[5] => $this->getCreatedAt(),
            $keys[6] => $this->getUpdatedAt(),
        ];
        if ($result[$keys[3]] instanceof \DateTimeInterface) {
            $result[$keys[3]] = $result[$keys[3]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[4]] instanceof \DateTimeInterface) {
            $result[$keys[4]] = $result[$keys[4]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[5]] instanceof \DateTimeInterface) {
            $result[$keys[5]] = $result[$keys[5]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[6]] instanceof \DateTimeInterface) {
            $result[$keys[6]] = $result[$keys[6]]->format('Y-m-d H:i:s.u');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aSite) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'site';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'sites';
                        break;
                    default:
                        $key = 'Site';
                }

                $result[$key] = $this->aSite->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collAlerts) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'alerts';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'alertss';
                        break;
                    default:
                        $key = 'Alerts';
                }

                $result[$key] = $this->collAlerts->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCartsRelatedByUserId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'carts';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'cartss';
                        break;
                    default:
                        $key = 'Carts';
                }

                $result[$key] = $this->collCartsRelatedByUserId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCartsRelatedBySellerUserId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'carts';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'cartss';
                        break;
                    default:
                        $key = 'Carts';
                }

                $result[$key] = $this->collCartsRelatedBySellerUserId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCoupons) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'coupons';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'couponss';
                        break;
                    default:
                        $key = 'Coupons';
                }

                $result[$key] = $this->collCoupons->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCustomers) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'customers';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'customerss';
                        break;
                    default:
                        $key = 'Customers';
                }

                $result[$key] = $this->collCustomers->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collDownloads) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'downloads';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'downloadss';
                        break;
                    default:
                        $key = 'Downloads';
                }

                $result[$key] = $this->collDownloads->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collFiles) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'files';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'filess';
                        break;
                    default:
                        $key = 'Files';
                }

                $result[$key] = $this->collFiles->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collLinks) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'links';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'linkss';
                        break;
                    default:
                        $key = 'Links';
                }

                $result[$key] = $this->collLinks->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collStockItemLists) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'stockItemLists';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'listss';
                        break;
                    default:
                        $key = 'StockItemLists';
                }

                $result[$key] = $this->collStockItemLists->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collOptions) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'options';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'optionss';
                        break;
                    default:
                        $key = 'Options';
                }

                $result[$key] = $this->collOptions->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collOrders) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'orders';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'orderss';
                        break;
                    default:
                        $key = 'Orders';
                }

                $result[$key] = $this->collOrders->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPermissions) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'permissions';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'permissionss';
                        break;
                    default:
                        $key = 'Permissions';
                }

                $result[$key] = $this->collPermissions->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPosts) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'posts';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'postss';
                        break;
                    default:
                        $key = 'Posts';
                }

                $result[$key] = $this->collPosts->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collRights) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'rights';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'rightss';
                        break;
                    default:
                        $key = 'Rights';
                }

                $result[$key] = $this->collRights->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collRoles) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'roles';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'roless';
                        break;
                    default:
                        $key = 'Roles';
                }

                $result[$key] = $this->collRoles->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSessions) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'sessions';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'sessions';
                        break;
                    default:
                        $key = 'Sessions';
                }

                $result[$key] = $this->collSessions->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collStocks) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'stocks';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'stocks';
                        break;
                    default:
                        $key = 'Stocks';
                }

                $result[$key] = $this->collStocks->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSubscriptions) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'subscriptions';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'subscriptionss';
                        break;
                    default:
                        $key = 'Subscriptions';
                }

                $result[$key] = $this->collSubscriptions->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collAuthenticationMethods) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'authenticationMethods';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'authentication_methodss';
                        break;
                    default:
                        $key = 'AuthenticationMethods';
                }

                $result[$key] = $this->collAuthenticationMethods->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collVotes) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'votes';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'votess';
                        break;
                    default:
                        $key = 'Votes';
                }

                $result[$key] = $this->collVotes->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collWishes) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'wishes';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'wishess';
                        break;
                    default:
                        $key = 'Wishes';
                }

                $result[$key] = $this->collWishes->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collWishlists) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'wishlists';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'wishlists';
                        break;
                    default:
                        $key = 'Wishlists';
                }

                $result[$key] = $this->collWishlists->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this
     */
    public function setByName(string $name, $value, string $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        $this->setByPosition($pos, $value);

        return $this;
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return $this
     */
    public function setByPosition(int $pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setSiteId($value);
                break;
            case 2:
                $this->setEmail($value);
                break;
            case 3:
                $this->setEmailValidatedAt($value);
                break;
            case 4:
                $this->setLastLoggedAt($value);
                break;
            case 5:
                $this->setCreatedAt($value);
                break;
            case 6:
                $this->setUpdatedAt($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param array $arr An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return $this
     */
    public function fromArray(array $arr, string $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = UserTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setSiteId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setEmail($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setEmailValidatedAt($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setLastLoggedAt($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setCreatedAt($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setUpdatedAt($arr[$keys[6]]);
        }

        return $this;
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this The current object, for fluid interface
     */
    public function importFrom($parser, string $data, string $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return \Propel\Runtime\ActiveQuery\Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria(): Criteria
    {
        $criteria = new Criteria(UserTableMap::DATABASE_NAME);

        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $criteria->add(UserTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(UserTableMap::COL_SITE_ID)) {
            $criteria->add(UserTableMap::COL_SITE_ID, $this->site_id);
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $criteria->add(UserTableMap::COL_EMAIL, $this->email);
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAILVALIDATEDAT)) {
            $criteria->add(UserTableMap::COL_EMAILVALIDATEDAT, $this->emailvalidatedat);
        }
        if ($this->isColumnModified(UserTableMap::COL_LASTLOGGEDAT)) {
            $criteria->add(UserTableMap::COL_LASTLOGGEDAT, $this->lastloggedat);
        }
        if ($this->isColumnModified(UserTableMap::COL_CREATED_AT)) {
            $criteria->add(UserTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(UserTableMap::COL_UPDATED_AT)) {
            $criteria->add(UserTableMap::COL_UPDATED_AT, $this->updated_at);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return \Propel\Runtime\ActiveQuery\Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria(): Criteria
    {
        $criteria = ChildUserQuery::create();
        $criteria->add(UserTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int|string Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param int|null $key Primary key.
     * @return void
     */
    public function setPrimaryKey(?int $key = null): void
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     *
     * @return bool
     */
    public function isPrimaryKeyNull(): bool
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of \Model\User (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setSiteId($this->getSiteId());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setEmailValidatedAt($this->getEmailValidatedAt());
        $copyObj->setLastLoggedAt($this->getLastLoggedAt());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getAlerts() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAlert($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCartsRelatedByUserId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCartRelatedByUserId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCartsRelatedBySellerUserId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCartRelatedBySellerUserId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCoupons() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCoupon($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCustomers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCustomer($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getDownloads() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDownload($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getFiles() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFile($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getLinks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLink($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getStockItemLists() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addStockItemList($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getOptions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOption($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getOrders() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOrder($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPermissions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPermission($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPosts() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPost($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getRights() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addRight($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getRoles() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addRole($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSessions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSession($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getStocks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addStock($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSubscriptions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSubscription($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getAuthenticationMethods() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAuthenticationMethod($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getVotes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addVote($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getWishes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addWish($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getWishlists() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addWishlist($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Model\User Clone of current object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function copy(bool $deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Declares an association between this object and a ChildSite object.
     *
     * @param ChildSite $v
     * @return $this The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setSite(ChildSite $v = null)
    {
        if ($v === null) {
            $this->setSiteId(NULL);
        } else {
            $this->setSiteId($v->getId());
        }

        $this->aSite = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildSite object, it will not be re-added.
        if ($v !== null) {
            $v->addUser($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildSite object
     *
     * @param ConnectionInterface $con Optional Connection object.
     * @return ChildSite The associated ChildSite object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getSite(?ConnectionInterface $con = null)
    {
        if ($this->aSite === null && ($this->site_id != 0)) {
            $this->aSite = ChildSiteQuery::create()->findPk($this->site_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aSite->addUsers($this);
             */
        }

        return $this->aSite;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName): void
    {
        if ('Alert' === $relationName) {
            $this->initAlerts();
            return;
        }
        if ('CartRelatedByUserId' === $relationName) {
            $this->initCartsRelatedByUserId();
            return;
        }
        if ('CartRelatedBySellerUserId' === $relationName) {
            $this->initCartsRelatedBySellerUserId();
            return;
        }
        if ('Coupon' === $relationName) {
            $this->initCoupons();
            return;
        }
        if ('Customer' === $relationName) {
            $this->initCustomers();
            return;
        }
        if ('Download' === $relationName) {
            $this->initDownloads();
            return;
        }
        if ('File' === $relationName) {
            $this->initFiles();
            return;
        }
        if ('Link' === $relationName) {
            $this->initLinks();
            return;
        }
        if ('StockItemList' === $relationName) {
            $this->initStockItemLists();
            return;
        }
        if ('Option' === $relationName) {
            $this->initOptions();
            return;
        }
        if ('Order' === $relationName) {
            $this->initOrders();
            return;
        }
        if ('Permission' === $relationName) {
            $this->initPermissions();
            return;
        }
        if ('Post' === $relationName) {
            $this->initPosts();
            return;
        }
        if ('Right' === $relationName) {
            $this->initRights();
            return;
        }
        if ('Role' === $relationName) {
            $this->initRoles();
            return;
        }
        if ('Session' === $relationName) {
            $this->initSessions();
            return;
        }
        if ('Stock' === $relationName) {
            $this->initStocks();
            return;
        }
        if ('Subscription' === $relationName) {
            $this->initSubscriptions();
            return;
        }
        if ('AuthenticationMethod' === $relationName) {
            $this->initAuthenticationMethods();
            return;
        }
        if ('Vote' === $relationName) {
            $this->initVotes();
            return;
        }
        if ('Wish' === $relationName) {
            $this->initWishes();
            return;
        }
        if ('Wishlist' === $relationName) {
            $this->initWishlists();
            return;
        }
    }

    /**
     * Clears out the collAlerts collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addAlerts()
     */
    public function clearAlerts()
    {
        $this->collAlerts = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collAlerts collection loaded partially.
     *
     * @return void
     */
    public function resetPartialAlerts($v = true): void
    {
        $this->collAlertsPartial = $v;
    }

    /**
     * Initializes the collAlerts collection.
     *
     * By default this just sets the collAlerts collection to an empty array (like clearcollAlerts());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAlerts(bool $overrideExisting = true): void
    {
        if (null !== $this->collAlerts && !$overrideExisting) {
            return;
        }

        $collectionClassName = AlertTableMap::getTableMap()->getCollectionClassName();

        $this->collAlerts = new $collectionClassName;
        $this->collAlerts->setModel('\Model\Alert');
    }

    /**
     * Gets an array of ChildAlert objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildAlert[] List of ChildAlert objects
     * @phpstan-return ObjectCollection&\Traversable<ChildAlert> List of ChildAlert objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getAlerts(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collAlertsPartial && !$this->isNew();
        if (null === $this->collAlerts || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collAlerts) {
                    $this->initAlerts();
                } else {
                    $collectionClassName = AlertTableMap::getTableMap()->getCollectionClassName();

                    $collAlerts = new $collectionClassName;
                    $collAlerts->setModel('\Model\Alert');

                    return $collAlerts;
                }
            } else {
                $collAlerts = ChildAlertQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collAlertsPartial && count($collAlerts)) {
                        $this->initAlerts(false);

                        foreach ($collAlerts as $obj) {
                            if (false == $this->collAlerts->contains($obj)) {
                                $this->collAlerts->append($obj);
                            }
                        }

                        $this->collAlertsPartial = true;
                    }

                    return $collAlerts;
                }

                if ($partial && $this->collAlerts) {
                    foreach ($this->collAlerts as $obj) {
                        if ($obj->isNew()) {
                            $collAlerts[] = $obj;
                        }
                    }
                }

                $this->collAlerts = $collAlerts;
                $this->collAlertsPartial = false;
            }
        }

        return $this->collAlerts;
    }

    /**
     * Sets a collection of ChildAlert objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $alerts A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setAlerts(Collection $alerts, ?ConnectionInterface $con = null)
    {
        /** @var ChildAlert[] $alertsToDelete */
        $alertsToDelete = $this->getAlerts(new Criteria(), $con)->diff($alerts);


        $this->alertsScheduledForDeletion = $alertsToDelete;

        foreach ($alertsToDelete as $alertRemoved) {
            $alertRemoved->setUser(null);
        }

        $this->collAlerts = null;
        foreach ($alerts as $alert) {
            $this->addAlert($alert);
        }

        $this->collAlerts = $alerts;
        $this->collAlertsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Alert objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Alert objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countAlerts(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collAlertsPartial && !$this->isNew();
        if (null === $this->collAlerts || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAlerts) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAlerts());
            }

            $query = ChildAlertQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collAlerts);
    }

    /**
     * Method called to associate a ChildAlert object to this object
     * through the ChildAlert foreign key attribute.
     *
     * @param ChildAlert $l ChildAlert
     * @return $this The current object (for fluent API support)
     */
    public function addAlert(ChildAlert $l)
    {
        if ($this->collAlerts === null) {
            $this->initAlerts();
            $this->collAlertsPartial = true;
        }

        if (!$this->collAlerts->contains($l)) {
            $this->doAddAlert($l);

            if ($this->alertsScheduledForDeletion and $this->alertsScheduledForDeletion->contains($l)) {
                $this->alertsScheduledForDeletion->remove($this->alertsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildAlert $alert The ChildAlert object to add.
     */
    protected function doAddAlert(ChildAlert $alert): void
    {
        $this->collAlerts[]= $alert;
        $alert->setUser($this);
    }

    /**
     * @param ChildAlert $alert The ChildAlert object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeAlert(ChildAlert $alert)
    {
        if ($this->getAlerts()->contains($alert)) {
            $pos = $this->collAlerts->search($alert);
            $this->collAlerts->remove($pos);
            if (null === $this->alertsScheduledForDeletion) {
                $this->alertsScheduledForDeletion = clone $this->collAlerts;
                $this->alertsScheduledForDeletion->clear();
            }
            $this->alertsScheduledForDeletion[]= $alert;
            $alert->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Alerts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildAlert[] List of ChildAlert objects
     * @phpstan-return ObjectCollection&\Traversable<ChildAlert}> List of ChildAlert objects
     */
    public function getAlertsJoinSite(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildAlertQuery::create(null, $criteria);
        $query->joinWith('Site', $joinBehavior);

        return $this->getAlerts($query, $con);
    }

    /**
     * Clears out the collCartsRelatedByUserId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addCartsRelatedByUserId()
     */
    public function clearCartsRelatedByUserId()
    {
        $this->collCartsRelatedByUserId = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collCartsRelatedByUserId collection loaded partially.
     *
     * @return void
     */
    public function resetPartialCartsRelatedByUserId($v = true): void
    {
        $this->collCartsRelatedByUserIdPartial = $v;
    }

    /**
     * Initializes the collCartsRelatedByUserId collection.
     *
     * By default this just sets the collCartsRelatedByUserId collection to an empty array (like clearcollCartsRelatedByUserId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCartsRelatedByUserId(bool $overrideExisting = true): void
    {
        if (null !== $this->collCartsRelatedByUserId && !$overrideExisting) {
            return;
        }

        $collectionClassName = CartTableMap::getTableMap()->getCollectionClassName();

        $this->collCartsRelatedByUserId = new $collectionClassName;
        $this->collCartsRelatedByUserId->setModel('\Model\Cart');
    }

    /**
     * Gets an array of ChildCart objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCart[] List of ChildCart objects
     * @phpstan-return ObjectCollection&\Traversable<ChildCart> List of ChildCart objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getCartsRelatedByUserId(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collCartsRelatedByUserIdPartial && !$this->isNew();
        if (null === $this->collCartsRelatedByUserId || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collCartsRelatedByUserId) {
                    $this->initCartsRelatedByUserId();
                } else {
                    $collectionClassName = CartTableMap::getTableMap()->getCollectionClassName();

                    $collCartsRelatedByUserId = new $collectionClassName;
                    $collCartsRelatedByUserId->setModel('\Model\Cart');

                    return $collCartsRelatedByUserId;
                }
            } else {
                $collCartsRelatedByUserId = ChildCartQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCartsRelatedByUserIdPartial && count($collCartsRelatedByUserId)) {
                        $this->initCartsRelatedByUserId(false);

                        foreach ($collCartsRelatedByUserId as $obj) {
                            if (false == $this->collCartsRelatedByUserId->contains($obj)) {
                                $this->collCartsRelatedByUserId->append($obj);
                            }
                        }

                        $this->collCartsRelatedByUserIdPartial = true;
                    }

                    return $collCartsRelatedByUserId;
                }

                if ($partial && $this->collCartsRelatedByUserId) {
                    foreach ($this->collCartsRelatedByUserId as $obj) {
                        if ($obj->isNew()) {
                            $collCartsRelatedByUserId[] = $obj;
                        }
                    }
                }

                $this->collCartsRelatedByUserId = $collCartsRelatedByUserId;
                $this->collCartsRelatedByUserIdPartial = false;
            }
        }

        return $this->collCartsRelatedByUserId;
    }

    /**
     * Sets a collection of ChildCart objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $cartsRelatedByUserId A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setCartsRelatedByUserId(Collection $cartsRelatedByUserId, ?ConnectionInterface $con = null)
    {
        /** @var ChildCart[] $cartsRelatedByUserIdToDelete */
        $cartsRelatedByUserIdToDelete = $this->getCartsRelatedByUserId(new Criteria(), $con)->diff($cartsRelatedByUserId);


        $this->cartsRelatedByUserIdScheduledForDeletion = $cartsRelatedByUserIdToDelete;

        foreach ($cartsRelatedByUserIdToDelete as $cartRelatedByUserIdRemoved) {
            $cartRelatedByUserIdRemoved->setUser(null);
        }

        $this->collCartsRelatedByUserId = null;
        foreach ($cartsRelatedByUserId as $cartRelatedByUserId) {
            $this->addCartRelatedByUserId($cartRelatedByUserId);
        }

        $this->collCartsRelatedByUserId = $cartsRelatedByUserId;
        $this->collCartsRelatedByUserIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Cart objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Cart objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countCartsRelatedByUserId(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collCartsRelatedByUserIdPartial && !$this->isNew();
        if (null === $this->collCartsRelatedByUserId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCartsRelatedByUserId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCartsRelatedByUserId());
            }

            $query = ChildCartQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collCartsRelatedByUserId);
    }

    /**
     * Method called to associate a ChildCart object to this object
     * through the ChildCart foreign key attribute.
     *
     * @param ChildCart $l ChildCart
     * @return $this The current object (for fluent API support)
     */
    public function addCartRelatedByUserId(ChildCart $l)
    {
        if ($this->collCartsRelatedByUserId === null) {
            $this->initCartsRelatedByUserId();
            $this->collCartsRelatedByUserIdPartial = true;
        }

        if (!$this->collCartsRelatedByUserId->contains($l)) {
            $this->doAddCartRelatedByUserId($l);

            if ($this->cartsRelatedByUserIdScheduledForDeletion and $this->cartsRelatedByUserIdScheduledForDeletion->contains($l)) {
                $this->cartsRelatedByUserIdScheduledForDeletion->remove($this->cartsRelatedByUserIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildCart $cartRelatedByUserId The ChildCart object to add.
     */
    protected function doAddCartRelatedByUserId(ChildCart $cartRelatedByUserId): void
    {
        $this->collCartsRelatedByUserId[]= $cartRelatedByUserId;
        $cartRelatedByUserId->setUser($this);
    }

    /**
     * @param ChildCart $cartRelatedByUserId The ChildCart object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeCartRelatedByUserId(ChildCart $cartRelatedByUserId)
    {
        if ($this->getCartsRelatedByUserId()->contains($cartRelatedByUserId)) {
            $pos = $this->collCartsRelatedByUserId->search($cartRelatedByUserId);
            $this->collCartsRelatedByUserId->remove($pos);
            if (null === $this->cartsRelatedByUserIdScheduledForDeletion) {
                $this->cartsRelatedByUserIdScheduledForDeletion = clone $this->collCartsRelatedByUserId;
                $this->cartsRelatedByUserIdScheduledForDeletion->clear();
            }
            $this->cartsRelatedByUserIdScheduledForDeletion[]= $cartRelatedByUserId;
            $cartRelatedByUserId->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related CartsRelatedByUserId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCart[] List of ChildCart objects
     * @phpstan-return ObjectCollection&\Traversable<ChildCart}> List of ChildCart objects
     */
    public function getCartsRelatedByUserIdJoinSite(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCartQuery::create(null, $criteria);
        $query->joinWith('Site', $joinBehavior);

        return $this->getCartsRelatedByUserId($query, $con);
    }

    /**
     * Clears out the collCartsRelatedBySellerUserId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addCartsRelatedBySellerUserId()
     */
    public function clearCartsRelatedBySellerUserId()
    {
        $this->collCartsRelatedBySellerUserId = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collCartsRelatedBySellerUserId collection loaded partially.
     *
     * @return void
     */
    public function resetPartialCartsRelatedBySellerUserId($v = true): void
    {
        $this->collCartsRelatedBySellerUserIdPartial = $v;
    }

    /**
     * Initializes the collCartsRelatedBySellerUserId collection.
     *
     * By default this just sets the collCartsRelatedBySellerUserId collection to an empty array (like clearcollCartsRelatedBySellerUserId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCartsRelatedBySellerUserId(bool $overrideExisting = true): void
    {
        if (null !== $this->collCartsRelatedBySellerUserId && !$overrideExisting) {
            return;
        }

        $collectionClassName = CartTableMap::getTableMap()->getCollectionClassName();

        $this->collCartsRelatedBySellerUserId = new $collectionClassName;
        $this->collCartsRelatedBySellerUserId->setModel('\Model\Cart');
    }

    /**
     * Gets an array of ChildCart objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCart[] List of ChildCart objects
     * @phpstan-return ObjectCollection&\Traversable<ChildCart> List of ChildCart objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getCartsRelatedBySellerUserId(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collCartsRelatedBySellerUserIdPartial && !$this->isNew();
        if (null === $this->collCartsRelatedBySellerUserId || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collCartsRelatedBySellerUserId) {
                    $this->initCartsRelatedBySellerUserId();
                } else {
                    $collectionClassName = CartTableMap::getTableMap()->getCollectionClassName();

                    $collCartsRelatedBySellerUserId = new $collectionClassName;
                    $collCartsRelatedBySellerUserId->setModel('\Model\Cart');

                    return $collCartsRelatedBySellerUserId;
                }
            } else {
                $collCartsRelatedBySellerUserId = ChildCartQuery::create(null, $criteria)
                    ->filterBySellerUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCartsRelatedBySellerUserIdPartial && count($collCartsRelatedBySellerUserId)) {
                        $this->initCartsRelatedBySellerUserId(false);

                        foreach ($collCartsRelatedBySellerUserId as $obj) {
                            if (false == $this->collCartsRelatedBySellerUserId->contains($obj)) {
                                $this->collCartsRelatedBySellerUserId->append($obj);
                            }
                        }

                        $this->collCartsRelatedBySellerUserIdPartial = true;
                    }

                    return $collCartsRelatedBySellerUserId;
                }

                if ($partial && $this->collCartsRelatedBySellerUserId) {
                    foreach ($this->collCartsRelatedBySellerUserId as $obj) {
                        if ($obj->isNew()) {
                            $collCartsRelatedBySellerUserId[] = $obj;
                        }
                    }
                }

                $this->collCartsRelatedBySellerUserId = $collCartsRelatedBySellerUserId;
                $this->collCartsRelatedBySellerUserIdPartial = false;
            }
        }

        return $this->collCartsRelatedBySellerUserId;
    }

    /**
     * Sets a collection of ChildCart objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $cartsRelatedBySellerUserId A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setCartsRelatedBySellerUserId(Collection $cartsRelatedBySellerUserId, ?ConnectionInterface $con = null)
    {
        /** @var ChildCart[] $cartsRelatedBySellerUserIdToDelete */
        $cartsRelatedBySellerUserIdToDelete = $this->getCartsRelatedBySellerUserId(new Criteria(), $con)->diff($cartsRelatedBySellerUserId);


        $this->cartsRelatedBySellerUserIdScheduledForDeletion = $cartsRelatedBySellerUserIdToDelete;

        foreach ($cartsRelatedBySellerUserIdToDelete as $cartRelatedBySellerUserIdRemoved) {
            $cartRelatedBySellerUserIdRemoved->setSellerUser(null);
        }

        $this->collCartsRelatedBySellerUserId = null;
        foreach ($cartsRelatedBySellerUserId as $cartRelatedBySellerUserId) {
            $this->addCartRelatedBySellerUserId($cartRelatedBySellerUserId);
        }

        $this->collCartsRelatedBySellerUserId = $cartsRelatedBySellerUserId;
        $this->collCartsRelatedBySellerUserIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Cart objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Cart objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countCartsRelatedBySellerUserId(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collCartsRelatedBySellerUserIdPartial && !$this->isNew();
        if (null === $this->collCartsRelatedBySellerUserId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCartsRelatedBySellerUserId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCartsRelatedBySellerUserId());
            }

            $query = ChildCartQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySellerUser($this)
                ->count($con);
        }

        return count($this->collCartsRelatedBySellerUserId);
    }

    /**
     * Method called to associate a ChildCart object to this object
     * through the ChildCart foreign key attribute.
     *
     * @param ChildCart $l ChildCart
     * @return $this The current object (for fluent API support)
     */
    public function addCartRelatedBySellerUserId(ChildCart $l)
    {
        if ($this->collCartsRelatedBySellerUserId === null) {
            $this->initCartsRelatedBySellerUserId();
            $this->collCartsRelatedBySellerUserIdPartial = true;
        }

        if (!$this->collCartsRelatedBySellerUserId->contains($l)) {
            $this->doAddCartRelatedBySellerUserId($l);

            if ($this->cartsRelatedBySellerUserIdScheduledForDeletion and $this->cartsRelatedBySellerUserIdScheduledForDeletion->contains($l)) {
                $this->cartsRelatedBySellerUserIdScheduledForDeletion->remove($this->cartsRelatedBySellerUserIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildCart $cartRelatedBySellerUserId The ChildCart object to add.
     */
    protected function doAddCartRelatedBySellerUserId(ChildCart $cartRelatedBySellerUserId): void
    {
        $this->collCartsRelatedBySellerUserId[]= $cartRelatedBySellerUserId;
        $cartRelatedBySellerUserId->setSellerUser($this);
    }

    /**
     * @param ChildCart $cartRelatedBySellerUserId The ChildCart object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeCartRelatedBySellerUserId(ChildCart $cartRelatedBySellerUserId)
    {
        if ($this->getCartsRelatedBySellerUserId()->contains($cartRelatedBySellerUserId)) {
            $pos = $this->collCartsRelatedBySellerUserId->search($cartRelatedBySellerUserId);
            $this->collCartsRelatedBySellerUserId->remove($pos);
            if (null === $this->cartsRelatedBySellerUserIdScheduledForDeletion) {
                $this->cartsRelatedBySellerUserIdScheduledForDeletion = clone $this->collCartsRelatedBySellerUserId;
                $this->cartsRelatedBySellerUserIdScheduledForDeletion->clear();
            }
            $this->cartsRelatedBySellerUserIdScheduledForDeletion[]= $cartRelatedBySellerUserId;
            $cartRelatedBySellerUserId->setSellerUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related CartsRelatedBySellerUserId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCart[] List of ChildCart objects
     * @phpstan-return ObjectCollection&\Traversable<ChildCart}> List of ChildCart objects
     */
    public function getCartsRelatedBySellerUserIdJoinSite(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCartQuery::create(null, $criteria);
        $query->joinWith('Site', $joinBehavior);

        return $this->getCartsRelatedBySellerUserId($query, $con);
    }

    /**
     * Clears out the collCoupons collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addCoupons()
     */
    public function clearCoupons()
    {
        $this->collCoupons = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collCoupons collection loaded partially.
     *
     * @return void
     */
    public function resetPartialCoupons($v = true): void
    {
        $this->collCouponsPartial = $v;
    }

    /**
     * Initializes the collCoupons collection.
     *
     * By default this just sets the collCoupons collection to an empty array (like clearcollCoupons());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCoupons(bool $overrideExisting = true): void
    {
        if (null !== $this->collCoupons && !$overrideExisting) {
            return;
        }

        $collectionClassName = CouponTableMap::getTableMap()->getCollectionClassName();

        $this->collCoupons = new $collectionClassName;
        $this->collCoupons->setModel('\Model\Coupon');
    }

    /**
     * Gets an array of ChildCoupon objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCoupon[] List of ChildCoupon objects
     * @phpstan-return ObjectCollection&\Traversable<ChildCoupon> List of ChildCoupon objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getCoupons(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collCouponsPartial && !$this->isNew();
        if (null === $this->collCoupons || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collCoupons) {
                    $this->initCoupons();
                } else {
                    $collectionClassName = CouponTableMap::getTableMap()->getCollectionClassName();

                    $collCoupons = new $collectionClassName;
                    $collCoupons->setModel('\Model\Coupon');

                    return $collCoupons;
                }
            } else {
                $collCoupons = ChildCouponQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCouponsPartial && count($collCoupons)) {
                        $this->initCoupons(false);

                        foreach ($collCoupons as $obj) {
                            if (false == $this->collCoupons->contains($obj)) {
                                $this->collCoupons->append($obj);
                            }
                        }

                        $this->collCouponsPartial = true;
                    }

                    return $collCoupons;
                }

                if ($partial && $this->collCoupons) {
                    foreach ($this->collCoupons as $obj) {
                        if ($obj->isNew()) {
                            $collCoupons[] = $obj;
                        }
                    }
                }

                $this->collCoupons = $collCoupons;
                $this->collCouponsPartial = false;
            }
        }

        return $this->collCoupons;
    }

    /**
     * Sets a collection of ChildCoupon objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $coupons A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setCoupons(Collection $coupons, ?ConnectionInterface $con = null)
    {
        /** @var ChildCoupon[] $couponsToDelete */
        $couponsToDelete = $this->getCoupons(new Criteria(), $con)->diff($coupons);


        $this->couponsScheduledForDeletion = $couponsToDelete;

        foreach ($couponsToDelete as $couponRemoved) {
            $couponRemoved->setUser(null);
        }

        $this->collCoupons = null;
        foreach ($coupons as $coupon) {
            $this->addCoupon($coupon);
        }

        $this->collCoupons = $coupons;
        $this->collCouponsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Coupon objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Coupon objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countCoupons(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collCouponsPartial && !$this->isNew();
        if (null === $this->collCoupons || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCoupons) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCoupons());
            }

            $query = ChildCouponQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collCoupons);
    }

    /**
     * Method called to associate a ChildCoupon object to this object
     * through the ChildCoupon foreign key attribute.
     *
     * @param ChildCoupon $l ChildCoupon
     * @return $this The current object (for fluent API support)
     */
    public function addCoupon(ChildCoupon $l)
    {
        if ($this->collCoupons === null) {
            $this->initCoupons();
            $this->collCouponsPartial = true;
        }

        if (!$this->collCoupons->contains($l)) {
            $this->doAddCoupon($l);

            if ($this->couponsScheduledForDeletion and $this->couponsScheduledForDeletion->contains($l)) {
                $this->couponsScheduledForDeletion->remove($this->couponsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildCoupon $coupon The ChildCoupon object to add.
     */
    protected function doAddCoupon(ChildCoupon $coupon): void
    {
        $this->collCoupons[]= $coupon;
        $coupon->setUser($this);
    }

    /**
     * @param ChildCoupon $coupon The ChildCoupon object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeCoupon(ChildCoupon $coupon)
    {
        if ($this->getCoupons()->contains($coupon)) {
            $pos = $this->collCoupons->search($coupon);
            $this->collCoupons->remove($pos);
            if (null === $this->couponsScheduledForDeletion) {
                $this->couponsScheduledForDeletion = clone $this->collCoupons;
                $this->couponsScheduledForDeletion->clear();
            }
            $this->couponsScheduledForDeletion[]= $coupon;
            $coupon->setUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collCustomers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addCustomers()
     */
    public function clearCustomers()
    {
        $this->collCustomers = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collCustomers collection loaded partially.
     *
     * @return void
     */
    public function resetPartialCustomers($v = true): void
    {
        $this->collCustomersPartial = $v;
    }

    /**
     * Initializes the collCustomers collection.
     *
     * By default this just sets the collCustomers collection to an empty array (like clearcollCustomers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCustomers(bool $overrideExisting = true): void
    {
        if (null !== $this->collCustomers && !$overrideExisting) {
            return;
        }

        $collectionClassName = CustomerTableMap::getTableMap()->getCollectionClassName();

        $this->collCustomers = new $collectionClassName;
        $this->collCustomers->setModel('\Model\Customer');
    }

    /**
     * Gets an array of ChildCustomer objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCustomer[] List of ChildCustomer objects
     * @phpstan-return ObjectCollection&\Traversable<ChildCustomer> List of ChildCustomer objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getCustomers(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collCustomersPartial && !$this->isNew();
        if (null === $this->collCustomers || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collCustomers) {
                    $this->initCustomers();
                } else {
                    $collectionClassName = CustomerTableMap::getTableMap()->getCollectionClassName();

                    $collCustomers = new $collectionClassName;
                    $collCustomers->setModel('\Model\Customer');

                    return $collCustomers;
                }
            } else {
                $collCustomers = ChildCustomerQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCustomersPartial && count($collCustomers)) {
                        $this->initCustomers(false);

                        foreach ($collCustomers as $obj) {
                            if (false == $this->collCustomers->contains($obj)) {
                                $this->collCustomers->append($obj);
                            }
                        }

                        $this->collCustomersPartial = true;
                    }

                    return $collCustomers;
                }

                if ($partial && $this->collCustomers) {
                    foreach ($this->collCustomers as $obj) {
                        if ($obj->isNew()) {
                            $collCustomers[] = $obj;
                        }
                    }
                }

                $this->collCustomers = $collCustomers;
                $this->collCustomersPartial = false;
            }
        }

        return $this->collCustomers;
    }

    /**
     * Sets a collection of ChildCustomer objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $customers A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setCustomers(Collection $customers, ?ConnectionInterface $con = null)
    {
        /** @var ChildCustomer[] $customersToDelete */
        $customersToDelete = $this->getCustomers(new Criteria(), $con)->diff($customers);


        $this->customersScheduledForDeletion = $customersToDelete;

        foreach ($customersToDelete as $customerRemoved) {
            $customerRemoved->setUser(null);
        }

        $this->collCustomers = null;
        foreach ($customers as $customer) {
            $this->addCustomer($customer);
        }

        $this->collCustomers = $customers;
        $this->collCustomersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Customer objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Customer objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countCustomers(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collCustomersPartial && !$this->isNew();
        if (null === $this->collCustomers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCustomers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCustomers());
            }

            $query = ChildCustomerQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collCustomers);
    }

    /**
     * Method called to associate a ChildCustomer object to this object
     * through the ChildCustomer foreign key attribute.
     *
     * @param ChildCustomer $l ChildCustomer
     * @return $this The current object (for fluent API support)
     */
    public function addCustomer(ChildCustomer $l)
    {
        if ($this->collCustomers === null) {
            $this->initCustomers();
            $this->collCustomersPartial = true;
        }

        if (!$this->collCustomers->contains($l)) {
            $this->doAddCustomer($l);

            if ($this->customersScheduledForDeletion and $this->customersScheduledForDeletion->contains($l)) {
                $this->customersScheduledForDeletion->remove($this->customersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildCustomer $customer The ChildCustomer object to add.
     */
    protected function doAddCustomer(ChildCustomer $customer): void
    {
        $this->collCustomers[]= $customer;
        $customer->setUser($this);
    }

    /**
     * @param ChildCustomer $customer The ChildCustomer object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeCustomer(ChildCustomer $customer)
    {
        if ($this->getCustomers()->contains($customer)) {
            $pos = $this->collCustomers->search($customer);
            $this->collCustomers->remove($pos);
            if (null === $this->customersScheduledForDeletion) {
                $this->customersScheduledForDeletion = clone $this->collCustomers;
                $this->customersScheduledForDeletion->clear();
            }
            $this->customersScheduledForDeletion[]= $customer;
            $customer->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Customers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCustomer[] List of ChildCustomer objects
     * @phpstan-return ObjectCollection&\Traversable<ChildCustomer}> List of ChildCustomer objects
     */
    public function getCustomersJoinSite(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCustomerQuery::create(null, $criteria);
        $query->joinWith('Site', $joinBehavior);

        return $this->getCustomers($query, $con);
    }

    /**
     * Clears out the collDownloads collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addDownloads()
     */
    public function clearDownloads()
    {
        $this->collDownloads = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collDownloads collection loaded partially.
     *
     * @return void
     */
    public function resetPartialDownloads($v = true): void
    {
        $this->collDownloadsPartial = $v;
    }

    /**
     * Initializes the collDownloads collection.
     *
     * By default this just sets the collDownloads collection to an empty array (like clearcollDownloads());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initDownloads(bool $overrideExisting = true): void
    {
        if (null !== $this->collDownloads && !$overrideExisting) {
            return;
        }

        $collectionClassName = DownloadTableMap::getTableMap()->getCollectionClassName();

        $this->collDownloads = new $collectionClassName;
        $this->collDownloads->setModel('\Model\Download');
    }

    /**
     * Gets an array of ChildDownload objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildDownload[] List of ChildDownload objects
     * @phpstan-return ObjectCollection&\Traversable<ChildDownload> List of ChildDownload objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getDownloads(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collDownloadsPartial && !$this->isNew();
        if (null === $this->collDownloads || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collDownloads) {
                    $this->initDownloads();
                } else {
                    $collectionClassName = DownloadTableMap::getTableMap()->getCollectionClassName();

                    $collDownloads = new $collectionClassName;
                    $collDownloads->setModel('\Model\Download');

                    return $collDownloads;
                }
            } else {
                $collDownloads = ChildDownloadQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collDownloadsPartial && count($collDownloads)) {
                        $this->initDownloads(false);

                        foreach ($collDownloads as $obj) {
                            if (false == $this->collDownloads->contains($obj)) {
                                $this->collDownloads->append($obj);
                            }
                        }

                        $this->collDownloadsPartial = true;
                    }

                    return $collDownloads;
                }

                if ($partial && $this->collDownloads) {
                    foreach ($this->collDownloads as $obj) {
                        if ($obj->isNew()) {
                            $collDownloads[] = $obj;
                        }
                    }
                }

                $this->collDownloads = $collDownloads;
                $this->collDownloadsPartial = false;
            }
        }

        return $this->collDownloads;
    }

    /**
     * Sets a collection of ChildDownload objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $downloads A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setDownloads(Collection $downloads, ?ConnectionInterface $con = null)
    {
        /** @var ChildDownload[] $downloadsToDelete */
        $downloadsToDelete = $this->getDownloads(new Criteria(), $con)->diff($downloads);


        $this->downloadsScheduledForDeletion = $downloadsToDelete;

        foreach ($downloadsToDelete as $downloadRemoved) {
            $downloadRemoved->setUser(null);
        }

        $this->collDownloads = null;
        foreach ($downloads as $download) {
            $this->addDownload($download);
        }

        $this->collDownloads = $downloads;
        $this->collDownloadsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Download objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Download objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countDownloads(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collDownloadsPartial && !$this->isNew();
        if (null === $this->collDownloads || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collDownloads) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getDownloads());
            }

            $query = ChildDownloadQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collDownloads);
    }

    /**
     * Method called to associate a ChildDownload object to this object
     * through the ChildDownload foreign key attribute.
     *
     * @param ChildDownload $l ChildDownload
     * @return $this The current object (for fluent API support)
     */
    public function addDownload(ChildDownload $l)
    {
        if ($this->collDownloads === null) {
            $this->initDownloads();
            $this->collDownloadsPartial = true;
        }

        if (!$this->collDownloads->contains($l)) {
            $this->doAddDownload($l);

            if ($this->downloadsScheduledForDeletion and $this->downloadsScheduledForDeletion->contains($l)) {
                $this->downloadsScheduledForDeletion->remove($this->downloadsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildDownload $download The ChildDownload object to add.
     */
    protected function doAddDownload(ChildDownload $download): void
    {
        $this->collDownloads[]= $download;
        $download->setUser($this);
    }

    /**
     * @param ChildDownload $download The ChildDownload object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeDownload(ChildDownload $download)
    {
        if ($this->getDownloads()->contains($download)) {
            $pos = $this->collDownloads->search($download);
            $this->collDownloads->remove($pos);
            if (null === $this->downloadsScheduledForDeletion) {
                $this->downloadsScheduledForDeletion = clone $this->collDownloads;
                $this->downloadsScheduledForDeletion->clear();
            }
            $this->downloadsScheduledForDeletion[]= $download;
            $download->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Downloads from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildDownload[] List of ChildDownload objects
     * @phpstan-return ObjectCollection&\Traversable<ChildDownload}> List of ChildDownload objects
     */
    public function getDownloadsJoinSite(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildDownloadQuery::create(null, $criteria);
        $query->joinWith('Site', $joinBehavior);

        return $this->getDownloads($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Downloads from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildDownload[] List of ChildDownload objects
     * @phpstan-return ObjectCollection&\Traversable<ChildDownload}> List of ChildDownload objects
     */
    public function getDownloadsJoinFile(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildDownloadQuery::create(null, $criteria);
        $query->joinWith('File', $joinBehavior);

        return $this->getDownloads($query, $con);
    }

    /**
     * Clears out the collFiles collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addFiles()
     */
    public function clearFiles()
    {
        $this->collFiles = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collFiles collection loaded partially.
     *
     * @return void
     */
    public function resetPartialFiles($v = true): void
    {
        $this->collFilesPartial = $v;
    }

    /**
     * Initializes the collFiles collection.
     *
     * By default this just sets the collFiles collection to an empty array (like clearcollFiles());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFiles(bool $overrideExisting = true): void
    {
        if (null !== $this->collFiles && !$overrideExisting) {
            return;
        }

        $collectionClassName = FileTableMap::getTableMap()->getCollectionClassName();

        $this->collFiles = new $collectionClassName;
        $this->collFiles->setModel('\Model\File');
    }

    /**
     * Gets an array of ChildFile objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildFile[] List of ChildFile objects
     * @phpstan-return ObjectCollection&\Traversable<ChildFile> List of ChildFile objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getFiles(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collFilesPartial && !$this->isNew();
        if (null === $this->collFiles || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collFiles) {
                    $this->initFiles();
                } else {
                    $collectionClassName = FileTableMap::getTableMap()->getCollectionClassName();

                    $collFiles = new $collectionClassName;
                    $collFiles->setModel('\Model\File');

                    return $collFiles;
                }
            } else {
                $collFiles = ChildFileQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collFilesPartial && count($collFiles)) {
                        $this->initFiles(false);

                        foreach ($collFiles as $obj) {
                            if (false == $this->collFiles->contains($obj)) {
                                $this->collFiles->append($obj);
                            }
                        }

                        $this->collFilesPartial = true;
                    }

                    return $collFiles;
                }

                if ($partial && $this->collFiles) {
                    foreach ($this->collFiles as $obj) {
                        if ($obj->isNew()) {
                            $collFiles[] = $obj;
                        }
                    }
                }

                $this->collFiles = $collFiles;
                $this->collFilesPartial = false;
            }
        }

        return $this->collFiles;
    }

    /**
     * Sets a collection of ChildFile objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $files A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setFiles(Collection $files, ?ConnectionInterface $con = null)
    {
        /** @var ChildFile[] $filesToDelete */
        $filesToDelete = $this->getFiles(new Criteria(), $con)->diff($files);


        $this->filesScheduledForDeletion = $filesToDelete;

        foreach ($filesToDelete as $fileRemoved) {
            $fileRemoved->setUser(null);
        }

        $this->collFiles = null;
        foreach ($files as $file) {
            $this->addFile($file);
        }

        $this->collFiles = $files;
        $this->collFilesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related File objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related File objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countFiles(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collFilesPartial && !$this->isNew();
        if (null === $this->collFiles || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFiles) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getFiles());
            }

            $query = ChildFileQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collFiles);
    }

    /**
     * Method called to associate a ChildFile object to this object
     * through the ChildFile foreign key attribute.
     *
     * @param ChildFile $l ChildFile
     * @return $this The current object (for fluent API support)
     */
    public function addFile(ChildFile $l)
    {
        if ($this->collFiles === null) {
            $this->initFiles();
            $this->collFilesPartial = true;
        }

        if (!$this->collFiles->contains($l)) {
            $this->doAddFile($l);

            if ($this->filesScheduledForDeletion and $this->filesScheduledForDeletion->contains($l)) {
                $this->filesScheduledForDeletion->remove($this->filesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildFile $file The ChildFile object to add.
     */
    protected function doAddFile(ChildFile $file): void
    {
        $this->collFiles[]= $file;
        $file->setUser($this);
    }

    /**
     * @param ChildFile $file The ChildFile object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeFile(ChildFile $file)
    {
        if ($this->getFiles()->contains($file)) {
            $pos = $this->collFiles->search($file);
            $this->collFiles->remove($pos);
            if (null === $this->filesScheduledForDeletion) {
                $this->filesScheduledForDeletion = clone $this->collFiles;
                $this->filesScheduledForDeletion->clear();
            }
            $this->filesScheduledForDeletion[]= $file;
            $file->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Files from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildFile[] List of ChildFile objects
     * @phpstan-return ObjectCollection&\Traversable<ChildFile}> List of ChildFile objects
     */
    public function getFilesJoinSite(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildFileQuery::create(null, $criteria);
        $query->joinWith('Site', $joinBehavior);

        return $this->getFiles($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Files from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildFile[] List of ChildFile objects
     * @phpstan-return ObjectCollection&\Traversable<ChildFile}> List of ChildFile objects
     */
    public function getFilesJoinArticle(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildFileQuery::create(null, $criteria);
        $query->joinWith('Article', $joinBehavior);

        return $this->getFiles($query, $con);
    }

    /**
     * Clears out the collLinks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addLinks()
     */
    public function clearLinks()
    {
        $this->collLinks = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collLinks collection loaded partially.
     *
     * @return void
     */
    public function resetPartialLinks($v = true): void
    {
        $this->collLinksPartial = $v;
    }

    /**
     * Initializes the collLinks collection.
     *
     * By default this just sets the collLinks collection to an empty array (like clearcollLinks());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLinks(bool $overrideExisting = true): void
    {
        if (null !== $this->collLinks && !$overrideExisting) {
            return;
        }

        $collectionClassName = LinkTableMap::getTableMap()->getCollectionClassName();

        $this->collLinks = new $collectionClassName;
        $this->collLinks->setModel('\Model\Link');
    }

    /**
     * Gets an array of ChildLink objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildLink[] List of ChildLink objects
     * @phpstan-return ObjectCollection&\Traversable<ChildLink> List of ChildLink objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getLinks(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collLinksPartial && !$this->isNew();
        if (null === $this->collLinks || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collLinks) {
                    $this->initLinks();
                } else {
                    $collectionClassName = LinkTableMap::getTableMap()->getCollectionClassName();

                    $collLinks = new $collectionClassName;
                    $collLinks->setModel('\Model\Link');

                    return $collLinks;
                }
            } else {
                $collLinks = ChildLinkQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collLinksPartial && count($collLinks)) {
                        $this->initLinks(false);

                        foreach ($collLinks as $obj) {
                            if (false == $this->collLinks->contains($obj)) {
                                $this->collLinks->append($obj);
                            }
                        }

                        $this->collLinksPartial = true;
                    }

                    return $collLinks;
                }

                if ($partial && $this->collLinks) {
                    foreach ($this->collLinks as $obj) {
                        if ($obj->isNew()) {
                            $collLinks[] = $obj;
                        }
                    }
                }

                $this->collLinks = $collLinks;
                $this->collLinksPartial = false;
            }
        }

        return $this->collLinks;
    }

    /**
     * Sets a collection of ChildLink objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $links A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setLinks(Collection $links, ?ConnectionInterface $con = null)
    {
        /** @var ChildLink[] $linksToDelete */
        $linksToDelete = $this->getLinks(new Criteria(), $con)->diff($links);


        $this->linksScheduledForDeletion = $linksToDelete;

        foreach ($linksToDelete as $linkRemoved) {
            $linkRemoved->setUser(null);
        }

        $this->collLinks = null;
        foreach ($links as $link) {
            $this->addLink($link);
        }

        $this->collLinks = $links;
        $this->collLinksPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Link objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Link objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countLinks(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collLinksPartial && !$this->isNew();
        if (null === $this->collLinks || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLinks) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getLinks());
            }

            $query = ChildLinkQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collLinks);
    }

    /**
     * Method called to associate a ChildLink object to this object
     * through the ChildLink foreign key attribute.
     *
     * @param ChildLink $l ChildLink
     * @return $this The current object (for fluent API support)
     */
    public function addLink(ChildLink $l)
    {
        if ($this->collLinks === null) {
            $this->initLinks();
            $this->collLinksPartial = true;
        }

        if (!$this->collLinks->contains($l)) {
            $this->doAddLink($l);

            if ($this->linksScheduledForDeletion and $this->linksScheduledForDeletion->contains($l)) {
                $this->linksScheduledForDeletion->remove($this->linksScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildLink $link The ChildLink object to add.
     */
    protected function doAddLink(ChildLink $link): void
    {
        $this->collLinks[]= $link;
        $link->setUser($this);
    }

    /**
     * @param ChildLink $link The ChildLink object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeLink(ChildLink $link)
    {
        if ($this->getLinks()->contains($link)) {
            $pos = $this->collLinks->search($link);
            $this->collLinks->remove($pos);
            if (null === $this->linksScheduledForDeletion) {
                $this->linksScheduledForDeletion = clone $this->collLinks;
                $this->linksScheduledForDeletion->clear();
            }
            $this->linksScheduledForDeletion[]= $link;
            $link->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Links from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLink[] List of ChildLink objects
     * @phpstan-return ObjectCollection&\Traversable<ChildLink}> List of ChildLink objects
     */
    public function getLinksJoinArticle(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLinkQuery::create(null, $criteria);
        $query->joinWith('Article', $joinBehavior);

        return $this->getLinks($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Links from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLink[] List of ChildLink objects
     * @phpstan-return ObjectCollection&\Traversable<ChildLink}> List of ChildLink objects
     */
    public function getLinksJoinArticleCategory(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLinkQuery::create(null, $criteria);
        $query->joinWith('ArticleCategory', $joinBehavior);

        return $this->getLinks($query, $con);
    }

    /**
     * Clears out the collStockItemLists collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addStockItemLists()
     */
    public function clearStockItemLists()
    {
        $this->collStockItemLists = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collStockItemLists collection loaded partially.
     *
     * @return void
     */
    public function resetPartialStockItemLists($v = true): void
    {
        $this->collStockItemListsPartial = $v;
    }

    /**
     * Initializes the collStockItemLists collection.
     *
     * By default this just sets the collStockItemLists collection to an empty array (like clearcollStockItemLists());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initStockItemLists(bool $overrideExisting = true): void
    {
        if (null !== $this->collStockItemLists && !$overrideExisting) {
            return;
        }

        $collectionClassName = StockItemListTableMap::getTableMap()->getCollectionClassName();

        $this->collStockItemLists = new $collectionClassName;
        $this->collStockItemLists->setModel('\Model\StockItemList');
    }

    /**
     * Gets an array of ChildStockItemList objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildStockItemList[] List of ChildStockItemList objects
     * @phpstan-return ObjectCollection&\Traversable<ChildStockItemList> List of ChildStockItemList objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getStockItemLists(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collStockItemListsPartial && !$this->isNew();
        if (null === $this->collStockItemLists || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collStockItemLists) {
                    $this->initStockItemLists();
                } else {
                    $collectionClassName = StockItemListTableMap::getTableMap()->getCollectionClassName();

                    $collStockItemLists = new $collectionClassName;
                    $collStockItemLists->setModel('\Model\StockItemList');

                    return $collStockItemLists;
                }
            } else {
                $collStockItemLists = ChildStockItemListQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collStockItemListsPartial && count($collStockItemLists)) {
                        $this->initStockItemLists(false);

                        foreach ($collStockItemLists as $obj) {
                            if (false == $this->collStockItemLists->contains($obj)) {
                                $this->collStockItemLists->append($obj);
                            }
                        }

                        $this->collStockItemListsPartial = true;
                    }

                    return $collStockItemLists;
                }

                if ($partial && $this->collStockItemLists) {
                    foreach ($this->collStockItemLists as $obj) {
                        if ($obj->isNew()) {
                            $collStockItemLists[] = $obj;
                        }
                    }
                }

                $this->collStockItemLists = $collStockItemLists;
                $this->collStockItemListsPartial = false;
            }
        }

        return $this->collStockItemLists;
    }

    /**
     * Sets a collection of ChildStockItemList objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $stockItemLists A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setStockItemLists(Collection $stockItemLists, ?ConnectionInterface $con = null)
    {
        /** @var ChildStockItemList[] $stockItemListsToDelete */
        $stockItemListsToDelete = $this->getStockItemLists(new Criteria(), $con)->diff($stockItemLists);


        $this->stockItemListsScheduledForDeletion = $stockItemListsToDelete;

        foreach ($stockItemListsToDelete as $stockItemListRemoved) {
            $stockItemListRemoved->setUser(null);
        }

        $this->collStockItemLists = null;
        foreach ($stockItemLists as $stockItemList) {
            $this->addStockItemList($stockItemList);
        }

        $this->collStockItemLists = $stockItemLists;
        $this->collStockItemListsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related StockItemList objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related StockItemList objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countStockItemLists(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collStockItemListsPartial && !$this->isNew();
        if (null === $this->collStockItemLists || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collStockItemLists) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getStockItemLists());
            }

            $query = ChildStockItemListQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collStockItemLists);
    }

    /**
     * Method called to associate a ChildStockItemList object to this object
     * through the ChildStockItemList foreign key attribute.
     *
     * @param ChildStockItemList $l ChildStockItemList
     * @return $this The current object (for fluent API support)
     */
    public function addStockItemList(ChildStockItemList $l)
    {
        if ($this->collStockItemLists === null) {
            $this->initStockItemLists();
            $this->collStockItemListsPartial = true;
        }

        if (!$this->collStockItemLists->contains($l)) {
            $this->doAddStockItemList($l);

            if ($this->stockItemListsScheduledForDeletion and $this->stockItemListsScheduledForDeletion->contains($l)) {
                $this->stockItemListsScheduledForDeletion->remove($this->stockItemListsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildStockItemList $stockItemList The ChildStockItemList object to add.
     */
    protected function doAddStockItemList(ChildStockItemList $stockItemList): void
    {
        $this->collStockItemLists[]= $stockItemList;
        $stockItemList->setUser($this);
    }

    /**
     * @param ChildStockItemList $stockItemList The ChildStockItemList object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeStockItemList(ChildStockItemList $stockItemList)
    {
        if ($this->getStockItemLists()->contains($stockItemList)) {
            $pos = $this->collStockItemLists->search($stockItemList);
            $this->collStockItemLists->remove($pos);
            if (null === $this->stockItemListsScheduledForDeletion) {
                $this->stockItemListsScheduledForDeletion = clone $this->collStockItemLists;
                $this->stockItemListsScheduledForDeletion->clear();
            }
            $this->stockItemListsScheduledForDeletion[]= $stockItemList;
            $stockItemList->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related StockItemLists from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildStockItemList[] List of ChildStockItemList objects
     * @phpstan-return ObjectCollection&\Traversable<ChildStockItemList}> List of ChildStockItemList objects
     */
    public function getStockItemListsJoinSite(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildStockItemListQuery::create(null, $criteria);
        $query->joinWith('Site', $joinBehavior);

        return $this->getStockItemLists($query, $con);
    }

    /**
     * Clears out the collOptions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addOptions()
     */
    public function clearOptions()
    {
        $this->collOptions = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collOptions collection loaded partially.
     *
     * @return void
     */
    public function resetPartialOptions($v = true): void
    {
        $this->collOptionsPartial = $v;
    }

    /**
     * Initializes the collOptions collection.
     *
     * By default this just sets the collOptions collection to an empty array (like clearcollOptions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initOptions(bool $overrideExisting = true): void
    {
        if (null !== $this->collOptions && !$overrideExisting) {
            return;
        }

        $collectionClassName = OptionTableMap::getTableMap()->getCollectionClassName();

        $this->collOptions = new $collectionClassName;
        $this->collOptions->setModel('\Model\Option');
    }

    /**
     * Gets an array of ChildOption objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildOption[] List of ChildOption objects
     * @phpstan-return ObjectCollection&\Traversable<ChildOption> List of ChildOption objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getOptions(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collOptionsPartial && !$this->isNew();
        if (null === $this->collOptions || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collOptions) {
                    $this->initOptions();
                } else {
                    $collectionClassName = OptionTableMap::getTableMap()->getCollectionClassName();

                    $collOptions = new $collectionClassName;
                    $collOptions->setModel('\Model\Option');

                    return $collOptions;
                }
            } else {
                $collOptions = ChildOptionQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collOptionsPartial && count($collOptions)) {
                        $this->initOptions(false);

                        foreach ($collOptions as $obj) {
                            if (false == $this->collOptions->contains($obj)) {
                                $this->collOptions->append($obj);
                            }
                        }

                        $this->collOptionsPartial = true;
                    }

                    return $collOptions;
                }

                if ($partial && $this->collOptions) {
                    foreach ($this->collOptions as $obj) {
                        if ($obj->isNew()) {
                            $collOptions[] = $obj;
                        }
                    }
                }

                $this->collOptions = $collOptions;
                $this->collOptionsPartial = false;
            }
        }

        return $this->collOptions;
    }

    /**
     * Sets a collection of ChildOption objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $options A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setOptions(Collection $options, ?ConnectionInterface $con = null)
    {
        /** @var ChildOption[] $optionsToDelete */
        $optionsToDelete = $this->getOptions(new Criteria(), $con)->diff($options);


        $this->optionsScheduledForDeletion = $optionsToDelete;

        foreach ($optionsToDelete as $optionRemoved) {
            $optionRemoved->setUser(null);
        }

        $this->collOptions = null;
        foreach ($options as $option) {
            $this->addOption($option);
        }

        $this->collOptions = $options;
        $this->collOptionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Option objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Option objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countOptions(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collOptionsPartial && !$this->isNew();
        if (null === $this->collOptions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collOptions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getOptions());
            }

            $query = ChildOptionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collOptions);
    }

    /**
     * Method called to associate a ChildOption object to this object
     * through the ChildOption foreign key attribute.
     *
     * @param ChildOption $l ChildOption
     * @return $this The current object (for fluent API support)
     */
    public function addOption(ChildOption $l)
    {
        if ($this->collOptions === null) {
            $this->initOptions();
            $this->collOptionsPartial = true;
        }

        if (!$this->collOptions->contains($l)) {
            $this->doAddOption($l);

            if ($this->optionsScheduledForDeletion and $this->optionsScheduledForDeletion->contains($l)) {
                $this->optionsScheduledForDeletion->remove($this->optionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildOption $option The ChildOption object to add.
     */
    protected function doAddOption(ChildOption $option): void
    {
        $this->collOptions[]= $option;
        $option->setUser($this);
    }

    /**
     * @param ChildOption $option The ChildOption object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeOption(ChildOption $option)
    {
        if ($this->getOptions()->contains($option)) {
            $pos = $this->collOptions->search($option);
            $this->collOptions->remove($pos);
            if (null === $this->optionsScheduledForDeletion) {
                $this->optionsScheduledForDeletion = clone $this->collOptions;
                $this->optionsScheduledForDeletion->clear();
            }
            $this->optionsScheduledForDeletion[]= $option;
            $option->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Options from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOption[] List of ChildOption objects
     * @phpstan-return ObjectCollection&\Traversable<ChildOption}> List of ChildOption objects
     */
    public function getOptionsJoinSite(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOptionQuery::create(null, $criteria);
        $query->joinWith('Site', $joinBehavior);

        return $this->getOptions($query, $con);
    }

    /**
     * Clears out the collOrders collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addOrders()
     */
    public function clearOrders()
    {
        $this->collOrders = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collOrders collection loaded partially.
     *
     * @return void
     */
    public function resetPartialOrders($v = true): void
    {
        $this->collOrdersPartial = $v;
    }

    /**
     * Initializes the collOrders collection.
     *
     * By default this just sets the collOrders collection to an empty array (like clearcollOrders());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initOrders(bool $overrideExisting = true): void
    {
        if (null !== $this->collOrders && !$overrideExisting) {
            return;
        }

        $collectionClassName = OrderTableMap::getTableMap()->getCollectionClassName();

        $this->collOrders = new $collectionClassName;
        $this->collOrders->setModel('\Model\Order');
    }

    /**
     * Gets an array of ChildOrder objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildOrder[] List of ChildOrder objects
     * @phpstan-return ObjectCollection&\Traversable<ChildOrder> List of ChildOrder objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getOrders(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collOrdersPartial && !$this->isNew();
        if (null === $this->collOrders || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collOrders) {
                    $this->initOrders();
                } else {
                    $collectionClassName = OrderTableMap::getTableMap()->getCollectionClassName();

                    $collOrders = new $collectionClassName;
                    $collOrders->setModel('\Model\Order');

                    return $collOrders;
                }
            } else {
                $collOrders = ChildOrderQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collOrdersPartial && count($collOrders)) {
                        $this->initOrders(false);

                        foreach ($collOrders as $obj) {
                            if (false == $this->collOrders->contains($obj)) {
                                $this->collOrders->append($obj);
                            }
                        }

                        $this->collOrdersPartial = true;
                    }

                    return $collOrders;
                }

                if ($partial && $this->collOrders) {
                    foreach ($this->collOrders as $obj) {
                        if ($obj->isNew()) {
                            $collOrders[] = $obj;
                        }
                    }
                }

                $this->collOrders = $collOrders;
                $this->collOrdersPartial = false;
            }
        }

        return $this->collOrders;
    }

    /**
     * Sets a collection of ChildOrder objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $orders A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setOrders(Collection $orders, ?ConnectionInterface $con = null)
    {
        /** @var ChildOrder[] $ordersToDelete */
        $ordersToDelete = $this->getOrders(new Criteria(), $con)->diff($orders);


        $this->ordersScheduledForDeletion = $ordersToDelete;

        foreach ($ordersToDelete as $orderRemoved) {
            $orderRemoved->setUser(null);
        }

        $this->collOrders = null;
        foreach ($orders as $order) {
            $this->addOrder($order);
        }

        $this->collOrders = $orders;
        $this->collOrdersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Order objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Order objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countOrders(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collOrdersPartial && !$this->isNew();
        if (null === $this->collOrders || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collOrders) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getOrders());
            }

            $query = ChildOrderQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collOrders);
    }

    /**
     * Method called to associate a ChildOrder object to this object
     * through the ChildOrder foreign key attribute.
     *
     * @param ChildOrder $l ChildOrder
     * @return $this The current object (for fluent API support)
     */
    public function addOrder(ChildOrder $l)
    {
        if ($this->collOrders === null) {
            $this->initOrders();
            $this->collOrdersPartial = true;
        }

        if (!$this->collOrders->contains($l)) {
            $this->doAddOrder($l);

            if ($this->ordersScheduledForDeletion and $this->ordersScheduledForDeletion->contains($l)) {
                $this->ordersScheduledForDeletion->remove($this->ordersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildOrder $order The ChildOrder object to add.
     */
    protected function doAddOrder(ChildOrder $order): void
    {
        $this->collOrders[]= $order;
        $order->setUser($this);
    }

    /**
     * @param ChildOrder $order The ChildOrder object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeOrder(ChildOrder $order)
    {
        if ($this->getOrders()->contains($order)) {
            $pos = $this->collOrders->search($order);
            $this->collOrders->remove($pos);
            if (null === $this->ordersScheduledForDeletion) {
                $this->ordersScheduledForDeletion = clone $this->collOrders;
                $this->ordersScheduledForDeletion->clear();
            }
            $this->ordersScheduledForDeletion[]= $order;
            $order->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Orders from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOrder[] List of ChildOrder objects
     * @phpstan-return ObjectCollection&\Traversable<ChildOrder}> List of ChildOrder objects
     */
    public function getOrdersJoinCustomer(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOrderQuery::create(null, $criteria);
        $query->joinWith('Customer', $joinBehavior);

        return $this->getOrders($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Orders from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOrder[] List of ChildOrder objects
     * @phpstan-return ObjectCollection&\Traversable<ChildOrder}> List of ChildOrder objects
     */
    public function getOrdersJoinShippingOption(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOrderQuery::create(null, $criteria);
        $query->joinWith('ShippingOption', $joinBehavior);

        return $this->getOrders($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Orders from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOrder[] List of ChildOrder objects
     * @phpstan-return ObjectCollection&\Traversable<ChildOrder}> List of ChildOrder objects
     */
    public function getOrdersJoinCountry(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOrderQuery::create(null, $criteria);
        $query->joinWith('Country', $joinBehavior);

        return $this->getOrders($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Orders from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildOrder[] List of ChildOrder objects
     * @phpstan-return ObjectCollection&\Traversable<ChildOrder}> List of ChildOrder objects
     */
    public function getOrdersJoinSite(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildOrderQuery::create(null, $criteria);
        $query->joinWith('Site', $joinBehavior);

        return $this->getOrders($query, $con);
    }

    /**
     * Clears out the collPermissions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addPermissions()
     */
    public function clearPermissions()
    {
        $this->collPermissions = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collPermissions collection loaded partially.
     *
     * @return void
     */
    public function resetPartialPermissions($v = true): void
    {
        $this->collPermissionsPartial = $v;
    }

    /**
     * Initializes the collPermissions collection.
     *
     * By default this just sets the collPermissions collection to an empty array (like clearcollPermissions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPermissions(bool $overrideExisting = true): void
    {
        if (null !== $this->collPermissions && !$overrideExisting) {
            return;
        }

        $collectionClassName = PermissionTableMap::getTableMap()->getCollectionClassName();

        $this->collPermissions = new $collectionClassName;
        $this->collPermissions->setModel('\Model\Permission');
    }

    /**
     * Gets an array of ChildPermission objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPermission[] List of ChildPermission objects
     * @phpstan-return ObjectCollection&\Traversable<ChildPermission> List of ChildPermission objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getPermissions(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collPermissionsPartial && !$this->isNew();
        if (null === $this->collPermissions || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collPermissions) {
                    $this->initPermissions();
                } else {
                    $collectionClassName = PermissionTableMap::getTableMap()->getCollectionClassName();

                    $collPermissions = new $collectionClassName;
                    $collPermissions->setModel('\Model\Permission');

                    return $collPermissions;
                }
            } else {
                $collPermissions = ChildPermissionQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPermissionsPartial && count($collPermissions)) {
                        $this->initPermissions(false);

                        foreach ($collPermissions as $obj) {
                            if (false == $this->collPermissions->contains($obj)) {
                                $this->collPermissions->append($obj);
                            }
                        }

                        $this->collPermissionsPartial = true;
                    }

                    return $collPermissions;
                }

                if ($partial && $this->collPermissions) {
                    foreach ($this->collPermissions as $obj) {
                        if ($obj->isNew()) {
                            $collPermissions[] = $obj;
                        }
                    }
                }

                $this->collPermissions = $collPermissions;
                $this->collPermissionsPartial = false;
            }
        }

        return $this->collPermissions;
    }

    /**
     * Sets a collection of ChildPermission objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $permissions A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setPermissions(Collection $permissions, ?ConnectionInterface $con = null)
    {
        /** @var ChildPermission[] $permissionsToDelete */
        $permissionsToDelete = $this->getPermissions(new Criteria(), $con)->diff($permissions);


        $this->permissionsScheduledForDeletion = $permissionsToDelete;

        foreach ($permissionsToDelete as $permissionRemoved) {
            $permissionRemoved->setUser(null);
        }

        $this->collPermissions = null;
        foreach ($permissions as $permission) {
            $this->addPermission($permission);
        }

        $this->collPermissions = $permissions;
        $this->collPermissionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Permission objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Permission objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countPermissions(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collPermissionsPartial && !$this->isNew();
        if (null === $this->collPermissions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPermissions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPermissions());
            }

            $query = ChildPermissionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collPermissions);
    }

    /**
     * Method called to associate a ChildPermission object to this object
     * through the ChildPermission foreign key attribute.
     *
     * @param ChildPermission $l ChildPermission
     * @return $this The current object (for fluent API support)
     */
    public function addPermission(ChildPermission $l)
    {
        if ($this->collPermissions === null) {
            $this->initPermissions();
            $this->collPermissionsPartial = true;
        }

        if (!$this->collPermissions->contains($l)) {
            $this->doAddPermission($l);

            if ($this->permissionsScheduledForDeletion and $this->permissionsScheduledForDeletion->contains($l)) {
                $this->permissionsScheduledForDeletion->remove($this->permissionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPermission $permission The ChildPermission object to add.
     */
    protected function doAddPermission(ChildPermission $permission): void
    {
        $this->collPermissions[]= $permission;
        $permission->setUser($this);
    }

    /**
     * @param ChildPermission $permission The ChildPermission object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removePermission(ChildPermission $permission)
    {
        if ($this->getPermissions()->contains($permission)) {
            $pos = $this->collPermissions->search($permission);
            $this->collPermissions->remove($pos);
            if (null === $this->permissionsScheduledForDeletion) {
                $this->permissionsScheduledForDeletion = clone $this->collPermissions;
                $this->permissionsScheduledForDeletion->clear();
            }
            $this->permissionsScheduledForDeletion[]= $permission;
            $permission->setUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collPosts collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addPosts()
     */
    public function clearPosts()
    {
        $this->collPosts = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collPosts collection loaded partially.
     *
     * @return void
     */
    public function resetPartialPosts($v = true): void
    {
        $this->collPostsPartial = $v;
    }

    /**
     * Initializes the collPosts collection.
     *
     * By default this just sets the collPosts collection to an empty array (like clearcollPosts());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPosts(bool $overrideExisting = true): void
    {
        if (null !== $this->collPosts && !$overrideExisting) {
            return;
        }

        $collectionClassName = PostTableMap::getTableMap()->getCollectionClassName();

        $this->collPosts = new $collectionClassName;
        $this->collPosts->setModel('\Model\Post');
    }

    /**
     * Gets an array of ChildPost objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPost[] List of ChildPost objects
     * @phpstan-return ObjectCollection&\Traversable<ChildPost> List of ChildPost objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getPosts(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collPostsPartial && !$this->isNew();
        if (null === $this->collPosts || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collPosts) {
                    $this->initPosts();
                } else {
                    $collectionClassName = PostTableMap::getTableMap()->getCollectionClassName();

                    $collPosts = new $collectionClassName;
                    $collPosts->setModel('\Model\Post');

                    return $collPosts;
                }
            } else {
                $collPosts = ChildPostQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPostsPartial && count($collPosts)) {
                        $this->initPosts(false);

                        foreach ($collPosts as $obj) {
                            if (false == $this->collPosts->contains($obj)) {
                                $this->collPosts->append($obj);
                            }
                        }

                        $this->collPostsPartial = true;
                    }

                    return $collPosts;
                }

                if ($partial && $this->collPosts) {
                    foreach ($this->collPosts as $obj) {
                        if ($obj->isNew()) {
                            $collPosts[] = $obj;
                        }
                    }
                }

                $this->collPosts = $collPosts;
                $this->collPostsPartial = false;
            }
        }

        return $this->collPosts;
    }

    /**
     * Sets a collection of ChildPost objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $posts A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setPosts(Collection $posts, ?ConnectionInterface $con = null)
    {
        /** @var ChildPost[] $postsToDelete */
        $postsToDelete = $this->getPosts(new Criteria(), $con)->diff($posts);


        $this->postsScheduledForDeletion = $postsToDelete;

        foreach ($postsToDelete as $postRemoved) {
            $postRemoved->setUser(null);
        }

        $this->collPosts = null;
        foreach ($posts as $post) {
            $this->addPost($post);
        }

        $this->collPosts = $posts;
        $this->collPostsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Post objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Post objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countPosts(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collPostsPartial && !$this->isNew();
        if (null === $this->collPosts || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPosts) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPosts());
            }

            $query = ChildPostQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collPosts);
    }

    /**
     * Method called to associate a ChildPost object to this object
     * through the ChildPost foreign key attribute.
     *
     * @param ChildPost $l ChildPost
     * @return $this The current object (for fluent API support)
     */
    public function addPost(ChildPost $l)
    {
        if ($this->collPosts === null) {
            $this->initPosts();
            $this->collPostsPartial = true;
        }

        if (!$this->collPosts->contains($l)) {
            $this->doAddPost($l);

            if ($this->postsScheduledForDeletion and $this->postsScheduledForDeletion->contains($l)) {
                $this->postsScheduledForDeletion->remove($this->postsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPost $post The ChildPost object to add.
     */
    protected function doAddPost(ChildPost $post): void
    {
        $this->collPosts[]= $post;
        $post->setUser($this);
    }

    /**
     * @param ChildPost $post The ChildPost object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removePost(ChildPost $post)
    {
        if ($this->getPosts()->contains($post)) {
            $pos = $this->collPosts->search($post);
            $this->collPosts->remove($pos);
            if (null === $this->postsScheduledForDeletion) {
                $this->postsScheduledForDeletion = clone $this->collPosts;
                $this->postsScheduledForDeletion->clear();
            }
            $this->postsScheduledForDeletion[]= $post;
            $post->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Posts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPost[] List of ChildPost objects
     * @phpstan-return ObjectCollection&\Traversable<ChildPost}> List of ChildPost objects
     */
    public function getPostsJoinSite(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPostQuery::create(null, $criteria);
        $query->joinWith('Site', $joinBehavior);

        return $this->getPosts($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Posts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPost[] List of ChildPost objects
     * @phpstan-return ObjectCollection&\Traversable<ChildPost}> List of ChildPost objects
     */
    public function getPostsJoinBlogCategory(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPostQuery::create(null, $criteria);
        $query->joinWith('BlogCategory', $joinBehavior);

        return $this->getPosts($query, $con);
    }

    /**
     * Clears out the collRights collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addRights()
     */
    public function clearRights()
    {
        $this->collRights = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collRights collection loaded partially.
     *
     * @return void
     */
    public function resetPartialRights($v = true): void
    {
        $this->collRightsPartial = $v;
    }

    /**
     * Initializes the collRights collection.
     *
     * By default this just sets the collRights collection to an empty array (like clearcollRights());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initRights(bool $overrideExisting = true): void
    {
        if (null !== $this->collRights && !$overrideExisting) {
            return;
        }

        $collectionClassName = RightTableMap::getTableMap()->getCollectionClassName();

        $this->collRights = new $collectionClassName;
        $this->collRights->setModel('\Model\Right');
    }

    /**
     * Gets an array of ChildRight objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildRight[] List of ChildRight objects
     * @phpstan-return ObjectCollection&\Traversable<ChildRight> List of ChildRight objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getRights(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collRightsPartial && !$this->isNew();
        if (null === $this->collRights || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collRights) {
                    $this->initRights();
                } else {
                    $collectionClassName = RightTableMap::getTableMap()->getCollectionClassName();

                    $collRights = new $collectionClassName;
                    $collRights->setModel('\Model\Right');

                    return $collRights;
                }
            } else {
                $collRights = ChildRightQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collRightsPartial && count($collRights)) {
                        $this->initRights(false);

                        foreach ($collRights as $obj) {
                            if (false == $this->collRights->contains($obj)) {
                                $this->collRights->append($obj);
                            }
                        }

                        $this->collRightsPartial = true;
                    }

                    return $collRights;
                }

                if ($partial && $this->collRights) {
                    foreach ($this->collRights as $obj) {
                        if ($obj->isNew()) {
                            $collRights[] = $obj;
                        }
                    }
                }

                $this->collRights = $collRights;
                $this->collRightsPartial = false;
            }
        }

        return $this->collRights;
    }

    /**
     * Sets a collection of ChildRight objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $rights A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setRights(Collection $rights, ?ConnectionInterface $con = null)
    {
        /** @var ChildRight[] $rightsToDelete */
        $rightsToDelete = $this->getRights(new Criteria(), $con)->diff($rights);


        $this->rightsScheduledForDeletion = $rightsToDelete;

        foreach ($rightsToDelete as $rightRemoved) {
            $rightRemoved->setUser(null);
        }

        $this->collRights = null;
        foreach ($rights as $right) {
            $this->addRight($right);
        }

        $this->collRights = $rights;
        $this->collRightsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Right objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Right objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countRights(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collRightsPartial && !$this->isNew();
        if (null === $this->collRights || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collRights) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getRights());
            }

            $query = ChildRightQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collRights);
    }

    /**
     * Method called to associate a ChildRight object to this object
     * through the ChildRight foreign key attribute.
     *
     * @param ChildRight $l ChildRight
     * @return $this The current object (for fluent API support)
     */
    public function addRight(ChildRight $l)
    {
        if ($this->collRights === null) {
            $this->initRights();
            $this->collRightsPartial = true;
        }

        if (!$this->collRights->contains($l)) {
            $this->doAddRight($l);

            if ($this->rightsScheduledForDeletion and $this->rightsScheduledForDeletion->contains($l)) {
                $this->rightsScheduledForDeletion->remove($this->rightsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildRight $right The ChildRight object to add.
     */
    protected function doAddRight(ChildRight $right): void
    {
        $this->collRights[]= $right;
        $right->setUser($this);
    }

    /**
     * @param ChildRight $right The ChildRight object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeRight(ChildRight $right)
    {
        if ($this->getRights()->contains($right)) {
            $pos = $this->collRights->search($right);
            $this->collRights->remove($pos);
            if (null === $this->rightsScheduledForDeletion) {
                $this->rightsScheduledForDeletion = clone $this->collRights;
                $this->rightsScheduledForDeletion->clear();
            }
            $this->rightsScheduledForDeletion[]= $right;
            $right->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Rights from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildRight[] List of ChildRight objects
     * @phpstan-return ObjectCollection&\Traversable<ChildRight}> List of ChildRight objects
     */
    public function getRightsJoinSite(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildRightQuery::create(null, $criteria);
        $query->joinWith('Site', $joinBehavior);

        return $this->getRights($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Rights from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildRight[] List of ChildRight objects
     * @phpstan-return ObjectCollection&\Traversable<ChildRight}> List of ChildRight objects
     */
    public function getRightsJoinPublisher(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildRightQuery::create(null, $criteria);
        $query->joinWith('Publisher', $joinBehavior);

        return $this->getRights($query, $con);
    }

    /**
     * Clears out the collRoles collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addRoles()
     */
    public function clearRoles()
    {
        $this->collRoles = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collRoles collection loaded partially.
     *
     * @return void
     */
    public function resetPartialRoles($v = true): void
    {
        $this->collRolesPartial = $v;
    }

    /**
     * Initializes the collRoles collection.
     *
     * By default this just sets the collRoles collection to an empty array (like clearcollRoles());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initRoles(bool $overrideExisting = true): void
    {
        if (null !== $this->collRoles && !$overrideExisting) {
            return;
        }

        $collectionClassName = RoleTableMap::getTableMap()->getCollectionClassName();

        $this->collRoles = new $collectionClassName;
        $this->collRoles->setModel('\Model\Role');
    }

    /**
     * Gets an array of ChildRole objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildRole[] List of ChildRole objects
     * @phpstan-return ObjectCollection&\Traversable<ChildRole> List of ChildRole objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getRoles(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collRolesPartial && !$this->isNew();
        if (null === $this->collRoles || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collRoles) {
                    $this->initRoles();
                } else {
                    $collectionClassName = RoleTableMap::getTableMap()->getCollectionClassName();

                    $collRoles = new $collectionClassName;
                    $collRoles->setModel('\Model\Role');

                    return $collRoles;
                }
            } else {
                $collRoles = ChildRoleQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collRolesPartial && count($collRoles)) {
                        $this->initRoles(false);

                        foreach ($collRoles as $obj) {
                            if (false == $this->collRoles->contains($obj)) {
                                $this->collRoles->append($obj);
                            }
                        }

                        $this->collRolesPartial = true;
                    }

                    return $collRoles;
                }

                if ($partial && $this->collRoles) {
                    foreach ($this->collRoles as $obj) {
                        if ($obj->isNew()) {
                            $collRoles[] = $obj;
                        }
                    }
                }

                $this->collRoles = $collRoles;
                $this->collRolesPartial = false;
            }
        }

        return $this->collRoles;
    }

    /**
     * Sets a collection of ChildRole objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $roles A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setRoles(Collection $roles, ?ConnectionInterface $con = null)
    {
        /** @var ChildRole[] $rolesToDelete */
        $rolesToDelete = $this->getRoles(new Criteria(), $con)->diff($roles);


        $this->rolesScheduledForDeletion = $rolesToDelete;

        foreach ($rolesToDelete as $roleRemoved) {
            $roleRemoved->setUser(null);
        }

        $this->collRoles = null;
        foreach ($roles as $role) {
            $this->addRole($role);
        }

        $this->collRoles = $roles;
        $this->collRolesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Role objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Role objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countRoles(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collRolesPartial && !$this->isNew();
        if (null === $this->collRoles || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collRoles) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getRoles());
            }

            $query = ChildRoleQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collRoles);
    }

    /**
     * Method called to associate a ChildRole object to this object
     * through the ChildRole foreign key attribute.
     *
     * @param ChildRole $l ChildRole
     * @return $this The current object (for fluent API support)
     */
    public function addRole(ChildRole $l)
    {
        if ($this->collRoles === null) {
            $this->initRoles();
            $this->collRolesPartial = true;
        }

        if (!$this->collRoles->contains($l)) {
            $this->doAddRole($l);

            if ($this->rolesScheduledForDeletion and $this->rolesScheduledForDeletion->contains($l)) {
                $this->rolesScheduledForDeletion->remove($this->rolesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildRole $role The ChildRole object to add.
     */
    protected function doAddRole(ChildRole $role): void
    {
        $this->collRoles[]= $role;
        $role->setUser($this);
    }

    /**
     * @param ChildRole $role The ChildRole object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeRole(ChildRole $role)
    {
        if ($this->getRoles()->contains($role)) {
            $pos = $this->collRoles->search($role);
            $this->collRoles->remove($pos);
            if (null === $this->rolesScheduledForDeletion) {
                $this->rolesScheduledForDeletion = clone $this->collRoles;
                $this->rolesScheduledForDeletion->clear();
            }
            $this->rolesScheduledForDeletion[]= $role;
            $role->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Roles from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildRole[] List of ChildRole objects
     * @phpstan-return ObjectCollection&\Traversable<ChildRole}> List of ChildRole objects
     */
    public function getRolesJoinArticle(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildRoleQuery::create(null, $criteria);
        $query->joinWith('Article', $joinBehavior);

        return $this->getRoles($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Roles from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildRole[] List of ChildRole objects
     * @phpstan-return ObjectCollection&\Traversable<ChildRole}> List of ChildRole objects
     */
    public function getRolesJoinPeople(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildRoleQuery::create(null, $criteria);
        $query->joinWith('People', $joinBehavior);

        return $this->getRoles($query, $con);
    }

    /**
     * Clears out the collSessions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addSessions()
     */
    public function clearSessions()
    {
        $this->collSessions = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collSessions collection loaded partially.
     *
     * @return void
     */
    public function resetPartialSessions($v = true): void
    {
        $this->collSessionsPartial = $v;
    }

    /**
     * Initializes the collSessions collection.
     *
     * By default this just sets the collSessions collection to an empty array (like clearcollSessions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSessions(bool $overrideExisting = true): void
    {
        if (null !== $this->collSessions && !$overrideExisting) {
            return;
        }

        $collectionClassName = SessionTableMap::getTableMap()->getCollectionClassName();

        $this->collSessions = new $collectionClassName;
        $this->collSessions->setModel('\Model\Session');
    }

    /**
     * Gets an array of ChildSession objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildSession[] List of ChildSession objects
     * @phpstan-return ObjectCollection&\Traversable<ChildSession> List of ChildSession objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getSessions(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collSessionsPartial && !$this->isNew();
        if (null === $this->collSessions || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collSessions) {
                    $this->initSessions();
                } else {
                    $collectionClassName = SessionTableMap::getTableMap()->getCollectionClassName();

                    $collSessions = new $collectionClassName;
                    $collSessions->setModel('\Model\Session');

                    return $collSessions;
                }
            } else {
                $collSessions = ChildSessionQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collSessionsPartial && count($collSessions)) {
                        $this->initSessions(false);

                        foreach ($collSessions as $obj) {
                            if (false == $this->collSessions->contains($obj)) {
                                $this->collSessions->append($obj);
                            }
                        }

                        $this->collSessionsPartial = true;
                    }

                    return $collSessions;
                }

                if ($partial && $this->collSessions) {
                    foreach ($this->collSessions as $obj) {
                        if ($obj->isNew()) {
                            $collSessions[] = $obj;
                        }
                    }
                }

                $this->collSessions = $collSessions;
                $this->collSessionsPartial = false;
            }
        }

        return $this->collSessions;
    }

    /**
     * Sets a collection of ChildSession objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $sessions A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setSessions(Collection $sessions, ?ConnectionInterface $con = null)
    {
        /** @var ChildSession[] $sessionsToDelete */
        $sessionsToDelete = $this->getSessions(new Criteria(), $con)->diff($sessions);


        $this->sessionsScheduledForDeletion = $sessionsToDelete;

        foreach ($sessionsToDelete as $sessionRemoved) {
            $sessionRemoved->setUser(null);
        }

        $this->collSessions = null;
        foreach ($sessions as $session) {
            $this->addSession($session);
        }

        $this->collSessions = $sessions;
        $this->collSessionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Session objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Session objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countSessions(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collSessionsPartial && !$this->isNew();
        if (null === $this->collSessions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSessions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSessions());
            }

            $query = ChildSessionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collSessions);
    }

    /**
     * Method called to associate a ChildSession object to this object
     * through the ChildSession foreign key attribute.
     *
     * @param ChildSession $l ChildSession
     * @return $this The current object (for fluent API support)
     */
    public function addSession(ChildSession $l)
    {
        if ($this->collSessions === null) {
            $this->initSessions();
            $this->collSessionsPartial = true;
        }

        if (!$this->collSessions->contains($l)) {
            $this->doAddSession($l);

            if ($this->sessionsScheduledForDeletion and $this->sessionsScheduledForDeletion->contains($l)) {
                $this->sessionsScheduledForDeletion->remove($this->sessionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildSession $session The ChildSession object to add.
     */
    protected function doAddSession(ChildSession $session): void
    {
        $this->collSessions[]= $session;
        $session->setUser($this);
    }

    /**
     * @param ChildSession $session The ChildSession object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeSession(ChildSession $session)
    {
        if ($this->getSessions()->contains($session)) {
            $pos = $this->collSessions->search($session);
            $this->collSessions->remove($pos);
            if (null === $this->sessionsScheduledForDeletion) {
                $this->sessionsScheduledForDeletion = clone $this->collSessions;
                $this->sessionsScheduledForDeletion->clear();
            }
            $this->sessionsScheduledForDeletion[]= $session;
            $session->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Sessions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildSession[] List of ChildSession objects
     * @phpstan-return ObjectCollection&\Traversable<ChildSession}> List of ChildSession objects
     */
    public function getSessionsJoinSite(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildSessionQuery::create(null, $criteria);
        $query->joinWith('Site', $joinBehavior);

        return $this->getSessions($query, $con);
    }

    /**
     * Clears out the collStocks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addStocks()
     */
    public function clearStocks()
    {
        $this->collStocks = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collStocks collection loaded partially.
     *
     * @return void
     */
    public function resetPartialStocks($v = true): void
    {
        $this->collStocksPartial = $v;
    }

    /**
     * Initializes the collStocks collection.
     *
     * By default this just sets the collStocks collection to an empty array (like clearcollStocks());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initStocks(bool $overrideExisting = true): void
    {
        if (null !== $this->collStocks && !$overrideExisting) {
            return;
        }

        $collectionClassName = StockTableMap::getTableMap()->getCollectionClassName();

        $this->collStocks = new $collectionClassName;
        $this->collStocks->setModel('\Model\Stock');
    }

    /**
     * Gets an array of ChildStock objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildStock[] List of ChildStock objects
     * @phpstan-return ObjectCollection&\Traversable<ChildStock> List of ChildStock objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getStocks(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collStocksPartial && !$this->isNew();
        if (null === $this->collStocks || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collStocks) {
                    $this->initStocks();
                } else {
                    $collectionClassName = StockTableMap::getTableMap()->getCollectionClassName();

                    $collStocks = new $collectionClassName;
                    $collStocks->setModel('\Model\Stock');

                    return $collStocks;
                }
            } else {
                $collStocks = ChildStockQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collStocksPartial && count($collStocks)) {
                        $this->initStocks(false);

                        foreach ($collStocks as $obj) {
                            if (false == $this->collStocks->contains($obj)) {
                                $this->collStocks->append($obj);
                            }
                        }

                        $this->collStocksPartial = true;
                    }

                    return $collStocks;
                }

                if ($partial && $this->collStocks) {
                    foreach ($this->collStocks as $obj) {
                        if ($obj->isNew()) {
                            $collStocks[] = $obj;
                        }
                    }
                }

                $this->collStocks = $collStocks;
                $this->collStocksPartial = false;
            }
        }

        return $this->collStocks;
    }

    /**
     * Sets a collection of ChildStock objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $stocks A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setStocks(Collection $stocks, ?ConnectionInterface $con = null)
    {
        /** @var ChildStock[] $stocksToDelete */
        $stocksToDelete = $this->getStocks(new Criteria(), $con)->diff($stocks);


        $this->stocksScheduledForDeletion = $stocksToDelete;

        foreach ($stocksToDelete as $stockRemoved) {
            $stockRemoved->setUser(null);
        }

        $this->collStocks = null;
        foreach ($stocks as $stock) {
            $this->addStock($stock);
        }

        $this->collStocks = $stocks;
        $this->collStocksPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Stock objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Stock objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countStocks(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collStocksPartial && !$this->isNew();
        if (null === $this->collStocks || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collStocks) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getStocks());
            }

            $query = ChildStockQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collStocks);
    }

    /**
     * Method called to associate a ChildStock object to this object
     * through the ChildStock foreign key attribute.
     *
     * @param ChildStock $l ChildStock
     * @return $this The current object (for fluent API support)
     */
    public function addStock(ChildStock $l)
    {
        if ($this->collStocks === null) {
            $this->initStocks();
            $this->collStocksPartial = true;
        }

        if (!$this->collStocks->contains($l)) {
            $this->doAddStock($l);

            if ($this->stocksScheduledForDeletion and $this->stocksScheduledForDeletion->contains($l)) {
                $this->stocksScheduledForDeletion->remove($this->stocksScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildStock $stock The ChildStock object to add.
     */
    protected function doAddStock(ChildStock $stock): void
    {
        $this->collStocks[]= $stock;
        $stock->setUser($this);
    }

    /**
     * @param ChildStock $stock The ChildStock object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeStock(ChildStock $stock)
    {
        if ($this->getStocks()->contains($stock)) {
            $pos = $this->collStocks->search($stock);
            $this->collStocks->remove($pos);
            if (null === $this->stocksScheduledForDeletion) {
                $this->stocksScheduledForDeletion = clone $this->collStocks;
                $this->stocksScheduledForDeletion->clear();
            }
            $this->stocksScheduledForDeletion[]= $stock;
            $stock->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Stocks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildStock[] List of ChildStock objects
     * @phpstan-return ObjectCollection&\Traversable<ChildStock}> List of ChildStock objects
     */
    public function getStocksJoinSite(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildStockQuery::create(null, $criteria);
        $query->joinWith('Site', $joinBehavior);

        return $this->getStocks($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Stocks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildStock[] List of ChildStock objects
     * @phpstan-return ObjectCollection&\Traversable<ChildStock}> List of ChildStock objects
     */
    public function getStocksJoinCart(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildStockQuery::create(null, $criteria);
        $query->joinWith('Cart', $joinBehavior);

        return $this->getStocks($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Stocks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildStock[] List of ChildStock objects
     * @phpstan-return ObjectCollection&\Traversable<ChildStock}> List of ChildStock objects
     */
    public function getStocksJoinOrder(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildStockQuery::create(null, $criteria);
        $query->joinWith('Order', $joinBehavior);

        return $this->getStocks($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Stocks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildStock[] List of ChildStock objects
     * @phpstan-return ObjectCollection&\Traversable<ChildStock}> List of ChildStock objects
     */
    public function getStocksJoinArticle(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildStockQuery::create(null, $criteria);
        $query->joinWith('Article', $joinBehavior);

        return $this->getStocks($query, $con);
    }

    /**
     * Clears out the collSubscriptions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addSubscriptions()
     */
    public function clearSubscriptions()
    {
        $this->collSubscriptions = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collSubscriptions collection loaded partially.
     *
     * @return void
     */
    public function resetPartialSubscriptions($v = true): void
    {
        $this->collSubscriptionsPartial = $v;
    }

    /**
     * Initializes the collSubscriptions collection.
     *
     * By default this just sets the collSubscriptions collection to an empty array (like clearcollSubscriptions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSubscriptions(bool $overrideExisting = true): void
    {
        if (null !== $this->collSubscriptions && !$overrideExisting) {
            return;
        }

        $collectionClassName = SubscriptionTableMap::getTableMap()->getCollectionClassName();

        $this->collSubscriptions = new $collectionClassName;
        $this->collSubscriptions->setModel('\Model\Subscription');
    }

    /**
     * Gets an array of ChildSubscription objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildSubscription[] List of ChildSubscription objects
     * @phpstan-return ObjectCollection&\Traversable<ChildSubscription> List of ChildSubscription objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getSubscriptions(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collSubscriptionsPartial && !$this->isNew();
        if (null === $this->collSubscriptions || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collSubscriptions) {
                    $this->initSubscriptions();
                } else {
                    $collectionClassName = SubscriptionTableMap::getTableMap()->getCollectionClassName();

                    $collSubscriptions = new $collectionClassName;
                    $collSubscriptions->setModel('\Model\Subscription');

                    return $collSubscriptions;
                }
            } else {
                $collSubscriptions = ChildSubscriptionQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collSubscriptionsPartial && count($collSubscriptions)) {
                        $this->initSubscriptions(false);

                        foreach ($collSubscriptions as $obj) {
                            if (false == $this->collSubscriptions->contains($obj)) {
                                $this->collSubscriptions->append($obj);
                            }
                        }

                        $this->collSubscriptionsPartial = true;
                    }

                    return $collSubscriptions;
                }

                if ($partial && $this->collSubscriptions) {
                    foreach ($this->collSubscriptions as $obj) {
                        if ($obj->isNew()) {
                            $collSubscriptions[] = $obj;
                        }
                    }
                }

                $this->collSubscriptions = $collSubscriptions;
                $this->collSubscriptionsPartial = false;
            }
        }

        return $this->collSubscriptions;
    }

    /**
     * Sets a collection of ChildSubscription objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $subscriptions A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setSubscriptions(Collection $subscriptions, ?ConnectionInterface $con = null)
    {
        /** @var ChildSubscription[] $subscriptionsToDelete */
        $subscriptionsToDelete = $this->getSubscriptions(new Criteria(), $con)->diff($subscriptions);


        $this->subscriptionsScheduledForDeletion = $subscriptionsToDelete;

        foreach ($subscriptionsToDelete as $subscriptionRemoved) {
            $subscriptionRemoved->setUser(null);
        }

        $this->collSubscriptions = null;
        foreach ($subscriptions as $subscription) {
            $this->addSubscription($subscription);
        }

        $this->collSubscriptions = $subscriptions;
        $this->collSubscriptionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Subscription objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Subscription objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countSubscriptions(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collSubscriptionsPartial && !$this->isNew();
        if (null === $this->collSubscriptions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSubscriptions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSubscriptions());
            }

            $query = ChildSubscriptionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collSubscriptions);
    }

    /**
     * Method called to associate a ChildSubscription object to this object
     * through the ChildSubscription foreign key attribute.
     *
     * @param ChildSubscription $l ChildSubscription
     * @return $this The current object (for fluent API support)
     */
    public function addSubscription(ChildSubscription $l)
    {
        if ($this->collSubscriptions === null) {
            $this->initSubscriptions();
            $this->collSubscriptionsPartial = true;
        }

        if (!$this->collSubscriptions->contains($l)) {
            $this->doAddSubscription($l);

            if ($this->subscriptionsScheduledForDeletion and $this->subscriptionsScheduledForDeletion->contains($l)) {
                $this->subscriptionsScheduledForDeletion->remove($this->subscriptionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildSubscription $subscription The ChildSubscription object to add.
     */
    protected function doAddSubscription(ChildSubscription $subscription): void
    {
        $this->collSubscriptions[]= $subscription;
        $subscription->setUser($this);
    }

    /**
     * @param ChildSubscription $subscription The ChildSubscription object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeSubscription(ChildSubscription $subscription)
    {
        if ($this->getSubscriptions()->contains($subscription)) {
            $pos = $this->collSubscriptions->search($subscription);
            $this->collSubscriptions->remove($pos);
            if (null === $this->subscriptionsScheduledForDeletion) {
                $this->subscriptionsScheduledForDeletion = clone $this->collSubscriptions;
                $this->subscriptionsScheduledForDeletion->clear();
            }
            $this->subscriptionsScheduledForDeletion[]= $subscription;
            $subscription->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Subscriptions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildSubscription[] List of ChildSubscription objects
     * @phpstan-return ObjectCollection&\Traversable<ChildSubscription}> List of ChildSubscription objects
     */
    public function getSubscriptionsJoinSite(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildSubscriptionQuery::create(null, $criteria);
        $query->joinWith('Site', $joinBehavior);

        return $this->getSubscriptions($query, $con);
    }

    /**
     * Clears out the collAuthenticationMethods collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addAuthenticationMethods()
     */
    public function clearAuthenticationMethods()
    {
        $this->collAuthenticationMethods = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collAuthenticationMethods collection loaded partially.
     *
     * @return void
     */
    public function resetPartialAuthenticationMethods($v = true): void
    {
        $this->collAuthenticationMethodsPartial = $v;
    }

    /**
     * Initializes the collAuthenticationMethods collection.
     *
     * By default this just sets the collAuthenticationMethods collection to an empty array (like clearcollAuthenticationMethods());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAuthenticationMethods(bool $overrideExisting = true): void
    {
        if (null !== $this->collAuthenticationMethods && !$overrideExisting) {
            return;
        }

        $collectionClassName = AuthenticationMethodTableMap::getTableMap()->getCollectionClassName();

        $this->collAuthenticationMethods = new $collectionClassName;
        $this->collAuthenticationMethods->setModel('\Model\AuthenticationMethod');
    }

    /**
     * Gets an array of ChildAuthenticationMethod objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildAuthenticationMethod[] List of ChildAuthenticationMethod objects
     * @phpstan-return ObjectCollection&\Traversable<ChildAuthenticationMethod> List of ChildAuthenticationMethod objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getAuthenticationMethods(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collAuthenticationMethodsPartial && !$this->isNew();
        if (null === $this->collAuthenticationMethods || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collAuthenticationMethods) {
                    $this->initAuthenticationMethods();
                } else {
                    $collectionClassName = AuthenticationMethodTableMap::getTableMap()->getCollectionClassName();

                    $collAuthenticationMethods = new $collectionClassName;
                    $collAuthenticationMethods->setModel('\Model\AuthenticationMethod');

                    return $collAuthenticationMethods;
                }
            } else {
                $collAuthenticationMethods = ChildAuthenticationMethodQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collAuthenticationMethodsPartial && count($collAuthenticationMethods)) {
                        $this->initAuthenticationMethods(false);

                        foreach ($collAuthenticationMethods as $obj) {
                            if (false == $this->collAuthenticationMethods->contains($obj)) {
                                $this->collAuthenticationMethods->append($obj);
                            }
                        }

                        $this->collAuthenticationMethodsPartial = true;
                    }

                    return $collAuthenticationMethods;
                }

                if ($partial && $this->collAuthenticationMethods) {
                    foreach ($this->collAuthenticationMethods as $obj) {
                        if ($obj->isNew()) {
                            $collAuthenticationMethods[] = $obj;
                        }
                    }
                }

                $this->collAuthenticationMethods = $collAuthenticationMethods;
                $this->collAuthenticationMethodsPartial = false;
            }
        }

        return $this->collAuthenticationMethods;
    }

    /**
     * Sets a collection of ChildAuthenticationMethod objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $authenticationMethods A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setAuthenticationMethods(Collection $authenticationMethods, ?ConnectionInterface $con = null)
    {
        /** @var ChildAuthenticationMethod[] $authenticationMethodsToDelete */
        $authenticationMethodsToDelete = $this->getAuthenticationMethods(new Criteria(), $con)->diff($authenticationMethods);


        $this->authenticationMethodsScheduledForDeletion = $authenticationMethodsToDelete;

        foreach ($authenticationMethodsToDelete as $authenticationMethodRemoved) {
            $authenticationMethodRemoved->setUser(null);
        }

        $this->collAuthenticationMethods = null;
        foreach ($authenticationMethods as $authenticationMethod) {
            $this->addAuthenticationMethod($authenticationMethod);
        }

        $this->collAuthenticationMethods = $authenticationMethods;
        $this->collAuthenticationMethodsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related AuthenticationMethod objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related AuthenticationMethod objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countAuthenticationMethods(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collAuthenticationMethodsPartial && !$this->isNew();
        if (null === $this->collAuthenticationMethods || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAuthenticationMethods) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAuthenticationMethods());
            }

            $query = ChildAuthenticationMethodQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collAuthenticationMethods);
    }

    /**
     * Method called to associate a ChildAuthenticationMethod object to this object
     * through the ChildAuthenticationMethod foreign key attribute.
     *
     * @param ChildAuthenticationMethod $l ChildAuthenticationMethod
     * @return $this The current object (for fluent API support)
     */
    public function addAuthenticationMethod(ChildAuthenticationMethod $l)
    {
        if ($this->collAuthenticationMethods === null) {
            $this->initAuthenticationMethods();
            $this->collAuthenticationMethodsPartial = true;
        }

        if (!$this->collAuthenticationMethods->contains($l)) {
            $this->doAddAuthenticationMethod($l);

            if ($this->authenticationMethodsScheduledForDeletion and $this->authenticationMethodsScheduledForDeletion->contains($l)) {
                $this->authenticationMethodsScheduledForDeletion->remove($this->authenticationMethodsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildAuthenticationMethod $authenticationMethod The ChildAuthenticationMethod object to add.
     */
    protected function doAddAuthenticationMethod(ChildAuthenticationMethod $authenticationMethod): void
    {
        $this->collAuthenticationMethods[]= $authenticationMethod;
        $authenticationMethod->setUser($this);
    }

    /**
     * @param ChildAuthenticationMethod $authenticationMethod The ChildAuthenticationMethod object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeAuthenticationMethod(ChildAuthenticationMethod $authenticationMethod)
    {
        if ($this->getAuthenticationMethods()->contains($authenticationMethod)) {
            $pos = $this->collAuthenticationMethods->search($authenticationMethod);
            $this->collAuthenticationMethods->remove($pos);
            if (null === $this->authenticationMethodsScheduledForDeletion) {
                $this->authenticationMethodsScheduledForDeletion = clone $this->collAuthenticationMethods;
                $this->authenticationMethodsScheduledForDeletion->clear();
            }
            $this->authenticationMethodsScheduledForDeletion[]= clone $authenticationMethod;
            $authenticationMethod->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related AuthenticationMethods from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildAuthenticationMethod[] List of ChildAuthenticationMethod objects
     * @phpstan-return ObjectCollection&\Traversable<ChildAuthenticationMethod}> List of ChildAuthenticationMethod objects
     */
    public function getAuthenticationMethodsJoinSite(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildAuthenticationMethodQuery::create(null, $criteria);
        $query->joinWith('Site', $joinBehavior);

        return $this->getAuthenticationMethods($query, $con);
    }

    /**
     * Clears out the collVotes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addVotes()
     */
    public function clearVotes()
    {
        $this->collVotes = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collVotes collection loaded partially.
     *
     * @return void
     */
    public function resetPartialVotes($v = true): void
    {
        $this->collVotesPartial = $v;
    }

    /**
     * Initializes the collVotes collection.
     *
     * By default this just sets the collVotes collection to an empty array (like clearcollVotes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initVotes(bool $overrideExisting = true): void
    {
        if (null !== $this->collVotes && !$overrideExisting) {
            return;
        }

        $collectionClassName = VoteTableMap::getTableMap()->getCollectionClassName();

        $this->collVotes = new $collectionClassName;
        $this->collVotes->setModel('\Model\Vote');
    }

    /**
     * Gets an array of ChildVote objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildVote[] List of ChildVote objects
     * @phpstan-return ObjectCollection&\Traversable<ChildVote> List of ChildVote objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getVotes(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collVotesPartial && !$this->isNew();
        if (null === $this->collVotes || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collVotes) {
                    $this->initVotes();
                } else {
                    $collectionClassName = VoteTableMap::getTableMap()->getCollectionClassName();

                    $collVotes = new $collectionClassName;
                    $collVotes->setModel('\Model\Vote');

                    return $collVotes;
                }
            } else {
                $collVotes = ChildVoteQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collVotesPartial && count($collVotes)) {
                        $this->initVotes(false);

                        foreach ($collVotes as $obj) {
                            if (false == $this->collVotes->contains($obj)) {
                                $this->collVotes->append($obj);
                            }
                        }

                        $this->collVotesPartial = true;
                    }

                    return $collVotes;
                }

                if ($partial && $this->collVotes) {
                    foreach ($this->collVotes as $obj) {
                        if ($obj->isNew()) {
                            $collVotes[] = $obj;
                        }
                    }
                }

                $this->collVotes = $collVotes;
                $this->collVotesPartial = false;
            }
        }

        return $this->collVotes;
    }

    /**
     * Sets a collection of ChildVote objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $votes A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setVotes(Collection $votes, ?ConnectionInterface $con = null)
    {
        /** @var ChildVote[] $votesToDelete */
        $votesToDelete = $this->getVotes(new Criteria(), $con)->diff($votes);


        $this->votesScheduledForDeletion = $votesToDelete;

        foreach ($votesToDelete as $voteRemoved) {
            $voteRemoved->setUser(null);
        }

        $this->collVotes = null;
        foreach ($votes as $vote) {
            $this->addVote($vote);
        }

        $this->collVotes = $votes;
        $this->collVotesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Vote objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Vote objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countVotes(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collVotesPartial && !$this->isNew();
        if (null === $this->collVotes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collVotes) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getVotes());
            }

            $query = ChildVoteQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collVotes);
    }

    /**
     * Method called to associate a ChildVote object to this object
     * through the ChildVote foreign key attribute.
     *
     * @param ChildVote $l ChildVote
     * @return $this The current object (for fluent API support)
     */
    public function addVote(ChildVote $l)
    {
        if ($this->collVotes === null) {
            $this->initVotes();
            $this->collVotesPartial = true;
        }

        if (!$this->collVotes->contains($l)) {
            $this->doAddVote($l);

            if ($this->votesScheduledForDeletion and $this->votesScheduledForDeletion->contains($l)) {
                $this->votesScheduledForDeletion->remove($this->votesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildVote $vote The ChildVote object to add.
     */
    protected function doAddVote(ChildVote $vote): void
    {
        $this->collVotes[]= $vote;
        $vote->setUser($this);
    }

    /**
     * @param ChildVote $vote The ChildVote object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeVote(ChildVote $vote)
    {
        if ($this->getVotes()->contains($vote)) {
            $pos = $this->collVotes->search($vote);
            $this->collVotes->remove($pos);
            if (null === $this->votesScheduledForDeletion) {
                $this->votesScheduledForDeletion = clone $this->collVotes;
                $this->votesScheduledForDeletion->clear();
            }
            $this->votesScheduledForDeletion[]= $vote;
            $vote->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Votes from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildVote[] List of ChildVote objects
     * @phpstan-return ObjectCollection&\Traversable<ChildVote}> List of ChildVote objects
     */
    public function getVotesJoinSite(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildVoteQuery::create(null, $criteria);
        $query->joinWith('Site', $joinBehavior);

        return $this->getVotes($query, $con);
    }

    /**
     * Clears out the collWishes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addWishes()
     */
    public function clearWishes()
    {
        $this->collWishes = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collWishes collection loaded partially.
     *
     * @return void
     */
    public function resetPartialWishes($v = true): void
    {
        $this->collWishesPartial = $v;
    }

    /**
     * Initializes the collWishes collection.
     *
     * By default this just sets the collWishes collection to an empty array (like clearcollWishes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initWishes(bool $overrideExisting = true): void
    {
        if (null !== $this->collWishes && !$overrideExisting) {
            return;
        }

        $collectionClassName = WishTableMap::getTableMap()->getCollectionClassName();

        $this->collWishes = new $collectionClassName;
        $this->collWishes->setModel('\Model\Wish');
    }

    /**
     * Gets an array of ChildWish objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildWish[] List of ChildWish objects
     * @phpstan-return ObjectCollection&\Traversable<ChildWish> List of ChildWish objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getWishes(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collWishesPartial && !$this->isNew();
        if (null === $this->collWishes || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collWishes) {
                    $this->initWishes();
                } else {
                    $collectionClassName = WishTableMap::getTableMap()->getCollectionClassName();

                    $collWishes = new $collectionClassName;
                    $collWishes->setModel('\Model\Wish');

                    return $collWishes;
                }
            } else {
                $collWishes = ChildWishQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collWishesPartial && count($collWishes)) {
                        $this->initWishes(false);

                        foreach ($collWishes as $obj) {
                            if (false == $this->collWishes->contains($obj)) {
                                $this->collWishes->append($obj);
                            }
                        }

                        $this->collWishesPartial = true;
                    }

                    return $collWishes;
                }

                if ($partial && $this->collWishes) {
                    foreach ($this->collWishes as $obj) {
                        if ($obj->isNew()) {
                            $collWishes[] = $obj;
                        }
                    }
                }

                $this->collWishes = $collWishes;
                $this->collWishesPartial = false;
            }
        }

        return $this->collWishes;
    }

    /**
     * Sets a collection of ChildWish objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $wishes A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setWishes(Collection $wishes, ?ConnectionInterface $con = null)
    {
        /** @var ChildWish[] $wishesToDelete */
        $wishesToDelete = $this->getWishes(new Criteria(), $con)->diff($wishes);


        $this->wishesScheduledForDeletion = $wishesToDelete;

        foreach ($wishesToDelete as $wishRemoved) {
            $wishRemoved->setUser(null);
        }

        $this->collWishes = null;
        foreach ($wishes as $wish) {
            $this->addWish($wish);
        }

        $this->collWishes = $wishes;
        $this->collWishesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Wish objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Wish objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countWishes(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collWishesPartial && !$this->isNew();
        if (null === $this->collWishes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collWishes) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getWishes());
            }

            $query = ChildWishQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collWishes);
    }

    /**
     * Method called to associate a ChildWish object to this object
     * through the ChildWish foreign key attribute.
     *
     * @param ChildWish $l ChildWish
     * @return $this The current object (for fluent API support)
     */
    public function addWish(ChildWish $l)
    {
        if ($this->collWishes === null) {
            $this->initWishes();
            $this->collWishesPartial = true;
        }

        if (!$this->collWishes->contains($l)) {
            $this->doAddWish($l);

            if ($this->wishesScheduledForDeletion and $this->wishesScheduledForDeletion->contains($l)) {
                $this->wishesScheduledForDeletion->remove($this->wishesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildWish $wish The ChildWish object to add.
     */
    protected function doAddWish(ChildWish $wish): void
    {
        $this->collWishes[]= $wish;
        $wish->setUser($this);
    }

    /**
     * @param ChildWish $wish The ChildWish object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeWish(ChildWish $wish)
    {
        if ($this->getWishes()->contains($wish)) {
            $pos = $this->collWishes->search($wish);
            $this->collWishes->remove($pos);
            if (null === $this->wishesScheduledForDeletion) {
                $this->wishesScheduledForDeletion = clone $this->collWishes;
                $this->wishesScheduledForDeletion->clear();
            }
            $this->wishesScheduledForDeletion[]= $wish;
            $wish->setUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collWishlists collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addWishlists()
     */
    public function clearWishlists()
    {
        $this->collWishlists = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collWishlists collection loaded partially.
     *
     * @return void
     */
    public function resetPartialWishlists($v = true): void
    {
        $this->collWishlistsPartial = $v;
    }

    /**
     * Initializes the collWishlists collection.
     *
     * By default this just sets the collWishlists collection to an empty array (like clearcollWishlists());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initWishlists(bool $overrideExisting = true): void
    {
        if (null !== $this->collWishlists && !$overrideExisting) {
            return;
        }

        $collectionClassName = WishlistTableMap::getTableMap()->getCollectionClassName();

        $this->collWishlists = new $collectionClassName;
        $this->collWishlists->setModel('\Model\Wishlist');
    }

    /**
     * Gets an array of ChildWishlist objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildWishlist[] List of ChildWishlist objects
     * @phpstan-return ObjectCollection&\Traversable<ChildWishlist> List of ChildWishlist objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getWishlists(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collWishlistsPartial && !$this->isNew();
        if (null === $this->collWishlists || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collWishlists) {
                    $this->initWishlists();
                } else {
                    $collectionClassName = WishlistTableMap::getTableMap()->getCollectionClassName();

                    $collWishlists = new $collectionClassName;
                    $collWishlists->setModel('\Model\Wishlist');

                    return $collWishlists;
                }
            } else {
                $collWishlists = ChildWishlistQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collWishlistsPartial && count($collWishlists)) {
                        $this->initWishlists(false);

                        foreach ($collWishlists as $obj) {
                            if (false == $this->collWishlists->contains($obj)) {
                                $this->collWishlists->append($obj);
                            }
                        }

                        $this->collWishlistsPartial = true;
                    }

                    return $collWishlists;
                }

                if ($partial && $this->collWishlists) {
                    foreach ($this->collWishlists as $obj) {
                        if ($obj->isNew()) {
                            $collWishlists[] = $obj;
                        }
                    }
                }

                $this->collWishlists = $collWishlists;
                $this->collWishlistsPartial = false;
            }
        }

        return $this->collWishlists;
    }

    /**
     * Sets a collection of ChildWishlist objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $wishlists A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setWishlists(Collection $wishlists, ?ConnectionInterface $con = null)
    {
        /** @var ChildWishlist[] $wishlistsToDelete */
        $wishlistsToDelete = $this->getWishlists(new Criteria(), $con)->diff($wishlists);


        $this->wishlistsScheduledForDeletion = $wishlistsToDelete;

        foreach ($wishlistsToDelete as $wishlistRemoved) {
            $wishlistRemoved->setUser(null);
        }

        $this->collWishlists = null;
        foreach ($wishlists as $wishlist) {
            $this->addWishlist($wishlist);
        }

        $this->collWishlists = $wishlists;
        $this->collWishlistsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Wishlist objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Wishlist objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countWishlists(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collWishlistsPartial && !$this->isNew();
        if (null === $this->collWishlists || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collWishlists) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getWishlists());
            }

            $query = ChildWishlistQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collWishlists);
    }

    /**
     * Method called to associate a ChildWishlist object to this object
     * through the ChildWishlist foreign key attribute.
     *
     * @param ChildWishlist $l ChildWishlist
     * @return $this The current object (for fluent API support)
     */
    public function addWishlist(ChildWishlist $l)
    {
        if ($this->collWishlists === null) {
            $this->initWishlists();
            $this->collWishlistsPartial = true;
        }

        if (!$this->collWishlists->contains($l)) {
            $this->doAddWishlist($l);

            if ($this->wishlistsScheduledForDeletion and $this->wishlistsScheduledForDeletion->contains($l)) {
                $this->wishlistsScheduledForDeletion->remove($this->wishlistsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildWishlist $wishlist The ChildWishlist object to add.
     */
    protected function doAddWishlist(ChildWishlist $wishlist): void
    {
        $this->collWishlists[]= $wishlist;
        $wishlist->setUser($this);
    }

    /**
     * @param ChildWishlist $wishlist The ChildWishlist object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeWishlist(ChildWishlist $wishlist)
    {
        if ($this->getWishlists()->contains($wishlist)) {
            $pos = $this->collWishlists->search($wishlist);
            $this->collWishlists->remove($pos);
            if (null === $this->wishlistsScheduledForDeletion) {
                $this->wishlistsScheduledForDeletion = clone $this->collWishlists;
                $this->wishlistsScheduledForDeletion->clear();
            }
            $this->wishlistsScheduledForDeletion[]= $wishlist;
            $wishlist->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Wishlists from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildWishlist[] List of ChildWishlist objects
     * @phpstan-return ObjectCollection&\Traversable<ChildWishlist}> List of ChildWishlist objects
     */
    public function getWishlistsJoinSite(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildWishlistQuery::create(null, $criteria);
        $query->joinWith('Site', $joinBehavior);

        return $this->getWishlists($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     *
     * @return $this
     */
    public function clear()
    {
        if (null !== $this->aSite) {
            $this->aSite->removeUser($this);
        }
        $this->id = null;
        $this->site_id = null;
        $this->email = null;
        $this->emailvalidatedat = null;
        $this->lastloggedat = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);

        return $this;
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param bool $deep Whether to also clear the references on all referrer objects.
     * @return $this
     */
    public function clearAllReferences(bool $deep = false)
    {
        if ($deep) {
            if ($this->collAlerts) {
                foreach ($this->collAlerts as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCartsRelatedByUserId) {
                foreach ($this->collCartsRelatedByUserId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCartsRelatedBySellerUserId) {
                foreach ($this->collCartsRelatedBySellerUserId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCoupons) {
                foreach ($this->collCoupons as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCustomers) {
                foreach ($this->collCustomers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collDownloads) {
                foreach ($this->collDownloads as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collFiles) {
                foreach ($this->collFiles as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLinks) {
                foreach ($this->collLinks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collStockItemLists) {
                foreach ($this->collStockItemLists as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collOptions) {
                foreach ($this->collOptions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collOrders) {
                foreach ($this->collOrders as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPermissions) {
                foreach ($this->collPermissions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPosts) {
                foreach ($this->collPosts as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collRights) {
                foreach ($this->collRights as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collRoles) {
                foreach ($this->collRoles as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSessions) {
                foreach ($this->collSessions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collStocks) {
                foreach ($this->collStocks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSubscriptions) {
                foreach ($this->collSubscriptions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collAuthenticationMethods) {
                foreach ($this->collAuthenticationMethods as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collVotes) {
                foreach ($this->collVotes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collWishes) {
                foreach ($this->collWishes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collWishlists) {
                foreach ($this->collWishlists as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collAlerts = null;
        $this->collCartsRelatedByUserId = null;
        $this->collCartsRelatedBySellerUserId = null;
        $this->collCoupons = null;
        $this->collCustomers = null;
        $this->collDownloads = null;
        $this->collFiles = null;
        $this->collLinks = null;
        $this->collStockItemLists = null;
        $this->collOptions = null;
        $this->collOrders = null;
        $this->collPermissions = null;
        $this->collPosts = null;
        $this->collRights = null;
        $this->collRoles = null;
        $this->collSessions = null;
        $this->collStocks = null;
        $this->collSubscriptions = null;
        $this->collAuthenticationMethods = null;
        $this->collVotes = null;
        $this->collWishes = null;
        $this->collWishlists = null;
        $this->aSite = null;
        return $this;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(UserTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return $this The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[UserTableMap::COL_UPDATED_AT] = true;

        return $this;
    }

    /**
     * Code to be run before persisting the object
     * @param ConnectionInterface|null $con
     * @return bool
     */
    public function preSave(?ConnectionInterface $con = null): bool
    {
                return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface|null $con
     * @return void
     */
    public function postSave(?ConnectionInterface $con = null): void
    {
            }

    /**
     * Code to be run before inserting to database
     * @param ConnectionInterface|null $con
     * @return bool
     */
    public function preInsert(?ConnectionInterface $con = null): bool
    {
                return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface|null $con
     * @return void
     */
    public function postInsert(?ConnectionInterface $con = null): void
    {
            }

    /**
     * Code to be run before updating the object in database
     * @param ConnectionInterface|null $con
     * @return bool
     */
    public function preUpdate(?ConnectionInterface $con = null): bool
    {
                return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface|null $con
     * @return void
     */
    public function postUpdate(?ConnectionInterface $con = null): void
    {
            }

    /**
     * Code to be run before deleting the object in database
     * @param ConnectionInterface|null $con
     * @return bool
     */
    public function preDelete(?ConnectionInterface $con = null): bool
    {
                return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface|null $con
     * @return void
     */
    public function postDelete(?ConnectionInterface $con = null): void
    {
            }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);
            $inputData = $params[0];
            $keyType = $params[1] ?? TableMap::TYPE_PHPNAME;

            return $this->importFrom($format, $inputData, $keyType);
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = $params[0] ?? true;
            $keyType = $params[1] ?? TableMap::TYPE_PHPNAME;

            return $this->exportTo($format, $includeLazyLoadColumns, $keyType);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
