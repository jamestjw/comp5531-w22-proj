<?php
// Christopher Almeida Neves - 27521979
// Andréanne Chartrand-Beaudry - 29605991
?>
<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>
<?php
require_once "../modules/models/user.php";
require_once "../common.php";
require_once "../modules/models/team.php";
require_once "../modules/models/team_member.php";
require_once "../modules/models/lecture.php";
require_once "../modules/models/section_student.php";
require_once "../modules/models/section.php";
require_once "../modules/models/meeting.php";
?>

<?php include "templates/header.php"; ?>

<?php
$team_id = $_GET["id"];

$team_members = TeamMember::includes("user")->where(array("team_id" => $team_id));
$meetings = Meeting::where(array("team_id" => $team_id));
$marked_entities = MarkedEntity::joins_raw_sql("
    JOIN lectures ON
    marked_entities.lecture_id = lectures.id
    JOIN teams ON
    teams.id = {$team_id} AND teams.lecture_id = lectures.id
")->getAll();

if (!empty($team_members)) {
?>

<div>
    <h1>Team Number: <?php echo $team_id; ?></h1>
    <div>
        <h2>Team Members:</h2>
        <ul>
            <?php
            foreach ($team_members as $u) {
                $full_name = $u->user->get_full_name();
                echo "<li>{$full_name}</li>";
            }
            ?>
        </ul>
    </div>
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
</div>
<?php } ?>

<?php include "templates/footer.php"; ?>