<?php
// Andréanne Chartrand-Beaudry - 29605991
// James Juan Whei Tan - 40161156
// Christopher Almeida Neves - 27521979
?>
<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>
<?php require_once(dirname(__FILE__)."/../common.php"); ?>
<?php $user_role = get_current_role()?>
<?php include "templates/header.php"; ?>
<?php if($user_role == "admin") { ?>
<?php

require_once "../modules/models/user.php";

if (isset($_POST['submit'])) {
    $create_success = false;

    $required_attrs = ['first_name', 'last_name', 'email', 'password', 'role'];

    foreach ($required_attrs as $attr) {
        if (!array_key_exists($attr, $_POST)) {
            set_error_and_go_back("Missing argument $attr.");
        }
    }

    $role = $_POST['role'];
    if (!in_array($role, ['student', 'ta', 'instructor'])) {
        set_error_and_go_back("$role is not a valid role.");
    }

    $user = new User();
    $user->first_name = $_POST['first_name'];
    $user->last_name = $_POST['last_name'];
    $user->email = $_POST['email'];
    $user->set_role($role);
    $user->is_password_changed = 0;
    $user->password_digest = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if ($role == 'student') {
        if (!array_key_exists('student_id', $_POST) || empty($_POST['student_id'])) {
            set_error_and_go_back("Invalid student_id for student role");
        }
        $user->student_id = $_POST['student_id'];
    }
    
    try {
        $user->save();
        $create_success = true;
    } catch (PDOException $error) {
        echo "<br>" . $error->getMessage();
    }
}
?>

<?php if (isset($_POST['submit']) && $create_success) { ?>
    <blockquote><?php echo $_POST['first_name']; ?> successfully added.</blockquote>
<?php } ?>

<h2>Add a user</h2>

<form method="post">
    <label for="first_name">First Name</label>
    <input type="text" name="first_name" id="first_name" required>
    <label for="last_name">Last Name</label>
    <input type="text" name="last_name" id="last_name" required>
    <label for="email" required>Email Address</label>
    <input type="email" name="email" id="email" required>
    <label for="student_id">Student ID (leave blank if not a student)</label>
    <input type="text" name="student_id" id="student_id">
    <select name="role" id="role" required>
        <option value="student">Student</option>
        <option value="ta">TA</option>
        <option value="instructor">Instructor</option>
    </select>
    <input type="hidden" name="password" values="welcome">
    <input type="submit" name="submit" value="Submit">
</form>
<?php } else { ?>
<blockquote>You do not have the credentials to view this page</blockquote>
<?php }?>
<a href="view_users.php">Back to user list</a>

<?php include "templates/footer.php"; ?>