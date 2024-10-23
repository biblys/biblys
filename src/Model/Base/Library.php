<?php
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


namespace Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Model\LibraryQuery as ChildLibraryQuery;
use Model\Map\LibraryTableMap;
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
 * Base class that represents a row from the 'libraries' table.
 *
 *
 *
 * @package    propel.generator.Model.Base
 */
abstract class Library implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\Model\\Map\\LibraryTableMap';


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
     * The value for the library_id field.
     *
     * @var        int
     */
    protected $library_id;

    /**
     * The value for the library_name field.
     *
     * @var        string|null
     */
    protected $library_name;

    /**
     * The value for the library_name_alphabetic field.
     *
     * @var        string|null
     */
    protected $library_name_alphabetic;

    /**
     * The value for the library_url field.
     *
     * @var        string|null
     */
    protected $library_url;

    /**
     * The value for the library_representative field.
     *
     * @var        string|null
     */
    protected $library_representative;

    /**
     * The value for the library_address field.
     *
     * @var        string|null
     */
    protected $library_address;

    /**
     * The value for the library_postal_code field.
     *
     * @var        string|null
     */
    protected $library_postal_code;

    /**
     * The value for the library_city field.
     *
     * @var        string|null
     */
    protected $library_city;

    /**
     * The value for the library_country field.
     *
     * @var        string|null
     */
    protected $library_country;

    /**
     * The value for the library_phone field.
     *
     * @var        string|null
     */
    protected $library_phone;

    /**
     * The value for the library_fax field.
     *
     * @var        string|null
     */
    protected $library_fax;

    /**
     * The value for the library_website field.
     *
     * @var        string|null
     */
    protected $library_website;

    /**
     * The value for the library_email field.
     *
     * @var        string|null
     */
    protected $library_email;

    /**
     * The value for the library_facebook field.
     *
     * @var        string|null
     */
    protected $library_facebook;

    /**
     * The value for the library_twitter field.
     *
     * @var        string|null
     */
    protected $library_twitter;

    /**
     * The value for the library_creation_year field.
     *
     * @var        string|null
     */
    protected $library_creation_year;

    /**
     * The value for the library_specialities field.
     *
     * @var        string|null
     */
    protected $library_specialities;

    /**
     * The value for the library_readings field.
     *
     * @var        string|null
     */
    protected $library_readings;

    /**
     * The value for the library_desc field.
     *
     * @var        string|null
     */
    protected $library_desc;

    /**
     * The value for the library_created field.
     *
     * Note: this column has a database default value of: (expression) CURRENT_TIMESTAMP
     * @var        DateTime|null
     */
    protected $library_created;

    /**
     * The value for the library_updated field.
     *
     * @var        DateTime|null
     */
    protected $library_updated;

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
     * Initializes internal state of Model\Base\Library object.
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
     * Compares this with another <code>Library</code> instance.  If
     * <code>obj</code> is an instance of <code>Library</code>, delegates to
     * <code>equals(Library)</code>.  Otherwise, returns <code>false</code>.
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
     * Get the [library_id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->library_id;
    }

    /**
     * Get the [library_name] column value.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->library_name;
    }

    /**
     * Get the [library_name_alphabetic] column value.
     *
     * @return string|null
     */
    public function getNameAlphabetic()
    {
        return $this->library_name_alphabetic;
    }

    /**
     * Get the [library_url] column value.
     *
     * @return string|null
     */
    public function getUrl()
    {
        return $this->library_url;
    }

    /**
     * Get the [library_representative] column value.
     *
     * @return string|null
     */
    public function getRepresentative()
    {
        return $this->library_representative;
    }

    /**
     * Get the [library_address] column value.
     *
     * @return string|null
     */
    public function getAddress()
    {
        return $this->library_address;
    }

    /**
     * Get the [library_postal_code] column value.
     *
     * @return string|null
     */
    public function getPostalCode()
    {
        return $this->library_postal_code;
    }

    /**
     * Get the [library_city] column value.
     *
     * @return string|null
     */
    public function getCity()
    {
        return $this->library_city;
    }

    /**
     * Get the [library_country] column value.
     *
     * @return string|null
     */
    public function getCountry()
    {
        return $this->library_country;
    }

    /**
     * Get the [library_phone] column value.
     *
     * @return string|null
     */
    public function getPhone()
    {
        return $this->library_phone;
    }

    /**
     * Get the [library_fax] column value.
     *
     * @return string|null
     */
    public function getFax()
    {
        return $this->library_fax;
    }

    /**
     * Get the [library_website] column value.
     *
     * @return string|null
     */
    public function getWebsite()
    {
        return $this->library_website;
    }

    /**
     * Get the [library_email] column value.
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->library_email;
    }

    /**
     * Get the [library_facebook] column value.
     *
     * @return string|null
     */
    public function getFacebook()
    {
        return $this->library_facebook;
    }

    /**
     * Get the [library_twitter] column value.
     *
     * @return string|null
     */
    public function getTwitter()
    {
        return $this->library_twitter;
    }

    /**
     * Get the [library_creation_year] column value.
     *
     * @return string|null
     */
    public function getCreationYear()
    {
        return $this->library_creation_year;
    }

    /**
     * Get the [library_specialities] column value.
     *
     * @return string|null
     */
    public function getSpecialities()
    {
        return $this->library_specialities;
    }

    /**
     * Get the [library_readings] column value.
     *
     * @return string|null
     */
    public function getReadings()
    {
        return $this->library_readings;
    }

    /**
     * Get the [library_desc] column value.
     *
     * @return string|null
     */
    public function getDesc()
    {
        return $this->library_desc;
    }

    /**
     * Get the [optionally formatted] temporal [library_created] column value.
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
            return $this->library_created;
        } else {
            return $this->library_created instanceof \DateTimeInterface ? $this->library_created->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [library_updated] column value.
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
            return $this->library_updated;
        } else {
            return $this->library_updated instanceof \DateTimeInterface ? $this->library_updated->format($format) : null;
        }
    }

    /**
     * Set the value of [library_id] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->library_id !== $v) {
            $this->library_id = $v;
            $this->modifiedColumns[LibraryTableMap::COL_LIBRARY_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [library_name] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->library_name !== $v) {
            $this->library_name = $v;
            $this->modifiedColumns[LibraryTableMap::COL_LIBRARY_NAME] = true;
        }

        return $this;
    }

    /**
     * Set the value of [library_name_alphabetic] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setNameAlphabetic($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->library_name_alphabetic !== $v) {
            $this->library_name_alphabetic = $v;
            $this->modifiedColumns[LibraryTableMap::COL_LIBRARY_NAME_ALPHABETIC] = true;
        }

        return $this;
    }

    /**
     * Set the value of [library_url] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->library_url !== $v) {
            $this->library_url = $v;
            $this->modifiedColumns[LibraryTableMap::COL_LIBRARY_URL] = true;
        }

        return $this;
    }

    /**
     * Set the value of [library_representative] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setRepresentative($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->library_representative !== $v) {
            $this->library_representative = $v;
            $this->modifiedColumns[LibraryTableMap::COL_LIBRARY_REPRESENTATIVE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [library_address] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setAddress($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->library_address !== $v) {
            $this->library_address = $v;
            $this->modifiedColumns[LibraryTableMap::COL_LIBRARY_ADDRESS] = true;
        }

        return $this;
    }

    /**
     * Set the value of [library_postal_code] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPostalCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->library_postal_code !== $v) {
            $this->library_postal_code = $v;
            $this->modifiedColumns[LibraryTableMap::COL_LIBRARY_POSTAL_CODE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [library_city] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setCity($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->library_city !== $v) {
            $this->library_city = $v;
            $this->modifiedColumns[LibraryTableMap::COL_LIBRARY_CITY] = true;
        }

        return $this;
    }

    /**
     * Set the value of [library_country] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setCountry($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->library_country !== $v) {
            $this->library_country = $v;
            $this->modifiedColumns[LibraryTableMap::COL_LIBRARY_COUNTRY] = true;
        }

        return $this;
    }

    /**
     * Set the value of [library_phone] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPhone($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->library_phone !== $v) {
            $this->library_phone = $v;
            $this->modifiedColumns[LibraryTableMap::COL_LIBRARY_PHONE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [library_fax] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setFax($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->library_fax !== $v) {
            $this->library_fax = $v;
            $this->modifiedColumns[LibraryTableMap::COL_LIBRARY_FAX] = true;
        }

        return $this;
    }

    /**
     * Set the value of [library_website] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setWebsite($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->library_website !== $v) {
            $this->library_website = $v;
            $this->modifiedColumns[LibraryTableMap::COL_LIBRARY_WEBSITE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [library_email] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->library_email !== $v) {
            $this->library_email = $v;
            $this->modifiedColumns[LibraryTableMap::COL_LIBRARY_EMAIL] = true;
        }

        return $this;
    }

    /**
     * Set the value of [library_facebook] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setFacebook($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->library_facebook !== $v) {
            $this->library_facebook = $v;
            $this->modifiedColumns[LibraryTableMap::COL_LIBRARY_FACEBOOK] = true;
        }

        return $this;
    }

    /**
     * Set the value of [library_twitter] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setTwitter($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->library_twitter !== $v) {
            $this->library_twitter = $v;
            $this->modifiedColumns[LibraryTableMap::COL_LIBRARY_TWITTER] = true;
        }

        return $this;
    }

    /**
     * Set the value of [library_creation_year] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setCreationYear($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->library_creation_year !== $v) {
            $this->library_creation_year = $v;
            $this->modifiedColumns[LibraryTableMap::COL_LIBRARY_CREATION_YEAR] = true;
        }

        return $this;
    }

    /**
     * Set the value of [library_specialities] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setSpecialities($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->library_specialities !== $v) {
            $this->library_specialities = $v;
            $this->modifiedColumns[LibraryTableMap::COL_LIBRARY_SPECIALITIES] = true;
        }

        return $this;
    }

    /**
     * Set the value of [library_readings] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setReadings($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->library_readings !== $v) {
            $this->library_readings = $v;
            $this->modifiedColumns[LibraryTableMap::COL_LIBRARY_READINGS] = true;
        }

        return $this;
    }

    /**
     * Set the value of [library_desc] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setDesc($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->library_desc !== $v) {
            $this->library_desc = $v;
            $this->modifiedColumns[LibraryTableMap::COL_LIBRARY_DESC] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [library_created] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->library_created !== null || $dt !== null) {
            if ($this->library_created === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->library_created->format("Y-m-d H:i:s.u")) {
                $this->library_created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[LibraryTableMap::COL_LIBRARY_CREATED] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [library_updated] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->library_updated !== null || $dt !== null) {
            if ($this->library_updated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->library_updated->format("Y-m-d H:i:s.u")) {
                $this->library_updated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[LibraryTableMap::COL_LIBRARY_UPDATED] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : LibraryTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->library_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : LibraryTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->library_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : LibraryTableMap::translateFieldName('NameAlphabetic', TableMap::TYPE_PHPNAME, $indexType)];
            $this->library_name_alphabetic = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : LibraryTableMap::translateFieldName('Url', TableMap::TYPE_PHPNAME, $indexType)];
            $this->library_url = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : LibraryTableMap::translateFieldName('Representative', TableMap::TYPE_PHPNAME, $indexType)];
            $this->library_representative = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : LibraryTableMap::translateFieldName('Address', TableMap::TYPE_PHPNAME, $indexType)];
            $this->library_address = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : LibraryTableMap::translateFieldName('PostalCode', TableMap::TYPE_PHPNAME, $indexType)];
            $this->library_postal_code = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : LibraryTableMap::translateFieldName('City', TableMap::TYPE_PHPNAME, $indexType)];
            $this->library_city = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : LibraryTableMap::translateFieldName('Country', TableMap::TYPE_PHPNAME, $indexType)];
            $this->library_country = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : LibraryTableMap::translateFieldName('Phone', TableMap::TYPE_PHPNAME, $indexType)];
            $this->library_phone = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : LibraryTableMap::translateFieldName('Fax', TableMap::TYPE_PHPNAME, $indexType)];
            $this->library_fax = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : LibraryTableMap::translateFieldName('Website', TableMap::TYPE_PHPNAME, $indexType)];
            $this->library_website = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : LibraryTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->library_email = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : LibraryTableMap::translateFieldName('Facebook', TableMap::TYPE_PHPNAME, $indexType)];
            $this->library_facebook = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : LibraryTableMap::translateFieldName('Twitter', TableMap::TYPE_PHPNAME, $indexType)];
            $this->library_twitter = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : LibraryTableMap::translateFieldName('CreationYear', TableMap::TYPE_PHPNAME, $indexType)];
            $this->library_creation_year = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : LibraryTableMap::translateFieldName('Specialities', TableMap::TYPE_PHPNAME, $indexType)];
            $this->library_specialities = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 17 + $startcol : LibraryTableMap::translateFieldName('Readings', TableMap::TYPE_PHPNAME, $indexType)];
            $this->library_readings = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 18 + $startcol : LibraryTableMap::translateFieldName('Desc', TableMap::TYPE_PHPNAME, $indexType)];
            $this->library_desc = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 19 + $startcol : LibraryTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->library_created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 20 + $startcol : LibraryTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->library_updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 21; // 21 = LibraryTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\Library'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(LibraryTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildLibraryQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
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
     * @see Library::setDeleted()
     * @see Library::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(LibraryTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildLibraryQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(LibraryTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(LibraryTableMap::COL_LIBRARY_CREATED)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(LibraryTableMap::COL_LIBRARY_UPDATED)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(LibraryTableMap::COL_LIBRARY_UPDATED)) {
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
                LibraryTableMap::addInstanceToPool($this);
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

        $this->modifiedColumns[LibraryTableMap::COL_LIBRARY_ID] = true;
        if (null !== $this->library_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . LibraryTableMap::COL_LIBRARY_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_ID)) {
            $modifiedColumns[':p' . $index++]  = 'library_id';
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'library_name';
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_NAME_ALPHABETIC)) {
            $modifiedColumns[':p' . $index++]  = 'library_name_alphabetic';
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_URL)) {
            $modifiedColumns[':p' . $index++]  = 'library_url';
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_REPRESENTATIVE)) {
            $modifiedColumns[':p' . $index++]  = 'library_representative';
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_ADDRESS)) {
            $modifiedColumns[':p' . $index++]  = 'library_address';
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_POSTAL_CODE)) {
            $modifiedColumns[':p' . $index++]  = 'library_postal_code';
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_CITY)) {
            $modifiedColumns[':p' . $index++]  = 'library_city';
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_COUNTRY)) {
            $modifiedColumns[':p' . $index++]  = 'library_country';
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_PHONE)) {
            $modifiedColumns[':p' . $index++]  = 'library_phone';
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_FAX)) {
            $modifiedColumns[':p' . $index++]  = 'library_fax';
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_WEBSITE)) {
            $modifiedColumns[':p' . $index++]  = 'library_website';
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'library_email';
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_FACEBOOK)) {
            $modifiedColumns[':p' . $index++]  = 'library_facebook';
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_TWITTER)) {
            $modifiedColumns[':p' . $index++]  = 'library_twitter';
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_CREATION_YEAR)) {
            $modifiedColumns[':p' . $index++]  = 'library_creation_year';
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_SPECIALITIES)) {
            $modifiedColumns[':p' . $index++]  = 'library_specialities';
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_READINGS)) {
            $modifiedColumns[':p' . $index++]  = 'library_readings';
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_DESC)) {
            $modifiedColumns[':p' . $index++]  = 'library_desc';
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'library_created';
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = 'library_updated';
        }

        $sql = sprintf(
            'INSERT INTO libraries (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'library_id':
                        $stmt->bindValue($identifier, $this->library_id, PDO::PARAM_INT);

                        break;
                    case 'library_name':
                        $stmt->bindValue($identifier, $this->library_name, PDO::PARAM_STR);

                        break;
                    case 'library_name_alphabetic':
                        $stmt->bindValue($identifier, $this->library_name_alphabetic, PDO::PARAM_STR);

                        break;
                    case 'library_url':
                        $stmt->bindValue($identifier, $this->library_url, PDO::PARAM_STR);

                        break;
                    case 'library_representative':
                        $stmt->bindValue($identifier, $this->library_representative, PDO::PARAM_STR);

                        break;
                    case 'library_address':
                        $stmt->bindValue($identifier, $this->library_address, PDO::PARAM_STR);

                        break;
                    case 'library_postal_code':
                        $stmt->bindValue($identifier, $this->library_postal_code, PDO::PARAM_STR);

                        break;
                    case 'library_city':
                        $stmt->bindValue($identifier, $this->library_city, PDO::PARAM_STR);

                        break;
                    case 'library_country':
                        $stmt->bindValue($identifier, $this->library_country, PDO::PARAM_STR);

                        break;
                    case 'library_phone':
                        $stmt->bindValue($identifier, $this->library_phone, PDO::PARAM_STR);

                        break;
                    case 'library_fax':
                        $stmt->bindValue($identifier, $this->library_fax, PDO::PARAM_STR);

                        break;
                    case 'library_website':
                        $stmt->bindValue($identifier, $this->library_website, PDO::PARAM_STR);

                        break;
                    case 'library_email':
                        $stmt->bindValue($identifier, $this->library_email, PDO::PARAM_STR);

                        break;
                    case 'library_facebook':
                        $stmt->bindValue($identifier, $this->library_facebook, PDO::PARAM_STR);

                        break;
                    case 'library_twitter':
                        $stmt->bindValue($identifier, $this->library_twitter, PDO::PARAM_STR);

                        break;
                    case 'library_creation_year':
                        $stmt->bindValue($identifier, $this->library_creation_year, PDO::PARAM_STR);

                        break;
                    case 'library_specialities':
                        $stmt->bindValue($identifier, $this->library_specialities, PDO::PARAM_STR);

                        break;
                    case 'library_readings':
                        $stmt->bindValue($identifier, $this->library_readings, PDO::PARAM_STR);

                        break;
                    case 'library_desc':
                        $stmt->bindValue($identifier, $this->library_desc, PDO::PARAM_STR);

                        break;
                    case 'library_created':
                        $stmt->bindValue($identifier, $this->library_created ? $this->library_created->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'library_updated':
                        $stmt->bindValue($identifier, $this->library_updated ? $this->library_updated->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

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
        $pos = LibraryTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getCreationYear();

            case 16:
                return $this->getSpecialities();

            case 17:
                return $this->getReadings();

            case 18:
                return $this->getDesc();

            case 19:
                return $this->getCreatedAt();

            case 20:
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
        if (isset($alreadyDumpedObjects['Library'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['Library'][$this->hashCode()] = true;
        $keys = LibraryTableMap::getFieldNames($keyType);
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
            $keys[15] => $this->getCreationYear(),
            $keys[16] => $this->getSpecialities(),
            $keys[17] => $this->getReadings(),
            $keys[18] => $this->getDesc(),
            $keys[19] => $this->getCreatedAt(),
            $keys[20] => $this->getUpdatedAt(),
        ];
        if ($result[$keys[19]] instanceof \DateTimeInterface) {
            $result[$keys[19]] = $result[$keys[19]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[20]] instanceof \DateTimeInterface) {
            $result[$keys[20]] = $result[$keys[20]]->format('Y-m-d H:i:s.u');
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
        $pos = LibraryTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setCreationYear($value);
                break;
            case 16:
                $this->setSpecialities($value);
                break;
            case 17:
                $this->setReadings($value);
                break;
            case 18:
                $this->setDesc($value);
                break;
            case 19:
                $this->setCreatedAt($value);
                break;
            case 20:
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
        $keys = LibraryTableMap::getFieldNames($keyType);

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
            $this->setCreationYear($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setSpecialities($arr[$keys[16]]);
        }
        if (array_key_exists($keys[17], $arr)) {
            $this->setReadings($arr[$keys[17]]);
        }
        if (array_key_exists($keys[18], $arr)) {
            $this->setDesc($arr[$keys[18]]);
        }
        if (array_key_exists($keys[19], $arr)) {
            $this->setCreatedAt($arr[$keys[19]]);
        }
        if (array_key_exists($keys[20], $arr)) {
            $this->setUpdatedAt($arr[$keys[20]]);
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
        $criteria = new Criteria(LibraryTableMap::DATABASE_NAME);

        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_ID)) {
            $criteria->add(LibraryTableMap::COL_LIBRARY_ID, $this->library_id);
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_NAME)) {
            $criteria->add(LibraryTableMap::COL_LIBRARY_NAME, $this->library_name);
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_NAME_ALPHABETIC)) {
            $criteria->add(LibraryTableMap::COL_LIBRARY_NAME_ALPHABETIC, $this->library_name_alphabetic);
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_URL)) {
            $criteria->add(LibraryTableMap::COL_LIBRARY_URL, $this->library_url);
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_REPRESENTATIVE)) {
            $criteria->add(LibraryTableMap::COL_LIBRARY_REPRESENTATIVE, $this->library_representative);
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_ADDRESS)) {
            $criteria->add(LibraryTableMap::COL_LIBRARY_ADDRESS, $this->library_address);
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_POSTAL_CODE)) {
            $criteria->add(LibraryTableMap::COL_LIBRARY_POSTAL_CODE, $this->library_postal_code);
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_CITY)) {
            $criteria->add(LibraryTableMap::COL_LIBRARY_CITY, $this->library_city);
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_COUNTRY)) {
            $criteria->add(LibraryTableMap::COL_LIBRARY_COUNTRY, $this->library_country);
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_PHONE)) {
            $criteria->add(LibraryTableMap::COL_LIBRARY_PHONE, $this->library_phone);
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_FAX)) {
            $criteria->add(LibraryTableMap::COL_LIBRARY_FAX, $this->library_fax);
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_WEBSITE)) {
            $criteria->add(LibraryTableMap::COL_LIBRARY_WEBSITE, $this->library_website);
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_EMAIL)) {
            $criteria->add(LibraryTableMap::COL_LIBRARY_EMAIL, $this->library_email);
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_FACEBOOK)) {
            $criteria->add(LibraryTableMap::COL_LIBRARY_FACEBOOK, $this->library_facebook);
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_TWITTER)) {
            $criteria->add(LibraryTableMap::COL_LIBRARY_TWITTER, $this->library_twitter);
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_CREATION_YEAR)) {
            $criteria->add(LibraryTableMap::COL_LIBRARY_CREATION_YEAR, $this->library_creation_year);
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_SPECIALITIES)) {
            $criteria->add(LibraryTableMap::COL_LIBRARY_SPECIALITIES, $this->library_specialities);
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_READINGS)) {
            $criteria->add(LibraryTableMap::COL_LIBRARY_READINGS, $this->library_readings);
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_DESC)) {
            $criteria->add(LibraryTableMap::COL_LIBRARY_DESC, $this->library_desc);
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_CREATED)) {
            $criteria->add(LibraryTableMap::COL_LIBRARY_CREATED, $this->library_created);
        }
        if ($this->isColumnModified(LibraryTableMap::COL_LIBRARY_UPDATED)) {
            $criteria->add(LibraryTableMap::COL_LIBRARY_UPDATED, $this->library_updated);
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
        $criteria = ChildLibraryQuery::create();
        $criteria->add(LibraryTableMap::COL_LIBRARY_ID, $this->library_id);

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
     * Generic method to set the primary key (library_id column).
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
     * @param object $copyObj An object of \Model\Library (or compatible) type.
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
        $copyObj->setCreationYear($this->getCreationYear());
        $copyObj->setSpecialities($this->getSpecialities());
        $copyObj->setReadings($this->getReadings());
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
     * @return \Model\Library Clone of current object.
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
        $this->library_id = null;
        $this->library_name = null;
        $this->library_name_alphabetic = null;
        $this->library_url = null;
        $this->library_representative = null;
        $this->library_address = null;
        $this->library_postal_code = null;
        $this->library_city = null;
        $this->library_country = null;
        $this->library_phone = null;
        $this->library_fax = null;
        $this->library_website = null;
        $this->library_email = null;
        $this->library_facebook = null;
        $this->library_twitter = null;
        $this->library_creation_year = null;
        $this->library_specialities = null;
        $this->library_readings = null;
        $this->library_desc = null;
        $this->library_created = null;
        $this->library_updated = null;
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
        return (string) $this->exportTo(LibraryTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return $this The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[LibraryTableMap::COL_LIBRARY_UPDATED] = true;

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
