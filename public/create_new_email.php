<?php
require_once "../common.php";
require_once "../modules/models/user.php";
require_once "../modules/models/email.php";
require_once "../modules/models/inbox.php";
require_once "../modules/models/sent.php";
print_r($_POST);
?>

<?php
if (isset($_POST["submit"])) {
    if (!empty($_POST["recipient_box"])) {
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
        foreach ($valid_emails as $val) {
            
        }

        // Add entry in sent table for current_user->email

        // Tell user which emails could not be sent due to invalid addresses
        if (!empty($invalid_emails)) {
            // This can probably be done more elegantly
            echo "<br>"."This message could not be sent to the following email addresses as they don't exist internally:"."<br>";
            foreach ($invalid_emails as $inv) {
                echo $inv . "\n";
            }   
        }

    } else {
        echo '<script>alert("Recipient field cannot be empty!")</script>';
    }


}

?>

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
            <input name="recipient_box" class="header_fields" type="text"></input>
        </span>
        <label class="header_labels" for="subject_box" style="float:left;margin-right:5px;">Subject:</label>
        <span>
            <input name="subject_box" class="header_fields" type="text" value="<?php 
                echo isset($_POST["subject_box"]) ? $_POST["subject_box"] : ""; 
            ?>"></input>
        </span>
    </div>
    <textarea class="content" name="content"><?php echo isset($_POST["content"]) ? $_POST["content"] : ""; ?></textarea>

</form>

