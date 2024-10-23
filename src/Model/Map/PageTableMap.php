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

use Model\Page;
use Model\PageQuery;
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
 * This class defines the structure of the 'pages' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class PageTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.PageTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'pages';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Page';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Page';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Page';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 10;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 10;

    /**
     * the column name for the page_id field
     */
    public const COL_PAGE_ID = 'pages.page_id';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'pages.site_id';

    /**
     * the column name for the page_url field
     */
    public const COL_PAGE_URL = 'pages.page_url';

    /**
     * the column name for the page_title field
     */
    public const COL_PAGE_TITLE = 'pages.page_title';

    /**
     * the column name for the page_content field
     */
    public const COL_PAGE_CONTENT = 'pages.page_content';

    /**
     * the column name for the page_status field
     */
    public const COL_PAGE_STATUS = 'pages.page_status';

    /**
     * the column name for the page_insert field
     */
    public const COL_PAGE_INSERT = 'pages.page_insert';

    /**
     * the column name for the page_update field
     */
    public const COL_PAGE_UPDATE = 'pages.page_update';

    /**
     * the column name for the page_created field
     */
    public const COL_PAGE_CREATED = 'pages.page_created';

    /**
     * the column name for the page_updated field
     */
    public const COL_PAGE_UPDATED = 'pages.page_updated';

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
        self::TYPE_PHPNAME       => ['Id', 'SiteId', 'Url', 'Title', 'Content', 'Status', 'Insert', 'Update', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'siteId', 'url', 'title', 'content', 'status', 'insert', 'update', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [PageTableMap::COL_PAGE_ID, PageTableMap::COL_SITE_ID, PageTableMap::COL_PAGE_URL, PageTableMap::COL_PAGE_TITLE, PageTableMap::COL_PAGE_CONTENT, PageTableMap::COL_PAGE_STATUS, PageTableMap::COL_PAGE_INSERT, PageTableMap::COL_PAGE_UPDATE, PageTableMap::COL_PAGE_CREATED, PageTableMap::COL_PAGE_UPDATED, ],
        self::TYPE_FIELDNAME     => ['page_id', 'site_id', 'page_url', 'page_title', 'page_content', 'page_status', 'page_insert', 'page_update', 'page_created', 'page_updated', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'SiteId' => 1, 'Url' => 2, 'Title' => 3, 'Content' => 4, 'Status' => 5, 'Insert' => 6, 'Update' => 7, 'CreatedAt' => 8, 'UpdatedAt' => 9, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'siteId' => 1, 'url' => 2, 'title' => 3, 'content' => 4, 'status' => 5, 'insert' => 6, 'update' => 7, 'createdAt' => 8, 'updatedAt' => 9, ],
        self::TYPE_COLNAME       => [PageTableMap::COL_PAGE_ID => 0, PageTableMap::COL_SITE_ID => 1, PageTableMap::COL_PAGE_URL => 2, PageTableMap::COL_PAGE_TITLE => 3, PageTableMap::COL_PAGE_CONTENT => 4, PageTableMap::COL_PAGE_STATUS => 5, PageTableMap::COL_PAGE_INSERT => 6, PageTableMap::COL_PAGE_UPDATE => 7, PageTableMap::COL_PAGE_CREATED => 8, PageTableMap::COL_PAGE_UPDATED => 9, ],
        self::TYPE_FIELDNAME     => ['page_id' => 0, 'site_id' => 1, 'page_url' => 2, 'page_title' => 3, 'page_content' => 4, 'page_status' => 5, 'page_insert' => 6, 'page_update' => 7, 'page_created' => 8, 'page_updated' => 9, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'PAGE_ID',
        'Page.Id' => 'PAGE_ID',
        'id' => 'PAGE_ID',
        'page.id' => 'PAGE_ID',
        'PageTableMap::COL_PAGE_ID' => 'PAGE_ID',
        'COL_PAGE_ID' => 'PAGE_ID',
        'page_id' => 'PAGE_ID',
        'pages.page_id' => 'PAGE_ID',
        'SiteId' => 'SITE_ID',
        'Page.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'page.siteId' => 'SITE_ID',
        'PageTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'pages.site_id' => 'SITE_ID',
        'Url' => 'PAGE_URL',
        'Page.Url' => 'PAGE_URL',
        'url' => 'PAGE_URL',
        'page.url' => 'PAGE_URL',
        'PageTableMap::COL_PAGE_URL' => 'PAGE_URL',
        'COL_PAGE_URL' => 'PAGE_URL',
        'page_url' => 'PAGE_URL',
        'pages.page_url' => 'PAGE_URL',
        'Title' => 'PAGE_TITLE',
        'Page.Title' => 'PAGE_TITLE',
        'title' => 'PAGE_TITLE',
        'page.title' => 'PAGE_TITLE',
        'PageTableMap::COL_PAGE_TITLE' => 'PAGE_TITLE',
        'COL_PAGE_TITLE' => 'PAGE_TITLE',
        'page_title' => 'PAGE_TITLE',
        'pages.page_title' => 'PAGE_TITLE',
        'Content' => 'PAGE_CONTENT',
        'Page.Content' => 'PAGE_CONTENT',
        'content' => 'PAGE_CONTENT',
        'page.content' => 'PAGE_CONTENT',
        'PageTableMap::COL_PAGE_CONTENT' => 'PAGE_CONTENT',
        'COL_PAGE_CONTENT' => 'PAGE_CONTENT',
        'page_content' => 'PAGE_CONTENT',
        'pages.page_content' => 'PAGE_CONTENT',
        'Status' => 'PAGE_STATUS',
        'Page.Status' => 'PAGE_STATUS',
        'status' => 'PAGE_STATUS',
        'page.status' => 'PAGE_STATUS',
        'PageTableMap::COL_PAGE_STATUS' => 'PAGE_STATUS',
        'COL_PAGE_STATUS' => 'PAGE_STATUS',
        'page_status' => 'PAGE_STATUS',
        'pages.page_status' => 'PAGE_STATUS',
        'Insert' => 'PAGE_INSERT',
        'Page.Insert' => 'PAGE_INSERT',
        'insert' => 'PAGE_INSERT',
        'page.insert' => 'PAGE_INSERT',
        'PageTableMap::COL_PAGE_INSERT' => 'PAGE_INSERT',
        'COL_PAGE_INSERT' => 'PAGE_INSERT',
        'page_insert' => 'PAGE_INSERT',
        'pages.page_insert' => 'PAGE_INSERT',
        'Update' => 'PAGE_UPDATE',
        'Page.Update' => 'PAGE_UPDATE',
        'update' => 'PAGE_UPDATE',
        'page.update' => 'PAGE_UPDATE',
        'PageTableMap::COL_PAGE_UPDATE' => 'PAGE_UPDATE',
        'COL_PAGE_UPDATE' => 'PAGE_UPDATE',
        'page_update' => 'PAGE_UPDATE',
        'pages.page_update' => 'PAGE_UPDATE',
        'CreatedAt' => 'PAGE_CREATED',
        'Page.CreatedAt' => 'PAGE_CREATED',
        'createdAt' => 'PAGE_CREATED',
        'page.createdAt' => 'PAGE_CREATED',
        'PageTableMap::COL_PAGE_CREATED' => 'PAGE_CREATED',
        'COL_PAGE_CREATED' => 'PAGE_CREATED',
        'page_created' => 'PAGE_CREATED',
        'pages.page_created' => 'PAGE_CREATED',
        'UpdatedAt' => 'PAGE_UPDATED',
        'Page.UpdatedAt' => 'PAGE_UPDATED',
        'updatedAt' => 'PAGE_UPDATED',
        'page.updatedAt' => 'PAGE_UPDATED',
        'PageTableMap::COL_PAGE_UPDATED' => 'PAGE_UPDATED',
        'COL_PAGE_UPDATED' => 'PAGE_UPDATED',
        'page_updated' => 'PAGE_UPDATED',
        'pages.page_updated' => 'PAGE_UPDATED',
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
        $this->setName('pages');
        $this->setPhpName('Page');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Page');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('page_id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('site_id', 'SiteId', 'INTEGER', 'sites', 'site_id', false, null, null);
        $this->addColumn('page_url', 'Url', 'LONGVARCHAR', false, null, null);
        $this->addColumn('page_title', 'Title', 'LONGVARCHAR', false, null, null);
        $this->addColumn('page_content', 'Content', 'LONGVARCHAR', false, null, null);
        $this->addColumn('page_status', 'Status', 'BOOLEAN', false, 1, null);
        $this->addColumn('page_insert', 'Insert', 'TIMESTAMP', false, null, null);
        $this->addColumn('page_update', 'Update', 'TIMESTAMP', false, null, null);
        $this->addColumn('page_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('page_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
        $this->addRelation('Site', '\\Model\\Site', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':site_id',
    1 => ':site_id',
  ),
), null, null, null, false);
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
            'timestampable' => ['create_column' => 'page_created', 'update_column' => 'page_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? PageTableMap::CLASS_DEFAULT : PageTableMap::OM_CLASS;
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
     * @return array (Page object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = PageTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = PageTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + PageTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PageTableMap::OM_CLASS;
            /** @var Page $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            PageTableMap::addInstanceToPool($obj, $key);
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
            $key = PageTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = PageTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Page $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PageTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(PageTableMap::COL_PAGE_ID);
            $criteria->addSelectColumn(PageTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(PageTableMap::COL_PAGE_URL);
            $criteria->addSelectColumn(PageTableMap::COL_PAGE_TITLE);
            $criteria->addSelectColumn(PageTableMap::COL_PAGE_CONTENT);
            $criteria->addSelectColumn(PageTableMap::COL_PAGE_STATUS);
            $criteria->addSelectColumn(PageTableMap::COL_PAGE_INSERT);
            $criteria->addSelectColumn(PageTableMap::COL_PAGE_UPDATE);
            $criteria->addSelectColumn(PageTableMap::COL_PAGE_CREATED);
            $criteria->addSelectColumn(PageTableMap::COL_PAGE_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.page_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.page_url');
            $criteria->addSelectColumn($alias . '.page_title');
            $criteria->addSelectColumn($alias . '.page_content');
            $criteria->addSelectColumn($alias . '.page_status');
            $criteria->addSelectColumn($alias . '.page_insert');
            $criteria->addSelectColumn($alias . '.page_update');
            $criteria->addSelectColumn($alias . '.page_created');
            $criteria->addSelectColumn($alias . '.page_updated');
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
            $criteria->removeSelectColumn(PageTableMap::COL_PAGE_ID);
            $criteria->removeSelectColumn(PageTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(PageTableMap::COL_PAGE_URL);
            $criteria->removeSelectColumn(PageTableMap::COL_PAGE_TITLE);
            $criteria->removeSelectColumn(PageTableMap::COL_PAGE_CONTENT);
            $criteria->removeSelectColumn(PageTableMap::COL_PAGE_STATUS);
            $criteria->removeSelectColumn(PageTableMap::COL_PAGE_INSERT);
            $criteria->removeSelectColumn(PageTableMap::COL_PAGE_UPDATE);
            $criteria->removeSelectColumn(PageTableMap::COL_PAGE_CREATED);
            $criteria->removeSelectColumn(PageTableMap::COL_PAGE_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.page_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.page_url');
            $criteria->removeSelectColumn($alias . '.page_title');
            $criteria->removeSelectColumn($alias . '.page_content');
            $criteria->removeSelectColumn($alias . '.page_status');
            $criteria->removeSelectColumn($alias . '.page_insert');
            $criteria->removeSelectColumn($alias . '.page_update');
            $criteria->removeSelectColumn($alias . '.page_created');
            $criteria->removeSelectColumn($alias . '.page_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(PageTableMap::DATABASE_NAME)->getTable(PageTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Page or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Page object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(PageTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Page) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PageTableMap::DATABASE_NAME);
            $criteria->add(PageTableMap::COL_PAGE_ID, (array) $values, Criteria::IN);
        }

        $query = PageQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            PageTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                PageTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the pages table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return PageQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Page or Criteria object.
     *
     * @param mixed $criteria Criteria or Page object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PageTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Page object
        }

        if ($criteria->containsKey(PageTableMap::COL_PAGE_ID) && $criteria->keyContainsValue(PageTableMap::COL_PAGE_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.PageTableMap::COL_PAGE_ID.')');
        }


        // Set the correct dbName
        $query = PageQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
