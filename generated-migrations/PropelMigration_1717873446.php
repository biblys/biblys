<?php
use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1717873446.
 * Generated on 2024-06-08 19:04:06 by clement */
class PropelMigration_1717873446{
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

CREATE TABLE `images`
(
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `type` VARCHAR(16),
    `filePath` VARCHAR(256),
    `fileName` VARCHAR(256),
    `version` INTEGER,
    `mediaType` VARCHAR(256),
    `fileSize` BIGINT,
    `height` INTEGER,
    `width` INTEGER,
    `article_id` INTEGER,
    `stock_item_id` INTEGER,
    `contributor_id` INTEGER,
    `post_id` INTEGER,
    `event_id` INTEGER,
    `publisher_id` INTEGER,
    `uploaded_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `images_fi_3fff59` (`article_id`),
    INDEX `images_fi_10a59a` (`stock_item_id`),
    INDEX `images_fi_1f4d5c` (`contributor_id`),
    INDEX `images_fi_4bbf65` (`post_id`),
    INDEX `images_fi_1f82a6` (`event_id`),
    INDEX `images_fi_861e9e` (`publisher_id`)
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

DROP TABLE IF EXISTS `images`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
EOT;

        return [
            'default' => $connection_default,
        ];
    }

}
