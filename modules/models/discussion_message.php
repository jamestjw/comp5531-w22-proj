<?php
require_once (dirname(__FILE__)."/record.php");

class DiscussionMessage extends Record {
    static protected $table_name = "discussion_messages";

    static protected $belongs_to = array(
        "user" => array(
            "class_name" => "User",
            "foreign_key" => "user_id",
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