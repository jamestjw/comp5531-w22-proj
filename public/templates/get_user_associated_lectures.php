<?php
$user_id = get_users_id();
$user_role = get_current_role();

$lectures = array();

switch ($user_role) {
    case 'admin':
        $lectures = Lecture::getAll();
        break;

    case 'instructor':
        $lecture_instructor = LectureInstructor::includes('lecture')->where(array('user_id' => $user_id));
        foreach($lecture_instructor as $lecture){
            array_push($lectures, $lecture->lecture);
        }
        break;

    case 'TA':
        # TO DO WHEN TA ARE IMPLEMENTED
        break;

    case 'student':
        $student_sections = SectionStudent::includes(['section'=> ['lecture']])->where(array('user_id' => $user_id));
        foreach($student_sections as $section){
            array_push($lectures, $section->section->lecture);
        }
        break;

    default:
        $lectures = [];
        break;
}
?>