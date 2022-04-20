<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>
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

// Get current user role
$role = get_current_role();

// TODO: Once tas are added, handle team selection here
if ($role == "instructor" && !$current_user->is_admin) {
    $lectures = Lecture::joins_raw_sql("
        JOIN lecture_instructors li on
        li.lecture_id = lectures.id
    ")->includes(["teams", "course"])->where(array("user_id" => $current_user->id));
} elseif ($role == "student" && !$current_user->is_admin) {
    $lectures = Lecture::joins_raw_sql("
        JOIN sections s on
        s.lecture_id = lectures.id
        JOIN section_students ss on
        ss.section_id = s.id
    ")->includes(["teams", "course"])->where(array("user_id" => $current_user->id));
} elseif ($role == "admin" || $current_user->is_admin) {
    $lectures = Lecture::includes(["teams", "course"])->getAll();
}
?>

<?php
foreach ($lectures as $lecture) {
    $header = $lecture->course->course_code." ".$lecture->lecture_code;
    echo "<h3>{$header}</h3>";
    ?>
    <ul>
        <?php
        foreach ($lecture->teams as $team) {
            echo "<li><a href='team_page.php?id={$team->id}'>Team Number {$team->id}</a></li>";
        }
        ?>
    </ul>
<?php } ?>

<?php include "templates/footer.php"; ?>