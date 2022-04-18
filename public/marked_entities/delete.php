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
    die("Unable to update marked entity pass its due date.");
}

$marked_entity->delete();

header("Location: ../marked_entities.php");
