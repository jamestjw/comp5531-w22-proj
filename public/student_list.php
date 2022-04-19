<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>
<?php include "templates/header.php"; ?>

<?php if(get_current_role() == 'admin' || get_current_role() == 'instructor') { ?>
<?php

require_once "../modules/models/user.php";
require_once "../modules/models/section.php";
require_once "../modules/models/section_student.php";
require_once "../common.php";

try {
    $students = User::where(array('is_instructor' => '0', 'is_admin' => '0'));
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

try {
    $sections = Section::includes('lecture')->getAll();
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

try {
    $sections_students = SectionStudent::includes("user")->getAll();
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}


?>

<?php
if ($students && count($students)) { ?>
        <h2>All Students</h2>

    <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email Address</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($students as $row) { ?>
            <tr>
                <td><?php echo escape($row->id); ?></td>
                <td><?php echo escape($row->first_name); ?></td>
                <td><?php echo escape($row->last_name); ?></td>
                <td><?php echo escape($row->email); ?></td>
                <td><?php echo escape($row->created_at);  ?> </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php } else { ?>
        <blockquote>No Students found.</blockquote>
    <?php }?>


  <?php
    if ($sections && count($sections)) { ?>
        <h2>Section student list</h2>

        <?php foreach($sections as $section) { ?>
            
            <h3>Lecture: <?php echo $section->lecture->lecture_code?> Section: <?php echo $section->section_code?> </h3>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email Address</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($sections_students as $section_students) { 
                        if ($section->id == $section_students->section_id) {
                            $student = $section_students->user; ?>
                                <tr>
                                    <td><?php echo escape($student->id); ?></td>
                                    <td><?php echo escape($student->first_name); ?></td>
                                    <td><?php echo escape($student->last_name); ?></td>
                                    <td><?php echo escape($student->email); ?></td>
                                    <td><?php echo escape($student->created_at); ?> </td>
                                </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>

        <?php }?>
    <?php } else { ?>
        <blockquote>No Sections Available.</blockquote>
    <?php }?>
    
    <br><a href="uploadStudentList.php">Upload Student list </a>

<?php } else {?>
    <h2>You do not have the credentials to view this page.</h2>
<?php }?>