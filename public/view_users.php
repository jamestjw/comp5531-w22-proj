<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>

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

?>

<?php include "templates/header.php"; ?>

<?php
if ($result && count($result)) { ?>
        <h2>Users</h2>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email Address</th>
                    <th>Hashed Password</th>
                    <th>Student ID</th>
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
                <td><?php echo escape($row->student_id); ?></td>
                <td><?php echo escape($row->created_at);  ?> </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php } else { ?>
        <blockquote>No users found.</blockquote>
    <?php }
?> 

<a href="index.php">Back to home</a>

<?php include "templates/footer.php"; ?>