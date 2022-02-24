<?php

require(dirname(__FILE__)."/../../common.php");

function getConnection() {
    require(dirname(__FILE__)."/../../config.php");
    return new PDO($dsn, $username, $password, $options);
}

class Record {
    static protected $table_name = "default_table_name";

    /*
    e.g. Override in the following manner
    static protected $has_many = array(
        "discussion_messages" => array(
            "class_name" => "DiscussionMessage",
            "foreign_key" => "discussion_id",
        )
    );
    */
    static protected $has_many = array();

    protected $is_new_record = true;
    // Stores arrays of entities for each association (or entity for 1-to-1
    // relationships)
    protected $associations = array();
    // Stores booleans to remember if each association has been loaded from the database
    protected $associations_are_loaded = array();

    // Loads a record and marks it as not new, i.e.
    // it will be treated as a record that has already
    // been saved to the database.
    protected static function loadRecordFromData($data) {
        $class = get_called_class();
        $obj = new $class();
        foreach (array_keys($data) as $attr) {
            $obj->$attr = $data[$attr];
        }
        $obj->is_new_record = false;
        return $obj;
    }

    public static function getAll() {
        return get_called_class()::where(array(1=>1));
    }

    /* 
        Usage: RecordName::where(array("name"=>"James", "student_id"=>12345));
    */  
    public static function where($attrs) {
        $table_name = get_called_class()::$table_name;
        $sql_wheres = array();
        foreach ($attrs as $key => $value) {
            array_push($sql_wheres, sprintf("%s = '%s'", $key, $value));
        }

        $sql = sprintf(
            "SELECT * FROM %s WHERE %s;",
            $table_name,
            implode(" AND ", $sql_wheres)
        );

        $statement = getConnection()->prepare($sql);
        $statement->execute();
        $res = $statement->fetchAll();

        return array_map([get_called_class(), 'loadRecordFromData'], $res);
    }

    /*
        Usage: RecordName::find_by(array("name"=>"James", "student_id"=>12345));
        Returns the first record that matches
    */
    public static function find_by($attrs) {
        $table_name = get_called_class()::$table_name;
        $sql_wheres = array();
        foreach ($attrs as $key => $value) {
            array_push($sql_wheres, sprintf("%s = '%s'", $key, $value));
        }

        $sql = sprintf(
            "SELECT * FROM %s WHERE %s LIMIT 1;",
            $table_name,
            implode(" AND ", $sql_wheres)
        );

        $statement = getConnection()->prepare($sql);
        $statement->execute();
        $res = $statement->fetchAll();

        if (count($res) > 0) {
            return get_called_class()::loadRecordFromData($res[0]);
        } else {
            return null;
        }
    }

    public static function getAttrs() {
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
    public function save() {
        if (!$this->is_new_record) {
            throw new ErrorException("Unimplemented feature: Saving dirty records.");
        }

        $new_obj = array();

        foreach (get_called_class()::getAttrs() as $attr) {
            $new_obj[$attr] = $this->$attr;
        }

        $new_obj['created_at'] = date('Y-m-d H:i:s');
        $new_obj['updated_at'] = date('Y-m-d H:i:s');

        $sql = sprintf(
            "INSERT INTO %s (%s) values (%s)",
            $this::$table_name,
            implode(", ", array_keys($new_obj)),
            ":" . implode(", :", array_keys($new_obj))
        );
        $conn = getConnection();
        $statement = $conn->prepare($sql);
        $statement->execute($new_obj);
        $this->id = $conn->lastInsertId();

        foreach(get_called_class()::$has_many as $association_name => $association_values) {
            foreach($this->$association_name as $obj) {
                $obj.save();
            }
        }
    }

    // public static function find_by_id($id) {
    //     return get_called_class()::find_by(array("id"=>$id));
    // }

    public static function get_table_name() {
        return get_called_class()::$table_name;
    }

    public function __get($name){
        // Make it easier to access has_many associations
        if (array_key_exists($name, get_called_class()::$has_many)) {
            if (isset($this->associations_are_loaded[$name])
            && $this->associations_are_loaded[$name]) {
                return $this->associations[$name];
            }

            $association = get_called_class()::$has_many[$name];

            $data = $association['class_name']::where(
                array($association['foreign_key']=>$this->id)
            );

            $this->associations[$name] = $data;
            $this->associations_are_loaded[$name] = true;

            return $data;
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }

    public static function __callStatic($name, $arguments) {
        if(startsWith($name, "find_by_")) {
            preg_match('/find_by_(\w+)/', $name, $match);
            return get_called_class()::find_by(array($match[1]=>$arguments[0]));
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined function via __call(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
    }
}

spl_autoload_register(function ($class_name) {
    require_once $class_name . '.php';
});