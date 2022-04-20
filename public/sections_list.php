<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>

<?php
    require_once "../modules/models/course.php";
    require_once "../modules/models/lecture.php";
    require_once "../modules/models/section.php";

    $course_id = $_GET['cid'];
    $lecture_id = $_GET['lid'];

    try {
        $section_course = Course::where(array('id' => $course_id));
    } catch (PDOException $error) {
        echo "<br>" . $error->getMessage();
    }

    try {
        $section_lecture = Lecture::where(array('id' => $lecture_id));
    } catch (PDOException $error) {
        echo "<br>" . $error->getMessage();
    }

    try {
        $existing_sections = Section::where(array('lecture_id' => $lecture_id));
    } catch (PDOException $error) {
        echo "<br>" . $error->getMessage();
    }

    if (!empty($_POST)) {
        if (get_current_role() == "admin") {        
            if (isset($_POST["submitSection"])) {
            $new_section = new Section();
            $new_section->lecture_id = $lecture_id;
            $new_section->section_code = $_POST['section_code'];

            try {
                $new_section->save();
                $create_success = true;
            } catch (PDOException $error) {
                echo "The section could not be added!<br>Error: " . $error->getMessage();
                }
            } 
        }
    }

    if (isset($_POST['submitSection']) && isset($create_success) && $create_success) {
            header('location:sections_list.php?cid='.$course_id."&lid=".$lecture_id);
        }

?>

<?php include "templates/header.php"; ?>


<html>
<h2>Sections for <?php echo($section_course[0]->course_code), " - ", ($section_course[0]->course_name), ", Lecture ", ($section_lecture[0]->lecture_code);?> </h2>

        <?php if (count($existing_sections) > 0): ?>
            <table class="ctb">
                <thead>
                    <tr>
                        <th class="tgPurp">Section ID</th>
                        <th class="tgPurp">Section Code</th>
                        <th class="tgPurp">Actions</th>
                    </tr>
        </thead>
        <tbody>
            <?php foreach ($existing_sections as $row):;?>
            <tr>
                <td class="tgNorm"><?php echo($row->id);?></td>
                <td class="tgNorm"><?php echo($row->section_code);?></td>
                <td class="tgNorm"></td>
            </tr>
            <?php endforeach;?>

            <?php
                if (get_current_role() == "admin") {
                    ?>
            <tr>
                <td class="tgNorm"></td>
                <form method="post">
                <td class="tgNorm"><input type="text" value="Section Code" name="section_code" id="section_code"></td>
                <td class="tgNorm"><input type="submit" name="submitSection" value="Add Section"></td>
            </tr>
            <?php
                } ?>
        </tbody>
        <?php elseif (get_current_role() == "admin"):?>
            <br>
            <b>No lectures found for this course, please add a lecture.</b>
            <form method="post">
            <input type="text" value="Section Code" name="section_code" id="section_code">
            <input type="submit" name="submitSection" value="Add Section">
        <?php endif;?>

    </table>

    <p><a href="lectures_list.php?id=<?php echo $course_id;?>"> Return to Lecture List</a></p>
</html>