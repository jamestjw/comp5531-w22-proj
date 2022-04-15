<?php require_once(dirname(__FILE__)."/../../modules/ensure_logged_in.php"); ?>

<?php

require_once "../../modules/models/marked_entity.php";
require_once "../../common.php";

$UPDATABLE_FIELDS = ["title", "description", "due_at"];

if (get_current_role() != "instructor") {
    die("You must be an instructor of this course to modify a marked entity.");
}

if (!isset($_POST["marked_entity_id"])) {
    die("Invalid marked entity.");
}

if (!isset($_POST["file_id"])) {
    die("Invalid file.");
}

$id = intval($_POST["marked_entity_id"]);
if (is_null($marked_entity = MarkedEntity::find_by_id($id))) {
    die("Invalid MarkedEntity");
}

// Check if the user is indeed an instructor of this course
if (is_null(CourseLectureInstructor::find_by(["lecture_id" => $marked_entity->lecture_id, "user_id"=> $_SESSION["current_user_id"]]))) {
    die("Course instructor does not teach this course.");
}

if ($marked_entity->due_date_passed()) {
    die("Unable to update marked entity pass its due date.");
}

if (is_null($file = Attachment::find_by(["attachable_id"=>$marked_entity->id, "attachable_type"=>"MarkedEntity", "id"=>$_POST["file_id"]]))) {
    die("Invalid file.");
}

$file->delete();

header("Location: ../marked_entity.php?id=$id");
