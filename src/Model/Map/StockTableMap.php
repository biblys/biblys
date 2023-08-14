<?php

namespace Model\Map;

use Model\Stock;
use Model\StockQuery;
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
 * This class defines the structure of the 'stock' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class StockTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.StockTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'stock';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Stock';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Stock';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Stock';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 41;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 41;

    /**
     * the column name for the stock_id field
     */
    public const COL_STOCK_ID = 'stock.stock_id';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'stock.site_id';

    /**
     * the column name for the article_id field
     */
    public const COL_ARTICLE_ID = 'stock.article_id';

    /**
     * the column name for the campaign_id field
     */
    public const COL_CAMPAIGN_ID = 'stock.campaign_id';

    /**
     * the column name for the reward_id field
     */
    public const COL_REWARD_ID = 'stock.reward_id';

    /**
     * the column name for the axys_account_id field
     */
    public const COL_AXYS_ACCOUNT_ID = 'stock.axys_account_id';

    /**
     * the column name for the customer_id field
     */
    public const COL_CUSTOMER_ID = 'stock.customer_id';

    /**
     * the column name for the wish_id field
     */
    public const COL_WISH_ID = 'stock.wish_id';

    /**
     * the column name for the cart_id field
     */
    public const COL_CART_ID = 'stock.cart_id';

    /**
     * the column name for the order_id field
     */
    public const COL_ORDER_ID = 'stock.order_id';

    /**
     * the column name for the coupon_id field
     */
    public const COL_COUPON_ID = 'stock.coupon_id';

    /**
     * the column name for the stock_shop field
     */
    public const COL_STOCK_SHOP = 'stock.stock_shop';

    /**
     * the column name for the stock_invoice field
     */
    public const COL_STOCK_INVOICE = 'stock.stock_invoice';

    /**
     * the column name for the stock_depot field
     */
    public const COL_STOCK_DEPOT = 'stock.stock_depot';

    /**
     * the column name for the stock_stockage field
     */
    public const COL_STOCK_STOCKAGE = 'stock.stock_stockage';

    /**
     * the column name for the stock_condition field
     */
    public const COL_STOCK_CONDITION = 'stock.stock_condition';

    /**
     * the column name for the stock_condition_details field
     */
    public const COL_STOCK_CONDITION_DETAILS = 'stock.stock_condition_details';

    /**
     * the column name for the stock_purchase_price field
     */
    public const COL_STOCK_PURCHASE_PRICE = 'stock.stock_purchase_price';

    /**
     * the column name for the stock_selling_price field
     */
    public const COL_STOCK_SELLING_PRICE = 'stock.stock_selling_price';

    /**
     * the column name for the stock_selling_price2 field
     */
    public const COL_STOCK_SELLING_PRICE2 = 'stock.stock_selling_price2';

    /**
     * the column name for the stock_selling_price_saved field
     */
    public const COL_STOCK_SELLING_PRICE_SAVED = 'stock.stock_selling_price_saved';

    /**
     * the column name for the stock_selling_price_ht field
     */
    public const COL_STOCK_SELLING_PRICE_HT = 'stock.stock_selling_price_ht';

    /**
     * the column name for the stock_selling_price_tva field
     */
    public const COL_STOCK_SELLING_PRICE_TVA = 'stock.stock_selling_price_tva';

    /**
     * the column name for the stock_tva_rate field
     */
    public const COL_STOCK_TVA_RATE = 'stock.stock_tva_rate';

    /**
     * the column name for the stock_weight field
     */
    public const COL_STOCK_WEIGHT = 'stock.stock_weight';

    /**
     * the column name for the stock_pub_year field
     */
    public const COL_STOCK_PUB_YEAR = 'stock.stock_pub_year';

    /**
     * the column name for the stock_allow_predownload field
     */
    public const COL_STOCK_ALLOW_PREDOWNLOAD = 'stock.stock_allow_predownload';

    /**
     * the column name for the stock_photo_version field
     */
    public const COL_STOCK_PHOTO_VERSION = 'stock.stock_photo_version';

    /**
     * the column name for the stock_purchase_date field
     */
    public const COL_STOCK_PURCHASE_DATE = 'stock.stock_purchase_date';

    /**
     * the column name for the stock_onsale_date field
     */
    public const COL_STOCK_ONSALE_DATE = 'stock.stock_onsale_date';

    /**
     * the column name for the stock_cart_date field
     */
    public const COL_STOCK_CART_DATE = 'stock.stock_cart_date';

    /**
     * the column name for the stock_selling_date field
     */
    public const COL_STOCK_SELLING_DATE = 'stock.stock_selling_date';

    /**
     * the column name for the stock_return_date field
     */
    public const COL_STOCK_RETURN_DATE = 'stock.stock_return_date';

    /**
     * the column name for the stock_lost_date field
     */
    public const COL_STOCK_LOST_DATE = 'stock.stock_lost_date';

    /**
     * the column name for the stock_media_ok field
     */
    public const COL_STOCK_MEDIA_OK = 'stock.stock_media_ok';

    /**
     * the column name for the stock_file_updated field
     */
    public const COL_STOCK_FILE_UPDATED = 'stock.stock_file_updated';

    /**
     * the column name for the stock_insert field
     */
    public const COL_STOCK_INSERT = 'stock.stock_insert';

    /**
     * the column name for the stock_update field
     */
    public const COL_STOCK_UPDATE = 'stock.stock_update';

    /**
     * the column name for the stock_dl field
     */
    public const COL_STOCK_DL = 'stock.stock_dl';

    /**
     * the column name for the stock_created field
     */
    public const COL_STOCK_CREATED = 'stock.stock_created';

    /**
     * the column name for the stock_updated field
     */
    public const COL_STOCK_UPDATED = 'stock.stock_updated';

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
        self::TYPE_PHPNAME       => ['Id', 'SiteId', 'ArticleId', 'CampaignId', 'RewardId', 'AxysAccountId', 'CustomerId', 'WishId', 'CartId', 'OrderId', 'CouponId', 'Shop', 'Invoice', 'Depot', 'Stockage', 'Condition', 'ConditionDetails', 'PurchasePrice', 'SellingPrice', 'SellingPrice2', 'SellingPriceSaved', 'SellingPriceHt', 'SellingPriceTva', 'TvaRate', 'Weight', 'PubYear', 'AllowPredownload', 'PhotoVersion', 'PurchaseDate', 'OnsaleDate', 'CartDate', 'SellingDate', 'ReturnDate', 'LostDate', 'MediaOk', 'FileUpdated', 'Insert', 'Update', 'Dl', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'siteId', 'articleId', 'campaignId', 'rewardId', 'axysAccountId', 'customerId', 'wishId', 'cartId', 'orderId', 'couponId', 'shop', 'invoice', 'depot', 'stockage', 'condition', 'conditionDetails', 'purchasePrice', 'sellingPrice', 'sellingPrice2', 'sellingPriceSaved', 'sellingPriceHt', 'sellingPriceTva', 'tvaRate', 'weight', 'pubYear', 'allowPredownload', 'photoVersion', 'purchaseDate', 'onsaleDate', 'cartDate', 'sellingDate', 'returnDate', 'lostDate', 'mediaOk', 'fileUpdated', 'insert', 'update', 'dl', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [StockTableMap::COL_STOCK_ID, StockTableMap::COL_SITE_ID, StockTableMap::COL_ARTICLE_ID, StockTableMap::COL_CAMPAIGN_ID, StockTableMap::COL_REWARD_ID, StockTableMap::COL_AXYS_ACCOUNT_ID, StockTableMap::COL_CUSTOMER_ID, StockTableMap::COL_WISH_ID, StockTableMap::COL_CART_ID, StockTableMap::COL_ORDER_ID, StockTableMap::COL_COUPON_ID, StockTableMap::COL_STOCK_SHOP, StockTableMap::COL_STOCK_INVOICE, StockTableMap::COL_STOCK_DEPOT, StockTableMap::COL_STOCK_STOCKAGE, StockTableMap::COL_STOCK_CONDITION, StockTableMap::COL_STOCK_CONDITION_DETAILS, StockTableMap::COL_STOCK_PURCHASE_PRICE, StockTableMap::COL_STOCK_SELLING_PRICE, StockTableMap::COL_STOCK_SELLING_PRICE2, StockTableMap::COL_STOCK_SELLING_PRICE_SAVED, StockTableMap::COL_STOCK_SELLING_PRICE_HT, StockTableMap::COL_STOCK_SELLING_PRICE_TVA, StockTableMap::COL_STOCK_TVA_RATE, StockTableMap::COL_STOCK_WEIGHT, StockTableMap::COL_STOCK_PUB_YEAR, StockTableMap::COL_STOCK_ALLOW_PREDOWNLOAD, StockTableMap::COL_STOCK_PHOTO_VERSION, StockTableMap::COL_STOCK_PURCHASE_DATE, StockTableMap::COL_STOCK_ONSALE_DATE, StockTableMap::COL_STOCK_CART_DATE, StockTableMap::COL_STOCK_SELLING_DATE, StockTableMap::COL_STOCK_RETURN_DATE, StockTableMap::COL_STOCK_LOST_DATE, StockTableMap::COL_STOCK_MEDIA_OK, StockTableMap::COL_STOCK_FILE_UPDATED, StockTableMap::COL_STOCK_INSERT, StockTableMap::COL_STOCK_UPDATE, StockTableMap::COL_STOCK_DL, StockTableMap::COL_STOCK_CREATED, StockTableMap::COL_STOCK_UPDATED, ],
        self::TYPE_FIELDNAME     => ['stock_id', 'site_id', 'article_id', 'campaign_id', 'reward_id', 'axys_account_id', 'customer_id', 'wish_id', 'cart_id', 'order_id', 'coupon_id', 'stock_shop', 'stock_invoice', 'stock_depot', 'stock_stockage', 'stock_condition', 'stock_condition_details', 'stock_purchase_price', 'stock_selling_price', 'stock_selling_price2', 'stock_selling_price_saved', 'stock_selling_price_ht', 'stock_selling_price_tva', 'stock_tva_rate', 'stock_weight', 'stock_pub_year', 'stock_allow_predownload', 'stock_photo_version', 'stock_purchase_date', 'stock_onsale_date', 'stock_cart_date', 'stock_selling_date', 'stock_return_date', 'stock_lost_date', 'stock_media_ok', 'stock_file_updated', 'stock_insert', 'stock_update', 'stock_dl', 'stock_created', 'stock_updated', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'SiteId' => 1, 'ArticleId' => 2, 'CampaignId' => 3, 'RewardId' => 4, 'AxysAccountId' => 5, 'CustomerId' => 6, 'WishId' => 7, 'CartId' => 8, 'OrderId' => 9, 'CouponId' => 10, 'Shop' => 11, 'Invoice' => 12, 'Depot' => 13, 'Stockage' => 14, 'Condition' => 15, 'ConditionDetails' => 16, 'PurchasePrice' => 17, 'SellingPrice' => 18, 'SellingPrice2' => 19, 'SellingPriceSaved' => 20, 'SellingPriceHt' => 21, 'SellingPriceTva' => 22, 'TvaRate' => 23, 'Weight' => 24, 'PubYear' => 25, 'AllowPredownload' => 26, 'PhotoVersion' => 27, 'PurchaseDate' => 28, 'OnsaleDate' => 29, 'CartDate' => 30, 'SellingDate' => 31, 'ReturnDate' => 32, 'LostDate' => 33, 'MediaOk' => 34, 'FileUpdated' => 35, 'Insert' => 36, 'Update' => 37, 'Dl' => 38, 'CreatedAt' => 39, 'UpdatedAt' => 40, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'siteId' => 1, 'articleId' => 2, 'campaignId' => 3, 'rewardId' => 4, 'axysAccountId' => 5, 'customerId' => 6, 'wishId' => 7, 'cartId' => 8, 'orderId' => 9, 'couponId' => 10, 'shop' => 11, 'invoice' => 12, 'depot' => 13, 'stockage' => 14, 'condition' => 15, 'conditionDetails' => 16, 'purchasePrice' => 17, 'sellingPrice' => 18, 'sellingPrice2' => 19, 'sellingPriceSaved' => 20, 'sellingPriceHt' => 21, 'sellingPriceTva' => 22, 'tvaRate' => 23, 'weight' => 24, 'pubYear' => 25, 'allowPredownload' => 26, 'photoVersion' => 27, 'purchaseDate' => 28, 'onsaleDate' => 29, 'cartDate' => 30, 'sellingDate' => 31, 'returnDate' => 32, 'lostDate' => 33, 'mediaOk' => 34, 'fileUpdated' => 35, 'insert' => 36, 'update' => 37, 'dl' => 38, 'createdAt' => 39, 'updatedAt' => 40, ],
        self::TYPE_COLNAME       => [StockTableMap::COL_STOCK_ID => 0, StockTableMap::COL_SITE_ID => 1, StockTableMap::COL_ARTICLE_ID => 2, StockTableMap::COL_CAMPAIGN_ID => 3, StockTableMap::COL_REWARD_ID => 4, StockTableMap::COL_AXYS_ACCOUNT_ID => 5, StockTableMap::COL_CUSTOMER_ID => 6, StockTableMap::COL_WISH_ID => 7, StockTableMap::COL_CART_ID => 8, StockTableMap::COL_ORDER_ID => 9, StockTableMap::COL_COUPON_ID => 10, StockTableMap::COL_STOCK_SHOP => 11, StockTableMap::COL_STOCK_INVOICE => 12, StockTableMap::COL_STOCK_DEPOT => 13, StockTableMap::COL_STOCK_STOCKAGE => 14, StockTableMap::COL_STOCK_CONDITION => 15, StockTableMap::COL_STOCK_CONDITION_DETAILS => 16, StockTableMap::COL_STOCK_PURCHASE_PRICE => 17, StockTableMap::COL_STOCK_SELLING_PRICE => 18, StockTableMap::COL_STOCK_SELLING_PRICE2 => 19, StockTableMap::COL_STOCK_SELLING_PRICE_SAVED => 20, StockTableMap::COL_STOCK_SELLING_PRICE_HT => 21, StockTableMap::COL_STOCK_SELLING_PRICE_TVA => 22, StockTableMap::COL_STOCK_TVA_RATE => 23, StockTableMap::COL_STOCK_WEIGHT => 24, StockTableMap::COL_STOCK_PUB_YEAR => 25, StockTableMap::COL_STOCK_ALLOW_PREDOWNLOAD => 26, StockTableMap::COL_STOCK_PHOTO_VERSION => 27, StockTableMap::COL_STOCK_PURCHASE_DATE => 28, StockTableMap::COL_STOCK_ONSALE_DATE => 29, StockTableMap::COL_STOCK_CART_DATE => 30, StockTableMap::COL_STOCK_SELLING_DATE => 31, StockTableMap::COL_STOCK_RETURN_DATE => 32, StockTableMap::COL_STOCK_LOST_DATE => 33, StockTableMap::COL_STOCK_MEDIA_OK => 34, StockTableMap::COL_STOCK_FILE_UPDATED => 35, StockTableMap::COL_STOCK_INSERT => 36, StockTableMap::COL_STOCK_UPDATE => 37, StockTableMap::COL_STOCK_DL => 38, StockTableMap::COL_STOCK_CREATED => 39, StockTableMap::COL_STOCK_UPDATED => 40, ],
        self::TYPE_FIELDNAME     => ['stock_id' => 0, 'site_id' => 1, 'article_id' => 2, 'campaign_id' => 3, 'reward_id' => 4, 'axys_account_id' => 5, 'customer_id' => 6, 'wish_id' => 7, 'cart_id' => 8, 'order_id' => 9, 'coupon_id' => 10, 'stock_shop' => 11, 'stock_invoice' => 12, 'stock_depot' => 13, 'stock_stockage' => 14, 'stock_condition' => 15, 'stock_condition_details' => 16, 'stock_purchase_price' => 17, 'stock_selling_price' => 18, 'stock_selling_price2' => 19, 'stock_selling_price_saved' => 20, 'stock_selling_price_ht' => 21, 'stock_selling_price_tva' => 22, 'stock_tva_rate' => 23, 'stock_weight' => 24, 'stock_pub_year' => 25, 'stock_allow_predownload' => 26, 'stock_photo_version' => 27, 'stock_purchase_date' => 28, 'stock_onsale_date' => 29, 'stock_cart_date' => 30, 'stock_selling_date' => 31, 'stock_return_date' => 32, 'stock_lost_date' => 33, 'stock_media_ok' => 34, 'stock_file_updated' => 35, 'stock_insert' => 36, 'stock_update' => 37, 'stock_dl' => 38, 'stock_created' => 39, 'stock_updated' => 40, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'STOCK_ID',
        'Stock.Id' => 'STOCK_ID',
        'id' => 'STOCK_ID',
        'stock.id' => 'STOCK_ID',
        'StockTableMap::COL_STOCK_ID' => 'STOCK_ID',
        'COL_STOCK_ID' => 'STOCK_ID',
        'stock_id' => 'STOCK_ID',
        'stock.stock_id' => 'STOCK_ID',
        'SiteId' => 'SITE_ID',
        'Stock.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'stock.siteId' => 'SITE_ID',
        'StockTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'stock.site_id' => 'SITE_ID',
        'ArticleId' => 'ARTICLE_ID',
        'Stock.ArticleId' => 'ARTICLE_ID',
        'articleId' => 'ARTICLE_ID',
        'stock.articleId' => 'ARTICLE_ID',
        'StockTableMap::COL_ARTICLE_ID' => 'ARTICLE_ID',
        'COL_ARTICLE_ID' => 'ARTICLE_ID',
        'article_id' => 'ARTICLE_ID',
        'stock.article_id' => 'ARTICLE_ID',
        'CampaignId' => 'CAMPAIGN_ID',
        'Stock.CampaignId' => 'CAMPAIGN_ID',
        'campaignId' => 'CAMPAIGN_ID',
        'stock.campaignId' => 'CAMPAIGN_ID',
        'StockTableMap::COL_CAMPAIGN_ID' => 'CAMPAIGN_ID',
        'COL_CAMPAIGN_ID' => 'CAMPAIGN_ID',
        'campaign_id' => 'CAMPAIGN_ID',
        'stock.campaign_id' => 'CAMPAIGN_ID',
        'RewardId' => 'REWARD_ID',
        'Stock.RewardId' => 'REWARD_ID',
        'rewardId' => 'REWARD_ID',
        'stock.rewardId' => 'REWARD_ID',
        'StockTableMap::COL_REWARD_ID' => 'REWARD_ID',
        'COL_REWARD_ID' => 'REWARD_ID',
        'reward_id' => 'REWARD_ID',
        'stock.reward_id' => 'REWARD_ID',
        'AxysAccountId' => 'AXYS_ACCOUNT_ID',
        'Stock.AxysAccountId' => 'AXYS_ACCOUNT_ID',
        'axysAccountId' => 'AXYS_ACCOUNT_ID',
        'stock.axysAccountId' => 'AXYS_ACCOUNT_ID',
        'StockTableMap::COL_AXYS_ACCOUNT_ID' => 'AXYS_ACCOUNT_ID',
        'COL_AXYS_ACCOUNT_ID' => 'AXYS_ACCOUNT_ID',
        'axys_account_id' => 'AXYS_ACCOUNT_ID',
        'stock.axys_account_id' => 'AXYS_ACCOUNT_ID',
        'CustomerId' => 'CUSTOMER_ID',
        'Stock.CustomerId' => 'CUSTOMER_ID',
        'customerId' => 'CUSTOMER_ID',
        'stock.customerId' => 'CUSTOMER_ID',
        'StockTableMap::COL_CUSTOMER_ID' => 'CUSTOMER_ID',
        'COL_CUSTOMER_ID' => 'CUSTOMER_ID',
        'customer_id' => 'CUSTOMER_ID',
        'stock.customer_id' => 'CUSTOMER_ID',
        'WishId' => 'WISH_ID',
        'Stock.WishId' => 'WISH_ID',
        'wishId' => 'WISH_ID',
        'stock.wishId' => 'WISH_ID',
        'StockTableMap::COL_WISH_ID' => 'WISH_ID',
        'COL_WISH_ID' => 'WISH_ID',
        'wish_id' => 'WISH_ID',
        'stock.wish_id' => 'WISH_ID',
        'CartId' => 'CART_ID',
        'Stock.CartId' => 'CART_ID',
        'cartId' => 'CART_ID',
        'stock.cartId' => 'CART_ID',
        'StockTableMap::COL_CART_ID' => 'CART_ID',
        'COL_CART_ID' => 'CART_ID',
        'cart_id' => 'CART_ID',
        'stock.cart_id' => 'CART_ID',
        'OrderId' => 'ORDER_ID',
        'Stock.OrderId' => 'ORDER_ID',
        'orderId' => 'ORDER_ID',
        'stock.orderId' => 'ORDER_ID',
        'StockTableMap::COL_ORDER_ID' => 'ORDER_ID',
        'COL_ORDER_ID' => 'ORDER_ID',
        'order_id' => 'ORDER_ID',
        'stock.order_id' => 'ORDER_ID',
        'CouponId' => 'COUPON_ID',
        'Stock.CouponId' => 'COUPON_ID',
        'couponId' => 'COUPON_ID',
        'stock.couponId' => 'COUPON_ID',
        'StockTableMap::COL_COUPON_ID' => 'COUPON_ID',
        'COL_COUPON_ID' => 'COUPON_ID',
        'coupon_id' => 'COUPON_ID',
        'stock.coupon_id' => 'COUPON_ID',
        'Shop' => 'STOCK_SHOP',
        'Stock.Shop' => 'STOCK_SHOP',
        'shop' => 'STOCK_SHOP',
        'stock.shop' => 'STOCK_SHOP',
        'StockTableMap::COL_STOCK_SHOP' => 'STOCK_SHOP',
        'COL_STOCK_SHOP' => 'STOCK_SHOP',
        'stock_shop' => 'STOCK_SHOP',
        'stock.stock_shop' => 'STOCK_SHOP',
        'Invoice' => 'STOCK_INVOICE',
        'Stock.Invoice' => 'STOCK_INVOICE',
        'invoice' => 'STOCK_INVOICE',
        'stock.invoice' => 'STOCK_INVOICE',
        'StockTableMap::COL_STOCK_INVOICE' => 'STOCK_INVOICE',
        'COL_STOCK_INVOICE' => 'STOCK_INVOICE',
        'stock_invoice' => 'STOCK_INVOICE',
        'stock.stock_invoice' => 'STOCK_INVOICE',
        'Depot' => 'STOCK_DEPOT',
        'Stock.Depot' => 'STOCK_DEPOT',
        'depot' => 'STOCK_DEPOT',
        'stock.depot' => 'STOCK_DEPOT',
        'StockTableMap::COL_STOCK_DEPOT' => 'STOCK_DEPOT',
        'COL_STOCK_DEPOT' => 'STOCK_DEPOT',
        'stock_depot' => 'STOCK_DEPOT',
        'stock.stock_depot' => 'STOCK_DEPOT',
        'Stockage' => 'STOCK_STOCKAGE',
        'Stock.Stockage' => 'STOCK_STOCKAGE',
        'stockage' => 'STOCK_STOCKAGE',
        'stock.stockage' => 'STOCK_STOCKAGE',
        'StockTableMap::COL_STOCK_STOCKAGE' => 'STOCK_STOCKAGE',
        'COL_STOCK_STOCKAGE' => 'STOCK_STOCKAGE',
        'stock_stockage' => 'STOCK_STOCKAGE',
        'stock.stock_stockage' => 'STOCK_STOCKAGE',
        'Condition' => 'STOCK_CONDITION',
        'Stock.Condition' => 'STOCK_CONDITION',
        'condition' => 'STOCK_CONDITION',
        'stock.condition' => 'STOCK_CONDITION',
        'StockTableMap::COL_STOCK_CONDITION' => 'STOCK_CONDITION',
        'COL_STOCK_CONDITION' => 'STOCK_CONDITION',
        'stock_condition' => 'STOCK_CONDITION',
        'stock.stock_condition' => 'STOCK_CONDITION',
        'ConditionDetails' => 'STOCK_CONDITION_DETAILS',
        'Stock.ConditionDetails' => 'STOCK_CONDITION_DETAILS',
        'conditionDetails' => 'STOCK_CONDITION_DETAILS',
        'stock.conditionDetails' => 'STOCK_CONDITION_DETAILS',
        'StockTableMap::COL_STOCK_CONDITION_DETAILS' => 'STOCK_CONDITION_DETAILS',
        'COL_STOCK_CONDITION_DETAILS' => 'STOCK_CONDITION_DETAILS',
        'stock_condition_details' => 'STOCK_CONDITION_DETAILS',
        'stock.stock_condition_details' => 'STOCK_CONDITION_DETAILS',
        'PurchasePrice' => 'STOCK_PURCHASE_PRICE',
        'Stock.PurchasePrice' => 'STOCK_PURCHASE_PRICE',
        'purchasePrice' => 'STOCK_PURCHASE_PRICE',
        'stock.purchasePrice' => 'STOCK_PURCHASE_PRICE',
        'StockTableMap::COL_STOCK_PURCHASE_PRICE' => 'STOCK_PURCHASE_PRICE',
        'COL_STOCK_PURCHASE_PRICE' => 'STOCK_PURCHASE_PRICE',
        'stock_purchase_price' => 'STOCK_PURCHASE_PRICE',
        'stock.stock_purchase_price' => 'STOCK_PURCHASE_PRICE',
        'SellingPrice' => 'STOCK_SELLING_PRICE',
        'Stock.SellingPrice' => 'STOCK_SELLING_PRICE',
        'sellingPrice' => 'STOCK_SELLING_PRICE',
        'stock.sellingPrice' => 'STOCK_SELLING_PRICE',
        'StockTableMap::COL_STOCK_SELLING_PRICE' => 'STOCK_SELLING_PRICE',
        'COL_STOCK_SELLING_PRICE' => 'STOCK_SELLING_PRICE',
        'stock_selling_price' => 'STOCK_SELLING_PRICE',
        'stock.stock_selling_price' => 'STOCK_SELLING_PRICE',
        'SellingPrice2' => 'STOCK_SELLING_PRICE2',
        'Stock.SellingPrice2' => 'STOCK_SELLING_PRICE2',
        'sellingPrice2' => 'STOCK_SELLING_PRICE2',
        'stock.sellingPrice2' => 'STOCK_SELLING_PRICE2',
        'StockTableMap::COL_STOCK_SELLING_PRICE2' => 'STOCK_SELLING_PRICE2',
        'COL_STOCK_SELLING_PRICE2' => 'STOCK_SELLING_PRICE2',
        'stock_selling_price2' => 'STOCK_SELLING_PRICE2',
        'stock.stock_selling_price2' => 'STOCK_SELLING_PRICE2',
        'SellingPriceSaved' => 'STOCK_SELLING_PRICE_SAVED',
        'Stock.SellingPriceSaved' => 'STOCK_SELLING_PRICE_SAVED',
        'sellingPriceSaved' => 'STOCK_SELLING_PRICE_SAVED',
        'stock.sellingPriceSaved' => 'STOCK_SELLING_PRICE_SAVED',
        'StockTableMap::COL_STOCK_SELLING_PRICE_SAVED' => 'STOCK_SELLING_PRICE_SAVED',
        'COL_STOCK_SELLING_PRICE_SAVED' => 'STOCK_SELLING_PRICE_SAVED',
        'stock_selling_price_saved' => 'STOCK_SELLING_PRICE_SAVED',
        'stock.stock_selling_price_saved' => 'STOCK_SELLING_PRICE_SAVED',
        'SellingPriceHt' => 'STOCK_SELLING_PRICE_HT',
        'Stock.SellingPriceHt' => 'STOCK_SELLING_PRICE_HT',
        'sellingPriceHt' => 'STOCK_SELLING_PRICE_HT',
        'stock.sellingPriceHt' => 'STOCK_SELLING_PRICE_HT',
        'StockTableMap::COL_STOCK_SELLING_PRICE_HT' => 'STOCK_SELLING_PRICE_HT',
        'COL_STOCK_SELLING_PRICE_HT' => 'STOCK_SELLING_PRICE_HT',
        'stock_selling_price_ht' => 'STOCK_SELLING_PRICE_HT',
        'stock.stock_selling_price_ht' => 'STOCK_SELLING_PRICE_HT',
        'SellingPriceTva' => 'STOCK_SELLING_PRICE_TVA',
        'Stock.SellingPriceTva' => 'STOCK_SELLING_PRICE_TVA',
        'sellingPriceTva' => 'STOCK_SELLING_PRICE_TVA',
        'stock.sellingPriceTva' => 'STOCK_SELLING_PRICE_TVA',
        'StockTableMap::COL_STOCK_SELLING_PRICE_TVA' => 'STOCK_SELLING_PRICE_TVA',
        'COL_STOCK_SELLING_PRICE_TVA' => 'STOCK_SELLING_PRICE_TVA',
        'stock_selling_price_tva' => 'STOCK_SELLING_PRICE_TVA',
        'stock.stock_selling_price_tva' => 'STOCK_SELLING_PRICE_TVA',
        'TvaRate' => 'STOCK_TVA_RATE',
        'Stock.TvaRate' => 'STOCK_TVA_RATE',
        'tvaRate' => 'STOCK_TVA_RATE',
        'stock.tvaRate' => 'STOCK_TVA_RATE',
        'StockTableMap::COL_STOCK_TVA_RATE' => 'STOCK_TVA_RATE',
        'COL_STOCK_TVA_RATE' => 'STOCK_TVA_RATE',
        'stock_tva_rate' => 'STOCK_TVA_RATE',
        'stock.stock_tva_rate' => 'STOCK_TVA_RATE',
        'Weight' => 'STOCK_WEIGHT',
        'Stock.Weight' => 'STOCK_WEIGHT',
        'weight' => 'STOCK_WEIGHT',
        'stock.weight' => 'STOCK_WEIGHT',
        'StockTableMap::COL_STOCK_WEIGHT' => 'STOCK_WEIGHT',
        'COL_STOCK_WEIGHT' => 'STOCK_WEIGHT',
        'stock_weight' => 'STOCK_WEIGHT',
        'stock.stock_weight' => 'STOCK_WEIGHT',
        'PubYear' => 'STOCK_PUB_YEAR',
        'Stock.PubYear' => 'STOCK_PUB_YEAR',
        'pubYear' => 'STOCK_PUB_YEAR',
        'stock.pubYear' => 'STOCK_PUB_YEAR',
        'StockTableMap::COL_STOCK_PUB_YEAR' => 'STOCK_PUB_YEAR',
        'COL_STOCK_PUB_YEAR' => 'STOCK_PUB_YEAR',
        'stock_pub_year' => 'STOCK_PUB_YEAR',
        'stock.stock_pub_year' => 'STOCK_PUB_YEAR',
        'AllowPredownload' => 'STOCK_ALLOW_PREDOWNLOAD',
        'Stock.AllowPredownload' => 'STOCK_ALLOW_PREDOWNLOAD',
        'allowPredownload' => 'STOCK_ALLOW_PREDOWNLOAD',
        'stock.allowPredownload' => 'STOCK_ALLOW_PREDOWNLOAD',
        'StockTableMap::COL_STOCK_ALLOW_PREDOWNLOAD' => 'STOCK_ALLOW_PREDOWNLOAD',
        'COL_STOCK_ALLOW_PREDOWNLOAD' => 'STOCK_ALLOW_PREDOWNLOAD',
        'stock_allow_predownload' => 'STOCK_ALLOW_PREDOWNLOAD',
        'stock.stock_allow_predownload' => 'STOCK_ALLOW_PREDOWNLOAD',
        'PhotoVersion' => 'STOCK_PHOTO_VERSION',
        'Stock.PhotoVersion' => 'STOCK_PHOTO_VERSION',
        'photoVersion' => 'STOCK_PHOTO_VERSION',
        'stock.photoVersion' => 'STOCK_PHOTO_VERSION',
        'StockTableMap::COL_STOCK_PHOTO_VERSION' => 'STOCK_PHOTO_VERSION',
        'COL_STOCK_PHOTO_VERSION' => 'STOCK_PHOTO_VERSION',
        'stock_photo_version' => 'STOCK_PHOTO_VERSION',
        'stock.stock_photo_version' => 'STOCK_PHOTO_VERSION',
        'PurchaseDate' => 'STOCK_PURCHASE_DATE',
        'Stock.PurchaseDate' => 'STOCK_PURCHASE_DATE',
        'purchaseDate' => 'STOCK_PURCHASE_DATE',
        'stock.purchaseDate' => 'STOCK_PURCHASE_DATE',
        'StockTableMap::COL_STOCK_PURCHASE_DATE' => 'STOCK_PURCHASE_DATE',
        'COL_STOCK_PURCHASE_DATE' => 'STOCK_PURCHASE_DATE',
        'stock_purchase_date' => 'STOCK_PURCHASE_DATE',
        'stock.stock_purchase_date' => 'STOCK_PURCHASE_DATE',
        'OnsaleDate' => 'STOCK_ONSALE_DATE',
        'Stock.OnsaleDate' => 'STOCK_ONSALE_DATE',
        'onsaleDate' => 'STOCK_ONSALE_DATE',
        'stock.onsaleDate' => 'STOCK_ONSALE_DATE',
        'StockTableMap::COL_STOCK_ONSALE_DATE' => 'STOCK_ONSALE_DATE',
        'COL_STOCK_ONSALE_DATE' => 'STOCK_ONSALE_DATE',
        'stock_onsale_date' => 'STOCK_ONSALE_DATE',
        'stock.stock_onsale_date' => 'STOCK_ONSALE_DATE',
        'CartDate' => 'STOCK_CART_DATE',
        'Stock.CartDate' => 'STOCK_CART_DATE',
        'cartDate' => 'STOCK_CART_DATE',
        'stock.cartDate' => 'STOCK_CART_DATE',
        'StockTableMap::COL_STOCK_CART_DATE' => 'STOCK_CART_DATE',
        'COL_STOCK_CART_DATE' => 'STOCK_CART_DATE',
        'stock_cart_date' => 'STOCK_CART_DATE',
        'stock.stock_cart_date' => 'STOCK_CART_DATE',
        'SellingDate' => 'STOCK_SELLING_DATE',
        'Stock.SellingDate' => 'STOCK_SELLING_DATE',
        'sellingDate' => 'STOCK_SELLING_DATE',
        'stock.sellingDate' => 'STOCK_SELLING_DATE',
        'StockTableMap::COL_STOCK_SELLING_DATE' => 'STOCK_SELLING_DATE',
        'COL_STOCK_SELLING_DATE' => 'STOCK_SELLING_DATE',
        'stock_selling_date' => 'STOCK_SELLING_DATE',
        'stock.stock_selling_date' => 'STOCK_SELLING_DATE',
        'ReturnDate' => 'STOCK_RETURN_DATE',
        'Stock.ReturnDate' => 'STOCK_RETURN_DATE',
        'returnDate' => 'STOCK_RETURN_DATE',
        'stock.returnDate' => 'STOCK_RETURN_DATE',
        'StockTableMap::COL_STOCK_RETURN_DATE' => 'STOCK_RETURN_DATE',
        'COL_STOCK_RETURN_DATE' => 'STOCK_RETURN_DATE',
        'stock_return_date' => 'STOCK_RETURN_DATE',
        'stock.stock_return_date' => 'STOCK_RETURN_DATE',
        'LostDate' => 'STOCK_LOST_DATE',
        'Stock.LostDate' => 'STOCK_LOST_DATE',
        'lostDate' => 'STOCK_LOST_DATE',
        'stock.lostDate' => 'STOCK_LOST_DATE',
        'StockTableMap::COL_STOCK_LOST_DATE' => 'STOCK_LOST_DATE',
        'COL_STOCK_LOST_DATE' => 'STOCK_LOST_DATE',
        'stock_lost_date' => 'STOCK_LOST_DATE',
        'stock.stock_lost_date' => 'STOCK_LOST_DATE',
        'MediaOk' => 'STOCK_MEDIA_OK',
        'Stock.MediaOk' => 'STOCK_MEDIA_OK',
        'mediaOk' => 'STOCK_MEDIA_OK',
        'stock.mediaOk' => 'STOCK_MEDIA_OK',
        'StockTableMap::COL_STOCK_MEDIA_OK' => 'STOCK_MEDIA_OK',
        'COL_STOCK_MEDIA_OK' => 'STOCK_MEDIA_OK',
        'stock_media_ok' => 'STOCK_MEDIA_OK',
        'stock.stock_media_ok' => 'STOCK_MEDIA_OK',
        'FileUpdated' => 'STOCK_FILE_UPDATED',
        'Stock.FileUpdated' => 'STOCK_FILE_UPDATED',
        'fileUpdated' => 'STOCK_FILE_UPDATED',
        'stock.fileUpdated' => 'STOCK_FILE_UPDATED',
        'StockTableMap::COL_STOCK_FILE_UPDATED' => 'STOCK_FILE_UPDATED',
        'COL_STOCK_FILE_UPDATED' => 'STOCK_FILE_UPDATED',
        'stock_file_updated' => 'STOCK_FILE_UPDATED',
        'stock.stock_file_updated' => 'STOCK_FILE_UPDATED',
        'Insert' => 'STOCK_INSERT',
        'Stock.Insert' => 'STOCK_INSERT',
        'insert' => 'STOCK_INSERT',
        'stock.insert' => 'STOCK_INSERT',
        'StockTableMap::COL_STOCK_INSERT' => 'STOCK_INSERT',
        'COL_STOCK_INSERT' => 'STOCK_INSERT',
        'stock_insert' => 'STOCK_INSERT',
        'stock.stock_insert' => 'STOCK_INSERT',
        'Update' => 'STOCK_UPDATE',
        'Stock.Update' => 'STOCK_UPDATE',
        'update' => 'STOCK_UPDATE',
        'stock.update' => 'STOCK_UPDATE',
        'StockTableMap::COL_STOCK_UPDATE' => 'STOCK_UPDATE',
        'COL_STOCK_UPDATE' => 'STOCK_UPDATE',
        'stock_update' => 'STOCK_UPDATE',
        'stock.stock_update' => 'STOCK_UPDATE',
        'Dl' => 'STOCK_DL',
        'Stock.Dl' => 'STOCK_DL',
        'dl' => 'STOCK_DL',
        'stock.dl' => 'STOCK_DL',
        'StockTableMap::COL_STOCK_DL' => 'STOCK_DL',
        'COL_STOCK_DL' => 'STOCK_DL',
        'stock_dl' => 'STOCK_DL',
        'stock.stock_dl' => 'STOCK_DL',
        'CreatedAt' => 'STOCK_CREATED',
        'Stock.CreatedAt' => 'STOCK_CREATED',
        'createdAt' => 'STOCK_CREATED',
        'stock.createdAt' => 'STOCK_CREATED',
        'StockTableMap::COL_STOCK_CREATED' => 'STOCK_CREATED',
        'COL_STOCK_CREATED' => 'STOCK_CREATED',
        'stock_created' => 'STOCK_CREATED',
        'stock.stock_created' => 'STOCK_CREATED',
        'UpdatedAt' => 'STOCK_UPDATED',
        'Stock.UpdatedAt' => 'STOCK_UPDATED',
        'updatedAt' => 'STOCK_UPDATED',
        'stock.updatedAt' => 'STOCK_UPDATED',
        'StockTableMap::COL_STOCK_UPDATED' => 'STOCK_UPDATED',
        'COL_STOCK_UPDATED' => 'STOCK_UPDATED',
        'stock_updated' => 'STOCK_UPDATED',
        'stock.stock_updated' => 'STOCK_UPDATED',
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
        $this->setName('stock');
        $this->setPhpName('Stock');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Stock');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('stock_id', 'Id', 'INTEGER', true, 10, null);
        $this->addForeignKey('site_id', 'SiteId', 'TINYINT', 'sites', 'site_id', false, null, null);
        $this->addForeignKey('article_id', 'ArticleId', 'INTEGER', 'articles', 'article_id', false, 10, null);
        $this->addColumn('campaign_id', 'CampaignId', 'INTEGER', false, 10, null);
        $this->addColumn('reward_id', 'RewardId', 'INTEGER', false, 10, null);
        $this->addForeignKey('axys_account_id', 'AxysAccountId', 'INTEGER', 'axys_accounts', 'id', false, 10, null);
        $this->addColumn('customer_id', 'CustomerId', 'INTEGER', false, 10, null);
        $this->addColumn('wish_id', 'WishId', 'INTEGER', false, 10, null);
        $this->addColumn('cart_id', 'CartId', 'INTEGER', false, 10, null);
        $this->addColumn('order_id', 'OrderId', 'INTEGER', false, 10, null);
        $this->addColumn('coupon_id', 'CouponId', 'INTEGER', false, 10, null);
        $this->addColumn('stock_shop', 'Shop', 'INTEGER', false, 10, null);
        $this->addColumn('stock_invoice', 'Invoice', 'VARCHAR', false, 256, null);
        $this->addColumn('stock_depot', 'Depot', 'BOOLEAN', false, 1, false);
        $this->addColumn('stock_stockage', 'Stockage', 'VARCHAR', false, 16, null);
        $this->addColumn('stock_condition', 'Condition', 'VARCHAR', false, 16, null);
        $this->addColumn('stock_condition_details', 'ConditionDetails', 'LONGVARCHAR', false, null, null);
        $this->addColumn('stock_purchase_price', 'PurchasePrice', 'INTEGER', false, 10, null);
        $this->addColumn('stock_selling_price', 'SellingPrice', 'INTEGER', false, 10, null);
        $this->addColumn('stock_selling_price2', 'SellingPrice2', 'INTEGER', false, 10, null);
        $this->addColumn('stock_selling_price_saved', 'SellingPriceSaved', 'INTEGER', false, 10, null);
        $this->addColumn('stock_selling_price_ht', 'SellingPriceHt', 'INTEGER', false, 10, null);
        $this->addColumn('stock_selling_price_tva', 'SellingPriceTva', 'INTEGER', false, 10, null);
        $this->addColumn('stock_tva_rate', 'TvaRate', 'FLOAT', false, null, null);
        $this->addColumn('stock_weight', 'Weight', 'INTEGER', false, 10, null);
        $this->addColumn('stock_pub_year', 'PubYear', 'INTEGER', false, 4, null);
        $this->addColumn('stock_allow_predownload', 'AllowPredownload', 'BOOLEAN', false, 1, false);
        $this->addColumn('stock_photo_version', 'PhotoVersion', 'INTEGER', false, 10, 0);
        $this->addColumn('stock_purchase_date', 'PurchaseDate', 'TIMESTAMP', false, null, null);
        $this->addColumn('stock_onsale_date', 'OnsaleDate', 'TIMESTAMP', false, null, null);
        $this->addColumn('stock_cart_date', 'CartDate', 'TIMESTAMP', false, null, null);
        $this->addColumn('stock_selling_date', 'SellingDate', 'TIMESTAMP', false, null, null);
        $this->addColumn('stock_return_date', 'ReturnDate', 'TIMESTAMP', false, null, null);
        $this->addColumn('stock_lost_date', 'LostDate', 'TIMESTAMP', false, null, null);
        $this->addColumn('stock_media_ok', 'MediaOk', 'BOOLEAN', false, 1, false);
        $this->addColumn('stock_file_updated', 'FileUpdated', 'BOOLEAN', false, 1, false);
        $this->addColumn('stock_insert', 'Insert', 'TIMESTAMP', false, null, null);
        $this->addColumn('stock_update', 'Update', 'TIMESTAMP', false, null, null);
        $this->addColumn('stock_dl', 'Dl', 'BOOLEAN', false, 1, false);
        $this->addColumn('stock_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('stock_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
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
        $this->addRelation('Article', '\\Model\\Article', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':article_id',
    1 => ':article_id',
  ),
), null, null, null, false);
        $this->addRelation('AxysAccount', '\\Model\\AxysAccount', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':axys_account_id',
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
            'timestampable' => ['create_column' => 'stock_created', 'update_column' => 'stock_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? StockTableMap::CLASS_DEFAULT : StockTableMap::OM_CLASS;
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
     * @return array (Stock object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = StockTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = StockTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + StockTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = StockTableMap::OM_CLASS;
            /** @var Stock $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            StockTableMap::addInstanceToPool($obj, $key);
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
            $key = StockTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = StockTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Stock $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                StockTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_ID);
            $criteria->addSelectColumn(StockTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(StockTableMap::COL_ARTICLE_ID);
            $criteria->addSelectColumn(StockTableMap::COL_CAMPAIGN_ID);
            $criteria->addSelectColumn(StockTableMap::COL_REWARD_ID);
            $criteria->addSelectColumn(StockTableMap::COL_AXYS_ACCOUNT_ID);
            $criteria->addSelectColumn(StockTableMap::COL_CUSTOMER_ID);
            $criteria->addSelectColumn(StockTableMap::COL_WISH_ID);
            $criteria->addSelectColumn(StockTableMap::COL_CART_ID);
            $criteria->addSelectColumn(StockTableMap::COL_ORDER_ID);
            $criteria->addSelectColumn(StockTableMap::COL_COUPON_ID);
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_SHOP);
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_INVOICE);
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_DEPOT);
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_STOCKAGE);
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_CONDITION);
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_CONDITION_DETAILS);
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_PURCHASE_PRICE);
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_SELLING_PRICE);
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_SELLING_PRICE2);
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_SELLING_PRICE_SAVED);
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_SELLING_PRICE_HT);
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_SELLING_PRICE_TVA);
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_TVA_RATE);
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_WEIGHT);
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_PUB_YEAR);
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_ALLOW_PREDOWNLOAD);
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_PHOTO_VERSION);
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_PURCHASE_DATE);
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_ONSALE_DATE);
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_CART_DATE);
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_SELLING_DATE);
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_RETURN_DATE);
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_LOST_DATE);
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_MEDIA_OK);
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_FILE_UPDATED);
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_INSERT);
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_UPDATE);
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_DL);
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_CREATED);
            $criteria->addSelectColumn(StockTableMap::COL_STOCK_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.stock_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.article_id');
            $criteria->addSelectColumn($alias . '.campaign_id');
            $criteria->addSelectColumn($alias . '.reward_id');
            $criteria->addSelectColumn($alias . '.axys_account_id');
            $criteria->addSelectColumn($alias . '.customer_id');
            $criteria->addSelectColumn($alias . '.wish_id');
            $criteria->addSelectColumn($alias . '.cart_id');
            $criteria->addSelectColumn($alias . '.order_id');
            $criteria->addSelectColumn($alias . '.coupon_id');
            $criteria->addSelectColumn($alias . '.stock_shop');
            $criteria->addSelectColumn($alias . '.stock_invoice');
            $criteria->addSelectColumn($alias . '.stock_depot');
            $criteria->addSelectColumn($alias . '.stock_stockage');
            $criteria->addSelectColumn($alias . '.stock_condition');
            $criteria->addSelectColumn($alias . '.stock_condition_details');
            $criteria->addSelectColumn($alias . '.stock_purchase_price');
            $criteria->addSelectColumn($alias . '.stock_selling_price');
            $criteria->addSelectColumn($alias . '.stock_selling_price2');
            $criteria->addSelectColumn($alias . '.stock_selling_price_saved');
            $criteria->addSelectColumn($alias . '.stock_selling_price_ht');
            $criteria->addSelectColumn($alias . '.stock_selling_price_tva');
            $criteria->addSelectColumn($alias . '.stock_tva_rate');
            $criteria->addSelectColumn($alias . '.stock_weight');
            $criteria->addSelectColumn($alias . '.stock_pub_year');
            $criteria->addSelectColumn($alias . '.stock_allow_predownload');
            $criteria->addSelectColumn($alias . '.stock_photo_version');
            $criteria->addSelectColumn($alias . '.stock_purchase_date');
            $criteria->addSelectColumn($alias . '.stock_onsale_date');
            $criteria->addSelectColumn($alias . '.stock_cart_date');
            $criteria->addSelectColumn($alias . '.stock_selling_date');
            $criteria->addSelectColumn($alias . '.stock_return_date');
            $criteria->addSelectColumn($alias . '.stock_lost_date');
            $criteria->addSelectColumn($alias . '.stock_media_ok');
            $criteria->addSelectColumn($alias . '.stock_file_updated');
            $criteria->addSelectColumn($alias . '.stock_insert');
            $criteria->addSelectColumn($alias . '.stock_update');
            $criteria->addSelectColumn($alias . '.stock_dl');
            $criteria->addSelectColumn($alias . '.stock_created');
            $criteria->addSelectColumn($alias . '.stock_updated');
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
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_ID);
            $criteria->removeSelectColumn(StockTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(StockTableMap::COL_ARTICLE_ID);
            $criteria->removeSelectColumn(StockTableMap::COL_CAMPAIGN_ID);
            $criteria->removeSelectColumn(StockTableMap::COL_REWARD_ID);
            $criteria->removeSelectColumn(StockTableMap::COL_AXYS_ACCOUNT_ID);
            $criteria->removeSelectColumn(StockTableMap::COL_CUSTOMER_ID);
            $criteria->removeSelectColumn(StockTableMap::COL_WISH_ID);
            $criteria->removeSelectColumn(StockTableMap::COL_CART_ID);
            $criteria->removeSelectColumn(StockTableMap::COL_ORDER_ID);
            $criteria->removeSelectColumn(StockTableMap::COL_COUPON_ID);
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_SHOP);
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_INVOICE);
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_DEPOT);
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_STOCKAGE);
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_CONDITION);
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_CONDITION_DETAILS);
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_PURCHASE_PRICE);
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_SELLING_PRICE);
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_SELLING_PRICE2);
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_SELLING_PRICE_SAVED);
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_SELLING_PRICE_HT);
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_SELLING_PRICE_TVA);
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_TVA_RATE);
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_WEIGHT);
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_PUB_YEAR);
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_ALLOW_PREDOWNLOAD);
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_PHOTO_VERSION);
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_PURCHASE_DATE);
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_ONSALE_DATE);
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_CART_DATE);
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_SELLING_DATE);
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_RETURN_DATE);
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_LOST_DATE);
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_MEDIA_OK);
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_FILE_UPDATED);
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_INSERT);
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_UPDATE);
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_DL);
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_CREATED);
            $criteria->removeSelectColumn(StockTableMap::COL_STOCK_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.stock_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.article_id');
            $criteria->removeSelectColumn($alias . '.campaign_id');
            $criteria->removeSelectColumn($alias . '.reward_id');
            $criteria->removeSelectColumn($alias . '.axys_account_id');
            $criteria->removeSelectColumn($alias . '.customer_id');
            $criteria->removeSelectColumn($alias . '.wish_id');
            $criteria->removeSelectColumn($alias . '.cart_id');
            $criteria->removeSelectColumn($alias . '.order_id');
            $criteria->removeSelectColumn($alias . '.coupon_id');
            $criteria->removeSelectColumn($alias . '.stock_shop');
            $criteria->removeSelectColumn($alias . '.stock_invoice');
            $criteria->removeSelectColumn($alias . '.stock_depot');
            $criteria->removeSelectColumn($alias . '.stock_stockage');
            $criteria->removeSelectColumn($alias . '.stock_condition');
            $criteria->removeSelectColumn($alias . '.stock_condition_details');
            $criteria->removeSelectColumn($alias . '.stock_purchase_price');
            $criteria->removeSelectColumn($alias . '.stock_selling_price');
            $criteria->removeSelectColumn($alias . '.stock_selling_price2');
            $criteria->removeSelectColumn($alias . '.stock_selling_price_saved');
            $criteria->removeSelectColumn($alias . '.stock_selling_price_ht');
            $criteria->removeSelectColumn($alias . '.stock_selling_price_tva');
            $criteria->removeSelectColumn($alias . '.stock_tva_rate');
            $criteria->removeSelectColumn($alias . '.stock_weight');
            $criteria->removeSelectColumn($alias . '.stock_pub_year');
            $criteria->removeSelectColumn($alias . '.stock_allow_predownload');
            $criteria->removeSelectColumn($alias . '.stock_photo_version');
            $criteria->removeSelectColumn($alias . '.stock_purchase_date');
            $criteria->removeSelectColumn($alias . '.stock_onsale_date');
            $criteria->removeSelectColumn($alias . '.stock_cart_date');
            $criteria->removeSelectColumn($alias . '.stock_selling_date');
            $criteria->removeSelectColumn($alias . '.stock_return_date');
            $criteria->removeSelectColumn($alias . '.stock_lost_date');
            $criteria->removeSelectColumn($alias . '.stock_media_ok');
            $criteria->removeSelectColumn($alias . '.stock_file_updated');
            $criteria->removeSelectColumn($alias . '.stock_insert');
            $criteria->removeSelectColumn($alias . '.stock_update');
            $criteria->removeSelectColumn($alias . '.stock_dl');
            $criteria->removeSelectColumn($alias . '.stock_created');
            $criteria->removeSelectColumn($alias . '.stock_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(StockTableMap::DATABASE_NAME)->getTable(StockTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Stock or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Stock object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(StockTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Stock) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(StockTableMap::DATABASE_NAME);
            $criteria->add(StockTableMap::COL_STOCK_ID, (array) $values, Criteria::IN);
        }

        $query = StockQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            StockTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                StockTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the stock table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return StockQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Stock or Criteria object.
     *
     * @param mixed $criteria Criteria or Stock object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(StockTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Stock object
        }

        if ($criteria->containsKey(StockTableMap::COL_STOCK_ID) && $criteria->keyContainsValue(StockTableMap::COL_STOCK_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.StockTableMap::COL_STOCK_ID.')');
        }


        // Set the correct dbName
        $query = StockQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
