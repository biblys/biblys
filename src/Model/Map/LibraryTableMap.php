<?php

namespace Model\Map;

use Model\Library;
use Model\LibraryQuery;
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
 * This class defines the structure of the 'libraries' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class LibraryTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Model.Map.LibraryTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'libraries';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\Library';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.Library';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 22;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 22;

    /**
     * the column name for the library_id field
     */
    const COL_LIBRARY_ID = 'libraries.library_id';

    /**
     * the column name for the library_name field
     */
    const COL_LIBRARY_NAME = 'libraries.library_name';

    /**
     * the column name for the library_name_alphabetic field
     */
    const COL_LIBRARY_NAME_ALPHABETIC = 'libraries.library_name_alphabetic';

    /**
     * the column name for the library_url field
     */
    const COL_LIBRARY_URL = 'libraries.library_url';

    /**
     * the column name for the library_representative field
     */
    const COL_LIBRARY_REPRESENTATIVE = 'libraries.library_representative';

    /**
     * the column name for the library_address field
     */
    const COL_LIBRARY_ADDRESS = 'libraries.library_address';

    /**
     * the column name for the library_postal_code field
     */
    const COL_LIBRARY_POSTAL_CODE = 'libraries.library_postal_code';

    /**
     * the column name for the library_city field
     */
    const COL_LIBRARY_CITY = 'libraries.library_city';

    /**
     * the column name for the library_country field
     */
    const COL_LIBRARY_COUNTRY = 'libraries.library_country';

    /**
     * the column name for the library_phone field
     */
    const COL_LIBRARY_PHONE = 'libraries.library_phone';

    /**
     * the column name for the library_fax field
     */
    const COL_LIBRARY_FAX = 'libraries.library_fax';

    /**
     * the column name for the library_website field
     */
    const COL_LIBRARY_WEBSITE = 'libraries.library_website';

    /**
     * the column name for the library_email field
     */
    const COL_LIBRARY_EMAIL = 'libraries.library_email';

    /**
     * the column name for the library_facebook field
     */
    const COL_LIBRARY_FACEBOOK = 'libraries.library_facebook';

    /**
     * the column name for the library_twitter field
     */
    const COL_LIBRARY_TWITTER = 'libraries.library_twitter';

    /**
     * the column name for the library_creation_year field
     */
    const COL_LIBRARY_CREATION_YEAR = 'libraries.library_creation_year';

    /**
     * the column name for the library_specialities field
     */
    const COL_LIBRARY_SPECIALITIES = 'libraries.library_specialities';

    /**
     * the column name for the library_readings field
     */
    const COL_LIBRARY_READINGS = 'libraries.library_readings';

    /**
     * the column name for the library_desc field
     */
    const COL_LIBRARY_DESC = 'libraries.library_desc';

    /**
     * the column name for the library_created field
     */
    const COL_LIBRARY_CREATED = 'libraries.library_created';

    /**
     * the column name for the library_updated field
     */
    const COL_LIBRARY_UPDATED = 'libraries.library_updated';

    /**
     * the column name for the library_deleted field
     */
    const COL_LIBRARY_DELETED = 'libraries.library_deleted';

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
        self::TYPE_PHPNAME       => array('Id', 'Name', 'NameAlphabetic', 'Url', 'Representative', 'Address', 'PostalCode', 'City', 'Country', 'Phone', 'Fax', 'Website', 'Email', 'Facebook', 'Twitter', 'CreationYear', 'Specialities', 'Readings', 'Desc', 'CreatedAt', 'UpdatedAt', 'DeletedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'name', 'nameAlphabetic', 'url', 'representative', 'address', 'postalCode', 'city', 'country', 'phone', 'fax', 'website', 'email', 'facebook', 'twitter', 'creationYear', 'specialities', 'readings', 'desc', 'createdAt', 'updatedAt', 'deletedAt', ),
        self::TYPE_COLNAME       => array(LibraryTableMap::COL_LIBRARY_ID, LibraryTableMap::COL_LIBRARY_NAME, LibraryTableMap::COL_LIBRARY_NAME_ALPHABETIC, LibraryTableMap::COL_LIBRARY_URL, LibraryTableMap::COL_LIBRARY_REPRESENTATIVE, LibraryTableMap::COL_LIBRARY_ADDRESS, LibraryTableMap::COL_LIBRARY_POSTAL_CODE, LibraryTableMap::COL_LIBRARY_CITY, LibraryTableMap::COL_LIBRARY_COUNTRY, LibraryTableMap::COL_LIBRARY_PHONE, LibraryTableMap::COL_LIBRARY_FAX, LibraryTableMap::COL_LIBRARY_WEBSITE, LibraryTableMap::COL_LIBRARY_EMAIL, LibraryTableMap::COL_LIBRARY_FACEBOOK, LibraryTableMap::COL_LIBRARY_TWITTER, LibraryTableMap::COL_LIBRARY_CREATION_YEAR, LibraryTableMap::COL_LIBRARY_SPECIALITIES, LibraryTableMap::COL_LIBRARY_READINGS, LibraryTableMap::COL_LIBRARY_DESC, LibraryTableMap::COL_LIBRARY_CREATED, LibraryTableMap::COL_LIBRARY_UPDATED, LibraryTableMap::COL_LIBRARY_DELETED, ),
        self::TYPE_FIELDNAME     => array('library_id', 'library_name', 'library_name_alphabetic', 'library_url', 'library_representative', 'library_address', 'library_postal_code', 'library_city', 'library_country', 'library_phone', 'library_fax', 'library_website', 'library_email', 'library_facebook', 'library_twitter', 'library_creation_year', 'library_specialities', 'library_readings', 'library_desc', 'library_created', 'library_updated', 'library_deleted', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Name' => 1, 'NameAlphabetic' => 2, 'Url' => 3, 'Representative' => 4, 'Address' => 5, 'PostalCode' => 6, 'City' => 7, 'Country' => 8, 'Phone' => 9, 'Fax' => 10, 'Website' => 11, 'Email' => 12, 'Facebook' => 13, 'Twitter' => 14, 'CreationYear' => 15, 'Specialities' => 16, 'Readings' => 17, 'Desc' => 18, 'CreatedAt' => 19, 'UpdatedAt' => 20, 'DeletedAt' => 21, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'name' => 1, 'nameAlphabetic' => 2, 'url' => 3, 'representative' => 4, 'address' => 5, 'postalCode' => 6, 'city' => 7, 'country' => 8, 'phone' => 9, 'fax' => 10, 'website' => 11, 'email' => 12, 'facebook' => 13, 'twitter' => 14, 'creationYear' => 15, 'specialities' => 16, 'readings' => 17, 'desc' => 18, 'createdAt' => 19, 'updatedAt' => 20, 'deletedAt' => 21, ),
        self::TYPE_COLNAME       => array(LibraryTableMap::COL_LIBRARY_ID => 0, LibraryTableMap::COL_LIBRARY_NAME => 1, LibraryTableMap::COL_LIBRARY_NAME_ALPHABETIC => 2, LibraryTableMap::COL_LIBRARY_URL => 3, LibraryTableMap::COL_LIBRARY_REPRESENTATIVE => 4, LibraryTableMap::COL_LIBRARY_ADDRESS => 5, LibraryTableMap::COL_LIBRARY_POSTAL_CODE => 6, LibraryTableMap::COL_LIBRARY_CITY => 7, LibraryTableMap::COL_LIBRARY_COUNTRY => 8, LibraryTableMap::COL_LIBRARY_PHONE => 9, LibraryTableMap::COL_LIBRARY_FAX => 10, LibraryTableMap::COL_LIBRARY_WEBSITE => 11, LibraryTableMap::COL_LIBRARY_EMAIL => 12, LibraryTableMap::COL_LIBRARY_FACEBOOK => 13, LibraryTableMap::COL_LIBRARY_TWITTER => 14, LibraryTableMap::COL_LIBRARY_CREATION_YEAR => 15, LibraryTableMap::COL_LIBRARY_SPECIALITIES => 16, LibraryTableMap::COL_LIBRARY_READINGS => 17, LibraryTableMap::COL_LIBRARY_DESC => 18, LibraryTableMap::COL_LIBRARY_CREATED => 19, LibraryTableMap::COL_LIBRARY_UPDATED => 20, LibraryTableMap::COL_LIBRARY_DELETED => 21, ),
        self::TYPE_FIELDNAME     => array('library_id' => 0, 'library_name' => 1, 'library_name_alphabetic' => 2, 'library_url' => 3, 'library_representative' => 4, 'library_address' => 5, 'library_postal_code' => 6, 'library_city' => 7, 'library_country' => 8, 'library_phone' => 9, 'library_fax' => 10, 'library_website' => 11, 'library_email' => 12, 'library_facebook' => 13, 'library_twitter' => 14, 'library_creation_year' => 15, 'library_specialities' => 16, 'library_readings' => 17, 'library_desc' => 18, 'library_created' => 19, 'library_updated' => 20, 'library_deleted' => 21, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, )
    );

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var string[]
     */
    protected $normalizedColumnNameMap = [

        'Id' => 'LIBRARY_ID',
        'Library.Id' => 'LIBRARY_ID',
        'id' => 'LIBRARY_ID',
        'library.id' => 'LIBRARY_ID',
        'LibraryTableMap::COL_LIBRARY_ID' => 'LIBRARY_ID',
        'COL_LIBRARY_ID' => 'LIBRARY_ID',
        'library_id' => 'LIBRARY_ID',
        'libraries.library_id' => 'LIBRARY_ID',
        'Name' => 'LIBRARY_NAME',
        'Library.Name' => 'LIBRARY_NAME',
        'name' => 'LIBRARY_NAME',
        'library.name' => 'LIBRARY_NAME',
        'LibraryTableMap::COL_LIBRARY_NAME' => 'LIBRARY_NAME',
        'COL_LIBRARY_NAME' => 'LIBRARY_NAME',
        'library_name' => 'LIBRARY_NAME',
        'libraries.library_name' => 'LIBRARY_NAME',
        'NameAlphabetic' => 'LIBRARY_NAME_ALPHABETIC',
        'Library.NameAlphabetic' => 'LIBRARY_NAME_ALPHABETIC',
        'nameAlphabetic' => 'LIBRARY_NAME_ALPHABETIC',
        'library.nameAlphabetic' => 'LIBRARY_NAME_ALPHABETIC',
        'LibraryTableMap::COL_LIBRARY_NAME_ALPHABETIC' => 'LIBRARY_NAME_ALPHABETIC',
        'COL_LIBRARY_NAME_ALPHABETIC' => 'LIBRARY_NAME_ALPHABETIC',
        'library_name_alphabetic' => 'LIBRARY_NAME_ALPHABETIC',
        'libraries.library_name_alphabetic' => 'LIBRARY_NAME_ALPHABETIC',
        'Url' => 'LIBRARY_URL',
        'Library.Url' => 'LIBRARY_URL',
        'url' => 'LIBRARY_URL',
        'library.url' => 'LIBRARY_URL',
        'LibraryTableMap::COL_LIBRARY_URL' => 'LIBRARY_URL',
        'COL_LIBRARY_URL' => 'LIBRARY_URL',
        'library_url' => 'LIBRARY_URL',
        'libraries.library_url' => 'LIBRARY_URL',
        'Representative' => 'LIBRARY_REPRESENTATIVE',
        'Library.Representative' => 'LIBRARY_REPRESENTATIVE',
        'representative' => 'LIBRARY_REPRESENTATIVE',
        'library.representative' => 'LIBRARY_REPRESENTATIVE',
        'LibraryTableMap::COL_LIBRARY_REPRESENTATIVE' => 'LIBRARY_REPRESENTATIVE',
        'COL_LIBRARY_REPRESENTATIVE' => 'LIBRARY_REPRESENTATIVE',
        'library_representative' => 'LIBRARY_REPRESENTATIVE',
        'libraries.library_representative' => 'LIBRARY_REPRESENTATIVE',
        'Address' => 'LIBRARY_ADDRESS',
        'Library.Address' => 'LIBRARY_ADDRESS',
        'address' => 'LIBRARY_ADDRESS',
        'library.address' => 'LIBRARY_ADDRESS',
        'LibraryTableMap::COL_LIBRARY_ADDRESS' => 'LIBRARY_ADDRESS',
        'COL_LIBRARY_ADDRESS' => 'LIBRARY_ADDRESS',
        'library_address' => 'LIBRARY_ADDRESS',
        'libraries.library_address' => 'LIBRARY_ADDRESS',
        'PostalCode' => 'LIBRARY_POSTAL_CODE',
        'Library.PostalCode' => 'LIBRARY_POSTAL_CODE',
        'postalCode' => 'LIBRARY_POSTAL_CODE',
        'library.postalCode' => 'LIBRARY_POSTAL_CODE',
        'LibraryTableMap::COL_LIBRARY_POSTAL_CODE' => 'LIBRARY_POSTAL_CODE',
        'COL_LIBRARY_POSTAL_CODE' => 'LIBRARY_POSTAL_CODE',
        'library_postal_code' => 'LIBRARY_POSTAL_CODE',
        'libraries.library_postal_code' => 'LIBRARY_POSTAL_CODE',
        'City' => 'LIBRARY_CITY',
        'Library.City' => 'LIBRARY_CITY',
        'city' => 'LIBRARY_CITY',
        'library.city' => 'LIBRARY_CITY',
        'LibraryTableMap::COL_LIBRARY_CITY' => 'LIBRARY_CITY',
        'COL_LIBRARY_CITY' => 'LIBRARY_CITY',
        'library_city' => 'LIBRARY_CITY',
        'libraries.library_city' => 'LIBRARY_CITY',
        'Country' => 'LIBRARY_COUNTRY',
        'Library.Country' => 'LIBRARY_COUNTRY',
        'country' => 'LIBRARY_COUNTRY',
        'library.country' => 'LIBRARY_COUNTRY',
        'LibraryTableMap::COL_LIBRARY_COUNTRY' => 'LIBRARY_COUNTRY',
        'COL_LIBRARY_COUNTRY' => 'LIBRARY_COUNTRY',
        'library_country' => 'LIBRARY_COUNTRY',
        'libraries.library_country' => 'LIBRARY_COUNTRY',
        'Phone' => 'LIBRARY_PHONE',
        'Library.Phone' => 'LIBRARY_PHONE',
        'phone' => 'LIBRARY_PHONE',
        'library.phone' => 'LIBRARY_PHONE',
        'LibraryTableMap::COL_LIBRARY_PHONE' => 'LIBRARY_PHONE',
        'COL_LIBRARY_PHONE' => 'LIBRARY_PHONE',
        'library_phone' => 'LIBRARY_PHONE',
        'libraries.library_phone' => 'LIBRARY_PHONE',
        'Fax' => 'LIBRARY_FAX',
        'Library.Fax' => 'LIBRARY_FAX',
        'fax' => 'LIBRARY_FAX',
        'library.fax' => 'LIBRARY_FAX',
        'LibraryTableMap::COL_LIBRARY_FAX' => 'LIBRARY_FAX',
        'COL_LIBRARY_FAX' => 'LIBRARY_FAX',
        'library_fax' => 'LIBRARY_FAX',
        'libraries.library_fax' => 'LIBRARY_FAX',
        'Website' => 'LIBRARY_WEBSITE',
        'Library.Website' => 'LIBRARY_WEBSITE',
        'website' => 'LIBRARY_WEBSITE',
        'library.website' => 'LIBRARY_WEBSITE',
        'LibraryTableMap::COL_LIBRARY_WEBSITE' => 'LIBRARY_WEBSITE',
        'COL_LIBRARY_WEBSITE' => 'LIBRARY_WEBSITE',
        'library_website' => 'LIBRARY_WEBSITE',
        'libraries.library_website' => 'LIBRARY_WEBSITE',
        'Email' => 'LIBRARY_EMAIL',
        'Library.Email' => 'LIBRARY_EMAIL',
        'email' => 'LIBRARY_EMAIL',
        'library.email' => 'LIBRARY_EMAIL',
        'LibraryTableMap::COL_LIBRARY_EMAIL' => 'LIBRARY_EMAIL',
        'COL_LIBRARY_EMAIL' => 'LIBRARY_EMAIL',
        'library_email' => 'LIBRARY_EMAIL',
        'libraries.library_email' => 'LIBRARY_EMAIL',
        'Facebook' => 'LIBRARY_FACEBOOK',
        'Library.Facebook' => 'LIBRARY_FACEBOOK',
        'facebook' => 'LIBRARY_FACEBOOK',
        'library.facebook' => 'LIBRARY_FACEBOOK',
        'LibraryTableMap::COL_LIBRARY_FACEBOOK' => 'LIBRARY_FACEBOOK',
        'COL_LIBRARY_FACEBOOK' => 'LIBRARY_FACEBOOK',
        'library_facebook' => 'LIBRARY_FACEBOOK',
        'libraries.library_facebook' => 'LIBRARY_FACEBOOK',
        'Twitter' => 'LIBRARY_TWITTER',
        'Library.Twitter' => 'LIBRARY_TWITTER',
        'twitter' => 'LIBRARY_TWITTER',
        'library.twitter' => 'LIBRARY_TWITTER',
        'LibraryTableMap::COL_LIBRARY_TWITTER' => 'LIBRARY_TWITTER',
        'COL_LIBRARY_TWITTER' => 'LIBRARY_TWITTER',
        'library_twitter' => 'LIBRARY_TWITTER',
        'libraries.library_twitter' => 'LIBRARY_TWITTER',
        'CreationYear' => 'LIBRARY_CREATION_YEAR',
        'Library.CreationYear' => 'LIBRARY_CREATION_YEAR',
        'creationYear' => 'LIBRARY_CREATION_YEAR',
        'library.creationYear' => 'LIBRARY_CREATION_YEAR',
        'LibraryTableMap::COL_LIBRARY_CREATION_YEAR' => 'LIBRARY_CREATION_YEAR',
        'COL_LIBRARY_CREATION_YEAR' => 'LIBRARY_CREATION_YEAR',
        'library_creation_year' => 'LIBRARY_CREATION_YEAR',
        'libraries.library_creation_year' => 'LIBRARY_CREATION_YEAR',
        'Specialities' => 'LIBRARY_SPECIALITIES',
        'Library.Specialities' => 'LIBRARY_SPECIALITIES',
        'specialities' => 'LIBRARY_SPECIALITIES',
        'library.specialities' => 'LIBRARY_SPECIALITIES',
        'LibraryTableMap::COL_LIBRARY_SPECIALITIES' => 'LIBRARY_SPECIALITIES',
        'COL_LIBRARY_SPECIALITIES' => 'LIBRARY_SPECIALITIES',
        'library_specialities' => 'LIBRARY_SPECIALITIES',
        'libraries.library_specialities' => 'LIBRARY_SPECIALITIES',
        'Readings' => 'LIBRARY_READINGS',
        'Library.Readings' => 'LIBRARY_READINGS',
        'readings' => 'LIBRARY_READINGS',
        'library.readings' => 'LIBRARY_READINGS',
        'LibraryTableMap::COL_LIBRARY_READINGS' => 'LIBRARY_READINGS',
        'COL_LIBRARY_READINGS' => 'LIBRARY_READINGS',
        'library_readings' => 'LIBRARY_READINGS',
        'libraries.library_readings' => 'LIBRARY_READINGS',
        'Desc' => 'LIBRARY_DESC',
        'Library.Desc' => 'LIBRARY_DESC',
        'desc' => 'LIBRARY_DESC',
        'library.desc' => 'LIBRARY_DESC',
        'LibraryTableMap::COL_LIBRARY_DESC' => 'LIBRARY_DESC',
        'COL_LIBRARY_DESC' => 'LIBRARY_DESC',
        'library_desc' => 'LIBRARY_DESC',
        'libraries.library_desc' => 'LIBRARY_DESC',
        'CreatedAt' => 'LIBRARY_CREATED',
        'Library.CreatedAt' => 'LIBRARY_CREATED',
        'createdAt' => 'LIBRARY_CREATED',
        'library.createdAt' => 'LIBRARY_CREATED',
        'LibraryTableMap::COL_LIBRARY_CREATED' => 'LIBRARY_CREATED',
        'COL_LIBRARY_CREATED' => 'LIBRARY_CREATED',
        'library_created' => 'LIBRARY_CREATED',
        'libraries.library_created' => 'LIBRARY_CREATED',
        'UpdatedAt' => 'LIBRARY_UPDATED',
        'Library.UpdatedAt' => 'LIBRARY_UPDATED',
        'updatedAt' => 'LIBRARY_UPDATED',
        'library.updatedAt' => 'LIBRARY_UPDATED',
        'LibraryTableMap::COL_LIBRARY_UPDATED' => 'LIBRARY_UPDATED',
        'COL_LIBRARY_UPDATED' => 'LIBRARY_UPDATED',
        'library_updated' => 'LIBRARY_UPDATED',
        'libraries.library_updated' => 'LIBRARY_UPDATED',
        'DeletedAt' => 'LIBRARY_DELETED',
        'Library.DeletedAt' => 'LIBRARY_DELETED',
        'deletedAt' => 'LIBRARY_DELETED',
        'library.deletedAt' => 'LIBRARY_DELETED',
        'LibraryTableMap::COL_LIBRARY_DELETED' => 'LIBRARY_DELETED',
        'COL_LIBRARY_DELETED' => 'LIBRARY_DELETED',
        'library_deleted' => 'LIBRARY_DELETED',
        'libraries.library_deleted' => 'LIBRARY_DELETED',
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
        $this->setName('libraries');
        $this->setPhpName('Library');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Library');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('library_id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('library_name', 'Name', 'VARCHAR', false, 255, null);
        $this->addColumn('library_name_alphabetic', 'NameAlphabetic', 'VARCHAR', false, 256, null);
        $this->addColumn('library_url', 'Url', 'VARCHAR', false, 128, null);
        $this->addColumn('library_representative', 'Representative', 'VARCHAR', false, 256, null);
        $this->addColumn('library_address', 'Address', 'VARCHAR', false, 256, null);
        $this->addColumn('library_postal_code', 'PostalCode', 'VARCHAR', false, 8, null);
        $this->addColumn('library_city', 'City', 'VARCHAR', false, 128, null);
        $this->addColumn('library_country', 'Country', 'VARCHAR', false, 128, null);
        $this->addColumn('library_phone', 'Phone', 'VARCHAR', false, 16, null);
        $this->addColumn('library_fax', 'Fax', 'VARCHAR', false, 16, null);
        $this->addColumn('library_website', 'Website', 'VARCHAR', false, 128, null);
        $this->addColumn('library_email', 'Email', 'VARCHAR', false, 128, null);
        $this->addColumn('library_facebook', 'Facebook', 'VARCHAR', false, 128, null);
        $this->addColumn('library_twitter', 'Twitter', 'VARCHAR', false, 15, null);
        $this->addColumn('library_creation_year', 'CreationYear', 'VARCHAR', false, 4, null);
        $this->addColumn('library_specialities', 'Specialities', 'VARCHAR', false, 256, null);
        $this->addColumn('library_readings', 'Readings', 'VARCHAR', false, 512, null);
        $this->addColumn('library_desc', 'Desc', 'LONGVARCHAR', false, null, null);
        $this->addColumn('library_created', 'CreatedAt', 'TIMESTAMP', false, null, 'CURRENT_TIMESTAMP');
        $this->addColumn('library_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('library_deleted', 'DeletedAt', 'TIMESTAMP', false, null, null);
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
            'timestampable' => array('create_column' => 'library_created', 'update_column' => 'library_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false', ),
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
        return $withPrefix ? LibraryTableMap::CLASS_DEFAULT : LibraryTableMap::OM_CLASS;
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
     * @return array           (Library object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = LibraryTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = LibraryTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + LibraryTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = LibraryTableMap::OM_CLASS;
            /** @var Library $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            LibraryTableMap::addInstanceToPool($obj, $key);
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
            $key = LibraryTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = LibraryTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Library $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                LibraryTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(LibraryTableMap::COL_LIBRARY_ID);
            $criteria->addSelectColumn(LibraryTableMap::COL_LIBRARY_NAME);
            $criteria->addSelectColumn(LibraryTableMap::COL_LIBRARY_NAME_ALPHABETIC);
            $criteria->addSelectColumn(LibraryTableMap::COL_LIBRARY_URL);
            $criteria->addSelectColumn(LibraryTableMap::COL_LIBRARY_REPRESENTATIVE);
            $criteria->addSelectColumn(LibraryTableMap::COL_LIBRARY_ADDRESS);
            $criteria->addSelectColumn(LibraryTableMap::COL_LIBRARY_POSTAL_CODE);
            $criteria->addSelectColumn(LibraryTableMap::COL_LIBRARY_CITY);
            $criteria->addSelectColumn(LibraryTableMap::COL_LIBRARY_COUNTRY);
            $criteria->addSelectColumn(LibraryTableMap::COL_LIBRARY_PHONE);
            $criteria->addSelectColumn(LibraryTableMap::COL_LIBRARY_FAX);
            $criteria->addSelectColumn(LibraryTableMap::COL_LIBRARY_WEBSITE);
            $criteria->addSelectColumn(LibraryTableMap::COL_LIBRARY_EMAIL);
            $criteria->addSelectColumn(LibraryTableMap::COL_LIBRARY_FACEBOOK);
            $criteria->addSelectColumn(LibraryTableMap::COL_LIBRARY_TWITTER);
            $criteria->addSelectColumn(LibraryTableMap::COL_LIBRARY_CREATION_YEAR);
            $criteria->addSelectColumn(LibraryTableMap::COL_LIBRARY_SPECIALITIES);
            $criteria->addSelectColumn(LibraryTableMap::COL_LIBRARY_READINGS);
            $criteria->addSelectColumn(LibraryTableMap::COL_LIBRARY_DESC);
            $criteria->addSelectColumn(LibraryTableMap::COL_LIBRARY_CREATED);
            $criteria->addSelectColumn(LibraryTableMap::COL_LIBRARY_UPDATED);
            $criteria->addSelectColumn(LibraryTableMap::COL_LIBRARY_DELETED);
        } else {
            $criteria->addSelectColumn($alias . '.library_id');
            $criteria->addSelectColumn($alias . '.library_name');
            $criteria->addSelectColumn($alias . '.library_name_alphabetic');
            $criteria->addSelectColumn($alias . '.library_url');
            $criteria->addSelectColumn($alias . '.library_representative');
            $criteria->addSelectColumn($alias . '.library_address');
            $criteria->addSelectColumn($alias . '.library_postal_code');
            $criteria->addSelectColumn($alias . '.library_city');
            $criteria->addSelectColumn($alias . '.library_country');
            $criteria->addSelectColumn($alias . '.library_phone');
            $criteria->addSelectColumn($alias . '.library_fax');
            $criteria->addSelectColumn($alias . '.library_website');
            $criteria->addSelectColumn($alias . '.library_email');
            $criteria->addSelectColumn($alias . '.library_facebook');
            $criteria->addSelectColumn($alias . '.library_twitter');
            $criteria->addSelectColumn($alias . '.library_creation_year');
            $criteria->addSelectColumn($alias . '.library_specialities');
            $criteria->addSelectColumn($alias . '.library_readings');
            $criteria->addSelectColumn($alias . '.library_desc');
            $criteria->addSelectColumn($alias . '.library_created');
            $criteria->addSelectColumn($alias . '.library_updated');
            $criteria->addSelectColumn($alias . '.library_deleted');
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
            $criteria->removeSelectColumn(LibraryTableMap::COL_LIBRARY_ID);
            $criteria->removeSelectColumn(LibraryTableMap::COL_LIBRARY_NAME);
            $criteria->removeSelectColumn(LibraryTableMap::COL_LIBRARY_NAME_ALPHABETIC);
            $criteria->removeSelectColumn(LibraryTableMap::COL_LIBRARY_URL);
            $criteria->removeSelectColumn(LibraryTableMap::COL_LIBRARY_REPRESENTATIVE);
            $criteria->removeSelectColumn(LibraryTableMap::COL_LIBRARY_ADDRESS);
            $criteria->removeSelectColumn(LibraryTableMap::COL_LIBRARY_POSTAL_CODE);
            $criteria->removeSelectColumn(LibraryTableMap::COL_LIBRARY_CITY);
            $criteria->removeSelectColumn(LibraryTableMap::COL_LIBRARY_COUNTRY);
            $criteria->removeSelectColumn(LibraryTableMap::COL_LIBRARY_PHONE);
            $criteria->removeSelectColumn(LibraryTableMap::COL_LIBRARY_FAX);
            $criteria->removeSelectColumn(LibraryTableMap::COL_LIBRARY_WEBSITE);
            $criteria->removeSelectColumn(LibraryTableMap::COL_LIBRARY_EMAIL);
            $criteria->removeSelectColumn(LibraryTableMap::COL_LIBRARY_FACEBOOK);
            $criteria->removeSelectColumn(LibraryTableMap::COL_LIBRARY_TWITTER);
            $criteria->removeSelectColumn(LibraryTableMap::COL_LIBRARY_CREATION_YEAR);
            $criteria->removeSelectColumn(LibraryTableMap::COL_LIBRARY_SPECIALITIES);
            $criteria->removeSelectColumn(LibraryTableMap::COL_LIBRARY_READINGS);
            $criteria->removeSelectColumn(LibraryTableMap::COL_LIBRARY_DESC);
            $criteria->removeSelectColumn(LibraryTableMap::COL_LIBRARY_CREATED);
            $criteria->removeSelectColumn(LibraryTableMap::COL_LIBRARY_UPDATED);
            $criteria->removeSelectColumn(LibraryTableMap::COL_LIBRARY_DELETED);
        } else {
            $criteria->removeSelectColumn($alias . '.library_id');
            $criteria->removeSelectColumn($alias . '.library_name');
            $criteria->removeSelectColumn($alias . '.library_name_alphabetic');
            $criteria->removeSelectColumn($alias . '.library_url');
            $criteria->removeSelectColumn($alias . '.library_representative');
            $criteria->removeSelectColumn($alias . '.library_address');
            $criteria->removeSelectColumn($alias . '.library_postal_code');
            $criteria->removeSelectColumn($alias . '.library_city');
            $criteria->removeSelectColumn($alias . '.library_country');
            $criteria->removeSelectColumn($alias . '.library_phone');
            $criteria->removeSelectColumn($alias . '.library_fax');
            $criteria->removeSelectColumn($alias . '.library_website');
            $criteria->removeSelectColumn($alias . '.library_email');
            $criteria->removeSelectColumn($alias . '.library_facebook');
            $criteria->removeSelectColumn($alias . '.library_twitter');
            $criteria->removeSelectColumn($alias . '.library_creation_year');
            $criteria->removeSelectColumn($alias . '.library_specialities');
            $criteria->removeSelectColumn($alias . '.library_readings');
            $criteria->removeSelectColumn($alias . '.library_desc');
            $criteria->removeSelectColumn($alias . '.library_created');
            $criteria->removeSelectColumn($alias . '.library_updated');
            $criteria->removeSelectColumn($alias . '.library_deleted');
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
        return Propel::getServiceContainer()->getDatabaseMap(LibraryTableMap::DATABASE_NAME)->getTable(LibraryTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(LibraryTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(LibraryTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new LibraryTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Library or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Library object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(LibraryTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Library) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(LibraryTableMap::DATABASE_NAME);
            $criteria->add(LibraryTableMap::COL_LIBRARY_ID, (array) $values, Criteria::IN);
        }

        $query = LibraryQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            LibraryTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                LibraryTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the libraries table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return LibraryQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Library or Criteria object.
     *
     * @param mixed               $criteria Criteria or Library object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LibraryTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Library object
        }

        if ($criteria->containsKey(LibraryTableMap::COL_LIBRARY_ID) && $criteria->keyContainsValue(LibraryTableMap::COL_LIBRARY_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.LibraryTableMap::COL_LIBRARY_ID.')');
        }


        // Set the correct dbName
        $query = LibraryQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // LibraryTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
LibraryTableMap::buildTableMap();
