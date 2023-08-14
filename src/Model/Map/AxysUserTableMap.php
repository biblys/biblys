<?php

namespace Model\Map;

use Model\AxysUser;
use Model\AxysUserQuery;
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
 * This class defines the structure of the 'axys_users' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class AxysUserTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.AxysUserTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'axys_users';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'AxysUser';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\AxysUser';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.AxysUser';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 15;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 15;

    /**
     * the column name for the id field
     */
    public const COL_ID = 'axys_users.id';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'axys_users.site_id';

    /**
     * the column name for the Email field
     */
    public const COL_EMAIL = 'axys_users.Email';

    /**
     * the column name for the user_password field
     */
    public const COL_USER_PASSWORD = 'axys_users.user_password';

    /**
     * the column name for the user_key field
     */
    public const COL_USER_KEY = 'axys_users.user_key';

    /**
     * the column name for the email_key field
     */
    public const COL_EMAIL_KEY = 'axys_users.email_key';

    /**
     * the column name for the user_screen_name field
     */
    public const COL_USER_SCREEN_NAME = 'axys_users.user_screen_name';

    /**
     * the column name for the user_slug field
     */
    public const COL_USER_SLUG = 'axys_users.user_slug';

    /**
     * the column name for the DateInscription field
     */
    public const COL_DATEINSCRIPTION = 'axys_users.DateInscription';

    /**
     * the column name for the DateConnexion field
     */
    public const COL_DATECONNEXION = 'axys_users.DateConnexion';

    /**
     * the column name for the user_nom field
     */
    public const COL_USER_NOM = 'axys_users.user_nom';

    /**
     * the column name for the user_prenom field
     */
    public const COL_USER_PRENOM = 'axys_users.user_prenom';

    /**
     * the column name for the user_update field
     */
    public const COL_USER_UPDATE = 'axys_users.user_update';

    /**
     * the column name for the user_created field
     */
    public const COL_USER_CREATED = 'axys_users.user_created';

    /**
     * the column name for the user_updated field
     */
    public const COL_USER_UPDATED = 'axys_users.user_updated';

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
        self::TYPE_PHPNAME       => ['Id', 'SiteId', 'Email', 'Password', 'Key', 'EmailKey', 'Username', 'Slug', 'Dateinscription', 'Dateconnexion', 'Nom', 'Prenom', 'Update', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'siteId', 'email', 'password', 'key', 'emailKey', 'username', 'slug', 'dateinscription', 'dateconnexion', 'nom', 'prenom', 'update', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [AxysUserTableMap::COL_ID, AxysUserTableMap::COL_SITE_ID, AxysUserTableMap::COL_EMAIL, AxysUserTableMap::COL_USER_PASSWORD, AxysUserTableMap::COL_USER_KEY, AxysUserTableMap::COL_EMAIL_KEY, AxysUserTableMap::COL_USER_SCREEN_NAME, AxysUserTableMap::COL_USER_SLUG, AxysUserTableMap::COL_DATEINSCRIPTION, AxysUserTableMap::COL_DATECONNEXION, AxysUserTableMap::COL_USER_NOM, AxysUserTableMap::COL_USER_PRENOM, AxysUserTableMap::COL_USER_UPDATE, AxysUserTableMap::COL_USER_CREATED, AxysUserTableMap::COL_USER_UPDATED, ],
        self::TYPE_FIELDNAME     => ['id', 'site_id', 'Email', 'user_password', 'user_key', 'email_key', 'user_screen_name', 'user_slug', 'DateInscription', 'DateConnexion', 'user_nom', 'user_prenom', 'user_update', 'user_created', 'user_updated', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'SiteId' => 1, 'Email' => 2, 'Password' => 3, 'Key' => 4, 'EmailKey' => 5, 'Username' => 6, 'Slug' => 7, 'Dateinscription' => 8, 'Dateconnexion' => 9, 'Nom' => 10, 'Prenom' => 11, 'Update' => 12, 'CreatedAt' => 13, 'UpdatedAt' => 14, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'siteId' => 1, 'email' => 2, 'password' => 3, 'key' => 4, 'emailKey' => 5, 'username' => 6, 'slug' => 7, 'dateinscription' => 8, 'dateconnexion' => 9, 'nom' => 10, 'prenom' => 11, 'update' => 12, 'createdAt' => 13, 'updatedAt' => 14, ],
        self::TYPE_COLNAME       => [AxysUserTableMap::COL_ID => 0, AxysUserTableMap::COL_SITE_ID => 1, AxysUserTableMap::COL_EMAIL => 2, AxysUserTableMap::COL_USER_PASSWORD => 3, AxysUserTableMap::COL_USER_KEY => 4, AxysUserTableMap::COL_EMAIL_KEY => 5, AxysUserTableMap::COL_USER_SCREEN_NAME => 6, AxysUserTableMap::COL_USER_SLUG => 7, AxysUserTableMap::COL_DATEINSCRIPTION => 8, AxysUserTableMap::COL_DATECONNEXION => 9, AxysUserTableMap::COL_USER_NOM => 10, AxysUserTableMap::COL_USER_PRENOM => 11, AxysUserTableMap::COL_USER_UPDATE => 12, AxysUserTableMap::COL_USER_CREATED => 13, AxysUserTableMap::COL_USER_UPDATED => 14, ],
        self::TYPE_FIELDNAME     => ['id' => 0, 'site_id' => 1, 'Email' => 2, 'user_password' => 3, 'user_key' => 4, 'email_key' => 5, 'user_screen_name' => 6, 'user_slug' => 7, 'DateInscription' => 8, 'DateConnexion' => 9, 'user_nom' => 10, 'user_prenom' => 11, 'user_update' => 12, 'user_created' => 13, 'user_updated' => 14, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'ID',
        'AxysUser.Id' => 'ID',
        'id' => 'ID',
        'axysUser.id' => 'ID',
        'AxysUserTableMap::COL_ID' => 'ID',
        'COL_ID' => 'ID',
        'axys_users.id' => 'ID',
        'SiteId' => 'SITE_ID',
        'AxysUser.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'axysUser.siteId' => 'SITE_ID',
        'AxysUserTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'axys_users.site_id' => 'SITE_ID',
        'Email' => 'EMAIL',
        'AxysUser.Email' => 'EMAIL',
        'email' => 'EMAIL',
        'axysUser.email' => 'EMAIL',
        'AxysUserTableMap::COL_EMAIL' => 'EMAIL',
        'COL_EMAIL' => 'EMAIL',
        'axys_users.Email' => 'EMAIL',
        'Password' => 'USER_PASSWORD',
        'AxysUser.Password' => 'USER_PASSWORD',
        'password' => 'USER_PASSWORD',
        'axysUser.password' => 'USER_PASSWORD',
        'AxysUserTableMap::COL_USER_PASSWORD' => 'USER_PASSWORD',
        'COL_USER_PASSWORD' => 'USER_PASSWORD',
        'user_password' => 'USER_PASSWORD',
        'axys_users.user_password' => 'USER_PASSWORD',
        'Key' => 'USER_KEY',
        'AxysUser.Key' => 'USER_KEY',
        'key' => 'USER_KEY',
        'axysUser.key' => 'USER_KEY',
        'AxysUserTableMap::COL_USER_KEY' => 'USER_KEY',
        'COL_USER_KEY' => 'USER_KEY',
        'user_key' => 'USER_KEY',
        'axys_users.user_key' => 'USER_KEY',
        'EmailKey' => 'EMAIL_KEY',
        'AxysUser.EmailKey' => 'EMAIL_KEY',
        'emailKey' => 'EMAIL_KEY',
        'axysUser.emailKey' => 'EMAIL_KEY',
        'AxysUserTableMap::COL_EMAIL_KEY' => 'EMAIL_KEY',
        'COL_EMAIL_KEY' => 'EMAIL_KEY',
        'email_key' => 'EMAIL_KEY',
        'axys_users.email_key' => 'EMAIL_KEY',
        'Username' => 'USER_SCREEN_NAME',
        'AxysUser.Username' => 'USER_SCREEN_NAME',
        'username' => 'USER_SCREEN_NAME',
        'axysUser.username' => 'USER_SCREEN_NAME',
        'AxysUserTableMap::COL_USER_SCREEN_NAME' => 'USER_SCREEN_NAME',
        'COL_USER_SCREEN_NAME' => 'USER_SCREEN_NAME',
        'user_screen_name' => 'USER_SCREEN_NAME',
        'axys_users.user_screen_name' => 'USER_SCREEN_NAME',
        'Slug' => 'USER_SLUG',
        'AxysUser.Slug' => 'USER_SLUG',
        'slug' => 'USER_SLUG',
        'axysUser.slug' => 'USER_SLUG',
        'AxysUserTableMap::COL_USER_SLUG' => 'USER_SLUG',
        'COL_USER_SLUG' => 'USER_SLUG',
        'user_slug' => 'USER_SLUG',
        'axys_users.user_slug' => 'USER_SLUG',
        'Dateinscription' => 'DATEINSCRIPTION',
        'AxysUser.Dateinscription' => 'DATEINSCRIPTION',
        'dateinscription' => 'DATEINSCRIPTION',
        'axysUser.dateinscription' => 'DATEINSCRIPTION',
        'AxysUserTableMap::COL_DATEINSCRIPTION' => 'DATEINSCRIPTION',
        'COL_DATEINSCRIPTION' => 'DATEINSCRIPTION',
        'DateInscription' => 'DATEINSCRIPTION',
        'axys_users.DateInscription' => 'DATEINSCRIPTION',
        'Dateconnexion' => 'DATECONNEXION',
        'AxysUser.Dateconnexion' => 'DATECONNEXION',
        'dateconnexion' => 'DATECONNEXION',
        'axysUser.dateconnexion' => 'DATECONNEXION',
        'AxysUserTableMap::COL_DATECONNEXION' => 'DATECONNEXION',
        'COL_DATECONNEXION' => 'DATECONNEXION',
        'DateConnexion' => 'DATECONNEXION',
        'axys_users.DateConnexion' => 'DATECONNEXION',
        'Nom' => 'USER_NOM',
        'AxysUser.Nom' => 'USER_NOM',
        'nom' => 'USER_NOM',
        'axysUser.nom' => 'USER_NOM',
        'AxysUserTableMap::COL_USER_NOM' => 'USER_NOM',
        'COL_USER_NOM' => 'USER_NOM',
        'user_nom' => 'USER_NOM',
        'axys_users.user_nom' => 'USER_NOM',
        'Prenom' => 'USER_PRENOM',
        'AxysUser.Prenom' => 'USER_PRENOM',
        'prenom' => 'USER_PRENOM',
        'axysUser.prenom' => 'USER_PRENOM',
        'AxysUserTableMap::COL_USER_PRENOM' => 'USER_PRENOM',
        'COL_USER_PRENOM' => 'USER_PRENOM',
        'user_prenom' => 'USER_PRENOM',
        'axys_users.user_prenom' => 'USER_PRENOM',
        'Update' => 'USER_UPDATE',
        'AxysUser.Update' => 'USER_UPDATE',
        'update' => 'USER_UPDATE',
        'axysUser.update' => 'USER_UPDATE',
        'AxysUserTableMap::COL_USER_UPDATE' => 'USER_UPDATE',
        'COL_USER_UPDATE' => 'USER_UPDATE',
        'user_update' => 'USER_UPDATE',
        'axys_users.user_update' => 'USER_UPDATE',
        'CreatedAt' => 'USER_CREATED',
        'AxysUser.CreatedAt' => 'USER_CREATED',
        'createdAt' => 'USER_CREATED',
        'axysUser.createdAt' => 'USER_CREATED',
        'AxysUserTableMap::COL_USER_CREATED' => 'USER_CREATED',
        'COL_USER_CREATED' => 'USER_CREATED',
        'user_created' => 'USER_CREATED',
        'axys_users.user_created' => 'USER_CREATED',
        'UpdatedAt' => 'USER_UPDATED',
        'AxysUser.UpdatedAt' => 'USER_UPDATED',
        'updatedAt' => 'USER_UPDATED',
        'axysUser.updatedAt' => 'USER_UPDATED',
        'AxysUserTableMap::COL_USER_UPDATED' => 'USER_UPDATED',
        'COL_USER_UPDATED' => 'USER_UPDATED',
        'user_updated' => 'USER_UPDATED',
        'axys_users.user_updated' => 'USER_UPDATED',
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
        $this->setName('axys_users');
        $this->setPhpName('AxysUser');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\AxysUser');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('site_id', 'SiteId', 'INTEGER', 'sites', 'site_id', false, null, null);
        $this->addColumn('Email', 'Email', 'VARCHAR', false, 255, null);
        $this->addColumn('user_password', 'Password', 'VARCHAR', false, 255, null);
        $this->addColumn('user_key', 'Key', 'LONGVARCHAR', false, null, null);
        $this->addColumn('email_key', 'EmailKey', 'VARCHAR', false, 32, null);
        $this->addColumn('user_screen_name', 'Username', 'VARCHAR', false, 128, null);
        $this->addColumn('user_slug', 'Slug', 'VARCHAR', false, 32, null);
        $this->addColumn('DateInscription', 'Dateinscription', 'TIMESTAMP', false, null, null);
        $this->addColumn('DateConnexion', 'Dateconnexion', 'TIMESTAMP', false, null, null);
        $this->addColumn('user_nom', 'Nom', 'LONGVARCHAR', false, null, null);
        $this->addColumn('user_prenom', 'Prenom', 'LONGVARCHAR', false, null, null);
        $this->addColumn('user_update', 'Update', 'TIMESTAMP', false, null, null);
        $this->addColumn('user_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('user_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
        $this->addRelation('Site', '\\Model\\Site', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':site_id',
    1 => ':site_id',
  ),
), null, null, null, false);
        $this->addRelation('AxysConsent', '\\Model\\AxysConsent', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'AxysConsents', false);
        $this->addRelation('Cart', '\\Model\\Cart', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':axys_user_id',
    1 => ':id',
  ),
), null, null, 'Carts', false);
        $this->addRelation('Option', '\\Model\\Option', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':axys_user_id',
    1 => ':id',
  ),
), null, null, 'Options', false);
        $this->addRelation('Right', '\\Model\\Right', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':axys_user_id',
    1 => ':id',
  ),
), null, null, 'Rights', false);
        $this->addRelation('Session', '\\Model\\Session', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':axys_user_id',
    1 => ':id',
  ),
), null, null, 'Sessions', false);
        $this->addRelation('Stock', '\\Model\\Stock', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':axys_user_id',
    1 => ':id',
  ),
), null, null, 'Stocks', false);
        $this->addRelation('Wish', '\\Model\\Wish', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':axys_user_id',
    1 => ':id',
  ),
), null, null, 'Wishes', false);
        $this->addRelation('Wishlist', '\\Model\\Wishlist', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':axys_user_id',
    1 => ':id',
  ),
), null, null, 'Wishlists', false);
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
            'timestampable' => ['create_column' => 'user_created', 'update_column' => 'user_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
            'validate' => ['rule1' => ['column' => 'email', 'validator' => 'Email']],
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
        return $withPrefix ? AxysUserTableMap::CLASS_DEFAULT : AxysUserTableMap::OM_CLASS;
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
     * @return array (AxysUser object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = AxysUserTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = AxysUserTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + AxysUserTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = AxysUserTableMap::OM_CLASS;
            /** @var AxysUser $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            AxysUserTableMap::addInstanceToPool($obj, $key);
        }

        return [$obj, $col];
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
            $key = AxysUserTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = AxysUserTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var AxysUser $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                AxysUserTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(AxysUserTableMap::COL_ID);
            $criteria->addSelectColumn(AxysUserTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(AxysUserTableMap::COL_EMAIL);
            $criteria->addSelectColumn(AxysUserTableMap::COL_USER_PASSWORD);
            $criteria->addSelectColumn(AxysUserTableMap::COL_USER_KEY);
            $criteria->addSelectColumn(AxysUserTableMap::COL_EMAIL_KEY);
            $criteria->addSelectColumn(AxysUserTableMap::COL_USER_SCREEN_NAME);
            $criteria->addSelectColumn(AxysUserTableMap::COL_USER_SLUG);
            $criteria->addSelectColumn(AxysUserTableMap::COL_DATEINSCRIPTION);
            $criteria->addSelectColumn(AxysUserTableMap::COL_DATECONNEXION);
            $criteria->addSelectColumn(AxysUserTableMap::COL_USER_NOM);
            $criteria->addSelectColumn(AxysUserTableMap::COL_USER_PRENOM);
            $criteria->addSelectColumn(AxysUserTableMap::COL_USER_UPDATE);
            $criteria->addSelectColumn(AxysUserTableMap::COL_USER_CREATED);
            $criteria->addSelectColumn(AxysUserTableMap::COL_USER_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.Email');
            $criteria->addSelectColumn($alias . '.user_password');
            $criteria->addSelectColumn($alias . '.user_key');
            $criteria->addSelectColumn($alias . '.email_key');
            $criteria->addSelectColumn($alias . '.user_screen_name');
            $criteria->addSelectColumn($alias . '.user_slug');
            $criteria->addSelectColumn($alias . '.DateInscription');
            $criteria->addSelectColumn($alias . '.DateConnexion');
            $criteria->addSelectColumn($alias . '.user_nom');
            $criteria->addSelectColumn($alias . '.user_prenom');
            $criteria->addSelectColumn($alias . '.user_update');
            $criteria->addSelectColumn($alias . '.user_created');
            $criteria->addSelectColumn($alias . '.user_updated');
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
            $criteria->removeSelectColumn(AxysUserTableMap::COL_ID);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_EMAIL);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_USER_PASSWORD);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_USER_KEY);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_EMAIL_KEY);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_USER_SCREEN_NAME);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_USER_SLUG);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_DATEINSCRIPTION);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_DATECONNEXION);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_USER_NOM);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_USER_PRENOM);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_USER_UPDATE);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_USER_CREATED);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_USER_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.Email');
            $criteria->removeSelectColumn($alias . '.user_password');
            $criteria->removeSelectColumn($alias . '.user_key');
            $criteria->removeSelectColumn($alias . '.email_key');
            $criteria->removeSelectColumn($alias . '.user_screen_name');
            $criteria->removeSelectColumn($alias . '.user_slug');
            $criteria->removeSelectColumn($alias . '.DateInscription');
            $criteria->removeSelectColumn($alias . '.DateConnexion');
            $criteria->removeSelectColumn($alias . '.user_nom');
            $criteria->removeSelectColumn($alias . '.user_prenom');
            $criteria->removeSelectColumn($alias . '.user_update');
            $criteria->removeSelectColumn($alias . '.user_created');
            $criteria->removeSelectColumn($alias . '.user_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(AxysUserTableMap::DATABASE_NAME)->getTable(AxysUserTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a AxysUser or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or AxysUser object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(AxysUserTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\AxysUser) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(AxysUserTableMap::DATABASE_NAME);
            $criteria->add(AxysUserTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = AxysUserQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            AxysUserTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                AxysUserTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the axys_users table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return AxysUserQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a AxysUser or Criteria object.
     *
     * @param mixed $criteria Criteria or AxysUser object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AxysUserTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from AxysUser object
        }

        if ($criteria->containsKey(AxysUserTableMap::COL_ID) && $criteria->keyContainsValue(AxysUserTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.AxysUserTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = AxysUserQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
