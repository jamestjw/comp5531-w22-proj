<!--This works for now but should probably be refactored into separate html/php files.
It was mostly to try mixed php/html and get something working with basic styling
and data entry. -->

<html>

    <?php

    require "../modules/models/course.php";
    require "../modules/models/lecture.php";
    require "../modules/models/section.php";
    require_once "../common.php";

    try {
        $result_courses = Course::getAll();
        $result_lectures = Lecture::getAll();
        $result_sections = Section::getAll();
    } catch (PDOException $error) {
        echo "Course Error: <br>" . $error->getMessage();
    }?>

<!-- This section deals with submission of the entered data.-->

    <?php if (isset($_POST['submitCourse'])) {
        $course = new Course();
        $course->course_code = $_POST['course_code'];
        $course->course_name = $_POST['course_name'];

        try {
            $course->save();
            $create_success = true;
        } catch (PDOException $error) {
            echo "General Error: The course could not be added.<br>" . $error->getMessage();
        }
    } elseif (isset($_POST["submitLecture"])) {
        $lecture = new Lecture();
        $lecture->course_id = $_POST['course_selection'];
        $lecture->lecture_code = $_POST['lecture_code'];


        try {
            $lecture->save();
            $create_success = true;
        } catch (PDOException $error) {
            echo "General Error: The course lecture could not be added.<br>" . $error->getMessage();
        }
    } elseif (isset($_POST["submitSection"])) {
        $section = new Section();
        $section->lecture_id = $_POST['lecture_selection'];
        $section->section_code = $_POST['section_code'];

        try {
            $section->save();
            $create_success = true;
        } catch (PDOException $error) {
            echo "General Error: The course Section could not be added.<br>" . $error->getMessage();
        }
    }
    ?>

    <!-- Seemed like a good way to prevent form resubmission on refresh? -->
    <?php if ((
        (
            isset($_POST['submitCourse']) ||
        isset($_POST['submitLecture']) ||
        isset($_POST['submitSection'])
        ) && isset($create_success)
    ) && $create_success) {
        header('location: course_list.php');
    }?>

    <body>

<!-- Styles-->

    <style>
        body
        {
            background-repeat: no-repeat;
            background-position: right;
            background-attachment:fixed;
            font-family: "Times New Roman";
            font-size:16px;
        }
    </style>
    <style type = "text/css">
                .ctb {border-collapse:collapse;border-spacing:0; margin:0px auto}
                .ctb td{border-color:black;border-style:solid;border-width:1px;font-family:"Times New Roman", "Comic Sans";font-size:16px; overflow:hidden;padding:10px 5px;word-break:normal;}
                .ctb th{border-color:black;border-style:solid;border-width:1px;font-family:"Times New Roman", "Comic Sans";font-size:16px;font-weight:normal;overflow:hidden;padding:10px 5px;word-break:normal;}
                .ctb .tgPurp{background-color:#cbcefb;border-color:inherit;text-align:left;vertical-align:top}
                .ctb .tgNorm{background-color:#fafaf0;text-align:left;vertical-align:top}
    </style>


        <?php include "templates/header.php"?>
        <h2>Class Creation Wizard!</h2>
        <br>

        <!-- Show current classes-->
        <b>Courses</b>

        <?php if (count($result_courses) > 0): ?>
            <table class="ctb">
                <thead>
                    <tr>
                        <th class="tgPurp">Course ID</th>
                        <th class="tgPurp">Course Code</th>
                        <th class="tgPurp">Course Name</th>
                    </tr>
        </thead>
        <tbody>
            <?php foreach ($result_courses as $row):;?>
            <tr>
                <td class="tgNorm"><?php echo($row->id);?></td>
                <td class="tgNorm"><?php echo($row->course_code);?></td>
                <td class="tgNorm"><?php echo($row->course_name);?></td>
            </tr>
            <?php endforeach;?>
            <tr>
                <td></td>
                <form method="post">
                <td class="tgNorm"><input type="text" value="Course Code" name="course_code" id="course_code"></td>
                <td class="tgNorm"><input type="text" value="Course Name" name="course_name" id="course_name"></td>
                <td class="tgNorm"><input type="submit" name="submitCourse" value="Add"></td>
            </tr>
        </tbody>
        </table>
        <?php else:?>
            <br>
            <b>No courses found, please add a course.</b>
            <form method="post">
            <input type="text" value="Course Code" name="course_code" id="course_code">
            <input type="text" value="Course Name" name="course_name" id="course_name">
            <input type="submit" name="submitCourse" value="Add">
        <?php endif;?>

<!--Lectures-->

        <br><br>
        <b>Lectures</b>
        <br>
        <?php if (count($result_lectures) > 0): ?>
            <table class="ctb">
                <thead>
                    <tr>
                        <th class="tgPurp">Lecture ID</th>
                        <th class="tgPurp">Course ID</th>
                        <th class="tgPurp">Course Name</th>
                        <th class="tgPurp">Lecture Code</th>
                    </tr>
        </thead>
        <tbody>
            <?php foreach ($result_lectures as $row):;?>
            <tr>
                <td class="tgNorm"><?php echo($row->id);?></td>
                <td class="tgNorm"><?php echo($row->course_id);?></td>
                <td class="tgNorm"><?php echo($row->course->course_name);?></td>
                <td class="tgNorm"><?php echo($row->lecture_code);?></td>
            
            </tr>
            <?php endforeach;?>
            <tr>
                <td class="tgNorm"></td>
                <form method="post">
                <td class="tgNorm">
                    <select name = "course_selection" id="course_selection">
                    <option value = "">--Select Course--</option>
                    <?php foreach ($result_courses as $selectop):;?>
                        <option value = <?php echo($selectop->id);?>><?php echo($selectop->course_name);?></option>
                    <?php endforeach;?>
                </td>
                <td class="tgNorm"></td>
                <td class="tgNorm"><input type="text" value="Lecture Code" name="lecture_code" id="lecture_code"></td>
                <td class="tgNorm"><input type="submit" name="submitLecture" value="Add"></td>
            </tr>
        </tbody>
        </table>
        <?php elseif (count($result_courses) > 0):?>
            <b>No lectures found. Add a course lecture:</b>
            <br>
            <form method="post">

            <select name = "course_selection" id="course_selection">
            <option value = "">--Select Course--</option>
            <?php foreach ($result_courses as $selectop):;?>
                <option value = <?php echo($selectop->id);?>><?php echo($selectop->course_name);?></option>
            <?php endforeach;?>
            <input type="text" value="Lecture Code" name="lecture_code" id="lecture_code">
            <input type="submit" name="submitLecture" value="Add">
        <?php else:?>
            <b>Please add a course before adding an lecture.</b>
        <?php endif?>

<!-- Sections -->
        <br><br>
        <b>Sections</b>
        <br>
        <?php if (count($result_sections) > 0): ?>
            <table class="ctb">
                <thead>
                    <tr>
                        <th class="tgPurp">Section ID</th>
                        <th class="tgPurp">Course ID-Lecture ID</td>
                        <th class="tgPurp">Course Name</td>
                        <th class="tgPurp">Section Code</th>
                    </tr>
        </thead>
        <tbody>
            <?php foreach ($result_sections as $row):;?>
            <tr>
                <td class="tgNorm"><?php echo($row->id);?></td>
                <td class="tgNorm"><?php echo($row->lecture->course_id), ".",($row->lecture_id), ": ", ($row->lecture->course->course_name), " - ", ($row->lecture->lecture_code) ;?></td>
                <td class="tgNorm"><?php echo($row->lecture->course->course_name);?></td>
                <td class="tgNorm"><?php echo($row->section_code);?></td>
            </tr>
            <?php endforeach;?>
            <tr>
                <td class="tgNorm"></td>
                <form method="post">
                <td class="tgNorm">            
                    <select name = "lecture_selection" id="lecture_selection">
                    <option value = "">--Select Course--</option>
                    <?php foreach ($result_lectures as $selectop):;?>
                        <option value = <?php echo($selectop->id);?>><?php echo($selectop->course->id), ".", ($selectop->id), ": ", ($selectop->course->course_name), " - ", ($selectop->lecture_code);?></option>
                    <?php endforeach;?>
                </td>
                <td class="tgNorm"></td>
                <td class="tgNorm"><input type="text" value="Section Code" name="section_code" id="section_code"></td>
                <td class="tgNorm"><input type="submit" name="submitSection" value="Add"></td>
            </tr>
        </tbody>
        </table>
        <?php elseif (count($result_courses) > 0):?>
            <b>No lecture found. Add a lecture.</b>
            <br>
            <form method="post">

            <select name = "lecture_selection" id="lecture_selection">
            <option value = "">--Select Course--</option>
            <?php foreach ($result_lectures as $selectop):;?>
                <option value = <?php echo($selectop->id);?>><?php echo($selectop->course->id), ".", ($selectop->id), ": ", ($selectop->course->course_name), " - ", ($selectop->lecture_code);?></option>
            <?php endforeach;?>
            <input type="text" value="Section Code" name="section_code" id="section_code">
            <input type="submit" name="submitSection" value="Add">
        <?php else:?>
            <b>Please add a lecture before adding a section</b>
        <?php endif?>


    </body>
</html>