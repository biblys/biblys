<?php

namespace Model\Map;

use Model\Cart;
use Model\CartQuery;
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
 * This class defines the structure of the 'carts' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class CartTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.CartTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'carts';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Cart';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Cart';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Cart';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 18;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 18;

    /**
     * the column name for the cart_id field
     */
    public const COL_CART_ID = 'carts.cart_id';

    /**
     * the column name for the cart_uid field
     */
    public const COL_CART_UID = 'carts.cart_uid';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'carts.site_id';

    /**
     * the column name for the axys_user_id field
     */
    public const COL_AXYS_USER_ID = 'carts.axys_user_id';

    /**
     * the column name for the cart_seller_id field
     */
    public const COL_CART_SELLER_ID = 'carts.cart_seller_id';

    /**
     * the column name for the customer_id field
     */
    public const COL_CUSTOMER_ID = 'carts.customer_id';

    /**
     * the column name for the cart_title field
     */
    public const COL_CART_TITLE = 'carts.cart_title';

    /**
     * the column name for the cart_type field
     */
    public const COL_CART_TYPE = 'carts.cart_type';

    /**
     * the column name for the cart_ip field
     */
    public const COL_CART_IP = 'carts.cart_ip';

    /**
     * the column name for the cart_count field
     */
    public const COL_CART_COUNT = 'carts.cart_count';

    /**
     * the column name for the cart_amount field
     */
    public const COL_CART_AMOUNT = 'carts.cart_amount';

    /**
     * the column name for the cart_as_a_gift field
     */
    public const COL_AS_A_GIFT = 'carts.cart_as_a_gift';

    /**
     * the column name for the cart_gift_recipient field
     */
    public const COL_GIFT_RECIPIENT = 'carts.cart_gift_recipient';

    /**
     * the column name for the cart_date field
     */
    public const COL_CART_DATE = 'carts.cart_date';

    /**
     * the column name for the cart_insert field
     */
    public const COL_CART_INSERT = 'carts.cart_insert';

    /**
     * the column name for the cart_update field
     */
    public const COL_CART_UPDATE = 'carts.cart_update';

    /**
     * the column name for the cart_created field
     */
    public const COL_CART_CREATED = 'carts.cart_created';

    /**
     * the column name for the cart_updated field
     */
    public const COL_CART_UPDATED = 'carts.cart_updated';

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
        self::TYPE_PHPNAME       => ['Id', 'Uid', 'SiteId', 'AxysUserId', 'SellerId', 'CustomerId', 'Title', 'Type', 'Ip', 'Count', 'Amount', 'AsAGift', 'GiftRecipient', 'Date', 'Insert', 'Update', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'uid', 'siteId', 'axysUserId', 'sellerId', 'customerId', 'title', 'type', 'ip', 'count', 'amount', 'asAGift', 'giftRecipient', 'date', 'insert', 'update', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [CartTableMap::COL_CART_ID, CartTableMap::COL_CART_UID, CartTableMap::COL_SITE_ID, CartTableMap::COL_AXYS_USER_ID, CartTableMap::COL_CART_SELLER_ID, CartTableMap::COL_CUSTOMER_ID, CartTableMap::COL_CART_TITLE, CartTableMap::COL_CART_TYPE, CartTableMap::COL_CART_IP, CartTableMap::COL_CART_COUNT, CartTableMap::COL_CART_AMOUNT, CartTableMap::COL_AS_A_GIFT, CartTableMap::COL_GIFT_RECIPIENT, CartTableMap::COL_CART_DATE, CartTableMap::COL_CART_INSERT, CartTableMap::COL_CART_UPDATE, CartTableMap::COL_CART_CREATED, CartTableMap::COL_CART_UPDATED, ],
        self::TYPE_FIELDNAME     => ['cart_id', 'cart_uid', 'site_id', 'axys_user_id', 'cart_seller_id', 'customer_id', 'cart_title', 'cart_type', 'cart_ip', 'cart_count', 'cart_amount', 'cart_as_a_gift', 'cart_gift_recipient', 'cart_date', 'cart_insert', 'cart_update', 'cart_created', 'cart_updated', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'Uid' => 1, 'SiteId' => 2, 'AxysUserId' => 3, 'SellerId' => 4, 'CustomerId' => 5, 'Title' => 6, 'Type' => 7, 'Ip' => 8, 'Count' => 9, 'Amount' => 10, 'AsAGift' => 11, 'GiftRecipient' => 12, 'Date' => 13, 'Insert' => 14, 'Update' => 15, 'CreatedAt' => 16, 'UpdatedAt' => 17, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'uid' => 1, 'siteId' => 2, 'axysUserId' => 3, 'sellerId' => 4, 'customerId' => 5, 'title' => 6, 'type' => 7, 'ip' => 8, 'count' => 9, 'amount' => 10, 'asAGift' => 11, 'giftRecipient' => 12, 'date' => 13, 'insert' => 14, 'update' => 15, 'createdAt' => 16, 'updatedAt' => 17, ],
        self::TYPE_COLNAME       => [CartTableMap::COL_CART_ID => 0, CartTableMap::COL_CART_UID => 1, CartTableMap::COL_SITE_ID => 2, CartTableMap::COL_AXYS_USER_ID => 3, CartTableMap::COL_CART_SELLER_ID => 4, CartTableMap::COL_CUSTOMER_ID => 5, CartTableMap::COL_CART_TITLE => 6, CartTableMap::COL_CART_TYPE => 7, CartTableMap::COL_CART_IP => 8, CartTableMap::COL_CART_COUNT => 9, CartTableMap::COL_CART_AMOUNT => 10, CartTableMap::COL_AS_A_GIFT => 11, CartTableMap::COL_GIFT_RECIPIENT => 12, CartTableMap::COL_CART_DATE => 13, CartTableMap::COL_CART_INSERT => 14, CartTableMap::COL_CART_UPDATE => 15, CartTableMap::COL_CART_CREATED => 16, CartTableMap::COL_CART_UPDATED => 17, ],
        self::TYPE_FIELDNAME     => ['cart_id' => 0, 'cart_uid' => 1, 'site_id' => 2, 'axys_user_id' => 3, 'cart_seller_id' => 4, 'customer_id' => 5, 'cart_title' => 6, 'cart_type' => 7, 'cart_ip' => 8, 'cart_count' => 9, 'cart_amount' => 10, 'cart_as_a_gift' => 11, 'cart_gift_recipient' => 12, 'cart_date' => 13, 'cart_insert' => 14, 'cart_update' => 15, 'cart_created' => 16, 'cart_updated' => 17, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'CART_ID',
        'Cart.Id' => 'CART_ID',
        'id' => 'CART_ID',
        'cart.id' => 'CART_ID',
        'CartTableMap::COL_CART_ID' => 'CART_ID',
        'COL_CART_ID' => 'CART_ID',
        'cart_id' => 'CART_ID',
        'carts.cart_id' => 'CART_ID',
        'Uid' => 'CART_UID',
        'Cart.Uid' => 'CART_UID',
        'uid' => 'CART_UID',
        'cart.uid' => 'CART_UID',
        'CartTableMap::COL_CART_UID' => 'CART_UID',
        'COL_CART_UID' => 'CART_UID',
        'cart_uid' => 'CART_UID',
        'carts.cart_uid' => 'CART_UID',
        'SiteId' => 'SITE_ID',
        'Cart.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'cart.siteId' => 'SITE_ID',
        'CartTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'carts.site_id' => 'SITE_ID',
        'AxysUserId' => 'AXYS_USER_ID',
        'Cart.AxysUserId' => 'AXYS_USER_ID',
        'axysUserId' => 'AXYS_USER_ID',
        'cart.axysUserId' => 'AXYS_USER_ID',
        'CartTableMap::COL_AXYS_USER_ID' => 'AXYS_USER_ID',
        'COL_AXYS_USER_ID' => 'AXYS_USER_ID',
        'axys_user_id' => 'AXYS_USER_ID',
        'carts.axys_user_id' => 'AXYS_USER_ID',
        'SellerId' => 'CART_SELLER_ID',
        'Cart.SellerId' => 'CART_SELLER_ID',
        'sellerId' => 'CART_SELLER_ID',
        'cart.sellerId' => 'CART_SELLER_ID',
        'CartTableMap::COL_CART_SELLER_ID' => 'CART_SELLER_ID',
        'COL_CART_SELLER_ID' => 'CART_SELLER_ID',
        'cart_seller_id' => 'CART_SELLER_ID',
        'carts.cart_seller_id' => 'CART_SELLER_ID',
        'CustomerId' => 'CUSTOMER_ID',
        'Cart.CustomerId' => 'CUSTOMER_ID',
        'customerId' => 'CUSTOMER_ID',
        'cart.customerId' => 'CUSTOMER_ID',
        'CartTableMap::COL_CUSTOMER_ID' => 'CUSTOMER_ID',
        'COL_CUSTOMER_ID' => 'CUSTOMER_ID',
        'customer_id' => 'CUSTOMER_ID',
        'carts.customer_id' => 'CUSTOMER_ID',
        'Title' => 'CART_TITLE',
        'Cart.Title' => 'CART_TITLE',
        'title' => 'CART_TITLE',
        'cart.title' => 'CART_TITLE',
        'CartTableMap::COL_CART_TITLE' => 'CART_TITLE',
        'COL_CART_TITLE' => 'CART_TITLE',
        'cart_title' => 'CART_TITLE',
        'carts.cart_title' => 'CART_TITLE',
        'Type' => 'CART_TYPE',
        'Cart.Type' => 'CART_TYPE',
        'type' => 'CART_TYPE',
        'cart.type' => 'CART_TYPE',
        'CartTableMap::COL_CART_TYPE' => 'CART_TYPE',
        'COL_CART_TYPE' => 'CART_TYPE',
        'cart_type' => 'CART_TYPE',
        'carts.cart_type' => 'CART_TYPE',
        'Ip' => 'CART_IP',
        'Cart.Ip' => 'CART_IP',
        'ip' => 'CART_IP',
        'cart.ip' => 'CART_IP',
        'CartTableMap::COL_CART_IP' => 'CART_IP',
        'COL_CART_IP' => 'CART_IP',
        'cart_ip' => 'CART_IP',
        'carts.cart_ip' => 'CART_IP',
        'Count' => 'CART_COUNT',
        'Cart.Count' => 'CART_COUNT',
        'count' => 'CART_COUNT',
        'cart.count' => 'CART_COUNT',
        'CartTableMap::COL_CART_COUNT' => 'CART_COUNT',
        'COL_CART_COUNT' => 'CART_COUNT',
        'cart_count' => 'CART_COUNT',
        'carts.cart_count' => 'CART_COUNT',
        'Amount' => 'CART_AMOUNT',
        'Cart.Amount' => 'CART_AMOUNT',
        'amount' => 'CART_AMOUNT',
        'cart.amount' => 'CART_AMOUNT',
        'CartTableMap::COL_CART_AMOUNT' => 'CART_AMOUNT',
        'COL_CART_AMOUNT' => 'CART_AMOUNT',
        'cart_amount' => 'CART_AMOUNT',
        'carts.cart_amount' => 'CART_AMOUNT',
        'AsAGift' => 'CART_AS_A_GIFT',
        'Cart.AsAGift' => 'CART_AS_A_GIFT',
        'asAGift' => 'CART_AS_A_GIFT',
        'cart.asAGift' => 'CART_AS_A_GIFT',
        'CartTableMap::COL_AS_A_GIFT' => 'CART_AS_A_GIFT',
        'COL_AS_A_GIFT' => 'CART_AS_A_GIFT',
        'cart_as_a_gift' => 'CART_AS_A_GIFT',
        'carts.cart_as_a_gift' => 'CART_AS_A_GIFT',
        'GiftRecipient' => 'CART_GIFT_RECIPIENT',
        'Cart.GiftRecipient' => 'CART_GIFT_RECIPIENT',
        'giftRecipient' => 'CART_GIFT_RECIPIENT',
        'cart.giftRecipient' => 'CART_GIFT_RECIPIENT',
        'CartTableMap::COL_GIFT_RECIPIENT' => 'CART_GIFT_RECIPIENT',
        'COL_GIFT_RECIPIENT' => 'CART_GIFT_RECIPIENT',
        'cart_gift_recipient' => 'CART_GIFT_RECIPIENT',
        'carts.cart_gift_recipient' => 'CART_GIFT_RECIPIENT',
        'Date' => 'CART_DATE',
        'Cart.Date' => 'CART_DATE',
        'date' => 'CART_DATE',
        'cart.date' => 'CART_DATE',
        'CartTableMap::COL_CART_DATE' => 'CART_DATE',
        'COL_CART_DATE' => 'CART_DATE',
        'cart_date' => 'CART_DATE',
        'carts.cart_date' => 'CART_DATE',
        'Insert' => 'CART_INSERT',
        'Cart.Insert' => 'CART_INSERT',
        'insert' => 'CART_INSERT',
        'cart.insert' => 'CART_INSERT',
        'CartTableMap::COL_CART_INSERT' => 'CART_INSERT',
        'COL_CART_INSERT' => 'CART_INSERT',
        'cart_insert' => 'CART_INSERT',
        'carts.cart_insert' => 'CART_INSERT',
        'Update' => 'CART_UPDATE',
        'Cart.Update' => 'CART_UPDATE',
        'update' => 'CART_UPDATE',
        'cart.update' => 'CART_UPDATE',
        'CartTableMap::COL_CART_UPDATE' => 'CART_UPDATE',
        'COL_CART_UPDATE' => 'CART_UPDATE',
        'cart_update' => 'CART_UPDATE',
        'carts.cart_update' => 'CART_UPDATE',
        'CreatedAt' => 'CART_CREATED',
        'Cart.CreatedAt' => 'CART_CREATED',
        'createdAt' => 'CART_CREATED',
        'cart.createdAt' => 'CART_CREATED',
        'CartTableMap::COL_CART_CREATED' => 'CART_CREATED',
        'COL_CART_CREATED' => 'CART_CREATED',
        'cart_created' => 'CART_CREATED',
        'carts.cart_created' => 'CART_CREATED',
        'UpdatedAt' => 'CART_UPDATED',
        'Cart.UpdatedAt' => 'CART_UPDATED',
        'updatedAt' => 'CART_UPDATED',
        'cart.updatedAt' => 'CART_UPDATED',
        'CartTableMap::COL_CART_UPDATED' => 'CART_UPDATED',
        'COL_CART_UPDATED' => 'CART_UPDATED',
        'cart_updated' => 'CART_UPDATED',
        'carts.cart_updated' => 'CART_UPDATED',
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
        $this->setName('carts');
        $this->setPhpName('Cart');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Cart');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('cart_id', 'Id', 'INTEGER', true, 10, null);
        $this->addColumn('cart_uid', 'Uid', 'VARCHAR', false, 32, null);
        $this->addForeignKey('site_id', 'SiteId', 'INTEGER', 'sites', 'site_id', false, 10, null);
        $this->addForeignKey('axys_user_id', 'AxysUserId', 'INTEGER', 'axys_accounts', 'id', false, 10, null);
        $this->addColumn('cart_seller_id', 'SellerId', 'INTEGER', false, 10, null);
        $this->addColumn('customer_id', 'CustomerId', 'INTEGER', false, 10, null);
        $this->addColumn('cart_title', 'Title', 'VARCHAR', false, 128, null);
        $this->addColumn('cart_type', 'Type', 'VARCHAR', false, 4, '');
        $this->addColumn('cart_ip', 'Ip', 'LONGVARCHAR', false, null, null);
        $this->addColumn('cart_count', 'Count', 'INTEGER', false, 10, 0);
        $this->addColumn('cart_amount', 'Amount', 'INTEGER', false, 10, 0);
        $this->addColumn('cart_as_a_gift', 'AsAGift', 'VARCHAR', false, 16, null);
        $this->addColumn('cart_gift_recipient', 'GiftRecipient', 'INTEGER', false, 10, null);
        $this->addColumn('cart_date', 'Date', 'TIMESTAMP', false, null, null);
        $this->addColumn('cart_insert', 'Insert', 'TIMESTAMP', false, null, 'CURRENT_TIMESTAMP');
        $this->addColumn('cart_update', 'Update', 'TIMESTAMP', false, null, null);
        $this->addColumn('cart_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('cart_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
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
        $this->addRelation('AxysAccount', '\\Model\\AxysAccount', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':axys_user_id',
    1 => ':id',
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
            'timestampable' => ['create_column' => 'cart_created', 'update_column' => 'cart_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? CartTableMap::CLASS_DEFAULT : CartTableMap::OM_CLASS;
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
     * @return array (Cart object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = CartTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = CartTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + CartTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = CartTableMap::OM_CLASS;
            /** @var Cart $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            CartTableMap::addInstanceToPool($obj, $key);
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
            $key = CartTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = CartTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Cart $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                CartTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(CartTableMap::COL_CART_ID);
            $criteria->addSelectColumn(CartTableMap::COL_CART_UID);
            $criteria->addSelectColumn(CartTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(CartTableMap::COL_AXYS_USER_ID);
            $criteria->addSelectColumn(CartTableMap::COL_CART_SELLER_ID);
            $criteria->addSelectColumn(CartTableMap::COL_CUSTOMER_ID);
            $criteria->addSelectColumn(CartTableMap::COL_CART_TITLE);
            $criteria->addSelectColumn(CartTableMap::COL_CART_TYPE);
            $criteria->addSelectColumn(CartTableMap::COL_CART_IP);
            $criteria->addSelectColumn(CartTableMap::COL_CART_COUNT);
            $criteria->addSelectColumn(CartTableMap::COL_CART_AMOUNT);
            $criteria->addSelectColumn(CartTableMap::COL_AS_A_GIFT);
            $criteria->addSelectColumn(CartTableMap::COL_GIFT_RECIPIENT);
            $criteria->addSelectColumn(CartTableMap::COL_CART_DATE);
            $criteria->addSelectColumn(CartTableMap::COL_CART_INSERT);
            $criteria->addSelectColumn(CartTableMap::COL_CART_UPDATE);
            $criteria->addSelectColumn(CartTableMap::COL_CART_CREATED);
            $criteria->addSelectColumn(CartTableMap::COL_CART_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.cart_id');
            $criteria->addSelectColumn($alias . '.cart_uid');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.axys_user_id');
            $criteria->addSelectColumn($alias . '.cart_seller_id');
            $criteria->addSelectColumn($alias . '.customer_id');
            $criteria->addSelectColumn($alias . '.cart_title');
            $criteria->addSelectColumn($alias . '.cart_type');
            $criteria->addSelectColumn($alias . '.cart_ip');
            $criteria->addSelectColumn($alias . '.cart_count');
            $criteria->addSelectColumn($alias . '.cart_amount');
            $criteria->addSelectColumn($alias . '.cart_as_a_gift');
            $criteria->addSelectColumn($alias . '.cart_gift_recipient');
            $criteria->addSelectColumn($alias . '.cart_date');
            $criteria->addSelectColumn($alias . '.cart_insert');
            $criteria->addSelectColumn($alias . '.cart_update');
            $criteria->addSelectColumn($alias . '.cart_created');
            $criteria->addSelectColumn($alias . '.cart_updated');
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
            $criteria->removeSelectColumn(CartTableMap::COL_CART_ID);
            $criteria->removeSelectColumn(CartTableMap::COL_CART_UID);
            $criteria->removeSelectColumn(CartTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(CartTableMap::COL_AXYS_USER_ID);
            $criteria->removeSelectColumn(CartTableMap::COL_CART_SELLER_ID);
            $criteria->removeSelectColumn(CartTableMap::COL_CUSTOMER_ID);
            $criteria->removeSelectColumn(CartTableMap::COL_CART_TITLE);
            $criteria->removeSelectColumn(CartTableMap::COL_CART_TYPE);
            $criteria->removeSelectColumn(CartTableMap::COL_CART_IP);
            $criteria->removeSelectColumn(CartTableMap::COL_CART_COUNT);
            $criteria->removeSelectColumn(CartTableMap::COL_CART_AMOUNT);
            $criteria->removeSelectColumn(CartTableMap::COL_AS_A_GIFT);
            $criteria->removeSelectColumn(CartTableMap::COL_GIFT_RECIPIENT);
            $criteria->removeSelectColumn(CartTableMap::COL_CART_DATE);
            $criteria->removeSelectColumn(CartTableMap::COL_CART_INSERT);
            $criteria->removeSelectColumn(CartTableMap::COL_CART_UPDATE);
            $criteria->removeSelectColumn(CartTableMap::COL_CART_CREATED);
            $criteria->removeSelectColumn(CartTableMap::COL_CART_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.cart_id');
            $criteria->removeSelectColumn($alias . '.cart_uid');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.axys_user_id');
            $criteria->removeSelectColumn($alias . '.cart_seller_id');
            $criteria->removeSelectColumn($alias . '.customer_id');
            $criteria->removeSelectColumn($alias . '.cart_title');
            $criteria->removeSelectColumn($alias . '.cart_type');
            $criteria->removeSelectColumn($alias . '.cart_ip');
            $criteria->removeSelectColumn($alias . '.cart_count');
            $criteria->removeSelectColumn($alias . '.cart_amount');
            $criteria->removeSelectColumn($alias . '.cart_as_a_gift');
            $criteria->removeSelectColumn($alias . '.cart_gift_recipient');
            $criteria->removeSelectColumn($alias . '.cart_date');
            $criteria->removeSelectColumn($alias . '.cart_insert');
            $criteria->removeSelectColumn($alias . '.cart_update');
            $criteria->removeSelectColumn($alias . '.cart_created');
            $criteria->removeSelectColumn($alias . '.cart_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(CartTableMap::DATABASE_NAME)->getTable(CartTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Cart or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Cart object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(CartTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Cart) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(CartTableMap::DATABASE_NAME);
            $criteria->add(CartTableMap::COL_CART_ID, (array) $values, Criteria::IN);
        }

        $query = CartQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            CartTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                CartTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the carts table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return CartQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Cart or Criteria object.
     *
     * @param mixed $criteria Criteria or Cart object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CartTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Cart object
        }

        if ($criteria->containsKey(CartTableMap::COL_CART_ID) && $criteria->keyContainsValue(CartTableMap::COL_CART_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.CartTableMap::COL_CART_ID.')');
        }


        // Set the correct dbName
        $query = CartQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
