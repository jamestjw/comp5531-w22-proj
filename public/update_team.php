<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>
<?php include "templates/header.php"; ?>

<?php 
$lecture_id = $_GET['id'];

if(get_current_role() == 'instructor') {

require_once "../modules/models/user.php";
require_once "../modules/models/section.php";
require_once "../modules/models/section_student.php";
require_once "../common.php";

try {
    $teams = Team::includes('team_member')->where(array('lecture_id' => $lecture_id));

} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

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
}?>

<h2>Teams</h2>
    <?php if ($teams && count($teams)) { ?>
    
        <?php foreach ($teams as $row) { ?>
           <h3><?php echo "Team # ".$row->id ?></h3>
           <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email Address</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                <?php $students_in_team = $row->team_member; 
                foreach ($students_in_team as $student) {?>
                <tr>
                    <td><?php echo escape($student->user_id); ?></td>
                    <td><?php echo escape($student->user->first_name); ?></td>
                    <td><?php echo escape($student->user->last_name); ?></td>
                    <td><?php echo escape($student->user->email); ?></td>
                    <td><?php echo escape($student->user->created_at);  ?> </td>
                </tr>
                <?php }?>
                </tbody>
                </table>

        <?php } ?>
        </tbody>
    </table>
    <?php } else { ?>
        <blockquote>No teams found for this course.</blockquote>
    <?php }?>

<?php } else { ?>
    <h2>You do not have the credentials to view this page.</h2>
<?php } ?>
