<?php

use Biblys\Exception\InvalidEntityFetchedException;
use Biblys\Service\Mailer;
use Biblys\Service\Log;

class Entity implements ArrayAccess, Iterator, Countable
{
    private $_attributes = [];
    private $cursor = 0;
    protected $prefix = 'entity';
    public $idField = 'entity_id';
    public $trackChange = true;

    public function __construct(array $data, $withJoins = true)
    {
        $this->hydrate($data);
        $this->cursor = 0;
        $this->idField = $this->prefix.'_id';
    }

    public function hydrate(array $data)
    {
        foreach ($data as $key => $val) {
            $this->set($key, $val);
        }
    }

    public function set($field, $value)
    {
        // If value is an object, create relation
        if (is_object($value)) {
            $this->set($field.'_id', $value->get('id'));
        }

        if ($field == $this->idField) {
            $value = (int) $value;
        }
        $this->_attributes[$field] = $value;

        return $this;
    }

    public function has($field)
    {
        if (!empty($this->_attributes[$field])) {
            return true;
        } elseif (!empty($this->_attributes[$this->prefix . '_' . $field])) {
            return true;
        }

        return false;
    }

    public function get($field)
    {
        if (isset($this->_attributes[$field])) {
            return $this->_attributes[$field];
        } elseif (isset($this->_attributes[$this->prefix . '_' . $field])) {
            return $this->_attributes[$this->prefix . '_' . $field];
        }
    }

    public function __get($field)
    {
        return $this->get($field);
    }

    public function remove($field)
    {
        unset($this->_attributes[$field]);
    }

    // Get related entitiy
    public function getRelated($entity)
    {
        global $_SQL;

        $entity_manager_class = ucfirst($entity).'Manager';
        $entity_manager = new $entity_manager_class();
        $entity_id = $this->get($entity.'_id');
        $entity = $entity_manager->getById($entity_id);

        return $entity;
    }

    // Get Linked entities
    public function getLinked($entity)
    {
        global $_SQL;
        $entity_name = ucfirst($entity);
        $entities = $_SQL->query('SELECT * FROM `'.$entity.'s` JOIN `links` USING(`'.$entity.'_id`) WHERE `'.$this->prefix.'_id` = '.$this->get('id'));
        $the_entities = array();
        while ($e = $entities->fetch(PDO::FETCH_ASSOC)) {
            $the_entities[] = new $entity_name($e);
        }

        return $the_entities;
    }

    // Interface ArrayAccess //

    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        if ($this->has($offset)) {
            $this->remove($offset);
        }
    }

    // Interface Iterator //

    /**
     * Retourne l'élément courant du tableau.
     */
    public function current()
    {
        return $this->_attributes[$this->key()];
    }

    /**
     * Retourne la clé actuelle (c'est la même que la position dans notre cas).
     */
    public function key()
    {
        $keys = array_keys($this->_attributes);
        return $keys[$this->cursor];
    }

    /**
     * Déplace le curseur vers l'élément suivant.
     */
    public function next()
    {
        ++$this->cursor;
    }

    /**
     * Remet la position du curseur à 0.
     */
    public function rewind()
    {
        $this->cursor = 0;
    }

    /**
     * Permet de tester si la position actuelle est valide.
     */
    public function valid()
    {
        $keys = array_keys($this->_attributes);
        return isset($keys[$this->cursor]);
    }

    // Interface Countable //

    public function count()
    {
        return count($this->_attributes);
    }

    public function __toString()
    {
        return json_encode($this->_attributes);
    }

    /**
     * Validates an entity based only on it's internal properties
     * CANNOT call it's manager or any other entity manager
     * CANNOT make any database query
     * Called after an entity was fetched from database
     * Throws MalformedEntityFetchedException
     */
    public function validateOnFetch(): void
    {
    }
}

class EntityManager
{
    protected $db;
    protected $site;
    protected $user;
    protected $prefix = 'entity';
    protected $table = 'entities';
    protected $object = 'Entity';
    protected $select = '*';
    protected $entities = array();
    protected $siteAgnostic = true;
    protected $_entityProperties = [];

    public function __construct(Site $site = null)
    {
        global $_SQL;
        global $_V;

        if ($site === null) {
            global $site;
        }

        $this->db = $_SQL;
        $this->site = $site;
        $this->user = $_V;
        $this->idField = $this->prefix.'_id';
    }

    /**
     * Entity exists ?
     */
    public function exists($where): bool
    {
        $entity = $this->get($where);
        if ($entity) {
            return true;
        }

        return false;
    }

    /**
     * Create unique slug.
     *
     * @param Entity $entity
     *
     * @return string
     */
    public function makeslug($entity)
    {
        if ($this->object == 'Collection') {
            $slug = collection_url($entity->get('publisher')->get('name'), $entity->get('name'));
        } elseif ($entity->has('title')) {
            $slug = makeurl($entity->get('title'));
        } elseif ($entity->has('name')) {
            $slug = makeurl($entity->get('name'));
        } else {
            $slug = $this->prefix . '_' . $entity->get('id');
        }

        // Check if slug is unique
        if ($u = $this->get(array($this->prefix.'_url' => $slug))) {
            // If slug exists but for another element
            if ($u->get('id') != $entity->get('id')) {
                // Append _id at the end
                $slug .= '_' . $entity->get('id');
            }
        }

        return $slug;
    }

    /**
     * Get query.
     */
    public function getQuery($query, $params, $options = array(), $withJoins = true)
    {
        $order = null;
        if (isset($options['order'])) {
            if ($options['order'] == 'shuffle') {
                $order = ' ORDER BY RAND()';
            } else {
                $order = ' ORDER BY '.$options['order'];
            }
        }

        $sort = null;
        if (isset($options['sort']) && $options['sort'] == 'desc') {
            $sort = ' DESC';
        }

        $limit = null;
        if (isset($options['limit'])) {
            $limit = ' LIMIT '.$options['limit'];
        }

        $offset = null;
        if (isset($options['offset'])) {
            if ($options["offset"] < 0) {
                throw new InvalidArgumentException("Offset options cannot be less than 0.");
            }
            $offset = ' OFFSET '.$options['offset'];
        }

        $leftjoin = null;
        if (isset($options['left-join'])) {
            foreach ($options['left-join'] as $lj) {
                $leftjoin .= ' LEFT JOIN `'.$lj['table'].'` USING(`'.$lj['key'].'`)';
            }
        }

        if (isset($options['fields'])) {
            $this->select = $options['fields'];
        }

        if ($query) {
            $query = "WHERE ".$query;
        }

        $query = 'SELECT '.$this->select.' FROM `'.$this->table.'`'.$leftjoin.$query.$order.$sort.$limit.$offset;

        $qu = self::prepareAndExecute($query, $params);

        $entities = array();
        while ($row = $qu->fetch(PDO::FETCH_ASSOC)) {
            $entity = new $this->object($row, $withJoins);
            $entity->validateOnFetch();
            $entities[] = $entity;
        }

        return $entities;
    }

    public static function prepareAndExecute($query, array $params)
    {
        global $_SQL;
        $config = new Biblys\Service\Config();

        try {
            // Logs sql query
            $logsConfig = $config->get('logs');
            if (isset($logsConfig['sql']) && $logsConfig['sql'] === true) {
                Log::sql('INFO', $query, $params);
            }

            $qu = $_SQL->prepare($query);
            $qu->execute($params);

            return $qu;
        } catch (Exception $e) {
            trigger_error($e->getMessage().'<br>query: '.$query.'<br>params: '.print_r($params, true));
        }
    }

    public function count(array $where = [])
    {
        if ($this->siteAgnostic === false) {
            global $site;
            $where['site_id'] = $site->get('id');
        }

        if (method_exists($this, 'addSiteFilters')) {
            $where = $this->addSiteFilters($where);
        }

        $q = EntityManager::buildSqlQuery($where);

        $queryWhere = '';
        if ($q['where']) {
            $queryWhere = ' WHERE '.$q['where'];
        }

        $query = 'SELECT COUNT(*) FROM `'.$this->table.'`'.$queryWhere;
        $res = EntityManager::prepareAndExecute($query, $q['params']);

        return $res->fetchColumn();
    }

    /**
     * Get all entities.
     *
     * @param $withJoins Do we want to get all joined entites along ?
     */
    public function getAll(array $where = array(), array $options = array(), $withJoins = true)
    {
        if ($this->siteAgnostic === false) {
            global $site;
            $where['site_id'] = $site->get('id');
        }

        $q = EntityManager::buildSqlQuery($where);

        return $this->getQuery($q['where'], $q['params'], $options, $withJoins);
    }

    /**
     * Get one entity by it's id from memory or from mysql.
     *
     * @param int $id
     */
    public function getById($id)
    {
        if (isset($this->entities[$id])) {
            return $this->entities[$id];
        } else {
            return $this->get([$this->idField => $id]);
        }
    }

    /**
     * Get entities by ids.
     */
    public function getByIds(array $ids)
    {
        $query = null;
        $params = array();
        $i = 0;

        foreach ($ids as $id) {
            if (isset($query)) {
                $query .= ' OR';
            }
            $query .= ' `'.$this->idField.'` = :id'.$i;
            $params['id'.$i] = $id;
            ++$i;
        }

        return $this->getQuery($query, $params);
    }

    /**
     * Get only one entity.
     */
    public function get(array $where, array $options = array())
    {
        $options['limit'] = 1;
        $entities = $this->getAll($where, $options);
        if (count($entities)) {
            return $entities[0];
        }

        return false;
    }

    /**
     * Filter entities with query.
     *
     * @param $filter the search terms
     */
    public function filter($filter, $where = [])
    {
        $query = null;
        $keywords = explode(' ', $filter);
        if (!isset($this->search_fields)) {
            return false;
        } else {
            $queries = [];
            $params = [];
            foreach ($keywords as $key => $value) {
                $params['param_'.$key] = '%'.$value.'%';
                $subqueries = [];
                foreach ($this->search_fields as $field) {
                    $subqueries[] = '`'.$this->prefix.'_'.$field.'` LIKE :param_'.$key;
                }
                $queries[] = '('.implode(' OR ', $subqueries).')';
            }
            $query = implode(' AND ', $queries);
        }

        return $this->getQuery($query, $params);
    }

    /**
     * Create new entity in database (persist).
     */
    public function create(array $defaults = array())
    {
        // If not site agnostic, add site id
        if ($this->siteAgnostic === false) {
            global $site;
            $defaults['site_id'] = $site->get('id');
        }

        $entity = new $this->object($defaults);

        // Preprocess
        $entity = $this->preprocess($entity);

        // Validate
        $this->validate($entity);

        // Default values
        $query = null;
        $values = null;
        $i = 0;
        $params = array();
        foreach ($entity as $property => $value) {
            if (is_object($value)) {
                continue;
            }

            if (!in_array($property, $this->_getEntityProperties())) {
                trigger_deprecation(
                    "biblys/biblys",
                    "2.53.1",
                    "Trying to create $this->object with invalid property $property"
                );
                continue;
            }

            $query .= ', `' . $property . '`';
            $values .= ', :key'.$i;
            $params['key' . $i] = $value;
            ++$i;
        }

        try {
            $qu = 'INSERT INTO `' . $this->table . '`(`' . $this->prefix . '_created`' . $query . ') VALUES(NOW()' . $values . ')';
            self::prepareAndExecute($qu, $params);
            $id = $this->db->lastInsertId();
        } catch (Exception $ex) {
            throw new Exception('Error creating '.$this->object.': '.$ex->getMessage());
        }

        $new = $this->getById($id);
        if (!$new) {
            // This error is probably caused by differents defaults in create and getAll methods
            throw new Exception('Error creating Entity');
        }

        return $new;
    }

    /**
     * Persist updated entity in database.
     *
     * @param Entity $entity the entity to update
     * @param string $reason the reason it has been updated (change log)
     *
     * @return Entity the updated entity
     */
    public function update($entity, $reason = null)
    {
        // Preprocess
        $entity = $this->preprocess($entity);

        // Validate
        $this->validateBeforeUpdate($entity);

        $fields = array(); // Updated fields array
        $query = array(); // SQL query
        $params = array(); // SQL query params
        $create_redirection = 0;

        // Get actual entity for comparison
        $o = $this->getById($entity->get('id'));
        if (!$o) {
            throw new Exception($this->object . ' #' . $entity->get('id') . ' not found');
        }

        // Build update query from modified fields
        $i = 0;
        $updatedProperties = [];
        foreach ($entity as $property => $value) {
            if (is_object($value)) {
                continue;
            }

            if (!in_array($property, $this->_getEntityProperties())) {
                trigger_deprecation(
                    "biblys/biblys",
                    "2.53.1",
                    "Trying to update $this->object with invalid property $property"
                );
                continue;
            }

            if ($o->get($property) != $value) { // If field has beed modified
                $fields[] = $property;

                if (
                    $property == $this->prefix . '_url'
                ) {
                    $create_redirection = 1; // Create redirection if url has changed
                }

                if (empty($value) && $value !== 0) {
                    $query[] = '`' . $property . '` = NULL';
                } else {
                    $query[] = '`' . $property . '` = :field' . $i;
                    $params['field' . $i] = $value;
                }
            }
            ++$i;
        }

        // Update database
        if (!empty($query)) {
            if ($this->prefix == 'user') {
                $this->idField = 'id';
            }

            try {
                $query = 'UPDATE `' . $this->table . '` SET ' . implode(', ', $query) . ', `' . $this->prefix . '_updated` = NOW() WHERE `' . $this->idField . '` = ' . $entity->get('id');
                $this->prepareAndExecute($query, $params);
            } catch (PDOException $e) {
                throw new Exception($e.' <br> Query: '.$query.' <br> Params: '.var_export($params, true));
            }

            // Create redirection if needed
            if ($create_redirection && $this->prefix == 'event') {
                $this->db->exec("REPLACE INTO `redirections`(`redirection_old`, `redirection_new`, `redirection_date`) VALUES('/evenement/" . $o->get('url') . "', '/evenement/" . $entity->get('url') . "', NOW())");
            }
        }

        return $this->getById($entity->get("id"));
    }

    /**
     * Reload entity from database;.
     *
     * @param object $entity the entity to reload
     *
     * @return object the reloaded entity
     */
    public function reload($entity)
    {
        return $this->getById($entity->get('id'));
    }

    /**
     * Is called before entity deletion to check if it can be deleted.
     *
     * @param Entity $entity The entity to check
     *
     * @return true if the entity can be deleted
     *
     * @throws Exception if it can't
     */
    public function beforeDelete($entity)
    {
        return true;
    }

    /**
     * Soft-delete entity from database.
     *
     * @param object $e Entity to delete
     */
    public function delete($entity, $reason = null)
    {
        // Check if entity can be deleted
        $this->beforeDelete($entity);

        try {
            $query = 'DELETE FROM `' . $this->table . '` WHERE `' . $this->idField . '` = ' . $entity->get('id');
            $this->db->exec($query);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage().' <br> Query: '.$query);
        }

        return true;
    }

    /**
     * Preprocess entity before they are created or updated.
     *
     * @param Entity $entity The entity to process
     *
     * @return Entity The processed entity
     */
    public function preprocess($entity)
    {
        return $entity;
    }

    /**
     * Preprocess entity before they are created or updated.
     *
     * @param Entity $entity The entity to process
     *
     * @return true if the entity is valid
     *
     * @throws Exception if it is not
     */
    public function validate($entity)
    {
        return true;
    }

    /**
     * Preprocess entity before they are updated.
     *
     * @param Entity $entity The entity to process
     *
     * @throws Exception if entity is invalid
     */
    public function validateBeforeUpdate($entity): void
    {
        $this->validate($entity);
    }

    public function getMailer()
    {
        if (isset($this->mailer)) {
            return $this->mailer;
        }

        $this->mailer = new Mailer();

        return $this->mailer;
    }

    /**
     * Build a SQL Query.
     *
     * @param array where an array of where criteria
     * @param array having an array of having criteria
     * @param int fieldCountPadding
     */
    public static function buildSqlQuery(
        array $where, array $having = array(), $fieldCountPadding = 0
    ) {
        $params = array();
        $i = 0;
        if (is_array($where)) {
            $q_where = null;
            foreach ($where as $key => $val) {
                if (isset($q_where)) {
                    $q_where .= ' AND ';
                }

                // $where['field NOT IN'] = array();
                if (preg_match('/^(.*) NOT IN$/', $key, $matches)) {
                    $q_where .= $matches[1].' NOT IN(';
                    $foreachCounter = 0;
                    foreach ($val as $v) {
                        $fieldName = 'field'.($i + $fieldCountPadding);
                        $params[$fieldName] = $v;
                        $q_where .= ':'.$fieldName;
                        if ($foreachCounter !== count($val) - 1) {
                            $q_where .= ',';
                        }
                        ++$foreachCounter;
                        ++$i;
                    }
                    $q_where .= ')';
                } elseif (is_array($val)) {
                    $ors = [];
                    foreach ($val as $v) {
                        $fieldName = 'field'.($i + $fieldCountPadding);
                        if ($v === 'NULL') {
                            $ors[] = '`'.$key.'` IS NULL';
                        } elseif ($v === 'NOT NULL') {
                            $ors[] = '`'.$key.'` IS NOT NULL';
                        } else {
                            $ors[] = '`'.$key.'` = :'.$fieldName;
                            $params[$fieldName] = $v;
                            ++$i;
                        }
                    }
                    $q_where .= '('.implode(' OR ', $ors).')';
                } elseif ($val === 'NULL') {
                    $q_where .= '`'.$key.'` IS NULL';
                } elseif ($val === 'NOT NULL') {
                    $q_where .= '`'.$key.'` IS NOT NULL';
                } elseif (preg_match("/^(!=|<\s|>\s|<=|>=|LIKE)\s*(.*)/", $val, $matches)) {
                    $q_where .= '`'.$key.'` '.$matches[1].' :field'.$i;
                    $params['field'.$i] = $matches[2];
                } else {
                    $q_where .= '`'.$key.'` = :field'.$i.'';
                    $params['field'.$i] = $val;
                }
                ++$i;
            }
        }

        if (is_array($having)) {
            $q_having = null;
            foreach ($having as $key => $val) {
                if (isset($q_having)) {
                    $q_having .= ' AND ';
                }
                $q_having .= '`'.$key.'` = :'.$key.'';
                $params[$key] = $val;
            }
            if (isset($q_having)) {
                $q_having = ' HAVING '.$q_having;
            }
        }

        return array('where' => $q_where, 'having' => $q_having, 'params' => $params);
    }

    private function _getEntityProperties(): array
    {
        if (!$this->_entityProperties) {
            $tableColumnsQuery = EntityManager::prepareAndExecute(
                "SHOW COLUMNS FROM `$this->table`",
                []
            );
            $tableColumnsResult = $tableColumnsQuery->fetchAll();
            $this->_entityProperties = array_map(function ($column) {
                return $column["Field"];
            }, $tableColumnsResult);
        }

        return $this->_entityProperties;
    }
}
