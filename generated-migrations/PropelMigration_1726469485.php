<?php
use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1726469485.
 * Generated on 2024-09-16 06:51:25 by GUL */
class PropelMigration_1726469485{
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
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('1', 'aa', 'aar', '', 'Afar', 'Afaraf', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('2', 'ab', 'abk', '', 'Abkhaze', 'Аҧсуа', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('3', 'ae', 'ave', '', 'Avestique', 'Avesta', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('4', 'af', 'afr', '', 'Afrikaans', 'Afrikaans', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('5', 'ak', 'aka', '', 'Akan', 'Akan', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('6', 'am', 'amh', '', 'Amharique', 'አማርኛ', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('7', 'an', 'arg', '', 'Aragonais', 'Aragonés', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('8', 'ar', 'ara', '', 'Arabe', '‫العربية', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('9', 'as', 'asm', '', 'Assamais', 'অসমীয়া', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('10', 'av', 'ava', '', 'Avar', 'авар мацӀ', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('11', 'ay', 'aym', '', 'Aymara', 'Aymar aru', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('12', 'az', 'aze', '', 'Azéri', 'Azərbaycan dili', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('13', 'ba', 'bak', '', 'Bachkir', 'башҡорт теле', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('14', 'be', 'bel', '', 'Biélorusse', 'Беларуская', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('15', 'bg', 'bul', '', 'Bulgare', 'български език', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('16', 'bh', 'bih', '', 'Bihari', 'भोजपुरी', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('17', 'bi', 'bis', '', 'Bichelamar', 'Bislama', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('18', 'bm', 'bam', '', 'Bambara', 'Bamanankan', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('19', 'bn', 'ben', '', 'Bengalî', 'বাংলা', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('20', 'bo', 'tib/bod', '', 'Tibétain', 'བོད་ཡིག', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('21', 'br', 'bre', '', 'Breton', 'Brezhoneg', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('22', 'bs', 'bos', '', 'Bosnien', 'Bosanski jezik', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('23', 'ca', 'cat', '', 'Catalan', 'Català', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('24', 'ce', 'che', '', 'Tchétchène', 'нохчийн мотт', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('25', 'ch', 'cha', '', 'Chamorro', 'Chamoru', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('26', 'co', 'cos', '', 'Corse', 'Corsu', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('27', 'cr', 'cre', '', 'Cri', 'ᓀᐦᐃᔭᐍᐏᐣ', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('28', 'cs', 'cze/ces', '', 'Tchèque', 'Česky', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('29', 'cu', 'chu', '', 'Vieux slave', 'Словѣньскъ', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('30', 'cv', 'chv', '', 'Tchouvache', 'чӑваш чӗлхи', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('31', 'cy', 'wel/cym', '', 'Gallois', 'Cymraeg', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('32', 'da', 'dan', '', 'Danois', 'Dansk', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('33', 'de', 'ger', '', 'Allemand', 'Deutsch', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('34', 'dv', 'div', '', 'Dhivehi', '‫ދިވެހި', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('35', 'dz', 'dzo', '', 'Dzongkha', 'རྫོང་ཁ', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('36', 'ee', 'ewe', '', 'Ewe', 'Ɛʋɛgbɛ', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('37', 'el', 'gre/ell', '', 'Grec moderne', 'Ελληνικά', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('38', 'en', 'eng', '', 'Anglais', 'English', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('39', 'eo', 'epo', '', 'Espéranto', 'Esperanto', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('40', 'es', 'spa', '', 'Espagnol', 'Español', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('41', 'et', 'est', '', 'Estonien', 'Eesti keel', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('42', 'eu', 'baq/eus', '', 'Basque', 'Euskara', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('43', 'fa', 'per/fas', '', 'Persan', '‫فارسی', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('44', 'ff', 'ful', '', 'Peul', 'Fulfulde', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('45', 'fi', 'fin', '', 'Finnois', 'Suomen kieli', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('46', 'fj', 'fij', '', 'Fidjien', 'Vosa Vakaviti', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('47', 'fo', 'fao', '', 'Féringien', 'Føroyskt', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('48', 'fr', 'fre', '', 'Français', 'Français', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('49', 'fy', 'fry', '', 'Frison', 'Frysk', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('50', 'ga', 'gle', '', 'Irlandais', 'Gaeilge', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('51', 'gd', 'gla', '', 'Écossais', 'Gàidhlig', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('52', 'gl', 'glg', '', 'Galicien', 'Galego', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('53', 'gn', 'grn', '', 'Guarani', 'Avañe\'ẽ', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('54', 'gu', 'guj', '', 'Gujarâtî', 'ગુજરાતી', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('55', 'gv', 'glv', '', 'Mannois', 'Ghaelg', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('56', 'ha', 'hau', '', 'Haoussa', '‫هَوُسَ', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('57', 'he', 'heb', '', 'Hébreu', '‫עברית', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('58', 'hi', 'hin', '', 'Hindî', 'हिन्दी', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('59', 'ho', 'hmo', '', 'Hiri motu', 'Hiri Motu', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('60', 'hr', 'scr/hrv', '', 'Croate', 'Hrvatski', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('61', 'ht', 'hat', '', 'Créole haïtien', 'Kreyòl ayisyen', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('62', 'hu', 'hun', '', 'Hongrois', 'magyar', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('63', 'hy', 'arm/hye', '', 'Arménien', 'Հայերեն', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('64', 'hz', 'her', '', 'Herero', 'Otjiherero', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('65', 'ia', 'ina', '', 'Interlingua', 'Interlingua', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('66', 'id', 'ind', '', 'Indonésien', 'Bahasa Indonesia', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('67', 'ie', 'ile', '', 'Occidental', 'Interlingue', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('68', 'ig', 'ibo', '', 'Igbo', 'Igbo', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('69', 'ii', 'iii', '', 'Yi', 'ꆇꉙ', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('70', 'ik', 'ipk', '', 'Inupiaq', 'Iñupiaq', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('71', 'io', 'ido', '', 'Ido', 'Ido', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('72', 'is', 'ice/isl', '', 'Islandais', 'Íslenska', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('73', 'it', 'ita', '', 'Italien', 'Italiano', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('74', 'iu', 'iku', '', 'Inuktitut', 'ᐃᓄᒃᑎᑐᑦ', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('75', 'ja', 'jpn', '', 'Japonais', '日本語 (にほんご)', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('76', 'jv', 'jav', '', 'Javanais', 'Basa Jawa', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('77', 'ka', 'geo/kat', '', 'Géorgien', 'ქართული', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('78', 'kg', 'kon', '', 'Kikongo', 'KiKongo', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('79', 'ki', 'kik', '', 'Kikuyu', 'Gĩkũyũ', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('80', 'kj', 'kua', '', 'Kuanyama', 'Kuanyama', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('81', 'kk', 'kaz', '', 'Kazakh', 'Қазақ тілі', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('82', 'kl', 'kal', '', 'Kalaallisut', 'Kalaallisut', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('83', 'km', 'khm', '', 'Khmer', 'ភាសាខ្មែរ', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('84', 'kn', 'kan', '', 'Kannara', 'ಕನ್ನಡ', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('85', 'ko', 'kor', '', 'Coréen', '한국어 (韓國語)', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('86', 'kr', 'kau', '', 'Kanouri', 'Kanuri', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('87', 'ks', 'kas', '', 'Kashmiri', 'कश्मीरी', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('88', 'ku', 'kur', '', 'Kurde', 'Kurdî', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('89', 'kv', 'kom', '', 'Komi', 'коми кыв', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('90', 'kw', 'cor', '', 'Cornique', 'Kernewek', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('91', 'ky', 'kir', '', 'Kirghiz', 'кыргыз тили', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('92', 'la', 'lat', '', 'Latin', 'Latine', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('93', 'lb', 'ltz', '', 'Luxembourgeois', 'Lëtzebuergesch', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('94', 'lg', 'lug', '', 'Ganda', 'Luganda', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('95', 'li', 'lim', '', 'Limbourgeois', 'Limburgs', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('96', 'ln', 'lin', '', 'Lingala', 'Lingála', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('97', 'lo', 'lao', '', 'Lao', 'ພາສາລາວ', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('98', 'lt', 'lit', '', 'Lituanien', 'Lietuvių kalba', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('99', 'lu', 'lub', '', 'Luba-katanga', 'kiluba', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('100', 'lv', 'lav', '', 'Letton', 'Latviešu valoda', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('101', 'mg', 'mlg', '', 'Malgache', 'Fiteny malagasy', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('102', 'mh', 'mah', '', 'Marshallais', 'Kajin M̧ajeļ', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('103', 'mi', 'mao/mri', '', 'Māori de Nouvelle-Zélande', 'Te reo Māori', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('104', 'mk', 'mac/mkd', '', 'Macédonien', 'македонски јазик', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('105', 'ml', 'mal', '', 'Malayalam', 'മലയാളം', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('106', 'mn', 'mon', '', 'Mongol', 'Монгол', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('107', 'mo', 'mol', '', 'Moldave', 'лимба молдовеняскэ', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('108', 'mr', 'mar', '', 'Marâthî', 'मराठी', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('109', 'ms', 'may/msa', '', 'Malais', 'Bahasa Melayu', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('110', 'mt', 'mlt', '', 'Maltais', 'Malti', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('111', 'my', 'bur/mya', '', 'Birman', 'ဗမာစာ', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('112', 'na', 'nau', '', 'Nauruan', 'Ekakairũ Naoero', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('113', 'nb', 'nob', '', 'Norvégien Bokmål', 'Norsk bokmål', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('114', 'nd', 'nde', '', 'Ndébélé du Nord', 'isiNdebele', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('115', 'ne', 'nep', '', 'Népalais', 'नेपाली', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('116', 'ng', 'ndo', '', 'Ndonga', 'Owambo', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('117', 'nl', 'dut/nld', '', 'Néerlandais', 'Nederlands', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('118', 'nn', 'nno', '', 'Norvégien Nynorsk', 'Norsk nynorsk', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('119', 'no', 'nor', '', 'Norvégien', 'Norsk', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('120', 'nr', 'nbl', '', 'Ndébélé du Sud', 'Ndébélé', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('121', 'nv', 'nav', '', 'Navajo', 'Diné bizaad', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('122', 'ny', 'nya', '', 'Chichewa', 'ChiCheŵa', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('123', 'oc', 'oci', '', 'Occitan', 'Occitan', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('124', 'oj', 'oji', '', 'Ojibwé', 'ᐊᓂᔑᓈᐯᒧᐎᓐ', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('125', 'om', 'orm', '', 'Oromo', 'Afaan Oromoo', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('126', 'or', 'ori', '', 'Oriya', 'ଓଡ଼ିଆ', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('127', 'os', 'oss', '', 'Ossète', 'Ирон æвзаг', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('128', 'pa', 'pan', '', 'Panjâbî', 'ਪੰਜਾਬੀ', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('129', 'pi', 'pli', '', 'Pâli', 'पािऴ', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('130', 'pl', 'pol', '', 'Polonais', 'Polski', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('131', 'ps', 'pus', '', 'Pachto', '‫پښتو', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('132', 'pt', 'por', '', 'Portugais', 'Português', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('133', 'qu', 'que', '', 'Quechua', 'Runa Simi', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('134', 'rm', 'roh', '', 'Romanche', 'Rumantsch grischun', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('135', 'rn', 'run', '', 'Kirundi', 'kiRundi', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('136', 'ro', 'rum/ron', '', 'Roumain', 'Română', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('137', 'ru', 'rus', '', 'Russe', 'русский язык', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('138', 'rw', 'kin', '', 'Kinyarwanda', 'Kinyarwanda', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('139', 'sa', 'san', '', 'Sanskrit', 'संस्कृतम्', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('140', 'sc', 'srd', '', 'Sarde', 'sardu', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('141', 'sd', 'snd', '', 'Sindhi', 'सिन्धी', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('142', 'se', 'sme', '', 'Same du Nord', 'Davvisámegiella', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('143', 'sg', 'sag', '', 'Sango', 'Yângâ tî sängö', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('144', 'si', 'sin', '', 'Cingalais', 'සිංහල', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('145', 'sk', 'slo/slk', '', 'Slovaque', 'Slovenčina', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('146', 'sl', 'slv', '', 'Slovène', 'Slovenščina', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('147', 'sm', 'smo', '', 'Samoan', 'Gagana fa\'a Samoa', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('148', 'sn', 'sna', '', 'Shona', 'chiShona', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('149', 'so', 'som', '', 'Somali', 'Soomaaliga', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('150', 'sq', 'alb/sqi', '', 'Albanais', 'Shqip', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('151', 'sr', 'scc/srp', '', 'Serbe', 'српски језик', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('152', 'ss', 'ssw', '', 'Siswati', 'SiSwati', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('153', 'st', 'sot', '', 'Sotho du Sud', 'seSotho', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('154', 'su', 'sun', '', 'Soundanais', 'Basa Sunda', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('155', 'sv', 'swe', '', 'Suédois', 'Svenska', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('156', 'sw', 'swa', '', 'Swahili', 'Kiswahili', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('157', 'ta', 'tam', '', 'Tamoul', 'தமிழ்', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('158', 'te', 'tel', '', 'Télougou', 'తెలుగు', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('159', 'tg', 'tgk', '', 'Tadjik', 'тоҷикӣ', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('160', 'th', 'tha', '', 'Thaï', 'ไทย', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('161', 'ti', 'tir', '', 'Tigrinya', 'ትግርኛ', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('162', 'tk', 'tuk', '', 'Turkmène', 'Türkmen', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('163', 'tl', 'tgl', '', 'Tagalog', 'Tagalog', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('164', 'tn', 'tsn', '', 'Tswana', 'seTswana', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('165', 'to', 'ton', '', 'Tongien', 'faka Tonga', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('166', 'tr', 'tur', '', 'Turc', 'Türkçe', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('167', 'ts', 'tso', '', 'Tsonga', 'xiTsonga', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('168', 'tt', 'tat', '', 'Tatar', 'татарча', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('169', 'tw', 'twi', '', 'Twi', 'Twi', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('170', 'ty', 'tah', '', 'Tahitien', 'Reo Mā`ohi', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('171', 'ug', 'uig', '', 'Ouïghour', 'Uyƣurqə', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('172', 'uk', 'ukr', '', 'Ukrainien', 'українська мова', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('173', 'ur', 'urd', '', 'Ourdou', '‫اردو', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('174', 'uz', 'uzb', '', 'Ouzbek', 'O\'zbek ', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('175', 've', 'ven', '', 'Venda', 'tshiVenḓa', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('176', 'vi', 'vie', '', 'Vietnamien', 'Tiếng Việt', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('177', 'vo', 'vol', '', 'Volapük', 'Volapük', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('178', 'wa', 'wln', '', 'Wallon', 'Walon', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('179', 'wo', 'wol', '', 'Wolof', 'Wollof', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('180', 'xh', 'xho', '', 'Xhosa', 'isiXhosa', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('181', 'yi', 'yid', '', 'Yiddish', '‫ייִדיש', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('182', 'yo', 'yor', '', 'Yoruba', 'Yorùbá', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('183', 'za', 'zha', '', 'Zhuang', 'Saɯ cueŋƅ', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('184', 'zh', 'chi/zho', '', 'Chinois', '中文, 汉语, 漢語', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('185', 'zu', 'zul', '', 'Zoulou', 'isiZulu', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('186', '', '', 'hbs', 'Serbo-croate', '', NULL, NULL);
INSERT IGNORE INTO langs (lang_id, `lang_iso_639-1`, `lang_iso_639-2`, `lang_iso_639-3`, lang_name, lang_name_original, lang_created, lang_updated) VALUES ('187', '', 'sus', 'sus', 'Soussou', '', NULL, NULL);

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
