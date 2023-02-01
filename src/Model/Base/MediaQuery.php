<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Media as ChildMedia;
use Model\MediaQuery as ChildMediaQuery;
use Model\Map\MediaTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `medias` table.
 *
 * @method     ChildMediaQuery orderById($order = Criteria::ASC) Order by the media_id column
 * @method     ChildMediaQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildMediaQuery orderByCategoryId($order = Criteria::ASC) Order by the category_id column
 * @method     ChildMediaQuery orderByDir($order = Criteria::ASC) Order by the media_dir column
 * @method     ChildMediaQuery orderByFile($order = Criteria::ASC) Order by the media_file column
 * @method     ChildMediaQuery orderByExt($order = Criteria::ASC) Order by the media_ext column
 * @method     ChildMediaQuery orderByTitle($order = Criteria::ASC) Order by the media_title column
 * @method     ChildMediaQuery orderByDesc($order = Criteria::ASC) Order by the media_desc column
 * @method     ChildMediaQuery orderByLink($order = Criteria::ASC) Order by the media_link column
 * @method     ChildMediaQuery orderByHeadline($order = Criteria::ASC) Order by the media_headline column
 * @method     ChildMediaQuery orderByInsert($order = Criteria::ASC) Order by the media_insert column
 * @method     ChildMediaQuery orderByUpdate($order = Criteria::ASC) Order by the media_update column
 * @method     ChildMediaQuery orderByCreatedAt($order = Criteria::ASC) Order by the media_created column
 * @method     ChildMediaQuery orderByUpdatedAt($order = Criteria::ASC) Order by the media_updated column
 *
 * @method     ChildMediaQuery groupById() Group by the media_id column
 * @method     ChildMediaQuery groupBySiteId() Group by the site_id column
 * @method     ChildMediaQuery groupByCategoryId() Group by the category_id column
 * @method     ChildMediaQuery groupByDir() Group by the media_dir column
 * @method     ChildMediaQuery groupByFile() Group by the media_file column
 * @method     ChildMediaQuery groupByExt() Group by the media_ext column
 * @method     ChildMediaQuery groupByTitle() Group by the media_title column
 * @method     ChildMediaQuery groupByDesc() Group by the media_desc column
 * @method     ChildMediaQuery groupByLink() Group by the media_link column
 * @method     ChildMediaQuery groupByHeadline() Group by the media_headline column
 * @method     ChildMediaQuery groupByInsert() Group by the media_insert column
 * @method     ChildMediaQuery groupByUpdate() Group by the media_update column
 * @method     ChildMediaQuery groupByCreatedAt() Group by the media_created column
 * @method     ChildMediaQuery groupByUpdatedAt() Group by the media_updated column
 *
 * @method     ChildMediaQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMediaQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMediaQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMediaQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildMediaQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildMediaQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildMedia|null findOne(?ConnectionInterface $con = null) Return the first ChildMedia matching the query
 * @method     ChildMedia findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildMedia matching the query, or a new ChildMedia object populated from the query conditions when no match is found
 *
 * @method     ChildMedia|null findOneById(int $media_id) Return the first ChildMedia filtered by the media_id column
 * @method     ChildMedia|null findOneBySiteId(int $site_id) Return the first ChildMedia filtered by the site_id column
 * @method     ChildMedia|null findOneByCategoryId(int $category_id) Return the first ChildMedia filtered by the category_id column
 * @method     ChildMedia|null findOneByDir(string $media_dir) Return the first ChildMedia filtered by the media_dir column
 * @method     ChildMedia|null findOneByFile(string $media_file) Return the first ChildMedia filtered by the media_file column
 * @method     ChildMedia|null findOneByExt(string $media_ext) Return the first ChildMedia filtered by the media_ext column
 * @method     ChildMedia|null findOneByTitle(string $media_title) Return the first ChildMedia filtered by the media_title column
 * @method     ChildMedia|null findOneByDesc(string $media_desc) Return the first ChildMedia filtered by the media_desc column
 * @method     ChildMedia|null findOneByLink(string $media_link) Return the first ChildMedia filtered by the media_link column
 * @method     ChildMedia|null findOneByHeadline(string $media_headline) Return the first ChildMedia filtered by the media_headline column
 * @method     ChildMedia|null findOneByInsert(string $media_insert) Return the first ChildMedia filtered by the media_insert column
 * @method     ChildMedia|null findOneByUpdate(string $media_update) Return the first ChildMedia filtered by the media_update column
 * @method     ChildMedia|null findOneByCreatedAt(string $media_created) Return the first ChildMedia filtered by the media_created column
 * @method     ChildMedia|null findOneByUpdatedAt(string $media_updated) Return the first ChildMedia filtered by the media_updated column
 *
 * @method     ChildMedia requirePk($key, ?ConnectionInterface $con = null) Return the ChildMedia by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMedia requireOne(?ConnectionInterface $con = null) Return the first ChildMedia matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMedia requireOneById(int $media_id) Return the first ChildMedia filtered by the media_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMedia requireOneBySiteId(int $site_id) Return the first ChildMedia filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMedia requireOneByCategoryId(int $category_id) Return the first ChildMedia filtered by the category_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMedia requireOneByDir(string $media_dir) Return the first ChildMedia filtered by the media_dir column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMedia requireOneByFile(string $media_file) Return the first ChildMedia filtered by the media_file column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMedia requireOneByExt(string $media_ext) Return the first ChildMedia filtered by the media_ext column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMedia requireOneByTitle(string $media_title) Return the first ChildMedia filtered by the media_title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMedia requireOneByDesc(string $media_desc) Return the first ChildMedia filtered by the media_desc column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMedia requireOneByLink(string $media_link) Return the first ChildMedia filtered by the media_link column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMedia requireOneByHeadline(string $media_headline) Return the first ChildMedia filtered by the media_headline column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMedia requireOneByInsert(string $media_insert) Return the first ChildMedia filtered by the media_insert column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMedia requireOneByUpdate(string $media_update) Return the first ChildMedia filtered by the media_update column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMedia requireOneByCreatedAt(string $media_created) Return the first ChildMedia filtered by the media_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMedia requireOneByUpdatedAt(string $media_updated) Return the first ChildMedia filtered by the media_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMedia[]|Collection find(?ConnectionInterface $con = null) Return ChildMedia objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildMedia> find(?ConnectionInterface $con = null) Return ChildMedia objects based on current ModelCriteria
 *
 * @method     ChildMedia[]|Collection findById(int|array<int> $media_id) Return ChildMedia objects filtered by the media_id column
 * @psalm-method Collection&\Traversable<ChildMedia> findById(int|array<int> $media_id) Return ChildMedia objects filtered by the media_id column
 * @method     ChildMedia[]|Collection findBySiteId(int|array<int> $site_id) Return ChildMedia objects filtered by the site_id column
 * @psalm-method Collection&\Traversable<ChildMedia> findBySiteId(int|array<int> $site_id) Return ChildMedia objects filtered by the site_id column
 * @method     ChildMedia[]|Collection findByCategoryId(int|array<int> $category_id) Return ChildMedia objects filtered by the category_id column
 * @psalm-method Collection&\Traversable<ChildMedia> findByCategoryId(int|array<int> $category_id) Return ChildMedia objects filtered by the category_id column
 * @method     ChildMedia[]|Collection findByDir(string|array<string> $media_dir) Return ChildMedia objects filtered by the media_dir column
 * @psalm-method Collection&\Traversable<ChildMedia> findByDir(string|array<string> $media_dir) Return ChildMedia objects filtered by the media_dir column
 * @method     ChildMedia[]|Collection findByFile(string|array<string> $media_file) Return ChildMedia objects filtered by the media_file column
 * @psalm-method Collection&\Traversable<ChildMedia> findByFile(string|array<string> $media_file) Return ChildMedia objects filtered by the media_file column
 * @method     ChildMedia[]|Collection findByExt(string|array<string> $media_ext) Return ChildMedia objects filtered by the media_ext column
 * @psalm-method Collection&\Traversable<ChildMedia> findByExt(string|array<string> $media_ext) Return ChildMedia objects filtered by the media_ext column
 * @method     ChildMedia[]|Collection findByTitle(string|array<string> $media_title) Return ChildMedia objects filtered by the media_title column
 * @psalm-method Collection&\Traversable<ChildMedia> findByTitle(string|array<string> $media_title) Return ChildMedia objects filtered by the media_title column
 * @method     ChildMedia[]|Collection findByDesc(string|array<string> $media_desc) Return ChildMedia objects filtered by the media_desc column
 * @psalm-method Collection&\Traversable<ChildMedia> findByDesc(string|array<string> $media_desc) Return ChildMedia objects filtered by the media_desc column
 * @method     ChildMedia[]|Collection findByLink(string|array<string> $media_link) Return ChildMedia objects filtered by the media_link column
 * @psalm-method Collection&\Traversable<ChildMedia> findByLink(string|array<string> $media_link) Return ChildMedia objects filtered by the media_link column
 * @method     ChildMedia[]|Collection findByHeadline(string|array<string> $media_headline) Return ChildMedia objects filtered by the media_headline column
 * @psalm-method Collection&\Traversable<ChildMedia> findByHeadline(string|array<string> $media_headline) Return ChildMedia objects filtered by the media_headline column
 * @method     ChildMedia[]|Collection findByInsert(string|array<string> $media_insert) Return ChildMedia objects filtered by the media_insert column
 * @psalm-method Collection&\Traversable<ChildMedia> findByInsert(string|array<string> $media_insert) Return ChildMedia objects filtered by the media_insert column
 * @method     ChildMedia[]|Collection findByUpdate(string|array<string> $media_update) Return ChildMedia objects filtered by the media_update column
 * @psalm-method Collection&\Traversable<ChildMedia> findByUpdate(string|array<string> $media_update) Return ChildMedia objects filtered by the media_update column
 * @method     ChildMedia[]|Collection findByCreatedAt(string|array<string> $media_created) Return ChildMedia objects filtered by the media_created column
 * @psalm-method Collection&\Traversable<ChildMedia> findByCreatedAt(string|array<string> $media_created) Return ChildMedia objects filtered by the media_created column
 * @method     ChildMedia[]|Collection findByUpdatedAt(string|array<string> $media_updated) Return ChildMedia objects filtered by the media_updated column
 * @psalm-method Collection&\Traversable<ChildMedia> findByUpdatedAt(string|array<string> $media_updated) Return ChildMedia objects filtered by the media_updated column
 *
 * @method     ChildMedia[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildMedia> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class MediaQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\MediaQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Media', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMediaQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMediaQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildMediaQuery) {
            return $criteria;
        }
        $query = new ChildMediaQuery();
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
     * @return ChildMedia|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MediaTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = MediaTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildMedia A model object, or null if the key is not found
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
            /** @var ChildMedia $obj */
            $obj = new ChildMedia();
            $obj->hydrate($row);
            MediaTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildMedia|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(MediaTableMap::COL_MEDIA_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(MediaTableMap::COL_MEDIA_ID, $keys, Criteria::IN);

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
                $this->addUsingAlias(MediaTableMap::COL_MEDIA_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(MediaTableMap::COL_MEDIA_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(MediaTableMap::COL_MEDIA_ID, $id, $comparison);

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
                $this->addUsingAlias(MediaTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(MediaTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(MediaTableMap::COL_SITE_ID, $siteId, $comparison);

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
                $this->addUsingAlias(MediaTableMap::COL_CATEGORY_ID, $categoryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($categoryId['max'])) {
                $this->addUsingAlias(MediaTableMap::COL_CATEGORY_ID, $categoryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(MediaTableMap::COL_CATEGORY_ID, $categoryId, $comparison);

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

        $this->addUsingAlias(MediaTableMap::COL_MEDIA_DIR, $dir, $comparison);

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

        $this->addUsingAlias(MediaTableMap::COL_MEDIA_FILE, $file, $comparison);

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

        $this->addUsingAlias(MediaTableMap::COL_MEDIA_EXT, $ext, $comparison);

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

        $this->addUsingAlias(MediaTableMap::COL_MEDIA_TITLE, $title, $comparison);

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

        $this->addUsingAlias(MediaTableMap::COL_MEDIA_DESC, $desc, $comparison);

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

        $this->addUsingAlias(MediaTableMap::COL_MEDIA_LINK, $link, $comparison);

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

        $this->addUsingAlias(MediaTableMap::COL_MEDIA_HEADLINE, $headline, $comparison);

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
                $this->addUsingAlias(MediaTableMap::COL_MEDIA_INSERT, $insert['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($insert['max'])) {
                $this->addUsingAlias(MediaTableMap::COL_MEDIA_INSERT, $insert['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(MediaTableMap::COL_MEDIA_INSERT, $insert, $comparison);

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
                $this->addUsingAlias(MediaTableMap::COL_MEDIA_UPDATE, $update['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($update['max'])) {
                $this->addUsingAlias(MediaTableMap::COL_MEDIA_UPDATE, $update['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(MediaTableMap::COL_MEDIA_UPDATE, $update, $comparison);

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
                $this->addUsingAlias(MediaTableMap::COL_MEDIA_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(MediaTableMap::COL_MEDIA_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(MediaTableMap::COL_MEDIA_CREATED, $createdAt, $comparison);

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
                $this->addUsingAlias(MediaTableMap::COL_MEDIA_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(MediaTableMap::COL_MEDIA_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(MediaTableMap::COL_MEDIA_UPDATED, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Exclude object from result
     *
     * @param ChildMedia $media Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($media = null)
    {
        if ($media) {
            $this->addUsingAlias(MediaTableMap::COL_MEDIA_ID, $media->getId(), Criteria::NOT_EQUAL);
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
            $con = Propel::getServiceContainer()->getWriteConnection(MediaTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MediaTableMap::clearInstancePool();
            MediaTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(MediaTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MediaTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            MediaTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MediaTableMap::clearRelatedInstancePool();

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
        $this->addUsingAlias(MediaTableMap::COL_MEDIA_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(MediaTableMap::COL_MEDIA_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(MediaTableMap::COL_MEDIA_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(MediaTableMap::COL_MEDIA_CREATED);

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
        $this->addUsingAlias(MediaTableMap::COL_MEDIA_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(MediaTableMap::COL_MEDIA_CREATED);

        return $this;
    }

}
