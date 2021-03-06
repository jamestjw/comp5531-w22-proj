<?php
// James Juan Whei Tan - 40161156
// Andréanne Chartrand-Beaudry - 29605991
?>
<?php
require_once "../modules/models/meeting.php";
require_once "../common.php";

ensure_logged_in();?>

<?php include "templates/header.php"; ?>

<?php

if (isset($_GET["id"]) && ($meeting = Meeting::find_by_id($_GET["id"]))) { ?>

    <div><h3>Title: <?php echo $meeting->title; ?> </h3> </div>
    <div><h5>Agenda</h5> 
    <?php echo nl2br($meeting->agenda); ?> 
    </div>
        <?php if ($meeting->has_passed == false) {
            if ($_SESSION['current_user_id'] == $meeting->user_id || !is_null(TeamMember::find_by(["user_id"=> $_SESSION['current_user_id'], "team_id" => $meeting->team_id]))) { ?>
            <form method="post">
                <input type="submit" name="start_meeting" value="Start Meeting">
            </form>
            <?php } ?>
        <?php } else {?>
            <h5>Started </h5> <?php echo $meeting->start_at;?>
            <h5>Ended </h5> <?php echo $meeting->end_at;?>
            <h5>Meeting Minutes</h5> <?php echo nl2br($meeting->minutes); ?>
        <?php }?>

<?php
} else {
    echo "Invalid meeting ID.";
}

?>


<?php  if (isset($_POST["start_meeting"])) { ?>
    <?php

    if (is_null(TeamMember::find_by(["user_id"=> $_SESSION['current_user_id'], "team_id" => $meeting->team_id]))) {
        set_error_and_go_back("You do not have permission to start this meeting");
    }
    $meeting->has_passed = '1';
    $meeting->start_at = date('Y-m-d H:i:s');

    try {
        $meeting->save();
    } catch (PDOException $error) {
        echo "<br>" . $error->getMessage();
    }
    ?>
    <form method="post">
        <label for="meeting_minutes">Meeting Minutes</label>
        <textarea name="meeting_minutes" cols="40" rows="5" required></textarea>
        <input type="submit" name="end_meeting" value="End meeting">
    </form>
    
<?php }?>

<?php if (isset($_POST["end_meeting"])) {
        if (is_null(TeamMember::find_by(["user_id"=> $_SESSION['current_user_id'], "team_id" => $meeting->team_id]))) {
            set_error_and_go_back("You do not have permission to add minutes to this meeting.");
        }

        $meeting->minutes = $_POST["meeting_minutes"];
        $meeting->end_at = date('Y-m-d H:i:s');


        try {
            $meeting->save();
            header("Refresh:0");
        } catch (PDOException $error) {
            echo "<br>" . $error->getMessage();
        }
    }?>