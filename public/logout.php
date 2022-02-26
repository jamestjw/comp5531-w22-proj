<?php
require_once (dirname(__FILE__)."/../common.php");
maybe_session_start();
$_SESSION["current_user"] = null;
session_destroy();
header("Location: index.php");
?>