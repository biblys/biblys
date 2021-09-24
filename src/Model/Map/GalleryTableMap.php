<?php

namespace Model\Map;

use Model\Gallery;
use Model\GalleryQuery;
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
 * This class defines the structure of the 'galleries' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class GalleryTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Model.Map.GalleryTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'galleries';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\Gallery';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.Gallery';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 9;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 9;

    /**
     * the column name for the gallery_id field
     */
    const COL_GALLERY_ID = 'galleries.gallery_id';

    /**
     * the column name for the site_id field
     */
    const COL_SITE_ID = 'galleries.site_id';

    /**
     * the column name for the gallery_title field
     */
    const COL_GALLERY_TITLE = 'galleries.gallery_title';

    /**
     * the column name for the media_dir field
     */
    const COL_MEDIA_DIR = 'galleries.media_dir';

    /**
     * the column name for the gallery_insert field
     */
    const COL_GALLERY_INSERT = 'galleries.gallery_insert';

    /**
     * the column name for the gallery_update field
     */
    const COL_GALLERY_UPDATE = 'galleries.gallery_update';

    /**
     * the column name for the gallery_created field
     */
    const COL_GALLERY_CREATED = 'galleries.gallery_created';

    /**
     * the column name for the gallery_updated field
     */
    const COL_GALLERY_UPDATED = 'galleries.gallery_updated';

    /**
     * the column name for the gallery_deleted field
     */
    const COL_GALLERY_DELETED = 'galleries.gallery_deleted';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'SiteId', 'Title', 'MediaDir', 'Insert', 'Update', 'Created', 'Updated', 'Deleted', ),
        self::TYPE_CAMELNAME     => array('id', 'siteId', 'title', 'mediaDir', 'insert', 'update', 'created', 'updated', 'deleted', ),
        self::TYPE_COLNAME       => array(GalleryTableMap::COL_GALLERY_ID, GalleryTableMap::COL_SITE_ID, GalleryTableMap::COL_GALLERY_TITLE, GalleryTableMap::COL_MEDIA_DIR, GalleryTableMap::COL_GALLERY_INSERT, GalleryTableMap::COL_GALLERY_UPDATE, GalleryTableMap::COL_GALLERY_CREATED, GalleryTableMap::COL_GALLERY_UPDATED, GalleryTableMap::COL_GALLERY_DELETED, ),
        self::TYPE_FIELDNAME     => array('gallery_id', 'site_id', 'gallery_title', 'media_dir', 'gallery_insert', 'gallery_update', 'gallery_created', 'gallery_updated', 'gallery_deleted', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'SiteId' => 1, 'Title' => 2, 'MediaDir' => 3, 'Insert' => 4, 'Update' => 5, 'Created' => 6, 'Updated' => 7, 'Deleted' => 8, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'siteId' => 1, 'title' => 2, 'mediaDir' => 3, 'insert' => 4, 'update' => 5, 'created' => 6, 'updated' => 7, 'deleted' => 8, ),
        self::TYPE_COLNAME       => array(GalleryTableMap::COL_GALLERY_ID => 0, GalleryTableMap::COL_SITE_ID => 1, GalleryTableMap::COL_GALLERY_TITLE => 2, GalleryTableMap::COL_MEDIA_DIR => 3, GalleryTableMap::COL_GALLERY_INSERT => 4, GalleryTableMap::COL_GALLERY_UPDATE => 5, GalleryTableMap::COL_GALLERY_CREATED => 6, GalleryTableMap::COL_GALLERY_UPDATED => 7, GalleryTableMap::COL_GALLERY_DELETED => 8, ),
        self::TYPE_FIELDNAME     => array('gallery_id' => 0, 'site_id' => 1, 'gallery_title' => 2, 'media_dir' => 3, 'gallery_insert' => 4, 'gallery_update' => 5, 'gallery_created' => 6, 'gallery_updated' => 7, 'gallery_deleted' => 8, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var string[]
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'GALLERY_ID',
        'Gallery.Id' => 'GALLERY_ID',
        'id' => 'GALLERY_ID',
        'gallery.id' => 'GALLERY_ID',
        'GalleryTableMap::COL_GALLERY_ID' => 'GALLERY_ID',
        'COL_GALLERY_ID' => 'GALLERY_ID',
        'gallery_id' => 'GALLERY_ID',
        'galleries.gallery_id' => 'GALLERY_ID',
        'SiteId' => 'SITE_ID',
        'Gallery.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'gallery.siteId' => 'SITE_ID',
        'GalleryTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'galleries.site_id' => 'SITE_ID',
        'Title' => 'GALLERY_TITLE',
        'Gallery.Title' => 'GALLERY_TITLE',
        'title' => 'GALLERY_TITLE',
        'gallery.title' => 'GALLERY_TITLE',
        'GalleryTableMap::COL_GALLERY_TITLE' => 'GALLERY_TITLE',
        'COL_GALLERY_TITLE' => 'GALLERY_TITLE',
        'gallery_title' => 'GALLERY_TITLE',
        'galleries.gallery_title' => 'GALLERY_TITLE',
        'MediaDir' => 'MEDIA_DIR',
        'Gallery.MediaDir' => 'MEDIA_DIR',
        'mediaDir' => 'MEDIA_DIR',
        'gallery.mediaDir' => 'MEDIA_DIR',
        'GalleryTableMap::COL_MEDIA_DIR' => 'MEDIA_DIR',
        'COL_MEDIA_DIR' => 'MEDIA_DIR',
        'media_dir' => 'MEDIA_DIR',
        'galleries.media_dir' => 'MEDIA_DIR',
        'Insert' => 'GALLERY_INSERT',
        'Gallery.Insert' => 'GALLERY_INSERT',
        'insert' => 'GALLERY_INSERT',
        'gallery.insert' => 'GALLERY_INSERT',
        'GalleryTableMap::COL_GALLERY_INSERT' => 'GALLERY_INSERT',
        'COL_GALLERY_INSERT' => 'GALLERY_INSERT',
        'gallery_insert' => 'GALLERY_INSERT',
        'galleries.gallery_insert' => 'GALLERY_INSERT',
        'Update' => 'GALLERY_UPDATE',
        'Gallery.Update' => 'GALLERY_UPDATE',
        'update' => 'GALLERY_UPDATE',
        'gallery.update' => 'GALLERY_UPDATE',
        'GalleryTableMap::COL_GALLERY_UPDATE' => 'GALLERY_UPDATE',
        'COL_GALLERY_UPDATE' => 'GALLERY_UPDATE',
        'gallery_update' => 'GALLERY_UPDATE',
        'galleries.gallery_update' => 'GALLERY_UPDATE',
        'Created' => 'GALLERY_CREATED',
        'Gallery.Created' => 'GALLERY_CREATED',
        'created' => 'GALLERY_CREATED',
        'gallery.created' => 'GALLERY_CREATED',
        'GalleryTableMap::COL_GALLERY_CREATED' => 'GALLERY_CREATED',
        'COL_GALLERY_CREATED' => 'GALLERY_CREATED',
        'gallery_created' => 'GALLERY_CREATED',
        'galleries.gallery_created' => 'GALLERY_CREATED',
        'Updated' => 'GALLERY_UPDATED',
        'Gallery.Updated' => 'GALLERY_UPDATED',
        'updated' => 'GALLERY_UPDATED',
        'gallery.updated' => 'GALLERY_UPDATED',
        'GalleryTableMap::COL_GALLERY_UPDATED' => 'GALLERY_UPDATED',
        'COL_GALLERY_UPDATED' => 'GALLERY_UPDATED',
        'gallery_updated' => 'GALLERY_UPDATED',
        'galleries.gallery_updated' => 'GALLERY_UPDATED',
        'Deleted' => 'GALLERY_DELETED',
        'Gallery.Deleted' => 'GALLERY_DELETED',
        'deleted' => 'GALLERY_DELETED',
        'gallery.deleted' => 'GALLERY_DELETED',
        'GalleryTableMap::COL_GALLERY_DELETED' => 'GALLERY_DELETED',
        'COL_GALLERY_DELETED' => 'GALLERY_DELETED',
        'gallery_deleted' => 'GALLERY_DELETED',
        'galleries.gallery_deleted' => 'GALLERY_DELETED',
    ];

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('galleries');
        $this->setPhpName('Gallery');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Gallery');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('gallery_id', 'Id', 'INTEGER', true, 10, null);
        $this->addColumn('site_id', 'SiteId', 'INTEGER', false, 10, null);
        $this->addColumn('gallery_title', 'Title', 'LONGVARCHAR', false, null, null);
        $this->addColumn('media_dir', 'MediaDir', 'LONGVARCHAR', false, null, null);
        $this->addColumn('gallery_insert', 'Insert', 'TIMESTAMP', false, null, null);
        $this->addColumn('gallery_update', 'Update', 'TIMESTAMP', false, null, null);
        $this->addColumn('gallery_created', 'Created', 'TIMESTAMP', false, null, null);
        $this->addColumn('gallery_updated', 'Updated', 'TIMESTAMP', false, null, null);
        $this->addColumn('gallery_deleted', 'Deleted', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' => ['create_column' => 'gallery_created', 'update_column' => 'gallery_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
        );
    } // getBehaviors()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
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
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
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
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? GalleryTableMap::CLASS_DEFAULT : GalleryTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (Gallery object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = GalleryTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = GalleryTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + GalleryTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = GalleryTableMap::OM_CLASS;
            /** @var Gallery $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            GalleryTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = GalleryTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = GalleryTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Gallery $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                GalleryTableMap::addInstanceToPool($obj, $key);
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
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(GalleryTableMap::COL_GALLERY_ID);
            $criteria->addSelectColumn(GalleryTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(GalleryTableMap::COL_GALLERY_TITLE);
            $criteria->addSelectColumn(GalleryTableMap::COL_MEDIA_DIR);
            $criteria->addSelectColumn(GalleryTableMap::COL_GALLERY_INSERT);
            $criteria->addSelectColumn(GalleryTableMap::COL_GALLERY_UPDATE);
            $criteria->addSelectColumn(GalleryTableMap::COL_GALLERY_CREATED);
            $criteria->addSelectColumn(GalleryTableMap::COL_GALLERY_UPDATED);
            $criteria->addSelectColumn(GalleryTableMap::COL_GALLERY_DELETED);
        } else {
            $criteria->addSelectColumn($alias . '.gallery_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.gallery_title');
            $criteria->addSelectColumn($alias . '.media_dir');
            $criteria->addSelectColumn($alias . '.gallery_insert');
            $criteria->addSelectColumn($alias . '.gallery_update');
            $criteria->addSelectColumn($alias . '.gallery_created');
            $criteria->addSelectColumn($alias . '.gallery_updated');
            $criteria->addSelectColumn($alias . '.gallery_deleted');
        }
    }

    /**
     * Remove all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be removed as they are only loaded on demand.
     *
     * @param Criteria $criteria object containing the columns to remove.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function removeSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->removeSelectColumn(GalleryTableMap::COL_GALLERY_ID);
            $criteria->removeSelectColumn(GalleryTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(GalleryTableMap::COL_GALLERY_TITLE);
            $criteria->removeSelectColumn(GalleryTableMap::COL_MEDIA_DIR);
            $criteria->removeSelectColumn(GalleryTableMap::COL_GALLERY_INSERT);
            $criteria->removeSelectColumn(GalleryTableMap::COL_GALLERY_UPDATE);
            $criteria->removeSelectColumn(GalleryTableMap::COL_GALLERY_CREATED);
            $criteria->removeSelectColumn(GalleryTableMap::COL_GALLERY_UPDATED);
            $criteria->removeSelectColumn(GalleryTableMap::COL_GALLERY_DELETED);
        } else {
            $criteria->removeSelectColumn($alias . '.gallery_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.gallery_title');
            $criteria->removeSelectColumn($alias . '.media_dir');
            $criteria->removeSelectColumn($alias . '.gallery_insert');
            $criteria->removeSelectColumn($alias . '.gallery_update');
            $criteria->removeSelectColumn($alias . '.gallery_created');
            $criteria->removeSelectColumn($alias . '.gallery_updated');
            $criteria->removeSelectColumn($alias . '.gallery_deleted');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(GalleryTableMap::DATABASE_NAME)->getTable(GalleryTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Gallery or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Gallery object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GalleryTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Gallery) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(GalleryTableMap::DATABASE_NAME);
            $criteria->add(GalleryTableMap::COL_GALLERY_ID, (array) $values, Criteria::IN);
        }

        $query = GalleryQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            GalleryTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                GalleryTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the galleries table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return GalleryQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Gallery or Criteria object.
     *
     * @param mixed               $criteria Criteria or Gallery object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GalleryTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Gallery object
        }

        if ($criteria->containsKey(GalleryTableMap::COL_GALLERY_ID) && $criteria->keyContainsValue(GalleryTableMap::COL_GALLERY_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.GalleryTableMap::COL_GALLERY_ID.')');
        }


        // Set the correct dbName
        $query = GalleryQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // GalleryTableMap
