<?php

require_once(dirname(__FILE__)."/record.php");

class PollOptionUser extends Record
{
    protected static $table_name = "poll_option_users";
    protected static $belongs_to = array(
        "poll_option" => array(
            "class_name" => "PollOption",
            "foreign_key" => "option_id",
        ),
        "user" => array(
            "class_name" => "User",
            "foreign_key" => "user_id",
        )
    );

    // TODO: Verify that it's ok to omit the ID
    public int $option_id;
    public int $user_id;
    public string $created_at;
    public string $updated_at;
}
