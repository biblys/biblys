<?php

namespace Model\Map;

use Model\Supplier;
use Model\SupplierQuery;
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
 * This class defines the structure of the 'suppliers' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class SupplierTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.SupplierTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'suppliers';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Supplier';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Supplier';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Supplier';

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
     * the column name for the supplier_id field
     */
    public const COL_SUPPLIER_ID = 'suppliers.supplier_id';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'suppliers.site_id';

    /**
     * the column name for the supplier_name field
     */
    public const COL_SUPPLIER_NAME = 'suppliers.supplier_name';

    /**
     * the column name for the supplier_gln field
     */
    public const COL_SUPPLIER_GLN = 'suppliers.supplier_gln';

    /**
     * the column name for the supplier_remise field
     */
    public const COL_SUPPLIER_REMISE = 'suppliers.supplier_remise';

    /**
     * the column name for the supplier_notva field
     */
    public const COL_SUPPLIER_NOTVA = 'suppliers.supplier_notva';

    /**
     * the column name for the supplier_on_order field
     */
    public const COL_SUPPLIER_ON_ORDER = 'suppliers.supplier_on_order';

    /**
     * the column name for the supplier_insert field
     */
    public const COL_SUPPLIER_INSERT = 'suppliers.supplier_insert';

    /**
     * the column name for the supplier_update field
     */
    public const COL_SUPPLIER_UPDATE = 'suppliers.supplier_update';

    /**
     * the column name for the supplier_created field
     */
    public const COL_SUPPLIER_CREATED = 'suppliers.supplier_created';

    /**
     * the column name for the supplier_updated field
     */
    public const COL_SUPPLIER_UPDATED = 'suppliers.supplier_updated';

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
        self::TYPE_PHPNAME       => ['Id', 'SiteId', 'Name', 'Gln', 'Remise', 'Notva', 'OnOrder', 'Insert', 'Update', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'siteId', 'name', 'gln', 'remise', 'notva', 'onOrder', 'insert', 'update', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [SupplierTableMap::COL_SUPPLIER_ID, SupplierTableMap::COL_SITE_ID, SupplierTableMap::COL_SUPPLIER_NAME, SupplierTableMap::COL_SUPPLIER_GLN, SupplierTableMap::COL_SUPPLIER_REMISE, SupplierTableMap::COL_SUPPLIER_NOTVA, SupplierTableMap::COL_SUPPLIER_ON_ORDER, SupplierTableMap::COL_SUPPLIER_INSERT, SupplierTableMap::COL_SUPPLIER_UPDATE, SupplierTableMap::COL_SUPPLIER_CREATED, SupplierTableMap::COL_SUPPLIER_UPDATED, ],
        self::TYPE_FIELDNAME     => ['supplier_id', 'site_id', 'supplier_name', 'supplier_gln', 'supplier_remise', 'supplier_notva', 'supplier_on_order', 'supplier_insert', 'supplier_update', 'supplier_created', 'supplier_updated', ],
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'SiteId' => 1, 'Name' => 2, 'Gln' => 3, 'Remise' => 4, 'Notva' => 5, 'OnOrder' => 6, 'Insert' => 7, 'Update' => 8, 'CreatedAt' => 9, 'UpdatedAt' => 10, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'siteId' => 1, 'name' => 2, 'gln' => 3, 'remise' => 4, 'notva' => 5, 'onOrder' => 6, 'insert' => 7, 'update' => 8, 'createdAt' => 9, 'updatedAt' => 10, ],
        self::TYPE_COLNAME       => [SupplierTableMap::COL_SUPPLIER_ID => 0, SupplierTableMap::COL_SITE_ID => 1, SupplierTableMap::COL_SUPPLIER_NAME => 2, SupplierTableMap::COL_SUPPLIER_GLN => 3, SupplierTableMap::COL_SUPPLIER_REMISE => 4, SupplierTableMap::COL_SUPPLIER_NOTVA => 5, SupplierTableMap::COL_SUPPLIER_ON_ORDER => 6, SupplierTableMap::COL_SUPPLIER_INSERT => 7, SupplierTableMap::COL_SUPPLIER_UPDATE => 8, SupplierTableMap::COL_SUPPLIER_CREATED => 9, SupplierTableMap::COL_SUPPLIER_UPDATED => 10, ],
        self::TYPE_FIELDNAME     => ['supplier_id' => 0, 'site_id' => 1, 'supplier_name' => 2, 'supplier_gln' => 3, 'supplier_remise' => 4, 'supplier_notva' => 5, 'supplier_on_order' => 6, 'supplier_insert' => 7, 'supplier_update' => 8, 'supplier_created' => 9, 'supplier_updated' => 10, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'SUPPLIER_ID',
        'Supplier.Id' => 'SUPPLIER_ID',
        'id' => 'SUPPLIER_ID',
        'supplier.id' => 'SUPPLIER_ID',
        'SupplierTableMap::COL_SUPPLIER_ID' => 'SUPPLIER_ID',
        'COL_SUPPLIER_ID' => 'SUPPLIER_ID',
        'supplier_id' => 'SUPPLIER_ID',
        'suppliers.supplier_id' => 'SUPPLIER_ID',
        'SiteId' => 'SITE_ID',
        'Supplier.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'supplier.siteId' => 'SITE_ID',
        'SupplierTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'suppliers.site_id' => 'SITE_ID',
        'Name' => 'SUPPLIER_NAME',
        'Supplier.Name' => 'SUPPLIER_NAME',
        'name' => 'SUPPLIER_NAME',
        'supplier.name' => 'SUPPLIER_NAME',
        'SupplierTableMap::COL_SUPPLIER_NAME' => 'SUPPLIER_NAME',
        'COL_SUPPLIER_NAME' => 'SUPPLIER_NAME',
        'supplier_name' => 'SUPPLIER_NAME',
        'suppliers.supplier_name' => 'SUPPLIER_NAME',
        'Gln' => 'SUPPLIER_GLN',
        'Supplier.Gln' => 'SUPPLIER_GLN',
        'gln' => 'SUPPLIER_GLN',
        'supplier.gln' => 'SUPPLIER_GLN',
        'SupplierTableMap::COL_SUPPLIER_GLN' => 'SUPPLIER_GLN',
        'COL_SUPPLIER_GLN' => 'SUPPLIER_GLN',
        'supplier_gln' => 'SUPPLIER_GLN',
        'suppliers.supplier_gln' => 'SUPPLIER_GLN',
        'Remise' => 'SUPPLIER_REMISE',
        'Supplier.Remise' => 'SUPPLIER_REMISE',
        'remise' => 'SUPPLIER_REMISE',
        'supplier.remise' => 'SUPPLIER_REMISE',
        'SupplierTableMap::COL_SUPPLIER_REMISE' => 'SUPPLIER_REMISE',
        'COL_SUPPLIER_REMISE' => 'SUPPLIER_REMISE',
        'supplier_remise' => 'SUPPLIER_REMISE',
        'suppliers.supplier_remise' => 'SUPPLIER_REMISE',
        'Notva' => 'SUPPLIER_NOTVA',
        'Supplier.Notva' => 'SUPPLIER_NOTVA',
        'notva' => 'SUPPLIER_NOTVA',
        'supplier.notva' => 'SUPPLIER_NOTVA',
        'SupplierTableMap::COL_SUPPLIER_NOTVA' => 'SUPPLIER_NOTVA',
        'COL_SUPPLIER_NOTVA' => 'SUPPLIER_NOTVA',
        'supplier_notva' => 'SUPPLIER_NOTVA',
        'suppliers.supplier_notva' => 'SUPPLIER_NOTVA',
        'OnOrder' => 'SUPPLIER_ON_ORDER',
        'Supplier.OnOrder' => 'SUPPLIER_ON_ORDER',
        'onOrder' => 'SUPPLIER_ON_ORDER',
        'supplier.onOrder' => 'SUPPLIER_ON_ORDER',
        'SupplierTableMap::COL_SUPPLIER_ON_ORDER' => 'SUPPLIER_ON_ORDER',
        'COL_SUPPLIER_ON_ORDER' => 'SUPPLIER_ON_ORDER',
        'supplier_on_order' => 'SUPPLIER_ON_ORDER',
        'suppliers.supplier_on_order' => 'SUPPLIER_ON_ORDER',
        'Insert' => 'SUPPLIER_INSERT',
        'Supplier.Insert' => 'SUPPLIER_INSERT',
        'insert' => 'SUPPLIER_INSERT',
        'supplier.insert' => 'SUPPLIER_INSERT',
        'SupplierTableMap::COL_SUPPLIER_INSERT' => 'SUPPLIER_INSERT',
        'COL_SUPPLIER_INSERT' => 'SUPPLIER_INSERT',
        'supplier_insert' => 'SUPPLIER_INSERT',
        'suppliers.supplier_insert' => 'SUPPLIER_INSERT',
        'Update' => 'SUPPLIER_UPDATE',
        'Supplier.Update' => 'SUPPLIER_UPDATE',
        'update' => 'SUPPLIER_UPDATE',
        'supplier.update' => 'SUPPLIER_UPDATE',
        'SupplierTableMap::COL_SUPPLIER_UPDATE' => 'SUPPLIER_UPDATE',
        'COL_SUPPLIER_UPDATE' => 'SUPPLIER_UPDATE',
        'supplier_update' => 'SUPPLIER_UPDATE',
        'suppliers.supplier_update' => 'SUPPLIER_UPDATE',
        'CreatedAt' => 'SUPPLIER_CREATED',
        'Supplier.CreatedAt' => 'SUPPLIER_CREATED',
        'createdAt' => 'SUPPLIER_CREATED',
        'supplier.createdAt' => 'SUPPLIER_CREATED',
        'SupplierTableMap::COL_SUPPLIER_CREATED' => 'SUPPLIER_CREATED',
        'COL_SUPPLIER_CREATED' => 'SUPPLIER_CREATED',
        'supplier_created' => 'SUPPLIER_CREATED',
        'suppliers.supplier_created' => 'SUPPLIER_CREATED',
        'UpdatedAt' => 'SUPPLIER_UPDATED',
        'Supplier.UpdatedAt' => 'SUPPLIER_UPDATED',
        'updatedAt' => 'SUPPLIER_UPDATED',
        'supplier.updatedAt' => 'SUPPLIER_UPDATED',
        'SupplierTableMap::COL_SUPPLIER_UPDATED' => 'SUPPLIER_UPDATED',
        'COL_SUPPLIER_UPDATED' => 'SUPPLIER_UPDATED',
        'supplier_updated' => 'SUPPLIER_UPDATED',
        'suppliers.supplier_updated' => 'SUPPLIER_UPDATED',
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
        $this->setName('suppliers');
        $this->setPhpName('Supplier');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Supplier');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('supplier_id', 'Id', 'INTEGER', true, 10, null);
        $this->addColumn('site_id', 'SiteId', 'TINYINT', false, 3, 1);
        $this->addColumn('supplier_name', 'Name', 'VARCHAR', false, 256, null);
        $this->addColumn('supplier_gln', 'Gln', 'BIGINT', false, null, null);
        $this->addColumn('supplier_remise', 'Remise', 'INTEGER', false, 10, null);
        $this->addColumn('supplier_notva', 'Notva', 'BOOLEAN', false, 1, null);
        $this->addColumn('supplier_on_order', 'OnOrder', 'BOOLEAN', false, 1, null);
        $this->addColumn('supplier_insert', 'Insert', 'TIMESTAMP', false, null, null);
        $this->addColumn('supplier_update', 'Update', 'TIMESTAMP', false, null, null);
        $this->addColumn('supplier_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('supplier_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
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
            'timestampable' => ['create_column' => 'supplier_created', 'update_column' => 'supplier_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? SupplierTableMap::CLASS_DEFAULT : SupplierTableMap::OM_CLASS;
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
     * @return array (Supplier object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = SupplierTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = SupplierTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + SupplierTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SupplierTableMap::OM_CLASS;
            /** @var Supplier $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            SupplierTableMap::addInstanceToPool($obj, $key);
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
            $key = SupplierTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = SupplierTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Supplier $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                SupplierTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(SupplierTableMap::COL_SUPPLIER_ID);
            $criteria->addSelectColumn(SupplierTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(SupplierTableMap::COL_SUPPLIER_NAME);
            $criteria->addSelectColumn(SupplierTableMap::COL_SUPPLIER_GLN);
            $criteria->addSelectColumn(SupplierTableMap::COL_SUPPLIER_REMISE);
            $criteria->addSelectColumn(SupplierTableMap::COL_SUPPLIER_NOTVA);
            $criteria->addSelectColumn(SupplierTableMap::COL_SUPPLIER_ON_ORDER);
            $criteria->addSelectColumn(SupplierTableMap::COL_SUPPLIER_INSERT);
            $criteria->addSelectColumn(SupplierTableMap::COL_SUPPLIER_UPDATE);
            $criteria->addSelectColumn(SupplierTableMap::COL_SUPPLIER_CREATED);
            $criteria->addSelectColumn(SupplierTableMap::COL_SUPPLIER_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.supplier_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.supplier_name');
            $criteria->addSelectColumn($alias . '.supplier_gln');
            $criteria->addSelectColumn($alias . '.supplier_remise');
            $criteria->addSelectColumn($alias . '.supplier_notva');
            $criteria->addSelectColumn($alias . '.supplier_on_order');
            $criteria->addSelectColumn($alias . '.supplier_insert');
            $criteria->addSelectColumn($alias . '.supplier_update');
            $criteria->addSelectColumn($alias . '.supplier_created');
            $criteria->addSelectColumn($alias . '.supplier_updated');
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
            $criteria->removeSelectColumn(SupplierTableMap::COL_SUPPLIER_ID);
            $criteria->removeSelectColumn(SupplierTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(SupplierTableMap::COL_SUPPLIER_NAME);
            $criteria->removeSelectColumn(SupplierTableMap::COL_SUPPLIER_GLN);
            $criteria->removeSelectColumn(SupplierTableMap::COL_SUPPLIER_REMISE);
            $criteria->removeSelectColumn(SupplierTableMap::COL_SUPPLIER_NOTVA);
            $criteria->removeSelectColumn(SupplierTableMap::COL_SUPPLIER_ON_ORDER);
            $criteria->removeSelectColumn(SupplierTableMap::COL_SUPPLIER_INSERT);
            $criteria->removeSelectColumn(SupplierTableMap::COL_SUPPLIER_UPDATE);
            $criteria->removeSelectColumn(SupplierTableMap::COL_SUPPLIER_CREATED);
            $criteria->removeSelectColumn(SupplierTableMap::COL_SUPPLIER_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.supplier_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.supplier_name');
            $criteria->removeSelectColumn($alias . '.supplier_gln');
            $criteria->removeSelectColumn($alias . '.supplier_remise');
            $criteria->removeSelectColumn($alias . '.supplier_notva');
            $criteria->removeSelectColumn($alias . '.supplier_on_order');
            $criteria->removeSelectColumn($alias . '.supplier_insert');
            $criteria->removeSelectColumn($alias . '.supplier_update');
            $criteria->removeSelectColumn($alias . '.supplier_created');
            $criteria->removeSelectColumn($alias . '.supplier_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(SupplierTableMap::DATABASE_NAME)->getTable(SupplierTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Supplier or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Supplier object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(SupplierTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Supplier) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(SupplierTableMap::DATABASE_NAME);
            $criteria->add(SupplierTableMap::COL_SUPPLIER_ID, (array) $values, Criteria::IN);
        }

        $query = SupplierQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            SupplierTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                SupplierTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the suppliers table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return SupplierQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Supplier or Criteria object.
     *
     * @param mixed $criteria Criteria or Supplier object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SupplierTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Supplier object
        }

        if ($criteria->containsKey(SupplierTableMap::COL_SUPPLIER_ID) && $criteria->keyContainsValue(SupplierTableMap::COL_SUPPLIER_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.SupplierTableMap::COL_SUPPLIER_ID.')');
        }


        // Set the correct dbName
        $query = SupplierQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
