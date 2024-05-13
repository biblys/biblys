<?php

namespace Model\Map;

use Model\SpecialOffer;
use Model\SpecialOfferQuery;
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
 * This class defines the structure of the 'special_offers' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class SpecialOfferTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.SpecialOfferTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'special_offers';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'SpecialOffer';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\SpecialOffer';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.SpecialOffer';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 11;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 11;

    /**
     * the column name for the id field
     */
    public const COL_ID = 'special_offers.id';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'special_offers.site_id';

    /**
     * the column name for the name field
     */
    public const COL_NAME = 'special_offers.name';

    /**
     * the column name for the description field
     */
    public const COL_DESCRIPTION = 'special_offers.description';

    /**
     * the column name for the target_collection_id field
     */
    public const COL_TARGET_COLLECTION_ID = 'special_offers.target_collection_id';

    /**
     * the column name for the target_quantity field
     */
    public const COL_TARGET_QUANTITY = 'special_offers.target_quantity';

    /**
     * the column name for the free_article_id field
     */
    public const COL_FREE_ARTICLE_ID = 'special_offers.free_article_id';

    /**
     * the column name for the start_date field
     */
    public const COL_START_DATE = 'special_offers.start_date';

    /**
     * the column name for the end_date field
     */
    public const COL_END_DATE = 'special_offers.end_date';

    /**
     * the column name for the created_at field
     */
    public const COL_CREATED_AT = 'special_offers.created_at';

    /**
     * the column name for the updated_at field
     */
    public const COL_UPDATED_AT = 'special_offers.updated_at';

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
        self::TYPE_PHPNAME       => ['Id', 'SiteId', 'Name', 'Description', 'TargetCollectionId', 'TargetQuantity', 'FreeArticleId', 'StartDate', 'EndDate', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'siteId', 'name', 'description', 'targetCollectionId', 'targetQuantity', 'freeArticleId', 'startDate', 'endDate', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [SpecialOfferTableMap::COL_ID, SpecialOfferTableMap::COL_SITE_ID, SpecialOfferTableMap::COL_NAME, SpecialOfferTableMap::COL_DESCRIPTION, SpecialOfferTableMap::COL_TARGET_COLLECTION_ID, SpecialOfferTableMap::COL_TARGET_QUANTITY, SpecialOfferTableMap::COL_FREE_ARTICLE_ID, SpecialOfferTableMap::COL_START_DATE, SpecialOfferTableMap::COL_END_DATE, SpecialOfferTableMap::COL_CREATED_AT, SpecialOfferTableMap::COL_UPDATED_AT, ],
        self::TYPE_FIELDNAME     => ['id', 'site_id', 'name', 'description', 'target_collection_id', 'target_quantity', 'free_article_id', 'start_date', 'end_date', 'created_at', 'updated_at', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'SiteId' => 1, 'Name' => 2, 'Description' => 3, 'TargetCollectionId' => 4, 'TargetQuantity' => 5, 'FreeArticleId' => 6, 'StartDate' => 7, 'EndDate' => 8, 'CreatedAt' => 9, 'UpdatedAt' => 10, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'siteId' => 1, 'name' => 2, 'description' => 3, 'targetCollectionId' => 4, 'targetQuantity' => 5, 'freeArticleId' => 6, 'startDate' => 7, 'endDate' => 8, 'createdAt' => 9, 'updatedAt' => 10, ],
        self::TYPE_COLNAME       => [SpecialOfferTableMap::COL_ID => 0, SpecialOfferTableMap::COL_SITE_ID => 1, SpecialOfferTableMap::COL_NAME => 2, SpecialOfferTableMap::COL_DESCRIPTION => 3, SpecialOfferTableMap::COL_TARGET_COLLECTION_ID => 4, SpecialOfferTableMap::COL_TARGET_QUANTITY => 5, SpecialOfferTableMap::COL_FREE_ARTICLE_ID => 6, SpecialOfferTableMap::COL_START_DATE => 7, SpecialOfferTableMap::COL_END_DATE => 8, SpecialOfferTableMap::COL_CREATED_AT => 9, SpecialOfferTableMap::COL_UPDATED_AT => 10, ],
        self::TYPE_FIELDNAME     => ['id' => 0, 'site_id' => 1, 'name' => 2, 'description' => 3, 'target_collection_id' => 4, 'target_quantity' => 5, 'free_article_id' => 6, 'start_date' => 7, 'end_date' => 8, 'created_at' => 9, 'updated_at' => 10, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'ID',
        'SpecialOffer.Id' => 'ID',
        'id' => 'ID',
        'specialOffer.id' => 'ID',
        'SpecialOfferTableMap::COL_ID' => 'ID',
        'COL_ID' => 'ID',
        'special_offers.id' => 'ID',
        'SiteId' => 'SITE_ID',
        'SpecialOffer.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'specialOffer.siteId' => 'SITE_ID',
        'SpecialOfferTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'special_offers.site_id' => 'SITE_ID',
        'Name' => 'NAME',
        'SpecialOffer.Name' => 'NAME',
        'name' => 'NAME',
        'specialOffer.name' => 'NAME',
        'SpecialOfferTableMap::COL_NAME' => 'NAME',
        'COL_NAME' => 'NAME',
        'special_offers.name' => 'NAME',
        'Description' => 'DESCRIPTION',
        'SpecialOffer.Description' => 'DESCRIPTION',
        'description' => 'DESCRIPTION',
        'specialOffer.description' => 'DESCRIPTION',
        'SpecialOfferTableMap::COL_DESCRIPTION' => 'DESCRIPTION',
        'COL_DESCRIPTION' => 'DESCRIPTION',
        'special_offers.description' => 'DESCRIPTION',
        'TargetCollectionId' => 'TARGET_COLLECTION_ID',
        'SpecialOffer.TargetCollectionId' => 'TARGET_COLLECTION_ID',
        'targetCollectionId' => 'TARGET_COLLECTION_ID',
        'specialOffer.targetCollectionId' => 'TARGET_COLLECTION_ID',
        'SpecialOfferTableMap::COL_TARGET_COLLECTION_ID' => 'TARGET_COLLECTION_ID',
        'COL_TARGET_COLLECTION_ID' => 'TARGET_COLLECTION_ID',
        'target_collection_id' => 'TARGET_COLLECTION_ID',
        'special_offers.target_collection_id' => 'TARGET_COLLECTION_ID',
        'TargetQuantity' => 'TARGET_QUANTITY',
        'SpecialOffer.TargetQuantity' => 'TARGET_QUANTITY',
        'targetQuantity' => 'TARGET_QUANTITY',
        'specialOffer.targetQuantity' => 'TARGET_QUANTITY',
        'SpecialOfferTableMap::COL_TARGET_QUANTITY' => 'TARGET_QUANTITY',
        'COL_TARGET_QUANTITY' => 'TARGET_QUANTITY',
        'target_quantity' => 'TARGET_QUANTITY',
        'special_offers.target_quantity' => 'TARGET_QUANTITY',
        'FreeArticleId' => 'FREE_ARTICLE_ID',
        'SpecialOffer.FreeArticleId' => 'FREE_ARTICLE_ID',
        'freeArticleId' => 'FREE_ARTICLE_ID',
        'specialOffer.freeArticleId' => 'FREE_ARTICLE_ID',
        'SpecialOfferTableMap::COL_FREE_ARTICLE_ID' => 'FREE_ARTICLE_ID',
        'COL_FREE_ARTICLE_ID' => 'FREE_ARTICLE_ID',
        'free_article_id' => 'FREE_ARTICLE_ID',
        'special_offers.free_article_id' => 'FREE_ARTICLE_ID',
        'StartDate' => 'START_DATE',
        'SpecialOffer.StartDate' => 'START_DATE',
        'startDate' => 'START_DATE',
        'specialOffer.startDate' => 'START_DATE',
        'SpecialOfferTableMap::COL_START_DATE' => 'START_DATE',
        'COL_START_DATE' => 'START_DATE',
        'start_date' => 'START_DATE',
        'special_offers.start_date' => 'START_DATE',
        'EndDate' => 'END_DATE',
        'SpecialOffer.EndDate' => 'END_DATE',
        'endDate' => 'END_DATE',
        'specialOffer.endDate' => 'END_DATE',
        'SpecialOfferTableMap::COL_END_DATE' => 'END_DATE',
        'COL_END_DATE' => 'END_DATE',
        'end_date' => 'END_DATE',
        'special_offers.end_date' => 'END_DATE',
        'CreatedAt' => 'CREATED_AT',
        'SpecialOffer.CreatedAt' => 'CREATED_AT',
        'createdAt' => 'CREATED_AT',
        'specialOffer.createdAt' => 'CREATED_AT',
        'SpecialOfferTableMap::COL_CREATED_AT' => 'CREATED_AT',
        'COL_CREATED_AT' => 'CREATED_AT',
        'created_at' => 'CREATED_AT',
        'special_offers.created_at' => 'CREATED_AT',
        'UpdatedAt' => 'UPDATED_AT',
        'SpecialOffer.UpdatedAt' => 'UPDATED_AT',
        'updatedAt' => 'UPDATED_AT',
        'specialOffer.updatedAt' => 'UPDATED_AT',
        'SpecialOfferTableMap::COL_UPDATED_AT' => 'UPDATED_AT',
        'COL_UPDATED_AT' => 'UPDATED_AT',
        'updated_at' => 'UPDATED_AT',
        'special_offers.updated_at' => 'UPDATED_AT',
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
        $this->setName('special_offers');
        $this->setPhpName('SpecialOffer');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\SpecialOffer');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('site_id', 'SiteId', 'INTEGER', 'sites', 'site_id', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 128, null);
        $this->addColumn('description', 'Description', 'LONGVARCHAR', false, null, null);
        $this->addForeignKey('target_collection_id', 'TargetCollectionId', 'INTEGER', 'collections', 'collection_id', true, null, null);
        $this->addColumn('target_quantity', 'TargetQuantity', 'INTEGER', true, null, null);
        $this->addForeignKey('free_article_id', 'FreeArticleId', 'INTEGER', 'articles', 'article_id', true, null, null);
        $this->addColumn('start_date', 'StartDate', 'TIMESTAMP', true, null, null);
        $this->addColumn('end_date', 'EndDate', 'TIMESTAMP', true, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', true, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', true, null, null);
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
        $this->addRelation('TargetCollection', '\\Model\\BookCollection', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':target_collection_id',
    1 => ':collection_id',
  ),
), null, null, null, false);
        $this->addRelation('FreeArticle', '\\Model\\Article', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':free_article_id',
    1 => ':article_id',
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
            'timestampable' => ['create_column' => 'created_at', 'update_column' => 'updated_at', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? SpecialOfferTableMap::CLASS_DEFAULT : SpecialOfferTableMap::OM_CLASS;
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
     * @return array (SpecialOffer object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = SpecialOfferTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = SpecialOfferTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + SpecialOfferTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SpecialOfferTableMap::OM_CLASS;
            /** @var SpecialOffer $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            SpecialOfferTableMap::addInstanceToPool($obj, $key);
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
            $key = SpecialOfferTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = SpecialOfferTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var SpecialOffer $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                SpecialOfferTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(SpecialOfferTableMap::COL_ID);
            $criteria->addSelectColumn(SpecialOfferTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(SpecialOfferTableMap::COL_NAME);
            $criteria->addSelectColumn(SpecialOfferTableMap::COL_DESCRIPTION);
            $criteria->addSelectColumn(SpecialOfferTableMap::COL_TARGET_COLLECTION_ID);
            $criteria->addSelectColumn(SpecialOfferTableMap::COL_TARGET_QUANTITY);
            $criteria->addSelectColumn(SpecialOfferTableMap::COL_FREE_ARTICLE_ID);
            $criteria->addSelectColumn(SpecialOfferTableMap::COL_START_DATE);
            $criteria->addSelectColumn(SpecialOfferTableMap::COL_END_DATE);
            $criteria->addSelectColumn(SpecialOfferTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(SpecialOfferTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.description');
            $criteria->addSelectColumn($alias . '.target_collection_id');
            $criteria->addSelectColumn($alias . '.target_quantity');
            $criteria->addSelectColumn($alias . '.free_article_id');
            $criteria->addSelectColumn($alias . '.start_date');
            $criteria->addSelectColumn($alias . '.end_date');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
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
            $criteria->removeSelectColumn(SpecialOfferTableMap::COL_ID);
            $criteria->removeSelectColumn(SpecialOfferTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(SpecialOfferTableMap::COL_NAME);
            $criteria->removeSelectColumn(SpecialOfferTableMap::COL_DESCRIPTION);
            $criteria->removeSelectColumn(SpecialOfferTableMap::COL_TARGET_COLLECTION_ID);
            $criteria->removeSelectColumn(SpecialOfferTableMap::COL_TARGET_QUANTITY);
            $criteria->removeSelectColumn(SpecialOfferTableMap::COL_FREE_ARTICLE_ID);
            $criteria->removeSelectColumn(SpecialOfferTableMap::COL_START_DATE);
            $criteria->removeSelectColumn(SpecialOfferTableMap::COL_END_DATE);
            $criteria->removeSelectColumn(SpecialOfferTableMap::COL_CREATED_AT);
            $criteria->removeSelectColumn(SpecialOfferTableMap::COL_UPDATED_AT);
        } else {
            $criteria->removeSelectColumn($alias . '.id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.name');
            $criteria->removeSelectColumn($alias . '.description');
            $criteria->removeSelectColumn($alias . '.target_collection_id');
            $criteria->removeSelectColumn($alias . '.target_quantity');
            $criteria->removeSelectColumn($alias . '.free_article_id');
            $criteria->removeSelectColumn($alias . '.start_date');
            $criteria->removeSelectColumn($alias . '.end_date');
            $criteria->removeSelectColumn($alias . '.created_at');
            $criteria->removeSelectColumn($alias . '.updated_at');
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
        return Propel::getServiceContainer()->getDatabaseMap(SpecialOfferTableMap::DATABASE_NAME)->getTable(SpecialOfferTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a SpecialOffer or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or SpecialOffer object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(SpecialOfferTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\SpecialOffer) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(SpecialOfferTableMap::DATABASE_NAME);
            $criteria->add(SpecialOfferTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = SpecialOfferQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            SpecialOfferTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                SpecialOfferTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the special_offers table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return SpecialOfferQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a SpecialOffer or Criteria object.
     *
     * @param mixed $criteria Criteria or SpecialOffer object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SpecialOfferTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from SpecialOffer object
        }

        if ($criteria->containsKey(SpecialOfferTableMap::COL_ID) && $criteria->keyContainsValue(SpecialOfferTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.SpecialOfferTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = SpecialOfferQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
