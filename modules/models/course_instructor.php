<?php
// Andréanne Chartrand-Beaudry - 29605991
?>
<?php require_once (dirname(__FILE__)."/record.php");

class CourseInstructor extends Record {

    static protected $table_name = "course_instructors";
    
    protected static $belongs_to = array(
        "user" => array(
            "class_name" => "User",
            "foreign_key" => "user_id",
        ),
        "courses" => array(
            "class_name" => "Courses",
            "foreign_key" => "course_id",
        )
    );

    public $course_id;
    public $user_id;
    public $created_at;
    public $updated_at;
}