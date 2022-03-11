<?php

require_once(dirname(__FILE__)."/record.php");

class PollOption extends Record
{
    protected static $table_name = "poll_options";
    protected static $belongs_to = array(
        "poll" => array(
            "class_name" => "Poll",
            "foreign_key" => "poll_id",
        )
    );

    public int $id;
    public int $poll_id;
    public string $content;
    public string $created_at;
    public string $updated_at;
}
