<?php

require_once(dirname(__FILE__)."/record.php");

class User extends Record
{
    protected static $table_name = "users";

    public $id;
    public $student_id;
    public $first_name;
    public $last_name;
    public $email;
    public $is_admin;
    public $is_instructor;
    public $password_digest;
    public $created_at;
    public $updated_at;

    public function get_possible_roles()
    {
        $res = array();

        // Admins should have access to every role
        if ($this->is_admin) {
            array_push($res, "admin");
            array_push($res, "instructor");
            array_push($res, "student");
        }

        if ($this->is_instructor) {
            array_push($res, "instructor");
        }

        if (isset($this->student_id)) {
            array_push($res, "student");
        }

        return $res;
    }

}
