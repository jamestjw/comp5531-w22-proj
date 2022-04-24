<?php
// James Juan Whei Tan - 40161156
// Zachary Jones - 40203969
?>
<?php

require_once(dirname(__FILE__)."/record.php");

class Course extends Record
{
    protected static $table_name = "courses";


    public $id;
    public $course_code;
    public $course_name;
    public $created_at;
    public $updated_at;
}
