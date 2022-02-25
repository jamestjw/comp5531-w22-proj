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

        foreach (User::getAttrs() as $attr) {
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
        
        $statement = getConnection()->prepare($sql);
        $statement->execute($new_obj);
    }
}

class User extends Record {
    static protected $table_name = "users";

    public $id;
    public $student_id;
    public $first_name;
	public $last_name;
	public $email;
	public $is_admin;
	public $password_digest;
	public $created_at;
	public $updated_at;

    // TODO: Make this method more generic and move it
    // to the Record class
    public static function find_by_email($email) {
        $sql = sprintf(
            "SELECT * from %s WHERE %s = '%s'",
            get_called_class()::$table_name,
            "email",
            $email
        );

        $statement = getConnection()->prepare($sql);
        $statement->execute();
        $res = $statement->fetchAll();

        if (count($res) == 0) {
            return null;
        } else {
            return Record::loadRecordFromData($res[0]);
        }
    }
}