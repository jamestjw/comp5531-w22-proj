
<?php 
require_once "../modules/models/user.php";
require_once "../common.php";

    if (isset($_POST['submit2'])) 
	{
        
		$students = fopen('studentList.csv', 'r');
        $count = 0;
		while (($studentData = fgetcsv($students, 1000, ",")) !== FALSE) 
        {
                $user = new User();
                $user->student_id = $studentData[0];
                $user->first_name = $studentData[1];
                $user->last_name = $studentData[2];
                $user->email = $studentData[3];
                $user->is_admin = 0;
                $user->is_instructor = 0;
                $user->password_digest = password_hash("welcome", PASSWORD_DEFAULT);
    
                try {
                    $user->save();
                    $count++;
                    $create_success = true;
                }  catch(PDOException $error) {
                     echo "<br>" . $error->getMessage();
                }
            
    
        }
        
        
        fclose($students);
        $filePath = 'studentList.csv';
        unlink($filePath);

	}
?>

<?php include "templates/header.php"; ?>

<?php
    if(isset($_POST['submit2']) && $create_success){
        echo "<h3> $count Students added successfully</h3><br>";
    }
?>

<form enctype='multipart/form-data' action='' method='post'>

    <h2>
        <label>Upload Student List</label> 
    </h2>
    <br>
    <label for="filename">Select File</label>
    <input size='50' type='file' name='filename' accept=".csv">
    </br>

    <input type='submit' name='submit' value='Upload List'>
 
</form>


<?php
    if(isset($_POST["submit"])){

        global $studentData;
        $studentData = array();
        $row=0;

        echo "<h5>Users to be added </h5>";

        $handle = fopen($_FILES['filename']['tmp_name'], "r");
		$headers = fgetcsv($handle, 1000, ",");
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
		{
            $studentData[$row] = array();

            $num = count($data);
            for ($c=0; $c<$num; $c++){
                echo $data[$c] . ", ";
                $studentData[$row][$c] = $data[$c];
            }
            echo "<br>";
            
            $row++;
            
        }
        fclose($handle);

        $fp = fopen('studentList.csv', 'w');
        foreach($studentData as $fields){
            fputcsv($fp, $fields);
        }

        fclose($fp);
     
    }
?>

<?php if(isset($_POST["submit"])) : ?>
    <form method='post'>
    <input type='submit' name='submit2' value='confirm and upload'>
    </form>

<?php endif; ?>





<?php include "templates/footer.php"; ?>