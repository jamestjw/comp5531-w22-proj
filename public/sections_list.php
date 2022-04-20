<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>

<?php
    require_once "../modules/models/course.php";
    require_once "../modules/models/lecture.php";
    require_once "../modules/models/section.php";

    $course_id = $_GET['cid'];
    $lecture_id = $_GET['lid'];

    try {
        $section_course = Course::find_by(array('id' => $course_id));
    } catch (PDOException $error) {
        echo "<br>" . $error->getMessage();
    }

    try {
        $section_lecture = Lecture::find_by(array('id' => $lecture_id));
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
                header('location:sections_list.php?cid='.$course_id."&lid=".$lecture_id);
            } catch (PDOException $error) {
                echo "The section could not be added!<br>Error:" . $error->getMessage();
                }
            }
            elseif (isset($_POST['deleteSection'])){
                try {
                    Section::find_by(array('id' => $_POST['key']))->delete("id");
                    header('location:sections_list.php?cid='.$course_id."&lid=".$lecture_id);
                }
                catch (PDOException $error) {
                    echo "The course could not be deleted!" . $error->getMessage();
                }
            } 
        }

        else {?>
            <p>You must be an <strong>admin</strong> to modify the section list.</p>
    <?php }
    }
?>

<?php include "templates/header.php"; ?>

<?php if (isset($section_course) && isset($section_lecture)): ?>
<html>
<head>
<link rel="stylesheet" href="css/table_style.css">
</head>
<h2>Sections for <?php echo($section_course->course_code), " - ", ($section_course->course_name), ", Lecture ", ($section_lecture->lecture_code);?> </h2>

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
                <td class="tgNorm">                    
                    <form method="post">
                    <input type="hidden" id="key" name="key" value="<?=$row->id?>">
                    <input type="submit" name="deleteSection" value = "Delete Section" style="margin:5px">
                    </form>
                </td>
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

<?php else: ?>
    <html>
        <h1> Error: The parent lecture or course do not exist.
    </html>
<?php endif;?>