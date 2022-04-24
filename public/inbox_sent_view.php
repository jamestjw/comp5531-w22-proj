<?php
require_once '../common.php';
require_once '../modules/models/user.php';
require_once '../modules/models/email.php';
require_once '../modules/models/inbox.php';
require_once '../modules/models/sent.php';
?>

<?php
// Get the logged in user
$current_user = $_SESSION['current_user'];

// Fetch either inbox or sent, depending on what user specified
if ($_SESSION["email_view"] == "inbox") {
    $messages = Inbox::includes(["emails" => "sender"])->order(["created_at" => "desc"])->where(array("email_address" => $current_user->email));
} elseif ($_SESSION["email_view"] == "sent") {
    $messages = Sent::includes("emails")->order(["created_at" => "desc"])->where(array("email_address" => $current_user->email));
}

if (!isset($_POST['clicked']) || is_null($_POST['clicked'])) {
    foreach ($messages as $message) {
        if ($_SESSION["email_view"] == "inbox") {
            $to_from = "From: ".$message->emails->sender->email_address;
            
        } elseif ($_SESSION["email_view"] == "sent") {
            $to_from = "To: ".implode(';', array_map(function ($rec) {return $rec->email_address;}, $message->emails->receiver));
        }
        echo "
        <form method='post' class='inboxmessage'>
            <div class='inboxmessage'>
                <button name='clicked[{$message->message_id}]' class='inboxmessagebtn'>
                    <div class='inboxmessagefield inboxmessagesender'> 
                        {$to_from}
                    </div>
                    <div class='inboxmessagefield inboxmessagesubject'>
                        Subject:
                        {$message->emails->subject}
                    </div>
                    <div class='inboxmessagefield inboxmessagetimestamp'>
                        {$message->created_at}
                    </div>
                </button>
            </div>
        </form>";
    }
} else { // Display the content of the email. User has clicked on a message

    $display_message = Email::find_by_id(key($_POST['clicked']));
    if ($_SESSION["email_view"] == "inbox") {
        $to_from = "From: ".$display_message->sender->email_address;
    } elseif ($_SESSION["email_view"] == "sent") {
        $to_from = "To: ".implode(';', array_map(function ($rec) {return $rec->email_address;}, $display_message->receiver));
    }

    // Convert the php new lines to html newlines
    $content = nl2br($display_message->content);
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
            {$content}
        </div>
    </div>
    ";
}
?>
