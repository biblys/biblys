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


namespace Model\Map;

use Model\Rayon;
use Model\RayonQuery;
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
 * This class defines the structure of the 'rayons' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class RayonTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Model.Map.RayonTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'rayons';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\Rayon';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.Rayon';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 11;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 11;

    /**
     * the column name for the rayon_id field
     */
    const COL_RAYON_ID = 'rayons.rayon_id';

    /**
     * the column name for the site_id field
     */
    const COL_SITE_ID = 'rayons.site_id';

    /**
     * the column name for the rayon_name field
     */
    const COL_RAYON_NAME = 'rayons.rayon_name';

    /**
     * the column name for the rayon_url field
     */
    const COL_RAYON_URL = 'rayons.rayon_url';

    /**
     * the column name for the rayon_desc field
     */
    const COL_RAYON_DESC = 'rayons.rayon_desc';

    /**
     * the column name for the rayon_order field
     */
    const COL_RAYON_ORDER = 'rayons.rayon_order';

    /**
     * the column name for the rayon_sort_by field
     */
    const COL_RAYON_SORT_BY = 'rayons.rayon_sort_by';

    /**
     * the column name for the rayon_sort_order field
     */
    const COL_RAYON_SORT_ORDER = 'rayons.rayon_sort_order';

    /**
     * the column name for the rayon_show_upcoming field
     */
    const COL_RAYON_SHOW_UPCOMING = 'rayons.rayon_show_upcoming';

    /**
     * the column name for the rayon_created field
     */
    const COL_RAYON_CREATED = 'rayons.rayon_created';

    /**
     * the column name for the rayon_updated field
     */
    const COL_RAYON_UPDATED = 'rayons.rayon_updated';

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
        self::TYPE_PHPNAME       => array('Id', 'SiteId', 'Name', 'Url', 'Desc', 'Order', 'SortBy', 'SortOrder', 'ShowUpcoming', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'siteId', 'name', 'url', 'desc', 'order', 'sortBy', 'sortOrder', 'showUpcoming', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(RayonTableMap::COL_RAYON_ID, RayonTableMap::COL_SITE_ID, RayonTableMap::COL_RAYON_NAME, RayonTableMap::COL_RAYON_URL, RayonTableMap::COL_RAYON_DESC, RayonTableMap::COL_RAYON_ORDER, RayonTableMap::COL_RAYON_SORT_BY, RayonTableMap::COL_RAYON_SORT_ORDER, RayonTableMap::COL_RAYON_SHOW_UPCOMING, RayonTableMap::COL_RAYON_CREATED, RayonTableMap::COL_RAYON_UPDATED, ),
        self::TYPE_FIELDNAME     => array('rayon_id', 'site_id', 'rayon_name', 'rayon_url', 'rayon_desc', 'rayon_order', 'rayon_sort_by', 'rayon_sort_order', 'rayon_show_upcoming', 'rayon_created', 'rayon_updated', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'SiteId' => 1, 'Name' => 2, 'Url' => 3, 'Desc' => 4, 'Order' => 5, 'SortBy' => 6, 'SortOrder' => 7, 'ShowUpcoming' => 8, 'CreatedAt' => 9, 'UpdatedAt' => 10, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'siteId' => 1, 'name' => 2, 'url' => 3, 'desc' => 4, 'order' => 5, 'sortBy' => 6, 'sortOrder' => 7, 'showUpcoming' => 8, 'createdAt' => 9, 'updatedAt' => 10, ),
        self::TYPE_COLNAME       => array(RayonTableMap::COL_RAYON_ID => 0, RayonTableMap::COL_SITE_ID => 1, RayonTableMap::COL_RAYON_NAME => 2, RayonTableMap::COL_RAYON_URL => 3, RayonTableMap::COL_RAYON_DESC => 4, RayonTableMap::COL_RAYON_ORDER => 5, RayonTableMap::COL_RAYON_SORT_BY => 6, RayonTableMap::COL_RAYON_SORT_ORDER => 7, RayonTableMap::COL_RAYON_SHOW_UPCOMING => 8, RayonTableMap::COL_RAYON_CREATED => 9, RayonTableMap::COL_RAYON_UPDATED => 10, ),
        self::TYPE_FIELDNAME     => array('rayon_id' => 0, 'site_id' => 1, 'rayon_name' => 2, 'rayon_url' => 3, 'rayon_desc' => 4, 'rayon_order' => 5, 'rayon_sort_by' => 6, 'rayon_sort_order' => 7, 'rayon_show_upcoming' => 8, 'rayon_created' => 9, 'rayon_updated' => 10, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, )
    );

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var string[]
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'RAYON_ID',
        'Rayon.Id' => 'RAYON_ID',
        'id' => 'RAYON_ID',
        'rayon.id' => 'RAYON_ID',
        'RayonTableMap::COL_RAYON_ID' => 'RAYON_ID',
        'COL_RAYON_ID' => 'RAYON_ID',
        'rayon_id' => 'RAYON_ID',
        'rayons.rayon_id' => 'RAYON_ID',
        'SiteId' => 'SITE_ID',
        'Rayon.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'rayon.siteId' => 'SITE_ID',
        'RayonTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'rayons.site_id' => 'SITE_ID',
        'Name' => 'RAYON_NAME',
        'Rayon.Name' => 'RAYON_NAME',
        'name' => 'RAYON_NAME',
        'rayon.name' => 'RAYON_NAME',
        'RayonTableMap::COL_RAYON_NAME' => 'RAYON_NAME',
        'COL_RAYON_NAME' => 'RAYON_NAME',
        'rayon_name' => 'RAYON_NAME',
        'rayons.rayon_name' => 'RAYON_NAME',
        'Url' => 'RAYON_URL',
        'Rayon.Url' => 'RAYON_URL',
        'url' => 'RAYON_URL',
        'rayon.url' => 'RAYON_URL',
        'RayonTableMap::COL_RAYON_URL' => 'RAYON_URL',
        'COL_RAYON_URL' => 'RAYON_URL',
        'rayon_url' => 'RAYON_URL',
        'rayons.rayon_url' => 'RAYON_URL',
        'Desc' => 'RAYON_DESC',
        'Rayon.Desc' => 'RAYON_DESC',
        'desc' => 'RAYON_DESC',
        'rayon.desc' => 'RAYON_DESC',
        'RayonTableMap::COL_RAYON_DESC' => 'RAYON_DESC',
        'COL_RAYON_DESC' => 'RAYON_DESC',
        'rayon_desc' => 'RAYON_DESC',
        'rayons.rayon_desc' => 'RAYON_DESC',
        'Order' => 'RAYON_ORDER',
        'Rayon.Order' => 'RAYON_ORDER',
        'order' => 'RAYON_ORDER',
        'rayon.order' => 'RAYON_ORDER',
        'RayonTableMap::COL_RAYON_ORDER' => 'RAYON_ORDER',
        'COL_RAYON_ORDER' => 'RAYON_ORDER',
        'rayon_order' => 'RAYON_ORDER',
        'rayons.rayon_order' => 'RAYON_ORDER',
        'SortBy' => 'RAYON_SORT_BY',
        'Rayon.SortBy' => 'RAYON_SORT_BY',
        'sortBy' => 'RAYON_SORT_BY',
        'rayon.sortBy' => 'RAYON_SORT_BY',
        'RayonTableMap::COL_RAYON_SORT_BY' => 'RAYON_SORT_BY',
        'COL_RAYON_SORT_BY' => 'RAYON_SORT_BY',
        'rayon_sort_by' => 'RAYON_SORT_BY',
        'rayons.rayon_sort_by' => 'RAYON_SORT_BY',
        'SortOrder' => 'RAYON_SORT_ORDER',
        'Rayon.SortOrder' => 'RAYON_SORT_ORDER',
        'sortOrder' => 'RAYON_SORT_ORDER',
        'rayon.sortOrder' => 'RAYON_SORT_ORDER',
        'RayonTableMap::COL_RAYON_SORT_ORDER' => 'RAYON_SORT_ORDER',
        'COL_RAYON_SORT_ORDER' => 'RAYON_SORT_ORDER',
        'rayon_sort_order' => 'RAYON_SORT_ORDER',
        'rayons.rayon_sort_order' => 'RAYON_SORT_ORDER',
        'ShowUpcoming' => 'RAYON_SHOW_UPCOMING',
        'Rayon.ShowUpcoming' => 'RAYON_SHOW_UPCOMING',
        'showUpcoming' => 'RAYON_SHOW_UPCOMING',
        'rayon.showUpcoming' => 'RAYON_SHOW_UPCOMING',
        'RayonTableMap::COL_RAYON_SHOW_UPCOMING' => 'RAYON_SHOW_UPCOMING',
        'COL_RAYON_SHOW_UPCOMING' => 'RAYON_SHOW_UPCOMING',
        'rayon_show_upcoming' => 'RAYON_SHOW_UPCOMING',
        'rayons.rayon_show_upcoming' => 'RAYON_SHOW_UPCOMING',
        'CreatedAt' => 'RAYON_CREATED',
        'Rayon.CreatedAt' => 'RAYON_CREATED',
        'createdAt' => 'RAYON_CREATED',
        'rayon.createdAt' => 'RAYON_CREATED',
        'RayonTableMap::COL_RAYON_CREATED' => 'RAYON_CREATED',
        'COL_RAYON_CREATED' => 'RAYON_CREATED',
        'rayon_created' => 'RAYON_CREATED',
        'rayons.rayon_created' => 'RAYON_CREATED',
        'UpdatedAt' => 'RAYON_UPDATED',
        'Rayon.UpdatedAt' => 'RAYON_UPDATED',
        'updatedAt' => 'RAYON_UPDATED',
        'rayon.updatedAt' => 'RAYON_UPDATED',
        'RayonTableMap::COL_RAYON_UPDATED' => 'RAYON_UPDATED',
        'COL_RAYON_UPDATED' => 'RAYON_UPDATED',
        'rayon_updated' => 'RAYON_UPDATED',
        'rayons.rayon_updated' => 'RAYON_UPDATED',
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
        $this->setName('rayons');
        $this->setPhpName('Rayon');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Rayon');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('rayon_id', 'Id', 'BIGINT', true, 4, null);
        $this->addColumn('site_id', 'SiteId', 'TINYINT', false, 3, null);
        $this->addColumn('rayon_name', 'Name', 'LONGVARCHAR', false, null, null);
        $this->addColumn('rayon_url', 'Url', 'LONGVARCHAR', false, null, null);
        $this->addColumn('rayon_desc', 'Desc', 'LONGVARCHAR', false, null, null);
        $this->addColumn('rayon_order', 'Order', 'TINYINT', false, 2, null);
        $this->addColumn('rayon_sort_by', 'SortBy', 'VARCHAR', false, 64, 'id');
        $this->addColumn('rayon_sort_order', 'SortOrder', 'BOOLEAN', false, 1, false);
        $this->addColumn('rayon_show_upcoming', 'ShowUpcoming', 'BOOLEAN', false, 1, false);
        $this->addColumn('rayon_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('rayon_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
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
            'timestampable' => ['create_column' => 'rayon_created', 'update_column' => 'rayon_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return (string) $row[
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
        return $withPrefix ? RayonTableMap::CLASS_DEFAULT : RayonTableMap::OM_CLASS;
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
     * @return array           (Rayon object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = RayonTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = RayonTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + RayonTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = RayonTableMap::OM_CLASS;
            /** @var Rayon $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            RayonTableMap::addInstanceToPool($obj, $key);
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
            $key = RayonTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = RayonTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Rayon $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                RayonTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(RayonTableMap::COL_RAYON_ID);
            $criteria->addSelectColumn(RayonTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(RayonTableMap::COL_RAYON_NAME);
            $criteria->addSelectColumn(RayonTableMap::COL_RAYON_URL);
            $criteria->addSelectColumn(RayonTableMap::COL_RAYON_DESC);
            $criteria->addSelectColumn(RayonTableMap::COL_RAYON_ORDER);
            $criteria->addSelectColumn(RayonTableMap::COL_RAYON_SORT_BY);
            $criteria->addSelectColumn(RayonTableMap::COL_RAYON_SORT_ORDER);
            $criteria->addSelectColumn(RayonTableMap::COL_RAYON_SHOW_UPCOMING);
            $criteria->addSelectColumn(RayonTableMap::COL_RAYON_CREATED);
            $criteria->addSelectColumn(RayonTableMap::COL_RAYON_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.rayon_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.rayon_name');
            $criteria->addSelectColumn($alias . '.rayon_url');
            $criteria->addSelectColumn($alias . '.rayon_desc');
            $criteria->addSelectColumn($alias . '.rayon_order');
            $criteria->addSelectColumn($alias . '.rayon_sort_by');
            $criteria->addSelectColumn($alias . '.rayon_sort_order');
            $criteria->addSelectColumn($alias . '.rayon_show_upcoming');
            $criteria->addSelectColumn($alias . '.rayon_created');
            $criteria->addSelectColumn($alias . '.rayon_updated');
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
            $criteria->removeSelectColumn(RayonTableMap::COL_RAYON_ID);
            $criteria->removeSelectColumn(RayonTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(RayonTableMap::COL_RAYON_NAME);
            $criteria->removeSelectColumn(RayonTableMap::COL_RAYON_URL);
            $criteria->removeSelectColumn(RayonTableMap::COL_RAYON_DESC);
            $criteria->removeSelectColumn(RayonTableMap::COL_RAYON_ORDER);
            $criteria->removeSelectColumn(RayonTableMap::COL_RAYON_SORT_BY);
            $criteria->removeSelectColumn(RayonTableMap::COL_RAYON_SORT_ORDER);
            $criteria->removeSelectColumn(RayonTableMap::COL_RAYON_SHOW_UPCOMING);
            $criteria->removeSelectColumn(RayonTableMap::COL_RAYON_CREATED);
            $criteria->removeSelectColumn(RayonTableMap::COL_RAYON_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.rayon_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.rayon_name');
            $criteria->removeSelectColumn($alias . '.rayon_url');
            $criteria->removeSelectColumn($alias . '.rayon_desc');
            $criteria->removeSelectColumn($alias . '.rayon_order');
            $criteria->removeSelectColumn($alias . '.rayon_sort_by');
            $criteria->removeSelectColumn($alias . '.rayon_sort_order');
            $criteria->removeSelectColumn($alias . '.rayon_show_upcoming');
            $criteria->removeSelectColumn($alias . '.rayon_created');
            $criteria->removeSelectColumn($alias . '.rayon_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(RayonTableMap::DATABASE_NAME)->getTable(RayonTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Rayon or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Rayon object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(RayonTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Rayon) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(RayonTableMap::DATABASE_NAME);
            $criteria->add(RayonTableMap::COL_RAYON_ID, (array) $values, Criteria::IN);
        }

        $query = RayonQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            RayonTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                RayonTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the rayons table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return RayonQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Rayon or Criteria object.
     *
     * @param mixed               $criteria Criteria or Rayon object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(RayonTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Rayon object
        }

        if ($criteria->containsKey(RayonTableMap::COL_RAYON_ID) && $criteria->keyContainsValue(RayonTableMap::COL_RAYON_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.RayonTableMap::COL_RAYON_ID.')');
        }


        // Set the correct dbName
        $query = RayonQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // RayonTableMap
