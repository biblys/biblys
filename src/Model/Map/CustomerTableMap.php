<?php

namespace Model\Map;

use Model\Customer;
use Model\CustomerQuery;
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
 * This class defines the structure of the 'customers' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class CustomerTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.CustomerTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'customers';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Customer';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Customer';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Customer';

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
     * the column name for the customer_id field
     */
    public const COL_CUSTOMER_ID = 'customers.customer_id';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'customers.site_id';

    /**
     * the column name for the axys_user_id field
     */
    public const COL_AXYS_USER_ID = 'customers.axys_user_id';

    /**
     * the column name for the customer_type field
     */
    public const COL_CUSTOMER_TYPE = 'customers.customer_type';

    /**
     * the column name for the customer_first_name field
     */
    public const COL_CUSTOMER_FIRST_NAME = 'customers.customer_first_name';

    /**
     * the column name for the customer_last_name field
     */
    public const COL_CUSTOMER_LAST_NAME = 'customers.customer_last_name';

    /**
     * the column name for the customer_email field
     */
    public const COL_CUSTOMER_EMAIL = 'customers.customer_email';

    /**
     * the column name for the customer_phone field
     */
    public const COL_CUSTOMER_PHONE = 'customers.customer_phone';

    /**
     * the column name for the country_id field
     */
    public const COL_COUNTRY_ID = 'customers.country_id';

    /**
     * the column name for the customer_privatization field
     */
    public const COL_CUSTOMER_PRIVATIZATION = 'customers.customer_privatization';

    /**
     * the column name for the customer_insert field
     */
    public const COL_CUSTOMER_INSERT = 'customers.customer_insert';

    /**
     * the column name for the customer_update field
     */
    public const COL_CUSTOMER_UPDATE = 'customers.customer_update';

    /**
     * the column name for the customer_created field
     */
    public const COL_CUSTOMER_CREATED = 'customers.customer_created';

    /**
     * the column name for the customer_updated field
     */
    public const COL_CUSTOMER_UPDATED = 'customers.customer_updated';

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
        self::TYPE_PHPNAME       => ['Id', 'SiteId', 'AxysUserId', 'Type', 'FirstName', 'LastName', 'Email', 'Phone', 'CountryId', 'Privatization', 'Insert', 'Update', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'siteId', 'axysUserId', 'type', 'firstName', 'lastName', 'email', 'phone', 'countryId', 'privatization', 'insert', 'update', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [CustomerTableMap::COL_CUSTOMER_ID, CustomerTableMap::COL_SITE_ID, CustomerTableMap::COL_AXYS_USER_ID, CustomerTableMap::COL_CUSTOMER_TYPE, CustomerTableMap::COL_CUSTOMER_FIRST_NAME, CustomerTableMap::COL_CUSTOMER_LAST_NAME, CustomerTableMap::COL_CUSTOMER_EMAIL, CustomerTableMap::COL_CUSTOMER_PHONE, CustomerTableMap::COL_COUNTRY_ID, CustomerTableMap::COL_CUSTOMER_PRIVATIZATION, CustomerTableMap::COL_CUSTOMER_INSERT, CustomerTableMap::COL_CUSTOMER_UPDATE, CustomerTableMap::COL_CUSTOMER_CREATED, CustomerTableMap::COL_CUSTOMER_UPDATED, ],
        self::TYPE_FIELDNAME     => ['customer_id', 'site_id', 'axys_user_id', 'customer_type', 'customer_first_name', 'customer_last_name', 'customer_email', 'customer_phone', 'country_id', 'customer_privatization', 'customer_insert', 'customer_update', 'customer_created', 'customer_updated', ],
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'SiteId' => 1, 'AxysUserId' => 2, 'Type' => 3, 'FirstName' => 4, 'LastName' => 5, 'Email' => 6, 'Phone' => 7, 'CountryId' => 8, 'Privatization' => 9, 'Insert' => 10, 'Update' => 11, 'CreatedAt' => 12, 'UpdatedAt' => 13, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'siteId' => 1, 'axysUserId' => 2, 'type' => 3, 'firstName' => 4, 'lastName' => 5, 'email' => 6, 'phone' => 7, 'countryId' => 8, 'privatization' => 9, 'insert' => 10, 'update' => 11, 'createdAt' => 12, 'updatedAt' => 13, ],
        self::TYPE_COLNAME       => [CustomerTableMap::COL_CUSTOMER_ID => 0, CustomerTableMap::COL_SITE_ID => 1, CustomerTableMap::COL_AXYS_USER_ID => 2, CustomerTableMap::COL_CUSTOMER_TYPE => 3, CustomerTableMap::COL_CUSTOMER_FIRST_NAME => 4, CustomerTableMap::COL_CUSTOMER_LAST_NAME => 5, CustomerTableMap::COL_CUSTOMER_EMAIL => 6, CustomerTableMap::COL_CUSTOMER_PHONE => 7, CustomerTableMap::COL_COUNTRY_ID => 8, CustomerTableMap::COL_CUSTOMER_PRIVATIZATION => 9, CustomerTableMap::COL_CUSTOMER_INSERT => 10, CustomerTableMap::COL_CUSTOMER_UPDATE => 11, CustomerTableMap::COL_CUSTOMER_CREATED => 12, CustomerTableMap::COL_CUSTOMER_UPDATED => 13, ],
        self::TYPE_FIELDNAME     => ['customer_id' => 0, 'site_id' => 1, 'axys_user_id' => 2, 'customer_type' => 3, 'customer_first_name' => 4, 'customer_last_name' => 5, 'customer_email' => 6, 'customer_phone' => 7, 'country_id' => 8, 'customer_privatization' => 9, 'customer_insert' => 10, 'customer_update' => 11, 'customer_created' => 12, 'customer_updated' => 13, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'CUSTOMER_ID',
        'Customer.Id' => 'CUSTOMER_ID',
        'id' => 'CUSTOMER_ID',
        'customer.id' => 'CUSTOMER_ID',
        'CustomerTableMap::COL_CUSTOMER_ID' => 'CUSTOMER_ID',
        'COL_CUSTOMER_ID' => 'CUSTOMER_ID',
        'customer_id' => 'CUSTOMER_ID',
        'customers.customer_id' => 'CUSTOMER_ID',
        'SiteId' => 'SITE_ID',
        'Customer.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'customer.siteId' => 'SITE_ID',
        'CustomerTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'customers.site_id' => 'SITE_ID',
        'AxysUserId' => 'AXYS_USER_ID',
        'Customer.AxysUserId' => 'AXYS_USER_ID',
        'axysUserId' => 'AXYS_USER_ID',
        'customer.axysUserId' => 'AXYS_USER_ID',
        'CustomerTableMap::COL_AXYS_USER_ID' => 'AXYS_USER_ID',
        'COL_AXYS_USER_ID' => 'AXYS_USER_ID',
        'axys_user_id' => 'AXYS_USER_ID',
        'customers.axys_user_id' => 'AXYS_USER_ID',
        'Type' => 'CUSTOMER_TYPE',
        'Customer.Type' => 'CUSTOMER_TYPE',
        'type' => 'CUSTOMER_TYPE',
        'customer.type' => 'CUSTOMER_TYPE',
        'CustomerTableMap::COL_CUSTOMER_TYPE' => 'CUSTOMER_TYPE',
        'COL_CUSTOMER_TYPE' => 'CUSTOMER_TYPE',
        'customer_type' => 'CUSTOMER_TYPE',
        'customers.customer_type' => 'CUSTOMER_TYPE',
        'FirstName' => 'CUSTOMER_FIRST_NAME',
        'Customer.FirstName' => 'CUSTOMER_FIRST_NAME',
        'firstName' => 'CUSTOMER_FIRST_NAME',
        'customer.firstName' => 'CUSTOMER_FIRST_NAME',
        'CustomerTableMap::COL_CUSTOMER_FIRST_NAME' => 'CUSTOMER_FIRST_NAME',
        'COL_CUSTOMER_FIRST_NAME' => 'CUSTOMER_FIRST_NAME',
        'customer_first_name' => 'CUSTOMER_FIRST_NAME',
        'customers.customer_first_name' => 'CUSTOMER_FIRST_NAME',
        'LastName' => 'CUSTOMER_LAST_NAME',
        'Customer.LastName' => 'CUSTOMER_LAST_NAME',
        'lastName' => 'CUSTOMER_LAST_NAME',
        'customer.lastName' => 'CUSTOMER_LAST_NAME',
        'CustomerTableMap::COL_CUSTOMER_LAST_NAME' => 'CUSTOMER_LAST_NAME',
        'COL_CUSTOMER_LAST_NAME' => 'CUSTOMER_LAST_NAME',
        'customer_last_name' => 'CUSTOMER_LAST_NAME',
        'customers.customer_last_name' => 'CUSTOMER_LAST_NAME',
        'Email' => 'CUSTOMER_EMAIL',
        'Customer.Email' => 'CUSTOMER_EMAIL',
        'email' => 'CUSTOMER_EMAIL',
        'customer.email' => 'CUSTOMER_EMAIL',
        'CustomerTableMap::COL_CUSTOMER_EMAIL' => 'CUSTOMER_EMAIL',
        'COL_CUSTOMER_EMAIL' => 'CUSTOMER_EMAIL',
        'customer_email' => 'CUSTOMER_EMAIL',
        'customers.customer_email' => 'CUSTOMER_EMAIL',
        'Phone' => 'CUSTOMER_PHONE',
        'Customer.Phone' => 'CUSTOMER_PHONE',
        'phone' => 'CUSTOMER_PHONE',
        'customer.phone' => 'CUSTOMER_PHONE',
        'CustomerTableMap::COL_CUSTOMER_PHONE' => 'CUSTOMER_PHONE',
        'COL_CUSTOMER_PHONE' => 'CUSTOMER_PHONE',
        'customer_phone' => 'CUSTOMER_PHONE',
        'customers.customer_phone' => 'CUSTOMER_PHONE',
        'CountryId' => 'COUNTRY_ID',
        'Customer.CountryId' => 'COUNTRY_ID',
        'countryId' => 'COUNTRY_ID',
        'customer.countryId' => 'COUNTRY_ID',
        'CustomerTableMap::COL_COUNTRY_ID' => 'COUNTRY_ID',
        'COL_COUNTRY_ID' => 'COUNTRY_ID',
        'country_id' => 'COUNTRY_ID',
        'customers.country_id' => 'COUNTRY_ID',
        'Privatization' => 'CUSTOMER_PRIVATIZATION',
        'Customer.Privatization' => 'CUSTOMER_PRIVATIZATION',
        'privatization' => 'CUSTOMER_PRIVATIZATION',
        'customer.privatization' => 'CUSTOMER_PRIVATIZATION',
        'CustomerTableMap::COL_CUSTOMER_PRIVATIZATION' => 'CUSTOMER_PRIVATIZATION',
        'COL_CUSTOMER_PRIVATIZATION' => 'CUSTOMER_PRIVATIZATION',
        'customer_privatization' => 'CUSTOMER_PRIVATIZATION',
        'customers.customer_privatization' => 'CUSTOMER_PRIVATIZATION',
        'Insert' => 'CUSTOMER_INSERT',
        'Customer.Insert' => 'CUSTOMER_INSERT',
        'insert' => 'CUSTOMER_INSERT',
        'customer.insert' => 'CUSTOMER_INSERT',
        'CustomerTableMap::COL_CUSTOMER_INSERT' => 'CUSTOMER_INSERT',
        'COL_CUSTOMER_INSERT' => 'CUSTOMER_INSERT',
        'customer_insert' => 'CUSTOMER_INSERT',
        'customers.customer_insert' => 'CUSTOMER_INSERT',
        'Update' => 'CUSTOMER_UPDATE',
        'Customer.Update' => 'CUSTOMER_UPDATE',
        'update' => 'CUSTOMER_UPDATE',
        'customer.update' => 'CUSTOMER_UPDATE',
        'CustomerTableMap::COL_CUSTOMER_UPDATE' => 'CUSTOMER_UPDATE',
        'COL_CUSTOMER_UPDATE' => 'CUSTOMER_UPDATE',
        'customer_update' => 'CUSTOMER_UPDATE',
        'customers.customer_update' => 'CUSTOMER_UPDATE',
        'CreatedAt' => 'CUSTOMER_CREATED',
        'Customer.CreatedAt' => 'CUSTOMER_CREATED',
        'createdAt' => 'CUSTOMER_CREATED',
        'customer.createdAt' => 'CUSTOMER_CREATED',
        'CustomerTableMap::COL_CUSTOMER_CREATED' => 'CUSTOMER_CREATED',
        'COL_CUSTOMER_CREATED' => 'CUSTOMER_CREATED',
        'customer_created' => 'CUSTOMER_CREATED',
        'customers.customer_created' => 'CUSTOMER_CREATED',
        'UpdatedAt' => 'CUSTOMER_UPDATED',
        'Customer.UpdatedAt' => 'CUSTOMER_UPDATED',
        'updatedAt' => 'CUSTOMER_UPDATED',
        'customer.updatedAt' => 'CUSTOMER_UPDATED',
        'CustomerTableMap::COL_CUSTOMER_UPDATED' => 'CUSTOMER_UPDATED',
        'COL_CUSTOMER_UPDATED' => 'CUSTOMER_UPDATED',
        'customer_updated' => 'CUSTOMER_UPDATED',
        'customers.customer_updated' => 'CUSTOMER_UPDATED',
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
        $this->setName('customers');
        $this->setPhpName('Customer');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Customer');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('customer_id', 'Id', 'INTEGER', true, 10, null);
        $this->addColumn('site_id', 'SiteId', 'INTEGER', false, 10, null);
        $this->addColumn('axys_user_id', 'AxysUserId', 'INTEGER', false, 10, null);
        $this->addColumn('customer_type', 'Type', 'VARCHAR', false, 16, 'Particulier');
        $this->addColumn('customer_first_name', 'FirstName', 'VARCHAR', false, 64, null);
        $this->addColumn('customer_last_name', 'LastName', 'VARCHAR', false, 64, null);
        $this->addColumn('customer_email', 'Email', 'VARCHAR', false, 128, null);
        $this->addColumn('customer_phone', 'Phone', 'VARCHAR', false, 16, null);
        $this->addColumn('country_id', 'CountryId', 'INTEGER', false, 10, null);
        $this->addColumn('customer_privatization', 'Privatization', 'DATE', false, null, null);
        $this->addColumn('customer_insert', 'Insert', 'TIMESTAMP', false, null, null);
        $this->addColumn('customer_update', 'Update', 'TIMESTAMP', false, null, null);
        $this->addColumn('customer_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('customer_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
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
            'timestampable' => ['create_column' => 'customer_created', 'update_column' => 'customer_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? CustomerTableMap::CLASS_DEFAULT : CustomerTableMap::OM_CLASS;
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
     * @return array (Customer object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = CustomerTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = CustomerTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + CustomerTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = CustomerTableMap::OM_CLASS;
            /** @var Customer $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            CustomerTableMap::addInstanceToPool($obj, $key);
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
            $key = CustomerTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = CustomerTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Customer $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                CustomerTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(CustomerTableMap::COL_CUSTOMER_ID);
            $criteria->addSelectColumn(CustomerTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(CustomerTableMap::COL_AXYS_USER_ID);
            $criteria->addSelectColumn(CustomerTableMap::COL_CUSTOMER_TYPE);
            $criteria->addSelectColumn(CustomerTableMap::COL_CUSTOMER_FIRST_NAME);
            $criteria->addSelectColumn(CustomerTableMap::COL_CUSTOMER_LAST_NAME);
            $criteria->addSelectColumn(CustomerTableMap::COL_CUSTOMER_EMAIL);
            $criteria->addSelectColumn(CustomerTableMap::COL_CUSTOMER_PHONE);
            $criteria->addSelectColumn(CustomerTableMap::COL_COUNTRY_ID);
            $criteria->addSelectColumn(CustomerTableMap::COL_CUSTOMER_PRIVATIZATION);
            $criteria->addSelectColumn(CustomerTableMap::COL_CUSTOMER_INSERT);
            $criteria->addSelectColumn(CustomerTableMap::COL_CUSTOMER_UPDATE);
            $criteria->addSelectColumn(CustomerTableMap::COL_CUSTOMER_CREATED);
            $criteria->addSelectColumn(CustomerTableMap::COL_CUSTOMER_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.customer_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.axys_user_id');
            $criteria->addSelectColumn($alias . '.customer_type');
            $criteria->addSelectColumn($alias . '.customer_first_name');
            $criteria->addSelectColumn($alias . '.customer_last_name');
            $criteria->addSelectColumn($alias . '.customer_email');
            $criteria->addSelectColumn($alias . '.customer_phone');
            $criteria->addSelectColumn($alias . '.country_id');
            $criteria->addSelectColumn($alias . '.customer_privatization');
            $criteria->addSelectColumn($alias . '.customer_insert');
            $criteria->addSelectColumn($alias . '.customer_update');
            $criteria->addSelectColumn($alias . '.customer_created');
            $criteria->addSelectColumn($alias . '.customer_updated');
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
            $criteria->removeSelectColumn(CustomerTableMap::COL_CUSTOMER_ID);
            $criteria->removeSelectColumn(CustomerTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(CustomerTableMap::COL_AXYS_USER_ID);
            $criteria->removeSelectColumn(CustomerTableMap::COL_CUSTOMER_TYPE);
            $criteria->removeSelectColumn(CustomerTableMap::COL_CUSTOMER_FIRST_NAME);
            $criteria->removeSelectColumn(CustomerTableMap::COL_CUSTOMER_LAST_NAME);
            $criteria->removeSelectColumn(CustomerTableMap::COL_CUSTOMER_EMAIL);
            $criteria->removeSelectColumn(CustomerTableMap::COL_CUSTOMER_PHONE);
            $criteria->removeSelectColumn(CustomerTableMap::COL_COUNTRY_ID);
            $criteria->removeSelectColumn(CustomerTableMap::COL_CUSTOMER_PRIVATIZATION);
            $criteria->removeSelectColumn(CustomerTableMap::COL_CUSTOMER_INSERT);
            $criteria->removeSelectColumn(CustomerTableMap::COL_CUSTOMER_UPDATE);
            $criteria->removeSelectColumn(CustomerTableMap::COL_CUSTOMER_CREATED);
            $criteria->removeSelectColumn(CustomerTableMap::COL_CUSTOMER_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.customer_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.axys_user_id');
            $criteria->removeSelectColumn($alias . '.customer_type');
            $criteria->removeSelectColumn($alias . '.customer_first_name');
            $criteria->removeSelectColumn($alias . '.customer_last_name');
            $criteria->removeSelectColumn($alias . '.customer_email');
            $criteria->removeSelectColumn($alias . '.customer_phone');
            $criteria->removeSelectColumn($alias . '.country_id');
            $criteria->removeSelectColumn($alias . '.customer_privatization');
            $criteria->removeSelectColumn($alias . '.customer_insert');
            $criteria->removeSelectColumn($alias . '.customer_update');
            $criteria->removeSelectColumn($alias . '.customer_created');
            $criteria->removeSelectColumn($alias . '.customer_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(CustomerTableMap::DATABASE_NAME)->getTable(CustomerTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Customer or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Customer object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(CustomerTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Customer) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(CustomerTableMap::DATABASE_NAME);
            $criteria->add(CustomerTableMap::COL_CUSTOMER_ID, (array) $values, Criteria::IN);
        }

        $query = CustomerQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            CustomerTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                CustomerTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the customers table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return CustomerQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Customer or Criteria object.
     *
     * @param mixed $criteria Criteria or Customer object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CustomerTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Customer object
        }

        if ($criteria->containsKey(CustomerTableMap::COL_CUSTOMER_ID) && $criteria->keyContainsValue(CustomerTableMap::COL_CUSTOMER_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.CustomerTableMap::COL_CUSTOMER_ID.')');
        }


        // Set the correct dbName
        $query = CustomerQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
