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

?>
<?php if(!get_current_role() == 'student') { ?>
<h2>Teams</h2>
    <p><a href="update_team.php?id=<?php echo $lecture_page_id?>">Update teams</a></p>
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
                <?php $students_in_team = $row->team_members;
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
    <?php } else { ?>
        <blockquote>No teams found for this course.</blockquote>
    <?php }?>

    <br><a href="create_team.php?id=<?php echo $lecture_page_id ?>">Create new teams</a> 
<?php }else {
    try {
        $all_student_team = TeamMember::includes(['teams' => 'lectures'])->where(array('user_id' => get_users_id(),));
    
    } catch (PDOException $error) {
        echo "<br>" . $error->getMessage();
    }
    
    if(count($all_student_team)>0){
        foreach($all_student_team as $st_team){
            if ($st_team->teams->lectures->id == $lecture_page_id){
                $student_team_id = $st_team->teams->id;
            } else {
                $student_team_id = null;
            }
        } 
    } else {
        $student_team_id = null;
    }
    
    if(is_null($student_team_id)) {
        echo "You are not part of a team for this lecture";
    } else {
        $team_members = TeamMember::where(array('team_id' => $student_team_id));
        ?>
    
        <h3><?php echo "Team # ".$student_team_id ?></h3>
               <table>
                    <thead>
                        <tr>
                            <th>Student id</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email Address</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($team_members as $student) {?>
                    <tr>
                        <td><?php echo escape($student->user->student_id); ?></td>
                        <td><?php echo escape($student->user->first_name); ?></td>
                        <td><?php echo escape($student->user->last_name); ?></td>
                        <td><?php echo escape($student->user->email); ?></td>
                    </tr>
                    <?php }?>
                    </tbody>
                    </table>
    
    <?php } ?>   
<?php }?>