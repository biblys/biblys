<?php
use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1682170299.
 * Generated on 2023-04-22 13:31:39 by clement */
class PropelMigration_1682170299{
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

CREATE INDEX `articles_fi_0bc695` ON `articles` (`collection_id`);

CREATE INDEX `carts_fi_db3f76` ON `carts` (`site_id`);

CREATE INDEX `carts_fi_69bd79` ON `carts` (`user_id`);

CREATE INDEX `cf_campaigns_fi_db3f76` ON `cf_campaigns` (`site_id`);

CREATE INDEX `cf_rewards_fi_db3f76` ON `cf_rewards` (`site_id`);

CREATE INDEX `cf_rewards_fi_28032c` ON `cf_rewards` (`campaign_id`);

CREATE INDEX `links_fi_3fff59` ON `links` (`article_id`);

CREATE INDEX `links_fi_48f1fc` ON `links` (`tag_id`);

CREATE INDEX `payments_fi_db3f76` ON `payments` (`site_id`);

CREATE INDEX `payments_fi_e393fa` ON `payments` (`order_id`);

CREATE INDEX `rayons_fi_db3f76` ON `rayons` (`site_id`);

CREATE INDEX `session_fi_db3f76` ON `session` (`site_id`);

CREATE INDEX `stock_fi_69bd79` ON `stock` (`user_id`);

CREATE INDEX `users_fi_db3f76` ON `users` (`site_id`);

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

DROP INDEX `articles_fi_0bc695` ON `articles`;

DROP INDEX `carts_fi_db3f76` ON `carts`;

DROP INDEX `carts_fi_69bd79` ON `carts`;

DROP INDEX `cf_campaigns_fi_db3f76` ON `cf_campaigns`;

DROP INDEX `cf_rewards_fi_db3f76` ON `cf_rewards`;

DROP INDEX `cf_rewards_fi_28032c` ON `cf_rewards`;

DROP INDEX `links_fi_3fff59` ON `links`;

DROP INDEX `links_fi_48f1fc` ON `links`;

DROP INDEX `payments_fi_db3f76` ON `payments`;

DROP INDEX `payments_fi_e393fa` ON `payments`;

DROP INDEX `rayons_fi_db3f76` ON `rayons`;

DROP INDEX `session_fi_db3f76` ON `session`;

DROP INDEX `stock_fi_69bd79` ON `stock`;

DROP INDEX `users_fi_db3f76` ON `users`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
EOT;

        return [
            'default' => $connection_default,
        ];
    }

}
