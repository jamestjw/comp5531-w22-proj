<?php
// Zachary Jones - 40203969
?>
<?php require_once (dirname(__FILE__)."/record.php");

class LectureInstructor extends Record {

    static protected $table_name = "lecture_instructors";
    
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
