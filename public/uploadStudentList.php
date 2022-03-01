
<?php 
require_once "../modules/models/user.php";
require_once "../common.php";

    if (isset($_POST['submit'])) 
	{

		$handle = fopen($_FILES['filename']['tmp_name'], "r");
		$headers = fgetcsv($handle, 1000, ",");
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
		{
			$user = new User();
            $user->student_id = $data[0];
            $user->first_name = $data[1];
            $user->last_name = $data[2];
            $user->email = $data[3];
            $user->is_admin = 0;
	        $user->is_instructor = 0;
            $user->password_digest = password_hash("welcome", PASSWORD_DEFAULT);

            try {
                $user->save();
                $create_success = true;
            }  catch(PDOException $error) {
                 echo "<br>" . $error->getMessage();
            }
		}
fclose($handle);
	}
?>

<?php include "templates/header.php"; ?>

<form enctype='multipart/form-data' action='' method='post'>

    <h2>
        <label>Upload Student List</label> 
    </h2>
    <br>

    <input size='50' type='file' name='filename' accept=".csv">
    </br>
    <input type='submit' name='submit' value='Upload List'>
 
</form>

<?php include "templates/footer.php"; ?>