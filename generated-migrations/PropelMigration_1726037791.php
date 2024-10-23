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

/**
* This migration file aims to populate referential data such as countries
**/
<?php
use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1726037791.
 * Generated on 2024-09-11 06:56:31 by GUL */
class PropelMigration_1726037791{
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
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('1', 'AF', 'Afghanistan', 'Afghanistan', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('2', 'ZA', 'Afrique du Sud', 'South Africa', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('3', 'AL', 'Albanie', 'Albania', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('4', 'DZ', 'Algérie', 'Algeria', 'B', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('5', 'DE', 'Allemagne', 'Germany', 'A', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('6', 'AD', 'Andorre', 'Andorra', 'B', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('7', 'AO', 'Angola', 'Angola', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('8', 'AI', 'Anguilla', 'Anguilla', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('9', 'AQ', 'Antarctique', 'Antarctica', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('10', 'AG', 'Antigua-et-Barbuda', 'Antigua & Barbuda', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('11', 'AN', 'Antilles néerlandaises', 'Netherlands Antilles', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('12', 'SA', 'Arabie saoudite', 'Saudi Arabia', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('13', 'AR', 'Argentine', 'Argentina', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('14', 'AM', 'Arménie', 'Armenia', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('15', 'AW', 'Aruba', 'Aruba', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('16', 'AU', 'Australie', 'Australia', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('17', 'AT', 'Autriche', 'Austria', 'A', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('18', 'AZ', 'Azerbaïdjan', 'Azerbaijan', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('19', 'BJ', 'Bénin', 'Benin', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('20', 'BS', 'Bahamas', 'Bahamas, The', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('21', 'BH', 'Bahreïn', 'Bahrain', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('22', 'BD', 'Bangladesh', 'Bangladesh', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('23', 'BB', 'Barbade', 'Barbados', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('24', 'PW', 'Belau', 'Palau', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('25', 'BE', 'Belgique', 'Belgium', 'A', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('26', 'BZ', 'Belize', 'Belize', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('27', 'BM', 'Bermudes', 'Bermuda', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('28', 'BT', 'Bhoutan', 'Bhutan', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('29', 'BY', 'Biélorussie', 'Belarus', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('30', 'MM', 'Birmanie', 'Myanmar (ex-Burma)', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('31', 'BO', 'Bolivie', 'Bolivia', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('32', 'BA', 'Bosnie-Herzégovine', 'Bosnia and Herzegovina', 'B', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('33', 'BW', 'Botswana', 'Botswana', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('34', 'BR', 'Brésil', 'Brazil', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('35', 'BN', 'Brunei', 'Brunei Darussalam', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('36', 'BG', 'Bulgarie', 'Bulgaria', 'B', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('37', 'BF', 'Burkina Faso', 'Burkina Faso', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('38', 'BI', 'Burundi', 'Burundi', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('39', 'CI', 'Côte d\'Ivoire', 'Ivory Coast (see Cote d\'Ivoire)', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('40', 'KH', 'Cambodge', 'Cambodia', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('41', 'CM', 'Cameroun', 'Cameroon', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('42', 'CA', 'Canada', 'Canada', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('43', 'CV', 'Cap-Vert', 'Cape Verde', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('44', 'CL', 'Chili', 'Chile', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('45', 'CN', 'Chine', 'China', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('46', 'CY', 'Chypre', 'Cyprus', 'A', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('47', 'CO', 'Colombie', 'Colombia', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('48', 'KM', 'Comores', 'Comoros', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('49', 'CG', 'Congo', 'Congo', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('50', 'KP', 'Corée du Nord', 'Korea, Demo. People\'s Rep. of', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('51', 'KR', 'Corée du Sud', 'Korea, (South) Republic of', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('52', 'CR', 'Costa Rica', 'Costa Rica', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('53', 'HR', 'Croatie', 'Croatia', 'B', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('54', 'CU', 'Cuba', 'Cuba', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('55', 'DK', 'Danemark', 'Denmark', 'A', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('56', 'DJ', 'Djibouti', 'Djibouti', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('57', 'DM', 'Dominique', 'Dominica', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('58', 'EG', 'Égypte', 'Egypt', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('59', 'AE', 'Émirats arabes unis', 'United Arab Emirates', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('60', 'EC', 'Équateur', 'Ecuador', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('61', 'ER', 'Érythrée', 'Eritrea', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('62', 'ES', 'Espagne', 'Spain', 'A', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('63', 'EE', 'Estonie', 'Estonia', 'A', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('64', 'US', 'États-Unis', 'United States', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('65', 'ET', 'Éthiopie', 'Ethiopia', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('66', 'FI', 'Finlande', 'Finland', 'A', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('67', 'FR', 'France', 'France', 'F', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('68', 'GE', 'Géorgie', 'Georgia', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('69', 'GA', 'Gabon', 'Gabon', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('70', 'GM', 'Gambie', 'Gambia, the', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('71', 'GH', 'Ghana', 'Ghana', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('72', 'GI', 'Gibraltar', 'Gibraltar', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('73', 'GR', 'Grèce', 'Greece', 'A', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('74', 'GD', 'Grenade', 'Grenada', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('75', 'GL', 'Groenland', 'Greenland', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('76', 'GP', 'Guadeloupe', 'Guinea, Equatorial', 'OM1', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('77', 'GU', 'Guam', 'Guam', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('78', 'GT', 'Guatemala', 'Guatemala', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('79', 'GN', 'Guinée', 'Guinea', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('80', 'GQ', 'Guinée équatoriale', 'Equatorial Guinea', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('81', 'GW', 'Guinée-Bissao', 'Guinea-Bissau', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('82', 'GY', 'Guyana', 'Guyana', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('83', 'GF', 'Guyane française', 'Guiana, French', 'OM1', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('84', 'HT', 'Haïti', 'Haiti', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('85', 'HN', 'Honduras', 'Honduras', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('86', 'HK', 'Hong Kong', 'Hong Kong, (China)', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('87', 'HU', 'Hongrie', 'Hungary', 'A', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('88', 'BV', 'Ile Bouvet', 'Bouvet Island', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('89', 'CX', 'Ile Christmas', 'Christmas Island', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('90', 'NF', 'Ile Norfolk', 'Norfolk Island', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('91', 'KY', 'Iles Cayman', 'Cayman Islands', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('92', 'CK', 'Iles Cook', 'Cook Islands', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('93', 'FO', 'Iles Féroé', 'Faroe Islands', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('94', 'FK', 'Iles Falkland', 'Falkland Islands (Malvinas)', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('95', 'FJ', 'Iles Fidji', 'Fiji', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('96', 'GS', 'Iles Géorgie du Sud et Sandwich du Sud', 'S. Georgia and S. Sandwich Is.', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('97', 'HM', 'Iles Heard et McDonald', 'Heard and McDonald Islands', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('98', 'MH', 'Iles Marshall', 'Marshall Islands', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('99', 'PN', 'Iles Pitcairn', 'Pitcairn Island', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('100', 'SB', 'Iles Salomon', 'Solomon Islands', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('101', 'SJ', 'Iles Svalbard et Jan Mayen', 'Svalbard and Jan Mayen Islands', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('102', 'TC', 'Iles Turks-et-Caicos', 'Turks and Caicos Islands', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('103', 'VI', 'Iles Vierges américaines', 'Virgin Islands, U.S.', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('104', 'VG', 'Iles Vierges britanniques', 'Virgin Islands, British', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('105', 'CC', 'Iles des Cocos (Keeling)', 'Cocos (Keeling) Islands', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('106', 'UM', 'Iles mineures éloignées des États-Unis', 'US Minor Outlying Islands', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('107', 'IN', 'Inde', 'India', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('108', 'ID', 'Indonésie', 'Indonesia', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('109', 'IR', 'Iran', 'Iran, Islamic Republic of', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('110', 'IQ', 'Iraq', 'Iraq', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('111', 'IE', 'Irlande', 'Ireland', 'A', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('112', 'IS', 'Islande', 'Iceland', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('113', 'IL', 'Israël', 'Israel', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('114', 'IT', 'Italie', 'Italy', 'A', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('115', 'JM', 'Jamaïque', 'Jamaica', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('116', 'JP', 'Japon', 'Japan', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('117', 'JO', 'Jordanie', 'Jordan', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('118', 'KZ', 'Kazakhstan', 'Kazakhstan', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('119', 'KE', 'Kenya', 'Kenya', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('120', 'KG', 'Kirghizistan', 'Kyrgyzstan', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('121', 'KI', 'Kiribati', 'Kiribati', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('122', 'KW', 'Koweït', 'Kuwait', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('123', 'LA', 'Laos', 'Lao People\'s Democratic Republic', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('124', 'LS', 'Lesotho', 'Lesotho', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('125', 'LV', 'Lettonie', 'Latvia', 'A', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('126', 'LB', 'Liban', 'Lebanon', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('127', 'LR', 'Liberia', 'Liberia', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('128', 'LY', 'Libye', 'Libyan Arab Jamahiriya', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('129', 'LI', 'Liechtenstein', 'Liechtenstein', 'B', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('130', 'LT', 'Lituanie', 'Lithuania', 'A', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('131', 'LU', 'Luxembourg', 'Luxembourg', 'A', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('132', 'MO', 'Macao', 'Macao, (China)', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('133', 'MG', 'Madagascar', 'Madagascar', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('134', 'MY', 'Malaisie', 'Malaysia', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('135', 'MW', 'Malawi', 'Malawi', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('136', 'MV', 'Maldives', 'Maldives', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('137', 'ML', 'Mali', 'Mali', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('138', 'MT', 'Malte', 'Malta', 'A', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('139', 'MP', 'Mariannes du Nord', 'Northern Mariana Islands', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('140', 'MA', 'Maroc', 'Morocco', 'B', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('141', 'MQ', 'Martinique', 'Martinique', 'OM1', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('142', 'MU', 'Maurice', 'Mauritius', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('143', 'MR', 'Mauritanie', 'Mauritania', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('144', 'YT', 'Mayotte', 'Mayotte', 'OM1', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('145', 'MX', 'Mexique', 'Mexico', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('146', 'FM', 'Micronésie', 'Micronesia, Federated States of', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('147', 'MD', 'Moldavie', 'Moldova, Republic of', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('148', 'MC', 'Monaco', 'Monaco', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('149', 'MN', 'Mongolie', 'Mongolia', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('150', 'MS', 'Montserrat', 'Montserrat', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('151', 'MZ', 'Mozambique', 'Mozambique', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('152', 'NP', 'Népal', 'Nepal', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('153', 'NA', 'Namibie', 'Namibia', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('154', 'NR', 'Nauru', 'Nauru', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('155', 'NI', 'Nicaragua', 'Nicaragua', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('156', 'NE', 'Niger', 'Niger', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('157', 'NG', 'Nigeria', 'Nigeria', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('158', 'NU', 'Nioué', 'Niue', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('159', 'NO', 'Norvège', 'Norway', 'B', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('160', 'NC', 'Nouvelle-Calédonie', 'New Caledonia', 'OM2', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('161', 'NZ', 'Nouvelle-Zélande', 'New Zealand', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('162', 'OM', 'Oman', 'Oman', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('163', 'UG', 'Ouganda', 'Uganda', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('164', 'UZ', 'Ouzbékistan', 'Uzbekistan', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('165', 'PE', 'Pérou', 'Peru', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('166', 'PK', 'Pakistan', 'Pakistan', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('167', 'PA', 'Panama', 'Panama', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('168', 'PG', 'Papouasie-Nouvelle-Guinée', 'Papua New Guinea', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('169', 'PY', 'Paraguay', 'Paraguay', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('170', 'NL', 'Pays-Bas', 'Netherlands', 'A', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('171', 'PH', 'Philippines', 'Philippines', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('172', 'PL', 'Pologne', 'Poland', 'A', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('173', 'PF', 'Polynésie française', 'French Polynesia', 'OM2', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('174', 'PR', 'Porto Rico', 'Puerto Rico', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('175', 'PT', 'Portugal', 'Portugal', 'A', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('176', 'QA', 'Qatar', 'Qatar', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('177', 'CF', 'République centrafricaine', 'Central African Republic', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('178', 'CD', 'République démocratique du Congo', 'Congo, Democratic Rep. of the', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('179', 'DO', 'République dominicaine', 'Dominican Republic', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('180', 'CZ', 'République tchèque', 'Czech Republic', 'A', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('181', 'RE', 'Réunion', 'Reunion', 'OM1', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('182', 'RO', 'Roumanie', 'Romania', 'B', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('183', 'GB', 'Royaume-Uni', 'United Kingdom', 'A', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('184', 'RU', 'Russie', 'Russia (Russian Federation)', 'A', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('185', 'RW', 'Rwanda', 'Rwanda', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('186', 'SN', 'Sénégal', 'Senegal', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('187', 'EH', 'Sahara occidental', 'Western Sahara', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('188', 'KN', 'Saint-Christophe-et-Niévès', 'Saint Kitts and Nevis', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('189', 'SM', 'Saint-Marin', 'San Marino', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('190', 'PM', 'Saint-Pierre-et-Miquelon', 'Saint Pierre and Miquelon', 'OM1', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('191', 'VA', 'Saint-Siège', 'Vatican City State (Holy See)', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('192', 'VC', 'Saint-Vincent-et-les-Grenadines', 'Saint Vincent and the Grenadines', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('193', 'SH', 'Sainte-Hélène', 'Saint Helena', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('194', 'LC', 'Sainte-Lucie', 'Saint Lucia', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('195', 'SV', 'Salvador', 'El Salvador', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('196', 'WS', 'Samoa', 'Samoa', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('197', 'AS', 'Samoa américaines', 'American Samoa', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('198', 'ST', 'Sao Tomé-et-Principe', 'Sao Tome and Principe', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('199', 'SC', 'Seychelles', 'Seychelles', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('200', 'SL', 'Sierra Leone', 'Sierra Leone', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('201', 'SG', 'Singapour', 'Singapore', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('202', 'SI', 'Slovénie', 'Slovenia', 'A', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('203', 'SK', 'Slovaquie', 'Slovakia', 'A', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('204', 'SO', 'Somalie', 'Somalia', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('205', 'SD', 'Soudan', 'Sudan', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('206', 'LK', 'Sri Lanka', 'Sri Lanka (ex-Ceilan)', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('207', 'SE', 'Suède', 'Sweden', 'A', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('208', 'CH', 'Suisse', 'Switzerland', 'A', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('209', 'SR', 'Suriname', 'Suriname', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('210', 'SZ', 'Swaziland', 'Swaziland', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('211', 'SY', 'Syrie', 'Syrian Arab Republic', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('212', 'TW', 'Taïwan', 'Taiwan', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('213', 'TJ', 'Tadjikistan', 'Tajikistan', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('214', 'TZ', 'Tanzanie', 'Tanzania, United Republic of', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('215', 'TD', 'Tchad', 'Chad', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('216', 'TF', 'Terres australes françaises', 'French Southern Territories - TF', 'OM2', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('217', 'IO', 'Territoire britannique de l\'Océan Indien', 'British Indian Ocean Territory', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('218', 'TH', 'Thaïlande', 'Thailand', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('219', 'TL', 'Timor Oriental', 'Timor-Leste (East Timor)', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('220', 'TG', 'Togo', 'Togo', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('221', 'TK', 'Tokélaou', 'Tokelau', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('222', 'TO', 'Tonga', 'Tonga', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('223', 'TT', 'Trinité-et-Tobago', 'Trinidad & Tobago', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('224', 'TN', 'Tunisie', 'Tunisia', 'B', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('225', 'TM', 'Turkménistan', 'Turkmenistan', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('226', 'TR', 'Turquie', 'Turkey', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('227', 'TV', 'Tuvalu', 'Tuvalu', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('228', 'UA', 'Ukraine', 'Ukraine', 'B', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('229', 'UY', 'Uruguay', 'Uruguay', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('230', 'VU', 'Vanuatu', 'Vanuatu', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('231', 'VE', 'Venezuela', 'Venezuela', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('232', 'VN', 'Viêt Nam', 'Viet Nam', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('233', 'WF', 'Wallis-et-Futuna', 'Wallis and Futuna', 'OM2', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('234', 'YE', 'Yémen', 'Yemen', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('235', 'YU', 'Yougoslavie', 'Saint Pierre and Miquelon', 'B', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('236', 'ZM', 'Zambie', 'Zambia', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('237', 'ZW', 'Zimbabwe', 'Zimbabwe', 'C', NULL, NULL);
INSERT IGNORE INTO countries (country_id, country_code, country_name, country_name_en, shipping_zone, country_created, country_updated) VALUES ('238', 'MK', 'ex-République yougoslave de Macédoine', 'Macedonia, TFYR', 'C', NULL, NULL);
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
EOT;

        return [
            'default' => $connection_default,
        ];
    }

}
