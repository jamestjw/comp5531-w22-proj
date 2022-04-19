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

<?php
$team_id = $_GET["id"];

$team_members = TeamMember::includes("user")->where(array("team_id" => $team_id));

//$marked_entities = 

if (isset($team_members) && !empty($team_members)) {
?>


<div>

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

    </div>
</div>

<?php } ?>

<?php include "templates/footer.php"; ?>