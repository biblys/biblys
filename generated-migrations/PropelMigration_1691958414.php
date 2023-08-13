<?php
use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1691958414.
 * Generated on 2023-08-13 20:26:54 by clement */
class PropelMigration_1691958414{
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

  CHANGE `user_id` `axys_user_id` int(10) unsigned;

DROP INDEX `carts_fi_b4bf15` ON `carts`;

ALTER TABLE `carts`

  CHANGE `user_id` `axys_user_id` int(10) unsigned;

CREATE INDEX `carts_fi_b89a7c` ON `carts` (`axys_user_id`);

ALTER TABLE `coupons`

  CHANGE `user_id` `axys_user_id` int(10) unsigned;

DROP INDEX `site_id` ON `customers`;

ALTER TABLE `customers`

  CHANGE `user_id` `axys_user_id` int(10) unsigned;

CREATE UNIQUE INDEX `site_id` ON `customers` (`site_id`, `axys_user_id`);

ALTER TABLE `downloads`

  CHANGE `user_id` `axys_user_id` int(10) unsigned;

ALTER TABLE `files`

  CHANGE `user_id` `axys_user_id` int(10) unsigned;

DROP INDEX `sponsorship` ON `links`;

DROP INDEX `myEvents` ON `links`;

ALTER TABLE `links`

  CHANGE `user_id` `axys_user_id` int(10) unsigned,

  CHANGE `link_sponsor_user_id` `link_sponsor_axys_user_id` int(10) unsigned;

CREATE UNIQUE INDEX `sponsorship` ON `links` (`site_id`, `axys_user_id`, `link_sponsor_axys_user_id`);

CREATE UNIQUE INDEX `myEvents` ON `links` (`site_id`, `axys_user_id`, `event_id`);

ALTER TABLE `lists`

  CHANGE `user_id` `axys_user_id` int(10) unsigned;

DROP INDEX `options_fi_b4bf15` ON `options`;

ALTER TABLE `options`

  CHANGE `user_id` `axys_user_id` INTEGER;

CREATE INDEX `options_fi_b89a7c` ON `options` (`axys_user_id`);

ALTER TABLE `orders`

  CHANGE `user_id` `axys_user_id` int(11) unsigned;

DROP INDEX `user_id` ON `permissions`;

ALTER TABLE `permissions`

  CHANGE `user_id` `axys_user_id` INTEGER;

CREATE UNIQUE INDEX `axys_user_id` ON `permissions` (`axys_user_id`, `site_id`);

ALTER TABLE `posts`

  CHANGE `user_id` `axys_user_id` INTEGER;

DROP INDEX `rights_fi_b4bf15` ON `rights`;

ALTER TABLE `rights`

  CHANGE `user_id` `axys_user_id` int(10) unsigned;

CREATE INDEX `rights_fi_b89a7c` ON `rights` (`axys_user_id`);

ALTER TABLE `roles`

  CHANGE `user_id` `axys_user_id` int(10) unsigned;

DROP INDEX `session_fi_b4bf15` ON `session`;

ALTER TABLE `session`

  CHANGE `user_id` `axys_user_id` int(11) unsigned;

CREATE INDEX `session_fi_b89a7c` ON `session` (`axys_user_id`);

DROP INDEX `stock_fi_b4bf15` ON `stock`;

ALTER TABLE `stock`

  CHANGE `user_id` `axys_user_id` INTEGER(10);

CREATE INDEX `stock_fi_b89a7c` ON `stock` (`axys_user_id`);

ALTER TABLE `subscriptions`

  CHANGE `user_id` `axys_user_id` int(10) unsigned;

ALTER TABLE `votes`

  CHANGE `user_id` `axys_user_id` int(10) unsigned;

DROP INDEX `user_id` ON `wishes`;

ALTER TABLE `wishes`

  CHANGE `user_id` `axys_user_id` int(10) unsigned;

CREATE UNIQUE INDEX `axys_user_id` ON `wishes` (`axys_user_id`, `article_id`);

ALTER TABLE `wishlist`

  CHANGE `user_id` `axys_user_id` int(11) unsigned;

CREATE INDEX `wishlist_fi_b89a7c` ON `wishlist` (`axys_user_id`);

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

  CHANGE `axys_user_id` `user_id` int(10) unsigned;

DROP INDEX `carts_fi_b89a7c` ON `carts`;

ALTER TABLE `carts`

  CHANGE `axys_user_id` `user_id` int(10) unsigned;

CREATE INDEX `carts_fi_b4bf15` ON `carts` (`user_id`);

ALTER TABLE `coupons`

  CHANGE `axys_user_id` `user_id` int(10) unsigned;

DROP INDEX `site_id` ON `customers`;

ALTER TABLE `customers`

  CHANGE `axys_user_id` `user_id` int(10) unsigned;

CREATE UNIQUE INDEX `site_id` ON `customers` (`site_id`, `user_id`);

ALTER TABLE `downloads`

  CHANGE `axys_user_id` `user_id` int(10) unsigned;

ALTER TABLE `files`

  CHANGE `axys_user_id` `user_id` int(10) unsigned;

DROP INDEX `sponsorship` ON `links`;

DROP INDEX `myEvents` ON `links`;

ALTER TABLE `links`

  CHANGE `axys_user_id` `user_id` int(10) unsigned,

  CHANGE `link_sponsor_axys_user_id` `link_sponsor_user_id` int(10) unsigned;

CREATE UNIQUE INDEX `sponsorship` ON `links` (`site_id`, `user_id`, `link_sponsor_user_id`);

CREATE UNIQUE INDEX `myEvents` ON `links` (`site_id`, `user_id`, `event_id`);

ALTER TABLE `lists`

  CHANGE `axys_user_id` `user_id` int(10) unsigned;

DROP INDEX `options_fi_b89a7c` ON `options`;

ALTER TABLE `options`

  CHANGE `axys_user_id` `user_id` INTEGER;

CREATE INDEX `options_fi_b4bf15` ON `options` (`user_id`);

ALTER TABLE `orders`

  CHANGE `axys_user_id` `user_id` int(11) unsigned;

DROP INDEX `axys_user_id` ON `permissions`;

ALTER TABLE `permissions`

  CHANGE `axys_user_id` `user_id` INTEGER;

CREATE UNIQUE INDEX `user_id` ON `permissions` (`user_id`, `site_id`);

ALTER TABLE `posts`

  CHANGE `axys_user_id` `user_id` INTEGER;

DROP INDEX `rights_fi_b89a7c` ON `rights`;

ALTER TABLE `rights`

  CHANGE `axys_user_id` `user_id` int(10) unsigned;

CREATE INDEX `rights_fi_b4bf15` ON `rights` (`user_id`);

ALTER TABLE `roles`

  CHANGE `axys_user_id` `user_id` int(10) unsigned;

DROP INDEX `session_fi_b89a7c` ON `session`;

ALTER TABLE `session`

  CHANGE `axys_user_id` `user_id` int(11) unsigned;

CREATE INDEX `session_fi_b4bf15` ON `session` (`user_id`);

DROP INDEX `stock_fi_b89a7c` ON `stock`;

ALTER TABLE `stock`

  CHANGE `axys_user_id` `user_id` INTEGER(10);

CREATE INDEX `stock_fi_b4bf15` ON `stock` (`user_id`);

ALTER TABLE `subscriptions`

  CHANGE `axys_user_id` `user_id` int(10) unsigned;

ALTER TABLE `votes`

  CHANGE `axys_user_id` `user_id` int(10) unsigned;

DROP INDEX `axys_user_id` ON `wishes`;

ALTER TABLE `wishes`

  CHANGE `axys_user_id` `user_id` int(10) unsigned;

CREATE UNIQUE INDEX `user_id` ON `wishes` (`user_id`, `article_id`);

DROP INDEX `wishlist_fi_b89a7c` ON `wishlist`;

ALTER TABLE `wishlist`

  CHANGE `axys_user_id` `user_id` int(11) unsigned;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
EOT;

        return [
            'default' => $connection_default,
        ];
    }

}
