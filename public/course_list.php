<!--This works for now but should probably be refactored into separate html/php files.
It was mostly to try mixed php/html and get something working with basic styling
and data entry. -->

<html>

    <?php

    require "../modules/models/course.php";
    require "../modules/models/course_offering.php";
    require "../modules/models/course_section.php";
    require_once "../common.php";

    try {
        $result_courses = Course::getAll();
        $result_offerings = CourseOffering::getAll();
        $result_sections = CourseSection::getAll();
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
    } elseif (isset($_POST["submitOffering"])) {
        $offering = new CourseOffering();
        $offering->course_id = $_POST['course_selection'];
        $offering->course_offering_code = $_POST['course_offering_code'];
        $offering->course_offering_name = $_POST['course_offering_name'];


        try {
            $offering->save();
            $create_success = true;
        } catch (PDOException $error) {
            echo "General Error: The course offering could not be added.<br>" . $error->getMessage();
        }
    } elseif (isset($_POST["submitSection"])) {
        $section = new CourseSection();
        $section->offering_id = $_POST['offering_selection'];
        $section->course_section_code = $_POST['course_section_code'];
        $section->course_section_name = $_POST['course_section_name'];

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
        isset($_POST['submitOffering']) ||
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

<!--Offerings-->

        <br><br>
        <b>Offerings</b>
        <br>
        <?php if (count($result_offerings) > 0): ?>
            <table class="ctb">
                <thead>
                    <tr>
                        <th class="tgPurp">Offering ID</th>
                        <th class="tgPurp">Course ID</th>
                        <th class="tgPurp">Course Name</th>
                        <th class="tgPurp">Offering Code</th>
                        <th class="tgPurp">Offering Name</th>
                    </tr>
        </thead>
        <tbody>
            <?php foreach ($result_offerings as $row):;?>
            <tr>
                <td class="tgNorm"><?php echo($row->id);?></td>
                <td class="tgNorm"><?php echo($row->course_id);?></td>
                <td class="tgNorm"><?php echo($row->course->course_name);?></td>
                <td class="tgNorm"><?php echo($row->course_offering_code);?></td>
                <td class="tgNorm"><?php echo($row->course_offering_name);?></td>
            
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
                <td class="tgNorm"><input type="text" value="Offering Code" name="course_offering_code" id="course_offering_code"></td>
                <td class="tgNorm"><input type="text" value="Offering Name" name="course_offering_name" id="course_offering_name"></td>
                <td class="tgNorm"><input type="submit" name="submitOffering" value="Add"></td>
            </tr>
        </tbody>
        </table>
        <?php elseif (count($result_courses) > 0):?>
            <b>No offerings found. Add a course offering:</b>
            <br>
            <form method="post">

            <select name = "course_selection" id="course_selection">
            <option value = "">--Select Course--</option>
            <?php foreach ($result_courses as $selectop):;?>
                <option value = <?php echo($selectop->id);?>><?php echo($selectop->course_name);?></option>
            <?php endforeach;?>
            <input type="text" value="Offering Code" name="course_offering_code" id="course_offering_code">
            <input type="text" value="Offering Name" name="course_offering_name" id="course_offering_name">
            <input type="submit" name="submitOffering" value="Add">
        <?php else:?>
            <b>Please add a course before adding an offering.</b>
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
                        <th class="tgPurp">Course ID-Offering ID</td>
                        <th class="tgPurp">Course Name-Offering Name</td>
                        <th class="tgPurp">Section Code</th>
                        <th class="tgPurp">Section Name</th>
                    </tr>
        </thead>
        <tbody>
            <?php foreach ($result_sections as $row):;?>
            <tr>
                <td class="tgNorm"><?php echo($row->id);?></td>
                <td class="tgNorm"><?php echo($row->course_offering->course_id), " - ",($row->offering_id);?></td>
                <td class="tgNorm"><?php echo($row->course_offering->course->course_name), " - ",($row->course_offering->course_offering_name);?></td>
                <td class="tgNorm"><?php echo($row->course_section_code);?></td>
                <td class="tgNorm"><?php echo($row->course_section_name);?></td>
            </tr>
            <?php endforeach;?>
            <tr>
                <td class="tgNorm"></td>
                <form method="post">
                <td class="tgNorm">            
                    <select name = "offering_selection" id="offering_selection">
                    <option value = "">--Select Course--</option>
                    <?php foreach ($result_offerings as $selectop):;?>
                        <option value = <?php echo($selectop->id);?>><?php echo($selectop->course_offering_name);?></option>
                    <?php endforeach;?>
                </td>
                <td class="tgNorm"></td>
                <td class="tgNorm"><input type="text" value="Section Code" name="course_section_code" id="course_section_code"></td>
                <td class="tgNorm"><input type="text" value="Section Name" name="course_section_name" id="course_section_name"></td>
                <td class="tgNorm"><input type="submit" name="submitSection" value="Add"></td>
            </tr>
        </tbody>
        </table>
        <?php elseif (count($result_courses) > 0):?>
            <b>No offering found. Add a course offering:</b>
            <br>
            <form method="post">

            <select name = "offering_selection" id="offering_selection">
            <option value = "">--Select Course--</option>
            <?php foreach ($result_offerings as $selectop):;?>
                <option value = <?php echo($selectop->id);?>><?php echo($selectop->course_offering_name);?></option>
            <?php endforeach;?>
            <input type="text" value="Section Code" name="course_section_code" id="course_section_code">
            <input type="text" value="Section Name" name="course_section_name" id="course_section_name">
            <input type="submit" name="submitSection" value="Add">
        <?php else:?>
            <b>Please add a course before adding an offering.</b>
        <?php endif?>


    </body>
</html>