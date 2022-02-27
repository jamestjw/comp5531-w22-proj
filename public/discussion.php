<?php
require_once "../modules/models/discussion.php";
require_once "../common.php";

ensure_logged_in();

if (isset($_POST['submit'])) {
    $msg = new DiscussionMessage();
    $msg->content = $_POST['content'];
    $msg->discussion_id = $_POST['discussion_id'];
    $msg->user_id = $_POST['user_id'];
    $msg->parent_id = is_numeric($_POST['replies_to']) ? $_POST['replies_to'] : null;

    try {
        $msg->save();
        header("Location: discussion.php?id=".$msg->discussion_id);
    }  catch(PDOException $error) {
        echo "<br>" . $error->getMessage();
    }
}

?>

<?php include "templates/header.php"; ?>

<?php

if (isset($_GET["id"]) && ($discussion = Discussion::find_by_id($_GET["id"]))) {
    $discussion_messages = $discussion->discussion_messages; ?>

    <div>Title: <?php echo $discussion->title; ?> </div>
    <div>Number of posts: <?php echo count($discussion_messages); ?> </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Post Author ID</th>
            <th>Content</th>
            <th>Replies to</th>
            <th>Created At</th>
        </tr>

        <?php foreach ($discussion_messages as $discussion_message) { ?>
            <tr>
                <td><?php echo $discussion_message->id; ?></td>
                <td><?php echo $discussion_message->user_id; ?></td>
                <td><?php echo $discussion_message->content; ?></td>
                <td><?php echo $discussion_message->parent_id; ?></td>
                <td><?php echo $discussion_message->created_at; ?></td>
            </tr>
        <?php } ?>
    </table>

    <div>Add new post:</div>
    <form method="post" action="discussion.php">
        <label for="content">Content</label>
        <input type="text" name="content" id="content">
        <input type="hidden" id="discussion_id" name="discussion_id" value="<?php echo $discussion->id; ?>">
        <input type="hidden" id="user_id" name="user_id" value="<?php echo $_SESSION["current_user"]->id; ?>">
        <!-- Put in actual ID of post to reply to-->
        <input type="hidden" id="replies_to" name="replies_to" value="<?php echo array_last($discussion_messages)->id ?? null; ?>">
        <input type="submit" name="submit" value="Submit">
    </form>

<?php }  else {
    echo "Invalid discussion ID.";
}

?>

<?php include "templates/footer.php"; ?>
