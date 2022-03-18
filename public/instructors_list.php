<?php

require_once "../modules/models/user.php";
require_once "../common.php";

try {
    $result = User::where(array('is_instructor' => '1'));
} catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}
if (isset($_POST['submit'])) {
    $user = new User();
    $user->first_name = $_POST['first_name'];
    $user->last_name = $_POST['last_name'];
    $user->email = $_POST['email'];
    $user->is_admin = 0;
    $user->is_instructor = 1;
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

<?php
if ($result && count($result)) { ?>
        <h2>Instructors</h2>

    <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email Address</th>
                    <th>Hashed Password</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($result as $row) { ?>
            <tr>
                <td><?php echo escape($row->id); ?></td>
                <td><?php echo escape($row->first_name); ?></td>
                <td><?php echo escape($row->last_name); ?></td>
                <td><?php echo escape($row->email); ?></td>
                <td><?php echo escape($row->password_digest); ?></td>
                <td><?php echo escape($row->created_at);  ?> </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php } else { ?>
        <blockquote>No instructors found.</blockquote>
    <?php } 
?> 

<?php if (isset($_POST['submit']) && $create_success) { ?>
    <blockquote><?php echo $_POST['first_name']; ?> successfully added.</blockquote>
    <?php header("refresh: 1")?>
<?php } ?>

<h2>Add instructor</h2>

<form method="post">
    <label for="first_name">First Name</label>
    <input type="text" name="first_name" id="first_name">
    <label for="last_name">Last Name</label>
    <input type="text" name="last_name" id="last_name">
    <label for="password">Password</label>
    <input type="password" name="password" id="password">
    <label for="email">Email Address</label>
    <input type="text" name="email" id="email">
    <input type="submit" name="submit" value="Submit">
</form>

<br><br>

<a href="index.php">Back to home</a>

<?php include "templates/footer.php"; ?>