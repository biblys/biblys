<?php
use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1633183736.
 * Generated on 2021-10-02 16:08:56 by root 
 */
class PropelMigration_1633183736 
{
    public $comment = '';

    public function preUp(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postUp(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    public function preDown(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

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
    public function getUpSQL()
    {
        $connection_default = <<< 'EOT'

# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `alerts`

  DROP `alert_deleted`;

ALTER TABLE `articles`

  DROP `article_deleted`;

ALTER TABLE `awards`

  DROP `award_deleted`;

ALTER TABLE `bookshops`

  DROP `bookshop_deleted`;

ALTER TABLE `carts`

  DROP `cart_deleted`;

ALTER TABLE `categories`

  DROP `category_deleted`;

ALTER TABLE `cf_campaigns`

  DROP `campaign_deleted`;

ALTER TABLE `cf_rewards`

  DROP `reward_deleted`;

ALTER TABLE `collections`

  DROP `collection_deleted`;

ALTER TABLE `countries`

  DROP `country_deleted`;

ALTER TABLE `cron_jobs`

  DROP `cron_job_deleted`;

ALTER TABLE `customers`

  DROP `customer_deleted`;

ALTER TABLE `cycles`

  DROP `cycle_deleted`;

ALTER TABLE `downloads`

  DROP `download_deleted`;

DROP INDEX `url` ON `events`;

ALTER TABLE `events`

  DROP `event_deleted`;

CREATE UNIQUE INDEX `url` ON `events` (`site_id`, `event_url`);

ALTER TABLE `files`

  DROP `file_deleted`;

ALTER TABLE `galleries`

  DROP `gallery_deleted`;

ALTER TABLE `images`

  DROP `image_deleted`;

ALTER TABLE `inventory`

  DROP `inventory_deleted`;

ALTER TABLE `inventory_item`

  DROP `ii_deleted`;

ALTER TABLE `jobs`

  CHANGE `job_event` `job_event` TINYINT(1),

  DROP `job_deleted`;

ALTER TABLE `langs`

  DROP `lang_deleted`;

ALTER TABLE `libraries`

  DROP `library_deleted`;

DROP INDEX `site_id` ON `links`;

ALTER TABLE `links`

  DROP `link_deleted`;

CREATE UNIQUE INDEX `site_id` ON `links` (`site_id`, `article_id`, `link_do_not_reorder`);

ALTER TABLE `lists`

  DROP `list_deleted`;

ALTER TABLE `mailing`

  DROP `mailing_deleted`;

ALTER TABLE `medias`

  DROP `media_deleted`;

ALTER TABLE `option`

  DROP `option_deleted`;

ALTER TABLE `orders`

  DROP `order_deleted`;

ALTER TABLE `pages`

  DROP `page_deleted`;

ALTER TABLE `payments`

  DROP `payment_deleted`;

ALTER TABLE `people`

  DROP `people_deleted`;

ALTER TABLE `posts`

  DROP `post_deleted`;

ALTER TABLE `prices`

  DROP `price_deleted`;

ALTER TABLE `publishers`

  DROP `publisher_deleted`;

ALTER TABLE `rayons`

  CHANGE `rayon_url` `rayon_url` TEXT,

  DROP `rayon_deleted`;

ALTER TABLE `redirections`

  DROP `redirection_deleted`;

ALTER TABLE `rights`

  DROP `right_deleted`;

ALTER TABLE `roles`

  DROP `role_deleted`;

ALTER TABLE `session`

  DROP `session_deleted`;

ALTER TABLE `shipping`

  DROP `shipping_deleted`;

ALTER TABLE `signings`

  DROP `signing_deleted`;

ALTER TABLE `sites`

  DROP `site_deleted`;

ALTER TABLE `stock`

  DROP `stock_deleted`;

ALTER TABLE `subscriptions`

  DROP `subscription_deleted`;

ALTER TABLE `suppliers`

  DROP `supplier_deleted`;

ALTER TABLE `tags`

  DROP `tag_deleted`;

ALTER TABLE `ticket`

  DROP `ticket_deleted`;

ALTER TABLE `ticket_comment`

  DROP `ticket_comment_deleted`;

ALTER TABLE `users`

  DROP `user_deleted`,

  DROP `user_deleted_why`;

DROP INDEX `user_id` ON `wishes`;

ALTER TABLE `wishes`

  DROP `wish_deleted`;

CREATE UNIQUE INDEX `user_id` ON `wishes` (`user_id`, `article_id`);

ALTER TABLE `wishlist`

  DROP `wishlist_deleted`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
EOT;

        return array(
            'default' => $connection_default,
        );
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        $connection_default = <<< 'EOT'

# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `alerts`

  ADD `alert_deleted` DATETIME AFTER `alert_updated`;

ALTER TABLE `articles`

  ADD `article_deleted` DATETIME AFTER `article_updated`;

ALTER TABLE `awards`

  ADD `award_deleted` DATETIME AFTER `award_updated`;

ALTER TABLE `bookshops`

  ADD `bookshop_deleted` DATETIME AFTER `bookshop_updated`;

ALTER TABLE `carts`

  ADD `cart_deleted` DATETIME AFTER `cart_updated`;

ALTER TABLE `categories`

  ADD `category_deleted` DATETIME AFTER `category_updated`;

ALTER TABLE `cf_campaigns`

  ADD `campaign_deleted` DATETIME AFTER `campaign_updated`;

ALTER TABLE `cf_rewards`

  ADD `reward_deleted` DATETIME AFTER `reward_updated`;

ALTER TABLE `collections`

  ADD `collection_deleted` DATETIME AFTER `collection_updated`;

ALTER TABLE `countries`

  ADD `country_deleted` DATETIME AFTER `country_updated`;

ALTER TABLE `cron_jobs`

  ADD `cron_job_deleted` DATETIME AFTER `cron_job_updated`;

ALTER TABLE `customers`

  ADD `customer_deleted` DATETIME AFTER `customer_updated`;

ALTER TABLE `cycles`

  ADD `cycle_deleted` DATETIME AFTER `cycle_updated`;

ALTER TABLE `downloads`

  ADD `download_deleted` DATETIME AFTER `download_updated`;

DROP INDEX `url` ON `events`;

ALTER TABLE `events`

  ADD `event_deleted` DATETIME AFTER `event_updated`;

CREATE UNIQUE INDEX `url` ON `events` (`site_id`, `event_url`, `event_deleted`);

ALTER TABLE `files`

  ADD `file_deleted` DATETIME AFTER `file_updated`;

ALTER TABLE `galleries`

  ADD `gallery_deleted` DATETIME AFTER `gallery_updated`;

ALTER TABLE `images`

  ADD `image_deleted` DATETIME AFTER `image_updated`;

ALTER TABLE `inventory`

  ADD `inventory_deleted` DATETIME AFTER `inventory_updated`;

ALTER TABLE `inventory_item`

  ADD `ii_deleted` DATETIME AFTER `ii_updated`;

ALTER TABLE `jobs`

  CHANGE `job_event` `job_event` TINYINT(1) NOT NULL,

  ADD `job_deleted` DATETIME AFTER `job_updated`;

ALTER TABLE `langs`

  ADD `lang_deleted` DATETIME AFTER `lang_updated`;

ALTER TABLE `libraries`

  ADD `library_deleted` DATETIME AFTER `library_updated`;

DROP INDEX `site_id` ON `links`;

ALTER TABLE `links`

  ADD `link_deleted` DATETIME AFTER `link_updated`;

CREATE UNIQUE INDEX `site_id` ON `links` (`site_id`, `article_id`, `link_do_not_reorder`, `link_deleted`);

ALTER TABLE `lists`

  ADD `list_deleted` DATETIME AFTER `list_updated`;

ALTER TABLE `mailing`

  ADD `mailing_deleted` DATETIME AFTER `mailing_updated`;

ALTER TABLE `medias`

  ADD `media_deleted` DATETIME AFTER `media_updated`;

ALTER TABLE `option`

  ADD `option_deleted` DATETIME AFTER `option_updated`;

ALTER TABLE `orders`

  ADD `order_deleted` DATETIME AFTER `order_updated`;

ALTER TABLE `pages`

  ADD `page_deleted` DATETIME AFTER `page_updated`;

ALTER TABLE `payments`

  ADD `payment_deleted` DATETIME AFTER `payment_updated`;

ALTER TABLE `people`

  ADD `people_deleted` DATETIME AFTER `people_updated`;

ALTER TABLE `posts`

  ADD `post_deleted` DATETIME AFTER `post_updated`;

ALTER TABLE `prices`

  ADD `price_deleted` DATETIME AFTER `price_updated`;

ALTER TABLE `publishers`

  ADD `publisher_deleted` DATETIME AFTER `publisher_updated`;

ALTER TABLE `rayons`

  CHANGE `rayon_url` `rayon_url` VARCHAR(256),

  ADD `rayon_deleted` DATETIME AFTER `rayon_updated`;

ALTER TABLE `redirections`

  ADD `redirection_deleted` DATETIME AFTER `redirection_updated`;

ALTER TABLE `rights`

  ADD `right_deleted` DATETIME AFTER `right_updated`;

ALTER TABLE `roles`

  ADD `role_deleted` DATETIME AFTER `role_updated`;

ALTER TABLE `session`

  ADD `session_deleted` DATETIME AFTER `session_updated`;

ALTER TABLE `shipping`

  ADD `shipping_deleted` DATETIME AFTER `shipping_updated`;

ALTER TABLE `signings`

  ADD `signing_deleted` DATETIME AFTER `signing_updated`;

ALTER TABLE `sites`

  ADD `site_deleted` DATETIME AFTER `site_updated`;

ALTER TABLE `stock`

  ADD `stock_deleted` DATETIME AFTER `stock_updated`;

ALTER TABLE `subscriptions`

  ADD `subscription_deleted` DATETIME AFTER `subscription_updated`;

ALTER TABLE `suppliers`

  ADD `supplier_deleted` DATETIME AFTER `supplier_updated`;

ALTER TABLE `tags`

  ADD `tag_deleted` DATETIME AFTER `tag_updated`;

ALTER TABLE `ticket`

  ADD `ticket_deleted` DATETIME AFTER `ticket_closed`;

ALTER TABLE `ticket_comment`

  ADD `ticket_comment_deleted` DATETIME AFTER `ticket_comment_update`;

ALTER TABLE `users`

  ADD `user_deleted` DATETIME AFTER `user_updated`,

  ADD `user_deleted_why` VARCHAR(1024) AFTER `user_deleted`;

DROP INDEX `user_id` ON `wishes`;

ALTER TABLE `wishes`

  ADD `wish_deleted` DATETIME AFTER `wish_bought`;

CREATE UNIQUE INDEX `user_id` ON `wishes` (`user_id`, `article_id`, `wish_deleted`);

ALTER TABLE `wishlist`

  ADD `wishlist_deleted` DATETIME AFTER `wishlist_updated`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
EOT;

        return array(
            'default' => $connection_default,
        );
    }

}