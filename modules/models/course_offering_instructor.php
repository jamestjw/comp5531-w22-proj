<?php require_once (dirname(__FILE__)."/record.php");

class CourseOfferingInstructor extends Record {

    static protected $table_name = "course_offerings_instructors";
    
    protected static $belongs_to = array(
        "user" => array(
            "class_name" => "User",
            "foreign_key" => "user_id",
        ),
        "course_offering" => array(
            "class_name" => "CourseOffering",
            "foreign_key" => "offering_id",
        )
    );

    public $offering_id;
    public $user_id;
	public $created_at;
	public $updated_at;
}