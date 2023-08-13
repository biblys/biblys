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
    public const NUM_COLUMNS = 37;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 37;

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
     * the column name for the facebook_uid field
     */
    public const COL_FACEBOOK_UID = 'axys_users.facebook_uid';

    /**
     * the column name for the user_screen_name field
     */
    public const COL_USER_SCREEN_NAME = 'axys_users.user_screen_name';

    /**
     * the column name for the user_slug field
     */
    public const COL_USER_SLUG = 'axys_users.user_slug';

    /**
     * the column name for the user_wishlist_ship field
     */
    public const COL_USER_WISHLIST_SHIP = 'axys_users.user_wishlist_ship';

    /**
     * the column name for the user_top field
     */
    public const COL_USER_TOP = 'axys_users.user_top';

    /**
     * the column name for the user_biblio field
     */
    public const COL_USER_BIBLIO = 'axys_users.user_biblio';

    /**
     * the column name for the adresse_ip field
     */
    public const COL_ADRESSE_IP = 'axys_users.adresse_ip';

    /**
     * the column name for the recaptcha_score field
     */
    public const COL_RECAPTCHA_SCORE = 'axys_users.recaptcha_score';

    /**
     * the column name for the DateInscription field
     */
    public const COL_DATEINSCRIPTION = 'axys_users.DateInscription';

    /**
     * the column name for the DateConnexion field
     */
    public const COL_DATECONNEXION = 'axys_users.DateConnexion';

    /**
     * the column name for the publisher_id field
     */
    public const COL_PUBLISHER_ID = 'axys_users.publisher_id';

    /**
     * the column name for the bookshop_id field
     */
    public const COL_BOOKSHOP_ID = 'axys_users.bookshop_id';

    /**
     * the column name for the library_id field
     */
    public const COL_LIBRARY_ID = 'axys_users.library_id';

    /**
     * the column name for the user_civilite field
     */
    public const COL_USER_CIVILITE = 'axys_users.user_civilite';

    /**
     * the column name for the user_nom field
     */
    public const COL_USER_NOM = 'axys_users.user_nom';

    /**
     * the column name for the user_prenom field
     */
    public const COL_USER_PRENOM = 'axys_users.user_prenom';

    /**
     * the column name for the user_adresse1 field
     */
    public const COL_USER_ADRESSE1 = 'axys_users.user_adresse1';

    /**
     * the column name for the user_adresse2 field
     */
    public const COL_USER_ADRESSE2 = 'axys_users.user_adresse2';

    /**
     * the column name for the user_codepostal field
     */
    public const COL_USER_CODEPOSTAL = 'axys_users.user_codepostal';

    /**
     * the column name for the user_ville field
     */
    public const COL_USER_VILLE = 'axys_users.user_ville';

    /**
     * the column name for the user_pays field
     */
    public const COL_USER_PAYS = 'axys_users.user_pays';

    /**
     * the column name for the user_telephone field
     */
    public const COL_USER_TELEPHONE = 'axys_users.user_telephone';

    /**
     * the column name for the user_pref_articles_show field
     */
    public const COL_USER_PREF_ARTICLES_SHOW = 'axys_users.user_pref_articles_show';

    /**
     * the column name for the user_fb_id field
     */
    public const COL_USER_FB_ID = 'axys_users.user_fb_id';

    /**
     * the column name for the user_fb_token field
     */
    public const COL_USER_FB_TOKEN = 'axys_users.user_fb_token';

    /**
     * the column name for the country_id field
     */
    public const COL_COUNTRY_ID = 'axys_users.country_id';

    /**
     * the column name for the user_password_reset_token field
     */
    public const COL_USER_PASSWORD_RESET_TOKEN = 'axys_users.user_password_reset_token';

    /**
     * the column name for the user_password_reset_token_created field
     */
    public const COL_USER_PASSWORD_RESET_TOKEN_CREATED = 'axys_users.user_password_reset_token_created';

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
        self::TYPE_PHPNAME       => ['Id', 'SiteId', 'Email', 'Password', 'Key', 'EmailKey', 'FacebookUid', 'Username', 'Slug', 'WishlistShip', 'Top', 'Biblio', 'AdresseIp', 'RecaptchaScore', 'Dateinscription', 'Dateconnexion', 'PublisherId', 'BookshopId', 'LibraryId', 'Civilite', 'Nom', 'Prenom', 'Adresse1', 'Adresse2', 'Codepostal', 'Ville', 'Pays', 'Telephone', 'PrefArticlesShow', 'FbId', 'FbToken', 'CountryId', 'PasswordResetToken', 'PasswordResetTokenCreated', 'Update', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'siteId', 'email', 'password', 'key', 'emailKey', 'facebookUid', 'username', 'slug', 'wishlistShip', 'top', 'biblio', 'adresseIp', 'recaptchaScore', 'dateinscription', 'dateconnexion', 'publisherId', 'bookshopId', 'libraryId', 'civilite', 'nom', 'prenom', 'adresse1', 'adresse2', 'codepostal', 'ville', 'pays', 'telephone', 'prefArticlesShow', 'fbId', 'fbToken', 'countryId', 'passwordResetToken', 'passwordResetTokenCreated', 'update', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [AxysUserTableMap::COL_ID, AxysUserTableMap::COL_SITE_ID, AxysUserTableMap::COL_EMAIL, AxysUserTableMap::COL_USER_PASSWORD, AxysUserTableMap::COL_USER_KEY, AxysUserTableMap::COL_EMAIL_KEY, AxysUserTableMap::COL_FACEBOOK_UID, AxysUserTableMap::COL_USER_SCREEN_NAME, AxysUserTableMap::COL_USER_SLUG, AxysUserTableMap::COL_USER_WISHLIST_SHIP, AxysUserTableMap::COL_USER_TOP, AxysUserTableMap::COL_USER_BIBLIO, AxysUserTableMap::COL_ADRESSE_IP, AxysUserTableMap::COL_RECAPTCHA_SCORE, AxysUserTableMap::COL_DATEINSCRIPTION, AxysUserTableMap::COL_DATECONNEXION, AxysUserTableMap::COL_PUBLISHER_ID, AxysUserTableMap::COL_BOOKSHOP_ID, AxysUserTableMap::COL_LIBRARY_ID, AxysUserTableMap::COL_USER_CIVILITE, AxysUserTableMap::COL_USER_NOM, AxysUserTableMap::COL_USER_PRENOM, AxysUserTableMap::COL_USER_ADRESSE1, AxysUserTableMap::COL_USER_ADRESSE2, AxysUserTableMap::COL_USER_CODEPOSTAL, AxysUserTableMap::COL_USER_VILLE, AxysUserTableMap::COL_USER_PAYS, AxysUserTableMap::COL_USER_TELEPHONE, AxysUserTableMap::COL_USER_PREF_ARTICLES_SHOW, AxysUserTableMap::COL_USER_FB_ID, AxysUserTableMap::COL_USER_FB_TOKEN, AxysUserTableMap::COL_COUNTRY_ID, AxysUserTableMap::COL_USER_PASSWORD_RESET_TOKEN, AxysUserTableMap::COL_USER_PASSWORD_RESET_TOKEN_CREATED, AxysUserTableMap::COL_USER_UPDATE, AxysUserTableMap::COL_USER_CREATED, AxysUserTableMap::COL_USER_UPDATED, ],
        self::TYPE_FIELDNAME     => ['id', 'site_id', 'Email', 'user_password', 'user_key', 'email_key', 'facebook_uid', 'user_screen_name', 'user_slug', 'user_wishlist_ship', 'user_top', 'user_biblio', 'adresse_ip', 'recaptcha_score', 'DateInscription', 'DateConnexion', 'publisher_id', 'bookshop_id', 'library_id', 'user_civilite', 'user_nom', 'user_prenom', 'user_adresse1', 'user_adresse2', 'user_codepostal', 'user_ville', 'user_pays', 'user_telephone', 'user_pref_articles_show', 'user_fb_id', 'user_fb_token', 'country_id', 'user_password_reset_token', 'user_password_reset_token_created', 'user_update', 'user_created', 'user_updated', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'SiteId' => 1, 'Email' => 2, 'Password' => 3, 'Key' => 4, 'EmailKey' => 5, 'FacebookUid' => 6, 'Username' => 7, 'Slug' => 8, 'WishlistShip' => 9, 'Top' => 10, 'Biblio' => 11, 'AdresseIp' => 12, 'RecaptchaScore' => 13, 'Dateinscription' => 14, 'Dateconnexion' => 15, 'PublisherId' => 16, 'BookshopId' => 17, 'LibraryId' => 18, 'Civilite' => 19, 'Nom' => 20, 'Prenom' => 21, 'Adresse1' => 22, 'Adresse2' => 23, 'Codepostal' => 24, 'Ville' => 25, 'Pays' => 26, 'Telephone' => 27, 'PrefArticlesShow' => 28, 'FbId' => 29, 'FbToken' => 30, 'CountryId' => 31, 'PasswordResetToken' => 32, 'PasswordResetTokenCreated' => 33, 'Update' => 34, 'CreatedAt' => 35, 'UpdatedAt' => 36, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'siteId' => 1, 'email' => 2, 'password' => 3, 'key' => 4, 'emailKey' => 5, 'facebookUid' => 6, 'username' => 7, 'slug' => 8, 'wishlistShip' => 9, 'top' => 10, 'biblio' => 11, 'adresseIp' => 12, 'recaptchaScore' => 13, 'dateinscription' => 14, 'dateconnexion' => 15, 'publisherId' => 16, 'bookshopId' => 17, 'libraryId' => 18, 'civilite' => 19, 'nom' => 20, 'prenom' => 21, 'adresse1' => 22, 'adresse2' => 23, 'codepostal' => 24, 'ville' => 25, 'pays' => 26, 'telephone' => 27, 'prefArticlesShow' => 28, 'fbId' => 29, 'fbToken' => 30, 'countryId' => 31, 'passwordResetToken' => 32, 'passwordResetTokenCreated' => 33, 'update' => 34, 'createdAt' => 35, 'updatedAt' => 36, ],
        self::TYPE_COLNAME       => [AxysUserTableMap::COL_ID => 0, AxysUserTableMap::COL_SITE_ID => 1, AxysUserTableMap::COL_EMAIL => 2, AxysUserTableMap::COL_USER_PASSWORD => 3, AxysUserTableMap::COL_USER_KEY => 4, AxysUserTableMap::COL_EMAIL_KEY => 5, AxysUserTableMap::COL_FACEBOOK_UID => 6, AxysUserTableMap::COL_USER_SCREEN_NAME => 7, AxysUserTableMap::COL_USER_SLUG => 8, AxysUserTableMap::COL_USER_WISHLIST_SHIP => 9, AxysUserTableMap::COL_USER_TOP => 10, AxysUserTableMap::COL_USER_BIBLIO => 11, AxysUserTableMap::COL_ADRESSE_IP => 12, AxysUserTableMap::COL_RECAPTCHA_SCORE => 13, AxysUserTableMap::COL_DATEINSCRIPTION => 14, AxysUserTableMap::COL_DATECONNEXION => 15, AxysUserTableMap::COL_PUBLISHER_ID => 16, AxysUserTableMap::COL_BOOKSHOP_ID => 17, AxysUserTableMap::COL_LIBRARY_ID => 18, AxysUserTableMap::COL_USER_CIVILITE => 19, AxysUserTableMap::COL_USER_NOM => 20, AxysUserTableMap::COL_USER_PRENOM => 21, AxysUserTableMap::COL_USER_ADRESSE1 => 22, AxysUserTableMap::COL_USER_ADRESSE2 => 23, AxysUserTableMap::COL_USER_CODEPOSTAL => 24, AxysUserTableMap::COL_USER_VILLE => 25, AxysUserTableMap::COL_USER_PAYS => 26, AxysUserTableMap::COL_USER_TELEPHONE => 27, AxysUserTableMap::COL_USER_PREF_ARTICLES_SHOW => 28, AxysUserTableMap::COL_USER_FB_ID => 29, AxysUserTableMap::COL_USER_FB_TOKEN => 30, AxysUserTableMap::COL_COUNTRY_ID => 31, AxysUserTableMap::COL_USER_PASSWORD_RESET_TOKEN => 32, AxysUserTableMap::COL_USER_PASSWORD_RESET_TOKEN_CREATED => 33, AxysUserTableMap::COL_USER_UPDATE => 34, AxysUserTableMap::COL_USER_CREATED => 35, AxysUserTableMap::COL_USER_UPDATED => 36, ],
        self::TYPE_FIELDNAME     => ['id' => 0, 'site_id' => 1, 'Email' => 2, 'user_password' => 3, 'user_key' => 4, 'email_key' => 5, 'facebook_uid' => 6, 'user_screen_name' => 7, 'user_slug' => 8, 'user_wishlist_ship' => 9, 'user_top' => 10, 'user_biblio' => 11, 'adresse_ip' => 12, 'recaptcha_score' => 13, 'DateInscription' => 14, 'DateConnexion' => 15, 'publisher_id' => 16, 'bookshop_id' => 17, 'library_id' => 18, 'user_civilite' => 19, 'user_nom' => 20, 'user_prenom' => 21, 'user_adresse1' => 22, 'user_adresse2' => 23, 'user_codepostal' => 24, 'user_ville' => 25, 'user_pays' => 26, 'user_telephone' => 27, 'user_pref_articles_show' => 28, 'user_fb_id' => 29, 'user_fb_token' => 30, 'country_id' => 31, 'user_password_reset_token' => 32, 'user_password_reset_token_created' => 33, 'user_update' => 34, 'user_created' => 35, 'user_updated' => 36, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, ]
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
        'FacebookUid' => 'FACEBOOK_UID',
        'AxysUser.FacebookUid' => 'FACEBOOK_UID',
        'facebookUid' => 'FACEBOOK_UID',
        'axysUser.facebookUid' => 'FACEBOOK_UID',
        'AxysUserTableMap::COL_FACEBOOK_UID' => 'FACEBOOK_UID',
        'COL_FACEBOOK_UID' => 'FACEBOOK_UID',
        'facebook_uid' => 'FACEBOOK_UID',
        'axys_users.facebook_uid' => 'FACEBOOK_UID',
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
        'WishlistShip' => 'USER_WISHLIST_SHIP',
        'AxysUser.WishlistShip' => 'USER_WISHLIST_SHIP',
        'wishlistShip' => 'USER_WISHLIST_SHIP',
        'axysUser.wishlistShip' => 'USER_WISHLIST_SHIP',
        'AxysUserTableMap::COL_USER_WISHLIST_SHIP' => 'USER_WISHLIST_SHIP',
        'COL_USER_WISHLIST_SHIP' => 'USER_WISHLIST_SHIP',
        'user_wishlist_ship' => 'USER_WISHLIST_SHIP',
        'axys_users.user_wishlist_ship' => 'USER_WISHLIST_SHIP',
        'Top' => 'USER_TOP',
        'AxysUser.Top' => 'USER_TOP',
        'top' => 'USER_TOP',
        'axysUser.top' => 'USER_TOP',
        'AxysUserTableMap::COL_USER_TOP' => 'USER_TOP',
        'COL_USER_TOP' => 'USER_TOP',
        'user_top' => 'USER_TOP',
        'axys_users.user_top' => 'USER_TOP',
        'Biblio' => 'USER_BIBLIO',
        'AxysUser.Biblio' => 'USER_BIBLIO',
        'biblio' => 'USER_BIBLIO',
        'axysUser.biblio' => 'USER_BIBLIO',
        'AxysUserTableMap::COL_USER_BIBLIO' => 'USER_BIBLIO',
        'COL_USER_BIBLIO' => 'USER_BIBLIO',
        'user_biblio' => 'USER_BIBLIO',
        'axys_users.user_biblio' => 'USER_BIBLIO',
        'AdresseIp' => 'ADRESSE_IP',
        'AxysUser.AdresseIp' => 'ADRESSE_IP',
        'adresseIp' => 'ADRESSE_IP',
        'axysUser.adresseIp' => 'ADRESSE_IP',
        'AxysUserTableMap::COL_ADRESSE_IP' => 'ADRESSE_IP',
        'COL_ADRESSE_IP' => 'ADRESSE_IP',
        'adresse_ip' => 'ADRESSE_IP',
        'axys_users.adresse_ip' => 'ADRESSE_IP',
        'RecaptchaScore' => 'RECAPTCHA_SCORE',
        'AxysUser.RecaptchaScore' => 'RECAPTCHA_SCORE',
        'recaptchaScore' => 'RECAPTCHA_SCORE',
        'axysUser.recaptchaScore' => 'RECAPTCHA_SCORE',
        'AxysUserTableMap::COL_RECAPTCHA_SCORE' => 'RECAPTCHA_SCORE',
        'COL_RECAPTCHA_SCORE' => 'RECAPTCHA_SCORE',
        'recaptcha_score' => 'RECAPTCHA_SCORE',
        'axys_users.recaptcha_score' => 'RECAPTCHA_SCORE',
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
        'PublisherId' => 'PUBLISHER_ID',
        'AxysUser.PublisherId' => 'PUBLISHER_ID',
        'publisherId' => 'PUBLISHER_ID',
        'axysUser.publisherId' => 'PUBLISHER_ID',
        'AxysUserTableMap::COL_PUBLISHER_ID' => 'PUBLISHER_ID',
        'COL_PUBLISHER_ID' => 'PUBLISHER_ID',
        'publisher_id' => 'PUBLISHER_ID',
        'axys_users.publisher_id' => 'PUBLISHER_ID',
        'BookshopId' => 'BOOKSHOP_ID',
        'AxysUser.BookshopId' => 'BOOKSHOP_ID',
        'bookshopId' => 'BOOKSHOP_ID',
        'axysUser.bookshopId' => 'BOOKSHOP_ID',
        'AxysUserTableMap::COL_BOOKSHOP_ID' => 'BOOKSHOP_ID',
        'COL_BOOKSHOP_ID' => 'BOOKSHOP_ID',
        'bookshop_id' => 'BOOKSHOP_ID',
        'axys_users.bookshop_id' => 'BOOKSHOP_ID',
        'LibraryId' => 'LIBRARY_ID',
        'AxysUser.LibraryId' => 'LIBRARY_ID',
        'libraryId' => 'LIBRARY_ID',
        'axysUser.libraryId' => 'LIBRARY_ID',
        'AxysUserTableMap::COL_LIBRARY_ID' => 'LIBRARY_ID',
        'COL_LIBRARY_ID' => 'LIBRARY_ID',
        'library_id' => 'LIBRARY_ID',
        'axys_users.library_id' => 'LIBRARY_ID',
        'Civilite' => 'USER_CIVILITE',
        'AxysUser.Civilite' => 'USER_CIVILITE',
        'civilite' => 'USER_CIVILITE',
        'axysUser.civilite' => 'USER_CIVILITE',
        'AxysUserTableMap::COL_USER_CIVILITE' => 'USER_CIVILITE',
        'COL_USER_CIVILITE' => 'USER_CIVILITE',
        'user_civilite' => 'USER_CIVILITE',
        'axys_users.user_civilite' => 'USER_CIVILITE',
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
        'Adresse1' => 'USER_ADRESSE1',
        'AxysUser.Adresse1' => 'USER_ADRESSE1',
        'adresse1' => 'USER_ADRESSE1',
        'axysUser.adresse1' => 'USER_ADRESSE1',
        'AxysUserTableMap::COL_USER_ADRESSE1' => 'USER_ADRESSE1',
        'COL_USER_ADRESSE1' => 'USER_ADRESSE1',
        'user_adresse1' => 'USER_ADRESSE1',
        'axys_users.user_adresse1' => 'USER_ADRESSE1',
        'Adresse2' => 'USER_ADRESSE2',
        'AxysUser.Adresse2' => 'USER_ADRESSE2',
        'adresse2' => 'USER_ADRESSE2',
        'axysUser.adresse2' => 'USER_ADRESSE2',
        'AxysUserTableMap::COL_USER_ADRESSE2' => 'USER_ADRESSE2',
        'COL_USER_ADRESSE2' => 'USER_ADRESSE2',
        'user_adresse2' => 'USER_ADRESSE2',
        'axys_users.user_adresse2' => 'USER_ADRESSE2',
        'Codepostal' => 'USER_CODEPOSTAL',
        'AxysUser.Codepostal' => 'USER_CODEPOSTAL',
        'codepostal' => 'USER_CODEPOSTAL',
        'axysUser.codepostal' => 'USER_CODEPOSTAL',
        'AxysUserTableMap::COL_USER_CODEPOSTAL' => 'USER_CODEPOSTAL',
        'COL_USER_CODEPOSTAL' => 'USER_CODEPOSTAL',
        'user_codepostal' => 'USER_CODEPOSTAL',
        'axys_users.user_codepostal' => 'USER_CODEPOSTAL',
        'Ville' => 'USER_VILLE',
        'AxysUser.Ville' => 'USER_VILLE',
        'ville' => 'USER_VILLE',
        'axysUser.ville' => 'USER_VILLE',
        'AxysUserTableMap::COL_USER_VILLE' => 'USER_VILLE',
        'COL_USER_VILLE' => 'USER_VILLE',
        'user_ville' => 'USER_VILLE',
        'axys_users.user_ville' => 'USER_VILLE',
        'Pays' => 'USER_PAYS',
        'AxysUser.Pays' => 'USER_PAYS',
        'pays' => 'USER_PAYS',
        'axysUser.pays' => 'USER_PAYS',
        'AxysUserTableMap::COL_USER_PAYS' => 'USER_PAYS',
        'COL_USER_PAYS' => 'USER_PAYS',
        'user_pays' => 'USER_PAYS',
        'axys_users.user_pays' => 'USER_PAYS',
        'Telephone' => 'USER_TELEPHONE',
        'AxysUser.Telephone' => 'USER_TELEPHONE',
        'telephone' => 'USER_TELEPHONE',
        'axysUser.telephone' => 'USER_TELEPHONE',
        'AxysUserTableMap::COL_USER_TELEPHONE' => 'USER_TELEPHONE',
        'COL_USER_TELEPHONE' => 'USER_TELEPHONE',
        'user_telephone' => 'USER_TELEPHONE',
        'axys_users.user_telephone' => 'USER_TELEPHONE',
        'PrefArticlesShow' => 'USER_PREF_ARTICLES_SHOW',
        'AxysUser.PrefArticlesShow' => 'USER_PREF_ARTICLES_SHOW',
        'prefArticlesShow' => 'USER_PREF_ARTICLES_SHOW',
        'axysUser.prefArticlesShow' => 'USER_PREF_ARTICLES_SHOW',
        'AxysUserTableMap::COL_USER_PREF_ARTICLES_SHOW' => 'USER_PREF_ARTICLES_SHOW',
        'COL_USER_PREF_ARTICLES_SHOW' => 'USER_PREF_ARTICLES_SHOW',
        'user_pref_articles_show' => 'USER_PREF_ARTICLES_SHOW',
        'axys_users.user_pref_articles_show' => 'USER_PREF_ARTICLES_SHOW',
        'FbId' => 'USER_FB_ID',
        'AxysUser.FbId' => 'USER_FB_ID',
        'fbId' => 'USER_FB_ID',
        'axysUser.fbId' => 'USER_FB_ID',
        'AxysUserTableMap::COL_USER_FB_ID' => 'USER_FB_ID',
        'COL_USER_FB_ID' => 'USER_FB_ID',
        'user_fb_id' => 'USER_FB_ID',
        'axys_users.user_fb_id' => 'USER_FB_ID',
        'FbToken' => 'USER_FB_TOKEN',
        'AxysUser.FbToken' => 'USER_FB_TOKEN',
        'fbToken' => 'USER_FB_TOKEN',
        'axysUser.fbToken' => 'USER_FB_TOKEN',
        'AxysUserTableMap::COL_USER_FB_TOKEN' => 'USER_FB_TOKEN',
        'COL_USER_FB_TOKEN' => 'USER_FB_TOKEN',
        'user_fb_token' => 'USER_FB_TOKEN',
        'axys_users.user_fb_token' => 'USER_FB_TOKEN',
        'CountryId' => 'COUNTRY_ID',
        'AxysUser.CountryId' => 'COUNTRY_ID',
        'countryId' => 'COUNTRY_ID',
        'axysUser.countryId' => 'COUNTRY_ID',
        'AxysUserTableMap::COL_COUNTRY_ID' => 'COUNTRY_ID',
        'COL_COUNTRY_ID' => 'COUNTRY_ID',
        'country_id' => 'COUNTRY_ID',
        'axys_users.country_id' => 'COUNTRY_ID',
        'PasswordResetToken' => 'USER_PASSWORD_RESET_TOKEN',
        'AxysUser.PasswordResetToken' => 'USER_PASSWORD_RESET_TOKEN',
        'passwordResetToken' => 'USER_PASSWORD_RESET_TOKEN',
        'axysUser.passwordResetToken' => 'USER_PASSWORD_RESET_TOKEN',
        'AxysUserTableMap::COL_USER_PASSWORD_RESET_TOKEN' => 'USER_PASSWORD_RESET_TOKEN',
        'COL_USER_PASSWORD_RESET_TOKEN' => 'USER_PASSWORD_RESET_TOKEN',
        'user_password_reset_token' => 'USER_PASSWORD_RESET_TOKEN',
        'axys_users.user_password_reset_token' => 'USER_PASSWORD_RESET_TOKEN',
        'PasswordResetTokenCreated' => 'USER_PASSWORD_RESET_TOKEN_CREATED',
        'AxysUser.PasswordResetTokenCreated' => 'USER_PASSWORD_RESET_TOKEN_CREATED',
        'passwordResetTokenCreated' => 'USER_PASSWORD_RESET_TOKEN_CREATED',
        'axysUser.passwordResetTokenCreated' => 'USER_PASSWORD_RESET_TOKEN_CREATED',
        'AxysUserTableMap::COL_USER_PASSWORD_RESET_TOKEN_CREATED' => 'USER_PASSWORD_RESET_TOKEN_CREATED',
        'COL_USER_PASSWORD_RESET_TOKEN_CREATED' => 'USER_PASSWORD_RESET_TOKEN_CREATED',
        'user_password_reset_token_created' => 'USER_PASSWORD_RESET_TOKEN_CREATED',
        'axys_users.user_password_reset_token_created' => 'USER_PASSWORD_RESET_TOKEN_CREATED',
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
        $this->addColumn('facebook_uid', 'FacebookUid', 'INTEGER', false, null, null);
        $this->addColumn('user_screen_name', 'Username', 'VARCHAR', false, 128, null);
        $this->addColumn('user_slug', 'Slug', 'VARCHAR', false, 32, null);
        $this->addColumn('user_wishlist_ship', 'WishlistShip', 'BOOLEAN', false, 1, false);
        $this->addColumn('user_top', 'Top', 'BOOLEAN', false, 1, null);
        $this->addColumn('user_biblio', 'Biblio', 'BOOLEAN', false, 1, null);
        $this->addColumn('adresse_ip', 'AdresseIp', 'VARCHAR', false, 255, null);
        $this->addColumn('recaptcha_score', 'RecaptchaScore', 'FLOAT', false, null, null);
        $this->addColumn('DateInscription', 'Dateinscription', 'TIMESTAMP', false, null, null);
        $this->addColumn('DateConnexion', 'Dateconnexion', 'TIMESTAMP', false, null, null);
        $this->addColumn('publisher_id', 'PublisherId', 'INTEGER', false, 10, null);
        $this->addColumn('bookshop_id', 'BookshopId', 'INTEGER', false, 10, null);
        $this->addColumn('library_id', 'LibraryId', 'INTEGER', false, 10, null);
        $this->addColumn('user_civilite', 'Civilite', 'LONGVARCHAR', false, null, null);
        $this->addColumn('user_nom', 'Nom', 'LONGVARCHAR', false, null, null);
        $this->addColumn('user_prenom', 'Prenom', 'LONGVARCHAR', false, null, null);
        $this->addColumn('user_adresse1', 'Adresse1', 'LONGVARCHAR', false, null, null);
        $this->addColumn('user_adresse2', 'Adresse2', 'LONGVARCHAR', false, null, null);
        $this->addColumn('user_codepostal', 'Codepostal', 'LONGVARCHAR', false, null, null);
        $this->addColumn('user_ville', 'Ville', 'LONGVARCHAR', false, null, null);
        $this->addColumn('user_pays', 'Pays', 'LONGVARCHAR', false, null, null);
        $this->addColumn('user_telephone', 'Telephone', 'LONGVARCHAR', false, null, null);
        $this->addColumn('user_pref_articles_show', 'PrefArticlesShow', 'VARCHAR', false, 8, null);
        $this->addColumn('user_fb_id', 'FbId', 'BIGINT', false, null, null);
        $this->addColumn('user_fb_token', 'FbToken', 'VARCHAR', false, 256, null);
        $this->addColumn('country_id', 'CountryId', 'INTEGER', false, 10, null);
        $this->addColumn('user_password_reset_token', 'PasswordResetToken', 'VARCHAR', false, 32, null);
        $this->addColumn('user_password_reset_token_created', 'PasswordResetTokenCreated', 'TIMESTAMP', false, null, null);
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
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'Carts', false);
        $this->addRelation('Option', '\\Model\\Option', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'Options', false);
        $this->addRelation('Right', '\\Model\\Right', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'Rights', false);
        $this->addRelation('Session', '\\Model\\Session', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'Sessions', false);
        $this->addRelation('Stock', '\\Model\\Stock', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'Stocks', false);
        $this->addRelation('Wish', '\\Model\\Wish', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'Wishes', false);
        $this->addRelation('Wishlist', '\\Model\\Wishlist', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
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
            $criteria->addSelectColumn(AxysUserTableMap::COL_FACEBOOK_UID);
            $criteria->addSelectColumn(AxysUserTableMap::COL_USER_SCREEN_NAME);
            $criteria->addSelectColumn(AxysUserTableMap::COL_USER_SLUG);
            $criteria->addSelectColumn(AxysUserTableMap::COL_USER_WISHLIST_SHIP);
            $criteria->addSelectColumn(AxysUserTableMap::COL_USER_TOP);
            $criteria->addSelectColumn(AxysUserTableMap::COL_USER_BIBLIO);
            $criteria->addSelectColumn(AxysUserTableMap::COL_ADRESSE_IP);
            $criteria->addSelectColumn(AxysUserTableMap::COL_RECAPTCHA_SCORE);
            $criteria->addSelectColumn(AxysUserTableMap::COL_DATEINSCRIPTION);
            $criteria->addSelectColumn(AxysUserTableMap::COL_DATECONNEXION);
            $criteria->addSelectColumn(AxysUserTableMap::COL_PUBLISHER_ID);
            $criteria->addSelectColumn(AxysUserTableMap::COL_BOOKSHOP_ID);
            $criteria->addSelectColumn(AxysUserTableMap::COL_LIBRARY_ID);
            $criteria->addSelectColumn(AxysUserTableMap::COL_USER_CIVILITE);
            $criteria->addSelectColumn(AxysUserTableMap::COL_USER_NOM);
            $criteria->addSelectColumn(AxysUserTableMap::COL_USER_PRENOM);
            $criteria->addSelectColumn(AxysUserTableMap::COL_USER_ADRESSE1);
            $criteria->addSelectColumn(AxysUserTableMap::COL_USER_ADRESSE2);
            $criteria->addSelectColumn(AxysUserTableMap::COL_USER_CODEPOSTAL);
            $criteria->addSelectColumn(AxysUserTableMap::COL_USER_VILLE);
            $criteria->addSelectColumn(AxysUserTableMap::COL_USER_PAYS);
            $criteria->addSelectColumn(AxysUserTableMap::COL_USER_TELEPHONE);
            $criteria->addSelectColumn(AxysUserTableMap::COL_USER_PREF_ARTICLES_SHOW);
            $criteria->addSelectColumn(AxysUserTableMap::COL_USER_FB_ID);
            $criteria->addSelectColumn(AxysUserTableMap::COL_USER_FB_TOKEN);
            $criteria->addSelectColumn(AxysUserTableMap::COL_COUNTRY_ID);
            $criteria->addSelectColumn(AxysUserTableMap::COL_USER_PASSWORD_RESET_TOKEN);
            $criteria->addSelectColumn(AxysUserTableMap::COL_USER_PASSWORD_RESET_TOKEN_CREATED);
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
            $criteria->addSelectColumn($alias . '.facebook_uid');
            $criteria->addSelectColumn($alias . '.user_screen_name');
            $criteria->addSelectColumn($alias . '.user_slug');
            $criteria->addSelectColumn($alias . '.user_wishlist_ship');
            $criteria->addSelectColumn($alias . '.user_top');
            $criteria->addSelectColumn($alias . '.user_biblio');
            $criteria->addSelectColumn($alias . '.adresse_ip');
            $criteria->addSelectColumn($alias . '.recaptcha_score');
            $criteria->addSelectColumn($alias . '.DateInscription');
            $criteria->addSelectColumn($alias . '.DateConnexion');
            $criteria->addSelectColumn($alias . '.publisher_id');
            $criteria->addSelectColumn($alias . '.bookshop_id');
            $criteria->addSelectColumn($alias . '.library_id');
            $criteria->addSelectColumn($alias . '.user_civilite');
            $criteria->addSelectColumn($alias . '.user_nom');
            $criteria->addSelectColumn($alias . '.user_prenom');
            $criteria->addSelectColumn($alias . '.user_adresse1');
            $criteria->addSelectColumn($alias . '.user_adresse2');
            $criteria->addSelectColumn($alias . '.user_codepostal');
            $criteria->addSelectColumn($alias . '.user_ville');
            $criteria->addSelectColumn($alias . '.user_pays');
            $criteria->addSelectColumn($alias . '.user_telephone');
            $criteria->addSelectColumn($alias . '.user_pref_articles_show');
            $criteria->addSelectColumn($alias . '.user_fb_id');
            $criteria->addSelectColumn($alias . '.user_fb_token');
            $criteria->addSelectColumn($alias . '.country_id');
            $criteria->addSelectColumn($alias . '.user_password_reset_token');
            $criteria->addSelectColumn($alias . '.user_password_reset_token_created');
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
            $criteria->removeSelectColumn(AxysUserTableMap::COL_FACEBOOK_UID);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_USER_SCREEN_NAME);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_USER_SLUG);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_USER_WISHLIST_SHIP);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_USER_TOP);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_USER_BIBLIO);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_ADRESSE_IP);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_RECAPTCHA_SCORE);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_DATEINSCRIPTION);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_DATECONNEXION);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_PUBLISHER_ID);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_BOOKSHOP_ID);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_LIBRARY_ID);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_USER_CIVILITE);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_USER_NOM);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_USER_PRENOM);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_USER_ADRESSE1);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_USER_ADRESSE2);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_USER_CODEPOSTAL);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_USER_VILLE);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_USER_PAYS);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_USER_TELEPHONE);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_USER_PREF_ARTICLES_SHOW);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_USER_FB_ID);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_USER_FB_TOKEN);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_COUNTRY_ID);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_USER_PASSWORD_RESET_TOKEN);
            $criteria->removeSelectColumn(AxysUserTableMap::COL_USER_PASSWORD_RESET_TOKEN_CREATED);
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
            $criteria->removeSelectColumn($alias . '.facebook_uid');
            $criteria->removeSelectColumn($alias . '.user_screen_name');
            $criteria->removeSelectColumn($alias . '.user_slug');
            $criteria->removeSelectColumn($alias . '.user_wishlist_ship');
            $criteria->removeSelectColumn($alias . '.user_top');
            $criteria->removeSelectColumn($alias . '.user_biblio');
            $criteria->removeSelectColumn($alias . '.adresse_ip');
            $criteria->removeSelectColumn($alias . '.recaptcha_score');
            $criteria->removeSelectColumn($alias . '.DateInscription');
            $criteria->removeSelectColumn($alias . '.DateConnexion');
            $criteria->removeSelectColumn($alias . '.publisher_id');
            $criteria->removeSelectColumn($alias . '.bookshop_id');
            $criteria->removeSelectColumn($alias . '.library_id');
            $criteria->removeSelectColumn($alias . '.user_civilite');
            $criteria->removeSelectColumn($alias . '.user_nom');
            $criteria->removeSelectColumn($alias . '.user_prenom');
            $criteria->removeSelectColumn($alias . '.user_adresse1');
            $criteria->removeSelectColumn($alias . '.user_adresse2');
            $criteria->removeSelectColumn($alias . '.user_codepostal');
            $criteria->removeSelectColumn($alias . '.user_ville');
            $criteria->removeSelectColumn($alias . '.user_pays');
            $criteria->removeSelectColumn($alias . '.user_telephone');
            $criteria->removeSelectColumn($alias . '.user_pref_articles_show');
            $criteria->removeSelectColumn($alias . '.user_fb_id');
            $criteria->removeSelectColumn($alias . '.user_fb_token');
            $criteria->removeSelectColumn($alias . '.country_id');
            $criteria->removeSelectColumn($alias . '.user_password_reset_token');
            $criteria->removeSelectColumn($alias . '.user_password_reset_token_created');
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