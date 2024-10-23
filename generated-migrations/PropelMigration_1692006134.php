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
 * up to version 1692006134.
 * Generated on 2023-08-14 09:42:14 by clement */
class PropelMigration_1692006134{
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

ALTER TABLE `axys_users`

  DROP `facebook_uid`,

  DROP `user_wishlist_ship`,

  DROP `user_top`,

  DROP `user_biblio`,

  DROP `adresse_ip`,

  DROP `recaptcha_score`,

  DROP `publisher_id`,

  DROP `bookshop_id`,

  DROP `library_id`,

  DROP `user_civilite`,

  DROP `user_adresse1`,

  DROP `user_adresse2`,

  DROP `user_codepostal`,

  DROP `user_ville`,

  DROP `user_pays`,

  DROP `user_telephone`,

  DROP `user_pref_articles_show`,

  DROP `user_fb_id`,

  DROP `user_fb_token`,

  DROP `country_id`,

  DROP `user_password_reset_token`,

  DROP `user_password_reset_token_created`;


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

ALTER TABLE `axys_users`

  ADD `facebook_uid` INTEGER AFTER `email_key`,

  ADD `user_wishlist_ship` TINYINT(1) DEFAULT 0 AFTER `user_slug`,

  ADD `user_top` TINYINT(1) AFTER `user_wishlist_ship`,

  ADD `user_biblio` TINYINT(1) AFTER `user_top`,

  ADD `adresse_ip` VARCHAR(255) AFTER `user_biblio`,

  ADD `recaptcha_score` FLOAT AFTER `adresse_ip`,

  ADD `publisher_id` int(10) unsigned AFTER `DateConnexion`,

  ADD `bookshop_id` int(10) unsigned AFTER `publisher_id`,

  ADD `library_id` int(10) unsigned AFTER `bookshop_id`,

  ADD `user_civilite` TEXT AFTER `library_id`,

  ADD `user_adresse1` TEXT AFTER `user_prenom`,

  ADD `user_adresse2` TEXT AFTER `user_adresse1`,

  ADD `user_codepostal` TEXT AFTER `user_adresse2`,

  ADD `user_ville` TEXT AFTER `user_codepostal`,

  ADD `user_pays` TEXT AFTER `user_ville`,

  ADD `user_telephone` TEXT AFTER `user_pays`,

  ADD `user_pref_articles_show` VARCHAR(8) AFTER `user_telephone`,

  ADD `user_fb_id` BIGINT AFTER `user_pref_articles_show`,

  ADD `user_fb_token` VARCHAR(256) AFTER `user_fb_id`,

  ADD `country_id` int(10) unsigned AFTER `user_fb_token`,

  ADD `user_password_reset_token` VARCHAR(32) AFTER `country_id`,

  ADD `user_password_reset_token_created` TIMESTAMP NULL AFTER `user_password_reset_token`;


# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
EOT;

        return [
            'default' => $connection_default,
        ];
    }

}
