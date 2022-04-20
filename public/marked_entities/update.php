<?php require_once(dirname(__FILE__)."/../../modules/ensure_logged_in.php"); ?>

<?php

require_once "../../modules/models/marked_entity.php";
require_once "../../common.php";

$UPDATABLE_FIELDS = ["title", "description", "due_at"];

if (get_current_role() != "instructor") {
    die("You must be an instructor of this course to create a marked entity.");
}

if (!isset($_POST["id"])) {
    die("Invalid marked entity.");
}

$id = intval($_POST["id"]);
if (is_null($marked_entity = MarkedEntity::find_by_id($id))) {
    die("Invalid MarkedEntity");
}

// Check if the user is indeed an instructor of this course
if (is_null(LectureInstructor::find_by(["lecture_id" => $marked_entity->lecture_id, "user_id"=> $_SESSION["current_user_id"]]))) {
    die("Course instructor does not teach this course.");
}

if ($marked_entity->due_date_passed()) {
    die("Unable to update marked entity past its due date.");
}

if (isset($_FILES['marked_entity_file']) && $_FILES['marked_entity_file']['size'] > 0) {
    // Uploads folder needs to be created in the public/ directory
    // TODO: Make this more convenient
    $target_dir = "../uploads/";
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

    $attachment->attachable_id = $marked_entity->id;
    $attachment->attachable_type = "MarkedEntity";
    $attachment->save();
}

foreach ($UPDATABLE_FIELDS as $field) {
    if (array_key_exists($field, $_POST)) {
        $marked_entity->$field = $_POST[$field];
    }
}

$marked_entity->save();

// // Go back to the previous page
header("Location:".$_SERVER['HTTP_REFERER']);
