<?php

namespace Model\Map;

use Model\CrowdfundingCampaign;
use Model\CrowdfundingCampaignQuery;
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
 * This class defines the structure of the 'cf_campaigns' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class CrowdfundingCampaignTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.CrowdfundingCampaignTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'cf_campaigns';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'CrowdfundingCampaign';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\CrowdfundingCampaign';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.CrowdfundingCampaign';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 13;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 13;

    /**
     * the column name for the campaign_id field
     */
    public const COL_CAMPAIGN_ID = 'cf_campaigns.campaign_id';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'cf_campaigns.site_id';

    /**
     * the column name for the campaign_title field
     */
    public const COL_CAMPAIGN_TITLE = 'cf_campaigns.campaign_title';

    /**
     * the column name for the campaign_url field
     */
    public const COL_CAMPAIGN_URL = 'cf_campaigns.campaign_url';

    /**
     * the column name for the campaign_description field
     */
    public const COL_CAMPAIGN_DESCRIPTION = 'cf_campaigns.campaign_description';

    /**
     * the column name for the campaign_image field
     */
    public const COL_CAMPAIGN_IMAGE = 'cf_campaigns.campaign_image';

    /**
     * the column name for the campaign_goal field
     */
    public const COL_CAMPAIGN_GOAL = 'cf_campaigns.campaign_goal';

    /**
     * the column name for the campaign_pledged field
     */
    public const COL_CAMPAIGN_PLEDGED = 'cf_campaigns.campaign_pledged';

    /**
     * the column name for the campaign_backers field
     */
    public const COL_CAMPAIGN_BACKERS = 'cf_campaigns.campaign_backers';

    /**
     * the column name for the campaign_starts field
     */
    public const COL_CAMPAIGN_STARTS = 'cf_campaigns.campaign_starts';

    /**
     * the column name for the campaign_ends field
     */
    public const COL_CAMPAIGN_ENDS = 'cf_campaigns.campaign_ends';

    /**
     * the column name for the campaign_created field
     */
    public const COL_CAMPAIGN_CREATED = 'cf_campaigns.campaign_created';

    /**
     * the column name for the campaign_updated field
     */
    public const COL_CAMPAIGN_UPDATED = 'cf_campaigns.campaign_updated';

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
        self::TYPE_PHPNAME       => ['Id', 'SiteId', 'Title', 'Url', 'Description', 'Image', 'Goal', 'Pledged', 'Backers', 'Starts', 'Ends', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'siteId', 'title', 'url', 'description', 'image', 'goal', 'pledged', 'backers', 'starts', 'ends', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [CrowdfundingCampaignTableMap::COL_CAMPAIGN_ID, CrowdfundingCampaignTableMap::COL_SITE_ID, CrowdfundingCampaignTableMap::COL_CAMPAIGN_TITLE, CrowdfundingCampaignTableMap::COL_CAMPAIGN_URL, CrowdfundingCampaignTableMap::COL_CAMPAIGN_DESCRIPTION, CrowdfundingCampaignTableMap::COL_CAMPAIGN_IMAGE, CrowdfundingCampaignTableMap::COL_CAMPAIGN_GOAL, CrowdfundingCampaignTableMap::COL_CAMPAIGN_PLEDGED, CrowdfundingCampaignTableMap::COL_CAMPAIGN_BACKERS, CrowdfundingCampaignTableMap::COL_CAMPAIGN_STARTS, CrowdfundingCampaignTableMap::COL_CAMPAIGN_ENDS, CrowdfundingCampaignTableMap::COL_CAMPAIGN_CREATED, CrowdfundingCampaignTableMap::COL_CAMPAIGN_UPDATED, ],
        self::TYPE_FIELDNAME     => ['campaign_id', 'site_id', 'campaign_title', 'campaign_url', 'campaign_description', 'campaign_image', 'campaign_goal', 'campaign_pledged', 'campaign_backers', 'campaign_starts', 'campaign_ends', 'campaign_created', 'campaign_updated', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'SiteId' => 1, 'Title' => 2, 'Url' => 3, 'Description' => 4, 'Image' => 5, 'Goal' => 6, 'Pledged' => 7, 'Backers' => 8, 'Starts' => 9, 'Ends' => 10, 'CreatedAt' => 11, 'UpdatedAt' => 12, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'siteId' => 1, 'title' => 2, 'url' => 3, 'description' => 4, 'image' => 5, 'goal' => 6, 'pledged' => 7, 'backers' => 8, 'starts' => 9, 'ends' => 10, 'createdAt' => 11, 'updatedAt' => 12, ],
        self::TYPE_COLNAME       => [CrowdfundingCampaignTableMap::COL_CAMPAIGN_ID => 0, CrowdfundingCampaignTableMap::COL_SITE_ID => 1, CrowdfundingCampaignTableMap::COL_CAMPAIGN_TITLE => 2, CrowdfundingCampaignTableMap::COL_CAMPAIGN_URL => 3, CrowdfundingCampaignTableMap::COL_CAMPAIGN_DESCRIPTION => 4, CrowdfundingCampaignTableMap::COL_CAMPAIGN_IMAGE => 5, CrowdfundingCampaignTableMap::COL_CAMPAIGN_GOAL => 6, CrowdfundingCampaignTableMap::COL_CAMPAIGN_PLEDGED => 7, CrowdfundingCampaignTableMap::COL_CAMPAIGN_BACKERS => 8, CrowdfundingCampaignTableMap::COL_CAMPAIGN_STARTS => 9, CrowdfundingCampaignTableMap::COL_CAMPAIGN_ENDS => 10, CrowdfundingCampaignTableMap::COL_CAMPAIGN_CREATED => 11, CrowdfundingCampaignTableMap::COL_CAMPAIGN_UPDATED => 12, ],
        self::TYPE_FIELDNAME     => ['campaign_id' => 0, 'site_id' => 1, 'campaign_title' => 2, 'campaign_url' => 3, 'campaign_description' => 4, 'campaign_image' => 5, 'campaign_goal' => 6, 'campaign_pledged' => 7, 'campaign_backers' => 8, 'campaign_starts' => 9, 'campaign_ends' => 10, 'campaign_created' => 11, 'campaign_updated' => 12, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'CAMPAIGN_ID',
        'CrowdfundingCampaign.Id' => 'CAMPAIGN_ID',
        'id' => 'CAMPAIGN_ID',
        'crowdfundingCampaign.id' => 'CAMPAIGN_ID',
        'CrowdfundingCampaignTableMap::COL_CAMPAIGN_ID' => 'CAMPAIGN_ID',
        'COL_CAMPAIGN_ID' => 'CAMPAIGN_ID',
        'campaign_id' => 'CAMPAIGN_ID',
        'cf_campaigns.campaign_id' => 'CAMPAIGN_ID',
        'SiteId' => 'SITE_ID',
        'CrowdfundingCampaign.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'crowdfundingCampaign.siteId' => 'SITE_ID',
        'CrowdfundingCampaignTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'cf_campaigns.site_id' => 'SITE_ID',
        'Title' => 'CAMPAIGN_TITLE',
        'CrowdfundingCampaign.Title' => 'CAMPAIGN_TITLE',
        'title' => 'CAMPAIGN_TITLE',
        'crowdfundingCampaign.title' => 'CAMPAIGN_TITLE',
        'CrowdfundingCampaignTableMap::COL_CAMPAIGN_TITLE' => 'CAMPAIGN_TITLE',
        'COL_CAMPAIGN_TITLE' => 'CAMPAIGN_TITLE',
        'campaign_title' => 'CAMPAIGN_TITLE',
        'cf_campaigns.campaign_title' => 'CAMPAIGN_TITLE',
        'Url' => 'CAMPAIGN_URL',
        'CrowdfundingCampaign.Url' => 'CAMPAIGN_URL',
        'url' => 'CAMPAIGN_URL',
        'crowdfundingCampaign.url' => 'CAMPAIGN_URL',
        'CrowdfundingCampaignTableMap::COL_CAMPAIGN_URL' => 'CAMPAIGN_URL',
        'COL_CAMPAIGN_URL' => 'CAMPAIGN_URL',
        'campaign_url' => 'CAMPAIGN_URL',
        'cf_campaigns.campaign_url' => 'CAMPAIGN_URL',
        'Description' => 'CAMPAIGN_DESCRIPTION',
        'CrowdfundingCampaign.Description' => 'CAMPAIGN_DESCRIPTION',
        'description' => 'CAMPAIGN_DESCRIPTION',
        'crowdfundingCampaign.description' => 'CAMPAIGN_DESCRIPTION',
        'CrowdfundingCampaignTableMap::COL_CAMPAIGN_DESCRIPTION' => 'CAMPAIGN_DESCRIPTION',
        'COL_CAMPAIGN_DESCRIPTION' => 'CAMPAIGN_DESCRIPTION',
        'campaign_description' => 'CAMPAIGN_DESCRIPTION',
        'cf_campaigns.campaign_description' => 'CAMPAIGN_DESCRIPTION',
        'Image' => 'CAMPAIGN_IMAGE',
        'CrowdfundingCampaign.Image' => 'CAMPAIGN_IMAGE',
        'image' => 'CAMPAIGN_IMAGE',
        'crowdfundingCampaign.image' => 'CAMPAIGN_IMAGE',
        'CrowdfundingCampaignTableMap::COL_CAMPAIGN_IMAGE' => 'CAMPAIGN_IMAGE',
        'COL_CAMPAIGN_IMAGE' => 'CAMPAIGN_IMAGE',
        'campaign_image' => 'CAMPAIGN_IMAGE',
        'cf_campaigns.campaign_image' => 'CAMPAIGN_IMAGE',
        'Goal' => 'CAMPAIGN_GOAL',
        'CrowdfundingCampaign.Goal' => 'CAMPAIGN_GOAL',
        'goal' => 'CAMPAIGN_GOAL',
        'crowdfundingCampaign.goal' => 'CAMPAIGN_GOAL',
        'CrowdfundingCampaignTableMap::COL_CAMPAIGN_GOAL' => 'CAMPAIGN_GOAL',
        'COL_CAMPAIGN_GOAL' => 'CAMPAIGN_GOAL',
        'campaign_goal' => 'CAMPAIGN_GOAL',
        'cf_campaigns.campaign_goal' => 'CAMPAIGN_GOAL',
        'Pledged' => 'CAMPAIGN_PLEDGED',
        'CrowdfundingCampaign.Pledged' => 'CAMPAIGN_PLEDGED',
        'pledged' => 'CAMPAIGN_PLEDGED',
        'crowdfundingCampaign.pledged' => 'CAMPAIGN_PLEDGED',
        'CrowdfundingCampaignTableMap::COL_CAMPAIGN_PLEDGED' => 'CAMPAIGN_PLEDGED',
        'COL_CAMPAIGN_PLEDGED' => 'CAMPAIGN_PLEDGED',
        'campaign_pledged' => 'CAMPAIGN_PLEDGED',
        'cf_campaigns.campaign_pledged' => 'CAMPAIGN_PLEDGED',
        'Backers' => 'CAMPAIGN_BACKERS',
        'CrowdfundingCampaign.Backers' => 'CAMPAIGN_BACKERS',
        'backers' => 'CAMPAIGN_BACKERS',
        'crowdfundingCampaign.backers' => 'CAMPAIGN_BACKERS',
        'CrowdfundingCampaignTableMap::COL_CAMPAIGN_BACKERS' => 'CAMPAIGN_BACKERS',
        'COL_CAMPAIGN_BACKERS' => 'CAMPAIGN_BACKERS',
        'campaign_backers' => 'CAMPAIGN_BACKERS',
        'cf_campaigns.campaign_backers' => 'CAMPAIGN_BACKERS',
        'Starts' => 'CAMPAIGN_STARTS',
        'CrowdfundingCampaign.Starts' => 'CAMPAIGN_STARTS',
        'starts' => 'CAMPAIGN_STARTS',
        'crowdfundingCampaign.starts' => 'CAMPAIGN_STARTS',
        'CrowdfundingCampaignTableMap::COL_CAMPAIGN_STARTS' => 'CAMPAIGN_STARTS',
        'COL_CAMPAIGN_STARTS' => 'CAMPAIGN_STARTS',
        'campaign_starts' => 'CAMPAIGN_STARTS',
        'cf_campaigns.campaign_starts' => 'CAMPAIGN_STARTS',
        'Ends' => 'CAMPAIGN_ENDS',
        'CrowdfundingCampaign.Ends' => 'CAMPAIGN_ENDS',
        'ends' => 'CAMPAIGN_ENDS',
        'crowdfundingCampaign.ends' => 'CAMPAIGN_ENDS',
        'CrowdfundingCampaignTableMap::COL_CAMPAIGN_ENDS' => 'CAMPAIGN_ENDS',
        'COL_CAMPAIGN_ENDS' => 'CAMPAIGN_ENDS',
        'campaign_ends' => 'CAMPAIGN_ENDS',
        'cf_campaigns.campaign_ends' => 'CAMPAIGN_ENDS',
        'CreatedAt' => 'CAMPAIGN_CREATED',
        'CrowdfundingCampaign.CreatedAt' => 'CAMPAIGN_CREATED',
        'createdAt' => 'CAMPAIGN_CREATED',
        'crowdfundingCampaign.createdAt' => 'CAMPAIGN_CREATED',
        'CrowdfundingCampaignTableMap::COL_CAMPAIGN_CREATED' => 'CAMPAIGN_CREATED',
        'COL_CAMPAIGN_CREATED' => 'CAMPAIGN_CREATED',
        'campaign_created' => 'CAMPAIGN_CREATED',
        'cf_campaigns.campaign_created' => 'CAMPAIGN_CREATED',
        'UpdatedAt' => 'CAMPAIGN_UPDATED',
        'CrowdfundingCampaign.UpdatedAt' => 'CAMPAIGN_UPDATED',
        'updatedAt' => 'CAMPAIGN_UPDATED',
        'crowdfundingCampaign.updatedAt' => 'CAMPAIGN_UPDATED',
        'CrowdfundingCampaignTableMap::COL_CAMPAIGN_UPDATED' => 'CAMPAIGN_UPDATED',
        'COL_CAMPAIGN_UPDATED' => 'CAMPAIGN_UPDATED',
        'campaign_updated' => 'CAMPAIGN_UPDATED',
        'cf_campaigns.campaign_updated' => 'CAMPAIGN_UPDATED',
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
        $this->setName('cf_campaigns');
        $this->setPhpName('CrowdfundingCampaign');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\CrowdfundingCampaign');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('campaign_id', 'Id', 'INTEGER', true, 10, null);
        $this->addColumn('site_id', 'SiteId', 'INTEGER', false, 10, null);
        $this->addColumn('campaign_title', 'Title', 'VARCHAR', false, 255, null);
        $this->addColumn('campaign_url', 'Url', 'VARCHAR', false, 128, null);
        $this->addColumn('campaign_description', 'Description', 'LONGVARCHAR', false, null, null);
        $this->addColumn('campaign_image', 'Image', 'VARCHAR', false, 256, null);
        $this->addColumn('campaign_goal', 'Goal', 'INTEGER', false, 10, null);
        $this->addColumn('campaign_pledged', 'Pledged', 'INTEGER', false, 10, null);
        $this->addColumn('campaign_backers', 'Backers', 'INTEGER', false, 10, null);
        $this->addColumn('campaign_starts', 'Starts', 'DATE', false, null, null);
        $this->addColumn('campaign_ends', 'Ends', 'DATE', false, null, null);
        $this->addColumn('campaign_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('campaign_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
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
            'timestampable' => ['create_column' => 'campaign_created', 'update_column' => 'campaign_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? CrowdfundingCampaignTableMap::CLASS_DEFAULT : CrowdfundingCampaignTableMap::OM_CLASS;
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
     * @return array (CrowdfundingCampaign object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = CrowdfundingCampaignTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = CrowdfundingCampaignTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + CrowdfundingCampaignTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = CrowdfundingCampaignTableMap::OM_CLASS;
            /** @var CrowdfundingCampaign $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            CrowdfundingCampaignTableMap::addInstanceToPool($obj, $key);
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
            $key = CrowdfundingCampaignTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = CrowdfundingCampaignTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var CrowdfundingCampaign $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                CrowdfundingCampaignTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(CrowdfundingCampaignTableMap::COL_CAMPAIGN_ID);
            $criteria->addSelectColumn(CrowdfundingCampaignTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(CrowdfundingCampaignTableMap::COL_CAMPAIGN_TITLE);
            $criteria->addSelectColumn(CrowdfundingCampaignTableMap::COL_CAMPAIGN_URL);
            $criteria->addSelectColumn(CrowdfundingCampaignTableMap::COL_CAMPAIGN_DESCRIPTION);
            $criteria->addSelectColumn(CrowdfundingCampaignTableMap::COL_CAMPAIGN_IMAGE);
            $criteria->addSelectColumn(CrowdfundingCampaignTableMap::COL_CAMPAIGN_GOAL);
            $criteria->addSelectColumn(CrowdfundingCampaignTableMap::COL_CAMPAIGN_PLEDGED);
            $criteria->addSelectColumn(CrowdfundingCampaignTableMap::COL_CAMPAIGN_BACKERS);
            $criteria->addSelectColumn(CrowdfundingCampaignTableMap::COL_CAMPAIGN_STARTS);
            $criteria->addSelectColumn(CrowdfundingCampaignTableMap::COL_CAMPAIGN_ENDS);
            $criteria->addSelectColumn(CrowdfundingCampaignTableMap::COL_CAMPAIGN_CREATED);
            $criteria->addSelectColumn(CrowdfundingCampaignTableMap::COL_CAMPAIGN_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.campaign_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.campaign_title');
            $criteria->addSelectColumn($alias . '.campaign_url');
            $criteria->addSelectColumn($alias . '.campaign_description');
            $criteria->addSelectColumn($alias . '.campaign_image');
            $criteria->addSelectColumn($alias . '.campaign_goal');
            $criteria->addSelectColumn($alias . '.campaign_pledged');
            $criteria->addSelectColumn($alias . '.campaign_backers');
            $criteria->addSelectColumn($alias . '.campaign_starts');
            $criteria->addSelectColumn($alias . '.campaign_ends');
            $criteria->addSelectColumn($alias . '.campaign_created');
            $criteria->addSelectColumn($alias . '.campaign_updated');
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
            $criteria->removeSelectColumn(CrowdfundingCampaignTableMap::COL_CAMPAIGN_ID);
            $criteria->removeSelectColumn(CrowdfundingCampaignTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(CrowdfundingCampaignTableMap::COL_CAMPAIGN_TITLE);
            $criteria->removeSelectColumn(CrowdfundingCampaignTableMap::COL_CAMPAIGN_URL);
            $criteria->removeSelectColumn(CrowdfundingCampaignTableMap::COL_CAMPAIGN_DESCRIPTION);
            $criteria->removeSelectColumn(CrowdfundingCampaignTableMap::COL_CAMPAIGN_IMAGE);
            $criteria->removeSelectColumn(CrowdfundingCampaignTableMap::COL_CAMPAIGN_GOAL);
            $criteria->removeSelectColumn(CrowdfundingCampaignTableMap::COL_CAMPAIGN_PLEDGED);
            $criteria->removeSelectColumn(CrowdfundingCampaignTableMap::COL_CAMPAIGN_BACKERS);
            $criteria->removeSelectColumn(CrowdfundingCampaignTableMap::COL_CAMPAIGN_STARTS);
            $criteria->removeSelectColumn(CrowdfundingCampaignTableMap::COL_CAMPAIGN_ENDS);
            $criteria->removeSelectColumn(CrowdfundingCampaignTableMap::COL_CAMPAIGN_CREATED);
            $criteria->removeSelectColumn(CrowdfundingCampaignTableMap::COL_CAMPAIGN_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.campaign_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.campaign_title');
            $criteria->removeSelectColumn($alias . '.campaign_url');
            $criteria->removeSelectColumn($alias . '.campaign_description');
            $criteria->removeSelectColumn($alias . '.campaign_image');
            $criteria->removeSelectColumn($alias . '.campaign_goal');
            $criteria->removeSelectColumn($alias . '.campaign_pledged');
            $criteria->removeSelectColumn($alias . '.campaign_backers');
            $criteria->removeSelectColumn($alias . '.campaign_starts');
            $criteria->removeSelectColumn($alias . '.campaign_ends');
            $criteria->removeSelectColumn($alias . '.campaign_created');
            $criteria->removeSelectColumn($alias . '.campaign_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(CrowdfundingCampaignTableMap::DATABASE_NAME)->getTable(CrowdfundingCampaignTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a CrowdfundingCampaign or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or CrowdfundingCampaign object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(CrowdfundingCampaignTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\CrowdfundingCampaign) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(CrowdfundingCampaignTableMap::DATABASE_NAME);
            $criteria->add(CrowdfundingCampaignTableMap::COL_CAMPAIGN_ID, (array) $values, Criteria::IN);
        }

        $query = CrowdfundingCampaignQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            CrowdfundingCampaignTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                CrowdfundingCampaignTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the cf_campaigns table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return CrowdfundingCampaignQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a CrowdfundingCampaign or Criteria object.
     *
     * @param mixed $criteria Criteria or CrowdfundingCampaign object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CrowdfundingCampaignTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from CrowdfundingCampaign object
        }

        if ($criteria->containsKey(CrowdfundingCampaignTableMap::COL_CAMPAIGN_ID) && $criteria->keyContainsValue(CrowdfundingCampaignTableMap::COL_CAMPAIGN_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.CrowdfundingCampaignTableMap::COL_CAMPAIGN_ID.')');
        }


        // Set the correct dbName
        $query = CrowdfundingCampaignQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
