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

    <form method="post">
    <input type="submit" name="start meeting" value="Start Meeting">
    </form>

<?php
} else {
        echo "Invalid meeting ID.";
    }

?>