<?php

namespace Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Model\Country as ChildCountry;
use Model\CountryQuery as ChildCountryQuery;
use Model\Order as ChildOrder;
use Model\OrderQuery as ChildOrderQuery;
use Model\Payment as ChildPayment;
use Model\PaymentQuery as ChildPaymentQuery;
use Model\ShippingOption as ChildShippingOption;
use Model\ShippingOptionQuery as ChildShippingOptionQuery;
use Model\Site as ChildSite;
use Model\SiteQuery as ChildSiteQuery;
use Model\Stock as ChildStock;
use Model\StockQuery as ChildStockQuery;
use Model\User as ChildUser;
use Model\UserQuery as ChildUserQuery;
use Model\Map\OrderTableMap;
use Model\Map\PaymentTableMap;
use Model\Map\StockTableMap;
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
 * Base class that represents a row from the 'orders' table.
 *
 *
 *
 * @package    propel.generator.Model.Base
 */
abstract class Order implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\Model\\Map\\OrderTableMap';


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
     * The value for the order_id field.
     *
     * @var        int
     */
    protected $order_id;

    /**
     * The value for the order_url field.
     *
     * @var        string|null
     */
    protected $order_url;

    /**
     * The value for the site_id field.
     *
     * @var        int|null
     */
    protected $site_id;

    /**
     * The value for the axys_account_id field.
     *
     * @var        int|null
     */
    protected $axys_account_id;

    /**
     * The value for the user_id field.
     *
     * @var        int|null
     */
    protected $user_id;

    /**
     * The value for the customer_id field.
     *
     * @var        int|null
     */
    protected $customer_id;

    /**
     * The value for the seller_id field.
     *
     * @var        int|null
     */
    protected $seller_id;

    /**
     * The value for the order_type field.
     *
     * Note: this column has a database default value of: ''
     * @var        string|null
     */
    protected $order_type;

    /**
     * The value for the order_as_a_gift field.
     *
     * @var        string|null
     */
    protected $order_as_a_gift;

    /**
     * The value for the order_gift_recipient field.
     *
     * @var        int|null
     */
    protected $order_gift_recipient;

    /**
     * The value for the order_amount field.
     *
     * Note: this column has a database default value of: 0
     * @var        int|null
     */
    protected $order_amount;

    /**
     * The value for the order_discount field.
     *
     * @var        int|null
     */
    protected $order_discount;

    /**
     * The value for the order_amount_tobepaid field.
     *
     * Note: this column has a database default value of: 0
     * @var        int|null
     */
    protected $order_amount_tobepaid;

    /**
     * The value for the shipping_id field.
     *
     * @var        int|null
     */
    protected $shipping_id;

    /**
     * The value for the country_id field.
     *
     * @var        int|null
     */
    protected $country_id;

    /**
     * The value for the order_shipping field.
     *
     * Note: this column has a database default value of: 0
     * @var        int|null
     */
    protected $order_shipping;

    /**
     * The value for the order_shipping_mode field.
     *
     * @var        string|null
     */
    protected $order_shipping_mode;

    /**
     * The value for the order_track_number field.
     *
     * @var        string|null
     */
    protected $order_track_number;

    /**
     * The value for the mondial_relay_pickup_point_code field.
     *
     * @var        string|null
     */
    protected $mondial_relay_pickup_point_code;

    /**
     * The value for the order_payment_mode field.
     *
     * @var        string|null
     */
    protected $order_payment_mode;

    /**
     * The value for the order_payment_cash field.
     *
     * Note: this column has a database default value of: 0
     * @var        int|null
     */
    protected $order_payment_cash;

    /**
     * The value for the order_payment_cheque field.
     *
     * Note: this column has a database default value of: 0
     * @var        int|null
     */
    protected $order_payment_cheque;

    /**
     * The value for the order_payment_transfer field.
     *
     * Note: this column has a database default value of: 0
     * @var        int|null
     */
    protected $order_payment_transfer;

    /**
     * The value for the order_payment_card field.
     *
     * Note: this column has a database default value of: 0
     * @var        int|null
     */
    protected $order_payment_card;

    /**
     * The value for the order_payment_paypal field.
     *
     * Note: this column has a database default value of: 0
     * @var        int|null
     */
    protected $order_payment_paypal;

    /**
     * The value for the order_payment_payplug field.
     *
     * Note: this column has a database default value of: 0
     * @var        int|null
     */
    protected $order_payment_payplug;

    /**
     * The value for the order_payment_left field.
     *
     * Note: this column has a database default value of: 0
     * @var        int|null
     */
    protected $order_payment_left;

    /**
     * The value for the order_title field.
     *
     * @var        string|null
     */
    protected $order_title;

    /**
     * The value for the order_firstname field.
     *
     * @var        string|null
     */
    protected $order_firstname;

    /**
     * The value for the order_lastname field.
     *
     * @var        string|null
     */
    protected $order_lastname;

    /**
     * The value for the order_address1 field.
     *
     * @var        string|null
     */
    protected $order_address1;

    /**
     * The value for the order_address2 field.
     *
     * @var        string|null
     */
    protected $order_address2;

    /**
     * The value for the order_postalcode field.
     *
     * @var        string|null
     */
    protected $order_postalcode;

    /**
     * The value for the order_city field.
     *
     * @var        string|null
     */
    protected $order_city;

    /**
     * The value for the order_country field.
     *
     * @var        string|null
     */
    protected $order_country;

    /**
     * The value for the order_email field.
     *
     * @var        string|null
     */
    protected $order_email;

    /**
     * The value for the order_phone field.
     *
     * @var        string|null
     */
    protected $order_phone;

    /**
     * The value for the order_comment field.
     *
     * @var        string|null
     */
    protected $order_comment;

    /**
     * The value for the order_utmz field.
     *
     * @var        string|null
     */
    protected $order_utmz;

    /**
     * The value for the order_referer field.
     *
     * @var        string|null
     */
    protected $order_referer;

    /**
     * The value for the order_insert field.
     *
     * @var        DateTime|null
     */
    protected $order_insert;

    /**
     * The value for the order_payment_date field.
     *
     * @var        DateTime|null
     */
    protected $order_payment_date;

    /**
     * The value for the order_shipping_date field.
     *
     * @var        DateTime|null
     */
    protected $order_shipping_date;

    /**
     * The value for the order_followup_date field.
     *
     * @var        DateTime|null
     */
    protected $order_followup_date;

    /**
     * The value for the order_confirmation_date field.
     *
     * @var        DateTime|null
     */
    protected $order_confirmation_date;

    /**
     * The value for the order_cancel_date field.
     *
     * @var        DateTime|null
     */
    protected $order_cancel_date;

    /**
     * The value for the order_update field.
     *
     * @var        DateTime|null
     */
    protected $order_update;

    /**
     * The value for the order_created field.
     *
     * @var        DateTime|null
     */
    protected $order_created;

    /**
     * The value for the order_updated field.
     *
     * @var        DateTime|null
     */
    protected $order_updated;

    /**
     * @var        ChildUser
     */
    protected $aUser;

    /**
     * @var        ChildShippingOption
     */
    protected $aShippingOption;

    /**
     * @var        ChildCountry
     */
    protected $aCountry;

    /**
     * @var        ChildSite
     */
    protected $aSite;

    /**
     * @var        ObjectCollection|ChildPayment[] Collection to store aggregation of ChildPayment objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildPayment> Collection to store aggregation of ChildPayment objects.
     */
    protected $collPayments;
    protected $collPaymentsPartial;

    /**
     * @var        ObjectCollection|ChildStock[] Collection to store aggregation of ChildStock objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildStock> Collection to store aggregation of ChildStock objects.
     */
    protected $collStockItems;
    protected $collStockItemsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var bool
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPayment[]
     * @phpstan-var ObjectCollection&\Traversable<ChildPayment>
     */
    protected $paymentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildStock[]
     * @phpstan-var ObjectCollection&\Traversable<ChildStock>
     */
    protected $stockItemsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues(): void
    {
        $this->order_type = '';
        $this->order_amount = 0;
        $this->order_amount_tobepaid = 0;
        $this->order_shipping = 0;
        $this->order_payment_cash = 0;
        $this->order_payment_cheque = 0;
        $this->order_payment_transfer = 0;
        $this->order_payment_card = 0;
        $this->order_payment_paypal = 0;
        $this->order_payment_payplug = 0;
        $this->order_payment_left = 0;
    }

    /**
     * Initializes internal state of Model\Base\Order object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
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
     * Compares this with another <code>Order</code> instance.  If
     * <code>obj</code> is an instance of <code>Order</code>, delegates to
     * <code>equals(Order)</code>.  Otherwise, returns <code>false</code>.
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
     * Get the [order_id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->order_id;
    }

    /**
     * Get the [order_url] column value.
     *
     * @return string|null
     */
    public function getSlug()
    {
        return $this->order_url;
    }

    /**
     * Get the [site_id] column value.
     *
     * @return int|null
     */
    public function getSiteId()
    {
        return $this->site_id;
    }

    /**
     * Get the [axys_account_id] column value.
     *
     * @return int|null
     */
    public function getAxysAccountId()
    {
        return $this->axys_account_id;
    }

    /**
     * Get the [user_id] column value.
     *
     * @return int|null
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Get the [customer_id] column value.
     *
     * @return int|null
     */
    public function getCustomerId()
    {
        return $this->customer_id;
    }

    /**
     * Get the [seller_id] column value.
     *
     * @return int|null
     */
    public function getSellerId()
    {
        return $this->seller_id;
    }

    /**
     * Get the [order_type] column value.
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->order_type;
    }

    /**
     * Get the [order_as_a_gift] column value.
     *
     * @return string|null
     */
    public function getAsAGift()
    {
        return $this->order_as_a_gift;
    }

    /**
     * Get the [order_gift_recipient] column value.
     *
     * @return int|null
     */
    public function getGiftRecipient()
    {
        return $this->order_gift_recipient;
    }

    /**
     * Get the [order_amount] column value.
     *
     * @return int|null
     */
    public function getAmount()
    {
        return $this->order_amount;
    }

    /**
     * Get the [order_discount] column value.
     *
     * @return int|null
     */
    public function getDiscount()
    {
        return $this->order_discount;
    }

    /**
     * Get the [order_amount_tobepaid] column value.
     *
     * @return int|null
     */
    public function getAmountTobepaid()
    {
        return $this->order_amount_tobepaid;
    }

    /**
     * Get the [shipping_id] column value.
     *
     * @return int|null
     */
    public function getShippingId()
    {
        return $this->shipping_id;
    }

    /**
     * Get the [country_id] column value.
     *
     * @return int|null
     */
    public function getCountryId()
    {
        return $this->country_id;
    }

    /**
     * Get the [order_shipping] column value.
     *
     * @return int|null
     */
    public function getShippingCost()
    {
        return $this->order_shipping;
    }

    /**
     * Get the [order_shipping_mode] column value.
     *
     * @return string|null
     */
    public function getShippingMode()
    {
        return $this->order_shipping_mode;
    }

    /**
     * Get the [order_track_number] column value.
     *
     * @return string|null
     */
    public function getTrackNumber()
    {
        return $this->order_track_number;
    }

    /**
     * Get the [mondial_relay_pickup_point_code] column value.
     *
     * @return string|null
     */
    public function getMondialRelayPickupPointCode()
    {
        return $this->mondial_relay_pickup_point_code;
    }

    /**
     * Get the [order_payment_mode] column value.
     *
     * @return string|null
     */
    public function getPaymentMode()
    {
        return $this->order_payment_mode;
    }

    /**
     * Get the [order_payment_cash] column value.
     *
     * @return int|null
     */
    public function getPaymentCash()
    {
        return $this->order_payment_cash;
    }

    /**
     * Get the [order_payment_cheque] column value.
     *
     * @return int|null
     */
    public function getPaymentCheque()
    {
        return $this->order_payment_cheque;
    }

    /**
     * Get the [order_payment_transfer] column value.
     *
     * @return int|null
     */
    public function getPaymentTransfer()
    {
        return $this->order_payment_transfer;
    }

    /**
     * Get the [order_payment_card] column value.
     *
     * @return int|null
     */
    public function getPaymentCard()
    {
        return $this->order_payment_card;
    }

    /**
     * Get the [order_payment_paypal] column value.
     *
     * @return int|null
     */
    public function getPaymentPaypal()
    {
        return $this->order_payment_paypal;
    }

    /**
     * Get the [order_payment_payplug] column value.
     *
     * @return int|null
     */
    public function getPaymentPayplug()
    {
        return $this->order_payment_payplug;
    }

    /**
     * Get the [order_payment_left] column value.
     *
     * @return int|null
     */
    public function getPaymentLeft()
    {
        return $this->order_payment_left;
    }

    /**
     * Get the [order_title] column value.
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->order_title;
    }

    /**
     * Get the [order_firstname] column value.
     *
     * @return string|null
     */
    public function getFirstname()
    {
        return $this->order_firstname;
    }

    /**
     * Get the [order_lastname] column value.
     *
     * @return string|null
     */
    public function getLastname()
    {
        return $this->order_lastname;
    }

    /**
     * Get the [order_address1] column value.
     *
     * @return string|null
     */
    public function getAddress1()
    {
        return $this->order_address1;
    }

    /**
     * Get the [order_address2] column value.
     *
     * @return string|null
     */
    public function getAddress2()
    {
        return $this->order_address2;
    }

    /**
     * Get the [order_postalcode] column value.
     *
     * @return string|null
     */
    public function getPostalcode()
    {
        return $this->order_postalcode;
    }

    /**
     * Get the [order_city] column value.
     *
     * @return string|null
     */
    public function getCity()
    {
        return $this->order_city;
    }

    /**
     * Get the [order_country] column value.
     *
     * @return string|null
     */
    public function getCountryName()
    {
        return $this->order_country;
    }

    /**
     * Get the [order_email] column value.
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->order_email;
    }

    /**
     * Get the [order_phone] column value.
     *
     * @return string|null
     */
    public function getPhone()
    {
        return $this->order_phone;
    }

    /**
     * Get the [order_comment] column value.
     *
     * @return string|null
     */
    public function getComment()
    {
        return $this->order_comment;
    }

    /**
     * Get the [order_utmz] column value.
     *
     * @return string|null
     */
    public function getUtmz()
    {
        return $this->order_utmz;
    }

    /**
     * Get the [order_referer] column value.
     *
     * @return string|null
     */
    public function getReferer()
    {
        return $this->order_referer;
    }

    /**
     * Get the [optionally formatted] temporal [order_insert] column value.
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
    public function getInsert($format = null)
    {
        if ($format === null) {
            return $this->order_insert;
        } else {
            return $this->order_insert instanceof \DateTimeInterface ? $this->order_insert->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [order_payment_date] column value.
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
    public function getPaymentDate($format = null)
    {
        if ($format === null) {
            return $this->order_payment_date;
        } else {
            return $this->order_payment_date instanceof \DateTimeInterface ? $this->order_payment_date->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [order_shipping_date] column value.
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
    public function getShippingDate($format = null)
    {
        if ($format === null) {
            return $this->order_shipping_date;
        } else {
            return $this->order_shipping_date instanceof \DateTimeInterface ? $this->order_shipping_date->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [order_followup_date] column value.
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
    public function getFollowupDate($format = null)
    {
        if ($format === null) {
            return $this->order_followup_date;
        } else {
            return $this->order_followup_date instanceof \DateTimeInterface ? $this->order_followup_date->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [order_confirmation_date] column value.
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
    public function getConfirmationDate($format = null)
    {
        if ($format === null) {
            return $this->order_confirmation_date;
        } else {
            return $this->order_confirmation_date instanceof \DateTimeInterface ? $this->order_confirmation_date->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [order_cancel_date] column value.
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
    public function getCancelDate($format = null)
    {
        if ($format === null) {
            return $this->order_cancel_date;
        } else {
            return $this->order_cancel_date instanceof \DateTimeInterface ? $this->order_cancel_date->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [order_update] column value.
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
    public function getUpdate($format = null)
    {
        if ($format === null) {
            return $this->order_update;
        } else {
            return $this->order_update instanceof \DateTimeInterface ? $this->order_update->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [order_created] column value.
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
            return $this->order_created;
        } else {
            return $this->order_created instanceof \DateTimeInterface ? $this->order_created->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [order_updated] column value.
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
            return $this->order_updated;
        } else {
            return $this->order_updated instanceof \DateTimeInterface ? $this->order_updated->format($format) : null;
        }
    }

    /**
     * Set the value of [order_id] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->order_id !== $v) {
            $this->order_id = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_url] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setSlug($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->order_url !== $v) {
            $this->order_url = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_URL] = true;
        }

        return $this;
    }

    /**
     * Set the value of [site_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setSiteId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->site_id !== $v) {
            $this->site_id = $v;
            $this->modifiedColumns[OrderTableMap::COL_SITE_ID] = true;
        }

        if ($this->aSite !== null && $this->aSite->getId() !== $v) {
            $this->aSite = null;
        }

        return $this;
    }

    /**
     * Set the value of [axys_account_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setAxysAccountId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->axys_account_id !== $v) {
            $this->axys_account_id = $v;
            $this->modifiedColumns[OrderTableMap::COL_AXYS_ACCOUNT_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [user_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setUserId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->user_id !== $v) {
            $this->user_id = $v;
            $this->modifiedColumns[OrderTableMap::COL_USER_ID] = true;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }

        return $this;
    }

    /**
     * Set the value of [customer_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setCustomerId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->customer_id !== $v) {
            $this->customer_id = $v;
            $this->modifiedColumns[OrderTableMap::COL_CUSTOMER_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [seller_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setSellerId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->seller_id !== $v) {
            $this->seller_id = $v;
            $this->modifiedColumns[OrderTableMap::COL_SELLER_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_type] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setType($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->order_type !== $v) {
            $this->order_type = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_TYPE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_as_a_gift] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setAsAGift($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->order_as_a_gift !== $v) {
            $this->order_as_a_gift = $v;
            $this->modifiedColumns[OrderTableMap::COL_AS_A_GIFT] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_gift_recipient] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setGiftRecipient($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->order_gift_recipient !== $v) {
            $this->order_gift_recipient = $v;
            $this->modifiedColumns[OrderTableMap::COL_GIFT_RECIPIENT] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_amount] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setAmount($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->order_amount !== $v) {
            $this->order_amount = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_AMOUNT] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_discount] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setDiscount($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->order_discount !== $v) {
            $this->order_discount = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_DISCOUNT] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_amount_tobepaid] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setAmountTobepaid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->order_amount_tobepaid !== $v) {
            $this->order_amount_tobepaid = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_AMOUNT_TOBEPAID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [shipping_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setShippingId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->shipping_id !== $v) {
            $this->shipping_id = $v;
            $this->modifiedColumns[OrderTableMap::COL_SHIPPING_ID] = true;
        }

        if ($this->aShippingOption !== null && $this->aShippingOption->getId() !== $v) {
            $this->aShippingOption = null;
        }

        return $this;
    }

    /**
     * Set the value of [country_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setCountryId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->country_id !== $v) {
            $this->country_id = $v;
            $this->modifiedColumns[OrderTableMap::COL_COUNTRY_ID] = true;
        }

        if ($this->aCountry !== null && $this->aCountry->getId() !== $v) {
            $this->aCountry = null;
        }

        return $this;
    }

    /**
     * Set the value of [order_shipping] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setShippingCost($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->order_shipping !== $v) {
            $this->order_shipping = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_SHIPPING] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_shipping_mode] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setShippingMode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->order_shipping_mode !== $v) {
            $this->order_shipping_mode = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_SHIPPING_MODE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_track_number] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setTrackNumber($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->order_track_number !== $v) {
            $this->order_track_number = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_TRACK_NUMBER] = true;
        }

        return $this;
    }

    /**
     * Set the value of [mondial_relay_pickup_point_code] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setMondialRelayPickupPointCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->mondial_relay_pickup_point_code !== $v) {
            $this->mondial_relay_pickup_point_code = $v;
            $this->modifiedColumns[OrderTableMap::COL_MONDIAL_RELAY_PICKUP_POINT_CODE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_payment_mode] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPaymentMode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->order_payment_mode !== $v) {
            $this->order_payment_mode = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_PAYMENT_MODE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_payment_cash] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPaymentCash($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->order_payment_cash !== $v) {
            $this->order_payment_cash = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_PAYMENT_CASH] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_payment_cheque] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPaymentCheque($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->order_payment_cheque !== $v) {
            $this->order_payment_cheque = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_PAYMENT_CHEQUE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_payment_transfer] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPaymentTransfer($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->order_payment_transfer !== $v) {
            $this->order_payment_transfer = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_PAYMENT_TRANSFER] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_payment_card] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPaymentCard($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->order_payment_card !== $v) {
            $this->order_payment_card = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_PAYMENT_CARD] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_payment_paypal] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPaymentPaypal($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->order_payment_paypal !== $v) {
            $this->order_payment_paypal = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_PAYMENT_PAYPAL] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_payment_payplug] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPaymentPayplug($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->order_payment_payplug !== $v) {
            $this->order_payment_payplug = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_PAYMENT_PAYPLUG] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_payment_left] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPaymentLeft($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->order_payment_left !== $v) {
            $this->order_payment_left = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_PAYMENT_LEFT] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_title] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->order_title !== $v) {
            $this->order_title = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_TITLE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_firstname] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setFirstname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->order_firstname !== $v) {
            $this->order_firstname = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_FIRSTNAME] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_lastname] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setLastname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->order_lastname !== $v) {
            $this->order_lastname = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_LASTNAME] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_address1] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setAddress1($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->order_address1 !== $v) {
            $this->order_address1 = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_ADDRESS1] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_address2] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setAddress2($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->order_address2 !== $v) {
            $this->order_address2 = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_ADDRESS2] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_postalcode] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPostalcode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->order_postalcode !== $v) {
            $this->order_postalcode = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_POSTALCODE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_city] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setCity($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->order_city !== $v) {
            $this->order_city = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_CITY] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_country] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setCountryName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->order_country !== $v) {
            $this->order_country = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_COUNTRY] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_email] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->order_email !== $v) {
            $this->order_email = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_EMAIL] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_phone] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPhone($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->order_phone !== $v) {
            $this->order_phone = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_PHONE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_comment] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setComment($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->order_comment !== $v) {
            $this->order_comment = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_COMMENT] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_utmz] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setUtmz($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->order_utmz !== $v) {
            $this->order_utmz = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_UTMZ] = true;
        }

        return $this;
    }

    /**
     * Set the value of [order_referer] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setReferer($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->order_referer !== $v) {
            $this->order_referer = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_REFERER] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [order_insert] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setInsert($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->order_insert !== null || $dt !== null) {
            if ($this->order_insert === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->order_insert->format("Y-m-d H:i:s.u")) {
                $this->order_insert = $dt === null ? null : clone $dt;
                $this->modifiedColumns[OrderTableMap::COL_ORDER_INSERT] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [order_payment_date] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setPaymentDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->order_payment_date !== null || $dt !== null) {
            if ($this->order_payment_date === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->order_payment_date->format("Y-m-d H:i:s.u")) {
                $this->order_payment_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[OrderTableMap::COL_ORDER_PAYMENT_DATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [order_shipping_date] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setShippingDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->order_shipping_date !== null || $dt !== null) {
            if ($this->order_shipping_date === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->order_shipping_date->format("Y-m-d H:i:s.u")) {
                $this->order_shipping_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[OrderTableMap::COL_ORDER_SHIPPING_DATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [order_followup_date] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setFollowupDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->order_followup_date !== null || $dt !== null) {
            if ($this->order_followup_date === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->order_followup_date->format("Y-m-d H:i:s.u")) {
                $this->order_followup_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[OrderTableMap::COL_ORDER_FOLLOWUP_DATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [order_confirmation_date] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setConfirmationDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->order_confirmation_date !== null || $dt !== null) {
            if ($this->order_confirmation_date === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->order_confirmation_date->format("Y-m-d H:i:s.u")) {
                $this->order_confirmation_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[OrderTableMap::COL_ORDER_CONFIRMATION_DATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [order_cancel_date] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setCancelDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->order_cancel_date !== null || $dt !== null) {
            if ($this->order_cancel_date === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->order_cancel_date->format("Y-m-d H:i:s.u")) {
                $this->order_cancel_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[OrderTableMap::COL_ORDER_CANCEL_DATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [order_update] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->order_update !== null || $dt !== null) {
            if ($this->order_update === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->order_update->format("Y-m-d H:i:s.u")) {
                $this->order_update = $dt === null ? null : clone $dt;
                $this->modifiedColumns[OrderTableMap::COL_ORDER_UPDATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [order_created] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->order_created !== null || $dt !== null) {
            if ($this->order_created === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->order_created->format("Y-m-d H:i:s.u")) {
                $this->order_created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[OrderTableMap::COL_ORDER_CREATED] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [order_updated] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->order_updated !== null || $dt !== null) {
            if ($this->order_updated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->order_updated->format("Y-m-d H:i:s.u")) {
                $this->order_updated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[OrderTableMap::COL_ORDER_UPDATED] = true;
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
            if ($this->order_type !== '') {
                return false;
            }

            if ($this->order_amount !== 0) {
                return false;
            }

            if ($this->order_amount_tobepaid !== 0) {
                return false;
            }

            if ($this->order_shipping !== 0) {
                return false;
            }

            if ($this->order_payment_cash !== 0) {
                return false;
            }

            if ($this->order_payment_cheque !== 0) {
                return false;
            }

            if ($this->order_payment_transfer !== 0) {
                return false;
            }

            if ($this->order_payment_card !== 0) {
                return false;
            }

            if ($this->order_payment_paypal !== 0) {
                return false;
            }

            if ($this->order_payment_payplug !== 0) {
                return false;
            }

            if ($this->order_payment_left !== 0) {
                return false;
            }

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : OrderTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : OrderTableMap::translateFieldName('Slug', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_url = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : OrderTableMap::translateFieldName('SiteId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : OrderTableMap::translateFieldName('AxysAccountId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->axys_account_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : OrderTableMap::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : OrderTableMap::translateFieldName('CustomerId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->customer_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : OrderTableMap::translateFieldName('SellerId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->seller_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : OrderTableMap::translateFieldName('Type', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_type = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : OrderTableMap::translateFieldName('AsAGift', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_as_a_gift = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : OrderTableMap::translateFieldName('GiftRecipient', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_gift_recipient = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : OrderTableMap::translateFieldName('Amount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_amount = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : OrderTableMap::translateFieldName('Discount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_discount = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : OrderTableMap::translateFieldName('AmountTobepaid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_amount_tobepaid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : OrderTableMap::translateFieldName('ShippingId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->shipping_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : OrderTableMap::translateFieldName('CountryId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->country_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : OrderTableMap::translateFieldName('ShippingCost', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_shipping = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : OrderTableMap::translateFieldName('ShippingMode', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_shipping_mode = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 17 + $startcol : OrderTableMap::translateFieldName('TrackNumber', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_track_number = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 18 + $startcol : OrderTableMap::translateFieldName('MondialRelayPickupPointCode', TableMap::TYPE_PHPNAME, $indexType)];
            $this->mondial_relay_pickup_point_code = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 19 + $startcol : OrderTableMap::translateFieldName('PaymentMode', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_payment_mode = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 20 + $startcol : OrderTableMap::translateFieldName('PaymentCash', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_payment_cash = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 21 + $startcol : OrderTableMap::translateFieldName('PaymentCheque', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_payment_cheque = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 22 + $startcol : OrderTableMap::translateFieldName('PaymentTransfer', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_payment_transfer = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 23 + $startcol : OrderTableMap::translateFieldName('PaymentCard', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_payment_card = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 24 + $startcol : OrderTableMap::translateFieldName('PaymentPaypal', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_payment_paypal = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 25 + $startcol : OrderTableMap::translateFieldName('PaymentPayplug', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_payment_payplug = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 26 + $startcol : OrderTableMap::translateFieldName('PaymentLeft', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_payment_left = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 27 + $startcol : OrderTableMap::translateFieldName('Title', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_title = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 28 + $startcol : OrderTableMap::translateFieldName('Firstname', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_firstname = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 29 + $startcol : OrderTableMap::translateFieldName('Lastname', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_lastname = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 30 + $startcol : OrderTableMap::translateFieldName('Address1', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_address1 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 31 + $startcol : OrderTableMap::translateFieldName('Address2', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_address2 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 32 + $startcol : OrderTableMap::translateFieldName('Postalcode', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_postalcode = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 33 + $startcol : OrderTableMap::translateFieldName('City', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_city = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 34 + $startcol : OrderTableMap::translateFieldName('CountryName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_country = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 35 + $startcol : OrderTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_email = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 36 + $startcol : OrderTableMap::translateFieldName('Phone', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_phone = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 37 + $startcol : OrderTableMap::translateFieldName('Comment', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_comment = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 38 + $startcol : OrderTableMap::translateFieldName('Utmz', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_utmz = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 39 + $startcol : OrderTableMap::translateFieldName('Referer', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_referer = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 40 + $startcol : OrderTableMap::translateFieldName('Insert', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->order_insert = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 41 + $startcol : OrderTableMap::translateFieldName('PaymentDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->order_payment_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 42 + $startcol : OrderTableMap::translateFieldName('ShippingDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->order_shipping_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 43 + $startcol : OrderTableMap::translateFieldName('FollowupDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->order_followup_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 44 + $startcol : OrderTableMap::translateFieldName('ConfirmationDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->order_confirmation_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 45 + $startcol : OrderTableMap::translateFieldName('CancelDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->order_cancel_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 46 + $startcol : OrderTableMap::translateFieldName('Update', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->order_update = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 47 + $startcol : OrderTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->order_created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 48 + $startcol : OrderTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->order_updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 49; // 49 = OrderTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\Order'), 0, $e);
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
        if ($this->aUser !== null && $this->user_id !== $this->aUser->getId()) {
            $this->aUser = null;
        }
        if ($this->aShippingOption !== null && $this->shipping_id !== $this->aShippingOption->getId()) {
            $this->aShippingOption = null;
        }
        if ($this->aCountry !== null && $this->country_id !== $this->aCountry->getId()) {
            $this->aCountry = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(OrderTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildOrderQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUser = null;
            $this->aShippingOption = null;
            $this->aCountry = null;
            $this->aSite = null;
            $this->collPayments = null;

            $this->collStockItems = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param ConnectionInterface $con
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     * @see Order::setDeleted()
     * @see Order::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(OrderTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildOrderQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(OrderTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(OrderTableMap::COL_ORDER_CREATED)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(OrderTableMap::COL_ORDER_UPDATED)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(OrderTableMap::COL_ORDER_UPDATED)) {
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
                OrderTableMap::addInstanceToPool($this);
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

            if ($this->aUser !== null) {
                if ($this->aUser->isModified() || $this->aUser->isNew()) {
                    $affectedRows += $this->aUser->save($con);
                }
                $this->setUser($this->aUser);
            }

            if ($this->aShippingOption !== null) {
                if ($this->aShippingOption->isModified() || $this->aShippingOption->isNew()) {
                    $affectedRows += $this->aShippingOption->save($con);
                }
                $this->setShippingOption($this->aShippingOption);
            }

            if ($this->aCountry !== null) {
                if ($this->aCountry->isModified() || $this->aCountry->isNew()) {
                    $affectedRows += $this->aCountry->save($con);
                }
                $this->setCountry($this->aCountry);
            }

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

            if ($this->paymentsScheduledForDeletion !== null) {
                if (!$this->paymentsScheduledForDeletion->isEmpty()) {
                    foreach ($this->paymentsScheduledForDeletion as $payment) {
                        // need to save related object because we set the relation to null
                        $payment->save($con);
                    }
                    $this->paymentsScheduledForDeletion = null;
                }
            }

            if ($this->collPayments !== null) {
                foreach ($this->collPayments as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->stockItemsScheduledForDeletion !== null) {
                if (!$this->stockItemsScheduledForDeletion->isEmpty()) {
                    foreach ($this->stockItemsScheduledForDeletion as $stockItem) {
                        // need to save related object because we set the relation to null
                        $stockItem->save($con);
                    }
                    $this->stockItemsScheduledForDeletion = null;
                }
            }

            if ($this->collStockItems !== null) {
                foreach ($this->collStockItems as $referrerFK) {
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

        $this->modifiedColumns[OrderTableMap::COL_ORDER_ID] = true;
        if (null !== $this->order_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . OrderTableMap::COL_ORDER_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'order_id';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_URL)) {
            $modifiedColumns[':p' . $index++]  = 'order_url';
        }
        if ($this->isColumnModified(OrderTableMap::COL_SITE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'site_id';
        }
        if ($this->isColumnModified(OrderTableMap::COL_AXYS_ACCOUNT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'axys_account_id';
        }
        if ($this->isColumnModified(OrderTableMap::COL_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'user_id';
        }
        if ($this->isColumnModified(OrderTableMap::COL_CUSTOMER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'customer_id';
        }
        if ($this->isColumnModified(OrderTableMap::COL_SELLER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'seller_id';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_TYPE)) {
            $modifiedColumns[':p' . $index++]  = 'order_type';
        }
        if ($this->isColumnModified(OrderTableMap::COL_AS_A_GIFT)) {
            $modifiedColumns[':p' . $index++]  = 'order_as_a_gift';
        }
        if ($this->isColumnModified(OrderTableMap::COL_GIFT_RECIPIENT)) {
            $modifiedColumns[':p' . $index++]  = 'order_gift_recipient';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_AMOUNT)) {
            $modifiedColumns[':p' . $index++]  = 'order_amount';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_DISCOUNT)) {
            $modifiedColumns[':p' . $index++]  = 'order_discount';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_AMOUNT_TOBEPAID)) {
            $modifiedColumns[':p' . $index++]  = 'order_amount_tobepaid';
        }
        if ($this->isColumnModified(OrderTableMap::COL_SHIPPING_ID)) {
            $modifiedColumns[':p' . $index++]  = 'shipping_id';
        }
        if ($this->isColumnModified(OrderTableMap::COL_COUNTRY_ID)) {
            $modifiedColumns[':p' . $index++]  = 'country_id';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_SHIPPING)) {
            $modifiedColumns[':p' . $index++]  = 'order_shipping';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_SHIPPING_MODE)) {
            $modifiedColumns[':p' . $index++]  = 'order_shipping_mode';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_TRACK_NUMBER)) {
            $modifiedColumns[':p' . $index++]  = 'order_track_number';
        }
        if ($this->isColumnModified(OrderTableMap::COL_MONDIAL_RELAY_PICKUP_POINT_CODE)) {
            $modifiedColumns[':p' . $index++]  = 'mondial_relay_pickup_point_code';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_PAYMENT_MODE)) {
            $modifiedColumns[':p' . $index++]  = 'order_payment_mode';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_PAYMENT_CASH)) {
            $modifiedColumns[':p' . $index++]  = 'order_payment_cash';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_PAYMENT_CHEQUE)) {
            $modifiedColumns[':p' . $index++]  = 'order_payment_cheque';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_PAYMENT_TRANSFER)) {
            $modifiedColumns[':p' . $index++]  = 'order_payment_transfer';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_PAYMENT_CARD)) {
            $modifiedColumns[':p' . $index++]  = 'order_payment_card';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_PAYMENT_PAYPAL)) {
            $modifiedColumns[':p' . $index++]  = 'order_payment_paypal';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_PAYMENT_PAYPLUG)) {
            $modifiedColumns[':p' . $index++]  = 'order_payment_payplug';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_PAYMENT_LEFT)) {
            $modifiedColumns[':p' . $index++]  = 'order_payment_left';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_TITLE)) {
            $modifiedColumns[':p' . $index++]  = 'order_title';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_FIRSTNAME)) {
            $modifiedColumns[':p' . $index++]  = 'order_firstname';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_LASTNAME)) {
            $modifiedColumns[':p' . $index++]  = 'order_lastname';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_ADDRESS1)) {
            $modifiedColumns[':p' . $index++]  = 'order_address1';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_ADDRESS2)) {
            $modifiedColumns[':p' . $index++]  = 'order_address2';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_POSTALCODE)) {
            $modifiedColumns[':p' . $index++]  = 'order_postalcode';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_CITY)) {
            $modifiedColumns[':p' . $index++]  = 'order_city';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_COUNTRY)) {
            $modifiedColumns[':p' . $index++]  = 'order_country';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'order_email';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_PHONE)) {
            $modifiedColumns[':p' . $index++]  = 'order_phone';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_COMMENT)) {
            $modifiedColumns[':p' . $index++]  = 'order_comment';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_UTMZ)) {
            $modifiedColumns[':p' . $index++]  = 'order_utmz';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_REFERER)) {
            $modifiedColumns[':p' . $index++]  = 'order_referer';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_INSERT)) {
            $modifiedColumns[':p' . $index++]  = 'order_insert';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_PAYMENT_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'order_payment_date';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_SHIPPING_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'order_shipping_date';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_FOLLOWUP_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'order_followup_date';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_CONFIRMATION_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'order_confirmation_date';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_CANCEL_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'order_cancel_date';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_UPDATE)) {
            $modifiedColumns[':p' . $index++]  = 'order_update';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'order_created';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = 'order_updated';
        }

        $sql = sprintf(
            'INSERT INTO orders (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'order_id':
                        $stmt->bindValue($identifier, $this->order_id, PDO::PARAM_INT);

                        break;
                    case 'order_url':
                        $stmt->bindValue($identifier, $this->order_url, PDO::PARAM_STR);

                        break;
                    case 'site_id':
                        $stmt->bindValue($identifier, $this->site_id, PDO::PARAM_INT);

                        break;
                    case 'axys_account_id':
                        $stmt->bindValue($identifier, $this->axys_account_id, PDO::PARAM_INT);

                        break;
                    case 'user_id':
                        $stmt->bindValue($identifier, $this->user_id, PDO::PARAM_INT);

                        break;
                    case 'customer_id':
                        $stmt->bindValue($identifier, $this->customer_id, PDO::PARAM_INT);

                        break;
                    case 'seller_id':
                        $stmt->bindValue($identifier, $this->seller_id, PDO::PARAM_INT);

                        break;
                    case 'order_type':
                        $stmt->bindValue($identifier, $this->order_type, PDO::PARAM_STR);

                        break;
                    case 'order_as_a_gift':
                        $stmt->bindValue($identifier, $this->order_as_a_gift, PDO::PARAM_STR);

                        break;
                    case 'order_gift_recipient':
                        $stmt->bindValue($identifier, $this->order_gift_recipient, PDO::PARAM_INT);

                        break;
                    case 'order_amount':
                        $stmt->bindValue($identifier, $this->order_amount, PDO::PARAM_INT);

                        break;
                    case 'order_discount':
                        $stmt->bindValue($identifier, $this->order_discount, PDO::PARAM_INT);

                        break;
                    case 'order_amount_tobepaid':
                        $stmt->bindValue($identifier, $this->order_amount_tobepaid, PDO::PARAM_INT);

                        break;
                    case 'shipping_id':
                        $stmt->bindValue($identifier, $this->shipping_id, PDO::PARAM_INT);

                        break;
                    case 'country_id':
                        $stmt->bindValue($identifier, $this->country_id, PDO::PARAM_INT);

                        break;
                    case 'order_shipping':
                        $stmt->bindValue($identifier, $this->order_shipping, PDO::PARAM_INT);

                        break;
                    case 'order_shipping_mode':
                        $stmt->bindValue($identifier, $this->order_shipping_mode, PDO::PARAM_STR);

                        break;
                    case 'order_track_number':
                        $stmt->bindValue($identifier, $this->order_track_number, PDO::PARAM_STR);

                        break;
                    case 'mondial_relay_pickup_point_code':
                        $stmt->bindValue($identifier, $this->mondial_relay_pickup_point_code, PDO::PARAM_STR);

                        break;
                    case 'order_payment_mode':
                        $stmt->bindValue($identifier, $this->order_payment_mode, PDO::PARAM_STR);

                        break;
                    case 'order_payment_cash':
                        $stmt->bindValue($identifier, $this->order_payment_cash, PDO::PARAM_INT);

                        break;
                    case 'order_payment_cheque':
                        $stmt->bindValue($identifier, $this->order_payment_cheque, PDO::PARAM_INT);

                        break;
                    case 'order_payment_transfer':
                        $stmt->bindValue($identifier, $this->order_payment_transfer, PDO::PARAM_INT);

                        break;
                    case 'order_payment_card':
                        $stmt->bindValue($identifier, $this->order_payment_card, PDO::PARAM_INT);

                        break;
                    case 'order_payment_paypal':
                        $stmt->bindValue($identifier, $this->order_payment_paypal, PDO::PARAM_INT);

                        break;
                    case 'order_payment_payplug':
                        $stmt->bindValue($identifier, $this->order_payment_payplug, PDO::PARAM_INT);

                        break;
                    case 'order_payment_left':
                        $stmt->bindValue($identifier, $this->order_payment_left, PDO::PARAM_INT);

                        break;
                    case 'order_title':
                        $stmt->bindValue($identifier, $this->order_title, PDO::PARAM_STR);

                        break;
                    case 'order_firstname':
                        $stmt->bindValue($identifier, $this->order_firstname, PDO::PARAM_STR);

                        break;
                    case 'order_lastname':
                        $stmt->bindValue($identifier, $this->order_lastname, PDO::PARAM_STR);

                        break;
                    case 'order_address1':
                        $stmt->bindValue($identifier, $this->order_address1, PDO::PARAM_STR);

                        break;
                    case 'order_address2':
                        $stmt->bindValue($identifier, $this->order_address2, PDO::PARAM_STR);

                        break;
                    case 'order_postalcode':
                        $stmt->bindValue($identifier, $this->order_postalcode, PDO::PARAM_STR);

                        break;
                    case 'order_city':
                        $stmt->bindValue($identifier, $this->order_city, PDO::PARAM_STR);

                        break;
                    case 'order_country':
                        $stmt->bindValue($identifier, $this->order_country, PDO::PARAM_STR);

                        break;
                    case 'order_email':
                        $stmt->bindValue($identifier, $this->order_email, PDO::PARAM_STR);

                        break;
                    case 'order_phone':
                        $stmt->bindValue($identifier, $this->order_phone, PDO::PARAM_STR);

                        break;
                    case 'order_comment':
                        $stmt->bindValue($identifier, $this->order_comment, PDO::PARAM_STR);

                        break;
                    case 'order_utmz':
                        $stmt->bindValue($identifier, $this->order_utmz, PDO::PARAM_STR);

                        break;
                    case 'order_referer':
                        $stmt->bindValue($identifier, $this->order_referer, PDO::PARAM_STR);

                        break;
                    case 'order_insert':
                        $stmt->bindValue($identifier, $this->order_insert ? $this->order_insert->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'order_payment_date':
                        $stmt->bindValue($identifier, $this->order_payment_date ? $this->order_payment_date->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'order_shipping_date':
                        $stmt->bindValue($identifier, $this->order_shipping_date ? $this->order_shipping_date->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'order_followup_date':
                        $stmt->bindValue($identifier, $this->order_followup_date ? $this->order_followup_date->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'order_confirmation_date':
                        $stmt->bindValue($identifier, $this->order_confirmation_date ? $this->order_confirmation_date->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'order_cancel_date':
                        $stmt->bindValue($identifier, $this->order_cancel_date ? $this->order_cancel_date->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'order_update':
                        $stmt->bindValue($identifier, $this->order_update ? $this->order_update->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'order_created':
                        $stmt->bindValue($identifier, $this->order_created ? $this->order_created->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'order_updated':
                        $stmt->bindValue($identifier, $this->order_updated ? $this->order_updated->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

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
        $pos = OrderTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getSlug();

            case 2:
                return $this->getSiteId();

            case 3:
                return $this->getAxysAccountId();

            case 4:
                return $this->getUserId();

            case 5:
                return $this->getCustomerId();

            case 6:
                return $this->getSellerId();

            case 7:
                return $this->getType();

            case 8:
                return $this->getAsAGift();

            case 9:
                return $this->getGiftRecipient();

            case 10:
                return $this->getAmount();

            case 11:
                return $this->getDiscount();

            case 12:
                return $this->getAmountTobepaid();

            case 13:
                return $this->getShippingId();

            case 14:
                return $this->getCountryId();

            case 15:
                return $this->getShippingCost();

            case 16:
                return $this->getShippingMode();

            case 17:
                return $this->getTrackNumber();

            case 18:
                return $this->getMondialRelayPickupPointCode();

            case 19:
                return $this->getPaymentMode();

            case 20:
                return $this->getPaymentCash();

            case 21:
                return $this->getPaymentCheque();

            case 22:
                return $this->getPaymentTransfer();

            case 23:
                return $this->getPaymentCard();

            case 24:
                return $this->getPaymentPaypal();

            case 25:
                return $this->getPaymentPayplug();

            case 26:
                return $this->getPaymentLeft();

            case 27:
                return $this->getTitle();

            case 28:
                return $this->getFirstname();

            case 29:
                return $this->getLastname();

            case 30:
                return $this->getAddress1();

            case 31:
                return $this->getAddress2();

            case 32:
                return $this->getPostalcode();

            case 33:
                return $this->getCity();

            case 34:
                return $this->getCountryName();

            case 35:
                return $this->getEmail();

            case 36:
                return $this->getPhone();

            case 37:
                return $this->getComment();

            case 38:
                return $this->getUtmz();

            case 39:
                return $this->getReferer();

            case 40:
                return $this->getInsert();

            case 41:
                return $this->getPaymentDate();

            case 42:
                return $this->getShippingDate();

            case 43:
                return $this->getFollowupDate();

            case 44:
                return $this->getConfirmationDate();

            case 45:
                return $this->getCancelDate();

            case 46:
                return $this->getUpdate();

            case 47:
                return $this->getCreatedAt();

            case 48:
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
        if (isset($alreadyDumpedObjects['Order'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['Order'][$this->hashCode()] = true;
        $keys = OrderTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getSlug(),
            $keys[2] => $this->getSiteId(),
            $keys[3] => $this->getAxysAccountId(),
            $keys[4] => $this->getUserId(),
            $keys[5] => $this->getCustomerId(),
            $keys[6] => $this->getSellerId(),
            $keys[7] => $this->getType(),
            $keys[8] => $this->getAsAGift(),
            $keys[9] => $this->getGiftRecipient(),
            $keys[10] => $this->getAmount(),
            $keys[11] => $this->getDiscount(),
            $keys[12] => $this->getAmountTobepaid(),
            $keys[13] => $this->getShippingId(),
            $keys[14] => $this->getCountryId(),
            $keys[15] => $this->getShippingCost(),
            $keys[16] => $this->getShippingMode(),
            $keys[17] => $this->getTrackNumber(),
            $keys[18] => $this->getMondialRelayPickupPointCode(),
            $keys[19] => $this->getPaymentMode(),
            $keys[20] => $this->getPaymentCash(),
            $keys[21] => $this->getPaymentCheque(),
            $keys[22] => $this->getPaymentTransfer(),
            $keys[23] => $this->getPaymentCard(),
            $keys[24] => $this->getPaymentPaypal(),
            $keys[25] => $this->getPaymentPayplug(),
            $keys[26] => $this->getPaymentLeft(),
            $keys[27] => $this->getTitle(),
            $keys[28] => $this->getFirstname(),
            $keys[29] => $this->getLastname(),
            $keys[30] => $this->getAddress1(),
            $keys[31] => $this->getAddress2(),
            $keys[32] => $this->getPostalcode(),
            $keys[33] => $this->getCity(),
            $keys[34] => $this->getCountryName(),
            $keys[35] => $this->getEmail(),
            $keys[36] => $this->getPhone(),
            $keys[37] => $this->getComment(),
            $keys[38] => $this->getUtmz(),
            $keys[39] => $this->getReferer(),
            $keys[40] => $this->getInsert(),
            $keys[41] => $this->getPaymentDate(),
            $keys[42] => $this->getShippingDate(),
            $keys[43] => $this->getFollowupDate(),
            $keys[44] => $this->getConfirmationDate(),
            $keys[45] => $this->getCancelDate(),
            $keys[46] => $this->getUpdate(),
            $keys[47] => $this->getCreatedAt(),
            $keys[48] => $this->getUpdatedAt(),
        ];
        if ($result[$keys[40]] instanceof \DateTimeInterface) {
            $result[$keys[40]] = $result[$keys[40]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[41]] instanceof \DateTimeInterface) {
            $result[$keys[41]] = $result[$keys[41]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[42]] instanceof \DateTimeInterface) {
            $result[$keys[42]] = $result[$keys[42]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[43]] instanceof \DateTimeInterface) {
            $result[$keys[43]] = $result[$keys[43]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[44]] instanceof \DateTimeInterface) {
            $result[$keys[44]] = $result[$keys[44]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[45]] instanceof \DateTimeInterface) {
            $result[$keys[45]] = $result[$keys[45]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[46]] instanceof \DateTimeInterface) {
            $result[$keys[46]] = $result[$keys[46]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[47]] instanceof \DateTimeInterface) {
            $result[$keys[47]] = $result[$keys[47]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[48]] instanceof \DateTimeInterface) {
            $result[$keys[48]] = $result[$keys[48]]->format('Y-m-d H:i:s.u');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aUser) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'user';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'users';
                        break;
                    default:
                        $key = 'User';
                }

                $result[$key] = $this->aUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aShippingOption) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'shippingOption';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'shipping';
                        break;
                    default:
                        $key = 'ShippingOption';
                }

                $result[$key] = $this->aShippingOption->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aCountry) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'country';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'countries';
                        break;
                    default:
                        $key = 'Country';
                }

                $result[$key] = $this->aCountry->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
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
            if (null !== $this->collPayments) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'payments';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'paymentss';
                        break;
                    default:
                        $key = 'Payments';
                }

                $result[$key] = $this->collPayments->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collStockItems) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'stocks';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'stocks';
                        break;
                    default:
                        $key = 'StockItems';
                }

                $result[$key] = $this->collStockItems->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = OrderTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setSlug($value);
                break;
            case 2:
                $this->setSiteId($value);
                break;
            case 3:
                $this->setAxysAccountId($value);
                break;
            case 4:
                $this->setUserId($value);
                break;
            case 5:
                $this->setCustomerId($value);
                break;
            case 6:
                $this->setSellerId($value);
                break;
            case 7:
                $this->setType($value);
                break;
            case 8:
                $this->setAsAGift($value);
                break;
            case 9:
                $this->setGiftRecipient($value);
                break;
            case 10:
                $this->setAmount($value);
                break;
            case 11:
                $this->setDiscount($value);
                break;
            case 12:
                $this->setAmountTobepaid($value);
                break;
            case 13:
                $this->setShippingId($value);
                break;
            case 14:
                $this->setCountryId($value);
                break;
            case 15:
                $this->setShippingCost($value);
                break;
            case 16:
                $this->setShippingMode($value);
                break;
            case 17:
                $this->setTrackNumber($value);
                break;
            case 18:
                $this->setMondialRelayPickupPointCode($value);
                break;
            case 19:
                $this->setPaymentMode($value);
                break;
            case 20:
                $this->setPaymentCash($value);
                break;
            case 21:
                $this->setPaymentCheque($value);
                break;
            case 22:
                $this->setPaymentTransfer($value);
                break;
            case 23:
                $this->setPaymentCard($value);
                break;
            case 24:
                $this->setPaymentPaypal($value);
                break;
            case 25:
                $this->setPaymentPayplug($value);
                break;
            case 26:
                $this->setPaymentLeft($value);
                break;
            case 27:
                $this->setTitle($value);
                break;
            case 28:
                $this->setFirstname($value);
                break;
            case 29:
                $this->setLastname($value);
                break;
            case 30:
                $this->setAddress1($value);
                break;
            case 31:
                $this->setAddress2($value);
                break;
            case 32:
                $this->setPostalcode($value);
                break;
            case 33:
                $this->setCity($value);
                break;
            case 34:
                $this->setCountryName($value);
                break;
            case 35:
                $this->setEmail($value);
                break;
            case 36:
                $this->setPhone($value);
                break;
            case 37:
                $this->setComment($value);
                break;
            case 38:
                $this->setUtmz($value);
                break;
            case 39:
                $this->setReferer($value);
                break;
            case 40:
                $this->setInsert($value);
                break;
            case 41:
                $this->setPaymentDate($value);
                break;
            case 42:
                $this->setShippingDate($value);
                break;
            case 43:
                $this->setFollowupDate($value);
                break;
            case 44:
                $this->setConfirmationDate($value);
                break;
            case 45:
                $this->setCancelDate($value);
                break;
            case 46:
                $this->setUpdate($value);
                break;
            case 47:
                $this->setCreatedAt($value);
                break;
            case 48:
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
        $keys = OrderTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setSlug($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setSiteId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setAxysAccountId($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setUserId($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setCustomerId($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setSellerId($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setType($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setAsAGift($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setGiftRecipient($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setAmount($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setDiscount($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setAmountTobepaid($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setShippingId($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setCountryId($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setShippingCost($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setShippingMode($arr[$keys[16]]);
        }
        if (array_key_exists($keys[17], $arr)) {
            $this->setTrackNumber($arr[$keys[17]]);
        }
        if (array_key_exists($keys[18], $arr)) {
            $this->setMondialRelayPickupPointCode($arr[$keys[18]]);
        }
        if (array_key_exists($keys[19], $arr)) {
            $this->setPaymentMode($arr[$keys[19]]);
        }
        if (array_key_exists($keys[20], $arr)) {
            $this->setPaymentCash($arr[$keys[20]]);
        }
        if (array_key_exists($keys[21], $arr)) {
            $this->setPaymentCheque($arr[$keys[21]]);
        }
        if (array_key_exists($keys[22], $arr)) {
            $this->setPaymentTransfer($arr[$keys[22]]);
        }
        if (array_key_exists($keys[23], $arr)) {
            $this->setPaymentCard($arr[$keys[23]]);
        }
        if (array_key_exists($keys[24], $arr)) {
            $this->setPaymentPaypal($arr[$keys[24]]);
        }
        if (array_key_exists($keys[25], $arr)) {
            $this->setPaymentPayplug($arr[$keys[25]]);
        }
        if (array_key_exists($keys[26], $arr)) {
            $this->setPaymentLeft($arr[$keys[26]]);
        }
        if (array_key_exists($keys[27], $arr)) {
            $this->setTitle($arr[$keys[27]]);
        }
        if (array_key_exists($keys[28], $arr)) {
            $this->setFirstname($arr[$keys[28]]);
        }
        if (array_key_exists($keys[29], $arr)) {
            $this->setLastname($arr[$keys[29]]);
        }
        if (array_key_exists($keys[30], $arr)) {
            $this->setAddress1($arr[$keys[30]]);
        }
        if (array_key_exists($keys[31], $arr)) {
            $this->setAddress2($arr[$keys[31]]);
        }
        if (array_key_exists($keys[32], $arr)) {
            $this->setPostalcode($arr[$keys[32]]);
        }
        if (array_key_exists($keys[33], $arr)) {
            $this->setCity($arr[$keys[33]]);
        }
        if (array_key_exists($keys[34], $arr)) {
            $this->setCountryName($arr[$keys[34]]);
        }
        if (array_key_exists($keys[35], $arr)) {
            $this->setEmail($arr[$keys[35]]);
        }
        if (array_key_exists($keys[36], $arr)) {
            $this->setPhone($arr[$keys[36]]);
        }
        if (array_key_exists($keys[37], $arr)) {
            $this->setComment($arr[$keys[37]]);
        }
        if (array_key_exists($keys[38], $arr)) {
            $this->setUtmz($arr[$keys[38]]);
        }
        if (array_key_exists($keys[39], $arr)) {
            $this->setReferer($arr[$keys[39]]);
        }
        if (array_key_exists($keys[40], $arr)) {
            $this->setInsert($arr[$keys[40]]);
        }
        if (array_key_exists($keys[41], $arr)) {
            $this->setPaymentDate($arr[$keys[41]]);
        }
        if (array_key_exists($keys[42], $arr)) {
            $this->setShippingDate($arr[$keys[42]]);
        }
        if (array_key_exists($keys[43], $arr)) {
            $this->setFollowupDate($arr[$keys[43]]);
        }
        if (array_key_exists($keys[44], $arr)) {
            $this->setConfirmationDate($arr[$keys[44]]);
        }
        if (array_key_exists($keys[45], $arr)) {
            $this->setCancelDate($arr[$keys[45]]);
        }
        if (array_key_exists($keys[46], $arr)) {
            $this->setUpdate($arr[$keys[46]]);
        }
        if (array_key_exists($keys[47], $arr)) {
            $this->setCreatedAt($arr[$keys[47]]);
        }
        if (array_key_exists($keys[48], $arr)) {
            $this->setUpdatedAt($arr[$keys[48]]);
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
        $criteria = new Criteria(OrderTableMap::DATABASE_NAME);

        if ($this->isColumnModified(OrderTableMap::COL_ORDER_ID)) {
            $criteria->add(OrderTableMap::COL_ORDER_ID, $this->order_id);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_URL)) {
            $criteria->add(OrderTableMap::COL_ORDER_URL, $this->order_url);
        }
        if ($this->isColumnModified(OrderTableMap::COL_SITE_ID)) {
            $criteria->add(OrderTableMap::COL_SITE_ID, $this->site_id);
        }
        if ($this->isColumnModified(OrderTableMap::COL_AXYS_ACCOUNT_ID)) {
            $criteria->add(OrderTableMap::COL_AXYS_ACCOUNT_ID, $this->axys_account_id);
        }
        if ($this->isColumnModified(OrderTableMap::COL_USER_ID)) {
            $criteria->add(OrderTableMap::COL_USER_ID, $this->user_id);
        }
        if ($this->isColumnModified(OrderTableMap::COL_CUSTOMER_ID)) {
            $criteria->add(OrderTableMap::COL_CUSTOMER_ID, $this->customer_id);
        }
        if ($this->isColumnModified(OrderTableMap::COL_SELLER_ID)) {
            $criteria->add(OrderTableMap::COL_SELLER_ID, $this->seller_id);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_TYPE)) {
            $criteria->add(OrderTableMap::COL_ORDER_TYPE, $this->order_type);
        }
        if ($this->isColumnModified(OrderTableMap::COL_AS_A_GIFT)) {
            $criteria->add(OrderTableMap::COL_AS_A_GIFT, $this->order_as_a_gift);
        }
        if ($this->isColumnModified(OrderTableMap::COL_GIFT_RECIPIENT)) {
            $criteria->add(OrderTableMap::COL_GIFT_RECIPIENT, $this->order_gift_recipient);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_AMOUNT)) {
            $criteria->add(OrderTableMap::COL_ORDER_AMOUNT, $this->order_amount);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_DISCOUNT)) {
            $criteria->add(OrderTableMap::COL_ORDER_DISCOUNT, $this->order_discount);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_AMOUNT_TOBEPAID)) {
            $criteria->add(OrderTableMap::COL_ORDER_AMOUNT_TOBEPAID, $this->order_amount_tobepaid);
        }
        if ($this->isColumnModified(OrderTableMap::COL_SHIPPING_ID)) {
            $criteria->add(OrderTableMap::COL_SHIPPING_ID, $this->shipping_id);
        }
        if ($this->isColumnModified(OrderTableMap::COL_COUNTRY_ID)) {
            $criteria->add(OrderTableMap::COL_COUNTRY_ID, $this->country_id);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_SHIPPING)) {
            $criteria->add(OrderTableMap::COL_ORDER_SHIPPING, $this->order_shipping);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_SHIPPING_MODE)) {
            $criteria->add(OrderTableMap::COL_ORDER_SHIPPING_MODE, $this->order_shipping_mode);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_TRACK_NUMBER)) {
            $criteria->add(OrderTableMap::COL_ORDER_TRACK_NUMBER, $this->order_track_number);
        }
        if ($this->isColumnModified(OrderTableMap::COL_MONDIAL_RELAY_PICKUP_POINT_CODE)) {
            $criteria->add(OrderTableMap::COL_MONDIAL_RELAY_PICKUP_POINT_CODE, $this->mondial_relay_pickup_point_code);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_PAYMENT_MODE)) {
            $criteria->add(OrderTableMap::COL_ORDER_PAYMENT_MODE, $this->order_payment_mode);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_PAYMENT_CASH)) {
            $criteria->add(OrderTableMap::COL_ORDER_PAYMENT_CASH, $this->order_payment_cash);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_PAYMENT_CHEQUE)) {
            $criteria->add(OrderTableMap::COL_ORDER_PAYMENT_CHEQUE, $this->order_payment_cheque);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_PAYMENT_TRANSFER)) {
            $criteria->add(OrderTableMap::COL_ORDER_PAYMENT_TRANSFER, $this->order_payment_transfer);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_PAYMENT_CARD)) {
            $criteria->add(OrderTableMap::COL_ORDER_PAYMENT_CARD, $this->order_payment_card);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_PAYMENT_PAYPAL)) {
            $criteria->add(OrderTableMap::COL_ORDER_PAYMENT_PAYPAL, $this->order_payment_paypal);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_PAYMENT_PAYPLUG)) {
            $criteria->add(OrderTableMap::COL_ORDER_PAYMENT_PAYPLUG, $this->order_payment_payplug);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_PAYMENT_LEFT)) {
            $criteria->add(OrderTableMap::COL_ORDER_PAYMENT_LEFT, $this->order_payment_left);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_TITLE)) {
            $criteria->add(OrderTableMap::COL_ORDER_TITLE, $this->order_title);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_FIRSTNAME)) {
            $criteria->add(OrderTableMap::COL_ORDER_FIRSTNAME, $this->order_firstname);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_LASTNAME)) {
            $criteria->add(OrderTableMap::COL_ORDER_LASTNAME, $this->order_lastname);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_ADDRESS1)) {
            $criteria->add(OrderTableMap::COL_ORDER_ADDRESS1, $this->order_address1);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_ADDRESS2)) {
            $criteria->add(OrderTableMap::COL_ORDER_ADDRESS2, $this->order_address2);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_POSTALCODE)) {
            $criteria->add(OrderTableMap::COL_ORDER_POSTALCODE, $this->order_postalcode);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_CITY)) {
            $criteria->add(OrderTableMap::COL_ORDER_CITY, $this->order_city);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_COUNTRY)) {
            $criteria->add(OrderTableMap::COL_ORDER_COUNTRY, $this->order_country);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_EMAIL)) {
            $criteria->add(OrderTableMap::COL_ORDER_EMAIL, $this->order_email);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_PHONE)) {
            $criteria->add(OrderTableMap::COL_ORDER_PHONE, $this->order_phone);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_COMMENT)) {
            $criteria->add(OrderTableMap::COL_ORDER_COMMENT, $this->order_comment);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_UTMZ)) {
            $criteria->add(OrderTableMap::COL_ORDER_UTMZ, $this->order_utmz);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_REFERER)) {
            $criteria->add(OrderTableMap::COL_ORDER_REFERER, $this->order_referer);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_INSERT)) {
            $criteria->add(OrderTableMap::COL_ORDER_INSERT, $this->order_insert);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_PAYMENT_DATE)) {
            $criteria->add(OrderTableMap::COL_ORDER_PAYMENT_DATE, $this->order_payment_date);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_SHIPPING_DATE)) {
            $criteria->add(OrderTableMap::COL_ORDER_SHIPPING_DATE, $this->order_shipping_date);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_FOLLOWUP_DATE)) {
            $criteria->add(OrderTableMap::COL_ORDER_FOLLOWUP_DATE, $this->order_followup_date);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_CONFIRMATION_DATE)) {
            $criteria->add(OrderTableMap::COL_ORDER_CONFIRMATION_DATE, $this->order_confirmation_date);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_CANCEL_DATE)) {
            $criteria->add(OrderTableMap::COL_ORDER_CANCEL_DATE, $this->order_cancel_date);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_UPDATE)) {
            $criteria->add(OrderTableMap::COL_ORDER_UPDATE, $this->order_update);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_CREATED)) {
            $criteria->add(OrderTableMap::COL_ORDER_CREATED, $this->order_created);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_UPDATED)) {
            $criteria->add(OrderTableMap::COL_ORDER_UPDATED, $this->order_updated);
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
        $criteria = ChildOrderQuery::create();
        $criteria->add(OrderTableMap::COL_ORDER_ID, $this->order_id);

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
     * Generic method to set the primary key (order_id column).
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
     * @param object $copyObj An object of \Model\Order (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setSlug($this->getSlug());
        $copyObj->setSiteId($this->getSiteId());
        $copyObj->setAxysAccountId($this->getAxysAccountId());
        $copyObj->setUserId($this->getUserId());
        $copyObj->setCustomerId($this->getCustomerId());
        $copyObj->setSellerId($this->getSellerId());
        $copyObj->setType($this->getType());
        $copyObj->setAsAGift($this->getAsAGift());
        $copyObj->setGiftRecipient($this->getGiftRecipient());
        $copyObj->setAmount($this->getAmount());
        $copyObj->setDiscount($this->getDiscount());
        $copyObj->setAmountTobepaid($this->getAmountTobepaid());
        $copyObj->setShippingId($this->getShippingId());
        $copyObj->setCountryId($this->getCountryId());
        $copyObj->setShippingCost($this->getShippingCost());
        $copyObj->setShippingMode($this->getShippingMode());
        $copyObj->setTrackNumber($this->getTrackNumber());
        $copyObj->setMondialRelayPickupPointCode($this->getMondialRelayPickupPointCode());
        $copyObj->setPaymentMode($this->getPaymentMode());
        $copyObj->setPaymentCash($this->getPaymentCash());
        $copyObj->setPaymentCheque($this->getPaymentCheque());
        $copyObj->setPaymentTransfer($this->getPaymentTransfer());
        $copyObj->setPaymentCard($this->getPaymentCard());
        $copyObj->setPaymentPaypal($this->getPaymentPaypal());
        $copyObj->setPaymentPayplug($this->getPaymentPayplug());
        $copyObj->setPaymentLeft($this->getPaymentLeft());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setFirstname($this->getFirstname());
        $copyObj->setLastname($this->getLastname());
        $copyObj->setAddress1($this->getAddress1());
        $copyObj->setAddress2($this->getAddress2());
        $copyObj->setPostalcode($this->getPostalcode());
        $copyObj->setCity($this->getCity());
        $copyObj->setCountryName($this->getCountryName());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setPhone($this->getPhone());
        $copyObj->setComment($this->getComment());
        $copyObj->setUtmz($this->getUtmz());
        $copyObj->setReferer($this->getReferer());
        $copyObj->setInsert($this->getInsert());
        $copyObj->setPaymentDate($this->getPaymentDate());
        $copyObj->setShippingDate($this->getShippingDate());
        $copyObj->setFollowupDate($this->getFollowupDate());
        $copyObj->setConfirmationDate($this->getConfirmationDate());
        $copyObj->setCancelDate($this->getCancelDate());
        $copyObj->setUpdate($this->getUpdate());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getPayments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPayment($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getStockItems() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addStockItem($relObj->copy($deepCopy));
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
     * @return \Model\Order Clone of current object.
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
     * Declares an association between this object and a ChildUser object.
     *
     * @param ChildUser|null $v
     * @return $this The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setUser(ChildUser $v = null)
    {
        if ($v === null) {
            $this->setUserId(NULL);
        } else {
            $this->setUserId($v->getId());
        }

        $this->aUser = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildUser object, it will not be re-added.
        if ($v !== null) {
            $v->addOrder($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildUser object
     *
     * @param ConnectionInterface $con Optional Connection object.
     * @return ChildUser|null The associated ChildUser object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getUser(?ConnectionInterface $con = null)
    {
        if ($this->aUser === null && ($this->user_id != 0)) {
            $this->aUser = ChildUserQuery::create()->findPk($this->user_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUser->addOrders($this);
             */
        }

        return $this->aUser;
    }

    /**
     * Declares an association between this object and a ChildShippingOption object.
     *
     * @param ChildShippingOption|null $v
     * @return $this The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setShippingOption(ChildShippingOption $v = null)
    {
        if ($v === null) {
            $this->setShippingId(NULL);
        } else {
            $this->setShippingId($v->getId());
        }

        $this->aShippingOption = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildShippingOption object, it will not be re-added.
        if ($v !== null) {
            $v->addOrder($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildShippingOption object
     *
     * @param ConnectionInterface $con Optional Connection object.
     * @return ChildShippingOption|null The associated ChildShippingOption object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getShippingOption(?ConnectionInterface $con = null)
    {
        if ($this->aShippingOption === null && ($this->shipping_id != 0)) {
            $this->aShippingOption = ChildShippingOptionQuery::create()->findPk($this->shipping_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aShippingOption->addOrders($this);
             */
        }

        return $this->aShippingOption;
    }

    /**
     * Declares an association between this object and a ChildCountry object.
     *
     * @param ChildCountry|null $v
     * @return $this The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setCountry(ChildCountry $v = null)
    {
        if ($v === null) {
            $this->setCountryId(NULL);
        } else {
            $this->setCountryId($v->getId());
        }

        $this->aCountry = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildCountry object, it will not be re-added.
        if ($v !== null) {
            $v->addOrder($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildCountry object
     *
     * @param ConnectionInterface $con Optional Connection object.
     * @return ChildCountry|null The associated ChildCountry object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getCountry(?ConnectionInterface $con = null)
    {
        if ($this->aCountry === null && ($this->country_id != 0)) {
            $this->aCountry = ChildCountryQuery::create()->findPk($this->country_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCountry->addOrders($this);
             */
        }

        return $this->aCountry;
    }

    /**
     * Declares an association between this object and a ChildSite object.
     *
     * @param ChildSite|null $v
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
            $v->addOrder($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildSite object
     *
     * @param ConnectionInterface $con Optional Connection object.
     * @return ChildSite|null The associated ChildSite object.
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
                $this->aSite->addOrders($this);
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
        if ('Payment' === $relationName) {
            $this->initPayments();
            return;
        }
        if ('StockItem' === $relationName) {
            $this->initStockItems();
            return;
        }
    }

    /**
     * Clears out the collPayments collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addPayments()
     */
    public function clearPayments()
    {
        $this->collPayments = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collPayments collection loaded partially.
     *
     * @return void
     */
    public function resetPartialPayments($v = true): void
    {
        $this->collPaymentsPartial = $v;
    }

    /**
     * Initializes the collPayments collection.
     *
     * By default this just sets the collPayments collection to an empty array (like clearcollPayments());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPayments(bool $overrideExisting = true): void
    {
        if (null !== $this->collPayments && !$overrideExisting) {
            return;
        }

        $collectionClassName = PaymentTableMap::getTableMap()->getCollectionClassName();

        $this->collPayments = new $collectionClassName;
        $this->collPayments->setModel('\Model\Payment');
    }

    /**
     * Gets an array of ChildPayment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildOrder is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPayment[] List of ChildPayment objects
     * @phpstan-return ObjectCollection&\Traversable<ChildPayment> List of ChildPayment objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getPayments(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collPaymentsPartial && !$this->isNew();
        if (null === $this->collPayments || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collPayments) {
                    $this->initPayments();
                } else {
                    $collectionClassName = PaymentTableMap::getTableMap()->getCollectionClassName();

                    $collPayments = new $collectionClassName;
                    $collPayments->setModel('\Model\Payment');

                    return $collPayments;
                }
            } else {
                $collPayments = ChildPaymentQuery::create(null, $criteria)
                    ->filterByOrder($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPaymentsPartial && count($collPayments)) {
                        $this->initPayments(false);

                        foreach ($collPayments as $obj) {
                            if (false == $this->collPayments->contains($obj)) {
                                $this->collPayments->append($obj);
                            }
                        }

                        $this->collPaymentsPartial = true;
                    }

                    return $collPayments;
                }

                if ($partial && $this->collPayments) {
                    foreach ($this->collPayments as $obj) {
                        if ($obj->isNew()) {
                            $collPayments[] = $obj;
                        }
                    }
                }

                $this->collPayments = $collPayments;
                $this->collPaymentsPartial = false;
            }
        }

        return $this->collPayments;
    }

    /**
     * Sets a collection of ChildPayment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $payments A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setPayments(Collection $payments, ?ConnectionInterface $con = null)
    {
        /** @var ChildPayment[] $paymentsToDelete */
        $paymentsToDelete = $this->getPayments(new Criteria(), $con)->diff($payments);


        $this->paymentsScheduledForDeletion = $paymentsToDelete;

        foreach ($paymentsToDelete as $paymentRemoved) {
            $paymentRemoved->setOrder(null);
        }

        $this->collPayments = null;
        foreach ($payments as $payment) {
            $this->addPayment($payment);
        }

        $this->collPayments = $payments;
        $this->collPaymentsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Payment objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Payment objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countPayments(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collPaymentsPartial && !$this->isNew();
        if (null === $this->collPayments || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPayments) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPayments());
            }

            $query = ChildPaymentQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByOrder($this)
                ->count($con);
        }

        return count($this->collPayments);
    }

    /**
     * Method called to associate a ChildPayment object to this object
     * through the ChildPayment foreign key attribute.
     *
     * @param ChildPayment $l ChildPayment
     * @return $this The current object (for fluent API support)
     */
    public function addPayment(ChildPayment $l)
    {
        if ($this->collPayments === null) {
            $this->initPayments();
            $this->collPaymentsPartial = true;
        }

        if (!$this->collPayments->contains($l)) {
            $this->doAddPayment($l);

            if ($this->paymentsScheduledForDeletion and $this->paymentsScheduledForDeletion->contains($l)) {
                $this->paymentsScheduledForDeletion->remove($this->paymentsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPayment $payment The ChildPayment object to add.
     */
    protected function doAddPayment(ChildPayment $payment): void
    {
        $this->collPayments[]= $payment;
        $payment->setOrder($this);
    }

    /**
     * @param ChildPayment $payment The ChildPayment object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removePayment(ChildPayment $payment)
    {
        if ($this->getPayments()->contains($payment)) {
            $pos = $this->collPayments->search($payment);
            $this->collPayments->remove($pos);
            if (null === $this->paymentsScheduledForDeletion) {
                $this->paymentsScheduledForDeletion = clone $this->collPayments;
                $this->paymentsScheduledForDeletion->clear();
            }
            $this->paymentsScheduledForDeletion[]= $payment;
            $payment->setOrder(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Order is new, it will return
     * an empty collection; or if this Order has previously
     * been saved, it will retrieve related Payments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Order.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPayment[] List of ChildPayment objects
     * @phpstan-return ObjectCollection&\Traversable<ChildPayment}> List of ChildPayment objects
     */
    public function getPaymentsJoinSite(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPaymentQuery::create(null, $criteria);
        $query->joinWith('Site', $joinBehavior);

        return $this->getPayments($query, $con);
    }

    /**
     * Clears out the collStockItems collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addStockItems()
     */
    public function clearStockItems()
    {
        $this->collStockItems = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collStockItems collection loaded partially.
     *
     * @return void
     */
    public function resetPartialStockItems($v = true): void
    {
        $this->collStockItemsPartial = $v;
    }

    /**
     * Initializes the collStockItems collection.
     *
     * By default this just sets the collStockItems collection to an empty array (like clearcollStockItems());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initStockItems(bool $overrideExisting = true): void
    {
        if (null !== $this->collStockItems && !$overrideExisting) {
            return;
        }

        $collectionClassName = StockTableMap::getTableMap()->getCollectionClassName();

        $this->collStockItems = new $collectionClassName;
        $this->collStockItems->setModel('\Model\Stock');
    }

    /**
     * Gets an array of ChildStock objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildOrder is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildStock[] List of ChildStock objects
     * @phpstan-return ObjectCollection&\Traversable<ChildStock> List of ChildStock objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getStockItems(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collStockItemsPartial && !$this->isNew();
        if (null === $this->collStockItems || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collStockItems) {
                    $this->initStockItems();
                } else {
                    $collectionClassName = StockTableMap::getTableMap()->getCollectionClassName();

                    $collStockItems = new $collectionClassName;
                    $collStockItems->setModel('\Model\Stock');

                    return $collStockItems;
                }
            } else {
                $collStockItems = ChildStockQuery::create(null, $criteria)
                    ->filterByOrder($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collStockItemsPartial && count($collStockItems)) {
                        $this->initStockItems(false);

                        foreach ($collStockItems as $obj) {
                            if (false == $this->collStockItems->contains($obj)) {
                                $this->collStockItems->append($obj);
                            }
                        }

                        $this->collStockItemsPartial = true;
                    }

                    return $collStockItems;
                }

                if ($partial && $this->collStockItems) {
                    foreach ($this->collStockItems as $obj) {
                        if ($obj->isNew()) {
                            $collStockItems[] = $obj;
                        }
                    }
                }

                $this->collStockItems = $collStockItems;
                $this->collStockItemsPartial = false;
            }
        }

        return $this->collStockItems;
    }

    /**
     * Sets a collection of ChildStock objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $stockItems A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setStockItems(Collection $stockItems, ?ConnectionInterface $con = null)
    {
        /** @var ChildStock[] $stockItemsToDelete */
        $stockItemsToDelete = $this->getStockItems(new Criteria(), $con)->diff($stockItems);


        $this->stockItemsScheduledForDeletion = $stockItemsToDelete;

        foreach ($stockItemsToDelete as $stockItemRemoved) {
            $stockItemRemoved->setOrder(null);
        }

        $this->collStockItems = null;
        foreach ($stockItems as $stockItem) {
            $this->addStockItem($stockItem);
        }

        $this->collStockItems = $stockItems;
        $this->collStockItemsPartial = false;

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
    public function countStockItems(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collStockItemsPartial && !$this->isNew();
        if (null === $this->collStockItems || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collStockItems) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getStockItems());
            }

            $query = ChildStockQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByOrder($this)
                ->count($con);
        }

        return count($this->collStockItems);
    }

    /**
     * Method called to associate a ChildStock object to this object
     * through the ChildStock foreign key attribute.
     *
     * @param ChildStock $l ChildStock
     * @return $this The current object (for fluent API support)
     */
    public function addStockItem(ChildStock $l)
    {
        if ($this->collStockItems === null) {
            $this->initStockItems();
            $this->collStockItemsPartial = true;
        }

        if (!$this->collStockItems->contains($l)) {
            $this->doAddStockItem($l);

            if ($this->stockItemsScheduledForDeletion and $this->stockItemsScheduledForDeletion->contains($l)) {
                $this->stockItemsScheduledForDeletion->remove($this->stockItemsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildStock $stockItem The ChildStock object to add.
     */
    protected function doAddStockItem(ChildStock $stockItem): void
    {
        $this->collStockItems[]= $stockItem;
        $stockItem->setOrder($this);
    }

    /**
     * @param ChildStock $stockItem The ChildStock object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeStockItem(ChildStock $stockItem)
    {
        if ($this->getStockItems()->contains($stockItem)) {
            $pos = $this->collStockItems->search($stockItem);
            $this->collStockItems->remove($pos);
            if (null === $this->stockItemsScheduledForDeletion) {
                $this->stockItemsScheduledForDeletion = clone $this->collStockItems;
                $this->stockItemsScheduledForDeletion->clear();
            }
            $this->stockItemsScheduledForDeletion[]= $stockItem;
            $stockItem->setOrder(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Order is new, it will return
     * an empty collection; or if this Order has previously
     * been saved, it will retrieve related StockItems from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Order.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildStock[] List of ChildStock objects
     * @phpstan-return ObjectCollection&\Traversable<ChildStock}> List of ChildStock objects
     */
    public function getStockItemsJoinSite(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildStockQuery::create(null, $criteria);
        $query->joinWith('Site', $joinBehavior);

        return $this->getStockItems($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Order is new, it will return
     * an empty collection; or if this Order has previously
     * been saved, it will retrieve related StockItems from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Order.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildStock[] List of ChildStock objects
     * @phpstan-return ObjectCollection&\Traversable<ChildStock}> List of ChildStock objects
     */
    public function getStockItemsJoinUser(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildStockQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getStockItems($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Order is new, it will return
     * an empty collection; or if this Order has previously
     * been saved, it will retrieve related StockItems from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Order.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildStock[] List of ChildStock objects
     * @phpstan-return ObjectCollection&\Traversable<ChildStock}> List of ChildStock objects
     */
    public function getStockItemsJoinCart(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildStockQuery::create(null, $criteria);
        $query->joinWith('Cart', $joinBehavior);

        return $this->getStockItems($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Order is new, it will return
     * an empty collection; or if this Order has previously
     * been saved, it will retrieve related StockItems from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Order.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildStock[] List of ChildStock objects
     * @phpstan-return ObjectCollection&\Traversable<ChildStock}> List of ChildStock objects
     */
    public function getStockItemsJoinArticle(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildStockQuery::create(null, $criteria);
        $query->joinWith('Article', $joinBehavior);

        return $this->getStockItems($query, $con);
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
        if (null !== $this->aUser) {
            $this->aUser->removeOrder($this);
        }
        if (null !== $this->aShippingOption) {
            $this->aShippingOption->removeOrder($this);
        }
        if (null !== $this->aCountry) {
            $this->aCountry->removeOrder($this);
        }
        if (null !== $this->aSite) {
            $this->aSite->removeOrder($this);
        }
        $this->order_id = null;
        $this->order_url = null;
        $this->site_id = null;
        $this->axys_account_id = null;
        $this->user_id = null;
        $this->customer_id = null;
        $this->seller_id = null;
        $this->order_type = null;
        $this->order_as_a_gift = null;
        $this->order_gift_recipient = null;
        $this->order_amount = null;
        $this->order_discount = null;
        $this->order_amount_tobepaid = null;
        $this->shipping_id = null;
        $this->country_id = null;
        $this->order_shipping = null;
        $this->order_shipping_mode = null;
        $this->order_track_number = null;
        $this->mondial_relay_pickup_point_code = null;
        $this->order_payment_mode = null;
        $this->order_payment_cash = null;
        $this->order_payment_cheque = null;
        $this->order_payment_transfer = null;
        $this->order_payment_card = null;
        $this->order_payment_paypal = null;
        $this->order_payment_payplug = null;
        $this->order_payment_left = null;
        $this->order_title = null;
        $this->order_firstname = null;
        $this->order_lastname = null;
        $this->order_address1 = null;
        $this->order_address2 = null;
        $this->order_postalcode = null;
        $this->order_city = null;
        $this->order_country = null;
        $this->order_email = null;
        $this->order_phone = null;
        $this->order_comment = null;
        $this->order_utmz = null;
        $this->order_referer = null;
        $this->order_insert = null;
        $this->order_payment_date = null;
        $this->order_shipping_date = null;
        $this->order_followup_date = null;
        $this->order_confirmation_date = null;
        $this->order_cancel_date = null;
        $this->order_update = null;
        $this->order_created = null;
        $this->order_updated = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
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
            if ($this->collPayments) {
                foreach ($this->collPayments as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collStockItems) {
                foreach ($this->collStockItems as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collPayments = null;
        $this->collStockItems = null;
        $this->aUser = null;
        $this->aShippingOption = null;
        $this->aCountry = null;
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
        return (string) $this->exportTo(OrderTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return $this The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[OrderTableMap::COL_ORDER_UPDATED] = true;

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
