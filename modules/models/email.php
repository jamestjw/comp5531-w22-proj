<?php
// Christopher Almeida Neves - 27521979
?>
<?php

require_once(dirname(__FILE__)."/record.php");

class Email extends Record
{
    protected static $table_name = "emails";

    public $id;
    public $subject;
    public $content;
    public $created_at;
    public $updated_at;

    static protected $has_one = array(
       "sender" => array(
           "class_name" => "Sent",
           "foreign_key" => "message_id"
       ) 
       );

    static protected $has_many = array(
        "receiver" => array(
            "class_name" => "Inbox",
            "foreign_key" => "message_id"
        )
        );
}
