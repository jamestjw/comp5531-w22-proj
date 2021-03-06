<?php
// James Juan Whei Tan - 40161156
// Christopher Almeida Neves - 27521979
?>
<?php

require_once(dirname(__FILE__)."/record.php");

class DiscussionMessage extends Record
{
    protected static $table_name = "discussion_messages";

    protected static $belongs_to = array(
        "user" => array(
            "class_name" => "User",
            "foreign_key" => "user_id",
        )
    );
    protected static $has_one = array(
        "poll" => array(
            "class_name" => "Poll",
            "foreign_key" => "parent_id",
        )
    );
    protected static $has_many = array(
        "comments" => array(
            "class_name" => "Comment",
            "as" => "commentable",
        )
    );

    public $id;
    public $user_id;
    public $discussion_id;
    public $content;
    public $parent_id;
    public $created_at;
    public $updated_at;
}
