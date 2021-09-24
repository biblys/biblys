<?php

namespace Model\Map;

use Model\CrowfundingReward;
use Model\CrowfundingRewardQuery;
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
 * This class defines the structure of the 'cf_rewards' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class CrowfundingRewardTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Model.Map.CrowfundingRewardTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'cf_rewards';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\CrowfundingReward';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.CrowfundingReward';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 14;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 14;

    /**
     * the column name for the reward_id field
     */
    const COL_REWARD_ID = 'cf_rewards.reward_id';

    /**
     * the column name for the site_id field
     */
    const COL_SITE_ID = 'cf_rewards.site_id';

    /**
     * the column name for the campaign_id field
     */
    const COL_CAMPAIGN_ID = 'cf_rewards.campaign_id';

    /**
     * the column name for the reward_content field
     */
    const COL_REWARD_CONTENT = 'cf_rewards.reward_content';

    /**
     * the column name for the reward_articles field
     */
    const COL_REWARD_ARTICLES = 'cf_rewards.reward_articles';

    /**
     * the column name for the reward_price field
     */
    const COL_REWARD_PRICE = 'cf_rewards.reward_price';

    /**
     * the column name for the reward_limited field
     */
    const COL_REWARD_LIMITED = 'cf_rewards.reward_limited';

    /**
     * the column name for the reward_highlighted field
     */
    const COL_REWARD_HIGHLIGHTED = 'cf_rewards.reward_highlighted';

    /**
     * the column name for the reward_image field
     */
    const COL_REWARD_IMAGE = 'cf_rewards.reward_image';

    /**
     * the column name for the reward_quantity field
     */
    const COL_REWARD_QUANTITY = 'cf_rewards.reward_quantity';

    /**
     * the column name for the reward_backers field
     */
    const COL_REWARD_BACKERS = 'cf_rewards.reward_backers';

    /**
     * the column name for the reward_created field
     */
    const COL_REWARD_CREATED = 'cf_rewards.reward_created';

    /**
     * the column name for the reward_updated field
     */
    const COL_REWARD_UPDATED = 'cf_rewards.reward_updated';

    /**
     * the column name for the reward_deleted field
     */
    const COL_REWARD_DELETED = 'cf_rewards.reward_deleted';

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
        self::TYPE_PHPNAME       => array('Id', 'SiteId', 'CampaignId', 'Content', 'Articles', 'Price', 'Limited', 'Highlighted', 'Image', 'Quantity', 'Backers', 'CreatedAt', 'UpdatedAt', 'DeletedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'siteId', 'campaignId', 'content', 'articles', 'price', 'limited', 'highlighted', 'image', 'quantity', 'backers', 'createdAt', 'updatedAt', 'deletedAt', ),
        self::TYPE_COLNAME       => array(CrowfundingRewardTableMap::COL_REWARD_ID, CrowfundingRewardTableMap::COL_SITE_ID, CrowfundingRewardTableMap::COL_CAMPAIGN_ID, CrowfundingRewardTableMap::COL_REWARD_CONTENT, CrowfundingRewardTableMap::COL_REWARD_ARTICLES, CrowfundingRewardTableMap::COL_REWARD_PRICE, CrowfundingRewardTableMap::COL_REWARD_LIMITED, CrowfundingRewardTableMap::COL_REWARD_HIGHLIGHTED, CrowfundingRewardTableMap::COL_REWARD_IMAGE, CrowfundingRewardTableMap::COL_REWARD_QUANTITY, CrowfundingRewardTableMap::COL_REWARD_BACKERS, CrowfundingRewardTableMap::COL_REWARD_CREATED, CrowfundingRewardTableMap::COL_REWARD_UPDATED, CrowfundingRewardTableMap::COL_REWARD_DELETED, ),
        self::TYPE_FIELDNAME     => array('reward_id', 'site_id', 'campaign_id', 'reward_content', 'reward_articles', 'reward_price', 'reward_limited', 'reward_highlighted', 'reward_image', 'reward_quantity', 'reward_backers', 'reward_created', 'reward_updated', 'reward_deleted', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'SiteId' => 1, 'CampaignId' => 2, 'Content' => 3, 'Articles' => 4, 'Price' => 5, 'Limited' => 6, 'Highlighted' => 7, 'Image' => 8, 'Quantity' => 9, 'Backers' => 10, 'CreatedAt' => 11, 'UpdatedAt' => 12, 'DeletedAt' => 13, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'siteId' => 1, 'campaignId' => 2, 'content' => 3, 'articles' => 4, 'price' => 5, 'limited' => 6, 'highlighted' => 7, 'image' => 8, 'quantity' => 9, 'backers' => 10, 'createdAt' => 11, 'updatedAt' => 12, 'deletedAt' => 13, ),
        self::TYPE_COLNAME       => array(CrowfundingRewardTableMap::COL_REWARD_ID => 0, CrowfundingRewardTableMap::COL_SITE_ID => 1, CrowfundingRewardTableMap::COL_CAMPAIGN_ID => 2, CrowfundingRewardTableMap::COL_REWARD_CONTENT => 3, CrowfundingRewardTableMap::COL_REWARD_ARTICLES => 4, CrowfundingRewardTableMap::COL_REWARD_PRICE => 5, CrowfundingRewardTableMap::COL_REWARD_LIMITED => 6, CrowfundingRewardTableMap::COL_REWARD_HIGHLIGHTED => 7, CrowfundingRewardTableMap::COL_REWARD_IMAGE => 8, CrowfundingRewardTableMap::COL_REWARD_QUANTITY => 9, CrowfundingRewardTableMap::COL_REWARD_BACKERS => 10, CrowfundingRewardTableMap::COL_REWARD_CREATED => 11, CrowfundingRewardTableMap::COL_REWARD_UPDATED => 12, CrowfundingRewardTableMap::COL_REWARD_DELETED => 13, ),
        self::TYPE_FIELDNAME     => array('reward_id' => 0, 'site_id' => 1, 'campaign_id' => 2, 'reward_content' => 3, 'reward_articles' => 4, 'reward_price' => 5, 'reward_limited' => 6, 'reward_highlighted' => 7, 'reward_image' => 8, 'reward_quantity' => 9, 'reward_backers' => 10, 'reward_created' => 11, 'reward_updated' => 12, 'reward_deleted' => 13, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, )
    );

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var string[]
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'REWARD_ID',
        'CrowfundingReward.Id' => 'REWARD_ID',
        'id' => 'REWARD_ID',
        'crowfundingReward.id' => 'REWARD_ID',
        'CrowfundingRewardTableMap::COL_REWARD_ID' => 'REWARD_ID',
        'COL_REWARD_ID' => 'REWARD_ID',
        'reward_id' => 'REWARD_ID',
        'cf_rewards.reward_id' => 'REWARD_ID',
        'SiteId' => 'SITE_ID',
        'CrowfundingReward.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'crowfundingReward.siteId' => 'SITE_ID',
        'CrowfundingRewardTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'cf_rewards.site_id' => 'SITE_ID',
        'CampaignId' => 'CAMPAIGN_ID',
        'CrowfundingReward.CampaignId' => 'CAMPAIGN_ID',
        'campaignId' => 'CAMPAIGN_ID',
        'crowfundingReward.campaignId' => 'CAMPAIGN_ID',
        'CrowfundingRewardTableMap::COL_CAMPAIGN_ID' => 'CAMPAIGN_ID',
        'COL_CAMPAIGN_ID' => 'CAMPAIGN_ID',
        'campaign_id' => 'CAMPAIGN_ID',
        'cf_rewards.campaign_id' => 'CAMPAIGN_ID',
        'Content' => 'REWARD_CONTENT',
        'CrowfundingReward.Content' => 'REWARD_CONTENT',
        'content' => 'REWARD_CONTENT',
        'crowfundingReward.content' => 'REWARD_CONTENT',
        'CrowfundingRewardTableMap::COL_REWARD_CONTENT' => 'REWARD_CONTENT',
        'COL_REWARD_CONTENT' => 'REWARD_CONTENT',
        'reward_content' => 'REWARD_CONTENT',
        'cf_rewards.reward_content' => 'REWARD_CONTENT',
        'Articles' => 'REWARD_ARTICLES',
        'CrowfundingReward.Articles' => 'REWARD_ARTICLES',
        'articles' => 'REWARD_ARTICLES',
        'crowfundingReward.articles' => 'REWARD_ARTICLES',
        'CrowfundingRewardTableMap::COL_REWARD_ARTICLES' => 'REWARD_ARTICLES',
        'COL_REWARD_ARTICLES' => 'REWARD_ARTICLES',
        'reward_articles' => 'REWARD_ARTICLES',
        'cf_rewards.reward_articles' => 'REWARD_ARTICLES',
        'Price' => 'REWARD_PRICE',
        'CrowfundingReward.Price' => 'REWARD_PRICE',
        'price' => 'REWARD_PRICE',
        'crowfundingReward.price' => 'REWARD_PRICE',
        'CrowfundingRewardTableMap::COL_REWARD_PRICE' => 'REWARD_PRICE',
        'COL_REWARD_PRICE' => 'REWARD_PRICE',
        'reward_price' => 'REWARD_PRICE',
        'cf_rewards.reward_price' => 'REWARD_PRICE',
        'Limited' => 'REWARD_LIMITED',
        'CrowfundingReward.Limited' => 'REWARD_LIMITED',
        'limited' => 'REWARD_LIMITED',
        'crowfundingReward.limited' => 'REWARD_LIMITED',
        'CrowfundingRewardTableMap::COL_REWARD_LIMITED' => 'REWARD_LIMITED',
        'COL_REWARD_LIMITED' => 'REWARD_LIMITED',
        'reward_limited' => 'REWARD_LIMITED',
        'cf_rewards.reward_limited' => 'REWARD_LIMITED',
        'Highlighted' => 'REWARD_HIGHLIGHTED',
        'CrowfundingReward.Highlighted' => 'REWARD_HIGHLIGHTED',
        'highlighted' => 'REWARD_HIGHLIGHTED',
        'crowfundingReward.highlighted' => 'REWARD_HIGHLIGHTED',
        'CrowfundingRewardTableMap::COL_REWARD_HIGHLIGHTED' => 'REWARD_HIGHLIGHTED',
        'COL_REWARD_HIGHLIGHTED' => 'REWARD_HIGHLIGHTED',
        'reward_highlighted' => 'REWARD_HIGHLIGHTED',
        'cf_rewards.reward_highlighted' => 'REWARD_HIGHLIGHTED',
        'Image' => 'REWARD_IMAGE',
        'CrowfundingReward.Image' => 'REWARD_IMAGE',
        'image' => 'REWARD_IMAGE',
        'crowfundingReward.image' => 'REWARD_IMAGE',
        'CrowfundingRewardTableMap::COL_REWARD_IMAGE' => 'REWARD_IMAGE',
        'COL_REWARD_IMAGE' => 'REWARD_IMAGE',
        'reward_image' => 'REWARD_IMAGE',
        'cf_rewards.reward_image' => 'REWARD_IMAGE',
        'Quantity' => 'REWARD_QUANTITY',
        'CrowfundingReward.Quantity' => 'REWARD_QUANTITY',
        'quantity' => 'REWARD_QUANTITY',
        'crowfundingReward.quantity' => 'REWARD_QUANTITY',
        'CrowfundingRewardTableMap::COL_REWARD_QUANTITY' => 'REWARD_QUANTITY',
        'COL_REWARD_QUANTITY' => 'REWARD_QUANTITY',
        'reward_quantity' => 'REWARD_QUANTITY',
        'cf_rewards.reward_quantity' => 'REWARD_QUANTITY',
        'Backers' => 'REWARD_BACKERS',
        'CrowfundingReward.Backers' => 'REWARD_BACKERS',
        'backers' => 'REWARD_BACKERS',
        'crowfundingReward.backers' => 'REWARD_BACKERS',
        'CrowfundingRewardTableMap::COL_REWARD_BACKERS' => 'REWARD_BACKERS',
        'COL_REWARD_BACKERS' => 'REWARD_BACKERS',
        'reward_backers' => 'REWARD_BACKERS',
        'cf_rewards.reward_backers' => 'REWARD_BACKERS',
        'CreatedAt' => 'REWARD_CREATED',
        'CrowfundingReward.CreatedAt' => 'REWARD_CREATED',
        'createdAt' => 'REWARD_CREATED',
        'crowfundingReward.createdAt' => 'REWARD_CREATED',
        'CrowfundingRewardTableMap::COL_REWARD_CREATED' => 'REWARD_CREATED',
        'COL_REWARD_CREATED' => 'REWARD_CREATED',
        'reward_created' => 'REWARD_CREATED',
        'cf_rewards.reward_created' => 'REWARD_CREATED',
        'UpdatedAt' => 'REWARD_UPDATED',
        'CrowfundingReward.UpdatedAt' => 'REWARD_UPDATED',
        'updatedAt' => 'REWARD_UPDATED',
        'crowfundingReward.updatedAt' => 'REWARD_UPDATED',
        'CrowfundingRewardTableMap::COL_REWARD_UPDATED' => 'REWARD_UPDATED',
        'COL_REWARD_UPDATED' => 'REWARD_UPDATED',
        'reward_updated' => 'REWARD_UPDATED',
        'cf_rewards.reward_updated' => 'REWARD_UPDATED',
        'DeletedAt' => 'REWARD_DELETED',
        'CrowfundingReward.DeletedAt' => 'REWARD_DELETED',
        'deletedAt' => 'REWARD_DELETED',
        'crowfundingReward.deletedAt' => 'REWARD_DELETED',
        'CrowfundingRewardTableMap::COL_REWARD_DELETED' => 'REWARD_DELETED',
        'COL_REWARD_DELETED' => 'REWARD_DELETED',
        'reward_deleted' => 'REWARD_DELETED',
        'cf_rewards.reward_deleted' => 'REWARD_DELETED',
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
        $this->setName('cf_rewards');
        $this->setPhpName('CrowfundingReward');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\CrowfundingReward');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('reward_id', 'Id', 'INTEGER', true, 10, null);
        $this->addColumn('site_id', 'SiteId', 'INTEGER', false, 10, null);
        $this->addColumn('campaign_id', 'CampaignId', 'INTEGER', false, 10, null);
        $this->addColumn('reward_content', 'Content', 'VARCHAR', false, 1025, null);
        $this->addColumn('reward_articles', 'Articles', 'VARCHAR', false, 255, null);
        $this->addColumn('reward_price', 'Price', 'INTEGER', false, 10, null);
        $this->addColumn('reward_limited', 'Limited', 'BOOLEAN', false, 1, null);
        $this->addColumn('reward_highlighted', 'Highlighted', 'BOOLEAN', false, 1, false);
        $this->addColumn('reward_image', 'Image', 'VARCHAR', false, 256, null);
        $this->addColumn('reward_quantity', 'Quantity', 'INTEGER', false, 10, null);
        $this->addColumn('reward_backers', 'Backers', 'INTEGER', false, 10, 0);
        $this->addColumn('reward_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('reward_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('reward_deleted', 'DeletedAt', 'TIMESTAMP', false, null, null);
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
            'timestampable' => ['create_column' => 'reward_created', 'update_column' => 'reward_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? CrowfundingRewardTableMap::CLASS_DEFAULT : CrowfundingRewardTableMap::OM_CLASS;
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
     * @return array           (CrowfundingReward object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = CrowfundingRewardTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = CrowfundingRewardTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + CrowfundingRewardTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = CrowfundingRewardTableMap::OM_CLASS;
            /** @var CrowfundingReward $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            CrowfundingRewardTableMap::addInstanceToPool($obj, $key);
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
            $key = CrowfundingRewardTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = CrowfundingRewardTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var CrowfundingReward $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                CrowfundingRewardTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(CrowfundingRewardTableMap::COL_REWARD_ID);
            $criteria->addSelectColumn(CrowfundingRewardTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(CrowfundingRewardTableMap::COL_CAMPAIGN_ID);
            $criteria->addSelectColumn(CrowfundingRewardTableMap::COL_REWARD_CONTENT);
            $criteria->addSelectColumn(CrowfundingRewardTableMap::COL_REWARD_ARTICLES);
            $criteria->addSelectColumn(CrowfundingRewardTableMap::COL_REWARD_PRICE);
            $criteria->addSelectColumn(CrowfundingRewardTableMap::COL_REWARD_LIMITED);
            $criteria->addSelectColumn(CrowfundingRewardTableMap::COL_REWARD_HIGHLIGHTED);
            $criteria->addSelectColumn(CrowfundingRewardTableMap::COL_REWARD_IMAGE);
            $criteria->addSelectColumn(CrowfundingRewardTableMap::COL_REWARD_QUANTITY);
            $criteria->addSelectColumn(CrowfundingRewardTableMap::COL_REWARD_BACKERS);
            $criteria->addSelectColumn(CrowfundingRewardTableMap::COL_REWARD_CREATED);
            $criteria->addSelectColumn(CrowfundingRewardTableMap::COL_REWARD_UPDATED);
            $criteria->addSelectColumn(CrowfundingRewardTableMap::COL_REWARD_DELETED);
        } else {
            $criteria->addSelectColumn($alias . '.reward_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.campaign_id');
            $criteria->addSelectColumn($alias . '.reward_content');
            $criteria->addSelectColumn($alias . '.reward_articles');
            $criteria->addSelectColumn($alias . '.reward_price');
            $criteria->addSelectColumn($alias . '.reward_limited');
            $criteria->addSelectColumn($alias . '.reward_highlighted');
            $criteria->addSelectColumn($alias . '.reward_image');
            $criteria->addSelectColumn($alias . '.reward_quantity');
            $criteria->addSelectColumn($alias . '.reward_backers');
            $criteria->addSelectColumn($alias . '.reward_created');
            $criteria->addSelectColumn($alias . '.reward_updated');
            $criteria->addSelectColumn($alias . '.reward_deleted');
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
            $criteria->removeSelectColumn(CrowfundingRewardTableMap::COL_REWARD_ID);
            $criteria->removeSelectColumn(CrowfundingRewardTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(CrowfundingRewardTableMap::COL_CAMPAIGN_ID);
            $criteria->removeSelectColumn(CrowfundingRewardTableMap::COL_REWARD_CONTENT);
            $criteria->removeSelectColumn(CrowfundingRewardTableMap::COL_REWARD_ARTICLES);
            $criteria->removeSelectColumn(CrowfundingRewardTableMap::COL_REWARD_PRICE);
            $criteria->removeSelectColumn(CrowfundingRewardTableMap::COL_REWARD_LIMITED);
            $criteria->removeSelectColumn(CrowfundingRewardTableMap::COL_REWARD_HIGHLIGHTED);
            $criteria->removeSelectColumn(CrowfundingRewardTableMap::COL_REWARD_IMAGE);
            $criteria->removeSelectColumn(CrowfundingRewardTableMap::COL_REWARD_QUANTITY);
            $criteria->removeSelectColumn(CrowfundingRewardTableMap::COL_REWARD_BACKERS);
            $criteria->removeSelectColumn(CrowfundingRewardTableMap::COL_REWARD_CREATED);
            $criteria->removeSelectColumn(CrowfundingRewardTableMap::COL_REWARD_UPDATED);
            $criteria->removeSelectColumn(CrowfundingRewardTableMap::COL_REWARD_DELETED);
        } else {
            $criteria->removeSelectColumn($alias . '.reward_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.campaign_id');
            $criteria->removeSelectColumn($alias . '.reward_content');
            $criteria->removeSelectColumn($alias . '.reward_articles');
            $criteria->removeSelectColumn($alias . '.reward_price');
            $criteria->removeSelectColumn($alias . '.reward_limited');
            $criteria->removeSelectColumn($alias . '.reward_highlighted');
            $criteria->removeSelectColumn($alias . '.reward_image');
            $criteria->removeSelectColumn($alias . '.reward_quantity');
            $criteria->removeSelectColumn($alias . '.reward_backers');
            $criteria->removeSelectColumn($alias . '.reward_created');
            $criteria->removeSelectColumn($alias . '.reward_updated');
            $criteria->removeSelectColumn($alias . '.reward_deleted');
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
        return Propel::getServiceContainer()->getDatabaseMap(CrowfundingRewardTableMap::DATABASE_NAME)->getTable(CrowfundingRewardTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a CrowfundingReward or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or CrowfundingReward object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(CrowfundingRewardTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\CrowfundingReward) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(CrowfundingRewardTableMap::DATABASE_NAME);
            $criteria->add(CrowfundingRewardTableMap::COL_REWARD_ID, (array) $values, Criteria::IN);
        }

        $query = CrowfundingRewardQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            CrowfundingRewardTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                CrowfundingRewardTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the cf_rewards table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return CrowfundingRewardQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a CrowfundingReward or Criteria object.
     *
     * @param mixed               $criteria Criteria or CrowfundingReward object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CrowfundingRewardTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from CrowfundingReward object
        }

        if ($criteria->containsKey(CrowfundingRewardTableMap::COL_REWARD_ID) && $criteria->keyContainsValue(CrowfundingRewardTableMap::COL_REWARD_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.CrowfundingRewardTableMap::COL_REWARD_ID.')');
        }


        // Set the correct dbName
        $query = CrowfundingRewardQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // CrowfundingRewardTableMap
