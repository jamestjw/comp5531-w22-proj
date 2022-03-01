<?php

require_once "../modules/models/discussion.php";
require_once "../modules/models/discussion_message.php";
require_once "../common.php";

ensure_logged_in();

if (isset($_POST["submit"])) {
    $discussion = new Discussion();
    $discussion->title = $_POST["title"];
    $discussion->user_id = $_SESSION["current_user"]->id;

    try {
        $discussionMessage = new DiscussionMessage();
        $discussionMessage->user_id = $_SESSION["current_user"]->id;
        $discussionMessage->discussion_id = $discussion->id;
        $discussionMessage->content = $_POST["content"];

        $discussion->discussion_messages = array($discussionMessage);
        $discussion->save();

        header("Location: discussion.php?id={$discussion->id}");
    } catch (PDOException $error) {
        echo "<br>" . $error->getMessage();
    }
}
?>

<?php include "templates/header.php"; ?>

<h2>Add a discussion</h2>

<form method="post">
    <label for="title">Title</label>
    <input type="text" name="title" id="title">
    <label for="content">Content</label>
    <input type="text" name="content" id="content">
    <input type="submit" name="submit" value="Submit">
</form>

<a href="index.php">Back to home</a>

<?php include "templates/footer.php"; ?>