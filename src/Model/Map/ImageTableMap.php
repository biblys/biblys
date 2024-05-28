<?php

namespace Model\Map;

use Model\Image;
use Model\ImageQuery;
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
 * This class defines the structure of the 'images' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class ImageTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.ImageTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'images';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Image';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Image';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Image';

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
     * the column name for the id field
     */
    public const COL_ID = 'images.id';

    /**
     * the column name for the type field
     */
    public const COL_TYPE = 'images.type';

    /**
     * the column name for the filePath field
     */
    public const COL_FILEPATH = 'images.filePath';

    /**
     * the column name for the fileName field
     */
    public const COL_FILENAME = 'images.fileName';

    /**
     * the column name for the version field
     */
    public const COL_VERSION = 'images.version';

    /**
     * the column name for the mediaType field
     */
    public const COL_MEDIATYPE = 'images.mediaType';

    /**
     * the column name for the fileSize field
     */
    public const COL_FILESIZE = 'images.fileSize';

    /**
     * the column name for the height field
     */
    public const COL_HEIGHT = 'images.height';

    /**
     * the column name for the width field
     */
    public const COL_WIDTH = 'images.width';

    /**
     * the column name for the article_id field
     */
    public const COL_ARTICLE_ID = 'images.article_id';

    /**
     * the column name for the stock_item_id field
     */
    public const COL_STOCK_ITEM_ID = 'images.stock_item_id';

    /**
     * the column name for the contributor_id field
     */
    public const COL_CONTRIBUTOR_ID = 'images.contributor_id';

    /**
     * the column name for the post_id field
     */
    public const COL_POST_ID = 'images.post_id';

    /**
     * the column name for the event_id field
     */
    public const COL_EVENT_ID = 'images.event_id';

    /**
     * the column name for the publisher_id field
     */
    public const COL_PUBLISHER_ID = 'images.publisher_id';

    /**
     * the column name for the uploaded_at field
     */
    public const COL_UPLOADED_AT = 'images.uploaded_at';

    /**
     * the column name for the created_at field
     */
    public const COL_CREATED_AT = 'images.created_at';

    /**
     * the column name for the updated_at field
     */
    public const COL_UPDATED_AT = 'images.updated_at';

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
        self::TYPE_PHPNAME       => ['Id', 'Type', 'Filepath', 'Filename', 'Version', 'Mediatype', 'Filesize', 'Height', 'Width', 'ArticleId', 'StockItemId', 'ContributorId', 'PostId', 'EventId', 'PublisherId', 'uploaded_at', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'type', 'filepath', 'filename', 'version', 'mediatype', 'filesize', 'height', 'width', 'articleId', 'stockItemId', 'contributorId', 'postId', 'eventId', 'publisherId', 'uploaded_at', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [ImageTableMap::COL_ID, ImageTableMap::COL_TYPE, ImageTableMap::COL_FILEPATH, ImageTableMap::COL_FILENAME, ImageTableMap::COL_VERSION, ImageTableMap::COL_MEDIATYPE, ImageTableMap::COL_FILESIZE, ImageTableMap::COL_HEIGHT, ImageTableMap::COL_WIDTH, ImageTableMap::COL_ARTICLE_ID, ImageTableMap::COL_STOCK_ITEM_ID, ImageTableMap::COL_CONTRIBUTOR_ID, ImageTableMap::COL_POST_ID, ImageTableMap::COL_EVENT_ID, ImageTableMap::COL_PUBLISHER_ID, ImageTableMap::COL_UPLOADED_AT, ImageTableMap::COL_CREATED_AT, ImageTableMap::COL_UPDATED_AT, ],
        self::TYPE_FIELDNAME     => ['id', 'type', 'filePath', 'fileName', 'version', 'mediaType', 'fileSize', 'height', 'width', 'article_id', 'stock_item_id', 'contributor_id', 'post_id', 'event_id', 'publisher_id', 'uploaded_at', 'created_at', 'updated_at', ],
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'Type' => 1, 'Filepath' => 2, 'Filename' => 3, 'Version' => 4, 'Mediatype' => 5, 'Filesize' => 6, 'Height' => 7, 'Width' => 8, 'ArticleId' => 9, 'StockItemId' => 10, 'ContributorId' => 11, 'PostId' => 12, 'EventId' => 13, 'PublisherId' => 14, 'uploaded_at' => 15, 'CreatedAt' => 16, 'UpdatedAt' => 17, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'type' => 1, 'filepath' => 2, 'filename' => 3, 'version' => 4, 'mediatype' => 5, 'filesize' => 6, 'height' => 7, 'width' => 8, 'articleId' => 9, 'stockItemId' => 10, 'contributorId' => 11, 'postId' => 12, 'eventId' => 13, 'publisherId' => 14, 'uploaded_at' => 15, 'createdAt' => 16, 'updatedAt' => 17, ],
        self::TYPE_COLNAME       => [ImageTableMap::COL_ID => 0, ImageTableMap::COL_TYPE => 1, ImageTableMap::COL_FILEPATH => 2, ImageTableMap::COL_FILENAME => 3, ImageTableMap::COL_VERSION => 4, ImageTableMap::COL_MEDIATYPE => 5, ImageTableMap::COL_FILESIZE => 6, ImageTableMap::COL_HEIGHT => 7, ImageTableMap::COL_WIDTH => 8, ImageTableMap::COL_ARTICLE_ID => 9, ImageTableMap::COL_STOCK_ITEM_ID => 10, ImageTableMap::COL_CONTRIBUTOR_ID => 11, ImageTableMap::COL_POST_ID => 12, ImageTableMap::COL_EVENT_ID => 13, ImageTableMap::COL_PUBLISHER_ID => 14, ImageTableMap::COL_UPLOADED_AT => 15, ImageTableMap::COL_CREATED_AT => 16, ImageTableMap::COL_UPDATED_AT => 17, ],
        self::TYPE_FIELDNAME     => ['id' => 0, 'type' => 1, 'filePath' => 2, 'fileName' => 3, 'version' => 4, 'mediaType' => 5, 'fileSize' => 6, 'height' => 7, 'width' => 8, 'article_id' => 9, 'stock_item_id' => 10, 'contributor_id' => 11, 'post_id' => 12, 'event_id' => 13, 'publisher_id' => 14, 'uploaded_at' => 15, 'created_at' => 16, 'updated_at' => 17, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'ID',
        'Image.Id' => 'ID',
        'id' => 'ID',
        'image.id' => 'ID',
        'ImageTableMap::COL_ID' => 'ID',
        'COL_ID' => 'ID',
        'images.id' => 'ID',
        'Type' => 'TYPE',
        'Image.Type' => 'TYPE',
        'type' => 'TYPE',
        'image.type' => 'TYPE',
        'ImageTableMap::COL_TYPE' => 'TYPE',
        'COL_TYPE' => 'TYPE',
        'images.type' => 'TYPE',
        'Filepath' => 'FILEPATH',
        'Image.Filepath' => 'FILEPATH',
        'filepath' => 'FILEPATH',
        'image.filepath' => 'FILEPATH',
        'ImageTableMap::COL_FILEPATH' => 'FILEPATH',
        'COL_FILEPATH' => 'FILEPATH',
        'filePath' => 'FILEPATH',
        'images.filePath' => 'FILEPATH',
        'Filename' => 'FILENAME',
        'Image.Filename' => 'FILENAME',
        'filename' => 'FILENAME',
        'image.filename' => 'FILENAME',
        'ImageTableMap::COL_FILENAME' => 'FILENAME',
        'COL_FILENAME' => 'FILENAME',
        'fileName' => 'FILENAME',
        'images.fileName' => 'FILENAME',
        'Version' => 'VERSION',
        'Image.Version' => 'VERSION',
        'version' => 'VERSION',
        'image.version' => 'VERSION',
        'ImageTableMap::COL_VERSION' => 'VERSION',
        'COL_VERSION' => 'VERSION',
        'images.version' => 'VERSION',
        'Mediatype' => 'MEDIATYPE',
        'Image.Mediatype' => 'MEDIATYPE',
        'mediatype' => 'MEDIATYPE',
        'image.mediatype' => 'MEDIATYPE',
        'ImageTableMap::COL_MEDIATYPE' => 'MEDIATYPE',
        'COL_MEDIATYPE' => 'MEDIATYPE',
        'mediaType' => 'MEDIATYPE',
        'images.mediaType' => 'MEDIATYPE',
        'Filesize' => 'FILESIZE',
        'Image.Filesize' => 'FILESIZE',
        'filesize' => 'FILESIZE',
        'image.filesize' => 'FILESIZE',
        'ImageTableMap::COL_FILESIZE' => 'FILESIZE',
        'COL_FILESIZE' => 'FILESIZE',
        'fileSize' => 'FILESIZE',
        'images.fileSize' => 'FILESIZE',
        'Height' => 'HEIGHT',
        'Image.Height' => 'HEIGHT',
        'height' => 'HEIGHT',
        'image.height' => 'HEIGHT',
        'ImageTableMap::COL_HEIGHT' => 'HEIGHT',
        'COL_HEIGHT' => 'HEIGHT',
        'images.height' => 'HEIGHT',
        'Width' => 'WIDTH',
        'Image.Width' => 'WIDTH',
        'width' => 'WIDTH',
        'image.width' => 'WIDTH',
        'ImageTableMap::COL_WIDTH' => 'WIDTH',
        'COL_WIDTH' => 'WIDTH',
        'images.width' => 'WIDTH',
        'ArticleId' => 'ARTICLE_ID',
        'Image.ArticleId' => 'ARTICLE_ID',
        'articleId' => 'ARTICLE_ID',
        'image.articleId' => 'ARTICLE_ID',
        'ImageTableMap::COL_ARTICLE_ID' => 'ARTICLE_ID',
        'COL_ARTICLE_ID' => 'ARTICLE_ID',
        'article_id' => 'ARTICLE_ID',
        'images.article_id' => 'ARTICLE_ID',
        'StockItemId' => 'STOCK_ITEM_ID',
        'Image.StockItemId' => 'STOCK_ITEM_ID',
        'stockItemId' => 'STOCK_ITEM_ID',
        'image.stockItemId' => 'STOCK_ITEM_ID',
        'ImageTableMap::COL_STOCK_ITEM_ID' => 'STOCK_ITEM_ID',
        'COL_STOCK_ITEM_ID' => 'STOCK_ITEM_ID',
        'stock_item_id' => 'STOCK_ITEM_ID',
        'images.stock_item_id' => 'STOCK_ITEM_ID',
        'ContributorId' => 'CONTRIBUTOR_ID',
        'Image.ContributorId' => 'CONTRIBUTOR_ID',
        'contributorId' => 'CONTRIBUTOR_ID',
        'image.contributorId' => 'CONTRIBUTOR_ID',
        'ImageTableMap::COL_CONTRIBUTOR_ID' => 'CONTRIBUTOR_ID',
        'COL_CONTRIBUTOR_ID' => 'CONTRIBUTOR_ID',
        'contributor_id' => 'CONTRIBUTOR_ID',
        'images.contributor_id' => 'CONTRIBUTOR_ID',
        'PostId' => 'POST_ID',
        'Image.PostId' => 'POST_ID',
        'postId' => 'POST_ID',
        'image.postId' => 'POST_ID',
        'ImageTableMap::COL_POST_ID' => 'POST_ID',
        'COL_POST_ID' => 'POST_ID',
        'post_id' => 'POST_ID',
        'images.post_id' => 'POST_ID',
        'EventId' => 'EVENT_ID',
        'Image.EventId' => 'EVENT_ID',
        'eventId' => 'EVENT_ID',
        'image.eventId' => 'EVENT_ID',
        'ImageTableMap::COL_EVENT_ID' => 'EVENT_ID',
        'COL_EVENT_ID' => 'EVENT_ID',
        'event_id' => 'EVENT_ID',
        'images.event_id' => 'EVENT_ID',
        'PublisherId' => 'PUBLISHER_ID',
        'Image.PublisherId' => 'PUBLISHER_ID',
        'publisherId' => 'PUBLISHER_ID',
        'image.publisherId' => 'PUBLISHER_ID',
        'ImageTableMap::COL_PUBLISHER_ID' => 'PUBLISHER_ID',
        'COL_PUBLISHER_ID' => 'PUBLISHER_ID',
        'publisher_id' => 'PUBLISHER_ID',
        'images.publisher_id' => 'PUBLISHER_ID',
        'uploaded_at' => 'UPLOADED_AT',
        'Image.uploaded_at' => 'UPLOADED_AT',
        'image.uploaded_at' => 'UPLOADED_AT',
        'ImageTableMap::COL_UPLOADED_AT' => 'UPLOADED_AT',
        'COL_UPLOADED_AT' => 'UPLOADED_AT',
        'images.uploaded_at' => 'UPLOADED_AT',
        'CreatedAt' => 'CREATED_AT',
        'Image.CreatedAt' => 'CREATED_AT',
        'createdAt' => 'CREATED_AT',
        'image.createdAt' => 'CREATED_AT',
        'ImageTableMap::COL_CREATED_AT' => 'CREATED_AT',
        'COL_CREATED_AT' => 'CREATED_AT',
        'created_at' => 'CREATED_AT',
        'images.created_at' => 'CREATED_AT',
        'UpdatedAt' => 'UPDATED_AT',
        'Image.UpdatedAt' => 'UPDATED_AT',
        'updatedAt' => 'UPDATED_AT',
        'image.updatedAt' => 'UPDATED_AT',
        'ImageTableMap::COL_UPDATED_AT' => 'UPDATED_AT',
        'COL_UPDATED_AT' => 'UPDATED_AT',
        'updated_at' => 'UPDATED_AT',
        'images.updated_at' => 'UPDATED_AT',
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
        $this->setName('images');
        $this->setPhpName('Image');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Image');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('type', 'Type', 'VARCHAR', false, 16, null);
        $this->addColumn('filePath', 'Filepath', 'VARCHAR', false, 256, null);
        $this->addColumn('fileName', 'Filename', 'VARCHAR', false, 256, null);
        $this->addColumn('version', 'Version', 'INTEGER', false, null, null);
        $this->addColumn('mediaType', 'Mediatype', 'VARCHAR', false, 256, null);
        $this->addColumn('fileSize', 'Filesize', 'BIGINT', false, null, null);
        $this->addColumn('height', 'Height', 'INTEGER', false, null, null);
        $this->addColumn('width', 'Width', 'INTEGER', false, null, null);
        $this->addForeignKey('article_id', 'ArticleId', 'INTEGER', 'articles', 'article_id', false, null, null);
        $this->addForeignKey('stock_item_id', 'StockItemId', 'INTEGER', 'stock', 'stock_id', false, null, null);
        $this->addForeignKey('contributor_id', 'ContributorId', 'INTEGER', 'people', 'people_id', false, null, null);
        $this->addForeignKey('post_id', 'PostId', 'INTEGER', 'posts', 'post_id', false, null, null);
        $this->addForeignKey('event_id', 'EventId', 'INTEGER', 'events', 'event_id', false, null, null);
        $this->addForeignKey('publisher_id', 'PublisherId', 'INTEGER', 'publishers', 'publisher_id', false, null, null);
        $this->addColumn('uploaded_at', 'uploaded_at', 'TIMESTAMP', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
        $this->addRelation('Article', '\\Model\\Article', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':article_id',
    1 => ':article_id',
  ),
), null, null, null, false);
        $this->addRelation('StockItem', '\\Model\\Stock', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':stock_item_id',
    1 => ':stock_id',
  ),
), null, null, null, false);
        $this->addRelation('Contributor', '\\Model\\People', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':contributor_id',
    1 => ':people_id',
  ),
), null, null, null, false);
        $this->addRelation('Post', '\\Model\\Post', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':post_id',
    1 => ':post_id',
  ),
), null, null, null, false);
        $this->addRelation('Event', '\\Model\\Event', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':event_id',
    1 => ':event_id',
  ),
), null, null, null, false);
        $this->addRelation('Publisher', '\\Model\\Publisher', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':publisher_id',
    1 => ':publisher_id',
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
        return $withPrefix ? ImageTableMap::CLASS_DEFAULT : ImageTableMap::OM_CLASS;
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
     * @return array (Image object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = ImageTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = ImageTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + ImageTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ImageTableMap::OM_CLASS;
            /** @var Image $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            ImageTableMap::addInstanceToPool($obj, $key);
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
            $key = ImageTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = ImageTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Image $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ImageTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(ImageTableMap::COL_ID);
            $criteria->addSelectColumn(ImageTableMap::COL_TYPE);
            $criteria->addSelectColumn(ImageTableMap::COL_FILEPATH);
            $criteria->addSelectColumn(ImageTableMap::COL_FILENAME);
            $criteria->addSelectColumn(ImageTableMap::COL_VERSION);
            $criteria->addSelectColumn(ImageTableMap::COL_MEDIATYPE);
            $criteria->addSelectColumn(ImageTableMap::COL_FILESIZE);
            $criteria->addSelectColumn(ImageTableMap::COL_HEIGHT);
            $criteria->addSelectColumn(ImageTableMap::COL_WIDTH);
            $criteria->addSelectColumn(ImageTableMap::COL_ARTICLE_ID);
            $criteria->addSelectColumn(ImageTableMap::COL_STOCK_ITEM_ID);
            $criteria->addSelectColumn(ImageTableMap::COL_CONTRIBUTOR_ID);
            $criteria->addSelectColumn(ImageTableMap::COL_POST_ID);
            $criteria->addSelectColumn(ImageTableMap::COL_EVENT_ID);
            $criteria->addSelectColumn(ImageTableMap::COL_PUBLISHER_ID);
            $criteria->addSelectColumn(ImageTableMap::COL_UPLOADED_AT);
            $criteria->addSelectColumn(ImageTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(ImageTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.type');
            $criteria->addSelectColumn($alias . '.filePath');
            $criteria->addSelectColumn($alias . '.fileName');
            $criteria->addSelectColumn($alias . '.version');
            $criteria->addSelectColumn($alias . '.mediaType');
            $criteria->addSelectColumn($alias . '.fileSize');
            $criteria->addSelectColumn($alias . '.height');
            $criteria->addSelectColumn($alias . '.width');
            $criteria->addSelectColumn($alias . '.article_id');
            $criteria->addSelectColumn($alias . '.stock_item_id');
            $criteria->addSelectColumn($alias . '.contributor_id');
            $criteria->addSelectColumn($alias . '.post_id');
            $criteria->addSelectColumn($alias . '.event_id');
            $criteria->addSelectColumn($alias . '.publisher_id');
            $criteria->addSelectColumn($alias . '.uploaded_at');
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
            $criteria->removeSelectColumn(ImageTableMap::COL_ID);
            $criteria->removeSelectColumn(ImageTableMap::COL_TYPE);
            $criteria->removeSelectColumn(ImageTableMap::COL_FILEPATH);
            $criteria->removeSelectColumn(ImageTableMap::COL_FILENAME);
            $criteria->removeSelectColumn(ImageTableMap::COL_VERSION);
            $criteria->removeSelectColumn(ImageTableMap::COL_MEDIATYPE);
            $criteria->removeSelectColumn(ImageTableMap::COL_FILESIZE);
            $criteria->removeSelectColumn(ImageTableMap::COL_HEIGHT);
            $criteria->removeSelectColumn(ImageTableMap::COL_WIDTH);
            $criteria->removeSelectColumn(ImageTableMap::COL_ARTICLE_ID);
            $criteria->removeSelectColumn(ImageTableMap::COL_STOCK_ITEM_ID);
            $criteria->removeSelectColumn(ImageTableMap::COL_CONTRIBUTOR_ID);
            $criteria->removeSelectColumn(ImageTableMap::COL_POST_ID);
            $criteria->removeSelectColumn(ImageTableMap::COL_EVENT_ID);
            $criteria->removeSelectColumn(ImageTableMap::COL_PUBLISHER_ID);
            $criteria->removeSelectColumn(ImageTableMap::COL_UPLOADED_AT);
            $criteria->removeSelectColumn(ImageTableMap::COL_CREATED_AT);
            $criteria->removeSelectColumn(ImageTableMap::COL_UPDATED_AT);
        } else {
            $criteria->removeSelectColumn($alias . '.id');
            $criteria->removeSelectColumn($alias . '.type');
            $criteria->removeSelectColumn($alias . '.filePath');
            $criteria->removeSelectColumn($alias . '.fileName');
            $criteria->removeSelectColumn($alias . '.version');
            $criteria->removeSelectColumn($alias . '.mediaType');
            $criteria->removeSelectColumn($alias . '.fileSize');
            $criteria->removeSelectColumn($alias . '.height');
            $criteria->removeSelectColumn($alias . '.width');
            $criteria->removeSelectColumn($alias . '.article_id');
            $criteria->removeSelectColumn($alias . '.stock_item_id');
            $criteria->removeSelectColumn($alias . '.contributor_id');
            $criteria->removeSelectColumn($alias . '.post_id');
            $criteria->removeSelectColumn($alias . '.event_id');
            $criteria->removeSelectColumn($alias . '.publisher_id');
            $criteria->removeSelectColumn($alias . '.uploaded_at');
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
        return Propel::getServiceContainer()->getDatabaseMap(ImageTableMap::DATABASE_NAME)->getTable(ImageTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Image or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Image object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(ImageTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Image) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ImageTableMap::DATABASE_NAME);
            $criteria->add(ImageTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = ImageQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            ImageTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                ImageTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the images table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return ImageQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Image or Criteria object.
     *
     * @param mixed $criteria Criteria or Image object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ImageTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Image object
        }

        if ($criteria->containsKey(ImageTableMap::COL_ID) && $criteria->keyContainsValue(ImageTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.ImageTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = ImageQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
