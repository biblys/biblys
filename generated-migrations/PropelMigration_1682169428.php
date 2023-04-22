<?php
use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1682169428.
 * Generated on 2023-04-22 13:17:08 by clement */
class PropelMigration_1682169428{
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

  CHANGE `alert_insert` `alert_insert` TIMESTAMP NULL,

  CHANGE `alert_update` `alert_update` TIMESTAMP NULL,

  CHANGE `alert_created` `alert_created` TIMESTAMP NULL,

  CHANGE `alert_updated` `alert_updated` TIMESTAMP NULL;

ALTER TABLE `articles`

  CHANGE `article_keywords_generated` `article_keywords_generated` TIMESTAMP NULL,

  CHANGE `article_insert` `article_insert` TIMESTAMP NULL,

  CHANGE `article_update` `article_update` TIMESTAMP NULL,

  CHANGE `article_created` `article_created` TIMESTAMP NULL,

  CHANGE `article_updated` `article_updated` TIMESTAMP NULL,

  CHANGE `article_deletion_date` `article_deletion_date` TIMESTAMP NULL;

ALTER TABLE `awards`

  CHANGE `award_date` `award_date` TIMESTAMP NULL,

  CHANGE `award_created` `award_created` TIMESTAMP NULL,

  CHANGE `award_updated` `award_updated` TIMESTAMP NULL;

ALTER TABLE `axys_apps`

  CHANGE `created_at` `created_at` TIMESTAMP NULL,

  CHANGE `updated_at` `updated_at` TIMESTAMP NULL;

ALTER TABLE `bookshops`

  CHANGE `bookshop_updated` `bookshop_updated` TIMESTAMP NULL;

ALTER TABLE `carts`

  CHANGE `cart_date` `cart_date` TIMESTAMP NULL,

  CHANGE `cart_update` `cart_update` TIMESTAMP NULL,

  CHANGE `cart_created` `cart_created` TIMESTAMP NULL,

  CHANGE `cart_updated` `cart_updated` TIMESTAMP NULL;

ALTER TABLE `categories`

  CHANGE `category_insert` `category_insert` TIMESTAMP NULL,

  CHANGE `category_update` `category_update` TIMESTAMP NULL,

  CHANGE `category_created` `category_created` TIMESTAMP NULL,

  CHANGE `category_updated` `category_updated` TIMESTAMP NULL;

ALTER TABLE `cf_campaigns`

  CHANGE `campaign_created` `campaign_created` TIMESTAMP NULL,

  CHANGE `campaign_updated` `campaign_updated` TIMESTAMP NULL;

ALTER TABLE `cf_rewards`

  CHANGE `reward_created` `reward_created` TIMESTAMP NULL,

  CHANGE `reward_updated` `reward_updated` TIMESTAMP NULL;

ALTER TABLE `collections`

  CHANGE `collection_update` `collection_update` TIMESTAMP NULL,

  CHANGE `collection_created` `collection_created` TIMESTAMP NULL,

  CHANGE `collection_updated` `collection_updated` TIMESTAMP NULL;

ALTER TABLE `countries`

  CHANGE `country_created` `country_created` TIMESTAMP NULL,

  CHANGE `country_updated` `country_updated` TIMESTAMP NULL;

ALTER TABLE `coupons`

  CHANGE `coupon_used` `coupon_used` TIMESTAMP NULL,

  CHANGE `coupon_insert` `coupon_insert` TIMESTAMP NULL,

  CHANGE `coupon_update` `coupon_update` TIMESTAMP NULL;

ALTER TABLE `cron_jobs`

  CHANGE `cron_job_created` `cron_job_created` TIMESTAMP NULL,

  CHANGE `cron_job_updated` `cron_job_updated` TIMESTAMP NULL;

ALTER TABLE `customers`

  CHANGE `customer_insert` `customer_insert` TIMESTAMP NULL,

  CHANGE `customer_update` `customer_update` TIMESTAMP NULL,

  CHANGE `customer_created` `customer_created` TIMESTAMP NULL,

  CHANGE `customer_updated` `customer_updated` TIMESTAMP NULL;

ALTER TABLE `cycles`

  CHANGE `cycle_insert` `cycle_insert` TIMESTAMP NULL,

  CHANGE `cycle_update` `cycle_update` TIMESTAMP NULL,

  CHANGE `cycle_created` `cycle_created` TIMESTAMP NULL,

  CHANGE `cycle_updated` `cycle_updated` TIMESTAMP NULL;

ALTER TABLE `downloads`

  CHANGE `download_date` `download_date` TIMESTAMP NULL,

  CHANGE `download_created` `download_created` TIMESTAMP NULL,

  CHANGE `download_updated` `download_updated` TIMESTAMP NULL;

ALTER TABLE `events`

  CHANGE `event_start` `event_start` TIMESTAMP NULL,

  CHANGE `event_end` `event_end` TIMESTAMP NULL,

  CHANGE `event_insert_` `event_insert_` TIMESTAMP NULL,

  CHANGE `event_update_` `event_update_` TIMESTAMP NULL,

  CHANGE `event_created` `event_created` TIMESTAMP NULL,

  CHANGE `event_updated` `event_updated` TIMESTAMP NULL;

ALTER TABLE `files`

  CHANGE `file_inserted` `file_inserted` TIMESTAMP NULL,

  CHANGE `file_uploaded` `file_uploaded` TIMESTAMP NULL,

  CHANGE `file_updated` `file_updated` TIMESTAMP NULL,

  CHANGE `file_created` `file_created` TIMESTAMP NULL;

ALTER TABLE `galleries`

  CHANGE `gallery_insert` `gallery_insert` TIMESTAMP NULL,

  CHANGE `gallery_update` `gallery_update` TIMESTAMP NULL,

  CHANGE `gallery_created` `gallery_created` TIMESTAMP NULL,

  CHANGE `gallery_updated` `gallery_updated` TIMESTAMP NULL;

ALTER TABLE `images`

  CHANGE `image_inserted` `image_inserted` TIMESTAMP NULL,

  CHANGE `image_uploaded` `image_uploaded` TIMESTAMP NULL,

  CHANGE `image_updated` `image_updated` TIMESTAMP NULL;

ALTER TABLE `inventory`

  CHANGE `inventory_created` `inventory_created` TIMESTAMP NULL,

  CHANGE `inventory_updated` `inventory_updated` TIMESTAMP NULL;

ALTER TABLE `inventory_item`

  CHANGE `ii_created` `ii_created` TIMESTAMP NULL,

  CHANGE `ii_updated` `ii_updated` TIMESTAMP NULL;

ALTER TABLE `jobs`

  CHANGE `job_created` `job_created` TIMESTAMP NULL,

  CHANGE `job_updated` `job_updated` TIMESTAMP NULL;

ALTER TABLE `langs`

  CHANGE `lang_created` `lang_created` TIMESTAMP NULL,

  CHANGE `lang_updated` `lang_updated` TIMESTAMP NULL;

ALTER TABLE `libraries`

  CHANGE `library_updated` `library_updated` TIMESTAMP NULL;

ALTER TABLE `links`

  CHANGE `link_date` `link_date` TIMESTAMP NULL,

  CHANGE `link_created` `link_created` TIMESTAMP NULL,

  CHANGE `link_updated` `link_updated` TIMESTAMP NULL;

ALTER TABLE `lists`

  CHANGE `list_created` `list_created` TIMESTAMP NULL,

  CHANGE `list_updated` `list_updated` TIMESTAMP NULL;

ALTER TABLE `mailing`

  CHANGE `mailing_date` `mailing_date` TIMESTAMP NULL,

  CHANGE `mailing_created` `mailing_created` TIMESTAMP NULL,

  CHANGE `mailing_updated` `mailing_updated` TIMESTAMP NULL;

ALTER TABLE `medias`

  CHANGE `media_insert` `media_insert` TIMESTAMP NULL,

  CHANGE `media_update` `media_update` TIMESTAMP NULL,

  CHANGE `media_created` `media_created` TIMESTAMP NULL,

  CHANGE `media_updated` `media_updated` TIMESTAMP NULL;

ALTER TABLE `options`

  CHANGE `option_created` `option_created` TIMESTAMP NULL,

  CHANGE `option_updated` `option_updated` TIMESTAMP NULL;

ALTER TABLE `orders`

  CHANGE `order_insert` `order_insert` TIMESTAMP NULL,

  CHANGE `order_payment_date` `order_payment_date` TIMESTAMP NULL,

  CHANGE `order_shipping_date` `order_shipping_date` TIMESTAMP NULL,

  CHANGE `order_followup_date` `order_followup_date` TIMESTAMP NULL,

  CHANGE `order_confirmation_date` `order_confirmation_date` TIMESTAMP NULL,

  CHANGE `order_cancel_date` `order_cancel_date` TIMESTAMP NULL,

  CHANGE `order_update` `order_update` TIMESTAMP NULL,

  CHANGE `order_created` `order_created` TIMESTAMP NULL,

  CHANGE `order_updated` `order_updated` TIMESTAMP NULL;

ALTER TABLE `pages`

  CHANGE `page_insert` `page_insert` TIMESTAMP NULL,

  CHANGE `page_update` `page_update` TIMESTAMP NULL,

  CHANGE `page_created` `page_created` TIMESTAMP NULL,

  CHANGE `page_updated` `page_updated` TIMESTAMP NULL;

ALTER TABLE `payments`

  CHANGE `payment_created` `payment_created` TIMESTAMP NULL,

  CHANGE `payment_executed` `payment_executed` TIMESTAMP NULL,

  CHANGE `payment_updated` `payment_updated` TIMESTAMP NULL;

ALTER TABLE `people`

  CHANGE `people_date` `people_date` TIMESTAMP NULL,

  CHANGE `people_insert` `people_insert` TIMESTAMP NULL,

  CHANGE `people_update` `people_update` TIMESTAMP NULL,

  CHANGE `people_created` `people_created` TIMESTAMP NULL,

  CHANGE `people_updated` `people_updated` TIMESTAMP NULL;

ALTER TABLE `permissions`

  CHANGE `permission_last` `permission_last` TIMESTAMP NULL,

  CHANGE `permission_date` `permission_date` TIMESTAMP NULL;

ALTER TABLE `posts`

  CHANGE `post_keywords_generated` `post_keywords_generated` TIMESTAMP NULL,

  CHANGE `post_date` `post_date` TIMESTAMP NULL,

  CHANGE `post_insert` `post_insert` TIMESTAMP NULL,

  CHANGE `post_update` `post_update` TIMESTAMP NULL,

  CHANGE `post_created` `post_created` TIMESTAMP NULL;

ALTER TABLE `prices`

  CHANGE `price_created` `price_created` TIMESTAMP NULL,

  CHANGE `price_updated` `price_updated` TIMESTAMP NULL;

ALTER TABLE `publishers`

  CHANGE `publisher_update` `publisher_update` TIMESTAMP NULL,

  CHANGE `publisher_created` `publisher_created` TIMESTAMP NULL,

  CHANGE `publisher_updated` `publisher_updated` TIMESTAMP NULL;

ALTER TABLE `rayons`

  CHANGE `rayon_created` `rayon_created` TIMESTAMP NULL,

  CHANGE `rayon_updated` `rayon_updated` TIMESTAMP NULL;

ALTER TABLE `redirections`

  CHANGE `redirection_date` `redirection_date` TIMESTAMP NULL,

  CHANGE `redirection_created` `redirection_created` TIMESTAMP NULL,

  CHANGE `redirection_updated` `redirection_updated` TIMESTAMP NULL;

ALTER TABLE `rights`

  CHANGE `right_created` `right_created` TIMESTAMP NULL,

  CHANGE `right_updated` `right_updated` TIMESTAMP NULL;

ALTER TABLE `roles`

  CHANGE `role_date` `role_date` TIMESTAMP NULL,

  CHANGE `role_created` `role_created` TIMESTAMP NULL,

  CHANGE `role_updated` `role_updated` TIMESTAMP NULL;

ALTER TABLE `session`

  CHANGE `session_created` `session_created` TIMESTAMP NULL,

  CHANGE `session_expires` `session_expires` TIMESTAMP NULL,

  CHANGE `session_updated` `session_updated` TIMESTAMP NULL;

ALTER TABLE `shipping`

  CHANGE `shipping_created` `shipping_created` TIMESTAMP NULL,

  CHANGE `shipping_updated` `shipping_updated` TIMESTAMP NULL;

ALTER TABLE `signings`

  CHANGE `signing_created` `signing_created` TIMESTAMP NULL,

  CHANGE `signing_updated` `signing_updated` TIMESTAMP NULL;

ALTER TABLE `sites`

  CHANGE `site_sitemap_updated` `site_sitemap_updated` TIMESTAMP NULL,

  CHANGE `site_created` `site_created` TIMESTAMP NULL,

  CHANGE `site_updated` `site_updated` TIMESTAMP NULL;

ALTER TABLE `stock`

  CHANGE `stock_purchase_date` `stock_purchase_date` TIMESTAMP NULL,

  CHANGE `stock_onsale_date` `stock_onsale_date` TIMESTAMP NULL,

  CHANGE `stock_cart_date` `stock_cart_date` TIMESTAMP NULL,

  CHANGE `stock_selling_date` `stock_selling_date` TIMESTAMP NULL,

  CHANGE `stock_return_date` `stock_return_date` TIMESTAMP NULL,

  CHANGE `stock_lost_date` `stock_lost_date` TIMESTAMP NULL,

  CHANGE `stock_insert` `stock_insert` TIMESTAMP NULL,

  CHANGE `stock_update` `stock_update` TIMESTAMP NULL,

  CHANGE `stock_created` `stock_created` TIMESTAMP NULL,

  CHANGE `stock_updated` `stock_updated` TIMESTAMP NULL;

ALTER TABLE `subscriptions`

  CHANGE `subscription_insert` `subscription_insert` TIMESTAMP NULL,

  CHANGE `subscription_update` `subscription_update` TIMESTAMP NULL,

  CHANGE `subscription_created` `subscription_created` TIMESTAMP NULL,

  CHANGE `subscription_updated` `subscription_updated` TIMESTAMP NULL;

ALTER TABLE `suppliers`

  CHANGE `supplier_insert` `supplier_insert` TIMESTAMP NULL,

  CHANGE `supplier_update` `supplier_update` TIMESTAMP NULL,

  CHANGE `supplier_created` `supplier_created` TIMESTAMP NULL,

  CHANGE `supplier_updated` `supplier_updated` TIMESTAMP NULL;

ALTER TABLE `tags`

  CHANGE `tag_date` `tag_date` TIMESTAMP NULL,

  CHANGE `tag_insert` `tag_insert` TIMESTAMP NULL,

  CHANGE `tag_update` `tag_update` TIMESTAMP NULL,

  CHANGE `tag_created` `tag_created` TIMESTAMP NULL,

  CHANGE `tag_updated` `tag_updated` TIMESTAMP NULL;

ALTER TABLE `ticket`

  CHANGE `ticket_created` `ticket_created` TIMESTAMP NULL,

  CHANGE `ticket_updated` `ticket_updated` TIMESTAMP NULL,

  CHANGE `ticket_resolved` `ticket_resolved` TIMESTAMP NULL,

  CHANGE `ticket_closed` `ticket_closed` TIMESTAMP NULL;

ALTER TABLE `ticket_comment`

  CHANGE `ticket_comment_created` `ticket_comment_created` TIMESTAMP NULL,

  CHANGE `ticket_comment_update` `ticket_comment_update` TIMESTAMP NULL;

ALTER TABLE `users`

  CHANGE `DateInscription` `DateInscription` TIMESTAMP NULL,

  CHANGE `DateConnexion` `DateConnexion` TIMESTAMP NULL,

  CHANGE `user_password_reset_token_created` `user_password_reset_token_created` TIMESTAMP NULL,

  CHANGE `user_update` `user_update` TIMESTAMP NULL,

  CHANGE `user_created` `user_created` TIMESTAMP NULL,

  CHANGE `user_updated` `user_updated` TIMESTAMP NULL;

ALTER TABLE `votes`

  CHANGE `vote_date` `vote_date` TIMESTAMP NULL;

ALTER TABLE `wishes`

  CHANGE `wish_created` `wish_created` TIMESTAMP NULL,

  CHANGE `wish_updated` `wish_updated` TIMESTAMP NULL,

  CHANGE `wish_bought` `wish_bought` TIMESTAMP NULL;

ALTER TABLE `wishlist`

  CHANGE `wishlist_created` `wishlist_created` TIMESTAMP NULL,

  CHANGE `wishlist_updated` `wishlist_updated` TIMESTAMP NULL;

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

  CHANGE `alert_insert` `alert_insert` DATETIME,

  CHANGE `alert_update` `alert_update` DATETIME,

  CHANGE `alert_created` `alert_created` DATETIME,

  CHANGE `alert_updated` `alert_updated` DATETIME;

ALTER TABLE `articles`

  CHANGE `article_keywords_generated` `article_keywords_generated` DATETIME,

  CHANGE `article_insert` `article_insert` DATETIME,

  CHANGE `article_update` `article_update` DATETIME,

  CHANGE `article_created` `article_created` DATETIME,

  CHANGE `article_updated` `article_updated` DATETIME,

  CHANGE `article_deletion_date` `article_deletion_date` DATETIME;

ALTER TABLE `awards`

  CHANGE `award_date` `award_date` DATETIME,

  CHANGE `award_created` `award_created` DATETIME,

  CHANGE `award_updated` `award_updated` DATETIME;

ALTER TABLE `axys_apps`

  CHANGE `created_at` `created_at` DATETIME,

  CHANGE `updated_at` `updated_at` DATETIME;

ALTER TABLE `bookshops`

  CHANGE `bookshop_updated` `bookshop_updated` DATETIME;

ALTER TABLE `carts`

  CHANGE `cart_date` `cart_date` DATETIME,

  CHANGE `cart_update` `cart_update` DATETIME,

  CHANGE `cart_created` `cart_created` DATETIME,

  CHANGE `cart_updated` `cart_updated` DATETIME;

ALTER TABLE `categories`

  CHANGE `category_insert` `category_insert` DATETIME,

  CHANGE `category_update` `category_update` DATETIME,

  CHANGE `category_created` `category_created` DATETIME,

  CHANGE `category_updated` `category_updated` DATETIME;

ALTER TABLE `cf_campaigns`

  CHANGE `campaign_created` `campaign_created` DATETIME,

  CHANGE `campaign_updated` `campaign_updated` DATETIME;

ALTER TABLE `cf_rewards`

  CHANGE `reward_created` `reward_created` DATETIME,

  CHANGE `reward_updated` `reward_updated` DATETIME;

ALTER TABLE `collections`

  CHANGE `collection_update` `collection_update` DATETIME,

  CHANGE `collection_created` `collection_created` DATETIME,

  CHANGE `collection_updated` `collection_updated` DATETIME;

ALTER TABLE `countries`

  CHANGE `country_created` `country_created` DATETIME,

  CHANGE `country_updated` `country_updated` DATETIME;

ALTER TABLE `coupons`

  CHANGE `coupon_used` `coupon_used` DATETIME,

  CHANGE `coupon_insert` `coupon_insert` DATETIME,

  CHANGE `coupon_update` `coupon_update` DATETIME;

ALTER TABLE `cron_jobs`

  CHANGE `cron_job_created` `cron_job_created` DATETIME,

  CHANGE `cron_job_updated` `cron_job_updated` DATETIME;

ALTER TABLE `customers`

  CHANGE `customer_insert` `customer_insert` DATETIME,

  CHANGE `customer_update` `customer_update` DATETIME,

  CHANGE `customer_created` `customer_created` DATETIME,

  CHANGE `customer_updated` `customer_updated` DATETIME;

ALTER TABLE `cycles`

  CHANGE `cycle_insert` `cycle_insert` DATETIME,

  CHANGE `cycle_update` `cycle_update` DATETIME,

  CHANGE `cycle_created` `cycle_created` DATETIME,

  CHANGE `cycle_updated` `cycle_updated` DATETIME;

ALTER TABLE `downloads`

  CHANGE `download_date` `download_date` DATETIME,

  CHANGE `download_created` `download_created` DATETIME,

  CHANGE `download_updated` `download_updated` DATETIME;

ALTER TABLE `events`

  CHANGE `event_start` `event_start` DATETIME,

  CHANGE `event_end` `event_end` DATETIME,

  CHANGE `event_insert_` `event_insert_` DATETIME,

  CHANGE `event_update_` `event_update_` DATETIME,

  CHANGE `event_created` `event_created` DATETIME,

  CHANGE `event_updated` `event_updated` DATETIME;

ALTER TABLE `files`

  CHANGE `file_inserted` `file_inserted` DATETIME,

  CHANGE `file_uploaded` `file_uploaded` DATETIME,

  CHANGE `file_updated` `file_updated` DATETIME,

  CHANGE `file_created` `file_created` DATETIME;

ALTER TABLE `galleries`

  CHANGE `gallery_insert` `gallery_insert` DATETIME,

  CHANGE `gallery_update` `gallery_update` DATETIME,

  CHANGE `gallery_created` `gallery_created` DATETIME,

  CHANGE `gallery_updated` `gallery_updated` DATETIME;

ALTER TABLE `images`

  CHANGE `image_inserted` `image_inserted` DATETIME,

  CHANGE `image_uploaded` `image_uploaded` DATETIME,

  CHANGE `image_updated` `image_updated` DATETIME;

ALTER TABLE `inventory`

  CHANGE `inventory_created` `inventory_created` DATETIME,

  CHANGE `inventory_updated` `inventory_updated` DATETIME;

ALTER TABLE `inventory_item`

  CHANGE `ii_created` `ii_created` DATETIME,

  CHANGE `ii_updated` `ii_updated` DATETIME;

ALTER TABLE `jobs`

  CHANGE `job_created` `job_created` DATETIME,

  CHANGE `job_updated` `job_updated` DATETIME;

ALTER TABLE `langs`

  CHANGE `lang_created` `lang_created` DATETIME,

  CHANGE `lang_updated` `lang_updated` DATETIME;

ALTER TABLE `libraries`

  CHANGE `library_updated` `library_updated` DATETIME;

ALTER TABLE `links`

  CHANGE `link_date` `link_date` DATETIME,

  CHANGE `link_created` `link_created` DATETIME,

  CHANGE `link_updated` `link_updated` DATETIME;

ALTER TABLE `lists`

  CHANGE `list_created` `list_created` DATETIME,

  CHANGE `list_updated` `list_updated` DATETIME;

ALTER TABLE `mailing`

  CHANGE `mailing_date` `mailing_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

  CHANGE `mailing_created` `mailing_created` DATETIME NOT NULL,

  CHANGE `mailing_updated` `mailing_updated` DATETIME;

ALTER TABLE `medias`

  CHANGE `media_insert` `media_insert` DATETIME,

  CHANGE `media_update` `media_update` DATETIME,

  CHANGE `media_created` `media_created` DATETIME,

  CHANGE `media_updated` `media_updated` DATETIME;

ALTER TABLE `options`

  CHANGE `option_created` `option_created` DATETIME,

  CHANGE `option_updated` `option_updated` DATETIME;

ALTER TABLE `orders`

  CHANGE `order_insert` `order_insert` DATETIME,

  CHANGE `order_payment_date` `order_payment_date` DATETIME,

  CHANGE `order_shipping_date` `order_shipping_date` DATETIME,

  CHANGE `order_followup_date` `order_followup_date` DATETIME,

  CHANGE `order_confirmation_date` `order_confirmation_date` DATETIME,

  CHANGE `order_cancel_date` `order_cancel_date` DATETIME,

  CHANGE `order_update` `order_update` DATETIME,

  CHANGE `order_created` `order_created` DATETIME,

  CHANGE `order_updated` `order_updated` DATETIME;

ALTER TABLE `pages`

  CHANGE `page_insert` `page_insert` DATETIME,

  CHANGE `page_update` `page_update` DATETIME,

  CHANGE `page_created` `page_created` DATETIME,

  CHANGE `page_updated` `page_updated` DATETIME;

ALTER TABLE `payments`

  CHANGE `payment_created` `payment_created` DATETIME,

  CHANGE `payment_executed` `payment_executed` DATETIME,

  CHANGE `payment_updated` `payment_updated` DATETIME;

ALTER TABLE `people`

  CHANGE `people_date` `people_date` DATETIME,

  CHANGE `people_insert` `people_insert` DATETIME,

  CHANGE `people_update` `people_update` DATETIME,

  CHANGE `people_created` `people_created` DATETIME,

  CHANGE `people_updated` `people_updated` DATETIME;

ALTER TABLE `permissions`

  CHANGE `permission_last` `permission_last` DATETIME,

  CHANGE `permission_date` `permission_date` DATETIME;

ALTER TABLE `posts`

  CHANGE `post_keywords_generated` `post_keywords_generated` DATETIME,

  CHANGE `post_date` `post_date` DATETIME,

  CHANGE `post_insert` `post_insert` DATETIME,

  CHANGE `post_update` `post_update` DATETIME,

  CHANGE `post_created` `post_created` DATETIME;

ALTER TABLE `prices`

  CHANGE `price_created` `price_created` DATETIME,

  CHANGE `price_updated` `price_updated` DATETIME;

ALTER TABLE `publishers`

  CHANGE `publisher_update` `publisher_update` DATETIME,

  CHANGE `publisher_created` `publisher_created` DATETIME,

  CHANGE `publisher_updated` `publisher_updated` DATETIME;

ALTER TABLE `rayons`

  CHANGE `rayon_created` `rayon_created` DATETIME,

  CHANGE `rayon_updated` `rayon_updated` DATETIME;

ALTER TABLE `redirections`

  CHANGE `redirection_date` `redirection_date` DATETIME,

  CHANGE `redirection_created` `redirection_created` DATETIME,

  CHANGE `redirection_updated` `redirection_updated` DATETIME;

ALTER TABLE `rights`

  CHANGE `right_created` `right_created` DATETIME,

  CHANGE `right_updated` `right_updated` DATETIME;

ALTER TABLE `roles`

  CHANGE `role_date` `role_date` DATETIME,

  CHANGE `role_created` `role_created` DATETIME,

  CHANGE `role_updated` `role_updated` DATETIME;

ALTER TABLE `session`

  CHANGE `session_created` `session_created` DATETIME,

  CHANGE `session_expires` `session_expires` DATETIME,

  CHANGE `session_updated` `session_updated` DATETIME;

ALTER TABLE `shipping`

  CHANGE `shipping_created` `shipping_created` DATETIME,

  CHANGE `shipping_updated` `shipping_updated` DATETIME;

ALTER TABLE `signings`

  CHANGE `signing_created` `signing_created` DATETIME,

  CHANGE `signing_updated` `signing_updated` DATETIME;

ALTER TABLE `sites`

  CHANGE `site_sitemap_updated` `site_sitemap_updated` DATETIME,

  CHANGE `site_created` `site_created` DATETIME,

  CHANGE `site_updated` `site_updated` DATETIME;

ALTER TABLE `stock`

  CHANGE `stock_purchase_date` `stock_purchase_date` DATETIME,

  CHANGE `stock_onsale_date` `stock_onsale_date` DATETIME,

  CHANGE `stock_cart_date` `stock_cart_date` DATETIME,

  CHANGE `stock_selling_date` `stock_selling_date` DATETIME,

  CHANGE `stock_return_date` `stock_return_date` DATETIME,

  CHANGE `stock_lost_date` `stock_lost_date` DATETIME,


  CHANGE `stock_update` `stock_update` DATETIME,

  CHANGE `stock_created` `stock_created` DATETIME,

  CHANGE `stock_updated` `stock_updated` DATETIME;

ALTER TABLE `subscriptions`

  CHANGE `subscription_insert` `subscription_insert` DATETIME,

  CHANGE `subscription_update` `subscription_update` DATETIME,

  CHANGE `subscription_created` `subscription_created` DATETIME,

  CHANGE `subscription_updated` `subscription_updated` DATETIME;

ALTER TABLE `suppliers`

  CHANGE `supplier_insert` `supplier_insert` DATETIME,

  CHANGE `supplier_update` `supplier_update` DATETIME,

  CHANGE `supplier_created` `supplier_created` DATETIME,

  CHANGE `supplier_updated` `supplier_updated` DATETIME;

ALTER TABLE `tags`

  CHANGE `tag_date` `tag_date` DATETIME,

  CHANGE `tag_insert` `tag_insert` DATETIME,

  CHANGE `tag_update` `tag_update` DATETIME,

  CHANGE `tag_created` `tag_created` DATETIME,

  CHANGE `tag_updated` `tag_updated` DATETIME;

ALTER TABLE `ticket`

  CHANGE `ticket_created` `ticket_created` DATETIME,

  CHANGE `ticket_updated` `ticket_updated` DATETIME,

  CHANGE `ticket_resolved` `ticket_resolved` DATETIME,

  CHANGE `ticket_closed` `ticket_closed` DATETIME;

ALTER TABLE `ticket_comment`

  CHANGE `ticket_comment_created` `ticket_comment_created` DATETIME,

  CHANGE `ticket_comment_update` `ticket_comment_update` DATETIME;

ALTER TABLE `users`

  CHANGE `DateInscription` `DateInscription` DATETIME,

  CHANGE `DateConnexion` `DateConnexion` DATETIME,

  CHANGE `user_password_reset_token_created` `user_password_reset_token_created` DATETIME,

  CHANGE `user_update` `user_update` DATETIME,

  CHANGE `user_created` `user_created` DATETIME,

  CHANGE `user_updated` `user_updated` DATETIME;

ALTER TABLE `votes`

  CHANGE `vote_date` `vote_date` DATETIME;

ALTER TABLE `wishes`

  CHANGE `wish_created` `wish_created` DATETIME,

  CHANGE `wish_updated` `wish_updated` DATETIME,

  CHANGE `wish_bought` `wish_bought` DATETIME;

ALTER TABLE `wishlist`

  CHANGE `wishlist_created` `wishlist_created` DATETIME,

  CHANGE `wishlist_updated` `wishlist_updated` DATETIME;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
EOT;

        return [
            'default' => $connection_default,
        ];
    }

}
