<?php

namespace Model\Map;

use Model\Payment;
use Model\PaymentQuery;
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
 * This class defines the structure of the 'payments' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class PaymentTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Model.Map.PaymentTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'payments';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\Payment';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.Payment';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 10;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 10;

    /**
     * the column name for the payment_id field
     */
    const COL_PAYMENT_ID = 'payments.payment_id';

    /**
     * the column name for the site_id field
     */
    const COL_SITE_ID = 'payments.site_id';

    /**
     * the column name for the order_id field
     */
    const COL_ORDER_ID = 'payments.order_id';

    /**
     * the column name for the payment_amount field
     */
    const COL_PAYMENT_AMOUNT = 'payments.payment_amount';

    /**
     * the column name for the payment_mode field
     */
    const COL_PAYMENT_MODE = 'payments.payment_mode';

    /**
     * the column name for the payment_provider_id field
     */
    const COL_PAYMENT_PROVIDER_ID = 'payments.payment_provider_id';

    /**
     * the column name for the payment_url field
     */
    const COL_PAYMENT_URL = 'payments.payment_url';

    /**
     * the column name for the payment_created field
     */
    const COL_PAYMENT_CREATED = 'payments.payment_created';

    /**
     * the column name for the payment_executed field
     */
    const COL_PAYMENT_EXECUTED = 'payments.payment_executed';

    /**
     * the column name for the payment_updated field
     */
    const COL_PAYMENT_UPDATED = 'payments.payment_updated';

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
        self::TYPE_PHPNAME       => array('Id', 'SiteId', 'OrderId', 'Amount', 'Mode', 'ProviderId', 'Url', 'CreatedAt', 'Executed', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'siteId', 'orderId', 'amount', 'mode', 'providerId', 'url', 'createdAt', 'executed', 'updatedAt', ),
        self::TYPE_COLNAME       => array(PaymentTableMap::COL_PAYMENT_ID, PaymentTableMap::COL_SITE_ID, PaymentTableMap::COL_ORDER_ID, PaymentTableMap::COL_PAYMENT_AMOUNT, PaymentTableMap::COL_PAYMENT_MODE, PaymentTableMap::COL_PAYMENT_PROVIDER_ID, PaymentTableMap::COL_PAYMENT_URL, PaymentTableMap::COL_PAYMENT_CREATED, PaymentTableMap::COL_PAYMENT_EXECUTED, PaymentTableMap::COL_PAYMENT_UPDATED, ),
        self::TYPE_FIELDNAME     => array('payment_id', 'site_id', 'order_id', 'payment_amount', 'payment_mode', 'payment_provider_id', 'payment_url', 'payment_created', 'payment_executed', 'payment_updated', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'SiteId' => 1, 'OrderId' => 2, 'Amount' => 3, 'Mode' => 4, 'ProviderId' => 5, 'Url' => 6, 'CreatedAt' => 7, 'Executed' => 8, 'UpdatedAt' => 9, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'siteId' => 1, 'orderId' => 2, 'amount' => 3, 'mode' => 4, 'providerId' => 5, 'url' => 6, 'createdAt' => 7, 'executed' => 8, 'updatedAt' => 9, ),
        self::TYPE_COLNAME       => array(PaymentTableMap::COL_PAYMENT_ID => 0, PaymentTableMap::COL_SITE_ID => 1, PaymentTableMap::COL_ORDER_ID => 2, PaymentTableMap::COL_PAYMENT_AMOUNT => 3, PaymentTableMap::COL_PAYMENT_MODE => 4, PaymentTableMap::COL_PAYMENT_PROVIDER_ID => 5, PaymentTableMap::COL_PAYMENT_URL => 6, PaymentTableMap::COL_PAYMENT_CREATED => 7, PaymentTableMap::COL_PAYMENT_EXECUTED => 8, PaymentTableMap::COL_PAYMENT_UPDATED => 9, ),
        self::TYPE_FIELDNAME     => array('payment_id' => 0, 'site_id' => 1, 'order_id' => 2, 'payment_amount' => 3, 'payment_mode' => 4, 'payment_provider_id' => 5, 'payment_url' => 6, 'payment_created' => 7, 'payment_executed' => 8, 'payment_updated' => 9, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
    );

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var string[]
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'PAYMENT_ID',
        'Payment.Id' => 'PAYMENT_ID',
        'id' => 'PAYMENT_ID',
        'payment.id' => 'PAYMENT_ID',
        'PaymentTableMap::COL_PAYMENT_ID' => 'PAYMENT_ID',
        'COL_PAYMENT_ID' => 'PAYMENT_ID',
        'payment_id' => 'PAYMENT_ID',
        'payments.payment_id' => 'PAYMENT_ID',
        'SiteId' => 'SITE_ID',
        'Payment.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'payment.siteId' => 'SITE_ID',
        'PaymentTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'payments.site_id' => 'SITE_ID',
        'OrderId' => 'ORDER_ID',
        'Payment.OrderId' => 'ORDER_ID',
        'orderId' => 'ORDER_ID',
        'payment.orderId' => 'ORDER_ID',
        'PaymentTableMap::COL_ORDER_ID' => 'ORDER_ID',
        'COL_ORDER_ID' => 'ORDER_ID',
        'order_id' => 'ORDER_ID',
        'payments.order_id' => 'ORDER_ID',
        'Amount' => 'PAYMENT_AMOUNT',
        'Payment.Amount' => 'PAYMENT_AMOUNT',
        'amount' => 'PAYMENT_AMOUNT',
        'payment.amount' => 'PAYMENT_AMOUNT',
        'PaymentTableMap::COL_PAYMENT_AMOUNT' => 'PAYMENT_AMOUNT',
        'COL_PAYMENT_AMOUNT' => 'PAYMENT_AMOUNT',
        'payment_amount' => 'PAYMENT_AMOUNT',
        'payments.payment_amount' => 'PAYMENT_AMOUNT',
        'Mode' => 'PAYMENT_MODE',
        'Payment.Mode' => 'PAYMENT_MODE',
        'mode' => 'PAYMENT_MODE',
        'payment.mode' => 'PAYMENT_MODE',
        'PaymentTableMap::COL_PAYMENT_MODE' => 'PAYMENT_MODE',
        'COL_PAYMENT_MODE' => 'PAYMENT_MODE',
        'payment_mode' => 'PAYMENT_MODE',
        'payments.payment_mode' => 'PAYMENT_MODE',
        'ProviderId' => 'PAYMENT_PROVIDER_ID',
        'Payment.ProviderId' => 'PAYMENT_PROVIDER_ID',
        'providerId' => 'PAYMENT_PROVIDER_ID',
        'payment.providerId' => 'PAYMENT_PROVIDER_ID',
        'PaymentTableMap::COL_PAYMENT_PROVIDER_ID' => 'PAYMENT_PROVIDER_ID',
        'COL_PAYMENT_PROVIDER_ID' => 'PAYMENT_PROVIDER_ID',
        'payment_provider_id' => 'PAYMENT_PROVIDER_ID',
        'payments.payment_provider_id' => 'PAYMENT_PROVIDER_ID',
        'Url' => 'PAYMENT_URL',
        'Payment.Url' => 'PAYMENT_URL',
        'url' => 'PAYMENT_URL',
        'payment.url' => 'PAYMENT_URL',
        'PaymentTableMap::COL_PAYMENT_URL' => 'PAYMENT_URL',
        'COL_PAYMENT_URL' => 'PAYMENT_URL',
        'payment_url' => 'PAYMENT_URL',
        'payments.payment_url' => 'PAYMENT_URL',
        'CreatedAt' => 'PAYMENT_CREATED',
        'Payment.CreatedAt' => 'PAYMENT_CREATED',
        'createdAt' => 'PAYMENT_CREATED',
        'payment.createdAt' => 'PAYMENT_CREATED',
        'PaymentTableMap::COL_PAYMENT_CREATED' => 'PAYMENT_CREATED',
        'COL_PAYMENT_CREATED' => 'PAYMENT_CREATED',
        'payment_created' => 'PAYMENT_CREATED',
        'payments.payment_created' => 'PAYMENT_CREATED',
        'Executed' => 'PAYMENT_EXECUTED',
        'Payment.Executed' => 'PAYMENT_EXECUTED',
        'executed' => 'PAYMENT_EXECUTED',
        'payment.executed' => 'PAYMENT_EXECUTED',
        'PaymentTableMap::COL_PAYMENT_EXECUTED' => 'PAYMENT_EXECUTED',
        'COL_PAYMENT_EXECUTED' => 'PAYMENT_EXECUTED',
        'payment_executed' => 'PAYMENT_EXECUTED',
        'payments.payment_executed' => 'PAYMENT_EXECUTED',
        'UpdatedAt' => 'PAYMENT_UPDATED',
        'Payment.UpdatedAt' => 'PAYMENT_UPDATED',
        'updatedAt' => 'PAYMENT_UPDATED',
        'payment.updatedAt' => 'PAYMENT_UPDATED',
        'PaymentTableMap::COL_PAYMENT_UPDATED' => 'PAYMENT_UPDATED',
        'COL_PAYMENT_UPDATED' => 'PAYMENT_UPDATED',
        'payment_updated' => 'PAYMENT_UPDATED',
        'payments.payment_updated' => 'PAYMENT_UPDATED',
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
        $this->setName('payments');
        $this->setPhpName('Payment');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Payment');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('payment_id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('site_id', 'SiteId', 'INTEGER', 'sites', 'site_id', false, null, null);
        $this->addForeignKey('order_id', 'OrderId', 'INTEGER', 'orders', 'order_id', false, null, null);
        $this->addColumn('payment_amount', 'Amount', 'INTEGER', false, null, null);
        $this->addColumn('payment_mode', 'Mode', 'VARCHAR', false, 16, null);
        $this->addColumn('payment_provider_id', 'ProviderId', 'VARCHAR', false, 256, null);
        $this->addColumn('payment_url', 'Url', 'VARCHAR', false, 1024, null);
        $this->addColumn('payment_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('payment_executed', 'Executed', 'TIMESTAMP', false, null, null);
        $this->addColumn('payment_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Site', '\\Model\\Site', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':site_id',
    1 => ':site_id',
  ),
), null, null, null, false);
        $this->addRelation('Order', '\\Model\\Order', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':order_id',
    1 => ':order_id',
  ),
), null, null, null, false);
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
            'timestampable' => ['create_column' => 'payment_created', 'update_column' => 'payment_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? PaymentTableMap::CLASS_DEFAULT : PaymentTableMap::OM_CLASS;
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
     * @return array           (Payment object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = PaymentTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = PaymentTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + PaymentTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PaymentTableMap::OM_CLASS;
            /** @var Payment $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            PaymentTableMap::addInstanceToPool($obj, $key);
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
            $key = PaymentTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = PaymentTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Payment $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PaymentTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(PaymentTableMap::COL_PAYMENT_ID);
            $criteria->addSelectColumn(PaymentTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(PaymentTableMap::COL_ORDER_ID);
            $criteria->addSelectColumn(PaymentTableMap::COL_PAYMENT_AMOUNT);
            $criteria->addSelectColumn(PaymentTableMap::COL_PAYMENT_MODE);
            $criteria->addSelectColumn(PaymentTableMap::COL_PAYMENT_PROVIDER_ID);
            $criteria->addSelectColumn(PaymentTableMap::COL_PAYMENT_URL);
            $criteria->addSelectColumn(PaymentTableMap::COL_PAYMENT_CREATED);
            $criteria->addSelectColumn(PaymentTableMap::COL_PAYMENT_EXECUTED);
            $criteria->addSelectColumn(PaymentTableMap::COL_PAYMENT_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.payment_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.order_id');
            $criteria->addSelectColumn($alias . '.payment_amount');
            $criteria->addSelectColumn($alias . '.payment_mode');
            $criteria->addSelectColumn($alias . '.payment_provider_id');
            $criteria->addSelectColumn($alias . '.payment_url');
            $criteria->addSelectColumn($alias . '.payment_created');
            $criteria->addSelectColumn($alias . '.payment_executed');
            $criteria->addSelectColumn($alias . '.payment_updated');
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
            $criteria->removeSelectColumn(PaymentTableMap::COL_PAYMENT_ID);
            $criteria->removeSelectColumn(PaymentTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(PaymentTableMap::COL_ORDER_ID);
            $criteria->removeSelectColumn(PaymentTableMap::COL_PAYMENT_AMOUNT);
            $criteria->removeSelectColumn(PaymentTableMap::COL_PAYMENT_MODE);
            $criteria->removeSelectColumn(PaymentTableMap::COL_PAYMENT_PROVIDER_ID);
            $criteria->removeSelectColumn(PaymentTableMap::COL_PAYMENT_URL);
            $criteria->removeSelectColumn(PaymentTableMap::COL_PAYMENT_CREATED);
            $criteria->removeSelectColumn(PaymentTableMap::COL_PAYMENT_EXECUTED);
            $criteria->removeSelectColumn(PaymentTableMap::COL_PAYMENT_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.payment_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.order_id');
            $criteria->removeSelectColumn($alias . '.payment_amount');
            $criteria->removeSelectColumn($alias . '.payment_mode');
            $criteria->removeSelectColumn($alias . '.payment_provider_id');
            $criteria->removeSelectColumn($alias . '.payment_url');
            $criteria->removeSelectColumn($alias . '.payment_created');
            $criteria->removeSelectColumn($alias . '.payment_executed');
            $criteria->removeSelectColumn($alias . '.payment_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(PaymentTableMap::DATABASE_NAME)->getTable(PaymentTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Payment or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Payment object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(PaymentTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Payment) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PaymentTableMap::DATABASE_NAME);
            $criteria->add(PaymentTableMap::COL_PAYMENT_ID, (array) $values, Criteria::IN);
        }

        $query = PaymentQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            PaymentTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                PaymentTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the payments table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return PaymentQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Payment or Criteria object.
     *
     * @param mixed               $criteria Criteria or Payment object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PaymentTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Payment object
        }

        if ($criteria->containsKey(PaymentTableMap::COL_PAYMENT_ID) && $criteria->keyContainsValue(PaymentTableMap::COL_PAYMENT_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.PaymentTableMap::COL_PAYMENT_ID.')');
        }


        // Set the correct dbName
        $query = PaymentQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // PaymentTableMap
