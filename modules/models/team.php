<?php
// Andréanne Chartrand-Beaudry - 29605991
// James Juan Whei Tan - 40161156
// Christopher Almeida Neves - 27521979
?>
<?php

require_once(dirname(__FILE__)."/record.php");

class Team extends Record
{
    protected static $table_name = "teams";

    protected static $has_many = array(
        'team_members' => array(
            "class_name" => "TeamMember",
            "foreign_key" => "team_id",
        )
    );

    protected static $belongs_to = array(
        "lectures" => array(
            "class_name" => "Lecture",
            "foreign_key" => "lecture_id"
        )
    );

    public $id;
    public $lecture_id;
    public $created_at;
    public $updated_at;
}
