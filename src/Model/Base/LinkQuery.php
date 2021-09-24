<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Link as ChildLink;
use Model\LinkQuery as ChildLinkQuery;
use Model\Map\LinkTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'links' table.
 *
 *
 *
 * @method     ChildLinkQuery orderById($order = Criteria::ASC) Order by the link_id column
 * @method     ChildLinkQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildLinkQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildLinkQuery orderByArticleId($order = Criteria::ASC) Order by the article_id column
 * @method     ChildLinkQuery orderByStockId($order = Criteria::ASC) Order by the stock_id column
 * @method     ChildLinkQuery orderByListId($order = Criteria::ASC) Order by the list_id column
 * @method     ChildLinkQuery orderByBookId($order = Criteria::ASC) Order by the book_id column
 * @method     ChildLinkQuery orderByPeopleId($order = Criteria::ASC) Order by the people_id column
 * @method     ChildLinkQuery orderByJobId($order = Criteria::ASC) Order by the job_id column
 * @method     ChildLinkQuery orderByRayonId($order = Criteria::ASC) Order by the rayon_id column
 * @method     ChildLinkQuery orderByTagId($order = Criteria::ASC) Order by the tag_id column
 * @method     ChildLinkQuery orderByEventId($order = Criteria::ASC) Order by the event_id column
 * @method     ChildLinkQuery orderByPostId($order = Criteria::ASC) Order by the post_id column
 * @method     ChildLinkQuery orderByCollectionId($order = Criteria::ASC) Order by the collection_id column
 * @method     ChildLinkQuery orderByPublisherId($order = Criteria::ASC) Order by the publisher_id column
 * @method     ChildLinkQuery orderBySupplierId($order = Criteria::ASC) Order by the supplier_id column
 * @method     ChildLinkQuery orderByMediaId($order = Criteria::ASC) Order by the media_id column
 * @method     ChildLinkQuery orderByBundleId($order = Criteria::ASC) Order by the bundle_id column
 * @method     ChildLinkQuery orderByHide($order = Criteria::ASC) Order by the link_hide column
 * @method     ChildLinkQuery orderByDoNotReorder($order = Criteria::ASC) Order by the link_do_not_reorder column
 * @method     ChildLinkQuery orderBySponsorUserId($order = Criteria::ASC) Order by the link_sponsor_user_id column
 * @method     ChildLinkQuery orderByDate($order = Criteria::ASC) Order by the link_date column
 * @method     ChildLinkQuery orderByCreatedAt($order = Criteria::ASC) Order by the link_created column
 * @method     ChildLinkQuery orderByUpdatedAt($order = Criteria::ASC) Order by the link_updated column
 * @method     ChildLinkQuery orderByDeletedAt($order = Criteria::ASC) Order by the link_deleted column
 *
 * @method     ChildLinkQuery groupById() Group by the link_id column
 * @method     ChildLinkQuery groupBySiteId() Group by the site_id column
 * @method     ChildLinkQuery groupByUserId() Group by the user_id column
 * @method     ChildLinkQuery groupByArticleId() Group by the article_id column
 * @method     ChildLinkQuery groupByStockId() Group by the stock_id column
 * @method     ChildLinkQuery groupByListId() Group by the list_id column
 * @method     ChildLinkQuery groupByBookId() Group by the book_id column
 * @method     ChildLinkQuery groupByPeopleId() Group by the people_id column
 * @method     ChildLinkQuery groupByJobId() Group by the job_id column
 * @method     ChildLinkQuery groupByRayonId() Group by the rayon_id column
 * @method     ChildLinkQuery groupByTagId() Group by the tag_id column
 * @method     ChildLinkQuery groupByEventId() Group by the event_id column
 * @method     ChildLinkQuery groupByPostId() Group by the post_id column
 * @method     ChildLinkQuery groupByCollectionId() Group by the collection_id column
 * @method     ChildLinkQuery groupByPublisherId() Group by the publisher_id column
 * @method     ChildLinkQuery groupBySupplierId() Group by the supplier_id column
 * @method     ChildLinkQuery groupByMediaId() Group by the media_id column
 * @method     ChildLinkQuery groupByBundleId() Group by the bundle_id column
 * @method     ChildLinkQuery groupByHide() Group by the link_hide column
 * @method     ChildLinkQuery groupByDoNotReorder() Group by the link_do_not_reorder column
 * @method     ChildLinkQuery groupBySponsorUserId() Group by the link_sponsor_user_id column
 * @method     ChildLinkQuery groupByDate() Group by the link_date column
 * @method     ChildLinkQuery groupByCreatedAt() Group by the link_created column
 * @method     ChildLinkQuery groupByUpdatedAt() Group by the link_updated column
 * @method     ChildLinkQuery groupByDeletedAt() Group by the link_deleted column
 *
 * @method     ChildLinkQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLinkQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLinkQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLinkQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildLinkQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildLinkQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildLink|null findOne(ConnectionInterface $con = null) Return the first ChildLink matching the query
 * @method     ChildLink findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLink matching the query, or a new ChildLink object populated from the query conditions when no match is found
 *
 * @method     ChildLink|null findOneById(int $link_id) Return the first ChildLink filtered by the link_id column
 * @method     ChildLink|null findOneBySiteId(int $site_id) Return the first ChildLink filtered by the site_id column
 * @method     ChildLink|null findOneByUserId(int $user_id) Return the first ChildLink filtered by the user_id column
 * @method     ChildLink|null findOneByArticleId(int $article_id) Return the first ChildLink filtered by the article_id column
 * @method     ChildLink|null findOneByStockId(int $stock_id) Return the first ChildLink filtered by the stock_id column
 * @method     ChildLink|null findOneByListId(int $list_id) Return the first ChildLink filtered by the list_id column
 * @method     ChildLink|null findOneByBookId(int $book_id) Return the first ChildLink filtered by the book_id column
 * @method     ChildLink|null findOneByPeopleId(int $people_id) Return the first ChildLink filtered by the people_id column
 * @method     ChildLink|null findOneByJobId(int $job_id) Return the first ChildLink filtered by the job_id column
 * @method     ChildLink|null findOneByRayonId(int $rayon_id) Return the first ChildLink filtered by the rayon_id column
 * @method     ChildLink|null findOneByTagId(int $tag_id) Return the first ChildLink filtered by the tag_id column
 * @method     ChildLink|null findOneByEventId(int $event_id) Return the first ChildLink filtered by the event_id column
 * @method     ChildLink|null findOneByPostId(int $post_id) Return the first ChildLink filtered by the post_id column
 * @method     ChildLink|null findOneByCollectionId(int $collection_id) Return the first ChildLink filtered by the collection_id column
 * @method     ChildLink|null findOneByPublisherId(int $publisher_id) Return the first ChildLink filtered by the publisher_id column
 * @method     ChildLink|null findOneBySupplierId(int $supplier_id) Return the first ChildLink filtered by the supplier_id column
 * @method     ChildLink|null findOneByMediaId(int $media_id) Return the first ChildLink filtered by the media_id column
 * @method     ChildLink|null findOneByBundleId(int $bundle_id) Return the first ChildLink filtered by the bundle_id column
 * @method     ChildLink|null findOneByHide(boolean $link_hide) Return the first ChildLink filtered by the link_hide column
 * @method     ChildLink|null findOneByDoNotReorder(boolean $link_do_not_reorder) Return the first ChildLink filtered by the link_do_not_reorder column
 * @method     ChildLink|null findOneBySponsorUserId(int $link_sponsor_user_id) Return the first ChildLink filtered by the link_sponsor_user_id column
 * @method     ChildLink|null findOneByDate(string $link_date) Return the first ChildLink filtered by the link_date column
 * @method     ChildLink|null findOneByCreatedAt(string $link_created) Return the first ChildLink filtered by the link_created column
 * @method     ChildLink|null findOneByUpdatedAt(string $link_updated) Return the first ChildLink filtered by the link_updated column
 * @method     ChildLink|null findOneByDeletedAt(string $link_deleted) Return the first ChildLink filtered by the link_deleted column *

 * @method     ChildLink requirePk($key, ConnectionInterface $con = null) Return the ChildLink by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLink requireOne(ConnectionInterface $con = null) Return the first ChildLink matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLink requireOneById(int $link_id) Return the first ChildLink filtered by the link_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLink requireOneBySiteId(int $site_id) Return the first ChildLink filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLink requireOneByUserId(int $user_id) Return the first ChildLink filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLink requireOneByArticleId(int $article_id) Return the first ChildLink filtered by the article_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLink requireOneByStockId(int $stock_id) Return the first ChildLink filtered by the stock_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLink requireOneByListId(int $list_id) Return the first ChildLink filtered by the list_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLink requireOneByBookId(int $book_id) Return the first ChildLink filtered by the book_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLink requireOneByPeopleId(int $people_id) Return the first ChildLink filtered by the people_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLink requireOneByJobId(int $job_id) Return the first ChildLink filtered by the job_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLink requireOneByRayonId(int $rayon_id) Return the first ChildLink filtered by the rayon_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLink requireOneByTagId(int $tag_id) Return the first ChildLink filtered by the tag_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLink requireOneByEventId(int $event_id) Return the first ChildLink filtered by the event_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLink requireOneByPostId(int $post_id) Return the first ChildLink filtered by the post_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLink requireOneByCollectionId(int $collection_id) Return the first ChildLink filtered by the collection_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLink requireOneByPublisherId(int $publisher_id) Return the first ChildLink filtered by the publisher_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLink requireOneBySupplierId(int $supplier_id) Return the first ChildLink filtered by the supplier_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLink requireOneByMediaId(int $media_id) Return the first ChildLink filtered by the media_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLink requireOneByBundleId(int $bundle_id) Return the first ChildLink filtered by the bundle_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLink requireOneByHide(boolean $link_hide) Return the first ChildLink filtered by the link_hide column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLink requireOneByDoNotReorder(boolean $link_do_not_reorder) Return the first ChildLink filtered by the link_do_not_reorder column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLink requireOneBySponsorUserId(int $link_sponsor_user_id) Return the first ChildLink filtered by the link_sponsor_user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLink requireOneByDate(string $link_date) Return the first ChildLink filtered by the link_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLink requireOneByCreatedAt(string $link_created) Return the first ChildLink filtered by the link_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLink requireOneByUpdatedAt(string $link_updated) Return the first ChildLink filtered by the link_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLink requireOneByDeletedAt(string $link_deleted) Return the first ChildLink filtered by the link_deleted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLink[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildLink objects based on current ModelCriteria
 * @psalm-method ObjectCollection&\Traversable<ChildLink> find(ConnectionInterface $con = null) Return ChildLink objects based on current ModelCriteria
 * @method     ChildLink[]|ObjectCollection findById(int $link_id) Return ChildLink objects filtered by the link_id column
 * @psalm-method ObjectCollection&\Traversable<ChildLink> findById(int $link_id) Return ChildLink objects filtered by the link_id column
 * @method     ChildLink[]|ObjectCollection findBySiteId(int $site_id) Return ChildLink objects filtered by the site_id column
 * @psalm-method ObjectCollection&\Traversable<ChildLink> findBySiteId(int $site_id) Return ChildLink objects filtered by the site_id column
 * @method     ChildLink[]|ObjectCollection findByUserId(int $user_id) Return ChildLink objects filtered by the user_id column
 * @psalm-method ObjectCollection&\Traversable<ChildLink> findByUserId(int $user_id) Return ChildLink objects filtered by the user_id column
 * @method     ChildLink[]|ObjectCollection findByArticleId(int $article_id) Return ChildLink objects filtered by the article_id column
 * @psalm-method ObjectCollection&\Traversable<ChildLink> findByArticleId(int $article_id) Return ChildLink objects filtered by the article_id column
 * @method     ChildLink[]|ObjectCollection findByStockId(int $stock_id) Return ChildLink objects filtered by the stock_id column
 * @psalm-method ObjectCollection&\Traversable<ChildLink> findByStockId(int $stock_id) Return ChildLink objects filtered by the stock_id column
 * @method     ChildLink[]|ObjectCollection findByListId(int $list_id) Return ChildLink objects filtered by the list_id column
 * @psalm-method ObjectCollection&\Traversable<ChildLink> findByListId(int $list_id) Return ChildLink objects filtered by the list_id column
 * @method     ChildLink[]|ObjectCollection findByBookId(int $book_id) Return ChildLink objects filtered by the book_id column
 * @psalm-method ObjectCollection&\Traversable<ChildLink> findByBookId(int $book_id) Return ChildLink objects filtered by the book_id column
 * @method     ChildLink[]|ObjectCollection findByPeopleId(int $people_id) Return ChildLink objects filtered by the people_id column
 * @psalm-method ObjectCollection&\Traversable<ChildLink> findByPeopleId(int $people_id) Return ChildLink objects filtered by the people_id column
 * @method     ChildLink[]|ObjectCollection findByJobId(int $job_id) Return ChildLink objects filtered by the job_id column
 * @psalm-method ObjectCollection&\Traversable<ChildLink> findByJobId(int $job_id) Return ChildLink objects filtered by the job_id column
 * @method     ChildLink[]|ObjectCollection findByRayonId(int $rayon_id) Return ChildLink objects filtered by the rayon_id column
 * @psalm-method ObjectCollection&\Traversable<ChildLink> findByRayonId(int $rayon_id) Return ChildLink objects filtered by the rayon_id column
 * @method     ChildLink[]|ObjectCollection findByTagId(int $tag_id) Return ChildLink objects filtered by the tag_id column
 * @psalm-method ObjectCollection&\Traversable<ChildLink> findByTagId(int $tag_id) Return ChildLink objects filtered by the tag_id column
 * @method     ChildLink[]|ObjectCollection findByEventId(int $event_id) Return ChildLink objects filtered by the event_id column
 * @psalm-method ObjectCollection&\Traversable<ChildLink> findByEventId(int $event_id) Return ChildLink objects filtered by the event_id column
 * @method     ChildLink[]|ObjectCollection findByPostId(int $post_id) Return ChildLink objects filtered by the post_id column
 * @psalm-method ObjectCollection&\Traversable<ChildLink> findByPostId(int $post_id) Return ChildLink objects filtered by the post_id column
 * @method     ChildLink[]|ObjectCollection findByCollectionId(int $collection_id) Return ChildLink objects filtered by the collection_id column
 * @psalm-method ObjectCollection&\Traversable<ChildLink> findByCollectionId(int $collection_id) Return ChildLink objects filtered by the collection_id column
 * @method     ChildLink[]|ObjectCollection findByPublisherId(int $publisher_id) Return ChildLink objects filtered by the publisher_id column
 * @psalm-method ObjectCollection&\Traversable<ChildLink> findByPublisherId(int $publisher_id) Return ChildLink objects filtered by the publisher_id column
 * @method     ChildLink[]|ObjectCollection findBySupplierId(int $supplier_id) Return ChildLink objects filtered by the supplier_id column
 * @psalm-method ObjectCollection&\Traversable<ChildLink> findBySupplierId(int $supplier_id) Return ChildLink objects filtered by the supplier_id column
 * @method     ChildLink[]|ObjectCollection findByMediaId(int $media_id) Return ChildLink objects filtered by the media_id column
 * @psalm-method ObjectCollection&\Traversable<ChildLink> findByMediaId(int $media_id) Return ChildLink objects filtered by the media_id column
 * @method     ChildLink[]|ObjectCollection findByBundleId(int $bundle_id) Return ChildLink objects filtered by the bundle_id column
 * @psalm-method ObjectCollection&\Traversable<ChildLink> findByBundleId(int $bundle_id) Return ChildLink objects filtered by the bundle_id column
 * @method     ChildLink[]|ObjectCollection findByHide(boolean $link_hide) Return ChildLink objects filtered by the link_hide column
 * @psalm-method ObjectCollection&\Traversable<ChildLink> findByHide(boolean $link_hide) Return ChildLink objects filtered by the link_hide column
 * @method     ChildLink[]|ObjectCollection findByDoNotReorder(boolean $link_do_not_reorder) Return ChildLink objects filtered by the link_do_not_reorder column
 * @psalm-method ObjectCollection&\Traversable<ChildLink> findByDoNotReorder(boolean $link_do_not_reorder) Return ChildLink objects filtered by the link_do_not_reorder column
 * @method     ChildLink[]|ObjectCollection findBySponsorUserId(int $link_sponsor_user_id) Return ChildLink objects filtered by the link_sponsor_user_id column
 * @psalm-method ObjectCollection&\Traversable<ChildLink> findBySponsorUserId(int $link_sponsor_user_id) Return ChildLink objects filtered by the link_sponsor_user_id column
 * @method     ChildLink[]|ObjectCollection findByDate(string $link_date) Return ChildLink objects filtered by the link_date column
 * @psalm-method ObjectCollection&\Traversable<ChildLink> findByDate(string $link_date) Return ChildLink objects filtered by the link_date column
 * @method     ChildLink[]|ObjectCollection findByCreatedAt(string $link_created) Return ChildLink objects filtered by the link_created column
 * @psalm-method ObjectCollection&\Traversable<ChildLink> findByCreatedAt(string $link_created) Return ChildLink objects filtered by the link_created column
 * @method     ChildLink[]|ObjectCollection findByUpdatedAt(string $link_updated) Return ChildLink objects filtered by the link_updated column
 * @psalm-method ObjectCollection&\Traversable<ChildLink> findByUpdatedAt(string $link_updated) Return ChildLink objects filtered by the link_updated column
 * @method     ChildLink[]|ObjectCollection findByDeletedAt(string $link_deleted) Return ChildLink objects filtered by the link_deleted column
 * @psalm-method ObjectCollection&\Traversable<ChildLink> findByDeletedAt(string $link_deleted) Return ChildLink objects filtered by the link_deleted column
 * @method     ChildLink[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildLink> paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class LinkQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\LinkQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Link', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLinkQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLinkQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildLinkQuery) {
            return $criteria;
        }
        $query = new ChildLinkQuery();
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
     * @return ChildLink|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LinkTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = LinkTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLink A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT link_id, site_id, user_id, article_id, stock_id, list_id, book_id, people_id, job_id, rayon_id, tag_id, event_id, post_id, collection_id, publisher_id, supplier_id, media_id, bundle_id, link_hide, link_do_not_reorder, link_sponsor_user_id, link_date, link_created, link_updated, link_deleted FROM links WHERE link_id = :p0';
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
            /** @var ChildLink $obj */
            $obj = new ChildLink();
            $obj->hydrate($row);
            LinkTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildLink|array|mixed the result, formatted by the current formatter
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
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
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
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildLinkQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(LinkTableMap::COL_LINK_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildLinkQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(LinkTableMap::COL_LINK_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the link_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE link_id = 1234
     * $query->filterById(array(12, 34)); // WHERE link_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE link_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLinkQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(LinkTableMap::COL_LINK_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(LinkTableMap::COL_LINK_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LinkTableMap::COL_LINK_ID, $id, $comparison);
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
     * @param     mixed $siteId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLinkQuery The current query, for fluid interface
     */
    public function filterBySiteId($siteId = null, $comparison = null)
    {
        if (is_array($siteId)) {
            $useMinMax = false;
            if (isset($siteId['min'])) {
                $this->addUsingAlias(LinkTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(LinkTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LinkTableMap::COL_SITE_ID, $siteId, $comparison);
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE user_id = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE user_id > 12
     * </code>
     *
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLinkQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(LinkTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(LinkTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LinkTableMap::COL_USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the article_id column
     *
     * Example usage:
     * <code>
     * $query->filterByArticleId(1234); // WHERE article_id = 1234
     * $query->filterByArticleId(array(12, 34)); // WHERE article_id IN (12, 34)
     * $query->filterByArticleId(array('min' => 12)); // WHERE article_id > 12
     * </code>
     *
     * @param     mixed $articleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLinkQuery The current query, for fluid interface
     */
    public function filterByArticleId($articleId = null, $comparison = null)
    {
        if (is_array($articleId)) {
            $useMinMax = false;
            if (isset($articleId['min'])) {
                $this->addUsingAlias(LinkTableMap::COL_ARTICLE_ID, $articleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($articleId['max'])) {
                $this->addUsingAlias(LinkTableMap::COL_ARTICLE_ID, $articleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LinkTableMap::COL_ARTICLE_ID, $articleId, $comparison);
    }

    /**
     * Filter the query on the stock_id column
     *
     * Example usage:
     * <code>
     * $query->filterByStockId(1234); // WHERE stock_id = 1234
     * $query->filterByStockId(array(12, 34)); // WHERE stock_id IN (12, 34)
     * $query->filterByStockId(array('min' => 12)); // WHERE stock_id > 12
     * </code>
     *
     * @param     mixed $stockId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLinkQuery The current query, for fluid interface
     */
    public function filterByStockId($stockId = null, $comparison = null)
    {
        if (is_array($stockId)) {
            $useMinMax = false;
            if (isset($stockId['min'])) {
                $this->addUsingAlias(LinkTableMap::COL_STOCK_ID, $stockId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($stockId['max'])) {
                $this->addUsingAlias(LinkTableMap::COL_STOCK_ID, $stockId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LinkTableMap::COL_STOCK_ID, $stockId, $comparison);
    }

    /**
     * Filter the query on the list_id column
     *
     * Example usage:
     * <code>
     * $query->filterByListId(1234); // WHERE list_id = 1234
     * $query->filterByListId(array(12, 34)); // WHERE list_id IN (12, 34)
     * $query->filterByListId(array('min' => 12)); // WHERE list_id > 12
     * </code>
     *
     * @param     mixed $listId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLinkQuery The current query, for fluid interface
     */
    public function filterByListId($listId = null, $comparison = null)
    {
        if (is_array($listId)) {
            $useMinMax = false;
            if (isset($listId['min'])) {
                $this->addUsingAlias(LinkTableMap::COL_LIST_ID, $listId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($listId['max'])) {
                $this->addUsingAlias(LinkTableMap::COL_LIST_ID, $listId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LinkTableMap::COL_LIST_ID, $listId, $comparison);
    }

    /**
     * Filter the query on the book_id column
     *
     * Example usage:
     * <code>
     * $query->filterByBookId(1234); // WHERE book_id = 1234
     * $query->filterByBookId(array(12, 34)); // WHERE book_id IN (12, 34)
     * $query->filterByBookId(array('min' => 12)); // WHERE book_id > 12
     * </code>
     *
     * @param     mixed $bookId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLinkQuery The current query, for fluid interface
     */
    public function filterByBookId($bookId = null, $comparison = null)
    {
        if (is_array($bookId)) {
            $useMinMax = false;
            if (isset($bookId['min'])) {
                $this->addUsingAlias(LinkTableMap::COL_BOOK_ID, $bookId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bookId['max'])) {
                $this->addUsingAlias(LinkTableMap::COL_BOOK_ID, $bookId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LinkTableMap::COL_BOOK_ID, $bookId, $comparison);
    }

    /**
     * Filter the query on the people_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPeopleId(1234); // WHERE people_id = 1234
     * $query->filterByPeopleId(array(12, 34)); // WHERE people_id IN (12, 34)
     * $query->filterByPeopleId(array('min' => 12)); // WHERE people_id > 12
     * </code>
     *
     * @param     mixed $peopleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLinkQuery The current query, for fluid interface
     */
    public function filterByPeopleId($peopleId = null, $comparison = null)
    {
        if (is_array($peopleId)) {
            $useMinMax = false;
            if (isset($peopleId['min'])) {
                $this->addUsingAlias(LinkTableMap::COL_PEOPLE_ID, $peopleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($peopleId['max'])) {
                $this->addUsingAlias(LinkTableMap::COL_PEOPLE_ID, $peopleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LinkTableMap::COL_PEOPLE_ID, $peopleId, $comparison);
    }

    /**
     * Filter the query on the job_id column
     *
     * Example usage:
     * <code>
     * $query->filterByJobId(1234); // WHERE job_id = 1234
     * $query->filterByJobId(array(12, 34)); // WHERE job_id IN (12, 34)
     * $query->filterByJobId(array('min' => 12)); // WHERE job_id > 12
     * </code>
     *
     * @param     mixed $jobId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLinkQuery The current query, for fluid interface
     */
    public function filterByJobId($jobId = null, $comparison = null)
    {
        if (is_array($jobId)) {
            $useMinMax = false;
            if (isset($jobId['min'])) {
                $this->addUsingAlias(LinkTableMap::COL_JOB_ID, $jobId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($jobId['max'])) {
                $this->addUsingAlias(LinkTableMap::COL_JOB_ID, $jobId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LinkTableMap::COL_JOB_ID, $jobId, $comparison);
    }

    /**
     * Filter the query on the rayon_id column
     *
     * Example usage:
     * <code>
     * $query->filterByRayonId(1234); // WHERE rayon_id = 1234
     * $query->filterByRayonId(array(12, 34)); // WHERE rayon_id IN (12, 34)
     * $query->filterByRayonId(array('min' => 12)); // WHERE rayon_id > 12
     * </code>
     *
     * @param     mixed $rayonId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLinkQuery The current query, for fluid interface
     */
    public function filterByRayonId($rayonId = null, $comparison = null)
    {
        if (is_array($rayonId)) {
            $useMinMax = false;
            if (isset($rayonId['min'])) {
                $this->addUsingAlias(LinkTableMap::COL_RAYON_ID, $rayonId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($rayonId['max'])) {
                $this->addUsingAlias(LinkTableMap::COL_RAYON_ID, $rayonId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LinkTableMap::COL_RAYON_ID, $rayonId, $comparison);
    }

    /**
     * Filter the query on the tag_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTagId(1234); // WHERE tag_id = 1234
     * $query->filterByTagId(array(12, 34)); // WHERE tag_id IN (12, 34)
     * $query->filterByTagId(array('min' => 12)); // WHERE tag_id > 12
     * </code>
     *
     * @param     mixed $tagId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLinkQuery The current query, for fluid interface
     */
    public function filterByTagId($tagId = null, $comparison = null)
    {
        if (is_array($tagId)) {
            $useMinMax = false;
            if (isset($tagId['min'])) {
                $this->addUsingAlias(LinkTableMap::COL_TAG_ID, $tagId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($tagId['max'])) {
                $this->addUsingAlias(LinkTableMap::COL_TAG_ID, $tagId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LinkTableMap::COL_TAG_ID, $tagId, $comparison);
    }

    /**
     * Filter the query on the event_id column
     *
     * Example usage:
     * <code>
     * $query->filterByEventId(1234); // WHERE event_id = 1234
     * $query->filterByEventId(array(12, 34)); // WHERE event_id IN (12, 34)
     * $query->filterByEventId(array('min' => 12)); // WHERE event_id > 12
     * </code>
     *
     * @param     mixed $eventId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLinkQuery The current query, for fluid interface
     */
    public function filterByEventId($eventId = null, $comparison = null)
    {
        if (is_array($eventId)) {
            $useMinMax = false;
            if (isset($eventId['min'])) {
                $this->addUsingAlias(LinkTableMap::COL_EVENT_ID, $eventId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventId['max'])) {
                $this->addUsingAlias(LinkTableMap::COL_EVENT_ID, $eventId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LinkTableMap::COL_EVENT_ID, $eventId, $comparison);
    }

    /**
     * Filter the query on the post_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPostId(1234); // WHERE post_id = 1234
     * $query->filterByPostId(array(12, 34)); // WHERE post_id IN (12, 34)
     * $query->filterByPostId(array('min' => 12)); // WHERE post_id > 12
     * </code>
     *
     * @param     mixed $postId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLinkQuery The current query, for fluid interface
     */
    public function filterByPostId($postId = null, $comparison = null)
    {
        if (is_array($postId)) {
            $useMinMax = false;
            if (isset($postId['min'])) {
                $this->addUsingAlias(LinkTableMap::COL_POST_ID, $postId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($postId['max'])) {
                $this->addUsingAlias(LinkTableMap::COL_POST_ID, $postId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LinkTableMap::COL_POST_ID, $postId, $comparison);
    }

    /**
     * Filter the query on the collection_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCollectionId(1234); // WHERE collection_id = 1234
     * $query->filterByCollectionId(array(12, 34)); // WHERE collection_id IN (12, 34)
     * $query->filterByCollectionId(array('min' => 12)); // WHERE collection_id > 12
     * </code>
     *
     * @param     mixed $collectionId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLinkQuery The current query, for fluid interface
     */
    public function filterByCollectionId($collectionId = null, $comparison = null)
    {
        if (is_array($collectionId)) {
            $useMinMax = false;
            if (isset($collectionId['min'])) {
                $this->addUsingAlias(LinkTableMap::COL_COLLECTION_ID, $collectionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($collectionId['max'])) {
                $this->addUsingAlias(LinkTableMap::COL_COLLECTION_ID, $collectionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LinkTableMap::COL_COLLECTION_ID, $collectionId, $comparison);
    }

    /**
     * Filter the query on the publisher_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPublisherId(1234); // WHERE publisher_id = 1234
     * $query->filterByPublisherId(array(12, 34)); // WHERE publisher_id IN (12, 34)
     * $query->filterByPublisherId(array('min' => 12)); // WHERE publisher_id > 12
     * </code>
     *
     * @param     mixed $publisherId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLinkQuery The current query, for fluid interface
     */
    public function filterByPublisherId($publisherId = null, $comparison = null)
    {
        if (is_array($publisherId)) {
            $useMinMax = false;
            if (isset($publisherId['min'])) {
                $this->addUsingAlias(LinkTableMap::COL_PUBLISHER_ID, $publisherId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publisherId['max'])) {
                $this->addUsingAlias(LinkTableMap::COL_PUBLISHER_ID, $publisherId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LinkTableMap::COL_PUBLISHER_ID, $publisherId, $comparison);
    }

    /**
     * Filter the query on the supplier_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySupplierId(1234); // WHERE supplier_id = 1234
     * $query->filterBySupplierId(array(12, 34)); // WHERE supplier_id IN (12, 34)
     * $query->filterBySupplierId(array('min' => 12)); // WHERE supplier_id > 12
     * </code>
     *
     * @param     mixed $supplierId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLinkQuery The current query, for fluid interface
     */
    public function filterBySupplierId($supplierId = null, $comparison = null)
    {
        if (is_array($supplierId)) {
            $useMinMax = false;
            if (isset($supplierId['min'])) {
                $this->addUsingAlias(LinkTableMap::COL_SUPPLIER_ID, $supplierId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($supplierId['max'])) {
                $this->addUsingAlias(LinkTableMap::COL_SUPPLIER_ID, $supplierId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LinkTableMap::COL_SUPPLIER_ID, $supplierId, $comparison);
    }

    /**
     * Filter the query on the media_id column
     *
     * Example usage:
     * <code>
     * $query->filterByMediaId(1234); // WHERE media_id = 1234
     * $query->filterByMediaId(array(12, 34)); // WHERE media_id IN (12, 34)
     * $query->filterByMediaId(array('min' => 12)); // WHERE media_id > 12
     * </code>
     *
     * @param     mixed $mediaId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLinkQuery The current query, for fluid interface
     */
    public function filterByMediaId($mediaId = null, $comparison = null)
    {
        if (is_array($mediaId)) {
            $useMinMax = false;
            if (isset($mediaId['min'])) {
                $this->addUsingAlias(LinkTableMap::COL_MEDIA_ID, $mediaId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($mediaId['max'])) {
                $this->addUsingAlias(LinkTableMap::COL_MEDIA_ID, $mediaId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LinkTableMap::COL_MEDIA_ID, $mediaId, $comparison);
    }

    /**
     * Filter the query on the bundle_id column
     *
     * Example usage:
     * <code>
     * $query->filterByBundleId(1234); // WHERE bundle_id = 1234
     * $query->filterByBundleId(array(12, 34)); // WHERE bundle_id IN (12, 34)
     * $query->filterByBundleId(array('min' => 12)); // WHERE bundle_id > 12
     * </code>
     *
     * @param     mixed $bundleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLinkQuery The current query, for fluid interface
     */
    public function filterByBundleId($bundleId = null, $comparison = null)
    {
        if (is_array($bundleId)) {
            $useMinMax = false;
            if (isset($bundleId['min'])) {
                $this->addUsingAlias(LinkTableMap::COL_BUNDLE_ID, $bundleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bundleId['max'])) {
                $this->addUsingAlias(LinkTableMap::COL_BUNDLE_ID, $bundleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LinkTableMap::COL_BUNDLE_ID, $bundleId, $comparison);
    }

    /**
     * Filter the query on the link_hide column
     *
     * Example usage:
     * <code>
     * $query->filterByHide(true); // WHERE link_hide = true
     * $query->filterByHide('yes'); // WHERE link_hide = true
     * </code>
     *
     * @param     boolean|string $hide The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLinkQuery The current query, for fluid interface
     */
    public function filterByHide($hide = null, $comparison = null)
    {
        if (is_string($hide)) {
            $hide = in_array(strtolower($hide), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(LinkTableMap::COL_LINK_HIDE, $hide, $comparison);
    }

    /**
     * Filter the query on the link_do_not_reorder column
     *
     * Example usage:
     * <code>
     * $query->filterByDoNotReorder(true); // WHERE link_do_not_reorder = true
     * $query->filterByDoNotReorder('yes'); // WHERE link_do_not_reorder = true
     * </code>
     *
     * @param     boolean|string $doNotReorder The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLinkQuery The current query, for fluid interface
     */
    public function filterByDoNotReorder($doNotReorder = null, $comparison = null)
    {
        if (is_string($doNotReorder)) {
            $doNotReorder = in_array(strtolower($doNotReorder), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(LinkTableMap::COL_LINK_DO_NOT_REORDER, $doNotReorder, $comparison);
    }

    /**
     * Filter the query on the link_sponsor_user_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySponsorUserId(1234); // WHERE link_sponsor_user_id = 1234
     * $query->filterBySponsorUserId(array(12, 34)); // WHERE link_sponsor_user_id IN (12, 34)
     * $query->filterBySponsorUserId(array('min' => 12)); // WHERE link_sponsor_user_id > 12
     * </code>
     *
     * @param     mixed $sponsorUserId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLinkQuery The current query, for fluid interface
     */
    public function filterBySponsorUserId($sponsorUserId = null, $comparison = null)
    {
        if (is_array($sponsorUserId)) {
            $useMinMax = false;
            if (isset($sponsorUserId['min'])) {
                $this->addUsingAlias(LinkTableMap::COL_LINK_SPONSOR_USER_ID, $sponsorUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sponsorUserId['max'])) {
                $this->addUsingAlias(LinkTableMap::COL_LINK_SPONSOR_USER_ID, $sponsorUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LinkTableMap::COL_LINK_SPONSOR_USER_ID, $sponsorUserId, $comparison);
    }

    /**
     * Filter the query on the link_date column
     *
     * Example usage:
     * <code>
     * $query->filterByDate('2011-03-14'); // WHERE link_date = '2011-03-14'
     * $query->filterByDate('now'); // WHERE link_date = '2011-03-14'
     * $query->filterByDate(array('max' => 'yesterday')); // WHERE link_date > '2011-03-13'
     * </code>
     *
     * @param     mixed $date The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLinkQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(LinkTableMap::COL_LINK_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(LinkTableMap::COL_LINK_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LinkTableMap::COL_LINK_DATE, $date, $comparison);
    }

    /**
     * Filter the query on the link_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE link_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE link_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE link_created > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLinkQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(LinkTableMap::COL_LINK_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(LinkTableMap::COL_LINK_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LinkTableMap::COL_LINK_CREATED, $createdAt, $comparison);
    }

    /**
     * Filter the query on the link_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE link_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE link_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE link_updated > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLinkQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(LinkTableMap::COL_LINK_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(LinkTableMap::COL_LINK_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LinkTableMap::COL_LINK_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the link_deleted column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletedAt('2011-03-14'); // WHERE link_deleted = '2011-03-14'
     * $query->filterByDeletedAt('now'); // WHERE link_deleted = '2011-03-14'
     * $query->filterByDeletedAt(array('max' => 'yesterday')); // WHERE link_deleted > '2011-03-13'
     * </code>
     *
     * @param     mixed $deletedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLinkQuery The current query, for fluid interface
     */
    public function filterByDeletedAt($deletedAt = null, $comparison = null)
    {
        if (is_array($deletedAt)) {
            $useMinMax = false;
            if (isset($deletedAt['min'])) {
                $this->addUsingAlias(LinkTableMap::COL_LINK_DELETED, $deletedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletedAt['max'])) {
                $this->addUsingAlias(LinkTableMap::COL_LINK_DELETED, $deletedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LinkTableMap::COL_LINK_DELETED, $deletedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildLink $link Object to remove from the list of results
     *
     * @return $this|ChildLinkQuery The current query, for fluid interface
     */
    public function prune($link = null)
    {
        if ($link) {
            $this->addUsingAlias(LinkTableMap::COL_LINK_ID, $link->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the links table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LinkTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LinkTableMap::clearInstancePool();
            LinkTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LinkTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(LinkTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            LinkTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            LinkTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildLinkQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(LinkTableMap::COL_LINK_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildLinkQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(LinkTableMap::COL_LINK_UPDATED);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildLinkQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(LinkTableMap::COL_LINK_UPDATED);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildLinkQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(LinkTableMap::COL_LINK_CREATED);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildLinkQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(LinkTableMap::COL_LINK_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildLinkQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(LinkTableMap::COL_LINK_CREATED);
    }

} // LinkQuery
