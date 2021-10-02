<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Publisher as ChildPublisher;
use Model\PublisherQuery as ChildPublisherQuery;
use Model\Map\PublisherTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'publishers' table.
 *
 *
 *
 * @method     ChildPublisherQuery orderById($order = Criteria::ASC) Order by the publisher_id column
 * @method     ChildPublisherQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildPublisherQuery orderByName($order = Criteria::ASC) Order by the publisher_name column
 * @method     ChildPublisherQuery orderByNameAlphabetic($order = Criteria::ASC) Order by the publisher_name_alphabetic column
 * @method     ChildPublisherQuery orderByUrl($order = Criteria::ASC) Order by the publisher_url column
 * @method     ChildPublisherQuery orderByNoosfereId($order = Criteria::ASC) Order by the publisher_noosfere_id column
 * @method     ChildPublisherQuery orderByRepresentative($order = Criteria::ASC) Order by the publisher_representative column
 * @method     ChildPublisherQuery orderByAddress($order = Criteria::ASC) Order by the publisher_address column
 * @method     ChildPublisherQuery orderByPostalCode($order = Criteria::ASC) Order by the publisher_postal_code column
 * @method     ChildPublisherQuery orderByCity($order = Criteria::ASC) Order by the publisher_city column
 * @method     ChildPublisherQuery orderByCountry($order = Criteria::ASC) Order by the publisher_country column
 * @method     ChildPublisherQuery orderByPhone($order = Criteria::ASC) Order by the publisher_phone column
 * @method     ChildPublisherQuery orderByFax($order = Criteria::ASC) Order by the publisher_fax column
 * @method     ChildPublisherQuery orderByWebsite($order = Criteria::ASC) Order by the publisher_website column
 * @method     ChildPublisherQuery orderByBuyLink($order = Criteria::ASC) Order by the publisher_buy_link column
 * @method     ChildPublisherQuery orderByEmail($order = Criteria::ASC) Order by the publisher_email column
 * @method     ChildPublisherQuery orderByFacebook($order = Criteria::ASC) Order by the publisher_facebook column
 * @method     ChildPublisherQuery orderByTwitter($order = Criteria::ASC) Order by the publisher_twitter column
 * @method     ChildPublisherQuery orderByLegalForm($order = Criteria::ASC) Order by the publisher_legal_form column
 * @method     ChildPublisherQuery orderByCreationYear($order = Criteria::ASC) Order by the publisher_creation_year column
 * @method     ChildPublisherQuery orderByIsbn($order = Criteria::ASC) Order by the publisher_isbn column
 * @method     ChildPublisherQuery orderByVolumes($order = Criteria::ASC) Order by the publisher_volumes column
 * @method     ChildPublisherQuery orderByAverageRun($order = Criteria::ASC) Order by the publisher_average_run column
 * @method     ChildPublisherQuery orderBySpecialities($order = Criteria::ASC) Order by the publisher_specialities column
 * @method     ChildPublisherQuery orderByDiffuseur($order = Criteria::ASC) Order by the publisher_diffuseur column
 * @method     ChildPublisherQuery orderByDistributeur($order = Criteria::ASC) Order by the publisher_distributeur column
 * @method     ChildPublisherQuery orderByVpc($order = Criteria::ASC) Order by the publisher_vpc column
 * @method     ChildPublisherQuery orderByPaypal($order = Criteria::ASC) Order by the publisher_paypal column
 * @method     ChildPublisherQuery orderByShippingMode($order = Criteria::ASC) Order by the publisher_shipping_mode column
 * @method     ChildPublisherQuery orderByShippingFee($order = Criteria::ASC) Order by the publisher_shipping_fee column
 * @method     ChildPublisherQuery orderByGln($order = Criteria::ASC) Order by the publisher_gln column
 * @method     ChildPublisherQuery orderByDesc($order = Criteria::ASC) Order by the publisher_desc column
 * @method     ChildPublisherQuery orderByDescShort($order = Criteria::ASC) Order by the publisher_desc_short column
 * @method     ChildPublisherQuery orderByOrderBy($order = Criteria::ASC) Order by the publisher_order_by column
 * @method     ChildPublisherQuery orderByInsert($order = Criteria::ASC) Order by the publisher_insert column
 * @method     ChildPublisherQuery orderByUpdate($order = Criteria::ASC) Order by the publisher_update column
 * @method     ChildPublisherQuery orderByCreatedAt($order = Criteria::ASC) Order by the publisher_created column
 * @method     ChildPublisherQuery orderByUpdatedAt($order = Criteria::ASC) Order by the publisher_updated column
 *
 * @method     ChildPublisherQuery groupById() Group by the publisher_id column
 * @method     ChildPublisherQuery groupBySiteId() Group by the site_id column
 * @method     ChildPublisherQuery groupByName() Group by the publisher_name column
 * @method     ChildPublisherQuery groupByNameAlphabetic() Group by the publisher_name_alphabetic column
 * @method     ChildPublisherQuery groupByUrl() Group by the publisher_url column
 * @method     ChildPublisherQuery groupByNoosfereId() Group by the publisher_noosfere_id column
 * @method     ChildPublisherQuery groupByRepresentative() Group by the publisher_representative column
 * @method     ChildPublisherQuery groupByAddress() Group by the publisher_address column
 * @method     ChildPublisherQuery groupByPostalCode() Group by the publisher_postal_code column
 * @method     ChildPublisherQuery groupByCity() Group by the publisher_city column
 * @method     ChildPublisherQuery groupByCountry() Group by the publisher_country column
 * @method     ChildPublisherQuery groupByPhone() Group by the publisher_phone column
 * @method     ChildPublisherQuery groupByFax() Group by the publisher_fax column
 * @method     ChildPublisherQuery groupByWebsite() Group by the publisher_website column
 * @method     ChildPublisherQuery groupByBuyLink() Group by the publisher_buy_link column
 * @method     ChildPublisherQuery groupByEmail() Group by the publisher_email column
 * @method     ChildPublisherQuery groupByFacebook() Group by the publisher_facebook column
 * @method     ChildPublisherQuery groupByTwitter() Group by the publisher_twitter column
 * @method     ChildPublisherQuery groupByLegalForm() Group by the publisher_legal_form column
 * @method     ChildPublisherQuery groupByCreationYear() Group by the publisher_creation_year column
 * @method     ChildPublisherQuery groupByIsbn() Group by the publisher_isbn column
 * @method     ChildPublisherQuery groupByVolumes() Group by the publisher_volumes column
 * @method     ChildPublisherQuery groupByAverageRun() Group by the publisher_average_run column
 * @method     ChildPublisherQuery groupBySpecialities() Group by the publisher_specialities column
 * @method     ChildPublisherQuery groupByDiffuseur() Group by the publisher_diffuseur column
 * @method     ChildPublisherQuery groupByDistributeur() Group by the publisher_distributeur column
 * @method     ChildPublisherQuery groupByVpc() Group by the publisher_vpc column
 * @method     ChildPublisherQuery groupByPaypal() Group by the publisher_paypal column
 * @method     ChildPublisherQuery groupByShippingMode() Group by the publisher_shipping_mode column
 * @method     ChildPublisherQuery groupByShippingFee() Group by the publisher_shipping_fee column
 * @method     ChildPublisherQuery groupByGln() Group by the publisher_gln column
 * @method     ChildPublisherQuery groupByDesc() Group by the publisher_desc column
 * @method     ChildPublisherQuery groupByDescShort() Group by the publisher_desc_short column
 * @method     ChildPublisherQuery groupByOrderBy() Group by the publisher_order_by column
 * @method     ChildPublisherQuery groupByInsert() Group by the publisher_insert column
 * @method     ChildPublisherQuery groupByUpdate() Group by the publisher_update column
 * @method     ChildPublisherQuery groupByCreatedAt() Group by the publisher_created column
 * @method     ChildPublisherQuery groupByUpdatedAt() Group by the publisher_updated column
 *
 * @method     ChildPublisherQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPublisherQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPublisherQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPublisherQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPublisherQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPublisherQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPublisher|null findOne(ConnectionInterface $con = null) Return the first ChildPublisher matching the query
 * @method     ChildPublisher findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPublisher matching the query, or a new ChildPublisher object populated from the query conditions when no match is found
 *
 * @method     ChildPublisher|null findOneById(int $publisher_id) Return the first ChildPublisher filtered by the publisher_id column
 * @method     ChildPublisher|null findOneBySiteId(int $site_id) Return the first ChildPublisher filtered by the site_id column
 * @method     ChildPublisher|null findOneByName(string $publisher_name) Return the first ChildPublisher filtered by the publisher_name column
 * @method     ChildPublisher|null findOneByNameAlphabetic(string $publisher_name_alphabetic) Return the first ChildPublisher filtered by the publisher_name_alphabetic column
 * @method     ChildPublisher|null findOneByUrl(string $publisher_url) Return the first ChildPublisher filtered by the publisher_url column
 * @method     ChildPublisher|null findOneByNoosfereId(int $publisher_noosfere_id) Return the first ChildPublisher filtered by the publisher_noosfere_id column
 * @method     ChildPublisher|null findOneByRepresentative(string $publisher_representative) Return the first ChildPublisher filtered by the publisher_representative column
 * @method     ChildPublisher|null findOneByAddress(string $publisher_address) Return the first ChildPublisher filtered by the publisher_address column
 * @method     ChildPublisher|null findOneByPostalCode(string $publisher_postal_code) Return the first ChildPublisher filtered by the publisher_postal_code column
 * @method     ChildPublisher|null findOneByCity(string $publisher_city) Return the first ChildPublisher filtered by the publisher_city column
 * @method     ChildPublisher|null findOneByCountry(string $publisher_country) Return the first ChildPublisher filtered by the publisher_country column
 * @method     ChildPublisher|null findOneByPhone(string $publisher_phone) Return the first ChildPublisher filtered by the publisher_phone column
 * @method     ChildPublisher|null findOneByFax(string $publisher_fax) Return the first ChildPublisher filtered by the publisher_fax column
 * @method     ChildPublisher|null findOneByWebsite(string $publisher_website) Return the first ChildPublisher filtered by the publisher_website column
 * @method     ChildPublisher|null findOneByBuyLink(string $publisher_buy_link) Return the first ChildPublisher filtered by the publisher_buy_link column
 * @method     ChildPublisher|null findOneByEmail(string $publisher_email) Return the first ChildPublisher filtered by the publisher_email column
 * @method     ChildPublisher|null findOneByFacebook(string $publisher_facebook) Return the first ChildPublisher filtered by the publisher_facebook column
 * @method     ChildPublisher|null findOneByTwitter(string $publisher_twitter) Return the first ChildPublisher filtered by the publisher_twitter column
 * @method     ChildPublisher|null findOneByLegalForm(string $publisher_legal_form) Return the first ChildPublisher filtered by the publisher_legal_form column
 * @method     ChildPublisher|null findOneByCreationYear(string $publisher_creation_year) Return the first ChildPublisher filtered by the publisher_creation_year column
 * @method     ChildPublisher|null findOneByIsbn(string $publisher_isbn) Return the first ChildPublisher filtered by the publisher_isbn column
 * @method     ChildPublisher|null findOneByVolumes(int $publisher_volumes) Return the first ChildPublisher filtered by the publisher_volumes column
 * @method     ChildPublisher|null findOneByAverageRun(int $publisher_average_run) Return the first ChildPublisher filtered by the publisher_average_run column
 * @method     ChildPublisher|null findOneBySpecialities(string $publisher_specialities) Return the first ChildPublisher filtered by the publisher_specialities column
 * @method     ChildPublisher|null findOneByDiffuseur(string $publisher_diffuseur) Return the first ChildPublisher filtered by the publisher_diffuseur column
 * @method     ChildPublisher|null findOneByDistributeur(string $publisher_distributeur) Return the first ChildPublisher filtered by the publisher_distributeur column
 * @method     ChildPublisher|null findOneByVpc(boolean $publisher_vpc) Return the first ChildPublisher filtered by the publisher_vpc column
 * @method     ChildPublisher|null findOneByPaypal(string $publisher_paypal) Return the first ChildPublisher filtered by the publisher_paypal column
 * @method     ChildPublisher|null findOneByShippingMode(string $publisher_shipping_mode) Return the first ChildPublisher filtered by the publisher_shipping_mode column
 * @method     ChildPublisher|null findOneByShippingFee(int $publisher_shipping_fee) Return the first ChildPublisher filtered by the publisher_shipping_fee column
 * @method     ChildPublisher|null findOneByGln(string $publisher_gln) Return the first ChildPublisher filtered by the publisher_gln column
 * @method     ChildPublisher|null findOneByDesc(string $publisher_desc) Return the first ChildPublisher filtered by the publisher_desc column
 * @method     ChildPublisher|null findOneByDescShort(string $publisher_desc_short) Return the first ChildPublisher filtered by the publisher_desc_short column
 * @method     ChildPublisher|null findOneByOrderBy(string $publisher_order_by) Return the first ChildPublisher filtered by the publisher_order_by column
 * @method     ChildPublisher|null findOneByInsert(string $publisher_insert) Return the first ChildPublisher filtered by the publisher_insert column
 * @method     ChildPublisher|null findOneByUpdate(string $publisher_update) Return the first ChildPublisher filtered by the publisher_update column
 * @method     ChildPublisher|null findOneByCreatedAt(string $publisher_created) Return the first ChildPublisher filtered by the publisher_created column
 * @method     ChildPublisher|null findOneByUpdatedAt(string $publisher_updated) Return the first ChildPublisher filtered by the publisher_updated column *

 * @method     ChildPublisher requirePk($key, ConnectionInterface $con = null) Return the ChildPublisher by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOne(ConnectionInterface $con = null) Return the first ChildPublisher matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPublisher requireOneById(int $publisher_id) Return the first ChildPublisher filtered by the publisher_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneBySiteId(int $site_id) Return the first ChildPublisher filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByName(string $publisher_name) Return the first ChildPublisher filtered by the publisher_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByNameAlphabetic(string $publisher_name_alphabetic) Return the first ChildPublisher filtered by the publisher_name_alphabetic column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByUrl(string $publisher_url) Return the first ChildPublisher filtered by the publisher_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByNoosfereId(int $publisher_noosfere_id) Return the first ChildPublisher filtered by the publisher_noosfere_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByRepresentative(string $publisher_representative) Return the first ChildPublisher filtered by the publisher_representative column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByAddress(string $publisher_address) Return the first ChildPublisher filtered by the publisher_address column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByPostalCode(string $publisher_postal_code) Return the first ChildPublisher filtered by the publisher_postal_code column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByCity(string $publisher_city) Return the first ChildPublisher filtered by the publisher_city column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByCountry(string $publisher_country) Return the first ChildPublisher filtered by the publisher_country column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByPhone(string $publisher_phone) Return the first ChildPublisher filtered by the publisher_phone column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByFax(string $publisher_fax) Return the first ChildPublisher filtered by the publisher_fax column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByWebsite(string $publisher_website) Return the first ChildPublisher filtered by the publisher_website column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByBuyLink(string $publisher_buy_link) Return the first ChildPublisher filtered by the publisher_buy_link column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByEmail(string $publisher_email) Return the first ChildPublisher filtered by the publisher_email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByFacebook(string $publisher_facebook) Return the first ChildPublisher filtered by the publisher_facebook column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByTwitter(string $publisher_twitter) Return the first ChildPublisher filtered by the publisher_twitter column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByLegalForm(string $publisher_legal_form) Return the first ChildPublisher filtered by the publisher_legal_form column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByCreationYear(string $publisher_creation_year) Return the first ChildPublisher filtered by the publisher_creation_year column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByIsbn(string $publisher_isbn) Return the first ChildPublisher filtered by the publisher_isbn column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByVolumes(int $publisher_volumes) Return the first ChildPublisher filtered by the publisher_volumes column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByAverageRun(int $publisher_average_run) Return the first ChildPublisher filtered by the publisher_average_run column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneBySpecialities(string $publisher_specialities) Return the first ChildPublisher filtered by the publisher_specialities column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByDiffuseur(string $publisher_diffuseur) Return the first ChildPublisher filtered by the publisher_diffuseur column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByDistributeur(string $publisher_distributeur) Return the first ChildPublisher filtered by the publisher_distributeur column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByVpc(boolean $publisher_vpc) Return the first ChildPublisher filtered by the publisher_vpc column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByPaypal(string $publisher_paypal) Return the first ChildPublisher filtered by the publisher_paypal column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByShippingMode(string $publisher_shipping_mode) Return the first ChildPublisher filtered by the publisher_shipping_mode column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByShippingFee(int $publisher_shipping_fee) Return the first ChildPublisher filtered by the publisher_shipping_fee column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByGln(string $publisher_gln) Return the first ChildPublisher filtered by the publisher_gln column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByDesc(string $publisher_desc) Return the first ChildPublisher filtered by the publisher_desc column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByDescShort(string $publisher_desc_short) Return the first ChildPublisher filtered by the publisher_desc_short column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByOrderBy(string $publisher_order_by) Return the first ChildPublisher filtered by the publisher_order_by column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByInsert(string $publisher_insert) Return the first ChildPublisher filtered by the publisher_insert column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByUpdate(string $publisher_update) Return the first ChildPublisher filtered by the publisher_update column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByCreatedAt(string $publisher_created) Return the first ChildPublisher filtered by the publisher_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOneByUpdatedAt(string $publisher_updated) Return the first ChildPublisher filtered by the publisher_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPublisher[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPublisher objects based on current ModelCriteria
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> find(ConnectionInterface $con = null) Return ChildPublisher objects based on current ModelCriteria
 * @method     ChildPublisher[]|ObjectCollection findById(int $publisher_id) Return ChildPublisher objects filtered by the publisher_id column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findById(int $publisher_id) Return ChildPublisher objects filtered by the publisher_id column
 * @method     ChildPublisher[]|ObjectCollection findBySiteId(int $site_id) Return ChildPublisher objects filtered by the site_id column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findBySiteId(int $site_id) Return ChildPublisher objects filtered by the site_id column
 * @method     ChildPublisher[]|ObjectCollection findByName(string $publisher_name) Return ChildPublisher objects filtered by the publisher_name column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByName(string $publisher_name) Return ChildPublisher objects filtered by the publisher_name column
 * @method     ChildPublisher[]|ObjectCollection findByNameAlphabetic(string $publisher_name_alphabetic) Return ChildPublisher objects filtered by the publisher_name_alphabetic column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByNameAlphabetic(string $publisher_name_alphabetic) Return ChildPublisher objects filtered by the publisher_name_alphabetic column
 * @method     ChildPublisher[]|ObjectCollection findByUrl(string $publisher_url) Return ChildPublisher objects filtered by the publisher_url column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByUrl(string $publisher_url) Return ChildPublisher objects filtered by the publisher_url column
 * @method     ChildPublisher[]|ObjectCollection findByNoosfereId(int $publisher_noosfere_id) Return ChildPublisher objects filtered by the publisher_noosfere_id column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByNoosfereId(int $publisher_noosfere_id) Return ChildPublisher objects filtered by the publisher_noosfere_id column
 * @method     ChildPublisher[]|ObjectCollection findByRepresentative(string $publisher_representative) Return ChildPublisher objects filtered by the publisher_representative column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByRepresentative(string $publisher_representative) Return ChildPublisher objects filtered by the publisher_representative column
 * @method     ChildPublisher[]|ObjectCollection findByAddress(string $publisher_address) Return ChildPublisher objects filtered by the publisher_address column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByAddress(string $publisher_address) Return ChildPublisher objects filtered by the publisher_address column
 * @method     ChildPublisher[]|ObjectCollection findByPostalCode(string $publisher_postal_code) Return ChildPublisher objects filtered by the publisher_postal_code column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByPostalCode(string $publisher_postal_code) Return ChildPublisher objects filtered by the publisher_postal_code column
 * @method     ChildPublisher[]|ObjectCollection findByCity(string $publisher_city) Return ChildPublisher objects filtered by the publisher_city column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByCity(string $publisher_city) Return ChildPublisher objects filtered by the publisher_city column
 * @method     ChildPublisher[]|ObjectCollection findByCountry(string $publisher_country) Return ChildPublisher objects filtered by the publisher_country column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByCountry(string $publisher_country) Return ChildPublisher objects filtered by the publisher_country column
 * @method     ChildPublisher[]|ObjectCollection findByPhone(string $publisher_phone) Return ChildPublisher objects filtered by the publisher_phone column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByPhone(string $publisher_phone) Return ChildPublisher objects filtered by the publisher_phone column
 * @method     ChildPublisher[]|ObjectCollection findByFax(string $publisher_fax) Return ChildPublisher objects filtered by the publisher_fax column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByFax(string $publisher_fax) Return ChildPublisher objects filtered by the publisher_fax column
 * @method     ChildPublisher[]|ObjectCollection findByWebsite(string $publisher_website) Return ChildPublisher objects filtered by the publisher_website column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByWebsite(string $publisher_website) Return ChildPublisher objects filtered by the publisher_website column
 * @method     ChildPublisher[]|ObjectCollection findByBuyLink(string $publisher_buy_link) Return ChildPublisher objects filtered by the publisher_buy_link column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByBuyLink(string $publisher_buy_link) Return ChildPublisher objects filtered by the publisher_buy_link column
 * @method     ChildPublisher[]|ObjectCollection findByEmail(string $publisher_email) Return ChildPublisher objects filtered by the publisher_email column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByEmail(string $publisher_email) Return ChildPublisher objects filtered by the publisher_email column
 * @method     ChildPublisher[]|ObjectCollection findByFacebook(string $publisher_facebook) Return ChildPublisher objects filtered by the publisher_facebook column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByFacebook(string $publisher_facebook) Return ChildPublisher objects filtered by the publisher_facebook column
 * @method     ChildPublisher[]|ObjectCollection findByTwitter(string $publisher_twitter) Return ChildPublisher objects filtered by the publisher_twitter column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByTwitter(string $publisher_twitter) Return ChildPublisher objects filtered by the publisher_twitter column
 * @method     ChildPublisher[]|ObjectCollection findByLegalForm(string $publisher_legal_form) Return ChildPublisher objects filtered by the publisher_legal_form column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByLegalForm(string $publisher_legal_form) Return ChildPublisher objects filtered by the publisher_legal_form column
 * @method     ChildPublisher[]|ObjectCollection findByCreationYear(string $publisher_creation_year) Return ChildPublisher objects filtered by the publisher_creation_year column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByCreationYear(string $publisher_creation_year) Return ChildPublisher objects filtered by the publisher_creation_year column
 * @method     ChildPublisher[]|ObjectCollection findByIsbn(string $publisher_isbn) Return ChildPublisher objects filtered by the publisher_isbn column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByIsbn(string $publisher_isbn) Return ChildPublisher objects filtered by the publisher_isbn column
 * @method     ChildPublisher[]|ObjectCollection findByVolumes(int $publisher_volumes) Return ChildPublisher objects filtered by the publisher_volumes column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByVolumes(int $publisher_volumes) Return ChildPublisher objects filtered by the publisher_volumes column
 * @method     ChildPublisher[]|ObjectCollection findByAverageRun(int $publisher_average_run) Return ChildPublisher objects filtered by the publisher_average_run column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByAverageRun(int $publisher_average_run) Return ChildPublisher objects filtered by the publisher_average_run column
 * @method     ChildPublisher[]|ObjectCollection findBySpecialities(string $publisher_specialities) Return ChildPublisher objects filtered by the publisher_specialities column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findBySpecialities(string $publisher_specialities) Return ChildPublisher objects filtered by the publisher_specialities column
 * @method     ChildPublisher[]|ObjectCollection findByDiffuseur(string $publisher_diffuseur) Return ChildPublisher objects filtered by the publisher_diffuseur column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByDiffuseur(string $publisher_diffuseur) Return ChildPublisher objects filtered by the publisher_diffuseur column
 * @method     ChildPublisher[]|ObjectCollection findByDistributeur(string $publisher_distributeur) Return ChildPublisher objects filtered by the publisher_distributeur column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByDistributeur(string $publisher_distributeur) Return ChildPublisher objects filtered by the publisher_distributeur column
 * @method     ChildPublisher[]|ObjectCollection findByVpc(boolean $publisher_vpc) Return ChildPublisher objects filtered by the publisher_vpc column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByVpc(boolean $publisher_vpc) Return ChildPublisher objects filtered by the publisher_vpc column
 * @method     ChildPublisher[]|ObjectCollection findByPaypal(string $publisher_paypal) Return ChildPublisher objects filtered by the publisher_paypal column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByPaypal(string $publisher_paypal) Return ChildPublisher objects filtered by the publisher_paypal column
 * @method     ChildPublisher[]|ObjectCollection findByShippingMode(string $publisher_shipping_mode) Return ChildPublisher objects filtered by the publisher_shipping_mode column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByShippingMode(string $publisher_shipping_mode) Return ChildPublisher objects filtered by the publisher_shipping_mode column
 * @method     ChildPublisher[]|ObjectCollection findByShippingFee(int $publisher_shipping_fee) Return ChildPublisher objects filtered by the publisher_shipping_fee column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByShippingFee(int $publisher_shipping_fee) Return ChildPublisher objects filtered by the publisher_shipping_fee column
 * @method     ChildPublisher[]|ObjectCollection findByGln(string $publisher_gln) Return ChildPublisher objects filtered by the publisher_gln column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByGln(string $publisher_gln) Return ChildPublisher objects filtered by the publisher_gln column
 * @method     ChildPublisher[]|ObjectCollection findByDesc(string $publisher_desc) Return ChildPublisher objects filtered by the publisher_desc column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByDesc(string $publisher_desc) Return ChildPublisher objects filtered by the publisher_desc column
 * @method     ChildPublisher[]|ObjectCollection findByDescShort(string $publisher_desc_short) Return ChildPublisher objects filtered by the publisher_desc_short column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByDescShort(string $publisher_desc_short) Return ChildPublisher objects filtered by the publisher_desc_short column
 * @method     ChildPublisher[]|ObjectCollection findByOrderBy(string $publisher_order_by) Return ChildPublisher objects filtered by the publisher_order_by column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByOrderBy(string $publisher_order_by) Return ChildPublisher objects filtered by the publisher_order_by column
 * @method     ChildPublisher[]|ObjectCollection findByInsert(string $publisher_insert) Return ChildPublisher objects filtered by the publisher_insert column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByInsert(string $publisher_insert) Return ChildPublisher objects filtered by the publisher_insert column
 * @method     ChildPublisher[]|ObjectCollection findByUpdate(string $publisher_update) Return ChildPublisher objects filtered by the publisher_update column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByUpdate(string $publisher_update) Return ChildPublisher objects filtered by the publisher_update column
 * @method     ChildPublisher[]|ObjectCollection findByCreatedAt(string $publisher_created) Return ChildPublisher objects filtered by the publisher_created column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByCreatedAt(string $publisher_created) Return ChildPublisher objects filtered by the publisher_created column
 * @method     ChildPublisher[]|ObjectCollection findByUpdatedAt(string $publisher_updated) Return ChildPublisher objects filtered by the publisher_updated column
 * @psalm-method ObjectCollection&\Traversable<ChildPublisher> findByUpdatedAt(string $publisher_updated) Return ChildPublisher objects filtered by the publisher_updated column
 * @method     ChildPublisher[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildPublisher> paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PublisherQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\PublisherQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Publisher', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPublisherQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPublisherQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPublisherQuery) {
            return $criteria;
        }
        $query = new ChildPublisherQuery();
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
     * @return ChildPublisher|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PublisherTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PublisherTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildPublisher A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT publisher_id, site_id, publisher_name, publisher_name_alphabetic, publisher_url, publisher_noosfere_id, publisher_representative, publisher_address, publisher_postal_code, publisher_city, publisher_country, publisher_phone, publisher_fax, publisher_website, publisher_buy_link, publisher_email, publisher_facebook, publisher_twitter, publisher_legal_form, publisher_creation_year, publisher_isbn, publisher_volumes, publisher_average_run, publisher_specialities, publisher_diffuseur, publisher_distributeur, publisher_vpc, publisher_paypal, publisher_shipping_mode, publisher_shipping_fee, publisher_gln, publisher_desc, publisher_desc_short, publisher_order_by, publisher_insert, publisher_update, publisher_created, publisher_updated FROM publishers WHERE publisher_id = :p0';
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
            /** @var ChildPublisher $obj */
            $obj = new ChildPublisher();
            $obj->hydrate($row);
            PublisherTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPublisher|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the publisher_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE publisher_id = 1234
     * $query->filterById(array(12, 34)); // WHERE publisher_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE publisher_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_ID, $id, $comparison);
    }

    /**
     * Filter the query on the site_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySiteId(1234); // WHERE site_id = 1234
     * $query->filterBySiteId(array(12, 34)); // WHERE site_id IN (12, 34)
     * $query->filterBySiteId(array('min' => 12)); // WHERE site_id > 12
     * </code>
     *
     * @param     mixed $siteId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterBySiteId($siteId = null, $comparison = null)
    {
        if (is_array($siteId)) {
            $useMinMax = false;
            if (isset($siteId['min'])) {
                $this->addUsingAlias(PublisherTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(PublisherTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_SITE_ID, $siteId, $comparison);
    }

    /**
     * Filter the query on the publisher_name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE publisher_name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE publisher_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the publisher_name_alphabetic column
     *
     * Example usage:
     * <code>
     * $query->filterByNameAlphabetic('fooValue');   // WHERE publisher_name_alphabetic = 'fooValue'
     * $query->filterByNameAlphabetic('%fooValue%', Criteria::LIKE); // WHERE publisher_name_alphabetic LIKE '%fooValue%'
     * </code>
     *
     * @param     string $nameAlphabetic The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByNameAlphabetic($nameAlphabetic = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nameAlphabetic)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_NAME_ALPHABETIC, $nameAlphabetic, $comparison);
    }

    /**
     * Filter the query on the publisher_url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE publisher_url = 'fooValue'
     * $query->filterByUrl('%fooValue%', Criteria::LIKE); // WHERE publisher_url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $url The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByUrl($url = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($url)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_URL, $url, $comparison);
    }

    /**
     * Filter the query on the publisher_noosfere_id column
     *
     * Example usage:
     * <code>
     * $query->filterByNoosfereId(1234); // WHERE publisher_noosfere_id = 1234
     * $query->filterByNoosfereId(array(12, 34)); // WHERE publisher_noosfere_id IN (12, 34)
     * $query->filterByNoosfereId(array('min' => 12)); // WHERE publisher_noosfere_id > 12
     * </code>
     *
     * @param     mixed $noosfereId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByNoosfereId($noosfereId = null, $comparison = null)
    {
        if (is_array($noosfereId)) {
            $useMinMax = false;
            if (isset($noosfereId['min'])) {
                $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_NOOSFERE_ID, $noosfereId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($noosfereId['max'])) {
                $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_NOOSFERE_ID, $noosfereId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_NOOSFERE_ID, $noosfereId, $comparison);
    }

    /**
     * Filter the query on the publisher_representative column
     *
     * Example usage:
     * <code>
     * $query->filterByRepresentative('fooValue');   // WHERE publisher_representative = 'fooValue'
     * $query->filterByRepresentative('%fooValue%', Criteria::LIKE); // WHERE publisher_representative LIKE '%fooValue%'
     * </code>
     *
     * @param     string $representative The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByRepresentative($representative = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($representative)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_REPRESENTATIVE, $representative, $comparison);
    }

    /**
     * Filter the query on the publisher_address column
     *
     * Example usage:
     * <code>
     * $query->filterByAddress('fooValue');   // WHERE publisher_address = 'fooValue'
     * $query->filterByAddress('%fooValue%', Criteria::LIKE); // WHERE publisher_address LIKE '%fooValue%'
     * </code>
     *
     * @param     string $address The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByAddress($address = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($address)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_ADDRESS, $address, $comparison);
    }

    /**
     * Filter the query on the publisher_postal_code column
     *
     * Example usage:
     * <code>
     * $query->filterByPostalCode('fooValue');   // WHERE publisher_postal_code = 'fooValue'
     * $query->filterByPostalCode('%fooValue%', Criteria::LIKE); // WHERE publisher_postal_code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $postalCode The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByPostalCode($postalCode = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($postalCode)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_POSTAL_CODE, $postalCode, $comparison);
    }

    /**
     * Filter the query on the publisher_city column
     *
     * Example usage:
     * <code>
     * $query->filterByCity('fooValue');   // WHERE publisher_city = 'fooValue'
     * $query->filterByCity('%fooValue%', Criteria::LIKE); // WHERE publisher_city LIKE '%fooValue%'
     * </code>
     *
     * @param     string $city The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByCity($city = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($city)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_CITY, $city, $comparison);
    }

    /**
     * Filter the query on the publisher_country column
     *
     * Example usage:
     * <code>
     * $query->filterByCountry('fooValue');   // WHERE publisher_country = 'fooValue'
     * $query->filterByCountry('%fooValue%', Criteria::LIKE); // WHERE publisher_country LIKE '%fooValue%'
     * </code>
     *
     * @param     string $country The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByCountry($country = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($country)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_COUNTRY, $country, $comparison);
    }

    /**
     * Filter the query on the publisher_phone column
     *
     * Example usage:
     * <code>
     * $query->filterByPhone('fooValue');   // WHERE publisher_phone = 'fooValue'
     * $query->filterByPhone('%fooValue%', Criteria::LIKE); // WHERE publisher_phone LIKE '%fooValue%'
     * </code>
     *
     * @param     string $phone The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByPhone($phone = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($phone)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_PHONE, $phone, $comparison);
    }

    /**
     * Filter the query on the publisher_fax column
     *
     * Example usage:
     * <code>
     * $query->filterByFax('fooValue');   // WHERE publisher_fax = 'fooValue'
     * $query->filterByFax('%fooValue%', Criteria::LIKE); // WHERE publisher_fax LIKE '%fooValue%'
     * </code>
     *
     * @param     string $fax The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByFax($fax = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($fax)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_FAX, $fax, $comparison);
    }

    /**
     * Filter the query on the publisher_website column
     *
     * Example usage:
     * <code>
     * $query->filterByWebsite('fooValue');   // WHERE publisher_website = 'fooValue'
     * $query->filterByWebsite('%fooValue%', Criteria::LIKE); // WHERE publisher_website LIKE '%fooValue%'
     * </code>
     *
     * @param     string $website The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByWebsite($website = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($website)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_WEBSITE, $website, $comparison);
    }

    /**
     * Filter the query on the publisher_buy_link column
     *
     * Example usage:
     * <code>
     * $query->filterByBuyLink('fooValue');   // WHERE publisher_buy_link = 'fooValue'
     * $query->filterByBuyLink('%fooValue%', Criteria::LIKE); // WHERE publisher_buy_link LIKE '%fooValue%'
     * </code>
     *
     * @param     string $buyLink The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByBuyLink($buyLink = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($buyLink)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_BUY_LINK, $buyLink, $comparison);
    }

    /**
     * Filter the query on the publisher_email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE publisher_email = 'fooValue'
     * $query->filterByEmail('%fooValue%', Criteria::LIKE); // WHERE publisher_email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the publisher_facebook column
     *
     * Example usage:
     * <code>
     * $query->filterByFacebook('fooValue');   // WHERE publisher_facebook = 'fooValue'
     * $query->filterByFacebook('%fooValue%', Criteria::LIKE); // WHERE publisher_facebook LIKE '%fooValue%'
     * </code>
     *
     * @param     string $facebook The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByFacebook($facebook = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($facebook)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_FACEBOOK, $facebook, $comparison);
    }

    /**
     * Filter the query on the publisher_twitter column
     *
     * Example usage:
     * <code>
     * $query->filterByTwitter('fooValue');   // WHERE publisher_twitter = 'fooValue'
     * $query->filterByTwitter('%fooValue%', Criteria::LIKE); // WHERE publisher_twitter LIKE '%fooValue%'
     * </code>
     *
     * @param     string $twitter The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByTwitter($twitter = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($twitter)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_TWITTER, $twitter, $comparison);
    }

    /**
     * Filter the query on the publisher_legal_form column
     *
     * Example usage:
     * <code>
     * $query->filterByLegalForm('fooValue');   // WHERE publisher_legal_form = 'fooValue'
     * $query->filterByLegalForm('%fooValue%', Criteria::LIKE); // WHERE publisher_legal_form LIKE '%fooValue%'
     * </code>
     *
     * @param     string $legalForm The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByLegalForm($legalForm = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($legalForm)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_LEGAL_FORM, $legalForm, $comparison);
    }

    /**
     * Filter the query on the publisher_creation_year column
     *
     * Example usage:
     * <code>
     * $query->filterByCreationYear('fooValue');   // WHERE publisher_creation_year = 'fooValue'
     * $query->filterByCreationYear('%fooValue%', Criteria::LIKE); // WHERE publisher_creation_year LIKE '%fooValue%'
     * </code>
     *
     * @param     string $creationYear The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByCreationYear($creationYear = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($creationYear)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_CREATION_YEAR, $creationYear, $comparison);
    }

    /**
     * Filter the query on the publisher_isbn column
     *
     * Example usage:
     * <code>
     * $query->filterByIsbn('fooValue');   // WHERE publisher_isbn = 'fooValue'
     * $query->filterByIsbn('%fooValue%', Criteria::LIKE); // WHERE publisher_isbn LIKE '%fooValue%'
     * </code>
     *
     * @param     string $isbn The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByIsbn($isbn = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($isbn)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_ISBN, $isbn, $comparison);
    }

    /**
     * Filter the query on the publisher_volumes column
     *
     * Example usage:
     * <code>
     * $query->filterByVolumes(1234); // WHERE publisher_volumes = 1234
     * $query->filterByVolumes(array(12, 34)); // WHERE publisher_volumes IN (12, 34)
     * $query->filterByVolumes(array('min' => 12)); // WHERE publisher_volumes > 12
     * </code>
     *
     * @param     mixed $volumes The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByVolumes($volumes = null, $comparison = null)
    {
        if (is_array($volumes)) {
            $useMinMax = false;
            if (isset($volumes['min'])) {
                $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_VOLUMES, $volumes['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($volumes['max'])) {
                $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_VOLUMES, $volumes['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_VOLUMES, $volumes, $comparison);
    }

    /**
     * Filter the query on the publisher_average_run column
     *
     * Example usage:
     * <code>
     * $query->filterByAverageRun(1234); // WHERE publisher_average_run = 1234
     * $query->filterByAverageRun(array(12, 34)); // WHERE publisher_average_run IN (12, 34)
     * $query->filterByAverageRun(array('min' => 12)); // WHERE publisher_average_run > 12
     * </code>
     *
     * @param     mixed $averageRun The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByAverageRun($averageRun = null, $comparison = null)
    {
        if (is_array($averageRun)) {
            $useMinMax = false;
            if (isset($averageRun['min'])) {
                $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_AVERAGE_RUN, $averageRun['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($averageRun['max'])) {
                $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_AVERAGE_RUN, $averageRun['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_AVERAGE_RUN, $averageRun, $comparison);
    }

    /**
     * Filter the query on the publisher_specialities column
     *
     * Example usage:
     * <code>
     * $query->filterBySpecialities('fooValue');   // WHERE publisher_specialities = 'fooValue'
     * $query->filterBySpecialities('%fooValue%', Criteria::LIKE); // WHERE publisher_specialities LIKE '%fooValue%'
     * </code>
     *
     * @param     string $specialities The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterBySpecialities($specialities = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($specialities)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_SPECIALITIES, $specialities, $comparison);
    }

    /**
     * Filter the query on the publisher_diffuseur column
     *
     * Example usage:
     * <code>
     * $query->filterByDiffuseur('fooValue');   // WHERE publisher_diffuseur = 'fooValue'
     * $query->filterByDiffuseur('%fooValue%', Criteria::LIKE); // WHERE publisher_diffuseur LIKE '%fooValue%'
     * </code>
     *
     * @param     string $diffuseur The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByDiffuseur($diffuseur = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($diffuseur)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_DIFFUSEUR, $diffuseur, $comparison);
    }

    /**
     * Filter the query on the publisher_distributeur column
     *
     * Example usage:
     * <code>
     * $query->filterByDistributeur('fooValue');   // WHERE publisher_distributeur = 'fooValue'
     * $query->filterByDistributeur('%fooValue%', Criteria::LIKE); // WHERE publisher_distributeur LIKE '%fooValue%'
     * </code>
     *
     * @param     string $distributeur The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByDistributeur($distributeur = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($distributeur)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_DISTRIBUTEUR, $distributeur, $comparison);
    }

    /**
     * Filter the query on the publisher_vpc column
     *
     * Example usage:
     * <code>
     * $query->filterByVpc(true); // WHERE publisher_vpc = true
     * $query->filterByVpc('yes'); // WHERE publisher_vpc = true
     * </code>
     *
     * @param     boolean|string $vpc The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByVpc($vpc = null, $comparison = null)
    {
        if (is_string($vpc)) {
            $vpc = in_array(strtolower($vpc), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_VPC, $vpc, $comparison);
    }

    /**
     * Filter the query on the publisher_paypal column
     *
     * Example usage:
     * <code>
     * $query->filterByPaypal('fooValue');   // WHERE publisher_paypal = 'fooValue'
     * $query->filterByPaypal('%fooValue%', Criteria::LIKE); // WHERE publisher_paypal LIKE '%fooValue%'
     * </code>
     *
     * @param     string $paypal The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByPaypal($paypal = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($paypal)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_PAYPAL, $paypal, $comparison);
    }

    /**
     * Filter the query on the publisher_shipping_mode column
     *
     * Example usage:
     * <code>
     * $query->filterByShippingMode('fooValue');   // WHERE publisher_shipping_mode = 'fooValue'
     * $query->filterByShippingMode('%fooValue%', Criteria::LIKE); // WHERE publisher_shipping_mode LIKE '%fooValue%'
     * </code>
     *
     * @param     string $shippingMode The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByShippingMode($shippingMode = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($shippingMode)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_SHIPPING_MODE, $shippingMode, $comparison);
    }

    /**
     * Filter the query on the publisher_shipping_fee column
     *
     * Example usage:
     * <code>
     * $query->filterByShippingFee(1234); // WHERE publisher_shipping_fee = 1234
     * $query->filterByShippingFee(array(12, 34)); // WHERE publisher_shipping_fee IN (12, 34)
     * $query->filterByShippingFee(array('min' => 12)); // WHERE publisher_shipping_fee > 12
     * </code>
     *
     * @param     mixed $shippingFee The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByShippingFee($shippingFee = null, $comparison = null)
    {
        if (is_array($shippingFee)) {
            $useMinMax = false;
            if (isset($shippingFee['min'])) {
                $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_SHIPPING_FEE, $shippingFee['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($shippingFee['max'])) {
                $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_SHIPPING_FEE, $shippingFee['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_SHIPPING_FEE, $shippingFee, $comparison);
    }

    /**
     * Filter the query on the publisher_gln column
     *
     * Example usage:
     * <code>
     * $query->filterByGln(1234); // WHERE publisher_gln = 1234
     * $query->filterByGln(array(12, 34)); // WHERE publisher_gln IN (12, 34)
     * $query->filterByGln(array('min' => 12)); // WHERE publisher_gln > 12
     * </code>
     *
     * @param     mixed $gln The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByGln($gln = null, $comparison = null)
    {
        if (is_array($gln)) {
            $useMinMax = false;
            if (isset($gln['min'])) {
                $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_GLN, $gln['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($gln['max'])) {
                $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_GLN, $gln['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_GLN, $gln, $comparison);
    }

    /**
     * Filter the query on the publisher_desc column
     *
     * Example usage:
     * <code>
     * $query->filterByDesc('fooValue');   // WHERE publisher_desc = 'fooValue'
     * $query->filterByDesc('%fooValue%', Criteria::LIKE); // WHERE publisher_desc LIKE '%fooValue%'
     * </code>
     *
     * @param     string $desc The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByDesc($desc = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($desc)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_DESC, $desc, $comparison);
    }

    /**
     * Filter the query on the publisher_desc_short column
     *
     * Example usage:
     * <code>
     * $query->filterByDescShort('fooValue');   // WHERE publisher_desc_short = 'fooValue'
     * $query->filterByDescShort('%fooValue%', Criteria::LIKE); // WHERE publisher_desc_short LIKE '%fooValue%'
     * </code>
     *
     * @param     string $descShort The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByDescShort($descShort = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($descShort)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_DESC_SHORT, $descShort, $comparison);
    }

    /**
     * Filter the query on the publisher_order_by column
     *
     * Example usage:
     * <code>
     * $query->filterByOrderBy('fooValue');   // WHERE publisher_order_by = 'fooValue'
     * $query->filterByOrderBy('%fooValue%', Criteria::LIKE); // WHERE publisher_order_by LIKE '%fooValue%'
     * </code>
     *
     * @param     string $orderBy The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByOrderBy($orderBy = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($orderBy)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_ORDER_BY, $orderBy, $comparison);
    }

    /**
     * Filter the query on the publisher_insert column
     *
     * Example usage:
     * <code>
     * $query->filterByInsert('2011-03-14'); // WHERE publisher_insert = '2011-03-14'
     * $query->filterByInsert('now'); // WHERE publisher_insert = '2011-03-14'
     * $query->filterByInsert(array('max' => 'yesterday')); // WHERE publisher_insert > '2011-03-13'
     * </code>
     *
     * @param     mixed $insert The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByInsert($insert = null, $comparison = null)
    {
        if (is_array($insert)) {
            $useMinMax = false;
            if (isset($insert['min'])) {
                $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_INSERT, $insert['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($insert['max'])) {
                $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_INSERT, $insert['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_INSERT, $insert, $comparison);
    }

    /**
     * Filter the query on the publisher_update column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdate('2011-03-14'); // WHERE publisher_update = '2011-03-14'
     * $query->filterByUpdate('now'); // WHERE publisher_update = '2011-03-14'
     * $query->filterByUpdate(array('max' => 'yesterday')); // WHERE publisher_update > '2011-03-13'
     * </code>
     *
     * @param     mixed $update The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByUpdate($update = null, $comparison = null)
    {
        if (is_array($update)) {
            $useMinMax = false;
            if (isset($update['min'])) {
                $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_UPDATE, $update['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($update['max'])) {
                $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_UPDATE, $update['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_UPDATE, $update, $comparison);
    }

    /**
     * Filter the query on the publisher_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE publisher_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE publisher_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE publisher_created > '2011-03-13'
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
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_CREATED, $createdAt, $comparison);
    }

    /**
     * Filter the query on the publisher_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE publisher_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE publisher_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE publisher_updated > '2011-03-13'
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
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPublisher $publisher Object to remove from the list of results
     *
     * @return $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function prune($publisher = null)
    {
        if ($publisher) {
            $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_ID, $publisher->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the publishers table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PublisherTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PublisherTableMap::clearInstancePool();
            PublisherTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PublisherTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PublisherTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PublisherTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PublisherTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PublisherTableMap::COL_PUBLISHER_UPDATED);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PublisherTableMap::COL_PUBLISHER_UPDATED);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PublisherTableMap::COL_PUBLISHER_CREATED);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildPublisherQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PublisherTableMap::COL_PUBLISHER_CREATED);
    }

} // PublisherQuery
