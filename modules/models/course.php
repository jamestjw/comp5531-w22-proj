<?php require_once (dirname(__FILE__)."/record.php");

class Course extends Record {

    static protected $table_name = "courses";
    

    public $id;
    public $course_code;
    public $course_name;
	public $created_at;
	public $updated_at;
}