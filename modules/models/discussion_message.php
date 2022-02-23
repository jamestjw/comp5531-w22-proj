<?php
require_once (dirname(__FILE__)."/record.php");

class DiscussionMessage extends Record {
    static protected $table_name = "discussion_messages";

    public $id;
    public $user_id;
    public $discussion_id;
    public $content;
	public $created_at;
	public $updated_at;
}