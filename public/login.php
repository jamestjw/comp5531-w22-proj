<?php include "templates/header.php"; ?>

<?php

require "../modules/models/user.php";

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
        $_SESSION["current_user"] = $user;
        header("Location:  ./index.php");
    }
}
?>

<form name="frmUser" method="post" action="">
	<div class="message"><?php if(isset($_SESSION["error_message"]) && $_SESSION["error_message"]!="") { echo $_SESSION["error_message"]; } ?></div>
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