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
<?php if(get_current_role() != 'student') { ?>
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
    
    $student_team = Team::joins(["team_members"])->find_by(["user_id"=>get_users_id(), "lecture_id"=>$lecture_page_id]);

    if(!is_null($student_team)){
        $student_team_id = $student_team->id;
    } else {
        $student_team_id= null;
    }


    
    if(isset($_GET["view"])){
        $student_team_id = $_GET["view"];
    }

    if(is_null($student_team_id)) {
        echo "You are not part of a team for this lecture";
    } else {
        $team_members = TeamMember::where(array('team_id' => $student_team_id));

        $meetings = Meeting::where(array("team_id" => $student_team_id));
        $marked_entities = MarkedEntity::joins_raw_sql("
            JOIN lectures ON
            marked_entities.lecture_id = lectures.id
            JOIN teams ON
            teams.id = {$student_team_id} AND teams.lecture_id = lectures.id
        ")->getAll();

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
        
        <div>
        <h2>Upcoming Meetings:</h2>
        <ul>
            <?php
            if (!empty($meetings) && !is_null($meetings)) {
                foreach ($meetings as $meeting) {
                    echo "<li><a href='meeting.php?id={$meeting->id}'>{$meeting->title} at 
                    {$meeting->planned_date} {$meeting->planned_time}</a></li>";
                }
            }
            ?>
        </ul>
    </div>
    <div>
        <h2>In Progress Marked Entities:</h2>
        <ul>
            <?php
            if (!empty($marked_entities) && !is_null($marked_entities)) {
                foreach ($marked_entities as $me) {
                    echo "<li><a href='marked_entity.php?id={$me->id}'>{$me->title} Due at: 
                    {$me->due_at}</a></li>";
                }
            }
            ?>
        </ul>
    </div>
    
    <?php } ?>   
<?php }?>