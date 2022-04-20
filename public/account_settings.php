<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>
<?php
require_once "../modules/models/user.php";
require_once "../common.php";
?>

<?php include "templates/header.php"; ?>

<h3>
    User Account
</h3>

<?php if (isset($_SESSION["current_user"])) {
    $user = $_SESSION["current_user"];

    if (!$user->is_password_changed) {
      ?>
      <h3>Please change your password.</h3>
      <?php
    }

    ?>

<style>
label { display: table-cell; }
input { display: table-cell; }
li    { display: table-row;}
</style>

<?php // The action field of the below form redirects the form contents to the page itself,
      //and prevents users from injecting malicious php code?>
<form method="post" style="display:table" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
 <ul>
  <li >
    <label for="first_name">First Name:  <?php echo escape($user->first_name)?></label>
    <input type="text" id="first_name" name="first_name">
  </li>
  <li>
    <label for="last_name">Last Name:  <?php echo escape($user->last_name)?></label>
    <input type="text" id="last_name" name="last_name">
  </li>
  <?php if (!is_null($user->student_id)
    && current_user_possible_roles()[0] == "student"
    && count(current_user_possible_roles()) == 1) {?>
  <li>
    <label for="student_id">Student ID: <?php echo escape($user->student_id)?></label>
    <input type="text" id="student_id" name="student_id"></input>
  </li>
  <?php } ?>
  <li>
    <label for="email">Email: <?php echo escape($user->email)?></label>
    <input type="email" id="email" name="email"></input>
  </li>
  <li>
    <label for="password">Change Password: </label>
    <input type="password" id="msg" name="password"></input>
  </li>
  <li>
    <label for="password_conf">Confirm Password: </label>
    <input type="password" id="msg" name="password_conf"></input>
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
        empty($_POST["email"]) ?: $user->email = $_POST["email"];

        if (!empty($_POST["student_id"])) {
            if (ctype_digit($_POST["student_id"])) {
                $user->student_id = $_POST["student_id"];
            } else {
                echo "The entered student ID - '".escape($_POST["student_id"]."' is not valid and should only contain digits.");
            }
        }

        if (!empty($_POST["password"])) {
            if ($_POST["password"] == $_POST["password_conf"]) {
                $user->password_digest = password_hash($_POST["password"], PASSWORD_DEFAULT);
                $user->is_password_changed = 1;
            } else {
                echo "Password and password confirmation does not match!";
            }
        }

        try {
            $user->save();
            header("refresh: 0");
        } catch (PDOException $error) {
            echo "<br>" . $error->getMessage();
        }
    }
?>

<?php include "templates/footer.php"; ?>
