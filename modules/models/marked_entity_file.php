<?php

require_once(dirname(__FILE__)."/record.php");

class MarkedEntityFile extends Record
{
    protected static $table_name = "marked_entity_files";
    protected static $has_one = array(
        "attachment" => array(
            "class_name" => "Attachment",
            "as" => "attachable",
        )
    );
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
    protected static $has_many = array(
        "comments" => array(
            "class_name" => "Comment",
            "as" => "commentable",
        ),
        "permissions" => array(
            "class_name" => "MarkedEntityFilePermissions",
            "foreign_key" => "file_id",
        )
    );

    public $id;
    public $entity_id;
    public $user_id;
    public $title;
    public $description;
    public $created_at;
    public $updated_at;

    public function get_permission_for_user(int $user_id, string $permission): bool
    {
        $res = array_filter($this->permissions, fn ($p) => $p->user_id == $user_id);

        if (empty($res)) {
            // If the user id was not valid, just return false.
            return false;
        } else {
            return array_pop($res)->get_permission($permission);
        }
    }
}
