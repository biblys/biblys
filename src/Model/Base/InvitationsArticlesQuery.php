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


namespace Model\Base;

use \Exception;
use \PDO;
use Model\InvitationsArticles as ChildInvitationsArticles;
use Model\InvitationsArticlesQuery as ChildInvitationsArticlesQuery;
use Model\Map\InvitationsArticlesTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `invitations_articles` table.
 *
 * @method     ChildInvitationsArticlesQuery orderByInvitationId($order = Criteria::ASC) Order by the invitation_id column
 * @method     ChildInvitationsArticlesQuery orderByArticleId($order = Criteria::ASC) Order by the article_id column
 *
 * @method     ChildInvitationsArticlesQuery groupByInvitationId() Group by the invitation_id column
 * @method     ChildInvitationsArticlesQuery groupByArticleId() Group by the article_id column
 *
 * @method     ChildInvitationsArticlesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildInvitationsArticlesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildInvitationsArticlesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildInvitationsArticlesQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildInvitationsArticlesQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildInvitationsArticlesQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildInvitationsArticlesQuery leftJoinInvitation($relationAlias = null) Adds a LEFT JOIN clause to the query using the Invitation relation
 * @method     ChildInvitationsArticlesQuery rightJoinInvitation($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Invitation relation
 * @method     ChildInvitationsArticlesQuery innerJoinInvitation($relationAlias = null) Adds a INNER JOIN clause to the query using the Invitation relation
 *
 * @method     ChildInvitationsArticlesQuery joinWithInvitation($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Invitation relation
 *
 * @method     ChildInvitationsArticlesQuery leftJoinWithInvitation() Adds a LEFT JOIN clause and with to the query using the Invitation relation
 * @method     ChildInvitationsArticlesQuery rightJoinWithInvitation() Adds a RIGHT JOIN clause and with to the query using the Invitation relation
 * @method     ChildInvitationsArticlesQuery innerJoinWithInvitation() Adds a INNER JOIN clause and with to the query using the Invitation relation
 *
 * @method     ChildInvitationsArticlesQuery leftJoinArticle($relationAlias = null) Adds a LEFT JOIN clause to the query using the Article relation
 * @method     ChildInvitationsArticlesQuery rightJoinArticle($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Article relation
 * @method     ChildInvitationsArticlesQuery innerJoinArticle($relationAlias = null) Adds a INNER JOIN clause to the query using the Article relation
 *
 * @method     ChildInvitationsArticlesQuery joinWithArticle($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Article relation
 *
 * @method     ChildInvitationsArticlesQuery leftJoinWithArticle() Adds a LEFT JOIN clause and with to the query using the Article relation
 * @method     ChildInvitationsArticlesQuery rightJoinWithArticle() Adds a RIGHT JOIN clause and with to the query using the Article relation
 * @method     ChildInvitationsArticlesQuery innerJoinWithArticle() Adds a INNER JOIN clause and with to the query using the Article relation
 *
 * @method     \Model\InvitationQuery|\Model\ArticleQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildInvitationsArticles|null findOne(?ConnectionInterface $con = null) Return the first ChildInvitationsArticles matching the query
 * @method     ChildInvitationsArticles findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildInvitationsArticles matching the query, or a new ChildInvitationsArticles object populated from the query conditions when no match is found
 *
 * @method     ChildInvitationsArticles|null findOneByInvitationId(int $invitation_id) Return the first ChildInvitationsArticles filtered by the invitation_id column
 * @method     ChildInvitationsArticles|null findOneByArticleId(int $article_id) Return the first ChildInvitationsArticles filtered by the article_id column
 *
 * @method     ChildInvitationsArticles requirePk($key, ?ConnectionInterface $con = null) Return the ChildInvitationsArticles by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvitationsArticles requireOne(?ConnectionInterface $con = null) Return the first ChildInvitationsArticles matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildInvitationsArticles requireOneByInvitationId(int $invitation_id) Return the first ChildInvitationsArticles filtered by the invitation_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInvitationsArticles requireOneByArticleId(int $article_id) Return the first ChildInvitationsArticles filtered by the article_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildInvitationsArticles[]|Collection find(?ConnectionInterface $con = null) Return ChildInvitationsArticles objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildInvitationsArticles> find(?ConnectionInterface $con = null) Return ChildInvitationsArticles objects based on current ModelCriteria
 *
 * @method     ChildInvitationsArticles[]|Collection findByInvitationId(int|array<int> $invitation_id) Return ChildInvitationsArticles objects filtered by the invitation_id column
 * @psalm-method Collection&\Traversable<ChildInvitationsArticles> findByInvitationId(int|array<int> $invitation_id) Return ChildInvitationsArticles objects filtered by the invitation_id column
 * @method     ChildInvitationsArticles[]|Collection findByArticleId(int|array<int> $article_id) Return ChildInvitationsArticles objects filtered by the article_id column
 * @psalm-method Collection&\Traversable<ChildInvitationsArticles> findByArticleId(int|array<int> $article_id) Return ChildInvitationsArticles objects filtered by the article_id column
 *
 * @method     ChildInvitationsArticles[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildInvitationsArticles> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class InvitationsArticlesQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\InvitationsArticlesQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\InvitationsArticles', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildInvitationsArticlesQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildInvitationsArticlesQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildInvitationsArticlesQuery) {
            return $criteria;
        }
        $query = new ChildInvitationsArticlesQuery();
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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array[$invitation_id, $article_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildInvitationsArticles|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(InvitationsArticlesTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = InvitationsArticlesTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildInvitationsArticles A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT invitation_id, article_id FROM invitations_articles WHERE invitation_id = :p0 AND article_id = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildInvitationsArticles $obj */
            $obj = new ChildInvitationsArticles();
            $obj->hydrate($row);
            InvitationsArticlesTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildInvitationsArticles|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
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
        $this->addUsingAlias(InvitationsArticlesTableMap::COL_INVITATION_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(InvitationsArticlesTableMap::COL_ARTICLE_ID, $key[1], Criteria::EQUAL);

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
        if (empty($keys)) {
            $this->add(null, '1<>1', Criteria::CUSTOM);

            return $this;
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(InvitationsArticlesTableMap::COL_INVITATION_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(InvitationsArticlesTableMap::COL_ARTICLE_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the invitation_id column
     *
     * Example usage:
     * <code>
     * $query->filterByInvitationId(1234); // WHERE invitation_id = 1234
     * $query->filterByInvitationId(array(12, 34)); // WHERE invitation_id IN (12, 34)
     * $query->filterByInvitationId(array('min' => 12)); // WHERE invitation_id > 12
     * </code>
     *
     * @see       filterByInvitation()
     *
     * @param mixed $invitationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByInvitationId($invitationId = null, ?string $comparison = null)
    {
        if (is_array($invitationId)) {
            $useMinMax = false;
            if (isset($invitationId['min'])) {
                $this->addUsingAlias(InvitationsArticlesTableMap::COL_INVITATION_ID, $invitationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($invitationId['max'])) {
                $this->addUsingAlias(InvitationsArticlesTableMap::COL_INVITATION_ID, $invitationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(InvitationsArticlesTableMap::COL_INVITATION_ID, $invitationId, $comparison);

        return $this;
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
     * @see       filterByArticle()
     *
     * @param mixed $articleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByArticleId($articleId = null, ?string $comparison = null)
    {
        if (is_array($articleId)) {
            $useMinMax = false;
            if (isset($articleId['min'])) {
                $this->addUsingAlias(InvitationsArticlesTableMap::COL_ARTICLE_ID, $articleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($articleId['max'])) {
                $this->addUsingAlias(InvitationsArticlesTableMap::COL_ARTICLE_ID, $articleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(InvitationsArticlesTableMap::COL_ARTICLE_ID, $articleId, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \Model\Invitation object
     *
     * @param \Model\Invitation|ObjectCollection $invitation The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByInvitation($invitation, ?string $comparison = null)
    {
        if ($invitation instanceof \Model\Invitation) {
            return $this
                ->addUsingAlias(InvitationsArticlesTableMap::COL_INVITATION_ID, $invitation->getId(), $comparison);
        } elseif ($invitation instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(InvitationsArticlesTableMap::COL_INVITATION_ID, $invitation->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByInvitation() only accepts arguments of type \Model\Invitation or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Invitation relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinInvitation(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Invitation');

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
            $this->addJoinObject($join, 'Invitation');
        }

        return $this;
    }

    /**
     * Use the Invitation relation Invitation object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\InvitationQuery A secondary query class using the current class as primary query
     */
    public function useInvitationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInvitation($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Invitation', '\Model\InvitationQuery');
    }

    /**
     * Use the Invitation relation Invitation object
     *
     * @param callable(\Model\InvitationQuery):\Model\InvitationQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withInvitationQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useInvitationQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Invitation table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\InvitationQuery The inner query object of the EXISTS statement
     */
    public function useInvitationExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\InvitationQuery */
        $q = $this->useExistsQuery('Invitation', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Invitation table for a NOT EXISTS query.
     *
     * @see useInvitationExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\InvitationQuery The inner query object of the NOT EXISTS statement
     */
    public function useInvitationNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\InvitationQuery */
        $q = $this->useExistsQuery('Invitation', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Invitation table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\InvitationQuery The inner query object of the IN statement
     */
    public function useInInvitationQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\InvitationQuery */
        $q = $this->useInQuery('Invitation', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Invitation table for a NOT IN query.
     *
     * @see useInvitationInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\InvitationQuery The inner query object of the NOT IN statement
     */
    public function useNotInInvitationQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\InvitationQuery */
        $q = $this->useInQuery('Invitation', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Article object
     *
     * @param \Model\Article|ObjectCollection $article The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByArticle($article, ?string $comparison = null)
    {
        if ($article instanceof \Model\Article) {
            return $this
                ->addUsingAlias(InvitationsArticlesTableMap::COL_ARTICLE_ID, $article->getId(), $comparison);
        } elseif ($article instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(InvitationsArticlesTableMap::COL_ARTICLE_ID, $article->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByArticle() only accepts arguments of type \Model\Article or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Article relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinArticle(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Article');

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
            $this->addJoinObject($join, 'Article');
        }

        return $this;
    }

    /**
     * Use the Article relation Article object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\ArticleQuery A secondary query class using the current class as primary query
     */
    public function useArticleQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinArticle($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Article', '\Model\ArticleQuery');
    }

    /**
     * Use the Article relation Article object
     *
     * @param callable(\Model\ArticleQuery):\Model\ArticleQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withArticleQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useArticleQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Article table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\ArticleQuery The inner query object of the EXISTS statement
     */
    public function useArticleExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\ArticleQuery */
        $q = $this->useExistsQuery('Article', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Article table for a NOT EXISTS query.
     *
     * @see useArticleExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\ArticleQuery The inner query object of the NOT EXISTS statement
     */
    public function useArticleNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\ArticleQuery */
        $q = $this->useExistsQuery('Article', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Article table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\ArticleQuery The inner query object of the IN statement
     */
    public function useInArticleQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\ArticleQuery */
        $q = $this->useInQuery('Article', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Article table for a NOT IN query.
     *
     * @see useArticleInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\ArticleQuery The inner query object of the NOT IN statement
     */
    public function useNotInArticleQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\ArticleQuery */
        $q = $this->useInQuery('Article', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Exclude object from result
     *
     * @param ChildInvitationsArticles $invitationsArticles Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($invitationsArticles = null)
    {
        if ($invitationsArticles) {
            $this->addCond('pruneCond0', $this->getAliasedColName(InvitationsArticlesTableMap::COL_INVITATION_ID), $invitationsArticles->getInvitationId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(InvitationsArticlesTableMap::COL_ARTICLE_ID), $invitationsArticles->getArticleId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the invitations_articles table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(InvitationsArticlesTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            InvitationsArticlesTableMap::clearInstancePool();
            InvitationsArticlesTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(InvitationsArticlesTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(InvitationsArticlesTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            InvitationsArticlesTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            InvitationsArticlesTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

}
