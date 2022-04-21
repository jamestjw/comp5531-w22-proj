<?php

$lecture_page_id = $_GET['id'];

try {
    $course_lecture = Lecture::includes(["course", "discussions"])->find_by_id($lecture_page_id);
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
} ?>

<h2> Discussions for lecture <?php echo $course_lecture->course->course_name." ".$course_lecture->lecture_code?> </h2>

<?php
$discussions = $course_lecture->discussions;
$discussable_id =  $course_lecture->id;
$discussable_type = "Lecture";
include "discussion_list.php";

?>