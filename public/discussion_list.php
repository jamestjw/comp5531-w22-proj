<?php
// James Juan Whei Tan - 40161156
// Andréanne Chartrand-Beaudry - 29605991
?>
<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>

<?php

// OPTIONAL: $discussable_id and $discussable_type should be defined by the scripts that include this one

if ($discussions && count($discussions)) { ?>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($discussions as $row) { ?>
            <tr>
                <td><a href="discussion.php?id=<?php echo $row->id; ?>"><?php echo escape($row->title); ?></a></td>
                <td><?php echo escape($row->user->get_full_name()); ?></td>
                <td><?php echo escape($row->created_at);  ?> </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php } else { ?>
        <blockquote>No discussions found.</blockquote>
    <?php }
?> 
<br>

<?php
    if (isset($discussable_id) && isset($discussable_type)) { ?>
    <h5>Add a discussion</h5>

    <form method="post" action="create_discussion.php">
        <label for="title">Title</label>
        <input type="text" name="title" id="title">
        <label for="content">Content</label>
        <input type="text" name="content" id="content">
        <input type="hidden" id="discussable_id" name="discussable_id" value="<?php echo $discussable_id; ?>">
        <input type="hidden" id="discussable_type" name="discussable_type" value="<?php echo $discussable_type; ?>">

        <input type="submit" name="submit" value="Submit">
    </form>
<?php } ?>
