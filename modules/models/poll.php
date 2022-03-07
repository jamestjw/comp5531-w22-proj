<?php

require_once(dirname(__FILE__)."/record.php");

class Poll extends Record
{
    protected static $table_name = "polls";
    protected static $has_many = array(
        "poll_options" => array(
            "class_name" => "PollOption",
            "foreign_key" => "poll_id",
        )
    );
    // TODO: Polls could have a polymorphic parent
    protected static $belongs_to = array(
        "discussion_message" => array(
            "class_name" => "DiscussionMessage",
            "foreign_key" => "parent_id",
        )
    );

    public int $id;
    public int $parent_id;
    public int $user_id;
    public string $title;
    // TODO: We are using this to avoid the need 
    // for a date and time picker, improve this
    // if possible!
    public int $duration;
    public string $created_at;
    public string $updated_at;
}
