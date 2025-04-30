<?php

namespace Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Model\Article as ChildArticle;
use Model\ArticleQuery as ChildArticleQuery;
use Model\Download as ChildDownload;
use Model\DownloadQuery as ChildDownloadQuery;
use Model\File as ChildFile;
use Model\FileQuery as ChildFileQuery;
use Model\Site as ChildSite;
use Model\SiteQuery as ChildSiteQuery;
use Model\User as ChildUser;
use Model\UserQuery as ChildUserQuery;
use Model\Map\DownloadTableMap;
use Model\Map\FileTableMap;
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
 * Base class that represents a row from the 'files' table.
 *
 *
 *
 * @package    propel.generator.Model.Base
 */
abstract class File implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\Model\\Map\\FileTableMap';


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
     * The value for the file_id field.
     *
     * @var        int
     */
    protected $file_id;

    /**
     * The value for the site_id field.
     *
     * @var        int|null
     */
    protected $site_id;

    /**
     * The value for the article_id field.
     *
     * @var        int|null
     */
    protected $article_id;

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
     * The value for the file_title field.
     *
     * @var        string|null
     */
    protected $file_title;

    /**
     * The value for the file_type field.
     *
     * @var        string|null
     */
    protected $file_type;

    /**
     * The value for the file_access field.
     *
     * Note: this column has a database default value of: true
     * @var        boolean|null
     */
    protected $file_access;

    /**
     * The value for the file_version field.
     *
     * Note: this column has a database default value of: '1.0'
     * @var        string|null
     */
    protected $file_version;

    /**
     * The value for the file_hash field.
     *
     * @var        string|null
     */
    protected $file_hash;

    /**
     * The value for the file_size field.
     *
     * Note: this column has a database default value of: '0'
     * @var        string|null
     */
    protected $file_size;

    /**
     * The value for the file_ean field.
     *
     * @var        string|null
     */
    protected $file_ean;

    /**
     * The value for the file_inserted field.
     *
     * @var        DateTime|null
     */
    protected $file_inserted;

    /**
     * The value for the file_uploaded field.
     *
     * @var        DateTime|null
     */
    protected $file_uploaded;

    /**
     * The value for the file_updated field.
     *
     * @var        DateTime|null
     */
    protected $file_updated;

    /**
     * The value for the file_created field.
     *
     * @var        DateTime|null
     */
    protected $file_created;

    /**
     * @var        ChildSite
     */
    protected $aSite;

    /**
     * @var        ChildArticle
     */
    protected $aArticle;

    /**
     * @var        ChildUser
     */
    protected $aUser;

    /**
     * @var        ObjectCollection|ChildDownload[] Collection to store aggregation of ChildDownload objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildDownload> Collection to store aggregation of ChildDownload objects.
     */
    protected $collDownloads;
    protected $collDownloadsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var bool
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildDownload[]
     * @phpstan-var ObjectCollection&\Traversable<ChildDownload>
     */
    protected $downloadsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues(): void
    {
        $this->file_access = true;
        $this->file_version = '1.0';
        $this->file_size = '0';
    }

    /**
     * Initializes internal state of Model\Base\File object.
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
     * Compares this with another <code>File</code> instance.  If
     * <code>obj</code> is an instance of <code>File</code>, delegates to
     * <code>equals(File)</code>.  Otherwise, returns <code>false</code>.
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
     * Get the [file_id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->file_id;
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
     * Get the [article_id] column value.
     *
     * @return int|null
     */
    public function getArticleId()
    {
        return $this->article_id;
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
     * Get the [file_title] column value.
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->file_title;
    }

    /**
     * Get the [file_type] column value.
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->file_type;
    }

    /**
     * Get the [file_access] column value.
     *
     * @return boolean|null
     */
    public function getAccess()
    {
        return $this->file_access;
    }

    /**
     * Get the [file_access] column value.
     *
     * @return boolean|null
     */
    public function isAccess()
    {
        return $this->getAccess();
    }

    /**
     * Get the [file_version] column value.
     *
     * @return string|null
     */
    public function getVersion()
    {
        return $this->file_version;
    }

    /**
     * Get the [file_hash] column value.
     *
     * @return string|null
     */
    public function getHash()
    {
        return $this->file_hash;
    }

    /**
     * Get the [file_size] column value.
     *
     * @return string|null
     */
    public function getSize()
    {
        return $this->file_size;
    }

    /**
     * Get the [file_ean] column value.
     *
     * @return string|null
     */
    public function getEan()
    {
        return $this->file_ean;
    }

    /**
     * Get the [optionally formatted] temporal [file_inserted] column value.
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
    public function getInserted($format = null)
    {
        if ($format === null) {
            return $this->file_inserted;
        } else {
            return $this->file_inserted instanceof \DateTimeInterface ? $this->file_inserted->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [file_uploaded] column value.
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
    public function getUploaded($format = null)
    {
        if ($format === null) {
            return $this->file_uploaded;
        } else {
            return $this->file_uploaded instanceof \DateTimeInterface ? $this->file_uploaded->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [file_updated] column value.
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
            return $this->file_updated;
        } else {
            return $this->file_updated instanceof \DateTimeInterface ? $this->file_updated->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [file_created] column value.
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
            return $this->file_created;
        } else {
            return $this->file_created instanceof \DateTimeInterface ? $this->file_created->format($format) : null;
        }
    }

    /**
     * Set the value of [file_id] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->file_id !== $v) {
            $this->file_id = $v;
            $this->modifiedColumns[FileTableMap::COL_FILE_ID] = true;
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
            $this->modifiedColumns[FileTableMap::COL_SITE_ID] = true;
        }

        if ($this->aSite !== null && $this->aSite->getId() !== $v) {
            $this->aSite = null;
        }

        return $this;
    }

    /**
     * Set the value of [article_id] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setArticleId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->article_id !== $v) {
            $this->article_id = $v;
            $this->modifiedColumns[FileTableMap::COL_ARTICLE_ID] = true;
        }

        if ($this->aArticle !== null && $this->aArticle->getId() !== $v) {
            $this->aArticle = null;
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
            $this->modifiedColumns[FileTableMap::COL_AXYS_ACCOUNT_ID] = true;
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
            $this->modifiedColumns[FileTableMap::COL_USER_ID] = true;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }

        return $this;
    }

    /**
     * Set the value of [file_title] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->file_title !== $v) {
            $this->file_title = $v;
            $this->modifiedColumns[FileTableMap::COL_FILE_TITLE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [file_type] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setType($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->file_type !== $v) {
            $this->file_type = $v;
            $this->modifiedColumns[FileTableMap::COL_FILE_TYPE] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [file_access] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string|null $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setAccess($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->file_access !== $v) {
            $this->file_access = $v;
            $this->modifiedColumns[FileTableMap::COL_FILE_ACCESS] = true;
        }

        return $this;
    }

    /**
     * Set the value of [file_version] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setVersion($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->file_version !== $v) {
            $this->file_version = $v;
            $this->modifiedColumns[FileTableMap::COL_FILE_VERSION] = true;
        }

        return $this;
    }

    /**
     * Set the value of [file_hash] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setHash($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->file_hash !== $v) {
            $this->file_hash = $v;
            $this->modifiedColumns[FileTableMap::COL_FILE_HASH] = true;
        }

        return $this;
    }

    /**
     * Set the value of [file_size] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setSize($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->file_size !== $v) {
            $this->file_size = $v;
            $this->modifiedColumns[FileTableMap::COL_FILE_SIZE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [file_ean] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setEan($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->file_ean !== $v) {
            $this->file_ean = $v;
            $this->modifiedColumns[FileTableMap::COL_FILE_EAN] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [file_inserted] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setInserted($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->file_inserted !== null || $dt !== null) {
            if ($this->file_inserted === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->file_inserted->format("Y-m-d H:i:s.u")) {
                $this->file_inserted = $dt === null ? null : clone $dt;
                $this->modifiedColumns[FileTableMap::COL_FILE_INSERTED] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [file_uploaded] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUploaded($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->file_uploaded !== null || $dt !== null) {
            if ($this->file_uploaded === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->file_uploaded->format("Y-m-d H:i:s.u")) {
                $this->file_uploaded = $dt === null ? null : clone $dt;
                $this->modifiedColumns[FileTableMap::COL_FILE_UPLOADED] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [file_updated] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->file_updated !== null || $dt !== null) {
            if ($this->file_updated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->file_updated->format("Y-m-d H:i:s.u")) {
                $this->file_updated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[FileTableMap::COL_FILE_UPDATED] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [file_created] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->file_created !== null || $dt !== null) {
            if ($this->file_created === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->file_created->format("Y-m-d H:i:s.u")) {
                $this->file_created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[FileTableMap::COL_FILE_CREATED] = true;
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
            if ($this->file_access !== true) {
                return false;
            }

            if ($this->file_version !== '1.0') {
                return false;
            }

            if ($this->file_size !== '0') {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : FileTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->file_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : FileTableMap::translateFieldName('SiteId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->site_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : FileTableMap::translateFieldName('ArticleId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : FileTableMap::translateFieldName('AxysAccountId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->axys_account_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : FileTableMap::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : FileTableMap::translateFieldName('Title', TableMap::TYPE_PHPNAME, $indexType)];
            $this->file_title = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : FileTableMap::translateFieldName('Type', TableMap::TYPE_PHPNAME, $indexType)];
            $this->file_type = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : FileTableMap::translateFieldName('Access', TableMap::TYPE_PHPNAME, $indexType)];
            $this->file_access = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : FileTableMap::translateFieldName('Version', TableMap::TYPE_PHPNAME, $indexType)];
            $this->file_version = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : FileTableMap::translateFieldName('Hash', TableMap::TYPE_PHPNAME, $indexType)];
            $this->file_hash = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : FileTableMap::translateFieldName('Size', TableMap::TYPE_PHPNAME, $indexType)];
            $this->file_size = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : FileTableMap::translateFieldName('Ean', TableMap::TYPE_PHPNAME, $indexType)];
            $this->file_ean = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : FileTableMap::translateFieldName('Inserted', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->file_inserted = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : FileTableMap::translateFieldName('Uploaded', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->file_uploaded = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : FileTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->file_updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : FileTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->file_created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 16; // 16 = FileTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\File'), 0, $e);
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
        if ($this->aArticle !== null && $this->article_id !== $this->aArticle->getId()) {
            $this->aArticle = null;
        }
        if ($this->aUser !== null && $this->user_id !== $this->aUser->getId()) {
            $this->aUser = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(FileTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildFileQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aSite = null;
            $this->aArticle = null;
            $this->aUser = null;
            $this->collDownloads = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param ConnectionInterface $con
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     * @see File::setDeleted()
     * @see File::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(FileTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildFileQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(FileTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(FileTableMap::COL_FILE_CREATED)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(FileTableMap::COL_FILE_UPDATED)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(FileTableMap::COL_FILE_UPDATED)) {
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
                FileTableMap::addInstanceToPool($this);
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

            if ($this->aArticle !== null) {
                if ($this->aArticle->isModified() || $this->aArticle->isNew()) {
                    $affectedRows += $this->aArticle->save($con);
                }
                $this->setArticle($this->aArticle);
            }

            if ($this->aUser !== null) {
                if ($this->aUser->isModified() || $this->aUser->isNew()) {
                    $affectedRows += $this->aUser->save($con);
                }
                $this->setUser($this->aUser);
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

        $this->modifiedColumns[FileTableMap::COL_FILE_ID] = true;
        if (null !== $this->file_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . FileTableMap::COL_FILE_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(FileTableMap::COL_FILE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'file_id';
        }
        if ($this->isColumnModified(FileTableMap::COL_SITE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'site_id';
        }
        if ($this->isColumnModified(FileTableMap::COL_ARTICLE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'article_id';
        }
        if ($this->isColumnModified(FileTableMap::COL_AXYS_ACCOUNT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'axys_account_id';
        }
        if ($this->isColumnModified(FileTableMap::COL_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'user_id';
        }
        if ($this->isColumnModified(FileTableMap::COL_FILE_TITLE)) {
            $modifiedColumns[':p' . $index++]  = 'file_title';
        }
        if ($this->isColumnModified(FileTableMap::COL_FILE_TYPE)) {
            $modifiedColumns[':p' . $index++]  = 'file_type';
        }
        if ($this->isColumnModified(FileTableMap::COL_FILE_ACCESS)) {
            $modifiedColumns[':p' . $index++]  = 'file_access';
        }
        if ($this->isColumnModified(FileTableMap::COL_FILE_VERSION)) {
            $modifiedColumns[':p' . $index++]  = 'file_version';
        }
        if ($this->isColumnModified(FileTableMap::COL_FILE_HASH)) {
            $modifiedColumns[':p' . $index++]  = 'file_hash';
        }
        if ($this->isColumnModified(FileTableMap::COL_FILE_SIZE)) {
            $modifiedColumns[':p' . $index++]  = 'file_size';
        }
        if ($this->isColumnModified(FileTableMap::COL_FILE_EAN)) {
            $modifiedColumns[':p' . $index++]  = 'file_ean';
        }
        if ($this->isColumnModified(FileTableMap::COL_FILE_INSERTED)) {
            $modifiedColumns[':p' . $index++]  = 'file_inserted';
        }
        if ($this->isColumnModified(FileTableMap::COL_FILE_UPLOADED)) {
            $modifiedColumns[':p' . $index++]  = 'file_uploaded';
        }
        if ($this->isColumnModified(FileTableMap::COL_FILE_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = 'file_updated';
        }
        if ($this->isColumnModified(FileTableMap::COL_FILE_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'file_created';
        }

        $sql = sprintf(
            'INSERT INTO files (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'file_id':
                        $stmt->bindValue($identifier, $this->file_id, PDO::PARAM_INT);

                        break;
                    case 'site_id':
                        $stmt->bindValue($identifier, $this->site_id, PDO::PARAM_INT);

                        break;
                    case 'article_id':
                        $stmt->bindValue($identifier, $this->article_id, PDO::PARAM_INT);

                        break;
                    case 'axys_account_id':
                        $stmt->bindValue($identifier, $this->axys_account_id, PDO::PARAM_INT);

                        break;
                    case 'user_id':
                        $stmt->bindValue($identifier, $this->user_id, PDO::PARAM_INT);

                        break;
                    case 'file_title':
                        $stmt->bindValue($identifier, $this->file_title, PDO::PARAM_STR);

                        break;
                    case 'file_type':
                        $stmt->bindValue($identifier, $this->file_type, PDO::PARAM_STR);

                        break;
                    case 'file_access':
                        $stmt->bindValue($identifier, (int) $this->file_access, PDO::PARAM_INT);

                        break;
                    case 'file_version':
                        $stmt->bindValue($identifier, $this->file_version, PDO::PARAM_STR);

                        break;
                    case 'file_hash':
                        $stmt->bindValue($identifier, $this->file_hash, PDO::PARAM_STR);

                        break;
                    case 'file_size':
                        $stmt->bindValue($identifier, $this->file_size, PDO::PARAM_INT);

                        break;
                    case 'file_ean':
                        $stmt->bindValue($identifier, $this->file_ean, PDO::PARAM_INT);

                        break;
                    case 'file_inserted':
                        $stmt->bindValue($identifier, $this->file_inserted ? $this->file_inserted->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'file_uploaded':
                        $stmt->bindValue($identifier, $this->file_uploaded ? $this->file_uploaded->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'file_updated':
                        $stmt->bindValue($identifier, $this->file_updated ? $this->file_updated->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'file_created':
                        $stmt->bindValue($identifier, $this->file_created ? $this->file_created->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

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
        $pos = FileTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getArticleId();

            case 3:
                return $this->getAxysAccountId();

            case 4:
                return $this->getUserId();

            case 5:
                return $this->getTitle();

            case 6:
                return $this->getType();

            case 7:
                return $this->getAccess();

            case 8:
                return $this->getVersion();

            case 9:
                return $this->getHash();

            case 10:
                return $this->getSize();

            case 11:
                return $this->getEan();

            case 12:
                return $this->getInserted();

            case 13:
                return $this->getUploaded();

            case 14:
                return $this->getUpdatedAt();

            case 15:
                return $this->getCreatedAt();

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
        if (isset($alreadyDumpedObjects['File'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['File'][$this->hashCode()] = true;
        $keys = FileTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getSiteId(),
            $keys[2] => $this->getArticleId(),
            $keys[3] => $this->getAxysAccountId(),
            $keys[4] => $this->getUserId(),
            $keys[5] => $this->getTitle(),
            $keys[6] => $this->getType(),
            $keys[7] => $this->getAccess(),
            $keys[8] => $this->getVersion(),
            $keys[9] => $this->getHash(),
            $keys[10] => $this->getSize(),
            $keys[11] => $this->getEan(),
            $keys[12] => $this->getInserted(),
            $keys[13] => $this->getUploaded(),
            $keys[14] => $this->getUpdatedAt(),
            $keys[15] => $this->getCreatedAt(),
        ];
        if ($result[$keys[12]] instanceof \DateTimeInterface) {
            $result[$keys[12]] = $result[$keys[12]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[13]] instanceof \DateTimeInterface) {
            $result[$keys[13]] = $result[$keys[13]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[14]] instanceof \DateTimeInterface) {
            $result[$keys[14]] = $result[$keys[14]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[15]] instanceof \DateTimeInterface) {
            $result[$keys[15]] = $result[$keys[15]]->format('Y-m-d H:i:s.u');
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
            if (null !== $this->aArticle) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'article';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'articles';
                        break;
                    default:
                        $key = 'Article';
                }

                $result[$key] = $this->aArticle->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
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
        $pos = FileTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setArticleId($value);
                break;
            case 3:
                $this->setAxysAccountId($value);
                break;
            case 4:
                $this->setUserId($value);
                break;
            case 5:
                $this->setTitle($value);
                break;
            case 6:
                $this->setType($value);
                break;
            case 7:
                $this->setAccess($value);
                break;
            case 8:
                $this->setVersion($value);
                break;
            case 9:
                $this->setHash($value);
                break;
            case 10:
                $this->setSize($value);
                break;
            case 11:
                $this->setEan($value);
                break;
            case 12:
                $this->setInserted($value);
                break;
            case 13:
                $this->setUploaded($value);
                break;
            case 14:
                $this->setUpdatedAt($value);
                break;
            case 15:
                $this->setCreatedAt($value);
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
        $keys = FileTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setSiteId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setArticleId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setAxysAccountId($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setUserId($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setTitle($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setType($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setAccess($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setVersion($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setHash($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setSize($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setEan($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setInserted($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setUploaded($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setUpdatedAt($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setCreatedAt($arr[$keys[15]]);
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
        $criteria = new Criteria(FileTableMap::DATABASE_NAME);

        if ($this->isColumnModified(FileTableMap::COL_FILE_ID)) {
            $criteria->add(FileTableMap::COL_FILE_ID, $this->file_id);
        }
        if ($this->isColumnModified(FileTableMap::COL_SITE_ID)) {
            $criteria->add(FileTableMap::COL_SITE_ID, $this->site_id);
        }
        if ($this->isColumnModified(FileTableMap::COL_ARTICLE_ID)) {
            $criteria->add(FileTableMap::COL_ARTICLE_ID, $this->article_id);
        }
        if ($this->isColumnModified(FileTableMap::COL_AXYS_ACCOUNT_ID)) {
            $criteria->add(FileTableMap::COL_AXYS_ACCOUNT_ID, $this->axys_account_id);
        }
        if ($this->isColumnModified(FileTableMap::COL_USER_ID)) {
            $criteria->add(FileTableMap::COL_USER_ID, $this->user_id);
        }
        if ($this->isColumnModified(FileTableMap::COL_FILE_TITLE)) {
            $criteria->add(FileTableMap::COL_FILE_TITLE, $this->file_title);
        }
        if ($this->isColumnModified(FileTableMap::COL_FILE_TYPE)) {
            $criteria->add(FileTableMap::COL_FILE_TYPE, $this->file_type);
        }
        if ($this->isColumnModified(FileTableMap::COL_FILE_ACCESS)) {
            $criteria->add(FileTableMap::COL_FILE_ACCESS, $this->file_access);
        }
        if ($this->isColumnModified(FileTableMap::COL_FILE_VERSION)) {
            $criteria->add(FileTableMap::COL_FILE_VERSION, $this->file_version);
        }
        if ($this->isColumnModified(FileTableMap::COL_FILE_HASH)) {
            $criteria->add(FileTableMap::COL_FILE_HASH, $this->file_hash);
        }
        if ($this->isColumnModified(FileTableMap::COL_FILE_SIZE)) {
            $criteria->add(FileTableMap::COL_FILE_SIZE, $this->file_size);
        }
        if ($this->isColumnModified(FileTableMap::COL_FILE_EAN)) {
            $criteria->add(FileTableMap::COL_FILE_EAN, $this->file_ean);
        }
        if ($this->isColumnModified(FileTableMap::COL_FILE_INSERTED)) {
            $criteria->add(FileTableMap::COL_FILE_INSERTED, $this->file_inserted);
        }
        if ($this->isColumnModified(FileTableMap::COL_FILE_UPLOADED)) {
            $criteria->add(FileTableMap::COL_FILE_UPLOADED, $this->file_uploaded);
        }
        if ($this->isColumnModified(FileTableMap::COL_FILE_UPDATED)) {
            $criteria->add(FileTableMap::COL_FILE_UPDATED, $this->file_updated);
        }
        if ($this->isColumnModified(FileTableMap::COL_FILE_CREATED)) {
            $criteria->add(FileTableMap::COL_FILE_CREATED, $this->file_created);
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
        $criteria = ChildFileQuery::create();
        $criteria->add(FileTableMap::COL_FILE_ID, $this->file_id);

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
     * Generic method to set the primary key (file_id column).
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
     * @param object $copyObj An object of \Model\File (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setSiteId($this->getSiteId());
        $copyObj->setArticleId($this->getArticleId());
        $copyObj->setAxysAccountId($this->getAxysAccountId());
        $copyObj->setUserId($this->getUserId());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setType($this->getType());
        $copyObj->setAccess($this->getAccess());
        $copyObj->setVersion($this->getVersion());
        $copyObj->setHash($this->getHash());
        $copyObj->setSize($this->getSize());
        $copyObj->setEan($this->getEan());
        $copyObj->setInserted($this->getInserted());
        $copyObj->setUploaded($this->getUploaded());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        $copyObj->setCreatedAt($this->getCreatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getDownloads() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDownload($relObj->copy($deepCopy));
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
     * @return \Model\File Clone of current object.
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
            $v->addFile($this);
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
                $this->aSite->addFiles($this);
             */
        }

        return $this->aSite;
    }

    /**
     * Declares an association between this object and a ChildArticle object.
     *
     * @param ChildArticle|null $v
     * @return $this The current object (for fluent API support)
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setArticle(ChildArticle $v = null)
    {
        if ($v === null) {
            $this->setArticleId(NULL);
        } else {
            $this->setArticleId($v->getId());
        }

        $this->aArticle = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildArticle object, it will not be re-added.
        if ($v !== null) {
            $v->addFile($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildArticle object
     *
     * @param ConnectionInterface $con Optional Connection object.
     * @return ChildArticle|null The associated ChildArticle object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getArticle(?ConnectionInterface $con = null)
    {
        if ($this->aArticle === null && ($this->article_id != 0)) {
            $this->aArticle = ChildArticleQuery::create()->findPk($this->article_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aArticle->addFiles($this);
             */
        }

        return $this->aArticle;
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
            $v->addFile($this);
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
                $this->aUser->addFiles($this);
             */
        }

        return $this->aUser;
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
        if ('Download' === $relationName) {
            $this->initDownloads();
            return;
        }
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
     * If this ChildFile is new, it will return
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
                    ->filterByFile($this)
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
            $downloadRemoved->setFile(null);
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
                ->filterByFile($this)
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
        $download->setFile($this);
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
            $download->setFile(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this File is new, it will return
     * an empty collection; or if this File has previously
     * been saved, it will retrieve related Downloads from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in File.
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
     * Otherwise if this File is new, it will return
     * an empty collection; or if this File has previously
     * been saved, it will retrieve related Downloads from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in File.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildDownload[] List of ChildDownload objects
     * @phpstan-return ObjectCollection&\Traversable<ChildDownload}> List of ChildDownload objects
     */
    public function getDownloadsJoinUser(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildDownloadQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getDownloads($query, $con);
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
            $this->aSite->removeFile($this);
        }
        if (null !== $this->aArticle) {
            $this->aArticle->removeFile($this);
        }
        if (null !== $this->aUser) {
            $this->aUser->removeFile($this);
        }
        $this->file_id = null;
        $this->site_id = null;
        $this->article_id = null;
        $this->axys_account_id = null;
        $this->user_id = null;
        $this->file_title = null;
        $this->file_type = null;
        $this->file_access = null;
        $this->file_version = null;
        $this->file_hash = null;
        $this->file_size = null;
        $this->file_ean = null;
        $this->file_inserted = null;
        $this->file_uploaded = null;
        $this->file_updated = null;
        $this->file_created = null;
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
            if ($this->collDownloads) {
                foreach ($this->collDownloads as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collDownloads = null;
        $this->aSite = null;
        $this->aArticle = null;
        $this->aUser = null;
        return $this;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(FileTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return $this The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[FileTableMap::COL_FILE_UPDATED] = true;

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
