<?php

namespace Model\Map;

use Model\File;
use Model\FileQuery;
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
 * This class defines the structure of the 'files' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class FileTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.FileTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'files';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'File';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\File';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.File';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 16;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 16;

    /**
     * the column name for the file_id field
     */
    public const COL_FILE_ID = 'files.file_id';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'files.site_id';

    /**
     * the column name for the article_id field
     */
    public const COL_ARTICLE_ID = 'files.article_id';

    /**
     * the column name for the axys_account_id field
     */
    public const COL_AXYS_ACCOUNT_ID = 'files.axys_account_id';

    /**
     * the column name for the user_id field
     */
    public const COL_USER_ID = 'files.user_id';

    /**
     * the column name for the file_title field
     */
    public const COL_FILE_TITLE = 'files.file_title';

    /**
     * the column name for the file_type field
     */
    public const COL_FILE_TYPE = 'files.file_type';

    /**
     * the column name for the file_access field
     */
    public const COL_FILE_ACCESS = 'files.file_access';

    /**
     * the column name for the file_version field
     */
    public const COL_FILE_VERSION = 'files.file_version';

    /**
     * the column name for the file_hash field
     */
    public const COL_FILE_HASH = 'files.file_hash';

    /**
     * the column name for the file_size field
     */
    public const COL_FILE_SIZE = 'files.file_size';

    /**
     * the column name for the file_ean field
     */
    public const COL_FILE_EAN = 'files.file_ean';

    /**
     * the column name for the file_inserted field
     */
    public const COL_FILE_INSERTED = 'files.file_inserted';

    /**
     * the column name for the file_uploaded field
     */
    public const COL_FILE_UPLOADED = 'files.file_uploaded';

    /**
     * the column name for the file_updated field
     */
    public const COL_FILE_UPDATED = 'files.file_updated';

    /**
     * the column name for the file_created field
     */
    public const COL_FILE_CREATED = 'files.file_created';

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
        self::TYPE_PHPNAME       => ['Id', 'SiteId', 'ArticleId', 'AxysAccountId', 'UserId', 'Title', 'Type', 'Access', 'Version', 'Hash', 'Size', 'Ean', 'Inserted', 'Uploaded', 'UpdatedAt', 'CreatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'siteId', 'articleId', 'axysAccountId', 'userId', 'title', 'type', 'access', 'version', 'hash', 'size', 'ean', 'inserted', 'uploaded', 'updatedAt', 'createdAt', ],
        self::TYPE_COLNAME       => [FileTableMap::COL_FILE_ID, FileTableMap::COL_SITE_ID, FileTableMap::COL_ARTICLE_ID, FileTableMap::COL_AXYS_ACCOUNT_ID, FileTableMap::COL_USER_ID, FileTableMap::COL_FILE_TITLE, FileTableMap::COL_FILE_TYPE, FileTableMap::COL_FILE_ACCESS, FileTableMap::COL_FILE_VERSION, FileTableMap::COL_FILE_HASH, FileTableMap::COL_FILE_SIZE, FileTableMap::COL_FILE_EAN, FileTableMap::COL_FILE_INSERTED, FileTableMap::COL_FILE_UPLOADED, FileTableMap::COL_FILE_UPDATED, FileTableMap::COL_FILE_CREATED, ],
        self::TYPE_FIELDNAME     => ['file_id', 'site_id', 'article_id', 'axys_account_id', 'user_id', 'file_title', 'file_type', 'file_access', 'file_version', 'file_hash', 'file_size', 'file_ean', 'file_inserted', 'file_uploaded', 'file_updated', 'file_created', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'SiteId' => 1, 'ArticleId' => 2, 'AxysAccountId' => 3, 'UserId' => 4, 'Title' => 5, 'Type' => 6, 'Access' => 7, 'Version' => 8, 'Hash' => 9, 'Size' => 10, 'Ean' => 11, 'Inserted' => 12, 'Uploaded' => 13, 'UpdatedAt' => 14, 'CreatedAt' => 15, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'siteId' => 1, 'articleId' => 2, 'axysAccountId' => 3, 'userId' => 4, 'title' => 5, 'type' => 6, 'access' => 7, 'version' => 8, 'hash' => 9, 'size' => 10, 'ean' => 11, 'inserted' => 12, 'uploaded' => 13, 'updatedAt' => 14, 'createdAt' => 15, ],
        self::TYPE_COLNAME       => [FileTableMap::COL_FILE_ID => 0, FileTableMap::COL_SITE_ID => 1, FileTableMap::COL_ARTICLE_ID => 2, FileTableMap::COL_AXYS_ACCOUNT_ID => 3, FileTableMap::COL_USER_ID => 4, FileTableMap::COL_FILE_TITLE => 5, FileTableMap::COL_FILE_TYPE => 6, FileTableMap::COL_FILE_ACCESS => 7, FileTableMap::COL_FILE_VERSION => 8, FileTableMap::COL_FILE_HASH => 9, FileTableMap::COL_FILE_SIZE => 10, FileTableMap::COL_FILE_EAN => 11, FileTableMap::COL_FILE_INSERTED => 12, FileTableMap::COL_FILE_UPLOADED => 13, FileTableMap::COL_FILE_UPDATED => 14, FileTableMap::COL_FILE_CREATED => 15, ],
        self::TYPE_FIELDNAME     => ['file_id' => 0, 'site_id' => 1, 'article_id' => 2, 'axys_account_id' => 3, 'user_id' => 4, 'file_title' => 5, 'file_type' => 6, 'file_access' => 7, 'file_version' => 8, 'file_hash' => 9, 'file_size' => 10, 'file_ean' => 11, 'file_inserted' => 12, 'file_uploaded' => 13, 'file_updated' => 14, 'file_created' => 15, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'FILE_ID',
        'File.Id' => 'FILE_ID',
        'id' => 'FILE_ID',
        'file.id' => 'FILE_ID',
        'FileTableMap::COL_FILE_ID' => 'FILE_ID',
        'COL_FILE_ID' => 'FILE_ID',
        'file_id' => 'FILE_ID',
        'files.file_id' => 'FILE_ID',
        'SiteId' => 'SITE_ID',
        'File.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'file.siteId' => 'SITE_ID',
        'FileTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'files.site_id' => 'SITE_ID',
        'ArticleId' => 'ARTICLE_ID',
        'File.ArticleId' => 'ARTICLE_ID',
        'articleId' => 'ARTICLE_ID',
        'file.articleId' => 'ARTICLE_ID',
        'FileTableMap::COL_ARTICLE_ID' => 'ARTICLE_ID',
        'COL_ARTICLE_ID' => 'ARTICLE_ID',
        'article_id' => 'ARTICLE_ID',
        'files.article_id' => 'ARTICLE_ID',
        'AxysAccountId' => 'AXYS_ACCOUNT_ID',
        'File.AxysAccountId' => 'AXYS_ACCOUNT_ID',
        'axysAccountId' => 'AXYS_ACCOUNT_ID',
        'file.axysAccountId' => 'AXYS_ACCOUNT_ID',
        'FileTableMap::COL_AXYS_ACCOUNT_ID' => 'AXYS_ACCOUNT_ID',
        'COL_AXYS_ACCOUNT_ID' => 'AXYS_ACCOUNT_ID',
        'axys_account_id' => 'AXYS_ACCOUNT_ID',
        'files.axys_account_id' => 'AXYS_ACCOUNT_ID',
        'UserId' => 'USER_ID',
        'File.UserId' => 'USER_ID',
        'userId' => 'USER_ID',
        'file.userId' => 'USER_ID',
        'FileTableMap::COL_USER_ID' => 'USER_ID',
        'COL_USER_ID' => 'USER_ID',
        'user_id' => 'USER_ID',
        'files.user_id' => 'USER_ID',
        'Title' => 'FILE_TITLE',
        'File.Title' => 'FILE_TITLE',
        'title' => 'FILE_TITLE',
        'file.title' => 'FILE_TITLE',
        'FileTableMap::COL_FILE_TITLE' => 'FILE_TITLE',
        'COL_FILE_TITLE' => 'FILE_TITLE',
        'file_title' => 'FILE_TITLE',
        'files.file_title' => 'FILE_TITLE',
        'Type' => 'FILE_TYPE',
        'File.Type' => 'FILE_TYPE',
        'type' => 'FILE_TYPE',
        'file.type' => 'FILE_TYPE',
        'FileTableMap::COL_FILE_TYPE' => 'FILE_TYPE',
        'COL_FILE_TYPE' => 'FILE_TYPE',
        'file_type' => 'FILE_TYPE',
        'files.file_type' => 'FILE_TYPE',
        'Access' => 'FILE_ACCESS',
        'File.Access' => 'FILE_ACCESS',
        'access' => 'FILE_ACCESS',
        'file.access' => 'FILE_ACCESS',
        'FileTableMap::COL_FILE_ACCESS' => 'FILE_ACCESS',
        'COL_FILE_ACCESS' => 'FILE_ACCESS',
        'file_access' => 'FILE_ACCESS',
        'files.file_access' => 'FILE_ACCESS',
        'Version' => 'FILE_VERSION',
        'File.Version' => 'FILE_VERSION',
        'version' => 'FILE_VERSION',
        'file.version' => 'FILE_VERSION',
        'FileTableMap::COL_FILE_VERSION' => 'FILE_VERSION',
        'COL_FILE_VERSION' => 'FILE_VERSION',
        'file_version' => 'FILE_VERSION',
        'files.file_version' => 'FILE_VERSION',
        'Hash' => 'FILE_HASH',
        'File.Hash' => 'FILE_HASH',
        'hash' => 'FILE_HASH',
        'file.hash' => 'FILE_HASH',
        'FileTableMap::COL_FILE_HASH' => 'FILE_HASH',
        'COL_FILE_HASH' => 'FILE_HASH',
        'file_hash' => 'FILE_HASH',
        'files.file_hash' => 'FILE_HASH',
        'Size' => 'FILE_SIZE',
        'File.Size' => 'FILE_SIZE',
        'size' => 'FILE_SIZE',
        'file.size' => 'FILE_SIZE',
        'FileTableMap::COL_FILE_SIZE' => 'FILE_SIZE',
        'COL_FILE_SIZE' => 'FILE_SIZE',
        'file_size' => 'FILE_SIZE',
        'files.file_size' => 'FILE_SIZE',
        'Ean' => 'FILE_EAN',
        'File.Ean' => 'FILE_EAN',
        'ean' => 'FILE_EAN',
        'file.ean' => 'FILE_EAN',
        'FileTableMap::COL_FILE_EAN' => 'FILE_EAN',
        'COL_FILE_EAN' => 'FILE_EAN',
        'file_ean' => 'FILE_EAN',
        'files.file_ean' => 'FILE_EAN',
        'Inserted' => 'FILE_INSERTED',
        'File.Inserted' => 'FILE_INSERTED',
        'inserted' => 'FILE_INSERTED',
        'file.inserted' => 'FILE_INSERTED',
        'FileTableMap::COL_FILE_INSERTED' => 'FILE_INSERTED',
        'COL_FILE_INSERTED' => 'FILE_INSERTED',
        'file_inserted' => 'FILE_INSERTED',
        'files.file_inserted' => 'FILE_INSERTED',
        'Uploaded' => 'FILE_UPLOADED',
        'File.Uploaded' => 'FILE_UPLOADED',
        'uploaded' => 'FILE_UPLOADED',
        'file.uploaded' => 'FILE_UPLOADED',
        'FileTableMap::COL_FILE_UPLOADED' => 'FILE_UPLOADED',
        'COL_FILE_UPLOADED' => 'FILE_UPLOADED',
        'file_uploaded' => 'FILE_UPLOADED',
        'files.file_uploaded' => 'FILE_UPLOADED',
        'UpdatedAt' => 'FILE_UPDATED',
        'File.UpdatedAt' => 'FILE_UPDATED',
        'updatedAt' => 'FILE_UPDATED',
        'file.updatedAt' => 'FILE_UPDATED',
        'FileTableMap::COL_FILE_UPDATED' => 'FILE_UPDATED',
        'COL_FILE_UPDATED' => 'FILE_UPDATED',
        'file_updated' => 'FILE_UPDATED',
        'files.file_updated' => 'FILE_UPDATED',
        'CreatedAt' => 'FILE_CREATED',
        'File.CreatedAt' => 'FILE_CREATED',
        'createdAt' => 'FILE_CREATED',
        'file.createdAt' => 'FILE_CREATED',
        'FileTableMap::COL_FILE_CREATED' => 'FILE_CREATED',
        'COL_FILE_CREATED' => 'FILE_CREATED',
        'file_created' => 'FILE_CREATED',
        'files.file_created' => 'FILE_CREATED',
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
        $this->setName('files');
        $this->setPhpName('File');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\File');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('file_id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('site_id', 'SiteId', 'INTEGER', 'sites', 'site_id', false, null, null);
        $this->addForeignKey('article_id', 'ArticleId', 'INTEGER', 'articles', 'article_id', false, null, null);
        $this->addColumn('axys_account_id', 'AxysAccountId', 'INTEGER', false, null, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'users', 'id', false, null, null);
        $this->addColumn('file_title', 'Title', 'VARCHAR', false, 32, null);
        $this->addColumn('file_type', 'Type', 'VARCHAR', false, 32, null);
        $this->addColumn('file_access', 'Access', 'BOOLEAN', false, 1, true);
        $this->addColumn('file_version', 'Version', 'VARCHAR', false, 8, '1.0');
        $this->addColumn('file_hash', 'Hash', 'VARCHAR', false, 32, null);
        $this->addColumn('file_size', 'Size', 'BIGINT', false, null, 0);
        $this->addColumn('file_ean', 'Ean', 'BIGINT', false, 13, null);
        $this->addColumn('file_inserted', 'Inserted', 'TIMESTAMP', false, null, null);
        $this->addColumn('file_uploaded', 'Uploaded', 'TIMESTAMP', false, null, null);
        $this->addColumn('file_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('file_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
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
        $this->addRelation('User', '\\Model\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Download', '\\Model\\Download', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':file_id',
    1 => ':file_id',
  ),
), null, null, 'Downloads', false);
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
            'timestampable' => ['create_column' => 'file_created', 'update_column' => 'file_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? FileTableMap::CLASS_DEFAULT : FileTableMap::OM_CLASS;
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
     * @return array (File object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = FileTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = FileTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + FileTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = FileTableMap::OM_CLASS;
            /** @var File $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            FileTableMap::addInstanceToPool($obj, $key);
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
            $key = FileTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = FileTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var File $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                FileTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(FileTableMap::COL_FILE_ID);
            $criteria->addSelectColumn(FileTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(FileTableMap::COL_ARTICLE_ID);
            $criteria->addSelectColumn(FileTableMap::COL_AXYS_ACCOUNT_ID);
            $criteria->addSelectColumn(FileTableMap::COL_USER_ID);
            $criteria->addSelectColumn(FileTableMap::COL_FILE_TITLE);
            $criteria->addSelectColumn(FileTableMap::COL_FILE_TYPE);
            $criteria->addSelectColumn(FileTableMap::COL_FILE_ACCESS);
            $criteria->addSelectColumn(FileTableMap::COL_FILE_VERSION);
            $criteria->addSelectColumn(FileTableMap::COL_FILE_HASH);
            $criteria->addSelectColumn(FileTableMap::COL_FILE_SIZE);
            $criteria->addSelectColumn(FileTableMap::COL_FILE_EAN);
            $criteria->addSelectColumn(FileTableMap::COL_FILE_INSERTED);
            $criteria->addSelectColumn(FileTableMap::COL_FILE_UPLOADED);
            $criteria->addSelectColumn(FileTableMap::COL_FILE_UPDATED);
            $criteria->addSelectColumn(FileTableMap::COL_FILE_CREATED);
        } else {
            $criteria->addSelectColumn($alias . '.file_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.article_id');
            $criteria->addSelectColumn($alias . '.axys_account_id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.file_title');
            $criteria->addSelectColumn($alias . '.file_type');
            $criteria->addSelectColumn($alias . '.file_access');
            $criteria->addSelectColumn($alias . '.file_version');
            $criteria->addSelectColumn($alias . '.file_hash');
            $criteria->addSelectColumn($alias . '.file_size');
            $criteria->addSelectColumn($alias . '.file_ean');
            $criteria->addSelectColumn($alias . '.file_inserted');
            $criteria->addSelectColumn($alias . '.file_uploaded');
            $criteria->addSelectColumn($alias . '.file_updated');
            $criteria->addSelectColumn($alias . '.file_created');
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
            $criteria->removeSelectColumn(FileTableMap::COL_FILE_ID);
            $criteria->removeSelectColumn(FileTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(FileTableMap::COL_ARTICLE_ID);
            $criteria->removeSelectColumn(FileTableMap::COL_AXYS_ACCOUNT_ID);
            $criteria->removeSelectColumn(FileTableMap::COL_USER_ID);
            $criteria->removeSelectColumn(FileTableMap::COL_FILE_TITLE);
            $criteria->removeSelectColumn(FileTableMap::COL_FILE_TYPE);
            $criteria->removeSelectColumn(FileTableMap::COL_FILE_ACCESS);
            $criteria->removeSelectColumn(FileTableMap::COL_FILE_VERSION);
            $criteria->removeSelectColumn(FileTableMap::COL_FILE_HASH);
            $criteria->removeSelectColumn(FileTableMap::COL_FILE_SIZE);
            $criteria->removeSelectColumn(FileTableMap::COL_FILE_EAN);
            $criteria->removeSelectColumn(FileTableMap::COL_FILE_INSERTED);
            $criteria->removeSelectColumn(FileTableMap::COL_FILE_UPLOADED);
            $criteria->removeSelectColumn(FileTableMap::COL_FILE_UPDATED);
            $criteria->removeSelectColumn(FileTableMap::COL_FILE_CREATED);
        } else {
            $criteria->removeSelectColumn($alias . '.file_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.article_id');
            $criteria->removeSelectColumn($alias . '.axys_account_id');
            $criteria->removeSelectColumn($alias . '.user_id');
            $criteria->removeSelectColumn($alias . '.file_title');
            $criteria->removeSelectColumn($alias . '.file_type');
            $criteria->removeSelectColumn($alias . '.file_access');
            $criteria->removeSelectColumn($alias . '.file_version');
            $criteria->removeSelectColumn($alias . '.file_hash');
            $criteria->removeSelectColumn($alias . '.file_size');
            $criteria->removeSelectColumn($alias . '.file_ean');
            $criteria->removeSelectColumn($alias . '.file_inserted');
            $criteria->removeSelectColumn($alias . '.file_uploaded');
            $criteria->removeSelectColumn($alias . '.file_updated');
            $criteria->removeSelectColumn($alias . '.file_created');
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
        return Propel::getServiceContainer()->getDatabaseMap(FileTableMap::DATABASE_NAME)->getTable(FileTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a File or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or File object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(FileTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\File) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(FileTableMap::DATABASE_NAME);
            $criteria->add(FileTableMap::COL_FILE_ID, (array) $values, Criteria::IN);
        }

        $query = FileQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            FileTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                FileTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the files table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return FileQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a File or Criteria object.
     *
     * @param mixed $criteria Criteria or File object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FileTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from File object
        }

        if ($criteria->containsKey(FileTableMap::COL_FILE_ID) && $criteria->keyContainsValue(FileTableMap::COL_FILE_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.FileTableMap::COL_FILE_ID.')');
        }


        // Set the correct dbName
        $query = FileQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
