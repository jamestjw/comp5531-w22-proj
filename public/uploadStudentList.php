<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>
<?php include "templates/header.php"; ?>



<?php
require_once "../modules/models/user.php";
require_once "../common.php";
require_once "../modules/models/section.php";

try {
    $section = Section::getAll();
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}


    $create_success = false;


    if (isset($_POST['submit2'])) {
        $students = fopen($_POST["fileID"], "r");
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

            try {
                $user->save();
                $count++;
                $create_success = true;
            } catch (PDOException $error) {
                echo "<br>" . $error->getMessage();
            }
            $section_student = new SectionStudent();
            $section_student->user_id = $user->id;
            $section_student->section_id = $_POST['sectionID'];
            try {
                $section_student->save();
            } catch (PDOException $error) {
                echo "<br>" . $error->getMessage();
            }

        }


        fclose($students);
        unlink($_POST["fileID"]);
    }
    
?>


<?php
    if (isset($_POST['submit2']) && $create_success) {
        echo "<h3> $count Students added successfully</h3><br>";
    } elseif (isset($_POST['submit2']) && !$create_success) {
        echo "<h3> Student List upload failed </h3>";
    }
?>

<form enctype='multipart/form-data' action='' method='post'>

    <h2>
        <label>Upload Student List</label> 
    </h2>
    <br>
    <p> 
        File should have a Header row and the columns should be as follows : Student ID, First Name, Last Name, Email 
        <br>All new users have the default password: "Welcome"
    </p>
    
    <label for="filename">Select File</label>
    <input size='50' type='file' name='filename' accept=".csv">
    </br>

    <!-- TO DO change to add info on section name as well -->
    <label for="section">Select Section</label>
    <select Name="section" id="section">
        <option value="" disabled selected>----Select----</option>
        <?php foreach($section as $row) { ?>
            <option value="<?php echo $row->id; ?>">
            <?php echo $row->id; ?>
            </option>
        <?php } ?>
        </select>

    <input type='submit' name='submit' value='Upload List'>
 
</form>


<?php
    if (isset($_POST["submit"])) {
        global $studentData;
        global $student_section;

        $student_section = $_POST["section"];
        $studentData = array();
        $row=0;

        echo "<h5>Users to be added </h5>";

        $handle = fopen($_FILES['filename']['tmp_name'], "r");
        $headers = fgetcsv($handle, 1000, ",");
        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            $studentData[$row] = $data;
            echo join(", ", $data);
            echo "<br>";

            $row++;
        }
        fclose($handle);

        $fileName = uniqid('studentList').".csv";
        move_uploaded_file($_FILES['filename']['tmp_name'], $fileName);

        echo "<h5> New users will be added to section ".$student_section."</h5>";
    }
?>

<?php if (isset($_POST["submit"])) : ?>
    <form method='post'>
    <input type='submit' name='submit2' value='confirm and upload'>
    <input type="hidden" id="fileID" name="fileID" value="<?php echo $fileName?>">
    <input type="hidden" id="sectionID" name="sectionID" value="<?php echo $student_section?>">
    </form>

<?php endif; ?>

<br><br>
<a href="student_list.php">Return to student list page</a>


<?php include "templates/footer.php"; ?>