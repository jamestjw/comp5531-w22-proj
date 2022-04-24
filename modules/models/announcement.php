<?php
// Andréanne Chartrand-Beaudry - 29605991
// James Juan Whei Tan - 40161156
?>
<?php

require_once(dirname(__FILE__)."/record.php");

class Announcement extends Record
{
    protected static $table_name = "announcements";

    public $id;
    public $announcement_text;
    public $created_at;
    public $updated_at;
}
