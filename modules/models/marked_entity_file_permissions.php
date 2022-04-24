<?php
// James Juan Whei Tan - 40161156
// Christopher Almeida Neves - 27521979
?>
<?php

require_once(dirname(__FILE__)."/record.php");

class MarkedEntityFilePermissions extends Record
{
    protected static $table_name = "marked_entity_file_permissions";
    protected static $belongs_to = array(
        "marked_entity_file" => array(
            "class_name" => "MarkedEntityFile",
            "foreign_key" => "file_id",
        ),
        "user" => array(
            "class_name" => "User",
            "foreign_key" => "user_id",
        )
    );

    public int $id;
    // This is intended to be a bitfield
    // 0b1 for READ
    // 0b10 for WRITE
    // 0b100 for DELETE
    public int $permissions = 0;
    public int $user_id;
    public int $file_id;
    public string $created_at;
    public string $updated_at;

    public function set_permission(string $inp)
    {
        switch ($inp) {
            case "read":
                $this->permissions |= bindec('1');
                break;
            case "write":
                $this->permissions |= bindec('10');
                break;
            case "delete":
                $this->permissions |= bindec('100');
                break;
        }
    }

    public function unset_permission(string $inp)
    {
        switch ($inp) {
            case "read":
                $this->permissions &= !bindec('1');
                break;
            case "write":
                $this->permissions &= !bindec('10');
                break;
            case "delete":
                $this->permissions &= !bindec('100');
                break;
        }
    }

    public function get_permission(string $inp): bool

    {
        switch ($inp) {
            case "read":
                return boolval($this->permissions & bindec('1'));
            case "write":
                return boolval($this->permissions & bindec('10'));
            case "delete":
                return boolval($this->permissions & bindec('100'));
        }

        return false;

    }

    public function stringify(): string
    {
        if ($this->permissions == 0) {
            return "No access";
        } else {
            $res = array();
            if ($this->get_permission("read")) {
                array_push($res, "Read");
            }
            if ($this->get_permission("write")) {
                array_push($res, "Write");
            }
            if ($this->get_permission("delete")) {
                array_push($res, "Delete");
            }
            return implode(", ", $res);
        }
    }
}
