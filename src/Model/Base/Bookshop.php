<?php

namespace Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Model\BookshopQuery as ChildBookshopQuery;
use Model\Map\BookshopTableMap;
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
 * Base class that represents a row from the 'bookshops' table.
 *
 *
 *
 * @package    propel.generator.Model.Base
 */
abstract class Bookshop implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\Model\\Map\\BookshopTableMap';


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
     * The value for the bookshop_id field.
     *
     * @var        int
     */
    protected $bookshop_id;

    /**
     * The value for the bookshop_name field.
     *
     * @var        string|null
     */
    protected $bookshop_name;

    /**
     * The value for the bookshop_name_alphabetic field.
     *
     * @var        string|null
     */
    protected $bookshop_name_alphabetic;

    /**
     * The value for the bookshop_url field.
     *
     * @var        string|null
     */
    protected $bookshop_url;

    /**
     * The value for the bookshop_representative field.
     *
     * @var        string|null
     */
    protected $bookshop_representative;

    /**
     * The value for the bookshop_address field.
     *
     * @var        string|null
     */
    protected $bookshop_address;

    /**
     * The value for the bookshop_postal_code field.
     *
     * @var        string|null
     */
    protected $bookshop_postal_code;

    /**
     * The value for the bookshop_city field.
     *
     * @var        string|null
     */
    protected $bookshop_city;

    /**
     * The value for the bookshop_country field.
     *
     * @var        string|null
     */
    protected $bookshop_country;

    /**
     * The value for the bookshop_phone field.
     *
     * @var        string|null
     */
    protected $bookshop_phone;

    /**
     * The value for the bookshop_fax field.
     *
     * @var        string|null
     */
    protected $bookshop_fax;

    /**
     * The value for the bookshop_website field.
     *
     * @var        string|null
     */
    protected $bookshop_website;

    /**
     * The value for the bookshop_email field.
     *
     * @var        string|null
     */
    protected $bookshop_email;

    /**
     * The value for the bookshop_facebook field.
     *
     * @var        string|null
     */
    protected $bookshop_facebook;

    /**
     * The value for the bookshop_twitter field.
     *
     * @var        string|null
     */
    protected $bookshop_twitter;

    /**
     * The value for the bookshop_legal_form field.
     *
     * @var        string|null
     */
    protected $bookshop_legal_form;

    /**
     * The value for the bookshop_creation_year field.
     *
     * @var        string|null
     */
    protected $bookshop_creation_year;

    /**
     * The value for the bookshop_specialities field.
     *
     * @var        string|null
     */
    protected $bookshop_specialities;

    /**
     * The value for the bookshop_membership field.
     *
     * @var        string|null
     */
    protected $bookshop_membership;

    /**
     * The value for the bookshop_motto field.
     *
     * @var        string|null
     */
    protected $bookshop_motto;

    /**
     * The value for the bookshop_desc field.
     *
     * @var        string|null
     */
    protected $bookshop_desc;

    /**
     * The value for the bookshop_created field.
     *
     * Note: this column has a database default value of: (expression) CURRENT_TIMESTAMP
     * @var        DateTime|null
     */
    protected $bookshop_created;

    /**
     * The value for the bookshop_updated field.
     *
     * @var        DateTime|null
     */
    protected $bookshop_updated;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var bool
     */
    protected $alreadyInSave = false;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues(): void
    {
    }

    /**
     * Initializes internal state of Model\Base\Bookshop object.
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
     * Compares this with another <code>Bookshop</code> instance.  If
     * <code>obj</code> is an instance of <code>Bookshop</code>, delegates to
     * <code>equals(Bookshop)</code>.  Otherwise, returns <code>false</code>.
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
     * Get the [bookshop_id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->bookshop_id;
    }

    /**
     * Get the [bookshop_name] column value.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->bookshop_name;
    }

    /**
     * Get the [bookshop_name_alphabetic] column value.
     *
     * @return string|null
     */
    public function getNameAlphabetic()
    {
        return $this->bookshop_name_alphabetic;
    }

    /**
     * Get the [bookshop_url] column value.
     *
     * @return string|null
     */
    public function getUrl()
    {
        return $this->bookshop_url;
    }

    /**
     * Get the [bookshop_representative] column value.
     *
     * @return string|null
     */
    public function getRepresentative()
    {
        return $this->bookshop_representative;
    }

    /**
     * Get the [bookshop_address] column value.
     *
     * @return string|null
     */
    public function getAddress()
    {
        return $this->bookshop_address;
    }

    /**
     * Get the [bookshop_postal_code] column value.
     *
     * @return string|null
     */
    public function getPostalCode()
    {
        return $this->bookshop_postal_code;
    }

    /**
     * Get the [bookshop_city] column value.
     *
     * @return string|null
     */
    public function getCity()
    {
        return $this->bookshop_city;
    }

    /**
     * Get the [bookshop_country] column value.
     *
     * @return string|null
     */
    public function getCountry()
    {
        return $this->bookshop_country;
    }

    /**
     * Get the [bookshop_phone] column value.
     *
     * @return string|null
     */
    public function getPhone()
    {
        return $this->bookshop_phone;
    }

    /**
     * Get the [bookshop_fax] column value.
     *
     * @return string|null
     */
    public function getFax()
    {
        return $this->bookshop_fax;
    }

    /**
     * Get the [bookshop_website] column value.
     *
     * @return string|null
     */
    public function getWebsite()
    {
        return $this->bookshop_website;
    }

    /**
     * Get the [bookshop_email] column value.
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->bookshop_email;
    }

    /**
     * Get the [bookshop_facebook] column value.
     *
     * @return string|null
     */
    public function getFacebook()
    {
        return $this->bookshop_facebook;
    }

    /**
     * Get the [bookshop_twitter] column value.
     *
     * @return string|null
     */
    public function getTwitter()
    {
        return $this->bookshop_twitter;
    }

    /**
     * Get the [bookshop_legal_form] column value.
     *
     * @return string|null
     */
    public function getLegalForm()
    {
        return $this->bookshop_legal_form;
    }

    /**
     * Get the [bookshop_creation_year] column value.
     *
     * @return string|null
     */
    public function getCreationYear()
    {
        return $this->bookshop_creation_year;
    }

    /**
     * Get the [bookshop_specialities] column value.
     *
     * @return string|null
     */
    public function getSpecialities()
    {
        return $this->bookshop_specialities;
    }

    /**
     * Get the [bookshop_membership] column value.
     *
     * @return string|null
     */
    public function getMembership()
    {
        return $this->bookshop_membership;
    }

    /**
     * Get the [bookshop_motto] column value.
     *
     * @return string|null
     */
    public function getMotto()
    {
        return $this->bookshop_motto;
    }

    /**
     * Get the [bookshop_desc] column value.
     *
     * @return string|null
     */
    public function getDesc()
    {
        return $this->bookshop_desc;
    }

    /**
     * Get the [optionally formatted] temporal [bookshop_created] column value.
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
            return $this->bookshop_created;
        } else {
            return $this->bookshop_created instanceof \DateTimeInterface ? $this->bookshop_created->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [bookshop_updated] column value.
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
            return $this->bookshop_updated;
        } else {
            return $this->bookshop_updated instanceof \DateTimeInterface ? $this->bookshop_updated->format($format) : null;
        }
    }

    /**
     * Set the value of [bookshop_id] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->bookshop_id !== $v) {
            $this->bookshop_id = $v;
            $this->modifiedColumns[BookshopTableMap::COL_BOOKSHOP_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [bookshop_name] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->bookshop_name !== $v) {
            $this->bookshop_name = $v;
            $this->modifiedColumns[BookshopTableMap::COL_BOOKSHOP_NAME] = true;
        }

        return $this;
    }

    /**
     * Set the value of [bookshop_name_alphabetic] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setNameAlphabetic($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->bookshop_name_alphabetic !== $v) {
            $this->bookshop_name_alphabetic = $v;
            $this->modifiedColumns[BookshopTableMap::COL_BOOKSHOP_NAME_ALPHABETIC] = true;
        }

        return $this;
    }

    /**
     * Set the value of [bookshop_url] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->bookshop_url !== $v) {
            $this->bookshop_url = $v;
            $this->modifiedColumns[BookshopTableMap::COL_BOOKSHOP_URL] = true;
        }

        return $this;
    }

    /**
     * Set the value of [bookshop_representative] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setRepresentative($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->bookshop_representative !== $v) {
            $this->bookshop_representative = $v;
            $this->modifiedColumns[BookshopTableMap::COL_BOOKSHOP_REPRESENTATIVE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [bookshop_address] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setAddress($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->bookshop_address !== $v) {
            $this->bookshop_address = $v;
            $this->modifiedColumns[BookshopTableMap::COL_BOOKSHOP_ADDRESS] = true;
        }

        return $this;
    }

    /**
     * Set the value of [bookshop_postal_code] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPostalCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->bookshop_postal_code !== $v) {
            $this->bookshop_postal_code = $v;
            $this->modifiedColumns[BookshopTableMap::COL_BOOKSHOP_POSTAL_CODE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [bookshop_city] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setCity($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->bookshop_city !== $v) {
            $this->bookshop_city = $v;
            $this->modifiedColumns[BookshopTableMap::COL_BOOKSHOP_CITY] = true;
        }

        return $this;
    }

    /**
     * Set the value of [bookshop_country] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setCountry($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->bookshop_country !== $v) {
            $this->bookshop_country = $v;
            $this->modifiedColumns[BookshopTableMap::COL_BOOKSHOP_COUNTRY] = true;
        }

        return $this;
    }

    /**
     * Set the value of [bookshop_phone] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPhone($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->bookshop_phone !== $v) {
            $this->bookshop_phone = $v;
            $this->modifiedColumns[BookshopTableMap::COL_BOOKSHOP_PHONE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [bookshop_fax] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setFax($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->bookshop_fax !== $v) {
            $this->bookshop_fax = $v;
            $this->modifiedColumns[BookshopTableMap::COL_BOOKSHOP_FAX] = true;
        }

        return $this;
    }

    /**
     * Set the value of [bookshop_website] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setWebsite($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->bookshop_website !== $v) {
            $this->bookshop_website = $v;
            $this->modifiedColumns[BookshopTableMap::COL_BOOKSHOP_WEBSITE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [bookshop_email] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->bookshop_email !== $v) {
            $this->bookshop_email = $v;
            $this->modifiedColumns[BookshopTableMap::COL_BOOKSHOP_EMAIL] = true;
        }

        return $this;
    }

    /**
     * Set the value of [bookshop_facebook] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setFacebook($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->bookshop_facebook !== $v) {
            $this->bookshop_facebook = $v;
            $this->modifiedColumns[BookshopTableMap::COL_BOOKSHOP_FACEBOOK] = true;
        }

        return $this;
    }

    /**
     * Set the value of [bookshop_twitter] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setTwitter($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->bookshop_twitter !== $v) {
            $this->bookshop_twitter = $v;
            $this->modifiedColumns[BookshopTableMap::COL_BOOKSHOP_TWITTER] = true;
        }

        return $this;
    }

    /**
     * Set the value of [bookshop_legal_form] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setLegalForm($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->bookshop_legal_form !== $v) {
            $this->bookshop_legal_form = $v;
            $this->modifiedColumns[BookshopTableMap::COL_BOOKSHOP_LEGAL_FORM] = true;
        }

        return $this;
    }

    /**
     * Set the value of [bookshop_creation_year] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setCreationYear($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->bookshop_creation_year !== $v) {
            $this->bookshop_creation_year = $v;
            $this->modifiedColumns[BookshopTableMap::COL_BOOKSHOP_CREATION_YEAR] = true;
        }

        return $this;
    }

    /**
     * Set the value of [bookshop_specialities] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setSpecialities($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->bookshop_specialities !== $v) {
            $this->bookshop_specialities = $v;
            $this->modifiedColumns[BookshopTableMap::COL_BOOKSHOP_SPECIALITIES] = true;
        }

        return $this;
    }

    /**
     * Set the value of [bookshop_membership] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setMembership($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->bookshop_membership !== $v) {
            $this->bookshop_membership = $v;
            $this->modifiedColumns[BookshopTableMap::COL_BOOKSHOP_MEMBERSHIP] = true;
        }

        return $this;
    }

    /**
     * Set the value of [bookshop_motto] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setMotto($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->bookshop_motto !== $v) {
            $this->bookshop_motto = $v;
            $this->modifiedColumns[BookshopTableMap::COL_BOOKSHOP_MOTTO] = true;
        }

        return $this;
    }

    /**
     * Set the value of [bookshop_desc] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setDesc($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->bookshop_desc !== $v) {
            $this->bookshop_desc = $v;
            $this->modifiedColumns[BookshopTableMap::COL_BOOKSHOP_DESC] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [bookshop_created] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->bookshop_created !== null || $dt !== null) {
            if ($this->bookshop_created === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->bookshop_created->format("Y-m-d H:i:s.u")) {
                $this->bookshop_created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[BookshopTableMap::COL_BOOKSHOP_CREATED] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [bookshop_updated] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->bookshop_updated !== null || $dt !== null) {
            if ($this->bookshop_updated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->bookshop_updated->format("Y-m-d H:i:s.u")) {
                $this->bookshop_updated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[BookshopTableMap::COL_BOOKSHOP_UPDATED] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : BookshopTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bookshop_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : BookshopTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bookshop_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : BookshopTableMap::translateFieldName('NameAlphabetic', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bookshop_name_alphabetic = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : BookshopTableMap::translateFieldName('Url', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bookshop_url = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : BookshopTableMap::translateFieldName('Representative', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bookshop_representative = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : BookshopTableMap::translateFieldName('Address', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bookshop_address = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : BookshopTableMap::translateFieldName('PostalCode', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bookshop_postal_code = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : BookshopTableMap::translateFieldName('City', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bookshop_city = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : BookshopTableMap::translateFieldName('Country', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bookshop_country = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : BookshopTableMap::translateFieldName('Phone', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bookshop_phone = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : BookshopTableMap::translateFieldName('Fax', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bookshop_fax = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : BookshopTableMap::translateFieldName('Website', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bookshop_website = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : BookshopTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bookshop_email = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : BookshopTableMap::translateFieldName('Facebook', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bookshop_facebook = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : BookshopTableMap::translateFieldName('Twitter', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bookshop_twitter = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : BookshopTableMap::translateFieldName('LegalForm', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bookshop_legal_form = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : BookshopTableMap::translateFieldName('CreationYear', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bookshop_creation_year = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 17 + $startcol : BookshopTableMap::translateFieldName('Specialities', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bookshop_specialities = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 18 + $startcol : BookshopTableMap::translateFieldName('Membership', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bookshop_membership = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 19 + $startcol : BookshopTableMap::translateFieldName('Motto', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bookshop_motto = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 20 + $startcol : BookshopTableMap::translateFieldName('Desc', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bookshop_desc = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 21 + $startcol : BookshopTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->bookshop_created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 22 + $startcol : BookshopTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->bookshop_updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 23; // 23 = BookshopTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\Bookshop'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(BookshopTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildBookshopQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
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
     * @param ConnectionInterface $con
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     * @see Bookshop::setDeleted()
     * @see Bookshop::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(BookshopTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildBookshopQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(BookshopTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_CREATED)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_UPDATED)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_UPDATED)) {
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
                BookshopTableMap::addInstanceToPool($this);
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

        $this->modifiedColumns[BookshopTableMap::COL_BOOKSHOP_ID] = true;
        if (null !== $this->bookshop_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . BookshopTableMap::COL_BOOKSHOP_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_ID)) {
            $modifiedColumns[':p' . $index++]  = 'bookshop_id';
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'bookshop_name';
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_NAME_ALPHABETIC)) {
            $modifiedColumns[':p' . $index++]  = 'bookshop_name_alphabetic';
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_URL)) {
            $modifiedColumns[':p' . $index++]  = 'bookshop_url';
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_REPRESENTATIVE)) {
            $modifiedColumns[':p' . $index++]  = 'bookshop_representative';
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_ADDRESS)) {
            $modifiedColumns[':p' . $index++]  = 'bookshop_address';
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_POSTAL_CODE)) {
            $modifiedColumns[':p' . $index++]  = 'bookshop_postal_code';
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_CITY)) {
            $modifiedColumns[':p' . $index++]  = 'bookshop_city';
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_COUNTRY)) {
            $modifiedColumns[':p' . $index++]  = 'bookshop_country';
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_PHONE)) {
            $modifiedColumns[':p' . $index++]  = 'bookshop_phone';
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_FAX)) {
            $modifiedColumns[':p' . $index++]  = 'bookshop_fax';
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_WEBSITE)) {
            $modifiedColumns[':p' . $index++]  = 'bookshop_website';
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'bookshop_email';
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_FACEBOOK)) {
            $modifiedColumns[':p' . $index++]  = 'bookshop_facebook';
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_TWITTER)) {
            $modifiedColumns[':p' . $index++]  = 'bookshop_twitter';
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_LEGAL_FORM)) {
            $modifiedColumns[':p' . $index++]  = 'bookshop_legal_form';
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_CREATION_YEAR)) {
            $modifiedColumns[':p' . $index++]  = 'bookshop_creation_year';
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_SPECIALITIES)) {
            $modifiedColumns[':p' . $index++]  = 'bookshop_specialities';
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_MEMBERSHIP)) {
            $modifiedColumns[':p' . $index++]  = 'bookshop_membership';
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_MOTTO)) {
            $modifiedColumns[':p' . $index++]  = 'bookshop_motto';
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_DESC)) {
            $modifiedColumns[':p' . $index++]  = 'bookshop_desc';
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'bookshop_created';
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = 'bookshop_updated';
        }

        $sql = sprintf(
            'INSERT INTO bookshops (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'bookshop_id':
                        $stmt->bindValue($identifier, $this->bookshop_id, PDO::PARAM_INT);
                        break;
                    case 'bookshop_name':
                        $stmt->bindValue($identifier, $this->bookshop_name, PDO::PARAM_STR);
                        break;
                    case 'bookshop_name_alphabetic':
                        $stmt->bindValue($identifier, $this->bookshop_name_alphabetic, PDO::PARAM_STR);
                        break;
                    case 'bookshop_url':
                        $stmt->bindValue($identifier, $this->bookshop_url, PDO::PARAM_STR);
                        break;
                    case 'bookshop_representative':
                        $stmt->bindValue($identifier, $this->bookshop_representative, PDO::PARAM_STR);
                        break;
                    case 'bookshop_address':
                        $stmt->bindValue($identifier, $this->bookshop_address, PDO::PARAM_STR);
                        break;
                    case 'bookshop_postal_code':
                        $stmt->bindValue($identifier, $this->bookshop_postal_code, PDO::PARAM_STR);
                        break;
                    case 'bookshop_city':
                        $stmt->bindValue($identifier, $this->bookshop_city, PDO::PARAM_STR);
                        break;
                    case 'bookshop_country':
                        $stmt->bindValue($identifier, $this->bookshop_country, PDO::PARAM_STR);
                        break;
                    case 'bookshop_phone':
                        $stmt->bindValue($identifier, $this->bookshop_phone, PDO::PARAM_STR);
                        break;
                    case 'bookshop_fax':
                        $stmt->bindValue($identifier, $this->bookshop_fax, PDO::PARAM_STR);
                        break;
                    case 'bookshop_website':
                        $stmt->bindValue($identifier, $this->bookshop_website, PDO::PARAM_STR);
                        break;
                    case 'bookshop_email':
                        $stmt->bindValue($identifier, $this->bookshop_email, PDO::PARAM_STR);
                        break;
                    case 'bookshop_facebook':
                        $stmt->bindValue($identifier, $this->bookshop_facebook, PDO::PARAM_STR);
                        break;
                    case 'bookshop_twitter':
                        $stmt->bindValue($identifier, $this->bookshop_twitter, PDO::PARAM_STR);
                        break;
                    case 'bookshop_legal_form':
                        $stmt->bindValue($identifier, $this->bookshop_legal_form, PDO::PARAM_STR);
                        break;
                    case 'bookshop_creation_year':
                        $stmt->bindValue($identifier, $this->bookshop_creation_year, PDO::PARAM_STR);
                        break;
                    case 'bookshop_specialities':
                        $stmt->bindValue($identifier, $this->bookshop_specialities, PDO::PARAM_STR);
                        break;
                    case 'bookshop_membership':
                        $stmt->bindValue($identifier, $this->bookshop_membership, PDO::PARAM_STR);
                        break;
                    case 'bookshop_motto':
                        $stmt->bindValue($identifier, $this->bookshop_motto, PDO::PARAM_STR);
                        break;
                    case 'bookshop_desc':
                        $stmt->bindValue($identifier, $this->bookshop_desc, PDO::PARAM_STR);
                        break;
                    case 'bookshop_created':
                        $stmt->bindValue($identifier, $this->bookshop_created ? $this->bookshop_created->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'bookshop_updated':
                        $stmt->bindValue($identifier, $this->bookshop_updated ? $this->bookshop_updated->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
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
        $pos = BookshopTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getName();

            case 2:
                return $this->getNameAlphabetic();

            case 3:
                return $this->getUrl();

            case 4:
                return $this->getRepresentative();

            case 5:
                return $this->getAddress();

            case 6:
                return $this->getPostalCode();

            case 7:
                return $this->getCity();

            case 8:
                return $this->getCountry();

            case 9:
                return $this->getPhone();

            case 10:
                return $this->getFax();

            case 11:
                return $this->getWebsite();

            case 12:
                return $this->getEmail();

            case 13:
                return $this->getFacebook();

            case 14:
                return $this->getTwitter();

            case 15:
                return $this->getLegalForm();

            case 16:
                return $this->getCreationYear();

            case 17:
                return $this->getSpecialities();

            case 18:
                return $this->getMembership();

            case 19:
                return $this->getMotto();

            case 20:
                return $this->getDesc();

            case 21:
                return $this->getCreatedAt();

            case 22:
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
     *
     * @return array An associative array containing the field names (as keys) and field values
     */
    public function toArray(string $keyType = TableMap::TYPE_PHPNAME, bool $includeLazyLoadColumns = true, array $alreadyDumpedObjects = []): array
    {
        if (isset($alreadyDumpedObjects['Bookshop'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['Bookshop'][$this->hashCode()] = true;
        $keys = BookshopTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getNameAlphabetic(),
            $keys[3] => $this->getUrl(),
            $keys[4] => $this->getRepresentative(),
            $keys[5] => $this->getAddress(),
            $keys[6] => $this->getPostalCode(),
            $keys[7] => $this->getCity(),
            $keys[8] => $this->getCountry(),
            $keys[9] => $this->getPhone(),
            $keys[10] => $this->getFax(),
            $keys[11] => $this->getWebsite(),
            $keys[12] => $this->getEmail(),
            $keys[13] => $this->getFacebook(),
            $keys[14] => $this->getTwitter(),
            $keys[15] => $this->getLegalForm(),
            $keys[16] => $this->getCreationYear(),
            $keys[17] => $this->getSpecialities(),
            $keys[18] => $this->getMembership(),
            $keys[19] => $this->getMotto(),
            $keys[20] => $this->getDesc(),
            $keys[21] => $this->getCreatedAt(),
            $keys[22] => $this->getUpdatedAt(),
        ];
        if ($result[$keys[21]] instanceof \DateTimeInterface) {
            $result[$keys[21]] = $result[$keys[21]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[22]] instanceof \DateTimeInterface) {
            $result[$keys[22]] = $result[$keys[22]]->format('Y-m-d H:i:s.u');
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
        $pos = BookshopTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setName($value);
                break;
            case 2:
                $this->setNameAlphabetic($value);
                break;
            case 3:
                $this->setUrl($value);
                break;
            case 4:
                $this->setRepresentative($value);
                break;
            case 5:
                $this->setAddress($value);
                break;
            case 6:
                $this->setPostalCode($value);
                break;
            case 7:
                $this->setCity($value);
                break;
            case 8:
                $this->setCountry($value);
                break;
            case 9:
                $this->setPhone($value);
                break;
            case 10:
                $this->setFax($value);
                break;
            case 11:
                $this->setWebsite($value);
                break;
            case 12:
                $this->setEmail($value);
                break;
            case 13:
                $this->setFacebook($value);
                break;
            case 14:
                $this->setTwitter($value);
                break;
            case 15:
                $this->setLegalForm($value);
                break;
            case 16:
                $this->setCreationYear($value);
                break;
            case 17:
                $this->setSpecialities($value);
                break;
            case 18:
                $this->setMembership($value);
                break;
            case 19:
                $this->setMotto($value);
                break;
            case 20:
                $this->setDesc($value);
                break;
            case 21:
                $this->setCreatedAt($value);
                break;
            case 22:
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
        $keys = BookshopTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setNameAlphabetic($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setUrl($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setRepresentative($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setAddress($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setPostalCode($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setCity($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setCountry($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setPhone($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setFax($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setWebsite($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setEmail($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setFacebook($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setTwitter($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setLegalForm($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setCreationYear($arr[$keys[16]]);
        }
        if (array_key_exists($keys[17], $arr)) {
            $this->setSpecialities($arr[$keys[17]]);
        }
        if (array_key_exists($keys[18], $arr)) {
            $this->setMembership($arr[$keys[18]]);
        }
        if (array_key_exists($keys[19], $arr)) {
            $this->setMotto($arr[$keys[19]]);
        }
        if (array_key_exists($keys[20], $arr)) {
            $this->setDesc($arr[$keys[20]]);
        }
        if (array_key_exists($keys[21], $arr)) {
            $this->setCreatedAt($arr[$keys[21]]);
        }
        if (array_key_exists($keys[22], $arr)) {
            $this->setUpdatedAt($arr[$keys[22]]);
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
        $criteria = new Criteria(BookshopTableMap::DATABASE_NAME);

        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_ID)) {
            $criteria->add(BookshopTableMap::COL_BOOKSHOP_ID, $this->bookshop_id);
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_NAME)) {
            $criteria->add(BookshopTableMap::COL_BOOKSHOP_NAME, $this->bookshop_name);
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_NAME_ALPHABETIC)) {
            $criteria->add(BookshopTableMap::COL_BOOKSHOP_NAME_ALPHABETIC, $this->bookshop_name_alphabetic);
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_URL)) {
            $criteria->add(BookshopTableMap::COL_BOOKSHOP_URL, $this->bookshop_url);
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_REPRESENTATIVE)) {
            $criteria->add(BookshopTableMap::COL_BOOKSHOP_REPRESENTATIVE, $this->bookshop_representative);
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_ADDRESS)) {
            $criteria->add(BookshopTableMap::COL_BOOKSHOP_ADDRESS, $this->bookshop_address);
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_POSTAL_CODE)) {
            $criteria->add(BookshopTableMap::COL_BOOKSHOP_POSTAL_CODE, $this->bookshop_postal_code);
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_CITY)) {
            $criteria->add(BookshopTableMap::COL_BOOKSHOP_CITY, $this->bookshop_city);
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_COUNTRY)) {
            $criteria->add(BookshopTableMap::COL_BOOKSHOP_COUNTRY, $this->bookshop_country);
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_PHONE)) {
            $criteria->add(BookshopTableMap::COL_BOOKSHOP_PHONE, $this->bookshop_phone);
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_FAX)) {
            $criteria->add(BookshopTableMap::COL_BOOKSHOP_FAX, $this->bookshop_fax);
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_WEBSITE)) {
            $criteria->add(BookshopTableMap::COL_BOOKSHOP_WEBSITE, $this->bookshop_website);
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_EMAIL)) {
            $criteria->add(BookshopTableMap::COL_BOOKSHOP_EMAIL, $this->bookshop_email);
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_FACEBOOK)) {
            $criteria->add(BookshopTableMap::COL_BOOKSHOP_FACEBOOK, $this->bookshop_facebook);
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_TWITTER)) {
            $criteria->add(BookshopTableMap::COL_BOOKSHOP_TWITTER, $this->bookshop_twitter);
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_LEGAL_FORM)) {
            $criteria->add(BookshopTableMap::COL_BOOKSHOP_LEGAL_FORM, $this->bookshop_legal_form);
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_CREATION_YEAR)) {
            $criteria->add(BookshopTableMap::COL_BOOKSHOP_CREATION_YEAR, $this->bookshop_creation_year);
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_SPECIALITIES)) {
            $criteria->add(BookshopTableMap::COL_BOOKSHOP_SPECIALITIES, $this->bookshop_specialities);
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_MEMBERSHIP)) {
            $criteria->add(BookshopTableMap::COL_BOOKSHOP_MEMBERSHIP, $this->bookshop_membership);
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_MOTTO)) {
            $criteria->add(BookshopTableMap::COL_BOOKSHOP_MOTTO, $this->bookshop_motto);
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_DESC)) {
            $criteria->add(BookshopTableMap::COL_BOOKSHOP_DESC, $this->bookshop_desc);
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_CREATED)) {
            $criteria->add(BookshopTableMap::COL_BOOKSHOP_CREATED, $this->bookshop_created);
        }
        if ($this->isColumnModified(BookshopTableMap::COL_BOOKSHOP_UPDATED)) {
            $criteria->add(BookshopTableMap::COL_BOOKSHOP_UPDATED, $this->bookshop_updated);
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
        $criteria = ChildBookshopQuery::create();
        $criteria->add(BookshopTableMap::COL_BOOKSHOP_ID, $this->bookshop_id);

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
     * Generic method to set the primary key (bookshop_id column).
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
     * @param object $copyObj An object of \Model\Bookshop (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setName($this->getName());
        $copyObj->setNameAlphabetic($this->getNameAlphabetic());
        $copyObj->setUrl($this->getUrl());
        $copyObj->setRepresentative($this->getRepresentative());
        $copyObj->setAddress($this->getAddress());
        $copyObj->setPostalCode($this->getPostalCode());
        $copyObj->setCity($this->getCity());
        $copyObj->setCountry($this->getCountry());
        $copyObj->setPhone($this->getPhone());
        $copyObj->setFax($this->getFax());
        $copyObj->setWebsite($this->getWebsite());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setFacebook($this->getFacebook());
        $copyObj->setTwitter($this->getTwitter());
        $copyObj->setLegalForm($this->getLegalForm());
        $copyObj->setCreationYear($this->getCreationYear());
        $copyObj->setSpecialities($this->getSpecialities());
        $copyObj->setMembership($this->getMembership());
        $copyObj->setMotto($this->getMotto());
        $copyObj->setDesc($this->getDesc());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
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
     * @return \Model\Bookshop Clone of current object.
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
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     *
     * @return $this
     */
    public function clear()
    {
        $this->bookshop_id = null;
        $this->bookshop_name = null;
        $this->bookshop_name_alphabetic = null;
        $this->bookshop_url = null;
        $this->bookshop_representative = null;
        $this->bookshop_address = null;
        $this->bookshop_postal_code = null;
        $this->bookshop_city = null;
        $this->bookshop_country = null;
        $this->bookshop_phone = null;
        $this->bookshop_fax = null;
        $this->bookshop_website = null;
        $this->bookshop_email = null;
        $this->bookshop_facebook = null;
        $this->bookshop_twitter = null;
        $this->bookshop_legal_form = null;
        $this->bookshop_creation_year = null;
        $this->bookshop_specialities = null;
        $this->bookshop_membership = null;
        $this->bookshop_motto = null;
        $this->bookshop_desc = null;
        $this->bookshop_created = null;
        $this->bookshop_updated = null;
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
        } // if ($deep)

        return $this;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(BookshopTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return $this The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[BookshopTableMap::COL_BOOKSHOP_UPDATED] = true;

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
