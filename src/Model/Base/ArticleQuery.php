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
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `articles` table.
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
 * @method     ChildArticleQuery orderByCollectionName($order = Criteria::ASC) Order by the article_collection column
 * @method     ChildArticleQuery orderByNumber($order = Criteria::ASC) Order by the article_number column
 * @method     ChildArticleQuery orderByPublisherId($order = Criteria::ASC) Order by the publisher_id column
 * @method     ChildArticleQuery orderByPublisherName($order = Criteria::ASC) Order by the article_publisher column
 * @method     ChildArticleQuery orderByCycleId($order = Criteria::ASC) Order by the cycle_id column
 * @method     ChildArticleQuery orderByCycleName($order = Criteria::ASC) Order by the article_cycle column
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
 * @method     ChildArticleQuery orderByComputedLinks($order = Criteria::ASC) Order by the article_links column
 * @method     ChildArticleQuery orderByKeywordsGenerated($order = Criteria::ASC) Order by the article_keywords_generated column
 * @method     ChildArticleQuery orderByPublisherStock($order = Criteria::ASC) Order by the article_publisher_stock column
 * @method     ChildArticleQuery orderByHits($order = Criteria::ASC) Order by the article_hits column
 * @method     ChildArticleQuery orderByEditingUser($order = Criteria::ASC) Order by the article_editing_user column
 * @method     ChildArticleQuery orderByInsert($order = Criteria::ASC) Order by the article_insert column
 * @method     ChildArticleQuery orderByUpdate($order = Criteria::ASC) Order by the article_update column
 * @method     ChildArticleQuery orderByCreatedAt($order = Criteria::ASC) Order by the article_created column
 * @method     ChildArticleQuery orderByUpdatedAt($order = Criteria::ASC) Order by the article_updated column
 * @method     ChildArticleQuery orderByDone($order = Criteria::ASC) Order by the article_done column
 * @method     ChildArticleQuery orderByToCheck($order = Criteria::ASC) Order by the article_to_check column
 * @method     ChildArticleQuery orderByDeletionBy($order = Criteria::ASC) Order by the article_deletion_by column
 * @method     ChildArticleQuery orderByDeletionDate($order = Criteria::ASC) Order by the article_deletion_date column
 * @method     ChildArticleQuery orderByDeletionReason($order = Criteria::ASC) Order by the article_deletion_reason column
 * @method     ChildArticleQuery orderByLemoninkMasterId($order = Criteria::ASC) Order by the lemonink_master_id column
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
 * @method     ChildArticleQuery groupByCollectionName() Group by the article_collection column
 * @method     ChildArticleQuery groupByNumber() Group by the article_number column
 * @method     ChildArticleQuery groupByPublisherId() Group by the publisher_id column
 * @method     ChildArticleQuery groupByPublisherName() Group by the article_publisher column
 * @method     ChildArticleQuery groupByCycleId() Group by the cycle_id column
 * @method     ChildArticleQuery groupByCycleName() Group by the article_cycle column
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
 * @method     ChildArticleQuery groupByComputedLinks() Group by the article_links column
 * @method     ChildArticleQuery groupByKeywordsGenerated() Group by the article_keywords_generated column
 * @method     ChildArticleQuery groupByPublisherStock() Group by the article_publisher_stock column
 * @method     ChildArticleQuery groupByHits() Group by the article_hits column
 * @method     ChildArticleQuery groupByEditingUser() Group by the article_editing_user column
 * @method     ChildArticleQuery groupByInsert() Group by the article_insert column
 * @method     ChildArticleQuery groupByUpdate() Group by the article_update column
 * @method     ChildArticleQuery groupByCreatedAt() Group by the article_created column
 * @method     ChildArticleQuery groupByUpdatedAt() Group by the article_updated column
 * @method     ChildArticleQuery groupByDone() Group by the article_done column
 * @method     ChildArticleQuery groupByToCheck() Group by the article_to_check column
 * @method     ChildArticleQuery groupByDeletionBy() Group by the article_deletion_by column
 * @method     ChildArticleQuery groupByDeletionDate() Group by the article_deletion_date column
 * @method     ChildArticleQuery groupByDeletionReason() Group by the article_deletion_reason column
 * @method     ChildArticleQuery groupByLemoninkMasterId() Group by the lemonink_master_id column
 *
 * @method     ChildArticleQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildArticleQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildArticleQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildArticleQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildArticleQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildArticleQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildArticleQuery leftJoinPublisher($relationAlias = null) Adds a LEFT JOIN clause to the query using the Publisher relation
 * @method     ChildArticleQuery rightJoinPublisher($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Publisher relation
 * @method     ChildArticleQuery innerJoinPublisher($relationAlias = null) Adds a INNER JOIN clause to the query using the Publisher relation
 *
 * @method     ChildArticleQuery joinWithPublisher($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Publisher relation
 *
 * @method     ChildArticleQuery leftJoinWithPublisher() Adds a LEFT JOIN clause and with to the query using the Publisher relation
 * @method     ChildArticleQuery rightJoinWithPublisher() Adds a RIGHT JOIN clause and with to the query using the Publisher relation
 * @method     ChildArticleQuery innerJoinWithPublisher() Adds a INNER JOIN clause and with to the query using the Publisher relation
 *
 * @method     ChildArticleQuery leftJoinBookCollection($relationAlias = null) Adds a LEFT JOIN clause to the query using the BookCollection relation
 * @method     ChildArticleQuery rightJoinBookCollection($relationAlias = null) Adds a RIGHT JOIN clause to the query using the BookCollection relation
 * @method     ChildArticleQuery innerJoinBookCollection($relationAlias = null) Adds a INNER JOIN clause to the query using the BookCollection relation
 *
 * @method     ChildArticleQuery joinWithBookCollection($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the BookCollection relation
 *
 * @method     ChildArticleQuery leftJoinWithBookCollection() Adds a LEFT JOIN clause and with to the query using the BookCollection relation
 * @method     ChildArticleQuery rightJoinWithBookCollection() Adds a RIGHT JOIN clause and with to the query using the BookCollection relation
 * @method     ChildArticleQuery innerJoinWithBookCollection() Adds a INNER JOIN clause and with to the query using the BookCollection relation
 *
 * @method     ChildArticleQuery leftJoinCycle($relationAlias = null) Adds a LEFT JOIN clause to the query using the Cycle relation
 * @method     ChildArticleQuery rightJoinCycle($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Cycle relation
 * @method     ChildArticleQuery innerJoinCycle($relationAlias = null) Adds a INNER JOIN clause to the query using the Cycle relation
 *
 * @method     ChildArticleQuery joinWithCycle($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Cycle relation
 *
 * @method     ChildArticleQuery leftJoinWithCycle() Adds a LEFT JOIN clause and with to the query using the Cycle relation
 * @method     ChildArticleQuery rightJoinWithCycle() Adds a RIGHT JOIN clause and with to the query using the Cycle relation
 * @method     ChildArticleQuery innerJoinWithCycle() Adds a INNER JOIN clause and with to the query using the Cycle relation
 *
 * @method     ChildArticleQuery leftJoinFile($relationAlias = null) Adds a LEFT JOIN clause to the query using the File relation
 * @method     ChildArticleQuery rightJoinFile($relationAlias = null) Adds a RIGHT JOIN clause to the query using the File relation
 * @method     ChildArticleQuery innerJoinFile($relationAlias = null) Adds a INNER JOIN clause to the query using the File relation
 *
 * @method     ChildArticleQuery joinWithFile($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the File relation
 *
 * @method     ChildArticleQuery leftJoinWithFile() Adds a LEFT JOIN clause and with to the query using the File relation
 * @method     ChildArticleQuery rightJoinWithFile() Adds a RIGHT JOIN clause and with to the query using the File relation
 * @method     ChildArticleQuery innerJoinWithFile() Adds a INNER JOIN clause and with to the query using the File relation
 *
 * @method     ChildArticleQuery leftJoinImage($relationAlias = null) Adds a LEFT JOIN clause to the query using the Image relation
 * @method     ChildArticleQuery rightJoinImage($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Image relation
 * @method     ChildArticleQuery innerJoinImage($relationAlias = null) Adds a INNER JOIN clause to the query using the Image relation
 *
 * @method     ChildArticleQuery joinWithImage($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Image relation
 *
 * @method     ChildArticleQuery leftJoinWithImage() Adds a LEFT JOIN clause and with to the query using the Image relation
 * @method     ChildArticleQuery rightJoinWithImage() Adds a RIGHT JOIN clause and with to the query using the Image relation
 * @method     ChildArticleQuery innerJoinWithImage() Adds a INNER JOIN clause and with to the query using the Image relation
 *
 * @method     ChildArticleQuery leftJoinInvitationsArticles($relationAlias = null) Adds a LEFT JOIN clause to the query using the InvitationsArticles relation
 * @method     ChildArticleQuery rightJoinInvitationsArticles($relationAlias = null) Adds a RIGHT JOIN clause to the query using the InvitationsArticles relation
 * @method     ChildArticleQuery innerJoinInvitationsArticles($relationAlias = null) Adds a INNER JOIN clause to the query using the InvitationsArticles relation
 *
 * @method     ChildArticleQuery joinWithInvitationsArticles($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the InvitationsArticles relation
 *
 * @method     ChildArticleQuery leftJoinWithInvitationsArticles() Adds a LEFT JOIN clause and with to the query using the InvitationsArticles relation
 * @method     ChildArticleQuery rightJoinWithInvitationsArticles() Adds a RIGHT JOIN clause and with to the query using the InvitationsArticles relation
 * @method     ChildArticleQuery innerJoinWithInvitationsArticles() Adds a INNER JOIN clause and with to the query using the InvitationsArticles relation
 *
 * @method     ChildArticleQuery leftJoinLink($relationAlias = null) Adds a LEFT JOIN clause to the query using the Link relation
 * @method     ChildArticleQuery rightJoinLink($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Link relation
 * @method     ChildArticleQuery innerJoinLink($relationAlias = null) Adds a INNER JOIN clause to the query using the Link relation
 *
 * @method     ChildArticleQuery joinWithLink($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Link relation
 *
 * @method     ChildArticleQuery leftJoinWithLink() Adds a LEFT JOIN clause and with to the query using the Link relation
 * @method     ChildArticleQuery rightJoinWithLink() Adds a RIGHT JOIN clause and with to the query using the Link relation
 * @method     ChildArticleQuery innerJoinWithLink() Adds a INNER JOIN clause and with to the query using the Link relation
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
 * @method     ChildArticleQuery leftJoinSpecialOffer($relationAlias = null) Adds a LEFT JOIN clause to the query using the SpecialOffer relation
 * @method     ChildArticleQuery rightJoinSpecialOffer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SpecialOffer relation
 * @method     ChildArticleQuery innerJoinSpecialOffer($relationAlias = null) Adds a INNER JOIN clause to the query using the SpecialOffer relation
 *
 * @method     ChildArticleQuery joinWithSpecialOffer($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the SpecialOffer relation
 *
 * @method     ChildArticleQuery leftJoinWithSpecialOffer() Adds a LEFT JOIN clause and with to the query using the SpecialOffer relation
 * @method     ChildArticleQuery rightJoinWithSpecialOffer() Adds a RIGHT JOIN clause and with to the query using the SpecialOffer relation
 * @method     ChildArticleQuery innerJoinWithSpecialOffer() Adds a INNER JOIN clause and with to the query using the SpecialOffer relation
 *
 * @method     ChildArticleQuery leftJoinStock($relationAlias = null) Adds a LEFT JOIN clause to the query using the Stock relation
 * @method     ChildArticleQuery rightJoinStock($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Stock relation
 * @method     ChildArticleQuery innerJoinStock($relationAlias = null) Adds a INNER JOIN clause to the query using the Stock relation
 *
 * @method     ChildArticleQuery joinWithStock($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Stock relation
 *
 * @method     ChildArticleQuery leftJoinWithStock() Adds a LEFT JOIN clause and with to the query using the Stock relation
 * @method     ChildArticleQuery rightJoinWithStock() Adds a RIGHT JOIN clause and with to the query using the Stock relation
 * @method     ChildArticleQuery innerJoinWithStock() Adds a INNER JOIN clause and with to the query using the Stock relation
 *
 * @method     ChildArticleQuery leftJoinArticleTag($relationAlias = null) Adds a LEFT JOIN clause to the query using the ArticleTag relation
 * @method     ChildArticleQuery rightJoinArticleTag($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ArticleTag relation
 * @method     ChildArticleQuery innerJoinArticleTag($relationAlias = null) Adds a INNER JOIN clause to the query using the ArticleTag relation
 *
 * @method     ChildArticleQuery joinWithArticleTag($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ArticleTag relation
 *
 * @method     ChildArticleQuery leftJoinWithArticleTag() Adds a LEFT JOIN clause and with to the query using the ArticleTag relation
 * @method     ChildArticleQuery rightJoinWithArticleTag() Adds a RIGHT JOIN clause and with to the query using the ArticleTag relation
 * @method     ChildArticleQuery innerJoinWithArticleTag() Adds a INNER JOIN clause and with to the query using the ArticleTag relation
 *
 * @method     \Model\PublisherQuery|\Model\BookCollectionQuery|\Model\CycleQuery|\Model\FileQuery|\Model\ImageQuery|\Model\InvitationsArticlesQuery|\Model\LinkQuery|\Model\RoleQuery|\Model\SpecialOfferQuery|\Model\StockQuery|\Model\ArticleTagQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildArticle|null findOne(?ConnectionInterface $con = null) Return the first ChildArticle matching the query
 * @method     ChildArticle findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildArticle matching the query, or a new ChildArticle object populated from the query conditions when no match is found
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
 * @method     ChildArticle|null findOneByCollectionName(string $article_collection) Return the first ChildArticle filtered by the article_collection column
 * @method     ChildArticle|null findOneByNumber(string $article_number) Return the first ChildArticle filtered by the article_number column
 * @method     ChildArticle|null findOneByPublisherId(int $publisher_id) Return the first ChildArticle filtered by the publisher_id column
 * @method     ChildArticle|null findOneByPublisherName(string $article_publisher) Return the first ChildArticle filtered by the article_publisher column
 * @method     ChildArticle|null findOneByCycleId(int $cycle_id) Return the first ChildArticle filtered by the cycle_id column
 * @method     ChildArticle|null findOneByCycleName(string $article_cycle) Return the first ChildArticle filtered by the article_cycle column
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
 * @method     ChildArticle|null findOneByComputedLinks(string $article_links) Return the first ChildArticle filtered by the article_links column
 * @method     ChildArticle|null findOneByKeywordsGenerated(string $article_keywords_generated) Return the first ChildArticle filtered by the article_keywords_generated column
 * @method     ChildArticle|null findOneByPublisherStock(int $article_publisher_stock) Return the first ChildArticle filtered by the article_publisher_stock column
 * @method     ChildArticle|null findOneByHits(int $article_hits) Return the first ChildArticle filtered by the article_hits column
 * @method     ChildArticle|null findOneByEditingUser(int $article_editing_user) Return the first ChildArticle filtered by the article_editing_user column
 * @method     ChildArticle|null findOneByInsert(string $article_insert) Return the first ChildArticle filtered by the article_insert column
 * @method     ChildArticle|null findOneByUpdate(string $article_update) Return the first ChildArticle filtered by the article_update column
 * @method     ChildArticle|null findOneByCreatedAt(string $article_created) Return the first ChildArticle filtered by the article_created column
 * @method     ChildArticle|null findOneByUpdatedAt(string $article_updated) Return the first ChildArticle filtered by the article_updated column
 * @method     ChildArticle|null findOneByDone(boolean $article_done) Return the first ChildArticle filtered by the article_done column
 * @method     ChildArticle|null findOneByToCheck(boolean $article_to_check) Return the first ChildArticle filtered by the article_to_check column
 * @method     ChildArticle|null findOneByDeletionBy(int $article_deletion_by) Return the first ChildArticle filtered by the article_deletion_by column
 * @method     ChildArticle|null findOneByDeletionDate(string $article_deletion_date) Return the first ChildArticle filtered by the article_deletion_date column
 * @method     ChildArticle|null findOneByDeletionReason(string $article_deletion_reason) Return the first ChildArticle filtered by the article_deletion_reason column
 * @method     ChildArticle|null findOneByLemoninkMasterId(string $lemonink_master_id) Return the first ChildArticle filtered by the lemonink_master_id column
 *
 * @method     ChildArticle requirePk($key, ?ConnectionInterface $con = null) Return the ChildArticle by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOne(?ConnectionInterface $con = null) Return the first ChildArticle matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
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
 * @method     ChildArticle requireOneByCollectionName(string $article_collection) Return the first ChildArticle filtered by the article_collection column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByNumber(string $article_number) Return the first ChildArticle filtered by the article_number column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByPublisherId(int $publisher_id) Return the first ChildArticle filtered by the publisher_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByPublisherName(string $article_publisher) Return the first ChildArticle filtered by the article_publisher column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByCycleId(int $cycle_id) Return the first ChildArticle filtered by the cycle_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByCycleName(string $article_cycle) Return the first ChildArticle filtered by the article_cycle column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
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
 * @method     ChildArticle requireOneByComputedLinks(string $article_links) Return the first ChildArticle filtered by the article_links column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByKeywordsGenerated(string $article_keywords_generated) Return the first ChildArticle filtered by the article_keywords_generated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByPublisherStock(int $article_publisher_stock) Return the first ChildArticle filtered by the article_publisher_stock column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByHits(int $article_hits) Return the first ChildArticle filtered by the article_hits column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByEditingUser(int $article_editing_user) Return the first ChildArticle filtered by the article_editing_user column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByInsert(string $article_insert) Return the first ChildArticle filtered by the article_insert column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByUpdate(string $article_update) Return the first ChildArticle filtered by the article_update column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByCreatedAt(string $article_created) Return the first ChildArticle filtered by the article_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByUpdatedAt(string $article_updated) Return the first ChildArticle filtered by the article_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByDone(boolean $article_done) Return the first ChildArticle filtered by the article_done column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByToCheck(boolean $article_to_check) Return the first ChildArticle filtered by the article_to_check column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByDeletionBy(int $article_deletion_by) Return the first ChildArticle filtered by the article_deletion_by column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByDeletionDate(string $article_deletion_date) Return the first ChildArticle filtered by the article_deletion_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByDeletionReason(string $article_deletion_reason) Return the first ChildArticle filtered by the article_deletion_reason column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByLemoninkMasterId(string $lemonink_master_id) Return the first ChildArticle filtered by the lemonink_master_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildArticle[]|Collection find(?ConnectionInterface $con = null) Return ChildArticle objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildArticle> find(?ConnectionInterface $con = null) Return ChildArticle objects based on current ModelCriteria
 *
 * @method     ChildArticle[]|Collection findById(int|array<int> $article_id) Return ChildArticle objects filtered by the article_id column
 * @psalm-method Collection&\Traversable<ChildArticle> findById(int|array<int> $article_id) Return ChildArticle objects filtered by the article_id column
 * @method     ChildArticle[]|Collection findByItem(int|array<int> $article_item) Return ChildArticle objects filtered by the article_item column
 * @psalm-method Collection&\Traversable<ChildArticle> findByItem(int|array<int> $article_item) Return ChildArticle objects filtered by the article_item column
 * @method     ChildArticle[]|Collection findByTextid(string|array<string> $article_textid) Return ChildArticle objects filtered by the article_textid column
 * @psalm-method Collection&\Traversable<ChildArticle> findByTextid(string|array<string> $article_textid) Return ChildArticle objects filtered by the article_textid column
 * @method     ChildArticle[]|Collection findByEan(string|array<string> $article_ean) Return ChildArticle objects filtered by the article_ean column
 * @psalm-method Collection&\Traversable<ChildArticle> findByEan(string|array<string> $article_ean) Return ChildArticle objects filtered by the article_ean column
 * @method     ChildArticle[]|Collection findByEanOthers(string|array<string> $article_ean_others) Return ChildArticle objects filtered by the article_ean_others column
 * @psalm-method Collection&\Traversable<ChildArticle> findByEanOthers(string|array<string> $article_ean_others) Return ChildArticle objects filtered by the article_ean_others column
 * @method     ChildArticle[]|Collection findByAsin(string|array<string> $article_asin) Return ChildArticle objects filtered by the article_asin column
 * @psalm-method Collection&\Traversable<ChildArticle> findByAsin(string|array<string> $article_asin) Return ChildArticle objects filtered by the article_asin column
 * @method     ChildArticle[]|Collection findByNoosfereId(int|array<int> $article_noosfere_id) Return ChildArticle objects filtered by the article_noosfere_id column
 * @psalm-method Collection&\Traversable<ChildArticle> findByNoosfereId(int|array<int> $article_noosfere_id) Return ChildArticle objects filtered by the article_noosfere_id column
 * @method     ChildArticle[]|Collection findByUrl(string|array<string> $article_url) Return ChildArticle objects filtered by the article_url column
 * @psalm-method Collection&\Traversable<ChildArticle> findByUrl(string|array<string> $article_url) Return ChildArticle objects filtered by the article_url column
 * @method     ChildArticle[]|Collection findByTypeId(int|array<int> $type_id) Return ChildArticle objects filtered by the type_id column
 * @psalm-method Collection&\Traversable<ChildArticle> findByTypeId(int|array<int> $type_id) Return ChildArticle objects filtered by the type_id column
 * @method     ChildArticle[]|Collection findByTitle(string|array<string> $article_title) Return ChildArticle objects filtered by the article_title column
 * @psalm-method Collection&\Traversable<ChildArticle> findByTitle(string|array<string> $article_title) Return ChildArticle objects filtered by the article_title column
 * @method     ChildArticle[]|Collection findByTitleAlphabetic(string|array<string> $article_title_alphabetic) Return ChildArticle objects filtered by the article_title_alphabetic column
 * @psalm-method Collection&\Traversable<ChildArticle> findByTitleAlphabetic(string|array<string> $article_title_alphabetic) Return ChildArticle objects filtered by the article_title_alphabetic column
 * @method     ChildArticle[]|Collection findByTitleOriginal(string|array<string> $article_title_original) Return ChildArticle objects filtered by the article_title_original column
 * @psalm-method Collection&\Traversable<ChildArticle> findByTitleOriginal(string|array<string> $article_title_original) Return ChildArticle objects filtered by the article_title_original column
 * @method     ChildArticle[]|Collection findByTitleOthers(string|array<string> $article_title_others) Return ChildArticle objects filtered by the article_title_others column
 * @psalm-method Collection&\Traversable<ChildArticle> findByTitleOthers(string|array<string> $article_title_others) Return ChildArticle objects filtered by the article_title_others column
 * @method     ChildArticle[]|Collection findBySubtitle(string|array<string> $article_subtitle) Return ChildArticle objects filtered by the article_subtitle column
 * @psalm-method Collection&\Traversable<ChildArticle> findBySubtitle(string|array<string> $article_subtitle) Return ChildArticle objects filtered by the article_subtitle column
 * @method     ChildArticle[]|Collection findByLangCurrent(int|array<int> $article_lang_current) Return ChildArticle objects filtered by the article_lang_current column
 * @psalm-method Collection&\Traversable<ChildArticle> findByLangCurrent(int|array<int> $article_lang_current) Return ChildArticle objects filtered by the article_lang_current column
 * @method     ChildArticle[]|Collection findByLangOriginal(int|array<int> $article_lang_original) Return ChildArticle objects filtered by the article_lang_original column
 * @psalm-method Collection&\Traversable<ChildArticle> findByLangOriginal(int|array<int> $article_lang_original) Return ChildArticle objects filtered by the article_lang_original column
 * @method     ChildArticle[]|Collection findByOriginCountry(int|array<int> $article_origin_country) Return ChildArticle objects filtered by the article_origin_country column
 * @psalm-method Collection&\Traversable<ChildArticle> findByOriginCountry(int|array<int> $article_origin_country) Return ChildArticle objects filtered by the article_origin_country column
 * @method     ChildArticle[]|Collection findByThemeBisac(string|array<string> $article_theme_bisac) Return ChildArticle objects filtered by the article_theme_bisac column
 * @psalm-method Collection&\Traversable<ChildArticle> findByThemeBisac(string|array<string> $article_theme_bisac) Return ChildArticle objects filtered by the article_theme_bisac column
 * @method     ChildArticle[]|Collection findByThemeClil(string|array<string> $article_theme_clil) Return ChildArticle objects filtered by the article_theme_clil column
 * @psalm-method Collection&\Traversable<ChildArticle> findByThemeClil(string|array<string> $article_theme_clil) Return ChildArticle objects filtered by the article_theme_clil column
 * @method     ChildArticle[]|Collection findByThemeDewey(string|array<string> $article_theme_dewey) Return ChildArticle objects filtered by the article_theme_dewey column
 * @psalm-method Collection&\Traversable<ChildArticle> findByThemeDewey(string|array<string> $article_theme_dewey) Return ChildArticle objects filtered by the article_theme_dewey column
 * @method     ChildArticle[]|Collection findByThemeElectre(string|array<string> $article_theme_electre) Return ChildArticle objects filtered by the article_theme_electre column
 * @psalm-method Collection&\Traversable<ChildArticle> findByThemeElectre(string|array<string> $article_theme_electre) Return ChildArticle objects filtered by the article_theme_electre column
 * @method     ChildArticle[]|Collection findBySourceId(int|array<int> $article_source_id) Return ChildArticle objects filtered by the article_source_id column
 * @psalm-method Collection&\Traversable<ChildArticle> findBySourceId(int|array<int> $article_source_id) Return ChildArticle objects filtered by the article_source_id column
 * @method     ChildArticle[]|Collection findByAuthors(string|array<string> $article_authors) Return ChildArticle objects filtered by the article_authors column
 * @psalm-method Collection&\Traversable<ChildArticle> findByAuthors(string|array<string> $article_authors) Return ChildArticle objects filtered by the article_authors column
 * @method     ChildArticle[]|Collection findByAuthorsAlphabetic(string|array<string> $article_authors_alphabetic) Return ChildArticle objects filtered by the article_authors_alphabetic column
 * @psalm-method Collection&\Traversable<ChildArticle> findByAuthorsAlphabetic(string|array<string> $article_authors_alphabetic) Return ChildArticle objects filtered by the article_authors_alphabetic column
 * @method     ChildArticle[]|Collection findByCollectionId(int|array<int> $collection_id) Return ChildArticle objects filtered by the collection_id column
 * @psalm-method Collection&\Traversable<ChildArticle> findByCollectionId(int|array<int> $collection_id) Return ChildArticle objects filtered by the collection_id column
 * @method     ChildArticle[]|Collection findByCollectionName(string|array<string> $article_collection) Return ChildArticle objects filtered by the article_collection column
 * @psalm-method Collection&\Traversable<ChildArticle> findByCollectionName(string|array<string> $article_collection) Return ChildArticle objects filtered by the article_collection column
 * @method     ChildArticle[]|Collection findByNumber(string|array<string> $article_number) Return ChildArticle objects filtered by the article_number column
 * @psalm-method Collection&\Traversable<ChildArticle> findByNumber(string|array<string> $article_number) Return ChildArticle objects filtered by the article_number column
 * @method     ChildArticle[]|Collection findByPublisherId(int|array<int> $publisher_id) Return ChildArticle objects filtered by the publisher_id column
 * @psalm-method Collection&\Traversable<ChildArticle> findByPublisherId(int|array<int> $publisher_id) Return ChildArticle objects filtered by the publisher_id column
 * @method     ChildArticle[]|Collection findByPublisherName(string|array<string> $article_publisher) Return ChildArticle objects filtered by the article_publisher column
 * @psalm-method Collection&\Traversable<ChildArticle> findByPublisherName(string|array<string> $article_publisher) Return ChildArticle objects filtered by the article_publisher column
 * @method     ChildArticle[]|Collection findByCycleId(int|array<int> $cycle_id) Return ChildArticle objects filtered by the cycle_id column
 * @psalm-method Collection&\Traversable<ChildArticle> findByCycleId(int|array<int> $cycle_id) Return ChildArticle objects filtered by the cycle_id column
 * @method     ChildArticle[]|Collection findByCycleName(string|array<string> $article_cycle) Return ChildArticle objects filtered by the article_cycle column
 * @psalm-method Collection&\Traversable<ChildArticle> findByCycleName(string|array<string> $article_cycle) Return ChildArticle objects filtered by the article_cycle column
 * @method     ChildArticle[]|Collection findByTome(string|array<string> $article_tome) Return ChildArticle objects filtered by the article_tome column
 * @psalm-method Collection&\Traversable<ChildArticle> findByTome(string|array<string> $article_tome) Return ChildArticle objects filtered by the article_tome column
 * @method     ChildArticle[]|Collection findByCoverVersion(int|array<int> $article_cover_version) Return ChildArticle objects filtered by the article_cover_version column
 * @psalm-method Collection&\Traversable<ChildArticle> findByCoverVersion(int|array<int> $article_cover_version) Return ChildArticle objects filtered by the article_cover_version column
 * @method     ChildArticle[]|Collection findByAvailability(int|array<int> $article_availability) Return ChildArticle objects filtered by the article_availability column
 * @psalm-method Collection&\Traversable<ChildArticle> findByAvailability(int|array<int> $article_availability) Return ChildArticle objects filtered by the article_availability column
 * @method     ChildArticle[]|Collection findByAvailabilityDilicom(int|array<int> $article_availability_dilicom) Return ChildArticle objects filtered by the article_availability_dilicom column
 * @psalm-method Collection&\Traversable<ChildArticle> findByAvailabilityDilicom(int|array<int> $article_availability_dilicom) Return ChildArticle objects filtered by the article_availability_dilicom column
 * @method     ChildArticle[]|Collection findByPreorder(boolean|array<boolean> $article_preorder) Return ChildArticle objects filtered by the article_preorder column
 * @psalm-method Collection&\Traversable<ChildArticle> findByPreorder(boolean|array<boolean> $article_preorder) Return ChildArticle objects filtered by the article_preorder column
 * @method     ChildArticle[]|Collection findByPrice(int|array<int> $article_price) Return ChildArticle objects filtered by the article_price column
 * @psalm-method Collection&\Traversable<ChildArticle> findByPrice(int|array<int> $article_price) Return ChildArticle objects filtered by the article_price column
 * @method     ChildArticle[]|Collection findByPriceEditable(boolean|array<boolean> $article_price_editable) Return ChildArticle objects filtered by the article_price_editable column
 * @psalm-method Collection&\Traversable<ChildArticle> findByPriceEditable(boolean|array<boolean> $article_price_editable) Return ChildArticle objects filtered by the article_price_editable column
 * @method     ChildArticle[]|Collection findByNewPrice(int|array<int> $article_new_price) Return ChildArticle objects filtered by the article_new_price column
 * @psalm-method Collection&\Traversable<ChildArticle> findByNewPrice(int|array<int> $article_new_price) Return ChildArticle objects filtered by the article_new_price column
 * @method     ChildArticle[]|Collection findByCategory(string|array<string> $article_category) Return ChildArticle objects filtered by the article_category column
 * @psalm-method Collection&\Traversable<ChildArticle> findByCategory(string|array<string> $article_category) Return ChildArticle objects filtered by the article_category column
 * @method     ChildArticle[]|Collection findByTva(int|array<int> $article_tva) Return ChildArticle objects filtered by the article_tva column
 * @psalm-method Collection&\Traversable<ChildArticle> findByTva(int|array<int> $article_tva) Return ChildArticle objects filtered by the article_tva column
 * @method     ChildArticle[]|Collection findByPdfEan(string|array<string> $article_pdf_ean) Return ChildArticle objects filtered by the article_pdf_ean column
 * @psalm-method Collection&\Traversable<ChildArticle> findByPdfEan(string|array<string> $article_pdf_ean) Return ChildArticle objects filtered by the article_pdf_ean column
 * @method     ChildArticle[]|Collection findByPdfVersion(string|array<string> $article_pdf_version) Return ChildArticle objects filtered by the article_pdf_version column
 * @psalm-method Collection&\Traversable<ChildArticle> findByPdfVersion(string|array<string> $article_pdf_version) Return ChildArticle objects filtered by the article_pdf_version column
 * @method     ChildArticle[]|Collection findByEpubEan(string|array<string> $article_epub_ean) Return ChildArticle objects filtered by the article_epub_ean column
 * @psalm-method Collection&\Traversable<ChildArticle> findByEpubEan(string|array<string> $article_epub_ean) Return ChildArticle objects filtered by the article_epub_ean column
 * @method     ChildArticle[]|Collection findByEpubVersion(string|array<string> $article_epub_version) Return ChildArticle objects filtered by the article_epub_version column
 * @psalm-method Collection&\Traversable<ChildArticle> findByEpubVersion(string|array<string> $article_epub_version) Return ChildArticle objects filtered by the article_epub_version column
 * @method     ChildArticle[]|Collection findByAzwEan(string|array<string> $article_azw_ean) Return ChildArticle objects filtered by the article_azw_ean column
 * @psalm-method Collection&\Traversable<ChildArticle> findByAzwEan(string|array<string> $article_azw_ean) Return ChildArticle objects filtered by the article_azw_ean column
 * @method     ChildArticle[]|Collection findByAzwVersion(string|array<string> $article_azw_version) Return ChildArticle objects filtered by the article_azw_version column
 * @psalm-method Collection&\Traversable<ChildArticle> findByAzwVersion(string|array<string> $article_azw_version) Return ChildArticle objects filtered by the article_azw_version column
 * @method     ChildArticle[]|Collection findByPages(int|array<int> $article_pages) Return ChildArticle objects filtered by the article_pages column
 * @psalm-method Collection&\Traversable<ChildArticle> findByPages(int|array<int> $article_pages) Return ChildArticle objects filtered by the article_pages column
 * @method     ChildArticle[]|Collection findByWeight(int|array<int> $article_weight) Return ChildArticle objects filtered by the article_weight column
 * @psalm-method Collection&\Traversable<ChildArticle> findByWeight(int|array<int> $article_weight) Return ChildArticle objects filtered by the article_weight column
 * @method     ChildArticle[]|Collection findByShaping(string|array<string> $article_shaping) Return ChildArticle objects filtered by the article_shaping column
 * @psalm-method Collection&\Traversable<ChildArticle> findByShaping(string|array<string> $article_shaping) Return ChildArticle objects filtered by the article_shaping column
 * @method     ChildArticle[]|Collection findByFormat(string|array<string> $article_format) Return ChildArticle objects filtered by the article_format column
 * @psalm-method Collection&\Traversable<ChildArticle> findByFormat(string|array<string> $article_format) Return ChildArticle objects filtered by the article_format column
 * @method     ChildArticle[]|Collection findByPrintingProcess(string|array<string> $article_printing_process) Return ChildArticle objects filtered by the article_printing_process column
 * @psalm-method Collection&\Traversable<ChildArticle> findByPrintingProcess(string|array<string> $article_printing_process) Return ChildArticle objects filtered by the article_printing_process column
 * @method     ChildArticle[]|Collection findByAgeMin(int|array<int> $article_age_min) Return ChildArticle objects filtered by the article_age_min column
 * @psalm-method Collection&\Traversable<ChildArticle> findByAgeMin(int|array<int> $article_age_min) Return ChildArticle objects filtered by the article_age_min column
 * @method     ChildArticle[]|Collection findByAgeMax(int|array<int> $article_age_max) Return ChildArticle objects filtered by the article_age_max column
 * @psalm-method Collection&\Traversable<ChildArticle> findByAgeMax(int|array<int> $article_age_max) Return ChildArticle objects filtered by the article_age_max column
 * @method     ChildArticle[]|Collection findBySummary(string|array<string> $article_summary) Return ChildArticle objects filtered by the article_summary column
 * @psalm-method Collection&\Traversable<ChildArticle> findBySummary(string|array<string> $article_summary) Return ChildArticle objects filtered by the article_summary column
 * @method     ChildArticle[]|Collection findByContents(string|array<string> $article_contents) Return ChildArticle objects filtered by the article_contents column
 * @psalm-method Collection&\Traversable<ChildArticle> findByContents(string|array<string> $article_contents) Return ChildArticle objects filtered by the article_contents column
 * @method     ChildArticle[]|Collection findByBonus(string|array<string> $article_bonus) Return ChildArticle objects filtered by the article_bonus column
 * @psalm-method Collection&\Traversable<ChildArticle> findByBonus(string|array<string> $article_bonus) Return ChildArticle objects filtered by the article_bonus column
 * @method     ChildArticle[]|Collection findByCatchline(string|array<string> $article_catchline) Return ChildArticle objects filtered by the article_catchline column
 * @psalm-method Collection&\Traversable<ChildArticle> findByCatchline(string|array<string> $article_catchline) Return ChildArticle objects filtered by the article_catchline column
 * @method     ChildArticle[]|Collection findByBiography(string|array<string> $article_biography) Return ChildArticle objects filtered by the article_biography column
 * @psalm-method Collection&\Traversable<ChildArticle> findByBiography(string|array<string> $article_biography) Return ChildArticle objects filtered by the article_biography column
 * @method     ChildArticle[]|Collection findByMotsv(string|array<string> $article_motsv) Return ChildArticle objects filtered by the article_motsv column
 * @psalm-method Collection&\Traversable<ChildArticle> findByMotsv(string|array<string> $article_motsv) Return ChildArticle objects filtered by the article_motsv column
 * @method     ChildArticle[]|Collection findByCopyright(int|array<int> $article_copyright) Return ChildArticle objects filtered by the article_copyright column
 * @psalm-method Collection&\Traversable<ChildArticle> findByCopyright(int|array<int> $article_copyright) Return ChildArticle objects filtered by the article_copyright column
 * @method     ChildArticle[]|Collection findByPubdate(string|array<string> $article_pubdate) Return ChildArticle objects filtered by the article_pubdate column
 * @psalm-method Collection&\Traversable<ChildArticle> findByPubdate(string|array<string> $article_pubdate) Return ChildArticle objects filtered by the article_pubdate column
 * @method     ChildArticle[]|Collection findByKeywords(string|array<string> $article_keywords) Return ChildArticle objects filtered by the article_keywords column
 * @psalm-method Collection&\Traversable<ChildArticle> findByKeywords(string|array<string> $article_keywords) Return ChildArticle objects filtered by the article_keywords column
 * @method     ChildArticle[]|Collection findByComputedLinks(string|array<string> $article_links) Return ChildArticle objects filtered by the article_links column
 * @psalm-method Collection&\Traversable<ChildArticle> findByComputedLinks(string|array<string> $article_links) Return ChildArticle objects filtered by the article_links column
 * @method     ChildArticle[]|Collection findByKeywordsGenerated(string|array<string> $article_keywords_generated) Return ChildArticle objects filtered by the article_keywords_generated column
 * @psalm-method Collection&\Traversable<ChildArticle> findByKeywordsGenerated(string|array<string> $article_keywords_generated) Return ChildArticle objects filtered by the article_keywords_generated column
 * @method     ChildArticle[]|Collection findByPublisherStock(int|array<int> $article_publisher_stock) Return ChildArticle objects filtered by the article_publisher_stock column
 * @psalm-method Collection&\Traversable<ChildArticle> findByPublisherStock(int|array<int> $article_publisher_stock) Return ChildArticle objects filtered by the article_publisher_stock column
 * @method     ChildArticle[]|Collection findByHits(int|array<int> $article_hits) Return ChildArticle objects filtered by the article_hits column
 * @psalm-method Collection&\Traversable<ChildArticle> findByHits(int|array<int> $article_hits) Return ChildArticle objects filtered by the article_hits column
 * @method     ChildArticle[]|Collection findByEditingUser(int|array<int> $article_editing_user) Return ChildArticle objects filtered by the article_editing_user column
 * @psalm-method Collection&\Traversable<ChildArticle> findByEditingUser(int|array<int> $article_editing_user) Return ChildArticle objects filtered by the article_editing_user column
 * @method     ChildArticle[]|Collection findByInsert(string|array<string> $article_insert) Return ChildArticle objects filtered by the article_insert column
 * @psalm-method Collection&\Traversable<ChildArticle> findByInsert(string|array<string> $article_insert) Return ChildArticle objects filtered by the article_insert column
 * @method     ChildArticle[]|Collection findByUpdate(string|array<string> $article_update) Return ChildArticle objects filtered by the article_update column
 * @psalm-method Collection&\Traversable<ChildArticle> findByUpdate(string|array<string> $article_update) Return ChildArticle objects filtered by the article_update column
 * @method     ChildArticle[]|Collection findByCreatedAt(string|array<string> $article_created) Return ChildArticle objects filtered by the article_created column
 * @psalm-method Collection&\Traversable<ChildArticle> findByCreatedAt(string|array<string> $article_created) Return ChildArticle objects filtered by the article_created column
 * @method     ChildArticle[]|Collection findByUpdatedAt(string|array<string> $article_updated) Return ChildArticle objects filtered by the article_updated column
 * @psalm-method Collection&\Traversable<ChildArticle> findByUpdatedAt(string|array<string> $article_updated) Return ChildArticle objects filtered by the article_updated column
 * @method     ChildArticle[]|Collection findByDone(boolean|array<boolean> $article_done) Return ChildArticle objects filtered by the article_done column
 * @psalm-method Collection&\Traversable<ChildArticle> findByDone(boolean|array<boolean> $article_done) Return ChildArticle objects filtered by the article_done column
 * @method     ChildArticle[]|Collection findByToCheck(boolean|array<boolean> $article_to_check) Return ChildArticle objects filtered by the article_to_check column
 * @psalm-method Collection&\Traversable<ChildArticle> findByToCheck(boolean|array<boolean> $article_to_check) Return ChildArticle objects filtered by the article_to_check column
 * @method     ChildArticle[]|Collection findByDeletionBy(int|array<int> $article_deletion_by) Return ChildArticle objects filtered by the article_deletion_by column
 * @psalm-method Collection&\Traversable<ChildArticle> findByDeletionBy(int|array<int> $article_deletion_by) Return ChildArticle objects filtered by the article_deletion_by column
 * @method     ChildArticle[]|Collection findByDeletionDate(string|array<string> $article_deletion_date) Return ChildArticle objects filtered by the article_deletion_date column
 * @psalm-method Collection&\Traversable<ChildArticle> findByDeletionDate(string|array<string> $article_deletion_date) Return ChildArticle objects filtered by the article_deletion_date column
 * @method     ChildArticle[]|Collection findByDeletionReason(string|array<string> $article_deletion_reason) Return ChildArticle objects filtered by the article_deletion_reason column
 * @psalm-method Collection&\Traversable<ChildArticle> findByDeletionReason(string|array<string> $article_deletion_reason) Return ChildArticle objects filtered by the article_deletion_reason column
 * @method     ChildArticle[]|Collection findByLemoninkMasterId(string|array<string> $lemonink_master_id) Return ChildArticle objects filtered by the lemonink_master_id column
 * @psalm-method Collection&\Traversable<ChildArticle> findByLemoninkMasterId(string|array<string> $lemonink_master_id) Return ChildArticle objects filtered by the lemonink_master_id column
 *
 * @method     ChildArticle[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildArticle> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class ArticleQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\ArticleQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Article', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildArticleQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildArticleQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
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
    public function findPk($key, ?ConnectionInterface $con = null)
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
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildArticle A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT article_id, article_item, article_textid, article_ean, article_ean_others, article_asin, article_noosfere_id, article_url, type_id, article_title, article_title_alphabetic, article_title_original, article_title_others, article_subtitle, article_lang_current, article_lang_original, article_origin_country, article_theme_bisac, article_theme_clil, article_theme_dewey, article_theme_electre, article_source_id, article_authors, article_authors_alphabetic, collection_id, article_collection, article_number, publisher_id, article_publisher, cycle_id, article_cycle, article_tome, article_cover_version, article_availability, article_availability_dilicom, article_preorder, article_price, article_price_editable, article_new_price, article_category, article_tva, article_pdf_ean, article_pdf_version, article_epub_ean, article_epub_version, article_azw_ean, article_azw_version, article_pages, article_weight, article_shaping, article_format, article_printing_process, article_age_min, article_age_max, article_summary, article_contents, article_bonus, article_catchline, article_biography, article_motsv, article_copyright, article_pubdate, article_keywords, article_links, article_keywords_generated, article_publisher_stock, article_hits, article_editing_user, article_insert, article_update, article_created, article_updated, article_done, article_to_check, article_deletion_by, article_deletion_date, article_deletion_reason, lemonink_master_id FROM articles WHERE article_id = :p0';
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
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
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
     * @param array $keys Primary keys to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return Collection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ?ConnectionInterface $con = null)
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
     * @param mixed $key Primary key to use for the query
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_ID, $key, Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param array|int $keys The list of primary key to use for the query
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_ID, $keys, Criteria::IN);

        return $this;
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
     * @param mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterById($id = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_ID, $id, $comparison);

        return $this;
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
     * @param mixed $item The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByItem($item = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_ITEM, $item, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_textid column
     *
     * Example usage:
     * <code>
     * $query->filterByTextid('fooValue');   // WHERE article_textid = 'fooValue'
     * $query->filterByTextid('%fooValue%', Criteria::LIKE); // WHERE article_textid LIKE '%fooValue%'
     * $query->filterByTextid(['foo', 'bar']); // WHERE article_textid IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $textid The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTextid($textid = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($textid)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_TEXTID, $textid, $comparison);

        return $this;
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
     * @param mixed $ean The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEan($ean = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_EAN, $ean, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_ean_others column
     *
     * Example usage:
     * <code>
     * $query->filterByEanOthers('fooValue');   // WHERE article_ean_others = 'fooValue'
     * $query->filterByEanOthers('%fooValue%', Criteria::LIKE); // WHERE article_ean_others LIKE '%fooValue%'
     * $query->filterByEanOthers(['foo', 'bar']); // WHERE article_ean_others IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $eanOthers The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEanOthers($eanOthers = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($eanOthers)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_EAN_OTHERS, $eanOthers, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_asin column
     *
     * Example usage:
     * <code>
     * $query->filterByAsin('fooValue');   // WHERE article_asin = 'fooValue'
     * $query->filterByAsin('%fooValue%', Criteria::LIKE); // WHERE article_asin LIKE '%fooValue%'
     * $query->filterByAsin(['foo', 'bar']); // WHERE article_asin IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $asin The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAsin($asin = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($asin)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_ASIN, $asin, $comparison);

        return $this;
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
     * @param mixed $noosfereId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByNoosfereId($noosfereId = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_NOOSFERE_ID, $noosfereId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE article_url = 'fooValue'
     * $query->filterByUrl('%fooValue%', Criteria::LIKE); // WHERE article_url LIKE '%fooValue%'
     * $query->filterByUrl(['foo', 'bar']); // WHERE article_url IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $url The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUrl($url = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($url)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_URL, $url, $comparison);

        return $this;
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
     * @param mixed $typeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTypeId($typeId = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_TYPE_ID, $typeId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE article_title = 'fooValue'
     * $query->filterByTitle('%fooValue%', Criteria::LIKE); // WHERE article_title LIKE '%fooValue%'
     * $query->filterByTitle(['foo', 'bar']); // WHERE article_title IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $title The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTitle($title = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_TITLE, $title, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_title_alphabetic column
     *
     * Example usage:
     * <code>
     * $query->filterByTitleAlphabetic('fooValue');   // WHERE article_title_alphabetic = 'fooValue'
     * $query->filterByTitleAlphabetic('%fooValue%', Criteria::LIKE); // WHERE article_title_alphabetic LIKE '%fooValue%'
     * $query->filterByTitleAlphabetic(['foo', 'bar']); // WHERE article_title_alphabetic IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $titleAlphabetic The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTitleAlphabetic($titleAlphabetic = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($titleAlphabetic)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_TITLE_ALPHABETIC, $titleAlphabetic, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_title_original column
     *
     * Example usage:
     * <code>
     * $query->filterByTitleOriginal('fooValue');   // WHERE article_title_original = 'fooValue'
     * $query->filterByTitleOriginal('%fooValue%', Criteria::LIKE); // WHERE article_title_original LIKE '%fooValue%'
     * $query->filterByTitleOriginal(['foo', 'bar']); // WHERE article_title_original IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $titleOriginal The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTitleOriginal($titleOriginal = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($titleOriginal)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_TITLE_ORIGINAL, $titleOriginal, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_title_others column
     *
     * Example usage:
     * <code>
     * $query->filterByTitleOthers('fooValue');   // WHERE article_title_others = 'fooValue'
     * $query->filterByTitleOthers('%fooValue%', Criteria::LIKE); // WHERE article_title_others LIKE '%fooValue%'
     * $query->filterByTitleOthers(['foo', 'bar']); // WHERE article_title_others IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $titleOthers The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTitleOthers($titleOthers = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($titleOthers)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_TITLE_OTHERS, $titleOthers, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_subtitle column
     *
     * Example usage:
     * <code>
     * $query->filterBySubtitle('fooValue');   // WHERE article_subtitle = 'fooValue'
     * $query->filterBySubtitle('%fooValue%', Criteria::LIKE); // WHERE article_subtitle LIKE '%fooValue%'
     * $query->filterBySubtitle(['foo', 'bar']); // WHERE article_subtitle IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $subtitle The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySubtitle($subtitle = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($subtitle)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_SUBTITLE, $subtitle, $comparison);

        return $this;
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
     * @param mixed $langCurrent The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByLangCurrent($langCurrent = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_LANG_CURRENT, $langCurrent, $comparison);

        return $this;
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
     * @param mixed $langOriginal The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByLangOriginal($langOriginal = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_LANG_ORIGINAL, $langOriginal, $comparison);

        return $this;
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
     * @param mixed $originCountry The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByOriginCountry($originCountry = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_ORIGIN_COUNTRY, $originCountry, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_theme_bisac column
     *
     * Example usage:
     * <code>
     * $query->filterByThemeBisac('fooValue');   // WHERE article_theme_bisac = 'fooValue'
     * $query->filterByThemeBisac('%fooValue%', Criteria::LIKE); // WHERE article_theme_bisac LIKE '%fooValue%'
     * $query->filterByThemeBisac(['foo', 'bar']); // WHERE article_theme_bisac IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $themeBisac The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByThemeBisac($themeBisac = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($themeBisac)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_THEME_BISAC, $themeBisac, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_theme_clil column
     *
     * Example usage:
     * <code>
     * $query->filterByThemeClil('fooValue');   // WHERE article_theme_clil = 'fooValue'
     * $query->filterByThemeClil('%fooValue%', Criteria::LIKE); // WHERE article_theme_clil LIKE '%fooValue%'
     * $query->filterByThemeClil(['foo', 'bar']); // WHERE article_theme_clil IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $themeClil The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByThemeClil($themeClil = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($themeClil)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_THEME_CLIL, $themeClil, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_theme_dewey column
     *
     * Example usage:
     * <code>
     * $query->filterByThemeDewey('fooValue');   // WHERE article_theme_dewey = 'fooValue'
     * $query->filterByThemeDewey('%fooValue%', Criteria::LIKE); // WHERE article_theme_dewey LIKE '%fooValue%'
     * $query->filterByThemeDewey(['foo', 'bar']); // WHERE article_theme_dewey IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $themeDewey The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByThemeDewey($themeDewey = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($themeDewey)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_THEME_DEWEY, $themeDewey, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_theme_electre column
     *
     * Example usage:
     * <code>
     * $query->filterByThemeElectre('fooValue');   // WHERE article_theme_electre = 'fooValue'
     * $query->filterByThemeElectre('%fooValue%', Criteria::LIKE); // WHERE article_theme_electre LIKE '%fooValue%'
     * $query->filterByThemeElectre(['foo', 'bar']); // WHERE article_theme_electre IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $themeElectre The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByThemeElectre($themeElectre = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($themeElectre)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_THEME_ELECTRE, $themeElectre, $comparison);

        return $this;
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
     * @param mixed $sourceId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySourceId($sourceId = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_SOURCE_ID, $sourceId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_authors column
     *
     * Example usage:
     * <code>
     * $query->filterByAuthors('fooValue');   // WHERE article_authors = 'fooValue'
     * $query->filterByAuthors('%fooValue%', Criteria::LIKE); // WHERE article_authors LIKE '%fooValue%'
     * $query->filterByAuthors(['foo', 'bar']); // WHERE article_authors IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $authors The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAuthors($authors = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($authors)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_AUTHORS, $authors, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_authors_alphabetic column
     *
     * Example usage:
     * <code>
     * $query->filterByAuthorsAlphabetic('fooValue');   // WHERE article_authors_alphabetic = 'fooValue'
     * $query->filterByAuthorsAlphabetic('%fooValue%', Criteria::LIKE); // WHERE article_authors_alphabetic LIKE '%fooValue%'
     * $query->filterByAuthorsAlphabetic(['foo', 'bar']); // WHERE article_authors_alphabetic IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $authorsAlphabetic The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAuthorsAlphabetic($authorsAlphabetic = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($authorsAlphabetic)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_AUTHORS_ALPHABETIC, $authorsAlphabetic, $comparison);

        return $this;
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
     * @see       filterByBookCollection()
     *
     * @param mixed $collectionId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCollectionId($collectionId = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_COLLECTION_ID, $collectionId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_collection column
     *
     * Example usage:
     * <code>
     * $query->filterByCollectionName('fooValue');   // WHERE article_collection = 'fooValue'
     * $query->filterByCollectionName('%fooValue%', Criteria::LIKE); // WHERE article_collection LIKE '%fooValue%'
     * $query->filterByCollectionName(['foo', 'bar']); // WHERE article_collection IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $collectionName The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCollectionName($collectionName = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($collectionName)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_COLLECTION, $collectionName, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_number column
     *
     * Example usage:
     * <code>
     * $query->filterByNumber('fooValue');   // WHERE article_number = 'fooValue'
     * $query->filterByNumber('%fooValue%', Criteria::LIKE); // WHERE article_number LIKE '%fooValue%'
     * $query->filterByNumber(['foo', 'bar']); // WHERE article_number IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $number The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByNumber($number = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($number)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_NUMBER, $number, $comparison);

        return $this;
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
     * @see       filterByPublisher()
     *
     * @param mixed $publisherId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPublisherId($publisherId = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_PUBLISHER_ID, $publisherId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_publisher column
     *
     * Example usage:
     * <code>
     * $query->filterByPublisherName('fooValue');   // WHERE article_publisher = 'fooValue'
     * $query->filterByPublisherName('%fooValue%', Criteria::LIKE); // WHERE article_publisher LIKE '%fooValue%'
     * $query->filterByPublisherName(['foo', 'bar']); // WHERE article_publisher IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $publisherName The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPublisherName($publisherName = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($publisherName)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PUBLISHER, $publisherName, $comparison);

        return $this;
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
     * @see       filterByCycle()
     *
     * @param mixed $cycleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCycleId($cycleId = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_CYCLE_ID, $cycleId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_cycle column
     *
     * Example usage:
     * <code>
     * $query->filterByCycleName('fooValue');   // WHERE article_cycle = 'fooValue'
     * $query->filterByCycleName('%fooValue%', Criteria::LIKE); // WHERE article_cycle LIKE '%fooValue%'
     * $query->filterByCycleName(['foo', 'bar']); // WHERE article_cycle IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $cycleName The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCycleName($cycleName = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($cycleName)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_CYCLE, $cycleName, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_tome column
     *
     * Example usage:
     * <code>
     * $query->filterByTome('fooValue');   // WHERE article_tome = 'fooValue'
     * $query->filterByTome('%fooValue%', Criteria::LIKE); // WHERE article_tome LIKE '%fooValue%'
     * $query->filterByTome(['foo', 'bar']); // WHERE article_tome IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $tome The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTome($tome = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($tome)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_TOME, $tome, $comparison);

        return $this;
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
     * @param mixed $coverVersion The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCoverVersion($coverVersion = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_COVER_VERSION, $coverVersion, $comparison);

        return $this;
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
     * @param mixed $availability The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAvailability($availability = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_AVAILABILITY, $availability, $comparison);

        return $this;
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
     * @param mixed $availabilityDilicom The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAvailabilityDilicom($availabilityDilicom = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_AVAILABILITY_DILICOM, $availabilityDilicom, $comparison);

        return $this;
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
     * @param bool|string $preorder The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPreorder($preorder = null, ?string $comparison = null)
    {
        if (is_string($preorder)) {
            $preorder = in_array(strtolower($preorder), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PREORDER, $preorder, $comparison);

        return $this;
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
     * @param mixed $price The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPrice($price = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PRICE, $price, $comparison);

        return $this;
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
     * @param bool|string $priceEditable The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPriceEditable($priceEditable = null, ?string $comparison = null)
    {
        if (is_string($priceEditable)) {
            $priceEditable = in_array(strtolower($priceEditable), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PRICE_EDITABLE, $priceEditable, $comparison);

        return $this;
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
     * @param mixed $newPrice The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByNewPrice($newPrice = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_NEW_PRICE, $newPrice, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_category column
     *
     * Example usage:
     * <code>
     * $query->filterByCategory('fooValue');   // WHERE article_category = 'fooValue'
     * $query->filterByCategory('%fooValue%', Criteria::LIKE); // WHERE article_category LIKE '%fooValue%'
     * $query->filterByCategory(['foo', 'bar']); // WHERE article_category IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $category The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCategory($category = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($category)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_CATEGORY, $category, $comparison);

        return $this;
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
     * @param mixed $tva The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTva($tva = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_TVA, $tva, $comparison);

        return $this;
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
     * @param mixed $pdfEan The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPdfEan($pdfEan = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PDF_EAN, $pdfEan, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_pdf_version column
     *
     * Example usage:
     * <code>
     * $query->filterByPdfVersion('fooValue');   // WHERE article_pdf_version = 'fooValue'
     * $query->filterByPdfVersion('%fooValue%', Criteria::LIKE); // WHERE article_pdf_version LIKE '%fooValue%'
     * $query->filterByPdfVersion(['foo', 'bar']); // WHERE article_pdf_version IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $pdfVersion The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPdfVersion($pdfVersion = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($pdfVersion)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PDF_VERSION, $pdfVersion, $comparison);

        return $this;
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
     * @param mixed $epubEan The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEpubEan($epubEan = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_EPUB_EAN, $epubEan, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_epub_version column
     *
     * Example usage:
     * <code>
     * $query->filterByEpubVersion('fooValue');   // WHERE article_epub_version = 'fooValue'
     * $query->filterByEpubVersion('%fooValue%', Criteria::LIKE); // WHERE article_epub_version LIKE '%fooValue%'
     * $query->filterByEpubVersion(['foo', 'bar']); // WHERE article_epub_version IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $epubVersion The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEpubVersion($epubVersion = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($epubVersion)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_EPUB_VERSION, $epubVersion, $comparison);

        return $this;
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
     * @param mixed $azwEan The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAzwEan($azwEan = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_AZW_EAN, $azwEan, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_azw_version column
     *
     * Example usage:
     * <code>
     * $query->filterByAzwVersion('fooValue');   // WHERE article_azw_version = 'fooValue'
     * $query->filterByAzwVersion('%fooValue%', Criteria::LIKE); // WHERE article_azw_version LIKE '%fooValue%'
     * $query->filterByAzwVersion(['foo', 'bar']); // WHERE article_azw_version IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $azwVersion The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAzwVersion($azwVersion = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($azwVersion)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_AZW_VERSION, $azwVersion, $comparison);

        return $this;
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
     * @param mixed $pages The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPages($pages = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PAGES, $pages, $comparison);

        return $this;
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
     * @param mixed $weight The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByWeight($weight = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_WEIGHT, $weight, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_shaping column
     *
     * Example usage:
     * <code>
     * $query->filterByShaping('fooValue');   // WHERE article_shaping = 'fooValue'
     * $query->filterByShaping('%fooValue%', Criteria::LIKE); // WHERE article_shaping LIKE '%fooValue%'
     * $query->filterByShaping(['foo', 'bar']); // WHERE article_shaping IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $shaping The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByShaping($shaping = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($shaping)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_SHAPING, $shaping, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_format column
     *
     * Example usage:
     * <code>
     * $query->filterByFormat('fooValue');   // WHERE article_format = 'fooValue'
     * $query->filterByFormat('%fooValue%', Criteria::LIKE); // WHERE article_format LIKE '%fooValue%'
     * $query->filterByFormat(['foo', 'bar']); // WHERE article_format IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $format The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFormat($format = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($format)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_FORMAT, $format, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_printing_process column
     *
     * Example usage:
     * <code>
     * $query->filterByPrintingProcess('fooValue');   // WHERE article_printing_process = 'fooValue'
     * $query->filterByPrintingProcess('%fooValue%', Criteria::LIKE); // WHERE article_printing_process LIKE '%fooValue%'
     * $query->filterByPrintingProcess(['foo', 'bar']); // WHERE article_printing_process IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $printingProcess The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPrintingProcess($printingProcess = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($printingProcess)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PRINTING_PROCESS, $printingProcess, $comparison);

        return $this;
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
     * @param mixed $ageMin The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAgeMin($ageMin = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_AGE_MIN, $ageMin, $comparison);

        return $this;
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
     * @param mixed $ageMax The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAgeMax($ageMax = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_AGE_MAX, $ageMax, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_summary column
     *
     * Example usage:
     * <code>
     * $query->filterBySummary('fooValue');   // WHERE article_summary = 'fooValue'
     * $query->filterBySummary('%fooValue%', Criteria::LIKE); // WHERE article_summary LIKE '%fooValue%'
     * $query->filterBySummary(['foo', 'bar']); // WHERE article_summary IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $summary The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySummary($summary = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($summary)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_SUMMARY, $summary, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_contents column
     *
     * Example usage:
     * <code>
     * $query->filterByContents('fooValue');   // WHERE article_contents = 'fooValue'
     * $query->filterByContents('%fooValue%', Criteria::LIKE); // WHERE article_contents LIKE '%fooValue%'
     * $query->filterByContents(['foo', 'bar']); // WHERE article_contents IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $contents The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByContents($contents = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($contents)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_CONTENTS, $contents, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_bonus column
     *
     * Example usage:
     * <code>
     * $query->filterByBonus('fooValue');   // WHERE article_bonus = 'fooValue'
     * $query->filterByBonus('%fooValue%', Criteria::LIKE); // WHERE article_bonus LIKE '%fooValue%'
     * $query->filterByBonus(['foo', 'bar']); // WHERE article_bonus IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $bonus The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByBonus($bonus = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($bonus)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_BONUS, $bonus, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_catchline column
     *
     * Example usage:
     * <code>
     * $query->filterByCatchline('fooValue');   // WHERE article_catchline = 'fooValue'
     * $query->filterByCatchline('%fooValue%', Criteria::LIKE); // WHERE article_catchline LIKE '%fooValue%'
     * $query->filterByCatchline(['foo', 'bar']); // WHERE article_catchline IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $catchline The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCatchline($catchline = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($catchline)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_CATCHLINE, $catchline, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_biography column
     *
     * Example usage:
     * <code>
     * $query->filterByBiography('fooValue');   // WHERE article_biography = 'fooValue'
     * $query->filterByBiography('%fooValue%', Criteria::LIKE); // WHERE article_biography LIKE '%fooValue%'
     * $query->filterByBiography(['foo', 'bar']); // WHERE article_biography IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $biography The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByBiography($biography = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($biography)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_BIOGRAPHY, $biography, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_motsv column
     *
     * Example usage:
     * <code>
     * $query->filterByMotsv('fooValue');   // WHERE article_motsv = 'fooValue'
     * $query->filterByMotsv('%fooValue%', Criteria::LIKE); // WHERE article_motsv LIKE '%fooValue%'
     * $query->filterByMotsv(['foo', 'bar']); // WHERE article_motsv IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $motsv The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByMotsv($motsv = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($motsv)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_MOTSV, $motsv, $comparison);

        return $this;
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
     * @param mixed $copyright The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCopyright($copyright = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_COPYRIGHT, $copyright, $comparison);

        return $this;
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
     * @param mixed $pubdate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPubdate($pubdate = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PUBDATE, $pubdate, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_keywords column
     *
     * Example usage:
     * <code>
     * $query->filterByKeywords('fooValue');   // WHERE article_keywords = 'fooValue'
     * $query->filterByKeywords('%fooValue%', Criteria::LIKE); // WHERE article_keywords LIKE '%fooValue%'
     * $query->filterByKeywords(['foo', 'bar']); // WHERE article_keywords IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $keywords The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByKeywords($keywords = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($keywords)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_KEYWORDS, $keywords, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_links column
     *
     * Example usage:
     * <code>
     * $query->filterByComputedLinks('fooValue');   // WHERE article_links = 'fooValue'
     * $query->filterByComputedLinks('%fooValue%', Criteria::LIKE); // WHERE article_links LIKE '%fooValue%'
     * $query->filterByComputedLinks(['foo', 'bar']); // WHERE article_links IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $computedLinks The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByComputedLinks($computedLinks = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($computedLinks)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_LINKS, $computedLinks, $comparison);

        return $this;
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
     * @param mixed $keywordsGenerated The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByKeywordsGenerated($keywordsGenerated = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_KEYWORDS_GENERATED, $keywordsGenerated, $comparison);

        return $this;
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
     * @param mixed $publisherStock The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPublisherStock($publisherStock = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_PUBLISHER_STOCK, $publisherStock, $comparison);

        return $this;
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
     * @param mixed $hits The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByHits($hits = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_HITS, $hits, $comparison);

        return $this;
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
     * @param mixed $editingUser The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEditingUser($editingUser = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_EDITING_USER, $editingUser, $comparison);

        return $this;
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
     * @param mixed $insert The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByInsert($insert = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_INSERT, $insert, $comparison);

        return $this;
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
     * @param mixed $update The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUpdate($update = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_UPDATE, $update, $comparison);

        return $this;
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
     * @param mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_CREATED, $createdAt, $comparison);

        return $this;
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
     * @param mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_UPDATED, $updatedAt, $comparison);

        return $this;
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
     * @param bool|string $done The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDone($done = null, ?string $comparison = null)
    {
        if (is_string($done)) {
            $done = in_array(strtolower($done), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_DONE, $done, $comparison);

        return $this;
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
     * @param bool|string $toCheck The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByToCheck($toCheck = null, ?string $comparison = null)
    {
        if (is_string($toCheck)) {
            $toCheck = in_array(strtolower($toCheck), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_TO_CHECK, $toCheck, $comparison);

        return $this;
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
     * @param mixed $deletionBy The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDeletionBy($deletionBy = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_DELETION_BY, $deletionBy, $comparison);

        return $this;
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
     * @param mixed $deletionDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDeletionDate($deletionDate = null, ?string $comparison = null)
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

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_DELETION_DATE, $deletionDate, $comparison);

        return $this;
    }

    /**
     * Filter the query on the article_deletion_reason column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletionReason('fooValue');   // WHERE article_deletion_reason = 'fooValue'
     * $query->filterByDeletionReason('%fooValue%', Criteria::LIKE); // WHERE article_deletion_reason LIKE '%fooValue%'
     * $query->filterByDeletionReason(['foo', 'bar']); // WHERE article_deletion_reason IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $deletionReason The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDeletionReason($deletionReason = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($deletionReason)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_DELETION_REASON, $deletionReason, $comparison);

        return $this;
    }

    /**
     * Filter the query on the lemonink_master_id column
     *
     * Example usage:
     * <code>
     * $query->filterByLemoninkMasterId('fooValue');   // WHERE lemonink_master_id = 'fooValue'
     * $query->filterByLemoninkMasterId('%fooValue%', Criteria::LIKE); // WHERE lemonink_master_id LIKE '%fooValue%'
     * $query->filterByLemoninkMasterId(['foo', 'bar']); // WHERE lemonink_master_id IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $lemoninkMasterId The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByLemoninkMasterId($lemoninkMasterId = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lemoninkMasterId)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(ArticleTableMap::COL_LEMONINK_MASTER_ID, $lemoninkMasterId, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \Model\Publisher object
     *
     * @param \Model\Publisher|ObjectCollection $publisher The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPublisher($publisher, ?string $comparison = null)
    {
        if ($publisher instanceof \Model\Publisher) {
            return $this
                ->addUsingAlias(ArticleTableMap::COL_PUBLISHER_ID, $publisher->getId(), $comparison);
        } elseif ($publisher instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(ArticleTableMap::COL_PUBLISHER_ID, $publisher->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByPublisher() only accepts arguments of type \Model\Publisher or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Publisher relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinPublisher(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Publisher');

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
            $this->addJoinObject($join, 'Publisher');
        }

        return $this;
    }

    /**
     * Use the Publisher relation Publisher object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\PublisherQuery A secondary query class using the current class as primary query
     */
    public function usePublisherQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPublisher($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Publisher', '\Model\PublisherQuery');
    }

    /**
     * Use the Publisher relation Publisher object
     *
     * @param callable(\Model\PublisherQuery):\Model\PublisherQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withPublisherQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->usePublisherQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Publisher table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\PublisherQuery The inner query object of the EXISTS statement
     */
    public function usePublisherExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\PublisherQuery */
        $q = $this->useExistsQuery('Publisher', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Publisher table for a NOT EXISTS query.
     *
     * @see usePublisherExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\PublisherQuery The inner query object of the NOT EXISTS statement
     */
    public function usePublisherNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\PublisherQuery */
        $q = $this->useExistsQuery('Publisher', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Publisher table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\PublisherQuery The inner query object of the IN statement
     */
    public function useInPublisherQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\PublisherQuery */
        $q = $this->useInQuery('Publisher', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Publisher table for a NOT IN query.
     *
     * @see usePublisherInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\PublisherQuery The inner query object of the NOT IN statement
     */
    public function useNotInPublisherQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\PublisherQuery */
        $q = $this->useInQuery('Publisher', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\BookCollection object
     *
     * @param \Model\BookCollection|ObjectCollection $bookCollection The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByBookCollection($bookCollection, ?string $comparison = null)
    {
        if ($bookCollection instanceof \Model\BookCollection) {
            return $this
                ->addUsingAlias(ArticleTableMap::COL_COLLECTION_ID, $bookCollection->getId(), $comparison);
        } elseif ($bookCollection instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(ArticleTableMap::COL_COLLECTION_ID, $bookCollection->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByBookCollection() only accepts arguments of type \Model\BookCollection or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the BookCollection relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinBookCollection(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('BookCollection');

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
            $this->addJoinObject($join, 'BookCollection');
        }

        return $this;
    }

    /**
     * Use the BookCollection relation BookCollection object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\BookCollectionQuery A secondary query class using the current class as primary query
     */
    public function useBookCollectionQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinBookCollection($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'BookCollection', '\Model\BookCollectionQuery');
    }

    /**
     * Use the BookCollection relation BookCollection object
     *
     * @param callable(\Model\BookCollectionQuery):\Model\BookCollectionQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withBookCollectionQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useBookCollectionQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to BookCollection table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\BookCollectionQuery The inner query object of the EXISTS statement
     */
    public function useBookCollectionExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\BookCollectionQuery */
        $q = $this->useExistsQuery('BookCollection', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to BookCollection table for a NOT EXISTS query.
     *
     * @see useBookCollectionExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\BookCollectionQuery The inner query object of the NOT EXISTS statement
     */
    public function useBookCollectionNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\BookCollectionQuery */
        $q = $this->useExistsQuery('BookCollection', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to BookCollection table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\BookCollectionQuery The inner query object of the IN statement
     */
    public function useInBookCollectionQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\BookCollectionQuery */
        $q = $this->useInQuery('BookCollection', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to BookCollection table for a NOT IN query.
     *
     * @see useBookCollectionInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\BookCollectionQuery The inner query object of the NOT IN statement
     */
    public function useNotInBookCollectionQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\BookCollectionQuery */
        $q = $this->useInQuery('BookCollection', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Cycle object
     *
     * @param \Model\Cycle|ObjectCollection $cycle The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCycle($cycle, ?string $comparison = null)
    {
        if ($cycle instanceof \Model\Cycle) {
            return $this
                ->addUsingAlias(ArticleTableMap::COL_CYCLE_ID, $cycle->getId(), $comparison);
        } elseif ($cycle instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(ArticleTableMap::COL_CYCLE_ID, $cycle->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByCycle() only accepts arguments of type \Model\Cycle or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Cycle relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinCycle(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Cycle');

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
            $this->addJoinObject($join, 'Cycle');
        }

        return $this;
    }

    /**
     * Use the Cycle relation Cycle object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\CycleQuery A secondary query class using the current class as primary query
     */
    public function useCycleQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCycle($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Cycle', '\Model\CycleQuery');
    }

    /**
     * Use the Cycle relation Cycle object
     *
     * @param callable(\Model\CycleQuery):\Model\CycleQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withCycleQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useCycleQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Cycle table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\CycleQuery The inner query object of the EXISTS statement
     */
    public function useCycleExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\CycleQuery */
        $q = $this->useExistsQuery('Cycle', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Cycle table for a NOT EXISTS query.
     *
     * @see useCycleExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\CycleQuery The inner query object of the NOT EXISTS statement
     */
    public function useCycleNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\CycleQuery */
        $q = $this->useExistsQuery('Cycle', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Cycle table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\CycleQuery The inner query object of the IN statement
     */
    public function useInCycleQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\CycleQuery */
        $q = $this->useInQuery('Cycle', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Cycle table for a NOT IN query.
     *
     * @see useCycleInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\CycleQuery The inner query object of the NOT IN statement
     */
    public function useNotInCycleQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\CycleQuery */
        $q = $this->useInQuery('Cycle', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\File object
     *
     * @param \Model\File|ObjectCollection $file the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFile($file, ?string $comparison = null)
    {
        if ($file instanceof \Model\File) {
            $this
                ->addUsingAlias(ArticleTableMap::COL_ARTICLE_ID, $file->getArticleId(), $comparison);

            return $this;
        } elseif ($file instanceof ObjectCollection) {
            $this
                ->useFileQuery()
                ->filterByPrimaryKeys($file->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByFile() only accepts arguments of type \Model\File or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the File relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinFile(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('File');

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
            $this->addJoinObject($join, 'File');
        }

        return $this;
    }

    /**
     * Use the File relation File object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\FileQuery A secondary query class using the current class as primary query
     */
    public function useFileQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinFile($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'File', '\Model\FileQuery');
    }

    /**
     * Use the File relation File object
     *
     * @param callable(\Model\FileQuery):\Model\FileQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withFileQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useFileQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to File table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\FileQuery The inner query object of the EXISTS statement
     */
    public function useFileExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\FileQuery */
        $q = $this->useExistsQuery('File', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to File table for a NOT EXISTS query.
     *
     * @see useFileExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\FileQuery The inner query object of the NOT EXISTS statement
     */
    public function useFileNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\FileQuery */
        $q = $this->useExistsQuery('File', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to File table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\FileQuery The inner query object of the IN statement
     */
    public function useInFileQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\FileQuery */
        $q = $this->useInQuery('File', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to File table for a NOT IN query.
     *
     * @see useFileInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\FileQuery The inner query object of the NOT IN statement
     */
    public function useNotInFileQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\FileQuery */
        $q = $this->useInQuery('File', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Image object
     *
     * @param \Model\Image|ObjectCollection $image the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByImage($image, ?string $comparison = null)
    {
        if ($image instanceof \Model\Image) {
            $this
                ->addUsingAlias(ArticleTableMap::COL_ARTICLE_ID, $image->getArticleId(), $comparison);

            return $this;
        } elseif ($image instanceof ObjectCollection) {
            $this
                ->useImageQuery()
                ->filterByPrimaryKeys($image->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByImage() only accepts arguments of type \Model\Image or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Image relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinImage(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Image');

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
            $this->addJoinObject($join, 'Image');
        }

        return $this;
    }

    /**
     * Use the Image relation Image object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\ImageQuery A secondary query class using the current class as primary query
     */
    public function useImageQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinImage($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Image', '\Model\ImageQuery');
    }

    /**
     * Use the Image relation Image object
     *
     * @param callable(\Model\ImageQuery):\Model\ImageQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withImageQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useImageQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Image table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\ImageQuery The inner query object of the EXISTS statement
     */
    public function useImageExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\ImageQuery */
        $q = $this->useExistsQuery('Image', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Image table for a NOT EXISTS query.
     *
     * @see useImageExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\ImageQuery The inner query object of the NOT EXISTS statement
     */
    public function useImageNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\ImageQuery */
        $q = $this->useExistsQuery('Image', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Image table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\ImageQuery The inner query object of the IN statement
     */
    public function useInImageQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\ImageQuery */
        $q = $this->useInQuery('Image', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Image table for a NOT IN query.
     *
     * @see useImageInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\ImageQuery The inner query object of the NOT IN statement
     */
    public function useNotInImageQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\ImageQuery */
        $q = $this->useInQuery('Image', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\InvitationsArticles object
     *
     * @param \Model\InvitationsArticles|ObjectCollection $invitationsArticles the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByInvitationsArticles($invitationsArticles, ?string $comparison = null)
    {
        if ($invitationsArticles instanceof \Model\InvitationsArticles) {
            $this
                ->addUsingAlias(ArticleTableMap::COL_ARTICLE_ID, $invitationsArticles->getArticleId(), $comparison);

            return $this;
        } elseif ($invitationsArticles instanceof ObjectCollection) {
            $this
                ->useInvitationsArticlesQuery()
                ->filterByPrimaryKeys($invitationsArticles->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByInvitationsArticles() only accepts arguments of type \Model\InvitationsArticles or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the InvitationsArticles relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinInvitationsArticles(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('InvitationsArticles');

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
            $this->addJoinObject($join, 'InvitationsArticles');
        }

        return $this;
    }

    /**
     * Use the InvitationsArticles relation InvitationsArticles object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\InvitationsArticlesQuery A secondary query class using the current class as primary query
     */
    public function useInvitationsArticlesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInvitationsArticles($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'InvitationsArticles', '\Model\InvitationsArticlesQuery');
    }

    /**
     * Use the InvitationsArticles relation InvitationsArticles object
     *
     * @param callable(\Model\InvitationsArticlesQuery):\Model\InvitationsArticlesQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withInvitationsArticlesQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useInvitationsArticlesQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to InvitationsArticles table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\InvitationsArticlesQuery The inner query object of the EXISTS statement
     */
    public function useInvitationsArticlesExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\InvitationsArticlesQuery */
        $q = $this->useExistsQuery('InvitationsArticles', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to InvitationsArticles table for a NOT EXISTS query.
     *
     * @see useInvitationsArticlesExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\InvitationsArticlesQuery The inner query object of the NOT EXISTS statement
     */
    public function useInvitationsArticlesNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\InvitationsArticlesQuery */
        $q = $this->useExistsQuery('InvitationsArticles', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to InvitationsArticles table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\InvitationsArticlesQuery The inner query object of the IN statement
     */
    public function useInInvitationsArticlesQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\InvitationsArticlesQuery */
        $q = $this->useInQuery('InvitationsArticles', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to InvitationsArticles table for a NOT IN query.
     *
     * @see useInvitationsArticlesInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\InvitationsArticlesQuery The inner query object of the NOT IN statement
     */
    public function useNotInInvitationsArticlesQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\InvitationsArticlesQuery */
        $q = $this->useInQuery('InvitationsArticles', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Link object
     *
     * @param \Model\Link|ObjectCollection $link the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByLink($link, ?string $comparison = null)
    {
        if ($link instanceof \Model\Link) {
            $this
                ->addUsingAlias(ArticleTableMap::COL_ARTICLE_ID, $link->getArticleId(), $comparison);

            return $this;
        } elseif ($link instanceof ObjectCollection) {
            $this
                ->useLinkQuery()
                ->filterByPrimaryKeys($link->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByLink() only accepts arguments of type \Model\Link or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Link relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinLink(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Link');

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
            $this->addJoinObject($join, 'Link');
        }

        return $this;
    }

    /**
     * Use the Link relation Link object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\LinkQuery A secondary query class using the current class as primary query
     */
    public function useLinkQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinLink($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Link', '\Model\LinkQuery');
    }

    /**
     * Use the Link relation Link object
     *
     * @param callable(\Model\LinkQuery):\Model\LinkQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withLinkQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useLinkQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Link table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\LinkQuery The inner query object of the EXISTS statement
     */
    public function useLinkExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\LinkQuery */
        $q = $this->useExistsQuery('Link', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Link table for a NOT EXISTS query.
     *
     * @see useLinkExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\LinkQuery The inner query object of the NOT EXISTS statement
     */
    public function useLinkNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\LinkQuery */
        $q = $this->useExistsQuery('Link', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Link table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\LinkQuery The inner query object of the IN statement
     */
    public function useInLinkQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\LinkQuery */
        $q = $this->useInQuery('Link', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Link table for a NOT IN query.
     *
     * @see useLinkInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\LinkQuery The inner query object of the NOT IN statement
     */
    public function useNotInLinkQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\LinkQuery */
        $q = $this->useInQuery('Link', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Role object
     *
     * @param \Model\Role|ObjectCollection $role the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByRole($role, ?string $comparison = null)
    {
        if ($role instanceof \Model\Role) {
            $this
                ->addUsingAlias(ArticleTableMap::COL_ARTICLE_ID, $role->getArticleId(), $comparison);

            return $this;
        } elseif ($role instanceof ObjectCollection) {
            $this
                ->useRoleQuery()
                ->filterByPrimaryKeys($role->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByRole() only accepts arguments of type \Model\Role or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Role relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinRole(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
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
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
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
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\RoleQuery The inner query object of the EXISTS statement
     */
    public function useRoleExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\RoleQuery */
        $q = $this->useExistsQuery('Role', $modelAlias, $queryClass, $typeOfExists);
        return $q;
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
        /** @var $q \Model\RoleQuery */
        $q = $this->useExistsQuery('Role', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Role table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\RoleQuery The inner query object of the IN statement
     */
    public function useInRoleQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\RoleQuery */
        $q = $this->useInQuery('Role', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Role table for a NOT IN query.
     *
     * @see useRoleInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\RoleQuery The inner query object of the NOT IN statement
     */
    public function useNotInRoleQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\RoleQuery */
        $q = $this->useInQuery('Role', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\SpecialOffer object
     *
     * @param \Model\SpecialOffer|ObjectCollection $specialOffer the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySpecialOffer($specialOffer, ?string $comparison = null)
    {
        if ($specialOffer instanceof \Model\SpecialOffer) {
            $this
                ->addUsingAlias(ArticleTableMap::COL_ARTICLE_ID, $specialOffer->getFreeArticleId(), $comparison);

            return $this;
        } elseif ($specialOffer instanceof ObjectCollection) {
            $this
                ->useSpecialOfferQuery()
                ->filterByPrimaryKeys($specialOffer->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterBySpecialOffer() only accepts arguments of type \Model\SpecialOffer or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SpecialOffer relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinSpecialOffer(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SpecialOffer');

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
            $this->addJoinObject($join, 'SpecialOffer');
        }

        return $this;
    }

    /**
     * Use the SpecialOffer relation SpecialOffer object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\SpecialOfferQuery A secondary query class using the current class as primary query
     */
    public function useSpecialOfferQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSpecialOffer($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SpecialOffer', '\Model\SpecialOfferQuery');
    }

    /**
     * Use the SpecialOffer relation SpecialOffer object
     *
     * @param callable(\Model\SpecialOfferQuery):\Model\SpecialOfferQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withSpecialOfferQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useSpecialOfferQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to SpecialOffer table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\SpecialOfferQuery The inner query object of the EXISTS statement
     */
    public function useSpecialOfferExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\SpecialOfferQuery */
        $q = $this->useExistsQuery('SpecialOffer', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to SpecialOffer table for a NOT EXISTS query.
     *
     * @see useSpecialOfferExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\SpecialOfferQuery The inner query object of the NOT EXISTS statement
     */
    public function useSpecialOfferNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\SpecialOfferQuery */
        $q = $this->useExistsQuery('SpecialOffer', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to SpecialOffer table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\SpecialOfferQuery The inner query object of the IN statement
     */
    public function useInSpecialOfferQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\SpecialOfferQuery */
        $q = $this->useInQuery('SpecialOffer', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to SpecialOffer table for a NOT IN query.
     *
     * @see useSpecialOfferInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\SpecialOfferQuery The inner query object of the NOT IN statement
     */
    public function useNotInSpecialOfferQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\SpecialOfferQuery */
        $q = $this->useInQuery('SpecialOffer', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Stock object
     *
     * @param \Model\Stock|ObjectCollection $stock the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByStock($stock, ?string $comparison = null)
    {
        if ($stock instanceof \Model\Stock) {
            $this
                ->addUsingAlias(ArticleTableMap::COL_ARTICLE_ID, $stock->getArticleId(), $comparison);

            return $this;
        } elseif ($stock instanceof ObjectCollection) {
            $this
                ->useStockQuery()
                ->filterByPrimaryKeys($stock->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByStock() only accepts arguments of type \Model\Stock or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Stock relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinStock(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Stock');

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
            $this->addJoinObject($join, 'Stock');
        }

        return $this;
    }

    /**
     * Use the Stock relation Stock object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\StockQuery A secondary query class using the current class as primary query
     */
    public function useStockQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinStock($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Stock', '\Model\StockQuery');
    }

    /**
     * Use the Stock relation Stock object
     *
     * @param callable(\Model\StockQuery):\Model\StockQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withStockQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useStockQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Stock table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\StockQuery The inner query object of the EXISTS statement
     */
    public function useStockExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\StockQuery */
        $q = $this->useExistsQuery('Stock', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Stock table for a NOT EXISTS query.
     *
     * @see useStockExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\StockQuery The inner query object of the NOT EXISTS statement
     */
    public function useStockNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\StockQuery */
        $q = $this->useExistsQuery('Stock', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Stock table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\StockQuery The inner query object of the IN statement
     */
    public function useInStockQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\StockQuery */
        $q = $this->useInQuery('Stock', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Stock table for a NOT IN query.
     *
     * @see useStockInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\StockQuery The inner query object of the NOT IN statement
     */
    public function useNotInStockQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\StockQuery */
        $q = $this->useInQuery('Stock', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\ArticleTag object
     *
     * @param \Model\ArticleTag|ObjectCollection $articleTag the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByArticleTag($articleTag, ?string $comparison = null)
    {
        if ($articleTag instanceof \Model\ArticleTag) {
            $this
                ->addUsingAlias(ArticleTableMap::COL_ARTICLE_ID, $articleTag->getArticleId(), $comparison);

            return $this;
        } elseif ($articleTag instanceof ObjectCollection) {
            $this
                ->useArticleTagQuery()
                ->filterByPrimaryKeys($articleTag->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByArticleTag() only accepts arguments of type \Model\ArticleTag or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ArticleTag relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinArticleTag(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ArticleTag');

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
            $this->addJoinObject($join, 'ArticleTag');
        }

        return $this;
    }

    /**
     * Use the ArticleTag relation ArticleTag object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\ArticleTagQuery A secondary query class using the current class as primary query
     */
    public function useArticleTagQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinArticleTag($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ArticleTag', '\Model\ArticleTagQuery');
    }

    /**
     * Use the ArticleTag relation ArticleTag object
     *
     * @param callable(\Model\ArticleTagQuery):\Model\ArticleTagQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withArticleTagQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useArticleTagQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to ArticleTag table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\ArticleTagQuery The inner query object of the EXISTS statement
     */
    public function useArticleTagExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\ArticleTagQuery */
        $q = $this->useExistsQuery('ArticleTag', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to ArticleTag table for a NOT EXISTS query.
     *
     * @see useArticleTagExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\ArticleTagQuery The inner query object of the NOT EXISTS statement
     */
    public function useArticleTagNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\ArticleTagQuery */
        $q = $this->useExistsQuery('ArticleTag', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to ArticleTag table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\ArticleTagQuery The inner query object of the IN statement
     */
    public function useInArticleTagQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\ArticleTagQuery */
        $q = $this->useInQuery('ArticleTag', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to ArticleTag table for a NOT IN query.
     *
     * @see useArticleTagInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\ArticleTagQuery The inner query object of the NOT IN statement
     */
    public function useNotInArticleTagQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\ArticleTagQuery */
        $q = $this->useInQuery('ArticleTag', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related Invitation object
     * using the invitations_articles table as cross reference
     *
     * @param Invitation $invitation the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL and Criteria::IN for queries
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByInvitation($invitation, string $comparison = null)
    {
        $this
            ->useInvitationsArticlesQuery()
            ->filterByInvitation($invitation, $comparison)
            ->endUse();

        return $this;
    }

    /**
     * Filter the query by a related Tag object
     * using the tags_articles table as cross reference
     *
     * @param Tag $tag the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL and Criteria::IN for queries
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTag($tag, string $comparison = null)
    {
        $this
            ->useArticleTagQuery()
            ->filterByTag($tag, $comparison)
            ->endUse();

        return $this;
    }

    /**
     * Exclude object from result
     *
     * @param ChildArticle $article Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
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
    public function doDeleteAll(?ConnectionInterface $con = null): int
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
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(?ConnectionInterface $con = null): int
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
     * @param int $nbDays Maximum age of the latest update in days
     *
     * @return $this The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(ArticleTableMap::COL_ARTICLE_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(ArticleTableMap::COL_ARTICLE_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(ArticleTableMap::COL_ARTICLE_CREATED);

        return $this;
    }

    /**
     * Filter by the latest created
     *
     * @param int $nbDays Maximum age of in days
     *
     * @return $this The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(ArticleTableMap::COL_ARTICLE_CREATED);

        return $this;
    }

    // sluggable behavior

    /**
     * Filter the query on the slug column
     *
     * @param string $slug The value to use as filter.
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySlug(string $slug)
    {
        $this->addUsingAlias(ArticleTableMap::COL_ARTICLE_URL, $slug, Criteria::EQUAL);

        return $this;
    }

    /**
     * Find one object based on its slug
     *
     * @param string $slug The value to use as filter.
     * @param ConnectionInterface $con The optional connection object
     *
     * @return ChildArticle the result, formatted by the current formatter
     */
    public function findOneBySlug(string $slug, ?ConnectionInterface $con = null)
    {
        return $this->filterBySlug($slug)->findOne($con);
    }

}
