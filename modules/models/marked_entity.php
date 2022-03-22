<?php

require_once(dirname(__FILE__)."/record.php");

class MarkedEntity extends Record
{
    protected static $table_name = "marked_entities";
    protected static $has_many = array(
        // Files that the instructor adds to the marked entity
        "files" => array(
            "class_name" => "Attachment",
            "foreign_key" => "attachable_id",
        ),
        "discussions" => array(
            "class_name" => "Discussion",
            "as" => "discussable",
        )
    );

    public int $id;
    public string $title;
    public string $description;
    public int $course_offering_id;
    public bool $is_group_work;
    public string $due_at;
    public string $created_at;
    public string $updated_at;
}
