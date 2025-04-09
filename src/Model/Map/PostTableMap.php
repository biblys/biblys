<?php

namespace Model\Map;

use Model\Post;
use Model\PostQuery;
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
 * This class defines the structure of the 'posts' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class PostTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.PostTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'posts';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Post';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Post';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Post';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 24;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 24;

    /**
     * the column name for the post_id field
     */
    public const COL_POST_ID = 'posts.post_id';

    /**
     * the column name for the axys_account_id field
     */
    public const COL_AXYS_ACCOUNT_ID = 'posts.axys_account_id';

    /**
     * the column name for the user_id field
     */
    public const COL_USER_ID = 'posts.user_id';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'posts.site_id';

    /**
     * the column name for the publisher_id field
     */
    public const COL_PUBLISHER_ID = 'posts.publisher_id';

    /**
     * the column name for the category_id field
     */
    public const COL_CATEGORY_ID = 'posts.category_id';

    /**
     * the column name for the post_url field
     */
    public const COL_POST_URL = 'posts.post_url';

    /**
     * the column name for the post_title field
     */
    public const COL_POST_TITLE = 'posts.post_title';

    /**
     * the column name for the post_content field
     */
    public const COL_POST_CONTENT = 'posts.post_content';

    /**
     * the column name for the post_illustration_version field
     */
    public const COL_POST_ILLUSTRATION_VERSION = 'posts.post_illustration_version';

    /**
     * the column name for the post_illustration_legend field
     */
    public const COL_POST_ILLUSTRATION_LEGEND = 'posts.post_illustration_legend';

    /**
     * the column name for the post_selected field
     */
    public const COL_POST_SELECTED = 'posts.post_selected';

    /**
     * the column name for the post_link field
     */
    public const COL_POST_LINK = 'posts.post_link';

    /**
     * the column name for the post_status field
     */
    public const COL_POST_STATUS = 'posts.post_status';

    /**
     * the column name for the post_keywords field
     */
    public const COL_POST_KEYWORDS = 'posts.post_keywords';

    /**
     * the column name for the post_links field
     */
    public const COL_POST_LINKS = 'posts.post_links';

    /**
     * the column name for the post_keywords_generated field
     */
    public const COL_POST_KEYWORDS_GENERATED = 'posts.post_keywords_generated';

    /**
     * the column name for the post_fb_id field
     */
    public const COL_POST_FB_ID = 'posts.post_fb_id';

    /**
     * the column name for the post_date field
     */
    public const COL_POST_DATE = 'posts.post_date';

    /**
     * the column name for the post_hits field
     */
    public const COL_POST_HITS = 'posts.post_hits';

    /**
     * the column name for the post_insert field
     */
    public const COL_POST_INSERT = 'posts.post_insert';

    /**
     * the column name for the post_update field
     */
    public const COL_POST_UPDATE = 'posts.post_update';

    /**
     * the column name for the post_created field
     */
    public const COL_POST_CREATED = 'posts.post_created';

    /**
     * the column name for the post_updated field
     */
    public const COL_POST_UPDATED = 'posts.post_updated';

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
        self::TYPE_PHPNAME       => ['Id', 'AxysAccountId', 'UserId', 'SiteId', 'PublisherId', 'CategoryId', 'Url', 'Title', 'Content', 'IllustrationVersion', 'IllustrationLegend', 'Selected', 'Link', 'Status', 'Keywords', 'Links', 'KeywordsGenerated', 'FbId', 'Date', 'Hits', 'Insert', 'Update', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'axysAccountId', 'userId', 'siteId', 'publisherId', 'categoryId', 'url', 'title', 'content', 'illustrationVersion', 'illustrationLegend', 'selected', 'link', 'status', 'keywords', 'links', 'keywordsGenerated', 'fbId', 'date', 'hits', 'insert', 'update', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [PostTableMap::COL_POST_ID, PostTableMap::COL_AXYS_ACCOUNT_ID, PostTableMap::COL_USER_ID, PostTableMap::COL_SITE_ID, PostTableMap::COL_PUBLISHER_ID, PostTableMap::COL_CATEGORY_ID, PostTableMap::COL_POST_URL, PostTableMap::COL_POST_TITLE, PostTableMap::COL_POST_CONTENT, PostTableMap::COL_POST_ILLUSTRATION_VERSION, PostTableMap::COL_POST_ILLUSTRATION_LEGEND, PostTableMap::COL_POST_SELECTED, PostTableMap::COL_POST_LINK, PostTableMap::COL_POST_STATUS, PostTableMap::COL_POST_KEYWORDS, PostTableMap::COL_POST_LINKS, PostTableMap::COL_POST_KEYWORDS_GENERATED, PostTableMap::COL_POST_FB_ID, PostTableMap::COL_POST_DATE, PostTableMap::COL_POST_HITS, PostTableMap::COL_POST_INSERT, PostTableMap::COL_POST_UPDATE, PostTableMap::COL_POST_CREATED, PostTableMap::COL_POST_UPDATED, ],
        self::TYPE_FIELDNAME     => ['post_id', 'axys_account_id', 'user_id', 'site_id', 'publisher_id', 'category_id', 'post_url', 'post_title', 'post_content', 'post_illustration_version', 'post_illustration_legend', 'post_selected', 'post_link', 'post_status', 'post_keywords', 'post_links', 'post_keywords_generated', 'post_fb_id', 'post_date', 'post_hits', 'post_insert', 'post_update', 'post_created', 'post_updated', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'AxysAccountId' => 1, 'UserId' => 2, 'SiteId' => 3, 'PublisherId' => 4, 'CategoryId' => 5, 'Url' => 6, 'Title' => 7, 'Content' => 8, 'IllustrationVersion' => 9, 'IllustrationLegend' => 10, 'Selected' => 11, 'Link' => 12, 'Status' => 13, 'Keywords' => 14, 'Links' => 15, 'KeywordsGenerated' => 16, 'FbId' => 17, 'Date' => 18, 'Hits' => 19, 'Insert' => 20, 'Update' => 21, 'CreatedAt' => 22, 'UpdatedAt' => 23, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'axysAccountId' => 1, 'userId' => 2, 'siteId' => 3, 'publisherId' => 4, 'categoryId' => 5, 'url' => 6, 'title' => 7, 'content' => 8, 'illustrationVersion' => 9, 'illustrationLegend' => 10, 'selected' => 11, 'link' => 12, 'status' => 13, 'keywords' => 14, 'links' => 15, 'keywordsGenerated' => 16, 'fbId' => 17, 'date' => 18, 'hits' => 19, 'insert' => 20, 'update' => 21, 'createdAt' => 22, 'updatedAt' => 23, ],
        self::TYPE_COLNAME       => [PostTableMap::COL_POST_ID => 0, PostTableMap::COL_AXYS_ACCOUNT_ID => 1, PostTableMap::COL_USER_ID => 2, PostTableMap::COL_SITE_ID => 3, PostTableMap::COL_PUBLISHER_ID => 4, PostTableMap::COL_CATEGORY_ID => 5, PostTableMap::COL_POST_URL => 6, PostTableMap::COL_POST_TITLE => 7, PostTableMap::COL_POST_CONTENT => 8, PostTableMap::COL_POST_ILLUSTRATION_VERSION => 9, PostTableMap::COL_POST_ILLUSTRATION_LEGEND => 10, PostTableMap::COL_POST_SELECTED => 11, PostTableMap::COL_POST_LINK => 12, PostTableMap::COL_POST_STATUS => 13, PostTableMap::COL_POST_KEYWORDS => 14, PostTableMap::COL_POST_LINKS => 15, PostTableMap::COL_POST_KEYWORDS_GENERATED => 16, PostTableMap::COL_POST_FB_ID => 17, PostTableMap::COL_POST_DATE => 18, PostTableMap::COL_POST_HITS => 19, PostTableMap::COL_POST_INSERT => 20, PostTableMap::COL_POST_UPDATE => 21, PostTableMap::COL_POST_CREATED => 22, PostTableMap::COL_POST_UPDATED => 23, ],
        self::TYPE_FIELDNAME     => ['post_id' => 0, 'axys_account_id' => 1, 'user_id' => 2, 'site_id' => 3, 'publisher_id' => 4, 'category_id' => 5, 'post_url' => 6, 'post_title' => 7, 'post_content' => 8, 'post_illustration_version' => 9, 'post_illustration_legend' => 10, 'post_selected' => 11, 'post_link' => 12, 'post_status' => 13, 'post_keywords' => 14, 'post_links' => 15, 'post_keywords_generated' => 16, 'post_fb_id' => 17, 'post_date' => 18, 'post_hits' => 19, 'post_insert' => 20, 'post_update' => 21, 'post_created' => 22, 'post_updated' => 23, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'POST_ID',
        'Post.Id' => 'POST_ID',
        'id' => 'POST_ID',
        'post.id' => 'POST_ID',
        'PostTableMap::COL_POST_ID' => 'POST_ID',
        'COL_POST_ID' => 'POST_ID',
        'post_id' => 'POST_ID',
        'posts.post_id' => 'POST_ID',
        'AxysAccountId' => 'AXYS_ACCOUNT_ID',
        'Post.AxysAccountId' => 'AXYS_ACCOUNT_ID',
        'axysAccountId' => 'AXYS_ACCOUNT_ID',
        'post.axysAccountId' => 'AXYS_ACCOUNT_ID',
        'PostTableMap::COL_AXYS_ACCOUNT_ID' => 'AXYS_ACCOUNT_ID',
        'COL_AXYS_ACCOUNT_ID' => 'AXYS_ACCOUNT_ID',
        'axys_account_id' => 'AXYS_ACCOUNT_ID',
        'posts.axys_account_id' => 'AXYS_ACCOUNT_ID',
        'UserId' => 'USER_ID',
        'Post.UserId' => 'USER_ID',
        'userId' => 'USER_ID',
        'post.userId' => 'USER_ID',
        'PostTableMap::COL_USER_ID' => 'USER_ID',
        'COL_USER_ID' => 'USER_ID',
        'user_id' => 'USER_ID',
        'posts.user_id' => 'USER_ID',
        'SiteId' => 'SITE_ID',
        'Post.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'post.siteId' => 'SITE_ID',
        'PostTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'posts.site_id' => 'SITE_ID',
        'PublisherId' => 'PUBLISHER_ID',
        'Post.PublisherId' => 'PUBLISHER_ID',
        'publisherId' => 'PUBLISHER_ID',
        'post.publisherId' => 'PUBLISHER_ID',
        'PostTableMap::COL_PUBLISHER_ID' => 'PUBLISHER_ID',
        'COL_PUBLISHER_ID' => 'PUBLISHER_ID',
        'publisher_id' => 'PUBLISHER_ID',
        'posts.publisher_id' => 'PUBLISHER_ID',
        'CategoryId' => 'CATEGORY_ID',
        'Post.CategoryId' => 'CATEGORY_ID',
        'categoryId' => 'CATEGORY_ID',
        'post.categoryId' => 'CATEGORY_ID',
        'PostTableMap::COL_CATEGORY_ID' => 'CATEGORY_ID',
        'COL_CATEGORY_ID' => 'CATEGORY_ID',
        'category_id' => 'CATEGORY_ID',
        'posts.category_id' => 'CATEGORY_ID',
        'Url' => 'POST_URL',
        'Post.Url' => 'POST_URL',
        'url' => 'POST_URL',
        'post.url' => 'POST_URL',
        'PostTableMap::COL_POST_URL' => 'POST_URL',
        'COL_POST_URL' => 'POST_URL',
        'post_url' => 'POST_URL',
        'posts.post_url' => 'POST_URL',
        'Title' => 'POST_TITLE',
        'Post.Title' => 'POST_TITLE',
        'title' => 'POST_TITLE',
        'post.title' => 'POST_TITLE',
        'PostTableMap::COL_POST_TITLE' => 'POST_TITLE',
        'COL_POST_TITLE' => 'POST_TITLE',
        'post_title' => 'POST_TITLE',
        'posts.post_title' => 'POST_TITLE',
        'Content' => 'POST_CONTENT',
        'Post.Content' => 'POST_CONTENT',
        'content' => 'POST_CONTENT',
        'post.content' => 'POST_CONTENT',
        'PostTableMap::COL_POST_CONTENT' => 'POST_CONTENT',
        'COL_POST_CONTENT' => 'POST_CONTENT',
        'post_content' => 'POST_CONTENT',
        'posts.post_content' => 'POST_CONTENT',
        'IllustrationVersion' => 'POST_ILLUSTRATION_VERSION',
        'Post.IllustrationVersion' => 'POST_ILLUSTRATION_VERSION',
        'illustrationVersion' => 'POST_ILLUSTRATION_VERSION',
        'post.illustrationVersion' => 'POST_ILLUSTRATION_VERSION',
        'PostTableMap::COL_POST_ILLUSTRATION_VERSION' => 'POST_ILLUSTRATION_VERSION',
        'COL_POST_ILLUSTRATION_VERSION' => 'POST_ILLUSTRATION_VERSION',
        'post_illustration_version' => 'POST_ILLUSTRATION_VERSION',
        'posts.post_illustration_version' => 'POST_ILLUSTRATION_VERSION',
        'IllustrationLegend' => 'POST_ILLUSTRATION_LEGEND',
        'Post.IllustrationLegend' => 'POST_ILLUSTRATION_LEGEND',
        'illustrationLegend' => 'POST_ILLUSTRATION_LEGEND',
        'post.illustrationLegend' => 'POST_ILLUSTRATION_LEGEND',
        'PostTableMap::COL_POST_ILLUSTRATION_LEGEND' => 'POST_ILLUSTRATION_LEGEND',
        'COL_POST_ILLUSTRATION_LEGEND' => 'POST_ILLUSTRATION_LEGEND',
        'post_illustration_legend' => 'POST_ILLUSTRATION_LEGEND',
        'posts.post_illustration_legend' => 'POST_ILLUSTRATION_LEGEND',
        'Selected' => 'POST_SELECTED',
        'Post.Selected' => 'POST_SELECTED',
        'selected' => 'POST_SELECTED',
        'post.selected' => 'POST_SELECTED',
        'PostTableMap::COL_POST_SELECTED' => 'POST_SELECTED',
        'COL_POST_SELECTED' => 'POST_SELECTED',
        'post_selected' => 'POST_SELECTED',
        'posts.post_selected' => 'POST_SELECTED',
        'Link' => 'POST_LINK',
        'Post.Link' => 'POST_LINK',
        'link' => 'POST_LINK',
        'post.link' => 'POST_LINK',
        'PostTableMap::COL_POST_LINK' => 'POST_LINK',
        'COL_POST_LINK' => 'POST_LINK',
        'post_link' => 'POST_LINK',
        'posts.post_link' => 'POST_LINK',
        'Status' => 'POST_STATUS',
        'Post.Status' => 'POST_STATUS',
        'status' => 'POST_STATUS',
        'post.status' => 'POST_STATUS',
        'PostTableMap::COL_POST_STATUS' => 'POST_STATUS',
        'COL_POST_STATUS' => 'POST_STATUS',
        'post_status' => 'POST_STATUS',
        'posts.post_status' => 'POST_STATUS',
        'Keywords' => 'POST_KEYWORDS',
        'Post.Keywords' => 'POST_KEYWORDS',
        'keywords' => 'POST_KEYWORDS',
        'post.keywords' => 'POST_KEYWORDS',
        'PostTableMap::COL_POST_KEYWORDS' => 'POST_KEYWORDS',
        'COL_POST_KEYWORDS' => 'POST_KEYWORDS',
        'post_keywords' => 'POST_KEYWORDS',
        'posts.post_keywords' => 'POST_KEYWORDS',
        'Links' => 'POST_LINKS',
        'Post.Links' => 'POST_LINKS',
        'links' => 'POST_LINKS',
        'post.links' => 'POST_LINKS',
        'PostTableMap::COL_POST_LINKS' => 'POST_LINKS',
        'COL_POST_LINKS' => 'POST_LINKS',
        'post_links' => 'POST_LINKS',
        'posts.post_links' => 'POST_LINKS',
        'KeywordsGenerated' => 'POST_KEYWORDS_GENERATED',
        'Post.KeywordsGenerated' => 'POST_KEYWORDS_GENERATED',
        'keywordsGenerated' => 'POST_KEYWORDS_GENERATED',
        'post.keywordsGenerated' => 'POST_KEYWORDS_GENERATED',
        'PostTableMap::COL_POST_KEYWORDS_GENERATED' => 'POST_KEYWORDS_GENERATED',
        'COL_POST_KEYWORDS_GENERATED' => 'POST_KEYWORDS_GENERATED',
        'post_keywords_generated' => 'POST_KEYWORDS_GENERATED',
        'posts.post_keywords_generated' => 'POST_KEYWORDS_GENERATED',
        'FbId' => 'POST_FB_ID',
        'Post.FbId' => 'POST_FB_ID',
        'fbId' => 'POST_FB_ID',
        'post.fbId' => 'POST_FB_ID',
        'PostTableMap::COL_POST_FB_ID' => 'POST_FB_ID',
        'COL_POST_FB_ID' => 'POST_FB_ID',
        'post_fb_id' => 'POST_FB_ID',
        'posts.post_fb_id' => 'POST_FB_ID',
        'Date' => 'POST_DATE',
        'Post.Date' => 'POST_DATE',
        'date' => 'POST_DATE',
        'post.date' => 'POST_DATE',
        'PostTableMap::COL_POST_DATE' => 'POST_DATE',
        'COL_POST_DATE' => 'POST_DATE',
        'post_date' => 'POST_DATE',
        'posts.post_date' => 'POST_DATE',
        'Hits' => 'POST_HITS',
        'Post.Hits' => 'POST_HITS',
        'hits' => 'POST_HITS',
        'post.hits' => 'POST_HITS',
        'PostTableMap::COL_POST_HITS' => 'POST_HITS',
        'COL_POST_HITS' => 'POST_HITS',
        'post_hits' => 'POST_HITS',
        'posts.post_hits' => 'POST_HITS',
        'Insert' => 'POST_INSERT',
        'Post.Insert' => 'POST_INSERT',
        'insert' => 'POST_INSERT',
        'post.insert' => 'POST_INSERT',
        'PostTableMap::COL_POST_INSERT' => 'POST_INSERT',
        'COL_POST_INSERT' => 'POST_INSERT',
        'post_insert' => 'POST_INSERT',
        'posts.post_insert' => 'POST_INSERT',
        'Update' => 'POST_UPDATE',
        'Post.Update' => 'POST_UPDATE',
        'update' => 'POST_UPDATE',
        'post.update' => 'POST_UPDATE',
        'PostTableMap::COL_POST_UPDATE' => 'POST_UPDATE',
        'COL_POST_UPDATE' => 'POST_UPDATE',
        'post_update' => 'POST_UPDATE',
        'posts.post_update' => 'POST_UPDATE',
        'CreatedAt' => 'POST_CREATED',
        'Post.CreatedAt' => 'POST_CREATED',
        'createdAt' => 'POST_CREATED',
        'post.createdAt' => 'POST_CREATED',
        'PostTableMap::COL_POST_CREATED' => 'POST_CREATED',
        'COL_POST_CREATED' => 'POST_CREATED',
        'post_created' => 'POST_CREATED',
        'posts.post_created' => 'POST_CREATED',
        'UpdatedAt' => 'POST_UPDATED',
        'Post.UpdatedAt' => 'POST_UPDATED',
        'updatedAt' => 'POST_UPDATED',
        'post.updatedAt' => 'POST_UPDATED',
        'PostTableMap::COL_POST_UPDATED' => 'POST_UPDATED',
        'COL_POST_UPDATED' => 'POST_UPDATED',
        'post_updated' => 'POST_UPDATED',
        'posts.post_updated' => 'POST_UPDATED',
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
        $this->setName('posts');
        $this->setPhpName('Post');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Post');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('post_id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('axys_account_id', 'AxysAccountId', 'INTEGER', false, null, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'users', 'id', false, null, null);
        $this->addForeignKey('site_id', 'SiteId', 'INTEGER', 'sites', 'site_id', false, null, null);
        $this->addColumn('publisher_id', 'PublisherId', 'INTEGER', false, null, null);
        $this->addForeignKey('category_id', 'CategoryId', 'INTEGER', 'categories', 'category_id', false, null, null);
        $this->addColumn('post_url', 'Url', 'LONGVARCHAR', false, null, null);
        $this->addColumn('post_title', 'Title', 'LONGVARCHAR', false, null, null);
        $this->addColumn('post_content', 'Content', 'LONGVARCHAR', false, null, null);
        $this->addColumn('post_illustration_version', 'IllustrationVersion', 'INTEGER', false, null, 0);
        $this->addColumn('post_illustration_legend', 'IllustrationLegend', 'VARCHAR', false, 64, null);
        $this->addColumn('post_selected', 'Selected', 'BOOLEAN', false, 1, null);
        $this->addColumn('post_link', 'Link', 'LONGVARCHAR', false, null, null);
        $this->addColumn('post_status', 'Status', 'BOOLEAN', false, 1, null);
        $this->addColumn('post_keywords', 'Keywords', 'VARCHAR', false, 512, null);
        $this->addColumn('post_links', 'Links', 'VARCHAR', false, 512, null);
        $this->addColumn('post_keywords_generated', 'KeywordsGenerated', 'TIMESTAMP', false, null, null);
        $this->addColumn('post_fb_id', 'FbId', 'BIGINT', false, null, null);
        $this->addColumn('post_date', 'Date', 'TIMESTAMP', false, null, null);
        $this->addColumn('post_hits', 'Hits', 'INTEGER', false, null, null);
        $this->addColumn('post_insert', 'Insert', 'TIMESTAMP', false, null, null);
        $this->addColumn('post_update', 'Update', 'TIMESTAMP', false, null, null);
        $this->addColumn('post_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('post_updated', 'UpdatedAt', 'DATE', false, null, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
        $this->addRelation('User', '\\Model\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Site', '\\Model\\Site', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':site_id',
    1 => ':site_id',
  ),
), null, null, null, false);
        $this->addRelation('BlogCategory', '\\Model\\BlogCategory', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':category_id',
    1 => ':category_id',
  ),
), null, null, null, false);
        $this->addRelation('Image', '\\Model\\Image', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':post_id',
    1 => ':post_id',
  ),
), null, null, 'Images', false);
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
            'timestampable' => ['create_column' => 'post_created', 'update_column' => 'post_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? PostTableMap::CLASS_DEFAULT : PostTableMap::OM_CLASS;
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
     * @return array (Post object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = PostTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = PostTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + PostTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PostTableMap::OM_CLASS;
            /** @var Post $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            PostTableMap::addInstanceToPool($obj, $key);
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
            $key = PostTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = PostTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Post $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PostTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(PostTableMap::COL_POST_ID);
            $criteria->addSelectColumn(PostTableMap::COL_AXYS_ACCOUNT_ID);
            $criteria->addSelectColumn(PostTableMap::COL_USER_ID);
            $criteria->addSelectColumn(PostTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(PostTableMap::COL_PUBLISHER_ID);
            $criteria->addSelectColumn(PostTableMap::COL_CATEGORY_ID);
            $criteria->addSelectColumn(PostTableMap::COL_POST_URL);
            $criteria->addSelectColumn(PostTableMap::COL_POST_TITLE);
            $criteria->addSelectColumn(PostTableMap::COL_POST_CONTENT);
            $criteria->addSelectColumn(PostTableMap::COL_POST_ILLUSTRATION_VERSION);
            $criteria->addSelectColumn(PostTableMap::COL_POST_ILLUSTRATION_LEGEND);
            $criteria->addSelectColumn(PostTableMap::COL_POST_SELECTED);
            $criteria->addSelectColumn(PostTableMap::COL_POST_LINK);
            $criteria->addSelectColumn(PostTableMap::COL_POST_STATUS);
            $criteria->addSelectColumn(PostTableMap::COL_POST_KEYWORDS);
            $criteria->addSelectColumn(PostTableMap::COL_POST_LINKS);
            $criteria->addSelectColumn(PostTableMap::COL_POST_KEYWORDS_GENERATED);
            $criteria->addSelectColumn(PostTableMap::COL_POST_FB_ID);
            $criteria->addSelectColumn(PostTableMap::COL_POST_DATE);
            $criteria->addSelectColumn(PostTableMap::COL_POST_HITS);
            $criteria->addSelectColumn(PostTableMap::COL_POST_INSERT);
            $criteria->addSelectColumn(PostTableMap::COL_POST_UPDATE);
            $criteria->addSelectColumn(PostTableMap::COL_POST_CREATED);
            $criteria->addSelectColumn(PostTableMap::COL_POST_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.post_id');
            $criteria->addSelectColumn($alias . '.axys_account_id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.publisher_id');
            $criteria->addSelectColumn($alias . '.category_id');
            $criteria->addSelectColumn($alias . '.post_url');
            $criteria->addSelectColumn($alias . '.post_title');
            $criteria->addSelectColumn($alias . '.post_content');
            $criteria->addSelectColumn($alias . '.post_illustration_version');
            $criteria->addSelectColumn($alias . '.post_illustration_legend');
            $criteria->addSelectColumn($alias . '.post_selected');
            $criteria->addSelectColumn($alias . '.post_link');
            $criteria->addSelectColumn($alias . '.post_status');
            $criteria->addSelectColumn($alias . '.post_keywords');
            $criteria->addSelectColumn($alias . '.post_links');
            $criteria->addSelectColumn($alias . '.post_keywords_generated');
            $criteria->addSelectColumn($alias . '.post_fb_id');
            $criteria->addSelectColumn($alias . '.post_date');
            $criteria->addSelectColumn($alias . '.post_hits');
            $criteria->addSelectColumn($alias . '.post_insert');
            $criteria->addSelectColumn($alias . '.post_update');
            $criteria->addSelectColumn($alias . '.post_created');
            $criteria->addSelectColumn($alias . '.post_updated');
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
            $criteria->removeSelectColumn(PostTableMap::COL_POST_ID);
            $criteria->removeSelectColumn(PostTableMap::COL_AXYS_ACCOUNT_ID);
            $criteria->removeSelectColumn(PostTableMap::COL_USER_ID);
            $criteria->removeSelectColumn(PostTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(PostTableMap::COL_PUBLISHER_ID);
            $criteria->removeSelectColumn(PostTableMap::COL_CATEGORY_ID);
            $criteria->removeSelectColumn(PostTableMap::COL_POST_URL);
            $criteria->removeSelectColumn(PostTableMap::COL_POST_TITLE);
            $criteria->removeSelectColumn(PostTableMap::COL_POST_CONTENT);
            $criteria->removeSelectColumn(PostTableMap::COL_POST_ILLUSTRATION_VERSION);
            $criteria->removeSelectColumn(PostTableMap::COL_POST_ILLUSTRATION_LEGEND);
            $criteria->removeSelectColumn(PostTableMap::COL_POST_SELECTED);
            $criteria->removeSelectColumn(PostTableMap::COL_POST_LINK);
            $criteria->removeSelectColumn(PostTableMap::COL_POST_STATUS);
            $criteria->removeSelectColumn(PostTableMap::COL_POST_KEYWORDS);
            $criteria->removeSelectColumn(PostTableMap::COL_POST_LINKS);
            $criteria->removeSelectColumn(PostTableMap::COL_POST_KEYWORDS_GENERATED);
            $criteria->removeSelectColumn(PostTableMap::COL_POST_FB_ID);
            $criteria->removeSelectColumn(PostTableMap::COL_POST_DATE);
            $criteria->removeSelectColumn(PostTableMap::COL_POST_HITS);
            $criteria->removeSelectColumn(PostTableMap::COL_POST_INSERT);
            $criteria->removeSelectColumn(PostTableMap::COL_POST_UPDATE);
            $criteria->removeSelectColumn(PostTableMap::COL_POST_CREATED);
            $criteria->removeSelectColumn(PostTableMap::COL_POST_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.post_id');
            $criteria->removeSelectColumn($alias . '.axys_account_id');
            $criteria->removeSelectColumn($alias . '.user_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.publisher_id');
            $criteria->removeSelectColumn($alias . '.category_id');
            $criteria->removeSelectColumn($alias . '.post_url');
            $criteria->removeSelectColumn($alias . '.post_title');
            $criteria->removeSelectColumn($alias . '.post_content');
            $criteria->removeSelectColumn($alias . '.post_illustration_version');
            $criteria->removeSelectColumn($alias . '.post_illustration_legend');
            $criteria->removeSelectColumn($alias . '.post_selected');
            $criteria->removeSelectColumn($alias . '.post_link');
            $criteria->removeSelectColumn($alias . '.post_status');
            $criteria->removeSelectColumn($alias . '.post_keywords');
            $criteria->removeSelectColumn($alias . '.post_links');
            $criteria->removeSelectColumn($alias . '.post_keywords_generated');
            $criteria->removeSelectColumn($alias . '.post_fb_id');
            $criteria->removeSelectColumn($alias . '.post_date');
            $criteria->removeSelectColumn($alias . '.post_hits');
            $criteria->removeSelectColumn($alias . '.post_insert');
            $criteria->removeSelectColumn($alias . '.post_update');
            $criteria->removeSelectColumn($alias . '.post_created');
            $criteria->removeSelectColumn($alias . '.post_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(PostTableMap::DATABASE_NAME)->getTable(PostTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Post or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Post object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(PostTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Post) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PostTableMap::DATABASE_NAME);
            $criteria->add(PostTableMap::COL_POST_ID, (array) $values, Criteria::IN);
        }

        $query = PostQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            PostTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                PostTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the posts table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return PostQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Post or Criteria object.
     *
     * @param mixed $criteria Criteria or Post object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PostTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Post object
        }

        if ($criteria->containsKey(PostTableMap::COL_POST_ID) && $criteria->keyContainsValue(PostTableMap::COL_POST_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.PostTableMap::COL_POST_ID.')');
        }


        // Set the correct dbName
        $query = PostQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
