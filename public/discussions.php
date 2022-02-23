<?php

require "../modules/models/discussion.php";

try  {
    $result = Discussion::getAll();
} catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}

?>

<?php include "templates/header.php"; ?>

<?php 
if ($result && count($result)) { ?>
        <h2>Discussions</h2>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>User ID</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($result as $row) { ?>
            <tr>
                <td><?php echo escape($row->id); ?></td>
                <td><a href="discussion.php?id=<?php echo $row->id; ?>"><?php echo escape($row->title); ?></a></td>
                <td><?php echo escape($row->user_id); ?></td>
                <td><?php echo escape($row->created_at);  ?> </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php } else { ?>
        <blockquote>No discussions found.</blockquote>
    <?php } 
?> 

<p><a href="create_discussion.php">Create new discussion</a></p>
<p><a href="index.php">Back to home</a></p>

<?php include "templates/footer.php"; ?>