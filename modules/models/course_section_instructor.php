<?php require_once (dirname(__FILE__)."/record.php");

class CourseSectionInstructor extends Record {

    static protected $table_name = "course_section_instructors";
    
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

    public $section_id;
    public $user_id;
	public $created_at;
	public $updated_at;
}