<?php

namespace Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Model\Right as ChildRight;
use Model\RightQuery as ChildRightQuery;
use Model\Session as ChildSession;
use Model\SessionQuery as ChildSessionQuery;
use Model\User as ChildUser;
use Model\UserQuery as ChildUserQuery;
use Model\Map\RightTableMap;
use Model\Map\SessionTableMap;
use Model\Map\UserTableMap;
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
     */
    const TABLE_MAP = '\\Model\\Map\\UserTableMap';


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
     * The value for the id field.
     *
     * @var        int
     */
    protected $id;

    /**
     * The value for the email field.
     *
     * @var        string|null
     */
    protected $email;

    /**
     * The value for the user_password field.
     *
     * @var        string|null
     */
    protected $user_password;

    /**
     * The value for the user_key field.
     *
     * @var        string|null
     */
    protected $user_key;

    /**
     * The value for the email_key field.
     *
     * @var        string|null
     */
    protected $email_key;

    /**
     * The value for the facebook_uid field.
     *
     * @var        int|null
     */
    protected $facebook_uid;

    /**
     * The value for the user_screen_name field.
     *
     * @var        string|null
     */
    protected $user_screen_name;

    /**
     * The value for the user_slug field.
     *
     * @var        string|null
     */
    protected $user_slug;

    /**
     * The value for the user_wishlist_ship field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean|null
     */
    protected $user_wishlist_ship;

    /**
     * The value for the user_top field.
     *
     * @var        boolean|null
     */
    protected $user_top;

    /**
     * The value for the user_biblio field.
     *
     * @var        boolean|null
     */
    protected $user_biblio;

    /**
     * The value for the adresse_ip field.
     *
     * @var        string|null
     */
    protected $adresse_ip;

    /**
     * The value for the recaptcha_score field.
     *
     * @var        double|null
     */
    protected $recaptcha_score;

    /**
     * The value for the dateinscription field.
     *
     * @var        DateTime|null
     */
    protected $dateinscription;

    /**
     * The value for the dateconnexion field.
     *
     * @var        DateTime|null
     */
    protected $dateconnexion;

    /**
     * The value for the publisher_id field.
     *
     * @var        int|null
     */
    protected $publisher_id;

    /**
     * The value for the bookshop_id field.
     *
     * @var        int|null
     */
    protected $bookshop_id;

    /**
     * The value for the library_id field.
     *
     * @var        int|null
     */
    protected $library_id;

    /**
     * The value for the user_civilite field.
     *
     * @var        string|null
     */
    protected $user_civilite;

    /**
     * The value for the user_nom field.
     *
     * @var        string|null
     */
    protected $user_nom;

    /**
     * The value for the user_prenom field.
     *
     * @var        string|null
     */
    protected $user_prenom;

    /**
     * The value for the user_adresse1 field.
     *
     * @var        string|null
     */
    protected $user_adresse1;

    /**
     * The value for the user_adresse2 field.
     *
     * @var        string|null
     */
    protected $user_adresse2;

    /**
     * The value for the user_codepostal field.
     *
     * @var        string|null
     */
    protected $user_codepostal;

    /**
     * The value for the user_ville field.
     *
     * @var        string|null
     */
    protected $user_ville;

    /**
     * The value for the user_pays field.
     *
     * @var        string|null
     */
    protected $user_pays;

    /**
     * The value for the user_telephone field.
     *
     * @var        string|null
     */
    protected $user_telephone;

    /**
     * The value for the user_pref_articles_show field.
     *
     * @var        string|null
     */
    protected $user_pref_articles_show;

    /**
     * The value for the user_fb_id field.
     *
     * @var        string|null
     */
    protected $user_fb_id;

    /**
     * The value for the user_fb_token field.
     *
     * @var        string|null
     */
    protected $user_fb_token;

    /**
     * The value for the country_id field.
     *
     * @var        int|null
     */
    protected $country_id;

    /**
     * The value for the user_password_reset_token field.
     *
     * @var        string|null
     */
    protected $user_password_reset_token;

    /**
     * The value for the user_password_reset_token_created field.
     *
     * @var        DateTime|null
     */
    protected $user_password_reset_token_created;

    /**
     * The value for the user_update field.
     *
     * @var        DateTime|null
     */
    protected $user_update;

    /**
     * The value for the user_created field.
     *
     * @var        DateTime|null
     */
    protected $user_created;

    /**
     * The value for the user_updated field.
     *
     * @var        DateTime|null
     */
    protected $user_updated;

    /**
     * The value for the user_deleted field.
     *
     * @var        DateTime|null
     */
    protected $user_deleted;

    /**
     * The value for the user_deleted_why field.
     *
     * @var        string|null
     */
    protected $user_deleted_why;

    /**
     * @var        ObjectCollection|ChildRight[] Collection to store aggregation of ChildRight objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildRight> Collection to store aggregation of ChildRight objects.
     */
    protected $collRights;
    protected $collRightsPartial;

    /**
     * @var        ObjectCollection|ChildSession[] Collection to store aggregation of ChildSession objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildSession> Collection to store aggregation of ChildSession objects.
     */
    protected $collSessions;
    protected $collSessionsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
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
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildRight[]
     * @phpstan-var ObjectCollection&\Traversable<ChildRight>
     */
    protected $rightsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildSession[]
     * @phpstan-var ObjectCollection&\Traversable<ChildSession>
     */
    protected $sessionsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->user_wishlist_ship = false;
    }

    /**
     * Initializes internal state of Model\Base\User object.
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
            unset($this->modifiedColumns[$col]);
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>User</code> instance.  If
     * <code>obj</code> is an instance of <code>User</code>, delegates to
     * <code>equals(User)</code>.  Otherwise, returns <code>false</code>.
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
     * @param  string  $keyType                (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME, TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM. Defaults to TableMap::TYPE_PHPNAME.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray($keyType, $includeLazyLoadColumns, array(), true));
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
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * Get the [user_password] column value.
     *
     * @return string|null
     */
    public function getPassword()
    {
        return $this->user_password;
    }

    /**
     * Get the [user_key] column value.
     *
     * @return string|null
     */
    public function getKey()
    {
        return $this->user_key;
    }

    /**
     * Get the [email_key] column value.
     *
     * @return string|null
     */
    public function getEmailKey()
    {
        return $this->email_key;
    }

    /**
     * Get the [facebook_uid] column value.
     *
     * @return int|null
     */
    public function getFacebookUid()
    {
        return $this->facebook_uid;
    }

    /**
     * Get the [user_screen_name] column value.
     *
     * @return string|null
     */
    public function getUsername()
    {
        return $this->user_screen_name;
    }

    /**
     * Get the [user_slug] column value.
     *
     * @return string|null
     */
    public function getSlug()
    {
        return $this->user_slug;
    }

    /**
     * Get the [user_wishlist_ship] column value.
     *
     * @return boolean|null
     */
    public function getWishlistShip()
    {
        return $this->user_wishlist_ship;
    }

    /**
     * Get the [user_wishlist_ship] column value.
     *
     * @return boolean|null
     */
    public function isWishlistShip()
    {
        return $this->getWishlistShip();
    }

    /**
     * Get the [user_top] column value.
     *
     * @return boolean|null
     */
    public function getTop()
    {
        return $this->user_top;
    }

    /**
     * Get the [user_top] column value.
     *
     * @return boolean|null
     */
    public function isTop()
    {
        return $this->getTop();
    }

    /**
     * Get the [user_biblio] column value.
     *
     * @return boolean|null
     */
    public function getBiblio()
    {
        return $this->user_biblio;
    }

    /**
     * Get the [user_biblio] column value.
     *
     * @return boolean|null
     */
    public function isBiblio()
    {
        return $this->getBiblio();
    }

    /**
     * Get the [adresse_ip] column value.
     *
     * @return string|null
     */
    public function getAdresseIp()
    {
        return $this->adresse_ip;
    }

    /**
     * Get the [recaptcha_score] column value.
     *
     * @return double|null
     */
    public function getRecaptchaScore()
    {
        return $this->recaptcha_score;
    }

    /**
     * Get the [optionally formatted] temporal [dateinscription] column value.
     *
     *
     * @param string|null $format The date/time format string (either date()-style or strftime()-style).
     *   If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     *
     * @psalm-return ($format is null ? DateTime|null : string|null)
     */
    public function getDateinscription($format = null)
    {
        if ($format === null) {
            return $this->dateinscription;
        } else {
            return $this->dateinscription instanceof \DateTimeInterface ? $this->dateinscription->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [dateconnexion] column value.
     *
     *
     * @param string|null $format The date/time format string (either date()-style or strftime()-style).
     *   If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     *
     * @psalm-return ($format is null ? DateTime|null : string|null)
     */
    public function getDateconnexion($format = null)
    {
        if ($format === null) {
            return $this->dateconnexion;
        } else {
            return $this->dateconnexion instanceof \DateTimeInterface ? $this->dateconnexion->format($format) : null;
        }
    }

    /**
     * Get the [publisher_id] column value.
     *
     * @return int|null
     */
    public function getPublisherId()
    {
        return $this->publisher_id;
    }

    /**
     * Get the [bookshop_id] column value.
     *
     * @return int|null
     */
    public function getBookshopId()
    {
        return $this->bookshop_id;
    }

    /**
     * Get the [library_id] column value.
     *
     * @return int|null
     */
    public function getLibraryId()
    {
        return $this->library_id;
    }

    /**
     * Get the [user_civilite] column value.
     *
     * @return string|null
     */
    public function getCivilite()
    {
        return $this->user_civilite;
    }

    /**
     * Get the [user_nom] column value.
     *
     * @return string|null
     */
    public function getNom()
    {
        return $this->user_nom;
    }

    /**
     * Get the [user_prenom] column value.
     *
     * @return string|null
     */
    public function getPrenom()
    {
        return $this->user_prenom;
    }

    /**
     * Get the [user_adresse1] column value.
     *
     * @return string|null
     */
    public function getAdresse1()
    {
        return $this->user_adresse1;
    }

    /**
     * Get the [user_adresse2] column value.
     *
     * @return string|null
     */
    public function getAdresse2()
    {
        return $this->user_adresse2;
    }

    /**
     * Get the [user_codepostal] column value.
     *
     * @return string|null
     */
    public function getCodepostal()
    {
        return $this->user_codepostal;
    }

    /**
     * Get the [user_ville] column value.
     *
     * @return string|null
     */
    public function getVille()
    {
        return $this->user_ville;
    }

    /**
     * Get the [user_pays] column value.
     *
     * @return string|null
     */
    public function getPays()
    {
        return $this->user_pays;
    }

    /**
     * Get the [user_telephone] column value.
     *
     * @return string|null
     */
    public function getTelephone()
    {
        return $this->user_telephone;
    }

    /**
     * Get the [user_pref_articles_show] column value.
     *
     * @return string|null
     */
    public function getPrefArticlesShow()
    {
        return $this->user_pref_articles_show;
    }

    /**
     * Get the [user_fb_id] column value.
     *
     * @return string|null
     */
    public function getFbId()
    {
        return $this->user_fb_id;
    }

    /**
     * Get the [user_fb_token] column value.
     *
     * @return string|null
     */
    public function getFbToken()
    {
        return $this->user_fb_token;
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
     * Get the [user_password_reset_token] column value.
     *
     * @return string|null
     */
    public function getPasswordResetToken()
    {
        return $this->user_password_reset_token;
    }

    /**
     * Get the [optionally formatted] temporal [user_password_reset_token_created] column value.
     *
     *
     * @param string|null $format The date/time format string (either date()-style or strftime()-style).
     *   If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     *
     * @psalm-return ($format is null ? DateTime|null : string|null)
     */
    public function getPasswordResetTokenCreated($format = null)
    {
        if ($format === null) {
            return $this->user_password_reset_token_created;
        } else {
            return $this->user_password_reset_token_created instanceof \DateTimeInterface ? $this->user_password_reset_token_created->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [user_update] column value.
     *
     *
     * @param string|null $format The date/time format string (either date()-style or strftime()-style).
     *   If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     *
     * @psalm-return ($format is null ? DateTime|null : string|null)
     */
    public function getUpdate($format = null)
    {
        if ($format === null) {
            return $this->user_update;
        } else {
            return $this->user_update instanceof \DateTimeInterface ? $this->user_update->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [user_created] column value.
     *
     *
     * @param string|null $format The date/time format string (either date()-style or strftime()-style).
     *   If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     *
     * @psalm-return ($format is null ? DateTime|null : string|null)
     */
    public function getCreatedAt($format = null)
    {
        if ($format === null) {
            return $this->user_created;
        } else {
            return $this->user_created instanceof \DateTimeInterface ? $this->user_created->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [user_updated] column value.
     *
     *
     * @param string|null $format The date/time format string (either date()-style or strftime()-style).
     *   If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     *
     * @psalm-return ($format is null ? DateTime|null : string|null)
     */
    public function getUpdatedAt($format = null)
    {
        if ($format === null) {
            return $this->user_updated;
        } else {
            return $this->user_updated instanceof \DateTimeInterface ? $this->user_updated->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [user_deleted] column value.
     *
     *
     * @param string|null $format The date/time format string (either date()-style or strftime()-style).
     *   If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     *
     * @psalm-return ($format is null ? DateTime|null : string|null)
     */
    public function getDeletedAt($format = null)
    {
        if ($format === null) {
            return $this->user_deleted;
        } else {
            return $this->user_deleted instanceof \DateTimeInterface ? $this->user_deleted->format($format) : null;
        }
    }

    /**
     * Get the [user_deleted_why] column value.
     *
     * @return string|null
     */
    public function getDeletedWhy()
    {
        return $this->user_deleted_why;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v New value
     * @return $this|\Model\User The current object (for fluent API support)
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
    } // setId()

    /**
     * Set the value of [email] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\User The current object (for fluent API support)
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
    } // setEmail()

    /**
     * Set the value of [user_password] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setPassword($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->user_password !== $v) {
            $this->user_password = $v;
            $this->modifiedColumns[UserTableMap::COL_USER_PASSWORD] = true;
        }

        return $this;
    } // setPassword()

    /**
     * Set the value of [user_key] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setKey($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->user_key !== $v) {
            $this->user_key = $v;
            $this->modifiedColumns[UserTableMap::COL_USER_KEY] = true;
        }

        return $this;
    } // setKey()

    /**
     * Set the value of [email_key] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setEmailKey($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email_key !== $v) {
            $this->email_key = $v;
            $this->modifiedColumns[UserTableMap::COL_EMAIL_KEY] = true;
        }

        return $this;
    } // setEmailKey()

    /**
     * Set the value of [facebook_uid] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setFacebookUid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->facebook_uid !== $v) {
            $this->facebook_uid = $v;
            $this->modifiedColumns[UserTableMap::COL_FACEBOOK_UID] = true;
        }

        return $this;
    } // setFacebookUid()

    /**
     * Set the value of [user_screen_name] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setUsername($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->user_screen_name !== $v) {
            $this->user_screen_name = $v;
            $this->modifiedColumns[UserTableMap::COL_USER_SCREEN_NAME] = true;
        }

        return $this;
    } // setUsername()

    /**
     * Set the value of [user_slug] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setSlug($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->user_slug !== $v) {
            $this->user_slug = $v;
            $this->modifiedColumns[UserTableMap::COL_USER_SLUG] = true;
        }

        return $this;
    } // setSlug()

    /**
     * Sets the value of the [user_wishlist_ship] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string|null $v The new value
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setWishlistShip($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->user_wishlist_ship !== $v) {
            $this->user_wishlist_ship = $v;
            $this->modifiedColumns[UserTableMap::COL_USER_WISHLIST_SHIP] = true;
        }

        return $this;
    } // setWishlistShip()

    /**
     * Sets the value of the [user_top] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string|null $v The new value
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setTop($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->user_top !== $v) {
            $this->user_top = $v;
            $this->modifiedColumns[UserTableMap::COL_USER_TOP] = true;
        }

        return $this;
    } // setTop()

    /**
     * Sets the value of the [user_biblio] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string|null $v The new value
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setBiblio($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->user_biblio !== $v) {
            $this->user_biblio = $v;
            $this->modifiedColumns[UserTableMap::COL_USER_BIBLIO] = true;
        }

        return $this;
    } // setBiblio()

    /**
     * Set the value of [adresse_ip] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setAdresseIp($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->adresse_ip !== $v) {
            $this->adresse_ip = $v;
            $this->modifiedColumns[UserTableMap::COL_ADRESSE_IP] = true;
        }

        return $this;
    } // setAdresseIp()

    /**
     * Set the value of [recaptcha_score] column.
     *
     * @param double|null $v New value
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setRecaptchaScore($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->recaptcha_score !== $v) {
            $this->recaptcha_score = $v;
            $this->modifiedColumns[UserTableMap::COL_RECAPTCHA_SCORE] = true;
        }

        return $this;
    } // setRecaptchaScore()

    /**
     * Sets the value of [dateinscription] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setDateinscription($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->dateinscription !== null || $dt !== null) {
            if ($this->dateinscription === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->dateinscription->format("Y-m-d H:i:s.u")) {
                $this->dateinscription = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_DATEINSCRIPTION] = true;
            }
        } // if either are not null

        return $this;
    } // setDateinscription()

    /**
     * Sets the value of [dateconnexion] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setDateconnexion($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->dateconnexion !== null || $dt !== null) {
            if ($this->dateconnexion === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->dateconnexion->format("Y-m-d H:i:s.u")) {
                $this->dateconnexion = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_DATECONNEXION] = true;
            }
        } // if either are not null

        return $this;
    } // setDateconnexion()

    /**
     * Set the value of [publisher_id] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setPublisherId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->publisher_id !== $v) {
            $this->publisher_id = $v;
            $this->modifiedColumns[UserTableMap::COL_PUBLISHER_ID] = true;
        }

        return $this;
    } // setPublisherId()

    /**
     * Set the value of [bookshop_id] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setBookshopId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->bookshop_id !== $v) {
            $this->bookshop_id = $v;
            $this->modifiedColumns[UserTableMap::COL_BOOKSHOP_ID] = true;
        }

        return $this;
    } // setBookshopId()

    /**
     * Set the value of [library_id] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setLibraryId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->library_id !== $v) {
            $this->library_id = $v;
            $this->modifiedColumns[UserTableMap::COL_LIBRARY_ID] = true;
        }

        return $this;
    } // setLibraryId()

    /**
     * Set the value of [user_civilite] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setCivilite($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->user_civilite !== $v) {
            $this->user_civilite = $v;
            $this->modifiedColumns[UserTableMap::COL_USER_CIVILITE] = true;
        }

        return $this;
    } // setCivilite()

    /**
     * Set the value of [user_nom] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setNom($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->user_nom !== $v) {
            $this->user_nom = $v;
            $this->modifiedColumns[UserTableMap::COL_USER_NOM] = true;
        }

        return $this;
    } // setNom()

    /**
     * Set the value of [user_prenom] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setPrenom($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->user_prenom !== $v) {
            $this->user_prenom = $v;
            $this->modifiedColumns[UserTableMap::COL_USER_PRENOM] = true;
        }

        return $this;
    } // setPrenom()

    /**
     * Set the value of [user_adresse1] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setAdresse1($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->user_adresse1 !== $v) {
            $this->user_adresse1 = $v;
            $this->modifiedColumns[UserTableMap::COL_USER_ADRESSE1] = true;
        }

        return $this;
    } // setAdresse1()

    /**
     * Set the value of [user_adresse2] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setAdresse2($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->user_adresse2 !== $v) {
            $this->user_adresse2 = $v;
            $this->modifiedColumns[UserTableMap::COL_USER_ADRESSE2] = true;
        }

        return $this;
    } // setAdresse2()

    /**
     * Set the value of [user_codepostal] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setCodepostal($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->user_codepostal !== $v) {
            $this->user_codepostal = $v;
            $this->modifiedColumns[UserTableMap::COL_USER_CODEPOSTAL] = true;
        }

        return $this;
    } // setCodepostal()

    /**
     * Set the value of [user_ville] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setVille($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->user_ville !== $v) {
            $this->user_ville = $v;
            $this->modifiedColumns[UserTableMap::COL_USER_VILLE] = true;
        }

        return $this;
    } // setVille()

    /**
     * Set the value of [user_pays] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setPays($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->user_pays !== $v) {
            $this->user_pays = $v;
            $this->modifiedColumns[UserTableMap::COL_USER_PAYS] = true;
        }

        return $this;
    } // setPays()

    /**
     * Set the value of [user_telephone] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setTelephone($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->user_telephone !== $v) {
            $this->user_telephone = $v;
            $this->modifiedColumns[UserTableMap::COL_USER_TELEPHONE] = true;
        }

        return $this;
    } // setTelephone()

    /**
     * Set the value of [user_pref_articles_show] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setPrefArticlesShow($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->user_pref_articles_show !== $v) {
            $this->user_pref_articles_show = $v;
            $this->modifiedColumns[UserTableMap::COL_USER_PREF_ARTICLES_SHOW] = true;
        }

        return $this;
    } // setPrefArticlesShow()

    /**
     * Set the value of [user_fb_id] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setFbId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->user_fb_id !== $v) {
            $this->user_fb_id = $v;
            $this->modifiedColumns[UserTableMap::COL_USER_FB_ID] = true;
        }

        return $this;
    } // setFbId()

    /**
     * Set the value of [user_fb_token] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setFbToken($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->user_fb_token !== $v) {
            $this->user_fb_token = $v;
            $this->modifiedColumns[UserTableMap::COL_USER_FB_TOKEN] = true;
        }

        return $this;
    } // setFbToken()

    /**
     * Set the value of [country_id] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setCountryId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->country_id !== $v) {
            $this->country_id = $v;
            $this->modifiedColumns[UserTableMap::COL_COUNTRY_ID] = true;
        }

        return $this;
    } // setCountryId()

    /**
     * Set the value of [user_password_reset_token] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setPasswordResetToken($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->user_password_reset_token !== $v) {
            $this->user_password_reset_token = $v;
            $this->modifiedColumns[UserTableMap::COL_USER_PASSWORD_RESET_TOKEN] = true;
        }

        return $this;
    } // setPasswordResetToken()

    /**
     * Sets the value of [user_password_reset_token_created] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setPasswordResetTokenCreated($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->user_password_reset_token_created !== null || $dt !== null) {
            if ($this->user_password_reset_token_created === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->user_password_reset_token_created->format("Y-m-d H:i:s.u")) {
                $this->user_password_reset_token_created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_USER_PASSWORD_RESET_TOKEN_CREATED] = true;
            }
        } // if either are not null

        return $this;
    } // setPasswordResetTokenCreated()

    /**
     * Sets the value of [user_update] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setUpdate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->user_update !== null || $dt !== null) {
            if ($this->user_update === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->user_update->format("Y-m-d H:i:s.u")) {
                $this->user_update = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_USER_UPDATE] = true;
            }
        } // if either are not null

        return $this;
    } // setUpdate()

    /**
     * Sets the value of [user_created] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->user_created !== null || $dt !== null) {
            if ($this->user_created === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->user_created->format("Y-m-d H:i:s.u")) {
                $this->user_created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_USER_CREATED] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [user_updated] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->user_updated !== null || $dt !== null) {
            if ($this->user_updated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->user_updated->format("Y-m-d H:i:s.u")) {
                $this->user_updated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_USER_UPDATED] = true;
            }
        } // if either are not null

        return $this;
    } // setUpdatedAt()

    /**
     * Sets the value of [user_deleted] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setDeletedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->user_deleted !== null || $dt !== null) {
            if ($this->user_deleted === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->user_deleted->format("Y-m-d H:i:s.u")) {
                $this->user_deleted = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_USER_DELETED] = true;
            }
        } // if either are not null

        return $this;
    } // setDeletedAt()

    /**
     * Set the value of [user_deleted_why] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\User The current object (for fluent API support)
     */
    public function setDeletedWhy($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->user_deleted_why !== $v) {
            $this->user_deleted_why = $v;
            $this->modifiedColumns[UserTableMap::COL_USER_DELETED_WHY] = true;
        }

        return $this;
    } // setDeletedWhy()

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
            if ($this->user_wishlist_ship !== false) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : UserTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : UserTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : UserTableMap::translateFieldName('Password', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_password = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : UserTableMap::translateFieldName('Key', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_key = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : UserTableMap::translateFieldName('EmailKey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email_key = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : UserTableMap::translateFieldName('FacebookUid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->facebook_uid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : UserTableMap::translateFieldName('Username', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_screen_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : UserTableMap::translateFieldName('Slug', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_slug = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : UserTableMap::translateFieldName('WishlistShip', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_wishlist_ship = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : UserTableMap::translateFieldName('Top', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_top = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : UserTableMap::translateFieldName('Biblio', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_biblio = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : UserTableMap::translateFieldName('AdresseIp', TableMap::TYPE_PHPNAME, $indexType)];
            $this->adresse_ip = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : UserTableMap::translateFieldName('RecaptchaScore', TableMap::TYPE_PHPNAME, $indexType)];
            $this->recaptcha_score = (null !== $col) ? (double) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : UserTableMap::translateFieldName('Dateinscription', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->dateinscription = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : UserTableMap::translateFieldName('Dateconnexion', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->dateconnexion = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : UserTableMap::translateFieldName('PublisherId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : UserTableMap::translateFieldName('BookshopId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bookshop_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 17 + $startcol : UserTableMap::translateFieldName('LibraryId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->library_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 18 + $startcol : UserTableMap::translateFieldName('Civilite', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_civilite = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 19 + $startcol : UserTableMap::translateFieldName('Nom', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_nom = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 20 + $startcol : UserTableMap::translateFieldName('Prenom', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_prenom = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 21 + $startcol : UserTableMap::translateFieldName('Adresse1', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_adresse1 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 22 + $startcol : UserTableMap::translateFieldName('Adresse2', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_adresse2 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 23 + $startcol : UserTableMap::translateFieldName('Codepostal', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_codepostal = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 24 + $startcol : UserTableMap::translateFieldName('Ville', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_ville = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 25 + $startcol : UserTableMap::translateFieldName('Pays', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_pays = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 26 + $startcol : UserTableMap::translateFieldName('Telephone', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_telephone = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 27 + $startcol : UserTableMap::translateFieldName('PrefArticlesShow', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_pref_articles_show = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 28 + $startcol : UserTableMap::translateFieldName('FbId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_fb_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 29 + $startcol : UserTableMap::translateFieldName('FbToken', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_fb_token = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 30 + $startcol : UserTableMap::translateFieldName('CountryId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->country_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 31 + $startcol : UserTableMap::translateFieldName('PasswordResetToken', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_password_reset_token = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 32 + $startcol : UserTableMap::translateFieldName('PasswordResetTokenCreated', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->user_password_reset_token_created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 33 + $startcol : UserTableMap::translateFieldName('Update', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->user_update = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 34 + $startcol : UserTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->user_created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 35 + $startcol : UserTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->user_updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 36 + $startcol : UserTableMap::translateFieldName('DeletedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->user_deleted = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 37 + $startcol : UserTableMap::translateFieldName('DeletedWhy', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_deleted_why = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 38; // 38 = UserTableMap::NUM_HYDRATE_COLUMNS.

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

            $this->collRights = null;

            $this->collSessions = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see User::setDeleted()
     * @see User::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
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
                if (!$this->isColumnModified(UserTableMap::COL_USER_CREATED)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(UserTableMap::COL_USER_UPDATED)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(UserTableMap::COL_USER_UPDATED)) {
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

        $this->modifiedColumns[UserTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UserTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'Email';
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_PASSWORD)) {
            $modifiedColumns[':p' . $index++]  = 'user_password';
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_KEY)) {
            $modifiedColumns[':p' . $index++]  = 'user_key';
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL_KEY)) {
            $modifiedColumns[':p' . $index++]  = 'email_key';
        }
        if ($this->isColumnModified(UserTableMap::COL_FACEBOOK_UID)) {
            $modifiedColumns[':p' . $index++]  = 'facebook_uid';
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_SCREEN_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'user_screen_name';
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_SLUG)) {
            $modifiedColumns[':p' . $index++]  = 'user_slug';
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_WISHLIST_SHIP)) {
            $modifiedColumns[':p' . $index++]  = 'user_wishlist_ship';
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_TOP)) {
            $modifiedColumns[':p' . $index++]  = 'user_top';
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_BIBLIO)) {
            $modifiedColumns[':p' . $index++]  = 'user_biblio';
        }
        if ($this->isColumnModified(UserTableMap::COL_ADRESSE_IP)) {
            $modifiedColumns[':p' . $index++]  = 'adresse_ip';
        }
        if ($this->isColumnModified(UserTableMap::COL_RECAPTCHA_SCORE)) {
            $modifiedColumns[':p' . $index++]  = 'recaptcha_score';
        }
        if ($this->isColumnModified(UserTableMap::COL_DATEINSCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = 'DateInscription';
        }
        if ($this->isColumnModified(UserTableMap::COL_DATECONNEXION)) {
            $modifiedColumns[':p' . $index++]  = 'DateConnexion';
        }
        if ($this->isColumnModified(UserTableMap::COL_PUBLISHER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_id';
        }
        if ($this->isColumnModified(UserTableMap::COL_BOOKSHOP_ID)) {
            $modifiedColumns[':p' . $index++]  = 'bookshop_id';
        }
        if ($this->isColumnModified(UserTableMap::COL_LIBRARY_ID)) {
            $modifiedColumns[':p' . $index++]  = 'library_id';
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_CIVILITE)) {
            $modifiedColumns[':p' . $index++]  = 'user_civilite';
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_NOM)) {
            $modifiedColumns[':p' . $index++]  = 'user_nom';
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_PRENOM)) {
            $modifiedColumns[':p' . $index++]  = 'user_prenom';
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_ADRESSE1)) {
            $modifiedColumns[':p' . $index++]  = 'user_adresse1';
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_ADRESSE2)) {
            $modifiedColumns[':p' . $index++]  = 'user_adresse2';
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_CODEPOSTAL)) {
            $modifiedColumns[':p' . $index++]  = 'user_codepostal';
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_VILLE)) {
            $modifiedColumns[':p' . $index++]  = 'user_ville';
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_PAYS)) {
            $modifiedColumns[':p' . $index++]  = 'user_pays';
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_TELEPHONE)) {
            $modifiedColumns[':p' . $index++]  = 'user_telephone';
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_PREF_ARTICLES_SHOW)) {
            $modifiedColumns[':p' . $index++]  = 'user_pref_articles_show';
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_FB_ID)) {
            $modifiedColumns[':p' . $index++]  = 'user_fb_id';
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_FB_TOKEN)) {
            $modifiedColumns[':p' . $index++]  = 'user_fb_token';
        }
        if ($this->isColumnModified(UserTableMap::COL_COUNTRY_ID)) {
            $modifiedColumns[':p' . $index++]  = 'country_id';
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_PASSWORD_RESET_TOKEN)) {
            $modifiedColumns[':p' . $index++]  = 'user_password_reset_token';
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_PASSWORD_RESET_TOKEN_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'user_password_reset_token_created';
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_UPDATE)) {
            $modifiedColumns[':p' . $index++]  = 'user_update';
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'user_created';
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = 'user_updated';
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_DELETED)) {
            $modifiedColumns[':p' . $index++]  = 'user_deleted';
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_DELETED_WHY)) {
            $modifiedColumns[':p' . $index++]  = 'user_deleted_why';
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
                    case 'Email':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case 'user_password':
                        $stmt->bindValue($identifier, $this->user_password, PDO::PARAM_STR);
                        break;
                    case 'user_key':
                        $stmt->bindValue($identifier, $this->user_key, PDO::PARAM_STR);
                        break;
                    case 'email_key':
                        $stmt->bindValue($identifier, $this->email_key, PDO::PARAM_STR);
                        break;
                    case 'facebook_uid':
                        $stmt->bindValue($identifier, $this->facebook_uid, PDO::PARAM_INT);
                        break;
                    case 'user_screen_name':
                        $stmt->bindValue($identifier, $this->user_screen_name, PDO::PARAM_STR);
                        break;
                    case 'user_slug':
                        $stmt->bindValue($identifier, $this->user_slug, PDO::PARAM_STR);
                        break;
                    case 'user_wishlist_ship':
                        $stmt->bindValue($identifier, (int) $this->user_wishlist_ship, PDO::PARAM_INT);
                        break;
                    case 'user_top':
                        $stmt->bindValue($identifier, (int) $this->user_top, PDO::PARAM_INT);
                        break;
                    case 'user_biblio':
                        $stmt->bindValue($identifier, (int) $this->user_biblio, PDO::PARAM_INT);
                        break;
                    case 'adresse_ip':
                        $stmt->bindValue($identifier, $this->adresse_ip, PDO::PARAM_STR);
                        break;
                    case 'recaptcha_score':
                        $stmt->bindValue($identifier, $this->recaptcha_score, PDO::PARAM_STR);
                        break;
                    case 'DateInscription':
                        $stmt->bindValue($identifier, $this->dateinscription ? $this->dateinscription->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'DateConnexion':
                        $stmt->bindValue($identifier, $this->dateconnexion ? $this->dateconnexion->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'publisher_id':
                        $stmt->bindValue($identifier, $this->publisher_id, PDO::PARAM_INT);
                        break;
                    case 'bookshop_id':
                        $stmt->bindValue($identifier, $this->bookshop_id, PDO::PARAM_INT);
                        break;
                    case 'library_id':
                        $stmt->bindValue($identifier, $this->library_id, PDO::PARAM_INT);
                        break;
                    case 'user_civilite':
                        $stmt->bindValue($identifier, $this->user_civilite, PDO::PARAM_STR);
                        break;
                    case 'user_nom':
                        $stmt->bindValue($identifier, $this->user_nom, PDO::PARAM_STR);
                        break;
                    case 'user_prenom':
                        $stmt->bindValue($identifier, $this->user_prenom, PDO::PARAM_STR);
                        break;
                    case 'user_adresse1':
                        $stmt->bindValue($identifier, $this->user_adresse1, PDO::PARAM_STR);
                        break;
                    case 'user_adresse2':
                        $stmt->bindValue($identifier, $this->user_adresse2, PDO::PARAM_STR);
                        break;
                    case 'user_codepostal':
                        $stmt->bindValue($identifier, $this->user_codepostal, PDO::PARAM_STR);
                        break;
                    case 'user_ville':
                        $stmt->bindValue($identifier, $this->user_ville, PDO::PARAM_STR);
                        break;
                    case 'user_pays':
                        $stmt->bindValue($identifier, $this->user_pays, PDO::PARAM_STR);
                        break;
                    case 'user_telephone':
                        $stmt->bindValue($identifier, $this->user_telephone, PDO::PARAM_STR);
                        break;
                    case 'user_pref_articles_show':
                        $stmt->bindValue($identifier, $this->user_pref_articles_show, PDO::PARAM_STR);
                        break;
                    case 'user_fb_id':
                        $stmt->bindValue($identifier, $this->user_fb_id, PDO::PARAM_INT);
                        break;
                    case 'user_fb_token':
                        $stmt->bindValue($identifier, $this->user_fb_token, PDO::PARAM_STR);
                        break;
                    case 'country_id':
                        $stmt->bindValue($identifier, $this->country_id, PDO::PARAM_INT);
                        break;
                    case 'user_password_reset_token':
                        $stmt->bindValue($identifier, $this->user_password_reset_token, PDO::PARAM_STR);
                        break;
                    case 'user_password_reset_token_created':
                        $stmt->bindValue($identifier, $this->user_password_reset_token_created ? $this->user_password_reset_token_created->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'user_update':
                        $stmt->bindValue($identifier, $this->user_update ? $this->user_update->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'user_created':
                        $stmt->bindValue($identifier, $this->user_created ? $this->user_created->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'user_updated':
                        $stmt->bindValue($identifier, $this->user_updated ? $this->user_updated->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'user_deleted':
                        $stmt->bindValue($identifier, $this->user_deleted ? $this->user_deleted->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'user_deleted_why':
                        $stmt->bindValue($identifier, $this->user_deleted_why, PDO::PARAM_STR);
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
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getEmail();
                break;
            case 2:
                return $this->getPassword();
                break;
            case 3:
                return $this->getKey();
                break;
            case 4:
                return $this->getEmailKey();
                break;
            case 5:
                return $this->getFacebookUid();
                break;
            case 6:
                return $this->getUsername();
                break;
            case 7:
                return $this->getSlug();
                break;
            case 8:
                return $this->getWishlistShip();
                break;
            case 9:
                return $this->getTop();
                break;
            case 10:
                return $this->getBiblio();
                break;
            case 11:
                return $this->getAdresseIp();
                break;
            case 12:
                return $this->getRecaptchaScore();
                break;
            case 13:
                return $this->getDateinscription();
                break;
            case 14:
                return $this->getDateconnexion();
                break;
            case 15:
                return $this->getPublisherId();
                break;
            case 16:
                return $this->getBookshopId();
                break;
            case 17:
                return $this->getLibraryId();
                break;
            case 18:
                return $this->getCivilite();
                break;
            case 19:
                return $this->getNom();
                break;
            case 20:
                return $this->getPrenom();
                break;
            case 21:
                return $this->getAdresse1();
                break;
            case 22:
                return $this->getAdresse2();
                break;
            case 23:
                return $this->getCodepostal();
                break;
            case 24:
                return $this->getVille();
                break;
            case 25:
                return $this->getPays();
                break;
            case 26:
                return $this->getTelephone();
                break;
            case 27:
                return $this->getPrefArticlesShow();
                break;
            case 28:
                return $this->getFbId();
                break;
            case 29:
                return $this->getFbToken();
                break;
            case 30:
                return $this->getCountryId();
                break;
            case 31:
                return $this->getPasswordResetToken();
                break;
            case 32:
                return $this->getPasswordResetTokenCreated();
                break;
            case 33:
                return $this->getUpdate();
                break;
            case 34:
                return $this->getCreatedAt();
                break;
            case 35:
                return $this->getUpdatedAt();
                break;
            case 36:
                return $this->getDeletedAt();
                break;
            case 37:
                return $this->getDeletedWhy();
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
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['User'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['User'][$this->hashCode()] = true;
        $keys = UserTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getEmail(),
            $keys[2] => $this->getPassword(),
            $keys[3] => $this->getKey(),
            $keys[4] => $this->getEmailKey(),
            $keys[5] => $this->getFacebookUid(),
            $keys[6] => $this->getUsername(),
            $keys[7] => $this->getSlug(),
            $keys[8] => $this->getWishlistShip(),
            $keys[9] => $this->getTop(),
            $keys[10] => $this->getBiblio(),
            $keys[11] => $this->getAdresseIp(),
            $keys[12] => $this->getRecaptchaScore(),
            $keys[13] => $this->getDateinscription(),
            $keys[14] => $this->getDateconnexion(),
            $keys[15] => $this->getPublisherId(),
            $keys[16] => $this->getBookshopId(),
            $keys[17] => $this->getLibraryId(),
            $keys[18] => $this->getCivilite(),
            $keys[19] => $this->getNom(),
            $keys[20] => $this->getPrenom(),
            $keys[21] => $this->getAdresse1(),
            $keys[22] => $this->getAdresse2(),
            $keys[23] => $this->getCodepostal(),
            $keys[24] => $this->getVille(),
            $keys[25] => $this->getPays(),
            $keys[26] => $this->getTelephone(),
            $keys[27] => $this->getPrefArticlesShow(),
            $keys[28] => $this->getFbId(),
            $keys[29] => $this->getFbToken(),
            $keys[30] => $this->getCountryId(),
            $keys[31] => $this->getPasswordResetToken(),
            $keys[32] => $this->getPasswordResetTokenCreated(),
            $keys[33] => $this->getUpdate(),
            $keys[34] => $this->getCreatedAt(),
            $keys[35] => $this->getUpdatedAt(),
            $keys[36] => $this->getDeletedAt(),
            $keys[37] => $this->getDeletedWhy(),
        );
        if ($result[$keys[13]] instanceof \DateTimeInterface) {
            $result[$keys[13]] = $result[$keys[13]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[14]] instanceof \DateTimeInterface) {
            $result[$keys[14]] = $result[$keys[14]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[32]] instanceof \DateTimeInterface) {
            $result[$keys[32]] = $result[$keys[32]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[33]] instanceof \DateTimeInterface) {
            $result[$keys[33]] = $result[$keys[33]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[34]] instanceof \DateTimeInterface) {
            $result[$keys[34]] = $result[$keys[34]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[35]] instanceof \DateTimeInterface) {
            $result[$keys[35]] = $result[$keys[35]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[36]] instanceof \DateTimeInterface) {
            $result[$keys[36]] = $result[$keys[36]]->format('Y-m-d H:i:s.u');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
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
     * @return $this|\Model\User
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Model\User
     */
    public function setByPosition($pos, $value)
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
                $this->setFacebookUid($value);
                break;
            case 6:
                $this->setUsername($value);
                break;
            case 7:
                $this->setSlug($value);
                break;
            case 8:
                $this->setWishlistShip($value);
                break;
            case 9:
                $this->setTop($value);
                break;
            case 10:
                $this->setBiblio($value);
                break;
            case 11:
                $this->setAdresseIp($value);
                break;
            case 12:
                $this->setRecaptchaScore($value);
                break;
            case 13:
                $this->setDateinscription($value);
                break;
            case 14:
                $this->setDateconnexion($value);
                break;
            case 15:
                $this->setPublisherId($value);
                break;
            case 16:
                $this->setBookshopId($value);
                break;
            case 17:
                $this->setLibraryId($value);
                break;
            case 18:
                $this->setCivilite($value);
                break;
            case 19:
                $this->setNom($value);
                break;
            case 20:
                $this->setPrenom($value);
                break;
            case 21:
                $this->setAdresse1($value);
                break;
            case 22:
                $this->setAdresse2($value);
                break;
            case 23:
                $this->setCodepostal($value);
                break;
            case 24:
                $this->setVille($value);
                break;
            case 25:
                $this->setPays($value);
                break;
            case 26:
                $this->setTelephone($value);
                break;
            case 27:
                $this->setPrefArticlesShow($value);
                break;
            case 28:
                $this->setFbId($value);
                break;
            case 29:
                $this->setFbToken($value);
                break;
            case 30:
                $this->setCountryId($value);
                break;
            case 31:
                $this->setPasswordResetToken($value);
                break;
            case 32:
                $this->setPasswordResetTokenCreated($value);
                break;
            case 33:
                $this->setUpdate($value);
                break;
            case 34:
                $this->setCreatedAt($value);
                break;
            case 35:
                $this->setUpdatedAt($value);
                break;
            case 36:
                $this->setDeletedAt($value);
                break;
            case 37:
                $this->setDeletedWhy($value);
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
     * @return     $this|\Model\User
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = UserTableMap::getFieldNames($keyType);

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
            $this->setFacebookUid($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setUsername($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setSlug($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setWishlistShip($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setTop($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setBiblio($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setAdresseIp($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setRecaptchaScore($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setDateinscription($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setDateconnexion($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setPublisherId($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setBookshopId($arr[$keys[16]]);
        }
        if (array_key_exists($keys[17], $arr)) {
            $this->setLibraryId($arr[$keys[17]]);
        }
        if (array_key_exists($keys[18], $arr)) {
            $this->setCivilite($arr[$keys[18]]);
        }
        if (array_key_exists($keys[19], $arr)) {
            $this->setNom($arr[$keys[19]]);
        }
        if (array_key_exists($keys[20], $arr)) {
            $this->setPrenom($arr[$keys[20]]);
        }
        if (array_key_exists($keys[21], $arr)) {
            $this->setAdresse1($arr[$keys[21]]);
        }
        if (array_key_exists($keys[22], $arr)) {
            $this->setAdresse2($arr[$keys[22]]);
        }
        if (array_key_exists($keys[23], $arr)) {
            $this->setCodepostal($arr[$keys[23]]);
        }
        if (array_key_exists($keys[24], $arr)) {
            $this->setVille($arr[$keys[24]]);
        }
        if (array_key_exists($keys[25], $arr)) {
            $this->setPays($arr[$keys[25]]);
        }
        if (array_key_exists($keys[26], $arr)) {
            $this->setTelephone($arr[$keys[26]]);
        }
        if (array_key_exists($keys[27], $arr)) {
            $this->setPrefArticlesShow($arr[$keys[27]]);
        }
        if (array_key_exists($keys[28], $arr)) {
            $this->setFbId($arr[$keys[28]]);
        }
        if (array_key_exists($keys[29], $arr)) {
            $this->setFbToken($arr[$keys[29]]);
        }
        if (array_key_exists($keys[30], $arr)) {
            $this->setCountryId($arr[$keys[30]]);
        }
        if (array_key_exists($keys[31], $arr)) {
            $this->setPasswordResetToken($arr[$keys[31]]);
        }
        if (array_key_exists($keys[32], $arr)) {
            $this->setPasswordResetTokenCreated($arr[$keys[32]]);
        }
        if (array_key_exists($keys[33], $arr)) {
            $this->setUpdate($arr[$keys[33]]);
        }
        if (array_key_exists($keys[34], $arr)) {
            $this->setCreatedAt($arr[$keys[34]]);
        }
        if (array_key_exists($keys[35], $arr)) {
            $this->setUpdatedAt($arr[$keys[35]]);
        }
        if (array_key_exists($keys[36], $arr)) {
            $this->setDeletedAt($arr[$keys[36]]);
        }
        if (array_key_exists($keys[37], $arr)) {
            $this->setDeletedWhy($arr[$keys[37]]);
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
     * @return $this|\Model\User The current object, for fluid interface
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
        $criteria = new Criteria(UserTableMap::DATABASE_NAME);

        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $criteria->add(UserTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $criteria->add(UserTableMap::COL_EMAIL, $this->email);
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_PASSWORD)) {
            $criteria->add(UserTableMap::COL_USER_PASSWORD, $this->user_password);
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_KEY)) {
            $criteria->add(UserTableMap::COL_USER_KEY, $this->user_key);
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL_KEY)) {
            $criteria->add(UserTableMap::COL_EMAIL_KEY, $this->email_key);
        }
        if ($this->isColumnModified(UserTableMap::COL_FACEBOOK_UID)) {
            $criteria->add(UserTableMap::COL_FACEBOOK_UID, $this->facebook_uid);
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_SCREEN_NAME)) {
            $criteria->add(UserTableMap::COL_USER_SCREEN_NAME, $this->user_screen_name);
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_SLUG)) {
            $criteria->add(UserTableMap::COL_USER_SLUG, $this->user_slug);
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_WISHLIST_SHIP)) {
            $criteria->add(UserTableMap::COL_USER_WISHLIST_SHIP, $this->user_wishlist_ship);
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_TOP)) {
            $criteria->add(UserTableMap::COL_USER_TOP, $this->user_top);
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_BIBLIO)) {
            $criteria->add(UserTableMap::COL_USER_BIBLIO, $this->user_biblio);
        }
        if ($this->isColumnModified(UserTableMap::COL_ADRESSE_IP)) {
            $criteria->add(UserTableMap::COL_ADRESSE_IP, $this->adresse_ip);
        }
        if ($this->isColumnModified(UserTableMap::COL_RECAPTCHA_SCORE)) {
            $criteria->add(UserTableMap::COL_RECAPTCHA_SCORE, $this->recaptcha_score);
        }
        if ($this->isColumnModified(UserTableMap::COL_DATEINSCRIPTION)) {
            $criteria->add(UserTableMap::COL_DATEINSCRIPTION, $this->dateinscription);
        }
        if ($this->isColumnModified(UserTableMap::COL_DATECONNEXION)) {
            $criteria->add(UserTableMap::COL_DATECONNEXION, $this->dateconnexion);
        }
        if ($this->isColumnModified(UserTableMap::COL_PUBLISHER_ID)) {
            $criteria->add(UserTableMap::COL_PUBLISHER_ID, $this->publisher_id);
        }
        if ($this->isColumnModified(UserTableMap::COL_BOOKSHOP_ID)) {
            $criteria->add(UserTableMap::COL_BOOKSHOP_ID, $this->bookshop_id);
        }
        if ($this->isColumnModified(UserTableMap::COL_LIBRARY_ID)) {
            $criteria->add(UserTableMap::COL_LIBRARY_ID, $this->library_id);
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_CIVILITE)) {
            $criteria->add(UserTableMap::COL_USER_CIVILITE, $this->user_civilite);
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_NOM)) {
            $criteria->add(UserTableMap::COL_USER_NOM, $this->user_nom);
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_PRENOM)) {
            $criteria->add(UserTableMap::COL_USER_PRENOM, $this->user_prenom);
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_ADRESSE1)) {
            $criteria->add(UserTableMap::COL_USER_ADRESSE1, $this->user_adresse1);
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_ADRESSE2)) {
            $criteria->add(UserTableMap::COL_USER_ADRESSE2, $this->user_adresse2);
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_CODEPOSTAL)) {
            $criteria->add(UserTableMap::COL_USER_CODEPOSTAL, $this->user_codepostal);
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_VILLE)) {
            $criteria->add(UserTableMap::COL_USER_VILLE, $this->user_ville);
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_PAYS)) {
            $criteria->add(UserTableMap::COL_USER_PAYS, $this->user_pays);
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_TELEPHONE)) {
            $criteria->add(UserTableMap::COL_USER_TELEPHONE, $this->user_telephone);
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_PREF_ARTICLES_SHOW)) {
            $criteria->add(UserTableMap::COL_USER_PREF_ARTICLES_SHOW, $this->user_pref_articles_show);
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_FB_ID)) {
            $criteria->add(UserTableMap::COL_USER_FB_ID, $this->user_fb_id);
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_FB_TOKEN)) {
            $criteria->add(UserTableMap::COL_USER_FB_TOKEN, $this->user_fb_token);
        }
        if ($this->isColumnModified(UserTableMap::COL_COUNTRY_ID)) {
            $criteria->add(UserTableMap::COL_COUNTRY_ID, $this->country_id);
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_PASSWORD_RESET_TOKEN)) {
            $criteria->add(UserTableMap::COL_USER_PASSWORD_RESET_TOKEN, $this->user_password_reset_token);
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_PASSWORD_RESET_TOKEN_CREATED)) {
            $criteria->add(UserTableMap::COL_USER_PASSWORD_RESET_TOKEN_CREATED, $this->user_password_reset_token_created);
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_UPDATE)) {
            $criteria->add(UserTableMap::COL_USER_UPDATE, $this->user_update);
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_CREATED)) {
            $criteria->add(UserTableMap::COL_USER_CREATED, $this->user_created);
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_UPDATED)) {
            $criteria->add(UserTableMap::COL_USER_UPDATED, $this->user_updated);
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_DELETED)) {
            $criteria->add(UserTableMap::COL_USER_DELETED, $this->user_deleted);
        }
        if ($this->isColumnModified(UserTableMap::COL_USER_DELETED_WHY)) {
            $criteria->add(UserTableMap::COL_USER_DELETED_WHY, $this->user_deleted_why);
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
        $criteria = ChildUserQuery::create();
        $criteria->add(UserTableMap::COL_ID, $this->id);

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
     * Generic method to set the primary key (id column).
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
     * @param      object $copyObj An object of \Model\User (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setEmail($this->getEmail());
        $copyObj->setPassword($this->getPassword());
        $copyObj->setKey($this->getKey());
        $copyObj->setEmailKey($this->getEmailKey());
        $copyObj->setFacebookUid($this->getFacebookUid());
        $copyObj->setUsername($this->getUsername());
        $copyObj->setSlug($this->getSlug());
        $copyObj->setWishlistShip($this->getWishlistShip());
        $copyObj->setTop($this->getTop());
        $copyObj->setBiblio($this->getBiblio());
        $copyObj->setAdresseIp($this->getAdresseIp());
        $copyObj->setRecaptchaScore($this->getRecaptchaScore());
        $copyObj->setDateinscription($this->getDateinscription());
        $copyObj->setDateconnexion($this->getDateconnexion());
        $copyObj->setPublisherId($this->getPublisherId());
        $copyObj->setBookshopId($this->getBookshopId());
        $copyObj->setLibraryId($this->getLibraryId());
        $copyObj->setCivilite($this->getCivilite());
        $copyObj->setNom($this->getNom());
        $copyObj->setPrenom($this->getPrenom());
        $copyObj->setAdresse1($this->getAdresse1());
        $copyObj->setAdresse2($this->getAdresse2());
        $copyObj->setCodepostal($this->getCodepostal());
        $copyObj->setVille($this->getVille());
        $copyObj->setPays($this->getPays());
        $copyObj->setTelephone($this->getTelephone());
        $copyObj->setPrefArticlesShow($this->getPrefArticlesShow());
        $copyObj->setFbId($this->getFbId());
        $copyObj->setFbToken($this->getFbToken());
        $copyObj->setCountryId($this->getCountryId());
        $copyObj->setPasswordResetToken($this->getPasswordResetToken());
        $copyObj->setPasswordResetTokenCreated($this->getPasswordResetTokenCreated());
        $copyObj->setUpdate($this->getUpdate());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        $copyObj->setDeletedAt($this->getDeletedAt());
        $copyObj->setDeletedWhy($this->getDeletedWhy());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getRights() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addRight($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSessions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSession($relObj->copy($deepCopy));
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
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Model\User Clone of current object.
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
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('Right' === $relationName) {
            $this->initRights();
            return;
        }
        if ('Session' === $relationName) {
            $this->initSessions();
            return;
        }
    }

    /**
     * Clears out the collRights collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addRights()
     */
    public function clearRights()
    {
        $this->collRights = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collRights collection loaded partially.
     */
    public function resetPartialRights($v = true)
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
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initRights($overrideExisting = true)
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
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildRight[] List of ChildRight objects
     * @phpstan-return ObjectCollection&\Traversable<ChildRight> List of ChildRight objects
     * @throws PropelException
     */
    public function getRights(Criteria $criteria = null, ConnectionInterface $con = null)
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
     * @param      Collection $rights A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setRights(Collection $rights, ConnectionInterface $con = null)
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
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Right objects.
     * @throws PropelException
     */
    public function countRights(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
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
     * @param  ChildRight $l ChildRight
     * @return $this|\Model\User The current object (for fluent API support)
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
    protected function doAddRight(ChildRight $right)
    {
        $this->collRights[]= $right;
        $right->setUser($this);
    }

    /**
     * @param  ChildRight $right The ChildRight object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
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
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildRight[] List of ChildRight objects
     * @phpstan-return ObjectCollection&\Traversable<ChildRight}> List of ChildRight objects
     */
    public function getRightsJoinSite(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildRightQuery::create(null, $criteria);
        $query->joinWith('Site', $joinBehavior);

        return $this->getRights($query, $con);
    }

    /**
     * Clears out the collSessions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addSessions()
     */
    public function clearSessions()
    {
        $this->collSessions = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collSessions collection loaded partially.
     */
    public function resetPartialSessions($v = true)
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
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSessions($overrideExisting = true)
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
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildSession[] List of ChildSession objects
     * @phpstan-return ObjectCollection&\Traversable<ChildSession> List of ChildSession objects
     * @throws PropelException
     */
    public function getSessions(Criteria $criteria = null, ConnectionInterface $con = null)
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
     * @param      Collection $sessions A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setSessions(Collection $sessions, ConnectionInterface $con = null)
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
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Session objects.
     * @throws PropelException
     */
    public function countSessions(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
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
     * @param  ChildSession $l ChildSession
     * @return $this|\Model\User The current object (for fluent API support)
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
    protected function doAddSession(ChildSession $session)
    {
        $this->collSessions[]= $session;
        $session->setUser($this);
    }

    /**
     * @param  ChildSession $session The ChildSession object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
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
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->email = null;
        $this->user_password = null;
        $this->user_key = null;
        $this->email_key = null;
        $this->facebook_uid = null;
        $this->user_screen_name = null;
        $this->user_slug = null;
        $this->user_wishlist_ship = null;
        $this->user_top = null;
        $this->user_biblio = null;
        $this->adresse_ip = null;
        $this->recaptcha_score = null;
        $this->dateinscription = null;
        $this->dateconnexion = null;
        $this->publisher_id = null;
        $this->bookshop_id = null;
        $this->library_id = null;
        $this->user_civilite = null;
        $this->user_nom = null;
        $this->user_prenom = null;
        $this->user_adresse1 = null;
        $this->user_adresse2 = null;
        $this->user_codepostal = null;
        $this->user_ville = null;
        $this->user_pays = null;
        $this->user_telephone = null;
        $this->user_pref_articles_show = null;
        $this->user_fb_id = null;
        $this->user_fb_token = null;
        $this->country_id = null;
        $this->user_password_reset_token = null;
        $this->user_password_reset_token_created = null;
        $this->user_update = null;
        $this->user_created = null;
        $this->user_updated = null;
        $this->user_deleted = null;
        $this->user_deleted_why = null;
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
            if ($this->collRights) {
                foreach ($this->collRights as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSessions) {
                foreach ($this->collSessions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collRights = null;
        $this->collSessions = null;
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
     * @return     $this|ChildUser The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[UserTableMap::COL_USER_UPDATED] = true;

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
        $metadata->addPropertyConstraint('email', new Email());
    }

    /**
     * Validates the object and all objects related to this table.
     *
     * @see        getValidationFailures()
     * @param      ValidatorInterface|null $validator A Validator class instance
     * @return     boolean Whether all objects pass validation.
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

            if (null !== $this->collRights) {
                foreach ($this->collRights as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }
            if (null !== $this->collSessions) {
                foreach ($this->collSessions as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
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
     * @return     ConstraintViolationList
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
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
