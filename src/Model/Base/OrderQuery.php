<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Order as ChildOrder;
use Model\OrderQuery as ChildOrderQuery;
use Model\Map\OrderTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `orders` table.
 *
 * @method     ChildOrderQuery orderById($order = Criteria::ASC) Order by the order_id column
 * @method     ChildOrderQuery orderBySlug($order = Criteria::ASC) Order by the order_url column
 * @method     ChildOrderQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildOrderQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildOrderQuery orderByCustomerId($order = Criteria::ASC) Order by the customer_id column
 * @method     ChildOrderQuery orderBySellerId($order = Criteria::ASC) Order by the seller_id column
 * @method     ChildOrderQuery orderByType($order = Criteria::ASC) Order by the order_type column
 * @method     ChildOrderQuery orderByAsAGift($order = Criteria::ASC) Order by the order_as_a_gift column
 * @method     ChildOrderQuery orderByGiftRecipient($order = Criteria::ASC) Order by the order_gift_recipient column
 * @method     ChildOrderQuery orderByAmount($order = Criteria::ASC) Order by the order_amount column
 * @method     ChildOrderQuery orderByDiscount($order = Criteria::ASC) Order by the order_discount column
 * @method     ChildOrderQuery orderByAmountTobepaid($order = Criteria::ASC) Order by the order_amount_tobepaid column
 * @method     ChildOrderQuery orderByShippingId($order = Criteria::ASC) Order by the shipping_id column
 * @method     ChildOrderQuery orderByCountryId($order = Criteria::ASC) Order by the country_id column
 * @method     ChildOrderQuery orderByShipping($order = Criteria::ASC) Order by the order_shipping column
 * @method     ChildOrderQuery orderByShippingMode($order = Criteria::ASC) Order by the order_shipping_mode column
 * @method     ChildOrderQuery orderByTrackNumber($order = Criteria::ASC) Order by the order_track_number column
 * @method     ChildOrderQuery orderByPaymentMode($order = Criteria::ASC) Order by the order_payment_mode column
 * @method     ChildOrderQuery orderByPaymentCash($order = Criteria::ASC) Order by the order_payment_cash column
 * @method     ChildOrderQuery orderByPaymentCheque($order = Criteria::ASC) Order by the order_payment_cheque column
 * @method     ChildOrderQuery orderByPaymentTransfer($order = Criteria::ASC) Order by the order_payment_transfer column
 * @method     ChildOrderQuery orderByPaymentCard($order = Criteria::ASC) Order by the order_payment_card column
 * @method     ChildOrderQuery orderByPaymentPaypal($order = Criteria::ASC) Order by the order_payment_paypal column
 * @method     ChildOrderQuery orderByPaymentPayplug($order = Criteria::ASC) Order by the order_payment_payplug column
 * @method     ChildOrderQuery orderByPaymentLeft($order = Criteria::ASC) Order by the order_payment_left column
 * @method     ChildOrderQuery orderByTitle($order = Criteria::ASC) Order by the order_title column
 * @method     ChildOrderQuery orderByFirstname($order = Criteria::ASC) Order by the order_firstname column
 * @method     ChildOrderQuery orderByLastname($order = Criteria::ASC) Order by the order_lastname column
 * @method     ChildOrderQuery orderByAddress1($order = Criteria::ASC) Order by the order_address1 column
 * @method     ChildOrderQuery orderByAddress2($order = Criteria::ASC) Order by the order_address2 column
 * @method     ChildOrderQuery orderByPostalcode($order = Criteria::ASC) Order by the order_postalcode column
 * @method     ChildOrderQuery orderByCity($order = Criteria::ASC) Order by the order_city column
 * @method     ChildOrderQuery orderByCountry($order = Criteria::ASC) Order by the order_country column
 * @method     ChildOrderQuery orderByEmail($order = Criteria::ASC) Order by the order_email column
 * @method     ChildOrderQuery orderByPhone($order = Criteria::ASC) Order by the order_phone column
 * @method     ChildOrderQuery orderByComment($order = Criteria::ASC) Order by the order_comment column
 * @method     ChildOrderQuery orderByUtmz($order = Criteria::ASC) Order by the order_utmz column
 * @method     ChildOrderQuery orderByReferer($order = Criteria::ASC) Order by the order_referer column
 * @method     ChildOrderQuery orderByInsert($order = Criteria::ASC) Order by the order_insert column
 * @method     ChildOrderQuery orderByPaymentDate($order = Criteria::ASC) Order by the order_payment_date column
 * @method     ChildOrderQuery orderByShippingDate($order = Criteria::ASC) Order by the order_shipping_date column
 * @method     ChildOrderQuery orderByFollowupDate($order = Criteria::ASC) Order by the order_followup_date column
 * @method     ChildOrderQuery orderByConfirmationDate($order = Criteria::ASC) Order by the order_confirmation_date column
 * @method     ChildOrderQuery orderByCancelDate($order = Criteria::ASC) Order by the order_cancel_date column
 * @method     ChildOrderQuery orderByUpdate($order = Criteria::ASC) Order by the order_update column
 * @method     ChildOrderQuery orderByCreatedAt($order = Criteria::ASC) Order by the order_created column
 * @method     ChildOrderQuery orderByUpdatedAt($order = Criteria::ASC) Order by the order_updated column
 *
 * @method     ChildOrderQuery groupById() Group by the order_id column
 * @method     ChildOrderQuery groupBySlug() Group by the order_url column
 * @method     ChildOrderQuery groupBySiteId() Group by the site_id column
 * @method     ChildOrderQuery groupByUserId() Group by the user_id column
 * @method     ChildOrderQuery groupByCustomerId() Group by the customer_id column
 * @method     ChildOrderQuery groupBySellerId() Group by the seller_id column
 * @method     ChildOrderQuery groupByType() Group by the order_type column
 * @method     ChildOrderQuery groupByAsAGift() Group by the order_as_a_gift column
 * @method     ChildOrderQuery groupByGiftRecipient() Group by the order_gift_recipient column
 * @method     ChildOrderQuery groupByAmount() Group by the order_amount column
 * @method     ChildOrderQuery groupByDiscount() Group by the order_discount column
 * @method     ChildOrderQuery groupByAmountTobepaid() Group by the order_amount_tobepaid column
 * @method     ChildOrderQuery groupByShippingId() Group by the shipping_id column
 * @method     ChildOrderQuery groupByCountryId() Group by the country_id column
 * @method     ChildOrderQuery groupByShipping() Group by the order_shipping column
 * @method     ChildOrderQuery groupByShippingMode() Group by the order_shipping_mode column
 * @method     ChildOrderQuery groupByTrackNumber() Group by the order_track_number column
 * @method     ChildOrderQuery groupByPaymentMode() Group by the order_payment_mode column
 * @method     ChildOrderQuery groupByPaymentCash() Group by the order_payment_cash column
 * @method     ChildOrderQuery groupByPaymentCheque() Group by the order_payment_cheque column
 * @method     ChildOrderQuery groupByPaymentTransfer() Group by the order_payment_transfer column
 * @method     ChildOrderQuery groupByPaymentCard() Group by the order_payment_card column
 * @method     ChildOrderQuery groupByPaymentPaypal() Group by the order_payment_paypal column
 * @method     ChildOrderQuery groupByPaymentPayplug() Group by the order_payment_payplug column
 * @method     ChildOrderQuery groupByPaymentLeft() Group by the order_payment_left column
 * @method     ChildOrderQuery groupByTitle() Group by the order_title column
 * @method     ChildOrderQuery groupByFirstname() Group by the order_firstname column
 * @method     ChildOrderQuery groupByLastname() Group by the order_lastname column
 * @method     ChildOrderQuery groupByAddress1() Group by the order_address1 column
 * @method     ChildOrderQuery groupByAddress2() Group by the order_address2 column
 * @method     ChildOrderQuery groupByPostalcode() Group by the order_postalcode column
 * @method     ChildOrderQuery groupByCity() Group by the order_city column
 * @method     ChildOrderQuery groupByCountry() Group by the order_country column
 * @method     ChildOrderQuery groupByEmail() Group by the order_email column
 * @method     ChildOrderQuery groupByPhone() Group by the order_phone column
 * @method     ChildOrderQuery groupByComment() Group by the order_comment column
 * @method     ChildOrderQuery groupByUtmz() Group by the order_utmz column
 * @method     ChildOrderQuery groupByReferer() Group by the order_referer column
 * @method     ChildOrderQuery groupByInsert() Group by the order_insert column
 * @method     ChildOrderQuery groupByPaymentDate() Group by the order_payment_date column
 * @method     ChildOrderQuery groupByShippingDate() Group by the order_shipping_date column
 * @method     ChildOrderQuery groupByFollowupDate() Group by the order_followup_date column
 * @method     ChildOrderQuery groupByConfirmationDate() Group by the order_confirmation_date column
 * @method     ChildOrderQuery groupByCancelDate() Group by the order_cancel_date column
 * @method     ChildOrderQuery groupByUpdate() Group by the order_update column
 * @method     ChildOrderQuery groupByCreatedAt() Group by the order_created column
 * @method     ChildOrderQuery groupByUpdatedAt() Group by the order_updated column
 *
 * @method     ChildOrderQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildOrderQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildOrderQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildOrderQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildOrderQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildOrderQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildOrderQuery leftJoinSite($relationAlias = null) Adds a LEFT JOIN clause to the query using the Site relation
 * @method     ChildOrderQuery rightJoinSite($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Site relation
 * @method     ChildOrderQuery innerJoinSite($relationAlias = null) Adds a INNER JOIN clause to the query using the Site relation
 *
 * @method     ChildOrderQuery joinWithSite($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Site relation
 *
 * @method     ChildOrderQuery leftJoinWithSite() Adds a LEFT JOIN clause and with to the query using the Site relation
 * @method     ChildOrderQuery rightJoinWithSite() Adds a RIGHT JOIN clause and with to the query using the Site relation
 * @method     ChildOrderQuery innerJoinWithSite() Adds a INNER JOIN clause and with to the query using the Site relation
 *
 * @method     ChildOrderQuery leftJoinPayment($relationAlias = null) Adds a LEFT JOIN clause to the query using the Payment relation
 * @method     ChildOrderQuery rightJoinPayment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Payment relation
 * @method     ChildOrderQuery innerJoinPayment($relationAlias = null) Adds a INNER JOIN clause to the query using the Payment relation
 *
 * @method     ChildOrderQuery joinWithPayment($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Payment relation
 *
 * @method     ChildOrderQuery leftJoinWithPayment() Adds a LEFT JOIN clause and with to the query using the Payment relation
 * @method     ChildOrderQuery rightJoinWithPayment() Adds a RIGHT JOIN clause and with to the query using the Payment relation
 * @method     ChildOrderQuery innerJoinWithPayment() Adds a INNER JOIN clause and with to the query using the Payment relation
 *
 * @method     \Model\SiteQuery|\Model\PaymentQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildOrder|null findOne(?ConnectionInterface $con = null) Return the first ChildOrder matching the query
 * @method     ChildOrder findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildOrder matching the query, or a new ChildOrder object populated from the query conditions when no match is found
 *
 * @method     ChildOrder|null findOneById(int $order_id) Return the first ChildOrder filtered by the order_id column
 * @method     ChildOrder|null findOneBySlug(string $order_url) Return the first ChildOrder filtered by the order_url column
 * @method     ChildOrder|null findOneBySiteId(int $site_id) Return the first ChildOrder filtered by the site_id column
 * @method     ChildOrder|null findOneByUserId(int $user_id) Return the first ChildOrder filtered by the user_id column
 * @method     ChildOrder|null findOneByCustomerId(int $customer_id) Return the first ChildOrder filtered by the customer_id column
 * @method     ChildOrder|null findOneBySellerId(int $seller_id) Return the first ChildOrder filtered by the seller_id column
 * @method     ChildOrder|null findOneByType(string $order_type) Return the first ChildOrder filtered by the order_type column
 * @method     ChildOrder|null findOneByAsAGift(string $order_as_a_gift) Return the first ChildOrder filtered by the order_as_a_gift column
 * @method     ChildOrder|null findOneByGiftRecipient(int $order_gift_recipient) Return the first ChildOrder filtered by the order_gift_recipient column
 * @method     ChildOrder|null findOneByAmount(int $order_amount) Return the first ChildOrder filtered by the order_amount column
 * @method     ChildOrder|null findOneByDiscount(int $order_discount) Return the first ChildOrder filtered by the order_discount column
 * @method     ChildOrder|null findOneByAmountTobepaid(int $order_amount_tobepaid) Return the first ChildOrder filtered by the order_amount_tobepaid column
 * @method     ChildOrder|null findOneByShippingId(int $shipping_id) Return the first ChildOrder filtered by the shipping_id column
 * @method     ChildOrder|null findOneByCountryId(int $country_id) Return the first ChildOrder filtered by the country_id column
 * @method     ChildOrder|null findOneByShipping(int $order_shipping) Return the first ChildOrder filtered by the order_shipping column
 * @method     ChildOrder|null findOneByShippingMode(string $order_shipping_mode) Return the first ChildOrder filtered by the order_shipping_mode column
 * @method     ChildOrder|null findOneByTrackNumber(string $order_track_number) Return the first ChildOrder filtered by the order_track_number column
 * @method     ChildOrder|null findOneByPaymentMode(string $order_payment_mode) Return the first ChildOrder filtered by the order_payment_mode column
 * @method     ChildOrder|null findOneByPaymentCash(int $order_payment_cash) Return the first ChildOrder filtered by the order_payment_cash column
 * @method     ChildOrder|null findOneByPaymentCheque(int $order_payment_cheque) Return the first ChildOrder filtered by the order_payment_cheque column
 * @method     ChildOrder|null findOneByPaymentTransfer(int $order_payment_transfer) Return the first ChildOrder filtered by the order_payment_transfer column
 * @method     ChildOrder|null findOneByPaymentCard(int $order_payment_card) Return the first ChildOrder filtered by the order_payment_card column
 * @method     ChildOrder|null findOneByPaymentPaypal(int $order_payment_paypal) Return the first ChildOrder filtered by the order_payment_paypal column
 * @method     ChildOrder|null findOneByPaymentPayplug(int $order_payment_payplug) Return the first ChildOrder filtered by the order_payment_payplug column
 * @method     ChildOrder|null findOneByPaymentLeft(int $order_payment_left) Return the first ChildOrder filtered by the order_payment_left column
 * @method     ChildOrder|null findOneByTitle(string $order_title) Return the first ChildOrder filtered by the order_title column
 * @method     ChildOrder|null findOneByFirstname(string $order_firstname) Return the first ChildOrder filtered by the order_firstname column
 * @method     ChildOrder|null findOneByLastname(string $order_lastname) Return the first ChildOrder filtered by the order_lastname column
 * @method     ChildOrder|null findOneByAddress1(string $order_address1) Return the first ChildOrder filtered by the order_address1 column
 * @method     ChildOrder|null findOneByAddress2(string $order_address2) Return the first ChildOrder filtered by the order_address2 column
 * @method     ChildOrder|null findOneByPostalcode(string $order_postalcode) Return the first ChildOrder filtered by the order_postalcode column
 * @method     ChildOrder|null findOneByCity(string $order_city) Return the first ChildOrder filtered by the order_city column
 * @method     ChildOrder|null findOneByCountry(string $order_country) Return the first ChildOrder filtered by the order_country column
 * @method     ChildOrder|null findOneByEmail(string $order_email) Return the first ChildOrder filtered by the order_email column
 * @method     ChildOrder|null findOneByPhone(string $order_phone) Return the first ChildOrder filtered by the order_phone column
 * @method     ChildOrder|null findOneByComment(string $order_comment) Return the first ChildOrder filtered by the order_comment column
 * @method     ChildOrder|null findOneByUtmz(string $order_utmz) Return the first ChildOrder filtered by the order_utmz column
 * @method     ChildOrder|null findOneByReferer(string $order_referer) Return the first ChildOrder filtered by the order_referer column
 * @method     ChildOrder|null findOneByInsert(string $order_insert) Return the first ChildOrder filtered by the order_insert column
 * @method     ChildOrder|null findOneByPaymentDate(string $order_payment_date) Return the first ChildOrder filtered by the order_payment_date column
 * @method     ChildOrder|null findOneByShippingDate(string $order_shipping_date) Return the first ChildOrder filtered by the order_shipping_date column
 * @method     ChildOrder|null findOneByFollowupDate(string $order_followup_date) Return the first ChildOrder filtered by the order_followup_date column
 * @method     ChildOrder|null findOneByConfirmationDate(string $order_confirmation_date) Return the first ChildOrder filtered by the order_confirmation_date column
 * @method     ChildOrder|null findOneByCancelDate(string $order_cancel_date) Return the first ChildOrder filtered by the order_cancel_date column
 * @method     ChildOrder|null findOneByUpdate(string $order_update) Return the first ChildOrder filtered by the order_update column
 * @method     ChildOrder|null findOneByCreatedAt(string $order_created) Return the first ChildOrder filtered by the order_created column
 * @method     ChildOrder|null findOneByUpdatedAt(string $order_updated) Return the first ChildOrder filtered by the order_updated column
 *
 * @method     ChildOrder requirePk($key, ?ConnectionInterface $con = null) Return the ChildOrder by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOne(?ConnectionInterface $con = null) Return the first ChildOrder matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildOrder requireOneById(int $order_id) Return the first ChildOrder filtered by the order_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneBySlug(string $order_url) Return the first ChildOrder filtered by the order_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneBySiteId(int $site_id) Return the first ChildOrder filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByUserId(int $user_id) Return the first ChildOrder filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByCustomerId(int $customer_id) Return the first ChildOrder filtered by the customer_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneBySellerId(int $seller_id) Return the first ChildOrder filtered by the seller_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByType(string $order_type) Return the first ChildOrder filtered by the order_type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByAsAGift(string $order_as_a_gift) Return the first ChildOrder filtered by the order_as_a_gift column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByGiftRecipient(int $order_gift_recipient) Return the first ChildOrder filtered by the order_gift_recipient column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByAmount(int $order_amount) Return the first ChildOrder filtered by the order_amount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByDiscount(int $order_discount) Return the first ChildOrder filtered by the order_discount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByAmountTobepaid(int $order_amount_tobepaid) Return the first ChildOrder filtered by the order_amount_tobepaid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByShippingId(int $shipping_id) Return the first ChildOrder filtered by the shipping_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByCountryId(int $country_id) Return the first ChildOrder filtered by the country_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByShipping(int $order_shipping) Return the first ChildOrder filtered by the order_shipping column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByShippingMode(string $order_shipping_mode) Return the first ChildOrder filtered by the order_shipping_mode column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByTrackNumber(string $order_track_number) Return the first ChildOrder filtered by the order_track_number column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByPaymentMode(string $order_payment_mode) Return the first ChildOrder filtered by the order_payment_mode column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByPaymentCash(int $order_payment_cash) Return the first ChildOrder filtered by the order_payment_cash column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByPaymentCheque(int $order_payment_cheque) Return the first ChildOrder filtered by the order_payment_cheque column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByPaymentTransfer(int $order_payment_transfer) Return the first ChildOrder filtered by the order_payment_transfer column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByPaymentCard(int $order_payment_card) Return the first ChildOrder filtered by the order_payment_card column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByPaymentPaypal(int $order_payment_paypal) Return the first ChildOrder filtered by the order_payment_paypal column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByPaymentPayplug(int $order_payment_payplug) Return the first ChildOrder filtered by the order_payment_payplug column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByPaymentLeft(int $order_payment_left) Return the first ChildOrder filtered by the order_payment_left column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByTitle(string $order_title) Return the first ChildOrder filtered by the order_title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByFirstname(string $order_firstname) Return the first ChildOrder filtered by the order_firstname column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByLastname(string $order_lastname) Return the first ChildOrder filtered by the order_lastname column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByAddress1(string $order_address1) Return the first ChildOrder filtered by the order_address1 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByAddress2(string $order_address2) Return the first ChildOrder filtered by the order_address2 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByPostalcode(string $order_postalcode) Return the first ChildOrder filtered by the order_postalcode column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByCity(string $order_city) Return the first ChildOrder filtered by the order_city column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByCountry(string $order_country) Return the first ChildOrder filtered by the order_country column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByEmail(string $order_email) Return the first ChildOrder filtered by the order_email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByPhone(string $order_phone) Return the first ChildOrder filtered by the order_phone column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByComment(string $order_comment) Return the first ChildOrder filtered by the order_comment column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByUtmz(string $order_utmz) Return the first ChildOrder filtered by the order_utmz column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByReferer(string $order_referer) Return the first ChildOrder filtered by the order_referer column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByInsert(string $order_insert) Return the first ChildOrder filtered by the order_insert column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByPaymentDate(string $order_payment_date) Return the first ChildOrder filtered by the order_payment_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByShippingDate(string $order_shipping_date) Return the first ChildOrder filtered by the order_shipping_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByFollowupDate(string $order_followup_date) Return the first ChildOrder filtered by the order_followup_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByConfirmationDate(string $order_confirmation_date) Return the first ChildOrder filtered by the order_confirmation_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByCancelDate(string $order_cancel_date) Return the first ChildOrder filtered by the order_cancel_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByUpdate(string $order_update) Return the first ChildOrder filtered by the order_update column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByCreatedAt(string $order_created) Return the first ChildOrder filtered by the order_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByUpdatedAt(string $order_updated) Return the first ChildOrder filtered by the order_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildOrder[]|Collection find(?ConnectionInterface $con = null) Return ChildOrder objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildOrder> find(?ConnectionInterface $con = null) Return ChildOrder objects based on current ModelCriteria
 *
 * @method     ChildOrder[]|Collection findById(int|array<int> $order_id) Return ChildOrder objects filtered by the order_id column
 * @psalm-method Collection&\Traversable<ChildOrder> findById(int|array<int> $order_id) Return ChildOrder objects filtered by the order_id column
 * @method     ChildOrder[]|Collection findBySlug(string|array<string> $order_url) Return ChildOrder objects filtered by the order_url column
 * @psalm-method Collection&\Traversable<ChildOrder> findBySlug(string|array<string> $order_url) Return ChildOrder objects filtered by the order_url column
 * @method     ChildOrder[]|Collection findBySiteId(int|array<int> $site_id) Return ChildOrder objects filtered by the site_id column
 * @psalm-method Collection&\Traversable<ChildOrder> findBySiteId(int|array<int> $site_id) Return ChildOrder objects filtered by the site_id column
 * @method     ChildOrder[]|Collection findByUserId(int|array<int> $user_id) Return ChildOrder objects filtered by the user_id column
 * @psalm-method Collection&\Traversable<ChildOrder> findByUserId(int|array<int> $user_id) Return ChildOrder objects filtered by the user_id column
 * @method     ChildOrder[]|Collection findByCustomerId(int|array<int> $customer_id) Return ChildOrder objects filtered by the customer_id column
 * @psalm-method Collection&\Traversable<ChildOrder> findByCustomerId(int|array<int> $customer_id) Return ChildOrder objects filtered by the customer_id column
 * @method     ChildOrder[]|Collection findBySellerId(int|array<int> $seller_id) Return ChildOrder objects filtered by the seller_id column
 * @psalm-method Collection&\Traversable<ChildOrder> findBySellerId(int|array<int> $seller_id) Return ChildOrder objects filtered by the seller_id column
 * @method     ChildOrder[]|Collection findByType(string|array<string> $order_type) Return ChildOrder objects filtered by the order_type column
 * @psalm-method Collection&\Traversable<ChildOrder> findByType(string|array<string> $order_type) Return ChildOrder objects filtered by the order_type column
 * @method     ChildOrder[]|Collection findByAsAGift(string|array<string> $order_as_a_gift) Return ChildOrder objects filtered by the order_as_a_gift column
 * @psalm-method Collection&\Traversable<ChildOrder> findByAsAGift(string|array<string> $order_as_a_gift) Return ChildOrder objects filtered by the order_as_a_gift column
 * @method     ChildOrder[]|Collection findByGiftRecipient(int|array<int> $order_gift_recipient) Return ChildOrder objects filtered by the order_gift_recipient column
 * @psalm-method Collection&\Traversable<ChildOrder> findByGiftRecipient(int|array<int> $order_gift_recipient) Return ChildOrder objects filtered by the order_gift_recipient column
 * @method     ChildOrder[]|Collection findByAmount(int|array<int> $order_amount) Return ChildOrder objects filtered by the order_amount column
 * @psalm-method Collection&\Traversable<ChildOrder> findByAmount(int|array<int> $order_amount) Return ChildOrder objects filtered by the order_amount column
 * @method     ChildOrder[]|Collection findByDiscount(int|array<int> $order_discount) Return ChildOrder objects filtered by the order_discount column
 * @psalm-method Collection&\Traversable<ChildOrder> findByDiscount(int|array<int> $order_discount) Return ChildOrder objects filtered by the order_discount column
 * @method     ChildOrder[]|Collection findByAmountTobepaid(int|array<int> $order_amount_tobepaid) Return ChildOrder objects filtered by the order_amount_tobepaid column
 * @psalm-method Collection&\Traversable<ChildOrder> findByAmountTobepaid(int|array<int> $order_amount_tobepaid) Return ChildOrder objects filtered by the order_amount_tobepaid column
 * @method     ChildOrder[]|Collection findByShippingId(int|array<int> $shipping_id) Return ChildOrder objects filtered by the shipping_id column
 * @psalm-method Collection&\Traversable<ChildOrder> findByShippingId(int|array<int> $shipping_id) Return ChildOrder objects filtered by the shipping_id column
 * @method     ChildOrder[]|Collection findByCountryId(int|array<int> $country_id) Return ChildOrder objects filtered by the country_id column
 * @psalm-method Collection&\Traversable<ChildOrder> findByCountryId(int|array<int> $country_id) Return ChildOrder objects filtered by the country_id column
 * @method     ChildOrder[]|Collection findByShipping(int|array<int> $order_shipping) Return ChildOrder objects filtered by the order_shipping column
 * @psalm-method Collection&\Traversable<ChildOrder> findByShipping(int|array<int> $order_shipping) Return ChildOrder objects filtered by the order_shipping column
 * @method     ChildOrder[]|Collection findByShippingMode(string|array<string> $order_shipping_mode) Return ChildOrder objects filtered by the order_shipping_mode column
 * @psalm-method Collection&\Traversable<ChildOrder> findByShippingMode(string|array<string> $order_shipping_mode) Return ChildOrder objects filtered by the order_shipping_mode column
 * @method     ChildOrder[]|Collection findByTrackNumber(string|array<string> $order_track_number) Return ChildOrder objects filtered by the order_track_number column
 * @psalm-method Collection&\Traversable<ChildOrder> findByTrackNumber(string|array<string> $order_track_number) Return ChildOrder objects filtered by the order_track_number column
 * @method     ChildOrder[]|Collection findByPaymentMode(string|array<string> $order_payment_mode) Return ChildOrder objects filtered by the order_payment_mode column
 * @psalm-method Collection&\Traversable<ChildOrder> findByPaymentMode(string|array<string> $order_payment_mode) Return ChildOrder objects filtered by the order_payment_mode column
 * @method     ChildOrder[]|Collection findByPaymentCash(int|array<int> $order_payment_cash) Return ChildOrder objects filtered by the order_payment_cash column
 * @psalm-method Collection&\Traversable<ChildOrder> findByPaymentCash(int|array<int> $order_payment_cash) Return ChildOrder objects filtered by the order_payment_cash column
 * @method     ChildOrder[]|Collection findByPaymentCheque(int|array<int> $order_payment_cheque) Return ChildOrder objects filtered by the order_payment_cheque column
 * @psalm-method Collection&\Traversable<ChildOrder> findByPaymentCheque(int|array<int> $order_payment_cheque) Return ChildOrder objects filtered by the order_payment_cheque column
 * @method     ChildOrder[]|Collection findByPaymentTransfer(int|array<int> $order_payment_transfer) Return ChildOrder objects filtered by the order_payment_transfer column
 * @psalm-method Collection&\Traversable<ChildOrder> findByPaymentTransfer(int|array<int> $order_payment_transfer) Return ChildOrder objects filtered by the order_payment_transfer column
 * @method     ChildOrder[]|Collection findByPaymentCard(int|array<int> $order_payment_card) Return ChildOrder objects filtered by the order_payment_card column
 * @psalm-method Collection&\Traversable<ChildOrder> findByPaymentCard(int|array<int> $order_payment_card) Return ChildOrder objects filtered by the order_payment_card column
 * @method     ChildOrder[]|Collection findByPaymentPaypal(int|array<int> $order_payment_paypal) Return ChildOrder objects filtered by the order_payment_paypal column
 * @psalm-method Collection&\Traversable<ChildOrder> findByPaymentPaypal(int|array<int> $order_payment_paypal) Return ChildOrder objects filtered by the order_payment_paypal column
 * @method     ChildOrder[]|Collection findByPaymentPayplug(int|array<int> $order_payment_payplug) Return ChildOrder objects filtered by the order_payment_payplug column
 * @psalm-method Collection&\Traversable<ChildOrder> findByPaymentPayplug(int|array<int> $order_payment_payplug) Return ChildOrder objects filtered by the order_payment_payplug column
 * @method     ChildOrder[]|Collection findByPaymentLeft(int|array<int> $order_payment_left) Return ChildOrder objects filtered by the order_payment_left column
 * @psalm-method Collection&\Traversable<ChildOrder> findByPaymentLeft(int|array<int> $order_payment_left) Return ChildOrder objects filtered by the order_payment_left column
 * @method     ChildOrder[]|Collection findByTitle(string|array<string> $order_title) Return ChildOrder objects filtered by the order_title column
 * @psalm-method Collection&\Traversable<ChildOrder> findByTitle(string|array<string> $order_title) Return ChildOrder objects filtered by the order_title column
 * @method     ChildOrder[]|Collection findByFirstname(string|array<string> $order_firstname) Return ChildOrder objects filtered by the order_firstname column
 * @psalm-method Collection&\Traversable<ChildOrder> findByFirstname(string|array<string> $order_firstname) Return ChildOrder objects filtered by the order_firstname column
 * @method     ChildOrder[]|Collection findByLastname(string|array<string> $order_lastname) Return ChildOrder objects filtered by the order_lastname column
 * @psalm-method Collection&\Traversable<ChildOrder> findByLastname(string|array<string> $order_lastname) Return ChildOrder objects filtered by the order_lastname column
 * @method     ChildOrder[]|Collection findByAddress1(string|array<string> $order_address1) Return ChildOrder objects filtered by the order_address1 column
 * @psalm-method Collection&\Traversable<ChildOrder> findByAddress1(string|array<string> $order_address1) Return ChildOrder objects filtered by the order_address1 column
 * @method     ChildOrder[]|Collection findByAddress2(string|array<string> $order_address2) Return ChildOrder objects filtered by the order_address2 column
 * @psalm-method Collection&\Traversable<ChildOrder> findByAddress2(string|array<string> $order_address2) Return ChildOrder objects filtered by the order_address2 column
 * @method     ChildOrder[]|Collection findByPostalcode(string|array<string> $order_postalcode) Return ChildOrder objects filtered by the order_postalcode column
 * @psalm-method Collection&\Traversable<ChildOrder> findByPostalcode(string|array<string> $order_postalcode) Return ChildOrder objects filtered by the order_postalcode column
 * @method     ChildOrder[]|Collection findByCity(string|array<string> $order_city) Return ChildOrder objects filtered by the order_city column
 * @psalm-method Collection&\Traversable<ChildOrder> findByCity(string|array<string> $order_city) Return ChildOrder objects filtered by the order_city column
 * @method     ChildOrder[]|Collection findByCountry(string|array<string> $order_country) Return ChildOrder objects filtered by the order_country column
 * @psalm-method Collection&\Traversable<ChildOrder> findByCountry(string|array<string> $order_country) Return ChildOrder objects filtered by the order_country column
 * @method     ChildOrder[]|Collection findByEmail(string|array<string> $order_email) Return ChildOrder objects filtered by the order_email column
 * @psalm-method Collection&\Traversable<ChildOrder> findByEmail(string|array<string> $order_email) Return ChildOrder objects filtered by the order_email column
 * @method     ChildOrder[]|Collection findByPhone(string|array<string> $order_phone) Return ChildOrder objects filtered by the order_phone column
 * @psalm-method Collection&\Traversable<ChildOrder> findByPhone(string|array<string> $order_phone) Return ChildOrder objects filtered by the order_phone column
 * @method     ChildOrder[]|Collection findByComment(string|array<string> $order_comment) Return ChildOrder objects filtered by the order_comment column
 * @psalm-method Collection&\Traversable<ChildOrder> findByComment(string|array<string> $order_comment) Return ChildOrder objects filtered by the order_comment column
 * @method     ChildOrder[]|Collection findByUtmz(string|array<string> $order_utmz) Return ChildOrder objects filtered by the order_utmz column
 * @psalm-method Collection&\Traversable<ChildOrder> findByUtmz(string|array<string> $order_utmz) Return ChildOrder objects filtered by the order_utmz column
 * @method     ChildOrder[]|Collection findByReferer(string|array<string> $order_referer) Return ChildOrder objects filtered by the order_referer column
 * @psalm-method Collection&\Traversable<ChildOrder> findByReferer(string|array<string> $order_referer) Return ChildOrder objects filtered by the order_referer column
 * @method     ChildOrder[]|Collection findByInsert(string|array<string> $order_insert) Return ChildOrder objects filtered by the order_insert column
 * @psalm-method Collection&\Traversable<ChildOrder> findByInsert(string|array<string> $order_insert) Return ChildOrder objects filtered by the order_insert column
 * @method     ChildOrder[]|Collection findByPaymentDate(string|array<string> $order_payment_date) Return ChildOrder objects filtered by the order_payment_date column
 * @psalm-method Collection&\Traversable<ChildOrder> findByPaymentDate(string|array<string> $order_payment_date) Return ChildOrder objects filtered by the order_payment_date column
 * @method     ChildOrder[]|Collection findByShippingDate(string|array<string> $order_shipping_date) Return ChildOrder objects filtered by the order_shipping_date column
 * @psalm-method Collection&\Traversable<ChildOrder> findByShippingDate(string|array<string> $order_shipping_date) Return ChildOrder objects filtered by the order_shipping_date column
 * @method     ChildOrder[]|Collection findByFollowupDate(string|array<string> $order_followup_date) Return ChildOrder objects filtered by the order_followup_date column
 * @psalm-method Collection&\Traversable<ChildOrder> findByFollowupDate(string|array<string> $order_followup_date) Return ChildOrder objects filtered by the order_followup_date column
 * @method     ChildOrder[]|Collection findByConfirmationDate(string|array<string> $order_confirmation_date) Return ChildOrder objects filtered by the order_confirmation_date column
 * @psalm-method Collection&\Traversable<ChildOrder> findByConfirmationDate(string|array<string> $order_confirmation_date) Return ChildOrder objects filtered by the order_confirmation_date column
 * @method     ChildOrder[]|Collection findByCancelDate(string|array<string> $order_cancel_date) Return ChildOrder objects filtered by the order_cancel_date column
 * @psalm-method Collection&\Traversable<ChildOrder> findByCancelDate(string|array<string> $order_cancel_date) Return ChildOrder objects filtered by the order_cancel_date column
 * @method     ChildOrder[]|Collection findByUpdate(string|array<string> $order_update) Return ChildOrder objects filtered by the order_update column
 * @psalm-method Collection&\Traversable<ChildOrder> findByUpdate(string|array<string> $order_update) Return ChildOrder objects filtered by the order_update column
 * @method     ChildOrder[]|Collection findByCreatedAt(string|array<string> $order_created) Return ChildOrder objects filtered by the order_created column
 * @psalm-method Collection&\Traversable<ChildOrder> findByCreatedAt(string|array<string> $order_created) Return ChildOrder objects filtered by the order_created column
 * @method     ChildOrder[]|Collection findByUpdatedAt(string|array<string> $order_updated) Return ChildOrder objects filtered by the order_updated column
 * @psalm-method Collection&\Traversable<ChildOrder> findByUpdatedAt(string|array<string> $order_updated) Return ChildOrder objects filtered by the order_updated column
 *
 * @method     ChildOrder[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildOrder> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class OrderQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\OrderQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Order', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildOrderQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildOrderQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildOrderQuery) {
            return $criteria;
        }
        $query = new ChildOrderQuery();
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
     * @return ChildOrder|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(OrderTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = OrderTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildOrder A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT order_id, order_url, site_id, user_id, customer_id, seller_id, order_type, order_as_a_gift, order_gift_recipient, order_amount, order_discount, order_amount_tobepaid, shipping_id, country_id, order_shipping, order_shipping_mode, order_track_number, order_payment_mode, order_payment_cash, order_payment_cheque, order_payment_transfer, order_payment_card, order_payment_paypal, order_payment_payplug, order_payment_left, order_title, order_firstname, order_lastname, order_address1, order_address2, order_postalcode, order_city, order_country, order_email, order_phone, order_comment, order_utmz, order_referer, order_insert, order_payment_date, order_shipping_date, order_followup_date, order_confirmation_date, order_cancel_date, order_update, order_created, order_updated FROM orders WHERE order_id = :p0';
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
            /** @var ChildOrder $obj */
            $obj = new ChildOrder();
            $obj->hydrate($row);
            OrderTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildOrder|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(OrderTableMap::COL_ORDER_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(OrderTableMap::COL_ORDER_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the order_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE order_id = 1234
     * $query->filterById(array(12, 34)); // WHERE order_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE order_id > 12
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
                $this->addUsingAlias(OrderTableMap::COL_ORDER_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_url column
     *
     * Example usage:
     * <code>
     * $query->filterBySlug('fooValue');   // WHERE order_url = 'fooValue'
     * $query->filterBySlug('%fooValue%', Criteria::LIKE); // WHERE order_url LIKE '%fooValue%'
     * $query->filterBySlug(['foo', 'bar']); // WHERE order_url IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $slug The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySlug($slug = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($slug)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_URL, $slug, $comparison);

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
     * @see       filterBySite()
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
                $this->addUsingAlias(OrderTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_SITE_ID, $siteId, $comparison);

        return $this;
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
     * @param mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUserId($userId = null, ?string $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_USER_ID, $userId, $comparison);

        return $this;
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
     * @param mixed $customerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCustomerId($customerId = null, ?string $comparison = null)
    {
        if (is_array($customerId)) {
            $useMinMax = false;
            if (isset($customerId['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_CUSTOMER_ID, $customerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($customerId['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_CUSTOMER_ID, $customerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_CUSTOMER_ID, $customerId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the seller_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySellerId(1234); // WHERE seller_id = 1234
     * $query->filterBySellerId(array(12, 34)); // WHERE seller_id IN (12, 34)
     * $query->filterBySellerId(array('min' => 12)); // WHERE seller_id > 12
     * </code>
     *
     * @param mixed $sellerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySellerId($sellerId = null, ?string $comparison = null)
    {
        if (is_array($sellerId)) {
            $useMinMax = false;
            if (isset($sellerId['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_SELLER_ID, $sellerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sellerId['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_SELLER_ID, $sellerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_SELLER_ID, $sellerId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_type column
     *
     * Example usage:
     * <code>
     * $query->filterByType('fooValue');   // WHERE order_type = 'fooValue'
     * $query->filterByType('%fooValue%', Criteria::LIKE); // WHERE order_type LIKE '%fooValue%'
     * $query->filterByType(['foo', 'bar']); // WHERE order_type IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $type The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByType($type = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($type)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_TYPE, $type, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_as_a_gift column
     *
     * Example usage:
     * <code>
     * $query->filterByAsAGift('fooValue');   // WHERE order_as_a_gift = 'fooValue'
     * $query->filterByAsAGift('%fooValue%', Criteria::LIKE); // WHERE order_as_a_gift LIKE '%fooValue%'
     * $query->filterByAsAGift(['foo', 'bar']); // WHERE order_as_a_gift IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $asAGift The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAsAGift($asAGift = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($asAGift)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_AS_A_GIFT, $asAGift, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_gift_recipient column
     *
     * Example usage:
     * <code>
     * $query->filterByGiftRecipient(1234); // WHERE order_gift_recipient = 1234
     * $query->filterByGiftRecipient(array(12, 34)); // WHERE order_gift_recipient IN (12, 34)
     * $query->filterByGiftRecipient(array('min' => 12)); // WHERE order_gift_recipient > 12
     * </code>
     *
     * @param mixed $giftRecipient The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByGiftRecipient($giftRecipient = null, ?string $comparison = null)
    {
        if (is_array($giftRecipient)) {
            $useMinMax = false;
            if (isset($giftRecipient['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_GIFT_RECIPIENT, $giftRecipient['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($giftRecipient['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_GIFT_RECIPIENT, $giftRecipient['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_GIFT_RECIPIENT, $giftRecipient, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_amount column
     *
     * Example usage:
     * <code>
     * $query->filterByAmount(1234); // WHERE order_amount = 1234
     * $query->filterByAmount(array(12, 34)); // WHERE order_amount IN (12, 34)
     * $query->filterByAmount(array('min' => 12)); // WHERE order_amount > 12
     * </code>
     *
     * @param mixed $amount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAmount($amount = null, ?string $comparison = null)
    {
        if (is_array($amount)) {
            $useMinMax = false;
            if (isset($amount['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_AMOUNT, $amount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($amount['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_AMOUNT, $amount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_AMOUNT, $amount, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_discount column
     *
     * Example usage:
     * <code>
     * $query->filterByDiscount(1234); // WHERE order_discount = 1234
     * $query->filterByDiscount(array(12, 34)); // WHERE order_discount IN (12, 34)
     * $query->filterByDiscount(array('min' => 12)); // WHERE order_discount > 12
     * </code>
     *
     * @param mixed $discount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDiscount($discount = null, ?string $comparison = null)
    {
        if (is_array($discount)) {
            $useMinMax = false;
            if (isset($discount['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_DISCOUNT, $discount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($discount['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_DISCOUNT, $discount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_DISCOUNT, $discount, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_amount_tobepaid column
     *
     * Example usage:
     * <code>
     * $query->filterByAmountTobepaid(1234); // WHERE order_amount_tobepaid = 1234
     * $query->filterByAmountTobepaid(array(12, 34)); // WHERE order_amount_tobepaid IN (12, 34)
     * $query->filterByAmountTobepaid(array('min' => 12)); // WHERE order_amount_tobepaid > 12
     * </code>
     *
     * @param mixed $amountTobepaid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAmountTobepaid($amountTobepaid = null, ?string $comparison = null)
    {
        if (is_array($amountTobepaid)) {
            $useMinMax = false;
            if (isset($amountTobepaid['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_AMOUNT_TOBEPAID, $amountTobepaid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($amountTobepaid['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_AMOUNT_TOBEPAID, $amountTobepaid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_AMOUNT_TOBEPAID, $amountTobepaid, $comparison);

        return $this;
    }

    /**
     * Filter the query on the shipping_id column
     *
     * Example usage:
     * <code>
     * $query->filterByShippingId(1234); // WHERE shipping_id = 1234
     * $query->filterByShippingId(array(12, 34)); // WHERE shipping_id IN (12, 34)
     * $query->filterByShippingId(array('min' => 12)); // WHERE shipping_id > 12
     * </code>
     *
     * @param mixed $shippingId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByShippingId($shippingId = null, ?string $comparison = null)
    {
        if (is_array($shippingId)) {
            $useMinMax = false;
            if (isset($shippingId['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_SHIPPING_ID, $shippingId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($shippingId['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_SHIPPING_ID, $shippingId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_SHIPPING_ID, $shippingId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the country_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCountryId(1234); // WHERE country_id = 1234
     * $query->filterByCountryId(array(12, 34)); // WHERE country_id IN (12, 34)
     * $query->filterByCountryId(array('min' => 12)); // WHERE country_id > 12
     * </code>
     *
     * @param mixed $countryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCountryId($countryId = null, ?string $comparison = null)
    {
        if (is_array($countryId)) {
            $useMinMax = false;
            if (isset($countryId['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_COUNTRY_ID, $countryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($countryId['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_COUNTRY_ID, $countryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_COUNTRY_ID, $countryId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_shipping column
     *
     * Example usage:
     * <code>
     * $query->filterByShipping(1234); // WHERE order_shipping = 1234
     * $query->filterByShipping(array(12, 34)); // WHERE order_shipping IN (12, 34)
     * $query->filterByShipping(array('min' => 12)); // WHERE order_shipping > 12
     * </code>
     *
     * @param mixed $shipping The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByShipping($shipping = null, ?string $comparison = null)
    {
        if (is_array($shipping)) {
            $useMinMax = false;
            if (isset($shipping['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_SHIPPING, $shipping['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($shipping['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_SHIPPING, $shipping['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_SHIPPING, $shipping, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_shipping_mode column
     *
     * Example usage:
     * <code>
     * $query->filterByShippingMode('fooValue');   // WHERE order_shipping_mode = 'fooValue'
     * $query->filterByShippingMode('%fooValue%', Criteria::LIKE); // WHERE order_shipping_mode LIKE '%fooValue%'
     * $query->filterByShippingMode(['foo', 'bar']); // WHERE order_shipping_mode IN ('foo', 'bar')
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

        $this->addUsingAlias(OrderTableMap::COL_ORDER_SHIPPING_MODE, $shippingMode, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_track_number column
     *
     * Example usage:
     * <code>
     * $query->filterByTrackNumber('fooValue');   // WHERE order_track_number = 'fooValue'
     * $query->filterByTrackNumber('%fooValue%', Criteria::LIKE); // WHERE order_track_number LIKE '%fooValue%'
     * $query->filterByTrackNumber(['foo', 'bar']); // WHERE order_track_number IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $trackNumber The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTrackNumber($trackNumber = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($trackNumber)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_TRACK_NUMBER, $trackNumber, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_payment_mode column
     *
     * Example usage:
     * <code>
     * $query->filterByPaymentMode('fooValue');   // WHERE order_payment_mode = 'fooValue'
     * $query->filterByPaymentMode('%fooValue%', Criteria::LIKE); // WHERE order_payment_mode LIKE '%fooValue%'
     * $query->filterByPaymentMode(['foo', 'bar']); // WHERE order_payment_mode IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $paymentMode The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPaymentMode($paymentMode = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($paymentMode)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_MODE, $paymentMode, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_payment_cash column
     *
     * Example usage:
     * <code>
     * $query->filterByPaymentCash(1234); // WHERE order_payment_cash = 1234
     * $query->filterByPaymentCash(array(12, 34)); // WHERE order_payment_cash IN (12, 34)
     * $query->filterByPaymentCash(array('min' => 12)); // WHERE order_payment_cash > 12
     * </code>
     *
     * @param mixed $paymentCash The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPaymentCash($paymentCash = null, ?string $comparison = null)
    {
        if (is_array($paymentCash)) {
            $useMinMax = false;
            if (isset($paymentCash['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_CASH, $paymentCash['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paymentCash['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_CASH, $paymentCash['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_CASH, $paymentCash, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_payment_cheque column
     *
     * Example usage:
     * <code>
     * $query->filterByPaymentCheque(1234); // WHERE order_payment_cheque = 1234
     * $query->filterByPaymentCheque(array(12, 34)); // WHERE order_payment_cheque IN (12, 34)
     * $query->filterByPaymentCheque(array('min' => 12)); // WHERE order_payment_cheque > 12
     * </code>
     *
     * @param mixed $paymentCheque The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPaymentCheque($paymentCheque = null, ?string $comparison = null)
    {
        if (is_array($paymentCheque)) {
            $useMinMax = false;
            if (isset($paymentCheque['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_CHEQUE, $paymentCheque['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paymentCheque['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_CHEQUE, $paymentCheque['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_CHEQUE, $paymentCheque, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_payment_transfer column
     *
     * Example usage:
     * <code>
     * $query->filterByPaymentTransfer(1234); // WHERE order_payment_transfer = 1234
     * $query->filterByPaymentTransfer(array(12, 34)); // WHERE order_payment_transfer IN (12, 34)
     * $query->filterByPaymentTransfer(array('min' => 12)); // WHERE order_payment_transfer > 12
     * </code>
     *
     * @param mixed $paymentTransfer The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPaymentTransfer($paymentTransfer = null, ?string $comparison = null)
    {
        if (is_array($paymentTransfer)) {
            $useMinMax = false;
            if (isset($paymentTransfer['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_TRANSFER, $paymentTransfer['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paymentTransfer['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_TRANSFER, $paymentTransfer['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_TRANSFER, $paymentTransfer, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_payment_card column
     *
     * Example usage:
     * <code>
     * $query->filterByPaymentCard(1234); // WHERE order_payment_card = 1234
     * $query->filterByPaymentCard(array(12, 34)); // WHERE order_payment_card IN (12, 34)
     * $query->filterByPaymentCard(array('min' => 12)); // WHERE order_payment_card > 12
     * </code>
     *
     * @param mixed $paymentCard The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPaymentCard($paymentCard = null, ?string $comparison = null)
    {
        if (is_array($paymentCard)) {
            $useMinMax = false;
            if (isset($paymentCard['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_CARD, $paymentCard['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paymentCard['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_CARD, $paymentCard['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_CARD, $paymentCard, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_payment_paypal column
     *
     * Example usage:
     * <code>
     * $query->filterByPaymentPaypal(1234); // WHERE order_payment_paypal = 1234
     * $query->filterByPaymentPaypal(array(12, 34)); // WHERE order_payment_paypal IN (12, 34)
     * $query->filterByPaymentPaypal(array('min' => 12)); // WHERE order_payment_paypal > 12
     * </code>
     *
     * @param mixed $paymentPaypal The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPaymentPaypal($paymentPaypal = null, ?string $comparison = null)
    {
        if (is_array($paymentPaypal)) {
            $useMinMax = false;
            if (isset($paymentPaypal['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_PAYPAL, $paymentPaypal['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paymentPaypal['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_PAYPAL, $paymentPaypal['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_PAYPAL, $paymentPaypal, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_payment_payplug column
     *
     * Example usage:
     * <code>
     * $query->filterByPaymentPayplug(1234); // WHERE order_payment_payplug = 1234
     * $query->filterByPaymentPayplug(array(12, 34)); // WHERE order_payment_payplug IN (12, 34)
     * $query->filterByPaymentPayplug(array('min' => 12)); // WHERE order_payment_payplug > 12
     * </code>
     *
     * @param mixed $paymentPayplug The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPaymentPayplug($paymentPayplug = null, ?string $comparison = null)
    {
        if (is_array($paymentPayplug)) {
            $useMinMax = false;
            if (isset($paymentPayplug['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_PAYPLUG, $paymentPayplug['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paymentPayplug['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_PAYPLUG, $paymentPayplug['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_PAYPLUG, $paymentPayplug, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_payment_left column
     *
     * Example usage:
     * <code>
     * $query->filterByPaymentLeft(1234); // WHERE order_payment_left = 1234
     * $query->filterByPaymentLeft(array(12, 34)); // WHERE order_payment_left IN (12, 34)
     * $query->filterByPaymentLeft(array('min' => 12)); // WHERE order_payment_left > 12
     * </code>
     *
     * @param mixed $paymentLeft The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPaymentLeft($paymentLeft = null, ?string $comparison = null)
    {
        if (is_array($paymentLeft)) {
            $useMinMax = false;
            if (isset($paymentLeft['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_LEFT, $paymentLeft['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paymentLeft['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_LEFT, $paymentLeft['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_LEFT, $paymentLeft, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE order_title = 'fooValue'
     * $query->filterByTitle('%fooValue%', Criteria::LIKE); // WHERE order_title LIKE '%fooValue%'
     * $query->filterByTitle(['foo', 'bar']); // WHERE order_title IN ('foo', 'bar')
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

        $this->addUsingAlias(OrderTableMap::COL_ORDER_TITLE, $title, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_firstname column
     *
     * Example usage:
     * <code>
     * $query->filterByFirstname('fooValue');   // WHERE order_firstname = 'fooValue'
     * $query->filterByFirstname('%fooValue%', Criteria::LIKE); // WHERE order_firstname LIKE '%fooValue%'
     * $query->filterByFirstname(['foo', 'bar']); // WHERE order_firstname IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $firstname The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFirstname($firstname = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($firstname)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_FIRSTNAME, $firstname, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_lastname column
     *
     * Example usage:
     * <code>
     * $query->filterByLastname('fooValue');   // WHERE order_lastname = 'fooValue'
     * $query->filterByLastname('%fooValue%', Criteria::LIKE); // WHERE order_lastname LIKE '%fooValue%'
     * $query->filterByLastname(['foo', 'bar']); // WHERE order_lastname IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $lastname The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByLastname($lastname = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lastname)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_LASTNAME, $lastname, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_address1 column
     *
     * Example usage:
     * <code>
     * $query->filterByAddress1('fooValue');   // WHERE order_address1 = 'fooValue'
     * $query->filterByAddress1('%fooValue%', Criteria::LIKE); // WHERE order_address1 LIKE '%fooValue%'
     * $query->filterByAddress1(['foo', 'bar']); // WHERE order_address1 IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $address1 The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAddress1($address1 = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($address1)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_ADDRESS1, $address1, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_address2 column
     *
     * Example usage:
     * <code>
     * $query->filterByAddress2('fooValue');   // WHERE order_address2 = 'fooValue'
     * $query->filterByAddress2('%fooValue%', Criteria::LIKE); // WHERE order_address2 LIKE '%fooValue%'
     * $query->filterByAddress2(['foo', 'bar']); // WHERE order_address2 IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $address2 The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAddress2($address2 = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($address2)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_ADDRESS2, $address2, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_postalcode column
     *
     * Example usage:
     * <code>
     * $query->filterByPostalcode('fooValue');   // WHERE order_postalcode = 'fooValue'
     * $query->filterByPostalcode('%fooValue%', Criteria::LIKE); // WHERE order_postalcode LIKE '%fooValue%'
     * $query->filterByPostalcode(['foo', 'bar']); // WHERE order_postalcode IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $postalcode The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPostalcode($postalcode = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($postalcode)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_POSTALCODE, $postalcode, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_city column
     *
     * Example usage:
     * <code>
     * $query->filterByCity('fooValue');   // WHERE order_city = 'fooValue'
     * $query->filterByCity('%fooValue%', Criteria::LIKE); // WHERE order_city LIKE '%fooValue%'
     * $query->filterByCity(['foo', 'bar']); // WHERE order_city IN ('foo', 'bar')
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

        $this->addUsingAlias(OrderTableMap::COL_ORDER_CITY, $city, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_country column
     *
     * Example usage:
     * <code>
     * $query->filterByCountry('fooValue');   // WHERE order_country = 'fooValue'
     * $query->filterByCountry('%fooValue%', Criteria::LIKE); // WHERE order_country LIKE '%fooValue%'
     * $query->filterByCountry(['foo', 'bar']); // WHERE order_country IN ('foo', 'bar')
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

        $this->addUsingAlias(OrderTableMap::COL_ORDER_COUNTRY, $country, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE order_email = 'fooValue'
     * $query->filterByEmail('%fooValue%', Criteria::LIKE); // WHERE order_email LIKE '%fooValue%'
     * $query->filterByEmail(['foo', 'bar']); // WHERE order_email IN ('foo', 'bar')
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

        $this->addUsingAlias(OrderTableMap::COL_ORDER_EMAIL, $email, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_phone column
     *
     * Example usage:
     * <code>
     * $query->filterByPhone('fooValue');   // WHERE order_phone = 'fooValue'
     * $query->filterByPhone('%fooValue%', Criteria::LIKE); // WHERE order_phone LIKE '%fooValue%'
     * $query->filterByPhone(['foo', 'bar']); // WHERE order_phone IN ('foo', 'bar')
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

        $this->addUsingAlias(OrderTableMap::COL_ORDER_PHONE, $phone, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_comment column
     *
     * Example usage:
     * <code>
     * $query->filterByComment('fooValue');   // WHERE order_comment = 'fooValue'
     * $query->filterByComment('%fooValue%', Criteria::LIKE); // WHERE order_comment LIKE '%fooValue%'
     * $query->filterByComment(['foo', 'bar']); // WHERE order_comment IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $comment The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByComment($comment = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($comment)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_COMMENT, $comment, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_utmz column
     *
     * Example usage:
     * <code>
     * $query->filterByUtmz('fooValue');   // WHERE order_utmz = 'fooValue'
     * $query->filterByUtmz('%fooValue%', Criteria::LIKE); // WHERE order_utmz LIKE '%fooValue%'
     * $query->filterByUtmz(['foo', 'bar']); // WHERE order_utmz IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $utmz The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUtmz($utmz = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($utmz)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_UTMZ, $utmz, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_referer column
     *
     * Example usage:
     * <code>
     * $query->filterByReferer('fooValue');   // WHERE order_referer = 'fooValue'
     * $query->filterByReferer('%fooValue%', Criteria::LIKE); // WHERE order_referer LIKE '%fooValue%'
     * $query->filterByReferer(['foo', 'bar']); // WHERE order_referer IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $referer The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByReferer($referer = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($referer)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_REFERER, $referer, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_insert column
     *
     * Example usage:
     * <code>
     * $query->filterByInsert('2011-03-14'); // WHERE order_insert = '2011-03-14'
     * $query->filterByInsert('now'); // WHERE order_insert = '2011-03-14'
     * $query->filterByInsert(array('max' => 'yesterday')); // WHERE order_insert > '2011-03-13'
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
                $this->addUsingAlias(OrderTableMap::COL_ORDER_INSERT, $insert['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($insert['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_INSERT, $insert['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_INSERT, $insert, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_payment_date column
     *
     * Example usage:
     * <code>
     * $query->filterByPaymentDate('2011-03-14'); // WHERE order_payment_date = '2011-03-14'
     * $query->filterByPaymentDate('now'); // WHERE order_payment_date = '2011-03-14'
     * $query->filterByPaymentDate(array('max' => 'yesterday')); // WHERE order_payment_date > '2011-03-13'
     * </code>
     *
     * @param mixed $paymentDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPaymentDate($paymentDate = null, ?string $comparison = null)
    {
        if (is_array($paymentDate)) {
            $useMinMax = false;
            if (isset($paymentDate['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_DATE, $paymentDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paymentDate['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_DATE, $paymentDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_DATE, $paymentDate, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_shipping_date column
     *
     * Example usage:
     * <code>
     * $query->filterByShippingDate('2011-03-14'); // WHERE order_shipping_date = '2011-03-14'
     * $query->filterByShippingDate('now'); // WHERE order_shipping_date = '2011-03-14'
     * $query->filterByShippingDate(array('max' => 'yesterday')); // WHERE order_shipping_date > '2011-03-13'
     * </code>
     *
     * @param mixed $shippingDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByShippingDate($shippingDate = null, ?string $comparison = null)
    {
        if (is_array($shippingDate)) {
            $useMinMax = false;
            if (isset($shippingDate['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_SHIPPING_DATE, $shippingDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($shippingDate['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_SHIPPING_DATE, $shippingDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_SHIPPING_DATE, $shippingDate, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_followup_date column
     *
     * Example usage:
     * <code>
     * $query->filterByFollowupDate('2011-03-14'); // WHERE order_followup_date = '2011-03-14'
     * $query->filterByFollowupDate('now'); // WHERE order_followup_date = '2011-03-14'
     * $query->filterByFollowupDate(array('max' => 'yesterday')); // WHERE order_followup_date > '2011-03-13'
     * </code>
     *
     * @param mixed $followupDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFollowupDate($followupDate = null, ?string $comparison = null)
    {
        if (is_array($followupDate)) {
            $useMinMax = false;
            if (isset($followupDate['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_FOLLOWUP_DATE, $followupDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($followupDate['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_FOLLOWUP_DATE, $followupDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_FOLLOWUP_DATE, $followupDate, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_confirmation_date column
     *
     * Example usage:
     * <code>
     * $query->filterByConfirmationDate('2011-03-14'); // WHERE order_confirmation_date = '2011-03-14'
     * $query->filterByConfirmationDate('now'); // WHERE order_confirmation_date = '2011-03-14'
     * $query->filterByConfirmationDate(array('max' => 'yesterday')); // WHERE order_confirmation_date > '2011-03-13'
     * </code>
     *
     * @param mixed $confirmationDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByConfirmationDate($confirmationDate = null, ?string $comparison = null)
    {
        if (is_array($confirmationDate)) {
            $useMinMax = false;
            if (isset($confirmationDate['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_CONFIRMATION_DATE, $confirmationDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($confirmationDate['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_CONFIRMATION_DATE, $confirmationDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_CONFIRMATION_DATE, $confirmationDate, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_cancel_date column
     *
     * Example usage:
     * <code>
     * $query->filterByCancelDate('2011-03-14'); // WHERE order_cancel_date = '2011-03-14'
     * $query->filterByCancelDate('now'); // WHERE order_cancel_date = '2011-03-14'
     * $query->filterByCancelDate(array('max' => 'yesterday')); // WHERE order_cancel_date > '2011-03-13'
     * </code>
     *
     * @param mixed $cancelDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCancelDate($cancelDate = null, ?string $comparison = null)
    {
        if (is_array($cancelDate)) {
            $useMinMax = false;
            if (isset($cancelDate['min'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_CANCEL_DATE, $cancelDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($cancelDate['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_CANCEL_DATE, $cancelDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_CANCEL_DATE, $cancelDate, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_update column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdate('2011-03-14'); // WHERE order_update = '2011-03-14'
     * $query->filterByUpdate('now'); // WHERE order_update = '2011-03-14'
     * $query->filterByUpdate(array('max' => 'yesterday')); // WHERE order_update > '2011-03-13'
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
                $this->addUsingAlias(OrderTableMap::COL_ORDER_UPDATE, $update['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($update['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_UPDATE, $update['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_UPDATE, $update, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE order_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE order_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE order_created > '2011-03-13'
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
                $this->addUsingAlias(OrderTableMap::COL_ORDER_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_CREATED, $createdAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the order_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE order_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE order_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE order_updated > '2011-03-13'
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
                $this->addUsingAlias(OrderTableMap::COL_ORDER_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(OrderTableMap::COL_ORDER_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(OrderTableMap::COL_ORDER_UPDATED, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \Model\Site object
     *
     * @param \Model\Site|ObjectCollection $site The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySite($site, ?string $comparison = null)
    {
        if ($site instanceof \Model\Site) {
            return $this
                ->addUsingAlias(OrderTableMap::COL_SITE_ID, $site->getId(), $comparison);
        } elseif ($site instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(OrderTableMap::COL_SITE_ID, $site->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterBySite() only accepts arguments of type \Model\Site or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Site relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinSite(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Site');

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
            $this->addJoinObject($join, 'Site');
        }

        return $this;
    }

    /**
     * Use the Site relation Site object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\SiteQuery A secondary query class using the current class as primary query
     */
    public function useSiteQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSite($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Site', '\Model\SiteQuery');
    }

    /**
     * Use the Site relation Site object
     *
     * @param callable(\Model\SiteQuery):\Model\SiteQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withSiteQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useSiteQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Site table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\SiteQuery The inner query object of the EXISTS statement
     */
    public function useSiteExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\SiteQuery */
        $q = $this->useExistsQuery('Site', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Site table for a NOT EXISTS query.
     *
     * @see useSiteExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\SiteQuery The inner query object of the NOT EXISTS statement
     */
    public function useSiteNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\SiteQuery */
        $q = $this->useExistsQuery('Site', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Site table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\SiteQuery The inner query object of the IN statement
     */
    public function useInSiteQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\SiteQuery */
        $q = $this->useInQuery('Site', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Site table for a NOT IN query.
     *
     * @see useSiteInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\SiteQuery The inner query object of the NOT IN statement
     */
    public function useNotInSiteQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\SiteQuery */
        $q = $this->useInQuery('Site', $modelAlias, $queryClass, 'NOT IN');
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
                ->addUsingAlias(OrderTableMap::COL_ORDER_ID, $payment->getOrderId(), $comparison);

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
     * Exclude object from result
     *
     * @param ChildOrder $order Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($order = null)
    {
        if ($order) {
            $this->addUsingAlias(OrderTableMap::COL_ORDER_ID, $order->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the orders table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(OrderTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            OrderTableMap::clearInstancePool();
            OrderTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(OrderTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(OrderTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            OrderTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            OrderTableMap::clearRelatedInstancePool();

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
        $this->addUsingAlias(OrderTableMap::COL_ORDER_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(OrderTableMap::COL_ORDER_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(OrderTableMap::COL_ORDER_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(OrderTableMap::COL_ORDER_CREATED);

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
        $this->addUsingAlias(OrderTableMap::COL_ORDER_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(OrderTableMap::COL_ORDER_CREATED);

        return $this;
    }

}
