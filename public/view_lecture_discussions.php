<?php

$lecture_page_id = $_GET['id'];

try {
    $course_lecture = Lecture::includes(["course", "discussions"])->find_by_id($lecture_page_id);
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

$discussions = $course_lecture->discussions;
$discussable_id =  $course_lecture->id;
$discussable_type = "Lecture";
include "discussion_list.php";

?>