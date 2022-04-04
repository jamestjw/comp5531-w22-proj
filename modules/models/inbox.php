<?php

require_once(dirname(__FILE__)."/record.php");

class Inbox extends Record
{
    protected static $table_name = "inbox";
    protected static $belongs_to = array(
        "user" => array(
            "class_name" => "User",
            "foreign_key" => "email",
        ),
        "emails" => array(
            "class_name" => "Email",
            "foreign_key" => "message_id"
        )
        );

    public $id;
    public $user_id;
    public $message_id;
    public $read;
    public $created_at;
    public $updated_at;
}
?>