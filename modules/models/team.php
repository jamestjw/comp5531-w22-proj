<?php

require_once(dirname(__FILE__)."/record.php");

class Team extends Record
{
    protected static $table_name = "teams";

    protected static $has_many = array(
        "team_member" => array(
            "class_name" => "TeamMember",
            "foreign_key" => "team_id",
        )
    );

    public $id;
    public $course_offering_id;
    public $created_at;
    public $updated_at;
}
