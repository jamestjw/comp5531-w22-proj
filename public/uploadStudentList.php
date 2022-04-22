<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>
<?php include "templates/header.php"; ?>
<link rel="stylesheet" href="css/crsmgr_table_style.css">


<?php
require_once "../modules/models/user.php";
require_once "../common.php";
require_once "../modules/models/section.php";

try {
    $section = Section::includes(['lecture' => 'course'])->getAll();
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

try {
    $all_students = User::where(array('roles' => 0));
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

// check if you where directed here through a specific lecture page 
$lecture_id = $_GET['id'] ?? 0;

if($lecture_id > 0){
    $section = Section::includes(['lecture' => 'course'])->where(array('lecture_id' => $lecture_id));
}

    $create_success = false;

    if (isset($_POST['submit2'])) {
        $students = fopen($_POST["fileID"], "r");
        $headers = fgetcsv($students, 1000, ",");
        $count = 0;
        $all_student_in_section = SectionStudent::includes('user')->where(array('section_id' => $_POST['sectionID']));

        while (($studentData = fgetcsv($students, 1000, ",")) !== false) {

            $existing_student = array_search($studentData[0], array_column($all_students, 'student_id'));
            $student_section_exists = in_array($all_students[$existing_student]->id, array_column($all_student_in_section, 'user_id'));

            if($existing_student === false){
                $user = new User();
                $user->student_id = $studentData[0];
                $user->first_name = $studentData[1];
                $user->last_name = $studentData[2];
                $user->email = $studentData[3];
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
                $section_success = true;
                try {
                    $section_student->save();
                } catch (PDOException $error) {
                    echo "<br>" . $error->getMessage();
                }
            } else if ($student_section_exists) { 
                echo "Student with id ".$studentData[0]." is already registered in course section ".$_POST['sectionID']."<br>";
                $section_success = true;
            }else {
                $section_student = new SectionStudent();
                $section_student->user_id = $all_students[$existing_student]->id;
                $section_student->section_id = $_POST['sectionID'];
                $count++;
                $section_success = true;
                try {
                    $section_student->save();
                } catch (PDOException $error) {
                    echo "<br>" . $error->getMessage();
                }
            }

        }


        fclose($students);
        unlink($_POST["fileID"]);
    }
    
?>


<?php
    if (isset($_POST['submit2']) && ($create_success || $section_success)) {
        echo "<h3> $count Students added successfully</h3><br>";
    } elseif (isset($_POST['submit2'])) {
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
        <br>All new users have the default password: "welcome"
    </p>
    
    <label for="filename">Select File</label>
    <input size='50' type='file' name='filename' accept=".csv">
    </br>

    <label for="section">Select Section</label>
    <select Name="section" id="section">
        <option value="" disabled selected>----Select----</option>
        <?php foreach($section as $row) { ?>
            <option value="<?php echo $row->id;?>">
            <?php echo $row->lecture->course->course_name." ".$row->section_code; ?>
            </option>
        <?php } ?>
        </select>

    <input type='submit' name='submit' value='Upload List'>
 
</form>


<?php
    if (isset($_POST["submit"])) {
        global $studentData;
        global $student_section;

        $student_section = Section::includes(['lecture' => 'course'])->find_by(array('id' => $_POST["section"]));
        $studentData = array();
        $row=0;

        echo "<h5>Users to be added </h5>";

        $handle = fopen($_FILES['filename']['tmp_name'], "r");
        $headers = fgetcsv($handle, 1000, ","); ?>
        <table>
            <thead>
            <tr>
                <th>Student ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email Address</th>
            <tr>
            </thead>
            <tbody>
        <?php while (($data = fgetcsv($handle, 1000, ",")) !== false) { ?>
            <tr>
            <?php $studentData[$row] = $data;
            foreach($data as $info){?>
            
                <td><?php echo $info ?></td>
 
            <?php } ?>
            </tr>
            <?php $row++;
        } ?>
        </tbody>
    </table>
        <?php fclose($handle);

        $fileName = uniqid('studentList').".csv";
        move_uploaded_file($_FILES['filename']['tmp_name'], $fileName);

        echo "<h5> New users will be added to section ".$student_section->lecture->course->course_name." ".$student_section->section_code."</h5>";
    }
?>

<?php if (isset($_POST["submit"])) : ?>
    <form method='post'>
    <input type='submit' name='submit2' value='confirm and upload'>
    <input type="hidden" id="fileID" name="fileID" value="<?php echo $fileName?>">
    <input type="hidden" id="sectionID" name="sectionID" value="<?php echo $student_section->id?>">
    </form>

<?php endif; ?>

<br><br>
<a href="student_list.php">Return to student list page</a>


<?php include "templates/footer.php"; ?>