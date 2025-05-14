<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Post as ChildPost;
use Model\PostQuery as ChildPostQuery;
use Model\Map\PostTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `posts` table.
 *
 * @method     ChildPostQuery orderById($order = Criteria::ASC) Order by the post_id column
 * @method     ChildPostQuery orderByAxysAccountId($order = Criteria::ASC) Order by the axys_account_id column
 * @method     ChildPostQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildPostQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildPostQuery orderByPublisherId($order = Criteria::ASC) Order by the publisher_id column
 * @method     ChildPostQuery orderByCategoryId($order = Criteria::ASC) Order by the category_id column
 * @method     ChildPostQuery orderByUrl($order = Criteria::ASC) Order by the post_url column
 * @method     ChildPostQuery orderByTitle($order = Criteria::ASC) Order by the post_title column
 * @method     ChildPostQuery orderByExcerpt($order = Criteria::ASC) Order by the post_excerpt column
 * @method     ChildPostQuery orderByContent($order = Criteria::ASC) Order by the post_content column
 * @method     ChildPostQuery orderByIllustrationVersion($order = Criteria::ASC) Order by the post_illustration_version column
 * @method     ChildPostQuery orderByIllustrationLegend($order = Criteria::ASC) Order by the post_illustration_legend column
 * @method     ChildPostQuery orderBySelected($order = Criteria::ASC) Order by the post_selected column
 * @method     ChildPostQuery orderByLink($order = Criteria::ASC) Order by the post_link column
 * @method     ChildPostQuery orderByStatus($order = Criteria::ASC) Order by the post_status column
 * @method     ChildPostQuery orderByKeywords($order = Criteria::ASC) Order by the post_keywords column
 * @method     ChildPostQuery orderByLinks($order = Criteria::ASC) Order by the post_links column
 * @method     ChildPostQuery orderByKeywordsGenerated($order = Criteria::ASC) Order by the post_keywords_generated column
 * @method     ChildPostQuery orderByFbId($order = Criteria::ASC) Order by the post_fb_id column
 * @method     ChildPostQuery orderByDate($order = Criteria::ASC) Order by the post_date column
 * @method     ChildPostQuery orderByHits($order = Criteria::ASC) Order by the post_hits column
 * @method     ChildPostQuery orderByInsert($order = Criteria::ASC) Order by the post_insert column
 * @method     ChildPostQuery orderByUpdate($order = Criteria::ASC) Order by the post_update column
 * @method     ChildPostQuery orderByCreatedAt($order = Criteria::ASC) Order by the post_created column
 * @method     ChildPostQuery orderByUpdatedAt($order = Criteria::ASC) Order by the post_updated column
 *
 * @method     ChildPostQuery groupById() Group by the post_id column
 * @method     ChildPostQuery groupByAxysAccountId() Group by the axys_account_id column
 * @method     ChildPostQuery groupByUserId() Group by the user_id column
 * @method     ChildPostQuery groupBySiteId() Group by the site_id column
 * @method     ChildPostQuery groupByPublisherId() Group by the publisher_id column
 * @method     ChildPostQuery groupByCategoryId() Group by the category_id column
 * @method     ChildPostQuery groupByUrl() Group by the post_url column
 * @method     ChildPostQuery groupByTitle() Group by the post_title column
 * @method     ChildPostQuery groupByExcerpt() Group by the post_excerpt column
 * @method     ChildPostQuery groupByContent() Group by the post_content column
 * @method     ChildPostQuery groupByIllustrationVersion() Group by the post_illustration_version column
 * @method     ChildPostQuery groupByIllustrationLegend() Group by the post_illustration_legend column
 * @method     ChildPostQuery groupBySelected() Group by the post_selected column
 * @method     ChildPostQuery groupByLink() Group by the post_link column
 * @method     ChildPostQuery groupByStatus() Group by the post_status column
 * @method     ChildPostQuery groupByKeywords() Group by the post_keywords column
 * @method     ChildPostQuery groupByLinks() Group by the post_links column
 * @method     ChildPostQuery groupByKeywordsGenerated() Group by the post_keywords_generated column
 * @method     ChildPostQuery groupByFbId() Group by the post_fb_id column
 * @method     ChildPostQuery groupByDate() Group by the post_date column
 * @method     ChildPostQuery groupByHits() Group by the post_hits column
 * @method     ChildPostQuery groupByInsert() Group by the post_insert column
 * @method     ChildPostQuery groupByUpdate() Group by the post_update column
 * @method     ChildPostQuery groupByCreatedAt() Group by the post_created column
 * @method     ChildPostQuery groupByUpdatedAt() Group by the post_updated column
 *
 * @method     ChildPostQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPostQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPostQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPostQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPostQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPostQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPostQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildPostQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildPostQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildPostQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildPostQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildPostQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildPostQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     ChildPostQuery leftJoinSite($relationAlias = null) Adds a LEFT JOIN clause to the query using the Site relation
 * @method     ChildPostQuery rightJoinSite($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Site relation
 * @method     ChildPostQuery innerJoinSite($relationAlias = null) Adds a INNER JOIN clause to the query using the Site relation
 *
 * @method     ChildPostQuery joinWithSite($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Site relation
 *
 * @method     ChildPostQuery leftJoinWithSite() Adds a LEFT JOIN clause and with to the query using the Site relation
 * @method     ChildPostQuery rightJoinWithSite() Adds a RIGHT JOIN clause and with to the query using the Site relation
 * @method     ChildPostQuery innerJoinWithSite() Adds a INNER JOIN clause and with to the query using the Site relation
 *
 * @method     ChildPostQuery leftJoinBlogCategory($relationAlias = null) Adds a LEFT JOIN clause to the query using the BlogCategory relation
 * @method     ChildPostQuery rightJoinBlogCategory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the BlogCategory relation
 * @method     ChildPostQuery innerJoinBlogCategory($relationAlias = null) Adds a INNER JOIN clause to the query using the BlogCategory relation
 *
 * @method     ChildPostQuery joinWithBlogCategory($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the BlogCategory relation
 *
 * @method     ChildPostQuery leftJoinWithBlogCategory() Adds a LEFT JOIN clause and with to the query using the BlogCategory relation
 * @method     ChildPostQuery rightJoinWithBlogCategory() Adds a RIGHT JOIN clause and with to the query using the BlogCategory relation
 * @method     ChildPostQuery innerJoinWithBlogCategory() Adds a INNER JOIN clause and with to the query using the BlogCategory relation
 *
 * @method     ChildPostQuery leftJoinImage($relationAlias = null) Adds a LEFT JOIN clause to the query using the Image relation
 * @method     ChildPostQuery rightJoinImage($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Image relation
 * @method     ChildPostQuery innerJoinImage($relationAlias = null) Adds a INNER JOIN clause to the query using the Image relation
 *
 * @method     ChildPostQuery joinWithImage($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Image relation
 *
 * @method     ChildPostQuery leftJoinWithImage() Adds a LEFT JOIN clause and with to the query using the Image relation
 * @method     ChildPostQuery rightJoinWithImage() Adds a RIGHT JOIN clause and with to the query using the Image relation
 * @method     ChildPostQuery innerJoinWithImage() Adds a INNER JOIN clause and with to the query using the Image relation
 *
 * @method     \Model\UserQuery|\Model\SiteQuery|\Model\BlogCategoryQuery|\Model\ImageQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPost|null findOne(?ConnectionInterface $con = null) Return the first ChildPost matching the query
 * @method     ChildPost findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildPost matching the query, or a new ChildPost object populated from the query conditions when no match is found
 *
 * @method     ChildPost|null findOneById(int $post_id) Return the first ChildPost filtered by the post_id column
 * @method     ChildPost|null findOneByAxysAccountId(int $axys_account_id) Return the first ChildPost filtered by the axys_account_id column
 * @method     ChildPost|null findOneByUserId(int $user_id) Return the first ChildPost filtered by the user_id column
 * @method     ChildPost|null findOneBySiteId(int $site_id) Return the first ChildPost filtered by the site_id column
 * @method     ChildPost|null findOneByPublisherId(int $publisher_id) Return the first ChildPost filtered by the publisher_id column
 * @method     ChildPost|null findOneByCategoryId(int $category_id) Return the first ChildPost filtered by the category_id column
 * @method     ChildPost|null findOneByUrl(string $post_url) Return the first ChildPost filtered by the post_url column
 * @method     ChildPost|null findOneByTitle(string $post_title) Return the first ChildPost filtered by the post_title column
 * @method     ChildPost|null findOneByExcerpt(string $post_excerpt) Return the first ChildPost filtered by the post_excerpt column
 * @method     ChildPost|null findOneByContent(string $post_content) Return the first ChildPost filtered by the post_content column
 * @method     ChildPost|null findOneByIllustrationVersion(int $post_illustration_version) Return the first ChildPost filtered by the post_illustration_version column
 * @method     ChildPost|null findOneByIllustrationLegend(string $post_illustration_legend) Return the first ChildPost filtered by the post_illustration_legend column
 * @method     ChildPost|null findOneBySelected(boolean $post_selected) Return the first ChildPost filtered by the post_selected column
 * @method     ChildPost|null findOneByLink(string $post_link) Return the first ChildPost filtered by the post_link column
 * @method     ChildPost|null findOneByStatus(boolean $post_status) Return the first ChildPost filtered by the post_status column
 * @method     ChildPost|null findOneByKeywords(string $post_keywords) Return the first ChildPost filtered by the post_keywords column
 * @method     ChildPost|null findOneByLinks(string $post_links) Return the first ChildPost filtered by the post_links column
 * @method     ChildPost|null findOneByKeywordsGenerated(string $post_keywords_generated) Return the first ChildPost filtered by the post_keywords_generated column
 * @method     ChildPost|null findOneByFbId(string $post_fb_id) Return the first ChildPost filtered by the post_fb_id column
 * @method     ChildPost|null findOneByDate(string $post_date) Return the first ChildPost filtered by the post_date column
 * @method     ChildPost|null findOneByHits(int $post_hits) Return the first ChildPost filtered by the post_hits column
 * @method     ChildPost|null findOneByInsert(string $post_insert) Return the first ChildPost filtered by the post_insert column
 * @method     ChildPost|null findOneByUpdate(string $post_update) Return the first ChildPost filtered by the post_update column
 * @method     ChildPost|null findOneByCreatedAt(string $post_created) Return the first ChildPost filtered by the post_created column
 * @method     ChildPost|null findOneByUpdatedAt(string $post_updated) Return the first ChildPost filtered by the post_updated column
 *
 * @method     ChildPost requirePk($key, ?ConnectionInterface $con = null) Return the ChildPost by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPost requireOne(?ConnectionInterface $con = null) Return the first ChildPost matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPost requireOneById(int $post_id) Return the first ChildPost filtered by the post_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPost requireOneByAxysAccountId(int $axys_account_id) Return the first ChildPost filtered by the axys_account_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPost requireOneByUserId(int $user_id) Return the first ChildPost filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPost requireOneBySiteId(int $site_id) Return the first ChildPost filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPost requireOneByPublisherId(int $publisher_id) Return the first ChildPost filtered by the publisher_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPost requireOneByCategoryId(int $category_id) Return the first ChildPost filtered by the category_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPost requireOneByUrl(string $post_url) Return the first ChildPost filtered by the post_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPost requireOneByTitle(string $post_title) Return the first ChildPost filtered by the post_title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPost requireOneByExcerpt(string $post_excerpt) Return the first ChildPost filtered by the post_excerpt column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPost requireOneByContent(string $post_content) Return the first ChildPost filtered by the post_content column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPost requireOneByIllustrationVersion(int $post_illustration_version) Return the first ChildPost filtered by the post_illustration_version column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPost requireOneByIllustrationLegend(string $post_illustration_legend) Return the first ChildPost filtered by the post_illustration_legend column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPost requireOneBySelected(boolean $post_selected) Return the first ChildPost filtered by the post_selected column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPost requireOneByLink(string $post_link) Return the first ChildPost filtered by the post_link column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPost requireOneByStatus(boolean $post_status) Return the first ChildPost filtered by the post_status column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPost requireOneByKeywords(string $post_keywords) Return the first ChildPost filtered by the post_keywords column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPost requireOneByLinks(string $post_links) Return the first ChildPost filtered by the post_links column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPost requireOneByKeywordsGenerated(string $post_keywords_generated) Return the first ChildPost filtered by the post_keywords_generated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPost requireOneByFbId(string $post_fb_id) Return the first ChildPost filtered by the post_fb_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPost requireOneByDate(string $post_date) Return the first ChildPost filtered by the post_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPost requireOneByHits(int $post_hits) Return the first ChildPost filtered by the post_hits column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPost requireOneByInsert(string $post_insert) Return the first ChildPost filtered by the post_insert column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPost requireOneByUpdate(string $post_update) Return the first ChildPost filtered by the post_update column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPost requireOneByCreatedAt(string $post_created) Return the first ChildPost filtered by the post_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPost requireOneByUpdatedAt(string $post_updated) Return the first ChildPost filtered by the post_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPost[]|Collection find(?ConnectionInterface $con = null) Return ChildPost objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildPost> find(?ConnectionInterface $con = null) Return ChildPost objects based on current ModelCriteria
 *
 * @method     ChildPost[]|Collection findById(int|array<int> $post_id) Return ChildPost objects filtered by the post_id column
 * @psalm-method Collection&\Traversable<ChildPost> findById(int|array<int> $post_id) Return ChildPost objects filtered by the post_id column
 * @method     ChildPost[]|Collection findByAxysAccountId(int|array<int> $axys_account_id) Return ChildPost objects filtered by the axys_account_id column
 * @psalm-method Collection&\Traversable<ChildPost> findByAxysAccountId(int|array<int> $axys_account_id) Return ChildPost objects filtered by the axys_account_id column
 * @method     ChildPost[]|Collection findByUserId(int|array<int> $user_id) Return ChildPost objects filtered by the user_id column
 * @psalm-method Collection&\Traversable<ChildPost> findByUserId(int|array<int> $user_id) Return ChildPost objects filtered by the user_id column
 * @method     ChildPost[]|Collection findBySiteId(int|array<int> $site_id) Return ChildPost objects filtered by the site_id column
 * @psalm-method Collection&\Traversable<ChildPost> findBySiteId(int|array<int> $site_id) Return ChildPost objects filtered by the site_id column
 * @method     ChildPost[]|Collection findByPublisherId(int|array<int> $publisher_id) Return ChildPost objects filtered by the publisher_id column
 * @psalm-method Collection&\Traversable<ChildPost> findByPublisherId(int|array<int> $publisher_id) Return ChildPost objects filtered by the publisher_id column
 * @method     ChildPost[]|Collection findByCategoryId(int|array<int> $category_id) Return ChildPost objects filtered by the category_id column
 * @psalm-method Collection&\Traversable<ChildPost> findByCategoryId(int|array<int> $category_id) Return ChildPost objects filtered by the category_id column
 * @method     ChildPost[]|Collection findByUrl(string|array<string> $post_url) Return ChildPost objects filtered by the post_url column
 * @psalm-method Collection&\Traversable<ChildPost> findByUrl(string|array<string> $post_url) Return ChildPost objects filtered by the post_url column
 * @method     ChildPost[]|Collection findByTitle(string|array<string> $post_title) Return ChildPost objects filtered by the post_title column
 * @psalm-method Collection&\Traversable<ChildPost> findByTitle(string|array<string> $post_title) Return ChildPost objects filtered by the post_title column
 * @method     ChildPost[]|Collection findByExcerpt(string|array<string> $post_excerpt) Return ChildPost objects filtered by the post_excerpt column
 * @psalm-method Collection&\Traversable<ChildPost> findByExcerpt(string|array<string> $post_excerpt) Return ChildPost objects filtered by the post_excerpt column
 * @method     ChildPost[]|Collection findByContent(string|array<string> $post_content) Return ChildPost objects filtered by the post_content column
 * @psalm-method Collection&\Traversable<ChildPost> findByContent(string|array<string> $post_content) Return ChildPost objects filtered by the post_content column
 * @method     ChildPost[]|Collection findByIllustrationVersion(int|array<int> $post_illustration_version) Return ChildPost objects filtered by the post_illustration_version column
 * @psalm-method Collection&\Traversable<ChildPost> findByIllustrationVersion(int|array<int> $post_illustration_version) Return ChildPost objects filtered by the post_illustration_version column
 * @method     ChildPost[]|Collection findByIllustrationLegend(string|array<string> $post_illustration_legend) Return ChildPost objects filtered by the post_illustration_legend column
 * @psalm-method Collection&\Traversable<ChildPost> findByIllustrationLegend(string|array<string> $post_illustration_legend) Return ChildPost objects filtered by the post_illustration_legend column
 * @method     ChildPost[]|Collection findBySelected(boolean|array<boolean> $post_selected) Return ChildPost objects filtered by the post_selected column
 * @psalm-method Collection&\Traversable<ChildPost> findBySelected(boolean|array<boolean> $post_selected) Return ChildPost objects filtered by the post_selected column
 * @method     ChildPost[]|Collection findByLink(string|array<string> $post_link) Return ChildPost objects filtered by the post_link column
 * @psalm-method Collection&\Traversable<ChildPost> findByLink(string|array<string> $post_link) Return ChildPost objects filtered by the post_link column
 * @method     ChildPost[]|Collection findByStatus(boolean|array<boolean> $post_status) Return ChildPost objects filtered by the post_status column
 * @psalm-method Collection&\Traversable<ChildPost> findByStatus(boolean|array<boolean> $post_status) Return ChildPost objects filtered by the post_status column
 * @method     ChildPost[]|Collection findByKeywords(string|array<string> $post_keywords) Return ChildPost objects filtered by the post_keywords column
 * @psalm-method Collection&\Traversable<ChildPost> findByKeywords(string|array<string> $post_keywords) Return ChildPost objects filtered by the post_keywords column
 * @method     ChildPost[]|Collection findByLinks(string|array<string> $post_links) Return ChildPost objects filtered by the post_links column
 * @psalm-method Collection&\Traversable<ChildPost> findByLinks(string|array<string> $post_links) Return ChildPost objects filtered by the post_links column
 * @method     ChildPost[]|Collection findByKeywordsGenerated(string|array<string> $post_keywords_generated) Return ChildPost objects filtered by the post_keywords_generated column
 * @psalm-method Collection&\Traversable<ChildPost> findByKeywordsGenerated(string|array<string> $post_keywords_generated) Return ChildPost objects filtered by the post_keywords_generated column
 * @method     ChildPost[]|Collection findByFbId(string|array<string> $post_fb_id) Return ChildPost objects filtered by the post_fb_id column
 * @psalm-method Collection&\Traversable<ChildPost> findByFbId(string|array<string> $post_fb_id) Return ChildPost objects filtered by the post_fb_id column
 * @method     ChildPost[]|Collection findByDate(string|array<string> $post_date) Return ChildPost objects filtered by the post_date column
 * @psalm-method Collection&\Traversable<ChildPost> findByDate(string|array<string> $post_date) Return ChildPost objects filtered by the post_date column
 * @method     ChildPost[]|Collection findByHits(int|array<int> $post_hits) Return ChildPost objects filtered by the post_hits column
 * @psalm-method Collection&\Traversable<ChildPost> findByHits(int|array<int> $post_hits) Return ChildPost objects filtered by the post_hits column
 * @method     ChildPost[]|Collection findByInsert(string|array<string> $post_insert) Return ChildPost objects filtered by the post_insert column
 * @psalm-method Collection&\Traversable<ChildPost> findByInsert(string|array<string> $post_insert) Return ChildPost objects filtered by the post_insert column
 * @method     ChildPost[]|Collection findByUpdate(string|array<string> $post_update) Return ChildPost objects filtered by the post_update column
 * @psalm-method Collection&\Traversable<ChildPost> findByUpdate(string|array<string> $post_update) Return ChildPost objects filtered by the post_update column
 * @method     ChildPost[]|Collection findByCreatedAt(string|array<string> $post_created) Return ChildPost objects filtered by the post_created column
 * @psalm-method Collection&\Traversable<ChildPost> findByCreatedAt(string|array<string> $post_created) Return ChildPost objects filtered by the post_created column
 * @method     ChildPost[]|Collection findByUpdatedAt(string|array<string> $post_updated) Return ChildPost objects filtered by the post_updated column
 * @psalm-method Collection&\Traversable<ChildPost> findByUpdatedAt(string|array<string> $post_updated) Return ChildPost objects filtered by the post_updated column
 *
 * @method     ChildPost[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildPost> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class PostQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\PostQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Post', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPostQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPostQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildPostQuery) {
            return $criteria;
        }
        $query = new ChildPostQuery();
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
     * @return ChildPost|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PostTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PostTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildPost A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT post_id, axys_account_id, user_id, site_id, publisher_id, category_id, post_url, post_title, post_excerpt, post_content, post_illustration_version, post_illustration_legend, post_selected, post_link, post_status, post_keywords, post_links, post_keywords_generated, post_fb_id, post_date, post_hits, post_insert, post_update, post_created, post_updated FROM posts WHERE post_id = :p0';
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
            /** @var ChildPost $obj */
            $obj = new ChildPost();
            $obj->hydrate($row);
            PostTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPost|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(PostTableMap::COL_POST_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(PostTableMap::COL_POST_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the post_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE post_id = 1234
     * $query->filterById(array(12, 34)); // WHERE post_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE post_id > 12
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
                $this->addUsingAlias(PostTableMap::COL_POST_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PostTableMap::COL_POST_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PostTableMap::COL_POST_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the axys_account_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAxysAccountId(1234); // WHERE axys_account_id = 1234
     * $query->filterByAxysAccountId(array(12, 34)); // WHERE axys_account_id IN (12, 34)
     * $query->filterByAxysAccountId(array('min' => 12)); // WHERE axys_account_id > 12
     * </code>
     *
     * @param mixed $axysAccountId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAxysAccountId($axysAccountId = null, ?string $comparison = null)
    {
        if (is_array($axysAccountId)) {
            $useMinMax = false;
            if (isset($axysAccountId['min'])) {
                $this->addUsingAlias(PostTableMap::COL_AXYS_ACCOUNT_ID, $axysAccountId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($axysAccountId['max'])) {
                $this->addUsingAlias(PostTableMap::COL_AXYS_ACCOUNT_ID, $axysAccountId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PostTableMap::COL_AXYS_ACCOUNT_ID, $axysAccountId, $comparison);

        return $this;
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
     * @see       filterByUser()
     *
     * @param mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUserId($userId = null, ?string $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(PostTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(PostTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PostTableMap::COL_USER_ID, $userId, $comparison);

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
     * @see       filterBySite()
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
                $this->addUsingAlias(PostTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(PostTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PostTableMap::COL_SITE_ID, $siteId, $comparison);

        return $this;
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
     * @param mixed $publisherId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPublisherId($publisherId = null, ?string $comparison = null)
    {
        if (is_array($publisherId)) {
            $useMinMax = false;
            if (isset($publisherId['min'])) {
                $this->addUsingAlias(PostTableMap::COL_PUBLISHER_ID, $publisherId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publisherId['max'])) {
                $this->addUsingAlias(PostTableMap::COL_PUBLISHER_ID, $publisherId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PostTableMap::COL_PUBLISHER_ID, $publisherId, $comparison);

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
     * @see       filterByBlogCategory()
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
                $this->addUsingAlias(PostTableMap::COL_CATEGORY_ID, $categoryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($categoryId['max'])) {
                $this->addUsingAlias(PostTableMap::COL_CATEGORY_ID, $categoryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PostTableMap::COL_CATEGORY_ID, $categoryId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the post_url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE post_url = 'fooValue'
     * $query->filterByUrl('%fooValue%', Criteria::LIKE); // WHERE post_url LIKE '%fooValue%'
     * $query->filterByUrl(['foo', 'bar']); // WHERE post_url IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $url The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUrl($url = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($url)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PostTableMap::COL_POST_URL, $url, $comparison);

        return $this;
    }

    /**
     * Filter the query on the post_title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE post_title = 'fooValue'
     * $query->filterByTitle('%fooValue%', Criteria::LIKE); // WHERE post_title LIKE '%fooValue%'
     * $query->filterByTitle(['foo', 'bar']); // WHERE post_title IN ('foo', 'bar')
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

        $this->addUsingAlias(PostTableMap::COL_POST_TITLE, $title, $comparison);

        return $this;
    }

    /**
     * Filter the query on the post_excerpt column
     *
     * Example usage:
     * <code>
     * $query->filterByExcerpt('fooValue');   // WHERE post_excerpt = 'fooValue'
     * $query->filterByExcerpt('%fooValue%', Criteria::LIKE); // WHERE post_excerpt LIKE '%fooValue%'
     * $query->filterByExcerpt(['foo', 'bar']); // WHERE post_excerpt IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $excerpt The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByExcerpt($excerpt = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($excerpt)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PostTableMap::COL_POST_EXCERPT, $excerpt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the post_content column
     *
     * Example usage:
     * <code>
     * $query->filterByContent('fooValue');   // WHERE post_content = 'fooValue'
     * $query->filterByContent('%fooValue%', Criteria::LIKE); // WHERE post_content LIKE '%fooValue%'
     * $query->filterByContent(['foo', 'bar']); // WHERE post_content IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $content The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByContent($content = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($content)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PostTableMap::COL_POST_CONTENT, $content, $comparison);

        return $this;
    }

    /**
     * Filter the query on the post_illustration_version column
     *
     * Example usage:
     * <code>
     * $query->filterByIllustrationVersion(1234); // WHERE post_illustration_version = 1234
     * $query->filterByIllustrationVersion(array(12, 34)); // WHERE post_illustration_version IN (12, 34)
     * $query->filterByIllustrationVersion(array('min' => 12)); // WHERE post_illustration_version > 12
     * </code>
     *
     * @param mixed $illustrationVersion The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByIllustrationVersion($illustrationVersion = null, ?string $comparison = null)
    {
        if (is_array($illustrationVersion)) {
            $useMinMax = false;
            if (isset($illustrationVersion['min'])) {
                $this->addUsingAlias(PostTableMap::COL_POST_ILLUSTRATION_VERSION, $illustrationVersion['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($illustrationVersion['max'])) {
                $this->addUsingAlias(PostTableMap::COL_POST_ILLUSTRATION_VERSION, $illustrationVersion['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PostTableMap::COL_POST_ILLUSTRATION_VERSION, $illustrationVersion, $comparison);

        return $this;
    }

    /**
     * Filter the query on the post_illustration_legend column
     *
     * Example usage:
     * <code>
     * $query->filterByIllustrationLegend('fooValue');   // WHERE post_illustration_legend = 'fooValue'
     * $query->filterByIllustrationLegend('%fooValue%', Criteria::LIKE); // WHERE post_illustration_legend LIKE '%fooValue%'
     * $query->filterByIllustrationLegend(['foo', 'bar']); // WHERE post_illustration_legend IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $illustrationLegend The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByIllustrationLegend($illustrationLegend = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($illustrationLegend)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PostTableMap::COL_POST_ILLUSTRATION_LEGEND, $illustrationLegend, $comparison);

        return $this;
    }

    /**
     * Filter the query on the post_selected column
     *
     * Example usage:
     * <code>
     * $query->filterBySelected(true); // WHERE post_selected = true
     * $query->filterBySelected('yes'); // WHERE post_selected = true
     * </code>
     *
     * @param bool|string $selected The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySelected($selected = null, ?string $comparison = null)
    {
        if (is_string($selected)) {
            $selected = in_array(strtolower($selected), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(PostTableMap::COL_POST_SELECTED, $selected, $comparison);

        return $this;
    }

    /**
     * Filter the query on the post_link column
     *
     * Example usage:
     * <code>
     * $query->filterByLink('fooValue');   // WHERE post_link = 'fooValue'
     * $query->filterByLink('%fooValue%', Criteria::LIKE); // WHERE post_link LIKE '%fooValue%'
     * $query->filterByLink(['foo', 'bar']); // WHERE post_link IN ('foo', 'bar')
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

        $this->addUsingAlias(PostTableMap::COL_POST_LINK, $link, $comparison);

        return $this;
    }

    /**
     * Filter the query on the post_status column
     *
     * Example usage:
     * <code>
     * $query->filterByStatus(true); // WHERE post_status = true
     * $query->filterByStatus('yes'); // WHERE post_status = true
     * </code>
     *
     * @param bool|string $status The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByStatus($status = null, ?string $comparison = null)
    {
        if (is_string($status)) {
            $status = in_array(strtolower($status), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(PostTableMap::COL_POST_STATUS, $status, $comparison);

        return $this;
    }

    /**
     * Filter the query on the post_keywords column
     *
     * Example usage:
     * <code>
     * $query->filterByKeywords('fooValue');   // WHERE post_keywords = 'fooValue'
     * $query->filterByKeywords('%fooValue%', Criteria::LIKE); // WHERE post_keywords LIKE '%fooValue%'
     * $query->filterByKeywords(['foo', 'bar']); // WHERE post_keywords IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $keywords The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByKeywords($keywords = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($keywords)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PostTableMap::COL_POST_KEYWORDS, $keywords, $comparison);

        return $this;
    }

    /**
     * Filter the query on the post_links column
     *
     * Example usage:
     * <code>
     * $query->filterByLinks('fooValue');   // WHERE post_links = 'fooValue'
     * $query->filterByLinks('%fooValue%', Criteria::LIKE); // WHERE post_links LIKE '%fooValue%'
     * $query->filterByLinks(['foo', 'bar']); // WHERE post_links IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $links The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByLinks($links = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($links)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PostTableMap::COL_POST_LINKS, $links, $comparison);

        return $this;
    }

    /**
     * Filter the query on the post_keywords_generated column
     *
     * Example usage:
     * <code>
     * $query->filterByKeywordsGenerated('2011-03-14'); // WHERE post_keywords_generated = '2011-03-14'
     * $query->filterByKeywordsGenerated('now'); // WHERE post_keywords_generated = '2011-03-14'
     * $query->filterByKeywordsGenerated(array('max' => 'yesterday')); // WHERE post_keywords_generated > '2011-03-13'
     * </code>
     *
     * @param mixed $keywordsGenerated The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByKeywordsGenerated($keywordsGenerated = null, ?string $comparison = null)
    {
        if (is_array($keywordsGenerated)) {
            $useMinMax = false;
            if (isset($keywordsGenerated['min'])) {
                $this->addUsingAlias(PostTableMap::COL_POST_KEYWORDS_GENERATED, $keywordsGenerated['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($keywordsGenerated['max'])) {
                $this->addUsingAlias(PostTableMap::COL_POST_KEYWORDS_GENERATED, $keywordsGenerated['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PostTableMap::COL_POST_KEYWORDS_GENERATED, $keywordsGenerated, $comparison);

        return $this;
    }

    /**
     * Filter the query on the post_fb_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFbId(1234); // WHERE post_fb_id = 1234
     * $query->filterByFbId(array(12, 34)); // WHERE post_fb_id IN (12, 34)
     * $query->filterByFbId(array('min' => 12)); // WHERE post_fb_id > 12
     * </code>
     *
     * @param mixed $fbId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFbId($fbId = null, ?string $comparison = null)
    {
        if (is_array($fbId)) {
            $useMinMax = false;
            if (isset($fbId['min'])) {
                $this->addUsingAlias(PostTableMap::COL_POST_FB_ID, $fbId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($fbId['max'])) {
                $this->addUsingAlias(PostTableMap::COL_POST_FB_ID, $fbId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PostTableMap::COL_POST_FB_ID, $fbId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the post_date column
     *
     * Example usage:
     * <code>
     * $query->filterByDate('2011-03-14'); // WHERE post_date = '2011-03-14'
     * $query->filterByDate('now'); // WHERE post_date = '2011-03-14'
     * $query->filterByDate(array('max' => 'yesterday')); // WHERE post_date > '2011-03-13'
     * </code>
     *
     * @param mixed $date The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDate($date = null, ?string $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(PostTableMap::COL_POST_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(PostTableMap::COL_POST_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PostTableMap::COL_POST_DATE, $date, $comparison);

        return $this;
    }

    /**
     * Filter the query on the post_hits column
     *
     * Example usage:
     * <code>
     * $query->filterByHits(1234); // WHERE post_hits = 1234
     * $query->filterByHits(array(12, 34)); // WHERE post_hits IN (12, 34)
     * $query->filterByHits(array('min' => 12)); // WHERE post_hits > 12
     * </code>
     *
     * @param mixed $hits The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByHits($hits = null, ?string $comparison = null)
    {
        if (is_array($hits)) {
            $useMinMax = false;
            if (isset($hits['min'])) {
                $this->addUsingAlias(PostTableMap::COL_POST_HITS, $hits['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($hits['max'])) {
                $this->addUsingAlias(PostTableMap::COL_POST_HITS, $hits['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PostTableMap::COL_POST_HITS, $hits, $comparison);

        return $this;
    }

    /**
     * Filter the query on the post_insert column
     *
     * Example usage:
     * <code>
     * $query->filterByInsert('2011-03-14'); // WHERE post_insert = '2011-03-14'
     * $query->filterByInsert('now'); // WHERE post_insert = '2011-03-14'
     * $query->filterByInsert(array('max' => 'yesterday')); // WHERE post_insert > '2011-03-13'
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
                $this->addUsingAlias(PostTableMap::COL_POST_INSERT, $insert['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($insert['max'])) {
                $this->addUsingAlias(PostTableMap::COL_POST_INSERT, $insert['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PostTableMap::COL_POST_INSERT, $insert, $comparison);

        return $this;
    }

    /**
     * Filter the query on the post_update column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdate('2011-03-14'); // WHERE post_update = '2011-03-14'
     * $query->filterByUpdate('now'); // WHERE post_update = '2011-03-14'
     * $query->filterByUpdate(array('max' => 'yesterday')); // WHERE post_update > '2011-03-13'
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
                $this->addUsingAlias(PostTableMap::COL_POST_UPDATE, $update['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($update['max'])) {
                $this->addUsingAlias(PostTableMap::COL_POST_UPDATE, $update['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PostTableMap::COL_POST_UPDATE, $update, $comparison);

        return $this;
    }

    /**
     * Filter the query on the post_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE post_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE post_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE post_created > '2011-03-13'
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
                $this->addUsingAlias(PostTableMap::COL_POST_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PostTableMap::COL_POST_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PostTableMap::COL_POST_CREATED, $createdAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the post_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE post_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE post_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE post_updated > '2011-03-13'
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
                $this->addUsingAlias(PostTableMap::COL_POST_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PostTableMap::COL_POST_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PostTableMap::COL_POST_UPDATED, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \Model\User object
     *
     * @param \Model\User|ObjectCollection $user The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUser($user, ?string $comparison = null)
    {
        if ($user instanceof \Model\User) {
            return $this
                ->addUsingAlias(PostTableMap::COL_USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(PostTableMap::COL_USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \Model\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinUser(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\Model\UserQuery');
    }

    /**
     * Use the User relation User object
     *
     * @param callable(\Model\UserQuery):\Model\UserQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withUserQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useUserQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to User table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\UserQuery The inner query object of the EXISTS statement
     */
    public function useUserExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\UserQuery */
        $q = $this->useExistsQuery('User', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to User table for a NOT EXISTS query.
     *
     * @see useUserExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\UserQuery The inner query object of the NOT EXISTS statement
     */
    public function useUserNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\UserQuery */
        $q = $this->useExistsQuery('User', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to User table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\UserQuery The inner query object of the IN statement
     */
    public function useInUserQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\UserQuery */
        $q = $this->useInQuery('User', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to User table for a NOT IN query.
     *
     * @see useUserInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\UserQuery The inner query object of the NOT IN statement
     */
    public function useNotInUserQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\UserQuery */
        $q = $this->useInQuery('User', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Site object
     *
     * @param \Model\Site|ObjectCollection $site The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySite($site, ?string $comparison = null)
    {
        if ($site instanceof \Model\Site) {
            return $this
                ->addUsingAlias(PostTableMap::COL_SITE_ID, $site->getId(), $comparison);
        } elseif ($site instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(PostTableMap::COL_SITE_ID, $site->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterBySite() only accepts arguments of type \Model\Site or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Site relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinSite(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Site');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Site');
        }

        return $this;
    }

    /**
     * Use the Site relation Site object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\SiteQuery A secondary query class using the current class as primary query
     */
    public function useSiteQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSite($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Site', '\Model\SiteQuery');
    }

    /**
     * Use the Site relation Site object
     *
     * @param callable(\Model\SiteQuery):\Model\SiteQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withSiteQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useSiteQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Site table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\SiteQuery The inner query object of the EXISTS statement
     */
    public function useSiteExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\SiteQuery */
        $q = $this->useExistsQuery('Site', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Site table for a NOT EXISTS query.
     *
     * @see useSiteExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\SiteQuery The inner query object of the NOT EXISTS statement
     */
    public function useSiteNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\SiteQuery */
        $q = $this->useExistsQuery('Site', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Site table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\SiteQuery The inner query object of the IN statement
     */
    public function useInSiteQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\SiteQuery */
        $q = $this->useInQuery('Site', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Site table for a NOT IN query.
     *
     * @see useSiteInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\SiteQuery The inner query object of the NOT IN statement
     */
    public function useNotInSiteQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\SiteQuery */
        $q = $this->useInQuery('Site', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\BlogCategory object
     *
     * @param \Model\BlogCategory|ObjectCollection $blogCategory The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByBlogCategory($blogCategory, ?string $comparison = null)
    {
        if ($blogCategory instanceof \Model\BlogCategory) {
            return $this
                ->addUsingAlias(PostTableMap::COL_CATEGORY_ID, $blogCategory->getId(), $comparison);
        } elseif ($blogCategory instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(PostTableMap::COL_CATEGORY_ID, $blogCategory->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByBlogCategory() only accepts arguments of type \Model\BlogCategory or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the BlogCategory relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinBlogCategory(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('BlogCategory');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'BlogCategory');
        }

        return $this;
    }

    /**
     * Use the BlogCategory relation BlogCategory object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\BlogCategoryQuery A secondary query class using the current class as primary query
     */
    public function useBlogCategoryQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinBlogCategory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'BlogCategory', '\Model\BlogCategoryQuery');
    }

    /**
     * Use the BlogCategory relation BlogCategory object
     *
     * @param callable(\Model\BlogCategoryQuery):\Model\BlogCategoryQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withBlogCategoryQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useBlogCategoryQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to BlogCategory table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\BlogCategoryQuery The inner query object of the EXISTS statement
     */
    public function useBlogCategoryExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\BlogCategoryQuery */
        $q = $this->useExistsQuery('BlogCategory', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to BlogCategory table for a NOT EXISTS query.
     *
     * @see useBlogCategoryExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\BlogCategoryQuery The inner query object of the NOT EXISTS statement
     */
    public function useBlogCategoryNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\BlogCategoryQuery */
        $q = $this->useExistsQuery('BlogCategory', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to BlogCategory table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\BlogCategoryQuery The inner query object of the IN statement
     */
    public function useInBlogCategoryQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\BlogCategoryQuery */
        $q = $this->useInQuery('BlogCategory', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to BlogCategory table for a NOT IN query.
     *
     * @see useBlogCategoryInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\BlogCategoryQuery The inner query object of the NOT IN statement
     */
    public function useNotInBlogCategoryQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\BlogCategoryQuery */
        $q = $this->useInQuery('BlogCategory', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Image object
     *
     * @param \Model\Image|ObjectCollection $image the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByImage($image, ?string $comparison = null)
    {
        if ($image instanceof \Model\Image) {
            $this
                ->addUsingAlias(PostTableMap::COL_POST_ID, $image->getPostId(), $comparison);

            return $this;
        } elseif ($image instanceof ObjectCollection) {
            $this
                ->useImageQuery()
                ->filterByPrimaryKeys($image->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByImage() only accepts arguments of type \Model\Image or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Image relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinImage(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Image');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Image');
        }

        return $this;
    }

    /**
     * Use the Image relation Image object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\ImageQuery A secondary query class using the current class as primary query
     */
    public function useImageQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinImage($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Image', '\Model\ImageQuery');
    }

    /**
     * Use the Image relation Image object
     *
     * @param callable(\Model\ImageQuery):\Model\ImageQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withImageQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useImageQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Image table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\ImageQuery The inner query object of the EXISTS statement
     */
    public function useImageExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\ImageQuery */
        $q = $this->useExistsQuery('Image', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Image table for a NOT EXISTS query.
     *
     * @see useImageExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\ImageQuery The inner query object of the NOT EXISTS statement
     */
    public function useImageNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\ImageQuery */
        $q = $this->useExistsQuery('Image', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Image table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\ImageQuery The inner query object of the IN statement
     */
    public function useInImageQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\ImageQuery */
        $q = $this->useInQuery('Image', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Image table for a NOT IN query.
     *
     * @see useImageInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\ImageQuery The inner query object of the NOT IN statement
     */
    public function useNotInImageQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\ImageQuery */
        $q = $this->useInQuery('Image', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Exclude object from result
     *
     * @param ChildPost $post Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($post = null)
    {
        if ($post) {
            $this->addUsingAlias(PostTableMap::COL_POST_ID, $post->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the posts table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PostTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PostTableMap::clearInstancePool();
            PostTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PostTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PostTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PostTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PostTableMap::clearRelatedInstancePool();

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
        $this->addUsingAlias(PostTableMap::COL_POST_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(PostTableMap::COL_POST_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(PostTableMap::COL_POST_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(PostTableMap::COL_POST_CREATED);

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
        $this->addUsingAlias(PostTableMap::COL_POST_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(PostTableMap::COL_POST_CREATED);

        return $this;
    }

}
