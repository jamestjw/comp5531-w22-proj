<?php

require_once "../modules/models/poll.php";
require_once "../modules/models/poll_option.php";
require_once "../modules/models/poll_option_user.php";
require_once "../common.php";

ensure_logged_in();

if (!isset($_POST["poll_id"]) || !($poll = Poll::find_by_id($_POST["poll_id"]))) {
    http_response_code(404);
    set_error_and_go_back("Resource not found.");
}

if ((strtotime($poll->created_at) + $poll->duration) <= time()) {
    http_response_code(422);
    set_error_and_go_back("Poll has expired.");
}

if (
    !isset($_POST['vote_option']) ||
    !($vote_option = PollOption::find_by_id($_POST['vote_option'])) ||
    ($vote_option->poll_id != $poll->id)
) {
    http_response_code(422);
    set_error_and_go_back("Invalid option.");
}

if ($poll->user_has_voted($_SESSION['current_user_id'])) {
    http_response_code(422);
    set_error_and_go_back("User has already voted.");
}

$obj = new PollOptionUser();
$obj->user_id = $_SESSION['current_user_id'];
$obj->option_id = $vote_option->id;

try {
    $obj->save();
} catch (PDOException $error) {
    http_response_code(500);
    set_error_and_go_back($error->getMessage());
}
