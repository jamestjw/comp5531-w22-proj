<?php

<<<<<<< HEAD:modules/models/course_offering_instructor.php
require_once(dirname(__FILE__)."/record.php");

class CourseOfferingInstructor extends Record
{
    protected static $table_name = "course_offerings_instructors";

=======
class LectureInstructor extends Record {

    static protected $table_name = "lecture_instructors";
    
>>>>>>> 51c4ab4 (Initial Commit of first rename changes.):modules/models/lecture_instructor.php
    protected static $belongs_to = array(
        "user" => array(
            "class_name" => "User",
            "foreign_key" => "user_id",
        ),
        "lecture" => array(
            "class_name" => "Lecture",
            "foreign_key" => "lecture_id",
        )
    );

    public $lecture_id;
    public $user_id;
    public $created_at;
    public $updated_at;
}
