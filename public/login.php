<?php

require "../modules/models/user.php";
require_once "../common.php";
require_once "../modules/models/loggedin.php";

maybe_session_start();

// If there is already a logged in user, redirect to index page
if (isset($_SESSION["current_user"])) {
    header("Location:  ./index.php");
}

if (count($_POST) > 0) {
    $is_success = 0;
    $user = User::find_by_email($_POST["email"]);

    if (isset($user)) {
        if (password_verify($_POST["password"], $user->password_digest)) {
            $is_success = 1;
        }
    }

    if ($is_success == 0) {
        $_SESSION["error_message"] = "Invalid email or Password!";
    } else {
        // Create user login token
        $loginToken = new Loggedin();
        $loginToken->user_digest = md5(time());
        $loginToken->user_id = $user->id;

        // Check if user id already exists in loggedin table. If it does, delete all entries for the user id before saving new token
        if (Loggedin::find_by_user_id($user->id)) {
            // TODO: Delete the auth token for the user that already exists.
        }

        // Add token to logedin table
        try {
            $loginToken->save();
        } catch (PDOException $error) {
            echo "<br>" . $error->getMessage();
        }

        // Register login token in session variable
        $_SESSION["AuthKey"] = $loginToken->user_digest;
        $_SESSION["current_user"] = $user;

        header("Location:  ./index.php");
    }
}
?>

<?php include "templates/header.php"; ?>

<form name="frmUser" method="post" action="">
	<table border="0" cellpadding="10" cellspacing="1" width="500" align="center" class="tblLogin">
		<tr class="tableheader">
		<td align="center" colspan="2">Enter Login Details</td>
		</tr>
		<tr class="tablerow">
		<td>
		<input type="text" name="email" placeholder="Email" class="login-input"></td>
		</tr>
		<tr class="tablerow">
		<td>
		<input type="password" name="password" placeholder="Password" class="login-input"></td>
		</tr>
		<tr class="tableheader">
		<td align="center" colspan="2"><input type="submit" name="submit" value="Submit" class="btnSubmit"></td>
		</tr>
	</table>
</form>

<?php include "templates/footer.php"; ?>