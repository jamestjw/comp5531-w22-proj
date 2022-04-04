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
}
?>