<?php

require_once(dirname(__FILE__)."/record.php");

class CourseSection extends Record
{
    protected static $table_name = "course_sections";

    protected static $belongs_to = array(
        "course_offering"=> array(
            "class_name" => "CourseOffering",
            "foreign_key" => "offering_id"
            )
        );

    public $id;
    public $offering_id;
    public $course_section_code;
    public $course_section_name;
    public $created_at;
    public $updated_at;
}
