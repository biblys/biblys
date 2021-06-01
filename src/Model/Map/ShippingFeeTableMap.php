<?php

namespace Model\Map;

use Model\ShippingFee;
use Model\ShippingFeeQuery;
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
 * This class defines the structure of the 'shipping' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class ShippingFeeTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Model.Map.ShippingFeeTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'shipping';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\ShippingFee';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.ShippingFee';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 16;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 16;

    /**
     * the column name for the shipping_id field
     */
    const COL_SHIPPING_ID = 'shipping.shipping_id';

    /**
     * the column name for the site_id field
     */
    const COL_SITE_ID = 'shipping.site_id';

    /**
     * the column name for the article_id field
     */
    const COL_ARTICLE_ID = 'shipping.article_id';

    /**
     * the column name for the shipping_mode field
     */
    const COL_SHIPPING_MODE = 'shipping.shipping_mode';

    /**
     * the column name for the shipping_type field
     */
    const COL_SHIPPING_TYPE = 'shipping.shipping_type';

    /**
     * the column name for the shipping_zone field
     */
    const COL_SHIPPING_ZONE = 'shipping.shipping_zone';

    /**
     * the column name for the shipping_min_weight field
     */
    const COL_SHIPPING_MIN_WEIGHT = 'shipping.shipping_min_weight';

    /**
     * the column name for the shipping_max_weight field
     */
    const COL_SHIPPING_MAX_WEIGHT = 'shipping.shipping_max_weight';

    /**
     * the column name for the shipping_max_articles field
     */
    const COL_SHIPPING_MAX_ARTICLES = 'shipping.shipping_max_articles';

    /**
     * the column name for the shipping_min_amount field
     */
    const COL_SHIPPING_MIN_AMOUNT = 'shipping.shipping_min_amount';

    /**
     * the column name for the shipping_max_amount field
     */
    const COL_SHIPPING_MAX_AMOUNT = 'shipping.shipping_max_amount';

    /**
     * the column name for the shipping_fee field
     */
    const COL_SHIPPING_FEE = 'shipping.shipping_fee';

    /**
     * the column name for the shipping_info field
     */
    const COL_SHIPPING_INFO = 'shipping.shipping_info';

    /**
     * the column name for the shipping_created field
     */
    const COL_SHIPPING_CREATED = 'shipping.shipping_created';

    /**
     * the column name for the shipping_updated field
     */
    const COL_SHIPPING_UPDATED = 'shipping.shipping_updated';

    /**
     * the column name for the shipping_deleted field
     */
    const COL_SHIPPING_DELETED = 'shipping.shipping_deleted';

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
        self::TYPE_PHPNAME       => array('Id', 'SiteId', 'ArticleId', 'Mode', 'Type', 'Zone', 'MinWeight', 'MaxWeight', 'MaxArticles', 'MinAmount', 'MaxAmount', 'Fee', 'Info', 'CreatedAt', 'UpdatedAt', 'DeletedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'siteId', 'articleId', 'mode', 'type', 'zone', 'minWeight', 'maxWeight', 'maxArticles', 'minAmount', 'maxAmount', 'fee', 'info', 'createdAt', 'updatedAt', 'deletedAt', ),
        self::TYPE_COLNAME       => array(ShippingFeeTableMap::COL_SHIPPING_ID, ShippingFeeTableMap::COL_SITE_ID, ShippingFeeTableMap::COL_ARTICLE_ID, ShippingFeeTableMap::COL_SHIPPING_MODE, ShippingFeeTableMap::COL_SHIPPING_TYPE, ShippingFeeTableMap::COL_SHIPPING_ZONE, ShippingFeeTableMap::COL_SHIPPING_MIN_WEIGHT, ShippingFeeTableMap::COL_SHIPPING_MAX_WEIGHT, ShippingFeeTableMap::COL_SHIPPING_MAX_ARTICLES, ShippingFeeTableMap::COL_SHIPPING_MIN_AMOUNT, ShippingFeeTableMap::COL_SHIPPING_MAX_AMOUNT, ShippingFeeTableMap::COL_SHIPPING_FEE, ShippingFeeTableMap::COL_SHIPPING_INFO, ShippingFeeTableMap::COL_SHIPPING_CREATED, ShippingFeeTableMap::COL_SHIPPING_UPDATED, ShippingFeeTableMap::COL_SHIPPING_DELETED, ),
        self::TYPE_FIELDNAME     => array('shipping_id', 'site_id', 'article_id', 'shipping_mode', 'shipping_type', 'shipping_zone', 'shipping_min_weight', 'shipping_max_weight', 'shipping_max_articles', 'shipping_min_amount', 'shipping_max_amount', 'shipping_fee', 'shipping_info', 'shipping_created', 'shipping_updated', 'shipping_deleted', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'SiteId' => 1, 'ArticleId' => 2, 'Mode' => 3, 'Type' => 4, 'Zone' => 5, 'MinWeight' => 6, 'MaxWeight' => 7, 'MaxArticles' => 8, 'MinAmount' => 9, 'MaxAmount' => 10, 'Fee' => 11, 'Info' => 12, 'CreatedAt' => 13, 'UpdatedAt' => 14, 'DeletedAt' => 15, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'siteId' => 1, 'articleId' => 2, 'mode' => 3, 'type' => 4, 'zone' => 5, 'minWeight' => 6, 'maxWeight' => 7, 'maxArticles' => 8, 'minAmount' => 9, 'maxAmount' => 10, 'fee' => 11, 'info' => 12, 'createdAt' => 13, 'updatedAt' => 14, 'deletedAt' => 15, ),
        self::TYPE_COLNAME       => array(ShippingFeeTableMap::COL_SHIPPING_ID => 0, ShippingFeeTableMap::COL_SITE_ID => 1, ShippingFeeTableMap::COL_ARTICLE_ID => 2, ShippingFeeTableMap::COL_SHIPPING_MODE => 3, ShippingFeeTableMap::COL_SHIPPING_TYPE => 4, ShippingFeeTableMap::COL_SHIPPING_ZONE => 5, ShippingFeeTableMap::COL_SHIPPING_MIN_WEIGHT => 6, ShippingFeeTableMap::COL_SHIPPING_MAX_WEIGHT => 7, ShippingFeeTableMap::COL_SHIPPING_MAX_ARTICLES => 8, ShippingFeeTableMap::COL_SHIPPING_MIN_AMOUNT => 9, ShippingFeeTableMap::COL_SHIPPING_MAX_AMOUNT => 10, ShippingFeeTableMap::COL_SHIPPING_FEE => 11, ShippingFeeTableMap::COL_SHIPPING_INFO => 12, ShippingFeeTableMap::COL_SHIPPING_CREATED => 13, ShippingFeeTableMap::COL_SHIPPING_UPDATED => 14, ShippingFeeTableMap::COL_SHIPPING_DELETED => 15, ),
        self::TYPE_FIELDNAME     => array('shipping_id' => 0, 'site_id' => 1, 'article_id' => 2, 'shipping_mode' => 3, 'shipping_type' => 4, 'shipping_zone' => 5, 'shipping_min_weight' => 6, 'shipping_max_weight' => 7, 'shipping_max_articles' => 8, 'shipping_min_amount' => 9, 'shipping_max_amount' => 10, 'shipping_fee' => 11, 'shipping_info' => 12, 'shipping_created' => 13, 'shipping_updated' => 14, 'shipping_deleted' => 15, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, )
    );

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var string[]
     */
    protected $normalizedColumnNameMap = [

        'Id' => 'SHIPPING_ID',
        'ShippingFee.Id' => 'SHIPPING_ID',
        'id' => 'SHIPPING_ID',
        'shippingFee.id' => 'SHIPPING_ID',
        'ShippingFeeTableMap::COL_SHIPPING_ID' => 'SHIPPING_ID',
        'COL_SHIPPING_ID' => 'SHIPPING_ID',
        'shipping_id' => 'SHIPPING_ID',
        'shipping.shipping_id' => 'SHIPPING_ID',
        'SiteId' => 'SITE_ID',
        'ShippingFee.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'shippingFee.siteId' => 'SITE_ID',
        'ShippingFeeTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'shipping.site_id' => 'SITE_ID',
        'ArticleId' => 'ARTICLE_ID',
        'ShippingFee.ArticleId' => 'ARTICLE_ID',
        'articleId' => 'ARTICLE_ID',
        'shippingFee.articleId' => 'ARTICLE_ID',
        'ShippingFeeTableMap::COL_ARTICLE_ID' => 'ARTICLE_ID',
        'COL_ARTICLE_ID' => 'ARTICLE_ID',
        'article_id' => 'ARTICLE_ID',
        'shipping.article_id' => 'ARTICLE_ID',
        'Mode' => 'SHIPPING_MODE',
        'ShippingFee.Mode' => 'SHIPPING_MODE',
        'mode' => 'SHIPPING_MODE',
        'shippingFee.mode' => 'SHIPPING_MODE',
        'ShippingFeeTableMap::COL_SHIPPING_MODE' => 'SHIPPING_MODE',
        'COL_SHIPPING_MODE' => 'SHIPPING_MODE',
        'shipping_mode' => 'SHIPPING_MODE',
        'shipping.shipping_mode' => 'SHIPPING_MODE',
        'Type' => 'SHIPPING_TYPE',
        'ShippingFee.Type' => 'SHIPPING_TYPE',
        'type' => 'SHIPPING_TYPE',
        'shippingFee.type' => 'SHIPPING_TYPE',
        'ShippingFeeTableMap::COL_SHIPPING_TYPE' => 'SHIPPING_TYPE',
        'COL_SHIPPING_TYPE' => 'SHIPPING_TYPE',
        'shipping_type' => 'SHIPPING_TYPE',
        'shipping.shipping_type' => 'SHIPPING_TYPE',
        'Zone' => 'SHIPPING_ZONE',
        'ShippingFee.Zone' => 'SHIPPING_ZONE',
        'zone' => 'SHIPPING_ZONE',
        'shippingFee.zone' => 'SHIPPING_ZONE',
        'ShippingFeeTableMap::COL_SHIPPING_ZONE' => 'SHIPPING_ZONE',
        'COL_SHIPPING_ZONE' => 'SHIPPING_ZONE',
        'shipping_zone' => 'SHIPPING_ZONE',
        'shipping.shipping_zone' => 'SHIPPING_ZONE',
        'MinWeight' => 'SHIPPING_MIN_WEIGHT',
        'ShippingFee.MinWeight' => 'SHIPPING_MIN_WEIGHT',
        'minWeight' => 'SHIPPING_MIN_WEIGHT',
        'shippingFee.minWeight' => 'SHIPPING_MIN_WEIGHT',
        'ShippingFeeTableMap::COL_SHIPPING_MIN_WEIGHT' => 'SHIPPING_MIN_WEIGHT',
        'COL_SHIPPING_MIN_WEIGHT' => 'SHIPPING_MIN_WEIGHT',
        'shipping_min_weight' => 'SHIPPING_MIN_WEIGHT',
        'shipping.shipping_min_weight' => 'SHIPPING_MIN_WEIGHT',
        'MaxWeight' => 'SHIPPING_MAX_WEIGHT',
        'ShippingFee.MaxWeight' => 'SHIPPING_MAX_WEIGHT',
        'maxWeight' => 'SHIPPING_MAX_WEIGHT',
        'shippingFee.maxWeight' => 'SHIPPING_MAX_WEIGHT',
        'ShippingFeeTableMap::COL_SHIPPING_MAX_WEIGHT' => 'SHIPPING_MAX_WEIGHT',
        'COL_SHIPPING_MAX_WEIGHT' => 'SHIPPING_MAX_WEIGHT',
        'shipping_max_weight' => 'SHIPPING_MAX_WEIGHT',
        'shipping.shipping_max_weight' => 'SHIPPING_MAX_WEIGHT',
        'MaxArticles' => 'SHIPPING_MAX_ARTICLES',
        'ShippingFee.MaxArticles' => 'SHIPPING_MAX_ARTICLES',
        'maxArticles' => 'SHIPPING_MAX_ARTICLES',
        'shippingFee.maxArticles' => 'SHIPPING_MAX_ARTICLES',
        'ShippingFeeTableMap::COL_SHIPPING_MAX_ARTICLES' => 'SHIPPING_MAX_ARTICLES',
        'COL_SHIPPING_MAX_ARTICLES' => 'SHIPPING_MAX_ARTICLES',
        'shipping_max_articles' => 'SHIPPING_MAX_ARTICLES',
        'shipping.shipping_max_articles' => 'SHIPPING_MAX_ARTICLES',
        'MinAmount' => 'SHIPPING_MIN_AMOUNT',
        'ShippingFee.MinAmount' => 'SHIPPING_MIN_AMOUNT',
        'minAmount' => 'SHIPPING_MIN_AMOUNT',
        'shippingFee.minAmount' => 'SHIPPING_MIN_AMOUNT',
        'ShippingFeeTableMap::COL_SHIPPING_MIN_AMOUNT' => 'SHIPPING_MIN_AMOUNT',
        'COL_SHIPPING_MIN_AMOUNT' => 'SHIPPING_MIN_AMOUNT',
        'shipping_min_amount' => 'SHIPPING_MIN_AMOUNT',
        'shipping.shipping_min_amount' => 'SHIPPING_MIN_AMOUNT',
        'MaxAmount' => 'SHIPPING_MAX_AMOUNT',
        'ShippingFee.MaxAmount' => 'SHIPPING_MAX_AMOUNT',
        'maxAmount' => 'SHIPPING_MAX_AMOUNT',
        'shippingFee.maxAmount' => 'SHIPPING_MAX_AMOUNT',
        'ShippingFeeTableMap::COL_SHIPPING_MAX_AMOUNT' => 'SHIPPING_MAX_AMOUNT',
        'COL_SHIPPING_MAX_AMOUNT' => 'SHIPPING_MAX_AMOUNT',
        'shipping_max_amount' => 'SHIPPING_MAX_AMOUNT',
        'shipping.shipping_max_amount' => 'SHIPPING_MAX_AMOUNT',
        'Fee' => 'SHIPPING_FEE',
        'ShippingFee.Fee' => 'SHIPPING_FEE',
        'fee' => 'SHIPPING_FEE',
        'shippingFee.fee' => 'SHIPPING_FEE',
        'ShippingFeeTableMap::COL_SHIPPING_FEE' => 'SHIPPING_FEE',
        'COL_SHIPPING_FEE' => 'SHIPPING_FEE',
        'shipping_fee' => 'SHIPPING_FEE',
        'shipping.shipping_fee' => 'SHIPPING_FEE',
        'Info' => 'SHIPPING_INFO',
        'ShippingFee.Info' => 'SHIPPING_INFO',
        'info' => 'SHIPPING_INFO',
        'shippingFee.info' => 'SHIPPING_INFO',
        'ShippingFeeTableMap::COL_SHIPPING_INFO' => 'SHIPPING_INFO',
        'COL_SHIPPING_INFO' => 'SHIPPING_INFO',
        'shipping_info' => 'SHIPPING_INFO',
        'shipping.shipping_info' => 'SHIPPING_INFO',
        'CreatedAt' => 'SHIPPING_CREATED',
        'ShippingFee.CreatedAt' => 'SHIPPING_CREATED',
        'createdAt' => 'SHIPPING_CREATED',
        'shippingFee.createdAt' => 'SHIPPING_CREATED',
        'ShippingFeeTableMap::COL_SHIPPING_CREATED' => 'SHIPPING_CREATED',
        'COL_SHIPPING_CREATED' => 'SHIPPING_CREATED',
        'shipping_created' => 'SHIPPING_CREATED',
        'shipping.shipping_created' => 'SHIPPING_CREATED',
        'UpdatedAt' => 'SHIPPING_UPDATED',
        'ShippingFee.UpdatedAt' => 'SHIPPING_UPDATED',
        'updatedAt' => 'SHIPPING_UPDATED',
        'shippingFee.updatedAt' => 'SHIPPING_UPDATED',
        'ShippingFeeTableMap::COL_SHIPPING_UPDATED' => 'SHIPPING_UPDATED',
        'COL_SHIPPING_UPDATED' => 'SHIPPING_UPDATED',
        'shipping_updated' => 'SHIPPING_UPDATED',
        'shipping.shipping_updated' => 'SHIPPING_UPDATED',
        'DeletedAt' => 'SHIPPING_DELETED',
        'ShippingFee.DeletedAt' => 'SHIPPING_DELETED',
        'deletedAt' => 'SHIPPING_DELETED',
        'shippingFee.deletedAt' => 'SHIPPING_DELETED',
        'ShippingFeeTableMap::COL_SHIPPING_DELETED' => 'SHIPPING_DELETED',
        'COL_SHIPPING_DELETED' => 'SHIPPING_DELETED',
        'shipping_deleted' => 'SHIPPING_DELETED',
        'shipping.shipping_deleted' => 'SHIPPING_DELETED',
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
        $this->setName('shipping');
        $this->setPhpName('ShippingFee');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\ShippingFee');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('shipping_id', 'Id', 'INTEGER', true, 10, null);
        $this->addColumn('site_id', 'SiteId', 'INTEGER', false, null, null);
        $this->addColumn('article_id', 'ArticleId', 'INTEGER', false, 10, null);
        $this->addColumn('shipping_mode', 'Mode', 'VARCHAR', false, 64, null);
        $this->addColumn('shipping_type', 'Type', 'VARCHAR', false, 16, null);
        $this->addColumn('shipping_zone', 'Zone', 'VARCHAR', false, 4, null);
        $this->addColumn('shipping_min_weight', 'MinWeight', 'INTEGER', false, null, null);
        $this->addColumn('shipping_max_weight', 'MaxWeight', 'INTEGER', false, null, null);
        $this->addColumn('shipping_max_articles', 'MaxArticles', 'INTEGER', false, 10, null);
        $this->addColumn('shipping_min_amount', 'MinAmount', 'INTEGER', false, null, null);
        $this->addColumn('shipping_max_amount', 'MaxAmount', 'INTEGER', false, null, null);
        $this->addColumn('shipping_fee', 'Fee', 'INTEGER', false, null, null);
        $this->addColumn('shipping_info', 'Info', 'VARCHAR', false, 512, null);
        $this->addColumn('shipping_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('shipping_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('shipping_deleted', 'DeletedAt', 'TIMESTAMP', false, null, null);
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
            'timestampable' => array('create_column' => 'shipping_created', 'update_column' => 'shipping_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false', ),
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
        return $withPrefix ? ShippingFeeTableMap::CLASS_DEFAULT : ShippingFeeTableMap::OM_CLASS;
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
     * @return array           (ShippingFee object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = ShippingFeeTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = ShippingFeeTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + ShippingFeeTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ShippingFeeTableMap::OM_CLASS;
            /** @var ShippingFee $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            ShippingFeeTableMap::addInstanceToPool($obj, $key);
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
            $key = ShippingFeeTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = ShippingFeeTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var ShippingFee $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ShippingFeeTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(ShippingFeeTableMap::COL_SHIPPING_ID);
            $criteria->addSelectColumn(ShippingFeeTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(ShippingFeeTableMap::COL_ARTICLE_ID);
            $criteria->addSelectColumn(ShippingFeeTableMap::COL_SHIPPING_MODE);
            $criteria->addSelectColumn(ShippingFeeTableMap::COL_SHIPPING_TYPE);
            $criteria->addSelectColumn(ShippingFeeTableMap::COL_SHIPPING_ZONE);
            $criteria->addSelectColumn(ShippingFeeTableMap::COL_SHIPPING_MIN_WEIGHT);
            $criteria->addSelectColumn(ShippingFeeTableMap::COL_SHIPPING_MAX_WEIGHT);
            $criteria->addSelectColumn(ShippingFeeTableMap::COL_SHIPPING_MAX_ARTICLES);
            $criteria->addSelectColumn(ShippingFeeTableMap::COL_SHIPPING_MIN_AMOUNT);
            $criteria->addSelectColumn(ShippingFeeTableMap::COL_SHIPPING_MAX_AMOUNT);
            $criteria->addSelectColumn(ShippingFeeTableMap::COL_SHIPPING_FEE);
            $criteria->addSelectColumn(ShippingFeeTableMap::COL_SHIPPING_INFO);
            $criteria->addSelectColumn(ShippingFeeTableMap::COL_SHIPPING_CREATED);
            $criteria->addSelectColumn(ShippingFeeTableMap::COL_SHIPPING_UPDATED);
            $criteria->addSelectColumn(ShippingFeeTableMap::COL_SHIPPING_DELETED);
        } else {
            $criteria->addSelectColumn($alias . '.shipping_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.article_id');
            $criteria->addSelectColumn($alias . '.shipping_mode');
            $criteria->addSelectColumn($alias . '.shipping_type');
            $criteria->addSelectColumn($alias . '.shipping_zone');
            $criteria->addSelectColumn($alias . '.shipping_min_weight');
            $criteria->addSelectColumn($alias . '.shipping_max_weight');
            $criteria->addSelectColumn($alias . '.shipping_max_articles');
            $criteria->addSelectColumn($alias . '.shipping_min_amount');
            $criteria->addSelectColumn($alias . '.shipping_max_amount');
            $criteria->addSelectColumn($alias . '.shipping_fee');
            $criteria->addSelectColumn($alias . '.shipping_info');
            $criteria->addSelectColumn($alias . '.shipping_created');
            $criteria->addSelectColumn($alias . '.shipping_updated');
            $criteria->addSelectColumn($alias . '.shipping_deleted');
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
            $criteria->removeSelectColumn(ShippingFeeTableMap::COL_SHIPPING_ID);
            $criteria->removeSelectColumn(ShippingFeeTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(ShippingFeeTableMap::COL_ARTICLE_ID);
            $criteria->removeSelectColumn(ShippingFeeTableMap::COL_SHIPPING_MODE);
            $criteria->removeSelectColumn(ShippingFeeTableMap::COL_SHIPPING_TYPE);
            $criteria->removeSelectColumn(ShippingFeeTableMap::COL_SHIPPING_ZONE);
            $criteria->removeSelectColumn(ShippingFeeTableMap::COL_SHIPPING_MIN_WEIGHT);
            $criteria->removeSelectColumn(ShippingFeeTableMap::COL_SHIPPING_MAX_WEIGHT);
            $criteria->removeSelectColumn(ShippingFeeTableMap::COL_SHIPPING_MAX_ARTICLES);
            $criteria->removeSelectColumn(ShippingFeeTableMap::COL_SHIPPING_MIN_AMOUNT);
            $criteria->removeSelectColumn(ShippingFeeTableMap::COL_SHIPPING_MAX_AMOUNT);
            $criteria->removeSelectColumn(ShippingFeeTableMap::COL_SHIPPING_FEE);
            $criteria->removeSelectColumn(ShippingFeeTableMap::COL_SHIPPING_INFO);
            $criteria->removeSelectColumn(ShippingFeeTableMap::COL_SHIPPING_CREATED);
            $criteria->removeSelectColumn(ShippingFeeTableMap::COL_SHIPPING_UPDATED);
            $criteria->removeSelectColumn(ShippingFeeTableMap::COL_SHIPPING_DELETED);
        } else {
            $criteria->removeSelectColumn($alias . '.shipping_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.article_id');
            $criteria->removeSelectColumn($alias . '.shipping_mode');
            $criteria->removeSelectColumn($alias . '.shipping_type');
            $criteria->removeSelectColumn($alias . '.shipping_zone');
            $criteria->removeSelectColumn($alias . '.shipping_min_weight');
            $criteria->removeSelectColumn($alias . '.shipping_max_weight');
            $criteria->removeSelectColumn($alias . '.shipping_max_articles');
            $criteria->removeSelectColumn($alias . '.shipping_min_amount');
            $criteria->removeSelectColumn($alias . '.shipping_max_amount');
            $criteria->removeSelectColumn($alias . '.shipping_fee');
            $criteria->removeSelectColumn($alias . '.shipping_info');
            $criteria->removeSelectColumn($alias . '.shipping_created');
            $criteria->removeSelectColumn($alias . '.shipping_updated');
            $criteria->removeSelectColumn($alias . '.shipping_deleted');
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
        return Propel::getServiceContainer()->getDatabaseMap(ShippingFeeTableMap::DATABASE_NAME)->getTable(ShippingFeeTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(ShippingFeeTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(ShippingFeeTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new ShippingFeeTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a ShippingFee or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ShippingFee object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(ShippingFeeTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\ShippingFee) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ShippingFeeTableMap::DATABASE_NAME);
            $criteria->add(ShippingFeeTableMap::COL_SHIPPING_ID, (array) $values, Criteria::IN);
        }

        $query = ShippingFeeQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            ShippingFeeTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                ShippingFeeTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the shipping table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return ShippingFeeQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a ShippingFee or Criteria object.
     *
     * @param mixed               $criteria Criteria or ShippingFee object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ShippingFeeTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from ShippingFee object
        }

        if ($criteria->containsKey(ShippingFeeTableMap::COL_SHIPPING_ID) && $criteria->keyContainsValue(ShippingFeeTableMap::COL_SHIPPING_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.ShippingFeeTableMap::COL_SHIPPING_ID.')');
        }


        // Set the correct dbName
        $query = ShippingFeeQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // ShippingFeeTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
ShippingFeeTableMap::buildTableMap();
