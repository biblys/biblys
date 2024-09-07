<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\MediaFile as ChildMediaFile;
use Model\MediaFileQuery as ChildMediaFileQuery;
use Model\Map\MediaFileTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `medias` table.
 *
 * @method     ChildMediaFileQuery orderById($order = Criteria::ASC) Order by the media_id column
 * @method     ChildMediaFileQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildMediaFileQuery orderByCategoryId($order = Criteria::ASC) Order by the category_id column
 * @method     ChildMediaFileQuery orderByDir($order = Criteria::ASC) Order by the media_dir column
 * @method     ChildMediaFileQuery orderByFile($order = Criteria::ASC) Order by the media_file column
 * @method     ChildMediaFileQuery orderByExt($order = Criteria::ASC) Order by the media_ext column
 * @method     ChildMediaFileQuery orderByTitle($order = Criteria::ASC) Order by the media_title column
 * @method     ChildMediaFileQuery orderByDesc($order = Criteria::ASC) Order by the media_desc column
 * @method     ChildMediaFileQuery orderByLink($order = Criteria::ASC) Order by the media_link column
 * @method     ChildMediaFileQuery orderByHeadline($order = Criteria::ASC) Order by the media_headline column
 * @method     ChildMediaFileQuery orderByInsert($order = Criteria::ASC) Order by the media_insert column
 * @method     ChildMediaFileQuery orderByUpdate($order = Criteria::ASC) Order by the media_update column
 * @method     ChildMediaFileQuery orderByCreatedAt($order = Criteria::ASC) Order by the media_created column
 * @method     ChildMediaFileQuery orderByUpdatedAt($order = Criteria::ASC) Order by the media_updated column
 *
 * @method     ChildMediaFileQuery groupById() Group by the media_id column
 * @method     ChildMediaFileQuery groupBySiteId() Group by the site_id column
 * @method     ChildMediaFileQuery groupByCategoryId() Group by the category_id column
 * @method     ChildMediaFileQuery groupByDir() Group by the media_dir column
 * @method     ChildMediaFileQuery groupByFile() Group by the media_file column
 * @method     ChildMediaFileQuery groupByExt() Group by the media_ext column
 * @method     ChildMediaFileQuery groupByTitle() Group by the media_title column
 * @method     ChildMediaFileQuery groupByDesc() Group by the media_desc column
 * @method     ChildMediaFileQuery groupByLink() Group by the media_link column
 * @method     ChildMediaFileQuery groupByHeadline() Group by the media_headline column
 * @method     ChildMediaFileQuery groupByInsert() Group by the media_insert column
 * @method     ChildMediaFileQuery groupByUpdate() Group by the media_update column
 * @method     ChildMediaFileQuery groupByCreatedAt() Group by the media_created column
 * @method     ChildMediaFileQuery groupByUpdatedAt() Group by the media_updated column
 *
 * @method     ChildMediaFileQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMediaFileQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMediaFileQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMediaFileQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildMediaFileQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildMediaFileQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildMediaFile|null findOne(?ConnectionInterface $con = null) Return the first ChildMediaFile matching the query
 * @method     ChildMediaFile findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildMediaFile matching the query, or a new ChildMediaFile object populated from the query conditions when no match is found
 *
 * @method     ChildMediaFile|null findOneById(int $media_id) Return the first ChildMediaFile filtered by the media_id column
 * @method     ChildMediaFile|null findOneBySiteId(int $site_id) Return the first ChildMediaFile filtered by the site_id column
 * @method     ChildMediaFile|null findOneByCategoryId(int $category_id) Return the first ChildMediaFile filtered by the category_id column
 * @method     ChildMediaFile|null findOneByDir(string $media_dir) Return the first ChildMediaFile filtered by the media_dir column
 * @method     ChildMediaFile|null findOneByFile(string $media_file) Return the first ChildMediaFile filtered by the media_file column
 * @method     ChildMediaFile|null findOneByExt(string $media_ext) Return the first ChildMediaFile filtered by the media_ext column
 * @method     ChildMediaFile|null findOneByTitle(string $media_title) Return the first ChildMediaFile filtered by the media_title column
 * @method     ChildMediaFile|null findOneByDesc(string $media_desc) Return the first ChildMediaFile filtered by the media_desc column
 * @method     ChildMediaFile|null findOneByLink(string $media_link) Return the first ChildMediaFile filtered by the media_link column
 * @method     ChildMediaFile|null findOneByHeadline(string $media_headline) Return the first ChildMediaFile filtered by the media_headline column
 * @method     ChildMediaFile|null findOneByInsert(string $media_insert) Return the first ChildMediaFile filtered by the media_insert column
 * @method     ChildMediaFile|null findOneByUpdate(string $media_update) Return the first ChildMediaFile filtered by the media_update column
 * @method     ChildMediaFile|null findOneByCreatedAt(string $media_created) Return the first ChildMediaFile filtered by the media_created column
 * @method     ChildMediaFile|null findOneByUpdatedAt(string $media_updated) Return the first ChildMediaFile filtered by the media_updated column
 *
 * @method     ChildMediaFile requirePk($key, ?ConnectionInterface $con = null) Return the ChildMediaFile by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMediaFile requireOne(?ConnectionInterface $con = null) Return the first ChildMediaFile matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMediaFile requireOneById(int $media_id) Return the first ChildMediaFile filtered by the media_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMediaFile requireOneBySiteId(int $site_id) Return the first ChildMediaFile filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMediaFile requireOneByCategoryId(int $category_id) Return the first ChildMediaFile filtered by the category_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMediaFile requireOneByDir(string $media_dir) Return the first ChildMediaFile filtered by the media_dir column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMediaFile requireOneByFile(string $media_file) Return the first ChildMediaFile filtered by the media_file column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMediaFile requireOneByExt(string $media_ext) Return the first ChildMediaFile filtered by the media_ext column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMediaFile requireOneByTitle(string $media_title) Return the first ChildMediaFile filtered by the media_title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMediaFile requireOneByDesc(string $media_desc) Return the first ChildMediaFile filtered by the media_desc column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMediaFile requireOneByLink(string $media_link) Return the first ChildMediaFile filtered by the media_link column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMediaFile requireOneByHeadline(string $media_headline) Return the first ChildMediaFile filtered by the media_headline column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMediaFile requireOneByInsert(string $media_insert) Return the first ChildMediaFile filtered by the media_insert column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMediaFile requireOneByUpdate(string $media_update) Return the first ChildMediaFile filtered by the media_update column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMediaFile requireOneByCreatedAt(string $media_created) Return the first ChildMediaFile filtered by the media_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMediaFile requireOneByUpdatedAt(string $media_updated) Return the first ChildMediaFile filtered by the media_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMediaFile[]|Collection find(?ConnectionInterface $con = null) Return ChildMediaFile objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildMediaFile> find(?ConnectionInterface $con = null) Return ChildMediaFile objects based on current ModelCriteria
 *
 * @method     ChildMediaFile[]|Collection findById(int|array<int> $media_id) Return ChildMediaFile objects filtered by the media_id column
 * @psalm-method Collection&\Traversable<ChildMediaFile> findById(int|array<int> $media_id) Return ChildMediaFile objects filtered by the media_id column
 * @method     ChildMediaFile[]|Collection findBySiteId(int|array<int> $site_id) Return ChildMediaFile objects filtered by the site_id column
 * @psalm-method Collection&\Traversable<ChildMediaFile> findBySiteId(int|array<int> $site_id) Return ChildMediaFile objects filtered by the site_id column
 * @method     ChildMediaFile[]|Collection findByCategoryId(int|array<int> $category_id) Return ChildMediaFile objects filtered by the category_id column
 * @psalm-method Collection&\Traversable<ChildMediaFile> findByCategoryId(int|array<int> $category_id) Return ChildMediaFile objects filtered by the category_id column
 * @method     ChildMediaFile[]|Collection findByDir(string|array<string> $media_dir) Return ChildMediaFile objects filtered by the media_dir column
 * @psalm-method Collection&\Traversable<ChildMediaFile> findByDir(string|array<string> $media_dir) Return ChildMediaFile objects filtered by the media_dir column
 * @method     ChildMediaFile[]|Collection findByFile(string|array<string> $media_file) Return ChildMediaFile objects filtered by the media_file column
 * @psalm-method Collection&\Traversable<ChildMediaFile> findByFile(string|array<string> $media_file) Return ChildMediaFile objects filtered by the media_file column
 * @method     ChildMediaFile[]|Collection findByExt(string|array<string> $media_ext) Return ChildMediaFile objects filtered by the media_ext column
 * @psalm-method Collection&\Traversable<ChildMediaFile> findByExt(string|array<string> $media_ext) Return ChildMediaFile objects filtered by the media_ext column
 * @method     ChildMediaFile[]|Collection findByTitle(string|array<string> $media_title) Return ChildMediaFile objects filtered by the media_title column
 * @psalm-method Collection&\Traversable<ChildMediaFile> findByTitle(string|array<string> $media_title) Return ChildMediaFile objects filtered by the media_title column
 * @method     ChildMediaFile[]|Collection findByDesc(string|array<string> $media_desc) Return ChildMediaFile objects filtered by the media_desc column
 * @psalm-method Collection&\Traversable<ChildMediaFile> findByDesc(string|array<string> $media_desc) Return ChildMediaFile objects filtered by the media_desc column
 * @method     ChildMediaFile[]|Collection findByLink(string|array<string> $media_link) Return ChildMediaFile objects filtered by the media_link column
 * @psalm-method Collection&\Traversable<ChildMediaFile> findByLink(string|array<string> $media_link) Return ChildMediaFile objects filtered by the media_link column
 * @method     ChildMediaFile[]|Collection findByHeadline(string|array<string> $media_headline) Return ChildMediaFile objects filtered by the media_headline column
 * @psalm-method Collection&\Traversable<ChildMediaFile> findByHeadline(string|array<string> $media_headline) Return ChildMediaFile objects filtered by the media_headline column
 * @method     ChildMediaFile[]|Collection findByInsert(string|array<string> $media_insert) Return ChildMediaFile objects filtered by the media_insert column
 * @psalm-method Collection&\Traversable<ChildMediaFile> findByInsert(string|array<string> $media_insert) Return ChildMediaFile objects filtered by the media_insert column
 * @method     ChildMediaFile[]|Collection findByUpdate(string|array<string> $media_update) Return ChildMediaFile objects filtered by the media_update column
 * @psalm-method Collection&\Traversable<ChildMediaFile> findByUpdate(string|array<string> $media_update) Return ChildMediaFile objects filtered by the media_update column
 * @method     ChildMediaFile[]|Collection findByCreatedAt(string|array<string> $media_created) Return ChildMediaFile objects filtered by the media_created column
 * @psalm-method Collection&\Traversable<ChildMediaFile> findByCreatedAt(string|array<string> $media_created) Return ChildMediaFile objects filtered by the media_created column
 * @method     ChildMediaFile[]|Collection findByUpdatedAt(string|array<string> $media_updated) Return ChildMediaFile objects filtered by the media_updated column
 * @psalm-method Collection&\Traversable<ChildMediaFile> findByUpdatedAt(string|array<string> $media_updated) Return ChildMediaFile objects filtered by the media_updated column
 *
 * @method     ChildMediaFile[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildMediaFile> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class MediaFileQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\MediaFileQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\MediaFile', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMediaFileQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMediaFileQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildMediaFileQuery) {
            return $criteria;
        }
        $query = new ChildMediaFileQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildMediaFile|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MediaFileTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = MediaFileTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMediaFile A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT media_id, site_id, category_id, media_dir, media_file, media_ext, media_title, media_desc, media_link, media_headline, media_insert, media_update, media_created, media_updated FROM medias WHERE media_id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildMediaFile $obj */
            $obj = new ChildMediaFile();
            $obj->hydrate($row);
            MediaFileTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @return ChildMediaFile|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param array $keys Primary keys to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return Collection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param mixed $key Primary key to use for the query
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        $this->addUsingAlias(MediaFileTableMap::COL_MEDIA_ID, $key, Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param array|int $keys The list of primary key to use for the query
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        $this->addUsingAlias(MediaFileTableMap::COL_MEDIA_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the media_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE media_id = 1234
     * $query->filterById(array(12, 34)); // WHERE media_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE media_id > 12
     * </code>
     *
     * @param mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterById($id = null, ?string $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(MediaFileTableMap::COL_MEDIA_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(MediaFileTableMap::COL_MEDIA_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(MediaFileTableMap::COL_MEDIA_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the site_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySiteId(1234); // WHERE site_id = 1234
     * $query->filterBySiteId(array(12, 34)); // WHERE site_id IN (12, 34)
     * $query->filterBySiteId(array('min' => 12)); // WHERE site_id > 12
     * </code>
     *
     * @param mixed $siteId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySiteId($siteId = null, ?string $comparison = null)
    {
        if (is_array($siteId)) {
            $useMinMax = false;
            if (isset($siteId['min'])) {
                $this->addUsingAlias(MediaFileTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(MediaFileTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(MediaFileTableMap::COL_SITE_ID, $siteId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the category_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCategoryId(1234); // WHERE category_id = 1234
     * $query->filterByCategoryId(array(12, 34)); // WHERE category_id IN (12, 34)
     * $query->filterByCategoryId(array('min' => 12)); // WHERE category_id > 12
     * </code>
     *
     * @param mixed $categoryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCategoryId($categoryId = null, ?string $comparison = null)
    {
        if (is_array($categoryId)) {
            $useMinMax = false;
            if (isset($categoryId['min'])) {
                $this->addUsingAlias(MediaFileTableMap::COL_CATEGORY_ID, $categoryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($categoryId['max'])) {
                $this->addUsingAlias(MediaFileTableMap::COL_CATEGORY_ID, $categoryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(MediaFileTableMap::COL_CATEGORY_ID, $categoryId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the media_dir column
     *
     * Example usage:
     * <code>
     * $query->filterByDir('fooValue');   // WHERE media_dir = 'fooValue'
     * $query->filterByDir('%fooValue%', Criteria::LIKE); // WHERE media_dir LIKE '%fooValue%'
     * $query->filterByDir(['foo', 'bar']); // WHERE media_dir IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $dir The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDir($dir = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($dir)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(MediaFileTableMap::COL_MEDIA_DIR, $dir, $comparison);

        return $this;
    }

    /**
     * Filter the query on the media_file column
     *
     * Example usage:
     * <code>
     * $query->filterByFile('fooValue');   // WHERE media_file = 'fooValue'
     * $query->filterByFile('%fooValue%', Criteria::LIKE); // WHERE media_file LIKE '%fooValue%'
     * $query->filterByFile(['foo', 'bar']); // WHERE media_file IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $file The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFile($file = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($file)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(MediaFileTableMap::COL_MEDIA_FILE, $file, $comparison);

        return $this;
    }

    /**
     * Filter the query on the media_ext column
     *
     * Example usage:
     * <code>
     * $query->filterByExt('fooValue');   // WHERE media_ext = 'fooValue'
     * $query->filterByExt('%fooValue%', Criteria::LIKE); // WHERE media_ext LIKE '%fooValue%'
     * $query->filterByExt(['foo', 'bar']); // WHERE media_ext IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $ext The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByExt($ext = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($ext)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(MediaFileTableMap::COL_MEDIA_EXT, $ext, $comparison);

        return $this;
    }

    /**
     * Filter the query on the media_title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE media_title = 'fooValue'
     * $query->filterByTitle('%fooValue%', Criteria::LIKE); // WHERE media_title LIKE '%fooValue%'
     * $query->filterByTitle(['foo', 'bar']); // WHERE media_title IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $title The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTitle($title = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(MediaFileTableMap::COL_MEDIA_TITLE, $title, $comparison);

        return $this;
    }

    /**
     * Filter the query on the media_desc column
     *
     * Example usage:
     * <code>
     * $query->filterByDesc('fooValue');   // WHERE media_desc = 'fooValue'
     * $query->filterByDesc('%fooValue%', Criteria::LIKE); // WHERE media_desc LIKE '%fooValue%'
     * $query->filterByDesc(['foo', 'bar']); // WHERE media_desc IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $desc The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDesc($desc = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($desc)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(MediaFileTableMap::COL_MEDIA_DESC, $desc, $comparison);

        return $this;
    }

    /**
     * Filter the query on the media_link column
     *
     * Example usage:
     * <code>
     * $query->filterByLink('fooValue');   // WHERE media_link = 'fooValue'
     * $query->filterByLink('%fooValue%', Criteria::LIKE); // WHERE media_link LIKE '%fooValue%'
     * $query->filterByLink(['foo', 'bar']); // WHERE media_link IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $link The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByLink($link = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($link)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(MediaFileTableMap::COL_MEDIA_LINK, $link, $comparison);

        return $this;
    }

    /**
     * Filter the query on the media_headline column
     *
     * Example usage:
     * <code>
     * $query->filterByHeadline('fooValue');   // WHERE media_headline = 'fooValue'
     * $query->filterByHeadline('%fooValue%', Criteria::LIKE); // WHERE media_headline LIKE '%fooValue%'
     * $query->filterByHeadline(['foo', 'bar']); // WHERE media_headline IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $headline The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByHeadline($headline = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($headline)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(MediaFileTableMap::COL_MEDIA_HEADLINE, $headline, $comparison);

        return $this;
    }

    /**
     * Filter the query on the media_insert column
     *
     * Example usage:
     * <code>
     * $query->filterByInsert('2011-03-14'); // WHERE media_insert = '2011-03-14'
     * $query->filterByInsert('now'); // WHERE media_insert = '2011-03-14'
     * $query->filterByInsert(array('max' => 'yesterday')); // WHERE media_insert > '2011-03-13'
     * </code>
     *
     * @param mixed $insert The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByInsert($insert = null, ?string $comparison = null)
    {
        if (is_array($insert)) {
            $useMinMax = false;
            if (isset($insert['min'])) {
                $this->addUsingAlias(MediaFileTableMap::COL_MEDIA_INSERT, $insert['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($insert['max'])) {
                $this->addUsingAlias(MediaFileTableMap::COL_MEDIA_INSERT, $insert['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(MediaFileTableMap::COL_MEDIA_INSERT, $insert, $comparison);

        return $this;
    }

    /**
     * Filter the query on the media_update column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdate('2011-03-14'); // WHERE media_update = '2011-03-14'
     * $query->filterByUpdate('now'); // WHERE media_update = '2011-03-14'
     * $query->filterByUpdate(array('max' => 'yesterday')); // WHERE media_update > '2011-03-13'
     * </code>
     *
     * @param mixed $update The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUpdate($update = null, ?string $comparison = null)
    {
        if (is_array($update)) {
            $useMinMax = false;
            if (isset($update['min'])) {
                $this->addUsingAlias(MediaFileTableMap::COL_MEDIA_UPDATE, $update['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($update['max'])) {
                $this->addUsingAlias(MediaFileTableMap::COL_MEDIA_UPDATE, $update['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(MediaFileTableMap::COL_MEDIA_UPDATE, $update, $comparison);

        return $this;
    }

    /**
     * Filter the query on the media_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE media_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE media_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE media_created > '2011-03-13'
     * </code>
     *
     * @param mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, ?string $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(MediaFileTableMap::COL_MEDIA_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(MediaFileTableMap::COL_MEDIA_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(MediaFileTableMap::COL_MEDIA_CREATED, $createdAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the media_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE media_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE media_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE media_updated > '2011-03-13'
     * </code>
     *
     * @param mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, ?string $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(MediaFileTableMap::COL_MEDIA_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(MediaFileTableMap::COL_MEDIA_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(MediaFileTableMap::COL_MEDIA_UPDATED, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Exclude object from result
     *
     * @param ChildMediaFile $mediaFile Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($mediaFile = null)
    {
        if ($mediaFile) {
            $this->addUsingAlias(MediaFileTableMap::COL_MEDIA_ID, $mediaFile->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the medias table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MediaFileTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MediaFileTableMap::clearInstancePool();
            MediaFileTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MediaFileTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MediaFileTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            MediaFileTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MediaFileTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param int $nbDays Maximum age of the latest update in days
     *
     * @return $this The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        $this->addUsingAlias(MediaFileTableMap::COL_MEDIA_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(MediaFileTableMap::COL_MEDIA_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(MediaFileTableMap::COL_MEDIA_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(MediaFileTableMap::COL_MEDIA_CREATED);

        return $this;
    }

    /**
     * Filter by the latest created
     *
     * @param int $nbDays Maximum age of in days
     *
     * @return $this The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        $this->addUsingAlias(MediaFileTableMap::COL_MEDIA_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(MediaFileTableMap::COL_MEDIA_CREATED);

        return $this;
    }

}
