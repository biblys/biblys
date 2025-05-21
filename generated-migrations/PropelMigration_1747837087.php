<?php
/*
 * Copyright (C) 2025 Clément Latzarus
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


use Model\ShippingZone;
use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1747837087.
 * Generated on 2025-05-21 14:18:07 by clement */
class PropelMigration_1747837087{
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
INSERT INTO `shipping_zones`(`name`, `description`, `created_at`) VALUES
    ('Monde', 'Toutes les destinations dans le monde.', NOW()),
    ('Colissimo France', 'France métropolitaine, Andorre, Monaco', NOW()),
    ('Colissimo International A', 'Union Européenne, Suisse, Royaume-Uni, Liechtenstein, Saint-Marin et le Vatican.', NOW()),
    ('Colissimo International B', 'Europe de l’Est (hors UE, Suisse et Russie), Norvège et Maghreb.', NOW()),
    ('Colissimo International C', 'Autres destinations (hors UE et Suisse).', NOW()),
    ('Colissimo OM1', 'Guadeloupe, Martinique, Guyane, La Réunion, Mayotte, Saint-Pierre-et-Miquelon, Saint-Martin, Saint-Barthélemy.', NOW()),
    ('Colissimo OM2', 'Polynésie française, Nouvelle-Calédonie, Wallis et Futuna, Terres australes et antarctiques françaises', NOW());

INSERT INTO shipping_zones_countries (country_id, shipping_zone_id)
SELECT country_id, 1 FROM countries;

INSERT INTO shipping_zones_countries (country_id, shipping_zone_id)
SELECT 
    country_id,
    CASE 
        WHEN shipping_zone = 'F' THEN 2
        WHEN shipping_zone = 'A' THEN 3
        WHEN shipping_zone = 'B' THEN 4
        WHEN shipping_zone = 'C' THEN 5
        WHEN shipping_zone = 'OM1' THEN 6
        WHEN shipping_zone = 'OM2' THEN 7
        ELSE NULL
    END AS shipping_zone_id
FROM countries;

UPDATE shipping SET shipping_zone_id = 1 WHERE shipping_zone = 'ALL';
UPDATE shipping SET shipping_zone_id = 2 WHERE shipping_zone = 'F';
UPDATE shipping SET shipping_zone_id = 3 WHERE shipping_zone = 'A';
UPDATE shipping SET shipping_zone_id = 4 WHERE shipping_zone = 'B';
UPDATE shipping SET shipping_zone_id = 5 WHERE shipping_zone = 'C';
UPDATE shipping SET shipping_zone_id = 6 WHERE shipping_zone = 'OM1';
UPDATE shipping SET shipping_zone_id = 7 WHERE shipping_zone = 'OM2';
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
TRUNCATE `shipping_zones`;
TRUNCATE `shipping_zones_countries`;
EOT;

        return [
            'default' => $connection_default,
        ];
    }

}
