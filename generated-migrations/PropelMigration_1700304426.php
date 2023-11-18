<?php
use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1700304426.
 * Generated on 2023-11-18 10:47:06 by clement */
class PropelMigration_1700304426{
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

  ADD `site_id` int(10) unsigned AFTER `alert_id`;

CREATE INDEX `alerts_fi_db3f76` ON `alerts` (`site_id`);

ALTER TABLE `sites`

  DROP `site_wishlist`;

ALTER TABLE `votes`

  ADD `site_id` int(10) unsigned AFTER `vote_id`;

CREATE INDEX `votes_fi_db3f76` ON `votes` (`site_id`);

ALTER TABLE `wishlist`

  ADD `site_id` int(10) unsigned AFTER `wishlist_id`;

CREATE INDEX `wishlist_fi_db3f76` ON `wishlist` (`site_id`);

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

DROP INDEX `alerts_fi_db3f76` ON `alerts`;

ALTER TABLE `alerts`

  DROP `site_id`;

ALTER TABLE `sites`

  ADD `site_wishlist` TINYINT(1) DEFAULT 0 AFTER `site_shipping_fee`;

DROP INDEX `votes_fi_db3f76` ON `votes`;

ALTER TABLE `votes`

  DROP `site_id`;

DROP INDEX `wishlist_fi_db3f76` ON `wishlist`;

ALTER TABLE `wishlist`

  DROP `site_id`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
EOT;

        return [
            'default' => $connection_default,
        ];
    }

}
