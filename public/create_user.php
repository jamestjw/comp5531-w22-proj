<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>

<?php

require_once "../modules/models/user.php";

if (isset($_POST['submit'])) {
    $user = new User();
    $user->first_name = $_POST['first_name'];
    $user->last_name = $_POST['last_name'];
    $user->email = $_POST['email'];
    $user->student_id = $_POST['student_id'];
    $user->is_admin = 0;
    $user->is_instructor = 0;
    $user->is_ta = 0;
    $user->is_password_changed = 0;
    $user->password_digest = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $user->save();
        $create_success = true;
    } catch (PDOException $error) {
        echo "<br>" . $error->getMessage();
    }
}
?>

<?php include "templates/header.php"; ?>

<?php if (isset($_POST['submit']) && $create_success) { ?>
    <blockquote><?php echo $_POST['first_name']; ?> successfully added.</blockquote>
<?php } ?>

<h2>Add a user</h2>

<form method="post">
    <label for="first_name">First Name</label>
    <input type="text" name="first_name" id="first_name">
    <label for="last_name">Last Name</label>
    <input type="text" name="last_name" id="last_name">
    <label for="password">Password</label>
    <input type="password" name="password" id="password">
    <label for="email">Email Address</label>
    <input type="text" name="email" id="email">
    <label for="student_id">Student ID</label>
    <input type="text" name="student_id" id="student_id">
    <input type="submit" name="submit" value="Submit">
</form>

<a href="index.php">Back to home</a>

<?php include "templates/footer.php"; ?>