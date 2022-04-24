<?php
// Andréanne Chartrand-Beaudry - 29605991
// James Juan Whei Tan - 40161156
?>
<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>

<!-- TODO: Only allow TA's of this course to do this  -->
<?php
require_once "../modules/models/comment.php";

if (!(isset($_POST['commentable_id']) && isset($_POST['commentable_type']))) {
    http_response_code(422);
    die("Missing commentable_id and commentable_type");
}

// TODO: Validate the commentable_type, we really need a form validation framework
if ($_POST['commentable_type']::find_by_id($_POST['commentable_id']) == null) {
    http_response_code(422);
    die("Invalid commentable");
}

if (!isset($_POST['user_id'])) {
    http_response_code(422);
    die("Invalid user_id");
}

$comment = new Comment();
$comment->commentable_id = $_POST['commentable_id'];
$comment->commentable_type = $_POST['commentable_type'];
$comment->user_id = $_POST['user_id'];
$comment->content = $_POST['content'];

try {
    $comment->save();
    // Go back to the previous page
    header("Location:".$_SERVER['HTTP_REFERER']);
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}
