<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Site as ChildSite;
use Model\SiteQuery as ChildSiteQuery;
use Model\Map\SiteTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'sites' table.
 *
 *
 *
 * @method     ChildSiteQuery orderById($order = Criteria::ASC) Order by the site_id column
 * @method     ChildSiteQuery orderByName($order = Criteria::ASC) Order by the site_name column
 * @method     ChildSiteQuery orderByPass($order = Criteria::ASC) Order by the site_pass column
 * @method     ChildSiteQuery orderByTitle($order = Criteria::ASC) Order by the site_title column
 * @method     ChildSiteQuery orderByDomain($order = Criteria::ASC) Order by the site_domain column
 * @method     ChildSiteQuery orderByVersion($order = Criteria::ASC) Order by the site_version column
 * @method     ChildSiteQuery orderByTag($order = Criteria::ASC) Order by the site_tag column
 * @method     ChildSiteQuery orderByFlag($order = Criteria::ASC) Order by the site_flag column
 * @method     ChildSiteQuery orderByContact($order = Criteria::ASC) Order by the site_contact column
 * @method     ChildSiteQuery orderByAddress($order = Criteria::ASC) Order by the site_address column
 * @method     ChildSiteQuery orderByTva($order = Criteria::ASC) Order by the site_tva column
 * @method     ChildSiteQuery orderByHtmlRenderer($order = Criteria::ASC) Order by the site_html_renderer column
 * @method     ChildSiteQuery orderByAxys($order = Criteria::ASC) Order by the site_axys column
 * @method     ChildSiteQuery orderByNoosfere($order = Criteria::ASC) Order by the site_noosfere column
 * @method     ChildSiteQuery orderByAmazon($order = Criteria::ASC) Order by the site_amazon column
 * @method     ChildSiteQuery orderByEventId($order = Criteria::ASC) Order by the site_event_id column
 * @method     ChildSiteQuery orderByEventDate($order = Criteria::ASC) Order by the site_event_date column
 * @method     ChildSiteQuery orderByShop($order = Criteria::ASC) Order by the site_shop column
 * @method     ChildSiteQuery orderByVpc($order = Criteria::ASC) Order by the site_vpc column
 * @method     ChildSiteQuery orderByShippingFee($order = Criteria::ASC) Order by the site_shipping_fee column
 * @method     ChildSiteQuery orderByAlerts($order = Criteria::ASC) Order by the site_alerts column
 * @method     ChildSiteQuery orderByWishlist($order = Criteria::ASC) Order by the site_wishlist column
 * @method     ChildSiteQuery orderByPaymentCheque($order = Criteria::ASC) Order by the site_payment_cheque column
 * @method     ChildSiteQuery orderByPaymentPaypal($order = Criteria::ASC) Order by the site_payment_paypal column
 * @method     ChildSiteQuery orderByPaymentPayplug($order = Criteria::ASC) Order by the site_payment_payplug column
 * @method     ChildSiteQuery orderByPaymentTransfer($order = Criteria::ASC) Order by the site_payment_transfer column
 * @method     ChildSiteQuery orderByBookshop($order = Criteria::ASC) Order by the site_bookshop column
 * @method     ChildSiteQuery orderByBookshopId($order = Criteria::ASC) Order by the site_bookshop_id column
 * @method     ChildSiteQuery orderByPublisher($order = Criteria::ASC) Order by the site_publisher column
 * @method     ChildSiteQuery orderByPublisherStock($order = Criteria::ASC) Order by the site_publisher_stock column
 * @method     ChildSiteQuery orderByPublisherId($order = Criteria::ASC) Order by the publisher_id column
 * @method     ChildSiteQuery orderByEbookBundle($order = Criteria::ASC) Order by the site_ebook_bundle column
 * @method     ChildSiteQuery orderByFbPageId($order = Criteria::ASC) Order by the site_fb_page_id column
 * @method     ChildSiteQuery orderByFbPageToken($order = Criteria::ASC) Order by the site_fb_page_token column
 * @method     ChildSiteQuery orderByAnalyticsId($order = Criteria::ASC) Order by the site_analytics_id column
 * @method     ChildSiteQuery orderByPiwikId($order = Criteria::ASC) Order by the site_piwik_id column
 * @method     ChildSiteQuery orderBySitemapUpdated($order = Criteria::ASC) Order by the site_sitemap_updated column
 * @method     ChildSiteQuery orderByMonitoring($order = Criteria::ASC) Order by the site_monitoring column
 * @method     ChildSiteQuery orderByCreatedAt($order = Criteria::ASC) Order by the site_created column
 * @method     ChildSiteQuery orderByUpdatedAt($order = Criteria::ASC) Order by the site_updated column
 *
 * @method     ChildSiteQuery groupById() Group by the site_id column
 * @method     ChildSiteQuery groupByName() Group by the site_name column
 * @method     ChildSiteQuery groupByPass() Group by the site_pass column
 * @method     ChildSiteQuery groupByTitle() Group by the site_title column
 * @method     ChildSiteQuery groupByDomain() Group by the site_domain column
 * @method     ChildSiteQuery groupByVersion() Group by the site_version column
 * @method     ChildSiteQuery groupByTag() Group by the site_tag column
 * @method     ChildSiteQuery groupByFlag() Group by the site_flag column
 * @method     ChildSiteQuery groupByContact() Group by the site_contact column
 * @method     ChildSiteQuery groupByAddress() Group by the site_address column
 * @method     ChildSiteQuery groupByTva() Group by the site_tva column
 * @method     ChildSiteQuery groupByHtmlRenderer() Group by the site_html_renderer column
 * @method     ChildSiteQuery groupByAxys() Group by the site_axys column
 * @method     ChildSiteQuery groupByNoosfere() Group by the site_noosfere column
 * @method     ChildSiteQuery groupByAmazon() Group by the site_amazon column
 * @method     ChildSiteQuery groupByEventId() Group by the site_event_id column
 * @method     ChildSiteQuery groupByEventDate() Group by the site_event_date column
 * @method     ChildSiteQuery groupByShop() Group by the site_shop column
 * @method     ChildSiteQuery groupByVpc() Group by the site_vpc column
 * @method     ChildSiteQuery groupByShippingFee() Group by the site_shipping_fee column
 * @method     ChildSiteQuery groupByAlerts() Group by the site_alerts column
 * @method     ChildSiteQuery groupByWishlist() Group by the site_wishlist column
 * @method     ChildSiteQuery groupByPaymentCheque() Group by the site_payment_cheque column
 * @method     ChildSiteQuery groupByPaymentPaypal() Group by the site_payment_paypal column
 * @method     ChildSiteQuery groupByPaymentPayplug() Group by the site_payment_payplug column
 * @method     ChildSiteQuery groupByPaymentTransfer() Group by the site_payment_transfer column
 * @method     ChildSiteQuery groupByBookshop() Group by the site_bookshop column
 * @method     ChildSiteQuery groupByBookshopId() Group by the site_bookshop_id column
 * @method     ChildSiteQuery groupByPublisher() Group by the site_publisher column
 * @method     ChildSiteQuery groupByPublisherStock() Group by the site_publisher_stock column
 * @method     ChildSiteQuery groupByPublisherId() Group by the publisher_id column
 * @method     ChildSiteQuery groupByEbookBundle() Group by the site_ebook_bundle column
 * @method     ChildSiteQuery groupByFbPageId() Group by the site_fb_page_id column
 * @method     ChildSiteQuery groupByFbPageToken() Group by the site_fb_page_token column
 * @method     ChildSiteQuery groupByAnalyticsId() Group by the site_analytics_id column
 * @method     ChildSiteQuery groupByPiwikId() Group by the site_piwik_id column
 * @method     ChildSiteQuery groupBySitemapUpdated() Group by the site_sitemap_updated column
 * @method     ChildSiteQuery groupByMonitoring() Group by the site_monitoring column
 * @method     ChildSiteQuery groupByCreatedAt() Group by the site_created column
 * @method     ChildSiteQuery groupByUpdatedAt() Group by the site_updated column
 *
 * @method     ChildSiteQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSiteQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSiteQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSiteQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildSiteQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildSiteQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildSiteQuery leftJoinRight($relationAlias = null) Adds a LEFT JOIN clause to the query using the Right relation
 * @method     ChildSiteQuery rightJoinRight($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Right relation
 * @method     ChildSiteQuery innerJoinRight($relationAlias = null) Adds a INNER JOIN clause to the query using the Right relation
 *
 * @method     ChildSiteQuery joinWithRight($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Right relation
 *
 * @method     ChildSiteQuery leftJoinWithRight() Adds a LEFT JOIN clause and with to the query using the Right relation
 * @method     ChildSiteQuery rightJoinWithRight() Adds a RIGHT JOIN clause and with to the query using the Right relation
 * @method     ChildSiteQuery innerJoinWithRight() Adds a INNER JOIN clause and with to the query using the Right relation
 *
 * @method     \Model\RightQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildSite|null findOne(ConnectionInterface $con = null) Return the first ChildSite matching the query
 * @method     ChildSite findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSite matching the query, or a new ChildSite object populated from the query conditions when no match is found
 *
 * @method     ChildSite|null findOneById(int $site_id) Return the first ChildSite filtered by the site_id column
 * @method     ChildSite|null findOneByName(string $site_name) Return the first ChildSite filtered by the site_name column
 * @method     ChildSite|null findOneByPass(string $site_pass) Return the first ChildSite filtered by the site_pass column
 * @method     ChildSite|null findOneByTitle(string $site_title) Return the first ChildSite filtered by the site_title column
 * @method     ChildSite|null findOneByDomain(string $site_domain) Return the first ChildSite filtered by the site_domain column
 * @method     ChildSite|null findOneByVersion(string $site_version) Return the first ChildSite filtered by the site_version column
 * @method     ChildSite|null findOneByTag(string $site_tag) Return the first ChildSite filtered by the site_tag column
 * @method     ChildSite|null findOneByFlag(string $site_flag) Return the first ChildSite filtered by the site_flag column
 * @method     ChildSite|null findOneByContact(string $site_contact) Return the first ChildSite filtered by the site_contact column
 * @method     ChildSite|null findOneByAddress(string $site_address) Return the first ChildSite filtered by the site_address column
 * @method     ChildSite|null findOneByTva(string $site_tva) Return the first ChildSite filtered by the site_tva column
 * @method     ChildSite|null findOneByHtmlRenderer(boolean $site_html_renderer) Return the first ChildSite filtered by the site_html_renderer column
 * @method     ChildSite|null findOneByAxys(boolean $site_axys) Return the first ChildSite filtered by the site_axys column
 * @method     ChildSite|null findOneByNoosfere(boolean $site_noosfere) Return the first ChildSite filtered by the site_noosfere column
 * @method     ChildSite|null findOneByAmazon(boolean $site_amazon) Return the first ChildSite filtered by the site_amazon column
 * @method     ChildSite|null findOneByEventId(int $site_event_id) Return the first ChildSite filtered by the site_event_id column
 * @method     ChildSite|null findOneByEventDate(int $site_event_date) Return the first ChildSite filtered by the site_event_date column
 * @method     ChildSite|null findOneByShop(boolean $site_shop) Return the first ChildSite filtered by the site_shop column
 * @method     ChildSite|null findOneByVpc(boolean $site_vpc) Return the first ChildSite filtered by the site_vpc column
 * @method     ChildSite|null findOneByShippingFee(string $site_shipping_fee) Return the first ChildSite filtered by the site_shipping_fee column
 * @method     ChildSite|null findOneByAlerts(boolean $site_alerts) Return the first ChildSite filtered by the site_alerts column
 * @method     ChildSite|null findOneByWishlist(boolean $site_wishlist) Return the first ChildSite filtered by the site_wishlist column
 * @method     ChildSite|null findOneByPaymentCheque(boolean $site_payment_cheque) Return the first ChildSite filtered by the site_payment_cheque column
 * @method     ChildSite|null findOneByPaymentPaypal(string $site_payment_paypal) Return the first ChildSite filtered by the site_payment_paypal column
 * @method     ChildSite|null findOneByPaymentPayplug(boolean $site_payment_payplug) Return the first ChildSite filtered by the site_payment_payplug column
 * @method     ChildSite|null findOneByPaymentTransfer(boolean $site_payment_transfer) Return the first ChildSite filtered by the site_payment_transfer column
 * @method     ChildSite|null findOneByBookshop(boolean $site_bookshop) Return the first ChildSite filtered by the site_bookshop column
 * @method     ChildSite|null findOneByBookshopId(int $site_bookshop_id) Return the first ChildSite filtered by the site_bookshop_id column
 * @method     ChildSite|null findOneByPublisher(boolean $site_publisher) Return the first ChildSite filtered by the site_publisher column
 * @method     ChildSite|null findOneByPublisherStock(boolean $site_publisher_stock) Return the first ChildSite filtered by the site_publisher_stock column
 * @method     ChildSite|null findOneByPublisherId(int $publisher_id) Return the first ChildSite filtered by the publisher_id column
 * @method     ChildSite|null findOneByEbookBundle(int $site_ebook_bundle) Return the first ChildSite filtered by the site_ebook_bundle column
 * @method     ChildSite|null findOneByFbPageId(string $site_fb_page_id) Return the first ChildSite filtered by the site_fb_page_id column
 * @method     ChildSite|null findOneByFbPageToken(string $site_fb_page_token) Return the first ChildSite filtered by the site_fb_page_token column
 * @method     ChildSite|null findOneByAnalyticsId(string $site_analytics_id) Return the first ChildSite filtered by the site_analytics_id column
 * @method     ChildSite|null findOneByPiwikId(int $site_piwik_id) Return the first ChildSite filtered by the site_piwik_id column
 * @method     ChildSite|null findOneBySitemapUpdated(string $site_sitemap_updated) Return the first ChildSite filtered by the site_sitemap_updated column
 * @method     ChildSite|null findOneByMonitoring(boolean $site_monitoring) Return the first ChildSite filtered by the site_monitoring column
 * @method     ChildSite|null findOneByCreatedAt(string $site_created) Return the first ChildSite filtered by the site_created column
 * @method     ChildSite|null findOneByUpdatedAt(string $site_updated) Return the first ChildSite filtered by the site_updated column *

 * @method     ChildSite requirePk($key, ConnectionInterface $con = null) Return the ChildSite by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOne(ConnectionInterface $con = null) Return the first ChildSite matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSite requireOneById(int $site_id) Return the first ChildSite filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByName(string $site_name) Return the first ChildSite filtered by the site_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByPass(string $site_pass) Return the first ChildSite filtered by the site_pass column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByTitle(string $site_title) Return the first ChildSite filtered by the site_title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByDomain(string $site_domain) Return the first ChildSite filtered by the site_domain column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByVersion(string $site_version) Return the first ChildSite filtered by the site_version column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByTag(string $site_tag) Return the first ChildSite filtered by the site_tag column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByFlag(string $site_flag) Return the first ChildSite filtered by the site_flag column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByContact(string $site_contact) Return the first ChildSite filtered by the site_contact column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByAddress(string $site_address) Return the first ChildSite filtered by the site_address column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByTva(string $site_tva) Return the first ChildSite filtered by the site_tva column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByHtmlRenderer(boolean $site_html_renderer) Return the first ChildSite filtered by the site_html_renderer column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByAxys(boolean $site_axys) Return the first ChildSite filtered by the site_axys column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByNoosfere(boolean $site_noosfere) Return the first ChildSite filtered by the site_noosfere column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByAmazon(boolean $site_amazon) Return the first ChildSite filtered by the site_amazon column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByEventId(int $site_event_id) Return the first ChildSite filtered by the site_event_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByEventDate(int $site_event_date) Return the first ChildSite filtered by the site_event_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByShop(boolean $site_shop) Return the first ChildSite filtered by the site_shop column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByVpc(boolean $site_vpc) Return the first ChildSite filtered by the site_vpc column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByShippingFee(string $site_shipping_fee) Return the first ChildSite filtered by the site_shipping_fee column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByAlerts(boolean $site_alerts) Return the first ChildSite filtered by the site_alerts column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByWishlist(boolean $site_wishlist) Return the first ChildSite filtered by the site_wishlist column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByPaymentCheque(boolean $site_payment_cheque) Return the first ChildSite filtered by the site_payment_cheque column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByPaymentPaypal(string $site_payment_paypal) Return the first ChildSite filtered by the site_payment_paypal column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByPaymentPayplug(boolean $site_payment_payplug) Return the first ChildSite filtered by the site_payment_payplug column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByPaymentTransfer(boolean $site_payment_transfer) Return the first ChildSite filtered by the site_payment_transfer column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByBookshop(boolean $site_bookshop) Return the first ChildSite filtered by the site_bookshop column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByBookshopId(int $site_bookshop_id) Return the first ChildSite filtered by the site_bookshop_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByPublisher(boolean $site_publisher) Return the first ChildSite filtered by the site_publisher column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByPublisherStock(boolean $site_publisher_stock) Return the first ChildSite filtered by the site_publisher_stock column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByPublisherId(int $publisher_id) Return the first ChildSite filtered by the publisher_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByEbookBundle(int $site_ebook_bundle) Return the first ChildSite filtered by the site_ebook_bundle column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByFbPageId(string $site_fb_page_id) Return the first ChildSite filtered by the site_fb_page_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByFbPageToken(string $site_fb_page_token) Return the first ChildSite filtered by the site_fb_page_token column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByAnalyticsId(string $site_analytics_id) Return the first ChildSite filtered by the site_analytics_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByPiwikId(int $site_piwik_id) Return the first ChildSite filtered by the site_piwik_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneBySitemapUpdated(string $site_sitemap_updated) Return the first ChildSite filtered by the site_sitemap_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByMonitoring(boolean $site_monitoring) Return the first ChildSite filtered by the site_monitoring column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByCreatedAt(string $site_created) Return the first ChildSite filtered by the site_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOneByUpdatedAt(string $site_updated) Return the first ChildSite filtered by the site_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSite[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSite objects based on current ModelCriteria
 * @psalm-method ObjectCollection&\Traversable<ChildSite> find(ConnectionInterface $con = null) Return ChildSite objects based on current ModelCriteria
 * @method     ChildSite[]|ObjectCollection findById(int $site_id) Return ChildSite objects filtered by the site_id column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findById(int $site_id) Return ChildSite objects filtered by the site_id column
 * @method     ChildSite[]|ObjectCollection findByName(string $site_name) Return ChildSite objects filtered by the site_name column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByName(string $site_name) Return ChildSite objects filtered by the site_name column
 * @method     ChildSite[]|ObjectCollection findByPass(string $site_pass) Return ChildSite objects filtered by the site_pass column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByPass(string $site_pass) Return ChildSite objects filtered by the site_pass column
 * @method     ChildSite[]|ObjectCollection findByTitle(string $site_title) Return ChildSite objects filtered by the site_title column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByTitle(string $site_title) Return ChildSite objects filtered by the site_title column
 * @method     ChildSite[]|ObjectCollection findByDomain(string $site_domain) Return ChildSite objects filtered by the site_domain column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByDomain(string $site_domain) Return ChildSite objects filtered by the site_domain column
 * @method     ChildSite[]|ObjectCollection findByVersion(string $site_version) Return ChildSite objects filtered by the site_version column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByVersion(string $site_version) Return ChildSite objects filtered by the site_version column
 * @method     ChildSite[]|ObjectCollection findByTag(string $site_tag) Return ChildSite objects filtered by the site_tag column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByTag(string $site_tag) Return ChildSite objects filtered by the site_tag column
 * @method     ChildSite[]|ObjectCollection findByFlag(string $site_flag) Return ChildSite objects filtered by the site_flag column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByFlag(string $site_flag) Return ChildSite objects filtered by the site_flag column
 * @method     ChildSite[]|ObjectCollection findByContact(string $site_contact) Return ChildSite objects filtered by the site_contact column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByContact(string $site_contact) Return ChildSite objects filtered by the site_contact column
 * @method     ChildSite[]|ObjectCollection findByAddress(string $site_address) Return ChildSite objects filtered by the site_address column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByAddress(string $site_address) Return ChildSite objects filtered by the site_address column
 * @method     ChildSite[]|ObjectCollection findByTva(string $site_tva) Return ChildSite objects filtered by the site_tva column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByTva(string $site_tva) Return ChildSite objects filtered by the site_tva column
 * @method     ChildSite[]|ObjectCollection findByHtmlRenderer(boolean $site_html_renderer) Return ChildSite objects filtered by the site_html_renderer column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByHtmlRenderer(boolean $site_html_renderer) Return ChildSite objects filtered by the site_html_renderer column
 * @method     ChildSite[]|ObjectCollection findByAxys(boolean $site_axys) Return ChildSite objects filtered by the site_axys column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByAxys(boolean $site_axys) Return ChildSite objects filtered by the site_axys column
 * @method     ChildSite[]|ObjectCollection findByNoosfere(boolean $site_noosfere) Return ChildSite objects filtered by the site_noosfere column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByNoosfere(boolean $site_noosfere) Return ChildSite objects filtered by the site_noosfere column
 * @method     ChildSite[]|ObjectCollection findByAmazon(boolean $site_amazon) Return ChildSite objects filtered by the site_amazon column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByAmazon(boolean $site_amazon) Return ChildSite objects filtered by the site_amazon column
 * @method     ChildSite[]|ObjectCollection findByEventId(int $site_event_id) Return ChildSite objects filtered by the site_event_id column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByEventId(int $site_event_id) Return ChildSite objects filtered by the site_event_id column
 * @method     ChildSite[]|ObjectCollection findByEventDate(int $site_event_date) Return ChildSite objects filtered by the site_event_date column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByEventDate(int $site_event_date) Return ChildSite objects filtered by the site_event_date column
 * @method     ChildSite[]|ObjectCollection findByShop(boolean $site_shop) Return ChildSite objects filtered by the site_shop column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByShop(boolean $site_shop) Return ChildSite objects filtered by the site_shop column
 * @method     ChildSite[]|ObjectCollection findByVpc(boolean $site_vpc) Return ChildSite objects filtered by the site_vpc column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByVpc(boolean $site_vpc) Return ChildSite objects filtered by the site_vpc column
 * @method     ChildSite[]|ObjectCollection findByShippingFee(string $site_shipping_fee) Return ChildSite objects filtered by the site_shipping_fee column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByShippingFee(string $site_shipping_fee) Return ChildSite objects filtered by the site_shipping_fee column
 * @method     ChildSite[]|ObjectCollection findByAlerts(boolean $site_alerts) Return ChildSite objects filtered by the site_alerts column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByAlerts(boolean $site_alerts) Return ChildSite objects filtered by the site_alerts column
 * @method     ChildSite[]|ObjectCollection findByWishlist(boolean $site_wishlist) Return ChildSite objects filtered by the site_wishlist column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByWishlist(boolean $site_wishlist) Return ChildSite objects filtered by the site_wishlist column
 * @method     ChildSite[]|ObjectCollection findByPaymentCheque(boolean $site_payment_cheque) Return ChildSite objects filtered by the site_payment_cheque column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByPaymentCheque(boolean $site_payment_cheque) Return ChildSite objects filtered by the site_payment_cheque column
 * @method     ChildSite[]|ObjectCollection findByPaymentPaypal(string $site_payment_paypal) Return ChildSite objects filtered by the site_payment_paypal column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByPaymentPaypal(string $site_payment_paypal) Return ChildSite objects filtered by the site_payment_paypal column
 * @method     ChildSite[]|ObjectCollection findByPaymentPayplug(boolean $site_payment_payplug) Return ChildSite objects filtered by the site_payment_payplug column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByPaymentPayplug(boolean $site_payment_payplug) Return ChildSite objects filtered by the site_payment_payplug column
 * @method     ChildSite[]|ObjectCollection findByPaymentTransfer(boolean $site_payment_transfer) Return ChildSite objects filtered by the site_payment_transfer column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByPaymentTransfer(boolean $site_payment_transfer) Return ChildSite objects filtered by the site_payment_transfer column
 * @method     ChildSite[]|ObjectCollection findByBookshop(boolean $site_bookshop) Return ChildSite objects filtered by the site_bookshop column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByBookshop(boolean $site_bookshop) Return ChildSite objects filtered by the site_bookshop column
 * @method     ChildSite[]|ObjectCollection findByBookshopId(int $site_bookshop_id) Return ChildSite objects filtered by the site_bookshop_id column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByBookshopId(int $site_bookshop_id) Return ChildSite objects filtered by the site_bookshop_id column
 * @method     ChildSite[]|ObjectCollection findByPublisher(boolean $site_publisher) Return ChildSite objects filtered by the site_publisher column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByPublisher(boolean $site_publisher) Return ChildSite objects filtered by the site_publisher column
 * @method     ChildSite[]|ObjectCollection findByPublisherStock(boolean $site_publisher_stock) Return ChildSite objects filtered by the site_publisher_stock column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByPublisherStock(boolean $site_publisher_stock) Return ChildSite objects filtered by the site_publisher_stock column
 * @method     ChildSite[]|ObjectCollection findByPublisherId(int $publisher_id) Return ChildSite objects filtered by the publisher_id column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByPublisherId(int $publisher_id) Return ChildSite objects filtered by the publisher_id column
 * @method     ChildSite[]|ObjectCollection findByEbookBundle(int $site_ebook_bundle) Return ChildSite objects filtered by the site_ebook_bundle column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByEbookBundle(int $site_ebook_bundle) Return ChildSite objects filtered by the site_ebook_bundle column
 * @method     ChildSite[]|ObjectCollection findByFbPageId(string $site_fb_page_id) Return ChildSite objects filtered by the site_fb_page_id column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByFbPageId(string $site_fb_page_id) Return ChildSite objects filtered by the site_fb_page_id column
 * @method     ChildSite[]|ObjectCollection findByFbPageToken(string $site_fb_page_token) Return ChildSite objects filtered by the site_fb_page_token column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByFbPageToken(string $site_fb_page_token) Return ChildSite objects filtered by the site_fb_page_token column
 * @method     ChildSite[]|ObjectCollection findByAnalyticsId(string $site_analytics_id) Return ChildSite objects filtered by the site_analytics_id column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByAnalyticsId(string $site_analytics_id) Return ChildSite objects filtered by the site_analytics_id column
 * @method     ChildSite[]|ObjectCollection findByPiwikId(int $site_piwik_id) Return ChildSite objects filtered by the site_piwik_id column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByPiwikId(int $site_piwik_id) Return ChildSite objects filtered by the site_piwik_id column
 * @method     ChildSite[]|ObjectCollection findBySitemapUpdated(string $site_sitemap_updated) Return ChildSite objects filtered by the site_sitemap_updated column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findBySitemapUpdated(string $site_sitemap_updated) Return ChildSite objects filtered by the site_sitemap_updated column
 * @method     ChildSite[]|ObjectCollection findByMonitoring(boolean $site_monitoring) Return ChildSite objects filtered by the site_monitoring column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByMonitoring(boolean $site_monitoring) Return ChildSite objects filtered by the site_monitoring column
 * @method     ChildSite[]|ObjectCollection findByCreatedAt(string $site_created) Return ChildSite objects filtered by the site_created column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByCreatedAt(string $site_created) Return ChildSite objects filtered by the site_created column
 * @method     ChildSite[]|ObjectCollection findByUpdatedAt(string $site_updated) Return ChildSite objects filtered by the site_updated column
 * @psalm-method ObjectCollection&\Traversable<ChildSite> findByUpdatedAt(string $site_updated) Return ChildSite objects filtered by the site_updated column
 * @method     ChildSite[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildSite> paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SiteQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\SiteQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Site', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSiteQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSiteQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSiteQuery) {
            return $criteria;
        }
        $query = new ChildSiteQuery();
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
     * @return ChildSite|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SiteTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = SiteTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildSite A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT site_id, site_name, site_pass, site_title, site_domain, site_version, site_tag, site_flag, site_contact, site_address, site_tva, site_html_renderer, site_axys, site_noosfere, site_amazon, site_event_id, site_event_date, site_shop, site_vpc, site_shipping_fee, site_alerts, site_wishlist, site_payment_cheque, site_payment_paypal, site_payment_payplug, site_payment_transfer, site_bookshop, site_bookshop_id, site_publisher, site_publisher_stock, publisher_id, site_ebook_bundle, site_fb_page_id, site_fb_page_token, site_analytics_id, site_piwik_id, site_sitemap_updated, site_monitoring, site_created, site_updated FROM sites WHERE site_id = :p0';
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
            /** @var ChildSite $obj */
            $obj = new ChildSite();
            $obj->hydrate($row);
            SiteTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildSite|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SiteTableMap::COL_SITE_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SiteTableMap::COL_SITE_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the site_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE site_id = 1234
     * $query->filterById(array(12, 34)); // WHERE site_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE site_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SiteTableMap::COL_SITE_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SiteTableMap::COL_SITE_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_ID, $id, $comparison);
    }

    /**
     * Filter the query on the site_name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE site_name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE site_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the site_pass column
     *
     * Example usage:
     * <code>
     * $query->filterByPass('fooValue');   // WHERE site_pass = 'fooValue'
     * $query->filterByPass('%fooValue%', Criteria::LIKE); // WHERE site_pass LIKE '%fooValue%'
     * </code>
     *
     * @param     string $pass The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByPass($pass = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($pass)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_PASS, $pass, $comparison);
    }

    /**
     * Filter the query on the site_title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE site_title = 'fooValue'
     * $query->filterByTitle('%fooValue%', Criteria::LIKE); // WHERE site_title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the site_domain column
     *
     * Example usage:
     * <code>
     * $query->filterByDomain('fooValue');   // WHERE site_domain = 'fooValue'
     * $query->filterByDomain('%fooValue%', Criteria::LIKE); // WHERE site_domain LIKE '%fooValue%'
     * </code>
     *
     * @param     string $domain The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByDomain($domain = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($domain)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_DOMAIN, $domain, $comparison);
    }

    /**
     * Filter the query on the site_version column
     *
     * Example usage:
     * <code>
     * $query->filterByVersion('fooValue');   // WHERE site_version = 'fooValue'
     * $query->filterByVersion('%fooValue%', Criteria::LIKE); // WHERE site_version LIKE '%fooValue%'
     * </code>
     *
     * @param     string $version The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByVersion($version = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($version)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_VERSION, $version, $comparison);
    }

    /**
     * Filter the query on the site_tag column
     *
     * Example usage:
     * <code>
     * $query->filterByTag('fooValue');   // WHERE site_tag = 'fooValue'
     * $query->filterByTag('%fooValue%', Criteria::LIKE); // WHERE site_tag LIKE '%fooValue%'
     * </code>
     *
     * @param     string $tag The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByTag($tag = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($tag)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_TAG, $tag, $comparison);
    }

    /**
     * Filter the query on the site_flag column
     *
     * Example usage:
     * <code>
     * $query->filterByFlag('fooValue');   // WHERE site_flag = 'fooValue'
     * $query->filterByFlag('%fooValue%', Criteria::LIKE); // WHERE site_flag LIKE '%fooValue%'
     * </code>
     *
     * @param     string $flag The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByFlag($flag = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($flag)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_FLAG, $flag, $comparison);
    }

    /**
     * Filter the query on the site_contact column
     *
     * Example usage:
     * <code>
     * $query->filterByContact('fooValue');   // WHERE site_contact = 'fooValue'
     * $query->filterByContact('%fooValue%', Criteria::LIKE); // WHERE site_contact LIKE '%fooValue%'
     * </code>
     *
     * @param     string $contact The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByContact($contact = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($contact)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_CONTACT, $contact, $comparison);
    }

    /**
     * Filter the query on the site_address column
     *
     * Example usage:
     * <code>
     * $query->filterByAddress('fooValue');   // WHERE site_address = 'fooValue'
     * $query->filterByAddress('%fooValue%', Criteria::LIKE); // WHERE site_address LIKE '%fooValue%'
     * </code>
     *
     * @param     string $address The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByAddress($address = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($address)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_ADDRESS, $address, $comparison);
    }

    /**
     * Filter the query on the site_tva column
     *
     * Example usage:
     * <code>
     * $query->filterByTva('fooValue');   // WHERE site_tva = 'fooValue'
     * $query->filterByTva('%fooValue%', Criteria::LIKE); // WHERE site_tva LIKE '%fooValue%'
     * </code>
     *
     * @param     string $tva The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByTva($tva = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($tva)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_TVA, $tva, $comparison);
    }

    /**
     * Filter the query on the site_html_renderer column
     *
     * Example usage:
     * <code>
     * $query->filterByHtmlRenderer(true); // WHERE site_html_renderer = true
     * $query->filterByHtmlRenderer('yes'); // WHERE site_html_renderer = true
     * </code>
     *
     * @param     boolean|string $htmlRenderer The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByHtmlRenderer($htmlRenderer = null, $comparison = null)
    {
        if (is_string($htmlRenderer)) {
            $htmlRenderer = in_array(strtolower($htmlRenderer), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_HTML_RENDERER, $htmlRenderer, $comparison);
    }

    /**
     * Filter the query on the site_axys column
     *
     * Example usage:
     * <code>
     * $query->filterByAxys(true); // WHERE site_axys = true
     * $query->filterByAxys('yes'); // WHERE site_axys = true
     * </code>
     *
     * @param     boolean|string $axys The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByAxys($axys = null, $comparison = null)
    {
        if (is_string($axys)) {
            $axys = in_array(strtolower($axys), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_AXYS, $axys, $comparison);
    }

    /**
     * Filter the query on the site_noosfere column
     *
     * Example usage:
     * <code>
     * $query->filterByNoosfere(true); // WHERE site_noosfere = true
     * $query->filterByNoosfere('yes'); // WHERE site_noosfere = true
     * </code>
     *
     * @param     boolean|string $noosfere The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByNoosfere($noosfere = null, $comparison = null)
    {
        if (is_string($noosfere)) {
            $noosfere = in_array(strtolower($noosfere), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_NOOSFERE, $noosfere, $comparison);
    }

    /**
     * Filter the query on the site_amazon column
     *
     * Example usage:
     * <code>
     * $query->filterByAmazon(true); // WHERE site_amazon = true
     * $query->filterByAmazon('yes'); // WHERE site_amazon = true
     * </code>
     *
     * @param     boolean|string $amazon The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByAmazon($amazon = null, $comparison = null)
    {
        if (is_string($amazon)) {
            $amazon = in_array(strtolower($amazon), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_AMAZON, $amazon, $comparison);
    }

    /**
     * Filter the query on the site_event_id column
     *
     * Example usage:
     * <code>
     * $query->filterByEventId(1234); // WHERE site_event_id = 1234
     * $query->filterByEventId(array(12, 34)); // WHERE site_event_id IN (12, 34)
     * $query->filterByEventId(array('min' => 12)); // WHERE site_event_id > 12
     * </code>
     *
     * @param     mixed $eventId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByEventId($eventId = null, $comparison = null)
    {
        if (is_array($eventId)) {
            $useMinMax = false;
            if (isset($eventId['min'])) {
                $this->addUsingAlias(SiteTableMap::COL_SITE_EVENT_ID, $eventId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventId['max'])) {
                $this->addUsingAlias(SiteTableMap::COL_SITE_EVENT_ID, $eventId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_EVENT_ID, $eventId, $comparison);
    }

    /**
     * Filter the query on the site_event_date column
     *
     * Example usage:
     * <code>
     * $query->filterByEventDate(1234); // WHERE site_event_date = 1234
     * $query->filterByEventDate(array(12, 34)); // WHERE site_event_date IN (12, 34)
     * $query->filterByEventDate(array('min' => 12)); // WHERE site_event_date > 12
     * </code>
     *
     * @param     mixed $eventDate The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByEventDate($eventDate = null, $comparison = null)
    {
        if (is_array($eventDate)) {
            $useMinMax = false;
            if (isset($eventDate['min'])) {
                $this->addUsingAlias(SiteTableMap::COL_SITE_EVENT_DATE, $eventDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventDate['max'])) {
                $this->addUsingAlias(SiteTableMap::COL_SITE_EVENT_DATE, $eventDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_EVENT_DATE, $eventDate, $comparison);
    }

    /**
     * Filter the query on the site_shop column
     *
     * Example usage:
     * <code>
     * $query->filterByShop(true); // WHERE site_shop = true
     * $query->filterByShop('yes'); // WHERE site_shop = true
     * </code>
     *
     * @param     boolean|string $shop The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByShop($shop = null, $comparison = null)
    {
        if (is_string($shop)) {
            $shop = in_array(strtolower($shop), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_SHOP, $shop, $comparison);
    }

    /**
     * Filter the query on the site_vpc column
     *
     * Example usage:
     * <code>
     * $query->filterByVpc(true); // WHERE site_vpc = true
     * $query->filterByVpc('yes'); // WHERE site_vpc = true
     * </code>
     *
     * @param     boolean|string $vpc The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByVpc($vpc = null, $comparison = null)
    {
        if (is_string($vpc)) {
            $vpc = in_array(strtolower($vpc), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_VPC, $vpc, $comparison);
    }

    /**
     * Filter the query on the site_shipping_fee column
     *
     * Example usage:
     * <code>
     * $query->filterByShippingFee('fooValue');   // WHERE site_shipping_fee = 'fooValue'
     * $query->filterByShippingFee('%fooValue%', Criteria::LIKE); // WHERE site_shipping_fee LIKE '%fooValue%'
     * </code>
     *
     * @param     string $shippingFee The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByShippingFee($shippingFee = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($shippingFee)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_SHIPPING_FEE, $shippingFee, $comparison);
    }

    /**
     * Filter the query on the site_alerts column
     *
     * Example usage:
     * <code>
     * $query->filterByAlerts(true); // WHERE site_alerts = true
     * $query->filterByAlerts('yes'); // WHERE site_alerts = true
     * </code>
     *
     * @param     boolean|string $alerts The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByAlerts($alerts = null, $comparison = null)
    {
        if (is_string($alerts)) {
            $alerts = in_array(strtolower($alerts), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_ALERTS, $alerts, $comparison);
    }

    /**
     * Filter the query on the site_wishlist column
     *
     * Example usage:
     * <code>
     * $query->filterByWishlist(true); // WHERE site_wishlist = true
     * $query->filterByWishlist('yes'); // WHERE site_wishlist = true
     * </code>
     *
     * @param     boolean|string $wishlist The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByWishlist($wishlist = null, $comparison = null)
    {
        if (is_string($wishlist)) {
            $wishlist = in_array(strtolower($wishlist), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_WISHLIST, $wishlist, $comparison);
    }

    /**
     * Filter the query on the site_payment_cheque column
     *
     * Example usage:
     * <code>
     * $query->filterByPaymentCheque(true); // WHERE site_payment_cheque = true
     * $query->filterByPaymentCheque('yes'); // WHERE site_payment_cheque = true
     * </code>
     *
     * @param     boolean|string $paymentCheque The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByPaymentCheque($paymentCheque = null, $comparison = null)
    {
        if (is_string($paymentCheque)) {
            $paymentCheque = in_array(strtolower($paymentCheque), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_PAYMENT_CHEQUE, $paymentCheque, $comparison);
    }

    /**
     * Filter the query on the site_payment_paypal column
     *
     * Example usage:
     * <code>
     * $query->filterByPaymentPaypal('fooValue');   // WHERE site_payment_paypal = 'fooValue'
     * $query->filterByPaymentPaypal('%fooValue%', Criteria::LIKE); // WHERE site_payment_paypal LIKE '%fooValue%'
     * </code>
     *
     * @param     string $paymentPaypal The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByPaymentPaypal($paymentPaypal = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($paymentPaypal)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_PAYMENT_PAYPAL, $paymentPaypal, $comparison);
    }

    /**
     * Filter the query on the site_payment_payplug column
     *
     * Example usage:
     * <code>
     * $query->filterByPaymentPayplug(true); // WHERE site_payment_payplug = true
     * $query->filterByPaymentPayplug('yes'); // WHERE site_payment_payplug = true
     * </code>
     *
     * @param     boolean|string $paymentPayplug The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByPaymentPayplug($paymentPayplug = null, $comparison = null)
    {
        if (is_string($paymentPayplug)) {
            $paymentPayplug = in_array(strtolower($paymentPayplug), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_PAYMENT_PAYPLUG, $paymentPayplug, $comparison);
    }

    /**
     * Filter the query on the site_payment_transfer column
     *
     * Example usage:
     * <code>
     * $query->filterByPaymentTransfer(true); // WHERE site_payment_transfer = true
     * $query->filterByPaymentTransfer('yes'); // WHERE site_payment_transfer = true
     * </code>
     *
     * @param     boolean|string $paymentTransfer The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByPaymentTransfer($paymentTransfer = null, $comparison = null)
    {
        if (is_string($paymentTransfer)) {
            $paymentTransfer = in_array(strtolower($paymentTransfer), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_PAYMENT_TRANSFER, $paymentTransfer, $comparison);
    }

    /**
     * Filter the query on the site_bookshop column
     *
     * Example usage:
     * <code>
     * $query->filterByBookshop(true); // WHERE site_bookshop = true
     * $query->filterByBookshop('yes'); // WHERE site_bookshop = true
     * </code>
     *
     * @param     boolean|string $bookshop The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByBookshop($bookshop = null, $comparison = null)
    {
        if (is_string($bookshop)) {
            $bookshop = in_array(strtolower($bookshop), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_BOOKSHOP, $bookshop, $comparison);
    }

    /**
     * Filter the query on the site_bookshop_id column
     *
     * Example usage:
     * <code>
     * $query->filterByBookshopId(1234); // WHERE site_bookshop_id = 1234
     * $query->filterByBookshopId(array(12, 34)); // WHERE site_bookshop_id IN (12, 34)
     * $query->filterByBookshopId(array('min' => 12)); // WHERE site_bookshop_id > 12
     * </code>
     *
     * @param     mixed $bookshopId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByBookshopId($bookshopId = null, $comparison = null)
    {
        if (is_array($bookshopId)) {
            $useMinMax = false;
            if (isset($bookshopId['min'])) {
                $this->addUsingAlias(SiteTableMap::COL_SITE_BOOKSHOP_ID, $bookshopId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bookshopId['max'])) {
                $this->addUsingAlias(SiteTableMap::COL_SITE_BOOKSHOP_ID, $bookshopId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_BOOKSHOP_ID, $bookshopId, $comparison);
    }

    /**
     * Filter the query on the site_publisher column
     *
     * Example usage:
     * <code>
     * $query->filterByPublisher(true); // WHERE site_publisher = true
     * $query->filterByPublisher('yes'); // WHERE site_publisher = true
     * </code>
     *
     * @param     boolean|string $publisher The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByPublisher($publisher = null, $comparison = null)
    {
        if (is_string($publisher)) {
            $publisher = in_array(strtolower($publisher), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_PUBLISHER, $publisher, $comparison);
    }

    /**
     * Filter the query on the site_publisher_stock column
     *
     * Example usage:
     * <code>
     * $query->filterByPublisherStock(true); // WHERE site_publisher_stock = true
     * $query->filterByPublisherStock('yes'); // WHERE site_publisher_stock = true
     * </code>
     *
     * @param     boolean|string $publisherStock The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByPublisherStock($publisherStock = null, $comparison = null)
    {
        if (is_string($publisherStock)) {
            $publisherStock = in_array(strtolower($publisherStock), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_PUBLISHER_STOCK, $publisherStock, $comparison);
    }

    /**
     * Filter the query on the publisher_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPublisherId(1234); // WHERE publisher_id = 1234
     * $query->filterByPublisherId(array(12, 34)); // WHERE publisher_id IN (12, 34)
     * $query->filterByPublisherId(array('min' => 12)); // WHERE publisher_id > 12
     * </code>
     *
     * @param     mixed $publisherId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByPublisherId($publisherId = null, $comparison = null)
    {
        if (is_array($publisherId)) {
            $useMinMax = false;
            if (isset($publisherId['min'])) {
                $this->addUsingAlias(SiteTableMap::COL_PUBLISHER_ID, $publisherId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publisherId['max'])) {
                $this->addUsingAlias(SiteTableMap::COL_PUBLISHER_ID, $publisherId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_PUBLISHER_ID, $publisherId, $comparison);
    }

    /**
     * Filter the query on the site_ebook_bundle column
     *
     * Example usage:
     * <code>
     * $query->filterByEbookBundle(1234); // WHERE site_ebook_bundle = 1234
     * $query->filterByEbookBundle(array(12, 34)); // WHERE site_ebook_bundle IN (12, 34)
     * $query->filterByEbookBundle(array('min' => 12)); // WHERE site_ebook_bundle > 12
     * </code>
     *
     * @param     mixed $ebookBundle The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByEbookBundle($ebookBundle = null, $comparison = null)
    {
        if (is_array($ebookBundle)) {
            $useMinMax = false;
            if (isset($ebookBundle['min'])) {
                $this->addUsingAlias(SiteTableMap::COL_SITE_EBOOK_BUNDLE, $ebookBundle['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ebookBundle['max'])) {
                $this->addUsingAlias(SiteTableMap::COL_SITE_EBOOK_BUNDLE, $ebookBundle['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_EBOOK_BUNDLE, $ebookBundle, $comparison);
    }

    /**
     * Filter the query on the site_fb_page_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFbPageId(1234); // WHERE site_fb_page_id = 1234
     * $query->filterByFbPageId(array(12, 34)); // WHERE site_fb_page_id IN (12, 34)
     * $query->filterByFbPageId(array('min' => 12)); // WHERE site_fb_page_id > 12
     * </code>
     *
     * @param     mixed $fbPageId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByFbPageId($fbPageId = null, $comparison = null)
    {
        if (is_array($fbPageId)) {
            $useMinMax = false;
            if (isset($fbPageId['min'])) {
                $this->addUsingAlias(SiteTableMap::COL_SITE_FB_PAGE_ID, $fbPageId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($fbPageId['max'])) {
                $this->addUsingAlias(SiteTableMap::COL_SITE_FB_PAGE_ID, $fbPageId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_FB_PAGE_ID, $fbPageId, $comparison);
    }

    /**
     * Filter the query on the site_fb_page_token column
     *
     * Example usage:
     * <code>
     * $query->filterByFbPageToken('fooValue');   // WHERE site_fb_page_token = 'fooValue'
     * $query->filterByFbPageToken('%fooValue%', Criteria::LIKE); // WHERE site_fb_page_token LIKE '%fooValue%'
     * </code>
     *
     * @param     string $fbPageToken The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByFbPageToken($fbPageToken = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($fbPageToken)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_FB_PAGE_TOKEN, $fbPageToken, $comparison);
    }

    /**
     * Filter the query on the site_analytics_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAnalyticsId('fooValue');   // WHERE site_analytics_id = 'fooValue'
     * $query->filterByAnalyticsId('%fooValue%', Criteria::LIKE); // WHERE site_analytics_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $analyticsId The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByAnalyticsId($analyticsId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($analyticsId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_ANALYTICS_ID, $analyticsId, $comparison);
    }

    /**
     * Filter the query on the site_piwik_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPiwikId(1234); // WHERE site_piwik_id = 1234
     * $query->filterByPiwikId(array(12, 34)); // WHERE site_piwik_id IN (12, 34)
     * $query->filterByPiwikId(array('min' => 12)); // WHERE site_piwik_id > 12
     * </code>
     *
     * @param     mixed $piwikId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByPiwikId($piwikId = null, $comparison = null)
    {
        if (is_array($piwikId)) {
            $useMinMax = false;
            if (isset($piwikId['min'])) {
                $this->addUsingAlias(SiteTableMap::COL_SITE_PIWIK_ID, $piwikId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($piwikId['max'])) {
                $this->addUsingAlias(SiteTableMap::COL_SITE_PIWIK_ID, $piwikId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_PIWIK_ID, $piwikId, $comparison);
    }

    /**
     * Filter the query on the site_sitemap_updated column
     *
     * Example usage:
     * <code>
     * $query->filterBySitemapUpdated('2011-03-14'); // WHERE site_sitemap_updated = '2011-03-14'
     * $query->filterBySitemapUpdated('now'); // WHERE site_sitemap_updated = '2011-03-14'
     * $query->filterBySitemapUpdated(array('max' => 'yesterday')); // WHERE site_sitemap_updated > '2011-03-13'
     * </code>
     *
     * @param     mixed $sitemapUpdated The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterBySitemapUpdated($sitemapUpdated = null, $comparison = null)
    {
        if (is_array($sitemapUpdated)) {
            $useMinMax = false;
            if (isset($sitemapUpdated['min'])) {
                $this->addUsingAlias(SiteTableMap::COL_SITE_SITEMAP_UPDATED, $sitemapUpdated['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sitemapUpdated['max'])) {
                $this->addUsingAlias(SiteTableMap::COL_SITE_SITEMAP_UPDATED, $sitemapUpdated['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_SITEMAP_UPDATED, $sitemapUpdated, $comparison);
    }

    /**
     * Filter the query on the site_monitoring column
     *
     * Example usage:
     * <code>
     * $query->filterByMonitoring(true); // WHERE site_monitoring = true
     * $query->filterByMonitoring('yes'); // WHERE site_monitoring = true
     * </code>
     *
     * @param     boolean|string $monitoring The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByMonitoring($monitoring = null, $comparison = null)
    {
        if (is_string($monitoring)) {
            $monitoring = in_array(strtolower($monitoring), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_MONITORING, $monitoring, $comparison);
    }

    /**
     * Filter the query on the site_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE site_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE site_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE site_created > '2011-03-13'
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
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(SiteTableMap::COL_SITE_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(SiteTableMap::COL_SITE_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_CREATED, $createdAt, $comparison);
    }

    /**
     * Filter the query on the site_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE site_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE site_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE site_updated > '2011-03-13'
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
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(SiteTableMap::COL_SITE_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(SiteTableMap::COL_SITE_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SiteTableMap::COL_SITE_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Model\Right object
     *
     * @param \Model\Right|ObjectCollection $right the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSiteQuery The current query, for fluid interface
     */
    public function filterByRight($right, $comparison = null)
    {
        if ($right instanceof \Model\Right) {
            return $this
                ->addUsingAlias(SiteTableMap::COL_SITE_ID, $right->getSiteId(), $comparison);
        } elseif ($right instanceof ObjectCollection) {
            return $this
                ->useRightQuery()
                ->filterByPrimaryKeys($right->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByRight() only accepts arguments of type \Model\Right or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Right relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function joinRight($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
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
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string $typeOfExists Either ExistsCriterion::TYPE_EXISTS or ExistsCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\RightQuery The inner query object of the EXISTS statement
     */
    public function useRightExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        return $this->useExistsQuery('Right', $modelAlias, $queryClass, $typeOfExists);
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
        return $this->useExistsQuery('Right', $modelAlias, $queryClass, 'NOT EXISTS');
    }
    /**
     * Exclude object from result
     *
     * @param   ChildSite $site Object to remove from the list of results
     *
     * @return $this|ChildSiteQuery The current query, for fluid interface
     */
    public function prune($site = null)
    {
        if ($site) {
            $this->addUsingAlias(SiteTableMap::COL_SITE_ID, $site->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the sites table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SiteTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SiteTableMap::clearInstancePool();
            SiteTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(SiteTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SiteTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SiteTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SiteTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildSiteQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(SiteTableMap::COL_SITE_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildSiteQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(SiteTableMap::COL_SITE_UPDATED);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildSiteQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(SiteTableMap::COL_SITE_UPDATED);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildSiteQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(SiteTableMap::COL_SITE_CREATED);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildSiteQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(SiteTableMap::COL_SITE_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildSiteQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(SiteTableMap::COL_SITE_CREATED);
    }

} // SiteQuery
