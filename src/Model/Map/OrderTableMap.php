<?php

namespace Model\Map;

use Model\Order;
use Model\OrderQuery;
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
 * This class defines the structure of the 'orders' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class OrderTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.OrderTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'orders';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Order';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Order';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Order';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 47;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 47;

    /**
     * the column name for the order_id field
     */
    public const COL_ORDER_ID = 'orders.order_id';

    /**
     * the column name for the order_url field
     */
    public const COL_ORDER_URL = 'orders.order_url';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'orders.site_id';

    /**
     * the column name for the user_id field
     */
    public const COL_USER_ID = 'orders.user_id';

    /**
     * the column name for the customer_id field
     */
    public const COL_CUSTOMER_ID = 'orders.customer_id';

    /**
     * the column name for the seller_id field
     */
    public const COL_SELLER_ID = 'orders.seller_id';

    /**
     * the column name for the order_type field
     */
    public const COL_ORDER_TYPE = 'orders.order_type';

    /**
     * the column name for the order_as_a_gift field
     */
    public const COL_AS_A_GIFT = 'orders.order_as_a_gift';

    /**
     * the column name for the order_gift_recipient field
     */
    public const COL_GIFT_RECIPIENT = 'orders.order_gift_recipient';

    /**
     * the column name for the order_amount field
     */
    public const COL_ORDER_AMOUNT = 'orders.order_amount';

    /**
     * the column name for the order_discount field
     */
    public const COL_ORDER_DISCOUNT = 'orders.order_discount';

    /**
     * the column name for the order_amount_tobepaid field
     */
    public const COL_ORDER_AMOUNT_TOBEPAID = 'orders.order_amount_tobepaid';

    /**
     * the column name for the shipping_id field
     */
    public const COL_SHIPPING_ID = 'orders.shipping_id';

    /**
     * the column name for the country_id field
     */
    public const COL_COUNTRY_ID = 'orders.country_id';

    /**
     * the column name for the order_shipping field
     */
    public const COL_ORDER_SHIPPING = 'orders.order_shipping';

    /**
     * the column name for the order_shipping_mode field
     */
    public const COL_ORDER_SHIPPING_MODE = 'orders.order_shipping_mode';

    /**
     * the column name for the order_track_number field
     */
    public const COL_ORDER_TRACK_NUMBER = 'orders.order_track_number';

    /**
     * the column name for the order_payment_mode field
     */
    public const COL_ORDER_PAYMENT_MODE = 'orders.order_payment_mode';

    /**
     * the column name for the order_payment_cash field
     */
    public const COL_ORDER_PAYMENT_CASH = 'orders.order_payment_cash';

    /**
     * the column name for the order_payment_cheque field
     */
    public const COL_ORDER_PAYMENT_CHEQUE = 'orders.order_payment_cheque';

    /**
     * the column name for the order_payment_transfer field
     */
    public const COL_ORDER_PAYMENT_TRANSFER = 'orders.order_payment_transfer';

    /**
     * the column name for the order_payment_card field
     */
    public const COL_ORDER_PAYMENT_CARD = 'orders.order_payment_card';

    /**
     * the column name for the order_payment_paypal field
     */
    public const COL_ORDER_PAYMENT_PAYPAL = 'orders.order_payment_paypal';

    /**
     * the column name for the order_payment_payplug field
     */
    public const COL_ORDER_PAYMENT_PAYPLUG = 'orders.order_payment_payplug';

    /**
     * the column name for the order_payment_left field
     */
    public const COL_ORDER_PAYMENT_LEFT = 'orders.order_payment_left';

    /**
     * the column name for the order_title field
     */
    public const COL_ORDER_TITLE = 'orders.order_title';

    /**
     * the column name for the order_firstname field
     */
    public const COL_ORDER_FIRSTNAME = 'orders.order_firstname';

    /**
     * the column name for the order_lastname field
     */
    public const COL_ORDER_LASTNAME = 'orders.order_lastname';

    /**
     * the column name for the order_address1 field
     */
    public const COL_ORDER_ADDRESS1 = 'orders.order_address1';

    /**
     * the column name for the order_address2 field
     */
    public const COL_ORDER_ADDRESS2 = 'orders.order_address2';

    /**
     * the column name for the order_postalcode field
     */
    public const COL_ORDER_POSTALCODE = 'orders.order_postalcode';

    /**
     * the column name for the order_city field
     */
    public const COL_ORDER_CITY = 'orders.order_city';

    /**
     * the column name for the order_country field
     */
    public const COL_ORDER_COUNTRY = 'orders.order_country';

    /**
     * the column name for the order_email field
     */
    public const COL_ORDER_EMAIL = 'orders.order_email';

    /**
     * the column name for the order_phone field
     */
    public const COL_ORDER_PHONE = 'orders.order_phone';

    /**
     * the column name for the order_comment field
     */
    public const COL_ORDER_COMMENT = 'orders.order_comment';

    /**
     * the column name for the order_utmz field
     */
    public const COL_ORDER_UTMZ = 'orders.order_utmz';

    /**
     * the column name for the order_referer field
     */
    public const COL_ORDER_REFERER = 'orders.order_referer';

    /**
     * the column name for the order_insert field
     */
    public const COL_ORDER_INSERT = 'orders.order_insert';

    /**
     * the column name for the order_payment_date field
     */
    public const COL_ORDER_PAYMENT_DATE = 'orders.order_payment_date';

    /**
     * the column name for the order_shipping_date field
     */
    public const COL_ORDER_SHIPPING_DATE = 'orders.order_shipping_date';

    /**
     * the column name for the order_followup_date field
     */
    public const COL_ORDER_FOLLOWUP_DATE = 'orders.order_followup_date';

    /**
     * the column name for the order_confirmation_date field
     */
    public const COL_ORDER_CONFIRMATION_DATE = 'orders.order_confirmation_date';

    /**
     * the column name for the order_cancel_date field
     */
    public const COL_ORDER_CANCEL_DATE = 'orders.order_cancel_date';

    /**
     * the column name for the order_update field
     */
    public const COL_ORDER_UPDATE = 'orders.order_update';

    /**
     * the column name for the order_created field
     */
    public const COL_ORDER_CREATED = 'orders.order_created';

    /**
     * the column name for the order_updated field
     */
    public const COL_ORDER_UPDATED = 'orders.order_updated';

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
        self::TYPE_PHPNAME       => ['Id', 'Slug', 'SiteId', 'UserId', 'CustomerId', 'SellerId', 'Type', 'AsAGift', 'GiftRecipient', 'Amount', 'Discount', 'AmountTobepaid', 'ShippingId', 'CountryId', 'Shipping', 'ShippingMode', 'TrackNumber', 'PaymentMode', 'PaymentCash', 'PaymentCheque', 'PaymentTransfer', 'PaymentCard', 'PaymentPaypal', 'PaymentPayplug', 'PaymentLeft', 'Title', 'Firstname', 'Lastname', 'Address1', 'Address2', 'Postalcode', 'City', 'Country', 'Email', 'Phone', 'Comment', 'Utmz', 'Referer', 'Insert', 'PaymentDate', 'ShippingDate', 'FollowupDate', 'ConfirmationDate', 'CancelDate', 'Update', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'slug', 'siteId', 'userId', 'customerId', 'sellerId', 'type', 'asAGift', 'giftRecipient', 'amount', 'discount', 'amountTobepaid', 'shippingId', 'countryId', 'shipping', 'shippingMode', 'trackNumber', 'paymentMode', 'paymentCash', 'paymentCheque', 'paymentTransfer', 'paymentCard', 'paymentPaypal', 'paymentPayplug', 'paymentLeft', 'title', 'firstname', 'lastname', 'address1', 'address2', 'postalcode', 'city', 'country', 'email', 'phone', 'comment', 'utmz', 'referer', 'insert', 'paymentDate', 'shippingDate', 'followupDate', 'confirmationDate', 'cancelDate', 'update', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [OrderTableMap::COL_ORDER_ID, OrderTableMap::COL_ORDER_URL, OrderTableMap::COL_SITE_ID, OrderTableMap::COL_USER_ID, OrderTableMap::COL_CUSTOMER_ID, OrderTableMap::COL_SELLER_ID, OrderTableMap::COL_ORDER_TYPE, OrderTableMap::COL_AS_A_GIFT, OrderTableMap::COL_GIFT_RECIPIENT, OrderTableMap::COL_ORDER_AMOUNT, OrderTableMap::COL_ORDER_DISCOUNT, OrderTableMap::COL_ORDER_AMOUNT_TOBEPAID, OrderTableMap::COL_SHIPPING_ID, OrderTableMap::COL_COUNTRY_ID, OrderTableMap::COL_ORDER_SHIPPING, OrderTableMap::COL_ORDER_SHIPPING_MODE, OrderTableMap::COL_ORDER_TRACK_NUMBER, OrderTableMap::COL_ORDER_PAYMENT_MODE, OrderTableMap::COL_ORDER_PAYMENT_CASH, OrderTableMap::COL_ORDER_PAYMENT_CHEQUE, OrderTableMap::COL_ORDER_PAYMENT_TRANSFER, OrderTableMap::COL_ORDER_PAYMENT_CARD, OrderTableMap::COL_ORDER_PAYMENT_PAYPAL, OrderTableMap::COL_ORDER_PAYMENT_PAYPLUG, OrderTableMap::COL_ORDER_PAYMENT_LEFT, OrderTableMap::COL_ORDER_TITLE, OrderTableMap::COL_ORDER_FIRSTNAME, OrderTableMap::COL_ORDER_LASTNAME, OrderTableMap::COL_ORDER_ADDRESS1, OrderTableMap::COL_ORDER_ADDRESS2, OrderTableMap::COL_ORDER_POSTALCODE, OrderTableMap::COL_ORDER_CITY, OrderTableMap::COL_ORDER_COUNTRY, OrderTableMap::COL_ORDER_EMAIL, OrderTableMap::COL_ORDER_PHONE, OrderTableMap::COL_ORDER_COMMENT, OrderTableMap::COL_ORDER_UTMZ, OrderTableMap::COL_ORDER_REFERER, OrderTableMap::COL_ORDER_INSERT, OrderTableMap::COL_ORDER_PAYMENT_DATE, OrderTableMap::COL_ORDER_SHIPPING_DATE, OrderTableMap::COL_ORDER_FOLLOWUP_DATE, OrderTableMap::COL_ORDER_CONFIRMATION_DATE, OrderTableMap::COL_ORDER_CANCEL_DATE, OrderTableMap::COL_ORDER_UPDATE, OrderTableMap::COL_ORDER_CREATED, OrderTableMap::COL_ORDER_UPDATED, ],
        self::TYPE_FIELDNAME     => ['order_id', 'order_url', 'site_id', 'user_id', 'customer_id', 'seller_id', 'order_type', 'order_as_a_gift', 'order_gift_recipient', 'order_amount', 'order_discount', 'order_amount_tobepaid', 'shipping_id', 'country_id', 'order_shipping', 'order_shipping_mode', 'order_track_number', 'order_payment_mode', 'order_payment_cash', 'order_payment_cheque', 'order_payment_transfer', 'order_payment_card', 'order_payment_paypal', 'order_payment_payplug', 'order_payment_left', 'order_title', 'order_firstname', 'order_lastname', 'order_address1', 'order_address2', 'order_postalcode', 'order_city', 'order_country', 'order_email', 'order_phone', 'order_comment', 'order_utmz', 'order_referer', 'order_insert', 'order_payment_date', 'order_shipping_date', 'order_followup_date', 'order_confirmation_date', 'order_cancel_date', 'order_update', 'order_created', 'order_updated', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'Slug' => 1, 'SiteId' => 2, 'UserId' => 3, 'CustomerId' => 4, 'SellerId' => 5, 'Type' => 6, 'AsAGift' => 7, 'GiftRecipient' => 8, 'Amount' => 9, 'Discount' => 10, 'AmountTobepaid' => 11, 'ShippingId' => 12, 'CountryId' => 13, 'Shipping' => 14, 'ShippingMode' => 15, 'TrackNumber' => 16, 'PaymentMode' => 17, 'PaymentCash' => 18, 'PaymentCheque' => 19, 'PaymentTransfer' => 20, 'PaymentCard' => 21, 'PaymentPaypal' => 22, 'PaymentPayplug' => 23, 'PaymentLeft' => 24, 'Title' => 25, 'Firstname' => 26, 'Lastname' => 27, 'Address1' => 28, 'Address2' => 29, 'Postalcode' => 30, 'City' => 31, 'Country' => 32, 'Email' => 33, 'Phone' => 34, 'Comment' => 35, 'Utmz' => 36, 'Referer' => 37, 'Insert' => 38, 'PaymentDate' => 39, 'ShippingDate' => 40, 'FollowupDate' => 41, 'ConfirmationDate' => 42, 'CancelDate' => 43, 'Update' => 44, 'CreatedAt' => 45, 'UpdatedAt' => 46, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'slug' => 1, 'siteId' => 2, 'userId' => 3, 'customerId' => 4, 'sellerId' => 5, 'type' => 6, 'asAGift' => 7, 'giftRecipient' => 8, 'amount' => 9, 'discount' => 10, 'amountTobepaid' => 11, 'shippingId' => 12, 'countryId' => 13, 'shipping' => 14, 'shippingMode' => 15, 'trackNumber' => 16, 'paymentMode' => 17, 'paymentCash' => 18, 'paymentCheque' => 19, 'paymentTransfer' => 20, 'paymentCard' => 21, 'paymentPaypal' => 22, 'paymentPayplug' => 23, 'paymentLeft' => 24, 'title' => 25, 'firstname' => 26, 'lastname' => 27, 'address1' => 28, 'address2' => 29, 'postalcode' => 30, 'city' => 31, 'country' => 32, 'email' => 33, 'phone' => 34, 'comment' => 35, 'utmz' => 36, 'referer' => 37, 'insert' => 38, 'paymentDate' => 39, 'shippingDate' => 40, 'followupDate' => 41, 'confirmationDate' => 42, 'cancelDate' => 43, 'update' => 44, 'createdAt' => 45, 'updatedAt' => 46, ],
        self::TYPE_COLNAME       => [OrderTableMap::COL_ORDER_ID => 0, OrderTableMap::COL_ORDER_URL => 1, OrderTableMap::COL_SITE_ID => 2, OrderTableMap::COL_USER_ID => 3, OrderTableMap::COL_CUSTOMER_ID => 4, OrderTableMap::COL_SELLER_ID => 5, OrderTableMap::COL_ORDER_TYPE => 6, OrderTableMap::COL_AS_A_GIFT => 7, OrderTableMap::COL_GIFT_RECIPIENT => 8, OrderTableMap::COL_ORDER_AMOUNT => 9, OrderTableMap::COL_ORDER_DISCOUNT => 10, OrderTableMap::COL_ORDER_AMOUNT_TOBEPAID => 11, OrderTableMap::COL_SHIPPING_ID => 12, OrderTableMap::COL_COUNTRY_ID => 13, OrderTableMap::COL_ORDER_SHIPPING => 14, OrderTableMap::COL_ORDER_SHIPPING_MODE => 15, OrderTableMap::COL_ORDER_TRACK_NUMBER => 16, OrderTableMap::COL_ORDER_PAYMENT_MODE => 17, OrderTableMap::COL_ORDER_PAYMENT_CASH => 18, OrderTableMap::COL_ORDER_PAYMENT_CHEQUE => 19, OrderTableMap::COL_ORDER_PAYMENT_TRANSFER => 20, OrderTableMap::COL_ORDER_PAYMENT_CARD => 21, OrderTableMap::COL_ORDER_PAYMENT_PAYPAL => 22, OrderTableMap::COL_ORDER_PAYMENT_PAYPLUG => 23, OrderTableMap::COL_ORDER_PAYMENT_LEFT => 24, OrderTableMap::COL_ORDER_TITLE => 25, OrderTableMap::COL_ORDER_FIRSTNAME => 26, OrderTableMap::COL_ORDER_LASTNAME => 27, OrderTableMap::COL_ORDER_ADDRESS1 => 28, OrderTableMap::COL_ORDER_ADDRESS2 => 29, OrderTableMap::COL_ORDER_POSTALCODE => 30, OrderTableMap::COL_ORDER_CITY => 31, OrderTableMap::COL_ORDER_COUNTRY => 32, OrderTableMap::COL_ORDER_EMAIL => 33, OrderTableMap::COL_ORDER_PHONE => 34, OrderTableMap::COL_ORDER_COMMENT => 35, OrderTableMap::COL_ORDER_UTMZ => 36, OrderTableMap::COL_ORDER_REFERER => 37, OrderTableMap::COL_ORDER_INSERT => 38, OrderTableMap::COL_ORDER_PAYMENT_DATE => 39, OrderTableMap::COL_ORDER_SHIPPING_DATE => 40, OrderTableMap::COL_ORDER_FOLLOWUP_DATE => 41, OrderTableMap::COL_ORDER_CONFIRMATION_DATE => 42, OrderTableMap::COL_ORDER_CANCEL_DATE => 43, OrderTableMap::COL_ORDER_UPDATE => 44, OrderTableMap::COL_ORDER_CREATED => 45, OrderTableMap::COL_ORDER_UPDATED => 46, ],
        self::TYPE_FIELDNAME     => ['order_id' => 0, 'order_url' => 1, 'site_id' => 2, 'user_id' => 3, 'customer_id' => 4, 'seller_id' => 5, 'order_type' => 6, 'order_as_a_gift' => 7, 'order_gift_recipient' => 8, 'order_amount' => 9, 'order_discount' => 10, 'order_amount_tobepaid' => 11, 'shipping_id' => 12, 'country_id' => 13, 'order_shipping' => 14, 'order_shipping_mode' => 15, 'order_track_number' => 16, 'order_payment_mode' => 17, 'order_payment_cash' => 18, 'order_payment_cheque' => 19, 'order_payment_transfer' => 20, 'order_payment_card' => 21, 'order_payment_paypal' => 22, 'order_payment_payplug' => 23, 'order_payment_left' => 24, 'order_title' => 25, 'order_firstname' => 26, 'order_lastname' => 27, 'order_address1' => 28, 'order_address2' => 29, 'order_postalcode' => 30, 'order_city' => 31, 'order_country' => 32, 'order_email' => 33, 'order_phone' => 34, 'order_comment' => 35, 'order_utmz' => 36, 'order_referer' => 37, 'order_insert' => 38, 'order_payment_date' => 39, 'order_shipping_date' => 40, 'order_followup_date' => 41, 'order_confirmation_date' => 42, 'order_cancel_date' => 43, 'order_update' => 44, 'order_created' => 45, 'order_updated' => 46, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'ORDER_ID',
        'Order.Id' => 'ORDER_ID',
        'id' => 'ORDER_ID',
        'order.id' => 'ORDER_ID',
        'OrderTableMap::COL_ORDER_ID' => 'ORDER_ID',
        'COL_ORDER_ID' => 'ORDER_ID',
        'order_id' => 'ORDER_ID',
        'orders.order_id' => 'ORDER_ID',
        'Slug' => 'ORDER_URL',
        'Order.Slug' => 'ORDER_URL',
        'slug' => 'ORDER_URL',
        'order.slug' => 'ORDER_URL',
        'OrderTableMap::COL_ORDER_URL' => 'ORDER_URL',
        'COL_ORDER_URL' => 'ORDER_URL',
        'order_url' => 'ORDER_URL',
        'orders.order_url' => 'ORDER_URL',
        'SiteId' => 'SITE_ID',
        'Order.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'order.siteId' => 'SITE_ID',
        'OrderTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'orders.site_id' => 'SITE_ID',
        'UserId' => 'USER_ID',
        'Order.UserId' => 'USER_ID',
        'userId' => 'USER_ID',
        'order.userId' => 'USER_ID',
        'OrderTableMap::COL_USER_ID' => 'USER_ID',
        'COL_USER_ID' => 'USER_ID',
        'user_id' => 'USER_ID',
        'orders.user_id' => 'USER_ID',
        'CustomerId' => 'CUSTOMER_ID',
        'Order.CustomerId' => 'CUSTOMER_ID',
        'customerId' => 'CUSTOMER_ID',
        'order.customerId' => 'CUSTOMER_ID',
        'OrderTableMap::COL_CUSTOMER_ID' => 'CUSTOMER_ID',
        'COL_CUSTOMER_ID' => 'CUSTOMER_ID',
        'customer_id' => 'CUSTOMER_ID',
        'orders.customer_id' => 'CUSTOMER_ID',
        'SellerId' => 'SELLER_ID',
        'Order.SellerId' => 'SELLER_ID',
        'sellerId' => 'SELLER_ID',
        'order.sellerId' => 'SELLER_ID',
        'OrderTableMap::COL_SELLER_ID' => 'SELLER_ID',
        'COL_SELLER_ID' => 'SELLER_ID',
        'seller_id' => 'SELLER_ID',
        'orders.seller_id' => 'SELLER_ID',
        'Type' => 'ORDER_TYPE',
        'Order.Type' => 'ORDER_TYPE',
        'type' => 'ORDER_TYPE',
        'order.type' => 'ORDER_TYPE',
        'OrderTableMap::COL_ORDER_TYPE' => 'ORDER_TYPE',
        'COL_ORDER_TYPE' => 'ORDER_TYPE',
        'order_type' => 'ORDER_TYPE',
        'orders.order_type' => 'ORDER_TYPE',
        'AsAGift' => 'ORDER_AS_A_GIFT',
        'Order.AsAGift' => 'ORDER_AS_A_GIFT',
        'asAGift' => 'ORDER_AS_A_GIFT',
        'order.asAGift' => 'ORDER_AS_A_GIFT',
        'OrderTableMap::COL_AS_A_GIFT' => 'ORDER_AS_A_GIFT',
        'COL_AS_A_GIFT' => 'ORDER_AS_A_GIFT',
        'order_as_a_gift' => 'ORDER_AS_A_GIFT',
        'orders.order_as_a_gift' => 'ORDER_AS_A_GIFT',
        'GiftRecipient' => 'ORDER_GIFT_RECIPIENT',
        'Order.GiftRecipient' => 'ORDER_GIFT_RECIPIENT',
        'giftRecipient' => 'ORDER_GIFT_RECIPIENT',
        'order.giftRecipient' => 'ORDER_GIFT_RECIPIENT',
        'OrderTableMap::COL_GIFT_RECIPIENT' => 'ORDER_GIFT_RECIPIENT',
        'COL_GIFT_RECIPIENT' => 'ORDER_GIFT_RECIPIENT',
        'order_gift_recipient' => 'ORDER_GIFT_RECIPIENT',
        'orders.order_gift_recipient' => 'ORDER_GIFT_RECIPIENT',
        'Amount' => 'ORDER_AMOUNT',
        'Order.Amount' => 'ORDER_AMOUNT',
        'amount' => 'ORDER_AMOUNT',
        'order.amount' => 'ORDER_AMOUNT',
        'OrderTableMap::COL_ORDER_AMOUNT' => 'ORDER_AMOUNT',
        'COL_ORDER_AMOUNT' => 'ORDER_AMOUNT',
        'order_amount' => 'ORDER_AMOUNT',
        'orders.order_amount' => 'ORDER_AMOUNT',
        'Discount' => 'ORDER_DISCOUNT',
        'Order.Discount' => 'ORDER_DISCOUNT',
        'discount' => 'ORDER_DISCOUNT',
        'order.discount' => 'ORDER_DISCOUNT',
        'OrderTableMap::COL_ORDER_DISCOUNT' => 'ORDER_DISCOUNT',
        'COL_ORDER_DISCOUNT' => 'ORDER_DISCOUNT',
        'order_discount' => 'ORDER_DISCOUNT',
        'orders.order_discount' => 'ORDER_DISCOUNT',
        'AmountTobepaid' => 'ORDER_AMOUNT_TOBEPAID',
        'Order.AmountTobepaid' => 'ORDER_AMOUNT_TOBEPAID',
        'amountTobepaid' => 'ORDER_AMOUNT_TOBEPAID',
        'order.amountTobepaid' => 'ORDER_AMOUNT_TOBEPAID',
        'OrderTableMap::COL_ORDER_AMOUNT_TOBEPAID' => 'ORDER_AMOUNT_TOBEPAID',
        'COL_ORDER_AMOUNT_TOBEPAID' => 'ORDER_AMOUNT_TOBEPAID',
        'order_amount_tobepaid' => 'ORDER_AMOUNT_TOBEPAID',
        'orders.order_amount_tobepaid' => 'ORDER_AMOUNT_TOBEPAID',
        'ShippingId' => 'SHIPPING_ID',
        'Order.ShippingId' => 'SHIPPING_ID',
        'shippingId' => 'SHIPPING_ID',
        'order.shippingId' => 'SHIPPING_ID',
        'OrderTableMap::COL_SHIPPING_ID' => 'SHIPPING_ID',
        'COL_SHIPPING_ID' => 'SHIPPING_ID',
        'shipping_id' => 'SHIPPING_ID',
        'orders.shipping_id' => 'SHIPPING_ID',
        'CountryId' => 'COUNTRY_ID',
        'Order.CountryId' => 'COUNTRY_ID',
        'countryId' => 'COUNTRY_ID',
        'order.countryId' => 'COUNTRY_ID',
        'OrderTableMap::COL_COUNTRY_ID' => 'COUNTRY_ID',
        'COL_COUNTRY_ID' => 'COUNTRY_ID',
        'country_id' => 'COUNTRY_ID',
        'orders.country_id' => 'COUNTRY_ID',
        'Shipping' => 'ORDER_SHIPPING',
        'Order.Shipping' => 'ORDER_SHIPPING',
        'shipping' => 'ORDER_SHIPPING',
        'order.shipping' => 'ORDER_SHIPPING',
        'OrderTableMap::COL_ORDER_SHIPPING' => 'ORDER_SHIPPING',
        'COL_ORDER_SHIPPING' => 'ORDER_SHIPPING',
        'order_shipping' => 'ORDER_SHIPPING',
        'orders.order_shipping' => 'ORDER_SHIPPING',
        'ShippingMode' => 'ORDER_SHIPPING_MODE',
        'Order.ShippingMode' => 'ORDER_SHIPPING_MODE',
        'shippingMode' => 'ORDER_SHIPPING_MODE',
        'order.shippingMode' => 'ORDER_SHIPPING_MODE',
        'OrderTableMap::COL_ORDER_SHIPPING_MODE' => 'ORDER_SHIPPING_MODE',
        'COL_ORDER_SHIPPING_MODE' => 'ORDER_SHIPPING_MODE',
        'order_shipping_mode' => 'ORDER_SHIPPING_MODE',
        'orders.order_shipping_mode' => 'ORDER_SHIPPING_MODE',
        'TrackNumber' => 'ORDER_TRACK_NUMBER',
        'Order.TrackNumber' => 'ORDER_TRACK_NUMBER',
        'trackNumber' => 'ORDER_TRACK_NUMBER',
        'order.trackNumber' => 'ORDER_TRACK_NUMBER',
        'OrderTableMap::COL_ORDER_TRACK_NUMBER' => 'ORDER_TRACK_NUMBER',
        'COL_ORDER_TRACK_NUMBER' => 'ORDER_TRACK_NUMBER',
        'order_track_number' => 'ORDER_TRACK_NUMBER',
        'orders.order_track_number' => 'ORDER_TRACK_NUMBER',
        'PaymentMode' => 'ORDER_PAYMENT_MODE',
        'Order.PaymentMode' => 'ORDER_PAYMENT_MODE',
        'paymentMode' => 'ORDER_PAYMENT_MODE',
        'order.paymentMode' => 'ORDER_PAYMENT_MODE',
        'OrderTableMap::COL_ORDER_PAYMENT_MODE' => 'ORDER_PAYMENT_MODE',
        'COL_ORDER_PAYMENT_MODE' => 'ORDER_PAYMENT_MODE',
        'order_payment_mode' => 'ORDER_PAYMENT_MODE',
        'orders.order_payment_mode' => 'ORDER_PAYMENT_MODE',
        'PaymentCash' => 'ORDER_PAYMENT_CASH',
        'Order.PaymentCash' => 'ORDER_PAYMENT_CASH',
        'paymentCash' => 'ORDER_PAYMENT_CASH',
        'order.paymentCash' => 'ORDER_PAYMENT_CASH',
        'OrderTableMap::COL_ORDER_PAYMENT_CASH' => 'ORDER_PAYMENT_CASH',
        'COL_ORDER_PAYMENT_CASH' => 'ORDER_PAYMENT_CASH',
        'order_payment_cash' => 'ORDER_PAYMENT_CASH',
        'orders.order_payment_cash' => 'ORDER_PAYMENT_CASH',
        'PaymentCheque' => 'ORDER_PAYMENT_CHEQUE',
        'Order.PaymentCheque' => 'ORDER_PAYMENT_CHEQUE',
        'paymentCheque' => 'ORDER_PAYMENT_CHEQUE',
        'order.paymentCheque' => 'ORDER_PAYMENT_CHEQUE',
        'OrderTableMap::COL_ORDER_PAYMENT_CHEQUE' => 'ORDER_PAYMENT_CHEQUE',
        'COL_ORDER_PAYMENT_CHEQUE' => 'ORDER_PAYMENT_CHEQUE',
        'order_payment_cheque' => 'ORDER_PAYMENT_CHEQUE',
        'orders.order_payment_cheque' => 'ORDER_PAYMENT_CHEQUE',
        'PaymentTransfer' => 'ORDER_PAYMENT_TRANSFER',
        'Order.PaymentTransfer' => 'ORDER_PAYMENT_TRANSFER',
        'paymentTransfer' => 'ORDER_PAYMENT_TRANSFER',
        'order.paymentTransfer' => 'ORDER_PAYMENT_TRANSFER',
        'OrderTableMap::COL_ORDER_PAYMENT_TRANSFER' => 'ORDER_PAYMENT_TRANSFER',
        'COL_ORDER_PAYMENT_TRANSFER' => 'ORDER_PAYMENT_TRANSFER',
        'order_payment_transfer' => 'ORDER_PAYMENT_TRANSFER',
        'orders.order_payment_transfer' => 'ORDER_PAYMENT_TRANSFER',
        'PaymentCard' => 'ORDER_PAYMENT_CARD',
        'Order.PaymentCard' => 'ORDER_PAYMENT_CARD',
        'paymentCard' => 'ORDER_PAYMENT_CARD',
        'order.paymentCard' => 'ORDER_PAYMENT_CARD',
        'OrderTableMap::COL_ORDER_PAYMENT_CARD' => 'ORDER_PAYMENT_CARD',
        'COL_ORDER_PAYMENT_CARD' => 'ORDER_PAYMENT_CARD',
        'order_payment_card' => 'ORDER_PAYMENT_CARD',
        'orders.order_payment_card' => 'ORDER_PAYMENT_CARD',
        'PaymentPaypal' => 'ORDER_PAYMENT_PAYPAL',
        'Order.PaymentPaypal' => 'ORDER_PAYMENT_PAYPAL',
        'paymentPaypal' => 'ORDER_PAYMENT_PAYPAL',
        'order.paymentPaypal' => 'ORDER_PAYMENT_PAYPAL',
        'OrderTableMap::COL_ORDER_PAYMENT_PAYPAL' => 'ORDER_PAYMENT_PAYPAL',
        'COL_ORDER_PAYMENT_PAYPAL' => 'ORDER_PAYMENT_PAYPAL',
        'order_payment_paypal' => 'ORDER_PAYMENT_PAYPAL',
        'orders.order_payment_paypal' => 'ORDER_PAYMENT_PAYPAL',
        'PaymentPayplug' => 'ORDER_PAYMENT_PAYPLUG',
        'Order.PaymentPayplug' => 'ORDER_PAYMENT_PAYPLUG',
        'paymentPayplug' => 'ORDER_PAYMENT_PAYPLUG',
        'order.paymentPayplug' => 'ORDER_PAYMENT_PAYPLUG',
        'OrderTableMap::COL_ORDER_PAYMENT_PAYPLUG' => 'ORDER_PAYMENT_PAYPLUG',
        'COL_ORDER_PAYMENT_PAYPLUG' => 'ORDER_PAYMENT_PAYPLUG',
        'order_payment_payplug' => 'ORDER_PAYMENT_PAYPLUG',
        'orders.order_payment_payplug' => 'ORDER_PAYMENT_PAYPLUG',
        'PaymentLeft' => 'ORDER_PAYMENT_LEFT',
        'Order.PaymentLeft' => 'ORDER_PAYMENT_LEFT',
        'paymentLeft' => 'ORDER_PAYMENT_LEFT',
        'order.paymentLeft' => 'ORDER_PAYMENT_LEFT',
        'OrderTableMap::COL_ORDER_PAYMENT_LEFT' => 'ORDER_PAYMENT_LEFT',
        'COL_ORDER_PAYMENT_LEFT' => 'ORDER_PAYMENT_LEFT',
        'order_payment_left' => 'ORDER_PAYMENT_LEFT',
        'orders.order_payment_left' => 'ORDER_PAYMENT_LEFT',
        'Title' => 'ORDER_TITLE',
        'Order.Title' => 'ORDER_TITLE',
        'title' => 'ORDER_TITLE',
        'order.title' => 'ORDER_TITLE',
        'OrderTableMap::COL_ORDER_TITLE' => 'ORDER_TITLE',
        'COL_ORDER_TITLE' => 'ORDER_TITLE',
        'order_title' => 'ORDER_TITLE',
        'orders.order_title' => 'ORDER_TITLE',
        'Firstname' => 'ORDER_FIRSTNAME',
        'Order.Firstname' => 'ORDER_FIRSTNAME',
        'firstname' => 'ORDER_FIRSTNAME',
        'order.firstname' => 'ORDER_FIRSTNAME',
        'OrderTableMap::COL_ORDER_FIRSTNAME' => 'ORDER_FIRSTNAME',
        'COL_ORDER_FIRSTNAME' => 'ORDER_FIRSTNAME',
        'order_firstname' => 'ORDER_FIRSTNAME',
        'orders.order_firstname' => 'ORDER_FIRSTNAME',
        'Lastname' => 'ORDER_LASTNAME',
        'Order.Lastname' => 'ORDER_LASTNAME',
        'lastname' => 'ORDER_LASTNAME',
        'order.lastname' => 'ORDER_LASTNAME',
        'OrderTableMap::COL_ORDER_LASTNAME' => 'ORDER_LASTNAME',
        'COL_ORDER_LASTNAME' => 'ORDER_LASTNAME',
        'order_lastname' => 'ORDER_LASTNAME',
        'orders.order_lastname' => 'ORDER_LASTNAME',
        'Address1' => 'ORDER_ADDRESS1',
        'Order.Address1' => 'ORDER_ADDRESS1',
        'address1' => 'ORDER_ADDRESS1',
        'order.address1' => 'ORDER_ADDRESS1',
        'OrderTableMap::COL_ORDER_ADDRESS1' => 'ORDER_ADDRESS1',
        'COL_ORDER_ADDRESS1' => 'ORDER_ADDRESS1',
        'order_address1' => 'ORDER_ADDRESS1',
        'orders.order_address1' => 'ORDER_ADDRESS1',
        'Address2' => 'ORDER_ADDRESS2',
        'Order.Address2' => 'ORDER_ADDRESS2',
        'address2' => 'ORDER_ADDRESS2',
        'order.address2' => 'ORDER_ADDRESS2',
        'OrderTableMap::COL_ORDER_ADDRESS2' => 'ORDER_ADDRESS2',
        'COL_ORDER_ADDRESS2' => 'ORDER_ADDRESS2',
        'order_address2' => 'ORDER_ADDRESS2',
        'orders.order_address2' => 'ORDER_ADDRESS2',
        'Postalcode' => 'ORDER_POSTALCODE',
        'Order.Postalcode' => 'ORDER_POSTALCODE',
        'postalcode' => 'ORDER_POSTALCODE',
        'order.postalcode' => 'ORDER_POSTALCODE',
        'OrderTableMap::COL_ORDER_POSTALCODE' => 'ORDER_POSTALCODE',
        'COL_ORDER_POSTALCODE' => 'ORDER_POSTALCODE',
        'order_postalcode' => 'ORDER_POSTALCODE',
        'orders.order_postalcode' => 'ORDER_POSTALCODE',
        'City' => 'ORDER_CITY',
        'Order.City' => 'ORDER_CITY',
        'city' => 'ORDER_CITY',
        'order.city' => 'ORDER_CITY',
        'OrderTableMap::COL_ORDER_CITY' => 'ORDER_CITY',
        'COL_ORDER_CITY' => 'ORDER_CITY',
        'order_city' => 'ORDER_CITY',
        'orders.order_city' => 'ORDER_CITY',
        'Country' => 'ORDER_COUNTRY',
        'Order.Country' => 'ORDER_COUNTRY',
        'country' => 'ORDER_COUNTRY',
        'order.country' => 'ORDER_COUNTRY',
        'OrderTableMap::COL_ORDER_COUNTRY' => 'ORDER_COUNTRY',
        'COL_ORDER_COUNTRY' => 'ORDER_COUNTRY',
        'order_country' => 'ORDER_COUNTRY',
        'orders.order_country' => 'ORDER_COUNTRY',
        'Email' => 'ORDER_EMAIL',
        'Order.Email' => 'ORDER_EMAIL',
        'email' => 'ORDER_EMAIL',
        'order.email' => 'ORDER_EMAIL',
        'OrderTableMap::COL_ORDER_EMAIL' => 'ORDER_EMAIL',
        'COL_ORDER_EMAIL' => 'ORDER_EMAIL',
        'order_email' => 'ORDER_EMAIL',
        'orders.order_email' => 'ORDER_EMAIL',
        'Phone' => 'ORDER_PHONE',
        'Order.Phone' => 'ORDER_PHONE',
        'phone' => 'ORDER_PHONE',
        'order.phone' => 'ORDER_PHONE',
        'OrderTableMap::COL_ORDER_PHONE' => 'ORDER_PHONE',
        'COL_ORDER_PHONE' => 'ORDER_PHONE',
        'order_phone' => 'ORDER_PHONE',
        'orders.order_phone' => 'ORDER_PHONE',
        'Comment' => 'ORDER_COMMENT',
        'Order.Comment' => 'ORDER_COMMENT',
        'comment' => 'ORDER_COMMENT',
        'order.comment' => 'ORDER_COMMENT',
        'OrderTableMap::COL_ORDER_COMMENT' => 'ORDER_COMMENT',
        'COL_ORDER_COMMENT' => 'ORDER_COMMENT',
        'order_comment' => 'ORDER_COMMENT',
        'orders.order_comment' => 'ORDER_COMMENT',
        'Utmz' => 'ORDER_UTMZ',
        'Order.Utmz' => 'ORDER_UTMZ',
        'utmz' => 'ORDER_UTMZ',
        'order.utmz' => 'ORDER_UTMZ',
        'OrderTableMap::COL_ORDER_UTMZ' => 'ORDER_UTMZ',
        'COL_ORDER_UTMZ' => 'ORDER_UTMZ',
        'order_utmz' => 'ORDER_UTMZ',
        'orders.order_utmz' => 'ORDER_UTMZ',
        'Referer' => 'ORDER_REFERER',
        'Order.Referer' => 'ORDER_REFERER',
        'referer' => 'ORDER_REFERER',
        'order.referer' => 'ORDER_REFERER',
        'OrderTableMap::COL_ORDER_REFERER' => 'ORDER_REFERER',
        'COL_ORDER_REFERER' => 'ORDER_REFERER',
        'order_referer' => 'ORDER_REFERER',
        'orders.order_referer' => 'ORDER_REFERER',
        'Insert' => 'ORDER_INSERT',
        'Order.Insert' => 'ORDER_INSERT',
        'insert' => 'ORDER_INSERT',
        'order.insert' => 'ORDER_INSERT',
        'OrderTableMap::COL_ORDER_INSERT' => 'ORDER_INSERT',
        'COL_ORDER_INSERT' => 'ORDER_INSERT',
        'order_insert' => 'ORDER_INSERT',
        'orders.order_insert' => 'ORDER_INSERT',
        'PaymentDate' => 'ORDER_PAYMENT_DATE',
        'Order.PaymentDate' => 'ORDER_PAYMENT_DATE',
        'paymentDate' => 'ORDER_PAYMENT_DATE',
        'order.paymentDate' => 'ORDER_PAYMENT_DATE',
        'OrderTableMap::COL_ORDER_PAYMENT_DATE' => 'ORDER_PAYMENT_DATE',
        'COL_ORDER_PAYMENT_DATE' => 'ORDER_PAYMENT_DATE',
        'order_payment_date' => 'ORDER_PAYMENT_DATE',
        'orders.order_payment_date' => 'ORDER_PAYMENT_DATE',
        'ShippingDate' => 'ORDER_SHIPPING_DATE',
        'Order.ShippingDate' => 'ORDER_SHIPPING_DATE',
        'shippingDate' => 'ORDER_SHIPPING_DATE',
        'order.shippingDate' => 'ORDER_SHIPPING_DATE',
        'OrderTableMap::COL_ORDER_SHIPPING_DATE' => 'ORDER_SHIPPING_DATE',
        'COL_ORDER_SHIPPING_DATE' => 'ORDER_SHIPPING_DATE',
        'order_shipping_date' => 'ORDER_SHIPPING_DATE',
        'orders.order_shipping_date' => 'ORDER_SHIPPING_DATE',
        'FollowupDate' => 'ORDER_FOLLOWUP_DATE',
        'Order.FollowupDate' => 'ORDER_FOLLOWUP_DATE',
        'followupDate' => 'ORDER_FOLLOWUP_DATE',
        'order.followupDate' => 'ORDER_FOLLOWUP_DATE',
        'OrderTableMap::COL_ORDER_FOLLOWUP_DATE' => 'ORDER_FOLLOWUP_DATE',
        'COL_ORDER_FOLLOWUP_DATE' => 'ORDER_FOLLOWUP_DATE',
        'order_followup_date' => 'ORDER_FOLLOWUP_DATE',
        'orders.order_followup_date' => 'ORDER_FOLLOWUP_DATE',
        'ConfirmationDate' => 'ORDER_CONFIRMATION_DATE',
        'Order.ConfirmationDate' => 'ORDER_CONFIRMATION_DATE',
        'confirmationDate' => 'ORDER_CONFIRMATION_DATE',
        'order.confirmationDate' => 'ORDER_CONFIRMATION_DATE',
        'OrderTableMap::COL_ORDER_CONFIRMATION_DATE' => 'ORDER_CONFIRMATION_DATE',
        'COL_ORDER_CONFIRMATION_DATE' => 'ORDER_CONFIRMATION_DATE',
        'order_confirmation_date' => 'ORDER_CONFIRMATION_DATE',
        'orders.order_confirmation_date' => 'ORDER_CONFIRMATION_DATE',
        'CancelDate' => 'ORDER_CANCEL_DATE',
        'Order.CancelDate' => 'ORDER_CANCEL_DATE',
        'cancelDate' => 'ORDER_CANCEL_DATE',
        'order.cancelDate' => 'ORDER_CANCEL_DATE',
        'OrderTableMap::COL_ORDER_CANCEL_DATE' => 'ORDER_CANCEL_DATE',
        'COL_ORDER_CANCEL_DATE' => 'ORDER_CANCEL_DATE',
        'order_cancel_date' => 'ORDER_CANCEL_DATE',
        'orders.order_cancel_date' => 'ORDER_CANCEL_DATE',
        'Update' => 'ORDER_UPDATE',
        'Order.Update' => 'ORDER_UPDATE',
        'update' => 'ORDER_UPDATE',
        'order.update' => 'ORDER_UPDATE',
        'OrderTableMap::COL_ORDER_UPDATE' => 'ORDER_UPDATE',
        'COL_ORDER_UPDATE' => 'ORDER_UPDATE',
        'order_update' => 'ORDER_UPDATE',
        'orders.order_update' => 'ORDER_UPDATE',
        'CreatedAt' => 'ORDER_CREATED',
        'Order.CreatedAt' => 'ORDER_CREATED',
        'createdAt' => 'ORDER_CREATED',
        'order.createdAt' => 'ORDER_CREATED',
        'OrderTableMap::COL_ORDER_CREATED' => 'ORDER_CREATED',
        'COL_ORDER_CREATED' => 'ORDER_CREATED',
        'order_created' => 'ORDER_CREATED',
        'orders.order_created' => 'ORDER_CREATED',
        'UpdatedAt' => 'ORDER_UPDATED',
        'Order.UpdatedAt' => 'ORDER_UPDATED',
        'updatedAt' => 'ORDER_UPDATED',
        'order.updatedAt' => 'ORDER_UPDATED',
        'OrderTableMap::COL_ORDER_UPDATED' => 'ORDER_UPDATED',
        'COL_ORDER_UPDATED' => 'ORDER_UPDATED',
        'order_updated' => 'ORDER_UPDATED',
        'orders.order_updated' => 'ORDER_UPDATED',
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
        $this->setName('orders');
        $this->setPhpName('Order');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Order');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('order_id', 'Id', 'INTEGER', true, 10, null);
        $this->addColumn('order_url', 'Slug', 'VARCHAR', false, 16, null);
        $this->addForeignKey('site_id', 'SiteId', 'INTEGER', 'sites', 'site_id', false, null, null);
        $this->addColumn('user_id', 'UserId', 'INTEGER', false, null, null);
        $this->addColumn('customer_id', 'CustomerId', 'INTEGER', false, 10, null);
        $this->addColumn('seller_id', 'SellerId', 'INTEGER', false, 10, null);
        $this->addColumn('order_type', 'Type', 'VARCHAR', false, 8, '');
        $this->addColumn('order_as_a_gift', 'AsAGift', 'VARCHAR', false, 16, null);
        $this->addColumn('order_gift_recipient', 'GiftRecipient', 'INTEGER', false, 10, null);
        $this->addColumn('order_amount', 'Amount', 'INTEGER', false, 10, 0);
        $this->addColumn('order_discount', 'Discount', 'INTEGER', false, 10, null);
        $this->addColumn('order_amount_tobepaid', 'AmountTobepaid', 'INTEGER', false, null, 0);
        $this->addColumn('shipping_id', 'ShippingId', 'INTEGER', false, null, null);
        $this->addColumn('country_id', 'CountryId', 'INTEGER', false, null, null);
        $this->addColumn('order_shipping', 'Shipping', 'INTEGER', false, null, 0);
        $this->addColumn('order_shipping_mode', 'ShippingMode', 'VARCHAR', false, 32, null);
        $this->addColumn('order_track_number', 'TrackNumber', 'VARCHAR', false, 16, null);
        $this->addColumn('order_payment_mode', 'PaymentMode', 'VARCHAR', false, 32, null);
        $this->addColumn('order_payment_cash', 'PaymentCash', 'INTEGER', false, 10, 0);
        $this->addColumn('order_payment_cheque', 'PaymentCheque', 'INTEGER', false, 10, 0);
        $this->addColumn('order_payment_transfer', 'PaymentTransfer', 'INTEGER', false, 10, 0);
        $this->addColumn('order_payment_card', 'PaymentCard', 'INTEGER', false, 10, 0);
        $this->addColumn('order_payment_paypal', 'PaymentPaypal', 'INTEGER', false, 10, 0);
        $this->addColumn('order_payment_payplug', 'PaymentPayplug', 'INTEGER', false, null, 0);
        $this->addColumn('order_payment_left', 'PaymentLeft', 'INTEGER', false, 10, 0);
        $this->addColumn('order_title', 'Title', 'LONGVARCHAR', false, null, null);
        $this->addColumn('order_firstname', 'Firstname', 'LONGVARCHAR', false, null, null);
        $this->addColumn('order_lastname', 'Lastname', 'LONGVARCHAR', false, null, null);
        $this->addColumn('order_address1', 'Address1', 'LONGVARCHAR', false, null, null);
        $this->addColumn('order_address2', 'Address2', 'LONGVARCHAR', false, null, null);
        $this->addColumn('order_postalcode', 'Postalcode', 'LONGVARCHAR', false, null, null);
        $this->addColumn('order_city', 'City', 'LONGVARCHAR', false, null, null);
        $this->addColumn('order_country', 'Country', 'VARCHAR', false, 64, null);
        $this->addColumn('order_email', 'Email', 'LONGVARCHAR', false, null, null);
        $this->addColumn('order_phone', 'Phone', 'LONGVARCHAR', false, null, null);
        $this->addColumn('order_comment', 'Comment', 'VARCHAR', false, 1024, null);
        $this->addColumn('order_utmz', 'Utmz', 'VARCHAR', false, 1024, null);
        $this->addColumn('order_referer', 'Referer', 'LONGVARCHAR', false, null, null);
        $this->addColumn('order_insert', 'Insert', 'TIMESTAMP', false, null, null);
        $this->addColumn('order_payment_date', 'PaymentDate', 'TIMESTAMP', false, null, null);
        $this->addColumn('order_shipping_date', 'ShippingDate', 'TIMESTAMP', false, null, null);
        $this->addColumn('order_followup_date', 'FollowupDate', 'TIMESTAMP', false, null, null);
        $this->addColumn('order_confirmation_date', 'ConfirmationDate', 'TIMESTAMP', false, null, null);
        $this->addColumn('order_cancel_date', 'CancelDate', 'TIMESTAMP', false, null, null);
        $this->addColumn('order_update', 'Update', 'TIMESTAMP', false, null, null);
        $this->addColumn('order_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('order_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
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
        $this->addRelation('Payment', '\\Model\\Payment', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':order_id',
    1 => ':order_id',
  ),
), null, null, 'Payments', false);
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
            'timestampable' => ['create_column' => 'order_created', 'update_column' => 'order_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? OrderTableMap::CLASS_DEFAULT : OrderTableMap::OM_CLASS;
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
     * @return array (Order object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = OrderTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = OrderTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + OrderTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = OrderTableMap::OM_CLASS;
            /** @var Order $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            OrderTableMap::addInstanceToPool($obj, $key);
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
            $key = OrderTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = OrderTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Order $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                OrderTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_ID);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_URL);
            $criteria->addSelectColumn(OrderTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(OrderTableMap::COL_USER_ID);
            $criteria->addSelectColumn(OrderTableMap::COL_CUSTOMER_ID);
            $criteria->addSelectColumn(OrderTableMap::COL_SELLER_ID);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_TYPE);
            $criteria->addSelectColumn(OrderTableMap::COL_AS_A_GIFT);
            $criteria->addSelectColumn(OrderTableMap::COL_GIFT_RECIPIENT);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_AMOUNT);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_DISCOUNT);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_AMOUNT_TOBEPAID);
            $criteria->addSelectColumn(OrderTableMap::COL_SHIPPING_ID);
            $criteria->addSelectColumn(OrderTableMap::COL_COUNTRY_ID);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_SHIPPING);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_SHIPPING_MODE);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_TRACK_NUMBER);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_PAYMENT_MODE);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_PAYMENT_CASH);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_PAYMENT_CHEQUE);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_PAYMENT_TRANSFER);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_PAYMENT_CARD);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_PAYMENT_PAYPAL);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_PAYMENT_PAYPLUG);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_PAYMENT_LEFT);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_TITLE);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_FIRSTNAME);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_LASTNAME);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_ADDRESS1);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_ADDRESS2);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_POSTALCODE);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_CITY);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_COUNTRY);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_EMAIL);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_PHONE);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_COMMENT);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_UTMZ);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_REFERER);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_INSERT);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_PAYMENT_DATE);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_SHIPPING_DATE);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_FOLLOWUP_DATE);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_CONFIRMATION_DATE);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_CANCEL_DATE);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_UPDATE);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_CREATED);
            $criteria->addSelectColumn(OrderTableMap::COL_ORDER_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.order_id');
            $criteria->addSelectColumn($alias . '.order_url');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.customer_id');
            $criteria->addSelectColumn($alias . '.seller_id');
            $criteria->addSelectColumn($alias . '.order_type');
            $criteria->addSelectColumn($alias . '.order_as_a_gift');
            $criteria->addSelectColumn($alias . '.order_gift_recipient');
            $criteria->addSelectColumn($alias . '.order_amount');
            $criteria->addSelectColumn($alias . '.order_discount');
            $criteria->addSelectColumn($alias . '.order_amount_tobepaid');
            $criteria->addSelectColumn($alias . '.shipping_id');
            $criteria->addSelectColumn($alias . '.country_id');
            $criteria->addSelectColumn($alias . '.order_shipping');
            $criteria->addSelectColumn($alias . '.order_shipping_mode');
            $criteria->addSelectColumn($alias . '.order_track_number');
            $criteria->addSelectColumn($alias . '.order_payment_mode');
            $criteria->addSelectColumn($alias . '.order_payment_cash');
            $criteria->addSelectColumn($alias . '.order_payment_cheque');
            $criteria->addSelectColumn($alias . '.order_payment_transfer');
            $criteria->addSelectColumn($alias . '.order_payment_card');
            $criteria->addSelectColumn($alias . '.order_payment_paypal');
            $criteria->addSelectColumn($alias . '.order_payment_payplug');
            $criteria->addSelectColumn($alias . '.order_payment_left');
            $criteria->addSelectColumn($alias . '.order_title');
            $criteria->addSelectColumn($alias . '.order_firstname');
            $criteria->addSelectColumn($alias . '.order_lastname');
            $criteria->addSelectColumn($alias . '.order_address1');
            $criteria->addSelectColumn($alias . '.order_address2');
            $criteria->addSelectColumn($alias . '.order_postalcode');
            $criteria->addSelectColumn($alias . '.order_city');
            $criteria->addSelectColumn($alias . '.order_country');
            $criteria->addSelectColumn($alias . '.order_email');
            $criteria->addSelectColumn($alias . '.order_phone');
            $criteria->addSelectColumn($alias . '.order_comment');
            $criteria->addSelectColumn($alias . '.order_utmz');
            $criteria->addSelectColumn($alias . '.order_referer');
            $criteria->addSelectColumn($alias . '.order_insert');
            $criteria->addSelectColumn($alias . '.order_payment_date');
            $criteria->addSelectColumn($alias . '.order_shipping_date');
            $criteria->addSelectColumn($alias . '.order_followup_date');
            $criteria->addSelectColumn($alias . '.order_confirmation_date');
            $criteria->addSelectColumn($alias . '.order_cancel_date');
            $criteria->addSelectColumn($alias . '.order_update');
            $criteria->addSelectColumn($alias . '.order_created');
            $criteria->addSelectColumn($alias . '.order_updated');
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
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_ID);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_URL);
            $criteria->removeSelectColumn(OrderTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(OrderTableMap::COL_USER_ID);
            $criteria->removeSelectColumn(OrderTableMap::COL_CUSTOMER_ID);
            $criteria->removeSelectColumn(OrderTableMap::COL_SELLER_ID);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_TYPE);
            $criteria->removeSelectColumn(OrderTableMap::COL_AS_A_GIFT);
            $criteria->removeSelectColumn(OrderTableMap::COL_GIFT_RECIPIENT);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_AMOUNT);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_DISCOUNT);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_AMOUNT_TOBEPAID);
            $criteria->removeSelectColumn(OrderTableMap::COL_SHIPPING_ID);
            $criteria->removeSelectColumn(OrderTableMap::COL_COUNTRY_ID);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_SHIPPING);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_SHIPPING_MODE);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_TRACK_NUMBER);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_PAYMENT_MODE);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_PAYMENT_CASH);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_PAYMENT_CHEQUE);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_PAYMENT_TRANSFER);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_PAYMENT_CARD);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_PAYMENT_PAYPAL);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_PAYMENT_PAYPLUG);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_PAYMENT_LEFT);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_TITLE);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_FIRSTNAME);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_LASTNAME);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_ADDRESS1);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_ADDRESS2);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_POSTALCODE);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_CITY);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_COUNTRY);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_EMAIL);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_PHONE);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_COMMENT);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_UTMZ);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_REFERER);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_INSERT);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_PAYMENT_DATE);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_SHIPPING_DATE);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_FOLLOWUP_DATE);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_CONFIRMATION_DATE);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_CANCEL_DATE);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_UPDATE);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_CREATED);
            $criteria->removeSelectColumn(OrderTableMap::COL_ORDER_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.order_id');
            $criteria->removeSelectColumn($alias . '.order_url');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.user_id');
            $criteria->removeSelectColumn($alias . '.customer_id');
            $criteria->removeSelectColumn($alias . '.seller_id');
            $criteria->removeSelectColumn($alias . '.order_type');
            $criteria->removeSelectColumn($alias . '.order_as_a_gift');
            $criteria->removeSelectColumn($alias . '.order_gift_recipient');
            $criteria->removeSelectColumn($alias . '.order_amount');
            $criteria->removeSelectColumn($alias . '.order_discount');
            $criteria->removeSelectColumn($alias . '.order_amount_tobepaid');
            $criteria->removeSelectColumn($alias . '.shipping_id');
            $criteria->removeSelectColumn($alias . '.country_id');
            $criteria->removeSelectColumn($alias . '.order_shipping');
            $criteria->removeSelectColumn($alias . '.order_shipping_mode');
            $criteria->removeSelectColumn($alias . '.order_track_number');
            $criteria->removeSelectColumn($alias . '.order_payment_mode');
            $criteria->removeSelectColumn($alias . '.order_payment_cash');
            $criteria->removeSelectColumn($alias . '.order_payment_cheque');
            $criteria->removeSelectColumn($alias . '.order_payment_transfer');
            $criteria->removeSelectColumn($alias . '.order_payment_card');
            $criteria->removeSelectColumn($alias . '.order_payment_paypal');
            $criteria->removeSelectColumn($alias . '.order_payment_payplug');
            $criteria->removeSelectColumn($alias . '.order_payment_left');
            $criteria->removeSelectColumn($alias . '.order_title');
            $criteria->removeSelectColumn($alias . '.order_firstname');
            $criteria->removeSelectColumn($alias . '.order_lastname');
            $criteria->removeSelectColumn($alias . '.order_address1');
            $criteria->removeSelectColumn($alias . '.order_address2');
            $criteria->removeSelectColumn($alias . '.order_postalcode');
            $criteria->removeSelectColumn($alias . '.order_city');
            $criteria->removeSelectColumn($alias . '.order_country');
            $criteria->removeSelectColumn($alias . '.order_email');
            $criteria->removeSelectColumn($alias . '.order_phone');
            $criteria->removeSelectColumn($alias . '.order_comment');
            $criteria->removeSelectColumn($alias . '.order_utmz');
            $criteria->removeSelectColumn($alias . '.order_referer');
            $criteria->removeSelectColumn($alias . '.order_insert');
            $criteria->removeSelectColumn($alias . '.order_payment_date');
            $criteria->removeSelectColumn($alias . '.order_shipping_date');
            $criteria->removeSelectColumn($alias . '.order_followup_date');
            $criteria->removeSelectColumn($alias . '.order_confirmation_date');
            $criteria->removeSelectColumn($alias . '.order_cancel_date');
            $criteria->removeSelectColumn($alias . '.order_update');
            $criteria->removeSelectColumn($alias . '.order_created');
            $criteria->removeSelectColumn($alias . '.order_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(OrderTableMap::DATABASE_NAME)->getTable(OrderTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Order or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Order object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(OrderTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Order) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(OrderTableMap::DATABASE_NAME);
            $criteria->add(OrderTableMap::COL_ORDER_ID, (array) $values, Criteria::IN);
        }

        $query = OrderQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            OrderTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                OrderTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the orders table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return OrderQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Order or Criteria object.
     *
     * @param mixed $criteria Criteria or Order object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(OrderTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Order object
        }

        if ($criteria->containsKey(OrderTableMap::COL_ORDER_ID) && $criteria->keyContainsValue(OrderTableMap::COL_ORDER_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.OrderTableMap::COL_ORDER_ID.')');
        }


        // Set the correct dbName
        $query = OrderQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
