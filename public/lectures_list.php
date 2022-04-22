<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>

<?php
    require "../modules/models/course.php";
    require "../modules/models/lecture.php";

    $course_id = $_GET['id'];

    try {
        $specific_course = Course::find_by(array('id' => $course_id));
    } catch (PDOException $error) {
        echo "<br>" . $error->getMessage();
    }

    try {
        $existing_lectures = Lecture::where(array('course_id' => $course_id));
    } catch (PDOException $error) {
        echo "<br>" . $error->getMessage();
    }

    if (!empty($_POST)) {
        if (get_current_role() == "admin") {        
            
            if (isset($_POST["submitLecture"])) {
            $new_lecture = new Lecture();
            $new_lecture->course_id = $course_id;
            $new_lecture->lecture_code = $_POST['lecture_code'];
            $new_lecture->starting_date = $_POST['starting_date'];
            $new_lecture->ending_date = $_POST['ending_date'];

            try {
                if ($new_lecture->starting_date > $new_lecture->ending_date) {
                    throw new PDOException("The ending date cannot be before the beginning.");
                }
                $new_lecture->save();
                header('location:lectures_list.php?id='.$course_id);
            } catch (PDOException $error) {
                echo "The Lecture could not be added!<br>Error: " . $error->getMessage();
                }
            } 

            elseif(isset($_POST['deleteLecture'])){
                try{
                    Lecture::find_by(array('id' => $_POST['key']))->delete("id");
                    header('location:lectures_list.php?id='.$course_id);
                }
                catch(PDOException $error){
                    echo "The Lecture could not be deleted.<br>Error: " . $error->getMessage();
                }
            }
        }
        else {?>
            <p>You must be an <strong>admin</strong> to modify the section list.</p>
            <?php }
    }
?>

<?php include "templates/header.php"; ?>

<?php if (isset($specific_course)): ?>
<html>
<head>
<link rel="stylesheet" href="css/crsmgr_table_style.css">
</head>

        <h2>Lectures for <?php echo($specific_course->course_code), " - ", ($specific_course->course_name);?></h2>
        <?php if (count($existing_lectures) > 0): ?>
            <table class="ctb">
                <thead>
                    <tr>
                        <th class="tgPurp">Lecture ID</th>
                        <th class="tgPurp">Lecture Code</th>
                        <th class="tgPurp">Lecture Start Date</th>
                        <th class="tgPurp">Lecture End Date</th>
                        <th class="tgPurp">Actions</th>
                    </tr>
        </thead>
        <tbody>
            <?php foreach ($existing_lectures as $row):;?>
            <tr>
                <td class="tgNorm"><?php echo($row->id);?></td>
                <td class="tgNorm"><?php echo($row->lecture_code);?></td>
                <td class="tgNorm"><?php echo($row->starting_date);?></td>
                <td class="tgNorm"><?php echo($row->ending_date);?></td>
                <td class = "tgAct"> 

                    <a href="sections_list.php?cid=<?php echo $course_id;?>&lid=<?php echo $row->id;?>">
                    <button type="button" style="margin:5px";>View Sections</button>
                    </a>
                    <br>
                    <a href="course_lecture.php?id=<?php echo $row->id ?>">
                    <button type="button"style="margin:5px";>View Lecture Page</button>
                    </a>
                    <form method="post">
                    <input type="hidden" id="key" name="key" value="<?=$row->id?>">
                    <input type="submit" name="deleteLecture" value = "Delete Lecture" style="margin:5px">
                    </form>
                </td>
            </tr>
            <?php endforeach;?>

            <?php
                if (get_current_role() == "admin") {
                    ?>
            <tr>
                <td class="tgInvis"></td>
                <form method="post">
                <td class="tgNorm"><input type="text" value="Lecture Code" name="lecture_code" id="lecture_code"></td>
                <td class="tgNorm"><input type ="date" name = "starting_date" id="starting_date"></td>
                <td class="tgNorm"><input type ="date" name = "ending_date" id="ending_date"></td>
                <td class="tgNorm"><input type="submit" name="submitLecture" value="Add Lecture"></td>
            </tr>
            <?php
                } ?>
        </tbody>
        </table>

        <?php elseif (get_current_role() == "admin"):?>
            <br>
            <b>No lectures found for this course, please add a lecture.</b>
            <form method="post">
            <input type="text" value="Lecture Code" name="lecture_code" id="lecture_code">
            <input type ="date" name = "starting_date" id="starting_date">
            <input type ="date" name = "ending_date" id="ending_date">
            <input type="submit" name="submitLecture" value="Add Lecture">
        <?php endif;?>


        <p><a href="course_list.php"> Return to Course List</a></p>
</html>

<?php else: ?>
    <html>
        <h1> Error: The parent course does not exist.
    </html>
<?php endif;?>