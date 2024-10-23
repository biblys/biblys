<?php
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


namespace Model\Map;

use Model\Publisher;
use Model\PublisherQuery;
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
 * This class defines the structure of the 'publishers' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class PublisherTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.PublisherTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'publishers';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Publisher';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Publisher';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Publisher';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 38;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 38;

    /**
     * the column name for the publisher_id field
     */
    public const COL_PUBLISHER_ID = 'publishers.publisher_id';

    /**
     * the column name for the site_id field
     */
    public const COL_SITE_ID = 'publishers.site_id';

    /**
     * the column name for the publisher_name field
     */
    public const COL_PUBLISHER_NAME = 'publishers.publisher_name';

    /**
     * the column name for the publisher_name_alphabetic field
     */
    public const COL_PUBLISHER_NAME_ALPHABETIC = 'publishers.publisher_name_alphabetic';

    /**
     * the column name for the publisher_url field
     */
    public const COL_PUBLISHER_URL = 'publishers.publisher_url';

    /**
     * the column name for the publisher_noosfere_id field
     */
    public const COL_PUBLISHER_NOOSFERE_ID = 'publishers.publisher_noosfere_id';

    /**
     * the column name for the publisher_representative field
     */
    public const COL_PUBLISHER_REPRESENTATIVE = 'publishers.publisher_representative';

    /**
     * the column name for the publisher_address field
     */
    public const COL_PUBLISHER_ADDRESS = 'publishers.publisher_address';

    /**
     * the column name for the publisher_postal_code field
     */
    public const COL_PUBLISHER_POSTAL_CODE = 'publishers.publisher_postal_code';

    /**
     * the column name for the publisher_city field
     */
    public const COL_PUBLISHER_CITY = 'publishers.publisher_city';

    /**
     * the column name for the publisher_country field
     */
    public const COL_PUBLISHER_COUNTRY = 'publishers.publisher_country';

    /**
     * the column name for the publisher_phone field
     */
    public const COL_PUBLISHER_PHONE = 'publishers.publisher_phone';

    /**
     * the column name for the publisher_fax field
     */
    public const COL_PUBLISHER_FAX = 'publishers.publisher_fax';

    /**
     * the column name for the publisher_website field
     */
    public const COL_PUBLISHER_WEBSITE = 'publishers.publisher_website';

    /**
     * the column name for the publisher_buy_link field
     */
    public const COL_PUBLISHER_BUY_LINK = 'publishers.publisher_buy_link';

    /**
     * the column name for the publisher_email field
     */
    public const COL_PUBLISHER_EMAIL = 'publishers.publisher_email';

    /**
     * the column name for the publisher_facebook field
     */
    public const COL_PUBLISHER_FACEBOOK = 'publishers.publisher_facebook';

    /**
     * the column name for the publisher_twitter field
     */
    public const COL_PUBLISHER_TWITTER = 'publishers.publisher_twitter';

    /**
     * the column name for the publisher_legal_form field
     */
    public const COL_PUBLISHER_LEGAL_FORM = 'publishers.publisher_legal_form';

    /**
     * the column name for the publisher_creation_year field
     */
    public const COL_PUBLISHER_CREATION_YEAR = 'publishers.publisher_creation_year';

    /**
     * the column name for the publisher_isbn field
     */
    public const COL_PUBLISHER_ISBN = 'publishers.publisher_isbn';

    /**
     * the column name for the publisher_volumes field
     */
    public const COL_PUBLISHER_VOLUMES = 'publishers.publisher_volumes';

    /**
     * the column name for the publisher_average_run field
     */
    public const COL_PUBLISHER_AVERAGE_RUN = 'publishers.publisher_average_run';

    /**
     * the column name for the publisher_specialities field
     */
    public const COL_PUBLISHER_SPECIALITIES = 'publishers.publisher_specialities';

    /**
     * the column name for the publisher_diffuseur field
     */
    public const COL_PUBLISHER_DIFFUSEUR = 'publishers.publisher_diffuseur';

    /**
     * the column name for the publisher_distributeur field
     */
    public const COL_PUBLISHER_DISTRIBUTEUR = 'publishers.publisher_distributeur';

    /**
     * the column name for the publisher_vpc field
     */
    public const COL_PUBLISHER_VPC = 'publishers.publisher_vpc';

    /**
     * the column name for the publisher_paypal field
     */
    public const COL_PUBLISHER_PAYPAL = 'publishers.publisher_paypal';

    /**
     * the column name for the publisher_shipping_mode field
     */
    public const COL_PUBLISHER_SHIPPING_MODE = 'publishers.publisher_shipping_mode';

    /**
     * the column name for the publisher_shipping_fee field
     */
    public const COL_PUBLISHER_SHIPPING_FEE = 'publishers.publisher_shipping_fee';

    /**
     * the column name for the publisher_gln field
     */
    public const COL_PUBLISHER_GLN = 'publishers.publisher_gln';

    /**
     * the column name for the publisher_desc field
     */
    public const COL_PUBLISHER_DESC = 'publishers.publisher_desc';

    /**
     * the column name for the publisher_desc_short field
     */
    public const COL_PUBLISHER_DESC_SHORT = 'publishers.publisher_desc_short';

    /**
     * the column name for the publisher_order_by field
     */
    public const COL_PUBLISHER_ORDER_BY = 'publishers.publisher_order_by';

    /**
     * the column name for the publisher_insert field
     */
    public const COL_PUBLISHER_INSERT = 'publishers.publisher_insert';

    /**
     * the column name for the publisher_update field
     */
    public const COL_PUBLISHER_UPDATE = 'publishers.publisher_update';

    /**
     * the column name for the publisher_created field
     */
    public const COL_PUBLISHER_CREATED = 'publishers.publisher_created';

    /**
     * the column name for the publisher_updated field
     */
    public const COL_PUBLISHER_UPDATED = 'publishers.publisher_updated';

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
        self::TYPE_PHPNAME       => ['Id', 'SiteId', 'Name', 'NameAlphabetic', 'Url', 'NoosfereId', 'Representative', 'Address', 'PostalCode', 'City', 'Country', 'Phone', 'Fax', 'Website', 'BuyLink', 'Email', 'Facebook', 'Twitter', 'LegalForm', 'CreationYear', 'Isbn', 'Volumes', 'AverageRun', 'Specialities', 'Diffuseur', 'Distributeur', 'Vpc', 'Paypal', 'ShippingMode', 'ShippingFee', 'Gln', 'Desc', 'DescShort', 'OrderBy', 'Insert', 'Update', 'CreatedAt', 'UpdatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'siteId', 'name', 'nameAlphabetic', 'url', 'noosfereId', 'representative', 'address', 'postalCode', 'city', 'country', 'phone', 'fax', 'website', 'buyLink', 'email', 'facebook', 'twitter', 'legalForm', 'creationYear', 'isbn', 'volumes', 'averageRun', 'specialities', 'diffuseur', 'distributeur', 'vpc', 'paypal', 'shippingMode', 'shippingFee', 'gln', 'desc', 'descShort', 'orderBy', 'insert', 'update', 'createdAt', 'updatedAt', ],
        self::TYPE_COLNAME       => [PublisherTableMap::COL_PUBLISHER_ID, PublisherTableMap::COL_SITE_ID, PublisherTableMap::COL_PUBLISHER_NAME, PublisherTableMap::COL_PUBLISHER_NAME_ALPHABETIC, PublisherTableMap::COL_PUBLISHER_URL, PublisherTableMap::COL_PUBLISHER_NOOSFERE_ID, PublisherTableMap::COL_PUBLISHER_REPRESENTATIVE, PublisherTableMap::COL_PUBLISHER_ADDRESS, PublisherTableMap::COL_PUBLISHER_POSTAL_CODE, PublisherTableMap::COL_PUBLISHER_CITY, PublisherTableMap::COL_PUBLISHER_COUNTRY, PublisherTableMap::COL_PUBLISHER_PHONE, PublisherTableMap::COL_PUBLISHER_FAX, PublisherTableMap::COL_PUBLISHER_WEBSITE, PublisherTableMap::COL_PUBLISHER_BUY_LINK, PublisherTableMap::COL_PUBLISHER_EMAIL, PublisherTableMap::COL_PUBLISHER_FACEBOOK, PublisherTableMap::COL_PUBLISHER_TWITTER, PublisherTableMap::COL_PUBLISHER_LEGAL_FORM, PublisherTableMap::COL_PUBLISHER_CREATION_YEAR, PublisherTableMap::COL_PUBLISHER_ISBN, PublisherTableMap::COL_PUBLISHER_VOLUMES, PublisherTableMap::COL_PUBLISHER_AVERAGE_RUN, PublisherTableMap::COL_PUBLISHER_SPECIALITIES, PublisherTableMap::COL_PUBLISHER_DIFFUSEUR, PublisherTableMap::COL_PUBLISHER_DISTRIBUTEUR, PublisherTableMap::COL_PUBLISHER_VPC, PublisherTableMap::COL_PUBLISHER_PAYPAL, PublisherTableMap::COL_PUBLISHER_SHIPPING_MODE, PublisherTableMap::COL_PUBLISHER_SHIPPING_FEE, PublisherTableMap::COL_PUBLISHER_GLN, PublisherTableMap::COL_PUBLISHER_DESC, PublisherTableMap::COL_PUBLISHER_DESC_SHORT, PublisherTableMap::COL_PUBLISHER_ORDER_BY, PublisherTableMap::COL_PUBLISHER_INSERT, PublisherTableMap::COL_PUBLISHER_UPDATE, PublisherTableMap::COL_PUBLISHER_CREATED, PublisherTableMap::COL_PUBLISHER_UPDATED, ],
        self::TYPE_FIELDNAME     => ['publisher_id', 'site_id', 'publisher_name', 'publisher_name_alphabetic', 'publisher_url', 'publisher_noosfere_id', 'publisher_representative', 'publisher_address', 'publisher_postal_code', 'publisher_city', 'publisher_country', 'publisher_phone', 'publisher_fax', 'publisher_website', 'publisher_buy_link', 'publisher_email', 'publisher_facebook', 'publisher_twitter', 'publisher_legal_form', 'publisher_creation_year', 'publisher_isbn', 'publisher_volumes', 'publisher_average_run', 'publisher_specialities', 'publisher_diffuseur', 'publisher_distributeur', 'publisher_vpc', 'publisher_paypal', 'publisher_shipping_mode', 'publisher_shipping_fee', 'publisher_gln', 'publisher_desc', 'publisher_desc_short', 'publisher_order_by', 'publisher_insert', 'publisher_update', 'publisher_created', 'publisher_updated', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, ]
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
        self::TYPE_PHPNAME       => ['Id' => 0, 'SiteId' => 1, 'Name' => 2, 'NameAlphabetic' => 3, 'Url' => 4, 'NoosfereId' => 5, 'Representative' => 6, 'Address' => 7, 'PostalCode' => 8, 'City' => 9, 'Country' => 10, 'Phone' => 11, 'Fax' => 12, 'Website' => 13, 'BuyLink' => 14, 'Email' => 15, 'Facebook' => 16, 'Twitter' => 17, 'LegalForm' => 18, 'CreationYear' => 19, 'Isbn' => 20, 'Volumes' => 21, 'AverageRun' => 22, 'Specialities' => 23, 'Diffuseur' => 24, 'Distributeur' => 25, 'Vpc' => 26, 'Paypal' => 27, 'ShippingMode' => 28, 'ShippingFee' => 29, 'Gln' => 30, 'Desc' => 31, 'DescShort' => 32, 'OrderBy' => 33, 'Insert' => 34, 'Update' => 35, 'CreatedAt' => 36, 'UpdatedAt' => 37, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'siteId' => 1, 'name' => 2, 'nameAlphabetic' => 3, 'url' => 4, 'noosfereId' => 5, 'representative' => 6, 'address' => 7, 'postalCode' => 8, 'city' => 9, 'country' => 10, 'phone' => 11, 'fax' => 12, 'website' => 13, 'buyLink' => 14, 'email' => 15, 'facebook' => 16, 'twitter' => 17, 'legalForm' => 18, 'creationYear' => 19, 'isbn' => 20, 'volumes' => 21, 'averageRun' => 22, 'specialities' => 23, 'diffuseur' => 24, 'distributeur' => 25, 'vpc' => 26, 'paypal' => 27, 'shippingMode' => 28, 'shippingFee' => 29, 'gln' => 30, 'desc' => 31, 'descShort' => 32, 'orderBy' => 33, 'insert' => 34, 'update' => 35, 'createdAt' => 36, 'updatedAt' => 37, ],
        self::TYPE_COLNAME       => [PublisherTableMap::COL_PUBLISHER_ID => 0, PublisherTableMap::COL_SITE_ID => 1, PublisherTableMap::COL_PUBLISHER_NAME => 2, PublisherTableMap::COL_PUBLISHER_NAME_ALPHABETIC => 3, PublisherTableMap::COL_PUBLISHER_URL => 4, PublisherTableMap::COL_PUBLISHER_NOOSFERE_ID => 5, PublisherTableMap::COL_PUBLISHER_REPRESENTATIVE => 6, PublisherTableMap::COL_PUBLISHER_ADDRESS => 7, PublisherTableMap::COL_PUBLISHER_POSTAL_CODE => 8, PublisherTableMap::COL_PUBLISHER_CITY => 9, PublisherTableMap::COL_PUBLISHER_COUNTRY => 10, PublisherTableMap::COL_PUBLISHER_PHONE => 11, PublisherTableMap::COL_PUBLISHER_FAX => 12, PublisherTableMap::COL_PUBLISHER_WEBSITE => 13, PublisherTableMap::COL_PUBLISHER_BUY_LINK => 14, PublisherTableMap::COL_PUBLISHER_EMAIL => 15, PublisherTableMap::COL_PUBLISHER_FACEBOOK => 16, PublisherTableMap::COL_PUBLISHER_TWITTER => 17, PublisherTableMap::COL_PUBLISHER_LEGAL_FORM => 18, PublisherTableMap::COL_PUBLISHER_CREATION_YEAR => 19, PublisherTableMap::COL_PUBLISHER_ISBN => 20, PublisherTableMap::COL_PUBLISHER_VOLUMES => 21, PublisherTableMap::COL_PUBLISHER_AVERAGE_RUN => 22, PublisherTableMap::COL_PUBLISHER_SPECIALITIES => 23, PublisherTableMap::COL_PUBLISHER_DIFFUSEUR => 24, PublisherTableMap::COL_PUBLISHER_DISTRIBUTEUR => 25, PublisherTableMap::COL_PUBLISHER_VPC => 26, PublisherTableMap::COL_PUBLISHER_PAYPAL => 27, PublisherTableMap::COL_PUBLISHER_SHIPPING_MODE => 28, PublisherTableMap::COL_PUBLISHER_SHIPPING_FEE => 29, PublisherTableMap::COL_PUBLISHER_GLN => 30, PublisherTableMap::COL_PUBLISHER_DESC => 31, PublisherTableMap::COL_PUBLISHER_DESC_SHORT => 32, PublisherTableMap::COL_PUBLISHER_ORDER_BY => 33, PublisherTableMap::COL_PUBLISHER_INSERT => 34, PublisherTableMap::COL_PUBLISHER_UPDATE => 35, PublisherTableMap::COL_PUBLISHER_CREATED => 36, PublisherTableMap::COL_PUBLISHER_UPDATED => 37, ],
        self::TYPE_FIELDNAME     => ['publisher_id' => 0, 'site_id' => 1, 'publisher_name' => 2, 'publisher_name_alphabetic' => 3, 'publisher_url' => 4, 'publisher_noosfere_id' => 5, 'publisher_representative' => 6, 'publisher_address' => 7, 'publisher_postal_code' => 8, 'publisher_city' => 9, 'publisher_country' => 10, 'publisher_phone' => 11, 'publisher_fax' => 12, 'publisher_website' => 13, 'publisher_buy_link' => 14, 'publisher_email' => 15, 'publisher_facebook' => 16, 'publisher_twitter' => 17, 'publisher_legal_form' => 18, 'publisher_creation_year' => 19, 'publisher_isbn' => 20, 'publisher_volumes' => 21, 'publisher_average_run' => 22, 'publisher_specialities' => 23, 'publisher_diffuseur' => 24, 'publisher_distributeur' => 25, 'publisher_vpc' => 26, 'publisher_paypal' => 27, 'publisher_shipping_mode' => 28, 'publisher_shipping_fee' => 29, 'publisher_gln' => 30, 'publisher_desc' => 31, 'publisher_desc_short' => 32, 'publisher_order_by' => 33, 'publisher_insert' => 34, 'publisher_update' => 35, 'publisher_created' => 36, 'publisher_updated' => 37, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'PUBLISHER_ID',
        'Publisher.Id' => 'PUBLISHER_ID',
        'id' => 'PUBLISHER_ID',
        'publisher.id' => 'PUBLISHER_ID',
        'PublisherTableMap::COL_PUBLISHER_ID' => 'PUBLISHER_ID',
        'COL_PUBLISHER_ID' => 'PUBLISHER_ID',
        'publisher_id' => 'PUBLISHER_ID',
        'publishers.publisher_id' => 'PUBLISHER_ID',
        'SiteId' => 'SITE_ID',
        'Publisher.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'publisher.siteId' => 'SITE_ID',
        'PublisherTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'publishers.site_id' => 'SITE_ID',
        'Name' => 'PUBLISHER_NAME',
        'Publisher.Name' => 'PUBLISHER_NAME',
        'name' => 'PUBLISHER_NAME',
        'publisher.name' => 'PUBLISHER_NAME',
        'PublisherTableMap::COL_PUBLISHER_NAME' => 'PUBLISHER_NAME',
        'COL_PUBLISHER_NAME' => 'PUBLISHER_NAME',
        'publisher_name' => 'PUBLISHER_NAME',
        'publishers.publisher_name' => 'PUBLISHER_NAME',
        'NameAlphabetic' => 'PUBLISHER_NAME_ALPHABETIC',
        'Publisher.NameAlphabetic' => 'PUBLISHER_NAME_ALPHABETIC',
        'nameAlphabetic' => 'PUBLISHER_NAME_ALPHABETIC',
        'publisher.nameAlphabetic' => 'PUBLISHER_NAME_ALPHABETIC',
        'PublisherTableMap::COL_PUBLISHER_NAME_ALPHABETIC' => 'PUBLISHER_NAME_ALPHABETIC',
        'COL_PUBLISHER_NAME_ALPHABETIC' => 'PUBLISHER_NAME_ALPHABETIC',
        'publisher_name_alphabetic' => 'PUBLISHER_NAME_ALPHABETIC',
        'publishers.publisher_name_alphabetic' => 'PUBLISHER_NAME_ALPHABETIC',
        'Url' => 'PUBLISHER_URL',
        'Publisher.Url' => 'PUBLISHER_URL',
        'url' => 'PUBLISHER_URL',
        'publisher.url' => 'PUBLISHER_URL',
        'PublisherTableMap::COL_PUBLISHER_URL' => 'PUBLISHER_URL',
        'COL_PUBLISHER_URL' => 'PUBLISHER_URL',
        'publisher_url' => 'PUBLISHER_URL',
        'publishers.publisher_url' => 'PUBLISHER_URL',
        'NoosfereId' => 'PUBLISHER_NOOSFERE_ID',
        'Publisher.NoosfereId' => 'PUBLISHER_NOOSFERE_ID',
        'noosfereId' => 'PUBLISHER_NOOSFERE_ID',
        'publisher.noosfereId' => 'PUBLISHER_NOOSFERE_ID',
        'PublisherTableMap::COL_PUBLISHER_NOOSFERE_ID' => 'PUBLISHER_NOOSFERE_ID',
        'COL_PUBLISHER_NOOSFERE_ID' => 'PUBLISHER_NOOSFERE_ID',
        'publisher_noosfere_id' => 'PUBLISHER_NOOSFERE_ID',
        'publishers.publisher_noosfere_id' => 'PUBLISHER_NOOSFERE_ID',
        'Representative' => 'PUBLISHER_REPRESENTATIVE',
        'Publisher.Representative' => 'PUBLISHER_REPRESENTATIVE',
        'representative' => 'PUBLISHER_REPRESENTATIVE',
        'publisher.representative' => 'PUBLISHER_REPRESENTATIVE',
        'PublisherTableMap::COL_PUBLISHER_REPRESENTATIVE' => 'PUBLISHER_REPRESENTATIVE',
        'COL_PUBLISHER_REPRESENTATIVE' => 'PUBLISHER_REPRESENTATIVE',
        'publisher_representative' => 'PUBLISHER_REPRESENTATIVE',
        'publishers.publisher_representative' => 'PUBLISHER_REPRESENTATIVE',
        'Address' => 'PUBLISHER_ADDRESS',
        'Publisher.Address' => 'PUBLISHER_ADDRESS',
        'address' => 'PUBLISHER_ADDRESS',
        'publisher.address' => 'PUBLISHER_ADDRESS',
        'PublisherTableMap::COL_PUBLISHER_ADDRESS' => 'PUBLISHER_ADDRESS',
        'COL_PUBLISHER_ADDRESS' => 'PUBLISHER_ADDRESS',
        'publisher_address' => 'PUBLISHER_ADDRESS',
        'publishers.publisher_address' => 'PUBLISHER_ADDRESS',
        'PostalCode' => 'PUBLISHER_POSTAL_CODE',
        'Publisher.PostalCode' => 'PUBLISHER_POSTAL_CODE',
        'postalCode' => 'PUBLISHER_POSTAL_CODE',
        'publisher.postalCode' => 'PUBLISHER_POSTAL_CODE',
        'PublisherTableMap::COL_PUBLISHER_POSTAL_CODE' => 'PUBLISHER_POSTAL_CODE',
        'COL_PUBLISHER_POSTAL_CODE' => 'PUBLISHER_POSTAL_CODE',
        'publisher_postal_code' => 'PUBLISHER_POSTAL_CODE',
        'publishers.publisher_postal_code' => 'PUBLISHER_POSTAL_CODE',
        'City' => 'PUBLISHER_CITY',
        'Publisher.City' => 'PUBLISHER_CITY',
        'city' => 'PUBLISHER_CITY',
        'publisher.city' => 'PUBLISHER_CITY',
        'PublisherTableMap::COL_PUBLISHER_CITY' => 'PUBLISHER_CITY',
        'COL_PUBLISHER_CITY' => 'PUBLISHER_CITY',
        'publisher_city' => 'PUBLISHER_CITY',
        'publishers.publisher_city' => 'PUBLISHER_CITY',
        'Country' => 'PUBLISHER_COUNTRY',
        'Publisher.Country' => 'PUBLISHER_COUNTRY',
        'country' => 'PUBLISHER_COUNTRY',
        'publisher.country' => 'PUBLISHER_COUNTRY',
        'PublisherTableMap::COL_PUBLISHER_COUNTRY' => 'PUBLISHER_COUNTRY',
        'COL_PUBLISHER_COUNTRY' => 'PUBLISHER_COUNTRY',
        'publisher_country' => 'PUBLISHER_COUNTRY',
        'publishers.publisher_country' => 'PUBLISHER_COUNTRY',
        'Phone' => 'PUBLISHER_PHONE',
        'Publisher.Phone' => 'PUBLISHER_PHONE',
        'phone' => 'PUBLISHER_PHONE',
        'publisher.phone' => 'PUBLISHER_PHONE',
        'PublisherTableMap::COL_PUBLISHER_PHONE' => 'PUBLISHER_PHONE',
        'COL_PUBLISHER_PHONE' => 'PUBLISHER_PHONE',
        'publisher_phone' => 'PUBLISHER_PHONE',
        'publishers.publisher_phone' => 'PUBLISHER_PHONE',
        'Fax' => 'PUBLISHER_FAX',
        'Publisher.Fax' => 'PUBLISHER_FAX',
        'fax' => 'PUBLISHER_FAX',
        'publisher.fax' => 'PUBLISHER_FAX',
        'PublisherTableMap::COL_PUBLISHER_FAX' => 'PUBLISHER_FAX',
        'COL_PUBLISHER_FAX' => 'PUBLISHER_FAX',
        'publisher_fax' => 'PUBLISHER_FAX',
        'publishers.publisher_fax' => 'PUBLISHER_FAX',
        'Website' => 'PUBLISHER_WEBSITE',
        'Publisher.Website' => 'PUBLISHER_WEBSITE',
        'website' => 'PUBLISHER_WEBSITE',
        'publisher.website' => 'PUBLISHER_WEBSITE',
        'PublisherTableMap::COL_PUBLISHER_WEBSITE' => 'PUBLISHER_WEBSITE',
        'COL_PUBLISHER_WEBSITE' => 'PUBLISHER_WEBSITE',
        'publisher_website' => 'PUBLISHER_WEBSITE',
        'publishers.publisher_website' => 'PUBLISHER_WEBSITE',
        'BuyLink' => 'PUBLISHER_BUY_LINK',
        'Publisher.BuyLink' => 'PUBLISHER_BUY_LINK',
        'buyLink' => 'PUBLISHER_BUY_LINK',
        'publisher.buyLink' => 'PUBLISHER_BUY_LINK',
        'PublisherTableMap::COL_PUBLISHER_BUY_LINK' => 'PUBLISHER_BUY_LINK',
        'COL_PUBLISHER_BUY_LINK' => 'PUBLISHER_BUY_LINK',
        'publisher_buy_link' => 'PUBLISHER_BUY_LINK',
        'publishers.publisher_buy_link' => 'PUBLISHER_BUY_LINK',
        'Email' => 'PUBLISHER_EMAIL',
        'Publisher.Email' => 'PUBLISHER_EMAIL',
        'email' => 'PUBLISHER_EMAIL',
        'publisher.email' => 'PUBLISHER_EMAIL',
        'PublisherTableMap::COL_PUBLISHER_EMAIL' => 'PUBLISHER_EMAIL',
        'COL_PUBLISHER_EMAIL' => 'PUBLISHER_EMAIL',
        'publisher_email' => 'PUBLISHER_EMAIL',
        'publishers.publisher_email' => 'PUBLISHER_EMAIL',
        'Facebook' => 'PUBLISHER_FACEBOOK',
        'Publisher.Facebook' => 'PUBLISHER_FACEBOOK',
        'facebook' => 'PUBLISHER_FACEBOOK',
        'publisher.facebook' => 'PUBLISHER_FACEBOOK',
        'PublisherTableMap::COL_PUBLISHER_FACEBOOK' => 'PUBLISHER_FACEBOOK',
        'COL_PUBLISHER_FACEBOOK' => 'PUBLISHER_FACEBOOK',
        'publisher_facebook' => 'PUBLISHER_FACEBOOK',
        'publishers.publisher_facebook' => 'PUBLISHER_FACEBOOK',
        'Twitter' => 'PUBLISHER_TWITTER',
        'Publisher.Twitter' => 'PUBLISHER_TWITTER',
        'twitter' => 'PUBLISHER_TWITTER',
        'publisher.twitter' => 'PUBLISHER_TWITTER',
        'PublisherTableMap::COL_PUBLISHER_TWITTER' => 'PUBLISHER_TWITTER',
        'COL_PUBLISHER_TWITTER' => 'PUBLISHER_TWITTER',
        'publisher_twitter' => 'PUBLISHER_TWITTER',
        'publishers.publisher_twitter' => 'PUBLISHER_TWITTER',
        'LegalForm' => 'PUBLISHER_LEGAL_FORM',
        'Publisher.LegalForm' => 'PUBLISHER_LEGAL_FORM',
        'legalForm' => 'PUBLISHER_LEGAL_FORM',
        'publisher.legalForm' => 'PUBLISHER_LEGAL_FORM',
        'PublisherTableMap::COL_PUBLISHER_LEGAL_FORM' => 'PUBLISHER_LEGAL_FORM',
        'COL_PUBLISHER_LEGAL_FORM' => 'PUBLISHER_LEGAL_FORM',
        'publisher_legal_form' => 'PUBLISHER_LEGAL_FORM',
        'publishers.publisher_legal_form' => 'PUBLISHER_LEGAL_FORM',
        'CreationYear' => 'PUBLISHER_CREATION_YEAR',
        'Publisher.CreationYear' => 'PUBLISHER_CREATION_YEAR',
        'creationYear' => 'PUBLISHER_CREATION_YEAR',
        'publisher.creationYear' => 'PUBLISHER_CREATION_YEAR',
        'PublisherTableMap::COL_PUBLISHER_CREATION_YEAR' => 'PUBLISHER_CREATION_YEAR',
        'COL_PUBLISHER_CREATION_YEAR' => 'PUBLISHER_CREATION_YEAR',
        'publisher_creation_year' => 'PUBLISHER_CREATION_YEAR',
        'publishers.publisher_creation_year' => 'PUBLISHER_CREATION_YEAR',
        'Isbn' => 'PUBLISHER_ISBN',
        'Publisher.Isbn' => 'PUBLISHER_ISBN',
        'isbn' => 'PUBLISHER_ISBN',
        'publisher.isbn' => 'PUBLISHER_ISBN',
        'PublisherTableMap::COL_PUBLISHER_ISBN' => 'PUBLISHER_ISBN',
        'COL_PUBLISHER_ISBN' => 'PUBLISHER_ISBN',
        'publisher_isbn' => 'PUBLISHER_ISBN',
        'publishers.publisher_isbn' => 'PUBLISHER_ISBN',
        'Volumes' => 'PUBLISHER_VOLUMES',
        'Publisher.Volumes' => 'PUBLISHER_VOLUMES',
        'volumes' => 'PUBLISHER_VOLUMES',
        'publisher.volumes' => 'PUBLISHER_VOLUMES',
        'PublisherTableMap::COL_PUBLISHER_VOLUMES' => 'PUBLISHER_VOLUMES',
        'COL_PUBLISHER_VOLUMES' => 'PUBLISHER_VOLUMES',
        'publisher_volumes' => 'PUBLISHER_VOLUMES',
        'publishers.publisher_volumes' => 'PUBLISHER_VOLUMES',
        'AverageRun' => 'PUBLISHER_AVERAGE_RUN',
        'Publisher.AverageRun' => 'PUBLISHER_AVERAGE_RUN',
        'averageRun' => 'PUBLISHER_AVERAGE_RUN',
        'publisher.averageRun' => 'PUBLISHER_AVERAGE_RUN',
        'PublisherTableMap::COL_PUBLISHER_AVERAGE_RUN' => 'PUBLISHER_AVERAGE_RUN',
        'COL_PUBLISHER_AVERAGE_RUN' => 'PUBLISHER_AVERAGE_RUN',
        'publisher_average_run' => 'PUBLISHER_AVERAGE_RUN',
        'publishers.publisher_average_run' => 'PUBLISHER_AVERAGE_RUN',
        'Specialities' => 'PUBLISHER_SPECIALITIES',
        'Publisher.Specialities' => 'PUBLISHER_SPECIALITIES',
        'specialities' => 'PUBLISHER_SPECIALITIES',
        'publisher.specialities' => 'PUBLISHER_SPECIALITIES',
        'PublisherTableMap::COL_PUBLISHER_SPECIALITIES' => 'PUBLISHER_SPECIALITIES',
        'COL_PUBLISHER_SPECIALITIES' => 'PUBLISHER_SPECIALITIES',
        'publisher_specialities' => 'PUBLISHER_SPECIALITIES',
        'publishers.publisher_specialities' => 'PUBLISHER_SPECIALITIES',
        'Diffuseur' => 'PUBLISHER_DIFFUSEUR',
        'Publisher.Diffuseur' => 'PUBLISHER_DIFFUSEUR',
        'diffuseur' => 'PUBLISHER_DIFFUSEUR',
        'publisher.diffuseur' => 'PUBLISHER_DIFFUSEUR',
        'PublisherTableMap::COL_PUBLISHER_DIFFUSEUR' => 'PUBLISHER_DIFFUSEUR',
        'COL_PUBLISHER_DIFFUSEUR' => 'PUBLISHER_DIFFUSEUR',
        'publisher_diffuseur' => 'PUBLISHER_DIFFUSEUR',
        'publishers.publisher_diffuseur' => 'PUBLISHER_DIFFUSEUR',
        'Distributeur' => 'PUBLISHER_DISTRIBUTEUR',
        'Publisher.Distributeur' => 'PUBLISHER_DISTRIBUTEUR',
        'distributeur' => 'PUBLISHER_DISTRIBUTEUR',
        'publisher.distributeur' => 'PUBLISHER_DISTRIBUTEUR',
        'PublisherTableMap::COL_PUBLISHER_DISTRIBUTEUR' => 'PUBLISHER_DISTRIBUTEUR',
        'COL_PUBLISHER_DISTRIBUTEUR' => 'PUBLISHER_DISTRIBUTEUR',
        'publisher_distributeur' => 'PUBLISHER_DISTRIBUTEUR',
        'publishers.publisher_distributeur' => 'PUBLISHER_DISTRIBUTEUR',
        'Vpc' => 'PUBLISHER_VPC',
        'Publisher.Vpc' => 'PUBLISHER_VPC',
        'vpc' => 'PUBLISHER_VPC',
        'publisher.vpc' => 'PUBLISHER_VPC',
        'PublisherTableMap::COL_PUBLISHER_VPC' => 'PUBLISHER_VPC',
        'COL_PUBLISHER_VPC' => 'PUBLISHER_VPC',
        'publisher_vpc' => 'PUBLISHER_VPC',
        'publishers.publisher_vpc' => 'PUBLISHER_VPC',
        'Paypal' => 'PUBLISHER_PAYPAL',
        'Publisher.Paypal' => 'PUBLISHER_PAYPAL',
        'paypal' => 'PUBLISHER_PAYPAL',
        'publisher.paypal' => 'PUBLISHER_PAYPAL',
        'PublisherTableMap::COL_PUBLISHER_PAYPAL' => 'PUBLISHER_PAYPAL',
        'COL_PUBLISHER_PAYPAL' => 'PUBLISHER_PAYPAL',
        'publisher_paypal' => 'PUBLISHER_PAYPAL',
        'publishers.publisher_paypal' => 'PUBLISHER_PAYPAL',
        'ShippingMode' => 'PUBLISHER_SHIPPING_MODE',
        'Publisher.ShippingMode' => 'PUBLISHER_SHIPPING_MODE',
        'shippingMode' => 'PUBLISHER_SHIPPING_MODE',
        'publisher.shippingMode' => 'PUBLISHER_SHIPPING_MODE',
        'PublisherTableMap::COL_PUBLISHER_SHIPPING_MODE' => 'PUBLISHER_SHIPPING_MODE',
        'COL_PUBLISHER_SHIPPING_MODE' => 'PUBLISHER_SHIPPING_MODE',
        'publisher_shipping_mode' => 'PUBLISHER_SHIPPING_MODE',
        'publishers.publisher_shipping_mode' => 'PUBLISHER_SHIPPING_MODE',
        'ShippingFee' => 'PUBLISHER_SHIPPING_FEE',
        'Publisher.ShippingFee' => 'PUBLISHER_SHIPPING_FEE',
        'shippingFee' => 'PUBLISHER_SHIPPING_FEE',
        'publisher.shippingFee' => 'PUBLISHER_SHIPPING_FEE',
        'PublisherTableMap::COL_PUBLISHER_SHIPPING_FEE' => 'PUBLISHER_SHIPPING_FEE',
        'COL_PUBLISHER_SHIPPING_FEE' => 'PUBLISHER_SHIPPING_FEE',
        'publisher_shipping_fee' => 'PUBLISHER_SHIPPING_FEE',
        'publishers.publisher_shipping_fee' => 'PUBLISHER_SHIPPING_FEE',
        'Gln' => 'PUBLISHER_GLN',
        'Publisher.Gln' => 'PUBLISHER_GLN',
        'gln' => 'PUBLISHER_GLN',
        'publisher.gln' => 'PUBLISHER_GLN',
        'PublisherTableMap::COL_PUBLISHER_GLN' => 'PUBLISHER_GLN',
        'COL_PUBLISHER_GLN' => 'PUBLISHER_GLN',
        'publisher_gln' => 'PUBLISHER_GLN',
        'publishers.publisher_gln' => 'PUBLISHER_GLN',
        'Desc' => 'PUBLISHER_DESC',
        'Publisher.Desc' => 'PUBLISHER_DESC',
        'desc' => 'PUBLISHER_DESC',
        'publisher.desc' => 'PUBLISHER_DESC',
        'PublisherTableMap::COL_PUBLISHER_DESC' => 'PUBLISHER_DESC',
        'COL_PUBLISHER_DESC' => 'PUBLISHER_DESC',
        'publisher_desc' => 'PUBLISHER_DESC',
        'publishers.publisher_desc' => 'PUBLISHER_DESC',
        'DescShort' => 'PUBLISHER_DESC_SHORT',
        'Publisher.DescShort' => 'PUBLISHER_DESC_SHORT',
        'descShort' => 'PUBLISHER_DESC_SHORT',
        'publisher.descShort' => 'PUBLISHER_DESC_SHORT',
        'PublisherTableMap::COL_PUBLISHER_DESC_SHORT' => 'PUBLISHER_DESC_SHORT',
        'COL_PUBLISHER_DESC_SHORT' => 'PUBLISHER_DESC_SHORT',
        'publisher_desc_short' => 'PUBLISHER_DESC_SHORT',
        'publishers.publisher_desc_short' => 'PUBLISHER_DESC_SHORT',
        'OrderBy' => 'PUBLISHER_ORDER_BY',
        'Publisher.OrderBy' => 'PUBLISHER_ORDER_BY',
        'orderBy' => 'PUBLISHER_ORDER_BY',
        'publisher.orderBy' => 'PUBLISHER_ORDER_BY',
        'PublisherTableMap::COL_PUBLISHER_ORDER_BY' => 'PUBLISHER_ORDER_BY',
        'COL_PUBLISHER_ORDER_BY' => 'PUBLISHER_ORDER_BY',
        'publisher_order_by' => 'PUBLISHER_ORDER_BY',
        'publishers.publisher_order_by' => 'PUBLISHER_ORDER_BY',
        'Insert' => 'PUBLISHER_INSERT',
        'Publisher.Insert' => 'PUBLISHER_INSERT',
        'insert' => 'PUBLISHER_INSERT',
        'publisher.insert' => 'PUBLISHER_INSERT',
        'PublisherTableMap::COL_PUBLISHER_INSERT' => 'PUBLISHER_INSERT',
        'COL_PUBLISHER_INSERT' => 'PUBLISHER_INSERT',
        'publisher_insert' => 'PUBLISHER_INSERT',
        'publishers.publisher_insert' => 'PUBLISHER_INSERT',
        'Update' => 'PUBLISHER_UPDATE',
        'Publisher.Update' => 'PUBLISHER_UPDATE',
        'update' => 'PUBLISHER_UPDATE',
        'publisher.update' => 'PUBLISHER_UPDATE',
        'PublisherTableMap::COL_PUBLISHER_UPDATE' => 'PUBLISHER_UPDATE',
        'COL_PUBLISHER_UPDATE' => 'PUBLISHER_UPDATE',
        'publisher_update' => 'PUBLISHER_UPDATE',
        'publishers.publisher_update' => 'PUBLISHER_UPDATE',
        'CreatedAt' => 'PUBLISHER_CREATED',
        'Publisher.CreatedAt' => 'PUBLISHER_CREATED',
        'createdAt' => 'PUBLISHER_CREATED',
        'publisher.createdAt' => 'PUBLISHER_CREATED',
        'PublisherTableMap::COL_PUBLISHER_CREATED' => 'PUBLISHER_CREATED',
        'COL_PUBLISHER_CREATED' => 'PUBLISHER_CREATED',
        'publisher_created' => 'PUBLISHER_CREATED',
        'publishers.publisher_created' => 'PUBLISHER_CREATED',
        'UpdatedAt' => 'PUBLISHER_UPDATED',
        'Publisher.UpdatedAt' => 'PUBLISHER_UPDATED',
        'updatedAt' => 'PUBLISHER_UPDATED',
        'publisher.updatedAt' => 'PUBLISHER_UPDATED',
        'PublisherTableMap::COL_PUBLISHER_UPDATED' => 'PUBLISHER_UPDATED',
        'COL_PUBLISHER_UPDATED' => 'PUBLISHER_UPDATED',
        'publisher_updated' => 'PUBLISHER_UPDATED',
        'publishers.publisher_updated' => 'PUBLISHER_UPDATED',
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
        $this->setName('publishers');
        $this->setPhpName('Publisher');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Publisher');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('publisher_id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('site_id', 'SiteId', 'INTEGER', false, null, null);
        $this->addColumn('publisher_name', 'Name', 'VARCHAR', false, 255, null);
        $this->addColumn('publisher_name_alphabetic', 'NameAlphabetic', 'VARCHAR', false, 256, null);
        $this->addColumn('publisher_url', 'Url', 'VARCHAR', false, 256, null);
        $this->addColumn('publisher_noosfere_id', 'NoosfereId', 'INTEGER', false, null, null);
        $this->addColumn('publisher_representative', 'Representative', 'VARCHAR', false, 256, null);
        $this->addColumn('publisher_address', 'Address', 'LONGVARCHAR', false, null, null);
        $this->addColumn('publisher_postal_code', 'PostalCode', 'VARCHAR', false, 8, null);
        $this->addColumn('publisher_city', 'City', 'VARCHAR', false, 128, null);
        $this->addColumn('publisher_country', 'Country', 'VARCHAR', false, 128, null);
        $this->addColumn('publisher_phone', 'Phone', 'VARCHAR', false, 16, null);
        $this->addColumn('publisher_fax', 'Fax', 'VARCHAR', false, 16, null);
        $this->addColumn('publisher_website', 'Website', 'VARCHAR', false, 128, null);
        $this->addColumn('publisher_buy_link', 'BuyLink', 'VARCHAR', false, 256, null);
        $this->addColumn('publisher_email', 'Email', 'VARCHAR', false, 128, null);
        $this->addColumn('publisher_facebook', 'Facebook', 'VARCHAR', false, 128, null);
        $this->addColumn('publisher_twitter', 'Twitter', 'VARCHAR', false, 15, null);
        $this->addColumn('publisher_legal_form', 'LegalForm', 'VARCHAR', false, 128, null);
        $this->addColumn('publisher_creation_year', 'CreationYear', 'VARCHAR', false, 4, null);
        $this->addColumn('publisher_isbn', 'Isbn', 'VARCHAR', false, 13, null);
        $this->addColumn('publisher_volumes', 'Volumes', 'INTEGER', false, null, null);
        $this->addColumn('publisher_average_run', 'AverageRun', 'INTEGER', false, null, null);
        $this->addColumn('publisher_specialities', 'Specialities', 'LONGVARCHAR', false, null, null);
        $this->addColumn('publisher_diffuseur', 'Diffuseur', 'VARCHAR', false, 128, null);
        $this->addColumn('publisher_distributeur', 'Distributeur', 'VARCHAR', false, 128, null);
        $this->addColumn('publisher_vpc', 'Vpc', 'BOOLEAN', false, 1, false);
        $this->addColumn('publisher_paypal', 'Paypal', 'VARCHAR', false, 128, null);
        $this->addColumn('publisher_shipping_mode', 'ShippingMode', 'VARCHAR', false, 9, 'offerts');
        $this->addColumn('publisher_shipping_fee', 'ShippingFee', 'INTEGER', false, null, null);
        $this->addColumn('publisher_gln', 'Gln', 'BIGINT', false, 13, null);
        $this->addColumn('publisher_desc', 'Desc', 'LONGVARCHAR', false, null, null);
        $this->addColumn('publisher_desc_short', 'DescShort', 'VARCHAR', false, 512, null);
        $this->addColumn('publisher_order_by', 'OrderBy', 'VARCHAR', false, 128, 'article_pubdate');
        $this->addColumn('publisher_insert', 'Insert', 'TIMESTAMP', false, null, 'CURRENT_TIMESTAMP');
        $this->addColumn('publisher_update', 'Update', 'TIMESTAMP', false, null, null);
        $this->addColumn('publisher_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('publisher_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
        $this->addRelation('Article', '\\Model\\Article', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':publisher_id',
    1 => ':publisher_id',
  ),
), null, null, 'Articles', false);
        $this->addRelation('Image', '\\Model\\Image', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':publisher_id',
    1 => ':publisher_id',
  ),
), null, null, 'Images', false);
        $this->addRelation('Right', '\\Model\\Right', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':publisher_id',
    1 => ':publisher_id',
  ),
), null, null, 'Rights', false);
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
            'timestampable' => ['create_column' => 'publisher_created', 'update_column' => 'publisher_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
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
        return $withPrefix ? PublisherTableMap::CLASS_DEFAULT : PublisherTableMap::OM_CLASS;
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
     * @return array (Publisher object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = PublisherTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = PublisherTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + PublisherTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PublisherTableMap::OM_CLASS;
            /** @var Publisher $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            PublisherTableMap::addInstanceToPool($obj, $key);
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
            $key = PublisherTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = PublisherTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Publisher $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PublisherTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_ID);
            $criteria->addSelectColumn(PublisherTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_NAME);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_NAME_ALPHABETIC);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_URL);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_NOOSFERE_ID);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_REPRESENTATIVE);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_ADDRESS);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_POSTAL_CODE);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_CITY);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_COUNTRY);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_PHONE);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_FAX);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_WEBSITE);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_BUY_LINK);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_EMAIL);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_FACEBOOK);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_TWITTER);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_LEGAL_FORM);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_CREATION_YEAR);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_ISBN);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_VOLUMES);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_AVERAGE_RUN);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_SPECIALITIES);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_DIFFUSEUR);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_DISTRIBUTEUR);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_VPC);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_PAYPAL);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_SHIPPING_MODE);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_SHIPPING_FEE);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_GLN);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_DESC);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_DESC_SHORT);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_ORDER_BY);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_INSERT);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_UPDATE);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_CREATED);
            $criteria->addSelectColumn(PublisherTableMap::COL_PUBLISHER_UPDATED);
        } else {
            $criteria->addSelectColumn($alias . '.publisher_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.publisher_name');
            $criteria->addSelectColumn($alias . '.publisher_name_alphabetic');
            $criteria->addSelectColumn($alias . '.publisher_url');
            $criteria->addSelectColumn($alias . '.publisher_noosfere_id');
            $criteria->addSelectColumn($alias . '.publisher_representative');
            $criteria->addSelectColumn($alias . '.publisher_address');
            $criteria->addSelectColumn($alias . '.publisher_postal_code');
            $criteria->addSelectColumn($alias . '.publisher_city');
            $criteria->addSelectColumn($alias . '.publisher_country');
            $criteria->addSelectColumn($alias . '.publisher_phone');
            $criteria->addSelectColumn($alias . '.publisher_fax');
            $criteria->addSelectColumn($alias . '.publisher_website');
            $criteria->addSelectColumn($alias . '.publisher_buy_link');
            $criteria->addSelectColumn($alias . '.publisher_email');
            $criteria->addSelectColumn($alias . '.publisher_facebook');
            $criteria->addSelectColumn($alias . '.publisher_twitter');
            $criteria->addSelectColumn($alias . '.publisher_legal_form');
            $criteria->addSelectColumn($alias . '.publisher_creation_year');
            $criteria->addSelectColumn($alias . '.publisher_isbn');
            $criteria->addSelectColumn($alias . '.publisher_volumes');
            $criteria->addSelectColumn($alias . '.publisher_average_run');
            $criteria->addSelectColumn($alias . '.publisher_specialities');
            $criteria->addSelectColumn($alias . '.publisher_diffuseur');
            $criteria->addSelectColumn($alias . '.publisher_distributeur');
            $criteria->addSelectColumn($alias . '.publisher_vpc');
            $criteria->addSelectColumn($alias . '.publisher_paypal');
            $criteria->addSelectColumn($alias . '.publisher_shipping_mode');
            $criteria->addSelectColumn($alias . '.publisher_shipping_fee');
            $criteria->addSelectColumn($alias . '.publisher_gln');
            $criteria->addSelectColumn($alias . '.publisher_desc');
            $criteria->addSelectColumn($alias . '.publisher_desc_short');
            $criteria->addSelectColumn($alias . '.publisher_order_by');
            $criteria->addSelectColumn($alias . '.publisher_insert');
            $criteria->addSelectColumn($alias . '.publisher_update');
            $criteria->addSelectColumn($alias . '.publisher_created');
            $criteria->addSelectColumn($alias . '.publisher_updated');
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
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_ID);
            $criteria->removeSelectColumn(PublisherTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_NAME);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_NAME_ALPHABETIC);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_URL);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_NOOSFERE_ID);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_REPRESENTATIVE);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_ADDRESS);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_POSTAL_CODE);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_CITY);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_COUNTRY);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_PHONE);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_FAX);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_WEBSITE);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_BUY_LINK);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_EMAIL);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_FACEBOOK);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_TWITTER);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_LEGAL_FORM);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_CREATION_YEAR);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_ISBN);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_VOLUMES);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_AVERAGE_RUN);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_SPECIALITIES);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_DIFFUSEUR);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_DISTRIBUTEUR);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_VPC);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_PAYPAL);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_SHIPPING_MODE);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_SHIPPING_FEE);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_GLN);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_DESC);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_DESC_SHORT);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_ORDER_BY);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_INSERT);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_UPDATE);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_CREATED);
            $criteria->removeSelectColumn(PublisherTableMap::COL_PUBLISHER_UPDATED);
        } else {
            $criteria->removeSelectColumn($alias . '.publisher_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.publisher_name');
            $criteria->removeSelectColumn($alias . '.publisher_name_alphabetic');
            $criteria->removeSelectColumn($alias . '.publisher_url');
            $criteria->removeSelectColumn($alias . '.publisher_noosfere_id');
            $criteria->removeSelectColumn($alias . '.publisher_representative');
            $criteria->removeSelectColumn($alias . '.publisher_address');
            $criteria->removeSelectColumn($alias . '.publisher_postal_code');
            $criteria->removeSelectColumn($alias . '.publisher_city');
            $criteria->removeSelectColumn($alias . '.publisher_country');
            $criteria->removeSelectColumn($alias . '.publisher_phone');
            $criteria->removeSelectColumn($alias . '.publisher_fax');
            $criteria->removeSelectColumn($alias . '.publisher_website');
            $criteria->removeSelectColumn($alias . '.publisher_buy_link');
            $criteria->removeSelectColumn($alias . '.publisher_email');
            $criteria->removeSelectColumn($alias . '.publisher_facebook');
            $criteria->removeSelectColumn($alias . '.publisher_twitter');
            $criteria->removeSelectColumn($alias . '.publisher_legal_form');
            $criteria->removeSelectColumn($alias . '.publisher_creation_year');
            $criteria->removeSelectColumn($alias . '.publisher_isbn');
            $criteria->removeSelectColumn($alias . '.publisher_volumes');
            $criteria->removeSelectColumn($alias . '.publisher_average_run');
            $criteria->removeSelectColumn($alias . '.publisher_specialities');
            $criteria->removeSelectColumn($alias . '.publisher_diffuseur');
            $criteria->removeSelectColumn($alias . '.publisher_distributeur');
            $criteria->removeSelectColumn($alias . '.publisher_vpc');
            $criteria->removeSelectColumn($alias . '.publisher_paypal');
            $criteria->removeSelectColumn($alias . '.publisher_shipping_mode');
            $criteria->removeSelectColumn($alias . '.publisher_shipping_fee');
            $criteria->removeSelectColumn($alias . '.publisher_gln');
            $criteria->removeSelectColumn($alias . '.publisher_desc');
            $criteria->removeSelectColumn($alias . '.publisher_desc_short');
            $criteria->removeSelectColumn($alias . '.publisher_order_by');
            $criteria->removeSelectColumn($alias . '.publisher_insert');
            $criteria->removeSelectColumn($alias . '.publisher_update');
            $criteria->removeSelectColumn($alias . '.publisher_created');
            $criteria->removeSelectColumn($alias . '.publisher_updated');
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
        return Propel::getServiceContainer()->getDatabaseMap(PublisherTableMap::DATABASE_NAME)->getTable(PublisherTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Publisher or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Publisher object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(PublisherTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Publisher) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PublisherTableMap::DATABASE_NAME);
            $criteria->add(PublisherTableMap::COL_PUBLISHER_ID, (array) $values, Criteria::IN);
        }

        $query = PublisherQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            PublisherTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                PublisherTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the publishers table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return PublisherQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Publisher or Criteria object.
     *
     * @param mixed $criteria Criteria or Publisher object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PublisherTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Publisher object
        }

        if ($criteria->containsKey(PublisherTableMap::COL_PUBLISHER_ID) && $criteria->keyContainsValue(PublisherTableMap::COL_PUBLISHER_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.PublisherTableMap::COL_PUBLISHER_ID.')');
        }


        // Set the correct dbName
        $query = PublisherQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
