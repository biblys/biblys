<?php

namespace Model\Map;

use Model\Bookshop;
use Model\BookshopQuery;
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
 * This class defines the structure of the 'bookshops' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class BookshopTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Model.Map.BookshopTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'bookshops';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\Bookshop';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.Bookshop';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 24;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 24;

    /**
     * the column name for the bookshop_id field
     */
    const COL_BOOKSHOP_ID = 'bookshops.bookshop_id';

    /**
     * the column name for the bookshop_name field
     */
    const COL_BOOKSHOP_NAME = 'bookshops.bookshop_name';

    /**
     * the column name for the bookshop_name_alphabetic field
     */
    const COL_BOOKSHOP_NAME_ALPHABETIC = 'bookshops.bookshop_name_alphabetic';

    /**
     * the column name for the bookshop_url field
     */
    const COL_BOOKSHOP_URL = 'bookshops.bookshop_url';

    /**
     * the column name for the bookshop_representative field
     */
    const COL_BOOKSHOP_REPRESENTATIVE = 'bookshops.bookshop_representative';

    /**
     * the column name for the bookshop_address field
     */
    const COL_BOOKSHOP_ADDRESS = 'bookshops.bookshop_address';

    /**
     * the column name for the bookshop_postal_code field
     */
    const COL_BOOKSHOP_POSTAL_CODE = 'bookshops.bookshop_postal_code';

    /**
     * the column name for the bookshop_city field
     */
    const COL_BOOKSHOP_CITY = 'bookshops.bookshop_city';

    /**
     * the column name for the bookshop_country field
     */
    const COL_BOOKSHOP_COUNTRY = 'bookshops.bookshop_country';

    /**
     * the column name for the bookshop_phone field
     */
    const COL_BOOKSHOP_PHONE = 'bookshops.bookshop_phone';

    /**
     * the column name for the bookshop_fax field
     */
    const COL_BOOKSHOP_FAX = 'bookshops.bookshop_fax';

    /**
     * the column name for the bookshop_website field
     */
    const COL_BOOKSHOP_WEBSITE = 'bookshops.bookshop_website';

    /**
     * the column name for the bookshop_email field
     */
    const COL_BOOKSHOP_EMAIL = 'bookshops.bookshop_email';

    /**
     * the column name for the bookshop_facebook field
     */
    const COL_BOOKSHOP_FACEBOOK = 'bookshops.bookshop_facebook';

    /**
     * the column name for the bookshop_twitter field
     */
    const COL_BOOKSHOP_TWITTER = 'bookshops.bookshop_twitter';

    /**
     * the column name for the bookshop_legal_form field
     */
    const COL_BOOKSHOP_LEGAL_FORM = 'bookshops.bookshop_legal_form';

    /**
     * the column name for the bookshop_creation_year field
     */
    const COL_BOOKSHOP_CREATION_YEAR = 'bookshops.bookshop_creation_year';

    /**
     * the column name for the bookshop_specialities field
     */
    const COL_BOOKSHOP_SPECIALITIES = 'bookshops.bookshop_specialities';

    /**
     * the column name for the bookshop_membership field
     */
    const COL_BOOKSHOP_MEMBERSHIP = 'bookshops.bookshop_membership';

    /**
     * the column name for the bookshop_motto field
     */
    const COL_BOOKSHOP_MOTTO = 'bookshops.bookshop_motto';

    /**
     * the column name for the bookshop_desc field
     */
    const COL_BOOKSHOP_DESC = 'bookshops.bookshop_desc';

    /**
     * the column name for the bookshop_created field
     */
    const COL_BOOKSHOP_CREATED = 'bookshops.bookshop_created';

    /**
     * the column name for the bookshop_updated field
     */
    const COL_BOOKSHOP_UPDATED = 'bookshops.bookshop_updated';

    /**
     * the column name for the bookshop_deleted field
     */
    const COL_BOOKSHOP_DELETED = 'bookshops.bookshop_deleted';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'Name', 'NameAlphabetic', 'Url', 'Representative', 'Address', 'PostalCode', 'City', 'Country', 'Phone', 'Fax', 'Website', 'Email', 'Facebook', 'Twitter', 'LegalForm', 'CreationYear', 'Specialities', 'Membership', 'Motto', 'Desc', 'CreatedAt', 'UpdatedAt', 'DeletedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'name', 'nameAlphabetic', 'url', 'representative', 'address', 'postalCode', 'city', 'country', 'phone', 'fax', 'website', 'email', 'facebook', 'twitter', 'legalForm', 'creationYear', 'specialities', 'membership', 'motto', 'desc', 'createdAt', 'updatedAt', 'deletedAt', ),
        self::TYPE_COLNAME       => array(BookshopTableMap::COL_BOOKSHOP_ID, BookshopTableMap::COL_BOOKSHOP_NAME, BookshopTableMap::COL_BOOKSHOP_NAME_ALPHABETIC, BookshopTableMap::COL_BOOKSHOP_URL, BookshopTableMap::COL_BOOKSHOP_REPRESENTATIVE, BookshopTableMap::COL_BOOKSHOP_ADDRESS, BookshopTableMap::COL_BOOKSHOP_POSTAL_CODE, BookshopTableMap::COL_BOOKSHOP_CITY, BookshopTableMap::COL_BOOKSHOP_COUNTRY, BookshopTableMap::COL_BOOKSHOP_PHONE, BookshopTableMap::COL_BOOKSHOP_FAX, BookshopTableMap::COL_BOOKSHOP_WEBSITE, BookshopTableMap::COL_BOOKSHOP_EMAIL, BookshopTableMap::COL_BOOKSHOP_FACEBOOK, BookshopTableMap::COL_BOOKSHOP_TWITTER, BookshopTableMap::COL_BOOKSHOP_LEGAL_FORM, BookshopTableMap::COL_BOOKSHOP_CREATION_YEAR, BookshopTableMap::COL_BOOKSHOP_SPECIALITIES, BookshopTableMap::COL_BOOKSHOP_MEMBERSHIP, BookshopTableMap::COL_BOOKSHOP_MOTTO, BookshopTableMap::COL_BOOKSHOP_DESC, BookshopTableMap::COL_BOOKSHOP_CREATED, BookshopTableMap::COL_BOOKSHOP_UPDATED, BookshopTableMap::COL_BOOKSHOP_DELETED, ),
        self::TYPE_FIELDNAME     => array('bookshop_id', 'bookshop_name', 'bookshop_name_alphabetic', 'bookshop_url', 'bookshop_representative', 'bookshop_address', 'bookshop_postal_code', 'bookshop_city', 'bookshop_country', 'bookshop_phone', 'bookshop_fax', 'bookshop_website', 'bookshop_email', 'bookshop_facebook', 'bookshop_twitter', 'bookshop_legal_form', 'bookshop_creation_year', 'bookshop_specialities', 'bookshop_membership', 'bookshop_motto', 'bookshop_desc', 'bookshop_created', 'bookshop_updated', 'bookshop_deleted', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Name' => 1, 'NameAlphabetic' => 2, 'Url' => 3, 'Representative' => 4, 'Address' => 5, 'PostalCode' => 6, 'City' => 7, 'Country' => 8, 'Phone' => 9, 'Fax' => 10, 'Website' => 11, 'Email' => 12, 'Facebook' => 13, 'Twitter' => 14, 'LegalForm' => 15, 'CreationYear' => 16, 'Specialities' => 17, 'Membership' => 18, 'Motto' => 19, 'Desc' => 20, 'CreatedAt' => 21, 'UpdatedAt' => 22, 'DeletedAt' => 23, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'name' => 1, 'nameAlphabetic' => 2, 'url' => 3, 'representative' => 4, 'address' => 5, 'postalCode' => 6, 'city' => 7, 'country' => 8, 'phone' => 9, 'fax' => 10, 'website' => 11, 'email' => 12, 'facebook' => 13, 'twitter' => 14, 'legalForm' => 15, 'creationYear' => 16, 'specialities' => 17, 'membership' => 18, 'motto' => 19, 'desc' => 20, 'createdAt' => 21, 'updatedAt' => 22, 'deletedAt' => 23, ),
        self::TYPE_COLNAME       => array(BookshopTableMap::COL_BOOKSHOP_ID => 0, BookshopTableMap::COL_BOOKSHOP_NAME => 1, BookshopTableMap::COL_BOOKSHOP_NAME_ALPHABETIC => 2, BookshopTableMap::COL_BOOKSHOP_URL => 3, BookshopTableMap::COL_BOOKSHOP_REPRESENTATIVE => 4, BookshopTableMap::COL_BOOKSHOP_ADDRESS => 5, BookshopTableMap::COL_BOOKSHOP_POSTAL_CODE => 6, BookshopTableMap::COL_BOOKSHOP_CITY => 7, BookshopTableMap::COL_BOOKSHOP_COUNTRY => 8, BookshopTableMap::COL_BOOKSHOP_PHONE => 9, BookshopTableMap::COL_BOOKSHOP_FAX => 10, BookshopTableMap::COL_BOOKSHOP_WEBSITE => 11, BookshopTableMap::COL_BOOKSHOP_EMAIL => 12, BookshopTableMap::COL_BOOKSHOP_FACEBOOK => 13, BookshopTableMap::COL_BOOKSHOP_TWITTER => 14, BookshopTableMap::COL_BOOKSHOP_LEGAL_FORM => 15, BookshopTableMap::COL_BOOKSHOP_CREATION_YEAR => 16, BookshopTableMap::COL_BOOKSHOP_SPECIALITIES => 17, BookshopTableMap::COL_BOOKSHOP_MEMBERSHIP => 18, BookshopTableMap::COL_BOOKSHOP_MOTTO => 19, BookshopTableMap::COL_BOOKSHOP_DESC => 20, BookshopTableMap::COL_BOOKSHOP_CREATED => 21, BookshopTableMap::COL_BOOKSHOP_UPDATED => 22, BookshopTableMap::COL_BOOKSHOP_DELETED => 23, ),
        self::TYPE_FIELDNAME     => array('bookshop_id' => 0, 'bookshop_name' => 1, 'bookshop_name_alphabetic' => 2, 'bookshop_url' => 3, 'bookshop_representative' => 4, 'bookshop_address' => 5, 'bookshop_postal_code' => 6, 'bookshop_city' => 7, 'bookshop_country' => 8, 'bookshop_phone' => 9, 'bookshop_fax' => 10, 'bookshop_website' => 11, 'bookshop_email' => 12, 'bookshop_facebook' => 13, 'bookshop_twitter' => 14, 'bookshop_legal_form' => 15, 'bookshop_creation_year' => 16, 'bookshop_specialities' => 17, 'bookshop_membership' => 18, 'bookshop_motto' => 19, 'bookshop_desc' => 20, 'bookshop_created' => 21, 'bookshop_updated' => 22, 'bookshop_deleted' => 23, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, )
    );

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var string[]
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'BOOKSHOP_ID',
        'Bookshop.Id' => 'BOOKSHOP_ID',
        'id' => 'BOOKSHOP_ID',
        'bookshop.id' => 'BOOKSHOP_ID',
        'BookshopTableMap::COL_BOOKSHOP_ID' => 'BOOKSHOP_ID',
        'COL_BOOKSHOP_ID' => 'BOOKSHOP_ID',
        'bookshop_id' => 'BOOKSHOP_ID',
        'bookshops.bookshop_id' => 'BOOKSHOP_ID',
        'Name' => 'BOOKSHOP_NAME',
        'Bookshop.Name' => 'BOOKSHOP_NAME',
        'name' => 'BOOKSHOP_NAME',
        'bookshop.name' => 'BOOKSHOP_NAME',
        'BookshopTableMap::COL_BOOKSHOP_NAME' => 'BOOKSHOP_NAME',
        'COL_BOOKSHOP_NAME' => 'BOOKSHOP_NAME',
        'bookshop_name' => 'BOOKSHOP_NAME',
        'bookshops.bookshop_name' => 'BOOKSHOP_NAME',
        'NameAlphabetic' => 'BOOKSHOP_NAME_ALPHABETIC',
        'Bookshop.NameAlphabetic' => 'BOOKSHOP_NAME_ALPHABETIC',
        'nameAlphabetic' => 'BOOKSHOP_NAME_ALPHABETIC',
        'bookshop.nameAlphabetic' => 'BOOKSHOP_NAME_ALPHABETIC',
        'BookshopTableMap::COL_BOOKSHOP_NAME_ALPHABETIC' => 'BOOKSHOP_NAME_ALPHABETIC',
        'COL_BOOKSHOP_NAME_ALPHABETIC' => 'BOOKSHOP_NAME_ALPHABETIC',
        'bookshop_name_alphabetic' => 'BOOKSHOP_NAME_ALPHABETIC',
        'bookshops.bookshop_name_alphabetic' => 'BOOKSHOP_NAME_ALPHABETIC',
        'Url' => 'BOOKSHOP_URL',
        'Bookshop.Url' => 'BOOKSHOP_URL',
        'url' => 'BOOKSHOP_URL',
        'bookshop.url' => 'BOOKSHOP_URL',
        'BookshopTableMap::COL_BOOKSHOP_URL' => 'BOOKSHOP_URL',
        'COL_BOOKSHOP_URL' => 'BOOKSHOP_URL',
        'bookshop_url' => 'BOOKSHOP_URL',
        'bookshops.bookshop_url' => 'BOOKSHOP_URL',
        'Representative' => 'BOOKSHOP_REPRESENTATIVE',
        'Bookshop.Representative' => 'BOOKSHOP_REPRESENTATIVE',
        'representative' => 'BOOKSHOP_REPRESENTATIVE',
        'bookshop.representative' => 'BOOKSHOP_REPRESENTATIVE',
        'BookshopTableMap::COL_BOOKSHOP_REPRESENTATIVE' => 'BOOKSHOP_REPRESENTATIVE',
        'COL_BOOKSHOP_REPRESENTATIVE' => 'BOOKSHOP_REPRESENTATIVE',
        'bookshop_representative' => 'BOOKSHOP_REPRESENTATIVE',
        'bookshops.bookshop_representative' => 'BOOKSHOP_REPRESENTATIVE',
        'Address' => 'BOOKSHOP_ADDRESS',
        'Bookshop.Address' => 'BOOKSHOP_ADDRESS',
        'address' => 'BOOKSHOP_ADDRESS',
        'bookshop.address' => 'BOOKSHOP_ADDRESS',
        'BookshopTableMap::COL_BOOKSHOP_ADDRESS' => 'BOOKSHOP_ADDRESS',
        'COL_BOOKSHOP_ADDRESS' => 'BOOKSHOP_ADDRESS',
        'bookshop_address' => 'BOOKSHOP_ADDRESS',
        'bookshops.bookshop_address' => 'BOOKSHOP_ADDRESS',
        'PostalCode' => 'BOOKSHOP_POSTAL_CODE',
        'Bookshop.PostalCode' => 'BOOKSHOP_POSTAL_CODE',
        'postalCode' => 'BOOKSHOP_POSTAL_CODE',
        'bookshop.postalCode' => 'BOOKSHOP_POSTAL_CODE',
        'BookshopTableMap::COL_BOOKSHOP_POSTAL_CODE' => 'BOOKSHOP_POSTAL_CODE',
        'COL_BOOKSHOP_POSTAL_CODE' => 'BOOKSHOP_POSTAL_CODE',
        'bookshop_postal_code' => 'BOOKSHOP_POSTAL_CODE',
        'bookshops.bookshop_postal_code' => 'BOOKSHOP_POSTAL_CODE',
        'City' => 'BOOKSHOP_CITY',
        'Bookshop.City' => 'BOOKSHOP_CITY',
        'city' => 'BOOKSHOP_CITY',
        'bookshop.city' => 'BOOKSHOP_CITY',
        'BookshopTableMap::COL_BOOKSHOP_CITY' => 'BOOKSHOP_CITY',
        'COL_BOOKSHOP_CITY' => 'BOOKSHOP_CITY',
        'bookshop_city' => 'BOOKSHOP_CITY',
        'bookshops.bookshop_city' => 'BOOKSHOP_CITY',
        'Country' => 'BOOKSHOP_COUNTRY',
        'Bookshop.Country' => 'BOOKSHOP_COUNTRY',
        'country' => 'BOOKSHOP_COUNTRY',
        'bookshop.country' => 'BOOKSHOP_COUNTRY',
        'BookshopTableMap::COL_BOOKSHOP_COUNTRY' => 'BOOKSHOP_COUNTRY',
        'COL_BOOKSHOP_COUNTRY' => 'BOOKSHOP_COUNTRY',
        'bookshop_country' => 'BOOKSHOP_COUNTRY',
        'bookshops.bookshop_country' => 'BOOKSHOP_COUNTRY',
        'Phone' => 'BOOKSHOP_PHONE',
        'Bookshop.Phone' => 'BOOKSHOP_PHONE',
        'phone' => 'BOOKSHOP_PHONE',
        'bookshop.phone' => 'BOOKSHOP_PHONE',
        'BookshopTableMap::COL_BOOKSHOP_PHONE' => 'BOOKSHOP_PHONE',
        'COL_BOOKSHOP_PHONE' => 'BOOKSHOP_PHONE',
        'bookshop_phone' => 'BOOKSHOP_PHONE',
        'bookshops.bookshop_phone' => 'BOOKSHOP_PHONE',
        'Fax' => 'BOOKSHOP_FAX',
        'Bookshop.Fax' => 'BOOKSHOP_FAX',
        'fax' => 'BOOKSHOP_FAX',
        'bookshop.fax' => 'BOOKSHOP_FAX',
        'BookshopTableMap::COL_BOOKSHOP_FAX' => 'BOOKSHOP_FAX',
        'COL_BOOKSHOP_FAX' => 'BOOKSHOP_FAX',
        'bookshop_fax' => 'BOOKSHOP_FAX',
        'bookshops.bookshop_fax' => 'BOOKSHOP_FAX',
        'Website' => 'BOOKSHOP_WEBSITE',
        'Bookshop.Website' => 'BOOKSHOP_WEBSITE',
        'website' => 'BOOKSHOP_WEBSITE',
        'bookshop.website' => 'BOOKSHOP_WEBSITE',
        'BookshopTableMap::COL_BOOKSHOP_WEBSITE' => 'BOOKSHOP_WEBSITE',
        'COL_BOOKSHOP_WEBSITE' => 'BOOKSHOP_WEBSITE',
        'bookshop_website' => 'BOOKSHOP_WEBSITE',
        'bookshops.bookshop_website' => 'BOOKSHOP_WEBSITE',
        'Email' => 'BOOKSHOP_EMAIL',
        'Bookshop.Email' => 'BOOKSHOP_EMAIL',
        'email' => 'BOOKSHOP_EMAIL',
        'bookshop.email' => 'BOOKSHOP_EMAIL',
        'BookshopTableMap::COL_BOOKSHOP_EMAIL' => 'BOOKSHOP_EMAIL',
        'COL_BOOKSHOP_EMAIL' => 'BOOKSHOP_EMAIL',
        'bookshop_email' => 'BOOKSHOP_EMAIL',
        'bookshops.bookshop_email' => 'BOOKSHOP_EMAIL',
        'Facebook' => 'BOOKSHOP_FACEBOOK',
        'Bookshop.Facebook' => 'BOOKSHOP_FACEBOOK',
        'facebook' => 'BOOKSHOP_FACEBOOK',
        'bookshop.facebook' => 'BOOKSHOP_FACEBOOK',
        'BookshopTableMap::COL_BOOKSHOP_FACEBOOK' => 'BOOKSHOP_FACEBOOK',
        'COL_BOOKSHOP_FACEBOOK' => 'BOOKSHOP_FACEBOOK',
        'bookshop_facebook' => 'BOOKSHOP_FACEBOOK',
        'bookshops.bookshop_facebook' => 'BOOKSHOP_FACEBOOK',
        'Twitter' => 'BOOKSHOP_TWITTER',
        'Bookshop.Twitter' => 'BOOKSHOP_TWITTER',
        'twitter' => 'BOOKSHOP_TWITTER',
        'bookshop.twitter' => 'BOOKSHOP_TWITTER',
        'BookshopTableMap::COL_BOOKSHOP_TWITTER' => 'BOOKSHOP_TWITTER',
        'COL_BOOKSHOP_TWITTER' => 'BOOKSHOP_TWITTER',
        'bookshop_twitter' => 'BOOKSHOP_TWITTER',
        'bookshops.bookshop_twitter' => 'BOOKSHOP_TWITTER',
        'LegalForm' => 'BOOKSHOP_LEGAL_FORM',
        'Bookshop.LegalForm' => 'BOOKSHOP_LEGAL_FORM',
        'legalForm' => 'BOOKSHOP_LEGAL_FORM',
        'bookshop.legalForm' => 'BOOKSHOP_LEGAL_FORM',
        'BookshopTableMap::COL_BOOKSHOP_LEGAL_FORM' => 'BOOKSHOP_LEGAL_FORM',
        'COL_BOOKSHOP_LEGAL_FORM' => 'BOOKSHOP_LEGAL_FORM',
        'bookshop_legal_form' => 'BOOKSHOP_LEGAL_FORM',
        'bookshops.bookshop_legal_form' => 'BOOKSHOP_LEGAL_FORM',
        'CreationYear' => 'BOOKSHOP_CREATION_YEAR',
        'Bookshop.CreationYear' => 'BOOKSHOP_CREATION_YEAR',
        'creationYear' => 'BOOKSHOP_CREATION_YEAR',
        'bookshop.creationYear' => 'BOOKSHOP_CREATION_YEAR',
        'BookshopTableMap::COL_BOOKSHOP_CREATION_YEAR' => 'BOOKSHOP_CREATION_YEAR',
        'COL_BOOKSHOP_CREATION_YEAR' => 'BOOKSHOP_CREATION_YEAR',
        'bookshop_creation_year' => 'BOOKSHOP_CREATION_YEAR',
        'bookshops.bookshop_creation_year' => 'BOOKSHOP_CREATION_YEAR',
        'Specialities' => 'BOOKSHOP_SPECIALITIES',
        'Bookshop.Specialities' => 'BOOKSHOP_SPECIALITIES',
        'specialities' => 'BOOKSHOP_SPECIALITIES',
        'bookshop.specialities' => 'BOOKSHOP_SPECIALITIES',
        'BookshopTableMap::COL_BOOKSHOP_SPECIALITIES' => 'BOOKSHOP_SPECIALITIES',
        'COL_BOOKSHOP_SPECIALITIES' => 'BOOKSHOP_SPECIALITIES',
        'bookshop_specialities' => 'BOOKSHOP_SPECIALITIES',
        'bookshops.bookshop_specialities' => 'BOOKSHOP_SPECIALITIES',
        'Membership' => 'BOOKSHOP_MEMBERSHIP',
        'Bookshop.Membership' => 'BOOKSHOP_MEMBERSHIP',
        'membership' => 'BOOKSHOP_MEMBERSHIP',
        'bookshop.membership' => 'BOOKSHOP_MEMBERSHIP',
        'BookshopTableMap::COL_BOOKSHOP_MEMBERSHIP' => 'BOOKSHOP_MEMBERSHIP',
        'COL_BOOKSHOP_MEMBERSHIP' => 'BOOKSHOP_MEMBERSHIP',
        'bookshop_membership' => 'BOOKSHOP_MEMBERSHIP',
        'bookshops.bookshop_membership' => 'BOOKSHOP_MEMBERSHIP',
        'Motto' => 'BOOKSHOP_MOTTO',
        'Bookshop.Motto' => 'BOOKSHOP_MOTTO',
        'motto' => 'BOOKSHOP_MOTTO',
        'bookshop.motto' => 'BOOKSHOP_MOTTO',
        'BookshopTableMap::COL_BOOKSHOP_MOTTO' => 'BOOKSHOP_MOTTO',
        'COL_BOOKSHOP_MOTTO' => 'BOOKSHOP_MOTTO',
        'bookshop_motto' => 'BOOKSHOP_MOTTO',
        'bookshops.bookshop_motto' => 'BOOKSHOP_MOTTO',
        'Desc' => 'BOOKSHOP_DESC',
        'Bookshop.Desc' => 'BOOKSHOP_DESC',
        'desc' => 'BOOKSHOP_DESC',
        'bookshop.desc' => 'BOOKSHOP_DESC',
        'BookshopTableMap::COL_BOOKSHOP_DESC' => 'BOOKSHOP_DESC',
        'COL_BOOKSHOP_DESC' => 'BOOKSHOP_DESC',
        'bookshop_desc' => 'BOOKSHOP_DESC',
        'bookshops.bookshop_desc' => 'BOOKSHOP_DESC',
        'CreatedAt' => 'BOOKSHOP_CREATED',
        'Bookshop.CreatedAt' => 'BOOKSHOP_CREATED',
        'createdAt' => 'BOOKSHOP_CREATED',
        'bookshop.createdAt' => 'BOOKSHOP_CREATED',
        'BookshopTableMap::COL_BOOKSHOP_CREATED' => 'BOOKSHOP_CREATED',
        'COL_BOOKSHOP_CREATED' => 'BOOKSHOP_CREATED',
        'bookshop_created' => 'BOOKSHOP_CREATED',
        'bookshops.bookshop_created' => 'BOOKSHOP_CREATED',
        'UpdatedAt' => 'BOOKSHOP_UPDATED',
        'Bookshop.UpdatedAt' => 'BOOKSHOP_UPDATED',
        'updatedAt' => 'BOOKSHOP_UPDATED',
        'bookshop.updatedAt' => 'BOOKSHOP_UPDATED',
        'BookshopTableMap::COL_BOOKSHOP_UPDATED' => 'BOOKSHOP_UPDATED',
        'COL_BOOKSHOP_UPDATED' => 'BOOKSHOP_UPDATED',
        'bookshop_updated' => 'BOOKSHOP_UPDATED',
        'bookshops.bookshop_updated' => 'BOOKSHOP_UPDATED',
        'DeletedAt' => 'BOOKSHOP_DELETED',
        'Bookshop.DeletedAt' => 'BOOKSHOP_DELETED',
        'deletedAt' => 'BOOKSHOP_DELETED',
        'bookshop.deletedAt' => 'BOOKSHOP_DELETED',
        'BookshopTableMap::COL_BOOKSHOP_DELETED' => 'BOOKSHOP_DELETED',
        'COL_BOOKSHOP_DELETED' => 'BOOKSHOP_DELETED',
        'bookshop_deleted' => 'BOOKSHOP_DELETED',
        'bookshops.bookshop_deleted' => 'BOOKSHOP_DELETED',
    ];

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('bookshops');
        $this->setPhpName('Bookshop');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Bookshop');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('bookshop_id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('bookshop_name', 'Name', 'VARCHAR', false, 255, null);
        $this->addColumn('bookshop_name_alphabetic', 'NameAlphabetic', 'VARCHAR', false, 256, null);
        $this->addColumn('bookshop_url', 'Url', 'VARCHAR', false, 128, null);
        $this->addColumn('bookshop_representative', 'Representative', 'VARCHAR', false, 256, null);
        $this->addColumn('bookshop_address', 'Address', 'VARCHAR', false, 256, null);
        $this->addColumn('bookshop_postal_code', 'PostalCode', 'VARCHAR', false, 8, null);
        $this->addColumn('bookshop_city', 'City', 'VARCHAR', false, 128, null);
        $this->addColumn('bookshop_country', 'Country', 'VARCHAR', false, 128, null);
        $this->addColumn('bookshop_phone', 'Phone', 'VARCHAR', false, 16, null);
        $this->addColumn('bookshop_fax', 'Fax', 'VARCHAR', false, 16, null);
        $this->addColumn('bookshop_website', 'Website', 'VARCHAR', false, 128, null);
        $this->addColumn('bookshop_email', 'Email', 'VARCHAR', false, 128, null);
        $this->addColumn('bookshop_facebook', 'Facebook', 'VARCHAR', false, 32, null);
        $this->addColumn('bookshop_twitter', 'Twitter', 'VARCHAR', false, 15, null);
        $this->addColumn('bookshop_legal_form', 'LegalForm', 'VARCHAR', false, 128, null);
        $this->addColumn('bookshop_creation_year', 'CreationYear', 'VARCHAR', false, 4, null);
        $this->addColumn('bookshop_specialities', 'Specialities', 'VARCHAR', false, 256, null);
        $this->addColumn('bookshop_membership', 'Membership', 'VARCHAR', false, 512, null);
        $this->addColumn('bookshop_motto', 'Motto', 'VARCHAR', false, 128, null);
        $this->addColumn('bookshop_desc', 'Desc', 'LONGVARCHAR', false, null, null);
        $this->addColumn('bookshop_created', 'CreatedAt', 'TIMESTAMP', false, null, 'CURRENT_TIMESTAMP');
        $this->addColumn('bookshop_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('bookshop_deleted', 'DeletedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' => ['create_column' => 'bookshop_created', 'update_column' => 'bookshop_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
        );
    } // getBehaviors()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? BookshopTableMap::CLASS_DEFAULT : BookshopTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (Bookshop object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = BookshopTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = BookshopTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + BookshopTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = BookshopTableMap::OM_CLASS;
            /** @var Bookshop $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            BookshopTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = BookshopTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = BookshopTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Bookshop $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                BookshopTableMap::addInstanceToPool($obj, $key);
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
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(BookshopTableMap::COL_BOOKSHOP_ID);
            $criteria->addSelectColumn(BookshopTableMap::COL_BOOKSHOP_NAME);
            $criteria->addSelectColumn(BookshopTableMap::COL_BOOKSHOP_NAME_ALPHABETIC);
            $criteria->addSelectColumn(BookshopTableMap::COL_BOOKSHOP_URL);
            $criteria->addSelectColumn(BookshopTableMap::COL_BOOKSHOP_REPRESENTATIVE);
            $criteria->addSelectColumn(BookshopTableMap::COL_BOOKSHOP_ADDRESS);
            $criteria->addSelectColumn(BookshopTableMap::COL_BOOKSHOP_POSTAL_CODE);
            $criteria->addSelectColumn(BookshopTableMap::COL_BOOKSHOP_CITY);
            $criteria->addSelectColumn(BookshopTableMap::COL_BOOKSHOP_COUNTRY);
            $criteria->addSelectColumn(BookshopTableMap::COL_BOOKSHOP_PHONE);
            $criteria->addSelectColumn(BookshopTableMap::COL_BOOKSHOP_FAX);
            $criteria->addSelectColumn(BookshopTableMap::COL_BOOKSHOP_WEBSITE);
            $criteria->addSelectColumn(BookshopTableMap::COL_BOOKSHOP_EMAIL);
            $criteria->addSelectColumn(BookshopTableMap::COL_BOOKSHOP_FACEBOOK);
            $criteria->addSelectColumn(BookshopTableMap::COL_BOOKSHOP_TWITTER);
            $criteria->addSelectColumn(BookshopTableMap::COL_BOOKSHOP_LEGAL_FORM);
            $criteria->addSelectColumn(BookshopTableMap::COL_BOOKSHOP_CREATION_YEAR);
            $criteria->addSelectColumn(BookshopTableMap::COL_BOOKSHOP_SPECIALITIES);
            $criteria->addSelectColumn(BookshopTableMap::COL_BOOKSHOP_MEMBERSHIP);
            $criteria->addSelectColumn(BookshopTableMap::COL_BOOKSHOP_MOTTO);
            $criteria->addSelectColumn(BookshopTableMap::COL_BOOKSHOP_DESC);
            $criteria->addSelectColumn(BookshopTableMap::COL_BOOKSHOP_CREATED);
            $criteria->addSelectColumn(BookshopTableMap::COL_BOOKSHOP_UPDATED);
            $criteria->addSelectColumn(BookshopTableMap::COL_BOOKSHOP_DELETED);
        } else {
            $criteria->addSelectColumn($alias . '.bookshop_id');
            $criteria->addSelectColumn($alias . '.bookshop_name');
            $criteria->addSelectColumn($alias . '.bookshop_name_alphabetic');
            $criteria->addSelectColumn($alias . '.bookshop_url');
            $criteria->addSelectColumn($alias . '.bookshop_representative');
            $criteria->addSelectColumn($alias . '.bookshop_address');
            $criteria->addSelectColumn($alias . '.bookshop_postal_code');
            $criteria->addSelectColumn($alias . '.bookshop_city');
            $criteria->addSelectColumn($alias . '.bookshop_country');
            $criteria->addSelectColumn($alias . '.bookshop_phone');
            $criteria->addSelectColumn($alias . '.bookshop_fax');
            $criteria->addSelectColumn($alias . '.bookshop_website');
            $criteria->addSelectColumn($alias . '.bookshop_email');
            $criteria->addSelectColumn($alias . '.bookshop_facebook');
            $criteria->addSelectColumn($alias . '.bookshop_twitter');
            $criteria->addSelectColumn($alias . '.bookshop_legal_form');
            $criteria->addSelectColumn($alias . '.bookshop_creation_year');
            $criteria->addSelectColumn($alias . '.bookshop_specialities');
            $criteria->addSelectColumn($alias . '.bookshop_membership');
            $criteria->addSelectColumn($alias . '.bookshop_motto');
            $criteria->addSelectColumn($alias . '.bookshop_desc');
            $criteria->addSelectColumn($alias . '.bookshop_created');
            $criteria->addSelectColumn($alias . '.bookshop_updated');
            $criteria->addSelectColumn($alias . '.bookshop_deleted');
        }
    }

    /**
     * Remove all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be removed as they are only loaded on demand.
     *
     * @param Criteria $criteria object containing the columns to remove.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function removeSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->removeSelectColumn(BookshopTableMap::COL_BOOKSHOP_ID);
            $criteria->removeSelectColumn(BookshopTableMap::COL_BOOKSHOP_NAME);
            $criteria->removeSelectColumn(BookshopTableMap::COL_BOOKSHOP_NAME_ALPHABETIC);
            $criteria->removeSelectColumn(BookshopTableMap::COL_BOOKSHOP_URL);
            $criteria->removeSelectColumn(BookshopTableMap::COL_BOOKSHOP_REPRESENTATIVE);
            $criteria->removeSelectColumn(BookshopTableMap::COL_BOOKSHOP_ADDRESS);
            $criteria->removeSelectColumn(BookshopTableMap::COL_BOOKSHOP_POSTAL_CODE);
            $criteria->removeSelectColumn(BookshopTableMap::COL_BOOKSHOP_CITY);
            $criteria->removeSelectColumn(BookshopTableMap::COL_BOOKSHOP_COUNTRY);
            $criteria->removeSelectColumn(BookshopTableMap::COL_BOOKSHOP_PHONE);
            $criteria->removeSelectColumn(BookshopTableMap::COL_BOOKSHOP_FAX);
            $criteria->removeSelectColumn(BookshopTableMap::COL_BOOKSHOP_WEBSITE);
            $criteria->removeSelectColumn(BookshopTableMap::COL_BOOKSHOP_EMAIL);
            $criteria->removeSelectColumn(BookshopTableMap::COL_BOOKSHOP_FACEBOOK);
            $criteria->removeSelectColumn(BookshopTableMap::COL_BOOKSHOP_TWITTER);
            $criteria->removeSelectColumn(BookshopTableMap::COL_BOOKSHOP_LEGAL_FORM);
            $criteria->removeSelectColumn(BookshopTableMap::COL_BOOKSHOP_CREATION_YEAR);
            $criteria->removeSelectColumn(BookshopTableMap::COL_BOOKSHOP_SPECIALITIES);
            $criteria->removeSelectColumn(BookshopTableMap::COL_BOOKSHOP_MEMBERSHIP);
            $criteria->removeSelectColumn(BookshopTableMap::COL_BOOKSHOP_MOTTO);
            $criteria->removeSelectColumn(BookshopTableMap::COL_BOOKSHOP_DESC);
            $criteria->removeSelectColumn(BookshopTableMap::COL_BOOKSHOP_CREATED);
            $criteria->removeSelectColumn(BookshopTableMap::COL_BOOKSHOP_UPDATED);
            $criteria->removeSelectColumn(BookshopTableMap::COL_BOOKSHOP_DELETED);
        } else {
            $criteria->removeSelectColumn($alias . '.bookshop_id');
            $criteria->removeSelectColumn($alias . '.bookshop_name');
            $criteria->removeSelectColumn($alias . '.bookshop_name_alphabetic');
            $criteria->removeSelectColumn($alias . '.bookshop_url');
            $criteria->removeSelectColumn($alias . '.bookshop_representative');
            $criteria->removeSelectColumn($alias . '.bookshop_address');
            $criteria->removeSelectColumn($alias . '.bookshop_postal_code');
            $criteria->removeSelectColumn($alias . '.bookshop_city');
            $criteria->removeSelectColumn($alias . '.bookshop_country');
            $criteria->removeSelectColumn($alias . '.bookshop_phone');
            $criteria->removeSelectColumn($alias . '.bookshop_fax');
            $criteria->removeSelectColumn($alias . '.bookshop_website');
            $criteria->removeSelectColumn($alias . '.bookshop_email');
            $criteria->removeSelectColumn($alias . '.bookshop_facebook');
            $criteria->removeSelectColumn($alias . '.bookshop_twitter');
            $criteria->removeSelectColumn($alias . '.bookshop_legal_form');
            $criteria->removeSelectColumn($alias . '.bookshop_creation_year');
            $criteria->removeSelectColumn($alias . '.bookshop_specialities');
            $criteria->removeSelectColumn($alias . '.bookshop_membership');
            $criteria->removeSelectColumn($alias . '.bookshop_motto');
            $criteria->removeSelectColumn($alias . '.bookshop_desc');
            $criteria->removeSelectColumn($alias . '.bookshop_created');
            $criteria->removeSelectColumn($alias . '.bookshop_updated');
            $criteria->removeSelectColumn($alias . '.bookshop_deleted');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(BookshopTableMap::DATABASE_NAME)->getTable(BookshopTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Bookshop or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Bookshop object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(BookshopTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Bookshop) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(BookshopTableMap::DATABASE_NAME);
            $criteria->add(BookshopTableMap::COL_BOOKSHOP_ID, (array) $values, Criteria::IN);
        }

        $query = BookshopQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            BookshopTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                BookshopTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the bookshops table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return BookshopQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Bookshop or Criteria object.
     *
     * @param mixed               $criteria Criteria or Bookshop object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(BookshopTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Bookshop object
        }

        if ($criteria->containsKey(BookshopTableMap::COL_BOOKSHOP_ID) && $criteria->keyContainsValue(BookshopTableMap::COL_BOOKSHOP_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.BookshopTableMap::COL_BOOKSHOP_ID.')');
        }


        // Set the correct dbName
        $query = BookshopQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // BookshopTableMap
