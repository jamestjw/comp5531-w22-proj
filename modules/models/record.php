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
        $table_name = get_called_class()::$table_name;
        $sql = "SELECT * FROM $table_name;";

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
        $sql = sprintf(
            "SELECT * from %s WHERE %s = %s",
            get_called_class()::$table_name,
            "id",
            $id
        );

        $statement = getConnection()->prepare($sql);
        $statement->execute();
        $res = $statement->fetchAll();

        if (count($res) == 0) {
            return null;
        } else {
            return get_called_class()::loadRecordFromData($res[0]);
        }
    }

    public static function get_table_name() {
        return get_called_class()::$table_name;
    }
}