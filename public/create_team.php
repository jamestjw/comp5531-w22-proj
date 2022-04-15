<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>
<?php include "templates/header.php"; ?>

<?php if(get_current_role() == 'instructor') {

require_once "../modules/models/user.php";
require_once "../modules/models/section.php";
require_once "../modules/models/section_student.php";
require_once "../common.php";

$lecture_id = $_GET['id'];

try {
    $course_sections = Section::includes('section_students')->where(array('lecture_id' => $lecture_id));

} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

$course_students = array();

foreach ($course_sections as $section) {
    try {
        $course_section_student = $section->section_students;
        foreach ($course_section_student as $student){
            array_push($course_students, $student->user);
        }
        
    } catch (PDOException $error) {
        echo "<br>" . $error->getMessage();
    }
}

    if (isset($_POST["submit"])) {


        $team = new Team();
        $team->lecture_id = $lecture_id;

        try {
            $team->save();
        } catch (PDOException $error) {
            echo "<br>" . $error->getMessage();
        }
        
        $student_in_team = $_POST['student'];

        foreach($student_in_team as $stu){
            $team_member = new TeamMember();
            $team_member->team_id = $team->id;
            $team_member->user_id = intval($stu);

            try {
                $team_member->save();
            } catch (PDOException $error) {
                echo "<br>" . $error->getMessage();
            }
        }

        echo "<h5>New team created </h5>";
        
    }

?>

<h2>Create Team</h2>
<p>Select 1 to 4 students</p>
<div class="student_choice">
    <form method="post">
        <?php foreach ($course_students as $student) { ?>
            <input class="single-checkbox" type="checkbox" id="<?php echo $student->id; ?>" name="student[]" value="<?php echo $student->id; ?>"> <?php echo ($student->first_name." ".$student->last_name." ".$student->student_id) ?><br>
        <?php } ?>
        <input type='submit' name='submit' value='submit'>
    </form>
</div>
<?php }
?>

<script type="text/JavaScript">
var theCheckboxes = $(".student_choice input[type='checkbox']");
theCheckboxes.click(function()
{
    if (theCheckboxes.filter(":checked").length > 4)
        $(this).removeAttr("checked");
});
</script>




