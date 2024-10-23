<?php
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


namespace Model\Map;

use Model\BookCollection;
use Model\BookCollectionQuery;
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
 * This class defines the structure of the 'collections' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class BookCollectionTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.BookCollectionTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'collections';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'BookCollection';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\BookCollection';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.BookCollection';

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
     * the column name for the collection_id field
     */
    public const COL_COLLECTION_ID = 'collections.collection_id';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'collections.site_id';

    /**
     * the column name for the publisher_id field
     */
    public const COL_PUBLISHER_ID = 'collections.publisher_id';

    /**
     * the column name for the pricegrid_id field
     */
    public const COL_PRICEGRID_ID = 'collections.pricegrid_id';

    /**
     * the column name for the collection_name field
     */
    public const COL_COLLECTION_NAME = 'collections.collection_name';

    /**
     * the column name for the collection_url field
     */
    public const COL_COLLECTION_URL = 'collections.collection_url';

    /**
     * the column name for the collection_publisher field
     */
    public const COL_COLLECTION_PUBLISHER = 'collections.collection_publisher';

    /**
     * the column name for the collection_desc field
     */
    public const COL_COLLECTION_DESC = 'collections.collection_desc';

    /**
     * the column name for the collection_ignorenum field
     */
    public const COL_COLLECTION_IGNORENUM = 'collections.collection_ignorenum';

    /**
     * the column name for the collection_orderby field
     */
    public const COL_COLLECTION_ORDERBY = 'collections.collection_orderby';

    /**
     * the column name for the collection_incorrect_weights field
     */
    public const COL_COLLECTION_INCORRECT_WEIGHTS = 'collections.collection_incorrect_weights';

    /**
     * the column name for the collection_noosfere_id field
     */
    public const COL_COLLECTION_NOOSFERE_ID = 'collections.collection_noosfere_id';

    /**
     * the column name for the collection_insert field
     */
    public const COL_COLLECTION_INSERT = 'collections.collection_insert';

    /**
     * the column name for the collection_update field
     */
    public const COL_COLLECTION_UPDATE = 'collections.collection_update';

    /**
     * the column name for the collection_hits field
     */
    public const COL_COLLECTION_HITS = 'collections.collection_hits';

    /**
     * the column name for the collection_duplicate field
     */
    public const COL_COLLECTION_DUPLICATE = 'collections.collection_duplicate';

    /**
     * the column name for the collection_created field
     */
    public const COL_COLLECTION_CREATED = 'collections.collection_created';

    /**
     * the column name for the collection_updated field
     */
    public const COL_COLLECTION_UPDATED = 'collections.collection_updated';

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
        self::TYPE_PHPNAME       => ['Id', 'SiteId', 'PublisherId', 'PricegridId', 'Name', 'Url', 'Publisher', 'Desc', 'Ignorenum', 'Orderby', 'IncorrectWeights', 'NoosfereId', 'Insert', 'Update', 'Hits', 'Duplicate', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'siteId', 'publisherId', 'pricegridId', 'name', 'url', 'publisher', 'desc', 'ignorenum', 'orderby', 'incorrectWeights', 'noosfereId', 'insert', 'update', 'hits', 'duplicate', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [BookCollectionTableMap::COL_COLLECTION_ID, BookCollectionTableMap::COL_SITE_ID, BookCollectionTableMap::COL_PUBLISHER_ID, BookCollectionTableMap::COL_PRICEGRID_ID, BookCollectionTableMap::COL_COLLECTION_NAME, BookCollectionTableMap::COL_COLLECTION_URL, BookCollectionTableMap::COL_COLLECTION_PUBLISHER, BookCollectionTableMap::COL_COLLECTION_DESC, BookCollectionTableMap::COL_COLLECTION_IGNORENUM, BookCollectionTableMap::COL_COLLECTION_ORDERBY, BookCollectionTableMap::COL_COLLECTION_INCORRECT_WEIGHTS, BookCollectionTableMap::COL_COLLECTION_NOOSFERE_ID, BookCollectionTableMap::COL_COLLECTION_INSERT, BookCollectionTableMap::COL_COLLECTION_UPDATE, BookCollectionTableMap::COL_COLLECTION_HITS, BookCollectionTableMap::COL_COLLECTION_DUPLICATE, BookCollectionTableMap::COL_COLLECTION_CREATED, BookCollectionTableMap::COL_COLLECTION_UPDATED, ],
        self::TYPE_FIELDNAME     => ['collection_id', 'site_id', 'publisher_id', 'pricegrid_id', 'collection_name', 'collection_url', 'collection_publisher', 'collection_desc', 'collection_ignorenum', 'collection_orderby', 'collection_incorrect_weights', 'collection_noosfere_id', 'collection_insert', 'collection_update', 'collection_hits', 'collection_duplicate', 'collection_created', 'collection_updated', ],
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'SiteId' => 1, 'PublisherId' => 2, 'PricegridId' => 3, 'Name' => 4, 'Url' => 5, 'Publisher' => 6, 'Desc' => 7, 'Ignorenum' => 8, 'Orderby' => 9, 'IncorrectWeights' => 10, 'NoosfereId' => 11, 'Insert' => 12, 'Update' => 13, 'Hits' => 14, 'Duplicate' => 15, 'CreatedAt' => 16, 'UpdatedAt' => 17, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'siteId' => 1, 'publisherId' => 2, 'pricegridId' => 3, 'name' => 4, 'url' => 5, 'publisher' => 6, 'desc' => 7, 'ignorenum' => 8, 'orderby' => 9, 'incorrectWeights' => 10, 'noosfereId' => 11, 'insert' => 12, 'update' => 13, 'hits' => 14, 'duplicate' => 15, 'createdAt' => 16, 'updatedAt' => 17, ],
        self::TYPE_COLNAME       => [BookCollectionTableMap::COL_COLLECTION_ID => 0, BookCollectionTableMap::COL_SITE_ID => 1, BookCollectionTableMap::COL_PUBLISHER_ID => 2, BookCollectionTableMap::COL_PRICEGRID_ID => 3, BookCollectionTableMap::COL_COLLECTION_NAME => 4, BookCollectionTableMap::COL_COLLECTION_URL => 5, BookCollectionTableMap::COL_COLLECTION_PUBLISHER => 6, BookCollectionTableMap::COL_COLLECTION_DESC => 7, BookCollectionTableMap::COL_COLLECTION_IGNORENUM => 8, BookCollectionTableMap::COL_COLLECTION_ORDERBY => 9, BookCollectionTableMap::COL_COLLECTION_INCORRECT_WEIGHTS => 10, BookCollectionTableMap::COL_COLLECTION_NOOSFERE_ID => 11, BookCollectionTableMap::COL_COLLECTION_INSERT => 12, BookCollectionTableMap::COL_COLLECTION_UPDATE => 13, BookCollectionTableMap::COL_COLLECTION_HITS => 14, BookCollectionTableMap::COL_COLLECTION_DUPLICATE => 15, BookCollectionTableMap::COL_COLLECTION_CREATED => 16, BookCollectionTableMap::COL_COLLECTION_UPDATED => 17, ],
        self::TYPE_FIELDNAME     => ['collection_id' => 0, 'site_id' => 1, 'publisher_id' => 2, 'pricegrid_id' => 3, 'collection_name' => 4, 'collection_url' => 5, 'collection_publisher' => 6, 'collection_desc' => 7, 'collection_ignorenum' => 8, 'collection_orderby' => 9, 'collection_incorrect_weights' => 10, 'collection_noosfere_id' => 11, 'collection_insert' => 12, 'collection_update' => 13, 'collection_hits' => 14, 'collection_duplicate' => 15, 'collection_created' => 16, 'collection_updated' => 17, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'COLLECTION_ID',
        'BookCollection.Id' => 'COLLECTION_ID',
        'id' => 'COLLECTION_ID',
        'bookCollection.id' => 'COLLECTION_ID',
        'BookCollectionTableMap::COL_COLLECTION_ID' => 'COLLECTION_ID',
        'COL_COLLECTION_ID' => 'COLLECTION_ID',
        'collection_id' => 'COLLECTION_ID',
        'collections.collection_id' => 'COLLECTION_ID',
        'SiteId' => 'SITE_ID',
        'BookCollection.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'bookCollection.siteId' => 'SITE_ID',
        'BookCollectionTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'collections.site_id' => 'SITE_ID',
        'PublisherId' => 'PUBLISHER_ID',
        'BookCollection.PublisherId' => 'PUBLISHER_ID',
        'publisherId' => 'PUBLISHER_ID',
        'bookCollection.publisherId' => 'PUBLISHER_ID',
        'BookCollectionTableMap::COL_PUBLISHER_ID' => 'PUBLISHER_ID',
        'COL_PUBLISHER_ID' => 'PUBLISHER_ID',
        'publisher_id' => 'PUBLISHER_ID',
        'collections.publisher_id' => 'PUBLISHER_ID',
        'PricegridId' => 'PRICEGRID_ID',
        'BookCollection.PricegridId' => 'PRICEGRID_ID',
        'pricegridId' => 'PRICEGRID_ID',
        'bookCollection.pricegridId' => 'PRICEGRID_ID',
        'BookCollectionTableMap::COL_PRICEGRID_ID' => 'PRICEGRID_ID',
        'COL_PRICEGRID_ID' => 'PRICEGRID_ID',
        'pricegrid_id' => 'PRICEGRID_ID',
        'collections.pricegrid_id' => 'PRICEGRID_ID',
        'Name' => 'COLLECTION_NAME',
        'BookCollection.Name' => 'COLLECTION_NAME',
        'name' => 'COLLECTION_NAME',
        'bookCollection.name' => 'COLLECTION_NAME',
        'BookCollectionTableMap::COL_COLLECTION_NAME' => 'COLLECTION_NAME',
        'COL_COLLECTION_NAME' => 'COLLECTION_NAME',
        'collection_name' => 'COLLECTION_NAME',
        'collections.collection_name' => 'COLLECTION_NAME',
        'Url' => 'COLLECTION_URL',
        'BookCollection.Url' => 'COLLECTION_URL',
        'url' => 'COLLECTION_URL',
        'bookCollection.url' => 'COLLECTION_URL',
        'BookCollectionTableMap::COL_COLLECTION_URL' => 'COLLECTION_URL',
        'COL_COLLECTION_URL' => 'COLLECTION_URL',
        'collection_url' => 'COLLECTION_URL',
        'collections.collection_url' => 'COLLECTION_URL',
        'Publisher' => 'COLLECTION_PUBLISHER',
        'BookCollection.Publisher' => 'COLLECTION_PUBLISHER',
        'publisher' => 'COLLECTION_PUBLISHER',
        'bookCollection.publisher' => 'COLLECTION_PUBLISHER',
        'BookCollectionTableMap::COL_COLLECTION_PUBLISHER' => 'COLLECTION_PUBLISHER',
        'COL_COLLECTION_PUBLISHER' => 'COLLECTION_PUBLISHER',
        'collection_publisher' => 'COLLECTION_PUBLISHER',
        'collections.collection_publisher' => 'COLLECTION_PUBLISHER',
        'Desc' => 'COLLECTION_DESC',
        'BookCollection.Desc' => 'COLLECTION_DESC',
        'desc' => 'COLLECTION_DESC',
        'bookCollection.desc' => 'COLLECTION_DESC',
        'BookCollectionTableMap::COL_COLLECTION_DESC' => 'COLLECTION_DESC',
        'COL_COLLECTION_DESC' => 'COLLECTION_DESC',
        'collection_desc' => 'COLLECTION_DESC',
        'collections.collection_desc' => 'COLLECTION_DESC',
        'Ignorenum' => 'COLLECTION_IGNORENUM',
        'BookCollection.Ignorenum' => 'COLLECTION_IGNORENUM',
        'ignorenum' => 'COLLECTION_IGNORENUM',
        'bookCollection.ignorenum' => 'COLLECTION_IGNORENUM',
        'BookCollectionTableMap::COL_COLLECTION_IGNORENUM' => 'COLLECTION_IGNORENUM',
        'COL_COLLECTION_IGNORENUM' => 'COLLECTION_IGNORENUM',
        'collection_ignorenum' => 'COLLECTION_IGNORENUM',
        'collections.collection_ignorenum' => 'COLLECTION_IGNORENUM',
        'Orderby' => 'COLLECTION_ORDERBY',
        'BookCollection.Orderby' => 'COLLECTION_ORDERBY',
        'orderby' => 'COLLECTION_ORDERBY',
        'bookCollection.orderby' => 'COLLECTION_ORDERBY',
        'BookCollectionTableMap::COL_COLLECTION_ORDERBY' => 'COLLECTION_ORDERBY',
        'COL_COLLECTION_ORDERBY' => 'COLLECTION_ORDERBY',
        'collection_orderby' => 'COLLECTION_ORDERBY',
        'collections.collection_orderby' => 'COLLECTION_ORDERBY',
        'IncorrectWeights' => 'COLLECTION_INCORRECT_WEIGHTS',
        'BookCollection.IncorrectWeights' => 'COLLECTION_INCORRECT_WEIGHTS',
        'incorrectWeights' => 'COLLECTION_INCORRECT_WEIGHTS',
        'bookCollection.incorrectWeights' => 'COLLECTION_INCORRECT_WEIGHTS',
        'BookCollectionTableMap::COL_COLLECTION_INCORRECT_WEIGHTS' => 'COLLECTION_INCORRECT_WEIGHTS',
        'COL_COLLECTION_INCORRECT_WEIGHTS' => 'COLLECTION_INCORRECT_WEIGHTS',
        'collection_incorrect_weights' => 'COLLECTION_INCORRECT_WEIGHTS',
        'collections.collection_incorrect_weights' => 'COLLECTION_INCORRECT_WEIGHTS',
        'NoosfereId' => 'COLLECTION_NOOSFERE_ID',
        'BookCollection.NoosfereId' => 'COLLECTION_NOOSFERE_ID',
        'noosfereId' => 'COLLECTION_NOOSFERE_ID',
        'bookCollection.noosfereId' => 'COLLECTION_NOOSFERE_ID',
        'BookCollectionTableMap::COL_COLLECTION_NOOSFERE_ID' => 'COLLECTION_NOOSFERE_ID',
        'COL_COLLECTION_NOOSFERE_ID' => 'COLLECTION_NOOSFERE_ID',
        'collection_noosfere_id' => 'COLLECTION_NOOSFERE_ID',
        'collections.collection_noosfere_id' => 'COLLECTION_NOOSFERE_ID',
        'Insert' => 'COLLECTION_INSERT',
        'BookCollection.Insert' => 'COLLECTION_INSERT',
        'insert' => 'COLLECTION_INSERT',
        'bookCollection.insert' => 'COLLECTION_INSERT',
        'BookCollectionTableMap::COL_COLLECTION_INSERT' => 'COLLECTION_INSERT',
        'COL_COLLECTION_INSERT' => 'COLLECTION_INSERT',
        'collection_insert' => 'COLLECTION_INSERT',
        'collections.collection_insert' => 'COLLECTION_INSERT',
        'Update' => 'COLLECTION_UPDATE',
        'BookCollection.Update' => 'COLLECTION_UPDATE',
        'update' => 'COLLECTION_UPDATE',
        'bookCollection.update' => 'COLLECTION_UPDATE',
        'BookCollectionTableMap::COL_COLLECTION_UPDATE' => 'COLLECTION_UPDATE',
        'COL_COLLECTION_UPDATE' => 'COLLECTION_UPDATE',
        'collection_update' => 'COLLECTION_UPDATE',
        'collections.collection_update' => 'COLLECTION_UPDATE',
        'Hits' => 'COLLECTION_HITS',
        'BookCollection.Hits' => 'COLLECTION_HITS',
        'hits' => 'COLLECTION_HITS',
        'bookCollection.hits' => 'COLLECTION_HITS',
        'BookCollectionTableMap::COL_COLLECTION_HITS' => 'COLLECTION_HITS',
        'COL_COLLECTION_HITS' => 'COLLECTION_HITS',
        'collection_hits' => 'COLLECTION_HITS',
        'collections.collection_hits' => 'COLLECTION_HITS',
        'Duplicate' => 'COLLECTION_DUPLICATE',
        'BookCollection.Duplicate' => 'COLLECTION_DUPLICATE',
        'duplicate' => 'COLLECTION_DUPLICATE',
        'bookCollection.duplicate' => 'COLLECTION_DUPLICATE',
        'BookCollectionTableMap::COL_COLLECTION_DUPLICATE' => 'COLLECTION_DUPLICATE',
        'COL_COLLECTION_DUPLICATE' => 'COLLECTION_DUPLICATE',
        'collection_duplicate' => 'COLLECTION_DUPLICATE',
        'collections.collection_duplicate' => 'COLLECTION_DUPLICATE',
        'CreatedAt' => 'COLLECTION_CREATED',
        'BookCollection.CreatedAt' => 'COLLECTION_CREATED',
        'createdAt' => 'COLLECTION_CREATED',
        'bookCollection.createdAt' => 'COLLECTION_CREATED',
        'BookCollectionTableMap::COL_COLLECTION_CREATED' => 'COLLECTION_CREATED',
        'COL_COLLECTION_CREATED' => 'COLLECTION_CREATED',
        'collection_created' => 'COLLECTION_CREATED',
        'collections.collection_created' => 'COLLECTION_CREATED',
        'UpdatedAt' => 'COLLECTION_UPDATED',
        'BookCollection.UpdatedAt' => 'COLLECTION_UPDATED',
        'updatedAt' => 'COLLECTION_UPDATED',
        'bookCollection.updatedAt' => 'COLLECTION_UPDATED',
        'BookCollectionTableMap::COL_COLLECTION_UPDATED' => 'COLLECTION_UPDATED',
        'COL_COLLECTION_UPDATED' => 'COLLECTION_UPDATED',
        'collection_updated' => 'COLLECTION_UPDATED',
        'collections.collection_updated' => 'COLLECTION_UPDATED',
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
        $this->setName('collections');
        $this->setPhpName('BookCollection');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\BookCollection');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('collection_id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('site_id', 'SiteId', 'INTEGER', false, null, null);
        $this->addColumn('publisher_id', 'PublisherId', 'INTEGER', false, null, null);
        $this->addColumn('pricegrid_id', 'PricegridId', 'INTEGER', false, null, null);
        $this->addColumn('collection_name', 'Name', 'VARCHAR', false, 255, null);
        $this->addColumn('collection_url', 'Url', 'VARCHAR', false, 255, null);
        $this->addColumn('collection_publisher', 'Publisher', 'VARCHAR', false, 255, null);
        $this->addColumn('collection_desc', 'Desc', 'LONGVARCHAR', false, null, null);
        $this->addColumn('collection_ignorenum', 'Ignorenum', 'BOOLEAN', false, 1, null);
        $this->addColumn('collection_orderby', 'Orderby', 'LONGVARCHAR', false, null, null);
        $this->addColumn('collection_incorrect_weights', 'IncorrectWeights', 'BOOLEAN', false, 1, null);
        $this->addColumn('collection_noosfere_id', 'NoosfereId', 'INTEGER', false, null, null);
        $this->addColumn('collection_insert', 'Insert', 'TIMESTAMP', false, null, 'CURRENT_TIMESTAMP');
        $this->addColumn('collection_update', 'Update', 'TIMESTAMP', false, null, null);
        $this->addColumn('collection_hits', 'Hits', 'INTEGER', false, null, null);
        $this->addColumn('collection_duplicate', 'Duplicate', 'BOOLEAN', false, 1, false);
        $this->addColumn('collection_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('collection_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
        $this->addRelation('Article', '\\Model\\Article', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':collection_id',
    1 => ':collection_id',
  ),
), null, null, 'Articles', false);
        $this->addRelation('SpecialOffer', '\\Model\\SpecialOffer', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':target_collection_id',
    1 => ':collection_id',
  ),
), null, null, 'SpecialOffers', false);
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
            'timestampable' => ['create_column' => 'collection_created', 'update_column' => 'collection_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? BookCollectionTableMap::CLASS_DEFAULT : BookCollectionTableMap::OM_CLASS;
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
     * @return array (BookCollection object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = BookCollectionTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = BookCollectionTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + BookCollectionTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = BookCollectionTableMap::OM_CLASS;
            /** @var BookCollection $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            BookCollectionTableMap::addInstanceToPool($obj, $key);
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
            $key = BookCollectionTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = BookCollectionTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var BookCollection $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                BookCollectionTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(BookCollectionTableMap::COL_COLLECTION_ID);
            $criteria->addSelectColumn(BookCollectionTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(BookCollectionTableMap::COL_PUBLISHER_ID);
            $criteria->addSelectColumn(BookCollectionTableMap::COL_PRICEGRID_ID);
            $criteria->addSelectColumn(BookCollectionTableMap::COL_COLLECTION_NAME);
            $criteria->addSelectColumn(BookCollectionTableMap::COL_COLLECTION_URL);
            $criteria->addSelectColumn(BookCollectionTableMap::COL_COLLECTION_PUBLISHER);
            $criteria->addSelectColumn(BookCollectionTableMap::COL_COLLECTION_DESC);
            $criteria->addSelectColumn(BookCollectionTableMap::COL_COLLECTION_IGNORENUM);
            $criteria->addSelectColumn(BookCollectionTableMap::COL_COLLECTION_ORDERBY);
            $criteria->addSelectColumn(BookCollectionTableMap::COL_COLLECTION_INCORRECT_WEIGHTS);
            $criteria->addSelectColumn(BookCollectionTableMap::COL_COLLECTION_NOOSFERE_ID);
            $criteria->addSelectColumn(BookCollectionTableMap::COL_COLLECTION_INSERT);
            $criteria->addSelectColumn(BookCollectionTableMap::COL_COLLECTION_UPDATE);
            $criteria->addSelectColumn(BookCollectionTableMap::COL_COLLECTION_HITS);
            $criteria->addSelectColumn(BookCollectionTableMap::COL_COLLECTION_DUPLICATE);
            $criteria->addSelectColumn(BookCollectionTableMap::COL_COLLECTION_CREATED);
            $criteria->addSelectColumn(BookCollectionTableMap::COL_COLLECTION_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.collection_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.publisher_id');
            $criteria->addSelectColumn($alias . '.pricegrid_id');
            $criteria->addSelectColumn($alias . '.collection_name');
            $criteria->addSelectColumn($alias . '.collection_url');
            $criteria->addSelectColumn($alias . '.collection_publisher');
            $criteria->addSelectColumn($alias . '.collection_desc');
            $criteria->addSelectColumn($alias . '.collection_ignorenum');
            $criteria->addSelectColumn($alias . '.collection_orderby');
            $criteria->addSelectColumn($alias . '.collection_incorrect_weights');
            $criteria->addSelectColumn($alias . '.collection_noosfere_id');
            $criteria->addSelectColumn($alias . '.collection_insert');
            $criteria->addSelectColumn($alias . '.collection_update');
            $criteria->addSelectColumn($alias . '.collection_hits');
            $criteria->addSelectColumn($alias . '.collection_duplicate');
            $criteria->addSelectColumn($alias . '.collection_created');
            $criteria->addSelectColumn($alias . '.collection_updated');
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
            $criteria->removeSelectColumn(BookCollectionTableMap::COL_COLLECTION_ID);
            $criteria->removeSelectColumn(BookCollectionTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(BookCollectionTableMap::COL_PUBLISHER_ID);
            $criteria->removeSelectColumn(BookCollectionTableMap::COL_PRICEGRID_ID);
            $criteria->removeSelectColumn(BookCollectionTableMap::COL_COLLECTION_NAME);
            $criteria->removeSelectColumn(BookCollectionTableMap::COL_COLLECTION_URL);
            $criteria->removeSelectColumn(BookCollectionTableMap::COL_COLLECTION_PUBLISHER);
            $criteria->removeSelectColumn(BookCollectionTableMap::COL_COLLECTION_DESC);
            $criteria->removeSelectColumn(BookCollectionTableMap::COL_COLLECTION_IGNORENUM);
            $criteria->removeSelectColumn(BookCollectionTableMap::COL_COLLECTION_ORDERBY);
            $criteria->removeSelectColumn(BookCollectionTableMap::COL_COLLECTION_INCORRECT_WEIGHTS);
            $criteria->removeSelectColumn(BookCollectionTableMap::COL_COLLECTION_NOOSFERE_ID);
            $criteria->removeSelectColumn(BookCollectionTableMap::COL_COLLECTION_INSERT);
            $criteria->removeSelectColumn(BookCollectionTableMap::COL_COLLECTION_UPDATE);
            $criteria->removeSelectColumn(BookCollectionTableMap::COL_COLLECTION_HITS);
            $criteria->removeSelectColumn(BookCollectionTableMap::COL_COLLECTION_DUPLICATE);
            $criteria->removeSelectColumn(BookCollectionTableMap::COL_COLLECTION_CREATED);
            $criteria->removeSelectColumn(BookCollectionTableMap::COL_COLLECTION_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.collection_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.publisher_id');
            $criteria->removeSelectColumn($alias . '.pricegrid_id');
            $criteria->removeSelectColumn($alias . '.collection_name');
            $criteria->removeSelectColumn($alias . '.collection_url');
            $criteria->removeSelectColumn($alias . '.collection_publisher');
            $criteria->removeSelectColumn($alias . '.collection_desc');
            $criteria->removeSelectColumn($alias . '.collection_ignorenum');
            $criteria->removeSelectColumn($alias . '.collection_orderby');
            $criteria->removeSelectColumn($alias . '.collection_incorrect_weights');
            $criteria->removeSelectColumn($alias . '.collection_noosfere_id');
            $criteria->removeSelectColumn($alias . '.collection_insert');
            $criteria->removeSelectColumn($alias . '.collection_update');
            $criteria->removeSelectColumn($alias . '.collection_hits');
            $criteria->removeSelectColumn($alias . '.collection_duplicate');
            $criteria->removeSelectColumn($alias . '.collection_created');
            $criteria->removeSelectColumn($alias . '.collection_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(BookCollectionTableMap::DATABASE_NAME)->getTable(BookCollectionTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a BookCollection or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or BookCollection object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(BookCollectionTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\BookCollection) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(BookCollectionTableMap::DATABASE_NAME);
            $criteria->add(BookCollectionTableMap::COL_COLLECTION_ID, (array) $values, Criteria::IN);
        }

        $query = BookCollectionQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            BookCollectionTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                BookCollectionTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the collections table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return BookCollectionQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a BookCollection or Criteria object.
     *
     * @param mixed $criteria Criteria or BookCollection object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(BookCollectionTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from BookCollection object
        }

        if ($criteria->containsKey(BookCollectionTableMap::COL_COLLECTION_ID) && $criteria->keyContainsValue(BookCollectionTableMap::COL_COLLECTION_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.BookCollectionTableMap::COL_COLLECTION_ID.')');
        }


        // Set the correct dbName
        $query = BookCollectionQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
