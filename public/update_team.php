<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>
<?php include "templates/header.php"; ?>

<?php 
$lecture_id = $_GET['id'];

try {
    $lecture_instructor = LectureInstructor::find_by(array('lecture_id' => $lecture_id));

} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

if(get_current_role() == 'instructor' && get_users_id() == $lecture_instructor->user_id) {

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


$students_in_teams = array();

foreach($teams as $t) {
    foreach ($t->team_member as $member ) {
        array_push($students_in_teams, $member->user_id);
    }
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

$available_students = array();
foreach ($course_students as $student) {
    if (!in_array($student->id, $students_in_teams)){
        array_push($available_students, $student);
    }
    
}

if (isset($_POST["submit"])) {

    $team_to_edit_id = $_POST['team'];
    if (isset($_POST['team_member_delete'])){
        $students_to_remove = $_POST['team_member_delete'];
    }
    else {
        $students_to_remove = null;
    }
    

    if(!is_null($students_to_remove)){ 
        foreach($students_to_remove as $remove){
            $delete = TeamMember::find_by(array('user_id' => $remove, 'team_id' => $team_to_edit_id ));
            $delete->delete();
        }
        $number_team_members = TeamMember::where(array('team_id' => $team_to_edit_id));
        if(count($number_team_members)==0){
            $delete_team = Team::find_by(array('id' => $team_to_edit_id));
            $delete_team->delete();
        }
    }

    if($_POST['student_selection'] != null){
        $team_member = new TeamMember();
        $team_member->team_id = $team_to_edit_id;
        $team_member->user_id = $_POST['student_selection'];

        try {
            $team_member->save();
        } catch (PDOException $error) {
            echo "<br>" . $error->getMessage();
        }
    }
    echo "<h5>Team updated succesfully </h5>";
    header("refresh : 1");
    
}

?>

<h2>Teams</h2>
    <?php if ($teams && count($teams)) { ?>
    
        <?php foreach ($teams as $row) { ?>
           <h3><?php echo "Team # ".$row->id ?></h3>
           <form method="post">
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
                    <td><input class="single-checkbox" type="checkbox" id="<?php echo $student->user_id; ?>" name="team_member_delete[]" value="<?php echo $student->user_id; ?>"> Remove from team</td>
                <?php }?>
                </tbody>
            </table>
            <?php if (count($students_in_team)<4){ ?>
                Add Student: 
                <select Name="student_selection" id="student_selection">
                    <option value="">----Select----</option>
                    <?php foreach ($available_students as $av_st) { ?>
                        <option value="<?php echo $av_st->id; ?>">
                        <?php echo $av_st->get_full_name()." Student ID ".$av_st->student_id; ?>
                        </option>
                            </tr>
                    <?php } ?>
                </select>
            <?php } ?>
            <br>
            <input type='hidden' name='team' value='<?php echo $row->id?>'>
            <input type='submit' name='submit' value='submit'>
            </form>
        <?php } ?>
    <?php } else { ?>
        <blockquote>No teams found for this course.</blockquote>
    <?php }?>

<?php } else { ?>
    <h2>You do not have the credentials to view this page.</h2>
<?php } ?>
