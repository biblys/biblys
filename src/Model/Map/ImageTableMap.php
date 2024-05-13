<?php

namespace Model\Map;

use Model\Image;
use Model\ImageQuery;
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
 * This class defines the structure of the 'images' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class ImageTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.ImageTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'images';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Image';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Image';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Image';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 12;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 12;

    /**
     * the column name for the image_id field
     */
    public const COL_IMAGE_ID = 'images.image_id';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'images.site_id';

    /**
     * the column name for the bookshop_id field
     */
    public const COL_BOOKSHOP_ID = 'images.bookshop_id';

    /**
     * the column name for the event_id field
     */
    public const COL_EVENT_ID = 'images.event_id';

    /**
     * the column name for the library_id field
     */
    public const COL_LIBRARY_ID = 'images.library_id';

    /**
     * the column name for the image_nature field
     */
    public const COL_IMAGE_NATURE = 'images.image_nature';

    /**
     * the column name for the image_legend field
     */
    public const COL_IMAGE_LEGEND = 'images.image_legend';

    /**
     * the column name for the image_type field
     */
    public const COL_IMAGE_TYPE = 'images.image_type';

    /**
     * the column name for the image_size field
     */
    public const COL_IMAGE_SIZE = 'images.image_size';

    /**
     * the column name for the image_inserted field
     */
    public const COL_IMAGE_INSERTED = 'images.image_inserted';

    /**
     * the column name for the image_uploaded field
     */
    public const COL_IMAGE_UPLOADED = 'images.image_uploaded';

    /**
     * the column name for the image_updated field
     */
    public const COL_IMAGE_UPDATED = 'images.image_updated';

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
        self::TYPE_PHPNAME       => ['Id', 'SiteId', 'BookshopId', 'EventId', 'LibraryId', 'Nature', 'Legend', 'Type', 'Size', 'Inserted', 'Uploaded', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'siteId', 'bookshopId', 'eventId', 'libraryId', 'nature', 'legend', 'type', 'size', 'inserted', 'uploaded', 'updatedAt', ],
        self::TYPE_COLNAME       => [ImageTableMap::COL_IMAGE_ID, ImageTableMap::COL_SITE_ID, ImageTableMap::COL_BOOKSHOP_ID, ImageTableMap::COL_EVENT_ID, ImageTableMap::COL_LIBRARY_ID, ImageTableMap::COL_IMAGE_NATURE, ImageTableMap::COL_IMAGE_LEGEND, ImageTableMap::COL_IMAGE_TYPE, ImageTableMap::COL_IMAGE_SIZE, ImageTableMap::COL_IMAGE_INSERTED, ImageTableMap::COL_IMAGE_UPLOADED, ImageTableMap::COL_IMAGE_UPDATED, ],
        self::TYPE_FIELDNAME     => ['image_id', 'site_id', 'bookshop_id', 'event_id', 'library_id', 'image_nature', 'image_legend', 'image_type', 'image_size', 'image_inserted', 'image_uploaded', 'image_updated', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'SiteId' => 1, 'BookshopId' => 2, 'EventId' => 3, 'LibraryId' => 4, 'Nature' => 5, 'Legend' => 6, 'Type' => 7, 'Size' => 8, 'Inserted' => 9, 'Uploaded' => 10, 'UpdatedAt' => 11, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'siteId' => 1, 'bookshopId' => 2, 'eventId' => 3, 'libraryId' => 4, 'nature' => 5, 'legend' => 6, 'type' => 7, 'size' => 8, 'inserted' => 9, 'uploaded' => 10, 'updatedAt' => 11, ],
        self::TYPE_COLNAME       => [ImageTableMap::COL_IMAGE_ID => 0, ImageTableMap::COL_SITE_ID => 1, ImageTableMap::COL_BOOKSHOP_ID => 2, ImageTableMap::COL_EVENT_ID => 3, ImageTableMap::COL_LIBRARY_ID => 4, ImageTableMap::COL_IMAGE_NATURE => 5, ImageTableMap::COL_IMAGE_LEGEND => 6, ImageTableMap::COL_IMAGE_TYPE => 7, ImageTableMap::COL_IMAGE_SIZE => 8, ImageTableMap::COL_IMAGE_INSERTED => 9, ImageTableMap::COL_IMAGE_UPLOADED => 10, ImageTableMap::COL_IMAGE_UPDATED => 11, ],
        self::TYPE_FIELDNAME     => ['image_id' => 0, 'site_id' => 1, 'bookshop_id' => 2, 'event_id' => 3, 'library_id' => 4, 'image_nature' => 5, 'image_legend' => 6, 'image_type' => 7, 'image_size' => 8, 'image_inserted' => 9, 'image_uploaded' => 10, 'image_updated' => 11, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'IMAGE_ID',
        'Image.Id' => 'IMAGE_ID',
        'id' => 'IMAGE_ID',
        'image.id' => 'IMAGE_ID',
        'ImageTableMap::COL_IMAGE_ID' => 'IMAGE_ID',
        'COL_IMAGE_ID' => 'IMAGE_ID',
        'image_id' => 'IMAGE_ID',
        'images.image_id' => 'IMAGE_ID',
        'SiteId' => 'SITE_ID',
        'Image.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'image.siteId' => 'SITE_ID',
        'ImageTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'images.site_id' => 'SITE_ID',
        'BookshopId' => 'BOOKSHOP_ID',
        'Image.BookshopId' => 'BOOKSHOP_ID',
        'bookshopId' => 'BOOKSHOP_ID',
        'image.bookshopId' => 'BOOKSHOP_ID',
        'ImageTableMap::COL_BOOKSHOP_ID' => 'BOOKSHOP_ID',
        'COL_BOOKSHOP_ID' => 'BOOKSHOP_ID',
        'bookshop_id' => 'BOOKSHOP_ID',
        'images.bookshop_id' => 'BOOKSHOP_ID',
        'EventId' => 'EVENT_ID',
        'Image.EventId' => 'EVENT_ID',
        'eventId' => 'EVENT_ID',
        'image.eventId' => 'EVENT_ID',
        'ImageTableMap::COL_EVENT_ID' => 'EVENT_ID',
        'COL_EVENT_ID' => 'EVENT_ID',
        'event_id' => 'EVENT_ID',
        'images.event_id' => 'EVENT_ID',
        'LibraryId' => 'LIBRARY_ID',
        'Image.LibraryId' => 'LIBRARY_ID',
        'libraryId' => 'LIBRARY_ID',
        'image.libraryId' => 'LIBRARY_ID',
        'ImageTableMap::COL_LIBRARY_ID' => 'LIBRARY_ID',
        'COL_LIBRARY_ID' => 'LIBRARY_ID',
        'library_id' => 'LIBRARY_ID',
        'images.library_id' => 'LIBRARY_ID',
        'Nature' => 'IMAGE_NATURE',
        'Image.Nature' => 'IMAGE_NATURE',
        'nature' => 'IMAGE_NATURE',
        'image.nature' => 'IMAGE_NATURE',
        'ImageTableMap::COL_IMAGE_NATURE' => 'IMAGE_NATURE',
        'COL_IMAGE_NATURE' => 'IMAGE_NATURE',
        'image_nature' => 'IMAGE_NATURE',
        'images.image_nature' => 'IMAGE_NATURE',
        'Legend' => 'IMAGE_LEGEND',
        'Image.Legend' => 'IMAGE_LEGEND',
        'legend' => 'IMAGE_LEGEND',
        'image.legend' => 'IMAGE_LEGEND',
        'ImageTableMap::COL_IMAGE_LEGEND' => 'IMAGE_LEGEND',
        'COL_IMAGE_LEGEND' => 'IMAGE_LEGEND',
        'image_legend' => 'IMAGE_LEGEND',
        'images.image_legend' => 'IMAGE_LEGEND',
        'Type' => 'IMAGE_TYPE',
        'Image.Type' => 'IMAGE_TYPE',
        'type' => 'IMAGE_TYPE',
        'image.type' => 'IMAGE_TYPE',
        'ImageTableMap::COL_IMAGE_TYPE' => 'IMAGE_TYPE',
        'COL_IMAGE_TYPE' => 'IMAGE_TYPE',
        'image_type' => 'IMAGE_TYPE',
        'images.image_type' => 'IMAGE_TYPE',
        'Size' => 'IMAGE_SIZE',
        'Image.Size' => 'IMAGE_SIZE',
        'size' => 'IMAGE_SIZE',
        'image.size' => 'IMAGE_SIZE',
        'ImageTableMap::COL_IMAGE_SIZE' => 'IMAGE_SIZE',
        'COL_IMAGE_SIZE' => 'IMAGE_SIZE',
        'image_size' => 'IMAGE_SIZE',
        'images.image_size' => 'IMAGE_SIZE',
        'Inserted' => 'IMAGE_INSERTED',
        'Image.Inserted' => 'IMAGE_INSERTED',
        'inserted' => 'IMAGE_INSERTED',
        'image.inserted' => 'IMAGE_INSERTED',
        'ImageTableMap::COL_IMAGE_INSERTED' => 'IMAGE_INSERTED',
        'COL_IMAGE_INSERTED' => 'IMAGE_INSERTED',
        'image_inserted' => 'IMAGE_INSERTED',
        'images.image_inserted' => 'IMAGE_INSERTED',
        'Uploaded' => 'IMAGE_UPLOADED',
        'Image.Uploaded' => 'IMAGE_UPLOADED',
        'uploaded' => 'IMAGE_UPLOADED',
        'image.uploaded' => 'IMAGE_UPLOADED',
        'ImageTableMap::COL_IMAGE_UPLOADED' => 'IMAGE_UPLOADED',
        'COL_IMAGE_UPLOADED' => 'IMAGE_UPLOADED',
        'image_uploaded' => 'IMAGE_UPLOADED',
        'images.image_uploaded' => 'IMAGE_UPLOADED',
        'UpdatedAt' => 'IMAGE_UPDATED',
        'Image.UpdatedAt' => 'IMAGE_UPDATED',
        'updatedAt' => 'IMAGE_UPDATED',
        'image.updatedAt' => 'IMAGE_UPDATED',
        'ImageTableMap::COL_IMAGE_UPDATED' => 'IMAGE_UPDATED',
        'COL_IMAGE_UPDATED' => 'IMAGE_UPDATED',
        'image_updated' => 'IMAGE_UPDATED',
        'images.image_updated' => 'IMAGE_UPDATED',
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
        $this->setName('images');
        $this->setPhpName('Image');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Image');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('image_id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('site_id', 'SiteId', 'INTEGER', false, null, null);
        $this->addColumn('bookshop_id', 'BookshopId', 'INTEGER', false, null, null);
        $this->addColumn('event_id', 'EventId', 'INTEGER', false, null, null);
        $this->addColumn('library_id', 'LibraryId', 'INTEGER', false, null, null);
        $this->addColumn('image_nature', 'Nature', 'VARCHAR', false, 16, null);
        $this->addColumn('image_legend', 'Legend', 'VARCHAR', false, 32, null);
        $this->addColumn('image_type', 'Type', 'VARCHAR', false, 32, null);
        $this->addColumn('image_size', 'Size', 'BIGINT', false, null, 0);
        $this->addColumn('image_inserted', 'Inserted', 'TIMESTAMP', false, null, null);
        $this->addColumn('image_uploaded', 'Uploaded', 'TIMESTAMP', false, null, null);
        $this->addColumn('image_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
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
            'timestampable' => ['create_column' => 'image_inserted', 'update_column' => 'image_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? ImageTableMap::CLASS_DEFAULT : ImageTableMap::OM_CLASS;
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
     * @return array (Image object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = ImageTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = ImageTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + ImageTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ImageTableMap::OM_CLASS;
            /** @var Image $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            ImageTableMap::addInstanceToPool($obj, $key);
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
            $key = ImageTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = ImageTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Image $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ImageTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(ImageTableMap::COL_IMAGE_ID);
            $criteria->addSelectColumn(ImageTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(ImageTableMap::COL_BOOKSHOP_ID);
            $criteria->addSelectColumn(ImageTableMap::COL_EVENT_ID);
            $criteria->addSelectColumn(ImageTableMap::COL_LIBRARY_ID);
            $criteria->addSelectColumn(ImageTableMap::COL_IMAGE_NATURE);
            $criteria->addSelectColumn(ImageTableMap::COL_IMAGE_LEGEND);
            $criteria->addSelectColumn(ImageTableMap::COL_IMAGE_TYPE);
            $criteria->addSelectColumn(ImageTableMap::COL_IMAGE_SIZE);
            $criteria->addSelectColumn(ImageTableMap::COL_IMAGE_INSERTED);
            $criteria->addSelectColumn(ImageTableMap::COL_IMAGE_UPLOADED);
            $criteria->addSelectColumn(ImageTableMap::COL_IMAGE_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.image_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.bookshop_id');
            $criteria->addSelectColumn($alias . '.event_id');
            $criteria->addSelectColumn($alias . '.library_id');
            $criteria->addSelectColumn($alias . '.image_nature');
            $criteria->addSelectColumn($alias . '.image_legend');
            $criteria->addSelectColumn($alias . '.image_type');
            $criteria->addSelectColumn($alias . '.image_size');
            $criteria->addSelectColumn($alias . '.image_inserted');
            $criteria->addSelectColumn($alias . '.image_uploaded');
            $criteria->addSelectColumn($alias . '.image_updated');
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
            $criteria->removeSelectColumn(ImageTableMap::COL_IMAGE_ID);
            $criteria->removeSelectColumn(ImageTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(ImageTableMap::COL_BOOKSHOP_ID);
            $criteria->removeSelectColumn(ImageTableMap::COL_EVENT_ID);
            $criteria->removeSelectColumn(ImageTableMap::COL_LIBRARY_ID);
            $criteria->removeSelectColumn(ImageTableMap::COL_IMAGE_NATURE);
            $criteria->removeSelectColumn(ImageTableMap::COL_IMAGE_LEGEND);
            $criteria->removeSelectColumn(ImageTableMap::COL_IMAGE_TYPE);
            $criteria->removeSelectColumn(ImageTableMap::COL_IMAGE_SIZE);
            $criteria->removeSelectColumn(ImageTableMap::COL_IMAGE_INSERTED);
            $criteria->removeSelectColumn(ImageTableMap::COL_IMAGE_UPLOADED);
            $criteria->removeSelectColumn(ImageTableMap::COL_IMAGE_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.image_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.bookshop_id');
            $criteria->removeSelectColumn($alias . '.event_id');
            $criteria->removeSelectColumn($alias . '.library_id');
            $criteria->removeSelectColumn($alias . '.image_nature');
            $criteria->removeSelectColumn($alias . '.image_legend');
            $criteria->removeSelectColumn($alias . '.image_type');
            $criteria->removeSelectColumn($alias . '.image_size');
            $criteria->removeSelectColumn($alias . '.image_inserted');
            $criteria->removeSelectColumn($alias . '.image_uploaded');
            $criteria->removeSelectColumn($alias . '.image_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(ImageTableMap::DATABASE_NAME)->getTable(ImageTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Image or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Image object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(ImageTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Image) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ImageTableMap::DATABASE_NAME);
            $criteria->add(ImageTableMap::COL_IMAGE_ID, (array) $values, Criteria::IN);
        }

        $query = ImageQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            ImageTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                ImageTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the images table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return ImageQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Image or Criteria object.
     *
     * @param mixed $criteria Criteria or Image object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ImageTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Image object
        }

        if ($criteria->containsKey(ImageTableMap::COL_IMAGE_ID) && $criteria->keyContainsValue(ImageTableMap::COL_IMAGE_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.ImageTableMap::COL_IMAGE_ID.')');
        }


        // Set the correct dbName
        $query = ImageQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
