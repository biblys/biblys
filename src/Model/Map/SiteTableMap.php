<?php

namespace Model\Map;

use Model\Site;
use Model\SiteQuery;
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
 * This class defines the structure of the 'sites' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class SiteTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.SiteTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'sites';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Site';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Site';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 39;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 39;

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'sites.site_id';

    /**
     * the column name for the site_name field
     */
    public const COL_SITE_NAME = 'sites.site_name';

    /**
     * the column name for the site_pass field
     */
    public const COL_SITE_PASS = 'sites.site_pass';

    /**
     * the column name for the site_title field
     */
    public const COL_SITE_TITLE = 'sites.site_title';

    /**
     * the column name for the site_domain field
     */
    public const COL_SITE_DOMAIN = 'sites.site_domain';

    /**
     * the column name for the site_version field
     */
    public const COL_SITE_VERSION = 'sites.site_version';

    /**
     * the column name for the site_tag field
     */
    public const COL_SITE_TAG = 'sites.site_tag';

    /**
     * the column name for the site_flag field
     */
    public const COL_SITE_FLAG = 'sites.site_flag';

    /**
     * the column name for the site_contact field
     */
    public const COL_SITE_CONTACT = 'sites.site_contact';

    /**
     * the column name for the site_address field
     */
    public const COL_SITE_ADDRESS = 'sites.site_address';

    /**
     * the column name for the site_tva field
     */
    public const COL_SITE_TVA = 'sites.site_tva';

    /**
     * the column name for the site_html_renderer field
     */
    public const COL_SITE_HTML_RENDERER = 'sites.site_html_renderer';

    /**
     * the column name for the site_axys field
     */
    public const COL_SITE_AXYS = 'sites.site_axys';

    /**
     * the column name for the site_noosfere field
     */
    public const COL_SITE_NOOSFERE = 'sites.site_noosfere';

    /**
     * the column name for the site_amazon field
     */
    public const COL_SITE_AMAZON = 'sites.site_amazon';

    /**
     * the column name for the site_event_id field
     */
    public const COL_SITE_EVENT_ID = 'sites.site_event_id';

    /**
     * the column name for the site_event_date field
     */
    public const COL_SITE_EVENT_DATE = 'sites.site_event_date';

    /**
     * the column name for the site_shop field
     */
    public const COL_SITE_SHOP = 'sites.site_shop';

    /**
     * the column name for the site_vpc field
     */
    public const COL_SITE_VPC = 'sites.site_vpc';

    /**
     * the column name for the site_shipping_fee field
     */
    public const COL_SITE_SHIPPING_FEE = 'sites.site_shipping_fee';

    /**
     * the column name for the site_wishlist field
     */
    public const COL_SITE_WISHLIST = 'sites.site_wishlist';

    /**
     * the column name for the site_payment_cheque field
     */
    public const COL_SITE_PAYMENT_CHEQUE = 'sites.site_payment_cheque';

    /**
     * the column name for the site_payment_paypal field
     */
    public const COL_SITE_PAYMENT_PAYPAL = 'sites.site_payment_paypal';

    /**
     * the column name for the site_payment_payplug field
     */
    public const COL_SITE_PAYMENT_PAYPLUG = 'sites.site_payment_payplug';

    /**
     * the column name for the site_payment_transfer field
     */
    public const COL_SITE_PAYMENT_TRANSFER = 'sites.site_payment_transfer';

    /**
     * the column name for the site_bookshop field
     */
    public const COL_SITE_BOOKSHOP = 'sites.site_bookshop';

    /**
     * the column name for the site_bookshop_id field
     */
    public const COL_SITE_BOOKSHOP_ID = 'sites.site_bookshop_id';

    /**
     * the column name for the site_publisher field
     */
    public const COL_SITE_PUBLISHER = 'sites.site_publisher';

    /**
     * the column name for the site_publisher_stock field
     */
    public const COL_SITE_PUBLISHER_STOCK = 'sites.site_publisher_stock';

    /**
     * the column name for the publisher_id field
     */
    public const COL_PUBLISHER_ID = 'sites.publisher_id';

    /**
     * the column name for the site_ebook_bundle field
     */
    public const COL_SITE_EBOOK_BUNDLE = 'sites.site_ebook_bundle';

    /**
     * the column name for the site_fb_page_id field
     */
    public const COL_SITE_FB_PAGE_ID = 'sites.site_fb_page_id';

    /**
     * the column name for the site_fb_page_token field
     */
    public const COL_SITE_FB_PAGE_TOKEN = 'sites.site_fb_page_token';

    /**
     * the column name for the site_analytics_id field
     */
    public const COL_SITE_ANALYTICS_ID = 'sites.site_analytics_id';

    /**
     * the column name for the site_piwik_id field
     */
    public const COL_SITE_PIWIK_ID = 'sites.site_piwik_id';

    /**
     * the column name for the site_sitemap_updated field
     */
    public const COL_SITE_SITEMAP_UPDATED = 'sites.site_sitemap_updated';

    /**
     * the column name for the site_monitoring field
     */
    public const COL_SITE_MONITORING = 'sites.site_monitoring';

    /**
     * the column name for the site_created field
     */
    public const COL_SITE_CREATED = 'sites.site_created';

    /**
     * the column name for the site_updated field
     */
    public const COL_SITE_UPDATED = 'sites.site_updated';

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
        self::TYPE_PHPNAME       => ['Id', 'Name', 'Pass', 'Title', 'Domain', 'Version', 'Tag', 'Flag', 'Contact', 'Address', 'Tva', 'HtmlRenderer', 'Axys', 'Noosfere', 'Amazon', 'EventId', 'EventDate', 'Shop', 'Vpc', 'ShippingFee', 'Wishlist', 'PaymentCheque', 'PaymentPaypal', 'PaymentPayplug', 'PaymentTransfer', 'Bookshop', 'BookshopId', 'Publisher', 'PublisherStock', 'PublisherId', 'EbookBundle', 'FbPageId', 'FbPageToken', 'AnalyticsId', 'PiwikId', 'SitemapUpdated', 'Monitoring', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'name', 'pass', 'title', 'domain', 'version', 'tag', 'flag', 'contact', 'address', 'tva', 'htmlRenderer', 'axys', 'noosfere', 'amazon', 'eventId', 'eventDate', 'shop', 'vpc', 'shippingFee', 'wishlist', 'paymentCheque', 'paymentPaypal', 'paymentPayplug', 'paymentTransfer', 'bookshop', 'bookshopId', 'publisher', 'publisherStock', 'publisherId', 'ebookBundle', 'fbPageId', 'fbPageToken', 'analyticsId', 'piwikId', 'sitemapUpdated', 'monitoring', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [SiteTableMap::COL_SITE_ID, SiteTableMap::COL_SITE_NAME, SiteTableMap::COL_SITE_PASS, SiteTableMap::COL_SITE_TITLE, SiteTableMap::COL_SITE_DOMAIN, SiteTableMap::COL_SITE_VERSION, SiteTableMap::COL_SITE_TAG, SiteTableMap::COL_SITE_FLAG, SiteTableMap::COL_SITE_CONTACT, SiteTableMap::COL_SITE_ADDRESS, SiteTableMap::COL_SITE_TVA, SiteTableMap::COL_SITE_HTML_RENDERER, SiteTableMap::COL_SITE_AXYS, SiteTableMap::COL_SITE_NOOSFERE, SiteTableMap::COL_SITE_AMAZON, SiteTableMap::COL_SITE_EVENT_ID, SiteTableMap::COL_SITE_EVENT_DATE, SiteTableMap::COL_SITE_SHOP, SiteTableMap::COL_SITE_VPC, SiteTableMap::COL_SITE_SHIPPING_FEE, SiteTableMap::COL_SITE_WISHLIST, SiteTableMap::COL_SITE_PAYMENT_CHEQUE, SiteTableMap::COL_SITE_PAYMENT_PAYPAL, SiteTableMap::COL_SITE_PAYMENT_PAYPLUG, SiteTableMap::COL_SITE_PAYMENT_TRANSFER, SiteTableMap::COL_SITE_BOOKSHOP, SiteTableMap::COL_SITE_BOOKSHOP_ID, SiteTableMap::COL_SITE_PUBLISHER, SiteTableMap::COL_SITE_PUBLISHER_STOCK, SiteTableMap::COL_PUBLISHER_ID, SiteTableMap::COL_SITE_EBOOK_BUNDLE, SiteTableMap::COL_SITE_FB_PAGE_ID, SiteTableMap::COL_SITE_FB_PAGE_TOKEN, SiteTableMap::COL_SITE_ANALYTICS_ID, SiteTableMap::COL_SITE_PIWIK_ID, SiteTableMap::COL_SITE_SITEMAP_UPDATED, SiteTableMap::COL_SITE_MONITORING, SiteTableMap::COL_SITE_CREATED, SiteTableMap::COL_SITE_UPDATED, ],
        self::TYPE_FIELDNAME     => ['site_id', 'site_name', 'site_pass', 'site_title', 'site_domain', 'site_version', 'site_tag', 'site_flag', 'site_contact', 'site_address', 'site_tva', 'site_html_renderer', 'site_axys', 'site_noosfere', 'site_amazon', 'site_event_id', 'site_event_date', 'site_shop', 'site_vpc', 'site_shipping_fee', 'site_wishlist', 'site_payment_cheque', 'site_payment_paypal', 'site_payment_payplug', 'site_payment_transfer', 'site_bookshop', 'site_bookshop_id', 'site_publisher', 'site_publisher_stock', 'publisher_id', 'site_ebook_bundle', 'site_fb_page_id', 'site_fb_page_token', 'site_analytics_id', 'site_piwik_id', 'site_sitemap_updated', 'site_monitoring', 'site_created', 'site_updated', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'Name' => 1, 'Pass' => 2, 'Title' => 3, 'Domain' => 4, 'Version' => 5, 'Tag' => 6, 'Flag' => 7, 'Contact' => 8, 'Address' => 9, 'Tva' => 10, 'HtmlRenderer' => 11, 'Axys' => 12, 'Noosfere' => 13, 'Amazon' => 14, 'EventId' => 15, 'EventDate' => 16, 'Shop' => 17, 'Vpc' => 18, 'ShippingFee' => 19, 'Wishlist' => 20, 'PaymentCheque' => 21, 'PaymentPaypal' => 22, 'PaymentPayplug' => 23, 'PaymentTransfer' => 24, 'Bookshop' => 25, 'BookshopId' => 26, 'Publisher' => 27, 'PublisherStock' => 28, 'PublisherId' => 29, 'EbookBundle' => 30, 'FbPageId' => 31, 'FbPageToken' => 32, 'AnalyticsId' => 33, 'PiwikId' => 34, 'SitemapUpdated' => 35, 'Monitoring' => 36, 'CreatedAt' => 37, 'UpdatedAt' => 38, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'name' => 1, 'pass' => 2, 'title' => 3, 'domain' => 4, 'version' => 5, 'tag' => 6, 'flag' => 7, 'contact' => 8, 'address' => 9, 'tva' => 10, 'htmlRenderer' => 11, 'axys' => 12, 'noosfere' => 13, 'amazon' => 14, 'eventId' => 15, 'eventDate' => 16, 'shop' => 17, 'vpc' => 18, 'shippingFee' => 19, 'wishlist' => 20, 'paymentCheque' => 21, 'paymentPaypal' => 22, 'paymentPayplug' => 23, 'paymentTransfer' => 24, 'bookshop' => 25, 'bookshopId' => 26, 'publisher' => 27, 'publisherStock' => 28, 'publisherId' => 29, 'ebookBundle' => 30, 'fbPageId' => 31, 'fbPageToken' => 32, 'analyticsId' => 33, 'piwikId' => 34, 'sitemapUpdated' => 35, 'monitoring' => 36, 'createdAt' => 37, 'updatedAt' => 38, ],
        self::TYPE_COLNAME       => [SiteTableMap::COL_SITE_ID => 0, SiteTableMap::COL_SITE_NAME => 1, SiteTableMap::COL_SITE_PASS => 2, SiteTableMap::COL_SITE_TITLE => 3, SiteTableMap::COL_SITE_DOMAIN => 4, SiteTableMap::COL_SITE_VERSION => 5, SiteTableMap::COL_SITE_TAG => 6, SiteTableMap::COL_SITE_FLAG => 7, SiteTableMap::COL_SITE_CONTACT => 8, SiteTableMap::COL_SITE_ADDRESS => 9, SiteTableMap::COL_SITE_TVA => 10, SiteTableMap::COL_SITE_HTML_RENDERER => 11, SiteTableMap::COL_SITE_AXYS => 12, SiteTableMap::COL_SITE_NOOSFERE => 13, SiteTableMap::COL_SITE_AMAZON => 14, SiteTableMap::COL_SITE_EVENT_ID => 15, SiteTableMap::COL_SITE_EVENT_DATE => 16, SiteTableMap::COL_SITE_SHOP => 17, SiteTableMap::COL_SITE_VPC => 18, SiteTableMap::COL_SITE_SHIPPING_FEE => 19, SiteTableMap::COL_SITE_WISHLIST => 20, SiteTableMap::COL_SITE_PAYMENT_CHEQUE => 21, SiteTableMap::COL_SITE_PAYMENT_PAYPAL => 22, SiteTableMap::COL_SITE_PAYMENT_PAYPLUG => 23, SiteTableMap::COL_SITE_PAYMENT_TRANSFER => 24, SiteTableMap::COL_SITE_BOOKSHOP => 25, SiteTableMap::COL_SITE_BOOKSHOP_ID => 26, SiteTableMap::COL_SITE_PUBLISHER => 27, SiteTableMap::COL_SITE_PUBLISHER_STOCK => 28, SiteTableMap::COL_PUBLISHER_ID => 29, SiteTableMap::COL_SITE_EBOOK_BUNDLE => 30, SiteTableMap::COL_SITE_FB_PAGE_ID => 31, SiteTableMap::COL_SITE_FB_PAGE_TOKEN => 32, SiteTableMap::COL_SITE_ANALYTICS_ID => 33, SiteTableMap::COL_SITE_PIWIK_ID => 34, SiteTableMap::COL_SITE_SITEMAP_UPDATED => 35, SiteTableMap::COL_SITE_MONITORING => 36, SiteTableMap::COL_SITE_CREATED => 37, SiteTableMap::COL_SITE_UPDATED => 38, ],
        self::TYPE_FIELDNAME     => ['site_id' => 0, 'site_name' => 1, 'site_pass' => 2, 'site_title' => 3, 'site_domain' => 4, 'site_version' => 5, 'site_tag' => 6, 'site_flag' => 7, 'site_contact' => 8, 'site_address' => 9, 'site_tva' => 10, 'site_html_renderer' => 11, 'site_axys' => 12, 'site_noosfere' => 13, 'site_amazon' => 14, 'site_event_id' => 15, 'site_event_date' => 16, 'site_shop' => 17, 'site_vpc' => 18, 'site_shipping_fee' => 19, 'site_wishlist' => 20, 'site_payment_cheque' => 21, 'site_payment_paypal' => 22, 'site_payment_payplug' => 23, 'site_payment_transfer' => 24, 'site_bookshop' => 25, 'site_bookshop_id' => 26, 'site_publisher' => 27, 'site_publisher_stock' => 28, 'publisher_id' => 29, 'site_ebook_bundle' => 30, 'site_fb_page_id' => 31, 'site_fb_page_token' => 32, 'site_analytics_id' => 33, 'site_piwik_id' => 34, 'site_sitemap_updated' => 35, 'site_monitoring' => 36, 'site_created' => 37, 'site_updated' => 38, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'SITE_ID',
        'Site.Id' => 'SITE_ID',
        'id' => 'SITE_ID',
        'site.id' => 'SITE_ID',
        'SiteTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'sites.site_id' => 'SITE_ID',
        'Name' => 'SITE_NAME',
        'Site.Name' => 'SITE_NAME',
        'name' => 'SITE_NAME',
        'site.name' => 'SITE_NAME',
        'SiteTableMap::COL_SITE_NAME' => 'SITE_NAME',
        'COL_SITE_NAME' => 'SITE_NAME',
        'site_name' => 'SITE_NAME',
        'sites.site_name' => 'SITE_NAME',
        'Pass' => 'SITE_PASS',
        'Site.Pass' => 'SITE_PASS',
        'pass' => 'SITE_PASS',
        'site.pass' => 'SITE_PASS',
        'SiteTableMap::COL_SITE_PASS' => 'SITE_PASS',
        'COL_SITE_PASS' => 'SITE_PASS',
        'site_pass' => 'SITE_PASS',
        'sites.site_pass' => 'SITE_PASS',
        'Title' => 'SITE_TITLE',
        'Site.Title' => 'SITE_TITLE',
        'title' => 'SITE_TITLE',
        'site.title' => 'SITE_TITLE',
        'SiteTableMap::COL_SITE_TITLE' => 'SITE_TITLE',
        'COL_SITE_TITLE' => 'SITE_TITLE',
        'site_title' => 'SITE_TITLE',
        'sites.site_title' => 'SITE_TITLE',
        'Domain' => 'SITE_DOMAIN',
        'Site.Domain' => 'SITE_DOMAIN',
        'domain' => 'SITE_DOMAIN',
        'site.domain' => 'SITE_DOMAIN',
        'SiteTableMap::COL_SITE_DOMAIN' => 'SITE_DOMAIN',
        'COL_SITE_DOMAIN' => 'SITE_DOMAIN',
        'site_domain' => 'SITE_DOMAIN',
        'sites.site_domain' => 'SITE_DOMAIN',
        'Version' => 'SITE_VERSION',
        'Site.Version' => 'SITE_VERSION',
        'version' => 'SITE_VERSION',
        'site.version' => 'SITE_VERSION',
        'SiteTableMap::COL_SITE_VERSION' => 'SITE_VERSION',
        'COL_SITE_VERSION' => 'SITE_VERSION',
        'site_version' => 'SITE_VERSION',
        'sites.site_version' => 'SITE_VERSION',
        'Tag' => 'SITE_TAG',
        'Site.Tag' => 'SITE_TAG',
        'tag' => 'SITE_TAG',
        'site.tag' => 'SITE_TAG',
        'SiteTableMap::COL_SITE_TAG' => 'SITE_TAG',
        'COL_SITE_TAG' => 'SITE_TAG',
        'site_tag' => 'SITE_TAG',
        'sites.site_tag' => 'SITE_TAG',
        'Flag' => 'SITE_FLAG',
        'Site.Flag' => 'SITE_FLAG',
        'flag' => 'SITE_FLAG',
        'site.flag' => 'SITE_FLAG',
        'SiteTableMap::COL_SITE_FLAG' => 'SITE_FLAG',
        'COL_SITE_FLAG' => 'SITE_FLAG',
        'site_flag' => 'SITE_FLAG',
        'sites.site_flag' => 'SITE_FLAG',
        'Contact' => 'SITE_CONTACT',
        'Site.Contact' => 'SITE_CONTACT',
        'contact' => 'SITE_CONTACT',
        'site.contact' => 'SITE_CONTACT',
        'SiteTableMap::COL_SITE_CONTACT' => 'SITE_CONTACT',
        'COL_SITE_CONTACT' => 'SITE_CONTACT',
        'site_contact' => 'SITE_CONTACT',
        'sites.site_contact' => 'SITE_CONTACT',
        'Address' => 'SITE_ADDRESS',
        'Site.Address' => 'SITE_ADDRESS',
        'address' => 'SITE_ADDRESS',
        'site.address' => 'SITE_ADDRESS',
        'SiteTableMap::COL_SITE_ADDRESS' => 'SITE_ADDRESS',
        'COL_SITE_ADDRESS' => 'SITE_ADDRESS',
        'site_address' => 'SITE_ADDRESS',
        'sites.site_address' => 'SITE_ADDRESS',
        'Tva' => 'SITE_TVA',
        'Site.Tva' => 'SITE_TVA',
        'tva' => 'SITE_TVA',
        'site.tva' => 'SITE_TVA',
        'SiteTableMap::COL_SITE_TVA' => 'SITE_TVA',
        'COL_SITE_TVA' => 'SITE_TVA',
        'site_tva' => 'SITE_TVA',
        'sites.site_tva' => 'SITE_TVA',
        'HtmlRenderer' => 'SITE_HTML_RENDERER',
        'Site.HtmlRenderer' => 'SITE_HTML_RENDERER',
        'htmlRenderer' => 'SITE_HTML_RENDERER',
        'site.htmlRenderer' => 'SITE_HTML_RENDERER',
        'SiteTableMap::COL_SITE_HTML_RENDERER' => 'SITE_HTML_RENDERER',
        'COL_SITE_HTML_RENDERER' => 'SITE_HTML_RENDERER',
        'site_html_renderer' => 'SITE_HTML_RENDERER',
        'sites.site_html_renderer' => 'SITE_HTML_RENDERER',
        'Axys' => 'SITE_AXYS',
        'Site.Axys' => 'SITE_AXYS',
        'axys' => 'SITE_AXYS',
        'site.axys' => 'SITE_AXYS',
        'SiteTableMap::COL_SITE_AXYS' => 'SITE_AXYS',
        'COL_SITE_AXYS' => 'SITE_AXYS',
        'site_axys' => 'SITE_AXYS',
        'sites.site_axys' => 'SITE_AXYS',
        'Noosfere' => 'SITE_NOOSFERE',
        'Site.Noosfere' => 'SITE_NOOSFERE',
        'noosfere' => 'SITE_NOOSFERE',
        'site.noosfere' => 'SITE_NOOSFERE',
        'SiteTableMap::COL_SITE_NOOSFERE' => 'SITE_NOOSFERE',
        'COL_SITE_NOOSFERE' => 'SITE_NOOSFERE',
        'site_noosfere' => 'SITE_NOOSFERE',
        'sites.site_noosfere' => 'SITE_NOOSFERE',
        'Amazon' => 'SITE_AMAZON',
        'Site.Amazon' => 'SITE_AMAZON',
        'amazon' => 'SITE_AMAZON',
        'site.amazon' => 'SITE_AMAZON',
        'SiteTableMap::COL_SITE_AMAZON' => 'SITE_AMAZON',
        'COL_SITE_AMAZON' => 'SITE_AMAZON',
        'site_amazon' => 'SITE_AMAZON',
        'sites.site_amazon' => 'SITE_AMAZON',
        'EventId' => 'SITE_EVENT_ID',
        'Site.EventId' => 'SITE_EVENT_ID',
        'eventId' => 'SITE_EVENT_ID',
        'site.eventId' => 'SITE_EVENT_ID',
        'SiteTableMap::COL_SITE_EVENT_ID' => 'SITE_EVENT_ID',
        'COL_SITE_EVENT_ID' => 'SITE_EVENT_ID',
        'site_event_id' => 'SITE_EVENT_ID',
        'sites.site_event_id' => 'SITE_EVENT_ID',
        'EventDate' => 'SITE_EVENT_DATE',
        'Site.EventDate' => 'SITE_EVENT_DATE',
        'eventDate' => 'SITE_EVENT_DATE',
        'site.eventDate' => 'SITE_EVENT_DATE',
        'SiteTableMap::COL_SITE_EVENT_DATE' => 'SITE_EVENT_DATE',
        'COL_SITE_EVENT_DATE' => 'SITE_EVENT_DATE',
        'site_event_date' => 'SITE_EVENT_DATE',
        'sites.site_event_date' => 'SITE_EVENT_DATE',
        'Shop' => 'SITE_SHOP',
        'Site.Shop' => 'SITE_SHOP',
        'shop' => 'SITE_SHOP',
        'site.shop' => 'SITE_SHOP',
        'SiteTableMap::COL_SITE_SHOP' => 'SITE_SHOP',
        'COL_SITE_SHOP' => 'SITE_SHOP',
        'site_shop' => 'SITE_SHOP',
        'sites.site_shop' => 'SITE_SHOP',
        'Vpc' => 'SITE_VPC',
        'Site.Vpc' => 'SITE_VPC',
        'vpc' => 'SITE_VPC',
        'site.vpc' => 'SITE_VPC',
        'SiteTableMap::COL_SITE_VPC' => 'SITE_VPC',
        'COL_SITE_VPC' => 'SITE_VPC',
        'site_vpc' => 'SITE_VPC',
        'sites.site_vpc' => 'SITE_VPC',
        'ShippingFee' => 'SITE_SHIPPING_FEE',
        'Site.ShippingFee' => 'SITE_SHIPPING_FEE',
        'shippingFee' => 'SITE_SHIPPING_FEE',
        'site.shippingFee' => 'SITE_SHIPPING_FEE',
        'SiteTableMap::COL_SITE_SHIPPING_FEE' => 'SITE_SHIPPING_FEE',
        'COL_SITE_SHIPPING_FEE' => 'SITE_SHIPPING_FEE',
        'site_shipping_fee' => 'SITE_SHIPPING_FEE',
        'sites.site_shipping_fee' => 'SITE_SHIPPING_FEE',
        'Wishlist' => 'SITE_WISHLIST',
        'Site.Wishlist' => 'SITE_WISHLIST',
        'wishlist' => 'SITE_WISHLIST',
        'site.wishlist' => 'SITE_WISHLIST',
        'SiteTableMap::COL_SITE_WISHLIST' => 'SITE_WISHLIST',
        'COL_SITE_WISHLIST' => 'SITE_WISHLIST',
        'site_wishlist' => 'SITE_WISHLIST',
        'sites.site_wishlist' => 'SITE_WISHLIST',
        'PaymentCheque' => 'SITE_PAYMENT_CHEQUE',
        'Site.PaymentCheque' => 'SITE_PAYMENT_CHEQUE',
        'paymentCheque' => 'SITE_PAYMENT_CHEQUE',
        'site.paymentCheque' => 'SITE_PAYMENT_CHEQUE',
        'SiteTableMap::COL_SITE_PAYMENT_CHEQUE' => 'SITE_PAYMENT_CHEQUE',
        'COL_SITE_PAYMENT_CHEQUE' => 'SITE_PAYMENT_CHEQUE',
        'site_payment_cheque' => 'SITE_PAYMENT_CHEQUE',
        'sites.site_payment_cheque' => 'SITE_PAYMENT_CHEQUE',
        'PaymentPaypal' => 'SITE_PAYMENT_PAYPAL',
        'Site.PaymentPaypal' => 'SITE_PAYMENT_PAYPAL',
        'paymentPaypal' => 'SITE_PAYMENT_PAYPAL',
        'site.paymentPaypal' => 'SITE_PAYMENT_PAYPAL',
        'SiteTableMap::COL_SITE_PAYMENT_PAYPAL' => 'SITE_PAYMENT_PAYPAL',
        'COL_SITE_PAYMENT_PAYPAL' => 'SITE_PAYMENT_PAYPAL',
        'site_payment_paypal' => 'SITE_PAYMENT_PAYPAL',
        'sites.site_payment_paypal' => 'SITE_PAYMENT_PAYPAL',
        'PaymentPayplug' => 'SITE_PAYMENT_PAYPLUG',
        'Site.PaymentPayplug' => 'SITE_PAYMENT_PAYPLUG',
        'paymentPayplug' => 'SITE_PAYMENT_PAYPLUG',
        'site.paymentPayplug' => 'SITE_PAYMENT_PAYPLUG',
        'SiteTableMap::COL_SITE_PAYMENT_PAYPLUG' => 'SITE_PAYMENT_PAYPLUG',
        'COL_SITE_PAYMENT_PAYPLUG' => 'SITE_PAYMENT_PAYPLUG',
        'site_payment_payplug' => 'SITE_PAYMENT_PAYPLUG',
        'sites.site_payment_payplug' => 'SITE_PAYMENT_PAYPLUG',
        'PaymentTransfer' => 'SITE_PAYMENT_TRANSFER',
        'Site.PaymentTransfer' => 'SITE_PAYMENT_TRANSFER',
        'paymentTransfer' => 'SITE_PAYMENT_TRANSFER',
        'site.paymentTransfer' => 'SITE_PAYMENT_TRANSFER',
        'SiteTableMap::COL_SITE_PAYMENT_TRANSFER' => 'SITE_PAYMENT_TRANSFER',
        'COL_SITE_PAYMENT_TRANSFER' => 'SITE_PAYMENT_TRANSFER',
        'site_payment_transfer' => 'SITE_PAYMENT_TRANSFER',
        'sites.site_payment_transfer' => 'SITE_PAYMENT_TRANSFER',
        'Bookshop' => 'SITE_BOOKSHOP',
        'Site.Bookshop' => 'SITE_BOOKSHOP',
        'bookshop' => 'SITE_BOOKSHOP',
        'site.bookshop' => 'SITE_BOOKSHOP',
        'SiteTableMap::COL_SITE_BOOKSHOP' => 'SITE_BOOKSHOP',
        'COL_SITE_BOOKSHOP' => 'SITE_BOOKSHOP',
        'site_bookshop' => 'SITE_BOOKSHOP',
        'sites.site_bookshop' => 'SITE_BOOKSHOP',
        'BookshopId' => 'SITE_BOOKSHOP_ID',
        'Site.BookshopId' => 'SITE_BOOKSHOP_ID',
        'bookshopId' => 'SITE_BOOKSHOP_ID',
        'site.bookshopId' => 'SITE_BOOKSHOP_ID',
        'SiteTableMap::COL_SITE_BOOKSHOP_ID' => 'SITE_BOOKSHOP_ID',
        'COL_SITE_BOOKSHOP_ID' => 'SITE_BOOKSHOP_ID',
        'site_bookshop_id' => 'SITE_BOOKSHOP_ID',
        'sites.site_bookshop_id' => 'SITE_BOOKSHOP_ID',
        'Publisher' => 'SITE_PUBLISHER',
        'Site.Publisher' => 'SITE_PUBLISHER',
        'publisher' => 'SITE_PUBLISHER',
        'site.publisher' => 'SITE_PUBLISHER',
        'SiteTableMap::COL_SITE_PUBLISHER' => 'SITE_PUBLISHER',
        'COL_SITE_PUBLISHER' => 'SITE_PUBLISHER',
        'site_publisher' => 'SITE_PUBLISHER',
        'sites.site_publisher' => 'SITE_PUBLISHER',
        'PublisherStock' => 'SITE_PUBLISHER_STOCK',
        'Site.PublisherStock' => 'SITE_PUBLISHER_STOCK',
        'publisherStock' => 'SITE_PUBLISHER_STOCK',
        'site.publisherStock' => 'SITE_PUBLISHER_STOCK',
        'SiteTableMap::COL_SITE_PUBLISHER_STOCK' => 'SITE_PUBLISHER_STOCK',
        'COL_SITE_PUBLISHER_STOCK' => 'SITE_PUBLISHER_STOCK',
        'site_publisher_stock' => 'SITE_PUBLISHER_STOCK',
        'sites.site_publisher_stock' => 'SITE_PUBLISHER_STOCK',
        'PublisherId' => 'PUBLISHER_ID',
        'Site.PublisherId' => 'PUBLISHER_ID',
        'publisherId' => 'PUBLISHER_ID',
        'site.publisherId' => 'PUBLISHER_ID',
        'SiteTableMap::COL_PUBLISHER_ID' => 'PUBLISHER_ID',
        'COL_PUBLISHER_ID' => 'PUBLISHER_ID',
        'publisher_id' => 'PUBLISHER_ID',
        'sites.publisher_id' => 'PUBLISHER_ID',
        'EbookBundle' => 'SITE_EBOOK_BUNDLE',
        'Site.EbookBundle' => 'SITE_EBOOK_BUNDLE',
        'ebookBundle' => 'SITE_EBOOK_BUNDLE',
        'site.ebookBundle' => 'SITE_EBOOK_BUNDLE',
        'SiteTableMap::COL_SITE_EBOOK_BUNDLE' => 'SITE_EBOOK_BUNDLE',
        'COL_SITE_EBOOK_BUNDLE' => 'SITE_EBOOK_BUNDLE',
        'site_ebook_bundle' => 'SITE_EBOOK_BUNDLE',
        'sites.site_ebook_bundle' => 'SITE_EBOOK_BUNDLE',
        'FbPageId' => 'SITE_FB_PAGE_ID',
        'Site.FbPageId' => 'SITE_FB_PAGE_ID',
        'fbPageId' => 'SITE_FB_PAGE_ID',
        'site.fbPageId' => 'SITE_FB_PAGE_ID',
        'SiteTableMap::COL_SITE_FB_PAGE_ID' => 'SITE_FB_PAGE_ID',
        'COL_SITE_FB_PAGE_ID' => 'SITE_FB_PAGE_ID',
        'site_fb_page_id' => 'SITE_FB_PAGE_ID',
        'sites.site_fb_page_id' => 'SITE_FB_PAGE_ID',
        'FbPageToken' => 'SITE_FB_PAGE_TOKEN',
        'Site.FbPageToken' => 'SITE_FB_PAGE_TOKEN',
        'fbPageToken' => 'SITE_FB_PAGE_TOKEN',
        'site.fbPageToken' => 'SITE_FB_PAGE_TOKEN',
        'SiteTableMap::COL_SITE_FB_PAGE_TOKEN' => 'SITE_FB_PAGE_TOKEN',
        'COL_SITE_FB_PAGE_TOKEN' => 'SITE_FB_PAGE_TOKEN',
        'site_fb_page_token' => 'SITE_FB_PAGE_TOKEN',
        'sites.site_fb_page_token' => 'SITE_FB_PAGE_TOKEN',
        'AnalyticsId' => 'SITE_ANALYTICS_ID',
        'Site.AnalyticsId' => 'SITE_ANALYTICS_ID',
        'analyticsId' => 'SITE_ANALYTICS_ID',
        'site.analyticsId' => 'SITE_ANALYTICS_ID',
        'SiteTableMap::COL_SITE_ANALYTICS_ID' => 'SITE_ANALYTICS_ID',
        'COL_SITE_ANALYTICS_ID' => 'SITE_ANALYTICS_ID',
        'site_analytics_id' => 'SITE_ANALYTICS_ID',
        'sites.site_analytics_id' => 'SITE_ANALYTICS_ID',
        'PiwikId' => 'SITE_PIWIK_ID',
        'Site.PiwikId' => 'SITE_PIWIK_ID',
        'piwikId' => 'SITE_PIWIK_ID',
        'site.piwikId' => 'SITE_PIWIK_ID',
        'SiteTableMap::COL_SITE_PIWIK_ID' => 'SITE_PIWIK_ID',
        'COL_SITE_PIWIK_ID' => 'SITE_PIWIK_ID',
        'site_piwik_id' => 'SITE_PIWIK_ID',
        'sites.site_piwik_id' => 'SITE_PIWIK_ID',
        'SitemapUpdated' => 'SITE_SITEMAP_UPDATED',
        'Site.SitemapUpdated' => 'SITE_SITEMAP_UPDATED',
        'sitemapUpdated' => 'SITE_SITEMAP_UPDATED',
        'site.sitemapUpdated' => 'SITE_SITEMAP_UPDATED',
        'SiteTableMap::COL_SITE_SITEMAP_UPDATED' => 'SITE_SITEMAP_UPDATED',
        'COL_SITE_SITEMAP_UPDATED' => 'SITE_SITEMAP_UPDATED',
        'site_sitemap_updated' => 'SITE_SITEMAP_UPDATED',
        'sites.site_sitemap_updated' => 'SITE_SITEMAP_UPDATED',
        'Monitoring' => 'SITE_MONITORING',
        'Site.Monitoring' => 'SITE_MONITORING',
        'monitoring' => 'SITE_MONITORING',
        'site.monitoring' => 'SITE_MONITORING',
        'SiteTableMap::COL_SITE_MONITORING' => 'SITE_MONITORING',
        'COL_SITE_MONITORING' => 'SITE_MONITORING',
        'site_monitoring' => 'SITE_MONITORING',
        'sites.site_monitoring' => 'SITE_MONITORING',
        'CreatedAt' => 'SITE_CREATED',
        'Site.CreatedAt' => 'SITE_CREATED',
        'createdAt' => 'SITE_CREATED',
        'site.createdAt' => 'SITE_CREATED',
        'SiteTableMap::COL_SITE_CREATED' => 'SITE_CREATED',
        'COL_SITE_CREATED' => 'SITE_CREATED',
        'site_created' => 'SITE_CREATED',
        'sites.site_created' => 'SITE_CREATED',
        'UpdatedAt' => 'SITE_UPDATED',
        'Site.UpdatedAt' => 'SITE_UPDATED',
        'updatedAt' => 'SITE_UPDATED',
        'site.updatedAt' => 'SITE_UPDATED',
        'SiteTableMap::COL_SITE_UPDATED' => 'SITE_UPDATED',
        'COL_SITE_UPDATED' => 'SITE_UPDATED',
        'site_updated' => 'SITE_UPDATED',
        'sites.site_updated' => 'SITE_UPDATED',
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
        $this->setName('sites');
        $this->setPhpName('Site');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Site');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('site_id', 'Id', 'INTEGER', true, 10, null);
        $this->addColumn('site_name', 'Name', 'VARCHAR', false, 16, '');
        $this->addColumn('site_pass', 'Pass', 'VARCHAR', false, 8, '');
        $this->addColumn('site_title', 'Title', 'VARCHAR', false, 32, '');
        $this->addColumn('site_domain', 'Domain', 'VARCHAR', false, 255, null);
        $this->addColumn('site_version', 'Version', 'VARCHAR', false, 16, null);
        $this->addColumn('site_tag', 'Tag', 'VARCHAR', false, 16, '');
        $this->addColumn('site_flag', 'Flag', 'VARCHAR', false, 2, null);
        $this->addColumn('site_contact', 'Contact', 'VARCHAR', false, 128, '');
        $this->addColumn('site_address', 'Address', 'VARCHAR', false, 256, '');
        $this->addColumn('site_tva', 'Tva', 'VARCHAR', false, 2, null);
        $this->addColumn('site_html_renderer', 'HtmlRenderer', 'BOOLEAN', false, 1, null);
        $this->addColumn('site_axys', 'Axys', 'BOOLEAN', false, 1, true);
        $this->addColumn('site_noosfere', 'Noosfere', 'BOOLEAN', false, 1, null);
        $this->addColumn('site_amazon', 'Amazon', 'BOOLEAN', false, 1, null);
        $this->addColumn('site_event_id', 'EventId', 'INTEGER', false, 10, null);
        $this->addColumn('site_event_date', 'EventDate', 'INTEGER', false, null, null);
        $this->addColumn('site_shop', 'Shop', 'BOOLEAN', false, 1, null);
        $this->addColumn('site_vpc', 'Vpc', 'BOOLEAN', false, 1, null);
        $this->addColumn('site_shipping_fee', 'ShippingFee', 'VARCHAR', false, 8, null);
        $this->addColumn('site_wishlist', 'Wishlist', 'BOOLEAN', false, 1, false);
        $this->addColumn('site_payment_cheque', 'PaymentCheque', 'BOOLEAN', false, 1, true);
        $this->addColumn('site_payment_paypal', 'PaymentPaypal', 'VARCHAR', false, 32, null);
        $this->addColumn('site_payment_payplug', 'PaymentPayplug', 'BOOLEAN', false, 1, false);
        $this->addColumn('site_payment_transfer', 'PaymentTransfer', 'BOOLEAN', false, 1, null);
        $this->addColumn('site_bookshop', 'Bookshop', 'BOOLEAN', false, 1, false);
        $this->addColumn('site_bookshop_id', 'BookshopId', 'INTEGER', false, 10, null);
        $this->addColumn('site_publisher', 'Publisher', 'BOOLEAN', false, 1, null);
        $this->addColumn('site_publisher_stock', 'PublisherStock', 'BOOLEAN', false, 1, false);
        $this->addColumn('publisher_id', 'PublisherId', 'INTEGER', false, 10, null);
        $this->addColumn('site_ebook_bundle', 'EbookBundle', 'INTEGER', false, null, null);
        $this->addColumn('site_fb_page_id', 'FbPageId', 'BIGINT', false, null, null);
        $this->addColumn('site_fb_page_token', 'FbPageToken', 'LONGVARCHAR', false, null, null);
        $this->addColumn('site_analytics_id', 'AnalyticsId', 'VARCHAR', false, 16, null);
        $this->addColumn('site_piwik_id', 'PiwikId', 'INTEGER', false, null, null);
        $this->addColumn('site_sitemap_updated', 'SitemapUpdated', 'TIMESTAMP', false, null, null);
        $this->addColumn('site_monitoring', 'Monitoring', 'BOOLEAN', false, 1, true);
        $this->addColumn('site_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('site_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
        $this->addRelation('Cart', '\\Model\\Cart', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':site_id',
    1 => ':site_id',
  ),
), null, null, 'Carts', false);
        $this->addRelation('Option', '\\Model\\Option', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':site_id',
    1 => ':site_id',
  ),
), null, null, 'Options', false);
        $this->addRelation('Order', '\\Model\\Order', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':site_id',
    1 => ':site_id',
  ),
), null, null, 'Orders', false);
        $this->addRelation('Payment', '\\Model\\Payment', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':site_id',
    1 => ':site_id',
  ),
), null, null, 'Payments', false);
        $this->addRelation('Right', '\\Model\\Right', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':site_id',
    1 => ':site_id',
  ),
), null, null, 'Rights', false);
        $this->addRelation('Session', '\\Model\\Session', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':site_id',
    1 => ':site_id',
  ),
), null, null, 'Sessions', false);
        $this->addRelation('Stock', '\\Model\\Stock', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':site_id',
    1 => ':site_id',
  ),
), null, null, 'Stocks', false);
        $this->addRelation('User', '\\Model\\User', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':site_id',
    1 => ':site_id',
  ),
), null, null, 'Users', false);
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
            'timestampable' => ['create_column' => 'site_created', 'update_column' => 'site_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? SiteTableMap::CLASS_DEFAULT : SiteTableMap::OM_CLASS;
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
     * @return array (Site object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = SiteTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = SiteTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + SiteTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SiteTableMap::OM_CLASS;
            /** @var Site $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            SiteTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
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
            $key = SiteTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = SiteTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Site $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                SiteTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_NAME);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_PASS);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_TITLE);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_DOMAIN);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_VERSION);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_TAG);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_FLAG);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_CONTACT);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_ADDRESS);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_TVA);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_HTML_RENDERER);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_AXYS);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_NOOSFERE);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_AMAZON);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_EVENT_ID);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_EVENT_DATE);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_SHOP);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_VPC);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_SHIPPING_FEE);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_WISHLIST);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_PAYMENT_CHEQUE);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_PAYMENT_PAYPAL);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_PAYMENT_PAYPLUG);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_PAYMENT_TRANSFER);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_BOOKSHOP);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_BOOKSHOP_ID);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_PUBLISHER);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_PUBLISHER_STOCK);
            $criteria->addSelectColumn(SiteTableMap::COL_PUBLISHER_ID);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_EBOOK_BUNDLE);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_FB_PAGE_ID);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_FB_PAGE_TOKEN);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_ANALYTICS_ID);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_PIWIK_ID);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_SITEMAP_UPDATED);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_MONITORING);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_CREATED);
            $criteria->addSelectColumn(SiteTableMap::COL_SITE_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.site_name');
            $criteria->addSelectColumn($alias . '.site_pass');
            $criteria->addSelectColumn($alias . '.site_title');
            $criteria->addSelectColumn($alias . '.site_domain');
            $criteria->addSelectColumn($alias . '.site_version');
            $criteria->addSelectColumn($alias . '.site_tag');
            $criteria->addSelectColumn($alias . '.site_flag');
            $criteria->addSelectColumn($alias . '.site_contact');
            $criteria->addSelectColumn($alias . '.site_address');
            $criteria->addSelectColumn($alias . '.site_tva');
            $criteria->addSelectColumn($alias . '.site_html_renderer');
            $criteria->addSelectColumn($alias . '.site_axys');
            $criteria->addSelectColumn($alias . '.site_noosfere');
            $criteria->addSelectColumn($alias . '.site_amazon');
            $criteria->addSelectColumn($alias . '.site_event_id');
            $criteria->addSelectColumn($alias . '.site_event_date');
            $criteria->addSelectColumn($alias . '.site_shop');
            $criteria->addSelectColumn($alias . '.site_vpc');
            $criteria->addSelectColumn($alias . '.site_shipping_fee');
            $criteria->addSelectColumn($alias . '.site_wishlist');
            $criteria->addSelectColumn($alias . '.site_payment_cheque');
            $criteria->addSelectColumn($alias . '.site_payment_paypal');
            $criteria->addSelectColumn($alias . '.site_payment_payplug');
            $criteria->addSelectColumn($alias . '.site_payment_transfer');
            $criteria->addSelectColumn($alias . '.site_bookshop');
            $criteria->addSelectColumn($alias . '.site_bookshop_id');
            $criteria->addSelectColumn($alias . '.site_publisher');
            $criteria->addSelectColumn($alias . '.site_publisher_stock');
            $criteria->addSelectColumn($alias . '.publisher_id');
            $criteria->addSelectColumn($alias . '.site_ebook_bundle');
            $criteria->addSelectColumn($alias . '.site_fb_page_id');
            $criteria->addSelectColumn($alias . '.site_fb_page_token');
            $criteria->addSelectColumn($alias . '.site_analytics_id');
            $criteria->addSelectColumn($alias . '.site_piwik_id');
            $criteria->addSelectColumn($alias . '.site_sitemap_updated');
            $criteria->addSelectColumn($alias . '.site_monitoring');
            $criteria->addSelectColumn($alias . '.site_created');
            $criteria->addSelectColumn($alias . '.site_updated');
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
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_NAME);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_PASS);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_TITLE);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_DOMAIN);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_VERSION);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_TAG);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_FLAG);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_CONTACT);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_ADDRESS);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_TVA);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_HTML_RENDERER);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_AXYS);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_NOOSFERE);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_AMAZON);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_EVENT_ID);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_EVENT_DATE);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_SHOP);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_VPC);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_SHIPPING_FEE);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_WISHLIST);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_PAYMENT_CHEQUE);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_PAYMENT_PAYPAL);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_PAYMENT_PAYPLUG);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_PAYMENT_TRANSFER);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_BOOKSHOP);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_BOOKSHOP_ID);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_PUBLISHER);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_PUBLISHER_STOCK);
            $criteria->removeSelectColumn(SiteTableMap::COL_PUBLISHER_ID);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_EBOOK_BUNDLE);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_FB_PAGE_ID);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_FB_PAGE_TOKEN);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_ANALYTICS_ID);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_PIWIK_ID);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_SITEMAP_UPDATED);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_MONITORING);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_CREATED);
            $criteria->removeSelectColumn(SiteTableMap::COL_SITE_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.site_name');
            $criteria->removeSelectColumn($alias . '.site_pass');
            $criteria->removeSelectColumn($alias . '.site_title');
            $criteria->removeSelectColumn($alias . '.site_domain');
            $criteria->removeSelectColumn($alias . '.site_version');
            $criteria->removeSelectColumn($alias . '.site_tag');
            $criteria->removeSelectColumn($alias . '.site_flag');
            $criteria->removeSelectColumn($alias . '.site_contact');
            $criteria->removeSelectColumn($alias . '.site_address');
            $criteria->removeSelectColumn($alias . '.site_tva');
            $criteria->removeSelectColumn($alias . '.site_html_renderer');
            $criteria->removeSelectColumn($alias . '.site_axys');
            $criteria->removeSelectColumn($alias . '.site_noosfere');
            $criteria->removeSelectColumn($alias . '.site_amazon');
            $criteria->removeSelectColumn($alias . '.site_event_id');
            $criteria->removeSelectColumn($alias . '.site_event_date');
            $criteria->removeSelectColumn($alias . '.site_shop');
            $criteria->removeSelectColumn($alias . '.site_vpc');
            $criteria->removeSelectColumn($alias . '.site_shipping_fee');
            $criteria->removeSelectColumn($alias . '.site_wishlist');
            $criteria->removeSelectColumn($alias . '.site_payment_cheque');
            $criteria->removeSelectColumn($alias . '.site_payment_paypal');
            $criteria->removeSelectColumn($alias . '.site_payment_payplug');
            $criteria->removeSelectColumn($alias . '.site_payment_transfer');
            $criteria->removeSelectColumn($alias . '.site_bookshop');
            $criteria->removeSelectColumn($alias . '.site_bookshop_id');
            $criteria->removeSelectColumn($alias . '.site_publisher');
            $criteria->removeSelectColumn($alias . '.site_publisher_stock');
            $criteria->removeSelectColumn($alias . '.publisher_id');
            $criteria->removeSelectColumn($alias . '.site_ebook_bundle');
            $criteria->removeSelectColumn($alias . '.site_fb_page_id');
            $criteria->removeSelectColumn($alias . '.site_fb_page_token');
            $criteria->removeSelectColumn($alias . '.site_analytics_id');
            $criteria->removeSelectColumn($alias . '.site_piwik_id');
            $criteria->removeSelectColumn($alias . '.site_sitemap_updated');
            $criteria->removeSelectColumn($alias . '.site_monitoring');
            $criteria->removeSelectColumn($alias . '.site_created');
            $criteria->removeSelectColumn($alias . '.site_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(SiteTableMap::DATABASE_NAME)->getTable(SiteTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Site or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Site object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(SiteTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Site) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(SiteTableMap::DATABASE_NAME);
            $criteria->add(SiteTableMap::COL_SITE_ID, (array) $values, Criteria::IN);
        }

        $query = SiteQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            SiteTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                SiteTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the sites table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return SiteQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Site or Criteria object.
     *
     * @param mixed $criteria Criteria or Site object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SiteTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Site object
        }

        if ($criteria->containsKey(SiteTableMap::COL_SITE_ID) && $criteria->keyContainsValue(SiteTableMap::COL_SITE_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.SiteTableMap::COL_SITE_ID.')');
        }


        // Set the correct dbName
        $query = SiteQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
