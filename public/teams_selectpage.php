<?php
require_once "../modules/models/user.php";
require_once "../common.php";
require_once "../modules/models/team.php";
require_once "../modules/models/team_member.php";
require_once "../modules/models/lecture.php";
require_once "../modules/models/section_student.php";
require_once "../modules/models/section.php";
?>

<?php include "templates/header.php"; ?>

<h2>My Teams</h2>

<?php

// Get the current user
$current_user = $_SESSION["current_user"];

// user->team_members->teams->lectures
// If the current user is an instructor, admin or ta, they should be able to 
// see all team in the relevant classes in read only (admin having modification privileges)

// Get current user role
$role = get_current_role();

// TODO: Once tas are added, handle team selection here
if ($role == "instructor" && !$current_user->is_admin) {
    $all_info = LectureInstructor::includes(["lecture" => "teams"])->where(array("user_id" => $current_user->id));
    print_r($all_info);
    $lecture_teams = array();
} elseif ($role == "student" && !$current_user->is_admin) {
    $all_info = SectionStudent::includes(["section" => ["lecture" => "teams"]])->where(array("user_id" => $current_user->id));
} elseif ($role == "admin" || $current_user->is_admin) {
    $all_info = Lecture::includes("teams")->getAll();
    $lecture_teams = $all_info;

}


?>


    <h2></h2>


<?php include "templates/footer.php"; ?>