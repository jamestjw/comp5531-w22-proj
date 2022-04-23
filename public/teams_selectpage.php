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

if ($role == "admin" || $current_user->get_role("admin")) {
    $lectures = Lecture::includes(["teams", "course"])->getAll();
} else if ($role == "instructor") {
    $lectures = Lecture::joins_raw_sql("
        JOIN lecture_instructors li on
        li.lecture_id = lectures.id
    ")->includes(["teams", "course"])->where(array("user_id" => $current_user->id));
} elseif ($role == "student") {
    $lectures = Lecture::joins_raw_sql("
        JOIN sections s on
        s.lecture_id = lectures.id
        JOIN section_students ss on
        ss.section_id = s.id
    ")->includes(["teams" => "team_members", "course" => []])->where(array("user_id" => $current_user->id));
} elseif ($role == "ta") {
    $lectures = Lecture::joins_raw_sql("
        JOIN sections s on
        s.lecture_id = lectures.id
        JOIN section_tas st on
        st.section_id = s.id
    ")->includes(["teams" => "team_members", "course" => []])->where(array("user_id" => $current_user->id));
} else {
    // This clause should never be reached, however if we add new roles
    // then we would need to remember to handle it here!
    // $lectures = [] would just make this fail silently
    die("This should never happen!");
}
?>

<?php
if($role != "student"){ 
    foreach ($lectures as $lecture) {
        $header = $lecture->course->course_code." ".$lecture->lecture_code;
        echo "<h3>{$header}</h3>";
        ?>
        <ul>
            <?php
            foreach ($lecture->teams as $team) {
                echo "<li><a href='course_lecture.php?id={$lecture->id}&view={$team->id}'>Team Number {$team->id}</a></li>";
            }
            ?>
        </ul>
    <?php } 
} else {
    foreach ($lectures as $lecture) {
        $header = $lecture->course->course_code." ".$lecture->lecture_code;
        echo "<h3>{$header}</h3>";
        ?>
        <ul>
            <?php
            foreach ($lecture->teams as $team) {
                if(in_array($current_user->id, array_column($team->team_members, 'user_id'))){
                    echo "<li><a href='course_lecture.php?id={$lecture->id}&view={$team->id}'>Team Number {$team->id}</a></li>";
                }
                
            }
            ?>
       </ul>
    <?php } 
}?>


<?php include "templates/footer.php"; ?>