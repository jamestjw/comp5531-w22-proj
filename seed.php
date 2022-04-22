<?php

require "modules/models/section_student.php";
require "modules/models/lecture_instructor.php";
require "modules/models/announcement.php";

// Insert Instructors
$instructor = new User();
$instructor->first_name = "Bipin";
$instructor->last_name = "Desai";
$instructor->email = "bdesai@concordia.ca";
$instructor->is_admin = 0;
$instructor->is_instructor = 1;
$instructor->password_digest = password_hash('welcome', PASSWORD_DEFAULT);
$instructor->save();

$instructor2 = new User();
$instructor2->first_name = "Mary";
$instructor2->last_name = "Smith";
$instructor2->email = "msmith@concordia.ca";
$instructor2->is_admin = 0;
$instructor2->is_instructor = 1;
$instructor2->password_digest = password_hash('welcome', PASSWORD_DEFAULT);
$instructor2->save();

// Associate Instructors to Lectures
$lecture1_instructor = new LectureInstructor();
$lecture1_instructor->lecture_id = 1;
$lecture1_instructor->user_id = 2;
$lecture1_instructor->save();

$lecture2_instructor = new LectureInstructor();
$lecture2_instructor->lecture_id = 2;
$lecture2_instructor->user_id = 2;
$lecture2_instructor->save();

$lecture2_instructor2 = new LectureInstructor();
$lecture2_instructor2->lecture_id = 2;
$lecture2_instructor2->user_id = 3;
$lecture2_instructor2->save();

// Insert TAs
$TA = new User();
$TA->first_name = "Evelyn";
$TA->last_name = "Steele";
$TA->email = "esteele@concordia.ca";
$TA->is_admin = 0;
$TA->is_instructor = 0;
$TA->is_ta = 1;
$TA->password_digest = password_hash('welcome', PASSWORD_DEFAULT);
$TA->save();

$TA = new User();
$TA->first_name = "Nick";
$TA->last_name = "Duffy";
$TA->email = "nduffy@concordia.ca";
$TA->is_admin = 0;
$TA->is_instructor = 0;
$TA->is_ta = 1;
$TA->password_digest = password_hash('welcome', PASSWORD_DEFAULT);
$TA->save();

$TA = new User();
$TA->first_name = "Eddie";
$TA->last_name = "Brooks";
$TA->email = "ebrooks@concordia.ca";
$TA->is_admin = 0;
$TA->is_instructor = 0;
$TA->is_ta = 1;
$TA->password_digest = password_hash('welcome', PASSWORD_DEFAULT);
$TA->save();

$TA = new User();
$TA->first_name = "Marta";
$TA->last_name = "Weiss";
$TA->email = "mweiss@concordia.ca";
$TA->is_admin = 0;
$TA->is_instructor = 0;
$TA->is_ta = 1;
$TA->password_digest = password_hash('welcome', PASSWORD_DEFAULT);
$TA->save();

$TA = new User();
$TA->first_name = "Ramona";
$TA->last_name = "Berger";
$TA->email = "rberger@concordia.ca";
$TA->is_admin = 0;
$TA->is_instructor = 0;
$TA->is_ta = 1;
$TA->password_digest = password_hash('welcome', PASSWORD_DEFAULT);
$TA->save();

// Associate TA to sections
$TA_section = new SectionTA();
$TA_section->user_id = 4;
$TA_section->section_id = 1;
$TA_section->save();

$TA_section1 = new SectionTA();
$TA_section1->user_id = 5;
$TA_section1->section_id = 2;
$TA_section1->save();

$TA_section2 = new SectionTA();
$TA_section2->user_id = 6;
$TA_section2->section_id = 3;
$TA_section2->save();

$TA_section3 = new SectionTA();
$TA_section3->user_id = 7;
$TA_section3->section_id = 4;
$TA_section3->save();

$TA_section4 = new SectionTA();
$TA_section4->user_id = 8;
$TA_section4->section_id = 5;
$TA_section4->save();



// Insert Student (from list in doc)

$students = fopen(__DIR__ .'/students.csv', "r");
$headers = fgetcsv($students, 1000, ",");
$count = 0;

while (($studentData = fgetcsv($students, 1000, ",")) !== false) {
    $user = new User();
    $user->student_id = $studentData[0];
    $user->first_name = $studentData[1];
    $user->last_name = $studentData[2];
    $user->email = $studentData[3];
    $user->is_admin = 0;
    $user->is_instructor = 0;
    $user->is_ta = 0;
    $user->password_digest = password_hash("welcome", PASSWORD_DEFAULT);
    $count++;

    try {
        $user->save();
        $create_success = true;
    } catch (PDOException $error) {
        echo "<br>" . $error->getMessage();
    }
}

fclose($students);

if ($create_success) {
    echo $count." students added to database";
} else {
    echo "student list upload failed";
}


// Associate Students to sections
for ($id = 9; $id <=49; $id++) {
    if ($id<=29) {
        $section_student = new SectionStudent();
        $section_student->user_id = $id;
        $section_student->section_id = 1;
        $section_student->save();

        if ($id<19) {
            $section_student = new SectionStudent();
            $section_student->user_id = $id;
            $section_student->section_id = 3;
            $section_student->save();
        } else {
            $section_student = new SectionStudent();
            $section_student->user_id = $id;
            $section_student->section_id = 4;
            $section_student->save();
        }
    } else {
        $section_student = new SectionStudent();
        $section_student->user_id = $id;
        $section_student->section_id = 2;
        $section_student->save();

        $section_student = new SectionStudent();
        $section_student->user_id = $id;
        $section_student->section_id = 5;
        $section_student->save();
    }
}

// Create a new team
$team = new Team();
$team->lecture_id = 1;
$new_team_members = array();
// Take 4 students who are in the same course and put them in a team
for ($id = 26; $id <=29; $id++) {
    $team_member = new TeamMember();
    $team_member->user_id = $id;
    array_push($new_team_members, $team_member);
}
$team->team_members = $new_team_members;
$team->save();

// Create welcome announcement (first announcement)
$announcement = new Announcement();
$announcement->announcement_text = "Welcome to Course Manager group assistant!";
$announcement->save();
