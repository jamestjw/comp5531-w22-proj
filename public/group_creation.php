<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>
<?php include "templates/header.php"; ?>

<?php if(get_current_role() == 'instructor') {

require_once "../modules/models/user.php";
require_once "../modules/models/course_section.php";
require_once "../modules/models/course_section_student.php";
require_once "../common.php";

$course_id = $_GET['id'];

try {
    $course_sections = CourseSection::where(array('offering_id' => $course_id));
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

$course_sections_students = array();

foreach ($course_sections as $section) {
    try {
        array_push($course_sections_students, CourseSectionStudent::includes("user")->where(array('section_id' => $section->id)));
    } catch (PDOException $error) {
        echo "<br>" . $error->getMessage();
    }
}
}
?>

<h2>Create Group</h2>
<br>
<p>Select 1 to 4 students</p>

