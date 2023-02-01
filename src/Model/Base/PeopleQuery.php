<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\People as ChildPeople;
use Model\PeopleQuery as ChildPeopleQuery;
use Model\Map\PeopleTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `people` table.
 *
 * Intervenants
 *
 * @method     ChildPeopleQuery orderById($order = Criteria::ASC) Order by the people_id column
 * @method     ChildPeopleQuery orderByFirstName($order = Criteria::ASC) Order by the people_first_name column
 * @method     ChildPeopleQuery orderByLastName($order = Criteria::ASC) Order by the people_last_name column
 * @method     ChildPeopleQuery orderByName($order = Criteria::ASC) Order by the people_name column
 * @method     ChildPeopleQuery orderByAlpha($order = Criteria::ASC) Order by the people_alpha column
 * @method     ChildPeopleQuery orderByUrlOld($order = Criteria::ASC) Order by the people_url_old column
 * @method     ChildPeopleQuery orderByUrl($order = Criteria::ASC) Order by the people_url column
 * @method     ChildPeopleQuery orderByPseudo($order = Criteria::ASC) Order by the people_pseudo column
 * @method     ChildPeopleQuery orderByNoosfereId($order = Criteria::ASC) Order by the people_noosfere_id column
 * @method     ChildPeopleQuery orderByBirth($order = Criteria::ASC) Order by the people_birth column
 * @method     ChildPeopleQuery orderByDeath($order = Criteria::ASC) Order by the people_death column
 * @method     ChildPeopleQuery orderByGender($order = Criteria::ASC) Order by the people_gender column
 * @method     ChildPeopleQuery orderByNation($order = Criteria::ASC) Order by the people_nation column
 * @method     ChildPeopleQuery orderByBio($order = Criteria::ASC) Order by the people_bio column
 * @method     ChildPeopleQuery orderBySite($order = Criteria::ASC) Order by the people_site column
 * @method     ChildPeopleQuery orderByFacebook($order = Criteria::ASC) Order by the people_facebook column
 * @method     ChildPeopleQuery orderByTwitter($order = Criteria::ASC) Order by the people_twitter column
 * @method     ChildPeopleQuery orderByHits($order = Criteria::ASC) Order by the people_hits column
 * @method     ChildPeopleQuery orderByDate($order = Criteria::ASC) Order by the people_date column
 * @method     ChildPeopleQuery orderByInsert($order = Criteria::ASC) Order by the people_insert column
 * @method     ChildPeopleQuery orderByUpdate($order = Criteria::ASC) Order by the people_update column
 * @method     ChildPeopleQuery orderByCreatedAt($order = Criteria::ASC) Order by the people_created column
 * @method     ChildPeopleQuery orderByUpdatedAt($order = Criteria::ASC) Order by the people_updated column
 *
 * @method     ChildPeopleQuery groupById() Group by the people_id column
 * @method     ChildPeopleQuery groupByFirstName() Group by the people_first_name column
 * @method     ChildPeopleQuery groupByLastName() Group by the people_last_name column
 * @method     ChildPeopleQuery groupByName() Group by the people_name column
 * @method     ChildPeopleQuery groupByAlpha() Group by the people_alpha column
 * @method     ChildPeopleQuery groupByUrlOld() Group by the people_url_old column
 * @method     ChildPeopleQuery groupByUrl() Group by the people_url column
 * @method     ChildPeopleQuery groupByPseudo() Group by the people_pseudo column
 * @method     ChildPeopleQuery groupByNoosfereId() Group by the people_noosfere_id column
 * @method     ChildPeopleQuery groupByBirth() Group by the people_birth column
 * @method     ChildPeopleQuery groupByDeath() Group by the people_death column
 * @method     ChildPeopleQuery groupByGender() Group by the people_gender column
 * @method     ChildPeopleQuery groupByNation() Group by the people_nation column
 * @method     ChildPeopleQuery groupByBio() Group by the people_bio column
 * @method     ChildPeopleQuery groupBySite() Group by the people_site column
 * @method     ChildPeopleQuery groupByFacebook() Group by the people_facebook column
 * @method     ChildPeopleQuery groupByTwitter() Group by the people_twitter column
 * @method     ChildPeopleQuery groupByHits() Group by the people_hits column
 * @method     ChildPeopleQuery groupByDate() Group by the people_date column
 * @method     ChildPeopleQuery groupByInsert() Group by the people_insert column
 * @method     ChildPeopleQuery groupByUpdate() Group by the people_update column
 * @method     ChildPeopleQuery groupByCreatedAt() Group by the people_created column
 * @method     ChildPeopleQuery groupByUpdatedAt() Group by the people_updated column
 *
 * @method     ChildPeopleQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPeopleQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPeopleQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPeopleQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPeopleQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPeopleQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPeopleQuery leftJoinRole($relationAlias = null) Adds a LEFT JOIN clause to the query using the Role relation
 * @method     ChildPeopleQuery rightJoinRole($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Role relation
 * @method     ChildPeopleQuery innerJoinRole($relationAlias = null) Adds a INNER JOIN clause to the query using the Role relation
 *
 * @method     ChildPeopleQuery joinWithRole($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Role relation
 *
 * @method     ChildPeopleQuery leftJoinWithRole() Adds a LEFT JOIN clause and with to the query using the Role relation
 * @method     ChildPeopleQuery rightJoinWithRole() Adds a RIGHT JOIN clause and with to the query using the Role relation
 * @method     ChildPeopleQuery innerJoinWithRole() Adds a INNER JOIN clause and with to the query using the Role relation
 *
 * @method     \Model\RoleQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPeople|null findOne(?ConnectionInterface $con = null) Return the first ChildPeople matching the query
 * @method     ChildPeople findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildPeople matching the query, or a new ChildPeople object populated from the query conditions when no match is found
 *
 * @method     ChildPeople|null findOneById(int $people_id) Return the first ChildPeople filtered by the people_id column
 * @method     ChildPeople|null findOneByFirstName(string $people_first_name) Return the first ChildPeople filtered by the people_first_name column
 * @method     ChildPeople|null findOneByLastName(string $people_last_name) Return the first ChildPeople filtered by the people_last_name column
 * @method     ChildPeople|null findOneByName(string $people_name) Return the first ChildPeople filtered by the people_name column
 * @method     ChildPeople|null findOneByAlpha(string $people_alpha) Return the first ChildPeople filtered by the people_alpha column
 * @method     ChildPeople|null findOneByUrlOld(string $people_url_old) Return the first ChildPeople filtered by the people_url_old column
 * @method     ChildPeople|null findOneByUrl(string $people_url) Return the first ChildPeople filtered by the people_url column
 * @method     ChildPeople|null findOneByPseudo(int $people_pseudo) Return the first ChildPeople filtered by the people_pseudo column
 * @method     ChildPeople|null findOneByNoosfereId(int $people_noosfere_id) Return the first ChildPeople filtered by the people_noosfere_id column
 * @method     ChildPeople|null findOneByBirth(int $people_birth) Return the first ChildPeople filtered by the people_birth column
 * @method     ChildPeople|null findOneByDeath(int $people_death) Return the first ChildPeople filtered by the people_death column
 * @method     ChildPeople|null findOneByGender(string $people_gender) Return the first ChildPeople filtered by the people_gender column
 * @method     ChildPeople|null findOneByNation(string $people_nation) Return the first ChildPeople filtered by the people_nation column
 * @method     ChildPeople|null findOneByBio(string $people_bio) Return the first ChildPeople filtered by the people_bio column
 * @method     ChildPeople|null findOneBySite(string $people_site) Return the first ChildPeople filtered by the people_site column
 * @method     ChildPeople|null findOneByFacebook(string $people_facebook) Return the first ChildPeople filtered by the people_facebook column
 * @method     ChildPeople|null findOneByTwitter(string $people_twitter) Return the first ChildPeople filtered by the people_twitter column
 * @method     ChildPeople|null findOneByHits(int $people_hits) Return the first ChildPeople filtered by the people_hits column
 * @method     ChildPeople|null findOneByDate(string $people_date) Return the first ChildPeople filtered by the people_date column
 * @method     ChildPeople|null findOneByInsert(string $people_insert) Return the first ChildPeople filtered by the people_insert column
 * @method     ChildPeople|null findOneByUpdate(string $people_update) Return the first ChildPeople filtered by the people_update column
 * @method     ChildPeople|null findOneByCreatedAt(string $people_created) Return the first ChildPeople filtered by the people_created column
 * @method     ChildPeople|null findOneByUpdatedAt(string $people_updated) Return the first ChildPeople filtered by the people_updated column
 *
 * @method     ChildPeople requirePk($key, ?ConnectionInterface $con = null) Return the ChildPeople by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPeople requireOne(?ConnectionInterface $con = null) Return the first ChildPeople matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPeople requireOneById(int $people_id) Return the first ChildPeople filtered by the people_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPeople requireOneByFirstName(string $people_first_name) Return the first ChildPeople filtered by the people_first_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPeople requireOneByLastName(string $people_last_name) Return the first ChildPeople filtered by the people_last_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPeople requireOneByName(string $people_name) Return the first ChildPeople filtered by the people_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPeople requireOneByAlpha(string $people_alpha) Return the first ChildPeople filtered by the people_alpha column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPeople requireOneByUrlOld(string $people_url_old) Return the first ChildPeople filtered by the people_url_old column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPeople requireOneByUrl(string $people_url) Return the first ChildPeople filtered by the people_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPeople requireOneByPseudo(int $people_pseudo) Return the first ChildPeople filtered by the people_pseudo column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPeople requireOneByNoosfereId(int $people_noosfere_id) Return the first ChildPeople filtered by the people_noosfere_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPeople requireOneByBirth(int $people_birth) Return the first ChildPeople filtered by the people_birth column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPeople requireOneByDeath(int $people_death) Return the first ChildPeople filtered by the people_death column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPeople requireOneByGender(string $people_gender) Return the first ChildPeople filtered by the people_gender column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPeople requireOneByNation(string $people_nation) Return the first ChildPeople filtered by the people_nation column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPeople requireOneByBio(string $people_bio) Return the first ChildPeople filtered by the people_bio column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPeople requireOneBySite(string $people_site) Return the first ChildPeople filtered by the people_site column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPeople requireOneByFacebook(string $people_facebook) Return the first ChildPeople filtered by the people_facebook column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPeople requireOneByTwitter(string $people_twitter) Return the first ChildPeople filtered by the people_twitter column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPeople requireOneByHits(int $people_hits) Return the first ChildPeople filtered by the people_hits column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPeople requireOneByDate(string $people_date) Return the first ChildPeople filtered by the people_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPeople requireOneByInsert(string $people_insert) Return the first ChildPeople filtered by the people_insert column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPeople requireOneByUpdate(string $people_update) Return the first ChildPeople filtered by the people_update column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPeople requireOneByCreatedAt(string $people_created) Return the first ChildPeople filtered by the people_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPeople requireOneByUpdatedAt(string $people_updated) Return the first ChildPeople filtered by the people_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPeople[]|Collection find(?ConnectionInterface $con = null) Return ChildPeople objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildPeople> find(?ConnectionInterface $con = null) Return ChildPeople objects based on current ModelCriteria
 *
 * @method     ChildPeople[]|Collection findById(int|array<int> $people_id) Return ChildPeople objects filtered by the people_id column
 * @psalm-method Collection&\Traversable<ChildPeople> findById(int|array<int> $people_id) Return ChildPeople objects filtered by the people_id column
 * @method     ChildPeople[]|Collection findByFirstName(string|array<string> $people_first_name) Return ChildPeople objects filtered by the people_first_name column
 * @psalm-method Collection&\Traversable<ChildPeople> findByFirstName(string|array<string> $people_first_name) Return ChildPeople objects filtered by the people_first_name column
 * @method     ChildPeople[]|Collection findByLastName(string|array<string> $people_last_name) Return ChildPeople objects filtered by the people_last_name column
 * @psalm-method Collection&\Traversable<ChildPeople> findByLastName(string|array<string> $people_last_name) Return ChildPeople objects filtered by the people_last_name column
 * @method     ChildPeople[]|Collection findByName(string|array<string> $people_name) Return ChildPeople objects filtered by the people_name column
 * @psalm-method Collection&\Traversable<ChildPeople> findByName(string|array<string> $people_name) Return ChildPeople objects filtered by the people_name column
 * @method     ChildPeople[]|Collection findByAlpha(string|array<string> $people_alpha) Return ChildPeople objects filtered by the people_alpha column
 * @psalm-method Collection&\Traversable<ChildPeople> findByAlpha(string|array<string> $people_alpha) Return ChildPeople objects filtered by the people_alpha column
 * @method     ChildPeople[]|Collection findByUrlOld(string|array<string> $people_url_old) Return ChildPeople objects filtered by the people_url_old column
 * @psalm-method Collection&\Traversable<ChildPeople> findByUrlOld(string|array<string> $people_url_old) Return ChildPeople objects filtered by the people_url_old column
 * @method     ChildPeople[]|Collection findByUrl(string|array<string> $people_url) Return ChildPeople objects filtered by the people_url column
 * @psalm-method Collection&\Traversable<ChildPeople> findByUrl(string|array<string> $people_url) Return ChildPeople objects filtered by the people_url column
 * @method     ChildPeople[]|Collection findByPseudo(int|array<int> $people_pseudo) Return ChildPeople objects filtered by the people_pseudo column
 * @psalm-method Collection&\Traversable<ChildPeople> findByPseudo(int|array<int> $people_pseudo) Return ChildPeople objects filtered by the people_pseudo column
 * @method     ChildPeople[]|Collection findByNoosfereId(int|array<int> $people_noosfere_id) Return ChildPeople objects filtered by the people_noosfere_id column
 * @psalm-method Collection&\Traversable<ChildPeople> findByNoosfereId(int|array<int> $people_noosfere_id) Return ChildPeople objects filtered by the people_noosfere_id column
 * @method     ChildPeople[]|Collection findByBirth(int|array<int> $people_birth) Return ChildPeople objects filtered by the people_birth column
 * @psalm-method Collection&\Traversable<ChildPeople> findByBirth(int|array<int> $people_birth) Return ChildPeople objects filtered by the people_birth column
 * @method     ChildPeople[]|Collection findByDeath(int|array<int> $people_death) Return ChildPeople objects filtered by the people_death column
 * @psalm-method Collection&\Traversable<ChildPeople> findByDeath(int|array<int> $people_death) Return ChildPeople objects filtered by the people_death column
 * @method     ChildPeople[]|Collection findByGender(string|array<string> $people_gender) Return ChildPeople objects filtered by the people_gender column
 * @psalm-method Collection&\Traversable<ChildPeople> findByGender(string|array<string> $people_gender) Return ChildPeople objects filtered by the people_gender column
 * @method     ChildPeople[]|Collection findByNation(string|array<string> $people_nation) Return ChildPeople objects filtered by the people_nation column
 * @psalm-method Collection&\Traversable<ChildPeople> findByNation(string|array<string> $people_nation) Return ChildPeople objects filtered by the people_nation column
 * @method     ChildPeople[]|Collection findByBio(string|array<string> $people_bio) Return ChildPeople objects filtered by the people_bio column
 * @psalm-method Collection&\Traversable<ChildPeople> findByBio(string|array<string> $people_bio) Return ChildPeople objects filtered by the people_bio column
 * @method     ChildPeople[]|Collection findBySite(string|array<string> $people_site) Return ChildPeople objects filtered by the people_site column
 * @psalm-method Collection&\Traversable<ChildPeople> findBySite(string|array<string> $people_site) Return ChildPeople objects filtered by the people_site column
 * @method     ChildPeople[]|Collection findByFacebook(string|array<string> $people_facebook) Return ChildPeople objects filtered by the people_facebook column
 * @psalm-method Collection&\Traversable<ChildPeople> findByFacebook(string|array<string> $people_facebook) Return ChildPeople objects filtered by the people_facebook column
 * @method     ChildPeople[]|Collection findByTwitter(string|array<string> $people_twitter) Return ChildPeople objects filtered by the people_twitter column
 * @psalm-method Collection&\Traversable<ChildPeople> findByTwitter(string|array<string> $people_twitter) Return ChildPeople objects filtered by the people_twitter column
 * @method     ChildPeople[]|Collection findByHits(int|array<int> $people_hits) Return ChildPeople objects filtered by the people_hits column
 * @psalm-method Collection&\Traversable<ChildPeople> findByHits(int|array<int> $people_hits) Return ChildPeople objects filtered by the people_hits column
 * @method     ChildPeople[]|Collection findByDate(string|array<string> $people_date) Return ChildPeople objects filtered by the people_date column
 * @psalm-method Collection&\Traversable<ChildPeople> findByDate(string|array<string> $people_date) Return ChildPeople objects filtered by the people_date column
 * @method     ChildPeople[]|Collection findByInsert(string|array<string> $people_insert) Return ChildPeople objects filtered by the people_insert column
 * @psalm-method Collection&\Traversable<ChildPeople> findByInsert(string|array<string> $people_insert) Return ChildPeople objects filtered by the people_insert column
 * @method     ChildPeople[]|Collection findByUpdate(string|array<string> $people_update) Return ChildPeople objects filtered by the people_update column
 * @psalm-method Collection&\Traversable<ChildPeople> findByUpdate(string|array<string> $people_update) Return ChildPeople objects filtered by the people_update column
 * @method     ChildPeople[]|Collection findByCreatedAt(string|array<string> $people_created) Return ChildPeople objects filtered by the people_created column
 * @psalm-method Collection&\Traversable<ChildPeople> findByCreatedAt(string|array<string> $people_created) Return ChildPeople objects filtered by the people_created column
 * @method     ChildPeople[]|Collection findByUpdatedAt(string|array<string> $people_updated) Return ChildPeople objects filtered by the people_updated column
 * @psalm-method Collection&\Traversable<ChildPeople> findByUpdatedAt(string|array<string> $people_updated) Return ChildPeople objects filtered by the people_updated column
 *
 * @method     ChildPeople[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildPeople> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class PeopleQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\PeopleQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\People', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPeopleQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPeopleQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildPeopleQuery) {
            return $criteria;
        }
        $query = new ChildPeopleQuery();
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
     * @return ChildPeople|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PeopleTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PeopleTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildPeople A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT people_id, people_first_name, people_last_name, people_name, people_alpha, people_url_old, people_url, people_pseudo, people_noosfere_id, people_birth, people_death, people_gender, people_nation, people_bio, people_site, people_facebook, people_twitter, people_hits, people_date, people_insert, people_update, people_created, people_updated FROM people WHERE people_id = :p0';
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
            /** @var ChildPeople $obj */
            $obj = new ChildPeople();
            $obj->hydrate($row);
            PeopleTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPeople|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the people_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE people_id = 1234
     * $query->filterById(array(12, 34)); // WHERE people_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE people_id > 12
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
                $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the people_first_name column
     *
     * Example usage:
     * <code>
     * $query->filterByFirstName('fooValue');   // WHERE people_first_name = 'fooValue'
     * $query->filterByFirstName('%fooValue%', Criteria::LIKE); // WHERE people_first_name LIKE '%fooValue%'
     * $query->filterByFirstName(['foo', 'bar']); // WHERE people_first_name IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $firstName The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFirstName($firstName = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($firstName)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_FIRST_NAME, $firstName, $comparison);

        return $this;
    }

    /**
     * Filter the query on the people_last_name column
     *
     * Example usage:
     * <code>
     * $query->filterByLastName('fooValue');   // WHERE people_last_name = 'fooValue'
     * $query->filterByLastName('%fooValue%', Criteria::LIKE); // WHERE people_last_name LIKE '%fooValue%'
     * $query->filterByLastName(['foo', 'bar']); // WHERE people_last_name IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $lastName The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByLastName($lastName = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lastName)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_LAST_NAME, $lastName, $comparison);

        return $this;
    }

    /**
     * Filter the query on the people_name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE people_name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE people_name LIKE '%fooValue%'
     * $query->filterByName(['foo', 'bar']); // WHERE people_name IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $name The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByName($name = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_NAME, $name, $comparison);

        return $this;
    }

    /**
     * Filter the query on the people_alpha column
     *
     * Example usage:
     * <code>
     * $query->filterByAlpha('fooValue');   // WHERE people_alpha = 'fooValue'
     * $query->filterByAlpha('%fooValue%', Criteria::LIKE); // WHERE people_alpha LIKE '%fooValue%'
     * $query->filterByAlpha(['foo', 'bar']); // WHERE people_alpha IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $alpha The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAlpha($alpha = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($alpha)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_ALPHA, $alpha, $comparison);

        return $this;
    }

    /**
     * Filter the query on the people_url_old column
     *
     * Example usage:
     * <code>
     * $query->filterByUrlOld('fooValue');   // WHERE people_url_old = 'fooValue'
     * $query->filterByUrlOld('%fooValue%', Criteria::LIKE); // WHERE people_url_old LIKE '%fooValue%'
     * $query->filterByUrlOld(['foo', 'bar']); // WHERE people_url_old IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $urlOld The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUrlOld($urlOld = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($urlOld)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_URL_OLD, $urlOld, $comparison);

        return $this;
    }

    /**
     * Filter the query on the people_url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE people_url = 'fooValue'
     * $query->filterByUrl('%fooValue%', Criteria::LIKE); // WHERE people_url LIKE '%fooValue%'
     * $query->filterByUrl(['foo', 'bar']); // WHERE people_url IN ('foo', 'bar')
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

        $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_URL, $url, $comparison);

        return $this;
    }

    /**
     * Filter the query on the people_pseudo column
     *
     * Example usage:
     * <code>
     * $query->filterByPseudo(1234); // WHERE people_pseudo = 1234
     * $query->filterByPseudo(array(12, 34)); // WHERE people_pseudo IN (12, 34)
     * $query->filterByPseudo(array('min' => 12)); // WHERE people_pseudo > 12
     * </code>
     *
     * @param mixed $pseudo The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPseudo($pseudo = null, ?string $comparison = null)
    {
        if (is_array($pseudo)) {
            $useMinMax = false;
            if (isset($pseudo['min'])) {
                $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_PSEUDO, $pseudo['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pseudo['max'])) {
                $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_PSEUDO, $pseudo['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_PSEUDO, $pseudo, $comparison);

        return $this;
    }

    /**
     * Filter the query on the people_noosfere_id column
     *
     * Example usage:
     * <code>
     * $query->filterByNoosfereId(1234); // WHERE people_noosfere_id = 1234
     * $query->filterByNoosfereId(array(12, 34)); // WHERE people_noosfere_id IN (12, 34)
     * $query->filterByNoosfereId(array('min' => 12)); // WHERE people_noosfere_id > 12
     * </code>
     *
     * @param mixed $noosfereId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByNoosfereId($noosfereId = null, ?string $comparison = null)
    {
        if (is_array($noosfereId)) {
            $useMinMax = false;
            if (isset($noosfereId['min'])) {
                $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_NOOSFERE_ID, $noosfereId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($noosfereId['max'])) {
                $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_NOOSFERE_ID, $noosfereId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_NOOSFERE_ID, $noosfereId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the people_birth column
     *
     * Example usage:
     * <code>
     * $query->filterByBirth(1234); // WHERE people_birth = 1234
     * $query->filterByBirth(array(12, 34)); // WHERE people_birth IN (12, 34)
     * $query->filterByBirth(array('min' => 12)); // WHERE people_birth > 12
     * </code>
     *
     * @param mixed $birth The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByBirth($birth = null, ?string $comparison = null)
    {
        if (is_array($birth)) {
            $useMinMax = false;
            if (isset($birth['min'])) {
                $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_BIRTH, $birth['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($birth['max'])) {
                $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_BIRTH, $birth['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_BIRTH, $birth, $comparison);

        return $this;
    }

    /**
     * Filter the query on the people_death column
     *
     * Example usage:
     * <code>
     * $query->filterByDeath(1234); // WHERE people_death = 1234
     * $query->filterByDeath(array(12, 34)); // WHERE people_death IN (12, 34)
     * $query->filterByDeath(array('min' => 12)); // WHERE people_death > 12
     * </code>
     *
     * @param mixed $death The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDeath($death = null, ?string $comparison = null)
    {
        if (is_array($death)) {
            $useMinMax = false;
            if (isset($death['min'])) {
                $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_DEATH, $death['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($death['max'])) {
                $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_DEATH, $death['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_DEATH, $death, $comparison);

        return $this;
    }

    /**
     * Filter the query on the people_gender column
     *
     * Example usage:
     * <code>
     * $query->filterByGender('fooValue');   // WHERE people_gender = 'fooValue'
     * $query->filterByGender('%fooValue%', Criteria::LIKE); // WHERE people_gender LIKE '%fooValue%'
     * $query->filterByGender(['foo', 'bar']); // WHERE people_gender IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $gender The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByGender($gender = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($gender)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_GENDER, $gender, $comparison);

        return $this;
    }

    /**
     * Filter the query on the people_nation column
     *
     * Example usage:
     * <code>
     * $query->filterByNation('fooValue');   // WHERE people_nation = 'fooValue'
     * $query->filterByNation('%fooValue%', Criteria::LIKE); // WHERE people_nation LIKE '%fooValue%'
     * $query->filterByNation(['foo', 'bar']); // WHERE people_nation IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $nation The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByNation($nation = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nation)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_NATION, $nation, $comparison);

        return $this;
    }

    /**
     * Filter the query on the people_bio column
     *
     * Example usage:
     * <code>
     * $query->filterByBio('fooValue');   // WHERE people_bio = 'fooValue'
     * $query->filterByBio('%fooValue%', Criteria::LIKE); // WHERE people_bio LIKE '%fooValue%'
     * $query->filterByBio(['foo', 'bar']); // WHERE people_bio IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $bio The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByBio($bio = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($bio)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_BIO, $bio, $comparison);

        return $this;
    }

    /**
     * Filter the query on the people_site column
     *
     * Example usage:
     * <code>
     * $query->filterBySite('fooValue');   // WHERE people_site = 'fooValue'
     * $query->filterBySite('%fooValue%', Criteria::LIKE); // WHERE people_site LIKE '%fooValue%'
     * $query->filterBySite(['foo', 'bar']); // WHERE people_site IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $site The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySite($site = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($site)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_SITE, $site, $comparison);

        return $this;
    }

    /**
     * Filter the query on the people_facebook column
     *
     * Example usage:
     * <code>
     * $query->filterByFacebook('fooValue');   // WHERE people_facebook = 'fooValue'
     * $query->filterByFacebook('%fooValue%', Criteria::LIKE); // WHERE people_facebook LIKE '%fooValue%'
     * $query->filterByFacebook(['foo', 'bar']); // WHERE people_facebook IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $facebook The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFacebook($facebook = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($facebook)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_FACEBOOK, $facebook, $comparison);

        return $this;
    }

    /**
     * Filter the query on the people_twitter column
     *
     * Example usage:
     * <code>
     * $query->filterByTwitter('fooValue');   // WHERE people_twitter = 'fooValue'
     * $query->filterByTwitter('%fooValue%', Criteria::LIKE); // WHERE people_twitter LIKE '%fooValue%'
     * $query->filterByTwitter(['foo', 'bar']); // WHERE people_twitter IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $twitter The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTwitter($twitter = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($twitter)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_TWITTER, $twitter, $comparison);

        return $this;
    }

    /**
     * Filter the query on the people_hits column
     *
     * Example usage:
     * <code>
     * $query->filterByHits(1234); // WHERE people_hits = 1234
     * $query->filterByHits(array(12, 34)); // WHERE people_hits IN (12, 34)
     * $query->filterByHits(array('min' => 12)); // WHERE people_hits > 12
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
                $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_HITS, $hits['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($hits['max'])) {
                $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_HITS, $hits['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_HITS, $hits, $comparison);

        return $this;
    }

    /**
     * Filter the query on the people_date column
     *
     * Example usage:
     * <code>
     * $query->filterByDate('2011-03-14'); // WHERE people_date = '2011-03-14'
     * $query->filterByDate('now'); // WHERE people_date = '2011-03-14'
     * $query->filterByDate(array('max' => 'yesterday')); // WHERE people_date > '2011-03-13'
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
                $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_DATE, $date, $comparison);

        return $this;
    }

    /**
     * Filter the query on the people_insert column
     *
     * Example usage:
     * <code>
     * $query->filterByInsert('2011-03-14'); // WHERE people_insert = '2011-03-14'
     * $query->filterByInsert('now'); // WHERE people_insert = '2011-03-14'
     * $query->filterByInsert(array('max' => 'yesterday')); // WHERE people_insert > '2011-03-13'
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
                $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_INSERT, $insert['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($insert['max'])) {
                $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_INSERT, $insert['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_INSERT, $insert, $comparison);

        return $this;
    }

    /**
     * Filter the query on the people_update column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdate('2011-03-14'); // WHERE people_update = '2011-03-14'
     * $query->filterByUpdate('now'); // WHERE people_update = '2011-03-14'
     * $query->filterByUpdate(array('max' => 'yesterday')); // WHERE people_update > '2011-03-13'
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
                $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_UPDATE, $update['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($update['max'])) {
                $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_UPDATE, $update['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_UPDATE, $update, $comparison);

        return $this;
    }

    /**
     * Filter the query on the people_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE people_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE people_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE people_created > '2011-03-13'
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
                $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_CREATED, $createdAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the people_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE people_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE people_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE people_updated > '2011-03-13'
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
                $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_UPDATED, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \Model\Role object
     *
     * @param \Model\Role|ObjectCollection $role the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByRole($role, ?string $comparison = null)
    {
        if ($role instanceof \Model\Role) {
            $this
                ->addUsingAlias(PeopleTableMap::COL_PEOPLE_ID, $role->getPeopleId(), $comparison);

            return $this;
        } elseif ($role instanceof ObjectCollection) {
            $this
                ->useRoleQuery()
                ->filterByPrimaryKeys($role->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByRole() only accepts arguments of type \Model\Role or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Role relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinRole(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Role');

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
            $this->addJoinObject($join, 'Role');
        }

        return $this;
    }

    /**
     * Use the Role relation Role object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\RoleQuery A secondary query class using the current class as primary query
     */
    public function useRoleQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinRole($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Role', '\Model\RoleQuery');
    }

    /**
     * Use the Role relation Role object
     *
     * @param callable(\Model\RoleQuery):\Model\RoleQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withRoleQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useRoleQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Role table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\RoleQuery The inner query object of the EXISTS statement
     */
    public function useRoleExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\RoleQuery */
        $q = $this->useExistsQuery('Role', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Role table for a NOT EXISTS query.
     *
     * @see useRoleExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\RoleQuery The inner query object of the NOT EXISTS statement
     */
    public function useRoleNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\RoleQuery */
        $q = $this->useExistsQuery('Role', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Role table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\RoleQuery The inner query object of the IN statement
     */
    public function useInRoleQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\RoleQuery */
        $q = $this->useInQuery('Role', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Role table for a NOT IN query.
     *
     * @see useRoleInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\RoleQuery The inner query object of the NOT IN statement
     */
    public function useNotInRoleQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\RoleQuery */
        $q = $this->useInQuery('Role', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Exclude object from result
     *
     * @param ChildPeople $people Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($people = null)
    {
        if ($people) {
            $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_ID, $people->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the people table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PeopleTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PeopleTableMap::clearInstancePool();
            PeopleTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PeopleTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PeopleTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PeopleTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PeopleTableMap::clearRelatedInstancePool();

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
        $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(PeopleTableMap::COL_PEOPLE_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(PeopleTableMap::COL_PEOPLE_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(PeopleTableMap::COL_PEOPLE_CREATED);

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
        $this->addUsingAlias(PeopleTableMap::COL_PEOPLE_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(PeopleTableMap::COL_PEOPLE_CREATED);

        return $this;
    }

}
