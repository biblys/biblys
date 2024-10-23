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

use Model\Right;
use Model\RightQuery;
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
 * This class defines the structure of the 'rights' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class RightTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.RightTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'rights';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Right';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Right';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Right';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 12;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 12;

    /**
     * the column name for the right_id field
     */
    public const COL_RIGHT_ID = 'rights.right_id';

    /**
     * the column name for the right_uid field
     */
    public const COL_RIGHT_UID = 'rights.right_uid';

    /**
     * the column name for the axys_account_id field
     */
    public const COL_AXYS_ACCOUNT_ID = 'rights.axys_account_id';

    /**
     * the column name for the user_id field
     */
    public const COL_USER_ID = 'rights.user_id';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'rights.site_id';

    /**
     * the column name for the is_admin field
     */
    public const COL_IS_ADMIN = 'rights.is_admin';

    /**
     * the column name for the publisher_id field
     */
    public const COL_PUBLISHER_ID = 'rights.publisher_id';

    /**
     * the column name for the bookshop_id field
     */
    public const COL_BOOKSHOP_ID = 'rights.bookshop_id';

    /**
     * the column name for the library_id field
     */
    public const COL_LIBRARY_ID = 'rights.library_id';

    /**
     * the column name for the right_current field
     */
    public const COL_RIGHT_CURRENT = 'rights.right_current';

    /**
     * the column name for the right_created field
     */
    public const COL_RIGHT_CREATED = 'rights.right_created';

    /**
     * the column name for the right_updated field
     */
    public const COL_RIGHT_UPDATED = 'rights.right_updated';

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
        self::TYPE_PHPNAME       => ['Id', 'Uid', 'AxysAccountId', 'UserId', 'SiteId', 'isAdmin', 'PublisherId', 'BookshopId', 'LibraryId', 'Current', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'uid', 'axysAccountId', 'userId', 'siteId', 'isAdmin', 'publisherId', 'bookshopId', 'libraryId', 'current', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [RightTableMap::COL_RIGHT_ID, RightTableMap::COL_RIGHT_UID, RightTableMap::COL_AXYS_ACCOUNT_ID, RightTableMap::COL_USER_ID, RightTableMap::COL_SITE_ID, RightTableMap::COL_IS_ADMIN, RightTableMap::COL_PUBLISHER_ID, RightTableMap::COL_BOOKSHOP_ID, RightTableMap::COL_LIBRARY_ID, RightTableMap::COL_RIGHT_CURRENT, RightTableMap::COL_RIGHT_CREATED, RightTableMap::COL_RIGHT_UPDATED, ],
        self::TYPE_FIELDNAME     => ['right_id', 'right_uid', 'axys_account_id', 'user_id', 'site_id', 'is_admin', 'publisher_id', 'bookshop_id', 'library_id', 'right_current', 'right_created', 'right_updated', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'Uid' => 1, 'AxysAccountId' => 2, 'UserId' => 3, 'SiteId' => 4, 'isAdmin' => 5, 'PublisherId' => 6, 'BookshopId' => 7, 'LibraryId' => 8, 'Current' => 9, 'CreatedAt' => 10, 'UpdatedAt' => 11, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'uid' => 1, 'axysAccountId' => 2, 'userId' => 3, 'siteId' => 4, 'isAdmin' => 5, 'publisherId' => 6, 'bookshopId' => 7, 'libraryId' => 8, 'current' => 9, 'createdAt' => 10, 'updatedAt' => 11, ],
        self::TYPE_COLNAME       => [RightTableMap::COL_RIGHT_ID => 0, RightTableMap::COL_RIGHT_UID => 1, RightTableMap::COL_AXYS_ACCOUNT_ID => 2, RightTableMap::COL_USER_ID => 3, RightTableMap::COL_SITE_ID => 4, RightTableMap::COL_IS_ADMIN => 5, RightTableMap::COL_PUBLISHER_ID => 6, RightTableMap::COL_BOOKSHOP_ID => 7, RightTableMap::COL_LIBRARY_ID => 8, RightTableMap::COL_RIGHT_CURRENT => 9, RightTableMap::COL_RIGHT_CREATED => 10, RightTableMap::COL_RIGHT_UPDATED => 11, ],
        self::TYPE_FIELDNAME     => ['right_id' => 0, 'right_uid' => 1, 'axys_account_id' => 2, 'user_id' => 3, 'site_id' => 4, 'is_admin' => 5, 'publisher_id' => 6, 'bookshop_id' => 7, 'library_id' => 8, 'right_current' => 9, 'right_created' => 10, 'right_updated' => 11, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'RIGHT_ID',
        'Right.Id' => 'RIGHT_ID',
        'id' => 'RIGHT_ID',
        'right.id' => 'RIGHT_ID',
        'RightTableMap::COL_RIGHT_ID' => 'RIGHT_ID',
        'COL_RIGHT_ID' => 'RIGHT_ID',
        'right_id' => 'RIGHT_ID',
        'rights.right_id' => 'RIGHT_ID',
        'Uid' => 'RIGHT_UID',
        'Right.Uid' => 'RIGHT_UID',
        'uid' => 'RIGHT_UID',
        'right.uid' => 'RIGHT_UID',
        'RightTableMap::COL_RIGHT_UID' => 'RIGHT_UID',
        'COL_RIGHT_UID' => 'RIGHT_UID',
        'right_uid' => 'RIGHT_UID',
        'rights.right_uid' => 'RIGHT_UID',
        'AxysAccountId' => 'AXYS_ACCOUNT_ID',
        'Right.AxysAccountId' => 'AXYS_ACCOUNT_ID',
        'axysAccountId' => 'AXYS_ACCOUNT_ID',
        'right.axysAccountId' => 'AXYS_ACCOUNT_ID',
        'RightTableMap::COL_AXYS_ACCOUNT_ID' => 'AXYS_ACCOUNT_ID',
        'COL_AXYS_ACCOUNT_ID' => 'AXYS_ACCOUNT_ID',
        'axys_account_id' => 'AXYS_ACCOUNT_ID',
        'rights.axys_account_id' => 'AXYS_ACCOUNT_ID',
        'UserId' => 'USER_ID',
        'Right.UserId' => 'USER_ID',
        'userId' => 'USER_ID',
        'right.userId' => 'USER_ID',
        'RightTableMap::COL_USER_ID' => 'USER_ID',
        'COL_USER_ID' => 'USER_ID',
        'user_id' => 'USER_ID',
        'rights.user_id' => 'USER_ID',
        'SiteId' => 'SITE_ID',
        'Right.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'right.siteId' => 'SITE_ID',
        'RightTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'rights.site_id' => 'SITE_ID',
        'isAdmin' => 'IS_ADMIN',
        'Right.isAdmin' => 'IS_ADMIN',
        'right.isAdmin' => 'IS_ADMIN',
        'RightTableMap::COL_IS_ADMIN' => 'IS_ADMIN',
        'COL_IS_ADMIN' => 'IS_ADMIN',
        'is_admin' => 'IS_ADMIN',
        'rights.is_admin' => 'IS_ADMIN',
        'PublisherId' => 'PUBLISHER_ID',
        'Right.PublisherId' => 'PUBLISHER_ID',
        'publisherId' => 'PUBLISHER_ID',
        'right.publisherId' => 'PUBLISHER_ID',
        'RightTableMap::COL_PUBLISHER_ID' => 'PUBLISHER_ID',
        'COL_PUBLISHER_ID' => 'PUBLISHER_ID',
        'publisher_id' => 'PUBLISHER_ID',
        'rights.publisher_id' => 'PUBLISHER_ID',
        'BookshopId' => 'BOOKSHOP_ID',
        'Right.BookshopId' => 'BOOKSHOP_ID',
        'bookshopId' => 'BOOKSHOP_ID',
        'right.bookshopId' => 'BOOKSHOP_ID',
        'RightTableMap::COL_BOOKSHOP_ID' => 'BOOKSHOP_ID',
        'COL_BOOKSHOP_ID' => 'BOOKSHOP_ID',
        'bookshop_id' => 'BOOKSHOP_ID',
        'rights.bookshop_id' => 'BOOKSHOP_ID',
        'LibraryId' => 'LIBRARY_ID',
        'Right.LibraryId' => 'LIBRARY_ID',
        'libraryId' => 'LIBRARY_ID',
        'right.libraryId' => 'LIBRARY_ID',
        'RightTableMap::COL_LIBRARY_ID' => 'LIBRARY_ID',
        'COL_LIBRARY_ID' => 'LIBRARY_ID',
        'library_id' => 'LIBRARY_ID',
        'rights.library_id' => 'LIBRARY_ID',
        'Current' => 'RIGHT_CURRENT',
        'Right.Current' => 'RIGHT_CURRENT',
        'current' => 'RIGHT_CURRENT',
        'right.current' => 'RIGHT_CURRENT',
        'RightTableMap::COL_RIGHT_CURRENT' => 'RIGHT_CURRENT',
        'COL_RIGHT_CURRENT' => 'RIGHT_CURRENT',
        'right_current' => 'RIGHT_CURRENT',
        'rights.right_current' => 'RIGHT_CURRENT',
        'CreatedAt' => 'RIGHT_CREATED',
        'Right.CreatedAt' => 'RIGHT_CREATED',
        'createdAt' => 'RIGHT_CREATED',
        'right.createdAt' => 'RIGHT_CREATED',
        'RightTableMap::COL_RIGHT_CREATED' => 'RIGHT_CREATED',
        'COL_RIGHT_CREATED' => 'RIGHT_CREATED',
        'right_created' => 'RIGHT_CREATED',
        'rights.right_created' => 'RIGHT_CREATED',
        'UpdatedAt' => 'RIGHT_UPDATED',
        'Right.UpdatedAt' => 'RIGHT_UPDATED',
        'updatedAt' => 'RIGHT_UPDATED',
        'right.updatedAt' => 'RIGHT_UPDATED',
        'RightTableMap::COL_RIGHT_UPDATED' => 'RIGHT_UPDATED',
        'COL_RIGHT_UPDATED' => 'RIGHT_UPDATED',
        'right_updated' => 'RIGHT_UPDATED',
        'rights.right_updated' => 'RIGHT_UPDATED',
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
        $this->setName('rights');
        $this->setPhpName('Right');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Right');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('right_id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('right_uid', 'Uid', 'VARCHAR', false, 32, null);
        $this->addColumn('axys_account_id', 'AxysAccountId', 'INTEGER', false, null, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'users', 'id', false, null, null);
        $this->addForeignKey('site_id', 'SiteId', 'INTEGER', 'sites', 'site_id', false, null, null);
        $this->addColumn('is_admin', 'isAdmin', 'BOOLEAN', false, 1, false);
        $this->addForeignKey('publisher_id', 'PublisherId', 'INTEGER', 'publishers', 'publisher_id', false, null, null);
        $this->addColumn('bookshop_id', 'BookshopId', 'INTEGER', false, null, null);
        $this->addColumn('library_id', 'LibraryId', 'INTEGER', false, null, null);
        $this->addColumn('right_current', 'Current', 'BOOLEAN', false, 1, false);
        $this->addColumn('right_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('right_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
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
            'timestampable' => ['create_column' => 'right_created', 'update_column' => 'right_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? RightTableMap::CLASS_DEFAULT : RightTableMap::OM_CLASS;
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
     * @return array (Right object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = RightTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = RightTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + RightTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = RightTableMap::OM_CLASS;
            /** @var Right $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            RightTableMap::addInstanceToPool($obj, $key);
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
            $key = RightTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = RightTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Right $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                RightTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(RightTableMap::COL_RIGHT_ID);
            $criteria->addSelectColumn(RightTableMap::COL_RIGHT_UID);
            $criteria->addSelectColumn(RightTableMap::COL_AXYS_ACCOUNT_ID);
            $criteria->addSelectColumn(RightTableMap::COL_USER_ID);
            $criteria->addSelectColumn(RightTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(RightTableMap::COL_IS_ADMIN);
            $criteria->addSelectColumn(RightTableMap::COL_PUBLISHER_ID);
            $criteria->addSelectColumn(RightTableMap::COL_BOOKSHOP_ID);
            $criteria->addSelectColumn(RightTableMap::COL_LIBRARY_ID);
            $criteria->addSelectColumn(RightTableMap::COL_RIGHT_CURRENT);
            $criteria->addSelectColumn(RightTableMap::COL_RIGHT_CREATED);
            $criteria->addSelectColumn(RightTableMap::COL_RIGHT_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.right_id');
            $criteria->addSelectColumn($alias . '.right_uid');
            $criteria->addSelectColumn($alias . '.axys_account_id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.is_admin');
            $criteria->addSelectColumn($alias . '.publisher_id');
            $criteria->addSelectColumn($alias . '.bookshop_id');
            $criteria->addSelectColumn($alias . '.library_id');
            $criteria->addSelectColumn($alias . '.right_current');
            $criteria->addSelectColumn($alias . '.right_created');
            $criteria->addSelectColumn($alias . '.right_updated');
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
            $criteria->removeSelectColumn(RightTableMap::COL_RIGHT_ID);
            $criteria->removeSelectColumn(RightTableMap::COL_RIGHT_UID);
            $criteria->removeSelectColumn(RightTableMap::COL_AXYS_ACCOUNT_ID);
            $criteria->removeSelectColumn(RightTableMap::COL_USER_ID);
            $criteria->removeSelectColumn(RightTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(RightTableMap::COL_IS_ADMIN);
            $criteria->removeSelectColumn(RightTableMap::COL_PUBLISHER_ID);
            $criteria->removeSelectColumn(RightTableMap::COL_BOOKSHOP_ID);
            $criteria->removeSelectColumn(RightTableMap::COL_LIBRARY_ID);
            $criteria->removeSelectColumn(RightTableMap::COL_RIGHT_CURRENT);
            $criteria->removeSelectColumn(RightTableMap::COL_RIGHT_CREATED);
            $criteria->removeSelectColumn(RightTableMap::COL_RIGHT_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.right_id');
            $criteria->removeSelectColumn($alias . '.right_uid');
            $criteria->removeSelectColumn($alias . '.axys_account_id');
            $criteria->removeSelectColumn($alias . '.user_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.is_admin');
            $criteria->removeSelectColumn($alias . '.publisher_id');
            $criteria->removeSelectColumn($alias . '.bookshop_id');
            $criteria->removeSelectColumn($alias . '.library_id');
            $criteria->removeSelectColumn($alias . '.right_current');
            $criteria->removeSelectColumn($alias . '.right_created');
            $criteria->removeSelectColumn($alias . '.right_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(RightTableMap::DATABASE_NAME)->getTable(RightTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Right or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Right object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(RightTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Right) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(RightTableMap::DATABASE_NAME);
            $criteria->add(RightTableMap::COL_RIGHT_ID, (array) $values, Criteria::IN);
        }

        $query = RightQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            RightTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                RightTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the rights table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return RightQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Right or Criteria object.
     *
     * @param mixed $criteria Criteria or Right object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(RightTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Right object
        }

        if ($criteria->containsKey(RightTableMap::COL_RIGHT_ID) && $criteria->keyContainsValue(RightTableMap::COL_RIGHT_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.RightTableMap::COL_RIGHT_ID.')');
        }


        // Set the correct dbName
        $query = RightQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
