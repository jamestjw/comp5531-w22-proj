<?php
require_once '../common.php';
require_once '../modules/models/user.php';
require_once '../modules/models/email.php';
require_once '../modules/models/inbox.php';
require_once '../modules/models/sent.php';
?>

<?php
if (isset($_SESSION['current_user'])) {
    $current_user = $_SESSION['current_user'];
} else {
    echo 'Error fetching logged in user.';
    header('Location: login.php');
}


// if ($_SESSION["email_view"] == "inbox") {
//     $box_entries
// }
$inbox_entries = Inbox::where(array('email_address' => $current_user->email));
// Sort the messages by sent date/time
$by_created_at = array();
foreach($inbox_entries as $im) {
    $by_created_at[$im->id] = $im->created_at;
}
array_multisort($by_created_at, SORT_DESC, $inbox_entries);

if(!isset($_POST['clicked']) || is_null($_POST['clicked'])) {
    foreach($inbox_entries as $in) {
        $message = Email::find_by_id($in->message_id);
        echo "
        <form method='post' class='inboxmessage'>
            <div class='inboxmessage'>
                <button name='clicked[{$in->message_id}]' class='inboxmessagebtn'>
                    <div class='inboxmessagefield inboxmessagesender'>
                        From: 
                        {$message->get_sender()}
                    </div>
                    <div class='inboxmessagefield inboxmessagesubject'>
                        Subject:
                        {$message->subject}
                    </div>
                    <div class='inboxmessagefield inboxmessagetimestamp'>
                        {$message->created_at}
                    </div>
                </button>
            </div>
        </form>";
    } 
} else { // Display the content of the email
    $display_message = Email::find_by_id(key($_POST['clicked']));
    echo "
    <div class='messagecontainer'>
        <div class='messagesender'>
            From:
            {$display_message->get_sender()}
        </div>
        <div class='messagesubject'>
            Subject:
            {$display_message->subject}
        </div>
        <div class='messagetimestamp'>
            Delivered on:
            {$display_message->created_at}
        </div>
        <div class='messagecontent'>
        {$display_message->content}
        </div>
    </div>
    ";
}
?>
