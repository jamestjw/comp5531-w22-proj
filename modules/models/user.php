<?php

function getConnection() {
    require(dirname(__FILE__)."/../../config.php");
    return new PDO($dsn, $username, $password, $options);
}

function getUsers() {
    $sql = "SELECT * 
            FROM users";

    $statement = getConnection()->prepare($sql);
    $statement->execute();
    $res = $statement->fetchAll();

    $load_user = function($data) {
        $u = new User();
        foreach (array_keys($data) as $attr) {
            $u->$attr = $data[$attr];
        }
        return $u;
    };

    return array_map($load_user, $res);
}

class Record {
    protected $table_name = "default_table_name";

    public static function getAttrs() {
        $class = get_called_class().$str;
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
            $this->table_name,
            implode(", ", array_keys($new_obj)),
            ":" . implode(", :", array_keys($new_obj))
        );
        
        $statement = getConnection()->prepare($sql);
        $statement->execute($new_obj);
    }
}

class User extends Record {
    protected $table_name = "users";

    public $id;
    public $first_name;
	public $last_name;
	public $email;
	public $is_admin;
	public $password_digest;
	public $created_at;
	public $updated_at;
}