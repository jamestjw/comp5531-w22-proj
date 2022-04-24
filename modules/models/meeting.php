<?php
// Christopher Almeida Neves - 27521979
// Andréanne Chartrand-Beaudry - 29605991
// James Juan Whei Tan - 40161156
?>
<?php

require_once(dirname(__FILE__)."/record.php");

class Meeting extends Record
{
    protected static $table_name = "meetings";

    public $id;
    public $team_id;
    public $user_id;
    public $title;
    public $agenda;
    public $minutes;
    public $planned_date;
    public $planned_time;
    public $has_passed;
    public $start_at;
    public $end_at;
    public $created_at;
    public $updated_at;
}
