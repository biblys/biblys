<?php

namespace Model\Map;

use Model\People;
use Model\PeopleQuery;
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
 * This class defines the structure of the 'people' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class PeopleTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.PeopleTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'people';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\People';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.People';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 23;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 23;

    /**
     * the column name for the people_id field
     */
    public const COL_PEOPLE_ID = 'people.people_id';

    /**
     * the column name for the people_first_name field
     */
    public const COL_PEOPLE_FIRST_NAME = 'people.people_first_name';

    /**
     * the column name for the people_last_name field
     */
    public const COL_PEOPLE_LAST_NAME = 'people.people_last_name';

    /**
     * the column name for the people_name field
     */
    public const COL_PEOPLE_NAME = 'people.people_name';

    /**
     * the column name for the people_alpha field
     */
    public const COL_PEOPLE_ALPHA = 'people.people_alpha';

    /**
     * the column name for the people_url_old field
     */
    public const COL_PEOPLE_URL_OLD = 'people.people_url_old';

    /**
     * the column name for the people_url field
     */
    public const COL_PEOPLE_URL = 'people.people_url';

    /**
     * the column name for the people_pseudo field
     */
    public const COL_PEOPLE_PSEUDO = 'people.people_pseudo';

    /**
     * the column name for the people_noosfere_id field
     */
    public const COL_PEOPLE_NOOSFERE_ID = 'people.people_noosfere_id';

    /**
     * the column name for the people_birth field
     */
    public const COL_PEOPLE_BIRTH = 'people.people_birth';

    /**
     * the column name for the people_death field
     */
    public const COL_PEOPLE_DEATH = 'people.people_death';

    /**
     * the column name for the people_gender field
     */
    public const COL_PEOPLE_GENDER = 'people.people_gender';

    /**
     * the column name for the people_nation field
     */
    public const COL_PEOPLE_NATION = 'people.people_nation';

    /**
     * the column name for the people_bio field
     */
    public const COL_PEOPLE_BIO = 'people.people_bio';

    /**
     * the column name for the people_site field
     */
    public const COL_PEOPLE_SITE = 'people.people_site';

    /**
     * the column name for the people_facebook field
     */
    public const COL_PEOPLE_FACEBOOK = 'people.people_facebook';

    /**
     * the column name for the people_twitter field
     */
    public const COL_PEOPLE_TWITTER = 'people.people_twitter';

    /**
     * the column name for the people_hits field
     */
    public const COL_PEOPLE_HITS = 'people.people_hits';

    /**
     * the column name for the people_date field
     */
    public const COL_PEOPLE_DATE = 'people.people_date';

    /**
     * the column name for the people_insert field
     */
    public const COL_PEOPLE_INSERT = 'people.people_insert';

    /**
     * the column name for the people_update field
     */
    public const COL_PEOPLE_UPDATE = 'people.people_update';

    /**
     * the column name for the people_created field
     */
    public const COL_PEOPLE_CREATED = 'people.people_created';

    /**
     * the column name for the people_updated field
     */
    public const COL_PEOPLE_UPDATED = 'people.people_updated';

    /**
     * The default string format for model objects of the related table
     */
    public const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     *
     * @var array<string, mixed>
     */
    protected static $fieldNames = [
        self::TYPE_PHPNAME       => ['Id', 'FirstName', 'LastName', 'Name', 'Alpha', 'UrlOld', 'Url', 'Pseudo', 'NoosfereId', 'Birth', 'Death', 'Gender', 'Nation', 'Bio', 'Site', 'Facebook', 'Twitter', 'Hits', 'Date', 'Insert', 'Update', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'firstName', 'lastName', 'name', 'alpha', 'urlOld', 'url', 'pseudo', 'noosfereId', 'birth', 'death', 'gender', 'nation', 'bio', 'site', 'facebook', 'twitter', 'hits', 'date', 'insert', 'update', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [PeopleTableMap::COL_PEOPLE_ID, PeopleTableMap::COL_PEOPLE_FIRST_NAME, PeopleTableMap::COL_PEOPLE_LAST_NAME, PeopleTableMap::COL_PEOPLE_NAME, PeopleTableMap::COL_PEOPLE_ALPHA, PeopleTableMap::COL_PEOPLE_URL_OLD, PeopleTableMap::COL_PEOPLE_URL, PeopleTableMap::COL_PEOPLE_PSEUDO, PeopleTableMap::COL_PEOPLE_NOOSFERE_ID, PeopleTableMap::COL_PEOPLE_BIRTH, PeopleTableMap::COL_PEOPLE_DEATH, PeopleTableMap::COL_PEOPLE_GENDER, PeopleTableMap::COL_PEOPLE_NATION, PeopleTableMap::COL_PEOPLE_BIO, PeopleTableMap::COL_PEOPLE_SITE, PeopleTableMap::COL_PEOPLE_FACEBOOK, PeopleTableMap::COL_PEOPLE_TWITTER, PeopleTableMap::COL_PEOPLE_HITS, PeopleTableMap::COL_PEOPLE_DATE, PeopleTableMap::COL_PEOPLE_INSERT, PeopleTableMap::COL_PEOPLE_UPDATE, PeopleTableMap::COL_PEOPLE_CREATED, PeopleTableMap::COL_PEOPLE_UPDATED, ],
        self::TYPE_FIELDNAME     => ['people_id', 'people_first_name', 'people_last_name', 'people_name', 'people_alpha', 'people_url_old', 'people_url', 'people_pseudo', 'people_noosfere_id', 'people_birth', 'people_death', 'people_gender', 'people_nation', 'people_bio', 'people_site', 'people_facebook', 'people_twitter', 'people_hits', 'people_date', 'people_insert', 'people_update', 'people_created', 'people_updated', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, ]
    ];

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     *
     * @var array<string, mixed>
     */
    protected static $fieldKeys = [
        self::TYPE_PHPNAME       => ['Id' => 0, 'FirstName' => 1, 'LastName' => 2, 'Name' => 3, 'Alpha' => 4, 'UrlOld' => 5, 'Url' => 6, 'Pseudo' => 7, 'NoosfereId' => 8, 'Birth' => 9, 'Death' => 10, 'Gender' => 11, 'Nation' => 12, 'Bio' => 13, 'Site' => 14, 'Facebook' => 15, 'Twitter' => 16, 'Hits' => 17, 'Date' => 18, 'Insert' => 19, 'Update' => 20, 'CreatedAt' => 21, 'UpdatedAt' => 22, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'firstName' => 1, 'lastName' => 2, 'name' => 3, 'alpha' => 4, 'urlOld' => 5, 'url' => 6, 'pseudo' => 7, 'noosfereId' => 8, 'birth' => 9, 'death' => 10, 'gender' => 11, 'nation' => 12, 'bio' => 13, 'site' => 14, 'facebook' => 15, 'twitter' => 16, 'hits' => 17, 'date' => 18, 'insert' => 19, 'update' => 20, 'createdAt' => 21, 'updatedAt' => 22, ],
        self::TYPE_COLNAME       => [PeopleTableMap::COL_PEOPLE_ID => 0, PeopleTableMap::COL_PEOPLE_FIRST_NAME => 1, PeopleTableMap::COL_PEOPLE_LAST_NAME => 2, PeopleTableMap::COL_PEOPLE_NAME => 3, PeopleTableMap::COL_PEOPLE_ALPHA => 4, PeopleTableMap::COL_PEOPLE_URL_OLD => 5, PeopleTableMap::COL_PEOPLE_URL => 6, PeopleTableMap::COL_PEOPLE_PSEUDO => 7, PeopleTableMap::COL_PEOPLE_NOOSFERE_ID => 8, PeopleTableMap::COL_PEOPLE_BIRTH => 9, PeopleTableMap::COL_PEOPLE_DEATH => 10, PeopleTableMap::COL_PEOPLE_GENDER => 11, PeopleTableMap::COL_PEOPLE_NATION => 12, PeopleTableMap::COL_PEOPLE_BIO => 13, PeopleTableMap::COL_PEOPLE_SITE => 14, PeopleTableMap::COL_PEOPLE_FACEBOOK => 15, PeopleTableMap::COL_PEOPLE_TWITTER => 16, PeopleTableMap::COL_PEOPLE_HITS => 17, PeopleTableMap::COL_PEOPLE_DATE => 18, PeopleTableMap::COL_PEOPLE_INSERT => 19, PeopleTableMap::COL_PEOPLE_UPDATE => 20, PeopleTableMap::COL_PEOPLE_CREATED => 21, PeopleTableMap::COL_PEOPLE_UPDATED => 22, ],
        self::TYPE_FIELDNAME     => ['people_id' => 0, 'people_first_name' => 1, 'people_last_name' => 2, 'people_name' => 3, 'people_alpha' => 4, 'people_url_old' => 5, 'people_url' => 6, 'people_pseudo' => 7, 'people_noosfere_id' => 8, 'people_birth' => 9, 'people_death' => 10, 'people_gender' => 11, 'people_nation' => 12, 'people_bio' => 13, 'people_site' => 14, 'people_facebook' => 15, 'people_twitter' => 16, 'people_hits' => 17, 'people_date' => 18, 'people_insert' => 19, 'people_update' => 20, 'people_created' => 21, 'people_updated' => 22, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'PEOPLE_ID',
        'People.Id' => 'PEOPLE_ID',
        'id' => 'PEOPLE_ID',
        'people.id' => 'PEOPLE_ID',
        'PeopleTableMap::COL_PEOPLE_ID' => 'PEOPLE_ID',
        'COL_PEOPLE_ID' => 'PEOPLE_ID',
        'people_id' => 'PEOPLE_ID',
        'people.people_id' => 'PEOPLE_ID',
        'FirstName' => 'PEOPLE_FIRST_NAME',
        'People.FirstName' => 'PEOPLE_FIRST_NAME',
        'firstName' => 'PEOPLE_FIRST_NAME',
        'people.firstName' => 'PEOPLE_FIRST_NAME',
        'PeopleTableMap::COL_PEOPLE_FIRST_NAME' => 'PEOPLE_FIRST_NAME',
        'COL_PEOPLE_FIRST_NAME' => 'PEOPLE_FIRST_NAME',
        'people_first_name' => 'PEOPLE_FIRST_NAME',
        'people.people_first_name' => 'PEOPLE_FIRST_NAME',
        'LastName' => 'PEOPLE_LAST_NAME',
        'People.LastName' => 'PEOPLE_LAST_NAME',
        'lastName' => 'PEOPLE_LAST_NAME',
        'people.lastName' => 'PEOPLE_LAST_NAME',
        'PeopleTableMap::COL_PEOPLE_LAST_NAME' => 'PEOPLE_LAST_NAME',
        'COL_PEOPLE_LAST_NAME' => 'PEOPLE_LAST_NAME',
        'people_last_name' => 'PEOPLE_LAST_NAME',
        'people.people_last_name' => 'PEOPLE_LAST_NAME',
        'Name' => 'PEOPLE_NAME',
        'People.Name' => 'PEOPLE_NAME',
        'name' => 'PEOPLE_NAME',
        'people.name' => 'PEOPLE_NAME',
        'PeopleTableMap::COL_PEOPLE_NAME' => 'PEOPLE_NAME',
        'COL_PEOPLE_NAME' => 'PEOPLE_NAME',
        'people_name' => 'PEOPLE_NAME',
        'people.people_name' => 'PEOPLE_NAME',
        'Alpha' => 'PEOPLE_ALPHA',
        'People.Alpha' => 'PEOPLE_ALPHA',
        'alpha' => 'PEOPLE_ALPHA',
        'people.alpha' => 'PEOPLE_ALPHA',
        'PeopleTableMap::COL_PEOPLE_ALPHA' => 'PEOPLE_ALPHA',
        'COL_PEOPLE_ALPHA' => 'PEOPLE_ALPHA',
        'people_alpha' => 'PEOPLE_ALPHA',
        'people.people_alpha' => 'PEOPLE_ALPHA',
        'UrlOld' => 'PEOPLE_URL_OLD',
        'People.UrlOld' => 'PEOPLE_URL_OLD',
        'urlOld' => 'PEOPLE_URL_OLD',
        'people.urlOld' => 'PEOPLE_URL_OLD',
        'PeopleTableMap::COL_PEOPLE_URL_OLD' => 'PEOPLE_URL_OLD',
        'COL_PEOPLE_URL_OLD' => 'PEOPLE_URL_OLD',
        'people_url_old' => 'PEOPLE_URL_OLD',
        'people.people_url_old' => 'PEOPLE_URL_OLD',
        'Url' => 'PEOPLE_URL',
        'People.Url' => 'PEOPLE_URL',
        'url' => 'PEOPLE_URL',
        'people.url' => 'PEOPLE_URL',
        'PeopleTableMap::COL_PEOPLE_URL' => 'PEOPLE_URL',
        'COL_PEOPLE_URL' => 'PEOPLE_URL',
        'people_url' => 'PEOPLE_URL',
        'people.people_url' => 'PEOPLE_URL',
        'Pseudo' => 'PEOPLE_PSEUDO',
        'People.Pseudo' => 'PEOPLE_PSEUDO',
        'pseudo' => 'PEOPLE_PSEUDO',
        'people.pseudo' => 'PEOPLE_PSEUDO',
        'PeopleTableMap::COL_PEOPLE_PSEUDO' => 'PEOPLE_PSEUDO',
        'COL_PEOPLE_PSEUDO' => 'PEOPLE_PSEUDO',
        'people_pseudo' => 'PEOPLE_PSEUDO',
        'people.people_pseudo' => 'PEOPLE_PSEUDO',
        'NoosfereId' => 'PEOPLE_NOOSFERE_ID',
        'People.NoosfereId' => 'PEOPLE_NOOSFERE_ID',
        'noosfereId' => 'PEOPLE_NOOSFERE_ID',
        'people.noosfereId' => 'PEOPLE_NOOSFERE_ID',
        'PeopleTableMap::COL_PEOPLE_NOOSFERE_ID' => 'PEOPLE_NOOSFERE_ID',
        'COL_PEOPLE_NOOSFERE_ID' => 'PEOPLE_NOOSFERE_ID',
        'people_noosfere_id' => 'PEOPLE_NOOSFERE_ID',
        'people.people_noosfere_id' => 'PEOPLE_NOOSFERE_ID',
        'Birth' => 'PEOPLE_BIRTH',
        'People.Birth' => 'PEOPLE_BIRTH',
        'birth' => 'PEOPLE_BIRTH',
        'people.birth' => 'PEOPLE_BIRTH',
        'PeopleTableMap::COL_PEOPLE_BIRTH' => 'PEOPLE_BIRTH',
        'COL_PEOPLE_BIRTH' => 'PEOPLE_BIRTH',
        'people_birth' => 'PEOPLE_BIRTH',
        'people.people_birth' => 'PEOPLE_BIRTH',
        'Death' => 'PEOPLE_DEATH',
        'People.Death' => 'PEOPLE_DEATH',
        'death' => 'PEOPLE_DEATH',
        'people.death' => 'PEOPLE_DEATH',
        'PeopleTableMap::COL_PEOPLE_DEATH' => 'PEOPLE_DEATH',
        'COL_PEOPLE_DEATH' => 'PEOPLE_DEATH',
        'people_death' => 'PEOPLE_DEATH',
        'people.people_death' => 'PEOPLE_DEATH',
        'Gender' => 'PEOPLE_GENDER',
        'People.Gender' => 'PEOPLE_GENDER',
        'gender' => 'PEOPLE_GENDER',
        'people.gender' => 'PEOPLE_GENDER',
        'PeopleTableMap::COL_PEOPLE_GENDER' => 'PEOPLE_GENDER',
        'COL_PEOPLE_GENDER' => 'PEOPLE_GENDER',
        'people_gender' => 'PEOPLE_GENDER',
        'people.people_gender' => 'PEOPLE_GENDER',
        'Nation' => 'PEOPLE_NATION',
        'People.Nation' => 'PEOPLE_NATION',
        'nation' => 'PEOPLE_NATION',
        'people.nation' => 'PEOPLE_NATION',
        'PeopleTableMap::COL_PEOPLE_NATION' => 'PEOPLE_NATION',
        'COL_PEOPLE_NATION' => 'PEOPLE_NATION',
        'people_nation' => 'PEOPLE_NATION',
        'people.people_nation' => 'PEOPLE_NATION',
        'Bio' => 'PEOPLE_BIO',
        'People.Bio' => 'PEOPLE_BIO',
        'bio' => 'PEOPLE_BIO',
        'people.bio' => 'PEOPLE_BIO',
        'PeopleTableMap::COL_PEOPLE_BIO' => 'PEOPLE_BIO',
        'COL_PEOPLE_BIO' => 'PEOPLE_BIO',
        'people_bio' => 'PEOPLE_BIO',
        'people.people_bio' => 'PEOPLE_BIO',
        'Site' => 'PEOPLE_SITE',
        'People.Site' => 'PEOPLE_SITE',
        'site' => 'PEOPLE_SITE',
        'people.site' => 'PEOPLE_SITE',
        'PeopleTableMap::COL_PEOPLE_SITE' => 'PEOPLE_SITE',
        'COL_PEOPLE_SITE' => 'PEOPLE_SITE',
        'people_site' => 'PEOPLE_SITE',
        'people.people_site' => 'PEOPLE_SITE',
        'Facebook' => 'PEOPLE_FACEBOOK',
        'People.Facebook' => 'PEOPLE_FACEBOOK',
        'facebook' => 'PEOPLE_FACEBOOK',
        'people.facebook' => 'PEOPLE_FACEBOOK',
        'PeopleTableMap::COL_PEOPLE_FACEBOOK' => 'PEOPLE_FACEBOOK',
        'COL_PEOPLE_FACEBOOK' => 'PEOPLE_FACEBOOK',
        'people_facebook' => 'PEOPLE_FACEBOOK',
        'people.people_facebook' => 'PEOPLE_FACEBOOK',
        'Twitter' => 'PEOPLE_TWITTER',
        'People.Twitter' => 'PEOPLE_TWITTER',
        'twitter' => 'PEOPLE_TWITTER',
        'people.twitter' => 'PEOPLE_TWITTER',
        'PeopleTableMap::COL_PEOPLE_TWITTER' => 'PEOPLE_TWITTER',
        'COL_PEOPLE_TWITTER' => 'PEOPLE_TWITTER',
        'people_twitter' => 'PEOPLE_TWITTER',
        'people.people_twitter' => 'PEOPLE_TWITTER',
        'Hits' => 'PEOPLE_HITS',
        'People.Hits' => 'PEOPLE_HITS',
        'hits' => 'PEOPLE_HITS',
        'people.hits' => 'PEOPLE_HITS',
        'PeopleTableMap::COL_PEOPLE_HITS' => 'PEOPLE_HITS',
        'COL_PEOPLE_HITS' => 'PEOPLE_HITS',
        'people_hits' => 'PEOPLE_HITS',
        'people.people_hits' => 'PEOPLE_HITS',
        'Date' => 'PEOPLE_DATE',
        'People.Date' => 'PEOPLE_DATE',
        'date' => 'PEOPLE_DATE',
        'people.date' => 'PEOPLE_DATE',
        'PeopleTableMap::COL_PEOPLE_DATE' => 'PEOPLE_DATE',
        'COL_PEOPLE_DATE' => 'PEOPLE_DATE',
        'people_date' => 'PEOPLE_DATE',
        'people.people_date' => 'PEOPLE_DATE',
        'Insert' => 'PEOPLE_INSERT',
        'People.Insert' => 'PEOPLE_INSERT',
        'insert' => 'PEOPLE_INSERT',
        'people.insert' => 'PEOPLE_INSERT',
        'PeopleTableMap::COL_PEOPLE_INSERT' => 'PEOPLE_INSERT',
        'COL_PEOPLE_INSERT' => 'PEOPLE_INSERT',
        'people_insert' => 'PEOPLE_INSERT',
        'people.people_insert' => 'PEOPLE_INSERT',
        'Update' => 'PEOPLE_UPDATE',
        'People.Update' => 'PEOPLE_UPDATE',
        'update' => 'PEOPLE_UPDATE',
        'people.update' => 'PEOPLE_UPDATE',
        'PeopleTableMap::COL_PEOPLE_UPDATE' => 'PEOPLE_UPDATE',
        'COL_PEOPLE_UPDATE' => 'PEOPLE_UPDATE',
        'people_update' => 'PEOPLE_UPDATE',
        'people.people_update' => 'PEOPLE_UPDATE',
        'CreatedAt' => 'PEOPLE_CREATED',
        'People.CreatedAt' => 'PEOPLE_CREATED',
        'createdAt' => 'PEOPLE_CREATED',
        'people.createdAt' => 'PEOPLE_CREATED',
        'PeopleTableMap::COL_PEOPLE_CREATED' => 'PEOPLE_CREATED',
        'COL_PEOPLE_CREATED' => 'PEOPLE_CREATED',
        'people_created' => 'PEOPLE_CREATED',
        'people.people_created' => 'PEOPLE_CREATED',
        'UpdatedAt' => 'PEOPLE_UPDATED',
        'People.UpdatedAt' => 'PEOPLE_UPDATED',
        'updatedAt' => 'PEOPLE_UPDATED',
        'people.updatedAt' => 'PEOPLE_UPDATED',
        'PeopleTableMap::COL_PEOPLE_UPDATED' => 'PEOPLE_UPDATED',
        'COL_PEOPLE_UPDATED' => 'PEOPLE_UPDATED',
        'people_updated' => 'PEOPLE_UPDATED',
        'people.people_updated' => 'PEOPLE_UPDATED',
    ];

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function initialize(): void
    {
        // attributes
        $this->setName('people');
        $this->setPhpName('People');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\People');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('people_id', 'Id', 'INTEGER', true, 10, null);
        $this->addColumn('people_first_name', 'FirstName', 'VARCHAR', false, 256, null);
        $this->addColumn('people_last_name', 'LastName', 'VARCHAR', false, 256, null);
        $this->addColumn('people_name', 'Name', 'VARCHAR', false, 255, null);
        $this->addColumn('people_alpha', 'Alpha', 'VARCHAR', false, 256, null);
        $this->addColumn('people_url_old', 'UrlOld', 'VARCHAR', false, 128, null);
        $this->addColumn('people_url', 'Url', 'VARCHAR', false, 128, null);
        $this->addColumn('people_pseudo', 'Pseudo', 'INTEGER', false, 10, null);
        $this->addColumn('people_noosfere_id', 'NoosfereId', 'INTEGER', false, null, null);
        $this->addColumn('people_birth', 'Birth', 'INTEGER', false, 4, null);
        $this->addColumn('people_death', 'Death', 'INTEGER', false, 4, null);
        $this->addColumn('people_gender', 'Gender', 'CHAR', false, null, null);
        $this->addColumn('people_nation', 'Nation', 'VARCHAR', false, 255, null);
        $this->addColumn('people_bio', 'Bio', 'LONGVARCHAR', false, null, null);
        $this->addColumn('people_site', 'Site', 'VARCHAR', false, 255, null);
        $this->addColumn('people_facebook', 'Facebook', 'VARCHAR', false, 256, null);
        $this->addColumn('people_twitter', 'Twitter', 'VARCHAR', false, 256, null);
        $this->addColumn('people_hits', 'Hits', 'INTEGER', false, 10, null);
        $this->addColumn('people_date', 'Date', 'TIMESTAMP', false, null, null);
        $this->addColumn('people_insert', 'Insert', 'TIMESTAMP', false, null, null);
        $this->addColumn('people_update', 'Update', 'TIMESTAMP', false, null, null);
        $this->addColumn('people_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('people_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
        $this->addRelation('Role', '\\Model\\Role', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':people_id',
    1 => ':people_id',
  ),
), null, null, 'Roles', false);
    }

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array<string, array> Associative array (name => parameters) of behaviors
     */
    public function getBehaviors(): array
    {
        return [
            'timestampable' => ['create_column' => 'people_created', 'update_column' => 'people_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
        ];
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array $row Resultset row.
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string|null The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): ?string
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
     * @param array $row Resultset row.
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM)
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
     * @param bool $withPrefix Whether to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass(bool $withPrefix = true): string
    {
        return $withPrefix ? PeopleTableMap::CLASS_DEFAULT : PeopleTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array $row Row returned by DataFetcher->fetch().
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array (People object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = PeopleTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = PeopleTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + PeopleTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PeopleTableMap::OM_CLASS;
            /** @var People $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            PeopleTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array<object>
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher): array
    {
        $results = [];

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = PeopleTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = PeopleTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var People $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PeopleTableMap::addInstanceToPool($obj, $key);
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
     * @param Criteria $criteria Object containing the columns to add.
     * @param string|null $alias Optional table alias
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return void
     */
    public static function addSelectColumns(Criteria $criteria, ?string $alias = null): void
    {
        if (null === $alias) {
            $criteria->addSelectColumn(PeopleTableMap::COL_PEOPLE_ID);
            $criteria->addSelectColumn(PeopleTableMap::COL_PEOPLE_FIRST_NAME);
            $criteria->addSelectColumn(PeopleTableMap::COL_PEOPLE_LAST_NAME);
            $criteria->addSelectColumn(PeopleTableMap::COL_PEOPLE_NAME);
            $criteria->addSelectColumn(PeopleTableMap::COL_PEOPLE_ALPHA);
            $criteria->addSelectColumn(PeopleTableMap::COL_PEOPLE_URL_OLD);
            $criteria->addSelectColumn(PeopleTableMap::COL_PEOPLE_URL);
            $criteria->addSelectColumn(PeopleTableMap::COL_PEOPLE_PSEUDO);
            $criteria->addSelectColumn(PeopleTableMap::COL_PEOPLE_NOOSFERE_ID);
            $criteria->addSelectColumn(PeopleTableMap::COL_PEOPLE_BIRTH);
            $criteria->addSelectColumn(PeopleTableMap::COL_PEOPLE_DEATH);
            $criteria->addSelectColumn(PeopleTableMap::COL_PEOPLE_GENDER);
            $criteria->addSelectColumn(PeopleTableMap::COL_PEOPLE_NATION);
            $criteria->addSelectColumn(PeopleTableMap::COL_PEOPLE_BIO);
            $criteria->addSelectColumn(PeopleTableMap::COL_PEOPLE_SITE);
            $criteria->addSelectColumn(PeopleTableMap::COL_PEOPLE_FACEBOOK);
            $criteria->addSelectColumn(PeopleTableMap::COL_PEOPLE_TWITTER);
            $criteria->addSelectColumn(PeopleTableMap::COL_PEOPLE_HITS);
            $criteria->addSelectColumn(PeopleTableMap::COL_PEOPLE_DATE);
            $criteria->addSelectColumn(PeopleTableMap::COL_PEOPLE_INSERT);
            $criteria->addSelectColumn(PeopleTableMap::COL_PEOPLE_UPDATE);
            $criteria->addSelectColumn(PeopleTableMap::COL_PEOPLE_CREATED);
            $criteria->addSelectColumn(PeopleTableMap::COL_PEOPLE_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.people_id');
            $criteria->addSelectColumn($alias . '.people_first_name');
            $criteria->addSelectColumn($alias . '.people_last_name');
            $criteria->addSelectColumn($alias . '.people_name');
            $criteria->addSelectColumn($alias . '.people_alpha');
            $criteria->addSelectColumn($alias . '.people_url_old');
            $criteria->addSelectColumn($alias . '.people_url');
            $criteria->addSelectColumn($alias . '.people_pseudo');
            $criteria->addSelectColumn($alias . '.people_noosfere_id');
            $criteria->addSelectColumn($alias . '.people_birth');
            $criteria->addSelectColumn($alias . '.people_death');
            $criteria->addSelectColumn($alias . '.people_gender');
            $criteria->addSelectColumn($alias . '.people_nation');
            $criteria->addSelectColumn($alias . '.people_bio');
            $criteria->addSelectColumn($alias . '.people_site');
            $criteria->addSelectColumn($alias . '.people_facebook');
            $criteria->addSelectColumn($alias . '.people_twitter');
            $criteria->addSelectColumn($alias . '.people_hits');
            $criteria->addSelectColumn($alias . '.people_date');
            $criteria->addSelectColumn($alias . '.people_insert');
            $criteria->addSelectColumn($alias . '.people_update');
            $criteria->addSelectColumn($alias . '.people_created');
            $criteria->addSelectColumn($alias . '.people_updated');
        }
    }

    /**
     * Remove all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be removed as they are only loaded on demand.
     *
     * @param Criteria $criteria Object containing the columns to remove.
     * @param string|null $alias Optional table alias
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return void
     */
    public static function removeSelectColumns(Criteria $criteria, ?string $alias = null): void
    {
        if (null === $alias) {
            $criteria->removeSelectColumn(PeopleTableMap::COL_PEOPLE_ID);
            $criteria->removeSelectColumn(PeopleTableMap::COL_PEOPLE_FIRST_NAME);
            $criteria->removeSelectColumn(PeopleTableMap::COL_PEOPLE_LAST_NAME);
            $criteria->removeSelectColumn(PeopleTableMap::COL_PEOPLE_NAME);
            $criteria->removeSelectColumn(PeopleTableMap::COL_PEOPLE_ALPHA);
            $criteria->removeSelectColumn(PeopleTableMap::COL_PEOPLE_URL_OLD);
            $criteria->removeSelectColumn(PeopleTableMap::COL_PEOPLE_URL);
            $criteria->removeSelectColumn(PeopleTableMap::COL_PEOPLE_PSEUDO);
            $criteria->removeSelectColumn(PeopleTableMap::COL_PEOPLE_NOOSFERE_ID);
            $criteria->removeSelectColumn(PeopleTableMap::COL_PEOPLE_BIRTH);
            $criteria->removeSelectColumn(PeopleTableMap::COL_PEOPLE_DEATH);
            $criteria->removeSelectColumn(PeopleTableMap::COL_PEOPLE_GENDER);
            $criteria->removeSelectColumn(PeopleTableMap::COL_PEOPLE_NATION);
            $criteria->removeSelectColumn(PeopleTableMap::COL_PEOPLE_BIO);
            $criteria->removeSelectColumn(PeopleTableMap::COL_PEOPLE_SITE);
            $criteria->removeSelectColumn(PeopleTableMap::COL_PEOPLE_FACEBOOK);
            $criteria->removeSelectColumn(PeopleTableMap::COL_PEOPLE_TWITTER);
            $criteria->removeSelectColumn(PeopleTableMap::COL_PEOPLE_HITS);
            $criteria->removeSelectColumn(PeopleTableMap::COL_PEOPLE_DATE);
            $criteria->removeSelectColumn(PeopleTableMap::COL_PEOPLE_INSERT);
            $criteria->removeSelectColumn(PeopleTableMap::COL_PEOPLE_UPDATE);
            $criteria->removeSelectColumn(PeopleTableMap::COL_PEOPLE_CREATED);
            $criteria->removeSelectColumn(PeopleTableMap::COL_PEOPLE_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.people_id');
            $criteria->removeSelectColumn($alias . '.people_first_name');
            $criteria->removeSelectColumn($alias . '.people_last_name');
            $criteria->removeSelectColumn($alias . '.people_name');
            $criteria->removeSelectColumn($alias . '.people_alpha');
            $criteria->removeSelectColumn($alias . '.people_url_old');
            $criteria->removeSelectColumn($alias . '.people_url');
            $criteria->removeSelectColumn($alias . '.people_pseudo');
            $criteria->removeSelectColumn($alias . '.people_noosfere_id');
            $criteria->removeSelectColumn($alias . '.people_birth');
            $criteria->removeSelectColumn($alias . '.people_death');
            $criteria->removeSelectColumn($alias . '.people_gender');
            $criteria->removeSelectColumn($alias . '.people_nation');
            $criteria->removeSelectColumn($alias . '.people_bio');
            $criteria->removeSelectColumn($alias . '.people_site');
            $criteria->removeSelectColumn($alias . '.people_facebook');
            $criteria->removeSelectColumn($alias . '.people_twitter');
            $criteria->removeSelectColumn($alias . '.people_hits');
            $criteria->removeSelectColumn($alias . '.people_date');
            $criteria->removeSelectColumn($alias . '.people_insert');
            $criteria->removeSelectColumn($alias . '.people_update');
            $criteria->removeSelectColumn($alias . '.people_created');
            $criteria->removeSelectColumn($alias . '.people_updated');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap(): TableMap
    {
        return Propel::getServiceContainer()->getDatabaseMap(PeopleTableMap::DATABASE_NAME)->getTable(PeopleTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a People or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or People object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ?ConnectionInterface $con = null): int
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PeopleTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\People) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PeopleTableMap::DATABASE_NAME);
            $criteria->add(PeopleTableMap::COL_PEOPLE_ID, (array) $values, Criteria::IN);
        }

        $query = PeopleQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            PeopleTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                PeopleTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the people table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return PeopleQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a People or Criteria object.
     *
     * @param mixed $criteria Criteria or People object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PeopleTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from People object
        }

        if ($criteria->containsKey(PeopleTableMap::COL_PEOPLE_ID) && $criteria->keyContainsValue(PeopleTableMap::COL_PEOPLE_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.PeopleTableMap::COL_PEOPLE_ID.')');
        }


        // Set the correct dbName
        $query = PeopleQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
