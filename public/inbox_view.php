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


if ($_SESSION["email_view"] == "inbox") {
    $box_entries = Inbox::where(array('email_address' => $current_user->email));
} elseif ($_SESSION["email_view"] == "sent") {
    $box_entries = Sent::where(array('email_address' => $current_user->email));
}

// Sort the messages by sent date/time
$by_created_at = array();
foreach($box_entries as $be) {
    $by_created_at[$be->id] = $be->created_at;
}
array_multisort($by_created_at, SORT_DESC, $box_entries);

if(!isset($_POST['clicked']) || is_null($_POST['clicked'])) {
    foreach($box_entries as $be) {
        $message = Email::find_by_id($be->message_id);
        if ($_SESSION["email_view"] == "inbox") {
            $to_from = "From: ".$message->get_sender();
        } elseif ($_SESSION["email_view"] == "sent") {
            $to_from = "To: ".implode(';',$message->get_all_receivers());
        }
        echo "
        <form method='post' class='inboxmessage'>
            <div class='inboxmessage'>
                <button name='clicked[{$be->message_id}]' class='inboxmessagebtn'>
                    <div class='inboxmessagefield inboxmessagesender'> 
                        {$to_from}
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
    if ($_SESSION["email_view"] == "inbox") {
        $to_from = "From: ".$display_message->get_sender();
    } elseif ($_SESSION["email_view"] == "sent") {
        $to_from = "To: ".implode(';',$display_message->get_all_receivers());
    }

    echo "
    <div class='messagecontainer'>
        <div class='messagesender'>
            {$to_from}
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
