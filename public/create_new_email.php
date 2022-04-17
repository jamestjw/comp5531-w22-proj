<?php
require_once "../common.php";
require_once "../modules/models/user.php";
require_once "../modules/models/email.php";
require_once "../modules/models/inbox.php";
require_once "../modules/models/sent.php";
?>

<?php
// Helper function to simply delete all objects contained in an array
function delete_all($array)
{
    foreach ($array as $elem) {
        $elem->delete();
    }
}

if (isset($_POST["submit"])) {
    // Get the logged in user
    if (isset($_SESSION["current_user"])) {
        $current_user = $_SESSION["current_user"];
    } else {
        echo "Error fetching logged in user.";
        header("Location: login.php");
    }

    // Split "To" field by ; delimiter to obtain all receiving emails
    $recipients = explode(";", $_POST["recipient_box"]);

    // Validate each email.
    $valid_emails = array();
    $invalid_emails = array();
    foreach ($recipients as $r) {
        if (User::find_by_email($r) !== null) {
            array_push($valid_emails, $r);
        } else {
            array_push($invalid_emails, $r);
        }
    }

    // Create new email object
    $email = new Email();
    $email->subject = $_POST["subject_box"];
    $email->content = $_POST["content"];

    try {
        $email->save();
    } catch (PDOException $error) {
        echo "<br>" . $error->getMessage();
    }

    // Add entry in inbox table for each user in "To" field
    $saved_inboxes = array(); // There needs to be a better way to undo transactions. Maybe update the record class.
    foreach ($valid_emails as $val) {
        $inbox = new Inbox();
        $inbox->email_address = $val;
        $inbox->message_id = $email->id;

        try {
            $inbox->save();
            array_push($saved_inboxes, $inbox);
        } catch (PDOException $error) {
            echo "<br>" . $error->getMessage();
            $email->delete();
            delete_all($saved_inboxes);
        }
    }

    // Add entry in sent table for current_user->email
    $sent = new Sent();
    $sent->email_address = $current_user->email;
    $sent->message_id = $email->id;

    try {
        $sent->save();
    } catch (PDOException $error) {
        echo "<br>" . $error->getMessage();
        $email->delete();
        delete_all($saved_inboxes);
    }

    // Tell user which emails could not be sent due to invalid addresses
    if (!empty($invalid_emails)) {
        // This can probably be done more elegantly
        echo "<br>"."This message could not be sent to the following email addresses as they don't exist internally:"."<br>";
        foreach ($invalid_emails as $inv) {
            echo $inv . "\n";
        }
    }
}?>

<style>
    span {
        display:block;
        overflow:hidden;
    }
</style>
<form method="post" style="height:100%" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div>
        <span><input type="submit" name="submit" value="Send Message"/></span>
        <label class="header_labels" for="recipient_box" style="float:left;margin-right:5px;">To:</label>
        <span>
            <input name="recipient_box" class="header_fields" type="text" required></input>
        </span>
        <label class="header_labels" for="subject_box" style="float:left;margin-right:5px;">Subject:</label>
        <span>
            <input name="subject_box" class="header_fields" type="text" value="<?php
                // Preserve content in text input if recipient isn't set
                if (isset($_POST["submit"]) && !empty($invalid_emails)) {
                    echo isset($_POST["subject_box"]) ? $_POST["subject_box"] : "";
                }
            ?>"></input>
        </span>
    </div>
    <textarea class="content" name="content"><?php
        // Preserve content in text input if recipient isn't set
        if (isset($_POST["submit"]) && !empty($invalid_emails)) {
            echo isset($_POST["content"]) ? $_POST["content"] : "";
        }?></textarea>
</form>

