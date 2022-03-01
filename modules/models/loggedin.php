<?php

require_once(dirname(__FILE__)."/record.php");

class Loggedin extends Record
{
    protected static $table_name = "loggedin";

    public $id;
    public $user_digest;
    public $user_id;
    public $created_at;
    public $updated_at;
}
