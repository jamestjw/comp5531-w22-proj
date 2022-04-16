<?php

require_once(dirname(__FILE__)."/record.php");

class MarkedEntityFileChange extends Record
{
    protected static $table_name = "marked_entity_file_changes";
    protected static $belongs_to = array(
        "marked_entity" => array(
            "class_name" => "MarkedEntity",
            "foreign_key" => "entity_id",
        ),
        "user" => array(
            "class_name" => "User",
            "foreign_key" => "user_id",
        )
    );

    public int $id;
    public int $entity_id;
    public int $user_id;
    public int $action;
    public string $file_name;
    public string $created_at;
    public string $updated_at;

    public function get_action(): string
    {
        switch ($this->action) {
            case 0:
                return "create";
            case 1:
                return "update";
            case 2:
                return "delete";
        }
        return null;
    }

    public function set_action(string $inp)
    {
        switch ($inp) {
            case "create":
                $this->action = 0;
                break;
            case "update":
                $this->action = 1;
                break;
            case "delete":
                $this->action = 2;
                break;
        }
    }
}
