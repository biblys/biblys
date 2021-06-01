<?php

namespace Model\Map;

use Model\Event;
use Model\EventQuery;
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
 * This class defines the structure of the 'events' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class EventTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Model.Map.EventTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'events';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Model\\Event';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Model.Event';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 21;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 21;

    /**
     * the column name for the event_id field
     */
    const COL_EVENT_ID = 'events.event_id';

    /**
     * the column name for the site_id field
     */
    const COL_SITE_ID = 'events.site_id';

    /**
     * the column name for the publisher_id field
     */
    const COL_PUBLISHER_ID = 'events.publisher_id';

    /**
     * the column name for the bookshop_id field
     */
    const COL_BOOKSHOP_ID = 'events.bookshop_id';

    /**
     * the column name for the library_id field
     */
    const COL_LIBRARY_ID = 'events.library_id';

    /**
     * the column name for the event_url field
     */
    const COL_EVENT_URL = 'events.event_url';

    /**
     * the column name for the event_title field
     */
    const COL_EVENT_TITLE = 'events.event_title';

    /**
     * the column name for the event_subtitle field
     */
    const COL_EVENT_SUBTITLE = 'events.event_subtitle';

    /**
     * the column name for the event_desc field
     */
    const COL_EVENT_DESC = 'events.event_desc';

    /**
     * the column name for the event_location field
     */
    const COL_EVENT_LOCATION = 'events.event_location';

    /**
     * the column name for the event_illustration_legend field
     */
    const COL_EVENT_ILLUSTRATION_LEGEND = 'events.event_illustration_legend';

    /**
     * the column name for the event_highlighted field
     */
    const COL_EVENT_HIGHLIGHTED = 'events.event_highlighted';

    /**
     * the column name for the event_start field
     */
    const COL_EVENT_START = 'events.event_start';

    /**
     * the column name for the event_end field
     */
    const COL_EVENT_END = 'events.event_end';

    /**
     * the column name for the event_date field
     */
    const COL_EVENT_DATE = 'events.event_date';

    /**
     * the column name for the event_status field
     */
    const COL_EVENT_STATUS = 'events.event_status';

    /**
     * the column name for the event_insert_ field
     */
    const COL_EVENT_INSERT_ = 'events.event_insert_';

    /**
     * the column name for the event_update_ field
     */
    const COL_EVENT_UPDATE_ = 'events.event_update_';

    /**
     * the column name for the event_created field
     */
    const COL_EVENT_CREATED = 'events.event_created';

    /**
     * the column name for the event_updated field
     */
    const COL_EVENT_UPDATED = 'events.event_updated';

    /**
     * the column name for the event_deleted field
     */
    const COL_EVENT_DELETED = 'events.event_deleted';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'SiteId', 'PublisherId', 'BookshopId', 'LibraryId', 'Url', 'Title', 'Subtitle', 'Desc', 'Location', 'IllustrationLegend', 'Highlighted', 'Start', 'End', 'Date', 'Status', 'Insert', 'Update', 'CreatedAt', 'UpdatedAt', 'DeletedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'siteId', 'publisherId', 'bookshopId', 'libraryId', 'url', 'title', 'subtitle', 'desc', 'location', 'illustrationLegend', 'highlighted', 'start', 'end', 'date', 'status', 'insert', 'update', 'createdAt', 'updatedAt', 'deletedAt', ),
        self::TYPE_COLNAME       => array(EventTableMap::COL_EVENT_ID, EventTableMap::COL_SITE_ID, EventTableMap::COL_PUBLISHER_ID, EventTableMap::COL_BOOKSHOP_ID, EventTableMap::COL_LIBRARY_ID, EventTableMap::COL_EVENT_URL, EventTableMap::COL_EVENT_TITLE, EventTableMap::COL_EVENT_SUBTITLE, EventTableMap::COL_EVENT_DESC, EventTableMap::COL_EVENT_LOCATION, EventTableMap::COL_EVENT_ILLUSTRATION_LEGEND, EventTableMap::COL_EVENT_HIGHLIGHTED, EventTableMap::COL_EVENT_START, EventTableMap::COL_EVENT_END, EventTableMap::COL_EVENT_DATE, EventTableMap::COL_EVENT_STATUS, EventTableMap::COL_EVENT_INSERT_, EventTableMap::COL_EVENT_UPDATE_, EventTableMap::COL_EVENT_CREATED, EventTableMap::COL_EVENT_UPDATED, EventTableMap::COL_EVENT_DELETED, ),
        self::TYPE_FIELDNAME     => array('event_id', 'site_id', 'publisher_id', 'bookshop_id', 'library_id', 'event_url', 'event_title', 'event_subtitle', 'event_desc', 'event_location', 'event_illustration_legend', 'event_highlighted', 'event_start', 'event_end', 'event_date', 'event_status', 'event_insert_', 'event_update_', 'event_created', 'event_updated', 'event_deleted', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'SiteId' => 1, 'PublisherId' => 2, 'BookshopId' => 3, 'LibraryId' => 4, 'Url' => 5, 'Title' => 6, 'Subtitle' => 7, 'Desc' => 8, 'Location' => 9, 'IllustrationLegend' => 10, 'Highlighted' => 11, 'Start' => 12, 'End' => 13, 'Date' => 14, 'Status' => 15, 'Insert' => 16, 'Update' => 17, 'CreatedAt' => 18, 'UpdatedAt' => 19, 'DeletedAt' => 20, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'siteId' => 1, 'publisherId' => 2, 'bookshopId' => 3, 'libraryId' => 4, 'url' => 5, 'title' => 6, 'subtitle' => 7, 'desc' => 8, 'location' => 9, 'illustrationLegend' => 10, 'highlighted' => 11, 'start' => 12, 'end' => 13, 'date' => 14, 'status' => 15, 'insert' => 16, 'update' => 17, 'createdAt' => 18, 'updatedAt' => 19, 'deletedAt' => 20, ),
        self::TYPE_COLNAME       => array(EventTableMap::COL_EVENT_ID => 0, EventTableMap::COL_SITE_ID => 1, EventTableMap::COL_PUBLISHER_ID => 2, EventTableMap::COL_BOOKSHOP_ID => 3, EventTableMap::COL_LIBRARY_ID => 4, EventTableMap::COL_EVENT_URL => 5, EventTableMap::COL_EVENT_TITLE => 6, EventTableMap::COL_EVENT_SUBTITLE => 7, EventTableMap::COL_EVENT_DESC => 8, EventTableMap::COL_EVENT_LOCATION => 9, EventTableMap::COL_EVENT_ILLUSTRATION_LEGEND => 10, EventTableMap::COL_EVENT_HIGHLIGHTED => 11, EventTableMap::COL_EVENT_START => 12, EventTableMap::COL_EVENT_END => 13, EventTableMap::COL_EVENT_DATE => 14, EventTableMap::COL_EVENT_STATUS => 15, EventTableMap::COL_EVENT_INSERT_ => 16, EventTableMap::COL_EVENT_UPDATE_ => 17, EventTableMap::COL_EVENT_CREATED => 18, EventTableMap::COL_EVENT_UPDATED => 19, EventTableMap::COL_EVENT_DELETED => 20, ),
        self::TYPE_FIELDNAME     => array('event_id' => 0, 'site_id' => 1, 'publisher_id' => 2, 'bookshop_id' => 3, 'library_id' => 4, 'event_url' => 5, 'event_title' => 6, 'event_subtitle' => 7, 'event_desc' => 8, 'event_location' => 9, 'event_illustration_legend' => 10, 'event_highlighted' => 11, 'event_start' => 12, 'event_end' => 13, 'event_date' => 14, 'event_status' => 15, 'event_insert_' => 16, 'event_update_' => 17, 'event_created' => 18, 'event_updated' => 19, 'event_deleted' => 20, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, )
    );

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var string[]
     */
    protected $normalizedColumnNameMap = [

        'Id' => 'EVENT_ID',
        'Event.Id' => 'EVENT_ID',
        'id' => 'EVENT_ID',
        'event.id' => 'EVENT_ID',
        'EventTableMap::COL_EVENT_ID' => 'EVENT_ID',
        'COL_EVENT_ID' => 'EVENT_ID',
        'event_id' => 'EVENT_ID',
        'events.event_id' => 'EVENT_ID',
        'SiteId' => 'SITE_ID',
        'Event.SiteId' => 'SITE_ID',
        'siteId' => 'SITE_ID',
        'event.siteId' => 'SITE_ID',
        'EventTableMap::COL_SITE_ID' => 'SITE_ID',
        'COL_SITE_ID' => 'SITE_ID',
        'site_id' => 'SITE_ID',
        'events.site_id' => 'SITE_ID',
        'PublisherId' => 'PUBLISHER_ID',
        'Event.PublisherId' => 'PUBLISHER_ID',
        'publisherId' => 'PUBLISHER_ID',
        'event.publisherId' => 'PUBLISHER_ID',
        'EventTableMap::COL_PUBLISHER_ID' => 'PUBLISHER_ID',
        'COL_PUBLISHER_ID' => 'PUBLISHER_ID',
        'publisher_id' => 'PUBLISHER_ID',
        'events.publisher_id' => 'PUBLISHER_ID',
        'BookshopId' => 'BOOKSHOP_ID',
        'Event.BookshopId' => 'BOOKSHOP_ID',
        'bookshopId' => 'BOOKSHOP_ID',
        'event.bookshopId' => 'BOOKSHOP_ID',
        'EventTableMap::COL_BOOKSHOP_ID' => 'BOOKSHOP_ID',
        'COL_BOOKSHOP_ID' => 'BOOKSHOP_ID',
        'bookshop_id' => 'BOOKSHOP_ID',
        'events.bookshop_id' => 'BOOKSHOP_ID',
        'LibraryId' => 'LIBRARY_ID',
        'Event.LibraryId' => 'LIBRARY_ID',
        'libraryId' => 'LIBRARY_ID',
        'event.libraryId' => 'LIBRARY_ID',
        'EventTableMap::COL_LIBRARY_ID' => 'LIBRARY_ID',
        'COL_LIBRARY_ID' => 'LIBRARY_ID',
        'library_id' => 'LIBRARY_ID',
        'events.library_id' => 'LIBRARY_ID',
        'Url' => 'EVENT_URL',
        'Event.Url' => 'EVENT_URL',
        'url' => 'EVENT_URL',
        'event.url' => 'EVENT_URL',
        'EventTableMap::COL_EVENT_URL' => 'EVENT_URL',
        'COL_EVENT_URL' => 'EVENT_URL',
        'event_url' => 'EVENT_URL',
        'events.event_url' => 'EVENT_URL',
        'Title' => 'EVENT_TITLE',
        'Event.Title' => 'EVENT_TITLE',
        'title' => 'EVENT_TITLE',
        'event.title' => 'EVENT_TITLE',
        'EventTableMap::COL_EVENT_TITLE' => 'EVENT_TITLE',
        'COL_EVENT_TITLE' => 'EVENT_TITLE',
        'event_title' => 'EVENT_TITLE',
        'events.event_title' => 'EVENT_TITLE',
        'Subtitle' => 'EVENT_SUBTITLE',
        'Event.Subtitle' => 'EVENT_SUBTITLE',
        'subtitle' => 'EVENT_SUBTITLE',
        'event.subtitle' => 'EVENT_SUBTITLE',
        'EventTableMap::COL_EVENT_SUBTITLE' => 'EVENT_SUBTITLE',
        'COL_EVENT_SUBTITLE' => 'EVENT_SUBTITLE',
        'event_subtitle' => 'EVENT_SUBTITLE',
        'events.event_subtitle' => 'EVENT_SUBTITLE',
        'Desc' => 'EVENT_DESC',
        'Event.Desc' => 'EVENT_DESC',
        'desc' => 'EVENT_DESC',
        'event.desc' => 'EVENT_DESC',
        'EventTableMap::COL_EVENT_DESC' => 'EVENT_DESC',
        'COL_EVENT_DESC' => 'EVENT_DESC',
        'event_desc' => 'EVENT_DESC',
        'events.event_desc' => 'EVENT_DESC',
        'Location' => 'EVENT_LOCATION',
        'Event.Location' => 'EVENT_LOCATION',
        'location' => 'EVENT_LOCATION',
        'event.location' => 'EVENT_LOCATION',
        'EventTableMap::COL_EVENT_LOCATION' => 'EVENT_LOCATION',
        'COL_EVENT_LOCATION' => 'EVENT_LOCATION',
        'event_location' => 'EVENT_LOCATION',
        'events.event_location' => 'EVENT_LOCATION',
        'IllustrationLegend' => 'EVENT_ILLUSTRATION_LEGEND',
        'Event.IllustrationLegend' => 'EVENT_ILLUSTRATION_LEGEND',
        'illustrationLegend' => 'EVENT_ILLUSTRATION_LEGEND',
        'event.illustrationLegend' => 'EVENT_ILLUSTRATION_LEGEND',
        'EventTableMap::COL_EVENT_ILLUSTRATION_LEGEND' => 'EVENT_ILLUSTRATION_LEGEND',
        'COL_EVENT_ILLUSTRATION_LEGEND' => 'EVENT_ILLUSTRATION_LEGEND',
        'event_illustration_legend' => 'EVENT_ILLUSTRATION_LEGEND',
        'events.event_illustration_legend' => 'EVENT_ILLUSTRATION_LEGEND',
        'Highlighted' => 'EVENT_HIGHLIGHTED',
        'Event.Highlighted' => 'EVENT_HIGHLIGHTED',
        'highlighted' => 'EVENT_HIGHLIGHTED',
        'event.highlighted' => 'EVENT_HIGHLIGHTED',
        'EventTableMap::COL_EVENT_HIGHLIGHTED' => 'EVENT_HIGHLIGHTED',
        'COL_EVENT_HIGHLIGHTED' => 'EVENT_HIGHLIGHTED',
        'event_highlighted' => 'EVENT_HIGHLIGHTED',
        'events.event_highlighted' => 'EVENT_HIGHLIGHTED',
        'Start' => 'EVENT_START',
        'Event.Start' => 'EVENT_START',
        'start' => 'EVENT_START',
        'event.start' => 'EVENT_START',
        'EventTableMap::COL_EVENT_START' => 'EVENT_START',
        'COL_EVENT_START' => 'EVENT_START',
        'event_start' => 'EVENT_START',
        'events.event_start' => 'EVENT_START',
        'End' => 'EVENT_END',
        'Event.End' => 'EVENT_END',
        'end' => 'EVENT_END',
        'event.end' => 'EVENT_END',
        'EventTableMap::COL_EVENT_END' => 'EVENT_END',
        'COL_EVENT_END' => 'EVENT_END',
        'event_end' => 'EVENT_END',
        'events.event_end' => 'EVENT_END',
        'Date' => 'EVENT_DATE',
        'Event.Date' => 'EVENT_DATE',
        'date' => 'EVENT_DATE',
        'event.date' => 'EVENT_DATE',
        'EventTableMap::COL_EVENT_DATE' => 'EVENT_DATE',
        'COL_EVENT_DATE' => 'EVENT_DATE',
        'event_date' => 'EVENT_DATE',
        'events.event_date' => 'EVENT_DATE',
        'Status' => 'EVENT_STATUS',
        'Event.Status' => 'EVENT_STATUS',
        'status' => 'EVENT_STATUS',
        'event.status' => 'EVENT_STATUS',
        'EventTableMap::COL_EVENT_STATUS' => 'EVENT_STATUS',
        'COL_EVENT_STATUS' => 'EVENT_STATUS',
        'event_status' => 'EVENT_STATUS',
        'events.event_status' => 'EVENT_STATUS',
        'Insert' => 'EVENT_INSERT_',
        'Event.Insert' => 'EVENT_INSERT_',
        'insert' => 'EVENT_INSERT_',
        'event.insert' => 'EVENT_INSERT_',
        'EventTableMap::COL_EVENT_INSERT_' => 'EVENT_INSERT_',
        'COL_EVENT_INSERT_' => 'EVENT_INSERT_',
        'event_insert_' => 'EVENT_INSERT_',
        'events.event_insert_' => 'EVENT_INSERT_',
        'Update' => 'EVENT_UPDATE_',
        'Event.Update' => 'EVENT_UPDATE_',
        'update' => 'EVENT_UPDATE_',
        'event.update' => 'EVENT_UPDATE_',
        'EventTableMap::COL_EVENT_UPDATE_' => 'EVENT_UPDATE_',
        'COL_EVENT_UPDATE_' => 'EVENT_UPDATE_',
        'event_update_' => 'EVENT_UPDATE_',
        'events.event_update_' => 'EVENT_UPDATE_',
        'CreatedAt' => 'EVENT_CREATED',
        'Event.CreatedAt' => 'EVENT_CREATED',
        'createdAt' => 'EVENT_CREATED',
        'event.createdAt' => 'EVENT_CREATED',
        'EventTableMap::COL_EVENT_CREATED' => 'EVENT_CREATED',
        'COL_EVENT_CREATED' => 'EVENT_CREATED',
        'event_created' => 'EVENT_CREATED',
        'events.event_created' => 'EVENT_CREATED',
        'UpdatedAt' => 'EVENT_UPDATED',
        'Event.UpdatedAt' => 'EVENT_UPDATED',
        'updatedAt' => 'EVENT_UPDATED',
        'event.updatedAt' => 'EVENT_UPDATED',
        'EventTableMap::COL_EVENT_UPDATED' => 'EVENT_UPDATED',
        'COL_EVENT_UPDATED' => 'EVENT_UPDATED',
        'event_updated' => 'EVENT_UPDATED',
        'events.event_updated' => 'EVENT_UPDATED',
        'DeletedAt' => 'EVENT_DELETED',
        'Event.DeletedAt' => 'EVENT_DELETED',
        'deletedAt' => 'EVENT_DELETED',
        'event.deletedAt' => 'EVENT_DELETED',
        'EventTableMap::COL_EVENT_DELETED' => 'EVENT_DELETED',
        'COL_EVENT_DELETED' => 'EVENT_DELETED',
        'event_deleted' => 'EVENT_DELETED',
        'events.event_deleted' => 'EVENT_DELETED',
    ];

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('events');
        $this->setPhpName('Event');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Event');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('event_id', 'Id', 'INTEGER', true, 10, null);
        $this->addColumn('site_id', 'SiteId', 'INTEGER', false, 10, null);
        $this->addColumn('publisher_id', 'PublisherId', 'INTEGER', false, null, null);
        $this->addColumn('bookshop_id', 'BookshopId', 'INTEGER', false, 10, null);
        $this->addColumn('library_id', 'LibraryId', 'INTEGER', false, 10, null);
        $this->addColumn('event_url', 'Url', 'VARCHAR', false, 128, null);
        $this->addColumn('event_title', 'Title', 'LONGVARCHAR', false, null, null);
        $this->addColumn('event_subtitle', 'Subtitle', 'LONGVARCHAR', false, null, null);
        $this->addColumn('event_desc', 'Desc', 'LONGVARCHAR', false, null, null);
        $this->addColumn('event_location', 'Location', 'LONGVARCHAR', false, null, null);
        $this->addColumn('event_illustration_legend', 'IllustrationLegend', 'VARCHAR', false, 64, null);
        $this->addColumn('event_highlighted', 'Highlighted', 'BOOLEAN', false, 1, null);
        $this->addColumn('event_start', 'Start', 'TIMESTAMP', false, null, null);
        $this->addColumn('event_end', 'End', 'TIMESTAMP', false, null, null);
        $this->addColumn('event_date', 'Date', 'TIMESTAMP', true, null, 'CURRENT_TIMESTAMP');
        $this->addColumn('event_status', 'Status', 'BOOLEAN', false, 1, null);
        $this->addColumn('event_insert_', 'Insert', 'TIMESTAMP', false, null, null);
        $this->addColumn('event_update_', 'Update', 'TIMESTAMP', false, null, null);
        $this->addColumn('event_created', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('event_updated', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('event_deleted', 'DeletedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' => array('create_column' => 'event_created', 'update_column' => 'event_updated', 'disable_created_at' => 'false', 'disable_updated_at' => 'false', ),
        );
    } // getBehaviors()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
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
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
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
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? EventTableMap::CLASS_DEFAULT : EventTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (Event object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = EventTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = EventTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + EventTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = EventTableMap::OM_CLASS;
            /** @var Event $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            EventTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = EventTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = EventTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Event $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                EventTableMap::addInstanceToPool($obj, $key);
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
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(EventTableMap::COL_EVENT_ID);
            $criteria->addSelectColumn(EventTableMap::COL_SITE_ID);
            $criteria->addSelectColumn(EventTableMap::COL_PUBLISHER_ID);
            $criteria->addSelectColumn(EventTableMap::COL_BOOKSHOP_ID);
            $criteria->addSelectColumn(EventTableMap::COL_LIBRARY_ID);
            $criteria->addSelectColumn(EventTableMap::COL_EVENT_URL);
            $criteria->addSelectColumn(EventTableMap::COL_EVENT_TITLE);
            $criteria->addSelectColumn(EventTableMap::COL_EVENT_SUBTITLE);
            $criteria->addSelectColumn(EventTableMap::COL_EVENT_DESC);
            $criteria->addSelectColumn(EventTableMap::COL_EVENT_LOCATION);
            $criteria->addSelectColumn(EventTableMap::COL_EVENT_ILLUSTRATION_LEGEND);
            $criteria->addSelectColumn(EventTableMap::COL_EVENT_HIGHLIGHTED);
            $criteria->addSelectColumn(EventTableMap::COL_EVENT_START);
            $criteria->addSelectColumn(EventTableMap::COL_EVENT_END);
            $criteria->addSelectColumn(EventTableMap::COL_EVENT_DATE);
            $criteria->addSelectColumn(EventTableMap::COL_EVENT_STATUS);
            $criteria->addSelectColumn(EventTableMap::COL_EVENT_INSERT_);
            $criteria->addSelectColumn(EventTableMap::COL_EVENT_UPDATE_);
            $criteria->addSelectColumn(EventTableMap::COL_EVENT_CREATED);
            $criteria->addSelectColumn(EventTableMap::COL_EVENT_UPDATED);
            $criteria->addSelectColumn(EventTableMap::COL_EVENT_DELETED);
        } else {
            $criteria->addSelectColumn($alias . '.event_id');
            $criteria->addSelectColumn($alias . '.site_id');
            $criteria->addSelectColumn($alias . '.publisher_id');
            $criteria->addSelectColumn($alias . '.bookshop_id');
            $criteria->addSelectColumn($alias . '.library_id');
            $criteria->addSelectColumn($alias . '.event_url');
            $criteria->addSelectColumn($alias . '.event_title');
            $criteria->addSelectColumn($alias . '.event_subtitle');
            $criteria->addSelectColumn($alias . '.event_desc');
            $criteria->addSelectColumn($alias . '.event_location');
            $criteria->addSelectColumn($alias . '.event_illustration_legend');
            $criteria->addSelectColumn($alias . '.event_highlighted');
            $criteria->addSelectColumn($alias . '.event_start');
            $criteria->addSelectColumn($alias . '.event_end');
            $criteria->addSelectColumn($alias . '.event_date');
            $criteria->addSelectColumn($alias . '.event_status');
            $criteria->addSelectColumn($alias . '.event_insert_');
            $criteria->addSelectColumn($alias . '.event_update_');
            $criteria->addSelectColumn($alias . '.event_created');
            $criteria->addSelectColumn($alias . '.event_updated');
            $criteria->addSelectColumn($alias . '.event_deleted');
        }
    }

    /**
     * Remove all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be removed as they are only loaded on demand.
     *
     * @param Criteria $criteria object containing the columns to remove.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function removeSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->removeSelectColumn(EventTableMap::COL_EVENT_ID);
            $criteria->removeSelectColumn(EventTableMap::COL_SITE_ID);
            $criteria->removeSelectColumn(EventTableMap::COL_PUBLISHER_ID);
            $criteria->removeSelectColumn(EventTableMap::COL_BOOKSHOP_ID);
            $criteria->removeSelectColumn(EventTableMap::COL_LIBRARY_ID);
            $criteria->removeSelectColumn(EventTableMap::COL_EVENT_URL);
            $criteria->removeSelectColumn(EventTableMap::COL_EVENT_TITLE);
            $criteria->removeSelectColumn(EventTableMap::COL_EVENT_SUBTITLE);
            $criteria->removeSelectColumn(EventTableMap::COL_EVENT_DESC);
            $criteria->removeSelectColumn(EventTableMap::COL_EVENT_LOCATION);
            $criteria->removeSelectColumn(EventTableMap::COL_EVENT_ILLUSTRATION_LEGEND);
            $criteria->removeSelectColumn(EventTableMap::COL_EVENT_HIGHLIGHTED);
            $criteria->removeSelectColumn(EventTableMap::COL_EVENT_START);
            $criteria->removeSelectColumn(EventTableMap::COL_EVENT_END);
            $criteria->removeSelectColumn(EventTableMap::COL_EVENT_DATE);
            $criteria->removeSelectColumn(EventTableMap::COL_EVENT_STATUS);
            $criteria->removeSelectColumn(EventTableMap::COL_EVENT_INSERT_);
            $criteria->removeSelectColumn(EventTableMap::COL_EVENT_UPDATE_);
            $criteria->removeSelectColumn(EventTableMap::COL_EVENT_CREATED);
            $criteria->removeSelectColumn(EventTableMap::COL_EVENT_UPDATED);
            $criteria->removeSelectColumn(EventTableMap::COL_EVENT_DELETED);
        } else {
            $criteria->removeSelectColumn($alias . '.event_id');
            $criteria->removeSelectColumn($alias . '.site_id');
            $criteria->removeSelectColumn($alias . '.publisher_id');
            $criteria->removeSelectColumn($alias . '.bookshop_id');
            $criteria->removeSelectColumn($alias . '.library_id');
            $criteria->removeSelectColumn($alias . '.event_url');
            $criteria->removeSelectColumn($alias . '.event_title');
            $criteria->removeSelectColumn($alias . '.event_subtitle');
            $criteria->removeSelectColumn($alias . '.event_desc');
            $criteria->removeSelectColumn($alias . '.event_location');
            $criteria->removeSelectColumn($alias . '.event_illustration_legend');
            $criteria->removeSelectColumn($alias . '.event_highlighted');
            $criteria->removeSelectColumn($alias . '.event_start');
            $criteria->removeSelectColumn($alias . '.event_end');
            $criteria->removeSelectColumn($alias . '.event_date');
            $criteria->removeSelectColumn($alias . '.event_status');
            $criteria->removeSelectColumn($alias . '.event_insert_');
            $criteria->removeSelectColumn($alias . '.event_update_');
            $criteria->removeSelectColumn($alias . '.event_created');
            $criteria->removeSelectColumn($alias . '.event_updated');
            $criteria->removeSelectColumn($alias . '.event_deleted');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(EventTableMap::DATABASE_NAME)->getTable(EventTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(EventTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(EventTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new EventTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Event or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Event object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Event) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(EventTableMap::DATABASE_NAME);
            $criteria->add(EventTableMap::COL_EVENT_ID, (array) $values, Criteria::IN);
        }

        $query = EventQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            EventTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                EventTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the events table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return EventQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Event or Criteria object.
     *
     * @param mixed               $criteria Criteria or Event object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Event object
        }

        if ($criteria->containsKey(EventTableMap::COL_EVENT_ID) && $criteria->keyContainsValue(EventTableMap::COL_EVENT_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.EventTableMap::COL_EVENT_ID.')');
        }


        // Set the correct dbName
        $query = EventQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // EventTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
EventTableMap::buildTableMap();
