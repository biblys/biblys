<?php

namespace Model\Map;

use Model\Download;
use Model\DownloadQuery;
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
 * This class defines the structure of the 'downloads' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class DownloadTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.DownloadTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'downloads';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Download';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Download';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Download';

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
     * the column name for the download_id field
     */
    public const COL_DOWNLOAD_ID = 'downloads.download_id';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'downloads.site_id';

    /**
     * the column name for the file_id field
     */
    public const COL_FILE_ID = 'downloads.file_id';

    /**
     * the column name for the article_id field
     */
    public const COL_ARTICLE_ID = 'downloads.article_id';

    /**
     * the column name for the book_id field
     */
    public const COL_BOOK_ID = 'downloads.book_id';

    /**
     * the column name for the axys_account_id field
     */
    public const COL_AXYS_ACCOUNT_ID = 'downloads.axys_account_id';

    /**
     * the column name for the user_id field
     */
    public const COL_USER_ID = 'downloads.user_id';

    /**
     * the column name for the download_filetype field
     */
    public const COL_DOWNLOAD_FILETYPE = 'downloads.download_filetype';

    /**
     * the column name for the download_version field
     */
    public const COL_DOWNLOAD_VERSION = 'downloads.download_version';

    /**
     * the column name for the download_ip field
     */
    public const COL_DOWNLOAD_IP = 'downloads.download_ip';

    /**
     * the column name for the download_date field
     */
    public const COL_DOWNLOAD_DATE = 'downloads.download_date';

    /**
     * the column name for the download_created field
     */
    public const COL_DOWNLOAD_CREATED = 'downloads.download_created';

    /**
     * the column name for the download_updated field
     */
    public const COL_DOWNLOAD_UPDATED = 'downloads.download_updated';

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
        self::TYPE_PHPNAME       => ['Id', 'SiteId', 'FileId', 'ArticleId', 'BookId', 'AxysAccountId', 'UserId', 'Filetype', 'Version', 'Ip', 'Date', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'siteId', 'fileId', 'articleId', 'bookId', 'axysAccountId', 'userId', 'filetype', 'version', 'ip', 'date', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [DownloadTableMap::COL_DOWNLOAD_ID, DownloadTableMap::COL_SITE_ID, DownloadTableMap::COL_FILE_ID, DownloadTableMap::COL_ARTICLE_ID, DownloadTableMap::COL_BOOK_ID, DownloadTableMap::COL_AXYS_ACCOUNT_ID, DownloadTableMap::COL_USER_ID, DownloadTableMap::COL_DOWNLOAD_FILETYPE, DownloadTableMap::COL_DOWNLOAD_VERSION, DownloadTableMap::COL_DOWNLOAD_IP, DownloadTableMap::COL_DOWNLOAD_DATE, DownloadTableMap::COL_DOWNLOAD_CREATED, DownloadTableMap::COL_DOWNLOAD_UPDATED, ],
        self::TYPE_FIELDNAME     => ['download_id', 'site_id', 'file_id', 'article_id', 'book_id', 'axys_account_id', 'user_id', 'download_filetype', 'download_version', 'download_ip', 'download_date', 'download_created', 'download_updated', ],
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'SiteId' => 1, 'FileId' => 2, 'ArticleId' => 3, 'BookId' => 4, 'AxysAccountId' => 5, 'UserId' => 6, 'Filetype' => 7, 'Version' => 8, 'Ip' => 9, 'Date' => 10, 'CreatedAt' => 11, 'UpdatedAt' => 12, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'siteId' => 1, 'fileId' => 2, 'articleId' => 3, 'bookId' => 4, 'axysAccountId' => 5, 'userId' => 6, 'filetype' => 7, 'version' => 8, 'ip' => 9, 'date' => 10, 'createdAt' => 11, 'updatedAt' => 12, ],
        self::TYPE_COLNAME       => [DownloadTableMap::COL_DOWNLOAD_ID => 0, DownloadTableMap::COL_SITE_ID => 1, DownloadTableMap::COL_FILE_ID => 2, DownloadTableMap::COL_ARTICLE_ID => 3, DownloadTableMap::COL_BOOK_ID => 4, DownloadTableMap::COL_AXYS_ACCOUNT_ID => 5, DownloadTableMap::COL_USER_ID => 6, DownloadTableMap::COL_DOWNLOAD_FILETYPE => 7, DownloadTableMap::COL_DOWNLOAD_VERSION => 8, DownloadTableMap::COL_DOWNLOAD_IP => 9, DownloadTableMap::COL_DOWNLOAD_DATE => 10, DownloadTableMap::COL_DOWNLOAD_CREATED => 11, DownloadTableMap::COL_DOWNLOAD_UPDATED => 12, ],
        self::TYPE_FIELDNAME     => ['download_id' => 0, 'site_id' => 1, 'file_id' => 2, 'article_id' => 3, 'book_id' => 4, 'axys_account_id' => 5, 'user_id' => 6, 'download_filetype' => 7, 'download_version' => 8, 'download_ip' => 9, 'download_date' => 10, 'download_created' => 11, 'download_updated' => 12, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'DOWNLOAD_ID',
        'Download.Id' => 'DOWNLOAD_ID',
        'id' => 'DOWNLOAD_ID',
        'download.id' => 'DOWNLOAD_ID',
        'DownloadTableMap::COL_DOWNLOAD_ID' => 'DOWNLOAD_ID',
        'COL_DOWNLOAD_ID' => 'DOWNLOAD_ID',
        'download_id' => 'DOWNLOAD_ID',
        'downloads.download_id' => 'DOWNLOAD_ID',
        'SiteId' => 'SITE_ID',
        'Download.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'download.siteId' => 'SITE_ID',
        'DownloadTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'downloads.site_id' => 'SITE_ID',
        'FileId' => 'FILE_ID',
        'Download.FileId' => 'FILE_ID',
        'fileId' => 'FILE_ID',
        'download.fileId' => 'FILE_ID',
        'DownloadTableMap::COL_FILE_ID' => 'FILE_ID',
        'COL_FILE_ID' => 'FILE_ID',
        'file_id' => 'FILE_ID',
        'downloads.file_id' => 'FILE_ID',
        'ArticleId' => 'ARTICLE_ID',
        'Download.ArticleId' => 'ARTICLE_ID',
        'articleId' => 'ARTICLE_ID',
        'download.articleId' => 'ARTICLE_ID',
        'DownloadTableMap::COL_ARTICLE_ID' => 'ARTICLE_ID',
        'COL_ARTICLE_ID' => 'ARTICLE_ID',
        'article_id' => 'ARTICLE_ID',
        'downloads.article_id' => 'ARTICLE_ID',
        'BookId' => 'BOOK_ID',
        'Download.BookId' => 'BOOK_ID',
        'bookId' => 'BOOK_ID',
        'download.bookId' => 'BOOK_ID',
        'DownloadTableMap::COL_BOOK_ID' => 'BOOK_ID',
        'COL_BOOK_ID' => 'BOOK_ID',
        'book_id' => 'BOOK_ID',
        'downloads.book_id' => 'BOOK_ID',
        'AxysAccountId' => 'AXYS_ACCOUNT_ID',
        'Download.AxysAccountId' => 'AXYS_ACCOUNT_ID',
        'axysAccountId' => 'AXYS_ACCOUNT_ID',
        'download.axysAccountId' => 'AXYS_ACCOUNT_ID',
        'DownloadTableMap::COL_AXYS_ACCOUNT_ID' => 'AXYS_ACCOUNT_ID',
        'COL_AXYS_ACCOUNT_ID' => 'AXYS_ACCOUNT_ID',
        'axys_account_id' => 'AXYS_ACCOUNT_ID',
        'downloads.axys_account_id' => 'AXYS_ACCOUNT_ID',
        'UserId' => 'USER_ID',
        'Download.UserId' => 'USER_ID',
        'userId' => 'USER_ID',
        'download.userId' => 'USER_ID',
        'DownloadTableMap::COL_USER_ID' => 'USER_ID',
        'COL_USER_ID' => 'USER_ID',
        'user_id' => 'USER_ID',
        'downloads.user_id' => 'USER_ID',
        'Filetype' => 'DOWNLOAD_FILETYPE',
        'Download.Filetype' => 'DOWNLOAD_FILETYPE',
        'filetype' => 'DOWNLOAD_FILETYPE',
        'download.filetype' => 'DOWNLOAD_FILETYPE',
        'DownloadTableMap::COL_DOWNLOAD_FILETYPE' => 'DOWNLOAD_FILETYPE',
        'COL_DOWNLOAD_FILETYPE' => 'DOWNLOAD_FILETYPE',
        'download_filetype' => 'DOWNLOAD_FILETYPE',
        'downloads.download_filetype' => 'DOWNLOAD_FILETYPE',
        'Version' => 'DOWNLOAD_VERSION',
        'Download.Version' => 'DOWNLOAD_VERSION',
        'version' => 'DOWNLOAD_VERSION',
        'download.version' => 'DOWNLOAD_VERSION',
        'DownloadTableMap::COL_DOWNLOAD_VERSION' => 'DOWNLOAD_VERSION',
        'COL_DOWNLOAD_VERSION' => 'DOWNLOAD_VERSION',
        'download_version' => 'DOWNLOAD_VERSION',
        'downloads.download_version' => 'DOWNLOAD_VERSION',
        'Ip' => 'DOWNLOAD_IP',
        'Download.Ip' => 'DOWNLOAD_IP',
        'ip' => 'DOWNLOAD_IP',
        'download.ip' => 'DOWNLOAD_IP',
        'DownloadTableMap::COL_DOWNLOAD_IP' => 'DOWNLOAD_IP',
        'COL_DOWNLOAD_IP' => 'DOWNLOAD_IP',
        'download_ip' => 'DOWNLOAD_IP',
        'downloads.download_ip' => 'DOWNLOAD_IP',
        'Date' => 'DOWNLOAD_DATE',
        'Download.Date' => 'DOWNLOAD_DATE',
        'date' => 'DOWNLOAD_DATE',
        'download.date' => 'DOWNLOAD_DATE',
        'DownloadTableMap::COL_DOWNLOAD_DATE' => 'DOWNLOAD_DATE',
        'COL_DOWNLOAD_DATE' => 'DOWNLOAD_DATE',
        'download_date' => 'DOWNLOAD_DATE',
        'downloads.download_date' => 'DOWNLOAD_DATE',
        'CreatedAt' => 'DOWNLOAD_CREATED',
        'Download.CreatedAt' => 'DOWNLOAD_CREATED',
        'createdAt' => 'DOWNLOAD_CREATED',
        'download.createdAt' => 'DOWNLOAD_CREATED',
        'DownloadTableMap::COL_DOWNLOAD_CREATED' => 'DOWNLOAD_CREATED',
        'COL_DOWNLOAD_CREATED' => 'DOWNLOAD_CREATED',
        'download_created' => 'DOWNLOAD_CREATED',
        'downloads.download_created' => 'DOWNLOAD_CREATED',
        'UpdatedAt' => 'DOWNLOAD_UPDATED',
        'Download.UpdatedAt' => 'DOWNLOAD_UPDATED',
        'updatedAt' => 'DOWNLOAD_UPDATED',
        'download.updatedAt' => 'DOWNLOAD_UPDATED',
        'DownloadTableMap::COL_DOWNLOAD_UPDATED' => 'DOWNLOAD_UPDATED',
        'COL_DOWNLOAD_UPDATED' => 'DOWNLOAD_UPDATED',
        'download_updated' => 'DOWNLOAD_UPDATED',
        'downloads.download_updated' => 'DOWNLOAD_UPDATED',
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
        $this->setName('downloads');
        $this->setPhpName('Download');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Download');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('download_id', 'Id', 'BIGINT', true, null, null);
        $this->addForeignKey('site_id', 'SiteId', 'INTEGER', 'sites', 'site_id', false, null, null);
        $this->addForeignKey('file_id', 'FileId', 'INTEGER', 'files', 'file_id', false, null, null);
        $this->addColumn('article_id', 'ArticleId', 'INTEGER', false, null, null);
        $this->addColumn('book_id', 'BookId', 'INTEGER', false, null, null);
        $this->addColumn('axys_account_id', 'AxysAccountId', 'INTEGER', false, null, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'users', 'id', false, null, null);
        $this->addColumn('download_filetype', 'Filetype', 'LONGVARCHAR', false, null, null);
        $this->addColumn('download_version', 'Version', 'VARCHAR', false, 8, null);
        $this->addColumn('download_ip', 'Ip', 'LONGVARCHAR', false, null, null);
        $this->addColumn('download_date', 'Date', 'TIMESTAMP', false, null, null);
        $this->addColumn('download_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('download_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
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
        $this->addRelation('File', '\\Model\\File', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':file_id',
    1 => ':file_id',
  ),
), null, null, null, false);
        $this->addRelation('User', '\\Model\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
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
            'timestampable' => ['create_column' => 'download_created', 'update_column' => 'download_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return (string) $row[
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
        return $withPrefix ? DownloadTableMap::CLASS_DEFAULT : DownloadTableMap::OM_CLASS;
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
     * @return array (Download object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = DownloadTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = DownloadTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + DownloadTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = DownloadTableMap::OM_CLASS;
            /** @var Download $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            DownloadTableMap::addInstanceToPool($obj, $key);
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
            $key = DownloadTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = DownloadTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Download $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                DownloadTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(DownloadTableMap::COL_DOWNLOAD_ID);
            $criteria->addSelectColumn(DownloadTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(DownloadTableMap::COL_FILE_ID);
            $criteria->addSelectColumn(DownloadTableMap::COL_ARTICLE_ID);
            $criteria->addSelectColumn(DownloadTableMap::COL_BOOK_ID);
            $criteria->addSelectColumn(DownloadTableMap::COL_AXYS_ACCOUNT_ID);
            $criteria->addSelectColumn(DownloadTableMap::COL_USER_ID);
            $criteria->addSelectColumn(DownloadTableMap::COL_DOWNLOAD_FILETYPE);
            $criteria->addSelectColumn(DownloadTableMap::COL_DOWNLOAD_VERSION);
            $criteria->addSelectColumn(DownloadTableMap::COL_DOWNLOAD_IP);
            $criteria->addSelectColumn(DownloadTableMap::COL_DOWNLOAD_DATE);
            $criteria->addSelectColumn(DownloadTableMap::COL_DOWNLOAD_CREATED);
            $criteria->addSelectColumn(DownloadTableMap::COL_DOWNLOAD_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.download_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.file_id');
            $criteria->addSelectColumn($alias . '.article_id');
            $criteria->addSelectColumn($alias . '.book_id');
            $criteria->addSelectColumn($alias . '.axys_account_id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.download_filetype');
            $criteria->addSelectColumn($alias . '.download_version');
            $criteria->addSelectColumn($alias . '.download_ip');
            $criteria->addSelectColumn($alias . '.download_date');
            $criteria->addSelectColumn($alias . '.download_created');
            $criteria->addSelectColumn($alias . '.download_updated');
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
            $criteria->removeSelectColumn(DownloadTableMap::COL_DOWNLOAD_ID);
            $criteria->removeSelectColumn(DownloadTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(DownloadTableMap::COL_FILE_ID);
            $criteria->removeSelectColumn(DownloadTableMap::COL_ARTICLE_ID);
            $criteria->removeSelectColumn(DownloadTableMap::COL_BOOK_ID);
            $criteria->removeSelectColumn(DownloadTableMap::COL_AXYS_ACCOUNT_ID);
            $criteria->removeSelectColumn(DownloadTableMap::COL_USER_ID);
            $criteria->removeSelectColumn(DownloadTableMap::COL_DOWNLOAD_FILETYPE);
            $criteria->removeSelectColumn(DownloadTableMap::COL_DOWNLOAD_VERSION);
            $criteria->removeSelectColumn(DownloadTableMap::COL_DOWNLOAD_IP);
            $criteria->removeSelectColumn(DownloadTableMap::COL_DOWNLOAD_DATE);
            $criteria->removeSelectColumn(DownloadTableMap::COL_DOWNLOAD_CREATED);
            $criteria->removeSelectColumn(DownloadTableMap::COL_DOWNLOAD_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.download_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.file_id');
            $criteria->removeSelectColumn($alias . '.article_id');
            $criteria->removeSelectColumn($alias . '.book_id');
            $criteria->removeSelectColumn($alias . '.axys_account_id');
            $criteria->removeSelectColumn($alias . '.user_id');
            $criteria->removeSelectColumn($alias . '.download_filetype');
            $criteria->removeSelectColumn($alias . '.download_version');
            $criteria->removeSelectColumn($alias . '.download_ip');
            $criteria->removeSelectColumn($alias . '.download_date');
            $criteria->removeSelectColumn($alias . '.download_created');
            $criteria->removeSelectColumn($alias . '.download_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(DownloadTableMap::DATABASE_NAME)->getTable(DownloadTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Download or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Download object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(DownloadTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Download) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(DownloadTableMap::DATABASE_NAME);
            $criteria->add(DownloadTableMap::COL_DOWNLOAD_ID, (array) $values, Criteria::IN);
        }

        $query = DownloadQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            DownloadTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                DownloadTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the downloads table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return DownloadQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Download or Criteria object.
     *
     * @param mixed $criteria Criteria or Download object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(DownloadTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Download object
        }

        if ($criteria->containsKey(DownloadTableMap::COL_DOWNLOAD_ID) && $criteria->keyContainsValue(DownloadTableMap::COL_DOWNLOAD_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.DownloadTableMap::COL_DOWNLOAD_ID.')');
        }


        // Set the correct dbName
        $query = DownloadQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
