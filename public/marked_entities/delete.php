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
// TODO: Check if the user is indeed an instructor of this course
// when instructors of courses are assigned.
if (is_null($marked_entity = MarkedEntity::find_by_id($id))) {
    die("Invalid MarkedEntity");
}

$marked_entity->delete();

header("Location: ../marked_entities.php");
