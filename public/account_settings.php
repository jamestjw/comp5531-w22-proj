<?php
require "../modules/models/user.php";
require_once "../common.php";
?>

<?php include "templates/header.php"; ?>

<?php

function compare_passwords($p1, $p2)
{
    // Used to verify the confirmation password matches original typed password
    return $p1 === $p2;
}
?>

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
 </ul>
 <input type="submit" name="submit" value="Submit">
</form>

<?php
} else { ?>

    <h5>You need to be logged in to see your account details.</h1>

<?php } ?>

<?php include "templates/footer.php"; ?>
