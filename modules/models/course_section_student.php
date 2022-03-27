<?php

require_once(dirname(__FILE__)."/record.php");

class CourseSectionStudent extends Record
{
    protected static $table_name = "course_section_students";

    protected static $belongs_to = array(
        "user" => array(
            "class_name" => "User",
            "foreign_key" => "user_id",
        ),
        "course_section" => array(
            "class_name" => "CourseSection",
            "foreign_key" => "section_id",
        )
        );

    public $user_id;
    public $section_id;
    public $created_at;
    public $updated_at;
}