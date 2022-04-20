<?php

require_once(dirname(__FILE__)."/../../common.php");
require_once(dirname(__FILE__)."/utils.php");
require_once(dirname(__FILE__)."/query_builder.php");

function getConnection()
{
    require(dirname(__FILE__)."/../../config.php");
    return new PDO($dsn, $username, $password, $options);
}

class Record
{
    protected static $table_name = "default_table_name";

    /*
    e.g. Override in the following manner
    static protected $has_many = array(
        "discussion_messages" => array(
            "class_name" => "DiscussionMessage",
            "foreign_key" => "discussion_id",
        )
    );
    static protected $belongs_to = array(
        "user" => array(
            "class_name" => "User",
            "foreign_key" => "user_id",
        )
    );
    static protected $has_one = array(
        "attachment" => array(
            "class_name" => "Attachment",
            "foreign_key" => "attachable_id",
        )
    );
    */
    protected static $has_many = array();
    protected static $belongs_to = array();
    protected static $has_one = array();

    protected $is_new_record = true;
    // Stores arrays of entities for each association (or entity for 1-to-1
    // relationships)
    protected $associations = array();
    // Stores booleans to remember if each association has been loaded from the database
    protected $associations_are_loaded = array();

    // Loads a record and marks it as not new, i.e.
    // it will be treated as a record that has already
    // been saved to the database.
    public static function loadRecordFromData($data)
    {
        $class = get_called_class();
        $obj = new $class();

        foreach (array_keys($data) as $attr) {
            // $obj->$attr = $data[$attr];
            $obj->$attr = $data[$attr];
        }
        $obj->is_new_record = false;
        return $obj;
    }

    public static function getAll()
    {
        return get_called_class()::where(array());
    }

    /*
        Usage: RecordName::where(array("name"=>"James", "student_id"=>12345));
    */
    public static function where(array $attrs)
    {
        $query_builder = new QueryBuilder(get_called_class());
        return $query_builder->where($attrs);
    }

    /*
        Usage: RecordName::find_by(array("name"=>"James", "student_id"=>12345));
        Returns the first record that matches
    */
    public static function find_by(array $attrs)
    {
        $query_builder = new QueryBuilder(get_called_class());
        return $query_builder->find_by($attrs);
    }

    public function delete($pk="id") // Deletes by assuming $id is always the pk. Not sure if robust enough.
    {
        // Check to see if we are deleting a record that has not been saved yet.
        if ($this->is_new_record) {
            throw new ErrorException("Cannot delete an object that is not yet in a table. Please set the object to null instead.");
        }

        $attrs = $this->getAttrs();

        // Ensure that the pk exists
        if (!in_array($pk, $attrs)) {
            throw new ErrorException("Record trying to delete using a primary key that does not exist: ".$pk);
        }

        $result = get_called_class()::where(array($pk=>$this->$pk));
        // Make sure that only one record with the supplied key exists in the table
        if (count($result) > 1) {
            throw new ErrorException("Primary key specified is not valid as it returns more than one record. PK: ".$pk);
        }

        $sql = sprintf(
            "DELETE FROM %s WHERE %s = ?",
            get_called_class()::$table_name,
            $pk
        );

        execute_sql_query($sql, array($pk => $this->$pk));

        // Not sure if this is needed for now
        // Set all attributes of the called object to none
        // foreach ($attrs as $attr) {
        //     $this->$attr = null;
        // }

        return;
    }

    public function deleteWhere($pk, $pk2) // Deletes from tables where pk is compound key
    {
        // Check to see if we are deleting a record that has not been saved yet.
        if ($this->is_new_record) {
            throw new ErrorException("Cannot delete an object that is not yet in a table. Please set the object to null instead.");
        }

        $attrs = $this->getAttrs();

        // Ensure that the pk exists
        if (!in_array($pk, $attrs) || !in_array($pk2, $attrs)) {
            throw new ErrorException("Record trying to delete using a primary key that does not exist: ".$pk);
        }

        $result = get_called_class()::where(array($pk=>$this->$pk, $pk2=>$this->$pk2));
        // Make sure that only one record with the supplied key exists in the table
        if (count($result) > 1) {
            throw new ErrorException("Primary key specified is not valid as it returns more than one record. PK: ".$pk);
        }

        $sql = sprintf(
            "DELETE FROM %s WHERE %s = ? AND %s = ?",
            get_called_class()::$table_name,
            $pk, $pk2
        );

        execute_sql_query($sql, array($pk => $this->$pk, $pk2=>$this->$pk2));
        return;
    }

    public static function getAttrs()
    {
        $class = get_called_class();
        $obj = new $class();
        $reflect = new ReflectionClass($obj);
        // Assume that public properties are attributes of the record
        $props = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);

        $f = function ($value) {
            return $value->getName();
        };

        return array_map($f, $props);
    }

    // Saves a record to the database
    // For now this function only handles the case
    // when the record is new.
    // TODO: Support updating records
    public function save()
    {
        if (!$this->is_new_record) {
            return $this->update();
        }

        $new_obj = array();

        foreach (get_called_class()::getAttrs() as $attr) {
            if (isset($this->$attr) && !is_null($this->$attr)) {
                $new_obj[$attr] = $this->$attr;
            }
        }

        $new_obj['created_at'] = date('Y-m-d H:i:s');
        $new_obj['updated_at'] = date('Y-m-d H:i:s');

        $sql = sprintf(
            "INSERT INTO %s (%s) values (%s)",
            $this::$table_name,
            implode(", ", array_keys($new_obj)),
            implode(", ", array_map(fn ($i) => " ? ", $new_obj))
        );

        $conn = getConnection();
        execute_sql_query($sql, $new_obj, $conn);

        if (in_array("id", get_called_class()::getAttrs())) {
            $this->id = $conn->lastInsertId();
        }
        $this->is_new_record = false;

        // TODO: Fix n+1 saving
        foreach (get_called_class()::$has_many as $association_name => $association_values) {
            if (array_key_exists("as", $association_values)) {
                $foreign_key = $association_values['as']."_id";
                $polymorphic_type = $association_values['as']."_type";
                $is_polymorphic = true;
            } else {
                $foreign_key = $association_values['foreign_key'];
                $is_polymorphic = false;
            }

            if (array_key_exists($association_name, $this->associations)) {
                foreach ($this->associations[$association_name] as $obj) {
                    $obj->$foreign_key = $this->id;
                    if ($is_polymorphic) {
                        $obj->$polymorphic_type = get_called_class();
                    }
                    $obj->save();
                }
            }
        }

        foreach (get_called_class()::$has_one as $association_name => $association_values) {
            $foreign_key = array_key_exists('foreign_key', $association_values) ? $association_values['foreign_key'] : $association_values['as'].'_id' ;

            if (array_key_exists($association_name, $this->associations)) {
                $this->associations[$association_name]->$foreign_key = $this->id;
                $this->associations[$association_name]->save();
            }
        }
    }
    /*
        I decided to make this a private function that is only invoked internally by save.
        Save can be considered to be "saving details in the database" so the user shouldnt
        save a dirty record differently to how they save a clean record.
    */
    private function update($pk='id')
    {
        // Get attributes of the called object
        $attrs = $this->getAttrs();

        // Ensure that the pk exists
        if (!in_array($pk, $attrs)) {
            throw new ErrorException("Record trying to update using a primary key that does not exist: ".$pk);
        }

        // Get the record with the primary key from the table
        $old = get_called_class()::find_by(array($pk => $this->$pk));

        // See which attributes are different from the one in the table to object
        $to_update = [];
        foreach ($attrs as $attr) {
            if ($this->$attr !== $old->$attr) {
                $to_update[$attr] = $this->$attr;
            }
        }

        if (empty($to_update)) {
            return;
        }

        // update the differing fields
        $update_fields = array();
        $where_condition = sprintf("%s = %s", $pk, $this->$pk);
        foreach (array_keys($to_update) as $key) {
            array_push($update_fields, "$key = ?");
        }

        if (in_array("updated_at", get_called_class()::getAttrs())) {
            array_push($update_fields, "updated_at = now()");
        }

        $sql = sprintf(
            "UPDATE %s SET %s WHERE %s;",
            get_called_class()::$table_name,
            implode(', ', $update_fields),
            $where_condition
        );
        execute_sql_query($sql, $to_update);
        return;
    }

    public static function get_table_name()
    {
        return get_called_class()::$table_name;
    }

    public function __get($name)
    {
        // Make it easier to access has_many associations
        if (array_key_exists($name, get_called_class()::$has_many)) {
            if (isset($this->associations_are_loaded[$name])
            && $this->associations_are_loaded[$name]) {
                return $this->associations[$name];
            }

            $association = get_called_class()::$has_many[$name];

            if (array_key_exists('as', $association)) {
                $data = $association['class_name']::where(
                    array(
                        $association['as']."_id"=>$this->id,
                        $association['as']."_type"=>get_called_class()
                    )
                );
            } else {
                $data = $association['class_name']::where(
                    array($association['foreign_key']=>$this->id)
                );
            }

            $this->associations[$name] = $data;
            $this->associations_are_loaded[$name] = true;

            return $data;
        } elseif (array_key_exists($name, get_called_class()::$belongs_to)) {
            if (isset($this->associations_are_loaded[$name])
            && $this->associations_are_loaded[$name]) {
                return $this->associations[$name];
            }

            $association = get_called_class()::$belongs_to[$name];
            $foreign_key = $association['foreign_key'];
            $class_name = array_key_exists('class_name', $association) ? $association['class_name'] : $association[$name."_type"];

            if ($class_name == null) {
                // Because the polymorphic association is undefined
                return null;
            }

            $data = $class_name::find_by(
                array("id"=>$this->$foreign_key)
            );

            $this->associations[$name] = $data;
            $this->associations_are_loaded[$name] = true;

            return $data;
        } elseif (array_key_exists($name, get_called_class()::$has_one)) {
            if (isset($this->associations_are_loaded[$name])
            && $this->associations_are_loaded[$name]) {
                return $this->associations[$name];
            }

            $association = get_called_class()::$has_one[$name];

            if (array_key_exists('as', $association)) {
                $data = $association['class_name']::find_by(
                    array(
                        $association['as']."_id"=>$this->id,
                        $association['as']."_type"=>get_called_class()
                    )
                );
            } else {
                $data = $association['class_name']::find_by(
                    array($association['foreign_key']=>$this->id)
                );
            }

            $this->associations[$name] = $data;
            $this->associations_are_loaded[$name] = true;

            return $data;
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE
        );
        return null;
    }

    public function __set($name, $val)
    {
        // TODO: Figure out what is happening here,
        // why is PHP assigning with integer indices?
        if (preg_match('/\d+/', $name)) {
            return;
        }

        // Make it easier to access has_many, belongs_to and has_one associations
        if (array_key_exists($name, get_called_class()::$has_many)
            || array_key_exists($name, get_called_class()::$belongs_to)
            || array_key_exists($name, get_called_class()::$has_one)) {
            $this->associations[$name] = $val;
            $this->associations_are_loaded[$name] = true;

            return;
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __set(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE
        );
        return null;
    }

    public static function __callStatic($name, $arguments)
    {
        if (startsWith($name, "find_by_")) {
            preg_match('/find_by_(\w+)/', $name, $match);
            return get_called_class()::find_by(array($match[1]=>$arguments[0]));
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined function via __call(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE
        );
    }

    public static function getAssociations()
    {
        return array_merge(
            array_keys(get_called_class()::$has_many),
            array_keys(get_called_class()::$has_one),
            array_keys(get_called_class()::$belongs_to)
        );
    }

    public static function getAssociationType(string $association)
    {
        if (in_array($association, array_keys(get_called_class()::$has_many))) {
            return "has_many";
        } elseif (in_array($association, array_keys(get_called_class()::$has_one))) {
            return "has_one";
        } elseif (in_array($association, array_keys(get_called_class()::$belongs_to))) {
            return "belongs_to";
        }

        return;
    }

    public static function getAssociationClassName(string $association)
    {
        if (in_array($association, array_keys(get_called_class()::$has_many))) {
            return get_called_class()::$has_many[$association]['class_name'];
        } elseif (in_array($association, array_keys(get_called_class()::$has_one))) {
            return get_called_class()::$has_one[$association]['class_name'];
        } elseif (in_array($association, array_keys(get_called_class()::$belongs_to))) {
            $association_data = get_called_class()::$belongs_to[$association];
            if (array_key_exists("polymorphic", $association_data) && $association_data['polymorphic']) {
                return $association."_type";
            } else {
                return get_called_class()::$belongs_to[$association]['class_name'];
            }
        }

        return;
    }

    public static function getAssociationForeignKey(string $association)
    {
        if (in_array($association, array_keys(get_called_class()::$has_many))) {
            $association_data = get_called_class()::$has_many[$association];
            if (array_key_exists("as", $association_data)) {
                return $association_data['as']."_id";
            } else {
                return $association_data['foreign_key'];
            }
        } elseif (in_array($association, array_keys(get_called_class()::$has_one))) {
            $association_data = get_called_class()::$has_one[$association];
            if (array_key_exists("as", $association_data)) {
                return $association_data['as']."_id";
            } else {
                return $association_data['foreign_key'];
            }
        } elseif (in_array($association, array_keys(get_called_class()::$belongs_to))) {
            return get_called_class()::$belongs_to[$association]['foreign_key'];
        }
        return;
    }

    public static function getAssociationPolymorphicTypeColumn(string $association)
    {
        if (in_array($association, array_keys(get_called_class()::$has_many))) {
            $association_data = get_called_class()::$has_many[$association];
            if (array_key_exists("as", $association_data)) {
                return $association_data['as']."_type";
            }
        } elseif (in_array($association, array_keys(get_called_class()::$has_one))) {
            $association_data = get_called_class()::$has_one[$association];
            if (array_key_exists("as", $association_data)) {
                return $association_data['as']."_type";
            }
        } elseif (in_array($association, array_keys(get_called_class()::$belongs_to))) {
            $association_data = get_called_class()::$belongs_to[$association];
            if (array_key_exists("polymorphic", $association_data) && $association_data['polymorphic']) {
                return $association."_type";
            }
        }
        return;
    }

    public static function includes($args)
    {
        return (new QueryBuilder(get_called_class()))->includes($args);
    }
    public static function getLast()
    {
        return get_called_class()::order(["id" => "desc"])->limit(1)->getAll()[0] ?? null;
    }

    public static function order(array $data)
    {
        $query_builder = new QueryBuilder(get_called_class());
        return $query_builder->order($data);
    }

    public static function limit(int $l)
    {
        $query_builder = new QueryBuilder(get_called_class());
        return $query_builder->limit($l);
    }

    public static function joins(array $assocs)
    {
        $query_builder = new QueryBuilder(get_called_class());
        return $query_builder->joins($assocs);
    }
    
    /*
    Example:
        If we want to find discussions that have recent messages
        Discussion::join_raw_sql("
            JOIN discussion_messages on
                discussion_messages.discussion_id = discussions.id AND 
                discussions.created_at > now() - INTERVAL 8 HOUR");

        Note: For the above use case, we would need a distinct clause
        for it to work as expected, but the idea is clear.
    */
    public static function joins_raw_sql(string $join_sql)
    {
        $query_builder = new QueryBuilder(get_called_class());
        return $query_builder->joins_raw_sql($join_sql);
    }
}

spl_autoload_register(function ($class_name) {
    // Convert camel case to snake case
    require_once sprintf("%s/%s.php", dirname(__FILE__), strtolower(preg_replace('/([^A-Z])([A-Z])/', "$1_$2", $class_name)));
});
