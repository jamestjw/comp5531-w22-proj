<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>
<?php include "templates/header.php"; ?>

<?php if(get_current_role() == 'admin' || get_current_role() == 'instructor') { ?>
<?php

require_once "../modules/models/user.php";
require_once "../common.php";

try {
    $students = User::where(array('is_instructor' => '0', 'is_admin' => '0'));
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

?>

<?php
if ($students && count($students)) { ?>
        <h2>All Students</h2>

    <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email Address</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($students as $row) { ?>
            <tr>
                <td><?php echo escape($row->id); ?></td>
                <td><?php echo escape($row->first_name); ?></td>
                <td><?php echo escape($row->last_name); ?></td>
                <td><?php echo escape($row->email); ?></td>
                <td><?php echo escape($row->created_at);  ?> </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php } else { ?>
        <blockquote>No Students found.</blockquote>
    <?php }?>
    
    <br><a href="uploadStudentList.php">Upload Student list </a>

<?php } else {?>
    <h2>You do not have the credentials to view this page.</h2>
<?php }?>