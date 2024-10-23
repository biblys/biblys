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
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `sites` table.
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
 * @method     ChildSiteQuery leftJoinAlert($relationAlias = null) Adds a LEFT JOIN clause to the query using the Alert relation
 * @method     ChildSiteQuery rightJoinAlert($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Alert relation
 * @method     ChildSiteQuery innerJoinAlert($relationAlias = null) Adds a INNER JOIN clause to the query using the Alert relation
 *
 * @method     ChildSiteQuery joinWithAlert($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Alert relation
 *
 * @method     ChildSiteQuery leftJoinWithAlert() Adds a LEFT JOIN clause and with to the query using the Alert relation
 * @method     ChildSiteQuery rightJoinWithAlert() Adds a RIGHT JOIN clause and with to the query using the Alert relation
 * @method     ChildSiteQuery innerJoinWithAlert() Adds a INNER JOIN clause and with to the query using the Alert relation
 *
 * @method     ChildSiteQuery leftJoinCart($relationAlias = null) Adds a LEFT JOIN clause to the query using the Cart relation
 * @method     ChildSiteQuery rightJoinCart($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Cart relation
 * @method     ChildSiteQuery innerJoinCart($relationAlias = null) Adds a INNER JOIN clause to the query using the Cart relation
 *
 * @method     ChildSiteQuery joinWithCart($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Cart relation
 *
 * @method     ChildSiteQuery leftJoinWithCart() Adds a LEFT JOIN clause and with to the query using the Cart relation
 * @method     ChildSiteQuery rightJoinWithCart() Adds a RIGHT JOIN clause and with to the query using the Cart relation
 * @method     ChildSiteQuery innerJoinWithCart() Adds a INNER JOIN clause and with to the query using the Cart relation
 *
 * @method     ChildSiteQuery leftJoinCrowdfundingCampaign($relationAlias = null) Adds a LEFT JOIN clause to the query using the CrowdfundingCampaign relation
 * @method     ChildSiteQuery rightJoinCrowdfundingCampaign($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CrowdfundingCampaign relation
 * @method     ChildSiteQuery innerJoinCrowdfundingCampaign($relationAlias = null) Adds a INNER JOIN clause to the query using the CrowdfundingCampaign relation
 *
 * @method     ChildSiteQuery joinWithCrowdfundingCampaign($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CrowdfundingCampaign relation
 *
 * @method     ChildSiteQuery leftJoinWithCrowdfundingCampaign() Adds a LEFT JOIN clause and with to the query using the CrowdfundingCampaign relation
 * @method     ChildSiteQuery rightJoinWithCrowdfundingCampaign() Adds a RIGHT JOIN clause and with to the query using the CrowdfundingCampaign relation
 * @method     ChildSiteQuery innerJoinWithCrowdfundingCampaign() Adds a INNER JOIN clause and with to the query using the CrowdfundingCampaign relation
 *
 * @method     ChildSiteQuery leftJoinCrowfundingReward($relationAlias = null) Adds a LEFT JOIN clause to the query using the CrowfundingReward relation
 * @method     ChildSiteQuery rightJoinCrowfundingReward($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CrowfundingReward relation
 * @method     ChildSiteQuery innerJoinCrowfundingReward($relationAlias = null) Adds a INNER JOIN clause to the query using the CrowfundingReward relation
 *
 * @method     ChildSiteQuery joinWithCrowfundingReward($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the CrowfundingReward relation
 *
 * @method     ChildSiteQuery leftJoinWithCrowfundingReward() Adds a LEFT JOIN clause and with to the query using the CrowfundingReward relation
 * @method     ChildSiteQuery rightJoinWithCrowfundingReward() Adds a RIGHT JOIN clause and with to the query using the CrowfundingReward relation
 * @method     ChildSiteQuery innerJoinWithCrowfundingReward() Adds a INNER JOIN clause and with to the query using the CrowfundingReward relation
 *
 * @method     ChildSiteQuery leftJoinCustomer($relationAlias = null) Adds a LEFT JOIN clause to the query using the Customer relation
 * @method     ChildSiteQuery rightJoinCustomer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Customer relation
 * @method     ChildSiteQuery innerJoinCustomer($relationAlias = null) Adds a INNER JOIN clause to the query using the Customer relation
 *
 * @method     ChildSiteQuery joinWithCustomer($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Customer relation
 *
 * @method     ChildSiteQuery leftJoinWithCustomer() Adds a LEFT JOIN clause and with to the query using the Customer relation
 * @method     ChildSiteQuery rightJoinWithCustomer() Adds a RIGHT JOIN clause and with to the query using the Customer relation
 * @method     ChildSiteQuery innerJoinWithCustomer() Adds a INNER JOIN clause and with to the query using the Customer relation
 *
 * @method     ChildSiteQuery leftJoinDownload($relationAlias = null) Adds a LEFT JOIN clause to the query using the Download relation
 * @method     ChildSiteQuery rightJoinDownload($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Download relation
 * @method     ChildSiteQuery innerJoinDownload($relationAlias = null) Adds a INNER JOIN clause to the query using the Download relation
 *
 * @method     ChildSiteQuery joinWithDownload($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Download relation
 *
 * @method     ChildSiteQuery leftJoinWithDownload() Adds a LEFT JOIN clause and with to the query using the Download relation
 * @method     ChildSiteQuery rightJoinWithDownload() Adds a RIGHT JOIN clause and with to the query using the Download relation
 * @method     ChildSiteQuery innerJoinWithDownload() Adds a INNER JOIN clause and with to the query using the Download relation
 *
 * @method     ChildSiteQuery leftJoinFile($relationAlias = null) Adds a LEFT JOIN clause to the query using the File relation
 * @method     ChildSiteQuery rightJoinFile($relationAlias = null) Adds a RIGHT JOIN clause to the query using the File relation
 * @method     ChildSiteQuery innerJoinFile($relationAlias = null) Adds a INNER JOIN clause to the query using the File relation
 *
 * @method     ChildSiteQuery joinWithFile($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the File relation
 *
 * @method     ChildSiteQuery leftJoinWithFile() Adds a LEFT JOIN clause and with to the query using the File relation
 * @method     ChildSiteQuery rightJoinWithFile() Adds a RIGHT JOIN clause and with to the query using the File relation
 * @method     ChildSiteQuery innerJoinWithFile() Adds a INNER JOIN clause and with to the query using the File relation
 *
 * @method     ChildSiteQuery leftJoinImage($relationAlias = null) Adds a LEFT JOIN clause to the query using the Image relation
 * @method     ChildSiteQuery rightJoinImage($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Image relation
 * @method     ChildSiteQuery innerJoinImage($relationAlias = null) Adds a INNER JOIN clause to the query using the Image relation
 *
 * @method     ChildSiteQuery joinWithImage($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Image relation
 *
 * @method     ChildSiteQuery leftJoinWithImage() Adds a LEFT JOIN clause and with to the query using the Image relation
 * @method     ChildSiteQuery rightJoinWithImage() Adds a RIGHT JOIN clause and with to the query using the Image relation
 * @method     ChildSiteQuery innerJoinWithImage() Adds a INNER JOIN clause and with to the query using the Image relation
 *
 * @method     ChildSiteQuery leftJoinInvitation($relationAlias = null) Adds a LEFT JOIN clause to the query using the Invitation relation
 * @method     ChildSiteQuery rightJoinInvitation($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Invitation relation
 * @method     ChildSiteQuery innerJoinInvitation($relationAlias = null) Adds a INNER JOIN clause to the query using the Invitation relation
 *
 * @method     ChildSiteQuery joinWithInvitation($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Invitation relation
 *
 * @method     ChildSiteQuery leftJoinWithInvitation() Adds a LEFT JOIN clause and with to the query using the Invitation relation
 * @method     ChildSiteQuery rightJoinWithInvitation() Adds a RIGHT JOIN clause and with to the query using the Invitation relation
 * @method     ChildSiteQuery innerJoinWithInvitation() Adds a INNER JOIN clause and with to the query using the Invitation relation
 *
 * @method     ChildSiteQuery leftJoinStockItemList($relationAlias = null) Adds a LEFT JOIN clause to the query using the StockItemList relation
 * @method     ChildSiteQuery rightJoinStockItemList($relationAlias = null) Adds a RIGHT JOIN clause to the query using the StockItemList relation
 * @method     ChildSiteQuery innerJoinStockItemList($relationAlias = null) Adds a INNER JOIN clause to the query using the StockItemList relation
 *
 * @method     ChildSiteQuery joinWithStockItemList($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the StockItemList relation
 *
 * @method     ChildSiteQuery leftJoinWithStockItemList() Adds a LEFT JOIN clause and with to the query using the StockItemList relation
 * @method     ChildSiteQuery rightJoinWithStockItemList() Adds a RIGHT JOIN clause and with to the query using the StockItemList relation
 * @method     ChildSiteQuery innerJoinWithStockItemList() Adds a INNER JOIN clause and with to the query using the StockItemList relation
 *
 * @method     ChildSiteQuery leftJoinOption($relationAlias = null) Adds a LEFT JOIN clause to the query using the Option relation
 * @method     ChildSiteQuery rightJoinOption($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Option relation
 * @method     ChildSiteQuery innerJoinOption($relationAlias = null) Adds a INNER JOIN clause to the query using the Option relation
 *
 * @method     ChildSiteQuery joinWithOption($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Option relation
 *
 * @method     ChildSiteQuery leftJoinWithOption() Adds a LEFT JOIN clause and with to the query using the Option relation
 * @method     ChildSiteQuery rightJoinWithOption() Adds a RIGHT JOIN clause and with to the query using the Option relation
 * @method     ChildSiteQuery innerJoinWithOption() Adds a INNER JOIN clause and with to the query using the Option relation
 *
 * @method     ChildSiteQuery leftJoinOrder($relationAlias = null) Adds a LEFT JOIN clause to the query using the Order relation
 * @method     ChildSiteQuery rightJoinOrder($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Order relation
 * @method     ChildSiteQuery innerJoinOrder($relationAlias = null) Adds a INNER JOIN clause to the query using the Order relation
 *
 * @method     ChildSiteQuery joinWithOrder($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Order relation
 *
 * @method     ChildSiteQuery leftJoinWithOrder() Adds a LEFT JOIN clause and with to the query using the Order relation
 * @method     ChildSiteQuery rightJoinWithOrder() Adds a RIGHT JOIN clause and with to the query using the Order relation
 * @method     ChildSiteQuery innerJoinWithOrder() Adds a INNER JOIN clause and with to the query using the Order relation
 *
 * @method     ChildSiteQuery leftJoinPage($relationAlias = null) Adds a LEFT JOIN clause to the query using the Page relation
 * @method     ChildSiteQuery rightJoinPage($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Page relation
 * @method     ChildSiteQuery innerJoinPage($relationAlias = null) Adds a INNER JOIN clause to the query using the Page relation
 *
 * @method     ChildSiteQuery joinWithPage($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Page relation
 *
 * @method     ChildSiteQuery leftJoinWithPage() Adds a LEFT JOIN clause and with to the query using the Page relation
 * @method     ChildSiteQuery rightJoinWithPage() Adds a RIGHT JOIN clause and with to the query using the Page relation
 * @method     ChildSiteQuery innerJoinWithPage() Adds a INNER JOIN clause and with to the query using the Page relation
 *
 * @method     ChildSiteQuery leftJoinPayment($relationAlias = null) Adds a LEFT JOIN clause to the query using the Payment relation
 * @method     ChildSiteQuery rightJoinPayment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Payment relation
 * @method     ChildSiteQuery innerJoinPayment($relationAlias = null) Adds a INNER JOIN clause to the query using the Payment relation
 *
 * @method     ChildSiteQuery joinWithPayment($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Payment relation
 *
 * @method     ChildSiteQuery leftJoinWithPayment() Adds a LEFT JOIN clause and with to the query using the Payment relation
 * @method     ChildSiteQuery rightJoinWithPayment() Adds a RIGHT JOIN clause and with to the query using the Payment relation
 * @method     ChildSiteQuery innerJoinWithPayment() Adds a INNER JOIN clause and with to the query using the Payment relation
 *
 * @method     ChildSiteQuery leftJoinPost($relationAlias = null) Adds a LEFT JOIN clause to the query using the Post relation
 * @method     ChildSiteQuery rightJoinPost($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Post relation
 * @method     ChildSiteQuery innerJoinPost($relationAlias = null) Adds a INNER JOIN clause to the query using the Post relation
 *
 * @method     ChildSiteQuery joinWithPost($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Post relation
 *
 * @method     ChildSiteQuery leftJoinWithPost() Adds a LEFT JOIN clause and with to the query using the Post relation
 * @method     ChildSiteQuery rightJoinWithPost() Adds a RIGHT JOIN clause and with to the query using the Post relation
 * @method     ChildSiteQuery innerJoinWithPost() Adds a INNER JOIN clause and with to the query using the Post relation
 *
 * @method     ChildSiteQuery leftJoinArticleCategory($relationAlias = null) Adds a LEFT JOIN clause to the query using the ArticleCategory relation
 * @method     ChildSiteQuery rightJoinArticleCategory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ArticleCategory relation
 * @method     ChildSiteQuery innerJoinArticleCategory($relationAlias = null) Adds a INNER JOIN clause to the query using the ArticleCategory relation
 *
 * @method     ChildSiteQuery joinWithArticleCategory($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ArticleCategory relation
 *
 * @method     ChildSiteQuery leftJoinWithArticleCategory() Adds a LEFT JOIN clause and with to the query using the ArticleCategory relation
 * @method     ChildSiteQuery rightJoinWithArticleCategory() Adds a RIGHT JOIN clause and with to the query using the ArticleCategory relation
 * @method     ChildSiteQuery innerJoinWithArticleCategory() Adds a INNER JOIN clause and with to the query using the ArticleCategory relation
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
 * @method     ChildSiteQuery leftJoinSession($relationAlias = null) Adds a LEFT JOIN clause to the query using the Session relation
 * @method     ChildSiteQuery rightJoinSession($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Session relation
 * @method     ChildSiteQuery innerJoinSession($relationAlias = null) Adds a INNER JOIN clause to the query using the Session relation
 *
 * @method     ChildSiteQuery joinWithSession($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Session relation
 *
 * @method     ChildSiteQuery leftJoinWithSession() Adds a LEFT JOIN clause and with to the query using the Session relation
 * @method     ChildSiteQuery rightJoinWithSession() Adds a RIGHT JOIN clause and with to the query using the Session relation
 * @method     ChildSiteQuery innerJoinWithSession() Adds a INNER JOIN clause and with to the query using the Session relation
 *
 * @method     ChildSiteQuery leftJoinSpecialOffer($relationAlias = null) Adds a LEFT JOIN clause to the query using the SpecialOffer relation
 * @method     ChildSiteQuery rightJoinSpecialOffer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SpecialOffer relation
 * @method     ChildSiteQuery innerJoinSpecialOffer($relationAlias = null) Adds a INNER JOIN clause to the query using the SpecialOffer relation
 *
 * @method     ChildSiteQuery joinWithSpecialOffer($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the SpecialOffer relation
 *
 * @method     ChildSiteQuery leftJoinWithSpecialOffer() Adds a LEFT JOIN clause and with to the query using the SpecialOffer relation
 * @method     ChildSiteQuery rightJoinWithSpecialOffer() Adds a RIGHT JOIN clause and with to the query using the SpecialOffer relation
 * @method     ChildSiteQuery innerJoinWithSpecialOffer() Adds a INNER JOIN clause and with to the query using the SpecialOffer relation
 *
 * @method     ChildSiteQuery leftJoinStock($relationAlias = null) Adds a LEFT JOIN clause to the query using the Stock relation
 * @method     ChildSiteQuery rightJoinStock($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Stock relation
 * @method     ChildSiteQuery innerJoinStock($relationAlias = null) Adds a INNER JOIN clause to the query using the Stock relation
 *
 * @method     ChildSiteQuery joinWithStock($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Stock relation
 *
 * @method     ChildSiteQuery leftJoinWithStock() Adds a LEFT JOIN clause and with to the query using the Stock relation
 * @method     ChildSiteQuery rightJoinWithStock() Adds a RIGHT JOIN clause and with to the query using the Stock relation
 * @method     ChildSiteQuery innerJoinWithStock() Adds a INNER JOIN clause and with to the query using the Stock relation
 *
 * @method     ChildSiteQuery leftJoinSubscription($relationAlias = null) Adds a LEFT JOIN clause to the query using the Subscription relation
 * @method     ChildSiteQuery rightJoinSubscription($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Subscription relation
 * @method     ChildSiteQuery innerJoinSubscription($relationAlias = null) Adds a INNER JOIN clause to the query using the Subscription relation
 *
 * @method     ChildSiteQuery joinWithSubscription($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Subscription relation
 *
 * @method     ChildSiteQuery leftJoinWithSubscription() Adds a LEFT JOIN clause and with to the query using the Subscription relation
 * @method     ChildSiteQuery rightJoinWithSubscription() Adds a RIGHT JOIN clause and with to the query using the Subscription relation
 * @method     ChildSiteQuery innerJoinWithSubscription() Adds a INNER JOIN clause and with to the query using the Subscription relation
 *
 * @method     ChildSiteQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildSiteQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildSiteQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildSiteQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildSiteQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildSiteQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildSiteQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     ChildSiteQuery leftJoinAuthenticationMethod($relationAlias = null) Adds a LEFT JOIN clause to the query using the AuthenticationMethod relation
 * @method     ChildSiteQuery rightJoinAuthenticationMethod($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AuthenticationMethod relation
 * @method     ChildSiteQuery innerJoinAuthenticationMethod($relationAlias = null) Adds a INNER JOIN clause to the query using the AuthenticationMethod relation
 *
 * @method     ChildSiteQuery joinWithAuthenticationMethod($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the AuthenticationMethod relation
 *
 * @method     ChildSiteQuery leftJoinWithAuthenticationMethod() Adds a LEFT JOIN clause and with to the query using the AuthenticationMethod relation
 * @method     ChildSiteQuery rightJoinWithAuthenticationMethod() Adds a RIGHT JOIN clause and with to the query using the AuthenticationMethod relation
 * @method     ChildSiteQuery innerJoinWithAuthenticationMethod() Adds a INNER JOIN clause and with to the query using the AuthenticationMethod relation
 *
 * @method     ChildSiteQuery leftJoinVote($relationAlias = null) Adds a LEFT JOIN clause to the query using the Vote relation
 * @method     ChildSiteQuery rightJoinVote($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Vote relation
 * @method     ChildSiteQuery innerJoinVote($relationAlias = null) Adds a INNER JOIN clause to the query using the Vote relation
 *
 * @method     ChildSiteQuery joinWithVote($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Vote relation
 *
 * @method     ChildSiteQuery leftJoinWithVote() Adds a LEFT JOIN clause and with to the query using the Vote relation
 * @method     ChildSiteQuery rightJoinWithVote() Adds a RIGHT JOIN clause and with to the query using the Vote relation
 * @method     ChildSiteQuery innerJoinWithVote() Adds a INNER JOIN clause and with to the query using the Vote relation
 *
 * @method     ChildSiteQuery leftJoinWishlist($relationAlias = null) Adds a LEFT JOIN clause to the query using the Wishlist relation
 * @method     ChildSiteQuery rightJoinWishlist($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Wishlist relation
 * @method     ChildSiteQuery innerJoinWishlist($relationAlias = null) Adds a INNER JOIN clause to the query using the Wishlist relation
 *
 * @method     ChildSiteQuery joinWithWishlist($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Wishlist relation
 *
 * @method     ChildSiteQuery leftJoinWithWishlist() Adds a LEFT JOIN clause and with to the query using the Wishlist relation
 * @method     ChildSiteQuery rightJoinWithWishlist() Adds a RIGHT JOIN clause and with to the query using the Wishlist relation
 * @method     ChildSiteQuery innerJoinWithWishlist() Adds a INNER JOIN clause and with to the query using the Wishlist relation
 *
 * @method     \Model\AlertQuery|\Model\CartQuery|\Model\CrowdfundingCampaignQuery|\Model\CrowfundingRewardQuery|\Model\CustomerQuery|\Model\DownloadQuery|\Model\FileQuery|\Model\ImageQuery|\Model\InvitationQuery|\Model\StockItemListQuery|\Model\OptionQuery|\Model\OrderQuery|\Model\PageQuery|\Model\PaymentQuery|\Model\PostQuery|\Model\ArticleCategoryQuery|\Model\RightQuery|\Model\SessionQuery|\Model\SpecialOfferQuery|\Model\StockQuery|\Model\SubscriptionQuery|\Model\UserQuery|\Model\AuthenticationMethodQuery|\Model\VoteQuery|\Model\WishlistQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildSite|null findOne(?ConnectionInterface $con = null) Return the first ChildSite matching the query
 * @method     ChildSite findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildSite matching the query, or a new ChildSite object populated from the query conditions when no match is found
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
 * @method     ChildSite|null findOneByUpdatedAt(string $site_updated) Return the first ChildSite filtered by the site_updated column
 *
 * @method     ChildSite requirePk($key, ?ConnectionInterface $con = null) Return the ChildSite by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSite requireOne(?ConnectionInterface $con = null) Return the first ChildSite matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
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
 * @method     ChildSite[]|Collection find(?ConnectionInterface $con = null) Return ChildSite objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildSite> find(?ConnectionInterface $con = null) Return ChildSite objects based on current ModelCriteria
 *
 * @method     ChildSite[]|Collection findById(int|array<int> $site_id) Return ChildSite objects filtered by the site_id column
 * @psalm-method Collection&\Traversable<ChildSite> findById(int|array<int> $site_id) Return ChildSite objects filtered by the site_id column
 * @method     ChildSite[]|Collection findByName(string|array<string> $site_name) Return ChildSite objects filtered by the site_name column
 * @psalm-method Collection&\Traversable<ChildSite> findByName(string|array<string> $site_name) Return ChildSite objects filtered by the site_name column
 * @method     ChildSite[]|Collection findByPass(string|array<string> $site_pass) Return ChildSite objects filtered by the site_pass column
 * @psalm-method Collection&\Traversable<ChildSite> findByPass(string|array<string> $site_pass) Return ChildSite objects filtered by the site_pass column
 * @method     ChildSite[]|Collection findByTitle(string|array<string> $site_title) Return ChildSite objects filtered by the site_title column
 * @psalm-method Collection&\Traversable<ChildSite> findByTitle(string|array<string> $site_title) Return ChildSite objects filtered by the site_title column
 * @method     ChildSite[]|Collection findByDomain(string|array<string> $site_domain) Return ChildSite objects filtered by the site_domain column
 * @psalm-method Collection&\Traversable<ChildSite> findByDomain(string|array<string> $site_domain) Return ChildSite objects filtered by the site_domain column
 * @method     ChildSite[]|Collection findByVersion(string|array<string> $site_version) Return ChildSite objects filtered by the site_version column
 * @psalm-method Collection&\Traversable<ChildSite> findByVersion(string|array<string> $site_version) Return ChildSite objects filtered by the site_version column
 * @method     ChildSite[]|Collection findByTag(string|array<string> $site_tag) Return ChildSite objects filtered by the site_tag column
 * @psalm-method Collection&\Traversable<ChildSite> findByTag(string|array<string> $site_tag) Return ChildSite objects filtered by the site_tag column
 * @method     ChildSite[]|Collection findByFlag(string|array<string> $site_flag) Return ChildSite objects filtered by the site_flag column
 * @psalm-method Collection&\Traversable<ChildSite> findByFlag(string|array<string> $site_flag) Return ChildSite objects filtered by the site_flag column
 * @method     ChildSite[]|Collection findByContact(string|array<string> $site_contact) Return ChildSite objects filtered by the site_contact column
 * @psalm-method Collection&\Traversable<ChildSite> findByContact(string|array<string> $site_contact) Return ChildSite objects filtered by the site_contact column
 * @method     ChildSite[]|Collection findByAddress(string|array<string> $site_address) Return ChildSite objects filtered by the site_address column
 * @psalm-method Collection&\Traversable<ChildSite> findByAddress(string|array<string> $site_address) Return ChildSite objects filtered by the site_address column
 * @method     ChildSite[]|Collection findByTva(string|array<string> $site_tva) Return ChildSite objects filtered by the site_tva column
 * @psalm-method Collection&\Traversable<ChildSite> findByTva(string|array<string> $site_tva) Return ChildSite objects filtered by the site_tva column
 * @method     ChildSite[]|Collection findByHtmlRenderer(boolean|array<boolean> $site_html_renderer) Return ChildSite objects filtered by the site_html_renderer column
 * @psalm-method Collection&\Traversable<ChildSite> findByHtmlRenderer(boolean|array<boolean> $site_html_renderer) Return ChildSite objects filtered by the site_html_renderer column
 * @method     ChildSite[]|Collection findByAxys(boolean|array<boolean> $site_axys) Return ChildSite objects filtered by the site_axys column
 * @psalm-method Collection&\Traversable<ChildSite> findByAxys(boolean|array<boolean> $site_axys) Return ChildSite objects filtered by the site_axys column
 * @method     ChildSite[]|Collection findByNoosfere(boolean|array<boolean> $site_noosfere) Return ChildSite objects filtered by the site_noosfere column
 * @psalm-method Collection&\Traversable<ChildSite> findByNoosfere(boolean|array<boolean> $site_noosfere) Return ChildSite objects filtered by the site_noosfere column
 * @method     ChildSite[]|Collection findByAmazon(boolean|array<boolean> $site_amazon) Return ChildSite objects filtered by the site_amazon column
 * @psalm-method Collection&\Traversable<ChildSite> findByAmazon(boolean|array<boolean> $site_amazon) Return ChildSite objects filtered by the site_amazon column
 * @method     ChildSite[]|Collection findByEventId(int|array<int> $site_event_id) Return ChildSite objects filtered by the site_event_id column
 * @psalm-method Collection&\Traversable<ChildSite> findByEventId(int|array<int> $site_event_id) Return ChildSite objects filtered by the site_event_id column
 * @method     ChildSite[]|Collection findByEventDate(int|array<int> $site_event_date) Return ChildSite objects filtered by the site_event_date column
 * @psalm-method Collection&\Traversable<ChildSite> findByEventDate(int|array<int> $site_event_date) Return ChildSite objects filtered by the site_event_date column
 * @method     ChildSite[]|Collection findByShop(boolean|array<boolean> $site_shop) Return ChildSite objects filtered by the site_shop column
 * @psalm-method Collection&\Traversable<ChildSite> findByShop(boolean|array<boolean> $site_shop) Return ChildSite objects filtered by the site_shop column
 * @method     ChildSite[]|Collection findByVpc(boolean|array<boolean> $site_vpc) Return ChildSite objects filtered by the site_vpc column
 * @psalm-method Collection&\Traversable<ChildSite> findByVpc(boolean|array<boolean> $site_vpc) Return ChildSite objects filtered by the site_vpc column
 * @method     ChildSite[]|Collection findByShippingFee(string|array<string> $site_shipping_fee) Return ChildSite objects filtered by the site_shipping_fee column
 * @psalm-method Collection&\Traversable<ChildSite> findByShippingFee(string|array<string> $site_shipping_fee) Return ChildSite objects filtered by the site_shipping_fee column
 * @method     ChildSite[]|Collection findByPaymentCheque(boolean|array<boolean> $site_payment_cheque) Return ChildSite objects filtered by the site_payment_cheque column
 * @psalm-method Collection&\Traversable<ChildSite> findByPaymentCheque(boolean|array<boolean> $site_payment_cheque) Return ChildSite objects filtered by the site_payment_cheque column
 * @method     ChildSite[]|Collection findByPaymentPaypal(string|array<string> $site_payment_paypal) Return ChildSite objects filtered by the site_payment_paypal column
 * @psalm-method Collection&\Traversable<ChildSite> findByPaymentPaypal(string|array<string> $site_payment_paypal) Return ChildSite objects filtered by the site_payment_paypal column
 * @method     ChildSite[]|Collection findByPaymentPayplug(boolean|array<boolean> $site_payment_payplug) Return ChildSite objects filtered by the site_payment_payplug column
 * @psalm-method Collection&\Traversable<ChildSite> findByPaymentPayplug(boolean|array<boolean> $site_payment_payplug) Return ChildSite objects filtered by the site_payment_payplug column
 * @method     ChildSite[]|Collection findByPaymentTransfer(boolean|array<boolean> $site_payment_transfer) Return ChildSite objects filtered by the site_payment_transfer column
 * @psalm-method Collection&\Traversable<ChildSite> findByPaymentTransfer(boolean|array<boolean> $site_payment_transfer) Return ChildSite objects filtered by the site_payment_transfer column
 * @method     ChildSite[]|Collection findByBookshop(boolean|array<boolean> $site_bookshop) Return ChildSite objects filtered by the site_bookshop column
 * @psalm-method Collection&\Traversable<ChildSite> findByBookshop(boolean|array<boolean> $site_bookshop) Return ChildSite objects filtered by the site_bookshop column
 * @method     ChildSite[]|Collection findByBookshopId(int|array<int> $site_bookshop_id) Return ChildSite objects filtered by the site_bookshop_id column
 * @psalm-method Collection&\Traversable<ChildSite> findByBookshopId(int|array<int> $site_bookshop_id) Return ChildSite objects filtered by the site_bookshop_id column
 * @method     ChildSite[]|Collection findByPublisher(boolean|array<boolean> $site_publisher) Return ChildSite objects filtered by the site_publisher column
 * @psalm-method Collection&\Traversable<ChildSite> findByPublisher(boolean|array<boolean> $site_publisher) Return ChildSite objects filtered by the site_publisher column
 * @method     ChildSite[]|Collection findByPublisherStock(boolean|array<boolean> $site_publisher_stock) Return ChildSite objects filtered by the site_publisher_stock column
 * @psalm-method Collection&\Traversable<ChildSite> findByPublisherStock(boolean|array<boolean> $site_publisher_stock) Return ChildSite objects filtered by the site_publisher_stock column
 * @method     ChildSite[]|Collection findByPublisherId(int|array<int> $publisher_id) Return ChildSite objects filtered by the publisher_id column
 * @psalm-method Collection&\Traversable<ChildSite> findByPublisherId(int|array<int> $publisher_id) Return ChildSite objects filtered by the publisher_id column
 * @method     ChildSite[]|Collection findByEbookBundle(int|array<int> $site_ebook_bundle) Return ChildSite objects filtered by the site_ebook_bundle column
 * @psalm-method Collection&\Traversable<ChildSite> findByEbookBundle(int|array<int> $site_ebook_bundle) Return ChildSite objects filtered by the site_ebook_bundle column
 * @method     ChildSite[]|Collection findByFbPageId(string|array<string> $site_fb_page_id) Return ChildSite objects filtered by the site_fb_page_id column
 * @psalm-method Collection&\Traversable<ChildSite> findByFbPageId(string|array<string> $site_fb_page_id) Return ChildSite objects filtered by the site_fb_page_id column
 * @method     ChildSite[]|Collection findByFbPageToken(string|array<string> $site_fb_page_token) Return ChildSite objects filtered by the site_fb_page_token column
 * @psalm-method Collection&\Traversable<ChildSite> findByFbPageToken(string|array<string> $site_fb_page_token) Return ChildSite objects filtered by the site_fb_page_token column
 * @method     ChildSite[]|Collection findByAnalyticsId(string|array<string> $site_analytics_id) Return ChildSite objects filtered by the site_analytics_id column
 * @psalm-method Collection&\Traversable<ChildSite> findByAnalyticsId(string|array<string> $site_analytics_id) Return ChildSite objects filtered by the site_analytics_id column
 * @method     ChildSite[]|Collection findByPiwikId(int|array<int> $site_piwik_id) Return ChildSite objects filtered by the site_piwik_id column
 * @psalm-method Collection&\Traversable<ChildSite> findByPiwikId(int|array<int> $site_piwik_id) Return ChildSite objects filtered by the site_piwik_id column
 * @method     ChildSite[]|Collection findBySitemapUpdated(string|array<string> $site_sitemap_updated) Return ChildSite objects filtered by the site_sitemap_updated column
 * @psalm-method Collection&\Traversable<ChildSite> findBySitemapUpdated(string|array<string> $site_sitemap_updated) Return ChildSite objects filtered by the site_sitemap_updated column
 * @method     ChildSite[]|Collection findByMonitoring(boolean|array<boolean> $site_monitoring) Return ChildSite objects filtered by the site_monitoring column
 * @psalm-method Collection&\Traversable<ChildSite> findByMonitoring(boolean|array<boolean> $site_monitoring) Return ChildSite objects filtered by the site_monitoring column
 * @method     ChildSite[]|Collection findByCreatedAt(string|array<string> $site_created) Return ChildSite objects filtered by the site_created column
 * @psalm-method Collection&\Traversable<ChildSite> findByCreatedAt(string|array<string> $site_created) Return ChildSite objects filtered by the site_created column
 * @method     ChildSite[]|Collection findByUpdatedAt(string|array<string> $site_updated) Return ChildSite objects filtered by the site_updated column
 * @psalm-method Collection&\Traversable<ChildSite> findByUpdatedAt(string|array<string> $site_updated) Return ChildSite objects filtered by the site_updated column
 *
 * @method     ChildSite[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildSite> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class SiteQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\SiteQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Site', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSiteQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSiteQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
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
    public function findPk($key, ?ConnectionInterface $con = null)
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
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSite A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT site_id, site_name, site_pass, site_title, site_domain, site_version, site_tag, site_flag, site_contact, site_address, site_tva, site_html_renderer, site_axys, site_noosfere, site_amazon, site_event_id, site_event_date, site_shop, site_vpc, site_shipping_fee, site_payment_cheque, site_payment_paypal, site_payment_payplug, site_payment_transfer, site_bookshop, site_bookshop_id, site_publisher, site_publisher_stock, publisher_id, site_ebook_bundle, site_fb_page_id, site_fb_page_token, site_analytics_id, site_piwik_id, site_sitemap_updated, site_monitoring, site_created, site_updated FROM sites WHERE site_id = :p0';
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
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
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

        $this->addUsingAlias(SiteTableMap::COL_SITE_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(SiteTableMap::COL_SITE_ID, $keys, Criteria::IN);

        return $this;
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

        $this->addUsingAlias(SiteTableMap::COL_SITE_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the site_name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE site_name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE site_name LIKE '%fooValue%'
     * $query->filterByName(['foo', 'bar']); // WHERE site_name IN ('foo', 'bar')
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

        $this->addUsingAlias(SiteTableMap::COL_SITE_NAME, $name, $comparison);

        return $this;
    }

    /**
     * Filter the query on the site_pass column
     *
     * Example usage:
     * <code>
     * $query->filterByPass('fooValue');   // WHERE site_pass = 'fooValue'
     * $query->filterByPass('%fooValue%', Criteria::LIKE); // WHERE site_pass LIKE '%fooValue%'
     * $query->filterByPass(['foo', 'bar']); // WHERE site_pass IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $pass The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPass($pass = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($pass)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SiteTableMap::COL_SITE_PASS, $pass, $comparison);

        return $this;
    }

    /**
     * Filter the query on the site_title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE site_title = 'fooValue'
     * $query->filterByTitle('%fooValue%', Criteria::LIKE); // WHERE site_title LIKE '%fooValue%'
     * $query->filterByTitle(['foo', 'bar']); // WHERE site_title IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $title The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTitle($title = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SiteTableMap::COL_SITE_TITLE, $title, $comparison);

        return $this;
    }

    /**
     * Filter the query on the site_domain column
     *
     * Example usage:
     * <code>
     * $query->filterByDomain('fooValue');   // WHERE site_domain = 'fooValue'
     * $query->filterByDomain('%fooValue%', Criteria::LIKE); // WHERE site_domain LIKE '%fooValue%'
     * $query->filterByDomain(['foo', 'bar']); // WHERE site_domain IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $domain The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDomain($domain = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($domain)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SiteTableMap::COL_SITE_DOMAIN, $domain, $comparison);

        return $this;
    }

    /**
     * Filter the query on the site_version column
     *
     * Example usage:
     * <code>
     * $query->filterByVersion('fooValue');   // WHERE site_version = 'fooValue'
     * $query->filterByVersion('%fooValue%', Criteria::LIKE); // WHERE site_version LIKE '%fooValue%'
     * $query->filterByVersion(['foo', 'bar']); // WHERE site_version IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $version The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByVersion($version = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($version)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SiteTableMap::COL_SITE_VERSION, $version, $comparison);

        return $this;
    }

    /**
     * Filter the query on the site_tag column
     *
     * Example usage:
     * <code>
     * $query->filterByTag('fooValue');   // WHERE site_tag = 'fooValue'
     * $query->filterByTag('%fooValue%', Criteria::LIKE); // WHERE site_tag LIKE '%fooValue%'
     * $query->filterByTag(['foo', 'bar']); // WHERE site_tag IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $tag The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTag($tag = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($tag)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SiteTableMap::COL_SITE_TAG, $tag, $comparison);

        return $this;
    }

    /**
     * Filter the query on the site_flag column
     *
     * Example usage:
     * <code>
     * $query->filterByFlag('fooValue');   // WHERE site_flag = 'fooValue'
     * $query->filterByFlag('%fooValue%', Criteria::LIKE); // WHERE site_flag LIKE '%fooValue%'
     * $query->filterByFlag(['foo', 'bar']); // WHERE site_flag IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $flag The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFlag($flag = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($flag)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SiteTableMap::COL_SITE_FLAG, $flag, $comparison);

        return $this;
    }

    /**
     * Filter the query on the site_contact column
     *
     * Example usage:
     * <code>
     * $query->filterByContact('fooValue');   // WHERE site_contact = 'fooValue'
     * $query->filterByContact('%fooValue%', Criteria::LIKE); // WHERE site_contact LIKE '%fooValue%'
     * $query->filterByContact(['foo', 'bar']); // WHERE site_contact IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $contact The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByContact($contact = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($contact)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SiteTableMap::COL_SITE_CONTACT, $contact, $comparison);

        return $this;
    }

    /**
     * Filter the query on the site_address column
     *
     * Example usage:
     * <code>
     * $query->filterByAddress('fooValue');   // WHERE site_address = 'fooValue'
     * $query->filterByAddress('%fooValue%', Criteria::LIKE); // WHERE site_address LIKE '%fooValue%'
     * $query->filterByAddress(['foo', 'bar']); // WHERE site_address IN ('foo', 'bar')
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

        $this->addUsingAlias(SiteTableMap::COL_SITE_ADDRESS, $address, $comparison);

        return $this;
    }

    /**
     * Filter the query on the site_tva column
     *
     * Example usage:
     * <code>
     * $query->filterByTva('fooValue');   // WHERE site_tva = 'fooValue'
     * $query->filterByTva('%fooValue%', Criteria::LIKE); // WHERE site_tva LIKE '%fooValue%'
     * $query->filterByTva(['foo', 'bar']); // WHERE site_tva IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $tva The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTva($tva = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($tva)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SiteTableMap::COL_SITE_TVA, $tva, $comparison);

        return $this;
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
     * @param bool|string $htmlRenderer The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByHtmlRenderer($htmlRenderer = null, ?string $comparison = null)
    {
        if (is_string($htmlRenderer)) {
            $htmlRenderer = in_array(strtolower($htmlRenderer), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(SiteTableMap::COL_SITE_HTML_RENDERER, $htmlRenderer, $comparison);

        return $this;
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
     * @param bool|string $axys The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAxys($axys = null, ?string $comparison = null)
    {
        if (is_string($axys)) {
            $axys = in_array(strtolower($axys), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(SiteTableMap::COL_SITE_AXYS, $axys, $comparison);

        return $this;
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
     * @param bool|string $noosfere The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByNoosfere($noosfere = null, ?string $comparison = null)
    {
        if (is_string($noosfere)) {
            $noosfere = in_array(strtolower($noosfere), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(SiteTableMap::COL_SITE_NOOSFERE, $noosfere, $comparison);

        return $this;
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
     * @param bool|string $amazon The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAmazon($amazon = null, ?string $comparison = null)
    {
        if (is_string($amazon)) {
            $amazon = in_array(strtolower($amazon), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(SiteTableMap::COL_SITE_AMAZON, $amazon, $comparison);

        return $this;
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
     * @param mixed $eventId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEventId($eventId = null, ?string $comparison = null)
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

        $this->addUsingAlias(SiteTableMap::COL_SITE_EVENT_ID, $eventId, $comparison);

        return $this;
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
     * @param mixed $eventDate The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEventDate($eventDate = null, ?string $comparison = null)
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

        $this->addUsingAlias(SiteTableMap::COL_SITE_EVENT_DATE, $eventDate, $comparison);

        return $this;
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
     * @param bool|string $shop The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByShop($shop = null, ?string $comparison = null)
    {
        if (is_string($shop)) {
            $shop = in_array(strtolower($shop), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(SiteTableMap::COL_SITE_SHOP, $shop, $comparison);

        return $this;
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

        $this->addUsingAlias(SiteTableMap::COL_SITE_VPC, $vpc, $comparison);

        return $this;
    }

    /**
     * Filter the query on the site_shipping_fee column
     *
     * Example usage:
     * <code>
     * $query->filterByShippingFee('fooValue');   // WHERE site_shipping_fee = 'fooValue'
     * $query->filterByShippingFee('%fooValue%', Criteria::LIKE); // WHERE site_shipping_fee LIKE '%fooValue%'
     * $query->filterByShippingFee(['foo', 'bar']); // WHERE site_shipping_fee IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $shippingFee The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByShippingFee($shippingFee = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($shippingFee)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SiteTableMap::COL_SITE_SHIPPING_FEE, $shippingFee, $comparison);

        return $this;
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
     * @param bool|string $paymentCheque The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPaymentCheque($paymentCheque = null, ?string $comparison = null)
    {
        if (is_string($paymentCheque)) {
            $paymentCheque = in_array(strtolower($paymentCheque), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(SiteTableMap::COL_SITE_PAYMENT_CHEQUE, $paymentCheque, $comparison);

        return $this;
    }

    /**
     * Filter the query on the site_payment_paypal column
     *
     * Example usage:
     * <code>
     * $query->filterByPaymentPaypal('fooValue');   // WHERE site_payment_paypal = 'fooValue'
     * $query->filterByPaymentPaypal('%fooValue%', Criteria::LIKE); // WHERE site_payment_paypal LIKE '%fooValue%'
     * $query->filterByPaymentPaypal(['foo', 'bar']); // WHERE site_payment_paypal IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $paymentPaypal The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPaymentPaypal($paymentPaypal = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($paymentPaypal)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SiteTableMap::COL_SITE_PAYMENT_PAYPAL, $paymentPaypal, $comparison);

        return $this;
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
     * @param bool|string $paymentPayplug The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPaymentPayplug($paymentPayplug = null, ?string $comparison = null)
    {
        if (is_string($paymentPayplug)) {
            $paymentPayplug = in_array(strtolower($paymentPayplug), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(SiteTableMap::COL_SITE_PAYMENT_PAYPLUG, $paymentPayplug, $comparison);

        return $this;
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
     * @param bool|string $paymentTransfer The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPaymentTransfer($paymentTransfer = null, ?string $comparison = null)
    {
        if (is_string($paymentTransfer)) {
            $paymentTransfer = in_array(strtolower($paymentTransfer), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(SiteTableMap::COL_SITE_PAYMENT_TRANSFER, $paymentTransfer, $comparison);

        return $this;
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
     * @param bool|string $bookshop The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByBookshop($bookshop = null, ?string $comparison = null)
    {
        if (is_string($bookshop)) {
            $bookshop = in_array(strtolower($bookshop), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(SiteTableMap::COL_SITE_BOOKSHOP, $bookshop, $comparison);

        return $this;
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
     * @param mixed $bookshopId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByBookshopId($bookshopId = null, ?string $comparison = null)
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

        $this->addUsingAlias(SiteTableMap::COL_SITE_BOOKSHOP_ID, $bookshopId, $comparison);

        return $this;
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
     * @param bool|string $publisher The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPublisher($publisher = null, ?string $comparison = null)
    {
        if (is_string($publisher)) {
            $publisher = in_array(strtolower($publisher), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(SiteTableMap::COL_SITE_PUBLISHER, $publisher, $comparison);

        return $this;
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
     * @param bool|string $publisherStock The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPublisherStock($publisherStock = null, ?string $comparison = null)
    {
        if (is_string($publisherStock)) {
            $publisherStock = in_array(strtolower($publisherStock), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(SiteTableMap::COL_SITE_PUBLISHER_STOCK, $publisherStock, $comparison);

        return $this;
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
     * @param mixed $publisherId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPublisherId($publisherId = null, ?string $comparison = null)
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

        $this->addUsingAlias(SiteTableMap::COL_PUBLISHER_ID, $publisherId, $comparison);

        return $this;
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
     * @param mixed $ebookBundle The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEbookBundle($ebookBundle = null, ?string $comparison = null)
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

        $this->addUsingAlias(SiteTableMap::COL_SITE_EBOOK_BUNDLE, $ebookBundle, $comparison);

        return $this;
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
     * @param mixed $fbPageId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFbPageId($fbPageId = null, ?string $comparison = null)
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

        $this->addUsingAlias(SiteTableMap::COL_SITE_FB_PAGE_ID, $fbPageId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the site_fb_page_token column
     *
     * Example usage:
     * <code>
     * $query->filterByFbPageToken('fooValue');   // WHERE site_fb_page_token = 'fooValue'
     * $query->filterByFbPageToken('%fooValue%', Criteria::LIKE); // WHERE site_fb_page_token LIKE '%fooValue%'
     * $query->filterByFbPageToken(['foo', 'bar']); // WHERE site_fb_page_token IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $fbPageToken The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFbPageToken($fbPageToken = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($fbPageToken)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SiteTableMap::COL_SITE_FB_PAGE_TOKEN, $fbPageToken, $comparison);

        return $this;
    }

    /**
     * Filter the query on the site_analytics_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAnalyticsId('fooValue');   // WHERE site_analytics_id = 'fooValue'
     * $query->filterByAnalyticsId('%fooValue%', Criteria::LIKE); // WHERE site_analytics_id LIKE '%fooValue%'
     * $query->filterByAnalyticsId(['foo', 'bar']); // WHERE site_analytics_id IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $analyticsId The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAnalyticsId($analyticsId = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($analyticsId)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(SiteTableMap::COL_SITE_ANALYTICS_ID, $analyticsId, $comparison);

        return $this;
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
     * @param mixed $piwikId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPiwikId($piwikId = null, ?string $comparison = null)
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

        $this->addUsingAlias(SiteTableMap::COL_SITE_PIWIK_ID, $piwikId, $comparison);

        return $this;
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
     * @param mixed $sitemapUpdated The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySitemapUpdated($sitemapUpdated = null, ?string $comparison = null)
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

        $this->addUsingAlias(SiteTableMap::COL_SITE_SITEMAP_UPDATED, $sitemapUpdated, $comparison);

        return $this;
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
     * @param bool|string $monitoring The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByMonitoring($monitoring = null, ?string $comparison = null)
    {
        if (is_string($monitoring)) {
            $monitoring = in_array(strtolower($monitoring), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(SiteTableMap::COL_SITE_MONITORING, $monitoring, $comparison);

        return $this;
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

        $this->addUsingAlias(SiteTableMap::COL_SITE_CREATED, $createdAt, $comparison);

        return $this;
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

        $this->addUsingAlias(SiteTableMap::COL_SITE_UPDATED, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \Model\Alert object
     *
     * @param \Model\Alert|ObjectCollection $alert the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAlert($alert, ?string $comparison = null)
    {
        if ($alert instanceof \Model\Alert) {
            $this
                ->addUsingAlias(SiteTableMap::COL_SITE_ID, $alert->getSiteId(), $comparison);

            return $this;
        } elseif ($alert instanceof ObjectCollection) {
            $this
                ->useAlertQuery()
                ->filterByPrimaryKeys($alert->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByAlert() only accepts arguments of type \Model\Alert or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Alert relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinAlert(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Alert');

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
            $this->addJoinObject($join, 'Alert');
        }

        return $this;
    }

    /**
     * Use the Alert relation Alert object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\AlertQuery A secondary query class using the current class as primary query
     */
    public function useAlertQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinAlert($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Alert', '\Model\AlertQuery');
    }

    /**
     * Use the Alert relation Alert object
     *
     * @param callable(\Model\AlertQuery):\Model\AlertQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withAlertQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useAlertQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Alert table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\AlertQuery The inner query object of the EXISTS statement
     */
    public function useAlertExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\AlertQuery */
        $q = $this->useExistsQuery('Alert', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Alert table for a NOT EXISTS query.
     *
     * @see useAlertExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\AlertQuery The inner query object of the NOT EXISTS statement
     */
    public function useAlertNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\AlertQuery */
        $q = $this->useExistsQuery('Alert', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Alert table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\AlertQuery The inner query object of the IN statement
     */
    public function useInAlertQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\AlertQuery */
        $q = $this->useInQuery('Alert', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Alert table for a NOT IN query.
     *
     * @see useAlertInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\AlertQuery The inner query object of the NOT IN statement
     */
    public function useNotInAlertQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\AlertQuery */
        $q = $this->useInQuery('Alert', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Cart object
     *
     * @param \Model\Cart|ObjectCollection $cart the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCart($cart, ?string $comparison = null)
    {
        if ($cart instanceof \Model\Cart) {
            $this
                ->addUsingAlias(SiteTableMap::COL_SITE_ID, $cart->getSiteId(), $comparison);

            return $this;
        } elseif ($cart instanceof ObjectCollection) {
            $this
                ->useCartQuery()
                ->filterByPrimaryKeys($cart->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByCart() only accepts arguments of type \Model\Cart or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Cart relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinCart(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Cart');

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
            $this->addJoinObject($join, 'Cart');
        }

        return $this;
    }

    /**
     * Use the Cart relation Cart object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\CartQuery A secondary query class using the current class as primary query
     */
    public function useCartQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCart($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Cart', '\Model\CartQuery');
    }

    /**
     * Use the Cart relation Cart object
     *
     * @param callable(\Model\CartQuery):\Model\CartQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withCartQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useCartQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Cart table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\CartQuery The inner query object of the EXISTS statement
     */
    public function useCartExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\CartQuery */
        $q = $this->useExistsQuery('Cart', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Cart table for a NOT EXISTS query.
     *
     * @see useCartExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\CartQuery The inner query object of the NOT EXISTS statement
     */
    public function useCartNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\CartQuery */
        $q = $this->useExistsQuery('Cart', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Cart table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\CartQuery The inner query object of the IN statement
     */
    public function useInCartQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\CartQuery */
        $q = $this->useInQuery('Cart', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Cart table for a NOT IN query.
     *
     * @see useCartInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\CartQuery The inner query object of the NOT IN statement
     */
    public function useNotInCartQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\CartQuery */
        $q = $this->useInQuery('Cart', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\CrowdfundingCampaign object
     *
     * @param \Model\CrowdfundingCampaign|ObjectCollection $crowdfundingCampaign the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCrowdfundingCampaign($crowdfundingCampaign, ?string $comparison = null)
    {
        if ($crowdfundingCampaign instanceof \Model\CrowdfundingCampaign) {
            $this
                ->addUsingAlias(SiteTableMap::COL_SITE_ID, $crowdfundingCampaign->getSiteId(), $comparison);

            return $this;
        } elseif ($crowdfundingCampaign instanceof ObjectCollection) {
            $this
                ->useCrowdfundingCampaignQuery()
                ->filterByPrimaryKeys($crowdfundingCampaign->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByCrowdfundingCampaign() only accepts arguments of type \Model\CrowdfundingCampaign or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CrowdfundingCampaign relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinCrowdfundingCampaign(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CrowdfundingCampaign');

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
            $this->addJoinObject($join, 'CrowdfundingCampaign');
        }

        return $this;
    }

    /**
     * Use the CrowdfundingCampaign relation CrowdfundingCampaign object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\CrowdfundingCampaignQuery A secondary query class using the current class as primary query
     */
    public function useCrowdfundingCampaignQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCrowdfundingCampaign($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CrowdfundingCampaign', '\Model\CrowdfundingCampaignQuery');
    }

    /**
     * Use the CrowdfundingCampaign relation CrowdfundingCampaign object
     *
     * @param callable(\Model\CrowdfundingCampaignQuery):\Model\CrowdfundingCampaignQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withCrowdfundingCampaignQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useCrowdfundingCampaignQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to CrowdfundingCampaign table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\CrowdfundingCampaignQuery The inner query object of the EXISTS statement
     */
    public function useCrowdfundingCampaignExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\CrowdfundingCampaignQuery */
        $q = $this->useExistsQuery('CrowdfundingCampaign', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to CrowdfundingCampaign table for a NOT EXISTS query.
     *
     * @see useCrowdfundingCampaignExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\CrowdfundingCampaignQuery The inner query object of the NOT EXISTS statement
     */
    public function useCrowdfundingCampaignNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\CrowdfundingCampaignQuery */
        $q = $this->useExistsQuery('CrowdfundingCampaign', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to CrowdfundingCampaign table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\CrowdfundingCampaignQuery The inner query object of the IN statement
     */
    public function useInCrowdfundingCampaignQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\CrowdfundingCampaignQuery */
        $q = $this->useInQuery('CrowdfundingCampaign', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to CrowdfundingCampaign table for a NOT IN query.
     *
     * @see useCrowdfundingCampaignInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\CrowdfundingCampaignQuery The inner query object of the NOT IN statement
     */
    public function useNotInCrowdfundingCampaignQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\CrowdfundingCampaignQuery */
        $q = $this->useInQuery('CrowdfundingCampaign', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\CrowfundingReward object
     *
     * @param \Model\CrowfundingReward|ObjectCollection $crowfundingReward the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCrowfundingReward($crowfundingReward, ?string $comparison = null)
    {
        if ($crowfundingReward instanceof \Model\CrowfundingReward) {
            $this
                ->addUsingAlias(SiteTableMap::COL_SITE_ID, $crowfundingReward->getSiteId(), $comparison);

            return $this;
        } elseif ($crowfundingReward instanceof ObjectCollection) {
            $this
                ->useCrowfundingRewardQuery()
                ->filterByPrimaryKeys($crowfundingReward->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByCrowfundingReward() only accepts arguments of type \Model\CrowfundingReward or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CrowfundingReward relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinCrowfundingReward(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CrowfundingReward');

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
            $this->addJoinObject($join, 'CrowfundingReward');
        }

        return $this;
    }

    /**
     * Use the CrowfundingReward relation CrowfundingReward object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\CrowfundingRewardQuery A secondary query class using the current class as primary query
     */
    public function useCrowfundingRewardQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCrowfundingReward($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CrowfundingReward', '\Model\CrowfundingRewardQuery');
    }

    /**
     * Use the CrowfundingReward relation CrowfundingReward object
     *
     * @param callable(\Model\CrowfundingRewardQuery):\Model\CrowfundingRewardQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withCrowfundingRewardQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useCrowfundingRewardQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to CrowfundingReward table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\CrowfundingRewardQuery The inner query object of the EXISTS statement
     */
    public function useCrowfundingRewardExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\CrowfundingRewardQuery */
        $q = $this->useExistsQuery('CrowfundingReward', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to CrowfundingReward table for a NOT EXISTS query.
     *
     * @see useCrowfundingRewardExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\CrowfundingRewardQuery The inner query object of the NOT EXISTS statement
     */
    public function useCrowfundingRewardNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\CrowfundingRewardQuery */
        $q = $this->useExistsQuery('CrowfundingReward', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to CrowfundingReward table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\CrowfundingRewardQuery The inner query object of the IN statement
     */
    public function useInCrowfundingRewardQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\CrowfundingRewardQuery */
        $q = $this->useInQuery('CrowfundingReward', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to CrowfundingReward table for a NOT IN query.
     *
     * @see useCrowfundingRewardInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\CrowfundingRewardQuery The inner query object of the NOT IN statement
     */
    public function useNotInCrowfundingRewardQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\CrowfundingRewardQuery */
        $q = $this->useInQuery('CrowfundingReward', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Customer object
     *
     * @param \Model\Customer|ObjectCollection $customer the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCustomer($customer, ?string $comparison = null)
    {
        if ($customer instanceof \Model\Customer) {
            $this
                ->addUsingAlias(SiteTableMap::COL_SITE_ID, $customer->getSiteId(), $comparison);

            return $this;
        } elseif ($customer instanceof ObjectCollection) {
            $this
                ->useCustomerQuery()
                ->filterByPrimaryKeys($customer->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByCustomer() only accepts arguments of type \Model\Customer or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Customer relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinCustomer(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Customer');

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
            $this->addJoinObject($join, 'Customer');
        }

        return $this;
    }

    /**
     * Use the Customer relation Customer object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\CustomerQuery A secondary query class using the current class as primary query
     */
    public function useCustomerQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCustomer($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Customer', '\Model\CustomerQuery');
    }

    /**
     * Use the Customer relation Customer object
     *
     * @param callable(\Model\CustomerQuery):\Model\CustomerQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withCustomerQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useCustomerQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Customer table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\CustomerQuery The inner query object of the EXISTS statement
     */
    public function useCustomerExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\CustomerQuery */
        $q = $this->useExistsQuery('Customer', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Customer table for a NOT EXISTS query.
     *
     * @see useCustomerExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\CustomerQuery The inner query object of the NOT EXISTS statement
     */
    public function useCustomerNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\CustomerQuery */
        $q = $this->useExistsQuery('Customer', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Customer table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\CustomerQuery The inner query object of the IN statement
     */
    public function useInCustomerQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\CustomerQuery */
        $q = $this->useInQuery('Customer', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Customer table for a NOT IN query.
     *
     * @see useCustomerInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\CustomerQuery The inner query object of the NOT IN statement
     */
    public function useNotInCustomerQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\CustomerQuery */
        $q = $this->useInQuery('Customer', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Download object
     *
     * @param \Model\Download|ObjectCollection $download the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDownload($download, ?string $comparison = null)
    {
        if ($download instanceof \Model\Download) {
            $this
                ->addUsingAlias(SiteTableMap::COL_SITE_ID, $download->getSiteId(), $comparison);

            return $this;
        } elseif ($download instanceof ObjectCollection) {
            $this
                ->useDownloadQuery()
                ->filterByPrimaryKeys($download->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByDownload() only accepts arguments of type \Model\Download or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Download relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinDownload(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Download');

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
            $this->addJoinObject($join, 'Download');
        }

        return $this;
    }

    /**
     * Use the Download relation Download object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\DownloadQuery A secondary query class using the current class as primary query
     */
    public function useDownloadQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDownload($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Download', '\Model\DownloadQuery');
    }

    /**
     * Use the Download relation Download object
     *
     * @param callable(\Model\DownloadQuery):\Model\DownloadQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withDownloadQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useDownloadQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Download table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\DownloadQuery The inner query object of the EXISTS statement
     */
    public function useDownloadExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\DownloadQuery */
        $q = $this->useExistsQuery('Download', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Download table for a NOT EXISTS query.
     *
     * @see useDownloadExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\DownloadQuery The inner query object of the NOT EXISTS statement
     */
    public function useDownloadNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\DownloadQuery */
        $q = $this->useExistsQuery('Download', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Download table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\DownloadQuery The inner query object of the IN statement
     */
    public function useInDownloadQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\DownloadQuery */
        $q = $this->useInQuery('Download', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Download table for a NOT IN query.
     *
     * @see useDownloadInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\DownloadQuery The inner query object of the NOT IN statement
     */
    public function useNotInDownloadQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\DownloadQuery */
        $q = $this->useInQuery('Download', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\File object
     *
     * @param \Model\File|ObjectCollection $file the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFile($file, ?string $comparison = null)
    {
        if ($file instanceof \Model\File) {
            $this
                ->addUsingAlias(SiteTableMap::COL_SITE_ID, $file->getSiteId(), $comparison);

            return $this;
        } elseif ($file instanceof ObjectCollection) {
            $this
                ->useFileQuery()
                ->filterByPrimaryKeys($file->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByFile() only accepts arguments of type \Model\File or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the File relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinFile(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('File');

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
            $this->addJoinObject($join, 'File');
        }

        return $this;
    }

    /**
     * Use the File relation File object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\FileQuery A secondary query class using the current class as primary query
     */
    public function useFileQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinFile($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'File', '\Model\FileQuery');
    }

    /**
     * Use the File relation File object
     *
     * @param callable(\Model\FileQuery):\Model\FileQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withFileQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useFileQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to File table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\FileQuery The inner query object of the EXISTS statement
     */
    public function useFileExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\FileQuery */
        $q = $this->useExistsQuery('File', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to File table for a NOT EXISTS query.
     *
     * @see useFileExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\FileQuery The inner query object of the NOT EXISTS statement
     */
    public function useFileNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\FileQuery */
        $q = $this->useExistsQuery('File', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to File table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\FileQuery The inner query object of the IN statement
     */
    public function useInFileQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\FileQuery */
        $q = $this->useInQuery('File', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to File table for a NOT IN query.
     *
     * @see useFileInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\FileQuery The inner query object of the NOT IN statement
     */
    public function useNotInFileQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\FileQuery */
        $q = $this->useInQuery('File', $modelAlias, $queryClass, 'NOT IN');
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
                ->addUsingAlias(SiteTableMap::COL_SITE_ID, $image->getSiteId(), $comparison);

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
     * Filter the query by a related \Model\Invitation object
     *
     * @param \Model\Invitation|ObjectCollection $invitation the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByInvitation($invitation, ?string $comparison = null)
    {
        if ($invitation instanceof \Model\Invitation) {
            $this
                ->addUsingAlias(SiteTableMap::COL_SITE_ID, $invitation->getSiteId(), $comparison);

            return $this;
        } elseif ($invitation instanceof ObjectCollection) {
            $this
                ->useInvitationQuery()
                ->filterByPrimaryKeys($invitation->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByInvitation() only accepts arguments of type \Model\Invitation or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Invitation relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinInvitation(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Invitation');

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
            $this->addJoinObject($join, 'Invitation');
        }

        return $this;
    }

    /**
     * Use the Invitation relation Invitation object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\InvitationQuery A secondary query class using the current class as primary query
     */
    public function useInvitationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInvitation($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Invitation', '\Model\InvitationQuery');
    }

    /**
     * Use the Invitation relation Invitation object
     *
     * @param callable(\Model\InvitationQuery):\Model\InvitationQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withInvitationQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useInvitationQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Invitation table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\InvitationQuery The inner query object of the EXISTS statement
     */
    public function useInvitationExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\InvitationQuery */
        $q = $this->useExistsQuery('Invitation', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Invitation table for a NOT EXISTS query.
     *
     * @see useInvitationExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\InvitationQuery The inner query object of the NOT EXISTS statement
     */
    public function useInvitationNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\InvitationQuery */
        $q = $this->useExistsQuery('Invitation', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Invitation table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\InvitationQuery The inner query object of the IN statement
     */
    public function useInInvitationQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\InvitationQuery */
        $q = $this->useInQuery('Invitation', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Invitation table for a NOT IN query.
     *
     * @see useInvitationInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\InvitationQuery The inner query object of the NOT IN statement
     */
    public function useNotInInvitationQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\InvitationQuery */
        $q = $this->useInQuery('Invitation', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\StockItemList object
     *
     * @param \Model\StockItemList|ObjectCollection $stockItemList the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByStockItemList($stockItemList, ?string $comparison = null)
    {
        if ($stockItemList instanceof \Model\StockItemList) {
            $this
                ->addUsingAlias(SiteTableMap::COL_SITE_ID, $stockItemList->getSiteId(), $comparison);

            return $this;
        } elseif ($stockItemList instanceof ObjectCollection) {
            $this
                ->useStockItemListQuery()
                ->filterByPrimaryKeys($stockItemList->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByStockItemList() only accepts arguments of type \Model\StockItemList or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the StockItemList relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinStockItemList(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('StockItemList');

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
            $this->addJoinObject($join, 'StockItemList');
        }

        return $this;
    }

    /**
     * Use the StockItemList relation StockItemList object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\StockItemListQuery A secondary query class using the current class as primary query
     */
    public function useStockItemListQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinStockItemList($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'StockItemList', '\Model\StockItemListQuery');
    }

    /**
     * Use the StockItemList relation StockItemList object
     *
     * @param callable(\Model\StockItemListQuery):\Model\StockItemListQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withStockItemListQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useStockItemListQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to StockItemList table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\StockItemListQuery The inner query object of the EXISTS statement
     */
    public function useStockItemListExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\StockItemListQuery */
        $q = $this->useExistsQuery('StockItemList', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to StockItemList table for a NOT EXISTS query.
     *
     * @see useStockItemListExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\StockItemListQuery The inner query object of the NOT EXISTS statement
     */
    public function useStockItemListNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\StockItemListQuery */
        $q = $this->useExistsQuery('StockItemList', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to StockItemList table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\StockItemListQuery The inner query object of the IN statement
     */
    public function useInStockItemListQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\StockItemListQuery */
        $q = $this->useInQuery('StockItemList', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to StockItemList table for a NOT IN query.
     *
     * @see useStockItemListInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\StockItemListQuery The inner query object of the NOT IN statement
     */
    public function useNotInStockItemListQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\StockItemListQuery */
        $q = $this->useInQuery('StockItemList', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Option object
     *
     * @param \Model\Option|ObjectCollection $option the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByOption($option, ?string $comparison = null)
    {
        if ($option instanceof \Model\Option) {
            $this
                ->addUsingAlias(SiteTableMap::COL_SITE_ID, $option->getSiteId(), $comparison);

            return $this;
        } elseif ($option instanceof ObjectCollection) {
            $this
                ->useOptionQuery()
                ->filterByPrimaryKeys($option->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByOption() only accepts arguments of type \Model\Option or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Option relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinOption(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Option');

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
            $this->addJoinObject($join, 'Option');
        }

        return $this;
    }

    /**
     * Use the Option relation Option object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\OptionQuery A secondary query class using the current class as primary query
     */
    public function useOptionQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinOption($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Option', '\Model\OptionQuery');
    }

    /**
     * Use the Option relation Option object
     *
     * @param callable(\Model\OptionQuery):\Model\OptionQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withOptionQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useOptionQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Option table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\OptionQuery The inner query object of the EXISTS statement
     */
    public function useOptionExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\OptionQuery */
        $q = $this->useExistsQuery('Option', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Option table for a NOT EXISTS query.
     *
     * @see useOptionExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\OptionQuery The inner query object of the NOT EXISTS statement
     */
    public function useOptionNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\OptionQuery */
        $q = $this->useExistsQuery('Option', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Option table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\OptionQuery The inner query object of the IN statement
     */
    public function useInOptionQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\OptionQuery */
        $q = $this->useInQuery('Option', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Option table for a NOT IN query.
     *
     * @see useOptionInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\OptionQuery The inner query object of the NOT IN statement
     */
    public function useNotInOptionQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\OptionQuery */
        $q = $this->useInQuery('Option', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Order object
     *
     * @param \Model\Order|ObjectCollection $order the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByOrder($order, ?string $comparison = null)
    {
        if ($order instanceof \Model\Order) {
            $this
                ->addUsingAlias(SiteTableMap::COL_SITE_ID, $order->getSiteId(), $comparison);

            return $this;
        } elseif ($order instanceof ObjectCollection) {
            $this
                ->useOrderQuery()
                ->filterByPrimaryKeys($order->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByOrder() only accepts arguments of type \Model\Order or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Order relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinOrder(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Order');

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
            $this->addJoinObject($join, 'Order');
        }

        return $this;
    }

    /**
     * Use the Order relation Order object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\OrderQuery A secondary query class using the current class as primary query
     */
    public function useOrderQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinOrder($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Order', '\Model\OrderQuery');
    }

    /**
     * Use the Order relation Order object
     *
     * @param callable(\Model\OrderQuery):\Model\OrderQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withOrderQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useOrderQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Order table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\OrderQuery The inner query object of the EXISTS statement
     */
    public function useOrderExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\OrderQuery */
        $q = $this->useExistsQuery('Order', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Order table for a NOT EXISTS query.
     *
     * @see useOrderExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\OrderQuery The inner query object of the NOT EXISTS statement
     */
    public function useOrderNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\OrderQuery */
        $q = $this->useExistsQuery('Order', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Order table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\OrderQuery The inner query object of the IN statement
     */
    public function useInOrderQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\OrderQuery */
        $q = $this->useInQuery('Order', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Order table for a NOT IN query.
     *
     * @see useOrderInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\OrderQuery The inner query object of the NOT IN statement
     */
    public function useNotInOrderQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\OrderQuery */
        $q = $this->useInQuery('Order', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Page object
     *
     * @param \Model\Page|ObjectCollection $page the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPage($page, ?string $comparison = null)
    {
        if ($page instanceof \Model\Page) {
            $this
                ->addUsingAlias(SiteTableMap::COL_SITE_ID, $page->getSiteId(), $comparison);

            return $this;
        } elseif ($page instanceof ObjectCollection) {
            $this
                ->usePageQuery()
                ->filterByPrimaryKeys($page->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByPage() only accepts arguments of type \Model\Page or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Page relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinPage(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Page');

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
            $this->addJoinObject($join, 'Page');
        }

        return $this;
    }

    /**
     * Use the Page relation Page object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\PageQuery A secondary query class using the current class as primary query
     */
    public function usePageQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPage($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Page', '\Model\PageQuery');
    }

    /**
     * Use the Page relation Page object
     *
     * @param callable(\Model\PageQuery):\Model\PageQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withPageQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->usePageQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Page table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\PageQuery The inner query object of the EXISTS statement
     */
    public function usePageExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\PageQuery */
        $q = $this->useExistsQuery('Page', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Page table for a NOT EXISTS query.
     *
     * @see usePageExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\PageQuery The inner query object of the NOT EXISTS statement
     */
    public function usePageNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\PageQuery */
        $q = $this->useExistsQuery('Page', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Page table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\PageQuery The inner query object of the IN statement
     */
    public function useInPageQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\PageQuery */
        $q = $this->useInQuery('Page', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Page table for a NOT IN query.
     *
     * @see usePageInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\PageQuery The inner query object of the NOT IN statement
     */
    public function useNotInPageQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\PageQuery */
        $q = $this->useInQuery('Page', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Payment object
     *
     * @param \Model\Payment|ObjectCollection $payment the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPayment($payment, ?string $comparison = null)
    {
        if ($payment instanceof \Model\Payment) {
            $this
                ->addUsingAlias(SiteTableMap::COL_SITE_ID, $payment->getSiteId(), $comparison);

            return $this;
        } elseif ($payment instanceof ObjectCollection) {
            $this
                ->usePaymentQuery()
                ->filterByPrimaryKeys($payment->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByPayment() only accepts arguments of type \Model\Payment or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Payment relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinPayment(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Payment');

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
            $this->addJoinObject($join, 'Payment');
        }

        return $this;
    }

    /**
     * Use the Payment relation Payment object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\PaymentQuery A secondary query class using the current class as primary query
     */
    public function usePaymentQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPayment($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Payment', '\Model\PaymentQuery');
    }

    /**
     * Use the Payment relation Payment object
     *
     * @param callable(\Model\PaymentQuery):\Model\PaymentQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withPaymentQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->usePaymentQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Payment table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\PaymentQuery The inner query object of the EXISTS statement
     */
    public function usePaymentExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\PaymentQuery */
        $q = $this->useExistsQuery('Payment', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Payment table for a NOT EXISTS query.
     *
     * @see usePaymentExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\PaymentQuery The inner query object of the NOT EXISTS statement
     */
    public function usePaymentNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\PaymentQuery */
        $q = $this->useExistsQuery('Payment', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Payment table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\PaymentQuery The inner query object of the IN statement
     */
    public function useInPaymentQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\PaymentQuery */
        $q = $this->useInQuery('Payment', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Payment table for a NOT IN query.
     *
     * @see usePaymentInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\PaymentQuery The inner query object of the NOT IN statement
     */
    public function useNotInPaymentQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\PaymentQuery */
        $q = $this->useInQuery('Payment', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Post object
     *
     * @param \Model\Post|ObjectCollection $post the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPost($post, ?string $comparison = null)
    {
        if ($post instanceof \Model\Post) {
            $this
                ->addUsingAlias(SiteTableMap::COL_SITE_ID, $post->getSiteId(), $comparison);

            return $this;
        } elseif ($post instanceof ObjectCollection) {
            $this
                ->usePostQuery()
                ->filterByPrimaryKeys($post->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByPost() only accepts arguments of type \Model\Post or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Post relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinPost(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Post');

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
            $this->addJoinObject($join, 'Post');
        }

        return $this;
    }

    /**
     * Use the Post relation Post object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\PostQuery A secondary query class using the current class as primary query
     */
    public function usePostQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPost($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Post', '\Model\PostQuery');
    }

    /**
     * Use the Post relation Post object
     *
     * @param callable(\Model\PostQuery):\Model\PostQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withPostQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->usePostQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Post table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\PostQuery The inner query object of the EXISTS statement
     */
    public function usePostExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\PostQuery */
        $q = $this->useExistsQuery('Post', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Post table for a NOT EXISTS query.
     *
     * @see usePostExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\PostQuery The inner query object of the NOT EXISTS statement
     */
    public function usePostNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\PostQuery */
        $q = $this->useExistsQuery('Post', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Post table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\PostQuery The inner query object of the IN statement
     */
    public function useInPostQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\PostQuery */
        $q = $this->useInQuery('Post', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Post table for a NOT IN query.
     *
     * @see usePostInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\PostQuery The inner query object of the NOT IN statement
     */
    public function useNotInPostQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\PostQuery */
        $q = $this->useInQuery('Post', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\ArticleCategory object
     *
     * @param \Model\ArticleCategory|ObjectCollection $articleCategory the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByArticleCategory($articleCategory, ?string $comparison = null)
    {
        if ($articleCategory instanceof \Model\ArticleCategory) {
            $this
                ->addUsingAlias(SiteTableMap::COL_SITE_ID, $articleCategory->getSiteId(), $comparison);

            return $this;
        } elseif ($articleCategory instanceof ObjectCollection) {
            $this
                ->useArticleCategoryQuery()
                ->filterByPrimaryKeys($articleCategory->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByArticleCategory() only accepts arguments of type \Model\ArticleCategory or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ArticleCategory relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinArticleCategory(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ArticleCategory');

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
            $this->addJoinObject($join, 'ArticleCategory');
        }

        return $this;
    }

    /**
     * Use the ArticleCategory relation ArticleCategory object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\ArticleCategoryQuery A secondary query class using the current class as primary query
     */
    public function useArticleCategoryQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinArticleCategory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ArticleCategory', '\Model\ArticleCategoryQuery');
    }

    /**
     * Use the ArticleCategory relation ArticleCategory object
     *
     * @param callable(\Model\ArticleCategoryQuery):\Model\ArticleCategoryQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withArticleCategoryQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useArticleCategoryQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to ArticleCategory table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\ArticleCategoryQuery The inner query object of the EXISTS statement
     */
    public function useArticleCategoryExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\ArticleCategoryQuery */
        $q = $this->useExistsQuery('ArticleCategory', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to ArticleCategory table for a NOT EXISTS query.
     *
     * @see useArticleCategoryExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\ArticleCategoryQuery The inner query object of the NOT EXISTS statement
     */
    public function useArticleCategoryNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\ArticleCategoryQuery */
        $q = $this->useExistsQuery('ArticleCategory', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to ArticleCategory table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\ArticleCategoryQuery The inner query object of the IN statement
     */
    public function useInArticleCategoryQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\ArticleCategoryQuery */
        $q = $this->useInQuery('ArticleCategory', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to ArticleCategory table for a NOT IN query.
     *
     * @see useArticleCategoryInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\ArticleCategoryQuery The inner query object of the NOT IN statement
     */
    public function useNotInArticleCategoryQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\ArticleCategoryQuery */
        $q = $this->useInQuery('ArticleCategory', $modelAlias, $queryClass, 'NOT IN');
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
                ->addUsingAlias(SiteTableMap::COL_SITE_ID, $right->getSiteId(), $comparison);

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
     * Filter the query by a related \Model\Session object
     *
     * @param \Model\Session|ObjectCollection $session the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySession($session, ?string $comparison = null)
    {
        if ($session instanceof \Model\Session) {
            $this
                ->addUsingAlias(SiteTableMap::COL_SITE_ID, $session->getSiteId(), $comparison);

            return $this;
        } elseif ($session instanceof ObjectCollection) {
            $this
                ->useSessionQuery()
                ->filterByPrimaryKeys($session->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterBySession() only accepts arguments of type \Model\Session or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Session relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinSession(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Session');

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
            $this->addJoinObject($join, 'Session');
        }

        return $this;
    }

    /**
     * Use the Session relation Session object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\SessionQuery A secondary query class using the current class as primary query
     */
    public function useSessionQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSession($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Session', '\Model\SessionQuery');
    }

    /**
     * Use the Session relation Session object
     *
     * @param callable(\Model\SessionQuery):\Model\SessionQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withSessionQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useSessionQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Session table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\SessionQuery The inner query object of the EXISTS statement
     */
    public function useSessionExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\SessionQuery */
        $q = $this->useExistsQuery('Session', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Session table for a NOT EXISTS query.
     *
     * @see useSessionExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\SessionQuery The inner query object of the NOT EXISTS statement
     */
    public function useSessionNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\SessionQuery */
        $q = $this->useExistsQuery('Session', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Session table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\SessionQuery The inner query object of the IN statement
     */
    public function useInSessionQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\SessionQuery */
        $q = $this->useInQuery('Session', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Session table for a NOT IN query.
     *
     * @see useSessionInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\SessionQuery The inner query object of the NOT IN statement
     */
    public function useNotInSessionQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\SessionQuery */
        $q = $this->useInQuery('Session', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\SpecialOffer object
     *
     * @param \Model\SpecialOffer|ObjectCollection $specialOffer the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySpecialOffer($specialOffer, ?string $comparison = null)
    {
        if ($specialOffer instanceof \Model\SpecialOffer) {
            $this
                ->addUsingAlias(SiteTableMap::COL_SITE_ID, $specialOffer->getSiteId(), $comparison);

            return $this;
        } elseif ($specialOffer instanceof ObjectCollection) {
            $this
                ->useSpecialOfferQuery()
                ->filterByPrimaryKeys($specialOffer->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterBySpecialOffer() only accepts arguments of type \Model\SpecialOffer or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SpecialOffer relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinSpecialOffer(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SpecialOffer');

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
            $this->addJoinObject($join, 'SpecialOffer');
        }

        return $this;
    }

    /**
     * Use the SpecialOffer relation SpecialOffer object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\SpecialOfferQuery A secondary query class using the current class as primary query
     */
    public function useSpecialOfferQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSpecialOffer($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SpecialOffer', '\Model\SpecialOfferQuery');
    }

    /**
     * Use the SpecialOffer relation SpecialOffer object
     *
     * @param callable(\Model\SpecialOfferQuery):\Model\SpecialOfferQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withSpecialOfferQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useSpecialOfferQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to SpecialOffer table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\SpecialOfferQuery The inner query object of the EXISTS statement
     */
    public function useSpecialOfferExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\SpecialOfferQuery */
        $q = $this->useExistsQuery('SpecialOffer', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to SpecialOffer table for a NOT EXISTS query.
     *
     * @see useSpecialOfferExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\SpecialOfferQuery The inner query object of the NOT EXISTS statement
     */
    public function useSpecialOfferNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\SpecialOfferQuery */
        $q = $this->useExistsQuery('SpecialOffer', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to SpecialOffer table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\SpecialOfferQuery The inner query object of the IN statement
     */
    public function useInSpecialOfferQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\SpecialOfferQuery */
        $q = $this->useInQuery('SpecialOffer', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to SpecialOffer table for a NOT IN query.
     *
     * @see useSpecialOfferInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\SpecialOfferQuery The inner query object of the NOT IN statement
     */
    public function useNotInSpecialOfferQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\SpecialOfferQuery */
        $q = $this->useInQuery('SpecialOffer', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Stock object
     *
     * @param \Model\Stock|ObjectCollection $stock the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByStock($stock, ?string $comparison = null)
    {
        if ($stock instanceof \Model\Stock) {
            $this
                ->addUsingAlias(SiteTableMap::COL_SITE_ID, $stock->getSiteId(), $comparison);

            return $this;
        } elseif ($stock instanceof ObjectCollection) {
            $this
                ->useStockQuery()
                ->filterByPrimaryKeys($stock->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByStock() only accepts arguments of type \Model\Stock or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Stock relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinStock(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Stock');

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
            $this->addJoinObject($join, 'Stock');
        }

        return $this;
    }

    /**
     * Use the Stock relation Stock object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\StockQuery A secondary query class using the current class as primary query
     */
    public function useStockQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinStock($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Stock', '\Model\StockQuery');
    }

    /**
     * Use the Stock relation Stock object
     *
     * @param callable(\Model\StockQuery):\Model\StockQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withStockQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useStockQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Stock table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\StockQuery The inner query object of the EXISTS statement
     */
    public function useStockExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\StockQuery */
        $q = $this->useExistsQuery('Stock', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Stock table for a NOT EXISTS query.
     *
     * @see useStockExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\StockQuery The inner query object of the NOT EXISTS statement
     */
    public function useStockNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\StockQuery */
        $q = $this->useExistsQuery('Stock', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Stock table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\StockQuery The inner query object of the IN statement
     */
    public function useInStockQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\StockQuery */
        $q = $this->useInQuery('Stock', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Stock table for a NOT IN query.
     *
     * @see useStockInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\StockQuery The inner query object of the NOT IN statement
     */
    public function useNotInStockQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\StockQuery */
        $q = $this->useInQuery('Stock', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Subscription object
     *
     * @param \Model\Subscription|ObjectCollection $subscription the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySubscription($subscription, ?string $comparison = null)
    {
        if ($subscription instanceof \Model\Subscription) {
            $this
                ->addUsingAlias(SiteTableMap::COL_SITE_ID, $subscription->getSiteId(), $comparison);

            return $this;
        } elseif ($subscription instanceof ObjectCollection) {
            $this
                ->useSubscriptionQuery()
                ->filterByPrimaryKeys($subscription->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterBySubscription() only accepts arguments of type \Model\Subscription or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Subscription relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinSubscription(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Subscription');

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
            $this->addJoinObject($join, 'Subscription');
        }

        return $this;
    }

    /**
     * Use the Subscription relation Subscription object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\SubscriptionQuery A secondary query class using the current class as primary query
     */
    public function useSubscriptionQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSubscription($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Subscription', '\Model\SubscriptionQuery');
    }

    /**
     * Use the Subscription relation Subscription object
     *
     * @param callable(\Model\SubscriptionQuery):\Model\SubscriptionQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withSubscriptionQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useSubscriptionQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Subscription table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\SubscriptionQuery The inner query object of the EXISTS statement
     */
    public function useSubscriptionExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\SubscriptionQuery */
        $q = $this->useExistsQuery('Subscription', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Subscription table for a NOT EXISTS query.
     *
     * @see useSubscriptionExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\SubscriptionQuery The inner query object of the NOT EXISTS statement
     */
    public function useSubscriptionNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\SubscriptionQuery */
        $q = $this->useExistsQuery('Subscription', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Subscription table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\SubscriptionQuery The inner query object of the IN statement
     */
    public function useInSubscriptionQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\SubscriptionQuery */
        $q = $this->useInQuery('Subscription', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Subscription table for a NOT IN query.
     *
     * @see useSubscriptionInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\SubscriptionQuery The inner query object of the NOT IN statement
     */
    public function useNotInSubscriptionQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\SubscriptionQuery */
        $q = $this->useInQuery('Subscription', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\User object
     *
     * @param \Model\User|ObjectCollection $user the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUser($user, ?string $comparison = null)
    {
        if ($user instanceof \Model\User) {
            $this
                ->addUsingAlias(SiteTableMap::COL_SITE_ID, $user->getSiteId(), $comparison);

            return $this;
        } elseif ($user instanceof ObjectCollection) {
            $this
                ->useUserQuery()
                ->filterByPrimaryKeys($user->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \Model\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinUser(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

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
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\Model\UserQuery');
    }

    /**
     * Use the User relation User object
     *
     * @param callable(\Model\UserQuery):\Model\UserQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withUserQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useUserQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to User table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\UserQuery The inner query object of the EXISTS statement
     */
    public function useUserExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\UserQuery */
        $q = $this->useExistsQuery('User', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to User table for a NOT EXISTS query.
     *
     * @see useUserExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\UserQuery The inner query object of the NOT EXISTS statement
     */
    public function useUserNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\UserQuery */
        $q = $this->useExistsQuery('User', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to User table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\UserQuery The inner query object of the IN statement
     */
    public function useInUserQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\UserQuery */
        $q = $this->useInQuery('User', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to User table for a NOT IN query.
     *
     * @see useUserInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\UserQuery The inner query object of the NOT IN statement
     */
    public function useNotInUserQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\UserQuery */
        $q = $this->useInQuery('User', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\AuthenticationMethod object
     *
     * @param \Model\AuthenticationMethod|ObjectCollection $authenticationMethod the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAuthenticationMethod($authenticationMethod, ?string $comparison = null)
    {
        if ($authenticationMethod instanceof \Model\AuthenticationMethod) {
            $this
                ->addUsingAlias(SiteTableMap::COL_SITE_ID, $authenticationMethod->getSiteId(), $comparison);

            return $this;
        } elseif ($authenticationMethod instanceof ObjectCollection) {
            $this
                ->useAuthenticationMethodQuery()
                ->filterByPrimaryKeys($authenticationMethod->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByAuthenticationMethod() only accepts arguments of type \Model\AuthenticationMethod or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AuthenticationMethod relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinAuthenticationMethod(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AuthenticationMethod');

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
            $this->addJoinObject($join, 'AuthenticationMethod');
        }

        return $this;
    }

    /**
     * Use the AuthenticationMethod relation AuthenticationMethod object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\AuthenticationMethodQuery A secondary query class using the current class as primary query
     */
    public function useAuthenticationMethodQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAuthenticationMethod($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AuthenticationMethod', '\Model\AuthenticationMethodQuery');
    }

    /**
     * Use the AuthenticationMethod relation AuthenticationMethod object
     *
     * @param callable(\Model\AuthenticationMethodQuery):\Model\AuthenticationMethodQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withAuthenticationMethodQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useAuthenticationMethodQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to AuthenticationMethod table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\AuthenticationMethodQuery The inner query object of the EXISTS statement
     */
    public function useAuthenticationMethodExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\AuthenticationMethodQuery */
        $q = $this->useExistsQuery('AuthenticationMethod', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to AuthenticationMethod table for a NOT EXISTS query.
     *
     * @see useAuthenticationMethodExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\AuthenticationMethodQuery The inner query object of the NOT EXISTS statement
     */
    public function useAuthenticationMethodNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\AuthenticationMethodQuery */
        $q = $this->useExistsQuery('AuthenticationMethod', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to AuthenticationMethod table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\AuthenticationMethodQuery The inner query object of the IN statement
     */
    public function useInAuthenticationMethodQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\AuthenticationMethodQuery */
        $q = $this->useInQuery('AuthenticationMethod', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to AuthenticationMethod table for a NOT IN query.
     *
     * @see useAuthenticationMethodInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\AuthenticationMethodQuery The inner query object of the NOT IN statement
     */
    public function useNotInAuthenticationMethodQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\AuthenticationMethodQuery */
        $q = $this->useInQuery('AuthenticationMethod', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Vote object
     *
     * @param \Model\Vote|ObjectCollection $vote the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByVote($vote, ?string $comparison = null)
    {
        if ($vote instanceof \Model\Vote) {
            $this
                ->addUsingAlias(SiteTableMap::COL_SITE_ID, $vote->getSiteId(), $comparison);

            return $this;
        } elseif ($vote instanceof ObjectCollection) {
            $this
                ->useVoteQuery()
                ->filterByPrimaryKeys($vote->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByVote() only accepts arguments of type \Model\Vote or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Vote relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinVote(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Vote');

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
            $this->addJoinObject($join, 'Vote');
        }

        return $this;
    }

    /**
     * Use the Vote relation Vote object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\VoteQuery A secondary query class using the current class as primary query
     */
    public function useVoteQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinVote($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Vote', '\Model\VoteQuery');
    }

    /**
     * Use the Vote relation Vote object
     *
     * @param callable(\Model\VoteQuery):\Model\VoteQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withVoteQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useVoteQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Vote table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\VoteQuery The inner query object of the EXISTS statement
     */
    public function useVoteExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\VoteQuery */
        $q = $this->useExistsQuery('Vote', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Vote table for a NOT EXISTS query.
     *
     * @see useVoteExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\VoteQuery The inner query object of the NOT EXISTS statement
     */
    public function useVoteNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\VoteQuery */
        $q = $this->useExistsQuery('Vote', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Vote table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\VoteQuery The inner query object of the IN statement
     */
    public function useInVoteQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\VoteQuery */
        $q = $this->useInQuery('Vote', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Vote table for a NOT IN query.
     *
     * @see useVoteInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\VoteQuery The inner query object of the NOT IN statement
     */
    public function useNotInVoteQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\VoteQuery */
        $q = $this->useInQuery('Vote', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Wishlist object
     *
     * @param \Model\Wishlist|ObjectCollection $wishlist the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByWishlist($wishlist, ?string $comparison = null)
    {
        if ($wishlist instanceof \Model\Wishlist) {
            $this
                ->addUsingAlias(SiteTableMap::COL_SITE_ID, $wishlist->getSiteId(), $comparison);

            return $this;
        } elseif ($wishlist instanceof ObjectCollection) {
            $this
                ->useWishlistQuery()
                ->filterByPrimaryKeys($wishlist->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByWishlist() only accepts arguments of type \Model\Wishlist or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Wishlist relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinWishlist(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Wishlist');

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
            $this->addJoinObject($join, 'Wishlist');
        }

        return $this;
    }

    /**
     * Use the Wishlist relation Wishlist object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\WishlistQuery A secondary query class using the current class as primary query
     */
    public function useWishlistQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinWishlist($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Wishlist', '\Model\WishlistQuery');
    }

    /**
     * Use the Wishlist relation Wishlist object
     *
     * @param callable(\Model\WishlistQuery):\Model\WishlistQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withWishlistQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useWishlistQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Wishlist table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\WishlistQuery The inner query object of the EXISTS statement
     */
    public function useWishlistExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\WishlistQuery */
        $q = $this->useExistsQuery('Wishlist', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Wishlist table for a NOT EXISTS query.
     *
     * @see useWishlistExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\WishlistQuery The inner query object of the NOT EXISTS statement
     */
    public function useWishlistNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\WishlistQuery */
        $q = $this->useExistsQuery('Wishlist', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Wishlist table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\WishlistQuery The inner query object of the IN statement
     */
    public function useInWishlistQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\WishlistQuery */
        $q = $this->useInQuery('Wishlist', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Wishlist table for a NOT IN query.
     *
     * @see useWishlistInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\WishlistQuery The inner query object of the NOT IN statement
     */
    public function useNotInWishlistQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\WishlistQuery */
        $q = $this->useInQuery('Wishlist', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Exclude object from result
     *
     * @param ChildSite $site Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
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
    public function doDeleteAll(?ConnectionInterface $con = null): int
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
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(?ConnectionInterface $con = null): int
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
     * @param int $nbDays Maximum age of the latest update in days
     *
     * @return $this The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        $this->addUsingAlias(SiteTableMap::COL_SITE_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(SiteTableMap::COL_SITE_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(SiteTableMap::COL_SITE_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(SiteTableMap::COL_SITE_CREATED);

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
        $this->addUsingAlias(SiteTableMap::COL_SITE_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(SiteTableMap::COL_SITE_CREATED);

        return $this;
    }

}
