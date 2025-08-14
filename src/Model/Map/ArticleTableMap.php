<?php

namespace Model\Map;

use Model\Article;
use Model\ArticleQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'articles' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class ArticleTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.ArticleTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'articles';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Article';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Article';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Article';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 78;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 78;

    /**
     * the column name for the article_id field
     */
    public const COL_ARTICLE_ID = 'articles.article_id';

    /**
     * the column name for the article_item field
     */
    public const COL_ARTICLE_ITEM = 'articles.article_item';

    /**
     * the column name for the article_textid field
     */
    public const COL_ARTICLE_TEXTID = 'articles.article_textid';

    /**
     * the column name for the article_ean field
     */
    public const COL_ARTICLE_EAN = 'articles.article_ean';

    /**
     * the column name for the article_ean_others field
     */
    public const COL_ARTICLE_EAN_OTHERS = 'articles.article_ean_others';

    /**
     * the column name for the article_asin field
     */
    public const COL_ARTICLE_ASIN = 'articles.article_asin';

    /**
     * the column name for the article_noosfere_id field
     */
    public const COL_ARTICLE_NOOSFERE_ID = 'articles.article_noosfere_id';

    /**
     * the column name for the article_url field
     */
    public const COL_ARTICLE_URL = 'articles.article_url';

    /**
     * the column name for the type_id field
     */
    public const COL_TYPE_ID = 'articles.type_id';

    /**
     * the column name for the article_title field
     */
    public const COL_ARTICLE_TITLE = 'articles.article_title';

    /**
     * the column name for the article_title_alphabetic field
     */
    public const COL_ARTICLE_TITLE_ALPHABETIC = 'articles.article_title_alphabetic';

    /**
     * the column name for the article_title_original field
     */
    public const COL_ARTICLE_TITLE_ORIGINAL = 'articles.article_title_original';

    /**
     * the column name for the article_title_others field
     */
    public const COL_ARTICLE_TITLE_OTHERS = 'articles.article_title_others';

    /**
     * the column name for the article_subtitle field
     */
    public const COL_ARTICLE_SUBTITLE = 'articles.article_subtitle';

    /**
     * the column name for the article_lang_current field
     */
    public const COL_ARTICLE_LANG_CURRENT = 'articles.article_lang_current';

    /**
     * the column name for the article_lang_original field
     */
    public const COL_ARTICLE_LANG_ORIGINAL = 'articles.article_lang_original';

    /**
     * the column name for the article_origin_country field
     */
    public const COL_ARTICLE_ORIGIN_COUNTRY = 'articles.article_origin_country';

    /**
     * the column name for the article_theme_bisac field
     */
    public const COL_ARTICLE_THEME_BISAC = 'articles.article_theme_bisac';

    /**
     * the column name for the article_theme_clil field
     */
    public const COL_ARTICLE_THEME_CLIL = 'articles.article_theme_clil';

    /**
     * the column name for the article_theme_dewey field
     */
    public const COL_ARTICLE_THEME_DEWEY = 'articles.article_theme_dewey';

    /**
     * the column name for the article_theme_electre field
     */
    public const COL_ARTICLE_THEME_ELECTRE = 'articles.article_theme_electre';

    /**
     * the column name for the article_source_id field
     */
    public const COL_ARTICLE_SOURCE_ID = 'articles.article_source_id';

    /**
     * the column name for the article_authors field
     */
    public const COL_ARTICLE_AUTHORS = 'articles.article_authors';

    /**
     * the column name for the article_authors_alphabetic field
     */
    public const COL_ARTICLE_AUTHORS_ALPHABETIC = 'articles.article_authors_alphabetic';

    /**
     * the column name for the collection_id field
     */
    public const COL_COLLECTION_ID = 'articles.collection_id';

    /**
     * the column name for the article_collection field
     */
    public const COL_ARTICLE_COLLECTION = 'articles.article_collection';

    /**
     * the column name for the article_number field
     */
    public const COL_ARTICLE_NUMBER = 'articles.article_number';

    /**
     * the column name for the publisher_id field
     */
    public const COL_PUBLISHER_ID = 'articles.publisher_id';

    /**
     * the column name for the article_publisher field
     */
    public const COL_ARTICLE_PUBLISHER = 'articles.article_publisher';

    /**
     * the column name for the cycle_id field
     */
    public const COL_CYCLE_ID = 'articles.cycle_id';

    /**
     * the column name for the article_cycle field
     */
    public const COL_ARTICLE_CYCLE = 'articles.article_cycle';

    /**
     * the column name for the article_tome field
     */
    public const COL_ARTICLE_TOME = 'articles.article_tome';

    /**
     * the column name for the article_cover_version field
     */
    public const COL_ARTICLE_COVER_VERSION = 'articles.article_cover_version';

    /**
     * the column name for the article_availability field
     */
    public const COL_ARTICLE_AVAILABILITY = 'articles.article_availability';

    /**
     * the column name for the article_availability_dilicom field
     */
    public const COL_ARTICLE_AVAILABILITY_DILICOM = 'articles.article_availability_dilicom';

    /**
     * the column name for the article_preorder field
     */
    public const COL_ARTICLE_PREORDER = 'articles.article_preorder';

    /**
     * the column name for the article_price field
     */
    public const COL_ARTICLE_PRICE = 'articles.article_price';

    /**
     * the column name for the article_price_editable field
     */
    public const COL_ARTICLE_PRICE_EDITABLE = 'articles.article_price_editable';

    /**
     * the column name for the article_new_price field
     */
    public const COL_ARTICLE_NEW_PRICE = 'articles.article_new_price';

    /**
     * the column name for the article_category field
     */
    public const COL_ARTICLE_CATEGORY = 'articles.article_category';

    /**
     * the column name for the article_tva field
     */
    public const COL_ARTICLE_TVA = 'articles.article_tva';

    /**
     * the column name for the article_pdf_ean field
     */
    public const COL_ARTICLE_PDF_EAN = 'articles.article_pdf_ean';

    /**
     * the column name for the article_pdf_version field
     */
    public const COL_ARTICLE_PDF_VERSION = 'articles.article_pdf_version';

    /**
     * the column name for the article_epub_ean field
     */
    public const COL_ARTICLE_EPUB_EAN = 'articles.article_epub_ean';

    /**
     * the column name for the article_epub_version field
     */
    public const COL_ARTICLE_EPUB_VERSION = 'articles.article_epub_version';

    /**
     * the column name for the article_azw_ean field
     */
    public const COL_ARTICLE_AZW_EAN = 'articles.article_azw_ean';

    /**
     * the column name for the article_azw_version field
     */
    public const COL_ARTICLE_AZW_VERSION = 'articles.article_azw_version';

    /**
     * the column name for the article_pages field
     */
    public const COL_ARTICLE_PAGES = 'articles.article_pages';

    /**
     * the column name for the article_weight field
     */
    public const COL_ARTICLE_WEIGHT = 'articles.article_weight';

    /**
     * the column name for the article_shaping field
     */
    public const COL_ARTICLE_SHAPING = 'articles.article_shaping';

    /**
     * the column name for the article_format field
     */
    public const COL_ARTICLE_FORMAT = 'articles.article_format';

    /**
     * the column name for the article_printing_process field
     */
    public const COL_ARTICLE_PRINTING_PROCESS = 'articles.article_printing_process';

    /**
     * the column name for the article_age_min field
     */
    public const COL_ARTICLE_AGE_MIN = 'articles.article_age_min';

    /**
     * the column name for the article_age_max field
     */
    public const COL_ARTICLE_AGE_MAX = 'articles.article_age_max';

    /**
     * the column name for the article_summary field
     */
    public const COL_ARTICLE_SUMMARY = 'articles.article_summary';

    /**
     * the column name for the article_contents field
     */
    public const COL_ARTICLE_CONTENTS = 'articles.article_contents';

    /**
     * the column name for the article_bonus field
     */
    public const COL_ARTICLE_BONUS = 'articles.article_bonus';

    /**
     * the column name for the article_catchline field
     */
    public const COL_ARTICLE_CATCHLINE = 'articles.article_catchline';

    /**
     * the column name for the article_biography field
     */
    public const COL_ARTICLE_BIOGRAPHY = 'articles.article_biography';

    /**
     * the column name for the article_motsv field
     */
    public const COL_ARTICLE_MOTSV = 'articles.article_motsv';

    /**
     * the column name for the article_copyright field
     */
    public const COL_ARTICLE_COPYRIGHT = 'articles.article_copyright';

    /**
     * the column name for the article_pubdate field
     */
    public const COL_ARTICLE_PUBDATE = 'articles.article_pubdate';

    /**
     * the column name for the article_keywords field
     */
    public const COL_ARTICLE_KEYWORDS = 'articles.article_keywords';

    /**
     * the column name for the article_links field
     */
    public const COL_ARTICLE_LINKS = 'articles.article_links';

    /**
     * the column name for the article_keywords_generated field
     */
    public const COL_ARTICLE_KEYWORDS_GENERATED = 'articles.article_keywords_generated';

    /**
     * the column name for the article_publisher_stock field
     */
    public const COL_ARTICLE_PUBLISHER_STOCK = 'articles.article_publisher_stock';

    /**
     * the column name for the article_hits field
     */
    public const COL_ARTICLE_HITS = 'articles.article_hits';

    /**
     * the column name for the article_editing_user field
     */
    public const COL_ARTICLE_EDITING_USER = 'articles.article_editing_user';

    /**
     * the column name for the article_insert field
     */
    public const COL_ARTICLE_INSERT = 'articles.article_insert';

    /**
     * the column name for the article_update field
     */
    public const COL_ARTICLE_UPDATE = 'articles.article_update';

    /**
     * the column name for the article_created field
     */
    public const COL_ARTICLE_CREATED = 'articles.article_created';

    /**
     * the column name for the article_updated field
     */
    public const COL_ARTICLE_UPDATED = 'articles.article_updated';

    /**
     * the column name for the article_done field
     */
    public const COL_ARTICLE_DONE = 'articles.article_done';

    /**
     * the column name for the article_to_check field
     */
    public const COL_ARTICLE_TO_CHECK = 'articles.article_to_check';

    /**
     * the column name for the article_deletion_by field
     */
    public const COL_ARTICLE_DELETION_BY = 'articles.article_deletion_by';

    /**
     * the column name for the article_deletion_date field
     */
    public const COL_ARTICLE_DELETION_DATE = 'articles.article_deletion_date';

    /**
     * the column name for the article_deletion_reason field
     */
    public const COL_ARTICLE_DELETION_REASON = 'articles.article_deletion_reason';

    /**
     * the column name for the lemonink_master_id field
     */
    public const COL_LEMONINK_MASTER_ID = 'articles.lemonink_master_id';

    /**
     * The default string format for model objects of the related table
     */
    public const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     *
     * @var array<string, mixed>
     */
    protected static $fieldNames = [
        self::TYPE_PHPNAME       => ['Id', 'Item', 'Textid', 'Ean', 'EanOthers', 'Asin', 'NoosfereId', 'Url', 'TypeId', 'Title', 'TitleAlphabetic', 'TitleOriginal', 'TitleOthers', 'Subtitle', 'LangCurrent', 'LangOriginal', 'OriginCountry', 'ThemeBisac', 'ThemeClil', 'ThemeDewey', 'ThemeElectre', 'SourceId', 'Authors', 'AuthorsAlphabetic', 'CollectionId', 'CollectionName', 'Number', 'PublisherId', 'PublisherName', 'CycleId', 'CycleName', 'Tome', 'CoverVersion', 'Availability', 'AvailabilityDilicom', 'Preorder', 'Price', 'PriceEditable', 'NewPrice', 'Category', 'Tva', 'PdfEan', 'PdfVersion', 'EpubEan', 'EpubVersion', 'AzwEan', 'AzwVersion', 'Pages', 'Weight', 'Shaping', 'Format', 'PrintingProcess', 'AgeMin', 'AgeMax', 'Summary', 'Contents', 'Bonus', 'Catchline', 'Biography', 'Motsv', 'Copyright', 'Pubdate', 'Keywords', 'ComputedLinks', 'KeywordsGenerated', 'PublisherStock', 'Hits', 'EditingUser', 'Insert', 'Update', 'CreatedAt', 'UpdatedAt', 'Done', 'ToCheck', 'DeletionBy', 'DeletionDate', 'DeletionReason', 'LemoninkMasterId', ],
        self::TYPE_CAMELNAME     => ['id', 'item', 'textid', 'ean', 'eanOthers', 'asin', 'noosfereId', 'url', 'typeId', 'title', 'titleAlphabetic', 'titleOriginal', 'titleOthers', 'subtitle', 'langCurrent', 'langOriginal', 'originCountry', 'themeBisac', 'themeClil', 'themeDewey', 'themeElectre', 'sourceId', 'authors', 'authorsAlphabetic', 'collectionId', 'collectionName', 'number', 'publisherId', 'publisherName', 'cycleId', 'cycleName', 'tome', 'coverVersion', 'availability', 'availabilityDilicom', 'preorder', 'price', 'priceEditable', 'newPrice', 'category', 'tva', 'pdfEan', 'pdfVersion', 'epubEan', 'epubVersion', 'azwEan', 'azwVersion', 'pages', 'weight', 'shaping', 'format', 'printingProcess', 'ageMin', 'ageMax', 'summary', 'contents', 'bonus', 'catchline', 'biography', 'motsv', 'copyright', 'pubdate', 'keywords', 'computedLinks', 'keywordsGenerated', 'publisherStock', 'hits', 'editingUser', 'insert', 'update', 'createdAt', 'updatedAt', 'done', 'toCheck', 'deletionBy', 'deletionDate', 'deletionReason', 'lemoninkMasterId', ],
        self::TYPE_COLNAME       => [ArticleTableMap::COL_ARTICLE_ID, ArticleTableMap::COL_ARTICLE_ITEM, ArticleTableMap::COL_ARTICLE_TEXTID, ArticleTableMap::COL_ARTICLE_EAN, ArticleTableMap::COL_ARTICLE_EAN_OTHERS, ArticleTableMap::COL_ARTICLE_ASIN, ArticleTableMap::COL_ARTICLE_NOOSFERE_ID, ArticleTableMap::COL_ARTICLE_URL, ArticleTableMap::COL_TYPE_ID, ArticleTableMap::COL_ARTICLE_TITLE, ArticleTableMap::COL_ARTICLE_TITLE_ALPHABETIC, ArticleTableMap::COL_ARTICLE_TITLE_ORIGINAL, ArticleTableMap::COL_ARTICLE_TITLE_OTHERS, ArticleTableMap::COL_ARTICLE_SUBTITLE, ArticleTableMap::COL_ARTICLE_LANG_CURRENT, ArticleTableMap::COL_ARTICLE_LANG_ORIGINAL, ArticleTableMap::COL_ARTICLE_ORIGIN_COUNTRY, ArticleTableMap::COL_ARTICLE_THEME_BISAC, ArticleTableMap::COL_ARTICLE_THEME_CLIL, ArticleTableMap::COL_ARTICLE_THEME_DEWEY, ArticleTableMap::COL_ARTICLE_THEME_ELECTRE, ArticleTableMap::COL_ARTICLE_SOURCE_ID, ArticleTableMap::COL_ARTICLE_AUTHORS, ArticleTableMap::COL_ARTICLE_AUTHORS_ALPHABETIC, ArticleTableMap::COL_COLLECTION_ID, ArticleTableMap::COL_ARTICLE_COLLECTION, ArticleTableMap::COL_ARTICLE_NUMBER, ArticleTableMap::COL_PUBLISHER_ID, ArticleTableMap::COL_ARTICLE_PUBLISHER, ArticleTableMap::COL_CYCLE_ID, ArticleTableMap::COL_ARTICLE_CYCLE, ArticleTableMap::COL_ARTICLE_TOME, ArticleTableMap::COL_ARTICLE_COVER_VERSION, ArticleTableMap::COL_ARTICLE_AVAILABILITY, ArticleTableMap::COL_ARTICLE_AVAILABILITY_DILICOM, ArticleTableMap::COL_ARTICLE_PREORDER, ArticleTableMap::COL_ARTICLE_PRICE, ArticleTableMap::COL_ARTICLE_PRICE_EDITABLE, ArticleTableMap::COL_ARTICLE_NEW_PRICE, ArticleTableMap::COL_ARTICLE_CATEGORY, ArticleTableMap::COL_ARTICLE_TVA, ArticleTableMap::COL_ARTICLE_PDF_EAN, ArticleTableMap::COL_ARTICLE_PDF_VERSION, ArticleTableMap::COL_ARTICLE_EPUB_EAN, ArticleTableMap::COL_ARTICLE_EPUB_VERSION, ArticleTableMap::COL_ARTICLE_AZW_EAN, ArticleTableMap::COL_ARTICLE_AZW_VERSION, ArticleTableMap::COL_ARTICLE_PAGES, ArticleTableMap::COL_ARTICLE_WEIGHT, ArticleTableMap::COL_ARTICLE_SHAPING, ArticleTableMap::COL_ARTICLE_FORMAT, ArticleTableMap::COL_ARTICLE_PRINTING_PROCESS, ArticleTableMap::COL_ARTICLE_AGE_MIN, ArticleTableMap::COL_ARTICLE_AGE_MAX, ArticleTableMap::COL_ARTICLE_SUMMARY, ArticleTableMap::COL_ARTICLE_CONTENTS, ArticleTableMap::COL_ARTICLE_BONUS, ArticleTableMap::COL_ARTICLE_CATCHLINE, ArticleTableMap::COL_ARTICLE_BIOGRAPHY, ArticleTableMap::COL_ARTICLE_MOTSV, ArticleTableMap::COL_ARTICLE_COPYRIGHT, ArticleTableMap::COL_ARTICLE_PUBDATE, ArticleTableMap::COL_ARTICLE_KEYWORDS, ArticleTableMap::COL_ARTICLE_LINKS, ArticleTableMap::COL_ARTICLE_KEYWORDS_GENERATED, ArticleTableMap::COL_ARTICLE_PUBLISHER_STOCK, ArticleTableMap::COL_ARTICLE_HITS, ArticleTableMap::COL_ARTICLE_EDITING_USER, ArticleTableMap::COL_ARTICLE_INSERT, ArticleTableMap::COL_ARTICLE_UPDATE, ArticleTableMap::COL_ARTICLE_CREATED, ArticleTableMap::COL_ARTICLE_UPDATED, ArticleTableMap::COL_ARTICLE_DONE, ArticleTableMap::COL_ARTICLE_TO_CHECK, ArticleTableMap::COL_ARTICLE_DELETION_BY, ArticleTableMap::COL_ARTICLE_DELETION_DATE, ArticleTableMap::COL_ARTICLE_DELETION_REASON, ArticleTableMap::COL_LEMONINK_MASTER_ID, ],
        self::TYPE_FIELDNAME     => ['article_id', 'article_item', 'article_textid', 'article_ean', 'article_ean_others', 'article_asin', 'article_noosfere_id', 'article_url', 'type_id', 'article_title', 'article_title_alphabetic', 'article_title_original', 'article_title_others', 'article_subtitle', 'article_lang_current', 'article_lang_original', 'article_origin_country', 'article_theme_bisac', 'article_theme_clil', 'article_theme_dewey', 'article_theme_electre', 'article_source_id', 'article_authors', 'article_authors_alphabetic', 'collection_id', 'article_collection', 'article_number', 'publisher_id', 'article_publisher', 'cycle_id', 'article_cycle', 'article_tome', 'article_cover_version', 'article_availability', 'article_availability_dilicom', 'article_preorder', 'article_price', 'article_price_editable', 'article_new_price', 'article_category', 'article_tva', 'article_pdf_ean', 'article_pdf_version', 'article_epub_ean', 'article_epub_version', 'article_azw_ean', 'article_azw_version', 'article_pages', 'article_weight', 'article_shaping', 'article_format', 'article_printing_process', 'article_age_min', 'article_age_max', 'article_summary', 'article_contents', 'article_bonus', 'article_catchline', 'article_biography', 'article_motsv', 'article_copyright', 'article_pubdate', 'article_keywords', 'article_links', 'article_keywords_generated', 'article_publisher_stock', 'article_hits', 'article_editing_user', 'article_insert', 'article_update', 'article_created', 'article_updated', 'article_done', 'article_to_check', 'article_deletion_by', 'article_deletion_date', 'article_deletion_reason', 'lemonink_master_id', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, ]
    ];

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     *
     * @var array<string, mixed>
     */
    protected static $fieldKeys = [
        self::TYPE_PHPNAME       => ['Id' => 0, 'Item' => 1, 'Textid' => 2, 'Ean' => 3, 'EanOthers' => 4, 'Asin' => 5, 'NoosfereId' => 6, 'Url' => 7, 'TypeId' => 8, 'Title' => 9, 'TitleAlphabetic' => 10, 'TitleOriginal' => 11, 'TitleOthers' => 12, 'Subtitle' => 13, 'LangCurrent' => 14, 'LangOriginal' => 15, 'OriginCountry' => 16, 'ThemeBisac' => 17, 'ThemeClil' => 18, 'ThemeDewey' => 19, 'ThemeElectre' => 20, 'SourceId' => 21, 'Authors' => 22, 'AuthorsAlphabetic' => 23, 'CollectionId' => 24, 'CollectionName' => 25, 'Number' => 26, 'PublisherId' => 27, 'PublisherName' => 28, 'CycleId' => 29, 'CycleName' => 30, 'Tome' => 31, 'CoverVersion' => 32, 'Availability' => 33, 'AvailabilityDilicom' => 34, 'Preorder' => 35, 'Price' => 36, 'PriceEditable' => 37, 'NewPrice' => 38, 'Category' => 39, 'Tva' => 40, 'PdfEan' => 41, 'PdfVersion' => 42, 'EpubEan' => 43, 'EpubVersion' => 44, 'AzwEan' => 45, 'AzwVersion' => 46, 'Pages' => 47, 'Weight' => 48, 'Shaping' => 49, 'Format' => 50, 'PrintingProcess' => 51, 'AgeMin' => 52, 'AgeMax' => 53, 'Summary' => 54, 'Contents' => 55, 'Bonus' => 56, 'Catchline' => 57, 'Biography' => 58, 'Motsv' => 59, 'Copyright' => 60, 'Pubdate' => 61, 'Keywords' => 62, 'ComputedLinks' => 63, 'KeywordsGenerated' => 64, 'PublisherStock' => 65, 'Hits' => 66, 'EditingUser' => 67, 'Insert' => 68, 'Update' => 69, 'CreatedAt' => 70, 'UpdatedAt' => 71, 'Done' => 72, 'ToCheck' => 73, 'DeletionBy' => 74, 'DeletionDate' => 75, 'DeletionReason' => 76, 'LemoninkMasterId' => 77, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'item' => 1, 'textid' => 2, 'ean' => 3, 'eanOthers' => 4, 'asin' => 5, 'noosfereId' => 6, 'url' => 7, 'typeId' => 8, 'title' => 9, 'titleAlphabetic' => 10, 'titleOriginal' => 11, 'titleOthers' => 12, 'subtitle' => 13, 'langCurrent' => 14, 'langOriginal' => 15, 'originCountry' => 16, 'themeBisac' => 17, 'themeClil' => 18, 'themeDewey' => 19, 'themeElectre' => 20, 'sourceId' => 21, 'authors' => 22, 'authorsAlphabetic' => 23, 'collectionId' => 24, 'collectionName' => 25, 'number' => 26, 'publisherId' => 27, 'publisherName' => 28, 'cycleId' => 29, 'cycleName' => 30, 'tome' => 31, 'coverVersion' => 32, 'availability' => 33, 'availabilityDilicom' => 34, 'preorder' => 35, 'price' => 36, 'priceEditable' => 37, 'newPrice' => 38, 'category' => 39, 'tva' => 40, 'pdfEan' => 41, 'pdfVersion' => 42, 'epubEan' => 43, 'epubVersion' => 44, 'azwEan' => 45, 'azwVersion' => 46, 'pages' => 47, 'weight' => 48, 'shaping' => 49, 'format' => 50, 'printingProcess' => 51, 'ageMin' => 52, 'ageMax' => 53, 'summary' => 54, 'contents' => 55, 'bonus' => 56, 'catchline' => 57, 'biography' => 58, 'motsv' => 59, 'copyright' => 60, 'pubdate' => 61, 'keywords' => 62, 'computedLinks' => 63, 'keywordsGenerated' => 64, 'publisherStock' => 65, 'hits' => 66, 'editingUser' => 67, 'insert' => 68, 'update' => 69, 'createdAt' => 70, 'updatedAt' => 71, 'done' => 72, 'toCheck' => 73, 'deletionBy' => 74, 'deletionDate' => 75, 'deletionReason' => 76, 'lemoninkMasterId' => 77, ],
        self::TYPE_COLNAME       => [ArticleTableMap::COL_ARTICLE_ID => 0, ArticleTableMap::COL_ARTICLE_ITEM => 1, ArticleTableMap::COL_ARTICLE_TEXTID => 2, ArticleTableMap::COL_ARTICLE_EAN => 3, ArticleTableMap::COL_ARTICLE_EAN_OTHERS => 4, ArticleTableMap::COL_ARTICLE_ASIN => 5, ArticleTableMap::COL_ARTICLE_NOOSFERE_ID => 6, ArticleTableMap::COL_ARTICLE_URL => 7, ArticleTableMap::COL_TYPE_ID => 8, ArticleTableMap::COL_ARTICLE_TITLE => 9, ArticleTableMap::COL_ARTICLE_TITLE_ALPHABETIC => 10, ArticleTableMap::COL_ARTICLE_TITLE_ORIGINAL => 11, ArticleTableMap::COL_ARTICLE_TITLE_OTHERS => 12, ArticleTableMap::COL_ARTICLE_SUBTITLE => 13, ArticleTableMap::COL_ARTICLE_LANG_CURRENT => 14, ArticleTableMap::COL_ARTICLE_LANG_ORIGINAL => 15, ArticleTableMap::COL_ARTICLE_ORIGIN_COUNTRY => 16, ArticleTableMap::COL_ARTICLE_THEME_BISAC => 17, ArticleTableMap::COL_ARTICLE_THEME_CLIL => 18, ArticleTableMap::COL_ARTICLE_THEME_DEWEY => 19, ArticleTableMap::COL_ARTICLE_THEME_ELECTRE => 20, ArticleTableMap::COL_ARTICLE_SOURCE_ID => 21, ArticleTableMap::COL_ARTICLE_AUTHORS => 22, ArticleTableMap::COL_ARTICLE_AUTHORS_ALPHABETIC => 23, ArticleTableMap::COL_COLLECTION_ID => 24, ArticleTableMap::COL_ARTICLE_COLLECTION => 25, ArticleTableMap::COL_ARTICLE_NUMBER => 26, ArticleTableMap::COL_PUBLISHER_ID => 27, ArticleTableMap::COL_ARTICLE_PUBLISHER => 28, ArticleTableMap::COL_CYCLE_ID => 29, ArticleTableMap::COL_ARTICLE_CYCLE => 30, ArticleTableMap::COL_ARTICLE_TOME => 31, ArticleTableMap::COL_ARTICLE_COVER_VERSION => 32, ArticleTableMap::COL_ARTICLE_AVAILABILITY => 33, ArticleTableMap::COL_ARTICLE_AVAILABILITY_DILICOM => 34, ArticleTableMap::COL_ARTICLE_PREORDER => 35, ArticleTableMap::COL_ARTICLE_PRICE => 36, ArticleTableMap::COL_ARTICLE_PRICE_EDITABLE => 37, ArticleTableMap::COL_ARTICLE_NEW_PRICE => 38, ArticleTableMap::COL_ARTICLE_CATEGORY => 39, ArticleTableMap::COL_ARTICLE_TVA => 40, ArticleTableMap::COL_ARTICLE_PDF_EAN => 41, ArticleTableMap::COL_ARTICLE_PDF_VERSION => 42, ArticleTableMap::COL_ARTICLE_EPUB_EAN => 43, ArticleTableMap::COL_ARTICLE_EPUB_VERSION => 44, ArticleTableMap::COL_ARTICLE_AZW_EAN => 45, ArticleTableMap::COL_ARTICLE_AZW_VERSION => 46, ArticleTableMap::COL_ARTICLE_PAGES => 47, ArticleTableMap::COL_ARTICLE_WEIGHT => 48, ArticleTableMap::COL_ARTICLE_SHAPING => 49, ArticleTableMap::COL_ARTICLE_FORMAT => 50, ArticleTableMap::COL_ARTICLE_PRINTING_PROCESS => 51, ArticleTableMap::COL_ARTICLE_AGE_MIN => 52, ArticleTableMap::COL_ARTICLE_AGE_MAX => 53, ArticleTableMap::COL_ARTICLE_SUMMARY => 54, ArticleTableMap::COL_ARTICLE_CONTENTS => 55, ArticleTableMap::COL_ARTICLE_BONUS => 56, ArticleTableMap::COL_ARTICLE_CATCHLINE => 57, ArticleTableMap::COL_ARTICLE_BIOGRAPHY => 58, ArticleTableMap::COL_ARTICLE_MOTSV => 59, ArticleTableMap::COL_ARTICLE_COPYRIGHT => 60, ArticleTableMap::COL_ARTICLE_PUBDATE => 61, ArticleTableMap::COL_ARTICLE_KEYWORDS => 62, ArticleTableMap::COL_ARTICLE_LINKS => 63, ArticleTableMap::COL_ARTICLE_KEYWORDS_GENERATED => 64, ArticleTableMap::COL_ARTICLE_PUBLISHER_STOCK => 65, ArticleTableMap::COL_ARTICLE_HITS => 66, ArticleTableMap::COL_ARTICLE_EDITING_USER => 67, ArticleTableMap::COL_ARTICLE_INSERT => 68, ArticleTableMap::COL_ARTICLE_UPDATE => 69, ArticleTableMap::COL_ARTICLE_CREATED => 70, ArticleTableMap::COL_ARTICLE_UPDATED => 71, ArticleTableMap::COL_ARTICLE_DONE => 72, ArticleTableMap::COL_ARTICLE_TO_CHECK => 73, ArticleTableMap::COL_ARTICLE_DELETION_BY => 74, ArticleTableMap::COL_ARTICLE_DELETION_DATE => 75, ArticleTableMap::COL_ARTICLE_DELETION_REASON => 76, ArticleTableMap::COL_LEMONINK_MASTER_ID => 77, ],
        self::TYPE_FIELDNAME     => ['article_id' => 0, 'article_item' => 1, 'article_textid' => 2, 'article_ean' => 3, 'article_ean_others' => 4, 'article_asin' => 5, 'article_noosfere_id' => 6, 'article_url' => 7, 'type_id' => 8, 'article_title' => 9, 'article_title_alphabetic' => 10, 'article_title_original' => 11, 'article_title_others' => 12, 'article_subtitle' => 13, 'article_lang_current' => 14, 'article_lang_original' => 15, 'article_origin_country' => 16, 'article_theme_bisac' => 17, 'article_theme_clil' => 18, 'article_theme_dewey' => 19, 'article_theme_electre' => 20, 'article_source_id' => 21, 'article_authors' => 22, 'article_authors_alphabetic' => 23, 'collection_id' => 24, 'article_collection' => 25, 'article_number' => 26, 'publisher_id' => 27, 'article_publisher' => 28, 'cycle_id' => 29, 'article_cycle' => 30, 'article_tome' => 31, 'article_cover_version' => 32, 'article_availability' => 33, 'article_availability_dilicom' => 34, 'article_preorder' => 35, 'article_price' => 36, 'article_price_editable' => 37, 'article_new_price' => 38, 'article_category' => 39, 'article_tva' => 40, 'article_pdf_ean' => 41, 'article_pdf_version' => 42, 'article_epub_ean' => 43, 'article_epub_version' => 44, 'article_azw_ean' => 45, 'article_azw_version' => 46, 'article_pages' => 47, 'article_weight' => 48, 'article_shaping' => 49, 'article_format' => 50, 'article_printing_process' => 51, 'article_age_min' => 52, 'article_age_max' => 53, 'article_summary' => 54, 'article_contents' => 55, 'article_bonus' => 56, 'article_catchline' => 57, 'article_biography' => 58, 'article_motsv' => 59, 'article_copyright' => 60, 'article_pubdate' => 61, 'article_keywords' => 62, 'article_links' => 63, 'article_keywords_generated' => 64, 'article_publisher_stock' => 65, 'article_hits' => 66, 'article_editing_user' => 67, 'article_insert' => 68, 'article_update' => 69, 'article_created' => 70, 'article_updated' => 71, 'article_done' => 72, 'article_to_check' => 73, 'article_deletion_by' => 74, 'article_deletion_date' => 75, 'article_deletion_reason' => 76, 'lemonink_master_id' => 77, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'ARTICLE_ID',
        'Article.Id' => 'ARTICLE_ID',
        'id' => 'ARTICLE_ID',
        'article.id' => 'ARTICLE_ID',
        'ArticleTableMap::COL_ARTICLE_ID' => 'ARTICLE_ID',
        'COL_ARTICLE_ID' => 'ARTICLE_ID',
        'article_id' => 'ARTICLE_ID',
        'articles.article_id' => 'ARTICLE_ID',
        'Item' => 'ARTICLE_ITEM',
        'Article.Item' => 'ARTICLE_ITEM',
        'item' => 'ARTICLE_ITEM',
        'article.item' => 'ARTICLE_ITEM',
        'ArticleTableMap::COL_ARTICLE_ITEM' => 'ARTICLE_ITEM',
        'COL_ARTICLE_ITEM' => 'ARTICLE_ITEM',
        'article_item' => 'ARTICLE_ITEM',
        'articles.article_item' => 'ARTICLE_ITEM',
        'Textid' => 'ARTICLE_TEXTID',
        'Article.Textid' => 'ARTICLE_TEXTID',
        'textid' => 'ARTICLE_TEXTID',
        'article.textid' => 'ARTICLE_TEXTID',
        'ArticleTableMap::COL_ARTICLE_TEXTID' => 'ARTICLE_TEXTID',
        'COL_ARTICLE_TEXTID' => 'ARTICLE_TEXTID',
        'article_textid' => 'ARTICLE_TEXTID',
        'articles.article_textid' => 'ARTICLE_TEXTID',
        'Ean' => 'ARTICLE_EAN',
        'Article.Ean' => 'ARTICLE_EAN',
        'ean' => 'ARTICLE_EAN',
        'article.ean' => 'ARTICLE_EAN',
        'ArticleTableMap::COL_ARTICLE_EAN' => 'ARTICLE_EAN',
        'COL_ARTICLE_EAN' => 'ARTICLE_EAN',
        'article_ean' => 'ARTICLE_EAN',
        'articles.article_ean' => 'ARTICLE_EAN',
        'EanOthers' => 'ARTICLE_EAN_OTHERS',
        'Article.EanOthers' => 'ARTICLE_EAN_OTHERS',
        'eanOthers' => 'ARTICLE_EAN_OTHERS',
        'article.eanOthers' => 'ARTICLE_EAN_OTHERS',
        'ArticleTableMap::COL_ARTICLE_EAN_OTHERS' => 'ARTICLE_EAN_OTHERS',
        'COL_ARTICLE_EAN_OTHERS' => 'ARTICLE_EAN_OTHERS',
        'article_ean_others' => 'ARTICLE_EAN_OTHERS',
        'articles.article_ean_others' => 'ARTICLE_EAN_OTHERS',
        'Asin' => 'ARTICLE_ASIN',
        'Article.Asin' => 'ARTICLE_ASIN',
        'asin' => 'ARTICLE_ASIN',
        'article.asin' => 'ARTICLE_ASIN',
        'ArticleTableMap::COL_ARTICLE_ASIN' => 'ARTICLE_ASIN',
        'COL_ARTICLE_ASIN' => 'ARTICLE_ASIN',
        'article_asin' => 'ARTICLE_ASIN',
        'articles.article_asin' => 'ARTICLE_ASIN',
        'NoosfereId' => 'ARTICLE_NOOSFERE_ID',
        'Article.NoosfereId' => 'ARTICLE_NOOSFERE_ID',
        'noosfereId' => 'ARTICLE_NOOSFERE_ID',
        'article.noosfereId' => 'ARTICLE_NOOSFERE_ID',
        'ArticleTableMap::COL_ARTICLE_NOOSFERE_ID' => 'ARTICLE_NOOSFERE_ID',
        'COL_ARTICLE_NOOSFERE_ID' => 'ARTICLE_NOOSFERE_ID',
        'article_noosfere_id' => 'ARTICLE_NOOSFERE_ID',
        'articles.article_noosfere_id' => 'ARTICLE_NOOSFERE_ID',
        'Url' => 'ARTICLE_URL',
        'Article.Url' => 'ARTICLE_URL',
        'url' => 'ARTICLE_URL',
        'article.url' => 'ARTICLE_URL',
        'ArticleTableMap::COL_ARTICLE_URL' => 'ARTICLE_URL',
        'COL_ARTICLE_URL' => 'ARTICLE_URL',
        'article_url' => 'ARTICLE_URL',
        'articles.article_url' => 'ARTICLE_URL',
        'TypeId' => 'TYPE_ID',
        'Article.TypeId' => 'TYPE_ID',
        'typeId' => 'TYPE_ID',
        'article.typeId' => 'TYPE_ID',
        'ArticleTableMap::COL_TYPE_ID' => 'TYPE_ID',
        'COL_TYPE_ID' => 'TYPE_ID',
        'type_id' => 'TYPE_ID',
        'articles.type_id' => 'TYPE_ID',
        'Title' => 'ARTICLE_TITLE',
        'Article.Title' => 'ARTICLE_TITLE',
        'title' => 'ARTICLE_TITLE',
        'article.title' => 'ARTICLE_TITLE',
        'ArticleTableMap::COL_ARTICLE_TITLE' => 'ARTICLE_TITLE',
        'COL_ARTICLE_TITLE' => 'ARTICLE_TITLE',
        'article_title' => 'ARTICLE_TITLE',
        'articles.article_title' => 'ARTICLE_TITLE',
        'TitleAlphabetic' => 'ARTICLE_TITLE_ALPHABETIC',
        'Article.TitleAlphabetic' => 'ARTICLE_TITLE_ALPHABETIC',
        'titleAlphabetic' => 'ARTICLE_TITLE_ALPHABETIC',
        'article.titleAlphabetic' => 'ARTICLE_TITLE_ALPHABETIC',
        'ArticleTableMap::COL_ARTICLE_TITLE_ALPHABETIC' => 'ARTICLE_TITLE_ALPHABETIC',
        'COL_ARTICLE_TITLE_ALPHABETIC' => 'ARTICLE_TITLE_ALPHABETIC',
        'article_title_alphabetic' => 'ARTICLE_TITLE_ALPHABETIC',
        'articles.article_title_alphabetic' => 'ARTICLE_TITLE_ALPHABETIC',
        'TitleOriginal' => 'ARTICLE_TITLE_ORIGINAL',
        'Article.TitleOriginal' => 'ARTICLE_TITLE_ORIGINAL',
        'titleOriginal' => 'ARTICLE_TITLE_ORIGINAL',
        'article.titleOriginal' => 'ARTICLE_TITLE_ORIGINAL',
        'ArticleTableMap::COL_ARTICLE_TITLE_ORIGINAL' => 'ARTICLE_TITLE_ORIGINAL',
        'COL_ARTICLE_TITLE_ORIGINAL' => 'ARTICLE_TITLE_ORIGINAL',
        'article_title_original' => 'ARTICLE_TITLE_ORIGINAL',
        'articles.article_title_original' => 'ARTICLE_TITLE_ORIGINAL',
        'TitleOthers' => 'ARTICLE_TITLE_OTHERS',
        'Article.TitleOthers' => 'ARTICLE_TITLE_OTHERS',
        'titleOthers' => 'ARTICLE_TITLE_OTHERS',
        'article.titleOthers' => 'ARTICLE_TITLE_OTHERS',
        'ArticleTableMap::COL_ARTICLE_TITLE_OTHERS' => 'ARTICLE_TITLE_OTHERS',
        'COL_ARTICLE_TITLE_OTHERS' => 'ARTICLE_TITLE_OTHERS',
        'article_title_others' => 'ARTICLE_TITLE_OTHERS',
        'articles.article_title_others' => 'ARTICLE_TITLE_OTHERS',
        'Subtitle' => 'ARTICLE_SUBTITLE',
        'Article.Subtitle' => 'ARTICLE_SUBTITLE',
        'subtitle' => 'ARTICLE_SUBTITLE',
        'article.subtitle' => 'ARTICLE_SUBTITLE',
        'ArticleTableMap::COL_ARTICLE_SUBTITLE' => 'ARTICLE_SUBTITLE',
        'COL_ARTICLE_SUBTITLE' => 'ARTICLE_SUBTITLE',
        'article_subtitle' => 'ARTICLE_SUBTITLE',
        'articles.article_subtitle' => 'ARTICLE_SUBTITLE',
        'LangCurrent' => 'ARTICLE_LANG_CURRENT',
        'Article.LangCurrent' => 'ARTICLE_LANG_CURRENT',
        'langCurrent' => 'ARTICLE_LANG_CURRENT',
        'article.langCurrent' => 'ARTICLE_LANG_CURRENT',
        'ArticleTableMap::COL_ARTICLE_LANG_CURRENT' => 'ARTICLE_LANG_CURRENT',
        'COL_ARTICLE_LANG_CURRENT' => 'ARTICLE_LANG_CURRENT',
        'article_lang_current' => 'ARTICLE_LANG_CURRENT',
        'articles.article_lang_current' => 'ARTICLE_LANG_CURRENT',
        'LangOriginal' => 'ARTICLE_LANG_ORIGINAL',
        'Article.LangOriginal' => 'ARTICLE_LANG_ORIGINAL',
        'langOriginal' => 'ARTICLE_LANG_ORIGINAL',
        'article.langOriginal' => 'ARTICLE_LANG_ORIGINAL',
        'ArticleTableMap::COL_ARTICLE_LANG_ORIGINAL' => 'ARTICLE_LANG_ORIGINAL',
        'COL_ARTICLE_LANG_ORIGINAL' => 'ARTICLE_LANG_ORIGINAL',
        'article_lang_original' => 'ARTICLE_LANG_ORIGINAL',
        'articles.article_lang_original' => 'ARTICLE_LANG_ORIGINAL',
        'OriginCountry' => 'ARTICLE_ORIGIN_COUNTRY',
        'Article.OriginCountry' => 'ARTICLE_ORIGIN_COUNTRY',
        'originCountry' => 'ARTICLE_ORIGIN_COUNTRY',
        'article.originCountry' => 'ARTICLE_ORIGIN_COUNTRY',
        'ArticleTableMap::COL_ARTICLE_ORIGIN_COUNTRY' => 'ARTICLE_ORIGIN_COUNTRY',
        'COL_ARTICLE_ORIGIN_COUNTRY' => 'ARTICLE_ORIGIN_COUNTRY',
        'article_origin_country' => 'ARTICLE_ORIGIN_COUNTRY',
        'articles.article_origin_country' => 'ARTICLE_ORIGIN_COUNTRY',
        'ThemeBisac' => 'ARTICLE_THEME_BISAC',
        'Article.ThemeBisac' => 'ARTICLE_THEME_BISAC',
        'themeBisac' => 'ARTICLE_THEME_BISAC',
        'article.themeBisac' => 'ARTICLE_THEME_BISAC',
        'ArticleTableMap::COL_ARTICLE_THEME_BISAC' => 'ARTICLE_THEME_BISAC',
        'COL_ARTICLE_THEME_BISAC' => 'ARTICLE_THEME_BISAC',
        'article_theme_bisac' => 'ARTICLE_THEME_BISAC',
        'articles.article_theme_bisac' => 'ARTICLE_THEME_BISAC',
        'ThemeClil' => 'ARTICLE_THEME_CLIL',
        'Article.ThemeClil' => 'ARTICLE_THEME_CLIL',
        'themeClil' => 'ARTICLE_THEME_CLIL',
        'article.themeClil' => 'ARTICLE_THEME_CLIL',
        'ArticleTableMap::COL_ARTICLE_THEME_CLIL' => 'ARTICLE_THEME_CLIL',
        'COL_ARTICLE_THEME_CLIL' => 'ARTICLE_THEME_CLIL',
        'article_theme_clil' => 'ARTICLE_THEME_CLIL',
        'articles.article_theme_clil' => 'ARTICLE_THEME_CLIL',
        'ThemeDewey' => 'ARTICLE_THEME_DEWEY',
        'Article.ThemeDewey' => 'ARTICLE_THEME_DEWEY',
        'themeDewey' => 'ARTICLE_THEME_DEWEY',
        'article.themeDewey' => 'ARTICLE_THEME_DEWEY',
        'ArticleTableMap::COL_ARTICLE_THEME_DEWEY' => 'ARTICLE_THEME_DEWEY',
        'COL_ARTICLE_THEME_DEWEY' => 'ARTICLE_THEME_DEWEY',
        'article_theme_dewey' => 'ARTICLE_THEME_DEWEY',
        'articles.article_theme_dewey' => 'ARTICLE_THEME_DEWEY',
        'ThemeElectre' => 'ARTICLE_THEME_ELECTRE',
        'Article.ThemeElectre' => 'ARTICLE_THEME_ELECTRE',
        'themeElectre' => 'ARTICLE_THEME_ELECTRE',
        'article.themeElectre' => 'ARTICLE_THEME_ELECTRE',
        'ArticleTableMap::COL_ARTICLE_THEME_ELECTRE' => 'ARTICLE_THEME_ELECTRE',
        'COL_ARTICLE_THEME_ELECTRE' => 'ARTICLE_THEME_ELECTRE',
        'article_theme_electre' => 'ARTICLE_THEME_ELECTRE',
        'articles.article_theme_electre' => 'ARTICLE_THEME_ELECTRE',
        'SourceId' => 'ARTICLE_SOURCE_ID',
        'Article.SourceId' => 'ARTICLE_SOURCE_ID',
        'sourceId' => 'ARTICLE_SOURCE_ID',
        'article.sourceId' => 'ARTICLE_SOURCE_ID',
        'ArticleTableMap::COL_ARTICLE_SOURCE_ID' => 'ARTICLE_SOURCE_ID',
        'COL_ARTICLE_SOURCE_ID' => 'ARTICLE_SOURCE_ID',
        'article_source_id' => 'ARTICLE_SOURCE_ID',
        'articles.article_source_id' => 'ARTICLE_SOURCE_ID',
        'Authors' => 'ARTICLE_AUTHORS',
        'Article.Authors' => 'ARTICLE_AUTHORS',
        'authors' => 'ARTICLE_AUTHORS',
        'article.authors' => 'ARTICLE_AUTHORS',
        'ArticleTableMap::COL_ARTICLE_AUTHORS' => 'ARTICLE_AUTHORS',
        'COL_ARTICLE_AUTHORS' => 'ARTICLE_AUTHORS',
        'article_authors' => 'ARTICLE_AUTHORS',
        'articles.article_authors' => 'ARTICLE_AUTHORS',
        'AuthorsAlphabetic' => 'ARTICLE_AUTHORS_ALPHABETIC',
        'Article.AuthorsAlphabetic' => 'ARTICLE_AUTHORS_ALPHABETIC',
        'authorsAlphabetic' => 'ARTICLE_AUTHORS_ALPHABETIC',
        'article.authorsAlphabetic' => 'ARTICLE_AUTHORS_ALPHABETIC',
        'ArticleTableMap::COL_ARTICLE_AUTHORS_ALPHABETIC' => 'ARTICLE_AUTHORS_ALPHABETIC',
        'COL_ARTICLE_AUTHORS_ALPHABETIC' => 'ARTICLE_AUTHORS_ALPHABETIC',
        'article_authors_alphabetic' => 'ARTICLE_AUTHORS_ALPHABETIC',
        'articles.article_authors_alphabetic' => 'ARTICLE_AUTHORS_ALPHABETIC',
        'CollectionId' => 'COLLECTION_ID',
        'Article.CollectionId' => 'COLLECTION_ID',
        'collectionId' => 'COLLECTION_ID',
        'article.collectionId' => 'COLLECTION_ID',
        'ArticleTableMap::COL_COLLECTION_ID' => 'COLLECTION_ID',
        'COL_COLLECTION_ID' => 'COLLECTION_ID',
        'collection_id' => 'COLLECTION_ID',
        'articles.collection_id' => 'COLLECTION_ID',
        'CollectionName' => 'ARTICLE_COLLECTION',
        'Article.CollectionName' => 'ARTICLE_COLLECTION',
        'collectionName' => 'ARTICLE_COLLECTION',
        'article.collectionName' => 'ARTICLE_COLLECTION',
        'ArticleTableMap::COL_ARTICLE_COLLECTION' => 'ARTICLE_COLLECTION',
        'COL_ARTICLE_COLLECTION' => 'ARTICLE_COLLECTION',
        'article_collection' => 'ARTICLE_COLLECTION',
        'articles.article_collection' => 'ARTICLE_COLLECTION',
        'Number' => 'ARTICLE_NUMBER',
        'Article.Number' => 'ARTICLE_NUMBER',
        'number' => 'ARTICLE_NUMBER',
        'article.number' => 'ARTICLE_NUMBER',
        'ArticleTableMap::COL_ARTICLE_NUMBER' => 'ARTICLE_NUMBER',
        'COL_ARTICLE_NUMBER' => 'ARTICLE_NUMBER',
        'article_number' => 'ARTICLE_NUMBER',
        'articles.article_number' => 'ARTICLE_NUMBER',
        'PublisherId' => 'PUBLISHER_ID',
        'Article.PublisherId' => 'PUBLISHER_ID',
        'publisherId' => 'PUBLISHER_ID',
        'article.publisherId' => 'PUBLISHER_ID',
        'ArticleTableMap::COL_PUBLISHER_ID' => 'PUBLISHER_ID',
        'COL_PUBLISHER_ID' => 'PUBLISHER_ID',
        'publisher_id' => 'PUBLISHER_ID',
        'articles.publisher_id' => 'PUBLISHER_ID',
        'PublisherName' => 'ARTICLE_PUBLISHER',
        'Article.PublisherName' => 'ARTICLE_PUBLISHER',
        'publisherName' => 'ARTICLE_PUBLISHER',
        'article.publisherName' => 'ARTICLE_PUBLISHER',
        'ArticleTableMap::COL_ARTICLE_PUBLISHER' => 'ARTICLE_PUBLISHER',
        'COL_ARTICLE_PUBLISHER' => 'ARTICLE_PUBLISHER',
        'article_publisher' => 'ARTICLE_PUBLISHER',
        'articles.article_publisher' => 'ARTICLE_PUBLISHER',
        'CycleId' => 'CYCLE_ID',
        'Article.CycleId' => 'CYCLE_ID',
        'cycleId' => 'CYCLE_ID',
        'article.cycleId' => 'CYCLE_ID',
        'ArticleTableMap::COL_CYCLE_ID' => 'CYCLE_ID',
        'COL_CYCLE_ID' => 'CYCLE_ID',
        'cycle_id' => 'CYCLE_ID',
        'articles.cycle_id' => 'CYCLE_ID',
        'CycleName' => 'ARTICLE_CYCLE',
        'Article.CycleName' => 'ARTICLE_CYCLE',
        'cycleName' => 'ARTICLE_CYCLE',
        'article.cycleName' => 'ARTICLE_CYCLE',
        'ArticleTableMap::COL_ARTICLE_CYCLE' => 'ARTICLE_CYCLE',
        'COL_ARTICLE_CYCLE' => 'ARTICLE_CYCLE',
        'article_cycle' => 'ARTICLE_CYCLE',
        'articles.article_cycle' => 'ARTICLE_CYCLE',
        'Tome' => 'ARTICLE_TOME',
        'Article.Tome' => 'ARTICLE_TOME',
        'tome' => 'ARTICLE_TOME',
        'article.tome' => 'ARTICLE_TOME',
        'ArticleTableMap::COL_ARTICLE_TOME' => 'ARTICLE_TOME',
        'COL_ARTICLE_TOME' => 'ARTICLE_TOME',
        'article_tome' => 'ARTICLE_TOME',
        'articles.article_tome' => 'ARTICLE_TOME',
        'CoverVersion' => 'ARTICLE_COVER_VERSION',
        'Article.CoverVersion' => 'ARTICLE_COVER_VERSION',
        'coverVersion' => 'ARTICLE_COVER_VERSION',
        'article.coverVersion' => 'ARTICLE_COVER_VERSION',
        'ArticleTableMap::COL_ARTICLE_COVER_VERSION' => 'ARTICLE_COVER_VERSION',
        'COL_ARTICLE_COVER_VERSION' => 'ARTICLE_COVER_VERSION',
        'article_cover_version' => 'ARTICLE_COVER_VERSION',
        'articles.article_cover_version' => 'ARTICLE_COVER_VERSION',
        'Availability' => 'ARTICLE_AVAILABILITY',
        'Article.Availability' => 'ARTICLE_AVAILABILITY',
        'availability' => 'ARTICLE_AVAILABILITY',
        'article.availability' => 'ARTICLE_AVAILABILITY',
        'ArticleTableMap::COL_ARTICLE_AVAILABILITY' => 'ARTICLE_AVAILABILITY',
        'COL_ARTICLE_AVAILABILITY' => 'ARTICLE_AVAILABILITY',
        'article_availability' => 'ARTICLE_AVAILABILITY',
        'articles.article_availability' => 'ARTICLE_AVAILABILITY',
        'AvailabilityDilicom' => 'ARTICLE_AVAILABILITY_DILICOM',
        'Article.AvailabilityDilicom' => 'ARTICLE_AVAILABILITY_DILICOM',
        'availabilityDilicom' => 'ARTICLE_AVAILABILITY_DILICOM',
        'article.availabilityDilicom' => 'ARTICLE_AVAILABILITY_DILICOM',
        'ArticleTableMap::COL_ARTICLE_AVAILABILITY_DILICOM' => 'ARTICLE_AVAILABILITY_DILICOM',
        'COL_ARTICLE_AVAILABILITY_DILICOM' => 'ARTICLE_AVAILABILITY_DILICOM',
        'article_availability_dilicom' => 'ARTICLE_AVAILABILITY_DILICOM',
        'articles.article_availability_dilicom' => 'ARTICLE_AVAILABILITY_DILICOM',
        'Preorder' => 'ARTICLE_PREORDER',
        'Article.Preorder' => 'ARTICLE_PREORDER',
        'preorder' => 'ARTICLE_PREORDER',
        'article.preorder' => 'ARTICLE_PREORDER',
        'ArticleTableMap::COL_ARTICLE_PREORDER' => 'ARTICLE_PREORDER',
        'COL_ARTICLE_PREORDER' => 'ARTICLE_PREORDER',
        'article_preorder' => 'ARTICLE_PREORDER',
        'articles.article_preorder' => 'ARTICLE_PREORDER',
        'Price' => 'ARTICLE_PRICE',
        'Article.Price' => 'ARTICLE_PRICE',
        'price' => 'ARTICLE_PRICE',
        'article.price' => 'ARTICLE_PRICE',
        'ArticleTableMap::COL_ARTICLE_PRICE' => 'ARTICLE_PRICE',
        'COL_ARTICLE_PRICE' => 'ARTICLE_PRICE',
        'article_price' => 'ARTICLE_PRICE',
        'articles.article_price' => 'ARTICLE_PRICE',
        'PriceEditable' => 'ARTICLE_PRICE_EDITABLE',
        'Article.PriceEditable' => 'ARTICLE_PRICE_EDITABLE',
        'priceEditable' => 'ARTICLE_PRICE_EDITABLE',
        'article.priceEditable' => 'ARTICLE_PRICE_EDITABLE',
        'ArticleTableMap::COL_ARTICLE_PRICE_EDITABLE' => 'ARTICLE_PRICE_EDITABLE',
        'COL_ARTICLE_PRICE_EDITABLE' => 'ARTICLE_PRICE_EDITABLE',
        'article_price_editable' => 'ARTICLE_PRICE_EDITABLE',
        'articles.article_price_editable' => 'ARTICLE_PRICE_EDITABLE',
        'NewPrice' => 'ARTICLE_NEW_PRICE',
        'Article.NewPrice' => 'ARTICLE_NEW_PRICE',
        'newPrice' => 'ARTICLE_NEW_PRICE',
        'article.newPrice' => 'ARTICLE_NEW_PRICE',
        'ArticleTableMap::COL_ARTICLE_NEW_PRICE' => 'ARTICLE_NEW_PRICE',
        'COL_ARTICLE_NEW_PRICE' => 'ARTICLE_NEW_PRICE',
        'article_new_price' => 'ARTICLE_NEW_PRICE',
        'articles.article_new_price' => 'ARTICLE_NEW_PRICE',
        'Category' => 'ARTICLE_CATEGORY',
        'Article.Category' => 'ARTICLE_CATEGORY',
        'category' => 'ARTICLE_CATEGORY',
        'article.category' => 'ARTICLE_CATEGORY',
        'ArticleTableMap::COL_ARTICLE_CATEGORY' => 'ARTICLE_CATEGORY',
        'COL_ARTICLE_CATEGORY' => 'ARTICLE_CATEGORY',
        'article_category' => 'ARTICLE_CATEGORY',
        'articles.article_category' => 'ARTICLE_CATEGORY',
        'Tva' => 'ARTICLE_TVA',
        'Article.Tva' => 'ARTICLE_TVA',
        'tva' => 'ARTICLE_TVA',
        'article.tva' => 'ARTICLE_TVA',
        'ArticleTableMap::COL_ARTICLE_TVA' => 'ARTICLE_TVA',
        'COL_ARTICLE_TVA' => 'ARTICLE_TVA',
        'article_tva' => 'ARTICLE_TVA',
        'articles.article_tva' => 'ARTICLE_TVA',
        'PdfEan' => 'ARTICLE_PDF_EAN',
        'Article.PdfEan' => 'ARTICLE_PDF_EAN',
        'pdfEan' => 'ARTICLE_PDF_EAN',
        'article.pdfEan' => 'ARTICLE_PDF_EAN',
        'ArticleTableMap::COL_ARTICLE_PDF_EAN' => 'ARTICLE_PDF_EAN',
        'COL_ARTICLE_PDF_EAN' => 'ARTICLE_PDF_EAN',
        'article_pdf_ean' => 'ARTICLE_PDF_EAN',
        'articles.article_pdf_ean' => 'ARTICLE_PDF_EAN',
        'PdfVersion' => 'ARTICLE_PDF_VERSION',
        'Article.PdfVersion' => 'ARTICLE_PDF_VERSION',
        'pdfVersion' => 'ARTICLE_PDF_VERSION',
        'article.pdfVersion' => 'ARTICLE_PDF_VERSION',
        'ArticleTableMap::COL_ARTICLE_PDF_VERSION' => 'ARTICLE_PDF_VERSION',
        'COL_ARTICLE_PDF_VERSION' => 'ARTICLE_PDF_VERSION',
        'article_pdf_version' => 'ARTICLE_PDF_VERSION',
        'articles.article_pdf_version' => 'ARTICLE_PDF_VERSION',
        'EpubEan' => 'ARTICLE_EPUB_EAN',
        'Article.EpubEan' => 'ARTICLE_EPUB_EAN',
        'epubEan' => 'ARTICLE_EPUB_EAN',
        'article.epubEan' => 'ARTICLE_EPUB_EAN',
        'ArticleTableMap::COL_ARTICLE_EPUB_EAN' => 'ARTICLE_EPUB_EAN',
        'COL_ARTICLE_EPUB_EAN' => 'ARTICLE_EPUB_EAN',
        'article_epub_ean' => 'ARTICLE_EPUB_EAN',
        'articles.article_epub_ean' => 'ARTICLE_EPUB_EAN',
        'EpubVersion' => 'ARTICLE_EPUB_VERSION',
        'Article.EpubVersion' => 'ARTICLE_EPUB_VERSION',
        'epubVersion' => 'ARTICLE_EPUB_VERSION',
        'article.epubVersion' => 'ARTICLE_EPUB_VERSION',
        'ArticleTableMap::COL_ARTICLE_EPUB_VERSION' => 'ARTICLE_EPUB_VERSION',
        'COL_ARTICLE_EPUB_VERSION' => 'ARTICLE_EPUB_VERSION',
        'article_epub_version' => 'ARTICLE_EPUB_VERSION',
        'articles.article_epub_version' => 'ARTICLE_EPUB_VERSION',
        'AzwEan' => 'ARTICLE_AZW_EAN',
        'Article.AzwEan' => 'ARTICLE_AZW_EAN',
        'azwEan' => 'ARTICLE_AZW_EAN',
        'article.azwEan' => 'ARTICLE_AZW_EAN',
        'ArticleTableMap::COL_ARTICLE_AZW_EAN' => 'ARTICLE_AZW_EAN',
        'COL_ARTICLE_AZW_EAN' => 'ARTICLE_AZW_EAN',
        'article_azw_ean' => 'ARTICLE_AZW_EAN',
        'articles.article_azw_ean' => 'ARTICLE_AZW_EAN',
        'AzwVersion' => 'ARTICLE_AZW_VERSION',
        'Article.AzwVersion' => 'ARTICLE_AZW_VERSION',
        'azwVersion' => 'ARTICLE_AZW_VERSION',
        'article.azwVersion' => 'ARTICLE_AZW_VERSION',
        'ArticleTableMap::COL_ARTICLE_AZW_VERSION' => 'ARTICLE_AZW_VERSION',
        'COL_ARTICLE_AZW_VERSION' => 'ARTICLE_AZW_VERSION',
        'article_azw_version' => 'ARTICLE_AZW_VERSION',
        'articles.article_azw_version' => 'ARTICLE_AZW_VERSION',
        'Pages' => 'ARTICLE_PAGES',
        'Article.Pages' => 'ARTICLE_PAGES',
        'pages' => 'ARTICLE_PAGES',
        'article.pages' => 'ARTICLE_PAGES',
        'ArticleTableMap::COL_ARTICLE_PAGES' => 'ARTICLE_PAGES',
        'COL_ARTICLE_PAGES' => 'ARTICLE_PAGES',
        'article_pages' => 'ARTICLE_PAGES',
        'articles.article_pages' => 'ARTICLE_PAGES',
        'Weight' => 'ARTICLE_WEIGHT',
        'Article.Weight' => 'ARTICLE_WEIGHT',
        'weight' => 'ARTICLE_WEIGHT',
        'article.weight' => 'ARTICLE_WEIGHT',
        'ArticleTableMap::COL_ARTICLE_WEIGHT' => 'ARTICLE_WEIGHT',
        'COL_ARTICLE_WEIGHT' => 'ARTICLE_WEIGHT',
        'article_weight' => 'ARTICLE_WEIGHT',
        'articles.article_weight' => 'ARTICLE_WEIGHT',
        'Shaping' => 'ARTICLE_SHAPING',
        'Article.Shaping' => 'ARTICLE_SHAPING',
        'shaping' => 'ARTICLE_SHAPING',
        'article.shaping' => 'ARTICLE_SHAPING',
        'ArticleTableMap::COL_ARTICLE_SHAPING' => 'ARTICLE_SHAPING',
        'COL_ARTICLE_SHAPING' => 'ARTICLE_SHAPING',
        'article_shaping' => 'ARTICLE_SHAPING',
        'articles.article_shaping' => 'ARTICLE_SHAPING',
        'Format' => 'ARTICLE_FORMAT',
        'Article.Format' => 'ARTICLE_FORMAT',
        'format' => 'ARTICLE_FORMAT',
        'article.format' => 'ARTICLE_FORMAT',
        'ArticleTableMap::COL_ARTICLE_FORMAT' => 'ARTICLE_FORMAT',
        'COL_ARTICLE_FORMAT' => 'ARTICLE_FORMAT',
        'article_format' => 'ARTICLE_FORMAT',
        'articles.article_format' => 'ARTICLE_FORMAT',
        'PrintingProcess' => 'ARTICLE_PRINTING_PROCESS',
        'Article.PrintingProcess' => 'ARTICLE_PRINTING_PROCESS',
        'printingProcess' => 'ARTICLE_PRINTING_PROCESS',
        'article.printingProcess' => 'ARTICLE_PRINTING_PROCESS',
        'ArticleTableMap::COL_ARTICLE_PRINTING_PROCESS' => 'ARTICLE_PRINTING_PROCESS',
        'COL_ARTICLE_PRINTING_PROCESS' => 'ARTICLE_PRINTING_PROCESS',
        'article_printing_process' => 'ARTICLE_PRINTING_PROCESS',
        'articles.article_printing_process' => 'ARTICLE_PRINTING_PROCESS',
        'AgeMin' => 'ARTICLE_AGE_MIN',
        'Article.AgeMin' => 'ARTICLE_AGE_MIN',
        'ageMin' => 'ARTICLE_AGE_MIN',
        'article.ageMin' => 'ARTICLE_AGE_MIN',
        'ArticleTableMap::COL_ARTICLE_AGE_MIN' => 'ARTICLE_AGE_MIN',
        'COL_ARTICLE_AGE_MIN' => 'ARTICLE_AGE_MIN',
        'article_age_min' => 'ARTICLE_AGE_MIN',
        'articles.article_age_min' => 'ARTICLE_AGE_MIN',
        'AgeMax' => 'ARTICLE_AGE_MAX',
        'Article.AgeMax' => 'ARTICLE_AGE_MAX',
        'ageMax' => 'ARTICLE_AGE_MAX',
        'article.ageMax' => 'ARTICLE_AGE_MAX',
        'ArticleTableMap::COL_ARTICLE_AGE_MAX' => 'ARTICLE_AGE_MAX',
        'COL_ARTICLE_AGE_MAX' => 'ARTICLE_AGE_MAX',
        'article_age_max' => 'ARTICLE_AGE_MAX',
        'articles.article_age_max' => 'ARTICLE_AGE_MAX',
        'Summary' => 'ARTICLE_SUMMARY',
        'Article.Summary' => 'ARTICLE_SUMMARY',
        'summary' => 'ARTICLE_SUMMARY',
        'article.summary' => 'ARTICLE_SUMMARY',
        'ArticleTableMap::COL_ARTICLE_SUMMARY' => 'ARTICLE_SUMMARY',
        'COL_ARTICLE_SUMMARY' => 'ARTICLE_SUMMARY',
        'article_summary' => 'ARTICLE_SUMMARY',
        'articles.article_summary' => 'ARTICLE_SUMMARY',
        'Contents' => 'ARTICLE_CONTENTS',
        'Article.Contents' => 'ARTICLE_CONTENTS',
        'contents' => 'ARTICLE_CONTENTS',
        'article.contents' => 'ARTICLE_CONTENTS',
        'ArticleTableMap::COL_ARTICLE_CONTENTS' => 'ARTICLE_CONTENTS',
        'COL_ARTICLE_CONTENTS' => 'ARTICLE_CONTENTS',
        'article_contents' => 'ARTICLE_CONTENTS',
        'articles.article_contents' => 'ARTICLE_CONTENTS',
        'Bonus' => 'ARTICLE_BONUS',
        'Article.Bonus' => 'ARTICLE_BONUS',
        'bonus' => 'ARTICLE_BONUS',
        'article.bonus' => 'ARTICLE_BONUS',
        'ArticleTableMap::COL_ARTICLE_BONUS' => 'ARTICLE_BONUS',
        'COL_ARTICLE_BONUS' => 'ARTICLE_BONUS',
        'article_bonus' => 'ARTICLE_BONUS',
        'articles.article_bonus' => 'ARTICLE_BONUS',
        'Catchline' => 'ARTICLE_CATCHLINE',
        'Article.Catchline' => 'ARTICLE_CATCHLINE',
        'catchline' => 'ARTICLE_CATCHLINE',
        'article.catchline' => 'ARTICLE_CATCHLINE',
        'ArticleTableMap::COL_ARTICLE_CATCHLINE' => 'ARTICLE_CATCHLINE',
        'COL_ARTICLE_CATCHLINE' => 'ARTICLE_CATCHLINE',
        'article_catchline' => 'ARTICLE_CATCHLINE',
        'articles.article_catchline' => 'ARTICLE_CATCHLINE',
        'Biography' => 'ARTICLE_BIOGRAPHY',
        'Article.Biography' => 'ARTICLE_BIOGRAPHY',
        'biography' => 'ARTICLE_BIOGRAPHY',
        'article.biography' => 'ARTICLE_BIOGRAPHY',
        'ArticleTableMap::COL_ARTICLE_BIOGRAPHY' => 'ARTICLE_BIOGRAPHY',
        'COL_ARTICLE_BIOGRAPHY' => 'ARTICLE_BIOGRAPHY',
        'article_biography' => 'ARTICLE_BIOGRAPHY',
        'articles.article_biography' => 'ARTICLE_BIOGRAPHY',
        'Motsv' => 'ARTICLE_MOTSV',
        'Article.Motsv' => 'ARTICLE_MOTSV',
        'motsv' => 'ARTICLE_MOTSV',
        'article.motsv' => 'ARTICLE_MOTSV',
        'ArticleTableMap::COL_ARTICLE_MOTSV' => 'ARTICLE_MOTSV',
        'COL_ARTICLE_MOTSV' => 'ARTICLE_MOTSV',
        'article_motsv' => 'ARTICLE_MOTSV',
        'articles.article_motsv' => 'ARTICLE_MOTSV',
        'Copyright' => 'ARTICLE_COPYRIGHT',
        'Article.Copyright' => 'ARTICLE_COPYRIGHT',
        'copyright' => 'ARTICLE_COPYRIGHT',
        'article.copyright' => 'ARTICLE_COPYRIGHT',
        'ArticleTableMap::COL_ARTICLE_COPYRIGHT' => 'ARTICLE_COPYRIGHT',
        'COL_ARTICLE_COPYRIGHT' => 'ARTICLE_COPYRIGHT',
        'article_copyright' => 'ARTICLE_COPYRIGHT',
        'articles.article_copyright' => 'ARTICLE_COPYRIGHT',
        'Pubdate' => 'ARTICLE_PUBDATE',
        'Article.Pubdate' => 'ARTICLE_PUBDATE',
        'pubdate' => 'ARTICLE_PUBDATE',
        'article.pubdate' => 'ARTICLE_PUBDATE',
        'ArticleTableMap::COL_ARTICLE_PUBDATE' => 'ARTICLE_PUBDATE',
        'COL_ARTICLE_PUBDATE' => 'ARTICLE_PUBDATE',
        'article_pubdate' => 'ARTICLE_PUBDATE',
        'articles.article_pubdate' => 'ARTICLE_PUBDATE',
        'Keywords' => 'ARTICLE_KEYWORDS',
        'Article.Keywords' => 'ARTICLE_KEYWORDS',
        'keywords' => 'ARTICLE_KEYWORDS',
        'article.keywords' => 'ARTICLE_KEYWORDS',
        'ArticleTableMap::COL_ARTICLE_KEYWORDS' => 'ARTICLE_KEYWORDS',
        'COL_ARTICLE_KEYWORDS' => 'ARTICLE_KEYWORDS',
        'article_keywords' => 'ARTICLE_KEYWORDS',
        'articles.article_keywords' => 'ARTICLE_KEYWORDS',
        'ComputedLinks' => 'ARTICLE_LINKS',
        'Article.ComputedLinks' => 'ARTICLE_LINKS',
        'computedLinks' => 'ARTICLE_LINKS',
        'article.computedLinks' => 'ARTICLE_LINKS',
        'ArticleTableMap::COL_ARTICLE_LINKS' => 'ARTICLE_LINKS',
        'COL_ARTICLE_LINKS' => 'ARTICLE_LINKS',
        'article_links' => 'ARTICLE_LINKS',
        'articles.article_links' => 'ARTICLE_LINKS',
        'KeywordsGenerated' => 'ARTICLE_KEYWORDS_GENERATED',
        'Article.KeywordsGenerated' => 'ARTICLE_KEYWORDS_GENERATED',
        'keywordsGenerated' => 'ARTICLE_KEYWORDS_GENERATED',
        'article.keywordsGenerated' => 'ARTICLE_KEYWORDS_GENERATED',
        'ArticleTableMap::COL_ARTICLE_KEYWORDS_GENERATED' => 'ARTICLE_KEYWORDS_GENERATED',
        'COL_ARTICLE_KEYWORDS_GENERATED' => 'ARTICLE_KEYWORDS_GENERATED',
        'article_keywords_generated' => 'ARTICLE_KEYWORDS_GENERATED',
        'articles.article_keywords_generated' => 'ARTICLE_KEYWORDS_GENERATED',
        'PublisherStock' => 'ARTICLE_PUBLISHER_STOCK',
        'Article.PublisherStock' => 'ARTICLE_PUBLISHER_STOCK',
        'publisherStock' => 'ARTICLE_PUBLISHER_STOCK',
        'article.publisherStock' => 'ARTICLE_PUBLISHER_STOCK',
        'ArticleTableMap::COL_ARTICLE_PUBLISHER_STOCK' => 'ARTICLE_PUBLISHER_STOCK',
        'COL_ARTICLE_PUBLISHER_STOCK' => 'ARTICLE_PUBLISHER_STOCK',
        'article_publisher_stock' => 'ARTICLE_PUBLISHER_STOCK',
        'articles.article_publisher_stock' => 'ARTICLE_PUBLISHER_STOCK',
        'Hits' => 'ARTICLE_HITS',
        'Article.Hits' => 'ARTICLE_HITS',
        'hits' => 'ARTICLE_HITS',
        'article.hits' => 'ARTICLE_HITS',
        'ArticleTableMap::COL_ARTICLE_HITS' => 'ARTICLE_HITS',
        'COL_ARTICLE_HITS' => 'ARTICLE_HITS',
        'article_hits' => 'ARTICLE_HITS',
        'articles.article_hits' => 'ARTICLE_HITS',
        'EditingUser' => 'ARTICLE_EDITING_USER',
        'Article.EditingUser' => 'ARTICLE_EDITING_USER',
        'editingUser' => 'ARTICLE_EDITING_USER',
        'article.editingUser' => 'ARTICLE_EDITING_USER',
        'ArticleTableMap::COL_ARTICLE_EDITING_USER' => 'ARTICLE_EDITING_USER',
        'COL_ARTICLE_EDITING_USER' => 'ARTICLE_EDITING_USER',
        'article_editing_user' => 'ARTICLE_EDITING_USER',
        'articles.article_editing_user' => 'ARTICLE_EDITING_USER',
        'Insert' => 'ARTICLE_INSERT',
        'Article.Insert' => 'ARTICLE_INSERT',
        'insert' => 'ARTICLE_INSERT',
        'article.insert' => 'ARTICLE_INSERT',
        'ArticleTableMap::COL_ARTICLE_INSERT' => 'ARTICLE_INSERT',
        'COL_ARTICLE_INSERT' => 'ARTICLE_INSERT',
        'article_insert' => 'ARTICLE_INSERT',
        'articles.article_insert' => 'ARTICLE_INSERT',
        'Update' => 'ARTICLE_UPDATE',
        'Article.Update' => 'ARTICLE_UPDATE',
        'update' => 'ARTICLE_UPDATE',
        'article.update' => 'ARTICLE_UPDATE',
        'ArticleTableMap::COL_ARTICLE_UPDATE' => 'ARTICLE_UPDATE',
        'COL_ARTICLE_UPDATE' => 'ARTICLE_UPDATE',
        'article_update' => 'ARTICLE_UPDATE',
        'articles.article_update' => 'ARTICLE_UPDATE',
        'CreatedAt' => 'ARTICLE_CREATED',
        'Article.CreatedAt' => 'ARTICLE_CREATED',
        'createdAt' => 'ARTICLE_CREATED',
        'article.createdAt' => 'ARTICLE_CREATED',
        'ArticleTableMap::COL_ARTICLE_CREATED' => 'ARTICLE_CREATED',
        'COL_ARTICLE_CREATED' => 'ARTICLE_CREATED',
        'article_created' => 'ARTICLE_CREATED',
        'articles.article_created' => 'ARTICLE_CREATED',
        'UpdatedAt' => 'ARTICLE_UPDATED',
        'Article.UpdatedAt' => 'ARTICLE_UPDATED',
        'updatedAt' => 'ARTICLE_UPDATED',
        'article.updatedAt' => 'ARTICLE_UPDATED',
        'ArticleTableMap::COL_ARTICLE_UPDATED' => 'ARTICLE_UPDATED',
        'COL_ARTICLE_UPDATED' => 'ARTICLE_UPDATED',
        'article_updated' => 'ARTICLE_UPDATED',
        'articles.article_updated' => 'ARTICLE_UPDATED',
        'Done' => 'ARTICLE_DONE',
        'Article.Done' => 'ARTICLE_DONE',
        'done' => 'ARTICLE_DONE',
        'article.done' => 'ARTICLE_DONE',
        'ArticleTableMap::COL_ARTICLE_DONE' => 'ARTICLE_DONE',
        'COL_ARTICLE_DONE' => 'ARTICLE_DONE',
        'article_done' => 'ARTICLE_DONE',
        'articles.article_done' => 'ARTICLE_DONE',
        'ToCheck' => 'ARTICLE_TO_CHECK',
        'Article.ToCheck' => 'ARTICLE_TO_CHECK',
        'toCheck' => 'ARTICLE_TO_CHECK',
        'article.toCheck' => 'ARTICLE_TO_CHECK',
        'ArticleTableMap::COL_ARTICLE_TO_CHECK' => 'ARTICLE_TO_CHECK',
        'COL_ARTICLE_TO_CHECK' => 'ARTICLE_TO_CHECK',
        'article_to_check' => 'ARTICLE_TO_CHECK',
        'articles.article_to_check' => 'ARTICLE_TO_CHECK',
        'DeletionBy' => 'ARTICLE_DELETION_BY',
        'Article.DeletionBy' => 'ARTICLE_DELETION_BY',
        'deletionBy' => 'ARTICLE_DELETION_BY',
        'article.deletionBy' => 'ARTICLE_DELETION_BY',
        'ArticleTableMap::COL_ARTICLE_DELETION_BY' => 'ARTICLE_DELETION_BY',
        'COL_ARTICLE_DELETION_BY' => 'ARTICLE_DELETION_BY',
        'article_deletion_by' => 'ARTICLE_DELETION_BY',
        'articles.article_deletion_by' => 'ARTICLE_DELETION_BY',
        'DeletionDate' => 'ARTICLE_DELETION_DATE',
        'Article.DeletionDate' => 'ARTICLE_DELETION_DATE',
        'deletionDate' => 'ARTICLE_DELETION_DATE',
        'article.deletionDate' => 'ARTICLE_DELETION_DATE',
        'ArticleTableMap::COL_ARTICLE_DELETION_DATE' => 'ARTICLE_DELETION_DATE',
        'COL_ARTICLE_DELETION_DATE' => 'ARTICLE_DELETION_DATE',
        'article_deletion_date' => 'ARTICLE_DELETION_DATE',
        'articles.article_deletion_date' => 'ARTICLE_DELETION_DATE',
        'DeletionReason' => 'ARTICLE_DELETION_REASON',
        'Article.DeletionReason' => 'ARTICLE_DELETION_REASON',
        'deletionReason' => 'ARTICLE_DELETION_REASON',
        'article.deletionReason' => 'ARTICLE_DELETION_REASON',
        'ArticleTableMap::COL_ARTICLE_DELETION_REASON' => 'ARTICLE_DELETION_REASON',
        'COL_ARTICLE_DELETION_REASON' => 'ARTICLE_DELETION_REASON',
        'article_deletion_reason' => 'ARTICLE_DELETION_REASON',
        'articles.article_deletion_reason' => 'ARTICLE_DELETION_REASON',
        'LemoninkMasterId' => 'LEMONINK_MASTER_ID',
        'Article.LemoninkMasterId' => 'LEMONINK_MASTER_ID',
        'lemoninkMasterId' => 'LEMONINK_MASTER_ID',
        'article.lemoninkMasterId' => 'LEMONINK_MASTER_ID',
        'ArticleTableMap::COL_LEMONINK_MASTER_ID' => 'LEMONINK_MASTER_ID',
        'COL_LEMONINK_MASTER_ID' => 'LEMONINK_MASTER_ID',
        'lemonink_master_id' => 'LEMONINK_MASTER_ID',
        'articles.lemonink_master_id' => 'LEMONINK_MASTER_ID',
    ];

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function initialize(): void
    {
        // attributes
        $this->setName('articles');
        $this->setPhpName('Article');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Article');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('article_id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('article_item', 'Item', 'INTEGER', false, null, null);
        $this->addColumn('article_textid', 'Textid', 'VARCHAR', false, 32, null);
        $this->addColumn('article_ean', 'Ean', 'BIGINT', false, null, null);
        $this->addColumn('article_ean_others', 'EanOthers', 'VARCHAR', false, 255, null);
        $this->addColumn('article_asin', 'Asin', 'VARCHAR', false, 32, null);
        $this->addColumn('article_noosfere_id', 'NoosfereId', 'INTEGER', false, null, null);
        $this->addColumn('article_url', 'Url', 'VARCHAR', false, 256, null);
        $this->addColumn('type_id', 'TypeId', 'TINYINT', false, null, null);
        $this->addColumn('article_title', 'Title', 'VARCHAR', false, 256, null);
        $this->addColumn('article_title_alphabetic', 'TitleAlphabetic', 'VARCHAR', false, 256, null);
        $this->addColumn('article_title_original', 'TitleOriginal', 'VARCHAR', false, 256, null);
        $this->addColumn('article_title_others', 'TitleOthers', 'VARCHAR', false, 256, null);
        $this->addColumn('article_subtitle', 'Subtitle', 'VARCHAR', false, 256, null);
        $this->addColumn('article_lang_current', 'LangCurrent', 'TINYINT', false, null, null);
        $this->addColumn('article_lang_original', 'LangOriginal', 'TINYINT', false, null, null);
        $this->addColumn('article_origin_country', 'OriginCountry', 'INTEGER', false, null, null);
        $this->addColumn('article_theme_bisac', 'ThemeBisac', 'VARCHAR', false, 16, null);
        $this->addColumn('article_theme_clil', 'ThemeClil', 'VARCHAR', false, 16, null);
        $this->addColumn('article_theme_dewey', 'ThemeDewey', 'VARCHAR', false, 16, null);
        $this->addColumn('article_theme_electre', 'ThemeElectre', 'VARCHAR', false, 16, null);
        $this->addColumn('article_source_id', 'SourceId', 'INTEGER', false, null, null);
        $this->addColumn('article_authors', 'Authors', 'VARCHAR', false, 256, null);
        $this->addColumn('article_authors_alphabetic', 'AuthorsAlphabetic', 'VARCHAR', false, 256, null);
        $this->addForeignKey('collection_id', 'CollectionId', 'INTEGER', 'collections', 'collection_id', false, null, null);
        $this->addColumn('article_collection', 'CollectionName', 'VARCHAR', false, 256, null);
        $this->addColumn('article_number', 'Number', 'VARCHAR', false, 8, null);
        $this->addForeignKey('publisher_id', 'PublisherId', 'INTEGER', 'publishers', 'publisher_id', false, null, null);
        $this->addColumn('article_publisher', 'PublisherName', 'VARCHAR', false, 256, null);
        $this->addForeignKey('cycle_id', 'CycleId', 'INTEGER', 'cycles', 'cycle_id', false, null, null);
        $this->addColumn('article_cycle', 'CycleName', 'VARCHAR', false, 256, null);
        $this->addColumn('article_tome', 'Tome', 'VARCHAR', false, 12, null);
        $this->addColumn('article_cover_version', 'CoverVersion', 'INTEGER', false, null, 0);
        $this->addColumn('article_availability', 'Availability', 'TINYINT', false, null, null);
        $this->addColumn('article_availability_dilicom', 'AvailabilityDilicom', 'TINYINT', false, null, 1);
        $this->addColumn('article_preorder', 'Preorder', 'BOOLEAN', false, 1, false);
        $this->addColumn('article_price', 'Price', 'INTEGER', false, null, null);
        $this->addColumn('article_price_editable', 'PriceEditable', 'BOOLEAN', false, 1, false);
        $this->addColumn('article_new_price', 'NewPrice', 'INTEGER', false, null, null);
        $this->addColumn('article_category', 'Category', 'VARCHAR', false, 8, null);
        $this->addColumn('article_tva', 'Tva', 'TINYINT', false, null, 1);
        $this->addColumn('article_pdf_ean', 'PdfEan', 'BIGINT', false, null, null);
        $this->addColumn('article_pdf_version', 'PdfVersion', 'VARCHAR', false, 8, '0');
        $this->addColumn('article_epub_ean', 'EpubEan', 'BIGINT', false, null, null);
        $this->addColumn('article_epub_version', 'EpubVersion', 'VARCHAR', false, 8, '0');
        $this->addColumn('article_azw_ean', 'AzwEan', 'BIGINT', false, null, null);
        $this->addColumn('article_azw_version', 'AzwVersion', 'VARCHAR', false, 8, '0');
        $this->addColumn('article_pages', 'Pages', 'INTEGER', false, null, null);
        $this->addColumn('article_weight', 'Weight', 'INTEGER', false, null, null);
        $this->addColumn('article_shaping', 'Shaping', 'VARCHAR', false, 128, null);
        $this->addColumn('article_format', 'Format', 'VARCHAR', false, 128, null);
        $this->addColumn('article_printing_process', 'PrintingProcess', 'VARCHAR', false, 256, null);
        $this->addColumn('article_age_min', 'AgeMin', 'INTEGER', false, null, null);
        $this->addColumn('article_age_max', 'AgeMax', 'INTEGER', false, null, null);
        $this->addColumn('article_summary', 'Summary', 'LONGVARCHAR', false, null, null);
        $this->addColumn('article_contents', 'Contents', 'LONGVARCHAR', false, null, null);
        $this->addColumn('article_bonus', 'Bonus', 'LONGVARCHAR', false, null, null);
        $this->addColumn('article_catchline', 'Catchline', 'LONGVARCHAR', false, null, null);
        $this->addColumn('article_biography', 'Biography', 'LONGVARCHAR', false, null, null);
        $this->addColumn('article_motsv', 'Motsv', 'LONGVARCHAR', false, null, null);
        $this->addColumn('article_copyright', 'Copyright', 'SMALLINT', false, null, null);
        $this->addColumn('article_pubdate', 'Pubdate', 'DATE', false, null, null);
        $this->addColumn('article_keywords', 'Keywords', 'VARCHAR', false, 1024, null);
        $this->addColumn('article_links', 'ComputedLinks', 'VARCHAR', false, 1024, null);
        $this->addColumn('article_keywords_generated', 'KeywordsGenerated', 'TIMESTAMP', false, null, null);
        $this->addColumn('article_publisher_stock', 'PublisherStock', 'INTEGER', false, null, 0);
        $this->addColumn('article_hits', 'Hits', 'INTEGER', false, null, 0);
        $this->addColumn('article_editing_user', 'EditingUser', 'INTEGER', false, null, null);
        $this->addColumn('article_insert', 'Insert', 'TIMESTAMP', false, null, null);
        $this->addColumn('article_update', 'Update', 'TIMESTAMP', false, null, null);
        $this->addColumn('article_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('article_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('article_done', 'Done', 'BOOLEAN', false, 1, false);
        $this->addColumn('article_to_check', 'ToCheck', 'BOOLEAN', false, 1, false);
        $this->addColumn('article_deletion_by', 'DeletionBy', 'INTEGER', false, null, null);
        $this->addColumn('article_deletion_date', 'DeletionDate', 'TIMESTAMP', false, null, null);
        $this->addColumn('article_deletion_reason', 'DeletionReason', 'VARCHAR', false, 512, null);
        $this->addColumn('lemonink_master_id', 'LemoninkMasterId', 'VARCHAR', false, 64, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
        $this->addRelation('Publisher', '\\Model\\Publisher', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':publisher_id',
    1 => ':publisher_id',
  ),
), null, null, null, false);
        $this->addRelation('BookCollection', '\\Model\\BookCollection', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':collection_id',
    1 => ':collection_id',
  ),
), null, null, null, false);
        $this->addRelation('Cycle', '\\Model\\Cycle', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':cycle_id',
    1 => ':cycle_id',
  ),
), null, null, null, false);
        $this->addRelation('File', '\\Model\\File', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':article_id',
    1 => ':article_id',
  ),
), null, null, 'Files', false);
        $this->addRelation('Image', '\\Model\\Image', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':article_id',
    1 => ':article_id',
  ),
), null, null, 'Images', false);
        $this->addRelation('InvitationsArticles', '\\Model\\InvitationsArticles', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':article_id',
    1 => ':article_id',
  ),
), null, null, 'InvitationsArticless', false);
        $this->addRelation('Link', '\\Model\\Link', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':article_id',
    1 => ':article_id',
  ),
), null, null, 'Links', false);
        $this->addRelation('Role', '\\Model\\Role', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':article_id',
    1 => ':article_id',
  ),
), null, null, 'Roles', false);
        $this->addRelation('SpecialOffer', '\\Model\\SpecialOffer', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':free_article_id',
    1 => ':article_id',
  ),
), null, null, 'SpecialOffers', false);
        $this->addRelation('Stock', '\\Model\\Stock', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':article_id',
    1 => ':article_id',
  ),
), null, null, 'Stocks', false);
        $this->addRelation('ArticleTag', '\\Model\\ArticleTag', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':article_id',
    1 => ':article_id',
  ),
), null, null, 'ArticleTags', false);
        $this->addRelation('Invitation', '\\Model\\Invitation', RelationMap::MANY_TO_MANY, array(), null, null, 'Invitations');
        $this->addRelation('Tag', '\\Model\\Tag', RelationMap::MANY_TO_MANY, array(), null, null, 'Tags');
    }

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array<string, array> Associative array (name => parameters) of behaviors
     */
    public function getBehaviors(): array
    {
        return [
            'timestampable' => ['create_column' => 'article_created', 'update_column' => 'article_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false'],
            'sluggable' => ['slug_column' => 'article_url', 'slug_pattern' => '{Authors}/{Title}', 'replace_pattern' => '/\\W+/', 'replacement' => '-', 'separator' => '-', 'permanent' => 'false', 'scope_column' => '', 'unique_constraint' => 'true'],
        ];
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array $row Resultset row.
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string|null The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): ?string
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array $row Resultset row.
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param bool $withPrefix Whether to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass(bool $withPrefix = true): string
    {
        return $withPrefix ? ArticleTableMap::CLASS_DEFAULT : ArticleTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array $row Row returned by DataFetcher->fetch().
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array (Article object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = ArticleTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = ArticleTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + ArticleTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ArticleTableMap::OM_CLASS;
            /** @var Article $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            ArticleTableMap::addInstanceToPool($obj, $key);
        }

        return [$obj, $col];
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array<object>
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher): array
    {
        $results = [];

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = ArticleTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = ArticleTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Article $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ArticleTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria Object containing the columns to add.
     * @param string|null $alias Optional table alias
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return void
     */
    public static function addSelectColumns(Criteria $criteria, ?string $alias = null): void
    {
        if (null === $alias) {
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_ID);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_ITEM);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_TEXTID);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_EAN);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_EAN_OTHERS);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_ASIN);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_NOOSFERE_ID);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_URL);
            $criteria->addSelectColumn(ArticleTableMap::COL_TYPE_ID);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_TITLE);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_TITLE_ALPHABETIC);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_TITLE_ORIGINAL);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_TITLE_OTHERS);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_SUBTITLE);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_LANG_CURRENT);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_LANG_ORIGINAL);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_ORIGIN_COUNTRY);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_THEME_BISAC);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_THEME_CLIL);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_THEME_DEWEY);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_THEME_ELECTRE);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_SOURCE_ID);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_AUTHORS);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_AUTHORS_ALPHABETIC);
            $criteria->addSelectColumn(ArticleTableMap::COL_COLLECTION_ID);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_COLLECTION);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_NUMBER);
            $criteria->addSelectColumn(ArticleTableMap::COL_PUBLISHER_ID);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_PUBLISHER);
            $criteria->addSelectColumn(ArticleTableMap::COL_CYCLE_ID);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_CYCLE);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_TOME);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_COVER_VERSION);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_AVAILABILITY);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_AVAILABILITY_DILICOM);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_PREORDER);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_PRICE);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_PRICE_EDITABLE);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_NEW_PRICE);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_CATEGORY);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_TVA);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_PDF_EAN);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_PDF_VERSION);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_EPUB_EAN);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_EPUB_VERSION);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_AZW_EAN);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_AZW_VERSION);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_PAGES);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_WEIGHT);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_SHAPING);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_FORMAT);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_PRINTING_PROCESS);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_AGE_MIN);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_AGE_MAX);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_SUMMARY);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_CONTENTS);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_BONUS);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_CATCHLINE);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_BIOGRAPHY);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_MOTSV);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_COPYRIGHT);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_PUBDATE);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_KEYWORDS);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_LINKS);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_KEYWORDS_GENERATED);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_PUBLISHER_STOCK);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_HITS);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_EDITING_USER);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_INSERT);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_UPDATE);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_CREATED);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_UPDATED);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_DONE);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_TO_CHECK);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_DELETION_BY);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_DELETION_DATE);
            $criteria->addSelectColumn(ArticleTableMap::COL_ARTICLE_DELETION_REASON);
            $criteria->addSelectColumn(ArticleTableMap::COL_LEMONINK_MASTER_ID);
        } else {
            $criteria->addSelectColumn($alias . '.article_id');
            $criteria->addSelectColumn($alias . '.article_item');
            $criteria->addSelectColumn($alias . '.article_textid');
            $criteria->addSelectColumn($alias . '.article_ean');
            $criteria->addSelectColumn($alias . '.article_ean_others');
            $criteria->addSelectColumn($alias . '.article_asin');
            $criteria->addSelectColumn($alias . '.article_noosfere_id');
            $criteria->addSelectColumn($alias . '.article_url');
            $criteria->addSelectColumn($alias . '.type_id');
            $criteria->addSelectColumn($alias . '.article_title');
            $criteria->addSelectColumn($alias . '.article_title_alphabetic');
            $criteria->addSelectColumn($alias . '.article_title_original');
            $criteria->addSelectColumn($alias . '.article_title_others');
            $criteria->addSelectColumn($alias . '.article_subtitle');
            $criteria->addSelectColumn($alias . '.article_lang_current');
            $criteria->addSelectColumn($alias . '.article_lang_original');
            $criteria->addSelectColumn($alias . '.article_origin_country');
            $criteria->addSelectColumn($alias . '.article_theme_bisac');
            $criteria->addSelectColumn($alias . '.article_theme_clil');
            $criteria->addSelectColumn($alias . '.article_theme_dewey');
            $criteria->addSelectColumn($alias . '.article_theme_electre');
            $criteria->addSelectColumn($alias . '.article_source_id');
            $criteria->addSelectColumn($alias . '.article_authors');
            $criteria->addSelectColumn($alias . '.article_authors_alphabetic');
            $criteria->addSelectColumn($alias . '.collection_id');
            $criteria->addSelectColumn($alias . '.article_collection');
            $criteria->addSelectColumn($alias . '.article_number');
            $criteria->addSelectColumn($alias . '.publisher_id');
            $criteria->addSelectColumn($alias . '.article_publisher');
            $criteria->addSelectColumn($alias . '.cycle_id');
            $criteria->addSelectColumn($alias . '.article_cycle');
            $criteria->addSelectColumn($alias . '.article_tome');
            $criteria->addSelectColumn($alias . '.article_cover_version');
            $criteria->addSelectColumn($alias . '.article_availability');
            $criteria->addSelectColumn($alias . '.article_availability_dilicom');
            $criteria->addSelectColumn($alias . '.article_preorder');
            $criteria->addSelectColumn($alias . '.article_price');
            $criteria->addSelectColumn($alias . '.article_price_editable');
            $criteria->addSelectColumn($alias . '.article_new_price');
            $criteria->addSelectColumn($alias . '.article_category');
            $criteria->addSelectColumn($alias . '.article_tva');
            $criteria->addSelectColumn($alias . '.article_pdf_ean');
            $criteria->addSelectColumn($alias . '.article_pdf_version');
            $criteria->addSelectColumn($alias . '.article_epub_ean');
            $criteria->addSelectColumn($alias . '.article_epub_version');
            $criteria->addSelectColumn($alias . '.article_azw_ean');
            $criteria->addSelectColumn($alias . '.article_azw_version');
            $criteria->addSelectColumn($alias . '.article_pages');
            $criteria->addSelectColumn($alias . '.article_weight');
            $criteria->addSelectColumn($alias . '.article_shaping');
            $criteria->addSelectColumn($alias . '.article_format');
            $criteria->addSelectColumn($alias . '.article_printing_process');
            $criteria->addSelectColumn($alias . '.article_age_min');
            $criteria->addSelectColumn($alias . '.article_age_max');
            $criteria->addSelectColumn($alias . '.article_summary');
            $criteria->addSelectColumn($alias . '.article_contents');
            $criteria->addSelectColumn($alias . '.article_bonus');
            $criteria->addSelectColumn($alias . '.article_catchline');
            $criteria->addSelectColumn($alias . '.article_biography');
            $criteria->addSelectColumn($alias . '.article_motsv');
            $criteria->addSelectColumn($alias . '.article_copyright');
            $criteria->addSelectColumn($alias . '.article_pubdate');
            $criteria->addSelectColumn($alias . '.article_keywords');
            $criteria->addSelectColumn($alias . '.article_links');
            $criteria->addSelectColumn($alias . '.article_keywords_generated');
            $criteria->addSelectColumn($alias . '.article_publisher_stock');
            $criteria->addSelectColumn($alias . '.article_hits');
            $criteria->addSelectColumn($alias . '.article_editing_user');
            $criteria->addSelectColumn($alias . '.article_insert');
            $criteria->addSelectColumn($alias . '.article_update');
            $criteria->addSelectColumn($alias . '.article_created');
            $criteria->addSelectColumn($alias . '.article_updated');
            $criteria->addSelectColumn($alias . '.article_done');
            $criteria->addSelectColumn($alias . '.article_to_check');
            $criteria->addSelectColumn($alias . '.article_deletion_by');
            $criteria->addSelectColumn($alias . '.article_deletion_date');
            $criteria->addSelectColumn($alias . '.article_deletion_reason');
            $criteria->addSelectColumn($alias . '.lemonink_master_id');
        }
    }

    /**
     * Remove all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be removed as they are only loaded on demand.
     *
     * @param Criteria $criteria Object containing the columns to remove.
     * @param string|null $alias Optional table alias
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return void
     */
    public static function removeSelectColumns(Criteria $criteria, ?string $alias = null): void
    {
        if (null === $alias) {
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_ID);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_ITEM);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_TEXTID);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_EAN);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_EAN_OTHERS);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_ASIN);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_NOOSFERE_ID);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_URL);
            $criteria->removeSelectColumn(ArticleTableMap::COL_TYPE_ID);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_TITLE);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_TITLE_ALPHABETIC);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_TITLE_ORIGINAL);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_TITLE_OTHERS);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_SUBTITLE);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_LANG_CURRENT);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_LANG_ORIGINAL);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_ORIGIN_COUNTRY);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_THEME_BISAC);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_THEME_CLIL);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_THEME_DEWEY);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_THEME_ELECTRE);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_SOURCE_ID);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_AUTHORS);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_AUTHORS_ALPHABETIC);
            $criteria->removeSelectColumn(ArticleTableMap::COL_COLLECTION_ID);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_COLLECTION);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_NUMBER);
            $criteria->removeSelectColumn(ArticleTableMap::COL_PUBLISHER_ID);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_PUBLISHER);
            $criteria->removeSelectColumn(ArticleTableMap::COL_CYCLE_ID);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_CYCLE);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_TOME);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_COVER_VERSION);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_AVAILABILITY);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_AVAILABILITY_DILICOM);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_PREORDER);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_PRICE);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_PRICE_EDITABLE);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_NEW_PRICE);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_CATEGORY);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_TVA);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_PDF_EAN);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_PDF_VERSION);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_EPUB_EAN);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_EPUB_VERSION);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_AZW_EAN);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_AZW_VERSION);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_PAGES);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_WEIGHT);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_SHAPING);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_FORMAT);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_PRINTING_PROCESS);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_AGE_MIN);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_AGE_MAX);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_SUMMARY);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_CONTENTS);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_BONUS);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_CATCHLINE);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_BIOGRAPHY);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_MOTSV);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_COPYRIGHT);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_PUBDATE);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_KEYWORDS);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_LINKS);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_KEYWORDS_GENERATED);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_PUBLISHER_STOCK);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_HITS);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_EDITING_USER);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_INSERT);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_UPDATE);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_CREATED);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_UPDATED);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_DONE);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_TO_CHECK);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_DELETION_BY);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_DELETION_DATE);
            $criteria->removeSelectColumn(ArticleTableMap::COL_ARTICLE_DELETION_REASON);
            $criteria->removeSelectColumn(ArticleTableMap::COL_LEMONINK_MASTER_ID);
        } else {
            $criteria->removeSelectColumn($alias . '.article_id');
            $criteria->removeSelectColumn($alias . '.article_item');
            $criteria->removeSelectColumn($alias . '.article_textid');
            $criteria->removeSelectColumn($alias . '.article_ean');
            $criteria->removeSelectColumn($alias . '.article_ean_others');
            $criteria->removeSelectColumn($alias . '.article_asin');
            $criteria->removeSelectColumn($alias . '.article_noosfere_id');
            $criteria->removeSelectColumn($alias . '.article_url');
            $criteria->removeSelectColumn($alias . '.type_id');
            $criteria->removeSelectColumn($alias . '.article_title');
            $criteria->removeSelectColumn($alias . '.article_title_alphabetic');
            $criteria->removeSelectColumn($alias . '.article_title_original');
            $criteria->removeSelectColumn($alias . '.article_title_others');
            $criteria->removeSelectColumn($alias . '.article_subtitle');
            $criteria->removeSelectColumn($alias . '.article_lang_current');
            $criteria->removeSelectColumn($alias . '.article_lang_original');
            $criteria->removeSelectColumn($alias . '.article_origin_country');
            $criteria->removeSelectColumn($alias . '.article_theme_bisac');
            $criteria->removeSelectColumn($alias . '.article_theme_clil');
            $criteria->removeSelectColumn($alias . '.article_theme_dewey');
            $criteria->removeSelectColumn($alias . '.article_theme_electre');
            $criteria->removeSelectColumn($alias . '.article_source_id');
            $criteria->removeSelectColumn($alias . '.article_authors');
            $criteria->removeSelectColumn($alias . '.article_authors_alphabetic');
            $criteria->removeSelectColumn($alias . '.collection_id');
            $criteria->removeSelectColumn($alias . '.article_collection');
            $criteria->removeSelectColumn($alias . '.article_number');
            $criteria->removeSelectColumn($alias . '.publisher_id');
            $criteria->removeSelectColumn($alias . '.article_publisher');
            $criteria->removeSelectColumn($alias . '.cycle_id');
            $criteria->removeSelectColumn($alias . '.article_cycle');
            $criteria->removeSelectColumn($alias . '.article_tome');
            $criteria->removeSelectColumn($alias . '.article_cover_version');
            $criteria->removeSelectColumn($alias . '.article_availability');
            $criteria->removeSelectColumn($alias . '.article_availability_dilicom');
            $criteria->removeSelectColumn($alias . '.article_preorder');
            $criteria->removeSelectColumn($alias . '.article_price');
            $criteria->removeSelectColumn($alias . '.article_price_editable');
            $criteria->removeSelectColumn($alias . '.article_new_price');
            $criteria->removeSelectColumn($alias . '.article_category');
            $criteria->removeSelectColumn($alias . '.article_tva');
            $criteria->removeSelectColumn($alias . '.article_pdf_ean');
            $criteria->removeSelectColumn($alias . '.article_pdf_version');
            $criteria->removeSelectColumn($alias . '.article_epub_ean');
            $criteria->removeSelectColumn($alias . '.article_epub_version');
            $criteria->removeSelectColumn($alias . '.article_azw_ean');
            $criteria->removeSelectColumn($alias . '.article_azw_version');
            $criteria->removeSelectColumn($alias . '.article_pages');
            $criteria->removeSelectColumn($alias . '.article_weight');
            $criteria->removeSelectColumn($alias . '.article_shaping');
            $criteria->removeSelectColumn($alias . '.article_format');
            $criteria->removeSelectColumn($alias . '.article_printing_process');
            $criteria->removeSelectColumn($alias . '.article_age_min');
            $criteria->removeSelectColumn($alias . '.article_age_max');
            $criteria->removeSelectColumn($alias . '.article_summary');
            $criteria->removeSelectColumn($alias . '.article_contents');
            $criteria->removeSelectColumn($alias . '.article_bonus');
            $criteria->removeSelectColumn($alias . '.article_catchline');
            $criteria->removeSelectColumn($alias . '.article_biography');
            $criteria->removeSelectColumn($alias . '.article_motsv');
            $criteria->removeSelectColumn($alias . '.article_copyright');
            $criteria->removeSelectColumn($alias . '.article_pubdate');
            $criteria->removeSelectColumn($alias . '.article_keywords');
            $criteria->removeSelectColumn($alias . '.article_links');
            $criteria->removeSelectColumn($alias . '.article_keywords_generated');
            $criteria->removeSelectColumn($alias . '.article_publisher_stock');
            $criteria->removeSelectColumn($alias . '.article_hits');
            $criteria->removeSelectColumn($alias . '.article_editing_user');
            $criteria->removeSelectColumn($alias . '.article_insert');
            $criteria->removeSelectColumn($alias . '.article_update');
            $criteria->removeSelectColumn($alias . '.article_created');
            $criteria->removeSelectColumn($alias . '.article_updated');
            $criteria->removeSelectColumn($alias . '.article_done');
            $criteria->removeSelectColumn($alias . '.article_to_check');
            $criteria->removeSelectColumn($alias . '.article_deletion_by');
            $criteria->removeSelectColumn($alias . '.article_deletion_date');
            $criteria->removeSelectColumn($alias . '.article_deletion_reason');
            $criteria->removeSelectColumn($alias . '.lemonink_master_id');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap(): TableMap
    {
        return Propel::getServiceContainer()->getDatabaseMap(ArticleTableMap::DATABASE_NAME)->getTable(ArticleTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Article or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Article object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ?ConnectionInterface $con = null): int
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ArticleTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Article) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ArticleTableMap::DATABASE_NAME);
            $criteria->add(ArticleTableMap::COL_ARTICLE_ID, (array) $values, Criteria::IN);
        }

        $query = ArticleQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            ArticleTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                ArticleTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the articles table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return ArticleQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Article or Criteria object.
     *
     * @param mixed $criteria Criteria or Article object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ArticleTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Article object
        }

        if ($criteria->containsKey(ArticleTableMap::COL_ARTICLE_ID) && $criteria->keyContainsValue(ArticleTableMap::COL_ARTICLE_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.ArticleTableMap::COL_ARTICLE_ID.')');
        }


        // Set the correct dbName
        $query = ArticleQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
