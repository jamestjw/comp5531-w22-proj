<?php
require_once (dirname(__FILE__)."/record.php");

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