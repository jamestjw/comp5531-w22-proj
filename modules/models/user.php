<?php
// Andréanne Chartrand-Beaudry - 29605991
// James Juan Whei Tan - 40161156
// Christopher Almeida Neves - 27521979
?>
<?php

require_once(dirname(__FILE__)."/record.php");

class User extends Record
{
    // TODO: Implement soft deletion when searching on null columns with
    // where clauses is implemented
    protected static $table_name = "users";

    public $id;
    public $student_id;
    public $first_name;
    public $last_name;
    public $email;
    // This is intended to be a bitfield
    // 0b1 for ADMIN
    // 0b10 for INSTRUCTOR
    // 0b100 for TA
    public $roles;
    public $password_digest;
    public $is_password_changed;
    public $created_at;
    public $updated_at;

    public function get_possible_roles()
    {
        $res = array();

        // Admins should have access to every role
        if ($this->get_role("admin")) {
            array_push($res, "admin");
            array_push($res, "instructor");
            array_push($res, "student");
            array_push($res, "ta");
        }

        if ($this->get_role("instructor")) {
            array_push($res, "instructor");
        }

        if (isset($this->student_id)) {
            array_push($res, "student");
        }

        if ($this->get_role("ta")) {
            array_push($res, "ta");
        }

        return $res;
    }

    public function get_full_name()
    {
        return $this->first_name." ".$this->last_name;
    }

    public function set_role(string $inp)
    {
        switch ($inp) {
            case "admin":
                $this->roles |= bindec('1');
                break;
            case "instructor":
                $this->roles |= bindec('10');
                break;
            case "ta":
                $this->roles |= bindec('100');
                break;
        }
    }

    public function unset_role(string $inp)
    {
        switch ($inp) {
            case "admin":
                $this->roles &= !bindec('1');
                break;
            case "instructor":
                $this->roles &= !bindec('10');
                break;
            case "ta":
                $this->roles &= !bindec('100');
                break;
        }
    }

    public function get_role(string $inp): bool
    {
        switch ($inp) {
            case "admin":
                return boolval($this->roles & bindec('1'));
            case "instructor":
                return boolval($this->roles & bindec('10'));
            case "ta":
                return boolval($this->roles & bindec('100'));
        }

        return false;

    }

    public function is_student(): bool
    {
        return !is_null($this->student_id);
    }
}
