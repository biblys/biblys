<?php
use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1692564344.
 * Generated on 2023-08-20 20:45:44 by clement */
class PropelMigration_1692564344{
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

  ADD `user_id` int(10) unsigned AFTER `axys_account_id`;

CREATE INDEX `alerts_fi_69bd79` ON `alerts` (`user_id`);

ALTER TABLE `carts`

  ADD `user_id` int(10) unsigned AFTER `axys_account_id`;

CREATE INDEX `carts_fi_69bd79` ON `carts` (`user_id`);

ALTER TABLE `coupons`

  ADD `user_id` int(10) unsigned AFTER `axys_account_id`;

CREATE INDEX `coupons_fi_69bd79` ON `coupons` (`user_id`);

ALTER TABLE `customers`

  ADD `user_id` int(10) unsigned AFTER `axys_account_id`;

CREATE INDEX `customers_fi_69bd79` ON `customers` (`user_id`);

ALTER TABLE `downloads`

  ADD `user_id` int(10) unsigned AFTER `axys_account_id`;

CREATE INDEX `downloads_fi_69bd79` ON `downloads` (`user_id`);

ALTER TABLE `files`

  ADD `user_id` int(10) unsigned AFTER `axys_account_id`;

CREATE INDEX `files_fi_69bd79` ON `files` (`user_id`);

ALTER TABLE `links`

  ADD `user_id` int(10) unsigned AFTER `axys_account_id`;

CREATE INDEX `links_fi_69bd79` ON `links` (`user_id`);

ALTER TABLE `lists`

  ADD `user_id` int(10) unsigned AFTER `axys_account_id`;

CREATE INDEX `lists_fi_69bd79` ON `lists` (`user_id`);

ALTER TABLE `options`

  ADD `user_id` int(10) unsigned AFTER `axys_account_id`;

CREATE INDEX `options_fi_69bd79` ON `options` (`user_id`);

ALTER TABLE `orders`

  ADD `user_id` int(10) unsigned AFTER `axys_account_id`;

CREATE INDEX `orders_fi_69bd79` ON `orders` (`user_id`);

ALTER TABLE `permissions`

  ADD `user_id` int(10) unsigned AFTER `axys_account_id`;

CREATE INDEX `permissions_fi_69bd79` ON `permissions` (`user_id`);

ALTER TABLE `posts`

  ADD `user_id` int(10) unsigned AFTER `axys_account_id`;

CREATE INDEX `posts_fi_69bd79` ON `posts` (`user_id`);

ALTER TABLE `rights`

  ADD `user_id` int(10) unsigned AFTER `axys_account_id`;

CREATE INDEX `rights_fi_69bd79` ON `rights` (`user_id`);

UPDATE `roles` 
    SET `role_date` = NULL 
    WHERE CAST(`role_date` AS CHAR(20)) = '0000-00-00 00:00:00';

ALTER TABLE `roles`

  ADD `user_id` int(10) unsigned AFTER `axys_account_id`;

CREATE INDEX `roles_fi_69bd79` ON `roles` (`user_id`);

ALTER TABLE `session`

  ADD `user_id` int(10) unsigned AFTER `axys_account_id`;

CREATE INDEX `session_fi_69bd79` ON `session` (`user_id`);

ALTER TABLE `stock`

  ADD `user_id` int(10) unsigned AFTER `axys_account_id`;

CREATE INDEX `stock_fi_69bd79` ON `stock` (`user_id`);

ALTER TABLE `subscriptions`

  ADD `user_id` int(10) unsigned AFTER `axys_account_id`;

CREATE INDEX `subscriptions_fi_69bd79` ON `subscriptions` (`user_id`);

ALTER TABLE `votes`

  ADD `user_id` int(10) unsigned AFTER `axys_account_id`;

CREATE INDEX `votes_fi_69bd79` ON `votes` (`user_id`);

ALTER TABLE `wishes`

  ADD `user_id` int(10) unsigned AFTER `axys_account_id`;

CREATE INDEX `wishes_fi_69bd79` ON `wishes` (`user_id`);

ALTER TABLE `wishlist`

  ADD `user_id` int(10) unsigned AFTER `axys_account_id`;

CREATE INDEX `wishlist_fi_69bd79` ON `wishlist` (`user_id`);

CREATE TABLE `users`
(
    `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
    `site_id` INTEGER(10) NOT NULL,
    `email` VARCHAR(256),
    `lastLoggedAt` TIMESTAMP NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `users_fi_db3f76` (`site_id`)
) ENGINE=InnoDB;

CREATE TABLE `authentication_methods`
(
    `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
    `site_id` INTEGER(10) NOT NULL,
    `user_id` INTEGER(10) NOT NULL,
    `identity_provider` VARCHAR(16),
    `external_id` VARCHAR(128),
    `access_token` VARCHAR(1024),
    `id_token` VARCHAR(1024),
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `authentication_methods_fi_db3f76` (`site_id`),
    INDEX `authentication_methods_fi_69bd79` (`user_id`)
) ENGINE=InnoDB;

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

DROP TABLE IF EXISTS `users`;

DROP TABLE IF EXISTS `authentication_methods`;

DROP INDEX `alerts_fi_69bd79` ON `alerts`;

ALTER TABLE `alerts`

  DROP `user_id`;

DROP INDEX `carts_fi_69bd79` ON `carts`;

ALTER TABLE `carts`

  DROP `user_id`;

DROP INDEX `coupons_fi_69bd79` ON `coupons`;

ALTER TABLE `coupons`

  DROP `user_id`;

DROP INDEX `customers_fi_69bd79` ON `customers`;

ALTER TABLE `customers`

  DROP `user_id`;

DROP INDEX `downloads_fi_69bd79` ON `downloads`;

ALTER TABLE `downloads`

  DROP `user_id`;

DROP INDEX `files_fi_69bd79` ON `files`;

ALTER TABLE `files`

  DROP `user_id`;

DROP INDEX `links_fi_69bd79` ON `links`;

ALTER TABLE `links`

  DROP `user_id`;

DROP INDEX `lists_fi_69bd79` ON `lists`;

ALTER TABLE `lists`

  DROP `user_id`;

DROP INDEX `options_fi_69bd79` ON `options`;

ALTER TABLE `options`

  DROP `user_id`;

DROP INDEX `orders_fi_69bd79` ON `orders`;

ALTER TABLE `orders`

  DROP `user_id`;

DROP INDEX `permissions_fi_69bd79` ON `permissions`;

ALTER TABLE `permissions`

  DROP `user_id`;

DROP INDEX `posts_fi_69bd79` ON `posts`;

ALTER TABLE `posts`

  DROP `user_id`;

DROP INDEX `rights_fi_69bd79` ON `rights`;

ALTER TABLE `rights`

  DROP `user_id`;

DROP INDEX `roles_fi_69bd79` ON `roles`;

ALTER TABLE `roles`

  DROP `user_id`;

DROP INDEX `session_fi_69bd79` ON `session`;

ALTER TABLE `session`

  DROP `user_id`;

DROP INDEX `stock_fi_69bd79` ON `stock`;

ALTER TABLE `stock`

  DROP `user_id`;

DROP INDEX `subscriptions_fi_69bd79` ON `subscriptions`;

ALTER TABLE `subscriptions`

  DROP `user_id`;

DROP INDEX `votes_fi_69bd79` ON `votes`;

ALTER TABLE `votes`

  DROP `user_id`;

DROP INDEX `wishes_fi_69bd79` ON `wishes`;

ALTER TABLE `wishes`

  DROP `user_id`;

DROP INDEX `wishlist_fi_69bd79` ON `wishlist`;

ALTER TABLE `wishlist`

  DROP `user_id`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
EOT;

        return [
            'default' => $connection_default,
        ];
    }

}
