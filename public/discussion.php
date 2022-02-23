<?php include "templates/header.php"; ?>


<?php

require "../modules/models/discussion.php";
require_once "../common.php";

ensure_logged_in();

if (!isset($_GET["id"])) {
    echo "Invalid discussion ID.";
} else { 
    $discussion = Discussion::find_by_id($_GET["id"]);
    $discussion_messages = $discussion->discussion_messages();
    
    # TODO: Make this more DRY
    if (!isset($discussion)) {
        echo "Invalid discussion ID.";
    } else {  ?>
        <div>Title: <?php echo $discussion->title; ?> </div>
        <div>Number of posts: <?php echo count($discussion_messages); ?> </div>

        <table>
            <tr>
                <th>Post Author ID</th>
                <th>Content</th>
                <th>Created At</th>
            </tr>

            <?php foreach ($discussion_messages as $discussion_message) { ?>
                <tr>
                    <td><?php echo $discussion_message->user_id; ?></td>
                    <td><?php echo $discussion_message->content; ?></td>
                    <td><?php echo $discussion_message->created_at; ?></td>
                </tr>
            <?php } ?>
        </table>
<?php } 
    }
?>

<?php include "templates/footer.php"; ?>
