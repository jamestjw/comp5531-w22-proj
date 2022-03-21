<?php require_once (dirname(__FILE__)."/record.php");

class CourseOfferingInstructor extends Record {

    static protected $table_name = "course_offerings_instructors";
    

    public $offering_id;
    public $user_id;
	public $created_at;
	public $updated_at;
}