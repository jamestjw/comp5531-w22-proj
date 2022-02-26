<?php
require_once (dirname(__FILE__)."/record.php");
require_once (dirname(__FILE__)."/discussion_message.php");

class Discussion extends Record {
    static protected $table_name = "discussions";
    static protected $has_many = array(
        "discussion_messages" => array(
            "class_name" => "DiscussionMessage",
            "foreign_key" => "discussion_id",
        )
    );
    static protected $belongs_to = array(
        "user" => array(
            "class_name" => "User",
            "foreign_key" => "user_id",
        )
    );

    public $id;
    public $user_id;
    public $title;
	public $created_at;
	public $updated_at;
}