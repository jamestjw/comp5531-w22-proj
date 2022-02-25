<?php
session_start();
$_SESSION["current_user"] = null;
session_destroy();
header("Location: index.php");
?>