<?php

function getConnection() {
    require(dirname(__FILE__)."/../../config.php");
    return new PDO($dsn, $username, $password, $options);
}

class Record {
    static protected $table_name = "default_table_name";

    protected static function loadRecordFromData($data) {
        $class = get_called_class();
        $obj = new $class();
        foreach (array_keys($data) as $attr) {
            $obj->$attr = $data[$attr];
        }
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
    }

    public static function find_by_id($id) {
        return get_called_class()::where(array("id"=>$id))[0];
    }

    public static function get_table_name() {
        return get_called_class()::$table_name;
    }
}

spl_autoload_register(function ($class_name) {
    require_once $class_name . '.php';
});