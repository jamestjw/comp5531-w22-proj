<?php
// James Juan Whei Tan - 40161156
?>
<?php

require_once(dirname(__FILE__)."/record.php");

class Comment extends Record
{
    protected static $table_name = "comments";

    protected static $belongs_to = array(
        "commentable" => array(
            "polymorphic" => true,
            "foreign_key" => "commentable_id",
        ),
        "user" => array(
            "class_name" => "User",
            "foreign_key" => "user_id",
        ),
    );

    public int $id;
    public int $user_id;
    public string $content;
    public int $commentable_id;
    public string $commentable_type;
    public string $created_at;
    public string $updated_at;
}
