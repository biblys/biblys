<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Article as ChildArticle;
use Model\ArticleQuery as ChildArticleQuery;
use Model\Map\ArticleTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'articles' table.
 *
 *
 *
 * @method     ChildArticleQuery orderById($order = Criteria::ASC) Order by the article_id column
 * @method     ChildArticleQuery orderByItem($order = Criteria::ASC) Order by the article_item column
 * @method     ChildArticleQuery orderByTextid($order = Criteria::ASC) Order by the article_textid column
 * @method     ChildArticleQuery orderByEan($order = Criteria::ASC) Order by the article_ean column
 * @method     ChildArticleQuery orderByEanOthers($order = Criteria::ASC) Order by the article_ean_others column
 * @method     ChildArticleQuery orderByAsin($order = Criteria::ASC) Order by the article_asin column
 * @method     ChildArticleQuery orderByNoosfereId($order = Criteria::ASC) Order by the article_noosfere_id column
 * @method     ChildArticleQuery orderByUrl($order = Criteria::ASC) Order by the article_url column
 * @method     ChildArticleQuery orderByTypeId($order = Criteria::ASC) Order by the type_id column
 * @method     ChildArticleQuery orderByTitle($order = Criteria::ASC) Order by the article_title column
 * @method     ChildArticleQuery orderByTitleAlphabetic($order = Criteria::ASC) Order by the article_title_alphabetic column
 * @method     ChildArticleQuery orderByTitleOriginal($order = Criteria::ASC) Order by the article_title_original column
 * @method     ChildArticleQuery orderByTitleOthers($order = Criteria::ASC) Order by the article_title_others column
 * @method     ChildArticleQuery orderBySubtitle($order = Criteria::ASC) Order by the article_subtitle column
 * @method     ChildArticleQuery orderByLangCurrent($order = Criteria::ASC) Order by the article_lang_current column
 * @method     ChildArticleQuery orderByLangOriginal($order = Criteria::ASC) Order by the article_lang_original column
 * @method     ChildArticleQuery orderByOriginCountry($order = Criteria::ASC) Order by the article_origin_country column
 * @method     ChildArticleQuery orderByThemeBisac($order = Criteria::ASC) Order by the article_theme_bisac column
 * @method     ChildArticleQuery orderByThemeClil($order = Criteria::ASC) Order by the article_theme_clil column
 * @method     ChildArticleQuery orderByThemeDewey($order = Criteria::ASC) Order by the article_theme_dewey column
 * @method     ChildArticleQuery orderByThemeElectre($order = Criteria::ASC) Order by the article_theme_electre column
 * @method     ChildArticleQuery orderBySourceId($order = Criteria::ASC) Order by the article_source_id column
 * @method     ChildArticleQuery orderByAuthors($order = Criteria::ASC) Order by the article_authors column
 * @method     ChildArticleQuery orderByAuthorsAlphabetic($order = Criteria::ASC) Order by the article_authors_alphabetic column
 * @method     ChildArticleQuery orderByCollectionId($order = Criteria::ASC) Order by the collection_id column
 * @method     ChildArticleQuery orderByCollection($order = Criteria::ASC) Order by the article_collection column
 * @method     ChildArticleQuery orderByNumber($order = Criteria::ASC) Order by the article_number column
 * @method     ChildArticleQuery orderByPublisherId($order = Criteria::ASC) Order by the publisher_id column
 * @method     ChildArticleQuery orderByPublisher($order = Criteria::ASC) Order by the article_publisher column
 * @method     ChildArticleQuery orderByCycleId($order = Criteria::ASC) Order by the cycle_id column
 * @method     ChildArticleQuery orderByCycle($order = Criteria::ASC) Order by the article_cycle column
 * @method     ChildArticleQuery orderByTome($order = Criteria::ASC) Order by the article_tome column
 * @method     ChildArticleQuery orderByCoverVersion($order = Criteria::ASC) Order by the article_cover_version column
 * @method     ChildArticleQuery orderByAvailability($order = Criteria::ASC) Order by the article_availability column
 * @method     ChildArticleQuery orderByAvailabilityDilicom($order = Criteria::ASC) Order by the article_availability_dilicom column
 * @method     ChildArticleQuery orderByPreorder($order = Criteria::ASC) Order by the article_preorder column
 * @method     ChildArticleQuery orderByPrice($order = Criteria::ASC) Order by the article_price column
 * @method     ChildArticleQuery orderByPriceEditable($order = Criteria::ASC) Order by the article_price_editable column
 * @method     ChildArticleQuery orderByNewPrice($order = Criteria::ASC) Order by the article_new_price column
 * @method     ChildArticleQuery orderByCategory($order = Criteria::ASC) Order by the article_category column
 * @method     ChildArticleQuery orderByTva($order = Criteria::ASC) Order by the article_tva column
 * @method     ChildArticleQuery orderByPdfEan($order = Criteria::ASC) Order by the article_pdf_ean column
 * @method     ChildArticleQuery orderByPdfVersion($order = Criteria::ASC) Order by the article_pdf_version column
 * @method     ChildArticleQuery orderByEpubEan($order = Criteria::ASC) Order by the article_epub_ean column
 * @method     ChildArticleQuery orderByEpubVersion($order = Criteria::ASC) Order by the article_epub_version column
 * @method     ChildArticleQuery orderByAzwEan($order = Criteria::ASC) Order by the article_azw_ean column
 * @method     ChildArticleQuery orderByAzwVersion($order = Criteria::ASC) Order by the article_azw_version column
 * @method     ChildArticleQuery orderByPages($order = Criteria::ASC) Order by the article_pages column
 * @method     ChildArticleQuery orderByWeight($order = Criteria::ASC) Order by the article_weight column
 * @method     ChildArticleQuery orderByShaping($order = Criteria::ASC) Order by the article_shaping column
 * @method     ChildArticleQuery orderByFormat($order = Criteria::ASC) Order by the article_format column
 * @method     ChildArticleQuery orderByPrintingProcess($order = Criteria::ASC) Order by the article_printing_process column
 * @method     ChildArticleQuery orderByAgeMin($order = Criteria::ASC) Order by the article_age_min column
 * @method     ChildArticleQuery orderByAgeMax($order = Criteria::ASC) Order by the article_age_max column
 * @method     ChildArticleQuery orderBySummary($order = Criteria::ASC) Order by the article_summary column
 * @method     ChildArticleQuery orderByContents($order = Criteria::ASC) Order by the article_contents column
 * @method     ChildArticleQuery orderByBonus($order = Criteria::ASC) Order by the article_bonus column
 * @method     ChildArticleQuery orderByCatchline($order = Criteria::ASC) Order by the article_catchline column
 * @method     ChildArticleQuery orderByBiography($order = Criteria::ASC) Order by the article_biography column
 * @method     ChildArticleQuery orderByMotsv($order = Criteria::ASC) Order by the article_motsv column
 * @method     ChildArticleQuery orderByCopyright($order = Criteria::ASC) Order by the article_copyright column
 * @method     ChildArticleQuery orderByPubdate($order = Criteria::ASC) Order by the article_pubdate column
 * @method     ChildArticleQuery orderByKeywords($order = Criteria::ASC) Order by the article_keywords column
 * @method     ChildArticleQuery orderByLinks($order = Criteria::ASC) Order by the article_links column
 * @method     ChildArticleQuery orderByKeywordsGenerated($order = Criteria::ASC) Order by the article_keywords_generated column
 * @method     ChildArticleQuery orderByPublisherStock($order = Criteria::ASC) Order by the article_publisher_stock column
 * @method     ChildArticleQuery orderByHits($order = Criteria::ASC) Order by the article_hits column
 * @method     ChildArticleQuery orderByEditingUser($order = Criteria::ASC) Order by the article_editing_user column
 * @method     ChildArticleQuery orderByInsert($order = Criteria::ASC) Order by the article_insert column
 * @method     ChildArticleQuery orderByUpdate($order = Criteria::ASC) Order by the article_update column
 * @method     ChildArticleQuery orderByCreatedAt($order = Criteria::ASC) Order by the article_created column
 * @method     ChildArticleQuery orderByUpdatedAt($order = Criteria::ASC) Order by the article_updated column
 * @method     ChildArticleQuery orderByDeletedAt($order = Criteria::ASC) Order by the article_deleted column
 * @method     ChildArticleQuery orderByDone($order = Criteria::ASC) Order by the article_done column
 * @method     ChildArticleQuery orderByToCheck($order = Criteria::ASC) Order by the article_to_check column
 * @method     ChildArticleQuery orderByPushedToData($order = Criteria::ASC) Order by the article_pushed_to_data column
 * @method     ChildArticleQuery orderByDeletionBy($order = Criteria::ASC) Order by the article_deletion_by column
 * @method     ChildArticleQuery orderByDeletionDate($order = Criteria::ASC) Order by the article_deletion_date column
 * @method     ChildArticleQuery orderByDeletionReason($order = Criteria::ASC) Order by the article_deletion_reason column
 *
 * @method     ChildArticleQuery groupById() Group by the article_id column
 * @method     ChildArticleQuery groupByItem() Group by the article_item column
 * @method     ChildArticleQuery groupByTextid() Group by the article_textid column
 * @method     ChildArticleQuery groupByEan() Group by the article_ean column
 * @method     ChildArticleQuery groupByEanOthers() Group by the article_ean_others column
 * @method     ChildArticleQuery groupByAsin() Group by the article_asin column
 * @method     ChildArticleQuery groupByNoosfereId() Group by the article_noosfere_id column
 * @method     ChildArticleQuery groupByUrl() Group by the article_url column
 * @method     ChildArticleQuery groupByTypeId() Group by the type_id column
 * @method     ChildArticleQuery groupByTitle() Group by the article_title column
 * @method     ChildArticleQuery groupByTitleAlphabetic() Group by the article_title_alphabetic column
 * @method     ChildArticleQuery groupByTitleOriginal() Group by the article_title_original column
 * @method     ChildArticleQuery groupByTitleOthers() Group by the article_title_others column
 * @method     ChildArticleQuery groupBySubtitle() Group by the article_subtitle column
 * @method     ChildArticleQuery groupByLangCurrent() Group by the article_lang_current column
 * @method     ChildArticleQuery groupByLangOriginal() Group by the article_lang_original column
 * @method     ChildArticleQuery groupByOriginCountry() Group by the article_origin_country column
 * @method     ChildArticleQuery groupByThemeBisac() Group by the article_theme_bisac column
 * @method     ChildArticleQuery groupByThemeClil() Group by the article_theme_clil column
 * @method     ChildArticleQuery groupByThemeDewey() Group by the article_theme_dewey column
 * @method     ChildArticleQuery groupByThemeElectre() Group by the article_theme_electre column
 * @method     ChildArticleQuery groupBySourceId() Group by the article_source_id column
 * @method     ChildArticleQuery groupByAuthors() Group by the article_authors column
 * @method     ChildArticleQuery groupByAuthorsAlphabetic() Group by the article_authors_alphabetic column
 * @method     ChildArticleQuery groupByCollectionId() Group by the collection_id column
 * @method     ChildArticleQuery groupByCollection() Group by the article_collection column
 * @method     ChildArticleQuery groupByNumber() Group by the article_number column
 * @method     ChildArticleQuery groupByPublisherId() Group by the publisher_id column
 * @method     ChildArticleQuery groupByPublisher() Group by the article_publisher column
 * @method     ChildArticleQuery groupByCycleId() Group by the cycle_id column
 * @method     ChildArticleQuery groupByCycle() Group by the article_cycle column
 * @method     ChildArticleQuery groupByTome() Group by the article_tome column
 * @method     ChildArticleQuery groupByCoverVersion() Group by the article_cover_version column
 * @method     ChildArticleQuery groupByAvailability() Group by the article_availability column
 * @method     ChildArticleQuery groupByAvailabilityDilicom() Group by the article_availability_dilicom column
 * @method     ChildArticleQuery groupByPreorder() Group by the article_preorder column
 * @method     ChildArticleQuery groupByPrice() Group by the article_price column
 * @method     ChildArticleQuery groupByPriceEditable() Group by the article_price_editable column
 * @method     ChildArticleQuery groupByNewPrice() Group by the article_new_price column
 * @method     ChildArticleQuery groupByCategory() Group by the article_category column
 * @method     ChildArticleQuery groupByTva() Group by the article_tva column
 * @method     ChildArticleQuery groupByPdfEan() Group by the article_pdf_ean column
 * @method     ChildArticleQuery groupByPdfVersion() Group by the article_pdf_version column
 * @method     ChildArticleQuery groupByEpubEan() Group by the article_epub_ean column
 * @method     ChildArticleQuery groupByEpubVersion() Group by the article_epub_version column
 * @method     ChildArticleQuery groupByAzwEan() Group by the article_azw_ean column
 * @method     ChildArticleQuery groupByAzwVersion() Group by the article_azw_version column
 * @method     ChildArticleQuery groupByPages() Group by the article_pages column
 * @method     ChildArticleQuery groupByWeight() Group by the article_weight column
 * @method     ChildArticleQuery groupByShaping() Group by the article_shaping column
 * @method     ChildArticleQuery groupByFormat() Group by the article_format column
 * @method     ChildArticleQuery groupByPrintingProcess() Group by the article_printing_process column
 * @method     ChildArticleQuery groupByAgeMin() Group by the article_age_min column
 * @method     ChildArticleQuery groupByAgeMax() Group by the article_age_max column
 * @method     ChildArticleQuery groupBySummary() Group by the article_summary column
 * @method     ChildArticleQuery groupByContents() Group by the article_contents column
 * @method     ChildArticleQuery groupByBonus() Group by the article_bonus column
 * @method     ChildArticleQuery groupByCatchline() Group by the article_catchline column
 * @method     ChildArticleQuery groupByBiography() Group by the article_biography column
 * @method     ChildArticleQuery groupByMotsv() Group by the article_motsv column
 * @method     ChildArticleQuery groupByCopyright() Group by the article_copyright column
 * @method     ChildArticleQuery groupByPubdate() Group by the article_pubdate column
 * @method     ChildArticleQuery groupByKeywords() Group by the article_keywords column
 * @method     ChildArticleQuery groupByLinks() Group by the article_links column
 * @method     ChildArticleQuery groupByKeywordsGenerated() Group by the article_keywords_generated column
 * @method     ChildArticleQuery groupByPublisherStock() Group by the article_publisher_stock column
 * @method     ChildArticleQuery groupByHits() Group by the article_hits column
 * @method     ChildArticleQuery groupByEditingUser() Group by the article_editing_user column
 * @method     ChildArticleQuery groupByInsert() Group by the article_insert column
 * @method     ChildArticleQuery groupByUpdate() Group by the article_update column
 * @method     ChildArticleQuery groupByCreatedAt() Group by the article_created column
 * @method     ChildArticleQuery groupByUpdatedAt() Group by the article_updated column
 * @method     ChildArticleQuery groupByDeletedAt() Group by the article_deleted column
 * @method     ChildArticleQuery groupByDone() Group by the article_done column
 * @method     ChildArticleQuery groupByToCheck() Group by the article_to_check column
 * @method     ChildArticleQuery groupByPushedToData() Group by the article_pushed_to_data column
 * @method     ChildArticleQuery groupByDeletionBy() Group by the article_deletion_by column
 * @method     ChildArticleQuery groupByDeletionDate() Group by the article_deletion_date column
 * @method     ChildArticleQuery groupByDeletionReason() Group by the article_deletion_reason column
 *
 * @method     ChildArticleQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildArticleQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildArticleQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildArticleQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildArticleQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildArticleQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildArticleQuery leftJoinRole($relationAlias = null) Adds a LEFT JOIN clause to the query using the Role relation
 * @method     ChildArticleQuery rightJoinRole($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Role relation
 * @method     ChildArticleQuery innerJoinRole($relationAlias = null) Adds a INNER JOIN clause to the query using the Role relation
 *
 * @method     ChildArticleQuery joinWithRole($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Role relation
 *
 * @method     ChildArticleQuery leftJoinWithRole() Adds a LEFT JOIN clause and with to the query using the Role relation
 * @method     ChildArticleQuery rightJoinWithRole() Adds a RIGHT JOIN clause and with to the query using the Role relation
 * @method     ChildArticleQuery innerJoinWithRole() Adds a INNER JOIN clause and with to the query using the Role relation
 *
 * @method     \Model\RoleQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildArticle|null findOne(ConnectionInterface $con = null) Return the first ChildArticle matching the query
 * @method     ChildArticle findOneOrCreate(ConnectionInterface $con = null) Return the first ChildArticle matching the query, or a new ChildArticle object populated from the query conditions when no match is found
 *
 * @method     ChildArticle|null findOneById(int $article_id) Return the first ChildArticle filtered by the article_id column
 * @method     ChildArticle|null findOneByItem(int $article_item) Return the first ChildArticle filtered by the article_item column
 * @method     ChildArticle|null findOneByTextid(string $article_textid) Return the first ChildArticle filtered by the article_textid column
 * @method     ChildArticle|null findOneByEan(string $article_ean) Return the first ChildArticle filtered by the article_ean column
 * @method     ChildArticle|null findOneByEanOthers(string $article_ean_others) Return the first ChildArticle filtered by the article_ean_others column
 * @method     ChildArticle|null findOneByAsin(string $article_asin) Return the first ChildArticle filtered by the article_asin column
 * @method     ChildArticle|null findOneByNoosfereId(int $article_noosfere_id) Return the first ChildArticle filtered by the article_noosfere_id column
 * @method     ChildArticle|null findOneByUrl(string $article_url) Return the first ChildArticle filtered by the article_url column
 * @method     ChildArticle|null findOneByTypeId(int $type_id) Return the first ChildArticle filtered by the type_id column
 * @method     ChildArticle|null findOneByTitle(string $article_title) Return the first ChildArticle filtered by the article_title column
 * @method     ChildArticle|null findOneByTitleAlphabetic(string $article_title_alphabetic) Return the first ChildArticle filtered by the article_title_alphabetic column
 * @method     ChildArticle|null findOneByTitleOriginal(string $article_title_original) Return the first ChildArticle filtered by the article_title_original column
 * @method     ChildArticle|null findOneByTitleOthers(string $article_title_others) Return the first ChildArticle filtered by the article_title_others column
 * @method     ChildArticle|null findOneBySubtitle(string $article_subtitle) Return the first ChildArticle filtered by the article_subtitle column
 * @method     ChildArticle|null findOneByLangCurrent(int $article_lang_current) Return the first ChildArticle filtered by the article_lang_current column
 * @method     ChildArticle|null findOneByLangOriginal(int $article_lang_original) Return the first ChildArticle filtered by the article_lang_original column
 * @method     ChildArticle|null findOneByOriginCountry(int $article_origin_country) Return the first ChildArticle filtered by the article_origin_country column
 * @method     ChildArticle|null findOneByThemeBisac(string $article_theme_bisac) Return the first ChildArticle filtered by the article_theme_bisac column
 * @method     ChildArticle|null findOneByThemeClil(string $article_theme_clil) Return the first ChildArticle filtered by the article_theme_clil column
 * @method     ChildArticle|null findOneByThemeDewey(string $article_theme_dewey) Return the first ChildArticle filtered by the article_theme_dewey column
 * @method     ChildArticle|null findOneByThemeElectre(string $article_theme_electre) Return the first ChildArticle filtered by the article_theme_electre column
 * @method     ChildArticle|null findOneBySourceId(int $article_source_id) Return the first ChildArticle filtered by the article_source_id column
 * @method     ChildArticle|null findOneByAuthors(string $article_authors) Return the first ChildArticle filtered by the article_authors column
 * @method     ChildArticle|null findOneByAuthorsAlphabetic(string $article_authors_alphabetic) Return the first ChildArticle filtered by the article_authors_alphabetic column
 * @method     ChildArticle|null findOneByCollectionId(int $collection_id) Return the first ChildArticle filtered by the collection_id column
 * @method     ChildArticle|null findOneByCollection(string $article_collection) Return the first ChildArticle filtered by the article_collection column
 * @method     ChildArticle|null findOneByNumber(string $article_number) Return the first ChildArticle filtered by the article_number column
 * @method     ChildArticle|null findOneByPublisherId(int $publisher_id) Return the first ChildArticle filtered by the publisher_id column
 * @method     ChildArticle|null findOneByPublisher(string $article_publisher) Return the first ChildArticle filtered by the article_publisher column
 * @method     ChildArticle|null findOneByCycleId(int $cycle_id) Return the first ChildArticle filtered by the cycle_id column
 * @method     ChildArticle|null findOneByCycle(string $article_cycle) Return the first ChildArticle filtered by the article_cycle column
 * @method     ChildArticle|null findOneByTome(string $article_tome) Return the first ChildArticle filtered by the article_tome column
 * @method     ChildArticle|null findOneByCoverVersion(int $article_cover_version) Return the first ChildArticle filtered by the article_cover_version column
 * @method     ChildArticle|null findOneByAvailability(int $article_availability) Return the first ChildArticle filtered by the article_availability column
 * @method     ChildArticle|null findOneByAvailabilityDilicom(int $article_availability_dilicom) Return the first ChildArticle filtered by the article_availability_dilicom column
 * @method     ChildArticle|null findOneByPreorder(boolean $article_preorder) Return the first ChildArticle filtered by the article_preorder column
 * @method     ChildArticle|null findOneByPrice(int $article_price) Return the first ChildArticle filtered by the article_price column
 * @method     ChildArticle|null findOneByPriceEditable(boolean $article_price_editable) Return the first ChildArticle filtered by the article_price_editable column
 * @method     ChildArticle|null findOneByNewPrice(int $article_new_price) Return the first ChildArticle filtered by the article_new_price column
 * @method     ChildArticle|null findOneByCategory(string $article_category) Return the first ChildArticle filtered by the article_category column
 * @method     ChildArticle|null findOneByTva(int $article_tva) Return the first ChildArticle filtered by the article_tva column
 * @method     ChildArticle|null findOneByPdfEan(string $article_pdf_ean) Return the first ChildArticle filtered by the article_pdf_ean column
 * @method     ChildArticle|null findOneByPdfVersion(string $article_pdf_version) Return the first ChildArticle filtered by the article_pdf_version column
 * @method     ChildArticle|null findOneByEpubEan(string $article_epub_ean) Return the first ChildArticle filtered by the article_epub_ean column
 * @method     ChildArticle|null findOneByEpubVersion(string $article_epub_version) Return the first ChildArticle filtered by the article_epub_version column
 * @method     ChildArticle|null findOneByAzwEan(string $article_azw_ean) Return the first ChildArticle filtered by the article_azw_ean column
 * @method     ChildArticle|null findOneByAzwVersion(string $article_azw_version) Return the first ChildArticle filtered by the article_azw_version column
 * @method     ChildArticle|null findOneByPages(int $article_pages) Return the first ChildArticle filtered by the article_pages column
 * @method     ChildArticle|null findOneByWeight(int $article_weight) Return the first ChildArticle filtered by the article_weight column
 * @method     ChildArticle|null findOneByShaping(string $article_shaping) Return the first ChildArticle filtered by the article_shaping column
 * @method     ChildArticle|null findOneByFormat(string $article_format) Return the first ChildArticle filtered by the article_format column
 * @method     ChildArticle|null findOneByPrintingProcess(string $article_printing_process) Return the first ChildArticle filtered by the article_printing_process column
 * @method     ChildArticle|null findOneByAgeMin(int $article_age_min) Return the first ChildArticle filtered by the article_age_min column
 * @method     ChildArticle|null findOneByAgeMax(int $article_age_max) Return the first ChildArticle filtered by the article_age_max column
 * @method     ChildArticle|null findOneBySummary(string $article_summary) Return the first ChildArticle filtered by the article_summary column
 * @method     ChildArticle|null findOneByContents(string $article_contents) Return the first ChildArticle filtered by the article_contents column
 * @method     ChildArticle|null findOneByBonus(string $article_bonus) Return the first ChildArticle filtered by the article_bonus column
 * @method     ChildArticle|null findOneByCatchline(string $article_catchline) Return the first ChildArticle filtered by the article_catchline column
 * @method     ChildArticle|null findOneByBiography(string $article_biography) Return the first ChildArticle filtered by the article_biography column
 * @method     ChildArticle|null findOneByMotsv(string $article_motsv) Return the first ChildArticle filtered by the article_motsv column
 * @method     ChildArticle|null findOneByCopyright(int $article_copyright) Return the first ChildArticle filtered by the article_copyright column
 * @method     ChildArticle|null findOneByPubdate(string $article_pubdate) Return the first ChildArticle filtered by the article_pubdate column
 * @method     ChildArticle|null findOneByKeywords(string $article_keywords) Return the first ChildArticle filtered by the article_keywords column
 * @method     ChildArticle|null findOneByLinks(string $article_links) Return the first ChildArticle filtered by the article_links column
 * @method     ChildArticle|null findOneByKeywordsGenerated(string $article_keywords_generated) Return the first ChildArticle filtered by the article_keywords_generated column
 * @method     ChildArticle|null findOneByPublisherStock(int $article_publisher_stock) Return the first ChildArticle filtered by the article_publisher_stock column
 * @method     ChildArticle|null findOneByHits(int $article_hits) Return the first ChildArticle filtered by the article_hits column
 * @method     ChildArticle|null findOneByEditingUser(int $article_editing_user) Return the first ChildArticle filtered by the article_editing_user column
 * @method     ChildArticle|null findOneByInsert(string $article_insert) Return the first ChildArticle filtered by the article_insert column
 * @method     ChildArticle|null findOneByUpdate(string $article_update) Return the first ChildArticle filtered by the article_update column
 * @method     ChildArticle|null findOneByCreatedAt(string $article_created) Return the first ChildArticle filtered by the article_created column
 * @method     ChildArticle|null findOneByUpdatedAt(string $article_updated) Return the first ChildArticle filtered by the article_updated column
 * @method     ChildArticle|null findOneByDeletedAt(string $article_deleted) Return the first ChildArticle filtered by the article_deleted column
 * @method     ChildArticle|null findOneByDone(boolean $article_done) Return the first ChildArticle filtered by the article_done column
 * @method     ChildArticle|null findOneByToCheck(boolean $article_to_check) Return the first ChildArticle filtered by the article_to_check column
 * @method     ChildArticle|null findOneByPushedToData(string $article_pushed_to_data) Return the first ChildArticle filtered by the article_pushed_to_data column
 * @method     ChildArticle|null findOneByDeletionBy(int $article_deletion_by) Return the first ChildArticle filtered by the article_deletion_by column
 * @method     ChildArticle|null findOneByDeletionDate(string $article_deletion_date) Return the first ChildArticle filtered by the article_deletion_date column
 * @method     ChildArticle|null findOneByDeletionReason(string $article_deletion_reason) Return the first ChildArticle filtered by the article_deletion_reason column *

 * @method     ChildArticle requirePk($key, ConnectionInterface $con = null) Return the ChildArticle by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOne(ConnectionInterface $con = null) Return the first ChildArticle matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildArticle requireOneById(int $article_id) Return the first ChildArticle filtered by the article_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByItem(int $article_item) Return the first ChildArticle filtered by the article_item column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByTextid(string $article_textid) Return the first ChildArticle filtered by the article_textid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByEan(string $article_ean) Return the first ChildArticle filtered by the article_ean column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByEanOthers(string $article_ean_others) Return the first ChildArticle filtered by the article_ean_others column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByAsin(string $article_asin) Return the first ChildArticle filtered by the article_asin column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByNoosfereId(int $article_noosfere_id) Return the first ChildArticle filtered by the article_noosfere_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByUrl(string $article_url) Return the first ChildArticle filtered by the article_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByTypeId(int $type_id) Return the first ChildArticle filtered by the type_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByTitle(string $article_title) Return the first ChildArticle filtered by the article_title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByTitleAlphabetic(string $article_title_alphabetic) Return the first ChildArticle filtered by the article_title_alphabetic column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByTitleOriginal(string $article_title_original) Return the first ChildArticle filtered by the article_title_original column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByTitleOthers(string $article_title_others) Return the first ChildArticle filtered by the article_title_others column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneBySubtitle(string $article_subtitle) Return the first ChildArticle filtered by the article_subtitle column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByLangCurrent(int $article_lang_current) Return the first ChildArticle filtered by the article_lang_current column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByLangOriginal(int $article_lang_original) Return the first ChildArticle filtered by the article_lang_original column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByOriginCountry(int $article_origin_country) Return the first ChildArticle filtered by the article_origin_country column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByThemeBisac(string $article_theme_bisac) Return the first ChildArticle filtered by the article_theme_bisac column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByThemeClil(string $article_theme_clil) Return the first ChildArticle filtered by the article_theme_clil column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByThemeDewey(string $article_theme_dewey) Return the first ChildArticle filtered by the article_theme_dewey column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByThemeElectre(string $article_theme_electre) Return the first ChildArticle filtered by the article_theme_electre column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneBySourceId(int $article_source_id) Return the first ChildArticle filtered by the article_source_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByAuthors(string $article_authors) Return the first ChildArticle filtered by the article_authors column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByAuthorsAlphabetic(string $article_authors_alphabetic) Return the first ChildArticle filtered by the article_authors_alphabetic column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByCollectionId(int $collection_id) Return the first ChildArticle filtered by the collection_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByCollection(string $article_collection) Return the first ChildArticle filtered by the article_collection column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByNumber(string $article_number) Return the first ChildArticle filtered by the article_number column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByPublisherId(int $publisher_id) Return the first ChildArticle filtered by the publisher_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByPublisher(string $article_publisher) Return the first ChildArticle filtered by the article_publisher column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByCycleId(int $cycle_id) Return the first ChildArticle filtered by the cycle_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByCycle(string $article_cycle) Return the first ChildArticle filtered by the article_cycle column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByTome(string $article_tome) Return the first ChildArticle filtered by the article_tome column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByCoverVersion(int $article_cover_version) Return the first ChildArticle filtered by the article_cover_version column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByAvailability(int $article_availability) Return the first ChildArticle filtered by the article_availability column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByAvailabilityDilicom(int $article_availability_dilicom) Return the first ChildArticle filtered by the article_availability_dilicom column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByPreorder(boolean $article_preorder) Return the first ChildArticle filtered by the article_preorder column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByPrice(int $article_price) Return the first ChildArticle filtered by the article_price column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByPriceEditable(boolean $article_price_editable) Return the first ChildArticle filtered by the article_price_editable column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByNewPrice(int $article_new_price) Return the first ChildArticle filtered by the article_new_price column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByCategory(string $article_category) Return the first ChildArticle filtered by the article_category column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByTva(int $article_tva) Return the first ChildArticle filtered by the article_tva column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByPdfEan(string $article_pdf_ean) Return the first ChildArticle filtered by the article_pdf_ean column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByPdfVersion(string $article_pdf_version) Return the first ChildArticle filtered by the article_pdf_version column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByEpubEan(string $article_epub_ean) Return the first ChildArticle filtered by the article_epub_ean column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByEpubVersion(string $article_epub_version) Return the first ChildArticle filtered by the article_epub_version column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByAzwEan(string $article_azw_ean) Return the first ChildArticle filtered by the article_azw_ean column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByAzwVersion(string $article_azw_version) Return the first ChildArticle filtered by the article_azw_version column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByPages(int $article_pages) Return the first ChildArticle filtered by the article_pages column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByWeight(int $article_weight) Return the first ChildArticle filtered by the article_weight column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByShaping(string $article_shaping) Return the first ChildArticle filtered by the article_shaping column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByFormat(string $article_format) Return the first ChildArticle filtered by the article_format column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByPrintingProcess(string $article_printing_process) Return the first ChildArticle filtered by the article_printing_process column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByAgeMin(int $article_age_min) Return the first ChildArticle filtered by the article_age_min column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByAgeMax(int $article_age_max) Return the first ChildArticle filtered by the article_age_max column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneBySummary(string $article_summary) Return the first ChildArticle filtered by the article_summary column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByContents(string $article_contents) Return the first ChildArticle filtered by the article_contents column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByBonus(string $article_bonus) Return the first ChildArticle filtered by the article_bonus column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByCatchline(string $article_catchline) Return the first ChildArticle filtered by the article_catchline column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByBiography(string $article_biography) Return the first ChildArticle filtered by the article_biography column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByMotsv(string $article_motsv) Return the first ChildArticle filtered by the article_motsv column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByCopyright(int $article_copyright) Return the first ChildArticle filtered by the article_copyright column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByPubdate(string $article_pubdate) Return the first ChildArticle filtered by the article_pubdate column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByKeywords(string $article_keywords) Return the first ChildArticle filtered by the article_keywords column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByLinks(string $article_links) Return the first ChildArticle filtered by the article_links column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByKeywordsGenerated(string $article_keywords_generated) Return the first ChildArticle filtered by the article_keywords_generated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByPublisherStock(int $article_publisher_stock) Return the first ChildArticle filtered by the article_publisher_stock column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByHits(int $article_hits) Return the first ChildArticle filtered by the article_hits column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByEditingUser(int $article_editing_user) Return the first ChildArticle filtered by the article_editing_user column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByInsert(string $article_insert) Return the first ChildArticle filtered by the article_insert column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByUpdate(string $article_update) Return the first ChildArticle filtered by the article_update column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByCreatedAt(string $article_created) Return the first ChildArticle filtered by the article_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByUpdatedAt(string $article_updated) Return the first ChildArticle filtered by the article_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByDeletedAt(string $article_deleted) Return the first ChildArticle filtered by the article_deleted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByDone(boolean $article_done) Return the first ChildArticle filtered by the article_done column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByToCheck(boolean $article_to_check) Return the first ChildArticle filtered by the article_to_check column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByPushedToData(string $article_pushed_to_data) Return the first ChildArticle filtered by the article_pushed_to_data column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByDeletionBy(int $article_deletion_by) Return the first ChildArticle filtered by the article_deletion_by column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByDeletionDate(string $article_deletion_date) Return the first ChildArticle filtered by the article_deletion_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByDeletionReason(string $article_deletion_reason) Return the first ChildArticle filtered by the article_deletion_reason column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildArticle[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildArticle objects based on current ModelCriteria
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> find(ConnectionInterface $con = null) Return ChildArticle objects based on current ModelCriteria
 * @method     ChildArticle[]|ObjectCollection findById(int $article_id) Return ChildArticle objects filtered by the article_id column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findById(int $article_id) Return ChildArticle objects filtered by the article_id column
 * @method     ChildArticle[]|ObjectCollection findByItem(int $article_item) Return ChildArticle objects filtered by the article_item column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByItem(int $article_item) Return ChildArticle objects filtered by the article_item column
 * @method     ChildArticle[]|ObjectCollection findByTextid(string $article_textid) Return ChildArticle objects filtered by the article_textid column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByTextid(string $article_textid) Return ChildArticle objects filtered by the article_textid column
 * @method     ChildArticle[]|ObjectCollection findByEan(string $article_ean) Return ChildArticle objects filtered by the article_ean column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByEan(string $article_ean) Return ChildArticle objects filtered by the article_ean column
 * @method     ChildArticle[]|ObjectCollection findByEanOthers(string $article_ean_others) Return ChildArticle objects filtered by the article_ean_others column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByEanOthers(string $article_ean_others) Return ChildArticle objects filtered by the article_ean_others column
 * @method     ChildArticle[]|ObjectCollection findByAsin(string $article_asin) Return ChildArticle objects filtered by the article_asin column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByAsin(string $article_asin) Return ChildArticle objects filtered by the article_asin column
 * @method     ChildArticle[]|ObjectCollection findByNoosfereId(int $article_noosfere_id) Return ChildArticle objects filtered by the article_noosfere_id column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByNoosfereId(int $article_noosfere_id) Return ChildArticle objects filtered by the article_noosfere_id column
 * @method     ChildArticle[]|ObjectCollection findByUrl(string $article_url) Return ChildArticle objects filtered by the article_url column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByUrl(string $article_url) Return ChildArticle objects filtered by the article_url column
 * @method     ChildArticle[]|ObjectCollection findByTypeId(int $type_id) Return ChildArticle objects filtered by the type_id column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByTypeId(int $type_id) Return ChildArticle objects filtered by the type_id column
 * @method     ChildArticle[]|ObjectCollection findByTitle(string $article_title) Return ChildArticle objects filtered by the article_title column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByTitle(string $article_title) Return ChildArticle objects filtered by the article_title column
 * @method     ChildArticle[]|ObjectCollection findByTitleAlphabetic(string $article_title_alphabetic) Return ChildArticle objects filtered by the article_title_alphabetic column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByTitleAlphabetic(string $article_title_alphabetic) Return ChildArticle objects filtered by the article_title_alphabetic column
 * @method     ChildArticle[]|ObjectCollection findByTitleOriginal(string $article_title_original) Return ChildArticle objects filtered by the article_title_original column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByTitleOriginal(string $article_title_original) Return ChildArticle objects filtered by the article_title_original column
 * @method     ChildArticle[]|ObjectCollection findByTitleOthers(string $article_title_others) Return ChildArticle objects filtered by the article_title_others column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByTitleOthers(string $article_title_others) Return ChildArticle objects filtered by the article_title_others column
 * @method     ChildArticle[]|ObjectCollection findBySubtitle(string $article_subtitle) Return ChildArticle objects filtered by the article_subtitle column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findBySubtitle(string $article_subtitle) Return ChildArticle objects filtered by the article_subtitle column
 * @method     ChildArticle[]|ObjectCollection findByLangCurrent(int $article_lang_current) Return ChildArticle objects filtered by the article_lang_current column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByLangCurrent(int $article_lang_current) Return ChildArticle objects filtered by the article_lang_current column
 * @method     ChildArticle[]|ObjectCollection findByLangOriginal(int $article_lang_original) Return ChildArticle objects filtered by the article_lang_original column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByLangOriginal(int $article_lang_original) Return ChildArticle objects filtered by the article_lang_original column
 * @method     ChildArticle[]|ObjectCollection findByOriginCountry(int $article_origin_country) Return ChildArticle objects filtered by the article_origin_country column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByOriginCountry(int $article_origin_country) Return ChildArticle objects filtered by the article_origin_country column
 * @method     ChildArticle[]|ObjectCollection findByThemeBisac(string $article_theme_bisac) Return ChildArticle objects filtered by the article_theme_bisac column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByThemeBisac(string $article_theme_bisac) Return ChildArticle objects filtered by the article_theme_bisac column
 * @method     ChildArticle[]|ObjectCollection findByThemeClil(string $article_theme_clil) Return ChildArticle objects filtered by the article_theme_clil column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByThemeClil(string $article_theme_clil) Return ChildArticle objects filtered by the article_theme_clil column
 * @method     ChildArticle[]|ObjectCollection findByThemeDewey(string $article_theme_dewey) Return ChildArticle objects filtered by the article_theme_dewey column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByThemeDewey(string $article_theme_dewey) Return ChildArticle objects filtered by the article_theme_dewey column
 * @method     ChildArticle[]|ObjectCollection findByThemeElectre(string $article_theme_electre) Return ChildArticle objects filtered by the article_theme_electre column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByThemeElectre(string $article_theme_electre) Return ChildArticle objects filtered by the article_theme_electre column
 * @method     ChildArticle[]|ObjectCollection findBySourceId(int $article_source_id) Return ChildArticle objects filtered by the article_source_id column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findBySourceId(int $article_source_id) Return ChildArticle objects filtered by the article_source_id column
 * @method     ChildArticle[]|ObjectCollection findByAuthors(string $article_authors) Return ChildArticle objects filtered by the article_authors column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByAuthors(string $article_authors) Return ChildArticle objects filtered by the article_authors column
 * @method     ChildArticle[]|ObjectCollection findByAuthorsAlphabetic(string $article_authors_alphabetic) Return ChildArticle objects filtered by the article_authors_alphabetic column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByAuthorsAlphabetic(string $article_authors_alphabetic) Return ChildArticle objects filtered by the article_authors_alphabetic column
 * @method     ChildArticle[]|ObjectCollection findByCollectionId(int $collection_id) Return ChildArticle objects filtered by the collection_id column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByCollectionId(int $collection_id) Return ChildArticle objects filtered by the collection_id column
 * @method     ChildArticle[]|ObjectCollection findByCollection(string $article_collection) Return ChildArticle objects filtered by the article_collection column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByCollection(string $article_collection) Return ChildArticle objects filtered by the article_collection column
 * @method     ChildArticle[]|ObjectCollection findByNumber(string $article_number) Return ChildArticle objects filtered by the article_number column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByNumber(string $article_number) Return ChildArticle objects filtered by the article_number column
 * @method     ChildArticle[]|ObjectCollection findByPublisherId(int $publisher_id) Return ChildArticle objects filtered by the publisher_id column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByPublisherId(int $publisher_id) Return ChildArticle objects filtered by the publisher_id column
 * @method     ChildArticle[]|ObjectCollection findByPublisher(string $article_publisher) Return ChildArticle objects filtered by the article_publisher column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByPublisher(string $article_publisher) Return ChildArticle objects filtered by the article_publisher column
 * @method     ChildArticle[]|ObjectCollection findByCycleId(int $cycle_id) Return ChildArticle objects filtered by the cycle_id column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByCycleId(int $cycle_id) Return ChildArticle objects filtered by the cycle_id column
 * @method     ChildArticle[]|ObjectCollection findByCycle(string $article_cycle) Return ChildArticle objects filtered by the article_cycle column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByCycle(string $article_cycle) Return ChildArticle objects filtered by the article_cycle column
 * @method     ChildArticle[]|ObjectCollection findByTome(string $article_tome) Return ChildArticle objects filtered by the article_tome column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByTome(string $article_tome) Return ChildArticle objects filtered by the article_tome column
 * @method     ChildArticle[]|ObjectCollection findByCoverVersion(int $article_cover_version) Return ChildArticle objects filtered by the article_cover_version column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByCoverVersion(int $article_cover_version) Return ChildArticle objects filtered by the article_cover_version column
 * @method     ChildArticle[]|ObjectCollection findByAvailability(int $article_availability) Return ChildArticle objects filtered by the article_availability column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByAvailability(int $article_availability) Return ChildArticle objects filtered by the article_availability column
 * @method     ChildArticle[]|ObjectCollection findByAvailabilityDilicom(int $article_availability_dilicom) Return ChildArticle objects filtered by the article_availability_dilicom column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByAvailabilityDilicom(int $article_availability_dilicom) Return ChildArticle objects filtered by the article_availability_dilicom column
 * @method     ChildArticle[]|ObjectCollection findByPreorder(boolean $article_preorder) Return ChildArticle objects filtered by the article_preorder column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByPreorder(boolean $article_preorder) Return ChildArticle objects filtered by the article_preorder column
 * @method     ChildArticle[]|ObjectCollection findByPrice(int $article_price) Return ChildArticle objects filtered by the article_price column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByPrice(int $article_price) Return ChildArticle objects filtered by the article_price column
 * @method     ChildArticle[]|ObjectCollection findByPriceEditable(boolean $article_price_editable) Return ChildArticle objects filtered by the article_price_editable column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByPriceEditable(boolean $article_price_editable) Return ChildArticle objects filtered by the article_price_editable column
 * @method     ChildArticle[]|ObjectCollection findByNewPrice(int $article_new_price) Return ChildArticle objects filtered by the article_new_price column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByNewPrice(int $article_new_price) Return ChildArticle objects filtered by the article_new_price column
 * @method     ChildArticle[]|ObjectCollection findByCategory(string $article_category) Return ChildArticle objects filtered by the article_category column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByCategory(string $article_category) Return ChildArticle objects filtered by the article_category column
 * @method     ChildArticle[]|ObjectCollection findByTva(int $article_tva) Return ChildArticle objects filtered by the article_tva column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByTva(int $article_tva) Return ChildArticle objects filtered by the article_tva column
 * @method     ChildArticle[]|ObjectCollection findByPdfEan(string $article_pdf_ean) Return ChildArticle objects filtered by the article_pdf_ean column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByPdfEan(string $article_pdf_ean) Return ChildArticle objects filtered by the article_pdf_ean column
 * @method     ChildArticle[]|ObjectCollection findByPdfVersion(string $article_pdf_version) Return ChildArticle objects filtered by the article_pdf_version column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByPdfVersion(string $article_pdf_version) Return ChildArticle objects filtered by the article_pdf_version column
 * @method     ChildArticle[]|ObjectCollection findByEpubEan(string $article_epub_ean) Return ChildArticle objects filtered by the article_epub_ean column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByEpubEan(string $article_epub_ean) Return ChildArticle objects filtered by the article_epub_ean column
 * @method     ChildArticle[]|ObjectCollection findByEpubVersion(string $article_epub_version) Return ChildArticle objects filtered by the article_epub_version column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByEpubVersion(string $article_epub_version) Return ChildArticle objects filtered by the article_epub_version column
 * @method     ChildArticle[]|ObjectCollection findByAzwEan(string $article_azw_ean) Return ChildArticle objects filtered by the article_azw_ean column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByAzwEan(string $article_azw_ean) Return ChildArticle objects filtered by the article_azw_ean column
 * @method     ChildArticle[]|ObjectCollection findByAzwVersion(string $article_azw_version) Return ChildArticle objects filtered by the article_azw_version column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByAzwVersion(string $article_azw_version) Return ChildArticle objects filtered by the article_azw_version column
 * @method     ChildArticle[]|ObjectCollection findByPages(int $article_pages) Return ChildArticle objects filtered by the article_pages column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByPages(int $article_pages) Return ChildArticle objects filtered by the article_pages column
 * @method     ChildArticle[]|ObjectCollection findByWeight(int $article_weight) Return ChildArticle objects filtered by the article_weight column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByWeight(int $article_weight) Return ChildArticle objects filtered by the article_weight column
 * @method     ChildArticle[]|ObjectCollection findByShaping(string $article_shaping) Return ChildArticle objects filtered by the article_shaping column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByShaping(string $article_shaping) Return ChildArticle objects filtered by the article_shaping column
 * @method     ChildArticle[]|ObjectCollection findByFormat(string $article_format) Return ChildArticle objects filtered by the article_format column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByFormat(string $article_format) Return ChildArticle objects filtered by the article_format column
 * @method     ChildArticle[]|ObjectCollection findByPrintingProcess(string $article_printing_process) Return ChildArticle objects filtered by the article_printing_process column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByPrintingProcess(string $article_printing_process) Return ChildArticle objects filtered by the article_printing_process column
 * @method     ChildArticle[]|ObjectCollection findByAgeMin(int $article_age_min) Return ChildArticle objects filtered by the article_age_min column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByAgeMin(int $article_age_min) Return ChildArticle objects filtered by the article_age_min column
 * @method     ChildArticle[]|ObjectCollection findByAgeMax(int $article_age_max) Return ChildArticle objects filtered by the article_age_max column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByAgeMax(int $article_age_max) Return ChildArticle objects filtered by the article_age_max column
 * @method     ChildArticle[]|ObjectCollection findBySummary(string $article_summary) Return ChildArticle objects filtered by the article_summary column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findBySummary(string $article_summary) Return ChildArticle objects filtered by the article_summary column
 * @method     ChildArticle[]|ObjectCollection findByContents(string $article_contents) Return ChildArticle objects filtered by the article_contents column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByContents(string $article_contents) Return ChildArticle objects filtered by the article_contents column
 * @method     ChildArticle[]|ObjectCollection findByBonus(string $article_bonus) Return ChildArticle objects filtered by the article_bonus column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByBonus(string $article_bonus) Return ChildArticle objects filtered by the article_bonus column
 * @method     ChildArticle[]|ObjectCollection findByCatchline(string $article_catchline) Return ChildArticle objects filtered by the article_catchline column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByCatchline(string $article_catchline) Return ChildArticle objects filtered by the article_catchline column
 * @method     ChildArticle[]|ObjectCollection findByBiography(string $article_biography) Return ChildArticle objects filtered by the article_biography column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByBiography(string $article_biography) Return ChildArticle objects filtered by the article_biography column
 * @method     ChildArticle[]|ObjectCollection findByMotsv(string $article_motsv) Return ChildArticle objects filtered by the article_motsv column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByMotsv(string $article_motsv) Return ChildArticle objects filtered by the article_motsv column
 * @method     ChildArticle[]|ObjectCollection findByCopyright(int $article_copyright) Return ChildArticle objects filtered by the article_copyright column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByCopyright(int $article_copyright) Return ChildArticle objects filtered by the article_copyright column
 * @method     ChildArticle[]|ObjectCollection findByPubdate(string $article_pubdate) Return ChildArticle objects filtered by the article_pubdate column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByPubdate(string $article_pubdate) Return ChildArticle objects filtered by the article_pubdate column
 * @method     ChildArticle[]|ObjectCollection findByKeywords(string $article_keywords) Return ChildArticle objects filtered by the article_keywords column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByKeywords(string $article_keywords) Return ChildArticle objects filtered by the article_keywords column
 * @method     ChildArticle[]|ObjectCollection findByLinks(string $article_links) Return ChildArticle objects filtered by the article_links column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByLinks(string $article_links) Return ChildArticle objects filtered by the article_links column
 * @method     ChildArticle[]|ObjectCollection findByKeywordsGenerated(string $article_keywords_generated) Return ChildArticle objects filtered by the article_keywords_generated column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByKeywordsGenerated(string $article_keywords_generated) Return ChildArticle objects filtered by the article_keywords_generated column
 * @method     ChildArticle[]|ObjectCollection findByPublisherStock(int $article_publisher_stock) Return ChildArticle objects filtered by the article_publisher_stock column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByPublisherStock(int $article_publisher_stock) Return ChildArticle objects filtered by the article_publisher_stock column
 * @method     ChildArticle[]|ObjectCollection findByHits(int $article_hits) Return ChildArticle objects filtered by the article_hits column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByHits(int $article_hits) Return ChildArticle objects filtered by the article_hits column
 * @method     ChildArticle[]|ObjectCollection findByEditingUser(int $article_editing_user) Return ChildArticle objects filtered by the article_editing_user column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByEditingUser(int $article_editing_user) Return ChildArticle objects filtered by the article_editing_user column
 * @method     ChildArticle[]|ObjectCollection findByInsert(string $article_insert) Return ChildArticle objects filtered by the article_insert column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByInsert(string $article_insert) Return ChildArticle objects filtered by the article_insert column
 * @method     ChildArticle[]|ObjectCollection findByUpdate(string $article_update) Return ChildArticle objects filtered by the article_update column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByUpdate(string $article_update) Return ChildArticle objects filtered by the article_update column
 * @method     ChildArticle[]|ObjectCollection findByCreatedAt(string $article_created) Return ChildArticle objects filtered by the article_created column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByCreatedAt(string $article_created) Return ChildArticle objects filtered by the article_created column
 * @method     ChildArticle[]|ObjectCollection findByUpdatedAt(string $article_updated) Return ChildArticle objects filtered by the article_updated column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByUpdatedAt(string $article_updated) Return ChildArticle objects filtered by the article_updated column
 * @method     ChildArticle[]|ObjectCollection findByDeletedAt(string $article_deleted) Return ChildArticle objects filtered by the article_deleted column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByDeletedAt(string $article_deleted) Return ChildArticle objects filtered by the article_deleted column
 * @method     ChildArticle[]|ObjectCollection findByDone(boolean $article_done) Return ChildArticle objects filtered by the article_done column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByDone(boolean $article_done) Return ChildArticle objects filtered by the article_done column
 * @method     ChildArticle[]|ObjectCollection findByToCheck(boolean $article_to_check) Return ChildArticle objects filtered by the article_to_check column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByToCheck(boolean $article_to_check) Return ChildArticle objects filtered by the article_to_check column
 * @method     ChildArticle[]|ObjectCollection findByPushedToData(string $article_pushed_to_data) Return ChildArticle objects filtered by the article_pushed_to_data column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByPushedToData(string $article_pushed_to_data) Return ChildArticle objects filtered by the article_pushed_to_data column
 * @method     ChildArticle[]|ObjectCollection findByDeletionBy(int $article_deletion_by) Return ChildArticle objects filtered by the article_deletion_by column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByDeletionBy(int $article_deletion_by) Return ChildArticle objects filtered by the article_deletion_by column
 * @method     ChildArticle[]|ObjectCollection findByDeletionDate(string $article_deletion_date) Return ChildArticle objects filtered by the article_deletion_date column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByDeletionDate(string $article_deletion_date) Return ChildArticle objects filtered by the article_deletion_date column
 * @method     ChildArticle[]|ObjectCollection findByDeletionReason(string $article_deletion_reason) Return ChildArticle objects filtered by the article_deletion_reason column
 * @psalm-method ObjectCollection&\Traversable<ChildArticle> findByDeletionReason(string $article_deletion_reason) Return ChildArticle objects filtered by the article_deletion_reason column
 * @method     ChildArticle[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildArticle> paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ArticleQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\ArticleQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Article', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildArticleQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildArticleQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildArticleQuery) {
            return $criteria;
        }
        $query = new ChildArticleQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildArticle|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ArticleTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ArticleTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildArticle A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT article_id, article_item, article_textid, article_ean, article_ean_others, article_asin, article_noosfere_id, article_url, type_id, article_title, article_title_alphabetic, article_title_original, article_title_others, article_subtitle, article_lang_current, article_lang_original, article_origin_country, article_theme_bisac, article_theme_clil, article_theme_dewey, article_theme_electre, article_source_id, article_authors, article_authors_alphabetic, collection_id, article_collection, article_number, publisher_id, article_publisher, cycle_id, article_cycle, article_tome, article_cover_version, article_availability, article_availability_dilicom, article_preorder, article_price, article_price_editable, article_new_price, article_category, article_tva, article_pdf_ean, article_pdf_version, article_epub_ean, article_epub_version, article_azw_ean, article_azw_version, article_pages, article_weight, article_shaping, article_format, article_printing_process, article_age_min, article_age_max, article_summary, article_contents, article_bonus, article_catchline, article_biography, article_motsv, article_copyright, article_pubdate, article_keywords, article_links, article_keywords_generated, article_publisher_stock, article_hits, article_editing_user, article_insert, article_update, article_created, article_updated, article_deleted, article_done, article_to_check, article_pushed_to_data, article_deletion_by, article_deletion_date, article_deletion_reason FROM articles WHERE article_id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildArticle $obj */
            $obj = new ChildArticle();
            $obj->hydrate($row);
            ArticleTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildArticle|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the article_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE article_id = 1234
     * $query->filterById(array(12, 34)); // WHERE article_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE article_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_ID, $id, $comparison);
    }

    /**
     * Filter the query on the article_item column
     *
     * Example usage:
     * <code>
     * $query->filterByItem(1234); // WHERE article_item = 1234
     * $query->filterByItem(array(12, 34)); // WHERE article_item IN (12, 34)
     * $query->filterByItem(array('min' => 12)); // WHERE article_item > 12
     * </code>
     *
     * @param     mixed $item The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByItem($item = null, $comparison = null)
    {
        if (is_array($item)) {
            $useMinMax = false;
            if (isset($item['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_ITEM, $item['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($item['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_ITEM, $item['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_ITEM, $item, $comparison);
    }

    /**
     * Filter the query on the article_textid column
     *
     * Example usage:
     * <code>
     * $query->filterByTextid('fooValue');   // WHERE article_textid = 'fooValue'
     * $query->filterByTextid('%fooValue%', Criteria::LIKE); // WHERE article_textid LIKE '%fooValue%'
     * </code>
     *
     * @param     string $textid The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByTextid($textid = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($textid)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_TEXTID, $textid, $comparison);
    }

    /**
     * Filter the query on the article_ean column
     *
     * Example usage:
     * <code>
     * $query->filterByEan(1234); // WHERE article_ean = 1234
     * $query->filterByEan(array(12, 34)); // WHERE article_ean IN (12, 34)
     * $query->filterByEan(array('min' => 12)); // WHERE article_ean > 12
     * </code>
     *
     * @param     mixed $ean The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByEan($ean = null, $comparison = null)
    {
        if (is_array($ean)) {
            $useMinMax = false;
            if (isset($ean['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_EAN, $ean['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ean['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_EAN, $ean['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_EAN, $ean, $comparison);
    }

    /**
     * Filter the query on the article_ean_others column
     *
     * Example usage:
     * <code>
     * $query->filterByEanOthers('fooValue');   // WHERE article_ean_others = 'fooValue'
     * $query->filterByEanOthers('%fooValue%', Criteria::LIKE); // WHERE article_ean_others LIKE '%fooValue%'
     * </code>
     *
     * @param     string $eanOthers The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByEanOthers($eanOthers = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($eanOthers)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_EAN_OTHERS, $eanOthers, $comparison);
    }

    /**
     * Filter the query on the article_asin column
     *
     * Example usage:
     * <code>
     * $query->filterByAsin('fooValue');   // WHERE article_asin = 'fooValue'
     * $query->filterByAsin('%fooValue%', Criteria::LIKE); // WHERE article_asin LIKE '%fooValue%'
     * </code>
     *
     * @param     string $asin The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByAsin($asin = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($asin)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_ASIN, $asin, $comparison);
    }

    /**
     * Filter the query on the article_noosfere_id column
     *
     * Example usage:
     * <code>
     * $query->filterByNoosfereId(1234); // WHERE article_noosfere_id = 1234
     * $query->filterByNoosfereId(array(12, 34)); // WHERE article_noosfere_id IN (12, 34)
     * $query->filterByNoosfereId(array('min' => 12)); // WHERE article_noosfere_id > 12
     * </code>
     *
     * @param     mixed $noosfereId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByNoosfereId($noosfereId = null, $comparison = null)
    {
        if (is_array($noosfereId)) {
            $useMinMax = false;
            if (isset($noosfereId['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_NOOSFERE_ID, $noosfereId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($noosfereId['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_NOOSFERE_ID, $noosfereId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_NOOSFERE_ID, $noosfereId, $comparison);
    }

    /**
     * Filter the query on the article_url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE article_url = 'fooValue'
     * $query->filterByUrl('%fooValue%', Criteria::LIKE); // WHERE article_url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $url The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByUrl($url = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($url)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_URL, $url, $comparison);
    }

    /**
     * Filter the query on the type_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTypeId(1234); // WHERE type_id = 1234
     * $query->filterByTypeId(array(12, 34)); // WHERE type_id IN (12, 34)
     * $query->filterByTypeId(array('min' => 12)); // WHERE type_id > 12
     * </code>
     *
     * @param     mixed $typeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByTypeId($typeId = null, $comparison = null)
    {
        if (is_array($typeId)) {
            $useMinMax = false;
            if (isset($typeId['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_TYPE_ID, $typeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($typeId['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_TYPE_ID, $typeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_TYPE_ID, $typeId, $comparison);
    }

    /**
     * Filter the query on the article_title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE article_title = 'fooValue'
     * $query->filterByTitle('%fooValue%', Criteria::LIKE); // WHERE article_title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the article_title_alphabetic column
     *
     * Example usage:
     * <code>
     * $query->filterByTitleAlphabetic('fooValue');   // WHERE article_title_alphabetic = 'fooValue'
     * $query->filterByTitleAlphabetic('%fooValue%', Criteria::LIKE); // WHERE article_title_alphabetic LIKE '%fooValue%'
     * </code>
     *
     * @param     string $titleAlphabetic The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByTitleAlphabetic($titleAlphabetic = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($titleAlphabetic)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_TITLE_ALPHABETIC, $titleAlphabetic, $comparison);
    }

    /**
     * Filter the query on the article_title_original column
     *
     * Example usage:
     * <code>
     * $query->filterByTitleOriginal('fooValue');   // WHERE article_title_original = 'fooValue'
     * $query->filterByTitleOriginal('%fooValue%', Criteria::LIKE); // WHERE article_title_original LIKE '%fooValue%'
     * </code>
     *
     * @param     string $titleOriginal The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByTitleOriginal($titleOriginal = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($titleOriginal)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_TITLE_ORIGINAL, $titleOriginal, $comparison);
    }

    /**
     * Filter the query on the article_title_others column
     *
     * Example usage:
     * <code>
     * $query->filterByTitleOthers('fooValue');   // WHERE article_title_others = 'fooValue'
     * $query->filterByTitleOthers('%fooValue%', Criteria::LIKE); // WHERE article_title_others LIKE '%fooValue%'
     * </code>
     *
     * @param     string $titleOthers The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByTitleOthers($titleOthers = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($titleOthers)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_TITLE_OTHERS, $titleOthers, $comparison);
    }

    /**
     * Filter the query on the article_subtitle column
     *
     * Example usage:
     * <code>
     * $query->filterBySubtitle('fooValue');   // WHERE article_subtitle = 'fooValue'
     * $query->filterBySubtitle('%fooValue%', Criteria::LIKE); // WHERE article_subtitle LIKE '%fooValue%'
     * </code>
     *
     * @param     string $subtitle The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterBySubtitle($subtitle = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($subtitle)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_SUBTITLE, $subtitle, $comparison);
    }

    /**
     * Filter the query on the article_lang_current column
     *
     * Example usage:
     * <code>
     * $query->filterByLangCurrent(1234); // WHERE article_lang_current = 1234
     * $query->filterByLangCurrent(array(12, 34)); // WHERE article_lang_current IN (12, 34)
     * $query->filterByLangCurrent(array('min' => 12)); // WHERE article_lang_current > 12
     * </code>
     *
     * @param     mixed $langCurrent The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByLangCurrent($langCurrent = null, $comparison = null)
    {
        if (is_array($langCurrent)) {
            $useMinMax = false;
            if (isset($langCurrent['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_LANG_CURRENT, $langCurrent['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($langCurrent['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_LANG_CURRENT, $langCurrent['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_LANG_CURRENT, $langCurrent, $comparison);
    }

    /**
     * Filter the query on the article_lang_original column
     *
     * Example usage:
     * <code>
     * $query->filterByLangOriginal(1234); // WHERE article_lang_original = 1234
     * $query->filterByLangOriginal(array(12, 34)); // WHERE article_lang_original IN (12, 34)
     * $query->filterByLangOriginal(array('min' => 12)); // WHERE article_lang_original > 12
     * </code>
     *
     * @param     mixed $langOriginal The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByLangOriginal($langOriginal = null, $comparison = null)
    {
        if (is_array($langOriginal)) {
            $useMinMax = false;
            if (isset($langOriginal['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_LANG_ORIGINAL, $langOriginal['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($langOriginal['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_LANG_ORIGINAL, $langOriginal['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_LANG_ORIGINAL, $langOriginal, $comparison);
    }

    /**
     * Filter the query on the article_origin_country column
     *
     * Example usage:
     * <code>
     * $query->filterByOriginCountry(1234); // WHERE article_origin_country = 1234
     * $query->filterByOriginCountry(array(12, 34)); // WHERE article_origin_country IN (12, 34)
     * $query->filterByOriginCountry(array('min' => 12)); // WHERE article_origin_country > 12
     * </code>
     *
     * @param     mixed $originCountry The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByOriginCountry($originCountry = null, $comparison = null)
    {
        if (is_array($originCountry)) {
            $useMinMax = false;
            if (isset($originCountry['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_ORIGIN_COUNTRY, $originCountry['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($originCountry['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_ORIGIN_COUNTRY, $originCountry['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_ORIGIN_COUNTRY, $originCountry, $comparison);
    }

    /**
     * Filter the query on the article_theme_bisac column
     *
     * Example usage:
     * <code>
     * $query->filterByThemeBisac('fooValue');   // WHERE article_theme_bisac = 'fooValue'
     * $query->filterByThemeBisac('%fooValue%', Criteria::LIKE); // WHERE article_theme_bisac LIKE '%fooValue%'
     * </code>
     *
     * @param     string $themeBisac The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByThemeBisac($themeBisac = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($themeBisac)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_THEME_BISAC, $themeBisac, $comparison);
    }

    /**
     * Filter the query on the article_theme_clil column
     *
     * Example usage:
     * <code>
     * $query->filterByThemeClil('fooValue');   // WHERE article_theme_clil = 'fooValue'
     * $query->filterByThemeClil('%fooValue%', Criteria::LIKE); // WHERE article_theme_clil LIKE '%fooValue%'
     * </code>
     *
     * @param     string $themeClil The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByThemeClil($themeClil = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($themeClil)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_THEME_CLIL, $themeClil, $comparison);
    }

    /**
     * Filter the query on the article_theme_dewey column
     *
     * Example usage:
     * <code>
     * $query->filterByThemeDewey('fooValue');   // WHERE article_theme_dewey = 'fooValue'
     * $query->filterByThemeDewey('%fooValue%', Criteria::LIKE); // WHERE article_theme_dewey LIKE '%fooValue%'
     * </code>
     *
     * @param     string $themeDewey The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByThemeDewey($themeDewey = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($themeDewey)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_THEME_DEWEY, $themeDewey, $comparison);
    }

    /**
     * Filter the query on the article_theme_electre column
     *
     * Example usage:
     * <code>
     * $query->filterByThemeElectre('fooValue');   // WHERE article_theme_electre = 'fooValue'
     * $query->filterByThemeElectre('%fooValue%', Criteria::LIKE); // WHERE article_theme_electre LIKE '%fooValue%'
     * </code>
     *
     * @param     string $themeElectre The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByThemeElectre($themeElectre = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($themeElectre)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_THEME_ELECTRE, $themeElectre, $comparison);
    }

    /**
     * Filter the query on the article_source_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySourceId(1234); // WHERE article_source_id = 1234
     * $query->filterBySourceId(array(12, 34)); // WHERE article_source_id IN (12, 34)
     * $query->filterBySourceId(array('min' => 12)); // WHERE article_source_id > 12
     * </code>
     *
     * @param     mixed $sourceId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterBySourceId($sourceId = null, $comparison = null)
    {
        if (is_array($sourceId)) {
            $useMinMax = false;
            if (isset($sourceId['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_SOURCE_ID, $sourceId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sourceId['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_SOURCE_ID, $sourceId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_SOURCE_ID, $sourceId, $comparison);
    }

    /**
     * Filter the query on the article_authors column
     *
     * Example usage:
     * <code>
     * $query->filterByAuthors('fooValue');   // WHERE article_authors = 'fooValue'
     * $query->filterByAuthors('%fooValue%', Criteria::LIKE); // WHERE article_authors LIKE '%fooValue%'
     * </code>
     *
     * @param     string $authors The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByAuthors($authors = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($authors)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_AUTHORS, $authors, $comparison);
    }

    /**
     * Filter the query on the article_authors_alphabetic column
     *
     * Example usage:
     * <code>
     * $query->filterByAuthorsAlphabetic('fooValue');   // WHERE article_authors_alphabetic = 'fooValue'
     * $query->filterByAuthorsAlphabetic('%fooValue%', Criteria::LIKE); // WHERE article_authors_alphabetic LIKE '%fooValue%'
     * </code>
     *
     * @param     string $authorsAlphabetic The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByAuthorsAlphabetic($authorsAlphabetic = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($authorsAlphabetic)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_AUTHORS_ALPHABETIC, $authorsAlphabetic, $comparison);
    }

    /**
     * Filter the query on the collection_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCollectionId(1234); // WHERE collection_id = 1234
     * $query->filterByCollectionId(array(12, 34)); // WHERE collection_id IN (12, 34)
     * $query->filterByCollectionId(array('min' => 12)); // WHERE collection_id > 12
     * </code>
     *
     * @param     mixed $collectionId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByCollectionId($collectionId = null, $comparison = null)
    {
        if (is_array($collectionId)) {
            $useMinMax = false;
            if (isset($collectionId['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_COLLECTION_ID, $collectionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($collectionId['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_COLLECTION_ID, $collectionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_COLLECTION_ID, $collectionId, $comparison);
    }

    /**
     * Filter the query on the article_collection column
     *
     * Example usage:
     * <code>
     * $query->filterByCollection('fooValue');   // WHERE article_collection = 'fooValue'
     * $query->filterByCollection('%fooValue%', Criteria::LIKE); // WHERE article_collection LIKE '%fooValue%'
     * </code>
     *
     * @param     string $collection The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByCollection($collection = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($collection)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_COLLECTION, $collection, $comparison);
    }

    /**
     * Filter the query on the article_number column
     *
     * Example usage:
     * <code>
     * $query->filterByNumber('fooValue');   // WHERE article_number = 'fooValue'
     * $query->filterByNumber('%fooValue%', Criteria::LIKE); // WHERE article_number LIKE '%fooValue%'
     * </code>
     *
     * @param     string $number The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByNumber($number = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($number)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_NUMBER, $number, $comparison);
    }

    /**
     * Filter the query on the publisher_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPublisherId(1234); // WHERE publisher_id = 1234
     * $query->filterByPublisherId(array(12, 34)); // WHERE publisher_id IN (12, 34)
     * $query->filterByPublisherId(array('min' => 12)); // WHERE publisher_id > 12
     * </code>
     *
     * @param     mixed $publisherId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByPublisherId($publisherId = null, $comparison = null)
    {
        if (is_array($publisherId)) {
            $useMinMax = false;
            if (isset($publisherId['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_PUBLISHER_ID, $publisherId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publisherId['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_PUBLISHER_ID, $publisherId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_PUBLISHER_ID, $publisherId, $comparison);
    }

    /**
     * Filter the query on the article_publisher column
     *
     * Example usage:
     * <code>
     * $query->filterByPublisher('fooValue');   // WHERE article_publisher = 'fooValue'
     * $query->filterByPublisher('%fooValue%', Criteria::LIKE); // WHERE article_publisher LIKE '%fooValue%'
     * </code>
     *
     * @param     string $publisher The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByPublisher($publisher = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($publisher)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PUBLISHER, $publisher, $comparison);
    }

    /**
     * Filter the query on the cycle_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCycleId(1234); // WHERE cycle_id = 1234
     * $query->filterByCycleId(array(12, 34)); // WHERE cycle_id IN (12, 34)
     * $query->filterByCycleId(array('min' => 12)); // WHERE cycle_id > 12
     * </code>
     *
     * @param     mixed $cycleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByCycleId($cycleId = null, $comparison = null)
    {
        if (is_array($cycleId)) {
            $useMinMax = false;
            if (isset($cycleId['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_CYCLE_ID, $cycleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($cycleId['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_CYCLE_ID, $cycleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_CYCLE_ID, $cycleId, $comparison);
    }

    /**
     * Filter the query on the article_cycle column
     *
     * Example usage:
     * <code>
     * $query->filterByCycle('fooValue');   // WHERE article_cycle = 'fooValue'
     * $query->filterByCycle('%fooValue%', Criteria::LIKE); // WHERE article_cycle LIKE '%fooValue%'
     * </code>
     *
     * @param     string $cycle The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByCycle($cycle = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($cycle)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_CYCLE, $cycle, $comparison);
    }

    /**
     * Filter the query on the article_tome column
     *
     * Example usage:
     * <code>
     * $query->filterByTome('fooValue');   // WHERE article_tome = 'fooValue'
     * $query->filterByTome('%fooValue%', Criteria::LIKE); // WHERE article_tome LIKE '%fooValue%'
     * </code>
     *
     * @param     string $tome The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByTome($tome = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($tome)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_TOME, $tome, $comparison);
    }

    /**
     * Filter the query on the article_cover_version column
     *
     * Example usage:
     * <code>
     * $query->filterByCoverVersion(1234); // WHERE article_cover_version = 1234
     * $query->filterByCoverVersion(array(12, 34)); // WHERE article_cover_version IN (12, 34)
     * $query->filterByCoverVersion(array('min' => 12)); // WHERE article_cover_version > 12
     * </code>
     *
     * @param     mixed $coverVersion The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByCoverVersion($coverVersion = null, $comparison = null)
    {
        if (is_array($coverVersion)) {
            $useMinMax = false;
            if (isset($coverVersion['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_COVER_VERSION, $coverVersion['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($coverVersion['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_COVER_VERSION, $coverVersion['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_COVER_VERSION, $coverVersion, $comparison);
    }

    /**
     * Filter the query on the article_availability column
     *
     * Example usage:
     * <code>
     * $query->filterByAvailability(1234); // WHERE article_availability = 1234
     * $query->filterByAvailability(array(12, 34)); // WHERE article_availability IN (12, 34)
     * $query->filterByAvailability(array('min' => 12)); // WHERE article_availability > 12
     * </code>
     *
     * @param     mixed $availability The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByAvailability($availability = null, $comparison = null)
    {
        if (is_array($availability)) {
            $useMinMax = false;
            if (isset($availability['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_AVAILABILITY, $availability['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($availability['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_AVAILABILITY, $availability['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_AVAILABILITY, $availability, $comparison);
    }

    /**
     * Filter the query on the article_availability_dilicom column
     *
     * Example usage:
     * <code>
     * $query->filterByAvailabilityDilicom(1234); // WHERE article_availability_dilicom = 1234
     * $query->filterByAvailabilityDilicom(array(12, 34)); // WHERE article_availability_dilicom IN (12, 34)
     * $query->filterByAvailabilityDilicom(array('min' => 12)); // WHERE article_availability_dilicom > 12
     * </code>
     *
     * @param     mixed $availabilityDilicom The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByAvailabilityDilicom($availabilityDilicom = null, $comparison = null)
    {
        if (is_array($availabilityDilicom)) {
            $useMinMax = false;
            if (isset($availabilityDilicom['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_AVAILABILITY_DILICOM, $availabilityDilicom['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($availabilityDilicom['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_AVAILABILITY_DILICOM, $availabilityDilicom['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_AVAILABILITY_DILICOM, $availabilityDilicom, $comparison);
    }

    /**
     * Filter the query on the article_preorder column
     *
     * Example usage:
     * <code>
     * $query->filterByPreorder(true); // WHERE article_preorder = true
     * $query->filterByPreorder('yes'); // WHERE article_preorder = true
     * </code>
     *
     * @param     boolean|string $preorder The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByPreorder($preorder = null, $comparison = null)
    {
        if (is_string($preorder)) {
            $preorder = in_array(strtolower($preorder), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PREORDER, $preorder, $comparison);
    }

    /**
     * Filter the query on the article_price column
     *
     * Example usage:
     * <code>
     * $query->filterByPrice(1234); // WHERE article_price = 1234
     * $query->filterByPrice(array(12, 34)); // WHERE article_price IN (12, 34)
     * $query->filterByPrice(array('min' => 12)); // WHERE article_price > 12
     * </code>
     *
     * @param     mixed $price The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByPrice($price = null, $comparison = null)
    {
        if (is_array($price)) {
            $useMinMax = false;
            if (isset($price['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PRICE, $price['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($price['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PRICE, $price['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PRICE, $price, $comparison);
    }

    /**
     * Filter the query on the article_price_editable column
     *
     * Example usage:
     * <code>
     * $query->filterByPriceEditable(true); // WHERE article_price_editable = true
     * $query->filterByPriceEditable('yes'); // WHERE article_price_editable = true
     * </code>
     *
     * @param     boolean|string $priceEditable The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByPriceEditable($priceEditable = null, $comparison = null)
    {
        if (is_string($priceEditable)) {
            $priceEditable = in_array(strtolower($priceEditable), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PRICE_EDITABLE, $priceEditable, $comparison);
    }

    /**
     * Filter the query on the article_new_price column
     *
     * Example usage:
     * <code>
     * $query->filterByNewPrice(1234); // WHERE article_new_price = 1234
     * $query->filterByNewPrice(array(12, 34)); // WHERE article_new_price IN (12, 34)
     * $query->filterByNewPrice(array('min' => 12)); // WHERE article_new_price > 12
     * </code>
     *
     * @param     mixed $newPrice The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByNewPrice($newPrice = null, $comparison = null)
    {
        if (is_array($newPrice)) {
            $useMinMax = false;
            if (isset($newPrice['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_NEW_PRICE, $newPrice['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($newPrice['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_NEW_PRICE, $newPrice['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_NEW_PRICE, $newPrice, $comparison);
    }

    /**
     * Filter the query on the article_category column
     *
     * Example usage:
     * <code>
     * $query->filterByCategory('fooValue');   // WHERE article_category = 'fooValue'
     * $query->filterByCategory('%fooValue%', Criteria::LIKE); // WHERE article_category LIKE '%fooValue%'
     * </code>
     *
     * @param     string $category The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByCategory($category = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($category)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_CATEGORY, $category, $comparison);
    }

    /**
     * Filter the query on the article_tva column
     *
     * Example usage:
     * <code>
     * $query->filterByTva(1234); // WHERE article_tva = 1234
     * $query->filterByTva(array(12, 34)); // WHERE article_tva IN (12, 34)
     * $query->filterByTva(array('min' => 12)); // WHERE article_tva > 12
     * </code>
     *
     * @param     mixed $tva The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByTva($tva = null, $comparison = null)
    {
        if (is_array($tva)) {
            $useMinMax = false;
            if (isset($tva['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_TVA, $tva['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($tva['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_TVA, $tva['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_TVA, $tva, $comparison);
    }

    /**
     * Filter the query on the article_pdf_ean column
     *
     * Example usage:
     * <code>
     * $query->filterByPdfEan(1234); // WHERE article_pdf_ean = 1234
     * $query->filterByPdfEan(array(12, 34)); // WHERE article_pdf_ean IN (12, 34)
     * $query->filterByPdfEan(array('min' => 12)); // WHERE article_pdf_ean > 12
     * </code>
     *
     * @param     mixed $pdfEan The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByPdfEan($pdfEan = null, $comparison = null)
    {
        if (is_array($pdfEan)) {
            $useMinMax = false;
            if (isset($pdfEan['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PDF_EAN, $pdfEan['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pdfEan['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PDF_EAN, $pdfEan['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PDF_EAN, $pdfEan, $comparison);
    }

    /**
     * Filter the query on the article_pdf_version column
     *
     * Example usage:
     * <code>
     * $query->filterByPdfVersion('fooValue');   // WHERE article_pdf_version = 'fooValue'
     * $query->filterByPdfVersion('%fooValue%', Criteria::LIKE); // WHERE article_pdf_version LIKE '%fooValue%'
     * </code>
     *
     * @param     string $pdfVersion The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByPdfVersion($pdfVersion = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($pdfVersion)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PDF_VERSION, $pdfVersion, $comparison);
    }

    /**
     * Filter the query on the article_epub_ean column
     *
     * Example usage:
     * <code>
     * $query->filterByEpubEan(1234); // WHERE article_epub_ean = 1234
     * $query->filterByEpubEan(array(12, 34)); // WHERE article_epub_ean IN (12, 34)
     * $query->filterByEpubEan(array('min' => 12)); // WHERE article_epub_ean > 12
     * </code>
     *
     * @param     mixed $epubEan The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByEpubEan($epubEan = null, $comparison = null)
    {
        if (is_array($epubEan)) {
            $useMinMax = false;
            if (isset($epubEan['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_EPUB_EAN, $epubEan['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($epubEan['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_EPUB_EAN, $epubEan['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_EPUB_EAN, $epubEan, $comparison);
    }

    /**
     * Filter the query on the article_epub_version column
     *
     * Example usage:
     * <code>
     * $query->filterByEpubVersion('fooValue');   // WHERE article_epub_version = 'fooValue'
     * $query->filterByEpubVersion('%fooValue%', Criteria::LIKE); // WHERE article_epub_version LIKE '%fooValue%'
     * </code>
     *
     * @param     string $epubVersion The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByEpubVersion($epubVersion = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($epubVersion)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_EPUB_VERSION, $epubVersion, $comparison);
    }

    /**
     * Filter the query on the article_azw_ean column
     *
     * Example usage:
     * <code>
     * $query->filterByAzwEan(1234); // WHERE article_azw_ean = 1234
     * $query->filterByAzwEan(array(12, 34)); // WHERE article_azw_ean IN (12, 34)
     * $query->filterByAzwEan(array('min' => 12)); // WHERE article_azw_ean > 12
     * </code>
     *
     * @param     mixed $azwEan The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByAzwEan($azwEan = null, $comparison = null)
    {
        if (is_array($azwEan)) {
            $useMinMax = false;
            if (isset($azwEan['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_AZW_EAN, $azwEan['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($azwEan['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_AZW_EAN, $azwEan['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_AZW_EAN, $azwEan, $comparison);
    }

    /**
     * Filter the query on the article_azw_version column
     *
     * Example usage:
     * <code>
     * $query->filterByAzwVersion('fooValue');   // WHERE article_azw_version = 'fooValue'
     * $query->filterByAzwVersion('%fooValue%', Criteria::LIKE); // WHERE article_azw_version LIKE '%fooValue%'
     * </code>
     *
     * @param     string $azwVersion The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByAzwVersion($azwVersion = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($azwVersion)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_AZW_VERSION, $azwVersion, $comparison);
    }

    /**
     * Filter the query on the article_pages column
     *
     * Example usage:
     * <code>
     * $query->filterByPages(1234); // WHERE article_pages = 1234
     * $query->filterByPages(array(12, 34)); // WHERE article_pages IN (12, 34)
     * $query->filterByPages(array('min' => 12)); // WHERE article_pages > 12
     * </code>
     *
     * @param     mixed $pages The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByPages($pages = null, $comparison = null)
    {
        if (is_array($pages)) {
            $useMinMax = false;
            if (isset($pages['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PAGES, $pages['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pages['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PAGES, $pages['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PAGES, $pages, $comparison);
    }

    /**
     * Filter the query on the article_weight column
     *
     * Example usage:
     * <code>
     * $query->filterByWeight(1234); // WHERE article_weight = 1234
     * $query->filterByWeight(array(12, 34)); // WHERE article_weight IN (12, 34)
     * $query->filterByWeight(array('min' => 12)); // WHERE article_weight > 12
     * </code>
     *
     * @param     mixed $weight The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByWeight($weight = null, $comparison = null)
    {
        if (is_array($weight)) {
            $useMinMax = false;
            if (isset($weight['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_WEIGHT, $weight['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($weight['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_WEIGHT, $weight['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_WEIGHT, $weight, $comparison);
    }

    /**
     * Filter the query on the article_shaping column
     *
     * Example usage:
     * <code>
     * $query->filterByShaping('fooValue');   // WHERE article_shaping = 'fooValue'
     * $query->filterByShaping('%fooValue%', Criteria::LIKE); // WHERE article_shaping LIKE '%fooValue%'
     * </code>
     *
     * @param     string $shaping The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByShaping($shaping = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($shaping)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_SHAPING, $shaping, $comparison);
    }

    /**
     * Filter the query on the article_format column
     *
     * Example usage:
     * <code>
     * $query->filterByFormat('fooValue');   // WHERE article_format = 'fooValue'
     * $query->filterByFormat('%fooValue%', Criteria::LIKE); // WHERE article_format LIKE '%fooValue%'
     * </code>
     *
     * @param     string $format The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByFormat($format = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($format)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_FORMAT, $format, $comparison);
    }

    /**
     * Filter the query on the article_printing_process column
     *
     * Example usage:
     * <code>
     * $query->filterByPrintingProcess('fooValue');   // WHERE article_printing_process = 'fooValue'
     * $query->filterByPrintingProcess('%fooValue%', Criteria::LIKE); // WHERE article_printing_process LIKE '%fooValue%'
     * </code>
     *
     * @param     string $printingProcess The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByPrintingProcess($printingProcess = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($printingProcess)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PRINTING_PROCESS, $printingProcess, $comparison);
    }

    /**
     * Filter the query on the article_age_min column
     *
     * Example usage:
     * <code>
     * $query->filterByAgeMin(1234); // WHERE article_age_min = 1234
     * $query->filterByAgeMin(array(12, 34)); // WHERE article_age_min IN (12, 34)
     * $query->filterByAgeMin(array('min' => 12)); // WHERE article_age_min > 12
     * </code>
     *
     * @param     mixed $ageMin The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByAgeMin($ageMin = null, $comparison = null)
    {
        if (is_array($ageMin)) {
            $useMinMax = false;
            if (isset($ageMin['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_AGE_MIN, $ageMin['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ageMin['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_AGE_MIN, $ageMin['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_AGE_MIN, $ageMin, $comparison);
    }

    /**
     * Filter the query on the article_age_max column
     *
     * Example usage:
     * <code>
     * $query->filterByAgeMax(1234); // WHERE article_age_max = 1234
     * $query->filterByAgeMax(array(12, 34)); // WHERE article_age_max IN (12, 34)
     * $query->filterByAgeMax(array('min' => 12)); // WHERE article_age_max > 12
     * </code>
     *
     * @param     mixed $ageMax The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByAgeMax($ageMax = null, $comparison = null)
    {
        if (is_array($ageMax)) {
            $useMinMax = false;
            if (isset($ageMax['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_AGE_MAX, $ageMax['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ageMax['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_AGE_MAX, $ageMax['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_AGE_MAX, $ageMax, $comparison);
    }

    /**
     * Filter the query on the article_summary column
     *
     * Example usage:
     * <code>
     * $query->filterBySummary('fooValue');   // WHERE article_summary = 'fooValue'
     * $query->filterBySummary('%fooValue%', Criteria::LIKE); // WHERE article_summary LIKE '%fooValue%'
     * </code>
     *
     * @param     string $summary The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterBySummary($summary = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($summary)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_SUMMARY, $summary, $comparison);
    }

    /**
     * Filter the query on the article_contents column
     *
     * Example usage:
     * <code>
     * $query->filterByContents('fooValue');   // WHERE article_contents = 'fooValue'
     * $query->filterByContents('%fooValue%', Criteria::LIKE); // WHERE article_contents LIKE '%fooValue%'
     * </code>
     *
     * @param     string $contents The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByContents($contents = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($contents)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_CONTENTS, $contents, $comparison);
    }

    /**
     * Filter the query on the article_bonus column
     *
     * Example usage:
     * <code>
     * $query->filterByBonus('fooValue');   // WHERE article_bonus = 'fooValue'
     * $query->filterByBonus('%fooValue%', Criteria::LIKE); // WHERE article_bonus LIKE '%fooValue%'
     * </code>
     *
     * @param     string $bonus The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByBonus($bonus = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($bonus)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_BONUS, $bonus, $comparison);
    }

    /**
     * Filter the query on the article_catchline column
     *
     * Example usage:
     * <code>
     * $query->filterByCatchline('fooValue');   // WHERE article_catchline = 'fooValue'
     * $query->filterByCatchline('%fooValue%', Criteria::LIKE); // WHERE article_catchline LIKE '%fooValue%'
     * </code>
     *
     * @param     string $catchline The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByCatchline($catchline = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($catchline)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_CATCHLINE, $catchline, $comparison);
    }

    /**
     * Filter the query on the article_biography column
     *
     * Example usage:
     * <code>
     * $query->filterByBiography('fooValue');   // WHERE article_biography = 'fooValue'
     * $query->filterByBiography('%fooValue%', Criteria::LIKE); // WHERE article_biography LIKE '%fooValue%'
     * </code>
     *
     * @param     string $biography The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByBiography($biography = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($biography)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_BIOGRAPHY, $biography, $comparison);
    }

    /**
     * Filter the query on the article_motsv column
     *
     * Example usage:
     * <code>
     * $query->filterByMotsv('fooValue');   // WHERE article_motsv = 'fooValue'
     * $query->filterByMotsv('%fooValue%', Criteria::LIKE); // WHERE article_motsv LIKE '%fooValue%'
     * </code>
     *
     * @param     string $motsv The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByMotsv($motsv = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($motsv)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_MOTSV, $motsv, $comparison);
    }

    /**
     * Filter the query on the article_copyright column
     *
     * Example usage:
     * <code>
     * $query->filterByCopyright(1234); // WHERE article_copyright = 1234
     * $query->filterByCopyright(array(12, 34)); // WHERE article_copyright IN (12, 34)
     * $query->filterByCopyright(array('min' => 12)); // WHERE article_copyright > 12
     * </code>
     *
     * @param     mixed $copyright The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByCopyright($copyright = null, $comparison = null)
    {
        if (is_array($copyright)) {
            $useMinMax = false;
            if (isset($copyright['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_COPYRIGHT, $copyright['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($copyright['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_COPYRIGHT, $copyright['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_COPYRIGHT, $copyright, $comparison);
    }

    /**
     * Filter the query on the article_pubdate column
     *
     * Example usage:
     * <code>
     * $query->filterByPubdate('2011-03-14'); // WHERE article_pubdate = '2011-03-14'
     * $query->filterByPubdate('now'); // WHERE article_pubdate = '2011-03-14'
     * $query->filterByPubdate(array('max' => 'yesterday')); // WHERE article_pubdate > '2011-03-13'
     * </code>
     *
     * @param     mixed $pubdate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByPubdate($pubdate = null, $comparison = null)
    {
        if (is_array($pubdate)) {
            $useMinMax = false;
            if (isset($pubdate['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PUBDATE, $pubdate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pubdate['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PUBDATE, $pubdate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PUBDATE, $pubdate, $comparison);
    }

    /**
     * Filter the query on the article_keywords column
     *
     * Example usage:
     * <code>
     * $query->filterByKeywords('fooValue');   // WHERE article_keywords = 'fooValue'
     * $query->filterByKeywords('%fooValue%', Criteria::LIKE); // WHERE article_keywords LIKE '%fooValue%'
     * </code>
     *
     * @param     string $keywords The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByKeywords($keywords = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($keywords)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_KEYWORDS, $keywords, $comparison);
    }

    /**
     * Filter the query on the article_links column
     *
     * Example usage:
     * <code>
     * $query->filterByLinks('fooValue');   // WHERE article_links = 'fooValue'
     * $query->filterByLinks('%fooValue%', Criteria::LIKE); // WHERE article_links LIKE '%fooValue%'
     * </code>
     *
     * @param     string $links The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByLinks($links = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($links)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_LINKS, $links, $comparison);
    }

    /**
     * Filter the query on the article_keywords_generated column
     *
     * Example usage:
     * <code>
     * $query->filterByKeywordsGenerated('2011-03-14'); // WHERE article_keywords_generated = '2011-03-14'
     * $query->filterByKeywordsGenerated('now'); // WHERE article_keywords_generated = '2011-03-14'
     * $query->filterByKeywordsGenerated(array('max' => 'yesterday')); // WHERE article_keywords_generated > '2011-03-13'
     * </code>
     *
     * @param     mixed $keywordsGenerated The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByKeywordsGenerated($keywordsGenerated = null, $comparison = null)
    {
        if (is_array($keywordsGenerated)) {
            $useMinMax = false;
            if (isset($keywordsGenerated['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_KEYWORDS_GENERATED, $keywordsGenerated['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($keywordsGenerated['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_KEYWORDS_GENERATED, $keywordsGenerated['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_KEYWORDS_GENERATED, $keywordsGenerated, $comparison);
    }

    /**
     * Filter the query on the article_publisher_stock column
     *
     * Example usage:
     * <code>
     * $query->filterByPublisherStock(1234); // WHERE article_publisher_stock = 1234
     * $query->filterByPublisherStock(array(12, 34)); // WHERE article_publisher_stock IN (12, 34)
     * $query->filterByPublisherStock(array('min' => 12)); // WHERE article_publisher_stock > 12
     * </code>
     *
     * @param     mixed $publisherStock The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByPublisherStock($publisherStock = null, $comparison = null)
    {
        if (is_array($publisherStock)) {
            $useMinMax = false;
            if (isset($publisherStock['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PUBLISHER_STOCK, $publisherStock['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publisherStock['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PUBLISHER_STOCK, $publisherStock['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PUBLISHER_STOCK, $publisherStock, $comparison);
    }

    /**
     * Filter the query on the article_hits column
     *
     * Example usage:
     * <code>
     * $query->filterByHits(1234); // WHERE article_hits = 1234
     * $query->filterByHits(array(12, 34)); // WHERE article_hits IN (12, 34)
     * $query->filterByHits(array('min' => 12)); // WHERE article_hits > 12
     * </code>
     *
     * @param     mixed $hits The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByHits($hits = null, $comparison = null)
    {
        if (is_array($hits)) {
            $useMinMax = false;
            if (isset($hits['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_HITS, $hits['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($hits['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_HITS, $hits['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_HITS, $hits, $comparison);
    }

    /**
     * Filter the query on the article_editing_user column
     *
     * Example usage:
     * <code>
     * $query->filterByEditingUser(1234); // WHERE article_editing_user = 1234
     * $query->filterByEditingUser(array(12, 34)); // WHERE article_editing_user IN (12, 34)
     * $query->filterByEditingUser(array('min' => 12)); // WHERE article_editing_user > 12
     * </code>
     *
     * @param     mixed $editingUser The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByEditingUser($editingUser = null, $comparison = null)
    {
        if (is_array($editingUser)) {
            $useMinMax = false;
            if (isset($editingUser['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_EDITING_USER, $editingUser['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($editingUser['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_EDITING_USER, $editingUser['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_EDITING_USER, $editingUser, $comparison);
    }

    /**
     * Filter the query on the article_insert column
     *
     * Example usage:
     * <code>
     * $query->filterByInsert('2011-03-14'); // WHERE article_insert = '2011-03-14'
     * $query->filterByInsert('now'); // WHERE article_insert = '2011-03-14'
     * $query->filterByInsert(array('max' => 'yesterday')); // WHERE article_insert > '2011-03-13'
     * </code>
     *
     * @param     mixed $insert The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByInsert($insert = null, $comparison = null)
    {
        if (is_array($insert)) {
            $useMinMax = false;
            if (isset($insert['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_INSERT, $insert['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($insert['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_INSERT, $insert['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_INSERT, $insert, $comparison);
    }

    /**
     * Filter the query on the article_update column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdate('2011-03-14'); // WHERE article_update = '2011-03-14'
     * $query->filterByUpdate('now'); // WHERE article_update = '2011-03-14'
     * $query->filterByUpdate(array('max' => 'yesterday')); // WHERE article_update > '2011-03-13'
     * </code>
     *
     * @param     mixed $update The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByUpdate($update = null, $comparison = null)
    {
        if (is_array($update)) {
            $useMinMax = false;
            if (isset($update['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_UPDATE, $update['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($update['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_UPDATE, $update['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_UPDATE, $update, $comparison);
    }

    /**
     * Filter the query on the article_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE article_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE article_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE article_created > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_CREATED, $createdAt, $comparison);
    }

    /**
     * Filter the query on the article_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE article_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE article_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE article_updated > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the article_deleted column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletedAt('2011-03-14'); // WHERE article_deleted = '2011-03-14'
     * $query->filterByDeletedAt('now'); // WHERE article_deleted = '2011-03-14'
     * $query->filterByDeletedAt(array('max' => 'yesterday')); // WHERE article_deleted > '2011-03-13'
     * </code>
     *
     * @param     mixed $deletedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByDeletedAt($deletedAt = null, $comparison = null)
    {
        if (is_array($deletedAt)) {
            $useMinMax = false;
            if (isset($deletedAt['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_DELETED, $deletedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletedAt['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_DELETED, $deletedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_DELETED, $deletedAt, $comparison);
    }

    /**
     * Filter the query on the article_done column
     *
     * Example usage:
     * <code>
     * $query->filterByDone(true); // WHERE article_done = true
     * $query->filterByDone('yes'); // WHERE article_done = true
     * </code>
     *
     * @param     boolean|string $done The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByDone($done = null, $comparison = null)
    {
        if (is_string($done)) {
            $done = in_array(strtolower($done), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_DONE, $done, $comparison);
    }

    /**
     * Filter the query on the article_to_check column
     *
     * Example usage:
     * <code>
     * $query->filterByToCheck(true); // WHERE article_to_check = true
     * $query->filterByToCheck('yes'); // WHERE article_to_check = true
     * </code>
     *
     * @param     boolean|string $toCheck The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByToCheck($toCheck = null, $comparison = null)
    {
        if (is_string($toCheck)) {
            $toCheck = in_array(strtolower($toCheck), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_TO_CHECK, $toCheck, $comparison);
    }

    /**
     * Filter the query on the article_pushed_to_data column
     *
     * Example usage:
     * <code>
     * $query->filterByPushedToData('2011-03-14'); // WHERE article_pushed_to_data = '2011-03-14'
     * $query->filterByPushedToData('now'); // WHERE article_pushed_to_data = '2011-03-14'
     * $query->filterByPushedToData(array('max' => 'yesterday')); // WHERE article_pushed_to_data > '2011-03-13'
     * </code>
     *
     * @param     mixed $pushedToData The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByPushedToData($pushedToData = null, $comparison = null)
    {
        if (is_array($pushedToData)) {
            $useMinMax = false;
            if (isset($pushedToData['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PUSHED_TO_DATA, $pushedToData['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pushedToData['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PUSHED_TO_DATA, $pushedToData['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PUSHED_TO_DATA, $pushedToData, $comparison);
    }

    /**
     * Filter the query on the article_deletion_by column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletionBy(1234); // WHERE article_deletion_by = 1234
     * $query->filterByDeletionBy(array(12, 34)); // WHERE article_deletion_by IN (12, 34)
     * $query->filterByDeletionBy(array('min' => 12)); // WHERE article_deletion_by > 12
     * </code>
     *
     * @param     mixed $deletionBy The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByDeletionBy($deletionBy = null, $comparison = null)
    {
        if (is_array($deletionBy)) {
            $useMinMax = false;
            if (isset($deletionBy['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_DELETION_BY, $deletionBy['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletionBy['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_DELETION_BY, $deletionBy['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_DELETION_BY, $deletionBy, $comparison);
    }

    /**
     * Filter the query on the article_deletion_date column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletionDate('2011-03-14'); // WHERE article_deletion_date = '2011-03-14'
     * $query->filterByDeletionDate('now'); // WHERE article_deletion_date = '2011-03-14'
     * $query->filterByDeletionDate(array('max' => 'yesterday')); // WHERE article_deletion_date > '2011-03-13'
     * </code>
     *
     * @param     mixed $deletionDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByDeletionDate($deletionDate = null, $comparison = null)
    {
        if (is_array($deletionDate)) {
            $useMinMax = false;
            if (isset($deletionDate['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_DELETION_DATE, $deletionDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletionDate['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_DELETION_DATE, $deletionDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_DELETION_DATE, $deletionDate, $comparison);
    }

    /**
     * Filter the query on the article_deletion_reason column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletionReason('fooValue');   // WHERE article_deletion_reason = 'fooValue'
     * $query->filterByDeletionReason('%fooValue%', Criteria::LIKE); // WHERE article_deletion_reason LIKE '%fooValue%'
     * </code>
     *
     * @param     string $deletionReason The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByDeletionReason($deletionReason = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($deletionReason)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_DELETION_REASON, $deletionReason, $comparison);
    }

    /**
     * Filter the query by a related \Model\Role object
     *
     * @param \Model\Role|ObjectCollection $role the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildArticleQuery The current query, for fluid interface
     */
    public function filterByRole($role, $comparison = null)
    {
        if ($role instanceof \Model\Role) {
            return $this
                ->addUsingAlias(ArticleTableMap::COL_ARTICLE_ID, $role->getArticleId(), $comparison);
        } elseif ($role instanceof ObjectCollection) {
            return $this
                ->useRoleQuery()
                ->filterByPrimaryKeys($role->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByRole() only accepts arguments of type \Model\Role or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Role relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function joinRole($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Role');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Role');
        }

        return $this;
    }

    /**
     * Use the Role relation Role object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\RoleQuery A secondary query class using the current class as primary query
     */
    public function useRoleQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinRole($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Role', '\Model\RoleQuery');
    }

    /**
     * Use the Role relation Role object
     *
     * @param callable(\Model\RoleQuery):\Model\RoleQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withRoleQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useRoleQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }
    /**
     * Use the relation to Role table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string $typeOfExists Either ExistsCriterion::TYPE_EXISTS or ExistsCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\RoleQuery The inner query object of the EXISTS statement
     */
    public function useRoleExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        return $this->useExistsQuery('Role', $modelAlias, $queryClass, $typeOfExists);
    }

    /**
     * Use the relation to Role table for a NOT EXISTS query.
     *
     * @see useRoleExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\RoleQuery The inner query object of the NOT EXISTS statement
     */
    public function useRoleNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        return $this->useExistsQuery('Role', $modelAlias, $queryClass, 'NOT EXISTS');
    }
    /**
     * Exclude object from result
     *
     * @param   ChildArticle $article Object to remove from the list of results
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function prune($article = null)
    {
        if ($article) {
            $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_ID, $article->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the articles table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ArticleTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ArticleTableMap::clearInstancePool();
            ArticleTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ArticleTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ArticleTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ArticleTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ArticleTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildArticleQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildArticleQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(ArticleTableMap::COL_ARTICLE_UPDATED);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildArticleQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(ArticleTableMap::COL_ARTICLE_UPDATED);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildArticleQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(ArticleTableMap::COL_ARTICLE_CREATED);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildArticleQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildArticleQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(ArticleTableMap::COL_ARTICLE_CREATED);
    }

} // ArticleQuery
