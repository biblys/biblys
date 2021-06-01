<?php
use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1622548260.
 * Generated on 2021-06-01 11:51:00 by clement 
 */
class PropelMigration_1622548260 
{
    public $comment = '';

    public function preUp(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postUp(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    public function preDown(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

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
    public function getUpSQL()
    {
        $connection_default = <<< 'EOT'

# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `alerts`
(
    `alert_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(10) unsigned,
    `article_id` int(10) unsigned,
    `alert_max_price` int(10) unsigned,
    `alert_pub_year` int(4) unsigned,
    `alert_condition` VARCHAR(4),
    `alert_insert` DATETIME,
    `alert_update` DATETIME,
    `alert_created` DATETIME,
    `alert_updated` DATETIME,
    `alert_deleted` DATETIME,
    PRIMARY KEY (`alert_id`)
) ENGINE=MyISAM;

CREATE TABLE `articles`
(
    `article_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `article_item` INTEGER,
    `article_textid` VARCHAR(32),
    `article_ean` bigint(13) unsigned,
    `article_ean_others` VARCHAR(255),
    `article_asin` VARCHAR(32),
    `article_noosfere_id` INTEGER(10),
    `article_url` VARCHAR(256),
    `type_id` TINYINT(11),
    `article_title` VARCHAR(256),
    `article_title_alphabetic` VARCHAR(256),
    `article_title_original` VARCHAR(256),
    `article_title_others` VARCHAR(256),
    `article_subtitle` VARCHAR(256),
    `article_lang_current` tinyint(3) unsigned,
    `article_lang_original` tinyint(3) unsigned,
    `article_origin_country` INTEGER,
    `article_theme_bisac` VARCHAR(16),
    `article_theme_clil` VARCHAR(16),
    `article_theme_dewey` VARCHAR(16),
    `article_theme_electre` VARCHAR(16),
    `article_source_id` int(10) unsigned,
    `article_authors` VARCHAR(256),
    `article_authors_alphabetic` VARCHAR(256),
    `collection_id` INTEGER(10),
    `article_collection` VARCHAR(256),
    `article_number` VARCHAR(8),
    `publisher_id` INTEGER,
    `article_publisher` VARCHAR(256),
    `cycle_id` int(10) unsigned,
    `article_cycle` VARCHAR(256),
    `article_tome` VARCHAR(8),
    `article_cover_version` INTEGER DEFAULT 0,
    `article_availability` tinyint(3) unsigned,
    `article_availability_dilicom` tinyint(3) unsigned DEFAULT 1,
    `article_preorder` TINYINT(1) DEFAULT 0,
    `article_price` int(10) unsigned,
    `article_price_editable` TINYINT(1) DEFAULT 0,
    `article_new_price` int(10) unsigned,
    `article_category` VARCHAR(8),
    `article_tva` TINYINT DEFAULT 1,
    `article_pdf_ean` bigint(13) unsigned,
    `article_pdf_version` VARCHAR(8) DEFAULT '0',
    `article_epub_ean` bigint(13) unsigned,
    `article_epub_version` VARCHAR(8) DEFAULT '0',
    `article_azw_ean` bigint(13) unsigned,
    `article_azw_version` VARCHAR(8) DEFAULT '0',
    `article_pages` INTEGER,
    `article_weight` INTEGER,
    `article_shaping` VARCHAR(128),
    `article_format` VARCHAR(128),
    `article_printing_process` VARCHAR(256),
    `article_age_min` INTEGER,
    `article_age_max` INTEGER,
    `article_summary` TEXT,
    `article_contents` TEXT,
    `article_bonus` TEXT,
    `article_catchline` TEXT,
    `article_biography` TEXT,
    `article_motsv` TEXT,
    `article_copyright` mediumint(4) unsigned,
    `article_pubdate` DATE,
    `article_keywords` VARCHAR(1024),
    `article_links` VARCHAR(1024),
    `article_keywords_generated` DATETIME,
    `article_publisher_stock` int(10) unsigned DEFAULT 0,
    `article_hits` int(10) unsigned DEFAULT 0,
    `article_editing_user` int(10) unsigned,
    `article_insert` DATETIME,
    `article_update` DATETIME,
    `article_created` DATETIME,
    `article_updated` DATETIME,
    `article_deleted` DATETIME,
    `article_done` TINYINT(1) DEFAULT 0,
    `article_to_check` TINYINT(1) DEFAULT 0,
    `article_pushed_to_data` DATETIME,
    `article_deletion_by` int(10) unsigned,
    `article_deletion_date` DATETIME,
    `article_deletion_reason` VARCHAR(512),
    PRIMARY KEY (`article_id`),
    UNIQUE INDEX `article_pdf_ean` (`article_pdf_ean`),
    UNIQUE INDEX `article_epub_ean` (`article_epub_ean`),
    INDEX `publisher_id` (`publisher_id`),
    INDEX `article_links` (`article_links`(767)),
    INDEX `article_keywords` (`article_keywords`(767)),
    INDEX `article_url` (`article_url`),
    INDEX `article_ean` (`article_ean`)
) ENGINE=InnoDB;

CREATE TABLE `awards`
(
    `award_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `article_id` int(10) unsigned,
    `book_id` INTEGER,
    `award_name` TEXT,
    `award_year` TEXT,
    `award_category` TEXT,
    `award_note` TEXT,
    `award_date` DATETIME,
    `award_created` DATETIME,
    `award_updated` DATETIME,
    `award_deleted` DATETIME,
    PRIMARY KEY (`award_id`)
) ENGINE=MyISAM;

CREATE TABLE `bookshops`
(
    `bookshop_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `bookshop_name` VARCHAR(255),
    `bookshop_name_alphabetic` VARCHAR(256),
    `bookshop_url` VARCHAR(128),
    `bookshop_representative` VARCHAR(256),
    `bookshop_address` VARCHAR(256),
    `bookshop_postal_code` VARCHAR(8),
    `bookshop_city` VARCHAR(128),
    `bookshop_country` VARCHAR(128),
    `bookshop_phone` VARCHAR(16),
    `bookshop_fax` VARCHAR(16),
    `bookshop_website` VARCHAR(128),
    `bookshop_email` VARCHAR(128),
    `bookshop_facebook` VARCHAR(32),
    `bookshop_twitter` VARCHAR(15),
    `bookshop_legal_form` VARCHAR(128),
    `bookshop_creation_year` VARCHAR(4),
    `bookshop_specialities` VARCHAR(256),
    `bookshop_membership` VARCHAR(512),
    `bookshop_motto` VARCHAR(128),
    `bookshop_desc` TEXT,
    `bookshop_created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `bookshop_updated` DATETIME,
    `bookshop_deleted` DATETIME,
    PRIMARY KEY (`bookshop_id`),
    UNIQUE INDEX `publisher_url` (`bookshop_url`)
) ENGINE=MyISAM;

CREATE TABLE `carts`
(
    `cart_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `cart_uid` VARCHAR(32),
    `site_id` int(10) unsigned,
    `user_id` int(10) unsigned,
    `cart_seller_id` int(10) unsigned,
    `customer_id` int(10) unsigned,
    `cart_title` VARCHAR(128),
    `cart_type` VARCHAR(4) DEFAULT '',
    `cart_ip` TEXT,
    `cart_count` int(10) unsigned DEFAULT 0,
    `cart_amount` int(10) unsigned DEFAULT 0,
    `cart_as-a-gift` VARCHAR(16),
    `cart_gift-recipient` int(10) unsigned,
    `cart_date` DATETIME,
    `cart_insert` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `cart_update` DATETIME,
    `cart_created` DATETIME,
    `cart_updated` DATETIME,
    `cart_deleted` DATETIME,
    PRIMARY KEY (`cart_id`)
) ENGINE=MyISAM;

CREATE TABLE `categories`
(
    `category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `site_id` int(10) unsigned,
    `category_name` VARCHAR(64),
    `category_url` VARCHAR(256),
    `category_desc` TEXT,
    `category_order` tinyint(3) unsigned,
    `category_hidden` TINYINT(1) DEFAULT 0,
    `category_insert` DATETIME,
    `category_update` DATETIME,
    `category_created` DATETIME,
    `category_updated` DATETIME,
    `category_deleted` DATETIME,
    PRIMARY KEY (`category_id`)
) ENGINE=MyISAM;

CREATE TABLE `cf_campaigns`
(
    `campaign_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `site_id` int(10) unsigned,
    `campaign_title` VARCHAR(255),
    `campaign_url` VARCHAR(128),
    `campaign_description` TEXT,
    `campaign_image` VARCHAR(256),
    `campaign_goal` int(10) unsigned,
    `campaign_pledged` int(10) unsigned,
    `campaign_backers` int(10) unsigned,
    `campaign_starts` DATE,
    `campaign_ends` DATE,
    `campaign_created` DATETIME,
    `campaign_updated` DATETIME,
    `campaign_deleted` DATETIME,
    PRIMARY KEY (`campaign_id`),
    UNIQUE INDEX `campaign_url` (`campaign_url`)
) ENGINE=InnoDB;

CREATE TABLE `cf_rewards`
(
    `reward_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `site_id` int(10) unsigned,
    `campaign_id` int(10) unsigned,
    `reward_content` VARCHAR(1025),
    `reward_articles` VARCHAR(255),
    `reward_price` int(10) unsigned,
    `reward_limited` TINYINT(1),
    `reward_highlighted` TINYINT(1) DEFAULT 0,
    `reward_image` VARCHAR(256),
    `reward_quantity` int(10) unsigned,
    `reward_backers` int(10) unsigned DEFAULT 0,
    `reward_created` DATETIME,
    `reward_updated` DATETIME,
    `reward_deleted` DATETIME,
    PRIMARY KEY (`reward_id`)
) ENGINE=InnoDB;

CREATE TABLE `collections`
(
    `collection_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `site_id` int(10) unsigned,
    `publisher_id` int(10) unsigned,
    `pricegrid_id` int(10) unsigned,
    `collection_name` VARCHAR(255),
    `collection_url` VARCHAR(255),
    `collection_publisher` VARCHAR(255),
    `collection_desc` TEXT,
    `collection_ignorenum` TINYINT(1),
    `collection_orderby` TEXT,
    `collection_incorrect_weights` TINYINT(1),
    `collection_noosfere_id` INTEGER,
    `collection_insert` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `collection_update` DATETIME,
    `collection_hits` INTEGER,
    `collection_duplicate` TINYINT(1) DEFAULT 0,
    `collection_created` DATETIME,
    `collection_updated` DATETIME,
    `collection_deleted` DATETIME,
    PRIMARY KEY (`collection_id`),
    UNIQUE INDEX `collection_noosfere_id` (`collection_noosfere_id`),
    INDEX `site_id` (`site_id`)
) ENGINE=MyISAM;

CREATE TABLE `countries`
(
    `country_id` INTEGER NOT NULL AUTO_INCREMENT,
    `country_code` VARCHAR(3),
    `country_name` VARCHAR(200),
    `country_name_en` VARCHAR(200),
    `shipping_zone` VARCHAR(8),
    `country_created` DATETIME,
    `country_updated` DATETIME,
    `country_deleted` DATETIME,
    PRIMARY KEY (`country_id`)
) ENGINE=InnoDB;

CREATE TABLE `coupons`
(
    `coupon_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `site_id` int(10) unsigned,
    `user_id` int(10) unsigned,
    `coupon_code` VARCHAR(6),
    `article_id` int(10) unsigned,
    `stock_id` int(10) unsigned,
    `coupon_amount` int(10) unsigned,
    `coupon_note` VARCHAR(256),
    `coupon_used` DATETIME,
    `coupon_creator` int(10) unsigned,
    `coupon_insert` DATETIME,
    `coupon_update` DATETIME,
    PRIMARY KEY (`coupon_id`),
    UNIQUE INDEX `coupon_code` (`coupon_code`),
    UNIQUE INDEX `stock_id` (`stock_id`)
) ENGINE=MyISAM;

CREATE TABLE `cron_jobs`
(
    `cron_job_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `site_id` INTEGER,
    `cron_job_task` VARCHAR(128),
    `cron_job_result` VARCHAR(16),
    `cron_job_message` VARCHAR(256),
    `cron_job_created` DATETIME,
    `cron_job_updated` DATETIME,
    `cron_job_deleted` DATETIME,
    PRIMARY KEY (`cron_job_id`)
) ENGINE=InnoDB;

CREATE TABLE `customers`
(
    `customer_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `site_id` int(10) unsigned,
    `user_id` int(10) unsigned,
    `customer_type` VARCHAR(16) DEFAULT 'Particulier',
    `customer_first_name` VARCHAR(64),
    `customer_last_name` VARCHAR(64),
    `customer_email` VARCHAR(128),
    `customer_phone` VARCHAR(16),
    `country_id` int(10) unsigned,
    `customer_privatization` DATE,
    `customer_insert` DATETIME,
    `customer_update` DATETIME,
    `customer_created` DATETIME,
    `customer_updated` DATETIME,
    `customer_deleted` DATETIME,
    PRIMARY KEY (`customer_id`),
    UNIQUE INDEX `site_id` (`site_id`, `user_id`)
) ENGINE=InnoDB;

CREATE TABLE `cycles`
(
    `cycle_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `cycle_name` VARCHAR(255),
    `cycle_url` TEXT,
    `cycle_desc` TEXT,
    `cycle_hits` INTEGER,
    `cycle_noosfere_id` INTEGER,
    `cycle_insert` DATETIME,
    `cycle_update` DATETIME,
    `cycle_created` DATETIME,
    `cycle_updated` DATETIME,
    `cycle_deleted` DATETIME,
    PRIMARY KEY (`cycle_id`),
    INDEX `cycle_name` (`cycle_name`)
) ENGINE=InnoDB;

CREATE TABLE `downloads`
(
    `download_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `file_id` int(10) unsigned,
    `article_id` int(10) unsigned,
    `book_id` int(10) unsigned,
    `user_id` int(10) unsigned,
    `download_filetype` TEXT,
    `download_version` VARCHAR(8),
    `download_ip` TEXT,
    `download_date` DATETIME,
    `download_created` DATETIME,
    `download_updated` DATETIME,
    `download_deleted` DATETIME,
    PRIMARY KEY (`download_id`)
) ENGINE=MyISAM;

CREATE TABLE `events`
(
    `event_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `site_id` int(10) unsigned,
    `publisher_id` INTEGER,
    `bookshop_id` int(10) unsigned,
    `library_id` int(10) unsigned,
    `event_url` VARCHAR(128),
    `event_title` TEXT,
    `event_subtitle` TEXT,
    `event_desc` TEXT,
    `event_location` TEXT,
    `event_illustration_legend` VARCHAR(64),
    `event_highlighted` TINYINT(1),
    `event_start` DATETIME,
    `event_end` DATETIME,
    `event_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `event_status` TINYINT(1),
    `event_insert_` DATETIME,
    `event_update_` DATETIME,
    `event_created` DATETIME,
    `event_updated` DATETIME,
    `event_deleted` DATETIME,
    PRIMARY KEY (`event_id`),
    UNIQUE INDEX `url` (`site_id`, `event_url`, `event_deleted`)
) ENGINE=MyISAM;

CREATE TABLE `files`
(
    `file_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `article_id` int(10) unsigned,
    `user_id` int(10) unsigned,
    `file_title` VARCHAR(32),
    `file_type` VARCHAR(32),
    `file_access` TINYINT(1) DEFAULT 1,
    `file_version` VARCHAR(8) DEFAULT '1.0',
    `file_hash` VARCHAR(32),
    `file_size` bigint(20) unsigned DEFAULT 0,
    `file_ean` bigint(13) unsigned,
    `file_inserted` DATETIME,
    `file_uploaded` DATETIME,
    `file_updated` DATETIME,
    `file_deleted` DATETIME,
    `file_created` DATETIME,
    PRIMARY KEY (`file_id`),
    INDEX `file_hash` (`file_hash`),
    INDEX `file_ean` (`file_ean`)
) ENGINE=MyISAM;

CREATE TABLE `galleries`
(
    `gallery_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `site_id` int(10) unsigned,
    `gallery_title` TEXT,
    `media_dir` TEXT,
    `gallery_insert` DATETIME,
    `gallery_update` DATETIME,
    `gallery_created` DATETIME,
    `gallery_updated` DATETIME,
    `gallery_deleted` DATETIME,
    PRIMARY KEY (`gallery_id`)
) ENGINE=MyISAM;

CREATE TABLE `images`
(
    `image_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `site_id` int(10) unsigned,
    `bookshop_id` INTEGER,
    `event_id` int(10) unsigned,
    `library_id` int(10) unsigned,
    `image_nature` VARCHAR(16),
    `image_legend` VARCHAR(32),
    `image_type` VARCHAR(32),
    `image_size` bigint(20) unsigned DEFAULT 0,
    `image_inserted` DATETIME,
    `image_uploaded` DATETIME,
    `image_updated` DATETIME,
    `image_deleted` DATETIME,
    PRIMARY KEY (`image_id`)
) ENGINE=MyISAM;

CREATE TABLE `inventory`
(
    `inventory_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `site_id` int(11) unsigned,
    `inventory_title` VARCHAR(32),
    `inventory_created` DATETIME,
    `inventory_updated` DATETIME,
    `inventory_deleted` DATETIME,
    PRIMARY KEY (`inventory_id`)
) ENGINE=InnoDB;

CREATE TABLE `inventory_item`
(
    `ii_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `inventory_id` int(11) unsigned,
    `ii_ean` VARCHAR(32),
    `ii_quantity` int(11) unsigned,
    `ii_stock` int(11) unsigned,
    `ii_created` DATETIME,
    `ii_updated` DATETIME,
    `ii_deleted` DATETIME,
    PRIMARY KEY (`ii_id`),
    INDEX `inventory_id` (`inventory_id`)
) ENGINE=InnoDB;

CREATE TABLE `jobs`
(
    `job_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `job_name` VARCHAR(64),
    `job_name_f` VARCHAR(64),
    `job_other_names` VARCHAR(256),
    `job_event` TINYINT(1),
    `job_order` tinyint(3) unsigned DEFAULT 0 NOT NULL,
    `job_onix` VARCHAR(3),
    `job_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `job_created` DATETIME,
    `job_updated` DATETIME,
    `job_deleted` DATETIME,
    PRIMARY KEY (`job_id`)
) ENGINE=MyISAM;

CREATE TABLE `langs`
(
    `lang_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `lang_iso_639-1` VARCHAR(2),
    `lang_iso_639-2` VARCHAR(7),
    `lang_iso_639-3` VARCHAR(7),
    `lang_name` VARCHAR(27),
    `lang_name_original` VARCHAR(35),
    `lang_created` DATETIME,
    `lang_updated` DATETIME,
    `lang_deleted` DATETIME,
    PRIMARY KEY (`lang_id`)
) ENGINE=MyISAM;

CREATE TABLE `libraries`
(
    `library_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `library_name` VARCHAR(255),
    `library_name_alphabetic` VARCHAR(256),
    `library_url` VARCHAR(128),
    `library_representative` VARCHAR(256),
    `library_address` VARCHAR(256),
    `library_postal_code` VARCHAR(8),
    `library_city` VARCHAR(128),
    `library_country` VARCHAR(128),
    `library_phone` VARCHAR(16),
    `library_fax` VARCHAR(16),
    `library_website` VARCHAR(128),
    `library_email` VARCHAR(128),
    `library_facebook` VARCHAR(128),
    `library_twitter` VARCHAR(15),
    `library_creation_year` VARCHAR(4),
    `library_specialities` VARCHAR(256),
    `library_readings` VARCHAR(512),
    `library_desc` TEXT,
    `library_created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `library_updated` DATETIME,
    `library_deleted` DATETIME,
    PRIMARY KEY (`library_id`),
    UNIQUE INDEX `publisher_url` (`library_url`)
) ENGINE=MyISAM;

CREATE TABLE `links`
(
    `link_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `site_id` int(10) unsigned,
    `user_id` int(10) unsigned,
    `article_id` int(10) unsigned,
    `stock_id` int(10) unsigned,
    `list_id` int(10) unsigned,
    `book_id` int(10) unsigned,
    `people_id` int(10) unsigned,
    `job_id` int(10) unsigned,
    `rayon_id` int(10) unsigned,
    `tag_id` int(10) unsigned,
    `event_id` int(11) unsigned,
    `post_id` int(11) unsigned,
    `collection_id` int(10) unsigned,
    `publisher_id` int(10) unsigned,
    `supplier_id` int(10) unsigned,
    `media_id` int(10) unsigned,
    `bundle_id` int(10) unsigned,
    `link_hide` TINYINT(1),
    `link_do_not_reorder` TINYINT(1),
    `link_sponsor_user_id` int(10) unsigned,
    `link_date` DATETIME,
    `link_created` DATETIME,
    `link_updated` DATETIME,
    `link_deleted` DATETIME,
    PRIMARY KEY (`link_id`),
    UNIQUE INDEX `sponsorship` (`site_id`, `user_id`, `link_sponsor_user_id`),
    UNIQUE INDEX `suppliers` (`site_id`, `publisher_id`, `supplier_id`),
    UNIQUE INDEX `myEvents` (`site_id`, `user_id`, `event_id`),
    UNIQUE INDEX `rayons` (`rayon_id`, `article_id`, `site_id`),
    UNIQUE INDEX `stock_id` (`stock_id`, `list_id`),
    UNIQUE INDEX `site_id` (`site_id`, `article_id`, `link_do_not_reorder`, `link_deleted`),
    INDEX `post_id` (`post_id`)
) ENGINE=InnoDB;

CREATE TABLE `lists`
(
    `list_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(10) unsigned,
    `site_id` int(10) unsigned,
    `list_title` VARCHAR(256),
    `list_url` VARCHAR(256),
    `list_created` DATETIME,
    `list_updated` DATETIME,
    `list_deleted` DATETIME,
    PRIMARY KEY (`list_id`)
) ENGINE=InnoDB;

CREATE TABLE `mailing`
(
    `mailing_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `site_id` tinyint(3) unsigned,
    `mailing_email` VARCHAR(256) DEFAULT '',
    `mailing_block` TINYINT(1),
    `mailing_checked` TINYINT(1),
    `mailing_date` DATETIME,
    `mailing_created` DATETIME,
    `mailing_updated` DATETIME,
    `mailing_deleted` DATETIME,
    PRIMARY KEY (`mailing_id`),
    INDEX `site_id` (`site_id`, `mailing_email`)
) ENGINE=MyISAM;

CREATE TABLE `medias`
(
    `media_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `site_id` int(10) unsigned,
    `category_id` int(10) unsigned,
    `media_dir` TEXT,
    `media_file` TEXT,
    `media_ext` TEXT,
    `media_title` TEXT,
    `media_desc` TEXT,
    `media_link` TEXT,
    `media_headline` TEXT,
    `media_insert` DATETIME,
    `media_update` DATETIME,
    `media_created` DATETIME,
    `media_updated` DATETIME,
    `media_deleted` DATETIME,
    PRIMARY KEY (`media_id`)
) ENGINE=MyISAM;

CREATE TABLE `option`
(
    `option_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `site_id` int(11) unsigned,
    `user_id` INTEGER,
    `option_key` VARCHAR(32),
    `option_value` VARCHAR(2048),
    `option_created` DATETIME,
    `option_updated` DATETIME,
    `option_deleted` DATETIME,
    PRIMARY KEY (`option_id`)
) ENGINE=InnoDB;

CREATE TABLE `orders`
(
    `order_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `order_url` VARCHAR(16),
    `site_id` INTEGER,
    `user_id` int(11) unsigned,
    `customer_id` int(10) unsigned,
    `seller_id` int(10) unsigned,
    `order_type` VARCHAR(8) DEFAULT '',
    `order_as-a-gift` VARCHAR(16),
    `order_gift-recipient` int(10) unsigned,
    `order_amount` int(10) unsigned DEFAULT 0,
    `order_discount` int(10) unsigned,
    `order_amount_tobepaid` INTEGER DEFAULT 0,
    `shipping_id` INTEGER,
    `country_id` INTEGER,
    `order_shipping` INTEGER DEFAULT 0,
    `order_shipping_mode` VARCHAR(32),
    `order_track_number` VARCHAR(16),
    `order_payment_mode` VARCHAR(32),
    `order_payment_cash` int(10) unsigned DEFAULT 0,
    `order_payment_cheque` int(10) unsigned DEFAULT 0,
    `order_payment_transfer` int(10) unsigned DEFAULT 0,
    `order_payment_card` int(10) unsigned DEFAULT 0,
    `order_payment_paypal` int(10) unsigned DEFAULT 0,
    `order_payment_payplug` int(11) unsigned DEFAULT 0,
    `order_payment_left` int(10) unsigned DEFAULT 0,
    `order_title` TEXT,
    `order_firstname` TEXT,
    `order_lastname` TEXT,
    `order_address1` TEXT,
    `order_address2` TEXT,
    `order_postalcode` TEXT,
    `order_city` TEXT,
    `order_country` VARCHAR(64),
    `order_email` TEXT,
    `order_phone` TEXT,
    `order_comment` VARCHAR(1024),
    `order_utmz` VARCHAR(1024),
    `order_utm_source` VARCHAR(256),
    `order_utm_campaign` VARCHAR(256),
    `order_utm_medium` VARCHAR(256),
    `order_referer` TEXT,
    `order_insert` DATETIME,
    `order_payment_date` DATETIME,
    `order_shipping_date` DATETIME,
    `order_followup_date` DATETIME,
    `order_confirmation_date` DATETIME,
    `order_cancel_date` DATETIME,
    `order_update` DATETIME,
    `order_created` DATETIME,
    `order_updated` DATETIME,
    `order_deleted` DATETIME,
    PRIMARY KEY (`order_id`),
    INDEX `country_id` (`country_id`),
    INDEX `site_id` (`site_id`)
) ENGINE=InnoDB;

CREATE TABLE `pages`
(
    `page_id` INTEGER NOT NULL AUTO_INCREMENT,
    `site_id` int(11) unsigned,
    `page_url` TEXT,
    `page_title` TEXT,
    `page_content` TEXT,
    `page_status` TINYINT(1),
    `page_insert` DATETIME,
    `page_update` DATETIME,
    `page_created` DATETIME,
    `page_updated` DATETIME,
    `page_deleted` DATETIME,
    PRIMARY KEY (`page_id`)
) ENGINE=MyISAM;

CREATE TABLE `payments`
(
    `payment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `site_id` INTEGER,
    `order_id` INTEGER,
    `payment_amount` INTEGER,
    `payment_mode` VARCHAR(16),
    `payment_provider_id` VARCHAR(256),
    `payment_url` VARCHAR(1024),
    `payment_created` DATETIME,
    `payment_executed` DATETIME,
    `payment_updated` DATETIME,
    `payment_deleted` DATETIME,
    PRIMARY KEY (`payment_id`)
) ENGINE=InnoDB;

CREATE TABLE `people`
(
    `people_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `people_first_name` VARCHAR(256),
    `people_last_name` VARCHAR(256),
    `people_name` VARCHAR(255),
    `people_alpha` VARCHAR(256),
    `people_url_old` VARCHAR(128),
    `people_url` VARCHAR(128),
    `people_pseudo` int(10) unsigned,
    `people_noosfere_id` INTEGER,
    `people_birth` INTEGER(4),
    `people_death` INTEGER(4),
    `people_gender` set('M','F'),
    `people_nation` VARCHAR(255),
    `people_bio` TEXT,
    `people_site` VARCHAR(255),
    `people_facebook` VARCHAR(256),
    `people_twitter` VARCHAR(256),
    `people_hits` int(10) unsigned,
    `people_date` DATETIME,
    `people_insert` DATETIME,
    `people_update` DATETIME,
    `people_created` DATETIME,
    `people_updated` DATETIME,
    `people_deleted` DATETIME,
    PRIMARY KEY (`people_id`),
    INDEX `people_name` (`people_name`)
) ENGINE=InnoDB COMMENT='Intervenants';

CREATE TABLE `permissions`
(
    `permission_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` INTEGER,
    `site_id` INTEGER,
    `permission_rank` VARCHAR(8),
    `permission_last` DATETIME,
    `permission_date` DATETIME,
    PRIMARY KEY (`permission_id`),
    UNIQUE INDEX `user_id` (`user_id`, `site_id`)
) ENGINE=MyISAM;

CREATE TABLE `posts`
(
    `post_id` INTEGER NOT NULL AUTO_INCREMENT,
    `user_id` INTEGER,
    `site_id` int(11) unsigned,
    `publisher_id` int(10) unsigned,
    `category_id` int(10) unsigned,
    `post_url` TEXT,
    `post_title` TEXT,
    `post_content` TEXT,
    `post_illustration_legend` VARCHAR(64),
    `post_selected` TINYINT(1),
    `post_link` TEXT,
    `post_status` TINYINT(1),
    `post_keywords` VARCHAR(512),
    `post_links` VARCHAR(512),
    `post_keywords_generated` DATETIME,
    `post_fb_id` BIGINT,
    `post_date` DATETIME,
    `post_hits` int(10) unsigned,
    `post_insert` DATETIME,
    `post_update` DATETIME,
    `post_created` DATETIME,
    `post_updated` DATE,
    `post_deleted` DATETIME,
    PRIMARY KEY (`post_id`)
) ENGINE=InnoDB;

CREATE TABLE `prices`
(
    `price_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `pricegrid_id` int(10) unsigned,
    `price_cat` TEXT,
    `price_amount` int(10) unsigned,
    `price_created` DATETIME,
    `price_updated` DATETIME,
    `price_deleted` DATETIME,
    PRIMARY KEY (`price_id`)
) ENGINE=MyISAM;

CREATE TABLE `publishers`
(
    `publisher_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `site_id` int(10) unsigned,
    `publisher_name` VARCHAR(255),
    `publisher_name_alphabetic` VARCHAR(256),
    `publisher_url` VARCHAR(256),
    `publisher_noosfere_id` INTEGER,
    `publisher_representative` VARCHAR(256),
    `publisher_address` TEXT,
    `publisher_postal_code` VARCHAR(8),
    `publisher_city` VARCHAR(128),
    `publisher_country` VARCHAR(128),
    `publisher_phone` VARCHAR(16),
    `publisher_fax` VARCHAR(16),
    `publisher_website` VARCHAR(128),
    `publisher_buy_link` VARCHAR(256),
    `publisher_email` VARCHAR(128),
    `publisher_facebook` VARCHAR(128),
    `publisher_twitter` VARCHAR(15),
    `publisher_legal_form` VARCHAR(128),
    `publisher_creation_year` VARCHAR(4),
    `publisher_isbn` VARCHAR(13),
    `publisher_volumes` INTEGER,
    `publisher_average_run` INTEGER,
    `publisher_specialities` TEXT,
    `publisher_diffuseur` VARCHAR(128),
    `publisher_distributeur` VARCHAR(128),
    `publisher_vpc` TINYINT(1) DEFAULT 0,
    `publisher_paypal` VARCHAR(128),
    `publisher_shipping_mode` VARCHAR(9) DEFAULT 'offerts',
    `publisher_shipping_fee` int(10) unsigned,
    `publisher_gln` bigint(13) unsigned,
    `publisher_desc` TEXT,
    `publisher_desc_short` VARCHAR(512),
    `publisher_order_by` VARCHAR(128) DEFAULT 'article_pubdate',
    `publisher_insert` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `publisher_update` DATETIME,
    `publisher_created` DATETIME,
    `publisher_updated` DATETIME,
    `publisher_deleted` DATETIME,
    PRIMARY KEY (`publisher_id`),
    UNIQUE INDEX `publisher_gln` (`publisher_gln`),
    INDEX `site_id` (`site_id`)
) ENGINE=InnoDB;

CREATE TABLE `rayons`
(
    `rayon_id` bigint(4) unsigned NOT NULL AUTO_INCREMENT,
    `site_id` tinyint(3) unsigned,
    `rayon_name` TEXT,
    `rayon_url` TEXT,
    `rayon_desc` TEXT,
    `rayon_order` TINYINT(2),
    `rayon_sort_by` VARCHAR(64) DEFAULT 'id',
    `rayon_sort_order` TINYINT(1) DEFAULT 0,
    `rayon_show_upcoming` tinyint(1) unsigned DEFAULT 0,
    `rayon_created` DATETIME,
    `rayon_updated` DATETIME,
    `rayon_deleted` DATETIME,
    PRIMARY KEY (`rayon_id`)
) ENGINE=MyISAM;

CREATE TABLE `redirections`
(
    `redirection_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `site_id` int(10) unsigned,
    `redirection_old` VARCHAR(256),
    `redirection_new` VARCHAR(256),
    `redirection_hits` INTEGER DEFAULT 0,
    `redirection_date` DATETIME,
    `redirection_created` DATETIME,
    `redirection_updated` DATETIME,
    `redirection_deleted` DATETIME,
    PRIMARY KEY (`redirection_id`),
    UNIQUE INDEX `redirection_old` (`redirection_old`),
    UNIQUE INDEX `site_id` (`site_id`, `redirection_old`)
) ENGINE=InnoDB;

CREATE TABLE `rights`
(
    `right_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `right_uid` VARCHAR(32),
    `user_id` int(10) unsigned,
    `site_id` int(10) unsigned,
    `publisher_id` int(10) unsigned,
    `bookshop_id` int(10) unsigned,
    `library_id` int(10) unsigned,
    `right_current` TINYINT(1) DEFAULT 0,
    `right_created` DATETIME,
    `right_updated` DATETIME,
    `right_deleted` DATETIME,
    PRIMARY KEY (`right_id`),
    INDEX `rights_fi_69bd79` (`user_id`),
    INDEX `rights_fi_db3f76` (`site_id`)
) ENGINE=InnoDB;

CREATE TABLE `roles`
(
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `article_id` int(10) unsigned,
    `book_id` int(10) unsigned,
    `event_id` int(10) unsigned,
    `people_id` int(10) unsigned,
    `job_id` int(10) unsigned,
    `user_id` int(10) unsigned,
    `role_hide` tinyint(1) unsigned,
    `role_presence` VARCHAR(256),
    `role_date` DATETIME,
    `role_created` DATETIME,
    `role_updated` DATETIME,
    `role_deleted` DATETIME,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `event_id` (`event_id`, `people_id`, `job_id`),
    INDEX `book_id` (`book_id`),
    INDEX `people_id` (`people_id`),
    INDEX `job_id` (`job_id`),
    INDEX `article_id` (`article_id`),
    INDEX `book_id_2` (`book_id`, `people_id`, `job_id`)
) ENGINE=MyISAM;

CREATE TABLE `session`
(
    `session_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(11) unsigned,
    `session_token` VARCHAR(32),
    `session_created` DATETIME,
    `session_expires` DATETIME,
    `session_updated` DATETIME,
    `session_deleted` DATETIME,
    PRIMARY KEY (`session_id`),
    INDEX `session_fi_69bd79` (`user_id`)
) ENGINE=InnoDB;

CREATE TABLE `shipping`
(
    `shipping_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `site_id` INTEGER,
    `article_id` int(10) unsigned,
    `shipping_mode` VARCHAR(64),
    `shipping_type` VARCHAR(16),
    `shipping_zone` VARCHAR(4),
    `shipping_min_weight` INTEGER,
    `shipping_max_weight` int(11) unsigned,
    `shipping_max_articles` int(10) unsigned,
    `shipping_min_amount` INTEGER,
    `shipping_max_amount` int(11) unsigned,
    `shipping_fee` INTEGER,
    `shipping_info` VARCHAR(512),
    `shipping_created` DATETIME,
    `shipping_updated` DATETIME,
    `shipping_deleted` DATETIME,
    PRIMARY KEY (`shipping_id`)
) ENGINE=InnoDB;

CREATE TABLE `signings`
(
    `signing_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `site_id` int(11) unsigned,
    `publisher_id` int(10) unsigned,
    `people_id` int(11) unsigned,
    `signing_date` DATE,
    `signing_starts` TIME,
    `signing_ends` TIME,
    `signing_location` VARCHAR(255),
    `signing_created` DATETIME,
    `signing_updated` DATETIME,
    `signing_deleted` DATETIME,
    PRIMARY KEY (`signing_id`),
    INDEX `site_id` (`site_id`),
    INDEX `people_id` (`people_id`),
    INDEX `publisher_id` (`publisher_id`)
) ENGINE=InnoDB;

CREATE TABLE `sites`
(
    `site_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `site_name` VARCHAR(16) DEFAULT '',
    `site_pass` VARCHAR(8) DEFAULT '',
    `site_title` VARCHAR(32) DEFAULT '',
    `site_domain` VARCHAR(255),
    `site_version` VARCHAR(16),
    `site_tag` VARCHAR(16) DEFAULT '',
    `site_flag` VARCHAR(2),
    `site_contact` VARCHAR(128) DEFAULT '',
    `site_address` VARCHAR(256) DEFAULT '',
    `site_tva` VARCHAR(2),
    `site_html_renderer` TINYINT(1),
    `site_axys` TINYINT(1) DEFAULT 1,
    `site_noosfere` TINYINT(1),
    `site_amazon` TINYINT(1),
    `site_event_id` int(10) unsigned,
    `site_event_date` int(11) unsigned,
    `site_shop` TINYINT(1),
    `site_vpc` TINYINT(1),
    `site_shipping_fee` VARCHAR(8),
    `site_alerts` TINYINT(1),
    `site_wishlist` TINYINT(1) DEFAULT 0,
    `site_payment_cheque` TINYINT(1) DEFAULT 1,
    `site_payment_paypal` VARCHAR(32),
    `site_payment_payplug` TINYINT(1) DEFAULT 0,
    `site_payment_transfer` TINYINT(1),
    `site_bookshop` TINYINT(1) DEFAULT 0,
    `site_bookshop_id` int(10) unsigned,
    `site_publisher` TINYINT(1),
    `site_publisher_stock` TINYINT(1) DEFAULT 0,
    `publisher_id` int(10) unsigned,
    `site_ebook_bundle` INTEGER,
    `site_fb_page_id` bigint(20) unsigned,
    `site_fb_page_token` TEXT,
    `site_analytics_id` VARCHAR(16),
    `site_piwik_id` INTEGER,
    `site_sitemap_updated` DATETIME,
    `site_monitoring` TINYINT(1) DEFAULT 1,
    `site_created` DATETIME,
    `site_updated` DATETIME,
    `site_deleted` DATETIME,
    PRIMARY KEY (`site_id`)
) ENGINE=InnoDB;

CREATE TABLE `stock`
(
    `stock_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `site_id` tinyint(4) unsigned,
    `article_id` int(10) unsigned,
    `campaign_id` int(10) unsigned,
    `reward_id` int(10) unsigned,
    `user_id` INTEGER(10),
    `customer_id` int(10) unsigned,
    `wish_id` int(10) unsigned,
    `cart_id` int(10) unsigned,
    `order_id` int(10) unsigned,
    `coupon_id` int(10) unsigned,
    `stock_shop` int(10) unsigned,
    `stock_invoice` VARCHAR(256),
    `stock_depot` TINYINT(1) DEFAULT 0,
    `stock_stockage` VARCHAR(16),
    `stock_condition` VARCHAR(16),
    `stock_condition_details` TEXT,
    `stock_purchase_price` int(10) unsigned,
    `stock_selling_price` int(10) unsigned,
    `stock_selling_price2` int(10) unsigned,
    `stock_selling_price_saved` int(10) unsigned,
    `stock_selling_price_ht` int(10) unsigned,
    `stock_selling_price_tva` int(10) unsigned,
    `stock_tva_rate` FLOAT,
    `stock_weight` int(10) unsigned,
    `stock_pub_year` int(4) unsigned,
    `stock_allow_predownload` TINYINT(1) DEFAULT 0,
    `stock_photo_version` int(10) unsigned DEFAULT 0,
    `stock_purchase_date` DATETIME,
    `stock_onsale_date` DATETIME,
    `stock_cart_date` DATETIME,
    `stock_selling_date` DATETIME,
    `stock_return_date` DATETIME,
    `stock_lost_date` DATETIME,
    `stock_media_ok` TINYINT(1) DEFAULT 0,
    `stock_file_updated` TINYINT(1) DEFAULT 0,
    `stock_insert` DATETIME,
    `stock_update` DATETIME,
    `stock_dl` TINYINT(1) DEFAULT 0,
    `stock_created` DATETIME,
    `stock_updated` DATETIME,
    `stock_deleted` DATETIME,
    PRIMARY KEY (`stock_id`),
    UNIQUE INDEX `coupon_id` (`coupon_id`),
    INDEX `site_id` (`site_id`),
    INDEX `cart_id` (`cart_id`),
    INDEX `article_id` (`article_id`),
    INDEX `stock_return_date` (`stock_return_date`),
    INDEX `stock_shop` (`stock_shop`),
    INDEX `order_id` (`order_id`),
    INDEX `customer_id` (`customer_id`)
) ENGINE=InnoDB;

CREATE TABLE `subscriptions`
(
    `subscription_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `site_id` int(10) unsigned,
    `user_id` int(10) unsigned,
    `publisher_id` int(10) unsigned,
    `bookshop_id` int(10) unsigned,
    `library_id` int(10) unsigned,
    `subscription_type` VARCHAR(16),
    `subscription_email` VARCHAR(256),
    `subscription_ends` SMALLINT(16),
    `subscription_option` TINYINT(1) DEFAULT 0,
    `subscription_insert` DATETIME,
    `subscription_update` DATETIME,
    `subscription_created` DATETIME,
    `subscription_updated` DATETIME,
    `subscription_deleted` DATETIME,
    PRIMARY KEY (`subscription_id`),
    INDEX `publisher_id` (`publisher_id`)
) ENGINE=MyISAM;

CREATE TABLE `suppliers`
(
    `supplier_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `site_id` tinyint(3) unsigned DEFAULT 1,
    `supplier_name` VARCHAR(256),
    `supplier_gln` BIGINT,
    `supplier_remise` int(10) unsigned,
    `supplier_notva` TINYINT(1),
    `supplier_on_order` TINYINT(1),
    `supplier_insert` DATETIME,
    `supplier_update` DATETIME,
    `supplier_created` DATETIME,
    `supplier_updated` DATETIME,
    `supplier_deleted` DATETIME,
    PRIMARY KEY (`supplier_id`)
) ENGINE=InnoDB;

CREATE TABLE `tags`
(
    `tag_id` INTEGER NOT NULL AUTO_INCREMENT,
    `tag_name` TEXT,
    `tag_url` TEXT,
    `tag_description` TEXT,
    `tag_date` DATETIME,
    `tag_num` int(10) unsigned,
    `tag_insert` DATETIME,
    `tag_update` DATETIME,
    `tag_created` DATETIME,
    `tag_updated` DATETIME,
    `tag_deleted` DATETIME,
    PRIMARY KEY (`tag_id`),
    INDEX `tag_num` (`tag_num`)
) ENGINE=MyISAM;

CREATE TABLE `ticket`
(
    `ticket_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` INTEGER,
    `site_id` INTEGER,
    `ticket_type` VARCHAR(16) DEFAULT '',
    `ticket_title` VARCHAR(255),
    `ticket_content` TEXT,
    `ticket_priority` INTEGER DEFAULT 0,
    `ticket_created` DATETIME,
    `ticket_updated` DATETIME,
    `ticket_resolved` DATETIME,
    `ticket_closed` DATETIME,
    `ticket_deleted` DATETIME,
    PRIMARY KEY (`ticket_id`)
) ENGINE=InnoDB;

CREATE TABLE `ticket_comment`
(
    `ticket_comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `ticket_id` INTEGER,
    `user_id` INTEGER,
    `ticket_comment_content` TEXT,
    `ticket_comment_created` DATETIME,
    `ticket_comment_update` DATETIME,
    `ticket_comment_deleted` DATETIME,
    PRIMARY KEY (`ticket_comment_id`)
) ENGINE=MyISAM;

CREATE TABLE `users`
(
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `Email` VARCHAR(255),
    `user_password` VARCHAR(255),
    `user_key` TEXT,
    `email_key` VARCHAR(32),
    `facebook_uid` INTEGER,
    `user_screen_name` VARCHAR(128),
    `user_slug` VARCHAR(32),
    `user_wishlist_ship` TINYINT(1) DEFAULT 0,
    `user_top` TINYINT(1),
    `user_biblio` TINYINT(1),
    `adresse_ip` VARCHAR(255),
    `recaptcha_score` FLOAT,
    `DateInscription` DATETIME,
    `DateConnexion` DATETIME,
    `publisher_id` int(10) unsigned,
    `bookshop_id` int(10) unsigned,
    `library_id` int(10) unsigned,
    `user_civilite` TEXT,
    `user_nom` TEXT,
    `user_prenom` TEXT,
    `user_adresse1` TEXT,
    `user_adresse2` TEXT,
    `user_codepostal` TEXT,
    `user_ville` TEXT,
    `user_pays` TEXT,
    `user_telephone` TEXT,
    `user_pref_articles_show` VARCHAR(8),
    `user_fb_id` BIGINT,
    `user_fb_token` VARCHAR(256),
    `country_id` int(10) unsigned,
    `user_password_reset_token` VARCHAR(32),
    `user_password_reset_token_created` DATETIME,
    `user_update` DATETIME,
    `user_created` DATETIME,
    `user_updated` DATETIME,
    `user_deleted` DATETIME,
    `user_deleted_why` VARCHAR(1024),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `user_screen_name` (`user_screen_name`),
    UNIQUE INDEX `user_slug` (`user_slug`),
    INDEX `publisher_id` (`publisher_id`),
    INDEX `Email` (`Email`)
) ENGINE=InnoDB;

CREATE TABLE `votes`
(
    `vote_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(10) unsigned,
    `vote_F` int(10) unsigned,
    `vote_E` int(10) unsigned,
    `vote_date` DATETIME,
    PRIMARY KEY (`vote_id`)
) ENGINE=MyISAM;

CREATE TABLE `wishes`
(
    `wish_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `wishlist_id` INTEGER,
    `user_id` int(10) unsigned,
    `site_id` int(10) unsigned,
    `article_id` int(10) unsigned,
    `wish_created` DATETIME,
    `wish_updated` DATETIME,
    `wish_bought` DATETIME,
    `wish_deleted` DATETIME,
    PRIMARY KEY (`wish_id`),
    UNIQUE INDEX `user_id` (`user_id`, `article_id`, `wish_deleted`)
) ENGINE=InnoDB;

CREATE TABLE `wishlist`
(
    `wishlist_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(11) unsigned,
    `wishlist_name` VARCHAR(128),
    `wishlist_current` TINYINT(1),
    `wishlist_public` TINYINT(1),
    `wishlist_created` DATETIME,
    `wishlist_updated` DATETIME,
    `wishlist_deleted` DATETIME,
    PRIMARY KEY (`wishlist_id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
EOT;

        return array(
            'default' => $connection_default,
        );
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        $connection_default = <<< 'EOT'

# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `alerts`;

DROP TABLE IF EXISTS `articles`;

DROP TABLE IF EXISTS `awards`;

DROP TABLE IF EXISTS `bookshops`;

DROP TABLE IF EXISTS `carts`;

DROP TABLE IF EXISTS `categories`;

DROP TABLE IF EXISTS `cf_campaigns`;

DROP TABLE IF EXISTS `cf_rewards`;

DROP TABLE IF EXISTS `collections`;

DROP TABLE IF EXISTS `countries`;

DROP TABLE IF EXISTS `coupons`;

DROP TABLE IF EXISTS `cron_jobs`;

DROP TABLE IF EXISTS `customers`;

DROP TABLE IF EXISTS `cycles`;

DROP TABLE IF EXISTS `downloads`;

DROP TABLE IF EXISTS `events`;

DROP TABLE IF EXISTS `files`;

DROP TABLE IF EXISTS `galleries`;

DROP TABLE IF EXISTS `images`;

DROP TABLE IF EXISTS `inventory`;

DROP TABLE IF EXISTS `inventory_item`;

DROP TABLE IF EXISTS `jobs`;

DROP TABLE IF EXISTS `langs`;

DROP TABLE IF EXISTS `libraries`;

DROP TABLE IF EXISTS `links`;

DROP TABLE IF EXISTS `lists`;

DROP TABLE IF EXISTS `mailing`;

DROP TABLE IF EXISTS `medias`;

DROP TABLE IF EXISTS `option`;

DROP TABLE IF EXISTS `orders`;

DROP TABLE IF EXISTS `pages`;

DROP TABLE IF EXISTS `payments`;

DROP TABLE IF EXISTS `people`;

DROP TABLE IF EXISTS `permissions`;

DROP TABLE IF EXISTS `posts`;

DROP TABLE IF EXISTS `prices`;

DROP TABLE IF EXISTS `publishers`;

DROP TABLE IF EXISTS `rayons`;

DROP TABLE IF EXISTS `redirections`;

DROP TABLE IF EXISTS `rights`;

DROP TABLE IF EXISTS `roles`;

DROP TABLE IF EXISTS `session`;

DROP TABLE IF EXISTS `shipping`;

DROP TABLE IF EXISTS `signings`;

DROP TABLE IF EXISTS `sites`;

DROP TABLE IF EXISTS `stock`;

DROP TABLE IF EXISTS `subscriptions`;

DROP TABLE IF EXISTS `suppliers`;

DROP TABLE IF EXISTS `tags`;

DROP TABLE IF EXISTS `ticket`;

DROP TABLE IF EXISTS `ticket_comment`;

DROP TABLE IF EXISTS `users`;

DROP TABLE IF EXISTS `votes`;

DROP TABLE IF EXISTS `wishes`;

DROP TABLE IF EXISTS `wishlist`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
EOT;

        return array(
            'default' => $connection_default,
        );
    }

}