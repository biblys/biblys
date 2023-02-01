<?php

namespace Model\Map;

use Model\Media;
use Model\MediaQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'medias' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class MediaTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.MediaTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'medias';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Media';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Media';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Media';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 14;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 14;

    /**
     * the column name for the media_id field
     */
    public const COL_MEDIA_ID = 'medias.media_id';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'medias.site_id';

    /**
     * the column name for the category_id field
     */
    public const COL_CATEGORY_ID = 'medias.category_id';

    /**
     * the column name for the media_dir field
     */
    public const COL_MEDIA_DIR = 'medias.media_dir';

    /**
     * the column name for the media_file field
     */
    public const COL_MEDIA_FILE = 'medias.media_file';

    /**
     * the column name for the media_ext field
     */
    public const COL_MEDIA_EXT = 'medias.media_ext';

    /**
     * the column name for the media_title field
     */
    public const COL_MEDIA_TITLE = 'medias.media_title';

    /**
     * the column name for the media_desc field
     */
    public const COL_MEDIA_DESC = 'medias.media_desc';

    /**
     * the column name for the media_link field
     */
    public const COL_MEDIA_LINK = 'medias.media_link';

    /**
     * the column name for the media_headline field
     */
    public const COL_MEDIA_HEADLINE = 'medias.media_headline';

    /**
     * the column name for the media_insert field
     */
    public const COL_MEDIA_INSERT = 'medias.media_insert';

    /**
     * the column name for the media_update field
     */
    public const COL_MEDIA_UPDATE = 'medias.media_update';

    /**
     * the column name for the media_created field
     */
    public const COL_MEDIA_CREATED = 'medias.media_created';

    /**
     * the column name for the media_updated field
     */
    public const COL_MEDIA_UPDATED = 'medias.media_updated';

    /**
     * The default string format for model objects of the related table
     */
    public const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     *
     * @var array<string, mixed>
     */
    protected static $fieldNames = [
        self::TYPE_PHPNAME       => ['Id', 'SiteId', 'CategoryId', 'Dir', 'File', 'Ext', 'Title', 'Desc', 'Link', 'Headline', 'Insert', 'Update', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'siteId', 'categoryId', 'dir', 'file', 'ext', 'title', 'desc', 'link', 'headline', 'insert', 'update', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [MediaTableMap::COL_MEDIA_ID, MediaTableMap::COL_SITE_ID, MediaTableMap::COL_CATEGORY_ID, MediaTableMap::COL_MEDIA_DIR, MediaTableMap::COL_MEDIA_FILE, MediaTableMap::COL_MEDIA_EXT, MediaTableMap::COL_MEDIA_TITLE, MediaTableMap::COL_MEDIA_DESC, MediaTableMap::COL_MEDIA_LINK, MediaTableMap::COL_MEDIA_HEADLINE, MediaTableMap::COL_MEDIA_INSERT, MediaTableMap::COL_MEDIA_UPDATE, MediaTableMap::COL_MEDIA_CREATED, MediaTableMap::COL_MEDIA_UPDATED, ],
        self::TYPE_FIELDNAME     => ['media_id', 'site_id', 'category_id', 'media_dir', 'media_file', 'media_ext', 'media_title', 'media_desc', 'media_link', 'media_headline', 'media_insert', 'media_update', 'media_created', 'media_updated', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, ]
    ];

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     *
     * @var array<string, mixed>
     */
    protected static $fieldKeys = [
        self::TYPE_PHPNAME       => ['Id' => 0, 'SiteId' => 1, 'CategoryId' => 2, 'Dir' => 3, 'File' => 4, 'Ext' => 5, 'Title' => 6, 'Desc' => 7, 'Link' => 8, 'Headline' => 9, 'Insert' => 10, 'Update' => 11, 'CreatedAt' => 12, 'UpdatedAt' => 13, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'siteId' => 1, 'categoryId' => 2, 'dir' => 3, 'file' => 4, 'ext' => 5, 'title' => 6, 'desc' => 7, 'link' => 8, 'headline' => 9, 'insert' => 10, 'update' => 11, 'createdAt' => 12, 'updatedAt' => 13, ],
        self::TYPE_COLNAME       => [MediaTableMap::COL_MEDIA_ID => 0, MediaTableMap::COL_SITE_ID => 1, MediaTableMap::COL_CATEGORY_ID => 2, MediaTableMap::COL_MEDIA_DIR => 3, MediaTableMap::COL_MEDIA_FILE => 4, MediaTableMap::COL_MEDIA_EXT => 5, MediaTableMap::COL_MEDIA_TITLE => 6, MediaTableMap::COL_MEDIA_DESC => 7, MediaTableMap::COL_MEDIA_LINK => 8, MediaTableMap::COL_MEDIA_HEADLINE => 9, MediaTableMap::COL_MEDIA_INSERT => 10, MediaTableMap::COL_MEDIA_UPDATE => 11, MediaTableMap::COL_MEDIA_CREATED => 12, MediaTableMap::COL_MEDIA_UPDATED => 13, ],
        self::TYPE_FIELDNAME     => ['media_id' => 0, 'site_id' => 1, 'category_id' => 2, 'media_dir' => 3, 'media_file' => 4, 'media_ext' => 5, 'media_title' => 6, 'media_desc' => 7, 'media_link' => 8, 'media_headline' => 9, 'media_insert' => 10, 'media_update' => 11, 'media_created' => 12, 'media_updated' => 13, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'MEDIA_ID',
        'Media.Id' => 'MEDIA_ID',
        'id' => 'MEDIA_ID',
        'media.id' => 'MEDIA_ID',
        'MediaTableMap::COL_MEDIA_ID' => 'MEDIA_ID',
        'COL_MEDIA_ID' => 'MEDIA_ID',
        'media_id' => 'MEDIA_ID',
        'medias.media_id' => 'MEDIA_ID',
        'SiteId' => 'SITE_ID',
        'Media.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'media.siteId' => 'SITE_ID',
        'MediaTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'medias.site_id' => 'SITE_ID',
        'CategoryId' => 'CATEGORY_ID',
        'Media.CategoryId' => 'CATEGORY_ID',
        'categoryId' => 'CATEGORY_ID',
        'media.categoryId' => 'CATEGORY_ID',
        'MediaTableMap::COL_CATEGORY_ID' => 'CATEGORY_ID',
        'COL_CATEGORY_ID' => 'CATEGORY_ID',
        'category_id' => 'CATEGORY_ID',
        'medias.category_id' => 'CATEGORY_ID',
        'Dir' => 'MEDIA_DIR',
        'Media.Dir' => 'MEDIA_DIR',
        'dir' => 'MEDIA_DIR',
        'media.dir' => 'MEDIA_DIR',
        'MediaTableMap::COL_MEDIA_DIR' => 'MEDIA_DIR',
        'COL_MEDIA_DIR' => 'MEDIA_DIR',
        'media_dir' => 'MEDIA_DIR',
        'medias.media_dir' => 'MEDIA_DIR',
        'File' => 'MEDIA_FILE',
        'Media.File' => 'MEDIA_FILE',
        'file' => 'MEDIA_FILE',
        'media.file' => 'MEDIA_FILE',
        'MediaTableMap::COL_MEDIA_FILE' => 'MEDIA_FILE',
        'COL_MEDIA_FILE' => 'MEDIA_FILE',
        'media_file' => 'MEDIA_FILE',
        'medias.media_file' => 'MEDIA_FILE',
        'Ext' => 'MEDIA_EXT',
        'Media.Ext' => 'MEDIA_EXT',
        'ext' => 'MEDIA_EXT',
        'media.ext' => 'MEDIA_EXT',
        'MediaTableMap::COL_MEDIA_EXT' => 'MEDIA_EXT',
        'COL_MEDIA_EXT' => 'MEDIA_EXT',
        'media_ext' => 'MEDIA_EXT',
        'medias.media_ext' => 'MEDIA_EXT',
        'Title' => 'MEDIA_TITLE',
        'Media.Title' => 'MEDIA_TITLE',
        'title' => 'MEDIA_TITLE',
        'media.title' => 'MEDIA_TITLE',
        'MediaTableMap::COL_MEDIA_TITLE' => 'MEDIA_TITLE',
        'COL_MEDIA_TITLE' => 'MEDIA_TITLE',
        'media_title' => 'MEDIA_TITLE',
        'medias.media_title' => 'MEDIA_TITLE',
        'Desc' => 'MEDIA_DESC',
        'Media.Desc' => 'MEDIA_DESC',
        'desc' => 'MEDIA_DESC',
        'media.desc' => 'MEDIA_DESC',
        'MediaTableMap::COL_MEDIA_DESC' => 'MEDIA_DESC',
        'COL_MEDIA_DESC' => 'MEDIA_DESC',
        'media_desc' => 'MEDIA_DESC',
        'medias.media_desc' => 'MEDIA_DESC',
        'Link' => 'MEDIA_LINK',
        'Media.Link' => 'MEDIA_LINK',
        'link' => 'MEDIA_LINK',
        'media.link' => 'MEDIA_LINK',
        'MediaTableMap::COL_MEDIA_LINK' => 'MEDIA_LINK',
        'COL_MEDIA_LINK' => 'MEDIA_LINK',
        'media_link' => 'MEDIA_LINK',
        'medias.media_link' => 'MEDIA_LINK',
        'Headline' => 'MEDIA_HEADLINE',
        'Media.Headline' => 'MEDIA_HEADLINE',
        'headline' => 'MEDIA_HEADLINE',
        'media.headline' => 'MEDIA_HEADLINE',
        'MediaTableMap::COL_MEDIA_HEADLINE' => 'MEDIA_HEADLINE',
        'COL_MEDIA_HEADLINE' => 'MEDIA_HEADLINE',
        'media_headline' => 'MEDIA_HEADLINE',
        'medias.media_headline' => 'MEDIA_HEADLINE',
        'Insert' => 'MEDIA_INSERT',
        'Media.Insert' => 'MEDIA_INSERT',
        'insert' => 'MEDIA_INSERT',
        'media.insert' => 'MEDIA_INSERT',
        'MediaTableMap::COL_MEDIA_INSERT' => 'MEDIA_INSERT',
        'COL_MEDIA_INSERT' => 'MEDIA_INSERT',
        'media_insert' => 'MEDIA_INSERT',
        'medias.media_insert' => 'MEDIA_INSERT',
        'Update' => 'MEDIA_UPDATE',
        'Media.Update' => 'MEDIA_UPDATE',
        'update' => 'MEDIA_UPDATE',
        'media.update' => 'MEDIA_UPDATE',
        'MediaTableMap::COL_MEDIA_UPDATE' => 'MEDIA_UPDATE',
        'COL_MEDIA_UPDATE' => 'MEDIA_UPDATE',
        'media_update' => 'MEDIA_UPDATE',
        'medias.media_update' => 'MEDIA_UPDATE',
        'CreatedAt' => 'MEDIA_CREATED',
        'Media.CreatedAt' => 'MEDIA_CREATED',
        'createdAt' => 'MEDIA_CREATED',
        'media.createdAt' => 'MEDIA_CREATED',
        'MediaTableMap::COL_MEDIA_CREATED' => 'MEDIA_CREATED',
        'COL_MEDIA_CREATED' => 'MEDIA_CREATED',
        'media_created' => 'MEDIA_CREATED',
        'medias.media_created' => 'MEDIA_CREATED',
        'UpdatedAt' => 'MEDIA_UPDATED',
        'Media.UpdatedAt' => 'MEDIA_UPDATED',
        'updatedAt' => 'MEDIA_UPDATED',
        'media.updatedAt' => 'MEDIA_UPDATED',
        'MediaTableMap::COL_MEDIA_UPDATED' => 'MEDIA_UPDATED',
        'COL_MEDIA_UPDATED' => 'MEDIA_UPDATED',
        'media_updated' => 'MEDIA_UPDATED',
        'medias.media_updated' => 'MEDIA_UPDATED',
    ];

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function initialize(): void
    {
        // attributes
        $this->setName('medias');
        $this->setPhpName('Media');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Media');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('media_id', 'Id', 'INTEGER', true, 10, null);
        $this->addColumn('site_id', 'SiteId', 'INTEGER', false, 10, null);
        $this->addColumn('category_id', 'CategoryId', 'INTEGER', false, 10, null);
        $this->addColumn('media_dir', 'Dir', 'LONGVARCHAR', false, null, null);
        $this->addColumn('media_file', 'File', 'LONGVARCHAR', false, null, null);
        $this->addColumn('media_ext', 'Ext', 'LONGVARCHAR', false, null, null);
        $this->addColumn('media_title', 'Title', 'LONGVARCHAR', false, null, null);
        $this->addColumn('media_desc', 'Desc', 'LONGVARCHAR', false, null, null);
        $this->addColumn('media_link', 'Link', 'LONGVARCHAR', false, null, null);
        $this->addColumn('media_headline', 'Headline', 'LONGVARCHAR', false, null, null);
        $this->addColumn('media_insert', 'Insert', 'TIMESTAMP', false, null, null);
        $this->addColumn('media_update', 'Update', 'TIMESTAMP', false, null, null);
        $this->addColumn('media_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('media_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
    }

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array<string, array> Associative array (name => parameters) of behaviors
     */
    public function getBehaviors(): array
    {
        return [
            'timestampable' => ['create_column' => 'media_created', 'update_column' => 'media_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
        ];
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array $row Resultset row.
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string|null The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): ?string
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array $row Resultset row.
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param bool $withPrefix Whether to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass(bool $withPrefix = true): string
    {
        return $withPrefix ? MediaTableMap::CLASS_DEFAULT : MediaTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array $row Row returned by DataFetcher->fetch().
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array (Media object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = MediaTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = MediaTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + MediaTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = MediaTableMap::OM_CLASS;
            /** @var Media $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            MediaTableMap::addInstanceToPool($obj, $key);
        }

        return [$obj, $col];
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array<object>
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher): array
    {
        $results = [];

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = MediaTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = MediaTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Media $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                MediaTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria Object containing the columns to add.
     * @param string|null $alias Optional table alias
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return void
     */
    public static function addSelectColumns(Criteria $criteria, ?string $alias = null): void
    {
        if (null === $alias) {
            $criteria->addSelectColumn(MediaTableMap::COL_MEDIA_ID);
            $criteria->addSelectColumn(MediaTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(MediaTableMap::COL_CATEGORY_ID);
            $criteria->addSelectColumn(MediaTableMap::COL_MEDIA_DIR);
            $criteria->addSelectColumn(MediaTableMap::COL_MEDIA_FILE);
            $criteria->addSelectColumn(MediaTableMap::COL_MEDIA_EXT);
            $criteria->addSelectColumn(MediaTableMap::COL_MEDIA_TITLE);
            $criteria->addSelectColumn(MediaTableMap::COL_MEDIA_DESC);
            $criteria->addSelectColumn(MediaTableMap::COL_MEDIA_LINK);
            $criteria->addSelectColumn(MediaTableMap::COL_MEDIA_HEADLINE);
            $criteria->addSelectColumn(MediaTableMap::COL_MEDIA_INSERT);
            $criteria->addSelectColumn(MediaTableMap::COL_MEDIA_UPDATE);
            $criteria->addSelectColumn(MediaTableMap::COL_MEDIA_CREATED);
            $criteria->addSelectColumn(MediaTableMap::COL_MEDIA_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.media_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.category_id');
            $criteria->addSelectColumn($alias . '.media_dir');
            $criteria->addSelectColumn($alias . '.media_file');
            $criteria->addSelectColumn($alias . '.media_ext');
            $criteria->addSelectColumn($alias . '.media_title');
            $criteria->addSelectColumn($alias . '.media_desc');
            $criteria->addSelectColumn($alias . '.media_link');
            $criteria->addSelectColumn($alias . '.media_headline');
            $criteria->addSelectColumn($alias . '.media_insert');
            $criteria->addSelectColumn($alias . '.media_update');
            $criteria->addSelectColumn($alias . '.media_created');
            $criteria->addSelectColumn($alias . '.media_updated');
        }
    }

    /**
     * Remove all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be removed as they are only loaded on demand.
     *
     * @param Criteria $criteria Object containing the columns to remove.
     * @param string|null $alias Optional table alias
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return void
     */
    public static function removeSelectColumns(Criteria $criteria, ?string $alias = null): void
    {
        if (null === $alias) {
            $criteria->removeSelectColumn(MediaTableMap::COL_MEDIA_ID);
            $criteria->removeSelectColumn(MediaTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(MediaTableMap::COL_CATEGORY_ID);
            $criteria->removeSelectColumn(MediaTableMap::COL_MEDIA_DIR);
            $criteria->removeSelectColumn(MediaTableMap::COL_MEDIA_FILE);
            $criteria->removeSelectColumn(MediaTableMap::COL_MEDIA_EXT);
            $criteria->removeSelectColumn(MediaTableMap::COL_MEDIA_TITLE);
            $criteria->removeSelectColumn(MediaTableMap::COL_MEDIA_DESC);
            $criteria->removeSelectColumn(MediaTableMap::COL_MEDIA_LINK);
            $criteria->removeSelectColumn(MediaTableMap::COL_MEDIA_HEADLINE);
            $criteria->removeSelectColumn(MediaTableMap::COL_MEDIA_INSERT);
            $criteria->removeSelectColumn(MediaTableMap::COL_MEDIA_UPDATE);
            $criteria->removeSelectColumn(MediaTableMap::COL_MEDIA_CREATED);
            $criteria->removeSelectColumn(MediaTableMap::COL_MEDIA_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.media_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.category_id');
            $criteria->removeSelectColumn($alias . '.media_dir');
            $criteria->removeSelectColumn($alias . '.media_file');
            $criteria->removeSelectColumn($alias . '.media_ext');
            $criteria->removeSelectColumn($alias . '.media_title');
            $criteria->removeSelectColumn($alias . '.media_desc');
            $criteria->removeSelectColumn($alias . '.media_link');
            $criteria->removeSelectColumn($alias . '.media_headline');
            $criteria->removeSelectColumn($alias . '.media_insert');
            $criteria->removeSelectColumn($alias . '.media_update');
            $criteria->removeSelectColumn($alias . '.media_created');
            $criteria->removeSelectColumn($alias . '.media_updated');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap(): TableMap
    {
        return Propel::getServiceContainer()->getDatabaseMap(MediaTableMap::DATABASE_NAME)->getTable(MediaTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Media or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Media object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ?ConnectionInterface $con = null): int
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MediaTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Media) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(MediaTableMap::DATABASE_NAME);
            $criteria->add(MediaTableMap::COL_MEDIA_ID, (array) $values, Criteria::IN);
        }

        $query = MediaQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            MediaTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                MediaTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the medias table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return MediaQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Media or Criteria object.
     *
     * @param mixed $criteria Criteria or Media object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MediaTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Media object
        }

        if ($criteria->containsKey(MediaTableMap::COL_MEDIA_ID) && $criteria->keyContainsValue(MediaTableMap::COL_MEDIA_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.MediaTableMap::COL_MEDIA_ID.')');
        }


        // Set the correct dbName
        $query = MediaQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
