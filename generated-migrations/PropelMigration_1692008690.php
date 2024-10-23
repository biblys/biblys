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
 * up to version 1692008690.
 * Generated on 2023-08-14 10:24:50 by clement */
class PropelMigration_1692008690{
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

  CHANGE `axys_user_id` `axys_account_id` int(10) unsigned;

DROP INDEX `axys_users_fi_db3f76` ON `axys_accounts`;

CREATE INDEX `axys_accounts_fi_db3f76` ON `axys_accounts` (`site_id`);

DROP INDEX `carts_fi_17bd41` ON `carts`;

ALTER TABLE `carts`

  CHANGE `axys_user_id` `axys_account_id` int(10) unsigned;

CREATE INDEX `carts_fi_c59114` ON `carts` (`axys_account_id`);

ALTER TABLE `coupons`

  CHANGE `axys_user_id` `axys_account_id` int(10) unsigned;

DROP INDEX `site_id` ON `customers`;

ALTER TABLE `customers`

  CHANGE `axys_user_id` `axys_account_id` int(10) unsigned;

CREATE UNIQUE INDEX `site_id` ON `customers` (`site_id`, `axys_account_id`);

ALTER TABLE `downloads`

  CHANGE `axys_user_id` `axys_account_id` int(10) unsigned;

ALTER TABLE `files`

  CHANGE `axys_user_id` `axys_account_id` int(10) unsigned;

DROP INDEX `sponsorship` ON `links`;

DROP INDEX `myEvents` ON `links`;

ALTER TABLE `links`

  CHANGE `axys_user_id` `axys_account_id` int(10) unsigned,

  CHANGE `link_sponsor_axys_user_id` `link_sponsor_axys_account_id` int(10) unsigned;

CREATE UNIQUE INDEX `sponsorship` ON `links` (`site_id`, `axys_account_id`, `link_sponsor_axys_account_id`);

CREATE UNIQUE INDEX `myEvents` ON `links` (`site_id`, `axys_account_id`, `event_id`);

ALTER TABLE `lists`

  CHANGE `axys_user_id` `axys_account_id` int(10) unsigned;

DROP INDEX `options_fi_17bd41` ON `options`;

ALTER TABLE `options`

  CHANGE `axys_user_id` `axys_account_id` INTEGER;

CREATE INDEX `options_fi_c59114` ON `options` (`axys_account_id`);

ALTER TABLE `orders`

  CHANGE `axys_user_id` `axys_account_id` int(11) unsigned;

DROP INDEX `axys_user_id` ON `permissions`;

ALTER TABLE `permissions`

  CHANGE `axys_user_id` `axys_account_id` INTEGER;

CREATE UNIQUE INDEX `axys_account_id` ON `permissions` (`axys_account_id`, `site_id`);

ALTER TABLE `posts`

  CHANGE `axys_user_id` `axys_account_id` INTEGER;

DROP INDEX `rights_fi_17bd41` ON `rights`;

ALTER TABLE `rights`

  CHANGE `axys_user_id` `axys_account_id` int(10) unsigned;

CREATE INDEX `rights_fi_c59114` ON `rights` (`axys_account_id`);

ALTER TABLE `roles`

  CHANGE `axys_user_id` `axys_account_id` int(10) unsigned;

DROP INDEX `session_fi_17bd41` ON `session`;

ALTER TABLE `session`

  CHANGE `axys_user_id` `axys_account_id` int(11) unsigned;

CREATE INDEX `session_fi_c59114` ON `session` (`axys_account_id`);

DROP INDEX `stock_fi_17bd41` ON `stock`;

ALTER TABLE `stock`

  CHANGE `axys_user_id` `axys_account_id` INTEGER(10);

CREATE INDEX `stock_fi_c59114` ON `stock` (`axys_account_id`);

ALTER TABLE `subscriptions`

  CHANGE `axys_user_id` `axys_account_id` int(10) unsigned;

ALTER TABLE `votes`

  CHANGE `axys_user_id` `axys_account_id` int(10) unsigned;

DROP INDEX `axys_user_id` ON `wishes`;

ALTER TABLE `wishes`

  CHANGE `axys_user_id` `axys_account_id` int(10) unsigned;

CREATE UNIQUE INDEX `axys_account_id` ON `wishes` (`axys_account_id`, `article_id`);

DROP INDEX `wishlist_fi_17bd41` ON `wishlist`;

ALTER TABLE `wishlist`

  CHANGE `axys_user_id` `axys_account_id` int(11) unsigned;

CREATE INDEX `wishlist_fi_c59114` ON `wishlist` (`axys_account_id`);

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

  CHANGE `axys_account_id` `axys_user_id` int(10) unsigned;

DROP INDEX `publisher_id` ON `axys_accounts`;

DROP INDEX `axys_accounts_fi_db3f76` ON `axys_accounts`;


CREATE INDEX `axys_users_fi_db3f76` ON `axys_accounts` (`site_id`);

DROP INDEX `carts_fi_c59114` ON `carts`;

ALTER TABLE `carts`

  CHANGE `axys_account_id` `axys_user_id` int(10) unsigned;

CREATE INDEX `carts_fi_17bd41` ON `carts` (`axys_user_id`);

ALTER TABLE `coupons`

  CHANGE `axys_account_id` `axys_user_id` int(10) unsigned;

DROP INDEX `site_id` ON `customers`;

ALTER TABLE `customers`

  CHANGE `axys_account_id` `axys_user_id` int(10) unsigned;

CREATE UNIQUE INDEX `site_id` ON `customers` (`site_id`, `axys_user_id`);

ALTER TABLE `downloads`

  CHANGE `axys_account_id` `axys_user_id` int(10) unsigned;

ALTER TABLE `files`

  CHANGE `axys_account_id` `axys_user_id` int(10) unsigned;

DROP INDEX `sponsorship` ON `links`;

DROP INDEX `myEvents` ON `links`;

ALTER TABLE `links`

  CHANGE `axys_account_id` `axys_user_id` int(10) unsigned,

  CHANGE `link_sponsor_axys_account_id` `link_sponsor_axys_user_id` int(10) unsigned;

CREATE UNIQUE INDEX `sponsorship` ON `links` (`site_id`, `axys_user_id`, `link_sponsor_axys_user_id`);

CREATE UNIQUE INDEX `myEvents` ON `links` (`site_id`, `axys_user_id`, `event_id`);

ALTER TABLE `lists`

  CHANGE `axys_account_id` `axys_user_id` int(10) unsigned;

DROP INDEX `options_fi_c59114` ON `options`;

ALTER TABLE `options`

  CHANGE `axys_account_id` `axys_user_id` INTEGER;

CREATE INDEX `options_fi_17bd41` ON `options` (`axys_user_id`);

ALTER TABLE `orders`

  CHANGE `axys_account_id` `axys_user_id` int(11) unsigned;

DROP INDEX `axys_account_id` ON `permissions`;

ALTER TABLE `permissions`

  CHANGE `axys_account_id` `axys_user_id` INTEGER;

CREATE UNIQUE INDEX `axys_user_id` ON `permissions` (`axys_user_id`, `site_id`);

ALTER TABLE `posts`

  CHANGE `axys_account_id` `axys_user_id` INTEGER;

DROP INDEX `rights_fi_c59114` ON `rights`;

ALTER TABLE `rights`

  CHANGE `axys_account_id` `axys_user_id` int(10) unsigned;

CREATE INDEX `rights_fi_17bd41` ON `rights` (`axys_user_id`);

ALTER TABLE `roles`

  CHANGE `axys_account_id` `axys_user_id` int(10) unsigned;

DROP INDEX `session_fi_c59114` ON `session`;

ALTER TABLE `session`

  CHANGE `axys_account_id` `axys_user_id` int(11) unsigned;

CREATE INDEX `session_fi_17bd41` ON `session` (`axys_user_id`);

DROP INDEX `stock_fi_c59114` ON `stock`;

ALTER TABLE `stock`

  CHANGE `axys_account_id` `axys_user_id` INTEGER(10);

CREATE INDEX `stock_fi_17bd41` ON `stock` (`axys_user_id`);

ALTER TABLE `subscriptions`

  CHANGE `axys_account_id` `axys_user_id` int(10) unsigned;

ALTER TABLE `votes`

  CHANGE `axys_account_id` `axys_user_id` int(10) unsigned;

DROP INDEX `axys_account_id` ON `wishes`;

ALTER TABLE `wishes`

  CHANGE `axys_account_id` `axys_user_id` int(10) unsigned;

CREATE UNIQUE INDEX `axys_user_id` ON `wishes` (`axys_user_id`, `article_id`);

DROP INDEX `wishlist_fi_c59114` ON `wishlist`;

ALTER TABLE `wishlist`

  CHANGE `axys_account_id` `axys_user_id` int(11) unsigned;

CREATE INDEX `wishlist_fi_17bd41` ON `wishlist` (`axys_user_id`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
EOT;

        return [
            'default' => $connection_default,
        ];
    }

}
