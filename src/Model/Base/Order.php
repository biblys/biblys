<?php

namespace Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Model\OrderQuery as ChildOrderQuery;
use Model\Map\OrderTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
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
     */
    const TABLE_MAP = '\\Model\\Map\\OrderTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

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
     * The value for the order_as-a-gift field.
     *
     * @var        string|null
     */
    protected $order_as-a-gift;

    /**
     * The value for the order_gift-recipient field.
     *
     * @var        int|null
     */
    protected $order_gift-recipient;

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
     * The value for the order_utm_source field.
     *
     * @var        string|null
     */
    protected $order_utm_source;

    /**
     * The value for the order_utm_campaign field.
     *
     * @var        string|null
     */
    protected $order_utm_campaign;

    /**
     * The value for the order_utm_medium field.
     *
     * @var        string|null
     */
    protected $order_utm_medium;

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
     * The value for the order_deleted field.
     *
     * @var        DateTime|null
     */
    protected $order_deleted;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
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
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Order</code> instance.  If
     * <code>obj</code> is an instance of <code>Order</code>, delegates to
     * <code>equals(Order)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
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
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return void
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
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
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
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
    public function getUrl()
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
     * Get the [order_as-a-gift] column value.
     *
     * @return string|null
     */
    public function getAs-a-gift()
    {
        return $this->order_as-a-gift;
    }

    /**
     * Get the [order_gift-recipient] column value.
     *
     * @return int|null
     */
    public function getGift-recipient()
    {
        return $this->order_gift-recipient;
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
    public function getShipping()
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
    public function getCountry()
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
     * Get the [order_utm_source] column value.
     *
     * @return string|null
     */
    public function getUtmSource()
    {
        return $this->order_utm_source;
    }

    /**
     * Get the [order_utm_campaign] column value.
     *
     * @return string|null
     */
    public function getUtmCampaign()
    {
        return $this->order_utm_campaign;
    }

    /**
     * Get the [order_utm_medium] column value.
     *
     * @return string|null
     */
    public function getUtmMedium()
    {
        return $this->order_utm_medium;
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
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
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
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
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
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
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
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
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
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
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
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
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
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
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
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
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
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
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
     * Get the [optionally formatted] temporal [order_deleted] column value.
     *
     *
     * @param string|null $format The date/time format string (either date()-style or strftime()-style).
     *   If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDeletedAt($format = null)
    {
        if ($format === null) {
            return $this->order_deleted;
        } else {
            return $this->order_deleted instanceof \DateTimeInterface ? $this->order_deleted->format($format) : null;
        }
    }

    /**
     * Set the value of [order_id] column.
     *
     * @param int $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setId()

    /**
     * Set the value of [order_url] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
     */
    public function setUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->order_url !== $v) {
            $this->order_url = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_URL] = true;
        }

        return $this;
    } // setUrl()

    /**
     * Set the value of [site_id] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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

        return $this;
    } // setSiteId()

    /**
     * Set the value of [user_id] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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

        return $this;
    } // setUserId()

    /**
     * Set the value of [customer_id] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setCustomerId()

    /**
     * Set the value of [seller_id] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setSellerId()

    /**
     * Set the value of [order_type] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setType()

    /**
     * Set the value of [order_as-a-gift] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
     */
    public function setAs-a-gift($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->order_as-a-gift !== $v) {
            $this->order_as-a-gift = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_AS-A-GIFT] = true;
        }

        return $this;
    } // setAs-a-gift()

    /**
     * Set the value of [order_gift-recipient] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
     */
    public function setGift-recipient($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->order_gift-recipient !== $v) {
            $this->order_gift-recipient = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_GIFT-RECIPIENT] = true;
        }

        return $this;
    } // setGift-recipient()

    /**
     * Set the value of [order_amount] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setAmount()

    /**
     * Set the value of [order_discount] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setDiscount()

    /**
     * Set the value of [order_amount_tobepaid] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setAmountTobepaid()

    /**
     * Set the value of [shipping_id] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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

        return $this;
    } // setShippingId()

    /**
     * Set the value of [country_id] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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

        return $this;
    } // setCountryId()

    /**
     * Set the value of [order_shipping] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
     */
    public function setShipping($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->order_shipping !== $v) {
            $this->order_shipping = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_SHIPPING] = true;
        }

        return $this;
    } // setShipping()

    /**
     * Set the value of [order_shipping_mode] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setShippingMode()

    /**
     * Set the value of [order_track_number] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setTrackNumber()

    /**
     * Set the value of [order_payment_mode] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setPaymentMode()

    /**
     * Set the value of [order_payment_cash] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setPaymentCash()

    /**
     * Set the value of [order_payment_cheque] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setPaymentCheque()

    /**
     * Set the value of [order_payment_transfer] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setPaymentTransfer()

    /**
     * Set the value of [order_payment_card] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setPaymentCard()

    /**
     * Set the value of [order_payment_paypal] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setPaymentPaypal()

    /**
     * Set the value of [order_payment_payplug] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setPaymentPayplug()

    /**
     * Set the value of [order_payment_left] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setPaymentLeft()

    /**
     * Set the value of [order_title] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setTitle()

    /**
     * Set the value of [order_firstname] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setFirstname()

    /**
     * Set the value of [order_lastname] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setLastname()

    /**
     * Set the value of [order_address1] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setAddress1()

    /**
     * Set the value of [order_address2] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setAddress2()

    /**
     * Set the value of [order_postalcode] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setPostalcode()

    /**
     * Set the value of [order_city] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setCity()

    /**
     * Set the value of [order_country] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
     */
    public function setCountry($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->order_country !== $v) {
            $this->order_country = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_COUNTRY] = true;
        }

        return $this;
    } // setCountry()

    /**
     * Set the value of [order_email] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setEmail()

    /**
     * Set the value of [order_phone] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setPhone()

    /**
     * Set the value of [order_comment] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setComment()

    /**
     * Set the value of [order_utmz] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setUtmz()

    /**
     * Set the value of [order_utm_source] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
     */
    public function setUtmSource($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->order_utm_source !== $v) {
            $this->order_utm_source = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_UTM_SOURCE] = true;
        }

        return $this;
    } // setUtmSource()

    /**
     * Set the value of [order_utm_campaign] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
     */
    public function setUtmCampaign($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->order_utm_campaign !== $v) {
            $this->order_utm_campaign = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_UTM_CAMPAIGN] = true;
        }

        return $this;
    } // setUtmCampaign()

    /**
     * Set the value of [order_utm_medium] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
     */
    public function setUtmMedium($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->order_utm_medium !== $v) {
            $this->order_utm_medium = $v;
            $this->modifiedColumns[OrderTableMap::COL_ORDER_UTM_MEDIUM] = true;
        }

        return $this;
    } // setUtmMedium()

    /**
     * Set the value of [order_referer] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setReferer()

    /**
     * Sets the value of [order_insert] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setInsert()

    /**
     * Sets the value of [order_payment_date] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setPaymentDate()

    /**
     * Sets the value of [order_shipping_date] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setShippingDate()

    /**
     * Sets the value of [order_followup_date] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setFollowupDate()

    /**
     * Sets the value of [order_confirmation_date] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setConfirmationDate()

    /**
     * Sets the value of [order_cancel_date] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setCancelDate()

    /**
     * Sets the value of [order_update] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setUpdate()

    /**
     * Sets the value of [order_created] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setCreatedAt()

    /**
     * Sets the value of [order_updated] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\Order The current object (for fluent API support)
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
    } // setUpdatedAt()

    /**
     * Sets the value of [order_deleted] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\Order The current object (for fluent API support)
     */
    public function setDeletedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->order_deleted !== null || $dt !== null) {
            if ($this->order_deleted === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->order_deleted->format("Y-m-d H:i:s.u")) {
                $this->order_deleted = $dt === null ? null : clone $dt;
                $this->modifiedColumns[OrderTableMap::COL_ORDER_DELETED] = true;
            }
        } // if either are not null

        return $this;
    } // setDeletedAt()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
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
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : OrderTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : OrderTableMap::translateFieldName('Url', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_url = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : OrderTableMap::translateFieldName('SiteId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : OrderTableMap::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : OrderTableMap::translateFieldName('CustomerId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->customer_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : OrderTableMap::translateFieldName('SellerId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->seller_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : OrderTableMap::translateFieldName('Type', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_type = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : OrderTableMap::translateFieldName('As-a-gift', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_as-a-gift = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : OrderTableMap::translateFieldName('Gift-recipient', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_gift-recipient = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : OrderTableMap::translateFieldName('Amount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_amount = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : OrderTableMap::translateFieldName('Discount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_discount = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : OrderTableMap::translateFieldName('AmountTobepaid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_amount_tobepaid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : OrderTableMap::translateFieldName('ShippingId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->shipping_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : OrderTableMap::translateFieldName('CountryId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->country_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : OrderTableMap::translateFieldName('Shipping', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_shipping = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : OrderTableMap::translateFieldName('ShippingMode', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_shipping_mode = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : OrderTableMap::translateFieldName('TrackNumber', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_track_number = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 17 + $startcol : OrderTableMap::translateFieldName('PaymentMode', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_payment_mode = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 18 + $startcol : OrderTableMap::translateFieldName('PaymentCash', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_payment_cash = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 19 + $startcol : OrderTableMap::translateFieldName('PaymentCheque', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_payment_cheque = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 20 + $startcol : OrderTableMap::translateFieldName('PaymentTransfer', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_payment_transfer = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 21 + $startcol : OrderTableMap::translateFieldName('PaymentCard', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_payment_card = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 22 + $startcol : OrderTableMap::translateFieldName('PaymentPaypal', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_payment_paypal = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 23 + $startcol : OrderTableMap::translateFieldName('PaymentPayplug', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_payment_payplug = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 24 + $startcol : OrderTableMap::translateFieldName('PaymentLeft', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_payment_left = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 25 + $startcol : OrderTableMap::translateFieldName('Title', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_title = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 26 + $startcol : OrderTableMap::translateFieldName('Firstname', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_firstname = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 27 + $startcol : OrderTableMap::translateFieldName('Lastname', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_lastname = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 28 + $startcol : OrderTableMap::translateFieldName('Address1', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_address1 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 29 + $startcol : OrderTableMap::translateFieldName('Address2', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_address2 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 30 + $startcol : OrderTableMap::translateFieldName('Postalcode', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_postalcode = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 31 + $startcol : OrderTableMap::translateFieldName('City', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_city = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 32 + $startcol : OrderTableMap::translateFieldName('Country', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_country = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 33 + $startcol : OrderTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_email = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 34 + $startcol : OrderTableMap::translateFieldName('Phone', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_phone = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 35 + $startcol : OrderTableMap::translateFieldName('Comment', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_comment = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 36 + $startcol : OrderTableMap::translateFieldName('Utmz', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_utmz = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 37 + $startcol : OrderTableMap::translateFieldName('UtmSource', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_utm_source = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 38 + $startcol : OrderTableMap::translateFieldName('UtmCampaign', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_utm_campaign = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 39 + $startcol : OrderTableMap::translateFieldName('UtmMedium', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_utm_medium = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 40 + $startcol : OrderTableMap::translateFieldName('Referer', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_referer = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 41 + $startcol : OrderTableMap::translateFieldName('Insert', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->order_insert = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 42 + $startcol : OrderTableMap::translateFieldName('PaymentDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->order_payment_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 43 + $startcol : OrderTableMap::translateFieldName('ShippingDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->order_shipping_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 44 + $startcol : OrderTableMap::translateFieldName('FollowupDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->order_followup_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 45 + $startcol : OrderTableMap::translateFieldName('ConfirmationDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->order_confirmation_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 46 + $startcol : OrderTableMap::translateFieldName('CancelDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->order_cancel_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 47 + $startcol : OrderTableMap::translateFieldName('Update', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->order_update = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 48 + $startcol : OrderTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->order_created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 49 + $startcol : OrderTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->order_updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 50 + $startcol : OrderTableMap::translateFieldName('DeletedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->order_deleted = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 51; // 51 = OrderTableMap::NUM_HYDRATE_COLUMNS.

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
     * @throws PropelException
     */
    public function ensureConsistency()
    {
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
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

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Order::setDeleted()
     * @see Order::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
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
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
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
            } else {
                $ret = $ret && $this->preUpdate($con);
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
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

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

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
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
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_AS-A-GIFT)) {
            $modifiedColumns[':p' . $index++]  = 'order_as-a-gift';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_GIFT-RECIPIENT)) {
            $modifiedColumns[':p' . $index++]  = 'order_gift-recipient';
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
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_UTM_SOURCE)) {
            $modifiedColumns[':p' . $index++]  = 'order_utm_source';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_UTM_CAMPAIGN)) {
            $modifiedColumns[':p' . $index++]  = 'order_utm_campaign';
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_UTM_MEDIUM)) {
            $modifiedColumns[':p' . $index++]  = 'order_utm_medium';
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
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_DELETED)) {
            $modifiedColumns[':p' . $index++]  = 'order_deleted';
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
                    case 'order_as-a-gift':
                        $stmt->bindValue($identifier, $this->order_as-a-gift, PDO::PARAM_STR);
                        break;
                    case 'order_gift-recipient':
                        $stmt->bindValue($identifier, $this->order_gift-recipient, PDO::PARAM_INT);
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
                    case 'order_utm_source':
                        $stmt->bindValue($identifier, $this->order_utm_source, PDO::PARAM_STR);
                        break;
                    case 'order_utm_campaign':
                        $stmt->bindValue($identifier, $this->order_utm_campaign, PDO::PARAM_STR);
                        break;
                    case 'order_utm_medium':
                        $stmt->bindValue($identifier, $this->order_utm_medium, PDO::PARAM_STR);
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
                    case 'order_deleted':
                        $stmt->bindValue($identifier, $this->order_deleted ? $this->order_deleted->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
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
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = OrderTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getUrl();
                break;
            case 2:
                return $this->getSiteId();
                break;
            case 3:
                return $this->getUserId();
                break;
            case 4:
                return $this->getCustomerId();
                break;
            case 5:
                return $this->getSellerId();
                break;
            case 6:
                return $this->getType();
                break;
            case 7:
                return $this->getAs-a-gift();
                break;
            case 8:
                return $this->getGift-recipient();
                break;
            case 9:
                return $this->getAmount();
                break;
            case 10:
                return $this->getDiscount();
                break;
            case 11:
                return $this->getAmountTobepaid();
                break;
            case 12:
                return $this->getShippingId();
                break;
            case 13:
                return $this->getCountryId();
                break;
            case 14:
                return $this->getShipping();
                break;
            case 15:
                return $this->getShippingMode();
                break;
            case 16:
                return $this->getTrackNumber();
                break;
            case 17:
                return $this->getPaymentMode();
                break;
            case 18:
                return $this->getPaymentCash();
                break;
            case 19:
                return $this->getPaymentCheque();
                break;
            case 20:
                return $this->getPaymentTransfer();
                break;
            case 21:
                return $this->getPaymentCard();
                break;
            case 22:
                return $this->getPaymentPaypal();
                break;
            case 23:
                return $this->getPaymentPayplug();
                break;
            case 24:
                return $this->getPaymentLeft();
                break;
            case 25:
                return $this->getTitle();
                break;
            case 26:
                return $this->getFirstname();
                break;
            case 27:
                return $this->getLastname();
                break;
            case 28:
                return $this->getAddress1();
                break;
            case 29:
                return $this->getAddress2();
                break;
            case 30:
                return $this->getPostalcode();
                break;
            case 31:
                return $this->getCity();
                break;
            case 32:
                return $this->getCountry();
                break;
            case 33:
                return $this->getEmail();
                break;
            case 34:
                return $this->getPhone();
                break;
            case 35:
                return $this->getComment();
                break;
            case 36:
                return $this->getUtmz();
                break;
            case 37:
                return $this->getUtmSource();
                break;
            case 38:
                return $this->getUtmCampaign();
                break;
            case 39:
                return $this->getUtmMedium();
                break;
            case 40:
                return $this->getReferer();
                break;
            case 41:
                return $this->getInsert();
                break;
            case 42:
                return $this->getPaymentDate();
                break;
            case 43:
                return $this->getShippingDate();
                break;
            case 44:
                return $this->getFollowupDate();
                break;
            case 45:
                return $this->getConfirmationDate();
                break;
            case 46:
                return $this->getCancelDate();
                break;
            case 47:
                return $this->getUpdate();
                break;
            case 48:
                return $this->getCreatedAt();
                break;
            case 49:
                return $this->getUpdatedAt();
                break;
            case 50:
                return $this->getDeletedAt();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array())
    {

        if (isset($alreadyDumpedObjects['Order'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Order'][$this->hashCode()] = true;
        $keys = OrderTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUrl(),
            $keys[2] => $this->getSiteId(),
            $keys[3] => $this->getUserId(),
            $keys[4] => $this->getCustomerId(),
            $keys[5] => $this->getSellerId(),
            $keys[6] => $this->getType(),
            $keys[7] => $this->getAs-a-gift(),
            $keys[8] => $this->getGift-recipient(),
            $keys[9] => $this->getAmount(),
            $keys[10] => $this->getDiscount(),
            $keys[11] => $this->getAmountTobepaid(),
            $keys[12] => $this->getShippingId(),
            $keys[13] => $this->getCountryId(),
            $keys[14] => $this->getShipping(),
            $keys[15] => $this->getShippingMode(),
            $keys[16] => $this->getTrackNumber(),
            $keys[17] => $this->getPaymentMode(),
            $keys[18] => $this->getPaymentCash(),
            $keys[19] => $this->getPaymentCheque(),
            $keys[20] => $this->getPaymentTransfer(),
            $keys[21] => $this->getPaymentCard(),
            $keys[22] => $this->getPaymentPaypal(),
            $keys[23] => $this->getPaymentPayplug(),
            $keys[24] => $this->getPaymentLeft(),
            $keys[25] => $this->getTitle(),
            $keys[26] => $this->getFirstname(),
            $keys[27] => $this->getLastname(),
            $keys[28] => $this->getAddress1(),
            $keys[29] => $this->getAddress2(),
            $keys[30] => $this->getPostalcode(),
            $keys[31] => $this->getCity(),
            $keys[32] => $this->getCountry(),
            $keys[33] => $this->getEmail(),
            $keys[34] => $this->getPhone(),
            $keys[35] => $this->getComment(),
            $keys[36] => $this->getUtmz(),
            $keys[37] => $this->getUtmSource(),
            $keys[38] => $this->getUtmCampaign(),
            $keys[39] => $this->getUtmMedium(),
            $keys[40] => $this->getReferer(),
            $keys[41] => $this->getInsert(),
            $keys[42] => $this->getPaymentDate(),
            $keys[43] => $this->getShippingDate(),
            $keys[44] => $this->getFollowupDate(),
            $keys[45] => $this->getConfirmationDate(),
            $keys[46] => $this->getCancelDate(),
            $keys[47] => $this->getUpdate(),
            $keys[48] => $this->getCreatedAt(),
            $keys[49] => $this->getUpdatedAt(),
            $keys[50] => $this->getDeletedAt(),
        );
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

        if ($result[$keys[49]] instanceof \DateTimeInterface) {
            $result[$keys[49]] = $result[$keys[49]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[50]] instanceof \DateTimeInterface) {
            $result[$keys[50]] = $result[$keys[50]]->format('Y-m-d H:i:s.u');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }


        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\Model\Order
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = OrderTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Model\Order
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setUrl($value);
                break;
            case 2:
                $this->setSiteId($value);
                break;
            case 3:
                $this->setUserId($value);
                break;
            case 4:
                $this->setCustomerId($value);
                break;
            case 5:
                $this->setSellerId($value);
                break;
            case 6:
                $this->setType($value);
                break;
            case 7:
                $this->setAs-a-gift($value);
                break;
            case 8:
                $this->setGift-recipient($value);
                break;
            case 9:
                $this->setAmount($value);
                break;
            case 10:
                $this->setDiscount($value);
                break;
            case 11:
                $this->setAmountTobepaid($value);
                break;
            case 12:
                $this->setShippingId($value);
                break;
            case 13:
                $this->setCountryId($value);
                break;
            case 14:
                $this->setShipping($value);
                break;
            case 15:
                $this->setShippingMode($value);
                break;
            case 16:
                $this->setTrackNumber($value);
                break;
            case 17:
                $this->setPaymentMode($value);
                break;
            case 18:
                $this->setPaymentCash($value);
                break;
            case 19:
                $this->setPaymentCheque($value);
                break;
            case 20:
                $this->setPaymentTransfer($value);
                break;
            case 21:
                $this->setPaymentCard($value);
                break;
            case 22:
                $this->setPaymentPaypal($value);
                break;
            case 23:
                $this->setPaymentPayplug($value);
                break;
            case 24:
                $this->setPaymentLeft($value);
                break;
            case 25:
                $this->setTitle($value);
                break;
            case 26:
                $this->setFirstname($value);
                break;
            case 27:
                $this->setLastname($value);
                break;
            case 28:
                $this->setAddress1($value);
                break;
            case 29:
                $this->setAddress2($value);
                break;
            case 30:
                $this->setPostalcode($value);
                break;
            case 31:
                $this->setCity($value);
                break;
            case 32:
                $this->setCountry($value);
                break;
            case 33:
                $this->setEmail($value);
                break;
            case 34:
                $this->setPhone($value);
                break;
            case 35:
                $this->setComment($value);
                break;
            case 36:
                $this->setUtmz($value);
                break;
            case 37:
                $this->setUtmSource($value);
                break;
            case 38:
                $this->setUtmCampaign($value);
                break;
            case 39:
                $this->setUtmMedium($value);
                break;
            case 40:
                $this->setReferer($value);
                break;
            case 41:
                $this->setInsert($value);
                break;
            case 42:
                $this->setPaymentDate($value);
                break;
            case 43:
                $this->setShippingDate($value);
                break;
            case 44:
                $this->setFollowupDate($value);
                break;
            case 45:
                $this->setConfirmationDate($value);
                break;
            case 46:
                $this->setCancelDate($value);
                break;
            case 47:
                $this->setUpdate($value);
                break;
            case 48:
                $this->setCreatedAt($value);
                break;
            case 49:
                $this->setUpdatedAt($value);
                break;
            case 50:
                $this->setDeletedAt($value);
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
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = OrderTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setUrl($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setSiteId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setUserId($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setCustomerId($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setSellerId($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setType($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setAs-a-gift($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setGift-recipient($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setAmount($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setDiscount($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setAmountTobepaid($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setShippingId($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setCountryId($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setShipping($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setShippingMode($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setTrackNumber($arr[$keys[16]]);
        }
        if (array_key_exists($keys[17], $arr)) {
            $this->setPaymentMode($arr[$keys[17]]);
        }
        if (array_key_exists($keys[18], $arr)) {
            $this->setPaymentCash($arr[$keys[18]]);
        }
        if (array_key_exists($keys[19], $arr)) {
            $this->setPaymentCheque($arr[$keys[19]]);
        }
        if (array_key_exists($keys[20], $arr)) {
            $this->setPaymentTransfer($arr[$keys[20]]);
        }
        if (array_key_exists($keys[21], $arr)) {
            $this->setPaymentCard($arr[$keys[21]]);
        }
        if (array_key_exists($keys[22], $arr)) {
            $this->setPaymentPaypal($arr[$keys[22]]);
        }
        if (array_key_exists($keys[23], $arr)) {
            $this->setPaymentPayplug($arr[$keys[23]]);
        }
        if (array_key_exists($keys[24], $arr)) {
            $this->setPaymentLeft($arr[$keys[24]]);
        }
        if (array_key_exists($keys[25], $arr)) {
            $this->setTitle($arr[$keys[25]]);
        }
        if (array_key_exists($keys[26], $arr)) {
            $this->setFirstname($arr[$keys[26]]);
        }
        if (array_key_exists($keys[27], $arr)) {
            $this->setLastname($arr[$keys[27]]);
        }
        if (array_key_exists($keys[28], $arr)) {
            $this->setAddress1($arr[$keys[28]]);
        }
        if (array_key_exists($keys[29], $arr)) {
            $this->setAddress2($arr[$keys[29]]);
        }
        if (array_key_exists($keys[30], $arr)) {
            $this->setPostalcode($arr[$keys[30]]);
        }
        if (array_key_exists($keys[31], $arr)) {
            $this->setCity($arr[$keys[31]]);
        }
        if (array_key_exists($keys[32], $arr)) {
            $this->setCountry($arr[$keys[32]]);
        }
        if (array_key_exists($keys[33], $arr)) {
            $this->setEmail($arr[$keys[33]]);
        }
        if (array_key_exists($keys[34], $arr)) {
            $this->setPhone($arr[$keys[34]]);
        }
        if (array_key_exists($keys[35], $arr)) {
            $this->setComment($arr[$keys[35]]);
        }
        if (array_key_exists($keys[36], $arr)) {
            $this->setUtmz($arr[$keys[36]]);
        }
        if (array_key_exists($keys[37], $arr)) {
            $this->setUtmSource($arr[$keys[37]]);
        }
        if (array_key_exists($keys[38], $arr)) {
            $this->setUtmCampaign($arr[$keys[38]]);
        }
        if (array_key_exists($keys[39], $arr)) {
            $this->setUtmMedium($arr[$keys[39]]);
        }
        if (array_key_exists($keys[40], $arr)) {
            $this->setReferer($arr[$keys[40]]);
        }
        if (array_key_exists($keys[41], $arr)) {
            $this->setInsert($arr[$keys[41]]);
        }
        if (array_key_exists($keys[42], $arr)) {
            $this->setPaymentDate($arr[$keys[42]]);
        }
        if (array_key_exists($keys[43], $arr)) {
            $this->setShippingDate($arr[$keys[43]]);
        }
        if (array_key_exists($keys[44], $arr)) {
            $this->setFollowupDate($arr[$keys[44]]);
        }
        if (array_key_exists($keys[45], $arr)) {
            $this->setConfirmationDate($arr[$keys[45]]);
        }
        if (array_key_exists($keys[46], $arr)) {
            $this->setCancelDate($arr[$keys[46]]);
        }
        if (array_key_exists($keys[47], $arr)) {
            $this->setUpdate($arr[$keys[47]]);
        }
        if (array_key_exists($keys[48], $arr)) {
            $this->setCreatedAt($arr[$keys[48]]);
        }
        if (array_key_exists($keys[49], $arr)) {
            $this->setUpdatedAt($arr[$keys[49]]);
        }
        if (array_key_exists($keys[50], $arr)) {
            $this->setDeletedAt($arr[$keys[50]]);
        }
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
     * @return $this|\Model\Order The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
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
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
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
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_AS-A-GIFT)) {
            $criteria->add(OrderTableMap::COL_ORDER_AS-A-GIFT, $this->order_as-a-gift);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_GIFT-RECIPIENT)) {
            $criteria->add(OrderTableMap::COL_ORDER_GIFT-RECIPIENT, $this->order_gift-recipient);
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
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_UTM_SOURCE)) {
            $criteria->add(OrderTableMap::COL_ORDER_UTM_SOURCE, $this->order_utm_source);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_UTM_CAMPAIGN)) {
            $criteria->add(OrderTableMap::COL_ORDER_UTM_CAMPAIGN, $this->order_utm_campaign);
        }
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_UTM_MEDIUM)) {
            $criteria->add(OrderTableMap::COL_ORDER_UTM_MEDIUM, $this->order_utm_medium);
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
        if ($this->isColumnModified(OrderTableMap::COL_ORDER_DELETED)) {
            $criteria->add(OrderTableMap::COL_ORDER_DELETED, $this->order_deleted);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildOrderQuery::create();
        $criteria->add(OrderTableMap::COL_ORDER_ID, $this->order_id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
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
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Model\Order (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUrl($this->getUrl());
        $copyObj->setSiteId($this->getSiteId());
        $copyObj->setUserId($this->getUserId());
        $copyObj->setCustomerId($this->getCustomerId());
        $copyObj->setSellerId($this->getSellerId());
        $copyObj->setType($this->getType());
        $copyObj->setAs-a-gift($this->getAs-a-gift());
        $copyObj->setGift-recipient($this->getGift-recipient());
        $copyObj->setAmount($this->getAmount());
        $copyObj->setDiscount($this->getDiscount());
        $copyObj->setAmountTobepaid($this->getAmountTobepaid());
        $copyObj->setShippingId($this->getShippingId());
        $copyObj->setCountryId($this->getCountryId());
        $copyObj->setShipping($this->getShipping());
        $copyObj->setShippingMode($this->getShippingMode());
        $copyObj->setTrackNumber($this->getTrackNumber());
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
        $copyObj->setCountry($this->getCountry());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setPhone($this->getPhone());
        $copyObj->setComment($this->getComment());
        $copyObj->setUtmz($this->getUtmz());
        $copyObj->setUtmSource($this->getUtmSource());
        $copyObj->setUtmCampaign($this->getUtmCampaign());
        $copyObj->setUtmMedium($this->getUtmMedium());
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
        $copyObj->setDeletedAt($this->getDeletedAt());
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
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Model\Order Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->order_id = null;
        $this->order_url = null;
        $this->site_id = null;
        $this->user_id = null;
        $this->customer_id = null;
        $this->seller_id = null;
        $this->order_type = null;
        $this->order_as-a-gift = null;
        $this->order_gift-recipient = null;
        $this->order_amount = null;
        $this->order_discount = null;
        $this->order_amount_tobepaid = null;
        $this->shipping_id = null;
        $this->country_id = null;
        $this->order_shipping = null;
        $this->order_shipping_mode = null;
        $this->order_track_number = null;
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
        $this->order_utm_source = null;
        $this->order_utm_campaign = null;
        $this->order_utm_medium = null;
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
        $this->order_deleted = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
        } // if ($deep)

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

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
                return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {
            }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
                return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {
            }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
                return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {
            }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
                return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {
            }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
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

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
