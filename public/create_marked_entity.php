<?php

require_once "../modules/models/marked_entity.php";
require_once "../common.php";

ensure_logged_in();

if (get_current_role() != "instructor") {
    die("You must be an instructor of this course to create a marked entity.");
}

if (!isset($_POST["lecture_id"])) {
    die("Invalid course lecture.");
}

$lecture_id = intval($_POST["lecture_id"]);
// TODO: Check if the user is indeed an instructor of this course
// when instructors of courses are assigned.
if (is_null(Lecture::find_by_id($lecture_id))) {
    die("Invalid course lecture.");
}

$marked_entity = new MarkedEntity();
$marked_entity->title = $_POST["title"];
$marked_entity->description = $_POST["description"];
$marked_entity->is_team_work = $_POST["is_team_work"];
// TODO: Fix this when courses are fully implemented
$marked_entity->lecture_id = $lecture_id;
$marked_entity->due_at = $_POST["due_at"];

if (isset($_FILES['marked_entity_file']) && $_FILES['marked_entity_file']['size'] > 0) {
    // Uploads folder needs to be created in the public/ directory
    // TODO: Make this more convenient
    $target_dir = "uploads/";
    // TODO: Improve the file ID
    $file_id = uniqid().basename($_FILES["marked_entity_file"]["name"]);
    $target_file = $target_dir . $file_id;

    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $file_size = $_FILES["marked_entity_file"]["size"];

    $attachment = new Attachment();
    $attachment->file_size = $_FILES["marked_entity_file"]["size"];
    $attachment->file_filename = basename($_FILES["marked_entity_file"]["name"]);
    $attachment->file_id = $file_id;

    if (!move_uploaded_file($_FILES["marked_entity_file"]["tmp_name"], $target_file)) {
        echo "Sorry, there was an error uploading your file.";
    }

    $marked_entity->files = array($attachment);
}

try {
    $marked_entity->save();

    header("Location: marked_entity.php?id={$marked_entity->id}");
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}
?>  