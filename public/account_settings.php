<?php
require_once "../modules/models/user.php";
require_once "../common.php";
?>

<?php include "templates/header.php"; ?>

<h3>
    User Account
</h3>

<?php if (isset($_SESSION["current_user"])) {
    $user = $_SESSION["current_user"] ?>

<style>
label { display: table-cell; }
input { display: table-cell; }
li    { display: table-row;}
</style>

<form method="post" style="display:table">
 <ul>
  <li >
    <label for="first_name">First Name:  <?php echo escape($user->first_name)?></label>
    <input type="text" id="first_name" name="first_name">
  </li>
  <li>
    <label for="last_name">Last Name:  <?php echo escape($user->last_name)?></label>
    <input type="text" id="last_name" name="last_name">
  </li>
  <li>
    <label for="student_id">Student ID: <?php echo escape($user->student_id)?></label>
    <input type="text" id="student_id" name="student_id"></input>
  </li>
  <li>
    <label for="email">Email: <?php echo escape($user->email)?></label>
    <input type="email" id="email" name="email"></input>
  </li>
  <li>
    <label for="password">Change Password: </label>
    <input type="text" id="msg" name="password"></input>
  </li>
  <li>
    <label for="password_conf">Confirm Password: </label>
    <input type="text" id="msg" name="password_conf"></input>
  </li>
 </ul>
 <input type="submit" name="submit" value="Submit">
</form>

<?php
} else { ?>

    <h5>You need to be logged in to see your account details.</h1>

<?php } ?>

<?php
if (isset($_POST['submit'])) {

  empty($_POST["first_name"]) ?: $user->first_name = $_POST["first_name"];
  empty($_POST["last_name"]) ?: $user->last_name = $_POST["last_name"];
  empty($_POST["student_id"]) ?: $user->student_id = $_POST["student_id"];
  empty($_POST["email"]) ?: $user->email = $_POST["email"];

  if (!empty($_POST["password"]) && ($_POST["password"] == $_POST["password_conf"])) {
    $user->password_digest = password_hash($_POST["password"], PASSWORD_DEFAULT);
  } else {
    echo "Password and password confirmation does not match!";
  }

  $user->save();
}
?>

<?php include "templates/footer.php"; ?>
