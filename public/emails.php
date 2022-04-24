<?php
// Andréanne Chartrand-Beaudry - 29605991
// Christopher Almeida Neves - 27521979
?>
<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>
<?php
include "templates/header.php";
?>
 <link rel="stylesheet" href="css/email.css">

<div class="menubar">
    <label for="menuform" class="menu_form_label">Folders</label>
    <form name="menuform" method="post" class="menu_form">
        <button name="create_btn" class="menu_item">Create New</button>
        <button name="inbox_btn" class="menu_item">Inbox</button>
        <button name="sent_btn" class="menu_item">Sent</button>
    </form>
</div>
<div class="emailsdisplay">
    <?php
    // Set the default view of the email page to the user's inbox
    if (!isset($_SESSION["email_view"])) {
        $_SESSION["email_view"] = "inbox";
    }
    // Set the email view depending on which menu item is pressed
    if (isset($_POST["inbox_btn"])) {
        $_SESSION["email_view"] = "inbox";
    } elseif (isset($_POST["sent_btn"])) {
        $_SESSION["email_view"] = "sent";
    } elseif (isset($_POST["create_btn"])) {
        $_SESSION["email_view"] = "create";
    }
    // Modify contents of page depending on which button was pressed
    if ($_SESSION["email_view"] == "inbox") {
        include "inbox_sent_view.php";
    } elseif ($_SESSION["email_view"] == "sent") {
        include "inbox_sent_view.php";
    } elseif ($_SESSION["email_view"] == "create") {
        include "create_new_email.php";
    }
    ?>
</div>