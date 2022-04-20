<?php

require_once(dirname(__FILE__)."/record.php");

class Lecture extends Record
{
    protected static $table_name = "lectures";

    protected static $belongs_to = array(
        "course" => array(
            "class_name" => "Course",
            "foreign_key" => "course_id"
            )
        );

    protected static $has_many = array(
        "teams" => array(
            "class_name" => "Team",
            "foreign_key" => "lecture_id"
        ),
        "marked_entities" => array(
            "class_name" => "MarkedEntity",
            "foreign_key" => "lecture_id"
        )
        );

    public $id;
    public $course_id;
    public $lecture_code;
    public $starting_date;
    public $ending_date;
    public $created_at;
    public $updated_at;
}
