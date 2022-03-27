<?php

require_once(dirname(__FILE__)."/record.php");

class CourseSectionStudent extends Record
{
    protected static $table_name = "course_section_students";

    public $user_id;
    public $section_id;
    public $created_at;
    public $updated_at;
}