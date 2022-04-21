<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>

<?php
require_once "../modules/models/user.php";
require_once "../modules/models/section.php";
require_once "../modules/models/section_student.php";
require_once "../common.php";

$lecture_page_id = $_GET['id'];

try {
    $course_lecture = Lecture::includes("course")->where(array('id' => $lecture_page_id));
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}


try {
    $course_section_students = SectionStudent::includes(["user", "section"])->getAll();
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

try {
    $marked_entities = MarkedEntity::where(array("lecture_id" => $lecture_page_id));
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

try {
    $course_sections = Section::includes(["section_students"])->where(array('lecture_id' => $lecture_page_id));
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

try {
    $teams = Team::includes(['team_members'])->where(array('lecture_id' => $lecture_page_id));
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

try {
    $lecture_instructor = LectureInstructor::includes(['user'])->find_by(array('lecture_id' => $lecture_page_id));
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

?>

<?php include "templates/header.php"; ?>

<link rel="stylesheet" href="css/course_lecture.css">

<div class="menubar">
    <label for="menuform" class="menu_form_label"><?php echo $course_lecture[0]->course->course_name." ".$course_lecture[0]->lecture_code?></label>
    <form name="menuform" method="post" class="menu_form">
        <button name="students" class="menu_item">Students</button>
        <button name="team" class="menu_item"><?php if(get_current_role() == 'student') { echo "My Team";} else { echo "Teams";} ?></button>
        <button name="marked_entities" class="menu_item">Marked Entities</button>
        <button name="discussion" class="menu_item">Lecture Discussion</button>
        <?php if (get_current_role() == 'instructor' || get_current_role() == 'admin') { ?>
            <button name="tas" class="menu_item">TAs</button>
        <?php } ?>
    </form>
</div>

   
<div class="lecturedisplay">
    <?php
    // Set the default view of the course page to ??
    if (!isset($_SESSION["course_view"])) {
        $_SESSION["course_view"] = "students";
    }

    if(isset($_GET["view"])){
        $_SESSION["course_view"] = "team";

    }

    // Set the course view depending on which menu item is pressed
    if (isset($_POST["students"])) {
        $_SESSION["course_view"] = "students";
    } elseif (isset($_POST["team"])) {
        $_SESSION["course_view"] = "team";
    } elseif (isset($_POST["marked_entities"])) {
        $_SESSION["course_view"] = "marked_entities";
    } elseif (isset($_POST["discussion"])) {
        $_SESSION["course_view"] = "discussion";
    } elseif (isset($_POST["tas"])) {
        $_SESSION["course_view"] = "tas";
    }
    // Modify contents of page depending on which button was pressed
    if ($_SESSION["course_view"] == "students") {
        include "view_lecture_Student_list.php";
    } elseif ($_SESSION["course_view"] == "team") {
        include "view_lecture_teams.php";
    } elseif ($_SESSION["course_view"] == "marked_entities") {
        include "view_lecture_marked_entities.php";
    } elseif ($_SESSION["course_view"] == "discussion") {
        include "view_lecture_discussions.php";
    } elseif ($_SESSION["course_view"] == "tas") {
        include "view_lecture_tas.php";
    }
    ?>
</div>