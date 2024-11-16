<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Bookshop as ChildBookshop;
use Model\BookshopQuery as ChildBookshopQuery;
use Model\Map\BookshopTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `bookshops` table.
 *
 * @method     ChildBookshopQuery orderById($order = Criteria::ASC) Order by the bookshop_id column
 * @method     ChildBookshopQuery orderByName($order = Criteria::ASC) Order by the bookshop_name column
 * @method     ChildBookshopQuery orderByNameAlphabetic($order = Criteria::ASC) Order by the bookshop_name_alphabetic column
 * @method     ChildBookshopQuery orderByUrl($order = Criteria::ASC) Order by the bookshop_url column
 * @method     ChildBookshopQuery orderByRepresentative($order = Criteria::ASC) Order by the bookshop_representative column
 * @method     ChildBookshopQuery orderByAddress($order = Criteria::ASC) Order by the bookshop_address column
 * @method     ChildBookshopQuery orderByPostalCode($order = Criteria::ASC) Order by the bookshop_postal_code column
 * @method     ChildBookshopQuery orderByCity($order = Criteria::ASC) Order by the bookshop_city column
 * @method     ChildBookshopQuery orderByCountry($order = Criteria::ASC) Order by the bookshop_country column
 * @method     ChildBookshopQuery orderByPhone($order = Criteria::ASC) Order by the bookshop_phone column
 * @method     ChildBookshopQuery orderByFax($order = Criteria::ASC) Order by the bookshop_fax column
 * @method     ChildBookshopQuery orderByWebsite($order = Criteria::ASC) Order by the bookshop_website column
 * @method     ChildBookshopQuery orderByEmail($order = Criteria::ASC) Order by the bookshop_email column
 * @method     ChildBookshopQuery orderByFacebook($order = Criteria::ASC) Order by the bookshop_facebook column
 * @method     ChildBookshopQuery orderByTwitter($order = Criteria::ASC) Order by the bookshop_twitter column
 * @method     ChildBookshopQuery orderByLegalForm($order = Criteria::ASC) Order by the bookshop_legal_form column
 * @method     ChildBookshopQuery orderByCreationYear($order = Criteria::ASC) Order by the bookshop_creation_year column
 * @method     ChildBookshopQuery orderBySpecialities($order = Criteria::ASC) Order by the bookshop_specialities column
 * @method     ChildBookshopQuery orderByMembership($order = Criteria::ASC) Order by the bookshop_membership column
 * @method     ChildBookshopQuery orderByMotto($order = Criteria::ASC) Order by the bookshop_motto column
 * @method     ChildBookshopQuery orderByDesc($order = Criteria::ASC) Order by the bookshop_desc column
 * @method     ChildBookshopQuery orderByCreatedAt($order = Criteria::ASC) Order by the bookshop_created column
 * @method     ChildBookshopQuery orderByUpdatedAt($order = Criteria::ASC) Order by the bookshop_updated column
 *
 * @method     ChildBookshopQuery groupById() Group by the bookshop_id column
 * @method     ChildBookshopQuery groupByName() Group by the bookshop_name column
 * @method     ChildBookshopQuery groupByNameAlphabetic() Group by the bookshop_name_alphabetic column
 * @method     ChildBookshopQuery groupByUrl() Group by the bookshop_url column
 * @method     ChildBookshopQuery groupByRepresentative() Group by the bookshop_representative column
 * @method     ChildBookshopQuery groupByAddress() Group by the bookshop_address column
 * @method     ChildBookshopQuery groupByPostalCode() Group by the bookshop_postal_code column
 * @method     ChildBookshopQuery groupByCity() Group by the bookshop_city column
 * @method     ChildBookshopQuery groupByCountry() Group by the bookshop_country column
 * @method     ChildBookshopQuery groupByPhone() Group by the bookshop_phone column
 * @method     ChildBookshopQuery groupByFax() Group by the bookshop_fax column
 * @method     ChildBookshopQuery groupByWebsite() Group by the bookshop_website column
 * @method     ChildBookshopQuery groupByEmail() Group by the bookshop_email column
 * @method     ChildBookshopQuery groupByFacebook() Group by the bookshop_facebook column
 * @method     ChildBookshopQuery groupByTwitter() Group by the bookshop_twitter column
 * @method     ChildBookshopQuery groupByLegalForm() Group by the bookshop_legal_form column
 * @method     ChildBookshopQuery groupByCreationYear() Group by the bookshop_creation_year column
 * @method     ChildBookshopQuery groupBySpecialities() Group by the bookshop_specialities column
 * @method     ChildBookshopQuery groupByMembership() Group by the bookshop_membership column
 * @method     ChildBookshopQuery groupByMotto() Group by the bookshop_motto column
 * @method     ChildBookshopQuery groupByDesc() Group by the bookshop_desc column
 * @method     ChildBookshopQuery groupByCreatedAt() Group by the bookshop_created column
 * @method     ChildBookshopQuery groupByUpdatedAt() Group by the bookshop_updated column
 *
 * @method     ChildBookshopQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildBookshopQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildBookshopQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildBookshopQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildBookshopQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildBookshopQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildBookshop|null findOne(?ConnectionInterface $con = null) Return the first ChildBookshop matching the query
 * @method     ChildBookshop findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildBookshop matching the query, or a new ChildBookshop object populated from the query conditions when no match is found
 *
 * @method     ChildBookshop|null findOneById(int $bookshop_id) Return the first ChildBookshop filtered by the bookshop_id column
 * @method     ChildBookshop|null findOneByName(string $bookshop_name) Return the first ChildBookshop filtered by the bookshop_name column
 * @method     ChildBookshop|null findOneByNameAlphabetic(string $bookshop_name_alphabetic) Return the first ChildBookshop filtered by the bookshop_name_alphabetic column
 * @method     ChildBookshop|null findOneByUrl(string $bookshop_url) Return the first ChildBookshop filtered by the bookshop_url column
 * @method     ChildBookshop|null findOneByRepresentative(string $bookshop_representative) Return the first ChildBookshop filtered by the bookshop_representative column
 * @method     ChildBookshop|null findOneByAddress(string $bookshop_address) Return the first ChildBookshop filtered by the bookshop_address column
 * @method     ChildBookshop|null findOneByPostalCode(string $bookshop_postal_code) Return the first ChildBookshop filtered by the bookshop_postal_code column
 * @method     ChildBookshop|null findOneByCity(string $bookshop_city) Return the first ChildBookshop filtered by the bookshop_city column
 * @method     ChildBookshop|null findOneByCountry(string $bookshop_country) Return the first ChildBookshop filtered by the bookshop_country column
 * @method     ChildBookshop|null findOneByPhone(string $bookshop_phone) Return the first ChildBookshop filtered by the bookshop_phone column
 * @method     ChildBookshop|null findOneByFax(string $bookshop_fax) Return the first ChildBookshop filtered by the bookshop_fax column
 * @method     ChildBookshop|null findOneByWebsite(string $bookshop_website) Return the first ChildBookshop filtered by the bookshop_website column
 * @method     ChildBookshop|null findOneByEmail(string $bookshop_email) Return the first ChildBookshop filtered by the bookshop_email column
 * @method     ChildBookshop|null findOneByFacebook(string $bookshop_facebook) Return the first ChildBookshop filtered by the bookshop_facebook column
 * @method     ChildBookshop|null findOneByTwitter(string $bookshop_twitter) Return the first ChildBookshop filtered by the bookshop_twitter column
 * @method     ChildBookshop|null findOneByLegalForm(string $bookshop_legal_form) Return the first ChildBookshop filtered by the bookshop_legal_form column
 * @method     ChildBookshop|null findOneByCreationYear(string $bookshop_creation_year) Return the first ChildBookshop filtered by the bookshop_creation_year column
 * @method     ChildBookshop|null findOneBySpecialities(string $bookshop_specialities) Return the first ChildBookshop filtered by the bookshop_specialities column
 * @method     ChildBookshop|null findOneByMembership(string $bookshop_membership) Return the first ChildBookshop filtered by the bookshop_membership column
 * @method     ChildBookshop|null findOneByMotto(string $bookshop_motto) Return the first ChildBookshop filtered by the bookshop_motto column
 * @method     ChildBookshop|null findOneByDesc(string $bookshop_desc) Return the first ChildBookshop filtered by the bookshop_desc column
 * @method     ChildBookshop|null findOneByCreatedAt(string $bookshop_created) Return the first ChildBookshop filtered by the bookshop_created column
 * @method     ChildBookshop|null findOneByUpdatedAt(string $bookshop_updated) Return the first ChildBookshop filtered by the bookshop_updated column
 *
 * @method     ChildBookshop requirePk($key, ?ConnectionInterface $con = null) Return the ChildBookshop by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookshop requireOne(?ConnectionInterface $con = null) Return the first ChildBookshop matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildBookshop requireOneById(int $bookshop_id) Return the first ChildBookshop filtered by the bookshop_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookshop requireOneByName(string $bookshop_name) Return the first ChildBookshop filtered by the bookshop_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookshop requireOneByNameAlphabetic(string $bookshop_name_alphabetic) Return the first ChildBookshop filtered by the bookshop_name_alphabetic column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookshop requireOneByUrl(string $bookshop_url) Return the first ChildBookshop filtered by the bookshop_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookshop requireOneByRepresentative(string $bookshop_representative) Return the first ChildBookshop filtered by the bookshop_representative column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookshop requireOneByAddress(string $bookshop_address) Return the first ChildBookshop filtered by the bookshop_address column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookshop requireOneByPostalCode(string $bookshop_postal_code) Return the first ChildBookshop filtered by the bookshop_postal_code column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookshop requireOneByCity(string $bookshop_city) Return the first ChildBookshop filtered by the bookshop_city column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookshop requireOneByCountry(string $bookshop_country) Return the first ChildBookshop filtered by the bookshop_country column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookshop requireOneByPhone(string $bookshop_phone) Return the first ChildBookshop filtered by the bookshop_phone column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookshop requireOneByFax(string $bookshop_fax) Return the first ChildBookshop filtered by the bookshop_fax column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookshop requireOneByWebsite(string $bookshop_website) Return the first ChildBookshop filtered by the bookshop_website column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookshop requireOneByEmail(string $bookshop_email) Return the first ChildBookshop filtered by the bookshop_email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookshop requireOneByFacebook(string $bookshop_facebook) Return the first ChildBookshop filtered by the bookshop_facebook column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookshop requireOneByTwitter(string $bookshop_twitter) Return the first ChildBookshop filtered by the bookshop_twitter column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookshop requireOneByLegalForm(string $bookshop_legal_form) Return the first ChildBookshop filtered by the bookshop_legal_form column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookshop requireOneByCreationYear(string $bookshop_creation_year) Return the first ChildBookshop filtered by the bookshop_creation_year column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookshop requireOneBySpecialities(string $bookshop_specialities) Return the first ChildBookshop filtered by the bookshop_specialities column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookshop requireOneByMembership(string $bookshop_membership) Return the first ChildBookshop filtered by the bookshop_membership column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookshop requireOneByMotto(string $bookshop_motto) Return the first ChildBookshop filtered by the bookshop_motto column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookshop requireOneByDesc(string $bookshop_desc) Return the first ChildBookshop filtered by the bookshop_desc column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookshop requireOneByCreatedAt(string $bookshop_created) Return the first ChildBookshop filtered by the bookshop_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookshop requireOneByUpdatedAt(string $bookshop_updated) Return the first ChildBookshop filtered by the bookshop_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildBookshop[]|Collection find(?ConnectionInterface $con = null) Return ChildBookshop objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildBookshop> find(?ConnectionInterface $con = null) Return ChildBookshop objects based on current ModelCriteria
 *
 * @method     ChildBookshop[]|Collection findById(int|array<int> $bookshop_id) Return ChildBookshop objects filtered by the bookshop_id column
 * @psalm-method Collection&\Traversable<ChildBookshop> findById(int|array<int> $bookshop_id) Return ChildBookshop objects filtered by the bookshop_id column
 * @method     ChildBookshop[]|Collection findByName(string|array<string> $bookshop_name) Return ChildBookshop objects filtered by the bookshop_name column
 * @psalm-method Collection&\Traversable<ChildBookshop> findByName(string|array<string> $bookshop_name) Return ChildBookshop objects filtered by the bookshop_name column
 * @method     ChildBookshop[]|Collection findByNameAlphabetic(string|array<string> $bookshop_name_alphabetic) Return ChildBookshop objects filtered by the bookshop_name_alphabetic column
 * @psalm-method Collection&\Traversable<ChildBookshop> findByNameAlphabetic(string|array<string> $bookshop_name_alphabetic) Return ChildBookshop objects filtered by the bookshop_name_alphabetic column
 * @method     ChildBookshop[]|Collection findByUrl(string|array<string> $bookshop_url) Return ChildBookshop objects filtered by the bookshop_url column
 * @psalm-method Collection&\Traversable<ChildBookshop> findByUrl(string|array<string> $bookshop_url) Return ChildBookshop objects filtered by the bookshop_url column
 * @method     ChildBookshop[]|Collection findByRepresentative(string|array<string> $bookshop_representative) Return ChildBookshop objects filtered by the bookshop_representative column
 * @psalm-method Collection&\Traversable<ChildBookshop> findByRepresentative(string|array<string> $bookshop_representative) Return ChildBookshop objects filtered by the bookshop_representative column
 * @method     ChildBookshop[]|Collection findByAddress(string|array<string> $bookshop_address) Return ChildBookshop objects filtered by the bookshop_address column
 * @psalm-method Collection&\Traversable<ChildBookshop> findByAddress(string|array<string> $bookshop_address) Return ChildBookshop objects filtered by the bookshop_address column
 * @method     ChildBookshop[]|Collection findByPostalCode(string|array<string> $bookshop_postal_code) Return ChildBookshop objects filtered by the bookshop_postal_code column
 * @psalm-method Collection&\Traversable<ChildBookshop> findByPostalCode(string|array<string> $bookshop_postal_code) Return ChildBookshop objects filtered by the bookshop_postal_code column
 * @method     ChildBookshop[]|Collection findByCity(string|array<string> $bookshop_city) Return ChildBookshop objects filtered by the bookshop_city column
 * @psalm-method Collection&\Traversable<ChildBookshop> findByCity(string|array<string> $bookshop_city) Return ChildBookshop objects filtered by the bookshop_city column
 * @method     ChildBookshop[]|Collection findByCountry(string|array<string> $bookshop_country) Return ChildBookshop objects filtered by the bookshop_country column
 * @psalm-method Collection&\Traversable<ChildBookshop> findByCountry(string|array<string> $bookshop_country) Return ChildBookshop objects filtered by the bookshop_country column
 * @method     ChildBookshop[]|Collection findByPhone(string|array<string> $bookshop_phone) Return ChildBookshop objects filtered by the bookshop_phone column
 * @psalm-method Collection&\Traversable<ChildBookshop> findByPhone(string|array<string> $bookshop_phone) Return ChildBookshop objects filtered by the bookshop_phone column
 * @method     ChildBookshop[]|Collection findByFax(string|array<string> $bookshop_fax) Return ChildBookshop objects filtered by the bookshop_fax column
 * @psalm-method Collection&\Traversable<ChildBookshop> findByFax(string|array<string> $bookshop_fax) Return ChildBookshop objects filtered by the bookshop_fax column
 * @method     ChildBookshop[]|Collection findByWebsite(string|array<string> $bookshop_website) Return ChildBookshop objects filtered by the bookshop_website column
 * @psalm-method Collection&\Traversable<ChildBookshop> findByWebsite(string|array<string> $bookshop_website) Return ChildBookshop objects filtered by the bookshop_website column
 * @method     ChildBookshop[]|Collection findByEmail(string|array<string> $bookshop_email) Return ChildBookshop objects filtered by the bookshop_email column
 * @psalm-method Collection&\Traversable<ChildBookshop> findByEmail(string|array<string> $bookshop_email) Return ChildBookshop objects filtered by the bookshop_email column
 * @method     ChildBookshop[]|Collection findByFacebook(string|array<string> $bookshop_facebook) Return ChildBookshop objects filtered by the bookshop_facebook column
 * @psalm-method Collection&\Traversable<ChildBookshop> findByFacebook(string|array<string> $bookshop_facebook) Return ChildBookshop objects filtered by the bookshop_facebook column
 * @method     ChildBookshop[]|Collection findByTwitter(string|array<string> $bookshop_twitter) Return ChildBookshop objects filtered by the bookshop_twitter column
 * @psalm-method Collection&\Traversable<ChildBookshop> findByTwitter(string|array<string> $bookshop_twitter) Return ChildBookshop objects filtered by the bookshop_twitter column
 * @method     ChildBookshop[]|Collection findByLegalForm(string|array<string> $bookshop_legal_form) Return ChildBookshop objects filtered by the bookshop_legal_form column
 * @psalm-method Collection&\Traversable<ChildBookshop> findByLegalForm(string|array<string> $bookshop_legal_form) Return ChildBookshop objects filtered by the bookshop_legal_form column
 * @method     ChildBookshop[]|Collection findByCreationYear(string|array<string> $bookshop_creation_year) Return ChildBookshop objects filtered by the bookshop_creation_year column
 * @psalm-method Collection&\Traversable<ChildBookshop> findByCreationYear(string|array<string> $bookshop_creation_year) Return ChildBookshop objects filtered by the bookshop_creation_year column
 * @method     ChildBookshop[]|Collection findBySpecialities(string|array<string> $bookshop_specialities) Return ChildBookshop objects filtered by the bookshop_specialities column
 * @psalm-method Collection&\Traversable<ChildBookshop> findBySpecialities(string|array<string> $bookshop_specialities) Return ChildBookshop objects filtered by the bookshop_specialities column
 * @method     ChildBookshop[]|Collection findByMembership(string|array<string> $bookshop_membership) Return ChildBookshop objects filtered by the bookshop_membership column
 * @psalm-method Collection&\Traversable<ChildBookshop> findByMembership(string|array<string> $bookshop_membership) Return ChildBookshop objects filtered by the bookshop_membership column
 * @method     ChildBookshop[]|Collection findByMotto(string|array<string> $bookshop_motto) Return ChildBookshop objects filtered by the bookshop_motto column
 * @psalm-method Collection&\Traversable<ChildBookshop> findByMotto(string|array<string> $bookshop_motto) Return ChildBookshop objects filtered by the bookshop_motto column
 * @method     ChildBookshop[]|Collection findByDesc(string|array<string> $bookshop_desc) Return ChildBookshop objects filtered by the bookshop_desc column
 * @psalm-method Collection&\Traversable<ChildBookshop> findByDesc(string|array<string> $bookshop_desc) Return ChildBookshop objects filtered by the bookshop_desc column
 * @method     ChildBookshop[]|Collection findByCreatedAt(string|array<string> $bookshop_created) Return ChildBookshop objects filtered by the bookshop_created column
 * @psalm-method Collection&\Traversable<ChildBookshop> findByCreatedAt(string|array<string> $bookshop_created) Return ChildBookshop objects filtered by the bookshop_created column
 * @method     ChildBookshop[]|Collection findByUpdatedAt(string|array<string> $bookshop_updated) Return ChildBookshop objects filtered by the bookshop_updated column
 * @psalm-method Collection&\Traversable<ChildBookshop> findByUpdatedAt(string|array<string> $bookshop_updated) Return ChildBookshop objects filtered by the bookshop_updated column
 *
 * @method     ChildBookshop[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildBookshop> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class BookshopQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\BookshopQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Bookshop', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildBookshopQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildBookshopQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildBookshopQuery) {
            return $criteria;
        }
        $query = new ChildBookshopQuery();
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
     * @return ChildBookshop|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(BookshopTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = BookshopTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildBookshop A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT bookshop_id, bookshop_name, bookshop_name_alphabetic, bookshop_url, bookshop_representative, bookshop_address, bookshop_postal_code, bookshop_city, bookshop_country, bookshop_phone, bookshop_fax, bookshop_website, bookshop_email, bookshop_facebook, bookshop_twitter, bookshop_legal_form, bookshop_creation_year, bookshop_specialities, bookshop_membership, bookshop_motto, bookshop_desc, bookshop_created, bookshop_updated FROM bookshops WHERE bookshop_id = :p0';
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
            /** @var ChildBookshop $obj */
            $obj = new ChildBookshop();
            $obj->hydrate($row);
            BookshopTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildBookshop|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the bookshop_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE bookshop_id = 1234
     * $query->filterById(array(12, 34)); // WHERE bookshop_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE bookshop_id > 12
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
                $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the bookshop_name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE bookshop_name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE bookshop_name LIKE '%fooValue%'
     * $query->filterByName(['foo', 'bar']); // WHERE bookshop_name IN ('foo', 'bar')
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

        $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_NAME, $name, $comparison);

        return $this;
    }

    /**
     * Filter the query on the bookshop_name_alphabetic column
     *
     * Example usage:
     * <code>
     * $query->filterByNameAlphabetic('fooValue');   // WHERE bookshop_name_alphabetic = 'fooValue'
     * $query->filterByNameAlphabetic('%fooValue%', Criteria::LIKE); // WHERE bookshop_name_alphabetic LIKE '%fooValue%'
     * $query->filterByNameAlphabetic(['foo', 'bar']); // WHERE bookshop_name_alphabetic IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $nameAlphabetic The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByNameAlphabetic($nameAlphabetic = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nameAlphabetic)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_NAME_ALPHABETIC, $nameAlphabetic, $comparison);

        return $this;
    }

    /**
     * Filter the query on the bookshop_url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE bookshop_url = 'fooValue'
     * $query->filterByUrl('%fooValue%', Criteria::LIKE); // WHERE bookshop_url LIKE '%fooValue%'
     * $query->filterByUrl(['foo', 'bar']); // WHERE bookshop_url IN ('foo', 'bar')
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

        $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_URL, $url, $comparison);

        return $this;
    }

    /**
     * Filter the query on the bookshop_representative column
     *
     * Example usage:
     * <code>
     * $query->filterByRepresentative('fooValue');   // WHERE bookshop_representative = 'fooValue'
     * $query->filterByRepresentative('%fooValue%', Criteria::LIKE); // WHERE bookshop_representative LIKE '%fooValue%'
     * $query->filterByRepresentative(['foo', 'bar']); // WHERE bookshop_representative IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $representative The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByRepresentative($representative = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($representative)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_REPRESENTATIVE, $representative, $comparison);

        return $this;
    }

    /**
     * Filter the query on the bookshop_address column
     *
     * Example usage:
     * <code>
     * $query->filterByAddress('fooValue');   // WHERE bookshop_address = 'fooValue'
     * $query->filterByAddress('%fooValue%', Criteria::LIKE); // WHERE bookshop_address LIKE '%fooValue%'
     * $query->filterByAddress(['foo', 'bar']); // WHERE bookshop_address IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $address The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAddress($address = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($address)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_ADDRESS, $address, $comparison);

        return $this;
    }

    /**
     * Filter the query on the bookshop_postal_code column
     *
     * Example usage:
     * <code>
     * $query->filterByPostalCode('fooValue');   // WHERE bookshop_postal_code = 'fooValue'
     * $query->filterByPostalCode('%fooValue%', Criteria::LIKE); // WHERE bookshop_postal_code LIKE '%fooValue%'
     * $query->filterByPostalCode(['foo', 'bar']); // WHERE bookshop_postal_code IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $postalCode The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPostalCode($postalCode = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($postalCode)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_POSTAL_CODE, $postalCode, $comparison);

        return $this;
    }

    /**
     * Filter the query on the bookshop_city column
     *
     * Example usage:
     * <code>
     * $query->filterByCity('fooValue');   // WHERE bookshop_city = 'fooValue'
     * $query->filterByCity('%fooValue%', Criteria::LIKE); // WHERE bookshop_city LIKE '%fooValue%'
     * $query->filterByCity(['foo', 'bar']); // WHERE bookshop_city IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $city The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCity($city = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($city)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_CITY, $city, $comparison);

        return $this;
    }

    /**
     * Filter the query on the bookshop_country column
     *
     * Example usage:
     * <code>
     * $query->filterByCountry('fooValue');   // WHERE bookshop_country = 'fooValue'
     * $query->filterByCountry('%fooValue%', Criteria::LIKE); // WHERE bookshop_country LIKE '%fooValue%'
     * $query->filterByCountry(['foo', 'bar']); // WHERE bookshop_country IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $country The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCountry($country = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($country)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_COUNTRY, $country, $comparison);

        return $this;
    }

    /**
     * Filter the query on the bookshop_phone column
     *
     * Example usage:
     * <code>
     * $query->filterByPhone('fooValue');   // WHERE bookshop_phone = 'fooValue'
     * $query->filterByPhone('%fooValue%', Criteria::LIKE); // WHERE bookshop_phone LIKE '%fooValue%'
     * $query->filterByPhone(['foo', 'bar']); // WHERE bookshop_phone IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $phone The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPhone($phone = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($phone)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_PHONE, $phone, $comparison);

        return $this;
    }

    /**
     * Filter the query on the bookshop_fax column
     *
     * Example usage:
     * <code>
     * $query->filterByFax('fooValue');   // WHERE bookshop_fax = 'fooValue'
     * $query->filterByFax('%fooValue%', Criteria::LIKE); // WHERE bookshop_fax LIKE '%fooValue%'
     * $query->filterByFax(['foo', 'bar']); // WHERE bookshop_fax IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $fax The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFax($fax = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($fax)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_FAX, $fax, $comparison);

        return $this;
    }

    /**
     * Filter the query on the bookshop_website column
     *
     * Example usage:
     * <code>
     * $query->filterByWebsite('fooValue');   // WHERE bookshop_website = 'fooValue'
     * $query->filterByWebsite('%fooValue%', Criteria::LIKE); // WHERE bookshop_website LIKE '%fooValue%'
     * $query->filterByWebsite(['foo', 'bar']); // WHERE bookshop_website IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $website The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByWebsite($website = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($website)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_WEBSITE, $website, $comparison);

        return $this;
    }

    /**
     * Filter the query on the bookshop_email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE bookshop_email = 'fooValue'
     * $query->filterByEmail('%fooValue%', Criteria::LIKE); // WHERE bookshop_email LIKE '%fooValue%'
     * $query->filterByEmail(['foo', 'bar']); // WHERE bookshop_email IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $email The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEmail($email = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_EMAIL, $email, $comparison);

        return $this;
    }

    /**
     * Filter the query on the bookshop_facebook column
     *
     * Example usage:
     * <code>
     * $query->filterByFacebook('fooValue');   // WHERE bookshop_facebook = 'fooValue'
     * $query->filterByFacebook('%fooValue%', Criteria::LIKE); // WHERE bookshop_facebook LIKE '%fooValue%'
     * $query->filterByFacebook(['foo', 'bar']); // WHERE bookshop_facebook IN ('foo', 'bar')
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

        $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_FACEBOOK, $facebook, $comparison);

        return $this;
    }

    /**
     * Filter the query on the bookshop_twitter column
     *
     * Example usage:
     * <code>
     * $query->filterByTwitter('fooValue');   // WHERE bookshop_twitter = 'fooValue'
     * $query->filterByTwitter('%fooValue%', Criteria::LIKE); // WHERE bookshop_twitter LIKE '%fooValue%'
     * $query->filterByTwitter(['foo', 'bar']); // WHERE bookshop_twitter IN ('foo', 'bar')
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

        $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_TWITTER, $twitter, $comparison);

        return $this;
    }

    /**
     * Filter the query on the bookshop_legal_form column
     *
     * Example usage:
     * <code>
     * $query->filterByLegalForm('fooValue');   // WHERE bookshop_legal_form = 'fooValue'
     * $query->filterByLegalForm('%fooValue%', Criteria::LIKE); // WHERE bookshop_legal_form LIKE '%fooValue%'
     * $query->filterByLegalForm(['foo', 'bar']); // WHERE bookshop_legal_form IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $legalForm The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByLegalForm($legalForm = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($legalForm)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_LEGAL_FORM, $legalForm, $comparison);

        return $this;
    }

    /**
     * Filter the query on the bookshop_creation_year column
     *
     * Example usage:
     * <code>
     * $query->filterByCreationYear('fooValue');   // WHERE bookshop_creation_year = 'fooValue'
     * $query->filterByCreationYear('%fooValue%', Criteria::LIKE); // WHERE bookshop_creation_year LIKE '%fooValue%'
     * $query->filterByCreationYear(['foo', 'bar']); // WHERE bookshop_creation_year IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $creationYear The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCreationYear($creationYear = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($creationYear)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_CREATION_YEAR, $creationYear, $comparison);

        return $this;
    }

    /**
     * Filter the query on the bookshop_specialities column
     *
     * Example usage:
     * <code>
     * $query->filterBySpecialities('fooValue');   // WHERE bookshop_specialities = 'fooValue'
     * $query->filterBySpecialities('%fooValue%', Criteria::LIKE); // WHERE bookshop_specialities LIKE '%fooValue%'
     * $query->filterBySpecialities(['foo', 'bar']); // WHERE bookshop_specialities IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $specialities The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySpecialities($specialities = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($specialities)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_SPECIALITIES, $specialities, $comparison);

        return $this;
    }

    /**
     * Filter the query on the bookshop_membership column
     *
     * Example usage:
     * <code>
     * $query->filterByMembership('fooValue');   // WHERE bookshop_membership = 'fooValue'
     * $query->filterByMembership('%fooValue%', Criteria::LIKE); // WHERE bookshop_membership LIKE '%fooValue%'
     * $query->filterByMembership(['foo', 'bar']); // WHERE bookshop_membership IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $membership The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByMembership($membership = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($membership)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_MEMBERSHIP, $membership, $comparison);

        return $this;
    }

    /**
     * Filter the query on the bookshop_motto column
     *
     * Example usage:
     * <code>
     * $query->filterByMotto('fooValue');   // WHERE bookshop_motto = 'fooValue'
     * $query->filterByMotto('%fooValue%', Criteria::LIKE); // WHERE bookshop_motto LIKE '%fooValue%'
     * $query->filterByMotto(['foo', 'bar']); // WHERE bookshop_motto IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $motto The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByMotto($motto = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($motto)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_MOTTO, $motto, $comparison);

        return $this;
    }

    /**
     * Filter the query on the bookshop_desc column
     *
     * Example usage:
     * <code>
     * $query->filterByDesc('fooValue');   // WHERE bookshop_desc = 'fooValue'
     * $query->filterByDesc('%fooValue%', Criteria::LIKE); // WHERE bookshop_desc LIKE '%fooValue%'
     * $query->filterByDesc(['foo', 'bar']); // WHERE bookshop_desc IN ('foo', 'bar')
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

        $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_DESC, $desc, $comparison);

        return $this;
    }

    /**
     * Filter the query on the bookshop_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE bookshop_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE bookshop_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE bookshop_created > '2011-03-13'
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
                $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_CREATED, $createdAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the bookshop_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE bookshop_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE bookshop_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE bookshop_updated > '2011-03-13'
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
                $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_UPDATED, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Exclude object from result
     *
     * @param ChildBookshop $bookshop Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($bookshop = null)
    {
        if ($bookshop) {
            $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_ID, $bookshop->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the bookshops table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(BookshopTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            BookshopTableMap::clearInstancePool();
            BookshopTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(BookshopTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(BookshopTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            BookshopTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            BookshopTableMap::clearRelatedInstancePool();

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
        $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(BookshopTableMap::COL_BOOKSHOP_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(BookshopTableMap::COL_BOOKSHOP_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(BookshopTableMap::COL_BOOKSHOP_CREATED);

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
        $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(BookshopTableMap::COL_BOOKSHOP_CREATED);

        return $this;
    }

}
