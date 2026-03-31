<?php
/*
 * Copyright (C) 2026 Clément Latzarus
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

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1775116758.
 * Generated on 2026-04-02 07:59:18 by clement */
class PropelMigration_1775116758{
    /**
     * @var string
     */
    public $comment = '';

    /**
     * @param \Propel\Generator\Manager\MigrationManager $manager
     *
     * @return null|false|void
     */
    public function preUp(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    /**
     * @param \Propel\Generator\Manager\MigrationManager $manager
     *
     * @return null|false|void
     */
    public function postUp(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    /**
     * @param \Propel\Generator\Manager\MigrationManager $manager
     *
     * @return null|false|void
     */
    public function preDown(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    /**
     * @param \Propel\Generator\Manager\MigrationManager $manager
     *
     * @return null|false|void
     */
    public function postDown(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL(): array
    {
        $connection_default = <<< 'EOT'

# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `alerts`

  CHANGE `alert_id` `alert_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int unsigned,

  CHANGE `axys_account_id` `axys_account_id` int unsigned,

  CHANGE `user_id` `user_id` int unsigned,

  CHANGE `article_id` `article_id` int unsigned,

  CHANGE `alert_max_price` `alert_max_price` int unsigned,

  CHANGE `alert_pub_year` `alert_pub_year` int unsigned;

ALTER TABLE `articles`

  CHANGE `article_id` `article_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `article_ean` `article_ean` bigint unsigned,

  CHANGE `article_lang_current` `article_lang_current` tinyint unsigned,

  CHANGE `article_lang_original` `article_lang_original` tinyint unsigned,

  CHANGE `article_source_id` `article_source_id` int unsigned,

  CHANGE `cycle_id` `cycle_id` int unsigned,

  CHANGE `article_availability` `article_availability` tinyint unsigned,

  CHANGE `article_availability_dilicom` `article_availability_dilicom` tinyint unsigned DEFAULT 1,

  CHANGE `article_price` `article_price` int unsigned,

  CHANGE `article_new_price` `article_new_price` int unsigned,

  CHANGE `article_pdf_ean` `article_pdf_ean` bigint unsigned,

  CHANGE `article_epub_ean` `article_epub_ean` bigint unsigned,

  CHANGE `article_azw_ean` `article_azw_ean` bigint unsigned,

  CHANGE `article_copyright` `article_copyright` mediumint unsigned,

  CHANGE `article_publisher_stock` `article_publisher_stock` int unsigned DEFAULT 0,

  CHANGE `article_hits` `article_hits` int unsigned DEFAULT 0,

  CHANGE `article_editing_user` `article_editing_user` int unsigned,

  CHANGE `article_deletion_by` `article_deletion_by` int unsigned;

ALTER TABLE `authentication_methods`

  CHANGE `site_id` `site_id` INTEGER;

ALTER TABLE `awards`

  CHANGE `award_id` `award_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `article_id` `article_id` int unsigned;

ALTER TABLE `carts`

  CHANGE `cart_id` `cart_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int unsigned,

  CHANGE `axys_account_id` `axys_account_id` int unsigned,

  CHANGE `user_id` `user_id` int unsigned,

  CHANGE `cart_seller_id` `cart_seller_id` int unsigned,

  CHANGE `seller_user_id` `seller_user_id` int unsigned,

  CHANGE `customer_id` `customer_id` int unsigned,

  CHANGE `cart_count` `cart_count` int unsigned DEFAULT 0,

  CHANGE `cart_amount` `cart_amount` int unsigned DEFAULT 0,

  CHANGE `cart_gift_recipient` `cart_gift_recipient` int unsigned;

ALTER TABLE `categories`

  CHANGE `category_id` `category_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int unsigned,

  CHANGE `category_order` `category_order` tinyint unsigned;

ALTER TABLE `cf_campaigns`

  CHANGE `campaign_id` `campaign_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int unsigned,

  CHANGE `campaign_goal` `campaign_goal` int unsigned,

  CHANGE `campaign_pledged` `campaign_pledged` int unsigned,

  CHANGE `campaign_backers` `campaign_backers` int unsigned;

ALTER TABLE `cf_rewards`

  CHANGE `reward_id` `reward_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int unsigned,

  CHANGE `campaign_id` `campaign_id` int unsigned,

  CHANGE `reward_price` `reward_price` int unsigned,

  CHANGE `reward_quantity` `reward_quantity` int unsigned,

  CHANGE `reward_backers` `reward_backers` int unsigned DEFAULT 0;

ALTER TABLE `collections`

  CHANGE `collection_id` `collection_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int unsigned,

  CHANGE `publisher_id` `publisher_id` int unsigned,

  CHANGE `pricegrid_id` `pricegrid_id` int unsigned;

ALTER TABLE `coupons`

  CHANGE `coupon_id` `coupon_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int unsigned,

  CHANGE `axys_account_id` `axys_account_id` int unsigned,

  CHANGE `user_id` `user_id` int unsigned,

  CHANGE `article_id` `article_id` int unsigned,

  CHANGE `stock_id` `stock_id` int unsigned,

  CHANGE `coupon_amount` `coupon_amount` int unsigned,

  CHANGE `coupon_creator` `coupon_creator` int unsigned;

ALTER TABLE `cron_jobs`

  CHANGE `cron_job_id` `cron_job_id` int unsigned NOT NULL AUTO_INCREMENT;

ALTER TABLE `customers`

  CHANGE `customer_id` `customer_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int unsigned,

  CHANGE `axys_account_id` `axys_account_id` int unsigned,

  CHANGE `user_id` `user_id` int unsigned,

  CHANGE `country_id` `country_id` int unsigned;

ALTER TABLE `cycles`

  CHANGE `cycle_id` `cycle_id` int unsigned NOT NULL AUTO_INCREMENT;

ALTER TABLE `downloads`

  CHANGE `download_id` `download_id` bigint unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int unsigned,

  CHANGE `file_id` `file_id` int unsigned,

  CHANGE `article_id` `article_id` int unsigned,

  CHANGE `book_id` `book_id` int unsigned,

  CHANGE `axys_account_id` `axys_account_id` int unsigned,

  CHANGE `user_id` `user_id` int unsigned;

ALTER TABLE `events`

  CHANGE `event_id` `event_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int unsigned;

ALTER TABLE `files`

  CHANGE `file_id` `file_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int unsigned,

  CHANGE `article_id` `article_id` int unsigned,

  CHANGE `axys_account_id` `axys_account_id` int unsigned,

  CHANGE `user_id` `user_id` int unsigned,

  CHANGE `file_size` `file_size` bigint unsigned DEFAULT 0,

  CHANGE `file_ean` `file_ean` bigint unsigned;

ALTER TABLE `galleries`

  CHANGE `gallery_id` `gallery_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int unsigned;

ALTER TABLE `images`

  CHANGE `id` `id` int unsigned NOT NULL AUTO_INCREMENT;

ALTER TABLE `inventory`

  CHANGE `inventory_id` `inventory_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int unsigned;

ALTER TABLE `inventory_item`

  CHANGE `ii_id` `ii_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `inventory_id` `inventory_id` int unsigned,

  CHANGE `ii_quantity` `ii_quantity` int unsigned,

  CHANGE `ii_stock` `ii_stock` int unsigned;

ALTER TABLE `invitations`

  CHANGE `id` `id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int unsigned;

ALTER TABLE `jobs`

  CHANGE `job_id` `job_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `job_order` `job_order` tinyint unsigned DEFAULT 0 NOT NULL;

ALTER TABLE `langs`

  CHANGE `lang_id` `lang_id` int unsigned NOT NULL AUTO_INCREMENT;

ALTER TABLE `links`

  CHANGE `link_id` `link_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int unsigned,

  CHANGE `axys_account_id` `axys_account_id` int unsigned,

  CHANGE `user_id` `user_id` int unsigned,

  CHANGE `article_id` `article_id` int unsigned,

  CHANGE `stock_id` `stock_id` int unsigned,

  CHANGE `list_id` `list_id` int unsigned,

  CHANGE `book_id` `book_id` int unsigned,

  CHANGE `people_id` `people_id` int unsigned,

  CHANGE `job_id` `job_id` int unsigned,

  CHANGE `rayon_id` `rayon_id` int unsigned,

  CHANGE `event_id` `event_id` int unsigned,

  CHANGE `post_id` `post_id` int unsigned,

  CHANGE `collection_id` `collection_id` int unsigned,

  CHANGE `publisher_id` `publisher_id` int unsigned,

  CHANGE `supplier_id` `supplier_id` int unsigned,

  CHANGE `media_id` `media_id` int unsigned,

  CHANGE `bundle_id` `bundle_id` int unsigned,

  CHANGE `link_sponsor_axys_account_id` `link_sponsor_axys_account_id` int unsigned;

ALTER TABLE `lists`

  CHANGE `list_id` `list_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `axys_account_id` `axys_account_id` int unsigned,

  CHANGE `user_id` `user_id` int unsigned,

  CHANGE `site_id` `site_id` int unsigned;

ALTER TABLE `medias`

  CHANGE `media_id` `media_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int unsigned,

  CHANGE `category_id` `category_id` int unsigned;

ALTER TABLE `options`

  CHANGE `option_id` `option_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int unsigned,

  CHANGE `user_id` `user_id` int unsigned;

ALTER TABLE `orders`

  CHANGE `order_id` `order_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `axys_account_id` `axys_account_id` int unsigned,

  CHANGE `user_id` `user_id` int unsigned,

  CHANGE `customer_id` `customer_id` int unsigned,

  CHANGE `seller_id` `seller_id` int unsigned,

  CHANGE `order_gift_recipient` `order_gift_recipient` int unsigned,

  CHANGE `order_amount` `order_amount` int unsigned DEFAULT 0,

  CHANGE `order_discount` `order_discount` int unsigned,

  CHANGE `order_payment_cash` `order_payment_cash` int unsigned DEFAULT 0,

  CHANGE `order_payment_cheque` `order_payment_cheque` int unsigned DEFAULT 0,

  CHANGE `order_payment_transfer` `order_payment_transfer` int unsigned DEFAULT 0,

  CHANGE `order_payment_card` `order_payment_card` int unsigned DEFAULT 0,

  CHANGE `order_payment_paypal` `order_payment_paypal` int unsigned DEFAULT 0,

  CHANGE `order_payment_payplug` `order_payment_payplug` int unsigned DEFAULT 0,

  CHANGE `order_payment_left` `order_payment_left` int unsigned DEFAULT 0;

ALTER TABLE `pages`

  CHANGE `site_id` `site_id` int unsigned;

ALTER TABLE `payments`

  CHANGE `payment_id` `payment_id` int unsigned NOT NULL AUTO_INCREMENT;

ALTER TABLE `people`

  CHANGE `people_id` `people_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `people_pseudo` `people_pseudo` int unsigned,

  CHANGE `people_hits` `people_hits` int unsigned;

ALTER TABLE `permissions`

  CHANGE `permission_id` `permission_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `user_id` `user_id` int unsigned;

ALTER TABLE `posts`

  CHANGE `user_id` `user_id` int unsigned,

  CHANGE `site_id` `site_id` int unsigned,

  CHANGE `publisher_id` `publisher_id` int unsigned,

  CHANGE `category_id` `category_id` int unsigned,

  CHANGE `post_hits` `post_hits` int unsigned;

ALTER TABLE `prices`

  CHANGE `price_id` `price_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `pricegrid_id` `pricegrid_id` int unsigned,

  CHANGE `price_amount` `price_amount` int unsigned;

ALTER TABLE `publishers`

  CHANGE `publisher_id` `publisher_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int unsigned,

  CHANGE `publisher_shipping_fee` `publisher_shipping_fee` int unsigned,

  CHANGE `publisher_gln` `publisher_gln` bigint unsigned;

ALTER TABLE `rayons`

  CHANGE `rayon_id` `rayon_id` bigint unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int unsigned,

  CHANGE `rayon_show_upcoming` `rayon_show_upcoming` tinyint unsigned DEFAULT 0;

ALTER TABLE `redirections`

  CHANGE `redirection_id` `redirection_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int unsigned;

ALTER TABLE `rights`

  CHANGE `right_id` `right_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `axys_account_id` `axys_account_id` int unsigned,

  CHANGE `user_id` `user_id` int unsigned,

  CHANGE `site_id` `site_id` int unsigned,

  CHANGE `publisher_id` `publisher_id` int unsigned;

ALTER TABLE `roles`

  CHANGE `id` `id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `article_id` `article_id` int unsigned,

  CHANGE `book_id` `book_id` int unsigned,

  CHANGE `event_id` `event_id` int unsigned,

  CHANGE `people_id` `people_id` int unsigned,

  CHANGE `job_id` `job_id` int unsigned,

  CHANGE `axys_account_id` `axys_account_id` int unsigned,

  CHANGE `user_id` `user_id` int unsigned,

  CHANGE `role_hide` `role_hide` tinyint unsigned;

ALTER TABLE `session`

  CHANGE `session_id` `session_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `axys_account_id` `axys_account_id` int unsigned,

  CHANGE `user_id` `user_id` int unsigned;

ALTER TABLE `shipping`

  CHANGE `shipping_id` `shipping_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `article_id` `article_id` int unsigned,

  CHANGE `shipping_zone_id` `shipping_zone_id` int unsigned,

  CHANGE `shipping_max_weight` `shipping_max_weight` int unsigned,

  CHANGE `shipping_max_articles` `shipping_max_articles` int unsigned,

  CHANGE `shipping_max_amount` `shipping_max_amount` int unsigned;

ALTER TABLE `signings`

  CHANGE `signing_id` `signing_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int unsigned,

  CHANGE `publisher_id` `publisher_id` int unsigned,

  CHANGE `people_id` `people_id` int unsigned;

ALTER TABLE `sites`

  CHANGE `site_id` `site_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_event_id` `site_event_id` int unsigned,

  CHANGE `site_event_date` `site_event_date` int unsigned,

  CHANGE `publisher_id` `publisher_id` int unsigned,

  CHANGE `site_fb_page_id` `site_fb_page_id` bigint unsigned;

ALTER TABLE `stock`

  CHANGE `stock_id` `stock_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int unsigned,

  CHANGE `article_id` `article_id` int unsigned,

  CHANGE `campaign_id` `campaign_id` int unsigned,

  CHANGE `reward_id` `reward_id` int unsigned,

  CHANGE `user_id` `user_id` int unsigned,

  CHANGE `customer_id` `customer_id` int unsigned,

  CHANGE `wish_id` `wish_id` int unsigned,

  CHANGE `cart_id` `cart_id` int unsigned,

  CHANGE `order_id` `order_id` int unsigned,

  CHANGE `coupon_id` `coupon_id` int unsigned,

  CHANGE `stock_shop` `stock_shop` int unsigned,

  CHANGE `stock_purchase_price` `stock_purchase_price` int unsigned,

  CHANGE `stock_selling_price` `stock_selling_price` int unsigned,

  CHANGE `stock_selling_price2` `stock_selling_price2` int unsigned,

  CHANGE `stock_selling_price_saved` `stock_selling_price_saved` int unsigned,

  CHANGE `stock_selling_price_ht` `stock_selling_price_ht` int unsigned,

  CHANGE `stock_selling_price_tva` `stock_selling_price_tva` int unsigned,

  CHANGE `stock_weight` `stock_weight` int unsigned,

  CHANGE `stock_pub_year` `stock_pub_year` int unsigned,

  CHANGE `stock_photo_version` `stock_photo_version` int unsigned DEFAULT 0;

ALTER TABLE `subscriptions`

  CHANGE `subscription_id` `subscription_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int unsigned,

  CHANGE `axys_account_id` `axys_account_id` int unsigned,

  CHANGE `user_id` `user_id` int unsigned,

  CHANGE `publisher_id` `publisher_id` int unsigned;

ALTER TABLE `suppliers`

  CHANGE `supplier_id` `supplier_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int unsigned,

  CHANGE `supplier_remise` `supplier_remise` int unsigned;

ALTER TABLE `tags`

  CHANGE `tag_num` `tag_num` int unsigned;

ALTER TABLE `tags_articles`

  CHANGE `article_id` `article_id` int unsigned NOT NULL;

ALTER TABLE `users`

  CHANGE `site_id` `site_id` INTEGER;

ALTER TABLE `votes`

  CHANGE `vote_id` `vote_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int unsigned,

  CHANGE `axys_account_id` `axys_account_id` int unsigned,

  CHANGE `user_id` `user_id` int unsigned,

  CHANGE `vote_F` `vote_F` int unsigned,

  CHANGE `vote_E` `vote_E` int unsigned;

ALTER TABLE `wishes`

  CHANGE `wish_id` `wish_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `axys_account_id` `axys_account_id` int unsigned,

  CHANGE `user_id` `user_id` int unsigned,

  CHANGE `site_id` `site_id` int unsigned,

  CHANGE `article_id` `article_id` int unsigned;

ALTER TABLE `wishlist`

  CHANGE `wishlist_id` `wishlist_id` int unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int unsigned,

  CHANGE `axys_account_id` `axys_account_id` int unsigned,

  CHANGE `user_id` `user_id` int unsigned;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
EOT;

        return [
            'default' => $connection_default,
        ];
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL(): array
    {
        $connection_default = <<< 'EOT'

# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `alerts`

  CHANGE `alert_id` `alert_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int(10) unsigned,

  CHANGE `axys_account_id` `axys_account_id` int(10) unsigned,

  CHANGE `user_id` `user_id` int(10) unsigned,

  CHANGE `article_id` `article_id` int(10) unsigned,

  CHANGE `alert_max_price` `alert_max_price` int(10) unsigned,

  CHANGE `alert_pub_year` `alert_pub_year` int(10) unsigned;

ALTER TABLE `articles`

  CHANGE `article_id` `article_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `article_ean` `article_ean` bigint(20) unsigned,

  CHANGE `article_lang_current` `article_lang_current` tinyint(3) unsigned,

  CHANGE `article_lang_original` `article_lang_original` tinyint(3) unsigned,

  CHANGE `article_source_id` `article_source_id` int(10) unsigned,

  CHANGE `cycle_id` `cycle_id` int(10) unsigned,

  CHANGE `article_availability` `article_availability` tinyint(3) unsigned,

  CHANGE `article_availability_dilicom` `article_availability_dilicom` tinyint(3) unsigned DEFAULT 1,

  CHANGE `article_price` `article_price` int(10) unsigned,

  CHANGE `article_new_price` `article_new_price` int(10) unsigned,

  CHANGE `article_pdf_ean` `article_pdf_ean` bigint(20) unsigned,

  CHANGE `article_epub_ean` `article_epub_ean` bigint(20) unsigned,

  CHANGE `article_azw_ean` `article_azw_ean` bigint(20) unsigned,

  CHANGE `article_copyright` `article_copyright` mediumint(8) unsigned,

  CHANGE `article_publisher_stock` `article_publisher_stock` int(10) unsigned DEFAULT 0,

  CHANGE `article_hits` `article_hits` int(10) unsigned DEFAULT 0,

  CHANGE `article_editing_user` `article_editing_user` int(10) unsigned,

  CHANGE `article_deletion_by` `article_deletion_by` int(10) unsigned;

ALTER TABLE `authentication_methods`

  CHANGE `site_id` `site_id` INTEGER NOT NULL;

ALTER TABLE `awards`

  CHANGE `award_id` `award_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `article_id` `article_id` int(10) unsigned;

ALTER TABLE `carts`

  CHANGE `cart_id` `cart_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int(10) unsigned,

  CHANGE `axys_account_id` `axys_account_id` int(10) unsigned,

  CHANGE `user_id` `user_id` int(10) unsigned,

  CHANGE `cart_seller_id` `cart_seller_id` int(10) unsigned,

  CHANGE `seller_user_id` `seller_user_id` int(10) unsigned,

  CHANGE `customer_id` `customer_id` int(10) unsigned,

  CHANGE `cart_count` `cart_count` int(10) unsigned DEFAULT 0,

  CHANGE `cart_amount` `cart_amount` int(10) unsigned DEFAULT 0,

  CHANGE `cart_gift_recipient` `cart_gift_recipient` int(10) unsigned;

ALTER TABLE `categories`

  CHANGE `category_id` `category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int(10) unsigned,

  CHANGE `category_order` `category_order` tinyint(3) unsigned;

ALTER TABLE `cf_campaigns`

  CHANGE `campaign_id` `campaign_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int(10) unsigned,

  CHANGE `campaign_goal` `campaign_goal` int(10) unsigned,

  CHANGE `campaign_pledged` `campaign_pledged` int(10) unsigned,

  CHANGE `campaign_backers` `campaign_backers` int(10) unsigned;

ALTER TABLE `cf_rewards`

  CHANGE `reward_id` `reward_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int(10) unsigned,

  CHANGE `campaign_id` `campaign_id` int(10) unsigned,

  CHANGE `reward_price` `reward_price` int(10) unsigned,

  CHANGE `reward_quantity` `reward_quantity` int(10) unsigned,

  CHANGE `reward_backers` `reward_backers` int(10) unsigned DEFAULT 0;

ALTER TABLE `collections`

  CHANGE `collection_id` `collection_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int(10) unsigned,

  CHANGE `publisher_id` `publisher_id` int(10) unsigned,

  CHANGE `pricegrid_id` `pricegrid_id` int(10) unsigned;

ALTER TABLE `coupons`

  CHANGE `coupon_id` `coupon_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int(10) unsigned,

  CHANGE `axys_account_id` `axys_account_id` int(10) unsigned,

  CHANGE `user_id` `user_id` int(10) unsigned,

  CHANGE `article_id` `article_id` int(10) unsigned,

  CHANGE `stock_id` `stock_id` int(10) unsigned,

  CHANGE `coupon_amount` `coupon_amount` int(10) unsigned,

  CHANGE `coupon_creator` `coupon_creator` int(10) unsigned;

ALTER TABLE `cron_jobs`

  CHANGE `cron_job_id` `cron_job_id` int(10) unsigned NOT NULL AUTO_INCREMENT;

ALTER TABLE `customers`

  CHANGE `customer_id` `customer_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int(10) unsigned,

  CHANGE `axys_account_id` `axys_account_id` int(10) unsigned,

  CHANGE `user_id` `user_id` int(10) unsigned,

  CHANGE `country_id` `country_id` int(10) unsigned;

ALTER TABLE `cycles`

  CHANGE `cycle_id` `cycle_id` int(10) unsigned NOT NULL AUTO_INCREMENT;

ALTER TABLE `downloads`

  CHANGE `download_id` `download_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int(10) unsigned,

  CHANGE `file_id` `file_id` int(10) unsigned,

  CHANGE `article_id` `article_id` int(10) unsigned,

  CHANGE `book_id` `book_id` int(10) unsigned,

  CHANGE `axys_account_id` `axys_account_id` int(10) unsigned,

  CHANGE `user_id` `user_id` int(10) unsigned;

ALTER TABLE `events`

  CHANGE `event_id` `event_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int(10) unsigned;

ALTER TABLE `files`

  CHANGE `file_id` `file_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int(10) unsigned,

  CHANGE `article_id` `article_id` int(10) unsigned,

  CHANGE `axys_account_id` `axys_account_id` int(10) unsigned,

  CHANGE `user_id` `user_id` int(10) unsigned,

  CHANGE `file_size` `file_size` bigint(20) unsigned DEFAULT 0,

  CHANGE `file_ean` `file_ean` bigint(20) unsigned;

ALTER TABLE `galleries`

  CHANGE `gallery_id` `gallery_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int(10) unsigned;

ALTER TABLE `images`

  CHANGE `id` `id` int(10) unsigned NOT NULL AUTO_INCREMENT;

ALTER TABLE `inventory`

  CHANGE `inventory_id` `inventory_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int(10) unsigned;

ALTER TABLE `inventory_item`

  CHANGE `ii_id` `ii_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `inventory_id` `inventory_id` int(10) unsigned,

  CHANGE `ii_quantity` `ii_quantity` int(10) unsigned,

  CHANGE `ii_stock` `ii_stock` int(10) unsigned;

ALTER TABLE `invitations`

  CHANGE `id` `id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int(10) unsigned NOT NULL;

ALTER TABLE `jobs`

  CHANGE `job_id` `job_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `job_order` `job_order` tinyint(3) unsigned DEFAULT 0 NOT NULL;

ALTER TABLE `langs`

  CHANGE `lang_id` `lang_id` int(10) unsigned NOT NULL AUTO_INCREMENT;

ALTER TABLE `links`

  CHANGE `link_id` `link_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int(10) unsigned,

  CHANGE `axys_account_id` `axys_account_id` int(10) unsigned,

  CHANGE `user_id` `user_id` int(10) unsigned,

  CHANGE `article_id` `article_id` int(10) unsigned,

  CHANGE `stock_id` `stock_id` int(10) unsigned,

  CHANGE `list_id` `list_id` int(10) unsigned,

  CHANGE `book_id` `book_id` int(10) unsigned,

  CHANGE `people_id` `people_id` int(10) unsigned,

  CHANGE `job_id` `job_id` int(10) unsigned,

  CHANGE `rayon_id` `rayon_id` int(10) unsigned,

  CHANGE `event_id` `event_id` int(10) unsigned,

  CHANGE `post_id` `post_id` int(10) unsigned,

  CHANGE `collection_id` `collection_id` int(10) unsigned,

  CHANGE `publisher_id` `publisher_id` int(10) unsigned,

  CHANGE `supplier_id` `supplier_id` int(10) unsigned,

  CHANGE `media_id` `media_id` int(10) unsigned,

  CHANGE `bundle_id` `bundle_id` int(10) unsigned,

  CHANGE `link_sponsor_axys_account_id` `link_sponsor_axys_account_id` int(10) unsigned;

ALTER TABLE `lists`

  CHANGE `list_id` `list_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `axys_account_id` `axys_account_id` int(10) unsigned,

  CHANGE `user_id` `user_id` int(10) unsigned,

  CHANGE `site_id` `site_id` int(10) unsigned;

ALTER TABLE `medias`

  CHANGE `media_id` `media_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int(10) unsigned,

  CHANGE `category_id` `category_id` int(10) unsigned;

ALTER TABLE `options`

  CHANGE `option_id` `option_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int(10) unsigned,

  CHANGE `user_id` `user_id` int(10) unsigned;

ALTER TABLE `orders`

  CHANGE `order_id` `order_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `axys_account_id` `axys_account_id` int(10) unsigned,

  CHANGE `user_id` `user_id` int(10) unsigned,

  CHANGE `customer_id` `customer_id` int(10) unsigned,

  CHANGE `seller_id` `seller_id` int(10) unsigned,

  CHANGE `order_gift_recipient` `order_gift_recipient` int(10) unsigned,

  CHANGE `order_amount` `order_amount` int(10) unsigned DEFAULT 0,

  CHANGE `order_discount` `order_discount` int(10) unsigned,

  CHANGE `order_payment_cash` `order_payment_cash` int(10) unsigned DEFAULT 0,

  CHANGE `order_payment_cheque` `order_payment_cheque` int(10) unsigned DEFAULT 0,

  CHANGE `order_payment_transfer` `order_payment_transfer` int(10) unsigned DEFAULT 0,

  CHANGE `order_payment_card` `order_payment_card` int(10) unsigned DEFAULT 0,

  CHANGE `order_payment_paypal` `order_payment_paypal` int(10) unsigned DEFAULT 0,

  CHANGE `order_payment_payplug` `order_payment_payplug` int(10) unsigned DEFAULT 0,

  CHANGE `order_payment_left` `order_payment_left` int(10) unsigned DEFAULT 0;

ALTER TABLE `pages`

  CHANGE `site_id` `site_id` int(10) unsigned;

ALTER TABLE `payments`

  CHANGE `payment_id` `payment_id` int(10) unsigned NOT NULL AUTO_INCREMENT;

ALTER TABLE `people`

  CHANGE `people_id` `people_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `people_pseudo` `people_pseudo` int(10) unsigned,

  CHANGE `people_hits` `people_hits` int(10) unsigned;

ALTER TABLE `permissions`

  CHANGE `permission_id` `permission_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `user_id` `user_id` int(10) unsigned;

ALTER TABLE `posts`

  CHANGE `user_id` `user_id` int(10) unsigned,

  CHANGE `site_id` `site_id` int(10) unsigned,

  CHANGE `publisher_id` `publisher_id` int(10) unsigned,

  CHANGE `category_id` `category_id` int(10) unsigned,

  CHANGE `post_hits` `post_hits` int(10) unsigned;

ALTER TABLE `prices`

  CHANGE `price_id` `price_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `pricegrid_id` `pricegrid_id` int(10) unsigned,

  CHANGE `price_amount` `price_amount` int(10) unsigned;

ALTER TABLE `publishers`

  CHANGE `publisher_id` `publisher_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int(10) unsigned,

  CHANGE `publisher_shipping_fee` `publisher_shipping_fee` int(10) unsigned,

  CHANGE `publisher_gln` `publisher_gln` bigint(20) unsigned;

ALTER TABLE `rayons`

  CHANGE `rayon_id` `rayon_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int(10) unsigned,

  CHANGE `rayon_show_upcoming` `rayon_show_upcoming` tinyint(3) unsigned DEFAULT 0;

ALTER TABLE `redirections`

  CHANGE `redirection_id` `redirection_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int(10) unsigned;

ALTER TABLE `rights`

  CHANGE `right_id` `right_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `axys_account_id` `axys_account_id` int(10) unsigned,

  CHANGE `user_id` `user_id` int(10) unsigned,

  CHANGE `site_id` `site_id` int(10) unsigned,

  CHANGE `publisher_id` `publisher_id` int(10) unsigned;

ALTER TABLE `roles`

  CHANGE `id` `id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `article_id` `article_id` int(10) unsigned,

  CHANGE `book_id` `book_id` int(10) unsigned,

  CHANGE `event_id` `event_id` int(10) unsigned,

  CHANGE `people_id` `people_id` int(10) unsigned,

  CHANGE `job_id` `job_id` int(10) unsigned,

  CHANGE `axys_account_id` `axys_account_id` int(10) unsigned,

  CHANGE `user_id` `user_id` int(10) unsigned,

  CHANGE `role_hide` `role_hide` tinyint(3) unsigned;

ALTER TABLE `session`

  CHANGE `session_id` `session_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `axys_account_id` `axys_account_id` int(10) unsigned,

  CHANGE `user_id` `user_id` int(10) unsigned;

ALTER TABLE `shipping`

  CHANGE `shipping_id` `shipping_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `article_id` `article_id` int(10) unsigned,

  CHANGE `shipping_zone_id` `shipping_zone_id` int(10) unsigned,

  CHANGE `shipping_max_weight` `shipping_max_weight` int(10) unsigned,

  CHANGE `shipping_max_articles` `shipping_max_articles` int(10) unsigned,

  CHANGE `shipping_max_amount` `shipping_max_amount` int(10) unsigned;

ALTER TABLE `signings`

  CHANGE `signing_id` `signing_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int(10) unsigned,

  CHANGE `publisher_id` `publisher_id` int(10) unsigned,

  CHANGE `people_id` `people_id` int(10) unsigned;

ALTER TABLE `sites`

  CHANGE `site_id` `site_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_event_id` `site_event_id` int(10) unsigned,

  CHANGE `site_event_date` `site_event_date` int(10) unsigned,

  CHANGE `publisher_id` `publisher_id` int(10) unsigned,

  CHANGE `site_fb_page_id` `site_fb_page_id` bigint(20) unsigned;

ALTER TABLE `stock`

  CHANGE `stock_id` `stock_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int(10) unsigned,

  CHANGE `article_id` `article_id` int(10) unsigned,

  CHANGE `campaign_id` `campaign_id` int(10) unsigned,

  CHANGE `reward_id` `reward_id` int(10) unsigned,

  CHANGE `user_id` `user_id` int(10) unsigned,

  CHANGE `customer_id` `customer_id` int(10) unsigned,

  CHANGE `wish_id` `wish_id` int(10) unsigned,

  CHANGE `cart_id` `cart_id` int(10) unsigned,

  CHANGE `order_id` `order_id` int(10) unsigned,

  CHANGE `coupon_id` `coupon_id` int(10) unsigned,

  CHANGE `stock_shop` `stock_shop` int(10) unsigned,

  CHANGE `stock_purchase_price` `stock_purchase_price` int(10) unsigned,

  CHANGE `stock_selling_price` `stock_selling_price` int(10) unsigned,

  CHANGE `stock_selling_price2` `stock_selling_price2` int(10) unsigned,

  CHANGE `stock_selling_price_saved` `stock_selling_price_saved` int(10) unsigned,

  CHANGE `stock_selling_price_ht` `stock_selling_price_ht` int(10) unsigned,

  CHANGE `stock_selling_price_tva` `stock_selling_price_tva` int(10) unsigned,

  CHANGE `stock_weight` `stock_weight` int(10) unsigned,

  CHANGE `stock_pub_year` `stock_pub_year` int(10) unsigned,

  CHANGE `stock_photo_version` `stock_photo_version` int(10) unsigned DEFAULT 0;

ALTER TABLE `subscriptions`

  CHANGE `subscription_id` `subscription_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int(10) unsigned,

  CHANGE `axys_account_id` `axys_account_id` int(10) unsigned,

  CHANGE `user_id` `user_id` int(10) unsigned,

  CHANGE `publisher_id` `publisher_id` int(10) unsigned;

ALTER TABLE `suppliers`

  CHANGE `supplier_id` `supplier_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int(10) unsigned,

  CHANGE `supplier_remise` `supplier_remise` int(10) unsigned;

ALTER TABLE `tags`

  CHANGE `tag_num` `tag_num` int(10) unsigned;

ALTER TABLE `tags_articles`

  CHANGE `article_id` `article_id` int(10) unsigned NOT NULL;

ALTER TABLE `users`

  CHANGE `site_id` `site_id` INTEGER NOT NULL;

ALTER TABLE `votes`

  CHANGE `vote_id` `vote_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int(10) unsigned,

  CHANGE `axys_account_id` `axys_account_id` int(10) unsigned,

  CHANGE `user_id` `user_id` int(10) unsigned,

  CHANGE `vote_F` `vote_F` int(10) unsigned,

  CHANGE `vote_E` `vote_E` int(10) unsigned;

ALTER TABLE `wishes`

  CHANGE `wish_id` `wish_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `axys_account_id` `axys_account_id` int(10) unsigned,

  CHANGE `user_id` `user_id` int(10) unsigned,

  CHANGE `site_id` `site_id` int(10) unsigned,

  CHANGE `article_id` `article_id` int(10) unsigned;

ALTER TABLE `wishlist`

  CHANGE `wishlist_id` `wishlist_id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  CHANGE `site_id` `site_id` int(10) unsigned,

  CHANGE `axys_account_id` `axys_account_id` int(10) unsigned,

  CHANGE `user_id` `user_id` int(10) unsigned;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
EOT;

        return [
            'default' => $connection_default,
        ];
    }

}
