<?php

namespace Model\Map;

use Model\BlogCategory;
use Model\BlogCategoryQuery;
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
 * This class defines the structure of the 'categories' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class BlogCategoryTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.BlogCategoryTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'categories';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'BlogCategory';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\BlogCategory';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.BlogCategory';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 11;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 11;

    /**
     * the column name for the category_id field
     */
    public const COL_CATEGORY_ID = 'categories.category_id';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'categories.site_id';

    /**
     * the column name for the category_name field
     */
    public const COL_CATEGORY_NAME = 'categories.category_name';

    /**
     * the column name for the category_url field
     */
    public const COL_CATEGORY_URL = 'categories.category_url';

    /**
     * the column name for the category_desc field
     */
    public const COL_CATEGORY_DESC = 'categories.category_desc';

    /**
     * the column name for the category_order field
     */
    public const COL_CATEGORY_ORDER = 'categories.category_order';

    /**
     * the column name for the category_hidden field
     */
    public const COL_CATEGORY_HIDDEN = 'categories.category_hidden';

    /**
     * the column name for the category_insert field
     */
    public const COL_CATEGORY_INSERT = 'categories.category_insert';

    /**
     * the column name for the category_update field
     */
    public const COL_CATEGORY_UPDATE = 'categories.category_update';

    /**
     * the column name for the category_created field
     */
    public const COL_CATEGORY_CREATED = 'categories.category_created';

    /**
     * the column name for the category_updated field
     */
    public const COL_CATEGORY_UPDATED = 'categories.category_updated';

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
        self::TYPE_PHPNAME       => ['Id', 'SiteId', 'Name', 'Url', 'Desc', 'Order', 'Hidden', 'Insert', 'Update', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'siteId', 'name', 'url', 'desc', 'order', 'hidden', 'insert', 'update', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [BlogCategoryTableMap::COL_CATEGORY_ID, BlogCategoryTableMap::COL_SITE_ID, BlogCategoryTableMap::COL_CATEGORY_NAME, BlogCategoryTableMap::COL_CATEGORY_URL, BlogCategoryTableMap::COL_CATEGORY_DESC, BlogCategoryTableMap::COL_CATEGORY_ORDER, BlogCategoryTableMap::COL_CATEGORY_HIDDEN, BlogCategoryTableMap::COL_CATEGORY_INSERT, BlogCategoryTableMap::COL_CATEGORY_UPDATE, BlogCategoryTableMap::COL_CATEGORY_CREATED, BlogCategoryTableMap::COL_CATEGORY_UPDATED, ],
        self::TYPE_FIELDNAME     => ['category_id', 'site_id', 'category_name', 'category_url', 'category_desc', 'category_order', 'category_hidden', 'category_insert', 'category_update', 'category_created', 'category_updated', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'SiteId' => 1, 'Name' => 2, 'Url' => 3, 'Desc' => 4, 'Order' => 5, 'Hidden' => 6, 'Insert' => 7, 'Update' => 8, 'CreatedAt' => 9, 'UpdatedAt' => 10, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'siteId' => 1, 'name' => 2, 'url' => 3, 'desc' => 4, 'order' => 5, 'hidden' => 6, 'insert' => 7, 'update' => 8, 'createdAt' => 9, 'updatedAt' => 10, ],
        self::TYPE_COLNAME       => [BlogCategoryTableMap::COL_CATEGORY_ID => 0, BlogCategoryTableMap::COL_SITE_ID => 1, BlogCategoryTableMap::COL_CATEGORY_NAME => 2, BlogCategoryTableMap::COL_CATEGORY_URL => 3, BlogCategoryTableMap::COL_CATEGORY_DESC => 4, BlogCategoryTableMap::COL_CATEGORY_ORDER => 5, BlogCategoryTableMap::COL_CATEGORY_HIDDEN => 6, BlogCategoryTableMap::COL_CATEGORY_INSERT => 7, BlogCategoryTableMap::COL_CATEGORY_UPDATE => 8, BlogCategoryTableMap::COL_CATEGORY_CREATED => 9, BlogCategoryTableMap::COL_CATEGORY_UPDATED => 10, ],
        self::TYPE_FIELDNAME     => ['category_id' => 0, 'site_id' => 1, 'category_name' => 2, 'category_url' => 3, 'category_desc' => 4, 'category_order' => 5, 'category_hidden' => 6, 'category_insert' => 7, 'category_update' => 8, 'category_created' => 9, 'category_updated' => 10, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'CATEGORY_ID',
        'BlogCategory.Id' => 'CATEGORY_ID',
        'id' => 'CATEGORY_ID',
        'blogCategory.id' => 'CATEGORY_ID',
        'BlogCategoryTableMap::COL_CATEGORY_ID' => 'CATEGORY_ID',
        'COL_CATEGORY_ID' => 'CATEGORY_ID',
        'category_id' => 'CATEGORY_ID',
        'categories.category_id' => 'CATEGORY_ID',
        'SiteId' => 'SITE_ID',
        'BlogCategory.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'blogCategory.siteId' => 'SITE_ID',
        'BlogCategoryTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'categories.site_id' => 'SITE_ID',
        'Name' => 'CATEGORY_NAME',
        'BlogCategory.Name' => 'CATEGORY_NAME',
        'name' => 'CATEGORY_NAME',
        'blogCategory.name' => 'CATEGORY_NAME',
        'BlogCategoryTableMap::COL_CATEGORY_NAME' => 'CATEGORY_NAME',
        'COL_CATEGORY_NAME' => 'CATEGORY_NAME',
        'category_name' => 'CATEGORY_NAME',
        'categories.category_name' => 'CATEGORY_NAME',
        'Url' => 'CATEGORY_URL',
        'BlogCategory.Url' => 'CATEGORY_URL',
        'url' => 'CATEGORY_URL',
        'blogCategory.url' => 'CATEGORY_URL',
        'BlogCategoryTableMap::COL_CATEGORY_URL' => 'CATEGORY_URL',
        'COL_CATEGORY_URL' => 'CATEGORY_URL',
        'category_url' => 'CATEGORY_URL',
        'categories.category_url' => 'CATEGORY_URL',
        'Desc' => 'CATEGORY_DESC',
        'BlogCategory.Desc' => 'CATEGORY_DESC',
        'desc' => 'CATEGORY_DESC',
        'blogCategory.desc' => 'CATEGORY_DESC',
        'BlogCategoryTableMap::COL_CATEGORY_DESC' => 'CATEGORY_DESC',
        'COL_CATEGORY_DESC' => 'CATEGORY_DESC',
        'category_desc' => 'CATEGORY_DESC',
        'categories.category_desc' => 'CATEGORY_DESC',
        'Order' => 'CATEGORY_ORDER',
        'BlogCategory.Order' => 'CATEGORY_ORDER',
        'order' => 'CATEGORY_ORDER',
        'blogCategory.order' => 'CATEGORY_ORDER',
        'BlogCategoryTableMap::COL_CATEGORY_ORDER' => 'CATEGORY_ORDER',
        'COL_CATEGORY_ORDER' => 'CATEGORY_ORDER',
        'category_order' => 'CATEGORY_ORDER',
        'categories.category_order' => 'CATEGORY_ORDER',
        'Hidden' => 'CATEGORY_HIDDEN',
        'BlogCategory.Hidden' => 'CATEGORY_HIDDEN',
        'hidden' => 'CATEGORY_HIDDEN',
        'blogCategory.hidden' => 'CATEGORY_HIDDEN',
        'BlogCategoryTableMap::COL_CATEGORY_HIDDEN' => 'CATEGORY_HIDDEN',
        'COL_CATEGORY_HIDDEN' => 'CATEGORY_HIDDEN',
        'category_hidden' => 'CATEGORY_HIDDEN',
        'categories.category_hidden' => 'CATEGORY_HIDDEN',
        'Insert' => 'CATEGORY_INSERT',
        'BlogCategory.Insert' => 'CATEGORY_INSERT',
        'insert' => 'CATEGORY_INSERT',
        'blogCategory.insert' => 'CATEGORY_INSERT',
        'BlogCategoryTableMap::COL_CATEGORY_INSERT' => 'CATEGORY_INSERT',
        'COL_CATEGORY_INSERT' => 'CATEGORY_INSERT',
        'category_insert' => 'CATEGORY_INSERT',
        'categories.category_insert' => 'CATEGORY_INSERT',
        'Update' => 'CATEGORY_UPDATE',
        'BlogCategory.Update' => 'CATEGORY_UPDATE',
        'update' => 'CATEGORY_UPDATE',
        'blogCategory.update' => 'CATEGORY_UPDATE',
        'BlogCategoryTableMap::COL_CATEGORY_UPDATE' => 'CATEGORY_UPDATE',
        'COL_CATEGORY_UPDATE' => 'CATEGORY_UPDATE',
        'category_update' => 'CATEGORY_UPDATE',
        'categories.category_update' => 'CATEGORY_UPDATE',
        'CreatedAt' => 'CATEGORY_CREATED',
        'BlogCategory.CreatedAt' => 'CATEGORY_CREATED',
        'createdAt' => 'CATEGORY_CREATED',
        'blogCategory.createdAt' => 'CATEGORY_CREATED',
        'BlogCategoryTableMap::COL_CATEGORY_CREATED' => 'CATEGORY_CREATED',
        'COL_CATEGORY_CREATED' => 'CATEGORY_CREATED',
        'category_created' => 'CATEGORY_CREATED',
        'categories.category_created' => 'CATEGORY_CREATED',
        'UpdatedAt' => 'CATEGORY_UPDATED',
        'BlogCategory.UpdatedAt' => 'CATEGORY_UPDATED',
        'updatedAt' => 'CATEGORY_UPDATED',
        'blogCategory.updatedAt' => 'CATEGORY_UPDATED',
        'BlogCategoryTableMap::COL_CATEGORY_UPDATED' => 'CATEGORY_UPDATED',
        'COL_CATEGORY_UPDATED' => 'CATEGORY_UPDATED',
        'category_updated' => 'CATEGORY_UPDATED',
        'categories.category_updated' => 'CATEGORY_UPDATED',
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
        $this->setName('categories');
        $this->setPhpName('BlogCategory');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\BlogCategory');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('category_id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('site_id', 'SiteId', 'INTEGER', false, null, null);
        $this->addColumn('category_name', 'Name', 'VARCHAR', false, 64, null);
        $this->addColumn('category_url', 'Url', 'VARCHAR', false, 256, null);
        $this->addColumn('category_desc', 'Desc', 'LONGVARCHAR', false, null, null);
        $this->addColumn('category_order', 'Order', 'TINYINT', false, 3, null);
        $this->addColumn('category_hidden', 'Hidden', 'BOOLEAN', false, 1, false);
        $this->addColumn('category_insert', 'Insert', 'TIMESTAMP', false, null, null);
        $this->addColumn('category_update', 'Update', 'TIMESTAMP', false, null, null);
        $this->addColumn('category_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('category_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
        $this->addRelation('Post', '\\Model\\Post', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':category_id',
    1 => ':category_id',
  ),
), null, null, 'Posts', false);
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
            'timestampable' => ['create_column' => 'category_created', 'update_column' => 'category_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? BlogCategoryTableMap::CLASS_DEFAULT : BlogCategoryTableMap::OM_CLASS;
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
     * @return array (BlogCategory object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = BlogCategoryTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = BlogCategoryTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + BlogCategoryTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = BlogCategoryTableMap::OM_CLASS;
            /** @var BlogCategory $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            BlogCategoryTableMap::addInstanceToPool($obj, $key);
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
            $key = BlogCategoryTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = BlogCategoryTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var BlogCategory $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                BlogCategoryTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(BlogCategoryTableMap::COL_CATEGORY_ID);
            $criteria->addSelectColumn(BlogCategoryTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(BlogCategoryTableMap::COL_CATEGORY_NAME);
            $criteria->addSelectColumn(BlogCategoryTableMap::COL_CATEGORY_URL);
            $criteria->addSelectColumn(BlogCategoryTableMap::COL_CATEGORY_DESC);
            $criteria->addSelectColumn(BlogCategoryTableMap::COL_CATEGORY_ORDER);
            $criteria->addSelectColumn(BlogCategoryTableMap::COL_CATEGORY_HIDDEN);
            $criteria->addSelectColumn(BlogCategoryTableMap::COL_CATEGORY_INSERT);
            $criteria->addSelectColumn(BlogCategoryTableMap::COL_CATEGORY_UPDATE);
            $criteria->addSelectColumn(BlogCategoryTableMap::COL_CATEGORY_CREATED);
            $criteria->addSelectColumn(BlogCategoryTableMap::COL_CATEGORY_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.category_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.category_name');
            $criteria->addSelectColumn($alias . '.category_url');
            $criteria->addSelectColumn($alias . '.category_desc');
            $criteria->addSelectColumn($alias . '.category_order');
            $criteria->addSelectColumn($alias . '.category_hidden');
            $criteria->addSelectColumn($alias . '.category_insert');
            $criteria->addSelectColumn($alias . '.category_update');
            $criteria->addSelectColumn($alias . '.category_created');
            $criteria->addSelectColumn($alias . '.category_updated');
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
            $criteria->removeSelectColumn(BlogCategoryTableMap::COL_CATEGORY_ID);
            $criteria->removeSelectColumn(BlogCategoryTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(BlogCategoryTableMap::COL_CATEGORY_NAME);
            $criteria->removeSelectColumn(BlogCategoryTableMap::COL_CATEGORY_URL);
            $criteria->removeSelectColumn(BlogCategoryTableMap::COL_CATEGORY_DESC);
            $criteria->removeSelectColumn(BlogCategoryTableMap::COL_CATEGORY_ORDER);
            $criteria->removeSelectColumn(BlogCategoryTableMap::COL_CATEGORY_HIDDEN);
            $criteria->removeSelectColumn(BlogCategoryTableMap::COL_CATEGORY_INSERT);
            $criteria->removeSelectColumn(BlogCategoryTableMap::COL_CATEGORY_UPDATE);
            $criteria->removeSelectColumn(BlogCategoryTableMap::COL_CATEGORY_CREATED);
            $criteria->removeSelectColumn(BlogCategoryTableMap::COL_CATEGORY_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.category_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.category_name');
            $criteria->removeSelectColumn($alias . '.category_url');
            $criteria->removeSelectColumn($alias . '.category_desc');
            $criteria->removeSelectColumn($alias . '.category_order');
            $criteria->removeSelectColumn($alias . '.category_hidden');
            $criteria->removeSelectColumn($alias . '.category_insert');
            $criteria->removeSelectColumn($alias . '.category_update');
            $criteria->removeSelectColumn($alias . '.category_created');
            $criteria->removeSelectColumn($alias . '.category_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(BlogCategoryTableMap::DATABASE_NAME)->getTable(BlogCategoryTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a BlogCategory or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or BlogCategory object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(BlogCategoryTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\BlogCategory) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(BlogCategoryTableMap::DATABASE_NAME);
            $criteria->add(BlogCategoryTableMap::COL_CATEGORY_ID, (array) $values, Criteria::IN);
        }

        $query = BlogCategoryQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            BlogCategoryTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                BlogCategoryTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the categories table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return BlogCategoryQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a BlogCategory or Criteria object.
     *
     * @param mixed $criteria Criteria or BlogCategory object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(BlogCategoryTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from BlogCategory object
        }

        if ($criteria->containsKey(BlogCategoryTableMap::COL_CATEGORY_ID) && $criteria->keyContainsValue(BlogCategoryTableMap::COL_CATEGORY_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.BlogCategoryTableMap::COL_CATEGORY_ID.')');
        }


        // Set the correct dbName
        $query = BlogCategoryQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
