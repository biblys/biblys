
-- Create syntax for TABLE 'alerts'
CREATE TABLE `alerts` (
  `alert_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `article_id` int(10) unsigned DEFAULT NULL,
  `alert_max_price` int(10) unsigned DEFAULT NULL,
  `alert_pub_year` int(4) unsigned DEFAULT NULL,
  `alert_condition` varchar(4) DEFAULT NULL,
  `alert_insert` datetime DEFAULT NULL,
  `alert_update` datetime DEFAULT NULL,
  `alert_created` datetime DEFAULT NULL,
  `alert_updated` datetime DEFAULT NULL,
  `alert_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`alert_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'articles'
CREATE TABLE `articles` (
  `article_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `article_item` int(11) DEFAULT NULL,
  `article_textid` varchar(32) CHARACTER SET latin1 DEFAULT NULL,
  `article_ean` bigint(13) unsigned DEFAULT NULL,
  `article_ean_others` varchar(255) DEFAULT NULL,
  `article_asin` varchar(32) CHARACTER SET latin1 DEFAULT NULL,
  `article_noosfere_id` int(10) DEFAULT NULL,
  `article_url` varchar(256) CHARACTER SET latin1 DEFAULT NULL,
  `type_id` tinyint(11) DEFAULT NULL,
  `article_title` varchar(256) CHARACTER SET latin1 DEFAULT NULL,
  `article_title_alphabetic` varchar(256) CHARACTER SET latin1 DEFAULT NULL,
  `article_title_original` varchar(256) CHARACTER SET latin1 DEFAULT NULL,
  `article_title_others` varchar(256) CHARACTER SET latin1 DEFAULT NULL,
  `article_subtitle` varchar(256) CHARACTER SET latin1 DEFAULT NULL,
  `article_lang_current` tinyint(3) unsigned DEFAULT NULL,
  `article_lang_original` tinyint(3) unsigned DEFAULT NULL,
  `article_origin_country` int(11) DEFAULT NULL,
  `article_theme_bisac` varchar(16) CHARACTER SET latin1 DEFAULT NULL,
  `article_theme_clil` varchar(16) CHARACTER SET latin1 DEFAULT NULL,
  `article_theme_dewey` varchar(16) CHARACTER SET latin1 DEFAULT NULL,
  `article_theme_electre` varchar(16) CHARACTER SET latin1 DEFAULT NULL,
  `article_source_id` int(10) unsigned DEFAULT NULL,
  `article_authors` varchar(256) CHARACTER SET latin1 DEFAULT NULL,
  `article_authors_alphabetic` varchar(256) CHARACTER SET latin1 DEFAULT NULL,
  `collection_id` int(10) DEFAULT NULL,
  `article_collection` varchar(256) CHARACTER SET latin1 DEFAULT NULL,
  `article_number` varchar(8) CHARACTER SET latin1 DEFAULT NULL,
  `publisher_id` int(11) DEFAULT NULL,
  `article_publisher` varchar(256) CHARACTER SET latin1 DEFAULT NULL,
  `cycle_id` int(10) unsigned DEFAULT NULL,
  `article_cycle` varchar(256) CHARACTER SET latin1 DEFAULT NULL,
  `article_tome` varchar(8) CHARACTER SET latin1 DEFAULT NULL,
  `article_cover_version` int(11) DEFAULT NULL DEFAULT '0',
  `article_availability` tinyint(3) unsigned DEFAULT NULL,
  `article_availability_dilicom` tinyint(3) unsigned DEFAULT NULL DEFAULT '1',
  `article_preorder` tinyint(1) DEFAULT NULL DEFAULT '0',
  `article_price` int(10) unsigned DEFAULT NULL,
  `article_price_editable` tinyint(1) DEFAULT NULL DEFAULT '0',
  `article_new_price` int(10) unsigned DEFAULT NULL,
  `article_category` varchar(8) CHARACTER SET latin1 DEFAULT NULL,
  `article_tva` tinyint(4) DEFAULT NULL DEFAULT '1',
  `article_pdf_ean` bigint(13) unsigned DEFAULT NULL,
  `article_pdf_version` varchar(8) CHARACTER SET latin1 DEFAULT NULL DEFAULT '0',
  `article_epub_ean` bigint(13) unsigned DEFAULT NULL,
  `article_epub_version` varchar(8) CHARACTER SET latin1 DEFAULT NULL DEFAULT '0',
  `article_azw_ean` bigint(13) unsigned DEFAULT NULL,
  `article_azw_version` varchar(8) CHARACTER SET latin1 DEFAULT NULL DEFAULT '0',
  `article_pages` int(11) DEFAULT NULL,
  `article_weight` int(11) DEFAULT NULL,
  `article_shaping` varchar(128) CHARACTER SET latin1 DEFAULT NULL,
  `article_format` varchar(128) DEFAULT NULL,
  `article_printing_process` varchar(256) DEFAULT NULL,
  `article_age_min` int(11) DEFAULT NULL,
  `article_age_max` int(11) DEFAULT NULL,
  `article_summary` text CHARACTER SET latin1,
  `article_contents` text CHARACTER SET latin1,
  `article_bonus` text CHARACTER SET latin1,
  `article_catchline` text,
  `article_biography` text,
  `article_motsv` text CHARACTER SET latin1,
  `article_copyright` mediumint(4) unsigned DEFAULT NULL,
  `article_pubdate` date DEFAULT NULL,
  `article_keywords` varchar(1024) CHARACTER SET latin1 DEFAULT NULL,
  `article_links` varchar(1024) CHARACTER SET latin1 DEFAULT NULL,
  `article_keywords_generated` datetime DEFAULT NULL,
  `article_publisher_stock` int(10) unsigned DEFAULT NULL DEFAULT '0',
  `article_hits` int(10) unsigned DEFAULT NULL DEFAULT '0',
  `article_editing_user` int(10) unsigned DEFAULT NULL,
  `article_insert` datetime DEFAULT NULL,
  `article_update` datetime DEFAULT NULL,
  `article_created` datetime DEFAULT NULL,
  `article_updated` datetime DEFAULT NULL,
  `article_deleted` datetime DEFAULT NULL,
  `article_done` tinyint(1) DEFAULT NULL DEFAULT '0',
  `article_to_check` tinyint(1) DEFAULT NULL DEFAULT '0',
  `article_pushed_to_data` datetime DEFAULT NULL,
  `article_deletion_by` int(10) unsigned DEFAULT NULL,
  `article_deletion_date` datetime DEFAULT NULL,
  `article_deletion_reason` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`article_id`),
  UNIQUE KEY `article_pdf_ean` (`article_pdf_ean`),
  UNIQUE KEY `article_epub_ean` (`article_epub_ean`),
  KEY `publisher_id` (`publisher_id`),
  KEY `article_links` (`article_links`(767)),
  KEY `article_keywords` (`article_keywords`(767)),
  KEY `article_url` (`article_url`) USING BTREE,
  KEY `article_ean` (`article_ean`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'awards'
CREATE TABLE `awards` (
  `award_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(10) unsigned DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `award_name` text DEFAULT NULL,
  `award_year` text DEFAULT NULL,
  `award_category` text DEFAULT NULL,
  `award_note` text DEFAULT NULL,
  `award_date` datetime DEFAULT NULL,
  `award_created` datetime DEFAULT NULL,
  `award_updated` datetime DEFAULT NULL,
  `award_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`award_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'bookshops'
CREATE TABLE `bookshops` (
  `bookshop_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bookshop_name` varchar(255) DEFAULT NULL,
  `bookshop_name_alphabetic` varchar(256) DEFAULT NULL,
  `bookshop_url` varchar(128) DEFAULT NULL,
  `bookshop_representative` varchar(256) DEFAULT NULL,
  `bookshop_address` varchar(256) DEFAULT NULL,
  `bookshop_postal_code` varchar(8) DEFAULT NULL,
  `bookshop_city` varchar(128) DEFAULT NULL,
  `bookshop_country` varchar(128) DEFAULT NULL,
  `bookshop_phone` varchar(16) DEFAULT NULL,
  `bookshop_fax` varchar(16) DEFAULT NULL,
  `bookshop_website` varchar(128) DEFAULT NULL,
  `bookshop_email` varchar(128) DEFAULT NULL,
  `bookshop_facebook` varchar(32) DEFAULT NULL,
  `bookshop_twitter` varchar(15) DEFAULT NULL,
  `bookshop_legal_form` varchar(128) DEFAULT NULL,
  `bookshop_creation_year` varchar(4) DEFAULT NULL,
  `bookshop_specialities` varchar(256) DEFAULT NULL,
  `bookshop_membership` varchar(512) DEFAULT NULL,
  `bookshop_motto` varchar(128) DEFAULT NULL,
  `bookshop_desc` text,
  `bookshop_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `bookshop_updated` datetime DEFAULT NULL,
  `bookshop_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`bookshop_id`),
  UNIQUE KEY `publisher_url` (`bookshop_url`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'carts'
CREATE TABLE `carts` (
  `cart_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cart_uid` varchar(32) DEFAULT NULL,
  `site_id` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `cart_seller_id` int(10) unsigned DEFAULT NULL,
  `customer_id` int(10) unsigned DEFAULT NULL,
  `cart_title` varchar(128) DEFAULT NULL,
  `cart_type` varchar(4) DEFAULT NULL DEFAULT '',
  `cart_ip` text DEFAULT NULL,
  `cart_count` int(10) unsigned DEFAULT NULL DEFAULT '0',
  `cart_amount` int(10) unsigned DEFAULT NULL DEFAULT '0',
  `cart_as-a-gift` varchar(16) DEFAULT NULL,
  `cart_gift-recipient` int(10) unsigned DEFAULT NULL,
  `cart_date` datetime DEFAULT NULL,
  `cart_insert` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `cart_update` datetime DEFAULT NULL,
  `cart_created` datetime DEFAULT NULL,
  `cart_updated` datetime DEFAULT NULL,
  `cart_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`cart_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'categories'
CREATE TABLE `categories` (
  `category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned DEFAULT NULL,
  `category_name` varchar(64) DEFAULT NULL,
  `category_url` varchar(256) DEFAULT NULL,
  `category_desc` text,
  `category_order` tinyint(3) unsigned DEFAULT NULL,
  `category_hidden` tinyint(1) DEFAULT NULL DEFAULT '0',
  `category_insert` datetime DEFAULT NULL,
  `category_update` datetime DEFAULT NULL,
  `category_created` datetime DEFAULT NULL,
  `category_updated` datetime DEFAULT NULL,
  `category_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'cf_campaigns'
CREATE TABLE `cf_campaigns` (
  `campaign_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned DEFAULT NULL,
  `campaign_title` varchar(255) DEFAULT NULL,
  `campaign_url` varchar(128) DEFAULT NULL,
  `campaign_description` text,
  `campaign_image` varchar(256) DEFAULT NULL,
  `campaign_goal` int(10) unsigned DEFAULT NULL,
  `campaign_pledged` int(10) unsigned DEFAULT NULL,
  `campaign_backers` int(10) unsigned DEFAULT NULL,
  `campaign_starts` date DEFAULT NULL,
  `campaign_ends` date DEFAULT NULL,
  `campaign_created` datetime DEFAULT NULL,
  `campaign_updated` datetime DEFAULT NULL,
  `campaign_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`campaign_id`),
  UNIQUE KEY `campaign_url` (`campaign_url`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'cf_rewards'
CREATE TABLE `cf_rewards` (
  `reward_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned DEFAULT NULL,
  `campaign_id` int(10) unsigned DEFAULT NULL,
  `reward_content` varchar(1025) DEFAULT NULL,
  `reward_articles` varchar(255) DEFAULT NULL,
  `reward_price` int(10) unsigned DEFAULT NULL,
  `reward_limited` tinyint(1) DEFAULT NULL,
  `reward_highlighted` tinyint(1) DEFAULT NULL DEFAULT '0',
  `reward_image` varchar(256) DEFAULT NULL,
  `reward_quantity` int(10) unsigned DEFAULT NULL,
  `reward_backers` int(10) unsigned DEFAULT NULL DEFAULT '0',
  `reward_created` datetime DEFAULT NULL,
  `reward_updated` datetime DEFAULT NULL,
  `reward_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`reward_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'collections'
CREATE TABLE `collections` (
  `collection_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned DEFAULT NULL,
  `publisher_id` int(10) unsigned DEFAULT NULL,
  `pricegrid_id` int(10) unsigned DEFAULT NULL,
  `collection_name` varchar(255) DEFAULT NULL,
  `collection_url` varchar(255) DEFAULT NULL,
  `collection_publisher` varchar(255) DEFAULT NULL,
  `collection_desc` text DEFAULT NULL,
  `collection_ignorenum` tinyint(1) DEFAULT NULL,
  `collection_orderby` text DEFAULT NULL,
  `collection_incorrect_weights` tinyint(1) DEFAULT NULL,
  `collection_noosfere_id` int(11) DEFAULT NULL,
  `collection_insert` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `collection_update` datetime DEFAULT NULL,
  `collection_hits` int(11) DEFAULT NULL,
  `collection_duplicate` tinyint(1) DEFAULT NULL DEFAULT '0',
  `collection_created` datetime DEFAULT NULL,
  `collection_updated` datetime DEFAULT NULL,
  `collection_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`collection_id`),
  UNIQUE KEY `collection_noosfere_id` (`collection_noosfere_id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'countries'
CREATE TABLE `countries` (
  `country_id` int(11) NOT NULL AUTO_INCREMENT,
  `country_code` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_name_en` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `shipping_zone` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_created` datetime DEFAULT NULL,
  `country_updated` datetime DEFAULT NULL,
  `country_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=239 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'coupons'
CREATE TABLE `coupons` (
  `coupon_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `coupon_code` varchar(6) DEFAULT NULL,
  `article_id` int(10) unsigned DEFAULT NULL,
  `stock_id` int(10) unsigned DEFAULT NULL,
  `coupon_amount` int(10) unsigned DEFAULT NULL,
  `coupon_note` varchar(256) DEFAULT NULL,
  `coupon_used` datetime DEFAULT NULL,
  `coupon_creator` int(10) unsigned DEFAULT NULL,
  `coupon_insert` datetime DEFAULT NULL,
  `coupon_update` datetime DEFAULT NULL,
  PRIMARY KEY (`coupon_id`),
  UNIQUE KEY `coupon_code` (`coupon_code`),
  UNIQUE KEY `stock_id` (`stock_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'cron_jobs'
CREATE TABLE `cron_jobs` (
  `cron_job_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(11) DEFAULT NULL,
  `cron_job_task` varchar(128) DEFAULT NULL,
  `cron_job_result` varchar(16) DEFAULT NULL,
  `cron_job_message` varchar(256) DEFAULT NULL,
  `cron_job_created` datetime DEFAULT NULL,
  `cron_job_updated` datetime DEFAULT NULL,
  `cron_job_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`cron_job_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'customers'
CREATE TABLE `customers` (
  `customer_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `customer_type` varchar(16) CHARACTER SET latin1 DEFAULT 'Particulier',
  `customer_first_name` varchar(64) CHARACTER SET latin1 DEFAULT NULL,
  `customer_last_name` varchar(64) CHARACTER SET latin1 DEFAULT NULL,
  `customer_email` varchar(128) CHARACTER SET latin1 DEFAULT NULL,
  `customer_phone` varchar(16) CHARACTER SET latin1 DEFAULT NULL,
  `country_id` int(10) unsigned DEFAULT NULL,
  `customer_privatization` date DEFAULT NULL,
  `customer_insert` datetime DEFAULT NULL,
  `customer_update` datetime DEFAULT NULL,
  `customer_created` datetime DEFAULT NULL,
  `customer_updated` datetime DEFAULT NULL,
  `customer_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `site_id` (`site_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'cycles'
CREATE TABLE `cycles` (
  `cycle_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cycle_name` varchar(255) DEFAULT NULL,
  `cycle_url` text DEFAULT NULL,
  `cycle_desc` text DEFAULT NULL,
  `cycle_hits` int(11) DEFAULT NULL,
  `cycle_noosfere_id` int(11) DEFAULT NULL,
  `cycle_insert` timestamp NULL DEFAULT NULL,
  `cycle_update` datetime DEFAULT NULL,
  `cycle_created` datetime DEFAULT NULL,
  `cycle_updated` datetime DEFAULT NULL,
  `cycle_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`cycle_id`),
  KEY `cycle_name` (`cycle_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'downloads'
CREATE TABLE `downloads` (
  `download_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `file_id` int(10) unsigned DEFAULT NULL,
  `article_id` int(10) unsigned DEFAULT NULL,
  `book_id` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `download_filetype` text DEFAULT NULL,
  `download_version` varchar(8) DEFAULT NULL,
  `download_ip` text DEFAULT NULL,
  `download_date` datetime DEFAULT NULL,
  `download_created` datetime DEFAULT NULL,
  `download_updated` datetime DEFAULT NULL,
  `download_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`download_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'events'
CREATE TABLE `events` (
  `event_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned DEFAULT NULL,
  `publisher_id` int(11) DEFAULT NULL,
  `bookshop_id` int(10) unsigned DEFAULT NULL,
  `library_id` int(10) unsigned DEFAULT NULL,
  `event_url` varchar(128) DEFAULT NULL,
  `event_title` text DEFAULT NULL,
  `event_subtitle` text DEFAULT NULL,
  `event_desc` text DEFAULT NULL,
  `event_location` text DEFAULT NULL,
  `event_illustration_legend` varchar(64) DEFAULT NULL,
  `event_highlighted` tinyint(1) DEFAULT NULL,
  `event_start` datetime DEFAULT NULL,
  `event_end` timestamp NULL DEFAULT NULL,
  `event_date` timestamp DEFAULT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `event_status` tinyint(1) DEFAULT NULL,
  `event_insert_` datetime DEFAULT NULL,
  `event_update_` datetime DEFAULT NULL,
  `event_created` datetime DEFAULT NULL,
  `event_updated` datetime DEFAULT NULL,
  `event_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`event_id`),
  UNIQUE KEY `url` (`site_id`,`event_url`,`event_deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'files'
CREATE TABLE `files` (
  `file_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `file_title` varchar(32) DEFAULT NULL,
  `file_type` varchar(32) DEFAULT NULL,
  `file_access` tinyint(1) DEFAULT NULL DEFAULT '1',
  `file_version` varchar(8) DEFAULT NULL DEFAULT '1.0',
  `file_hash` varchar(32) DEFAULT NULL,
  `file_size` bigint(20) unsigned DEFAULT NULL DEFAULT '0',
  `file_ean` bigint(13) unsigned DEFAULT NULL,
  `file_inserted` datetime DEFAULT NULL,
  `file_uploaded` datetime DEFAULT NULL,
  `file_updated` datetime DEFAULT NULL,
  `file_deleted` datetime DEFAULT NULL,
  `file_created` datetime DEFAULT NULL,
  PRIMARY KEY (`file_id`),
  KEY `file_hash` (`file_hash`),
  KEY `file_ean` (`file_ean`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'galleries'
CREATE TABLE `galleries` (
  `gallery_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned DEFAULT NULL,
  `gallery_title` text DEFAULT NULL,
  `media_dir` text DEFAULT NULL,
  `gallery_insert` datetime DEFAULT NULL,
  `gallery_update` datetime DEFAULT NULL,
  `gallery_created` datetime DEFAULT NULL,
  `gallery_updated` datetime DEFAULT NULL,
  `gallery_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`gallery_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'images'
CREATE TABLE `images` (
  `image_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned DEFAULT NULL,
  `bookshop_id` int(11) DEFAULT NULL,
  `event_id` int(10) unsigned DEFAULT NULL,
  `library_id` int(10) unsigned DEFAULT NULL,
  `image_nature` varchar(16) DEFAULT NULL,
  `image_legend` varchar(32) DEFAULT NULL,
  `image_type` varchar(32) DEFAULT NULL,
  `image_size` bigint(20) unsigned DEFAULT NULL DEFAULT '0',
  `image_inserted` datetime DEFAULT NULL,
  `image_uploaded` datetime DEFAULT NULL,
  `image_updated` datetime DEFAULT NULL,
  `image_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`image_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'inventory'
CREATE TABLE `inventory` (
  `inventory_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(11) unsigned DEFAULT NULL,
  `inventory_title` varchar(32) DEFAULT NULL,
  `inventory_created` datetime DEFAULT NULL,
  `inventory_updated` datetime DEFAULT NULL,
  `inventory_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`inventory_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'inventory_item'
CREATE TABLE `inventory_item` (
  `ii_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `inventory_id` int(11) unsigned DEFAULT NULL,
  `ii_ean` varchar(32) DEFAULT NULL,
  `ii_quantity` int(11) unsigned DEFAULT NULL,
  `ii_stock` int(11) unsigned DEFAULT NULL,
  `ii_created` datetime DEFAULT NULL,
  `ii_updated` datetime DEFAULT NULL,
  `ii_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`ii_id`),
  KEY `inventory_id` (`inventory_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'jobs'
CREATE TABLE `jobs` (
  `job_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `job_name` varchar(64) DEFAULT NULL,
  `job_name_f` varchar(64) DEFAULT NULL,
  `job_other_names` varchar(256) DEFAULT NULL,
  `job_event` tinyint(1) DEFAULT NULL,
  `job_order` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `job_onix` varchar(3) DEFAULT NULL,
  `job_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `job_created` datetime DEFAULT NULL,
  `job_updated` datetime DEFAULT NULL,
  `job_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`job_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'langs'
CREATE TABLE `langs` (
  `lang_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lang_iso_639-1` varchar(2) DEFAULT NULL,
  `lang_iso_639-2` varchar(7) DEFAULT NULL,
  `lang_iso_639-3` varchar(7) DEFAULT NULL,
  `lang_name` varchar(27) DEFAULT NULL,
  `lang_name_original` varchar(35) DEFAULT NULL,
  `lang_created` datetime DEFAULT NULL,
  `lang_updated` datetime DEFAULT NULL,
  `lang_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`lang_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'libraries'
CREATE TABLE `libraries` (
  `library_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `library_name` varchar(255) DEFAULT NULL,
  `library_name_alphabetic` varchar(256) DEFAULT NULL,
  `library_url` varchar(128) DEFAULT NULL,
  `library_representative` varchar(256) DEFAULT NULL,
  `library_address` varchar(256) DEFAULT NULL,
  `library_postal_code` varchar(8) DEFAULT NULL,
  `library_city` varchar(128) DEFAULT NULL,
  `library_country` varchar(128) DEFAULT NULL,
  `library_phone` varchar(16) DEFAULT NULL,
  `library_fax` varchar(16) DEFAULT NULL,
  `library_website` varchar(128) DEFAULT NULL,
  `library_email` varchar(128) DEFAULT NULL,
  `library_facebook` varchar(128) DEFAULT NULL,
  `library_twitter` varchar(15) DEFAULT NULL,
  `library_creation_year` varchar(4) DEFAULT NULL,
  `library_specialities` varchar(256) DEFAULT NULL,
  `library_readings` varchar(512) DEFAULT NULL,
  `library_desc` text,
  `library_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `library_updated` datetime DEFAULT NULL,
  `library_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`library_id`),
  UNIQUE KEY `publisher_url` (`library_url`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'links'
CREATE TABLE `links` (
  `link_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `article_id` int(10) unsigned DEFAULT NULL,
  `stock_id` int(10) unsigned DEFAULT NULL,
  `list_id` int(10) unsigned DEFAULT NULL,
  `book_id` int(10) unsigned DEFAULT NULL,
  `people_id` int(10) unsigned DEFAULT NULL,
  `job_id` int(10) unsigned DEFAULT NULL,
  `rayon_id` int(10) unsigned DEFAULT NULL,
  `tag_id` int(10) unsigned DEFAULT NULL,
  `event_id` int(11) unsigned DEFAULT NULL,
  `post_id` int(11) unsigned DEFAULT NULL,
  `collection_id` int(10) unsigned DEFAULT NULL,
  `publisher_id` int(10) unsigned DEFAULT NULL,
  `supplier_id` int(10) unsigned DEFAULT NULL,
  `media_id` int(10) unsigned DEFAULT NULL,
  `bundle_id` int(10) unsigned DEFAULT NULL,
  `link_hide` tinyint(1) DEFAULT NULL,
  `link_do_not_reorder` tinyint(1) DEFAULT NULL,
  `link_sponsor_user_id` int(10) unsigned DEFAULT NULL,
  `link_date` datetime DEFAULT NULL,
  `link_created` datetime DEFAULT NULL,
  `link_updated` datetime DEFAULT NULL,
  `link_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`link_id`),
  UNIQUE KEY `sponsorship` (`site_id`,`user_id`,`link_sponsor_user_id`),
  UNIQUE KEY `suppliers` (`site_id`,`publisher_id`,`supplier_id`),
  UNIQUE KEY `myEvents` (`site_id`,`user_id`,`event_id`),
  UNIQUE KEY `rayons` (`rayon_id`,`article_id`,`site_id`),
  UNIQUE KEY `stock_id` (`stock_id`,`list_id`),
  UNIQUE KEY `site_id` (`site_id`,`article_id`,`link_do_not_reorder`,`link_deleted`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'lists'
CREATE TABLE `lists` (
  `list_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `site_id` int(10) unsigned DEFAULT NULL,
  `list_title` varchar(256) CHARACTER SET latin1 DEFAULT NULL,
  `list_url` varchar(256) CHARACTER SET latin1 DEFAULT NULL,
  `list_created` datetime DEFAULT NULL,
  `list_updated` datetime DEFAULT NULL,
  `list_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'mailing'
CREATE TABLE `mailing` (
  `mailing_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` tinyint(3) unsigned DEFAULT NULL,
  `mailing_email` varchar(256) DEFAULT NULL DEFAULT '',
  `mailing_block` tinyint(1) DEFAULT NULL,
  `mailing_checked` tinyint(1) DEFAULT NULL,
  `mailing_date` datetime DEFAULT NULL,
  `mailing_created` datetime DEFAULT NULL,
  `mailing_updated` datetime DEFAULT NULL,
  `mailing_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`mailing_id`),
  KEY `site_id` (`site_id`,`mailing_email`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'medias'
CREATE TABLE `medias` (
  `media_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned DEFAULT NULL,
  `category_id` int(10) unsigned DEFAULT NULL,
  `media_dir` text DEFAULT NULL,
  `media_file` text DEFAULT NULL,
  `media_ext` text DEFAULT NULL,
  `media_title` text DEFAULT NULL,
  `media_desc` text DEFAULT NULL,
  `media_link` text DEFAULT NULL,
  `media_headline` text DEFAULT NULL,
  `media_insert` datetime DEFAULT NULL,
  `media_update` datetime DEFAULT NULL,
  `media_created` datetime DEFAULT NULL,
  `media_updated` datetime DEFAULT NULL,
  `media_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`media_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'option'
CREATE TABLE `option` (
  `option_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(11) unsigned DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `option_key` varchar(32) DEFAULT NULL,
  `option_value` varchar(2048) DEFAULT NULL,
  `option_created` datetime DEFAULT NULL,
  `option_updated` datetime DEFAULT NULL,
  `option_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`option_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'orders'
CREATE TABLE `orders` (
  `order_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_url` varchar(16) CHARACTER SET latin1 DEFAULT NULL,
  `site_id` int(11) DEFAULT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `customer_id` int(10) unsigned DEFAULT NULL,
  `seller_id` int(10) unsigned DEFAULT NULL,
  `order_type` varchar(8) CHARACTER SET latin1 DEFAULT '',
  `order_as-a-gift` varchar(16) CHARACTER SET latin1 DEFAULT NULL,
  `order_gift-recipient` int(10) unsigned DEFAULT NULL,
  `order_amount` int(10) unsigned DEFAULT NULL DEFAULT '0',
  `order_discount` int(10) unsigned DEFAULT NULL,
  `order_amount_tobepaid` int(11) DEFAULT NULL DEFAULT '0',
  `shipping_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `order_shipping` int(11) DEFAULT NULL DEFAULT '0',
  `order_shipping_mode` varchar(32) DEFAULT NULL,
  `order_track_number` varchar(16) CHARACTER SET latin1 DEFAULT NULL,
  `order_payment_mode` varchar(32) DEFAULT NULL,
  `order_payment_cash` int(10) unsigned DEFAULT NULL DEFAULT '0',
  `order_payment_cheque` int(10) unsigned DEFAULT NULL DEFAULT '0',
  `order_payment_transfer` int(10) unsigned DEFAULT NULL DEFAULT '0',
  `order_payment_card` int(10) unsigned DEFAULT NULL DEFAULT '0',
  `order_payment_paypal` int(10) unsigned DEFAULT NULL DEFAULT '0',
  `order_payment_payplug` int(11) unsigned DEFAULT NULL DEFAULT '0',
  `order_payment_left` int(10) unsigned DEFAULT NULL DEFAULT '0',
  `order_title` text CHARACTER SET latin1,
  `order_firstname` text CHARACTER SET latin1,
  `order_lastname` text CHARACTER SET latin1,
  `order_address1` text CHARACTER SET latin1,
  `order_address2` text CHARACTER SET latin1,
  `order_postalcode` text CHARACTER SET latin1,
  `order_city` text CHARACTER SET latin1,
  `order_country` varchar(64) CHARACTER SET latin1 DEFAULT NULL,
  `order_email` text CHARACTER SET latin1,
  `order_phone` text CHARACTER SET latin1,
  `order_comment` varchar(1024) DEFAULT NULL,
  `order_utmz` varchar(1024) CHARACTER SET latin1 DEFAULT NULL,
  `order_utm_source` varchar(256) DEFAULT NULL,
  `order_utm_campaign` varchar(256) DEFAULT NULL,
  `order_utm_medium` varchar(256) DEFAULT NULL,
  `order_referer` text CHARACTER SET latin1,
  `order_insert` datetime DEFAULT NULL,
  `order_payment_date` datetime DEFAULT NULL,
  `order_shipping_date` datetime DEFAULT NULL,
  `order_followup_date` datetime DEFAULT NULL,
  `order_confirmation_date` datetime DEFAULT NULL,
  `order_cancel_date` datetime DEFAULT NULL,
  `order_update` timestamp NULL DEFAULT NULL,
  `order_created` datetime DEFAULT NULL,
  `order_updated` datetime DEFAULT NULL,
  `order_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  KEY `country_id` (`country_id`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'pages'
CREATE TABLE `pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) unsigned DEFAULT NULL,
  `page_url` text DEFAULT NULL,
  `page_title` text DEFAULT NULL,
  `page_content` text DEFAULT NULL,
  `page_status` tinyint(1) DEFAULT NULL,
  `page_insert` datetime DEFAULT NULL,
  `page_update` datetime DEFAULT NULL,
  `page_created` datetime DEFAULT NULL,
  `page_updated` datetime DEFAULT NULL,
  `page_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'payments'
CREATE TABLE `payments` (
  `payment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `payment_amount` int(11) DEFAULT NULL,
  `payment_mode` varchar(16) DEFAULT NULL,
  `payment_provider_id` varchar(256) DEFAULT NULL,
  `payment_url` varchar(1024) DEFAULT NULL,
  `payment_created` datetime DEFAULT NULL,
  `payment_executed` datetime DEFAULT NULL,
  `payment_updated` datetime DEFAULT NULL,
  `payment_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'people'
CREATE TABLE `people` (
  `people_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `people_first_name` varchar(256) DEFAULT NULL,
  `people_last_name` varchar(256) DEFAULT NULL,
  `people_name` varchar(255) DEFAULT NULL,
  `people_alpha` varchar(256) DEFAULT NULL,
  `people_url_old` varchar(128) DEFAULT NULL,
  `people_url` varchar(128) DEFAULT NULL,
  `people_pseudo` int(10) unsigned DEFAULT NULL,
  `people_noosfere_id` int(11) DEFAULT NULL,
  `people_birth` year(4) DEFAULT NULL,
  `people_death` year(4) DEFAULT NULL,
  `people_gender` set('M','F') DEFAULT NULL,
  `people_nation` tinytext,
  `people_bio` text,
  `people_site` varchar(255) DEFAULT NULL,
  `people_facebook` varchar(256) DEFAULT NULL,
  `people_twitter` varchar(256) DEFAULT NULL,
  `people_hits` int(10) unsigned DEFAULT NULL,
  `people_date` datetime DEFAULT NULL,
  `people_insert` datetime DEFAULT NULL,
  `people_update` datetime DEFAULT NULL,
  `people_created` datetime DEFAULT NULL,
  `people_updated` datetime DEFAULT NULL,
  `people_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`people_id`),
  KEY `people_name` (`people_name`)
) ENGINE=InnoDB AUTO_INCREMENT=36012 DEFAULT CHARSET=latin1 COMMENT='Intervenants';

-- Create syntax for TABLE 'permissions'
CREATE TABLE `permissions` (
  `permission_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `site_id` int(11) DEFAULT NULL,
  `permission_rank` varchar(8) DEFAULT NULL,
  `permission_last` datetime DEFAULT NULL,
  `permission_date` datetime DEFAULT NULL,
  PRIMARY KEY (`permission_id`),
  UNIQUE KEY `user_id` (`user_id`,`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'posts'
CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `site_id` int(11) unsigned DEFAULT NULL,
  `publisher_id` int(10) unsigned DEFAULT NULL,
  `category_id` int(10) unsigned DEFAULT NULL,
  `post_url` text CHARACTER SET latin1 DEFAULT NULL,
  `post_title` text CHARACTER SET latin1 DEFAULT NULL,
  `post_content` text CHARACTER SET latin1 DEFAULT NULL,
  `post_illustration_legend` varchar(64) CHARACTER SET latin1 DEFAULT NULL,
  `post_selected` tinyint(1) DEFAULT NULL,
  `post_link` text CHARACTER SET latin1 DEFAULT NULL,
  `post_status` tinyint(1) DEFAULT NULL,
  `post_keywords` varchar(512) CHARACTER SET latin1 DEFAULT NULL,
  `post_links` varchar(512) CHARACTER SET latin1 DEFAULT NULL,
  `post_keywords_generated` datetime DEFAULT NULL,
  `post_fb_id` bigint(20) DEFAULT NULL,
  `post_date` datetime DEFAULT NULL,
  `post_hits` int(10) unsigned DEFAULT NULL,
  `post_insert` datetime DEFAULT NULL,
  `post_update` datetime DEFAULT NULL,
  `post_created` datetime DEFAULT NULL,
  `post_updated` date DEFAULT NULL,
  `post_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'prices'
CREATE TABLE `prices` (
  `price_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pricegrid_id` int(10) unsigned DEFAULT NULL,
  `price_cat` text DEFAULT NULL,
  `price_amount` int(10) unsigned DEFAULT NULL,
  `price_created` datetime DEFAULT NULL,
  `price_updated` datetime DEFAULT NULL,
  `price_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`price_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'publishers'
CREATE TABLE `publishers` (
  `publisher_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned DEFAULT NULL,
  `publisher_name` varchar(255) DEFAULT NULL,
  `publisher_name_alphabetic` varchar(256) DEFAULT NULL,
  `publisher_url` varchar(256) DEFAULT NULL,
  `publisher_noosfere_id` int(11) DEFAULT NULL,
  `publisher_representative` varchar(256) DEFAULT NULL,
  `publisher_address` text,
  `publisher_postal_code` varchar(8) DEFAULT NULL,
  `publisher_city` varchar(128) DEFAULT NULL,
  `publisher_country` varchar(128) DEFAULT NULL,
  `publisher_phone` varchar(16) DEFAULT NULL,
  `publisher_fax` varchar(16) DEFAULT NULL,
  `publisher_website` varchar(128) DEFAULT NULL,
  `publisher_buy_link` varchar(256) DEFAULT NULL,
  `publisher_email` varchar(128) DEFAULT NULL,
  `publisher_facebook` varchar(128) DEFAULT NULL,
  `publisher_twitter` varchar(15) DEFAULT NULL,
  `publisher_legal_form` varchar(128) DEFAULT NULL,
  `publisher_creation_year` varchar(4) DEFAULT NULL,
  `publisher_isbn` varchar(13) DEFAULT NULL,
  `publisher_volumes` int(11) DEFAULT NULL,
  `publisher_average_run` int(11) DEFAULT NULL,
  `publisher_specialities` text,
  `publisher_diffuseur` varchar(128) DEFAULT NULL,
  `publisher_distributeur` varchar(128) DEFAULT NULL,
  `publisher_vpc` tinyint(1) DEFAULT NULL DEFAULT '0',
  `publisher_paypal` varchar(128) DEFAULT NULL,
  `publisher_shipping_mode` varchar(9) DEFAULT NULL DEFAULT 'offerts',
  `publisher_shipping_fee` int(10) unsigned DEFAULT NULL,
  `publisher_gln` bigint(13) unsigned DEFAULT NULL,
  `publisher_desc` text,
  `publisher_desc_short` varchar(512) DEFAULT NULL,
  `publisher_order_by` varchar(128) DEFAULT NULL DEFAULT 'article_pubdate',
  `publisher_insert` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `publisher_update` datetime DEFAULT NULL,
  `publisher_created` datetime DEFAULT NULL,
  `publisher_updated` datetime DEFAULT NULL,
  `publisher_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`publisher_id`),
  UNIQUE KEY `publisher_gln` (`publisher_gln`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'rayons'
CREATE TABLE `rayons` (
  `rayon_id` bigint(4) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` tinyint(3) unsigned DEFAULT NULL,
  `rayon_name` text DEFAULT NULL,
  `rayon_url` text DEFAULT NULL,
  `rayon_desc` text DEFAULT NULL,
  `rayon_order` tinyint(2) DEFAULT NULL,
  `rayon_sort_by` varchar(64) DEFAULT 'id',
  `rayon_sort_order` tinyint(1) DEFAULT '0',
  `rayon_show_upcoming` tinyint(1) unsigned DEFAULT NULL DEFAULT '0',
  `rayon_created` datetime DEFAULT NULL,
  `rayon_updated` datetime DEFAULT NULL,
  `rayon_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`rayon_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'redirections'
CREATE TABLE `redirections` (
  `redirection_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned DEFAULT NULL,
  `redirection_old` varchar(256) CHARACTER SET latin1 DEFAULT NULL,
  `redirection_new` varchar(256) CHARACTER SET latin1 DEFAULT NULL,
  `redirection_hits` int(11) DEFAULT NULL DEFAULT '0',
  `redirection_date` datetime DEFAULT NULL,
  `redirection_created` datetime DEFAULT NULL,
  `redirection_updated` datetime DEFAULT NULL,
  `redirection_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`redirection_id`),
  UNIQUE KEY `redirection_old` (`redirection_old`),
  UNIQUE KEY `site_id` (`site_id`,`redirection_old`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'rights'
CREATE TABLE `rights` (
  `right_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `right_uid` varchar(32) DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `site_id` int(10) unsigned DEFAULT NULL,
  `publisher_id` int(10) unsigned DEFAULT NULL,
  `bookshop_id` int(10) unsigned DEFAULT NULL,
  `library_id` int(10) unsigned DEFAULT NULL,
  `right_current` tinyint(1) DEFAULT NULL DEFAULT '0',
  `right_created` datetime DEFAULT NULL,
  `right_updated` datetime DEFAULT NULL,
  `right_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`right_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'roles'
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(10) unsigned DEFAULT NULL,
  `book_id` int(10) unsigned DEFAULT NULL,
  `event_id` int(10) unsigned DEFAULT NULL,
  `people_id` int(10) unsigned DEFAULT NULL,
  `job_id` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `role_hide` tinyint(1) unsigned DEFAULT NULL,
  `role_presence` varchar(256) DEFAULT NULL,
  `role_date` datetime DEFAULT NULL,
  `role_created` datetime DEFAULT NULL,
  `role_updated` datetime DEFAULT NULL,
  `role_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_id` (`event_id`,`people_id`,`job_id`),
  KEY `book_id` (`book_id`),
  KEY `people_id` (`people_id`),
  KEY `job_id` (`job_id`),
  KEY `article_id` (`article_id`),
  KEY `book_id_2` (`book_id`,`people_id`,`job_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'session'
CREATE TABLE `session` (
  `session_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `session_token` varchar(32) DEFAULT NULL,
  `session_created` datetime DEFAULT NULL,
  `session_expires` datetime DEFAULT NULL,
  `session_updated` datetime DEFAULT NULL,
  `session_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'shipping'
CREATE TABLE `shipping` (
  `shipping_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(11) DEFAULT NULL,
  `article_id` int(10) unsigned DEFAULT NULL,
  `shipping_mode` varchar(64) CHARACTER SET utf8mb4 DEFAULT NULL,
  `shipping_type` varchar(16) CHARACTER SET latin1 DEFAULT NULL,
  `shipping_zone` varchar(4) CHARACTER SET latin1 DEFAULT NULL,
  `shipping_min_weight` int(11) DEFAULT NULL,
  `shipping_max_weight` int(11) unsigned DEFAULT NULL,
  `shipping_max_articles` int(10) unsigned DEFAULT NULL,
  `shipping_min_amount` int(11) DEFAULT NULL,
  `shipping_max_amount` int(11) unsigned DEFAULT NULL,
  `shipping_fee` int(11) DEFAULT NULL,
  `shipping_info` varchar(512) DEFAULT NULL,
  `shipping_created` datetime DEFAULT NULL,
  `shipping_updated` datetime DEFAULT NULL,
  `shipping_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`shipping_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'signings'
CREATE TABLE `signings` (
  `signing_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(11) unsigned DEFAULT NULL,
  `publisher_id` int(10) unsigned DEFAULT NULL,
  `people_id` int(11) unsigned DEFAULT NULL,
  `signing_date` date DEFAULT NULL,
  `signing_starts` time DEFAULT NULL,
  `signing_ends` time DEFAULT NULL,
  `signing_location` varchar(255) DEFAULT NULL,
  `signing_created` datetime DEFAULT NULL,
  `signing_updated` datetime DEFAULT NULL,
  `signing_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`signing_id`),
  KEY `site_id` (`site_id`),
  KEY `people_id` (`people_id`),
  KEY `publisher_id` (`publisher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'sites'
CREATE TABLE `sites` (
  `site_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_name` varchar(16) DEFAULT NULL DEFAULT '',
  `site_pass` varchar(8) DEFAULT NULL DEFAULT '',
  `site_title` varchar(32) DEFAULT NULL DEFAULT '',
  `site_domain` varchar(255) DEFAULT NULL,
  `site_version` varchar(16) DEFAULT NULL,
  `site_tag` varchar(16) DEFAULT NULL DEFAULT '',
  `site_flag` varchar(2) DEFAULT NULL,
  `site_contact` varchar(128) DEFAULT NULL DEFAULT '',
  `site_address` varchar(256) DEFAULT NULL DEFAULT '',
  `site_tva` varchar(2) DEFAULT NULL,
  `site_html_renderer` tinyint(1) DEFAULT NULL,
  `site_axys` tinyint(1) DEFAULT NULL DEFAULT '1',
  `site_noosfere` tinyint(1) DEFAULT NULL,
  `site_amazon` tinyint(1) DEFAULT NULL,
  `site_event_id` int(10) unsigned DEFAULT NULL,
  `site_event_date` int(11) unsigned DEFAULT NULL,
  `site_shop` tinyint(1) DEFAULT NULL,
  `site_vpc` tinyint(1) DEFAULT NULL,
  `site_shipping_fee` varchar(8) DEFAULT NULL,
  `site_alerts` tinyint(1) DEFAULT NULL,
  `site_wishlist` tinyint(1) DEFAULT NULL DEFAULT '0',
  `site_payment_cheque` tinyint(1) DEFAULT NULL DEFAULT '1',
  `site_payment_paypal` varchar(32) DEFAULT NULL,
  `site_payment_payplug` tinyint(1) DEFAULT NULL DEFAULT '0',
  `site_payment_transfer` tinyint(1) DEFAULT NULL,
  `site_bookshop` tinyint(1) DEFAULT NULL DEFAULT '0',
  `site_bookshop_id` int(10) unsigned DEFAULT NULL,
  `site_publisher` tinyint(1) DEFAULT NULL,
  `site_publisher_stock` tinyint(1) DEFAULT NULL DEFAULT '0',
  `publisher_id` int(10) unsigned DEFAULT NULL,
  `site_ebook_bundle` int(11) DEFAULT NULL,
  `site_fb_page_id` bigint(20) unsigned DEFAULT NULL,
  `site_fb_page_token` text,
  `site_analytics_id` varchar(16) DEFAULT NULL,
  `site_piwik_id` int(11) DEFAULT NULL,
  `site_sitemap_updated` datetime DEFAULT NULL,
  `site_monitoring` tinyint(1) DEFAULT NULL DEFAULT '1',
  `site_created` datetime DEFAULT NULL,
  `site_updated` datetime DEFAULT NULL,
  `site_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`site_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'stock'
CREATE TABLE `stock` (
  `stock_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` tinyint(4) unsigned DEFAULT NULL,
  `article_id` int(10) unsigned DEFAULT NULL,
  `campaign_id` int(10) unsigned DEFAULT NULL,
  `reward_id` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) DEFAULT NULL,
  `customer_id` int(10) unsigned DEFAULT NULL,
  `wish_id` int(10) unsigned DEFAULT NULL,
  `cart_id` int(10) unsigned DEFAULT NULL,
  `order_id` int(10) unsigned DEFAULT NULL,
  `coupon_id` int(10) unsigned DEFAULT NULL,
  `stock_shop` int(10) unsigned DEFAULT NULL,
  `stock_invoice` varchar(256) CHARACTER SET latin1 DEFAULT NULL,
  `stock_depot` tinyint(1) DEFAULT NULL DEFAULT '0',
  `stock_stockage` varchar(16) CHARACTER SET latin1 DEFAULT NULL,
  `stock_condition` varchar(16) CHARACTER SET latin1 DEFAULT NULL,
  `stock_condition_details` text CHARACTER SET latin1,
  `stock_purchase_price` int(10) unsigned DEFAULT NULL,
  `stock_selling_price` int(10) unsigned DEFAULT NULL,
  `stock_selling_price2` int(10) unsigned DEFAULT NULL,
  `stock_selling_price_saved` int(10) unsigned DEFAULT NULL,
  `stock_selling_price_ht` int(10) unsigned DEFAULT NULL,
  `stock_selling_price_tva` int(10) unsigned DEFAULT NULL,
  `stock_tva_rate` float DEFAULT NULL,
  `stock_weight` int(10) unsigned DEFAULT NULL,
  `stock_pub_year` int(4) unsigned DEFAULT NULL,
  `stock_allow_predownload` tinyint(1) DEFAULT NULL DEFAULT '0',
  `stock_photo_version` int(10) unsigned DEFAULT '0',
  `stock_purchase_date` datetime DEFAULT NULL,
  `stock_onsale_date` datetime DEFAULT NULL,
  `stock_cart_date` datetime DEFAULT NULL,
  `stock_selling_date` datetime DEFAULT NULL,
  `stock_return_date` datetime DEFAULT NULL,
  `stock_lost_date` datetime DEFAULT NULL,
  `stock_media_ok` tinyint(1) DEFAULT NULL DEFAULT '0',
  `stock_file_updated` tinyint(1) DEFAULT NULL DEFAULT '0',
  `stock_insert` datetime DEFAULT NULL,
  `stock_update` datetime DEFAULT NULL,
  `stock_dl` tinyint(1) DEFAULT NULL DEFAULT '0',
  `stock_created` datetime DEFAULT NULL,
  `stock_updated` datetime DEFAULT NULL,
  `stock_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`stock_id`),
  UNIQUE KEY `coupon_id` (`coupon_id`),
  KEY `site_id` (`site_id`),
  KEY `cart_id` (`cart_id`),
  KEY `article_id` (`article_id`),
  KEY `stock_return_date` (`stock_return_date`),
  KEY `stock_shop` (`stock_shop`),
  KEY `order_id` (`order_id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'subscriptions'
CREATE TABLE `subscriptions` (
  `subscription_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `publisher_id` int(10) unsigned DEFAULT NULL,
  `bookshop_id` int(10) unsigned DEFAULT NULL,
  `library_id` int(10) unsigned DEFAULT NULL,
  `subscription_type` varchar(16) DEFAULT NULL,
  `subscription_email` varchar(256) DEFAULT NULL,
  `subscription_ends` smallint(16) DEFAULT NULL,
  `subscription_option` tinyint(1) DEFAULT NULL DEFAULT '0',
  `subscription_insert` datetime DEFAULT NULL,
  `subscription_update` datetime DEFAULT NULL,
  `subscription_created` datetime DEFAULT NULL,
  `subscription_updated` datetime DEFAULT NULL,
  `subscription_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`subscription_id`),
  KEY `publisher_id` (`publisher_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'suppliers'
CREATE TABLE `suppliers` (
  `supplier_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` tinyint(3) unsigned DEFAULT NULL DEFAULT '1',
  `supplier_name` varchar(256) DEFAULT NULL,
  `supplier_gln` bigint(20) DEFAULT NULL,
  `supplier_remise` int(10) unsigned DEFAULT NULL,
  `supplier_notva` tinyint(1) DEFAULT NULL,
  `supplier_on_order` tinyint(1) DEFAULT NULL,
  `supplier_insert` datetime DEFAULT NULL,
  `supplier_update` datetime DEFAULT NULL,
  `supplier_created` datetime DEFAULT NULL,
  `supplier_updated` datetime DEFAULT NULL,
  `supplier_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`supplier_id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'tags'
CREATE TABLE `tags` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_name` text DEFAULT NULL,
  `tag_url` text DEFAULT NULL,
  `tag_description` text DEFAULT NULL,
  `tag_date` datetime DEFAULT NULL,
  `tag_num` int(10) unsigned DEFAULT NULL,
  `tag_insert` datetime DEFAULT NULL,
  `tag_update` datetime DEFAULT NULL,
  `tag_created` datetime DEFAULT NULL,
  `tag_updated` datetime DEFAULT NULL,
  `tag_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`tag_id`),
  KEY `tag_num` (`tag_num`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'ticket'
CREATE TABLE `ticket` (
  `ticket_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `site_id` int(11) DEFAULT NULL,
  `ticket_type` varchar(16) DEFAULT NULL DEFAULT '',
  `ticket_title` varchar(255) DEFAULT NULL,
  `ticket_content` text,
  `ticket_priority` int(11) DEFAULT NULL DEFAULT '0',
  `ticket_created` datetime DEFAULT NULL,
  `ticket_updated` datetime DEFAULT NULL,
  `ticket_resolved` datetime DEFAULT NULL,
  `ticket_closed` datetime DEFAULT NULL,
  `ticket_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`ticket_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'ticket_comment'
CREATE TABLE `ticket_comment` (
  `ticket_comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ticket_comment_content` text,
  `ticket_comment_created` datetime DEFAULT NULL,
  `ticket_comment_update` datetime DEFAULT NULL,
  `ticket_comment_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`ticket_comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'users'
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Email` varchar(255) DEFAULT NULL,
  `user_password` varchar(255) DEFAULT NULL,
  `user_key` text,
  `email_key` varchar(32) DEFAULT NULL,
  `facebook_uid` int(11) DEFAULT NULL,
  `user_screen_name` varchar(128) DEFAULT NULL,
  `user_slug` varchar(32) DEFAULT NULL,
  `user_wishlist_ship` tinyint(1) DEFAULT NULL DEFAULT '0',
  `user_top` tinyint(1) DEFAULT NULL,
  `user_biblio` tinyint(1) DEFAULT NULL,
  `adresse_ip` tinytext,
  `recaptcha_score` float DEFAULT NULL,
  `DateInscription` datetime DEFAULT NULL,
  `DateConnexion` datetime DEFAULT NULL,
  `publisher_id` int(10) unsigned DEFAULT NULL,
  `bookshop_id` int(10) unsigned DEFAULT NULL,
  `library_id` int(10) unsigned DEFAULT NULL,
  `user_civilite` text,
  `user_nom` text,
  `user_prenom` text,
  `user_adresse1` text,
  `user_adresse2` text,
  `user_codepostal` text,
  `user_ville` text,
  `user_pays` text,
  `user_telephone` text,
  `user_pref_articles_show` varchar(8) DEFAULT NULL,
  `user_fb_id` bigint(20) DEFAULT NULL,
  `user_fb_token` varchar(256) DEFAULT NULL,
  `country_id` int(10) unsigned DEFAULT NULL,
  `user_password_reset_token` varchar(32) DEFAULT NULL,
  `user_password_reset_token_created` datetime DEFAULT NULL,
  `user_update` datetime DEFAULT NULL,
  `user_created` datetime DEFAULT NULL,
  `user_updated` datetime DEFAULT NULL,
  `user_deleted` datetime DEFAULT NULL,
  `user_deleted_why` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_screen_name` (`user_screen_name`),
  UNIQUE KEY `user_slug` (`user_slug`),
  KEY `publisher_id` (`publisher_id`),
  KEY `Email` (`Email`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'votes'
CREATE TABLE `votes` (
  `vote_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `vote_F` int(10) unsigned DEFAULT NULL,
  `vote_E` int(10) unsigned DEFAULT NULL,
  `vote_date` datetime DEFAULT NULL,
  PRIMARY KEY (`vote_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Create syntax for TABLE 'wishes'
CREATE TABLE `wishes` (
  `wish_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `wishlist_id` int(11) DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `site_id` int(10) unsigned DEFAULT NULL,
  `article_id` int(10) unsigned DEFAULT NULL,
  `wish_created` datetime DEFAULT NULL,
  `wish_updated` datetime DEFAULT NULL,
  `wish_bought` datetime DEFAULT NULL,
  `wish_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`wish_id`),
  UNIQUE KEY `user_id` (`user_id`,`article_id`,`wish_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'wishlist'
CREATE TABLE `wishlist` (
  `wishlist_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `wishlist_name` varchar(128) DEFAULT NULL,
  `wishlist_current` tinyint(1) DEFAULT NULL,
  `wishlist_public` tinyint(1) DEFAULT NULL,
  `wishlist_created` datetime DEFAULT NULL,
  `wishlist_updated` datetime DEFAULT NULL,
  `wishlist_deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`wishlist_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

COMMIT;
