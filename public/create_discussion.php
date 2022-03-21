<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>

<?php

require_once "../modules/models/discussion.php";
require_once "../modules/models/discussion_message.php";
require_once "../common.php";

if (isset($_POST["submit"])) {
    $discussion = new Discussion();
    $discussion->title = $_POST["title"];
    $discussion->user_id = $_SESSION["current_user"]->id;

    // TODO: Check if the user has permission to do this
    if (isset($_POST['discussable_id']) && isset($_POST['discussable_type'])) {
        if ($_POST['discussable_type']::find_by_id($_POST['discussable_id']) == null) {
            die("Invalid discussable");
        }
        $discussion->discussable_id = $_POST['discussable_id'];
        $discussion->discussable_type = $_POST['discussable_type'];
    }

    try {
        $discussionMessage = new DiscussionMessage();
        $discussionMessage->user_id = $_SESSION["current_user"]->id;
        $discussionMessage->discussion_id = $discussion->id;
        $discussionMessage->content = $_POST["content"];

        $discussion->discussion_messages = array($discussionMessage);
        $discussion->save();

        header("Location:".$_SERVER['HTTP_REFERER']);
    } catch (PDOException $error) {
        echo "<br>" . $error->getMessage();
    }
}
?>
