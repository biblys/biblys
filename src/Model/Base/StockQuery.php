<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Stock as ChildStock;
use Model\StockQuery as ChildStockQuery;
use Model\Map\StockTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'stock' table.
 *
 *
 *
 * @method     ChildStockQuery orderById($order = Criteria::ASC) Order by the stock_id column
 * @method     ChildStockQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildStockQuery orderByArticleId($order = Criteria::ASC) Order by the article_id column
 * @method     ChildStockQuery orderByCampaignId($order = Criteria::ASC) Order by the campaign_id column
 * @method     ChildStockQuery orderByRewardId($order = Criteria::ASC) Order by the reward_id column
 * @method     ChildStockQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildStockQuery orderByCustomerId($order = Criteria::ASC) Order by the customer_id column
 * @method     ChildStockQuery orderByWishId($order = Criteria::ASC) Order by the wish_id column
 * @method     ChildStockQuery orderByCartId($order = Criteria::ASC) Order by the cart_id column
 * @method     ChildStockQuery orderByOrderId($order = Criteria::ASC) Order by the order_id column
 * @method     ChildStockQuery orderByCouponId($order = Criteria::ASC) Order by the coupon_id column
 * @method     ChildStockQuery orderByShop($order = Criteria::ASC) Order by the stock_shop column
 * @method     ChildStockQuery orderByInvoice($order = Criteria::ASC) Order by the stock_invoice column
 * @method     ChildStockQuery orderByDepot($order = Criteria::ASC) Order by the stock_depot column
 * @method     ChildStockQuery orderByStockage($order = Criteria::ASC) Order by the stock_stockage column
 * @method     ChildStockQuery orderByCondition($order = Criteria::ASC) Order by the stock_condition column
 * @method     ChildStockQuery orderByConditionDetails($order = Criteria::ASC) Order by the stock_condition_details column
 * @method     ChildStockQuery orderByPurchasePrice($order = Criteria::ASC) Order by the stock_purchase_price column
 * @method     ChildStockQuery orderBySellingPrice($order = Criteria::ASC) Order by the stock_selling_price column
 * @method     ChildStockQuery orderBySellingPrice2($order = Criteria::ASC) Order by the stock_selling_price2 column
 * @method     ChildStockQuery orderBySellingPriceSaved($order = Criteria::ASC) Order by the stock_selling_price_saved column
 * @method     ChildStockQuery orderBySellingPriceHt($order = Criteria::ASC) Order by the stock_selling_price_ht column
 * @method     ChildStockQuery orderBySellingPriceTva($order = Criteria::ASC) Order by the stock_selling_price_tva column
 * @method     ChildStockQuery orderByTvaRate($order = Criteria::ASC) Order by the stock_tva_rate column
 * @method     ChildStockQuery orderByWeight($order = Criteria::ASC) Order by the stock_weight column
 * @method     ChildStockQuery orderByPubYear($order = Criteria::ASC) Order by the stock_pub_year column
 * @method     ChildStockQuery orderByAllowPredownload($order = Criteria::ASC) Order by the stock_allow_predownload column
 * @method     ChildStockQuery orderByPhotoVersion($order = Criteria::ASC) Order by the stock_photo_version column
 * @method     ChildStockQuery orderByPurchaseDate($order = Criteria::ASC) Order by the stock_purchase_date column
 * @method     ChildStockQuery orderByOnsaleDate($order = Criteria::ASC) Order by the stock_onsale_date column
 * @method     ChildStockQuery orderByCartDate($order = Criteria::ASC) Order by the stock_cart_date column
 * @method     ChildStockQuery orderBySellingDate($order = Criteria::ASC) Order by the stock_selling_date column
 * @method     ChildStockQuery orderByReturnDate($order = Criteria::ASC) Order by the stock_return_date column
 * @method     ChildStockQuery orderByLostDate($order = Criteria::ASC) Order by the stock_lost_date column
 * @method     ChildStockQuery orderByMediaOk($order = Criteria::ASC) Order by the stock_media_ok column
 * @method     ChildStockQuery orderByFileUpdated($order = Criteria::ASC) Order by the stock_file_updated column
 * @method     ChildStockQuery orderByInsert($order = Criteria::ASC) Order by the stock_insert column
 * @method     ChildStockQuery orderByUpdate($order = Criteria::ASC) Order by the stock_update column
 * @method     ChildStockQuery orderByDl($order = Criteria::ASC) Order by the stock_dl column
 * @method     ChildStockQuery orderByCreatedAt($order = Criteria::ASC) Order by the stock_created column
 * @method     ChildStockQuery orderByUpdatedAt($order = Criteria::ASC) Order by the stock_updated column
 * @method     ChildStockQuery orderByDeletedAt($order = Criteria::ASC) Order by the stock_deleted column
 *
 * @method     ChildStockQuery groupById() Group by the stock_id column
 * @method     ChildStockQuery groupBySiteId() Group by the site_id column
 * @method     ChildStockQuery groupByArticleId() Group by the article_id column
 * @method     ChildStockQuery groupByCampaignId() Group by the campaign_id column
 * @method     ChildStockQuery groupByRewardId() Group by the reward_id column
 * @method     ChildStockQuery groupByUserId() Group by the user_id column
 * @method     ChildStockQuery groupByCustomerId() Group by the customer_id column
 * @method     ChildStockQuery groupByWishId() Group by the wish_id column
 * @method     ChildStockQuery groupByCartId() Group by the cart_id column
 * @method     ChildStockQuery groupByOrderId() Group by the order_id column
 * @method     ChildStockQuery groupByCouponId() Group by the coupon_id column
 * @method     ChildStockQuery groupByShop() Group by the stock_shop column
 * @method     ChildStockQuery groupByInvoice() Group by the stock_invoice column
 * @method     ChildStockQuery groupByDepot() Group by the stock_depot column
 * @method     ChildStockQuery groupByStockage() Group by the stock_stockage column
 * @method     ChildStockQuery groupByCondition() Group by the stock_condition column
 * @method     ChildStockQuery groupByConditionDetails() Group by the stock_condition_details column
 * @method     ChildStockQuery groupByPurchasePrice() Group by the stock_purchase_price column
 * @method     ChildStockQuery groupBySellingPrice() Group by the stock_selling_price column
 * @method     ChildStockQuery groupBySellingPrice2() Group by the stock_selling_price2 column
 * @method     ChildStockQuery groupBySellingPriceSaved() Group by the stock_selling_price_saved column
 * @method     ChildStockQuery groupBySellingPriceHt() Group by the stock_selling_price_ht column
 * @method     ChildStockQuery groupBySellingPriceTva() Group by the stock_selling_price_tva column
 * @method     ChildStockQuery groupByTvaRate() Group by the stock_tva_rate column
 * @method     ChildStockQuery groupByWeight() Group by the stock_weight column
 * @method     ChildStockQuery groupByPubYear() Group by the stock_pub_year column
 * @method     ChildStockQuery groupByAllowPredownload() Group by the stock_allow_predownload column
 * @method     ChildStockQuery groupByPhotoVersion() Group by the stock_photo_version column
 * @method     ChildStockQuery groupByPurchaseDate() Group by the stock_purchase_date column
 * @method     ChildStockQuery groupByOnsaleDate() Group by the stock_onsale_date column
 * @method     ChildStockQuery groupByCartDate() Group by the stock_cart_date column
 * @method     ChildStockQuery groupBySellingDate() Group by the stock_selling_date column
 * @method     ChildStockQuery groupByReturnDate() Group by the stock_return_date column
 * @method     ChildStockQuery groupByLostDate() Group by the stock_lost_date column
 * @method     ChildStockQuery groupByMediaOk() Group by the stock_media_ok column
 * @method     ChildStockQuery groupByFileUpdated() Group by the stock_file_updated column
 * @method     ChildStockQuery groupByInsert() Group by the stock_insert column
 * @method     ChildStockQuery groupByUpdate() Group by the stock_update column
 * @method     ChildStockQuery groupByDl() Group by the stock_dl column
 * @method     ChildStockQuery groupByCreatedAt() Group by the stock_created column
 * @method     ChildStockQuery groupByUpdatedAt() Group by the stock_updated column
 * @method     ChildStockQuery groupByDeletedAt() Group by the stock_deleted column
 *
 * @method     ChildStockQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildStockQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildStockQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildStockQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildStockQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildStockQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildStock|null findOne(ConnectionInterface $con = null) Return the first ChildStock matching the query
 * @method     ChildStock findOneOrCreate(ConnectionInterface $con = null) Return the first ChildStock matching the query, or a new ChildStock object populated from the query conditions when no match is found
 *
 * @method     ChildStock|null findOneById(int $stock_id) Return the first ChildStock filtered by the stock_id column
 * @method     ChildStock|null findOneBySiteId(int $site_id) Return the first ChildStock filtered by the site_id column
 * @method     ChildStock|null findOneByArticleId(int $article_id) Return the first ChildStock filtered by the article_id column
 * @method     ChildStock|null findOneByCampaignId(int $campaign_id) Return the first ChildStock filtered by the campaign_id column
 * @method     ChildStock|null findOneByRewardId(int $reward_id) Return the first ChildStock filtered by the reward_id column
 * @method     ChildStock|null findOneByUserId(int $user_id) Return the first ChildStock filtered by the user_id column
 * @method     ChildStock|null findOneByCustomerId(int $customer_id) Return the first ChildStock filtered by the customer_id column
 * @method     ChildStock|null findOneByWishId(int $wish_id) Return the first ChildStock filtered by the wish_id column
 * @method     ChildStock|null findOneByCartId(int $cart_id) Return the first ChildStock filtered by the cart_id column
 * @method     ChildStock|null findOneByOrderId(int $order_id) Return the first ChildStock filtered by the order_id column
 * @method     ChildStock|null findOneByCouponId(int $coupon_id) Return the first ChildStock filtered by the coupon_id column
 * @method     ChildStock|null findOneByShop(int $stock_shop) Return the first ChildStock filtered by the stock_shop column
 * @method     ChildStock|null findOneByInvoice(string $stock_invoice) Return the first ChildStock filtered by the stock_invoice column
 * @method     ChildStock|null findOneByDepot(boolean $stock_depot) Return the first ChildStock filtered by the stock_depot column
 * @method     ChildStock|null findOneByStockage(string $stock_stockage) Return the first ChildStock filtered by the stock_stockage column
 * @method     ChildStock|null findOneByCondition(string $stock_condition) Return the first ChildStock filtered by the stock_condition column
 * @method     ChildStock|null findOneByConditionDetails(string $stock_condition_details) Return the first ChildStock filtered by the stock_condition_details column
 * @method     ChildStock|null findOneByPurchasePrice(int $stock_purchase_price) Return the first ChildStock filtered by the stock_purchase_price column
 * @method     ChildStock|null findOneBySellingPrice(int $stock_selling_price) Return the first ChildStock filtered by the stock_selling_price column
 * @method     ChildStock|null findOneBySellingPrice2(int $stock_selling_price2) Return the first ChildStock filtered by the stock_selling_price2 column
 * @method     ChildStock|null findOneBySellingPriceSaved(int $stock_selling_price_saved) Return the first ChildStock filtered by the stock_selling_price_saved column
 * @method     ChildStock|null findOneBySellingPriceHt(int $stock_selling_price_ht) Return the first ChildStock filtered by the stock_selling_price_ht column
 * @method     ChildStock|null findOneBySellingPriceTva(int $stock_selling_price_tva) Return the first ChildStock filtered by the stock_selling_price_tva column
 * @method     ChildStock|null findOneByTvaRate(double $stock_tva_rate) Return the first ChildStock filtered by the stock_tva_rate column
 * @method     ChildStock|null findOneByWeight(int $stock_weight) Return the first ChildStock filtered by the stock_weight column
 * @method     ChildStock|null findOneByPubYear(int $stock_pub_year) Return the first ChildStock filtered by the stock_pub_year column
 * @method     ChildStock|null findOneByAllowPredownload(boolean $stock_allow_predownload) Return the first ChildStock filtered by the stock_allow_predownload column
 * @method     ChildStock|null findOneByPhotoVersion(int $stock_photo_version) Return the first ChildStock filtered by the stock_photo_version column
 * @method     ChildStock|null findOneByPurchaseDate(string $stock_purchase_date) Return the first ChildStock filtered by the stock_purchase_date column
 * @method     ChildStock|null findOneByOnsaleDate(string $stock_onsale_date) Return the first ChildStock filtered by the stock_onsale_date column
 * @method     ChildStock|null findOneByCartDate(string $stock_cart_date) Return the first ChildStock filtered by the stock_cart_date column
 * @method     ChildStock|null findOneBySellingDate(string $stock_selling_date) Return the first ChildStock filtered by the stock_selling_date column
 * @method     ChildStock|null findOneByReturnDate(string $stock_return_date) Return the first ChildStock filtered by the stock_return_date column
 * @method     ChildStock|null findOneByLostDate(string $stock_lost_date) Return the first ChildStock filtered by the stock_lost_date column
 * @method     ChildStock|null findOneByMediaOk(boolean $stock_media_ok) Return the first ChildStock filtered by the stock_media_ok column
 * @method     ChildStock|null findOneByFileUpdated(boolean $stock_file_updated) Return the first ChildStock filtered by the stock_file_updated column
 * @method     ChildStock|null findOneByInsert(string $stock_insert) Return the first ChildStock filtered by the stock_insert column
 * @method     ChildStock|null findOneByUpdate(string $stock_update) Return the first ChildStock filtered by the stock_update column
 * @method     ChildStock|null findOneByDl(boolean $stock_dl) Return the first ChildStock filtered by the stock_dl column
 * @method     ChildStock|null findOneByCreatedAt(string $stock_created) Return the first ChildStock filtered by the stock_created column
 * @method     ChildStock|null findOneByUpdatedAt(string $stock_updated) Return the first ChildStock filtered by the stock_updated column
 * @method     ChildStock|null findOneByDeletedAt(string $stock_deleted) Return the first ChildStock filtered by the stock_deleted column *

 * @method     ChildStock requirePk($key, ConnectionInterface $con = null) Return the ChildStock by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOne(ConnectionInterface $con = null) Return the first ChildStock matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildStock requireOneById(int $stock_id) Return the first ChildStock filtered by the stock_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneBySiteId(int $site_id) Return the first ChildStock filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByArticleId(int $article_id) Return the first ChildStock filtered by the article_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByCampaignId(int $campaign_id) Return the first ChildStock filtered by the campaign_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByRewardId(int $reward_id) Return the first ChildStock filtered by the reward_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByUserId(int $user_id) Return the first ChildStock filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByCustomerId(int $customer_id) Return the first ChildStock filtered by the customer_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByWishId(int $wish_id) Return the first ChildStock filtered by the wish_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByCartId(int $cart_id) Return the first ChildStock filtered by the cart_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByOrderId(int $order_id) Return the first ChildStock filtered by the order_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByCouponId(int $coupon_id) Return the first ChildStock filtered by the coupon_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByShop(int $stock_shop) Return the first ChildStock filtered by the stock_shop column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByInvoice(string $stock_invoice) Return the first ChildStock filtered by the stock_invoice column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByDepot(boolean $stock_depot) Return the first ChildStock filtered by the stock_depot column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByStockage(string $stock_stockage) Return the first ChildStock filtered by the stock_stockage column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByCondition(string $stock_condition) Return the first ChildStock filtered by the stock_condition column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByConditionDetails(string $stock_condition_details) Return the first ChildStock filtered by the stock_condition_details column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByPurchasePrice(int $stock_purchase_price) Return the first ChildStock filtered by the stock_purchase_price column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneBySellingPrice(int $stock_selling_price) Return the first ChildStock filtered by the stock_selling_price column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneBySellingPrice2(int $stock_selling_price2) Return the first ChildStock filtered by the stock_selling_price2 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneBySellingPriceSaved(int $stock_selling_price_saved) Return the first ChildStock filtered by the stock_selling_price_saved column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneBySellingPriceHt(int $stock_selling_price_ht) Return the first ChildStock filtered by the stock_selling_price_ht column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneBySellingPriceTva(int $stock_selling_price_tva) Return the first ChildStock filtered by the stock_selling_price_tva column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByTvaRate(double $stock_tva_rate) Return the first ChildStock filtered by the stock_tva_rate column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByWeight(int $stock_weight) Return the first ChildStock filtered by the stock_weight column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByPubYear(int $stock_pub_year) Return the first ChildStock filtered by the stock_pub_year column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByAllowPredownload(boolean $stock_allow_predownload) Return the first ChildStock filtered by the stock_allow_predownload column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByPhotoVersion(int $stock_photo_version) Return the first ChildStock filtered by the stock_photo_version column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByPurchaseDate(string $stock_purchase_date) Return the first ChildStock filtered by the stock_purchase_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByOnsaleDate(string $stock_onsale_date) Return the first ChildStock filtered by the stock_onsale_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByCartDate(string $stock_cart_date) Return the first ChildStock filtered by the stock_cart_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneBySellingDate(string $stock_selling_date) Return the first ChildStock filtered by the stock_selling_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByReturnDate(string $stock_return_date) Return the first ChildStock filtered by the stock_return_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByLostDate(string $stock_lost_date) Return the first ChildStock filtered by the stock_lost_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByMediaOk(boolean $stock_media_ok) Return the first ChildStock filtered by the stock_media_ok column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByFileUpdated(boolean $stock_file_updated) Return the first ChildStock filtered by the stock_file_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByInsert(string $stock_insert) Return the first ChildStock filtered by the stock_insert column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByUpdate(string $stock_update) Return the first ChildStock filtered by the stock_update column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByDl(boolean $stock_dl) Return the first ChildStock filtered by the stock_dl column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByCreatedAt(string $stock_created) Return the first ChildStock filtered by the stock_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByUpdatedAt(string $stock_updated) Return the first ChildStock filtered by the stock_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStock requireOneByDeletedAt(string $stock_deleted) Return the first ChildStock filtered by the stock_deleted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildStock[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildStock objects based on current ModelCriteria
 * @method     ChildStock[]|ObjectCollection findById(int $stock_id) Return ChildStock objects filtered by the stock_id column
 * @method     ChildStock[]|ObjectCollection findBySiteId(int $site_id) Return ChildStock objects filtered by the site_id column
 * @method     ChildStock[]|ObjectCollection findByArticleId(int $article_id) Return ChildStock objects filtered by the article_id column
 * @method     ChildStock[]|ObjectCollection findByCampaignId(int $campaign_id) Return ChildStock objects filtered by the campaign_id column
 * @method     ChildStock[]|ObjectCollection findByRewardId(int $reward_id) Return ChildStock objects filtered by the reward_id column
 * @method     ChildStock[]|ObjectCollection findByUserId(int $user_id) Return ChildStock objects filtered by the user_id column
 * @method     ChildStock[]|ObjectCollection findByCustomerId(int $customer_id) Return ChildStock objects filtered by the customer_id column
 * @method     ChildStock[]|ObjectCollection findByWishId(int $wish_id) Return ChildStock objects filtered by the wish_id column
 * @method     ChildStock[]|ObjectCollection findByCartId(int $cart_id) Return ChildStock objects filtered by the cart_id column
 * @method     ChildStock[]|ObjectCollection findByOrderId(int $order_id) Return ChildStock objects filtered by the order_id column
 * @method     ChildStock[]|ObjectCollection findByCouponId(int $coupon_id) Return ChildStock objects filtered by the coupon_id column
 * @method     ChildStock[]|ObjectCollection findByShop(int $stock_shop) Return ChildStock objects filtered by the stock_shop column
 * @method     ChildStock[]|ObjectCollection findByInvoice(string $stock_invoice) Return ChildStock objects filtered by the stock_invoice column
 * @method     ChildStock[]|ObjectCollection findByDepot(boolean $stock_depot) Return ChildStock objects filtered by the stock_depot column
 * @method     ChildStock[]|ObjectCollection findByStockage(string $stock_stockage) Return ChildStock objects filtered by the stock_stockage column
 * @method     ChildStock[]|ObjectCollection findByCondition(string $stock_condition) Return ChildStock objects filtered by the stock_condition column
 * @method     ChildStock[]|ObjectCollection findByConditionDetails(string $stock_condition_details) Return ChildStock objects filtered by the stock_condition_details column
 * @method     ChildStock[]|ObjectCollection findByPurchasePrice(int $stock_purchase_price) Return ChildStock objects filtered by the stock_purchase_price column
 * @method     ChildStock[]|ObjectCollection findBySellingPrice(int $stock_selling_price) Return ChildStock objects filtered by the stock_selling_price column
 * @method     ChildStock[]|ObjectCollection findBySellingPrice2(int $stock_selling_price2) Return ChildStock objects filtered by the stock_selling_price2 column
 * @method     ChildStock[]|ObjectCollection findBySellingPriceSaved(int $stock_selling_price_saved) Return ChildStock objects filtered by the stock_selling_price_saved column
 * @method     ChildStock[]|ObjectCollection findBySellingPriceHt(int $stock_selling_price_ht) Return ChildStock objects filtered by the stock_selling_price_ht column
 * @method     ChildStock[]|ObjectCollection findBySellingPriceTva(int $stock_selling_price_tva) Return ChildStock objects filtered by the stock_selling_price_tva column
 * @method     ChildStock[]|ObjectCollection findByTvaRate(double $stock_tva_rate) Return ChildStock objects filtered by the stock_tva_rate column
 * @method     ChildStock[]|ObjectCollection findByWeight(int $stock_weight) Return ChildStock objects filtered by the stock_weight column
 * @method     ChildStock[]|ObjectCollection findByPubYear(int $stock_pub_year) Return ChildStock objects filtered by the stock_pub_year column
 * @method     ChildStock[]|ObjectCollection findByAllowPredownload(boolean $stock_allow_predownload) Return ChildStock objects filtered by the stock_allow_predownload column
 * @method     ChildStock[]|ObjectCollection findByPhotoVersion(int $stock_photo_version) Return ChildStock objects filtered by the stock_photo_version column
 * @method     ChildStock[]|ObjectCollection findByPurchaseDate(string $stock_purchase_date) Return ChildStock objects filtered by the stock_purchase_date column
 * @method     ChildStock[]|ObjectCollection findByOnsaleDate(string $stock_onsale_date) Return ChildStock objects filtered by the stock_onsale_date column
 * @method     ChildStock[]|ObjectCollection findByCartDate(string $stock_cart_date) Return ChildStock objects filtered by the stock_cart_date column
 * @method     ChildStock[]|ObjectCollection findBySellingDate(string $stock_selling_date) Return ChildStock objects filtered by the stock_selling_date column
 * @method     ChildStock[]|ObjectCollection findByReturnDate(string $stock_return_date) Return ChildStock objects filtered by the stock_return_date column
 * @method     ChildStock[]|ObjectCollection findByLostDate(string $stock_lost_date) Return ChildStock objects filtered by the stock_lost_date column
 * @method     ChildStock[]|ObjectCollection findByMediaOk(boolean $stock_media_ok) Return ChildStock objects filtered by the stock_media_ok column
 * @method     ChildStock[]|ObjectCollection findByFileUpdated(boolean $stock_file_updated) Return ChildStock objects filtered by the stock_file_updated column
 * @method     ChildStock[]|ObjectCollection findByInsert(string $stock_insert) Return ChildStock objects filtered by the stock_insert column
 * @method     ChildStock[]|ObjectCollection findByUpdate(string $stock_update) Return ChildStock objects filtered by the stock_update column
 * @method     ChildStock[]|ObjectCollection findByDl(boolean $stock_dl) Return ChildStock objects filtered by the stock_dl column
 * @method     ChildStock[]|ObjectCollection findByCreatedAt(string $stock_created) Return ChildStock objects filtered by the stock_created column
 * @method     ChildStock[]|ObjectCollection findByUpdatedAt(string $stock_updated) Return ChildStock objects filtered by the stock_updated column
 * @method     ChildStock[]|ObjectCollection findByDeletedAt(string $stock_deleted) Return ChildStock objects filtered by the stock_deleted column
 * @method     ChildStock[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class StockQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\StockQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Stock', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildStockQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildStockQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildStockQuery) {
            return $criteria;
        }
        $query = new ChildStockQuery();
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
     * @return ChildStock|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(StockTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = StockTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildStock A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT stock_id, site_id, article_id, campaign_id, reward_id, user_id, customer_id, wish_id, cart_id, order_id, coupon_id, stock_shop, stock_invoice, stock_depot, stock_stockage, stock_condition, stock_condition_details, stock_purchase_price, stock_selling_price, stock_selling_price2, stock_selling_price_saved, stock_selling_price_ht, stock_selling_price_tva, stock_tva_rate, stock_weight, stock_pub_year, stock_allow_predownload, stock_photo_version, stock_purchase_date, stock_onsale_date, stock_cart_date, stock_selling_date, stock_return_date, stock_lost_date, stock_media_ok, stock_file_updated, stock_insert, stock_update, stock_dl, stock_created, stock_updated, stock_deleted FROM stock WHERE stock_id = :p0';
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
            /** @var ChildStock $obj */
            $obj = new ChildStock();
            $obj->hydrate($row);
            StockTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildStock|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(StockTableMap::COL_STOCK_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(StockTableMap::COL_STOCK_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the stock_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE stock_id = 1234
     * $query->filterById(array(12, 34)); // WHERE stock_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE stock_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_ID, $id, $comparison);
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
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterBySiteId($siteId = null, $comparison = null)
    {
        if (is_array($siteId)) {
            $useMinMax = false;
            if (isset($siteId['min'])) {
                $this->addUsingAlias(StockTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(StockTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_SITE_ID, $siteId, $comparison);
    }

    /**
     * Filter the query on the article_id column
     *
     * Example usage:
     * <code>
     * $query->filterByArticleId(1234); // WHERE article_id = 1234
     * $query->filterByArticleId(array(12, 34)); // WHERE article_id IN (12, 34)
     * $query->filterByArticleId(array('min' => 12)); // WHERE article_id > 12
     * </code>
     *
     * @param     mixed $articleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByArticleId($articleId = null, $comparison = null)
    {
        if (is_array($articleId)) {
            $useMinMax = false;
            if (isset($articleId['min'])) {
                $this->addUsingAlias(StockTableMap::COL_ARTICLE_ID, $articleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($articleId['max'])) {
                $this->addUsingAlias(StockTableMap::COL_ARTICLE_ID, $articleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_ARTICLE_ID, $articleId, $comparison);
    }

    /**
     * Filter the query on the campaign_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCampaignId(1234); // WHERE campaign_id = 1234
     * $query->filterByCampaignId(array(12, 34)); // WHERE campaign_id IN (12, 34)
     * $query->filterByCampaignId(array('min' => 12)); // WHERE campaign_id > 12
     * </code>
     *
     * @param     mixed $campaignId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByCampaignId($campaignId = null, $comparison = null)
    {
        if (is_array($campaignId)) {
            $useMinMax = false;
            if (isset($campaignId['min'])) {
                $this->addUsingAlias(StockTableMap::COL_CAMPAIGN_ID, $campaignId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($campaignId['max'])) {
                $this->addUsingAlias(StockTableMap::COL_CAMPAIGN_ID, $campaignId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_CAMPAIGN_ID, $campaignId, $comparison);
    }

    /**
     * Filter the query on the reward_id column
     *
     * Example usage:
     * <code>
     * $query->filterByRewardId(1234); // WHERE reward_id = 1234
     * $query->filterByRewardId(array(12, 34)); // WHERE reward_id IN (12, 34)
     * $query->filterByRewardId(array('min' => 12)); // WHERE reward_id > 12
     * </code>
     *
     * @param     mixed $rewardId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByRewardId($rewardId = null, $comparison = null)
    {
        if (is_array($rewardId)) {
            $useMinMax = false;
            if (isset($rewardId['min'])) {
                $this->addUsingAlias(StockTableMap::COL_REWARD_ID, $rewardId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($rewardId['max'])) {
                $this->addUsingAlias(StockTableMap::COL_REWARD_ID, $rewardId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_REWARD_ID, $rewardId, $comparison);
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE user_id = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE user_id > 12
     * </code>
     *
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(StockTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(StockTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the customer_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCustomerId(1234); // WHERE customer_id = 1234
     * $query->filterByCustomerId(array(12, 34)); // WHERE customer_id IN (12, 34)
     * $query->filterByCustomerId(array('min' => 12)); // WHERE customer_id > 12
     * </code>
     *
     * @param     mixed $customerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByCustomerId($customerId = null, $comparison = null)
    {
        if (is_array($customerId)) {
            $useMinMax = false;
            if (isset($customerId['min'])) {
                $this->addUsingAlias(StockTableMap::COL_CUSTOMER_ID, $customerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($customerId['max'])) {
                $this->addUsingAlias(StockTableMap::COL_CUSTOMER_ID, $customerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_CUSTOMER_ID, $customerId, $comparison);
    }

    /**
     * Filter the query on the wish_id column
     *
     * Example usage:
     * <code>
     * $query->filterByWishId(1234); // WHERE wish_id = 1234
     * $query->filterByWishId(array(12, 34)); // WHERE wish_id IN (12, 34)
     * $query->filterByWishId(array('min' => 12)); // WHERE wish_id > 12
     * </code>
     *
     * @param     mixed $wishId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByWishId($wishId = null, $comparison = null)
    {
        if (is_array($wishId)) {
            $useMinMax = false;
            if (isset($wishId['min'])) {
                $this->addUsingAlias(StockTableMap::COL_WISH_ID, $wishId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($wishId['max'])) {
                $this->addUsingAlias(StockTableMap::COL_WISH_ID, $wishId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_WISH_ID, $wishId, $comparison);
    }

    /**
     * Filter the query on the cart_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCartId(1234); // WHERE cart_id = 1234
     * $query->filterByCartId(array(12, 34)); // WHERE cart_id IN (12, 34)
     * $query->filterByCartId(array('min' => 12)); // WHERE cart_id > 12
     * </code>
     *
     * @param     mixed $cartId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByCartId($cartId = null, $comparison = null)
    {
        if (is_array($cartId)) {
            $useMinMax = false;
            if (isset($cartId['min'])) {
                $this->addUsingAlias(StockTableMap::COL_CART_ID, $cartId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($cartId['max'])) {
                $this->addUsingAlias(StockTableMap::COL_CART_ID, $cartId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_CART_ID, $cartId, $comparison);
    }

    /**
     * Filter the query on the order_id column
     *
     * Example usage:
     * <code>
     * $query->filterByOrderId(1234); // WHERE order_id = 1234
     * $query->filterByOrderId(array(12, 34)); // WHERE order_id IN (12, 34)
     * $query->filterByOrderId(array('min' => 12)); // WHERE order_id > 12
     * </code>
     *
     * @param     mixed $orderId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByOrderId($orderId = null, $comparison = null)
    {
        if (is_array($orderId)) {
            $useMinMax = false;
            if (isset($orderId['min'])) {
                $this->addUsingAlias(StockTableMap::COL_ORDER_ID, $orderId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($orderId['max'])) {
                $this->addUsingAlias(StockTableMap::COL_ORDER_ID, $orderId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_ORDER_ID, $orderId, $comparison);
    }

    /**
     * Filter the query on the coupon_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCouponId(1234); // WHERE coupon_id = 1234
     * $query->filterByCouponId(array(12, 34)); // WHERE coupon_id IN (12, 34)
     * $query->filterByCouponId(array('min' => 12)); // WHERE coupon_id > 12
     * </code>
     *
     * @param     mixed $couponId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByCouponId($couponId = null, $comparison = null)
    {
        if (is_array($couponId)) {
            $useMinMax = false;
            if (isset($couponId['min'])) {
                $this->addUsingAlias(StockTableMap::COL_COUPON_ID, $couponId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($couponId['max'])) {
                $this->addUsingAlias(StockTableMap::COL_COUPON_ID, $couponId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_COUPON_ID, $couponId, $comparison);
    }

    /**
     * Filter the query on the stock_shop column
     *
     * Example usage:
     * <code>
     * $query->filterByShop(1234); // WHERE stock_shop = 1234
     * $query->filterByShop(array(12, 34)); // WHERE stock_shop IN (12, 34)
     * $query->filterByShop(array('min' => 12)); // WHERE stock_shop > 12
     * </code>
     *
     * @param     mixed $shop The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByShop($shop = null, $comparison = null)
    {
        if (is_array($shop)) {
            $useMinMax = false;
            if (isset($shop['min'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_SHOP, $shop['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($shop['max'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_SHOP, $shop['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_SHOP, $shop, $comparison);
    }

    /**
     * Filter the query on the stock_invoice column
     *
     * Example usage:
     * <code>
     * $query->filterByInvoice('fooValue');   // WHERE stock_invoice = 'fooValue'
     * $query->filterByInvoice('%fooValue%', Criteria::LIKE); // WHERE stock_invoice LIKE '%fooValue%'
     * </code>
     *
     * @param     string $invoice The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByInvoice($invoice = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($invoice)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_INVOICE, $invoice, $comparison);
    }

    /**
     * Filter the query on the stock_depot column
     *
     * Example usage:
     * <code>
     * $query->filterByDepot(true); // WHERE stock_depot = true
     * $query->filterByDepot('yes'); // WHERE stock_depot = true
     * </code>
     *
     * @param     boolean|string $depot The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByDepot($depot = null, $comparison = null)
    {
        if (is_string($depot)) {
            $depot = in_array(strtolower($depot), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_DEPOT, $depot, $comparison);
    }

    /**
     * Filter the query on the stock_stockage column
     *
     * Example usage:
     * <code>
     * $query->filterByStockage('fooValue');   // WHERE stock_stockage = 'fooValue'
     * $query->filterByStockage('%fooValue%', Criteria::LIKE); // WHERE stock_stockage LIKE '%fooValue%'
     * </code>
     *
     * @param     string $stockage The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByStockage($stockage = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($stockage)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_STOCKAGE, $stockage, $comparison);
    }

    /**
     * Filter the query on the stock_condition column
     *
     * Example usage:
     * <code>
     * $query->filterByCondition('fooValue');   // WHERE stock_condition = 'fooValue'
     * $query->filterByCondition('%fooValue%', Criteria::LIKE); // WHERE stock_condition LIKE '%fooValue%'
     * </code>
     *
     * @param     string $condition The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByCondition($condition = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($condition)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_CONDITION, $condition, $comparison);
    }

    /**
     * Filter the query on the stock_condition_details column
     *
     * Example usage:
     * <code>
     * $query->filterByConditionDetails('fooValue');   // WHERE stock_condition_details = 'fooValue'
     * $query->filterByConditionDetails('%fooValue%', Criteria::LIKE); // WHERE stock_condition_details LIKE '%fooValue%'
     * </code>
     *
     * @param     string $conditionDetails The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByConditionDetails($conditionDetails = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($conditionDetails)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_CONDITION_DETAILS, $conditionDetails, $comparison);
    }

    /**
     * Filter the query on the stock_purchase_price column
     *
     * Example usage:
     * <code>
     * $query->filterByPurchasePrice(1234); // WHERE stock_purchase_price = 1234
     * $query->filterByPurchasePrice(array(12, 34)); // WHERE stock_purchase_price IN (12, 34)
     * $query->filterByPurchasePrice(array('min' => 12)); // WHERE stock_purchase_price > 12
     * </code>
     *
     * @param     mixed $purchasePrice The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByPurchasePrice($purchasePrice = null, $comparison = null)
    {
        if (is_array($purchasePrice)) {
            $useMinMax = false;
            if (isset($purchasePrice['min'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_PURCHASE_PRICE, $purchasePrice['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($purchasePrice['max'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_PURCHASE_PRICE, $purchasePrice['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_PURCHASE_PRICE, $purchasePrice, $comparison);
    }

    /**
     * Filter the query on the stock_selling_price column
     *
     * Example usage:
     * <code>
     * $query->filterBySellingPrice(1234); // WHERE stock_selling_price = 1234
     * $query->filterBySellingPrice(array(12, 34)); // WHERE stock_selling_price IN (12, 34)
     * $query->filterBySellingPrice(array('min' => 12)); // WHERE stock_selling_price > 12
     * </code>
     *
     * @param     mixed $sellingPrice The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterBySellingPrice($sellingPrice = null, $comparison = null)
    {
        if (is_array($sellingPrice)) {
            $useMinMax = false;
            if (isset($sellingPrice['min'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_SELLING_PRICE, $sellingPrice['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sellingPrice['max'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_SELLING_PRICE, $sellingPrice['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_SELLING_PRICE, $sellingPrice, $comparison);
    }

    /**
     * Filter the query on the stock_selling_price2 column
     *
     * Example usage:
     * <code>
     * $query->filterBySellingPrice2(1234); // WHERE stock_selling_price2 = 1234
     * $query->filterBySellingPrice2(array(12, 34)); // WHERE stock_selling_price2 IN (12, 34)
     * $query->filterBySellingPrice2(array('min' => 12)); // WHERE stock_selling_price2 > 12
     * </code>
     *
     * @param     mixed $sellingPrice2 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterBySellingPrice2($sellingPrice2 = null, $comparison = null)
    {
        if (is_array($sellingPrice2)) {
            $useMinMax = false;
            if (isset($sellingPrice2['min'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_SELLING_PRICE2, $sellingPrice2['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sellingPrice2['max'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_SELLING_PRICE2, $sellingPrice2['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_SELLING_PRICE2, $sellingPrice2, $comparison);
    }

    /**
     * Filter the query on the stock_selling_price_saved column
     *
     * Example usage:
     * <code>
     * $query->filterBySellingPriceSaved(1234); // WHERE stock_selling_price_saved = 1234
     * $query->filterBySellingPriceSaved(array(12, 34)); // WHERE stock_selling_price_saved IN (12, 34)
     * $query->filterBySellingPriceSaved(array('min' => 12)); // WHERE stock_selling_price_saved > 12
     * </code>
     *
     * @param     mixed $sellingPriceSaved The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterBySellingPriceSaved($sellingPriceSaved = null, $comparison = null)
    {
        if (is_array($sellingPriceSaved)) {
            $useMinMax = false;
            if (isset($sellingPriceSaved['min'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_SELLING_PRICE_SAVED, $sellingPriceSaved['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sellingPriceSaved['max'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_SELLING_PRICE_SAVED, $sellingPriceSaved['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_SELLING_PRICE_SAVED, $sellingPriceSaved, $comparison);
    }

    /**
     * Filter the query on the stock_selling_price_ht column
     *
     * Example usage:
     * <code>
     * $query->filterBySellingPriceHt(1234); // WHERE stock_selling_price_ht = 1234
     * $query->filterBySellingPriceHt(array(12, 34)); // WHERE stock_selling_price_ht IN (12, 34)
     * $query->filterBySellingPriceHt(array('min' => 12)); // WHERE stock_selling_price_ht > 12
     * </code>
     *
     * @param     mixed $sellingPriceHt The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterBySellingPriceHt($sellingPriceHt = null, $comparison = null)
    {
        if (is_array($sellingPriceHt)) {
            $useMinMax = false;
            if (isset($sellingPriceHt['min'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_SELLING_PRICE_HT, $sellingPriceHt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sellingPriceHt['max'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_SELLING_PRICE_HT, $sellingPriceHt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_SELLING_PRICE_HT, $sellingPriceHt, $comparison);
    }

    /**
     * Filter the query on the stock_selling_price_tva column
     *
     * Example usage:
     * <code>
     * $query->filterBySellingPriceTva(1234); // WHERE stock_selling_price_tva = 1234
     * $query->filterBySellingPriceTva(array(12, 34)); // WHERE stock_selling_price_tva IN (12, 34)
     * $query->filterBySellingPriceTva(array('min' => 12)); // WHERE stock_selling_price_tva > 12
     * </code>
     *
     * @param     mixed $sellingPriceTva The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterBySellingPriceTva($sellingPriceTva = null, $comparison = null)
    {
        if (is_array($sellingPriceTva)) {
            $useMinMax = false;
            if (isset($sellingPriceTva['min'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_SELLING_PRICE_TVA, $sellingPriceTva['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sellingPriceTva['max'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_SELLING_PRICE_TVA, $sellingPriceTva['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_SELLING_PRICE_TVA, $sellingPriceTva, $comparison);
    }

    /**
     * Filter the query on the stock_tva_rate column
     *
     * Example usage:
     * <code>
     * $query->filterByTvaRate(1234); // WHERE stock_tva_rate = 1234
     * $query->filterByTvaRate(array(12, 34)); // WHERE stock_tva_rate IN (12, 34)
     * $query->filterByTvaRate(array('min' => 12)); // WHERE stock_tva_rate > 12
     * </code>
     *
     * @param     mixed $tvaRate The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByTvaRate($tvaRate = null, $comparison = null)
    {
        if (is_array($tvaRate)) {
            $useMinMax = false;
            if (isset($tvaRate['min'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_TVA_RATE, $tvaRate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($tvaRate['max'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_TVA_RATE, $tvaRate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_TVA_RATE, $tvaRate, $comparison);
    }

    /**
     * Filter the query on the stock_weight column
     *
     * Example usage:
     * <code>
     * $query->filterByWeight(1234); // WHERE stock_weight = 1234
     * $query->filterByWeight(array(12, 34)); // WHERE stock_weight IN (12, 34)
     * $query->filterByWeight(array('min' => 12)); // WHERE stock_weight > 12
     * </code>
     *
     * @param     mixed $weight The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByWeight($weight = null, $comparison = null)
    {
        if (is_array($weight)) {
            $useMinMax = false;
            if (isset($weight['min'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_WEIGHT, $weight['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($weight['max'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_WEIGHT, $weight['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_WEIGHT, $weight, $comparison);
    }

    /**
     * Filter the query on the stock_pub_year column
     *
     * Example usage:
     * <code>
     * $query->filterByPubYear(1234); // WHERE stock_pub_year = 1234
     * $query->filterByPubYear(array(12, 34)); // WHERE stock_pub_year IN (12, 34)
     * $query->filterByPubYear(array('min' => 12)); // WHERE stock_pub_year > 12
     * </code>
     *
     * @param     mixed $pubYear The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByPubYear($pubYear = null, $comparison = null)
    {
        if (is_array($pubYear)) {
            $useMinMax = false;
            if (isset($pubYear['min'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_PUB_YEAR, $pubYear['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pubYear['max'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_PUB_YEAR, $pubYear['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_PUB_YEAR, $pubYear, $comparison);
    }

    /**
     * Filter the query on the stock_allow_predownload column
     *
     * Example usage:
     * <code>
     * $query->filterByAllowPredownload(true); // WHERE stock_allow_predownload = true
     * $query->filterByAllowPredownload('yes'); // WHERE stock_allow_predownload = true
     * </code>
     *
     * @param     boolean|string $allowPredownload The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByAllowPredownload($allowPredownload = null, $comparison = null)
    {
        if (is_string($allowPredownload)) {
            $allowPredownload = in_array(strtolower($allowPredownload), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_ALLOW_PREDOWNLOAD, $allowPredownload, $comparison);
    }

    /**
     * Filter the query on the stock_photo_version column
     *
     * Example usage:
     * <code>
     * $query->filterByPhotoVersion(1234); // WHERE stock_photo_version = 1234
     * $query->filterByPhotoVersion(array(12, 34)); // WHERE stock_photo_version IN (12, 34)
     * $query->filterByPhotoVersion(array('min' => 12)); // WHERE stock_photo_version > 12
     * </code>
     *
     * @param     mixed $photoVersion The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByPhotoVersion($photoVersion = null, $comparison = null)
    {
        if (is_array($photoVersion)) {
            $useMinMax = false;
            if (isset($photoVersion['min'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_PHOTO_VERSION, $photoVersion['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($photoVersion['max'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_PHOTO_VERSION, $photoVersion['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_PHOTO_VERSION, $photoVersion, $comparison);
    }

    /**
     * Filter the query on the stock_purchase_date column
     *
     * Example usage:
     * <code>
     * $query->filterByPurchaseDate('2011-03-14'); // WHERE stock_purchase_date = '2011-03-14'
     * $query->filterByPurchaseDate('now'); // WHERE stock_purchase_date = '2011-03-14'
     * $query->filterByPurchaseDate(array('max' => 'yesterday')); // WHERE stock_purchase_date > '2011-03-13'
     * </code>
     *
     * @param     mixed $purchaseDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByPurchaseDate($purchaseDate = null, $comparison = null)
    {
        if (is_array($purchaseDate)) {
            $useMinMax = false;
            if (isset($purchaseDate['min'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_PURCHASE_DATE, $purchaseDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($purchaseDate['max'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_PURCHASE_DATE, $purchaseDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_PURCHASE_DATE, $purchaseDate, $comparison);
    }

    /**
     * Filter the query on the stock_onsale_date column
     *
     * Example usage:
     * <code>
     * $query->filterByOnsaleDate('2011-03-14'); // WHERE stock_onsale_date = '2011-03-14'
     * $query->filterByOnsaleDate('now'); // WHERE stock_onsale_date = '2011-03-14'
     * $query->filterByOnsaleDate(array('max' => 'yesterday')); // WHERE stock_onsale_date > '2011-03-13'
     * </code>
     *
     * @param     mixed $onsaleDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByOnsaleDate($onsaleDate = null, $comparison = null)
    {
        if (is_array($onsaleDate)) {
            $useMinMax = false;
            if (isset($onsaleDate['min'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_ONSALE_DATE, $onsaleDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($onsaleDate['max'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_ONSALE_DATE, $onsaleDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_ONSALE_DATE, $onsaleDate, $comparison);
    }

    /**
     * Filter the query on the stock_cart_date column
     *
     * Example usage:
     * <code>
     * $query->filterByCartDate('2011-03-14'); // WHERE stock_cart_date = '2011-03-14'
     * $query->filterByCartDate('now'); // WHERE stock_cart_date = '2011-03-14'
     * $query->filterByCartDate(array('max' => 'yesterday')); // WHERE stock_cart_date > '2011-03-13'
     * </code>
     *
     * @param     mixed $cartDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByCartDate($cartDate = null, $comparison = null)
    {
        if (is_array($cartDate)) {
            $useMinMax = false;
            if (isset($cartDate['min'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_CART_DATE, $cartDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($cartDate['max'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_CART_DATE, $cartDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_CART_DATE, $cartDate, $comparison);
    }

    /**
     * Filter the query on the stock_selling_date column
     *
     * Example usage:
     * <code>
     * $query->filterBySellingDate('2011-03-14'); // WHERE stock_selling_date = '2011-03-14'
     * $query->filterBySellingDate('now'); // WHERE stock_selling_date = '2011-03-14'
     * $query->filterBySellingDate(array('max' => 'yesterday')); // WHERE stock_selling_date > '2011-03-13'
     * </code>
     *
     * @param     mixed $sellingDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterBySellingDate($sellingDate = null, $comparison = null)
    {
        if (is_array($sellingDate)) {
            $useMinMax = false;
            if (isset($sellingDate['min'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_SELLING_DATE, $sellingDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sellingDate['max'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_SELLING_DATE, $sellingDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_SELLING_DATE, $sellingDate, $comparison);
    }

    /**
     * Filter the query on the stock_return_date column
     *
     * Example usage:
     * <code>
     * $query->filterByReturnDate('2011-03-14'); // WHERE stock_return_date = '2011-03-14'
     * $query->filterByReturnDate('now'); // WHERE stock_return_date = '2011-03-14'
     * $query->filterByReturnDate(array('max' => 'yesterday')); // WHERE stock_return_date > '2011-03-13'
     * </code>
     *
     * @param     mixed $returnDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByReturnDate($returnDate = null, $comparison = null)
    {
        if (is_array($returnDate)) {
            $useMinMax = false;
            if (isset($returnDate['min'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_RETURN_DATE, $returnDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($returnDate['max'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_RETURN_DATE, $returnDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_RETURN_DATE, $returnDate, $comparison);
    }

    /**
     * Filter the query on the stock_lost_date column
     *
     * Example usage:
     * <code>
     * $query->filterByLostDate('2011-03-14'); // WHERE stock_lost_date = '2011-03-14'
     * $query->filterByLostDate('now'); // WHERE stock_lost_date = '2011-03-14'
     * $query->filterByLostDate(array('max' => 'yesterday')); // WHERE stock_lost_date > '2011-03-13'
     * </code>
     *
     * @param     mixed $lostDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByLostDate($lostDate = null, $comparison = null)
    {
        if (is_array($lostDate)) {
            $useMinMax = false;
            if (isset($lostDate['min'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_LOST_DATE, $lostDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lostDate['max'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_LOST_DATE, $lostDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_LOST_DATE, $lostDate, $comparison);
    }

    /**
     * Filter the query on the stock_media_ok column
     *
     * Example usage:
     * <code>
     * $query->filterByMediaOk(true); // WHERE stock_media_ok = true
     * $query->filterByMediaOk('yes'); // WHERE stock_media_ok = true
     * </code>
     *
     * @param     boolean|string $mediaOk The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByMediaOk($mediaOk = null, $comparison = null)
    {
        if (is_string($mediaOk)) {
            $mediaOk = in_array(strtolower($mediaOk), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_MEDIA_OK, $mediaOk, $comparison);
    }

    /**
     * Filter the query on the stock_file_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByFileUpdated(true); // WHERE stock_file_updated = true
     * $query->filterByFileUpdated('yes'); // WHERE stock_file_updated = true
     * </code>
     *
     * @param     boolean|string $fileUpdated The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByFileUpdated($fileUpdated = null, $comparison = null)
    {
        if (is_string($fileUpdated)) {
            $fileUpdated = in_array(strtolower($fileUpdated), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_FILE_UPDATED, $fileUpdated, $comparison);
    }

    /**
     * Filter the query on the stock_insert column
     *
     * Example usage:
     * <code>
     * $query->filterByInsert('2011-03-14'); // WHERE stock_insert = '2011-03-14'
     * $query->filterByInsert('now'); // WHERE stock_insert = '2011-03-14'
     * $query->filterByInsert(array('max' => 'yesterday')); // WHERE stock_insert > '2011-03-13'
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
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByInsert($insert = null, $comparison = null)
    {
        if (is_array($insert)) {
            $useMinMax = false;
            if (isset($insert['min'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_INSERT, $insert['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($insert['max'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_INSERT, $insert['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_INSERT, $insert, $comparison);
    }

    /**
     * Filter the query on the stock_update column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdate('2011-03-14'); // WHERE stock_update = '2011-03-14'
     * $query->filterByUpdate('now'); // WHERE stock_update = '2011-03-14'
     * $query->filterByUpdate(array('max' => 'yesterday')); // WHERE stock_update > '2011-03-13'
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
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByUpdate($update = null, $comparison = null)
    {
        if (is_array($update)) {
            $useMinMax = false;
            if (isset($update['min'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_UPDATE, $update['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($update['max'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_UPDATE, $update['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_UPDATE, $update, $comparison);
    }

    /**
     * Filter the query on the stock_dl column
     *
     * Example usage:
     * <code>
     * $query->filterByDl(true); // WHERE stock_dl = true
     * $query->filterByDl('yes'); // WHERE stock_dl = true
     * </code>
     *
     * @param     boolean|string $dl The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByDl($dl = null, $comparison = null)
    {
        if (is_string($dl)) {
            $dl = in_array(strtolower($dl), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_DL, $dl, $comparison);
    }

    /**
     * Filter the query on the stock_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE stock_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE stock_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE stock_created > '2011-03-13'
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
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_CREATED, $createdAt, $comparison);
    }

    /**
     * Filter the query on the stock_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE stock_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE stock_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE stock_updated > '2011-03-13'
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
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the stock_deleted column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletedAt('2011-03-14'); // WHERE stock_deleted = '2011-03-14'
     * $query->filterByDeletedAt('now'); // WHERE stock_deleted = '2011-03-14'
     * $query->filterByDeletedAt(array('max' => 'yesterday')); // WHERE stock_deleted > '2011-03-13'
     * </code>
     *
     * @param     mixed $deletedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function filterByDeletedAt($deletedAt = null, $comparison = null)
    {
        if (is_array($deletedAt)) {
            $useMinMax = false;
            if (isset($deletedAt['min'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_DELETED, $deletedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletedAt['max'])) {
                $this->addUsingAlias(StockTableMap::COL_STOCK_DELETED, $deletedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StockTableMap::COL_STOCK_DELETED, $deletedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildStock $stock Object to remove from the list of results
     *
     * @return $this|ChildStockQuery The current query, for fluid interface
     */
    public function prune($stock = null)
    {
        if ($stock) {
            $this->addUsingAlias(StockTableMap::COL_STOCK_ID, $stock->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the stock table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(StockTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            StockTableMap::clearInstancePool();
            StockTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(StockTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(StockTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            StockTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            StockTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildStockQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(StockTableMap::COL_STOCK_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildStockQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(StockTableMap::COL_STOCK_UPDATED);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildStockQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(StockTableMap::COL_STOCK_UPDATED);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildStockQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(StockTableMap::COL_STOCK_CREATED);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildStockQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(StockTableMap::COL_STOCK_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildStockQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(StockTableMap::COL_STOCK_CREATED);
    }

} // StockQuery
