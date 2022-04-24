<?php
// James Juan Whei Tan - 40161156
// Christopher Almeida Neves - 27521979
?>
<?php

require_once(dirname(__FILE__)."/../common.php");
maybe_session_start();

// TODO: Delete the record from loggedin with corresponding $_SESSION["AuthKey"] once delete is implemented in record class

$_SESSION = array();
setcookie(session_name(), '', time() - 30000, '/');
session_destroy();
header("Location: index.php");
