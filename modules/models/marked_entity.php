<?php
// Christopher Almeida Neves - 27521979
// James Juan Whei Tan - 40161156
// Zachary Jones - 40203969
?>
<?php

require_once(dirname(__FILE__)."/record.php");

class MarkedEntity extends Record
{
    protected static $table_name = "marked_entities";
    protected static $has_many = array(
        // Files that the instructor adds to the marked entity
        "files" => array(
            "class_name" => "Attachment",
            "as" => "attachable",
        ),
        "discussions" => array(
            "class_name" => "Discussion",
            "as" => "discussable",
        ),
        "student_files" => array(
            "class_name" => "MarkedEntityFile",
            "foreign_key" => "entity_id",
        )
    );

    protected static $belongs_to = array(
        "lecture" => array(
            "class_name" => "lectures",
            "foreign_key" => "lecture_id"
        )
    );

    public int $id;
    public string $title;
    public string $description;
    public int $lecture_id;
    public bool $is_team_work;
    public string $due_at;
    public string $created_at;
    public string $updated_at;

    public function due_date_passed(): bool
    {
        return new DateTime() > new DateTime($this->due_at);
    }
}
