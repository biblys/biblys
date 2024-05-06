<?php

namespace Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Model\AxysAccountQuery as ChildAxysAccountQuery;
use Model\Map\AxysAccountTableMap;
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
use Symfony\Component\Translation\IdentityTranslator;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Context\ExecutionContextFactory;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Mapping\Factory\LazyLoadingMetadataFactory;
use Symfony\Component\Validator\Mapping\Loader\StaticMethodLoader;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Base class that represents a row from the 'axys_accounts' table.
 *
 *
 *
 * @package    propel.generator.Model.Base
 */
abstract class AxysAccount implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\Model\\Map\\AxysAccountTableMap';


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
     * The value for the axys_account_id field.
     *
     * @var        int
     */
    protected $axys_account_id;

    /**
     * The value for the axys_account_email field.
     *
     * @var        string|null
     */
    protected $axys_account_email;

    /**
     * The value for the axys_account_password field.
     *
     * @var        string|null
     */
    protected $axys_account_password;

    /**
     * The value for the axys_account_key field.
     *
     * @var        string|null
     */
    protected $axys_account_key;

    /**
     * The value for the axys_account_email_key field.
     *
     * @var        string|null
     */
    protected $axys_account_email_key;

    /**
     * The value for the axys_account_screen_name field.
     *
     * @var        string|null
     */
    protected $axys_account_screen_name;

    /**
     * The value for the axys_account_slug field.
     *
     * @var        string|null
     */
    protected $axys_account_slug;

    /**
     * The value for the axys_account_signup_date field.
     *
     * @var        DateTime|null
     */
    protected $axys_account_signup_date;

    /**
     * The value for the axys_account_login_date field.
     *
     * @var        DateTime|null
     */
    protected $axys_account_login_date;

    /**
     * The value for the axys_account_first_name field.
     *
     * @var        string|null
     */
    protected $axys_account_first_name;

    /**
     * The value for the axys_account_last_name field.
     *
     * @var        string|null
     */
    protected $axys_account_last_name;

    /**
     * The value for the axys_account_update field.
     *
     * @var        DateTime|null
     */
    protected $axys_account_update;

    /**
     * The value for the email_verified_at field.
     *
     * @var        DateTime|null
     */
    protected $email_verified_at;

    /**
     * The value for the marked_for_email_verification_at field.
     *
     * @var        DateTime|null
     */
    protected $marked_for_email_verification_at;

    /**
     * The value for the warned_before_deletion_at field.
     *
     * @var        DateTime|null
     */
    protected $warned_before_deletion_at;

    /**
     * The value for the axys_account_created field.
     *
     * @var        DateTime|null
     */
    protected $axys_account_created;

    /**
     * The value for the axys_account_updated field.
     *
     * @var        DateTime|null
     */
    protected $axys_account_updated;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var bool
     */
    protected $alreadyInSave = false;

    // validate behavior

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * ConstraintViolationList object
     *
     * @see     http://api.symfony.com/2.0/Symfony/Component/Validator/ConstraintViolationList.html
     * @var     ConstraintViolationList
     */
    protected $validationFailures;

    /**
     * Initializes internal state of Model\Base\AxysAccount object.
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
     * Compares this with another <code>AxysAccount</code> instance.  If
     * <code>obj</code> is an instance of <code>AxysAccount</code>, delegates to
     * <code>equals(AxysAccount)</code>.  Otherwise, returns <code>false</code>.
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
     * Get the [axys_account_id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->axys_account_id;
    }

    /**
     * Get the [axys_account_email] column value.
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->axys_account_email;
    }

    /**
     * Get the [axys_account_password] column value.
     *
     * @return string|null
     */
    public function getPassword()
    {
        return $this->axys_account_password;
    }

    /**
     * Get the [axys_account_key] column value.
     *
     * @return string|null
     */
    public function getKey()
    {
        return $this->axys_account_key;
    }

    /**
     * Get the [axys_account_email_key] column value.
     *
     * @return string|null
     */
    public function getEmailKey()
    {
        return $this->axys_account_email_key;
    }

    /**
     * Get the [axys_account_screen_name] column value.
     *
     * @return string|null
     */
    public function getUsername()
    {
        return $this->axys_account_screen_name;
    }

    /**
     * Get the [axys_account_slug] column value.
     *
     * @return string|null
     */
    public function getSlug()
    {
        return $this->axys_account_slug;
    }

    /**
     * Get the [optionally formatted] temporal [axys_account_signup_date] column value.
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
    public function getSignupDate($format = null)
    {
        if ($format === null) {
            return $this->axys_account_signup_date;
        } else {
            return $this->axys_account_signup_date instanceof \DateTimeInterface ? $this->axys_account_signup_date->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [axys_account_login_date] column value.
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
    public function getLoginDate($format = null)
    {
        if ($format === null) {
            return $this->axys_account_login_date;
        } else {
            return $this->axys_account_login_date instanceof \DateTimeInterface ? $this->axys_account_login_date->format($format) : null;
        }
    }

    /**
     * Get the [axys_account_first_name] column value.
     *
     * @return string|null
     */
    public function getFirstName()
    {
        return $this->axys_account_first_name;
    }

    /**
     * Get the [axys_account_last_name] column value.
     *
     * @return string|null
     */
    public function getLastName()
    {
        return $this->axys_account_last_name;
    }

    /**
     * Get the [optionally formatted] temporal [axys_account_update] column value.
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
            return $this->axys_account_update;
        } else {
            return $this->axys_account_update instanceof \DateTimeInterface ? $this->axys_account_update->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [email_verified_at] column value.
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
    public function getEmailVerifiedAt($format = null)
    {
        if ($format === null) {
            return $this->email_verified_at;
        } else {
            return $this->email_verified_at instanceof \DateTimeInterface ? $this->email_verified_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [marked_for_email_verification_at] column value.
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
    public function getMarkedForEmailVerificationAt($format = null)
    {
        if ($format === null) {
            return $this->marked_for_email_verification_at;
        } else {
            return $this->marked_for_email_verification_at instanceof \DateTimeInterface ? $this->marked_for_email_verification_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [warned_before_deletion_at] column value.
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
    public function getWarnedBeforeDeletionAt($format = null)
    {
        if ($format === null) {
            return $this->warned_before_deletion_at;
        } else {
            return $this->warned_before_deletion_at instanceof \DateTimeInterface ? $this->warned_before_deletion_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [axys_account_created] column value.
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
            return $this->axys_account_created;
        } else {
            return $this->axys_account_created instanceof \DateTimeInterface ? $this->axys_account_created->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [axys_account_updated] column value.
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
            return $this->axys_account_updated;
        } else {
            return $this->axys_account_updated instanceof \DateTimeInterface ? $this->axys_account_updated->format($format) : null;
        }
    }

    /**
     * Set the value of [axys_account_id] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->axys_account_id !== $v) {
            $this->axys_account_id = $v;
            $this->modifiedColumns[AxysAccountTableMap::COL_AXYS_ACCOUNT_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [axys_account_email] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->axys_account_email !== $v) {
            $this->axys_account_email = $v;
            $this->modifiedColumns[AxysAccountTableMap::COL_AXYS_ACCOUNT_EMAIL] = true;
        }

        return $this;
    }

    /**
     * Set the value of [axys_account_password] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPassword($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->axys_account_password !== $v) {
            $this->axys_account_password = $v;
            $this->modifiedColumns[AxysAccountTableMap::COL_AXYS_ACCOUNT_PASSWORD] = true;
        }

        return $this;
    }

    /**
     * Set the value of [axys_account_key] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setKey($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->axys_account_key !== $v) {
            $this->axys_account_key = $v;
            $this->modifiedColumns[AxysAccountTableMap::COL_AXYS_ACCOUNT_KEY] = true;
        }

        return $this;
    }

    /**
     * Set the value of [axys_account_email_key] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setEmailKey($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->axys_account_email_key !== $v) {
            $this->axys_account_email_key = $v;
            $this->modifiedColumns[AxysAccountTableMap::COL_AXYS_ACCOUNT_EMAIL_KEY] = true;
        }

        return $this;
    }

    /**
     * Set the value of [axys_account_screen_name] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setUsername($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->axys_account_screen_name !== $v) {
            $this->axys_account_screen_name = $v;
            $this->modifiedColumns[AxysAccountTableMap::COL_AXYS_ACCOUNT_SCREEN_NAME] = true;
        }

        return $this;
    }

    /**
     * Set the value of [axys_account_slug] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setSlug($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->axys_account_slug !== $v) {
            $this->axys_account_slug = $v;
            $this->modifiedColumns[AxysAccountTableMap::COL_AXYS_ACCOUNT_SLUG] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [axys_account_signup_date] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setSignupDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->axys_account_signup_date !== null || $dt !== null) {
            if ($this->axys_account_signup_date === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->axys_account_signup_date->format("Y-m-d H:i:s.u")) {
                $this->axys_account_signup_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[AxysAccountTableMap::COL_AXYS_ACCOUNT_SIGNUP_DATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [axys_account_login_date] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setLoginDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->axys_account_login_date !== null || $dt !== null) {
            if ($this->axys_account_login_date === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->axys_account_login_date->format("Y-m-d H:i:s.u")) {
                $this->axys_account_login_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[AxysAccountTableMap::COL_AXYS_ACCOUNT_LOGIN_DATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Set the value of [axys_account_first_name] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setFirstName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->axys_account_first_name !== $v) {
            $this->axys_account_first_name = $v;
            $this->modifiedColumns[AxysAccountTableMap::COL_AXYS_ACCOUNT_FIRST_NAME] = true;
        }

        return $this;
    }

    /**
     * Set the value of [axys_account_last_name] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setLastName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->axys_account_last_name !== $v) {
            $this->axys_account_last_name = $v;
            $this->modifiedColumns[AxysAccountTableMap::COL_AXYS_ACCOUNT_LAST_NAME] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [axys_account_update] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->axys_account_update !== null || $dt !== null) {
            if ($this->axys_account_update === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->axys_account_update->format("Y-m-d H:i:s.u")) {
                $this->axys_account_update = $dt === null ? null : clone $dt;
                $this->modifiedColumns[AxysAccountTableMap::COL_AXYS_ACCOUNT_UPDATE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [email_verified_at] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setEmailVerifiedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->email_verified_at !== null || $dt !== null) {
            if ($this->email_verified_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->email_verified_at->format("Y-m-d H:i:s.u")) {
                $this->email_verified_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[AxysAccountTableMap::COL_EMAIL_VERIFIED_AT] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [marked_for_email_verification_at] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setMarkedForEmailVerificationAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->marked_for_email_verification_at !== null || $dt !== null) {
            if ($this->marked_for_email_verification_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->marked_for_email_verification_at->format("Y-m-d H:i:s.u")) {
                $this->marked_for_email_verification_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[AxysAccountTableMap::COL_MARKED_FOR_EMAIL_VERIFICATION_AT] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [warned_before_deletion_at] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setWarnedBeforeDeletionAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->warned_before_deletion_at !== null || $dt !== null) {
            if ($this->warned_before_deletion_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->warned_before_deletion_at->format("Y-m-d H:i:s.u")) {
                $this->warned_before_deletion_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[AxysAccountTableMap::COL_WARNED_BEFORE_DELETION_AT] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [axys_account_created] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->axys_account_created !== null || $dt !== null) {
            if ($this->axys_account_created === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->axys_account_created->format("Y-m-d H:i:s.u")) {
                $this->axys_account_created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[AxysAccountTableMap::COL_AXYS_ACCOUNT_CREATED] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [axys_account_updated] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->axys_account_updated !== null || $dt !== null) {
            if ($this->axys_account_updated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->axys_account_updated->format("Y-m-d H:i:s.u")) {
                $this->axys_account_updated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[AxysAccountTableMap::COL_AXYS_ACCOUNT_UPDATED] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : AxysAccountTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->axys_account_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : AxysAccountTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->axys_account_email = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : AxysAccountTableMap::translateFieldName('Password', TableMap::TYPE_PHPNAME, $indexType)];
            $this->axys_account_password = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : AxysAccountTableMap::translateFieldName('Key', TableMap::TYPE_PHPNAME, $indexType)];
            $this->axys_account_key = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : AxysAccountTableMap::translateFieldName('EmailKey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->axys_account_email_key = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : AxysAccountTableMap::translateFieldName('Username', TableMap::TYPE_PHPNAME, $indexType)];
            $this->axys_account_screen_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : AxysAccountTableMap::translateFieldName('Slug', TableMap::TYPE_PHPNAME, $indexType)];
            $this->axys_account_slug = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : AxysAccountTableMap::translateFieldName('SignupDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->axys_account_signup_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : AxysAccountTableMap::translateFieldName('LoginDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->axys_account_login_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : AxysAccountTableMap::translateFieldName('FirstName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->axys_account_first_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : AxysAccountTableMap::translateFieldName('LastName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->axys_account_last_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : AxysAccountTableMap::translateFieldName('Update', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->axys_account_update = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : AxysAccountTableMap::translateFieldName('EmailVerifiedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->email_verified_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : AxysAccountTableMap::translateFieldName('MarkedForEmailVerificationAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->marked_for_email_verification_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : AxysAccountTableMap::translateFieldName('WarnedBeforeDeletionAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->warned_before_deletion_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : AxysAccountTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->axys_account_created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : AxysAccountTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->axys_account_updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 17; // 17 = AxysAccountTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\AxysAccount'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(AxysAccountTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildAxysAccountQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
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
     * @see AxysAccount::setDeleted()
     * @see AxysAccount::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(AxysAccountTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildAxysAccountQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(AxysAccountTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_CREATED)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_UPDATED)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_UPDATED)) {
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
                AxysAccountTableMap::addInstanceToPool($this);
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

        $this->modifiedColumns[AxysAccountTableMap::COL_AXYS_ACCOUNT_ID] = true;
        if (null !== $this->axys_account_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . AxysAccountTableMap::COL_AXYS_ACCOUNT_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'axys_account_id';
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'axys_account_email';
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_PASSWORD)) {
            $modifiedColumns[':p' . $index++]  = 'axys_account_password';
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_KEY)) {
            $modifiedColumns[':p' . $index++]  = 'axys_account_key';
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_EMAIL_KEY)) {
            $modifiedColumns[':p' . $index++]  = 'axys_account_email_key';
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_SCREEN_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'axys_account_screen_name';
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_SLUG)) {
            $modifiedColumns[':p' . $index++]  = 'axys_account_slug';
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_SIGNUP_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'axys_account_signup_date';
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_LOGIN_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'axys_account_login_date';
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_FIRST_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'axys_account_first_name';
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_LAST_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'axys_account_last_name';
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_UPDATE)) {
            $modifiedColumns[':p' . $index++]  = 'axys_account_update';
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_EMAIL_VERIFIED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'email_verified_at';
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_MARKED_FOR_EMAIL_VERIFICATION_AT)) {
            $modifiedColumns[':p' . $index++]  = 'marked_for_email_verification_at';
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_WARNED_BEFORE_DELETION_AT)) {
            $modifiedColumns[':p' . $index++]  = 'warned_before_deletion_at';
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'axys_account_created';
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = 'axys_account_updated';
        }

        $sql = sprintf(
            'INSERT INTO axys_accounts (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'axys_account_id':
                        $stmt->bindValue($identifier, $this->axys_account_id, PDO::PARAM_INT);

                        break;
                    case 'axys_account_email':
                        $stmt->bindValue($identifier, $this->axys_account_email, PDO::PARAM_STR);

                        break;
                    case 'axys_account_password':
                        $stmt->bindValue($identifier, $this->axys_account_password, PDO::PARAM_STR);

                        break;
                    case 'axys_account_key':
                        $stmt->bindValue($identifier, $this->axys_account_key, PDO::PARAM_STR);

                        break;
                    case 'axys_account_email_key':
                        $stmt->bindValue($identifier, $this->axys_account_email_key, PDO::PARAM_STR);

                        break;
                    case 'axys_account_screen_name':
                        $stmt->bindValue($identifier, $this->axys_account_screen_name, PDO::PARAM_STR);

                        break;
                    case 'axys_account_slug':
                        $stmt->bindValue($identifier, $this->axys_account_slug, PDO::PARAM_STR);

                        break;
                    case 'axys_account_signup_date':
                        $stmt->bindValue($identifier, $this->axys_account_signup_date ? $this->axys_account_signup_date->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'axys_account_login_date':
                        $stmt->bindValue($identifier, $this->axys_account_login_date ? $this->axys_account_login_date->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'axys_account_first_name':
                        $stmt->bindValue($identifier, $this->axys_account_first_name, PDO::PARAM_STR);

                        break;
                    case 'axys_account_last_name':
                        $stmt->bindValue($identifier, $this->axys_account_last_name, PDO::PARAM_STR);

                        break;
                    case 'axys_account_update':
                        $stmt->bindValue($identifier, $this->axys_account_update ? $this->axys_account_update->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'email_verified_at':
                        $stmt->bindValue($identifier, $this->email_verified_at ? $this->email_verified_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'marked_for_email_verification_at':
                        $stmt->bindValue($identifier, $this->marked_for_email_verification_at ? $this->marked_for_email_verification_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'warned_before_deletion_at':
                        $stmt->bindValue($identifier, $this->warned_before_deletion_at ? $this->warned_before_deletion_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'axys_account_created':
                        $stmt->bindValue($identifier, $this->axys_account_created ? $this->axys_account_created->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'axys_account_updated':
                        $stmt->bindValue($identifier, $this->axys_account_updated ? $this->axys_account_updated->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

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
        $pos = AxysAccountTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getEmail();

            case 2:
                return $this->getPassword();

            case 3:
                return $this->getKey();

            case 4:
                return $this->getEmailKey();

            case 5:
                return $this->getUsername();

            case 6:
                return $this->getSlug();

            case 7:
                return $this->getSignupDate();

            case 8:
                return $this->getLoginDate();

            case 9:
                return $this->getFirstName();

            case 10:
                return $this->getLastName();

            case 11:
                return $this->getUpdate();

            case 12:
                return $this->getEmailVerifiedAt();

            case 13:
                return $this->getMarkedForEmailVerificationAt();

            case 14:
                return $this->getWarnedBeforeDeletionAt();

            case 15:
                return $this->getCreatedAt();

            case 16:
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
        if (isset($alreadyDumpedObjects['AxysAccount'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['AxysAccount'][$this->hashCode()] = true;
        $keys = AxysAccountTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getEmail(),
            $keys[2] => $this->getPassword(),
            $keys[3] => $this->getKey(),
            $keys[4] => $this->getEmailKey(),
            $keys[5] => $this->getUsername(),
            $keys[6] => $this->getSlug(),
            $keys[7] => $this->getSignupDate(),
            $keys[8] => $this->getLoginDate(),
            $keys[9] => $this->getFirstName(),
            $keys[10] => $this->getLastName(),
            $keys[11] => $this->getUpdate(),
            $keys[12] => $this->getEmailVerifiedAt(),
            $keys[13] => $this->getMarkedForEmailVerificationAt(),
            $keys[14] => $this->getWarnedBeforeDeletionAt(),
            $keys[15] => $this->getCreatedAt(),
            $keys[16] => $this->getUpdatedAt(),
        ];
        if ($result[$keys[7]] instanceof \DateTimeInterface) {
            $result[$keys[7]] = $result[$keys[7]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[8]] instanceof \DateTimeInterface) {
            $result[$keys[8]] = $result[$keys[8]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[11]] instanceof \DateTimeInterface) {
            $result[$keys[11]] = $result[$keys[11]]->format('Y-m-d H:i:s.u');
        }

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

        if ($result[$keys[16]] instanceof \DateTimeInterface) {
            $result[$keys[16]] = $result[$keys[16]]->format('Y-m-d H:i:s.u');
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
        $pos = AxysAccountTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setEmail($value);
                break;
            case 2:
                $this->setPassword($value);
                break;
            case 3:
                $this->setKey($value);
                break;
            case 4:
                $this->setEmailKey($value);
                break;
            case 5:
                $this->setUsername($value);
                break;
            case 6:
                $this->setSlug($value);
                break;
            case 7:
                $this->setSignupDate($value);
                break;
            case 8:
                $this->setLoginDate($value);
                break;
            case 9:
                $this->setFirstName($value);
                break;
            case 10:
                $this->setLastName($value);
                break;
            case 11:
                $this->setUpdate($value);
                break;
            case 12:
                $this->setEmailVerifiedAt($value);
                break;
            case 13:
                $this->setMarkedForEmailVerificationAt($value);
                break;
            case 14:
                $this->setWarnedBeforeDeletionAt($value);
                break;
            case 15:
                $this->setCreatedAt($value);
                break;
            case 16:
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
        $keys = AxysAccountTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setEmail($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setPassword($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setKey($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setEmailKey($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setUsername($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setSlug($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setSignupDate($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setLoginDate($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setFirstName($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setLastName($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setUpdate($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setEmailVerifiedAt($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setMarkedForEmailVerificationAt($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setWarnedBeforeDeletionAt($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setCreatedAt($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setUpdatedAt($arr[$keys[16]]);
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
        $criteria = new Criteria(AxysAccountTableMap::DATABASE_NAME);

        if ($this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_ID)) {
            $criteria->add(AxysAccountTableMap::COL_AXYS_ACCOUNT_ID, $this->axys_account_id);
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_EMAIL)) {
            $criteria->add(AxysAccountTableMap::COL_AXYS_ACCOUNT_EMAIL, $this->axys_account_email);
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_PASSWORD)) {
            $criteria->add(AxysAccountTableMap::COL_AXYS_ACCOUNT_PASSWORD, $this->axys_account_password);
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_KEY)) {
            $criteria->add(AxysAccountTableMap::COL_AXYS_ACCOUNT_KEY, $this->axys_account_key);
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_EMAIL_KEY)) {
            $criteria->add(AxysAccountTableMap::COL_AXYS_ACCOUNT_EMAIL_KEY, $this->axys_account_email_key);
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_SCREEN_NAME)) {
            $criteria->add(AxysAccountTableMap::COL_AXYS_ACCOUNT_SCREEN_NAME, $this->axys_account_screen_name);
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_SLUG)) {
            $criteria->add(AxysAccountTableMap::COL_AXYS_ACCOUNT_SLUG, $this->axys_account_slug);
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_SIGNUP_DATE)) {
            $criteria->add(AxysAccountTableMap::COL_AXYS_ACCOUNT_SIGNUP_DATE, $this->axys_account_signup_date);
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_LOGIN_DATE)) {
            $criteria->add(AxysAccountTableMap::COL_AXYS_ACCOUNT_LOGIN_DATE, $this->axys_account_login_date);
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_FIRST_NAME)) {
            $criteria->add(AxysAccountTableMap::COL_AXYS_ACCOUNT_FIRST_NAME, $this->axys_account_first_name);
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_LAST_NAME)) {
            $criteria->add(AxysAccountTableMap::COL_AXYS_ACCOUNT_LAST_NAME, $this->axys_account_last_name);
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_UPDATE)) {
            $criteria->add(AxysAccountTableMap::COL_AXYS_ACCOUNT_UPDATE, $this->axys_account_update);
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_EMAIL_VERIFIED_AT)) {
            $criteria->add(AxysAccountTableMap::COL_EMAIL_VERIFIED_AT, $this->email_verified_at);
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_MARKED_FOR_EMAIL_VERIFICATION_AT)) {
            $criteria->add(AxysAccountTableMap::COL_MARKED_FOR_EMAIL_VERIFICATION_AT, $this->marked_for_email_verification_at);
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_WARNED_BEFORE_DELETION_AT)) {
            $criteria->add(AxysAccountTableMap::COL_WARNED_BEFORE_DELETION_AT, $this->warned_before_deletion_at);
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_CREATED)) {
            $criteria->add(AxysAccountTableMap::COL_AXYS_ACCOUNT_CREATED, $this->axys_account_created);
        }
        if ($this->isColumnModified(AxysAccountTableMap::COL_AXYS_ACCOUNT_UPDATED)) {
            $criteria->add(AxysAccountTableMap::COL_AXYS_ACCOUNT_UPDATED, $this->axys_account_updated);
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
        $criteria = ChildAxysAccountQuery::create();
        $criteria->add(AxysAccountTableMap::COL_AXYS_ACCOUNT_ID, $this->axys_account_id);

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
     * Generic method to set the primary key (axys_account_id column).
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
     * @param object $copyObj An object of \Model\AxysAccount (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setEmail($this->getEmail());
        $copyObj->setPassword($this->getPassword());
        $copyObj->setKey($this->getKey());
        $copyObj->setEmailKey($this->getEmailKey());
        $copyObj->setUsername($this->getUsername());
        $copyObj->setSlug($this->getSlug());
        $copyObj->setSignupDate($this->getSignupDate());
        $copyObj->setLoginDate($this->getLoginDate());
        $copyObj->setFirstName($this->getFirstName());
        $copyObj->setLastName($this->getLastName());
        $copyObj->setUpdate($this->getUpdate());
        $copyObj->setEmailVerifiedAt($this->getEmailVerifiedAt());
        $copyObj->setMarkedForEmailVerificationAt($this->getMarkedForEmailVerificationAt());
        $copyObj->setWarnedBeforeDeletionAt($this->getWarnedBeforeDeletionAt());
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
     * @return \Model\AxysAccount Clone of current object.
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
        $this->axys_account_id = null;
        $this->axys_account_email = null;
        $this->axys_account_password = null;
        $this->axys_account_key = null;
        $this->axys_account_email_key = null;
        $this->axys_account_screen_name = null;
        $this->axys_account_slug = null;
        $this->axys_account_signup_date = null;
        $this->axys_account_login_date = null;
        $this->axys_account_first_name = null;
        $this->axys_account_last_name = null;
        $this->axys_account_update = null;
        $this->email_verified_at = null;
        $this->marked_for_email_verification_at = null;
        $this->warned_before_deletion_at = null;
        $this->axys_account_created = null;
        $this->axys_account_updated = null;
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
        return (string) $this->exportTo(AxysAccountTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return $this The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[AxysAccountTableMap::COL_AXYS_ACCOUNT_UPDATED] = true;

        return $this;
    }

    // validate behavior

    /**
     * Configure validators constraints. The Validator object uses this method
     * to perform object validation.
     *
     * @param ClassMetadata $metadata
     */
    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('axys_account_email', new Email());
    }

    /**
     * Validates the object and all objects related to this table.
     *
     * @see        getValidationFailures()
     * @param ValidatorInterface|null $validator A Validator class instance
     * @return bool Whether all objects pass validation.
     */
    public function validate(ValidatorInterface $validator = null)
    {
        if (null === $validator) {
            $validator = new RecursiveValidator(
                new ExecutionContextFactory(new IdentityTranslator()),
                new LazyLoadingMetadataFactory(new StaticMethodLoader()),
                new ConstraintValidatorFactory()
            );
        }

        $failureMap = new ConstraintViolationList();

        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;


            $retval = $validator->validate($this);
            if (count($retval) > 0) {
                $failureMap->addAll($retval);
            }


            $this->alreadyInValidation = false;
        }

        $this->validationFailures = $failureMap;

        return (bool) (!(count($this->validationFailures) > 0));

    }

    /**
     * Gets any ConstraintViolation objects that resulted from last call to validate().
     *
     *
     * @return ConstraintViolationList
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
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
