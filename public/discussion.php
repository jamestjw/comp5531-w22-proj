<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>

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

if (isset($_GET["id"]) && ($discussion = Discussion::includes(["discussion_messages" => ["poll"=>"poll_options", "user"=>[]]])->find_by_id($_GET["id"]))) {
    $discussion_messages = $discussion->discussion_messages; ?>

    <div>Title: <?php echo $discussion->title; ?> (#<?php echo $discussion->id ?>) </div>
    <div>Number of posts: <?php echo count($discussion_messages); ?> </div>
    <div>Author: <?php echo $discussion->user->first_name ?> </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Author</th>
            <th>Content</th>
            <th>Replies to</th>
            <th>Created At</th>
            <th>Poll</th>
            <th></th> <!-- Reply button -->
            <th></th> <!-- Reply form -->
        </tr>

        <?php foreach ($discussion_messages as $discussion_message) { ?>
            <tr>
                <td><?php echo $discussion_message->id; ?></td>
                <td><?php echo $discussion_message->user->first_name; ?></td>
                <td><?php echo $discussion_message->content; ?></td>
                <td><?php echo $discussion_message->parent_id; ?></td>
                <td><?php echo $discussion_message->created_at; ?></td>
                <td>

                <?php
                if (($poll=$discussion_message->poll)) {
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
                } else {
                    echo "N/A";
                }
                ?>
                
                </td>
                <!-- Reply button -->
                <td><a href="discussions.php" class="replyMessage">Reply</a></td>
                <!-- Reply form -->
                <td>
                    <form method="post" action="discussion.php" class="replyMessageForm" style="display: none;">
                        <label for="content">Content</label>
                        <input type="text" name="content" id="content">
                        <input type="hidden" id="discussion_id" name="discussion_id" value="<?php echo $discussion->id; ?>">
                        <input type="hidden" id="user_id" name="user_id" value="<?php echo $_SESSION["current_user"]->id; ?>">
                        <!-- Put in actual ID of post to reply to-->
                        <input type="hidden" id="replies_to" name="replies_to" value="<?php echo $discussion_message->id ?>">
                        <input type="submit" name="submit" value="Submit">
                    </form>
                </td>
            </tr>
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
