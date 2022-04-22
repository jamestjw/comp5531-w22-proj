<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>
<link rel="stylesheet" href="css/crsmgr_table_style.css">
<?php

require_once "../modules/models/user.php";
require_once "../common.php";

try {
    // TODO: Make this get Users that are not soft deleted
    // TODO: Maybe refactor this page and other pages that display users to reuse the same code.
    $result = User::getAll();
} catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}
$user_role = get_current_role();

if (isset($_POST['delete_user'])) {
    $user_to_delete = User::find_by(array('id' => $_POST['user_id']));
    $delete_sucess = false;
    
    if (!$user_to_delete->get_role("admin")){
        $user_to_delete->delete();
        $delete_sucess = true;
    }else {
        echo "Admin cannot be deleted";
    }

    if($delete_sucess) {
        echo "User successfully deleted";
        header("refresh: 1");
    }
}


?>

<?php include "templates/header.php"; ?>

<?php if($user_role == "admin") { ?>
<?php
if ($result && count($result)) { ?>
        <h2>Users</h2>

        <table>
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email Address</th>
                    <th>Instructor</th>
                    <th>TA</th>
                    <th>Student ID</th>
                    <th>Delete user</th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($result as $row) { ?>
            <tr>
                <td><?php echo escape($row->first_name); ?></td>
                <td><?php echo escape($row->last_name); ?></td>
                <td><?php echo escape($row->email); ?></td>
                <td><?php echo ( $row->get_role("instructor") ? "yes" : "no"); ?></td>
                <td><?php echo ( $row->get_role("ta") ? "yes" : "no"); ?></td>
                <td><?php echo escape($row->student_id); ?></td>
                <td><form method="post"><input type="hidden" name='user_id' value="<?php echo $row->id; ?>"><input type="submit" name="delete_user" value="delete">
                    </form> </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php } else { ?>
        <blockquote>No users found.</blockquote>
    <?php }
?> 
<?php } else { ?>
    <blockquote>You don't have the credentials to view this page</blockquote>
<?php } ?>

<a href="index.php">Back to home</a>

<?php include "templates/footer.php"; ?>