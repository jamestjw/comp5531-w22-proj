<?php
// James Juan Whei Tan - 40161156
?>
<?php

require_once(dirname(__FILE__)."/record.php");

class Attachment extends Record
{
    protected static $table_name = "attachments";
    // TODO: Implement polymorphic belongs_to

    public $file_id;
    public $file_filename;
    public $file_size;
    public $attachable_id;
    public $attachable_type;
    public $created_at;
    public $updated_at;
}
