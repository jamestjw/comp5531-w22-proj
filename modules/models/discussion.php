<?php

require_once(dirname(__FILE__)."/record.php");

class Discussion extends Record
{
    protected static $table_name = "discussions";
    protected static $has_many = array(
        "discussion_messages" => array(
            "class_name" => "DiscussionMessage",
            "foreign_key" => "discussion_id",
        )
    );
    protected static $belongs_to = array(
        "user" => array(
            "class_name" => "User",
            "foreign_key" => "user_id",
        ),
        "discussable" => array(
            "polymorphic" => true,
            "foreign_key" => "discussable_id",
        )
    );

    public $id;
    public $user_id;
    public $title;
    public $discussable_id;
    public $discussable_type;
    public $created_at;
    public $updated_at;
}
