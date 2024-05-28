<?php

namespace Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Model\Image as ChildImage;
use Model\ImageQuery as ChildImageQuery;
use Model\People as ChildPeople;
use Model\PeopleQuery as ChildPeopleQuery;
use Model\Role as ChildRole;
use Model\RoleQuery as ChildRoleQuery;
use Model\Map\ImageTableMap;
use Model\Map\PeopleTableMap;
use Model\Map\RoleTableMap;
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
 * Base class that represents a row from the 'people' table.
 *
 * Intervenants
 *
 * @package    propel.generator.Model.Base
 */
abstract class People implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\Model\\Map\\PeopleTableMap';


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
     * The value for the people_id field.
     *
     * @var        int
     */
    protected $people_id;

    /**
     * The value for the people_first_name field.
     *
     * @var        string|null
     */
    protected $people_first_name;

    /**
     * The value for the people_last_name field.
     *
     * @var        string|null
     */
    protected $people_last_name;

    /**
     * The value for the people_name field.
     *
     * @var        string|null
     */
    protected $people_name;

    /**
     * The value for the people_alpha field.
     *
     * @var        string|null
     */
    protected $people_alpha;

    /**
     * The value for the people_url_old field.
     *
     * @var        string|null
     */
    protected $people_url_old;

    /**
     * The value for the people_url field.
     *
     * @var        string|null
     */
    protected $people_url;

    /**
     * The value for the people_pseudo field.
     *
     * @var        int|null
     */
    protected $people_pseudo;

    /**
     * The value for the people_noosfere_id field.
     *
     * @var        int|null
     */
    protected $people_noosfere_id;

    /**
     * The value for the people_birth field.
     *
     * @var        int|null
     */
    protected $people_birth;

    /**
     * The value for the people_death field.
     *
     * @var        int|null
     */
    protected $people_death;

    /**
     * The value for the people_gender field.
     *
     * @var        string|null
     */
    protected $people_gender;

    /**
     * The value for the people_nation field.
     *
     * @var        string|null
     */
    protected $people_nation;

    /**
     * The value for the people_bio field.
     *
     * @var        string|null
     */
    protected $people_bio;

    /**
     * The value for the people_site field.
     *
     * @var        string|null
     */
    protected $people_site;

    /**
     * The value for the people_facebook field.
     *
     * @var        string|null
     */
    protected $people_facebook;

    /**
     * The value for the people_twitter field.
     *
     * @var        string|null
     */
    protected $people_twitter;

    /**
     * The value for the people_hits field.
     *
     * @var        int|null
     */
    protected $people_hits;

    /**
     * The value for the people_date field.
     *
     * @var        DateTime|null
     */
    protected $people_date;

    /**
     * The value for the people_insert field.
     *
     * @var        DateTime|null
     */
    protected $people_insert;

    /**
     * The value for the people_update field.
     *
     * @var        DateTime|null
     */
    protected $people_update;

    /**
     * The value for the people_created field.
     *
     * @var        DateTime|null
     */
    protected $people_created;

    /**
     * The value for the people_updated field.
     *
     * @var        DateTime|null
     */
    protected $people_updated;

    /**
     * @var        ObjectCollection|ChildImage[] Collection to store aggregation of ChildImage objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildImage> Collection to store aggregation of ChildImage objects.
     */
    protected $collImages;
    protected $collImagesPartial;

    /**
     * @var        ObjectCollection|ChildRole[] Collection to store aggregation of ChildRole objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildRole> Collection to store aggregation of ChildRole objects.
     */
    protected $collRoles;
    protected $collRolesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var bool
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildImage[]
     * @phpstan-var ObjectCollection&\Traversable<ChildImage>
     */
    protected $imagesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildRole[]
     * @phpstan-var ObjectCollection&\Traversable<ChildRole>
     */
    protected $rolesScheduledForDeletion = null;

    /**
     * Initializes internal state of Model\Base\People object.
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
     * Compares this with another <code>People</code> instance.  If
     * <code>obj</code> is an instance of <code>People</code>, delegates to
     * <code>equals(People)</code>.  Otherwise, returns <code>false</code>.
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
     * Get the [people_id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->people_id;
    }

    /**
     * Get the [people_first_name] column value.
     *
     * @return string|null
     */
    public function getFirstName()
    {
        return $this->people_first_name;
    }

    /**
     * Get the [people_last_name] column value.
     *
     * @return string|null
     */
    public function getLastName()
    {
        return $this->people_last_name;
    }

    /**
     * Get the [people_name] column value.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->people_name;
    }

    /**
     * Get the [people_alpha] column value.
     *
     * @return string|null
     */
    public function getAlpha()
    {
        return $this->people_alpha;
    }

    /**
     * Get the [people_url_old] column value.
     *
     * @return string|null
     */
    public function getUrlOld()
    {
        return $this->people_url_old;
    }

    /**
     * Get the [people_url] column value.
     *
     * @return string|null
     */
    public function getUrl()
    {
        return $this->people_url;
    }

    /**
     * Get the [people_pseudo] column value.
     *
     * @return int|null
     */
    public function getPseudo()
    {
        return $this->people_pseudo;
    }

    /**
     * Get the [people_noosfere_id] column value.
     *
     * @return int|null
     */
    public function getNoosfereId()
    {
        return $this->people_noosfere_id;
    }

    /**
     * Get the [people_birth] column value.
     *
     * @return int|null
     */
    public function getBirth()
    {
        return $this->people_birth;
    }

    /**
     * Get the [people_death] column value.
     *
     * @return int|null
     */
    public function getDeath()
    {
        return $this->people_death;
    }

    /**
     * Get the [people_gender] column value.
     *
     * @return string|null
     */
    public function getGender()
    {
        return $this->people_gender;
    }

    /**
     * Get the [people_nation] column value.
     *
     * @return string|null
     */
    public function getNation()
    {
        return $this->people_nation;
    }

    /**
     * Get the [people_bio] column value.
     *
     * @return string|null
     */
    public function getBio()
    {
        return $this->people_bio;
    }

    /**
     * Get the [people_site] column value.
     *
     * @return string|null
     */
    public function getSite()
    {
        return $this->people_site;
    }

    /**
     * Get the [people_facebook] column value.
     *
     * @return string|null
     */
    public function getFacebook()
    {
        return $this->people_facebook;
    }

    /**
     * Get the [people_twitter] column value.
     *
     * @return string|null
     */
    public function getTwitter()
    {
        return $this->people_twitter;
    }

    /**
     * Get the [people_hits] column value.
     *
     * @return int|null
     */
    public function getHits()
    {
        return $this->people_hits;
    }

    /**
     * Get the [optionally formatted] temporal [people_date] column value.
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
    public function getDate($format = null)
    {
        if ($format === null) {
            return $this->people_date;
        } else {
            return $this->people_date instanceof \DateTimeInterface ? $this->people_date->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [people_insert] column value.
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
            return $this->people_insert;
        } else {
            return $this->people_insert instanceof \DateTimeInterface ? $this->people_insert->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [people_update] column value.
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
            return $this->people_update;
        } else {
            return $this->people_update instanceof \DateTimeInterface ? $this->people_update->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [people_created] column value.
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
            return $this->people_created;
        } else {
            return $this->people_created instanceof \DateTimeInterface ? $this->people_created->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [people_updated] column value.
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
            return $this->people_updated;
        } else {
            return $this->people_updated instanceof \DateTimeInterface ? $this->people_updated->format($format) : null;
        }
    }

    /**
     * Set the value of [people_id] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->people_id !== $v) {
            $this->people_id = $v;
            $this->modifiedColumns[PeopleTableMap::COL_PEOPLE_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [people_first_name] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setFirstName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->people_first_name !== $v) {
            $this->people_first_name = $v;
            $this->modifiedColumns[PeopleTableMap::COL_PEOPLE_FIRST_NAME] = true;
        }

        return $this;
    }

    /**
     * Set the value of [people_last_name] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setLastName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->people_last_name !== $v) {
            $this->people_last_name = $v;
            $this->modifiedColumns[PeopleTableMap::COL_PEOPLE_LAST_NAME] = true;
        }

        return $this;
    }

    /**
     * Set the value of [people_name] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->people_name !== $v) {
            $this->people_name = $v;
            $this->modifiedColumns[PeopleTableMap::COL_PEOPLE_NAME] = true;
        }

        return $this;
    }

    /**
     * Set the value of [people_alpha] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setAlpha($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->people_alpha !== $v) {
            $this->people_alpha = $v;
            $this->modifiedColumns[PeopleTableMap::COL_PEOPLE_ALPHA] = true;
        }

        return $this;
    }

    /**
     * Set the value of [people_url_old] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setUrlOld($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->people_url_old !== $v) {
            $this->people_url_old = $v;
            $this->modifiedColumns[PeopleTableMap::COL_PEOPLE_URL_OLD] = true;
        }

        return $this;
    }

    /**
     * Set the value of [people_url] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->people_url !== $v) {
            $this->people_url = $v;
            $this->modifiedColumns[PeopleTableMap::COL_PEOPLE_URL] = true;
        }

        return $this;
    }

    /**
     * Set the value of [people_pseudo] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPseudo($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->people_pseudo !== $v) {
            $this->people_pseudo = $v;
            $this->modifiedColumns[PeopleTableMap::COL_PEOPLE_PSEUDO] = true;
        }

        return $this;
    }

    /**
     * Set the value of [people_noosfere_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setNoosfereId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->people_noosfere_id !== $v) {
            $this->people_noosfere_id = $v;
            $this->modifiedColumns[PeopleTableMap::COL_PEOPLE_NOOSFERE_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [people_birth] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setBirth($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->people_birth !== $v) {
            $this->people_birth = $v;
            $this->modifiedColumns[PeopleTableMap::COL_PEOPLE_BIRTH] = true;
        }

        return $this;
    }

    /**
     * Set the value of [people_death] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setDeath($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->people_death !== $v) {
            $this->people_death = $v;
            $this->modifiedColumns[PeopleTableMap::COL_PEOPLE_DEATH] = true;
        }

        return $this;
    }

    /**
     * Set the value of [people_gender] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setGender($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->people_gender !== $v) {
            $this->people_gender = $v;
            $this->modifiedColumns[PeopleTableMap::COL_PEOPLE_GENDER] = true;
        }

        return $this;
    }

    /**
     * Set the value of [people_nation] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setNation($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->people_nation !== $v) {
            $this->people_nation = $v;
            $this->modifiedColumns[PeopleTableMap::COL_PEOPLE_NATION] = true;
        }

        return $this;
    }

    /**
     * Set the value of [people_bio] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setBio($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->people_bio !== $v) {
            $this->people_bio = $v;
            $this->modifiedColumns[PeopleTableMap::COL_PEOPLE_BIO] = true;
        }

        return $this;
    }

    /**
     * Set the value of [people_site] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setSite($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->people_site !== $v) {
            $this->people_site = $v;
            $this->modifiedColumns[PeopleTableMap::COL_PEOPLE_SITE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [people_facebook] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setFacebook($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->people_facebook !== $v) {
            $this->people_facebook = $v;
            $this->modifiedColumns[PeopleTableMap::COL_PEOPLE_FACEBOOK] = true;
        }

        return $this;
    }

    /**
     * Set the value of [people_twitter] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setTwitter($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->people_twitter !== $v) {
            $this->people_twitter = $v;
            $this->modifiedColumns[PeopleTableMap::COL_PEOPLE_TWITTER] = true;
        }

        return $this;
    }

    /**
     * Set the value of [people_hits] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setHits($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->people_hits !== $v) {
            $this->people_hits = $v;
            $this->modifiedColumns[PeopleTableMap::COL_PEOPLE_HITS] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [people_date] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->people_date !== null || $dt !== null) {
            if ($this->people_date === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->people_date->format("Y-m-d H:i:s.u")) {
                $this->people_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PeopleTableMap::COL_PEOPLE_DATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [people_insert] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setInsert($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->people_insert !== null || $dt !== null) {
            if ($this->people_insert === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->people_insert->format("Y-m-d H:i:s.u")) {
                $this->people_insert = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PeopleTableMap::COL_PEOPLE_INSERT] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [people_update] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->people_update !== null || $dt !== null) {
            if ($this->people_update === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->people_update->format("Y-m-d H:i:s.u")) {
                $this->people_update = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PeopleTableMap::COL_PEOPLE_UPDATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [people_created] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->people_created !== null || $dt !== null) {
            if ($this->people_created === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->people_created->format("Y-m-d H:i:s.u")) {
                $this->people_created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PeopleTableMap::COL_PEOPLE_CREATED] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [people_updated] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->people_updated !== null || $dt !== null) {
            if ($this->people_updated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->people_updated->format("Y-m-d H:i:s.u")) {
                $this->people_updated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[PeopleTableMap::COL_PEOPLE_UPDATED] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : PeopleTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->people_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : PeopleTableMap::translateFieldName('FirstName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->people_first_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : PeopleTableMap::translateFieldName('LastName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->people_last_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : PeopleTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->people_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : PeopleTableMap::translateFieldName('Alpha', TableMap::TYPE_PHPNAME, $indexType)];
            $this->people_alpha = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : PeopleTableMap::translateFieldName('UrlOld', TableMap::TYPE_PHPNAME, $indexType)];
            $this->people_url_old = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : PeopleTableMap::translateFieldName('Url', TableMap::TYPE_PHPNAME, $indexType)];
            $this->people_url = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : PeopleTableMap::translateFieldName('Pseudo', TableMap::TYPE_PHPNAME, $indexType)];
            $this->people_pseudo = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : PeopleTableMap::translateFieldName('NoosfereId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->people_noosfere_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : PeopleTableMap::translateFieldName('Birth', TableMap::TYPE_PHPNAME, $indexType)];
            $this->people_birth = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : PeopleTableMap::translateFieldName('Death', TableMap::TYPE_PHPNAME, $indexType)];
            $this->people_death = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : PeopleTableMap::translateFieldName('Gender', TableMap::TYPE_PHPNAME, $indexType)];
            $this->people_gender = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : PeopleTableMap::translateFieldName('Nation', TableMap::TYPE_PHPNAME, $indexType)];
            $this->people_nation = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : PeopleTableMap::translateFieldName('Bio', TableMap::TYPE_PHPNAME, $indexType)];
            $this->people_bio = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : PeopleTableMap::translateFieldName('Site', TableMap::TYPE_PHPNAME, $indexType)];
            $this->people_site = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : PeopleTableMap::translateFieldName('Facebook', TableMap::TYPE_PHPNAME, $indexType)];
            $this->people_facebook = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : PeopleTableMap::translateFieldName('Twitter', TableMap::TYPE_PHPNAME, $indexType)];
            $this->people_twitter = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 17 + $startcol : PeopleTableMap::translateFieldName('Hits', TableMap::TYPE_PHPNAME, $indexType)];
            $this->people_hits = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 18 + $startcol : PeopleTableMap::translateFieldName('Date', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->people_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 19 + $startcol : PeopleTableMap::translateFieldName('Insert', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->people_insert = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 20 + $startcol : PeopleTableMap::translateFieldName('Update', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->people_update = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 21 + $startcol : PeopleTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->people_created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 22 + $startcol : PeopleTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->people_updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 23; // 23 = PeopleTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\People'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(PeopleTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildPeopleQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collImages = null;

            $this->collRoles = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param ConnectionInterface $con
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     * @see People::setDeleted()
     * @see People::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(PeopleTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildPeopleQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(PeopleTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(PeopleTableMap::COL_PEOPLE_CREATED)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(PeopleTableMap::COL_PEOPLE_UPDATED)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PeopleTableMap::COL_PEOPLE_UPDATED)) {
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
                PeopleTableMap::addInstanceToPool($this);
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

        $this->modifiedColumns[PeopleTableMap::COL_PEOPLE_ID] = true;
        if (null !== $this->people_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PeopleTableMap::COL_PEOPLE_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'people_id';
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_FIRST_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'people_first_name';
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_LAST_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'people_last_name';
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'people_name';
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_ALPHA)) {
            $modifiedColumns[':p' . $index++]  = 'people_alpha';
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_URL_OLD)) {
            $modifiedColumns[':p' . $index++]  = 'people_url_old';
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_URL)) {
            $modifiedColumns[':p' . $index++]  = 'people_url';
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_PSEUDO)) {
            $modifiedColumns[':p' . $index++]  = 'people_pseudo';
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_NOOSFERE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'people_noosfere_id';
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_BIRTH)) {
            $modifiedColumns[':p' . $index++]  = 'people_birth';
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_DEATH)) {
            $modifiedColumns[':p' . $index++]  = 'people_death';
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_GENDER)) {
            $modifiedColumns[':p' . $index++]  = 'people_gender';
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_NATION)) {
            $modifiedColumns[':p' . $index++]  = 'people_nation';
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_BIO)) {
            $modifiedColumns[':p' . $index++]  = 'people_bio';
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_SITE)) {
            $modifiedColumns[':p' . $index++]  = 'people_site';
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_FACEBOOK)) {
            $modifiedColumns[':p' . $index++]  = 'people_facebook';
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_TWITTER)) {
            $modifiedColumns[':p' . $index++]  = 'people_twitter';
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_HITS)) {
            $modifiedColumns[':p' . $index++]  = 'people_hits';
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'people_date';
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_INSERT)) {
            $modifiedColumns[':p' . $index++]  = 'people_insert';
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_UPDATE)) {
            $modifiedColumns[':p' . $index++]  = 'people_update';
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'people_created';
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = 'people_updated';
        }

        $sql = sprintf(
            'INSERT INTO people (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'people_id':
                        $stmt->bindValue($identifier, $this->people_id, PDO::PARAM_INT);

                        break;
                    case 'people_first_name':
                        $stmt->bindValue($identifier, $this->people_first_name, PDO::PARAM_STR);

                        break;
                    case 'people_last_name':
                        $stmt->bindValue($identifier, $this->people_last_name, PDO::PARAM_STR);

                        break;
                    case 'people_name':
                        $stmt->bindValue($identifier, $this->people_name, PDO::PARAM_STR);

                        break;
                    case 'people_alpha':
                        $stmt->bindValue($identifier, $this->people_alpha, PDO::PARAM_STR);

                        break;
                    case 'people_url_old':
                        $stmt->bindValue($identifier, $this->people_url_old, PDO::PARAM_STR);

                        break;
                    case 'people_url':
                        $stmt->bindValue($identifier, $this->people_url, PDO::PARAM_STR);

                        break;
                    case 'people_pseudo':
                        $stmt->bindValue($identifier, $this->people_pseudo, PDO::PARAM_INT);

                        break;
                    case 'people_noosfere_id':
                        $stmt->bindValue($identifier, $this->people_noosfere_id, PDO::PARAM_INT);

                        break;
                    case 'people_birth':
                        $stmt->bindValue($identifier, $this->people_birth, PDO::PARAM_INT);

                        break;
                    case 'people_death':
                        $stmt->bindValue($identifier, $this->people_death, PDO::PARAM_INT);

                        break;
                    case 'people_gender':
                        $stmt->bindValue($identifier, $this->people_gender, PDO::PARAM_STR);

                        break;
                    case 'people_nation':
                        $stmt->bindValue($identifier, $this->people_nation, PDO::PARAM_STR);

                        break;
                    case 'people_bio':
                        $stmt->bindValue($identifier, $this->people_bio, PDO::PARAM_STR);

                        break;
                    case 'people_site':
                        $stmt->bindValue($identifier, $this->people_site, PDO::PARAM_STR);

                        break;
                    case 'people_facebook':
                        $stmt->bindValue($identifier, $this->people_facebook, PDO::PARAM_STR);

                        break;
                    case 'people_twitter':
                        $stmt->bindValue($identifier, $this->people_twitter, PDO::PARAM_STR);

                        break;
                    case 'people_hits':
                        $stmt->bindValue($identifier, $this->people_hits, PDO::PARAM_INT);

                        break;
                    case 'people_date':
                        $stmt->bindValue($identifier, $this->people_date ? $this->people_date->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'people_insert':
                        $stmt->bindValue($identifier, $this->people_insert ? $this->people_insert->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'people_update':
                        $stmt->bindValue($identifier, $this->people_update ? $this->people_update->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'people_created':
                        $stmt->bindValue($identifier, $this->people_created ? $this->people_created->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'people_updated':
                        $stmt->bindValue($identifier, $this->people_updated ? $this->people_updated->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

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
        $pos = PeopleTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getFirstName();

            case 2:
                return $this->getLastName();

            case 3:
                return $this->getName();

            case 4:
                return $this->getAlpha();

            case 5:
                return $this->getUrlOld();

            case 6:
                return $this->getUrl();

            case 7:
                return $this->getPseudo();

            case 8:
                return $this->getNoosfereId();

            case 9:
                return $this->getBirth();

            case 10:
                return $this->getDeath();

            case 11:
                return $this->getGender();

            case 12:
                return $this->getNation();

            case 13:
                return $this->getBio();

            case 14:
                return $this->getSite();

            case 15:
                return $this->getFacebook();

            case 16:
                return $this->getTwitter();

            case 17:
                return $this->getHits();

            case 18:
                return $this->getDate();

            case 19:
                return $this->getInsert();

            case 20:
                return $this->getUpdate();

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
     * @param bool $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array An associative array containing the field names (as keys) and field values
     */
    public function toArray(string $keyType = TableMap::TYPE_PHPNAME, bool $includeLazyLoadColumns = true, array $alreadyDumpedObjects = [], bool $includeForeignObjects = false): array
    {
        if (isset($alreadyDumpedObjects['People'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['People'][$this->hashCode()] = true;
        $keys = PeopleTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getFirstName(),
            $keys[2] => $this->getLastName(),
            $keys[3] => $this->getName(),
            $keys[4] => $this->getAlpha(),
            $keys[5] => $this->getUrlOld(),
            $keys[6] => $this->getUrl(),
            $keys[7] => $this->getPseudo(),
            $keys[8] => $this->getNoosfereId(),
            $keys[9] => $this->getBirth(),
            $keys[10] => $this->getDeath(),
            $keys[11] => $this->getGender(),
            $keys[12] => $this->getNation(),
            $keys[13] => $this->getBio(),
            $keys[14] => $this->getSite(),
            $keys[15] => $this->getFacebook(),
            $keys[16] => $this->getTwitter(),
            $keys[17] => $this->getHits(),
            $keys[18] => $this->getDate(),
            $keys[19] => $this->getInsert(),
            $keys[20] => $this->getUpdate(),
            $keys[21] => $this->getCreatedAt(),
            $keys[22] => $this->getUpdatedAt(),
        ];
        if ($result[$keys[18]] instanceof \DateTimeInterface) {
            $result[$keys[18]] = $result[$keys[18]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[19]] instanceof \DateTimeInterface) {
            $result[$keys[19]] = $result[$keys[19]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[20]] instanceof \DateTimeInterface) {
            $result[$keys[20]] = $result[$keys[20]]->format('Y-m-d H:i:s.u');
        }

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

        if ($includeForeignObjects) {
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
        $pos = PeopleTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setFirstName($value);
                break;
            case 2:
                $this->setLastName($value);
                break;
            case 3:
                $this->setName($value);
                break;
            case 4:
                $this->setAlpha($value);
                break;
            case 5:
                $this->setUrlOld($value);
                break;
            case 6:
                $this->setUrl($value);
                break;
            case 7:
                $this->setPseudo($value);
                break;
            case 8:
                $this->setNoosfereId($value);
                break;
            case 9:
                $this->setBirth($value);
                break;
            case 10:
                $this->setDeath($value);
                break;
            case 11:
                $this->setGender($value);
                break;
            case 12:
                $this->setNation($value);
                break;
            case 13:
                $this->setBio($value);
                break;
            case 14:
                $this->setSite($value);
                break;
            case 15:
                $this->setFacebook($value);
                break;
            case 16:
                $this->setTwitter($value);
                break;
            case 17:
                $this->setHits($value);
                break;
            case 18:
                $this->setDate($value);
                break;
            case 19:
                $this->setInsert($value);
                break;
            case 20:
                $this->setUpdate($value);
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
        $keys = PeopleTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setFirstName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setLastName($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setName($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setAlpha($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setUrlOld($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setUrl($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setPseudo($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setNoosfereId($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setBirth($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setDeath($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setGender($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setNation($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setBio($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setSite($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setFacebook($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setTwitter($arr[$keys[16]]);
        }
        if (array_key_exists($keys[17], $arr)) {
            $this->setHits($arr[$keys[17]]);
        }
        if (array_key_exists($keys[18], $arr)) {
            $this->setDate($arr[$keys[18]]);
        }
        if (array_key_exists($keys[19], $arr)) {
            $this->setInsert($arr[$keys[19]]);
        }
        if (array_key_exists($keys[20], $arr)) {
            $this->setUpdate($arr[$keys[20]]);
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
        $criteria = new Criteria(PeopleTableMap::DATABASE_NAME);

        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_ID)) {
            $criteria->add(PeopleTableMap::COL_PEOPLE_ID, $this->people_id);
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_FIRST_NAME)) {
            $criteria->add(PeopleTableMap::COL_PEOPLE_FIRST_NAME, $this->people_first_name);
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_LAST_NAME)) {
            $criteria->add(PeopleTableMap::COL_PEOPLE_LAST_NAME, $this->people_last_name);
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_NAME)) {
            $criteria->add(PeopleTableMap::COL_PEOPLE_NAME, $this->people_name);
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_ALPHA)) {
            $criteria->add(PeopleTableMap::COL_PEOPLE_ALPHA, $this->people_alpha);
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_URL_OLD)) {
            $criteria->add(PeopleTableMap::COL_PEOPLE_URL_OLD, $this->people_url_old);
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_URL)) {
            $criteria->add(PeopleTableMap::COL_PEOPLE_URL, $this->people_url);
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_PSEUDO)) {
            $criteria->add(PeopleTableMap::COL_PEOPLE_PSEUDO, $this->people_pseudo);
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_NOOSFERE_ID)) {
            $criteria->add(PeopleTableMap::COL_PEOPLE_NOOSFERE_ID, $this->people_noosfere_id);
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_BIRTH)) {
            $criteria->add(PeopleTableMap::COL_PEOPLE_BIRTH, $this->people_birth);
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_DEATH)) {
            $criteria->add(PeopleTableMap::COL_PEOPLE_DEATH, $this->people_death);
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_GENDER)) {
            $criteria->add(PeopleTableMap::COL_PEOPLE_GENDER, $this->people_gender);
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_NATION)) {
            $criteria->add(PeopleTableMap::COL_PEOPLE_NATION, $this->people_nation);
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_BIO)) {
            $criteria->add(PeopleTableMap::COL_PEOPLE_BIO, $this->people_bio);
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_SITE)) {
            $criteria->add(PeopleTableMap::COL_PEOPLE_SITE, $this->people_site);
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_FACEBOOK)) {
            $criteria->add(PeopleTableMap::COL_PEOPLE_FACEBOOK, $this->people_facebook);
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_TWITTER)) {
            $criteria->add(PeopleTableMap::COL_PEOPLE_TWITTER, $this->people_twitter);
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_HITS)) {
            $criteria->add(PeopleTableMap::COL_PEOPLE_HITS, $this->people_hits);
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_DATE)) {
            $criteria->add(PeopleTableMap::COL_PEOPLE_DATE, $this->people_date);
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_INSERT)) {
            $criteria->add(PeopleTableMap::COL_PEOPLE_INSERT, $this->people_insert);
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_UPDATE)) {
            $criteria->add(PeopleTableMap::COL_PEOPLE_UPDATE, $this->people_update);
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_CREATED)) {
            $criteria->add(PeopleTableMap::COL_PEOPLE_CREATED, $this->people_created);
        }
        if ($this->isColumnModified(PeopleTableMap::COL_PEOPLE_UPDATED)) {
            $criteria->add(PeopleTableMap::COL_PEOPLE_UPDATED, $this->people_updated);
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
        $criteria = ChildPeopleQuery::create();
        $criteria->add(PeopleTableMap::COL_PEOPLE_ID, $this->people_id);

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
     * Generic method to set the primary key (people_id column).
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
     * @param object $copyObj An object of \Model\People (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setFirstName($this->getFirstName());
        $copyObj->setLastName($this->getLastName());
        $copyObj->setName($this->getName());
        $copyObj->setAlpha($this->getAlpha());
        $copyObj->setUrlOld($this->getUrlOld());
        $copyObj->setUrl($this->getUrl());
        $copyObj->setPseudo($this->getPseudo());
        $copyObj->setNoosfereId($this->getNoosfereId());
        $copyObj->setBirth($this->getBirth());
        $copyObj->setDeath($this->getDeath());
        $copyObj->setGender($this->getGender());
        $copyObj->setNation($this->getNation());
        $copyObj->setBio($this->getBio());
        $copyObj->setSite($this->getSite());
        $copyObj->setFacebook($this->getFacebook());
        $copyObj->setTwitter($this->getTwitter());
        $copyObj->setHits($this->getHits());
        $copyObj->setDate($this->getDate());
        $copyObj->setInsert($this->getInsert());
        $copyObj->setUpdate($this->getUpdate());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getImages() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addImage($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getRoles() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addRole($relObj->copy($deepCopy));
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
     * @return \Model\People Clone of current object.
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
        if ('Image' === $relationName) {
            $this->initImages();
            return;
        }
        if ('Role' === $relationName) {
            $this->initRoles();
            return;
        }
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
     * If this ChildPeople is new, it will return
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
                    ->filterByContributor($this)
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
            $imageRemoved->setContributor(null);
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
                ->filterByContributor($this)
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
        $image->setContributor($this);
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
            $image->setContributor(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this People is new, it will return
     * an empty collection; or if this People has previously
     * been saved, it will retrieve related Images from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in People.
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
     * Otherwise if this People is new, it will return
     * an empty collection; or if this People has previously
     * been saved, it will retrieve related Images from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in People.
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
     * Otherwise if this People is new, it will return
     * an empty collection; or if this People has previously
     * been saved, it will retrieve related Images from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in People.
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
     * Otherwise if this People is new, it will return
     * an empty collection; or if this People has previously
     * been saved, it will retrieve related Images from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in People.
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
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this People is new, it will return
     * an empty collection; or if this People has previously
     * been saved, it will retrieve related Images from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in People.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildImage[] List of ChildImage objects
     * @phpstan-return ObjectCollection&\Traversable<ChildImage}> List of ChildImage objects
     */
    public function getImagesJoinPublisher(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildImageQuery::create(null, $criteria);
        $query->joinWith('Publisher', $joinBehavior);

        return $this->getImages($query, $con);
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
     * If this ChildPeople is new, it will return
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
                    ->filterByPeople($this)
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
            $roleRemoved->setPeople(null);
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
                ->filterByPeople($this)
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
        $role->setPeople($this);
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
            $role->setPeople(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this People is new, it will return
     * an empty collection; or if this People has previously
     * been saved, it will retrieve related Roles from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in People.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildRole[] List of ChildRole objects
     * @phpstan-return ObjectCollection&\Traversable<ChildRole}> List of ChildRole objects
     */
    public function getRolesJoinUser(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildRoleQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getRoles($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this People is new, it will return
     * an empty collection; or if this People has previously
     * been saved, it will retrieve related Roles from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in People.
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
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     *
     * @return $this
     */
    public function clear()
    {
        $this->people_id = null;
        $this->people_first_name = null;
        $this->people_last_name = null;
        $this->people_name = null;
        $this->people_alpha = null;
        $this->people_url_old = null;
        $this->people_url = null;
        $this->people_pseudo = null;
        $this->people_noosfere_id = null;
        $this->people_birth = null;
        $this->people_death = null;
        $this->people_gender = null;
        $this->people_nation = null;
        $this->people_bio = null;
        $this->people_site = null;
        $this->people_facebook = null;
        $this->people_twitter = null;
        $this->people_hits = null;
        $this->people_date = null;
        $this->people_insert = null;
        $this->people_update = null;
        $this->people_created = null;
        $this->people_updated = null;
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
            if ($this->collImages) {
                foreach ($this->collImages as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collRoles) {
                foreach ($this->collRoles as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collImages = null;
        $this->collRoles = null;
        return $this;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PeopleTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return $this The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[PeopleTableMap::COL_PEOPLE_UPDATED] = true;

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
