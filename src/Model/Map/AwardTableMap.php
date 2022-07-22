<?php

namespace Model\Map;

use Model\Award;
use Model\AwardQuery;
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
 * This class defines the structure of the 'awards' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class AwardTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.AwardTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'awards';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Award';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Award';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 10;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 10;

    /**
     * the column name for the award_id field
     */
    public const COL_AWARD_ID = 'awards.award_id';

    /**
     * the column name for the article_id field
     */
    public const COL_ARTICLE_ID = 'awards.article_id';

    /**
     * the column name for the book_id field
     */
    public const COL_BOOK_ID = 'awards.book_id';

    /**
     * the column name for the award_name field
     */
    public const COL_AWARD_NAME = 'awards.award_name';

    /**
     * the column name for the award_year field
     */
    public const COL_AWARD_YEAR = 'awards.award_year';

    /**
     * the column name for the award_category field
     */
    public const COL_AWARD_CATEGORY = 'awards.award_category';

    /**
     * the column name for the award_note field
     */
    public const COL_AWARD_NOTE = 'awards.award_note';

    /**
     * the column name for the award_date field
     */
    public const COL_AWARD_DATE = 'awards.award_date';

    /**
     * the column name for the award_created field
     */
    public const COL_AWARD_CREATED = 'awards.award_created';

    /**
     * the column name for the award_updated field
     */
    public const COL_AWARD_UPDATED = 'awards.award_updated';

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
        self::TYPE_PHPNAME       => ['Id', 'ArticleId', 'BookId', 'Name', 'Year', 'Category', 'Note', 'Date', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'articleId', 'bookId', 'name', 'year', 'category', 'note', 'date', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [AwardTableMap::COL_AWARD_ID, AwardTableMap::COL_ARTICLE_ID, AwardTableMap::COL_BOOK_ID, AwardTableMap::COL_AWARD_NAME, AwardTableMap::COL_AWARD_YEAR, AwardTableMap::COL_AWARD_CATEGORY, AwardTableMap::COL_AWARD_NOTE, AwardTableMap::COL_AWARD_DATE, AwardTableMap::COL_AWARD_CREATED, AwardTableMap::COL_AWARD_UPDATED, ],
        self::TYPE_FIELDNAME     => ['award_id', 'article_id', 'book_id', 'award_name', 'award_year', 'award_category', 'award_note', 'award_date', 'award_created', 'award_updated', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'ArticleId' => 1, 'BookId' => 2, 'Name' => 3, 'Year' => 4, 'Category' => 5, 'Note' => 6, 'Date' => 7, 'CreatedAt' => 8, 'UpdatedAt' => 9, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'articleId' => 1, 'bookId' => 2, 'name' => 3, 'year' => 4, 'category' => 5, 'note' => 6, 'date' => 7, 'createdAt' => 8, 'updatedAt' => 9, ],
        self::TYPE_COLNAME       => [AwardTableMap::COL_AWARD_ID => 0, AwardTableMap::COL_ARTICLE_ID => 1, AwardTableMap::COL_BOOK_ID => 2, AwardTableMap::COL_AWARD_NAME => 3, AwardTableMap::COL_AWARD_YEAR => 4, AwardTableMap::COL_AWARD_CATEGORY => 5, AwardTableMap::COL_AWARD_NOTE => 6, AwardTableMap::COL_AWARD_DATE => 7, AwardTableMap::COL_AWARD_CREATED => 8, AwardTableMap::COL_AWARD_UPDATED => 9, ],
        self::TYPE_FIELDNAME     => ['award_id' => 0, 'article_id' => 1, 'book_id' => 2, 'award_name' => 3, 'award_year' => 4, 'award_category' => 5, 'award_note' => 6, 'award_date' => 7, 'award_created' => 8, 'award_updated' => 9, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'AWARD_ID',
        'Award.Id' => 'AWARD_ID',
        'id' => 'AWARD_ID',
        'award.id' => 'AWARD_ID',
        'AwardTableMap::COL_AWARD_ID' => 'AWARD_ID',
        'COL_AWARD_ID' => 'AWARD_ID',
        'award_id' => 'AWARD_ID',
        'awards.award_id' => 'AWARD_ID',
        'ArticleId' => 'ARTICLE_ID',
        'Award.ArticleId' => 'ARTICLE_ID',
        'articleId' => 'ARTICLE_ID',
        'award.articleId' => 'ARTICLE_ID',
        'AwardTableMap::COL_ARTICLE_ID' => 'ARTICLE_ID',
        'COL_ARTICLE_ID' => 'ARTICLE_ID',
        'article_id' => 'ARTICLE_ID',
        'awards.article_id' => 'ARTICLE_ID',
        'BookId' => 'BOOK_ID',
        'Award.BookId' => 'BOOK_ID',
        'bookId' => 'BOOK_ID',
        'award.bookId' => 'BOOK_ID',
        'AwardTableMap::COL_BOOK_ID' => 'BOOK_ID',
        'COL_BOOK_ID' => 'BOOK_ID',
        'book_id' => 'BOOK_ID',
        'awards.book_id' => 'BOOK_ID',
        'Name' => 'AWARD_NAME',
        'Award.Name' => 'AWARD_NAME',
        'name' => 'AWARD_NAME',
        'award.name' => 'AWARD_NAME',
        'AwardTableMap::COL_AWARD_NAME' => 'AWARD_NAME',
        'COL_AWARD_NAME' => 'AWARD_NAME',
        'award_name' => 'AWARD_NAME',
        'awards.award_name' => 'AWARD_NAME',
        'Year' => 'AWARD_YEAR',
        'Award.Year' => 'AWARD_YEAR',
        'year' => 'AWARD_YEAR',
        'award.year' => 'AWARD_YEAR',
        'AwardTableMap::COL_AWARD_YEAR' => 'AWARD_YEAR',
        'COL_AWARD_YEAR' => 'AWARD_YEAR',
        'award_year' => 'AWARD_YEAR',
        'awards.award_year' => 'AWARD_YEAR',
        'Category' => 'AWARD_CATEGORY',
        'Award.Category' => 'AWARD_CATEGORY',
        'category' => 'AWARD_CATEGORY',
        'award.category' => 'AWARD_CATEGORY',
        'AwardTableMap::COL_AWARD_CATEGORY' => 'AWARD_CATEGORY',
        'COL_AWARD_CATEGORY' => 'AWARD_CATEGORY',
        'award_category' => 'AWARD_CATEGORY',
        'awards.award_category' => 'AWARD_CATEGORY',
        'Note' => 'AWARD_NOTE',
        'Award.Note' => 'AWARD_NOTE',
        'note' => 'AWARD_NOTE',
        'award.note' => 'AWARD_NOTE',
        'AwardTableMap::COL_AWARD_NOTE' => 'AWARD_NOTE',
        'COL_AWARD_NOTE' => 'AWARD_NOTE',
        'award_note' => 'AWARD_NOTE',
        'awards.award_note' => 'AWARD_NOTE',
        'Date' => 'AWARD_DATE',
        'Award.Date' => 'AWARD_DATE',
        'date' => 'AWARD_DATE',
        'award.date' => 'AWARD_DATE',
        'AwardTableMap::COL_AWARD_DATE' => 'AWARD_DATE',
        'COL_AWARD_DATE' => 'AWARD_DATE',
        'award_date' => 'AWARD_DATE',
        'awards.award_date' => 'AWARD_DATE',
        'CreatedAt' => 'AWARD_CREATED',
        'Award.CreatedAt' => 'AWARD_CREATED',
        'createdAt' => 'AWARD_CREATED',
        'award.createdAt' => 'AWARD_CREATED',
        'AwardTableMap::COL_AWARD_CREATED' => 'AWARD_CREATED',
        'COL_AWARD_CREATED' => 'AWARD_CREATED',
        'award_created' => 'AWARD_CREATED',
        'awards.award_created' => 'AWARD_CREATED',
        'UpdatedAt' => 'AWARD_UPDATED',
        'Award.UpdatedAt' => 'AWARD_UPDATED',
        'updatedAt' => 'AWARD_UPDATED',
        'award.updatedAt' => 'AWARD_UPDATED',
        'AwardTableMap::COL_AWARD_UPDATED' => 'AWARD_UPDATED',
        'COL_AWARD_UPDATED' => 'AWARD_UPDATED',
        'award_updated' => 'AWARD_UPDATED',
        'awards.award_updated' => 'AWARD_UPDATED',
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
        $this->setName('awards');
        $this->setPhpName('Award');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Award');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('award_id', 'Id', 'INTEGER', true, 10, null);
        $this->addColumn('article_id', 'ArticleId', 'INTEGER', false, 10, null);
        $this->addColumn('book_id', 'BookId', 'INTEGER', false, null, null);
        $this->addColumn('award_name', 'Name', 'LONGVARCHAR', false, null, null);
        $this->addColumn('award_year', 'Year', 'LONGVARCHAR', false, null, null);
        $this->addColumn('award_category', 'Category', 'LONGVARCHAR', false, null, null);
        $this->addColumn('award_note', 'Note', 'LONGVARCHAR', false, null, null);
        $this->addColumn('award_date', 'Date', 'TIMESTAMP', false, null, null);
        $this->addColumn('award_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('award_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
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
            'timestampable' => ['create_column' => 'award_created', 'update_column' => 'award_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? AwardTableMap::CLASS_DEFAULT : AwardTableMap::OM_CLASS;
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
     * @return array (Award object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = AwardTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = AwardTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + AwardTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = AwardTableMap::OM_CLASS;
            /** @var Award $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            AwardTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
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
            $key = AwardTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = AwardTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Award $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                AwardTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(AwardTableMap::COL_AWARD_ID);
            $criteria->addSelectColumn(AwardTableMap::COL_ARTICLE_ID);
            $criteria->addSelectColumn(AwardTableMap::COL_BOOK_ID);
            $criteria->addSelectColumn(AwardTableMap::COL_AWARD_NAME);
            $criteria->addSelectColumn(AwardTableMap::COL_AWARD_YEAR);
            $criteria->addSelectColumn(AwardTableMap::COL_AWARD_CATEGORY);
            $criteria->addSelectColumn(AwardTableMap::COL_AWARD_NOTE);
            $criteria->addSelectColumn(AwardTableMap::COL_AWARD_DATE);
            $criteria->addSelectColumn(AwardTableMap::COL_AWARD_CREATED);
            $criteria->addSelectColumn(AwardTableMap::COL_AWARD_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.award_id');
            $criteria->addSelectColumn($alias . '.article_id');
            $criteria->addSelectColumn($alias . '.book_id');
            $criteria->addSelectColumn($alias . '.award_name');
            $criteria->addSelectColumn($alias . '.award_year');
            $criteria->addSelectColumn($alias . '.award_category');
            $criteria->addSelectColumn($alias . '.award_note');
            $criteria->addSelectColumn($alias . '.award_date');
            $criteria->addSelectColumn($alias . '.award_created');
            $criteria->addSelectColumn($alias . '.award_updated');
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
            $criteria->removeSelectColumn(AwardTableMap::COL_AWARD_ID);
            $criteria->removeSelectColumn(AwardTableMap::COL_ARTICLE_ID);
            $criteria->removeSelectColumn(AwardTableMap::COL_BOOK_ID);
            $criteria->removeSelectColumn(AwardTableMap::COL_AWARD_NAME);
            $criteria->removeSelectColumn(AwardTableMap::COL_AWARD_YEAR);
            $criteria->removeSelectColumn(AwardTableMap::COL_AWARD_CATEGORY);
            $criteria->removeSelectColumn(AwardTableMap::COL_AWARD_NOTE);
            $criteria->removeSelectColumn(AwardTableMap::COL_AWARD_DATE);
            $criteria->removeSelectColumn(AwardTableMap::COL_AWARD_CREATED);
            $criteria->removeSelectColumn(AwardTableMap::COL_AWARD_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.award_id');
            $criteria->removeSelectColumn($alias . '.article_id');
            $criteria->removeSelectColumn($alias . '.book_id');
            $criteria->removeSelectColumn($alias . '.award_name');
            $criteria->removeSelectColumn($alias . '.award_year');
            $criteria->removeSelectColumn($alias . '.award_category');
            $criteria->removeSelectColumn($alias . '.award_note');
            $criteria->removeSelectColumn($alias . '.award_date');
            $criteria->removeSelectColumn($alias . '.award_created');
            $criteria->removeSelectColumn($alias . '.award_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(AwardTableMap::DATABASE_NAME)->getTable(AwardTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Award or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Award object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(AwardTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Award) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(AwardTableMap::DATABASE_NAME);
            $criteria->add(AwardTableMap::COL_AWARD_ID, (array) $values, Criteria::IN);
        }

        $query = AwardQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            AwardTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                AwardTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the awards table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return AwardQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Award or Criteria object.
     *
     * @param mixed $criteria Criteria or Award object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AwardTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Award object
        }

        if ($criteria->containsKey(AwardTableMap::COL_AWARD_ID) && $criteria->keyContainsValue(AwardTableMap::COL_AWARD_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.AwardTableMap::COL_AWARD_ID.')');
        }


        // Set the correct dbName
        $query = AwardQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
