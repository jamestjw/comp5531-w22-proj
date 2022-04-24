<?php
// James Juan Whei Tan - 40161156
?>
<?php require_once(dirname(__FILE__)."/../../modules/ensure_logged_in.php"); ?>

<?php

require_once "../../modules/models/marked_entity.php";
require_once "../../common.php";

if (get_current_role() != "student") {
    die("You must be an instructor of this course to modify a marked entity file.");
}

if (!isset($_POST["marked_entity_file_id"])) {
    die("Invalid marked entity file.");
}

$id = intval($_POST["marked_entity_file_id"]);
if (is_null($marked_entity_file = MarkedEntityFile::find_by_id($id))) {
    die("Invalid Marked Entity file");
}

if (!$marked_entity_file->get_permission_for_user($_SESSION["current_user_id"], "delete")) {
    die("You do not have permission to delete this file");
}

if ($marked_entity_file->marked_entity->due_date_passed()) {
    die("Unable to update marked entity pass its due date.");
}

if (is_null($file = $marked_entity_file->attachment)) {
    die("Invalid file.");
}

$marked_entity_file_change = new MarkedEntityFileChange();
$marked_entity_file_change->user_id = $_SESSION["current_user_id"];
$marked_entity_file_change->entity_id = $marked_entity_file->entity_id;
$marked_entity_file_change->file_name = $file->file_filename;
$marked_entity_file_change->set_action("delete");

$file->delete("file_id");
$marked_entity_file->delete();
$marked_entity_file_change->save();

header("Location: ../marked_entity_files.php?marked_entity_id=$marked_entity_file->entity_id");
