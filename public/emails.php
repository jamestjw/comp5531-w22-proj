<?php 
require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php");
require_once "../common.php";
require_once "email_view.php";
include "templates/header.php"; 
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/email.css">
</head>
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

        if(!isset($_SESSION["email_view"])) {
            $_SESSION["email_view"] = "inbox";
        }

        if (isset($_POST["inbox_btn"])) {
            $_SESSION["email_view"] = "inbox";
        } elseif (isset($_POST["sent_btn"])) {
            $_SESSION["email_view"] = "sent";
        } elseif (isset($_POST["create_btn"])) {
            $_SESSION["email_view"] = "create";
        }

        if ($_SESSION["email_view"] == "inbox") {
            include "inbox_view.php";
        } elseif ($_SESSION["email_view"] == "sent") {
            include "inbox_view.php";
        } elseif ($_SESSION["email_view"] == "create") {
            include "create_new_email.php";
        } 
        ?>
    </div>
</html>
