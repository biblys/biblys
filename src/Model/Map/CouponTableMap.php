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

use Model\Coupon;
use Model\CouponQuery;
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
 * This class defines the structure of the 'coupons' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class CouponTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.CouponTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'coupons';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Coupon';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Coupon';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Coupon';

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
     * the column name for the coupon_id field
     */
    public const COL_COUPON_ID = 'coupons.coupon_id';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'coupons.site_id';

    /**
     * the column name for the axys_account_id field
     */
    public const COL_AXYS_ACCOUNT_ID = 'coupons.axys_account_id';

    /**
     * the column name for the user_id field
     */
    public const COL_USER_ID = 'coupons.user_id';

    /**
     * the column name for the coupon_code field
     */
    public const COL_COUPON_CODE = 'coupons.coupon_code';

    /**
     * the column name for the article_id field
     */
    public const COL_ARTICLE_ID = 'coupons.article_id';

    /**
     * the column name for the stock_id field
     */
    public const COL_STOCK_ID = 'coupons.stock_id';

    /**
     * the column name for the coupon_amount field
     */
    public const COL_COUPON_AMOUNT = 'coupons.coupon_amount';

    /**
     * the column name for the coupon_note field
     */
    public const COL_COUPON_NOTE = 'coupons.coupon_note';

    /**
     * the column name for the coupon_used field
     */
    public const COL_COUPON_USED = 'coupons.coupon_used';

    /**
     * the column name for the coupon_creator field
     */
    public const COL_COUPON_CREATOR = 'coupons.coupon_creator';

    /**
     * the column name for the coupon_insert field
     */
    public const COL_COUPON_INSERT = 'coupons.coupon_insert';

    /**
     * the column name for the coupon_update field
     */
    public const COL_COUPON_UPDATE = 'coupons.coupon_update';

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
        self::TYPE_PHPNAME       => ['Id', 'SiteId', 'AxysAccountId', 'UserId', 'Code', 'ArticleId', 'StockId', 'Amount', 'Note', 'Used', 'Creator', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'siteId', 'axysAccountId', 'userId', 'code', 'articleId', 'stockId', 'amount', 'note', 'used', 'creator', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [CouponTableMap::COL_COUPON_ID, CouponTableMap::COL_SITE_ID, CouponTableMap::COL_AXYS_ACCOUNT_ID, CouponTableMap::COL_USER_ID, CouponTableMap::COL_COUPON_CODE, CouponTableMap::COL_ARTICLE_ID, CouponTableMap::COL_STOCK_ID, CouponTableMap::COL_COUPON_AMOUNT, CouponTableMap::COL_COUPON_NOTE, CouponTableMap::COL_COUPON_USED, CouponTableMap::COL_COUPON_CREATOR, CouponTableMap::COL_COUPON_INSERT, CouponTableMap::COL_COUPON_UPDATE, ],
        self::TYPE_FIELDNAME     => ['coupon_id', 'site_id', 'axys_account_id', 'user_id', 'coupon_code', 'article_id', 'stock_id', 'coupon_amount', 'coupon_note', 'coupon_used', 'coupon_creator', 'coupon_insert', 'coupon_update', ],
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'SiteId' => 1, 'AxysAccountId' => 2, 'UserId' => 3, 'Code' => 4, 'ArticleId' => 5, 'StockId' => 6, 'Amount' => 7, 'Note' => 8, 'Used' => 9, 'Creator' => 10, 'CreatedAt' => 11, 'UpdatedAt' => 12, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'siteId' => 1, 'axysAccountId' => 2, 'userId' => 3, 'code' => 4, 'articleId' => 5, 'stockId' => 6, 'amount' => 7, 'note' => 8, 'used' => 9, 'creator' => 10, 'createdAt' => 11, 'updatedAt' => 12, ],
        self::TYPE_COLNAME       => [CouponTableMap::COL_COUPON_ID => 0, CouponTableMap::COL_SITE_ID => 1, CouponTableMap::COL_AXYS_ACCOUNT_ID => 2, CouponTableMap::COL_USER_ID => 3, CouponTableMap::COL_COUPON_CODE => 4, CouponTableMap::COL_ARTICLE_ID => 5, CouponTableMap::COL_STOCK_ID => 6, CouponTableMap::COL_COUPON_AMOUNT => 7, CouponTableMap::COL_COUPON_NOTE => 8, CouponTableMap::COL_COUPON_USED => 9, CouponTableMap::COL_COUPON_CREATOR => 10, CouponTableMap::COL_COUPON_INSERT => 11, CouponTableMap::COL_COUPON_UPDATE => 12, ],
        self::TYPE_FIELDNAME     => ['coupon_id' => 0, 'site_id' => 1, 'axys_account_id' => 2, 'user_id' => 3, 'coupon_code' => 4, 'article_id' => 5, 'stock_id' => 6, 'coupon_amount' => 7, 'coupon_note' => 8, 'coupon_used' => 9, 'coupon_creator' => 10, 'coupon_insert' => 11, 'coupon_update' => 12, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'COUPON_ID',
        'Coupon.Id' => 'COUPON_ID',
        'id' => 'COUPON_ID',
        'coupon.id' => 'COUPON_ID',
        'CouponTableMap::COL_COUPON_ID' => 'COUPON_ID',
        'COL_COUPON_ID' => 'COUPON_ID',
        'coupon_id' => 'COUPON_ID',
        'coupons.coupon_id' => 'COUPON_ID',
        'SiteId' => 'SITE_ID',
        'Coupon.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'coupon.siteId' => 'SITE_ID',
        'CouponTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'coupons.site_id' => 'SITE_ID',
        'AxysAccountId' => 'AXYS_ACCOUNT_ID',
        'Coupon.AxysAccountId' => 'AXYS_ACCOUNT_ID',
        'axysAccountId' => 'AXYS_ACCOUNT_ID',
        'coupon.axysAccountId' => 'AXYS_ACCOUNT_ID',
        'CouponTableMap::COL_AXYS_ACCOUNT_ID' => 'AXYS_ACCOUNT_ID',
        'COL_AXYS_ACCOUNT_ID' => 'AXYS_ACCOUNT_ID',
        'axys_account_id' => 'AXYS_ACCOUNT_ID',
        'coupons.axys_account_id' => 'AXYS_ACCOUNT_ID',
        'UserId' => 'USER_ID',
        'Coupon.UserId' => 'USER_ID',
        'userId' => 'USER_ID',
        'coupon.userId' => 'USER_ID',
        'CouponTableMap::COL_USER_ID' => 'USER_ID',
        'COL_USER_ID' => 'USER_ID',
        'user_id' => 'USER_ID',
        'coupons.user_id' => 'USER_ID',
        'Code' => 'COUPON_CODE',
        'Coupon.Code' => 'COUPON_CODE',
        'code' => 'COUPON_CODE',
        'coupon.code' => 'COUPON_CODE',
        'CouponTableMap::COL_COUPON_CODE' => 'COUPON_CODE',
        'COL_COUPON_CODE' => 'COUPON_CODE',
        'coupon_code' => 'COUPON_CODE',
        'coupons.coupon_code' => 'COUPON_CODE',
        'ArticleId' => 'ARTICLE_ID',
        'Coupon.ArticleId' => 'ARTICLE_ID',
        'articleId' => 'ARTICLE_ID',
        'coupon.articleId' => 'ARTICLE_ID',
        'CouponTableMap::COL_ARTICLE_ID' => 'ARTICLE_ID',
        'COL_ARTICLE_ID' => 'ARTICLE_ID',
        'article_id' => 'ARTICLE_ID',
        'coupons.article_id' => 'ARTICLE_ID',
        'StockId' => 'STOCK_ID',
        'Coupon.StockId' => 'STOCK_ID',
        'stockId' => 'STOCK_ID',
        'coupon.stockId' => 'STOCK_ID',
        'CouponTableMap::COL_STOCK_ID' => 'STOCK_ID',
        'COL_STOCK_ID' => 'STOCK_ID',
        'stock_id' => 'STOCK_ID',
        'coupons.stock_id' => 'STOCK_ID',
        'Amount' => 'COUPON_AMOUNT',
        'Coupon.Amount' => 'COUPON_AMOUNT',
        'amount' => 'COUPON_AMOUNT',
        'coupon.amount' => 'COUPON_AMOUNT',
        'CouponTableMap::COL_COUPON_AMOUNT' => 'COUPON_AMOUNT',
        'COL_COUPON_AMOUNT' => 'COUPON_AMOUNT',
        'coupon_amount' => 'COUPON_AMOUNT',
        'coupons.coupon_amount' => 'COUPON_AMOUNT',
        'Note' => 'COUPON_NOTE',
        'Coupon.Note' => 'COUPON_NOTE',
        'note' => 'COUPON_NOTE',
        'coupon.note' => 'COUPON_NOTE',
        'CouponTableMap::COL_COUPON_NOTE' => 'COUPON_NOTE',
        'COL_COUPON_NOTE' => 'COUPON_NOTE',
        'coupon_note' => 'COUPON_NOTE',
        'coupons.coupon_note' => 'COUPON_NOTE',
        'Used' => 'COUPON_USED',
        'Coupon.Used' => 'COUPON_USED',
        'used' => 'COUPON_USED',
        'coupon.used' => 'COUPON_USED',
        'CouponTableMap::COL_COUPON_USED' => 'COUPON_USED',
        'COL_COUPON_USED' => 'COUPON_USED',
        'coupon_used' => 'COUPON_USED',
        'coupons.coupon_used' => 'COUPON_USED',
        'Creator' => 'COUPON_CREATOR',
        'Coupon.Creator' => 'COUPON_CREATOR',
        'creator' => 'COUPON_CREATOR',
        'coupon.creator' => 'COUPON_CREATOR',
        'CouponTableMap::COL_COUPON_CREATOR' => 'COUPON_CREATOR',
        'COL_COUPON_CREATOR' => 'COUPON_CREATOR',
        'coupon_creator' => 'COUPON_CREATOR',
        'coupons.coupon_creator' => 'COUPON_CREATOR',
        'CreatedAt' => 'COUPON_INSERT',
        'Coupon.CreatedAt' => 'COUPON_INSERT',
        'createdAt' => 'COUPON_INSERT',
        'coupon.createdAt' => 'COUPON_INSERT',
        'CouponTableMap::COL_COUPON_INSERT' => 'COUPON_INSERT',
        'COL_COUPON_INSERT' => 'COUPON_INSERT',
        'coupon_insert' => 'COUPON_INSERT',
        'coupons.coupon_insert' => 'COUPON_INSERT',
        'UpdatedAt' => 'COUPON_UPDATE',
        'Coupon.UpdatedAt' => 'COUPON_UPDATE',
        'updatedAt' => 'COUPON_UPDATE',
        'coupon.updatedAt' => 'COUPON_UPDATE',
        'CouponTableMap::COL_COUPON_UPDATE' => 'COUPON_UPDATE',
        'COL_COUPON_UPDATE' => 'COUPON_UPDATE',
        'coupon_update' => 'COUPON_UPDATE',
        'coupons.coupon_update' => 'COUPON_UPDATE',
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
        $this->setName('coupons');
        $this->setPhpName('Coupon');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Coupon');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('coupon_id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('site_id', 'SiteId', 'INTEGER', false, null, null);
        $this->addColumn('axys_account_id', 'AxysAccountId', 'INTEGER', false, null, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'users', 'id', false, null, null);
        $this->addColumn('coupon_code', 'Code', 'VARCHAR', false, 6, null);
        $this->addColumn('article_id', 'ArticleId', 'INTEGER', false, null, null);
        $this->addColumn('stock_id', 'StockId', 'INTEGER', false, null, null);
        $this->addColumn('coupon_amount', 'Amount', 'INTEGER', false, null, null);
        $this->addColumn('coupon_note', 'Note', 'VARCHAR', false, 256, null);
        $this->addColumn('coupon_used', 'Used', 'TIMESTAMP', false, null, null);
        $this->addColumn('coupon_creator', 'Creator', 'INTEGER', false, null, null);
        $this->addColumn('coupon_insert', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('coupon_update', 'UpdatedAt', 'TIMESTAMP', false, null, null);
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
            'timestampable' => ['create_column' => 'coupon_insert', 'update_column' => 'coupon_update', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? CouponTableMap::CLASS_DEFAULT : CouponTableMap::OM_CLASS;
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
     * @return array (Coupon object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = CouponTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = CouponTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + CouponTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = CouponTableMap::OM_CLASS;
            /** @var Coupon $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            CouponTableMap::addInstanceToPool($obj, $key);
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
            $key = CouponTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = CouponTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Coupon $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                CouponTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(CouponTableMap::COL_COUPON_ID);
            $criteria->addSelectColumn(CouponTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(CouponTableMap::COL_AXYS_ACCOUNT_ID);
            $criteria->addSelectColumn(CouponTableMap::COL_USER_ID);
            $criteria->addSelectColumn(CouponTableMap::COL_COUPON_CODE);
            $criteria->addSelectColumn(CouponTableMap::COL_ARTICLE_ID);
            $criteria->addSelectColumn(CouponTableMap::COL_STOCK_ID);
            $criteria->addSelectColumn(CouponTableMap::COL_COUPON_AMOUNT);
            $criteria->addSelectColumn(CouponTableMap::COL_COUPON_NOTE);
            $criteria->addSelectColumn(CouponTableMap::COL_COUPON_USED);
            $criteria->addSelectColumn(CouponTableMap::COL_COUPON_CREATOR);
            $criteria->addSelectColumn(CouponTableMap::COL_COUPON_INSERT);
            $criteria->addSelectColumn(CouponTableMap::COL_COUPON_UPDATE);
        } else {
            $criteria->addSelectColumn($alias . '.coupon_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.axys_account_id');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.coupon_code');
            $criteria->addSelectColumn($alias . '.article_id');
            $criteria->addSelectColumn($alias . '.stock_id');
            $criteria->addSelectColumn($alias . '.coupon_amount');
            $criteria->addSelectColumn($alias . '.coupon_note');
            $criteria->addSelectColumn($alias . '.coupon_used');
            $criteria->addSelectColumn($alias . '.coupon_creator');
            $criteria->addSelectColumn($alias . '.coupon_insert');
            $criteria->addSelectColumn($alias . '.coupon_update');
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
            $criteria->removeSelectColumn(CouponTableMap::COL_COUPON_ID);
            $criteria->removeSelectColumn(CouponTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(CouponTableMap::COL_AXYS_ACCOUNT_ID);
            $criteria->removeSelectColumn(CouponTableMap::COL_USER_ID);
            $criteria->removeSelectColumn(CouponTableMap::COL_COUPON_CODE);
            $criteria->removeSelectColumn(CouponTableMap::COL_ARTICLE_ID);
            $criteria->removeSelectColumn(CouponTableMap::COL_STOCK_ID);
            $criteria->removeSelectColumn(CouponTableMap::COL_COUPON_AMOUNT);
            $criteria->removeSelectColumn(CouponTableMap::COL_COUPON_NOTE);
            $criteria->removeSelectColumn(CouponTableMap::COL_COUPON_USED);
            $criteria->removeSelectColumn(CouponTableMap::COL_COUPON_CREATOR);
            $criteria->removeSelectColumn(CouponTableMap::COL_COUPON_INSERT);
            $criteria->removeSelectColumn(CouponTableMap::COL_COUPON_UPDATE);
        } else {
            $criteria->removeSelectColumn($alias . '.coupon_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.axys_account_id');
            $criteria->removeSelectColumn($alias . '.user_id');
            $criteria->removeSelectColumn($alias . '.coupon_code');
            $criteria->removeSelectColumn($alias . '.article_id');
            $criteria->removeSelectColumn($alias . '.stock_id');
            $criteria->removeSelectColumn($alias . '.coupon_amount');
            $criteria->removeSelectColumn($alias . '.coupon_note');
            $criteria->removeSelectColumn($alias . '.coupon_used');
            $criteria->removeSelectColumn($alias . '.coupon_creator');
            $criteria->removeSelectColumn($alias . '.coupon_insert');
            $criteria->removeSelectColumn($alias . '.coupon_update');
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
        return Propel::getServiceContainer()->getDatabaseMap(CouponTableMap::DATABASE_NAME)->getTable(CouponTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Coupon or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Coupon object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(CouponTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Coupon) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(CouponTableMap::DATABASE_NAME);
            $criteria->add(CouponTableMap::COL_COUPON_ID, (array) $values, Criteria::IN);
        }

        $query = CouponQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            CouponTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                CouponTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the coupons table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return CouponQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Coupon or Criteria object.
     *
     * @param mixed $criteria Criteria or Coupon object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CouponTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Coupon object
        }

        if ($criteria->containsKey(CouponTableMap::COL_COUPON_ID) && $criteria->keyContainsValue(CouponTableMap::COL_COUPON_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.CouponTableMap::COL_COUPON_ID.')');
        }


        // Set the correct dbName
        $query = CouponQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
