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

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1622577464.
 * Generated on 2021-06-01 21:57:44 by root 
 */
class PropelMigration_1622577464 
{
    public $comment = '';

    public function preUp(MigrationManager $manager)
    {

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

DELETE FROM `alerts` WHERE `alert_deleted` IS NOT NULL;

DELETE FROM `articles` WHERE `article_deleted` IS NOT NULL;

DELETE FROM `awards` WHERE `award_deleted` IS NOT NULL;

DELETE FROM `bookshops` WHERE `bookshop_deleted` IS NOT NULL;

DELETE FROM `carts` WHERE `cart_deleted` IS NOT NULL;

DELETE FROM `categories` WHERE `category_deleted` IS NOT NULL;

DELETE FROM `cf_campaigns` WHERE `campaign_deleted` IS NOT NULL;

DELETE FROM `cf_rewards` WHERE `reward_deleted` IS NOT NULL;

DELETE FROM `collections` WHERE `collection_deleted` IS NOT NULL;

DELETE FROM `countries` WHERE `country_deleted` IS NOT NULL;

DELETE FROM `cron_jobs` WHERE `cron_job_deleted` IS NOT NULL;

DELETE FROM `customers` WHERE `customer_deleted` IS NOT NULL;

DELETE FROM `cycles` WHERE `cycle_deleted` IS NOT NULL;

DELETE FROM `downloads` WHERE `download_deleted` IS NOT NULL;

DELETE FROM `events` WHERE `event_deleted` IS NOT NULL;

DELETE FROM `files` WHERE `file_deleted` IS NOT NULL;

DELETE FROM `images` WHERE `image_deleted` IS NOT NULL;

DELETE FROM `inventory` WHERE `inventory_deleted` IS NOT NULL;

DELETE FROM `inventory_item` WHERE `ii_deleted` IS NOT NULL;

DELETE FROM `jobs` WHERE `job_deleted` IS NOT NULL;

DELETE FROM `langs` WHERE `lang_deleted` IS NOT NULL;

DELETE FROM `libraries` WHERE `library_deleted` IS NOT NULL;

DELETE FROM `links` WHERE `link_deleted` IS NOT NULL;

DELETE FROM `lists` WHERE `list_deleted` IS NOT NULL;

DELETE FROM `mailing` WHERE `mailing_deleted` IS NOT NULL;

DELETE FROM `medias` WHERE `media_deleted` IS NOT NULL;

DELETE FROM `option` WHERE `option_deleted` IS NOT NULL;

DELETE FROM `orders` WHERE `order_deleted` IS NOT NULL;

DELETE FROM `pages` WHERE `page_deleted` IS NOT NULL;

DELETE FROM `payments` WHERE `payment_deleted` IS NOT NULL;

DELETE FROM `people` WHERE `people_deleted` IS NOT NULL;

DELETE FROM `posts` WHERE `post_deleted` IS NOT NULL;

DELETE FROM `prices` WHERE `price_deleted` IS NOT NULL;

DELETE FROM `publishers` WHERE `publisher_deleted` IS NOT NULL;

DELETE FROM `rayons` WHERE `rayon_deleted` IS NOT NULL;

DELETE FROM `redirections` WHERE `redirection_deleted` IS NOT NULL;

DELETE FROM `rights` WHERE `right_deleted` IS NOT NULL;

DELETE FROM `roles` WHERE `role_deleted` IS NOT NULL;

DELETE FROM `session` WHERE `session_deleted` IS NOT NULL;

DELETE FROM `shipping` WHERE `shipping_deleted` IS NOT NULL;

DELETE FROM `signings` WHERE `signing_deleted` IS NOT NULL;

DELETE FROM `sites` WHERE `site_deleted` IS NOT NULL;

DELETE FROM `stock` WHERE `stock_deleted` IS NOT NULL;

DELETE FROM `subscriptions` WHERE `subscription_deleted` IS NOT NULL;

DELETE FROM `suppliers` WHERE `supplier_deleted` IS NOT NULL;

DELETE FROM `tags` WHERE `tag_deleted` IS NOT NULL;

DELETE FROM `ticket` WHERE `ticket_deleted` IS NOT NULL;

DELETE FROM `ticket_comment` WHERE `ticket_comment_deleted` IS NOT NULL;

DELETE FROM `users` WHERE `user_deleted` IS NOT NULL;

DELETE FROM `wishes` WHERE `wish_deleted` IS NOT NULL;

DELETE FROM `wishlist` WHERE `wishlist_deleted` IS NOT NULL;

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
        return array();
    }

}