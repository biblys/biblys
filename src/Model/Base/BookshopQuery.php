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
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'bookshops' table.
 *
 *
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
 * @method     ChildBookshop|null findOne(ConnectionInterface $con = null) Return the first ChildBookshop matching the query
 * @method     ChildBookshop findOneOrCreate(ConnectionInterface $con = null) Return the first ChildBookshop matching the query, or a new ChildBookshop object populated from the query conditions when no match is found
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
 * @method     ChildBookshop|null findOneByUpdatedAt(string $bookshop_updated) Return the first ChildBookshop filtered by the bookshop_updated column *

 * @method     ChildBookshop requirePk($key, ConnectionInterface $con = null) Return the ChildBookshop by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBookshop requireOne(ConnectionInterface $con = null) Return the first ChildBookshop matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
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
 * @method     ChildBookshop[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildBookshop objects based on current ModelCriteria
 * @psalm-method ObjectCollection&\Traversable<ChildBookshop> find(ConnectionInterface $con = null) Return ChildBookshop objects based on current ModelCriteria
 * @method     ChildBookshop[]|ObjectCollection findById(int $bookshop_id) Return ChildBookshop objects filtered by the bookshop_id column
 * @psalm-method ObjectCollection&\Traversable<ChildBookshop> findById(int $bookshop_id) Return ChildBookshop objects filtered by the bookshop_id column
 * @method     ChildBookshop[]|ObjectCollection findByName(string $bookshop_name) Return ChildBookshop objects filtered by the bookshop_name column
 * @psalm-method ObjectCollection&\Traversable<ChildBookshop> findByName(string $bookshop_name) Return ChildBookshop objects filtered by the bookshop_name column
 * @method     ChildBookshop[]|ObjectCollection findByNameAlphabetic(string $bookshop_name_alphabetic) Return ChildBookshop objects filtered by the bookshop_name_alphabetic column
 * @psalm-method ObjectCollection&\Traversable<ChildBookshop> findByNameAlphabetic(string $bookshop_name_alphabetic) Return ChildBookshop objects filtered by the bookshop_name_alphabetic column
 * @method     ChildBookshop[]|ObjectCollection findByUrl(string $bookshop_url) Return ChildBookshop objects filtered by the bookshop_url column
 * @psalm-method ObjectCollection&\Traversable<ChildBookshop> findByUrl(string $bookshop_url) Return ChildBookshop objects filtered by the bookshop_url column
 * @method     ChildBookshop[]|ObjectCollection findByRepresentative(string $bookshop_representative) Return ChildBookshop objects filtered by the bookshop_representative column
 * @psalm-method ObjectCollection&\Traversable<ChildBookshop> findByRepresentative(string $bookshop_representative) Return ChildBookshop objects filtered by the bookshop_representative column
 * @method     ChildBookshop[]|ObjectCollection findByAddress(string $bookshop_address) Return ChildBookshop objects filtered by the bookshop_address column
 * @psalm-method ObjectCollection&\Traversable<ChildBookshop> findByAddress(string $bookshop_address) Return ChildBookshop objects filtered by the bookshop_address column
 * @method     ChildBookshop[]|ObjectCollection findByPostalCode(string $bookshop_postal_code) Return ChildBookshop objects filtered by the bookshop_postal_code column
 * @psalm-method ObjectCollection&\Traversable<ChildBookshop> findByPostalCode(string $bookshop_postal_code) Return ChildBookshop objects filtered by the bookshop_postal_code column
 * @method     ChildBookshop[]|ObjectCollection findByCity(string $bookshop_city) Return ChildBookshop objects filtered by the bookshop_city column
 * @psalm-method ObjectCollection&\Traversable<ChildBookshop> findByCity(string $bookshop_city) Return ChildBookshop objects filtered by the bookshop_city column
 * @method     ChildBookshop[]|ObjectCollection findByCountry(string $bookshop_country) Return ChildBookshop objects filtered by the bookshop_country column
 * @psalm-method ObjectCollection&\Traversable<ChildBookshop> findByCountry(string $bookshop_country) Return ChildBookshop objects filtered by the bookshop_country column
 * @method     ChildBookshop[]|ObjectCollection findByPhone(string $bookshop_phone) Return ChildBookshop objects filtered by the bookshop_phone column
 * @psalm-method ObjectCollection&\Traversable<ChildBookshop> findByPhone(string $bookshop_phone) Return ChildBookshop objects filtered by the bookshop_phone column
 * @method     ChildBookshop[]|ObjectCollection findByFax(string $bookshop_fax) Return ChildBookshop objects filtered by the bookshop_fax column
 * @psalm-method ObjectCollection&\Traversable<ChildBookshop> findByFax(string $bookshop_fax) Return ChildBookshop objects filtered by the bookshop_fax column
 * @method     ChildBookshop[]|ObjectCollection findByWebsite(string $bookshop_website) Return ChildBookshop objects filtered by the bookshop_website column
 * @psalm-method ObjectCollection&\Traversable<ChildBookshop> findByWebsite(string $bookshop_website) Return ChildBookshop objects filtered by the bookshop_website column
 * @method     ChildBookshop[]|ObjectCollection findByEmail(string $bookshop_email) Return ChildBookshop objects filtered by the bookshop_email column
 * @psalm-method ObjectCollection&\Traversable<ChildBookshop> findByEmail(string $bookshop_email) Return ChildBookshop objects filtered by the bookshop_email column
 * @method     ChildBookshop[]|ObjectCollection findByFacebook(string $bookshop_facebook) Return ChildBookshop objects filtered by the bookshop_facebook column
 * @psalm-method ObjectCollection&\Traversable<ChildBookshop> findByFacebook(string $bookshop_facebook) Return ChildBookshop objects filtered by the bookshop_facebook column
 * @method     ChildBookshop[]|ObjectCollection findByTwitter(string $bookshop_twitter) Return ChildBookshop objects filtered by the bookshop_twitter column
 * @psalm-method ObjectCollection&\Traversable<ChildBookshop> findByTwitter(string $bookshop_twitter) Return ChildBookshop objects filtered by the bookshop_twitter column
 * @method     ChildBookshop[]|ObjectCollection findByLegalForm(string $bookshop_legal_form) Return ChildBookshop objects filtered by the bookshop_legal_form column
 * @psalm-method ObjectCollection&\Traversable<ChildBookshop> findByLegalForm(string $bookshop_legal_form) Return ChildBookshop objects filtered by the bookshop_legal_form column
 * @method     ChildBookshop[]|ObjectCollection findByCreationYear(string $bookshop_creation_year) Return ChildBookshop objects filtered by the bookshop_creation_year column
 * @psalm-method ObjectCollection&\Traversable<ChildBookshop> findByCreationYear(string $bookshop_creation_year) Return ChildBookshop objects filtered by the bookshop_creation_year column
 * @method     ChildBookshop[]|ObjectCollection findBySpecialities(string $bookshop_specialities) Return ChildBookshop objects filtered by the bookshop_specialities column
 * @psalm-method ObjectCollection&\Traversable<ChildBookshop> findBySpecialities(string $bookshop_specialities) Return ChildBookshop objects filtered by the bookshop_specialities column
 * @method     ChildBookshop[]|ObjectCollection findByMembership(string $bookshop_membership) Return ChildBookshop objects filtered by the bookshop_membership column
 * @psalm-method ObjectCollection&\Traversable<ChildBookshop> findByMembership(string $bookshop_membership) Return ChildBookshop objects filtered by the bookshop_membership column
 * @method     ChildBookshop[]|ObjectCollection findByMotto(string $bookshop_motto) Return ChildBookshop objects filtered by the bookshop_motto column
 * @psalm-method ObjectCollection&\Traversable<ChildBookshop> findByMotto(string $bookshop_motto) Return ChildBookshop objects filtered by the bookshop_motto column
 * @method     ChildBookshop[]|ObjectCollection findByDesc(string $bookshop_desc) Return ChildBookshop objects filtered by the bookshop_desc column
 * @psalm-method ObjectCollection&\Traversable<ChildBookshop> findByDesc(string $bookshop_desc) Return ChildBookshop objects filtered by the bookshop_desc column
 * @method     ChildBookshop[]|ObjectCollection findByCreatedAt(string $bookshop_created) Return ChildBookshop objects filtered by the bookshop_created column
 * @psalm-method ObjectCollection&\Traversable<ChildBookshop> findByCreatedAt(string $bookshop_created) Return ChildBookshop objects filtered by the bookshop_created column
 * @method     ChildBookshop[]|ObjectCollection findByUpdatedAt(string $bookshop_updated) Return ChildBookshop objects filtered by the bookshop_updated column
 * @psalm-method ObjectCollection&\Traversable<ChildBookshop> findByUpdatedAt(string $bookshop_updated) Return ChildBookshop objects filtered by the bookshop_updated column
 * @method     ChildBookshop[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildBookshop> paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class BookshopQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\BookshopQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Bookshop', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildBookshopQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildBookshopQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
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
    public function findPk($key, ConnectionInterface $con = null)
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
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
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
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
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
     * @return $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_ID, $keys, Criteria::IN);
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
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
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

        return $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_ID, $id, $comparison);
    }

    /**
     * Filter the query on the bookshop_name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE bookshop_name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE bookshop_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the bookshop_name_alphabetic column
     *
     * Example usage:
     * <code>
     * $query->filterByNameAlphabetic('fooValue');   // WHERE bookshop_name_alphabetic = 'fooValue'
     * $query->filterByNameAlphabetic('%fooValue%', Criteria::LIKE); // WHERE bookshop_name_alphabetic LIKE '%fooValue%'
     * </code>
     *
     * @param     string $nameAlphabetic The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function filterByNameAlphabetic($nameAlphabetic = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nameAlphabetic)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_NAME_ALPHABETIC, $nameAlphabetic, $comparison);
    }

    /**
     * Filter the query on the bookshop_url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE bookshop_url = 'fooValue'
     * $query->filterByUrl('%fooValue%', Criteria::LIKE); // WHERE bookshop_url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $url The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function filterByUrl($url = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($url)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_URL, $url, $comparison);
    }

    /**
     * Filter the query on the bookshop_representative column
     *
     * Example usage:
     * <code>
     * $query->filterByRepresentative('fooValue');   // WHERE bookshop_representative = 'fooValue'
     * $query->filterByRepresentative('%fooValue%', Criteria::LIKE); // WHERE bookshop_representative LIKE '%fooValue%'
     * </code>
     *
     * @param     string $representative The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function filterByRepresentative($representative = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($representative)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_REPRESENTATIVE, $representative, $comparison);
    }

    /**
     * Filter the query on the bookshop_address column
     *
     * Example usage:
     * <code>
     * $query->filterByAddress('fooValue');   // WHERE bookshop_address = 'fooValue'
     * $query->filterByAddress('%fooValue%', Criteria::LIKE); // WHERE bookshop_address LIKE '%fooValue%'
     * </code>
     *
     * @param     string $address The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function filterByAddress($address = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($address)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_ADDRESS, $address, $comparison);
    }

    /**
     * Filter the query on the bookshop_postal_code column
     *
     * Example usage:
     * <code>
     * $query->filterByPostalCode('fooValue');   // WHERE bookshop_postal_code = 'fooValue'
     * $query->filterByPostalCode('%fooValue%', Criteria::LIKE); // WHERE bookshop_postal_code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $postalCode The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function filterByPostalCode($postalCode = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($postalCode)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_POSTAL_CODE, $postalCode, $comparison);
    }

    /**
     * Filter the query on the bookshop_city column
     *
     * Example usage:
     * <code>
     * $query->filterByCity('fooValue');   // WHERE bookshop_city = 'fooValue'
     * $query->filterByCity('%fooValue%', Criteria::LIKE); // WHERE bookshop_city LIKE '%fooValue%'
     * </code>
     *
     * @param     string $city The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function filterByCity($city = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($city)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_CITY, $city, $comparison);
    }

    /**
     * Filter the query on the bookshop_country column
     *
     * Example usage:
     * <code>
     * $query->filterByCountry('fooValue');   // WHERE bookshop_country = 'fooValue'
     * $query->filterByCountry('%fooValue%', Criteria::LIKE); // WHERE bookshop_country LIKE '%fooValue%'
     * </code>
     *
     * @param     string $country The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function filterByCountry($country = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($country)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_COUNTRY, $country, $comparison);
    }

    /**
     * Filter the query on the bookshop_phone column
     *
     * Example usage:
     * <code>
     * $query->filterByPhone('fooValue');   // WHERE bookshop_phone = 'fooValue'
     * $query->filterByPhone('%fooValue%', Criteria::LIKE); // WHERE bookshop_phone LIKE '%fooValue%'
     * </code>
     *
     * @param     string $phone The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function filterByPhone($phone = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($phone)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_PHONE, $phone, $comparison);
    }

    /**
     * Filter the query on the bookshop_fax column
     *
     * Example usage:
     * <code>
     * $query->filterByFax('fooValue');   // WHERE bookshop_fax = 'fooValue'
     * $query->filterByFax('%fooValue%', Criteria::LIKE); // WHERE bookshop_fax LIKE '%fooValue%'
     * </code>
     *
     * @param     string $fax The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function filterByFax($fax = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($fax)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_FAX, $fax, $comparison);
    }

    /**
     * Filter the query on the bookshop_website column
     *
     * Example usage:
     * <code>
     * $query->filterByWebsite('fooValue');   // WHERE bookshop_website = 'fooValue'
     * $query->filterByWebsite('%fooValue%', Criteria::LIKE); // WHERE bookshop_website LIKE '%fooValue%'
     * </code>
     *
     * @param     string $website The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function filterByWebsite($website = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($website)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_WEBSITE, $website, $comparison);
    }

    /**
     * Filter the query on the bookshop_email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE bookshop_email = 'fooValue'
     * $query->filterByEmail('%fooValue%', Criteria::LIKE); // WHERE bookshop_email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the bookshop_facebook column
     *
     * Example usage:
     * <code>
     * $query->filterByFacebook('fooValue');   // WHERE bookshop_facebook = 'fooValue'
     * $query->filterByFacebook('%fooValue%', Criteria::LIKE); // WHERE bookshop_facebook LIKE '%fooValue%'
     * </code>
     *
     * @param     string $facebook The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function filterByFacebook($facebook = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($facebook)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_FACEBOOK, $facebook, $comparison);
    }

    /**
     * Filter the query on the bookshop_twitter column
     *
     * Example usage:
     * <code>
     * $query->filterByTwitter('fooValue');   // WHERE bookshop_twitter = 'fooValue'
     * $query->filterByTwitter('%fooValue%', Criteria::LIKE); // WHERE bookshop_twitter LIKE '%fooValue%'
     * </code>
     *
     * @param     string $twitter The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function filterByTwitter($twitter = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($twitter)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_TWITTER, $twitter, $comparison);
    }

    /**
     * Filter the query on the bookshop_legal_form column
     *
     * Example usage:
     * <code>
     * $query->filterByLegalForm('fooValue');   // WHERE bookshop_legal_form = 'fooValue'
     * $query->filterByLegalForm('%fooValue%', Criteria::LIKE); // WHERE bookshop_legal_form LIKE '%fooValue%'
     * </code>
     *
     * @param     string $legalForm The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function filterByLegalForm($legalForm = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($legalForm)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_LEGAL_FORM, $legalForm, $comparison);
    }

    /**
     * Filter the query on the bookshop_creation_year column
     *
     * Example usage:
     * <code>
     * $query->filterByCreationYear('fooValue');   // WHERE bookshop_creation_year = 'fooValue'
     * $query->filterByCreationYear('%fooValue%', Criteria::LIKE); // WHERE bookshop_creation_year LIKE '%fooValue%'
     * </code>
     *
     * @param     string $creationYear The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function filterByCreationYear($creationYear = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($creationYear)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_CREATION_YEAR, $creationYear, $comparison);
    }

    /**
     * Filter the query on the bookshop_specialities column
     *
     * Example usage:
     * <code>
     * $query->filterBySpecialities('fooValue');   // WHERE bookshop_specialities = 'fooValue'
     * $query->filterBySpecialities('%fooValue%', Criteria::LIKE); // WHERE bookshop_specialities LIKE '%fooValue%'
     * </code>
     *
     * @param     string $specialities The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function filterBySpecialities($specialities = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($specialities)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_SPECIALITIES, $specialities, $comparison);
    }

    /**
     * Filter the query on the bookshop_membership column
     *
     * Example usage:
     * <code>
     * $query->filterByMembership('fooValue');   // WHERE bookshop_membership = 'fooValue'
     * $query->filterByMembership('%fooValue%', Criteria::LIKE); // WHERE bookshop_membership LIKE '%fooValue%'
     * </code>
     *
     * @param     string $membership The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function filterByMembership($membership = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($membership)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_MEMBERSHIP, $membership, $comparison);
    }

    /**
     * Filter the query on the bookshop_motto column
     *
     * Example usage:
     * <code>
     * $query->filterByMotto('fooValue');   // WHERE bookshop_motto = 'fooValue'
     * $query->filterByMotto('%fooValue%', Criteria::LIKE); // WHERE bookshop_motto LIKE '%fooValue%'
     * </code>
     *
     * @param     string $motto The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function filterByMotto($motto = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($motto)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_MOTTO, $motto, $comparison);
    }

    /**
     * Filter the query on the bookshop_desc column
     *
     * Example usage:
     * <code>
     * $query->filterByDesc('fooValue');   // WHERE bookshop_desc = 'fooValue'
     * $query->filterByDesc('%fooValue%', Criteria::LIKE); // WHERE bookshop_desc LIKE '%fooValue%'
     * </code>
     *
     * @param     string $desc The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function filterByDesc($desc = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($desc)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_DESC, $desc, $comparison);
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
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
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

        return $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_CREATED, $createdAt, $comparison);
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
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
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

        return $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildBookshop $bookshop Object to remove from the list of results
     *
     * @return $this|ChildBookshopQuery The current query, for fluid interface
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
    public function doDeleteAll(ConnectionInterface $con = null)
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
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
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
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(BookshopTableMap::COL_BOOKSHOP_UPDATED);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(BookshopTableMap::COL_BOOKSHOP_UPDATED);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(BookshopTableMap::COL_BOOKSHOP_CREATED);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(BookshopTableMap::COL_BOOKSHOP_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildBookshopQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(BookshopTableMap::COL_BOOKSHOP_CREATED);
    }

} // BookshopQuery
