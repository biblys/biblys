<?php

namespace Model\Map;

use Model\ShippingOption;
use Model\ShippingOptionQuery;
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
class ShippingOptionTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.ShippingOptionTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'shipping';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'ShippingOption';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\ShippingOption';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.ShippingOption';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 17;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 17;

    /**
     * the column name for the shipping_id field
     */
    public const COL_SHIPPING_ID = 'shipping.shipping_id';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'shipping.site_id';

    /**
     * the column name for the article_id field
     */
    public const COL_ARTICLE_ID = 'shipping.article_id';

    /**
     * the column name for the shipping_mode field
     */
    public const COL_SHIPPING_MODE = 'shipping.shipping_mode';

    /**
     * the column name for the shipping_type field
     */
    public const COL_SHIPPING_TYPE = 'shipping.shipping_type';

    /**
     * the column name for the shipping_zone field
     */
    public const COL_SHIPPING_ZONE = 'shipping.shipping_zone';

    /**
     * the column name for the shipping_zone_id field
     */
    public const COL_SHIPPING_ZONE_ID = 'shipping.shipping_zone_id';

    /**
     * the column name for the shipping_min_weight field
     */
    public const COL_SHIPPING_MIN_WEIGHT = 'shipping.shipping_min_weight';

    /**
     * the column name for the shipping_max_weight field
     */
    public const COL_SHIPPING_MAX_WEIGHT = 'shipping.shipping_max_weight';

    /**
     * the column name for the shipping_max_articles field
     */
    public const COL_SHIPPING_MAX_ARTICLES = 'shipping.shipping_max_articles';

    /**
     * the column name for the shipping_min_amount field
     */
    public const COL_SHIPPING_MIN_AMOUNT = 'shipping.shipping_min_amount';

    /**
     * the column name for the shipping_max_amount field
     */
    public const COL_SHIPPING_MAX_AMOUNT = 'shipping.shipping_max_amount';

    /**
     * the column name for the shipping_fee field
     */
    public const COL_SHIPPING_FEE = 'shipping.shipping_fee';

    /**
     * the column name for the shipping_info field
     */
    public const COL_SHIPPING_INFO = 'shipping.shipping_info';

    /**
     * the column name for the shipping_created field
     */
    public const COL_SHIPPING_CREATED = 'shipping.shipping_created';

    /**
     * the column name for the shipping_updated field
     */
    public const COL_SHIPPING_UPDATED = 'shipping.shipping_updated';

    /**
     * the column name for the shipping_archived_at field
     */
    public const COL_SHIPPING_ARCHIVED_AT = 'shipping.shipping_archived_at';

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
        self::TYPE_PHPNAME       => ['Id', 'SiteId', 'ArticleId', 'Mode', 'Type', 'ZoneCode', 'ShippingZoneId', 'MinWeight', 'MaxWeight', 'MaxArticles', 'MinAmount', 'MaxAmount', 'Fee', 'Info', 'CreatedAt', 'UpdatedAt', 'ArchivedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'siteId', 'articleId', 'mode', 'type', 'zoneCode', 'shippingZoneId', 'minWeight', 'maxWeight', 'maxArticles', 'minAmount', 'maxAmount', 'fee', 'info', 'createdAt', 'updatedAt', 'archivedAt', ],
        self::TYPE_COLNAME       => [ShippingOptionTableMap::COL_SHIPPING_ID, ShippingOptionTableMap::COL_SITE_ID, ShippingOptionTableMap::COL_ARTICLE_ID, ShippingOptionTableMap::COL_SHIPPING_MODE, ShippingOptionTableMap::COL_SHIPPING_TYPE, ShippingOptionTableMap::COL_SHIPPING_ZONE, ShippingOptionTableMap::COL_SHIPPING_ZONE_ID, ShippingOptionTableMap::COL_SHIPPING_MIN_WEIGHT, ShippingOptionTableMap::COL_SHIPPING_MAX_WEIGHT, ShippingOptionTableMap::COL_SHIPPING_MAX_ARTICLES, ShippingOptionTableMap::COL_SHIPPING_MIN_AMOUNT, ShippingOptionTableMap::COL_SHIPPING_MAX_AMOUNT, ShippingOptionTableMap::COL_SHIPPING_FEE, ShippingOptionTableMap::COL_SHIPPING_INFO, ShippingOptionTableMap::COL_SHIPPING_CREATED, ShippingOptionTableMap::COL_SHIPPING_UPDATED, ShippingOptionTableMap::COL_SHIPPING_ARCHIVED_AT, ],
        self::TYPE_FIELDNAME     => ['shipping_id', 'site_id', 'article_id', 'shipping_mode', 'shipping_type', 'shipping_zone', 'shipping_zone_id', 'shipping_min_weight', 'shipping_max_weight', 'shipping_max_articles', 'shipping_min_amount', 'shipping_max_amount', 'shipping_fee', 'shipping_info', 'shipping_created', 'shipping_updated', 'shipping_archived_at', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'SiteId' => 1, 'ArticleId' => 2, 'Mode' => 3, 'Type' => 4, 'ZoneCode' => 5, 'ShippingZoneId' => 6, 'MinWeight' => 7, 'MaxWeight' => 8, 'MaxArticles' => 9, 'MinAmount' => 10, 'MaxAmount' => 11, 'Fee' => 12, 'Info' => 13, 'CreatedAt' => 14, 'UpdatedAt' => 15, 'ArchivedAt' => 16, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'siteId' => 1, 'articleId' => 2, 'mode' => 3, 'type' => 4, 'zoneCode' => 5, 'shippingZoneId' => 6, 'minWeight' => 7, 'maxWeight' => 8, 'maxArticles' => 9, 'minAmount' => 10, 'maxAmount' => 11, 'fee' => 12, 'info' => 13, 'createdAt' => 14, 'updatedAt' => 15, 'archivedAt' => 16, ],
        self::TYPE_COLNAME       => [ShippingOptionTableMap::COL_SHIPPING_ID => 0, ShippingOptionTableMap::COL_SITE_ID => 1, ShippingOptionTableMap::COL_ARTICLE_ID => 2, ShippingOptionTableMap::COL_SHIPPING_MODE => 3, ShippingOptionTableMap::COL_SHIPPING_TYPE => 4, ShippingOptionTableMap::COL_SHIPPING_ZONE => 5, ShippingOptionTableMap::COL_SHIPPING_ZONE_ID => 6, ShippingOptionTableMap::COL_SHIPPING_MIN_WEIGHT => 7, ShippingOptionTableMap::COL_SHIPPING_MAX_WEIGHT => 8, ShippingOptionTableMap::COL_SHIPPING_MAX_ARTICLES => 9, ShippingOptionTableMap::COL_SHIPPING_MIN_AMOUNT => 10, ShippingOptionTableMap::COL_SHIPPING_MAX_AMOUNT => 11, ShippingOptionTableMap::COL_SHIPPING_FEE => 12, ShippingOptionTableMap::COL_SHIPPING_INFO => 13, ShippingOptionTableMap::COL_SHIPPING_CREATED => 14, ShippingOptionTableMap::COL_SHIPPING_UPDATED => 15, ShippingOptionTableMap::COL_SHIPPING_ARCHIVED_AT => 16, ],
        self::TYPE_FIELDNAME     => ['shipping_id' => 0, 'site_id' => 1, 'article_id' => 2, 'shipping_mode' => 3, 'shipping_type' => 4, 'shipping_zone' => 5, 'shipping_zone_id' => 6, 'shipping_min_weight' => 7, 'shipping_max_weight' => 8, 'shipping_max_articles' => 9, 'shipping_min_amount' => 10, 'shipping_max_amount' => 11, 'shipping_fee' => 12, 'shipping_info' => 13, 'shipping_created' => 14, 'shipping_updated' => 15, 'shipping_archived_at' => 16, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'SHIPPING_ID',
        'ShippingOption.Id' => 'SHIPPING_ID',
        'id' => 'SHIPPING_ID',
        'shippingOption.id' => 'SHIPPING_ID',
        'ShippingOptionTableMap::COL_SHIPPING_ID' => 'SHIPPING_ID',
        'COL_SHIPPING_ID' => 'SHIPPING_ID',
        'shipping_id' => 'SHIPPING_ID',
        'shipping.shipping_id' => 'SHIPPING_ID',
        'SiteId' => 'SITE_ID',
        'ShippingOption.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'shippingOption.siteId' => 'SITE_ID',
        'ShippingOptionTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'shipping.site_id' => 'SITE_ID',
        'ArticleId' => 'ARTICLE_ID',
        'ShippingOption.ArticleId' => 'ARTICLE_ID',
        'articleId' => 'ARTICLE_ID',
        'shippingOption.articleId' => 'ARTICLE_ID',
        'ShippingOptionTableMap::COL_ARTICLE_ID' => 'ARTICLE_ID',
        'COL_ARTICLE_ID' => 'ARTICLE_ID',
        'article_id' => 'ARTICLE_ID',
        'shipping.article_id' => 'ARTICLE_ID',
        'Mode' => 'SHIPPING_MODE',
        'ShippingOption.Mode' => 'SHIPPING_MODE',
        'mode' => 'SHIPPING_MODE',
        'shippingOption.mode' => 'SHIPPING_MODE',
        'ShippingOptionTableMap::COL_SHIPPING_MODE' => 'SHIPPING_MODE',
        'COL_SHIPPING_MODE' => 'SHIPPING_MODE',
        'shipping_mode' => 'SHIPPING_MODE',
        'shipping.shipping_mode' => 'SHIPPING_MODE',
        'Type' => 'SHIPPING_TYPE',
        'ShippingOption.Type' => 'SHIPPING_TYPE',
        'type' => 'SHIPPING_TYPE',
        'shippingOption.type' => 'SHIPPING_TYPE',
        'ShippingOptionTableMap::COL_SHIPPING_TYPE' => 'SHIPPING_TYPE',
        'COL_SHIPPING_TYPE' => 'SHIPPING_TYPE',
        'shipping_type' => 'SHIPPING_TYPE',
        'shipping.shipping_type' => 'SHIPPING_TYPE',
        'ZoneCode' => 'SHIPPING_ZONE',
        'ShippingOption.ZoneCode' => 'SHIPPING_ZONE',
        'zoneCode' => 'SHIPPING_ZONE',
        'shippingOption.zoneCode' => 'SHIPPING_ZONE',
        'ShippingOptionTableMap::COL_SHIPPING_ZONE' => 'SHIPPING_ZONE',
        'COL_SHIPPING_ZONE' => 'SHIPPING_ZONE',
        'shipping_zone' => 'SHIPPING_ZONE',
        'shipping.shipping_zone' => 'SHIPPING_ZONE',
        'ShippingZoneId' => 'SHIPPING_ZONE_ID',
        'ShippingOption.ShippingZoneId' => 'SHIPPING_ZONE_ID',
        'shippingZoneId' => 'SHIPPING_ZONE_ID',
        'shippingOption.shippingZoneId' => 'SHIPPING_ZONE_ID',
        'ShippingOptionTableMap::COL_SHIPPING_ZONE_ID' => 'SHIPPING_ZONE_ID',
        'COL_SHIPPING_ZONE_ID' => 'SHIPPING_ZONE_ID',
        'shipping_zone_id' => 'SHIPPING_ZONE_ID',
        'shipping.shipping_zone_id' => 'SHIPPING_ZONE_ID',
        'MinWeight' => 'SHIPPING_MIN_WEIGHT',
        'ShippingOption.MinWeight' => 'SHIPPING_MIN_WEIGHT',
        'minWeight' => 'SHIPPING_MIN_WEIGHT',
        'shippingOption.minWeight' => 'SHIPPING_MIN_WEIGHT',
        'ShippingOptionTableMap::COL_SHIPPING_MIN_WEIGHT' => 'SHIPPING_MIN_WEIGHT',
        'COL_SHIPPING_MIN_WEIGHT' => 'SHIPPING_MIN_WEIGHT',
        'shipping_min_weight' => 'SHIPPING_MIN_WEIGHT',
        'shipping.shipping_min_weight' => 'SHIPPING_MIN_WEIGHT',
        'MaxWeight' => 'SHIPPING_MAX_WEIGHT',
        'ShippingOption.MaxWeight' => 'SHIPPING_MAX_WEIGHT',
        'maxWeight' => 'SHIPPING_MAX_WEIGHT',
        'shippingOption.maxWeight' => 'SHIPPING_MAX_WEIGHT',
        'ShippingOptionTableMap::COL_SHIPPING_MAX_WEIGHT' => 'SHIPPING_MAX_WEIGHT',
        'COL_SHIPPING_MAX_WEIGHT' => 'SHIPPING_MAX_WEIGHT',
        'shipping_max_weight' => 'SHIPPING_MAX_WEIGHT',
        'shipping.shipping_max_weight' => 'SHIPPING_MAX_WEIGHT',
        'MaxArticles' => 'SHIPPING_MAX_ARTICLES',
        'ShippingOption.MaxArticles' => 'SHIPPING_MAX_ARTICLES',
        'maxArticles' => 'SHIPPING_MAX_ARTICLES',
        'shippingOption.maxArticles' => 'SHIPPING_MAX_ARTICLES',
        'ShippingOptionTableMap::COL_SHIPPING_MAX_ARTICLES' => 'SHIPPING_MAX_ARTICLES',
        'COL_SHIPPING_MAX_ARTICLES' => 'SHIPPING_MAX_ARTICLES',
        'shipping_max_articles' => 'SHIPPING_MAX_ARTICLES',
        'shipping.shipping_max_articles' => 'SHIPPING_MAX_ARTICLES',
        'MinAmount' => 'SHIPPING_MIN_AMOUNT',
        'ShippingOption.MinAmount' => 'SHIPPING_MIN_AMOUNT',
        'minAmount' => 'SHIPPING_MIN_AMOUNT',
        'shippingOption.minAmount' => 'SHIPPING_MIN_AMOUNT',
        'ShippingOptionTableMap::COL_SHIPPING_MIN_AMOUNT' => 'SHIPPING_MIN_AMOUNT',
        'COL_SHIPPING_MIN_AMOUNT' => 'SHIPPING_MIN_AMOUNT',
        'shipping_min_amount' => 'SHIPPING_MIN_AMOUNT',
        'shipping.shipping_min_amount' => 'SHIPPING_MIN_AMOUNT',
        'MaxAmount' => 'SHIPPING_MAX_AMOUNT',
        'ShippingOption.MaxAmount' => 'SHIPPING_MAX_AMOUNT',
        'maxAmount' => 'SHIPPING_MAX_AMOUNT',
        'shippingOption.maxAmount' => 'SHIPPING_MAX_AMOUNT',
        'ShippingOptionTableMap::COL_SHIPPING_MAX_AMOUNT' => 'SHIPPING_MAX_AMOUNT',
        'COL_SHIPPING_MAX_AMOUNT' => 'SHIPPING_MAX_AMOUNT',
        'shipping_max_amount' => 'SHIPPING_MAX_AMOUNT',
        'shipping.shipping_max_amount' => 'SHIPPING_MAX_AMOUNT',
        'Fee' => 'SHIPPING_FEE',
        'ShippingOption.Fee' => 'SHIPPING_FEE',
        'fee' => 'SHIPPING_FEE',
        'shippingOption.fee' => 'SHIPPING_FEE',
        'ShippingOptionTableMap::COL_SHIPPING_FEE' => 'SHIPPING_FEE',
        'COL_SHIPPING_FEE' => 'SHIPPING_FEE',
        'shipping_fee' => 'SHIPPING_FEE',
        'shipping.shipping_fee' => 'SHIPPING_FEE',
        'Info' => 'SHIPPING_INFO',
        'ShippingOption.Info' => 'SHIPPING_INFO',
        'info' => 'SHIPPING_INFO',
        'shippingOption.info' => 'SHIPPING_INFO',
        'ShippingOptionTableMap::COL_SHIPPING_INFO' => 'SHIPPING_INFO',
        'COL_SHIPPING_INFO' => 'SHIPPING_INFO',
        'shipping_info' => 'SHIPPING_INFO',
        'shipping.shipping_info' => 'SHIPPING_INFO',
        'CreatedAt' => 'SHIPPING_CREATED',
        'ShippingOption.CreatedAt' => 'SHIPPING_CREATED',
        'createdAt' => 'SHIPPING_CREATED',
        'shippingOption.createdAt' => 'SHIPPING_CREATED',
        'ShippingOptionTableMap::COL_SHIPPING_CREATED' => 'SHIPPING_CREATED',
        'COL_SHIPPING_CREATED' => 'SHIPPING_CREATED',
        'shipping_created' => 'SHIPPING_CREATED',
        'shipping.shipping_created' => 'SHIPPING_CREATED',
        'UpdatedAt' => 'SHIPPING_UPDATED',
        'ShippingOption.UpdatedAt' => 'SHIPPING_UPDATED',
        'updatedAt' => 'SHIPPING_UPDATED',
        'shippingOption.updatedAt' => 'SHIPPING_UPDATED',
        'ShippingOptionTableMap::COL_SHIPPING_UPDATED' => 'SHIPPING_UPDATED',
        'COL_SHIPPING_UPDATED' => 'SHIPPING_UPDATED',
        'shipping_updated' => 'SHIPPING_UPDATED',
        'shipping.shipping_updated' => 'SHIPPING_UPDATED',
        'ArchivedAt' => 'SHIPPING_ARCHIVED_AT',
        'ShippingOption.ArchivedAt' => 'SHIPPING_ARCHIVED_AT',
        'archivedAt' => 'SHIPPING_ARCHIVED_AT',
        'shippingOption.archivedAt' => 'SHIPPING_ARCHIVED_AT',
        'ShippingOptionTableMap::COL_SHIPPING_ARCHIVED_AT' => 'SHIPPING_ARCHIVED_AT',
        'COL_SHIPPING_ARCHIVED_AT' => 'SHIPPING_ARCHIVED_AT',
        'shipping_archived_at' => 'SHIPPING_ARCHIVED_AT',
        'shipping.shipping_archived_at' => 'SHIPPING_ARCHIVED_AT',
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
        $this->setName('shipping');
        $this->setPhpName('ShippingOption');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\ShippingOption');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('shipping_id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('site_id', 'SiteId', 'INTEGER', false, null, null);
        $this->addColumn('article_id', 'ArticleId', 'INTEGER', false, null, null);
        $this->addColumn('shipping_mode', 'Mode', 'VARCHAR', false, 64, null);
        $this->addColumn('shipping_type', 'Type', 'VARCHAR', false, 16, null);
        $this->addColumn('shipping_zone', 'ZoneCode', 'VARCHAR', false, 4, null);
        $this->addForeignKey('shipping_zone_id', 'ShippingZoneId', 'INTEGER', 'shipping_zones', 'id', false, null, null);
        $this->addColumn('shipping_min_weight', 'MinWeight', 'INTEGER', false, null, null);
        $this->addColumn('shipping_max_weight', 'MaxWeight', 'INTEGER', false, null, null);
        $this->addColumn('shipping_max_articles', 'MaxArticles', 'INTEGER', false, null, null);
        $this->addColumn('shipping_min_amount', 'MinAmount', 'INTEGER', false, null, null);
        $this->addColumn('shipping_max_amount', 'MaxAmount', 'INTEGER', false, null, null);
        $this->addColumn('shipping_fee', 'Fee', 'INTEGER', false, null, null);
        $this->addColumn('shipping_info', 'Info', 'VARCHAR', false, 512, null);
        $this->addColumn('shipping_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('shipping_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('shipping_archived_at', 'ArchivedAt', 'TIMESTAMP', false, null, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
        $this->addRelation('ShippingZone', '\\Model\\ShippingZone', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':shipping_zone_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Order', '\\Model\\Order', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':shipping_id',
    1 => ':shipping_id',
  ),
), null, null, 'Orders', false);
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
            'timestampable' => ['create_column' => 'shipping_created', 'update_column' => 'shipping_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? ShippingOptionTableMap::CLASS_DEFAULT : ShippingOptionTableMap::OM_CLASS;
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
     * @return array (ShippingOption object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = ShippingOptionTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = ShippingOptionTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + ShippingOptionTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ShippingOptionTableMap::OM_CLASS;
            /** @var ShippingOption $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            ShippingOptionTableMap::addInstanceToPool($obj, $key);
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
            $key = ShippingOptionTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = ShippingOptionTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var ShippingOption $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ShippingOptionTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(ShippingOptionTableMap::COL_SHIPPING_ID);
            $criteria->addSelectColumn(ShippingOptionTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(ShippingOptionTableMap::COL_ARTICLE_ID);
            $criteria->addSelectColumn(ShippingOptionTableMap::COL_SHIPPING_MODE);
            $criteria->addSelectColumn(ShippingOptionTableMap::COL_SHIPPING_TYPE);
            $criteria->addSelectColumn(ShippingOptionTableMap::COL_SHIPPING_ZONE);
            $criteria->addSelectColumn(ShippingOptionTableMap::COL_SHIPPING_ZONE_ID);
            $criteria->addSelectColumn(ShippingOptionTableMap::COL_SHIPPING_MIN_WEIGHT);
            $criteria->addSelectColumn(ShippingOptionTableMap::COL_SHIPPING_MAX_WEIGHT);
            $criteria->addSelectColumn(ShippingOptionTableMap::COL_SHIPPING_MAX_ARTICLES);
            $criteria->addSelectColumn(ShippingOptionTableMap::COL_SHIPPING_MIN_AMOUNT);
            $criteria->addSelectColumn(ShippingOptionTableMap::COL_SHIPPING_MAX_AMOUNT);
            $criteria->addSelectColumn(ShippingOptionTableMap::COL_SHIPPING_FEE);
            $criteria->addSelectColumn(ShippingOptionTableMap::COL_SHIPPING_INFO);
            $criteria->addSelectColumn(ShippingOptionTableMap::COL_SHIPPING_CREATED);
            $criteria->addSelectColumn(ShippingOptionTableMap::COL_SHIPPING_UPDATED);
            $criteria->addSelectColumn(ShippingOptionTableMap::COL_SHIPPING_ARCHIVED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.shipping_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.article_id');
            $criteria->addSelectColumn($alias . '.shipping_mode');
            $criteria->addSelectColumn($alias . '.shipping_type');
            $criteria->addSelectColumn($alias . '.shipping_zone');
            $criteria->addSelectColumn($alias . '.shipping_zone_id');
            $criteria->addSelectColumn($alias . '.shipping_min_weight');
            $criteria->addSelectColumn($alias . '.shipping_max_weight');
            $criteria->addSelectColumn($alias . '.shipping_max_articles');
            $criteria->addSelectColumn($alias . '.shipping_min_amount');
            $criteria->addSelectColumn($alias . '.shipping_max_amount');
            $criteria->addSelectColumn($alias . '.shipping_fee');
            $criteria->addSelectColumn($alias . '.shipping_info');
            $criteria->addSelectColumn($alias . '.shipping_created');
            $criteria->addSelectColumn($alias . '.shipping_updated');
            $criteria->addSelectColumn($alias . '.shipping_archived_at');
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
            $criteria->removeSelectColumn(ShippingOptionTableMap::COL_SHIPPING_ID);
            $criteria->removeSelectColumn(ShippingOptionTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(ShippingOptionTableMap::COL_ARTICLE_ID);
            $criteria->removeSelectColumn(ShippingOptionTableMap::COL_SHIPPING_MODE);
            $criteria->removeSelectColumn(ShippingOptionTableMap::COL_SHIPPING_TYPE);
            $criteria->removeSelectColumn(ShippingOptionTableMap::COL_SHIPPING_ZONE);
            $criteria->removeSelectColumn(ShippingOptionTableMap::COL_SHIPPING_ZONE_ID);
            $criteria->removeSelectColumn(ShippingOptionTableMap::COL_SHIPPING_MIN_WEIGHT);
            $criteria->removeSelectColumn(ShippingOptionTableMap::COL_SHIPPING_MAX_WEIGHT);
            $criteria->removeSelectColumn(ShippingOptionTableMap::COL_SHIPPING_MAX_ARTICLES);
            $criteria->removeSelectColumn(ShippingOptionTableMap::COL_SHIPPING_MIN_AMOUNT);
            $criteria->removeSelectColumn(ShippingOptionTableMap::COL_SHIPPING_MAX_AMOUNT);
            $criteria->removeSelectColumn(ShippingOptionTableMap::COL_SHIPPING_FEE);
            $criteria->removeSelectColumn(ShippingOptionTableMap::COL_SHIPPING_INFO);
            $criteria->removeSelectColumn(ShippingOptionTableMap::COL_SHIPPING_CREATED);
            $criteria->removeSelectColumn(ShippingOptionTableMap::COL_SHIPPING_UPDATED);
            $criteria->removeSelectColumn(ShippingOptionTableMap::COL_SHIPPING_ARCHIVED_AT);
        } else {
            $criteria->removeSelectColumn($alias . '.shipping_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.article_id');
            $criteria->removeSelectColumn($alias . '.shipping_mode');
            $criteria->removeSelectColumn($alias . '.shipping_type');
            $criteria->removeSelectColumn($alias . '.shipping_zone');
            $criteria->removeSelectColumn($alias . '.shipping_zone_id');
            $criteria->removeSelectColumn($alias . '.shipping_min_weight');
            $criteria->removeSelectColumn($alias . '.shipping_max_weight');
            $criteria->removeSelectColumn($alias . '.shipping_max_articles');
            $criteria->removeSelectColumn($alias . '.shipping_min_amount');
            $criteria->removeSelectColumn($alias . '.shipping_max_amount');
            $criteria->removeSelectColumn($alias . '.shipping_fee');
            $criteria->removeSelectColumn($alias . '.shipping_info');
            $criteria->removeSelectColumn($alias . '.shipping_created');
            $criteria->removeSelectColumn($alias . '.shipping_updated');
            $criteria->removeSelectColumn($alias . '.shipping_archived_at');
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
        return Propel::getServiceContainer()->getDatabaseMap(ShippingOptionTableMap::DATABASE_NAME)->getTable(ShippingOptionTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a ShippingOption or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or ShippingOption object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(ShippingOptionTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\ShippingOption) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ShippingOptionTableMap::DATABASE_NAME);
            $criteria->add(ShippingOptionTableMap::COL_SHIPPING_ID, (array) $values, Criteria::IN);
        }

        $query = ShippingOptionQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            ShippingOptionTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                ShippingOptionTableMap::removeInstanceFromPool($singleval);
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
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return ShippingOptionQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a ShippingOption or Criteria object.
     *
     * @param mixed $criteria Criteria or ShippingOption object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ShippingOptionTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from ShippingOption object
        }

        if ($criteria->containsKey(ShippingOptionTableMap::COL_SHIPPING_ID) && $criteria->keyContainsValue(ShippingOptionTableMap::COL_SHIPPING_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.ShippingOptionTableMap::COL_SHIPPING_ID.')');
        }


        // Set the correct dbName
        $query = ShippingOptionQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
