<?php

require_once(dirname(__FILE__)."/record.php");

class TeamMember extends Record
{
    protected static $table_name = "team_members";

    protected static $belongs_to = array(
        "user" => array(
            "class_name" => "User",
            "foreign_key" => "user_id",
        ),
        "teams"=> array(
            "class_name" => "Team",
            "foreign_key" => "team_id"
            )
        );

    public $team_id;
    public $user_id;
    public $created_at;
    public $updated_at;
}
