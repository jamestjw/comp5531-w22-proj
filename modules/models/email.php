<?php

require_once(dirname(__FILE__)."/record.php");

class Email extends Record
{
    protected static $table_name = "emails";

    public $id;
    public $subject;
    public $content;
    public $created_at;
    public $updated_at;

    // Returns the email address of the person who sent this email
    public function get_sender() {
        $raw_sql = "
        SELECT email_address
        FROM sent
        WHERE message_id = {$this->id};
        ";

        $sql = getConnection()->prepare($raw_sql);
        $sql->execute();

        return $sql->fetch()[0]; // There should only ever be one sender
    }

}
?>