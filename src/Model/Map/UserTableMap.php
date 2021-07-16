<?php

namespace Model\Map;

use Model\User;
use Model\UserQuery;
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
 * This class defines the structure of the 'users' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class UserTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Model.Map.UserTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'users';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\User';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.User';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 38;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 38;

    /**
     * the column name for the id field
     */
    const COL_ID = 'users.id';

    /**
     * the column name for the Email field
     */
    const COL_EMAIL = 'users.Email';

    /**
     * the column name for the user_password field
     */
    const COL_USER_PASSWORD = 'users.user_password';

    /**
     * the column name for the user_key field
     */
    const COL_USER_KEY = 'users.user_key';

    /**
     * the column name for the email_key field
     */
    const COL_EMAIL_KEY = 'users.email_key';

    /**
     * the column name for the facebook_uid field
     */
    const COL_FACEBOOK_UID = 'users.facebook_uid';

    /**
     * the column name for the user_screen_name field
     */
    const COL_USER_SCREEN_NAME = 'users.user_screen_name';

    /**
     * the column name for the user_slug field
     */
    const COL_USER_SLUG = 'users.user_slug';

    /**
     * the column name for the user_wishlist_ship field
     */
    const COL_USER_WISHLIST_SHIP = 'users.user_wishlist_ship';

    /**
     * the column name for the user_top field
     */
    const COL_USER_TOP = 'users.user_top';

    /**
     * the column name for the user_biblio field
     */
    const COL_USER_BIBLIO = 'users.user_biblio';

    /**
     * the column name for the adresse_ip field
     */
    const COL_ADRESSE_IP = 'users.adresse_ip';

    /**
     * the column name for the recaptcha_score field
     */
    const COL_RECAPTCHA_SCORE = 'users.recaptcha_score';

    /**
     * the column name for the DateInscription field
     */
    const COL_DATEINSCRIPTION = 'users.DateInscription';

    /**
     * the column name for the DateConnexion field
     */
    const COL_DATECONNEXION = 'users.DateConnexion';

    /**
     * the column name for the publisher_id field
     */
    const COL_PUBLISHER_ID = 'users.publisher_id';

    /**
     * the column name for the bookshop_id field
     */
    const COL_BOOKSHOP_ID = 'users.bookshop_id';

    /**
     * the column name for the library_id field
     */
    const COL_LIBRARY_ID = 'users.library_id';

    /**
     * the column name for the user_civilite field
     */
    const COL_USER_CIVILITE = 'users.user_civilite';

    /**
     * the column name for the user_nom field
     */
    const COL_USER_NOM = 'users.user_nom';

    /**
     * the column name for the user_prenom field
     */
    const COL_USER_PRENOM = 'users.user_prenom';

    /**
     * the column name for the user_adresse1 field
     */
    const COL_USER_ADRESSE1 = 'users.user_adresse1';

    /**
     * the column name for the user_adresse2 field
     */
    const COL_USER_ADRESSE2 = 'users.user_adresse2';

    /**
     * the column name for the user_codepostal field
     */
    const COL_USER_CODEPOSTAL = 'users.user_codepostal';

    /**
     * the column name for the user_ville field
     */
    const COL_USER_VILLE = 'users.user_ville';

    /**
     * the column name for the user_pays field
     */
    const COL_USER_PAYS = 'users.user_pays';

    /**
     * the column name for the user_telephone field
     */
    const COL_USER_TELEPHONE = 'users.user_telephone';

    /**
     * the column name for the user_pref_articles_show field
     */
    const COL_USER_PREF_ARTICLES_SHOW = 'users.user_pref_articles_show';

    /**
     * the column name for the user_fb_id field
     */
    const COL_USER_FB_ID = 'users.user_fb_id';

    /**
     * the column name for the user_fb_token field
     */
    const COL_USER_FB_TOKEN = 'users.user_fb_token';

    /**
     * the column name for the country_id field
     */
    const COL_COUNTRY_ID = 'users.country_id';

    /**
     * the column name for the user_password_reset_token field
     */
    const COL_USER_PASSWORD_RESET_TOKEN = 'users.user_password_reset_token';

    /**
     * the column name for the user_password_reset_token_created field
     */
    const COL_USER_PASSWORD_RESET_TOKEN_CREATED = 'users.user_password_reset_token_created';

    /**
     * the column name for the user_update field
     */
    const COL_USER_UPDATE = 'users.user_update';

    /**
     * the column name for the user_created field
     */
    const COL_USER_CREATED = 'users.user_created';

    /**
     * the column name for the user_updated field
     */
    const COL_USER_UPDATED = 'users.user_updated';

    /**
     * the column name for the user_deleted field
     */
    const COL_USER_DELETED = 'users.user_deleted';

    /**
     * the column name for the user_deleted_why field
     */
    const COL_USER_DELETED_WHY = 'users.user_deleted_why';

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
        self::TYPE_PHPNAME       => array('Id', 'Email', 'Password', 'Key', 'EmailKey', 'FacebookUid', 'Username', 'Slug', 'WishlistShip', 'Top', 'Biblio', 'AdresseIp', 'RecaptchaScore', 'Dateinscription', 'Dateconnexion', 'PublisherId', 'BookshopId', 'LibraryId', 'Civilite', 'Nom', 'Prenom', 'Adresse1', 'Adresse2', 'Codepostal', 'Ville', 'Pays', 'Telephone', 'PrefArticlesShow', 'FbId', 'FbToken', 'CountryId', 'PasswordResetToken', 'PasswordResetTokenCreated', 'Update', 'CreatedAt', 'UpdatedAt', 'DeletedAt', 'DeletedWhy', ),
        self::TYPE_CAMELNAME     => array('id', 'email', 'password', 'key', 'emailKey', 'facebookUid', 'username', 'slug', 'wishlistShip', 'top', 'biblio', 'adresseIp', 'recaptchaScore', 'dateinscription', 'dateconnexion', 'publisherId', 'bookshopId', 'libraryId', 'civilite', 'nom', 'prenom', 'adresse1', 'adresse2', 'codepostal', 'ville', 'pays', 'telephone', 'prefArticlesShow', 'fbId', 'fbToken', 'countryId', 'passwordResetToken', 'passwordResetTokenCreated', 'update', 'createdAt', 'updatedAt', 'deletedAt', 'deletedWhy', ),
        self::TYPE_COLNAME       => array(UserTableMap::COL_ID, UserTableMap::COL_EMAIL, UserTableMap::COL_USER_PASSWORD, UserTableMap::COL_USER_KEY, UserTableMap::COL_EMAIL_KEY, UserTableMap::COL_FACEBOOK_UID, UserTableMap::COL_USER_SCREEN_NAME, UserTableMap::COL_USER_SLUG, UserTableMap::COL_USER_WISHLIST_SHIP, UserTableMap::COL_USER_TOP, UserTableMap::COL_USER_BIBLIO, UserTableMap::COL_ADRESSE_IP, UserTableMap::COL_RECAPTCHA_SCORE, UserTableMap::COL_DATEINSCRIPTION, UserTableMap::COL_DATECONNEXION, UserTableMap::COL_PUBLISHER_ID, UserTableMap::COL_BOOKSHOP_ID, UserTableMap::COL_LIBRARY_ID, UserTableMap::COL_USER_CIVILITE, UserTableMap::COL_USER_NOM, UserTableMap::COL_USER_PRENOM, UserTableMap::COL_USER_ADRESSE1, UserTableMap::COL_USER_ADRESSE2, UserTableMap::COL_USER_CODEPOSTAL, UserTableMap::COL_USER_VILLE, UserTableMap::COL_USER_PAYS, UserTableMap::COL_USER_TELEPHONE, UserTableMap::COL_USER_PREF_ARTICLES_SHOW, UserTableMap::COL_USER_FB_ID, UserTableMap::COL_USER_FB_TOKEN, UserTableMap::COL_COUNTRY_ID, UserTableMap::COL_USER_PASSWORD_RESET_TOKEN, UserTableMap::COL_USER_PASSWORD_RESET_TOKEN_CREATED, UserTableMap::COL_USER_UPDATE, UserTableMap::COL_USER_CREATED, UserTableMap::COL_USER_UPDATED, UserTableMap::COL_USER_DELETED, UserTableMap::COL_USER_DELETED_WHY, ),
        self::TYPE_FIELDNAME     => array('id', 'Email', 'user_password', 'user_key', 'email_key', 'facebook_uid', 'user_screen_name', 'user_slug', 'user_wishlist_ship', 'user_top', 'user_biblio', 'adresse_ip', 'recaptcha_score', 'DateInscription', 'DateConnexion', 'publisher_id', 'bookshop_id', 'library_id', 'user_civilite', 'user_nom', 'user_prenom', 'user_adresse1', 'user_adresse2', 'user_codepostal', 'user_ville', 'user_pays', 'user_telephone', 'user_pref_articles_show', 'user_fb_id', 'user_fb_token', 'country_id', 'user_password_reset_token', 'user_password_reset_token_created', 'user_update', 'user_created', 'user_updated', 'user_deleted', 'user_deleted_why', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Email' => 1, 'Password' => 2, 'Key' => 3, 'EmailKey' => 4, 'FacebookUid' => 5, 'Username' => 6, 'Slug' => 7, 'WishlistShip' => 8, 'Top' => 9, 'Biblio' => 10, 'AdresseIp' => 11, 'RecaptchaScore' => 12, 'Dateinscription' => 13, 'Dateconnexion' => 14, 'PublisherId' => 15, 'BookshopId' => 16, 'LibraryId' => 17, 'Civilite' => 18, 'Nom' => 19, 'Prenom' => 20, 'Adresse1' => 21, 'Adresse2' => 22, 'Codepostal' => 23, 'Ville' => 24, 'Pays' => 25, 'Telephone' => 26, 'PrefArticlesShow' => 27, 'FbId' => 28, 'FbToken' => 29, 'CountryId' => 30, 'PasswordResetToken' => 31, 'PasswordResetTokenCreated' => 32, 'Update' => 33, 'CreatedAt' => 34, 'UpdatedAt' => 35, 'DeletedAt' => 36, 'DeletedWhy' => 37, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'email' => 1, 'password' => 2, 'key' => 3, 'emailKey' => 4, 'facebookUid' => 5, 'username' => 6, 'slug' => 7, 'wishlistShip' => 8, 'top' => 9, 'biblio' => 10, 'adresseIp' => 11, 'recaptchaScore' => 12, 'dateinscription' => 13, 'dateconnexion' => 14, 'publisherId' => 15, 'bookshopId' => 16, 'libraryId' => 17, 'civilite' => 18, 'nom' => 19, 'prenom' => 20, 'adresse1' => 21, 'adresse2' => 22, 'codepostal' => 23, 'ville' => 24, 'pays' => 25, 'telephone' => 26, 'prefArticlesShow' => 27, 'fbId' => 28, 'fbToken' => 29, 'countryId' => 30, 'passwordResetToken' => 31, 'passwordResetTokenCreated' => 32, 'update' => 33, 'createdAt' => 34, 'updatedAt' => 35, 'deletedAt' => 36, 'deletedWhy' => 37, ),
        self::TYPE_COLNAME       => array(UserTableMap::COL_ID => 0, UserTableMap::COL_EMAIL => 1, UserTableMap::COL_USER_PASSWORD => 2, UserTableMap::COL_USER_KEY => 3, UserTableMap::COL_EMAIL_KEY => 4, UserTableMap::COL_FACEBOOK_UID => 5, UserTableMap::COL_USER_SCREEN_NAME => 6, UserTableMap::COL_USER_SLUG => 7, UserTableMap::COL_USER_WISHLIST_SHIP => 8, UserTableMap::COL_USER_TOP => 9, UserTableMap::COL_USER_BIBLIO => 10, UserTableMap::COL_ADRESSE_IP => 11, UserTableMap::COL_RECAPTCHA_SCORE => 12, UserTableMap::COL_DATEINSCRIPTION => 13, UserTableMap::COL_DATECONNEXION => 14, UserTableMap::COL_PUBLISHER_ID => 15, UserTableMap::COL_BOOKSHOP_ID => 16, UserTableMap::COL_LIBRARY_ID => 17, UserTableMap::COL_USER_CIVILITE => 18, UserTableMap::COL_USER_NOM => 19, UserTableMap::COL_USER_PRENOM => 20, UserTableMap::COL_USER_ADRESSE1 => 21, UserTableMap::COL_USER_ADRESSE2 => 22, UserTableMap::COL_USER_CODEPOSTAL => 23, UserTableMap::COL_USER_VILLE => 24, UserTableMap::COL_USER_PAYS => 25, UserTableMap::COL_USER_TELEPHONE => 26, UserTableMap::COL_USER_PREF_ARTICLES_SHOW => 27, UserTableMap::COL_USER_FB_ID => 28, UserTableMap::COL_USER_FB_TOKEN => 29, UserTableMap::COL_COUNTRY_ID => 30, UserTableMap::COL_USER_PASSWORD_RESET_TOKEN => 31, UserTableMap::COL_USER_PASSWORD_RESET_TOKEN_CREATED => 32, UserTableMap::COL_USER_UPDATE => 33, UserTableMap::COL_USER_CREATED => 34, UserTableMap::COL_USER_UPDATED => 35, UserTableMap::COL_USER_DELETED => 36, UserTableMap::COL_USER_DELETED_WHY => 37, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'Email' => 1, 'user_password' => 2, 'user_key' => 3, 'email_key' => 4, 'facebook_uid' => 5, 'user_screen_name' => 6, 'user_slug' => 7, 'user_wishlist_ship' => 8, 'user_top' => 9, 'user_biblio' => 10, 'adresse_ip' => 11, 'recaptcha_score' => 12, 'DateInscription' => 13, 'DateConnexion' => 14, 'publisher_id' => 15, 'bookshop_id' => 16, 'library_id' => 17, 'user_civilite' => 18, 'user_nom' => 19, 'user_prenom' => 20, 'user_adresse1' => 21, 'user_adresse2' => 22, 'user_codepostal' => 23, 'user_ville' => 24, 'user_pays' => 25, 'user_telephone' => 26, 'user_pref_articles_show' => 27, 'user_fb_id' => 28, 'user_fb_token' => 29, 'country_id' => 30, 'user_password_reset_token' => 31, 'user_password_reset_token_created' => 32, 'user_update' => 33, 'user_created' => 34, 'user_updated' => 35, 'user_deleted' => 36, 'user_deleted_why' => 37, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, )
    );

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var string[]
     */
    protected $normalizedColumnNameMap = [

        'Id' => 'ID',
        'User.Id' => 'ID',
        'id' => 'ID',
        'user.id' => 'ID',
        'UserTableMap::COL_ID' => 'ID',
        'COL_ID' => 'ID',
        'id' => 'ID',
        'users.id' => 'ID',
        'Email' => 'EMAIL',
        'User.Email' => 'EMAIL',
        'email' => 'EMAIL',
        'user.email' => 'EMAIL',
        'UserTableMap::COL_EMAIL' => 'EMAIL',
        'COL_EMAIL' => 'EMAIL',
        'Email' => 'EMAIL',
        'users.Email' => 'EMAIL',
        'Password' => 'USER_PASSWORD',
        'User.Password' => 'USER_PASSWORD',
        'password' => 'USER_PASSWORD',
        'user.password' => 'USER_PASSWORD',
        'UserTableMap::COL_USER_PASSWORD' => 'USER_PASSWORD',
        'COL_USER_PASSWORD' => 'USER_PASSWORD',
        'user_password' => 'USER_PASSWORD',
        'users.user_password' => 'USER_PASSWORD',
        'Key' => 'USER_KEY',
        'User.Key' => 'USER_KEY',
        'key' => 'USER_KEY',
        'user.key' => 'USER_KEY',
        'UserTableMap::COL_USER_KEY' => 'USER_KEY',
        'COL_USER_KEY' => 'USER_KEY',
        'user_key' => 'USER_KEY',
        'users.user_key' => 'USER_KEY',
        'EmailKey' => 'EMAIL_KEY',
        'User.EmailKey' => 'EMAIL_KEY',
        'emailKey' => 'EMAIL_KEY',
        'user.emailKey' => 'EMAIL_KEY',
        'UserTableMap::COL_EMAIL_KEY' => 'EMAIL_KEY',
        'COL_EMAIL_KEY' => 'EMAIL_KEY',
        'email_key' => 'EMAIL_KEY',
        'users.email_key' => 'EMAIL_KEY',
        'FacebookUid' => 'FACEBOOK_UID',
        'User.FacebookUid' => 'FACEBOOK_UID',
        'facebookUid' => 'FACEBOOK_UID',
        'user.facebookUid' => 'FACEBOOK_UID',
        'UserTableMap::COL_FACEBOOK_UID' => 'FACEBOOK_UID',
        'COL_FACEBOOK_UID' => 'FACEBOOK_UID',
        'facebook_uid' => 'FACEBOOK_UID',
        'users.facebook_uid' => 'FACEBOOK_UID',
        'Username' => 'USER_SCREEN_NAME',
        'User.Username' => 'USER_SCREEN_NAME',
        'username' => 'USER_SCREEN_NAME',
        'user.username' => 'USER_SCREEN_NAME',
        'UserTableMap::COL_USER_SCREEN_NAME' => 'USER_SCREEN_NAME',
        'COL_USER_SCREEN_NAME' => 'USER_SCREEN_NAME',
        'user_screen_name' => 'USER_SCREEN_NAME',
        'users.user_screen_name' => 'USER_SCREEN_NAME',
        'Slug' => 'USER_SLUG',
        'User.Slug' => 'USER_SLUG',
        'slug' => 'USER_SLUG',
        'user.slug' => 'USER_SLUG',
        'UserTableMap::COL_USER_SLUG' => 'USER_SLUG',
        'COL_USER_SLUG' => 'USER_SLUG',
        'user_slug' => 'USER_SLUG',
        'users.user_slug' => 'USER_SLUG',
        'WishlistShip' => 'USER_WISHLIST_SHIP',
        'User.WishlistShip' => 'USER_WISHLIST_SHIP',
        'wishlistShip' => 'USER_WISHLIST_SHIP',
        'user.wishlistShip' => 'USER_WISHLIST_SHIP',
        'UserTableMap::COL_USER_WISHLIST_SHIP' => 'USER_WISHLIST_SHIP',
        'COL_USER_WISHLIST_SHIP' => 'USER_WISHLIST_SHIP',
        'user_wishlist_ship' => 'USER_WISHLIST_SHIP',
        'users.user_wishlist_ship' => 'USER_WISHLIST_SHIP',
        'Top' => 'USER_TOP',
        'User.Top' => 'USER_TOP',
        'top' => 'USER_TOP',
        'user.top' => 'USER_TOP',
        'UserTableMap::COL_USER_TOP' => 'USER_TOP',
        'COL_USER_TOP' => 'USER_TOP',
        'user_top' => 'USER_TOP',
        'users.user_top' => 'USER_TOP',
        'Biblio' => 'USER_BIBLIO',
        'User.Biblio' => 'USER_BIBLIO',
        'biblio' => 'USER_BIBLIO',
        'user.biblio' => 'USER_BIBLIO',
        'UserTableMap::COL_USER_BIBLIO' => 'USER_BIBLIO',
        'COL_USER_BIBLIO' => 'USER_BIBLIO',
        'user_biblio' => 'USER_BIBLIO',
        'users.user_biblio' => 'USER_BIBLIO',
        'AdresseIp' => 'ADRESSE_IP',
        'User.AdresseIp' => 'ADRESSE_IP',
        'adresseIp' => 'ADRESSE_IP',
        'user.adresseIp' => 'ADRESSE_IP',
        'UserTableMap::COL_ADRESSE_IP' => 'ADRESSE_IP',
        'COL_ADRESSE_IP' => 'ADRESSE_IP',
        'adresse_ip' => 'ADRESSE_IP',
        'users.adresse_ip' => 'ADRESSE_IP',
        'RecaptchaScore' => 'RECAPTCHA_SCORE',
        'User.RecaptchaScore' => 'RECAPTCHA_SCORE',
        'recaptchaScore' => 'RECAPTCHA_SCORE',
        'user.recaptchaScore' => 'RECAPTCHA_SCORE',
        'UserTableMap::COL_RECAPTCHA_SCORE' => 'RECAPTCHA_SCORE',
        'COL_RECAPTCHA_SCORE' => 'RECAPTCHA_SCORE',
        'recaptcha_score' => 'RECAPTCHA_SCORE',
        'users.recaptcha_score' => 'RECAPTCHA_SCORE',
        'Dateinscription' => 'DATEINSCRIPTION',
        'User.Dateinscription' => 'DATEINSCRIPTION',
        'dateinscription' => 'DATEINSCRIPTION',
        'user.dateinscription' => 'DATEINSCRIPTION',
        'UserTableMap::COL_DATEINSCRIPTION' => 'DATEINSCRIPTION',
        'COL_DATEINSCRIPTION' => 'DATEINSCRIPTION',
        'DateInscription' => 'DATEINSCRIPTION',
        'users.DateInscription' => 'DATEINSCRIPTION',
        'Dateconnexion' => 'DATECONNEXION',
        'User.Dateconnexion' => 'DATECONNEXION',
        'dateconnexion' => 'DATECONNEXION',
        'user.dateconnexion' => 'DATECONNEXION',
        'UserTableMap::COL_DATECONNEXION' => 'DATECONNEXION',
        'COL_DATECONNEXION' => 'DATECONNEXION',
        'DateConnexion' => 'DATECONNEXION',
        'users.DateConnexion' => 'DATECONNEXION',
        'PublisherId' => 'PUBLISHER_ID',
        'User.PublisherId' => 'PUBLISHER_ID',
        'publisherId' => 'PUBLISHER_ID',
        'user.publisherId' => 'PUBLISHER_ID',
        'UserTableMap::COL_PUBLISHER_ID' => 'PUBLISHER_ID',
        'COL_PUBLISHER_ID' => 'PUBLISHER_ID',
        'publisher_id' => 'PUBLISHER_ID',
        'users.publisher_id' => 'PUBLISHER_ID',
        'BookshopId' => 'BOOKSHOP_ID',
        'User.BookshopId' => 'BOOKSHOP_ID',
        'bookshopId' => 'BOOKSHOP_ID',
        'user.bookshopId' => 'BOOKSHOP_ID',
        'UserTableMap::COL_BOOKSHOP_ID' => 'BOOKSHOP_ID',
        'COL_BOOKSHOP_ID' => 'BOOKSHOP_ID',
        'bookshop_id' => 'BOOKSHOP_ID',
        'users.bookshop_id' => 'BOOKSHOP_ID',
        'LibraryId' => 'LIBRARY_ID',
        'User.LibraryId' => 'LIBRARY_ID',
        'libraryId' => 'LIBRARY_ID',
        'user.libraryId' => 'LIBRARY_ID',
        'UserTableMap::COL_LIBRARY_ID' => 'LIBRARY_ID',
        'COL_LIBRARY_ID' => 'LIBRARY_ID',
        'library_id' => 'LIBRARY_ID',
        'users.library_id' => 'LIBRARY_ID',
        'Civilite' => 'USER_CIVILITE',
        'User.Civilite' => 'USER_CIVILITE',
        'civilite' => 'USER_CIVILITE',
        'user.civilite' => 'USER_CIVILITE',
        'UserTableMap::COL_USER_CIVILITE' => 'USER_CIVILITE',
        'COL_USER_CIVILITE' => 'USER_CIVILITE',
        'user_civilite' => 'USER_CIVILITE',
        'users.user_civilite' => 'USER_CIVILITE',
        'Nom' => 'USER_NOM',
        'User.Nom' => 'USER_NOM',
        'nom' => 'USER_NOM',
        'user.nom' => 'USER_NOM',
        'UserTableMap::COL_USER_NOM' => 'USER_NOM',
        'COL_USER_NOM' => 'USER_NOM',
        'user_nom' => 'USER_NOM',
        'users.user_nom' => 'USER_NOM',
        'Prenom' => 'USER_PRENOM',
        'User.Prenom' => 'USER_PRENOM',
        'prenom' => 'USER_PRENOM',
        'user.prenom' => 'USER_PRENOM',
        'UserTableMap::COL_USER_PRENOM' => 'USER_PRENOM',
        'COL_USER_PRENOM' => 'USER_PRENOM',
        'user_prenom' => 'USER_PRENOM',
        'users.user_prenom' => 'USER_PRENOM',
        'Adresse1' => 'USER_ADRESSE1',
        'User.Adresse1' => 'USER_ADRESSE1',
        'adresse1' => 'USER_ADRESSE1',
        'user.adresse1' => 'USER_ADRESSE1',
        'UserTableMap::COL_USER_ADRESSE1' => 'USER_ADRESSE1',
        'COL_USER_ADRESSE1' => 'USER_ADRESSE1',
        'user_adresse1' => 'USER_ADRESSE1',
        'users.user_adresse1' => 'USER_ADRESSE1',
        'Adresse2' => 'USER_ADRESSE2',
        'User.Adresse2' => 'USER_ADRESSE2',
        'adresse2' => 'USER_ADRESSE2',
        'user.adresse2' => 'USER_ADRESSE2',
        'UserTableMap::COL_USER_ADRESSE2' => 'USER_ADRESSE2',
        'COL_USER_ADRESSE2' => 'USER_ADRESSE2',
        'user_adresse2' => 'USER_ADRESSE2',
        'users.user_adresse2' => 'USER_ADRESSE2',
        'Codepostal' => 'USER_CODEPOSTAL',
        'User.Codepostal' => 'USER_CODEPOSTAL',
        'codepostal' => 'USER_CODEPOSTAL',
        'user.codepostal' => 'USER_CODEPOSTAL',
        'UserTableMap::COL_USER_CODEPOSTAL' => 'USER_CODEPOSTAL',
        'COL_USER_CODEPOSTAL' => 'USER_CODEPOSTAL',
        'user_codepostal' => 'USER_CODEPOSTAL',
        'users.user_codepostal' => 'USER_CODEPOSTAL',
        'Ville' => 'USER_VILLE',
        'User.Ville' => 'USER_VILLE',
        'ville' => 'USER_VILLE',
        'user.ville' => 'USER_VILLE',
        'UserTableMap::COL_USER_VILLE' => 'USER_VILLE',
        'COL_USER_VILLE' => 'USER_VILLE',
        'user_ville' => 'USER_VILLE',
        'users.user_ville' => 'USER_VILLE',
        'Pays' => 'USER_PAYS',
        'User.Pays' => 'USER_PAYS',
        'pays' => 'USER_PAYS',
        'user.pays' => 'USER_PAYS',
        'UserTableMap::COL_USER_PAYS' => 'USER_PAYS',
        'COL_USER_PAYS' => 'USER_PAYS',
        'user_pays' => 'USER_PAYS',
        'users.user_pays' => 'USER_PAYS',
        'Telephone' => 'USER_TELEPHONE',
        'User.Telephone' => 'USER_TELEPHONE',
        'telephone' => 'USER_TELEPHONE',
        'user.telephone' => 'USER_TELEPHONE',
        'UserTableMap::COL_USER_TELEPHONE' => 'USER_TELEPHONE',
        'COL_USER_TELEPHONE' => 'USER_TELEPHONE',
        'user_telephone' => 'USER_TELEPHONE',
        'users.user_telephone' => 'USER_TELEPHONE',
        'PrefArticlesShow' => 'USER_PREF_ARTICLES_SHOW',
        'User.PrefArticlesShow' => 'USER_PREF_ARTICLES_SHOW',
        'prefArticlesShow' => 'USER_PREF_ARTICLES_SHOW',
        'user.prefArticlesShow' => 'USER_PREF_ARTICLES_SHOW',
        'UserTableMap::COL_USER_PREF_ARTICLES_SHOW' => 'USER_PREF_ARTICLES_SHOW',
        'COL_USER_PREF_ARTICLES_SHOW' => 'USER_PREF_ARTICLES_SHOW',
        'user_pref_articles_show' => 'USER_PREF_ARTICLES_SHOW',
        'users.user_pref_articles_show' => 'USER_PREF_ARTICLES_SHOW',
        'FbId' => 'USER_FB_ID',
        'User.FbId' => 'USER_FB_ID',
        'fbId' => 'USER_FB_ID',
        'user.fbId' => 'USER_FB_ID',
        'UserTableMap::COL_USER_FB_ID' => 'USER_FB_ID',
        'COL_USER_FB_ID' => 'USER_FB_ID',
        'user_fb_id' => 'USER_FB_ID',
        'users.user_fb_id' => 'USER_FB_ID',
        'FbToken' => 'USER_FB_TOKEN',
        'User.FbToken' => 'USER_FB_TOKEN',
        'fbToken' => 'USER_FB_TOKEN',
        'user.fbToken' => 'USER_FB_TOKEN',
        'UserTableMap::COL_USER_FB_TOKEN' => 'USER_FB_TOKEN',
        'COL_USER_FB_TOKEN' => 'USER_FB_TOKEN',
        'user_fb_token' => 'USER_FB_TOKEN',
        'users.user_fb_token' => 'USER_FB_TOKEN',
        'CountryId' => 'COUNTRY_ID',
        'User.CountryId' => 'COUNTRY_ID',
        'countryId' => 'COUNTRY_ID',
        'user.countryId' => 'COUNTRY_ID',
        'UserTableMap::COL_COUNTRY_ID' => 'COUNTRY_ID',
        'COL_COUNTRY_ID' => 'COUNTRY_ID',
        'country_id' => 'COUNTRY_ID',
        'users.country_id' => 'COUNTRY_ID',
        'PasswordResetToken' => 'USER_PASSWORD_RESET_TOKEN',
        'User.PasswordResetToken' => 'USER_PASSWORD_RESET_TOKEN',
        'passwordResetToken' => 'USER_PASSWORD_RESET_TOKEN',
        'user.passwordResetToken' => 'USER_PASSWORD_RESET_TOKEN',
        'UserTableMap::COL_USER_PASSWORD_RESET_TOKEN' => 'USER_PASSWORD_RESET_TOKEN',
        'COL_USER_PASSWORD_RESET_TOKEN' => 'USER_PASSWORD_RESET_TOKEN',
        'user_password_reset_token' => 'USER_PASSWORD_RESET_TOKEN',
        'users.user_password_reset_token' => 'USER_PASSWORD_RESET_TOKEN',
        'PasswordResetTokenCreated' => 'USER_PASSWORD_RESET_TOKEN_CREATED',
        'User.PasswordResetTokenCreated' => 'USER_PASSWORD_RESET_TOKEN_CREATED',
        'passwordResetTokenCreated' => 'USER_PASSWORD_RESET_TOKEN_CREATED',
        'user.passwordResetTokenCreated' => 'USER_PASSWORD_RESET_TOKEN_CREATED',
        'UserTableMap::COL_USER_PASSWORD_RESET_TOKEN_CREATED' => 'USER_PASSWORD_RESET_TOKEN_CREATED',
        'COL_USER_PASSWORD_RESET_TOKEN_CREATED' => 'USER_PASSWORD_RESET_TOKEN_CREATED',
        'user_password_reset_token_created' => 'USER_PASSWORD_RESET_TOKEN_CREATED',
        'users.user_password_reset_token_created' => 'USER_PASSWORD_RESET_TOKEN_CREATED',
        'Update' => 'USER_UPDATE',
        'User.Update' => 'USER_UPDATE',
        'update' => 'USER_UPDATE',
        'user.update' => 'USER_UPDATE',
        'UserTableMap::COL_USER_UPDATE' => 'USER_UPDATE',
        'COL_USER_UPDATE' => 'USER_UPDATE',
        'user_update' => 'USER_UPDATE',
        'users.user_update' => 'USER_UPDATE',
        'CreatedAt' => 'USER_CREATED',
        'User.CreatedAt' => 'USER_CREATED',
        'createdAt' => 'USER_CREATED',
        'user.createdAt' => 'USER_CREATED',
        'UserTableMap::COL_USER_CREATED' => 'USER_CREATED',
        'COL_USER_CREATED' => 'USER_CREATED',
        'user_created' => 'USER_CREATED',
        'users.user_created' => 'USER_CREATED',
        'UpdatedAt' => 'USER_UPDATED',
        'User.UpdatedAt' => 'USER_UPDATED',
        'updatedAt' => 'USER_UPDATED',
        'user.updatedAt' => 'USER_UPDATED',
        'UserTableMap::COL_USER_UPDATED' => 'USER_UPDATED',
        'COL_USER_UPDATED' => 'USER_UPDATED',
        'user_updated' => 'USER_UPDATED',
        'users.user_updated' => 'USER_UPDATED',
        'DeletedAt' => 'USER_DELETED',
        'User.DeletedAt' => 'USER_DELETED',
        'deletedAt' => 'USER_DELETED',
        'user.deletedAt' => 'USER_DELETED',
        'UserTableMap::COL_USER_DELETED' => 'USER_DELETED',
        'COL_USER_DELETED' => 'USER_DELETED',
        'user_deleted' => 'USER_DELETED',
        'users.user_deleted' => 'USER_DELETED',
        'DeletedWhy' => 'USER_DELETED_WHY',
        'User.DeletedWhy' => 'USER_DELETED_WHY',
        'deletedWhy' => 'USER_DELETED_WHY',
        'user.deletedWhy' => 'USER_DELETED_WHY',
        'UserTableMap::COL_USER_DELETED_WHY' => 'USER_DELETED_WHY',
        'COL_USER_DELETED_WHY' => 'USER_DELETED_WHY',
        'user_deleted_why' => 'USER_DELETED_WHY',
        'users.user_deleted_why' => 'USER_DELETED_WHY',
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
        $this->setName('users');
        $this->setPhpName('User');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\User');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
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
        $this->addColumn('user_deleted', 'DeletedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('user_deleted_why', 'DeletedWhy', 'VARCHAR', false, 1024, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
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
            'timestampable' => array('create_column' => 'user_created', 'update_column' => 'user_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false', ),
            'validate' => array('rule1' => array ('column' => 'email','validator' => 'Email',), ),
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
        return $withPrefix ? UserTableMap::CLASS_DEFAULT : UserTableMap::OM_CLASS;
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
     * @return array           (User object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = UserTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = UserTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + UserTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = UserTableMap::OM_CLASS;
            /** @var User $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            UserTableMap::addInstanceToPool($obj, $key);
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
            $key = UserTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = UserTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var User $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                UserTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(UserTableMap::COL_ID);
            $criteria->addSelectColumn(UserTableMap::COL_EMAIL);
            $criteria->addSelectColumn(UserTableMap::COL_USER_PASSWORD);
            $criteria->addSelectColumn(UserTableMap::COL_USER_KEY);
            $criteria->addSelectColumn(UserTableMap::COL_EMAIL_KEY);
            $criteria->addSelectColumn(UserTableMap::COL_FACEBOOK_UID);
            $criteria->addSelectColumn(UserTableMap::COL_USER_SCREEN_NAME);
            $criteria->addSelectColumn(UserTableMap::COL_USER_SLUG);
            $criteria->addSelectColumn(UserTableMap::COL_USER_WISHLIST_SHIP);
            $criteria->addSelectColumn(UserTableMap::COL_USER_TOP);
            $criteria->addSelectColumn(UserTableMap::COL_USER_BIBLIO);
            $criteria->addSelectColumn(UserTableMap::COL_ADRESSE_IP);
            $criteria->addSelectColumn(UserTableMap::COL_RECAPTCHA_SCORE);
            $criteria->addSelectColumn(UserTableMap::COL_DATEINSCRIPTION);
            $criteria->addSelectColumn(UserTableMap::COL_DATECONNEXION);
            $criteria->addSelectColumn(UserTableMap::COL_PUBLISHER_ID);
            $criteria->addSelectColumn(UserTableMap::COL_BOOKSHOP_ID);
            $criteria->addSelectColumn(UserTableMap::COL_LIBRARY_ID);
            $criteria->addSelectColumn(UserTableMap::COL_USER_CIVILITE);
            $criteria->addSelectColumn(UserTableMap::COL_USER_NOM);
            $criteria->addSelectColumn(UserTableMap::COL_USER_PRENOM);
            $criteria->addSelectColumn(UserTableMap::COL_USER_ADRESSE1);
            $criteria->addSelectColumn(UserTableMap::COL_USER_ADRESSE2);
            $criteria->addSelectColumn(UserTableMap::COL_USER_CODEPOSTAL);
            $criteria->addSelectColumn(UserTableMap::COL_USER_VILLE);
            $criteria->addSelectColumn(UserTableMap::COL_USER_PAYS);
            $criteria->addSelectColumn(UserTableMap::COL_USER_TELEPHONE);
            $criteria->addSelectColumn(UserTableMap::COL_USER_PREF_ARTICLES_SHOW);
            $criteria->addSelectColumn(UserTableMap::COL_USER_FB_ID);
            $criteria->addSelectColumn(UserTableMap::COL_USER_FB_TOKEN);
            $criteria->addSelectColumn(UserTableMap::COL_COUNTRY_ID);
            $criteria->addSelectColumn(UserTableMap::COL_USER_PASSWORD_RESET_TOKEN);
            $criteria->addSelectColumn(UserTableMap::COL_USER_PASSWORD_RESET_TOKEN_CREATED);
            $criteria->addSelectColumn(UserTableMap::COL_USER_UPDATE);
            $criteria->addSelectColumn(UserTableMap::COL_USER_CREATED);
            $criteria->addSelectColumn(UserTableMap::COL_USER_UPDATED);
            $criteria->addSelectColumn(UserTableMap::COL_USER_DELETED);
            $criteria->addSelectColumn(UserTableMap::COL_USER_DELETED_WHY);
        } else {
            $criteria->addSelectColumn($alias . '.id');
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
            $criteria->addSelectColumn($alias . '.user_deleted');
            $criteria->addSelectColumn($alias . '.user_deleted_why');
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
            $criteria->removeSelectColumn(UserTableMap::COL_ID);
            $criteria->removeSelectColumn(UserTableMap::COL_EMAIL);
            $criteria->removeSelectColumn(UserTableMap::COL_USER_PASSWORD);
            $criteria->removeSelectColumn(UserTableMap::COL_USER_KEY);
            $criteria->removeSelectColumn(UserTableMap::COL_EMAIL_KEY);
            $criteria->removeSelectColumn(UserTableMap::COL_FACEBOOK_UID);
            $criteria->removeSelectColumn(UserTableMap::COL_USER_SCREEN_NAME);
            $criteria->removeSelectColumn(UserTableMap::COL_USER_SLUG);
            $criteria->removeSelectColumn(UserTableMap::COL_USER_WISHLIST_SHIP);
            $criteria->removeSelectColumn(UserTableMap::COL_USER_TOP);
            $criteria->removeSelectColumn(UserTableMap::COL_USER_BIBLIO);
            $criteria->removeSelectColumn(UserTableMap::COL_ADRESSE_IP);
            $criteria->removeSelectColumn(UserTableMap::COL_RECAPTCHA_SCORE);
            $criteria->removeSelectColumn(UserTableMap::COL_DATEINSCRIPTION);
            $criteria->removeSelectColumn(UserTableMap::COL_DATECONNEXION);
            $criteria->removeSelectColumn(UserTableMap::COL_PUBLISHER_ID);
            $criteria->removeSelectColumn(UserTableMap::COL_BOOKSHOP_ID);
            $criteria->removeSelectColumn(UserTableMap::COL_LIBRARY_ID);
            $criteria->removeSelectColumn(UserTableMap::COL_USER_CIVILITE);
            $criteria->removeSelectColumn(UserTableMap::COL_USER_NOM);
            $criteria->removeSelectColumn(UserTableMap::COL_USER_PRENOM);
            $criteria->removeSelectColumn(UserTableMap::COL_USER_ADRESSE1);
            $criteria->removeSelectColumn(UserTableMap::COL_USER_ADRESSE2);
            $criteria->removeSelectColumn(UserTableMap::COL_USER_CODEPOSTAL);
            $criteria->removeSelectColumn(UserTableMap::COL_USER_VILLE);
            $criteria->removeSelectColumn(UserTableMap::COL_USER_PAYS);
            $criteria->removeSelectColumn(UserTableMap::COL_USER_TELEPHONE);
            $criteria->removeSelectColumn(UserTableMap::COL_USER_PREF_ARTICLES_SHOW);
            $criteria->removeSelectColumn(UserTableMap::COL_USER_FB_ID);
            $criteria->removeSelectColumn(UserTableMap::COL_USER_FB_TOKEN);
            $criteria->removeSelectColumn(UserTableMap::COL_COUNTRY_ID);
            $criteria->removeSelectColumn(UserTableMap::COL_USER_PASSWORD_RESET_TOKEN);
            $criteria->removeSelectColumn(UserTableMap::COL_USER_PASSWORD_RESET_TOKEN_CREATED);
            $criteria->removeSelectColumn(UserTableMap::COL_USER_UPDATE);
            $criteria->removeSelectColumn(UserTableMap::COL_USER_CREATED);
            $criteria->removeSelectColumn(UserTableMap::COL_USER_UPDATED);
            $criteria->removeSelectColumn(UserTableMap::COL_USER_DELETED);
            $criteria->removeSelectColumn(UserTableMap::COL_USER_DELETED_WHY);
        } else {
            $criteria->removeSelectColumn($alias . '.id');
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
            $criteria->removeSelectColumn($alias . '.user_deleted');
            $criteria->removeSelectColumn($alias . '.user_deleted_why');
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
        return Propel::getServiceContainer()->getDatabaseMap(UserTableMap::DATABASE_NAME)->getTable(UserTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(UserTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(UserTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new UserTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a User or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or User object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\User) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(UserTableMap::DATABASE_NAME);
            $criteria->add(UserTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = UserQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            UserTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                UserTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the users table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return UserQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a User or Criteria object.
     *
     * @param mixed               $criteria Criteria or User object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from User object
        }

        if ($criteria->containsKey(UserTableMap::COL_ID) && $criteria->keyContainsValue(UserTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.UserTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = UserQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // UserTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
UserTableMap::buildTableMap();
