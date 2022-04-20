<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>

<link rel="stylesheet" href="css/discussion.css">


<?php
require_once "../modules/models/discussion.php";
require_once "../modules/models/poll.php";
require_once "../modules/models/poll_option.php";
require_once "../common.php";

if (isset($_POST['submit'])) {
    $msg = new DiscussionMessage();
    $msg->content = $_POST['content'];
    $msg->discussion_id = $_POST['discussion_id'];
    $msg->user_id = $_POST['user_id'];
    $msg->parent_id = $_POST['replies_to'] ?? null;

    $poll_option_count = intval($_POST["option_count"] ?? 0);

    if ($poll_option_count > 0) {
        $poll = new Poll();
        $poll->user_id = $_POST['user_id'];
        $poll->duration = $_POST["duration"];
        $poll->title = $_POST["poll_title"];
        $poll_options = array();

        for ($i = 1; $i <= $poll_option_count; $i++) {
            $poll_option = new PollOption();
            $poll_option->content = $_POST["option_".$i];
            array_push($poll_options, $poll_option);
        }

        $poll->poll_options = $poll_options;
        $msg->poll = $poll;
    }

    try {
        $msg->save();
        header("Location: discussion.php?id=".$msg->discussion_id);
    } catch (PDOException $error) {
        echo "<br>" . $error->getMessage();
    }
}

?>

<?php include "templates/header.php"; ?>

<?php

if (isset($_GET["id"]) && ($discussion = Discussion::includes(["discussion_messages" => ["poll"=>"poll_options", "user"=>[], "comments" => "user"]])->find_by_id($_GET["id"]))) {
    $discussion_messages = $discussion->discussion_messages; ?>
    
    <table width=100% class="disctable">
    <colgroup>
       <col span="1" style="width: 30%;">
       <col span="1" style="width: 70%;">
    </colgroup>
    <tr>
    <th>Post Info</th>
    <th>Post Body</th>
    </tr>
    <?php foreach ($discussion_messages as $discussion_message) {?>
        <tr>
            <td>
            <?php echo "Post ID:".$discussion_message->id."<br>"; ?>
            <?php echo "Post Author:".$discussion_message->user->get_full_name()."<br>"; ?>
            <?php echo "Parent ID:".$discussion_message->parent_id."<br>"; ?>
            <?php echo "Created at:".$discussion_message->created_at."<br>"; ?>
            </td>
            <td>
            <!-- Polls -->
            <?php 
            if (!is_null($discussion_message->parent_id)) {
                echo "<Replies to: {$discussion_message->parent_id}> ";
            }
            ?>
            <?php echo $discussion_message->content."<br><br>"; ?>

            <?php
            if (($poll=$discussion_message->poll)) { ?>
                <?php echo "<u>Poll ID:".$poll->id."</u><br>"; ?>
                <?php
                if ($poll->user_has_voted($_SESSION['current_user_id'])) {
                    $poll_result = PollResult::from_poll($poll); ?>
                    <ul>
                        <?php foreach ($poll->poll_options as $option) { ?>
                            <li>
                            <?php echo sprintf("%s - %d votes - %.2f%%", $option->content, $poll_result->votes[$option->id][0], $poll_result->votes[$option->id][1] * 100) ?></li>
                            </li>
                        <?php } ?>
                    </ul>
                    <?php
                } else { ?>
                    <form method="post" action="poll.php" id="pollVote">
                        <input type="hidden" id="poll_id" name="poll_id" value="<?php echo $poll->id; ?>">
                        <p>Options:</p>
                        <?php foreach ($poll->poll_options as $option) { ?>
                            <input type="radio" id="vote_option_<?php echo $option->id ?>" name="vote_option" value="<?php echo $option->id ?>">
                            <label for="vote_option_<?php echo $option->id ?>"><?php echo $option->content ?></label><br>
                        <?php }?>
                        <input type="submit" name="submit" value="Vote">
                    </form>
                <?php }
            }?>
            <?php
            // TODO: Only display this to TA's of this course!
            if (true) {
                $user_id = $_SESSION["current_user"]->id;
                $commentable_id = $discussion_message->id;
                $commentable_type = "DiscussionMessage";
                if (get_current_role() == "instructor" || get_current_role() == "ta") {
                    include "new_comment_form.php";
                }
            }
            ?>
            </td>
        </tr>
        <!-- Comments -->
        
        <?php
        if (!empty($comments=$discussion_message->comments)) { ?>
                <?php
                foreach ($comments as $comment) { ?>
                    <tr>
                    <td>

                    </td>
                    <td>
                    <?php echo sprintf("%s - %s - %s", $comment->content, $comment->user->first_name, $comment->created_at); ?>
                    </td>
                    </tr>
                <?php }
                ?>

        <?php } else {
                    echo "N/A";
                }
        ?>

        
        


    <?php } ?>
    </table>
    <div>Add new post:</div>
    <form method="post" action="discussion.php">
        <label for="content">Content</label>
        <input type="text" name="content" id="content">
        <input type="hidden" id="discussion_id" name="discussion_id" value="<?php echo $discussion->id; ?>">
        <input type="hidden" id="user_id" name="user_id" value="<?php echo $_SESSION["current_user"]->id; ?>">

        <button type="button" id="displayAddPoll">Insert poll</button>
        <div id="pollForm" style="display: none;">
            <p>Insert poll</p>
            <div><label for="poll_title">Poll title</label></div>
            <div><input type="text" name="poll_title" id="poll_title"></div>

            <div><label for="duration">Ends in (seconds)</label></div>
            <div><input type="number" name="duration" id="duration"></div>

            <input type="hidden" id="option_count" name="option_count" value="0">
            <button type="button" id="addPollOption">Add option</button>
        </div>

        <input type="submit" name="submit" value="Submit">
    </form>

<?php
} else {
    echo "Invalid discussion ID.";
}

?>

<?php include "templates/footer.php"; ?>
<script src = "../js/discussion.js"></script>
