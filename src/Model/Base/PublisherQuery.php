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
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `publishers` table.
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
 * @method     ChildPublisherQuery leftJoinArticle($relationAlias = null) Adds a LEFT JOIN clause to the query using the Article relation
 * @method     ChildPublisherQuery rightJoinArticle($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Article relation
 * @method     ChildPublisherQuery innerJoinArticle($relationAlias = null) Adds a INNER JOIN clause to the query using the Article relation
 *
 * @method     ChildPublisherQuery joinWithArticle($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Article relation
 *
 * @method     ChildPublisherQuery leftJoinWithArticle() Adds a LEFT JOIN clause and with to the query using the Article relation
 * @method     ChildPublisherQuery rightJoinWithArticle() Adds a RIGHT JOIN clause and with to the query using the Article relation
 * @method     ChildPublisherQuery innerJoinWithArticle() Adds a INNER JOIN clause and with to the query using the Article relation
 *
 * @method     ChildPublisherQuery leftJoinImage($relationAlias = null) Adds a LEFT JOIN clause to the query using the Image relation
 * @method     ChildPublisherQuery rightJoinImage($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Image relation
 * @method     ChildPublisherQuery innerJoinImage($relationAlias = null) Adds a INNER JOIN clause to the query using the Image relation
 *
 * @method     ChildPublisherQuery joinWithImage($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Image relation
 *
 * @method     ChildPublisherQuery leftJoinWithImage() Adds a LEFT JOIN clause and with to the query using the Image relation
 * @method     ChildPublisherQuery rightJoinWithImage() Adds a RIGHT JOIN clause and with to the query using the Image relation
 * @method     ChildPublisherQuery innerJoinWithImage() Adds a INNER JOIN clause and with to the query using the Image relation
 *
 * @method     ChildPublisherQuery leftJoinRight($relationAlias = null) Adds a LEFT JOIN clause to the query using the Right relation
 * @method     ChildPublisherQuery rightJoinRight($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Right relation
 * @method     ChildPublisherQuery innerJoinRight($relationAlias = null) Adds a INNER JOIN clause to the query using the Right relation
 *
 * @method     ChildPublisherQuery joinWithRight($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Right relation
 *
 * @method     ChildPublisherQuery leftJoinWithRight() Adds a LEFT JOIN clause and with to the query using the Right relation
 * @method     ChildPublisherQuery rightJoinWithRight() Adds a RIGHT JOIN clause and with to the query using the Right relation
 * @method     ChildPublisherQuery innerJoinWithRight() Adds a INNER JOIN clause and with to the query using the Right relation
 *
 * @method     \Model\ArticleQuery|\Model\ImageQuery|\Model\RightQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPublisher|null findOne(?ConnectionInterface $con = null) Return the first ChildPublisher matching the query
 * @method     ChildPublisher findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildPublisher matching the query, or a new ChildPublisher object populated from the query conditions when no match is found
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
 * @method     ChildPublisher|null findOneByUpdatedAt(string $publisher_updated) Return the first ChildPublisher filtered by the publisher_updated column
 *
 * @method     ChildPublisher requirePk($key, ?ConnectionInterface $con = null) Return the ChildPublisher by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPublisher requireOne(?ConnectionInterface $con = null) Return the first ChildPublisher matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
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
 * @method     ChildPublisher[]|Collection find(?ConnectionInterface $con = null) Return ChildPublisher objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildPublisher> find(?ConnectionInterface $con = null) Return ChildPublisher objects based on current ModelCriteria
 *
 * @method     ChildPublisher[]|Collection findById(int|array<int> $publisher_id) Return ChildPublisher objects filtered by the publisher_id column
 * @psalm-method Collection&\Traversable<ChildPublisher> findById(int|array<int> $publisher_id) Return ChildPublisher objects filtered by the publisher_id column
 * @method     ChildPublisher[]|Collection findBySiteId(int|array<int> $site_id) Return ChildPublisher objects filtered by the site_id column
 * @psalm-method Collection&\Traversable<ChildPublisher> findBySiteId(int|array<int> $site_id) Return ChildPublisher objects filtered by the site_id column
 * @method     ChildPublisher[]|Collection findByName(string|array<string> $publisher_name) Return ChildPublisher objects filtered by the publisher_name column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByName(string|array<string> $publisher_name) Return ChildPublisher objects filtered by the publisher_name column
 * @method     ChildPublisher[]|Collection findByNameAlphabetic(string|array<string> $publisher_name_alphabetic) Return ChildPublisher objects filtered by the publisher_name_alphabetic column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByNameAlphabetic(string|array<string> $publisher_name_alphabetic) Return ChildPublisher objects filtered by the publisher_name_alphabetic column
 * @method     ChildPublisher[]|Collection findByUrl(string|array<string> $publisher_url) Return ChildPublisher objects filtered by the publisher_url column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByUrl(string|array<string> $publisher_url) Return ChildPublisher objects filtered by the publisher_url column
 * @method     ChildPublisher[]|Collection findByNoosfereId(int|array<int> $publisher_noosfere_id) Return ChildPublisher objects filtered by the publisher_noosfere_id column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByNoosfereId(int|array<int> $publisher_noosfere_id) Return ChildPublisher objects filtered by the publisher_noosfere_id column
 * @method     ChildPublisher[]|Collection findByRepresentative(string|array<string> $publisher_representative) Return ChildPublisher objects filtered by the publisher_representative column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByRepresentative(string|array<string> $publisher_representative) Return ChildPublisher objects filtered by the publisher_representative column
 * @method     ChildPublisher[]|Collection findByAddress(string|array<string> $publisher_address) Return ChildPublisher objects filtered by the publisher_address column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByAddress(string|array<string> $publisher_address) Return ChildPublisher objects filtered by the publisher_address column
 * @method     ChildPublisher[]|Collection findByPostalCode(string|array<string> $publisher_postal_code) Return ChildPublisher objects filtered by the publisher_postal_code column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByPostalCode(string|array<string> $publisher_postal_code) Return ChildPublisher objects filtered by the publisher_postal_code column
 * @method     ChildPublisher[]|Collection findByCity(string|array<string> $publisher_city) Return ChildPublisher objects filtered by the publisher_city column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByCity(string|array<string> $publisher_city) Return ChildPublisher objects filtered by the publisher_city column
 * @method     ChildPublisher[]|Collection findByCountry(string|array<string> $publisher_country) Return ChildPublisher objects filtered by the publisher_country column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByCountry(string|array<string> $publisher_country) Return ChildPublisher objects filtered by the publisher_country column
 * @method     ChildPublisher[]|Collection findByPhone(string|array<string> $publisher_phone) Return ChildPublisher objects filtered by the publisher_phone column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByPhone(string|array<string> $publisher_phone) Return ChildPublisher objects filtered by the publisher_phone column
 * @method     ChildPublisher[]|Collection findByFax(string|array<string> $publisher_fax) Return ChildPublisher objects filtered by the publisher_fax column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByFax(string|array<string> $publisher_fax) Return ChildPublisher objects filtered by the publisher_fax column
 * @method     ChildPublisher[]|Collection findByWebsite(string|array<string> $publisher_website) Return ChildPublisher objects filtered by the publisher_website column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByWebsite(string|array<string> $publisher_website) Return ChildPublisher objects filtered by the publisher_website column
 * @method     ChildPublisher[]|Collection findByBuyLink(string|array<string> $publisher_buy_link) Return ChildPublisher objects filtered by the publisher_buy_link column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByBuyLink(string|array<string> $publisher_buy_link) Return ChildPublisher objects filtered by the publisher_buy_link column
 * @method     ChildPublisher[]|Collection findByEmail(string|array<string> $publisher_email) Return ChildPublisher objects filtered by the publisher_email column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByEmail(string|array<string> $publisher_email) Return ChildPublisher objects filtered by the publisher_email column
 * @method     ChildPublisher[]|Collection findByFacebook(string|array<string> $publisher_facebook) Return ChildPublisher objects filtered by the publisher_facebook column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByFacebook(string|array<string> $publisher_facebook) Return ChildPublisher objects filtered by the publisher_facebook column
 * @method     ChildPublisher[]|Collection findByTwitter(string|array<string> $publisher_twitter) Return ChildPublisher objects filtered by the publisher_twitter column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByTwitter(string|array<string> $publisher_twitter) Return ChildPublisher objects filtered by the publisher_twitter column
 * @method     ChildPublisher[]|Collection findByLegalForm(string|array<string> $publisher_legal_form) Return ChildPublisher objects filtered by the publisher_legal_form column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByLegalForm(string|array<string> $publisher_legal_form) Return ChildPublisher objects filtered by the publisher_legal_form column
 * @method     ChildPublisher[]|Collection findByCreationYear(string|array<string> $publisher_creation_year) Return ChildPublisher objects filtered by the publisher_creation_year column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByCreationYear(string|array<string> $publisher_creation_year) Return ChildPublisher objects filtered by the publisher_creation_year column
 * @method     ChildPublisher[]|Collection findByIsbn(string|array<string> $publisher_isbn) Return ChildPublisher objects filtered by the publisher_isbn column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByIsbn(string|array<string> $publisher_isbn) Return ChildPublisher objects filtered by the publisher_isbn column
 * @method     ChildPublisher[]|Collection findByVolumes(int|array<int> $publisher_volumes) Return ChildPublisher objects filtered by the publisher_volumes column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByVolumes(int|array<int> $publisher_volumes) Return ChildPublisher objects filtered by the publisher_volumes column
 * @method     ChildPublisher[]|Collection findByAverageRun(int|array<int> $publisher_average_run) Return ChildPublisher objects filtered by the publisher_average_run column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByAverageRun(int|array<int> $publisher_average_run) Return ChildPublisher objects filtered by the publisher_average_run column
 * @method     ChildPublisher[]|Collection findBySpecialities(string|array<string> $publisher_specialities) Return ChildPublisher objects filtered by the publisher_specialities column
 * @psalm-method Collection&\Traversable<ChildPublisher> findBySpecialities(string|array<string> $publisher_specialities) Return ChildPublisher objects filtered by the publisher_specialities column
 * @method     ChildPublisher[]|Collection findByDiffuseur(string|array<string> $publisher_diffuseur) Return ChildPublisher objects filtered by the publisher_diffuseur column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByDiffuseur(string|array<string> $publisher_diffuseur) Return ChildPublisher objects filtered by the publisher_diffuseur column
 * @method     ChildPublisher[]|Collection findByDistributeur(string|array<string> $publisher_distributeur) Return ChildPublisher objects filtered by the publisher_distributeur column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByDistributeur(string|array<string> $publisher_distributeur) Return ChildPublisher objects filtered by the publisher_distributeur column
 * @method     ChildPublisher[]|Collection findByVpc(boolean|array<boolean> $publisher_vpc) Return ChildPublisher objects filtered by the publisher_vpc column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByVpc(boolean|array<boolean> $publisher_vpc) Return ChildPublisher objects filtered by the publisher_vpc column
 * @method     ChildPublisher[]|Collection findByPaypal(string|array<string> $publisher_paypal) Return ChildPublisher objects filtered by the publisher_paypal column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByPaypal(string|array<string> $publisher_paypal) Return ChildPublisher objects filtered by the publisher_paypal column
 * @method     ChildPublisher[]|Collection findByShippingMode(string|array<string> $publisher_shipping_mode) Return ChildPublisher objects filtered by the publisher_shipping_mode column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByShippingMode(string|array<string> $publisher_shipping_mode) Return ChildPublisher objects filtered by the publisher_shipping_mode column
 * @method     ChildPublisher[]|Collection findByShippingFee(int|array<int> $publisher_shipping_fee) Return ChildPublisher objects filtered by the publisher_shipping_fee column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByShippingFee(int|array<int> $publisher_shipping_fee) Return ChildPublisher objects filtered by the publisher_shipping_fee column
 * @method     ChildPublisher[]|Collection findByGln(string|array<string> $publisher_gln) Return ChildPublisher objects filtered by the publisher_gln column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByGln(string|array<string> $publisher_gln) Return ChildPublisher objects filtered by the publisher_gln column
 * @method     ChildPublisher[]|Collection findByDesc(string|array<string> $publisher_desc) Return ChildPublisher objects filtered by the publisher_desc column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByDesc(string|array<string> $publisher_desc) Return ChildPublisher objects filtered by the publisher_desc column
 * @method     ChildPublisher[]|Collection findByDescShort(string|array<string> $publisher_desc_short) Return ChildPublisher objects filtered by the publisher_desc_short column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByDescShort(string|array<string> $publisher_desc_short) Return ChildPublisher objects filtered by the publisher_desc_short column
 * @method     ChildPublisher[]|Collection findByOrderBy(string|array<string> $publisher_order_by) Return ChildPublisher objects filtered by the publisher_order_by column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByOrderBy(string|array<string> $publisher_order_by) Return ChildPublisher objects filtered by the publisher_order_by column
 * @method     ChildPublisher[]|Collection findByInsert(string|array<string> $publisher_insert) Return ChildPublisher objects filtered by the publisher_insert column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByInsert(string|array<string> $publisher_insert) Return ChildPublisher objects filtered by the publisher_insert column
 * @method     ChildPublisher[]|Collection findByUpdate(string|array<string> $publisher_update) Return ChildPublisher objects filtered by the publisher_update column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByUpdate(string|array<string> $publisher_update) Return ChildPublisher objects filtered by the publisher_update column
 * @method     ChildPublisher[]|Collection findByCreatedAt(string|array<string> $publisher_created) Return ChildPublisher objects filtered by the publisher_created column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByCreatedAt(string|array<string> $publisher_created) Return ChildPublisher objects filtered by the publisher_created column
 * @method     ChildPublisher[]|Collection findByUpdatedAt(string|array<string> $publisher_updated) Return ChildPublisher objects filtered by the publisher_updated column
 * @psalm-method Collection&\Traversable<ChildPublisher> findByUpdatedAt(string|array<string> $publisher_updated) Return ChildPublisher objects filtered by the publisher_updated column
 *
 * @method     ChildPublisher[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildPublisher> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class PublisherQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\PublisherQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Publisher', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPublisherQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPublisherQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
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
    public function findPk($key, ?ConnectionInterface $con = null)
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
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
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
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
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
     * @param array $keys Primary keys to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return Collection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ?ConnectionInterface $con = null)
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
     * @param mixed $key Primary key to use for the query
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_ID, $key, Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param array|int $keys The list of primary key to use for the query
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_ID, $keys, Criteria::IN);

        return $this;
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
     * @param mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterById($id = null, ?string $comparison = null)
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

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_ID, $id, $comparison);

        return $this;
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
     * @param mixed $siteId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySiteId($siteId = null, ?string $comparison = null)
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

        $this->addUsingAlias(PublisherTableMap::COL_SITE_ID, $siteId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the publisher_name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE publisher_name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE publisher_name LIKE '%fooValue%'
     * $query->filterByName(['foo', 'bar']); // WHERE publisher_name IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $name The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByName($name = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_NAME, $name, $comparison);

        return $this;
    }

    /**
     * Filter the query on the publisher_name_alphabetic column
     *
     * Example usage:
     * <code>
     * $query->filterByNameAlphabetic('fooValue');   // WHERE publisher_name_alphabetic = 'fooValue'
     * $query->filterByNameAlphabetic('%fooValue%', Criteria::LIKE); // WHERE publisher_name_alphabetic LIKE '%fooValue%'
     * $query->filterByNameAlphabetic(['foo', 'bar']); // WHERE publisher_name_alphabetic IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $nameAlphabetic The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByNameAlphabetic($nameAlphabetic = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nameAlphabetic)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_NAME_ALPHABETIC, $nameAlphabetic, $comparison);

        return $this;
    }

    /**
     * Filter the query on the publisher_url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE publisher_url = 'fooValue'
     * $query->filterByUrl('%fooValue%', Criteria::LIKE); // WHERE publisher_url LIKE '%fooValue%'
     * $query->filterByUrl(['foo', 'bar']); // WHERE publisher_url IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $url The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUrl($url = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($url)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_URL, $url, $comparison);

        return $this;
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
     * @param mixed $noosfereId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByNoosfereId($noosfereId = null, ?string $comparison = null)
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

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_NOOSFERE_ID, $noosfereId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the publisher_representative column
     *
     * Example usage:
     * <code>
     * $query->filterByRepresentative('fooValue');   // WHERE publisher_representative = 'fooValue'
     * $query->filterByRepresentative('%fooValue%', Criteria::LIKE); // WHERE publisher_representative LIKE '%fooValue%'
     * $query->filterByRepresentative(['foo', 'bar']); // WHERE publisher_representative IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $representative The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByRepresentative($representative = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($representative)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_REPRESENTATIVE, $representative, $comparison);

        return $this;
    }

    /**
     * Filter the query on the publisher_address column
     *
     * Example usage:
     * <code>
     * $query->filterByAddress('fooValue');   // WHERE publisher_address = 'fooValue'
     * $query->filterByAddress('%fooValue%', Criteria::LIKE); // WHERE publisher_address LIKE '%fooValue%'
     * $query->filterByAddress(['foo', 'bar']); // WHERE publisher_address IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $address The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAddress($address = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($address)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_ADDRESS, $address, $comparison);

        return $this;
    }

    /**
     * Filter the query on the publisher_postal_code column
     *
     * Example usage:
     * <code>
     * $query->filterByPostalCode('fooValue');   // WHERE publisher_postal_code = 'fooValue'
     * $query->filterByPostalCode('%fooValue%', Criteria::LIKE); // WHERE publisher_postal_code LIKE '%fooValue%'
     * $query->filterByPostalCode(['foo', 'bar']); // WHERE publisher_postal_code IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $postalCode The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPostalCode($postalCode = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($postalCode)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_POSTAL_CODE, $postalCode, $comparison);

        return $this;
    }

    /**
     * Filter the query on the publisher_city column
     *
     * Example usage:
     * <code>
     * $query->filterByCity('fooValue');   // WHERE publisher_city = 'fooValue'
     * $query->filterByCity('%fooValue%', Criteria::LIKE); // WHERE publisher_city LIKE '%fooValue%'
     * $query->filterByCity(['foo', 'bar']); // WHERE publisher_city IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $city The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCity($city = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($city)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_CITY, $city, $comparison);

        return $this;
    }

    /**
     * Filter the query on the publisher_country column
     *
     * Example usage:
     * <code>
     * $query->filterByCountry('fooValue');   // WHERE publisher_country = 'fooValue'
     * $query->filterByCountry('%fooValue%', Criteria::LIKE); // WHERE publisher_country LIKE '%fooValue%'
     * $query->filterByCountry(['foo', 'bar']); // WHERE publisher_country IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $country The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCountry($country = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($country)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_COUNTRY, $country, $comparison);

        return $this;
    }

    /**
     * Filter the query on the publisher_phone column
     *
     * Example usage:
     * <code>
     * $query->filterByPhone('fooValue');   // WHERE publisher_phone = 'fooValue'
     * $query->filterByPhone('%fooValue%', Criteria::LIKE); // WHERE publisher_phone LIKE '%fooValue%'
     * $query->filterByPhone(['foo', 'bar']); // WHERE publisher_phone IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $phone The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPhone($phone = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($phone)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_PHONE, $phone, $comparison);

        return $this;
    }

    /**
     * Filter the query on the publisher_fax column
     *
     * Example usage:
     * <code>
     * $query->filterByFax('fooValue');   // WHERE publisher_fax = 'fooValue'
     * $query->filterByFax('%fooValue%', Criteria::LIKE); // WHERE publisher_fax LIKE '%fooValue%'
     * $query->filterByFax(['foo', 'bar']); // WHERE publisher_fax IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $fax The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFax($fax = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($fax)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_FAX, $fax, $comparison);

        return $this;
    }

    /**
     * Filter the query on the publisher_website column
     *
     * Example usage:
     * <code>
     * $query->filterByWebsite('fooValue');   // WHERE publisher_website = 'fooValue'
     * $query->filterByWebsite('%fooValue%', Criteria::LIKE); // WHERE publisher_website LIKE '%fooValue%'
     * $query->filterByWebsite(['foo', 'bar']); // WHERE publisher_website IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $website The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByWebsite($website = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($website)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_WEBSITE, $website, $comparison);

        return $this;
    }

    /**
     * Filter the query on the publisher_buy_link column
     *
     * Example usage:
     * <code>
     * $query->filterByBuyLink('fooValue');   // WHERE publisher_buy_link = 'fooValue'
     * $query->filterByBuyLink('%fooValue%', Criteria::LIKE); // WHERE publisher_buy_link LIKE '%fooValue%'
     * $query->filterByBuyLink(['foo', 'bar']); // WHERE publisher_buy_link IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $buyLink The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByBuyLink($buyLink = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($buyLink)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_BUY_LINK, $buyLink, $comparison);

        return $this;
    }

    /**
     * Filter the query on the publisher_email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE publisher_email = 'fooValue'
     * $query->filterByEmail('%fooValue%', Criteria::LIKE); // WHERE publisher_email LIKE '%fooValue%'
     * $query->filterByEmail(['foo', 'bar']); // WHERE publisher_email IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $email The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEmail($email = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_EMAIL, $email, $comparison);

        return $this;
    }

    /**
     * Filter the query on the publisher_facebook column
     *
     * Example usage:
     * <code>
     * $query->filterByFacebook('fooValue');   // WHERE publisher_facebook = 'fooValue'
     * $query->filterByFacebook('%fooValue%', Criteria::LIKE); // WHERE publisher_facebook LIKE '%fooValue%'
     * $query->filterByFacebook(['foo', 'bar']); // WHERE publisher_facebook IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $facebook The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFacebook($facebook = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($facebook)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_FACEBOOK, $facebook, $comparison);

        return $this;
    }

    /**
     * Filter the query on the publisher_twitter column
     *
     * Example usage:
     * <code>
     * $query->filterByTwitter('fooValue');   // WHERE publisher_twitter = 'fooValue'
     * $query->filterByTwitter('%fooValue%', Criteria::LIKE); // WHERE publisher_twitter LIKE '%fooValue%'
     * $query->filterByTwitter(['foo', 'bar']); // WHERE publisher_twitter IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $twitter The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTwitter($twitter = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($twitter)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_TWITTER, $twitter, $comparison);

        return $this;
    }

    /**
     * Filter the query on the publisher_legal_form column
     *
     * Example usage:
     * <code>
     * $query->filterByLegalForm('fooValue');   // WHERE publisher_legal_form = 'fooValue'
     * $query->filterByLegalForm('%fooValue%', Criteria::LIKE); // WHERE publisher_legal_form LIKE '%fooValue%'
     * $query->filterByLegalForm(['foo', 'bar']); // WHERE publisher_legal_form IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $legalForm The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByLegalForm($legalForm = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($legalForm)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_LEGAL_FORM, $legalForm, $comparison);

        return $this;
    }

    /**
     * Filter the query on the publisher_creation_year column
     *
     * Example usage:
     * <code>
     * $query->filterByCreationYear('fooValue');   // WHERE publisher_creation_year = 'fooValue'
     * $query->filterByCreationYear('%fooValue%', Criteria::LIKE); // WHERE publisher_creation_year LIKE '%fooValue%'
     * $query->filterByCreationYear(['foo', 'bar']); // WHERE publisher_creation_year IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $creationYear The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCreationYear($creationYear = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($creationYear)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_CREATION_YEAR, $creationYear, $comparison);

        return $this;
    }

    /**
     * Filter the query on the publisher_isbn column
     *
     * Example usage:
     * <code>
     * $query->filterByIsbn('fooValue');   // WHERE publisher_isbn = 'fooValue'
     * $query->filterByIsbn('%fooValue%', Criteria::LIKE); // WHERE publisher_isbn LIKE '%fooValue%'
     * $query->filterByIsbn(['foo', 'bar']); // WHERE publisher_isbn IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $isbn The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByIsbn($isbn = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($isbn)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_ISBN, $isbn, $comparison);

        return $this;
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
     * @param mixed $volumes The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByVolumes($volumes = null, ?string $comparison = null)
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

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_VOLUMES, $volumes, $comparison);

        return $this;
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
     * @param mixed $averageRun The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAverageRun($averageRun = null, ?string $comparison = null)
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

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_AVERAGE_RUN, $averageRun, $comparison);

        return $this;
    }

    /**
     * Filter the query on the publisher_specialities column
     *
     * Example usage:
     * <code>
     * $query->filterBySpecialities('fooValue');   // WHERE publisher_specialities = 'fooValue'
     * $query->filterBySpecialities('%fooValue%', Criteria::LIKE); // WHERE publisher_specialities LIKE '%fooValue%'
     * $query->filterBySpecialities(['foo', 'bar']); // WHERE publisher_specialities IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $specialities The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySpecialities($specialities = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($specialities)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_SPECIALITIES, $specialities, $comparison);

        return $this;
    }

    /**
     * Filter the query on the publisher_diffuseur column
     *
     * Example usage:
     * <code>
     * $query->filterByDiffuseur('fooValue');   // WHERE publisher_diffuseur = 'fooValue'
     * $query->filterByDiffuseur('%fooValue%', Criteria::LIKE); // WHERE publisher_diffuseur LIKE '%fooValue%'
     * $query->filterByDiffuseur(['foo', 'bar']); // WHERE publisher_diffuseur IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $diffuseur The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDiffuseur($diffuseur = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($diffuseur)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_DIFFUSEUR, $diffuseur, $comparison);

        return $this;
    }

    /**
     * Filter the query on the publisher_distributeur column
     *
     * Example usage:
     * <code>
     * $query->filterByDistributeur('fooValue');   // WHERE publisher_distributeur = 'fooValue'
     * $query->filterByDistributeur('%fooValue%', Criteria::LIKE); // WHERE publisher_distributeur LIKE '%fooValue%'
     * $query->filterByDistributeur(['foo', 'bar']); // WHERE publisher_distributeur IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $distributeur The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDistributeur($distributeur = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($distributeur)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_DISTRIBUTEUR, $distributeur, $comparison);

        return $this;
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
     * @param bool|string $vpc The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByVpc($vpc = null, ?string $comparison = null)
    {
        if (is_string($vpc)) {
            $vpc = in_array(strtolower($vpc), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_VPC, $vpc, $comparison);

        return $this;
    }

    /**
     * Filter the query on the publisher_paypal column
     *
     * Example usage:
     * <code>
     * $query->filterByPaypal('fooValue');   // WHERE publisher_paypal = 'fooValue'
     * $query->filterByPaypal('%fooValue%', Criteria::LIKE); // WHERE publisher_paypal LIKE '%fooValue%'
     * $query->filterByPaypal(['foo', 'bar']); // WHERE publisher_paypal IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $paypal The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPaypal($paypal = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($paypal)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_PAYPAL, $paypal, $comparison);

        return $this;
    }

    /**
     * Filter the query on the publisher_shipping_mode column
     *
     * Example usage:
     * <code>
     * $query->filterByShippingMode('fooValue');   // WHERE publisher_shipping_mode = 'fooValue'
     * $query->filterByShippingMode('%fooValue%', Criteria::LIKE); // WHERE publisher_shipping_mode LIKE '%fooValue%'
     * $query->filterByShippingMode(['foo', 'bar']); // WHERE publisher_shipping_mode IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $shippingMode The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByShippingMode($shippingMode = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($shippingMode)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_SHIPPING_MODE, $shippingMode, $comparison);

        return $this;
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
     * @param mixed $shippingFee The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByShippingFee($shippingFee = null, ?string $comparison = null)
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

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_SHIPPING_FEE, $shippingFee, $comparison);

        return $this;
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
     * @param mixed $gln The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByGln($gln = null, ?string $comparison = null)
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

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_GLN, $gln, $comparison);

        return $this;
    }

    /**
     * Filter the query on the publisher_desc column
     *
     * Example usage:
     * <code>
     * $query->filterByDesc('fooValue');   // WHERE publisher_desc = 'fooValue'
     * $query->filterByDesc('%fooValue%', Criteria::LIKE); // WHERE publisher_desc LIKE '%fooValue%'
     * $query->filterByDesc(['foo', 'bar']); // WHERE publisher_desc IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $desc The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDesc($desc = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($desc)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_DESC, $desc, $comparison);

        return $this;
    }

    /**
     * Filter the query on the publisher_desc_short column
     *
     * Example usage:
     * <code>
     * $query->filterByDescShort('fooValue');   // WHERE publisher_desc_short = 'fooValue'
     * $query->filterByDescShort('%fooValue%', Criteria::LIKE); // WHERE publisher_desc_short LIKE '%fooValue%'
     * $query->filterByDescShort(['foo', 'bar']); // WHERE publisher_desc_short IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $descShort The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDescShort($descShort = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($descShort)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_DESC_SHORT, $descShort, $comparison);

        return $this;
    }

    /**
     * Filter the query on the publisher_order_by column
     *
     * Example usage:
     * <code>
     * $query->filterByOrderBy('fooValue');   // WHERE publisher_order_by = 'fooValue'
     * $query->filterByOrderBy('%fooValue%', Criteria::LIKE); // WHERE publisher_order_by LIKE '%fooValue%'
     * $query->filterByOrderBy(['foo', 'bar']); // WHERE publisher_order_by IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $orderBy The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByOrderBy($orderBy = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($orderBy)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_ORDER_BY, $orderBy, $comparison);

        return $this;
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
     * @param mixed $insert The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByInsert($insert = null, ?string $comparison = null)
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

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_INSERT, $insert, $comparison);

        return $this;
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
     * @param mixed $update The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUpdate($update = null, ?string $comparison = null)
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

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_UPDATE, $update, $comparison);

        return $this;
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
     * @param mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, ?string $comparison = null)
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

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_CREATED, $createdAt, $comparison);

        return $this;
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
     * @param mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, ?string $comparison = null)
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

        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_UPDATED, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \Model\Article object
     *
     * @param \Model\Article|ObjectCollection $article the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByArticle($article, ?string $comparison = null)
    {
        if ($article instanceof \Model\Article) {
            $this
                ->addUsingAlias(PublisherTableMap::COL_PUBLISHER_ID, $article->getPublisherId(), $comparison);

            return $this;
        } elseif ($article instanceof ObjectCollection) {
            $this
                ->useArticleQuery()
                ->filterByPrimaryKeys($article->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByArticle() only accepts arguments of type \Model\Article or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Article relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinArticle(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Article');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Article');
        }

        return $this;
    }

    /**
     * Use the Article relation Article object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\ArticleQuery A secondary query class using the current class as primary query
     */
    public function useArticleQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinArticle($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Article', '\Model\ArticleQuery');
    }

    /**
     * Use the Article relation Article object
     *
     * @param callable(\Model\ArticleQuery):\Model\ArticleQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withArticleQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useArticleQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Article table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\ArticleQuery The inner query object of the EXISTS statement
     */
    public function useArticleExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\ArticleQuery */
        $q = $this->useExistsQuery('Article', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Article table for a NOT EXISTS query.
     *
     * @see useArticleExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\ArticleQuery The inner query object of the NOT EXISTS statement
     */
    public function useArticleNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\ArticleQuery */
        $q = $this->useExistsQuery('Article', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Article table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\ArticleQuery The inner query object of the IN statement
     */
    public function useInArticleQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\ArticleQuery */
        $q = $this->useInQuery('Article', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Article table for a NOT IN query.
     *
     * @see useArticleInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\ArticleQuery The inner query object of the NOT IN statement
     */
    public function useNotInArticleQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\ArticleQuery */
        $q = $this->useInQuery('Article', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Image object
     *
     * @param \Model\Image|ObjectCollection $image the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByImage($image, ?string $comparison = null)
    {
        if ($image instanceof \Model\Image) {
            $this
                ->addUsingAlias(PublisherTableMap::COL_PUBLISHER_ID, $image->getPublisherId(), $comparison);

            return $this;
        } elseif ($image instanceof ObjectCollection) {
            $this
                ->useImageQuery()
                ->filterByPrimaryKeys($image->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByImage() only accepts arguments of type \Model\Image or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Image relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinImage(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Image');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Image');
        }

        return $this;
    }

    /**
     * Use the Image relation Image object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\ImageQuery A secondary query class using the current class as primary query
     */
    public function useImageQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinImage($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Image', '\Model\ImageQuery');
    }

    /**
     * Use the Image relation Image object
     *
     * @param callable(\Model\ImageQuery):\Model\ImageQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withImageQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useImageQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Image table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\ImageQuery The inner query object of the EXISTS statement
     */
    public function useImageExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\ImageQuery */
        $q = $this->useExistsQuery('Image', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Image table for a NOT EXISTS query.
     *
     * @see useImageExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\ImageQuery The inner query object of the NOT EXISTS statement
     */
    public function useImageNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\ImageQuery */
        $q = $this->useExistsQuery('Image', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Image table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\ImageQuery The inner query object of the IN statement
     */
    public function useInImageQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\ImageQuery */
        $q = $this->useInQuery('Image', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Image table for a NOT IN query.
     *
     * @see useImageInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\ImageQuery The inner query object of the NOT IN statement
     */
    public function useNotInImageQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\ImageQuery */
        $q = $this->useInQuery('Image', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Right object
     *
     * @param \Model\Right|ObjectCollection $right the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByRight($right, ?string $comparison = null)
    {
        if ($right instanceof \Model\Right) {
            $this
                ->addUsingAlias(PublisherTableMap::COL_PUBLISHER_ID, $right->getPublisherId(), $comparison);

            return $this;
        } elseif ($right instanceof ObjectCollection) {
            $this
                ->useRightQuery()
                ->filterByPrimaryKeys($right->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByRight() only accepts arguments of type \Model\Right or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Right relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinRight(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Right');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Right');
        }

        return $this;
    }

    /**
     * Use the Right relation Right object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\RightQuery A secondary query class using the current class as primary query
     */
    public function useRightQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinRight($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Right', '\Model\RightQuery');
    }

    /**
     * Use the Right relation Right object
     *
     * @param callable(\Model\RightQuery):\Model\RightQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withRightQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useRightQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Right table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\RightQuery The inner query object of the EXISTS statement
     */
    public function useRightExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\RightQuery */
        $q = $this->useExistsQuery('Right', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Right table for a NOT EXISTS query.
     *
     * @see useRightExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\RightQuery The inner query object of the NOT EXISTS statement
     */
    public function useRightNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\RightQuery */
        $q = $this->useExistsQuery('Right', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Right table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\RightQuery The inner query object of the IN statement
     */
    public function useInRightQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\RightQuery */
        $q = $this->useInQuery('Right', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Right table for a NOT IN query.
     *
     * @see useRightInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\RightQuery The inner query object of the NOT IN statement
     */
    public function useNotInRightQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\RightQuery */
        $q = $this->useInQuery('Right', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Exclude object from result
     *
     * @param ChildPublisher $publisher Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
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
    public function doDeleteAll(?ConnectionInterface $con = null): int
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
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(?ConnectionInterface $con = null): int
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
     * @param int $nbDays Maximum age of the latest update in days
     *
     * @return $this The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(PublisherTableMap::COL_PUBLISHER_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(PublisherTableMap::COL_PUBLISHER_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(PublisherTableMap::COL_PUBLISHER_CREATED);

        return $this;
    }

    /**
     * Filter by the latest created
     *
     * @param int $nbDays Maximum age of in days
     *
     * @return $this The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        $this->addUsingAlias(PublisherTableMap::COL_PUBLISHER_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(PublisherTableMap::COL_PUBLISHER_CREATED);

        return $this;
    }

}
