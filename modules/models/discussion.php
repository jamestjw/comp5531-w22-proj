<?php
require_once (dirname(__FILE__)."/record.php");
require_once (dirname(__FILE__)."/discussion_message.php");

class Discussion extends Record {
    static protected $table_name = "discussions";

    public $id;
    public $user_id;
    public $title;
	public $created_at;
	public $updated_at;

    # TODO: Make this more convenient using metaprogramming
    public function discussion_messages() {
        return DiscussionMessage::where(array("discussion_id"=>$this->id));
    }
}