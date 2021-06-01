<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Library as ChildLibrary;
use Model\LibraryQuery as ChildLibraryQuery;
use Model\Map\LibraryTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'libraries' table.
 *
 *
 *
 * @method     ChildLibraryQuery orderById($order = Criteria::ASC) Order by the library_id column
 * @method     ChildLibraryQuery orderByName($order = Criteria::ASC) Order by the library_name column
 * @method     ChildLibraryQuery orderByNameAlphabetic($order = Criteria::ASC) Order by the library_name_alphabetic column
 * @method     ChildLibraryQuery orderByUrl($order = Criteria::ASC) Order by the library_url column
 * @method     ChildLibraryQuery orderByRepresentative($order = Criteria::ASC) Order by the library_representative column
 * @method     ChildLibraryQuery orderByAddress($order = Criteria::ASC) Order by the library_address column
 * @method     ChildLibraryQuery orderByPostalCode($order = Criteria::ASC) Order by the library_postal_code column
 * @method     ChildLibraryQuery orderByCity($order = Criteria::ASC) Order by the library_city column
 * @method     ChildLibraryQuery orderByCountry($order = Criteria::ASC) Order by the library_country column
 * @method     ChildLibraryQuery orderByPhone($order = Criteria::ASC) Order by the library_phone column
 * @method     ChildLibraryQuery orderByFax($order = Criteria::ASC) Order by the library_fax column
 * @method     ChildLibraryQuery orderByWebsite($order = Criteria::ASC) Order by the library_website column
 * @method     ChildLibraryQuery orderByEmail($order = Criteria::ASC) Order by the library_email column
 * @method     ChildLibraryQuery orderByFacebook($order = Criteria::ASC) Order by the library_facebook column
 * @method     ChildLibraryQuery orderByTwitter($order = Criteria::ASC) Order by the library_twitter column
 * @method     ChildLibraryQuery orderByCreationYear($order = Criteria::ASC) Order by the library_creation_year column
 * @method     ChildLibraryQuery orderBySpecialities($order = Criteria::ASC) Order by the library_specialities column
 * @method     ChildLibraryQuery orderByReadings($order = Criteria::ASC) Order by the library_readings column
 * @method     ChildLibraryQuery orderByDesc($order = Criteria::ASC) Order by the library_desc column
 * @method     ChildLibraryQuery orderByCreatedAt($order = Criteria::ASC) Order by the library_created column
 * @method     ChildLibraryQuery orderByUpdatedAt($order = Criteria::ASC) Order by the library_updated column
 * @method     ChildLibraryQuery orderByDeletedAt($order = Criteria::ASC) Order by the library_deleted column
 *
 * @method     ChildLibraryQuery groupById() Group by the library_id column
 * @method     ChildLibraryQuery groupByName() Group by the library_name column
 * @method     ChildLibraryQuery groupByNameAlphabetic() Group by the library_name_alphabetic column
 * @method     ChildLibraryQuery groupByUrl() Group by the library_url column
 * @method     ChildLibraryQuery groupByRepresentative() Group by the library_representative column
 * @method     ChildLibraryQuery groupByAddress() Group by the library_address column
 * @method     ChildLibraryQuery groupByPostalCode() Group by the library_postal_code column
 * @method     ChildLibraryQuery groupByCity() Group by the library_city column
 * @method     ChildLibraryQuery groupByCountry() Group by the library_country column
 * @method     ChildLibraryQuery groupByPhone() Group by the library_phone column
 * @method     ChildLibraryQuery groupByFax() Group by the library_fax column
 * @method     ChildLibraryQuery groupByWebsite() Group by the library_website column
 * @method     ChildLibraryQuery groupByEmail() Group by the library_email column
 * @method     ChildLibraryQuery groupByFacebook() Group by the library_facebook column
 * @method     ChildLibraryQuery groupByTwitter() Group by the library_twitter column
 * @method     ChildLibraryQuery groupByCreationYear() Group by the library_creation_year column
 * @method     ChildLibraryQuery groupBySpecialities() Group by the library_specialities column
 * @method     ChildLibraryQuery groupByReadings() Group by the library_readings column
 * @method     ChildLibraryQuery groupByDesc() Group by the library_desc column
 * @method     ChildLibraryQuery groupByCreatedAt() Group by the library_created column
 * @method     ChildLibraryQuery groupByUpdatedAt() Group by the library_updated column
 * @method     ChildLibraryQuery groupByDeletedAt() Group by the library_deleted column
 *
 * @method     ChildLibraryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLibraryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLibraryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLibraryQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildLibraryQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildLibraryQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildLibrary|null findOne(ConnectionInterface $con = null) Return the first ChildLibrary matching the query
 * @method     ChildLibrary findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLibrary matching the query, or a new ChildLibrary object populated from the query conditions when no match is found
 *
 * @method     ChildLibrary|null findOneById(int $library_id) Return the first ChildLibrary filtered by the library_id column
 * @method     ChildLibrary|null findOneByName(string $library_name) Return the first ChildLibrary filtered by the library_name column
 * @method     ChildLibrary|null findOneByNameAlphabetic(string $library_name_alphabetic) Return the first ChildLibrary filtered by the library_name_alphabetic column
 * @method     ChildLibrary|null findOneByUrl(string $library_url) Return the first ChildLibrary filtered by the library_url column
 * @method     ChildLibrary|null findOneByRepresentative(string $library_representative) Return the first ChildLibrary filtered by the library_representative column
 * @method     ChildLibrary|null findOneByAddress(string $library_address) Return the first ChildLibrary filtered by the library_address column
 * @method     ChildLibrary|null findOneByPostalCode(string $library_postal_code) Return the first ChildLibrary filtered by the library_postal_code column
 * @method     ChildLibrary|null findOneByCity(string $library_city) Return the first ChildLibrary filtered by the library_city column
 * @method     ChildLibrary|null findOneByCountry(string $library_country) Return the first ChildLibrary filtered by the library_country column
 * @method     ChildLibrary|null findOneByPhone(string $library_phone) Return the first ChildLibrary filtered by the library_phone column
 * @method     ChildLibrary|null findOneByFax(string $library_fax) Return the first ChildLibrary filtered by the library_fax column
 * @method     ChildLibrary|null findOneByWebsite(string $library_website) Return the first ChildLibrary filtered by the library_website column
 * @method     ChildLibrary|null findOneByEmail(string $library_email) Return the first ChildLibrary filtered by the library_email column
 * @method     ChildLibrary|null findOneByFacebook(string $library_facebook) Return the first ChildLibrary filtered by the library_facebook column
 * @method     ChildLibrary|null findOneByTwitter(string $library_twitter) Return the first ChildLibrary filtered by the library_twitter column
 * @method     ChildLibrary|null findOneByCreationYear(string $library_creation_year) Return the first ChildLibrary filtered by the library_creation_year column
 * @method     ChildLibrary|null findOneBySpecialities(string $library_specialities) Return the first ChildLibrary filtered by the library_specialities column
 * @method     ChildLibrary|null findOneByReadings(string $library_readings) Return the first ChildLibrary filtered by the library_readings column
 * @method     ChildLibrary|null findOneByDesc(string $library_desc) Return the first ChildLibrary filtered by the library_desc column
 * @method     ChildLibrary|null findOneByCreatedAt(string $library_created) Return the first ChildLibrary filtered by the library_created column
 * @method     ChildLibrary|null findOneByUpdatedAt(string $library_updated) Return the first ChildLibrary filtered by the library_updated column
 * @method     ChildLibrary|null findOneByDeletedAt(string $library_deleted) Return the first ChildLibrary filtered by the library_deleted column *

 * @method     ChildLibrary requirePk($key, ConnectionInterface $con = null) Return the ChildLibrary by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLibrary requireOne(ConnectionInterface $con = null) Return the first ChildLibrary matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLibrary requireOneById(int $library_id) Return the first ChildLibrary filtered by the library_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLibrary requireOneByName(string $library_name) Return the first ChildLibrary filtered by the library_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLibrary requireOneByNameAlphabetic(string $library_name_alphabetic) Return the first ChildLibrary filtered by the library_name_alphabetic column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLibrary requireOneByUrl(string $library_url) Return the first ChildLibrary filtered by the library_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLibrary requireOneByRepresentative(string $library_representative) Return the first ChildLibrary filtered by the library_representative column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLibrary requireOneByAddress(string $library_address) Return the first ChildLibrary filtered by the library_address column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLibrary requireOneByPostalCode(string $library_postal_code) Return the first ChildLibrary filtered by the library_postal_code column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLibrary requireOneByCity(string $library_city) Return the first ChildLibrary filtered by the library_city column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLibrary requireOneByCountry(string $library_country) Return the first ChildLibrary filtered by the library_country column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLibrary requireOneByPhone(string $library_phone) Return the first ChildLibrary filtered by the library_phone column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLibrary requireOneByFax(string $library_fax) Return the first ChildLibrary filtered by the library_fax column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLibrary requireOneByWebsite(string $library_website) Return the first ChildLibrary filtered by the library_website column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLibrary requireOneByEmail(string $library_email) Return the first ChildLibrary filtered by the library_email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLibrary requireOneByFacebook(string $library_facebook) Return the first ChildLibrary filtered by the library_facebook column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLibrary requireOneByTwitter(string $library_twitter) Return the first ChildLibrary filtered by the library_twitter column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLibrary requireOneByCreationYear(string $library_creation_year) Return the first ChildLibrary filtered by the library_creation_year column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLibrary requireOneBySpecialities(string $library_specialities) Return the first ChildLibrary filtered by the library_specialities column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLibrary requireOneByReadings(string $library_readings) Return the first ChildLibrary filtered by the library_readings column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLibrary requireOneByDesc(string $library_desc) Return the first ChildLibrary filtered by the library_desc column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLibrary requireOneByCreatedAt(string $library_created) Return the first ChildLibrary filtered by the library_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLibrary requireOneByUpdatedAt(string $library_updated) Return the first ChildLibrary filtered by the library_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLibrary requireOneByDeletedAt(string $library_deleted) Return the first ChildLibrary filtered by the library_deleted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLibrary[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildLibrary objects based on current ModelCriteria
 * @method     ChildLibrary[]|ObjectCollection findById(int $library_id) Return ChildLibrary objects filtered by the library_id column
 * @method     ChildLibrary[]|ObjectCollection findByName(string $library_name) Return ChildLibrary objects filtered by the library_name column
 * @method     ChildLibrary[]|ObjectCollection findByNameAlphabetic(string $library_name_alphabetic) Return ChildLibrary objects filtered by the library_name_alphabetic column
 * @method     ChildLibrary[]|ObjectCollection findByUrl(string $library_url) Return ChildLibrary objects filtered by the library_url column
 * @method     ChildLibrary[]|ObjectCollection findByRepresentative(string $library_representative) Return ChildLibrary objects filtered by the library_representative column
 * @method     ChildLibrary[]|ObjectCollection findByAddress(string $library_address) Return ChildLibrary objects filtered by the library_address column
 * @method     ChildLibrary[]|ObjectCollection findByPostalCode(string $library_postal_code) Return ChildLibrary objects filtered by the library_postal_code column
 * @method     ChildLibrary[]|ObjectCollection findByCity(string $library_city) Return ChildLibrary objects filtered by the library_city column
 * @method     ChildLibrary[]|ObjectCollection findByCountry(string $library_country) Return ChildLibrary objects filtered by the library_country column
 * @method     ChildLibrary[]|ObjectCollection findByPhone(string $library_phone) Return ChildLibrary objects filtered by the library_phone column
 * @method     ChildLibrary[]|ObjectCollection findByFax(string $library_fax) Return ChildLibrary objects filtered by the library_fax column
 * @method     ChildLibrary[]|ObjectCollection findByWebsite(string $library_website) Return ChildLibrary objects filtered by the library_website column
 * @method     ChildLibrary[]|ObjectCollection findByEmail(string $library_email) Return ChildLibrary objects filtered by the library_email column
 * @method     ChildLibrary[]|ObjectCollection findByFacebook(string $library_facebook) Return ChildLibrary objects filtered by the library_facebook column
 * @method     ChildLibrary[]|ObjectCollection findByTwitter(string $library_twitter) Return ChildLibrary objects filtered by the library_twitter column
 * @method     ChildLibrary[]|ObjectCollection findByCreationYear(string $library_creation_year) Return ChildLibrary objects filtered by the library_creation_year column
 * @method     ChildLibrary[]|ObjectCollection findBySpecialities(string $library_specialities) Return ChildLibrary objects filtered by the library_specialities column
 * @method     ChildLibrary[]|ObjectCollection findByReadings(string $library_readings) Return ChildLibrary objects filtered by the library_readings column
 * @method     ChildLibrary[]|ObjectCollection findByDesc(string $library_desc) Return ChildLibrary objects filtered by the library_desc column
 * @method     ChildLibrary[]|ObjectCollection findByCreatedAt(string $library_created) Return ChildLibrary objects filtered by the library_created column
 * @method     ChildLibrary[]|ObjectCollection findByUpdatedAt(string $library_updated) Return ChildLibrary objects filtered by the library_updated column
 * @method     ChildLibrary[]|ObjectCollection findByDeletedAt(string $library_deleted) Return ChildLibrary objects filtered by the library_deleted column
 * @method     ChildLibrary[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class LibraryQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\LibraryQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Library', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLibraryQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLibraryQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildLibraryQuery) {
            return $criteria;
        }
        $query = new ChildLibraryQuery();
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
     * @return ChildLibrary|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LibraryTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = LibraryTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildLibrary A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT library_id, library_name, library_name_alphabetic, library_url, library_representative, library_address, library_postal_code, library_city, library_country, library_phone, library_fax, library_website, library_email, library_facebook, library_twitter, library_creation_year, library_specialities, library_readings, library_desc, library_created, library_updated, library_deleted FROM libraries WHERE library_id = :p0';
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
            /** @var ChildLibrary $obj */
            $obj = new ChildLibrary();
            $obj->hydrate($row);
            LibraryTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildLibrary|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the library_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE library_id = 1234
     * $query->filterById(array(12, 34)); // WHERE library_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE library_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_ID, $id, $comparison);
    }

    /**
     * Filter the query on the library_name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE library_name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE library_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the library_name_alphabetic column
     *
     * Example usage:
     * <code>
     * $query->filterByNameAlphabetic('fooValue');   // WHERE library_name_alphabetic = 'fooValue'
     * $query->filterByNameAlphabetic('%fooValue%', Criteria::LIKE); // WHERE library_name_alphabetic LIKE '%fooValue%'
     * </code>
     *
     * @param     string $nameAlphabetic The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function filterByNameAlphabetic($nameAlphabetic = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nameAlphabetic)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_NAME_ALPHABETIC, $nameAlphabetic, $comparison);
    }

    /**
     * Filter the query on the library_url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE library_url = 'fooValue'
     * $query->filterByUrl('%fooValue%', Criteria::LIKE); // WHERE library_url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $url The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function filterByUrl($url = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($url)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_URL, $url, $comparison);
    }

    /**
     * Filter the query on the library_representative column
     *
     * Example usage:
     * <code>
     * $query->filterByRepresentative('fooValue');   // WHERE library_representative = 'fooValue'
     * $query->filterByRepresentative('%fooValue%', Criteria::LIKE); // WHERE library_representative LIKE '%fooValue%'
     * </code>
     *
     * @param     string $representative The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function filterByRepresentative($representative = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($representative)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_REPRESENTATIVE, $representative, $comparison);
    }

    /**
     * Filter the query on the library_address column
     *
     * Example usage:
     * <code>
     * $query->filterByAddress('fooValue');   // WHERE library_address = 'fooValue'
     * $query->filterByAddress('%fooValue%', Criteria::LIKE); // WHERE library_address LIKE '%fooValue%'
     * </code>
     *
     * @param     string $address The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function filterByAddress($address = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($address)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_ADDRESS, $address, $comparison);
    }

    /**
     * Filter the query on the library_postal_code column
     *
     * Example usage:
     * <code>
     * $query->filterByPostalCode('fooValue');   // WHERE library_postal_code = 'fooValue'
     * $query->filterByPostalCode('%fooValue%', Criteria::LIKE); // WHERE library_postal_code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $postalCode The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function filterByPostalCode($postalCode = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($postalCode)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_POSTAL_CODE, $postalCode, $comparison);
    }

    /**
     * Filter the query on the library_city column
     *
     * Example usage:
     * <code>
     * $query->filterByCity('fooValue');   // WHERE library_city = 'fooValue'
     * $query->filterByCity('%fooValue%', Criteria::LIKE); // WHERE library_city LIKE '%fooValue%'
     * </code>
     *
     * @param     string $city The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function filterByCity($city = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($city)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_CITY, $city, $comparison);
    }

    /**
     * Filter the query on the library_country column
     *
     * Example usage:
     * <code>
     * $query->filterByCountry('fooValue');   // WHERE library_country = 'fooValue'
     * $query->filterByCountry('%fooValue%', Criteria::LIKE); // WHERE library_country LIKE '%fooValue%'
     * </code>
     *
     * @param     string $country The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function filterByCountry($country = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($country)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_COUNTRY, $country, $comparison);
    }

    /**
     * Filter the query on the library_phone column
     *
     * Example usage:
     * <code>
     * $query->filterByPhone('fooValue');   // WHERE library_phone = 'fooValue'
     * $query->filterByPhone('%fooValue%', Criteria::LIKE); // WHERE library_phone LIKE '%fooValue%'
     * </code>
     *
     * @param     string $phone The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function filterByPhone($phone = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($phone)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_PHONE, $phone, $comparison);
    }

    /**
     * Filter the query on the library_fax column
     *
     * Example usage:
     * <code>
     * $query->filterByFax('fooValue');   // WHERE library_fax = 'fooValue'
     * $query->filterByFax('%fooValue%', Criteria::LIKE); // WHERE library_fax LIKE '%fooValue%'
     * </code>
     *
     * @param     string $fax The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function filterByFax($fax = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($fax)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_FAX, $fax, $comparison);
    }

    /**
     * Filter the query on the library_website column
     *
     * Example usage:
     * <code>
     * $query->filterByWebsite('fooValue');   // WHERE library_website = 'fooValue'
     * $query->filterByWebsite('%fooValue%', Criteria::LIKE); // WHERE library_website LIKE '%fooValue%'
     * </code>
     *
     * @param     string $website The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function filterByWebsite($website = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($website)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_WEBSITE, $website, $comparison);
    }

    /**
     * Filter the query on the library_email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE library_email = 'fooValue'
     * $query->filterByEmail('%fooValue%', Criteria::LIKE); // WHERE library_email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the library_facebook column
     *
     * Example usage:
     * <code>
     * $query->filterByFacebook('fooValue');   // WHERE library_facebook = 'fooValue'
     * $query->filterByFacebook('%fooValue%', Criteria::LIKE); // WHERE library_facebook LIKE '%fooValue%'
     * </code>
     *
     * @param     string $facebook The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function filterByFacebook($facebook = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($facebook)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_FACEBOOK, $facebook, $comparison);
    }

    /**
     * Filter the query on the library_twitter column
     *
     * Example usage:
     * <code>
     * $query->filterByTwitter('fooValue');   // WHERE library_twitter = 'fooValue'
     * $query->filterByTwitter('%fooValue%', Criteria::LIKE); // WHERE library_twitter LIKE '%fooValue%'
     * </code>
     *
     * @param     string $twitter The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function filterByTwitter($twitter = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($twitter)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_TWITTER, $twitter, $comparison);
    }

    /**
     * Filter the query on the library_creation_year column
     *
     * Example usage:
     * <code>
     * $query->filterByCreationYear('fooValue');   // WHERE library_creation_year = 'fooValue'
     * $query->filterByCreationYear('%fooValue%', Criteria::LIKE); // WHERE library_creation_year LIKE '%fooValue%'
     * </code>
     *
     * @param     string $creationYear The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function filterByCreationYear($creationYear = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($creationYear)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_CREATION_YEAR, $creationYear, $comparison);
    }

    /**
     * Filter the query on the library_specialities column
     *
     * Example usage:
     * <code>
     * $query->filterBySpecialities('fooValue');   // WHERE library_specialities = 'fooValue'
     * $query->filterBySpecialities('%fooValue%', Criteria::LIKE); // WHERE library_specialities LIKE '%fooValue%'
     * </code>
     *
     * @param     string $specialities The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function filterBySpecialities($specialities = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($specialities)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_SPECIALITIES, $specialities, $comparison);
    }

    /**
     * Filter the query on the library_readings column
     *
     * Example usage:
     * <code>
     * $query->filterByReadings('fooValue');   // WHERE library_readings = 'fooValue'
     * $query->filterByReadings('%fooValue%', Criteria::LIKE); // WHERE library_readings LIKE '%fooValue%'
     * </code>
     *
     * @param     string $readings The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function filterByReadings($readings = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($readings)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_READINGS, $readings, $comparison);
    }

    /**
     * Filter the query on the library_desc column
     *
     * Example usage:
     * <code>
     * $query->filterByDesc('fooValue');   // WHERE library_desc = 'fooValue'
     * $query->filterByDesc('%fooValue%', Criteria::LIKE); // WHERE library_desc LIKE '%fooValue%'
     * </code>
     *
     * @param     string $desc The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function filterByDesc($desc = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($desc)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_DESC, $desc, $comparison);
    }

    /**
     * Filter the query on the library_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE library_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE library_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE library_created > '2011-03-13'
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
     * @return $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_CREATED, $createdAt, $comparison);
    }

    /**
     * Filter the query on the library_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE library_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE library_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE library_updated > '2011-03-13'
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
     * @return $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the library_deleted column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletedAt('2011-03-14'); // WHERE library_deleted = '2011-03-14'
     * $query->filterByDeletedAt('now'); // WHERE library_deleted = '2011-03-14'
     * $query->filterByDeletedAt(array('max' => 'yesterday')); // WHERE library_deleted > '2011-03-13'
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
     * @return $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function filterByDeletedAt($deletedAt = null, $comparison = null)
    {
        if (is_array($deletedAt)) {
            $useMinMax = false;
            if (isset($deletedAt['min'])) {
                $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_DELETED, $deletedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletedAt['max'])) {
                $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_DELETED, $deletedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_DELETED, $deletedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildLibrary $library Object to remove from the list of results
     *
     * @return $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function prune($library = null)
    {
        if ($library) {
            $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_ID, $library->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the libraries table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LibraryTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LibraryTableMap::clearInstancePool();
            LibraryTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(LibraryTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(LibraryTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            LibraryTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            LibraryTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(LibraryTableMap::COL_LIBRARY_UPDATED);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(LibraryTableMap::COL_LIBRARY_UPDATED);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(LibraryTableMap::COL_LIBRARY_CREATED);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(LibraryTableMap::COL_LIBRARY_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildLibraryQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(LibraryTableMap::COL_LIBRARY_CREATED);
    }

} // LibraryQuery
