<?php

namespace Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Model\Article as ChildArticle;
use Model\ArticleQuery as ChildArticleQuery;
use Model\Image as ChildImage;
use Model\ImageQuery as ChildImageQuery;
use Model\Publisher as ChildPublisher;
use Model\PublisherQuery as ChildPublisherQuery;
use Model\Right as ChildRight;
use Model\RightQuery as ChildRightQuery;
use Model\Map\ArticleTableMap;
use Model\Map\ImageTableMap;
use Model\Map\PublisherTableMap;
use Model\Map\RightTableMap;
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
 * Base class that represents a row from the 'publishers' table.
 *
 *
 *
 * @package    propel.generator.Model.Base
 */
abstract class Publisher implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\Model\\Map\\PublisherTableMap';


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
     * The value for the publisher_id field.
     *
     * @var        int
     */
    protected $publisher_id;

    /**
     * The value for the site_id field.
     *
     * @var        int|null
     */
    protected $site_id;

    /**
     * The value for the publisher_name field.
     *
     * @var        string|null
     */
    protected $publisher_name;

    /**
     * The value for the publisher_name_alphabetic field.
     *
     * @var        string|null
     */
    protected $publisher_name_alphabetic;

    /**
     * The value for the publisher_url field.
     *
     * @var        string|null
     */
    protected $publisher_url;

    /**
     * The value for the publisher_noosfere_id field.
     *
     * @var        int|null
     */
    protected $publisher_noosfere_id;

    /**
     * The value for the publisher_representative field.
     *
     * @var        string|null
     */
    protected $publisher_representative;

    /**
     * The value for the publisher_address field.
     *
     * @var        string|null
     */
    protected $publisher_address;

    /**
     * The value for the publisher_postal_code field.
     *
     * @var        string|null
     */
    protected $publisher_postal_code;

    /**
     * The value for the publisher_city field.
     *
     * @var        string|null
     */
    protected $publisher_city;

    /**
     * The value for the publisher_country field.
     *
     * @var        string|null
     */
    protected $publisher_country;

    /**
     * The value for the publisher_phone field.
     *
     * @var        string|null
     */
    protected $publisher_phone;

    /**
     * The value for the publisher_fax field.
     *
     * @var        string|null
     */
    protected $publisher_fax;

    /**
     * The value for the publisher_website field.
     *
     * @var        string|null
     */
    protected $publisher_website;

    /**
     * The value for the publisher_buy_link field.
     *
     * @var        string|null
     */
    protected $publisher_buy_link;

    /**
     * The value for the publisher_email field.
     *
     * @var        string|null
     */
    protected $publisher_email;

    /**
     * The value for the publisher_facebook field.
     *
     * @var        string|null
     */
    protected $publisher_facebook;

    /**
     * The value for the publisher_twitter field.
     *
     * @var        string|null
     */
    protected $publisher_twitter;

    /**
     * The value for the publisher_legal_form field.
     *
     * @var        string|null
     */
    protected $publisher_legal_form;

    /**
     * The value for the publisher_creation_year field.
     *
     * @var        string|null
     */
    protected $publisher_creation_year;

    /**
     * The value for the publisher_isbn field.
     *
     * @var        string|null
     */
    protected $publisher_isbn;

    /**
     * The value for the publisher_volumes field.
     *
     * @var        int|null
     */
    protected $publisher_volumes;

    /**
     * The value for the publisher_average_run field.
     *
     * @var        int|null
     */
    protected $publisher_average_run;

    /**
     * The value for the publisher_specialities field.
     *
     * @var        string|null
     */
    protected $publisher_specialities;

    /**
     * The value for the publisher_diffuseur field.
     *
     * @var        string|null
     */
    protected $publisher_diffuseur;

    /**
     * The value for the publisher_distributeur field.
     *
     * @var        string|null
     */
    protected $publisher_distributeur;

    /**
     * The value for the publisher_vpc field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean|null
     */
    protected $publisher_vpc;

    /**
     * The value for the publisher_paypal field.
     *
     * @var        string|null
     */
    protected $publisher_paypal;

    /**
     * The value for the publisher_shipping_mode field.
     *
     * Note: this column has a database default value of: 'offerts'
     * @var        string|null
     */
    protected $publisher_shipping_mode;

    /**
     * The value for the publisher_shipping_fee field.
     *
     * @var        int|null
     */
    protected $publisher_shipping_fee;

    /**
     * The value for the publisher_gln field.
     *
     * @var        string|null
     */
    protected $publisher_gln;

    /**
     * The value for the publisher_desc field.
     *
     * @var        string|null
     */
    protected $publisher_desc;

    /**
     * The value for the publisher_desc_short field.
     *
     * @var        string|null
     */
    protected $publisher_desc_short;

    /**
     * The value for the publisher_order_by field.
     *
     * Note: this column has a database default value of: 'article_pubdate'
     * @var        string|null
     */
    protected $publisher_order_by;

    /**
     * The value for the publisher_insert field.
     *
     * Note: this column has a database default value of: (expression) CURRENT_TIMESTAMP
     * @var        DateTime|null
     */
    protected $publisher_insert;

    /**
     * The value for the publisher_update field.
     *
     * @var        DateTime|null
     */
    protected $publisher_update;

    /**
     * The value for the publisher_created field.
     *
     * @var        DateTime|null
     */
    protected $publisher_created;

    /**
     * The value for the publisher_updated field.
     *
     * @var        DateTime|null
     */
    protected $publisher_updated;

    /**
     * @var        ObjectCollection|ChildArticle[] Collection to store aggregation of ChildArticle objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildArticle> Collection to store aggregation of ChildArticle objects.
     */
    protected $collArticles;
    protected $collArticlesPartial;

    /**
     * @var        ObjectCollection|ChildImage[] Collection to store aggregation of ChildImage objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildImage> Collection to store aggregation of ChildImage objects.
     */
    protected $collImages;
    protected $collImagesPartial;

    /**
     * @var        ObjectCollection|ChildRight[] Collection to store aggregation of ChildRight objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildRight> Collection to store aggregation of ChildRight objects.
     */
    protected $collRights;
    protected $collRightsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var bool
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildArticle[]
     * @phpstan-var ObjectCollection&\Traversable<ChildArticle>
     */
    protected $articlesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildImage[]
     * @phpstan-var ObjectCollection&\Traversable<ChildImage>
     */
    protected $imagesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildRight[]
     * @phpstan-var ObjectCollection&\Traversable<ChildRight>
     */
    protected $rightsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues(): void
    {
        $this->publisher_vpc = false;
        $this->publisher_shipping_mode = 'offerts';
        $this->publisher_order_by = 'article_pubdate';
    }

    /**
     * Initializes internal state of Model\Base\Publisher object.
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
     * Compares this with another <code>Publisher</code> instance.  If
     * <code>obj</code> is an instance of <code>Publisher</code>, delegates to
     * <code>equals(Publisher)</code>.  Otherwise, returns <code>false</code>.
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
     * Get the [publisher_id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->publisher_id;
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
     * Get the [publisher_name] column value.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->publisher_name;
    }

    /**
     * Get the [publisher_name_alphabetic] column value.
     *
     * @return string|null
     */
    public function getNameAlphabetic()
    {
        return $this->publisher_name_alphabetic;
    }

    /**
     * Get the [publisher_url] column value.
     *
     * @return string|null
     */
    public function getUrl()
    {
        return $this->publisher_url;
    }

    /**
     * Get the [publisher_noosfere_id] column value.
     *
     * @return int|null
     */
    public function getNoosfereId()
    {
        return $this->publisher_noosfere_id;
    }

    /**
     * Get the [publisher_representative] column value.
     *
     * @return string|null
     */
    public function getRepresentative()
    {
        return $this->publisher_representative;
    }

    /**
     * Get the [publisher_address] column value.
     *
     * @return string|null
     */
    public function getAddress()
    {
        return $this->publisher_address;
    }

    /**
     * Get the [publisher_postal_code] column value.
     *
     * @return string|null
     */
    public function getPostalCode()
    {
        return $this->publisher_postal_code;
    }

    /**
     * Get the [publisher_city] column value.
     *
     * @return string|null
     */
    public function getCity()
    {
        return $this->publisher_city;
    }

    /**
     * Get the [publisher_country] column value.
     *
     * @return string|null
     */
    public function getCountry()
    {
        return $this->publisher_country;
    }

    /**
     * Get the [publisher_phone] column value.
     *
     * @return string|null
     */
    public function getPhone()
    {
        return $this->publisher_phone;
    }

    /**
     * Get the [publisher_fax] column value.
     *
     * @return string|null
     */
    public function getFax()
    {
        return $this->publisher_fax;
    }

    /**
     * Get the [publisher_website] column value.
     *
     * @return string|null
     */
    public function getWebsite()
    {
        return $this->publisher_website;
    }

    /**
     * Get the [publisher_buy_link] column value.
     *
     * @return string|null
     */
    public function getBuyLink()
    {
        return $this->publisher_buy_link;
    }

    /**
     * Get the [publisher_email] column value.
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->publisher_email;
    }

    /**
     * Get the [publisher_facebook] column value.
     *
     * @return string|null
     */
    public function getFacebook()
    {
        return $this->publisher_facebook;
    }

    /**
     * Get the [publisher_twitter] column value.
     *
     * @return string|null
     */
    public function getTwitter()
    {
        return $this->publisher_twitter;
    }

    /**
     * Get the [publisher_legal_form] column value.
     *
     * @return string|null
     */
    public function getLegalForm()
    {
        return $this->publisher_legal_form;
    }

    /**
     * Get the [publisher_creation_year] column value.
     *
     * @return string|null
     */
    public function getCreationYear()
    {
        return $this->publisher_creation_year;
    }

    /**
     * Get the [publisher_isbn] column value.
     *
     * @return string|null
     */
    public function getIsbn()
    {
        return $this->publisher_isbn;
    }

    /**
     * Get the [publisher_volumes] column value.
     *
     * @return int|null
     */
    public function getVolumes()
    {
        return $this->publisher_volumes;
    }

    /**
     * Get the [publisher_average_run] column value.
     *
     * @return int|null
     */
    public function getAverageRun()
    {
        return $this->publisher_average_run;
    }

    /**
     * Get the [publisher_specialities] column value.
     *
     * @return string|null
     */
    public function getSpecialities()
    {
        return $this->publisher_specialities;
    }

    /**
     * Get the [publisher_diffuseur] column value.
     *
     * @return string|null
     */
    public function getDiffuseur()
    {
        return $this->publisher_diffuseur;
    }

    /**
     * Get the [publisher_distributeur] column value.
     *
     * @return string|null
     */
    public function getDistributeur()
    {
        return $this->publisher_distributeur;
    }

    /**
     * Get the [publisher_vpc] column value.
     *
     * @return boolean|null
     */
    public function getVpc()
    {
        return $this->publisher_vpc;
    }

    /**
     * Get the [publisher_vpc] column value.
     *
     * @return boolean|null
     */
    public function isVpc()
    {
        return $this->getVpc();
    }

    /**
     * Get the [publisher_paypal] column value.
     *
     * @return string|null
     */
    public function getPaypal()
    {
        return $this->publisher_paypal;
    }

    /**
     * Get the [publisher_shipping_mode] column value.
     *
     * @return string|null
     */
    public function getShippingMode()
    {
        return $this->publisher_shipping_mode;
    }

    /**
     * Get the [publisher_shipping_fee] column value.
     *
     * @return int|null
     */
    public function getShippingFee()
    {
        return $this->publisher_shipping_fee;
    }

    /**
     * Get the [publisher_gln] column value.
     *
     * @return string|null
     */
    public function getGln()
    {
        return $this->publisher_gln;
    }

    /**
     * Get the [publisher_desc] column value.
     *
     * @return string|null
     */
    public function getDesc()
    {
        return $this->publisher_desc;
    }

    /**
     * Get the [publisher_desc_short] column value.
     *
     * @return string|null
     */
    public function getDescShort()
    {
        return $this->publisher_desc_short;
    }

    /**
     * Get the [publisher_order_by] column value.
     *
     * @return string|null
     */
    public function getOrderBy()
    {
        return $this->publisher_order_by;
    }

    /**
     * Get the [optionally formatted] temporal [publisher_insert] column value.
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
            return $this->publisher_insert;
        } else {
            return $this->publisher_insert instanceof \DateTimeInterface ? $this->publisher_insert->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [publisher_update] column value.
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
            return $this->publisher_update;
        } else {
            return $this->publisher_update instanceof \DateTimeInterface ? $this->publisher_update->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [publisher_created] column value.
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
            return $this->publisher_created;
        } else {
            return $this->publisher_created instanceof \DateTimeInterface ? $this->publisher_created->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [publisher_updated] column value.
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
            return $this->publisher_updated;
        } else {
            return $this->publisher_updated instanceof \DateTimeInterface ? $this->publisher_updated->format($format) : null;
        }
    }

    /**
     * Set the value of [publisher_id] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->publisher_id !== $v) {
            $this->publisher_id = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_ID] = true;
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
            $this->modifiedColumns[PublisherTableMap::COL_SITE_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_name] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->publisher_name !== $v) {
            $this->publisher_name = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_NAME] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_name_alphabetic] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setNameAlphabetic($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->publisher_name_alphabetic !== $v) {
            $this->publisher_name_alphabetic = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_NAME_ALPHABETIC] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_url] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->publisher_url !== $v) {
            $this->publisher_url = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_URL] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_noosfere_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setNoosfereId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->publisher_noosfere_id !== $v) {
            $this->publisher_noosfere_id = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_NOOSFERE_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_representative] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setRepresentative($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->publisher_representative !== $v) {
            $this->publisher_representative = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_REPRESENTATIVE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_address] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setAddress($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->publisher_address !== $v) {
            $this->publisher_address = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_ADDRESS] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_postal_code] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPostalCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->publisher_postal_code !== $v) {
            $this->publisher_postal_code = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_POSTAL_CODE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_city] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setCity($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->publisher_city !== $v) {
            $this->publisher_city = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_CITY] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_country] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setCountry($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->publisher_country !== $v) {
            $this->publisher_country = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_COUNTRY] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_phone] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPhone($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->publisher_phone !== $v) {
            $this->publisher_phone = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_PHONE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_fax] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setFax($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->publisher_fax !== $v) {
            $this->publisher_fax = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_FAX] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_website] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setWebsite($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->publisher_website !== $v) {
            $this->publisher_website = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_WEBSITE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_buy_link] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setBuyLink($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->publisher_buy_link !== $v) {
            $this->publisher_buy_link = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_BUY_LINK] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_email] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->publisher_email !== $v) {
            $this->publisher_email = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_EMAIL] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_facebook] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setFacebook($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->publisher_facebook !== $v) {
            $this->publisher_facebook = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_FACEBOOK] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_twitter] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setTwitter($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->publisher_twitter !== $v) {
            $this->publisher_twitter = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_TWITTER] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_legal_form] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setLegalForm($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->publisher_legal_form !== $v) {
            $this->publisher_legal_form = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_LEGAL_FORM] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_creation_year] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setCreationYear($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->publisher_creation_year !== $v) {
            $this->publisher_creation_year = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_CREATION_YEAR] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_isbn] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setIsbn($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->publisher_isbn !== $v) {
            $this->publisher_isbn = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_ISBN] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_volumes] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setVolumes($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->publisher_volumes !== $v) {
            $this->publisher_volumes = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_VOLUMES] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_average_run] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setAverageRun($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->publisher_average_run !== $v) {
            $this->publisher_average_run = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_AVERAGE_RUN] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_specialities] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setSpecialities($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->publisher_specialities !== $v) {
            $this->publisher_specialities = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_SPECIALITIES] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_diffuseur] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setDiffuseur($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->publisher_diffuseur !== $v) {
            $this->publisher_diffuseur = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_DIFFUSEUR] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_distributeur] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setDistributeur($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->publisher_distributeur !== $v) {
            $this->publisher_distributeur = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_DISTRIBUTEUR] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [publisher_vpc] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setVpc($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->publisher_vpc !== $v) {
            $this->publisher_vpc = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_VPC] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_paypal] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPaypal($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->publisher_paypal !== $v) {
            $this->publisher_paypal = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_PAYPAL] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_shipping_mode] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setShippingMode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->publisher_shipping_mode !== $v) {
            $this->publisher_shipping_mode = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_SHIPPING_MODE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_shipping_fee] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setShippingFee($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->publisher_shipping_fee !== $v) {
            $this->publisher_shipping_fee = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_SHIPPING_FEE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_gln] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setGln($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->publisher_gln !== $v) {
            $this->publisher_gln = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_GLN] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_desc] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setDesc($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->publisher_desc !== $v) {
            $this->publisher_desc = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_DESC] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_desc_short] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setDescShort($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->publisher_desc_short !== $v) {
            $this->publisher_desc_short = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_DESC_SHORT] = true;
        }

        return $this;
    }

    /**
     * Set the value of [publisher_order_by] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setOrderBy($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->publisher_order_by !== $v) {
            $this->publisher_order_by = $v;
            $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_ORDER_BY] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [publisher_insert] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setInsert($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->publisher_insert !== null || $dt !== null) {
            if ($this->publisher_insert === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->publisher_insert->format("Y-m-d H:i:s.u")) {
                $this->publisher_insert = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_INSERT] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [publisher_update] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->publisher_update !== null || $dt !== null) {
            if ($this->publisher_update === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->publisher_update->format("Y-m-d H:i:s.u")) {
                $this->publisher_update = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_UPDATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [publisher_created] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->publisher_created !== null || $dt !== null) {
            if ($this->publisher_created === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->publisher_created->format("Y-m-d H:i:s.u")) {
                $this->publisher_created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_CREATED] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [publisher_updated] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->publisher_updated !== null || $dt !== null) {
            if ($this->publisher_updated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->publisher_updated->format("Y-m-d H:i:s.u")) {
                $this->publisher_updated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_UPDATED] = true;
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
            if ($this->publisher_vpc !== false) {
                return false;
            }

            if ($this->publisher_shipping_mode !== 'offerts') {
                return false;
            }

            if ($this->publisher_order_by !== 'article_pubdate') {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : PublisherTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : PublisherTableMap::translateFieldName('SiteId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : PublisherTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : PublisherTableMap::translateFieldName('NameAlphabetic', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_name_alphabetic = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : PublisherTableMap::translateFieldName('Url', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_url = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : PublisherTableMap::translateFieldName('NoosfereId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_noosfere_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : PublisherTableMap::translateFieldName('Representative', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_representative = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : PublisherTableMap::translateFieldName('Address', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_address = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : PublisherTableMap::translateFieldName('PostalCode', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_postal_code = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : PublisherTableMap::translateFieldName('City', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_city = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : PublisherTableMap::translateFieldName('Country', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_country = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : PublisherTableMap::translateFieldName('Phone', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_phone = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : PublisherTableMap::translateFieldName('Fax', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_fax = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : PublisherTableMap::translateFieldName('Website', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_website = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : PublisherTableMap::translateFieldName('BuyLink', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_buy_link = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : PublisherTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_email = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : PublisherTableMap::translateFieldName('Facebook', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_facebook = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 17 + $startcol : PublisherTableMap::translateFieldName('Twitter', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_twitter = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 18 + $startcol : PublisherTableMap::translateFieldName('LegalForm', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_legal_form = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 19 + $startcol : PublisherTableMap::translateFieldName('CreationYear', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_creation_year = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 20 + $startcol : PublisherTableMap::translateFieldName('Isbn', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_isbn = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 21 + $startcol : PublisherTableMap::translateFieldName('Volumes', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_volumes = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 22 + $startcol : PublisherTableMap::translateFieldName('AverageRun', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_average_run = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 23 + $startcol : PublisherTableMap::translateFieldName('Specialities', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_specialities = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 24 + $startcol : PublisherTableMap::translateFieldName('Diffuseur', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_diffuseur = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 25 + $startcol : PublisherTableMap::translateFieldName('Distributeur', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_distributeur = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 26 + $startcol : PublisherTableMap::translateFieldName('Vpc', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_vpc = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 27 + $startcol : PublisherTableMap::translateFieldName('Paypal', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_paypal = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 28 + $startcol : PublisherTableMap::translateFieldName('ShippingMode', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_shipping_mode = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 29 + $startcol : PublisherTableMap::translateFieldName('ShippingFee', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_shipping_fee = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 30 + $startcol : PublisherTableMap::translateFieldName('Gln', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_gln = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 31 + $startcol : PublisherTableMap::translateFieldName('Desc', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_desc = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 32 + $startcol : PublisherTableMap::translateFieldName('DescShort', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_desc_short = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 33 + $startcol : PublisherTableMap::translateFieldName('OrderBy', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_order_by = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 34 + $startcol : PublisherTableMap::translateFieldName('Insert', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->publisher_insert = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 35 + $startcol : PublisherTableMap::translateFieldName('Update', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->publisher_update = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 36 + $startcol : PublisherTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->publisher_created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 37 + $startcol : PublisherTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->publisher_updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 38; // 38 = PublisherTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\Publisher'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(PublisherTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildPublisherQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collArticles = null;

            $this->collImages = null;

            $this->collRights = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param ConnectionInterface $con
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     * @see Publisher::setDeleted()
     * @see Publisher::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(PublisherTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildPublisherQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(PublisherTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(PublisherTableMap::COL_PUBLISHER_CREATED)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(PublisherTableMap::COL_PUBLISHER_UPDATED)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PublisherTableMap::COL_PUBLISHER_UPDATED)) {
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
                PublisherTableMap::addInstanceToPool($this);
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

            if ($this->articlesScheduledForDeletion !== null) {
                if (!$this->articlesScheduledForDeletion->isEmpty()) {
                    foreach ($this->articlesScheduledForDeletion as $article) {
                        // need to save related object because we set the relation to null
                        $article->save($con);
                    }
                    $this->articlesScheduledForDeletion = null;
                }
            }

            if ($this->collArticles !== null) {
                foreach ($this->collArticles as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->imagesScheduledForDeletion !== null) {
                if (!$this->imagesScheduledForDeletion->isEmpty()) {
                    foreach ($this->imagesScheduledForDeletion as $image) {
                        // need to save related object because we set the relation to null
                        $image->save($con);
                    }
                    $this->imagesScheduledForDeletion = null;
                }
            }

            if ($this->collImages !== null) {
                foreach ($this->collImages as $referrerFK) {
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

        $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_ID] = true;
        if (null !== $this->publisher_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PublisherTableMap::COL_PUBLISHER_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_id';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_SITE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'site_id';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_name';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_NAME_ALPHABETIC)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_name_alphabetic';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_URL)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_url';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_NOOSFERE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_noosfere_id';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_REPRESENTATIVE)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_representative';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_ADDRESS)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_address';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_POSTAL_CODE)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_postal_code';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_CITY)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_city';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_COUNTRY)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_country';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_PHONE)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_phone';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_FAX)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_fax';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_WEBSITE)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_website';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_BUY_LINK)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_buy_link';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_email';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_FACEBOOK)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_facebook';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_TWITTER)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_twitter';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_LEGAL_FORM)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_legal_form';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_CREATION_YEAR)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_creation_year';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_ISBN)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_isbn';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_VOLUMES)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_volumes';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_AVERAGE_RUN)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_average_run';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_SPECIALITIES)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_specialities';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_DIFFUSEUR)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_diffuseur';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_DISTRIBUTEUR)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_distributeur';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_VPC)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_vpc';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_PAYPAL)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_paypal';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_SHIPPING_MODE)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_shipping_mode';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_SHIPPING_FEE)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_shipping_fee';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_GLN)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_gln';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_DESC)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_desc';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_DESC_SHORT)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_desc_short';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_ORDER_BY)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_order_by';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_INSERT)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_insert';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_UPDATE)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_update';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_created';
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_updated';
        }

        $sql = sprintf(
            'INSERT INTO publishers (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'publisher_id':
                        $stmt->bindValue($identifier, $this->publisher_id, PDO::PARAM_INT);

                        break;
                    case 'site_id':
                        $stmt->bindValue($identifier, $this->site_id, PDO::PARAM_INT);

                        break;
                    case 'publisher_name':
                        $stmt->bindValue($identifier, $this->publisher_name, PDO::PARAM_STR);

                        break;
                    case 'publisher_name_alphabetic':
                        $stmt->bindValue($identifier, $this->publisher_name_alphabetic, PDO::PARAM_STR);

                        break;
                    case 'publisher_url':
                        $stmt->bindValue($identifier, $this->publisher_url, PDO::PARAM_STR);

                        break;
                    case 'publisher_noosfere_id':
                        $stmt->bindValue($identifier, $this->publisher_noosfere_id, PDO::PARAM_INT);

                        break;
                    case 'publisher_representative':
                        $stmt->bindValue($identifier, $this->publisher_representative, PDO::PARAM_STR);

                        break;
                    case 'publisher_address':
                        $stmt->bindValue($identifier, $this->publisher_address, PDO::PARAM_STR);

                        break;
                    case 'publisher_postal_code':
                        $stmt->bindValue($identifier, $this->publisher_postal_code, PDO::PARAM_STR);

                        break;
                    case 'publisher_city':
                        $stmt->bindValue($identifier, $this->publisher_city, PDO::PARAM_STR);

                        break;
                    case 'publisher_country':
                        $stmt->bindValue($identifier, $this->publisher_country, PDO::PARAM_STR);

                        break;
                    case 'publisher_phone':
                        $stmt->bindValue($identifier, $this->publisher_phone, PDO::PARAM_STR);

                        break;
                    case 'publisher_fax':
                        $stmt->bindValue($identifier, $this->publisher_fax, PDO::PARAM_STR);

                        break;
                    case 'publisher_website':
                        $stmt->bindValue($identifier, $this->publisher_website, PDO::PARAM_STR);

                        break;
                    case 'publisher_buy_link':
                        $stmt->bindValue($identifier, $this->publisher_buy_link, PDO::PARAM_STR);

                        break;
                    case 'publisher_email':
                        $stmt->bindValue($identifier, $this->publisher_email, PDO::PARAM_STR);

                        break;
                    case 'publisher_facebook':
                        $stmt->bindValue($identifier, $this->publisher_facebook, PDO::PARAM_STR);

                        break;
                    case 'publisher_twitter':
                        $stmt->bindValue($identifier, $this->publisher_twitter, PDO::PARAM_STR);

                        break;
                    case 'publisher_legal_form':
                        $stmt->bindValue($identifier, $this->publisher_legal_form, PDO::PARAM_STR);

                        break;
                    case 'publisher_creation_year':
                        $stmt->bindValue($identifier, $this->publisher_creation_year, PDO::PARAM_STR);

                        break;
                    case 'publisher_isbn':
                        $stmt->bindValue($identifier, $this->publisher_isbn, PDO::PARAM_STR);

                        break;
                    case 'publisher_volumes':
                        $stmt->bindValue($identifier, $this->publisher_volumes, PDO::PARAM_INT);

                        break;
                    case 'publisher_average_run':
                        $stmt->bindValue($identifier, $this->publisher_average_run, PDO::PARAM_INT);

                        break;
                    case 'publisher_specialities':
                        $stmt->bindValue($identifier, $this->publisher_specialities, PDO::PARAM_STR);

                        break;
                    case 'publisher_diffuseur':
                        $stmt->bindValue($identifier, $this->publisher_diffuseur, PDO::PARAM_STR);

                        break;
                    case 'publisher_distributeur':
                        $stmt->bindValue($identifier, $this->publisher_distributeur, PDO::PARAM_STR);

                        break;
                    case 'publisher_vpc':
                        $stmt->bindValue($identifier, (int) $this->publisher_vpc, PDO::PARAM_INT);

                        break;
                    case 'publisher_paypal':
                        $stmt->bindValue($identifier, $this->publisher_paypal, PDO::PARAM_STR);

                        break;
                    case 'publisher_shipping_mode':
                        $stmt->bindValue($identifier, $this->publisher_shipping_mode, PDO::PARAM_STR);

                        break;
                    case 'publisher_shipping_fee':
                        $stmt->bindValue($identifier, $this->publisher_shipping_fee, PDO::PARAM_INT);

                        break;
                    case 'publisher_gln':
                        $stmt->bindValue($identifier, $this->publisher_gln, PDO::PARAM_INT);

                        break;
                    case 'publisher_desc':
                        $stmt->bindValue($identifier, $this->publisher_desc, PDO::PARAM_STR);

                        break;
                    case 'publisher_desc_short':
                        $stmt->bindValue($identifier, $this->publisher_desc_short, PDO::PARAM_STR);

                        break;
                    case 'publisher_order_by':
                        $stmt->bindValue($identifier, $this->publisher_order_by, PDO::PARAM_STR);

                        break;
                    case 'publisher_insert':
                        $stmt->bindValue($identifier, $this->publisher_insert ? $this->publisher_insert->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'publisher_update':
                        $stmt->bindValue($identifier, $this->publisher_update ? $this->publisher_update->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'publisher_created':
                        $stmt->bindValue($identifier, $this->publisher_created ? $this->publisher_created->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'publisher_updated':
                        $stmt->bindValue($identifier, $this->publisher_updated ? $this->publisher_updated->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

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
        $pos = PublisherTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getName();

            case 3:
                return $this->getNameAlphabetic();

            case 4:
                return $this->getUrl();

            case 5:
                return $this->getNoosfereId();

            case 6:
                return $this->getRepresentative();

            case 7:
                return $this->getAddress();

            case 8:
                return $this->getPostalCode();

            case 9:
                return $this->getCity();

            case 10:
                return $this->getCountry();

            case 11:
                return $this->getPhone();

            case 12:
                return $this->getFax();

            case 13:
                return $this->getWebsite();

            case 14:
                return $this->getBuyLink();

            case 15:
                return $this->getEmail();

            case 16:
                return $this->getFacebook();

            case 17:
                return $this->getTwitter();

            case 18:
                return $this->getLegalForm();

            case 19:
                return $this->getCreationYear();

            case 20:
                return $this->getIsbn();

            case 21:
                return $this->getVolumes();

            case 22:
                return $this->getAverageRun();

            case 23:
                return $this->getSpecialities();

            case 24:
                return $this->getDiffuseur();

            case 25:
                return $this->getDistributeur();

            case 26:
                return $this->getVpc();

            case 27:
                return $this->getPaypal();

            case 28:
                return $this->getShippingMode();

            case 29:
                return $this->getShippingFee();

            case 30:
                return $this->getGln();

            case 31:
                return $this->getDesc();

            case 32:
                return $this->getDescShort();

            case 33:
                return $this->getOrderBy();

            case 34:
                return $this->getInsert();

            case 35:
                return $this->getUpdate();

            case 36:
                return $this->getCreatedAt();

            case 37:
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
        if (isset($alreadyDumpedObjects['Publisher'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['Publisher'][$this->hashCode()] = true;
        $keys = PublisherTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getSiteId(),
            $keys[2] => $this->getName(),
            $keys[3] => $this->getNameAlphabetic(),
            $keys[4] => $this->getUrl(),
            $keys[5] => $this->getNoosfereId(),
            $keys[6] => $this->getRepresentative(),
            $keys[7] => $this->getAddress(),
            $keys[8] => $this->getPostalCode(),
            $keys[9] => $this->getCity(),
            $keys[10] => $this->getCountry(),
            $keys[11] => $this->getPhone(),
            $keys[12] => $this->getFax(),
            $keys[13] => $this->getWebsite(),
            $keys[14] => $this->getBuyLink(),
            $keys[15] => $this->getEmail(),
            $keys[16] => $this->getFacebook(),
            $keys[17] => $this->getTwitter(),
            $keys[18] => $this->getLegalForm(),
            $keys[19] => $this->getCreationYear(),
            $keys[20] => $this->getIsbn(),
            $keys[21] => $this->getVolumes(),
            $keys[22] => $this->getAverageRun(),
            $keys[23] => $this->getSpecialities(),
            $keys[24] => $this->getDiffuseur(),
            $keys[25] => $this->getDistributeur(),
            $keys[26] => $this->getVpc(),
            $keys[27] => $this->getPaypal(),
            $keys[28] => $this->getShippingMode(),
            $keys[29] => $this->getShippingFee(),
            $keys[30] => $this->getGln(),
            $keys[31] => $this->getDesc(),
            $keys[32] => $this->getDescShort(),
            $keys[33] => $this->getOrderBy(),
            $keys[34] => $this->getInsert(),
            $keys[35] => $this->getUpdate(),
            $keys[36] => $this->getCreatedAt(),
            $keys[37] => $this->getUpdatedAt(),
        ];
        if ($result[$keys[34]] instanceof \DateTimeInterface) {
            $result[$keys[34]] = $result[$keys[34]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[35]] instanceof \DateTimeInterface) {
            $result[$keys[35]] = $result[$keys[35]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[36]] instanceof \DateTimeInterface) {
            $result[$keys[36]] = $result[$keys[36]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[37]] instanceof \DateTimeInterface) {
            $result[$keys[37]] = $result[$keys[37]]->format('Y-m-d H:i:s.u');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collArticles) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'articles';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'articless';
                        break;
                    default:
                        $key = 'Articles';
                }

                $result[$key] = $this->collArticles->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collImages) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'images';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'imagess';
                        break;
                    default:
                        $key = 'Images';
                }

                $result[$key] = $this->collImages->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = PublisherTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setName($value);
                break;
            case 3:
                $this->setNameAlphabetic($value);
                break;
            case 4:
                $this->setUrl($value);
                break;
            case 5:
                $this->setNoosfereId($value);
                break;
            case 6:
                $this->setRepresentative($value);
                break;
            case 7:
                $this->setAddress($value);
                break;
            case 8:
                $this->setPostalCode($value);
                break;
            case 9:
                $this->setCity($value);
                break;
            case 10:
                $this->setCountry($value);
                break;
            case 11:
                $this->setPhone($value);
                break;
            case 12:
                $this->setFax($value);
                break;
            case 13:
                $this->setWebsite($value);
                break;
            case 14:
                $this->setBuyLink($value);
                break;
            case 15:
                $this->setEmail($value);
                break;
            case 16:
                $this->setFacebook($value);
                break;
            case 17:
                $this->setTwitter($value);
                break;
            case 18:
                $this->setLegalForm($value);
                break;
            case 19:
                $this->setCreationYear($value);
                break;
            case 20:
                $this->setIsbn($value);
                break;
            case 21:
                $this->setVolumes($value);
                break;
            case 22:
                $this->setAverageRun($value);
                break;
            case 23:
                $this->setSpecialities($value);
                break;
            case 24:
                $this->setDiffuseur($value);
                break;
            case 25:
                $this->setDistributeur($value);
                break;
            case 26:
                $this->setVpc($value);
                break;
            case 27:
                $this->setPaypal($value);
                break;
            case 28:
                $this->setShippingMode($value);
                break;
            case 29:
                $this->setShippingFee($value);
                break;
            case 30:
                $this->setGln($value);
                break;
            case 31:
                $this->setDesc($value);
                break;
            case 32:
                $this->setDescShort($value);
                break;
            case 33:
                $this->setOrderBy($value);
                break;
            case 34:
                $this->setInsert($value);
                break;
            case 35:
                $this->setUpdate($value);
                break;
            case 36:
                $this->setCreatedAt($value);
                break;
            case 37:
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
        $keys = PublisherTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setSiteId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setName($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setNameAlphabetic($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setUrl($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setNoosfereId($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setRepresentative($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setAddress($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setPostalCode($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setCity($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setCountry($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setPhone($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setFax($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setWebsite($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setBuyLink($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setEmail($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setFacebook($arr[$keys[16]]);
        }
        if (array_key_exists($keys[17], $arr)) {
            $this->setTwitter($arr[$keys[17]]);
        }
        if (array_key_exists($keys[18], $arr)) {
            $this->setLegalForm($arr[$keys[18]]);
        }
        if (array_key_exists($keys[19], $arr)) {
            $this->setCreationYear($arr[$keys[19]]);
        }
        if (array_key_exists($keys[20], $arr)) {
            $this->setIsbn($arr[$keys[20]]);
        }
        if (array_key_exists($keys[21], $arr)) {
            $this->setVolumes($arr[$keys[21]]);
        }
        if (array_key_exists($keys[22], $arr)) {
            $this->setAverageRun($arr[$keys[22]]);
        }
        if (array_key_exists($keys[23], $arr)) {
            $this->setSpecialities($arr[$keys[23]]);
        }
        if (array_key_exists($keys[24], $arr)) {
            $this->setDiffuseur($arr[$keys[24]]);
        }
        if (array_key_exists($keys[25], $arr)) {
            $this->setDistributeur($arr[$keys[25]]);
        }
        if (array_key_exists($keys[26], $arr)) {
            $this->setVpc($arr[$keys[26]]);
        }
        if (array_key_exists($keys[27], $arr)) {
            $this->setPaypal($arr[$keys[27]]);
        }
        if (array_key_exists($keys[28], $arr)) {
            $this->setShippingMode($arr[$keys[28]]);
        }
        if (array_key_exists($keys[29], $arr)) {
            $this->setShippingFee($arr[$keys[29]]);
        }
        if (array_key_exists($keys[30], $arr)) {
            $this->setGln($arr[$keys[30]]);
        }
        if (array_key_exists($keys[31], $arr)) {
            $this->setDesc($arr[$keys[31]]);
        }
        if (array_key_exists($keys[32], $arr)) {
            $this->setDescShort($arr[$keys[32]]);
        }
        if (array_key_exists($keys[33], $arr)) {
            $this->setOrderBy($arr[$keys[33]]);
        }
        if (array_key_exists($keys[34], $arr)) {
            $this->setInsert($arr[$keys[34]]);
        }
        if (array_key_exists($keys[35], $arr)) {
            $this->setUpdate($arr[$keys[35]]);
        }
        if (array_key_exists($keys[36], $arr)) {
            $this->setCreatedAt($arr[$keys[36]]);
        }
        if (array_key_exists($keys[37], $arr)) {
            $this->setUpdatedAt($arr[$keys[37]]);
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
        $criteria = new Criteria(PublisherTableMap::DATABASE_NAME);

        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_ID)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_ID, $this->publisher_id);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_SITE_ID)) {
            $criteria->add(PublisherTableMap::COL_SITE_ID, $this->site_id);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_NAME)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_NAME, $this->publisher_name);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_NAME_ALPHABETIC)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_NAME_ALPHABETIC, $this->publisher_name_alphabetic);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_URL)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_URL, $this->publisher_url);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_NOOSFERE_ID)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_NOOSFERE_ID, $this->publisher_noosfere_id);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_REPRESENTATIVE)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_REPRESENTATIVE, $this->publisher_representative);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_ADDRESS)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_ADDRESS, $this->publisher_address);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_POSTAL_CODE)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_POSTAL_CODE, $this->publisher_postal_code);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_CITY)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_CITY, $this->publisher_city);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_COUNTRY)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_COUNTRY, $this->publisher_country);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_PHONE)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_PHONE, $this->publisher_phone);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_FAX)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_FAX, $this->publisher_fax);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_WEBSITE)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_WEBSITE, $this->publisher_website);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_BUY_LINK)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_BUY_LINK, $this->publisher_buy_link);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_EMAIL)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_EMAIL, $this->publisher_email);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_FACEBOOK)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_FACEBOOK, $this->publisher_facebook);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_TWITTER)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_TWITTER, $this->publisher_twitter);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_LEGAL_FORM)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_LEGAL_FORM, $this->publisher_legal_form);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_CREATION_YEAR)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_CREATION_YEAR, $this->publisher_creation_year);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_ISBN)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_ISBN, $this->publisher_isbn);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_VOLUMES)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_VOLUMES, $this->publisher_volumes);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_AVERAGE_RUN)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_AVERAGE_RUN, $this->publisher_average_run);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_SPECIALITIES)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_SPECIALITIES, $this->publisher_specialities);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_DIFFUSEUR)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_DIFFUSEUR, $this->publisher_diffuseur);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_DISTRIBUTEUR)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_DISTRIBUTEUR, $this->publisher_distributeur);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_VPC)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_VPC, $this->publisher_vpc);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_PAYPAL)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_PAYPAL, $this->publisher_paypal);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_SHIPPING_MODE)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_SHIPPING_MODE, $this->publisher_shipping_mode);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_SHIPPING_FEE)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_SHIPPING_FEE, $this->publisher_shipping_fee);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_GLN)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_GLN, $this->publisher_gln);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_DESC)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_DESC, $this->publisher_desc);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_DESC_SHORT)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_DESC_SHORT, $this->publisher_desc_short);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_ORDER_BY)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_ORDER_BY, $this->publisher_order_by);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_INSERT)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_INSERT, $this->publisher_insert);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_UPDATE)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_UPDATE, $this->publisher_update);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_CREATED)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_CREATED, $this->publisher_created);
        }
        if ($this->isColumnModified(PublisherTableMap::COL_PUBLISHER_UPDATED)) {
            $criteria->add(PublisherTableMap::COL_PUBLISHER_UPDATED, $this->publisher_updated);
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
        $criteria = ChildPublisherQuery::create();
        $criteria->add(PublisherTableMap::COL_PUBLISHER_ID, $this->publisher_id);

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
     * Generic method to set the primary key (publisher_id column).
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
     * @param object $copyObj An object of \Model\Publisher (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setSiteId($this->getSiteId());
        $copyObj->setName($this->getName());
        $copyObj->setNameAlphabetic($this->getNameAlphabetic());
        $copyObj->setUrl($this->getUrl());
        $copyObj->setNoosfereId($this->getNoosfereId());
        $copyObj->setRepresentative($this->getRepresentative());
        $copyObj->setAddress($this->getAddress());
        $copyObj->setPostalCode($this->getPostalCode());
        $copyObj->setCity($this->getCity());
        $copyObj->setCountry($this->getCountry());
        $copyObj->setPhone($this->getPhone());
        $copyObj->setFax($this->getFax());
        $copyObj->setWebsite($this->getWebsite());
        $copyObj->setBuyLink($this->getBuyLink());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setFacebook($this->getFacebook());
        $copyObj->setTwitter($this->getTwitter());
        $copyObj->setLegalForm($this->getLegalForm());
        $copyObj->setCreationYear($this->getCreationYear());
        $copyObj->setIsbn($this->getIsbn());
        $copyObj->setVolumes($this->getVolumes());
        $copyObj->setAverageRun($this->getAverageRun());
        $copyObj->setSpecialities($this->getSpecialities());
        $copyObj->setDiffuseur($this->getDiffuseur());
        $copyObj->setDistributeur($this->getDistributeur());
        $copyObj->setVpc($this->getVpc());
        $copyObj->setPaypal($this->getPaypal());
        $copyObj->setShippingMode($this->getShippingMode());
        $copyObj->setShippingFee($this->getShippingFee());
        $copyObj->setGln($this->getGln());
        $copyObj->setDesc($this->getDesc());
        $copyObj->setDescShort($this->getDescShort());
        $copyObj->setOrderBy($this->getOrderBy());
        $copyObj->setInsert($this->getInsert());
        $copyObj->setUpdate($this->getUpdate());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getArticles() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addArticle($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getImages() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addImage($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getRights() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addRight($relObj->copy($deepCopy));
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
     * @return \Model\Publisher Clone of current object.
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
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName): void
    {
        if ('Article' === $relationName) {
            $this->initArticles();
            return;
        }
        if ('Image' === $relationName) {
            $this->initImages();
            return;
        }
        if ('Right' === $relationName) {
            $this->initRights();
            return;
        }
    }

    /**
     * Clears out the collArticles collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addArticles()
     */
    public function clearArticles()
    {
        $this->collArticles = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collArticles collection loaded partially.
     *
     * @return void
     */
    public function resetPartialArticles($v = true): void
    {
        $this->collArticlesPartial = $v;
    }

    /**
     * Initializes the collArticles collection.
     *
     * By default this just sets the collArticles collection to an empty array (like clearcollArticles());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initArticles(bool $overrideExisting = true): void
    {
        if (null !== $this->collArticles && !$overrideExisting) {
            return;
        }

        $collectionClassName = ArticleTableMap::getTableMap()->getCollectionClassName();

        $this->collArticles = new $collectionClassName;
        $this->collArticles->setModel('\Model\Article');
    }

    /**
     * Gets an array of ChildArticle objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPublisher is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildArticle[] List of ChildArticle objects
     * @phpstan-return ObjectCollection&\Traversable<ChildArticle> List of ChildArticle objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getArticles(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collArticlesPartial && !$this->isNew();
        if (null === $this->collArticles || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collArticles) {
                    $this->initArticles();
                } else {
                    $collectionClassName = ArticleTableMap::getTableMap()->getCollectionClassName();

                    $collArticles = new $collectionClassName;
                    $collArticles->setModel('\Model\Article');

                    return $collArticles;
                }
            } else {
                $collArticles = ChildArticleQuery::create(null, $criteria)
                    ->filterByPublisher($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collArticlesPartial && count($collArticles)) {
                        $this->initArticles(false);

                        foreach ($collArticles as $obj) {
                            if (false == $this->collArticles->contains($obj)) {
                                $this->collArticles->append($obj);
                            }
                        }

                        $this->collArticlesPartial = true;
                    }

                    return $collArticles;
                }

                if ($partial && $this->collArticles) {
                    foreach ($this->collArticles as $obj) {
                        if ($obj->isNew()) {
                            $collArticles[] = $obj;
                        }
                    }
                }

                $this->collArticles = $collArticles;
                $this->collArticlesPartial = false;
            }
        }

        return $this->collArticles;
    }

    /**
     * Sets a collection of ChildArticle objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $articles A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setArticles(Collection $articles, ?ConnectionInterface $con = null)
    {
        /** @var ChildArticle[] $articlesToDelete */
        $articlesToDelete = $this->getArticles(new Criteria(), $con)->diff($articles);


        $this->articlesScheduledForDeletion = $articlesToDelete;

        foreach ($articlesToDelete as $articleRemoved) {
            $articleRemoved->setPublisher(null);
        }

        $this->collArticles = null;
        foreach ($articles as $article) {
            $this->addArticle($article);
        }

        $this->collArticles = $articles;
        $this->collArticlesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Article objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Article objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countArticles(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collArticlesPartial && !$this->isNew();
        if (null === $this->collArticles || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collArticles) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getArticles());
            }

            $query = ChildArticleQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublisher($this)
                ->count($con);
        }

        return count($this->collArticles);
    }

    /**
     * Method called to associate a ChildArticle object to this object
     * through the ChildArticle foreign key attribute.
     *
     * @param ChildArticle $l ChildArticle
     * @return $this The current object (for fluent API support)
     */
    public function addArticle(ChildArticle $l)
    {
        if ($this->collArticles === null) {
            $this->initArticles();
            $this->collArticlesPartial = true;
        }

        if (!$this->collArticles->contains($l)) {
            $this->doAddArticle($l);

            if ($this->articlesScheduledForDeletion and $this->articlesScheduledForDeletion->contains($l)) {
                $this->articlesScheduledForDeletion->remove($this->articlesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildArticle $article The ChildArticle object to add.
     */
    protected function doAddArticle(ChildArticle $article): void
    {
        $this->collArticles[]= $article;
        $article->setPublisher($this);
    }

    /**
     * @param ChildArticle $article The ChildArticle object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeArticle(ChildArticle $article)
    {
        if ($this->getArticles()->contains($article)) {
            $pos = $this->collArticles->search($article);
            $this->collArticles->remove($pos);
            if (null === $this->articlesScheduledForDeletion) {
                $this->articlesScheduledForDeletion = clone $this->collArticles;
                $this->articlesScheduledForDeletion->clear();
            }
            $this->articlesScheduledForDeletion[]= $article;
            $article->setPublisher(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Articles from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildArticle[] List of ChildArticle objects
     * @phpstan-return ObjectCollection&\Traversable<ChildArticle}> List of ChildArticle objects
     */
    public function getArticlesJoinBookCollection(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildArticleQuery::create(null, $criteria);
        $query->joinWith('BookCollection', $joinBehavior);

        return $this->getArticles($query, $con);
    }

    /**
     * Clears out the collImages collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addImages()
     */
    public function clearImages()
    {
        $this->collImages = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collImages collection loaded partially.
     *
     * @return void
     */
    public function resetPartialImages($v = true): void
    {
        $this->collImagesPartial = $v;
    }

    /**
     * Initializes the collImages collection.
     *
     * By default this just sets the collImages collection to an empty array (like clearcollImages());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initImages(bool $overrideExisting = true): void
    {
        if (null !== $this->collImages && !$overrideExisting) {
            return;
        }

        $collectionClassName = ImageTableMap::getTableMap()->getCollectionClassName();

        $this->collImages = new $collectionClassName;
        $this->collImages->setModel('\Model\Image');
    }

    /**
     * Gets an array of ChildImage objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPublisher is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildImage[] List of ChildImage objects
     * @phpstan-return ObjectCollection&\Traversable<ChildImage> List of ChildImage objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getImages(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collImagesPartial && !$this->isNew();
        if (null === $this->collImages || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collImages) {
                    $this->initImages();
                } else {
                    $collectionClassName = ImageTableMap::getTableMap()->getCollectionClassName();

                    $collImages = new $collectionClassName;
                    $collImages->setModel('\Model\Image');

                    return $collImages;
                }
            } else {
                $collImages = ChildImageQuery::create(null, $criteria)
                    ->filterByPublisher($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collImagesPartial && count($collImages)) {
                        $this->initImages(false);

                        foreach ($collImages as $obj) {
                            if (false == $this->collImages->contains($obj)) {
                                $this->collImages->append($obj);
                            }
                        }

                        $this->collImagesPartial = true;
                    }

                    return $collImages;
                }

                if ($partial && $this->collImages) {
                    foreach ($this->collImages as $obj) {
                        if ($obj->isNew()) {
                            $collImages[] = $obj;
                        }
                    }
                }

                $this->collImages = $collImages;
                $this->collImagesPartial = false;
            }
        }

        return $this->collImages;
    }

    /**
     * Sets a collection of ChildImage objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $images A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setImages(Collection $images, ?ConnectionInterface $con = null)
    {
        /** @var ChildImage[] $imagesToDelete */
        $imagesToDelete = $this->getImages(new Criteria(), $con)->diff($images);


        $this->imagesScheduledForDeletion = $imagesToDelete;

        foreach ($imagesToDelete as $imageRemoved) {
            $imageRemoved->setPublisher(null);
        }

        $this->collImages = null;
        foreach ($images as $image) {
            $this->addImage($image);
        }

        $this->collImages = $images;
        $this->collImagesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Image objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Image objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countImages(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collImagesPartial && !$this->isNew();
        if (null === $this->collImages || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collImages) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getImages());
            }

            $query = ChildImageQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPublisher($this)
                ->count($con);
        }

        return count($this->collImages);
    }

    /**
     * Method called to associate a ChildImage object to this object
     * through the ChildImage foreign key attribute.
     *
     * @param ChildImage $l ChildImage
     * @return $this The current object (for fluent API support)
     */
    public function addImage(ChildImage $l)
    {
        if ($this->collImages === null) {
            $this->initImages();
            $this->collImagesPartial = true;
        }

        if (!$this->collImages->contains($l)) {
            $this->doAddImage($l);

            if ($this->imagesScheduledForDeletion and $this->imagesScheduledForDeletion->contains($l)) {
                $this->imagesScheduledForDeletion->remove($this->imagesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildImage $image The ChildImage object to add.
     */
    protected function doAddImage(ChildImage $image): void
    {
        $this->collImages[]= $image;
        $image->setPublisher($this);
    }

    /**
     * @param ChildImage $image The ChildImage object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeImage(ChildImage $image)
    {
        if ($this->getImages()->contains($image)) {
            $pos = $this->collImages->search($image);
            $this->collImages->remove($pos);
            if (null === $this->imagesScheduledForDeletion) {
                $this->imagesScheduledForDeletion = clone $this->collImages;
                $this->imagesScheduledForDeletion->clear();
            }
            $this->imagesScheduledForDeletion[]= $image;
            $image->setPublisher(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Images from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildImage[] List of ChildImage objects
     * @phpstan-return ObjectCollection&\Traversable<ChildImage}> List of ChildImage objects
     */
    public function getImagesJoinSite(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildImageQuery::create(null, $criteria);
        $query->joinWith('Site', $joinBehavior);

        return $this->getImages($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Images from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildImage[] List of ChildImage objects
     * @phpstan-return ObjectCollection&\Traversable<ChildImage}> List of ChildImage objects
     */
    public function getImagesJoinArticle(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildImageQuery::create(null, $criteria);
        $query->joinWith('Article', $joinBehavior);

        return $this->getImages($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Images from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildImage[] List of ChildImage objects
     * @phpstan-return ObjectCollection&\Traversable<ChildImage}> List of ChildImage objects
     */
    public function getImagesJoinStockItem(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildImageQuery::create(null, $criteria);
        $query->joinWith('StockItem', $joinBehavior);

        return $this->getImages($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Images from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildImage[] List of ChildImage objects
     * @phpstan-return ObjectCollection&\Traversable<ChildImage}> List of ChildImage objects
     */
    public function getImagesJoinContributor(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildImageQuery::create(null, $criteria);
        $query->joinWith('Contributor', $joinBehavior);

        return $this->getImages($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Images from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildImage[] List of ChildImage objects
     * @phpstan-return ObjectCollection&\Traversable<ChildImage}> List of ChildImage objects
     */
    public function getImagesJoinPost(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildImageQuery::create(null, $criteria);
        $query->joinWith('Post', $joinBehavior);

        return $this->getImages($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Images from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildImage[] List of ChildImage objects
     * @phpstan-return ObjectCollection&\Traversable<ChildImage}> List of ChildImage objects
     */
    public function getImagesJoinEvent(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildImageQuery::create(null, $criteria);
        $query->joinWith('Event', $joinBehavior);

        return $this->getImages($query, $con);
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
     * If this ChildPublisher is new, it will return
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
                    ->filterByPublisher($this)
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
            $rightRemoved->setPublisher(null);
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
                ->filterByPublisher($this)
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
        $right->setPublisher($this);
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
            $right->setPublisher(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Rights from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildRight[] List of ChildRight objects
     * @phpstan-return ObjectCollection&\Traversable<ChildRight}> List of ChildRight objects
     */
    public function getRightsJoinUser(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildRightQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getRights($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Publisher is new, it will return
     * an empty collection; or if this Publisher has previously
     * been saved, it will retrieve related Rights from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Publisher.
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
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     *
     * @return $this
     */
    public function clear()
    {
        $this->publisher_id = null;
        $this->site_id = null;
        $this->publisher_name = null;
        $this->publisher_name_alphabetic = null;
        $this->publisher_url = null;
        $this->publisher_noosfere_id = null;
        $this->publisher_representative = null;
        $this->publisher_address = null;
        $this->publisher_postal_code = null;
        $this->publisher_city = null;
        $this->publisher_country = null;
        $this->publisher_phone = null;
        $this->publisher_fax = null;
        $this->publisher_website = null;
        $this->publisher_buy_link = null;
        $this->publisher_email = null;
        $this->publisher_facebook = null;
        $this->publisher_twitter = null;
        $this->publisher_legal_form = null;
        $this->publisher_creation_year = null;
        $this->publisher_isbn = null;
        $this->publisher_volumes = null;
        $this->publisher_average_run = null;
        $this->publisher_specialities = null;
        $this->publisher_diffuseur = null;
        $this->publisher_distributeur = null;
        $this->publisher_vpc = null;
        $this->publisher_paypal = null;
        $this->publisher_shipping_mode = null;
        $this->publisher_shipping_fee = null;
        $this->publisher_gln = null;
        $this->publisher_desc = null;
        $this->publisher_desc_short = null;
        $this->publisher_order_by = null;
        $this->publisher_insert = null;
        $this->publisher_update = null;
        $this->publisher_created = null;
        $this->publisher_updated = null;
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
            if ($this->collArticles) {
                foreach ($this->collArticles as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collImages) {
                foreach ($this->collImages as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collRights) {
                foreach ($this->collRights as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collArticles = null;
        $this->collImages = null;
        $this->collRights = null;
        return $this;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PublisherTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return $this The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[PublisherTableMap::COL_PUBLISHER_UPDATED] = true;

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
