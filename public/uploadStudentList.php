
<?php
require_once "../modules/models/user.php";
require_once "../common.php";

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
        }


        fclose($students);
        unlink($_POST["fileID"]);
    }
?>

<?php include "templates/header.php"; ?>

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

    <input type='submit' name='submit' value='Upload List'>
 
</form>


<?php
    if (isset($_POST["submit"])) {
        global $studentData;
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
    }
?>

<?php if (isset($_POST["submit"])) : ?>
    <form method='post'>
    <input type='submit' name='submit2' value='confirm and upload'>
    <input type="hidden" id="fileID" name="fileID" value="<?php echo $fileName?>">
    </form>

<?php endif; ?>





<?php include "templates/footer.php"; ?>