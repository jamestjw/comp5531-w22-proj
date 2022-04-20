<?php 
require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php");
require_once "../modules/models/course.php";
require_once "../common.php";

try {
    $result_courses = Course::getAll();
} catch (PDOException $error) {
    echo "Course Error: <br>" . $error->getMessage();
}?>

<?php include "templates/header.php"?>

<?php if (!empty($_POST)) {
    if (get_current_role() == "admin" && isset($_POST['submitCourse'])) {
        $course = new Course();
        $course->course_code = $_POST['course_code'];
        $course->course_name = $_POST['course_name'];
        try {
            $course->save();
            $create_success = true;
        } catch (PDOException $error) {
            echo "General Error: The course could not be added.<br>" . $error->getMessage();
        }  

    if (isset($_POST['submitCourse']) && isset($create_success)&& $create_success) 
    {
            header('location: course_list.php');
    }
    } 
    
    else {?>
    <p>You must be an <strong>admin</strong> to modify the course list.</p>
    <?php }
} ?>

<html>
    <body>
        <h2>Courses</h2>
        <?php if (count($result_courses) > 0): ?>
            <table class="ctb">
                <thead>
                    <tr>
                        <th class="tgPurp">Course ID</th>
                        <th class="tgPurp">Course Code</th>
                        <th class="tgPurp">Course Name</th>
                        <th class="tgPurp">Actions</th>
                    </tr>
        </thead>
        <tbody>

            <?php foreach ($result_courses as $row):;?>
            <tr>
                <td class="tgNorm"><?php echo($row->id);?></td>
                <td class="tgNorm"><?php echo($row->course_code);?></td>
                <td class="tgNorm"><?php echo($row->course_name);?></td>
                <td class = "tgAct">                    
                    <a href="lectures_list.php?id=<?php echo $row->id;?>">
                    View Lectures
                    </a>
                </td>

            </tr>
            <?php endforeach;?>

            <?php
                if (get_current_role() == "admin") {
                    ?>
            <tr>
                <td class = "tgInvis"></td>
                <form method="post">
                <td class="tgNorm"><input type="text" value="Course Code" name="course_code" id="course_code"></td>
                <td class="tgNorm"><input type="text" value="Course Name" name="course_name" id="course_name"></td>
                <td class="tgAct"><input type="submit" name="submitCourse" value="Add Course"></td>
            </tr>
            <?php
                } ?>
        </tbody>
        </table>
        <?php elseif (get_current_role() == "admin"):?>
            <br>
            <b>No courses found, please add a course.</b>
            <form method="post">
            <input type="text" value="Course Code" name="course_code" id="course_code">
            <input type="text" value="Course Name" name="course_name" id="course_name">
            <input type="submit" name="submitCourse" value="Add Course">
        <?php endif;?>

    </body>

</html>