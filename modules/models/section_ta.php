<?php
// James Juan Whei Tan - 40161156
?>
<?php

require_once(dirname(__FILE__)."/record.php");

class SectionTA extends Record
{
    protected static $table_name = "section_tas";

    protected static $belongs_to = array(
        "user" => array(
            "class_name" => "User",
            "foreign_key" => "user_id",
        ),
        "section" => array(
            "class_name" => "Section",
            "foreign_key" => "section_id",
        )
    );

    public $user_id;
    public $section_id;
    public $created_at;
    public $updated_at;
}
