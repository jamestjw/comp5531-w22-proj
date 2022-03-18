<?php

require_once "../modules/models/meeting.php";
require_once "../common.php";

ensure_logged_in();

if (isset($_POST["submit"])) {
    $meeting = new Meeting();
    $meeting->title = $_POST["title"];
    $meeting->user_id = $_SESSION["current_user"]->id;
    $meeting->agenda = $_POST["agenda"];
    $meeting->planned_date = $_POST["date"];
    $meeting->planned_time = $_POST["time"];
    $meeting->has_passed = '0';

    header("Location: meetings.php");

    try {
        $meeting->save();
    } catch (PDOException $error) {
        echo "<br>" . $error->getMessage();
    }
}
?>

<?php include "templates/header.php"; ?>

<h2>Create a Meeting</h2>

<form method="post">
    <label for="title">Title</label>
    <input type="text" name="title" id="title">
    <label for="agenda">Agenda</label>
    <textarea name="agenda" cols="40" rows="5"></textarea>
    <label for="date">Date of meeting</label>
    <input type="date" name="date" id="date">
    <label for="time">Time of meeting</label>
    <input type="time" name="time" id="time">
    <input type="submit" name="submit" value="Submit">
</form>



<?php include "templates/footer.php"; ?>