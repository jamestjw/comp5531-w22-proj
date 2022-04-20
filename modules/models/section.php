<?php

require_once(dirname(__FILE__)."/record.php");

class Section extends Record
{
    protected static $table_name = "sections";

    protected static $belongs_to = array(
        "lecture"=> array(
            "class_name" => "Lecture",
            "foreign_key" => "lecture_id"
        )
    );
    protected static $has_many = array(
        "section_students"=> array(
            "class_name" => "SectionStudent",
            "foreign_key" => "section_id"
        )
    );
    protected static $has_one = array(
        "section_ta"=> array(
            "class_name" => "SectionTA",
            "foreign_key" => "section_id"
        )
    );

    public $id;
    public $lecture_id;
    public $section_code;
    public $created_at;
    public $updated_at;
}
