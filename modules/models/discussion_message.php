<?php

require_once(dirname(__FILE__)."/record.php");

class DiscussionMessage extends Record
{
    protected static $table_name = "discussion_messages";

    public $id;
    public $user_id;
    public $discussion_id;
    public $content;
    public $parent_id;
    public $created_at;
    public $updated_at;
}
