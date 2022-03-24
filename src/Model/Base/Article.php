<?php

namespace Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Model\Article as ChildArticle;
use Model\ArticleQuery as ChildArticleQuery;
use Model\BookCollection as ChildBookCollection;
use Model\BookCollectionQuery as ChildBookCollectionQuery;
use Model\Link as ChildLink;
use Model\LinkQuery as ChildLinkQuery;
use Model\Publisher as ChildPublisher;
use Model\PublisherQuery as ChildPublisherQuery;
use Model\Role as ChildRole;
use Model\RoleQuery as ChildRoleQuery;
use Model\Map\ArticleTableMap;
use Model\Map\LinkTableMap;
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
 * Base class that represents a row from the 'articles' table.
 *
 *
 *
 * @package    propel.generator.Model.Base
 */
abstract class Article implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Model\\Map\\ArticleTableMap';


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
     * The value for the article_id field.
     *
     * @var        int
     */
    protected $article_id;

    /**
     * The value for the article_item field.
     *
     * @var        int|null
     */
    protected $article_item;

    /**
     * The value for the article_textid field.
     *
     * @var        string|null
     */
    protected $article_textid;

    /**
     * The value for the article_ean field.
     *
     * @var        string|null
     */
    protected $article_ean;

    /**
     * The value for the article_ean_others field.
     *
     * @var        string|null
     */
    protected $article_ean_others;

    /**
     * The value for the article_asin field.
     *
     * @var        string|null
     */
    protected $article_asin;

    /**
     * The value for the article_noosfere_id field.
     *
     * @var        int|null
     */
    protected $article_noosfere_id;

    /**
     * The value for the article_url field.
     *
     * @var        string|null
     */
    protected $article_url;

    /**
     * The value for the type_id field.
     *
     * @var        int|null
     */
    protected $type_id;

    /**
     * The value for the article_title field.
     *
     * @var        string|null
     */
    protected $article_title;

    /**
     * The value for the article_title_alphabetic field.
     *
     * @var        string|null
     */
    protected $article_title_alphabetic;

    /**
     * The value for the article_title_original field.
     *
     * @var        string|null
     */
    protected $article_title_original;

    /**
     * The value for the article_title_others field.
     *
     * @var        string|null
     */
    protected $article_title_others;

    /**
     * The value for the article_subtitle field.
     *
     * @var        string|null
     */
    protected $article_subtitle;

    /**
     * The value for the article_lang_current field.
     *
     * @var        int|null
     */
    protected $article_lang_current;

    /**
     * The value for the article_lang_original field.
     *
     * @var        int|null
     */
    protected $article_lang_original;

    /**
     * The value for the article_origin_country field.
     *
     * @var        int|null
     */
    protected $article_origin_country;

    /**
     * The value for the article_theme_bisac field.
     *
     * @var        string|null
     */
    protected $article_theme_bisac;

    /**
     * The value for the article_theme_clil field.
     *
     * @var        string|null
     */
    protected $article_theme_clil;

    /**
     * The value for the article_theme_dewey field.
     *
     * @var        string|null
     */
    protected $article_theme_dewey;

    /**
     * The value for the article_theme_electre field.
     *
     * @var        string|null
     */
    protected $article_theme_electre;

    /**
     * The value for the article_source_id field.
     *
     * @var        int|null
     */
    protected $article_source_id;

    /**
     * The value for the article_authors field.
     *
     * @var        string|null
     */
    protected $article_authors;

    /**
     * The value for the article_authors_alphabetic field.
     *
     * @var        string|null
     */
    protected $article_authors_alphabetic;

    /**
     * The value for the collection_id field.
     *
     * @var        int|null
     */
    protected $collection_id;

    /**
     * The value for the article_collection field.
     *
     * @var        string|null
     */
    protected $article_collection;

    /**
     * The value for the article_number field.
     *
     * @var        string|null
     */
    protected $article_number;

    /**
     * The value for the publisher_id field.
     *
     * @var        int|null
     */
    protected $publisher_id;

    /**
     * The value for the article_publisher field.
     *
     * @var        string|null
     */
    protected $article_publisher;

    /**
     * The value for the cycle_id field.
     *
     * @var        int|null
     */
    protected $cycle_id;

    /**
     * The value for the article_cycle field.
     *
     * @var        string|null
     */
    protected $article_cycle;

    /**
     * The value for the article_tome field.
     *
     * @var        string|null
     */
    protected $article_tome;

    /**
     * The value for the article_cover_version field.
     *
     * Note: this column has a database default value of: 0
     * @var        int|null
     */
    protected $article_cover_version;

    /**
     * The value for the article_availability field.
     *
     * @var        int|null
     */
    protected $article_availability;

    /**
     * The value for the article_availability_dilicom field.
     *
     * Note: this column has a database default value of: 1
     * @var        int|null
     */
    protected $article_availability_dilicom;

    /**
     * The value for the article_preorder field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean|null
     */
    protected $article_preorder;

    /**
     * The value for the article_price field.
     *
     * @var        int|null
     */
    protected $article_price;

    /**
     * The value for the article_price_editable field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean|null
     */
    protected $article_price_editable;

    /**
     * The value for the article_new_price field.
     *
     * @var        int|null
     */
    protected $article_new_price;

    /**
     * The value for the article_category field.
     *
     * @var        string|null
     */
    protected $article_category;

    /**
     * The value for the article_tva field.
     *
     * Note: this column has a database default value of: 1
     * @var        int|null
     */
    protected $article_tva;

    /**
     * The value for the article_pdf_ean field.
     *
     * @var        string|null
     */
    protected $article_pdf_ean;

    /**
     * The value for the article_pdf_version field.
     *
     * Note: this column has a database default value of: '0'
     * @var        string|null
     */
    protected $article_pdf_version;

    /**
     * The value for the article_epub_ean field.
     *
     * @var        string|null
     */
    protected $article_epub_ean;

    /**
     * The value for the article_epub_version field.
     *
     * Note: this column has a database default value of: '0'
     * @var        string|null
     */
    protected $article_epub_version;

    /**
     * The value for the article_azw_ean field.
     *
     * @var        string|null
     */
    protected $article_azw_ean;

    /**
     * The value for the article_azw_version field.
     *
     * Note: this column has a database default value of: '0'
     * @var        string|null
     */
    protected $article_azw_version;

    /**
     * The value for the article_pages field.
     *
     * @var        int|null
     */
    protected $article_pages;

    /**
     * The value for the article_weight field.
     *
     * @var        int|null
     */
    protected $article_weight;

    /**
     * The value for the article_shaping field.
     *
     * @var        string|null
     */
    protected $article_shaping;

    /**
     * The value for the article_format field.
     *
     * @var        string|null
     */
    protected $article_format;

    /**
     * The value for the article_printing_process field.
     *
     * @var        string|null
     */
    protected $article_printing_process;

    /**
     * The value for the article_age_min field.
     *
     * @var        int|null
     */
    protected $article_age_min;

    /**
     * The value for the article_age_max field.
     *
     * @var        int|null
     */
    protected $article_age_max;

    /**
     * The value for the article_summary field.
     *
     * @var        string|null
     */
    protected $article_summary;

    /**
     * The value for the article_contents field.
     *
     * @var        string|null
     */
    protected $article_contents;

    /**
     * The value for the article_bonus field.
     *
     * @var        string|null
     */
    protected $article_bonus;

    /**
     * The value for the article_catchline field.
     *
     * @var        string|null
     */
    protected $article_catchline;

    /**
     * The value for the article_biography field.
     *
     * @var        string|null
     */
    protected $article_biography;

    /**
     * The value for the article_motsv field.
     *
     * @var        string|null
     */
    protected $article_motsv;

    /**
     * The value for the article_copyright field.
     *
     * @var        int|null
     */
    protected $article_copyright;

    /**
     * The value for the article_pubdate field.
     *
     * @var        DateTime|null
     */
    protected $article_pubdate;

    /**
     * The value for the article_keywords field.
     *
     * @var        string|null
     */
    protected $article_keywords;

    /**
     * The value for the article_links field.
     *
     * @var        string|null
     */
    protected $article_links;

    /**
     * The value for the article_keywords_generated field.
     *
     * @var        DateTime|null
     */
    protected $article_keywords_generated;

    /**
     * The value for the article_publisher_stock field.
     *
     * Note: this column has a database default value of: 0
     * @var        int|null
     */
    protected $article_publisher_stock;

    /**
     * The value for the article_hits field.
     *
     * Note: this column has a database default value of: 0
     * @var        int|null
     */
    protected $article_hits;

    /**
     * The value for the article_editing_user field.
     *
     * @var        int|null
     */
    protected $article_editing_user;

    /**
     * The value for the article_insert field.
     *
     * @var        DateTime|null
     */
    protected $article_insert;

    /**
     * The value for the article_update field.
     *
     * @var        DateTime|null
     */
    protected $article_update;

    /**
     * The value for the article_created field.
     *
     * @var        DateTime|null
     */
    protected $article_created;

    /**
     * The value for the article_updated field.
     *
     * @var        DateTime|null
     */
    protected $article_updated;

    /**
     * The value for the article_done field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean|null
     */
    protected $article_done;

    /**
     * The value for the article_to_check field.
     *
     * Note: this column has a database default value of: false
     * @var        boolean|null
     */
    protected $article_to_check;

    /**
     * The value for the article_pushed_to_data field.
     *
     * @var        DateTime|null
     */
    protected $article_pushed_to_data;

    /**
     * The value for the article_deletion_by field.
     *
     * @var        int|null
     */
    protected $article_deletion_by;

    /**
     * The value for the article_deletion_date field.
     *
     * @var        DateTime|null
     */
    protected $article_deletion_date;

    /**
     * The value for the article_deletion_reason field.
     *
     * @var        string|null
     */
    protected $article_deletion_reason;

    /**
     * @var        ChildPublisher
     */
    protected $aPublisher;

    /**
     * @var        ChildBookCollection
     */
    protected $aBookCollection;

    /**
     * @var        ObjectCollection|ChildLink[] Collection to store aggregation of ChildLink objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildLink> Collection to store aggregation of ChildLink objects.
     */
    protected $collLinks;
    protected $collLinksPartial;

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
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildLink[]
     * @phpstan-var ObjectCollection&\Traversable<ChildLink>
     */
    protected $linksScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildRole[]
     * @phpstan-var ObjectCollection&\Traversable<ChildRole>
     */
    protected $rolesScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->article_cover_version = 0;
        $this->article_availability_dilicom = 1;
        $this->article_preorder = false;
        $this->article_price_editable = false;
        $this->article_tva = 1;
        $this->article_pdf_version = '0';
        $this->article_epub_version = '0';
        $this->article_azw_version = '0';
        $this->article_publisher_stock = 0;
        $this->article_hits = 0;
        $this->article_done = false;
        $this->article_to_check = false;
    }

    /**
     * Initializes internal state of Model\Base\Article object.
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
     * Compares this with another <code>Article</code> instance.  If
     * <code>obj</code> is an instance of <code>Article</code>, delegates to
     * <code>equals(Article)</code>.  Otherwise, returns <code>false</code>.
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
     * Get the [article_id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->article_id;
    }

    /**
     * Get the [article_item] column value.
     *
     * @return int|null
     */
    public function getItem()
    {
        return $this->article_item;
    }

    /**
     * Get the [article_textid] column value.
     *
     * @return string|null
     */
    public function getTextid()
    {
        return $this->article_textid;
    }

    /**
     * Get the [article_ean] column value.
     *
     * @return string|null
     */
    public function getEan()
    {
        return $this->article_ean;
    }

    /**
     * Get the [article_ean_others] column value.
     *
     * @return string|null
     */
    public function getEanOthers()
    {
        return $this->article_ean_others;
    }

    /**
     * Get the [article_asin] column value.
     *
     * @return string|null
     */
    public function getAsin()
    {
        return $this->article_asin;
    }

    /**
     * Get the [article_noosfere_id] column value.
     *
     * @return int|null
     */
    public function getNoosfereId()
    {
        return $this->article_noosfere_id;
    }

    /**
     * Get the [article_url] column value.
     *
     * @return string|null
     */
    public function getUrl()
    {
        return $this->article_url;
    }

    /**
     * Get the [type_id] column value.
     *
     * @return int|null
     */
    public function getTypeId()
    {
        return $this->type_id;
    }

    /**
     * Get the [article_title] column value.
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->article_title;
    }

    /**
     * Get the [article_title_alphabetic] column value.
     *
     * @return string|null
     */
    public function getTitleAlphabetic()
    {
        return $this->article_title_alphabetic;
    }

    /**
     * Get the [article_title_original] column value.
     *
     * @return string|null
     */
    public function getTitleOriginal()
    {
        return $this->article_title_original;
    }

    /**
     * Get the [article_title_others] column value.
     *
     * @return string|null
     */
    public function getTitleOthers()
    {
        return $this->article_title_others;
    }

    /**
     * Get the [article_subtitle] column value.
     *
     * @return string|null
     */
    public function getSubtitle()
    {
        return $this->article_subtitle;
    }

    /**
     * Get the [article_lang_current] column value.
     *
     * @return int|null
     */
    public function getLangCurrent()
    {
        return $this->article_lang_current;
    }

    /**
     * Get the [article_lang_original] column value.
     *
     * @return int|null
     */
    public function getLangOriginal()
    {
        return $this->article_lang_original;
    }

    /**
     * Get the [article_origin_country] column value.
     *
     * @return int|null
     */
    public function getOriginCountry()
    {
        return $this->article_origin_country;
    }

    /**
     * Get the [article_theme_bisac] column value.
     *
     * @return string|null
     */
    public function getThemeBisac()
    {
        return $this->article_theme_bisac;
    }

    /**
     * Get the [article_theme_clil] column value.
     *
     * @return string|null
     */
    public function getThemeClil()
    {
        return $this->article_theme_clil;
    }

    /**
     * Get the [article_theme_dewey] column value.
     *
     * @return string|null
     */
    public function getThemeDewey()
    {
        return $this->article_theme_dewey;
    }

    /**
     * Get the [article_theme_electre] column value.
     *
     * @return string|null
     */
    public function getThemeElectre()
    {
        return $this->article_theme_electre;
    }

    /**
     * Get the [article_source_id] column value.
     *
     * @return int|null
     */
    public function getSourceId()
    {
        return $this->article_source_id;
    }

    /**
     * Get the [article_authors] column value.
     *
     * @return string|null
     */
    public function getAuthors()
    {
        return $this->article_authors;
    }

    /**
     * Get the [article_authors_alphabetic] column value.
     *
     * @return string|null
     */
    public function getAuthorsAlphabetic()
    {
        return $this->article_authors_alphabetic;
    }

    /**
     * Get the [collection_id] column value.
     *
     * @return int|null
     */
    public function getCollectionId()
    {
        return $this->collection_id;
    }

    /**
     * Get the [article_collection] column value.
     *
     * @return string|null
     */
    public function getCollectionName()
    {
        return $this->article_collection;
    }

    /**
     * Get the [article_number] column value.
     *
     * @return string|null
     */
    public function getNumber()
    {
        return $this->article_number;
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
     * Get the [article_publisher] column value.
     *
     * @return string|null
     */
    public function getPublisherName()
    {
        return $this->article_publisher;
    }

    /**
     * Get the [cycle_id] column value.
     *
     * @return int|null
     */
    public function getCycleId()
    {
        return $this->cycle_id;
    }

    /**
     * Get the [article_cycle] column value.
     *
     * @return string|null
     */
    public function getCycle()
    {
        return $this->article_cycle;
    }

    /**
     * Get the [article_tome] column value.
     *
     * @return string|null
     */
    public function getTome()
    {
        return $this->article_tome;
    }

    /**
     * Get the [article_cover_version] column value.
     *
     * @return int|null
     */
    public function getCoverVersion()
    {
        return $this->article_cover_version;
    }

    /**
     * Get the [article_availability] column value.
     *
     * @return int|null
     */
    public function getAvailability()
    {
        return $this->article_availability;
    }

    /**
     * Get the [article_availability_dilicom] column value.
     *
     * @return int|null
     */
    public function getAvailabilityDilicom()
    {
        return $this->article_availability_dilicom;
    }

    /**
     * Get the [article_preorder] column value.
     *
     * @return boolean|null
     */
    public function getPreorder()
    {
        return $this->article_preorder;
    }

    /**
     * Get the [article_preorder] column value.
     *
     * @return boolean|null
     */
    public function isPreorder()
    {
        return $this->getPreorder();
    }

    /**
     * Get the [article_price] column value.
     *
     * @return int|null
     */
    public function getPrice()
    {
        return $this->article_price;
    }

    /**
     * Get the [article_price_editable] column value.
     *
     * @return boolean|null
     */
    public function getPriceEditable()
    {
        return $this->article_price_editable;
    }

    /**
     * Get the [article_price_editable] column value.
     *
     * @return boolean|null
     */
    public function isPriceEditable()
    {
        return $this->getPriceEditable();
    }

    /**
     * Get the [article_new_price] column value.
     *
     * @return int|null
     */
    public function getNewPrice()
    {
        return $this->article_new_price;
    }

    /**
     * Get the [article_category] column value.
     *
     * @return string|null
     */
    public function getCategory()
    {
        return $this->article_category;
    }

    /**
     * Get the [article_tva] column value.
     *
     * @return int|null
     */
    public function getTva()
    {
        return $this->article_tva;
    }

    /**
     * Get the [article_pdf_ean] column value.
     *
     * @return string|null
     */
    public function getPdfEan()
    {
        return $this->article_pdf_ean;
    }

    /**
     * Get the [article_pdf_version] column value.
     *
     * @return string|null
     */
    public function getPdfVersion()
    {
        return $this->article_pdf_version;
    }

    /**
     * Get the [article_epub_ean] column value.
     *
     * @return string|null
     */
    public function getEpubEan()
    {
        return $this->article_epub_ean;
    }

    /**
     * Get the [article_epub_version] column value.
     *
     * @return string|null
     */
    public function getEpubVersion()
    {
        return $this->article_epub_version;
    }

    /**
     * Get the [article_azw_ean] column value.
     *
     * @return string|null
     */
    public function getAzwEan()
    {
        return $this->article_azw_ean;
    }

    /**
     * Get the [article_azw_version] column value.
     *
     * @return string|null
     */
    public function getAzwVersion()
    {
        return $this->article_azw_version;
    }

    /**
     * Get the [article_pages] column value.
     *
     * @return int|null
     */
    public function getPages()
    {
        return $this->article_pages;
    }

    /**
     * Get the [article_weight] column value.
     *
     * @return int|null
     */
    public function getWeight()
    {
        return $this->article_weight;
    }

    /**
     * Get the [article_shaping] column value.
     *
     * @return string|null
     */
    public function getShaping()
    {
        return $this->article_shaping;
    }

    /**
     * Get the [article_format] column value.
     *
     * @return string|null
     */
    public function getFormat()
    {
        return $this->article_format;
    }

    /**
     * Get the [article_printing_process] column value.
     *
     * @return string|null
     */
    public function getPrintingProcess()
    {
        return $this->article_printing_process;
    }

    /**
     * Get the [article_age_min] column value.
     *
     * @return int|null
     */
    public function getAgeMin()
    {
        return $this->article_age_min;
    }

    /**
     * Get the [article_age_max] column value.
     *
     * @return int|null
     */
    public function getAgeMax()
    {
        return $this->article_age_max;
    }

    /**
     * Get the [article_summary] column value.
     *
     * @return string|null
     */
    public function getSummary()
    {
        return $this->article_summary;
    }

    /**
     * Get the [article_contents] column value.
     *
     * @return string|null
     */
    public function getContents()
    {
        return $this->article_contents;
    }

    /**
     * Get the [article_bonus] column value.
     *
     * @return string|null
     */
    public function getBonus()
    {
        return $this->article_bonus;
    }

    /**
     * Get the [article_catchline] column value.
     *
     * @return string|null
     */
    public function getCatchline()
    {
        return $this->article_catchline;
    }

    /**
     * Get the [article_biography] column value.
     *
     * @return string|null
     */
    public function getBiography()
    {
        return $this->article_biography;
    }

    /**
     * Get the [article_motsv] column value.
     *
     * @return string|null
     */
    public function getMotsv()
    {
        return $this->article_motsv;
    }

    /**
     * Get the [article_copyright] column value.
     *
     * @return int|null
     */
    public function getCopyright()
    {
        return $this->article_copyright;
    }

    /**
     * Get the [optionally formatted] temporal [article_pubdate] column value.
     *
     *
     * @param string|null $format The date/time format string (either date()-style or strftime()-style).
     *   If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     *
     * @psalm-return ($format is null ? DateTime|null : string|null)
     */
    public function getPubdate($format = null)
    {
        if ($format === null) {
            return $this->article_pubdate;
        } else {
            return $this->article_pubdate instanceof \DateTimeInterface ? $this->article_pubdate->format($format) : null;
        }
    }

    /**
     * Get the [article_keywords] column value.
     *
     * @return string|null
     */
    public function getKeywords()
    {
        return $this->article_keywords;
    }

    /**
     * Get the [article_links] column value.
     *
     * @return string|null
     */
    public function getComputedLinks()
    {
        return $this->article_links;
    }

    /**
     * Get the [optionally formatted] temporal [article_keywords_generated] column value.
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
    public function getKeywordsGenerated($format = null)
    {
        if ($format === null) {
            return $this->article_keywords_generated;
        } else {
            return $this->article_keywords_generated instanceof \DateTimeInterface ? $this->article_keywords_generated->format($format) : null;
        }
    }

    /**
     * Get the [article_publisher_stock] column value.
     *
     * @return int|null
     */
    public function getPublisherStock()
    {
        return $this->article_publisher_stock;
    }

    /**
     * Get the [article_hits] column value.
     *
     * @return int|null
     */
    public function getHits()
    {
        return $this->article_hits;
    }

    /**
     * Get the [article_editing_user] column value.
     *
     * @return int|null
     */
    public function getEditingUser()
    {
        return $this->article_editing_user;
    }

    /**
     * Get the [optionally formatted] temporal [article_insert] column value.
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
    public function getInsert($format = null)
    {
        if ($format === null) {
            return $this->article_insert;
        } else {
            return $this->article_insert instanceof \DateTimeInterface ? $this->article_insert->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [article_update] column value.
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
            return $this->article_update;
        } else {
            return $this->article_update instanceof \DateTimeInterface ? $this->article_update->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [article_created] column value.
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
            return $this->article_created;
        } else {
            return $this->article_created instanceof \DateTimeInterface ? $this->article_created->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [article_updated] column value.
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
            return $this->article_updated;
        } else {
            return $this->article_updated instanceof \DateTimeInterface ? $this->article_updated->format($format) : null;
        }
    }

    /**
     * Get the [article_done] column value.
     *
     * @return boolean|null
     */
    public function getDone()
    {
        return $this->article_done;
    }

    /**
     * Get the [article_done] column value.
     *
     * @return boolean|null
     */
    public function isDone()
    {
        return $this->getDone();
    }

    /**
     * Get the [article_to_check] column value.
     *
     * @return boolean|null
     */
    public function getToCheck()
    {
        return $this->article_to_check;
    }

    /**
     * Get the [article_to_check] column value.
     *
     * @return boolean|null
     */
    public function isToCheck()
    {
        return $this->getToCheck();
    }

    /**
     * Get the [optionally formatted] temporal [article_pushed_to_data] column value.
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
    public function getPushedToData($format = null)
    {
        if ($format === null) {
            return $this->article_pushed_to_data;
        } else {
            return $this->article_pushed_to_data instanceof \DateTimeInterface ? $this->article_pushed_to_data->format($format) : null;
        }
    }

    /**
     * Get the [article_deletion_by] column value.
     *
     * @return int|null
     */
    public function getDeletionBy()
    {
        return $this->article_deletion_by;
    }

    /**
     * Get the [optionally formatted] temporal [article_deletion_date] column value.
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
    public function getDeletionDate($format = null)
    {
        if ($format === null) {
            return $this->article_deletion_date;
        } else {
            return $this->article_deletion_date instanceof \DateTimeInterface ? $this->article_deletion_date->format($format) : null;
        }
    }

    /**
     * Get the [article_deletion_reason] column value.
     *
     * @return string|null
     */
    public function getDeletionReason()
    {
        return $this->article_deletion_reason;
    }

    /**
     * Set the value of [article_id] column.
     *
     * @param int $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->article_id !== $v) {
            $this->article_id = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [article_item] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setItem($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->article_item !== $v) {
            $this->article_item = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_ITEM] = true;
        }

        return $this;
    } // setItem()

    /**
     * Set the value of [article_textid] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setTextid($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_textid !== $v) {
            $this->article_textid = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_TEXTID] = true;
        }

        return $this;
    } // setTextid()

    /**
     * Set the value of [article_ean] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setEan($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_ean !== $v) {
            $this->article_ean = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_EAN] = true;
        }

        return $this;
    } // setEan()

    /**
     * Set the value of [article_ean_others] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setEanOthers($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_ean_others !== $v) {
            $this->article_ean_others = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_EAN_OTHERS] = true;
        }

        return $this;
    } // setEanOthers()

    /**
     * Set the value of [article_asin] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setAsin($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_asin !== $v) {
            $this->article_asin = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_ASIN] = true;
        }

        return $this;
    } // setAsin()

    /**
     * Set the value of [article_noosfere_id] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setNoosfereId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->article_noosfere_id !== $v) {
            $this->article_noosfere_id = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_NOOSFERE_ID] = true;
        }

        return $this;
    } // setNoosfereId()

    /**
     * Set the value of [article_url] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_url !== $v) {
            $this->article_url = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_URL] = true;
        }

        return $this;
    } // setUrl()

    /**
     * Set the value of [type_id] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setTypeId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->type_id !== $v) {
            $this->type_id = $v;
            $this->modifiedColumns[ArticleTableMap::COL_TYPE_ID] = true;
        }

        return $this;
    } // setTypeId()

    /**
     * Set the value of [article_title] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_title !== $v) {
            $this->article_title = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_TITLE] = true;
        }

        return $this;
    } // setTitle()

    /**
     * Set the value of [article_title_alphabetic] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setTitleAlphabetic($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_title_alphabetic !== $v) {
            $this->article_title_alphabetic = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_TITLE_ALPHABETIC] = true;
        }

        return $this;
    } // setTitleAlphabetic()

    /**
     * Set the value of [article_title_original] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setTitleOriginal($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_title_original !== $v) {
            $this->article_title_original = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_TITLE_ORIGINAL] = true;
        }

        return $this;
    } // setTitleOriginal()

    /**
     * Set the value of [article_title_others] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setTitleOthers($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_title_others !== $v) {
            $this->article_title_others = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_TITLE_OTHERS] = true;
        }

        return $this;
    } // setTitleOthers()

    /**
     * Set the value of [article_subtitle] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setSubtitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_subtitle !== $v) {
            $this->article_subtitle = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_SUBTITLE] = true;
        }

        return $this;
    } // setSubtitle()

    /**
     * Set the value of [article_lang_current] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setLangCurrent($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->article_lang_current !== $v) {
            $this->article_lang_current = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_LANG_CURRENT] = true;
        }

        return $this;
    } // setLangCurrent()

    /**
     * Set the value of [article_lang_original] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setLangOriginal($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->article_lang_original !== $v) {
            $this->article_lang_original = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_LANG_ORIGINAL] = true;
        }

        return $this;
    } // setLangOriginal()

    /**
     * Set the value of [article_origin_country] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setOriginCountry($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->article_origin_country !== $v) {
            $this->article_origin_country = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_ORIGIN_COUNTRY] = true;
        }

        return $this;
    } // setOriginCountry()

    /**
     * Set the value of [article_theme_bisac] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setThemeBisac($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_theme_bisac !== $v) {
            $this->article_theme_bisac = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_THEME_BISAC] = true;
        }

        return $this;
    } // setThemeBisac()

    /**
     * Set the value of [article_theme_clil] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setThemeClil($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_theme_clil !== $v) {
            $this->article_theme_clil = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_THEME_CLIL] = true;
        }

        return $this;
    } // setThemeClil()

    /**
     * Set the value of [article_theme_dewey] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setThemeDewey($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_theme_dewey !== $v) {
            $this->article_theme_dewey = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_THEME_DEWEY] = true;
        }

        return $this;
    } // setThemeDewey()

    /**
     * Set the value of [article_theme_electre] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setThemeElectre($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_theme_electre !== $v) {
            $this->article_theme_electre = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_THEME_ELECTRE] = true;
        }

        return $this;
    } // setThemeElectre()

    /**
     * Set the value of [article_source_id] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setSourceId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->article_source_id !== $v) {
            $this->article_source_id = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_SOURCE_ID] = true;
        }

        return $this;
    } // setSourceId()

    /**
     * Set the value of [article_authors] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setAuthors($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_authors !== $v) {
            $this->article_authors = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_AUTHORS] = true;
        }

        return $this;
    } // setAuthors()

    /**
     * Set the value of [article_authors_alphabetic] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setAuthorsAlphabetic($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_authors_alphabetic !== $v) {
            $this->article_authors_alphabetic = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_AUTHORS_ALPHABETIC] = true;
        }

        return $this;
    } // setAuthorsAlphabetic()

    /**
     * Set the value of [collection_id] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setCollectionId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->collection_id !== $v) {
            $this->collection_id = $v;
            $this->modifiedColumns[ArticleTableMap::COL_COLLECTION_ID] = true;
        }

        if ($this->aBookCollection !== null && $this->aBookCollection->getId() !== $v) {
            $this->aBookCollection = null;
        }

        return $this;
    } // setCollectionId()

    /**
     * Set the value of [article_collection] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setCollectionName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_collection !== $v) {
            $this->article_collection = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_COLLECTION] = true;
        }

        return $this;
    } // setCollectionName()

    /**
     * Set the value of [article_number] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setNumber($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_number !== $v) {
            $this->article_number = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_NUMBER] = true;
        }

        return $this;
    } // setNumber()

    /**
     * Set the value of [publisher_id] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setPublisherId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->publisher_id !== $v) {
            $this->publisher_id = $v;
            $this->modifiedColumns[ArticleTableMap::COL_PUBLISHER_ID] = true;
        }

        if ($this->aPublisher !== null && $this->aPublisher->getId() !== $v) {
            $this->aPublisher = null;
        }

        return $this;
    } // setPublisherId()

    /**
     * Set the value of [article_publisher] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setPublisherName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_publisher !== $v) {
            $this->article_publisher = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_PUBLISHER] = true;
        }

        return $this;
    } // setPublisherName()

    /**
     * Set the value of [cycle_id] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setCycleId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->cycle_id !== $v) {
            $this->cycle_id = $v;
            $this->modifiedColumns[ArticleTableMap::COL_CYCLE_ID] = true;
        }

        return $this;
    } // setCycleId()

    /**
     * Set the value of [article_cycle] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setCycle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_cycle !== $v) {
            $this->article_cycle = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_CYCLE] = true;
        }

        return $this;
    } // setCycle()

    /**
     * Set the value of [article_tome] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setTome($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_tome !== $v) {
            $this->article_tome = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_TOME] = true;
        }

        return $this;
    } // setTome()

    /**
     * Set the value of [article_cover_version] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setCoverVersion($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->article_cover_version !== $v) {
            $this->article_cover_version = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_COVER_VERSION] = true;
        }

        return $this;
    } // setCoverVersion()

    /**
     * Set the value of [article_availability] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setAvailability($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->article_availability !== $v) {
            $this->article_availability = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_AVAILABILITY] = true;
        }

        return $this;
    } // setAvailability()

    /**
     * Set the value of [article_availability_dilicom] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setAvailabilityDilicom($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->article_availability_dilicom !== $v) {
            $this->article_availability_dilicom = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_AVAILABILITY_DILICOM] = true;
        }

        return $this;
    } // setAvailabilityDilicom()

    /**
     * Sets the value of the [article_preorder] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string|null $v The new value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setPreorder($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->article_preorder !== $v) {
            $this->article_preorder = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_PREORDER] = true;
        }

        return $this;
    } // setPreorder()

    /**
     * Set the value of [article_price] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setPrice($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->article_price !== $v) {
            $this->article_price = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_PRICE] = true;
        }

        return $this;
    } // setPrice()

    /**
     * Sets the value of the [article_price_editable] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string|null $v The new value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setPriceEditable($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->article_price_editable !== $v) {
            $this->article_price_editable = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_PRICE_EDITABLE] = true;
        }

        return $this;
    } // setPriceEditable()

    /**
     * Set the value of [article_new_price] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setNewPrice($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->article_new_price !== $v) {
            $this->article_new_price = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_NEW_PRICE] = true;
        }

        return $this;
    } // setNewPrice()

    /**
     * Set the value of [article_category] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setCategory($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_category !== $v) {
            $this->article_category = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_CATEGORY] = true;
        }

        return $this;
    } // setCategory()

    /**
     * Set the value of [article_tva] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setTva($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->article_tva !== $v) {
            $this->article_tva = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_TVA] = true;
        }

        return $this;
    } // setTva()

    /**
     * Set the value of [article_pdf_ean] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setPdfEan($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_pdf_ean !== $v) {
            $this->article_pdf_ean = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_PDF_EAN] = true;
        }

        return $this;
    } // setPdfEan()

    /**
     * Set the value of [article_pdf_version] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setPdfVersion($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_pdf_version !== $v) {
            $this->article_pdf_version = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_PDF_VERSION] = true;
        }

        return $this;
    } // setPdfVersion()

    /**
     * Set the value of [article_epub_ean] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setEpubEan($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_epub_ean !== $v) {
            $this->article_epub_ean = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_EPUB_EAN] = true;
        }

        return $this;
    } // setEpubEan()

    /**
     * Set the value of [article_epub_version] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setEpubVersion($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_epub_version !== $v) {
            $this->article_epub_version = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_EPUB_VERSION] = true;
        }

        return $this;
    } // setEpubVersion()

    /**
     * Set the value of [article_azw_ean] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setAzwEan($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_azw_ean !== $v) {
            $this->article_azw_ean = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_AZW_EAN] = true;
        }

        return $this;
    } // setAzwEan()

    /**
     * Set the value of [article_azw_version] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setAzwVersion($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_azw_version !== $v) {
            $this->article_azw_version = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_AZW_VERSION] = true;
        }

        return $this;
    } // setAzwVersion()

    /**
     * Set the value of [article_pages] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setPages($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->article_pages !== $v) {
            $this->article_pages = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_PAGES] = true;
        }

        return $this;
    } // setPages()

    /**
     * Set the value of [article_weight] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setWeight($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->article_weight !== $v) {
            $this->article_weight = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_WEIGHT] = true;
        }

        return $this;
    } // setWeight()

    /**
     * Set the value of [article_shaping] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setShaping($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_shaping !== $v) {
            $this->article_shaping = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_SHAPING] = true;
        }

        return $this;
    } // setShaping()

    /**
     * Set the value of [article_format] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setFormat($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_format !== $v) {
            $this->article_format = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_FORMAT] = true;
        }

        return $this;
    } // setFormat()

    /**
     * Set the value of [article_printing_process] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setPrintingProcess($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_printing_process !== $v) {
            $this->article_printing_process = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_PRINTING_PROCESS] = true;
        }

        return $this;
    } // setPrintingProcess()

    /**
     * Set the value of [article_age_min] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setAgeMin($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->article_age_min !== $v) {
            $this->article_age_min = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_AGE_MIN] = true;
        }

        return $this;
    } // setAgeMin()

    /**
     * Set the value of [article_age_max] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setAgeMax($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->article_age_max !== $v) {
            $this->article_age_max = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_AGE_MAX] = true;
        }

        return $this;
    } // setAgeMax()

    /**
     * Set the value of [article_summary] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setSummary($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_summary !== $v) {
            $this->article_summary = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_SUMMARY] = true;
        }

        return $this;
    } // setSummary()

    /**
     * Set the value of [article_contents] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setContents($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_contents !== $v) {
            $this->article_contents = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_CONTENTS] = true;
        }

        return $this;
    } // setContents()

    /**
     * Set the value of [article_bonus] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setBonus($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_bonus !== $v) {
            $this->article_bonus = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_BONUS] = true;
        }

        return $this;
    } // setBonus()

    /**
     * Set the value of [article_catchline] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setCatchline($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_catchline !== $v) {
            $this->article_catchline = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_CATCHLINE] = true;
        }

        return $this;
    } // setCatchline()

    /**
     * Set the value of [article_biography] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setBiography($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_biography !== $v) {
            $this->article_biography = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_BIOGRAPHY] = true;
        }

        return $this;
    } // setBiography()

    /**
     * Set the value of [article_motsv] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setMotsv($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_motsv !== $v) {
            $this->article_motsv = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_MOTSV] = true;
        }

        return $this;
    } // setMotsv()

    /**
     * Set the value of [article_copyright] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setCopyright($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->article_copyright !== $v) {
            $this->article_copyright = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_COPYRIGHT] = true;
        }

        return $this;
    } // setCopyright()

    /**
     * Sets the value of [article_pubdate] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setPubdate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->article_pubdate !== null || $dt !== null) {
            if ($this->article_pubdate === null || $dt === null || $dt->format("Y-m-d") !== $this->article_pubdate->format("Y-m-d")) {
                $this->article_pubdate = $dt === null ? null : clone $dt;
                $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_PUBDATE] = true;
            }
        } // if either are not null

        return $this;
    } // setPubdate()

    /**
     * Set the value of [article_keywords] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setKeywords($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_keywords !== $v) {
            $this->article_keywords = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_KEYWORDS] = true;
        }

        return $this;
    } // setKeywords()

    /**
     * Set the value of [article_links] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setComputedLinks($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_links !== $v) {
            $this->article_links = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_LINKS] = true;
        }

        return $this;
    } // setComputedLinks()

    /**
     * Sets the value of [article_keywords_generated] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setKeywordsGenerated($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->article_keywords_generated !== null || $dt !== null) {
            if ($this->article_keywords_generated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->article_keywords_generated->format("Y-m-d H:i:s.u")) {
                $this->article_keywords_generated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_KEYWORDS_GENERATED] = true;
            }
        } // if either are not null

        return $this;
    } // setKeywordsGenerated()

    /**
     * Set the value of [article_publisher_stock] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setPublisherStock($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->article_publisher_stock !== $v) {
            $this->article_publisher_stock = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_PUBLISHER_STOCK] = true;
        }

        return $this;
    } // setPublisherStock()

    /**
     * Set the value of [article_hits] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setHits($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->article_hits !== $v) {
            $this->article_hits = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_HITS] = true;
        }

        return $this;
    } // setHits()

    /**
     * Set the value of [article_editing_user] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setEditingUser($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->article_editing_user !== $v) {
            $this->article_editing_user = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_EDITING_USER] = true;
        }

        return $this;
    } // setEditingUser()

    /**
     * Sets the value of [article_insert] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setInsert($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->article_insert !== null || $dt !== null) {
            if ($this->article_insert === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->article_insert->format("Y-m-d H:i:s.u")) {
                $this->article_insert = $dt === null ? null : clone $dt;
                $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_INSERT] = true;
            }
        } // if either are not null

        return $this;
    } // setInsert()

    /**
     * Sets the value of [article_update] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setUpdate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->article_update !== null || $dt !== null) {
            if ($this->article_update === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->article_update->format("Y-m-d H:i:s.u")) {
                $this->article_update = $dt === null ? null : clone $dt;
                $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_UPDATE] = true;
            }
        } // if either are not null

        return $this;
    } // setUpdate()

    /**
     * Sets the value of [article_created] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->article_created !== null || $dt !== null) {
            if ($this->article_created === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->article_created->format("Y-m-d H:i:s.u")) {
                $this->article_created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_CREATED] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [article_updated] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->article_updated !== null || $dt !== null) {
            if ($this->article_updated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->article_updated->format("Y-m-d H:i:s.u")) {
                $this->article_updated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_UPDATED] = true;
            }
        } // if either are not null

        return $this;
    } // setUpdatedAt()

    /**
     * Sets the value of the [article_done] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string|null $v The new value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setDone($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->article_done !== $v) {
            $this->article_done = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_DONE] = true;
        }

        return $this;
    } // setDone()

    /**
     * Sets the value of the [article_to_check] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string|null $v The new value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setToCheck($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->article_to_check !== $v) {
            $this->article_to_check = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_TO_CHECK] = true;
        }

        return $this;
    } // setToCheck()

    /**
     * Sets the value of [article_pushed_to_data] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setPushedToData($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->article_pushed_to_data !== null || $dt !== null) {
            if ($this->article_pushed_to_data === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->article_pushed_to_data->format("Y-m-d H:i:s.u")) {
                $this->article_pushed_to_data = $dt === null ? null : clone $dt;
                $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_PUSHED_TO_DATA] = true;
            }
        } // if either are not null

        return $this;
    } // setPushedToData()

    /**
     * Set the value of [article_deletion_by] column.
     *
     * @param int|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setDeletionBy($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->article_deletion_by !== $v) {
            $this->article_deletion_by = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_DELETION_BY] = true;
        }

        return $this;
    } // setDeletionBy()

    /**
     * Sets the value of [article_deletion_date] column to a normalized version of the date/time value specified.
     *
     * @param  string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setDeletionDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->article_deletion_date !== null || $dt !== null) {
            if ($this->article_deletion_date === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->article_deletion_date->format("Y-m-d H:i:s.u")) {
                $this->article_deletion_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_DELETION_DATE] = true;
            }
        } // if either are not null

        return $this;
    } // setDeletionDate()

    /**
     * Set the value of [article_deletion_reason] column.
     *
     * @param string|null $v New value
     * @return $this|\Model\Article The current object (for fluent API support)
     */
    public function setDeletionReason($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->article_deletion_reason !== $v) {
            $this->article_deletion_reason = $v;
            $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_DELETION_REASON] = true;
        }

        return $this;
    } // setDeletionReason()

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
            if ($this->article_cover_version !== 0) {
                return false;
            }

            if ($this->article_availability_dilicom !== 1) {
                return false;
            }

            if ($this->article_preorder !== false) {
                return false;
            }

            if ($this->article_price_editable !== false) {
                return false;
            }

            if ($this->article_tva !== 1) {
                return false;
            }

            if ($this->article_pdf_version !== '0') {
                return false;
            }

            if ($this->article_epub_version !== '0') {
                return false;
            }

            if ($this->article_azw_version !== '0') {
                return false;
            }

            if ($this->article_publisher_stock !== 0) {
                return false;
            }

            if ($this->article_hits !== 0) {
                return false;
            }

            if ($this->article_done !== false) {
                return false;
            }

            if ($this->article_to_check !== false) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : ArticleTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : ArticleTableMap::translateFieldName('Item', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_item = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : ArticleTableMap::translateFieldName('Textid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_textid = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : ArticleTableMap::translateFieldName('Ean', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_ean = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : ArticleTableMap::translateFieldName('EanOthers', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_ean_others = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : ArticleTableMap::translateFieldName('Asin', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_asin = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : ArticleTableMap::translateFieldName('NoosfereId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_noosfere_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : ArticleTableMap::translateFieldName('Url', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_url = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : ArticleTableMap::translateFieldName('TypeId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->type_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : ArticleTableMap::translateFieldName('Title', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_title = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : ArticleTableMap::translateFieldName('TitleAlphabetic', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_title_alphabetic = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : ArticleTableMap::translateFieldName('TitleOriginal', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_title_original = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : ArticleTableMap::translateFieldName('TitleOthers', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_title_others = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : ArticleTableMap::translateFieldName('Subtitle', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_subtitle = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : ArticleTableMap::translateFieldName('LangCurrent', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_lang_current = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : ArticleTableMap::translateFieldName('LangOriginal', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_lang_original = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : ArticleTableMap::translateFieldName('OriginCountry', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_origin_country = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 17 + $startcol : ArticleTableMap::translateFieldName('ThemeBisac', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_theme_bisac = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 18 + $startcol : ArticleTableMap::translateFieldName('ThemeClil', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_theme_clil = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 19 + $startcol : ArticleTableMap::translateFieldName('ThemeDewey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_theme_dewey = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 20 + $startcol : ArticleTableMap::translateFieldName('ThemeElectre', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_theme_electre = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 21 + $startcol : ArticleTableMap::translateFieldName('SourceId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_source_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 22 + $startcol : ArticleTableMap::translateFieldName('Authors', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_authors = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 23 + $startcol : ArticleTableMap::translateFieldName('AuthorsAlphabetic', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_authors_alphabetic = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 24 + $startcol : ArticleTableMap::translateFieldName('CollectionId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->collection_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 25 + $startcol : ArticleTableMap::translateFieldName('CollectionName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_collection = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 26 + $startcol : ArticleTableMap::translateFieldName('Number', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_number = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 27 + $startcol : ArticleTableMap::translateFieldName('PublisherId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->publisher_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 28 + $startcol : ArticleTableMap::translateFieldName('PublisherName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_publisher = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 29 + $startcol : ArticleTableMap::translateFieldName('CycleId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cycle_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 30 + $startcol : ArticleTableMap::translateFieldName('Cycle', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_cycle = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 31 + $startcol : ArticleTableMap::translateFieldName('Tome', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_tome = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 32 + $startcol : ArticleTableMap::translateFieldName('CoverVersion', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_cover_version = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 33 + $startcol : ArticleTableMap::translateFieldName('Availability', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_availability = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 34 + $startcol : ArticleTableMap::translateFieldName('AvailabilityDilicom', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_availability_dilicom = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 35 + $startcol : ArticleTableMap::translateFieldName('Preorder', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_preorder = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 36 + $startcol : ArticleTableMap::translateFieldName('Price', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_price = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 37 + $startcol : ArticleTableMap::translateFieldName('PriceEditable', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_price_editable = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 38 + $startcol : ArticleTableMap::translateFieldName('NewPrice', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_new_price = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 39 + $startcol : ArticleTableMap::translateFieldName('Category', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_category = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 40 + $startcol : ArticleTableMap::translateFieldName('Tva', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_tva = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 41 + $startcol : ArticleTableMap::translateFieldName('PdfEan', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_pdf_ean = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 42 + $startcol : ArticleTableMap::translateFieldName('PdfVersion', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_pdf_version = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 43 + $startcol : ArticleTableMap::translateFieldName('EpubEan', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_epub_ean = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 44 + $startcol : ArticleTableMap::translateFieldName('EpubVersion', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_epub_version = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 45 + $startcol : ArticleTableMap::translateFieldName('AzwEan', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_azw_ean = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 46 + $startcol : ArticleTableMap::translateFieldName('AzwVersion', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_azw_version = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 47 + $startcol : ArticleTableMap::translateFieldName('Pages', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_pages = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 48 + $startcol : ArticleTableMap::translateFieldName('Weight', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_weight = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 49 + $startcol : ArticleTableMap::translateFieldName('Shaping', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_shaping = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 50 + $startcol : ArticleTableMap::translateFieldName('Format', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_format = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 51 + $startcol : ArticleTableMap::translateFieldName('PrintingProcess', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_printing_process = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 52 + $startcol : ArticleTableMap::translateFieldName('AgeMin', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_age_min = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 53 + $startcol : ArticleTableMap::translateFieldName('AgeMax', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_age_max = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 54 + $startcol : ArticleTableMap::translateFieldName('Summary', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_summary = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 55 + $startcol : ArticleTableMap::translateFieldName('Contents', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_contents = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 56 + $startcol : ArticleTableMap::translateFieldName('Bonus', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_bonus = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 57 + $startcol : ArticleTableMap::translateFieldName('Catchline', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_catchline = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 58 + $startcol : ArticleTableMap::translateFieldName('Biography', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_biography = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 59 + $startcol : ArticleTableMap::translateFieldName('Motsv', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_motsv = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 60 + $startcol : ArticleTableMap::translateFieldName('Copyright', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_copyright = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 61 + $startcol : ArticleTableMap::translateFieldName('Pubdate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00') {
                $col = null;
            }
            $this->article_pubdate = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 62 + $startcol : ArticleTableMap::translateFieldName('Keywords', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_keywords = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 63 + $startcol : ArticleTableMap::translateFieldName('ComputedLinks', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_links = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 64 + $startcol : ArticleTableMap::translateFieldName('KeywordsGenerated', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->article_keywords_generated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 65 + $startcol : ArticleTableMap::translateFieldName('PublisherStock', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_publisher_stock = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 66 + $startcol : ArticleTableMap::translateFieldName('Hits', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_hits = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 67 + $startcol : ArticleTableMap::translateFieldName('EditingUser', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_editing_user = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 68 + $startcol : ArticleTableMap::translateFieldName('Insert', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->article_insert = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 69 + $startcol : ArticleTableMap::translateFieldName('Update', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->article_update = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 70 + $startcol : ArticleTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->article_created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 71 + $startcol : ArticleTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->article_updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 72 + $startcol : ArticleTableMap::translateFieldName('Done', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_done = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 73 + $startcol : ArticleTableMap::translateFieldName('ToCheck', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_to_check = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 74 + $startcol : ArticleTableMap::translateFieldName('PushedToData', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->article_pushed_to_data = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 75 + $startcol : ArticleTableMap::translateFieldName('DeletionBy', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_deletion_by = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 76 + $startcol : ArticleTableMap::translateFieldName('DeletionDate', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->article_deletion_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 77 + $startcol : ArticleTableMap::translateFieldName('DeletionReason', TableMap::TYPE_PHPNAME, $indexType)];
            $this->article_deletion_reason = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 78; // 78 = ArticleTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\Article'), 0, $e);
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
        if ($this->aBookCollection !== null && $this->collection_id !== $this->aBookCollection->getId()) {
            $this->aBookCollection = null;
        }
        if ($this->aPublisher !== null && $this->publisher_id !== $this->aPublisher->getId()) {
            $this->aPublisher = null;
        }
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
            $con = Propel::getServiceContainer()->getReadConnection(ArticleTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildArticleQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aPublisher = null;
            $this->aBookCollection = null;
            $this->collLinks = null;

            $this->collRoles = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Article::setDeleted()
     * @see Article::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ArticleTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildArticleQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(ArticleTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            // sluggable behavior

            if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_URL) && $this->getUrl()) {
                $this->setUrl($this->makeSlugUnique($this->getUrl()));
            } else {
                $this->setUrl($this->createSlug());
            }
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                $time = time();
                $highPrecision = \Propel\Runtime\Util\PropelDateTime::createHighPrecision();
                if (!$this->isColumnModified(ArticleTableMap::COL_ARTICLE_CREATED)) {
                    $this->setCreatedAt($highPrecision);
                }
                if (!$this->isColumnModified(ArticleTableMap::COL_ARTICLE_UPDATED)) {
                    $this->setUpdatedAt($highPrecision);
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(ArticleTableMap::COL_ARTICLE_UPDATED)) {
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
                ArticleTableMap::addInstanceToPool($this);
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

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aPublisher !== null) {
                if ($this->aPublisher->isModified() || $this->aPublisher->isNew()) {
                    $affectedRows += $this->aPublisher->save($con);
                }
                $this->setPublisher($this->aPublisher);
            }

            if ($this->aBookCollection !== null) {
                if ($this->aBookCollection->isModified() || $this->aBookCollection->isNew()) {
                    $affectedRows += $this->aBookCollection->save($con);
                }
                $this->setBookCollection($this->aBookCollection);
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

        $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_ID] = true;
        if (null !== $this->article_id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ArticleTableMap::COL_ARTICLE_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'article_id';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_ITEM)) {
            $modifiedColumns[':p' . $index++]  = 'article_item';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_TEXTID)) {
            $modifiedColumns[':p' . $index++]  = 'article_textid';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_EAN)) {
            $modifiedColumns[':p' . $index++]  = 'article_ean';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_EAN_OTHERS)) {
            $modifiedColumns[':p' . $index++]  = 'article_ean_others';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_ASIN)) {
            $modifiedColumns[':p' . $index++]  = 'article_asin';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_NOOSFERE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'article_noosfere_id';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_URL)) {
            $modifiedColumns[':p' . $index++]  = 'article_url';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_TYPE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'type_id';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_TITLE)) {
            $modifiedColumns[':p' . $index++]  = 'article_title';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_TITLE_ALPHABETIC)) {
            $modifiedColumns[':p' . $index++]  = 'article_title_alphabetic';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_TITLE_ORIGINAL)) {
            $modifiedColumns[':p' . $index++]  = 'article_title_original';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_TITLE_OTHERS)) {
            $modifiedColumns[':p' . $index++]  = 'article_title_others';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_SUBTITLE)) {
            $modifiedColumns[':p' . $index++]  = 'article_subtitle';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_LANG_CURRENT)) {
            $modifiedColumns[':p' . $index++]  = 'article_lang_current';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_LANG_ORIGINAL)) {
            $modifiedColumns[':p' . $index++]  = 'article_lang_original';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_ORIGIN_COUNTRY)) {
            $modifiedColumns[':p' . $index++]  = 'article_origin_country';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_THEME_BISAC)) {
            $modifiedColumns[':p' . $index++]  = 'article_theme_bisac';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_THEME_CLIL)) {
            $modifiedColumns[':p' . $index++]  = 'article_theme_clil';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_THEME_DEWEY)) {
            $modifiedColumns[':p' . $index++]  = 'article_theme_dewey';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_THEME_ELECTRE)) {
            $modifiedColumns[':p' . $index++]  = 'article_theme_electre';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_SOURCE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'article_source_id';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_AUTHORS)) {
            $modifiedColumns[':p' . $index++]  = 'article_authors';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_AUTHORS_ALPHABETIC)) {
            $modifiedColumns[':p' . $index++]  = 'article_authors_alphabetic';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_COLLECTION_ID)) {
            $modifiedColumns[':p' . $index++]  = 'collection_id';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_COLLECTION)) {
            $modifiedColumns[':p' . $index++]  = 'article_collection';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_NUMBER)) {
            $modifiedColumns[':p' . $index++]  = 'article_number';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_PUBLISHER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'publisher_id';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_PUBLISHER)) {
            $modifiedColumns[':p' . $index++]  = 'article_publisher';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_CYCLE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'cycle_id';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_CYCLE)) {
            $modifiedColumns[':p' . $index++]  = 'article_cycle';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_TOME)) {
            $modifiedColumns[':p' . $index++]  = 'article_tome';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_COVER_VERSION)) {
            $modifiedColumns[':p' . $index++]  = 'article_cover_version';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_AVAILABILITY)) {
            $modifiedColumns[':p' . $index++]  = 'article_availability';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_AVAILABILITY_DILICOM)) {
            $modifiedColumns[':p' . $index++]  = 'article_availability_dilicom';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_PREORDER)) {
            $modifiedColumns[':p' . $index++]  = 'article_preorder';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_PRICE)) {
            $modifiedColumns[':p' . $index++]  = 'article_price';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_PRICE_EDITABLE)) {
            $modifiedColumns[':p' . $index++]  = 'article_price_editable';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_NEW_PRICE)) {
            $modifiedColumns[':p' . $index++]  = 'article_new_price';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_CATEGORY)) {
            $modifiedColumns[':p' . $index++]  = 'article_category';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_TVA)) {
            $modifiedColumns[':p' . $index++]  = 'article_tva';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_PDF_EAN)) {
            $modifiedColumns[':p' . $index++]  = 'article_pdf_ean';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_PDF_VERSION)) {
            $modifiedColumns[':p' . $index++]  = 'article_pdf_version';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_EPUB_EAN)) {
            $modifiedColumns[':p' . $index++]  = 'article_epub_ean';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_EPUB_VERSION)) {
            $modifiedColumns[':p' . $index++]  = 'article_epub_version';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_AZW_EAN)) {
            $modifiedColumns[':p' . $index++]  = 'article_azw_ean';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_AZW_VERSION)) {
            $modifiedColumns[':p' . $index++]  = 'article_azw_version';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_PAGES)) {
            $modifiedColumns[':p' . $index++]  = 'article_pages';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_WEIGHT)) {
            $modifiedColumns[':p' . $index++]  = 'article_weight';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_SHAPING)) {
            $modifiedColumns[':p' . $index++]  = 'article_shaping';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_FORMAT)) {
            $modifiedColumns[':p' . $index++]  = 'article_format';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_PRINTING_PROCESS)) {
            $modifiedColumns[':p' . $index++]  = 'article_printing_process';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_AGE_MIN)) {
            $modifiedColumns[':p' . $index++]  = 'article_age_min';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_AGE_MAX)) {
            $modifiedColumns[':p' . $index++]  = 'article_age_max';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_SUMMARY)) {
            $modifiedColumns[':p' . $index++]  = 'article_summary';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_CONTENTS)) {
            $modifiedColumns[':p' . $index++]  = 'article_contents';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_BONUS)) {
            $modifiedColumns[':p' . $index++]  = 'article_bonus';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_CATCHLINE)) {
            $modifiedColumns[':p' . $index++]  = 'article_catchline';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_BIOGRAPHY)) {
            $modifiedColumns[':p' . $index++]  = 'article_biography';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_MOTSV)) {
            $modifiedColumns[':p' . $index++]  = 'article_motsv';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_COPYRIGHT)) {
            $modifiedColumns[':p' . $index++]  = 'article_copyright';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_PUBDATE)) {
            $modifiedColumns[':p' . $index++]  = 'article_pubdate';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_KEYWORDS)) {
            $modifiedColumns[':p' . $index++]  = 'article_keywords';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_LINKS)) {
            $modifiedColumns[':p' . $index++]  = 'article_links';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_KEYWORDS_GENERATED)) {
            $modifiedColumns[':p' . $index++]  = 'article_keywords_generated';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_PUBLISHER_STOCK)) {
            $modifiedColumns[':p' . $index++]  = 'article_publisher_stock';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_HITS)) {
            $modifiedColumns[':p' . $index++]  = 'article_hits';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_EDITING_USER)) {
            $modifiedColumns[':p' . $index++]  = 'article_editing_user';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_INSERT)) {
            $modifiedColumns[':p' . $index++]  = 'article_insert';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_UPDATE)) {
            $modifiedColumns[':p' . $index++]  = 'article_update';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'article_created';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = 'article_updated';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_DONE)) {
            $modifiedColumns[':p' . $index++]  = 'article_done';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_TO_CHECK)) {
            $modifiedColumns[':p' . $index++]  = 'article_to_check';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_PUSHED_TO_DATA)) {
            $modifiedColumns[':p' . $index++]  = 'article_pushed_to_data';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_DELETION_BY)) {
            $modifiedColumns[':p' . $index++]  = 'article_deletion_by';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_DELETION_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'article_deletion_date';
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_DELETION_REASON)) {
            $modifiedColumns[':p' . $index++]  = 'article_deletion_reason';
        }

        $sql = sprintf(
            'INSERT INTO articles (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'article_id':
                        $stmt->bindValue($identifier, $this->article_id, PDO::PARAM_INT);
                        break;
                    case 'article_item':
                        $stmt->bindValue($identifier, $this->article_item, PDO::PARAM_INT);
                        break;
                    case 'article_textid':
                        $stmt->bindValue($identifier, $this->article_textid, PDO::PARAM_STR);
                        break;
                    case 'article_ean':
                        $stmt->bindValue($identifier, $this->article_ean, PDO::PARAM_INT);
                        break;
                    case 'article_ean_others':
                        $stmt->bindValue($identifier, $this->article_ean_others, PDO::PARAM_STR);
                        break;
                    case 'article_asin':
                        $stmt->bindValue($identifier, $this->article_asin, PDO::PARAM_STR);
                        break;
                    case 'article_noosfere_id':
                        $stmt->bindValue($identifier, $this->article_noosfere_id, PDO::PARAM_INT);
                        break;
                    case 'article_url':
                        $stmt->bindValue($identifier, $this->article_url, PDO::PARAM_STR);
                        break;
                    case 'type_id':
                        $stmt->bindValue($identifier, $this->type_id, PDO::PARAM_INT);
                        break;
                    case 'article_title':
                        $stmt->bindValue($identifier, $this->article_title, PDO::PARAM_STR);
                        break;
                    case 'article_title_alphabetic':
                        $stmt->bindValue($identifier, $this->article_title_alphabetic, PDO::PARAM_STR);
                        break;
                    case 'article_title_original':
                        $stmt->bindValue($identifier, $this->article_title_original, PDO::PARAM_STR);
                        break;
                    case 'article_title_others':
                        $stmt->bindValue($identifier, $this->article_title_others, PDO::PARAM_STR);
                        break;
                    case 'article_subtitle':
                        $stmt->bindValue($identifier, $this->article_subtitle, PDO::PARAM_STR);
                        break;
                    case 'article_lang_current':
                        $stmt->bindValue($identifier, $this->article_lang_current, PDO::PARAM_INT);
                        break;
                    case 'article_lang_original':
                        $stmt->bindValue($identifier, $this->article_lang_original, PDO::PARAM_INT);
                        break;
                    case 'article_origin_country':
                        $stmt->bindValue($identifier, $this->article_origin_country, PDO::PARAM_INT);
                        break;
                    case 'article_theme_bisac':
                        $stmt->bindValue($identifier, $this->article_theme_bisac, PDO::PARAM_STR);
                        break;
                    case 'article_theme_clil':
                        $stmt->bindValue($identifier, $this->article_theme_clil, PDO::PARAM_STR);
                        break;
                    case 'article_theme_dewey':
                        $stmt->bindValue($identifier, $this->article_theme_dewey, PDO::PARAM_STR);
                        break;
                    case 'article_theme_electre':
                        $stmt->bindValue($identifier, $this->article_theme_electre, PDO::PARAM_STR);
                        break;
                    case 'article_source_id':
                        $stmt->bindValue($identifier, $this->article_source_id, PDO::PARAM_INT);
                        break;
                    case 'article_authors':
                        $stmt->bindValue($identifier, $this->article_authors, PDO::PARAM_STR);
                        break;
                    case 'article_authors_alphabetic':
                        $stmt->bindValue($identifier, $this->article_authors_alphabetic, PDO::PARAM_STR);
                        break;
                    case 'collection_id':
                        $stmt->bindValue($identifier, $this->collection_id, PDO::PARAM_INT);
                        break;
                    case 'article_collection':
                        $stmt->bindValue($identifier, $this->article_collection, PDO::PARAM_STR);
                        break;
                    case 'article_number':
                        $stmt->bindValue($identifier, $this->article_number, PDO::PARAM_STR);
                        break;
                    case 'publisher_id':
                        $stmt->bindValue($identifier, $this->publisher_id, PDO::PARAM_INT);
                        break;
                    case 'article_publisher':
                        $stmt->bindValue($identifier, $this->article_publisher, PDO::PARAM_STR);
                        break;
                    case 'cycle_id':
                        $stmt->bindValue($identifier, $this->cycle_id, PDO::PARAM_INT);
                        break;
                    case 'article_cycle':
                        $stmt->bindValue($identifier, $this->article_cycle, PDO::PARAM_STR);
                        break;
                    case 'article_tome':
                        $stmt->bindValue($identifier, $this->article_tome, PDO::PARAM_STR);
                        break;
                    case 'article_cover_version':
                        $stmt->bindValue($identifier, $this->article_cover_version, PDO::PARAM_INT);
                        break;
                    case 'article_availability':
                        $stmt->bindValue($identifier, $this->article_availability, PDO::PARAM_INT);
                        break;
                    case 'article_availability_dilicom':
                        $stmt->bindValue($identifier, $this->article_availability_dilicom, PDO::PARAM_INT);
                        break;
                    case 'article_preorder':
                        $stmt->bindValue($identifier, (int) $this->article_preorder, PDO::PARAM_INT);
                        break;
                    case 'article_price':
                        $stmt->bindValue($identifier, $this->article_price, PDO::PARAM_INT);
                        break;
                    case 'article_price_editable':
                        $stmt->bindValue($identifier, (int) $this->article_price_editable, PDO::PARAM_INT);
                        break;
                    case 'article_new_price':
                        $stmt->bindValue($identifier, $this->article_new_price, PDO::PARAM_INT);
                        break;
                    case 'article_category':
                        $stmt->bindValue($identifier, $this->article_category, PDO::PARAM_STR);
                        break;
                    case 'article_tva':
                        $stmt->bindValue($identifier, $this->article_tva, PDO::PARAM_INT);
                        break;
                    case 'article_pdf_ean':
                        $stmt->bindValue($identifier, $this->article_pdf_ean, PDO::PARAM_INT);
                        break;
                    case 'article_pdf_version':
                        $stmt->bindValue($identifier, $this->article_pdf_version, PDO::PARAM_STR);
                        break;
                    case 'article_epub_ean':
                        $stmt->bindValue($identifier, $this->article_epub_ean, PDO::PARAM_INT);
                        break;
                    case 'article_epub_version':
                        $stmt->bindValue($identifier, $this->article_epub_version, PDO::PARAM_STR);
                        break;
                    case 'article_azw_ean':
                        $stmt->bindValue($identifier, $this->article_azw_ean, PDO::PARAM_INT);
                        break;
                    case 'article_azw_version':
                        $stmt->bindValue($identifier, $this->article_azw_version, PDO::PARAM_STR);
                        break;
                    case 'article_pages':
                        $stmt->bindValue($identifier, $this->article_pages, PDO::PARAM_INT);
                        break;
                    case 'article_weight':
                        $stmt->bindValue($identifier, $this->article_weight, PDO::PARAM_INT);
                        break;
                    case 'article_shaping':
                        $stmt->bindValue($identifier, $this->article_shaping, PDO::PARAM_STR);
                        break;
                    case 'article_format':
                        $stmt->bindValue($identifier, $this->article_format, PDO::PARAM_STR);
                        break;
                    case 'article_printing_process':
                        $stmt->bindValue($identifier, $this->article_printing_process, PDO::PARAM_STR);
                        break;
                    case 'article_age_min':
                        $stmt->bindValue($identifier, $this->article_age_min, PDO::PARAM_INT);
                        break;
                    case 'article_age_max':
                        $stmt->bindValue($identifier, $this->article_age_max, PDO::PARAM_INT);
                        break;
                    case 'article_summary':
                        $stmt->bindValue($identifier, $this->article_summary, PDO::PARAM_STR);
                        break;
                    case 'article_contents':
                        $stmt->bindValue($identifier, $this->article_contents, PDO::PARAM_STR);
                        break;
                    case 'article_bonus':
                        $stmt->bindValue($identifier, $this->article_bonus, PDO::PARAM_STR);
                        break;
                    case 'article_catchline':
                        $stmt->bindValue($identifier, $this->article_catchline, PDO::PARAM_STR);
                        break;
                    case 'article_biography':
                        $stmt->bindValue($identifier, $this->article_biography, PDO::PARAM_STR);
                        break;
                    case 'article_motsv':
                        $stmt->bindValue($identifier, $this->article_motsv, PDO::PARAM_STR);
                        break;
                    case 'article_copyright':
                        $stmt->bindValue($identifier, $this->article_copyright, PDO::PARAM_INT);
                        break;
                    case 'article_pubdate':
                        $stmt->bindValue($identifier, $this->article_pubdate ? $this->article_pubdate->format("Y-m-d") : null, PDO::PARAM_STR);
                        break;
                    case 'article_keywords':
                        $stmt->bindValue($identifier, $this->article_keywords, PDO::PARAM_STR);
                        break;
                    case 'article_links':
                        $stmt->bindValue($identifier, $this->article_links, PDO::PARAM_STR);
                        break;
                    case 'article_keywords_generated':
                        $stmt->bindValue($identifier, $this->article_keywords_generated ? $this->article_keywords_generated->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'article_publisher_stock':
                        $stmt->bindValue($identifier, $this->article_publisher_stock, PDO::PARAM_INT);
                        break;
                    case 'article_hits':
                        $stmt->bindValue($identifier, $this->article_hits, PDO::PARAM_INT);
                        break;
                    case 'article_editing_user':
                        $stmt->bindValue($identifier, $this->article_editing_user, PDO::PARAM_INT);
                        break;
                    case 'article_insert':
                        $stmt->bindValue($identifier, $this->article_insert ? $this->article_insert->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'article_update':
                        $stmt->bindValue($identifier, $this->article_update ? $this->article_update->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'article_created':
                        $stmt->bindValue($identifier, $this->article_created ? $this->article_created->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'article_updated':
                        $stmt->bindValue($identifier, $this->article_updated ? $this->article_updated->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'article_done':
                        $stmt->bindValue($identifier, (int) $this->article_done, PDO::PARAM_INT);
                        break;
                    case 'article_to_check':
                        $stmt->bindValue($identifier, (int) $this->article_to_check, PDO::PARAM_INT);
                        break;
                    case 'article_pushed_to_data':
                        $stmt->bindValue($identifier, $this->article_pushed_to_data ? $this->article_pushed_to_data->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'article_deletion_by':
                        $stmt->bindValue($identifier, $this->article_deletion_by, PDO::PARAM_INT);
                        break;
                    case 'article_deletion_date':
                        $stmt->bindValue($identifier, $this->article_deletion_date ? $this->article_deletion_date->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'article_deletion_reason':
                        $stmt->bindValue($identifier, $this->article_deletion_reason, PDO::PARAM_STR);
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
        $pos = ArticleTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getItem();
                break;
            case 2:
                return $this->getTextid();
                break;
            case 3:
                return $this->getEan();
                break;
            case 4:
                return $this->getEanOthers();
                break;
            case 5:
                return $this->getAsin();
                break;
            case 6:
                return $this->getNoosfereId();
                break;
            case 7:
                return $this->getUrl();
                break;
            case 8:
                return $this->getTypeId();
                break;
            case 9:
                return $this->getTitle();
                break;
            case 10:
                return $this->getTitleAlphabetic();
                break;
            case 11:
                return $this->getTitleOriginal();
                break;
            case 12:
                return $this->getTitleOthers();
                break;
            case 13:
                return $this->getSubtitle();
                break;
            case 14:
                return $this->getLangCurrent();
                break;
            case 15:
                return $this->getLangOriginal();
                break;
            case 16:
                return $this->getOriginCountry();
                break;
            case 17:
                return $this->getThemeBisac();
                break;
            case 18:
                return $this->getThemeClil();
                break;
            case 19:
                return $this->getThemeDewey();
                break;
            case 20:
                return $this->getThemeElectre();
                break;
            case 21:
                return $this->getSourceId();
                break;
            case 22:
                return $this->getAuthors();
                break;
            case 23:
                return $this->getAuthorsAlphabetic();
                break;
            case 24:
                return $this->getCollectionId();
                break;
            case 25:
                return $this->getCollectionName();
                break;
            case 26:
                return $this->getNumber();
                break;
            case 27:
                return $this->getPublisherId();
                break;
            case 28:
                return $this->getPublisherName();
                break;
            case 29:
                return $this->getCycleId();
                break;
            case 30:
                return $this->getCycle();
                break;
            case 31:
                return $this->getTome();
                break;
            case 32:
                return $this->getCoverVersion();
                break;
            case 33:
                return $this->getAvailability();
                break;
            case 34:
                return $this->getAvailabilityDilicom();
                break;
            case 35:
                return $this->getPreorder();
                break;
            case 36:
                return $this->getPrice();
                break;
            case 37:
                return $this->getPriceEditable();
                break;
            case 38:
                return $this->getNewPrice();
                break;
            case 39:
                return $this->getCategory();
                break;
            case 40:
                return $this->getTva();
                break;
            case 41:
                return $this->getPdfEan();
                break;
            case 42:
                return $this->getPdfVersion();
                break;
            case 43:
                return $this->getEpubEan();
                break;
            case 44:
                return $this->getEpubVersion();
                break;
            case 45:
                return $this->getAzwEan();
                break;
            case 46:
                return $this->getAzwVersion();
                break;
            case 47:
                return $this->getPages();
                break;
            case 48:
                return $this->getWeight();
                break;
            case 49:
                return $this->getShaping();
                break;
            case 50:
                return $this->getFormat();
                break;
            case 51:
                return $this->getPrintingProcess();
                break;
            case 52:
                return $this->getAgeMin();
                break;
            case 53:
                return $this->getAgeMax();
                break;
            case 54:
                return $this->getSummary();
                break;
            case 55:
                return $this->getContents();
                break;
            case 56:
                return $this->getBonus();
                break;
            case 57:
                return $this->getCatchline();
                break;
            case 58:
                return $this->getBiography();
                break;
            case 59:
                return $this->getMotsv();
                break;
            case 60:
                return $this->getCopyright();
                break;
            case 61:
                return $this->getPubdate();
                break;
            case 62:
                return $this->getKeywords();
                break;
            case 63:
                return $this->getComputedLinks();
                break;
            case 64:
                return $this->getKeywordsGenerated();
                break;
            case 65:
                return $this->getPublisherStock();
                break;
            case 66:
                return $this->getHits();
                break;
            case 67:
                return $this->getEditingUser();
                break;
            case 68:
                return $this->getInsert();
                break;
            case 69:
                return $this->getUpdate();
                break;
            case 70:
                return $this->getCreatedAt();
                break;
            case 71:
                return $this->getUpdatedAt();
                break;
            case 72:
                return $this->getDone();
                break;
            case 73:
                return $this->getToCheck();
                break;
            case 74:
                return $this->getPushedToData();
                break;
            case 75:
                return $this->getDeletionBy();
                break;
            case 76:
                return $this->getDeletionDate();
                break;
            case 77:
                return $this->getDeletionReason();
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

        if (isset($alreadyDumpedObjects['Article'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Article'][$this->hashCode()] = true;
        $keys = ArticleTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getItem(),
            $keys[2] => $this->getTextid(),
            $keys[3] => $this->getEan(),
            $keys[4] => $this->getEanOthers(),
            $keys[5] => $this->getAsin(),
            $keys[6] => $this->getNoosfereId(),
            $keys[7] => $this->getUrl(),
            $keys[8] => $this->getTypeId(),
            $keys[9] => $this->getTitle(),
            $keys[10] => $this->getTitleAlphabetic(),
            $keys[11] => $this->getTitleOriginal(),
            $keys[12] => $this->getTitleOthers(),
            $keys[13] => $this->getSubtitle(),
            $keys[14] => $this->getLangCurrent(),
            $keys[15] => $this->getLangOriginal(),
            $keys[16] => $this->getOriginCountry(),
            $keys[17] => $this->getThemeBisac(),
            $keys[18] => $this->getThemeClil(),
            $keys[19] => $this->getThemeDewey(),
            $keys[20] => $this->getThemeElectre(),
            $keys[21] => $this->getSourceId(),
            $keys[22] => $this->getAuthors(),
            $keys[23] => $this->getAuthorsAlphabetic(),
            $keys[24] => $this->getCollectionId(),
            $keys[25] => $this->getCollectionName(),
            $keys[26] => $this->getNumber(),
            $keys[27] => $this->getPublisherId(),
            $keys[28] => $this->getPublisherName(),
            $keys[29] => $this->getCycleId(),
            $keys[30] => $this->getCycle(),
            $keys[31] => $this->getTome(),
            $keys[32] => $this->getCoverVersion(),
            $keys[33] => $this->getAvailability(),
            $keys[34] => $this->getAvailabilityDilicom(),
            $keys[35] => $this->getPreorder(),
            $keys[36] => $this->getPrice(),
            $keys[37] => $this->getPriceEditable(),
            $keys[38] => $this->getNewPrice(),
            $keys[39] => $this->getCategory(),
            $keys[40] => $this->getTva(),
            $keys[41] => $this->getPdfEan(),
            $keys[42] => $this->getPdfVersion(),
            $keys[43] => $this->getEpubEan(),
            $keys[44] => $this->getEpubVersion(),
            $keys[45] => $this->getAzwEan(),
            $keys[46] => $this->getAzwVersion(),
            $keys[47] => $this->getPages(),
            $keys[48] => $this->getWeight(),
            $keys[49] => $this->getShaping(),
            $keys[50] => $this->getFormat(),
            $keys[51] => $this->getPrintingProcess(),
            $keys[52] => $this->getAgeMin(),
            $keys[53] => $this->getAgeMax(),
            $keys[54] => $this->getSummary(),
            $keys[55] => $this->getContents(),
            $keys[56] => $this->getBonus(),
            $keys[57] => $this->getCatchline(),
            $keys[58] => $this->getBiography(),
            $keys[59] => $this->getMotsv(),
            $keys[60] => $this->getCopyright(),
            $keys[61] => $this->getPubdate(),
            $keys[62] => $this->getKeywords(),
            $keys[63] => $this->getComputedLinks(),
            $keys[64] => $this->getKeywordsGenerated(),
            $keys[65] => $this->getPublisherStock(),
            $keys[66] => $this->getHits(),
            $keys[67] => $this->getEditingUser(),
            $keys[68] => $this->getInsert(),
            $keys[69] => $this->getUpdate(),
            $keys[70] => $this->getCreatedAt(),
            $keys[71] => $this->getUpdatedAt(),
            $keys[72] => $this->getDone(),
            $keys[73] => $this->getToCheck(),
            $keys[74] => $this->getPushedToData(),
            $keys[75] => $this->getDeletionBy(),
            $keys[76] => $this->getDeletionDate(),
            $keys[77] => $this->getDeletionReason(),
        );
        if ($result[$keys[61]] instanceof \DateTimeInterface) {
            $result[$keys[61]] = $result[$keys[61]]->format('Y-m-d');
        }

        if ($result[$keys[64]] instanceof \DateTimeInterface) {
            $result[$keys[64]] = $result[$keys[64]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[68]] instanceof \DateTimeInterface) {
            $result[$keys[68]] = $result[$keys[68]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[69]] instanceof \DateTimeInterface) {
            $result[$keys[69]] = $result[$keys[69]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[70]] instanceof \DateTimeInterface) {
            $result[$keys[70]] = $result[$keys[70]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[71]] instanceof \DateTimeInterface) {
            $result[$keys[71]] = $result[$keys[71]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[74]] instanceof \DateTimeInterface) {
            $result[$keys[74]] = $result[$keys[74]]->format('Y-m-d H:i:s.u');
        }

        if ($result[$keys[76]] instanceof \DateTimeInterface) {
            $result[$keys[76]] = $result[$keys[76]]->format('Y-m-d H:i:s.u');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aPublisher) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'publisher';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'publishers';
                        break;
                    default:
                        $key = 'Publisher';
                }

                $result[$key] = $this->aPublisher->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aBookCollection) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'bookCollection';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'collections';
                        break;
                    default:
                        $key = 'BookCollection';
                }

                $result[$key] = $this->aBookCollection->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\Model\Article
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = ArticleTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Model\Article
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setItem($value);
                break;
            case 2:
                $this->setTextid($value);
                break;
            case 3:
                $this->setEan($value);
                break;
            case 4:
                $this->setEanOthers($value);
                break;
            case 5:
                $this->setAsin($value);
                break;
            case 6:
                $this->setNoosfereId($value);
                break;
            case 7:
                $this->setUrl($value);
                break;
            case 8:
                $this->setTypeId($value);
                break;
            case 9:
                $this->setTitle($value);
                break;
            case 10:
                $this->setTitleAlphabetic($value);
                break;
            case 11:
                $this->setTitleOriginal($value);
                break;
            case 12:
                $this->setTitleOthers($value);
                break;
            case 13:
                $this->setSubtitle($value);
                break;
            case 14:
                $this->setLangCurrent($value);
                break;
            case 15:
                $this->setLangOriginal($value);
                break;
            case 16:
                $this->setOriginCountry($value);
                break;
            case 17:
                $this->setThemeBisac($value);
                break;
            case 18:
                $this->setThemeClil($value);
                break;
            case 19:
                $this->setThemeDewey($value);
                break;
            case 20:
                $this->setThemeElectre($value);
                break;
            case 21:
                $this->setSourceId($value);
                break;
            case 22:
                $this->setAuthors($value);
                break;
            case 23:
                $this->setAuthorsAlphabetic($value);
                break;
            case 24:
                $this->setCollectionId($value);
                break;
            case 25:
                $this->setCollectionName($value);
                break;
            case 26:
                $this->setNumber($value);
                break;
            case 27:
                $this->setPublisherId($value);
                break;
            case 28:
                $this->setPublisherName($value);
                break;
            case 29:
                $this->setCycleId($value);
                break;
            case 30:
                $this->setCycle($value);
                break;
            case 31:
                $this->setTome($value);
                break;
            case 32:
                $this->setCoverVersion($value);
                break;
            case 33:
                $this->setAvailability($value);
                break;
            case 34:
                $this->setAvailabilityDilicom($value);
                break;
            case 35:
                $this->setPreorder($value);
                break;
            case 36:
                $this->setPrice($value);
                break;
            case 37:
                $this->setPriceEditable($value);
                break;
            case 38:
                $this->setNewPrice($value);
                break;
            case 39:
                $this->setCategory($value);
                break;
            case 40:
                $this->setTva($value);
                break;
            case 41:
                $this->setPdfEan($value);
                break;
            case 42:
                $this->setPdfVersion($value);
                break;
            case 43:
                $this->setEpubEan($value);
                break;
            case 44:
                $this->setEpubVersion($value);
                break;
            case 45:
                $this->setAzwEan($value);
                break;
            case 46:
                $this->setAzwVersion($value);
                break;
            case 47:
                $this->setPages($value);
                break;
            case 48:
                $this->setWeight($value);
                break;
            case 49:
                $this->setShaping($value);
                break;
            case 50:
                $this->setFormat($value);
                break;
            case 51:
                $this->setPrintingProcess($value);
                break;
            case 52:
                $this->setAgeMin($value);
                break;
            case 53:
                $this->setAgeMax($value);
                break;
            case 54:
                $this->setSummary($value);
                break;
            case 55:
                $this->setContents($value);
                break;
            case 56:
                $this->setBonus($value);
                break;
            case 57:
                $this->setCatchline($value);
                break;
            case 58:
                $this->setBiography($value);
                break;
            case 59:
                $this->setMotsv($value);
                break;
            case 60:
                $this->setCopyright($value);
                break;
            case 61:
                $this->setPubdate($value);
                break;
            case 62:
                $this->setKeywords($value);
                break;
            case 63:
                $this->setComputedLinks($value);
                break;
            case 64:
                $this->setKeywordsGenerated($value);
                break;
            case 65:
                $this->setPublisherStock($value);
                break;
            case 66:
                $this->setHits($value);
                break;
            case 67:
                $this->setEditingUser($value);
                break;
            case 68:
                $this->setInsert($value);
                break;
            case 69:
                $this->setUpdate($value);
                break;
            case 70:
                $this->setCreatedAt($value);
                break;
            case 71:
                $this->setUpdatedAt($value);
                break;
            case 72:
                $this->setDone($value);
                break;
            case 73:
                $this->setToCheck($value);
                break;
            case 74:
                $this->setPushedToData($value);
                break;
            case 75:
                $this->setDeletionBy($value);
                break;
            case 76:
                $this->setDeletionDate($value);
                break;
            case 77:
                $this->setDeletionReason($value);
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
     * @return     $this|\Model\Article
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = ArticleTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setItem($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setTextid($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setEan($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setEanOthers($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setAsin($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setNoosfereId($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setUrl($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setTypeId($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setTitle($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setTitleAlphabetic($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setTitleOriginal($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setTitleOthers($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setSubtitle($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setLangCurrent($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setLangOriginal($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setOriginCountry($arr[$keys[16]]);
        }
        if (array_key_exists($keys[17], $arr)) {
            $this->setThemeBisac($arr[$keys[17]]);
        }
        if (array_key_exists($keys[18], $arr)) {
            $this->setThemeClil($arr[$keys[18]]);
        }
        if (array_key_exists($keys[19], $arr)) {
            $this->setThemeDewey($arr[$keys[19]]);
        }
        if (array_key_exists($keys[20], $arr)) {
            $this->setThemeElectre($arr[$keys[20]]);
        }
        if (array_key_exists($keys[21], $arr)) {
            $this->setSourceId($arr[$keys[21]]);
        }
        if (array_key_exists($keys[22], $arr)) {
            $this->setAuthors($arr[$keys[22]]);
        }
        if (array_key_exists($keys[23], $arr)) {
            $this->setAuthorsAlphabetic($arr[$keys[23]]);
        }
        if (array_key_exists($keys[24], $arr)) {
            $this->setCollectionId($arr[$keys[24]]);
        }
        if (array_key_exists($keys[25], $arr)) {
            $this->setCollectionName($arr[$keys[25]]);
        }
        if (array_key_exists($keys[26], $arr)) {
            $this->setNumber($arr[$keys[26]]);
        }
        if (array_key_exists($keys[27], $arr)) {
            $this->setPublisherId($arr[$keys[27]]);
        }
        if (array_key_exists($keys[28], $arr)) {
            $this->setPublisherName($arr[$keys[28]]);
        }
        if (array_key_exists($keys[29], $arr)) {
            $this->setCycleId($arr[$keys[29]]);
        }
        if (array_key_exists($keys[30], $arr)) {
            $this->setCycle($arr[$keys[30]]);
        }
        if (array_key_exists($keys[31], $arr)) {
            $this->setTome($arr[$keys[31]]);
        }
        if (array_key_exists($keys[32], $arr)) {
            $this->setCoverVersion($arr[$keys[32]]);
        }
        if (array_key_exists($keys[33], $arr)) {
            $this->setAvailability($arr[$keys[33]]);
        }
        if (array_key_exists($keys[34], $arr)) {
            $this->setAvailabilityDilicom($arr[$keys[34]]);
        }
        if (array_key_exists($keys[35], $arr)) {
            $this->setPreorder($arr[$keys[35]]);
        }
        if (array_key_exists($keys[36], $arr)) {
            $this->setPrice($arr[$keys[36]]);
        }
        if (array_key_exists($keys[37], $arr)) {
            $this->setPriceEditable($arr[$keys[37]]);
        }
        if (array_key_exists($keys[38], $arr)) {
            $this->setNewPrice($arr[$keys[38]]);
        }
        if (array_key_exists($keys[39], $arr)) {
            $this->setCategory($arr[$keys[39]]);
        }
        if (array_key_exists($keys[40], $arr)) {
            $this->setTva($arr[$keys[40]]);
        }
        if (array_key_exists($keys[41], $arr)) {
            $this->setPdfEan($arr[$keys[41]]);
        }
        if (array_key_exists($keys[42], $arr)) {
            $this->setPdfVersion($arr[$keys[42]]);
        }
        if (array_key_exists($keys[43], $arr)) {
            $this->setEpubEan($arr[$keys[43]]);
        }
        if (array_key_exists($keys[44], $arr)) {
            $this->setEpubVersion($arr[$keys[44]]);
        }
        if (array_key_exists($keys[45], $arr)) {
            $this->setAzwEan($arr[$keys[45]]);
        }
        if (array_key_exists($keys[46], $arr)) {
            $this->setAzwVersion($arr[$keys[46]]);
        }
        if (array_key_exists($keys[47], $arr)) {
            $this->setPages($arr[$keys[47]]);
        }
        if (array_key_exists($keys[48], $arr)) {
            $this->setWeight($arr[$keys[48]]);
        }
        if (array_key_exists($keys[49], $arr)) {
            $this->setShaping($arr[$keys[49]]);
        }
        if (array_key_exists($keys[50], $arr)) {
            $this->setFormat($arr[$keys[50]]);
        }
        if (array_key_exists($keys[51], $arr)) {
            $this->setPrintingProcess($arr[$keys[51]]);
        }
        if (array_key_exists($keys[52], $arr)) {
            $this->setAgeMin($arr[$keys[52]]);
        }
        if (array_key_exists($keys[53], $arr)) {
            $this->setAgeMax($arr[$keys[53]]);
        }
        if (array_key_exists($keys[54], $arr)) {
            $this->setSummary($arr[$keys[54]]);
        }
        if (array_key_exists($keys[55], $arr)) {
            $this->setContents($arr[$keys[55]]);
        }
        if (array_key_exists($keys[56], $arr)) {
            $this->setBonus($arr[$keys[56]]);
        }
        if (array_key_exists($keys[57], $arr)) {
            $this->setCatchline($arr[$keys[57]]);
        }
        if (array_key_exists($keys[58], $arr)) {
            $this->setBiography($arr[$keys[58]]);
        }
        if (array_key_exists($keys[59], $arr)) {
            $this->setMotsv($arr[$keys[59]]);
        }
        if (array_key_exists($keys[60], $arr)) {
            $this->setCopyright($arr[$keys[60]]);
        }
        if (array_key_exists($keys[61], $arr)) {
            $this->setPubdate($arr[$keys[61]]);
        }
        if (array_key_exists($keys[62], $arr)) {
            $this->setKeywords($arr[$keys[62]]);
        }
        if (array_key_exists($keys[63], $arr)) {
            $this->setComputedLinks($arr[$keys[63]]);
        }
        if (array_key_exists($keys[64], $arr)) {
            $this->setKeywordsGenerated($arr[$keys[64]]);
        }
        if (array_key_exists($keys[65], $arr)) {
            $this->setPublisherStock($arr[$keys[65]]);
        }
        if (array_key_exists($keys[66], $arr)) {
            $this->setHits($arr[$keys[66]]);
        }
        if (array_key_exists($keys[67], $arr)) {
            $this->setEditingUser($arr[$keys[67]]);
        }
        if (array_key_exists($keys[68], $arr)) {
            $this->setInsert($arr[$keys[68]]);
        }
        if (array_key_exists($keys[69], $arr)) {
            $this->setUpdate($arr[$keys[69]]);
        }
        if (array_key_exists($keys[70], $arr)) {
            $this->setCreatedAt($arr[$keys[70]]);
        }
        if (array_key_exists($keys[71], $arr)) {
            $this->setUpdatedAt($arr[$keys[71]]);
        }
        if (array_key_exists($keys[72], $arr)) {
            $this->setDone($arr[$keys[72]]);
        }
        if (array_key_exists($keys[73], $arr)) {
            $this->setToCheck($arr[$keys[73]]);
        }
        if (array_key_exists($keys[74], $arr)) {
            $this->setPushedToData($arr[$keys[74]]);
        }
        if (array_key_exists($keys[75], $arr)) {
            $this->setDeletionBy($arr[$keys[75]]);
        }
        if (array_key_exists($keys[76], $arr)) {
            $this->setDeletionDate($arr[$keys[76]]);
        }
        if (array_key_exists($keys[77], $arr)) {
            $this->setDeletionReason($arr[$keys[77]]);
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
     * @return $this|\Model\Article The current object, for fluid interface
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
        $criteria = new Criteria(ArticleTableMap::DATABASE_NAME);

        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_ID)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_ID, $this->article_id);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_ITEM)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_ITEM, $this->article_item);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_TEXTID)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_TEXTID, $this->article_textid);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_EAN)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_EAN, $this->article_ean);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_EAN_OTHERS)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_EAN_OTHERS, $this->article_ean_others);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_ASIN)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_ASIN, $this->article_asin);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_NOOSFERE_ID)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_NOOSFERE_ID, $this->article_noosfere_id);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_URL)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_URL, $this->article_url);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_TYPE_ID)) {
            $criteria->add(ArticleTableMap::COL_TYPE_ID, $this->type_id);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_TITLE)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_TITLE, $this->article_title);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_TITLE_ALPHABETIC)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_TITLE_ALPHABETIC, $this->article_title_alphabetic);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_TITLE_ORIGINAL)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_TITLE_ORIGINAL, $this->article_title_original);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_TITLE_OTHERS)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_TITLE_OTHERS, $this->article_title_others);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_SUBTITLE)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_SUBTITLE, $this->article_subtitle);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_LANG_CURRENT)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_LANG_CURRENT, $this->article_lang_current);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_LANG_ORIGINAL)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_LANG_ORIGINAL, $this->article_lang_original);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_ORIGIN_COUNTRY)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_ORIGIN_COUNTRY, $this->article_origin_country);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_THEME_BISAC)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_THEME_BISAC, $this->article_theme_bisac);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_THEME_CLIL)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_THEME_CLIL, $this->article_theme_clil);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_THEME_DEWEY)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_THEME_DEWEY, $this->article_theme_dewey);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_THEME_ELECTRE)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_THEME_ELECTRE, $this->article_theme_electre);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_SOURCE_ID)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_SOURCE_ID, $this->article_source_id);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_AUTHORS)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_AUTHORS, $this->article_authors);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_AUTHORS_ALPHABETIC)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_AUTHORS_ALPHABETIC, $this->article_authors_alphabetic);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_COLLECTION_ID)) {
            $criteria->add(ArticleTableMap::COL_COLLECTION_ID, $this->collection_id);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_COLLECTION)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_COLLECTION, $this->article_collection);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_NUMBER)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_NUMBER, $this->article_number);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_PUBLISHER_ID)) {
            $criteria->add(ArticleTableMap::COL_PUBLISHER_ID, $this->publisher_id);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_PUBLISHER)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_PUBLISHER, $this->article_publisher);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_CYCLE_ID)) {
            $criteria->add(ArticleTableMap::COL_CYCLE_ID, $this->cycle_id);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_CYCLE)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_CYCLE, $this->article_cycle);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_TOME)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_TOME, $this->article_tome);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_COVER_VERSION)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_COVER_VERSION, $this->article_cover_version);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_AVAILABILITY)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_AVAILABILITY, $this->article_availability);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_AVAILABILITY_DILICOM)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_AVAILABILITY_DILICOM, $this->article_availability_dilicom);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_PREORDER)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_PREORDER, $this->article_preorder);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_PRICE)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_PRICE, $this->article_price);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_PRICE_EDITABLE)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_PRICE_EDITABLE, $this->article_price_editable);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_NEW_PRICE)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_NEW_PRICE, $this->article_new_price);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_CATEGORY)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_CATEGORY, $this->article_category);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_TVA)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_TVA, $this->article_tva);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_PDF_EAN)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_PDF_EAN, $this->article_pdf_ean);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_PDF_VERSION)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_PDF_VERSION, $this->article_pdf_version);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_EPUB_EAN)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_EPUB_EAN, $this->article_epub_ean);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_EPUB_VERSION)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_EPUB_VERSION, $this->article_epub_version);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_AZW_EAN)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_AZW_EAN, $this->article_azw_ean);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_AZW_VERSION)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_AZW_VERSION, $this->article_azw_version);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_PAGES)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_PAGES, $this->article_pages);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_WEIGHT)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_WEIGHT, $this->article_weight);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_SHAPING)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_SHAPING, $this->article_shaping);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_FORMAT)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_FORMAT, $this->article_format);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_PRINTING_PROCESS)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_PRINTING_PROCESS, $this->article_printing_process);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_AGE_MIN)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_AGE_MIN, $this->article_age_min);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_AGE_MAX)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_AGE_MAX, $this->article_age_max);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_SUMMARY)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_SUMMARY, $this->article_summary);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_CONTENTS)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_CONTENTS, $this->article_contents);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_BONUS)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_BONUS, $this->article_bonus);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_CATCHLINE)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_CATCHLINE, $this->article_catchline);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_BIOGRAPHY)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_BIOGRAPHY, $this->article_biography);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_MOTSV)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_MOTSV, $this->article_motsv);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_COPYRIGHT)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_COPYRIGHT, $this->article_copyright);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_PUBDATE)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_PUBDATE, $this->article_pubdate);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_KEYWORDS)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_KEYWORDS, $this->article_keywords);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_LINKS)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_LINKS, $this->article_links);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_KEYWORDS_GENERATED)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_KEYWORDS_GENERATED, $this->article_keywords_generated);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_PUBLISHER_STOCK)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_PUBLISHER_STOCK, $this->article_publisher_stock);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_HITS)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_HITS, $this->article_hits);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_EDITING_USER)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_EDITING_USER, $this->article_editing_user);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_INSERT)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_INSERT, $this->article_insert);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_UPDATE)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_UPDATE, $this->article_update);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_CREATED)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_CREATED, $this->article_created);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_UPDATED)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_UPDATED, $this->article_updated);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_DONE)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_DONE, $this->article_done);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_TO_CHECK)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_TO_CHECK, $this->article_to_check);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_PUSHED_TO_DATA)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_PUSHED_TO_DATA, $this->article_pushed_to_data);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_DELETION_BY)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_DELETION_BY, $this->article_deletion_by);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_DELETION_DATE)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_DELETION_DATE, $this->article_deletion_date);
        }
        if ($this->isColumnModified(ArticleTableMap::COL_ARTICLE_DELETION_REASON)) {
            $criteria->add(ArticleTableMap::COL_ARTICLE_DELETION_REASON, $this->article_deletion_reason);
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
        $criteria = ChildArticleQuery::create();
        $criteria->add(ArticleTableMap::COL_ARTICLE_ID, $this->article_id);

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
     * Generic method to set the primary key (article_id column).
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
     * @param      object $copyObj An object of \Model\Article (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setItem($this->getItem());
        $copyObj->setTextid($this->getTextid());
        $copyObj->setEan($this->getEan());
        $copyObj->setEanOthers($this->getEanOthers());
        $copyObj->setAsin($this->getAsin());
        $copyObj->setNoosfereId($this->getNoosfereId());
        $copyObj->setUrl($this->getUrl());
        $copyObj->setTypeId($this->getTypeId());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setTitleAlphabetic($this->getTitleAlphabetic());
        $copyObj->setTitleOriginal($this->getTitleOriginal());
        $copyObj->setTitleOthers($this->getTitleOthers());
        $copyObj->setSubtitle($this->getSubtitle());
        $copyObj->setLangCurrent($this->getLangCurrent());
        $copyObj->setLangOriginal($this->getLangOriginal());
        $copyObj->setOriginCountry($this->getOriginCountry());
        $copyObj->setThemeBisac($this->getThemeBisac());
        $copyObj->setThemeClil($this->getThemeClil());
        $copyObj->setThemeDewey($this->getThemeDewey());
        $copyObj->setThemeElectre($this->getThemeElectre());
        $copyObj->setSourceId($this->getSourceId());
        $copyObj->setAuthors($this->getAuthors());
        $copyObj->setAuthorsAlphabetic($this->getAuthorsAlphabetic());
        $copyObj->setCollectionId($this->getCollectionId());
        $copyObj->setCollectionName($this->getCollectionName());
        $copyObj->setNumber($this->getNumber());
        $copyObj->setPublisherId($this->getPublisherId());
        $copyObj->setPublisherName($this->getPublisherName());
        $copyObj->setCycleId($this->getCycleId());
        $copyObj->setCycle($this->getCycle());
        $copyObj->setTome($this->getTome());
        $copyObj->setCoverVersion($this->getCoverVersion());
        $copyObj->setAvailability($this->getAvailability());
        $copyObj->setAvailabilityDilicom($this->getAvailabilityDilicom());
        $copyObj->setPreorder($this->getPreorder());
        $copyObj->setPrice($this->getPrice());
        $copyObj->setPriceEditable($this->getPriceEditable());
        $copyObj->setNewPrice($this->getNewPrice());
        $copyObj->setCategory($this->getCategory());
        $copyObj->setTva($this->getTva());
        $copyObj->setPdfEan($this->getPdfEan());
        $copyObj->setPdfVersion($this->getPdfVersion());
        $copyObj->setEpubEan($this->getEpubEan());
        $copyObj->setEpubVersion($this->getEpubVersion());
        $copyObj->setAzwEan($this->getAzwEan());
        $copyObj->setAzwVersion($this->getAzwVersion());
        $copyObj->setPages($this->getPages());
        $copyObj->setWeight($this->getWeight());
        $copyObj->setShaping($this->getShaping());
        $copyObj->setFormat($this->getFormat());
        $copyObj->setPrintingProcess($this->getPrintingProcess());
        $copyObj->setAgeMin($this->getAgeMin());
        $copyObj->setAgeMax($this->getAgeMax());
        $copyObj->setSummary($this->getSummary());
        $copyObj->setContents($this->getContents());
        $copyObj->setBonus($this->getBonus());
        $copyObj->setCatchline($this->getCatchline());
        $copyObj->setBiography($this->getBiography());
        $copyObj->setMotsv($this->getMotsv());
        $copyObj->setCopyright($this->getCopyright());
        $copyObj->setPubdate($this->getPubdate());
        $copyObj->setKeywords($this->getKeywords());
        $copyObj->setComputedLinks($this->getComputedLinks());
        $copyObj->setKeywordsGenerated($this->getKeywordsGenerated());
        $copyObj->setPublisherStock($this->getPublisherStock());
        $copyObj->setHits($this->getHits());
        $copyObj->setEditingUser($this->getEditingUser());
        $copyObj->setInsert($this->getInsert());
        $copyObj->setUpdate($this->getUpdate());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        $copyObj->setDone($this->getDone());
        $copyObj->setToCheck($this->getToCheck());
        $copyObj->setPushedToData($this->getPushedToData());
        $copyObj->setDeletionBy($this->getDeletionBy());
        $copyObj->setDeletionDate($this->getDeletionDate());
        $copyObj->setDeletionReason($this->getDeletionReason());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getLinks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLink($relObj->copy($deepCopy));
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
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Model\Article Clone of current object.
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
     * Declares an association between this object and a ChildPublisher object.
     *
     * @param  ChildPublisher|null $v
     * @return $this|\Model\Article The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPublisher(ChildPublisher $v = null)
    {
        if ($v === null) {
            $this->setPublisherId(NULL);
        } else {
            $this->setPublisherId($v->getId());
        }

        $this->aPublisher = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildPublisher object, it will not be re-added.
        if ($v !== null) {
            $v->addArticle($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildPublisher object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildPublisher|null The associated ChildPublisher object.
     * @throws PropelException
     */
    public function getPublisher(ConnectionInterface $con = null)
    {
        if ($this->aPublisher === null && ($this->publisher_id != 0)) {
            $this->aPublisher = ChildPublisherQuery::create()->findPk($this->publisher_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPublisher->addArticles($this);
             */
        }

        return $this->aPublisher;
    }

    /**
     * Declares an association between this object and a ChildBookCollection object.
     *
     * @param  ChildBookCollection|null $v
     * @return $this|\Model\Article The current object (for fluent API support)
     * @throws PropelException
     */
    public function setBookCollection(ChildBookCollection $v = null)
    {
        if ($v === null) {
            $this->setCollectionId(NULL);
        } else {
            $this->setCollectionId($v->getId());
        }

        $this->aBookCollection = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildBookCollection object, it will not be re-added.
        if ($v !== null) {
            $v->addArticle($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildBookCollection object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildBookCollection|null The associated ChildBookCollection object.
     * @throws PropelException
     */
    public function getBookCollection(ConnectionInterface $con = null)
    {
        if ($this->aBookCollection === null && ($this->collection_id != 0)) {
            $this->aBookCollection = ChildBookCollectionQuery::create()->findPk($this->collection_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aBookCollection->addArticles($this);
             */
        }

        return $this->aBookCollection;
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
        if ('Link' === $relationName) {
            $this->initLinks();
            return;
        }
        if ('Role' === $relationName) {
            $this->initRoles();
            return;
        }
    }

    /**
     * Clears out the collLinks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addLinks()
     */
    public function clearLinks()
    {
        $this->collLinks = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collLinks collection loaded partially.
     */
    public function resetPartialLinks($v = true)
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
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLinks($overrideExisting = true)
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
     * If this ChildArticle is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildLink[] List of ChildLink objects
     * @phpstan-return ObjectCollection&\Traversable<ChildLink> List of ChildLink objects
     * @throws PropelException
     */
    public function getLinks(Criteria $criteria = null, ConnectionInterface $con = null)
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
                    ->filterByArticle($this)
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
     * @param      Collection $links A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildArticle The current object (for fluent API support)
     */
    public function setLinks(Collection $links, ConnectionInterface $con = null)
    {
        /** @var ChildLink[] $linksToDelete */
        $linksToDelete = $this->getLinks(new Criteria(), $con)->diff($links);


        $this->linksScheduledForDeletion = $linksToDelete;

        foreach ($linksToDelete as $linkRemoved) {
            $linkRemoved->setArticle(null);
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
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Link objects.
     * @throws PropelException
     */
    public function countLinks(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
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
                ->filterByArticle($this)
                ->count($con);
        }

        return count($this->collLinks);
    }

    /**
     * Method called to associate a ChildLink object to this object
     * through the ChildLink foreign key attribute.
     *
     * @param  ChildLink $l ChildLink
     * @return $this|\Model\Article The current object (for fluent API support)
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
    protected function doAddLink(ChildLink $link)
    {
        $this->collLinks[]= $link;
        $link->setArticle($this);
    }

    /**
     * @param  ChildLink $link The ChildLink object to remove.
     * @return $this|ChildArticle The current object (for fluent API support)
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
            $link->setArticle(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Article is new, it will return
     * an empty collection; or if this Article has previously
     * been saved, it will retrieve related Links from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Article.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildLink[] List of ChildLink objects
     * @phpstan-return ObjectCollection&\Traversable<ChildLink}> List of ChildLink objects
     */
    public function getLinksJoinTag(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildLinkQuery::create(null, $criteria);
        $query->joinWith('Tag', $joinBehavior);

        return $this->getLinks($query, $con);
    }

    /**
     * Clears out the collRoles collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addRoles()
     */
    public function clearRoles()
    {
        $this->collRoles = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collRoles collection loaded partially.
     */
    public function resetPartialRoles($v = true)
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
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initRoles($overrideExisting = true)
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
     * If this ChildArticle is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildRole[] List of ChildRole objects
     * @phpstan-return ObjectCollection&\Traversable<ChildRole> List of ChildRole objects
     * @throws PropelException
     */
    public function getRoles(Criteria $criteria = null, ConnectionInterface $con = null)
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
                    ->filterByArticle($this)
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
     * @param      Collection $roles A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildArticle The current object (for fluent API support)
     */
    public function setRoles(Collection $roles, ConnectionInterface $con = null)
    {
        /** @var ChildRole[] $rolesToDelete */
        $rolesToDelete = $this->getRoles(new Criteria(), $con)->diff($roles);


        $this->rolesScheduledForDeletion = $rolesToDelete;

        foreach ($rolesToDelete as $roleRemoved) {
            $roleRemoved->setArticle(null);
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
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Role objects.
     * @throws PropelException
     */
    public function countRoles(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
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
                ->filterByArticle($this)
                ->count($con);
        }

        return count($this->collRoles);
    }

    /**
     * Method called to associate a ChildRole object to this object
     * through the ChildRole foreign key attribute.
     *
     * @param  ChildRole $l ChildRole
     * @return $this|\Model\Article The current object (for fluent API support)
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
    protected function doAddRole(ChildRole $role)
    {
        $this->collRoles[]= $role;
        $role->setArticle($this);
    }

    /**
     * @param  ChildRole $role The ChildRole object to remove.
     * @return $this|ChildArticle The current object (for fluent API support)
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
            $role->setArticle(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Article is new, it will return
     * an empty collection; or if this Article has previously
     * been saved, it will retrieve related Roles from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Article.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildRole[] List of ChildRole objects
     * @phpstan-return ObjectCollection&\Traversable<ChildRole}> List of ChildRole objects
     */
    public function getRolesJoinPeople(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildRoleQuery::create(null, $criteria);
        $query->joinWith('People', $joinBehavior);

        return $this->getRoles($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aPublisher) {
            $this->aPublisher->removeArticle($this);
        }
        if (null !== $this->aBookCollection) {
            $this->aBookCollection->removeArticle($this);
        }
        $this->article_id = null;
        $this->article_item = null;
        $this->article_textid = null;
        $this->article_ean = null;
        $this->article_ean_others = null;
        $this->article_asin = null;
        $this->article_noosfere_id = null;
        $this->article_url = null;
        $this->type_id = null;
        $this->article_title = null;
        $this->article_title_alphabetic = null;
        $this->article_title_original = null;
        $this->article_title_others = null;
        $this->article_subtitle = null;
        $this->article_lang_current = null;
        $this->article_lang_original = null;
        $this->article_origin_country = null;
        $this->article_theme_bisac = null;
        $this->article_theme_clil = null;
        $this->article_theme_dewey = null;
        $this->article_theme_electre = null;
        $this->article_source_id = null;
        $this->article_authors = null;
        $this->article_authors_alphabetic = null;
        $this->collection_id = null;
        $this->article_collection = null;
        $this->article_number = null;
        $this->publisher_id = null;
        $this->article_publisher = null;
        $this->cycle_id = null;
        $this->article_cycle = null;
        $this->article_tome = null;
        $this->article_cover_version = null;
        $this->article_availability = null;
        $this->article_availability_dilicom = null;
        $this->article_preorder = null;
        $this->article_price = null;
        $this->article_price_editable = null;
        $this->article_new_price = null;
        $this->article_category = null;
        $this->article_tva = null;
        $this->article_pdf_ean = null;
        $this->article_pdf_version = null;
        $this->article_epub_ean = null;
        $this->article_epub_version = null;
        $this->article_azw_ean = null;
        $this->article_azw_version = null;
        $this->article_pages = null;
        $this->article_weight = null;
        $this->article_shaping = null;
        $this->article_format = null;
        $this->article_printing_process = null;
        $this->article_age_min = null;
        $this->article_age_max = null;
        $this->article_summary = null;
        $this->article_contents = null;
        $this->article_bonus = null;
        $this->article_catchline = null;
        $this->article_biography = null;
        $this->article_motsv = null;
        $this->article_copyright = null;
        $this->article_pubdate = null;
        $this->article_keywords = null;
        $this->article_links = null;
        $this->article_keywords_generated = null;
        $this->article_publisher_stock = null;
        $this->article_hits = null;
        $this->article_editing_user = null;
        $this->article_insert = null;
        $this->article_update = null;
        $this->article_created = null;
        $this->article_updated = null;
        $this->article_done = null;
        $this->article_to_check = null;
        $this->article_pushed_to_data = null;
        $this->article_deletion_by = null;
        $this->article_deletion_date = null;
        $this->article_deletion_reason = null;
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
            if ($this->collLinks) {
                foreach ($this->collLinks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collRoles) {
                foreach ($this->collRoles as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collLinks = null;
        $this->collRoles = null;
        $this->aPublisher = null;
        $this->aBookCollection = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ArticleTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildArticle The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[ArticleTableMap::COL_ARTICLE_UPDATED] = true;

        return $this;
    }

    // sluggable behavior

    /**
     * Wrap the setter for slug value
     *
     * @param   string
     * @return  $this|Article
     */
    public function setSlug($v)
    {
        return $this->setUrl($v);
    }

    /**
     * Wrap the getter for slug value
     *
     * @return  string
     */
    public function getSlug()
    {
        return $this->getUrl();
    }

    /**
     * Create a unique slug based on the object
     *
     * @return string The object slug
     */
    protected function createSlug()
    {
        $slug = $this->createRawSlug();
        $slug = $this->limitSlugSize($slug);
        $slug = $this->makeSlugUnique($slug);

        return $slug;
    }

    /**
     * Create the slug from the appropriate columns
     *
     * @return string
     */
    protected function createRawSlug()
    {
        return '' . $this->cleanupSlugPart($this->getAuthors()) . '/' . $this->cleanupSlugPart($this->getTitle()) . '';
    }

    /**
     * Cleanup a string to make a slug of it
     * Removes special characters, replaces blanks with a separator, and trim it
     *
     * @param     string $slug        the text to slugify
     * @param     string $replacement the separator used by slug
     * @return    string               the slugified text
     */
    protected static function cleanupSlugPart($slug, $replacement = '-')
    {
        // set locale explicitly
        $localeOrigin = setlocale(LC_CTYPE, 0);
        setlocale(LC_CTYPE, 'C.UTF-8');

        // transliterate
        if (function_exists('iconv')) {
            $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
        }

        // lowercase
        if (function_exists('mb_strtolower')) {
            $slug = mb_strtolower($slug);
        } else {
            $slug = strtolower($slug);
        }

        // remove accents resulting from OSX's iconv
        $slug = str_replace(array('\'', '`', '^'), '', $slug);

        // replace non letter or digits with separator
        $slug = preg_replace('/\W+/', $replacement, $slug);

        // trim
        $slug = trim($slug, $replacement);

        setlocale(LC_CTYPE, $localeOrigin);

        if (empty($slug)) {
            return 'n-a';
        }

        return $slug;
    }


    /**
     * Make sure the slug is short enough to accommodate the column size
     *
     * @param    string $slug            the slug to check
     *
     * @return string                        the truncated slug
     */
    protected static function limitSlugSize($slug, $incrementReservedSpace = 3)
    {
        // check length, as suffix could put it over maximum
        if (strlen($slug) > (256 - $incrementReservedSpace)) {
            $slug = substr($slug, 0, 256 - $incrementReservedSpace);
        }

        return $slug;
    }


    /**
     * Get the slug, ensuring its uniqueness
     *
     * @param    string $slug            the slug to check
     * @param    string $separator       the separator used by slug
     * @param    int    $alreadyExists   false for the first try, true for the second, and take the high count + 1
     * @return   string                   the unique slug
     */
    protected function makeSlugUnique($slug, $separator = '-', $alreadyExists = false)
    {
        if (!$alreadyExists) {
            $slug2 = $slug;
        } else {
            $slug2 = $slug . $separator;
        }

        $adapter = \Propel\Runtime\Propel::getServiceContainer()->getAdapter('default');
        $col = 'q.Url';
        $compare = $alreadyExists ? $adapter->compareRegex($col, '?') : sprintf('%s = ?', $col);

        $query = \Model\ArticleQuery::create('q')
            ->where($compare, $alreadyExists ? '^' . $slug2 . '[0-9]+$' : $slug2)
            ->prune($this)
        ;

        if (!$alreadyExists) {
            $count = $query->count();
            if ($count > 0) {
                return $this->makeSlugUnique($slug, $separator, true);
            }

            return $slug2;
        }

        $adapter = \Propel\Runtime\Propel::getServiceContainer()->getAdapter('default');
        // Already exists
        $object = $query
            ->addDescendingOrderByColumn($adapter->strLength('article_url'))
            ->addDescendingOrderByColumn('article_url')
        ->findOne();

        // First duplicate slug
        if (null == $object) {
            return $slug2 . '1';
        }

        $slugNum = substr($object->getUrl(), strlen($slug) + 1);
        if (0 == $slugNum[0]) {
            $slugNum[0] = 1;
        }

        return $slug2 . ($slugNum + 1);
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
