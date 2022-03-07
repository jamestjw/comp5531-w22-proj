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
        <?php if($meeting->has_passed == false){?>
            <form method="post">
            <input type="submit" name="start_meeting" value="Start Meeting">
            </form>
        <?php } else {?>
            <h5>Started at </h5> <?php echo $meeting->started_at;?>
            <h5>Ended at </h5> <?php echo $meeting->ended_at;?>
            <h5>Meeting Minutes</h5> <?php echo nl2br($meeting->minutes); ?>
        <?php }?>

<?php
} else {
        echo "Invalid meeting ID.";
    }

?>

<?php  if(isset($_POST["start_meeting"])) {
    $meeting->has_passed = 1;
    $meeting->start_at = time();
    ?>
    <form method="post">
    <label for="meeting_minutes">Meeting Minutes</label>
    <textarea name="meeting_minutes" cols="40" rows="5"></textarea>
    <input type="submit" name="end_meeting" value="End meeting">
    </form>
<?php }?>

<?php if(isset($POST["end_meeting"])) {
    $meeting->minutes = $POST["meeting_minutes"];
    $meeting->end_at = time();
}?>

    