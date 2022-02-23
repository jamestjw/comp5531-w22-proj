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
        $table_name = DiscussionMessage::get_table_name();
        $sql = sprintf("SELECT * FROM %s WHERE %s = %s;", $table_name, 'discussion_id', $this->id);


        $statement = getConnection()->prepare($sql);
        $statement->execute();
        $res = $statement->fetchAll();

        return array_map(['DiscussionMessage', 'loadRecordFromData'], $res);
    }
}