<?php

namespace Model\Map;

use Model\Tag;
use Model\TagQuery;
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
 * This class defines the structure of the 'tags' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class TagTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.TagTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'tags';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Tag';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Tag';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Tag';

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
     * the column name for the tag_id field
     */
    public const COL_TAG_ID = 'tags.tag_id';

    /**
     * the column name for the tag_name field
     */
    public const COL_TAG_NAME = 'tags.tag_name';

    /**
     * the column name for the tag_url field
     */
    public const COL_TAG_URL = 'tags.tag_url';

    /**
     * the column name for the tag_description field
     */
    public const COL_TAG_DESCRIPTION = 'tags.tag_description';

    /**
     * the column name for the tag_date field
     */
    public const COL_TAG_DATE = 'tags.tag_date';

    /**
     * the column name for the tag_num field
     */
    public const COL_TAG_NUM = 'tags.tag_num';

    /**
     * the column name for the tag_insert field
     */
    public const COL_TAG_INSERT = 'tags.tag_insert';

    /**
     * the column name for the tag_update field
     */
    public const COL_TAG_UPDATE = 'tags.tag_update';

    /**
     * the column name for the tag_created field
     */
    public const COL_TAG_CREATED = 'tags.tag_created';

    /**
     * the column name for the tag_updated field
     */
    public const COL_TAG_UPDATED = 'tags.tag_updated';

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
        self::TYPE_PHPNAME       => ['Id', 'Name', 'Url', 'Description', 'Date', 'Num', 'Insert', 'Update', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'name', 'url', 'description', 'date', 'num', 'insert', 'update', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [TagTableMap::COL_TAG_ID, TagTableMap::COL_TAG_NAME, TagTableMap::COL_TAG_URL, TagTableMap::COL_TAG_DESCRIPTION, TagTableMap::COL_TAG_DATE, TagTableMap::COL_TAG_NUM, TagTableMap::COL_TAG_INSERT, TagTableMap::COL_TAG_UPDATE, TagTableMap::COL_TAG_CREATED, TagTableMap::COL_TAG_UPDATED, ],
        self::TYPE_FIELDNAME     => ['tag_id', 'tag_name', 'tag_url', 'tag_description', 'tag_date', 'tag_num', 'tag_insert', 'tag_update', 'tag_created', 'tag_updated', ],
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'Name' => 1, 'Url' => 2, 'Description' => 3, 'Date' => 4, 'Num' => 5, 'Insert' => 6, 'Update' => 7, 'CreatedAt' => 8, 'UpdatedAt' => 9, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'name' => 1, 'url' => 2, 'description' => 3, 'date' => 4, 'num' => 5, 'insert' => 6, 'update' => 7, 'createdAt' => 8, 'updatedAt' => 9, ],
        self::TYPE_COLNAME       => [TagTableMap::COL_TAG_ID => 0, TagTableMap::COL_TAG_NAME => 1, TagTableMap::COL_TAG_URL => 2, TagTableMap::COL_TAG_DESCRIPTION => 3, TagTableMap::COL_TAG_DATE => 4, TagTableMap::COL_TAG_NUM => 5, TagTableMap::COL_TAG_INSERT => 6, TagTableMap::COL_TAG_UPDATE => 7, TagTableMap::COL_TAG_CREATED => 8, TagTableMap::COL_TAG_UPDATED => 9, ],
        self::TYPE_FIELDNAME     => ['tag_id' => 0, 'tag_name' => 1, 'tag_url' => 2, 'tag_description' => 3, 'tag_date' => 4, 'tag_num' => 5, 'tag_insert' => 6, 'tag_update' => 7, 'tag_created' => 8, 'tag_updated' => 9, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'TAG_ID',
        'Tag.Id' => 'TAG_ID',
        'id' => 'TAG_ID',
        'tag.id' => 'TAG_ID',
        'TagTableMap::COL_TAG_ID' => 'TAG_ID',
        'COL_TAG_ID' => 'TAG_ID',
        'tag_id' => 'TAG_ID',
        'tags.tag_id' => 'TAG_ID',
        'Name' => 'TAG_NAME',
        'Tag.Name' => 'TAG_NAME',
        'name' => 'TAG_NAME',
        'tag.name' => 'TAG_NAME',
        'TagTableMap::COL_TAG_NAME' => 'TAG_NAME',
        'COL_TAG_NAME' => 'TAG_NAME',
        'tag_name' => 'TAG_NAME',
        'tags.tag_name' => 'TAG_NAME',
        'Url' => 'TAG_URL',
        'Tag.Url' => 'TAG_URL',
        'url' => 'TAG_URL',
        'tag.url' => 'TAG_URL',
        'TagTableMap::COL_TAG_URL' => 'TAG_URL',
        'COL_TAG_URL' => 'TAG_URL',
        'tag_url' => 'TAG_URL',
        'tags.tag_url' => 'TAG_URL',
        'Description' => 'TAG_DESCRIPTION',
        'Tag.Description' => 'TAG_DESCRIPTION',
        'description' => 'TAG_DESCRIPTION',
        'tag.description' => 'TAG_DESCRIPTION',
        'TagTableMap::COL_TAG_DESCRIPTION' => 'TAG_DESCRIPTION',
        'COL_TAG_DESCRIPTION' => 'TAG_DESCRIPTION',
        'tag_description' => 'TAG_DESCRIPTION',
        'tags.tag_description' => 'TAG_DESCRIPTION',
        'Date' => 'TAG_DATE',
        'Tag.Date' => 'TAG_DATE',
        'date' => 'TAG_DATE',
        'tag.date' => 'TAG_DATE',
        'TagTableMap::COL_TAG_DATE' => 'TAG_DATE',
        'COL_TAG_DATE' => 'TAG_DATE',
        'tag_date' => 'TAG_DATE',
        'tags.tag_date' => 'TAG_DATE',
        'Num' => 'TAG_NUM',
        'Tag.Num' => 'TAG_NUM',
        'num' => 'TAG_NUM',
        'tag.num' => 'TAG_NUM',
        'TagTableMap::COL_TAG_NUM' => 'TAG_NUM',
        'COL_TAG_NUM' => 'TAG_NUM',
        'tag_num' => 'TAG_NUM',
        'tags.tag_num' => 'TAG_NUM',
        'Insert' => 'TAG_INSERT',
        'Tag.Insert' => 'TAG_INSERT',
        'insert' => 'TAG_INSERT',
        'tag.insert' => 'TAG_INSERT',
        'TagTableMap::COL_TAG_INSERT' => 'TAG_INSERT',
        'COL_TAG_INSERT' => 'TAG_INSERT',
        'tag_insert' => 'TAG_INSERT',
        'tags.tag_insert' => 'TAG_INSERT',
        'Update' => 'TAG_UPDATE',
        'Tag.Update' => 'TAG_UPDATE',
        'update' => 'TAG_UPDATE',
        'tag.update' => 'TAG_UPDATE',
        'TagTableMap::COL_TAG_UPDATE' => 'TAG_UPDATE',
        'COL_TAG_UPDATE' => 'TAG_UPDATE',
        'tag_update' => 'TAG_UPDATE',
        'tags.tag_update' => 'TAG_UPDATE',
        'CreatedAt' => 'TAG_CREATED',
        'Tag.CreatedAt' => 'TAG_CREATED',
        'createdAt' => 'TAG_CREATED',
        'tag.createdAt' => 'TAG_CREATED',
        'TagTableMap::COL_TAG_CREATED' => 'TAG_CREATED',
        'COL_TAG_CREATED' => 'TAG_CREATED',
        'tag_created' => 'TAG_CREATED',
        'tags.tag_created' => 'TAG_CREATED',
        'UpdatedAt' => 'TAG_UPDATED',
        'Tag.UpdatedAt' => 'TAG_UPDATED',
        'updatedAt' => 'TAG_UPDATED',
        'tag.updatedAt' => 'TAG_UPDATED',
        'TagTableMap::COL_TAG_UPDATED' => 'TAG_UPDATED',
        'COL_TAG_UPDATED' => 'TAG_UPDATED',
        'tag_updated' => 'TAG_UPDATED',
        'tags.tag_updated' => 'TAG_UPDATED',
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
        $this->setName('tags');
        $this->setPhpName('Tag');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Tag');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('tag_id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('tag_name', 'Name', 'LONGVARCHAR', false, null, null);
        $this->addColumn('tag_url', 'Url', 'LONGVARCHAR', false, null, null);
        $this->addColumn('tag_description', 'Description', 'LONGVARCHAR', false, null, null);
        $this->addColumn('tag_date', 'Date', 'TIMESTAMP', false, null, null);
        $this->addColumn('tag_num', 'Num', 'INTEGER', false, null, null);
        $this->addColumn('tag_insert', 'Insert', 'TIMESTAMP', false, null, null);
        $this->addColumn('tag_update', 'Update', 'TIMESTAMP', false, null, null);
        $this->addColumn('tag_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('tag_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
        $this->addRelation('Link', '\\Model\\Link', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':tag_id',
    1 => ':tag_id',
  ),
), null, null, 'Links', false);
        $this->addRelation('ArticleTag', '\\Model\\ArticleTag', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':tag_id',
    1 => ':tag_id',
  ),
), null, null, 'ArticleTags', false);
        $this->addRelation('Article', '\\Model\\Article', RelationMap::MANY_TO_MANY, array(), null, null, 'Articles');
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
            'timestampable' => ['create_column' => 'tag_created', 'update_column' => 'tag_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? TagTableMap::CLASS_DEFAULT : TagTableMap::OM_CLASS;
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
     * @return array (Tag object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = TagTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = TagTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + TagTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = TagTableMap::OM_CLASS;
            /** @var Tag $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            TagTableMap::addInstanceToPool($obj, $key);
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
            $key = TagTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = TagTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Tag $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                TagTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(TagTableMap::COL_TAG_ID);
            $criteria->addSelectColumn(TagTableMap::COL_TAG_NAME);
            $criteria->addSelectColumn(TagTableMap::COL_TAG_URL);
            $criteria->addSelectColumn(TagTableMap::COL_TAG_DESCRIPTION);
            $criteria->addSelectColumn(TagTableMap::COL_TAG_DATE);
            $criteria->addSelectColumn(TagTableMap::COL_TAG_NUM);
            $criteria->addSelectColumn(TagTableMap::COL_TAG_INSERT);
            $criteria->addSelectColumn(TagTableMap::COL_TAG_UPDATE);
            $criteria->addSelectColumn(TagTableMap::COL_TAG_CREATED);
            $criteria->addSelectColumn(TagTableMap::COL_TAG_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.tag_id');
            $criteria->addSelectColumn($alias . '.tag_name');
            $criteria->addSelectColumn($alias . '.tag_url');
            $criteria->addSelectColumn($alias . '.tag_description');
            $criteria->addSelectColumn($alias . '.tag_date');
            $criteria->addSelectColumn($alias . '.tag_num');
            $criteria->addSelectColumn($alias . '.tag_insert');
            $criteria->addSelectColumn($alias . '.tag_update');
            $criteria->addSelectColumn($alias . '.tag_created');
            $criteria->addSelectColumn($alias . '.tag_updated');
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
            $criteria->removeSelectColumn(TagTableMap::COL_TAG_ID);
            $criteria->removeSelectColumn(TagTableMap::COL_TAG_NAME);
            $criteria->removeSelectColumn(TagTableMap::COL_TAG_URL);
            $criteria->removeSelectColumn(TagTableMap::COL_TAG_DESCRIPTION);
            $criteria->removeSelectColumn(TagTableMap::COL_TAG_DATE);
            $criteria->removeSelectColumn(TagTableMap::COL_TAG_NUM);
            $criteria->removeSelectColumn(TagTableMap::COL_TAG_INSERT);
            $criteria->removeSelectColumn(TagTableMap::COL_TAG_UPDATE);
            $criteria->removeSelectColumn(TagTableMap::COL_TAG_CREATED);
            $criteria->removeSelectColumn(TagTableMap::COL_TAG_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.tag_id');
            $criteria->removeSelectColumn($alias . '.tag_name');
            $criteria->removeSelectColumn($alias . '.tag_url');
            $criteria->removeSelectColumn($alias . '.tag_description');
            $criteria->removeSelectColumn($alias . '.tag_date');
            $criteria->removeSelectColumn($alias . '.tag_num');
            $criteria->removeSelectColumn($alias . '.tag_insert');
            $criteria->removeSelectColumn($alias . '.tag_update');
            $criteria->removeSelectColumn($alias . '.tag_created');
            $criteria->removeSelectColumn($alias . '.tag_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(TagTableMap::DATABASE_NAME)->getTable(TagTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Tag or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Tag object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(TagTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Tag) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(TagTableMap::DATABASE_NAME);
            $criteria->add(TagTableMap::COL_TAG_ID, (array) $values, Criteria::IN);
        }

        $query = TagQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            TagTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                TagTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the tags table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return TagQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Tag or Criteria object.
     *
     * @param mixed $criteria Criteria or Tag object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(TagTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Tag object
        }

        if ($criteria->containsKey(TagTableMap::COL_TAG_ID) && $criteria->keyContainsValue(TagTableMap::COL_TAG_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.TagTableMap::COL_TAG_ID.')');
        }


        // Set the correct dbName
        $query = TagQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
