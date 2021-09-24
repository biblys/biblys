<?php

namespace Model\Map;

use Model\Link;
use Model\LinkQuery;
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
 * This class defines the structure of the 'links' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class LinkTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Model.Map.LinkTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'links';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\Link';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.Link';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 25;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 25;

    /**
     * the column name for the link_id field
     */
    const COL_LINK_ID = 'links.link_id';

    /**
     * the column name for the site_id field
     */
    const COL_SITE_ID = 'links.site_id';

    /**
     * the column name for the user_id field
     */
    const COL_USER_ID = 'links.user_id';

    /**
     * the column name for the article_id field
     */
    const COL_ARTICLE_ID = 'links.article_id';

    /**
     * the column name for the stock_id field
     */
    const COL_STOCK_ID = 'links.stock_id';

    /**
     * the column name for the list_id field
     */
    const COL_LIST_ID = 'links.list_id';

    /**
     * the column name for the book_id field
     */
    const COL_BOOK_ID = 'links.book_id';

    /**
     * the column name for the people_id field
     */
    const COL_PEOPLE_ID = 'links.people_id';

    /**
     * the column name for the job_id field
     */
    const COL_JOB_ID = 'links.job_id';

    /**
     * the column name for the rayon_id field
     */
    const COL_RAYON_ID = 'links.rayon_id';

    /**
     * the column name for the tag_id field
     */
    const COL_TAG_ID = 'links.tag_id';

    /**
     * the column name for the event_id field
     */
    const COL_EVENT_ID = 'links.event_id';

    /**
     * the column name for the post_id field
     */
    const COL_POST_ID = 'links.post_id';

    /**
     * the column name for the collection_id field
     */
    const COL_COLLECTION_ID = 'links.collection_id';

    /**
     * the column name for the publisher_id field
     */
    const COL_PUBLISHER_ID = 'links.publisher_id';

    /**
     * the column name for the supplier_id field
     */
    const COL_SUPPLIER_ID = 'links.supplier_id';

    /**
     * the column name for the media_id field
     */
    const COL_MEDIA_ID = 'links.media_id';

    /**
     * the column name for the bundle_id field
     */
    const COL_BUNDLE_ID = 'links.bundle_id';

    /**
     * the column name for the link_hide field
     */
    const COL_LINK_HIDE = 'links.link_hide';

    /**
     * the column name for the link_do_not_reorder field
     */
    const COL_LINK_DO_NOT_REORDER = 'links.link_do_not_reorder';

    /**
     * the column name for the link_sponsor_user_id field
     */
    const COL_LINK_SPONSOR_USER_ID = 'links.link_sponsor_user_id';

    /**
     * the column name for the link_date field
     */
    const COL_LINK_DATE = 'links.link_date';

    /**
     * the column name for the link_created field
     */
    const COL_LINK_CREATED = 'links.link_created';

    /**
     * the column name for the link_updated field
     */
    const COL_LINK_UPDATED = 'links.link_updated';

    /**
     * the column name for the link_deleted field
     */
    const COL_LINK_DELETED = 'links.link_deleted';

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
        self::TYPE_PHPNAME       => array('Id', 'SiteId', 'UserId', 'ArticleId', 'StockId', 'ListId', 'BookId', 'PeopleId', 'JobId', 'RayonId', 'TagId', 'EventId', 'PostId', 'CollectionId', 'PublisherId', 'SupplierId', 'MediaId', 'BundleId', 'Hide', 'DoNotReorder', 'SponsorUserId', 'Date', 'CreatedAt', 'UpdatedAt', 'DeletedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'siteId', 'userId', 'articleId', 'stockId', 'listId', 'bookId', 'peopleId', 'jobId', 'rayonId', 'tagId', 'eventId', 'postId', 'collectionId', 'publisherId', 'supplierId', 'mediaId', 'bundleId', 'hide', 'doNotReorder', 'sponsorUserId', 'date', 'createdAt', 'updatedAt', 'deletedAt', ),
        self::TYPE_COLNAME       => array(LinkTableMap::COL_LINK_ID, LinkTableMap::COL_SITE_ID, LinkTableMap::COL_USER_ID, LinkTableMap::COL_ARTICLE_ID, LinkTableMap::COL_STOCK_ID, LinkTableMap::COL_LIST_ID, LinkTableMap::COL_BOOK_ID, LinkTableMap::COL_PEOPLE_ID, LinkTableMap::COL_JOB_ID, LinkTableMap::COL_RAYON_ID, LinkTableMap::COL_TAG_ID, LinkTableMap::COL_EVENT_ID, LinkTableMap::COL_POST_ID, LinkTableMap::COL_COLLECTION_ID, LinkTableMap::COL_PUBLISHER_ID, LinkTableMap::COL_SUPPLIER_ID, LinkTableMap::COL_MEDIA_ID, LinkTableMap::COL_BUNDLE_ID, LinkTableMap::COL_LINK_HIDE, LinkTableMap::COL_LINK_DO_NOT_REORDER, LinkTableMap::COL_LINK_SPONSOR_USER_ID, LinkTableMap::COL_LINK_DATE, LinkTableMap::COL_LINK_CREATED, LinkTableMap::COL_LINK_UPDATED, LinkTableMap::COL_LINK_DELETED, ),
        self::TYPE_FIELDNAME     => array('link_id', 'site_id', 'user_id', 'article_id', 'stock_id', 'list_id', 'book_id', 'people_id', 'job_id', 'rayon_id', 'tag_id', 'event_id', 'post_id', 'collection_id', 'publisher_id', 'supplier_id', 'media_id', 'bundle_id', 'link_hide', 'link_do_not_reorder', 'link_sponsor_user_id', 'link_date', 'link_created', 'link_updated', 'link_deleted', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'SiteId' => 1, 'UserId' => 2, 'ArticleId' => 3, 'StockId' => 4, 'ListId' => 5, 'BookId' => 6, 'PeopleId' => 7, 'JobId' => 8, 'RayonId' => 9, 'TagId' => 10, 'EventId' => 11, 'PostId' => 12, 'CollectionId' => 13, 'PublisherId' => 14, 'SupplierId' => 15, 'MediaId' => 16, 'BundleId' => 17, 'Hide' => 18, 'DoNotReorder' => 19, 'SponsorUserId' => 20, 'Date' => 21, 'CreatedAt' => 22, 'UpdatedAt' => 23, 'DeletedAt' => 24, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'siteId' => 1, 'userId' => 2, 'articleId' => 3, 'stockId' => 4, 'listId' => 5, 'bookId' => 6, 'peopleId' => 7, 'jobId' => 8, 'rayonId' => 9, 'tagId' => 10, 'eventId' => 11, 'postId' => 12, 'collectionId' => 13, 'publisherId' => 14, 'supplierId' => 15, 'mediaId' => 16, 'bundleId' => 17, 'hide' => 18, 'doNotReorder' => 19, 'sponsorUserId' => 20, 'date' => 21, 'createdAt' => 22, 'updatedAt' => 23, 'deletedAt' => 24, ),
        self::TYPE_COLNAME       => array(LinkTableMap::COL_LINK_ID => 0, LinkTableMap::COL_SITE_ID => 1, LinkTableMap::COL_USER_ID => 2, LinkTableMap::COL_ARTICLE_ID => 3, LinkTableMap::COL_STOCK_ID => 4, LinkTableMap::COL_LIST_ID => 5, LinkTableMap::COL_BOOK_ID => 6, LinkTableMap::COL_PEOPLE_ID => 7, LinkTableMap::COL_JOB_ID => 8, LinkTableMap::COL_RAYON_ID => 9, LinkTableMap::COL_TAG_ID => 10, LinkTableMap::COL_EVENT_ID => 11, LinkTableMap::COL_POST_ID => 12, LinkTableMap::COL_COLLECTION_ID => 13, LinkTableMap::COL_PUBLISHER_ID => 14, LinkTableMap::COL_SUPPLIER_ID => 15, LinkTableMap::COL_MEDIA_ID => 16, LinkTableMap::COL_BUNDLE_ID => 17, LinkTableMap::COL_LINK_HIDE => 18, LinkTableMap::COL_LINK_DO_NOT_REORDER => 19, LinkTableMap::COL_LINK_SPONSOR_USER_ID => 20, LinkTableMap::COL_LINK_DATE => 21, LinkTableMap::COL_LINK_CREATED => 22, LinkTableMap::COL_LINK_UPDATED => 23, LinkTableMap::COL_LINK_DELETED => 24, ),
        self::TYPE_FIELDNAME     => array('link_id' => 0, 'site_id' => 1, 'user_id' => 2, 'article_id' => 3, 'stock_id' => 4, 'list_id' => 5, 'book_id' => 6, 'people_id' => 7, 'job_id' => 8, 'rayon_id' => 9, 'tag_id' => 10, 'event_id' => 11, 'post_id' => 12, 'collection_id' => 13, 'publisher_id' => 14, 'supplier_id' => 15, 'media_id' => 16, 'bundle_id' => 17, 'link_hide' => 18, 'link_do_not_reorder' => 19, 'link_sponsor_user_id' => 20, 'link_date' => 21, 'link_created' => 22, 'link_updated' => 23, 'link_deleted' => 24, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, )
    );

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var string[]
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'LINK_ID',
        'Link.Id' => 'LINK_ID',
        'id' => 'LINK_ID',
        'link.id' => 'LINK_ID',
        'LinkTableMap::COL_LINK_ID' => 'LINK_ID',
        'COL_LINK_ID' => 'LINK_ID',
        'link_id' => 'LINK_ID',
        'links.link_id' => 'LINK_ID',
        'SiteId' => 'SITE_ID',
        'Link.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'link.siteId' => 'SITE_ID',
        'LinkTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'links.site_id' => 'SITE_ID',
        'UserId' => 'USER_ID',
        'Link.UserId' => 'USER_ID',
        'userId' => 'USER_ID',
        'link.userId' => 'USER_ID',
        'LinkTableMap::COL_USER_ID' => 'USER_ID',
        'COL_USER_ID' => 'USER_ID',
        'user_id' => 'USER_ID',
        'links.user_id' => 'USER_ID',
        'ArticleId' => 'ARTICLE_ID',
        'Link.ArticleId' => 'ARTICLE_ID',
        'articleId' => 'ARTICLE_ID',
        'link.articleId' => 'ARTICLE_ID',
        'LinkTableMap::COL_ARTICLE_ID' => 'ARTICLE_ID',
        'COL_ARTICLE_ID' => 'ARTICLE_ID',
        'article_id' => 'ARTICLE_ID',
        'links.article_id' => 'ARTICLE_ID',
        'StockId' => 'STOCK_ID',
        'Link.StockId' => 'STOCK_ID',
        'stockId' => 'STOCK_ID',
        'link.stockId' => 'STOCK_ID',
        'LinkTableMap::COL_STOCK_ID' => 'STOCK_ID',
        'COL_STOCK_ID' => 'STOCK_ID',
        'stock_id' => 'STOCK_ID',
        'links.stock_id' => 'STOCK_ID',
        'ListId' => 'LIST_ID',
        'Link.ListId' => 'LIST_ID',
        'listId' => 'LIST_ID',
        'link.listId' => 'LIST_ID',
        'LinkTableMap::COL_LIST_ID' => 'LIST_ID',
        'COL_LIST_ID' => 'LIST_ID',
        'list_id' => 'LIST_ID',
        'links.list_id' => 'LIST_ID',
        'BookId' => 'BOOK_ID',
        'Link.BookId' => 'BOOK_ID',
        'bookId' => 'BOOK_ID',
        'link.bookId' => 'BOOK_ID',
        'LinkTableMap::COL_BOOK_ID' => 'BOOK_ID',
        'COL_BOOK_ID' => 'BOOK_ID',
        'book_id' => 'BOOK_ID',
        'links.book_id' => 'BOOK_ID',
        'PeopleId' => 'PEOPLE_ID',
        'Link.PeopleId' => 'PEOPLE_ID',
        'peopleId' => 'PEOPLE_ID',
        'link.peopleId' => 'PEOPLE_ID',
        'LinkTableMap::COL_PEOPLE_ID' => 'PEOPLE_ID',
        'COL_PEOPLE_ID' => 'PEOPLE_ID',
        'people_id' => 'PEOPLE_ID',
        'links.people_id' => 'PEOPLE_ID',
        'JobId' => 'JOB_ID',
        'Link.JobId' => 'JOB_ID',
        'jobId' => 'JOB_ID',
        'link.jobId' => 'JOB_ID',
        'LinkTableMap::COL_JOB_ID' => 'JOB_ID',
        'COL_JOB_ID' => 'JOB_ID',
        'job_id' => 'JOB_ID',
        'links.job_id' => 'JOB_ID',
        'RayonId' => 'RAYON_ID',
        'Link.RayonId' => 'RAYON_ID',
        'rayonId' => 'RAYON_ID',
        'link.rayonId' => 'RAYON_ID',
        'LinkTableMap::COL_RAYON_ID' => 'RAYON_ID',
        'COL_RAYON_ID' => 'RAYON_ID',
        'rayon_id' => 'RAYON_ID',
        'links.rayon_id' => 'RAYON_ID',
        'TagId' => 'TAG_ID',
        'Link.TagId' => 'TAG_ID',
        'tagId' => 'TAG_ID',
        'link.tagId' => 'TAG_ID',
        'LinkTableMap::COL_TAG_ID' => 'TAG_ID',
        'COL_TAG_ID' => 'TAG_ID',
        'tag_id' => 'TAG_ID',
        'links.tag_id' => 'TAG_ID',
        'EventId' => 'EVENT_ID',
        'Link.EventId' => 'EVENT_ID',
        'eventId' => 'EVENT_ID',
        'link.eventId' => 'EVENT_ID',
        'LinkTableMap::COL_EVENT_ID' => 'EVENT_ID',
        'COL_EVENT_ID' => 'EVENT_ID',
        'event_id' => 'EVENT_ID',
        'links.event_id' => 'EVENT_ID',
        'PostId' => 'POST_ID',
        'Link.PostId' => 'POST_ID',
        'postId' => 'POST_ID',
        'link.postId' => 'POST_ID',
        'LinkTableMap::COL_POST_ID' => 'POST_ID',
        'COL_POST_ID' => 'POST_ID',
        'post_id' => 'POST_ID',
        'links.post_id' => 'POST_ID',
        'CollectionId' => 'COLLECTION_ID',
        'Link.CollectionId' => 'COLLECTION_ID',
        'collectionId' => 'COLLECTION_ID',
        'link.collectionId' => 'COLLECTION_ID',
        'LinkTableMap::COL_COLLECTION_ID' => 'COLLECTION_ID',
        'COL_COLLECTION_ID' => 'COLLECTION_ID',
        'collection_id' => 'COLLECTION_ID',
        'links.collection_id' => 'COLLECTION_ID',
        'PublisherId' => 'PUBLISHER_ID',
        'Link.PublisherId' => 'PUBLISHER_ID',
        'publisherId' => 'PUBLISHER_ID',
        'link.publisherId' => 'PUBLISHER_ID',
        'LinkTableMap::COL_PUBLISHER_ID' => 'PUBLISHER_ID',
        'COL_PUBLISHER_ID' => 'PUBLISHER_ID',
        'publisher_id' => 'PUBLISHER_ID',
        'links.publisher_id' => 'PUBLISHER_ID',
        'SupplierId' => 'SUPPLIER_ID',
        'Link.SupplierId' => 'SUPPLIER_ID',
        'supplierId' => 'SUPPLIER_ID',
        'link.supplierId' => 'SUPPLIER_ID',
        'LinkTableMap::COL_SUPPLIER_ID' => 'SUPPLIER_ID',
        'COL_SUPPLIER_ID' => 'SUPPLIER_ID',
        'supplier_id' => 'SUPPLIER_ID',
        'links.supplier_id' => 'SUPPLIER_ID',
        'MediaId' => 'MEDIA_ID',
        'Link.MediaId' => 'MEDIA_ID',
        'mediaId' => 'MEDIA_ID',
        'link.mediaId' => 'MEDIA_ID',
        'LinkTableMap::COL_MEDIA_ID' => 'MEDIA_ID',
        'COL_MEDIA_ID' => 'MEDIA_ID',
        'media_id' => 'MEDIA_ID',
        'links.media_id' => 'MEDIA_ID',
        'BundleId' => 'BUNDLE_ID',
        'Link.BundleId' => 'BUNDLE_ID',
        'bundleId' => 'BUNDLE_ID',
        'link.bundleId' => 'BUNDLE_ID',
        'LinkTableMap::COL_BUNDLE_ID' => 'BUNDLE_ID',
        'COL_BUNDLE_ID' => 'BUNDLE_ID',
        'bundle_id' => 'BUNDLE_ID',
        'links.bundle_id' => 'BUNDLE_ID',
        'Hide' => 'LINK_HIDE',
        'Link.Hide' => 'LINK_HIDE',
        'hide' => 'LINK_HIDE',
        'link.hide' => 'LINK_HIDE',
        'LinkTableMap::COL_LINK_HIDE' => 'LINK_HIDE',
        'COL_LINK_HIDE' => 'LINK_HIDE',
        'link_hide' => 'LINK_HIDE',
        'links.link_hide' => 'LINK_HIDE',
        'DoNotReorder' => 'LINK_DO_NOT_REORDER',
        'Link.DoNotReorder' => 'LINK_DO_NOT_REORDER',
        'doNotReorder' => 'LINK_DO_NOT_REORDER',
        'link.doNotReorder' => 'LINK_DO_NOT_REORDER',
        'LinkTableMap::COL_LINK_DO_NOT_REORDER' => 'LINK_DO_NOT_REORDER',
        'COL_LINK_DO_NOT_REORDER' => 'LINK_DO_NOT_REORDER',
        'link_do_not_reorder' => 'LINK_DO_NOT_REORDER',
        'links.link_do_not_reorder' => 'LINK_DO_NOT_REORDER',
        'SponsorUserId' => 'LINK_SPONSOR_USER_ID',
        'Link.SponsorUserId' => 'LINK_SPONSOR_USER_ID',
        'sponsorUserId' => 'LINK_SPONSOR_USER_ID',
        'link.sponsorUserId' => 'LINK_SPONSOR_USER_ID',
        'LinkTableMap::COL_LINK_SPONSOR_USER_ID' => 'LINK_SPONSOR_USER_ID',
        'COL_LINK_SPONSOR_USER_ID' => 'LINK_SPONSOR_USER_ID',
        'link_sponsor_user_id' => 'LINK_SPONSOR_USER_ID',
        'links.link_sponsor_user_id' => 'LINK_SPONSOR_USER_ID',
        'Date' => 'LINK_DATE',
        'Link.Date' => 'LINK_DATE',
        'date' => 'LINK_DATE',
        'link.date' => 'LINK_DATE',
        'LinkTableMap::COL_LINK_DATE' => 'LINK_DATE',
        'COL_LINK_DATE' => 'LINK_DATE',
        'link_date' => 'LINK_DATE',
        'links.link_date' => 'LINK_DATE',
        'CreatedAt' => 'LINK_CREATED',
        'Link.CreatedAt' => 'LINK_CREATED',
        'createdAt' => 'LINK_CREATED',
        'link.createdAt' => 'LINK_CREATED',
        'LinkTableMap::COL_LINK_CREATED' => 'LINK_CREATED',
        'COL_LINK_CREATED' => 'LINK_CREATED',
        'link_created' => 'LINK_CREATED',
        'links.link_created' => 'LINK_CREATED',
        'UpdatedAt' => 'LINK_UPDATED',
        'Link.UpdatedAt' => 'LINK_UPDATED',
        'updatedAt' => 'LINK_UPDATED',
        'link.updatedAt' => 'LINK_UPDATED',
        'LinkTableMap::COL_LINK_UPDATED' => 'LINK_UPDATED',
        'COL_LINK_UPDATED' => 'LINK_UPDATED',
        'link_updated' => 'LINK_UPDATED',
        'links.link_updated' => 'LINK_UPDATED',
        'DeletedAt' => 'LINK_DELETED',
        'Link.DeletedAt' => 'LINK_DELETED',
        'deletedAt' => 'LINK_DELETED',
        'link.deletedAt' => 'LINK_DELETED',
        'LinkTableMap::COL_LINK_DELETED' => 'LINK_DELETED',
        'COL_LINK_DELETED' => 'LINK_DELETED',
        'link_deleted' => 'LINK_DELETED',
        'links.link_deleted' => 'LINK_DELETED',
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
        $this->setName('links');
        $this->setPhpName('Link');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Link');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('link_id', 'Id', 'INTEGER', true, 10, null);
        $this->addColumn('site_id', 'SiteId', 'INTEGER', false, 10, null);
        $this->addColumn('user_id', 'UserId', 'INTEGER', false, 10, null);
        $this->addColumn('article_id', 'ArticleId', 'INTEGER', false, 10, null);
        $this->addColumn('stock_id', 'StockId', 'INTEGER', false, 10, null);
        $this->addColumn('list_id', 'ListId', 'INTEGER', false, 10, null);
        $this->addColumn('book_id', 'BookId', 'INTEGER', false, 10, null);
        $this->addColumn('people_id', 'PeopleId', 'INTEGER', false, 10, null);
        $this->addColumn('job_id', 'JobId', 'INTEGER', false, 10, null);
        $this->addColumn('rayon_id', 'RayonId', 'INTEGER', false, 10, null);
        $this->addColumn('tag_id', 'TagId', 'INTEGER', false, 10, null);
        $this->addColumn('event_id', 'EventId', 'INTEGER', false, null, null);
        $this->addColumn('post_id', 'PostId', 'INTEGER', false, null, null);
        $this->addColumn('collection_id', 'CollectionId', 'INTEGER', false, 10, null);
        $this->addColumn('publisher_id', 'PublisherId', 'INTEGER', false, 10, null);
        $this->addColumn('supplier_id', 'SupplierId', 'INTEGER', false, 10, null);
        $this->addColumn('media_id', 'MediaId', 'INTEGER', false, 10, null);
        $this->addColumn('bundle_id', 'BundleId', 'INTEGER', false, 10, null);
        $this->addColumn('link_hide', 'Hide', 'BOOLEAN', false, 1, null);
        $this->addColumn('link_do_not_reorder', 'DoNotReorder', 'BOOLEAN', false, 1, null);
        $this->addColumn('link_sponsor_user_id', 'SponsorUserId', 'INTEGER', false, 10, null);
        $this->addColumn('link_date', 'Date', 'TIMESTAMP', false, null, null);
        $this->addColumn('link_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('link_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('link_deleted', 'DeletedAt', 'TIMESTAMP', false, null, null);
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
            'timestampable' => ['create_column' => 'link_created', 'update_column' => 'link_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? LinkTableMap::CLASS_DEFAULT : LinkTableMap::OM_CLASS;
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
     * @return array           (Link object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = LinkTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = LinkTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + LinkTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = LinkTableMap::OM_CLASS;
            /** @var Link $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            LinkTableMap::addInstanceToPool($obj, $key);
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
            $key = LinkTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = LinkTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Link $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                LinkTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(LinkTableMap::COL_LINK_ID);
            $criteria->addSelectColumn(LinkTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(LinkTableMap::COL_USER_ID);
            $criteria->addSelectColumn(LinkTableMap::COL_ARTICLE_ID);
            $criteria->addSelectColumn(LinkTableMap::COL_STOCK_ID);
            $criteria->addSelectColumn(LinkTableMap::COL_LIST_ID);
            $criteria->addSelectColumn(LinkTableMap::COL_BOOK_ID);
            $criteria->addSelectColumn(LinkTableMap::COL_PEOPLE_ID);
            $criteria->addSelectColumn(LinkTableMap::COL_JOB_ID);
            $criteria->addSelectColumn(LinkTableMap::COL_RAYON_ID);
            $criteria->addSelectColumn(LinkTableMap::COL_TAG_ID);
            $criteria->addSelectColumn(LinkTableMap::COL_EVENT_ID);
            $criteria->addSelectColumn(LinkTableMap::COL_POST_ID);
            $criteria->addSelectColumn(LinkTableMap::COL_COLLECTION_ID);
            $criteria->addSelectColumn(LinkTableMap::COL_PUBLISHER_ID);
            $criteria->addSelectColumn(LinkTableMap::COL_SUPPLIER_ID);
            $criteria->addSelectColumn(LinkTableMap::COL_MEDIA_ID);
            $criteria->addSelectColumn(LinkTableMap::COL_BUNDLE_ID);
            $criteria->addSelectColumn(LinkTableMap::COL_LINK_HIDE);
            $criteria->addSelectColumn(LinkTableMap::COL_LINK_DO_NOT_REORDER);
            $criteria->addSelectColumn(LinkTableMap::COL_LINK_SPONSOR_USER_ID);
            $criteria->addSelectColumn(LinkTableMap::COL_LINK_DATE);
            $criteria->addSelectColumn(LinkTableMap::COL_LINK_CREATED);
            $criteria->addSelectColumn(LinkTableMap::COL_LINK_UPDATED);
            $criteria->addSelectColumn(LinkTableMap::COL_LINK_DELETED);
        } else {
            $criteria->addSelectColumn($alias . '.link_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.article_id');
            $criteria->addSelectColumn($alias . '.stock_id');
            $criteria->addSelectColumn($alias . '.list_id');
            $criteria->addSelectColumn($alias . '.book_id');
            $criteria->addSelectColumn($alias . '.people_id');
            $criteria->addSelectColumn($alias . '.job_id');
            $criteria->addSelectColumn($alias . '.rayon_id');
            $criteria->addSelectColumn($alias . '.tag_id');
            $criteria->addSelectColumn($alias . '.event_id');
            $criteria->addSelectColumn($alias . '.post_id');
            $criteria->addSelectColumn($alias . '.collection_id');
            $criteria->addSelectColumn($alias . '.publisher_id');
            $criteria->addSelectColumn($alias . '.supplier_id');
            $criteria->addSelectColumn($alias . '.media_id');
            $criteria->addSelectColumn($alias . '.bundle_id');
            $criteria->addSelectColumn($alias . '.link_hide');
            $criteria->addSelectColumn($alias . '.link_do_not_reorder');
            $criteria->addSelectColumn($alias . '.link_sponsor_user_id');
            $criteria->addSelectColumn($alias . '.link_date');
            $criteria->addSelectColumn($alias . '.link_created');
            $criteria->addSelectColumn($alias . '.link_updated');
            $criteria->addSelectColumn($alias . '.link_deleted');
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
            $criteria->removeSelectColumn(LinkTableMap::COL_LINK_ID);
            $criteria->removeSelectColumn(LinkTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(LinkTableMap::COL_USER_ID);
            $criteria->removeSelectColumn(LinkTableMap::COL_ARTICLE_ID);
            $criteria->removeSelectColumn(LinkTableMap::COL_STOCK_ID);
            $criteria->removeSelectColumn(LinkTableMap::COL_LIST_ID);
            $criteria->removeSelectColumn(LinkTableMap::COL_BOOK_ID);
            $criteria->removeSelectColumn(LinkTableMap::COL_PEOPLE_ID);
            $criteria->removeSelectColumn(LinkTableMap::COL_JOB_ID);
            $criteria->removeSelectColumn(LinkTableMap::COL_RAYON_ID);
            $criteria->removeSelectColumn(LinkTableMap::COL_TAG_ID);
            $criteria->removeSelectColumn(LinkTableMap::COL_EVENT_ID);
            $criteria->removeSelectColumn(LinkTableMap::COL_POST_ID);
            $criteria->removeSelectColumn(LinkTableMap::COL_COLLECTION_ID);
            $criteria->removeSelectColumn(LinkTableMap::COL_PUBLISHER_ID);
            $criteria->removeSelectColumn(LinkTableMap::COL_SUPPLIER_ID);
            $criteria->removeSelectColumn(LinkTableMap::COL_MEDIA_ID);
            $criteria->removeSelectColumn(LinkTableMap::COL_BUNDLE_ID);
            $criteria->removeSelectColumn(LinkTableMap::COL_LINK_HIDE);
            $criteria->removeSelectColumn(LinkTableMap::COL_LINK_DO_NOT_REORDER);
            $criteria->removeSelectColumn(LinkTableMap::COL_LINK_SPONSOR_USER_ID);
            $criteria->removeSelectColumn(LinkTableMap::COL_LINK_DATE);
            $criteria->removeSelectColumn(LinkTableMap::COL_LINK_CREATED);
            $criteria->removeSelectColumn(LinkTableMap::COL_LINK_UPDATED);
            $criteria->removeSelectColumn(LinkTableMap::COL_LINK_DELETED);
        } else {
            $criteria->removeSelectColumn($alias . '.link_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.user_id');
            $criteria->removeSelectColumn($alias . '.article_id');
            $criteria->removeSelectColumn($alias . '.stock_id');
            $criteria->removeSelectColumn($alias . '.list_id');
            $criteria->removeSelectColumn($alias . '.book_id');
            $criteria->removeSelectColumn($alias . '.people_id');
            $criteria->removeSelectColumn($alias . '.job_id');
            $criteria->removeSelectColumn($alias . '.rayon_id');
            $criteria->removeSelectColumn($alias . '.tag_id');
            $criteria->removeSelectColumn($alias . '.event_id');
            $criteria->removeSelectColumn($alias . '.post_id');
            $criteria->removeSelectColumn($alias . '.collection_id');
            $criteria->removeSelectColumn($alias . '.publisher_id');
            $criteria->removeSelectColumn($alias . '.supplier_id');
            $criteria->removeSelectColumn($alias . '.media_id');
            $criteria->removeSelectColumn($alias . '.bundle_id');
            $criteria->removeSelectColumn($alias . '.link_hide');
            $criteria->removeSelectColumn($alias . '.link_do_not_reorder');
            $criteria->removeSelectColumn($alias . '.link_sponsor_user_id');
            $criteria->removeSelectColumn($alias . '.link_date');
            $criteria->removeSelectColumn($alias . '.link_created');
            $criteria->removeSelectColumn($alias . '.link_updated');
            $criteria->removeSelectColumn($alias . '.link_deleted');
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
        return Propel::getServiceContainer()->getDatabaseMap(LinkTableMap::DATABASE_NAME)->getTable(LinkTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Link or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Link object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(LinkTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Link) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(LinkTableMap::DATABASE_NAME);
            $criteria->add(LinkTableMap::COL_LINK_ID, (array) $values, Criteria::IN);
        }

        $query = LinkQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            LinkTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                LinkTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the links table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return LinkQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Link or Criteria object.
     *
     * @param mixed               $criteria Criteria or Link object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LinkTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Link object
        }

        if ($criteria->containsKey(LinkTableMap::COL_LINK_ID) && $criteria->keyContainsValue(LinkTableMap::COL_LINK_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.LinkTableMap::COL_LINK_ID.')');
        }


        // Set the correct dbName
        $query = LinkQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // LinkTableMap
