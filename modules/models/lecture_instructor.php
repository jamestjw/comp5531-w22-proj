<<<<<<< HEAD
<?php
=======
<?php require_once (dirname(__FILE__)."/record.php");
>>>>>>> 1bf3a476e40cc49cfcd35835f256df59cb84d2e0

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
<<<<<<< HEAD
    public $created_at;
    public $updated_at;
}
=======
	public $created_at;
	public $updated_at;
}
>>>>>>> 1bf3a476e40cc49cfcd35835f256df59cb84d2e0
