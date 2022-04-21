<?php require_once(dirname(__FILE__)."/../../modules/ensure_logged_in.php"); ?>

<?php

require_once "../../modules/models/section_ta.php";
require_once "../../modules/models/section.php";
require_once "../../common.php";

$required_attrs = ["section_id", "first_name", "last_name", "password", "email"];

foreach ($required_attrs as $attr) {
    if (!array_key_exists($attr, $_POST)) {
        set_error_and_go_back("Invalid $attr");
    }
}

if (is_null($section = Section::find_by_id($_POST["section_id"]))) {
    set_error_and_go_back("Invalid section");
}

if (get_current_role() != "instructor" || is_null(LectureInstructor::find_by(["lecture_id" => $section->lecture_id, "user_id"=> $_SESSION["current_user_id"]]))) {
    set_error_and_go_back("You must be an instructor of this course to assign TAs.");
}

// Check that the section has no TAs
if (!is_null(SectionTA::find_by(["section_id" => $_POST["section_id"]]))) {
    set_error_and_go_back("This section already has a TA.");
}

$user = new User();
$user->first_name = $_POST['first_name'];
$user->last_name = $_POST['last_name'];
$user->email = $_POST['email'];
$user->is_admin = 0;
$user->is_instructor = 0;
$user->is_ta = 1;
$user->is_password_changed = 0;
$user->password_digest = password_hash($_POST['password'], PASSWORD_DEFAULT);
$user->save();

$section_ta = new SectionTA();
$section_ta->user_id = $user->id;
$section_ta->section_id = $_POST["section_id"];
$section_ta->save();

header("Location: ../course_lecture.php?id={$section->lecture_id}");
