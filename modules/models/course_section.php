<?php

require_once(dirname(__FILE__)."/record.php");

class CourseSection extends Record
{
    protected static $table_name = "course_sections";

    protected static $belongs_to = array(
        "course"=> array(
            "class_name" => "Course",
            "foreign_key" => "course_id"
            )
        );

    public $id;
    public $course_id;
    public $course_section_code;
    public $course_section_name;
    public $created_at;
    public $updated_at;
}
