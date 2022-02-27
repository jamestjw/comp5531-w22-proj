<?php
require_once (dirname(__FILE__)."/record.php");

class Attachment extends Record {
    static protected $table_name = "attachments";
    // TODO: Implement polymorphic belongs_to

    public $id;
    public $file_id;
    public $file_content_type;
    public $file_filename;
    public $file_size;
    public $attachable_id;
    public $attachable_type;
	public $created_at;
	public $updated_at;
}