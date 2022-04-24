<?php
// James Juan Whei Tan - 40161156
?>
<?php

require_once "../modules/models/attachment.php";
require_once "../common.php";

ensure_logged_in();

if (isset($_GET["file_id"]) && ($attachment = Attachment::find_by_file_id($_GET["file_id"]))) {
    header('Content-Disposition: attachment; filename=' . basename($attachment->file_filename));
    readfile("./uploads/".$attachment->file_id);
} else {
    echo "Invalid file ID.";
}
