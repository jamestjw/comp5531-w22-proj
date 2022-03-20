<?php require_once (dirname(__FILE__)."/record.php");

class CourseOffering extends Record {

    static protected $table_name = "course_offerings";

    static protected  $belongs_to = array(
        "course"=> array(
            "class_name" => "Course",
            "foreign_key" => "course_id"
            )
        );

    public $id;
    public $course_id;
    public $course_offering_code;
    public $course_offering_name;
	public $created_at;
	public $updated_at;

}