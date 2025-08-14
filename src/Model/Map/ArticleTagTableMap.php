<?php

namespace Model\Map;

use Model\ArticleTag;
use Model\ArticleTagQuery;
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
 * This class defines the structure of the 'tags_articles' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class ArticleTagTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.ArticleTagTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'tags_articles';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'ArticleTag';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\ArticleTag';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.ArticleTag';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 2;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 2;

    /**
     * the column name for the tag_id field
     */
    public const COL_TAG_ID = 'tags_articles.tag_id';

    /**
     * the column name for the article_id field
     */
    public const COL_ARTICLE_ID = 'tags_articles.article_id';

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
        self::TYPE_PHPNAME       => ['TagId', 'ArticleId', ],
        self::TYPE_CAMELNAME     => ['tagId', 'articleId', ],
        self::TYPE_COLNAME       => [ArticleTagTableMap::COL_TAG_ID, ArticleTagTableMap::COL_ARTICLE_ID, ],
        self::TYPE_FIELDNAME     => ['tag_id', 'article_id', ],
        self::TYPE_NUM           => [0, 1, ]
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
        self::TYPE_PHPNAME       => ['TagId' => 0, 'ArticleId' => 1, ],
        self::TYPE_CAMELNAME     => ['tagId' => 0, 'articleId' => 1, ],
        self::TYPE_COLNAME       => [ArticleTagTableMap::COL_TAG_ID => 0, ArticleTagTableMap::COL_ARTICLE_ID => 1, ],
        self::TYPE_FIELDNAME     => ['tag_id' => 0, 'article_id' => 1, ],
        self::TYPE_NUM           => [0, 1, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'TagId' => 'TAG_ID',
        'ArticleTag.TagId' => 'TAG_ID',
        'tagId' => 'TAG_ID',
        'articleTag.tagId' => 'TAG_ID',
        'ArticleTagTableMap::COL_TAG_ID' => 'TAG_ID',
        'COL_TAG_ID' => 'TAG_ID',
        'tag_id' => 'TAG_ID',
        'tags_articles.tag_id' => 'TAG_ID',
        'ArticleId' => 'ARTICLE_ID',
        'ArticleTag.ArticleId' => 'ARTICLE_ID',
        'articleId' => 'ARTICLE_ID',
        'articleTag.articleId' => 'ARTICLE_ID',
        'ArticleTagTableMap::COL_ARTICLE_ID' => 'ARTICLE_ID',
        'COL_ARTICLE_ID' => 'ARTICLE_ID',
        'article_id' => 'ARTICLE_ID',
        'tags_articles.article_id' => 'ARTICLE_ID',
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
        $this->setName('tags_articles');
        $this->setPhpName('ArticleTag');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\ArticleTag');
        $this->setPackage('Model');
        $this->setUseIdGenerator(false);
        $this->setIsCrossRef(true);
        // columns
        $this->addForeignPrimaryKey('tag_id', 'TagId', 'INTEGER' , 'tags', 'tag_id', true, null, null);
        $this->addForeignPrimaryKey('article_id', 'ArticleId', 'INTEGER' , 'articles', 'article_id', true, null, null);
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
        $this->addRelation('Tag', '\\Model\\Tag', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':tag_id',
    1 => ':tag_id',
  ),
), null, null, null, false);
    }

    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database. In some cases you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by find*()
     * and findPk*() calls.
     *
     * @param \Model\ArticleTag $obj A \Model\ArticleTag object.
     * @param string|null $key Key (optional) to use for instance map (for performance boost if key was already calculated externally).
     *
     * @return void
     */
    public static function addInstanceToPool(ArticleTag $obj, ?string $key = null): void
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (null === $key) {
                $key = serialize([(null === $obj->getTagId() || is_scalar($obj->getTagId()) || is_callable([$obj->getTagId(), '__toString']) ? (string) $obj->getTagId() : $obj->getTagId()), (null === $obj->getArticleId() || is_scalar($obj->getArticleId()) || is_callable([$obj->getArticleId(), '__toString']) ? (string) $obj->getArticleId() : $obj->getArticleId())]);
            } // if key === null
            self::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param mixed $value A \Model\ArticleTag object or a primary key value.
     *
     * @return void
     */
    public static function removeInstanceFromPool($value): void
    {
        if (Propel::isInstancePoolingEnabled() && null !== $value) {
            if (is_object($value) && $value instanceof \Model\ArticleTag) {
                $key = serialize([(null === $value->getTagId() || is_scalar($value->getTagId()) || is_callable([$value->getTagId(), '__toString']) ? (string) $value->getTagId() : $value->getTagId()), (null === $value->getArticleId() || is_scalar($value->getArticleId()) || is_callable([$value->getArticleId(), '__toString']) ? (string) $value->getArticleId() : $value->getArticleId())]);

            } elseif (is_array($value) && count($value) === 2) {
                // assume we've been passed a primary key";
                $key = serialize([(null === $value[0] || is_scalar($value[0]) || is_callable([$value[0], '__toString']) ? (string) $value[0] : $value[0]), (null === $value[1] || is_scalar($value[1]) || is_callable([$value[1], '__toString']) ? (string) $value[1] : $value[1])]);
            } elseif ($value instanceof Criteria) {
                self::$instances = [];

                return;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or \Model\ArticleTag object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value, true)));
                throw $e;
            }

            unset(self::$instances[$key]);
        }
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('TagId', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('ArticleId', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return serialize([(null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('TagId', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('TagId', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('TagId', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('TagId', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('TagId', TableMap::TYPE_PHPNAME, $indexType)]), (null === $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('ArticleId', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('ArticleId', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('ArticleId', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('ArticleId', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('ArticleId', TableMap::TYPE_PHPNAME, $indexType)])]);
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
            $pks = [];

        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('TagId', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 1 + $offset
                : self::translateFieldName('ArticleId', TableMap::TYPE_PHPNAME, $indexType)
        ];

        return $pks;
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
        return $withPrefix ? ArticleTagTableMap::CLASS_DEFAULT : ArticleTagTableMap::OM_CLASS;
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
     * @return array (ArticleTag object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = ArticleTagTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = ArticleTagTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + ArticleTagTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ArticleTagTableMap::OM_CLASS;
            /** @var ArticleTag $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            ArticleTagTableMap::addInstanceToPool($obj, $key);
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
            $key = ArticleTagTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = ArticleTagTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var ArticleTag $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ArticleTagTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(ArticleTagTableMap::COL_TAG_ID);
            $criteria->addSelectColumn(ArticleTagTableMap::COL_ARTICLE_ID);
        } else {
            $criteria->addSelectColumn($alias . '.tag_id');
            $criteria->addSelectColumn($alias . '.article_id');
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
            $criteria->removeSelectColumn(ArticleTagTableMap::COL_TAG_ID);
            $criteria->removeSelectColumn(ArticleTagTableMap::COL_ARTICLE_ID);
        } else {
            $criteria->removeSelectColumn($alias . '.tag_id');
            $criteria->removeSelectColumn($alias . '.article_id');
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
        return Propel::getServiceContainer()->getDatabaseMap(ArticleTagTableMap::DATABASE_NAME)->getTable(ArticleTagTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a ArticleTag or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or ArticleTag object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(ArticleTagTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\ArticleTag) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ArticleTagTableMap::DATABASE_NAME);
            // primary key is composite; we therefore, expect
            // the primary key passed to be an array of pkey values
            if (count($values) == count($values, COUNT_RECURSIVE)) {
                // array is not multi-dimensional
                $values = [$values];
            }
            foreach ($values as $value) {
                $criterion = $criteria->getNewCriterion(ArticleTagTableMap::COL_TAG_ID, $value[0]);
                $criterion->addAnd($criteria->getNewCriterion(ArticleTagTableMap::COL_ARTICLE_ID, $value[1]));
                $criteria->addOr($criterion);
            }
        }

        $query = ArticleTagQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            ArticleTagTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                ArticleTagTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the tags_articles table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return ArticleTagQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a ArticleTag or Criteria object.
     *
     * @param mixed $criteria Criteria or ArticleTag object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ArticleTagTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from ArticleTag object
        }


        // Set the correct dbName
        $query = ArticleTagQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
