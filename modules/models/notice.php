<?php

require_once(dirname(__FILE__)."/record.php");

class Notice extends Record
{
    protected static $table_name = "notices";

    public $id;
    public $notice_text;
    public $created_at;
    public $updated_at;
}
?>