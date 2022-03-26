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
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'orders' table.
 *
 *
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
 * @method     ChildOrderQuery orderByUtmSource($order = Criteria::ASC) Order by the order_utm_source column
 * @method     ChildOrderQuery orderByUtmCampaign($order = Criteria::ASC) Order by the order_utm_campaign column
 * @method     ChildOrderQuery orderByUtmMedium($order = Criteria::ASC) Order by the order_utm_medium column
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
 * @method     ChildOrderQuery groupByUtmSource() Group by the order_utm_source column
 * @method     ChildOrderQuery groupByUtmCampaign() Group by the order_utm_campaign column
 * @method     ChildOrderQuery groupByUtmMedium() Group by the order_utm_medium column
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
 * @method     \Model\PaymentQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildOrder|null findOne(ConnectionInterface $con = null) Return the first ChildOrder matching the query
 * @method     ChildOrder findOneOrCreate(ConnectionInterface $con = null) Return the first ChildOrder matching the query, or a new ChildOrder object populated from the query conditions when no match is found
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
 * @method     ChildOrder|null findOneByUtmSource(string $order_utm_source) Return the first ChildOrder filtered by the order_utm_source column
 * @method     ChildOrder|null findOneByUtmCampaign(string $order_utm_campaign) Return the first ChildOrder filtered by the order_utm_campaign column
 * @method     ChildOrder|null findOneByUtmMedium(string $order_utm_medium) Return the first ChildOrder filtered by the order_utm_medium column
 * @method     ChildOrder|null findOneByReferer(string $order_referer) Return the first ChildOrder filtered by the order_referer column
 * @method     ChildOrder|null findOneByInsert(string $order_insert) Return the first ChildOrder filtered by the order_insert column
 * @method     ChildOrder|null findOneByPaymentDate(string $order_payment_date) Return the first ChildOrder filtered by the order_payment_date column
 * @method     ChildOrder|null findOneByShippingDate(string $order_shipping_date) Return the first ChildOrder filtered by the order_shipping_date column
 * @method     ChildOrder|null findOneByFollowupDate(string $order_followup_date) Return the first ChildOrder filtered by the order_followup_date column
 * @method     ChildOrder|null findOneByConfirmationDate(string $order_confirmation_date) Return the first ChildOrder filtered by the order_confirmation_date column
 * @method     ChildOrder|null findOneByCancelDate(string $order_cancel_date) Return the first ChildOrder filtered by the order_cancel_date column
 * @method     ChildOrder|null findOneByUpdate(string $order_update) Return the first ChildOrder filtered by the order_update column
 * @method     ChildOrder|null findOneByCreatedAt(string $order_created) Return the first ChildOrder filtered by the order_created column
 * @method     ChildOrder|null findOneByUpdatedAt(string $order_updated) Return the first ChildOrder filtered by the order_updated column *

 * @method     ChildOrder requirePk($key, ConnectionInterface $con = null) Return the ChildOrder by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOne(ConnectionInterface $con = null) Return the first ChildOrder matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
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
 * @method     ChildOrder requireOneByUtmSource(string $order_utm_source) Return the first ChildOrder filtered by the order_utm_source column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByUtmCampaign(string $order_utm_campaign) Return the first ChildOrder filtered by the order_utm_campaign column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOrder requireOneByUtmMedium(string $order_utm_medium) Return the first ChildOrder filtered by the order_utm_medium column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
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
 * @method     ChildOrder[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildOrder objects based on current ModelCriteria
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> find(ConnectionInterface $con = null) Return ChildOrder objects based on current ModelCriteria
 * @method     ChildOrder[]|ObjectCollection findById(int $order_id) Return ChildOrder objects filtered by the order_id column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findById(int $order_id) Return ChildOrder objects filtered by the order_id column
 * @method     ChildOrder[]|ObjectCollection findBySlug(string $order_url) Return ChildOrder objects filtered by the order_url column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findBySlug(string $order_url) Return ChildOrder objects filtered by the order_url column
 * @method     ChildOrder[]|ObjectCollection findBySiteId(int $site_id) Return ChildOrder objects filtered by the site_id column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findBySiteId(int $site_id) Return ChildOrder objects filtered by the site_id column
 * @method     ChildOrder[]|ObjectCollection findByUserId(int $user_id) Return ChildOrder objects filtered by the user_id column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByUserId(int $user_id) Return ChildOrder objects filtered by the user_id column
 * @method     ChildOrder[]|ObjectCollection findByCustomerId(int $customer_id) Return ChildOrder objects filtered by the customer_id column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByCustomerId(int $customer_id) Return ChildOrder objects filtered by the customer_id column
 * @method     ChildOrder[]|ObjectCollection findBySellerId(int $seller_id) Return ChildOrder objects filtered by the seller_id column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findBySellerId(int $seller_id) Return ChildOrder objects filtered by the seller_id column
 * @method     ChildOrder[]|ObjectCollection findByType(string $order_type) Return ChildOrder objects filtered by the order_type column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByType(string $order_type) Return ChildOrder objects filtered by the order_type column
 * @method     ChildOrder[]|ObjectCollection findByAsAGift(string $order_as_a_gift) Return ChildOrder objects filtered by the order_as_a_gift column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByAsAGift(string $order_as_a_gift) Return ChildOrder objects filtered by the order_as_a_gift column
 * @method     ChildOrder[]|ObjectCollection findByGiftRecipient(int $order_gift_recipient) Return ChildOrder objects filtered by the order_gift_recipient column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByGiftRecipient(int $order_gift_recipient) Return ChildOrder objects filtered by the order_gift_recipient column
 * @method     ChildOrder[]|ObjectCollection findByAmount(int $order_amount) Return ChildOrder objects filtered by the order_amount column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByAmount(int $order_amount) Return ChildOrder objects filtered by the order_amount column
 * @method     ChildOrder[]|ObjectCollection findByDiscount(int $order_discount) Return ChildOrder objects filtered by the order_discount column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByDiscount(int $order_discount) Return ChildOrder objects filtered by the order_discount column
 * @method     ChildOrder[]|ObjectCollection findByAmountTobepaid(int $order_amount_tobepaid) Return ChildOrder objects filtered by the order_amount_tobepaid column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByAmountTobepaid(int $order_amount_tobepaid) Return ChildOrder objects filtered by the order_amount_tobepaid column
 * @method     ChildOrder[]|ObjectCollection findByShippingId(int $shipping_id) Return ChildOrder objects filtered by the shipping_id column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByShippingId(int $shipping_id) Return ChildOrder objects filtered by the shipping_id column
 * @method     ChildOrder[]|ObjectCollection findByCountryId(int $country_id) Return ChildOrder objects filtered by the country_id column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByCountryId(int $country_id) Return ChildOrder objects filtered by the country_id column
 * @method     ChildOrder[]|ObjectCollection findByShipping(int $order_shipping) Return ChildOrder objects filtered by the order_shipping column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByShipping(int $order_shipping) Return ChildOrder objects filtered by the order_shipping column
 * @method     ChildOrder[]|ObjectCollection findByShippingMode(string $order_shipping_mode) Return ChildOrder objects filtered by the order_shipping_mode column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByShippingMode(string $order_shipping_mode) Return ChildOrder objects filtered by the order_shipping_mode column
 * @method     ChildOrder[]|ObjectCollection findByTrackNumber(string $order_track_number) Return ChildOrder objects filtered by the order_track_number column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByTrackNumber(string $order_track_number) Return ChildOrder objects filtered by the order_track_number column
 * @method     ChildOrder[]|ObjectCollection findByPaymentMode(string $order_payment_mode) Return ChildOrder objects filtered by the order_payment_mode column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByPaymentMode(string $order_payment_mode) Return ChildOrder objects filtered by the order_payment_mode column
 * @method     ChildOrder[]|ObjectCollection findByPaymentCash(int $order_payment_cash) Return ChildOrder objects filtered by the order_payment_cash column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByPaymentCash(int $order_payment_cash) Return ChildOrder objects filtered by the order_payment_cash column
 * @method     ChildOrder[]|ObjectCollection findByPaymentCheque(int $order_payment_cheque) Return ChildOrder objects filtered by the order_payment_cheque column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByPaymentCheque(int $order_payment_cheque) Return ChildOrder objects filtered by the order_payment_cheque column
 * @method     ChildOrder[]|ObjectCollection findByPaymentTransfer(int $order_payment_transfer) Return ChildOrder objects filtered by the order_payment_transfer column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByPaymentTransfer(int $order_payment_transfer) Return ChildOrder objects filtered by the order_payment_transfer column
 * @method     ChildOrder[]|ObjectCollection findByPaymentCard(int $order_payment_card) Return ChildOrder objects filtered by the order_payment_card column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByPaymentCard(int $order_payment_card) Return ChildOrder objects filtered by the order_payment_card column
 * @method     ChildOrder[]|ObjectCollection findByPaymentPaypal(int $order_payment_paypal) Return ChildOrder objects filtered by the order_payment_paypal column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByPaymentPaypal(int $order_payment_paypal) Return ChildOrder objects filtered by the order_payment_paypal column
 * @method     ChildOrder[]|ObjectCollection findByPaymentPayplug(int $order_payment_payplug) Return ChildOrder objects filtered by the order_payment_payplug column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByPaymentPayplug(int $order_payment_payplug) Return ChildOrder objects filtered by the order_payment_payplug column
 * @method     ChildOrder[]|ObjectCollection findByPaymentLeft(int $order_payment_left) Return ChildOrder objects filtered by the order_payment_left column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByPaymentLeft(int $order_payment_left) Return ChildOrder objects filtered by the order_payment_left column
 * @method     ChildOrder[]|ObjectCollection findByTitle(string $order_title) Return ChildOrder objects filtered by the order_title column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByTitle(string $order_title) Return ChildOrder objects filtered by the order_title column
 * @method     ChildOrder[]|ObjectCollection findByFirstname(string $order_firstname) Return ChildOrder objects filtered by the order_firstname column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByFirstname(string $order_firstname) Return ChildOrder objects filtered by the order_firstname column
 * @method     ChildOrder[]|ObjectCollection findByLastname(string $order_lastname) Return ChildOrder objects filtered by the order_lastname column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByLastname(string $order_lastname) Return ChildOrder objects filtered by the order_lastname column
 * @method     ChildOrder[]|ObjectCollection findByAddress1(string $order_address1) Return ChildOrder objects filtered by the order_address1 column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByAddress1(string $order_address1) Return ChildOrder objects filtered by the order_address1 column
 * @method     ChildOrder[]|ObjectCollection findByAddress2(string $order_address2) Return ChildOrder objects filtered by the order_address2 column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByAddress2(string $order_address2) Return ChildOrder objects filtered by the order_address2 column
 * @method     ChildOrder[]|ObjectCollection findByPostalcode(string $order_postalcode) Return ChildOrder objects filtered by the order_postalcode column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByPostalcode(string $order_postalcode) Return ChildOrder objects filtered by the order_postalcode column
 * @method     ChildOrder[]|ObjectCollection findByCity(string $order_city) Return ChildOrder objects filtered by the order_city column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByCity(string $order_city) Return ChildOrder objects filtered by the order_city column
 * @method     ChildOrder[]|ObjectCollection findByCountry(string $order_country) Return ChildOrder objects filtered by the order_country column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByCountry(string $order_country) Return ChildOrder objects filtered by the order_country column
 * @method     ChildOrder[]|ObjectCollection findByEmail(string $order_email) Return ChildOrder objects filtered by the order_email column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByEmail(string $order_email) Return ChildOrder objects filtered by the order_email column
 * @method     ChildOrder[]|ObjectCollection findByPhone(string $order_phone) Return ChildOrder objects filtered by the order_phone column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByPhone(string $order_phone) Return ChildOrder objects filtered by the order_phone column
 * @method     ChildOrder[]|ObjectCollection findByComment(string $order_comment) Return ChildOrder objects filtered by the order_comment column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByComment(string $order_comment) Return ChildOrder objects filtered by the order_comment column
 * @method     ChildOrder[]|ObjectCollection findByUtmz(string $order_utmz) Return ChildOrder objects filtered by the order_utmz column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByUtmz(string $order_utmz) Return ChildOrder objects filtered by the order_utmz column
 * @method     ChildOrder[]|ObjectCollection findByUtmSource(string $order_utm_source) Return ChildOrder objects filtered by the order_utm_source column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByUtmSource(string $order_utm_source) Return ChildOrder objects filtered by the order_utm_source column
 * @method     ChildOrder[]|ObjectCollection findByUtmCampaign(string $order_utm_campaign) Return ChildOrder objects filtered by the order_utm_campaign column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByUtmCampaign(string $order_utm_campaign) Return ChildOrder objects filtered by the order_utm_campaign column
 * @method     ChildOrder[]|ObjectCollection findByUtmMedium(string $order_utm_medium) Return ChildOrder objects filtered by the order_utm_medium column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByUtmMedium(string $order_utm_medium) Return ChildOrder objects filtered by the order_utm_medium column
 * @method     ChildOrder[]|ObjectCollection findByReferer(string $order_referer) Return ChildOrder objects filtered by the order_referer column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByReferer(string $order_referer) Return ChildOrder objects filtered by the order_referer column
 * @method     ChildOrder[]|ObjectCollection findByInsert(string $order_insert) Return ChildOrder objects filtered by the order_insert column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByInsert(string $order_insert) Return ChildOrder objects filtered by the order_insert column
 * @method     ChildOrder[]|ObjectCollection findByPaymentDate(string $order_payment_date) Return ChildOrder objects filtered by the order_payment_date column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByPaymentDate(string $order_payment_date) Return ChildOrder objects filtered by the order_payment_date column
 * @method     ChildOrder[]|ObjectCollection findByShippingDate(string $order_shipping_date) Return ChildOrder objects filtered by the order_shipping_date column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByShippingDate(string $order_shipping_date) Return ChildOrder objects filtered by the order_shipping_date column
 * @method     ChildOrder[]|ObjectCollection findByFollowupDate(string $order_followup_date) Return ChildOrder objects filtered by the order_followup_date column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByFollowupDate(string $order_followup_date) Return ChildOrder objects filtered by the order_followup_date column
 * @method     ChildOrder[]|ObjectCollection findByConfirmationDate(string $order_confirmation_date) Return ChildOrder objects filtered by the order_confirmation_date column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByConfirmationDate(string $order_confirmation_date) Return ChildOrder objects filtered by the order_confirmation_date column
 * @method     ChildOrder[]|ObjectCollection findByCancelDate(string $order_cancel_date) Return ChildOrder objects filtered by the order_cancel_date column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByCancelDate(string $order_cancel_date) Return ChildOrder objects filtered by the order_cancel_date column
 * @method     ChildOrder[]|ObjectCollection findByUpdate(string $order_update) Return ChildOrder objects filtered by the order_update column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByUpdate(string $order_update) Return ChildOrder objects filtered by the order_update column
 * @method     ChildOrder[]|ObjectCollection findByCreatedAt(string $order_created) Return ChildOrder objects filtered by the order_created column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByCreatedAt(string $order_created) Return ChildOrder objects filtered by the order_created column
 * @method     ChildOrder[]|ObjectCollection findByUpdatedAt(string $order_updated) Return ChildOrder objects filtered by the order_updated column
 * @psalm-method ObjectCollection&\Traversable<ChildOrder> findByUpdatedAt(string $order_updated) Return ChildOrder objects filtered by the order_updated column
 * @method     ChildOrder[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildOrder> paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class OrderQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\OrderQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Order', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildOrderQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildOrderQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
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
    public function findPk($key, ConnectionInterface $con = null)
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
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOrder A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT order_id, order_url, site_id, user_id, customer_id, seller_id, order_type, order_as_a_gift, order_gift_recipient, order_amount, order_discount, order_amount_tobepaid, shipping_id, country_id, order_shipping, order_shipping_mode, order_track_number, order_payment_mode, order_payment_cash, order_payment_cheque, order_payment_transfer, order_payment_card, order_payment_paypal, order_payment_payplug, order_payment_left, order_title, order_firstname, order_lastname, order_address1, order_address2, order_postalcode, order_city, order_country, order_email, order_phone, order_comment, order_utmz, order_utm_source, order_utm_campaign, order_utm_medium, order_referer, order_insert, order_payment_date, order_shipping_date, order_followup_date, order_confirmation_date, order_cancel_date, order_update, order_created, order_updated FROM orders WHERE order_id = :p0';
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
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
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
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_ID, $keys, Criteria::IN);
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
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
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

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_ID, $id, $comparison);
    }

    /**
     * Filter the query on the order_url column
     *
     * Example usage:
     * <code>
     * $query->filterBySlug('fooValue');   // WHERE order_url = 'fooValue'
     * $query->filterBySlug('%fooValue%', Criteria::LIKE); // WHERE order_url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $slug The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterBySlug($slug = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($slug)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_URL, $slug, $comparison);
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
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterBySiteId($siteId = null, $comparison = null)
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

        return $this->addUsingAlias(OrderTableMap::COL_SITE_ID, $siteId, $comparison);
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
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
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

        return $this->addUsingAlias(OrderTableMap::COL_USER_ID, $userId, $comparison);
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
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByCustomerId($customerId = null, $comparison = null)
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

        return $this->addUsingAlias(OrderTableMap::COL_CUSTOMER_ID, $customerId, $comparison);
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
     * @param     mixed $sellerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterBySellerId($sellerId = null, $comparison = null)
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

        return $this->addUsingAlias(OrderTableMap::COL_SELLER_ID, $sellerId, $comparison);
    }

    /**
     * Filter the query on the order_type column
     *
     * Example usage:
     * <code>
     * $query->filterByType('fooValue');   // WHERE order_type = 'fooValue'
     * $query->filterByType('%fooValue%', Criteria::LIKE); // WHERE order_type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $type The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($type)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_TYPE, $type, $comparison);
    }

    /**
     * Filter the query on the order_as_a_gift column
     *
     * Example usage:
     * <code>
     * $query->filterByAsAGift('fooValue');   // WHERE order_as_a_gift = 'fooValue'
     * $query->filterByAsAGift('%fooValue%', Criteria::LIKE); // WHERE order_as_a_gift LIKE '%fooValue%'
     * </code>
     *
     * @param     string $asAGift The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByAsAGift($asAGift = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($asAGift)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_AS_A_GIFT, $asAGift, $comparison);
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
     * @param     mixed $giftRecipient The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByGiftRecipient($giftRecipient = null, $comparison = null)
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

        return $this->addUsingAlias(OrderTableMap::COL_GIFT_RECIPIENT, $giftRecipient, $comparison);
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
     * @param     mixed $amount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByAmount($amount = null, $comparison = null)
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

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_AMOUNT, $amount, $comparison);
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
     * @param     mixed $discount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByDiscount($discount = null, $comparison = null)
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

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_DISCOUNT, $discount, $comparison);
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
     * @param     mixed $amountTobepaid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByAmountTobepaid($amountTobepaid = null, $comparison = null)
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

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_AMOUNT_TOBEPAID, $amountTobepaid, $comparison);
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
     * @param     mixed $shippingId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByShippingId($shippingId = null, $comparison = null)
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

        return $this->addUsingAlias(OrderTableMap::COL_SHIPPING_ID, $shippingId, $comparison);
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
     * @param     mixed $countryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByCountryId($countryId = null, $comparison = null)
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

        return $this->addUsingAlias(OrderTableMap::COL_COUNTRY_ID, $countryId, $comparison);
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
     * @param     mixed $shipping The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByShipping($shipping = null, $comparison = null)
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

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_SHIPPING, $shipping, $comparison);
    }

    /**
     * Filter the query on the order_shipping_mode column
     *
     * Example usage:
     * <code>
     * $query->filterByShippingMode('fooValue');   // WHERE order_shipping_mode = 'fooValue'
     * $query->filterByShippingMode('%fooValue%', Criteria::LIKE); // WHERE order_shipping_mode LIKE '%fooValue%'
     * </code>
     *
     * @param     string $shippingMode The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByShippingMode($shippingMode = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($shippingMode)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_SHIPPING_MODE, $shippingMode, $comparison);
    }

    /**
     * Filter the query on the order_track_number column
     *
     * Example usage:
     * <code>
     * $query->filterByTrackNumber('fooValue');   // WHERE order_track_number = 'fooValue'
     * $query->filterByTrackNumber('%fooValue%', Criteria::LIKE); // WHERE order_track_number LIKE '%fooValue%'
     * </code>
     *
     * @param     string $trackNumber The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByTrackNumber($trackNumber = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($trackNumber)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_TRACK_NUMBER, $trackNumber, $comparison);
    }

    /**
     * Filter the query on the order_payment_mode column
     *
     * Example usage:
     * <code>
     * $query->filterByPaymentMode('fooValue');   // WHERE order_payment_mode = 'fooValue'
     * $query->filterByPaymentMode('%fooValue%', Criteria::LIKE); // WHERE order_payment_mode LIKE '%fooValue%'
     * </code>
     *
     * @param     string $paymentMode The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByPaymentMode($paymentMode = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($paymentMode)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_MODE, $paymentMode, $comparison);
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
     * @param     mixed $paymentCash The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByPaymentCash($paymentCash = null, $comparison = null)
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

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_CASH, $paymentCash, $comparison);
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
     * @param     mixed $paymentCheque The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByPaymentCheque($paymentCheque = null, $comparison = null)
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

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_CHEQUE, $paymentCheque, $comparison);
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
     * @param     mixed $paymentTransfer The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByPaymentTransfer($paymentTransfer = null, $comparison = null)
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

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_TRANSFER, $paymentTransfer, $comparison);
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
     * @param     mixed $paymentCard The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByPaymentCard($paymentCard = null, $comparison = null)
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

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_CARD, $paymentCard, $comparison);
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
     * @param     mixed $paymentPaypal The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByPaymentPaypal($paymentPaypal = null, $comparison = null)
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

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_PAYPAL, $paymentPaypal, $comparison);
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
     * @param     mixed $paymentPayplug The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByPaymentPayplug($paymentPayplug = null, $comparison = null)
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

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_PAYPLUG, $paymentPayplug, $comparison);
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
     * @param     mixed $paymentLeft The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByPaymentLeft($paymentLeft = null, $comparison = null)
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

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_LEFT, $paymentLeft, $comparison);
    }

    /**
     * Filter the query on the order_title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE order_title = 'fooValue'
     * $query->filterByTitle('%fooValue%', Criteria::LIKE); // WHERE order_title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the order_firstname column
     *
     * Example usage:
     * <code>
     * $query->filterByFirstname('fooValue');   // WHERE order_firstname = 'fooValue'
     * $query->filterByFirstname('%fooValue%', Criteria::LIKE); // WHERE order_firstname LIKE '%fooValue%'
     * </code>
     *
     * @param     string $firstname The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByFirstname($firstname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($firstname)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_FIRSTNAME, $firstname, $comparison);
    }

    /**
     * Filter the query on the order_lastname column
     *
     * Example usage:
     * <code>
     * $query->filterByLastname('fooValue');   // WHERE order_lastname = 'fooValue'
     * $query->filterByLastname('%fooValue%', Criteria::LIKE); // WHERE order_lastname LIKE '%fooValue%'
     * </code>
     *
     * @param     string $lastname The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByLastname($lastname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lastname)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_LASTNAME, $lastname, $comparison);
    }

    /**
     * Filter the query on the order_address1 column
     *
     * Example usage:
     * <code>
     * $query->filterByAddress1('fooValue');   // WHERE order_address1 = 'fooValue'
     * $query->filterByAddress1('%fooValue%', Criteria::LIKE); // WHERE order_address1 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $address1 The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByAddress1($address1 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($address1)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_ADDRESS1, $address1, $comparison);
    }

    /**
     * Filter the query on the order_address2 column
     *
     * Example usage:
     * <code>
     * $query->filterByAddress2('fooValue');   // WHERE order_address2 = 'fooValue'
     * $query->filterByAddress2('%fooValue%', Criteria::LIKE); // WHERE order_address2 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $address2 The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByAddress2($address2 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($address2)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_ADDRESS2, $address2, $comparison);
    }

    /**
     * Filter the query on the order_postalcode column
     *
     * Example usage:
     * <code>
     * $query->filterByPostalcode('fooValue');   // WHERE order_postalcode = 'fooValue'
     * $query->filterByPostalcode('%fooValue%', Criteria::LIKE); // WHERE order_postalcode LIKE '%fooValue%'
     * </code>
     *
     * @param     string $postalcode The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByPostalcode($postalcode = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($postalcode)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_POSTALCODE, $postalcode, $comparison);
    }

    /**
     * Filter the query on the order_city column
     *
     * Example usage:
     * <code>
     * $query->filterByCity('fooValue');   // WHERE order_city = 'fooValue'
     * $query->filterByCity('%fooValue%', Criteria::LIKE); // WHERE order_city LIKE '%fooValue%'
     * </code>
     *
     * @param     string $city The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByCity($city = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($city)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_CITY, $city, $comparison);
    }

    /**
     * Filter the query on the order_country column
     *
     * Example usage:
     * <code>
     * $query->filterByCountry('fooValue');   // WHERE order_country = 'fooValue'
     * $query->filterByCountry('%fooValue%', Criteria::LIKE); // WHERE order_country LIKE '%fooValue%'
     * </code>
     *
     * @param     string $country The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByCountry($country = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($country)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_COUNTRY, $country, $comparison);
    }

    /**
     * Filter the query on the order_email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE order_email = 'fooValue'
     * $query->filterByEmail('%fooValue%', Criteria::LIKE); // WHERE order_email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the order_phone column
     *
     * Example usage:
     * <code>
     * $query->filterByPhone('fooValue');   // WHERE order_phone = 'fooValue'
     * $query->filterByPhone('%fooValue%', Criteria::LIKE); // WHERE order_phone LIKE '%fooValue%'
     * </code>
     *
     * @param     string $phone The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByPhone($phone = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($phone)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_PHONE, $phone, $comparison);
    }

    /**
     * Filter the query on the order_comment column
     *
     * Example usage:
     * <code>
     * $query->filterByComment('fooValue');   // WHERE order_comment = 'fooValue'
     * $query->filterByComment('%fooValue%', Criteria::LIKE); // WHERE order_comment LIKE '%fooValue%'
     * </code>
     *
     * @param     string $comment The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByComment($comment = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($comment)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_COMMENT, $comment, $comparison);
    }

    /**
     * Filter the query on the order_utmz column
     *
     * Example usage:
     * <code>
     * $query->filterByUtmz('fooValue');   // WHERE order_utmz = 'fooValue'
     * $query->filterByUtmz('%fooValue%', Criteria::LIKE); // WHERE order_utmz LIKE '%fooValue%'
     * </code>
     *
     * @param     string $utmz The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByUtmz($utmz = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($utmz)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_UTMZ, $utmz, $comparison);
    }

    /**
     * Filter the query on the order_utm_source column
     *
     * Example usage:
     * <code>
     * $query->filterByUtmSource('fooValue');   // WHERE order_utm_source = 'fooValue'
     * $query->filterByUtmSource('%fooValue%', Criteria::LIKE); // WHERE order_utm_source LIKE '%fooValue%'
     * </code>
     *
     * @param     string $utmSource The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByUtmSource($utmSource = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($utmSource)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_UTM_SOURCE, $utmSource, $comparison);
    }

    /**
     * Filter the query on the order_utm_campaign column
     *
     * Example usage:
     * <code>
     * $query->filterByUtmCampaign('fooValue');   // WHERE order_utm_campaign = 'fooValue'
     * $query->filterByUtmCampaign('%fooValue%', Criteria::LIKE); // WHERE order_utm_campaign LIKE '%fooValue%'
     * </code>
     *
     * @param     string $utmCampaign The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByUtmCampaign($utmCampaign = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($utmCampaign)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_UTM_CAMPAIGN, $utmCampaign, $comparison);
    }

    /**
     * Filter the query on the order_utm_medium column
     *
     * Example usage:
     * <code>
     * $query->filterByUtmMedium('fooValue');   // WHERE order_utm_medium = 'fooValue'
     * $query->filterByUtmMedium('%fooValue%', Criteria::LIKE); // WHERE order_utm_medium LIKE '%fooValue%'
     * </code>
     *
     * @param     string $utmMedium The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByUtmMedium($utmMedium = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($utmMedium)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_UTM_MEDIUM, $utmMedium, $comparison);
    }

    /**
     * Filter the query on the order_referer column
     *
     * Example usage:
     * <code>
     * $query->filterByReferer('fooValue');   // WHERE order_referer = 'fooValue'
     * $query->filterByReferer('%fooValue%', Criteria::LIKE); // WHERE order_referer LIKE '%fooValue%'
     * </code>
     *
     * @param     string $referer The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByReferer($referer = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($referer)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_REFERER, $referer, $comparison);
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
     * @param     mixed $insert The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByInsert($insert = null, $comparison = null)
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

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_INSERT, $insert, $comparison);
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
     * @param     mixed $paymentDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByPaymentDate($paymentDate = null, $comparison = null)
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

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_PAYMENT_DATE, $paymentDate, $comparison);
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
     * @param     mixed $shippingDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByShippingDate($shippingDate = null, $comparison = null)
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

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_SHIPPING_DATE, $shippingDate, $comparison);
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
     * @param     mixed $followupDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByFollowupDate($followupDate = null, $comparison = null)
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

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_FOLLOWUP_DATE, $followupDate, $comparison);
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
     * @param     mixed $confirmationDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByConfirmationDate($confirmationDate = null, $comparison = null)
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

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_CONFIRMATION_DATE, $confirmationDate, $comparison);
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
     * @param     mixed $cancelDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByCancelDate($cancelDate = null, $comparison = null)
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

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_CANCEL_DATE, $cancelDate, $comparison);
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
     * @param     mixed $update The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByUpdate($update = null, $comparison = null)
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

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_UPDATE, $update, $comparison);
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
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
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

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_CREATED, $createdAt, $comparison);
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
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
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

        return $this->addUsingAlias(OrderTableMap::COL_ORDER_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Model\Payment object
     *
     * @param \Model\Payment|ObjectCollection $payment the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildOrderQuery The current query, for fluid interface
     */
    public function filterByPayment($payment, $comparison = null)
    {
        if ($payment instanceof \Model\Payment) {
            return $this
                ->addUsingAlias(OrderTableMap::COL_ORDER_ID, $payment->getOrderId(), $comparison);
        } elseif ($payment instanceof ObjectCollection) {
            return $this
                ->usePaymentQuery()
                ->filterByPrimaryKeys($payment->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPayment() only accepts arguments of type \Model\Payment or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Payment relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
     */
    public function joinPayment($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
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
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string $typeOfExists Either ExistsCriterion::TYPE_EXISTS or ExistsCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\PaymentQuery The inner query object of the EXISTS statement
     */
    public function usePaymentExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        return $this->useExistsQuery('Payment', $modelAlias, $queryClass, $typeOfExists);
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
        return $this->useExistsQuery('Payment', $modelAlias, $queryClass, 'NOT EXISTS');
    }
    /**
     * Exclude object from result
     *
     * @param   ChildOrder $order Object to remove from the list of results
     *
     * @return $this|ChildOrderQuery The current query, for fluid interface
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
    public function doDeleteAll(ConnectionInterface $con = null)
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
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
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
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildOrderQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(OrderTableMap::COL_ORDER_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildOrderQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(OrderTableMap::COL_ORDER_UPDATED);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildOrderQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(OrderTableMap::COL_ORDER_UPDATED);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildOrderQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(OrderTableMap::COL_ORDER_CREATED);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildOrderQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(OrderTableMap::COL_ORDER_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildOrderQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(OrderTableMap::COL_ORDER_CREATED);
    }

} // OrderQuery
